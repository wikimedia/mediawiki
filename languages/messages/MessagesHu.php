<?php
/** Hungarian (magyar)
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
 * @author Bean49
 * @author Bennó
 * @author Bináris
 * @author BáthoryPéter
 * @author CERminator
 * @author Cerasus
 * @author Csigabi
 * @author Dani
 * @author Dj
 * @author Dorgan
 * @author Enbéká
 * @author Geitost
 * @author Glanthor Reviol
 * @author Gondnok
 * @author Hunyadym
 * @author Kaganer
 * @author KossuthRad
 * @author Misibacsi
 * @author Nemo bis
 * @author R-Joe
 * @author Samat
 * @author Sucy
 * @author TK-999
 * @author Tacsipacsi
 * @author Terik
 * @author Tgr
 * @author Xbspiro
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
	'Activeusers'               => array( 'Aktív_felhasználók', 'Aktív_szerkesztők' ),
	'Allmessages'               => array( 'Rendszerüzenetek' ),
	'Allpages'                  => array( 'Az_összes_lap_listája' ),
	'Ancientpages'              => array( 'Régóta_nem_változott_szócikkek' ),
	'Badtitle'                  => array( 'Hibás_címek' ),
	'Blankpage'                 => array( 'Üres_lap' ),
	'Block'                     => array( 'Blokkolás' ),
	'Blockme'                   => array( 'Blokkolj' ),
	'Booksources'               => array( 'Könyvforrások' ),
	'BrokenRedirects'           => array( 'Nem_létező_lapra_mutató_átirányítások', 'Hibás_átirányítások' ),
	'Categories'                => array( 'Kategóriák' ),
	'ChangePassword'            => array( 'Jelszócsere' ),
	'ComparePages'              => array( 'Lapok_összehasonlítása' ),
	'Confirmemail'              => array( 'Emailcím_megerősítése' ),
	'Contributions'             => array( 'Szerkesztő_közreműködései' ),
	'CreateAccount'             => array( 'Szerkesztői_fiók_létrehozása', 'Felhasználói_fiók_létrehozása' ),
	'Deadendpages'              => array( 'Zsákutcalapok' ),
	'DeletedContributions'      => array( 'Törölt_szerkesztések' ),
	'Disambiguations'           => array( 'Egyértelműsítő_lapok' ),
	'DoubleRedirects'           => array( 'Kettős_átirányítások', 'Dupla_átirányítások' ),
	'EditWatchlist'             => array( 'Figyelőlista_szerkesztése' ),
	'Emailuser'                 => array( 'E-mail_küldése', 'E-mail_küldése_ezen_szerkesztőnek' ),
	'Export'                    => array( 'Lapok_exportálása' ),
	'Fewestrevisions'           => array( 'Legkevesebbet_szerkesztett_lapok' ),
	'FileDuplicateSearch'       => array( 'Duplikátumok_keresése' ),
	'Filepath'                  => array( 'Fájl_elérési_útja', 'Fájl_elérési_út' ),
	'Import'                    => array( 'Lapok_importálása' ),
	'Invalidateemail'           => array( 'E-mail_cím_érvénytelenítése' ),
	'BlockList'                 => array( 'Blokkolt_IP-címek_listája' ),
	'LinkSearch'                => array( 'Hivatkozás_keresés' ),
	'Listadmins'                => array( 'Adminisztrátorok', 'Adminisztrátorok_listája', 'Sysopok' ),
	'Listbots'                  => array( 'Botok', 'Botok_listája' ),
	'Listfiles'                 => array( 'Fájlok_listája', 'Képek_listája', 'Fájllista', 'Képlista' ),
	'Listgrouprights'           => array( 'Szerkesztői_csoportok_jogai' ),
	'Listredirects'             => array( 'Átirányítások_listája' ),
	'Listusers'                 => array( 'Szerkesztők_listája', 'Szerkesztők', 'Felhasználók' ),
	'Lockdb'                    => array( 'Adatbázis_lezárása' ),
	'Log'                       => array( 'Rendszernaplók', 'Naplók', 'Napló' ),
	'Lonelypages'               => array( 'Árva_lapok', 'Magányos_lapok' ),
	'Longpages'                 => array( 'Hosszú_lapok' ),
	'MergeHistory'              => array( 'Laptörténetek_egyesítése', 'Laptörténet-egyesítés' ),
	'MIMEsearch'                => array( 'Keresés_MIME-típus_alapján' ),
	'Mostcategories'            => array( 'Legtöbb_kategóriába_tartozó_lapok' ),
	'Mostimages'                => array( 'Legtöbbet_használt_fájlok', 'Legtöbbet_használt_képek' ),
	'Mostlinked'                => array( 'Legtöbbet_hivatkozott_lapok' ),
	'Mostlinkedcategories'      => array( 'Legtöbbet_hivatkozott_kategóriák' ),
	'Mostlinkedtemplates'       => array( 'Legtöbbet_hivatkozott_sablonok' ),
	'Mostrevisions'             => array( 'Legtöbbet_szerkesztett_lapok' ),
	'Movepage'                  => array( 'Lap_átnevezése' ),
	'Mycontributions'           => array( 'Közreműködéseim' ),
	'Mypage'                    => array( 'Lapom', 'Userlapom' ),
	'Mytalk'                    => array( 'Vitám', 'Vitalapom', 'Uservitalapom' ),
	'Myuploads'                 => array( 'Saját_feltöltéseim' ),
	'Newimages'                 => array( 'Új_fájlok', 'Új_képek', 'Új_képek_galériája' ),
	'Newpages'                  => array( 'Új_lapok' ),
	'PasswordReset'             => array( 'Jelszó_helyreállítása' ),
	'Popularpages'              => array( 'Népszerű_lapok', 'Népszerű_oldalak' ),
	'Preferences'               => array( 'Beállításaim' ),
	'Prefixindex'               => array( 'Keresés_előtag_szerint' ),
	'Protectedpages'            => array( 'Védett_lapok' ),
	'Protectedtitles'           => array( 'Védett_címek' ),
	'Randompage'                => array( 'Lap_találomra' ),
	'Randomredirect'            => array( 'Átirányítás_találomra' ),
	'Recentchanges'             => array( 'Friss_változtatások' ),
	'Recentchangeslinked'       => array( 'Kapcsolódó_változtatások' ),
	'Revisiondelete'            => array( 'Változat_törlése' ),
	'Search'                    => array( 'Keresés' ),
	'Shortpages'                => array( 'Rövid_lapok' ),
	'Specialpages'              => array( 'Speciális_lapok' ),
	'Statistics'                => array( 'Statisztika', 'Statisztikák' ),
	'Tags'                      => array( 'Címkék' ),
	'Unblock'                   => array( 'Blokkolás_feloldása' ),
	'Uncategorizedcategories'   => array( 'Kategorizálatlan_kategóriák' ),
	'Uncategorizedimages'       => array( 'Kategorizálatlan_fájlok', 'Kategorizálatlan_képek' ),
	'Uncategorizedpages'        => array( 'Kategorizálatlan_lapok' ),
	'Uncategorizedtemplates'    => array( 'Kategorizálatlan_sablonok' ),
	'Undelete'                  => array( 'Törölt_lapváltozatok_visszaállítása' ),
	'Unlockdb'                  => array( 'Adatbázis_lezárás_feloldása' ),
	'Unusedcategories'          => array( 'Nem_használt_kategóriák' ),
	'Unusedimages'              => array( 'Nem_használt_képek' ),
	'Unusedtemplates'           => array( 'Nem_használt_sablonok' ),
	'Unwatchedpages'            => array( 'Nem_figyelt_lapok' ),
	'Upload'                    => array( 'Feltöltés' ),
	'Userlogin'                 => array( 'Belépés' ),
	'Userlogout'                => array( 'Kilépés' ),
	'Userrights'                => array( 'Szerkesztők_jogai', 'Szerkesztői_jogok', 'Szerkesztőjogok', 'Szerkesztő_jogai' ),
	'Version'                   => array( 'Névjegy', 'Verziószám', 'Verzió' ),
	'Wantedcategories'          => array( 'Keresett_kategóriák' ),
	'Wantedfiles'               => array( 'Keresett_fájlok' ),
	'Wantedpages'               => array( 'Keresett_lapok' ),
	'Wantedtemplates'           => array( 'Keresett_sablonok' ),
	'Watchlist'                 => array( 'Figyelőlistám' ),
	'Whatlinkshere'             => array( 'Mi_hivatkozik_erre' ),
	'Withoutinterwiki'          => array( 'Nyelvközi_hivatkozás_nélküli_lapok', 'Wikiközi_hivatkozás_nélküli_lapok', 'Interwikilinkek_nélküli_lapok' ),
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
	'redirect'                  => array( '0', '#ÁTIRÁNYÍTÁS', '#REDIRECT' ),
	'notoc'                     => array( '0', '__NINCSTARTALOMJEGYZÉK__', '__NINCSTJ__', '__NOTOC__' ),
	'nogallery'                 => array( '0', '__NINCSGALÉRIA__', '__NOGALLERY__' ),
	'forcetoc'                  => array( '0', '__LEGYENTARTALOMJEGYZÉK__', '__LEGYENTJ__', '__FORCETOC__' ),
	'toc'                       => array( '0', '__TARTALOMJEGYZÉK__', '__TJ__', '__TOC__' ),
	'noeditsection'             => array( '0', '__NINCSSZERKESZTÉS__', '__NINCSSZERK__', '__NOEDITSECTION__' ),
	'currentmonth'              => array( '1', 'HÓNAP', 'CURRENTMONTH', 'CURRENTMONTH2' ),
	'currentmonth1'             => array( '1', 'HÓNAP1', 'CURRENTMONTH1' ),
	'currentmonthname'          => array( '1', 'HÓNAPNEVE', 'CURRENTMONTHNAME' ),
	'currentmonthabbrev'        => array( '1', 'HÓNAPRÖVID', 'CURRENTMONTHABBREV' ),
	'currentday'                => array( '1', 'MAINAP', 'CURRENTDAY' ),
	'currentday2'               => array( '1', 'MAINAP2', 'CURRENTDAY2' ),
	'currentdayname'            => array( '1', 'MAINAPNEVE', 'CURRENTDAYNAME' ),
	'currentyear'               => array( '1', 'ÉV', 'CURRENTYEAR' ),
	'currenttime'               => array( '1', 'IDŐ', 'CURRENTTIME' ),
	'currenthour'               => array( '1', 'ÓRA', 'CURRENTHOUR' ),
	'localmonth'                => array( '1', 'HELYIHÓNAP', 'LOCALMONTH', 'LOCALMONTH2' ),
	'localmonth1'               => array( '1', 'HELYIHÓNAP1', 'LOCALMONTH1' ),
	'localmonthname'            => array( '1', 'HELYIHÓNAPNÉV', 'LOCALMONTHNAME' ),
	'localmonthabbrev'          => array( '1', 'HELYIHÓNAPRÖVIDÍTÉS', 'LOCALMONTHABBREV' ),
	'localday'                  => array( '1', 'HELYINAP', 'LOCALDAY' ),
	'localday2'                 => array( '1', 'HELYINAP2', 'LOCALDAY2' ),
	'localdayname'              => array( '1', 'HELYINAPNEVE', 'LOCALDAYNAME' ),
	'localyear'                 => array( '1', 'HELYIÉV', 'LOCALYEAR' ),
	'localtime'                 => array( '1', 'HELYIIDŐ', 'LOCALTIME' ),
	'localhour'                 => array( '1', 'HELYIÓRA', 'LOCALHOUR' ),
	'numberofpages'             => array( '1', 'OLDALAKSZÁMA', 'LAPOKSZÁMA', 'NUMBEROFPAGES' ),
	'numberofarticles'          => array( '1', 'SZÓCIKKEKSZÁMA', 'NUMBEROFARTICLES' ),
	'numberoffiles'             => array( '1', 'FÁJLOKSZÁMA', 'KÉPEKSZÁMA', 'NUMBEROFFILES' ),
	'numberofusers'             => array( '1', 'SZERKESZTŐKSZÁMA', 'NUMBEROFUSERS' ),
	'numberofactiveusers'       => array( '1', 'AKTÍVSZERKESZTŐKSZÁMA', 'NUMBEROFACTIVEUSERS' ),
	'numberofedits'             => array( '1', 'SZERKESZTÉSEKSZÁMA', 'NUMBEROFEDITS' ),
	'numberofviews'             => array( '1', 'MEGTEKINTÉSEKSZÁMA', 'NUMBEROFVIEWS' ),
	'pagename'                  => array( '1', 'OLDALNEVE', 'PAGENAME' ),
	'pagenamee'                 => array( '1', 'OLDALNEVEE', 'PAGENAMEE' ),
	'namespace'                 => array( '1', 'NÉVTERE', 'NAMESPACE' ),
	'namespacee'                => array( '1', 'NÉVTEREE', 'NAMESPACEE' ),
	'talkspace'                 => array( '1', 'VITATERE', 'TALKSPACE' ),
	'talkspacee'                => array( '1', 'VITATEREE', 'TALKSPACEE' ),
	'subjectspace'              => array( '1', 'SZÓCIKKNÉVTERE', 'SUBJECTSPACE', 'ARTICLESPACE' ),
	'subjectspacee'             => array( '1', 'SZÓCIKKNÉVTEREE', 'SUBJECTSPACEE', 'ARTICLESPACEE' ),
	'fullpagename'              => array( '1', 'LAPTELJESNEVE', 'FULLPAGENAME' ),
	'fullpagenamee'             => array( '1', 'LAPTELJESNEVEE', 'FULLPAGENAMEE' ),
	'subpagename'               => array( '1', 'ALLAPNEVE', 'SUBPAGENAME' ),
	'subpagenamee'              => array( '1', 'ALLAPNEVEE', 'SUBPAGENAMEE' ),
	'basepagename'              => array( '1', 'ALAPLAPNEVE', 'BASEPAGENAME' ),
	'basepagenamee'             => array( '1', 'ALAPLAPNEVEE', 'BASEPAGENAMEE' ),
	'talkpagename'              => array( '1', 'VITALAPNEVE', 'TALKPAGENAME' ),
	'talkpagenamee'             => array( '1', 'VITALAPNEVEE', 'TALKPAGENAMEE' ),
	'subjectpagename'           => array( '1', 'SZÓCIKKNEVE', 'SUBJECTPAGENAME', 'ARTICLEPAGENAME' ),
	'subjectpagenamee'          => array( '1', 'SZÓCIKKNEVEE', 'SUBJECTPAGENAMEE', 'ARTICLEPAGENAMEE' ),
	'msg'                       => array( '0', 'ÜZENET:', 'ÜZ:', 'MSG:' ),
	'subst'                     => array( '0', 'BEILLESZT:', 'BEMÁSOL:', 'SUBST:' ),
	'img_thumbnail'             => array( '1', 'bélyegkép', 'bélyeg', 'miniatűr', 'thumbnail', 'thumb' ),
	'img_manualthumb'           => array( '1', 'bélyegkép=$1', 'bélyeg=$1', 'miniatűr=$1', 'thumbnail=$1', 'thumb=$1' ),
	'img_right'                 => array( '1', 'jobb', 'jobbra', 'right' ),
	'img_left'                  => array( '1', 'bal', 'balra', 'left' ),
	'img_none'                  => array( '1', 'semmi', 'none' ),
	'img_center'                => array( '1', 'közép', 'középre', 'center', 'centre' ),
	'img_framed'                => array( '1', 'keretezett', 'keretes', 'keretben', 'kerettel', 'framed', 'enframed', 'frame' ),
	'img_frameless'             => array( '1', 'keretnélküli', 'frameless' ),
	'img_page'                  => array( '1', 'oldal=$1', 'oldal $1', 'page=$1', 'page $1' ),
	'img_upright'               => array( '1', 'fennjobbra', 'fennjobbra=$1', 'fennjobbra $1', 'upright', 'upright=$1', 'upright $1' ),
	'img_border'                => array( '1', 'keret', 'border' ),
	'img_baseline'              => array( '1', 'alapvonal', 'baseline' ),
	'img_sub'                   => array( '1', 'ai', 'alsóindex', 'sub' ),
	'img_super'                 => array( '1', 'fi', 'felsőindex', 'super', 'sup' ),
	'img_top'                   => array( '1', 'fenn', 'fent', 'top' ),
	'img_text_top'              => array( '1', 'szöveg-fenn', 'szöveg-fent', 'text-top' ),
	'img_middle'                => array( '1', 'vközépen', 'vközépre', 'middle' ),
	'img_bottom'                => array( '1', 'lenn', 'lent', 'bottom' ),
	'img_text_bottom'           => array( '1', 'szöveg-lenn', 'szöveg-lent', 'text-bottom' ),
	'sitename'                  => array( '1', 'WIKINEVE', 'SITENAME' ),
	'ns'                        => array( '0', 'NÉVTÉR:', 'NS:' ),
	'localurl'                  => array( '0', 'HELYIURL:', 'LOCALURL:' ),
	'localurle'                 => array( '0', 'HELYIURLE:', 'LOCALURLE:' ),
	'server'                    => array( '0', 'SZERVER', 'KISZOLGÁLÓ', 'SERVER' ),
	'servername'                => array( '0', 'SZERVERNEVE', 'KISZOLGÁLÓNEVE', 'SERVERNAME' ),
	'grammar'                   => array( '0', 'NYELVTAN:', 'GRAMMAR:' ),
	'currentweek'               => array( '1', 'HÉT', 'CURRENTWEEK' ),
	'currentdow'                => array( '1', 'HÉTNAPJA', 'CURRENTDOW' ),
	'localweek'                 => array( '1', 'HELYIHÉT', 'LOCALWEEK' ),
	'localdow'                  => array( '1', 'HELYIHÉTNAPJA', 'LOCALDOW' ),
	'revisionid'                => array( '1', 'VÁLTOZATAZON', 'VÁLTOZATAZONOSÍTÓ', 'REVISIONID' ),
	'revisionday'               => array( '1', 'VÁLTOZATNAPJA', 'REVISIONDAY' ),
	'revisionday2'              => array( '1', 'VÁLTOZATNAPJA2', 'REVISIONDAY2' ),
	'revisionmonth'             => array( '1', 'VÁLTOZATHÓNAPJA', 'REVISIONMONTH' ),
	'revisionyear'              => array( '1', 'VÁLTOZATÉVE', 'REVISIONYEAR' ),
	'revisiontimestamp'         => array( '1', 'VÁLTOZATIDŐBÉLYEG', 'VÁLTOZATIDEJE', 'REVISIONTIMESTAMP' ),
	'revisionuser'              => array( '1', 'VÁLTOZATSZERKESZTŐJE', 'REVISIONUSER' ),
	'plural'                    => array( '0', 'TÖBBESSZÁM:', 'PLURAL:' ),
	'fullurl'                   => array( '0', 'TELJESURL:', 'FULLURL:' ),
	'fullurle'                  => array( '0', 'TELJESURLE:', 'FULLURLE:' ),
	'lcfirst'                   => array( '0', 'KISKEZDŐ:', 'KISKEZDŐBETŰ:', 'LCFIRST:' ),
	'ucfirst'                   => array( '0', 'NAGYKEZDŐ:', 'NAGYKEZDŐBETŰ:', 'UCFIRST:' ),
	'lc'                        => array( '0', 'KISBETŰ:', 'KISBETŰK:', 'KB:', 'KISBETŰS:', 'LC:' ),
	'uc'                        => array( '0', 'NAGYBETŰ:', 'NAGYBETŰK', 'NB:', 'NAGYBETŰS:', 'UC:' ),
	'displaytitle'              => array( '1', 'MEGJELENÍTENDŐCÍM', 'CÍM', 'DISPLAYTITLE' ),
	'newsectionlink'            => array( '1', '__ÚJSZAKASZHIV__', '__ÚJSZAKASZLINK__', '__NEWSECTIONLINK__' ),
	'nonewsectionlink'          => array( '1', '__NINCSÚJSZAKASZHIV__', '__NINCSÚJSZAKASZLINK__', '__NONEWSECTIONLINK__' ),
	'currentversion'            => array( '1', 'JELENLEGIVÁLTOZAT', 'CURRENTVERSION' ),
	'urlencode'                 => array( '0', 'URLKÓDOLVA:', 'URLENCODE:' ),
	'anchorencode'              => array( '0', 'HORGONYKÓDOLVA', 'ANCHORENCODE' ),
	'currenttimestamp'          => array( '1', 'IDŐBÉLYEG', 'CURRENTTIMESTAMP' ),
	'localtimestamp'            => array( '1', 'HELYIIDŐBÉLYEG', 'LOCALTIMESTAMP' ),
	'directionmark'             => array( '1', 'IRÁNYJELZŐ', 'DIRECTIONMARK', 'DIRMARK' ),
	'language'                  => array( '0', '#NYELV:', '#LANGUAGE:' ),
	'contentlanguage'           => array( '1', 'TARTALOMNYELVE', 'TARTNYELVE', 'CONTENTLANGUAGE', 'CONTENTLANG' ),
	'pagesinnamespace'          => array( '1', 'OLDALAKNÉVTÉRBEN:', 'OLDALAKNBEN:', 'PAGESINNAMESPACE:', 'PAGESINNS:' ),
	'numberofadmins'            => array( '1', 'ADMINOKSZÁMA', 'NUMBEROFADMINS' ),
	'formatnum'                 => array( '0', 'FORMÁZOTTSZÁM', 'SZÁMFORMÁZÁS', 'SZÁMFORM', 'FORMATNUM' ),
	'special'                   => array( '0', 'speciális', 'special' ),
	'defaultsort'               => array( '1', 'RENDEZÉS:', 'KULCS:', 'DEFAULTSORT:', 'DEFAULTSORTKEY:', 'DEFAULTCATEGORYSORT:' ),
	'filepath'                  => array( '0', 'ELÉRÉSIÚT:', 'FILEPATH:' ),
	'hiddencat'                 => array( '1', '__REJTETTKAT__', '__REJTETTKATEGÓRIA__', '__HIDDENCAT__' ),
	'pagesincategory'           => array( '1', 'LAPOKAKATEGÓRIÁBAN', 'PAGESINCATEGORY', 'PAGESINCAT' ),
	'pagesize'                  => array( '1', 'LAPMÉRET', 'PAGESIZE' ),
	'noindex'                   => array( '1', '__NINCSINDEX__', '__NOINDEX__' ),
	'numberingroup'             => array( '1', 'CSOPORTTAGOK', 'NUMBERINGROUP', 'NUMINGROUP' ),
	'staticredirect'            => array( '1', '__ÁLLANDÓÁTIRÁNYÍTÁS__', '__STATIKUSÁTIRÁNYÍTÁS__', '__STATICREDIRECT__' ),
	'protectionlevel'           => array( '1', 'VÉDELMISZINT', 'PROTECTIONLEVEL' ),
	'formatdate'                => array( '0', 'dátumformázás', 'formatdate', 'dateformat' ),
);

$linkTrail = '/^([a-záéíóúöüőűÁÉÍÓÚÖÜŐŰ]+)(.*)$/sDu';

$messages = array(
# User preference toggles
'tog-underline' => 'Hivatkozások aláhúzása:',
'tog-justify' => 'Bekezdések sorkizárása',
'tog-hideminor' => 'Apró változtatások elrejtése a friss változtatások lapon',
'tog-hidepatrolled' => 'Az ellenőrzött szerkesztések elrejtése a friss változtatások lapon',
'tog-newpageshidepatrolled' => 'Ellenőrzött lapok elrejtése az új lapok listájáról',
'tog-extendwatchlist' => 'A figyelőlistán az összes változtatás látszódjon, ne csak az utolsó',
'tog-usenewrc' => 'Szerkesztések csoportosítása oldal szerint a friss változtatásokban és a figyelőlistán',
'tog-numberheadings' => 'Fejezetcímek automatikus számozása',
'tog-showtoolbar' => 'Szerkesztőeszközsor megjelenítése',
'tog-editondblclick' => 'A lapok szerkesztése dupla kattintásra',
'tog-editsection' => '[szerkesztés] linkek az egyes szakaszok szerkesztéséhez',
'tog-editsectiononrightclick' => 'Szakaszok szerkesztése a szakaszcímre való jobb kattintással',
'tog-showtoc' => 'Tartalomjegyzék megjelenítése a három fejezetnél többel rendelkező cikkeknél',
'tog-rememberpassword' => 'Emlékezzen rám ezzel a böngészővel (legfeljebb {{PLURAL:$1|egy|$1}} napig)',
'tog-watchcreations' => 'Az általam létrehozott lapok és feltöltött fájlok felvétele a figyelőlistámra',
'tog-watchdefault' => 'Az általam szerkesztett lapok és fájlok felvétele a figyelőlistámra',
'tog-watchmoves' => 'Az általam átnevezett lapok és fájlok felvétele a figyelőlistámra',
'tog-watchdeletion' => 'Az általam törölt lapok és fájlok felvétele a figyelőlistámra',
'tog-minordefault' => 'Alapértelmezetten minden szerkesztésemet jelölje aprónak',
'tog-previewontop' => 'Előnézet megjelenítése a szerkesztőablak előtt',
'tog-previewonfirst' => 'Előnézet első szerkesztésnél',
'tog-nocache' => 'A lapok gyorstárazásának letiltása a böngészőben',
'tog-enotifwatchlistpages' => 'Kapjak értesítést e-mailben, ha egy általam figyelt lap vagy fájl megváltozik',
'tog-enotifusertalkpages' => 'Kapjak értesítést e-mailben, ha megváltozik a vitalapom',
'tog-enotifminoredits' => 'Kapjak értesítést e-mailben a lapok és fájlok apró változtatásairól',
'tog-enotifrevealaddr' => 'Jelenjen meg az e-mail címem a figyelmeztető e-mailekben',
'tog-shownumberswatching' => 'A lapot figyelő szerkesztők számának megjelenítése',
'tog-oldsig' => 'A jelenlegi aláírás:',
'tog-fancysig' => 'Az aláírás wikiszöveg (nem lesz automatikusan hivatkozásba rakva)',
'tog-uselivepreview' => 'Élő előnézet használata (kísérleti)',
'tog-forceeditsummary' => 'Figyelmeztessen, ha nem adok meg szerkesztési összefoglalót',
'tog-watchlisthideown' => 'Saját szerkesztések elrejtése',
'tog-watchlisthidebots' => 'Robotok szerkesztéseinek elrejtése',
'tog-watchlisthideminor' => 'Apró változtatások elrejtése',
'tog-watchlisthideliu' => 'Bejelentkezett szerkesztők módosításainak elrejtése a figyelőlistáról',
'tog-watchlisthideanons' => 'Névtelen szerkesztések elrejtése',
'tog-watchlisthidepatrolled' => 'Az ellenőrzött szerkesztések elrejtése',
'tog-ccmeonemails' => 'A másoknak küldött e-mailjeimről kapjak másolatot',
'tog-diffonly' => 'Ne mutassa a lap tartalmát a lapváltozatok közötti eltérések megtekintésekor',
'tog-showhiddencats' => 'Rejtett kategóriák megjelenítése',
'tog-norollbackdiff' => 'Ne jelenjenek meg az eltérések visszaállítás után',
'tog-useeditwarning' => 'Figyelmeztessen, ha szerkesztéskor a módosítások mentése nélkül akarom elhagyni a lapot',
'tog-prefershttps' => 'Mindig biztonságos kapcsolatot használjon, amikor be vagyok jelentkezve',

'underline-always' => 'mindig',
'underline-never' => 'soha',
'underline-default' => 'Felület és böngésző alapértelmezése szerint',

# Font style option in Special:Preferences
'editfont-style' => 'A szerkesztőterület betűtípusa:',
'editfont-default' => 'a böngésző alapértelmezett beállítása',
'editfont-monospace' => 'fix szélességű betűtípus',
'editfont-sansserif' => 'talpatlan (sans-serif) betűtípus',
'editfont-serif' => 'talpas (serif) betűtípus',

# Dates
'sunday' => 'vasárnap',
'monday' => 'hétfő',
'tuesday' => 'kedd',
'wednesday' => 'szerda',
'thursday' => 'csütörtök',
'friday' => 'péntek',
'saturday' => 'szombat',
'sun' => 'vas',
'mon' => 'hét',
'tue' => 'kedd',
'wed' => 'sze',
'thu' => 'csü',
'fri' => 'pén',
'sat' => 'szo',
'january' => 'január',
'february' => 'február',
'march' => 'március',
'april' => 'április',
'may_long' => 'május',
'june' => 'június',
'july' => 'július',
'august' => 'augusztus',
'september' => 'szeptember',
'october' => 'október',
'november' => 'november',
'december' => 'december',
'january-gen' => 'január',
'february-gen' => 'február',
'march-gen' => 'március',
'april-gen' => 'április',
'may-gen' => 'május',
'june-gen' => 'június',
'july-gen' => 'július',
'august-gen' => 'augusztus',
'september-gen' => 'szeptember',
'october-gen' => 'október',
'november-gen' => 'november',
'december-gen' => 'december',
'jan' => 'jan',
'feb' => 'febr',
'mar' => 'márc',
'apr' => 'ápr',
'may' => 'máj',
'jun' => 'jún',
'jul' => 'júl',
'aug' => 'aug',
'sep' => 'szept',
'oct' => 'okt',
'nov' => 'nov',
'dec' => 'dec',
'january-date' => 'Január $1',
'february-date' => 'Február $1',
'march-date' => 'Március $1',
'april-date' => 'Április $1',
'may-date' => 'Május $1',
'june-date' => 'Június $1',
'july-date' => 'Július $1',
'august-date' => 'Augusztus $1',
'september-date' => 'Szeptember $1',
'october-date' => 'Október $1',
'november-date' => 'November $1',
'december-date' => 'December $1',

# Categories related messages
'pagecategories' => '{{PLURAL:$1|Kategória|Kategória}}',
'category_header' => 'A(z) „$1” kategóriába tartozó lapok',
'subcategories' => 'Alkategóriák',
'category-media-header' => 'A(z) „$1” kategóriába tartozó médiafájlok',
'category-empty' => "''Ebben a kategóriában pillanatnyilag egyetlen lap vagy médiafájl sem szerepel.''",
'hidden-categories' => '{{PLURAL:$1|Rejtett kategória|Rejtett kategória}}',
'hidden-category-category' => 'Rejtett kategóriák',
'category-subcat-count' => "''{{PLURAL:$2|Ennek a kategóriának csak egyetlen alkategóriája van.|Ez a kategória az alábbi {{PLURAL:$1|alkategóriával|$1 alkategóriával}} rendelkezik (összesen $2 alkategóriája van).}}''",
'category-subcat-count-limited' => 'Ebben a kategóriában {{PLURAL:$1|egy|$1}} alkategória található.',
'category-article-count' => '{{PLURAL:$2|A kategóriában csak a következő lap található.|A következő $1 lap található a kategóriában, összesen $2 lapból.}}',
'category-article-count-limited' => 'Ebben a kategóriában a következő {{PLURAL:$1|lap|$1 lap}} található:',
'category-file-count' => '{{PLURAL:$2|Csak a következő fájl található ebben a kategóriában.|Az összesen $2 fájlból a következő $1-t listázza ez a kategórialap, a többi a további oldalakon található.}}',
'category-file-count-limited' => 'Ebben a kategóriában {{PLURAL:$1|egy|$1}} fájl található.',
'listingcontinuesabbrev' => 'folyt.',
'index-category' => 'Indexelt lapok',
'noindex-category' => 'Nem indexelt lapok',
'broken-file-category' => 'Hibás fájlhivatkozásokat tartalmazó lapok',

'about' => 'Névjegy',
'article' => 'Szócikk',
'newwindow' => '(új ablakban nyílik meg)',
'cancel' => 'Mégse',
'moredotdotdot' => 'Tovább…',
'morenotlisted' => 'A lista nem teljes.',
'mypage' => 'Lapom',
'mytalk' => 'Vitalap',
'anontalk' => 'Az IP-címhez tartozó vitalap',
'navigation' => 'Navigáció',
'and' => '&#32;és',

# Cologne Blue skin
'qbfind' => 'Keresés',
'qbbrowse' => 'Böngészés',
'qbedit' => 'Szerkesztés',
'qbpageoptions' => 'Lapbeállítások',
'qbmyoptions' => 'Lapjaim',
'qbspecialpages' => 'Speciális lapok',
'faq' => 'GyIK',
'faqpage' => 'Project:GyIK',

# Vector skin
'vector-action-addsection' => 'Új téma nyitása',
'vector-action-delete' => 'Törlés',
'vector-action-move' => 'Átnevezés',
'vector-action-protect' => 'Lapvédelem',
'vector-action-undelete' => 'Visszaállítás',
'vector-action-unprotect' => 'Védelem módosítása',
'vector-simplesearch-preference' => 'Egyszerűsített keresési sáv engedélyezése (csak Vector felületen)',
'vector-view-create' => 'Létrehozás',
'vector-view-edit' => 'Szerkesztés',
'vector-view-history' => 'Laptörténet',
'vector-view-view' => 'Olvasás',
'vector-view-viewsource' => 'A lap forrása',
'actions' => 'Műveletek',
'namespaces' => 'Névterek',
'variants' => 'Változatok',

'navigation-heading' => 'Navigációs menü',
'errorpagetitle' => 'Hiba',
'returnto' => 'Vissza a(z) $1 laphoz.',
'tagline' => 'A {{SITENAME}} wikiből',
'help' => 'Segítség',
'search' => 'Keresés',
'searchbutton' => 'Keresés',
'go' => 'Menj',
'searcharticle' => 'Menj',
'history' => 'Laptörténet',
'history_short' => 'Laptörténet',
'updatedmarker' => 'az utolsó látogatásom óta frissítették',
'printableversion' => 'Nyomtatható változat',
'permalink' => 'Hivatkozás erre a változatra',
'print' => 'Nyomtatás',
'view' => 'Olvasás',
'edit' => 'Szerkesztés',
'create' => 'Létrehozás',
'editthispage' => 'Lap szerkesztése',
'create-this-page' => 'Oldal létrehozása',
'delete' => 'Törlés',
'deletethispage' => 'Lap törlése',
'undeletethispage' => 'Lap helyreállítása',
'undelete_short' => '{{PLURAL:$1|Egy|$1}} szerkesztés helyreállítása',
'viewdeleted_short' => '{{PLURAL:$1|Egy|$1}} törölt szerkesztés megtekintése',
'protect' => 'Lapvédelem',
'protect_change' => 'módosítás',
'protectthispage' => 'Lapvédelem',
'unprotect' => 'Védelem módosítása',
'unprotectthispage' => 'A lap védelmének módosítása',
'newpage' => 'Új lap',
'talkpage' => 'A lappal kapcsolatos megbeszélés',
'talkpagelinktext' => 'vitalap',
'specialpage' => 'Speciális lap',
'personaltools' => 'Személyes eszközök',
'postcomment' => 'Új szakasz',
'articlepage' => 'Szócikk megtekintése',
'talk' => 'Vitalap',
'views' => 'Nézetek',
'toolbox' => 'Eszközök',
'userpage' => 'Felhasználó lapjának megtekintése',
'projectpage' => 'Projektlap megtekintése',
'imagepage' => 'A fájl leírólapjának megtekintése',
'mediawikipage' => 'Üzenetlap megtekintése',
'templatepage' => 'Sablon lapjának megtekintése',
'viewhelppage' => 'Súgólap megtekintése',
'categorypage' => 'Kategórialap megtekintése',
'viewtalkpage' => 'Beszélgetés megtekintése',
'otherlanguages' => 'Más nyelveken',
'redirectedfrom' => '($1 szócikkből átirányítva)',
'redirectpagesub' => 'Átirányító lap',
'lastmodifiedat' => 'A lap utolsó módosítása: $1, $2',
'viewcount' => 'Ezt a lapot {{PLURAL:$1|egy|$1}} alkalommal keresték fel.',
'protectedpage' => 'Védett lap',
'jumpto' => 'Ugrás:',
'jumptonavigation' => 'navigáció',
'jumptosearch' => 'keresés',
'view-pool-error' => 'A szerverek jelenleg túl vannak terhelve, mert túl sok felhasználó próbálta megtekinteni ezt az oldalt.
Kérjük, várj egy kicsit, mielőtt újra próbálkoznál a lap megtekintésével!

$1',
'pool-timeout' => 'Letelt a zárolás feloldására szánt várakozási idő',
'pool-queuefull' => 'A pool sor megtelt',
'pool-errorunknown' => 'Ismeretlen hiba',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage).
'aboutsite' => 'A {{SITENAME}} wikiről',
'aboutpage' => 'Project:Rólunk',
'copyright' => 'A tartalom további jelölés hiányában a(z) $1 feltételei szerint használható fel.',
'copyrightpage' => '{{ns:project}}:Szerzői jogok',
'currentevents' => 'Aktuális események',
'currentevents-url' => 'Project:Friss események',
'disclaimers' => 'Jogi nyilatkozat',
'disclaimerpage' => 'Project:Jogi nyilatkozat',
'edithelp' => 'Szerkesztési segítség',
'helppage' => 'Help:Tartalom',
'mainpage' => 'Kezdőlap',
'mainpage-description' => 'Kezdőlap',
'policy-url' => 'Project:Irányelvek',
'portal' => 'Közösségi portál',
'portal-url' => 'Project:Közösségi portál',
'privacy' => 'Adatvédelmi irányelvek',
'privacypage' => 'Project:Adatvédelmi irányelvek',

'badaccess' => 'Engedélyezési hiba',
'badaccess-group0' => 'Ezt a tevékenységet nem végezheted el.',
'badaccess-groups' => 'Ezt a tevékenységet csak a(z) $1 {{PLURAL:$2|csoportba|csoportok valamelyikébe}} tartozó felhasználó végezheti el.',

'versionrequired' => 'A MediaWiki $1 verziója szükséges',
'versionrequiredtext' => 'A lap használatához a MediaWiki $1 verziójára van szükség.
További információkat a [[Special:Version|verzióinformációs lapon]] találsz.',

'ok' => 'OK',
'retrievedfrom' => 'A lap eredeti címe: „$1”',
'youhavenewmessages' => '$1 a vitalapodon! ($2 külön is megtekintheted.)',
'newmessageslink' => 'új üzenet vár',
'newmessagesdifflink' => 'az utolsó üzenetet',
'youhavenewmessagesfromusers' => '$2 kaptál {{PLURAL:$3|egy|$3}} szerkesztőtől $1!',
'youhavenewmessagesmanyusers' => '$2 kaptál több szerkesztőtől $1.',
'newmessageslinkplural' => '{{PLURAL:$1||}}a vitalapodon',
'newmessagesdifflinkplural' => '{{PLURAL:$1|Új üzenetet|Új üzeneteket}}',
'youhavenewmessagesmulti' => 'Új üzenet vár a(z) $1 wikin',
'editsection' => 'szerkesztés',
'editold' => 'szerkesztés',
'viewsourceold' => 'lapforrás',
'editlink' => 'szerkesztés',
'viewsourcelink' => 'forráskód megtekintése',
'editsectionhint' => 'Szakasz szerkesztése: $1',
'toc' => 'Tartalomjegyzék',
'showtoc' => 'megjelenítés',
'hidetoc' => 'elrejtés',
'collapsible-collapse' => 'becsuk',
'collapsible-expand' => 'kinyit',
'thisisdeleted' => '$1 megtekintése vagy helyreállítása?',
'viewdeleted' => '$1 megtekintése?',
'restorelink' => '{{PLURAL:$1|Egy|$1}} törölt szerkesztés',
'feedlinks' => 'Hírcsatorna:',
'feed-invalid' => 'A figyelt hírcsatorna típusa érvénytelen.',
'feed-unavailable' => 'Nincs elérhető hírcsatorna',
'site-rss-feed' => '$1 RSS-hírcsatorna',
'site-atom-feed' => '$1 Atom-hírcsatorna',
'page-rss-feed' => '„$1” RSS-hírcsatorna',
'page-atom-feed' => '„$1” Atom-hírcsatorna',
'feed-rss' => 'RSS',
'red-link-title' => '$1 (a lap nem létezik)',
'sort-descending' => 'Csökkenő sorrend',
'sort-ascending' => 'Növekvő sorrend',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main' => 'Szócikk',
'nstab-user' => 'Felhasználói lap',
'nstab-media' => 'Média',
'nstab-special' => 'Speciális lap',
'nstab-project' => 'Projektlap',
'nstab-image' => 'Fájl',
'nstab-mediawiki' => 'Üzenet',
'nstab-template' => 'Sablon',
'nstab-help' => 'Segítség',
'nstab-category' => 'Kategória',

# Main script and global functions
'nosuchaction' => 'Nincs ilyen művelet',
'nosuchactiontext' => 'Az URL-ben megadott művelet érvénytelen.
Valószínűleg elgépelted vagy hibás hivatkozásra kattintottál.
Az is előfordulhat, hogy a(z) {{SITENAME}} wiki szoftverében hiba található.',
'nosuchspecialpage' => 'Nem létezik ilyen speciális lap',
'nospecialpagetext' => '<strong>Érvénytelen speciális lapot akartál megtekinteni.</strong>

Az érvényes speciális lapok listáját a [[Special:SpecialPages|{{int:specialpages}}]] oldalon találod.',

# General errors
'error' => 'Hiba',
'databaseerror' => 'Adatbázishiba',
'databaseerror-text' => 'Hiba történt az adatbázis-lekérdezés során. Lehetséges, hogy ez egy szoftverhiba eredménye.',
'databaseerror-textcl' => 'Hiba történt az adatbázis-lekérdezés során.',
'databaseerror-query' => 'Lekérdezés: $1',
'databaseerror-function' => 'Függvény: $1',
'databaseerror-error' => 'Hiba: $1',
'laggedslavemode' => "'''Figyelem:''' Ez a lap nem feltétlenül tartalmazza a legfrissebb változtatásokat!",
'readonly' => 'Az adatbázis le van zárva',
'enterlockreason' => 'Add meg a lezárás okát, valamint egy becslést, hogy mikor lesz a lezárásnak vége',
'readonlytext' => 'A wiki adatbázisa ideiglenesen le van zárva (valószínűleg adatbázis-karbantartás miatt). A lezárás időtartama alatt a lapok nem szerkeszthetők, és új szócikkek sem hozhatók létre, az oldalakat azonban lehet böngészni.

Az adminisztrátor, aki lezárta az adatbázist, az alábbi indoklást adta: $1',
'missing-article' => 'Az adatbázisban nem található meg a(z) „$1” című lap szövege $2.

Ennek az oka általában az, hogy egy olyan lapra vonatkozó linket követtél, amit már töröltek.

Ha ez nem így van, lehet, hogy hibát találtál a szoftverben.
Jelezd ezt egy [[Special:ListUsers/sysop|adminiszttrátornak]] az URL megadásával.',
'missingarticle-rev' => '(változat azonosítója: $1)',
'missingarticle-diff' => '(eltérés: $1, $2)',
'readonly_lag' => 'Az adatbázis automatikusan le lett zárva, amíg a mellékkiszolgálók utolérik a főkiszolgálót.',
'internalerror' => 'Belső hiba',
'internalerror_info' => 'Belső hiba: $1',
'fileappenderrorread' => 'A(z) „$1” nem olvasható hozzáírás közben.',
'fileappenderror' => 'Nem sikerült hozzáfűzni a(z) „$1” fájlt a(z) „$2” fájlhoz.',
'filecopyerror' => 'Nem tudtam átmásolni a(z) „$1” fájlt „$2” névre.',
'filerenameerror' => 'Nem tudtam átnevezni a(z) „$1” fájlt „$2” névre.',
'filedeleteerror' => 'Nem tudtam törölni a(z) „$1” fájlt.',
'directorycreateerror' => 'Nem tudtam létrehozni a(z) „$1” könyvtárat.',
'filenotfound' => 'A(z) „$1” fájl nem található.',
'fileexistserror' => 'Nem tudtam írni a(z) „$1” fájlba: a fájl már létezik',
'unexpected' => 'Váratlan érték: „$1”=„$2”.',
'formerror' => 'Hiba: nem tudom elküldeni az űrlapot',
'badarticleerror' => 'Ez a tevékenység nem végezhető el ezen a lapon.',
'cannotdelete' => 'A(z) $1 lapot vagy fájlt nem lehet törölni.
Talán már valaki más törölte.',
'cannotdelete-title' => 'Nem lehet törölni a(z) „$1” lapot',
'delete-hook-aborted' => 'A törlés meg lett szakítva egy hook által.
Nem lett magyarázat csatolva.',
'no-null-revision' => 'Nem sikerült új null-revíziót létrehozni a(z) „$1” lap számára.',
'badtitle' => 'Hibás cím',
'badtitletext' => 'A kért oldal címe érvénytelen, üres, vagy rosszul hivatkozott nyelvközi vagy wikiközi cím volt. Olyan karaktereket is tartalmazhatott, melyek a címekben nem használhatóak.',
'perfcached' => "Az alábbi adatok gyorsítótárból (''cache''-ből) származnak, és ezért lehetséges, hogy nem a legfrissebb változatot mutatják. Legfeljebb {{PLURAL:$1|egy|$1 }} eredmény áll rendelkezésre a gyorsítótárban.",
'perfcachedts' => "Az alábbi adatok gyorsítótárból (''cache''-ből) származnak, legutóbbi frissítésük ideje $1. Legfeljebb {{PLURAL:$4|egy|$4}} eredmény áll rendelkezésre a gyorsítótárban.",
'querypage-no-updates' => 'Az oldal frissítése jelenleg le van tiltva. Az itt szereplő adatok nem frissülnek azonnal.',
'wrong_wfQuery_params' => 'A wfQuery() függvény paraméterei hibásak<br />
Függvény: $1<br />
Lekérdezés: $2',
'viewsource' => 'Lapforrás',
'viewsource-title' => '$1 forrásának megtekintése',
'actionthrottled' => 'Művelet megszakítva',
'actionthrottledtext' => 'A spamek elleni védekezés miatt nem végezheted el a műveletet túl sokszor egy adott időn belül, és te átlépted a megengedett határt. Próbálkozz újra néhány perc múlva.',
'protectedpagetext' => 'Ez egy védett lap, így nem végezhető rajta szerkesztés és más tevékenység',
'viewsourcetext' => 'Megtekintheted és másolhatod a lap forrását:',
'viewyourtext' => "Megtekintheted és kimásolhatod a '''saját szerkesztéseidet''' az alábbi lapra:",
'protectedinterface' => 'Ez a lap a szoftver felületéhez szolgáltat szöveget, és a visszaélések elkerülése miatt le van zárva.',
'editinginterface' => "'''Vigyázat:''' egy olyan lapot szerkesztesz, ami a MediaWiki szoftver felületéhez tartozik. A lap megváltoztatása hatással lesz a kinézetre, ahogy más szerkesztők látják a lapot. Fordításra inkább használd a MediaWiki fordítására indított kezdeményezést, a [//translatewiki.net/wiki/Main_Page?setlang=hu translatewiki.net-et].",
'cascadeprotected' => 'Ez a lap szerkesztés elleni védelemmel lett ellátva, mert a következő {{PLURAL:$1|lapon|lapokon}} be van kapcsolva a „kaszkádolt” védelem:
$2',
'namespaceprotected' => "Nincs jogosultságod a(z) '''$1''' névtérben található lapok szerkesztésére.",
'customcssprotected' => 'Nem szerkesztheted ezt a CSS-lapot, mert egy másik felhasználó személyes beállításait tartalmazza.',
'customjsprotected' => 'Nem szerkesztheted ezt a JavaScript-lapot, mert egy másik felhasználó személyes beállításait tartalmazza.',
'mycustomcssprotected' => 'Nincs jogod szerkeszteni ezt a CSS lapot.',
'mycustomjsprotected' => 'Nincs jogod szerkeszteni ezt a Javascript lapot.',
'myprivateinfoprotected' => 'Nincs jogod módosítani a privát adataidat.',
'mypreferencesprotected' => 'Nincs jogod módosítani a beállításaidat.',
'ns-specialprotected' => 'A speciális lapok nem szerkeszthetők.',
'titleprotected' => "Ilyen címmel nem lehet szócikket készíteni, [[User:$1|$1]] letiltotta.
Az indoklás: „''$2''”.",
'filereadonlyerror' => 'A(z) "$1" fájl nem módosítható, mert a(z) "$2" fájltároló csak olvasható módban üzemel.

A lezárást végrehajtó rendszergazda az alábbi indoklást adta meg: "$3".',
'invalidtitle-knownnamespace' => 'Érvénytelen cím "$2" névtérrel és "$3" szöveggel',
'invalidtitle-unknownnamespace' => 'Érvénytelen cím az ismeretlen $1 névtérszámmal és "$2" szöveggel',
'exception-nologin' => 'Nem vagy bejelentkezve.',
'exception-nologin-text' => 'Ezen lap vagy művelet használatához be kell jelentkezned erre a wikire.',

# Virus scanner
'virus-badscanner' => "Hibás beállítás: ismeretlen víruskereső: ''$1''",
'virus-scanfailed' => 'az ellenőrzés nem sikerült (hibakód: $1)',
'virus-unknownscanner' => 'ismeretlen antivírus:',

# Login and logout pages
'logouttext' => "'''Sikeresen kijelentkeztél.'''

Lehetséges, hogy néhány oldalon továbbra is azt látod, be vagy jelentkezve, mindaddig, amíg nem üríted a böngésződ gyorsítótárát.",
'welcomeuser' => 'Üdvözlünk, $1!',
'welcomecreation-msg' => 'A felhasználói fiókod elkészült.
Ne felejtsd el módosítani a [[Special:Preferences|{{SITENAME}} beállításaidat]].',
'yourname' => 'Szerkesztőneved:',
'userlogin-yourname' => 'Felhasználónév',
'userlogin-yourname-ph' => 'Add meg a felhasználóneved',
'createacct-another-username-ph' => 'Add meg a felhasználónevet',
'yourpassword' => 'Jelszavad:',
'userlogin-yourpassword' => 'Jelszó',
'userlogin-yourpassword-ph' => 'Add meg a jelszavad',
'createacct-yourpassword-ph' => 'Add meg a jelszavad',
'yourpasswordagain' => 'Jelszavad ismét:',
'createacct-yourpasswordagain' => 'Új jelszó megerősítése',
'createacct-yourpasswordagain-ph' => 'Írd be a jelszót újra',
'remembermypassword' => 'Emlékezzen rám ezen a számítógépen (legfeljebb $1 napig)',
'userlogin-remembermypassword' => 'Maradjak bejelentkezve',
'userlogin-signwithsecure' => 'Biztonságos kapcsolat használata',
'yourdomainname' => 'A domainneved:',
'password-change-forbidden' => 'Nem módosíthatod a jelszót ezen a wikin.',
'externaldberror' => 'Hiba történt a külső adatbázis hitelesítése közben, vagy nem vagy jogosult a külső fiókod frissítésére.',
'login' => 'Bejelentkezés',
'nav-login-createaccount' => 'Bejelentkezés / fiók létrehozása',
'loginprompt' => "Engedélyezned kell a sütiket (''cookie''), hogy bejelentkezhess a(z) {{SITENAME}} wikibe.",
'userlogin' => 'Bejelentkezés / fiók létrehozása',
'userloginnocreate' => 'Bejelentkezés',
'logout' => 'Kijelentkezés',
'userlogout' => 'Kijelentkezés',
'notloggedin' => 'Nem vagy bejelentkezve',
'userlogin-noaccount' => 'Nem rendelkezel még felhasználói fiókkal?',
'userlogin-joinproject' => 'Csatlakozz a(z) {{SITENAME}} wikihez',
'nologin' => 'Nem rendelkezel még felhasználói fiókkal? $1.',
'nologinlink' => 'Itt regisztrálhatsz',
'createaccount' => 'Regisztráció',
'gotaccount' => "Ha már korábban regisztráltál, '''$1'''.",
'gotaccountlink' => 'Bejelentkezés',
'userlogin-resetlink' => 'Elfelejtetted a bejelentkezési adataidat?',
'userlogin-resetpassword-link' => 'A jelszó alaphelyzetbe állítása',
'helplogin-url' => 'Help:Bejelentkezés',
'userlogin-helplink' => '[[{{MediaWiki:helplogin-url}}|Segítség a bejelentkezéshez]]',
'createacct-join' => 'Add meg az alábbi információkat.',
'createacct-another-join' => 'Add meg az új fiók adatait alább.',
'createacct-emailrequired' => 'E-mail cím',
'createacct-emailoptional' => 'E-mail cím (opcionális)',
'createacct-email-ph' => 'Add meg e-mail címed',
'createacct-another-email-ph' => 'Add meg az emailcímet',
'createaccountmail' => 'Átmeneti, véletlenszerű jelszó beállítása és kiküldése a megadott e-mail címre',
'createacct-realname' => 'Igazi neved (nem kötelező)',
'createaccountreason' => 'Indoklás:',
'createacct-reason' => 'Indoklás',
'createacct-reason-ph' => 'Miért hozol létre egy másik fiókot',
'createacct-captcha' => 'Biztonsági ellenőrzés',
'createacct-imgcaptcha-ph' => 'Írd be a szöveget, amit fent látsz',
'createacct-submit' => 'Felhasználói fiók létrehozása',
'createacct-another-submit' => 'Újabb felhasználó létrehozása',
'createacct-benefit-heading' => 'A(z) {{SITENAME}}-t hozzád hasonló emberek készítik.',
'createacct-benefit-body1' => '{{PLURAL:$1|szerkesztés|szerkesztés}}',
'createacct-benefit-body2' => '{{PLURAL:$1|lap|lap}}',
'createacct-benefit-body3' => 'aktív {{PLURAL:$1|szerkesztő|szerkesztő}}',
'badretype' => 'A megadott jelszavak nem egyeznek.',
'userexists' => 'A megadott felhasználónév már foglalt.
Kérlek, válassz másikat!',
'loginerror' => 'Hiba történt a bejelentkezés során',
'createacct-error' => 'Fióklétrehozási hiba',
'createaccounterror' => 'Nem sikerült létrehozni a felhasználói fiókot: $1',
'nocookiesnew' => 'A felhasználói fiókod létrejött, de nem vagy bejelentkezve. A wiki sütiket („cookie”) használ a szerkesztők azonosítására. Nálad ezek le vannak tiltva. Kérlek, engedélyezd őket a böngésződben, majd lépj be az új azonosítóddal és jelszavaddal.',
'nocookieslogin' => 'A wiki sütiket („cookie”) használ a szerkesztők azonosításhoz.
Nálad ezek le vannak tiltva.
Engedélyezd őket a böngésződben, majd próbáld újra.',
'nocookiesfornew' => 'A felhasználói fiók nem lett létrehozva, mivel nem sikerült megerősítenünk a forrását.
Ellenőrizd, hogy a sütik engedélyezve vannak-e, majd frissítsd az oldalt, és próbálkozz újra.',
'noname' => 'Érvénytelen szerkesztőnevet adtál meg.',
'loginsuccesstitle' => 'Sikeres bejelentkezés',
'loginsuccess' => "'''Sikeresen bejelentkeztél a(z) {{SITENAME}} wikibe „$1” néven.'''",
'nosuchuser' => 'Nem létezik „$1” nevű szerkesztő.
A szerkesztőnevek kis- és nagybetű-érzékenyek.
Ellenőrizd, hogy helyesen írtad-e be, vagy [[Special:UserLogin/signup|hozz létre egy új fiókot]].',
'nosuchusershort' => 'Nem létezik „$1” nevű szerkesztő.
Ellenőrizd, hogy helyesen írtad-e be.',
'nouserspecified' => 'Meg kell adnod a felhasználói nevet.',
'login-userblocked' => 'Ez a szerkesztő blokkolva van, a bejelentkezés nem engedélyezett.',
'wrongpassword' => 'A megadott jelszó érvénytelen. Próbáld meg újra.',
'wrongpasswordempty' => 'Nem adtál meg jelszót. Próbáld meg újra.',
'passwordtooshort' => 'A jelszónak legalább {{PLURAL:$1|egy|$1}} karakterből kell állnia.',
'password-name-match' => 'A jelszavadnak különböznie kell a szerkesztőnevedtől.',
'password-login-forbidden' => 'Ezen felhasználónév és jelszó használata tiltott.',
'mailmypassword' => 'Új jelszó küldése e-mailben',
'passwordremindertitle' => 'Ideiglenes jelszó a(z) {{SITENAME}} wikire',
'passwordremindertext' => 'Valaki (vélhetően te, a(z) $1 IP-címről) új jelszót kért a(z)
{{SITENAME}} wikis ($4) felhasználói fiókjához.
"$2" számára most egy ideiglenes jelszót készítettünk: "$3".
Ha te kértél új jelszót, lépj be, és változtasd meg.
Az ideiglenes jelszó {{PLURAL:$5|egy nap|$5 nap}} múlva érvényét veszti.

Ha nem te küldted a kérést, vagy közben eszedbe jutott a régi, és már nem akarod megváltoztatni, hagyd figyelmen kívül ezt az üzenetet, és használd továbbra is a régi jelszavadat.',
'noemail' => '„$1” e-mail címe nincs megadva.',
'noemailcreate' => 'Meg kell adnod egy valós e-mail címet',
'passwordsent' => 'Az új jelszót elküldtük „$1” e-mail címére.
Lépj be a levélben található adatokkal.',
'blocked-mailpassword' => 'Az IP-címedet blokkoltuk, azaz eltiltottunk a szerkesztéstől, ezért a visszaélések elkerülése érdekében a jelszó-visszaállítás funkciót nem használhatod.',
'eauthentsent' => 'Egy ellenőrző e-mailt küldtünk a megadott címre. Mielőtt más leveleket kaphatnál, igazolnod kell az e-mailben írt utasításoknak megfelelően, hogy valóban a tiéd a megadott cím.',
'throttled-mailpassword' => 'Már elküldtünk egy jelszóemlékeztetőt az utóbbi {{PLURAL:$1|egy|$1}} órában.
A visszaélések elkerülése végett {{PLURAL:$1|egy|$1}} óránként csak egy jelszó-emlékeztetőt küldünk.',
'mailerror' => 'Hiba történt az e-mail küldése közben: $1',
'acct_creation_throttle_hit' => 'A wiki látogatói ezt az IP-címet használva {{PLURAL:$1|egy|$1}} fiókot hoztak létre az elmúlt egy nap alatt . Ez a megengedett maximum ezen időtartam alatt, így az erről a címről látogatók jelenleg nem hozhatnak létre újabb fiókokat.',
'emailauthenticated' => 'Az e-mail címedet $2 $3-kor erősítetted meg.',
'emailnotauthenticated' => 'Az e-mail címed még <strong>nincs megerősítve</strong>. E-mailek küldése és fogadása nem engedélyezett.',
'noemailprefs' => 'Az alábbi funkciók használatához meg kell adnod az e-mail címedet.',
'emailconfirmlink' => 'E-mail cím megerősítése',
'invalidemailaddress' => 'A megadott e-mail cím érvénytelen formátumú. Kérlek, adj meg egy érvényes e-mail címet vagy hagyd üresen azt a mezőt.',
'cannotchangeemail' => 'Ezen a wikin nem módosítható a fiókhoz tartozó e-mail cím.',
'emaildisabled' => 'Ez az oldal nem küld e-maileket.',
'accountcreated' => 'Felhasználói fiók létrehozva',
'accountcreatedtext' => '[[{{ns:User}}:$1|$1]] ([[{{ns:User talk}}:$1|vita]]) felhasználói fiókja sikeresen létrejött.',
'createaccount-title' => 'Új {{SITENAME}}-azonosító létrehozása',
'createaccount-text' => 'Valaki létrehozott számodra egy "$2" nevű {{SITENAME}}-azonosítót ($4).
A hozzá tartozó jelszó "$3", melyet a bejelentkezés után minél előbb változtass meg.

Ha nem kértél új azonosítót, és tévedésből kaptad ezt a levelet, hagyd figyelmen kívül.',
'usernamehasherror' => 'A felhasználónév nem tartalmazhat hash karaktereket',
'login-throttled' => 'Túl sok hibás bejelentkezés.
Várj $1, mielőtt újra próbálkozol.',
'login-abort-generic' => 'A bejelentkezés sikertelen – megszakítva',
'loginlanguagelabel' => 'Nyelv: $1',
'suspicious-userlogout' => 'A kijelentkezési kérésed vissza lett utasítva, mert úgy tűnik, hogy egy hibás böngésző vagy gyorsítótárazó proxy küldte.',

# Email sending
'php-mail-error-unknown' => 'Ismeretlen hiba a PHP mail() függvényében',
'user-mail-no-addy' => 'E-mail üzenetet próbáltál küldeni e-mail cím megadása nélkül.',
'user-mail-no-body' => 'Üres vagy nagyon rövid email-t próbáltál küldeni.',

# Change password dialog
'resetpass' => 'Jelszó módosítása',
'resetpass_announce' => 'Az e-mailben elküldött ideiglenes kóddal jelentkeztél be. A bejelentkezés befejezéséhez meg kell adnod egy új jelszót:',
'resetpass_text' => '<!-- Ide írd a szöveget -->',
'resetpass_header' => 'A fiókhoz tartozó jelszó megváltoztatása',
'oldpassword' => 'Régi jelszó:',
'newpassword' => 'Új jelszó:',
'retypenew' => 'Új jelszó ismét:',
'resetpass_submit' => 'Add meg a jelszót és jelentkezz be',
'changepassword-success' => 'A jelszavad megváltoztatása sikeresen befejeződött!',
'resetpass_forbidden' => 'A jelszavak nem változtathatók meg',
'resetpass-no-info' => 'Be kell jelentkezned, hogy közvetlenül elérd ezt a lapot.',
'resetpass-submit-loggedin' => 'Jelszó megváltoztatása',
'resetpass-submit-cancel' => 'Mégse',
'resetpass-wrong-oldpass' => 'Nem megfelelő ideiglenes vagy jelenlegi jelszó.
Lehet, hogy már sikeresen megváltoztattad a jelszavad, vagy pedig időközben új ideiglenes jelszót kértél.',
'resetpass-temp-password' => 'Ideiglenes jelszó:',
'resetpass-abort-generic' => 'A jelszómódosítást megszakította egy kiterjesztés.',

# Special:PasswordReset
'passwordreset' => 'Jelszó törlése',
'passwordreset-text-one' => 'A jelszavad alaphelyzetbe állításához töltsd ki az űrlapot.',
'passwordreset-text-many' => '{{PLURAL:$1|A jelszavad alaphelyzetbe állításához töltsd ki az alábbi mezők egyikét.}}',
'passwordreset-legend' => 'Új jelszó kérése',
'passwordreset-disabled' => 'Új jelszó kérése nem engedélyezett ezen a wikin.',
'passwordreset-emaildisabled' => 'Az e-mail funkció le van tiltva ezen a wikin.',
'passwordreset-username' => 'Felhasználónév:',
'passwordreset-domain' => 'Tartomány:',
'passwordreset-capture' => 'Meg szeretnéd nézni az elkészült üzenetet?',
'passwordreset-capture-help' => 'Ha kipipálod a dobozt, elmegy az üzenet a felhasználónak és megjelenik számodra (az ideiglenes jelszóval együtt).',
'passwordreset-email' => 'E-mail cím:',
'passwordreset-emailtitle' => 'A(z) {{SITENAME}}-fiók adatai',
'passwordreset-emailtext-ip' => 'Valaki (vélhetően Te, a $1 IP-címről) a jelszavad visszaállítását kérte a {{SITENAME}} ($4) oldalon felvett {{PLURAL:$3|fiókban|fiókokban}}. A következő felhasználói {{PLURAL:$3|fiók van|fiókok vannak}} hozzárendelve ehhez az e-mail címhez:

$2

{{PLURAL:$3|Ez az ideiglenes jelszó|Ezek az ideiglenes jelszavak}} $5 nap múlva {{PLURAL:$3|jár|járnak}} le. Jelentkezz be, és cseréld le a jelszavadat. Ha valaki más kérte az emlékeztetőt, vagy eszedbe jutott a régi jelszó, és nem akarod lecserélni a jelszavadat, hagyd figyelmen kívül ezt az üzenetet, és használd a régi jelszavadat.',
'passwordreset-emailtext-user' => '$1 felhasználó jelszó-visszaállítást kért a {{SITENAME}} ($4) oldalon felvett {{PLURAL:$3|fiókban|fiókokban}}. A következő felhasználói {{PLURAL:$3|fiók van|fiókok vannak}} hozzárendelve ehhez az e-mail címhez:

$2

{{PLURAL:$3|Ez az ideiglenes jelszó|Ezek az ideiglenes jelszavak}} $5 nap múlva {{PLURAL:$3|jár|járnak}} le. Jelentkezz be, és cseréld le a jelszavadat. Ha valaki más kérte az emlékeztetőt, vagy eszedbe jutott a régi jelszó, és nem akarod lecserélni a jelszavadat, hagyd figyelmen kívül ezt az üzenetet, és használd a régi jelszavadat.',
'passwordreset-emailelement' => 'Felhasználónév: $1
Ideiglenes jelszó: $2',
'passwordreset-emailsent' => 'Jelszó-visszaállító e-mail elküldve.',
'passwordreset-emailsent-capture' => 'Az alább látható jelszó-visszaállító e-mail lett elküldve.',
'passwordreset-emailerror-capture' => 'A jelszó-visszaállító e-mail generálása megtörtént, mint az alább látszik, de elküldése a {{GENDER:$2|szerkesztőnek}} nem sikerült: $1',

# Special:ChangeEmail
'changeemail' => 'E-mail cím megváltoztatása',
'changeemail-header' => 'A fiókhoz tartozó e-mail cím megváltoztatása',
'changeemail-text' => 'Az e-mail címed megváltoztatásához ki kell töltened az alábbi űrlapot. Megerősítésképpen meg kell adnod a jelszavadat is.',
'changeemail-no-info' => 'A lap közvetlen eléréséhez be kell jelentkezned.',
'changeemail-oldemail' => 'Jelenlegi e-mail cím:',
'changeemail-newemail' => 'Új e-mail cím:',
'changeemail-none' => '(nincs)',
'changeemail-password' => 'A {{SITENAME}} jelszavad:',
'changeemail-submit' => 'E-mail cím megváltoztatása',
'changeemail-cancel' => 'Mégse',

# Edit page toolbar
'bold_sample' => 'Félkövér szöveg',
'bold_tip' => 'Félkövér szöveg',
'italic_sample' => 'Dőlt szöveg',
'italic_tip' => 'Dőlt szöveg',
'link_sample' => 'Hivatkozás megnevezése',
'link_tip' => 'Belső hivatkozás',
'extlink_sample' => 'http://www.példa-hivatkozás.hu hivatkozás megnevezése',
'extlink_tip' => 'Külső hivatkozás (ne felejtsd el a http:// előtagot)',
'headline_sample' => 'Alfejezet címe',
'headline_tip' => 'Alfejezetcím',
'nowiki_sample' => 'Ide írd a formázatlan szöveget',
'nowiki_tip' => 'Wiki formázás kikapcsolása',
'image_sample' => 'Pelda.jpg',
'image_tip' => 'Fájl (pl. kép) beszúrása',
'media_sample' => 'Peldaegyketto.ogg',
'media_tip' => 'Fájlhivatkozás',
'sig_tip' => 'Aláírás időponttal',
'hr_tip' => 'Vízszintes vonal (ritkán használd)',

# Edit pages
'summary' => 'Összefoglaló:',
'subject' => 'Téma/főcím:',
'minoredit' => 'Apró változtatás',
'watchthis' => 'A lap figyelése',
'savearticle' => 'Lap mentése',
'preview' => 'Előnézet',
'showpreview' => 'Előnézet megtekintése',
'showlivepreview' => 'Élő előnézet',
'showdiff' => 'Változtatások megtekintése',
'anoneditwarning' => "'''Figyelem:''' Nem vagy bejelentkezve, ha szerkesztesz, az IP-címed látható lesz a laptörténetben.",
'anonpreviewwarning' => "''Nem vagy bejelentkezve. A mentéskor az IP-címed rögzítve lesz a laptörténetben.''",
'missingsummary' => "'''Emlékeztető:''' Nem adtál meg szerkesztési összefoglalót. Ha összefoglaló nélkül akarod elküldeni a szöveget, kattints újra a mentésre.",
'missingcommenttext' => 'Kérjük, írj összefoglalót a szerkesztésedhez.',
'missingcommentheader' => "'''Emlékeztető:''' Nem adtad meg a megjegyzés tárgyát vagy címét.
Ha ismét a „{{int:savearticle}}” gombra kattintasz, akkor a szerkesztésed nélküle lesz elmentve.",
'summary-preview' => 'A szerkesztési összefoglaló előnézete:',
'subject-preview' => 'A téma/főcím előnézete:',
'blockedtitle' => 'A szerkesztő blokkolva van',
'blockedtext' => "'''A szerkesztőnevedet vagy az IP-címedet blokkoltuk.'''

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
'autoblockedtext' => "Az IP-címed automatikusan blokkolva lett, mert korábban egy olyan szerkesztő használta, akit $1 blokkolt, az alábbi indoklással:

:''$2''

*A blokk kezdete: '''$8'''
*A blokk lejárata: '''$6'''
*Blokkolt szerkesztő: '''$7'''

Kapcsolatba léphetsz $1 szerkesztőnkkel, vagy egy másik [[{{MediaWiki:Grouppage-sysop}}|adminisztrátorral]], és megbeszélheted vele a blokkolást.

Az 'E-mail küldése ennek a szerkesztőnek' funkciót csak akkor használhatod, ha érvényes e-mail címet adtál meg
[[Special:Preferences|fiókbeállításaidban]], és nem blokkolták a használatát.

Jelenlegi IP-címed: $3, a blokkolás azonosítószáma: #$5.
Kérjük, hogy érdeklődés esetén mindkettőt add meg.",
'blockednoreason' => 'nem adott meg okot',
'whitelistedittext' => 'Lapok szerkesztéséhez $1.',
'confirmedittext' => 'Lapok szerkesztése előtt meg kell erősítened az e-mail címedet. Kérjük, hogy a [[Special:Preferences|szerkesztői beállításaidban]] add meg, majd erősítsd meg az e-mail címedet.',
'nosuchsectiontitle' => 'A szakasz nem található',
'nosuchsectiontext' => 'Egy olyan szakaszt próbáltál meg szerkeszteni, ami nem létezik.
Lehet, hogy áthelyezték, átnevezték vagy törölték, miközben nézted a lapot.',
'loginreqtitle' => 'Bejelentkezés szükséges',
'loginreqlink' => 'be kell jelentkezned',
'loginreqpagetext' => '$1 más oldalak megtekintéséhez.',
'accmailtitle' => 'Elküldtük a jelszót.',
'accmailtext' => "A(z) [[User talk:$1|$1]] fiókhoz egy véletlenszerűen generált jelszót küldünk a(z) $2 címre.

Az új fiók jelszava a ''[[Special:ChangePassword|jelszó megváltoztatása]]'' lapon módosítható a bejelentkezés után.",
'newarticle' => '(Új)',
'newarticletext' => "Egy olyan lapra mutató hivatkozást követtél, ami még nem létezik.
A lap létrehozásához csak gépeld be a szövegét a lenti szövegdobozba. Ha kész vagy, az „Előnézet megtekintése” gombbal ellenőrizheted, hogy úgy fog-e kinézni, ahogy szeretnéd, és a „Lap mentése” gombbal tudod elmenteni. (További információkat a [[{{MediaWiki:Helppage}}|súgólapon]] találsz).
Ha tévedésből jutottál ide, kattints a böngésződ '''vissza''' vagy '''back''' gombjára.",
'anontalkpagetext' => "----''Ez egy olyan anonim szerkesztő vitalapja, aki még nem regisztrált, vagy csak nem jelentkezett be.
Ezért az IP-címét használjuk az azonosítására.
Ugyanazon az IP-címen számos szerkesztő osztozhat az idők folyamán.
Ha úgy látod, hogy az üzenetek, amiket ide kapsz, nem neked szólnak, [[Special:UserLogin/signup|regisztrálj]] vagy ha már regisztráltál, [[Special:UserLogin|jelentkezz be]], hogy ne keverjenek össze másokkal.''",
'noarticletext' => 'Ez a lap jelenleg nem tartalmaz szöveget.
[[Special:Search/{{PAGENAME}}|Rákereshetsz erre a címszóra]],
<span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} megtekintheted a kapcsolódó naplókat],
vagy [{{fullurl:{{FULLPAGENAME}}|action=edit}} szerkesztheted a lapot].</span>',
'noarticletext-nopermission' => 'Ez a lap jelenleg nem tartalmaz szöveget.
[[Special:Search/{{PAGENAME}}|Rákereshetsz a lap címére]] más lapok tartalmában, vagy <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} megtekintheted a kapcsolódó naplófájlokat]</span>.',
'missing-revision' => 'A(z) "{{PAGENAME}}" nevű oldal #$1 változata nem létezik.

Ezt általában egy elavult, törölt oldalra mutató laptörténeti hivatkozás használata okozza. Részletek a [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} törlési naplóban] találhatóak.',
'userpage-userdoesnotexist' => 'Nincs „<nowiki>$1</nowiki>” nevű regisztrált felhasználónk.
Nézd meg, hogy valóban ezt a lapot szeretnéd-e létrehozni vagy szerkeszteni.',
'userpage-userdoesnotexist-view' => 'Nincs regisztrálva „$1” szerkesztői azonosító.',
'blocked-notice-logextract' => 'A felhasználó jelenleg blokkolva van.
A blokkolási napló legutóbbi ide vonatkozó bejegyzése a következő:',
'clearyourcache' => "'''Megjegyzés:''' mentés után frissítened kell a böngésződ gyorsítótárát, hogy lásd a változásokat.
'''Firefox / Safari:''' tartsd lenyomva a Shift gombot és kattints a ''Frissítés'' gombra a címsorban, vagy használd a ''Ctrl–F5'' vagy ''Ctrl–R'' billentyűkombinációt (Mac-en ''Command–R'');
'''Google Chrome:''' használd a ''Ctrl–Shift–R'' billentyűkombinációt (Mac-en ''Command–Shift–R'');
'''Internet Explorer:''' tartsd nyomva a ''Ctrl''-t, és kattints a ''Frissítés'' gombra, vagy nyomj ''Ctrl–F5''-öt;
'''Opera:''' ürítsd ki a gyorsítótárat a ''Beállítások / Haladó / Előzmények→Törlés most'' gombbal, majd frissítsd az oldalt.",
'usercssyoucanpreview' => "'''Tipp:''' mentés előtt használd az „{{int:showpreview}}” gombot az új CSS-ed teszteléséhez.",
'userjsyoucanpreview' => "'''Tipp:''' mentés előtt használd az „{{int:showpreview}}” gombot az új JavaScipted teszteléséhez.",
'usercsspreview' => "'''Ne felejtsd el, hogy ez csak a felhasználói CSS-ed előnézete és még nincs elmentve!'''",
'userjspreview' => "'''Ne felejtsd el, hogy még csak teszteled a felhasználói JavaScriptedet, és még nincs elmentve!'''",
'sitecsspreview' => "'''Ne feledd, hogy csak a CSS előnézetét látod.'''
'''Még nincs elmentve!'''",
'sitejspreview' => "'''Ne feledd, hogy a JavaScript-kódnak csak az előnézetét látod.'''
'''Még nincs elmentve!'''",
'userinvalidcssjstitle' => "'''Figyelem:''' Nincs „$1” nevű felület. A felületekhez tartozó .css/.js oldalak kisbetűvel kezdődnek, például ''{{ns:user}}:Gipsz Jakab/vector.css'' és nem ''{{ns:user}}:Gipsz Jakab/Vector.css''.",
'updated' => '(frissítve)',
'note' => "'''Megjegyzés:'''",
'previewnote' => "'''Ne feledd, hogy ez csak egy előnézet.''' A változtatásaid még nincsenek elmentve!",
'continue-editing' => 'Szerkesztés folytatása',
'previewconflict' => 'Ez az előnézet a felső szerkesztődobozban levő szöveg mentés utáni megfelelőjét mutatja.',
'session_fail_preview' => "'''Az elveszett munkamenetadatok miatt sajnos nem tudtuk feldolgozni a szerkesztésedet.
Kérjük próbálkozz újra!
Amennyiben továbbra sem sikerül, próbálj meg [[Special:UserLogout|kijelentkezni]], majd ismét bejelentkezni!'''",
'session_fail_preview_html' => "'''Az elveszett munkamenetadatok miatt nem tudtuk feldolgozni a szerkesztésedet.'''

''Mivel a wikiben engedélyezett a nyers HTML-kód használata, az előnézet el van rejtve a JavaScript-alapú támadások megakadályozása céljából.''

'''Ha ez egy normális szerkesztési kísérlet, akkor próbálkozz újra. Amennyiben továbbra sem sikerül, próbálj meg [[Special:UserLogout|kijelentkezni]], majd ismét bejelentkezni!''' (a változtatásaidat mentsd el magadnak, különben elvesznek!)",
'token_suffix_mismatch' => "'''A szerkesztésedet elutasítottuk, mert a kliensprogramod megváltoztatta a központozó karaktereket
a szerkesztési tokenben. A szerkesztés azért lett visszautasítva, hogy megelőzzük a lap szövegének sérülését.
Ez a probléma akkor fordulhat elő, ha hibás web-alapú proxyszolgáltatást használsz.'''",
'edit_form_incomplete' => "'''A szerkesztési űrlap egyes részei nem érkeztek meg a szerverre; ellenőrizd, hogy a szerkesztés sértetlen-e, majd próbáld újra.'''",
'editing' => '$1 szerkesztése',
'creating' => '$1 létrehozása',
'editingsection' => '$1 szerkesztése (szakasz)',
'editingcomment' => '$1 szerkesztése (új szakasz)',
'editconflict' => 'Szerkesztési ütközés: $1',
'explainconflict' => "Valaki megváltoztatta a lapot, mióta elkezdted szerkeszteni. A felső szövegdobozban láthatod az oldal jelenlegi tartalmát. A te módosításaid az alsó dobozban találhatók. Át kell másolnod a módosításaidat a felsőbe! 

'''Csak''' a felső dobozban levő szöveg lesz elmentve, amikor a „{{int:savearticle}}” gombra kattintasz.",
'yourtext' => 'A te változatod',
'storedversion' => 'A tárolt változat',
'nonunicodebrowser' => "'''Figyelem: A böngésződ nem Unicode kompatibilis. Egy kerülő megoldásként biztonságban szerkesztheted a cikkeket: a nem ASCII karakterek a szerkesztőablakban hexadeciális kódokként jelennek meg.'''",
'editingold' => "'''FIGYELMEZTETÉS: A lap egy elavult változatát szerkeszted.
Ha elmented, akkor az ezen változat után végzett összes módosítás elvész.'''",
'yourdiff' => 'Eltérések',
'copyrightwarning' => "Vedd figyelembe, hogy a {{SITENAME}} wikin végzett összes módosítás a(z) $2 alatt jelenik meg (lásd a(z) $1 lapot a részletekért). Ha nem akarod, hogy az írásodat módosítsák vagy továbbterjesszék, akkor ne küldd be.<br />
Azt is megígéred, hogy ezt magadtól írtad, vagy egy közkincsből vagy más szabad forrásból másoltad.
'''NE KÜLDJ BE JOGVÉDETT MUNKÁT ENGEDÉLY NÉLKÜL!'''",
'copyrightwarning2' => "Vedd figyelembe, hogy a {{SITENAME}} wikin végzett összes módosítást szerkeszthetik, módosíthatják vagy eltávolíthatják más szerkesztők.
Ha nem akarod, hogy az írásodat módosítsák, akkor ne küldd be.<br />
Azt is megígéred, hogy ezt magadtól írtad, vagy egy közkincsből vagy más szabad forrásból másoltad (lásd a(z) $1 lapot a részletekért).
'''NE KÜLDJ BE JOGVÉDETT MUNKÁT ENGEDÉLY NÉLKÜL!'''",
'longpageerror' => "'''HIBA: Az általad beküldött szöveg {{PLURAL:$1|egy kilobájt|$1 kilobájt}} hosszú, ami több az engedélyezett {{PLURAL:$2|egy kilobájtnál|$2 kilobájtnál}}.
A szerkesztést nem lehet elmenteni.'''",
'readonlywarning' => "FIGYELMEZTETÉS: A wiki adatbázisát karbantartás miatt zárolták, ezért most nem fogod tudni elmenteni a szerkesztéseidet!
A lap szövegét másold egy szövegfájlba, amit később felhasználhatsz!'''

Az adatbázist lezáró adminisztrátor az alábbi magyarázatot adta: $1",
'protectedpagewarning' => "'''Figyelem: Ez a lap le van védve, így csak adminisztrátori jogosultságokkal rendelkező szerkesztők módosíthatják.'''
A legutolsó ide vonatkozó naplóbejegyzés alább látható:",
'semiprotectedpagewarning' => "'''Megjegyzés:''' ez a lap védett, így regisztrálatlan vagy újonnan regisztrált szerkesztők nem módosíthatják.",
'cascadeprotectedwarning' => "'''Figyelem:''' ez a lap le van zárva, csak adminisztrátorok szerkeszthetik, mert a következő kaszkádvédelemmel ellátott {{PLURAL:$1|lapon|lapokon}} szerepel beillesztve:",
'titleprotectedwarning' => "'''Figyelem: Ez a lap le van védve, így csak a [[Special:ListGroupRights|megfelelő jogosultságokkal]] rendelkező szerkesztők hozhatják létre.'''
A legutolsó ide vonatkozó naplóbejegyzés alább látható:",
'templatesused' => 'A lapon használt {{PLURAL:$1|sablon|sablonok}}:',
'templatesusedpreview' => 'Az előnézet megjelenítésekor használt {{PLURAL:$1|sablon|sablonok}}:',
'templatesusedsection' => 'Az ebben a szakaszban használt {{PLURAL:$1|sablon|sablonok}}:',
'template-protected' => '(védett)',
'template-semiprotected' => '(félig védett)',
'hiddencategories' => 'Ez a lap {{PLURAL:$1|egy|$1}} rejtett kategóriába tartozik:',
'edittools' => '<!-- Ez a szöveg a szerkesztés és a feltöltés űrlap alatt lesz látható. -->',
'nocreatetext' => 'A(z) {{SITENAME}} wikin korlátozták az új oldalak létrehozásának lehetőségét.
Visszamehetsz és szerkeszthetsz egy létező lapot, valamint [[Special:UserLogin|bejelentkezhetsz vagy készíthetsz egy felhasználói fiókot]].',
'nocreate-loggedin' => 'Nincs jogosultságod új lapokat létrehozni.',
'sectioneditnotsupported-title' => 'A szakaszszerkesztés nem támogatott',
'sectioneditnotsupported-text' => 'Ezen a lapon nem támogatott a szakaszok szerkesztése',
'permissionserrors' => 'Engedélyezési hiba',
'permissionserrorstext' => 'A művelet elvégzése nem engedélyezett a számodra, a következő {{PLURAL:$1|ok|okok}} miatt:',
'permissionserrorstext-withaction' => 'Nincs jogosultságod a következő művelet elvégzéséhez: $2, a következő {{PLURAL:$1|ok|okok}} miatt:',
'recreate-moveddeleted-warn' => "'''Figyelem! Olyan lapot készülsz létrehozni, amit már legalább egyszer töröltek.'''

Mielőtt létrehoznád, nézd meg, miért törölték a lap korábbi tartalmát, és győződj meg róla, hogy a törlés indoka érvényes-e még. A törlési és átnevezési naplókban az érintett lapról az alábbi bejegyzések szerepelnek:",
'moveddeleted-notice' => 'Az oldal korábban törölve lett.
A lap törlési és átnevezési naplója alább olvasható.',
'log-fulllog' => 'Teljes napló megtekintése',
'edit-hook-aborted' => 'A szerkesztés meg lett szakítva egy hook által.
Nem lett magyarázat csatolva.',
'edit-gone-missing' => 'Nem lehet frissíteni a lapot.
Úgy tűnik, hogy törölve lett.',
'edit-conflict' => 'Szerkesztési ütközés.',
'edit-no-change' => 'A szerkesztésed figyelmen kívül lett hagyva, mivel nem változtattál a lap szövegén.',
'postedit-confirmation' => 'A szerkesztésedet elmentettük.',
'edit-already-exists' => 'Az új lap nem készíthető el.
Már létezik.',
'defaultmessagetext' => 'Alapértelmezett szöveg',
'content-failed-to-parse' => 'Hiba történt a $2 tartalom $1 modellre történő konvertálása során: $3',
'invalid-content-data' => 'Érvénytelen tartalom adat',
'content-not-allowed-here' => '"$1" tartalom nem engedélyezett a [[$2]] oldalon',
'editwarning-warning' => 'A lap elhagyásával az összes itt végzett változtatás elveszhet.
Ha be vagy jelentkezve letilthatod ezt a figyelmeztetést a beállításaid „Szerkesztés” szakaszában.',

# Content models
'content-model-wikitext' => 'wikiszöveg',
'content-model-text' => 'egyszerű szöveg',
'content-model-javascript' => 'JavaScript',
'content-model-css' => 'CSS',

# Parser/template warnings
'expensive-parserfunction-warning' => 'Figyelem: ezen a lapon túl sok erőforrásigényes elemzőfüggvény-hívás található.

Kevesebb, mint {{PLURAL:$2|egy|$2}} kellene, jelenleg {{PLURAL:$1|egy|$1}} van.',
'expensive-parserfunction-category' => 'Túl sok költséges elemzőfüggvény-hívást tartalmazó lapok',
'post-expand-template-inclusion-warning' => 'Figyelem: a beillesztett sablonok mérete túl nagy.
Néhány sablon nem fog megjelenni.',
'post-expand-template-inclusion-category' => 'Lapok, melyeken a beillesztett sablon mérete meghaladja a megengedett méretet',
'post-expand-template-argument-warning' => 'Figyelem: Ez a lap legalább egy olyan sablonparamétert tartalmaz, amely kibontva túl nagy, így el lett(ek) hagyva.',
'post-expand-template-argument-category' => 'Elhagyott sablonparaméterekkel rendelkező lapok',
'parser-template-loop-warning' => 'Végtelen ciklus a következő sablonban: [[$1]]',
'parser-template-recursion-depth-warning' => 'A sablon rekurzív beillesztésének mélysége átlépte a határérékét ($1)',
'language-converter-depth-warning' => 'A nyelvátalakító rekurzióinak száma túllépve ($1)',
'node-count-exceeded-category' => 'Lapok, ahogy a csomópont szám túl nagy',
'node-count-exceeded-warning' => 'Az oldal meghaladta a csomópont számot',
'expansion-depth-exceeded-category' => 'Lapok, melyeken a sablonok kibontása meghaladja a megengedett szintet',
'expansion-depth-exceeded-warning' => 'A lap meghaladta az engedélyezett kiterjesztési mélységet',
'parser-unstrip-loop-warning' => 'Unstrip hurok észlelve',
'parser-unstrip-recursion-limit' => 'Túl mély unstrip rekurzió: $1',
'converter-manual-rule-error' => 'Hiba van a kézi nyelvi konverziós szabályban',

# "Undo" feature
'undo-success' => 'A szerkesztés visszavonható. Kérlek ellenőrizd alább a változásokat, hogy valóban ezt szeretnéd-e tenni, majd kattints a lap mentése gombra a visszavonás véglegesítéséhez.',
'undo-failure' => 'A szerkesztést nem lehet automatikusan visszavonni vele ütköző későbbi szerkesztések miatt.',
'undo-norev' => 'A szerkesztés nem állítható vissza, mert nem létezik vagy törölve lett.',
'undo-summary' => 'Visszavontam [[Special:Contributions/$2|$2]] ([[User talk:$2|vita]] | [[Special:Contributions/$2|{{MediaWiki:Contribslink}}]]) szerkesztését (oldid: $1)',

# Account creation failure
'cantcreateaccounttitle' => 'Felhasználói fiók létrehozása sikertelen',
'cantcreateaccount-text' => "Erről az IP-címről ('''$1''') nem lehet regisztrálni, mert [[User:$3|$3]] blokkolta az alábbi indokkal:

:''$2''",

# History pages
'viewpagelogs' => 'A lap a rendszernaplókban',
'nohistory' => 'A lap nem rendelkezik laptörténettel.',
'currentrev' => 'Aktuális változat',
'currentrev-asof' => 'A lap jelenlegi, $1-kori változata',
'revisionasof' => 'A lap $1-kori változata',
'revision-info' => 'A lap korábbi változatát látod, amilyen $2 $1-kor történt szerkesztése után volt.',
'previousrevision' => '←Régebbi változat',
'nextrevision' => 'Újabb változat→',
'currentrevisionlink' => 'Aktuális változat',
'cur' => 'akt',
'next' => 'következő',
'last' => 'előző',
'page_first' => 'első',
'page_last' => 'utolsó',
'histlegend' => 'Eltérések kijelölése: jelöld ki az összehasonlítandó változatokat, majd nyomd meg az Enter billentyűt, vagy az alul lévő gombot.<br />
Jelmagyarázat: (akt) = eltérés az aktuális változattól, (előző) = eltérés az előző változattól, a = apró szerkesztés',
'history-fieldset-title' => 'Keresés a laptörténetben',
'history-show-deleted' => 'Csak a törölt változatok',
'histfirst' => 'legelső',
'histlast' => 'legutolsó',
'historysize' => '({{PLURAL:$1|egy|$1}} bájt)',
'historyempty' => '(üres)',

# Revision feed
'history-feed-title' => 'Laptörténet',
'history-feed-description' => 'Az oldal laptörténete a wikiben',
'history-feed-item-nocomment' => '$1, $2-n',
'history-feed-empty' => 'A kért oldal nem létezik.
Lehet, hogy törölték a wikiből, vagy átnevezték.
Próbálkozhatsz a témával kapcsolatos lapok [[Special:Search|keresésével]].',

# Revision deletion
'rev-deleted-comment' => '(szerkesztési összefoglaló eltávolítva)',
'rev-deleted-user' => '(szerkesztőnév eltávolítva)',
'rev-deleted-event' => '(bejegyzés eltávolítva)',
'rev-deleted-user-contribs' => '[felhasználónév vagy IP-cím eltávolítva – szerkesztés elrejtve a közreműködések közül]',
'rev-deleted-text-permission' => "A lap ezen változatát '''törölték'''.
További információkat a [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} törlési naplóban] találhatsz.",
'rev-deleted-text-unhide' => "A lap ezen változatát '''törölték'''.
További részleteket a [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} törlési naplóban] találhatsz.
Mivel adminisztrátor vagy, még mindig [$1 megtekintheted a tartalmát], ha szeretnéd.",
'rev-suppressed-text-unhide' => "A lap ezen változatát '''elrejtették'''.
További részleteket az [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} elrejtési naplóban] találhatsz.
Mivel adminisztrátor vagy, még mindig [$1 megtekintheted a tartalmát], ha szeretnéd.",
'rev-deleted-text-view' => "A lap ezen változatát '''törölték'''.
Te megnézheted. További részleteket a [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} törlési naplóban] találhatsz.",
'rev-suppressed-text-view' => "A lap ezen változatát '''elrejtették'''.
Te megtekintheted. További részleteket az [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} elrejtési naplóban] találhatsz.",
'rev-deleted-no-diff' => "Nem nézheted meg a két változat közötti eltérést, mert a változatok egyikét '''törölték'''.
További részleteket a [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} törlési naplóban] találhatsz.",
'rev-suppressed-no-diff' => "Nem nézheted meg ezt a változtatást, mert az egyik változatot '''törölték'''.",
'rev-deleted-unhide-diff' => "A változatok közötti eltéréshez kiválasztott változatok egyike '''törölve''' lett.
További részleteket a [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} törlési naplóban] találhatsz.
Te még mindig [$1 megtekintheted a változatok közötti eltérést], ha szeretnéd.",
'rev-suppressed-unhide-diff' => "A változatok közötti eltéréshez kiválasztott változatok egyike '''el lett rejtve'''.
Részleteket az [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} elrejtési naplójában találhatsz].
Te még mindig [$1 megtekintheted a változatok közötti eltérést], ha szeretnéd.",
'rev-deleted-diff-view' => "A változatok közötti eltéréshez kiválasztott változatok egyike '''törölve''' lett.
Te még mindig megtekintheted a változatok közötti eltérést. További részleteket a [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} törlési naplóban] találhatsz.",
'rev-suppressed-diff-view' => "A változatok közötti eltéréshez kiválasztott változatok egyike '''el lett rejtve'''.
Te még mindig megtekintheted a változatok közötti eltérést. További részleteket az [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} elrejtési naplójában találhatsz].",
'rev-delundel' => 'megjelenítés/elrejtés',
'rev-showdeleted' => 'megjelenítés',
'revisiondelete' => 'Változatok törlése vagy helyreállítása',
'revdelete-nooldid-title' => 'Érvénytelen célváltozat',
'revdelete-nooldid-text' => 'Nem adtad meg a célváltozato(ka)t, a megadott változat nem létezik,
vagy a legutolsó változatot próbáltad meg elrejteni.',
'revdelete-nologtype-title' => 'Nem adtad meg a napló típusát',
'revdelete-nologtype-text' => 'Nem adtad meg, hogy melyik naplón szeretnéd elvégezni a műveletet.',
'revdelete-nologid-title' => 'Érvénytelen naplóbejegyzés',
'revdelete-nologid-text' => 'Nem adtad meg azt a naplóbejegyzést, amin el szeretnéd végezni a műveletet, vagy olyat adtál meg, ami nem létezik.',
'revdelete-no-file' => 'A megadott fájl nem létezik.',
'revdelete-show-file-confirm' => 'Biztosan meg szeretnéd nézni a(z) „<nowiki>$1</nowiki>” $2, $3-i törölt változatát?',
'revdelete-show-file-submit' => 'Igen',
'revdelete-selected' => "'''A(z) [[:$1]] lap {{PLURAL:$2|kiválasztott változata|kiválasztott változatai}}:'''",
'logdelete-selected' => "'''{{PLURAL:$1|Kiválasztott naplóesemény|Kiválasztott naplóesemények}}:'''",
'revdelete-text' => "'''A törölt változatok és események továbbra is megjelennek a laptörténetben és a naplókban,
azonban a tartalmuk nem lesz mindenki számára hozzáférhető.'''
A(z) {{SITENAME}} adminisztrátorai továbbra is meg tudják tekinteni az elrejtett tartalmat, és helyre tudják állítani ugyanezen a felületen keresztül, amennyiben nincs további korlátozás beállítva.",
'revdelete-confirm' => 'Kérlek erősítsd meg, hogy valóban ezt szeretnéd tenni; megértetted a következményeket, és amit teszel, az összhangban van [[{{MediaWiki:Policy-url}}|az irányelvekkel]].',
'revdelete-suppress-text' => "Az elrejtés '''csak''' a következő esetekben használható:
* Illetlen személyes információk
*: ''otthoni címek és telefonszámok, társadalombiztosítási számok stb.''",
'revdelete-legend' => 'Korlátozások megadása:',
'revdelete-hide-text' => 'Változat szövegének elrejtése',
'revdelete-hide-image' => 'A fájl tartalmának elrejtése',
'revdelete-hide-name' => 'Művelet és cél elrejtése',
'revdelete-hide-comment' => 'Összefoglaló elrejtése',
'revdelete-hide-user' => 'A szerkesztő felhasználónevének/IP-címének elrejtése',
'revdelete-hide-restricted' => 'Adatok elrejtése az adminisztrátorok és mindenki más elől',
'revdelete-radio-same' => '(nincs változtatás)',
'revdelete-radio-set' => 'Igen',
'revdelete-radio-unset' => 'Nem',
'revdelete-suppress' => 'Adatok elrejtése az adminisztrátorok és a többi felhasználó elől is',
'revdelete-unsuppress' => 'Korlátozások eltávolítása a visszaállított változatokról',
'revdelete-log' => 'Ok:',
'revdelete-submit' => 'Alkalmazás a kiválasztott {{PLURAL:$1|változatra|változatokra}}',
'revdelete-success' => "'''A változat láthatósága sikeresen frissítve.'''",
'revdelete-failure' => "'''Nem sikerült frissíteni a változat láthatóságát:'''
$1",
'logdelete-success' => "'''Az esemény láthatóságának beállítása sikeresen elvégezve.'''",
'logdelete-failure' => "'''Nem sikerült módosítani a naplóbejegyzés láthatóságát:'''
$1",
'revdel-restore' => 'Láthatóság megváltoztatása',
'revdel-restore-deleted' => 'törölt lapváltozatok',
'revdel-restore-visible' => 'látható lapváltozatok',
'pagehist' => 'Laptörténet',
'deletedhist' => 'Törölt változatok',
'revdelete-hide-current' => 'Nem sikerült elrejteni a $1 $2-kori elemet: ez a jelenlegi változat, amit nem lehet elrejteni.',
'revdelete-show-no-access' => 'Nem lehet megjeleníteni a $2 $1-kori elemet, mert „korlátozottnak” van jelölve.',
'revdelete-modify-no-access' => 'Nem lehet módosítani a $2 $1-kori elemet, mert „korlátozottnak” van jelölve.',
'revdelete-modify-missing' => 'Nem sikerült módosítani a(z) $1 azonosítójú elemet, mert hiányzik az adatbázisból.',
'revdelete-no-change' => "'''Figyelem:''' a(z) $1 $2-kori elem már rendelkezik a kért láthatósági beállításokkal.",
'revdelete-concurrent-change' => 'Hiba történt a(z) $1 $2-kori elem módosítása közben: úgy tűnik, valaki megváltoztatta az állapotát, miközben módosítani próbáltad.
Ellenőrizd a naplókat.',
'revdelete-only-restricted' => 'Hiba a(z) $1 $2 időbélyegű elem elrejtésekor: nem rejthetsz el az adminisztrátorok elől elemeket anélkül, hogy ne választanál ki egy másik elrejtési beállítást.',
'revdelete-reason-dropdown' => '*Általános törlési okok
** Jogsértő tartalom
** Kényes személyes információk
** Potenciális becsületsértő információk',
'revdelete-otherreason' => 'Más/további ok:',
'revdelete-reasonotherlist' => 'Más ok',
'revdelete-edit-reasonlist' => 'Törlési okok szerkesztése',
'revdelete-offender' => 'Változat szerzője:',

# Suppression log
'suppressionlog' => 'Adatvédelmibiztos-napló',
'suppressionlogtext' => 'Lenn látható az adminisztrátorok elől legutóbb elrejtett törlések és blokkok listája. Lásd a [[Special:BlockList|blokkok listája]] lapot a jelenleg érvényben lévő kitiltásokhoz és blokkokhoz.',

# History merging
'mergehistory' => 'Laptörténetek egyesítése',
'mergehistory-header' => 'Ez az oldal lehetővé teszi egy oldal laptörténetének egyesítését egy másikéval.
Győződj meg róla, hogy a laptörténet folytonossága megmarad.',
'mergehistory-box' => 'Két oldal változatainak egyesítése:',
'mergehistory-from' => 'Forrásoldal:',
'mergehistory-into' => 'Céloldal:',
'mergehistory-list' => 'Egyesíthető laptörténet',
'mergehistory-merge' => '[[:$1]] és [[:$2]] következő változatai vonhatóak össze. A gombok segítségével választhatod ki, ha csak egy adott idő előttieket szeretnél feldolgozni.',
'mergehistory-go' => 'Egyesíthető szerkesztések mutatása',
'mergehistory-submit' => 'Változatok egyesítése',
'mergehistory-empty' => 'Nincs egyesíthető változás.',
'mergehistory-success' => '[[:$1]] {{PLURAL:$3|egy|$3}} változata sikeresen egyesítve lett a(z) [[:$2]] lappal.',
'mergehistory-fail' => 'Nem sikerült a laptörténetek egyesítése. Kérlek, ellenőrizd újra az oldalt és a megadott időparamétereket.',
'mergehistory-no-source' => 'Nem létezik forráslap $1 néven.',
'mergehistory-no-destination' => 'Nem létezik céllap $1 néven.',
'mergehistory-invalid-source' => 'A forráslapnak érvényes címet kell megadni.',
'mergehistory-invalid-destination' => 'A céllapnak érvényes címet kell megadni.',
'mergehistory-autocomment' => 'Egyesítette a(z) [[:$1]] lapot a(z) [[:$2]] lappal',
'mergehistory-comment' => 'Egyesítette a(z) [[:$1]] lapot a(z) [[:$2]] lappal: $3',
'mergehistory-same-destination' => 'A forrás- és a céllap nem egyezhet meg',
'mergehistory-reason' => 'Ok:',

# Merge log
'mergelog' => 'Egyesítési napló',
'pagemerge-logentry' => '[[$1]] és [[$2]] egyesítve ($3 változatig)',
'revertmerge' => 'Szétválasztás',
'mergelogpagetext' => 'A lapok egyesítéséről szóló napló. Szűkítheted a listát a műveletet végző szerkesztő, vagy az érintett oldal megadásával.',

# Diffs
'history-title' => 'A(z) „$1” laptörténete',
'difference-title' => '„$1” változatai közötti eltérés',
'difference-title-multipage' => 'Oldalak közötti különbség " $1 "és" $2 "',
'difference-multipage' => '(Lapok közti eltérés)',
'lineno' => '$1. sor:',
'compareselectedversions' => 'Kiválasztott változatok összehasonlítása',
'showhideselectedversions' => 'Kiválasztott változatok láthatóságának beállítása',
'editundo' => 'visszavonás',
'diff-empty' => '(Nincs különbség)',
'diff-multi' => '({{PLURAL:$2|egy|$2}} szerkesztő {{PLURAL:$1|egy|$1}} közbeeső változata nincs mutatva)',
'diff-multi-manyusers' => '({{PLURAL:$1|Egy közbeeső változat|$1 közbeeső változat}} nincs mutatva, amit $2 szerkesztő módosított)',
'difference-missing-revision' => 'A(z) "{{PAGENAME}}" nevű oldal #$1 $2 változata nem létezik.

Ezt általában egy elavult, törölt oldalra mutató laptörténeti hivatkozás használata okozza. Részletek a [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} törlési naplóban] találhatóak.',

# Search results
'searchresults' => 'A keresés eredménye',
'searchresults-title' => 'Keresési eredmények: „$1”',
'searchresulttext' => 'A keresésről a [[{{MediaWiki:Helppage}}|{{int:help}}]] lapon találhatsz további információkat.',
'searchsubtitle' => 'A keresett kifejezés: „[[:$1]]” ([[Special:Prefixindex/$1|minden, „$1” előtaggal kezdődő lap]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|a(z) „$1” lapra hivatkozó lapok]])',
'searchsubtitleinvalid' => "A keresett kulcsszó: „'''$1'''”",
'toomanymatches' => 'Túl sok találat van, próbálkozz egy másik lekérdezéssel',
'titlematches' => 'Címbeli egyezések',
'notitlematches' => 'Nincs megegyező cím',
'textmatches' => 'Szövegbeli egyezések',
'notextmatches' => 'Nincsenek szövegbeli egyezések',
'prevn' => 'előző {{PLURAL:$1|egy|$1}}',
'nextn' => 'következő {{PLURAL:$1|egy|$1}}',
'prevn-title' => 'Előző {{PLURAL:$1|egy|$1}} találat',
'nextn-title' => 'Következő {{PLURAL:$1|egy|$1}} találat',
'shown-title' => '{{PLURAL:$1|Egy|$1}} találat laponként',
'viewprevnext' => '($1 {{int:pipe-separator}} $2) ($3)',
'searchmenu-legend' => 'Keresési beállítások',
'searchmenu-exists' => "'''A wikin már van „[[:$1]]” nevű lap'''",
'searchmenu-new' => "'''Hozd létre a(z) „[[:$1]]” nevű lapot ezen a wikin!'''",
'searchmenu-prefix' => '[[Special:PrefixIndex/$1|Ilyen előtagú lapok listázása]]',
'searchprofile-articles' => 'Tartalmi oldalak',
'searchprofile-project' => 'Segítség- és projektlapok',
'searchprofile-images' => 'Médiafájlok',
'searchprofile-everything' => 'Minden lap',
'searchprofile-advanced' => 'Részletes',
'searchprofile-articles-tooltip' => 'A következőkben keres: $1',
'searchprofile-project-tooltip' => 'A következőkben keres: $1',
'searchprofile-images-tooltip' => 'Fájlok keresése',
'searchprofile-everything-tooltip' => 'Minden névtérben keres (a vitalapokat is beleértve)',
'searchprofile-advanced-tooltip' => 'Keresés adott névterekben',
'search-result-size' => '$1 ({{PLURAL:$2|egy|$2}} szó)',
'search-result-category-size' => '$1 oldal, $2 alkategória, $3 fájl',
'search-result-score' => 'Relevancia: $1%',
'search-redirect' => '(átirányítva innen: $1)',
'search-section' => '($1 szakasz)',
'search-suggest' => 'Keresési javaslat: $1',
'search-interwiki-caption' => 'Társlapok',
'search-interwiki-default' => '$1 találat',
'search-interwiki-more' => '(több)',
'search-relatedarticle' => 'Kapcsolódó',
'mwsuggest-disable' => 'Keresési javaslatok letiltása',
'searcheverything-enable' => 'Keresés az összes névtérben',
'searchrelated' => 'kapcsolódó',
'searchall' => 'mind',
'showingresults' => "Lent '''{{PLURAL:$1|egy|$1}}''' találat látható, az eleje '''$2'''.",
'showingresultsnum' => "Lent '''{{PLURAL:$3|egy|$3}}''' találat látható, az eleje '''$2'''.",
'showingresultsheader' => "{{PLURAL:$5|'''$1'''|'''$1 - $2'''}}. találat a(z) '''$4''' kifejezésre (összesen: '''$3''')",
'nonefound' => "'''Megjegyzés''': Alapértelmezésben a keresés nem terjed ki minden névtérre. Ha az összes névtérben keresni akarsz, írd az ''all:'' karaktersorozatot a keresett kifejezés elé.",
'search-nonefound' => 'Nincs egyezés a megadott szöveggel.',
'powersearch' => 'Részletes keresés',
'powersearch-legend' => 'Részletes keresés',
'powersearch-ns' => 'Névterek:',
'powersearch-redir' => 'Átirányítások megjelenítése',
'powersearch-field' => 'Keresett szöveg:',
'powersearch-togglelabel' => 'Megjelölés:',
'powersearch-toggleall' => 'Mind',
'powersearch-togglenone' => 'Egyik sem',
'search-external' => 'Külső kereső',
'searchdisabled' => 'Elnézésed kérjük, de a teljes szöveges keresés terhelési okok miatt átmenetileg nem használható. Ezidő alatt használhatod a lenti Google keresést, mely viszont lehetséges, hogy nem teljesen friss adatokkal dolgozik.',
'search-error' => 'A keresés közben hiba történt: $1',

# Preferences page
'preferences' => 'Beállítások',
'mypreferences' => 'Beállítások',
'prefs-edits' => 'Szerkesztéseid száma:',
'prefsnologin' => 'Nem jelentkeztél be',
'prefsnologintext' => 'Saját beállításaid elmentéséhez <span class="plainlinks">[{{fullurl:{{#Special:UserLogin}}|returnto=$1}} be kell jelentkezned.] </span>',
'changepassword' => 'Jelszócsere',
'prefs-skin' => 'Felület',
'skin-preview' => 'előnézet',
'datedefault' => 'Nincs beállítás',
'prefs-beta' => 'Béta funkciók',
'prefs-datetime' => 'Dátum és idő',
'prefs-labs' => 'Kísérleti funkciók',
'prefs-user-pages' => 'Felhasználói lapok',
'prefs-personal' => 'Felhasználói adatok',
'prefs-rc' => 'Friss változtatások',
'prefs-watchlist' => 'Figyelőlista',
'prefs-watchlist-days' => 'A figyelőlistában mutatott napok száma:',
'prefs-watchlist-days-max' => 'Legfeljebb $1 {{PLURAL:$1|nap|nap}}',
'prefs-watchlist-edits' => 'A kiterjesztett figyelőlistán mutatott szerkesztések száma:',
'prefs-watchlist-edits-max' => 'Legfeljebb 1000',
'prefs-watchlist-token' => 'A figyelőlista kulcsa:',
'prefs-misc' => 'Egyéb',
'prefs-resetpass' => 'Jelszó megváltoztatása',
'prefs-changeemail' => 'e-mail cím megváltoztatása',
'prefs-setemail' => 'e-mail cím megadása',
'prefs-email' => 'Levelezés',
'prefs-rendering' => 'Lapok megjelenítése',
'saveprefs' => 'Mentés',
'resetprefs' => 'Alaphelyzet',
'restoreprefs' => 'A beállítások alaphelyzetbe állítása',
'prefs-editing' => 'Szerkesztés',
'rows' => 'Sor',
'columns' => 'Oszlop',
'searchresultshead' => 'Keresés',
'resultsperpage' => 'Laponként mutatott találatok száma:',
'stub-threshold' => 'A hivatkozások <a href="#" class="stub">csonkként</a> történő formázásának határa (bájtban):',
'stub-threshold-disabled' => 'Kikapcsolva',
'recentchangesdays' => 'A friss változtatásokban mutatott napok száma:',
'recentchangesdays-max' => '(maximum {{PLURAL:$1|egy|$1}} nap)',
'recentchangescount' => 'Az alapértelmezettként mutatott szerkesztések száma:',
'prefs-help-recentchangescount' => 'Ez vonatkozik a friss változtatásokra, laptörténetekre és naplókra is.',
'prefs-help-watchlist-token2' => 'Ez a titkos kulcs a figyelőlistádhoz.
Aki ismeri, meg tudja nézni, milyen lapokat figyelsz, úgyhogy ne oszdd meg másokkal.
[[Special:ResetTokens|Kattints ide, ha meg akarod változtatni]].',
'savedprefs' => 'Az új beállításaid érvénybe léptek.',
'timezonelegend' => 'Időzóna:',
'localtime' => 'Helyi idő:',
'timezoneuseserverdefault' => 'Az alapértelmezett beállítás használata ($1)',
'timezoneuseoffset' => 'Egyéb (eltérés megadása)',
'timezoneoffset' => 'Eltérés¹:',
'servertime' => 'A kiszolgáló ideje:',
'guesstimezone' => 'Töltse ki a böngésző',
'timezoneregion-africa' => 'Afrika',
'timezoneregion-america' => 'Amerika',
'timezoneregion-antarctica' => 'Antarktisz',
'timezoneregion-arctic' => 'Északi-sark',
'timezoneregion-asia' => 'Ázsia',
'timezoneregion-atlantic' => 'Atlanti-óceán',
'timezoneregion-australia' => 'Ausztrália',
'timezoneregion-europe' => 'Európa',
'timezoneregion-indian' => 'Indiai-óceán',
'timezoneregion-pacific' => 'Csendes-óceán',
'allowemail' => 'E-mail engedélyezése más szerkesztőktől',
'prefs-searchoptions' => 'Keresés',
'prefs-namespaces' => 'Névterek',
'defaultns' => 'Egyébként a következő névterekben keressen:',
'default' => 'alapértelmezett',
'prefs-files' => 'Fájlok',
'prefs-custom-css' => 'saját CSS',
'prefs-custom-js' => 'saját JS',
'prefs-common-css-js' => 'Közös CSS/JS az összes felület számára:',
'prefs-reset-intro' => 'Ezen a lapon állíthatod vissza a beállításaidat az oldal alapértelmezett értékeire.
A műveletet nem lehet visszavonni.',
'prefs-emailconfirm-label' => 'E-mail cím megerősítése:',
'youremail' => 'Az e-mail címed:',
'username' => '{{GENDER:$1|Szerkesztőnév}}:',
'uid' => '{{GENDER:$1|Azonosító}}:',
'prefs-memberingroups' => '{{GENDER:$2|{{PLURAL:$1|Csoporttagság|Csoporttagságok}}}}:',
'prefs-registration' => 'Regisztráció ideje:',
'yourrealname' => 'Valódi neved:',
'yourlanguage' => 'A felület nyelve:',
'yourvariant' => 'A tartalom nyelvváltozata:',
'prefs-help-variant' => 'A választott variánsod vagy rendezési sorrendek, ahogy a wiki lapokat meg akarod jeleníteni.',
'yournick' => 'Aláírás:',
'prefs-help-signature' => 'A vitalapra írt hozzászólásaidat négy hullámvonallal (<nowiki>~~~~</nowiki>) írd alá. A lap mentésekor ez lecserélődik az aláírásodra és egy időbélyegre.',
'badsig' => 'Érvénytelen aláírás; ellenőrizd a HTML-formázást.',
'badsiglength' => 'Az aláírásod túl hosszú.
{{PLURAL:$1|Egy|$1}} karakternél rövidebbnek kell lennie.',
'yourgender' => 'Nem:',
'gender-unknown' => 'Nincs megadva',
'gender-male' => 'Férfi',
'gender-female' => 'Nő',
'prefs-help-gender' => 'Nem kötelező: a szoftver használja a nemtől függő üzenetek megjelenítéséhez. Az információ mindenki számára látható.',
'email' => 'E-mail',
'prefs-help-realname' => 'A valódi nevet nem kötelező megadni, de ha úgy döntesz, hogy megadod, azzal leszel feltüntetve a munkád szerzőjeként.',
'prefs-help-email' => 'Az e-mail cím megadása nem kötelező, de szükséges új jelszó kéréséhez, ha elfelejtenéd a meglévőt.',
'prefs-help-email-others' => 'Úgy is dönthetsz, hogy lehetővé teszed mások számára, hogy kapcsolatba lépjenek veled a felhasználói vagy vitalapodon keresztül, anélkül, hogy fel kellene fedned a személyazonosságodat.',
'prefs-help-email-required' => 'Meg kell adnod az e-mail címedet.',
'prefs-info' => 'Alapinformációk',
'prefs-i18n' => 'Nyelvi beállítások',
'prefs-signature' => 'Aláírás',
'prefs-dateformat' => 'Dátumformátum',
'prefs-timeoffset' => 'Időeltérés',
'prefs-advancedediting' => 'Általános',
'prefs-editor' => 'Szerkesztő',
'prefs-preview' => 'Előnézet',
'prefs-advancedrc' => 'Haladó beállítások',
'prefs-advancedrendering' => 'Haladó beállítások',
'prefs-advancedsearchoptions' => 'Haladó beállítások',
'prefs-advancedwatchlist' => 'Haladó beállítások',
'prefs-displayrc' => 'Megjelenítési beállítások',
'prefs-displaysearchoptions' => 'Megjelenítési beállítások',
'prefs-displaywatchlist' => 'Megjelenítési beállítások',
'prefs-diffs' => 'Eltérések (diffek)',

# User preference: email validation using jQuery
'email-address-validity-valid' => 'Az e-mail cím érvényesnek tűnik',
'email-address-validity-invalid' => 'Írj be egy érvényes e-mail címet',

# User rights
'userrights' => 'Szerkesztői jogok beállítása',
'userrights-lookup-user' => 'Szerkesztőcsoportok beállítása',
'userrights-user-editname' => 'Add meg a szerkesztő nevét:',
'editusergroup' => 'Szerkesztőcsoportok módosítása',
'editinguser' => "'''[[User:$1|$1]]''' szerkesztő jogainak megváltoztatása $2",
'userrights-editusergroup' => 'Szerkesztőcsoportok módosítása',
'saveusergroups' => 'Szerkesztőcsoportok mentése',
'userrights-groupsmember' => 'Csoporttag:',
'userrights-groupsmember-auto' => 'Alapértelmezetten tagja:',
'userrights-groups-help' => 'Beállíthatod, hogy a szerkesztő mely csoportokba tartozik.
* A bepipált doboz azt jelenti, hogy a szerkesztő benne van a csoportban, az üres azt, hogy nem.
* A * az olyan csoportokat jelöli, amelyeket ha egyszer hozzáadtál, nem távolíthatod el, vagy nem adhatod hozzá.',
'userrights-reason' => 'Ok:',
'userrights-no-interwiki' => 'Nincs jogod a szerkesztők jogainak módosításához más wikiken.',
'userrights-nodatabase' => '$1 adatbázis nem létezik vagy nem helyi.',
'userrights-nologin' => '[[Special:UserLogin|Be kell jelentkezned]] egy adminisztrátori fiókkal, hogy szerkesztői jogokat adhass.',
'userrights-notallowed' => 'Nincs jogosultságod jogosultságok adására vagy elvételére.',
'userrights-changeable-col' => 'Megváltoztatható csoportok',
'userrights-unchangeable-col' => 'Nem megváltoztatható csoportok',
'userrights-conflict' => 'Felhasználói jogok ütközése! Kérlek, végezd el újra a változtatásokat.',

# Groups
'group' => 'Csoport:',
'group-user' => 'szerkesztők',
'group-autoconfirmed' => 'automatikusan megerősített szerkesztők',
'group-bot' => 'botok',
'group-sysop' => 'adminisztrátorok',
'group-bureaucrat' => 'bürokraták',
'group-suppress' => 'adatvédelmi biztosok',
'group-all' => '(mind)',

'group-user-member' => '{{GENDER:$1|szerkesztő}}',
'group-autoconfirmed-member' => '{{GENDER:$1|automatikusan megerősített felhasználó}}',
'group-bot-member' => '{{GENDER:$1|bot}}',
'group-sysop-member' => '{{GENDER:$1|adminisztrátor}}',
'group-bureaucrat-member' => '{{GENDER:$1|bürokrata}}',
'group-suppress-member' => '{{GENDER:$1|adatvédelmi biztos}}',

'grouppage-user' => '{{ns:project}}:Felhasználók',
'grouppage-autoconfirmed' => '{{ns:project}}:Munkatársak#Automatikusan megerősített szerkesztők',
'grouppage-bot' => '{{ns:project}}:Botok',
'grouppage-sysop' => '{{ns:project}}:Adminisztrátorok',
'grouppage-bureaucrat' => '{{ns:project}}:Bürokraták',
'grouppage-suppress' => '{{ns:project}}:Adatvédelmi biztosok',

# Rights
'right-read' => 'lapok olvasása',
'right-edit' => 'lapok szerkesztése',
'right-createpage' => 'lapok készítése (nem vitalapok)',
'right-createtalk' => 'vitalapok készítése',
'right-createaccount' => 'új felhasználói fiók készítése',
'right-minoredit' => 'szerkesztések apróként jelölésének lehetősége',
'right-move' => 'lapok átnevezése',
'right-move-subpages' => 'lapok átnevezése az allapjukkal együtt',
'right-move-rootuserpages' => 'szerkesztői lapok mozgatása',
'right-movefile' => 'fájlok átnevezése',
'right-suppressredirect' => 'nem készít átirányítást a régi néven lapok átnevezésekor',
'right-upload' => 'fájlok feltöltése',
'right-reupload' => 'létező fájlok felülírása',
'right-reupload-own' => 'a saját maga által feltöltött fájlok felülírása',
'right-reupload-shared' => 'felülírhatja a közös megosztóhelyen lévő fájlokat helyben',
'right-upload_by_url' => 'fájl feltöltése URL-cím alapján',
'right-purge' => 'oldal gyorsítótárának ürítése megerősítés nélkül',
'right-autoconfirmed' => 'félig védett lapok szerkesztése',
'right-bot' => 'automatikus folyamatként való kezelés',
'right-nominornewtalk' => 'felhasználói lapok nem apró szerkesztésével megjelenik az új üzenet szöveg',
'right-apihighlimits' => 'nagyobb mennyiségű lekérdezés az API-n keresztül',
'right-writeapi' => 'a szerkesztő-API használata',
'right-delete' => 'lapok törlése',
'right-bigdelete' => 'nagy történettel rendelkező fájlok törlése',
'right-deletelogentry' => 'bizonyos napló bejegyzések törlése és visszaállítása',
'right-deleterevision' => 'lapok adott változatainak törlése és helyreállítása',
'right-deletedhistory' => 'törölt lapváltozatok megtekintése, a szövegük nélkül',
'right-deletedtext' => 'törölt változatok szövegének és a változatok közötti eltérés megtekintése',
'right-browsearchive' => 'keresés a törölt lapok között',
'right-undelete' => 'lap helyreállítása',
'right-suppressrevision' => 'az adminisztrátorok elől elrejtett változatok megtekintése és helyreállítása',
'right-suppressionlog' => 'privát naplók megtekintése',
'right-block' => 'szerkesztők blokkolása',
'right-blockemail' => 'szerkesztő e-mail küldési lehetőségének blokkolása',
'right-hideuser' => 'felhasználói név blokkolása és elrejtése a külvilág elől',
'right-ipblock-exempt' => 'IP-, auto- és tartományblokkok megkerülése',
'right-proxyunbannable' => 'proxyk automatikus blokkjainak megkerülése',
'right-unblockself' => 'saját felhasználói fiók blokkjának feloldása',
'right-protect' => 'védelmi szintek megváltoztatása és kaszkádolt védelemmel rendelkező lapok szerkesztése',
'right-editprotected' => '"{{int:protect-level-sysop}}" védelmi szintű lapok szerkesztése',
'right-editinterface' => 'felhasználói felület szerkesztése',
'right-editusercssjs' => 'más felhasználók CSS és JS fájljainak szerkesztése',
'right-editusercss' => 'más felhasználók CSS fájljainak szerkesztése',
'right-edituserjs' => 'más felhasználók JS fájljainak szerkesztése',
'right-rollback' => 'a lap utolsó szerkesztésének gyors visszaállítása',
'right-markbotedits' => 'visszaállított szerkesztések botként való jelölése',
'right-noratelimit' => 'sebességkorlát figyelmen kívül hagyása',
'right-import' => 'lapok importálása más wikikből',
'right-importupload' => 'lapok importálása fájl feltöltésével',
'right-patrol' => 'szerkesztések ellenőrzöttként való jelölése',
'right-autopatrol' => 'szerkesztések automatikusan ellenőrzöttként való jelölése',
'right-patrolmarks' => 'járőrök jelzéseinek megtekintése a friss változásokban',
'right-unwatchedpages' => 'nem figyelt lapok listájának megtekintése',
'right-mergehistory' => 'laptörténetek egyesítése',
'right-userrights' => 'az összes szerkesztő jogainak módosítása',
'right-userrights-interwiki' => 'más wikik szerkesztői jogainak módosítása',
'right-siteadmin' => 'adatbázis lezárása, felnyitása',
'right-override-export-depth' => 'Lapok exportálása a hivatkozott lapokkal együtt, legfeljebb 5-ös mélységig',
'right-sendemail' => 'e-mail küldése más felhasználóknak',
'right-passwordreset' => 'Jelszó visszaállítási emailek megtekintése',

# Special:Log/newusers
'newuserlogpage' => 'Új szerkesztők naplója',
'newuserlogpagetext' => 'Ez a napló az újonnan regisztrált szerkesztők listáját tartalmazza.',

# User rights log
'rightslog' => 'Szerkesztői jogosultságok naplója',
'rightslogtext' => 'Ez a rendszernapló a felhasználó jogosultságok változásait mutatja.',

# Associated actions - in the sentence "You do not have permission to X"
'action-read' => 'lap olvasása',
'action-edit' => 'lap szerkesztése',
'action-createpage' => 'új lap készítése',
'action-createtalk' => 'vitalap készítése',
'action-createaccount' => 'felhasználói fiók elkészítése',
'action-minoredit' => 'szerkesztés aprónak jelölése',
'action-move' => 'lap átnevezése',
'action-move-subpages' => 'lap és allapjainak átnevezése',
'action-move-rootuserpages' => 'szerkesztői lapok átnevezése',
'action-movefile' => 'fájlok átnevezése',
'action-upload' => 'fájl feltöltése',
'action-reupload' => 'már létező fájl felülírása',
'action-reupload-shared' => 'közös megosztón található fájl felülírása',
'action-upload_by_url' => 'fájl feltöltése URL-címről',
'action-writeapi' => 'író API használata',
'action-delete' => 'lap törlése',
'action-deleterevision' => 'változat törlése',
'action-deletedhistory' => 'lap törölt laptörténetének megtekintése',
'action-browsearchive' => 'keresés a törölt lapok között',
'action-undelete' => 'lap helyreállítása',
'action-suppressrevision' => 'rejtett változat megtekintése és helyreállítása',
'action-suppressionlog' => 'privát napló megtekintése',
'action-block' => 'szerkesztő blokkolása',
'action-protect' => 'lap védelmi szintjének megváltoztatása',
'action-rollback' => 'szerkesztések gyors visszaállítása az utolsó szerkesztő változatára egy adott oldalon',
'action-import' => 'lap importálása más wikiből',
'action-importupload' => 'lap importálása fájl feltöltésével',
'action-patrol' => 'mások szerkesztéseinek ellenőrzöttként való megjelölése',
'action-autopatrol' => 'saját szerkesztések ellenőrzöttként való megjelölése',
'action-unwatchedpages' => 'nem figyelt lapok listájának megtekintése',
'action-mergehistory' => 'lap laptörténetének egyesítése',
'action-userrights' => 'összes szerkesztő jogainak módosítása',
'action-userrights-interwiki' => 'más wikik szerkesztői jogainak módosítása',
'action-siteadmin' => 'adatbázis lezárása vagy felnyitása',
'action-sendemail' => 'e-mailek küldése',

# Recent changes
'nchanges' => '{{PLURAL:$1|egy|$1}} változtatás',
'enhancedrc-since-last-visit' => '$1 az utolsó látogatás óta',
'enhancedrc-history' => 'történet',
'recentchanges' => 'Friss változtatások',
'recentchanges-legend' => 'A friss változtatások beállításai',
'recentchanges-summary' => 'Ezen a lapon a wikiben történt legutóbbi fejleményeket lehet nyomon követni.',
'recentchanges-noresult' => 'A megadott időszakban nincs a feltételeknek megfelelő szerkesztés.',
'recentchanges-feed-description' => 'Kövesd a wiki friss változtatásait ezzel a hírcsatornával.',
'recentchanges-label-newpage' => 'Ezzel a szerkesztéssel egy új lap jött létre',
'recentchanges-label-minor' => 'Ez egy apró szerkesztés',
'recentchanges-label-bot' => 'Ezt a szerkesztést egy bot hajtotta végre',
'recentchanges-label-unpatrolled' => 'Ezt a szerkesztést még nem ellenőrizték',
'rcnote' => "Alább az utolsó '''{{PLURAL:$2|egy|$2}}''' nap utolsó '''{{PLURAL:$1|egy|$1}}''' változtatása látható. A lap generálásának időpontja $4, $5.",
'rcnotefrom' => 'Alább a <b>$2</b> óta történt változtatások láthatóak (<b>$1</b> db).',
'rclistfrom' => '$1 után történt változtatások megtekintése',
'rcshowhideminor' => 'apró szerkesztések $1',
'rcshowhidebots' => 'botok szerkesztéseinek $1',
'rcshowhideliu' => 'bejelentkezett felhasználók szerkesztéseinek $1',
'rcshowhideanons' => 'névtelen szerkesztések $1',
'rcshowhidepatr' => 'ellenőrzött szerkesztések $1',
'rcshowhidemine' => 'saját szerkesztések $1',
'rclinks' => 'Az elmúlt $2 nap utolsó $1 változtatása legyen látható<br />$3',
'diff' => 'eltér',
'hist' => 'történet',
'hide' => 'elrejtése',
'show' => 'megjelenítése',
'minoreditletter' => 'a',
'newpageletter' => 'Ú',
'boteditletter' => 'b',
'number_of_watching_users_pageview' => '[Jelenleg {{PLURAL:$1|egy|$1}} felhasználó figyeli]',
'rc_categories' => 'Szűkítés kategóriákra („|” jellel válaszd el őket)',
'rc_categories_any' => 'Bármelyik',
'rc-change-size-new' => '{{PLURAL:$1| egy bájt|$1 bájt}} módosítás után',
'newsectionsummary' => '/* $1 */ (új szakasz)',
'rc-enhanced-expand' => 'Részletek megjelenítése',
'rc-enhanced-hide' => 'Részletek elrejtése',
'rc-old-title' => 'eredetileg létrehozott " $1 "',

