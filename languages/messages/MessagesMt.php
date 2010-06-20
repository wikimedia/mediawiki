<?php
/** Maltese (Malti)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Chrisportelli
 * @author Giangian15
 * @author Malafaya
 * @author Roderick Mallia
 * @author Urhixidur
 */

$namespaceNames = array(
	NS_MEDIA            => 'Medja',
	NS_SPECIAL          => 'Speċjali',
	NS_TALK             => 'Diskussjoni',
	NS_USER             => 'Utent',
	NS_USER_TALK        => 'Diskussjoni_utent',
	NS_PROJECT_TALK     => 'Diskussjoni_$1',
	NS_FILE             => 'Stampa',
	NS_FILE_TALK        => 'Diskussjoni_stampa',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'Diskussjoni_MediaWiki',
	NS_TEMPLATE         => 'Mudell',
	NS_TEMPLATE_TALK    => 'Diskussjoni_mudell',
	NS_HELP             => 'Għajnuna',
	NS_HELP_TALK        => 'Diskussjoni_għajnuna',
	NS_CATEGORY         => 'Kategorija',
	NS_CATEGORY_TALK    => 'Diskussjoni_kategorija',
);

$namespaceAliases = array(
	'Midja' => NS_MEDIA,
	'Diskuti' => NS_TALK,
	'Diskuti_utent' => NS_USER_TALK,
	'$1_diskuti' => NS_PROJECT_TALK,
	'$1_diskussjoni' => NS_PROJECT_TALK,
	'Diskuti_stampa' => NS_FILE_TALK,
	'MedjaWiki' => NS_MEDIAWIKI,
	'Diskuti_MedjaWiki' => NS_MEDIAWIKI_TALK,
	'Diskuti_template' => NS_TEMPLATE_TALK,
	'Diskuti_għajnuna' => NS_HELP_TALK,
	'Diskuti_kategorija' => NS_CATEGORY_TALK,
);

$specialPageAliases = array(
	'DoubleRedirects'           => array( 'RindirizziDoppji', 'RiindirizziDoppji' ),
	'BrokenRedirects'           => array( 'RindirizziMiksura', 'RiindirizziMiksura' ),
	'Disambiguations'           => array( 'Diżambigwazzjoni' ),
	'Userlogin'                 => array( 'UtentDħul' ),
	'Userlogout'                => array( 'UtentĦruġ' ),
	'CreateAccount'             => array( 'OħloqKont' ),
	'Preferences'               => array( 'Preferenzi' ),
	'Watchlist'                 => array( 'ListaOsservazzjoni' ),
	'Recentchanges'             => array( 'TibdilRiċenti' ),
	'Upload'                    => array( 'Tella\'' ),
	'Listfiles'                 => array( 'ListaStampi', 'ListaFajls' ),
	'Newimages'                 => array( 'StampiĠodda', 'FajlsĠodda' ),
	'Listusers'                 => array( 'Utenti', 'ListaUtenti' ),
	'Listgrouprights'           => array( 'ListaDrittijietGruppi' ),
	'Statistics'                => array( 'Statistika' ),
	'Randompage'                => array( 'PaġnaKwalunkwe' ),
	'Lonelypages'               => array( 'PaġniOrfni' ),
	'Uncategorizedpages'        => array( 'PaġniMhuxKategorizzati' ),
	'Uncategorizedcategories'   => array( 'KategorijiMhuxKategorizzati' ),
	'Uncategorizedimages'       => array( 'StampiMhuxKategorizzati' ),
	'Uncategorizedtemplates'    => array( 'MudelliMhuxKategorizzati' ),
	'Unusedcategories'          => array( 'KategorijiMhuxUżati' ),
	'Unusedimages'              => array( 'StampiMhuxUżati', 'FajlsMhuxUżati' ),
	'Wantedpages'               => array( 'PaġniRikjesti', 'ĦoloqMiksura' ),
	'Wantedcategories'          => array( 'KategorijiRikjesti' ),
	'Wantedfiles'               => array( 'FajlsRikjesti' ),
	'Wantedtemplates'           => array( 'MudelliRikjesti' ),
	'Mostlinked'                => array( 'L-AktarPaġniMarbuta' ),
	'Mostlinkedcategories'      => array( 'L-AktarKategorijiMarbuta' ),
	'Mostlinkedtemplates'       => array( 'L-AktarMudelliMarbuta' ),
	'Mostimages'                => array( 'L-AktarStampiMarbuta' ),
	'Mostcategories'            => array( 'L-AktarKategoriji' ),
	'Mostrevisions'             => array( 'L-AktarReviżjonijiet' ),
	'Fewestrevisions'           => array( 'L-InqasReviżjonijiet' ),
	'Shortpages'                => array( 'PaġniQosra' ),
	'Longpages'                 => array( 'PaġniTwal' ),
	'Newpages'                  => array( 'PaġniĠodda' ),
	'Ancientpages'              => array( 'PaġniQodma', 'PaġniAntiki' ),
	'Deadendpages'              => array( 'PaġniWieqfa' ),
	'Protectedpages'            => array( 'PaġniProtetti' ),
	'Protectedtitles'           => array( 'TitliProtetti' ),
	'Allpages'                  => array( 'PaġniKollha' ),
	'Prefixindex'               => array( 'IndiċiPrefiss' ),
	'Ipblocklist'               => array( 'ListaIPImblukkati' ),
	'Unblock'                   => array( 'Żblokka' ),
	'Specialpages'              => array( 'PaġniSpeċjali' ),
	'Contributions'             => array( 'Kontribuzzjonijiet' ),
	'Emailuser'                 => array( 'IbgħatUtent' ),
	'Confirmemail'              => array( 'KonfermaPostaElettronika' ),
	'Whatlinkshere'             => array( 'XiJwassalHawn' ),
	'Recentchangeslinked'       => array( 'TibdilRelatat' ),
	'Movepage'                  => array( 'Mexxi', 'MexxiPaġna' ),
	'Blockme'                   => array( 'Imblukkani' ),
	'Booksources'               => array( 'SorsiKotba' ),
	'Categories'                => array( 'Kategoriji' ),
	'Export'                    => array( 'Esporta' ),
	'Version'                   => array( 'Verżjoni' ),
	'Allmessages'               => array( 'MessaġġiKollha' ),
	'Log'                       => array( 'Reġistru', 'Reġistri' ),
	'Blockip'                   => array( 'BlokkaIP' ),
	'Undelete'                  => array( 'Irkupra' ),
	'Import'                    => array( 'Importa' ),
	'Lockdb'                    => array( 'AgħlaqDB' ),
	'Unlockdb'                  => array( 'IftaħDB' ),
	'Userrights'                => array( 'DrittijietUtent' ),
	'MIMEsearch'                => array( 'FittexMIME' ),
	'FileDuplicateSearch'       => array( 'FittexFajlDuplikat' ),
	'Unwatchedpages'            => array( 'PaġniMhuxOsservati' ),
	'Listredirects'             => array( 'ListaRindirizzi', 'ListaRiindirizzi' ),
	'Revisiondelete'            => array( 'ĦassarReviżjoni' ),
	'Unusedtemplates'           => array( 'MudelliMhuxUżati' ),
	'Randomredirect'            => array( 'RiindirizzKwalunkwe' ),
	'Mypage'                    => array( 'PaġnaTiegħi' ),
	'Mytalk'                    => array( 'DiskussjonijietTiegħi' ),
	'Mycontributions'           => array( 'KontribuzzjonijietTiegħi' ),
	'Listadmins'                => array( 'ListaAmmin' ),
	'Listbots'                  => array( 'ListaBots' ),
	'Popularpages'              => array( 'PaġniPopolari' ),
	'Search'                    => array( 'Fittex' ),
	'Resetpass'                 => array( 'BiddelPassword' ),
	'Withoutinterwiki'          => array( 'PaġniMingħajrInterwiki', 'BlaInterwiki' ),
	'MergeHistory'              => array( 'WaħħadKronoloġija' ),
	'Filepath'                  => array( 'PostFajl' ),
	'Invalidateemail'           => array( 'PostaElettronikaInvalida' ),
	'Blankpage'                 => array( 'PaġnaVojta' ),
	'LinkSearch'                => array( 'FittexĦolqa' ),
	'DeletedContributions'      => array( 'KontribuzzjonijietImħassra' ),
	'Activeusers'               => array( 'UtentiAttivi' ),
);

$magicWords = array(
	'redirect'              => array( '0', '#RINDIRIZZA', '#REDIRECT' ),
	'notoc'                 => array( '0', '__EBDAWERREJ__', '__NOTOC__' ),
	'nogallery'             => array( '0', '__EBDAGALLERIJA__', '__NOGALLERY__' ),
	'forcetoc'              => array( '0', '__SFORZAWERREJ__', '__FORCETOC__' ),
	'toc'                   => array( '0', '__WERREJ__', '__TOC__' ),
	'noeditsection'         => array( '0', '__EBDASEZZJONIMODIFIKA__', '__NOEDITSECTION__' ),
	'currentmonth'          => array( '1', 'XAHARKURRENTI', 'CURRENTMONTH', 'CURRENTMONTH2' ),
	'currentmonthname'      => array( '1', 'ISEMXAHARKURRENTI', 'CURRENTMONTHNAME' ),
	'currentmonthnamegen'   => array( '1', 'ĠENISEMXAHARKURRENTI', 'CURRENTMONTHNAMEGEN' ),
	'currentmonthabbrev'    => array( '1', 'ABBREVXAHARKURRENTI', 'CURRENTMONTHABBREV' ),
	'currentday'            => array( '1', 'ĠURNATAKURRENTI', 'CURRENTDAY' ),
	'currentday2'           => array( '1', 'ĠURNATAKURRENTI2', 'CURRENTDAY2' ),
	'currentdayname'        => array( '1', 'ISEMĠURNATAKURRENTI', 'CURRENTDAYNAME' ),
	'currentyear'           => array( '1', 'SENAKURRENTI', 'CURRENTYEAR' ),
	'currenttime'           => array( '1', 'ĦINKURRENTI', 'CURRENTTIME' ),
	'currenthour'           => array( '1', 'SIEGĦAKURRENTI', 'CURRENTHOUR' ),
	'localmonth'            => array( '1', 'XAHARLOKALI', 'XAHARLOKALI2', 'LOCALMONTH', 'LOCALMONTH2' ),
	'localmonth1'           => array( '1', 'XAHARLOKALI1', 'LOCALMONTH1' ),
	'localmonthname'        => array( '1', 'ISEMXAHARLOKALI', 'LOCALMONTHNAME' ),
	'localmonthnamegen'     => array( '1', 'ĠENISEMXAHARLOKALI', 'LOCALMONTHNAMEGEN' ),
	'localmonthabbrev'      => array( '1', 'ABBREVXAHARLOKALI', 'LOCALMONTHABBREV' ),
	'localday'              => array( '1', 'ĠURNATALOKALI', 'LOCALDAY' ),
	'localday2'             => array( '1', 'ĠURNATALOKALI2', 'LOCALDAY2' ),
	'localdayname'          => array( '1', 'ISEMTAL-ĠURNATALOKALI', 'LOCALDAYNAME' ),
	'localyear'             => array( '1', 'SENALOKALI', 'LOCALYEAR' ),
	'localtime'             => array( '1', 'ĦINLOKALI', 'LOCALTIME' ),
	'localhour'             => array( '1', 'SIEGĦALOKALI', 'LOCALHOUR' ),
	'numberofpages'         => array( '1', 'NUMRUTA\'PAĠNI', 'NUMBEROFPAGES' ),
	'numberofarticles'      => array( '1', 'NUMRUTA\'ARTIKLI', 'NUMBEROFARTICLES' ),
	'numberoffiles'         => array( '1', 'NUMRUTA\'FAJLS', 'NUMBEROFFILES' ),
	'numberofusers'         => array( '1', 'NUMRUTA\'UTENTI', 'NUMBEROFUSERS' ),
	'numberofactiveusers'   => array( '1', 'NUMRUTA\'UTENTIATTIVI', 'NUMBEROFACTIVEUSERS' ),
	'numberofedits'         => array( '1', 'NUMBRUTA\'MODIFIKI', 'NUMBEROFEDITS' ),
	'numberofviews'         => array( '1', 'NUMRUTA\'VISTI', 'NUMBEROFVIEWS' ),
	'pagename'              => array( '1', 'ISEMTAL-PAĠNA', 'PAGENAME' ),
	'pagenamee'             => array( '1', 'ISEMTAL-PAĠNAE', 'PAGENAMEE' ),
	'namespace'             => array( '1', 'SPAZJUTAL-ISEM', 'NAMESPACE' ),
	'namespacee'            => array( '1', 'SPAZJUTAL-ISEME', 'NAMESPACEE' ),
	'talkspace'             => array( '1', 'SPAZJUTA\'DISKUSSJONI', 'TALKSPACE' ),
	'talkspacee'            => array( '1', 'SPAZJUTA\'DISKUSSJONIE', 'TALKSPACEE' ),
	'subjectspace'          => array( '1', 'SPAZJUTAS-SUĠĠETT', 'SPAZJUTAL-ARTIKLU', 'SUBJECTSPACE', 'ARTICLESPACE' ),
	'fullpagename'          => array( '1', 'ISEMSĦIĦTAL-PAĠNA', 'FULLPAGENAME' ),
	'fullpagenamee'         => array( '1', 'ISEMTAL-PAĠNASĦIĦAE', 'FULLPAGENAMEE' ),
	'subpagename'           => array( '1', 'ISEMTAS-SOTTOPAĠNA', 'SUBPAGENAME' ),
	'subpagenamee'          => array( '1', 'ISEMTAS-SUBPAĠNAE', 'SUBPAGENAMEE' ),
	'basepagename'          => array( '1', 'ISEMBAŻIKUTAL-PAĠNA', 'BASEPAGENAME' ),
	'basepagenamee'         => array( '1', 'ISEMTAL-PAĠNATAL-BAŻIE', 'BASEPAGENAMEE' ),
	'talkpagename'          => array( '1', 'ISEMPAĠNATA\'DISKUSSJONI', 'TALKPAGENAME' ),
	'talkpagenamee'         => array( '1', 'ISEMTAL-PAĠNATAD-DISKUSSJONIE', 'TALKPAGENAMEE' ),
	'subjectpagename'       => array( '1', 'ISEMTAS-SUĠĠETTTAL-PAĠNA', 'ISEMTAL-ARTIKLUTAL-PAĠNA', 'SUBJECTPAGENAME', 'ARTICLEPAGENAME' ),
	'subjectpagenamee'      => array( '1', 'ISEMTAS-SUĠĠETTTAL-PAĠNAE', 'ISEMTAL-ARTIKLUTAL-PAĠNAE', 'SUBJECTPAGENAMEE', 'ARTICLEPAGENAMEE' ),
	'msg'                   => array( '0', 'MSĠ:', 'MSG:' ),
	'subst'                 => array( '0', 'BIDDEL:', 'SUBST:' ),
	'msgnw'                 => array( '0', 'MSĠEW:', 'MSGNW:' ),
	'img_thumbnail'         => array( '1', 'daqsminuri', 'minuri', 'thumbnail', 'thumb' ),
	'img_manualthumb'       => array( '1', 'daqsminuri=$1', 'minuri=$1', 'thumbnail=$1', 'thumb=$1' ),
	'img_right'             => array( '1', 'lemin', 'right' ),
	'img_left'              => array( '1', 'xellug', 'left' ),
	'img_none'              => array( '1', 'xejn', 'none' ),
	'img_center'            => array( '1', 'nofs', 'ċentrali', 'ċentru', 'center', 'centre' ),
	'img_framed'            => array( '1', 'tilat', 'b\'tilar', 'tilar', 'framed', 'enframed', 'frame' ),
	'img_frameless'         => array( '1', 'bla tilar', 'frameless' ),
	'img_page'              => array( '1', 'paġna=$1', 'paġna $1', 'page=$1', 'page $1' ),
	'img_upright'           => array( '1', 'wieqaf', 'wieqaf=$1', 'wieqaf $1', 'upright', 'upright=$1', 'upright $1' ),
	'img_border'            => array( '1', 'bordura', 'burdura', 'border' ),
	'img_baseline'          => array( '1', 'bażi tal-linja', 'baseline' ),
	'img_sub'               => array( '1', 'bid', 'sub' ),
	'img_super'             => array( '1', 'tajjeb', 'super', 'sup' ),
	'img_top'               => array( '1', 'fuq', 'top' ),
	'img_text_top'          => array( '1', 'test-fuq', 'text-top' ),
	'img_bottom'            => array( '1', 'taħt', 'bottom' ),
	'img_text_bottom'       => array( '1', 'test-taħt', 'text-bottom' ),
	'img_link'              => array( '1', 'ħolqa=$1', 'link=$1' ),
	'sitename'              => array( '1', 'ISEMTAS-SIT', 'SITENAME' ),
	'ns'                    => array( '0', 'IS:', 'NS:' ),
	'localurl'              => array( '0', 'URLLOKALI:', 'LOCALURL:' ),
	'localurle'             => array( '0', 'URLLOKALIE:', 'LOCALURLE:' ),
	'servername'            => array( '0', 'ISEMTAS-SERVER', 'SERVERNAME' ),
	'scriptpath'            => array( '0', 'DESTINAZZJONITA\'SKRITT', 'SCRIPTPATH' ),
	'grammar'               => array( '0', 'GRAMMATIKA:', 'GRAMMAR:' ),
	'gender'                => array( '0', 'SESS:', 'GENDER:' ),
	'notitleconvert'        => array( '0', '__EBDAKONVERTURTITLU__', '__EBDAKT__', '__NOTITLECONVERT__', '__NOTC__' ),
	'nocontentconvert'      => array( '0', '__EBDAKONVERTURKONTENUT__', '__EBDAKK__', '__NOCONTENTCONVERT__', '__NOCC__' ),
	'currentweek'           => array( '1', 'ĠIMGĦAKURRENTI', 'CURRENTWEEK' ),
	'currentdow'            => array( '1', 'ĠTĠKURRENTI', 'CURRENTDOW' ),
	'localweek'             => array( '1', 'ĠIMGĦALOKALI', 'LOCALWEEK' ),
	'localdow'              => array( '1', 'ĠTĠLOKALI', 'LOCALDOW' ),
	'revisionid'            => array( '1', 'IDTAR-REVIŻJONI', 'REVISIONID' ),
	'revisionday'           => array( '1', 'ĠURNATATAR-REVIŻJONI', 'REVISIONDAY' ),
	'revisionday2'          => array( '1', 'ĠURNATATAR-REVIŻJONI2', 'REVISIONDAY2' ),
	'revisionmonth'         => array( '1', 'XAHARTAR-REVIŻJONI', 'REVISIONMONTH' ),
	'revisionyear'          => array( '1', 'SENATAR-REVIŻJONI', 'REVISIONYEAR' ),
	'revisiontimestamp'     => array( '1', 'TIMBRUTAR-REVIŻJONI', 'REVISIONTIMESTAMP' ),
	'fullurl'               => array( '0', 'URLSĦIĦA:', 'FULLURL:' ),
	'fullurle'              => array( '0', 'URLSĦIĦAE:', 'FULLURLE:' ),
	'lcfirst'               => array( '0', 'IBDAKŻ:', 'LCFIRST:' ),
	'ucfirst'               => array( '0', 'IBDAKK:', 'UCFIRST:' ),
	'lc'                    => array( '0', 'KŻ:', 'LC:' ),
	'uc'                    => array( '0', 'KK:', 'UC:' ),
	'displaytitle'          => array( '1', 'URITITLU', 'DISPLAYTITLE' ),
	'newsectionlink'        => array( '1', '__ĦOLQASEZZJONIĠDIDA__', '__NEWSECTIONLINK__' ),
	'nonewsectionlink'      => array( '1', '__EBDAĦOLQASEZZJONIĠDIDA__', '__NONEWSECTIONLINK__' ),
	'currentversion'        => array( '1', 'VERŻJONIKURRENTI', 'CURRENTVERSION' ),
	'urlencode'             => array( '0', 'URLKODIĊI:', 'URLENCODE:' ),
	'anchorencode'          => array( '0', 'ANKRAKODIĊI', 'ANCHORENCODE' ),
	'currenttimestamp'      => array( '1', 'TIMBRUTAL-ĦINKURRENTI', 'CURRENTTIMESTAMP' ),
	'localtimestamp'        => array( '1', 'TIMBRUTAL-ĦINLOKALI', 'LOCALTIMESTAMP' ),
	'directionmark'         => array( '1', 'MARKATAD-DIREZZJONI', 'MARKADIRE', 'DIRECTIONMARK', 'DIRMARK' ),
	'language'              => array( '0', '#LINGWA:', '#LANGUAGE:' ),
	'contentlanguage'       => array( '1', 'LINGWATAL-KONTENUT', 'LINGKONTENUT', 'CONTENTLANGUAGE', 'CONTENTLANG' ),
	'pagesinnamespace'      => array( '1', 'PAĠNIFL-ISPAZJUTAL-ISEM:', 'PAĠNISI:', 'PAGESINNAMESPACE:', 'PAGESINNS:' ),
	'numberofadmins'        => array( '1', 'NUMRUTA\'AMMIN', 'NUMBEROFADMINS' ),
	'padleft'               => array( '0', 'PADXELLUG', 'PADLEFT' ),
	'padright'              => array( '0', 'PADLEMIN', 'PADRIGHT' ),
	'special'               => array( '0', 'speċjali', 'special' ),
	'defaultsort'           => array( '1', 'DEFAULTSORTJA:', 'DEFAULTSORTJAĊAVETTA:', 'DEFAULTKATEGORIJISORTJA:', 'DEFAULTSORT:', 'DEFAULTSORTKEY:', 'DEFAULTCATEGORYSORT:' ),
	'filepath'              => array( '0', 'DESTINAZZJONITAL-FAJL:', 'FILEPATH:' ),
	'tag'                   => array( '0', 'tabella', 'tag' ),
	'hiddencat'             => array( '1', '__KATMOĦBIJA__', '__HIDDENCAT__' ),
	'pagesincategory'       => array( '1', 'PAĠNIFIL-KATEGORIJA', 'PAĠNIFILK', 'PAGESINCATEGORY', 'PAGESINCAT' ),
	'pagesize'              => array( '1', 'DAQSTAL-PAĠNI', 'PAGESIZE' ),
	'index'                 => array( '1', '__INDIĊI__', '__INDEX__' ),
	'noindex'               => array( '1', '__EBDAINDIĊI__', '__NOINDEX__' ),
	'numberingroup'         => array( '1', 'NUMRUFIL-GRUPP', 'NUMFIL-GRUPP', 'NUMBERINGROUP', 'NUMINGROUP' ),
	'staticredirect'        => array( '1', '__RIINDIRIZZSTATIKU__', '__STATICREDIRECT__' ),
	'protectionlevel'       => array( '1', 'LIVELLITA\'PROTEZZJONI', 'PROTECTIONLEVEL' ),
	'formatdate'            => array( '0', 'formatdata', 'dataformat', 'formatdate', 'dateformat' ),
);

