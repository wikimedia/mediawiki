<?php
/** Swedish (svenska)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Ainali
 * @author Boivie
 * @author Cybjit
 * @author Dafer45
 * @author Diupwijk
 * @author EPO
 * @author Fader
 * @author Fluff
 * @author GameOn
 * @author Geitost
 * @author Greggegorius
 * @author Grillo
 * @author Habj
 * @author Habjchen
 * @author Hangsna
 * @author Hannibal
 * @author Haxpett
 * @author Jon Harald Søby
 * @author Jopparn
 * @author Kaganer
 * @author LPfi
 * @author Lejonel
 * @author Leo Johannes
 * @author Liftarn
 * @author Lokal Profil
 * @author M.M.S.
 * @author MagnusA
 * @author Micke
 * @author Mikez
 * @author NH
 * @author Najami
 * @author Nemo bis
 * @author Nghtwlkr
 * @author Ozp
 * @author Per
 * @author Petter Strandmark
 * @author Poxnar
 * @author Purodha
 * @author Rotsee
 * @author S.Örvarr.S
 * @author Sannab
 * @author Sendelbach
 * @author Sertion
 * @author Skalman
 * @author Stefan2
 * @author StefanB
 * @author Steinninn
 * @author Str4nd
 * @author Thurs
 * @author Tobulos1
 * @author VickyC
 * @author Warrakkk
 * @author Where next Columbus
 * @author Where next Columbus?
 * @author WikiPhoenix
 * @author לערי ריינהארט
 */

$namespaceNames = array(
	NS_MEDIA            => 'Media',
	NS_SPECIAL          => 'Special',
	NS_TALK             => 'Diskussion',
	NS_USER             => 'Användare',
	NS_USER_TALK        => 'Användardiskussion',
	NS_PROJECT_TALK     => '$1diskussion',
	NS_FILE             => 'Fil',
	NS_FILE_TALK        => 'Fildiskussion',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki-diskussion',
	NS_TEMPLATE         => 'Mall',
	NS_TEMPLATE_TALK    => 'Malldiskussion',
	NS_HELP             => 'Hjälp',
	NS_HELP_TALK        => 'Hjälpdiskussion',
	NS_CATEGORY         => 'Kategori',
	NS_CATEGORY_TALK    => 'Kategoridiskussion',
);

$namespaceAliases = array(
	'Bild' => NS_FILE,
	'Bilddiskussion' => NS_FILE_TALK,
	'MediaWiki_diskussion' => NS_MEDIAWIKI_TALK,
	'Hjälp_diskussion'     => NS_HELP_TALK
);