# Recent changes linked
'recentchangeslinked' => 'Kapcsolódó változtatások',
'recentchangeslinked-feed' => 'Kapcsolódó változtatások',
'recentchangeslinked-toolbox' => 'Kapcsolódó változtatások',
'recentchangeslinked-title' => 'A(z) $1 laphoz kapcsolódó változtatások',
'recentchangeslinked-summary' => "Alább azon lapoknak a legutóbbi változtatásai láthatóak, amelyekre hivatkozik egy megadott lap (vagy tagjai a megadott kategóriának).
A [[Special:Watchlist|figyelőlistádon]] szereplő lapok '''félkövérrel''' vannak jelölve.",
'recentchangeslinked-page' => 'Lap neve:',
'recentchangeslinked-to' => 'Inkább az erre linkelő lapok változtatásait mutasd',

# Upload
'upload' => 'Fájl feltöltése',
'uploadbtn' => 'Fájl feltöltése',
'reuploaddesc' => 'Visszatérés a feltöltési űrlaphoz.',
'upload-tryagain' => 'Módosított fájl-leírás elküldése',
'uploadnologin' => 'Nem vagy bejelentkezve',
'uploadnologintext' => '{{UCFIRST:$1}} fájlok feltöltéséhez.',
'upload_directory_missing' => 'A feltöltési könyvtár ($1) nem létezik vagy nem tudja létrehozni a kiszolgáló.',
'upload_directory_read_only' => 'A kiszolgálónak nincs írási jogosultsága a feltöltési könyvtárban ($1).',
'uploaderror' => 'Feltöltési hiba',
'upload-recreate-warning' => "'''Figyelmeztetés: az ilyen nevű fájlt törölték vagy átnevezték.'''

