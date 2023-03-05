<?php
/** Swedish (svenska)
 *
 * @file
 * @ingroup Languages
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

/** @phpcs-require-sorted-array */
$specialPageAliases = [
	'Activeusers'               => [ 'Aktiva_användare' ],
	'Allmessages'               => [ 'Systemmeddelanden' ],
	'AllMyUploads'              => [ 'Alla_mina_uppladdnignar', 'Alla_mina_filer' ],
	'Allpages'                  => [ 'Alla_sidor' ],
	'Ancientpages'              => [ 'Gamla_sidor' ],
	'Badtitle'                  => [ 'Dålig_titel' ],
	'Blankpage'                 => [ 'Tom_sida' ],
	'Block'                     => [ 'Blockera' ],
	'BlockList'                 => [ 'Blockeringslista' ],
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
	'LinkSearch'                => [ 'Länksökning' ],
	'Listadmins'                => [ 'Administratörer' ],
	'Listbots'                  => [ 'Robotlista' ],
	'ListDuplicatedFiles'       => [ 'Lista_dubblettfiler' ],
	'Listfiles'                 => [ 'Fillista', 'Bildlista' ],
	'Listgrouprights'           => [ 'Grupprättighetslista' ],
	'Listredirects'             => [ 'Omdirigeringar' ],
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
	'PageLanguage'              => [ 'Sidspråk' ],
	'PagesWithProp'             => [ 'Sidor_med_en_sidegenskap' ],
	'PasswordReset'             => [ 'Återställ_lösenord' ],
	'PermanentLink'             => [ 'Permanent_länk' ],
	'Preferences'               => [ 'Inställningar' ],
	'Protectedpages'            => [ 'Skyddade_sidor' ],
	'Protectedtitles'           => [ 'Skyddade_titlar' ],
	'RandomInCategory'          => [ 'Slumpsida_i_kategori' ],
	'Randompage'                => [ 'Slumpsida' ],
	'Randomredirect'            => [ 'Slumpomdirigering' ],
	'Recentchanges'             => [ 'Senaste_ändringar' ],
	'Recentchangeslinked'       => [ 'Senaste_relaterade_ändringar' ],
	'Redirect'                  => [ 'Omdirigering' ],
	'Renameuser'                => [ 'Användarnamnbyte' ],
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

/** @phpcs-require-sorted-array */
$magicWords = [
	'basepagename'              => [ '1', 'GRUNDSIDNAMN', 'BASEPAGENAME' ],
	'basepagenamee'             => [ '1', 'GRUNDSIDNAMNE', 'BASEPAGENAMEE' ],
	'contentlanguage'           => [ '1', 'INNEHÅLLSSPRÅK', 'CONTENTLANGUAGE', 'CONTENTLANG' ],
	'currentday'                => [ '1', 'NUVARANDEDAG', 'NUDAG', 'CURRENTDAY' ],
	'currentday2'               => [ '1', 'NUVARANDEDAG2', 'NUDAG2', 'CURRENTDAY2' ],
	'currentdayname'            => [ '1', 'NUVARANDEDAGSNAMN', 'NUDAGSNAMN', 'CURRENTDAYNAME' ],
	'currentdow'                => [ '1', 'NUVARANDEVECKODAG', 'CURRENTDOW' ],
	'currenthour'               => [ '1', 'NUVARANDETIMME', 'NUTIMME', 'CURRENTHOUR' ],
	'currentmonth'              => [ '1', 'NUVARANDEMÅNAD', 'NUMÅNAD', 'CURRENTMONTH', 'CURRENTMONTH2' ],
	'currentmonth1'             => [ '1', 'NUVARANDEMÅNAD1', 'CURRENTMONTH1' ],
	'currentmonthabbrev'        => [ '1', 'NUVARANDEMÅNADKORT', 'NUMÅNADKORT', 'CURRENTMONTHABBREV' ],
	'currentmonthname'          => [ '1', 'NUVARANDEMÅNADSNAMN', 'NUMÅNADSNAMN', 'CURRENTMONTHNAME' ],
	'currenttime'               => [ '1', 'NUVARANDETID', 'NUTID', 'CURRENTTIME' ],
	'currenttimestamp'          => [ '1', 'NUTIDSTÄMPEL', 'CURRENTTIMESTAMP' ],
	'currentversion'            => [ '1', 'NUVARANDEVERSION', 'NUVERSION', 'CURRENTVERSION' ],
	'currentweek'               => [ '1', 'NUVARANDEVECKA', 'NUVECKA', 'CURRENTWEEK' ],
	'currentyear'               => [ '1', 'NUVARANDEÅR', 'NUÅR', 'CURRENTYEAR' ],
	'defaultsort'               => [ '1', 'STANDARDSORTERING:', 'DEFAULTSORT:', 'DEFAULTSORTKEY:', 'DEFAULTCATEGORYSORT:' ],
	'displaytitle'              => [ '1', 'VISATITEL', 'DISPLAYTITLE' ],
	'filepath'                  => [ '0', 'FILSÖKVÄG:', 'FILEPATH:' ],
	'forcetoc'                  => [ '0', '__ALLTIDINNEHÅLLSFÖRTECKNING__', '__FORCETOC__' ],
	'formatdate'                => [ '0', 'formateradatum', 'datumformat', 'formatdate', 'dateformat' ],
	'formatnum'                 => [ '0', 'FORMATERANUM', 'FORMATERATAL', 'FORMATNUM' ],
	'fullpagename'              => [ '1', 'HELASIDNAMNET', 'FULLPAGENAME' ],
	'fullpagenamee'             => [ '1', 'HELASIDNAMNETE', 'FULLPAGENAMEE' ],
	'fullurl'                   => [ '0', 'FULLTURL:', 'FULLURL:' ],
	'fullurle'                  => [ '0', 'FULLTURLE:', 'FULLURLE:' ],
	'gender'                    => [ '0', 'KÖN:', 'GENDER:' ],
	'grammar'                   => [ '0', 'GRAMMATIK:', 'GRAMMAR:' ],
	'hiddencat'                 => [ '1', '__DOLDKAT__', '__HIDDENCAT__' ],
	'img_baseline'              => [ '1', 'baslinje', 'baseline' ],
	'img_border'                => [ '1', 'kantlinje', 'border' ],
	'img_bottom'                => [ '1', 'botten', 'bottom' ],
	'img_center'                => [ '1', 'centrerad', 'center', 'centre' ],
	'img_framed'                => [ '1', 'ram', 'inramad', 'frame', 'framed', 'enframed' ],
	'img_frameless'             => [ '1', 'ramlös', 'frameless' ],
	'img_left'                  => [ '1', 'vänster', 'left' ],
	'img_link'                  => [ '1', 'länk=$1', 'link=$1' ],
	'img_manualthumb'           => [ '1', 'miniatyr=$1', 'mini=$1', 'thumbnail=$1', 'thumb=$1' ],
	'img_middle'                => [ '1', 'mitten', 'middle' ],
	'img_none'                  => [ '1', 'ingen', 'none' ],
	'img_page'                  => [ '1', 'sida=$1', 'sida $1', 'page=$1', 'page $1' ],
	'img_right'                 => [ '1', 'höger', 'right' ],
	'img_sub'                   => [ '1', 'ned', 'sub' ],
	'img_super'                 => [ '1', 'upp', 'super', 'sup' ],
	'img_text_bottom'           => [ '1', 'text-botten', 'text-bottom' ],
	'img_text_top'              => [ '1', 'text-topp', 'text-top' ],
	'img_thumbnail'             => [ '1', 'miniatyr', 'mini', 'thumb', 'thumbnail' ],
	'img_top'                   => [ '1', 'topp', 'top' ],
	'img_upright'               => [ '1', 'stående', 'stående=$1', 'stående $1', 'upright', 'upright=$1', 'upright $1' ],
	'index'                     => [ '1', '__INDEXERA__', '__INDEX__' ],
	'language'                  => [ '0', '#SPRÅK:', '#LANGUAGE:' ],
	'lc'                        => [ '0', 'LB:', 'LC:' ],
	'lcfirst'                   => [ '0', 'LBFÖRST:', 'LCFIRST:' ],
	'localday'                  => [ '1', 'LOKALDAG', 'LOCALDAY' ],
	'localday2'                 => [ '1', 'LOKALDAG2', 'LOCALDAY2' ],
	'localdayname'              => [ '1', 'LOKALDAGSNAMN', 'LOCALDAYNAME' ],
	'localdow'                  => [ '1', 'LOKALVECKODAG', 'LOCALDOW' ],
	'localhour'                 => [ '1', 'LOKALTIMME', 'LOCALHOUR' ],
	'localmonth'                => [ '1', 'LOKALMÅNAD', 'LOCALMONTH', 'LOCALMONTH2' ],
	'localmonth1'               => [ '1', 'LOKALMÅNAD1', 'LOCALMONTH1' ],
	'localmonthabbrev'          => [ '1', 'LOKALMÅNADKORT', 'LOCALMONTHABBREV' ],
	'localmonthname'            => [ '1', 'LOKALMÅNADSNAMN', 'LOCALMONTHNAME' ],
	'localtime'                 => [ '1', 'LOKALTID', 'LOCALTIME' ],
	'localtimestamp'            => [ '1', 'LOKALTIDSTÄMPEL', 'LOCALTIMESTAMP' ],
	'localurl'                  => [ '0', 'LOKALURL:', 'LOCALURL:' ],
	'localurle'                 => [ '0', 'LOKALURLE:', 'LOCALURLE:' ],
	'localweek'                 => [ '1', 'LOKALVECKA', 'LOCALWEEK' ],
	'localyear'                 => [ '1', 'LOKALTÅR', 'LOCALYEAR' ],
	'msg'                       => [ '0', 'MED:', 'MSG:' ],
	'msgnw'                     => [ '0', 'MEDNW:', 'MSGNW:' ],
	'namespace'                 => [ '1', 'NAMNRYMD', 'NAMESPACE' ],
	'namespacee'                => [ '1', 'NAMNRYMDE', 'NAMESPACEE' ],
	'newsectionlink'            => [ '1', '__NYTTAVSNITTLÄNK__', '__NEWSECTIONLINK__' ],
	'noeditsection'             => [ '0', '__INTEREDIGERASEKTION__', '__NOEDITSECTION__' ],
	'nogallery'                 => [ '0', '__INGETGALLERI__', '__NOGALLERY__' ],
	'noindex'                   => [ '1', '__INTEINDEXERA_', '__NOINDEX__' ],
	'notoc'                     => [ '0', '__INGENINNEHÅLLSFÖRTECKNING__', '__NOTOC__' ],
	'ns'                        => [ '0', 'NR:', 'NS:' ],
	'numberingroup'             => [ '1', 'ANTALIGRUPP', 'NUMBERINGROUP', 'NUMINGROUP' ],
	'numberofactiveusers'       => [ '1', 'ANTALAKTIVAANVÄNDARE', 'NUMBEROFACTIVEUSERS' ],
	'numberofadmins'            => [ '1', 'ANTALADMINS', 'ANTALADMINISTRATÖRER', 'NUMBEROFADMINS' ],
	'numberofarticles'          => [ '1', 'ANTALARTIKLAR', 'NUMBEROFARTICLES' ],
	'numberofedits'             => [ '1', 'ANTALREDIGERINGAR', 'NUMBEROFEDITS' ],
	'numberoffiles'             => [ '1', 'ANTALFILER', 'NUMBEROFFILES' ],
	'numberofpages'             => [ '1', 'ANTALSIDOR', 'NUMBEROFPAGES' ],
	'numberofusers'             => [ '1', 'ANTALANVÄNDARE', 'NUMBEROFUSERS' ],
	'pagename'                  => [ '1', 'SIDNAMN', 'PAGENAME' ],
	'pagenamee'                 => [ '1', 'SIDNAMNE', 'PAGENAMEE' ],
	'pagesincategory'           => [ '1', 'SIDORIKATEGORI', 'PAGESINCATEGORY', 'PAGESINCAT' ],
	'pagesinnamespace'          => [ '1', 'SIDORINAMNRYMD:', 'SIDORINR:', 'PAGESINNAMESPACE:', 'PAGESINNS:' ],
	'pagesize'                  => [ '1', 'SIDSTORLEK', 'PAGESIZE' ],
	'protectionlevel'           => [ '1', 'SKYDDSNIVÅ', 'PROTECTIONLEVEL' ],
	'raw'                       => [ '0', 'RÅ:', 'RAW:' ],
	'redirect'                  => [ '0', '#OMDIRIGERING', '#REDIRECT' ],
	'revisionday'               => [ '1', 'REVISIONSDAG', 'REVISIONDAY' ],
	'revisionday2'              => [ '1', 'REVISIONSDAG2', 'REVISIONDAY2' ],
	'revisionid'                => [ '1', 'REVISIONSID', 'REVISIONID' ],
	'revisionmonth'             => [ '1', 'REVISIONSMÅNAD', 'REVISIONMONTH' ],
	'revisiontimestamp'         => [ '1', 'REVISIONSTIDSSTÄMPEL', 'REVISIONTIMESTAMP' ],
	'revisionuser'              => [ '1', 'REVISIONSANVÄNDARE', 'REVISIONUSER' ],
	'revisionyear'              => [ '1', 'REVISIONSÅR', 'REVISIONYEAR' ],
	'scriptpath'                => [ '0', 'SKRIPTSÖKVÄG', 'SCRIPTPATH' ],
	'servername'                => [ '0', 'SERVERNAMN', 'SERVERNAME' ],
	'sitename'                  => [ '1', 'SAJTNAMN', 'SITENAMN', 'SITENAME' ],
	'staticredirect'            => [ '1', '__STATISKOMDIRIGERING__', '__STATICREDIRECT__' ],
	'subjectspace'              => [ '1', 'ARTIKELRYMD', 'SUBJECTSPACE', 'ARTICLESPACE' ],
	'subjectspacee'             => [ '1', 'ARTIKELRYMDE', 'SUBJECTSPACEE', 'ARTICLESPACEE' ],
	'subpagename'               => [ '1', 'UNDERSIDNAMN', 'SUBPAGENAME' ],
	'subpagenamee'              => [ '1', 'UNDERSIDNAMNE', 'SUBPAGENAMEE' ],
	'subst'                     => [ '0', 'BYT:', 'SUBST:' ],
	'tag'                       => [ '0', 'tagg', 'tag' ],
	'talkpagename'              => [ '1', 'DISKUSSIONSSIDNAMN', 'TALKPAGENAME' ],
	'talkpagenamee'             => [ '1', 'DISKUSSIONSSIDNAMNE', 'TALKPAGENAMEE' ],
	'talkspace'                 => [ '1', 'DISKUSSIONSRYMD', 'TALKSPACE' ],
	'talkspacee'                => [ '1', 'DISKUSSIONSRYMDE', 'TALKSPACEE' ],
	'toc'                       => [ '0', '__INNEHÅLLSFÖRTECKNING__', '__TOC__' ],
	'uc'                        => [ '0', 'SB:', 'UC:' ],
	'ucfirst'                   => [ '0', 'UCFIRST', 'SBFÖRST:', 'UCFIRST:' ],
];

$linkTrail = '/^([a-zåäöéÅÄÖÉ]+)(.*)$/sDu';
$separatorTransformTable = [
	',' => "\u{00A0}", // T4749
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