#!!# Translation <b>Prefixindex</b> for <a href="#mw-sp-magic-Prefixindex">Prefixindex</a> is not in normalised form, which is <b>PrefixIndex</b>
$specialPageAliases = array(
	'Allmessages'               => array( 'Systemmeddelanden' ),
	'Allpages'                  => array( 'Alla_sidor' ),
	'Ancientpages'              => array( 'Gamla_sidor' ),
	'Blankpage'                 => array( 'Tom_sida' ),
	'Block'                     => array( 'Blockera' ),
	'Booksources'               => array( 'Bokkällor' ),
	'BrokenRedirects'           => array( 'Trasiga_omdirigeringar', 'Dåliga_omdirigeringar' ),
	'Categories'                => array( 'Kategorier' ),
	'ChangePassword'            => array( 'Återställ_lösenord' ),
	'Confirmemail'              => array( 'Bekräfta_epost' ),
	'Contributions'             => array( 'Bidrag' ),
	'CreateAccount'             => array( 'Skapa_konto' ),
	'Deadendpages'              => array( 'Sidor_utan_länkar', 'Sidor_utan_länkar_från' ),
	'DeletedContributions'      => array( 'Raderade_bidrag' ),
	'DoubleRedirects'           => array( 'Dubbla_omdirigeringar' ),
	'Emailuser'                 => array( 'E-mail' ),
	'ExpandTemplates'           => array( 'Expandera_mallar', 'Utöka_mallar' ),
	'Export'                    => array( 'Exportera' ),
	'Fewestrevisions'           => array( 'Minst_versioner' ),
	'FileDuplicateSearch'       => array( 'Dublettfilsökning' ),
	'Filepath'                  => array( 'Filsökväg' ),
	'Import'                    => array( 'Importera' ),
	'Invalidateemail'           => array( 'Ogiltigförklara_epost' ),
	'BlockList'                 => array( 'Blockeringslista' ),
	'LinkSearch'                => array( 'Länksökning' ),
	'Listadmins'                => array( 'Administratörer' ),
	'Listbots'                  => array( 'Robotlista' ),
	'Listfiles'                 => array( 'Fillista', 'Bildlista' ),
	'Listgrouprights'           => array( 'Grupprättighetslista' ),
	'Listredirects'             => array( 'Omdirigeringar' ),
	'Listusers'                 => array( 'Användare', 'Användarlista' ),
	'Lockdb'                    => array( 'Lås_databasen' ),
	'Log'                       => array( 'Logg' ),
	'Lonelypages'               => array( 'Föräldralösa_sidor', 'Övergivna_sidor', 'Sidor_utan_länkar_till' ),
	'Longpages'                 => array( 'Långa_sidor' ),
	'MergeHistory'              => array( 'Slå_ihop_historik' ),
	'MIMEsearch'                => array( 'MIME-sökning' ),
	'Mostcategories'            => array( 'Flest_kategorier' ),
	'Mostimages'                => array( 'Mest_länkade_filer', 'Flest_bilder' ),
	'Mostlinked'                => array( 'Mest_länkade_sidor' ),
	'Mostlinkedcategories'      => array( 'Största_kategorier' ),
	'Mostlinkedtemplates'       => array( 'Mest_använda_mallar' ),
	'Mostrevisions'             => array( 'Flest_versioner' ),
	'Movepage'                  => array( 'Flytta' ),
	'Mycontributions'           => array( 'Mina_bidrag' ),
	'Mypage'                    => array( 'Min_sida' ),
	'Mytalk'                    => array( 'Min_diskussion' ),
	'Myuploads'                 => array( 'Mina_uppladdningar' ),
	'Newimages'                 => array( 'Nya_bilder' ),
	'Newpages'                  => array( 'Nya_sidor' ),
	'Popularpages'              => array( 'Populära_sidor' ),
	'Preferences'               => array( 'Inställningar' ),
	'Protectedpages'            => array( 'Skyddade_sidor' ),
	'Protectedtitles'           => array( 'Skyddade_titlar' ),
	'Randompage'                => array( 'Slumpsida' ),
	'Randomredirect'            => array( 'Slumpomdirigering' ),
	'Recentchanges'             => array( 'Senaste_ändringar' ),
	'Recentchangeslinked'       => array( 'Senaste_relaterade_ändringar' ),
	'Revisiondelete'            => array( 'Radera_version' ),
	'Search'                    => array( 'Sök' ),
	'Shortpages'                => array( 'Korta_sidor' ),
	'Specialpages'              => array( 'Specialsidor' ),
	'Statistics'                => array( 'Statistik' ),
	'Tags'                      => array( 'Märken', 'Taggar' ),
	'Unblock'                   => array( 'Avblockera' ),
	'Uncategorizedcategories'   => array( 'Okategoriserade_kategorier' ),
	'Uncategorizedimages'       => array( 'Okategoriserade_filer', 'Okategoriserade_bilder' ),
	'Uncategorizedpages'        => array( 'Okategoriserade_sidor' ),
	'Uncategorizedtemplates'    => array( 'Okategoriserade_mallar' ),
	'Undelete'                  => array( 'Återställ' ),
	'Unlockdb'                  => array( 'Lås_upp_databasen' ),
	'Unusedcategories'          => array( 'Oanvända_kategorier' ),
	'Unusedimages'              => array( 'Oanvända_filer', 'Oanvända_bilder' ),
	'Unusedtemplates'           => array( 'Oanvända_mallar' ),
	'Unwatchedpages'            => array( 'Obevakade_sidor' ),
	'Upload'                    => array( 'Uppladdning' ),
	'Userlogin'                 => array( 'Inloggning' ),
	'Userlogout'                => array( 'Utloggning' ),
	'Userrights'                => array( 'Rättigheter' ),
	'Wantedcategories'          => array( 'Önskade_kategorier' ),
	'Wantedfiles'               => array( 'Önskade_filer' ),
	'Wantedpages'               => array( 'Önskade_sidor', 'Trasiga_länkar' ),
	'Wantedtemplates'           => array( 'Önskade_mallar' ),
	'Watchlist'                 => array( 'Bevakningslista', 'Övervakningslista' ),
	'Whatlinkshere'             => array( 'Länkar_hit' ),
	'Withoutinterwiki'          => array( 'Utan_interwikilänkar' ),
);