Az oldalhoz tartozó törlési és átnevezési naplóbejegyzések:",
'uploadtext' => "Az alábbi űrlap használatával tölthetsz fel fájlokat.
A korábban feltöltött képek megtekintéséhez vagy a köztük való kereséshez menj a [[Special:FileList|feltöltött fájlok listájához]], a feltöltések, újrafeltöltések a [[Special:Log/upload|feltöltési naplóban]], a törlések a [[Special:Log/delete|törlési naplóban]] vannak jegyezve.

Képet a következő módon illeszthetsz be egy oldalra: '''<nowiki>[[</nowiki>{{ns:file}}<nowiki>:Kép.jpg]]</nowiki>''',
'''<nowiki>[[</nowiki>{{ns:file}}<nowiki>:Kép.png|alternatív szöveg]]</nowiki>''' vagy a közvetlen hivatkozáshoz használd a
'''<nowiki>[[</nowiki>{{ns:media}}<nowiki>:Fájl.ogg]]</nowiki>''' formát.",
'upload-permitted' => 'Engedélyezett fájltípusok: $1.',
'upload-preferred' => 'Támogatott fájltípusok: $1.',
'upload-prohibited' => 'Tiltott fájltípusok: $1.',
'uploadlog' => 'feltöltési napló',
'uploadlogpage' => 'Feltöltési napló',
'uploadlogpagetext' => 'Lentebb látható a legutóbbi felküldések listája.
Lásd még az [[Special:NewFiles|új fáljlok galériáját]]',
'filename' => 'Fájlnév',
'filedesc' => 'Összefoglaló',
'fileuploadsummary' => 'Összefoglaló:',
'filereuploadsummary' => 'Változtatások:',
'filestatus' => 'Szerzői jogi állapot:',
'filesource' => 'Forrás:',
'uploadedfiles' => 'Feltöltött fájlok',
'ignorewarning' => 'Biztosan így akarom feltölteni',
'ignorewarnings' => 'Hagyd figyelmen kívül a figyelmeztetéseket',
'minlength1' => 'A fájlnévnek legalább egy betűből kell állnia.',
'illegalfilename' => 'A „$1” lap neve olyan karaktereket tartalmaz, melyek nincsenek megengedve lapcímben. Kérlek, változtasd meg a nevet, és próbálkozz a mentéssel újra.',
'filename-toolong' => 'A fájlok neve nem lehet hosszabb 240 bájtnál.',
'badfilename' => 'A fájl új neve „$1”.',
'filetype-mime-mismatch' => 'A fájl kiterjesztése („.$1”) nem egyezik meg az észlelt MIME-típussal ($2).',
'filetype-badmime' => '„$1” MIME-típusú fájlokat nem lehet feltölteni.',
'filetype-bad-ie-mime' => 'A fájlt nem lehet feltölteni, mert az Internet Explorer „$1” típusúnak tekintené, ami tiltott és potenciálisan veszélyes fájltípus.',
'filetype-unwanted-type' => "A(z) '''„.$1”''' nem javasolt fájltípus.
Az ajánlott {{PLURAL:$3|típus|típusok}}: $2.",
'filetype-banned-type' => "A következő {{PLURAL:$4|fájltípus nem engedélyezett|fájltípusok nem engedélyezettek}}: '''„.$1”'''
Engedélyezett {{PLURAL:$3|típus|típusok}}: $2.",
'filetype-missing' => 'A fájlnak nincs kiterjesztése (pl. „.jpg”).',
'empty-file' => 'Az elküldött fájl üres volt.',
'file-too-large' => 'Az elküldött fájl túl nagy volt.',
'filename-tooshort' => 'A fájlnév túl rövid.',
'filetype-banned' => 'Az ilyen típusú fájlok tiltva vannak.',
'verification-error' => 'Ez a fájl nem felelt meg az ellenőrzésen (hibás, rossz kiterjesztés, stb.).',
'hookaborted' => 'A módosítást, amit próbáltál elvégezni megszakította egy kiterjesztés-hook.',
'illegal-filename' => 'A fájlnév nem engedélyezett.',
'overwrite' => 'Nem engedélyezett felülírni egy létező fájlt.',
'unknown-error' => 'Ismeretlen hiba történt.',
'tmp-create-error' => 'Nem sikerült létrehozni az ideiglenes fájlt.',
'tmp-write-error' => 'Hiba az ideiglenes fájl írásakor.',
'large-file' => 'Javasoljuk, hogy ne tölts fel olyan fájlokat, melyek nagyobbak, mint $1;
ez a fájl $2.',
'largefileserver' => 'A fájl mérete meghaladja a kiszolgálón beállított maximális értéket.',
'emptyfile' => 'Az általad feltöltött fájl üresnek tűnik.
Ez valószínűleg azért van, mert hibásan adtad meg a feltöltendő fájl nevét.
Ellenőrizd, hogy valóban fel akarod-e tölteni ezt a fájlt.',
'windows-nonascii-filename' => 'A wiki nem támogatja a speciális karaktereket tartalmazó fájlneveket.',
'fileexists' => '<strong>[[:$1]]</strong> névvel már létezik egy állomány.
Ellenőrizd, hogy biztosan felül akarod-e írni! [[$1|thumb]]',
'filepageexists' => 'Ehhez a fájlnévhez már létezik leírás a <strong>[[:$1]]</strong> lapon, de jelenleg nincs feltöltve ilyen nevű fájl.
A leírás, amit ebbe az űrlapba írsz, nem fogja felülírni a már létezőt.
Ha meg szeretnéd változtatni a leírást, meg kell nyitnod szerkesztésre a lapjot.
[[$1|thumb]]',
'fileexists-extension' => 'Már van egy hasonló nevű feltöltött fájl: [[$2|thumb]]
* A feltöltendő fájl neve: <strong>[[:$1]]</strong>
* A már létező fájl neve: <strong>[[:$2]]</strong>
Kérjük, hogy válassz másik nevet.',
'fileexists-thumbnail-yes' => "A fájl egy kisméretű képnek ''(bélyegképnek)'' tűnik. [[$1|thumb]]
Kérjük, hogy ellenőrizd a(z) <strong>[[:$1]]</strong> fájlt.
Ha az ellenőrzött fájl ugyanakkora, mint az eredeti méretű kép, akkor nincs szükség bélyegkép feltöltésére.",
'file-thumbnail-no' => "A fájlnév a(z) <strong>$1</strong> karakterlánccal kezdődik.
Úgy tűnik, hogy ez egy kisméretű kép ''(bélyegkép)''.
Ha rendelkezel a teljesméretű képpel, akkor töltsd fel azt, egyébként kérjük, hogy változtasd meg a fájlnevet.",
'fileexists-forbidden' => 'Már létezik egy ugyanilyen nevű fájl, és nem lehet felülírni.
Ha még mindig fel szeretnéd tölteni a fájlt, menj vissza, és adj meg egy új nevet. [[File:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => 'Egy ugyanilyen nevű fájl már létezik a közös fájlmegosztóban; kérlek menj vissza és válassz egy másik nevet a fájlnak, ha még mindig fel akarod tölteni! [[File:$1|thumb|center|$1]]',
'file-exists-duplicate' => 'Ez a következő {{PLURAL:$1|fájl|fájlok}} duplikátuma:',
'file-deleted-duplicate' => 'Egy ehhez hasonló fájlt ([[:$1]]) korábban már töröltek. Ellenőrizd a fájl törlési naplóját, mielőtt újra feltöltenéd.',
'uploadwarning' => 'Feltöltési figyelmeztetés',
'uploadwarning-text' => 'Kérlek módosítsd a fájl leírását alább, majd próbáld újra.',
'savefile' => 'Fájl mentése',
'uploadedimage' => '„[[$1]]” felküldve',
'overwroteimage' => 'feltöltötte a(z) „[[$1]]” fájl új változatát',
'uploaddisabled' => 'Feltöltések kikapcsolva',
'copyuploaddisabled' => 'A feltöltés URL alapján le van tiltva.',
'uploadfromurl-queued' => 'A feltöltésed a várakozási sorba került.',
'uploaddisabledtext' => 'A fájlfeltöltés nem engedélyezett.',
'php-uploaddisabledtext' => 'A PHP-s fájlfeltöltés le van tiltva. Ellenőrizd a file_uploads beállítást.',
'uploadscripted' => 'Ez a fájl olyan HTML- vagy parancsfájlkódot tartalmaz, melyet tévedésből egy webböngésző esetleg értelmezni próbálhatna.',
'uploadvirus' => 'Ez a fájl vírust tartalmaz! A részletek: $1',
'uploadjava' => 'A fájl egy ZIP-fájl, ami egy Java .class fájlt tartalmaz.
Java fájlok feltöltése nem engedélyezett, mert segítségükkel kijátszhatóak a biztonsági korlátozások.',
'upload-source' => 'Forrásfájl',
'sourcefilename' => 'Forrásfájl neve:',
'sourceurl' => 'A forrás URL-címe:',
'destfilename' => 'Célfájlnév:',
'upload-maxfilesize' => 'Maximális fájlméret: $1',
'upload-description' => 'A fájl leírása',
'upload-options' => 'Feltöltési beállítások',
'watchthisupload' => 'Fájl figyelése',
'filewasdeleted' => 'Korábban valaki már feltöltött ilyen néven egy fájlt, amelyet később töröltünk. Ellenőrizd a $1 bejegyzését, nehogy újra feltöltsd ugyanezt a fájlt.',
'filename-bad-prefix' => "Annak a fájlnak a neve, amelyet fel akarsz tölteni '''„$1”''' karakterekkel kezdődik. Ilyeneket általában a digitális kamerák adnak a fájloknak, automatikusan, azonban ezek nem írják le annak tartalmát. Válassz egy leíró nevet!",
'filename-prefix-blacklist' => ' #<!-- ezt a sort hagyd így --> <pre>
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
'upload-success-subj' => 'A feltöltés sikerült',
'upload-success-msg' => 'A feltöltés (innen $2) sikeres volt. A feltöltésed itt érhető el: [[:{{ns:file}}:$1]]',
'upload-failure-subj' => 'Feltöltési hiba',
'upload-failure-msg' => 'Probléma történt a feltöltéseddel (innen: $2):

$1',
'upload-warning-subj' => 'Feltöltési figyelmeztetés',
'upload-warning-msg' => 'Hiba történt a feltöltéseddel innen: [$2]. Visszatérhetsz a [[Special:Upload/stash/$1|feltöltéshez]], hogy orvosold a hibát.',

'upload-proto-error' => 'Hibás protokoll',
'upload-proto-error-text' => 'A távoli feltöltéshez <code>http://</code> vagy <code>ftp://</code> kezdetű URL-ekre van szükség.',
'upload-file-error' => 'Belső hiba',
'upload-file-error-text' => 'Belső hiba történt egy ideiglenes fájl szerveren történő létrehozásakor.
Kérjük, hogy lépj kapcsolatba egy  [[Special:ListUsers/sysop|adminisztrátorral]].',
'upload-misc-error' => 'Ismeretlen feltöltési hiba',
'upload-misc-error-text' => 'A feltöltés során ismeretlen hiba történt.  Kérjük, ellenőrizd, hogy az URL érvényes-e és hozzáférhető-e, majd próbáld újra.  Ha a probléma továbbra is fennáll, akkor lépj kapcsolatba a [[Special:ListUsers/sysop|adminisztrátorral]].',
'upload-too-many-redirects' => 'Az URL túl sokszor volt átirányítva',
'upload-unknown-size' => 'Ismeretlen méretű',
'upload-http-error' => 'HTTP-hiba történt: $1',
'upload-copy-upload-invalid-domain' => 'Másolás nem engedélyezett ebből a tartományból.',

# File backend
'backend-fail-stream' => 'Nem sikerült sugározni ezt a fájlt: $1.',
'backend-fail-backup' => 'Nem lehet elmenteni ezt a fájlt: $1.',
'backend-fail-notexists' => 'Ez a fájl nem létezik: $1 .',
'backend-fail-hashes' => 'Nem lehet lekérni a hash értéket az összehasonlításhoz.',
'backend-fail-notsame' => 'Egy nem azonos fájl már létezik $1 néven.',
'backend-fail-invalidpath' => '$1 nem érvényes tárolási útvonal.',
'backend-fail-delete' => 'Nem sikerült törölni ezt a fájlt: $1 .',
'backend-fail-describe' => 'Nem lehet megváltoztatna a "$1" fájl metaadatát.',
'backend-fail-alreadyexists' => 'Ez a fájl már létezik: $1 .',
'backend-fail-store' => 'Nem sikerült a(z) $1 fájl tárolása $2 helyen.',
'backend-fail-copy' => 'Nem sikerült a(z) $1 fájl másolása $2 helyre.',
'backend-fail-move' => 'Nem sikerült a(z) $1 fájl mozgatása $2 helyre.',
'backend-fail-opentemp' => 'Nem lehet megnyitni az ideiglenes fájlt.',
'backend-fail-writetemp' => 'Nem lehet írni az ideiglenes fájlba.',
'backend-fail-closetemp' => 'Nem lehet lezárni az ideiglenes fájlt.',
'backend-fail-read' => 'Nem sikerült olvasni ebből a fájlból: $1.',
'backend-fail-create' => 'Nem sikerült írni ebbe a fájlba: $1.',
'backend-fail-maxsize' => 'Nem lehet írni ezt a fájlt: $1, mert a mérete nagyobb, mint $2 bájt.',
'backend-fail-readonly' => 'A(z) „$1” tárolórendszer jelenleg csak olvasható. Ennek oka a következő: „$2”',
'backend-fail-synced' => 'A(z) „$1” fájl inkonzisztens állapotban van a tárolórendszerek között',
'backend-fail-connect' => 'Nem sikerült csatlakozni a(z) „$1” tárolórendszerhez.',
'backend-fail-internal' => 'Ismeretlen hiba keletkezett a(z) „$1” tárolórendszerben.',
'backend-fail-contenttype' => 'Nem lehetett a fájl típusát meghatározni a „$1” helyen történő tároláshoz.',
'backend-fail-batchsize' => 'A tárolórendszer {{PLURAL:$1|1|$1}} fájlműveletet tartalmazó parancsfájlt kapott; legfeljebb {{PLURAL:$2|1|$2}} műveletből állót kaphat.',
'backend-fail-usable' => 'Nem lehet olvasni vagy írni a "$1" fájlt, jogosultság hiánya, vagy hiányzó könyvtár/konténer miatt.',

# File journal errors
'filejournal-fail-dbconnect' => 'Nem sikerült csatlakozni a napló adatbázis "$1 " háttér tárolójához.',
'filejournal-fail-dbquery' => 'Nem sikerült frissíteni a naplóadatbázis "$1 " háttértárolóját.',

# Lock manager
'lockmanager-notlocked' => 'Nem lehet a zárolást feloldani: „$1”; nincs zárolva.',
'lockmanager-fail-closelock' => 'Nem sikerült a „$1” zárolási fájljának bezárása.',
'lockmanager-fail-deletelock' => 'Nem sikerült a(z) „$1” zárolási fájljának törlése.',
'lockmanager-fail-acquirelock' => 'Nem sikerült zárolást igényelni a „$1” fájlhoz.',
'lockmanager-fail-openlock' => 'Nem sikerült a „$1” zárolási fájljának megnyitása.',
'lockmanager-fail-releaselock' => 'Nem sikerült a(z) „$1” fájl zárolásának feloldása.',
'lockmanager-fail-db-bucket' => 'Nem sikerült kapcsolatot létesíteni elég adatbázis zároláshoz a $1 vödörben.',
'lockmanager-fail-db-release' => 'Nem lehet a $1 adatbázis zárolását feloldani.',
'lockmanager-fail-svr-acquire' => 'Nem sikerült zárolást igényelni a $1 szerveren.',
'lockmanager-fail-svr-release' => 'Nem lehet a(z) $1 szerver zárolását feloldani.',

# ZipDirectoryReader
'zip-file-open-error' => 'Hiba történt a ZIP fájlokon végzett ellenőrzés elindítása közben.',
'zip-wrong-format' => 'A fájl sérült, vagy más miatt olvashatatlan ZIP fájl.
Nem lehet megfelelően ellenőrizni a biztonságosságát.',
'zip-bad' => 'A fájl sérült, vagy más miatt olvashatatlan ZIP fájl.
Nem lehet megfelelően ellenőrizni a biztonságosságát.',
'zip-unsupported' => 'A fájl ZIP fájl, ami olyan ZIP funkciókat használ, melyeket nem támogat a MediaWiki.
Nem lehet megfelelően ellenőrizni a biztonságosságát.',

# Special:UploadStash
'uploadstash' => 'Feltöltéstároló',
'uploadstash-summary' => 'Ezen a lapon lehet hozzáférni azokhoz a fájlokhoz, melyek fel lettek töltve (vagy épp feltöltés alatt vannak), de még nem lettek közzétéve a wikin. Az ilyen fájlok csak a feltöltőik számára láthatóak.',
'uploadstash-clear' => 'Tárolt fájlok törlése',
'uploadstash-nofiles' => 'Nincsenek tárolt fájljaid.',
'uploadstash-badtoken' => 'A művelet végrehajtása sikertelen volt. Lehetséges, hogy lejártak a szerkesztést hitelesítő adataid. Próbáld újra!',
'uploadstash-errclear' => 'A fájlok törlése nem sikerült.',
'uploadstash-refresh' => 'Fájlok listájának frissítése',
'invalid-chunk-offset' => 'Érvénytelen darab eltolás',

# img_auth script messages
'img-auth-accessdenied' => 'Hozzáférés megtagadva',
'img-auth-nopathinfo' => 'Hiányzó PATH_INFO.
A szerver nincs beállítva, hogy továbbítsa ezt az információt.
Lehet, hogy CGI-alapú, és nem támogatja az img_auth-ot.
Lásd https://www.mediawiki.org/wiki/Manual:Image_Authorization!',
'img-auth-notindir' => 'A kért elérési út nincs a beállított feltöltési könyvtárban.',
'img-auth-badtitle' => 'Nem sikerült érvényes címet készíteni a(z) „$1” szövegből.',
'img-auth-nologinnWL' => 'Nem vagy bejelentkezve, és a(z) „$1” nincs az engedélyezési listán.',
'img-auth-nofile' => 'A fájl („$1”) nem létezik.',
'img-auth-isdir' => 'Megpróbáltál hozzáférni a(z) „$1” könyvtárhoz, azonban csak a fájlokhoz lehet.',
'img-auth-streaming' => '„$1” továbbítása.',
'img-auth-public' => 'Az img_auth.php funkciója az, hogy fájlokat közvetítsen egy privát wikiből.
Ez a wiki publikus, így a biztonság miatt az img_auth.php ki van kapcsolva.',
'img-auth-noread' => 'A szerkesztő nem jogosult a(z) „$1” olvasására.',
'img-auth-bad-query-string' => 'Az URL-cím érvénytelen lekérdezést tartalmaz.',

# HTTP errors
'http-invalid-url' => 'Érvénytelen URL-cím: $1',
'http-invalid-scheme' => 'A(z) „$1” sémájú URL-ek nem támogatottak.',
'http-request-error' => 'A HTTP-kérés nem sikerült egy ismeretlen hiba miatt.',
'http-read-error' => 'HTTP-olvasási hiba.',
'http-timed-out' => 'A HTTP-kérés túllépte a határidőt.',
'http-curl-error' => 'Hiba történt az URL lekérésekor: $1',
'http-bad-status' => 'Probléma történt a HTTP-kérés közben: $1 $2',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6' => 'Nem érhető el az URL',
'upload-curl-error6-text' => 'A megadott URL nem érhető el.  Kérjük, ellenőrizd újra, hogy az URL pontos-e, és a webhely működik-e.',
'upload-curl-error28' => 'Feltöltési időtúllépés',
'upload-curl-error28-text' => 'A webhely túl sokára válaszolt. Kérjük, ellenőrizd, hogy a webhely elérhető-e, várj egy kicsit, aztán próbáld újra. Kevésbé forgalmas időben is megpróbálhatod.',

'license' => 'Licenc:',
'license-header' => 'Licenc',
'nolicense' => 'Válassz licencet!',
'license-nopreview' => '(Előnézet nem elérhető)',
'upload_source_url' => ' (egy érvényes, nyilvánosan elérhető URL)',
'upload_source_file' => ' (egy fájl a számítógépeden)',

# Special:ListFiles
'listfiles-summary' => 'Ezen a speciális lapon látható az összes feltöltött fájl.
Amennyiben a szerkesztő szűrést állított be, úgy csak azok a fájlok jelennek meg, amikor a szerkesztő töltötte fel a legfrissebb verziót.',
'listfiles_search_for' => 'Keresés fájl nevére:',
'imgfile' => 'fájl',
'listfiles' => 'Fájllista',
'listfiles_thumb' => 'Bélyegkép',
'listfiles_date' => 'Dátum',
'listfiles_name' => 'Név',
'listfiles_user' => 'feltöltő',
'listfiles_size' => 'Méret',
'listfiles_description' => 'Leírás',
'listfiles_count' => 'Változatok',

# File description page
'file-anchor-link' => 'Fájl',
'filehist' => 'Fájltörténet',
'filehist-help' => 'Kattints egy időpontra, hogy a fájl akkori állapotát láthasd.',
'filehist-deleteall' => 'összes törlése',
'filehist-deleteone' => 'törlés',
'filehist-revert' => 'visszaállít',
'filehist-current' => 'aktuális',
'filehist-datetime' => 'Dátum/idő',
'filehist-thumb' => 'Bélyegkép',
'filehist-thumbtext' => 'Bélyegkép a $1-kori változatról',
'filehist-nothumb' => 'Nincs bélyegkép',
'filehist-user' => 'Feltöltő',
'filehist-dimensions' => 'Felbontás',
'filehist-filesize' => 'Fájlméret',
'filehist-comment' => 'Megjegyzés',
'filehist-missing' => 'A fájl hiányzik',
'imagelinks' => 'Fájlhasználat',
'linkstoimage' => 'Az alábbi {{PLURAL:$1|lap hivatkozik|lapok hivatkoznak}} erre a fájlra:',
'linkstoimage-more' => 'Több, mint {{PLURAL:$1|egy|$1}} oldal hivatkozik erre a fájlra.
A következő lista csak az {{PLURAL:$1|első linket|első $1 linket}} tartalmazza.
A teljes lista [[Special:WhatLinksHere/$2|ezen a lapon]] található meg.',
'nolinkstoimage' => 'Erre a fájlra nem hivatkozik lap.',
'morelinkstoimage' => '[[Special:WhatLinksHere/$1|További hivatkozások]] megtekintése',
'linkstoimage-redirect' => '$1 (fájlátirányítás) $2',
'duplicatesoffile' => 'A következő {{PLURAL:$1|fájl|$1 fájl}} ennek a fájlnak a duplikátuma ([[Special:FileDuplicateSearch/$2|további részletek]]):',
'sharedupload' => 'Ez a fájl a(z) $1 megosztott tárhelyről származik, és más projektek is használhatják.',
'sharedupload-desc-there' => 'Ez a fájl a $1 megosztott tárhelyről származik, és más projektek is használhatják.
Az [$2 ottani leírólapján] további információkat találhatsz róla.',
'sharedupload-desc-here' => 'Ez a fájl a $1 megosztott tárhelyről származik, és más projektek is használhatják.
A [$2 fájl ottani leírólapjának] másolata alább látható.',
'filepage-nofile' => 'Nem létezik ilyen nevű fájl.',
'filepage-nofile-link' => 'Nem létezik ilyen nevű fájl. [$1 Ide kattintva] feltölthetsz egyet.',
'uploadnewversion-linktext' => 'Új változat feltöltése',
'shared-repo-from' => 'a(z) $1 megosztott tárhelyről',
'shared-repo' => 'megosztott tárhely',
'filepage.css' => '/* Az itt elhelyezett CSS a fájl leíró lapra kerül beillesztésre, a külföldi nyelvű kliens wikikbe is*/',
'upload-disallowed-here' => 'Ezt a fájlt nem lehet felülírni.',

# File reversion
'filerevert' => '$1 visszaállítása',
'filerevert-legend' => 'Fájl visszaállítása',
'filerevert-intro' => '<span class="plainlinks">A(z) \'\'\'[[Media:$1|$1]]\'\'\' fájl [$4 verzióját állítod vissza, dátum: $3, $2].</span>',
'filerevert-comment' => 'Ok:',
'filerevert-defaultcomment' => 'A $2, $1-i verzió visszaállítása',
'filerevert-submit' => 'Visszaállítás',
'filerevert-success' => '<span class="plainlinks">A(z) \'\'\'[[Media:$1|$1]]\'\'\' fájl visszaállítása a(z) [$4 verzióra, $3, $2] sikerült.</span>',
'filerevert-badversion' => 'A megadott időbélyegzésű fájlnak nincs helyi változata.',

# File deletion
'filedelete' => '$1 törlése',
'filedelete-legend' => 'Fájl törlése',
'filedelete-intro' => "Törölni készülsz a(z) '''[[Media:$1|$1]]''' médiafájlt, a teljes fájltörténetével együtt.",
'filedelete-intro-old' => '<span class="plainlinks">A(z) \'\'\'[[Media:$1|$1]]\'\'\' fájl, dátum: [$4 $3, $2] változatát törlöd.</span>',
'filedelete-comment' => 'Ok:',
'filedelete-submit' => 'Törlés',
'filedelete-success' => "A(z) '''$1''' médiafájlt törölted.",
'filedelete-success-old' => "A(z) '''[[Media:$1|$1]]''' $3, $2-kori változata sikeresen törölve lett.",
'filedelete-nofile' => "'''$1''' nem létezik.",
'filedelete-nofile-old' => "A(z) '''$1''' fájlnak nincs a megadott tulajdonságokkal rendelkező archivált változata.",
'filedelete-otherreason' => 'Más/további ok:',
'filedelete-reason-otherlist' => 'Más ok',
'filedelete-reason-dropdown' => '*Általános törlési okok
** Szerzői jog megsértése
** Duplikátum',
'filedelete-edit-reasonlist' => 'Törlési okok szerkesztése',
'filedelete-maintenance' => 'A fájlok törlése és helyreállítása ideiglenesen le van tiltva karbantartás miatt.',
'filedelete-maintenance-title' => 'Nem lehet törölni a fájlt',

# MIME search
'mimesearch' => 'Keresés MIME-típus alapján',
'mimesearch-summary' => 'Ez az oldal engedélyezi a fájlok MIME-típus alapján történő szűrését. Bevitel: tartalomtípus/altípus, pl. <code>image/jpeg</code>.',
'mimetype' => 'MIME-típus:',
'download' => 'letöltés',

# Unwatched pages
'unwatchedpages' => 'Nem figyelt lapok',

# List redirects
'listredirects' => 'Átirányítások listája',

# Unused templates
'unusedtemplates' => 'Nem használt sablonok',
'unusedtemplatestext' => 'Ez a lap azon {{ns:template}} névtérbe tartozó lapokat gyűjti össze, melyek nincsenek használva egyetlen lapon sem.
Ellenőrizd a meglévő hivatkozásokat, mielőtt törölnéd őket.',
'unusedtemplateswlh' => 'más hivatkozások',

# Random page
'randompage' => 'Lap találomra',
'randompage-nopages' => 'A következő {{PLURAL:$2|névtérben|névterekben}} nincsenek lapok: $1.',

# Random redirect
'randomredirect' => 'Átirányítás találomra',
'randomredirect-nopages' => 'A(z) „$1” névtérben nincsenek átirányítások.',

# Statistics
'statistics' => 'Statisztika',
'statistics-header-pages' => 'Lapstatisztikák',
'statistics-header-edits' => 'Szerkesztési statisztika',
'statistics-header-views' => 'Látogatási statisztika',
'statistics-header-users' => 'Szerkesztői statisztika',
'statistics-header-hooks' => 'További statisztikák',
'statistics-articles' => 'Tartalommal rendelkező lapok',
'statistics-pages' => 'Lapok száma',
'statistics-pages-desc' => 'A wikiben található összes lap, beleértve a vitalapokat és az átirányításokat is',
'statistics-files' => 'Feltöltött fájlok',
'statistics-edits' => 'Szerkesztések száma a(z) {{SITENAME}} indulása óta',
'statistics-edits-average' => 'Szerkesztések átlagos száma laponként',
'statistics-views-total' => 'Összes megtekintés',
'statistics-views-total-desc' => 'A nem létező és speciális lapok megtekintési adatai nincsenek beleszámolva.',
'statistics-views-peredit' => 'Megtekintések szerkesztésenként',
'statistics-users' => 'Regisztrált [[Speciális:Szerkesztők listája|szerkesztők]]',
'statistics-users-active' => 'Aktív szerkesztők',
'statistics-users-active-desc' => 'Szerkesztők, akik csináltak valamit az elmúlt {{PLURAL:$1|egy|$1}} napban',
'statistics-mostpopular' => 'Legtöbbször megtekintett lapok',

'doubleredirects' => 'Dupla átirányítások',
'doubleredirectstext' => 'Ez a lap azokat a lapokat listázza, melyek átirányító lapokra irányítanak át.
Minden sor tartalmaz egy hivatkozást az első, valamint a második átirányításra, valamint a második átirányítás céljára, ami általában a valódi céllap, erre kellene az első átirányításnak mutatnia.
Az <del>áthúzott</del> sorok a lista elkészülése óta javítva lettek.',
'double-redirect-fixed-move' => '[[$1]] átnevezve, a továbbiakban átirányításként működik a(z) [[$2]] lapra',
'double-redirect-fixed-maintenance' => '[[$1]] dupla átirányítás javítása a következőre: [[$2]]',
'double-redirect-fixer' => 'Átirányításjavító',

'brokenredirects' => 'Nem létező lapra mutató átirányítások',
'brokenredirectstext' => 'A következő átirányítások nem létező lapokra hivatkoznak:',
'brokenredirects-edit' => 'szerkesztés',
'brokenredirects-delete' => 'törlés',

'withoutinterwiki' => 'Nyelvközi hivatkozás nélküli lapok',
'withoutinterwiki-summary' => 'A következő lapok nem hivatkoznak más nyelvű változatokra:',
'withoutinterwiki-legend' => 'Előtag',
'withoutinterwiki-submit' => 'Megjelenítés',

'fewestrevisions' => 'Legrövidebb laptörténetű lapok',

# Miscellaneous special pages
'nbytes' => '{{PLURAL:$1|egy|$1}} bájt',
'ncategories' => '{{PLURAL:$1|egy|$1}} kategória',
'ninterwikis' => '{{PLURAL:$1|egy|$1}} interwiki',
'nlinks' => '{{PLURAL:$1|egy|$1}} hivatkozás',
'nmembers' => '{{PLURAL:$1|egy|$1}} elem',
'nrevisions' => '{{PLURAL:$1|egy|$1}} változat',
'nviews' => '{{PLURAL:$1|egy|$1}} megtekintés',
'nimagelinks' => '{{PLURAL:$1|Egy|$1}} lapon van használva',
'ntransclusions' => '{{PLURAL:$1|egy|$1}} lapon van használva',
'specialpage-empty' => 'Ez az oldal üres.',
'lonelypages' => 'Árva lapok',
'lonelypagestext' => 'A következő lapok nincsenek linkelve vagy beillesztve más lapokra a(z) {{SITENAME}} wikin.',
'uncategorizedpages' => 'Kategorizálatlan lapok',
'uncategorizedcategories' => 'Kategorizálatlan kategóriák',
'uncategorizedimages' => 'Kategorizálatlan fájlok',
'uncategorizedtemplates' => 'Kategorizálatlan sablonok',
'unusedcategories' => 'Nem használt kategóriák',
'unusedimages' => 'Nem használt fájlok',
'popularpages' => 'Népszerű lapok',
'wantedcategories' => 'Keresett kategóriák',
'wantedpages' => 'Keresett lapok',
'wantedpages-badtitle' => 'Érvénytelen cím található az eredményhalmazban: $1',
'wantedfiles' => 'Keresett fájlok',
'wantedfiletext-cat' => 'A következő fájlok használatban vannak, de nem léteznek. Külső tárhelyről származó fájlok akkor is a listára kerülhetnek, ha léteznek. Az ilyen hamis riasztások <del>áthúzva</del> jelennek meg. Ezen felül az olyan beágyazott fájlok, amelyek nem léteznek a  [[:$1]] kategóriában jelennek meg.',
'wantedfiletext-nocat' => 'A következő fájlok használatban vannak, de nem léteznek. Külső tárhelyről származó fájlok akkor is a listára kerülhetnek, ha léteznek. Az ilyen hamis riasztások <del>áthúzva</del> jelennek meg.',
'wantedtemplates' => 'Keresett sablonok',
'mostlinked' => 'Legtöbbet hivatkozott lapok',
'mostlinkedcategories' => 'Legtöbbet hivatkozott kategóriák',
'mostlinkedtemplates' => 'Legtöbbet hivatkozott sablonok',
'mostcategories' => 'Legtöbb kategóriába tartozó lapok',
'mostimages' => 'Legtöbbet hivatkozott fájlok',
'mostinterwikis' => 'Legtöbb interwikit tartalmazó lapok',
'mostrevisions' => 'Legtöbbet szerkesztett lapok',
'prefixindex' => 'Keresés előtag szerint',
'prefixindex-namespace' => 'Összes lap adott előtaggal ($1 névtér)',
'shortpages' => 'Rövid lapok',
'longpages' => 'Hosszú lapok',
'deadendpages' => 'Zsákutcalapok',
'deadendpagestext' => 'Az itt található lapok nem kapcsolódnak hivatkozásokkal ezen wiki más oldalaihoz.',
'protectedpages' => 'Védett lapok',
'protectedpages-indef' => 'Csak a meghatározatlan idejű védelmek',
'protectedpages-cascade' => 'Csak a kaszkádvédelmek',
'protectedpagestext' => 'A következő lapok átnevezés vagy szerkesztés ellen védettek',
'protectedpagesempty' => 'Jelenleg nincsenek ilyen paraméterekkel védett lapok.',
'protectedtitles' => 'Létrehozás ellen védett lapok',
'protectedtitlestext' => 'A következő lapok védve vannak a létrehozás ellen',
'protectedtitlesempty' => 'Jelenleg nincsenek ilyen típusú védett lapok.',
'listusers' => 'Szerkesztők',
'listusers-editsonly' => 'Csak a szerkesztéssel rendelkező szerkesztők mutatása',
'listusers-creationsort' => 'Rendezés létrehozási dátum szerint',
'usereditcount' => '{{PLURAL:$1|egy|$1}} szerkesztés',
'usercreated' => '{{GENDER:$3|Létrehozva}} $1, $2-kor',
'newpages' => 'Új lapok',
'newpages-username' => 'Felhasználói név:',
'ancientpages' => 'Régóta nem változott szócikkek',
'move' => 'Átnevezés',
'movethispage' => 'Nevezd át ezt a lapot',
'unusedimagestext' => 'Az alábbi fájlokat nem használjuk egyetlen oldalon sem.
Vedd figyelembe, hogy más weboldalak közvetlenül hivatkozhatnak egy fájl URL-jére, ezért szerepelhet itt annak ellenére, hogy aktívan használják.',
'unusedcategoriestext' => 'A következő kategóriákban egyetlen szócikk, illetve alkategória sem szerepel.',
'notargettitle' => 'Nincs cél',
'notargettext' => 'Nem adtad meg annak a lapnak vagy szerkesztőnek a nevét, amin a műveletet végre akartad hajtani.',
'nopagetitle' => 'A megadott céllap nem létezik',
'nopagetext' => 'A megadott céllap nem létezik.',
'pager-newer-n' => '{{PLURAL:$1|1 újabb|$1 újabb}}',
'pager-older-n' => '{{PLURAL:$1|1 régebbi|$1 régebbi}}',
'suppress' => 'adatvédelmi biztos',
'querypage-disabled' => 'Ez a speciális lap a megfelelő teljesítmény fenntartása érdekében le van tiltva.',

# Book sources
'booksources' => 'Könyvforrások',
'booksources-search-legend' => 'Könyvforrások keresése',
'booksources-go' => 'Keresés',
'booksources-text' => 'Alább látható a másik webhelyekre mutató hivatkozások listája, ahol új és használt könyveket árulnak, és
további információkat lelhetsz ott az általad keresett könyvekről:',
'booksources-invalid-isbn' => 'A megadott ISBN hibásnak tűnik; ellenőrizd, hogy jól másoltad-e át az eredeti forrásból.',

# Special:Log
'specialloguserlabel' => 'Szerkesztő:',
'speciallogtitlelabel' => 'Cél (cím vagy felhasználó):',
'log' => 'Rendszernaplók',
'all-logs-page' => 'Minden nyilvános napló',
'alllogstext' => 'A(z) {{SITENAME}} naplóinak összesített listája.
A napló típusának, a szerkesztő nevének (kis- és nagybetűérzékeny), vagy az érintett lap kiválasztásával (ez is kis- és nagybetűérzékeny) szűkítheted a találatok listáját.',
'logempty' => 'Nincs illeszkedő naplóbejegyzés.',
'log-title-wildcard' => 'Így kezdődő címek keresése',
'showhideselectedlogentries' => 'Kijelölt napló bejegyzések megjelenítése/elrejtése',

# Special:AllPages
'allpages' => 'Az összes lap listája',
'alphaindexline' => '$1 – $2',
'nextpage' => 'Következő lap ($1)',
'prevpage' => 'Előző lap ($1)',
'allpagesfrom' => 'Lapok listázása a következő címtől kezdve:',
'allpagesto' => 'Lapok listázása a következő címig:',
'allarticles' => 'Az összes lap listája',
'allinnamespace' => 'Összes lap ($1 névtér)',
'allnotinnamespace' => 'Minden olyan lap, ami nem a(z) $1 névtérben van.',
'allpagesprev' => 'Előző',
'allpagesnext' => 'Következő',
'allpagessubmit' => 'Keresés',
'allpagesprefix' => 'Lapok listázása, amik ezzel az előtaggal kezdődnek:',
'allpagesbadtitle' => 'A megadott lapnév nyelvközi vagy wikiközi előtagot tartalmazott, vagy érvénytelen volt. Talán olyan karakter van benne, amit nem lehet lapnevekben használni.',
'allpages-bad-ns' => 'A(z) {{SITENAME}} webhelyen nincs "$1" névtér.',
'allpages-hide-redirects' => 'Átirányítások elrejtése',

# SpecialCachedPage
'cachedspecial-viewing-cached-ttl' => 'A lap tárolt változatát látod, aminek utolsó frissítése ennyi ideje volt:  $1',
'cachedspecial-viewing-cached-ts' => 'Az oldal tárolt változatát látod, ami eltérhet az aktuálistól.',
'cachedspecial-refresh-now' => 'A legfrissebb változat megjelenítése.',

# Special:Categories
'categories' => 'Kategóriák',
'categoriespagetext' => 'A következő {{PLURAL:$1|kategória tartalmaz|kategóriák tartalmaznak}} lapokat vagy fájlokat.
A [[Special:UnusedCategories|nem használt kategóriák]] nem jelennek meg.
Lásd még a [[Special:WantedCategories|keresett kategóriák]] listáját.',
'categoriesfrom' => 'Kategóriák listázása a következő névtől kezdve:',
'special-categories-sort-count' => 'rendezés elemszám szerint',
'special-categories-sort-abc' => 'rendezés ABC szerint',

# Special:DeletedContributions
'deletedcontributions' => 'Törölt szerkesztések',
'deletedcontributions-title' => 'Törölt szerkesztések',
'sp-deletedcontributions-contribs' => 'közreműködései',

# Special:LinkSearch
'linksearch' => 'Külső hivatkozások keresése',
'linksearch-pat' => 'Keresett minta:',
'linksearch-ns' => 'Névtér:',
'linksearch-ok' => 'keresés',
'linksearch-text' => 'Helyettesítő karaktereket is lehet használni, például "*.wikipedia.org".
Legalább egy felső szintű tartománynak lennie kell, például "*.org"<br />
Támogatott {{PLURAL:$2|protokoll|protokollok}}: <code>$1</code> (http:// az alapértelmezett, ha nincs protokoll megadva).',
'linksearch-line' => '$1 hivatkozva innen: $2',
'linksearch-error' => 'Helyettesítő karakterek csak a cím elején szerepelhetnek.',

# Special:ListUsers
'listusersfrom' => 'Szerkesztők listázása a következő névtől kezdve:',
'listusers-submit' => 'Megjelenítés',
'listusers-noresult' => 'Nem található szerkesztő.',
'listusers-blocked' => '(blokkolva)',

# Special:ActiveUsers
'activeusers' => 'Aktív szerkesztők listája',
'activeusers-intro' => 'Ez a lap azon felhasználók listáját tartalmazza, akik csináltak valamilyen tevékenységet az elmúlt {{PLURAL:$1|egy|$1}} napban.',
'activeusers-count' => '$1 szerkesztés az utolsó $3 napban',
'activeusers-from' => 'Szerkesztők listázása a következő névtől kezdve:',
'activeusers-hidebots' => 'Botok elrejtése',
'activeusers-hidesysops' => 'Adminisztrátorok elrejtése',
'activeusers-noresult' => 'Nem található ilyen szerkesztő.',

# Special:ListGroupRights
'listgrouprights' => 'Szerkesztői csoportok jogai',
'listgrouprights-summary' => 'Lenn láthatóak a wikiben létező szerkesztői csoportok, valamint az azokhoz tartozó jogok.
Az egyes csoportokról további információt [[{{MediaWiki:Listgrouprights-helppage}}|itt]] találhatsz.',
'listgrouprights-key' => '* <span class="listgrouprights-granted">Kapott jog</span>
* <span class="listgrouprights-revoked">Elvett jog</span>',
'listgrouprights-group' => 'Csoport',
'listgrouprights-rights' => 'Jogok',
'listgrouprights-helppage' => 'Help:Szerkesztői csoportok jogai',
'listgrouprights-members' => '(tagok listája)',
'listgrouprights-addgroup' => '{{PLURAL:$2|ehhez a csoporthoz|ezekhez a csoportokhoz}} adhat szerkesztőket: $1',
'listgrouprights-removegroup' => '{{PLURAL:$2|ebből a csoportból|ezekből a csoportokból}} távolíthat el szerkesztőket: $1',
'listgrouprights-addgroup-all' => 'bármelyik csoporthoz adhat szerkesztőket',
'listgrouprights-removegroup-all' => 'bármelyik csoportból távolíthat el szerkesztőket',
'listgrouprights-addgroup-self' => 'hozzáadhatja a következő {{PLURAL:$2|csoporthoz|csoportokhoz}} a saját fiókját: $1',
'listgrouprights-removegroup-self' => 'eltávolíthatja a következő {{PLURAL:$2|csoportból|csoportokból}} a saját fiókját: $1',
'listgrouprights-addgroup-self-all' => 'az összes csoportot hozzáadhatja a saját fiókjához',
'listgrouprights-removegroup-self-all' => 'az összes csoporból eltávolíthatja a saját fiókját',

# Email user
'mailnologin' => 'Nincs feladó',
'mailnologintext' => 'Ahhoz hogy másoknak e-mailt küldhess, [[Special:UserLogin|be kell jelentkezned]] és meg kell adnod egy érvényes e-mail címet a [[Special:Preferences|beállításaidban]].',
'emailuser' => 'E-mail küldése ezen szerkesztőnek',
'emailuser-title-target' => 'E-mail küldése ennek a felhasználónak: $1',
'emailuser-title-notarget' => 'E-mail küldése a felhasználónak',
'emailpage' => 'E-mail küldése',
'emailpagetext' => '{{GENDER:$1|user}} nevű szerkesztő e-mail-címére ezen űrlap kitöltésével üzenetet tudsz küldeni.
Feladóként a [[Special:Preferences|beállításaid]]nál megadott e-mail-címed fog szerepelni, így a címzett közvetlenül tud majd válaszolni neked.',
'usermailererror' => 'A levélküldő objektum hibával tért vissza:',
'defemailsubject' => '{{SITENAME}} e-mail a következő felhasználótól: „$1”',
'usermaildisabled' => 'Email fogadás letiltva',
'usermaildisabledtext' => 'Nem küldhetsz emailt más felhasználóknak ezen a wikin',
'noemailtitle' => 'Nincs e-mail cím',
'noemailtext' => 'Ez a szerkesztő nem adott meg érvényes e-mail címet.',
'nowikiemailtitle' => 'Nem küldhető e-mail üzenet',
'nowikiemailtext' => 'Ez a szerkesztő nem kíván másoktól e-mail üzeneteket fogadni.',
'emailnotarget' => 'A címzett nem létezik vagy a felhasználónév érvénytelen.',
'emailtarget' => 'Írd be címzett felhasználónevét',
'emailusername' => 'Felhasználónév:',
'emailusernamesubmit' => 'Küldés',
'email-legend' => 'E-mail küldése egy másik {{SITENAME}}-szerkesztőnek',
'emailfrom' => 'Feladó:',
'emailto' => 'Címzett:',
'emailsubject' => 'Tárgy:',
'emailmessage' => 'Üzenet:',
'emailsend' => 'Küldés',
'emailccme' => 'Az üzenet másolatát küldje el nekem is e-mailben.',
'emailccsubject' => '$1 szerkesztőnek küldött $2 tárgyú üzenet másolata',
'emailsent' => 'E-mail elküldve',
'emailsenttext' => 'Az e-mail üzenetedet elküldtem.',
'emailuserfooter' => 'Ezt az e-mailt $1 küldte $2 számára, az „E-mail küldése ezen szerkesztőnek” funkció használatával a(z) {{SITENAME}} wikin.',

# User Messenger
'usermessage-summary' => 'Rendszerüzenet megadása.',
'usermessage-editor' => 'Rendszerüzenetek',

# Watchlist
'watchlist' => 'Figyelőlistám',
'mywatchlist' => 'Figyelőlista',
'watchlistfor2' => '$1 részére $2',
'nowatchlist' => 'Nincs lap a figyelőlistádon.',
'watchlistanontext' => 'A figyelőlistád megtekintéséhez és szerkesztéséhez $1.',
'watchnologin' => 'Nem vagy bejelentkezve',
'watchnologintext' => 'Ahhoz, hogy figyelőlistád lehessen, [[Special:UserLogin|be kell lépned]].',
'addwatch' => 'Hozzáadás a figyelőlistához',
'addedwatchtext' => "A(z) „[[:$1]]” lapot hozzáadtam a [[Special:Watchlist|figyelőlistádhoz]].
Ezután minden, a lapon vagy annak vitalapján történő változást ott fogsz látni, és a lap '''vastagon''' fog szerepelni a [[Special:RecentChanges|friss változtatások]] lapon, hogy könnyen észrevehető legyen.",
'removewatch' => 'Eltávolítás a figyelőlistáról',
'removedwatchtext' => 'A(z) „[[:$1]]” lapot eltávolítottam a [[Special:Watchlist|figyelőlistáról]].',
'watch' => 'Lap figyelése',
'watchthispage' => 'Lap figyelése',
'unwatch' => 'Lapfigyelés vége',
'unwatchthispage' => 'Figyelés leállítása',
'notanarticle' => 'Nem szócikk',
'notvisiblerev' => 'A változat törölve lett',
'watchlist-details' => 'A vitalapokon kívül {{PLURAL:$1|egy|$1}} lap van a figyelőlistádon.',
'wlheader-enotif' => 'Az e-mailen keresztül történő értesítés engedélyezve.',
'wlheader-showupdated' => "Azok a lapok, amelyek megváltoztak, mióta utoljára megnézted őket, '''vastagon''' láthatóak.",
'watchmethod-recent' => 'a figyelt lapokon belüli legfrissebb szerkesztések',
'watchmethod-list' => 'a legfrissebb szerkesztésekben található figyelt lapok',
'watchlistcontains' => 'A figyelőlistádon {{PLURAL:$1|egy|$1}} lap szerepel.',
'iteminvalidname' => "Probléma a '$1' elemmel: érvénytelen név...",
'wlnote' => "Alább az utolsó '''{{PLURAL:$2|egy|$2}}''' óra '''{{PLURAL:$1|egy|$1}}''' változtatása látható. A lap generálásának ideje $3, $4.",
'wlshowlast' => 'Az elmúlt $1 órában | $2 napon | $3 történt változtatások legyenek láthatóak',
'watchlist-options' => 'A figyelőlista beállításai',

# Displayed when you click the "watch" button and it is in the process of watching
'watching' => 'Figyelés...',
'unwatching' => 'Figyelés befejezése...',
'watcherrortext' => 'Hiba történt a(z) „$1” lapra vonatkozó figyelőlista-beállítások módosítása közben.',

'enotif_mailer' => '{{SITENAME}} Értesítéspostázó',
'enotif_reset' => 'Az összes lap megjelölése felkeresettként',
'enotif_impersonal_salutation' => '{{SITENAME}} felhasználó',
'enotif_subject_deleted' => '$2 törölte a $1 {{SITENAME}} oldalt.',
'enotif_subject_moved' => '$2 átmozgatta a $1 {{SITENAME}} oldalt.',
'enotif_subject_restored' => '$2 visszaállította a $1 {{SITENAME}} oldalt.',
'enotif_subject_changed' => '$2 megváltoztatta a $1 {{SITENAME}} oldalt.',
'enotif_body_intro_deleted' => '$2 törölte a $1 {{SITENAME}} oldalt $PAGEEDITDATE-kor, lásd $3.',
'enotif_body_intro_created' => '$2 létrehozta a $1 {{SITENAME}} oldalt $PAGEEDITDATE-kor, lásd az aktuális verziót itt: $3.',
'enotif_body_intro_moved' => '$2 átmozgatta a $1 {{SITENAME}} oldalt $PAGEEDITDATE-kor, lásd az aktuális verziót itt: $3.',
'enotif_body_intro_restored' => '$2 visszaállította a $1 {{SITENAME}} oldalt $PAGEEDITDATE-kor, lásd az aktuális verziót itt: $3.',
'enotif_body_intro_changed' => '$2 megváltoztatta a $1 {{SITENAME}} oldalt $PAGEEDITDATE-kor, lásd az aktuális verziót itt: $3.',
'enotif_lastvisited' => 'Lásd a $1 lapot az utolsó látogatásod óta történt változtatásokért.',
'enotif_lastdiff' => 'Lásd a $1 lapot ezen változtatás megtekintéséhez.',
'enotif_anon_editor' => '$1 névtelen felhasználó',
'enotif_body' => 'Kedves $WATCHINGUSERNAME!

$PAGEINTRO $NEWPAGE

A szerkesztési összefoglaló a következő volt: $PAGESUMMARY $PAGEMINOREDIT

A szerkesztő elérhetősége:
e-mail küldése: $PAGEEDITOR_EMAIL
wiki: $PAGEEDITOR_WIKI

Amíg nem keresed fel az oldalt, addig nem érkeznek újabb értesítések az oldal változásaival kapcsolatban. A figyelőlistádon is beállíthatod, hogy újból kapj értesítéseket, az összes lap után.

             Baráti üdvözlettel: a(z) {{SITENAME}} értesítő rendszere

--
Az e-mail értesítéseid módosításához keresd fel a 
{{canonicalurl:{{#special:Preferences}}}} címet

A figyelőlistád módosításához keresd fel a
{{canonicalurl:{{#special:EditWatchlist}}}} címet

A lap figyelőlistádról való törléséhez keresd fel a
$UNWATCHURL címet

Visszajelzés és további segítség:
{{canonicalurl:{{MediaWiki:Helppage}}}}',
'created' => 'létrehozta',
'changed' => 'megváltoztatta',

# Delete
'deletepage' => 'Lap törlése',
'confirm' => 'Megerősítés',
'excontent' => 'a lap tartalma: „$1”',
'excontentauthor' => 'a lap tartalma: „$1” (és csak „[[Special:Contributions/$2|$2]]” szerkesztette)',
'exbeforeblank' => 'az eltávolítás előtti tartalom: „$1”',
'exblank' => 'a lap üres volt',
'delete-confirm' => '$1 törlése',
'delete-legend' => 'Törlés',
'historywarning' => "'''Figyelem:''' a lapnak, amit törölni készülsz, körülbelül $1 változattal rendelkező laptörténete van:",
'confirmdeletetext' => 'Egy lapot vagy fájlt készülsz törölni a teljes laptörténetével együtt.
Kérjük, erősítsd meg, hogy valóban ezt szeretnéd tenni, átlátod a következményeit, és hogy a műveletet a [[{{MediaWiki:Policy-url}}|törlési irányelvekkel]] összhangban végzed.',
'actioncomplete' => 'Művelet végrehajtva',
'actionfailed' => 'A művelet nem sikerült',
'deletedtext' => 'A(z) „$1” lapot törölted.
A legutóbbi törlések listájához lásd a $2 lapot.',
'dellogpage' => 'Törlési napló',
'dellogpagetext' => 'Itt láthatók a legutóbb törölt lapok.',
'deletionlog' => 'törlési napló',
'reverted' => 'Visszaállítva a korábbi változatra',
'deletecomment' => 'Ok:',
'deleteotherreason' => 'További indoklás:',
'deletereasonotherlist' => 'Egyéb indok',
'deletereason-dropdown' => '*Gyakori törlési okok
** Szerző kérésére
** Jogsértő
** Vandalizmus',
'delete-edit-reasonlist' => 'Törlési okok szerkesztése',
'delete-toobig' => 'Ennek a lapnak a laptörténete több mint {{PLURAL:$1|egy|$1}} változatot őriz. A szervert kímélendő az ilyen lapok törlése nem engedélyezett.',
'delete-warning-toobig' => 'Ennek a lapnak a laptörténete több mint {{PLURAL:$1|egy|$1}} változatot őriz. Törlése fennakadásokat okozhat a wiki adatbázis-műveleteiben; óvatosan járj el.',

# Rollback
'rollback' => 'Szerkesztések visszaállítása',
'rollback_short' => 'Visszaállítás',
'rollbacklink' => 'visszaállítás',
'rollbacklinkcount' => '$1 szerkesztés visszaállítása',
'rollbacklinkcount-morethan' => 'több mint $1 szerkesztés visszaállítása',
'rollbackfailed' => 'A visszaállítás nem sikerült',
'cantrollback' => 'Nem lehet visszaállítani: az utolsó szerkesztést végző felhasználó az egyetlen, aki a lapot szerkesztette.',
'alreadyrolled' => '[[:$1]] utolsó, [[User:$2|$2]] ([[User talk:$2|vita]]{{int:pipe-separator}}[[Special:Contributions/$2|{{int:contribslink}}]]) általi szerkesztését nem lehet visszavonni:
időközben valaki már visszavonta, vagy szerkesztette a lapot.

Az utolsó szerkesztést [[User:$3|$3]] ([[User talk:$3|vita]]{{int:pipe-separator}}[[Special:Contributions/$3|{{int:contribslink}}]]) végezte.',
'editcomment' => "A szerkesztési összefoglaló „''$1''” volt.",
'revertpage' => 'Visszaállítottam a lap korábbi változatát: [[Special:Contributions/$2|$2]]  ([[User talk:$2|vita]]) szerkesztéséről [[User:$1|$1]] szerkesztésére',
'revertpage-nouser' => 'Visszaállítottam a lap korábbi változatát (szerkesztőnév eltávolítva) szerkesztéséről [[User:$1|$1]] szerkesztésére',
'rollback-success' => '$1 szerkesztéseit visszaállítottam $2 utolsó változatára.',

# Edit tokens
'sessionfailure-title' => 'Munkamenethiba',
'sessionfailure' => 'Úgy látszik, hogy probléma van a bejelentkezési munkameneteddel;
ez a művelet a munkamenet eltérítése miatti óvatosságból megszakadt.
Kérjük, hogy nyomd meg a "vissza" gombot, és töltsd le újra az oldalt, ahonnan jöttél, majd próbáld újra.',

# Protect
'protectlogpage' => 'Lapvédelmi napló',
'protectlogtext' => 'Alább látható a lapvédelemmel kapcsolatos változtatások listája.
A [[Special:ProtectedPages|védett lapok listáján]] megtekintheted a jelenleg is érvényben lévő védelmeket.',
'protectedarticle' => 'levédte a(z) [[$1]] lapot',
'modifiedarticleprotection' => 'megváltoztatta a(z) „[[$1]]” lap védelmi szintjét',
'unprotectedarticle' => 'eltávolította a védelmet a(z) „[[$1]]” lapról',
'movedarticleprotection' => 'áthelyezte „[[$2]]” védelmi beállításait „[[$1]]” cím alá',
'protect-title' => '„$1” levédése',
'protect-title-notallowed' => '„$1” védelmi szintjének megtekintése',
'prot_1movedto2' => '[[$1]] lapot átneveztem [[$2]] névre',
'protect-badnamespace-title' => 'Nem védhető névtér',
'protect-badnamespace-text' => 'Ebben a névtérben az oldalak nem védhetők.',
'protect-legend' => 'Levédés megerősítése',
'protectcomment' => 'Ok:',
'protectexpiry' => 'Időtartam',
'protect_expiry_invalid' => 'A lejárati idő érvénytelen.',
'protect_expiry_old' => 'A lejárati idő a múltban van.',
'protect-unchain-permissions' => 'További védelmi lehetőségek feloldása',
'protect-text' => "Itt megtekintheted és módosíthatod a(z) '''$1''' lap védelmi szintjét.",
'protect-locked-blocked' => "Nem változtathatod meg a védelmi szinteket, amíg blokkolnak. Itt vannak a(z)
'''$1''' lap jelenlegi beállításai:",
'protect-locked-dblock' => "A védelmi szinteket egy aktív adatbázis zárolás miatt nem változtathatod meg.
Itt vannak a(z) '''$1''' lap jelenlegi beállításai:",
'protect-locked-access' => "A fiókod számára nem engedélyezett a védelmi szintek megváltoztatása.
Itt vannak a(z) '''$1''' lap jelenlegi beállításai:",
'protect-cascadeon' => 'A lap le van védve, mert {{PLURAL:$1|tartalmazza az alábbi lap, amelyen|tartalmazzák az alábbi lapok, amelyeken}}
be van kapcsolva a kaszkád védelem.
Megváltoztathatod ezen lap védelmi szintjét, de az nem lesz hatással a kaszkád védelemre.',
'protect-default' => 'Minden szerkesztő számára engedélyezett',
'protect-fallback' => '"$1" engedély szükséges hozzá',
'protect-level-autoconfirmed' => 'Csak automatikusan ellenőrzött szerkesztőknek engedélyezett (nem vagy frissen regisztráltaknak nem)',
'protect-level-sysop' => 'Csak adminisztrátoroknak engedélyezett',
'protect-summary-cascade' => 'kaszkád védelem',
'protect-expiring' => 'lejár: $1 (UTC)',
'protect-expiring-local' => 'lejárat: $1',
'protect-expiry-indefinite' => 'határozatlan',
'protect-cascade' => 'Kaszkád védelem – védjen le minden lapot, amit ez a lap tartalmaz.',
'protect-cantedit' => 'Nem változtathatod meg a lap védelmi szintjét, mert nincs jogod a szerkesztéséhez.',
'protect-othertime' => 'Más időtartam:',
'protect-othertime-op' => 'más időtartam',
'protect-existing-expiry' => 'Jelenleg érvényben lévő lejárati idő: $2, $3',
'protect-otherreason' => 'További okok:',
'protect-otherreason-op' => 'Más/további ok:',
'protect-dropdown' => '*Általános védelmi okok
** Gyakori vandalizmus
** Gyakori spamelés
** Nagyforgalmú lap',
'protect-edit-reasonlist' => 'Lapvédelem oka',
'protect-expiry-options' => '1 óra:1 hour,1 nap:1 day,1 hét:1 week,2 hét:2 weeks,1 hónap:1 month,3 hónap:3 months,6 hónap:6 months,1 év:1 year,végtelen:infinite',
'restriction-type' => 'Engedély:',
'restriction-level' => 'Korlátozási szint:',
'minimum-size' => 'Legkisebb méret',
'maximum-size' => 'Legnagyobb méret',
'pagesize' => '(bájt)',

# Restrictions (nouns)
'restriction-edit' => 'Szerkesztés',
'restriction-move' => 'Átnevezés',
'restriction-create' => 'Létrehozás',
'restriction-upload' => 'Feltöltés',

# Restriction levels
'restriction-level-sysop' => 'teljesen védett',
'restriction-level-autoconfirmed' => 'félig védett',
'restriction-level-all' => 'bármilyen szint',

# Undelete
'undelete' => 'Törölt lap helyreállítása',
'undeletepage' => 'Törölt lapok megtekintése és helyreállítása',
'undeletepagetitle' => "'''A(z) [[:$1]] lap törölt változatai alább láthatók.'''",
'viewdeletedpage' => 'Törölt lapok megtekintése',
'undeletepagetext' => 'Az alábbi {{PLURAL:$1|lapot törölték, de még helyreállítható|$1 lapot törölték, de még helyreállíthatók}} az archívumból.
Az archívumot időről időre üríthetik!',
'undelete-fieldset-title' => 'Változatok helyreállítása',
'undeleteextrahelp' => "A lap teljes helyreállításához ne jelölj be egy jelölőnégyzetet sem, csak kattints a '''''{{int:undeletebtn}}''''' gombra.
A lap részleges helyreállításához jelöld be a kívánt változatok melletti jelölőnégyzeteket, és kattints a '''''{{int:undeletebtn}}''''' gombra.",
'undeleterevisions' => '{{PLURAL:$1|egy|$1}} változat archiválva',
'undeletehistory' => 'Ha helyreállítasz egy lapot, azzal visszahozod laptörténet összes változatát.
Ha lap törlése óta azonos néven már létrehoztak egy újabb lapot, a helyreállított
változatok a laptörténet végére kerülnek be, a jelenlegi lapváltozat módosítása nélkül.',
'undeleterevdel' => 'A visszavonás nem hajtható végre, ha a legfrissebb lapváltozat részben
törlését eredmémyezi. Ilyen esetekben törölnöd kell a legújabb törölt változatok kijelölését, vagy megszüntetni az elrejtésüket. Azon fájlváltozatok,
melyek megtekintése a számodra nem engedélyezett, nem kerülnek visszaállításra.',
'undeletehistorynoadmin' => 'Ezt a szócikket törölték. A törlés okát alább az összegzésben
láthatod, az oldalt a törlés előtt szerkesztő felhasználók részleteivel együtt. Ezeknek
a törölt változatoknak a tényleges szövege csak az adminisztrátorok számára hozzáférhető.',
'undelete-revision' => '$1 $4, $5-kori törölt változata (szerző: $3).',
'undeleterevision-missing' => 'Érvénytelen vagy hiányzó változat. Lehet, hogy rossz hivatkozásod van, ill. a
változatot visszaállították vagy eltávolították az archívumból.',
'undelete-nodiff' => 'Nem található korábbi változat.',
'undeletebtn' => 'Helyreállítás',
'undeletelink' => 'megtekintés/helyreállítás',
'undeleteviewlink' => 'megtekintés',
'undeletereset' => 'Vissza',
'undeleteinvert' => 'Kijelölés megfordítása',
'undeletecomment' => 'Ok:',
'undeletedrevisions' => '{{PLURAL:$1|egy|$1}} változat helyreállítva',
'undeletedrevisions-files' => '{{PLURAL:$1|egy|$1}} változat és {{PLURAL:$2|egy|$2}} fájl visszaállítva',
'undeletedfiles' => '{{PLURAL:$1|egy|$1}} fájl visszaállítva',
'cannotundelete' => 'Lap visszaállítása sikertelen: $1',
'undeletedpage' => "'''$1 helyreállítva'''

Lásd a [[Special:Log/delete|törlési naplót]] a legutóbbi törlések és helyreállítások listájához.",
'undelete-header' => 'A legutoljára törölt lapokat lásd a [[Special:Log/delete|törlési naplóban]].',
'undelete-search-title' => 'Törölt lapok keresése',
'undelete-search-box' => 'Törölt lapok keresése',
'undelete-search-prefix' => 'A megadott szavakkal kezdődő oldalak megjelenítése:',
'undelete-search-submit' => 'Keresés',
'undelete-no-results' => 'Nem található a keresési feltételeknek megfelelő oldal a törlési naplóban.',
'undelete-filename-mismatch' => 'Nem állítható helyre a(z) $1 időbélyeggel ellátott változat: a fájlnév nem egyezik meg',
'undelete-bad-store-key' => 'Nem állítható helyre a(z) $1 időbélyeggel ellátott változat: a fájl már hiányzott törlés előtt.',
'undelete-cleanup-error' => 'Hiba történt a nem használt „$1” archivált fájl törlésekor.',
'undelete-missing-filearchive' => 'Nem állítható helyre a(z) $1 azonosítószámú fájlarchívum, mert nincs az adatbázisban. Lehet, hogy már korábban helyreállították.',
'undelete-error' => 'Hiba a lap helyreállítása során',
'undelete-error-short' => 'Hiba történt a fájl helyreállítása során: $1',
'undelete-error-long' => 'Hiba történt a fájl helyreállítása során:

$1',
'undelete-show-file-confirm' => 'Biztosan meg akarod nézni a(z) "<nowiki>$1</nowiki>" fájl $2, $3-kori törölt változatát?',
'undelete-show-file-submit' => 'Igen',

# Namespace form on various pages
'namespace' => 'Névtér:',
'invert' => 'Kijelölés megfordítása',
'tooltip-invert' => 'Pipáld ki a dobozt, ha el szeretnéd rejteni a kiválasztott névterekben történt változtatásokat (és a kapcsolódó névterekben, amennyiben úgy van beállítva)',
'namespace_association' => 'Kapcsolódó névtér',
'tooltip-namespace_association' => 'Pipáld ki ezt a dobozt, ha a kiválasztott névtérhez tartozó vita- vagy tárgynévteret is bele szeretnéd venni.',
'blanknamespace' => '(Fő)',

# Contributions
'contributions' => '{{GENDER:$1|Szerkesztő}} közreműködései',
'contributions-title' => '$1 közreműködései',
'mycontris' => 'Közreműködések',
'contribsub2' => '$1 ($2)',
'nocontribs' => 'Nem található a feltételeknek megfelelő változtatás.',
'uctop' => '(aktuális)',
'month' => 'E hónap végéig:',
'year' => 'Eddig az évig:',

'sp-contributions-newbies' => 'Csak a nemrég regisztrált szerkesztők közreműködéseinek mutatása',
'sp-contributions-newbies-sub' => 'Új szerkesztők lapjai',
'sp-contributions-newbies-title' => 'Új szerkesztők közreműködései',
'sp-contributions-blocklog' => 'Blokkolási napló',
'sp-contributions-deleted' => 'törölt szerkesztések',
'sp-contributions-uploads' => 'feltöltések',
'sp-contributions-logs' => 'naplók',
'sp-contributions-talk' => 'vitalap',
'sp-contributions-userrights' => 'szerkesztői jogok beállítása',
'sp-contributions-blocked-notice' => 'Ez a szerkesztő blokkolva van. A blokknapló legutóbbi ide vonatkozó bejegyzése a következő:',
'sp-contributions-blocked-notice-anon' => 'Ez az IP-cím blokkolva van.
A blokknapló legutóbbi ide vonatkozó bejegyzése a következő:',
'sp-contributions-search' => 'Közreműködések szűrése',
'sp-contributions-username' => 'IP-cím vagy felhasználónév:',
'sp-contributions-toponly' => 'Csak a jelenleg utolsónak számító változtatásokat mutassa',
'sp-contributions-submit' => 'Keresés',

# What links here
'whatlinkshere' => 'Mi hivatkozik erre',
'whatlinkshere-title' => 'A(z) „$1” lapra hivatkozó lapok',
'whatlinkshere-page' => 'Lap:',
'linkshere' => 'Az alábbi lapok hivatkoznak erre: [[:$1]]',
'nolinkshere' => '[[:$1]]: erre a lapra egyetlen más lap sem hivatkozik.',
'nolinkshere-ns' => "A kiválasztott névtérben egyetlen oldal sem hivatkozik a(z) '''[[:$1]]''' lapra.",
'isredirect' => 'átirányítás',
'istemplate' => 'beillesztve',
'isimage' => 'fájlhivatkozás',
'whatlinkshere-prev' => '{{PLURAL:$1|előző|előző $1}}',
'whatlinkshere-next' => '{{PLURAL:$1|következő|következő $1}}',
'whatlinkshere-links' => '← erre mutató hivatkozások',
'whatlinkshere-hideredirs' => 'átirányítások $1',
'whatlinkshere-hidetrans' => 'beillesztések $1',
'whatlinkshere-hidelinks' => 'linkek $1',
'whatlinkshere-hideimages' => 'fájlhivatkozások $1',
'whatlinkshere-filters' => 'Elemek szűrése',

# Block/unblock
'autoblockid' => '$1. autoblokk',
'block' => 'Felhasználó blokkolása',
'unblock' => 'Felhasználó blokkolásának feloldása',
'blockip' => 'Blokkolás',
'blockip-title' => 'Felhasználó blokkolása',
'blockip-legend' => 'Felhasználó blokkolása',
'blockiptext' => 'Az alábbi űrlap segítségével megvonhatod egy szerkesztő vagy IP-cím szerkesztési jogait.
Ügyelj rá, hogy az intézkedésed mindig legyen tekintettel a vonatkozó [[{{MediaWiki:Policy-url}}|irányelvekre]].
Add meg a blokkolás okát is (például idézd a blokkolandó személy által vandalizált lapokat).',
'ipadressorusername' => 'IP-cím vagy felhasználói név',
'ipbexpiry' => 'Lejárat:',
'ipbreason' => 'Ok:',
'ipbreasonotherlist' => 'Más ok',
'ipbreason-dropdown' => '*Gyakori blokkolási okok
** Téves információ beírása
** Lapok tartalmának eltávolítása
** Spammelgetés, reklámlinkek tömködése a lapokba
** Értelmetlen megjegyzések, halandzsa beillesztése a cikkekbe
** Megfélemlítő viselkedés, zaklatás
** Több szerkesztői fiókkal való visszaélés
** Elfogadhatatlan azonosító',
'ipb-hardblock' => 'Megakadályozza, hogy a bejelentkezett felhasználók erről az IP-címről szerkesszenek',
'ipbcreateaccount' => 'Új regisztráció megakadályozása',
'ipbemailban' => 'E-mailt se tudjon küldeni',
'ipbenableautoblock' => 'A szerkesztő által használt IP-címek automatikus blokkolása',
'ipbsubmit' => 'Blokkolás',
'ipbother' => 'Más időtartam:',
'ipboptions' => '2 óra:2 hours,1 nap:1 day,3 nap:3 days,1 hét:1 week,2 hét:2 weeks,1 hónap:1 month,3 hónap:3 months,6 hónap:6 months,1 év:1 year,végtelen:infinite',
'ipbotheroption' => 'Más időtartam',
'ipbotherreason' => 'Más/további ok:',
'ipbhidename' => 'A felhasználónév ne jelenjen meg a szerkesztéseknél és a listákban',
'ipbwatchuser' => 'A felhasználó lapjának és vitalapjának figyelése',
'ipb-disableusertalk' => 'Megakadályozza, hogy a felhasználó szerkeszthesse a saját vitalapját, miközben blokkolva van',
'ipb-change-block' => 'Blokk beállításainak megváltoztatása',
'ipb-confirm' => 'Blokk megerősítése',
'badipaddress' => 'Érvénytelen IP-cím',
'blockipsuccesssub' => 'Sikeres blokkolás',
'blockipsuccesstext' => '„[[Special:Contributions/$1|$1]]” felhasználót blokkoltad.
<br />Lásd a [[Special:BlockList|blokkolt IP-címek listáját]] az érvényben lévő blokkok áttekintéséhez.',
'ipb-blockingself' => 'Saját magad blokkolására készülsz! Biztos, hogy ezt szeretnéd tenni?',
'ipb-confirmhideuser' => 'Egy felhasználó blokkolására készülsz, úgy, hogy a „felhasználó elrejtése” funkció be van kapcsolva. Ez elrejti a felhasználó nevét az összes listában és naplóbejegyzésben. Biztosan ezt szeretnéd tenni?',
'ipb-edit-dropdown' => 'Blokkolási okok szerkesztése',
'ipb-unblock-addr' => '$1 blokkjának feloldása',
'ipb-unblock' => 'Felhasználónév vagy IP-cím blokkolásának feloldása',
'ipb-blocklist' => 'Létező blokkok megtekintése',
'ipb-blocklist-contribs' => '$1 közreműködései',
'unblockip' => 'Blokk feloldása',
'unblockiptext' => 'Itt tudod visszaadni egy blokkolt felhasználónévnek vagy IP-nek a szerkesztési jogosultságot.',
'ipusubmit' => 'Blokk eltávolítása',
'unblocked' => '[[User:$1|$1]] blokkolása feloldva',
'unblocked-range' => '$1 blokkja feloldva',
'unblocked-id' => '$1 blokkolása feloldásra került',
'blocklist' => 'Blokkolt felhasználók',
'ipblocklist' => 'Blokkolt felhasználók',
'ipblocklist-legend' => 'Blokkolt felhasználó keresése',
'blocklist-userblocks' => 'Fiókblokkolások elrejtése',
'blocklist-tempblocks' => 'Ideiglenes blokkolások elrejtése',
'blocklist-addressblocks' => 'IP-címek blokkolásainak elrejtése',
'blocklist-rangeblocks' => 'Tartományblokkok elrejtése',
'blocklist-timestamp' => 'Időbélyeg',
'blocklist-target' => 'Célpont',
'blocklist-expiry' => 'Lejárat',
'blocklist-by' => 'Blokkoló adminisztrátor',
'blocklist-params' => 'Blokkparaméterek',
'blocklist-reason' => 'Ok',
'ipblocklist-submit' => 'Keresés',
'ipblocklist-localblock' => 'Helyi blokk',
'ipblocklist-otherblocks' => 'További {{PLURAL:$1|blokk|blokkok}}',
'infiniteblock' => 'végtelen',
'expiringblock' => 'lejárat: $1 $2',
'anononlyblock' => 'csak anon.',
'noautoblockblock' => 'az automatikus blokkolás letiltott',
'createaccountblock' => 'új felhasználó létrehozása blokkolva',
'emailblock' => 'e-mail cím blokkolva',
'blocklist-nousertalk' => 'nem szerkesztheti a vitalapját',
'ipblocklist-empty' => 'A blokkoltak listája üres.',
'ipblocklist-no-results' => 'A kért IP-cím vagy felhasználónév nem blokkolt.',
'blocklink' => 'blokkolás',
'unblocklink' => 'blokk feloldása',
'change-blocklink' => 'blokkolás módosítása',
'contribslink' => 'szerkesztései',
'emaillink' => 'e-mail küldése',
'autoblocker' => "Az általad használt IP-cím autoblokkolva van, mivel korábban a kitiltott „[[User:$1|$1]]” használta. ($1 blokkolásának indoklása: „'''$2'''”) Ha nem te vagy $1, lépj kapcsolatba valamelyik adminisztrátorral, és kérd az autoblokk feloldását. Ne felejtsd el megírni neki, hogy kinek szóló blokkba ütköztél bele!",
'blocklogpage' => 'Blokkolási napló',
'blocklog-showlog' => 'Ez a felhasználó már blokkolva volt korábban. A blokkolási napló ide vonatkozó része alább látható:',
'blocklog-showsuppresslog' => 'Ez a felhasználó korábban blokkot kapott, és a naplóbejegyzés el lett rejtve. Az elrejtési napló alább látható tájékoztatásként:',
'blocklogentry' => '„[[$1]]” blokkolva $2 $3 időtartamra',
'reblock-logentry' => 'megváltoztatta [[$1]] blokkjának beállításait, a blokk lejárta: $2 $3',
'blocklogtext' => 'Ez a felhasználókra helyezett blokkoknak és azok feloldásának listája. Az automatikus blokkolt IP címek nem szerepelnek a listában. Lásd még [[Special:BlockList|a jelenleg életben lévő blokkok listáját]].',
'unblocklogentry' => '„$1” blokkolása feloldva',
'block-log-flags-anononly' => 'csak anonok',
'block-log-flags-nocreate' => 'nem hozhat létre új fiókot',
'block-log-flags-noautoblock' => 'autoblokk kikapcsolva',
'block-log-flags-noemail' => 'e-mail blokkolva',
'block-log-flags-nousertalk' => 'saját vitalapját sem szerkesztheti',
'block-log-flags-angry-autoblock' => 'bővített automatikus blokk bekapcsolva',
'block-log-flags-hiddenname' => 'rejtett felhasználónév',
'range_block_disabled' => 'A rendszerfelelős tartományblokkolás létrehozási képessége letiltott.',
'ipb_expiry_invalid' => 'Hibás lejárati dátum.',
'ipb_expiry_temp' => 'A láthatatlan felhasználóinév-blokkok lehetnek állandóak.',
'ipb_hide_invalid' => 'A felhasználói fiókot nem lehet elrejteni; lehet, hogy túl sok szerkesztése van.',
'ipb_already_blocked' => '"$1" már blokkolva',
'ipb-needreblock' => '$1 már blokkolva van. Meg szeretnéd változtatni a beállításokat?',
'ipb-otherblocks-header' => 'További {{PLURAL:$1|blokk|blokkok}}',
'unblock-hideuser' => 'Nem oldhatod fel a felhasználó blokkját, mivel a felhasználóneve el van rejtve.',
'ipb_cant_unblock' => 'Hiba: A(z) $1 blokkolási azonosító nem található. Lehet, hogy már feloldották a blokkolását.',
'ipb_blocked_as_range' => 'Hiba: a(z) $1 IP-cím nem blokkolható közvetlenül, és nem lehet feloldani. A(z) $2 tartomány részeként van blokkolva, amely feloldható.',
'ip_range_invalid' => 'Érvénytelen IP-tartomány.',
'ip_range_toolarge' => 'Nem engedélyezettek azok a tartományblokkok, melyek nagyobbak mint /$1.',
'proxyblocker' => 'Proxyblokkoló',
'proxyblockreason' => "Az IP-címeden ''nyílt proxy'' üzemel. Amennyiben nem használsz proxyt, vedd fel a kapcsolatot egy informatikussal vagy az internetszolgáltatóddal ezen súlyos biztonsági probléma ügyében.",
'sorbsreason' => 'Az IP-címed nyitott proxyként szerepel e webhely által használt DNSBL listán.',
'sorbs_create_account_reason' => 'Az IP-címed nyitott proxyként szerepel e webhely által használt DNSBL listán. Nem hozhatsz létre fiókot.',
'cant-block-while-blocked' => 'Nem blokkolhatsz más szerkesztőket, miközben te magad blokkolva vagy.',
'cant-see-hidden-user' => 'A felhasználó, akit blokkolni próbáltál már blokkolva és rejtve van. Mivel nincs felhasználó elrejtése jogosultságod, nem láthatod és nem szerkesztheted a felhasználó blokkját.',
'ipbblocked' => 'Nem blokkolhatsz és nem oldhatod fel más felhasználók blokkjait, mert te magad is blokkolva vagy',
'ipbnounblockself' => 'Nincs jogosultságod feloldani a saját felhasználói fiókod blokkját',

# Developer tools
'lockdb' => 'Adatbázis zárolása',
'unlockdb' => 'Adatbázis kinyitása',
'lockdbtext' => 'Az adatbázis zárolása felfüggeszti valamennyi szerkesztő
számára a lapok szerkesztésének, a beállításaik módosításának, és olyan más
dolgoknak a képességét, amihez az adatbázisban kell
változtatni. Kérjük, erősítsd meg, hogy ezt kívánod tenni, és a karbantartás
befejezése után kinyitod az adatbázist.',
'unlockdbtext' => 'Az adatbázis kinyitása visszaállítja valamennyi felhasználó
számára a lapok szerkesztésének, a beállításaik módosításának, és olyan más
dolgoknak a képességét, amihez az adatbázisban kell
változtatni. Kérjük, erősítsd meg, hogy ezt kívánod tenni.',
'lockconfirm' => 'Igen, valóban zárolni akarom az adatbázist.',
'unlockconfirm' => 'Igen, valóban ki akarom nyitni az adatbázist.',
'lockbtn' => 'Adatbázis zárolása',
'unlockbtn' => 'Adatbázis kinyitása',
'locknoconfirm' => 'Nem jelölted ki a megerősítő jelölőnégyzetet.',
'lockdbsuccesssub' => 'Az adatbázis zárolása sikerült',
'unlockdbsuccesssub' => 'Az adatbázis zárolásának eltávolítása kész',
'lockdbsuccesstext' => 'Az adatbázist zárolták.
<br />A karbantartás befejezése után ne feledd el [[Special:UnlockDB|kinyitni]].',
'unlockdbsuccesstext' => 'Az adatbázis kinyitása kész.',
'lockfilenotwritable' => 'Az adatbázist zároló fájl nem írható. Az adatbázis zárolásához vagy kinyitásához ennek a webkiszolgáló által írhatónak kell lennie.',
'databasenotlocked' => 'Az adatbázis nincs lezárva.',
'lockedbyandtime' => '($1 zárta le $2 $3-kor)',

# Move page
'move-page' => '$1 átnevezése',
'move-page-legend' => 'Lap átnevezése',
'movepagetext' => "Az alábbi űrlap használatával nevezhetsz át egy lapot, és helyezheted át teljes laptörténetét az új nevére.
A régi cím az új címre való átirányítás lesz.
Frissítheted a régi címre mutató átirányításokat, hogy azok automatikusan a megfelelő címre mutassanak;
ha nem teszed, ellenőrizd a [[Special:DoubleRedirects|dupla]] vagy [[Special:BrokenRedirects|hibás átirányításokat]].
Neked kell biztosítanod, hogy a linkek továbbra is oda mutassanak, ahová mutatniuk kell.

A lap '''nem''' nevezhető át, ha már van egy ugyanilyen című lap, hacsak nem üres vagy átirányítás, és nincs laptörténete.
Ez azt jelenti, hogy vissza tudsz nevezni egy tévedésből átnevezett lapot, és nem tudsz létező lapot véletlenül felülírni.

'''FIGYELEM!'''
Népszerű oldalak esetén ez drasztikus és nem várt változtatás lehet;
győződj meg a folytatás előtt arról, hogy tisztában vagy a következményekkel.",
'movepagetext-noredirectfixer' => "Az alábbi űrlap használatával nevezhetsz át egy lapot, és helyezheted át teljes laptörténetét az új nevére.
A régi cím az új címre való átirányítás lesz.
Ellenőrizd a [[Special:DoubleRedirects|dupla]] és a [[Special:BrokenRedirects|hibás átirányításoknál]], hogy a linkek továbbra is oda mutatnak, ahová mutatniuk kell.

A lap '''nem''' nevezhető át, ha már van egy ugyanilyen című lap, hacsak nem üres, vagy átirányítás, aminek nincs laptörténete.
Ez azt jelenti, hogy vissza tudsz nevezni egy tévedésből átnevezett lapot, de nem tudsz egy már létező lapot véletlenül felülírni.

'''Figyelem!'''
Népszerű oldalak esetén ez drasztikus és nem várt változtatás lehet;
győződj meg a folytatás előtt arról, hogy tisztában vagy-e a következményekkel.",
'movepagetalktext' => "A laphoz tartozó vitalap automatikusan átneveződik, '''kivéve, ha:'''
*már létezik egy nem üres vitalap az új helyen,
*nem jelölöd be a lenti pipát.

Ezen esetekben a vitalapot külön, kézzel kell átnevezned a kívánságaid szerint.",
'movearticle' => 'Lap átnevezése',
'moveuserpage-warning' => "'''Figyelem:''' Egy felhasználólapot készülsz átmozgatni. Csak a lap lesz átmozgatva, a szerkesztő ''nem'' lesz átnevezve.",
'movenologin' => 'Nem jelentkeztél be',
'movenologintext' => 'Ahhoz, hogy átnevezhess egy lapot, [[Special:UserLogin|be kell lépned]].',
'movenotallowed' => 'Nincs jogod a lapok átnevezéséhez.',
'movenotallowedfile' => 'Nincs megfelelő jogosultságod a fájlok átnevezéséhez.',
'cant-move-user-page' => 'Nem nevezhetsz át szerkesztői lapokat (az allapokon kívül).',
'cant-move-to-user-page' => 'Nincs jogosultságod átnevezni egy lapot szerkesztői lapnak (kivéve annak allapjának).',
'newtitle' => 'Az új cím:',
'move-watch' => 'Figyeld a lapot',
'movepagebtn' => 'Lap átnevezése',
'pagemovedsub' => 'Átnevezés sikeres',
'movepage-moved' => "'''„$1” átnevezve „$2” névre'''",
'movepage-moved-redirect' => 'Átirányítás létrehozva.',
'movepage-moved-noredirect' => 'A régi címről nem készült átirányítás.',
'articleexists' => 'Ilyen névvel már létezik lap, vagy az általad választott név érvénytelen.
Kérlek, válassz egy másik nevet.',
'cantmove-titleprotected' => 'Nem nevezheted át a lapot, mert az új cím le van védve a létrehozás ellen.',
'talkexists' => 'A lap átnevezése sikerült, de a hozzá tartozó vitalapot nem tudtam átnevezni, mert már létezik egy egyező nevű lap az új helyen. Kérjük, gondoskodj a két lap összefűzéséről.',
'movedto' => 'átnevezve',
'movetalk' => 'Nevezd át a vitalapot is, ha lehetséges',
'move-subpages' => 'Allapok átnevezése (maximum $1)',
'move-talk-subpages' => 'A vitalap allapjainak átnevezése (maximum $1)',
'movepage-page-exists' => 'A(z) „$1” nevű lap már létezik, és nem írható felül automatikusan.',
'movepage-page-moved' => 'A(z) „$1” nevű lap át lett nevezve „$2” névre.',
'movepage-page-unmoved' => 'A(z) „$1” nevű lap nem nevezhető át „$2” névre.',
'movepage-max-pages' => '{{PLURAL:$1|Egy|$1}} lapnál több nem nevezhető át automatikusan, így a további lapok a helyükön maradnak.',
'movelogpage' => 'Átnevezési napló',
'movelogpagetext' => 'Az alábbiakban az átnevezett lapok listája látható.',
'movesubpage' => '{{PLURAL:$1|Allap|Allapok}}',
'movesubpagetext' => 'Ennek a lapnak {{PLURAL:$1|egy|$1}} allapja van.',
'movenosubpage' => 'Ez a lap nem rendelkezik allapokkal.',
'movereason' => 'Indoklás:',
'revertmove' => 'visszaállítás',
'delete_and_move' => 'Törlés és átnevezés',
'delete_and_move_text' => '== Törlés szükséges ==

Az átnevezés céljaként megadott „[[:$1]]” szócikk már létezik.  Ha az átnevezést végre akarod hajtani, ezt a lapot törölni kell.  Valóban ezt szeretnéd?',
'delete_and_move_confirm' => 'Igen, töröld a lapot',
'delete_and_move_reason' => 'átnevezendő lap célneve felszabadítva „[[$1]]” számára',
'selfmove' => 'A cikk jelenlegi címe megegyezik azzal, amire át szeretnéd mozgatni. Egy szócikket saját magára mozgatni nem lehet.',
'immobile-source-namespace' => 'A(z) „$1” névtér lapjai nem nevezhetőek át',
'immobile-target-namespace' => 'A(z) „$1” névtérbe nem mozgathatsz át lapokat',
'immobile-target-namespace-iw' => 'Wikiközi hivatkozás nem lehet a lap új neve.',
'immobile-source-page' => 'Ez a lap nem nevezhető át.',
'immobile-target-page' => 'A lap nem helyezhető át a megadott címre.',
'bad-target-model' => 'A kívánt célhely eltérő tartalom modellt használ. Nem lehet $1 modellről $2 modellre konvertálni.',
'imagenocrossnamespace' => 'A fájlok nem helyezhetőek át más névtérbe',
'nonfile-cannot-move-to-file' => 'Nem fájlok nem nevezhetők át fájlnévtérbe',
'imagetypemismatch' => 'Az új kiterjesztés nem egyezik meg a fájl típusával',
'imageinvalidfilename' => 'A célnév érvénytelen',
'fix-double-redirects' => 'Az eredeti címre mutató hivatkozások frissítése',
'move-leave-redirect' => 'Átirányítás készítése a régi címről az új címre',
'protectedpagemovewarning' => "'''Figyelem:''' Ez a lap le van védve, így csak adminisztrátori jogosultságokkal rendelkező szerkesztők nevezhetik át.
A legutolsó ide vonatkozó naplóbejegyzés alább látható:",
'semiprotectedpagemovewarning' => "'''Figyelem:''' Ez a lap le van védve, így csak regisztrált felhasználók nevezhetik át.
A legutolsó ide vonatkozó naplóbejegyzés alább látható:",
'move-over-sharedrepo' => '== Létező fájlnév ==

A(z) [[:$1]] néven már létezik fájl egy megosztott tárhelyen. Ha ilyen néven töltöd fel, el fogja takarni a közös tárhelyen levőt.',
'file-exists-sharedrepo' => 'A választott fájlnév már használatban van egy közös tárhelyen.
Kérlek válassz másik nevet.',

# Export
'export' => 'Lapok exportálása',
'exporttext' => 'Egy adott lap vagy lapcsoport szövegét és laptörténetét exportálhatod XML-be. A kapott
fájlt importálhatod egy másik MediaWiki alapú rendszerbe
a Special:Import lapon keresztül.

Lapok exportálásához add meg a címüket a lenti szövegdobozban (minden címet külön sorba), és válaszd ki,
hogy az összes korábbi változatra és a teljes laptörténetekre szükséged van-e, vagy csak az aktuális
változatok és a legutolsó változtatásokra vonatkozó információk kellenek.

Az utóbbi esetben közvetlen hivatkozást is használhatsz, például a [[{{#Special:Export}}/{{MediaWiki:Mainpage}}]] a "[[{{MediaWiki:Mainpage}}]]" nevű lapot exportálja.',
'exportall' => 'Összes lap exportálása',
'exportcuronly' => 'Csak a legfrissebb állapot, teljes laptörténet nélkül',
'exportnohistory' => "----
'''Megjegyzés:''' A lapok teljes előzményeinek ezen az űrlapon keresztül történő exportálása teljesítményporlbémák miatt letiltott.",
'exportlistauthors' => 'Minden lap valamennyi szerkesztőjének hozzávétele',
'export-submit' => 'Exportálás',
'export-addcattext' => 'Lapok hozzáadása kategóriából:',
'export-addcat' => 'Hozzáadás',
'export-addnstext' => 'Lapok hozzáadása ebből a névtérből:',
'export-addns' => 'Hozzáadás',
'export-download' => 'A fájlban történő mentés felkínálása',
'export-templates' => 'Sablonok hozzáadása',
'export-pagelinks' => 'Hivatkozott lapok hozzáadása, eddig a szintig:',

# Namespace 8 related
'allmessages' => 'Rendszerüzenetek',
'allmessagesname' => 'Név',
'allmessagesdefault' => 'Alapértelmezett szöveg',
'allmessagescurrent' => 'Jelenlegi szöveg',
'allmessagestext' => 'Ezen a lapon a MediaWiki-névtérben elérhető rendszerüzenetek listája látható.
Ha részt szeretnél venni a MediaWiki fordításában, látogass el a [//www.mediawiki.org/wiki/Localisation MediaWiki Localisation], valamint a [//translatewiki.net translatewiki.net] oldalra.',
'allmessagesnotsupportedDB' => "A '''''{{ns:special}}:Allmessages''''' lap nem használható, mert a '''\$wgUseDatabaseMessages''' ki van kapcsolva.",
'allmessages-filter-legend' => 'Elemek szűrése',
'allmessages-filter' => 'Módosítás állapota:',
'allmessages-filter-unmodified' => 'nem módosított',
'allmessages-filter-all' => 'összes',
'allmessages-filter-modified' => 'módosított',
'allmessages-prefix' => 'Előtag szerint:',
'allmessages-language' => 'Nyelv:',
'allmessages-filter-submit' => 'Szűrés',

# Thumbnails
'thumbnail-more' => 'A kép nagyítása',
'filemissing' => 'A fájl nincs meg',
'thumbnail_error' => 'Hiba a bélyegkép létrehozásakor: $1',
'djvu_page_error' => 'A DjVu lap a tartományon kívülre esik',
'djvu_no_xml' => 'Nem olvasható ki a DjVu fájl XML-je',
'thumbnail-temp-create' => 'Nem lehet ideiglenes bélyegkép fájlt létrehozni',
'thumbnail-dest-create' => 'Nem lehet a bélyegképet a célhelyre menteni',
'thumbnail_invalid_params' => 'Érvénytelen bélyegkép paraméterek',
'thumbnail_dest_directory' => 'Nem hozható létre a célkönyvtár',
'thumbnail_image-type' => 'A képformátum nem támogatott',
'thumbnail_gd-library' => 'A GD-könyvtár nincs megfelelően beállítva: a(z) $1 függvény hiányzik',
'thumbnail_image-missing' => 'Úgy tűnik, hogy a fájl hiányzik: $1',

# Special:Import
'import' => 'Lapok importálása',
'importinterwiki' => 'Transwiki importálása',
'import-interwiki-text' => 'Válaszd ki az importálandó wikit és lapcímet.
A változatok dátumai és a szerkesztők nevei megőrzésre kerülnek.
Valamennyi transwiki importálási művelet az [[Special:Log/import|importálási naplóban]] kerül naplózásra.',
'import-interwiki-source' => 'Forrás wiki/lap:',
'import-interwiki-history' => 'A lap összes előzményváltozatainak másolása',
'import-interwiki-templates' => 'Az összes sablon hozzáadása',
'import-interwiki-submit' => 'Importálás',
'import-interwiki-namespace' => 'Célnévtér:',
'import-interwiki-rootpage' => 'Cél gyökér lap (opcionális):',
'import-upload-filename' => 'Fájlnév:',
'import-comment' => 'Megjegyzés:',
'importtext' => 'Exportáld a fájlt a forráswikiből az [[Special:Export|exportáló eszköz]] segítségével.
Mentsd el a számítógépedre, majd töltsd fel ide.',
'importstart' => 'Lapok importálása...',
'import-revision-count' => '$1 {{PLURAL:$1|revision|változatok}}',
'importnopages' => 'Nincs importálandó lap.',
'imported-log-entries' => 'Importálva $1 logbejegyzés.',
'importfailed' => 'Az importálás nem sikerült: $1',
'importunknownsource' => 'Ismeretlen import forrástípus',
'importcantopen' => 'Nem nyitható meg az importfájl',
'importbadinterwiki' => 'Rossz wikiközi hivatkozás',
'importnotext' => 'Üres, vagy nincs szöveg',
'importsuccess' => 'Az importálás befejeződött!',
'importhistoryconflict' => 'Ütköző előzményváltozat létezik (lehet, hogy már importálták ezt a lapot)',
'importnosources' => 'Nincsenek transzwikiimport-források definiálva, a közvetlen laptörténet-felküldés pedig nem megengedett.',
'importnofile' => 'Nem került importfájl feltöltésre.',
'importuploaderrorsize' => 'Az importálandó fájl feltöltése nem sikerült, mert nagyobb, mint a megengedett feltöltési méret.',
'importuploaderrorpartial' => 'Az importálandó fájl feltöltése nem sikerült. A fájl csak részben lett feltöltve.',
'importuploaderrortemp' => 'Az importálandó fájl feltöltése nem sikerült. Nem létezik ideiglenes mappa.',
'import-parse-failure' => 'XML elemzési hiba importáláskor',
'import-noarticle' => 'Nincs importálandó lap!',
'import-nonewrevisions' => 'A korábban importált összes változat.',
'xml-error-string' => '$1 a(z) $2. sorban, $3. oszlopban ($4. bájt): $5',
'import-upload' => 'XML-adatok feltöltése',
'import-token-mismatch' => 'Elveszett a session adat, próbálkozz újra.',
'import-invalid-interwiki' => 'A kijelölt wikiből nem lehet importálni.',
'import-error-edit' => '„$1” lap nem került importálásra, mert nem szerkesztheted azt.',
'import-error-create' => '„$1” lap nem került importálásra, mert nem hozhatod létre azt.',
'import-error-interwiki' => '„$1” lap nem került importálásra, mert a név külső hivatkozásokra van fenntartva (interwiki).',
'import-error-special' => '„$1” lap nem került importálásra, mert olyan speciális névtérbe tartozik, amelyen nem engedélyezettek a lapok.',
'import-error-invalid' => '„$1” lap nem került importálásra, mert a neve nem érvényes.',
'import-options-wrong' => 'Rossz {{PLURAL:$2|opció|opciók}}: <nowiki>$1</nowiki>',
'import-rootpage-invalid' => 'A megadott gyökér oldal címe érvénytelen.',
'import-rootpage-nosubpage' => 'A(z) "$1" névtér nem engedi meg aloldalak használatát.',

# Import log
'importlogpage' => 'Importnapló',
'importlogpagetext' => 'Lapok szerkesztési előzményekkel történő adminisztratív imporálása más wikikből.',
'import-logentry-upload' => '[[$1]] importálása fájlfeltöltéssel kész',
'import-logentry-upload-detail' => '{{PLURAL:$1|egy|$1}} változat',
'import-logentry-interwiki' => '$1 más wikiből áthozva',
'import-logentry-interwiki-detail' => '{{PLURAL:$1|egy|$1}} változat innen: $2',

# JavaScriptTest
'javascripttest' => 'JavaScript tesztelés',
'javascripttest-title' => '$1 tesztek futtatása',
'javascripttest-pagetext-noframework' => 'Ez az oldal JavaStript tesztek futtatására van fenntartva.',
'javascripttest-pagetext-unknownframework' => 'Ismeretlen teszt keretrendszer: $1.',
'javascripttest-pagetext-frameworks' => 'Kérlek válaszd valamelyik teszt keretrendszert az alábbiak közül: $1',
'javascripttest-pagetext-skins' => 'Válassz egy megjelenítő felületet, amin a tesztet futtatod:',
'javascripttest-qunit-intro' => 'Lásd a [$1 tesztelési dokumentációt]  a mediawiki.org helyen.',
'javascripttest-qunit-heading' => 'MediaWiki JavaScript QUnit tesztcsomag',

# Tooltip help for the actions
'tooltip-pt-userpage' => 'A szerkesztőlapod',
'tooltip-pt-anonuserpage' => 'Az általad használt IP-címhez tartozó felhasználói lap',
'tooltip-pt-mytalk' => 'A vitalapod',
'tooltip-pt-anontalk' => 'Az általad használt IP-címről végrehajtott szerkesztések megvitatása',
'tooltip-pt-preferences' => 'A beállításaid',
'tooltip-pt-watchlist' => 'Az általad figyelemmel kísért oldalak utolsó változtatásai',
'tooltip-pt-mycontris' => 'A közreműködéseid listája',
'tooltip-pt-login' => 'Bejelentkezni javasolt, de nem kötelező.',
'tooltip-pt-anonlogin' => 'Bejelentkezni javasolt, de nem kötelező.',
'tooltip-pt-logout' => 'Kijelentkezés',
'tooltip-ca-talk' => 'Az oldal tartalmának megvitatása',
'tooltip-ca-edit' => 'Te is szerkesztheted ezt az oldalt. Mentés előtt használd az előnézet gombot.',
'tooltip-ca-addsection' => 'Új szakasz nyitása',
'tooltip-ca-viewsource' => 'Ez egy védett lap. Ide kattintva megnézheted a forrását.',
'tooltip-ca-history' => 'A lap korábbi változatai',
'tooltip-ca-protect' => 'A lap levédése',
'tooltip-ca-unprotect' => 'Lapvédelem módosítása',
'tooltip-ca-delete' => 'A lap törlése',
'tooltip-ca-undelete' => 'A törölt lapváltozatok visszaállítása',
'tooltip-ca-move' => 'A lap áthelyezése',
'tooltip-ca-watch' => 'A lap hozzáadása a figyelőlistádhoz',
'tooltip-ca-unwatch' => 'A lap eltávolítása a figyelőlistádról',
'tooltip-search' => 'Keresés a wikin',
'tooltip-search-go' => 'Ugrás a megadott lapra, ha létezik',
'tooltip-search-fulltext' => 'Oldalak keresése a megadott szöveg alapján',
'tooltip-p-logo' => 'Kezdőlap',
'tooltip-n-mainpage' => 'A kezdőlap felkeresése',
'tooltip-n-mainpage-description' => 'A kezdőlap megtekintése',
'tooltip-n-portal' => 'A közösségről, miben segíthetsz, mit hol találsz meg',
'tooltip-n-currentevents' => 'Háttérinformáció az aktuális eseményekről',
'tooltip-n-recentchanges' => 'A wikiben történt legutóbbi változtatások listája',
'tooltip-n-randompage' => 'Egy véletlenszerűen kiválasztott lap betöltése',
'tooltip-n-help' => 'Ha bármi problémád van...',
'tooltip-t-whatlinkshere' => 'Az erre a lapra hivatkozó más lapok listája',
'tooltip-t-recentchangeslinked' => 'Az erről a lapról hivatkozott lapok utolsó változtatásai',
'tooltip-feed-rss' => 'A lap tartalma RSS hírcsatorna formájában',
'tooltip-feed-atom' => 'A lap tartalma Atom hírcsatorna formájában',
'tooltip-t-contributions' => 'A felhasználó közreműködéseinek listája',
'tooltip-t-emailuser' => 'Írj levelet ennek a felhasználónak!',
'tooltip-t-upload' => 'Képek vagy egyéb fájlok feltöltése',
'tooltip-t-specialpages' => 'Az összes speciális lap listája',
'tooltip-t-print' => 'A lap nyomtatható változata',
'tooltip-t-permalink' => 'Állandó hivatkozás a lap ezen változatához',
'tooltip-ca-nstab-main' => 'A lap megtekintése',
'tooltip-ca-nstab-user' => 'A felhasználói lap megtekintése',
'tooltip-ca-nstab-media' => 'A fájlleíró lap megtekintése',
'tooltip-ca-nstab-special' => 'Ez egy speciális lap, nem szerkesztheted.',
'tooltip-ca-nstab-project' => 'A projektlap megtekintése',
'tooltip-ca-nstab-image' => 'A képleíró lap megtekintése',
'tooltip-ca-nstab-mediawiki' => 'A rendszerüzenet megtekintése',
'tooltip-ca-nstab-template' => 'A sablon megtekintése',
'tooltip-ca-nstab-help' => 'A súgólap megtekintése',
'tooltip-ca-nstab-category' => 'A kategória megtekintése',
'tooltip-minoredit' => 'A szerkesztés megjelölése apróként',
'tooltip-save' => 'A változtatásaid elmentése',
'tooltip-preview' => 'Mielőtt elmentenéd a lapot, ellenőrizd, biztosan úgy néz-e ki, ahogy szeretnéd!',
'tooltip-diff' => 'Nézd meg, milyen változtatásokat végeztél eddig a szövegen',
'tooltip-compareselectedversions' => 'A két kiválasztott változat közötti eltérések megjelenítése',
'tooltip-watch' => 'Lap hozzáadása a figyelőlistádhoz',
'tooltip-watchlistedit-normal-submit' => 'A kijelölt címek törlése',
'tooltip-watchlistedit-raw-submit' => 'Figyelőlista frissítése',
'tooltip-recreate' => 'A lap újra létrehozása a törlés ellenére',
'tooltip-upload' => 'Feltöltés indítása',
'tooltip-rollback' => '„Visszaállítás”: egy kattintással visszavonja az utolsó felhasználó egy vagy több szerkesztését.',
'tooltip-undo' => '„Visszavonás”: visszavonja ezt a szerkesztést, valamint megnyitja a szerkesztőt előnézet módban. A szerkesztési összefoglalóban meg lehet adni a visszavonás okát.',
'tooltip-preferences-save' => 'Beállítások mentése',
'tooltip-summary' => 'Adj meg egy rövid összefoglalót',

# Stylesheets
'common.css' => '/* Közös CSS az összes felületnek */',
'cologneblue.css' => '/* Az ide elhelyezett CSS hatással lesz a Kölni kék felület használóira */',
'monobook.css' => '/* Az ide elhelyezett CSS hatással lesz a Monobook felület használóira */',
'modern.css' => '/* Az ide elhelyezett CSS hatással lesz a Modern felület használóira */',
'vector.css' => '/* Az ide elhelyezett CSS hatással lesz a Vector felület használóira */',
'print.css' => '/* Az ide elhelyezett CSS hatással lesz a nyomtatás kimenetelére */',
'noscript.css' => '/* Az ide elhelyezett CSS azon felhasználókra lesz hatással, ahol a JavaScript le van tiltva */',
'group-autoconfirmed.css' => '/* Az ide elhelyezett CSS az automatikusan megerősített felhasználókra lesz hatással */',
'group-bot.css' => '/* Az ide elhelyezett CSS csak botokra lesz hatással */',
'group-sysop.css' => '/* Az ide elhelyezett CSS csak adminisztrátorokra lesz hatással */',
'group-bureaucrat.css' => '/* Az ide elhelyezett CSS csak bürokratákra lesz hatással */',

# Scripts
'common.js' => '/* Az ide elhelyezett JavaScript kód minden felhasználó számára lefut az oldalak betöltésekor. */',
'cologneblue.js' => '/* A Kölni kék felületet használó szerkesztők számára betöltendő JavaScriptek */',
'monobook.js' => '/* A Monobook felületet használó szerkesztők számára betöltendő JavaScriptek */',
'modern.js' => '/* A Modern felületet használó szerkesztők számára betöltendő JavaScriptek */',
'vector.js' => '/* A Vector felületet használó szerkesztők számára betöltendő JavaScriptek */',
'group-autoconfirmed.js' => '/* Az ide elhelyezett JavaScript csak automatikusan megerősített felhasználóknak töltődik be */',
'group-bot.js' => '/* Az ide elhelyezett JavaScript csak botoknak töltődik be */',
'group-sysop.js' => '/* Az ide elhelyezett JavaScript csak adminisztrátoroknak töltődik be */',
'group-bureaucrat.js' => '/* Az ide elhelyezett JavaScript csak bürokratáknak töltődik be */',

# Metadata
'notacceptable' => 'A wiki kiszolgálója nem tudja olyan formátumban biztosítani az adatokat, amit a kliens olvasni tud.',

# Attribution
'anonymous' => 'Névtelen {{SITENAME}}-{{PLURAL:$1|szerkesztő|szerkesztők}}',
'siteuser' => '$1 {{SITENAME}}-felhasználó',
'anonuser' => '$1 névtelen {{SITENAME}}-felhasználó',
'lastmodifiedatby' => 'Ezt a lapot utoljára $3 módosította $2, $1 időpontban.',
'othercontribs' => '$1 munkája alapján.',
'others' => 'mások',
'siteusers' => '$1 {{SITENAME}}-{{PLURAL:$2|szerkesztő|szerkesztők}}',
'anonusers' => '$1 névtelen {{PLURAL:$2|felhasználó|felhasználók}}',
'creditspage' => 'A lap közreműködői',
'nocredits' => 'Ennek a lapnak nincs közreműködői információja.',

# Spam protection
'spamprotectiontitle' => 'Spamszűrő',
'spamprotectiontext' => 'Az általad elmenteni kívánt lap egyik külső hivatkozása fennakadt a spamszűrőn.
Ez valószínűleg egy olyan link miatt van, ami egy feketelistán lévő oldalra hivatkozik.',
'spamprotectionmatch' => 'A spamszűrőn az alábbi szöveg fennakadt: $1',
'spambot_username' => 'MediaWiki spam kitakarítása',
'spam_reverting' => 'Visszatérés a $1 lapra mutató hivatkozásokat nem tartalmazó utolsó változathoz',
'spam_blanking' => 'Az összes változat tartalmazott a $1 lapra mutató hivatkozásokat, kiürítés',
'spam_deleting' => 'Minden változat tartalmazott $1-re mutató hivatkozást, törlöm',

# Info page
'pageinfo-title' => 'Információk a(z) „$1” lapról',
'pageinfo-not-current' => 'Sajnáljuk, de lehetetlen információt nyújtani a régi verziókhoz.',
'pageinfo-header-basic' => 'Alapinformációk',
'pageinfo-header-edits' => 'Szerkesztések története',
'pageinfo-header-restrictions' => 'Lapvédelem',
'pageinfo-header-properties' => 'Lap tulajdonságok',
'pageinfo-display-title' => 'Megjelenített cím',
'pageinfo-default-sort' => 'Alapértelmezett rendezési kulcs',
'pageinfo-length' => 'Lap hossza (bájtokban)',
'pageinfo-article-id' => 'Lapazonosító',
'pageinfo-language' => 'Laptartalom nyelve',
'pageinfo-robot-policy' => 'Kereső motor státusz',
'pageinfo-robot-index' => 'Indexelhető',
'pageinfo-robot-noindex' => 'Nem indexelhető',
'pageinfo-views' => 'Megtekintések száma',
'pageinfo-watchers' => 'Figyelők száma',
'pageinfo-few-watchers' => 'Kevesebb mint $1 szerkesztő figyeli',
'pageinfo-redirects-name' => 'Átirányítások erre a lapra',
'pageinfo-subpages-name' => 'A lap allapjai',
'pageinfo-subpages-value' => '$1 ($2 {{PLURAL:$2|átirányítás}}; $3 {{PLURAL:$3|nem átirányítás}})',
'pageinfo-firstuser' => 'A lap létrehozója',
'pageinfo-firsttime' => 'A lap létrehozásának ideje',
'pageinfo-lastuser' => 'Utolsó szerkesztő',
'pageinfo-lasttime' => 'Az utolsó szerkesztés ideje',
'pageinfo-edits' => 'Szerkesztések teljes száma',
'pageinfo-authors' => 'Egyedi szerkesztők teljes száma',
'pageinfo-recent-edits' => 'Friss változtatások száma (elmúlt $1 alatt)',
'pageinfo-recent-authors' => 'Friss változtatások szerkesztőinek száma',
'pageinfo-magic-words' => 'Varázs{{PLURAL:$1|szó|szavak}} ($1)',
'pageinfo-hidden-categories' => 'Rejtett {{PLURAL:$1|kategória|kategóriák}} ($1)',
'pageinfo-templates' => 'Felhasznált {{PLURAL:$1|sablon|sablonok}} ($1)',
'pageinfo-toolboxlink' => 'Lapinformációk',
'pageinfo-redirectsto' => 'Átirányítás ide',
'pageinfo-redirectsto-info' => 'infó',
'pageinfo-contentpage' => 'Tartalmi lapnak számít',
'pageinfo-contentpage-yes' => 'Igen',
'pageinfo-protect-cascading-yes' => 'Igen',
'pageinfo-category-info' => 'Kategória információk',
'pageinfo-category-pages' => 'Lapok száma',
'pageinfo-category-subcats' => 'Alkategóriák száma',
'pageinfo-category-files' => 'Fájlok száma',

# Skin names
'skinname-cologneblue' => 'Kölni kék',
'skinname-monobook' => 'MonoBook',
'skinname-modern' => 'Modern',

# Patrolling
'markaspatrolleddiff' => 'Ellenőrzöttnek jelölöd',
'markaspatrolledtext' => 'Ellenőriztem',
'markedaspatrolled' => 'Ellenőrzöttnek jelölve',
'markedaspatrolledtext' => 'A(z) [[:$1]] lap kiválasztott változatát ellenőrzöttnek jelölted.',
'rcpatroldisabled' => 'A friss változtatások járőrözése kikapcsolva',
'rcpatroldisabledtext' => 'A friss változtatások ellenőrzése jelenleg nincs engedélyezve.',
'markedaspatrollederror' => 'Nem lehet ellenőrzöttnek jelölni',
'markedaspatrollederrortext' => 'Meg kell adnod egy ellenőrzöttként megjelölt változatot.',
'markedaspatrollederror-noautopatrol' => 'A saját változtatásaid megjelölése ellenőrzöttként nem engedélyezett.',
'markedaspatrollednotify' => '$1 változtatása ellenőrzöttnek lett jelölve.',
'markedaspatrollederrornotify' => 'Nem sikerült ellenőrzöttnek jelölni.',

# Patrol log
'patrol-log-page' => 'Ellenőrzési napló (patrol)',
'patrol-log-header' => 'Ez az ellenőrzött változatok naplója.',
'log-show-hide-patrol' => 'járőrnapló $1',

# Image deletion
'deletedrevision' => 'Régebbi változat törölve: $1',
'filedeleteerror-short' => 'Hiba a fájl törlésekor: $1',
'filedeleteerror-long' => 'Hibák merültek föl a következő fájl törlésekor:

$1',
'filedelete-missing' => 'A(z) "$1" fájl nem törölhető, mert nem létezik.',
'filedelete-old-unregistered' => 'A megadott "$1" fájlváltzozat nincs az adatbázisban.',
'filedelete-current-unregistered' => 'A megadott "$1" fájl nincs az adatbázisban.',
'filedelete-archive-read-only' => 'A(z) "$1" archív könyvtár a webkiszolgáló által nem írható.',

# Browsing diffs
'previousdiff' => '← Régebbi szerkesztés',
'nextdiff' => 'Újabb szerkesztés →',

# Media information
'mediawarning' => "'''Figyelmeztetés''': Ez a fájltípus kártékony kódot tartalmazhat.
A futtatása során kárt tehet a számítógépedben.",
'imagemaxsize' => "A képek mérete, legfeljebb:<br />''(a leírólapokon)''",
'thumbsize' => 'Bélyegkép mérete:',
'widthheightpage' => '$1 × $2, {{PLURAL:$3|egy|$3}} oldal',
'file-info' => 'fájlméret: $1, MIME-típus: $2',
'file-info-size' => '$1 × $2 képpont, fájlméret: $3, MIME-típus: $4',
'file-info-size-pages' => '$1 × $2 képpont, fájlméret: $3, MIME típus: $4, $5 oldal',
'file-nohires' => 'Nem érhető el nagyobb felbontású változat.',
'svg-long-desc' => 'SVG fájl, névlegesen $1 × $2 képpont, fájlméret: $3',
'svg-long-desc-animated' => 'Animált SVG fájl, névlegesen $1 × $2 képpont, fájlméret: $3',
'svg-long-error' => 'Érvénytelen SVG-fájl: $1',
'show-big-image' => 'A kép nagyfelbontású változata',
'show-big-image-preview' => 'Az előnézet mérete: $1',
'show-big-image-other' => 'További {{PLURAL:$2|felbontás|felbontások}}: $1.',
'show-big-image-size' => '$1 × $2 képpont',
'file-info-gif-looped' => 'ismétlődik',
'file-info-gif-frames' => '{{PLURAL:$1|egy|$1}} képkocka',
'file-info-png-looped' => 'ismétlődik',
'file-info-png-repeat' => 'lejátszva {{PLURAL:$1|egy|$1}} alkalommal',
'file-info-png-frames' => '{{PLURAL:$1|egy|$1}} képkocka',
'file-no-thumb-animation' => "'''Megjegyzés: technikai korlátok miatt a fájl bélyegképe nem lesz animált.'''",
'file-no-thumb-animation-gif' => "'''Megjegyzés: technikai korlátok miatt a nagy felbontású GIF képekből készített bélyegkép nem lesz animált.'''",

# Special:NewFiles
'newimages' => 'Új fájlok galériája',
'imagelisttext' => "Lentebb '''{{PLURAL:$1|egy|$1}}''' kép látható, $2 rendezve.",
'newimages-summary' => 'Ezen a speciális lapon láthatóak a legutóbb feltöltött fájlok.',
'newimages-legend' => 'Fájlnév',
'newimages-label' => 'Fájlnév (vagy annak részlete):',
'showhidebots' => '(botok szerkesztéseinek $1)',
'noimages' => 'Nem tekinthető meg semmi.',
'ilsubmit' => 'Keresés',
'bydate' => 'dátum szerint',
'sp-newimages-showfrom' => 'Új fájlok mutatása $1 $2 után',

# Video information, used by Language::formatTimePeriod() to format lengths in the above messages
'seconds' => '{{PLURAL:$1|egy|$1}} másodperccel',
'minutes' => '{{PLURAL:$1|egy|$1}} perccel',
'hours' => '{{PLURAL:$1|egy|$1}} órával',
'days' => '{{PLURAL:$1|egy|$1}} nappal',
'months' => '{{PLURAL:$1|$1 hónap|$1 hónap}}',
'years' => '{{PLURAL:$1|$1 év|$1 év}}',
'ago' => '$1 ezelőtt',
'just-now' => 'épp most',

# Human-readable timestamps
'hours-ago' => '$1 {{PLURAL:$1|órával|órával}} ezelőtt',

# Bad image list
'bad_image_list' => 'A formátum a következő:

Csak a listatételek (csillaggal * kezdődő tételek) vannak figyelembe véve. Egy sor első hivatkozásának egy rossz képre mutató hivatkozásnak kell lennie.
Ugyanazon sor további hivatkozásai kivételnek tekintettek, pl. a szócikkek, ahol a kép bennük fordulhat elő.',

# Metadata
'metadata' => 'Metaadatok',
'metadata-help' => 'Ez a kép járulékos adatokat tartalmaz, amelyek feltehetően a kép létrehozásához használt digitális fényképezőgép vagy lapolvasó beállításairól adnak tájékoztatást.  Ha a képet az eredetihez képest módosították, ezen adatok eltérhetnek a kép tényleges jellemzőitől.',
'metadata-expand' => 'További képadatok',
'metadata-collapse' => 'További képadatok elrejtése',
'metadata-fields' => 'Az alábbi mezőben kilistázott képmetaadat mezők láthatóak maradnak a kép leírólapján,
míg a többi elem a táblázat összecsukása után alapértelmezett esetben rejtve marad.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* isospeedratings
* focallength
* artist
* copyright
* imagedescription
* gpslatitude
* gpslongitude
* gpsaltitude',

# Exif tags
'exif-imagewidth' => 'Szélesség',
'exif-imagelength' => 'Magasság',
'exif-bitspersample' => 'Bitek összetevőnként',
'exif-compression' => 'Tömörítési séma',
'exif-photometricinterpretation' => 'Színösszetevők',
'exif-orientation' => 'Tájolás',
'exif-samplesperpixel' => 'Színösszetevők száma',
'exif-planarconfiguration' => 'Adatok csoportosítása',
'exif-ycbcrsubsampling' => 'Y to C almintavételezésének aránya',
'exif-ycbcrpositioning' => 'Y és C pozicionálása',
'exif-xresolution' => 'Vízszintes felbontás',
'exif-yresolution' => 'Függőleges felbontás',
'exif-stripoffsets' => 'Képadatok elhelyezése',
'exif-rowsperstrip' => 'Egy csíkban levő sorok száma',
'exif-stripbytecounts' => 'Bájt/csík',
'exif-jpeginterchangeformat' => 'Eltolás JPEG SOI-be',
'exif-jpeginterchangeformatlength' => 'JPEG adatok bájtjai',
'exif-whitepoint' => 'Fehér pont színérték',
'exif-primarychromaticities' => 'Színinger',
'exif-ycbcrcoefficients' => 'Színtér transzformációs mátrixának együtthatói',
'exif-referenceblackwhite' => 'Fekete-fehér referenciaértékek párja',
'exif-datetime' => 'Utolsó változtatás ideje',
'exif-imagedescription' => 'Kép címe',
'exif-make' => 'Fényképezőgép gyártója',
'exif-model' => 'Fényképezőgép típusa',
'exif-software' => 'Használt szoftver',
'exif-artist' => 'Szerző',
'exif-copyright' => 'Szerzői jog tulajdonosa',
'exif-exifversion' => 'EXIF verzió',
'exif-flashpixversion' => 'Támogatott Flashpix verzió',
'exif-colorspace' => 'Színtér',
'exif-componentsconfiguration' => 'Az egyes összetevők jelentése',
'exif-compressedbitsperpixel' => 'Képtömörítési mód',
'exif-pixelydimension' => 'Képszélesség',
'exif-pixelxdimension' => 'Képmagasság',
'exif-usercomment' => 'Felhasználók megjegyzései',
'exif-relatedsoundfile' => 'Kapcsolódó hangfájl',
'exif-datetimeoriginal' => 'EXIF információ létrehozásának dátuma',
'exif-datetimedigitized' => 'Digitalizálás dátuma és időpontja',
'exif-subsectime' => 'DateTime almásodpercek',
'exif-subsectimeoriginal' => 'DateTimeOriginal almásodpercek',
'exif-subsectimedigitized' => 'DateTimeDigitized almásodpercek',
'exif-exposuretime' => 'Expozíciós idő',
'exif-exposuretime-format' => '$1 mp. ($2)',
'exif-fnumber' => 'Rekesznyílás',
'exif-exposureprogram' => 'Expozíciós program',
'exif-spectralsensitivity' => 'Színkép érzékenysége',
'exif-isospeedratings' => 'ISO érzékenység értéke',
'exif-shutterspeedvalue' => 'APEX zársebesség',
'exif-aperturevalue' => 'APEX lencsenyílás',
'exif-brightnessvalue' => 'APEX fényerő',
'exif-exposurebiasvalue' => 'Expozíciós eltolás',
'exif-maxaperturevalue' => 'Legnagyobb rekesznyílás',
'exif-subjectdistance' => 'Tárgy távolsága',
'exif-meteringmode' => 'Fénymérési mód',
'exif-lightsource' => 'Fényforrás',
'exif-flash' => 'Vaku',
'exif-focallength' => 'Fókusztávolság',
'exif-subjectarea' => 'Tárgy területe',
'exif-flashenergy' => 'Vaku ereje',
'exif-focalplanexresolution' => 'Mátrixdetektor X felbontása',
'exif-focalplaneyresolution' => 'Mátrixdetektor Y felbontása',
'exif-focalplaneresolutionunit' => 'Mátrixdetektor felbontásának mértékegysége',
'exif-subjectlocation' => 'Tárgy helye',
'exif-exposureindex' => 'Expozíciós index',
'exif-sensingmethod' => 'Érzékelési mód',
'exif-filesource' => 'Fájl forrása',
'exif-scenetype' => 'Színhely típusa',
'exif-customrendered' => 'Egyéni képfeldolgozás',
'exif-exposuremode' => 'Expozíciós mód',
'exif-whitebalance' => 'Fehéregyensúly',
'exif-digitalzoomratio' => 'Digitális zoom aránya',
'exif-focallengthin35mmfilm' => 'Fókusztávolság 35 mm-es filmen',
'exif-scenecapturetype' => 'Színhely rögzítési típusa',
'exif-gaincontrol' => 'Érzékelés vezérlése',
'exif-contrast' => 'Kontraszt',
'exif-saturation' => 'Telítettség',
'exif-sharpness' => 'Élesség',
'exif-devicesettingdescription' => 'Eszközbeállítások leírása',
'exif-subjectdistancerange' => 'Tárgy távolsági tartománya',
'exif-imageuniqueid' => 'Egyedi képazonosító',
'exif-gpsversionid' => 'GPS kód verziója',
'exif-gpslatituderef' => 'Északi vagy déli szélességi fok',
'exif-gpslatitude' => 'Szélességi fok',
'exif-gpslongituderef' => 'Keleti vagy nyugati hosszúsági fok',
'exif-gpslongitude' => 'Hosszúsági fok',
'exif-gpsaltituderef' => 'Tengerszint feletti magasság hivatkozás',
'exif-gpsaltitude' => 'Tengerszint feletti magasság',
'exif-gpstimestamp' => 'GPS idő (atomóra)',
'exif-gpssatellites' => 'Méréshez felhasznált műholdak',
'exif-gpsstatus' => 'Vevő állapota',
'exif-gpsmeasuremode' => 'Mérési mód',
'exif-gpsdop' => 'Mérés pontossága',
'exif-gpsspeedref' => 'Sebesség mértékegysége',
'exif-gpsspeed' => 'GPS vevő sebessége',
'exif-gpstrackref' => 'Hivatkozás a mozgásirányra',
'exif-gpstrack' => 'Mozgásirány',
'exif-gpsimgdirectionref' => 'Hivatkozás a kép irányára',
'exif-gpsimgdirection' => 'Kép iránya',
'exif-gpsmapdatum' => 'Felhasznált geodéziai kérdőív adatai',
'exif-gpsdestlatituderef' => 'Hivatkozás a cél szélességi fokára',
'exif-gpsdestlatitude' => 'Szélességi fok célja',
'exif-gpsdestlongituderef' => 'Hivatkozás a cél hosszúsági fokára',
'exif-gpsdestlongitude' => 'Cél hosszúsági foka',
'exif-gpsdestbearingref' => 'Hivatkozás a cél hordozójára',
'exif-gpsdestbearing' => 'Cél hordozója',
'exif-gpsdestdistanceref' => 'Hivatkozás a cél távolságára',
'exif-gpsdestdistance' => 'Cél távolsága',
'exif-gpsprocessingmethod' => 'GPS feldolgozási mód neve',
'exif-gpsareainformation' => 'GPS terület neve',
'exif-gpsdatestamp' => 'GPS dátum',
'exif-gpsdifferential' => 'GPS különbözeti korrekció',
'exif-jpegfilecomment' => 'JPEG fájlmegjegyzés',
'exif-keywords' => 'Kulcsszavak',
'exif-worldregioncreated' => 'Világrész, ahol a kép készült',
'exif-countrycreated' => 'Ország, ahol a kép készült',
'exif-countrycodecreated' => 'Ország kódja, ahol a kép készült',
'exif-provinceorstatecreated' => 'Tartomány vagy állam, ahol a kép készült',
'exif-citycreated' => 'Város, ahol a kép készült',
'exif-sublocationcreated' => 'Városbeli hely, ahol a kép készült',
'exif-worldregiondest' => 'Ábrázolt világrész',
'exif-countrydest' => 'Ábrázolt ország',
'exif-countrycodedest' => 'Ábrázolt ország kódja',
'exif-provinceorstatedest' => 'Ábrázolt tartomány vagy állam',
'exif-citydest' => 'Ábrázolt város',
'exif-sublocationdest' => 'Ábrázolt városbeli hely',
'exif-objectname' => 'Rövid cím',
'exif-specialinstructions' => 'Különleges utasítások',
'exif-headline' => 'Fejléc',
'exif-credit' => 'Köszönet/Készítő',
'exif-source' => 'Forrás',
'exif-editstatus' => 'Kép szerkesztési állapota',
'exif-urgency' => 'Sürgősség',
'exif-fixtureidentifier' => 'A készülék neve',
'exif-locationdest' => 'Ábrázolt helyszín',
'exif-locationdestcode' => 'Ábrázolt helyszín kódja',
'exif-objectcycle' => 'Napszak, amikorra a média készült',
'exif-contact' => 'Elérhetőségi adatok',
'exif-writer' => 'Író',
'exif-languagecode' => 'Nyelv',
'exif-iimversion' => 'IIM-verzió',
'exif-iimcategory' => 'Kategória',
'exif-iimsupplementalcategory' => 'Kiegészítő kategóriák',
'exif-datetimeexpires' => 'Nem használandó ezután',
'exif-datetimereleased' => 'Megjelenés ideje',
'exif-originaltransmissionref' => 'Eredeti átviteli hely kódja',
'exif-identifier' => 'Azonosító',
'exif-lens' => 'Használt lencse',
'exif-serialnumber' => 'Kamera sorozatszáma',
'exif-cameraownername' => 'Kamera tulajdonosa',
'exif-label' => 'Címke',
'exif-datetimemetadata' => 'Dátum metaadat utolsó módosítása',
'exif-nickname' => 'A kép informális neve',
'exif-rating' => 'Értékelés (5-ből)',
'exif-rightscertificate' => 'Jogokat kezelő tanúsítvány',
'exif-copyrighted' => 'Szerzői jogi állapot',
'exif-copyrightowner' => 'Szerzői jog tulajdonosa',
'exif-usageterms' => 'Felhasználási feltételek',
'exif-webstatement' => 'Online szerzői jogi nyilatkozat',
'exif-originaldocumentid' => 'Eredeti dokumentum egyedi azonosítója',
'exif-licenseurl' => 'Szerzői jog engedély URL-címe',
'exif-morepermissionsurl' => 'Alternatív licencinformáció',
'exif-attributionurl' => 'Újrafelhasználás során hivatkozz erre:',
'exif-preferredattributionname' => 'Ha újra felhasználód, köszönd meg:',
'exif-pngfilecomment' => 'PNG fájlmegjegyzés',
'exif-disclaimer' => 'Jogi nyilatkozat',
'exif-contentwarning' => 'Tartalom figyelmeztetés',
'exif-giffilecomment' => 'GIF fájlmegjegyzés',
'exif-intellectualgenre' => 'Elemtípus',
'exif-subjectnewscode' => 'Tárgykód',
'exif-scenecode' => 'IPTC jelenet kód',
'exif-event' => 'Ábrázolt esemény',
'exif-organisationinimage' => 'Ábrázolt szervezet',
'exif-personinimage' => 'Ábrázolt személy',
'exif-originalimageheight' => 'Kép magassága a levágás előtt',
'exif-originalimagewidth' => 'Kép szélessége a levágás előtt',

# Exif attributes
'exif-compression-1' => 'Nem tömörített',
'exif-compression-2' => 'CCITT Group 3 1 dimenziós módosított Huffman kódolás',
'exif-compression-3' => 'CCITT Group 3 fax kódolás',
'exif-compression-4' => 'CCITT Group 4 fax kódolás',

'exif-copyrighted-true' => 'Szerzői jog által védett',
'exif-copyrighted-false' => 'Közkincs',

'exif-unknowndate' => 'Ismeretlen dátum',

'exif-orientation-1' => 'Normál',
'exif-orientation-2' => 'Vízszintesen tükrözött',
'exif-orientation-3' => 'Elforgatott 180°',
'exif-orientation-4' => 'Függőlegesen tükrözött',
'exif-orientation-5' => 'Elforgatott 90° ÓE és függőlegesen tükrözött',
'exif-orientation-6' => 'Elforgatott 90° ÓE',
'exif-orientation-7' => 'Elforgatott 90° ÓSZ és függőlegesen tükrözött',
'exif-orientation-8' => 'Elforgatott 90° ÓSZ',

'exif-planarconfiguration-1' => 'Egyben',
'exif-planarconfiguration-2' => 'sík formátum',

'exif-colorspace-65535' => 'Nem kalibrált',

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

'exif-meteringmode-0' => 'Ismeretlen',
'exif-meteringmode-1' => 'Átlagos',
'exif-meteringmode-2' => 'CenterWeightedAverage',
'exif-meteringmode-3' => 'Megvilágítás',
'exif-meteringmode-4' => 'Többszörös megvilágítás',
'exif-meteringmode-5' => 'Minta',
'exif-meteringmode-6' => 'Részleges',
'exif-meteringmode-255' => 'Egyéb',

'exif-lightsource-0' => 'Ismeretlen',
'exif-lightsource-1' => 'Természetes fény',
'exif-lightsource-2' => 'Fénycső',
'exif-lightsource-3' => 'Wolfram (izzófény)',
'exif-lightsource-4' => 'Vaku',
'exif-lightsource-9' => 'Derült idő',
'exif-lightsource-10' => 'Felhős idő',
'exif-lightsource-11' => 'Árnyék',
'exif-lightsource-12' => 'Természetes fény fénycső (D 5700 – 7100K)',
'exif-lightsource-13' => 'Napfehér fénycső (N 4600 – 5400K)',
'exif-lightsource-14' => 'Hideg fehér fénycső (W 3900 – 4500K)',
'exif-lightsource-15' => 'Fehér fénycső (WW 3200 – 3700K)',
'exif-lightsource-17' => 'Hagyományos izzó A',
'exif-lightsource-18' => 'Hagyományos izzó B',
'exif-lightsource-19' => 'Hagyományos izzó C',
'exif-lightsource-24' => 'ISO stúdió wolfram',
'exif-lightsource-255' => 'Egyéb fényforrás',

# Flash modes
'exif-flash-fired-0' => 'A vaku nem sült el',
'exif-flash-fired-1' => 'A vaku elsült',
'exif-flash-return-0' => 'Nincs strobe return detection funkció.',
'exif-flash-return-2' => 'strobe return light nincs érzékelve',
'exif-flash-return-3' => 'strobe return light érzékelve',
'exif-flash-mode-1' => 'Kötelező vaku',
'exif-flash-mode-2' => 'Kötelező vakukikapcsolás',
'exif-flash-mode-3' => 'automatikus mód',
'exif-flash-function-1' => 'Nincs vakufunkció',
'exif-flash-redeye-1' => 'Vörös szem eltávolító mód',

'exif-focalplaneresolutionunit-2' => 'hüvelyk',

'exif-sensingmethod-1' => 'Nem meghatározott',
'exif-sensingmethod-2' => 'Egylapkás színterület-érzékelő',
'exif-sensingmethod-3' => 'Kétlapkás színterület-érzékelő',
'exif-sensingmethod-4' => 'Háromlapkás színterület-érzékelő',
'exif-sensingmethod-5' => 'Színsorrendi területérzékelő',
'exif-sensingmethod-7' => 'Háromvonalas érzékelő',
'exif-sensingmethod-8' => 'Színsorrendi vonalas érzékelő',

'exif-filesource-3' => 'Digitális fényképezőgép',

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

# Pseudotags used for GPSAltitudeRef
'exif-gpsaltitude-above-sealevel' => '$1 méterrel a tengerszint felett',
'exif-gpsaltitude-below-sealevel' => '$1 méterrel a tengerszint alatt',

'exif-gpsstatus-a' => 'Mérés folyamatban',
'exif-gpsstatus-v' => 'Mérés közbeni működőképesség',

'exif-gpsmeasuremode-2' => '2-dimenziós méret',
'exif-gpsmeasuremode-3' => '3-dimenziós méret',

# Pseudotags used for GPSSpeedRef
'exif-gpsspeed-k' => 'Kilométer óránként',
'exif-gpsspeed-m' => 'Mérföld óránként',
'exif-gpsspeed-n' => 'Csomó',

# Pseudotags used for GPSDestDistanceRef
'exif-gpsdestdistance-k' => 'kilométer',
'exif-gpsdestdistance-m' => 'mérföld',
'exif-gpsdestdistance-n' => 'tengeri mérföld',

'exif-gpsdop-excellent' => 'Kiváló ($1)',
'exif-gpsdop-good' => 'Jó ($1)',
'exif-gpsdop-moderate' => 'Mérsékelt ($1)',
'exif-gpsdop-fair' => 'Elfogadható ($1)',
'exif-gpsdop-poor' => 'Gyenge ($1)',

'exif-objectcycle-a' => 'Csak reggel',
'exif-objectcycle-p' => 'Csak este',
'exif-objectcycle-b' => 'Reggel és este',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'Igazi irány',
'exif-gpsdirection-m' => 'Mágneses irány',

'exif-ycbcrpositioning-1' => 'Központosított',
'exif-ycbcrpositioning-2' => 'Szomszédos',

'exif-dc-contributor' => 'Közreműködők',
'exif-dc-coverage' => 'A média térbeli vagy időbeli hatálya',
'exif-dc-date' => 'Dátum(ok)',
'exif-dc-publisher' => 'Kiadó',
'exif-dc-relation' => 'Kapcsolódó média',
'exif-dc-rights' => 'Jogok',
'exif-dc-source' => 'Forrás-adathordozó',
'exif-dc-type' => 'Adathordozó típusa',

'exif-rating-rejected' => 'Elutasítva',

'exif-isospeedratings-overflow' => 'Nagyobb, mint 65535',

'exif-iimcategory-ace' => 'Művészetek, kultúra és szórakoztatás',
'exif-iimcategory-clj' => 'Bűnözés és törvény',
'exif-iimcategory-dis' => 'Katasztrófák és a balesetek',
'exif-iimcategory-fin' => 'Gazdaság és üzlet',
'exif-iimcategory-edu' => 'Oktatás',
'exif-iimcategory-evn' => 'Környezet',
'exif-iimcategory-hth' => 'Egészség',
'exif-iimcategory-hum' => 'Emberi érdeklődés',
'exif-iimcategory-lab' => 'Munka',
'exif-iimcategory-lif' => 'Életmód és szabadidő',
'exif-iimcategory-pol' => 'Politika',
'exif-iimcategory-rel' => 'Vallás és hit',
'exif-iimcategory-sci' => 'Tudomány és technológia',
'exif-iimcategory-soi' => 'Társadalmi kérdések',
'exif-iimcategory-spo' => 'Sport',
'exif-iimcategory-war' => 'Háború, konfliktus és nyugtalanság',
'exif-iimcategory-wea' => 'Időjárás',

'exif-urgency-normal' => 'Normális ($1)',
'exif-urgency-low' => 'Alacsony ($1)',
'exif-urgency-high' => 'Magas ($1)',
'exif-urgency-other' => 'Egyedi prioritás ($1)',

# External editor support
'edit-externally' => 'A fájl szerkesztése külső alkalmazással',
'edit-externally-help' => '(Lásd a [//www.mediawiki.org/wiki/Manual:External_editors használati utasítást] (angolul) a beállításához.)',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'bármikor',
'namespacesall' => 'Összes',
'monthsall' => 'mind',
'limitall' => 'mind',

# Email address confirmation
'confirmemail' => 'E-mail cím megerősítése',
'confirmemail_noemail' => 'Nincs érvényes e-mail cím megadva a [[Special:Preferences|beállításaidnál]].',
'confirmemail_text' => 'Meg kell erősítened az e-mail címed, mielőtt használhatnád a(z) {{SITENAME}} levelezési rendszerét. Nyomd meg az alsó gombot, hogy kaphass egy e-mailt, melyben megtalálod a megerősítéshez szükséges kódot. Töltsd be a kódot a böngésződbe, hogy aktiválhasd az e-mail címedet.',
'confirmemail_pending' => 'A megerősítő kódot már elküldtük neked e-mailben, kérjük, várj türelemmel, amíg a szükséges adatok megérkeznek az e-mailcímedre, és csak akkor kérj új kódot, ha valami technikai malőr folytán értelmes időn belül nem kapod meg a levelet.',
'confirmemail_send' => 'Küldd el a kódot',
'confirmemail_sent' => 'Kaptál egy e-mailt, melyben megtalálod a megerősítéshez szükséges kódot.',
'confirmemail_oncreate' => 'A megerősítő kódot elküldtük az e-mail címedre.
Ez a kód nem szükséges a belépéshez, de meg kell adnod,
mielőtt a wiki e-mail alapú szolgáltatásait igénybe veheted.',
'confirmemail_sendfailed' => 'Nem sikerült elküldeni a megerősítő e-mailt.
Ellenőrizd, hogy nem írtál-e érvénytelen karaktert a címbe.

A levelező üzenete: $1',
'confirmemail_invalid' => 'Nem megfelelő kód. A kódnak lehet, hogy lejárt a felhasználhatósági ideje.',
'confirmemail_needlogin' => 'Meg kell $1 erősíteni az e-mail címedet.',
'confirmemail_success' => 'Az e-mail címed megerősítve. Most már beléphetsz a wikibe.',
'confirmemail_loggedin' => 'E-mail címed megerősítve.',
'confirmemail_error' => 'Hiba az e-mail címed megerősítése során.',
'confirmemail_subject' => '{{SITENAME}} e-mail cím megerősítés',
'confirmemail_body' => 'Valaki, valószínűleg te, ezzel az e-mail címmel regisztrált
„$2” néven a(z) {{SITENAME}} wikin, a(z) $1 IP-címről.

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
'confirmemail_body_set' => 'Valaki, valószínűleg te, ezt az email címet adta meg
„$2” nevű {{SITENAME}}-fiókjához a következő IP-címről: $1.

Ha meg szeretnéd erősíteni, hogy a fiók valóban hozzád tartozik, így aktiválva a(z) {{SITENAME}} e-mailes funkcióit, nyisd meg az alábbi linket a böngésződben:

$3

Ha a fiók *nem* hozzád tartozik, kövesd az alábbi linket a
megerősítés visszavonásához:

$5

Ez a megerősítő e-mail $4-ig érvényes.',
'confirmemail_invalidated' => 'E-mail-cím megerősíthetősége visszavonva',
'invalidateemail' => 'E-mail-cím megerősíthetőségének visszavonása',

# Scary transclusion
'scarytranscludedisabled' => '[Wikiközi beillesztés le van tiltva]',
'scarytranscludefailed' => '[$1 sablon letöltése sikertelen]',
'scarytranscludefailed-httpstatus' => ' [Nem sikerült betölteni a(z) $1 sablont: HTTP $2]',
'scarytranscludetoolong' => '[Az URL túl hosszú]',

# Delete conflict
'deletedwhileediting' => "'''Figyelmeztetés:''' A lapot a szerkesztés megkezdése után törölték!",
'confirmrecreate' => "Miután elkezdted szerkeszteni, [[User:$1|$1]] ([[User talk:$1|vita]]) törölte ezt a lapot a következő indokkal:
: ''$2''
Kérlek erősítsd meg, hogy tényleg újra akarod-e írni a lapot.",
'confirmrecreate-noreason' => '[[User:$1|$1]] ([[User talk:$1|vita]]) törölte ezt a lapot, miután elkezdtél szerkeszteni. Erősítsd meg, hogy tényleg ismét létre szeretnéd hozni a lapot.',
'recreate' => 'Újraírás',

# action=purge
'confirm_purge_button' => 'OK',
'confirm-purge-top' => 'Törlöd az oldal gyorsítótárban (cache) található változatát?',
'confirm-purge-bottom' => 'A lap ürítésével törlődik annak gyorsítótárazott változata, és a legújabb tartalom fog megjelenni.',

# action=watch/unwatch
'confirm-watch-button' => 'OK',
'confirm-watch-top' => 'Hozzá szeretnéd adni a lapot a figyelőlistádhoz?',
'confirm-unwatch-button' => 'OK',
'confirm-unwatch-top' => 'El szeretnéd távolítani a lapot a figyelőlistádról?',

# Separators for various lists, etc.
'ellipsis' => '…',

# Multipage image navigation
'imgmultipageprev' => '← előző oldal',
'imgmultipagenext' => 'következő oldal →',
'imgmultigo' => 'Menj',
'imgmultigoto' => 'Ugrás a(z) $1. oldalra',

# Table pager
'ascending_abbrev' => 'növ',
'descending_abbrev' => 'csökk',
'table_pager_next' => 'Következő oldal',
'table_pager_prev' => 'Előző oldal',
'table_pager_first' => 'Első oldal',
'table_pager_last' => 'Utolsó oldal',
'table_pager_limit' => 'Laponként $1 tétel megjelenítése',
'table_pager_limit_label' => 'Elemek száma oldalanként:',
'table_pager_limit_submit' => 'Ugrás',
'table_pager_empty' => 'Nincs találat',

# Auto-summaries
'autosumm-blank' => 'Eltávolította a lap teljes tartalmát',
'autosumm-replace' => 'A lap tartalmának cseréje erre: $1',
'autoredircomment' => 'Átirányítás ide: [[$1]]',
'autosumm-new' => 'Új oldal, tartalma: „$1”',

# Live preview
'livepreview-loading' => 'Betöltés…',
'livepreview-ready' => 'Betöltés… Kész!',
'livepreview-failed' => 'Az élő előnézet nem sikerült! Próbálkozz a normál előnézettel.',
'livepreview-error' => 'A csatlakozás nem sikerült: $1 "$2". Próbálkozz a normál előnézettel.',

# Friendlier slave lag warnings
'lag-warn-normal' => '{{PLURAL:$1|Az egy|A(z) $1}} másodpercnél frissebb szerkesztések nem biztos, hogy megjelennek ezen a listán.',
'lag-warn-high' => 'Az adatbázisszerver túlterheltsége miatt {{PLURAL:$1|az egy|a(z) $1}} másodpercnél frissebb változtatások nem biztos, hogy megjelennek ezen a listán.',

# Watchlist editor
'watchlistedit-numitems' => 'A figyelőlistádon {{PLURAL:$1|egy|$1}} cím szerepel (a vitalapok nélkül).',
'watchlistedit-noitems' => 'A figyelőlistád üres.',
'watchlistedit-normal-title' => 'A figyelőlista szerkesztése',
'watchlistedit-normal-legend' => 'Lapok eltávolítása a figyelőlistáról',
'watchlistedit-normal-explain' => 'A figyelőlistádra felvett lapok címei alább láthatóak.
Ha el szeretnél távolítani egy címet, pipáld ki a mellette található jelölőnégyzetet, majd kattints „{{int:Watchlistedit-normal-submit}}” gombra.
Lehetőséged van a [[Special:EditWatchlist/raw|figyelőlista nyers változatának]] szerkesztésére is.',
'watchlistedit-normal-submit' => 'A kijelöltek eltávolítása',
'watchlistedit-normal-done' => '{{PLURAL:$1|A következő|A következő $1}} cikket eltávolítottam a figyelőlistádról:',
'watchlistedit-raw-title' => 'A nyers figyelőlista szerkesztése',
'watchlistedit-raw-legend' => 'A nyers figyelőlista szerkesztése',
'watchlistedit-raw-explain' => 'A figyelőlistádra felvett lapok az alábbi listában találhatók. A lista szerkeszthető;
minden egyes sor egy figyelt lap címe. Ha kész vagy, kattints a lista alatt található
„{{int:Watchlistedit-raw-submit}}” feliratú gombra. Használhatod a [[Special:EditWatchlist|hagyományos listaszerkesztőt]] is.',
'watchlistedit-raw-titles' => 'A figyelőlistádon található cikkek:',
'watchlistedit-raw-submit' => 'Mentés',
'watchlistedit-raw-done' => 'A figyelőlistád változtatásait elmentettem.',
'watchlistedit-raw-added' => 'A {{PLURAL:$1|következő|következő $1}} cikket hozzáadtam a figyelőlistádhoz:',
'watchlistedit-raw-removed' => 'A {{PLURAL:$1|következő|következő $1}} cikket eltávolítottam a figyelőlistádról:',

# Watchlist editing tools
'watchlisttools-view' => 'Kapcsolódó változtatások',
'watchlisttools-edit' => 'A figyelőlista megtekintése és szerkesztése',
'watchlisttools-raw' => 'A nyers figyelőlista szerkesztése',

# Signatures
'signature' => '[[{{ns:user}}:$1|$2]] ([[{{ns:user_talk}}:$1|vita]])',

# Core parser functions
'unknown_extension_tag' => 'Ismeretlen tag kiterjesztés: $1',
'duplicate-defaultsort' => 'Figyelem: a(z) „$2” rendezőkulcs felülírja a korábbit („$1”).',

# Special:Version
'version' => 'Névjegy',
'version-extensions' => 'Telepített kiterjesztések',
'version-specialpages' => 'Speciális lapok',
'version-parserhooks' => 'Értelmező hookok',
'version-variables' => 'Változók',
'version-antispam' => 'Spammegelőzés',
'version-skins' => 'Felületek',
'version-other' => 'Egyéb',
'version-mediahandlers' => 'Médiafájl-kezelők',
'version-hooks' => 'Hookok',
'version-parser-extensiontags' => 'Az értelmező kiterjesztéseinek tagjei',
'version-parser-function-hooks' => 'Az értelmező függvényeinek hookjai',
'version-hook-name' => 'Hook neve',
'version-hook-subscribedby' => 'Használja',
'version-version' => '(verzió: $1)',
'version-license' => 'Licenc',
'version-poweredby-credits' => "Ez a wiki '''[//www.mediawiki.org/ MediaWiki]''' szoftverrel működik, copyright © 2001-$1 $2.",
'version-poweredby-others' => 'mások',
'version-poweredby-translators' => 'translatewiki.net fordítók',
'version-credits-summary' => 'Szeretnénk elismerni a következő személyek hozzájárulását a [[Special:Version|MediaWiki]] szoftverhez.',
'version-license-info' => 'A MediaWiki szabad szoftver, terjeszthető és / vagy módosítható a GNU General Public License alatt, amit a Free Software Foundation közzétett; vagy a 2-es verziójú licenc, vagy (az Ön választása alapján) bármely későbbi verzió szerint. 

A MediaWikit abban a reményben terjesztjük, hogy hasznos lesz, de GARANCIA NÉLKÜL, anélkül, hogy PIACKÉPES vagy HASZNÁLHATÓ LENNE EGY ADOTT CÉLRA. Lásd a GNU General Public License-t a további részletekért. 

Önnek kapnia kellett [{{SERVER}}{{SCRIPTPATH}}/COPYING egy példányt a GNU General Public License-ből] ezzel a programmal együtt, ha nem, írjon a Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA címre vagy [//www.gnu.org/licenses/old-licenses/gpl-2.0.html olvassa el online].',
'version-software' => 'Telepített szoftverek',
'version-software-product' => 'Termék',
'version-software-version' => 'Verzió',
'version-entrypoints' => 'Belépési pont URL-címek',
'version-entrypoints-header-entrypoint' => 'Belépési pont',
'version-entrypoints-header-url' => 'URL',

# Special:FileDuplicateSearch
'fileduplicatesearch' => 'Duplikátumok keresése',
'fileduplicatesearch-summary' => 'Fájlok duplikátumainak keresése hash értékük alapján.',
'fileduplicatesearch-legend' => 'Duplikátum keresése',
'fileduplicatesearch-filename' => 'Fájlnév:',
'fileduplicatesearch-submit' => 'Keresés',
'fileduplicatesearch-info' => '$1 × $2 pixel<br />Fájlméret: $3<br />MIME-típus: $4',
'fileduplicatesearch-result-1' => 'A(z) „$1“ nevű fájlnak nincs duplikátuma.',
'fileduplicatesearch-result-n' => 'A(z) „$1” nevű fájlnak {{PLURAL:$2|egy|$2}} duplikátuma van.',
'fileduplicatesearch-noresults' => 'Nincs „$1” nevű fájl.',

# Special:SpecialPages
'specialpages' => 'Speciális lapok',
'specialpages-note' => '----
* Mindenki számára elérhető speciális lapok.
* <span class="mw-specialpagerestricted">Korlátozott hozzáférésű speciális lapok.</span>',
'specialpages-group-maintenance' => 'Állapotjelentések',
'specialpages-group-other' => 'További speciális lapok',
'specialpages-group-login' => 'Bejelentkezés / fiók létrehozása',
'specialpages-group-changes' => 'Friss változások, naplók',
'specialpages-group-media' => 'Médiafájlok, feltöltések',
'specialpages-group-users' => 'Szerkesztők és jogaik',
'specialpages-group-highuse' => 'Gyakran használt lapok',
'specialpages-group-pages' => 'Listák',
'specialpages-group-pagetools' => 'Eszközök',
'specialpages-group-wiki' => 'A wiki adatai és eszközei',
'specialpages-group-redirects' => 'Átirányító speciális lapok',
'specialpages-group-spam' => 'Spam eszközök',

# Special:BlankPage
'blankpage' => 'Üres lap',
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
'tags' => 'Érvényes módosítási címkék',
'tag-filter' => '[[Special:Tags|Címke]]szűrő:',
'tag-filter-submit' => 'Szűrő',
'tags-title' => 'Címkék',
'tags-intro' => 'Ez a lap azokat a címkéket és jelentéseiket tartalmazza, amikkel a szoftver megjelölhet egy szerkesztést.',
'tags-tag' => 'Címke neve',
'tags-display-header' => 'Megjelenése a listákon',
'tags-description-header' => 'Teljes leírás',
'tags-hitcount-header' => 'Címkézett változtatások',
'tags-edit' => 'szerkesztés',
'tags-hitcount' => '{{PLURAL:$1|Egy|$1}} változtatás',

# Special:ComparePages
'comparepages' => 'Lapok összehasonlítása',
'compare-selector' => 'Lapváltozatok összehasonlítása',
'compare-page1' => '1. lap',
'compare-page2' => '2. lap',
'compare-rev1' => '1. változat',
'compare-rev2' => '2. változat',
'compare-submit' => 'Összehasonlítás',
'compare-invalid-title' => 'A megadott cím érvénytelen.',
'compare-title-not-exists' => 'A megadott cím nem létezik.',
'compare-revision-not-exists' => 'A megadott lapváltozat nem létezik.',

# Database error messages
'dberr-header' => 'A wikivel problémák vannak',
'dberr-problems' => 'Sajnáljuk, de az oldallal technikai problémák vannak.',
'dberr-again' => 'Várj néhány percet, majd frissítsd az oldalt.',
'dberr-info' => '(Nem sikerült kapcsolatot létesíteni az adatbázisszerverrel: $1)',
'dberr-usegoogle' => 'A probléma elmúlásáig próbálhatsz keresni a Google-lel.',
'dberr-outofdate' => 'Fontos tudnivaló, hogy az oldal tartalmáról készített indexeik elavultak lehetnek.',
'dberr-cachederror' => 'Lenn a kért oldal gyorsítótárazott változata látható, és lehet, hogy nem teljesen friss.',

# HTML forms
'htmlform-invalid-input' => 'Probléma van az általad megadott értékkel',
'htmlform-select-badoption' => 'A megadott érték nem érvényes.',
'htmlform-int-invalid' => 'A megadott érték nem szám.',
'htmlform-float-invalid' => 'A megadott érték nem szám.',
'htmlform-int-toolow' => 'A megadott érték a minimum, $1 alatt van',
'htmlform-int-toohigh' => 'A megadott érték a maximum, $1 felett van',
'htmlform-required' => 'Az érték megadása kötelező',
'htmlform-submit' => 'Elküldés',
'htmlform-reset' => 'Változtatások visszavonása',
'htmlform-selectorother-other' => 'egyéb',
'htmlform-no' => 'Nem',
'htmlform-yes' => 'Igen',

# SQLite database support
'sqlite-has-fts' => '$1 teljes szöveges keresés támogatással',
'sqlite-no-fts' => '$1 teljes szöveges keresés támogatása nélkül',

# New logging system
'logentry-delete-delete' => '$1 törölte a következő lapot: $3',
'logentry-delete-restore' => '$1 helyreállította a következő lapot: $3',
'logentry-delete-event' => '$1 megváltoztatta {{PLURAL:$5|egy napló bejegyzés|$5 napló bejegyzés}} láthatóságát a(z) $3 című lapon: $4',
'logentry-delete-revision' => '$1 módosította a(z) $3 című lap {{PLURAL:$5|egy|$1}} lapváltozatának láthatóságát: $4',
'logentry-delete-event-legacy' => '$1 módosította a(z) $3 című lap naplóbejegyzéseinek láthatóságát',
'logentry-delete-revision-legacy' => '$1 módosította a(z) $3 című lap lapváltozatainak láthatóságát',
'logentry-suppress-delete' => '$1 elrejtette a következő lapot: $3',
'logentry-suppress-event' => '$1 rejtetten megváltoztatta {{PLURAL:$5|egy napló bejegyzés|$5 napló bejegyzés}} láthatóságát a(z) $3 című lapon: $4',
'logentry-suppress-revision' => '$1 rejtetten megváltoztatta {{PLURAL:$5|egy változat|$5 változat}} láthatóságát a(z) $3 című lapon: $4',
'logentry-suppress-event-legacy' => '$1 rejtetten megváltoztatta napló bejegyzések láthatóságát a(z) $3 című lapon',
'logentry-suppress-revision-legacy' => '$1 rejtetten megváltoztatta változatok láthatóságát a(z) $3 lapon',
'revdelete-content-hid' => 'tartalom elrejtve',
'revdelete-summary-hid' => 'szerkesztési összefoglaló elrejtve',
'revdelete-uname-hid' => 'szerkesztő elrejtve',
'revdelete-content-unhid' => 'tartalom megjelenítve',
'revdelete-summary-unhid' => 'szerkesztési összefoglalás megjelenítve',
'revdelete-uname-unhid' => 'szerkesztő megjelenítve',
'revdelete-restricted' => 'elrejtett az adminisztrátorok elől',
'revdelete-unrestricted' => 'felfedett az adminisztrátoroknak',
'logentry-move-move' => '$1 átnevezte a(z) $3 lapot a következő névre: $4',
'logentry-move-move-noredirect' => '$1 átnevezte a(z) $3 lapot $4 lapra átirányítás nélkül',
'logentry-move-move_redir' => '$1 átnevezte a(z) $3 lapot $4 lapra az átirányítást felülírva',
'logentry-move-move_redir-noredirect' => '$1 átnevezte a(z) $3 lapot $4 lapra az átirányítást felülírva, átirányítás nélkül',
'logentry-patrol-patrol' => '$1 a(z) $3 lap $4 változatát ellenőrzöttnek jelölte',
'logentry-patrol-patrol-auto' => '$1 a(z) $3 lap $4 változatát automatikusan ellenőrzöttnek jelölte',
'logentry-newusers-newusers' => '$1 felhasználói fiók létrehozva',
'logentry-newusers-create' => '$1 felhasználói fiók létrehozva',
'logentry-newusers-create2' => '$1 létrehozta $3 felhasználói fiókját',
'logentry-newusers-byemail' => 'Szerkesztői lap $3 néven létrehozva $1 által, jelszó kiküldve emailben.',
'logentry-newusers-autocreate' => '$1 fiók automatikusan létrehozva',
'logentry-rights-rights' => '$1 megváltoztatta $3 csoporttagságát erről: $4 erre: $5',
'logentry-rights-rights-legacy' => '$1 megváltoztatta $3 csoporttagságát',
'logentry-rights-autopromote' => '$1 automatikusan előléptetve erről: $4 erre: $5',
'rightsnone' => '(semmi)',

# Feedback
'feedback-bugornote' => 'Ha kész technikai problémát részletesen leírni, akkor kérjük [$1 jelents egy hibát]. Egyébként használd az alábbi űrlapot. A hozzászólásod a „[$3 $2]” laphoz kerül felvételre, a szerkesztő neveddel és böngésződ típusával együtt.',
'feedback-subject' => 'Tárgy:',
'feedback-message' => 'Üzenet:',
'feedback-cancel' => 'Mégse',
'feedback-submit' => 'Visszajelzés elküldése',
'feedback-adding' => 'Visszajelzés elmentése...',
'feedback-error1' => 'Hiba: az API ismeretlen eredménnyel tért vissza',
'feedback-error2' => 'Hiba: a szerkesztés nem sikerült',
'feedback-error3' => 'Hiba: nem érkezett válasz az API-tól',
'feedback-thanks' => 'Köszönjük. A visszajelzésed elküldve a „[$2 $1]” laphoz.',
'feedback-close' => 'Kész',
'feedback-bugcheck' => 'Nagyszerű! Ellenőrizd, hogy ez nem egy [$1 ismert hiba].',
'feedback-bugnew' => 'Ellenőriztem. Új hiba jelentése',

# Search suggestions
'searchsuggest-search' => 'Keresés',
'searchsuggest-containing' => 'tartalmazza…',

# API errors
'api-error-badaccess-groups' => 'Nincs jogod fájlokat feltölteni erre a wikire.',
'api-error-badtoken' => 'Belső hiba: hibás token.',
'api-error-copyuploaddisabled' => 'Az URL-címes feltöltés nem engedélyezett ezen a kiszolgálón.',
'api-error-duplicate' => 'Már van {{PLURAL:$1|egy|néhány}} [$2 másik fájl] az oldalon ugyanilyen tartalommal',
'api-error-duplicate-archive' => 'Az oldalon {{PLURAL:$1|szerepelt|szerepeltek}} [$2 más {{PLURAL:$1|fájl|fájlok}}] is ugyanezzel a tartalommal, de törölve {{PLURAL:$1|lett|lettek}}.',
'api-error-duplicate-archive-popup-title' => '{{PLURAL:$1|Az azonos fájl, ami törölve lett|Azonos fájlok, amik törölve lettek}}',
'api-error-duplicate-popup-title' => '{{PLURAL:$1|Duplikátum|Duplikátumok}}',
'api-error-empty-file' => 'Az általad elküldött fájl üres volt.',
'api-error-emptypage' => 'Új, üres lap létrehozása nem engedélyezett.',
'api-error-fetchfileerror' => 'Belső hiba: valami baj történt a fájl beolvasása közben.',
'api-error-fileexists-forbidden' => 'Már létezik „$1” nevű fájl, és nem lehet felülírni.',
'api-error-fileexists-shared-forbidden' => 'Már létezik „$1” nevű fájl a megosztott fájlok között, és nem lehet felülírni.',
'api-error-file-too-large' => 'Az általad elküldött fájl túl nagy.',
'api-error-filename-tooshort' => 'A fájlnév túl rövid.',
'api-error-filetype-banned' => 'Tiltott fájltípus.',
'api-error-filetype-banned-type' => '!A következő {{PLURAL:$4|fájltípus nem engedélyezett|fájltípusok nem engedélyezettek}}: $1. Engedélyezett {{PLURAL:$3|típus|típusok}}: $2.',
'api-error-filetype-missing' => 'Hiányzik a fájl kiterjesztése.',
'api-error-hookaborted' => 'Az általad kezdeményezett módosítást nem lehet végrehajtani. (Egy bővítmény megakadályozta.)',
'api-error-http' => 'Belső hiba: nem sikerült kapcsolódni a kiszolgálóhoz.',
'api-error-illegal-filename' => 'Nem megengedett fájlnév.',
'api-error-internal-error' => 'Belső hiba: valami baj történt a feltöltésed feldolgozása közben.',
'api-error-invalid-file-key' => 'Belső hiba: a fájl nem található az ideiglenes tárhelyen.',
'api-error-missingparam' => 'Belső hiba: paraméterek hiányoznak a kérésből.',
'api-error-missingresult' => 'Belső hiba: nem sikerült megállapítani, hogy a másolás sikeres volt-e.',
'api-error-mustbeloggedin' => 'Be kell jelentkezned fájlok feltöltéséhez.',
'api-error-mustbeposted' => 'Belső hiba: a kérésnek HTTP POST-nak kell lennie.',
'api-error-noimageinfo' => 'A feltöltés sikerült, de a szerver nem szolgáltatott semmilyen információt a fájlról.',
'api-error-nomodule' => 'Belső hiba: nincs feltöltőmodul beállítva.',
'api-error-ok-but-empty' => 'Belső hiba: nem érkezett válasz a kiszolgálótól.',
'api-error-overwrite' => 'Létező fájlok felülírására nem engedélyezett.',
'api-error-stashfailed' => 'Belső hiba: a kiszolgálünak nem sikerült eltárolni az ideiglenes fájlt.',
'api-error-publishfailed' => 'Belső hiba: a kiszolgálónak nem sikerült közzétennie az ideiglenes fájlt.',
'api-error-timeout' => 'A kiszolgáló nem adott választ a várt időn belül.',
'api-error-unclassified' => 'Ismeretlen hiba történt',
'api-error-unknown-code' => 'Ismeretlen hiba: „$1”',
'api-error-unknown-error' => 'Belső hiba: valami baj történt a fájl feltöltése közben.',
'api-error-unknown-warning' => 'Ismeretlen figyelmeztetés: $1',
'api-error-unknownerror' => 'Ismeretlen hiba: „$1”.',
'api-error-uploaddisabled' => 'A feltöltés le van tiltva ezen a wikin.',
'api-error-verification-error' => 'A fájl feltehetőleg sérült, vagy hibás a kiterjesztése.',

# Durations
'duration-seconds' => '{{PLURAL: $1|másodperc|másodperc}}',
'duration-minutes' => '$1 {{PLURAL: $1|perc|perc}}',
'duration-hours' => '{{PLURAL:$1|egy|$1}} óra',
'duration-days' => '{{PLURAL:$1|egy|$1}} nap',
'duration-weeks' => '$1 {{PLURAL:$1|hét|hét}}',
'duration-years' => '{{PLURAL: $1|Egy év|$1 év}}',
'duration-decades' => '{{PLURAL:$1|egy|$1}} évtized',
'duration-centuries' => '{{PLURAL:$1|egy|$1}} évszázad',
'duration-millennia' => '{{PLURAL:$1|egy|$1}} évezred',

# Image rotation
'rotate-comment' => 'Elforgattam a képet $1 fokkal, az óramutató járásával megegyező irányban',

);