$messages = array(
# User preference toggles
'tog-underline'               => 'Ħoloq sottolinjati:',
'tog-highlightbroken'         => 'Uri ħoloq miksura <a href="" class="new">hekk</a> (alternattiva: hekk<a href="" class="internal">?</a>)',
'tog-justify'                 => 'Iġġustifika l-paragrafi',
'tog-hideminor'               => 'Aħbi l-modifiki minuri fit-tibdil riċenti',
'tog-hidepatrolled'           => 'Aħbi l-modifiki verifikati fit-tibdil riċenti',
'tog-newpageshidepatrolled'   => 'Aħbi l-paġni verifikati mil-lista tal-paġni l-ġodda',
'tog-extendwatchlist'         => "Espandi l-lista ta' osservazzjoni biex turi t-tibdil kollu, u mhux biss dak riċenti",
'tog-usenewrc'                => 'Uża t-tibdil riċenti avvanzat (bżonn tal-JavaScript)',
'tog-numberheadings'          => 'Numerazzjoni awtomatika tat-titli tas-sezzjonijiet',
'tog-showtoolbar'             => 'Uri l-kolonna tal-għodda għall-immodifikar (bżonn tal-JavaScript)',
'tog-editondblclick'          => "Immodifika l-paġni permezz ta' klikk doppju (bżonn tal-JavaScript)",
'tog-editsection'             => 'L-immodifikar tas-sezzjonijiet permezz tal-ħolqa [editja]',
'tog-editsectiononrightclick' => "L-immodifikar ta' sezzjonijiet bi klikk lemini fuq it-titli tas-sezzjonijiet (bżonn tal-JavaScript)",
'tog-showtoc'                 => "Uri l-werrej (għal paġni b'iktar minn 3 sezzjonijiet)",
'tog-rememberpassword'        => "Ftakar il-login tiegħi fuq dan il-kompjuter (għal massimu ta' {{PLURAL:$1|ġurnata|$1 ġurnata}})",
'tog-watchcreations'          => "Żid il-paġni li noħloq fil-lista ta' osservazzjoni tiegħi",
'tog-watchdefault'            => "Żid il-paġni li nimmodifika fil-lista ta' osservazzjoni personali",
'tog-watchmoves'              => "Żid il-paġni li mmexxi fil-lista ta' osservazzjoni tiegħi",
'tog-watchdeletion'           => "Żid il-paġni li nħassar mal-lista ta' osservazzjoni tiegħi",
'tog-minordefault'            => 'Immarka awtomatikament kull modifika bħala waħda minuri',
'tog-previewontop'            => 'Uri dehra proviżorja tal-paġna fuq il-kaxxa tal-immodifikar',
'tog-previewonfirst'          => 'Uri dehra proviżorja mal-ewwel modifika',
'tog-nocache'                 => 'Itfi l-cache għall-paġni',
'tog-enotifwatchlistpages'    => "Ibgħatli ittra-e kull meta sseħħ modifika fuq paġna li tinsab fil-lista ta' osservazzjoni tiegħi",
'tog-enotifusertalkpages'     => "Ibgħatli ittra-e kull meta l-paġna ta' diskussjoni tiegħi tiġi modifikata",
'tog-enotifminoredits'        => 'Ibgħatli wkoll ittra-e għall-modifiki minuri fuq paġni',
'tog-enotifrevealaddr'        => "Ikxef l-indirizz tal-posta elettronika tiegħi fil-messaġġi ta' avviż",
'tog-shownumberswatching'     => "Uri n-numru ta' utenti li qegħdin isegwu din il-paġna",
'tog-oldsig'                  => 'Dehra proviżorja tal-firma attwali:',
'tog-fancysig'                => 'Interpreta l-firma bħala test tal-wiki (mingħajr ħolqa awtomatika)',
'tog-externaleditor'          => 'Uża awtomatikament modifikatur estern għat-testi (għal esperti biss, għandha bżonn preferenzi speċjali fuq il-komputer tiegħek)',
'tog-externaldiff'            => 'Uża awtomatikament diff estern (għal esperti biss, għandhek bżonn preferenzi speċjali fuq il-komputer tiegħek)',
'tog-showjumplinks'           => 'Attiva l-ħoloq aċċessibbli "aqbeż għal"',
'tog-uselivepreview'          => "Attiva l-funzjoni ''Live preview'' (bżonn tal-JavaScript; sperimentali)",
'tog-forceeditsummary'        => 'Nebbaħni meta ndaħħal taqsira tal-modifika vojta',
'tog-watchlisthideown'        => "Aħbi l-modifiki tiegħi mil-lista ta' osservazzjoni",
'tog-watchlisthidebots'       => "Aħbi l-modifiki tal-bots mil-lista ta' osservazzjoni",
'tog-watchlisthideminor'      => "Aħbi modifiki żgħar mil-lista t'osservazzjoni",
'tog-watchlisthideliu'        => "Aħbi modifiki minn utenti illogjati mil-lista ta' osservazzjoni tiegħi",
'tog-watchlisthideanons'      => "Aħbi modifiki minn utenti anonimi mil-lista ta' osservazzjoni",
'tog-nolangconversion'        => 'Disattiva konversazzjonijiet fost varjanti lingwistiċi',
'tog-ccmeonemails'            => "Ibgħatli kopji tal-ittri-e li nibgħat 'l utenti oħrajn",
'tog-diffonly'                => 'Turiex kontenut tal-paġni wara li tkun għamilt paragun bejn il-verżjonijiet',
'tog-showhiddencats'          => 'Uri kategoriji moħbija',
'tog-norollbackdiff'          => "Turix il-paragun bejn il-verżjonijiet wara li tkun effettwajt ir-''rollback''",

'underline-always'  => 'Dejjem',
'underline-never'   => 'Qatt',
'underline-default' => 'Żomm l-issettjar tal-browser',

# Font style option in Special:Preferences
'editfont-default' => 'Żomm l-issettjar tal-browser',

# Dates
'sunday'        => 'Il-Ħadd',
'monday'        => 'It-Tnejn',
'tuesday'       => 'It-Tlieta',
'wednesday'     => 'L-Erbgħa',
'thursday'      => 'Il-Ħamis',
'friday'        => 'Il-Ġimgħa',
'saturday'      => 'Is-Sibt',
'sun'           => 'Ħdd',
'mon'           => 'Tnn',
'tue'           => 'Tlt',
'wed'           => 'Erb',
'thu'           => 'Ħam',
'fri'           => 'Ġim',
'sat'           => 'Sbt',
'january'       => 'Jannar',
'february'      => 'Frar',
'march'         => 'Marzu',
'april'         => 'April',
'may_long'      => 'Mejju',
'june'          => 'Ġunju',
'july'          => 'Lulju',
'august'        => 'Awwissu',
'september'     => 'Settembru',
'october'       => 'Ottubru',
'november'      => 'Novembru',
'december'      => 'Diċembru',
'january-gen'   => 'Jannar',
'february-gen'  => 'Frar',
'march-gen'     => 'Marzu',
'april-gen'     => 'April',
'may-gen'       => 'Mejju',
'june-gen'      => 'Ġunju',
'july-gen'      => 'Lulju',
'august-gen'    => 'Awwissu',
'september-gen' => 'Settembru',
'october-gen'   => 'Ottubru',
'november-gen'  => 'Novembru',
'december-gen'  => 'Diċembru',
'jan'           => 'Jan',
'feb'           => 'Fra',
'mar'           => 'Mar',
'apr'           => 'Apr',
'may'           => 'Mej',
'jun'           => 'Ġun',
'jul'           => 'Lul',
'aug'           => 'Awi',
'sep'           => 'Set',
'oct'           => 'Ott',
'nov'           => 'Nov',
'dec'           => 'Diċ',

# Categories related messages
'pagecategories'                 => '{{PLURAL:$1|Kategorija|Kategoriji}}',
'category_header'                => 'Paġni fil-kategorija "$1"',
'subcategories'                  => 'Sottokategoriji',
'category-media-header'          => 'Fajls fil-kategorija "$1"',
'category-empty'                 => "''Bħalissa, din il-kategorija m'għandiex paġni jew fajls multimedjali.''",
'hidden-categories'              => '{{PLURAL:$1|Kategorija moħbija|Kategoriji moħbija}}',
'hidden-category-category'       => 'Kategoriji moħbija',
'category-subcat-count'          => "{{PLURAL:$2|Din il-kategorija għandha biss din is-sottokategorija segwenti.|Din il-kategorija għandha {{PLURAL:$1|s-sottokategorija indikata|$1 sottokategoriji}}, minn total ta' $2.}}",
'category-subcat-count-limited'  => 'Din il-kategorija għandha {{PLURAL:$1|sottokategorija|$1 sottokategoriji}}.',
'category-article-count'         => "{{PLURAL:$2|Din il-kategorija għandha biss din il-paġna, kif indikat.| Din il-kategorija għandha {{PLURAL:$1|l-paġna indikata|l-$1 paġni indikati}}, minn total ta' $2.}}",
'category-article-count-limited' => 'Din il-kategorija għanda {{PLURAL:$1|l-paġna indikata|l-$1 paġni indikati}}.',
'category-file-count'            => "{{PLURAL:$2|Din il-kategorija għandha biss dan il-fajl, kif indikat|Din il-kategorija għandha {{PLURAL:$1|fajl, kif indikat|$1 fajls, indikati}}, minn total ta' $2.}}",
'category-file-count-limited'    => "Il-{{PLURAL:$1|fajl indikat huwa|$1 fajls indikati huma}} f'din il-kategorija.",
'listingcontinuesabbrev'         => 'kompli',
'index-category'                 => 'Paġni indiċjati',

'linkprefix'        => '/^(.*?)([a-żA-Ż\\x80-\\xff]+)$/sD',
'mainpagetext'      => "'''MediaWiki ġie installat b'suċċess.'''",
'mainpagedocfooter' => "Ikkonsulta l-[http://meta.wikimedia.org/wiki/Help:Contents Gwida għall-utenti] sabiex tikseb iktar informazzjoni dwar kif tuża' s-softwer tal-wiki.

== Biex tibda ==
* [http://www.mediawiki.org/wiki/Manual:Configuration_settings Lista ta' preferenzi għall-konfigurazzjoni]
* [http://www.mediawiki.org/wiki/Manual:FAQ Mistoqsijiet rikorrenti fuq il-MediaWiki]
* [https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce Il-lista tal-posta tħabbar 'l MediaWiki]",

'about'         => 'Dwar',
'article'       => 'artiklu',
'newwindow'     => "(tinfetaħ f'tieqa ġdida)",
'cancel'        => 'Annulla',
'moredotdotdot' => 'Aktar...',
'mypage'        => 'Il-paġna tiegħi',
'mytalk'        => 'id-diskussjonijiet tiegħi',
'anontalk'      => 'Diskussjoni għal dan l-IP',
'navigation'    => 'Navigazzjoni',
'and'           => '&#32;u',

# Cologne Blue skin
'qbfind'         => 'Fittex',
'qbbrowse'       => 'Qalleb',
'qbedit'         => 'Immodifika',
'qbpageoptions'  => 'Din il-paġna',
'qbpageinfo'     => 'Kuntest',
'qbmyoptions'    => 'Il-paġni tiegħi',
'qbspecialpages' => 'Paġni speċjali',
'faq'            => 'Mistoqsijiet komuni',
'faqpage'        => 'Project:FAQ',

# Vector skin
'vector-action-addsection'   => 'Żid diskussjoni',
'vector-action-delete'       => 'Ħassar',
'vector-action-move'         => 'Mexxi',
'vector-action-protect'      => 'Ipproteġi',
'vector-action-undelete'     => 'Irkupra',
'vector-action-unprotect'    => 'Żblokka',
'vector-namespace-category'  => 'Kategorija',
'vector-namespace-help'      => 'Għajnuna',
'vector-namespace-image'     => 'Fajl',
'vector-namespace-main'      => 'Paġna',
'vector-namespace-media'     => 'Fajl multimedjali',
'vector-namespace-mediawiki' => 'Messaġġ',
'vector-namespace-project'   => "Paġna ta' proġett",
'vector-namespace-special'   => 'Paġna speċjali',
'vector-namespace-talk'      => 'Diskussjoni',
'vector-namespace-template'  => 'Mudell',
'vector-namespace-user'      => 'Utent',
'vector-view-create'         => 'Oħloq',
'vector-view-edit'           => 'Editja',
'vector-view-history'        => 'Uri l-kronoloġija',
'vector-view-view'           => 'Aqra',
'vector-view-viewsource'     => 'Ara s-sors',
'actions'                    => 'Azzjonijiet',
'namespaces'                 => 'Spazji tal-isem',
'variants'                   => 'Varjanti',

'errorpagetitle'    => 'Problema',
'returnto'          => "Erġa' lura lejn $1.",
'tagline'           => 'Minn {{SITENAME}}',
'help'              => 'Għajnuna',
'search'            => 'Fittex',
'searchbutton'      => 'Fittex',
'go'                => 'Mur',
'searcharticle'     => 'Mur',
'history'           => 'Verżjoni preċedenti',
'history_short'     => 'Kronoloġija',
'updatedmarker'     => 'ġiet modifikata mill-aħħar żjara',
'info_short'        => 'Informazzjoni',
'printableversion'  => 'Verżjoni għall-ipprintjar',
'permalink'         => 'Ħolqa permanenti',
'print'             => 'Ipprintja',
'edit'              => 'Editja',
'create'            => 'Oħloq',
'editthispage'      => 'Immodifika din il-paġna',
'create-this-page'  => 'Oħloq din il-paġna',
'delete'            => 'Ħassar',
'deletethispage'    => 'Ħassar din il-paġna',
'undelete_short'    => 'Irkupra {{PLURAL:$1|modifika waħda|$1 modifiki}}',
'protect'           => 'Ipproteġi',
'protect_change'    => 'biddel',
'protectthispage'   => 'Ipproteġi din il-paġna',
'unprotect'         => 'Tibqax tipproteġi',
'unprotectthispage' => 'Tibqax tipproteġi din il-paġna',
'newpage'           => 'Paġna ġdida',
'talkpage'          => "Paġna ta' diskussjoni",
'talkpagelinktext'  => 'Diskussjoni',
'specialpage'       => 'Paġna speċjali',
'personaltools'     => 'Għodda personali',
'postcomment'       => 'Sezzjoni ġdida',
'articlepage'       => 'Ara l-artiklu',
'talk'              => 'Diskussjoni',
'views'             => 'Veduti',
'toolbox'           => 'Għodda',
'userpage'          => 'Ara l-paġna tal-utent',
'projectpage'       => 'Ara l-paġna tal-proġett',
'imagepage'         => 'Ara l-paġna tal-fajl',
'mediawikipage'     => 'Ara l-paġna tal-messaġġ',
'templatepage'      => 'Ara l-mudell',
'viewhelppage'      => 'Ara l-paġna tal-għajnuna',
'categorypage'      => 'Ara l-kategorija',
'viewtalkpage'      => 'Ara d-diskussjoni',
'otherlanguages'    => "F'lingwi oħrajn",
'redirectedfrom'    => '(Riindirizzat minn $1)',
'redirectpagesub'   => "Paġna ta' riindirizzament",
'lastmodifiedat'    => 'L-aħħar modifika fuq il-paġna: $2, $1.',
'viewcount'         => 'Din il-paġna ġiet aċċessata {{PLURAL:$1|darba|$1 darba}}.',
'protectedpage'     => 'Paġna protetta',
'jumpto'            => 'Aqbeż għal:',
'jumptonavigation'  => 'navigazzjoni',
'jumptosearch'      => 'fittex',
'view-pool-error'   => "Jiddispjaċina, imma fil-mument is-servers jinsabu mgħobbija ż-żejjed.
Ħafna utenti qegħdin jippruvaw jaraw din il-paġna.
Jekk jogħġbok stenna ftit qabel ma terġa' tipprova tuża' din il-paġna.

$1",

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'Dwar {{SITENAME}}',
'aboutpage'            => 'Project:Dwar',
'copyright'            => 'Kontenut aċċessibli taħt $1.',
'copyrightpage'        => '{{ns:project}}:Copyright',
'currentevents'        => 'Ġrajjiet kurrenti',
'currentevents-url'    => 'Project:Ġrajjiet kurrenti',
'disclaimers'          => 'Ċaħdiet',
'disclaimerpage'       => 'Project:Ċaħda ġenerali',
'edithelp'             => 'Gwida',
'edithelppage'         => 'Help:Kif Timmodifika',
'helppage'             => 'Help:Kontenut',
'mainpage'             => 'Il-Paġna prinċipali',
'mainpage-description' => 'Il-Paġna prinċipali',
'policy-url'           => 'Project:Politika',
'portal'               => 'Portal tal-komunità',
'portal-url'           => 'Project:Portal tal-komunità',
'privacy'              => 'Politika dwar il-privatezza',
'privacypage'          => 'Project:Politika dwar il-privatezza',

'badaccess'        => 'Problema bil-permess',
'badaccess-group0' => "M'għandekx permess twettaq din l-azzjoni.",
'badaccess-groups' => "L-azzjoni li rrikjedejt hija riservata għall-utenti membri {{PLURAL:$2|tal-grupp|ta' wieħed minn dawn il-gruppi}}: $1.",

'versionrequired'     => "Hija meħtieġa l-verżjoni $1 ta' MedjaWiki",
'versionrequiredtext' => "Hija meħtieġa l-verżjoni $1 ta' MedjaWiki biex tuża din il-paġna. Ara [[Special:Version|paġna tal-verżjoni]].",

'ok'                      => 'OK',
'retrievedfrom'           => 'Miġjub minn "$1"',
'youhavenewmessages'      => 'Għandek $1 ($2).',
'newmessageslink'         => 'messaġġi ġodda',
'newmessagesdifflink'     => 'l-aħħar bidla',
'youhavenewmessagesmulti' => 'Għandek messaġġi ġodda fuq $1',
'editsection'             => 'editja',
'editold'                 => 'editja',
'viewsourceold'           => 'ara s-sors',
'editlink'                => 'editja',
'viewsourcelink'          => 'ara s-sors',
'editsectionhint'         => 'Immodifika s-sezzjoni: $1',
'toc'                     => 'Kontenut',
'showtoc'                 => 'uri',
'hidetoc'                 => 'aħbi',
'thisisdeleted'           => 'Uri jew ġib lura $1?',
'viewdeleted'             => 'Ara $1?',
'restorelink'             => '{{PLURAL:$1|waħda mill-modifiki mħassra|$1 modifiki mħassra}}',
'feedlinks'               => 'Feed:',
'feed-invalid'            => "Tip ta' feed ta' l-abbonar invalidu.",
'feed-unavailable'        => 'Feeds mhumiex disponibbli',
'site-rss-feed'           => "Feed RSS ta' $1",
'site-atom-feed'          => "Feed Atom ta' $1",
'page-rss-feed'           => 'Feed RSS għal "$1"',
'page-atom-feed'          => 'Feed Atom għal "$1"',
'red-link-title'          => '$1 (il-paġna ma teżistix)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Paġna',
'nstab-user'      => 'Paġna tal-utent',
'nstab-media'     => 'Paġna tal-medja',
'nstab-special'   => 'Paġna speċjali',
'nstab-project'   => 'Paġna tal-proġett',
'nstab-image'     => 'Fajl',
'nstab-mediawiki' => 'Messaġġ',
'nstab-template'  => 'Mudell',
'nstab-help'      => 'Paġna tal-għajnuna',
'nstab-category'  => 'Kategorija',

# Main script and global functions
'nosuchaction'      => 'Ma teżistix din l-azzjoni',
'nosuchactiontext'  => "L-azzjoni speċifikata mill-URL mhijiex valida.
Jista' jkun li tkun ktibt ħażin il-URL, jew ġejt imwassal għal ħolqa ħażina.
Dan jista' jindika wkoll bug fis-softwer użat fil-{{SITENAME}}.",
'nosuchspecialpage' => 'L-Ebda paġna speċjali',
'nospecialpagetext' => "<strong>Inti għamilt rikjesta għal paġna speċjali invalida.</strong>

Lista ta' paġni speċjali validi tinsab hawn [[Special:SpecialPages|{{int:specialpages}}]].",

# General errors
'error'                => 'Problema',
'databaseerror'        => 'Problema fid-database',
'dberrortext'          => 'Kien hemm żball fis-sintassi ta\' rikjesta tad-databażi.
Dan jista\' jindika li hemm problema fis-softwer.
L-aħħar attentat ta\' rikjesta tad-databażi kienet:
<blockquote><tt>$1</tt></blockquote>
mill-funzjoni ta\' "<tt>$2</tt>".
Id-databażi tat problema ta\' "<tt>$3: $4</tt>".',
'dberrortextcl'        => 'Kien hemm żball fis-sintassi ta\' rikjesta tad-databażi. 
L-aħħar attentat ta\' rikjesta tad-databażi kienet:
"$1"
mill-funzjoni "$2".
Id-databażi tat il-problema segwenti "$3: $4"',
'laggedslavemode'      => "Twissija: Il-Paġna jista' ma jkollhiex l-affarijiet aġġornati.",
'readonly'             => 'Database magħluq',
'enterlockreason'      => "Daħħal raġuni għala qiegħed tagħlqu, inkludi l-istima ta' meta l-għeluq se tieħu effett",
'readonlytext'         => 'Id-Database bħalissa magħluq għal daħla ġdid u modifiki oħrajn, probabilment għal manteniment tar-rutina tad-database, wara terġa tiġi għan-normali.

L-amministratur li għalqu offra din l-ispjegazzjoni: $1',
'missing-article'      => 'Id-databażi ma sabitx it-test tal-paġna li suppost sab, taħt l-isem ta\' "$1" $2.

Is-soltu dan jiġri meta l-paġna terġa\' tiġi msejjħa, billi tibda mill-kronoloġija jew mill-konfront bejn ir-reviżjonijiet, link għal paġna mħassra, ma\' konfront bejn reviżjonijiet ineżistenti jew konfront bejn reviżjonijiet imnaddfa mill-kronoloġija.

Jekk din mhix ir-raġuni, wisq probabli sibt problema fis-software. Jekk jogħġbok irraporta dan lil [[Special:ListUsers/sysop|amministratur]], u agħmel nota tal-URL.',
'missingarticle-rev'   => '(reviżjoni#: $1)',
'missingarticle-diff'  => '(Diff: $1, $2)',
'readonly_lag'         => "Id-databażi ġiet awtomatikament magħluqa sakemm id-databażi inferjuri tas-server jilħqu ma' dak superjuri",
'internalerror'        => 'Problema interna',
'internalerror_info'   => 'Problema interna: $1',
'filecopyerror'        => 'Ma setax jiġi kkuppjat il-fajl "$1" f\' "$2".',
'filerenameerror'      => 'Il-fajl "$1" ma setax jiġi msemmi mill-ġdid għal "$2".',
'filedeleteerror'      => 'Il-fajl "$1" ma setax jiġi mħassar.',
'directorycreateerror' => 'Id-direttorju "$1" ma setax jiġi maħluq.',
'filenotfound'         => 'Il-fajl "$1" ma nstabx.',
'fileexistserror'      => 'Il-fajl "$1" ma setax jiġi miktub: fajl diġà jeżisti',
'unexpected'           => 'Valur mhux mistenni: "$1"="$2".',
'formerror'            => 'Problema: il-formula ma setgħatx tiġi proċessata',
'badarticleerror'      => 'Din l-azzjoni ma setgħetx isseħħ fuq din il-paġna.',
'cannotdelete'         => 'Il-paġna jew il-fajl "$1" ma jistax jiġi mħassar.
Jista\' jkun li diġà ġie mħassar minn xi ħaddieħor.',
'badtitle'             => 'Titlu ħażin',
'badtitletext'         => "It-titlu tal-paġna rikjesta huwa invalidu, vojt, jew ġej minn żball fil-ħolqa bejn siti wiki differenti jew verżjonijiet ta' lingwi differenti tal-istess sit. Jista' wkoll ikollu wieħed jew aktar karattri li ma jistgħux jintużaw għat-titli.",
'perfcached'           => "L-informazzjoni li jmiss huwa kopja ''cache'' u jista' ma jkunx aġġornat.",
'perfcachedts'         => "Id-dati segwenti huma estratt ta' kopja cache tad-database. L-aħħar aġġornament: $1.",
'querypage-no-updates' => 'Aġġornamenti għal din il-paġna huma temporalment sospesi. L-Informazzjoni hawnhekk preżentament mhux qiegħed jiġi aġġornat.',
'wrong_wfQuery_params' => 'Parametri skoretti għal wfQuery()<br />
Funżjoni: $1<br />
Rikjesta: $2',
'viewsource'           => 'Ara s-sors',
'viewsourcefor'        => 'għal $1',
'actionthrottled'      => 'Azzjoni miżmuma',
'actionthrottledtext'  => "Bħala miżura għal kontra l-ispam, inti limitat li tagħmel din l-azzjoni għal ħafna drabi f'spazju ta' ħin żgħir, u inti qbiżt dan il-limitu.
Jekk jogħġbok erġa' prova fi ftit minuti oħra.",
'protectedpagetext'    => "Din il-paġna ġiet protetta sabiex twaqqaf kull tip ta' modifika.",
'viewsourcetext'       => "Tista' tara u tikkopja s-sors ta' din il-paġna:",
'protectedinterface'   => 'Din il-paġna għanda element li tagħmel parti mill-interfaċċa tal-utent tas-software, u għaldaqstant ġiet protetta sabiex ma jkunx hemm abbuż.',
'editinginterface'     => "'''Avviż:''' Qiegħed tagħmel modifiki lejn paġna li qegħdha tintuża biex tipprovdi interfaċċa għall-messaġġi tas-software. Kull modifika f'din il-paġna se taffetwa l-apparenza tal-faċċata tal-utenti kollha. Għat-traduzzjonijiet, ikkunsidra l-possibilità li tuża'  [http://translatewiki.net/wiki/Main_Page?setlang=mt translatewiki.net], il-proġett MediaWiki għal-lokalizzazzjoni.",
'sqlhidden'            => '(SQL rikjesta moħbija)',
'cascadeprotected'     => 'Din il-paġna ġiet protetta mill-modifiki, minħabba li tinkludi {{PLURAL:$1|paġni, li huwa|paġni, li huma}} protetti bil-preferenza tal-"kaskata" mixewla:
$2',
'namespaceprotected'   => "Inti m'għandhekx il-permess li timodifika paġni fin-''namespace'' '''$1''.",
'customcssjsprotected' => "M'għandekx permess timmodifika din il-paġna, peress li tinkludi l-preferenzi personali ta' utent ieħor.",
'ns-specialprotected'  => 'Il-paġni speċjali ma jistgħux jiġu mmodifikati.',
'titleprotected'       => "Dan it-titlu ġie protett mill-ħolqien minn [[User:$1|$1]].
Ir-raġuni li ġiet mogħtija kienet ''$2''.",

# Virus scanner
'virus-badscanner'     => "Problema fil-konfigurazzjoni: antivirus mhux magħruf: ''$1''",
'virus-scanfailed'     => 'Tfittxija falliet (kodiċi $1)',
'virus-unknownscanner' => 'antivirus mhux magħruf:',

# Login and logout pages
'logouttext'                 => "'''Bħalissa tinsab barra mill-kont tiegħek.'''

Tista' tkompli tuża' {{SITENAME}} bħala utent anonimu, jew tista' terġa [[Special:UserLogin|tidħol]] bħala l-istess utent jew wieħed differenti.
Kun af li ċerti paġni jistgħu jkomplu jidhru bħallikieku l-illogjar 'l barra mill-kont qatt ma seħħ, sakemm ma tħassarx il-cache tal-browser.",
'welcomecreation'            => "== Merħba, $1! ==
Il-kont tiegħek ġie maħluq.<br />
Tinsiex tippersonalizza l-[[Special:Preferences|preferenzi]] ta' {{SITENAME}}.",
'yourname'                   => 'Isem tal-utent:',
'yourpassword'               => 'Password:',
'yourpasswordagain'          => "Erġa' ikteb il-password:",
'remembermypassword'         => "Ftakar il-login tiegħi fuq dan il-kompjuter (għal massimu ta' {{PLURAL:$1|ġurnata|$1 ġurnata}})",
'yourdomainname'             => 'Id-dominju tiegħek:',
'externaldberror'            => "Kien hemm problema esterna ta' awtentiċitá jew m'għandhekx permess neċċessarju sabiex tagħmel aġġornamenti fuq l-aċċess estern.",
'login'                      => 'Idħol',
'nav-login-createaccount'    => 'Idħol / Oħloq kont',
'loginprompt'                => "Irid ikollok il-cookies mixgħula biex tkun tista' tidħol fuq {{SITENAME}}.",
'userlogin'                  => 'Idħol jew oħloq kont ġdid',
'userloginnocreate'          => 'Idħol',
'logout'                     => 'Oħroġ',
'userlogout'                 => 'oħroġ',
'notloggedin'                => 'Għadek ma dħaltx ġewwa',
'nologin'                    => "Għad m'għandekx kont? '''$1'''.",
'nologinlink'                => 'Oħloq kont',
'createaccount'              => 'Oħloq kont',
'gotaccount'                 => "Diġa għandhek kont? '''$1'''.",
'gotaccountlink'             => 'Idħol',
'createaccountmail'          => 'bil-posta elettronika',
'badretype'                  => 'Il-passwords li daħħalt ma jaqblux.',
'userexists'                 => 'L-isem tal-utent li daħħalt huwa diġà meħud. Jekk jogħġbok, agħżel isem differenti.',
'loginerror'                 => 'Problemi fil-login',
'createaccounterror'         => 'Il-kont ma jistax jinħoloq: $1',
'nocookiesnew'               => "Il-Kont tal-utent għal l-aċċess ġie maħluq, però ma kienx possibli li tagħmel aċċess għal {{SITENAME}} għax il-''cookies'' huma disattivati. Erġa' prova l-aċċess bl-isem tal-utent u l-password wara li tkun attivajt il-''cookies'' tal-''browser''.",
'nocookieslogin'             => "L-aċċess għal {{SITENAME}} jagħmel użu minn ''cookies'', li bħalissa huma disattivati. Jekk jogħġbok erġa' prova idħol wara li tkun attivajt il-''cookies'' fil-browser.",
'noname'                     => "Inti ma speċifikajtx isem ta' utent validu.",
'loginsuccesstitle'          => "Dħalt b'suċċess",
'loginsuccess'               => "'''Irnexxielek taqbad mas-server ta' {{SITENAME}} bl-isem tal-utent \"\$1\".'''",
'nosuchuser'                 => 'M\'hemm l-ebda utent bl-isem ta\' "$1".<br />
L-ismijiet tal-utenti huma sensittivi fuq kif jinkitbu.<br />
Jekk jogħġbok kun żġur li ktibtu sew, jew minflok [[Special:UserLogin/signup|oħloq kont ġdid]].',
'nosuchusershort'            => 'M\'hemm l-ebda utent bl-isem "<nowiki>$1</nowiki>".
Agħmel żġur li ktibta sew.',
'nouserspecified'            => 'Trid tispeċifika isem tal-utent.',
'login-userblocked'          => 'Dan l-utent huwa imblukkat. Mhuwiex possibbli li jsir il-login.',
'wrongpassword'              => "Il-password li daħħalt mhijiex tajba. 
Jekk jogħġbok, erġa' pprova.",
'wrongpasswordempty'         => "Ma ddaħlet l-ebda password. 
Jekk jogħġbok, erġa' pprova.",
'passwordtooshort'           => 'Il-password trid tkun mill-inqas {{PLURAL:$1|karattru|$1 karattri}} twila u differenti mill-isem tal-utent.',
'password-name-match'        => 'Il-password trid tkun differenti mill-isem tal-utent tiegħek.',
'mailmypassword'             => 'Ibgħatli password ġdida',
'passwordremindertitle'      => 'Password temporanju ġdid għal {{SITENAME}}',
'passwordremindertext'       => 'Xi ħadd (probabbilment int, mill-indirizz tal-IP $1) għamel rikjesta għal password ġdida għal {{SITENAME}} ($4).<br />
Inħolqot password temporanja għall-utent "$2" u din hi "$3".
Huwa opportun li inti tidħol issa u tbiddel immedjatament il-password tiegħek.<br />
Din il-password il-ġdida se tiskadi fi żmien {{PLURAL:$5|ġurnata|$5 ijiem}}.

Jekk xi ħadd ieħor għamel din ir-rikjesta jew jekk int ftakart il-password tiegħek u issa ma tridx tbiddilha, int tista\' ma tagħtix każ dan il-messaġġ u tkompli bl-użu tal-password l-antika.',
'noemail'                    => 'M\'hemm l-ebda indirizz ta\' posta elettronika għall-utent "$1".',
'noemailcreate'              => 'Huwa neċessarju li tipprovdi indirizz elettroniku validu',
'passwordsent'               => 'Il-password il-ġdida ntbagħtet fl-indirizz tal-posta elettronika ta\' "$1".
Jekk jogħġbok, għamel aċċess wara li tasallek.',
'blocked-mailpassword'       => 'L-indirizz tal-IP tiegħek huwa bblokkjat u miżmum milli jwettaq modifiki. Għaldaqstant, mhuwiex possibli għalik li tuża l-funzjoni sabiex iġġib lura l-password, u dan sabiex ma jkunx hemm abbużi.',
'eauthentsent'               => "Intbagħat messaġġ ta' konferma b'permezz tal-posta elettronika lejn l-indirizz indikat.<br />
Qabel xi posta elettronika oħra tiġi mibgħuta fuq il-kont, trid qabel xejn tesegwixxi l-istruzzjonijiet kif inhuma indikati, sabiex tikkonferma li l-kont huwa tassew tiegħek.",
'throttled-mailpassword'     => "Posta elettronika sabiex tfakrek il-password ġiet postjata, fl-aħħar {{PLURAL:$1|siegħa|$1 siegħat}}.
Sabiex jitnaqqas l-abbuż, waħda biss tista' tiġi postjata f'kull {{PLURAL:$1|siegħa|$1 siegħat}}.",
'mailerror'                  => 'Problema bil-postar tal-messaġġ: $1',
'acct_creation_throttle_hit' => "L-utenti ta' din il-wiki li jużaw l-indirizz IP tiegħek ħolqu {{PLURAL:$1|kont|$1 kontijiet}} fl-aħħar ġurnata, li hu n-numru massimu permess f'dan il-perjodu ta' żmien.
Bħala riżultat, il-viżitaturi li jużaw dan l-IP ma jistgħux għall-mument, joħoloqu aktar kontijiet.",
'emailauthenticated'         => 'L-indirizz tal-posta elettronika tiegħek ġiet konfermat nhar il-$2, fil-$3.',
'emailnotauthenticated'      => 'L-indirizz tal-posta elettronika tiegħek għadu ma ġiex konfermat. L-ebda posta elettronika mhi se tintbagħat għall-ebda minn dawn il-funzjonijiet elenkati hawn taħt.',
'noemailprefs'               => "Speċifika indirizz ta' posta elettronika sabiex dawn il-faċċilitajiet jaħdmu.",
'emailconfirmlink'           => 'Ikkonferma l-indirizz tal-posta elettronika tiegħek',
'invalidemailaddress'        => 'L-indirizz tal-posta elettronika ma jistax jiġi aċċettat għax jidher li għandu format ħażin.
Jekk jogħġbok daħħal indirizz validu jew inkella ħassru.',
'accountcreated'             => 'Il-kont inħoloq',
'accountcreatedtext'         => 'Inħoloq kont tal-utent għal $1.',
'createaccount-title'        => 'Ħolqien tal-kont għal {{SITENAME}}',
'createaccount-text'         => 'Xi ħadd ħoloq kont għall-indirizz tal-posta elettronika tiegħek fuq {{SITENAME}} ($4) bl-isem "$2", bil-password: "$3".
Huwa opportun li tidħol issa u tbiddel il-password tiegħek mill-ewwel.

Jekk trid tista\' ma tagħtix każ dan il-messaġġ, jekk dan il-kont ġie maħluq bi żball.',
'usernamehasherror'          => 'L-isem tal-utent ma jistax ikolu karattri hash',
'login-throttled'            => "Saru ħafna tentattivi riċenti fuq il-password ta' dan il-kont.
Jekk jogħġbok stenna qabel ma terġa' tipprova.",
'loginlanguagelabel'         => 'Lingwa: $1',
'suspicious-userlogout'      => "Ir-rikjesta tiegħek li toħroġ barra mill-kont tiegħek ġiet miċħuda minħabba li jidher li din intbagħtet minn browser li ma jaħdimx jew minn proxy ta' caching.",

# Password reset dialog
'resetpass'                 => 'Biddel il-password',
'resetpass_announce'        => "L-aċċess ġe effetwat permezz ta' kodiċi temporanju, li ntbagħat permezz tal-posta elettronika.
Biex tkompli l-aċċess tal-kont tiegħek huwa neċessarju li toħloq password ġdida hawnhekk:",
'resetpass_text'            => '<!-- Żied il-kliem hawnhekk -->',
'resetpass_header'          => 'Biddel il-password tal-kont',
'oldpassword'               => 'Password antika:',
'newpassword'               => 'Password ġdida:',
'retypenew'                 => "Erġa' ikteb il-password il-ġdida:",
'resetpass_submit'          => 'Issettja l-password u idħol fis-sit',
'resetpass_success'         => 'Il-password ġie modifikat. Aċċess fil-proċess...',
'resetpass_forbidden'       => 'Mhuwiex possibbli li timmodifika l-passwords',
'resetpass-no-info'         => 'Trid tkun effetwajt il-login qabel ma taċċessa direttament din il-paġna.',
'resetpass-submit-loggedin' => 'Biddel il-password',
'resetpass-submit-cancel'   => 'Annulla',
'resetpass-wrong-oldpass'   => "Password temporanja jew kurrenti invalida.
Jista' jkun li int diġà biddilt il-password, jew għamilt rikjesta għal password temporanja ġdida.",
'resetpass-temp-password'   => 'Password temporanja:',

# Edit page toolbar
'bold_sample'     => 'Tipa ħoxna',
'bold_tip'        => 'Tipa ħoxna',
'italic_sample'   => 'Tipa korsiva',
'italic_tip'      => 'Tipa korsiva',
'link_sample'     => 'Titlu tal-link',
'link_tip'        => 'Link intern',
'extlink_sample'  => 'http://www.example.com titlu tal-link',
'extlink_tip'     => 'Link estern (ftakar il-prefiss http://)',
'headline_sample' => "Kliem ta' l-ewwel vers",
'headline_tip'    => "L-ewwel vers ta' livell 2",
'math_sample'     => 'Daħħal formula hawnhekk',
'math_tip'        => 'Formula matematika (LaTeX)',
'nowiki_sample'   => 'Daħħal test mhux formatjat hawnhekk',
'nowiki_tip'      => 'Tagħtix każ il-formatjar tal-wiki',
'image_sample'    => 'Eżempju.jpg',
'image_tip'       => 'Fajl ingastat',
'media_sample'    => 'Eżempju.ogg',
'media_tip'       => 'Link tal-fajl',
'sig_tip'         => 'Il-Firma tiegħek u it-timbru tal-ħin',
'hr_tip'          => 'Linja mimduda (uża bil-qies)',

# Edit pages
'summary'                          => 'Taqsira:',
'subject'                          => 'Suġġett/Titlu:',
'minoredit'                        => "Din hija modifika ta' importanza minuri",
'watchthis'                        => 'Segwi din il-paġna',
'savearticle'                      => 'Salva l-paġna',
'preview'                          => 'Dehra proviżorja',
'showpreview'                      => 'Dehra proviżorja',
'showlivepreview'                  => "Funzjoni ''Live preview''",
'showdiff'                         => 'Uri t-tibdiliet',
'anoneditwarning'                  => "'''Twissija:''' Ma rnexxilekx tidħol. 
L-indirizz tal-IP tiegħek se jiġi reġistrat fil-kronoloġija tal-modifikar ta' din il-paġna.",
'anonpreviewwarning'               => "''Bħalissa mintix fil-kont tiegħek. Jekk issalva xi modifiki tiegħek, fil-kronoloġija tal-paġna se jiġi reġistrat l-indirizz IP tiegħek.''",
'missingsummary'                   => "'''Twissija:''' Ma pprovdejt l-ebda taqsira dwar il-modifika.
Jekk terġa' tagħfas Modifika, l-modifika se tiġi salvata mingħajr waħda.",
'missingcommenttext'               => 'Jekk jogħġbok ħalli kumment hawn taħt.',
'missingcommentheader'             => "'''Twissija:''' Ma pprovdejtx suġġett/titlu għal dan il-kumment.
Jekk terġa' tagħfas Modifika, l-modifika tiegħek se tiġi salvata mingħajr waħda.",
'summary-preview'                  => 'Dehra proviżorja tat-taqsira:',
'subject-preview'                  => 'Dehra proviżorja tat-taqsira/suġġett:',
'blockedtitle'                     => 'L-utent ġie bblokkjat',
'blockedtext'                      => "'''L-isem tal-utent jew l-indirizz IP tiegħek ġew imblukkat.'''

Il-blokk twettaq minn \$1. Ir-raġuni mogħtiha kienet ''\$2''.

* Il-blokk jibda: \$8
* Il-blokk jintemm: \$6
* Il-blokk kien għal: \$7

Tista' tikkuntattja lil \$1 jew xi [[{{MediaWiki:Grouppage-sysop}}|amministratur]] ieħor biex tiddiskuti dan il-blokk.

Ma tistax tuża' l-faċilità \"ikteb lil dan l-utent\" sakemm m'għandekx indirizz validu tal-email fil-[[Special:Preferences|preferenzi tal-kont]] tiegħek u ma ġejtx miżmum milli tużah.

L-IP kurrenti tiegħek huwa \$3, u l-ID tal-imblukkar huwa #\$5.<br />Jekk jogħġbok inkludi mqar wieħed minn dawn it-tnejn f'kwalunkwe rikjesta.",
'autoblockedtext'                  => "L-indirizz IP tiegħek ġie awtomatikament imblukkat minħabba li kienet qiegħed jiġi wżat minn utent ieħor, u ġie imblukkat minn \$1.
Ir-raġuni li ġiet mogħtiha kienet:

:''\$2''

* Bidu tal-blokk: \$8
* Il-blokk jintemm: \$6
* Il-blokk kien għal: \$7

Int tista' tagħmel kuntatt ma' \$1 jew ma' [[{{MediaWiki:Grouppage-sysop}}|amministratur]] ieħor biex tidiskuti dan il-blokk.

Għandek tkun taf li ma tistax tuża l-faċilità ta' \"ibgħat email lil dan l-utent\" sakemm m'għandekx indirizz validu tal-email fil-[[Special:Preferences|preferenzi tiegħek]] u int ma ġejtx imblukkat milli tużaha.

L-IP kurrenti tiegħek huwa \$3, u l-ID ta' l-imblukkar huwa #\$5.<br />
Jekk jogħġbok inkludi mqar wieħed minn dawn it-tnejn f'kwalunkwe rikjesta.",
'blockednoreason'                  => 'ma ingħatat l-ebda raġuni',
'blockedoriginalsource'            => "Is-sors tal-paġna '''$1''' jinsab hawn taħt:",
'blockededitsource'                => "It-test tal-'''modifiki tiegħek''' f' '''$1''' jinstab hawn taħt:",
'whitelistedittitle'               => "Trid tidħol sabiex tkun tista' timmodifika l-paġna",
'whitelistedittext'                => "Int trid $1 biex tkun tista' timodifika l-paġni.",
'confirmedittext'                  => "Jinħtieġ li tikkonferma l-indirizz tal-e-mail tiegħek sabiex tkun tista' timmodifika l-paġni.
Jekk jogħġbok, issettja u kkonferma l-indirizz tal-e-mail tiegħek mill-[[Special:Preferences|preferenzi tal-utent]].",
'nosuchsectiontitle'               => 'Is-sezzjoni ma tistax tinstab',
'nosuchsectiontext'                => "Int ippruvjat timmodifika sezzjoni li ma teżistix.<br />
Seta' ġara li din ġiet immexxiha jew imħassra waqt li kont qed tara l-paġna.",
'loginreqtitle'                    => "Sabiex tagħmel modifiki f'din il-paġna huwa neċessarju li tidħol bħalha utent reġistrat fuq dan is-sit.",
'loginreqlink'                     => 'li tidħol fil-kont tiegħek',
'loginreqpagetext'                 => "Int trid ikollhok $1 sabiex tkun tista' tara paġni oħrajn.",
'accmailtitle'                     => 'Il-password intbagħtet.',
'accmailtext'                      => "Password ġenerata każwalment għal [[User talk:$1|$1]] intbagħtet lil $2.<br />

Il-password għal dan il-kont il-ġdid tista' titbiddel fil-paġna għat-''[[Special:ChangePassword|tibdil tal-password]]''.",
'newarticle'                       => '(Ġdid)',
'newarticletext'                   => "Inti segwejt link għal paġna li għadha ma ġietx maħluqa.
Sabiex toħloq il-paġna, ikteb fil-kaxxa li tinsab hawn taħt (ara [[{{MediaWiki:Helppage}}|paġna tal-għajnuna]] għal aktar informazzjoni). 
Jekk wasalt hawn biż-żball, agħfas il-buttuna '''lura''' (''back'') fuq il-browser tiegħek.",
'anontalkpagetext'                 => "----''Din hija l-paġna ta' diskussjoni ta' utent anonimu li għadu ma ħoloqx kont, jew inkella li ma jużahx. 
Għaldaqstant biex nidentifikawh ikollna nużaw l-indirizz tal-IP tiegħu/tagħha.
L-istess indirizz tal-IP jista' jkun użat minn bosta utenti differenti.
Jekk int utent anonimu u tħoss li qiegħed tirċievi kummenti irrelevanti jew li ma jagħmlux sens, jekk jogħġbok [[Special:UserLogin|idħol fil-kont tiegħek]] jew [[Special:UserLogin/signup|oħloq wieħed]] sabiex tevita li fil-futur tiġi konfuż ma' utenti anonimi oħra.''",
'noarticletext'                    => "Bħalissa m'hemmx test f'din il-paġna. Inti tista' [[Special:Search/{{PAGENAME}}|tfittex it-titlu ta' din il-paġna]] f'paġni oħra, jew <span class=\"plainlinks\">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} tfittex ir-reġistri relatati], jew [{{fullurl:{{FULLPAGENAME}}|action=edit}} timmodifika din il-paġna]</span>.",
'userpage-userdoesnotexist'        => 'Il-kont tal-utent "$1" mhux reġistrat. 
Jekk jogħġbok, ara jekk verament tridx toħloq/timodifika din il-paġna.',
'blocked-notice-logextract'        => 'L-utent attwalment jinsab imblukkat.
L-aħħar daħla fir-reġistru tal-imblokki hi mogħtiha hawn taħt għal referenza:',
'clearyourcache'                   => "'''Nota - Wara li tagħmel il-modifiki, xi drabi jkollok bżonn tħassar ''il-cache'' sabiex tkun tista' tara t-tibdil li sar.'''  '''Mozilla / Firefox / Safari:''' żomm ''Shift'' waqt li tkun qiegħed tagħfas ''Reload,'' jew agħfas ''Ctrl-F5'' jew ''Ctrl-R'' (''Command-R'' fuq \"Macintosh\"); '''Konqueror: '''agħfas ''Reload'' jew agħfas ''F5;''  '''Opera:''' ħassar il-cache fl-''Għodda → Preferenzi;'' '''Internet Explorer:''' żomm ''Ctrl'' waqt li tagħfas ''Refresh,'' jew agħfas ''Ctrl-F5.''",
'usercssyoucanpreview'             => "'''Suġġeriment:''' Uża l-buttuna \"{{int:showpreview}}\" sabiex tipprova s-CSS il-ġdid tiegħek qabel ma ssalvah.",
'userjsyoucanpreview'              => "'''Suġġeriment:''' Uża l-buttuna \"{{int:showpreview}}\" sabiex tipprova l-JavaScript il-ġdid tiegħek qabel ma ssalvah.",
'usercsspreview'                   => "'''Ftakar li inti qed turi dehra proviżorja tas-CSS personali. Il-modifiki li għamilt għadhom ma ġewx salvati!'''",
'userjspreview'                    => "'''Ftakar li inti qiegħed biss tipprova/tara dehra proviżorja tal-JavaScript personali; il-modifiki li għamilt għad iridu jiġu salvati!'''",
'userinvalidcssjstitle'            => "'''Twissija:''' M'hemm l-ebda skin bl-isem \"\$1\".
Ftakar li l-paġni .css u .js personalizzati għandhom l-ewwel ittra tat-titlu żgħira, eż. {{ns:user}}:Foo/monobook.css u mhux {{ns:user}}:Foo/Monobook.css.",
'updated'                          => '(Aġġornata)',
'note'                             => "'''Nota:'''",
'previewnote'                      => "'''Ftakar li din hija biss dehra proviżorja, u li għadha ma ġietx salvata!'''",
'previewconflict'                  => "Din il-previżjoni turi l-kliem li jinsab fiż-żona ta' modifika superjuri u turi kif tidher kieku l-paġna kella tiġi modifikata.",
'session_fail_preview'             => "'''Jiddispjaċina imma l-modifika tiegħek ma setgħetx tiġi pproċessata minħabba li ntilfet l-informazzjoni tas-sessjoni.
Jekk jogħġbok, erġa' pprova. Jekk xorta tibqa' ma taħdimx, ipprova [[Special:UserLogout|oħroġ]] u erġa' idħol.'''",
'session_fail_preview_html'        => "'''Jiddispjaċina imma l-modifika tiegħek ma setgħetx tiġi pproċessata minħabba li ntilfet l-informazzjoni tas-sessjoni.'''

''Peress li {{#ifeq: {{SITENAME}} | translatewiki.net | fuq {{SITENAME}} | fil-{{SITENAME}}}} huwa possibbli l-użu tal-HTML mingħajr limitazzjonijiet (''raw HTML''), id-dehra proviżorja tiġi moħbija bħala prekawzjoni kontra l-attakki tal-JavaScript.''

'''Jekk dan huwa attentat leġittmu ta' modifika, jekk jogħġbok erġa' pprova. Jekk tibqa' ma taħdimx, ipprova [[Special:UserLogout|oħroġ]] u erġa' idħol.'''",
'token_suffix_mismatch'            => "'''Il-modifika tiegħek ma ġietx aċċettata minħabba li klijent tiegħek tertaq l-karratri tal-ortografija fit-token tal-modifika.
Din il-modifika ma ġietx aċċettata sabiex ma jkunx hemm żballji fit-test tal-paġna. Dan xi kultant jiġri minħabba li qiegħed tuża servizz difettuż anonimu li huwa bbażat fuq il-web ta' prokura.'''",
'editing'                          => 'Qiegħed jiġi modifikat l-artiklu $1',
'editingsection'                   => "Modifika ta' $1 (sezzjoni)",
'editingcomment'                   => 'Qed jiġi editjat $1 (sezzjoni ġdida)',
'editconflict'                     => "Kunflitt t'editjar: $1",
'explainconflict'                  => "Xi ħadd modifika din il-paġna sakemm int kont qiegħed tagħmel il-modifiki. <br />
Fiż-Żona tal-modifika superjuri jinsab il-kliem tal-paġna kif teżisti bħalissa, kif ġiet modifikata mill-utent l-ieħor. Il-Verżjoni bil-modifiki tiegħek jinsab fiż-żona ta' modifika inferjuri. Jekk trid il-modifiki tiegħek jiġu salvati, inti trid tgħaqqad il-modifiki tiegħek mat-test kif jeżisti bħalissa fiż-żona superjuri.
Meta tagħfas ''Modifika'', se jiġi salvat '''biss''' it-test li jinsab fiż-żona superjuri.",
'yourtext'                         => 'It-test tiegħek',
'storedversion'                    => 'Il-verżjoni maħżuna',
'nonunicodebrowser'                => "'''TWISSIJA: Il-Browser tiegħek m'għandux sapport għal unicode.
Hemm xogħol sabiex iħallik tagħmel modifiki lil paġni mingħajr periklu ta' xejn: karratri li m'humiex ASCII se jidhru fil-kaxxa tal-modifika bħala kodiċi hexadeċimali.'''",
'editingold'                       => "'''TWISSIJA: Qiegħed tagħmel modifika ta' reviżjoni antika ta' din il-paġna.
Jekk isalva dawn il-modifiki, kull bidla li saret mir-reviżjonijiet ta' wara din ir-reviżjoni se jiġu mitlufa.'''",
'yourdiff'                         => 'Differenzi',
'copyrightwarning'                 => "Jekk jogħġbok innota li kull kontribuzzjoni li tagħmel lil {{SITENAME}} hija konsidrata li ġiet postjata taħt l-$2 (ara $1 għal aktar informazzjoni).
Jekk inti tixtieq li l-kitba tiegħek ma tiġiex modifikata jew mqassma, jekk jogħġbok tagħmilx modifiki hawnhekk.<br />
Inti qiegħed ukoll qiegħed twiegħed li ktibt dan ix-xogħol int, jew ġibtu minn dominazzjoni pubblika jew resorsi b'xejn simili. <br />
<br />
'''TAGĦMILX MODIFIKI LI JINKLUDU XOGĦOL TA' ĦADDIEĦOR BLA PERMESS!'''",
'copyrightwarning2'                => "Jekk jogħġbok innota li kull kontribuzzjoni li tagħmel lil {{SITENAME}} tista' tiġi modifikata, inbidla, jew imħassra minn kontributuri oħrajn. 
Jekk inti tixtieq li l-kitba tiegħek ma tiġiex modifikata jew mqassma, jekk jogħġbok tagħmilx modifiki hawnhekk.<br />
Inti qiegħed ukoll qiegħed twiegħed li ktibt dan ix-xogħol int, jew ġibtu minn dominazzjoni pubblika jew resorsi b'xejn simili. (ara  $1 għal aktar informazzjoni) <br />
<br />
'''TAGĦMILX MODIFIKI LI JINKLUDU XOGĦOL TA' ĦADDIEĦOR BLA PERMESS!'''",
'longpagewarning'                  => "'''TWISSIJA: Din il-paġna hija $1 kb twila;
ċerta browsers jista' jkollhom problemi biex jagħmlu modifiki lil paġni li qegħdin lejn jew aktar minn 32 kb.
Jekk jogħġbok konsidra taqsam din il-paġna f'sezzjonijiet iż-żgħar.'''",
'longpageerror'                    => "'''PROBLEMA: Il-Modifika li għamilt hija twila $1 ''kilobyte'', li hija aktar mill-massimu ta' $2 ''kilobyte''. Il-Modifiki ma jistgħux jiġu salvati.'''",
'readonlywarning'                  => "'''TWISSIJA: Id-database ġiet imblukkata għall-manutenzjoni, u għaldaqstant m'huwiex possibbli ssalva l-modifiki f'dal-ħin. Biex ma titlifhomx, għalissa salva xogħlok ġo fajl u ladarba terġa' tinfetaħ id-database, ikkopja kollox. Grazzi.'''

L-amministratur li bblokkja d-database offra din ir-raġuni: $1",
'protectedpagewarning'             => "'''Twissija:  Din il-paġna ġiet imblukkata b'tali mod li l-utenti li għandhom il-privileġġi ta' amministratur biss jistgħu jimmodifikawha.'''<br/ >
L-aħħar daħla fir-reġistru hija disponibbli hawn taħt għar-referenza:",
'semiprotectedpagewarning'         => "'''Nota:''' Din il-paġna ġiet imblukkata b'tali mod li l-utenti reġistrati biss jistgħu jimmodifikawha.<br />
L-aħħar daħla fir-reġistru hija disponibbli hawn taħt għar-referenza:",
'cascadeprotectedwarning'          => "'''Twissija:''' Din il-paġna ġiet imblukkata sabiex l-utenti li għandhom il-privileġġi ta' amministratur biss ikunu jistgħu jimmodifikawha, minħabba li hija inkluża fil-{{PLURAL:\$1|paġna segwenti, li ġiet protetta|paġni segwenti li ġew protetti}}, bil-protezzjoni \"rikorsiva\" tiġi magħżula:",
'titleprotectedwarning'            => "'''Twissija: Din il-paġna ġiet imblukkata b'tali mod li l-utenti li għandhom [[Special:ListGroupRights|drittijiet speċifiċi]] jistgħu joħolquha.'''<br/ >
L-aħħar daħla fir-reġistru hija disponibbli hawn taħt għar-referenza:",
'templatesused'                    => "{{PLURAL:$1|Mudell użat|Mudelli wżati}} f'din il-paġna:",
'templatesusedpreview'             => "{{PLURAL:$1|Mudell użat|Mudelli wżati}} f'din id-dehra proviżorja.",
'templatesusedsection'             => "{{PLURAL:$1|Mudell użat|Mudelli wżati}} f'din is-sezzjoni:",
'template-protected'               => '(protetta)',
'template-semiprotected'           => '(semi-protetta)',
'hiddencategories'                 => "Din il-paġna hija membru ta' {{PLURAL:$1|1 kategorija moħbija|$1 kategoriji moħbija}}:",
'edittools'                        => '<!-- Kliem hawnhekk jidher taħt l-formuli tal-modifika u postjar. -->',
'nocreatetitle'                    => 'Il-ħolqien tal-paġna ġie miżmum',
'nocreatetext'                     => "{{SITENAME}} limitat l-abbilitá tal-ħolqien ta' paġni ġodda.
Tista' tmur lura u tagħmel modifiki ta' paġni eżistenti, inkella [[Special:UserLogin|idħol jew oħloq kont ġdid]].",
'nocreate-loggedin'                => "M'għandekx permess li toħloq paġni ġodda.",
'sectioneditnotsupported-title'    => 'L-immodifikar tas-sezzjonijiet mhuwiex sostnut',
'sectioneditnotsupported-text'     => "L-immodifikar ta' sezzjonijiet f'din il-paġna mhuwiex sostnut.",
'permissionserrors'                => 'Problemi bil-permessi',
'permissionserrorstext'            => "Inti m'għandhekx dritt li tagħmel hekk, għar-{{PLURAL:$1|raġuni|raġunijiet}} segwenti:",
'permissionserrorstext-withaction' => "M'għandekx il-permessi neċessarji biex $2, minħabba r-{{PLURAL:$1|raġuni|raġunijiet}} segwenti:",
'recreate-moveddeleted-warn'       => "'''Twissija: Inti qiegħed toħloq mill-ġdid paġna li ġiet imħassra.'''

Aċċerta ruħek jekk huwiex opportun li tkompli timmodifika din il-paġna.
Ir-reġistru tat-tħassir u tal-mixi huwa pprovdut għal aktar konvenjenza:",
'moveddeleted-notice'              => 'Din il-paġna ġiet imħassra. Ir-reġistri tat-tħassir u tal-mixi għal din il-paġna huma provduti hawn taħt għal referenza.',
'log-fulllog'                      => 'Uri r-reġistru sħiħ',
'edit-gone-missing'                => 'Il-paġna ma tistax tiġi aġġornata.
Jidher li din ġiet imħassra.',
'edit-conflict'                    => 'Kunflitt tal-editjar.',
'edit-no-change'                   => 'Il-modifika li għamilt ġiet injorata, minħabba li ebda bidla ma saret lejn it-test.',
'edit-already-exists'              => 'Ma tistax tinħoloq din il-paġna.
Din teżisti diġà.',

# Parser/template warnings
'expensive-parserfunction-warning'        => "'''Twissija:''' Din il-paġna għandha ħafna sejħiet għall-funzjonijiet parser.

Suppost irid ikollha inqas minn $2, bħalissa hemm {{PLURAL:$1|waħda|$1}}.",
'expensive-parserfunction-category'       => "Paġni b'ħafna sejħiet ta' funżjonijiet ta' analiżi għoljien.",
'post-expand-template-inclusion-warning'  => "Twissija: Id-Daqs tal-kontenut ta' template hija wisq kbira.
Ftit templates mhux se jiġu inkluża.",
'post-expand-template-inclusion-category' => "Paġni fejn id-daqs tal-kontenut ta' template ġiet maqbuża",
'post-expand-template-argument-warning'   => "Twissija: Din il-paġna għanda mill-inqas argument wieħed ta' template li għandu daqs ta' espanżjoni wisq kbira.
Dawn l-argumenti tħallew barra.",
'post-expand-template-argument-category'  => "Paġni li jinkludu mudelli b'argumenti nieqsa",

# "Undo" feature
'undo-success' => "Din il-modifika tista' tiġi mneħħija. Jekk jogħġbok verifika il-paragun t'hawn taħt u verifika li dan huwa dak li trid int, imbgħad salva l-bidliet t'hawn taħt sabiex tlesti l-proċedura ta' tneħħija.",
'undo-failure' => "Huwa impossibbli li tiġi annullata l-modifika, minħabba kunflitt ta' modifiki intermedji.",
'undo-norev'   => 'Il-modifika ma tistax tiġi annullata peress li ma teżistix jew inkella għax ġiet diġà imħassra.',
'undo-summary' => "Neħħi r-reviżjoni $1 ta' [[Special:Contributions/$2|$2]] ([[User talk:$2|Diskussjoni]])",

# Account creation failure
'cantcreateaccounttitle' => 'Il-kont ma jistax jinħoloq',
'cantcreateaccount-text' => "Ħolqien tal-kont ta' l-indirizz tal-IP ('''$1''') ġiet blokkata minn [[User:$3|$3]].

Ir-raġuni li ġiet mogħtija mingħand $3 kienet ''$2''",

# History pages
'viewpagelogs'           => "Ara r-reġistri ta' din il-paġna",
'nohistory'              => "M'hemm l-ebda kronoloġija ta' modifika f'din il-paġna.",
'currentrev'             => 'Reviżjoni kurrenti',
'currentrev-asof'        => "Reviżjoni kurrenti ta' $1",
'revisionasof'           => "Reviżjoni ta' $1",
'revision-info'          => "Reviżjoni ta' $1 minn $2",
'previousrevision'       => '←Reviżjoni eqdem',
'nextrevision'           => 'Reviżjoni iġded→',
'currentrevisionlink'    => 'Reviżjoni kurrenti',
'cur'                    => 'kur',
'next'                   => 'li jmiss',
'last'                   => 'preċ',
'page_first'             => 'l-ewwel',
'page_last'              => 'l-aħħar',
'histlegend'             => "Selezzjoni diff: marka l-kaxxi tar-radju tal-verżjonijiet sabiex tagħmel paragun u agħfas enter jew il-buttuna fin-naħħa t'isfel.<br />
Leġġenda: (kur) = differenzi bil-verżjoni kurrenti,
(l-aħħar) = differenzi bil-verżjoni preċedenti, M = modifiki żgħar.",
'history-fieldset-title' => 'Fittex fil-kronoloġija',
'history-show-deleted'   => 'Dawk biss imħassra',
'histfirst'              => 'L-iġded',
'histlast'               => 'L-eqdem',
'historysize'            => '({{PLURAL:$1|1 byte|$1 bytes}})',
'historyempty'           => '(vojta)',

# Revision feed
'history-feed-title'          => 'Kronoloġija',
'history-feed-description'    => 'Kronoloġija għal din il-paġna fuq dan il-wiki',
'history-feed-item-nocomment' => '$1 fil- $2',
'history-feed-empty'          => "Il-Paġna rikjesta qas teżisti.
Jista' jkun li ġiet imħassra mill-wiki, jew imsemmija mill-ġdid.
Prova [[Special:Search|fittex fuq il-wiki]] għal paġni relevanti ġodda.",

# Revision deletion
'rev-deleted-comment'         => '(tneħħa l-kumment)',
'rev-deleted-user'            => '(l-isem tal-utent tneħħa)',
'rev-deleted-event'           => '(azzjoni tal-log tneħħa)',
'rev-deleted-user-contribs'   => '[isem tal-utent jew indirizz IP imneħħi - il-modifika ġie moħbiha mill-kronoloġija]',
'rev-deleted-text-permission' => "Din ir-reviżjoni ta' din il-paġna ġiet '''imħassra'''.
Ikkonsulta r-[{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} reġistru tat-tħassir] għal aktar dettalji.",
'rev-deleted-text-unhide'     => "Din ir-reviżjoni ta' din il-paġna ġiet '''imħassra'''.
Ikkonsulta r-[{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} reġistru tat-tħassir] għal aktar dettalji.
Bħala amministratur inti tista' [$1 tara din ir-reviżjoni] jekk huwa neċessarju.",
'rev-suppressed-text-unhide'  => "Ir-reviżjoni ta' din il-paġna ġiet '''imħassra'''.
Dettalji jistgħu jinstabu fuq ir-[{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} reġistru tat-tħassir]. Bħala amministratur inti xorta waħda tista' [$1 tara din ir-reviżjoni] jekk huwa neċessarju.",
'rev-deleted-text-view'       => "Ir-reviżjoni ta' din il-paġna ġiet '''imħassra'''.
Bħala amministratur inti tista' taraha; jista' jkun li hemm dettalji fir-[{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} reġistru tat-tħassir].",
'rev-suppressed-text-view'    => "Ir-reviżjoni ta' din il-paġna ġiet '''imħassra'''.
Bħala amministratur inti xorta waħda tista' taraha; dettalji jistgħu jinstabu fuq ir-[{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} reġistru tat-tħassir].",
'rev-delundel'                => 'uri/aħbi',
'rev-showdeleted'             => 'uri',
'revisiondelete'              => 'Ħassar/irkupra reviżjonijiet',
'revdelete-nooldid-title'     => 'Verżjoni mhux speċifikata',
'revdelete-nooldid-text'      => 'Ma ġiet speċifikata l-ebda reviżjoni tal-paġna fuq liema se ssir din l-azzjoni, ir-reviżjoni speċifikata ma teżistix, jew inkella qiegħed tipprova taħbi r-reviżjoni kurrenti.',
'revdelete-nologtype-title'   => "L-ebda tip ta' reġistru ma ġie speċifikat",
'revdelete-nologtype-text'    => "Ma ġie speċifikat l-ebda tip ta' reġistru fuqiex l-azzjoni se ssir.",
'revdelete-nologid-title'     => 'Daħla invalida għar-reġistru',
'revdelete-nologid-text'      => 'Ma ġie speċifikat l-ebda avveniment tar-reġistru fuqiex il-funzjoni se ssir jew id-daħla speċifikata ma teżistix.',
'revdelete-no-file'           => 'Il-fajl speċifikat ma jeżistix.',
'revdelete-show-file-confirm' => 'Tinsab ċert li trid tara reviżjoni mħassra tal-fajl "<nowiki>$1</nowiki>" tal-$2 fil-$3?',
'revdelete-show-file-submit'  => 'Iva',
'revdelete-selected'          => "'''{{PLURAL:$2|Reviżjoni magħżula|Reviżjonijiet magħżula}} ta' [[:$1]]:'''",
'logdelete-selected'          => "'''{{PLURAL:$1|Avveniment tar-reġistru magħżul|Avvenimenti tar-reġistri magħżula}}:'''",
'revdelete-text'              => "'''Reviżjonijiet u azzjonijiet imħassra xorta waħda jidhru fil-kronoloġija tal-paġna, filwaqt li partijiet tal-kontenut jiġu inaċċessibli għall-pubbliku.'''
L-amminstraturi l-oħrajn fuq {{SITENAME}} xorta jkunu jistgħu jidħlu fuq il-kontenut moħbi u jistgħu jirkuprawh minn din l-istess interfaċċa, sakemm restrizzjonijiet ulterjuri jiġu definiti.",
'revdelete-confirm'           => 'Jekk jogħġbok ikkonferma li dan hu dak li tixtieq tagħmel, li tifhem il-konsegwenzi, u li qed tagħmel dan skont il-[[{{MediaWiki:Policy-url}}|politika]].',
'revdelete-legend'            => "Oħloq limiti ta' viżibilità",
'revdelete-hide-text'         => 'Aħbi l-kontenut tar-reviżjoni',
'revdelete-hide-image'        => 'Aħbi l-kontenut tal-fajl',
'revdelete-hide-name'         => 'Aħbi l-azzjoni u it-tarka',
'revdelete-hide-comment'      => 'Aħbi kumment tal-modifika',
'revdelete-hide-user'         => 'Aħbi l-isem tal-utent/IP tal-modifikatur',
'revdelete-hide-restricted'   => 'Aħbi d-dati indikati anki lill-amministraturi.',
'revdelete-radio-same'        => '(tbiddilx)',
'revdelete-radio-set'         => 'Iva',
'revdelete-radio-unset'       => 'Le',
'revdelete-suppress'          => 'Aħbi l-informazzjoni minn Amministraturi wkoll bħall-oħrajn',
'revdelete-unsuppress'        => 'Neħħi limiti fuq reviżjonijiet irkuprati',
'revdelete-log'               => 'Raġuni għat-tħassir:',
'revdelete-submit'            => 'Applika lir-{{PLURAL:$1|reviżjoni magħżulha|reviżjonijiet magħżula}}',
'revdelete-logentry'          => "Il-Visibilitá tar-reviżjoni ta' [[$1]] inbidlet",
'logdelete-logentry'          => "Il-Visibilitá tal-avveniment ta' $1 inbidlet",
'revdelete-success'           => "'''Il-viżibilità tar-reviżjoni ġiet aġġornata b'suċċess.'''",
'revdelete-failure'           => "'''Il-viżibilità tar-reviżjoni ma tistax tiġi aġġornata:'''
$1",
'logdelete-success'           => "'''II-viżibilità tar-reġistru ġiet imposta b'suċċess.'''",
'revdel-restore'              => 'Biddel visibilitá',
'pagehist'                    => 'Kronoloġija tal-paġna',
'deletedhist'                 => 'Kronoloġija mħassra',
'revdelete-content'           => 'kontenut',
'revdelete-summary'           => 'Taqsira tal-modifika',
'revdelete-uname'             => 'isem tal-utent',
'revdelete-restricted'        => "limiti applikati 'l amministraturi",
'revdelete-unrestricted'      => "neħħi l-limiti 'l amministraturi",
'revdelete-hid'               => 'aħbi $1',
'revdelete-unhid'             => 'uri $1',
'revdelete-log-message'       => '$1 għal $2 {{PLURAL:$2|revision|reviżjoni}}',
'logdelete-log-message'       => '$1 għal $2 {{PLURAL:$2|event|avvenimenti}}',
'revdelete-reason-dropdown'   => "*Raġunijiet komuni għat-tħassir
** Vjolazzjoni ta' copyright
** Informazzjoni personali inapproprjata
** Informazzjoni potenzjalment libelluża",
'revdelete-reasonotherlist'   => 'Raġuni oħra',
'revdelete-edit-reasonlist'   => 'Immodifika r-raġunijiet għat-tħassir',

# Suppression log
'suppressionlog'     => "Log ta' ċaħdied",
'suppressionlogtext' => "Il-lista ta' tħassir u blokjar tinsab hawn taħt din tinkludi kontenut li huwa moħbi mill-amministraturi.
Ara [[Special:IPBlockList|IP lista ta' blokjar]] għal lista ta' ċaħdiet u blokjar kurrenti.",

# History merging
'mergehistory'                     => 'Waħħad l-istorji tal-paġni',
'mergehistory-header'              => "Din il-paġna tħallik twaħħad reviżjonijiet li jappartjenu lil kronoloġija ta' paġna (magħrufa bħala paġna t'oriġini) mal-kronoloġija ta' paġna aktar riċenti.
Huwa importanti li l-kontinwità storika tal-paġna ma tiġix alterata.",
'mergehistory-box'                 => 'Waħħad ir-reviżjonijiet taż-żewġ paġni:',
'mergehistory-from'                => 'Il-paġna tal-oriġini:',
'mergehistory-into'                => "Il-paġna ta' destinazzjoni:",
'mergehistory-list'                => 'Kronoloġija tal-modifiki jistgħu jiġu uniti',
'mergehistory-merge'               => "Ir-reviżjonijiet ta' [[:$1]] jistgħu jiġu magħquda f'[[:$2]].
Uża l-kolonna tal-buttona tar-radju sabiex tgħaqqad biss dawk ir-reviżjonijiet li ħloqt f'ċerta ħin jew qabel ħin speċifiku.
Nota li l-użu tal-links tan-navigazzjoni jagħmel reset tal-kolonna.",
'mergehistory-go'                  => 'Uri modifiki li jistgħu jiġu magħquda',
'mergehistory-submit'              => 'Waħħad ir-reviżjonijiet',
'mergehistory-empty'               => "L-Ebda reviżjoni tista' tiġi magħquda.",
'mergehistory-success'             => "$3 {{PLURAL:$3|reviżjoni|reviżjonijiet}} ta' [[:$1]] twaħħdu ma' [[:$2]] b'suċċess.",
'mergehistory-fail'                => 'Mhux possibli li jitwaħħdu l-istejjer, jekk jogħġbok ivverifika l-paġna u l-parametri tal-ħin.',
'mergehistory-no-source'           => 'Paġna tas-sors $1 ma teżistix.',
'mergehistory-no-destination'      => 'Paġna tad-destinazzjoni $1 ma teżistix.',
'mergehistory-invalid-source'      => 'Paġna tas-sors għanda jkollha titlu validu.',
'mergehistory-invalid-destination' => 'Paġna tad-destinazzjoni għandu jkollu titlu validu.',
'mergehistory-autocomment'         => "[[:$1]] twaħħad ma' [[:$2]]",
'mergehistory-comment'             => "[[:$1]] twaħħad ma' [[:$2]]: $3",
'mergehistory-same-destination'    => 'Il-paġni tas-sors u tad-destinazzjoni ma jistgħux ikunu l-istess',
'mergehistory-reason'              => 'Raġuni:',

# Merge log
'mergelog'           => "Reġistru ta' twaħħid",
'pagemerge-logentry' => "waħħad [[$1]] ma' [[$2]] (reviżjonijiet sa $3)",
'revertmerge'        => 'Infired',
'mergelogpagetext'   => "Hawn taħt hawn lista ta' l-aktar tgħaqqid riċenti ta' paġna waħda ta' storja f'oħra.",

# Diffs
'history-title'            => 'Kronoloġija tal-modifiki ta\' "$1"',
'difference'               => '(Differenzi bejn ir-reviżjonijiet)',
'lineno'                   => 'Linja $1:',
'compareselectedversions'  => 'Qabbel il-verżjonijiet magħżula',
'showhideselectedversions' => 'Uri/aħbi reviżjonijiet magħżula',
'editundo'                 => 'ħassar',
'diff-multi'               => '({{PLURAL:$1|One intermediate revision|$1 reviżjonijiet intermedji}} mhux qegħdin jidhru.)',

# Search results
'searchresults'                    => 'Riżultat tat-tfittxija',
'searchresults-title'              => 'Riżultati tat-tfittxija għal "$1"',
'searchresulttext'                 => "Aktar informazzjoni dwar ir-riċerka ta' {{SITENAME}}, ara [[{{MediaWiki:Helppage}}|{{int:help}}]].",
'searchsubtitle'                   => 'Int fittixt għal \'\'\'[[:$1]]\'\'\' ([[Special:Prefixindex/$1|il-paġni kollha li jibdew b\'"$1"]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|il-paġni kollha li jwasslu għal "$1"]])',
'searchsubtitleinvalid'            => "Int fittixt għal '''$1'''",
'toomanymatches'                   => 'Ħafna tqabbil ġew ritornati, jekk jogħġbok prova inkjesta differenti',
'titlematches'                     => 'Titlu tal-paġna taqbel',
'notitlematches'                   => "L-ebda titlu ta' paġna ma jaqbel",
'textmatches'                      => 'It-test tal-paġni, jaqbel',
'notextmatches'                    => "L-ebda test ta' paġna ma jaqbel",
'prevn'                            => "{{PLURAL:$1|$1}} ta' qabel",
'nextn'                            => '{{PLURAL:$1|$1}} li jmiss',
'viewprevnext'                     => 'Ara ($1 {{int:pipe-separator}} $2) ($3)',
'searchmenu-exists'                => "'''Hemm paġna bl-isem ta' \"[[:\$1]]\" fuq din il-wiki'''",
'searchmenu-new'                   => "'''Oħloq il-paġna \"[[:\$1]]\" fuq din il-wiki!'''",
'searchhelp-url'                   => 'Help:Kontenut',
'searchmenu-prefix'                => "[[Special:PrefixIndex/$1|Uri l-paġni b'dan il-prefiss]]",
'searchprofile-articles'           => "Paġni ta' kontenut",
'searchprofile-project'            => "Paġni ta' għajnuna u ta' proġett",
'searchprofile-images'             => 'Multimedja',
'searchprofile-everything'         => 'Kollox',
'searchprofile-advanced'           => 'Avvanzata',
'searchprofile-articles-tooltip'   => "Fittex f'$1",
'searchprofile-project-tooltip'    => "Fittex f'$1",
'searchprofile-images-tooltip'     => 'Fittex għal fajls',
'searchprofile-everything-tooltip' => "Fittex kullimkien (inklużi l-paġni ta' diskussjoni)",
'search-result-size'               => '$1 ({{PLURAL:$2|1 word|$2 kliem}})',
'search-result-score'              => 'Relevanza: $1%',
'search-redirect'                  => '(rindirizza $1)',
'search-section'                   => '(sezzjoni $1)',
'search-suggest'                   => 'Trid tfisser: $1',
'search-interwiki-caption'         => 'Proġetti kuġini',
'search-interwiki-default'         => "Riżultati ta' $1:",
'search-interwiki-more'            => '(aktar)',
'search-mwsuggest-enabled'         => 'bis-suġġerimenti',
'search-mwsuggest-disabled'        => 'l-ebda suġġeriment',
'search-relatedarticle'            => 'Relatati',
'mwsuggest-disable'                => 'Neħħi suġġeriment tal-AJAX',
'searchrelated'                    => 'relatati',
'searchall'                        => 'kollha',
'showingresults'                   => "Hawn taħt ġie inkluż massimu ta' {{PLURAL:$1|riżultat '''1''' li jibda|'''$1''' riżultat li jibdew}} bin-numru '''$2'''.",
'showingresultsnum'                => "Hawn taħt {{PLURAL:$3|jinsab riżultat '''1''' li jibda|jinsabu '''$3''' riżultati li jibdew}} bin-numru '''$2'''.",
'nonefound'                        => "'''Nota''': Awtomatikament, huma ftit spazji tal-isem imfittxija.
Ipprova għamel prefiss għall-inkjesta tiegħek ma' ''all:'' sabiex tfittex il-kontenut kollu (inkluż paġni ta' diskussjoni, mudelli, etċ), jew uża l-ispazju tal-isem mixtieq bħala prefiss.",
'search-nonefound'                 => 'It-tfittxija ma tat l-ebda riżultat.',
'powersearch'                      => 'Tfittxija avvanzata',
'powersearch-legend'               => 'Tfittxija avvanzata',
'powersearch-ns'                   => 'Fittex fl-ispazju tal-isem:',
'powersearch-redir'                => "Lista ta' riindirizzi",
'powersearch-field'                => 'Fittex',
'search-external'                  => 'Tfittxija esterna',
'searchdisabled'                   => "It-Tfittxija fil-{{SITENAME}} mhux attiva.
Sadanittant, tista' tipprova tfittex bil-Google.
Innota però li l-werreja tal-kontenut ta' {{SITENAME}} f'dawn is-siti, jistgħu ma jkunux aġġornati.",

# Quickbar
'qbsettings'               => "''Quickbar''",
'qbsettings-none'          => 'Xejn',
'qbsettings-fixedleft'     => 'Mehmuż fix-xellug',
'qbsettings-fixedright'    => 'Mehmuż fil-lemin',
'qbsettings-floatingleft'  => "''Floating'' lejn ix-xellug",
'qbsettings-floatingright' => "''Floating'' lejn il-lemin",

# Preferences page
'preferences'                   => 'Preferenzi',
'mypreferences'                 => 'il-preferenzi tiegħi',
'prefs-edits'                   => "Numru ta' modifiki:",
'prefsnologin'                  => 'Għadek ma dħaltx ġewwa',
'prefsnologintext'              => 'Sabiex tkun tista\' tippersonalizza l-preferenzi huwa neċessarju li tidħol fil-<span class="plainlinks">[{{fullurl:{{#Special:UserLogin}}|returnto=$1}} kont]</span>.',
'changepassword'                => 'Ibdel il-password',
'prefs-skin'                    => 'Aspett grafiku (skin)',
'skin-preview'                  => 'dehra proviżorja',
'prefs-math'                    => 'Formuli matematiċi',
'datedefault'                   => 'L-ebda preferenza',
'prefs-datetime'                => 'Data u ħin',
'prefs-personal'                => 'Profil tal-utent',
'prefs-rc'                      => 'Modifiki riċenti',
'prefs-watchlist'               => 'Osservazzjoni speċjali',
'prefs-watchlist-days'          => "Numru ta' ġranet li għandu jintwera fil-osservazzjoni speċjali:",
'prefs-watchlist-days-max'      => "(massimu ta' 7 ġranet)",
'prefs-watchlist-edits'         => "Numru ta' modifiki li tista' turi bil-funżjoni avvanzata:",
'prefs-watchlist-edits-max'     => '(numru massimu: 1000)',
'prefs-misc'                    => 'Varji',
'prefs-resetpass'               => 'Biddel il-password',
'prefs-email'                   => 'Opzjonijiet għall-posta elettronika',
'prefs-rendering'               => 'Dehra',
'saveprefs'                     => 'Żomm il-preferenzi',
'resetprefs'                    => 'Neħħi modifiki mhux salvati',
'restoreprefs'                  => 'Irkupra l-impostazzjonijiet awtomatiċi',
'prefs-editing'                 => 'Modifiki',
'prefs-edit-boxsize'            => 'Daqs tat-tieqa tal-immodifikar.',
'rows'                          => 'Fillieri:',
'columns'                       => 'Kolonni:',
'searchresultshead'             => 'Fittex',
'resultsperpage'                => "Numru ta' riżultati għal kull paġna:",
'contextlines'                  => 'Fillieri għal kull riżultat:',
'contextchars'                  => "Numru ta' karratri tal-kliem",
'stub-threshold'                => 'Valur minimu għall-<a href="#" class="stub">ħoloq għall-abozzi</a>, f\'bytes:',
'recentchangesdays'             => "Numru ta' ġranet li għandhom jintwerew fit-tibdil riċenti:",
'recentchangesdays-max'         => "(massimu ta' $1 {{PLURAL:$1|ġurnata|ġurnata}})",
'recentchangescount'            => "Numru ta' fillieri fit-tibdil riċenti:",
'prefs-help-recentchangescount' => 'Din tinkludi tibdil riċenti, kronoloġiji u reġistri.',
'savedprefs'                    => 'Il-preferenzi tiegħek ġew salvati.',
'timezonelegend'                => 'Żona tal-ħin:',
'localtime'                     => 'Ħin lokali:',
'timezoneuseserverdefault'      => 'Uża l-ħin tas-server',
'timezoneuseoffset'             => 'Ieħor (ispeċifika d-differenza)',
'timezoneoffset'                => 'Differenza¹:',
'servertime'                    => 'Ħin tas-server:',
'guesstimezone'                 => "Uża l-ħin tal-''browser'' tiegħek",
'timezoneregion-africa'         => 'Afrika',
'timezoneregion-america'        => 'Amerika',
'timezoneregion-antarctica'     => 'Antartika',
'timezoneregion-arctic'         => 'Artiku',
'timezoneregion-asia'           => 'Asja',
'timezoneregion-atlantic'       => 'Oċean Atlantiku',
'timezoneregion-australia'      => 'Awstralja',
'timezoneregion-europe'         => 'Ewropa',
'timezoneregion-indian'         => 'Oċean Indjan',
'timezoneregion-pacific'        => 'Oċean Paċifiku',
'allowemail'                    => 'Ħalli li jaslulek ittri-e mingħand utenti oħrajn',
'prefs-searchoptions'           => 'Preferenzi għat-tfittxija',
'prefs-namespaces'              => 'Namespace',
'defaultns'                     => "Fil-każ kuntrarju, fittex f'dawn l-ispazji tal-isem:",
'default'                       => 'predefinit',
'prefs-files'                   => 'Fajls',
'prefs-custom-css'              => 'CSS personalizzat',
'prefs-custom-js'               => 'JS personalizzat',
'prefs-common-css-js'           => 'CSS/JS maqsum għal kull aspett grafiku:',
'prefs-textboxsize'             => 'Daqs tat-tieqa tal-modifika',
'youremail'                     => 'E-mail:',
'username'                      => 'Isem tal-utent:',
'uid'                           => 'L-ID tal-utent:',
'prefs-memberingroups'          => 'Membru tal-{{PLURAL:$1|grupp|gruppi}}:',
'prefs-memberingroups-type'     => '$1',
'prefs-registration'            => "Ħin ta' reġistrazzjoni:",
'yourrealname'                  => 'Isem proprju:',
'yourlanguage'                  => 'Lingwa:',
'yourvariant'                   => 'Varjant:',
'yournick'                      => 'Firma:',
'prefs-help-signature'          => "Kummenti fuq il-paġni ta' diskussjoni jridu jiġu ffirmati b'permezz ta' \"<nowiki>~~~~</nowiki>\" li jiġu maqluba għall-firma tiegħek u d-data.",
'badsig'                        => 'Il-firma mhux standard, hija invalida; iċċekkja t-tags tal-HTML.',
'badsiglength'                  => 'Il-firma hija twila wisq. Trid tkun inqas minn $1 {{PLURAL:$1|karattru|karattru}}.',
'yourgender'                    => 'Sess:',
'gender-unknown'                => 'Mhux speċifikat',
'gender-male'                   => 'Maskil',
'gender-female'                 => 'Femminil',
'prefs-help-gender'             => 'Opzjonali: użat biex jagħmel l-indikazzjoni tas-sess mis-softwer. Din l-informazzjoni tkun pubblika.',
'email'                         => 'Posta elettronika',
'prefs-help-realname'           => 'L-isem propju mhuwiex obbligatorju; jekk tagħżel li tipprovdih, dan jintuża biss biex jagħtik attribuzzjoni għax-xogħol tiegħek.',
'prefs-help-email'              => "L-indirizz tal-posta elettronika huwa kamp opzjonali, però dan jagħti aċċess lil oħrajn sabiex jagħmlu kuntatt miegħek minn ġol-paġna tal-utent jew mill-paġna ta' diskussjoni tal-utent mingħajr ma jkollok bżonn li turi l-identità tiegħek.",
'prefs-help-email-required'     => 'Hemm bżonn l-indirizz tal-posta elettronika.',
'prefs-info'                    => 'Informazzjoni bażika',
'prefs-i18n'                    => 'Internazzjonalizzazzjoni',
'prefs-signature'               => 'Firma',
'prefs-dateformat'              => 'Format tad-data',
'prefs-timeoffset'              => 'Differenza fis-sigħat',
'prefs-advancedediting'         => 'Opzjonijiet avvanzati',
'prefs-advancedrc'              => 'Opzjonijiet avvanzati',
'prefs-advancedrendering'       => 'Opzjonijiet avvanzati',
'prefs-advancedsearchoptions'   => 'Opzjonijiet avvanzati',
'prefs-advancedwatchlist'       => 'Opzjonijiet avvanzati',
'prefs-display'                 => "Opzjonijiet ta' viżwalizazzjoni",

# User rights
'userrights'                   => 'Ġestjoni tad-drittijiet tal-utent',
'userrights-lookup-user'       => 'Ġestjoni tal-gruppi tal-utent',
'userrights-user-editname'     => 'Daħħal isem tal-utent:',
'editusergroup'                => 'Immodifika l-gruppi tal-utent',
'editinguser'                  => "Modifika tad-drittijiet tal-utent '''[[User:$1|$1]]''' ([[User talk:$1|{{int:talkpagelinktext}}]]{{int:pipe-separator}}[[Special:Contributions/$1|{{int:contribslink}}]])",
'userrights-editusergroup'     => 'Immodifika l-gruppi tal-utent',
'saveusergroups'               => 'Salva l-gruppi tal-utent',
'userrights-groupsmember'      => "Membru ta':",
'userrights-groupsmember-auto' => "Membru impliċitu ta':",
'userrights-groups-help'       => "Huwa possibbli li timmodifika l-gruppi li dan l-utent jinsab fihom:
* Kaxxa bil-punta magħżula tfisser li l-utent huwa fil-grupp
* Kaxxa bil-punta mhux magħżula tfisser li l-utent mhuwiex f'dak il-grupp
* It-tagħrifa * tindika li mhuwiex possibbli li tneħħi l-grupp ġaladarba tkun żidtu (jew viċi versa)",
'userrights-reason'            => 'Raġuni:',
'userrights-no-interwiki'      => "M'għandekx permess tagħmel modifiki fid-drittijiet tal-utenti fuq siti oħrajn.",
'userrights-nodatabase'        => 'Id-Database $1 ma jeżistix jew inkella mhux database lokali.',
'userrights-nologin'           => "Sabiex tkun tista' tagħti d-drittijiet lill-utenti hemm bżonn li [[Special:UserLogin|tidħol]] bħalha amministratur.",
'userrights-notallowed'        => "M'għandekx permess tagħti drittijiet lill-utenti.",
'userrights-changeable-col'    => "Gruppi li tista' tbiddel",
'userrights-unchangeable-col'  => 'Gruppi li ma tistax tbiddel',

# Groups
'group'               => 'Grupp:',
'group-user'          => 'Utenti',
'group-autoconfirmed' => 'Utenti konfermati awtomatikament',
'group-bot'           => 'Bot',
'group-sysop'         => 'Amministraturi',
'group-bureaucrat'    => 'Burokrati',
'group-suppress'      => "''Oversight''",
'group-all'           => '(kollha)',

'group-user-member'          => 'utent',
'group-autoconfirmed-member' => 'utent konfermat awtomatikament',
'group-bot-member'           => 'bot',
'group-sysop-member'         => 'amministratur',
'group-bureaucrat-member'    => 'burokrata',
'group-suppress-member'      => "''Oversight''",

'grouppage-user'          => '{{ns:project}}:Utenti',
'grouppage-autoconfirmed' => '{{ns:project}}:Utenti konfermati awtomatikament',
'grouppage-bot'           => '{{ns:project}}:Bot',
'grouppage-sysop'         => '{{ns:project}}:Amminstraturi',
'grouppage-bureaucrat'    => '{{ns:project}}:Burokrati',
'grouppage-suppress'      => '{{ns:project}}:Oversight',

# Rights
'right-read'                 => 'Aqra paġni',
'right-edit'                 => 'Modifika paġni',
'right-createpage'           => 'Oħloq paġni',
'right-createtalk'           => "Oħloq paġni ta' diskussjoni",
'right-createaccount'        => "Oħloq kontijiet ġodda ta' utent",
'right-minoredit'            => 'Marka l-modifiki bħalha modifiki żgħar',
'right-move'                 => 'Mexxi l-paġni',
'right-move-subpages'        => 'Mexxi l-paġni flimkien mal-paġni relatati',
'right-movefile'             => 'Mexxi l-fajls',
'right-suppressredirect'     => 'Evita li toħloq rindirizz mill-paġna l-antika meta tmexxi l-paġna',
'right-upload'               => "Tella' fajls",
'right-reupload'             => 'Ikteb fuq fajl ġa eżistenti',
'right-reupload-own'         => "Ikteb fuq fajl ġa eżistenti li ġie mtella' mill-istess utent",
'right-reupload-shared'      => "Ikteb fuq fajls preżenti fl-arkivju lokali tal-''shared'' medja.",
'right-upload_by_url'        => "Tella' fajl minn indirizz ta' URL",
'right-purge'                => "Porga l-''cache'' tas-sit mingħajr konferma.",
'right-autoconfirmed'        => 'Modifika paġni semi-protetti',
'right-bot'                  => 'Jiġi trattat bħalha proċess awtomatiku',
'right-nominornewtalk'       => "Tnebbaħniex dwar modifiki żgħar li ssiru fil-paġni ta' diskussjoni",
'right-apihighlimits'        => 'Uża limitu aktar għoli għall-inkjesti API',
'right-writeapi'             => 'Uża API sabiex tagħmel modifiki fil-wiki',
'right-delete'               => 'Ħassar paġni',
'right-bigdelete'            => "Ħassar paġni b'kronoloġija kbira",
'right-deleterevision'       => 'Ħassar reviżjonijiet speċifiki tal-paġni',
'right-deletedhistory'       => 'Uri r-reviżjonijiet tal-kronoloġija li huma mħassra mingħajr it-test assoċjat.',
'right-browsearchive'        => 'Uri paġni mħassra',
'right-undelete'             => 'Irkupra paġna',
'right-suppressrevision'     => 'Irrevedi u rkupra reviżjonijiet moħbija mill-amministraturi',
'right-suppressionlog'       => 'Uri reġistri privati',
'right-block'                => 'Blokka utenti oħrajn mill-modifikar',
'right-blockemail'           => "Blokka utent milli jkun jista' jibgħat posta elettronika",
'right-hideuser'             => "Blokka isem ta' utent, aħbih mill-pubbliku",
'right-ipblock-exempt'       => "Tgħatix każ blokki tal-IP, blokki awtomatiċi u blokki ta' range ta' IP",
'right-proxyunbannable'      => "Tgħatix każ blokki fuq il-''proxy''",
'right-protect'              => "Modifika l-livell ta' protezzjoni",
'right-editprotected'        => 'Modifika paġni protetti',
'right-editinterface'        => 'Immodifika l-interfaċċa tal-utent',
'right-editusercssjs'        => "Modifika l-fajls CSS u JS ta' utenti oħrajn",
'right-editusercss'          => "Modifika l-fajls CSS ta' utenti oħrajn",
'right-edituserjs'           => "Modifika l-fajls JS ta' utenti oħrajn",
'right-rollback'             => "Rollback malajr il-modifiki ta' l-aħħar utent li għamel modifiki f'paġna partikulari",
'right-markbotedits'         => "Marka modifiki speċifiki bħalha modifiki ta' bot",
'right-noratelimit'          => "Mhux suġġett ta' limitu ta' azzjoni",
'right-import'               => 'Importa paġni minn wiki oħrajn',
'right-importupload'         => "Importa paġni minn ''upload'' ta' fajl",
'right-patrol'               => "Marka modifiki ta' utenti oħrajn bħalha verifikati",
'right-autopatrol'           => 'Marka awtomatikament il-modifiki tiegħek bħalha verifikati',
'right-patrolmarks'          => "Uża l-funzjoni ta' verifika tat-tibdil riċenti",
'right-unwatchedpages'       => "Uri lista ta' paġni mhux osservati",
'right-trackback'            => "Ibgħat ''trackback''",
'right-mergehistory'         => 'Agħqqad il-kronoloġija tal-paġni',
'right-userrights'           => 'Modifika d-drittijiet tal-utenti kollha',
'right-userrights-interwiki' => "Modifika d-drittijiet tal-utenti ta' wiki oħrajn",
'right-siteadmin'            => 'Agħlaq u iftaħ id-database',

# User rights log
'rightslog'      => 'Drittijiet tal-utenti',
'rightslogtext'  => "Dan huwa r-reġistru tal-modifiki ta' drittijiet tal-utenti.",
'rightslogentry' => "ġie modifikat is-sħubija tal-grupp ta' $1 mill-grupp $2 għal grupp $3",
'rightsnone'     => '(xejn)',

# Associated actions - in the sentence "You do not have permission to X"
'action-read'           => 'aqra din il-paġna',
'action-edit'           => 'timmodifika din il-paġna',
'action-createpage'     => 'oħloq paġni',
'action-createtalk'     => "oħloq paġni ta' diskussjoni",
'action-createaccount'  => 'oħloq dan il-kont tal-utent',
'action-minoredit'      => 'timmarka din il-modifika bħala waħda minuri',
'action-move'           => 'mexxi din il-paġna',
'action-move-subpages'  => 'mexxi din il-paġna, u s-sottopaġni',
'action-movefile'       => 'mexxi dan il-fajl',
'action-upload'         => "tella' dan il-fajl",
'action-delete'         => 'ħassar din il-paġna',
'action-deleterevision' => 'ħassar din ir-reviżjoni',
'action-deletedhistory' => "ara l-kronoloġija mħassar ta' din il-paġna",
'action-browsearchive'  => 'fittex paġni mħassra',
'action-undelete'       => 'irkupra din il-paġna',
'action-suppressionlog' => 'ara dan il-log privat',
'action-block'          => 'imblokka lil dan l-utent milli jimmodifika',
'action-protect'        => "biddel il-livelli ta' protezzjoni għal din il-paġna",
'action-unwatchedpages' => "uri l-lista ta' paġni li mhumiex osservati",
'action-mergehistory'   => "waħħad il-kronoloġija ta' din il-paġna",

# Recent changes
'nchanges'                          => '{{PLURAL:$1|modifika $1 |$1 modifiki}}',
'recentchanges'                     => 'Tibdil riċenti',
'recentchanges-legend'              => 'Opzjonijiet tat-tibdil riċenti',
'recentchangestext'                 => 'Din il-paġna turi l-modifiki l-aktar riċenti għal kontenut tas-sit.',
'recentchanges-feed-description'    => "Dan il-feed jirraporta l-modifiki l-aktar riċenti fil-kontenut ta' dan is-sit.",
'recentchanges-label-legend'        => 'Leġġenda: $1.',
'recentchanges-legend-newpage'      => '$1 - paġna ġdida',
'recentchanges-label-newpage'       => 'Din il-modifika ħolqot paġna ġdida',
'recentchanges-legend-minor'        => '$1 - modifika minuri',
'recentchanges-label-minor'         => 'Din hi modifika minuri',
'recentchanges-legend-bot'          => "$1 - modifika ta' bot",
'recentchanges-label-bot'           => 'Din il-modifika ġiet effettwata minn bot',
'recentchanges-legend-unpatrolled'  => '$1 - modifika mhux verifikata',
'recentchanges-label-unpatrolled'   => 'Din il-modifika għadha ma ġietx verifikata',
'rcnote'                            => "Hawn taħt {{PLURAL:$1|tinsab l-aktar modifika riċenti|jinsabu l-'''$1''' modifiki riċenti}} għas-sit fl-aħħar {{PLURAL:$2|24 siegħa|'''$2''' ġranet}}, id-dati ġew aġġornati fil-$5 ta' $4.",
'rcnotefrom'                        => "Ħawn taħt jinsabu l-modifiki minn '''$2''' (sa '''$1''').",
'rclistfrom'                        => 'Uri l-modifiki ġodda jibdew minn $1',
'rcshowhideminor'                   => '$1 modifiki żgħar',
'rcshowhidebots'                    => '$1 bot',
'rcshowhideliu'                     => 'Utenti reġistrati: $1',
'rcshowhideanons'                   => 'Utenti anonimi: $1',
'rcshowhidepatr'                    => '$1 modifiki kontrollati',
'rcshowhidemine'                    => '$1 modifiki tiegħi',
'rclinks'                           => 'Uri l-aħħar $1 modifiki fl-aħħar $2 ġranet<br />$3',
'diff'                              => 'diff',
'hist'                              => 'kron',
'hide'                              => 'Aħbi',
'show'                              => 'Uri',
'minoreditletter'                   => 'm',
'newpageletter'                     => 'Ġ',
'boteditletter'                     => 'b',
'number_of_watching_users_pageview' => '[osservat minn {{PLURAL:$1|$1 utent|$1 utent}}]',
'rc_categories'                     => 'Illimita għall-kategoriji (issepara b\' "|")',
'rc_categories_any'                 => 'Kwalunkwe',
'newsectionsummary'                 => '/* $1 */ sezzjoni ġdida',
'rc-enhanced-expand'                => 'Uri d-dettalji (hemm bżonn tal-JavaScript)',
'rc-enhanced-hide'                  => 'Aħbi d-dettalji',

# Recent changes linked
'recentchangeslinked'          => 'Tibdil relatat',
'recentchangeslinked-feed'     => 'Tibdil relatat',
'recentchangeslinked-toolbox'  => 'Tibdil relatat',
'recentchangeslinked-title'    => 'Modifiki relatati ma\' "$1"',
'recentchangeslinked-noresult' => 'L-ebda modifika ma saret fuq il-paġni relatati waqt il-perjodu speċifikat.',
'recentchangeslinked-summary'  => "Din hija lista ta' bidliet li saru riċentament fuq paġni marbuta minn paġna speċifika (jew lejn membri ta' kategorija speċifika). Il-paġni fuq il-[[Special:Watchlist|lista ta' osservazzjoni]] tiegħek huma mmarkati b''''tipa ħoxna'''.",
'recentchangeslinked-page'     => 'Isem tal-paġna:',
'recentchangeslinked-to'       => "Minflok, uri t-tibdiliet fil-paġni llinkjati ma' dik speċifikata",

# Upload
'upload'                      => "Tella' fajl",
'uploadbtn'                   => "Tella' fajl",
'reuploaddesc'                => 'Mur lura għal formula',
'uploadnologin'               => 'Għadek ma dħaltx ġewwa',
'uploadnologintext'           => "Sabiex il-fajl jiġu ''uploaded'' inti trid tkun [[Special:UserLogin|dħalt]] b'kont reġistrat.",
'upload_directory_missing'    => "Id-Direttorju tal-''upload'' ($1) huwa nieqes u ma jistax jiġi maħluq mill-''webserver''.",
'upload_directory_read_only'  => "Il-''Webserver'' m'għandux il-mezzi sabiex jikteb fil-direttorju tal-''upload'' ($1).",
'uploaderror'                 => "Problema fl-''upload''",
'uploadtext'                  => "Uża l-formula t'hawn taħt sabiex ittella' fajls ġodda.
Biex tara jew tfittex fajls li ġew mtellgħin qabel mur fil-[[Special:FileList|lista ta' fajls mtellgħin]]. Fajls imtellgħin u verżjonijiet ġodda tal-fajls huma reġistrati fir-[[Special:Log/upload|reġistru tat-tlugħ tal-fajls]], u dawk li tħassru huma fir-[[Special:Log/delete|reġistru tat-tħassir]].

Biex tinkludi fajl f'paġna, uża ħolqa taħt waħda minn dawn il-forom:
* '''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:Fajl.jpg]]</nowiki></tt>''' sabiex tuża' l-verżjoni sħiħa tal-fajl
* '''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:Fajl.png|200px|thumb|left|test alternattiv]]</nowiki></tt>''' sabiex tpoġġi l-istampa fuq ix-xellug ġo kaxxa ta' 200px b'\"test alternattiv\" tkun id-deskrizzjoni
* '''<tt><nowiki>[[</nowiki>{{ns:media}}<nowiki>:Fajl.ogg]]</nowiki></tt>''' biex toħloq ħolqa diretta lejn il-fajl, mingħajr ma turih.",
'upload-permitted'            => "Tipi ta' fajls permessi: $1.",
'upload-preferred'            => "Tipi ta' fajls preferuti: $1.",
'upload-prohibited'           => "Tipi ta' fajls projibiti: $1.",
'uploadlog'                   => 'Reġistru tal-uploads',
'uploadlogpage'               => "Reġistru tal-fajls li ġew imtella'",
'uploadlogpagetext'           => "Ħawn taħt tinsab il-lista ta' l-aktar fajls imtellgħin riċenti.<br />
Ara l-[[Special:NewFiles|gallerija ta' fajls ġodda]] għal ħarsa viżiva.",
'filename'                    => 'Isem tal-fajl',
'filedesc'                    => 'Taqsira',
'fileuploadsummary'           => 'Taqsira:',
'filereuploadsummary'         => 'Tibdil fil-fajl:',
'filestatus'                  => 'Informazzjoni dwar il-copyright:',
'filesource'                  => 'Sors:',
'uploadedfiles'               => "Fajls li ġew mtella'",
'ignorewarning'               => 'Injora twissiji u modifika l-fajl xorta waħda',
'ignorewarnings'              => 'Injora kull twissija',
'minlength1'                  => "L-ismijiet tal-fajls għandhom ikunu ta' l-inqas ittra waħda.",
'illegalfilename'             => 'L-Isem tal-fajl "$1" għandu karattri li mhux permessi fit-titli ta\' paġna. Jekk jogħġbok agħti isem ġdid lil fajl u prova tellgħu mill-ġdid.',
'badfilename'                 => 'Isem il-fajl ġie mibdul għal "$1".',
'filetype-badmime'            => "Mhux permess li fajls jiġu ''uploaded'' ta' tip MIME \"\$1\".",
'filetype-unwanted-type'      => "'''\".\$1\"''' huwa tip ta' fajl mhux rikjest.
{{PLURAL:\$3|Tip ta' fajl preferut huwa|Tipi ta' fajl preferuti huma}} \$2.",
'filetype-banned-type'        => "'''\".\$1\"''' mhux tip ta' fajl permess.
{{PLURAL:\$3|Tip ta' fajl permess huwa|Tipi ta' fajl permessi huma}} \$2.",
'filetype-missing'            => 'Il-Fajl m\'għandux estenżjoni (bħal ".jpg").',
'large-file'                  => 'Huwa suġġerit li l-fajls ma jkunux akbar minn $1;
dan il-fajl huwa $2 kbir.',
'largefileserver'             => 'Il-Fajl għandu dimenżjoni akbar minn dak konsentit mill-konfigurazzjoni tas-server.',
'emptyfile'                   => "Il-Fajl li ġie ''uploaded'' jidher li huwa vojt. Dan jista' jkun minħabba żball fl-isem tal-fajl.
Jekk jogħġbok verifika jekk xorta waħda trid itella' dan il-fajl.",
'fileexists'                  => "Fajl b'dan l-isem ġa jeżisti, jekk jogħġbok verifika l-ewwel '''<tt>[[:$1]]</tt>''' jekk ma tridx tikteb fuqu.
[[$1|thumb]]",
'filepageexists'              => "Il-paġna ta' deskrizzjoni għal dan il-fajl diġà ġiet maħluqa f''''<tt>[[:$1]]</tt>''', iżda l-ebda fajl b'dan l-isem ma jeżisti. It-taqsira li daħħalt mhux se tidher fuq il-paġna ta' deskrizzjoni.
Sabiex it-taqsira tidher fuq il-paġna ta' deskrizzjoni, huwa neċessarju li timmodifikaha manwalment.
[[$1|thumb]]",
'fileexists-extension'        => "Diġà jeżisti fajl b'isem simili: [[$2|thumb]]
* L-isem tal-fajl imtella': '''<tt>[[:$1]]</tt>'''
* L-isem tal-fajl eżistenti: '''<tt>[[:$2]]</tt>'''
Jekk jogħġbok, agħżel isem differenti.",
'fileexists-thumbnail-yes'    => "Il-fajl li ttella' jidher li huwa stampa żgħira ''(minjatura)''. [[$1|thumb]]
Jekk jogħġbok, iċċekkja dan il-fajl '''<tt>[[:$1]]</tt>'''. 
Jekk il-fajl li ċċekkjajt huwa l-istess stampa fid-daqs oriġinali, m'hemmx bżonn li ttella' minjatura oħra.",
'file-thumbnail-no'           => "L-isem tal-fajl jibda' b''''<tt>$1</tt>'''. Jidher ukoll li din hija stampa tad-daqs imnaqqas ''(thumbnail)''.<br />
Jekk għandek din l-istampa ta' riżoluzzjoni sħiħa, jekk jogħġbok, tella' dan il-fajl jew inkella immodifika l-isem tal-fajl.",
'fileexists-forbidden'        => "Fajl b'dan l-isem diġà jeżisti.<br />
Jekk jogħġbok mur lura u tella' dan il-fajl b'isem ġdid. [[File:$1|thumb|center|$1]]",
'fileexists-shared-forbidden' => "Fajl b'dan l-isem diġà jeżisti fl-arkivju tar-riżorsi multimedjali maqsuma. Jekk tixtieq xorta waħda li ttella' l-fajl, mur lura u tella' fajl b'isem ġdid. [[File:$1|thumb|center|$1]]",
'file-exists-duplicate'       => "Dan il-fajl huwa duplikat ta' {{PLURAL:$1|dan il-fajl|dawn il-fajls}} segwenti:",
'file-deleted-duplicate'      => "Fajl identiku għal dan ([[$1]]) ġie mħassar fil-passat. Iċċekja qabel xejn il-kronoloġija tat-tħassir qabel ma tkompli ttella' l-fajl.",
'successfulupload'            => "Mtella' b'suċċess",
'uploadwarning'               => 'Twissija dwar it-tlugħ tal-fajls',
'savefile'                    => 'Salva l-fajl',
'uploadedimage'               => 'tella\' "[[$1]]"',
'overwroteimage'              => 'Verżjoni ġdida ġiet imtella\' "[[$1]]"',
'uploaddisabled'              => "Skuzana, però ''uploads'' ta' fajls huwa temporalment sospiż.",
'uploaddisabledtext'          => "It-tlugħ ta' fajls mhuwiex attiv.",
'uploadscripted'              => "Dan il-fajl fih kodiċi ta' ''HTML'' u ''script'' li jista' jkun interpretat hażin mill-''web browser''.",
'uploadvirus'                 => "Dan il-fajl huwa infettat b'virus! Dettalji: $1",
'upload-source'               => 'Sors tal-fajl',
'sourcefilename'              => 'L-isem tal-fajl tal-oriġini:',
'sourceurl'                   => 'Sors tal-URL:',
'destfilename'                => 'L-Isem tal-fajl tad-destinazzjoni:',
'upload-maxfilesize'          => 'Daqs massimu tal-fajl: $1',
'upload-description'          => 'Deskrizzjoni tal-fajl',
'upload-options'              => 'Opzjonijiet għat-tlugħ tal-fajl',
'watchthisupload'             => 'Segwi dan il-fajl',
'filewasdeleted'              => "Fajl b'dan l-isem kien itella' diġa u wara ġie mħassar.
Inti għandek tiverifika ir-$1 qabel ma tkompli bl-''upload'' mill-ġdid.",
'upload-wasdeleted'           => "'''Twissija: Il-Fajl li qiegħed itella' kien imħassar.'''

Verifika jekk jogħġbok jekk m'għandhekx tkompli itella' dan il-fajl. Ir-Reġistru ta' tħassir għal dan il-fajl huwa provdut għal konvenjenza:",
'filename-bad-prefix'         => "L-Isem tal-fajl li qiegħed itella' jibda' b''''\"\$1\"''', 
li huma isem mhux deskrittiv u huwa tipikament mogħti awtomatikament minn kameras diġitali. Jekk jogħġbok agħżel isem għal fajl tiegħek aktar deskrittiv.",
'filename-prefix-blacklist'   => ' #<!-- ħalli din il-linja eżattament kif inhi --> <pre>
# Is-Sintassi huwa dan segwenti:
#   * Kollox mill-karattru "#" sa l-aħħar tal-linja tal-kumment
#   * Kull linja li mhux vojta huwa prefiss għal ismijiet ta\' fajl tipiċi li jiġu mogħtija minn kameras diġitali
CIMG # Casio
DSC_ # Nikon
DSCF # Fuji
DSCN # Nikon
DUW # ċerta mobiles
IMG # generic
JD # Jenoptik
MGP # Pentax
PICT # misc.
 #</pre> <!-- ħalli din il-linja eżattament kif inhi -->',
'upload-successful-msg'       => 'Il-fajl tiegħek huwa disponibbli hawnhekk: $1',
'upload-failure-subj'         => 'Problem fit-tlugħ tal-fajl',

'upload-proto-error'      => 'Protocol ħażin',
'upload-proto-error-text' => "Għal upload remote huwa neċessarju tispeċifika l-URL li jibda' b'<code>http://</code> jew <code>ftp://</code>.",
'upload-file-error'       => 'Problema interna',
'upload-file-error-text'  => "Kien hemm problema interna waqt il-ħolqien ta' fajl temporanju fuq is-server.<br />
Jekk jogħġbok ikkuntatja lil xi [[Special:ListUsers/sysop|amministratur]].",
'upload-misc-error'       => 'Problema tal-upload mhux magħrufa',
'upload-misc-error-text'  => "Waqt li l-fajl kien qed jittella', ġiet verifikata problema mhux magħrufha.<br />
Ivverifika li l-URL huwa validu u aċċessibbli, u erġa' pprova.<br />
Jekk il-problema tkompli tippersisti, ikkuntatja lil xi [[Special:ListUsers/sysop|amministratur]].",
'upload-unknown-size'     => 'Dimensjoni mhux magħrufa',

# img_auth script messages
'img-auth-accessdenied' => 'Aċċess miċħud',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6'       => 'URL mhux tajjeb',
'upload-curl-error6-text'  => 'Il-URL provdut mhux tajjeb.
Jekk jogħġbok verifika li l-URL hija miktuba tajjeb u is-sit huwa attiv.',
'upload-curl-error28'      => 'Ħin skadut għall-upload',
'upload-curl-error28-text' => "Is-Sit dam wisq sabiex jirispondi.
Jekk jogħġbok verifika li s-sit huwa attiv, stenna għal ftit u erġa' prova mill-ġdid, l-aħjar f'mument meta jkun hemm inqas traffiku.",

'license'            => "Liċenzja t'użu:",
'nolicense'          => 'L-Ebda liċenzja indikata',
'license-nopreview'  => '(Dehra proviżorja mhix disponibbli)',
'upload_source_url'  => ' (URL validu u aċċessibli)',
'upload_source_file' => ' (fajl fuq il-komputer tiegħek)',

# Special:ListFiles
'listfiles-summary'     => "Din il-paġna speċjali turi l-fajls kollha mtella'.
L-aktar ''uploads'' riċenti jiġu fuq in-naħa ta' fuq tal-lista.
Biex tagħmel modifika fl-ordni tal-kolonna, klikkja fuq it-titlu tal-kolonna stess.",
'listfiles_search_for'  => 'Fittex stampi skont l-isem:',
'imgfile'               => 'fajl',
'listfiles'             => 'Lista tal-fajl',
'listfiles_date'        => 'Data',
'listfiles_name'        => 'Isem',
'listfiles_user'        => 'Utent',
'listfiles_size'        => 'Daqs',
'listfiles_description' => 'Deskrizzjoni',
'listfiles_count'       => 'Verżjonijiet',

# File description page
'file-anchor-link'          => 'Fajl',
'filehist'                  => 'Kronoloġija tal-fajl',
'filehist-help'             => 'Agħfas fuq il-grupp data/ħin biex tara l-fajl biex tara kif jidher dak il-ħin.',
'filehist-deleteall'        => 'ħassar kollox',
'filehist-deleteone'        => 'ħassar',
'filehist-revert'           => 'revertja',
'filehist-current'          => 'kurrenti',
'filehist-datetime'         => 'Data/Ħin',
'filehist-thumb'            => 'Minjatura',
'filehist-thumbtext'        => "Minjatura tal-verżjoni ta' $1",
'filehist-user'             => 'Utent',
'filehist-dimensions'       => 'Qisien',
'filehist-filesize'         => 'Daqs tal-fajl',
'filehist-comment'          => 'Kumment',
'imagelinks'                => 'Ħoloq għall-fajl',
'linkstoimage'              => '{{PLURAL:$1|Il-Paġna segwenti għandha|Il-$1 paġni segwenti għandhom}} links għal-fajl:',
'nolinkstoimage'            => "M'hemmx paġni li huma relatati ma' dan il-fajl.",
'morelinkstoimage'          => 'Uri [[Special:WhatLinksHere/$1|aktar links]] għal dan il-fajl.',
'redirectstofile'           => '{{PLURAL:$1|Il-fajl segwenti huwa rindirizzat|Il-$1 fajls segwenti huma rindirizzati}} għal dan il-fajl.',
'duplicatesoffile'          => "{{PLURAL:$1|Il-fajl segwenti huwa duplikat|Il-$1 fajls segwenti huma duplikati}} ta' dan il-fajl ([[Special:FileDuplicateSearch/$2|aktar dettalji]]):",
'sharedupload'              => "Dan il-fajl ġej minn $1 u jista' jiġi wżat minn proġetti oħra.",
'uploadnewversion-linktext' => "Tella' verżjoni ġdida ta' dan il-fajl",
'shared-repo-from'          => 'minn $1',
'shared-repo'               => 'repożitorju maqsum',

# File reversion
'filerevert'                => 'Ġib lura $1',
'filerevert-legend'         => "Erġa' lura għall-fajl",
'filerevert-intro'          => "Inti qiegħed terġa lura għal fajl '''[[Media:$1|$1]]''' fil-[verżjoni $4 minn $3, $2].",
'filerevert-comment'        => 'Raġuni:',
'filerevert-defaultcomment' => "Mort lura għal verżjoni ta' $2, $1",
'filerevert-submit'         => 'Ġib lura',
'filerevert-success'        => "'''Il-Fajl [[Media:$1|$1]]''' ġie restorat għal [verżjoni $4 minn $3, $2].",
'filerevert-badversion'     => "M'hemmx verżjoni lokali tal-fajl aktar riċenti b'timbru tal-ħin rikjest.",

# File deletion
'filedelete'                  => 'Ħassar $1',
'filedelete-legend'           => 'Ħassar il-fajl',
'filedelete-intro'            => "Sejjer tħassar '''[[Media:$1|$1]]''' flimkien mal-kronoloġija kollha tiegħu.",
'filedelete-intro-old'        => "Se tħassar il-verżjoni ta' '''[[Media:$1|$1]]''' - [$4 $3, $2].",
'filedelete-comment'          => 'Raġuni għat-tħassir:',
'filedelete-submit'           => 'Ħassar',
'filedelete-success'          => "'''$1''' ġie mħassar.",
'filedelete-success-old'      => "Il-verżjoni tal-fajl '''[[Media:$1|$1]]''' tal-$2, $3 ġiet mħassra.",
'filedelete-nofile'           => "'''$1''' ma jeżistix.",
'filedelete-nofile-old'       => "Fl-Arkivju m'hemmx verżjoni ta' '''$1''' bil-karrateristiċi indikati.",
'filedelete-otherreason'      => 'Raġuni oħra/addizzjonali:',
'filedelete-reason-otherlist' => 'Raġuni oħra',
'filedelete-reason-dropdown'  => "*Raġunijiet aktar komuni dwar tħassir
** Vjolazzjoni ta' copyright
** Fajl duplikat",
'filedelete-edit-reasonlist'  => 'Immodifika r-raġunijiet għat-tħassir',

# MIME search
'mimesearch'         => 'Fittex fil-bażi għal tip MIME',
'mimesearch-summary' => "Din il-paġna tħalli filtrar ta' fajls fil-bażi ta' tip MIME.
Daħħal: tip/subtip, e.ż. <tt>image/jpeg</tt>.",
'mimetype'           => 'Tip MIME:',
'download'           => 'niżżel',

# Unwatched pages
'unwatchedpages' => 'Paġni mhux osservati',

# List redirects
'listredirects' => "Lista ta' riindirizzi",

# Unused templates
'unusedtemplates'     => 'Templates mhux użati',
'unusedtemplatestext' => "F'din il-paġna hawn il-lista ta' paġni fl-ispazju tal-isem {{ns:template}} li mhumiex inklużi fl-ebda paġna. Qabel ma tħassarhom huwa opportun li tivverifika li dawn il-mudelli m'għandhomx ħoloq oħra.",
'unusedtemplateswlh'  => 'links oħrajn',

# Random page
'randompage'         => 'Paġna kwalunkwe',
'randompage-nopages' => "M'hemm l-ebda paġna fl-{{PLURAL:$2|ispazju tal-isem|ispazji tal-isem}} segwenti: $1.",

# Random redirect
'randomredirect'         => 'Rindirizz kwalunkwe',
'randomredirect-nopages' => 'M\'hawnx riindirizzi fl-ispazju tal-isem "$1".',

# Statistics
'statistics'               => 'Statistika',
'statistics-header-pages'  => 'Statistika tal-paġna',
'statistics-header-edits'  => 'Statistika tal-immodifikar',
'statistics-header-views'  => 'Statistika tal-viżwalizzazzjoni',
'statistics-header-users'  => 'Statistika tal-utent',
'statistics-header-hooks'  => 'Statistika oħra',
'statistics-articles'      => "Paġni ta' kontenut",
'statistics-pages'         => 'Paġni',
'statistics-pages-desc'    => "Il-paġni kollha tal-wiki, inklużi l-paġni ta' diskussjoni, ir-riindirizzi, etċ.",
'statistics-files'         => 'Fajls imtellgħa',
'statistics-edits'         => "Total ta' modifiki minn mindu {{SITENAME}} bdiet fil-funzjon",
'statistics-edits-average' => "Medja ta' modifiki għal kull paġna",
'statistics-views-total'   => "Total ta' viżti",
'statistics-views-peredit' => 'Viżti għal kull modifika',
'statistics-users'         => '[[Special:ListUsers|Utenti]] reġistrati',
'statistics-users-active'  => 'Utenti attivi',
'statistics-mostpopular'   => 'Il-paġni l-aktar miżjura',

'disambiguations'      => "Paġni ta' diżambigwazzjoni",
'disambiguationspage'  => 'Template:diżambig',
'disambiguations-text' => "Il-Paġni li jinsabu f'din lista huma parti minn '''paġna ta' diżambigwazzjoni''' b'hekk għandhom jiġu relatati mas-suġġett preċiż minflok. <br />
Paġna tiġi stimata paġna ta' diżambigwazzjoni dawk kollha li jagħmlu użu mit-template elenkat f'[[MediaWiki:Disambiguationspage]]",

'doubleredirects'            => 'Riindirizzi doppji',
'doubleredirectstext'        => 'Din il-paġna telenka dawk il-paġni li jindirizzaw lejn paġna oħra ta\' riindirizzament.
Kull filliera għandha ħolqa għall-ewwel u t-tieni riindirizz, kif ukoll fejn tirrindirizza t-tieni paġna, is-soltu magħrufa bħalha l-paġna "reali" fejn se twassal, fejn suppost l-ewwel riindirizz għandu jipponta.',
'double-redirect-fixed-move' => '[[$1]] ġie mmexxi awtomatikament, issa hu rindirizz għal [[$2]]',

'brokenredirects'        => 'Riindirizzi ħżiena',
'brokenredirectstext'    => 'Ir-riindirizzi segwenti għandhom ħoloq għal paġni ineżistenti:',
'brokenredirects-edit'   => 'editja',
'brokenredirects-delete' => 'ħassar',

'withoutinterwiki'         => 'Paġni bla interwiki',
'withoutinterwiki-summary' => "Il-paġni segwenti m'għandhomx links għal verżjonijiet ta' lingwi oħrajn:",
'withoutinterwiki-legend'  => 'Prefiss',
'withoutinterwiki-submit'  => 'Uri',

'fewestrevisions' => 'Paġni bl-inqas reviżjonijiet',

# Miscellaneous special pages
'nbytes'                  => '{{PLURAL:$1|byte|$1  bytes}}',
'ncategories'             => '{{PLURAL:$1|kategorija|$1  kategoriji}}',
'nlinks'                  => '{{PLURAL:$1|link|$1 links}}',
'nmembers'                => '{{PLURAL:$1|membru|$1 membri}}',
'nrevisions'              => '{{PLURAL:$1|reviżjoni|$1 reviżjonijiet}}',
'nviews'                  => '{{PLURAL:$1|visita|$1 visiti}}',
'specialpage-empty'       => 'Dan ir-rapport ma fih l-ebda riżultat.',
'lonelypages'             => 'Paġni orfni',
'lonelypagestext'         => "Il-paġni segwenti m'għandhomx ħoloq ġejjin minn paġni oħra ta' {{SITENAME}} u mhumiex inklużi f'ebda paġna tas-sit.",
'uncategorizedpages'      => 'Paġni mhux ikkategorizzati',
'uncategorizedcategories' => 'Kategoriji mhux ikkategorizzati',
'uncategorizedimages'     => 'Fajl mhux kategorizati',
'uncategorizedtemplates'  => 'Templates mhux ikkategorizzati',
'unusedcategories'        => 'Kategoriji mhux użati',
'unusedimages'            => 'Fajls mhux użati',
'popularpages'            => 'L-iktar paġni popolari',
'wantedcategories'        => 'Kategoriji rikjesti',
'wantedpages'             => 'Paġni rikjesti',
'wantedpages-badtitle'    => "Titlu invalidu fil-grupp ta' riżultati: $1",
'mostlinked'              => "Paġni bl-ikbar numru ta' links li jwasslu għalihom",
'mostlinkedcategories'    => "Kategoriji bl-ikbar numru ta' links li jwasslu għalihom",
'mostlinkedtemplates'     => 'L-iktar mudelli wżati',
'mostcategories'          => "Paġni bl-ikbar numru ta' kategoriji",
'mostimages'              => "Fajls bl-ikbar numru ta' links li jwasslu għalihom",
'mostrevisions'           => "Paġni bl-ikbar numru ta' reviżjonijiet",
'prefixindex'             => 'Il-paġni kollha bil-prefiss',
'shortpages'              => 'Paġni qosra',
'longpages'               => 'Paġni twal',
'deadendpages'            => 'Paġni bla ħruġ',
'deadendpagestext'        => "Il-Paġni segwenti m'għandhomx link għal paġna oħra.",
'protectedpages'          => 'Paġni protetti',
'protectedpages-indef'    => 'Protezzjoni indefinit biss',
'protectedpagestext'      => 'Il-Paġni segwenti huma protetti minn modifiki u ċaqlieq',
'protectedpagesempty'     => "M'hawnx paġni protetti bħalissa b'dawn il-parametri.",
'protectedtitles'         => 'Titli protetti',
'protectedtitlestext'     => 'It-Titli segwenti huma protetti mill-ħolqien',
'protectedtitlesempty'    => "L-Ebda titli bħalissa huma protetti b'dawn il-parametri.",
'listusers'               => 'Lista tal-utenti',
'usereditcount'           => '$1 {{PLURAL:$1|kontribuzzjonijiet|kontribuzzjoni}}',
'newpages'                => 'Paġni ġodda',
'newpages-username'       => 'Isem tal-utent:',
'ancientpages'            => 'L-iktar paġni qodma',
'move'                    => 'Mexxi',
'movethispage'            => 'Ċaqlaq din il-paġna',
'unusedimagestext'        => "F'din il-lista hemm preżenti l-fajls imtellgħin imma li mhumiex użati f'ebda paġna.
Kun af li siti elettroniċi oħra jistgħu jorbtu b'ħolqa diretta lejn il-fajl, u għalhekk xorta jistgħu jiġu elenkati hawnhekk wżati minkejja li għandhom użu attiv.",
'unusedcategoriestext'    => 'Il-paġni tal-kategoriji segwenti jeżistu, għalkemm ma teżisti l-ebda paġna jew kategorija li tagħmel użu minnhom.',
'notargettitle'           => 'L-Ebda tarka',
'notargettext'            => 'Ma ġiet speċifikata l-ebda paġna jew utent fuqiex din il-funzjoni se ssir.',
'nopagetitle'             => "Il-paġna ta' destinazzjoni ma teżistix",
'nopagetext'              => "Il-paġna ta' destinazzjoni li speċifikajt ma teżistix.",
'pager-newer-n'           => '{{PLURAL:$1|l-aktar riċenti|$1 l-aktar riċenti}}',
'pager-older-n'           => '{{PLURAL:$1|l-inqas riċenti|$1 l-inqas riċenti}}',
'suppress'                => 'Oversight',

# Book sources
'booksources'               => 'Sorsi tal-kotba',
'booksources-search-legend' => 'Fittex għal sorsi tal-kotba',
'booksources-isbn'          => 'Kodiċi ISBN:',
'booksources-go'            => 'Mur',
'booksources-text'          => "Hawn taħt hawn lista ta' ħoloq għal siti oħrajn li jbiegħu kotba ġodda u wżati, u jistgħu jkollhom aktar informazzjoni dwar il-kotba li qiegħed tfittex:",
'booksources-invalid-isbn'  => 'L-ISBN li ngħata jidher li mhuwiex validu; iċċekkja għal xi żbalji mis-sors oriġinali.',

# Special:Log
'specialloguserlabel'  => 'Utent:',
'speciallogtitlelabel' => 'Titlu:',
'log'                  => 'Reġistri',
'all-logs-page'        => 'Ir-reġistri pubbliċi kollha',
'alllogstext'          => "Preżentazzjoni unifikata tar-reġistri kollha ta' {{SITENAME}}.<br />
Tista' tqassar il-kriterji ta' tfittxija billi tagħżel it-tip ta' log, l-isem tal-utent, jew il-paġna affetwata (it-tnejn tal-aħħar huma sensittivi għal kif jinkitbu l-karattri).",
'logempty'             => "Ir-reġistru m'għandu ebda element korrispondenti mat-tfittxija tiegħek.",
'log-title-wildcard'   => "Tfittxija ta' titli li jibdew b'dan it-test",

# Special:AllPages
'allpages'          => 'Il-paġni kollha',
'alphaindexline'    => 'minn $1 sa $2',
'nextpage'          => 'Il-paġna li jmiss ($1)',
'prevpage'          => "Il-paġna ta' qabel ($1)",
'allpagesfrom'      => 'Uri l-paġni li jibdew minn:',
'allpagesto'        => "Uri l-paġni li jispiċċaw b':",
'allarticles'       => 'Il-paġni kollha',
'allinnamespace'    => 'Il-paġni kollha fl-ispazju tal-isem $1',
'allnotinnamespace' => 'Il-paġni kollha, minbarra dawk fl-ispazju tal-isem $1',
'allpagesprev'      => "Ta' qabel",
'allpagesnext'      => 'Li jmiss',
'allpagessubmit'    => 'Mur',
'allpagesprefix'    => 'Uri l-paġni bil-prefiss:',
'allpagesbadtitle'  => "It-titlu indikat għal dil-paġna mhuwiex validu jew inkella fih xi prefiss interlingwa jew interwiki. Għaldaqstant, jista' ikun fih xi karratru(i) li ma jistgħux jintużaw fit-titli.",
'allpages-bad-ns'   => 'In-namespace "$1" ma jeżistix fuq {{SITENAME}}.',

# Special:Categories
'categories'                    => 'Kategoriji',
'categoriespagetext'            => 'Il-{{PLURAL:$1|kategorija segwenti għandha|kategoriji segwenti għandhom}} paġni jew fajls multimedjali.<br />
Il-[[Special:UnusedCategories|kategoriji vojta]] ma jidhrux hawnhekk.
Ara wkoll il-[[Special:WantedCategories|kategoriji rikjesti]].',
'categoriesfrom'                => 'Uri kategoriji minn:',
'special-categories-sort-count' => 'irranġa skont in-numru',
'special-categories-sort-abc'   => 'irranġa skont l-alfabett',

# Special:DeletedContributions
'deletedcontributions'             => 'Kontribuzzjonijiet imħassra tal-utent',
'deletedcontributions-title'       => 'Kontribuzzjonijiet imħassra tal-utent',
'sp-deletedcontributions-contribs' => 'kontribuzzjonijiet',

# Special:LinkSearch
'linksearch'      => 'Ħoloq esterni',
'linksearch-ns'   => 'Spazju tal-isem:',
'linksearch-ok'   => 'Fittex',
'linksearch-line' => '$1 hija marbuta mill-paġna $2',

# Special:ListUsers
'listusersfrom'      => 'Uri utenti li jibdew minn:',
'listusers-submit'   => 'Uri',
'listusers-noresult' => 'l-Ebda utent insab għal din il-kriterja.',

# Special:ActiveUsers
'activeusers'            => 'Lista tal-utenti attivi',
'activeusers-intro'      => "Din hija lista ta' utenti li kellhom xi tip ta' attività f'dawn l-aħħar $1 {{PLURAL:$1|ġurnata|ġurnata}}.",
'activeusers-count'      => '$1 {{PLURAL:$1|modifika|modifika}} fl-aħħar {{PLURAL:$3|jum|$3 jum}}',
'activeusers-from'       => 'Uri utenti li jibdew minn:',
'activeusers-hidebots'   => 'Aħbi l-bots',
'activeusers-hidesysops' => 'Aħbi amministraturi',

# Special:Log/newusers
'newuserlogpage'              => 'Utenti ġodda',
'newuserlogpagetext'          => "Dan hu reġistru tal-kreazzjoni ta' kontijiet ġodda.",
'newuserlog-byemail'          => "il-password intbagħtet permezz ta' posta elettronika",
'newuserlog-create-entry'     => 'Utent ġdid',
'newuserlog-create2-entry'    => 'irreġistra l-isem tal-utent ġdid $1',
'newuserlog-autocreate-entry' => 'Kont maħluq awtomatikament',

# Special:ListGroupRights
'listgrouprights'          => 'Drittijiet tal-grupp tal-utenti',
'listgrouprights-summary'  => "Hawn taħt hawn elenkati l-gruppi tal-utenti għal din il-wiki, bid-drittijiet ta' aċċess rispettiv.
Jista' jkun hemm [[{{MediaWiki:Listgrouprights-helppage}}|aktar informazzjoni]] fuq id-drittjiet individwali.",
'listgrouprights-key'      => '* <span class="listgrouprights-granted">Dritt mogħti</span>
* <span class="listgrouprights-revoked">Dritt revokat</span>',
'listgrouprights-group'    => 'Grupp',
'listgrouprights-rights'   => 'Drittijiet',
'listgrouprights-helppage' => 'Help:Drittijiet tal-grupp',
'listgrouprights-members'  => "(lista ta' membri)",

# E-mail user
'mailnologin'     => 'L-Ebda indirizz tal-posta',
'mailnologintext' => "Sabiex tkun tista' tibgħat posta elettronika 'l utenti oħrajn huwa neċessarju li [[Special:UserLogin|tidħol fis-sit]] bħalha utent reġistrat u jkollhok indirizz validu fil-[[Special:Preferences|preferenzi]] tiegħek.",
'emailuser'       => 'Ikteb lil dan l-utent',
'emailpage'       => 'Ibgħat messaġġ lil dan l-utent bil-posta elettronika',
'emailpagetext'   => "Huwa possibbli li tuża' l-formola t'hawn taħt biex tibgħat posta elettronika għal dan l-utent. L-indirizz li daħħalt fil-[[Special:Preferences|preferenzi]] jidher fl-ispazju \"Minn:\" tal-messaġġ, biex dak li jirċievi l-messaġġ ikun jista' jagħtik risposta.",
'usermailererror' => 'L-oġġett tal-posta ta l-problema:',
'defemailsubject' => 'Messaġġ minn {{SITENAME}}',
'noemailtitle'    => 'L-Ebda indirizz tal-posta elettronika',
'noemailtext'     => 'Dan l-utent ma daħħalx indirizz tal-posta elettronika valida.',
'emailfrom'       => 'Minn:',
'emailto'         => 'Lil:',
'emailsubject'    => 'Suġġett:',
'emailmessage'    => 'Messaġġ:',
'emailsend'       => 'Ibgħat',
'emailccme'       => 'Ibgħatli kopja tal-messaġġ tiegħi.',
'emailccsubject'  => 'Kopja tal-messaġġ tiegħek lil $1: $2',
'emailsent'       => 'Il-messaġġ intbagħat',
'emailsenttext'   => 'Il-messaġġ bil-posta elettronika intbagħat.',
'emailuserfooter' => 'Din il-posta elettronika intbgħattet minn $1 lil $2 bil-"Utent tal-posta elettronika" funżjoni ta\' {{SITENAME}}.',

# Watchlist
'watchlist'            => "Lista ta' osservazzjoni tiegħi",
'mywatchlist'          => 'li qed insegwi',
'watchlistfor'         => "(għal '''$1''')",
'nowatchlist'          => "Il-lista ta' osservazzjoni tiegħek hija vojta.",
'watchlistanontext'    => "Sabiex tara u timmodifika l-lista ta' osservazzjoni tiegħek, hemm bżonn li $1.",
'watchnologin'         => 'Għadek ma dħaltx ġewwa',
'watchnologintext'     => "Biex tagħmel modifika fil-lista t'osservazzjoni speċjali huwa neċessarju li l-ewwel [[Special:UserLogin|tidħol]] fil-kont tiegħek.",
'addedwatch'           => "Il-paġna ġiet miżjuda mal-lista ta' osservazzjoni",
'addedwatchtext'       => "Il-paġna \"[[:\$1]]\" ġiet miżjuda mal-[[Special:Watchlist|lista ta' osservazzjoni]] tiegħek.
Minn issa 'l quddiem, il-modifiki f'din il-paġna u fil-paġna ta' diskussjoni tagħha jiġu rreġistrati hawnhekk, u l-paġna tibda tidher b'tipa '''ħoxna''' fil-[[Special:RecentChanges|lista ta' modifiki riċenti]] sabiex tinsab iktar faċilment.

Jekk f'xi ħin tkun tixtieq tneħħi l-paġna mil-lista ta' osservazzjoni tiegħek, kemm tagħfas fuq \"tibqax issegwi\" li tinsab fl-iżbarra ta' fuq.",
'removedwatch'         => "Il-paġna tneħħiet mil-lista ta' osservazzjoni",
'removedwatchtext'     => 'Il-paġna "[[:$1]]" tneħħiet mil-[[Special:Watchlist|lista ta\' osservazzjoni tiegħek]].',
'watch'                => 'Segwi',
'watchthispage'        => 'Segwi din il-paġna',
'unwatch'              => 'Issegwix',
'unwatchthispage'      => 'Ieqaf osserva',
'notanarticle'         => 'Din il-paġna mhux artiklu',
'notvisiblerev'        => 'Ir-reviżjoni tħassret',
'watchnochange'        => 'L-ebda waħda mill-paġni osservati tiegħek ma ġiet modifikata fil-ħin stipulat.',
'watchlist-details'    => "Il-lista ta' osservazzjoni fiha {{PLURAL:$1|paġna waħda (u l-paġna ta' diskussjoni tagħha)|$1 paġni (u l-paġni ta' diskussjoni tagħhom)}}.",
'wlheader-enotif'      => '* In-notifikazzjoni bl-użu tal-posta elettronika hija attivata.',
'wlheader-showupdated' => "* Il-paġni li ġew editjati wara l-aħħar żjara tiegħek qed jiġu murija b'tipa '''ħoxna'''",
'watchmethod-recent'   => "Kontroll ta' modifiki riċenti għall-osservati speċjali.",
'watchmethod-list'     => 'Kontroll tal-osservati speċjali għal modifiki riċenti',
'watchlistcontains'    => "Il-lista ta' osservazzjoni fiha {{PLURAL:$1|paġna|$1 paġni}}.",
'iteminvalidname'      => "Problema bil-paġna'$1', l-isem mhux validu...",
'wlnote'               => "Hawn taħt hawn {{PLURAL:$1|l-aħħar modifika|l-aħħar '''$1''' modifiki}} fl-aħħar {{PLURAL:$2|siegħa|'''$2''' siegħat}}.",
'wlshowlast'           => 'Uri l-aħħar $1 siegħat $2 ġranet $3',
'watchlist-options'    => "Opzjonijiet tal-lista ta' osservazzjoni",

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'Imsegwi...',
'unwatching' => 'Mhux osservat aktar...',

'enotif_mailer'                => "Sistema ta' notifikazzjoni bl-użu tal-posta elettronika fuq {{SITENAME}}",
'enotif_reset'                 => 'Marka l-paġni kollha viżitati',
'enotif_newpagetext'           => 'Din hija paġna ġdida.',
'enotif_impersonal_salutation' => "Utent ta' {{SITENAME}}",
'changed'                      => 'modifikata',
'created'                      => 'inħolqot',
'enotif_subject'               => 'Il-Paġna $PAGETITLE ta\' {{SITENAME}} ġiet $CHANGEDORCREATED minn $PAGEEDITOR',
'enotif_lastvisited'           => 'Ara $1 għal modifiki kollha mill-aħħar żjara.',
'enotif_lastdiff'              => 'Ara $1 biex tara din l-modifika.',
'enotif_anon_editor'           => 'utent anonimu $1',
'enotif_body'                  => 'Għażiż $WATCHINGUSERNAME,

Il-paġna $PAGETITLE ta\' {{SITENAME}} ġiet $CHANGEDORCREATED nhar il-$PAGEEDITDATE minn $PAGEEDITOR; il-verżjoni kurrenti tinsab fl-indirizz $PAGETITLE_URL.

$NEWPAGE

Taqsira tal-editur: $PAGESUMMARY $PAGEMINOREDIT

Ikkuntatja \'l-editur:
ittra-e: $PAGEEDITOR_EMAIL
wiki: $PAGEEDITOR_WIKI

Mhux se jiġu mibgħuta notifiki oħra f\'każ ta\' aktar modifiki sakemm ma żżirx din il-paġna. Huwa possibbli li terġa\' tpoġġi l-avviż mill-ġdid għal paġni kollha fil-lista ta\' osservazzjoni speċjali.

                 Is-sistema ta\' notifika ta\' {{SITENAME}}, fis-servizz tiegħek 

--
Biex tbiddel l-impostazzjonijiet tal-lista ta\' osservazzjoni tiegħek, żur
{{fullurl:{{#special:Watchlist}}/edit}}

Biex tħassar il-paġna minn fuq il-lista ta\' osservazzjoni tiegħek, żur
$UNWATCHURL

Biex tagħti l-kumment tiegħek u biex tikseb aktar għajnuna:
{{fullurl:{{MediaWiki:Helppage}}}}',

# Delete
'deletepage'             => 'Ħassar il-paġna',
'confirm'                => 'Ikkonferma',
'excontent'              => "kontenut kien: '$1'",
'excontentauthor'        => "kontenut kien: '$1' (u l-unika kontributur kien '[[Special:Contributions/$2|$2]]')",
'exbeforeblank'          => "kontenut qabel ma ġie żvojtjat kien: '$1'",
'exblank'                => 'paġna kienet vojta',
'delete-confirm'         => 'Ħassar "$1"',
'delete-legend'          => 'Ħassar',
'historywarning'         => "'''Twissija:''' Il-paġna li se tħassar għandha kronoloġija b'madwar {{PLURAL:$1|reviżjoni waħda|$1 reviżjoni}}:",
'confirmdeletetext'      => "Int se tħassar għal kollox mid-database paġna jew stampa, flimkien mal-kronoloġija kollha tagħha. Jekk jogħġbok, ikkonferma li hija x-xewqa tiegħek li tkompli bit-tħassir ta' din il-paġna, u tifhem il-konsegwenzi ta' li qiegħed tagħmel, u li qiegħed tagħmilhom skont il- [[{{MediaWiki:Policy-url}}|politika]] stabbilita.",
'actioncomplete'         => 'Azzjoni mwettqa',
'deletedtext'            => 'Il-paġna "<nowiki>$1</nowiki>" ġiet imħassra.
Ikkonsulta r-$2 biex tara paġni li ġew imħassra riċentament.',
'deletedarticle'         => 'ħassar "[[$1]]"',
'suppressedarticle'      => 'Neħħi "[[$1]]"',
'dellogpage'             => 'Tħassir',
'dellogpagetext'         => "Hawn taħt hawn lista ta' l-aktar tħassir riċenti.",
'deletionlog'            => 'reġistru tat-tħassir',
'reverted'               => 'Mort lura għal verżjoni preċedenti',
'deletecomment'          => 'Raġuni dwar it-tħassir:',
'deleteotherreason'      => 'Raġunijiet oħra/addizzjonali:',
'deletereasonotherlist'  => 'Raġuni oħra',
'deletereason-dropdown'  => "*Raġunijiet ta' tħassir komuni
** Rikjesta tal-awtur
** Vjolazzjoni tal-copyright
** Vandaliżmu",
'delete-edit-reasonlist' => "Immodifika r-raġunijiet ta' tħassir",
'delete-toobig'          => "Din il-paġna għandha kronoloġija ta' modifikar kbira, l-fuq minn $1 {{PLURAL:$1|reviżjoni|reviżjonijiet}}.
Tħassir ta' dawn il-paġni huwa limitat sabiex tnaqqas il-ħolqien aċċidentalment ta' problemi fil-funżjoni tad-database ta' {{SITENAME}}.",
'delete-warning-toobig'  => "Din il-paġna għandha kronoloġija ta' modifikar kbira, l-fuq minn $1 {{PLURAL:$1|reviżjoni|reviżjonijiet}}.
Tħassara tista' toħloq problema ta' funżjoni fid-database ta' {{SITENAME}}; moħħok hemm.",

# Rollback
'rollback'         => 'Ħassar il-modifiki',
'rollback_short'   => 'Rollback',
'rollbacklink'     => 'rollback',
'rollbackfailed'   => 'Rollback ma ħadmitx',
'cantrollback'     => 'Impossibli tħassar il-modifiki; l-utent li wettaqhom huwa l-unika li għamel kontributi lil din il-paġna.',
'alreadyrolled'    => "Mhuwiex possibbli li tneħħi l-modifiki ta' [[User:$2|$2]] ([[User talk:$2|diskussjoni]]{{int:pipe-separator}}[[Special:Contributions/$2|{{int:contribslink}}]]) fil-paġna [[:$1]]; utent ieħor diġà immodifika din il-paġna jew inkella reġġa' lura.

L-iktar modifika riċenti fuq dil-paġna saret minn [[User:$3|$3]] ([[User talk:$3|diskussjoni]]).",
'editcomment'      => "It-taqsira tal-modifika kienet: \"''\$1''\".",
'revertpage'       => "Modifiki mneħħa minn [[Special:Contributions/$2|$2]] ([[User talk:$2|Talk]]) għall-aħħar verżjoni ta' [[User:$1|$1]]",
'rollback-success' => "Modifiki mneħħa ta' $1;
Modifikata lura għall-aħħar verżjoni ta' $2.",

# Edit tokens
'sessionfailure' => "Ġie verifikat problema fis-sessjoni tal-aċċess; din l-azzjoni ġiet imħassra bħalha prekawzjoni. Mur lura fil-paġna preċedenti bl-użu tal-buttuna 'Lura' tal-browser, niżżel il-paġna mill-ġdid u erġa' prova.",

# Protect
'protectlogpage'              => 'Protezzjoni',
'protectlogtext'              => "Hawn taħt hawn lista ta' l-azzjonijiet tal-protezzjoni u żblokki tal-paġna.
Ara [[Special:ProtectedPages|l-lista ta' paġni protetti]] għal lista ta' paġni bħalissa protetti.",
'protectedarticle'            => '"[[$1]]" huwa protett',
'modifiedarticleprotection'   => 'biddel il-livell ta\' protezzjoni għal "[[$1]]"',
'unprotectedarticle'          => 'żblokkjajt "[[$1]]"',
'protect-title'               => 'Modifika livell ta\' protezzjoni ta\' "$1"',
'prot_1movedto2'              => '[[$1]] tmexxa lejn [[$2]]',
'protect-legend'              => 'Ikkonferma l-protezzjoni',
'protectcomment'              => 'Raġuni:',
'protectexpiry'               => 'Jiskadi:',
'protect_expiry_invalid'      => 'Skadenza mhux valida.',
'protect_expiry_old'          => 'Skadenza ġa inġarbet.',
'protect-text'                => "Int tista' tara jew tagħmel modifiki fil-livell ta' protezzjoni hawnhekk għal paġna '''<nowiki>$1</nowiki>'''.",
'protect-locked-blocked'      => "Ma tistax tbiddel il-livell ta' protezzjoni waqt li tkun imblukkat. Il-preferenzi kurrenti għall-paġna '''$1''' huma:",
'protect-locked-dblock'       => "Livelli ta' protezzjoni ma jistgħux jiġu modifikata minħabba li database attiv huwa magħluq. Il-Preferenzi kurrenti ta' din il-paġna huma '''$1''':",
'protect-locked-access'       => "M'għandhekx il-permessi neċessarji biex tagħmel modifiki fil-livelli ta' protezzjoni ta' din il-paġna.
Il-Preferenzi kurrenti ta' din il-paġni huma '''$1''':",
'protect-cascadeon'           => "Din il-paġna hija bħalissa protetta minħabba li hija inkluża fil-{{PLURAL:$1|paġna segwenti li għanda|paġni segwenti li għandhom}} protezzjoni rikorsiva attiva. Huwa possibli li tagħmel modifiki fil-livell ta' protezzjoni individwali tal-paġna, però mhux se taffetwa l-protezzjoni rikorsiva.",
'protect-default'             => 'Awtorizza l-utenti kollha',
'protect-fallback'            => 'Huwa rikjest il-permess "$1"',
'protect-level-autoconfirmed' => 'Imblokka l-utenti ġodda u dawk li mhumiex reġistrati',
'protect-level-sysop'         => 'Amministraturi biss',
'protect-summary-cascade'     => 'rikorsiv',
'protect-expiring'            => 'jiskadi $1 (UTC)',
'protect-expiry-indefinite'   => 'indefinit',
'protect-cascade'             => "Protezzjoni rikorsiva (testendi l-protezzjoni 'l paġni kollha inklużi f'din il-paġna).",
'protect-cantedit'            => "Ma tistax timodifika l-livelli ta' protezzjoni ta' din il-paġna, għax int m'għandhekx il-permessi neċessarji.",
'protect-otherreason'         => 'Raġunijiet oħra/addizjonali:',
'protect-dropdown'            => '*Raġunijiet komuni għall-protezzjoni
** Vandaliżmu eċċessiv
** Spamming eċċessiv
** Gwerrer tal-editjar kontinwi
** Paġna wżata ħafna',
'protect-expiry-options'      => 'siegħa:1 hour,ġurnata:1 day,ġimgħa:1 week,ġimgħatejn:2 weeks,xahar:1 month,3 xhur:3 months,6 xhur:6 months,sena:1 year,infinita:infinite',
'restriction-type'            => 'Permess:',
'restriction-level'           => "Livell ta' limitazzjoni:",
'minimum-size'                => 'Daqs minimu',
'maximum-size'                => 'Daqs massimu:',
'pagesize'                    => '(byte)',

# Restrictions (nouns)
'restriction-edit'   => 'Modifika',
'restriction-move'   => 'Mexxi',
'restriction-create' => 'Ħolqien',
'restriction-upload' => "Tella'",

# Restriction levels
'restriction-level-sysop'         => 'protezzjoni sħiħa',
'restriction-level-autoconfirmed' => 'semi-protetta',
'restriction-level-all'           => 'kull livell',

# Undelete
'undelete'                     => 'Ara l-paġni mħassra',
'undeletepage'                 => 'Ara u rkupra paġni mħassra',
'undeletepagetitle'            => "'''Hawn jinsabu reviżjonijiet imħassra ta' [[:$1|$1]]'''.",
'viewdeletedpage'              => 'Ara l-paġni mħassra',
'undeletepagetext'             => "Il-{{PLURAL:$1|paġna segwenti ġiet mħassra, però xorta għadha fl-arkivju u għalhekk tista' tiġi rkuprata|paġni segwenti ġew imħassra, però xorta għadhom jistgħu jiġu rkuprati}}. L-arkivju jista' jiġi mnaddaf perjodikament.",
'undelete-fieldset-title'      => 'Irkupra reviżjonijiet',
'undeleteextrahelp'            => "Biex tirkupra l-paġna sħiħa, ħalli l-kaxxi kollha vojta u agħfas fuq '''''Irkupra'''''.
Biex tirkupra partijiet speċifiċi, agħżel il-kaxxi korrispondenti mar-reviżjonijiet li tixtieq tirkupra u agħfas '''''Irkupra'''''. Jekk tagħfas '''''Irrisettja''''', kemm il-kaxxi kif ukoll l-ispazju għall-kummenti jiżvojtjaw.",
'undeleterevisions'            => '{{PLURAL:$1|reviżjoni|$1 reviżjonijiet}} fl-arkivju',
'undeletehistory'              => 'Jekk tirkupra l-paġna, ir-reviżjonijiet kollha jiġu mdaħħla mill-ġdid fil-kronoloġija relattiva. Jekk wara t-tħassir paġni ġodda jiġi maħluqa bl-istess titlu, r-reviżjonijiet irkuprati jiġu jidhru fil-kronoloġija preċedenti. Kun af wkoll li limitazzjonijiet fuq reviżjonijiet tal-fajl huwa mitlufa waqt li jiġi rkuprati.',
'undeleterevdel'               => "Jekk il-parti ta' fuq tal-paġna jew ir-reviżjoni tal-fajl huma parzjalment imħassra, l-proċess ta' irkuprar ma ssirx. F'dawn il-każi, int trid ma taħbiex jew ma tagħżilx ir-reviżjoni mħassra l-aktar riċenti.",
'undeletehistorynoadmin'       => "Din il-paġna ġiet imħassra.
Ir-raġuni għat-tħassir tinsab fit-taqsira t'hawn taħt, flimkien mal-informazzjoni tal-utenti li għamlu modifiki fuq din il-paġna qabel ma ġiet imħassra.
It-test attwali ta' dawn ir-reviżjonijiet imħassra hija disponibbli biss lill-amministraturi.",
'undelete-revision'            => 'Reviżjoni mħassra tal-paġna $1, imdaħħla nhar il-$4 fil-$5, minn $3:',
'undeleterevision-missing'     => "Reviżjoni invalidu jew nieqes.
Int jista' jkollhok link ħażin, jew jista' jkun li ir-reviżjoni ġie rkuprat jew mneħħa mill-arkivju.",
'undelete-nodiff'              => 'L-ebda reviżjoni preċedenti ma ġiet misjuba.',
'undeletebtn'                  => 'Irkupra',
'undeletelink'                 => 'uri/irkupra',
'undeletereset'                => 'Irrisettja',
'undeleteinvert'               => 'Inverti s-selezzjoni',
'undeletecomment'              => 'Raġuni:',
'undeletedarticle'             => 'ġie irkuprat "[[$1]]"',
'undeletedrevisions'           => '{{PLURAL:$1|reviżjoni irkuprata|$1 reviżjonijiet irkuprati}}',
'undeletedrevisions-files'     => '{{PLURAL:$1|reviżjoni waħda|$1 reviżjonijiet}} u {{PLURAL:$2|fajl wieħed irkuprat|$2 fajls irkuprati}}',
'undeletedfiles'               => '{{PLURAL:$1|file wieħed|$1 fajls}} irkuprati',
'cannotundelete'               => "L-irkuprar ma rnexxiex;
jista' jkun li xi ħadd ieħor irkupra l-paġna qabel.",
'undeletedpage'                => "'''$1 ġie irkuprat'''

Ikkonsulta r-[[Special:Log/delete|reġistru tat-tħassir]] biex tara t-tħassir u l-irkuprar ta' paġni l-aktar riċenti.",
'undelete-header'              => 'Ara r-[[Special:Log/delete|reġistru tat-tħassir]] għal paġni mħassra riċentament.',
'undelete-search-box'          => 'Fittex paġni mħassra',
'undelete-search-prefix'       => "Uri l-paġni li t-titlu jibda' bl-ittri:",
'undelete-search-submit'       => 'Fittex',
'undelete-no-results'          => "L-ebda paġna korrispondenti ma ġiet misjuba fl-arkivju ta' tħassir.",
'undelete-filename-mismatch'   => 'Ir-Reviżjoni tal-fajl bit-timbru tal-ħin $1 ma setgħax jiġi mħassar: isem tal-fajl ma jaqbilx',
'undelete-bad-store-key'       => 'Huwa impossibbli li jiġi annullat it-tħassir tar-reviżjoni tal-fajl bit-timbru tal-ħin $1: il-fajl kien nieqes qabel it-tħassir.',
'undelete-cleanup-error'       => 'Problema fit-tħassir ta\' fajl "$1" tal-arkivju li mhux użat.',
'undelete-missing-filearchive' => "Impossibli tirkupra l-fajl tal-arkivju b'ID $1 minħabba li mhux qiegħed fid-database.
Jista' jkun li ġa ġie rkuprat.",
'undelete-error-short'         => 'Problema fl-irkuprar tal-fajl: $1',
'undelete-error-long'          => 'Kien hemm problemi waqt il-fajl kien qiegħed jiġi rkuprat:

$1',
'undelete-show-file-confirm'   => 'Inti ċert li trid tara reviżjoni imħassra tal-fajl "<nowiki>$1</nowiki>" ta\' nhar $2, fil-ħin ta\' $3?',
'undelete-show-file-submit'    => 'Iva',

# Namespace form on various pages
'namespace'      => 'Spazju tal-isem:',
'invert'         => 'Inverti l-għażla',
'blanknamespace' => '(Prinċipali)',

# Contributions
'contributions'       => 'Kontribuzzjonijiet tal-utent',
'contributions-title' => 'Kontribuzzjonijiet tal-utent għal $1',
'mycontris'           => 'il-kontribuzzjonijiet tiegħi',
'contribsub2'         => 'Għal $1 ($2)',
'nocontribs'          => 'L-Ebda modifiki li jisodisfa l-kriterji tat-tfittxija.',
'uctop'               => '(l-aħħar fil-paġna)',
'month'               => 'Mix-xahar (u qabel):',
'year'                => 'Mis-sena (u qabel):',

'sp-contributions-newbies'       => 'Uri biss il-kontribuzzjonijiet tal-utenti l-ġodda',
'sp-contributions-newbies-sub'   => 'Għall-utenti l-ġodda',
'sp-contributions-newbies-title' => "Kontribuzzjonijiet ta' utenti ġodda",
'sp-contributions-blocklog'      => 'blokki',
'sp-contributions-deleted'       => 'kontribuzzjonijiet imħassra tal-utent',
'sp-contributions-talk'          => 'diskussjoni',
'sp-contributions-search'        => 'Fittex għal kontribuzzjonijiet',
'sp-contributions-username'      => 'Indirizz IP jew isem tal-utent:',
'sp-contributions-submit'        => 'Fittex',

# What links here
'whatlinkshere'            => "Li jwasslu 'l hawn",
'whatlinkshere-title'      => 'Paġni li jippuntaw lejn $1',
'whatlinkshere-page'       => 'Paġna:',
'linkshere'                => "Il-paġni segwenti jorbtu lejn '''[[:$1]]''':",
'nolinkshere'              => "L-ebda paġna ma twassal għal '''[[:$1]]'''.",
'nolinkshere-ns'           => "L-Ebda paġni li jippontaw għal '''[[:$1]]''' fin-namespace magħżula.",
'isredirect'               => 'Paġna ta Rindirizz',
'istemplate'               => 'inklużjoni',
'isimage'                  => 'link tas-stampa',
'whatlinkshere-prev'       => '{{PLURAL:$1|preċedent|$1 preċedenti}}',
'whatlinkshere-next'       => '{{PLURAL:$1|segwent|$1 segwenti}}',
'whatlinkshere-links'      => '← links',
'whatlinkshere-hideredirs' => '$1 riindirizzi',
'whatlinkshere-hidetrans'  => '$1 inklużjonijiet',
'whatlinkshere-hidelinks'  => '$1 link',
'whatlinkshere-hideimages' => '$1 links tal-istampi',
'whatlinkshere-filters'    => 'Filtri',

# Block/unblock
'blockip'                         => 'Imblokka lil utent',
'blockip-title'                   => "Imblokka 'l-utent",
'blockip-legend'                  => "Imblokka 'l-utent",
'blockiptext'                     => "Uża l-formola t'hawn taħt sabiex biex tibblokkja l-aċċess tal-kitba lil IP speċifiku jew utent.
Il-blokk irid ikun użat biss sabiex jitnaqqas iċ-ċans ta' vandaliżmu, u għandu josservja b'mod strett il-[[{{MediaWiki:Policy-url}}|politika ta' {{SITENAME}}]]. 
Indika r-raġuni speċifika għalfejn tixtieq tipproċedi bil-blokk (per eżempju, billi turi l-paġni partikolari li ġew ivvandalizzati).",
'ipaddress'                       => 'Indirizz tal-IP:',
'ipadressorusername'              => 'Indirizz tal-IP jew isem tal-utent:',
'ipbexpiry'                       => 'Skadenza tal-imblokk:',
'ipbreason'                       => 'Raġuni:',
'ipbreasonotherlist'              => 'Raġuni oħra',
'ipbreason-dropdown'              => "*Raġunijiet komuni dwar blokki
** Iddaħħal informazzjoni falza
** Tneħħi kontenut mill-paġni
** Links kummerċjali ma' siti esterni
** Iddaħal kontenut bla sens
** Komportament intimidanti jew molestiku
** Abbuż minn aktar minn kont wieħed
** isem ta' utent mhux aċċettabli",
'ipbanononly'                     => 'Imblokka biss l-utenti anonimi',
'ipbcreateaccount'                => "Impedixxi ħolqien ta' kontijiet oħrajn",
'ipbemailban'                     => 'Impedixxi utenti milli jkunu jistgħu jibgħatu posta elettronika',
'ipbenableautoblock'              => 'Awtomatikament blokka l-aħħar indirizz tal-IP użat minn dan l-utent, u IP suċċessivi li jipprovaw jagħmlu modifiki',
'ipbsubmit'                       => 'Imblokk lil dan l-utent',
'ipbother'                        => 'Ħin ieħor:',
'ipboptions'                      => 'sagħtejn:2 hours,ġurnata 1:1 day,3 ġranet:3 days,ġimgħa 1:1 week,ġimgħatejn:2 weeks,xahar 1:1 month,3 xhur:3 months,6 xhur:6 months,sena 1:1 year,infinita:infinite',
'ipbotheroption'                  => 'ieħor',
'ipbotherreason'                  => 'Raġunijiet oħra/addizzjonali:',
'ipbhidename'                     => 'Aħbi l-isem tal-utent mill-modifiki u mill-elenki.',
'ipbwatchuser'                    => "Osserva l-paġni u d-diskussjonijiet ta' dan l-utent",
'badipaddress'                    => "Indirizz ta' IP invalidu",
'blockipsuccesssub'               => 'Il-blokk irnexxa',
'blockipsuccesstext'              => 'L-utent [[Special:Contributions/$1|$1]] ġie imblukkat.<br />
Ara l-[[Special:IPBlockList|lista tal-IP imblukkati]] biex tara l-blokki attivi.',
'ipb-edit-dropdown'               => 'Modifika r-raġuni tal-blokkar',
'ipb-unblock-addr'                => 'Żblokkja $1',
'ipb-unblock'                     => 'Żblokka isem tal-utent jew indirizz IP',
'ipb-blocklist-addr'              => 'Uri l-blokki attivi għal $1',
'ipb-blocklist'                   => 'Uri blokki attivi',
'unblockip'                       => 'Żblokkja l-utent',
'unblockiptext'                   => "Uża l-formula t'hawn taħt sabiex tirkupra aċċess tal-kitba 'l utent jew indirizz tal-IP blokkat.",
'ipusubmit'                       => 'Żblokkja dan l-indirizz',
'unblocked'                       => 'L-utent [[User:$1|$1]] ġie żblokkjat',
'unblocked-id'                    => 'Il-blokk $1 tneħħa',
'ipblocklist'                     => 'Lista tal-utenti u indirizzi IP imblukkati',
'ipblocklist-legend'              => 'Fittex utent ibblokkjat',
'ipblocklist-username'            => 'Isem tal-utent u indirizz IP:',
'ipblocklist-sh-userblocks'       => '$1 l-blokki tal-utenti reġistrati',
'ipblocklist-sh-tempblocks'       => '$1 l-blokki temporanji',
'ipblocklist-sh-addressblocks'    => '$1 l-blokki waħdanija tal-IP',
'ipblocklist-submit'              => 'Fittex',
'blocklistline'                   => '$1, $2 imblokka lil $3 (sa $4)',
'infiniteblock'                   => 'infinit',
'expiringblock'                   => 'jiskadi sa $1 fil-$2',
'anononlyblock'                   => 'anonimu biss',
'noautoblockblock'                => 'bla blokk awtomatiku',
'createaccountblock'              => 'ħolqien tal-kont imblukkat',
'emailblock'                      => 'posta elettronika blokkata',
'ipblocklist-empty'               => 'Il-Lista tal-blokki hija vojta.',
'ipblocklist-no-results'          => 'L-indirizz IP jew isem tal-utent rikjest mhuwiex imblukkat.',
'blocklink'                       => 'blokkja',
'unblocklink'                     => 'żblokkja',
'change-blocklink'                => 'biddel il-blokk',
'contribslink'                    => 'kontributi',
'autoblocker'                     => 'Blokkat awtomatikament minħabba li l-indirizz tal-IP ġie użat mill-utent "[[User:$1|$1]]". Ir-Raġuni li ġiet mogħtija għall-imblokk ta\' $1 kienet: "$2":',
'blocklogpage'                    => 'Blokki',
'blocklogentry'                   => 'imblokka lil "[[$1]]" għal perjodu ta\' $2 $3',
'blocklogtext'                    => "Dan huwa log tal-azzjonijiet tal-blokkar u sblokkar ta' utent. Indirizzi tal-IP blokkati awtomatikament m'humiex fil-lista. Ara l-[[Special:IPBlockList|lista tal-IP blokkati]] għal lista tal-blokki attivi bħalissa.",
'unblocklogentry'                 => 'żblokkjajt $1',
'block-log-flags-anononly'        => 'utenti anonimi biss',
'block-log-flags-nocreate'        => 'ħolqien tal-kont imblukkat',
'block-log-flags-noautoblock'     => 'blokkar awtomatiku disattivat',
'block-log-flags-noemail'         => 'posta elettronika blokkata',
'block-log-flags-angry-autoblock' => 'blokkar awtomatiku avvanzat attivat',
'range_block_disabled'            => "Il-Possibilitá li timblokka intervali ta' indirizzi tal-IP mhux attiva bħalissa.",
'ipb_expiry_invalid'              => "Il-ħin ta' skadenza huwa invalidu.",
'ipb_expiry_temp'                 => "Blokkijiet ta' ismijiet ta' l-utent moħbija rridu jkunu permanenti.",
'ipb_already_blocked'             => 'L-utent "$1" diġà bblokkjat',
'ipb-needreblock'                 => '== Diġà imblukkat ==
L-utent $1 hu diġà imblukkat. Trid tbiddel l-impostazzjonijet?',
'ipb_cant_unblock'                => 'Problema: Impossibli ssib il-blokk bl-ID $1. L-Imblokk setgħa jkun ġa sblokkat.',
'ipb_blocked_as_range'            => "Problema: L-Indirizz tal-IP $1 ma jistax jiġi blokkat waħdu u ma jistax jiġi sblokkat. L-Imblokk huwa attiv però f'livell ta' interval $2, li jista' jkun sblokkat.",
'ip_range_invalid'                => "Interval ta' indirizzi ta' IP mhux validi.",
'blockme'                         => 'Ibblokkjani',
'proxyblocker'                    => "Blokki ta' proxy miftuħa",
'proxyblocker-disabled'           => 'Din il-funzjoni mhijiex attivata.',
'proxyblockreason'                => "L-Indirizz tal-IP tiegħek ġie bblokkjat peress li huwa proxy miftuħ. 
Jekk jogħġbok, ikkuntattja lill-provdituri tas-servizz tal-internet jew lis-support tekniku tiegħek u informahom b'din il-problema serja ta' sigurtà.",
'proxyblocksuccess'               => 'Blokkjat.',
'sorbsreason'                     => "L-Indirizz tal-IP tiegħek huwa mniżżel bħala proxy miftuħ fil-DNSBL ta' {{SITENAME}}.",
'sorbs_create_account_reason'     => 'Mhux possibli toħloq aċċessi ġodda minn dan l-indirizz tal-IP minħabba li huwa mniżżel bħala proxy miftuħ fil-DNSBL użat minn {{SITENAME}}.',

# Developer tools
'lockdb'              => 'Agħlaq id-database',
'unlockdb'            => 'Iftaħ id-database',
'lockdbtext'          => "Tagħlaq id-database se jisuspendi l-abbilitá ta' kull utent li jagħmlu modifiki fil-paġni, modifika l-preferenzi tagħhom, modifika l-osservazzjonijiet speċjali tagħhom, u affarijiet oħra li għadnhom bżonn modifika fid-database.
Jekk jogħġbok konferma li dan huwa li tixtieq li tagħmel, u li se tiftaħ id-database wara li l-manteniment ikun lest.",
'unlockdbtext'        => "Tiftaħ id-database se jirkupra l-abbilità ta' kull utent li jagħmlu modifiki mill-ġdid, jagħmlu modifiki fil-preferenzi, modifiki fl-osservazzjonijiet speċjali, u affarijiet oħra li għandhom bżonn modifika fid-database.
Jekk jogħġbok konferma mill-ġdid li dan huwa li tixtieq li tagħmel.",
'lockconfirm'         => 'Iva, ċert li rrid nagħlaq id-database.',
'unlockconfirm'       => 'Iva, ċert li rrid niftaħ id-database.',
'lockbtn'             => 'Agħlaq id-database',
'unlockbtn'           => 'Iftaħ id-database',
'locknoconfirm'       => 'Inti ma għażiltx il-kaxxa tal-konferma.',
'lockdbsuccesssub'    => "Id-Database ngħalaq b'suċċess",
'unlockdbsuccesssub'  => 'Id-database infetħet',
'lockdbsuccesstext'   => 'Id-Database ngħalaq.<br />
Ftakar li [[Special:UnlockDB|tiftaħ]] wara l-manteniment ikun lest.',
'unlockdbsuccesstext' => 'Id-database infetaħ.',
'lockfilenotwritable' => "Impossibli tikteb fuq il-fajl ''agħlaq' tad-database. L-Aċċess tad-database, l-web server irrid ikun jista' jikteb.",
'databasenotlocked'   => 'Id-Database mhux magħluq.',

# Move page
'move-page'                    => 'Mexxi $1',
'move-page-legend'             => 'Ċaqlaq il-paġna',
'movepagetext'                 => "L-użu tal-formola t'hawn taħt, twassal f'isem ġdid għall-paġna, tmexxija tal-kronoloġija kollha tagħha għall-isem il-ġdid.<br />
It-titlu l-antik se jsir paġna ta' riindirizz għat-titlu l-ġdid.<br />
Tista' taġġorna riindirizzi li jippuntaw awtomatikament lejn l-isem oriġinali.<br />
Tista' tagħżel li ma tagħmilx dan, imma ftakar biex tivverifika li t-tmexxija li saret ma ħolqitx riindirizzi [[Special:DoubleRedirects|doppji]] jew [[Special:BrokenRedirects|ħżiena]]. Inti responsabbli li tkun ċert li l-ħoloq jibqgħu korretti.

Għandek tkun taf li l-paġna '''mhux''' se titmexxa jekk hemm diġà paġna fit-titlu l-ġdid, sakemm tkun vojta jew xi riindirizz u m'għandha ebda kronoloġija ta' modifika passata. Fil-każ ta' tmexxija ħażina tista' tmur lura mal-ewwel għat-titlu l-antik, u mhuwiex possibli li tikteb bi żball fuq paġna diġà eżistenti.

'''ATTENZJONI!'''
Din tista' tkun bidla drastika u mhux mistenniha għal paġna popolari; jekk jogħġbok kun ċert li tifhem il-konsegwenzi ta' din qabel ma tkompli.",
'movepagetalktext'             => "Il-Paġna korrispondenti tad-diskussjoni se tiġi awtomatikament imċaqilqa flimkien magħha '''sakemm:'''
* Paġna mhux vojta ta' diskussjoni ġa teżisti taħt l-isem il-ġdid.
* Ma tagħżilx il-kaxxa t'hawn taħt.

F'dawn il-każi, inti trid iċaqlaq jew tagħqad il-paġna manwalment jekk dan huwa mixtieq.",
'movearticle'                  => 'Ċaqlaq il-paġna:',
'movenologin'                  => 'Aċċess mhux imwettaq',
'movenologintext'              => "Trid [[Special:UserLogin|tidħol]] bħalha utent reġistrat sabiex tkun tista' iċaqlaq din il-paġna.",
'movenotallowed'               => "Inti m'għandekx il-permessi meħtieġa sabiex tmexxi l-paġni.",
'newtitle'                     => 'Titlu ġdid:',
'move-watch'                   => 'Segwi din il-paġna',
'movepagebtn'                  => 'Ċaqlaq il-paġna',
'pagemovedsub'                 => 'Ċaqlieq irnexxa',
'movepage-moved'               => '\'\'\'"$1" ġie mċaqlaq għal "$2"\'\'\'',
'articleexists'                => "Diġà teżisti paġna b'dak l-isem, jew inkella l-isem li għażilt mhux validu.
Jekk jogħġbok, agħżel isem ieħor.",
'cantmove-titleprotected'      => 'Ma tistax iċċaqlaq paġna hemmhekk, minħabba li t-titlu l-ġdid ġie protett milli jiġi maħluq.',
'talkexists'                   => "'''Il-paġna tmexxiet sewwa, iżda mhux il-paġna tad-diskussjoni, peress diġà teżisti waħda b'dan it-titlu.
Jekk jogħġbok, waħħad iż-żewġ paġni manwalment.'''",
'movedto'                      => 'Imċaqlaq għal',
'movetalk'                     => 'Ċaqlaq ukoll il-paġni tad-diskussjoni',
'move-subpages'                => 'Mexxi s-sottopaġni (sa $1)',
'move-talk-subpages'           => "Mexxi is-sottopaġni kollha tal-paġna ta' diskussjoni (sa $1)",
'movepage-page-exists'         => 'Il-Paġna $1 ġa teżisti u ma tistax tiġi awtomatikament miktub fuqha.',
'movepage-page-moved'          => 'Il-Paġna $1 ġiet imċaqilqa għal $2.',
'movepage-page-unmoved'        => 'Il-Paġna $1 ma setgħatx tiġi mċaqilqa għal $2.',
'movepage-max-pages'           => "Ġie mċaqlaq in-numru massimu ta' {{PLURAL:$1|paġna u ma jistax jiġi mċaqlaq aktar awtomatikament|$1 paġni u ma jistgħux jiġu mċaqilqa aktar awtomatikament.}}",
'1movedto2'                    => '[[$1]] tmexxa lejn [[$2]]',
'1movedto2_redir'              => "[[$1]] tmexxa lejn [[$2]] permezz ta' riindirizzament",
'movelogpage'                  => 'Ċaqlieq',
'movelogpagetext'              => "Hawn taħt jinsab lista ta' paġni mċaqilqa.",
'movesubpage'                  => '{{PLURAL:$1|Sottopaġna|Sottopaġna}}',
'movenosubpage'                => "Din il-paġna m'għandha l-ebda sottopaġna.",
'movereason'                   => 'Raġuni:',
'revertmove'                   => 'irkupra',
'delete_and_move'              => 'Ħassar u mexxi',
'delete_and_move_text'         => '==Rikjesta ta\' tħassir==
Il-Paġna tad-destinazzjoni "[[:$1]]" ġa teżisti.
Trid tħassara sabiex tkun tista\' tagħmel triq għal ċaqlieqa?',
'delete_and_move_confirm'      => 'Iva, ħassar il-paġna',
'delete_and_move_reason'       => 'Imħassra sabiex tagħmel triq għal ċaqlieqa',
'selfmove'                     => 'It-Titli tas-sors u destinazzjoni huma l-istess;
ma tistax iċaqlaq paġna fuqha nnifsa.',
'immobile-source-namespace'    => 'Mhuwiex possibbli li tmexxi paġni fl-ispazju tal-isem "$1"',
'immobile-target-namespace'    => 'Mhuwiex possibbli li tmexxi paġni fl-ispazju tal-isem "$1"',
'immobile-target-namespace-iw' => 'Il-ħolqa interwiki mhijiex destinazzjoni valida biex tmexxi l-paġna.',
'immobile-source-page'         => 'Din il-paġna ma tistax tiġi mmexxiha.',
'immobile-target-page'         => 'Ma jistax jitmexxa lejn it-titlu indikat.',
'imagenocrossnamespace'        => "Ma tistax iċaqlaq fajl f'namespace mhux tal-fajls.",
'imagetypemismatch'            => 'L-Estenżjoni l-ġdida tal-fajl ma taqbilx mat-tip tagħha.',
'imageinvalidfilename'         => 'L-Isem tal-fajl destinat mhux validu',
'fix-double-redirects'         => 'Aġġorna kwalunkwe rindirizz li jippunta lejn it-titlu l-oriġinali',
'move-leave-redirect'          => 'Oħloq riindirizz wara t-tmexxija',

# Export
'export'            => 'Esporta paġni',
'exporttext'        => "Inti tista' tesporta test jew kronoloġija tal-modifiki ta' paġna partikulari jew grupp ta' paġni fil-format XML.
Dan jista' jiġi importat f'wiki oħra bl-użu ta' MediaWiki permezz tal-[[Special:Import|paġna ta' importazzjoni]].

Biex tesporta paġni, daħħal it-titli fil-kaxxa tat-test hawn taħt, titlu f'kull linja, u agħżel jekk tridx il-verżjoni kurrrenti kif ukoll il-verżonijiet qodma kollha, bil-linji tal-kronoloġija tal-paġna, jew il-verżjoni kurrenti biss bl-informazzjoni dwar l-aħħar modifika.

Fl-aħħar każ inti tista' tuża ħolqa, per eżempju
[[{{#Special:Export}}/{{MediaWiki:Mainpage}}]] sabiex tesporta \"[[{{MediaWiki:Mainpage}}]]\".",
'exportcuronly'     => 'Inkludi biss reviżjonijiet kurrenti, mhux kronoloġiji sħaħ',
'exportnohistory'   => "----
'''Nota:''' L-Esportazzjoni tal-kronoloġija kollha tal-paġni min-naħa waħda għall-oħra din l-''interface'' ġiet disattivata għar-raġunijiet marbutin tal-għemil.",
'export-submit'     => 'Esporta',
'export-addcattext' => 'Żied paġni mill-kategorija:',
'export-addcat'     => 'Żied',
'export-addnstext'  => 'Żid paġni mill-ispazju tal-isem:',
'export-addns'      => 'Żid',
'export-download'   => 'Salva bħala fajl',
'export-templates'  => 'Inkludi templates',

# Namespace 8 related
'allmessages'                   => 'Messaġġi tas-sistema',
'allmessagesname'               => 'Isem',
'allmessagesdefault'            => 'Test predefinit',
'allmessagescurrent'            => 'It-test attwali',
'allmessagestext'               => "Din hija lista ta' messaġġi tas-sistema disponibbli fl-ispazju tal-isem MediaWiki.",
'allmessagesnotsupportedDB'     => "Il-paġna ma tistax tintuża għax '''\$wgUseDatabaseMessages''' mhux attivat.",
'allmessages-filter-legend'     => 'Filtru',
'allmessages-filter'            => "Iffilitra skont l-istat ta' modifika:",
'allmessages-filter-unmodified' => 'Mhux modifikati',
'allmessages-filter-all'        => 'Kollha',
'allmessages-filter-modified'   => 'Modifikati',
'allmessages-prefix'            => 'Iffiltra skont il-prefiss:',
'allmessages-language'          => 'Lingwa:',
'allmessages-filter-submit'     => 'Mur',

# Thumbnails
'thumbnail-more'           => 'Kabbar',
'filemissing'              => 'Fajl nieqes',
'thumbnail_error'          => "Problema fil-ħolqien ta' ''thumbnail'': $1",
'djvu_page_error'          => 'Numru tal-paġna DjVu bla klassifika',
'djvu_no_xml'              => 'Impossibli ġġib il-XML għal fajl DjVu',
'thumbnail_invalid_params' => 'Parametri invalidi għall-minjatura',
'thumbnail_dest_directory' => 'Impossibli toħloq id-direttorju tad-destinazzjoni',
'thumbnail_image-missing'  => 'Il-fajl $1 jidher li hu nieqes',

# Special:Import
'import'                     => 'Importa paġni',
'importinterwiki'            => 'Importazzjoni transwiki',
'import-interwiki-text'      => "Agħżel wiki u titlu ta' paġna li se timporta.
Dati ta' reviżjonijiet u ismijiet tal-modifikaturi jiġu preservati.
Kull azzjonijiet ta' importazzjoni tal-transwiki jiġu reġistrati fil-[[Special:Log/import|log ta' importazzjoni]].",
'import-interwiki-source'    => 'Sors tal-wiki/paġna:',
'import-interwiki-history'   => 'Kopja l-verżjonijiet tal-kronoloġija kollha għal din il-paġna',
'import-interwiki-templates' => 'Inkludi l-mudelli kollha',
'import-interwiki-submit'    => 'Importa',
'import-interwiki-namespace' => 'Ittrasferixxi l-paġni fl-ispazju tal-isem:',
'import-upload-filename'     => 'Isem tal-fajl:',
'import-comment'             => 'Kumment:',
'importtext'                 => 'Jekk jogħġbok, esporta l-fajl mis-sit wiki l-oriġini bil-funzjoni [[Special:Export]], salvah fuq id-diska tiegħek u tellgħu hawn.',
'importstart'                => 'Paġni qegħdin jiġu importati...',
'import-revision-count'      => '{{PLURAL:$1|reviżjoni|$1 reviżjonijiet}}',
'importnopages'              => 'L-Ebda paġna li għanda tiġi importata.',
'importfailed'               => 'Importazzjoni ma rnexxiex: <nowiki>$1</nowiki>',
'importunknownsource'        => "Tip ta' oriġini mhux magħruf għall-importazzjoni",
'importcantopen'             => 'Impossibli tiftaħ il-fajl tal-importazzjoni',
'importbadinterwiki'         => 'Link interwiki mhux tajjeb',
'importnotext'               => 'Test vojt jew nieqes',
'importsuccess'              => 'L-Importazzjoni rnexxiet!',
'importhistoryconflict'      => "Il-Kronoloġija fija verżjonijiet f'kunflitt (dan il-paġna setgħet tiġi ġiet importata qabel)",
'importnosources'            => "Ma ġie definit ebda sors ta' importazzjoni transwiki; l-importazzjoni diretta tal-kronoloġija mhix attivata.",
'importnofile'               => "L-Ebda fajl tal-importazzjoni itella'.",
'importuploaderrorsize'      => 'Il-Fajl ma itellax. Il-Fajl huwa akbar mid-daqs massimu permessa.',
'importuploaderrorpartial'   => "Upload tal-fajl għall-importazzjoni ma rnexxiex. Il-Fajl itella' parzjalment.",
'importuploaderrortemp'      => "Upload tal-fajl ta' importazzjoni ma rnexxiex. Folder temporanju huwa nieqes.",
'import-parse-failure'       => 'Problema fl-analiżi fl-importazzjoni XML',
'import-noarticle'           => 'L-Ebda paġna li għandha tiġi importata!',
'import-nonewrevisions'      => 'Ir-Reviżjonijiet kollha kienu importati preċedentament.',
'xml-error-string'           => '$1 fil-linja $2, kol $3 (byte $4): $5',
'import-upload'              => "Tella' data XML",
'import-token-mismatch'      => "Telfien tad-dati tas-sessjoni. Jekk jogħġbok erġa' pprova.",

# Import log
'importlogpage'                    => 'Importazzjoni',
'importlogpagetext'                => "Importi amministrativi ta' paġni b'kronoloġiji ta' modifiki minn wikis oħrajn.",
'import-logentry-upload'           => "importajt [[$1]] bl-użu ta' upload.",
'import-logentry-upload-detail'    => '{{PLURAL:$1|reviżjoni|$1 reviżjonijiet}}',
'import-logentry-interwiki'        => 'Trasferixxejt minn wiki ieħor il-paġna $1',
'import-logentry-interwiki-detail' => '{{PLURAL:$1|reviżjoni|$1 reviżjonijiet}} minn $2',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Il-paġna tal-utent tiegħek',
'tooltip-pt-anonuserpage'         => "Il-Paġna tal-utent ta' dan l-indirizz tal-IP",
'tooltip-pt-mytalk'               => "Il-paġna ta' diskussjoni tiegħek",
'tooltip-pt-anontalk'             => 'Diskussjoni dwar il-modifiki magħmula minn dan l-indirizz tal-IP',
'tooltip-pt-preferences'          => 'Il-preferenzi tiegħi',
'tooltip-pt-watchlist'            => 'Il-Lista tal-paġni li qiegħed tosserva',
'tooltip-pt-mycontris'            => 'Lista tal-kontribuzzjonijiet tiegħek',
'tooltip-pt-login'                => 'Tirreġistra ruħek huwa avviżat però mhux obbligatorju.',
'tooltip-pt-anonlogin'            => 'Tirreġistra ruħek huwa avviżat, anki jekk mhux obbligatorju.',
'tooltip-pt-logout'               => 'Oħroġ (logout)',
'tooltip-ca-talk'                 => 'Diskussjoni dwar il-kontenut tal-paġna',
'tooltip-ca-edit'                 => "Tista' timmodifika din il-paġna. Jekk jogħġbok uża l-buttuna tad-dehra proviżorja qabel ma ssalva l-modifiki.",
'tooltip-ca-addsection'           => 'Ibda sezzjoni ġdida',
'tooltip-ca-viewsource'           => "Din il-paġna hija protetta. Tista' tara l-fonti tagħha.",
'tooltip-ca-history'              => "Verżjonijiet preċedenti ta' din il-paġna",
'tooltip-ca-protect'              => 'Ipproteġi din il-paġna',
'tooltip-ca-delete'               => 'Ħassar din il-paġna',
'tooltip-ca-undelete'             => "Irkupra l-modifiki magħmula f'din il-paġna qabel ma ġiet imħassra",
'tooltip-ca-move'                 => 'Mexxi din il-paġna',
'tooltip-ca-watch'                => "Żid din il-paġna mal-lista ta' osservazzjoni tiegħek",
'tooltip-ca-unwatch'              => 'Neħħi din il-paġna mill-osservazzjonijiet speċjali tiegħek',
'tooltip-search'                  => 'Fittex fil-{{SITENAME}}',
'tooltip-search-go'               => 'Mur fil-paġna bit-titlu indikat, jekk teżisti',
'tooltip-search-fulltext'         => 'Fittex it-test indikat fil-paġni',
'tooltip-p-logo'                  => 'Il-Paġna prinċipali',
'tooltip-n-mainpage'              => 'Żur il-Paġna Prinċipali',
'tooltip-n-mainpage-description'  => 'Żur il-paġna prinċipali',
'tooltip-n-portal'                => "Dwar il-proġett, x'tista' tagħmel, fejn tista' ssib l-affarijiet",
'tooltip-n-currentevents'         => 'Sib aktar informazzjoni dwar il-ġrajjiet kurrenti',
'tooltip-n-recentchanges'         => 'Il-Lista tal-modifiki riċenti fil-wiki.',
'tooltip-n-randompage'            => 'Uri paġna kwalunkwe',
'tooltip-n-help'                  => 'Il-Post fejn issir taf.',
'tooltip-t-whatlinkshere'         => 'Lista tal-paġni tal-wiki kollha li jwasslu hawn',
'tooltip-t-recentchangeslinked'   => "Link għal modifiki riċenti ta' paġni relatati",
'tooltip-feed-rss'                => 'Feed RSS għal din il-paġna',
'tooltip-feed-atom'               => 'Feed Atom għal din il-paġna',
'tooltip-t-contributions'         => "Uri l-lista tal-kontribuzzjonijiet ta' dan l-utent",
'tooltip-t-emailuser'             => 'Ibgħat posta elettronika lil dan l-utent',
'tooltip-t-upload'                => "Tella' fajls",
'tooltip-t-specialpages'          => 'Lista tal-paġni speċjali kollha',
'tooltip-t-print'                 => "Verżjoni tal-ipprintjar ta' din il-paġna",
'tooltip-t-permalink'             => 'Ħolqa permanenti għal din il-verżjoni tal-paġna',
'tooltip-ca-nstab-main'           => 'Uri l-kontenut tal-paġna',
'tooltip-ca-nstab-user'           => 'Uri l-paġna tal-utent',
'tooltip-ca-nstab-media'          => 'Uri l-paġna tal-medja',
'tooltip-ca-nstab-special'        => "Din hija paġna speċjali, ma tistax tagħmel modifiki f'din il-paġna",
'tooltip-ca-nstab-project'        => 'Uri l-paġna tal-proġett',
'tooltip-ca-nstab-image'          => 'Uri l-paġna tal-fajl',
'tooltip-ca-nstab-mediawiki'      => 'Uri l-messaġġ tas-sistema',
'tooltip-ca-nstab-template'       => 'Uri t-template',
'tooltip-ca-nstab-help'           => 'Uri l-paġna tal-għajnuna',
'tooltip-ca-nstab-category'       => 'Uri l-paġna kategorika',
'tooltip-minoredit'               => 'Immarka din bħala modifika minuri',
'tooltip-save'                    => 'Salva l-modifiki',
'tooltip-preview'                 => 'Qabel ma ssalva l-modifiki tiegħek, ara l-ewwel dehra proviżorja tat-tibdil li tkun wettaqt!',
'tooltip-diff'                    => 'Uri liem modifiki għamilt fit-test.',
'tooltip-compareselectedversions' => "Ara d-differenzi bejn iż-żewġ verżjonijiet magħżula ta' din il-paġna.",
'tooltip-watch'                   => "Żid din il-paġna mal-lista ta' osservazzjoni tiegħek",
'tooltip-recreate'                => "Erġa' oħloq din il-paġna minkejja li kienet ġiet imħassra",
'tooltip-upload'                  => "Ibda tella'",
'tooltip-rollback'                => '"Rollback" tannulla l-modifiki li saru mill-aħħar kontributur fuq din il-paġna, permezz ta\' sempliċi klikk',
'tooltip-undo'                    => '"Annulla" tannulla din il-modifika u tiftaħ il-formola tal-modifika b\'mod ta\' anteprima. Din ukoll tippermetti biex idaħħal raġuni fit-taqsira.',

# Stylesheets
'common.css'      => '/* CSS li tpoġġa hawnhekk irrid jiġi applikat fl-iskins kollha */',
'standard.css'    => '/* CSS li tpoġġa hawnhekk se jaffetwa l-utenti li jagħmlu użu mill-iskin Standard */',
'nostalgia.css'   => '/* CSS li tpoġġa hawnhekk se jaffetwa l-utenti li jagħmlu użu mill-aspett grafiku Nostalgia */',
'cologneblue.css' => '/* CSS li tpoġġa hawnhekk se jaffetwa dawk l-utenti li jagħmlu użu mill-aspett grafiku Cologne Blue */',
'monobook.css'    => "/* CSS li tpoġġa hawnhekk se jaffetwa dawk l-utenti li jagħmlu użu mill-iskin ''Monobook'' */",
'myskin.css'      => "/* CSS li tpoġġa hawnhekk se jaffetwa dawk l-utenti li jagħmlu użu mill-iskin ''L-Iskin tiegħi'' */",
'chick.css'       => '/* CSS li tpoġġa hawnhekk se jaffetwa dawk l-utenti li jagħmlu użu mill-aspett grafiku Ckick */',
'simple.css'      => '/* CSS li tpoġġa hawnhekk se jaffetwa dawk l-utenti li jagħmlu użu mill-aspett grafiku Simple */',
'modern.css'      => '/* CSS li tpoġġa hawnhekk se jaffetwa dawk l-utenti li jagħmlu użu mill-aspett grafiku Modern */',

# Scripts
'common.js'      => "/* Kull ''JavaScript'' hawnhekk jiġi mniżżel għal kull utent f'kull tniżżil ta' paġna. */",
'standard.js'    => "/* Kull ''JavaScript'' hawnhekk jiġi mniżżel għal dawk l-utenti li qegħdin jagħmlu użu mill-iskin ''Standard'' */",
'nostalgia.js'   => "/* Kull ''JavaScript'' hawnhekk jiġi mniżżel għal dawk l-utenti li qegħdin jagħmlu użu mill-iskin ''Nostalgia'' */",
'cologneblue.js' => "/* Kull ''JavaScript'' hawnhekk jiġi mniżżel għal dawk l-utenti li qegħdin jagħmlu użu mill-iskin ''Cologne Blue'' */",
'monobook.js'    => "/* Kull ''JavaScript'' hawnhekk jiġi mniżżel għal dawk l-utenti li qegħdin jagħmlu użu mill-iskin ''Monobook'' */",
'myskin.js'      => "/* Kull ''JavaScript'' hawnhekk jiġi mniżżel għal dawk l-utenti li qegħdin jagħmlu użu mill-iskin ''Myskin'' */",
'chick.js'       => "/* Kull ''JavaScript'' hawnhekk jiġi mniżżel għal dawk l-utenti li qegħdin jagħmlu użu mill-iskin ''Chick'' */",
'simple.js'      => "/* Kull ''JavaScript'' hawnhekk jiġi mniżżel għal dawk l-utenti li qegħdin jagħmlu użu mill-iskin ''Simple'' */",
'modern.js'      => "/* Kull ''JavaScript'' hawnhekk jiġi mniżżel għal dawk l-utenti li qegħdin jagħmlu użu mill-iskin ''Modern''*/",

# Metadata
'nodublincore'      => "''Dublin Core RDF metadata'' ġie mneħħi għal dan is-server.",
'nocreativecommons' => "''Creative Commons RDF metadata'' ġie mneħħi għal dan is-server. .",
'notacceptable'     => "Is-Server tal-wiki m'għandux format li l-klijent tiegħek ikun jista' jaqra.",

# Attribution
'anonymous'        => "{{PLURAL:$1|Utent anonimu|Utenti anonimi}} ta' {{SITENAME}}",
'siteuser'         => '$1, utent tal-{{SITENAME}}',
'lastmodifiedatby' => 'Din il-paġna ġiet modifikata l-aħħar fil-$2, $1 minn $3.',
'othercontribs'    => "Dan it-test ibbażat fuq ix-xogħol ta' $1.",
'others'           => 'oħrajn',
'siteusers'        => "$1, {{PLURAL:$2|utent|utenti}} ta' {{SITENAME}}",
'creditspage'      => 'Kredenzjali tal-paġna',
'nocredits'        => "M'hemmx informazzjoni dwar kredenzjali f'din il-paġna.",

# Spam protection
'spamprotectiontitle' => 'Filter tal-protezzjoni kontra l-ispam',
'spamprotectiontext'  => "Din il-paġna li ridt timmodifika ġiet imblukkata mill-filtru tal-ispam. Dan hu probabbli kważa ta' ħolqa għal sit estern.",
'spamprotectionmatch' => 'It-test segwenti huwa li ġab l-attenżjoni tal-filters tal-ispam: $1',
'spambot_username'    => 'Tindif tal-MedjaWiki mill-ispam',
'spam_reverting'      => "Erġa' lura għall-aħħar verżjoni li m'għandiex link għal $1",
'spam_blanking'       => 'Paġna svojtjata, kull verżjoni kellu link għal $1',

# Info page
'infosubtitle'   => 'Informazzjoni għal paġna',
'numedits'       => "Numru ta' modifiki (paġna): $1",
'numtalkedits'   => "Numru ta' modifiki (paġna tad-diskussjoni): $1",
'numwatchers'    => "Numru ta' osservaturi: $1",
'numauthors'     => "Numru ta' awturi distinti (paġna): $1",
'numtalkauthors' => "Numru ta' awturi distinti (paġna tad-diskussjoni): $1",

# Skin names
'skinname-standard'    => 'Classic',
'skinname-nostalgia'   => 'Nostalgia',
'skinname-cologneblue' => 'Cologne Blue',
'skinname-monobook'    => 'Monobook',
'skinname-myskin'      => 'MySkin',
'skinname-chick'       => 'Chick',
'skinname-simple'      => 'Simple',
'skinname-modern'      => 'Modern',

# Math options
'mw_math_png'    => "Uri dejjem f'PNG",
'mw_math_simple' => 'HTML jekk sempliċi ħafna, inkella PNG',
'mw_math_html'   => 'HTML jekk possibli inkella PNG',
'mw_math_source' => "Ħallija bħala TeX (għal browsers ta' test)",
'mw_math_modern' => 'Format rakkomandat għall-browsers moderni',
'mw_math_mathml' => 'MathML jekk possibli (esperimentali)',

# Math errors
'math_failure'          => "Problema fil-''parser''",
'math_unknown_error'    => 'Problema mhux magħrufa',
'math_unknown_function' => 'funżjoni mhux magħrufa',
'math_lexing_error'     => 'żball lessikali',
'math_syntax_error'     => 'żball fis-sintassi',
'math_image_error'      => "Konverżjoni għal PNG bla suċċess; verifika li huma installati tajjeb il-programmi segwenti: ''latex, dvips, gs, u convert''",
'math_bad_tmpdir'       => "Impossibli tikteb jew toħloq direttorju temporanju għal ''math''",
'math_bad_output'       => "Impossibli tikteb jew toħloq direttorju tal-''output'' tal-''math''",
'math_notexvc'          => "Esekuzzjoni ''texvc'' nieqes; jekk jogħġbok konsultà ''math/README'' għal konfigurazzjoni.",

# Patrolling
'markaspatrolleddiff'                 => 'Marka l-modifiki bħalha verifikati',
'markaspatrolledtext'                 => 'Immarka din il-paġna bħala verifikata',
'markedaspatrolled'                   => 'Markat bħalha verifikat',
'markedaspatrolledtext'               => "Ir-reviżjoni magħżulha ta' [[:$1]] hija mmarkata bħala verifikata.",
'rcpatroldisabled'                    => 'Modifikar riċenti verifikar disattivat',
'rcpatroldisabledtext'                => "Il-Funżjoni ta' verifika tal-aħħar modifiki bħalissa mhux attivata.",
'markedaspatrollederror'              => 'Ma jistax jiġi markat bħalha verifikat',
'markedaspatrollederrortext'          => 'Int trid tispeċifika r-reviżjoni li trida tkun verifikata.',
'markedaspatrollederror-noautopatrol' => "Int m'għandhekx id-drittijiet neċessarji biex timmarka l-modifiki tiegħek bħala verifikati.",

# Patrol log
'patrol-log-page' => 'Modifiki verifikati',
'patrol-log-line' => 'immarka r-$1 tal-paġna $2 bħala verifikata $3',
'patrol-log-auto' => '(verifika awtomatika)',
'patrol-log-diff' => 'reviżjoni $1',

# Image deletion
'deletedrevision'                 => 'Reviżjoni preċedenti, mħassra: $1',
'filedeleteerror-short'           => 'Problema waqt li kont qiegħed tħassar il-fajl: $1',
'filedeleteerror-long'            => 'Ġew verifikati xi problemi waqt li kont qiegħed tħassar il-fajl:

$1',
'filedelete-missing'              => 'Il-Fajl "$1" ma setgħax jiġi mħassar, minħabba li ma jeżistix.',
'filedelete-old-unregistered'     => 'Ir-Reviżjoni speċifikati tal-fajl "$1" mhux qiegħed fid-database.',
'filedelete-current-unregistered' => 'Il-Fajl speċifikat "$1" mhux qiegħed fid-database.',
'filedelete-archive-read-only'    => 'L-Arkivju tad-direttorju "$1" ma jistax jinkiteb mill-webserver.',

# Browsing diffs
'previousdiff' => '← Differenza preċedenti',
'nextdiff'     => 'Id-differenza suċċessiva →',

# Media information
'mediawarning'         => "'''Twissija''': Dan il-fajl jista' jinkludi ġo fih kodiċi malizzjuż. L-eżekuzzjoni tiegħu jista' jagħmel ħsara s-sistema informatika tiegħek.<hr />",
'imagemaxsize'         => "Daqs massimu tal-istampa:<br />''(għall-paġni ta' deskrizzjoni tal-fajl)''",
'thumbsize'            => 'Daqs tal-minjatura:',
'widthheightpage'      => '$1×$2, $3 {{PLURAL:$3|paġna|paġni}}',
'file-info'            => "(Dimensjoni: $1, tip ta' MIME: $2)",
'file-info-size'       => "($1 × $2 pixel, dimensjoni: $3, tip ta' MIME: $4)",
'file-nohires'         => "<small>Mhux disponibli verżjonijiet b'risoluzzjoni akbar.</small>",
'svg-long-desc'        => '(Fajl fil-format SVG, dimensjoni nominali $1 × $2 pixel, dimensjoni tal-fajl: $3)',
'show-big-image'       => "Verżjoni b'risoluzzjoni sħiħa",
'show-big-image-thumb' => '<small>Dimensjoni tal-previżjoni: $1 × $2 pixel</small>',

# Special:NewFiles
'newimages'             => "Gallerija ta' fajls ġodda",
'imagelisttext'         => "Il-Lista t'hawn taħt ta' '''$1''' {{PLURAL:$1|fajl|fajls}} irranġati $2.",
'newimages-summary'     => "Din il-paġna speċjali turi l-aħħar fajls li ġew mtella' riċentament.",
'newimages-legend'      => 'Filtru',
'newimages-label'       => 'Isem tal-fajl (jew parti minnu):',
'showhidebots'          => '($1 bots)',
'noimages'              => "M'hawn xejn x'tara.",
'ilsubmit'              => 'Fittex',
'bydate'                => 'data',
'sp-newimages-showfrom' => "Uri l-fajls l-aktar riċenti mill-ħin $2 ta' $1",

# Video information, used by Language::formatTimePeriod() to format lengths in the above messages
'hours-abbrev' => 'S',

# Bad image list
'bad_image_list' => "Il-format huwa kif imiss:

Jiġu kkunsidrati biss listi ppuntati (linji li jibdew b'*).
L-ewwel link fil-linja hija link għal fajl ħażin.
Il-links suċċessivi fuq l-istess linja huma kkunsidrati bħala eċċezzjonijiet, ċoè, paġni fejn il-fajl jista' jiġi rikjamat b'mod normali.",

# Metadata
'metadata'          => 'Metadati',
'metadata-help'     => 'Dan il-fajl fih aktar informazzjoni, aktarx ġie miżjud minn kamera diġitali jew skanner li ġew użati sabiex joħolquh jew biex jagħmluh diġitali. 
Jekk il-fajl ġie modifikat mill-istat oriġinali, xi dettalji jistgħu ma jikkorispondux mal-verżjoni kurrenti.',
'metadata-expand'   => 'Uri d-dettalji',
'metadata-collapse' => 'Aħbi d-dettalji',
'metadata-fields'   => "EXIF metadata mniżżel f'din il-lista tal-messaġġ se jiġu inkluż fil-veduta tal-paġna tal-istampa meta t-tabella tiġi murija fil-forma qasira. 
Minħabba veduta predefinita ,l-oħrajn se jiġu moħbija. 
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* isospeedratings
* focallength",

# EXIF tags
'exif-imagewidth'                  => "Wisa'",
'exif-imagelength'                 => 'Għoli',
'exif-bitspersample'               => 'Bits għal kull komponent',
'exif-compression'                 => 'Skema tal-kompressjoni',
'exif-photometricinterpretation'   => 'Struttura tal-pixel',
'exif-orientation'                 => 'Orjentazzjoni',
'exif-samplesperpixel'             => "Numru ta' komponenti",
'exif-planarconfiguration'         => 'Tqassim tad-dati',
'exif-ycbcrsubsampling'            => 'Rapport tal-provi Y / C',
'exif-ycbcrpositioning'            => 'Pożizzjonament tal-komponenti Y u C',
'exif-xresolution'                 => 'Riżoluzzjoni orizzontali',
'exif-yresolution'                 => 'Riżoluzzjoni vertikali',
'exif-resolutionunit'              => 'Unita tar-riżoluzzjoni X u Y',
'exif-stripoffsets'                => 'Post fejn jinsab id-data tal-istampa',
'exif-rowsperstrip'                => "Numru ta' fillieri għal kull strixxa",
'exif-stripbytecounts'             => 'Bytes għal kull strixxa kompressa',
'exif-jpeginterchangeformat'       => 'Pożizzjoni byte SOI JPEG',
'exif-jpeginterchangeformatlength' => "Numru ta' bytes ta' data JPEG",
'exif-transferfunction'            => "Funżjoni ta' transferiment",
'exif-whitepoint'                  => 'Kromatiku tal-punt abjad',
'exif-primarychromaticities'       => 'Kromitiku tal-kuluri primarji',
'exif-ycbcrcoefficients'           => 'Koeffiċjent matriċi tat-trasformazzjoni spazji tal-kuluri',
'exif-referenceblackwhite'         => "Par ta' valuri tar-riferiment (iswed jew abjad)",
'exif-datetime'                    => "Data jew ħin ta' modifiki tal-fajl",
'exif-imagedescription'            => 'Titlu tal-istampa',
'exif-make'                        => 'Manifattur tal-kameri',
'exif-model'                       => 'Mudell tal-kamera',
'exif-software'                    => 'Software',
'exif-artist'                      => 'Awtur',
'exif-copyright'                   => 'Informazzjoni dwar il-propjetá letterarja',
'exif-exifversion'                 => 'Verżjoni tal-format Exif',
'exif-flashpixversion'             => "Verżjoni sapportata ta' Flashpix",
'exif-colorspace'                  => 'Spazju tal-kulur',
'exif-componentsconfiguration'     => 'Tfissira dwar kull komponent',
'exif-compressedbitsperpixel'      => 'Għamla tal-kompressjoni tal-istampa',
'exif-pixelydimension'             => "Wisa' valida tal-istampa",
'exif-pixelxdimension'             => 'Għoli validu tal-istampa',
'exif-makernote'                   => 'Noti dwar il-maniffatur',
'exif-usercomment'                 => 'Noti tal-utent',
'exif-relatedsoundfile'            => 'Fajl relatat mal-ismiegħ',
'exif-datetimeoriginal'            => 'Data u ħin tal-ħolqien tad-data',
'exif-datetimedigitized'           => 'Data u ħin tad-diġitazzjoni',
'exif-subsectime'                  => "Data u ħin, frazzjoni ta' sekondi",
'exif-subsectimeoriginal'          => "Data u ħin tal-ħolqien, frazzjoni ta' sekonda",
'exif-subsectimedigitized'         => "Data u ħin ta' diġitazzjoni, frazzjoni ta' sekonda",
'exif-exposuretime'                => 'Ħin tal-wirja',
'exif-exposuretime-format'         => '$1 s ($2)',
'exif-fnumber'                     => 'Numru fokali',
'exif-exposureprogram'             => 'Programm tal-wirja',
'exif-spectralsensitivity'         => 'Sensitività spettrali',
'exif-isospeedratings'             => 'Sensibilità ISO',
'exif-oecf'                        => "Fattur ta' konverżjoni optoelettronika",
'exif-shutterspeedvalue'           => "Ħin ta' wirja",
'exif-aperturevalue'               => 'Ftuħ',
'exif-brightnessvalue'             => 'Ċarezza',
'exif-exposurebiasvalue'           => "Inklinazzjoni ta' wirja",
'exif-maxaperturevalue'            => 'Ftuħ massimu',
'exif-subjectdistance'             => 'Distanza mis-suġġett',
'exif-meteringmode'                => "Metodu ta' misurazzjoni",
'exif-lightsource'                 => 'Sorġent tad-dawl',
'exif-flash'                       => 'Flash',
'exif-focallength'                 => 'Distanza fokali objettiva',
'exif-subjectarea'                 => 'Spazju tas-suġġett',
'exif-flashenergy'                 => 'Saħħa tal-flash',
'exif-spatialfrequencyresponse'    => 'Risposta fi frekwenza spazjali',
'exif-focalplanexresolution'       => 'Riżoluzzjoni X fuq il-witja fokali',
'exif-focalplaneyresolution'       => 'Riżoluzzjoni Y fuq il-witja fokali',
'exif-focalplaneresolutionunit'    => "Unita ta' qisien tar-riżoluzzjoni fuq il-witja fokali",
'exif-subjectlocation'             => 'Post tas-suġġett',
'exif-exposureindex'               => 'Indiċi tal-wirja',
'exif-sensingmethod'               => 'Metodu ta tħaffif',
'exif-filesource'                  => 'Oriġini tal-fajl',
'exif-scenetype'                   => 'Tip tax-xena',
'exif-cfapattern'                  => 'Dispożizzjoni tal-filtru tal-kulur',
'exif-customrendered'              => 'Proċessar tal-istampi personalizzati',
'exif-exposuremode'                => "Stat ta' wirja",
'exif-whitebalance'                => 'Bilanċ l-abjad',
'exif-digitalzoomratio'            => 'Rapport zoom diġitali',
'exif-focallengthin35mmfilm'       => 'Daqs fokali ġo film 35mm',
'exif-scenecapturetype'            => "Tip ta' akwistazzjoni",
'exif-gaincontrol'                 => 'Kontroll tax-xena',
'exif-contrast'                    => 'Kontrolla kuntrast',
'exif-saturation'                  => 'Kontrolla saturazzjoni',
'exif-sharpness'                   => 'Kontrolla xfir',
'exif-devicesettingdescription'    => 'Deskrizzjoni tal-preferenzi dispositivi',
'exif-subjectdistancerange'        => 'Skala tad-distanza tas-suġġett',
'exif-imageuniqueid'               => 'ID uniku tal-istampa',
'exif-gpsversionid'                => "Verżjoni ta' tabella GPS",
'exif-gpslatituderef'              => 'Latitudni Tramuntana/Nofs inhar',
'exif-gpslatitude'                 => 'Latitudni',
'exif-gpslongituderef'             => 'Lonġitudni Lvant/Punent',
'exif-gpslongitude'                => 'Lonġitudni',
'exif-gpsaltituderef'              => 'Riferiment għall-għoli',
'exif-gpsaltitude'                 => 'Għoli',
'exif-gpstimestamp'                => 'Ħin GPS (arloġġ atomiku)',
'exif-gpssatellites'               => 'Satelliti użat għal qisien',
'exif-gpsstatus'                   => "Statut ta' minn jirċievi",
'exif-gpsmeasuremode'              => "Stat ta' qisien",
'exif-gpsdop'                      => 'Preċiżjoni tal-qisien',
'exif-gpsspeedref'                 => "Unit ta' miżuri ta' veloċita",
'exif-gpsspeed'                    => 'Veloċita tal-reċivitur tal-GPS',
'exif-gpstrackref'                 => 'Referiment tad-direzzjoni tal-moviment',
'exif-gpstrack'                    => 'Direzzjoni tal-moviment',
'exif-gpsimgdirectionref'          => 'Referiment tad-direzzjoni tal-istampa',
'exif-gpsimgdirection'             => 'Direzzjoni tal-istampa',
'exif-gpsmapdatum'                 => 'Informazzjoni geodetiku użat',
'exif-gpsdestlatituderef'          => 'Referiment tal-latitudni tad-destinazzjoni',
'exif-gpsdestlatitude'             => 'Destinazzjoni tal-latitudni',
'exif-gpsdestlongituderef'         => 'Referiment għal lonġitudni tad-destinazzjoni',
'exif-gpsdestlongitude'            => 'Destinazzjoni tal-lonġitudni',
'exif-gpsdestbearingref'           => 'Referiment tal-bronżina tad-destinazzjoni',
'exif-gpsdestbearing'              => 'Bronżina tad-destinazzjoni',
'exif-gpsdestdistanceref'          => 'Referiment għal distanza tad-destinazzjoni',
'exif-gpsdestdistance'             => 'Distanza tad-destinazzjoni',
'exif-gpsprocessingmethod'         => 'Isem tal-metodu tal-proċessar GPS',
'exif-gpsareainformation'          => 'Isem taż-żona tal-GPS',
'exif-gpsdatestamp'                => 'Data tal-GPS',
'exif-gpsdifferential'             => 'Tiswija differenzjali tal-GPS',

# EXIF attributes
'exif-compression-1' => 'L-Ebda',

'exif-unknowndate' => 'Data mhux magħrufa',

'exif-orientation-1' => 'Normali',
'exif-orientation-2' => 'Maqlub oriżżontali',
'exif-orientation-3' => 'Imdawwar 180°',
'exif-orientation-4' => 'Maqlub vertikali',
'exif-orientation-5' => 'Imdawwar 90° fis-sens kontra l-arloġġ u maqlub vertikali',
'exif-orientation-6' => 'Imdawwar 90° fis-sens tal-arloġġ',
'exif-orientation-7' => 'Imdawwar 90° fis-sens tal-arloġġ u maqlub vertikalment',
'exif-orientation-8' => 'Imdawwar 90° fis-sens kontra l-arloġġ',

'exif-planarconfiguration-1' => 'format imbaċċaċ',
'exif-planarconfiguration-2' => 'format tal-ippjanar',

'exif-componentsconfiguration-0' => 'nieqes',

'exif-exposureprogram-0' => 'Mhux definit',
'exif-exposureprogram-1' => 'Manwali',
'exif-exposureprogram-2' => 'Programma normali',
'exif-exposureprogram-3' => 'Priorita tal-ftuħ',
'exif-exposureprogram-4' => 'Priorita tal-għeluq',
'exif-exposureprogram-5' => 'Programm kreativ (inklinat lejn il-fond tal-linja)',
'exif-exposureprogram-6' => "Programm ta' azzjoni (inklinat lejn veloċita aktar mgħaġġla mill-ġdid)",
'exif-exposureprogram-7' => 'Ritratt (suġġett għal viċin bl-isfond mhux fokat)',
'exif-exposureprogram-8' => 'Panorama (suġġett il-bogħod bl-isfond mhux fokat)',

'exif-subjectdistance-value' => '$1 metri',

'exif-meteringmode-0'   => 'Mhux magħruf',
'exif-meteringmode-1'   => 'Medja',
'exif-meteringmode-2'   => 'Medja tal-piż ċentrali',
'exif-meteringmode-3'   => 'Spot',
'exif-meteringmode-4'   => 'MultiSpot',
'exif-meteringmode-5'   => 'Mudell',
'exif-meteringmode-6'   => 'Parzjali',
'exif-meteringmode-255' => 'Ieħor',

'exif-lightsource-0'   => 'Mhux magħruf',
'exif-lightsource-1'   => "B'inhar",
'exif-lightsource-2'   => 'Lampa tal-flourit',
'exif-lightsource-3'   => 'Lampa tungsten (dawl inkandestesti)',
'exif-lightsource-4'   => 'Flash',
'exif-lightsource-9'   => 'Temp tajjeb',
'exif-lightsource-10'  => 'Temp imsaħħab',
'exif-lightsource-11'  => 'Dell',
'exif-lightsource-12'  => 'Daylight fluorescent (D 5700 – 7100K)',
'exif-lightsource-13'  => 'Day white fluorescent (N 4600 – 5400K)',
'exif-lightsource-14'  => 'Cool white fluorescent (W 3900 – 4500K)',
'exif-lightsource-15'  => 'White fluorescent (WW 3200 – 3700K)',
'exif-lightsource-17'  => "Dawl ta' standard A",
'exif-lightsource-18'  => "Dawl ta' standard B",
'exif-lightsource-19'  => "Dawl ta' standard Ċ",
'exif-lightsource-20'  => 'Illuminanti D55',
'exif-lightsource-21'  => 'Illuminanti D65',
'exif-lightsource-22'  => 'Illuminanti D75',
'exif-lightsource-23'  => 'Illuminanti D50',
'exif-lightsource-24'  => 'Lampa tal-istudjo ISO għal tungsten',
'exif-lightsource-255' => 'Sorġent ieħor tad-dawl',

'exif-focalplaneresolutionunit-2' => 'pulzier',

'exif-sensingmethod-1' => 'Mhux definit',
'exif-sensingmethod-2' => "Sensur ta' l-area tal-kulur b'''chip'' waħda",
'exif-sensingmethod-3' => "Sensur ta' l-area tal-kulur b'żewġ chips",
'exif-sensingmethod-4' => "Sensur ta' l-area tal-kulur b'tliet chips",
'exif-sensingmethod-5' => "Sensur ta' l-area tal-kulur sequenzjali",
'exif-sensingmethod-7' => 'Sensur trilinjari',
'exif-sensingmethod-8' => 'Sensur linjari tal-kulur sequenzjali',

'exif-scenetype-1' => 'Fotografija diretta',

'exif-customrendered-0' => 'Proċess normali',
'exif-customrendered-1' => 'Proċess personalizzat',

'exif-exposuremode-0' => 'Wirja awtomatika',
'exif-exposuremode-1' => 'Wirja manwali',
'exif-exposuremode-2' => 'Brakit awtomatiku',

'exif-whitebalance-0' => 'Bilanċ tal-abjad awtomatiku',
'exif-whitebalance-1' => 'Bilanċ tal-abjad manwali',

'exif-scenecapturetype-0' => 'Standard',
'exif-scenecapturetype-1' => 'Panorama',
'exif-scenecapturetype-2' => 'Ritratt',
'exif-scenecapturetype-3' => 'Notturna',

'exif-gaincontrol-0' => 'Xejn',
'exif-gaincontrol-1' => 'Aktar qliegħ baxx',
'exif-gaincontrol-2' => 'Aktar qliegħ għoli',
'exif-gaincontrol-3' => 'Inqas qliegħ baxx',
'exif-gaincontrol-4' => 'Inqas qliegħ għoli',

'exif-contrast-0' => 'Normali',
'exif-contrast-1' => 'Kuntrast għoli',
'exif-contrast-2' => 'Kuntrast baxx',

'exif-saturation-0' => 'Normali',
'exif-saturation-1' => 'Saturazzjoni baxx',
'exif-saturation-2' => 'Saturazzjoni għolja',

'exif-sharpness-0' => 'Normali',
'exif-sharpness-1' => 'Mislut ftit',
'exif-sharpness-2' => 'Mislut aħrax',

'exif-subjectdistancerange-0' => 'Mhux magħruf',
'exif-subjectdistancerange-1' => 'Vast',
'exif-subjectdistancerange-2' => 'Suġġett viċin',
'exif-subjectdistancerange-3' => 'Suġġett il-bogħod',

# Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => 'Latitudni tat-tramuntana',
'exif-gpslatitude-s' => 'Latitudni tan-nofs inhar',

# Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => 'Loġitudni tal-lvant',
'exif-gpslongitude-w' => 'Lonġitudni tal-punent',

'exif-gpsstatus-a' => 'Qisien fil-progress',
'exif-gpsstatus-v' => 'Qisien interoperabili',

'exif-gpsmeasuremode-2' => 'Qisien bidimensjonali',
'exif-gpsmeasuremode-3' => 'Qisien tridimensjonali',

# Pseudotags used for GPSSpeedRef
'exif-gpsspeed-k' => 'Kilometru fis-siegħa',
'exif-gpsspeed-m' => 'Mili fis-siegħa',
'exif-gpsspeed-n' => 'Nodi',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'Direzzjoni vera',
'exif-gpsdirection-m' => 'Direzzjoni tal-kalamita',

# External editor support
'edit-externally'      => "Immodifika dan il-fajl b'użu ta' applikazzjoni esterna",
'edit-externally-help' => '(Għal aktar informazzjoni ara l-[http://www.mediawiki.org/wiki/Manual:External_editors istruzzjonijiet])',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'kollha',
'imagelistall'     => 'kollha',
'watchlistall2'    => 'kollha',
'namespacesall'    => 'kollha',
'monthsall'        => 'kollha',

# E-mail address confirmation
'confirmemail'             => 'Ikkonferma l-indirizz tal-posta elettronika',
'confirmemail_noemail'     => "M'għandekx indirizz tal-posta elettronika validu fil-[[Special:Preferences|preferenzi tal-utent]].",
'confirmemail_text'        => "{{SITENAME}} għandha bżonn li inti tivverifika l-indirizz tal-posta elettronika tiegħek qabel ma tkun tista' tagħmel użu mill-faċilitajiet tal-posta elettronika.
Attiva l-buttuna t'hawn taħt sabiex tibgħat posta ta' konferma fl-indirizz tiegħek.
Il-posta se tinkludi ħolqa li jkun fiha kodiċi;
iftaħ il-ħolqa fil-browżer tiegħek sabiex tikkonferma l-indirizz tal-posta elettronika tiegħek huwa validu.",
'confirmemail_pending'     => "Kodiċi ta' konfermazzjoni ġie postjat diġa;
jekk int riċentament ħloqt kont, l-aħjar li tistenna ftit minuti biex tasalek qabel ma tagħmel rikjesta għal kodiċi ġdida.",
'confirmemail_send'        => "Postja kodiċi ta' konfermazzjoni.",
'confirmemail_sent'        => 'Posta elettronika dwar konfermazzjoni ġiet postjata.',
'confirmemail_oncreate'    => "Il-Kodiċi ta' konfermazzjoni ġiet mibgħuta lejn l-indirizz tal-posta elettronika tiegħek, Din il-kodiċi m'għandhekx bżonna għall-aċċess, imma jkollhok bżonna biex ikollhok aċċess għal faċċilitajiet li għandhom x'jaqsmu mal-posta elettronika fuq wiki.",
'confirmemail_sendfailed'  => "{{SITENAME}} ma jistax jibgħat il-messaġġ ta' konferma. Jekk jogħġbok iċċekkja li l-indirizz elettroniku m'għandux karattri invalidi.

Messaġġ tal-problema tal-ippostjar: $1",
'confirmemail_invalid'     => "Kodiċi ta' konfermazzjoni invalida.
Il-kodiċi setgħat tkun skadiet.",
'confirmemail_needlogin'   => 'Huwa neċessarju $1 biex tikkonferma l-indirizz propju tal-posta elettronika.',
'confirmemail_success'     => "L-indirizz tal-posta elettronika tiegħek ġie konfermat. Issa tista' [[Special:UserLogin|tidħol fil-kont tiegħek]] u tgawdi bis-sħiħ din il-wiki.",
'confirmemail_loggedin'    => 'L-indirizz tal-posta elettronika tiegħek ġie ikkonfermat.',
'confirmemail_error'       => 'Problema fis-salvataġġ tal-konferma.',
'confirmemail_subject'     => '{{SITENAME}}: rikjesta tal-konferma tal-indirizz',
'confirmemail_body'        => 'Xi ħadd, probabbilment int, mill-indirizz tal-IP $1,
irreġistra l-kont "$2" b\'dan l-indirizz tal-posta elettronika fuq {{SITENAME}}.

Biex tikkonferma li dan il-kont huwa vera tiegħek u biex ikollok aċċess għall-faċilitajiet tal-posta elettronika ta\' {{SITENAME}}, iftaħ din il-ħolqa fil-browżer tiegħek:

$3

Jekk int *ma irreġistrajtx* il-kont, segwi l-ħolqa segwenti sabiex tħassar il-konferma tal-indirizz tal-posta elettronika:

$5

Din il-kodiċi tal-konferma se tiskadi fil-$4.',
'confirmemail_invalidated' => "Rikjesta ta' konfermazzjoni tal-indirizz tal-posta elettronika mħassra",
'invalidateemail'          => 'Ħassar il-konfermazzjoni tal-posta elettronika',

# Scary transclusion
'scarytranscludedisabled' => '[L-Inklużjoni tal-paġna fost is-siti tal-wiki mhux attivata]',
'scarytranscludefailed'   => '[Problema: Impossibli ġġib il-mudell $1]',
'scarytranscludetoolong'  => '[Problema: URL wisq twil]',

# Trackbacks
'trackbackbox'      => 'Informazzjoni tat-Trackbacks għal din il-paġna:<br />
$1',
'trackbackremove'   => '([$1 Ħassar])',
'trackbacklink'     => 'Trackback',
'trackbackdeleteok' => "Informazzjoni ta' trackback imħassar tajjeb.",

# Delete conflict
'deletedwhileediting' => "'''Twissija''': Din il-paġna ġiet imħassra wara li int bdejt timmodifikaha!",
'confirmrecreate'     => "L-Utent [[User:$1|$1]] ([[User talk:$1|diskussjoni]]) ħassar din il-paġna wara li bdejt tagħmel il-modifiki bir-raġuni:
: ''$2''
Jekk jogħġbok konferma jekk vera trid terġa' toħloq din il-paġna.",
'recreate'            => "Erġa' oħloq",

# action=purge
'confirm_purge_button' => 'OK',
'confirm-purge-top'    => "Ħassar il-''cache'' ta' din il-paġna?",
'confirm-purge-bottom' => 'L-iżvojtar tal-cache iwassal għall-viżwalizzazzjoni tal-verżjoni l-aktar aġġornata tal-paġna.',

# Separators for various lists, etc.
'semicolon-separator' => ';',
'autocomment-prefix'  => '-',

# Multipage image navigation
'imgmultipageprev' => "← il-paġna ta' qabel",
'imgmultipagenext' => 'il-paġna li jmiss →',
'imgmultigo'       => 'Mur!',
'imgmultigoto'     => 'Mur għal paġna $1',

# Table pager
'ascending_abbrev'         => 'axx',
'descending_abbrev'        => 'dixx',
'table_pager_next'         => 'Il-paġna li jmiss',
'table_pager_prev'         => "Il-paġna ta' qabel",
'table_pager_first'        => 'L-ewwel paġna',
'table_pager_last'         => 'L-aħħar paġna',
'table_pager_limit'        => "Uri $1 oġġett f'kull paġna",
'table_pager_limit_submit' => 'Mur',
'table_pager_empty'        => 'L-ebda riżultat',

# Auto-summaries
'autosumm-blank'   => 'Paġna żvojtata',
'autosumm-replace' => "Il-paġna ġiet mibdula ma' '$1'",
'autoredircomment' => 'Rindirizzat għal [[$1]]',
'autosumm-new'     => "Inħoloq paġna b'<nowiki>'</nowiki>$1'",

# Size units
'size-bytes'     => '$1 B',
'size-kilobytes' => '$1 KB',
'size-megabytes' => '$1 MB',
'size-gigabytes' => '$1 GB',

# Live preview
'livepreview-loading' => 'Tniżżil fil-progress…',
'livepreview-ready'   => 'Tniżżil… Lest!',
'livepreview-failed'  => 'Problema fil-funżjoni tal-previżjoni lajv.
Uża previżjoni standard.',
'livepreview-error'   => 'Impossibli toħloq konnessjoni: $1 "$2". Uża previżjoni standard.',

# Friendlier slave lag warnings
'lag-warn-normal' => "It-tibdil li hu aktar riċenti minn $1 {{PLURAL:$1|sekonda|sekonda}}, jista' li ma jiġix inkluż fil-lista.",
'lag-warn-high'   => 'Minħabba li l-aġġornament tas-server huwa eċċessivament bil-mod, il-modifiki fl-aħħar $1 {{PLURAL:$1|sekonda|sekonda}} ma jistgħux jiġu nklużi fil-lista.',

# Watchlist editor
'watchlistedit-numitems'       => "Il-lista ta' osservazzjoni tiegħek fiha {{PLURAL:$1|titlu|$1 titli}}, minbarra l-paġni ta' diskussjoni.",
'watchlistedit-noitems'        => "Il-lista ta' osservazzjoni tiegħek hija vojta.",
'watchlistedit-normal-title'   => 'Modifika l-lista tal-osservazzjonijiet speċjali',
'watchlistedit-normal-legend'  => 'Neħħi titli mil-lista tal-osservazzjonijiet speċjali',
'watchlistedit-normal-explain' => "Titli fil-lista ta' osservazzjoni tiegħek huma murija hawn taħt.
Biex tneħħi titlu, agħżel il-kaxxa ħdejn l-istess titlu, u agħfas \"{{int:Watchlistedit-normal-submit}}\".
Int tista' wkoll [[Special:Watchlist/raw|timmodifika l-lista f'format testwali]].",
'watchlistedit-normal-submit'  => 'Neħħi t-titli',
'watchlistedit-normal-done'    => '{{PLURAL:$1|Titlu kien imħassar|$1 titli kienu mħassra}} mil-lista tal-osservazzjonijiet speċjali tiegħek:',
'watchlistedit-raw-title'      => 'Modifiki osservazzjonijiet speċjali fil-forma testwali',
'watchlistedit-raw-legend'     => 'Modifika osservazzjonijiet speċjali testwali',
'watchlistedit-raw-explain'    => "Titli fil-lista tal-osservazzjonijiet speċjali huma murija hawn taħt, u jistgħu jiġu modifikati billi żżid u tneħħi mil-lista;
titlu f'kull linja.
Meta tlesti, agħfas fuq \"{{int:Watchlistedit-raw-submit}}\".
Inti tista' wkoll tuża' l-[[Special:Watchlist/edit|editur bl-interfaċċa standard]].",
'watchlistedit-raw-titles'     => 'Titli:',
'watchlistedit-raw-submit'     => 'Aġġorna l-lista',
'watchlistedit-raw-done'       => "Il-lista ta' osservazzjoni tiegħek ġiet aġġornata.",
'watchlistedit-raw-added'      => '{{PLURAL:$1|Titlu kien miżjud|$1 titli kienu miżjuda}}:',
'watchlistedit-raw-removed'    => '{{PLURAL:$1|Titlu tneħħa|$1 titli tneħħew}}:',

# Watchlist editing tools
'watchlisttools-view' => 'Uri modifika relevanti',
'watchlisttools-edit' => 'Uri u modifika fil-lista',
'watchlisttools-raw'  => 'Modifika l-modifika fil-format testwali',

# Iranian month names
'iranian-calendar-m1'  => 'Farvardin',
'iranian-calendar-m2'  => 'Ordibehesht',
'iranian-calendar-m3'  => 'Khordad',
'iranian-calendar-m4'  => 'Tir',
'iranian-calendar-m5'  => 'Mordad',
'iranian-calendar-m6'  => 'Shahrivar',
'iranian-calendar-m7'  => 'Mehr',
'iranian-calendar-m8'  => 'Aban',
'iranian-calendar-m9'  => 'Azar',
'iranian-calendar-m10' => 'Dey',

# Core parser functions
'unknown_extension_tag' => 'Estensjoni tat-tag mhux magħrufa "$1"',

# Special:Version
'version'                          => 'Verżjoni',
'version-extensions'               => 'Estensjonijiet installati',
'version-specialpages'             => 'Paġni speċjali',
'version-parserhooks'              => 'Hook tal-parser',
'version-variables'                => 'Varjabili',
'version-other'                    => 'Oħrajn',
'version-mediahandlers'            => 'Imradd tal-medja',
'version-hooks'                    => 'Hook',
'version-extension-functions'      => 'Funzjonijiet tal-estensjoni',
'version-parser-extensiontags'     => 'Tags magħrufa mill-parser introdott tal-estenżjoni',
'version-parser-function-hooks'    => 'Hook għal funżjoni tal-parser',
'version-skin-extension-functions' => 'Funżjoni marbut mall-aspett grafiku (skin) introdott mill-estenżjoni',
'version-hook-name'                => 'Isem tal-hook',
'version-hook-subscribedby'        => 'Reġistrat minn',
'version-version'                  => '(Verżjoni $1)',
'version-license'                  => 'Liċensja',
'version-software'                 => 'Software installat',
'version-software-product'         => 'Prodott',
'version-software-version'         => 'Verżjoni',

# Special:FilePath
'filepath'         => 'Post tal-fajl',
'filepath-page'    => 'Fajl:',
'filepath-submit'  => 'Mur',
'filepath-summary' => 'Din il-paġna speċjali tagħti lura l-indirizz komplet tal-posta għal fajl.
Stampi huwa mogħrija b\'risoluzzjoni sħiħa, tipi tal-fajl oħrajn jibdew bil-program assoċjat magħhom direttament.

Daħħal l-isem tal-fajl bla l-prefiss "{{ns:file}}:".',

# Special:FileDuplicateSearch
'fileduplicatesearch'          => 'Fittex fajls duplikati',
'fileduplicatesearch-summary'  => "Fittex fajls duplikati fil-bażi għal valur ''hash''.

Daħħal l-isem tal-fajl mingħajr il-prefiss \"{{ns:file}}:\".",
'fileduplicatesearch-legend'   => 'Fittex għal duplikat',
'fileduplicatesearch-filename' => 'Isem il-fajl:',
'fileduplicatesearch-submit'   => 'Fittex',
'fileduplicatesearch-info'     => '$1 × $2 pixel<br />Daqs tal-fajl: $3<br />tip MIME: $4',
'fileduplicatesearch-result-1' => 'Il-Fajl "$1" m\'għandux duplikat eżatt.',
'fileduplicatesearch-result-n' => 'Il-Fajl "$1" għandu {{PLURAL:$2|duplikat identiku|$2 duplikati identiki}}.',

# Special:SpecialPages
'specialpages'                   => 'Paġni speċjali',
'specialpages-note'              => '----
* Paġni normali speċjali.
* <strong class="mw-specialpagerestricted">Paġni speċjali limitati.</strong>',
'specialpages-group-maintenance' => 'Rapporti tal-manteniment',
'specialpages-group-other'       => 'Paġni speċjali oħrajn',
'specialpages-group-login'       => 'Idħol / irreġistra',
'specialpages-group-changes'     => 'L-Aħħar modifiki u reġistri',
'specialpages-group-media'       => 'Fajls multimedjali - rapporti u tellgħar',
'specialpages-group-users'       => 'Utenti u drittijiet',
'specialpages-group-highuse'     => 'Paġni użati ħafna',
'specialpages-group-pages'       => "Elenki ta' paġni",
'specialpages-group-pagetools'   => 'Għodda tal-paġna',
'specialpages-group-wiki'        => 'Għodda u informazzjoni fuq il-proġett',
'specialpages-group-redirects'   => "Paġni speċjali ta' rindirizz",
'specialpages-group-spam'        => 'Għodda kontra l-ispam',

# Special:BlankPage
'blankpage'              => 'Paġna vojta',
'intentionallyblankpage' => 'Din il-paġna tħalliet vojta ataposta',

# Special:Tags
'tag-filter-submit' => 'Filtru',
'tags-edit'         => 'editja',

# Database error messages
'dberr-header'   => 'Din il-wiki għandha problema',
'dberr-problems' => 'Jiddispjaċina! Dan is-sit għandu diffikultajiet tekniċi.',
'dberr-again'    => "Prova stenna ftit minuti u erġa' tella' l-paġna.",

# HTML forms
'htmlform-invalid-input'       => "Hemm xi problemi f'dak li daħħalt",
'htmlform-select-badoption'    => 'Il-valur li speċifikajt mhuwiex għażla valida.',
'htmlform-int-invalid'         => 'Il-valur li speċifikajt mhuwiex sħiħ.',
'htmlform-int-toolow'          => "Il-valur li speċifikajt hu inferjuri għall-minimu ta' $1",
'htmlform-int-toohigh'         => "Il-valur li speċifikjat hu 'l fuq mill-massimu ta' $1",
'htmlform-submit'              => 'Ibgħat',
'htmlform-reset'               => 'Annulla l-modifiki',
'htmlform-selectorother-other' => 'Oħrajn',

);