$magicWords = array(
	'redirect'                  => array( '0', '#OMDIRIGERING', '#REDIRECT' ),
	'notoc'                     => array( '0', '__INGENINNEHÅLLSFÖRTECKNING__', '__NOTOC__' ),
	'nogallery'                 => array( '0', '__INGETGALLERI__', '__NOGALLERY__' ),
	'forcetoc'                  => array( '0', '__ALLTIDINNEHÅLLSFÖRTECKNING__', '__FORCETOC__' ),
	'toc'                       => array( '0', '__INNEHÅLLSFÖRTECKNING__', '__TOC__' ),
	'noeditsection'             => array( '0', '__INTEREDIGERASEKTION__', '__NOEDITSECTION__' ),
	'currentmonth'              => array( '1', 'NUVARANDEMÅNAD', 'NUMÅNAD', 'CURRENTMONTH', 'CURRENTMONTH2' ),
	'currentmonth1'             => array( '1', 'NUVARANDEMÅNAD1', 'CURRENTMONTH1' ),
	'currentmonthname'          => array( '1', 'NUVARANDEMÅNADSNAMN', 'NUMÅNADSNAMN', 'CURRENTMONTHNAME' ),
	'currentmonthabbrev'        => array( '1', 'NUVARANDEMÅNADKORT', 'NUMÅNADKORT', 'CURRENTMONTHABBREV' ),
	'currentday'                => array( '1', 'NUVARANDEDAG', 'NUDAG', 'CURRENTDAY' ),
	'currentday2'               => array( '1', 'NUVARANDEDAG2', 'NUDAG2', 'CURRENTDAY2' ),
	'currentdayname'            => array( '1', 'NUVARANDEDAGSNAMN', 'NUDAGSNAMN', 'CURRENTDAYNAME' ),
	'currentyear'               => array( '1', 'NUVARANDEÅR', 'NUÅR', 'CURRENTYEAR' ),
	'currenttime'               => array( '1', 'NUVARANDETID', 'NUTID', 'CURRENTTIME' ),
	'currenthour'               => array( '1', 'NUVARANDETIMME', 'NUTIMME', 'CURRENTHOUR' ),
	'localmonth'                => array( '1', 'LOKALMÅNAD', 'LOCALMONTH', 'LOCALMONTH2' ),
	'localmonth1'               => array( '1', 'LOKALMÅNAD1', 'LOCALMONTH1' ),
	'localmonthname'            => array( '1', 'LOKALMÅNADSNAMN', 'LOCALMONTHNAME' ),
	'localmonthabbrev'          => array( '1', 'LOKALMÅNADKORT', 'LOCALMONTHABBREV' ),
	'localday'                  => array( '1', 'LOKALDAG', 'LOCALDAY' ),
	'localday2'                 => array( '1', 'LOKALDAG2', 'LOCALDAY2' ),
	'localdayname'              => array( '1', 'LOKALDAGSNAMN', 'LOCALDAYNAME' ),
	'localyear'                 => array( '1', 'LOKALTÅR', 'LOCALYEAR' ),
	'localtime'                 => array( '1', 'LOKALTID', 'LOCALTIME' ),
	'localhour'                 => array( '1', 'LOKALTIMME', 'LOCALHOUR' ),
	'numberofpages'             => array( '1', 'ANTALSIDOR', 'NUMBEROFPAGES' ),
	'numberofarticles'          => array( '1', 'ANTALARTIKLAR', 'NUMBEROFARTICLES' ),
	'numberoffiles'             => array( '1', 'ANTALFILER', 'NUMBEROFFILES' ),
	'numberofusers'             => array( '1', 'ANTALANVÄNDARE', 'NUMBEROFUSERS' ),
	'numberofactiveusers'       => array( '1', 'ANTALAKTIVAANVÄNDARE', 'NUMBEROFACTIVEUSERS' ),
	'numberofedits'             => array( '1', 'ANTALREDIGERINGAR', 'NUMBEROFEDITS' ),
	'numberofviews'             => array( '1', 'ANTALVISNINGAR', 'NUMBEROFVIEWS' ),
	'pagename'                  => array( '1', 'SIDNAMN', 'PAGENAME' ),
	'pagenamee'                 => array( '1', 'SIDNAMNE', 'PAGENAMEE' ),
	'namespace'                 => array( '1', 'NAMNRYMD', 'NAMESPACE' ),
	'namespacee'                => array( '1', 'NAMNRYMDE', 'NAMESPACEE' ),
	'talkspace'                 => array( '1', 'DISKUSSIONSRYMD', 'TALKSPACE' ),
	'talkspacee'                => array( '1', 'DISKUSSIONSRYMDE', 'TALKSPACEE' ),
	'subjectspace'              => array( '1', 'ARTIKELRYMD', 'SUBJECTSPACE', 'ARTICLESPACE' ),
	'subjectspacee'             => array( '1', 'ARTIKELRYMDE', 'SUBJECTSPACEE', 'ARTICLESPACEE' ),
	'fullpagename'              => array( '1', 'HELASIDNAMNET', 'FULLPAGENAME' ),
	'fullpagenamee'             => array( '1', 'HELASIDNAMNETE', 'FULLPAGENAMEE' ),
	'subpagename'               => array( '1', 'UNDERSIDNAMN', 'SUBPAGENAME' ),
	'subpagenamee'              => array( '1', 'UNDERSIDNAMNE', 'SUBPAGENAMEE' ),
	'basepagename'              => array( '1', 'GRUNDSIDNAMN', 'BASEPAGENAME' ),
	'basepagenamee'             => array( '1', 'GRUNDSIDNAMNE', 'BASEPAGENAMEE' ),
	'talkpagename'              => array( '1', 'DISKUSSIONSSIDNAMN', 'TALKPAGENAME' ),
	'talkpagenamee'             => array( '1', 'DISKUSSIONSSIDNAMNE', 'TALKPAGENAMEE' ),
	'msg'                       => array( '0', 'MED:', 'MSG:' ),
	'subst'                     => array( '0', 'BYT:', 'SUBST:' ),
	'msgnw'                     => array( '0', 'MEDNW:', 'MSGNW:' ),
	'img_thumbnail'             => array( '1', 'miniatyr', 'mini', 'thumbnail', 'thumb' ),
	'img_manualthumb'           => array( '1', 'miniatyr=$1', 'mini=$1', 'thumbnail=$1', 'thumb=$1' ),
	'img_right'                 => array( '1', 'höger', 'right' ),
	'img_left'                  => array( '1', 'vänster', 'left' ),
	'img_none'                  => array( '1', 'ingen', 'none' ),
	'img_center'                => array( '1', 'centrerad', 'center', 'centre' ),
	'img_framed'                => array( '1', 'inramad', 'ram', 'framed', 'enframed', 'frame' ),
	'img_frameless'             => array( '1', 'ramlös', 'frameless' ),
	'img_page'                  => array( '1', 'sida=$1', 'sida $1', 'page=$1', 'page $1' ),
	'img_upright'               => array( '1', 'stående', 'stående=$1', 'stående $1', 'upright', 'upright=$1', 'upright $1' ),
	'img_border'                => array( '1', 'kantlinje', 'border' ),
	'img_baseline'              => array( '1', 'baslinje', 'baseline' ),
	'img_sub'                   => array( '1', 'ned', 'sub' ),
	'img_super'                 => array( '1', 'upp', 'super', 'sup' ),
	'img_top'                   => array( '1', 'topp', 'top' ),
	'img_text_top'              => array( '1', 'text-topp', 'text-top' ),
	'img_middle'                => array( '1', 'mitten', 'middle' ),
	'img_bottom'                => array( '1', 'botten', 'bottom' ),
	'img_text_bottom'           => array( '1', 'text-botten', 'text-bottom' ),
	'img_link'                  => array( '1', 'länk=$1', 'link=$1' ),
	'sitename'                  => array( '1', 'SAJTNAMN', 'SITENAMN', 'SITENAME' ),
	'ns'                        => array( '0', 'NR:', 'NS:' ),
	'localurl'                  => array( '0', 'LOKALURL:', 'LOCALURL:' ),
	'localurle'                 => array( '0', 'LOKALURLE:', 'LOCALURLE:' ),
	'servername'                => array( '0', 'SERVERNAMN', 'SERVERNAME' ),
	'scriptpath'                => array( '0', 'SKRIPTSÖKVÄG', 'SCRIPTPATH' ),
	'grammar'                   => array( '0', 'GRAMMATIK:', 'GRAMMAR:' ),
	'gender'                    => array( '0', 'KÖN:', 'GENDER:' ),
	'currentweek'               => array( '1', 'NUVARANDEVECKA', 'NUVECKA', 'CURRENTWEEK' ),
	'currentdow'                => array( '1', 'NUVARANDEVECKODAG', 'CURRENTDOW' ),
	'localweek'                 => array( '1', 'LOKALVECKA', 'LOCALWEEK' ),
	'localdow'                  => array( '1', 'LOKALVECKODAG', 'LOCALDOW' ),
	'revisionid'                => array( '1', 'REVISIONSID', 'REVISIONID' ),
	'revisionday'               => array( '1', 'REVISIONSDAG', 'REVISIONDAY' ),
	'revisionday2'              => array( '1', 'REVISIONSDAG2', 'REVISIONDAY2' ),
	'revisionmonth'             => array( '1', 'REVISIONSMÅNAD', 'REVISIONMONTH' ),
	'revisionyear'              => array( '1', 'REVISIONSÅR', 'REVISIONYEAR' ),
	'revisiontimestamp'         => array( '1', 'REVISIONSTIDSSTÄMPEL', 'REVISIONTIMESTAMP' ),
	'revisionuser'              => array( '1', 'REVISIONSANVÄNDARE', 'REVISIONUSER' ),
	'fullurl'                   => array( '0', 'FULLTURL:', 'FULLURL:' ),
	'fullurle'                  => array( '0', 'FULLTURLE:', 'FULLURLE:' ),
	'lcfirst'                   => array( '0', 'LBFÖRST:', 'LCFIRST:' ),
	'ucfirst'                   => array( '0', 'UCFIRST', 'SBFÖRST:', 'UCFIRST:' ),
	'lc'                        => array( '0', 'LB:', 'LC:' ),
	'uc'                        => array( '0', 'SB:', 'UC:' ),
	'raw'                       => array( '0', 'RÅ:', 'RAW:' ),
	'displaytitle'              => array( '1', 'VISATITEL', 'DISPLAYTITLE' ),
	'newsectionlink'            => array( '1', '__NYTTAVSNITTLÄNK__', '__NEWSECTIONLINK__' ),
	'currentversion'            => array( '1', 'NUVARANDEVERSION', 'NUVERSION', 'CURRENTVERSION' ),
	'currenttimestamp'          => array( '1', 'NUTIDSTÄMPEL', 'CURRENTTIMESTAMP' ),
	'localtimestamp'            => array( '1', 'LOKALTIDSTÄMPEL', 'LOCALTIMESTAMP' ),
	'language'                  => array( '0', '#SPRÅK:', '#LANGUAGE:' ),
	'contentlanguage'           => array( '1', 'INNEHÅLLSSPRÅK', 'CONTENTLANGUAGE', 'CONTENTLANG' ),
	'pagesinnamespace'          => array( '1', 'SIDORINAMNRYMD:', 'SIDORINR:', 'PAGESINNAMESPACE:', 'PAGESINNS:' ),
	'numberofadmins'            => array( '1', 'ANTALADMINS', 'ANTALADMINISTRATÖRER', 'NUMBEROFADMINS' ),
	'formatnum'                 => array( '0', 'FORMATERANUM', 'FORMATERATAL', 'FORMATNUM' ),
	'defaultsort'               => array( '1', 'STANDARDSORTERING:', 'DEFAULTSORT:', 'DEFAULTSORTKEY:', 'DEFAULTCATEGORYSORT:' ),
	'filepath'                  => array( '0', 'FILSÖKVÄG:', 'FILEPATH:' ),
	'tag'                       => array( '0', 'tagg', 'tag' ),
	'hiddencat'                 => array( '1', '__DOLDKAT__', '__HIDDENCAT__' ),
	'pagesincategory'           => array( '1', 'SIDORIKATEGORI', 'PAGESINCATEGORY', 'PAGESINCAT' ),
	'pagesize'                  => array( '1', 'SIDSTORLEK', 'PAGESIZE' ),
	'index'                     => array( '1', '__INDEXERA__', '__INDEX__' ),
	'noindex'                   => array( '1', '__INTEINDEXERA_', '__NOINDEX__' ),
	'numberingroup'             => array( '1', 'ANTALIGRUPP', 'NUMBERINGROUP', 'NUMINGROUP' ),
	'staticredirect'            => array( '1', '__STATISKOMDIRIGERING__', '__STATICREDIRECT__' ),
	'protectionlevel'           => array( '1', 'SKYDDSNIVÅ', 'PROTECTIONLEVEL' ),
	'formatdate'                => array( '0', 'formateradatum', 'datumformat', 'formatdate', 'dateformat' ),
);

$linkTrail = '/^([a-zåäöéÅÄÖÉ]+)(.*)$/sDu';
$separatorTransformTable =  array(
	',' => "\xc2\xa0", // @bug 2749
	'.' => ','
);

$dateFormats = array(
	'mdy time' => 'H.i',
	'mdy date' => 'F j, Y',
	'mdy both' => 'F j, Y "kl." H.i',

	'dmy time' => 'H.i',
	'dmy date' => 'j F Y',
	'dmy both' => 'j F Y "kl." H.i',

	'ymd time' => 'H.i',
	'ymd date' => 'Y F j',
	'ymd both' => 'Y F j "kl." H.i',
);

