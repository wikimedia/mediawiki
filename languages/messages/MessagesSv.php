<?php
/** Swedish (svenska)
 *
 * To improve a translation please visit https://translatewiki.net
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

$namespaceNames = [
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
];

$namespaceAliases = [
	'Bild' => NS_FILE,
	'Bilddiskussion' => NS_FILE_TALK,
	'MediaWiki_diskussion' => NS_MEDIAWIKI_TALK,
	'Hjälp_diskussion'     => NS_HELP_TALK
];

$specialPageAliases = [
	'Activeusers'               => [ 'Aktiva_användare' ],
	'Allmessages'               => [ 'Systemmeddelanden' ],
	'AllMyUploads'              => [ 'Alla_mina_uppladdnignar', 'Alla_mina_filer' ],
	'Allpages'                  => [ 'Alla_sidor' ],
	'Ancientpages'              => [ 'Gamla_sidor' ],
	'Badtitle'                  => [ 'Dålig_titel' ],
	'Blankpage'                 => [ 'Tom_sida' ],
	'Block'                     => [ 'Blockera' ],
	'Booksources'               => [ 'Bokkällor' ],
	'BrokenRedirects'           => [ 'Trasiga_omdirigeringar', 'Dåliga_omdirigeringar' ],
	'Categories'                => [ 'Kategorier' ],
	'ChangeEmail'               => [ 'Ändra_e-postadress' ],
	'ChangePassword'            => [ 'Ändra_lösenord' ],
	'ComparePages'              => [ 'Jämför_sidor' ],
	'Confirmemail'              => [ 'Bekräfta_epost' ],
	'Contributions'             => [ 'Bidrag' ],
	'CreateAccount'             => [ 'Skapa_konto' ],
	'Deadendpages'              => [ 'Sidor_utan_länkar', 'Sidor_utan_länkar_från' ],
	'DeletedContributions'      => [ 'Raderade_bidrag' ],
	'DoubleRedirects'           => [ 'Dubbla_omdirigeringar' ],
	'EditWatchlist'             => [ 'Redigera_bevakningslista' ],
	'Emailuser'                 => [ 'E-post', 'E-mail' ],
	'ExpandTemplates'           => [ 'Expandera_mallar', 'Utöka_mallar' ],
	'Export'                    => [ 'Exportera' ],
	'Fewestrevisions'           => [ 'Minst_versioner' ],
	'FileDuplicateSearch'       => [ 'Dublettfilsökning' ],
	'Filepath'                  => [ 'Filsökväg' ],
	'Import'                    => [ 'Importera' ],
	'Invalidateemail'           => [ 'Ogiltigförklara_epost' ],
	'JavaScriptTest'            => [ 'JavaScript_test' ],
	'BlockList'                 => [ 'Blockeringslista' ],
	'LinkSearch'                => [ 'Länksökning' ],
	'Listadmins'                => [ 'Administratörer' ],
	'Listbots'                  => [ 'Robotlista' ],
	'Listfiles'                 => [ 'Fillista', 'Bildlista' ],
	'Listgrouprights'           => [ 'Grupprättighetslista' ],
	'Listredirects'             => [ 'Omdirigeringar' ],
	'ListDuplicatedFiles'       => [ 'Lista_dubblettfiler' ],
	'Listusers'                 => [ 'Användare', 'Användarlista' ],
	'Lockdb'                    => [ 'Lås_databasen' ],
	'Log'                       => [ 'Logg' ],
	'Lonelypages'               => [ 'Föräldralösa_sidor', 'Övergivna_sidor', 'Sidor_utan_länkar_till' ],
	'Longpages'                 => [ 'Långa_sidor' ],
	'MediaStatistics'           => [ 'Media_statistik' ],
	'MergeHistory'              => [ 'Slå_ihop_historik' ],
	'MIMEsearch'                => [ 'MIME-sökning' ],
	'Mostcategories'            => [ 'Flest_kategorier' ],
	'Mostimages'                => [ 'Mest_länkade_filer', 'Flest_bilder' ],
	'Mostinterwikis'            => [ 'Flest_interwikilänkar' ],
	'Mostlinked'                => [ 'Mest_länkade_sidor' ],
	'Mostlinkedcategories'      => [ 'Största_kategorier' ],
	'Mostlinkedtemplates'       => [ 'Mest_använda_mallar' ],
	'Mostrevisions'             => [ 'Flest_versioner' ],
	'Movepage'                  => [ 'Flytta' ],
	'Mycontributions'           => [ 'Mina_bidrag' ],
	'MyLanguage'                => [ 'Mitt_språk' ],
	'Mypage'                    => [ 'Min_sida' ],
	'Mytalk'                    => [ 'Min_diskussion' ],
	'Myuploads'                 => [ 'Mina_uppladdningar' ],
	'Newimages'                 => [ 'Nya_filer', 'Nya_bilder' ],
	'Newpages'                  => [ 'Nya_sidor' ],
	'PagesWithProp'             => [ 'Sidor_med_en_sidegenskap' ],
	'PageLanguage'              => [ 'Sidspråk' ],
	'PasswordReset'             => [ 'Återställ_lösenord' ],
	'PermanentLink'             => [ 'Permanent_länk' ],
	'Preferences'               => [ 'Inställningar' ],
	'Protectedpages'            => [ 'Skyddade_sidor' ],
	'Protectedtitles'           => [ 'Skyddade_titlar' ],
	'Randompage'                => [ 'Slumpsida' ],
	'RandomInCategory'          => [ 'Slumpsida_i_kategori' ],
	'Randomredirect'            => [ 'Slumpomdirigering' ],
	'Recentchanges'             => [ 'Senaste_ändringar' ],
	'Recentchangeslinked'       => [ 'Senaste_relaterade_ändringar' ],
	'Redirect'                  => [ 'Omdirigering' ],
	'ResetTokens'               => [ 'Återställ_nycklar' ],
	'Revisiondelete'            => [ 'Radera_version' ],
	'Search'                    => [ 'Sök' ],
	'Shortpages'                => [ 'Korta_sidor' ],
	'Specialpages'              => [ 'Specialsidor' ],
	'Statistics'                => [ 'Statistik' ],
	'Tags'                      => [ 'Märken', 'Taggar' ],
	'TrackingCategories'        => [ 'Spårningskategorier' ],
	'Unblock'                   => [ 'Avblockera' ],
	'Uncategorizedcategories'   => [ 'Okategoriserade_kategorier' ],
	'Uncategorizedimages'       => [ 'Okategoriserade_filer', 'Okategoriserade_bilder' ],
	'Uncategorizedpages'        => [ 'Okategoriserade_sidor' ],
	'Uncategorizedtemplates'    => [ 'Okategoriserade_mallar' ],
	'Undelete'                  => [ 'Återställ' ],
	'Unlockdb'                  => [ 'Lås_upp_databasen' ],
	'Unusedcategories'          => [ 'Oanvända_kategorier' ],
	'Unusedimages'              => [ 'Oanvända_filer', 'Oanvända_bilder' ],
	'Unusedtemplates'           => [ 'Oanvända_mallar' ],
	'Unwatchedpages'            => [ 'Obevakade_sidor' ],
	'Upload'                    => [ 'Uppladdning' ],
	'Userlogin'                 => [ 'Inloggning' ],
	'Userlogout'                => [ 'Utloggning' ],
	'Userrights'                => [ 'Rättigheter' ],
	'Wantedcategories'          => [ 'Önskade_kategorier' ],
	'Wantedfiles'               => [ 'Önskade_filer' ],
	'Wantedpages'               => [ 'Önskade_sidor', 'Trasiga_länkar' ],
	'Wantedtemplates'           => [ 'Önskade_mallar' ],
	'Watchlist'                 => [ 'Bevakningslista', 'Övervakningslista' ],
	'Whatlinkshere'             => [ 'Länkar_hit' ],
	'Withoutinterwiki'          => [ 'Utan_interwikilänkar' ],
];

$magicWords = [
	'redirect'                  => [ '0', '#OMDIRIGERING', '#REDIRECT' ],
	'notoc'                     => [ '0', '__INGENINNEHÅLLSFÖRTECKNING__', '__NOTOC__' ],
	'nogallery'                 => [ '0', '__INGETGALLERI__', '__NOGALLERY__' ],
	'forcetoc'                  => [ '0', '__ALLTIDINNEHÅLLSFÖRTECKNING__', '__FORCETOC__' ],
	'toc'                       => [ '0', '__INNEHÅLLSFÖRTECKNING__', '__TOC__' ],
	'noeditsection'             => [ '0', '__INTEREDIGERASEKTION__', '__NOEDITSECTION__' ],
	'currentmonth'              => [ '1', 'NUVARANDEMÅNAD', 'NUMÅNAD', 'CURRENTMONTH', 'CURRENTMONTH2' ],
	'currentmonth1'             => [ '1', 'NUVARANDEMÅNAD1', 'CURRENTMONTH1' ],
	'currentmonthname'          => [ '1', 'NUVARANDEMÅNADSNAMN', 'NUMÅNADSNAMN', 'CURRENTMONTHNAME' ],
	'currentmonthabbrev'        => [ '1', 'NUVARANDEMÅNADKORT', 'NUMÅNADKORT', 'CURRENTMONTHABBREV' ],
	'currentday'                => [ '1', 'NUVARANDEDAG', 'NUDAG', 'CURRENTDAY' ],
	'currentday2'               => [ '1', 'NUVARANDEDAG2', 'NUDAG2', 'CURRENTDAY2' ],
	'currentdayname'            => [ '1', 'NUVARANDEDAGSNAMN', 'NUDAGSNAMN', 'CURRENTDAYNAME' ],
	'currentyear'               => [ '1', 'NUVARANDEÅR', 'NUÅR', 'CURRENTYEAR' ],
	'currenttime'               => [ '1', 'NUVARANDETID', 'NUTID', 'CURRENTTIME' ],
	'currenthour'               => [ '1', 'NUVARANDETIMME', 'NUTIMME', 'CURRENTHOUR' ],
	'localmonth'                => [ '1', 'LOKALMÅNAD', 'LOCALMONTH', 'LOCALMONTH2' ],
	'localmonth1'               => [ '1', 'LOKALMÅNAD1', 'LOCALMONTH1' ],
	'localmonthname'            => [ '1', 'LOKALMÅNADSNAMN', 'LOCALMONTHNAME' ],
	'localmonthabbrev'          => [ '1', 'LOKALMÅNADKORT', 'LOCALMONTHABBREV' ],
	'localday'                  => [ '1', 'LOKALDAG', 'LOCALDAY' ],
	'localday2'                 => [ '1', 'LOKALDAG2', 'LOCALDAY2' ],
	'localdayname'              => [ '1', 'LOKALDAGSNAMN', 'LOCALDAYNAME' ],
	'localyear'                 => [ '1', 'LOKALTÅR', 'LOCALYEAR' ],
	'localtime'                 => [ '1', 'LOKALTID', 'LOCALTIME' ],
	'localhour'                 => [ '1', 'LOKALTIMME', 'LOCALHOUR' ],
	'numberofpages'             => [ '1', 'ANTALSIDOR', 'NUMBEROFPAGES' ],
	'numberofarticles'          => [ '1', 'ANTALARTIKLAR', 'NUMBEROFARTICLES' ],
	'numberoffiles'             => [ '1', 'ANTALFILER', 'NUMBEROFFILES' ],
	'numberofusers'             => [ '1', 'ANTALANVÄNDARE', 'NUMBEROFUSERS' ],
	'numberofactiveusers'       => [ '1', 'ANTALAKTIVAANVÄNDARE', 'NUMBEROFACTIVEUSERS' ],
	'numberofedits'             => [ '1', 'ANTALREDIGERINGAR', 'NUMBEROFEDITS' ],
	'pagename'                  => [ '1', 'SIDNAMN', 'PAGENAME' ],
	'pagenamee'                 => [ '1', 'SIDNAMNE', 'PAGENAMEE' ],
	'namespace'                 => [ '1', 'NAMNRYMD', 'NAMESPACE' ],
	'namespacee'                => [ '1', 'NAMNRYMDE', 'NAMESPACEE' ],
	'talkspace'                 => [ '1', 'DISKUSSIONSRYMD', 'TALKSPACE' ],
	'talkspacee'                => [ '1', 'DISKUSSIONSRYMDE', 'TALKSPACEE' ],
	'subjectspace'              => [ '1', 'ARTIKELRYMD', 'SUBJECTSPACE', 'ARTICLESPACE' ],
	'subjectspacee'             => [ '1', 'ARTIKELRYMDE', 'SUBJECTSPACEE', 'ARTICLESPACEE' ],
	'fullpagename'              => [ '1', 'HELASIDNAMNET', 'FULLPAGENAME' ],
	'fullpagenamee'             => [ '1', 'HELASIDNAMNETE', 'FULLPAGENAMEE' ],
	'subpagename'               => [ '1', 'UNDERSIDNAMN', 'SUBPAGENAME' ],
	'subpagenamee'              => [ '1', 'UNDERSIDNAMNE', 'SUBPAGENAMEE' ],
	'basepagename'              => [ '1', 'GRUNDSIDNAMN', 'BASEPAGENAME' ],
	'basepagenamee'             => [ '1', 'GRUNDSIDNAMNE', 'BASEPAGENAMEE' ],
	'talkpagename'              => [ '1', 'DISKUSSIONSSIDNAMN', 'TALKPAGENAME' ],
	'talkpagenamee'             => [ '1', 'DISKUSSIONSSIDNAMNE', 'TALKPAGENAMEE' ],
	'msg'                       => [ '0', 'MED:', 'MSG:' ],
	'subst'                     => [ '0', 'BYT:', 'SUBST:' ],
	'msgnw'                     => [ '0', 'MEDNW:', 'MSGNW:' ],
	'img_thumbnail'             => [ '1', 'miniatyr', 'mini', 'thumb', 'thumbnail' ],
	'img_manualthumb'           => [ '1', 'miniatyr=$1', 'mini=$1', 'thumbnail=$1', 'thumb=$1' ],
	'img_right'                 => [ '1', 'höger', 'right' ],
	'img_left'                  => [ '1', 'vänster', 'left' ],
	'img_none'                  => [ '1', 'ingen', 'none' ],
	'img_center'                => [ '1', 'centrerad', 'center', 'centre' ],
	'img_framed'                => [ '1', 'ram', 'inramad', 'frame', 'framed', 'enframed' ],
	'img_frameless'             => [ '1', 'ramlös', 'frameless' ],
	'img_page'                  => [ '1', 'sida=$1', 'sida $1', 'page=$1', 'page $1' ],
	'img_upright'               => [ '1', 'stående', 'stående=$1', 'stående $1', 'upright', 'upright=$1', 'upright $1' ],
	'img_border'                => [ '1', 'kantlinje', 'border' ],
	'img_baseline'              => [ '1', 'baslinje', 'baseline' ],
	'img_sub'                   => [ '1', 'ned', 'sub' ],
	'img_super'                 => [ '1', 'upp', 'super', 'sup' ],
	'img_top'                   => [ '1', 'topp', 'top' ],
	'img_text_top'              => [ '1', 'text-topp', 'text-top' ],
	'img_middle'                => [ '1', 'mitten', 'middle' ],
	'img_bottom'                => [ '1', 'botten', 'bottom' ],
	'img_text_bottom'           => [ '1', 'text-botten', 'text-bottom' ],
	'img_link'                  => [ '1', 'länk=$1', 'link=$1' ],
	'sitename'                  => [ '1', 'SAJTNAMN', 'SITENAMN', 'SITENAME' ],
	'ns'                        => [ '0', 'NR:', 'NS:' ],
	'localurl'                  => [ '0', 'LOKALURL:', 'LOCALURL:' ],
	'localurle'                 => [ '0', 'LOKALURLE:', 'LOCALURLE:' ],
	'servername'                => [ '0', 'SERVERNAMN', 'SERVERNAME' ],
	'scriptpath'                => [ '0', 'SKRIPTSÖKVÄG', 'SCRIPTPATH' ],
	'grammar'                   => [ '0', 'GRAMMATIK:', 'GRAMMAR:' ],
	'gender'                    => [ '0', 'KÖN:', 'GENDER:' ],
	'currentweek'               => [ '1', 'NUVARANDEVECKA', 'NUVECKA', 'CURRENTWEEK' ],
	'currentdow'                => [ '1', 'NUVARANDEVECKODAG', 'CURRENTDOW' ],
	'localweek'                 => [ '1', 'LOKALVECKA', 'LOCALWEEK' ],
	'localdow'                  => [ '1', 'LOKALVECKODAG', 'LOCALDOW' ],
	'revisionid'                => [ '1', 'REVISIONSID', 'REVISIONID' ],
	'revisionday'               => [ '1', 'REVISIONSDAG', 'REVISIONDAY' ],
	'revisionday2'              => [ '1', 'REVISIONSDAG2', 'REVISIONDAY2' ],
	'revisionmonth'             => [ '1', 'REVISIONSMÅNAD', 'REVISIONMONTH' ],
	'revisionyear'              => [ '1', 'REVISIONSÅR', 'REVISIONYEAR' ],
	'revisiontimestamp'         => [ '1', 'REVISIONSTIDSSTÄMPEL', 'REVISIONTIMESTAMP' ],
	'revisionuser'              => [ '1', 'REVISIONSANVÄNDARE', 'REVISIONUSER' ],
	'fullurl'                   => [ '0', 'FULLTURL:', 'FULLURL:' ],
	'fullurle'                  => [ '0', 'FULLTURLE:', 'FULLURLE:' ],
	'lcfirst'                   => [ '0', 'LBFÖRST:', 'LCFIRST:' ],
	'ucfirst'                   => [ '0', 'UCFIRST', 'SBFÖRST:', 'UCFIRST:' ],
	'lc'                        => [ '0', 'LB:', 'LC:' ],
	'uc'                        => [ '0', 'SB:', 'UC:' ],
	'raw'                       => [ '0', 'RÅ:', 'RAW:' ],
	'displaytitle'              => [ '1', 'VISATITEL', 'DISPLAYTITLE' ],
	'newsectionlink'            => [ '1', '__NYTTAVSNITTLÄNK__', '__NEWSECTIONLINK__' ],
	'currentversion'            => [ '1', 'NUVARANDEVERSION', 'NUVERSION', 'CURRENTVERSION' ],
	'currenttimestamp'          => [ '1', 'NUTIDSTÄMPEL', 'CURRENTTIMESTAMP' ],
	'localtimestamp'            => [ '1', 'LOKALTIDSTÄMPEL', 'LOCALTIMESTAMP' ],
	'language'                  => [ '0', '#SPRÅK:', '#LANGUAGE:' ],
	'contentlanguage'           => [ '1', 'INNEHÅLLSSPRÅK', 'CONTENTLANGUAGE', 'CONTENTLANG' ],
	'pagesinnamespace'          => [ '1', 'SIDORINAMNRYMD:', 'SIDORINR:', 'PAGESINNAMESPACE:', 'PAGESINNS:' ],
	'numberofadmins'            => [ '1', 'ANTALADMINS', 'ANTALADMINISTRATÖRER', 'NUMBEROFADMINS' ],
	'formatnum'                 => [ '0', 'FORMATERANUM', 'FORMATERATAL', 'FORMATNUM' ],
	'defaultsort'               => [ '1', 'STANDARDSORTERING:', 'DEFAULTSORT:', 'DEFAULTSORTKEY:', 'DEFAULTCATEGORYSORT:' ],
	'filepath'                  => [ '0', 'FILSÖKVÄG:', 'FILEPATH:' ],
	'tag'                       => [ '0', 'tagg', 'tag' ],
	'hiddencat'                 => [ '1', '__DOLDKAT__', '__HIDDENCAT__' ],
	'pagesincategory'           => [ '1', 'SIDORIKATEGORI', 'PAGESINCATEGORY', 'PAGESINCAT' ],
	'pagesize'                  => [ '1', 'SIDSTORLEK', 'PAGESIZE' ],
	'index'                     => [ '1', '__INDEXERA__', '__INDEX__' ],
	'noindex'                   => [ '1', '__INTEINDEXERA_', '__NOINDEX__' ],
	'numberingroup'             => [ '1', 'ANTALIGRUPP', 'NUMBERINGROUP', 'NUMINGROUP' ],
	'staticredirect'            => [ '1', '__STATISKOMDIRIGERING__', '__STATICREDIRECT__' ],
	'protectionlevel'           => [ '1', 'SKYDDSNIVÅ', 'PROTECTIONLEVEL' ],
	'formatdate'                => [ '0', 'formateradatum', 'datumformat', 'formatdate', 'dateformat' ],
];

$linkTrail = '/^([a-zåäöéÅÄÖÉ]+)(.*)$/sDu';
$separatorTransformTable = [
	',' => "\xc2\xa0", // T4749
	'.' => ','
];

$dateFormats = [
	'mdy time' => 'H.i',
	'mdy date' => 'F j, Y',
	'mdy both' => 'F j, Y "kl." H.i',

	'dmy time' => 'H.i',
	'dmy date' => 'j F Y',
	'dmy both' => 'j F Y "kl." H.i',

	'ymd time' => 'H.i',
	'ymd date' => 'Y F j',
	'ymd both' => 'Y F j "kl." H.i',
];
