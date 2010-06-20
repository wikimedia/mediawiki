<?php
/** Swedish (Svenska)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Ainali
 * @author Boivie
 * @author Dafer45
 * @author Fluff
 * @author GameOn
 * @author Grillo
 * @author Habj
 * @author Habjchen
 * @author Jon Harald Søby
 * @author Lejonel
 * @author Leo Johannes
 * @author Lokal Profil
 * @author M.M.S.
 * @author MagnusA
 * @author Micke
 * @author NH
 * @author Najami
 * @author Nghtwlkr
 * @author Ozp
 * @author Per
 * @author Petter Strandmark
 * @author Poxnar
 * @author Purodha
 * @author S.Örvarr.S
 * @author Sannab
 * @author Sertion
 * @author Skalman
 * @author StefanB
 * @author Steinninn
 * @author Str4nd
 * @author Where next Columbus
 * @author Where next Columbus?
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

$specialPageAliases = array(
	'DoubleRedirects'           => array( 'Dubbla omdirigeringar' ),
	'BrokenRedirects'           => array( 'Trasiga omdirigeringar', 'Dåliga omdirigeringar' ),
	'Disambiguations'           => array( 'Förgreningssidor' ),
	'Userlogin'                 => array( 'Inloggning' ),
	'Userlogout'                => array( 'Utloggning' ),
	'CreateAccount'             => array( 'Skapa konto' ),
	'Preferences'               => array( 'Inställningar' ),
	'Watchlist'                 => array( 'Bevakningslista', 'Övervakningslista' ),
	'Recentchanges'             => array( 'Senaste ändringar' ),
	'Upload'                    => array( 'Uppladdning' ),
	'Listfiles'                 => array( 'Fillista', 'Bildlista' ),
	'Newimages'                 => array( 'Nya bilder' ),
	'Listusers'                 => array( 'Användare', 'Användarlista' ),
	'Listgrouprights'           => array( 'Grupprättighetslista' ),
	'Statistics'                => array( 'Statistik' ),
	'Randompage'                => array( 'Slumpsida' ),
	'Lonelypages'               => array( 'Övergivna sidor', 'Sidor utan länkar till' ),
	'Uncategorizedpages'        => array( 'Okategoriserade sidor' ),
	'Uncategorizedcategories'   => array( 'Okategoriserade kategorier' ),
	'Uncategorizedimages'       => array( 'Okategoriserade filer', 'Okategoriserade bilder' ),
	'Uncategorizedtemplates'    => array( 'Okategoriserade mallar' ),
	'Unusedcategories'          => array( 'Oanvända kategorier' ),
	'Unusedimages'              => array( 'Oanvända filer', 'Oanvända bilder' ),
	'Wantedpages'               => array( 'Önskade sidor', 'Trasiga länkar' ),
	'Wantedcategories'          => array( 'Önskade kategorier' ),
	'Wantedfiles'               => array( 'Önskade filer' ),
	'Wantedtemplates'           => array( 'Önskade mallar' ),
	'Mostlinked'                => array( 'Mest länkade sidor' ),
	'Mostlinkedcategories'      => array( 'Största kategorier' ),
	'Mostlinkedtemplates'       => array( 'Mest använda mallar' ),
	'Mostimages'                => array( 'Mest länkade filer', 'Flest bilder' ),
	'Mostcategories'            => array( 'Flest kategorier' ),
	'Mostrevisions'             => array( 'Flest versioner' ),
	'Fewestrevisions'           => array( 'Minst versioner' ),
	'Shortpages'                => array( 'Korta sidor' ),
	'Longpages'                 => array( 'Långa sidor' ),
	'Newpages'                  => array( 'Nya sidor' ),
	'Ancientpages'              => array( 'Gamla sidor' ),
	'Deadendpages'              => array( 'Sidor utan länkar', 'Sidor utan länkar från' ),
	'Protectedpages'            => array( 'Skyddade sidor' ),
	'Protectedtitles'           => array( 'Skyddade titlar' ),
	'Allpages'                  => array( 'Alla sidor' ),
	'Prefixindex'               => array( 'Prefixindex' ),
	'Ipblocklist'               => array( 'Blockeringslista' ),
	'Specialpages'              => array( 'Specialsidor' ),
	'Contributions'             => array( 'Bidrag' ),
	'Emailuser'                 => array( 'E-mail' ),
	'Confirmemail'              => array( 'Bekräfta epost' ),
	'Whatlinkshere'             => array( 'Länkar hit' ),
	'Recentchangeslinked'       => array( 'Senaste relaterade ändringar' ),
	'Movepage'                  => array( 'Flytta' ),
	'Blockme'                   => array( 'Blockera mig' ),
	'Booksources'               => array( 'Bokkällor' ),
	'Categories'                => array( 'Kategorier' ),
	'Export'                    => array( 'Exportera' ),
	'Allmessages'               => array( 'Systemmeddelanden' ),
	'Log'                       => array( 'Logg' ),
	'Blockip'                   => array( 'Blockera' ),
	'Undelete'                  => array( 'Återställ' ),
	'Import'                    => array( 'Importera' ),
	'Lockdb'                    => array( 'Lås databasen' ),
	'Unlockdb'                  => array( 'Lås upp databasen' ),
	'Userrights'                => array( 'Rättigheter' ),
	'MIMEsearch'                => array( 'MIME-sökning' ),
	'FileDuplicateSearch'       => array( 'Dublettfilsökning' ),
	'Unwatchedpages'            => array( 'Obevakade sidor' ),
	'Listredirects'             => array( 'Omdirigeringar' ),
	'Revisiondelete'            => array( 'Radera version' ),
	'Unusedtemplates'           => array( 'Oanvända mallar' ),
	'Randomredirect'            => array( 'Slumpomdirigering' ),
	'Mypage'                    => array( 'Min sida' ),
	'Mytalk'                    => array( 'Min diskussion' ),
	'Mycontributions'           => array( 'Mina bidrag' ),
	'Listadmins'                => array( 'Administratörer' ),
	'Listbots'                  => array( 'Robotlista' ),
	'Popularpages'              => array( 'Populära sidor' ),
	'Search'                    => array( 'Sök' ),
	'Resetpass'                 => array( 'Återställ lösenord' ),
	'Withoutinterwiki'          => array( 'Utan interwikilänkar' ),
	'MergeHistory'              => array( 'Slå ihop historik' ),
	'Filepath'                  => array( 'Filsökväg' ),
	'Invalidateemail'           => array( 'Ogiltigförklara epost' ),
	'Blankpage'                 => array( 'Tom sida' ),
	'LinkSearch'                => array( 'Länksökning' ),
	'DeletedContributions'      => array( 'Raderade bidrag' ),
	'Tags'                      => array( 'Märken', 'Taggar' ),
);

$magicWords = array(
	'redirect'              => array( '0', '#OMDIRIGERING', '#REDIRECT' ),
	'notoc'                 => array( '0', '__INGENINNEHÅLLSFÖRTECKNING__', '__NOTOC__' ),
	'nogallery'             => array( '0', '__INGETGALLERI__', '__NOGALLERY__' ),
	'forcetoc'              => array( '0', '__ALLTIDINNEHÅLLSFÖRTECKNING__', '__FORCETOC__' ),
	'toc'                   => array( '0', '__INNEHÅLLSFÖRTECKNING__', '__TOC__' ),
	'noeditsection'         => array( '0', '__INTEREDIGERASEKTION__', '__NOEDITSECTION__' ),
	'noheader'              => array( '0', '__INGENRUBRIK__', '__NOHEADER__' ),
	'currentmonth'          => array( '1', 'NUVARANDEMÅNAD', 'NUMÅNAD', 'CURRENTMONTH', 'CURRENTMONTH2' ),
	'currentmonth1'         => array( '1', 'NUVARANDEMÅNAD1', 'CURRENTMONTH1' ),
	'currentmonthname'      => array( '1', 'NUVARANDEMÅNADSNAMN', 'NUMÅNADSNAMN', 'CURRENTMONTHNAME' ),
	'currentmonthabbrev'    => array( '1', 'NUVARANDEMÅNADKORT', 'NUMÅNADKORT', 'CURRENTMONTHABBREV' ),
	'currentday'            => array( '1', 'NUVARANDEDAG', 'NUDAG', 'CURRENTDAY' ),
	'currentday2'           => array( '1', 'NUVARANDEDAG2', 'NUDAG2', 'CURRENTDAY2' ),
	'currentdayname'        => array( '1', 'NUVARANDEDAGSNAMN', 'NUDAGSNAMN', 'CURRENTDAYNAME' ),
	'currentyear'           => array( '1', 'NUVARANDEÅR', 'NUÅR', 'CURRENTYEAR' ),
	'currenttime'           => array( '1', 'NUVARANDETID', 'NUTID', 'CURRENTTIME' ),
	'currenthour'           => array( '1', 'NUVARANDETIMME', 'NUTIMME', 'CURRENTHOUR' ),
	'localmonth'            => array( '1', 'LOKALMÅNAD', 'LOCALMONTH', 'LOCALMONTH2' ),
	'localmonth1'           => array( '1', 'LOKALMÅNAD1', 'LOCALMONTH1' ),
	'localmonthname'        => array( '1', 'LOKALMÅNADSNAMN', 'LOCALMONTHNAME' ),
	'localmonthabbrev'      => array( '1', 'LOKALMÅNADKORT', 'LOCALMONTHABBREV' ),
	'localday'              => array( '1', 'LOKALDAG', 'LOCALDAY' ),
	'localday2'             => array( '1', 'LOKALDAG2', 'LOCALDAY2' ),
	'localdayname'          => array( '1', 'LOKALDAGSNAMN', 'LOCALDAYNAME' ),
	'localyear'             => array( '1', 'LOKALTÅR', 'LOCALYEAR' ),
	'localtime'             => array( '1', 'LOKALTID', 'LOCALTIME' ),
	'localhour'             => array( '1', 'LOKALTIMME', 'LOCALHOUR' ),
	'numberofpages'         => array( '1', 'ANTALSIDOR', 'NUMBEROFPAGES' ),
	'numberofarticles'      => array( '1', 'ANTALARTIKLAR', 'NUMBEROFARTICLES' ),
	'numberoffiles'         => array( '1', 'ANTALFILER', 'NUMBEROFFILES' ),
	'numberofusers'         => array( '1', 'ANTALANVÄNDARE', 'NUMBEROFUSERS' ),
	'numberofactiveusers'   => array( '1', 'ANTALAKTIVAANVÄNDARE', 'NUMBEROFACTIVEUSERS' ),
	'numberofedits'         => array( '1', 'ANTALREDIGERINGAR', 'NUMBEROFEDITS' ),
	'numberofviews'         => array( '1', 'ANTALVISNINGAR', 'NUMBEROFVIEWS' ),
	'pagename'              => array( '1', 'SIDNAMN', 'PAGENAME' ),
	'pagenamee'             => array( '1', 'SIDNAMNE', 'PAGENAMEE' ),
	'namespace'             => array( '1', 'NAMNRYMD', 'NAMESPACE' ),
	'namespacee'            => array( '1', 'NAMNRYMDE', 'NAMESPACEE' ),
	'talkspace'             => array( '1', 'DISKUSSIONSRYMD', 'TALKSPACE' ),
	'talkspacee'            => array( '1', 'DISKUSSIONSRYMDE', 'TALKSPACEE' ),
	'subjectspace'          => array( '1', 'ARTIKELRYMD', 'SUBJECTSPACE', 'ARTICLESPACE' ),
	'subjectspacee'         => array( '1', 'ARTIKELRYMDE', 'SUBJECTSPACEE', 'ARTICLESPACEE' ),
	'fullpagename'          => array( '1', 'HELASIDNAMNET', 'FULLPAGENAME' ),
	'fullpagenamee'         => array( '1', 'HELASIDNAMNETE', 'FULLPAGENAMEE' ),
	'subpagename'           => array( '1', 'UNDERSIDNAMN', 'SUBPAGENAME' ),
	'subpagenamee'          => array( '1', 'UNDERSIDNAMNE', 'SUBPAGENAMEE' ),
	'basepagename'          => array( '1', 'GRUNDSIDNAMN', 'BASEPAGENAME' ),
	'basepagenamee'         => array( '1', 'GRUNDSIDNAMNE', 'BASEPAGENAMEE' ),
	'talkpagename'          => array( '1', 'DISKUSSIONSSIDNAMN', 'TALKPAGENAME' ),
	'talkpagenamee'         => array( '1', 'DISKUSSIONSSIDNAMNE', 'TALKPAGENAMEE' ),
	'msg'                   => array( '0', 'MED:', 'MSG:' ),
	'subst'                 => array( '0', 'BYT:', 'SUBST:' ),
	'msgnw'                 => array( '0', 'MEDNW:', 'MSGNW:' ),
	'img_thumbnail'         => array( '1', 'miniatyr', 'mini', 'thumbnail', 'thumb' ),
	'img_manualthumb'       => array( '1', 'miniatyr=$1', 'mini=$1', 'thumbnail=$1', 'thumb=$1' ),
	'img_right'             => array( '1', 'höger', 'right' ),
	'img_left'              => array( '1', 'vänster', 'left' ),
	'img_none'              => array( '1', 'ingen', 'none' ),
	'img_center'            => array( '1', 'centrerad', 'center', 'centre' ),
	'img_framed'            => array( '1', 'inramad', 'ram', 'framed', 'enframed', 'frame' ),
	'img_frameless'         => array( '1', 'ramlös', 'frameless' ),
	'img_page'              => array( '1', 'sida=$1', 'sida $1', 'page=$1', 'page $1' ),
	'img_upright'           => array( '1', 'stående', 'stående=$1', 'stående $1', 'upright', 'upright=$1', 'upright $1' ),
	'img_border'            => array( '1', 'kantlinje', 'border' ),
	'img_baseline'          => array( '1', 'baslinje', 'baseline' ),
	'img_sub'               => array( '1', 'ned', 'sub' ),
	'img_super'             => array( '1', 'upp', 'super', 'sup' ),
	'img_top'               => array( '1', 'topp', 'top' ),
	'img_text_top'          => array( '1', 'text-topp', 'text-top' ),
	'img_middle'            => array( '1', 'mitten', 'middle' ),
	'img_bottom'            => array( '1', 'botten', 'bottom' ),
	'img_text_bottom'       => array( '1', 'text-botten', 'text-bottom' ),
	'img_link'              => array( '1', 'länk=$1', 'link=$1' ),
	'sitename'              => array( '1', 'SAJTNAMN', 'SITENAMN', 'SITENAME' ),
	'ns'                    => array( '0', 'NR:', 'NS:' ),
	'localurl'              => array( '0', 'LOKALURL:', 'LOCALURL:' ),
	'localurle'             => array( '0', 'LOKALURLE:', 'LOCALURLE:' ),
	'servername'            => array( '0', 'SERVERNAMN', 'SERVERNAME' ),
	'scriptpath'            => array( '0', 'SKRIPTSÖKVÄG', 'SCRIPTPATH' ),
	'grammar'               => array( '0', 'GRAMMATIK:', 'GRAMMAR:' ),
	'gender'                => array( '0', 'KÖN:', 'GENDER:' ),
	'currentweek'           => array( '1', 'NUVARANDEVECKA', 'NUVECKA', 'CURRENTWEEK' ),
	'currentdow'            => array( '1', 'NUVARANDEVECKODAG', 'CURRENTDOW' ),
	'localweek'             => array( '1', 'LOKALVECKA', 'LOCALWEEK' ),
	'localdow'              => array( '1', 'LOKALVECKODAG', 'LOCALDOW' ),
	'revisionid'            => array( '1', 'REVISIONSID', 'REVISIONID' ),
	'revisionday'           => array( '1', 'REVISIONSDAG', 'REVISIONDAY' ),
	'revisionday2'          => array( '1', 'REVISIONSDAG2', 'REVISIONDAY2' ),
	'revisionmonth'         => array( '1', 'REVISIONSMÅNAD', 'REVISIONMONTH' ),
	'revisionyear'          => array( '1', 'REVISIONSÅR', 'REVISIONYEAR' ),
	'revisiontimestamp'     => array( '1', 'REVISIONSTIDSSTÄMPEL', 'REVISIONTIMESTAMP' ),
	'revisionuser'          => array( '1', 'REVISIONSANVÄNDARE', 'REVISIONUSER' ),
	'fullurl'               => array( '0', 'FULLURL:. FULLTURL:', 'FULLURL:' ),
	'fullurle'              => array( '0', 'FULLTURLE:', 'FULLURLE:' ),
	'lcfirst'               => array( '0', 'LBFÖRST:', 'LCFIRST:' ),
	'ucfirst'               => array( '0', 'UCFIRST', 'SBFÖRST:', 'UCFIRST:' ),
	'lc'                    => array( '0', 'LB:', 'LC:' ),
	'uc'                    => array( '0', 'SB:', 'UC:' ),
	'raw'                   => array( '0', 'RÅ:', 'RAW:' ),
	'displaytitle'          => array( '1', 'VISATITEL', 'DISPLAYTITLE' ),
	'newsectionlink'        => array( '1', '__NYTTAVSNITTLÄNK__', '__NEWSECTIONLINK__' ),
	'currentversion'        => array( '1', 'NUVARANDEVERSION', 'NUVERSION', 'CURRENTVERSION' ),
	'currenttimestamp'      => array( '1', 'NUTIDSTÄMPEL', 'CURRENTTIMESTAMP' ),
	'localtimestamp'        => array( '1', 'LOKALTIDSTÄMPEL', 'LOCALTIMESTAMP' ),
	'language'              => array( '0', '#SPRÅK:', '#LANGUAGE:' ),
	'contentlanguage'       => array( '1', 'INNEHÅLLSSPRÅK', 'CONTENTLANGUAGE', 'CONTENTLANG' ),
	'pagesinnamespace'      => array( '1', 'SIDORINAMNRYMD:', 'SIDORINR:', 'PAGESINNAMESPACE:', 'PAGESINNS:' ),
	'numberofadmins'        => array( '1', 'ANTALADMINS', 'ANTALADMINISTRATÖRER', 'NUMBEROFADMINS' ),
	'formatnum'             => array( '0', 'FORMATERANUM', 'FORMATERATAL', 'FORMATNUM' ),
	'defaultsort'           => array( '1', 'STANDARDSORTERING:', 'DEFAULTSORT:', 'DEFAULTSORTKEY:', 'DEFAULTCATEGORYSORT:' ),
	'filepath'              => array( '0', 'FILSÖKVÄG:', 'FILEPATH:' ),
	'tag'                   => array( '0', 'tagg', 'tag' ),
	'hiddencat'             => array( '1', '__DOLDKAT__', '__HIDDENCAT__' ),
	'pagesincategory'       => array( '1', 'SIDORIKATEGORI', 'PAGESINCATEGORY', 'PAGESINCAT' ),
	'pagesize'              => array( '1', 'SIDSTORLEK', 'PAGESIZE' ),
	'index'                 => array( '1', '__INDEXERA__', '__INDEX__' ),
	'noindex'               => array( '1', '__INTEINDEXERA_', '__NOINDEX__' ),
	'numberingroup'         => array( '1', 'ANTALIGRUPP', 'NUMBERINGROUP', 'NUMINGROUP' ),
	'staticredirect'        => array( '1', '__STATISKOMDIRIGERING__', '__STATICREDIRECT__' ),
	'protectionlevel'       => array( '1', 'SKYDDSNIVÅ', 'PROTECTIONLEVEL' ),
	'formatdate'            => array( '0', 'formateradatum', 'datumformat', 'formatdate', 'dateformat' ),
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

$messages = array(
# User preference toggles
'tog-underline'               => 'Stryk under länkar',
'tog-highlightbroken'         => 'Formatera trasiga länkar <a href="" class="new">så här</a> (alternativt: <a href="" class="internal">?</a>).',
'tog-justify'                 => 'Marginaljustera stycken',
'tog-hideminor'               => 'Visa inte mindre redigeringar i Senaste ändringar',
'tog-hidepatrolled'           => 'Dölj patrullerade redigeringar i senaste ändringar',
'tog-newpageshidepatrolled'   => 'Göm patrullerade sidor från listan över nya sidor',
'tog-extendwatchlist'         => 'Utöka bevakningslistan till att visa alla ändringar, inte bara den senaste',
'tog-usenewrc'                => 'Använd avancerad senaste ändringar (kräver JavaScript)',
'tog-numberheadings'          => 'Numrerade rubriker',
'tog-showtoolbar'             => 'Visa verktygsrad (Javascript)',
'tog-editondblclick'          => 'Redigera sidor med dubbelklick (Javascript)',
'tog-editsection'             => 'Aktivera redigering av avsnitt genom [redigera]-länkar',
'tog-editsectiononrightclick' => 'Aktivera redigering av avsnitt genom högerklick på underrubriker (Javascript)',
'tog-showtoc'                 => 'Visa innehållsförteckning (för sidor som har minst fyra rubriker)',
'tog-rememberpassword'        => 'Spara min inloggning på den här datorn (i max $1 {{PLURAL:$1|dag|dagar}})',
'tog-watchcreations'          => 'Lägg till sidor jag skapar i min bevakningslista',
'tog-watchdefault'            => 'Lägg till sidor jag redigerar i min bevakningslista',
'tog-watchmoves'              => 'Lägg till sidor jag flyttar i min bevakningslista',
'tog-watchdeletion'           => 'Lägg till sidor jag raderar i min bevakningslista',
'tog-minordefault'            => 'Markera automatiskt ändringar som mindre',
'tog-previewontop'            => 'Visa förhandsgranskningen ovanför redigeringsrutan',
'tog-previewonfirst'          => 'Visa förhandsgranskning när redigering påbörjas',
'tog-nocache'                 => 'Stäng av cachning av sidor',
'tog-enotifwatchlistpages'    => 'Skicka e-post till mig när en sida på min bevakningslista ändras',
'tog-enotifusertalkpages'     => 'Skicka e-post till mig när något händer på min diskussionssida',
'tog-enotifminoredits'        => 'Skicka mig e-post även för små redigeringar',
'tog-enotifrevealaddr'        => 'Visa min e-postadress i e-postmeddelanden om ändringar som skickas till andra',
'tog-shownumberswatching'     => 'Visa antalet användare som bevakar',
'tog-oldsig'                  => 'Förhandsvisning av nuvarande signatur:',
'tog-fancysig'                => 'Rå signatur som wikitext (utan en automatisk länk)',
'tog-externaleditor'          => 'Använd extern texteditor som standard (avancerat, kräver speciella inställningar i din dator)',
'tog-externaldiff'            => 'Använd externt diff-verktyg (avancerat, kräver speciella inställningar i din dator)',
'tog-showjumplinks'           => 'Aktivera "hoppa till"-tillgänglighetslänkar',
'tog-uselivepreview'          => 'Använd direktuppdaterad förhandsgranskning (Javascript, på försöksstadiet)',
'tog-forceeditsummary'        => 'Påminn mig om jag inte fyller i en redigeringskommentar',
'tog-watchlisthideown'        => 'Visa inte mina redigeringar i bevakningslistan',
'tog-watchlisthidebots'       => 'Visa inte robotredigeringar i bevakningslistan',
'tog-watchlisthideminor'      => 'Visa inte mindre ändringar i bevakningslistan',
'tog-watchlisthideliu'        => 'Visa inte redigeringar av inloggade användare i bevakningslistan',
'tog-watchlisthideanons'      => 'Visa inte redigeringar av anonyma användare i bevakningslistan',
'tog-watchlisthidepatrolled'  => 'Göm patrullerade redigeringar från bevakningslistan',
'tog-nolangconversion'        => 'Konvertera inte mellan språkvarianter',
'tog-ccmeonemails'            => 'Skicka mig kopior av epost jag skickar till andra användare',
'tog-diffonly'                => 'Visa inte sidinnehåll under diffar',
'tog-showhiddencats'          => 'Visa dolda kategorier',
'tog-noconvertlink'           => 'Stäng av konvertering av sidtitlar',
'tog-norollbackdiff'          => 'Visa inte diff efter tillbakarullning',

'underline-always'  => 'Alltid',
'underline-never'   => 'Aldrig',
'underline-default' => 'Webbläsarens standardinställning',

# Font style option in Special:Preferences
'editfont-style'     => 'Typsnitt i redigeringsrutan:',
'editfont-default'   => 'Webbläsarens standard',
'editfont-monospace' => 'Fast bredd',
'editfont-sansserif' => 'Sans-serif',
'editfont-serif'     => 'Serif',

# Dates
'sunday'        => 'söndag',
'monday'        => 'måndag',
'tuesday'       => 'tisdag',
'wednesday'     => 'onsdag',
'thursday'      => 'torsdag',
'friday'        => 'fredag',
'saturday'      => 'lördag',
'sun'           => 'sön',
'mon'           => 'mån',
'tue'           => 'tis',
'wed'           => 'ons',
'thu'           => 'tor',
'fri'           => 'fre',
'sat'           => 'lör',
'january'       => 'januari',
'february'      => 'februari',
'march'         => 'mars',
'april'         => 'april',
'may_long'      => 'maj',
'june'          => 'juni',
'july'          => 'juli',
'august'        => 'augusti',
'september'     => 'september',
'october'       => 'oktober',
'november'      => 'november',
'december'      => 'december',
'january-gen'   => 'januaris',
'february-gen'  => 'februaris',
'march-gen'     => 'mars',
'april-gen'     => 'aprils',
'may-gen'       => 'majs',
'june-gen'      => 'junis',
'july-gen'      => 'julis',
'august-gen'    => 'augustis',
'september-gen' => 'septembers',
'october-gen'   => 'oktobers',
'november-gen'  => 'novembers',
'december-gen'  => 'decembers',
'jan'           => 'jan',
'feb'           => 'feb',
'mar'           => 'mar',
'apr'           => 'apr',
'may'           => 'maj',
'jun'           => 'jun',
'jul'           => 'jul',
'aug'           => 'aug',
'sep'           => 'sep',
'oct'           => 'okt',
'nov'           => 'nov',
'dec'           => 'dec',

# Categories related messages
'pagecategories'                 => '{{PLURAL:$1|Kategori|Kategorier}}',
'category_header'                => 'Sidor i kategorin "$1"',
'subcategories'                  => 'Underkategorier',
'category-media-header'          => 'Media i kategorin "$1"',
'category-empty'                 => "''Den här kategorin innehåller just nu inga sidor eller filer.''",
'hidden-categories'              => '{{PLURAL:$1|Dold kategori|Dolda kategorier}}',
'hidden-category-category'       => 'Dolda kategorier',
'category-subcat-count'          => '{{PLURAL:$2|Denna kategori har endast följande underkategori.|Denna kategori har följande {{PLURAL:$1|underkategori|$1 underkategorier}} (av totalt $2).}}',
'category-subcat-count-limited'  => 'Denna kategori har följande {{PLURAL:$1|underkategori|$1 underkategorier}}.',
'category-article-count'         => '{{PLURAL:$2|Denna kategori innehåller endast följande sida.|Följande {{PLURAL:$1|sida|$1 sidor}} (av totalt $2) finns i denna kategori.}}',
'category-article-count-limited' => 'Följande {{PLURAL:$1|sida|$1 sidor}} finns i den här kategorin.',
'category-file-count'            => '{{PLURAL:$2|Denna kategori innehåller endast följande fil.|Följande {{PLURAL:$1|fil|$1 filer}} (av totalt $2) finns i denna kategori.}}',
'category-file-count-limited'    => 'Följande {{PLURAL:$1|fil |$1 filer}} finns i den här kategorin.',
'listingcontinuesabbrev'         => 'forts.',
'index-category'                 => 'Indexerade sidor',
'noindex-category'               => 'Icke-indexerade sidor',

'mainpagetext'      => "'''MediaWiki har installerats utan problem.'''",
'mainpagedocfooter' => 'Information om hur wiki-programvaran används finns i [http://meta.wikimedia.org/wiki/Help:Contents användarguiden].

== Att komma igång ==

* [http://www.mediawiki.org/wiki/Manual:Configuration_settings Lista över konfigurationsinställningar]
* [http://www.mediawiki.org/wiki/Manual:FAQ MediaWiki FAQ]
* [https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce MediaWiki release mail list]',

'about'         => 'Om',
'article'       => 'Innehållssida',
'newwindow'     => '(öppnas i ett nytt fönster)',
'cancel'        => 'Avbryt',
'moredotdotdot' => 'Mer...',
'mypage'        => 'Min sida',
'mytalk'        => 'Min diskussion',
'anontalk'      => 'Diskussionssida för denna IP-adress',
'navigation'    => 'Navigering',
'and'           => '&#32;och',

# Cologne Blue skin
'qbfind'         => 'Hitta',
'qbbrowse'       => 'Bläddra igenom',
'qbedit'         => 'Redigera',
'qbpageoptions'  => 'Denna sida',
'qbpageinfo'     => 'Sidinformation',
'qbmyoptions'    => 'Mina inställningar',
'qbspecialpages' => 'Specialsidor',
'faq'            => 'FAQ',
'faqpage'        => 'Project:FAQ',

# Vector skin
'vector-action-addsection'   => 'Nytt ämne',
'vector-action-delete'       => 'Radera',
'vector-action-move'         => 'Flytta',
'vector-action-protect'      => 'Skrivskydda',
'vector-action-undelete'     => 'Återställ',
'vector-action-unprotect'    => 'Ta bort skrivskydd',
'vector-namespace-category'  => 'Kategori',
'vector-namespace-help'      => 'Hjälpsida',
'vector-namespace-image'     => 'Fil',
'vector-namespace-main'      => 'Sida',
'vector-namespace-media'     => 'Mediasida',
'vector-namespace-mediawiki' => 'Systemmeddelande',
'vector-namespace-project'   => 'Projektsida',
'vector-namespace-special'   => 'Specialsida',
'vector-namespace-talk'      => 'Diskussion',
'vector-namespace-template'  => 'Mall',
'vector-namespace-user'      => 'Användarsida',
'vector-view-create'         => 'Skapa',
'vector-view-edit'           => 'Redigera',
'vector-view-history'        => 'Visa historik',
'vector-view-view'           => 'Visa',
'vector-view-viewsource'     => 'Visa källa',
'actions'                    => 'Åtgärder',
'namespaces'                 => 'Namnrymder',
'variants'                   => 'Varianter',

'errorpagetitle'    => 'Fel',
'returnto'          => 'Tillbaka till $1.',
'tagline'           => 'Från {{SITENAME}}',
'help'              => 'Hjälp',
'search'            => 'Sök',
'searchbutton'      => 'Sök',
'go'                => 'Gå till',
'searcharticle'     => 'Gå till',
'history'           => 'Versionshistorik',
'history_short'     => 'Historik',
'updatedmarker'     => 'uppdaterad sedan senaste besöket',
'info_short'        => 'Information',
'printableversion'  => 'Utskriftsvänlig version',
'permalink'         => 'Permanent länk',
'print'             => 'Skriv ut',
'edit'              => 'Redigera',
'create'            => 'Skapa',
'editthispage'      => 'Redigera denna sida',
'create-this-page'  => 'Skapa denna sida',
'delete'            => 'radera',
'deletethispage'    => 'Radera denna sida',
'undelete_short'    => 'Återställ {{PLURAL:$1|en version|$1 versioner}}',
'protect'           => 'Skrivskydda',
'protect_change'    => 'ändra',
'protectthispage'   => 'Skrivskydda denna sida',
'unprotect'         => 'Ta bort skrivskydd',
'unprotectthispage' => 'Ta bort skrivskyddet från den här sidan',
'newpage'           => 'Ny sida',
'talkpage'          => 'Diskutera denna sida',
'talkpagelinktext'  => 'Diskussion',
'specialpage'       => 'Specialsida',
'personaltools'     => 'Personliga verktyg',
'postcomment'       => 'Nytt avsnitt',
'articlepage'       => 'Visa innehållssida',
'talk'              => 'Diskussion',
'views'             => 'Visningar',
'toolbox'           => 'Verktygslåda',
'userpage'          => 'Visa användarsida',
'projectpage'       => 'Visa projektsida',
'imagepage'         => 'Visa filsida',
'mediawikipage'     => 'Visa meddelandesida',
'templatepage'      => 'Visa mallsida',
'viewhelppage'      => 'Visa hjälpsida',
'categorypage'      => 'Visa kategorisida',
'viewtalkpage'      => 'Visa diskussionssida',
'otherlanguages'    => 'På andra språk',
'redirectedfrom'    => '(Omdirigerad från $1)',
'redirectpagesub'   => 'Omdirigeringssida',
'lastmodifiedat'    => 'Sidan ändrades senast den $1 kl. $2.',
'viewcount'         => 'Den här sidan har visats {{PLURAL:$1|en gång|$1 gånger}}.',
'protectedpage'     => 'Skrivskyddad sida',
'jumpto'            => 'Hoppa till:',
'jumptonavigation'  => 'navigering',
'jumptosearch'      => 'sök',
'view-pool-error'   => 'Tyvärr är servrarna överbelastade för tillfället.
För många användare försöker visa denna sida.
Vänta en liten stund och försök igen lite senare.

$1',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'Om {{SITENAME}}',
'aboutpage'            => 'Project:Om',
'copyright'            => 'Innehållet är tillgängligt under $1.',
'copyrightpage'        => '{{ns:project}}:Upphovsrätt',
'currentevents'        => 'Aktuella händelser',
'currentevents-url'    => 'Project:Aktuella händelser',
'disclaimers'          => 'Förbehåll',
'disclaimerpage'       => 'Project:Allmänt förbehåll',
'edithelp'             => 'Redigeringshjälp',
'edithelppage'         => 'Help:Redigering',
'helppage'             => 'Help:Innehåll',
'mainpage'             => 'Huvudsida',
'mainpage-description' => 'Huvudsida',
'policy-url'           => 'Project:Riktlinjer',
'portal'               => 'Deltagarportalen',
'portal-url'           => 'Project:Deltagarportalen',
'privacy'              => 'Integritetspolicy',
'privacypage'          => 'Project:Integritetspolicy',

'badaccess'        => 'Behörighetsfel',
'badaccess-group0' => 'Du har inte behörighet att utföra den handling du begärt.',
'badaccess-groups' => 'Den handling du har begärt kan enbart utföras av användare i {{PLURAL:$2|gruppen|grupperna}} $1.',

'versionrequired'     => 'Version $1 av MediaWiki krävs',
'versionrequiredtext' => 'Version $1 av MediaWiki är nödvändig för att använda denna sida. Se [[Special:Version|versionssidan]].',

'ok'                      => 'OK',
'retrievedfrom'           => 'Hämtad från "$1"',
'youhavenewmessages'      => 'Du har $1 ($2).',
'newmessageslink'         => 'nya meddelanden',
'newmessagesdifflink'     => 'senaste ändringen',
'youhavenewmessagesmulti' => 'Du har nya meddelanden på $1',
'editsection'             => 'redigera',
'editold'                 => 'redigera',
'viewsourceold'           => 'visa wikitext',
'editlink'                => 'redigera',
'viewsourcelink'          => 'visa wikitext',
'editsectionhint'         => 'Redigera avsnitt: $1',
'toc'                     => 'Innehåll',
'showtoc'                 => 'visa',
'hidetoc'                 => 'göm',
'thisisdeleted'           => 'Visa eller återställ $1?',
'viewdeleted'             => 'Visa $1?',
'restorelink'             => '{{PLURAL:$1|en raderad version|$1 raderade versioner}}',
'feedlinks'               => 'Matning:',
'feed-invalid'            => 'Ogiltig matningstyp.',
'feed-unavailable'        => 'Webbmatningar (feeds) är inte tillgängliga',
'site-rss-feed'           => '$1 RSS-matning',
'site-atom-feed'          => '$1 Atom-matning',
'page-rss-feed'           => '"$1" RSS-matning',
'page-atom-feed'          => '"$1" Atom-matning',
'red-link-title'          => '$1 (sidan existerar inte)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Sida',
'nstab-user'      => 'Användarsida',
'nstab-media'     => 'Media',
'nstab-special'   => 'Specialsida',
'nstab-project'   => 'Projektsida',
'nstab-image'     => 'Fil',
'nstab-mediawiki' => 'Systemmeddelande',
'nstab-template'  => 'Mall',
'nstab-help'      => 'Hjälpsida',
'nstab-category'  => 'Kategori',

# Main script and global functions
'nosuchaction'      => 'Funktionen finns inte',
'nosuchactiontext'  => 'Den handling som specificerats av URL:en är ogiltig.
Du kan ha stavat URL:en fel, eller följt en felaktig länk.
Det kan också bero på en bug i {{SITENAME}}.',
'nosuchspecialpage' => 'Någon sådan specialsida finns inte',
'nospecialpagetext' => '<strong>Du har begärt en specialsida som inte finns.</strong>

I [[Special:SpecialPages|listan över specialsidor]] kan du se vilka specialsidor som finns.',

# General errors
'error'                => 'Fel',
'databaseerror'        => 'Databasfel',
'dberrortext'          => 'Ett syntaxfel i databasfrågan har uppstått.
Detta kan indikera en bug i mjukvaran.
Den senaste databasfrågan att köras var:
<blockquote><tt>$1</tt></blockquote>
från funktionen "<tt>$2</tt>".
Databasen returnerade felet "<tt>$3: $4</tt>".',
'dberrortextcl'        => 'Det har uppstått ett syntaxfel i databassökningen.
Senaste sökbegrepp var:
"$1"
från funktionen "$2".
Databasen svarade med felmeddelandet "$3: $4"',
'laggedslavemode'      => 'Varning: Sidan kan sakna de senaste uppdateringarna.',
'readonly'             => 'Databasen är skrivskyddad',
'enterlockreason'      => 'Ange varför sidan skrivskyddats, och ge en uppskattning av hur länge skrivskyddet bör behållas.',
'readonlytext'         => 'Databasen är tillfälligt låst för nya inlägg och andra modifieringar, förmodligen på grund av rutinmässigt underhåll, efter vilket den kommer den att återgå till normalläge.

Den administratör som låste den har angivit följande förklaring: $1',
'missing-article'      => 'Databasen hittade inte texten för en sida som den borde ha funnit, med namnet "$1" $2.

Detta orsakas oftast av att man följer en inaktuell länk till en jämförelse mellan versioner (diff) eller en historiklänk för en sida som raderats.

Om inte så är fallet, kan du ha hittat en bugg i mjukvaran.
Rapportera gärna problemet till någon [[Special:ListUsers/sysop|administratör]], ange då URL:en (webbadressen).',
'missingarticle-rev'   => '(version $1)',
'missingarticle-diff'  => '(jämförelse mellan version $1 och $2)',
'readonly_lag'         => 'Databasen har automatiskt skrivskyddats medan slavdatabasservrarna synkroniseras med huvudservern.',
'internalerror'        => 'Internt fel',
'internalerror_info'   => 'Internt fel: $1',
'fileappenderrorread'  => 'Kunde inte läsa "$1" medan data skulle tillföras.',
'fileappenderror'      => 'Kunde inte bifoga "$1" till "$2".',
'filecopyerror'        => 'Kunde inte kopiera filen "$1" till "$2".',
'filerenameerror'      => 'Kunde inte byta namn på filen "$1" till "$2".',
'filedeleteerror'      => 'Kunde inte radera filen "$1".',
'directorycreateerror' => 'Kunde inte skapa katalogen "$1".',
'filenotfound'         => 'Kunde inte hitta filen "$1".',
'fileexistserror'      => 'Kan inte skriva till "$1": filen finns redan',
'unexpected'           => 'Oväntat värde: "$1"="$2".',
'formerror'            => 'Fel: Kunde inte sända formulär',
'badarticleerror'      => 'Den åtgärden kan inte utföras på den här sidan.',
'cannotdelete'         => 'Sidan eller filen "$1" kunde inte raderas.
Den kanske redan har raderats av någon annan.',
'badtitle'             => 'Felaktig titel',
'badtitletext'         => 'Den begärda sidtiteln är antingen ogiltig eller tom, eller så är titeln felaktigt länkad från en annan wiki.
Den kan innehålla ett eller flera tecken som inte får användas i sidtitlar.',
'perfcached'           => 'Följande data är cachad och är möjligtvis inte helt uppdaterad.',
'perfcachedts'         => 'Sidan är hämtad ur ett cacheminne och uppdaterades senast $1.',
'querypage-no-updates' => 'Uppdatering av den här sidan är inte aktiverad. Datan kommer i nuläget inte att uppdateras.',
'wrong_wfQuery_params' => 'Felaktiga parametrar för wfQuery()<br /> Funktion: $1<br /> Förfrågan: $2',
'viewsource'           => 'Visa wikitext',
'viewsourcefor'        => 'för $1',
'actionthrottled'      => 'Åtgärden stoppades',
'actionthrottledtext'  => 'Som skydd mot spam, finns det en begränsning av hur många gånger du kan utföra den här åtgärden under en viss tid. Du har överskridit den gränsen. Försök igen om några minuter.',
'protectedpagetext'    => 'Den här sidan har skrivskyddats för att förhindra redigering.',
'viewsourcetext'       => 'Du kan se och kopiera denna sidas källtext:',
'protectedinterface'   => 'Denna sida innehåller text för mjukvarans gränssnitt, och är skrivskyddad för att förebygga missbruk.',
'editinginterface'     => "'''Varning:''' Du redigerar en sida som används till texten i gränssnittet. Ändringar på denna sida kommer att påverka gränssnittets utseende för alla användare.
För översättningar, använd gärna [http://translatewiki.net/wiki/Main_Page?setlang=sv translatewiki.net], översättningsprojektet för MediaWiki.",
'sqlhidden'            => '(gömd SQL-förfrågan)',
'cascadeprotected'     => 'Den här sidan har skyddats från redigering eftersom den inkluderas på följande {{PLURAL:$1|sida|sidor}} som skrivskyddats med "kaskaderande skydd":
$2',
'namespaceprotected'   => "Du har inte behörighet att redigera sidor i namnrymden '''$1'''.",
'customcssjsprotected' => 'Du har inte behörighet att redigera den här sidan eftersom den innehåller en annan användares personliga inställningar.',
'ns-specialprotected'  => 'Specialsidor kan inte redigeras.',
'titleprotected'       => 'Denna sidtitel har skyddats från att skapas av [[User:$1|$1]].
Den uppgivna anledningen är "\'\'$2\'\'".',

# Virus scanner
'virus-badscanner'     => "Dålig konfigurering: okänd virusskanner: ''$1''",
'virus-scanfailed'     => 'skanning misslyckades (kod $1)',
'virus-unknownscanner' => 'okänt antivirusprogram:',

# Login and logout pages
'logouttext'                 => "'''Du är nu utloggad.'''

Du kan fortsätta att använda {{SITENAME}} anonymt, eller så kan du [[Special:UserLogin|logga in igen]] som samma eller som en annan användare.
Observera att det, tills du tömmer din webbläsares cache, på vissa sidor kan se ut som att du fortfarande är inloggad.",
'welcomecreation'            => '== Välkommen, $1! ==
Ditt konto har skapats.
Glöm inte att justera dina [[Special:Preferences|{{SITENAME}}-inställningar]].',
'yourname'                   => 'Användarnamn:',
'yourpassword'               => 'Lösenord:',
'yourpasswordagain'          => 'Upprepa lösenord',
'remembermypassword'         => 'Spara min inloggning på den här datorn (i max $1 {{PLURAL:$1|dag|dagar}})',
'yourdomainname'             => 'Din domän',
'externaldberror'            => 'Antingen inträffade autentiseringsproblem med en extern databas, eller så får du inte uppdatera ditt externa konto.',
'login'                      => 'Logga in',
'nav-login-createaccount'    => 'Logga in / skapa konto',
'loginprompt'                => 'Du måste tillåta cookies för att logga in på {{SITENAME}}.',
'userlogin'                  => 'Logga in / skapa konto',
'userloginnocreate'          => 'Logga in',
'logout'                     => 'Logga ut',
'userlogout'                 => 'Logga ut',
'notloggedin'                => 'Inte inloggad',
'nologin'                    => "Har du inget användarkonto? '''$1'''.",
'nologinlink'                => 'Skapa ett användarkonto',
'createaccount'              => 'Skapa ett konto',
'gotaccount'                 => "Har du redan ett användarkonto? '''$1'''.",
'gotaccountlink'             => 'Logga in',
'createaccountmail'          => 'med e-post',
'badretype'                  => 'De lösenord du uppgett överenstämmer inte med varandra.',
'userexists'                 => 'Det valda användarnamnet används redan.
Välj ett annat namn.',
'loginerror'                 => 'Inloggningsproblem',
'createaccounterror'         => 'Kunde inte skapa konto: $1',
'nocookiesnew'               => 'Användarkontot skapades, men du är inte inloggad.
{{SITENAME}} använder cookies för att logga in användare.
Du har cookies avaktiverade.
Aktivera dem, och logga sen in med ditt nya användarnamn och lösenord.',
'nocookieslogin'             => '{{SITENAME}} använder cookies för att logga in användare. Du har stängt av cookies i din webbläsare. Försök igen med stöd för cookies aktiverat.',
'noname'                     => 'Du har angett ett ogiltigt användarnamn.',
'loginsuccesstitle'          => 'Inloggningen lyckades',
'loginsuccess'               => "'''Du är nu inloggad på {{SITENAME}} som \"\$1\".'''",
'nosuchuser'                 => 'Det finns ingen användare med namnet "$1".
Användarnamn är skiftlägeskänsliga.
Kontrollera din stavning, eller [[Special:UserLogin/signup|skapa ett nytt konto]].',
'nosuchusershort'            => 'Det finns ingen användare som heter "<nowiki>$1</nowiki>". Kontrollera att du stavat rätt.',
'nouserspecified'            => 'Du måste ange ett användarnamn.',
'login-userblocked'          => 'Denna användare är blockerad. Login inte tillåtet.',
'wrongpassword'              => 'Lösenordet du angav är felaktigt. Försök igen',
'wrongpasswordempty'         => 'Lösenordet som angavs var blankt. Var god försök igen.',
'passwordtooshort'           => 'Lösenord måste innehålla minst {{PLURAL:$1|$1 tecken}}.',
'password-name-match'        => 'Ditt lösenord måste vara olikt ditt användarnamn.',
'mailmypassword'             => 'Skicka nytt lösenord',
'passwordremindertitle'      => 'Nytt temporärt lösenord från {{SITENAME}}',
'passwordremindertext'       => 'Någon (förmodligen du, från IP-adressen $1) har begärt ett nytt lösenord till {{SITENAME}} ($4). Ett tillfälligt lösenordet för användaren "$2" har skapats och det blev "$3". Om detta var vad du önskade, så behöver du nu logga in och välja ett nytt lösenord. Ditt tillfälliga lösenord går ut om {{PLURAL:$5|en dag|$5 dagar}}.

Om denna begäran gjordes av någon annan, eller om du har kommit på ditt lösenord,
och inte längre önskar ändra det, så kan du ignorera detta meddelande och 
fortsätta använda ditt gamla lösenord.',
'noemail'                    => 'Användaren "$1" har inte registrerat någon e-postadress.',
'noemailcreate'              => 'Du måste ange en giltig e-postadress',
'passwordsent'               => 'Ett nytt lösenord har skickats till den e-postadress som användaren "$1" har registrerat. När du får meddelandet, var god logga in igen.',
'blocked-mailpassword'       => 'Din IP-adress är blockerad, därför kan den inte användas för att få ett nytt lösenord.',
'eauthentsent'               => 'Ett e-brev för bekräftelse har skickats till den e-postadress som angivits.
Innan någon annan e-post kan skickas härifrån till kontot, måste du följa instruktionerna i e-brevet för att bekräfta att kontot verkligen är ditt.',
'throttled-mailpassword'     => 'Ett nytt lösenord har redan skickats för mindre än {{PLURAL:$1|en timme|$1 timmar}} sedan.
För att förhindra missbruk skickas bara ett nytt lösenord per {{PLURAL:$1|timme|$1-timmarsperiod}}.',
'mailerror'                  => 'Fel vid skickande av e-post: $1',
'acct_creation_throttle_hit' => 'Besökare till den här wikin som har använt din IP-adress har skapat {{PLURAL:$1|1 användarkonto|$1 användarkonton}} under den senaste dagen, vilket är det maximalt tillåtna inom denna tidsperioden.
Som ett resultat kan inte besökare som använder den här IP-adressen skapa några fler användarkonton just nu.',
'emailauthenticated'         => 'Din e-postadress bekräftades den $2 kl. $3.',
'emailnotauthenticated'      => 'Din e-postadress är ännu inte bekräftad. Ingen e-post kommer att skickas vad gäller det följande:',
'noemailprefs'               => 'Uppge en e-postadress i dina inställningar för att få dessa funktioner att fungera.',
'emailconfirmlink'           => 'Bekräfta din e-postadress',
'invalidemailaddress'        => 'E-postadressen kan inte godtas då formatet verkar vara felaktigt.
Skriv in en adress med korrekt format eller töm fältet.',
'accountcreated'             => 'Användarkontot har skapats',
'accountcreatedtext'         => 'Användarkontot $1 har skapats.',
'createaccount-title'        => 'Konto skapat på {{SITENAME}}',
'createaccount-text'         => 'Någon har skapat ett konto åt din e-postadress på {{SITENAME}} ($4) med namnet "$2" och lösenordet "$3". Du bör nu logga in och ändra ditt lösenord.

Du kan ignorera detta meddelande om kontot skapats av misstag.',
'usernamehasherror'          => 'Användarnamnet får inte innehålla #.',
'login-throttled'            => 'Du har gjort för många misslyckade inloggningsförsök till det här kontot.
Vänta innan du försöker igen.',
'loginlanguagelabel'         => 'Språk: $1',
'suspicious-userlogout'      => 'Din begäran om att logga ut nekades eftersom det ser ut som det skickades av en trasig webbläsare eller cachande proxy.',

# Password reset dialog
'resetpass'                 => 'Ändra lösenord',
'resetpass_announce'        => 'Du loggade in med ett temporärt lösenord. För att slutföra inloggningen måste du välja ett nytt lösenord.',
'resetpass_text'            => '<!-- Lägg till text här -->',
'resetpass_header'          => 'Ändra lösenord för konto',
'oldpassword'               => 'Gammalt lösenord:',
'newpassword'               => 'Nytt lösenord:',
'retypenew'                 => 'Upprepa det nya lösenordet:',
'resetpass_submit'          => 'Spara lösenord och logga in',
'resetpass_success'         => 'Ditt lösenord har ändrats. Nu loggas du in...',
'resetpass_forbidden'       => 'Lösenord kan inte ändras',
'resetpass-no-info'         => 'Du måste vara inloggad för att komma åt den här sidan direkt.',
'resetpass-submit-loggedin' => 'Ändra lösenord',
'resetpass-submit-cancel'   => 'Avbryt',
'resetpass-wrong-oldpass'   => 'Ogiltigt tillfälligt eller nuvarande lösenord.
Du kanske redan har lyckats ändra ditt lösenord eller begärt ett nytt tillfälligt lösenord.',
'resetpass-temp-password'   => 'Tillfälligt lösenord:',

# Edit page toolbar
'bold_sample'     => 'Fet text',
'bold_tip'        => 'Fet stil',
'italic_sample'   => 'Kursiv text',
'italic_tip'      => 'Kursiv stil',
'link_sample'     => 'länkens namn',
'link_tip'        => 'Intern länk',
'extlink_sample'  => 'http://www.example.com länkens namn',
'extlink_tip'     => 'Extern länk (kom ihåg prefixet http://)',
'headline_sample' => 'Rubriktext',
'headline_tip'    => 'Rubrik i nivå 2',
'math_sample'     => 'Skriv formeln här',
'math_tip'        => 'Matematisk formel (LaTeX)',
'nowiki_sample'   => 'Skriv in oformaterad text här',
'nowiki_tip'      => 'Ignorera wikiformatering',
'image_sample'    => 'Exempel.jpg',
'image_tip'       => 'Inbäddad fil',
'media_sample'    => 'Exempel.ogg',
'media_tip'       => 'Länk till fil',
'sig_tip'         => 'Din signatur med tidsstämpel',
'hr_tip'          => 'Horisontell linje (använd sparsamt)',

# Edit pages
'summary'                          => 'Sammanfattning:',
'subject'                          => 'Ämne/rubrik:',
'minoredit'                        => 'Mindre ändring (m)',
'watchthis'                        => 'Bevaka denna sida',
'savearticle'                      => 'Spara',
'preview'                          => 'Förhandsgranska',
'showpreview'                      => 'Visa förhandsgranskning',
'showlivepreview'                  => 'Automatiskt uppdaterad förhandsvisning',
'showdiff'                         => 'Visa ändringar',
'anoneditwarning'                  => "'''Varning:''' Du är inte inloggad.
Din IP-adress kommer att sparas i historiken för den här sidan.",
'anonpreviewwarning'               => "''Du är inte inloggad. Vid spara kommer att din IP-adress registreras på denna sidas redigeringshistorik.''",
'missingsummary'                   => "'''Påminnelse:''' Du har inte skrivit någon redigeringskommentar. 
Om du klickar på Spara igen, kommer din redigering att sparas utan en sådan.",
'missingcommenttext'               => 'Var god och skriv in en kommentar nedan.',
'missingcommentheader'             => "'''OBS:''' Du har inte skrivit någon rubrik till den här kommentaren. Om du trycker på \"Spara\" igen, så sparas kommentaren utan någon rubrik.",
'summary-preview'                  => 'Förhandsgranskning av sammanfattning:',
'subject-preview'                  => 'Rubrikförhandsgranskning:',
'blockedtitle'                     => 'Användaren är blockerad',
'blockedtext'                      => "'''Din IP-adress eller ditt användarnamn är blockerat.'''

Blockeringen utfördes av $1 med motiveringen: ''$2''.

* Blockeringen startade $8
* Blockeringen gäller till $6.
* Blockeringen var avsedd för $7.

Du kan kontakta $1 eller någon annan av [[{{MediaWiki:Grouppage-sysop}}|administratörerna]] för att diskutera blockeringen.
Om du är inloggad och har uppgivit en e-postadress i dina [[Special:Preferences|inställningar]] så kan du använda funktionen 'skicka e-post till den här användaren', såvida du inte blivit blockerad från funktionen.

Din IP-adress är $3 och blockerings-ID är #$5.
Vänligen ange informationen ovan i alla förfrågningar som du gör i ärendet.",
'autoblockedtext'                  => 'Din IP-adress har blockerats automatiskt eftersom den har använts av en annan användare som blockerats av $1.
Motiveringen av blockeringen var:

:\'\'$2\'\'

* Blockeringen startade $8
* Blockeringen gäller till $6
* Blockeringen är avsedd för $7

Du kan kontakta $1 eller någon annan [[{{MediaWiki:Grouppage-sysop}}|administratör]] för att diskutera blockeringen.

Observera att du inte kan använda dig av funktionen "skicka e-post till användare" om du inte har registrerat en giltig e-postadress i [[Special:Preferences|dina inställningar]] eller om du har blivit blockerad från att skicka e-post.

Din nuvarande IP-adress är $3, och blockerings-ID är #$5.
Vänligen ange informationen ovan i alla förfrågningar som du gör i ärendet.',
'blockednoreason'                  => 'ingen motivering angavs',
'blockedoriginalsource'            => "Källkoden för '''$1''' visas nedan:",
'blockededitsource'                => "Texten för '''dina ändringar''' av '''$1''' visas nedanför:",
'whitelistedittitle'               => 'Du måste logga in för att redigera',
'whitelistedittext'                => 'Du måste $1 för att kunna redigera sidor.',
'confirmedittext'                  => 'Du måste bekräfta din e-postadress innan du kan redigera sidor. Var vänlig ställ in och validera din e-postadress genom dina [[Special:Preferences|användarinställningar]].',
'nosuchsectiontitle'               => 'Kan inte hitta avsnitt',
'nosuchsectiontext'                => 'Du försökte redigera ett avsnitt som inte finns.
Det kan ha flyttats eller raderats medan du tittade på sidan.',
'loginreqtitle'                    => 'Inloggning krävs',
'loginreqlink'                     => 'logga in',
'loginreqpagetext'                 => 'Du måste $1 för att visa andra sidor.',
'accmailtitle'                     => 'Lösenord skickat.',
'accmailtext'                      => "Ett slumpgenererat lösenord för [[User talk:$1|$1]] har skickats till $2.

Lösenordet för det här nya användarkontot kan ändras på sidan ''[[Special:ChangePassword|ändra lösenord]]'' när du loggar in.",
'newarticle'                       => '(Ny)',
'newarticletext'                   => 'Du har klickat på en länk till en sida som inte finns ännu. Du kan själv skapa sidan genom att skriva i fältet nedan (du kan läsa mer på [[{{MediaWiki:Helppage}}|hjälpsidan]]). Om du inte vill skriva något kan du bara trycka på "tillbaka" i din webbläsare.',
'anontalkpagetext'                 => "----''Detta är diskussionssidan för en anonym användare som inte ännu skapat ett konto, eller som inte använder det.
Därför måste vi använda den numeriska IP-adressen för att identifiera honom/henne.
En sådan IP-adress kan delas av flera användare.
Om du är en anonym användare och känner att irrelevanta kommentarer har riktats mot dig, vänligen [[Special:UserLogin/signup|skapa ett konto]] eller [[Special:UserLogin|logga in]] för att undvika framtida förväxlingar med andra anonyma användare.''",
'noarticletext'                    => 'Det finns just nu ingen text på denna sida.
Du kan [[Special:Search/{{PAGENAME}}|söka efter denna sidtitel]] på andra sidor, <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} söka i loggarna], eller [{{fullurl:{{FULLPAGENAME}}|action=edit}} redigera denna sida]</span>.',
'noarticletext-nopermission'       => 'Det finns för tillfället ingen text på denna sida.
Du kan [[Special:Search/{{PAGENAME}}|söka efter denna sidas titel]] i andra sidor,
eller <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} söka i relevanta loggar]</span>.',
'userpage-userdoesnotexist'        => '"$1" är inte ett registrerat användarkonto. Tänk efter om du vill skapa/redigera den här sidan.',
'userpage-userdoesnotexist-view'   => 'Kontot "$1" är inte registrerat.',
'blocked-notice-logextract'        => 'Användaren är blockerad.
Orsaken till senaste blockeringen kan ses nedan:',
'clearyourcache'                   => "'''Observera: Sedan du sparat sidan kan du behöva tömma din webbläsares cache för att se ändringarna.''' '''Mozilla/Firefox/Safari:''' håll ner ''Skift'' och klicka på ''Reload'' eller tryck antingen ''Ctrl-F5'' eller ''Ctrl-R'' (''Command-R'' på Macintosh); '''Konqueror:''': klicka ''Reload'' eller tryck ''F5;'' '''Opera:''' rensa cachen i ''Tools → Preferences;'' '''Internet Explorer:'''  håll ner ''Ctrl'' och klicka på ''Refresh'' eller tryck ''Ctrl-F5.''",
'usercssyoucanpreview'             => "'''Tips:''' Använd \"{{int:showpreview}}\"-knappen för att testa din nya css innan du sparar.",
'userjsyoucanpreview'              => "'''Tips:''' Använd \"{{int:showpreview}}\"-knappen för att testa din nya JavaScript innan du sparar.",
'usercsspreview'                   => "'''Kom ihåg att du bara förhandsgranskar din användar-CSS.
Den har inte sparats än!'''",
'userjspreview'                    => "'''Kom ihåg att du bara testar/förhandsgranskar ditt JavaScript, det har inte sparats än!'''",
'userinvalidcssjstitle'            => "'''Varning:''' Skalet \"\$1\" finns inte. Kom ihåg att .css- och .js-sidor för enskilda användare börjar på liten bokstav. Exempel: {{ns:user}}:Foo/monobook.css i stället för {{ns:user}}:Foo/Monobook.css.",
'updated'                          => '(Uppdaterad)',
'note'                             => "'''Obs!'''",
'previewnote'                      => "'''Detta är bara en förhandsvisning;
ändringar har ännu inte sparats!'''",
'previewconflict'                  => 'Den här förhandsvisningen är resultatet av den
redigerbara texten ovanför,
så som det kommer att se ut om du väljer att spara.',
'session_fail_preview'             => "'''Vi kunde inte behandla din redigering eftersom sessionsdata gått förlorad.
Var god försök igen.
Om det fortfarande inte fungerar, pröva att [[Special:UserLogout|logga ut]] och logga in igen.'''",
'session_fail_preview_html'        => "'''Vi kunde inte behandla din redigering eftersom sessionsdata gått förlorad.'''

''Eftersom {{SITENAME}} har aktiverat rå HTML, så döljs förhandsvisningen som en förebyggande säkerhetsåtgärd mot JavaScript-attacker.''

'''Om detta är ett försök att göra en rättmätig redigering, så försök igen.
Om det fortfarande inte fungerar, pröva att [[Special:UserLogout|logga ut]] och logga in igen.'''",
'token_suffix_mismatch'            => "'''Din redigering har stoppats eftersom din klient har ändrat tecknen
i redigeringens \"edit token\". Redigeringen stoppades för att förhindra att sidtexten skadas.
Detta händer ibland om du använder buggiga webbaserade anonyma proxytjänster.'''",
'editing'                          => 'Redigerar $1',
'editingsection'                   => 'Redigerar $1 (avsnitt)',
'editingcomment'                   => 'Redigerar $1 (nytt avsnitt)',
'editconflict'                     => 'Redigeringskonflikt: $1',
'explainconflict'                  => "Någon annan har ändrat den här sidan efter att du började att redigera den.
Den översta textrutan innehåller den nuvarande sparade versionen av texten.
Din ändrade version visas i den nedre rutan.
Om du vill spara dina ändringar så måste du infoga dem i den övre texten.
'''Endast''' texten i den översta textrutan kommer att sparas när du trycker på \"Spara\".",
'yourtext'                         => 'Din text',
'storedversion'                    => 'Den sparade versionen',
'nonunicodebrowser'                => "'''VARNING: Din webbläsare saknar stöd för unicode. För att du ska kunna redigera sidor utan problem, så visas icke-ASCII-tecken som hexadecimala koder i redigeringsrutan.'''",
'editingold'                       => "'''VARNING: Du redigerar en gammal version av denna sida. Om du sparar den kommer alla ändringar som har gjorts sedan denna version att skrivas över.'''",
'yourdiff'                         => 'Skillnader',
'copyrightwarning'                 => "Observera att alla bidrag till {{SITENAME}} är att betrakta som utgivna under $2 (se $1 för detaljer). Om du inte vill att din text ska redigeras eller kopieras efter andras gottfinnande skall du inte skriva något här.<br />
Du lovar oss också att du skrev texten själv, eller kopierade från kulturellt allmängods som inte skyddas av upphovsrätt, eller liknande källor. '''LÄGG INTE UT UPPHOVSRÄTTSSKYDDAT MATERIAL HÄR UTAN TILLÅTELSE!'''",
'copyrightwarning2'                => "Observera att alla bidrag till {{SITENAME}} kan komma att redigeras, ändras, eller tas bort av andra deltagare. Om du inte vill se din text förändrad efter andras gottfinnade skall du inte skriva in någon text här.<br />
Du lovar oss också att du skrev texten själv, eller kopierade från kulturellt allmängods som inte skyddas av upphovsrätt, eller liknande källor - se $1 för detaljer.
'''LÄGG INTE UT UPPHOVSRÄTTSSKYDDAT MATERIAL HÄR UTAN TILLÅTELSE!'''",
'longpagewarning'                  => "'''VARNING: Den här sidan är $1 kilobyte lång;
vissa webbläsare kan ha problem att redigera sidor som närmar sig eller är större än 32 kB.
Överväg att bryta upp sidan i mindre delar.'''",
'longpageerror'                    => "'''FEL: Texten som du försöker spara är $1 kilobyte, vilket är mer än det maximalt tillåtna $2 kilobyte. Den kan inte sparas.'''",
'readonlywarning'                  => "'''VARNING: Databasen är tillfälligt låst för underhåll. Du kommer inte att kunna spara dina ändringar just nu. 
Det kan vara klokt att kopiera över texten till din egen dator, tills databasen är upplåst igen.'''

Administratören som låste databasen gav följande förklaring: $1",
'protectedpagewarning'             => "'''Varning: Den här sidan har låsts så att bara användare med administratörsrättigheter kan redigera den.'''
Den senaste loggposten tillhandahålls nedan som referens:",
'semiprotectedpagewarning'         => "'''Observera:''' Denna sida har låsts så att endast registrerade användare kan redigera den.
Den senaste loggposten tillhandahålls nedan som referens:",
'cascadeprotectedwarning'          => "'''Varning:''' Den här sidan är låst så att bara användare med administratörsrättigheter kan redigera den, eftersom den är inkluderad på följande {{PLURAL:$1|sida|sidor}} som skyddats med kaskaderande skrivskydd:",
'titleprotectedwarning'            => "'''Varning: Denna sida har låsts så att [[Special:ListGroupRights|specifika rättigheter]] krävs för att skapa den.'''
Den senaste loggposten tillhandahålls nedan som referens:",
'templatesused'                    => '{{PLURAL:$1|Mall|Mallar}} som används på den här sidan:',
'templatesusedpreview'             => '{{PLURAL:$1|Mall|Mallar}} som används i denna förhandsgranskning:',
'templatesusedsection'             => '{{PLURAL:$1|Mall|Mallar}} som används i det här avsnittet:',
'template-protected'               => '(skyddad)',
'template-semiprotected'           => '(delvis skyddad)',
'hiddencategories'                 => 'Denna sida är medlem i följande dolda {{PLURAL:$1|kategori|kategorier}}:',
'edittools'                        => '<!-- Denna text kommer att visas nedanför redigeringsrutor och uppladdningsformulär. -->',
'nocreatetitle'                    => 'Skapande av sidor begränsat',
'nocreatetext'                     => '{{SITENAME}} har begränsat möjligheterna att skapa nya sidor.
Du kan redigera existerande sidor, eller [[Special:UserLogin|logga in eller skapa ett användarkonto]].',
'nocreate-loggedin'                => 'Du har inte behörighet att skapa nya sidor.',
'sectioneditnotsupported-title'    => 'Sektionsredigering stöds inte',
'sectioneditnotsupported-text'     => 'Sektionsredigering stöds inte på denna sida.',
'permissionserrors'                => 'Behörighetsfel',
'permissionserrorstext'            => 'Du har inte behörighet att göra det du försöker göra, av följande {{PLURAL:$1|anledning|anledningar}}:',
'permissionserrorstext-withaction' => 'Du har inte behörighet att $2, av följande {{PLURAL:$1|anledning|anledningar}}:',
'recreate-moveddeleted-warn'       => "'''Varning: Du återskapar en sida som tidigare raderats.'''

Du bör överväga om det är lämpligt att fortsätta redigera den här sidan.
Raderings- och sidflyttningsloggen för den här sidan visas här som hjälp:",
'moveddeleted-notice'              => 'Den här sidan har raderats.
Raderings- och sidflyttningsloggen för sidan visas nedan som referens.',
'log-fulllog'                      => 'Visa fullständig logg',
'edit-hook-aborted'                => 'Redigering avbruten av hook.
Den gav ingen förklaring.',
'edit-gone-missing'                => 'Kunde inte uppdatera sidan.
Det verkar som att den har raderats.',
'edit-conflict'                    => 'Redigeringskonflikt.',
'edit-no-change'                   => 'Din redigering ignorerades, eftersom ingen ändring gjordes i texten.',
'edit-already-exists'              => 'Sidan kunde inte skapas.
Den finns redan.',

# Parser/template warnings
'expensive-parserfunction-warning'        => 'Varning: Denna sida innehåller för många anrop av resurskrävande parserfunktioner.

Antalet anrop får vara högst $2, nu görs {{PLURAL:$#|$1 anrop}}.',
'expensive-parserfunction-category'       => 'Sidor med för många resurskrävande parserfunktioner',
'post-expand-template-inclusion-warning'  => 'Varning: Den här sidan innehåller för mycket mallinklusioner.
Några av mallarna kommer inte att inkluderas.',
'post-expand-template-inclusion-category' => 'Sidor som inkluderar för mycket mallar',
'post-expand-template-argument-warning'   => 'Varning: Sidan innehåller en eller flera mallparametrar som blir för långa när de expanderas.
Dessa parametrar har uteslutits.',
'post-expand-template-argument-category'  => 'Sidor med uteslutna mallparametrar',
'parser-template-loop-warning'            => 'Mall-loop upptäckt: [[$1]]',
'parser-template-recursion-depth-warning' => 'Gräns för mallrekursionsdjup överskriden ($1)',
'language-converter-depth-warning'        => 'Gräns för språkkonverteringsdjup överskriden ($1)',

# "Undo" feature
'undo-success' => 'Redigeringen kan göras ogjord.
Var god och kontrollera jämförelsen nedan för att bekräfta att detta är vad du avser att göra, och spara sedan ändringarna nedan för att göra redigeringen ogjord.',
'undo-failure' => 'Redigeringen kunde inte göras ogjord på grund av konflikt med mellanliggande redigeringar.',
'undo-norev'   => 'Redigeringen kan inte göras ogjord eftersom den inte finns eller har raderats.',
'undo-summary' => 'Gör version $1 av [[Special:Contributions/$2|$2]] ([[User talk:$2|diskussion]]) ogjord',

# Account creation failure
'cantcreateaccounttitle' => 'Kan inte skapa konto',
'cantcreateaccount-text' => '[[User:$3|$3]] har blockerat den här IP-adressen (\'\'\'$1\'\'\') från att registrera konton.

Anledningen till blockeringen var "$2".',

# History pages
'viewpagelogs'           => 'Visa loggar för denna sida',
'nohistory'              => 'Den här sidan har ingen versionshistorik.',
'currentrev'             => 'Nuvarande version',
'currentrev-asof'        => 'Nuvarande version från $1',
'revisionasof'           => 'Versionen från $1',
'revision-info'          => 'Version från den $1 av $2',
'previousrevision'       => '← Äldre version',
'nextrevision'           => 'Nyare version →',
'currentrevisionlink'    => 'Nuvarande version',
'cur'                    => 'nuvarande',
'next'                   => 'nästa',
'last'                   => 'föregående',
'page_first'             => 'första',
'page_last'              => 'senaste',
'histlegend'             => "Val av diff: markera i klickrutorna för att jämföra versioner och tryck enter eller knappen längst ner.<br />
Förklaring: (nuvarande) = skillnad mot nuvarande version; (föregående) = skillnad mot föregående version; '''m''' = mindre ändring.",
'history-fieldset-title' => 'Bläddra i historiken',
'history-show-deleted'   => 'Endast borttagna',
'histfirst'              => 'Första',
'histlast'               => 'Senaste',
'historysize'            => '({{PLURAL:$1|1 byte|$1 byte}})',
'historyempty'           => '(tom)',

# Revision feed
'history-feed-title'          => 'Versionshistorik',
'history-feed-description'    => 'Versionshistorik för denna sida på wikin',
'history-feed-item-nocomment' => '$1 den $2',
'history-feed-empty'          => 'Den begärda sidan finns inte.
Den kan ha tagits bort från wikin eller bytt namn.
Prova att [[Special:Search|söka på wikin]] för relevanta nya sidor.',

# Revision deletion
'rev-deleted-comment'         => '(kommentar borttagen)',
'rev-deleted-user'            => '(användarnamn borttaget)',
'rev-deleted-event'           => '(loggåtgärd borttagen)',
'rev-deleted-user-contribs'   => '[användarnamn eller IP adress borttagen - editering visas ej bland bidragen]',
'rev-deleted-text-permission' => "Denna version av sidan har '''raderats'''.
Det kan finnas mer information i [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} raderingsloggen].",
'rev-deleted-text-unhide'     => "Den här versionen av sidan har '''raderats'''.
Det kan finnas mer information i [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} raderingsloggen].
Som administratör kan du fortfarande [$1 se den här versionen] om du önskar att fortsätta.",
'rev-suppressed-text-unhide'  => "Den här versionen av sidan har blivit '''undanhållen'''.
Det kan finnas mer information i [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} undanhållandeloggen].
Som administratör kan du fortfarande [$1 se den här versionen] om du önskar att fortsätta.",
'rev-deleted-text-view'       => "Denna version av sidan har '''raderats'''.
Som administratör kan du se den; det kan finnas mer information i [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} raderingsloggen].",
'rev-suppressed-text-view'    => "Denna version av sidan har '''undanhållits'''.
Som administratör kan du se den; det kan finnas mer information i [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} undanhållandeloggen].",
'rev-deleted-no-diff'         => "Du kan inte se den här diffen på grund av att en av versionerna har '''raderats'''.
Det kan finnas mer information i [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} raderingsloggen].",
'rev-suppressed-no-diff'      => "Du kan inte se skillnaden för en av revisionerna har blivit '''raderad'''.",
'rev-deleted-unhide-diff'     => "En av versionerna för den här diffen har '''raderats'''.
Det kan finnas mer information i [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} raderingsloggen].
Som administratör kan du fortfarande [$1 se den här diffen] om du önskar att fortsätta.",
'rev-suppressed-unhide-diff'  => "En av versionerna för denna diff har blivit '''undanhållen'''.
Det kan finnas detaljer i [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} undanhållandeloggen].
Som administratör kan du fortfarande [$1 se denna diff] om du önskar fortsätta.",
'rev-deleted-diff-view'       => "En av revisionerna för denna diff har blivit '''raderad'''.
Som en administratör kan du se denna diff, det kan finnas detaljer i [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} raderingsloggen].",
'rev-suppressed-diff-view'    => "En av versionerna för denna diff har blivit '''undanhållen'''.
Som administratör kan du se denna diff; det kan finnas detaljer i [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} undanhållandeloggen].",
'rev-delundel'                => 'visa/göm',
'rev-showdeleted'             => 'visa',
'revisiondelete'              => 'Ta bort/återställ versioner',
'revdelete-nooldid-title'     => 'Ogiltig målversion',
'revdelete-nooldid-text'      => 'Antingen har du inte angivit någon sidversion att utföra funktionen på,
eller så finns inte den version du angav,
eller så försöker du gömma den senaste versionen av sidan.',
'revdelete-nologtype-title'   => 'Ingen loggtyp specificerad',
'revdelete-nologtype-text'    => 'Du har inte specificerat en loggtyp att utföra denna handling på.',
'revdelete-nologid-title'     => 'Ogiltig logpost',
'revdelete-nologid-text'      => 'Du har antingen inte specificerat en loggpost att utföra denna funktion på eller så existerar inte den specificerade posten.',
'revdelete-no-file'           => 'Den specificerade filen finns inte.',
'revdelete-show-file-confirm' => 'Är du säker på att du vill visa en raderad version av filen "<nowiki>$1</nowiki>" från den $2 klockan $3?',
'revdelete-show-file-submit'  => 'Ja',
'revdelete-selected'          => "'''{{PLURAL:$2|Vald version|Valda versioner}} av [[:$1]]:'''",
'logdelete-selected'          => "'''{{PLURAL:$1|Vald loggåtgärd|Valda loggåtgärder}}:'''",
'revdelete-text'              => "'''Borttagna versioner och åtgärder kommer fortfarande att synas i historiken och i loggar, men deras innehåll kommer ej att vara tillgängligt för allmänheten.'''
Andra administratörer på {{SITENAME}} kommer fortfarande att kunna läsa det dolda innehållet och kan återställa sidan genom samma gränssnitt, om inte ytterligare begränsningar finns.",
'revdelete-confirm'           => 'Var god bekräfta att du vill göra detta, och att du förstår konsekvenserna, och att du gör så i enlighet med [[{{MediaWiki:Policy-url}}|policyn]].',
'revdelete-suppress-text'     => "Undanhållande ska '''bara''' användas i följande fall:
* Opassande personlig information
*: ''hemadresser och telefonnummer, personnummer, etc.''",
'revdelete-legend'            => 'Ändra synlighet',
'revdelete-hide-text'         => 'Dölj versionstext',
'revdelete-hide-image'        => 'Dölj filinnehåll',
'revdelete-hide-name'         => 'Dölj åtgärd och sidnamn',
'revdelete-hide-comment'      => 'Dölj redigeringskommentar',
'revdelete-hide-user'         => 'Dölj skribentens användarnamn/IP-adress',
'revdelete-hide-restricted'   => 'Undanhåll data från administratörer så väl som från övriga',
'revdelete-radio-same'        => '(låt vara)',
'revdelete-radio-set'         => 'Ja',
'revdelete-radio-unset'       => 'Nej',
'revdelete-suppress'          => 'Undanhåll data även från administratörer',
'revdelete-unsuppress'        => 'Ta bort begränsningar på återställda versioner',
'revdelete-log'               => 'Orsak till radering:',
'revdelete-submit'            => 'Tillämpa på {{PLURAL:$1|vald version|valda versioner}}',
'revdelete-logentry'          => 'ändrade synlighet för versioner av [[$1]]',
'logdelete-logentry'          => 'ändrade synlighet för åtgärder i [[$1]]',
'revdelete-success'           => "'''Sidversionssynlighet har uppdaterats.'''",
'revdelete-failure'           => "'''Sidversionssynlighet kunde inte uppdateras:'''
$1",
'logdelete-success'           => "'''Loggåtgärdens synlighet har ändrats.'''",
'logdelete-failure'           => "'''Loggens synlighet kunde inte ställas in:'''
$1",
'revdel-restore'              => 'ändra synlighet',
'revdel-restore-deleted'      => 'borttagna versioner',
'revdel-restore-visible'      => 'synliga versioner',
'pagehist'                    => 'Sidhistorik',
'deletedhist'                 => 'Raderad historik',
'revdelete-content'           => 'innehåll',
'revdelete-summary'           => 'sammanfattning',
'revdelete-uname'             => 'användarnamn',
'revdelete-restricted'        => 'satte begränsningar för administratörer',
'revdelete-unrestricted'      => 'tog bort begränsningar för administratörer',
'revdelete-hid'               => 'dolde $1',
'revdelete-unhid'             => 'synliggjorde $1',
'revdelete-log-message'       => '$1 för $2 {{PLURAL:$2|sidversion|sidversioner}}',
'logdelete-log-message'       => '$1 för $2 {{PLURAL:$2|åtgärd|åtgärder}}',
'revdelete-hide-current'      => 'Fel vid döljande av objektet daterat $2, $1: detta är nuvarande version.
Den kan inte döljas.',
'revdelete-show-no-access'    => 'Fel vid visning av objektet daterat $2, $1: detta objekt har markerats "begränsat".
Du har inte behörighet till det.',
'revdelete-modify-no-access'  => 'Fel vid förändring av objektet daterat $2, $1: detta objekt har markerats "begränsat".
Du har inte behörighet till det.',
'revdelete-modify-missing'    => 'Fel vid förändring av objektet med ID $1: det saknas i databasen!',
'revdelete-no-change'         => "'''Varning:''' objektet daterat $2, $1 hade redan de begärda synlighetsinställningarna.",
'revdelete-concurrent-change' => 'Fel vid förändring av objektet daterat $2, $1: dess status verkar ha ändrats av någon annan medan du försökte förändra det.
Vänligen kontrollera loggarna.',
'revdelete-only-restricted'   => 'Fel vid döljning av objekt daterat $2, $1: du kan inte undanhålla objekt från att visas för administratörer utan att också välja en av de övriga visningsalternativen.',
'revdelete-reason-dropdown'   => '*Vanliga orsaker till radering
** Brott mot copyright 
** Opassande personupplysningar
** Möjligt ärekränkande uppgifter',
'revdelete-otherreason'       => 'Annan/ytterligare anledning:',
'revdelete-reasonotherlist'   => 'Annan anledning',
'revdelete-edit-reasonlist'   => 'Redigera anledningar för radering',
'revdelete-offender'          => 'Versionens författare:',

# Suppression log
'suppressionlog'     => 'Undanhållandelogg',
'suppressionlogtext' => 'Nedan visas en lista över raderingar och blockeringar som berör innehåll dolt för administratörer.
Se [[Special:IPBlockList|blockeringslistan]] för listan över gällande blockeringar.',

# Revision move
'moverevlogentry'              => 'flyttade {{PLURAL:$3|en version|$3 versioner}} från $1 till $2',
'revisionmove'                 => 'Flytta versioner från "$1"',
'revmove-explain'              => 'Följande versioner kommer att flyttas från $1 till den angivna målsidan. Om målsidan inte finns, skapas den. Annars kommer de här versionerna att läggas till i sidans historik.',
'revmove-legend'               => 'Sätt målsida och sammanfattning',
'revmove-submit'               => 'Flytta versioner till vald sida',
'revisionmoveselectedversions' => 'Flytta valda versioner',
'revmove-reasonfield'          => 'Anledning:',
'revmove-titlefield'           => 'Målsida:',
'revmove-badparam-title'       => 'Dåliga parametrar',
'revmove-badparam'             => '<span class="error">Din begäran innehåller olagliga eller otillräckliga parametrar. Klicka på "tillbaka" och försök igen.</span>',
'revmove-norevisions-title'    => 'Ogiltig målversion',
'revmove-norevisions'          => '<span class="error">Du har inte angivit en eller flera målversioner för att utföra denna funktion eller den angivna versionen finns inte.</span>',
'revmove-nullmove-title'       => 'Dålig titel',
'revmove-nullmove'             => '<span class="error">Källa och målsida är identiska. Klicka på "tillbaka" och ange ett sidnamn som skiljer sig från "$1".</span>',
'revmove-success-existing'     => '{{PLURAL:$1|En version från [[$2]] har|$1 versioner från [[$2]] har}} flyttats till den befintliga sidan [[$3]].',
'revmove-success-created'      => '{{PLURAL:$1|En version från [[$2]] har|$1 versioner från [[$2]] har}} flyttats till den nyskapade sidan [[$3]].',

# History merging
'mergehistory'                     => 'Sammanfoga sidhistoriker',
'mergehistory-header'              => 'Med den här specialsidan kan du infoga versioner av en sida i en nyare sidas historik.
Se till att sidhistorikens kontinuitet behålls när du sammanfogar historik.',
'mergehistory-box'                 => 'Sammanfoga versioner av följande två sidor:',
'mergehistory-from'                => 'Källsida:',
'mergehistory-into'                => 'Målsida:',
'mergehistory-list'                => 'Sidhistorik som kan sammanfogas',
'mergehistory-merge'               => 'Följande versioner av [[:$1]] kan infogas i [[:$2]]. Med hjälp av alternativknapparna för varje version kan du välja att endast infoga versioner fram till en viss tidpunkt. Notera att om du använder navigationslänkarna så avmarkeras alla alternativknappar.',
'mergehistory-go'                  => 'Visa versioner som kan infogas',
'mergehistory-submit'              => 'Sammanfoga',
'mergehistory-empty'               => 'Inga versioner av sidorna kan sammanfogas.',
'mergehistory-success'             => '$3 {{PLURAL:$3|version|versioner}} av [[:$1]] har infogats i [[:$2]].',
'mergehistory-fail'                => 'Historikerna kunde inte sammanfogas, kontrollera de sidor och den sidversion som du valt.',
'mergehistory-no-source'           => 'Källsidan $1 finns inte.',
'mergehistory-no-destination'      => 'Målsidan $1 finns inte.',
'mergehistory-invalid-source'      => 'Källsidan måste vara en giltig sidtitel.',
'mergehistory-invalid-destination' => 'Målsidan måste vara en giltig sidtitel.',
'mergehistory-autocomment'         => 'Infogade [[:$1]] i [[:$2]]',
'mergehistory-comment'             => 'Infogade [[:$1]] i [[:$2]]: $3',
'mergehistory-same-destination'    => 'Käll- och målsidor kan inte vara samma',
'mergehistory-reason'              => 'Anledning:',

# Merge log
'mergelog'           => 'Sammanfogningslogg',
'pagemerge-logentry' => 'infogade [[$1]] i [[$2]] (versioner t.o.m. $3)',
'revertmerge'        => 'Återställ infogning',
'mergelogpagetext'   => 'Detta är en lista över de senaste sammansfogningarna av sidhistoriker.',

# Diffs
'history-title'            => 'Versionshistorik för "$1"',
'difference'               => '(Skillnad mellan versioner)',
'lineno'                   => 'Rad $1:',
'compareselectedversions'  => 'Jämför angivna versioner',
'showhideselectedversions' => 'Visa/dölj valda versioner',
'editundo'                 => 'gör ogjord',
'diff-multi'               => '({{PLURAL:$1|En mellanliggande version|$1 mellanliggande versioner}} visas inte.)',

# Search results
'searchresults'                    => 'Sökresultat',
'searchresults-title'              => 'Sökresultat för "$1"',
'searchresulttext'                 => 'Se [[{{MediaWiki:Helppage}}|hjälpsidan]] för mer information om sökning på {{SITENAME}}.',
'searchsubtitle'                   => 'Du sökte efter \'\'\'[[:$1]]\'\'\' ([[Special:Prefixindex/$1|alla sidor som startar med "$1"]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|alla sidor som länkar till "$1"]])',
'searchsubtitleinvalid'            => 'För sökbegreppet $1',
'toomanymatches'                   => 'Sökningen gav för många resultat, försök med en annan fråga',
'titlematches'                     => 'Träffar i sidtitlar',
'notitlematches'                   => 'Det finns ingen sida vars titel överensstämmer med sökordet.',
'textmatches'                      => 'Sidor som innehåller sökordet:',
'notextmatches'                    => 'Det finns inga sidor som innehåller sökordet',
'prevn'                            => 'föregående {{PLURAL:$1|$1}}',
'nextn'                            => 'nästa {{PLURAL:$1|$1}}',
'prevn-title'                      => 'Föregående $1 {{PLURAL:$1|resultat|resultat}}',
'nextn-title'                      => 'Nästa $1 {{PLURAL:$1|resultat|resultat}}',
'shown-title'                      => 'Visa $1 {{PLURAL:$1|resultat|resultat}} per sida',
'viewprevnext'                     => 'Visa ($1 {{int:pipe-separator}} $2) ($3)',
'searchmenu-legend'                => 'Sökalternativ',
'searchmenu-exists'                => "'''Det finns en sida med namnet \"[[:\$1]]\" på denna wiki'''",
'searchmenu-new'                   => "'''Skapa sidan \"[[:\$1]]\" på denna wiki!'''",
'searchhelp-url'                   => 'Help:Innehåll',
'searchmenu-prefix'                => '[[Special:PrefixIndex/$1|Bläddra igenom sidor med detta prefix]]',
'searchprofile-articles'           => 'Innehållssidor',
'searchprofile-project'            => 'Hjälp- och projektsidor',
'searchprofile-images'             => 'Multimedia',
'searchprofile-everything'         => 'Allt',
'searchprofile-advanced'           => 'Avancerad',
'searchprofile-articles-tooltip'   => 'Sök i $1',
'searchprofile-project-tooltip'    => 'Sök i $1',
'searchprofile-images-tooltip'     => 'Sök efter filer',
'searchprofile-everything-tooltip' => 'Sök allt innehåll (inklusive diskussionssidor)',
'searchprofile-advanced-tooltip'   => 'Sök i vissa namnrymder',
'search-result-size'               => '$1 ({{PLURAL:$2|1 ord|$2 ord}})',
'search-result-category-size'      => '{{PLURAL:$1|1 medlem|$1 medlemmar}} ({{PLURAL:$2|1 underkategori|$2 underkategorier}}, {{PLURAL:$3|1 fil|$3 filer}})',
'search-result-score'              => 'Relevans: $1%',
'search-redirect'                  => '(omdirigering $1)',
'search-section'                   => '(avsnitt $1)',
'search-suggest'                   => 'Menade du: $1',
'search-interwiki-caption'         => 'Systerprojekt',
'search-interwiki-default'         => 'Resultat i $1:',
'search-interwiki-more'            => '(mer)',
'search-mwsuggest-enabled'         => 'med förslag',
'search-mwsuggest-disabled'        => 'inga förslag',
'search-relatedarticle'            => 'Relaterad',
'mwsuggest-disable'                => 'Avaktivera AJAX-förslag',
'searcheverything-enable'          => 'Sök i alla namnrymder',
'searchrelated'                    => 'relaterad',
'searchall'                        => 'alla',
'showingresults'                   => "Nedan visas upp till {{PLURAL:$1|'''1''' post|'''$1''' poster}} från och med nummer '''$2'''.",
'showingresultsnum'                => "Nedan visas {{PLURAL:$3|'''1''' post|'''$3''' poster}} från och med nummer '''$2'''.",
'showingresultsheader'             => "{{PLURAL:$5|Resultat '''$1''' av '''$3'''|Resultat '''$1 - $2''' av '''$3'''}} för '''$4'''",
'nonefound'                        => "'''Observera:''' Som standard sker sökning endast i vissa namnrymder. Du kan pröva att skriva ''all:'' i början av din sökning om du vill söka i alla sidor (inklusive diskussionssidor, mallar, m.m.), eller så kan du att börja din sökning med namnet på den namnrymd du vill söka i.",
'search-nonefound'                 => 'Inga resultat matchade frågan.',
'powersearch'                      => 'Avancerad sökning',
'powersearch-legend'               => 'Avancerad sökning',
'powersearch-ns'                   => 'Sök i namnrymderna:',
'powersearch-redir'                => 'Visa omdirigeringar',
'powersearch-field'                => 'Sök efter',
'powersearch-togglelabel'          => 'Kryssa:',
'powersearch-toggleall'            => 'Alla',
'powersearch-togglenone'           => 'Ingen',
'search-external'                  => 'Extern sökning',
'searchdisabled'                   => 'Sökfunktionen på {{SITENAME}} är avstängd.
Du kan istället göra sökningar med hjälp av Google.
Notera dock att deras indexering av {{SITENAME}} kan vara något föråldrad.',

# Quickbar
'qbsettings'               => 'Snabbmeny',
'qbsettings-none'          => 'Ingen',
'qbsettings-fixedleft'     => 'Fast vänster',
'qbsettings-fixedright'    => 'Fast höger',
'qbsettings-floatingleft'  => 'Flytande vänster',
'qbsettings-floatingright' => 'Flytande höger',

# Preferences page
'preferences'                   => 'Inställningar',
'mypreferences'                 => 'Mina inställningar',
'prefs-edits'                   => 'Antal redigeringar:',
'prefsnologin'                  => 'Inte inloggad',
'prefsnologintext'              => 'Du måste vara <span class="plainlinks">[{{fullurl:{{#Special:UserLogin}}|returnto=$1}} inloggad]</span> för att kunna ändra dina inställningar.',
'changepassword'                => 'Byt lösenord',
'prefs-skin'                    => 'Utseende',
'skin-preview'                  => 'förhandsvisning',
'prefs-math'                    => 'Matematik',
'datedefault'                   => 'Ovidkommande',
'prefs-datetime'                => 'Datum och tid',
'prefs-personal'                => 'Mitt konto',
'prefs-rc'                      => 'Senaste ändringar',
'prefs-watchlist'               => 'Bevakningslista',
'prefs-watchlist-days'          => 'Antal dygn som skall visas i bevakningslistan:',
'prefs-watchlist-days-max'      => '(maximalt 7 dygn)',
'prefs-watchlist-edits'         => 'Maximalt antal redigeringar som visas i utökad bevakningslista:',
'prefs-watchlist-edits-max'     => '(maximalt antal: 1 000)',
'prefs-watchlist-token'         => 'Bevakningslistsnyckel:',
'prefs-misc'                    => 'Diverse',
'prefs-resetpass'               => 'Ändra lösenord',
'prefs-email'                   => 'Alternativ för e-post',
'prefs-rendering'               => 'Utseende',
'saveprefs'                     => 'Spara',
'resetprefs'                    => 'Återställ osparade ändringar',
'restoreprefs'                  => 'Återställ alla standardinställningar',
'prefs-editing'                 => 'Redigering',
'prefs-edit-boxsize'            => 'Storlek på redigeringsfönstret.',
'rows'                          => 'Rader:',
'columns'                       => 'Kolumner:',
'searchresultshead'             => 'Sökning',
'resultsperpage'                => 'Träffar per sida:',
'contextlines'                  => 'Antal rader per träff:',
'contextchars'                  => 'Tecken per rad:',
'stub-threshold'                => 'Gräns för <a href="#" class="stub">stublänk</a>-formattering (byte):',
'recentchangesdays'             => 'Antal dygn som skall visas i "senaste ändringarna":',
'recentchangesdays-max'         => '(maximalt $1 {{PLURAL:$1|dygn|dygn}})',
'recentchangescount'            => 'Antal redigeringar som visas som standard:',
'prefs-help-recentchangescount' => 'Detta inkluderar senaste ändringarna, sidhistorik och loggar.',
'prefs-help-watchlist-token'    => 'Om du fyller i detta fält med en hemlig nyckel så skapas ett RSS-flöde för din bevakningslista.
Alla som kan nyckeln kan också läsa din bevakningslista, så välj ett säkert värde.
Här är ett slumpmässigt genererat värde som du kan använda: $1',
'savedprefs'                    => 'Dina inställningar har sparats',
'timezonelegend'                => 'Tidszon:',
'localtime'                     => 'Lokal tid:',
'timezoneuseserverdefault'      => 'Använd serverns standardinställning',
'timezoneuseoffset'             => 'Annan (specificera skillnad)',
'timezoneoffset'                => 'Skillnad¹:',
'servertime'                    => 'Serverns tid:',
'guesstimezone'                 => 'Fyll i från webbläsare',
'timezoneregion-africa'         => 'Afrika',
'timezoneregion-america'        => 'Amerika',
'timezoneregion-antarctica'     => 'Antarktis',
'timezoneregion-arctic'         => 'Arktis',
'timezoneregion-asia'           => 'Asien',
'timezoneregion-atlantic'       => 'Atlanten',
'timezoneregion-australia'      => 'Australien',
'timezoneregion-europe'         => 'Europa',
'timezoneregion-indian'         => 'Indiska oceanen',
'timezoneregion-pacific'        => 'Stilla havet',
'allowemail'                    => 'Tillåt e-post från andra användare',
'prefs-searchoptions'           => 'Sökalternativ',
'prefs-namespaces'              => 'Namnrymder',
'defaultns'                     => 'Sök annars i dessa namnrymder:',
'default'                       => 'ursprungsinställning',
'prefs-files'                   => 'Filer',
'prefs-custom-css'              => 'personlig CSS',
'prefs-custom-js'               => 'personlig JavaScript',
'prefs-common-css-js'           => 'Delad CSS/JS för alla teman:',
'prefs-reset-intro'             => 'Du kan använda den här sidan till att återställa dina inställningar till webbplatsens standardinställningar.
Detta kan inte återställas.',
'prefs-emailconfirm-label'      => 'E-postbekräftelse:',
'prefs-textboxsize'             => 'Storlek på redigeringsrutan',
'youremail'                     => 'E-post:',
'username'                      => 'Användarnamn:',
'uid'                           => 'Användar-ID:',
'prefs-memberingroups'          => 'Medlem av {{PLURAL:$1|gruppen|grupperna}}:',
'prefs-registration'            => 'Registreringstid:',
'yourrealname'                  => 'Riktigt namn:',
'yourlanguage'                  => 'Språk:',
'yournick'                      => 'Signatur:',
'prefs-help-signature'          => 'Kommentarer på diskussionssidor ska signeras med "<nowiki>~~~~</nowiki>", vilket konverteras till din signatur och tidpunkt.',
'badsig'                        => 'Det är något fel med råsignaturen, kontrollera HTML-koden.',
'badsiglength'                  => 'Din signatur är för lång.
Den får inte vara längre än $1 {{PLURAL:$1|tecken|tecken}}.',
'yourgender'                    => 'Kön:',
'gender-unknown'                => 'Ospecificerat',
'gender-male'                   => 'Man',
'gender-female'                 => 'Kvinna',
'prefs-help-gender'             => 'Valfri: används för att mjukvaran ska kunna adressera med rätt genus. Denna information kommer vara offentlig.',
'email'                         => 'E-post',
'prefs-help-realname'           => 'Riktigt namn behöver inte anges.
Om du väljer att ange ditt riktiga namn, kommer det att användas för att tillskriva dig ditt arbete.',
'prefs-help-email'              => 'Att ange e-postadress är valfritt, men gör det möjligt att få ditt lösenord mejlat till dig om du glömmer det.
Du kan också välja att låta andra användare kontakta dig genom din användar- eller användardiskussionssida utan att du behöver avslöja din identitet.',
'prefs-help-email-required'     => 'E-postadress måste anges.',
'prefs-info'                    => 'Grundläggande information',
'prefs-i18n'                    => 'Internationalisering',
'prefs-signature'               => 'Signatur',
'prefs-dateformat'              => 'Datumformat',
'prefs-timeoffset'              => 'Tidsförskjutning',
'prefs-advancedediting'         => 'Avancerade alternativ',
'prefs-advancedrc'              => 'Avancerade alternativ',
'prefs-advancedrendering'       => 'Avancerade alternativ',
'prefs-advancedsearchoptions'   => 'Avancerade alternativ',
'prefs-advancedwatchlist'       => 'Avancerade alternativ',
'prefs-display'                 => 'Visningsalternativ',
'prefs-diffs'                   => 'Skillnader',

# User rights
'userrights'                   => 'Hantering av användarrättigheter',
'userrights-lookup-user'       => 'Hantera användargrupper',
'userrights-user-editname'     => 'Skriv in ett användarnamn:',
'editusergroup'                => 'Ändra användargrupper',
'editinguser'                  => "Ändrar rättigheter för användaren '''[[User:$1|$1]]''' ([[User talk:$1|{{int:talkpagelinktext}}]]{{int:pipe-separator}}[[Special:Contributions/$1|{{int:contribslink}}]])",
'userrights-editusergroup'     => 'Ändra användargrupper',
'saveusergroups'               => 'Spara användargrupper',
'userrights-groupsmember'      => 'Medlem i:',
'userrights-groupsmember-auto' => 'Implicit medlem av:',
'userrights-groups-help'       => 'Du kan ändra vilka grupper denna användare är medlem i.
* En ikryssad ruta betyder användaren är medlem i den gruppen.
* En okryssad ruta betyder att användaren inte är medlem i den gruppen.
* En asterisk (*) markerar att du inte kan ta bort gruppen när du har lagt till den, eller vice versa.',
'userrights-reason'            => 'Anledning:',
'userrights-no-interwiki'      => 'Du har inte behörighet att ändra användarrättigheter på andra wikis.',
'userrights-nodatabase'        => 'Databasen $1 finns inte eller så är den inte lokal.',
'userrights-nologin'           => 'Du måste [[Special:UserLogin|logga in]] med ett administratörskonto för att ändra användarrättigheter.',
'userrights-notallowed'        => 'Ditt konto har inte behörighet till att ändra användarrättigheter.',
'userrights-changeable-col'    => 'Grupper du kan ändra',
'userrights-unchangeable-col'  => 'Grupper du inte kan ändra',

# Groups
'group'               => 'Grupp:',
'group-user'          => 'Användare',
'group-autoconfirmed' => 'Bekräftade användare',
'group-bot'           => 'Robotar',
'group-sysop'         => 'Administratörer',
'group-bureaucrat'    => 'Byråkrater',
'group-suppress'      => 'Censorer',
'group-all'           => '(alla)',

'group-user-member'          => 'användare',
'group-autoconfirmed-member' => 'bekräftad användare',
'group-bot-member'           => 'robot',
'group-sysop-member'         => 'administratör',
'group-bureaucrat-member'    => 'byråkrat',
'group-suppress-member'      => 'censor',

'grouppage-user'          => '{{ns:project}}:Användare',
'grouppage-autoconfirmed' => '{{ns:project}}:Bekräftade användare',
'grouppage-bot'           => '{{ns:project}}:Robotar',
'grouppage-sysop'         => '{{ns:project}}:Administratörer',
'grouppage-bureaucrat'    => '{{ns:project}}:Byråkrater',
'grouppage-suppress'      => '{{ns:project}}:Censur',

# Rights
'right-read'                  => 'Se sidor',
'right-edit'                  => 'Redigera sidor',
'right-createpage'            => 'Skapa sidor (som inte är diskussionssidor)',
'right-createtalk'            => 'Skapa diskussionssidor',
'right-createaccount'         => 'Skapa nya användarkonton',
'right-minoredit'             => 'Markera mindre ändringar',
'right-move'                  => 'Flytta sidor',
'right-move-subpages'         => 'Flytta sidor med deras undersidor',
'right-move-rootuserpages'    => 'Flytta root-användarsidor',
'right-movefile'              => 'Flytta filer',
'right-suppressredirect'      => 'Behöver inte skapa omdirigeringar vid sidflyttning',
'right-upload'                => 'Ladda upp filer',
'right-reupload'              => 'Skriva över existerande filer',
'right-reupload-own'          => 'Skriva över egna filer',
'right-reupload-shared'       => 'Skriva över delade filer lokalt',
'right-upload_by_url'         => 'Ladda upp en fil genom en URL',
'right-purge'                 => 'Rensa cachen för sidor utan att behöva bekräfta',
'right-autoconfirmed'         => 'Redigera halvlåsta sidor',
'right-bot'                   => 'Behandlas som en automatisk process',
'right-nominornewtalk'        => 'Får inte meddelanden om nya ändringar på diskussionssidan vid mindre ändringar.',
'right-apihighlimits'         => 'Använda högre gränser i API-frågor',
'right-writeapi'              => 'Använda skriv-API:t',
'right-delete'                => 'Radera sidor',
'right-bigdelete'             => 'Radera sidor med stor historik',
'right-deleterevision'        => 'Radera och återställa enskilda sidversioner',
'right-deletedhistory'        => 'Se raderad historik utan tillhörande sidtext',
'right-deletedtext'           => 'Visa raderad text och ändringar mellan raderade versioner',
'right-browsearchive'         => 'Söka efter raderade sidor',
'right-undelete'              => 'Återställa raderade sidor',
'right-suppressrevision'      => 'Se och återställa sidversioner som dolts för administratörer',
'right-suppressionlog'        => 'Se privata loggar',
'right-block'                 => 'Blockera andra användare från att redigera',
'right-blockemail'            => 'Blockera användare från att skicka e-post',
'right-hideuser'              => 'Blockera användarnamn och dölja det från blockeringsloggen',
'right-ipblock-exempt'        => 'Kan redigera från blockerade IP-adresser',
'right-proxyunbannable'       => 'Kan redigera från blockerade proxyer',
'right-unblockself'           => 'Avblockera sig själva',
'right-protect'               => 'Ändra skyddsnivåer och redigera skyddade sidor',
'right-editprotected'         => 'Redigera skyddade sidor',
'right-editinterface'         => 'Redigera användargränssnittet',
'right-editusercssjs'         => 'Redigera andra användares CSS- och JS-filer',
'right-editusercss'           => 'Redigera andra användares CSS-filer',
'right-edituserjs'            => 'Redigera andra användares JS-filer',
'right-rollback'              => 'Rulla tillbaka den användare som senast redigerat en sida',
'right-markbotedits'          => 'Markera tillbakarullningar som robotändringar',
'right-noratelimit'           => 'Påverkas inte av hastighetsgränser',
'right-import'                => 'Importera sidor från andra wikier',
'right-importupload'          => 'Importera sidor genom uppladdning',
'right-patrol'                => 'Markera ändringar som patrullerade',
'right-autopatrol'            => 'Får automatiskt sina ändringar markerade som patrullerade',
'right-patrolmarks'           => 'Se markeringar av opatrullerade ändringar i senaste ändringarna',
'right-unwatchedpages'        => 'Se listan över obevakade sidor',
'right-trackback'             => 'Lägga till trackback',
'right-mergehistory'          => 'Sammanfoga sidhistoriker',
'right-userrights'            => 'Ändra alla användarrättigheter',
'right-userrights-interwiki'  => 'Ändra rättigheter för användare på andra wikier',
'right-siteadmin'             => 'Låsa och låsa upp databasen',
'right-reset-passwords'       => 'Återställa andra användares lösenord',
'right-override-export-depth' => 'Exportera sidor inklusive länkade sidor till ett djup på 5',
'right-sendemail'             => 'Skicka e-post till andra användare',
'right-revisionmove'          => 'Flytta versioner',

# User rights log
'rightslog'      => 'Användarrättighetslogg',
'rightslogtext'  => 'Detta är en logg över ändringar av användares rättigheter.',
'rightslogentry' => 'ändrade grupptillhörighet för $1 från $2 till $3',
'rightsnone'     => '(inga)',

# Associated actions - in the sentence "You do not have permission to X"
'action-read'                 => 'läsa denna sida',
'action-edit'                 => 'redigera denna sida',
'action-createpage'           => 'skapa sidor',
'action-createtalk'           => 'skapa diskussionssidor',
'action-createaccount'        => 'skapa detta användarkonto',
'action-minoredit'            => 'markera denna redigering som mindre',
'action-move'                 => 'flytta denna sida',
'action-move-subpages'        => 'flytta denna sida, och dess undersidor',
'action-move-rootuserpages'   => 'flytta root-användarsidor',
'action-movefile'             => 'flytta den här filen',
'action-upload'               => 'ladda upp denna fil',
'action-reupload'             => 'skriva över denna existerade fil',
'action-reupload-shared'      => 'skriva över denna fil i gemensam databas',
'action-upload_by_url'        => 'ladda upp denna fil från en URL-adress',
'action-writeapi'             => 'använda skriv-API:t',
'action-delete'               => 'radera denna sida',
'action-deleterevision'       => 'radera denna version',
'action-deletedhistory'       => 'se denna sidas raderade historik',
'action-browsearchive'        => 'söka raderade sidor',
'action-undelete'             => 'avradera denna sida',
'action-suppressrevision'     => 'granska och återställa denna dolda version',
'action-suppressionlog'       => 'se denna privata logg',
'action-block'                => 'blockera denna användare från redigering',
'action-protect'              => 'ändra skyddsnivå för denna sida',
'action-import'               => 'importera denna sida från en annan wiki',
'action-importupload'         => 'importera denna sida från en uppladdad fil',
'action-patrol'               => 'märka annans redigering som patrullerad',
'action-autopatrol'           => 'få din redigering märkt som patrullerad',
'action-unwatchedpages'       => 'se listan över obevakade sidor',
'action-trackback'            => 'lägga in en trackback',
'action-mergehistory'         => 'slå ihop historiken för denna sida',
'action-userrights'           => 'ändra alla användarrättigheter',
'action-userrights-interwiki' => 'ändra rättigheter för användare på andra wikier',
'action-siteadmin'            => 'låsa eller låsa upp databasen',
'action-revisionmove'         => 'flytta versioner',

# Recent changes
'nchanges'                          => '$1 {{PLURAL:$1|ändring|ändringar}}',
'recentchanges'                     => 'Senaste ändringarna',
'recentchanges-legend'              => 'Alternativ för senaste ändringarna',
'recentchangestext'                 => 'Följ de senaste ändringarna i wikin på denna sida.',
'recentchanges-feed-description'    => 'Följ de senaste ändringarna i wikin genom den här matningen.',
'recentchanges-label-legend'        => 'Förklaring: $1',
'recentchanges-legend-newpage'      => '$1 - ny sida',
'recentchanges-label-newpage'       => 'Denna redigering skapade en ny sida',
'recentchanges-legend-minor'        => '$1 - mindre ändring',
'recentchanges-label-minor'         => 'Detta är en mindre ändring',
'recentchanges-legend-bot'          => '$1 - botredigering',
'recentchanges-label-bot'           => 'Denna redigering gjordes av en bot',
'recentchanges-legend-unpatrolled'  => '$1 - okontrollerad redigering',
'recentchanges-label-unpatrolled'   => 'Denna redigering har inte blivit kontrollerad ännu',
'rcnote'                            => "Nedan visas {{PLURAL:$1|'''1''' ändring|de senaste '''$1''' ändringarna}} från {{PLURAL:$2|det senaste dygnet|de senaste '''$2''' dygnen}}, per $4, kl. $5.",
'rcnotefrom'                        => "Nedan visas ändringar sedan '''$2''' (upp till '''$1''' visas).",
'rclistfrom'                        => 'Visa ändringar efter $1',
'rcshowhideminor'                   => '$1 mindre ändringar',
'rcshowhidebots'                    => '$1 robotar',
'rcshowhideliu'                     => '$1 inloggade användare',
'rcshowhideanons'                   => '$1 oinloggade användare',
'rcshowhidepatr'                    => '$1 kontrollerade redigeringar',
'rcshowhidemine'                    => '$1 mina ändringar',
'rclinks'                           => 'Visa senaste $1 ändringar under de senaste $2 dygnen<br />$3',
'diff'                              => 'skillnad',
'hist'                              => 'historik',
'hide'                              => 'Göm',
'show'                              => 'Visa',
'minoreditletter'                   => 'm',
'newpageletter'                     => 'N',
'boteditletter'                     => 'b',
'number_of_watching_users_pageview' => '[$1 bevakande {{PLURAL:$1|användare|användare}}]',
'rc_categories'                     => 'Begränsa till följande kategorier (separera med "|")',
'rc_categories_any'                 => 'Vilken som helst',
'newsectionsummary'                 => '/* $1 */ nytt avsnitt',
'rc-enhanced-expand'                => 'Visa detaljer (kräver JavaScript)',
'rc-enhanced-hide'                  => 'Göm detaljer',

# Recent changes linked
'recentchangeslinked'          => 'Relaterade ändringar',
'recentchangeslinked-feed'     => 'Relaterade ändringar',
'recentchangeslinked-toolbox'  => 'Relaterade ändringar',
'recentchangeslinked-title'    => 'Ändringar relaterade till $1',
'recentchangeslinked-noresult' => 'Inga ändringar på länkade sidor under den angivna tidsperioden.',
'recentchangeslinked-summary'  => "Detta är en lista över de senaste ändringarna på sidor som länkas till från en given sida (eller på sidor som hör till en viss kategori).
Sidor på [[Special:Watchlist|din bevakningslista]] är markerade med '''fetstil'''.",
'recentchangeslinked-page'     => 'Sidnamn:',
'recentchangeslinked-to'       => 'Visa ändringar på sidor med länkar till den givna sidan istället',

# Upload
'upload'                      => 'Ladda upp fil',
'uploadbtn'                   => 'Ladda upp fil',
'reuploaddesc'                => 'Avbryt uppladdningen och gå tillbaka till uppladdningsformuläret.',
'upload-tryagain'             => 'Skicka modifierad filbeskrivning',
'uploadnologin'               => 'Inte inloggad',
'uploadnologintext'           => 'Du måste vara [[Special:UserLogin|inloggad]] för att kunna ladda upp filer.',
'upload_directory_missing'    => 'Uppladdningskatalogen ($1) saknas och kunde inte skapas av webbservern.',
'upload_directory_read_only'  => 'Webbservern kan inte skriva till uppladdningskatalogen ($1).',
'uploaderror'                 => 'Fel vid uppladdningen',
'upload-recreate-warning'     => "'''Varning: En fil med det namnet har tagits bort eller flyttats.'''

Raderings- och sidflyttningsloggen för denna sida återges här:",
'uploadtext'                  => "Använd formuläret nedan för att ladda upp filer.
För att titta på eller leta efter filer som redan har laddats upp, se [[Special:FileList|listan över uppladdade filer]]. Uppladdningar loggförs även i [[Special:Log/upload|uppladdningsloggen]], och raderingar i [[Special:Log/delete|raderingsloggen]].

Använd en länk på något av följande format för att infoga en fil på en sida:
* '''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:File.jpg]]</nowiki></tt>''' för att visa filen i dess hela storlek
* '''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:File.png|200px|thumb|left|alternativ text]]</nowiki></tt>''' för att visa en rendering med bredden 200 pixel i en ruta till vänster med bildtexten 'alternativ text'
* '''<tt><nowiki>[[</nowiki>{{ns:media}}<nowiki>:File.ogg]]</nowiki></tt>''' för att länka direkt till filen utan att visa den",
'upload-permitted'            => 'Tillåtna filtyper: $1.',
'upload-preferred'            => 'Föredragna filtyper: $1.',
'upload-prohibited'           => 'Förbjudna filtyper: $1.',
'uploadlog'                   => 'Uppladdningar',
'uploadlogpage'               => 'Uppladdningslogg',
'uploadlogpagetext'           => 'Det här är en logg över de senast uppladdade filerna.
Se [[Special:NewFiles|galleriet över nya filer]] för en mer visuell översikt.',
'filename'                    => 'Filnamn',
'filedesc'                    => 'Beskrivning',
'fileuploadsummary'           => 'Beskrivning:',
'filereuploadsummary'         => 'Filändringar:',
'filestatus'                  => 'Upphovsrättslig status:',
'filesource'                  => 'Källa:',
'uploadedfiles'               => 'Uppladdade filer',
'ignorewarning'               => 'Ignorera varningen och spara filen ändå.',
'ignorewarnings'              => 'Ignorera eventuella varningar',
'minlength1'                  => 'Filens namn måste innehålla minst ett tecken.',
'illegalfilename'             => 'Filnamnet "$1" innehåller tecken som inte är tillåtna i sidtitlar. Byt namn på filen och försök ladda upp igen.',
'badfilename'                 => 'Filens namn har blivit ändrat till "$1".',
'filetype-mime-mismatch'      => 'Filnamnsändelse matchar inte MIME-typ.',
'filetype-badmime'            => 'Uppladdning av filer med MIME-typen "$1" är inte tillåten.',
'filetype-bad-ie-mime'        => 'Kan inte ladda upp denna fil på grund av att Internet Explorer skulle upptäcka att den är "$1", vilket är en otillåten och möjligtvis farlig filtyp.',
'filetype-unwanted-type'      => "'''\".\$1\"''' är en oönskad filtyp.
{{PLURAL:\$3|Föredragen filtyp|Föredragna filtyper}} är \$2.",
'filetype-banned-type'        => "'''\".\$1\"''' är inte en tillåten filtyp.
{{PLURAL:\$3|Tillåtna filtyper|Tillåten filtyp}} är \$2.",
'filetype-missing'            => 'Filnamnet saknar ändelse (t ex ".jpg").',
'empty-file'                  => 'Filen du skickade var tom.',
'file-too-large'              => 'Filen du skickade var för stor.',
'filename-tooshort'           => 'Filnamnet är för kort.',
'filetype-banned'             => 'Denna typ av fil är förbjuden.',
'verification-error'          => 'Denna fil klarade inte verifieringen.',
'hookaborted'                 => 'Ändringen du försökte göra avbröts av en extension hook.',
'illegal-filename'            => 'Filnamnet är inte tillåtet.',
'overwrite'                   => 'Det är inte tillåtet att skriva över en befintlig fil.',
'unknown-error'               => 'Ett okänt fel uppstod.',
'tmp-create-error'            => 'Kunde inte skapa temporär fil.',
'tmp-write-error'             => 'Fel vid skrivning av temporär fil.',
'large-file'                  => 'Det rekommenderas att filer inte är större än $1;
denna fil är $2.',
'largefileserver'             => 'Denna fil är större än vad servern ställts in att tillåta.',
'emptyfile'                   => 'Filen du laddade upp verkar vara tom; felet kan bero på ett stavfel i filnamnet. Kontrollera om du verkligen vill ladda upp denna fil.',
'fileexists'                  => "Det finns redan en fil med detta namn.
Titta på '''<tt>[[:$1]]</tt>''', såvida du inte är säker på att du vill ändra den.
[[$1|thumb]]",
'filepageexists'              => "Beskrivningssidan för denna fil har redan skapats på '''<tt>[[:$1]]</tt>''', men just nu finns ingen fil med detta namn.
Den sammanfattning du skriver här kommer inte visas på beskrivningssidan.
För att din sammanfattning ska visas där, så måste du redigera beskrivningssidan manuellt.
[[$1|thumb]]",
'fileexists-extension'        => "En fil med ett liknande namn finns redan: [[$2|thumb]]
* Namn på den fil du försöker ladda upp: '''<tt>[[:$1]]</tt>'''
* Namn på filen som redan finns: '''<tt>[[:$2]]</tt>'''
Var vänlig välj ett annat namn.",
'fileexists-thumbnail-yes'    => "Filen verkar vara en bild med förminskad storlek ''(miniatyrbild)''. [[$1|thumb]]
Var vänlig kontrollera filen '''<tt>[[:$1]]</tt>'''.
Om det är samma fil i originalstorlek så är det inte nödvändigt att ladda upp en extra miniatyrbild.",
'file-thumbnail-no'           => "Filnamnet börjar med '''<tt>$1</tt>'''.
Det verkar vara en bild med förminskad storlek ''(miniatyrbild)''.
Om du har denna bild i full storlek, ladda då hellre upp den, annars var vänlig och ändra filens namn.",
'fileexists-forbidden'        => 'En fil med detta namn existerar redan, och kan inte överskrivas.
Om du fortfarande vill ladda upp din fil, var god gå tillbaka och välj ett nytt namn. [[File:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => 'En fil med detta namn finns redan bland de delade filerna.
Om du ändå vill ladda upp din fil, gå då tillbaka och använd ett annat namn. [[File:$1|thumb|center|$1]]',
'file-exists-duplicate'       => 'Den här filen är en dubblett till följande {{PLURAL:$1|fil|filer}}:',
'file-deleted-duplicate'      => 'En identisk fil till den här filen ([[$1]]) har tidigare raderats. Du bör kontrollera den filens raderingshistorik innan du fortsätter att återuppladda den.',
'successfulupload'            => 'Uppladdningen lyckades',
'uploadwarning'               => 'Uppladdningsvarning',
'uploadwarning-text'          => 'Var god och ändra filbeskrivningen nedanför och försök igen.',
'savefile'                    => 'Spara fil',
'uploadedimage'               => 'laddade upp "[[$1]]"',
'overwroteimage'              => 'laddade upp ny version av "[[$1]]"',
'uploaddisabled'              => 'Uppladdningsfunktionen är avstängd',
'copyuploaddisabled'          => 'Uppladdning med URL avstängt.',
'uploadfromurl-queued'        => 'Din uppladdning har lagts i kö.',
'uploaddisabledtext'          => 'Uppladdning av filer är avstängd.',
'php-uploaddisabledtext'      => 'PHP filuppladdningar är avaktiverade. Kolla inställningarna för file_uploads.',
'uploadscripted'              => 'Denna fil innehåller HTML eller script som felaktigt kan komma att tolkas av webbläsare.',
'uploadvirus'                 => 'Filen innehåller virus! Detaljer: $1',
'upload-source'               => 'Källfil',
'sourcefilename'              => 'Ursprungsfilens namn:',
'sourceurl'                   => 'Källans URL:',
'destfilename'                => 'Nytt filnamn:',
'upload-maxfilesize'          => 'Maximal filstorlek: $1',
'upload-description'          => 'Filbeskrivning',
'upload-options'              => 'Uppladdningsalternativ',
'watchthisupload'             => 'Bevaka den här filen',
'filewasdeleted'              => 'En fil med detta namn har tidigare laddats upp och därefter tagits bort. Du bör kontrollera $1 innan du fortsätter att ladda upp den.',
'upload-wasdeleted'           => "'''Varning: Du håller på att ladda upp en fil som tidigare raderats.'''

Tänk över om det är lämpligt att fortsätta ladda upp denna fil.

Här finns raderingsloggen för denna fil:",
'filename-bad-prefix'         => "Namnet på filen du vill ladda upp börjar med '''\"\$1\"'''. Filnamnet kommer förmodligen direkt från en digitalkamera och beskriver inte filens innehåll. Välj ett annat filnamn som bättre beskriver filen.",
'filename-prefix-blacklist'   => ' #<!-- ändra inte den här raden --> <pre>
# Syntaxen är följande:
#   * All text från ett #-tecken till radens slut är en kommentar
#   * Icke-tomma rader anger typiska prefix för filnamn som används av olika digitalkameror
CIMG # Casio
DSC_ # Nikon
DSCF # Fuji
DSCN # Nikon
DUW # en del mobiltelefoner
IMG # allmänt bildprefix
JD # Jenoptik
MGP # Pentax
PICT # allmänt bildprefix
 #</pre> <!-- ändra inte den här raden -->',
'upload-successful-msg'       => 'Din uppladdade fil finns tillgänglig här: $1',
'upload-failure-subj'         => 'Uppladdningsproblem',
'upload-failure-msg'          => 'Det uppstod ett problem med din uppladdning:

$1',

'upload-proto-error'        => 'Felaktigt protokoll',
'upload-proto-error-text'   => 'Fjärruppladdning kräver URL:ar som börjar med <code>http://</code> eller <code>ftp://</code>.',
'upload-file-error'         => 'Internt fel',
'upload-file-error-text'    => 'Ett internt fel inträffade när en temporär fil skulle skapas på servern. Kontakta en [[Special:ListUsers/sysop|systemadministratör]].',
'upload-misc-error'         => 'Okänt uppladdningsfel',
'upload-misc-error-text'    => 'Ett okänt fel inträffade under uppladdningen.
Kontrollera att URL:en giltig och försök igen.
Om problemet kvarstår, kontakta en [[Special:ListUsers/sysop|administratör]].',
'upload-too-many-redirects' => 'URL-en innehöll för många omdirigeringar',
'upload-unknown-size'       => 'Okänd storlek',
'upload-http-error'         => 'Ett HTTP-fel uppstod: $1',

# img_auth script messages
'img-auth-accessdenied' => 'Åtkomst nekad',
'img-auth-nopathinfo'   => 'Saknad PATH_INFO.
Din server är inte inställd för att ge denna information.
Den kan vara CGI-baserad och stöttar inte img_auth.
Se http://www.mediawiki.org/wiki/Manual:Image_Authorization.',
'img-auth-notindir'     => 'Den önskade sökvägen finns inte i den inställda uppladdningskatalogen.',
'img-auth-badtitle'     => 'Kan inte skapa en giltig titel från "$1"',
'img-auth-nologinnWL'   => 'Du är inte inloggad och "$1" finns inte i vitlistan.',
'img-auth-nofile'       => 'Filen "$1" finns inte.',
'img-auth-isdir'        => 'Du försöker få åtkomst till en katalog "$1".
Endast åtkomst till filer är tillåten.',
'img-auth-streaming'    => 'Strömmar "$1".',
'img-auth-public'       => 'Funktionaliteten i img_auth.php är att skicka filer från en privat wiki.
Denna wiki är konfigurerad som en publik wiki.
För optimal säkerhet, har img_auth.php blivit avaktiverad.',
'img-auth-noread'       => 'Användaren har inte tillåtelse att läsa "$1"',

# HTTP errors
'http-invalid-url'      => 'Ogiltig URL: $1',
'http-invalid-scheme'   => 'URLer med "$1"-formen stöds inte',
'http-request-error'    => 'HTTP-begäran misslyckades på grund av okänt fel.',
'http-read-error'       => 'HTTP-läsfel.',
'http-timed-out'        => 'Time out för HTTP-begäran.',
'http-curl-error'       => 'Fel vid hämtning av URL: $1',
'http-host-unreachable' => 'URL:en kunde inte nås.',
'http-bad-status'       => 'Det uppstod ett problem under HTTP-begäran: $1 $2',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6'       => 'URL:en kunde inte nås',
'upload-curl-error6-text'  => 'Den angivna URL:en kunde inte nås. Kontrollera att den är korrekt och att webbplatsern fungerar.',
'upload-curl-error28'      => 'Timeout för uppladdningen',
'upload-curl-error28-text' => 'Webbplatsen tog för lång tid på sig att svara. Kontrollera att den är uppe och försök igen om en liten stund.',

'license'            => 'Licens:',
'license-header'     => 'Licensiering',
'nolicense'          => 'Ingen angiven',
'license-nopreview'  => '(Förhandsvisning är inte tillgänglig)',
'upload_source_url'  => ' (en giltig URL som är allmänt åtkomlig)',
'upload_source_file' => ' (en fil på din dator)',

# Special:ListFiles
'listfiles-summary'     => 'Den här specialsidan visar alla filer som har laddats upp.
Som standard visas de senast upladdade filerna högst upp i listan.
Genom att klicka på rubrikerna för kolumnerna kan man ändra sorteringsordningen.',
'listfiles_search_for'  => 'Sök efter filnamn:',
'imgfile'               => 'fil',
'listfiles'             => 'Fillista',
'listfiles_date'        => 'Datum',
'listfiles_name'        => 'Namn',
'listfiles_user'        => 'Användare',
'listfiles_size'        => 'Storlek (byte)',
'listfiles_description' => 'Beskrivning',
'listfiles_count'       => 'Versioner',

# File description page
'file-anchor-link'          => 'Fil',
'filehist'                  => 'Filhistorik',
'filehist-help'             => 'Klicka på ett datum/klockslag för att se filen som den såg ut då.',
'filehist-deleteall'        => 'radera alla',
'filehist-deleteone'        => 'radera version',
'filehist-revert'           => 'återställ',
'filehist-current'          => 'nuvarande',
'filehist-datetime'         => 'Datum/Tid',
'filehist-thumb'            => 'Miniatyrbild',
'filehist-thumbtext'        => 'Miniatyrbild för versionen från den $1',
'filehist-nothumb'          => 'Ingen miniatyrbild',
'filehist-user'             => 'Användare',
'filehist-dimensions'       => 'Dimensioner',
'filehist-filesize'         => 'Filstorlek',
'filehist-comment'          => 'Kommentar',
'filehist-missing'          => 'Fil saknas',
'imagelinks'                => 'Fillänkar',
'linkstoimage'              => 'Följande {{PLURAL:$1|sida|$1 sidor}} länkar till den här filen:',
'linkstoimage-more'         => 'Mer är {{PLURAL:$1|en sida|$1 sidor}} länkar till den här filen.
Följande lista visar bara {{PLURAL:$1|den första sidan|de $1 första sidorna}} som länkar till filen.
Det finns en [[Special:WhatLinksHere/$2|fullständig lista]].',
'nolinkstoimage'            => 'Inga sidor länkar till den här filen.',
'morelinkstoimage'          => 'Visa [[Special:WhatLinksHere/$1|fler länkar]] till den här filen.',
'redirectstofile'           => 'Följande {{PLURAL:$1|fil är en omdirigering|filer är omdirigeringar}} till den här filen:',
'duplicatesoffile'          => 'Följande {{PLURAL:$1|fil är en dubblett|filer är dubbletter}} till den här filen ([[Special:FileDuplicateSearch/$2|mer detaljer]]):',
'sharedupload'              => 'Den här filen är från $1 och kan användas av andra projekt.',
'sharedupload-desc-there'   => 'Den här filen är från $1 och kan användas av andra projekt.
Var god se [$2 filbeskrivningssidan] för mer information.',
'sharedupload-desc-here'    => 'Den här filen är från $1 och kan användas av andra projekt.
Beskrivningen på dess [$2 filbeskrivningssida] visas nedan.',
'filepage-nofile'           => 'Det finns ingen fil med det här namnet.',
'filepage-nofile-link'      => 'Det finns ingen fil med det här namnet, men du kan [$1 ladda upp den].',
'uploadnewversion-linktext' => 'Ladda upp en ny version av denna fil',
'shared-repo-from'          => 'från $1',
'shared-repo'               => 'en gemensam filförvaring',

# File reversion
'filerevert'                => 'Återställ $1',
'filerevert-legend'         => 'Återställ fil',
'filerevert-intro'          => "Du återställer '''[[Media:$1|$1]]''' till [$4 versionen från $2 kl. $3].",
'filerevert-comment'        => 'Anledning:',
'filerevert-defaultcomment' => 'Återställer till versionen från $1 kl. $2.',
'filerevert-submit'         => 'Återställ',
'filerevert-success'        => "'''[[Media:$1|$1]]''' har återställts till [$4 versionen från $2 kl. $3].",
'filerevert-badversion'     => 'Det finns ingen tidigare version av filen från den angivna tidpunkten.',

# File deletion
'filedelete'                  => 'Radera $1',
'filedelete-legend'           => 'Radera fil',
'filedelete-intro'            => "Du håller på att radera filen '''[[Media:$1|$1]]''' tillsammans med hela dess historik.",
'filedelete-intro-old'        => "Du håller på att radera versionen av '''[[Media:$1|$1]]''' från [$4 $2 kl. $3].",
'filedelete-comment'          => 'Anledning:',
'filedelete-submit'           => 'Radera',
'filedelete-success'          => "'''$1''' har raderats.",
'filedelete-success-old'      => "Versionen av '''[[Media:$1|$1]]''' från $2 kl. $3 har raderats.",
'filedelete-nofile'           => "Filen '''$1''' finns inte.",
'filedelete-nofile-old'       => "Den versionen av '''$1''' kan inte raderas eftersom den inte finns.",
'filedelete-otherreason'      => 'Annan/ytterligare anledning:',
'filedelete-reason-otherlist' => 'Annan anledning',
'filedelete-reason-dropdown'  => '*Vanliga anledningar till radering
** Upphovsrättsbrott
** Dubblettfil',
'filedelete-edit-reasonlist'  => 'Redigera anledningar för radering',
'filedelete-maintenance'      => 'Radering och återställning av filer tillfälligt avaktiverat under underhåll.',

# MIME search
'mimesearch'         => 'MIME-sökning',
'mimesearch-summary' => 'På den här sidan kan du söka efter filer via dess MIME-typ. Input: contenttype/subtype, t.ex. <tt>image/jpeg</tt>.',
'mimetype'           => 'MIME-typ:',
'download'           => 'ladda ner',

# Unwatched pages
'unwatchedpages' => 'Obevakade sidor',

# List redirects
'listredirects' => 'Lista över omdirigeringar',

# Unused templates
'unusedtemplates'     => 'Oanvända mallar',
'unusedtemplatestext' => 'Denna sida listar alla sidor i {{ns:template}}-namnrymden som inte inkluderas på någon annan sida.
Innan mallarna raderas, kontrollera att det inte finns andra länkar till dem.',
'unusedtemplateswlh'  => 'andra länkar',

# Random page
'randompage'         => 'Slumpsida',
'randompage-nopages' => 'Det finns inte några sidor i följande {{PLURAL:$2|namnrymd|namnrymder}}: $1.',

# Random redirect
'randomredirect'         => 'Slumpvald omdirigering',
'randomredirect-nopages' => 'Det finns inte några omdirigeringar i namnrymden "$1".',

# Statistics
'statistics'                   => 'Statistik',
'statistics-header-pages'      => 'Sidstatistik',
'statistics-header-edits'      => 'Redigeringsstatistik',
'statistics-header-views'      => 'Sidvisningsstatistik',
'statistics-header-users'      => 'Användarstatistik',
'statistics-header-hooks'      => 'Övrig statistik',
'statistics-articles'          => 'Sidor med innehåll',
'statistics-pages'             => 'Sidor',
'statistics-pages-desc'        => 'Alla sidor i wikin, inklusive diskussionssidor, omdirigeringar, m.m.',
'statistics-files'             => 'Uppladdade filer',
'statistics-edits'             => 'Sidredigeringar sedan {{SITENAME}} startades',
'statistics-edits-average'     => 'Redigeringar per sida i genomsnitt',
'statistics-views-total'       => 'Totalt antal sidvisningar',
'statistics-views-peredit'     => 'Antal sidvisningar per redigering',
'statistics-users'             => 'Registrerade [[Special:ListUsers|användare]]',
'statistics-users-active'      => 'Aktiva användare',
'statistics-users-active-desc' => 'Användare som utfört någon åtgärd under {{PLURAL:$1|det senaste dygnet|de senaste $1 dagarna}}',
'statistics-mostpopular'       => 'Mest besökta sidor',

'disambiguations'      => 'Sidor som länkar till förgreningssidor',
'disambiguationspage'  => 'Template:Förgrening',
'disambiguations-text' => "Följande sidor länkar till ''förgreningssidor''.
Länkarna bör troligtvis ändras så att de länkar till en artikel istället.<br />
En sida anses vara en förgreningssida om den inkluderar en mall som länkas till från [[MediaWiki:Disambiguationspage]].",

'doubleredirects'            => 'Dubbla omdirigeringar',
'doubleredirectstext'        => 'Det här är en lista över sidor som dirigerar om till andra omdirigeringssidor. Varje rad innehåller länkar till den första och andra omdirigeringsidan, samt till målet för den andra omdirigeringen. Målet för den andra omdirigeringen är ofta den "riktiga" sidan, som den första omdirigeringen egentligen ska leda till.
<s>Stryk över</s> poster som har åtgärdats.',
'double-redirect-fixed-move' => '[[$1]] har flyttats, och är nu en omdirigering till [[$2]]',
'double-redirect-fixer'      => 'Omdirigeringsrättaren',

'brokenredirects'        => 'Trasiga omdirigeringar',
'brokenredirectstext'    => 'Följande omdirigerar länkar till ej existerande sidor:',
'brokenredirects-edit'   => 'redigera',
'brokenredirects-delete' => 'radera',

'withoutinterwiki'         => 'Sidor utan språklänkar',
'withoutinterwiki-summary' => 'Följande sidor innehåller inte några länkar till andra språkversioner.',
'withoutinterwiki-legend'  => 'Prefix',
'withoutinterwiki-submit'  => 'Visa',

'fewestrevisions' => 'Sidor med minst antal ändringar',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|byte|byte}}',
'ncategories'             => '$1 {{PLURAL:$1|kategori|kategorier}}',
'nlinks'                  => '$1 {{PLURAL:$1|länk|länkar}}',
'nmembers'                => '$1 {{PLURAL:$1|medlem|medlemmar}}',
'nrevisions'              => '$1 {{PLURAL:$1|version|versioner}}',
'nviews'                  => '$1 {{PLURAL:$1|visning|visningar}}',
'specialpage-empty'       => 'Den här sidan är tom.',
'lonelypages'             => 'Föräldralösa sidor',
'lonelypagestext'         => 'Följande sidor länkas inte till från någon annan sida på {{SITENAME}}.',
'uncategorizedpages'      => 'Ej kategoriserade sidor',
'uncategorizedcategories' => 'Ej kategoriserade kategorier',
'uncategorizedimages'     => 'Filer utan kategori',
'uncategorizedtemplates'  => 'Ej kategoriserade mallar',
'unusedcategories'        => 'Tomma kategorier',
'unusedimages'            => 'Oanvända filer',
'popularpages'            => 'Populära sidor',
'wantedcategories'        => 'Önskade kategorier',
'wantedpages'             => 'Önskade sidor',
'wantedpages-badtitle'    => 'Ogiltig titel bland resultaten: $1',
'wantedfiles'             => 'Önskade filer',
'wantedtemplates'         => 'Önskade mallar',
'mostlinked'              => 'Sidor med flest länkar till sig',
'mostlinkedcategories'    => 'Kategorier med flest länkar till sig',
'mostlinkedtemplates'     => 'Mest använda mallar',
'mostcategories'          => 'Sidor med flest kategorier',
'mostimages'              => 'Filer med flest länkar till sig',
'mostrevisions'           => 'Sidor med flest ändringar',
'prefixindex'             => 'Alla sidor med prefix',
'shortpages'              => 'Korta sidor',
'longpages'               => 'Långa sidor',
'deadendpages'            => 'Sidor utan länkar',
'deadendpagestext'        => 'Följande sidor saknar länkar till andra sidor på {{SITENAME}}.',
'protectedpages'          => 'Skyddade sidor',
'protectedpages-indef'    => 'Endast skydd på obestämd tid',
'protectedpages-cascade'  => 'Endast kaskaderande skydd',
'protectedpagestext'      => 'Följande sidor är skyddade mot redigering eller flyttning.',
'protectedpagesempty'     => 'Inga sidor är skyddade under de villkoren.',
'protectedtitles'         => 'Skyddade titlar',
'protectedtitlestext'     => 'Följande sidtitlar är skyddade från att skapas',
'protectedtitlesempty'    => 'Just nu finns inga skyddade sidtitlar med de parametrarna.',
'listusers'               => 'Användarlista',
'listusers-editsonly'     => 'Visa endast användare som redigerat',
'listusers-creationsort'  => 'Sortera efter datum skapat',
'usereditcount'           => '$1 {{PLURAL:$1|redigering|redigeringar}}',
'usercreated'             => 'Skapat $1 $2',
'newpages'                => 'Nya sidor',
'newpages-username'       => 'Användare:',
'ancientpages'            => 'Äldsta sidorna',
'move'                    => 'Flytta',
'movethispage'            => 'Flytta denna sida',
'unusedimagestext'        => 'Följande filer existerar men är inte inlagda i någon sida.
Lägg märke till att andra webbplatser kan länka till en fil med en direkt URL. Filer kan därför  användas aktivt trots att de listas här.',
'unusedcategoriestext'    => 'Följande kategorier finns men innehåller inga sidor eller underkategorier.',
'notargettitle'           => 'Inget mål',
'notargettext'            => 'Du har inte angivit någon sida eller användare att utföra denna funktion på.',
'nopagetitle'             => 'Målsidan finns inte',
'nopagetext'              => 'Sidan som du vill flytta finns inte.',
'pager-newer-n'           => '{{PLURAL:$1|1 nyare|$1 nyare}}',
'pager-older-n'           => '{{PLURAL:$1|1 äldre|$1 äldre}}',
'suppress'                => 'Censur',

# Book sources
'booksources'               => 'Bokkällor',
'booksources-search-legend' => 'Sök efter bokkällor',
'booksources-go'            => 'Sök',
'booksources-text'          => 'Nedan följer en lista över länkar till webbplatser som säljer nya och begagnade böcker, och som kanske har ytterligare information om de böcker du söker.',
'booksources-invalid-isbn'  => 'Det angivna ISBN-numret verkar inte vara giltigt. Kontrollera källan för eventuella fel.',

# Special:Log
'specialloguserlabel'  => 'Användare:',
'speciallogtitlelabel' => 'Titel:',
'log'                  => 'Loggar',
'all-logs-page'        => 'Alla publika loggar',
'alllogstext'          => 'Kombinerad visning av alla tillgängliga loggar för {{SITENAME}}.
Du kan avgränsa sökningen och få färre träffar genom att ange typ av logg, användarnamn (skiftlägeskänsligt), eller berörd sida (också skiftlägeskänsligt).',
'logempty'             => 'Inga matchande träffar i loggen.',
'log-title-wildcard'   => 'Sök efter sidtitlar som börjar med texten',

# Special:AllPages
'allpages'          => 'Alla sidor',
'alphaindexline'    => '$1 till $2',
'nextpage'          => 'Nästa sida ($1)',
'prevpage'          => 'Föregående sida ($1)',
'allpagesfrom'      => 'Visa sidor från och med:',
'allpagesto'        => 'Visa sidor till och med:',
'allarticles'       => 'Alla sidor',
'allinnamespace'    => 'Alla sidor (i namnrymden $1)',
'allnotinnamespace' => 'Alla sidor (inte i namnrymden $1)',
'allpagesprev'      => 'Föregående',
'allpagesnext'      => 'Nästa',
'allpagessubmit'    => 'Visa',
'allpagesprefix'    => 'Visa sidor med prefixet:',
'allpagesbadtitle'  => 'Den sökta sidtiteln var ogiltig eller så innehöll den ett prefix för annan språkversion eller interwiki-prefix. Titeln kan innehålla bokstäver som inte är tillåtna i sidtitlar.',
'allpages-bad-ns'   => 'Namnrymden "$1" finns inte på {{SITENAME}}.',

# Special:Categories
'categories'                    => 'Kategorier',
'categoriespagetext'            => 'Följande {{PLURAL:$1|kategori|kategorier}} innehåller sidor eller media.
[[Special:UnusedCategories|Oanvända kategorier]] visas inte här.
Se även [[Special:WantedCategories|önskade kategorier]].',
'categoriesfrom'                => 'Visa kategorier från och med:',
'special-categories-sort-count' => 'sortera efter storlek',
'special-categories-sort-abc'   => 'sortera alfabetiskt',

# Special:DeletedContributions
'deletedcontributions'             => 'Raderade användarbidrag',
'deletedcontributions-title'       => 'Raderade användarbidrag',
'sp-deletedcontributions-contribs' => 'bidrag',

# Special:LinkSearch
'linksearch'       => 'Externa länkar',
'linksearch-pat'   => 'Sökmönster:',
'linksearch-ns'    => 'Namnrymd:',
'linksearch-ok'    => 'Sök',
'linksearch-text'  => 'Jokertecken (wildcards) som t ex "*.wikipedia.org" kan användas.<br />
Stödda protokoll: <tt>$1</tt>',
'linksearch-line'  => '$1 länkas från $2',
'linksearch-error' => 'Jokertecken kan bara användas i början av domännamnet.',

# Special:ListUsers
'listusersfrom'      => 'Visa användare från och med:',
'listusers-submit'   => 'Visa',
'listusers-noresult' => 'Ingen användare hittades.',
'listusers-blocked'  => '(blockerad)',

# Special:ActiveUsers
'activeusers'            => 'Lista över aktiva användare',
'activeusers-intro'      => 'Detta är en lista på användare som har haft någon form av aktivitet inom de senaste $1 {{PLURAL:$1|dagen|dagarna}}.',
'activeusers-count'      => '$1 {{PLURAL:$1|redigering|redigeringar}} {{PLURAL:$3|det senaste dygnet|de senaste $3 dygnen}}',
'activeusers-from'       => 'Visa användare från och med:',
'activeusers-hidebots'   => 'Göm botar',
'activeusers-hidesysops' => 'Dölj administratörer',
'activeusers-noresult'   => 'Inga användare funna.',

# Special:Log/newusers
'newuserlogpage'              => 'Logg över nya användare',
'newuserlogpagetext'          => 'Detta är en logg över skapade användarkonton.',
'newuserlog-byemail'          => 'lösenord skickat med e-post',
'newuserlog-create-entry'     => 'Ny användare',
'newuserlog-create2-entry'    => 'skapade nytt användarkonto $1',
'newuserlog-autocreate-entry' => 'Konto skapades automatiskt',

# Special:ListGroupRights
'listgrouprights'                      => 'Behörigheter för användargrupper',
'listgrouprights-summary'              => 'Följande lista visar vilka användargrupper som är definierade på den här wikin och vilka behörigheter grupperna har.
Det kan finnas [[{{MediaWiki:Listgrouprights-helppage}}|ytterligare information]] om de olika behörigheterna.',
'listgrouprights-key'                  => '* <span class="listgrouprights-granted">Given rättighet</span>
* <span class="listgrouprights-revoked">Tillbakatagen rättighet</span>',
'listgrouprights-group'                => 'Grupp',
'listgrouprights-rights'               => 'Behörigheter',
'listgrouprights-helppage'             => 'Help:Gruppbehörigheter',
'listgrouprights-members'              => '(lista över medlemmar)',
'listgrouprights-addgroup'             => 'Kan lägga till {{PLURAL:$2|gruppen|grupperna}}: $1',
'listgrouprights-removegroup'          => 'Kan ta bort {{PLURAL:$2|gruppen|grupperna}}: $1',
'listgrouprights-addgroup-all'         => 'Kan lägga till alla användargrupper',
'listgrouprights-removegroup-all'      => 'Kan ta bort alla användargrupper',
'listgrouprights-addgroup-self'        => 'Kan lägga till {{PLURAL:$2|gruppen|grupperna}} till sitt eget konto: $1',
'listgrouprights-removegroup-self'     => 'Kan ta bort {{PLURAL:$2|gruppen|grupperna}} från sitt eget konto: $1',
'listgrouprights-addgroup-self-all'    => 'Kan lägga till alla grupperna till sitt eget konto',
'listgrouprights-removegroup-self-all' => 'Kan ta bort alla grupperna från sitt eget konto',

# E-mail user
'mailnologin'          => 'Ingen adress att skicka till',
'mailnologintext'      => 'För att kunna skicka e-post till andra användare, måste du vara [[Special:UserLogin|inloggad]] och ha angivit en korrekt e-postadress i dina [[Special:Preferences|användarinställningar]].',
'emailuser'            => 'Skicka e-post till den här användaren',
'emailpage'            => 'Skicka e-post till användare',
'emailpagetext'        => 'Du kan använda det här formuläret för att skicka e-post till den här användaren.
Den e-postadress du har angivit i [[Special:Preferences|dina användarinställningar]] kommer att visas som "Från"-adress i meddelandet, så att mottagaren har möjlighet att svara direkt till dig.',
'usermailererror'      => 'Fel i hanteringen av mail:',
'defemailsubject'      => '{{SITENAME}} e-post',
'usermaildisabled'     => 'Användar-epost avaktiverat',
'usermaildisabledtext' => 'Du kan inte skicka e-post till andra användare på den här wikin',
'noemailtitle'         => 'Ingen e-postadress',
'noemailtext'          => 'Den här användaren har inte angivit en giltig e-postadress.',
'nowikiemailtitle'     => 'Ingen e-post tillåten',
'nowikiemailtext'      => 'Den här användaren har valt att inte motta e-post från andra användare.',
'email-legend'         => 'Skicka ett mail till en annan användare på {{SITENAME}}',
'emailfrom'            => 'Från:',
'emailto'              => 'Till:',
'emailsubject'         => 'Ämne:',
'emailmessage'         => 'Meddelande:',
'emailsend'            => 'Skicka',
'emailccme'            => 'Skicka en kopia av meddelandet till mig.',
'emailccsubject'       => 'Kopia av ditt meddelande till $1: $2',
'emailsent'            => 'E-post har nu skickats',
'emailsenttext'        => 'Din e-post har skickats.',
'emailuserfooter'      => 'Detta e-brev skickades av $1 till $2 genom "Skicka e-post"-funktionen på {{SITENAME}}.',

# User Messenger
'usermessage-summary' => 'Lämnar systemmeddelande.',
'usermessage-editor'  => 'Systemmeddelare',

# Watchlist
'watchlist'            => 'Bevakningslista',
'mywatchlist'          => 'Min bevakningslista',
'watchlistfor'         => "(för '''$1''')",
'nowatchlist'          => 'Du har inga sidor i din bevakningslista.',
'watchlistanontext'    => 'Du måste $1 för att se eller redigera din bevakningslista.',
'watchnologin'         => 'Inte inloggad',
'watchnologintext'     => 'Du måste vara [[Special:UserLogin|inloggad]] för att kunna ändra din bevakningslista.',
'addedwatch'           => 'Tillagd på bevakningslistan',
'addedwatchtext'       => "Sidan \"[[:\$1]]\" har lagts till på din [[Special:Watchlist|bevakningslista]].
Framtida ändringar av den här sidan och dess diskussionssida kommer att listas där, och sidan kommer att markeras med '''fetstil''' i [[Special:RecentChanges|listan över de senaste ändringarna]] för att lättare kunna hittas.",
'removedwatch'         => 'Borttagen från bevakningslista',
'removedwatchtext'     => 'Sidan "[[:$1]]" har tagits bort från [[Special:Watchlist|din bevakningslista]].',
'watch'                => 'Bevaka',
'watchthispage'        => 'Bevaka denna sida',
'unwatch'              => 'Avbevaka',
'unwatchthispage'      => 'Sluta bevaka',
'notanarticle'         => 'Inte en artikel',
'notvisiblerev'        => 'Sidversionen har raderats',
'watchnochange'        => 'Inga av dina bevakade sidor har ändrats inom den visade tidsperioden.',
'watchlist-details'    => 'Du har $1 {{PLURAL:$1|sida|sidor}} på din bevakningslista (diskussionssidor är inte medräknade).',
'wlheader-enotif'      => '* Bekräftelse per e-post är aktiverad.',
'wlheader-showupdated' => "* Sidor som har ändrats sedan ditt senaste besök visas i '''fetstil.'''",
'watchmethod-recent'   => 'letar efter bevakade sidor bland senaste ändringar',
'watchmethod-list'     => 'letar efter nyligen gjorda ändringar bland bevakade sidor',
'watchlistcontains'    => 'Din bevakningslista innehåller $1 {{PLURAL:$1|sida|sidor}}.',
'iteminvalidname'      => "Problem med sidan '$1', ogiltigt namn...",
'wlnote'               => "Nedan finns {{PLURAL:$1|den senaste ändringen|de senaste '''$1''' ändringarna}} under {{PLURAL:$2|den senaste timmen|de senaste '''$2''' timmarna}}.",
'wlshowlast'           => 'Visa senaste $1 timmarna $2 dygnen $3',
'watchlist-options'    => 'Alternativ för bevakningslistan',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'Bevakar...',
'unwatching' => 'Avbevakar...',

'enotif_mailer'                => '{{SITENAME}}s system för att få meddelanden om förändringar per e-post',
'enotif_reset'                 => 'Markera alla sidor som besökta',
'enotif_newpagetext'           => 'Detta är en ny sida.',
'enotif_impersonal_salutation' => '{{SITENAME}}användare',
'changed'                      => 'ändrad',
'created'                      => 'skapad',
'enotif_subject'               => '{{SITENAME}}-sidan $PAGETITLE har blivit $CHANGEDORCREATED av $PAGEEDITOR',
'enotif_lastvisited'           => 'På $1 återfinner du alla ändringar sedan ditt senaste besök.',
'enotif_lastdiff'              => 'Se denna ändring på $1',
'enotif_anon_editor'           => 'anonym användare $1',
'enotif_body'                  => '$WATCHINGUSERNAME,

{{SITENAME}}-sidan $PAGETITLE har blivit $CHANGEDORCREATED $PAGEEDITDATE av $PAGEEDITOR; se $PAGETITLE_URL för den nuvarande versionen.

$NEWPAGE

Angiven sammanfattning av redigeringen: $PAGESUMMARY $PAGEMINOREDIT

Kontakta användaren:
e-post: $PAGEEDITOR_EMAIL
wiki: $PAGEEDITOR_WIKI

Såvida du inte besöker sidan, kommer du inte att få flera meddelanden om ändringar av sidan.
Du kan också ta bort flaggan för meddelanden om ändringar på alla sidor i din bevakningslista.

             Hälsningar från {{SITENAME}}s meddelandesystem

--
För att ändra inställningarna i din bevakningslista, besök
{{fullurl:{{#special:Watchlist}}/edit}}

För att radera sidan från din bevakningslista, besök
$UNWATCHURL

Feedback och ytterligare hjälp:
{{fullurl:{{MediaWiki:Helppage}}}}',

# Delete
'deletepage'             => 'Ta bort sida',
'confirm'                => 'Bekräfta',
'excontent'              => "Före radering: '$1'",
'excontentauthor'        => "innehållet var: '$1' (den enda som skrivit var '[[Special:Contributions/$2|$2]]')",
'exbeforeblank'          => "innehåll före tömning var: '$1'",
'exblank'                => 'sidan var tom',
'delete-confirm'         => 'Radera "$1"',
'delete-legend'          => 'Radera',
'historywarning'         => "'''Varning:''' Sidan du håller på att radera har en historik med ungefär $1 {{PLURAL:$1|version|versioner}}:",
'confirmdeletetext'      => 'Du håller på att ta bort en sida med hela dess historik.
Bekräfta att du förstår vad du håller på med och vilka konsekvenser detta leder till, och att du följer [[{{MediaWiki:Policy-url}}|riktlinjerna]].',
'actioncomplete'         => 'Genomfört',
'actionfailed'           => 'Handlingen misslyckades',
'deletedtext'            => '"<nowiki>$1</nowiki>" har tagits bort.
Se $2 för noteringar om de senaste raderingarna.',
'deletedarticle'         => 'raderade "[[$1]]"',
'suppressedarticle'      => 'undanhöll "[[$1]]"',
'dellogpage'             => 'Raderingslogg',
'dellogpagetext'         => 'Nedan listas de senaste raderingarna.',
'deletionlog'            => 'raderingsloggen',
'reverted'               => 'Återgått till tidigare version',
'deletecomment'          => 'Anledning till borttagning:',
'deleteotherreason'      => 'Annan/ytterligare anledning:',
'deletereasonotherlist'  => 'Annan anledning',
'deletereason-dropdown'  => '*Vanliga anledningar till radering
** Författarens begäran
** Upphovsrättsbrott
** Vandalism',
'delete-edit-reasonlist' => 'Redigera anledningar för radering',
'delete-toobig'          => 'Denna sida har en lång redigeringshistorik med mer än $1 {{PLURAL:$1|sidversion|sidversioner}}. Borttagning av sådana sidor har begränsats för att förhindra oavsiktliga driftstörningar på {{SITENAME}}.',
'delete-warning-toobig'  => 'Denna sida har en lång redigeringshistorik med mer än $1 {{PLURAL:$1|sidversion|sidversioner}}. Att radera sidan kan skapa problem med hanteringen av databasen på {{SITENAME}}; var försiktig.',

# Rollback
'rollback'          => 'Rulla tillbaka ändringar',
'rollback_short'    => 'Återställning',
'rollbacklink'      => 'rulla tillbaka',
'rollbackfailed'    => 'Tillbakarullning misslyckades',
'cantrollback'      => 'Det gick inte att rulla tillbaka, då sidan endast redigerats av en användare.',
'alreadyrolled'     => 'Det gick inte att rulla tillbaka den senaste redigeringen av [[User:$2|$2]] ([[User talk:$2|diskussion]]{{int:pipe-separator}}[[Special:Contributions/$2|{{int:contribslink}}]]) på sidan [[:$1|$1]]. Någon annan har redan rullat tillbaka eller redigerat sidan.

Sidan ändrades senast av [[User:$3|$3]] ([[User talk:$3|diskussion]]{{int:pipe-separator}}[[Special:Contributions/$2|{{int:contribslink}}]]).',
'editcomment'       => "Redigeringskommentaren var: \"''\$1''\".",
'revertpage'        => 'Återställde redigeringar av  [[Special:Contributions/$2|$2]] ([[User talk:$2|användardiskussion]]) till senaste versionen av [[User:$1|$1]]',
'revertpage-nouser' => 'Återställda redigeringar av (användarnamn borttaget) till senaste version av [[User:$1|$1]]',
'rollback-success'  => 'Återställde ändringar av $1;
ändrade tillbaka till senaste version av $2.',

# Edit tokens
'sessionfailure-title' => 'Sessionsfel',
'sessionfailure'       => 'Något med din session som inloggad är på tok. Din begärda åtgärd har avbrutits, för att förhindra att någon kapar din session. Klicka på "Tillbaka" i din webbläsare och ladda om den sida du kom ifrån. Försök sedan igen.',

# Protect
'protectlogpage'              => 'Skrivskyddslogg',
'protectlogtext'              => 'Detta är en lista över applicerande och borttagande av skrivskydd.',
'protectedarticle'            => 'skyddade [[$1]]',
'modifiedarticleprotection'   => 'ändrade skyddsnivån för "[[$1]]"',
'unprotectedarticle'          => 'tog bort skydd av "[[$1]]"',
'movedarticleprotection'      => 'flyttade skyddsinställningar från "[[$2]]" till "[[$1]]"',
'protect-title'               => 'Skyddsinställningar för "$1"',
'prot_1movedto2'              => 'flyttade [[$1]] till [[$2]]',
'protect-legend'              => 'Bekräfta skrivskydd av sida',
'protectcomment'              => 'Anledning:',
'protectexpiry'               => 'Varaktighet:',
'protect_expiry_invalid'      => 'Ogiltig varaktighetstid.',
'protect_expiry_old'          => 'Den angivna varaktighetentiden har redan passerats.',
'protect-unchain-permissions' => 'Lås upp fler skyddsalternativ',
'protect-text'                => "Här kan du se och ändra skyddsnivån av sidan '''<nowiki>$1</nowiki>'''.",
'protect-locked-blocked'      => "Du kan inte ändra sidors skydd medan du är blockerad.
Här kan du se gällande skyddsinställninger för sidan '''$1''':",
'protect-locked-dblock'       => "Skrivskydd kan inte ändras då databasen är låst.
Nuvarande skrivskyddsinställning för sidan '''$1''' är:",
'protect-locked-access'       => "Ditt konto har inte behörighet att ändra skrivskydd på sidor.
Nuvarande skrivskyddsinställning för sidan '''$1''' är:",
'protect-cascadeon'           => 'Den här sidan är skrivskyddad eftersom den inkluderas på följande {{PLURAL:$1|sida|sidor}} som har ett kaskaderande skydd.
Du kan ändra skyddet av den här sidan, men det påverkar inte det kaskaderande skyddet.',
'protect-default'             => 'Tillåt alla användare',
'protect-fallback'            => 'Kräver "$1"-behörighet',
'protect-level-autoconfirmed' => 'Blockera nya och oregistrerade användare',
'protect-level-sysop'         => 'Enbart administratörer',
'protect-summary-cascade'     => 'kaskaderande',
'protect-expiring'            => 'upphör den $1 (UTC)',
'protect-expiry-indefinite'   => 'på obestämd tid',
'protect-cascade'             => 'Skydda sidor som är inkluderade i den här sidan (kaskaderande skydd)',
'protect-cantedit'            => 'Du kan inte ändra skyddsnivån för den här sidan, eftersom du inte har behörighet att redigera den.',
'protect-othertime'           => 'Annan tidsperiod:',
'protect-othertime-op'        => 'annan tidsperiod',
'protect-existing-expiry'     => 'Gällande varaktighet: $2, kl. $3',
'protect-otherreason'         => 'Annan/ytterligare anledning:',
'protect-otherreason-op'      => 'Annan anledning',
'protect-dropdown'            => '*Vanliga anledningar för skrivskydd
** Upprepad vandalisering
** Upprepad spam
** Redigeringskrig
** Sida med många besökare',
'protect-edit-reasonlist'     => 'Redigera skyddsanledningar',
'protect-expiry-options'      => '1 timme:1 hour,1 dag:1 day,1 vecka:1 week,2 veckor:2 weeks,1 månad:1 month,3 månader:3 months,6 månader:6 months,1 år:1 year,oändlig:infinite',
'restriction-type'            => 'Typ av skydd:',
'restriction-level'           => 'Skyddsnivå:',
'minimum-size'                => 'Minsta storlek',
'maximum-size'                => 'Största storlek:',
'pagesize'                    => '(byte)',

# Restrictions (nouns)
'restriction-edit'   => 'Redigering',
'restriction-move'   => 'Flyttning',
'restriction-create' => 'Skapa sidan',
'restriction-upload' => 'Uppladdning',

# Restriction levels
'restriction-level-sysop'         => 'helt låst',
'restriction-level-autoconfirmed' => 'halvlåst',
'restriction-level-all'           => 'alla nivåer',

# Undelete
'undelete'                     => 'Visa raderade sidor',
'undeletepage'                 => 'Visa och återställ borttagna sidor',
'undeletepagetitle'            => "'''Härunder visas en lista över raderade versioner av [[:$1|$1]]'''.",
'viewdeletedpage'              => 'Visa raderade sidor',
'undeletepagetext'             => 'Följande {{PLURAL:$1|sida har blivit raderad|$1 sidor har blivit raderade}} men finns fortfarande i arkivet och kan återställas.
Arkivet kan ibland rensas ut.',
'undelete-fieldset-title'      => 'Återställ sidversioner',
'undeleteextrahelp'            => "För att återställa sidans hela historik, lämna alla rutor oifyllda och klicka '''''Återställ'''''.
För att göra en selektiv återställning, kryssa i de rutor som hör till de versioner som ska återställas, och klicka '''''Återställ'''''.
Om du klickar '''''Rensa''''' så töms kommentarfältet och alla kryssrutorna.",
'undeleterevisions'            => '$1 {{PLURAL:$1|version|versioner}} arkiverade',
'undeletehistory'              => 'Om du återställer sidan kommer alla tidigare versioner att återfinnas i versionshistoriken.
Om en ny sida med samma namn har skapats sedan sidan raderades, kommer den återskapade historiken automatiskt att återfinnas i den äldre historiken.',
'undeleterevdel'               => 'Återställningen kan inte utföras om den resulterar i att den senaste versionen är delvis borttagen.
I sådana fall måste du se till att den senaste raderade versionen inte är ikryssad, eller att den inte är dold.',
'undeletehistorynoadmin'       => 'Den här sidan har blivit raderad. Anledningen till detta anges i sammanfattningen nedan, tillsammans med uppgifter om de användare som redigerat sidan innan den raderades. Enbart administratörerna har tillgång till den raderade texten.',
'undelete-revision'            => 'Raderad version av $1 (från den $4 kl. $5) av $3.',
'undeleterevision-missing'     => 'Versionen finns inte eller är felaktig. Versionen kan ha återställts eller tagits bort från arkivet, du kan också ha följt en felaktig länk.',
'undelete-nodiff'              => 'Ingen tidigare version hittades.',
'undeletebtn'                  => 'Återställ',
'undeletelink'                 => 'visa/återställ',
'undeleteviewlink'             => 'visa',
'undeletereset'                => 'Rensa',
'undeleteinvert'               => 'Invertera urval',
'undeletecomment'              => 'Anledning:',
'undeletedarticle'             => 'återställde "[[$1]]"',
'undeletedrevisions'           => '{{PLURAL:$1|en version återställd|$1 versioner återställda}}',
'undeletedrevisions-files'     => '$1 {{PLURAL:$1|version|versioner}} och $2 {{PLURAL:$2|fil|filer}} återställda',
'undeletedfiles'               => '{{PLURAL:$1|en fil återställd|$1 filer återställda}}',
'cannotundelete'               => 'Återställning misslyckades; kanske någon redan har återställt sidan.',
'undeletedpage'                => "'''$1 har återställts'''

Se [[Special:Log/delete|raderingsloggen]] för en förteckning över de senaste raderingarna och återställningarna.",
'undelete-header'              => 'Se [[Special:Log/delete|raderingsloggen]] för nyligen raderade sidor.',
'undelete-search-box'          => 'Sök efter raderade sidor',
'undelete-search-prefix'       => 'Sidor som börjar med:',
'undelete-search-submit'       => 'Sök',
'undelete-no-results'          => 'Inga sidor med sådan titel hittades i arkivet över raderade sidor.',
'undelete-filename-mismatch'   => 'Filversionen med tidsstämpeln $1 kan inte återställas: filnamnet stämmer inte.',
'undelete-bad-store-key'       => 'Filversionen med tidsstämpeln $1 kan inte återställas: filen saknades före radering.',
'undelete-cleanup-error'       => 'Fel vid radering av den oanvända arkivfilen "$1".',
'undelete-missing-filearchive' => 'Filen med arkiv-ID $1 kunde inte återställas eftersom den inte finns i databasen. Filen kanske redan har återställts.',
'undelete-error-short'         => 'Fel vid filåterställning: $1',
'undelete-error-long'          => 'Fel inträffade när vid återställning av filen:

$1',
'undelete-show-file-confirm'   => 'Är du säker på att du vill visa en raderad version av filen "<nowiki>$1</nowiki>" från den $2 kl $3?',
'undelete-show-file-submit'    => 'Ja',

# Namespace form on various pages
'namespace'      => 'Namnrymd:',
'invert'         => 'Uteslut vald namnrymd',
'blanknamespace' => '(Huvudnamnrymden)',

# Contributions
'contributions'       => 'Användarbidrag',
'contributions-title' => 'Bidrag av $1',
'mycontris'           => 'Mina bidrag',
'contribsub2'         => 'För $1 ($2)',
'nocontribs'          => 'Inga ändringar som motsvarar dessa kriterier hittades.',
'uctop'               => '(senaste)',
'month'               => 'Från månad (och tidigare):',
'year'                => 'Från år (och tidigare):',

'sp-contributions-newbies'             => 'Visa endast bidrag från nya konton',
'sp-contributions-newbies-sub'         => 'Från nya konton',
'sp-contributions-newbies-title'       => 'Bidrag från nya konton',
'sp-contributions-blocklog'            => 'Blockeringslogg',
'sp-contributions-deleted'             => 'raderade användarbidrag',
'sp-contributions-logs'                => 'Loggar',
'sp-contributions-talk'                => 'diskussion',
'sp-contributions-userrights'          => 'hantering av användarrättigheter',
'sp-contributions-blocked-notice'      => 'Användaren är blockerad.
Orsaken till senaste blockeringen kan ses nedan:',
'sp-contributions-blocked-notice-anon' => 'Denna IP-adress är för närvarande blockerad.
Den senaste posten i blockeringsloggen visas nedan som referens:',
'sp-contributions-search'              => 'Sök efter användarbidrag',
'sp-contributions-username'            => 'IP-adress eller användarnamn:',
'sp-contributions-submit'              => 'Sök',

# What links here
'whatlinkshere'            => 'Vad som länkar hit',
'whatlinkshere-title'      => 'Sidor som länkar till "$1"',
'whatlinkshere-page'       => 'Sida:',
'linkshere'                => "Följande sidor länkar till '''[[:$1]]''':",
'nolinkshere'              => "Inga sidor länkar till '''[[:$1]]'''.",
'nolinkshere-ns'           => "Inga sidor i den angivna namnrymden länkar till '''[[:$1]]'''.",
'isredirect'               => 'omdirigeringssida',
'istemplate'               => 'inkluderad som mall',
'isimage'                  => 'fillänk',
'whatlinkshere-prev'       => '{{PLURAL:$1|förra|förra $1}}',
'whatlinkshere-next'       => '{{PLURAL:$1|nästa|nästa $1}}',
'whatlinkshere-links'      => '← länkar',
'whatlinkshere-hideredirs' => '$1 omdirigeringar',
'whatlinkshere-hidetrans'  => '$1 mallinkluderingar',
'whatlinkshere-hidelinks'  => '$1 länkar',
'whatlinkshere-hideimages' => '$1 fillänkar',
'whatlinkshere-filters'    => 'Filter',

# Block/unblock
'blockip'                         => 'Blockera användare',
'blockip-title'                   => 'Blockera användare',
'blockip-legend'                  => 'Blockera användare',
'blockiptext'                     => 'Använd formuläret nedan för att blockera möjligheten att redigera sidor från en specifik IP-adress eller ett användarnamn.
Detta bör endast göras för att förhindra vandalisering, och i överensstämmelse med gällande [[{{MediaWiki:Policy-url}}|policy]].
Ange orsak nedan (exempelvis genom att nämna sidor som blivit vandaliserade).',
'ipaddress'                       => 'IP-adress',
'ipadressorusername'              => 'IP-adress eller användarnamn:',
'ipbexpiry'                       => 'Varaktighet:',
'ipbreason'                       => 'Anledning:',
'ipbreasonotherlist'              => 'Annan anledning',
'ipbreason-dropdown'              => '*Vanliga motiv till blockering
** Infogar falsk information
** Tar bort sidinnehåll
** Länkspam till externa sajter
** Lägger till nonsens på sidor
** Hotfullt beteende/trakasserier
** Missbruk av flera användarkonton
** Oacceptabelt användarnamn',
'ipbanononly'                     => 'Blockera bara oinloggade användare',
'ipbcreateaccount'                => 'Förhindra registrering av användarkonton',
'ipbemailban'                     => 'Hindra användaren från att skicka e-post',
'ipbenableautoblock'              => 'Blockera automatiskt den IP-adress som användaren använde senast, samt alla adresser som användaren försöker redigera ifrån',
'ipbsubmit'                       => 'Blockera användaren',
'ipbother'                        => 'Annan tidsperiod:',
'ipboptions'                      => '2 timmar:2 hours,1 dygn:1 day,3 dygn:3 days,1 vecka:1 week,2 veckor:2 weeks,1 månad:1 month,3 månader:3 months,6 månader:6 months,1 år:1 year,oändlig:infinite',
'ipbotheroption'                  => 'annan tidsperiod',
'ipbotherreason'                  => 'Annan/ytterligare anledning:',
'ipbhidename'                     => 'Dölj användarnamnet från redigeringar och listor',
'ipbwatchuser'                    => 'Bevaka användarens användarsida och diskussionssida',
'ipballowusertalk'                => 'Låt användaren redigera sin egen diskussionssida under blockeringen',
'ipb-change-block'                => 'Återblockera användaren med de här inställningarna',
'badipaddress'                    => 'Du har inte skrivit IP-adressen korrekt.',
'blockipsuccesssub'               => 'Blockeringen är utförd',
'blockipsuccesstext'              => '[[Special:Contributions/$1|$1]] har blockerats.
<br />För att se alla aktuella blockeringar, gå till [[Special:IPBlockList|listan över blockeringar]].',
'ipb-edit-dropdown'               => 'Redigera blockeringsanledningar',
'ipb-unblock-addr'                => 'Ta bort blockering av $1',
'ipb-unblock'                     => 'Ta bort blockering av en användare eller IP-adress',
'ipb-blocklist-addr'              => 'Gällande blockeringar av $1',
'ipb-blocklist'                   => 'Visa gällande blockeringar',
'ipb-blocklist-contribs'          => 'Bidrag från $1',
'unblockip'                       => 'Ta bort blockering av användare/IP-adress',
'unblockiptext'                   => 'Använd formuläret nedan för att ta bort blockeringen av en IP-adress.',
'ipusubmit'                       => 'Upphäv denna blockering',
'unblocked'                       => 'Blockeringen av [[User:$1|$1]] har hävts',
'unblocked-id'                    => 'Blockeringen $1 har hävts',
'ipblocklist'                     => 'Blockerade IP-adresser och användarnamn',
'ipblocklist-legend'              => 'Sök efter en blockerad användare',
'ipblocklist-username'            => 'Användarnamn eller IP-adress',
'ipblocklist-sh-userblocks'       => '$1 blockeringar av konton',
'ipblocklist-sh-tempblocks'       => '$1 tillfälliga blockeringar',
'ipblocklist-sh-addressblocks'    => '$1 blockeringar av enskilda IP-adresser',
'ipblocklist-submit'              => 'Sök',
'ipblocklist-localblock'          => 'Lokalblockering',
'ipblocklist-otherblocks'         => 'Övriga {{PLURAL:$1|blockering|blockeringar}}',
'blocklistline'                   => '$1, $2 blockerade $3 ($4)',
'infiniteblock'                   => 'för evigt',
'expiringblock'                   => 'utgår den $1 kl $2',
'anononlyblock'                   => 'endast för oinloggade',
'noautoblockblock'                => 'utan automatisk blockering',
'createaccountblock'              => 'kontoregistrering blockerad',
'emailblock'                      => 'e-post blockerad',
'blocklist-nousertalk'            => 'kan inte redigera sin egen diskussionssida',
'ipblocklist-empty'               => 'Listan över blockerade IP-adresser är tom.',
'ipblocklist-no-results'          => 'Den angivna IP-adressen eller användaren är inte blockerad.',
'blocklink'                       => 'blockera',
'unblocklink'                     => 'ta bort blockering',
'change-blocklink'                => 'ändra blockering',
'contribslink'                    => 'bidrag',
'autoblocker'                     => 'Automatisk blockerad eftersom din IP-adress nyligen använts av "[[User:$1|$1]]".
Motiveringen som angavs för blockeringen av $1 var: "$2".',
'blocklogpage'                    => 'Blockeringslogg',
'blocklog-showlog'                => 'Denna användare har blivit blockerad tidigare.
Blockeringsloggen är tillgänglig nedan som referens:',
'blocklog-showsuppresslog'        => 'Denna användare har tidigare blivit blockerad och gömd.
Undanhållandeloggen visas nedan för referens:',
'blocklogentry'                   => 'blockerade [[$1]] med blockeringstid på $2 $3',
'reblock-logentry'                => 'ändrade blockeringsinställningar för [[$1]] med en varaktighet på $2 $3',
'blocklogtext'                    => 'Detta är en logg över blockeringar och avblockeringar.
Automatiskt blockerade IP-adresser listas ej.
I [[Special:IPBlockList|blockeringslistan]] listas alla IP-adresser och användare som är blockerade för närvarande.',
'unblocklogentry'                 => 'tog bort blockering av "$1"',
'block-log-flags-anononly'        => 'bara oinloggade',
'block-log-flags-nocreate'        => 'hindrar kontoregistrering',
'block-log-flags-noautoblock'     => 'utan automatblockering',
'block-log-flags-noemail'         => 'e-post blockerad',
'block-log-flags-nousertalk'      => 'kan inte redigera sin diskussionssida',
'block-log-flags-angry-autoblock' => 'utökad automatblockering aktiverad',
'block-log-flags-hiddenname'      => 'användarnamn gömt',
'range_block_disabled'            => 'Möjligheten för administratörer att blockera intervall av IP-adresser har stängts av.',
'ipb_expiry_invalid'              => 'Ogiltig varaktighetstid.',
'ipb_expiry_temp'                 => 'För att dölja användarnamnet måste blockeringen vara permanent.',
'ipb_hide_invalid'                => 'Kan inte undanhålla detta konto; det kan ha för många redigeringar.',
'ipb_already_blocked'             => '"$1" är redan blockerad',
'ipb-needreblock'                 => '== Redan blockerad ==
$1 är redan blockerad. Vill du ändra inställningarna?',
'ipb-otherblocks-header'          => 'Andra {{PLURAL:$1|blockering|blockeringar}}',
'ipb_cant_unblock'                => 'Fel: Hittade inte blockering $1. Det är möjligt att den redan har upphävts.',
'ipb_blocked_as_range'            => 'Fel: IP-adressen $1 är inte direkt blockerad, och kan därför inte avblockeras. Adressen är blockerad som en del av IP-intervallet $2, som kan avblockeras.',
'ip_range_invalid'                => 'Ogiltigt IP-intervall.',
'ip_range_toolarge'               => 'Blockering av block större än /$1 är inte tillåtna.',
'blockme'                         => 'Blockera mig',
'proxyblocker'                    => 'Proxy-block',
'proxyblocker-disabled'           => 'Den här funktionen är avaktiverad.',
'proxyblockreason'                => 'Din IP-adress har blivit blockerad eftersom den tillhör en öppen proxy. Kontakta din internetleverantör eller din organisations eller företags tekniska support, och informera dem om denna allvarliga säkerhetsrisk.',
'proxyblocksuccess'               => 'Gjort.',
'sorbsreason'                     => 'Din IP-adress är listad som öppen proxy i den DNSBL {{SITENAME}} använder.',
'sorbs_create_account_reason'     => 'Din IP-adress är listad som en öppen proxy i den DNSBL som används av {{SITENAME}}.
Du får inte skapa ett användarkonto',
'cant-block-while-blocked'        => 'Du kan inte blockera andra användare medan du är blockerad.',
'cant-see-hidden-user'            => 'Användaren du försöker blockera är redan blockerad och gömd. Eftersom du inte har hideuser-rättigheter, kan du inte se eller redigera användarens blockering.',
'ipbblocked'                      => 'Du kan inte blockera eller avblockera andra användare, eftersom du själv är blockerad',
'ipbnounblockself'                => 'Du har inte tillåtelse att avblockera dig själv',

# Developer tools
'lockdb'              => 'Lås databas',
'unlockdb'            => 'Lås upp databas',
'lockdbtext'          => 'En låsning av databasen hindrar alla användare från att redigera sidor, ändra inställningar och andra saker som kräver ändringar i databasen.
Bekräfta att du verkligen vill göra detta, och att du kommer att låsa upp databasen när underhållet är utfört.',
'unlockdbtext'        => 'Om du låser upp databasen kommer alla användare att åter kunna redigera sidor, ändra sina inställningar och så vidare. Bekräfta att du vill göra detta.',
'lockconfirm'         => 'Ja, jag vill verkligen låsa databasen.',
'unlockconfirm'       => 'Ja, jag vill låsa upp databasen.',
'lockbtn'             => 'Lås databasen',
'unlockbtn'           => 'Lås upp databasen',
'locknoconfirm'       => 'Du har inte bekräftat låsningen.',
'lockdbsuccesssub'    => 'Databasen har låsts',
'unlockdbsuccesssub'  => 'Databasen har låsts upp',
'lockdbsuccesstext'   => 'Databasen är nu låst.
<br />Kom ihåg att [[Special:UnlockDB|ta bort låsningen]] när du är färdig med ditt underhåll.',
'unlockdbsuccesstext' => 'Databasen är upplåst.',
'lockfilenotwritable' => 'Det går inte att skriva till databasens låsfil. För att låsa eller låsa upp databasen, så måste webbservern kunna skriva till den filen.',
'databasenotlocked'   => 'Databasen är inte låst.',

# Move page
'move-page'                    => 'Flytta $1',
'move-page-legend'             => 'Flytta sida',
'movepagetext'                 => "Med hjälp av formuläret härunder kan du byta namn på en sida, och flytta hela dess historik till ett nytt namn.
Den gamla sidtiteln kommer att göras om till en omdirigering till den nya titeln.
Du kan välja att automatiskt uppdatera omdirigeringar som leder till den gamla titeln.
Om du väljer att inte göra det, kontrollera då att du inte skapar några [[Special:DoubleRedirects|dubbla]] eller [[Special:BrokenRedirects|trasiga omdirigeringar]].
Du bör också se till att länkar fortsätter att peka dit de ska.

Notera att sidan '''inte''' kan flyttas om det redan finns en sida under den nya sidtiteln, såvida inte den sidan är tom eller en omdirigering till den gamla titeln och saknar annan versionshistorik.
Det innebär att du kan flytta tillbaks en sida om du råkar göra fel, och att du inte kan skriva över existerande sidor.

'''VARNING!'''
Att flytta en populär sida kan vara en drastisk och oväntad ändring;
därför bör du vara säker på att du förstår konsekvenserna innan du fortsätter med flytten.",
'movepagetalktext'             => "Diskussionssidan kommer att även den automatiskt flyttas '''om inte''':
*Det redan finns en diskussionssida som inte är tom med det nya namnet, eller
*Du avmarkerar rutan nedan.

I de fallen måste du flytta eller sammanfoga sidan manuellt, om det önskas.",
'movearticle'                  => 'Flytta sidan:',
'moveuserpage-warning'         => "'''Varning:''' Du håller på att flytta en användarsida. Observera att endast sidan kommer att flyttas och att användaren ''inte'' kommer att byta namn.",
'movenologin'                  => 'Inte inloggad',
'movenologintext'              => 'För att flytta en sida måste du vara registrerad användare och [[Special:UserLogin|inloggad]].',
'movenotallowed'               => 'Du har inte behörighet att flytta sidor på den här wikin.',
'movenotallowedfile'           => 'Du har inte tillåtelse att flytta filer.',
'cant-move-user-page'          => 'Du har inte behörighet att flytta användarsidor (bortsett från undersidor).',
'cant-move-to-user-page'       => 'Du har inte behörighet att flytta en sida till en användarsida (förutom till en användarundersida).',
'newtitle'                     => 'Till nya titeln:',
'move-watch'                   => 'Bevaka denna sida',
'movepagebtn'                  => 'Flytta sidan',
'pagemovedsub'                 => 'Flyttningen lyckades',
'movepage-moved'               => '\'\'\'"$1" har flyttats till "$2"\'\'\'',
'movepage-moved-redirect'      => 'En omdirigering har skapats.',
'movepage-moved-noredirect'    => 'Skapandet av en omdirigering avbröts.',
'articleexists'                => 'Antingen existerar redan en sida med det namnet, eller så har du valt ett namn som inte är tillåtet.
Välj något annat namn istället.',
'cantmove-titleprotected'      => 'Du kan inte flytta sidan till den titeln, eftersom den nya titeln har skyddats från att skapas.',
'talkexists'                   => "'''Sidan flyttades, men diskussionssidan kunde inte flyttas eftersom det redan fanns en diskussionssida med det nya namnet.
Försök att sammanfoga dem manuellt.'''",
'movedto'                      => 'flyttad till',
'movetalk'                     => 'Flytta tillhörande diskussionssida',
'move-subpages'                => 'Flytta undersidor (upp till $1)',
'move-talk-subpages'           => 'Flytta undersidor av diskussionssidan (upp till $1)',
'movepage-page-exists'         => 'Sidan $1 finns redan och kan inte skrivas över automatiskt.',
'movepage-page-moved'          => 'Sidan $1 har flyttats till $2.',
'movepage-page-unmoved'        => 'Sidan $1 kunde inte flyttas till $2.',
'movepage-max-pages'           => 'Gränsen på $1 {{PLURAL:$1|flyttad sida|flyttade sidor}} har uppnåtts och inga fler sidor kommer att flyttas automatiskt.',
'1movedto2'                    => 'flyttade [[$1]] till [[$2]]',
'1movedto2_redir'              => 'flyttade [[$1]] till [[$2]], som var en omdirigeringssida',
'move-redirect-suppressed'     => 'utan omdirigering',
'movelogpage'                  => 'Flyttlogg',
'movelogpagetext'              => 'Listan nedan visar sidor som flyttats.',
'movesubpage'                  => '{{PLURAL:$1|Undersida|Undersidor}}',
'movesubpagetext'              => 'Denna sida har $1 {{PLURAL:$1|undersida|undersidor}} som visas nedan.',
'movenosubpage'                => 'Denna sida har inga undersidor.',
'movereason'                   => 'Anledning:',
'revertmove'                   => 'flytta tillbaka',
'delete_and_move'              => 'Radera och flytta',
'delete_and_move_text'         => '==Radering krävs==
Den titel du vill flytta sidan till, "[[:$1]]", finns redan. Vill du radera den för att möjliggöra flytt av denna sida dit?',
'delete_and_move_confirm'      => 'Ja, radera sidan',
'delete_and_move_reason'       => 'Raderad för att flytta hit en annan sida.',
'selfmove'                     => 'Ursprungstitel och destinationstitel är identiska. Sidan kan inte flyttas till sig själv.',
'immobile-source-namespace'    => 'Kan inte flytta sidor i namnrymden "$1"',
'immobile-target-namespace'    => 'Kan inte flytta sidor till namnrymden "$1"',
'immobile-target-namespace-iw' => 'Interwikilänk är inte ett giltigt mål för sidflyttar.',
'immobile-source-page'         => 'Denna sida är inte flyttbar.',
'immobile-target-page'         => 'Kan inte flytta till det målnamnet.',
'imagenocrossnamespace'        => 'Kan inte flytta filer till andra namnrymder än filnamnrymden',
'imagetypemismatch'            => 'Den nya filändelsen motsvarar inte filtypen',
'imageinvalidfilename'         => 'Önskat filnamn är ogiltigt',
'fix-double-redirects'         => 'Uppdatera omdirigeringar som leder till den gamla titeln',
'move-leave-redirect'          => 'Lämna kvar en omdirigering',
'protectedpagemovewarning'     => "'''Varning:''' Den här sidan har låsts så att endast användare med administratörsrättigheter kan flytta den.
Den senaste loggposten tillhandahålls nedan som referens:",
'semiprotectedpagemovewarning' => "'''Observera:''' Denna sida har låsts så att endast registrerade användare kan flytta den.
Den senaste loggposten tillhandahålls nedan som referens:",
'move-over-sharedrepo'         => '== Filen existerar ==
[[:$1]] existerar på en delad källa. Om du flyttar en fil till detta namn kommer den delad filen bli överskriven.',
'file-exists-sharedrepo'       => 'Detta filnamn existerar redan på en delad källa.
Var god välj ett annat namn.',

# Export
'export'            => 'Exportera sidor',
'exporttext'        => 'Du kan exportera text och versionshistorik för en eller flera sidor i XML-format.
Filen kan sedan importeras till en annan MediaWiki-wiki med hjälp av sidan [[Special:Import|importera]].

Exportera sidor genom att skriva in sidtitlarna i rutan här nedan.
Skriv en titel per rad och välj om du du vill exportera alla versioner av texten med sidhistorik, eller om du enbart vill exportera den nuvarande versionen med information om den senaste redigeringen.

I det senare fallet kan du även använda en länk, exempel [[{{#Special:Export}}/{{MediaWiki:Mainpage}}]] för sidan "[[{{MediaWiki:Mainpage}}]]".',
'exportcuronly'     => 'Inkludera endast den nuvarande versionen, inte hela historiken',
'exportnohistory'   => "----
'''OBS:''' export av fullständig sidhistorik med hjälp av detta formulär har stängts av på grund av prestandaskäl.",
'export-submit'     => 'Exportera',
'export-addcattext' => 'Lägg till sidor från kategori:',
'export-addcat'     => 'Lägg till',
'export-addnstext'  => 'Lägg till sidor från namnrymd:',
'export-addns'      => 'Lägg till',
'export-download'   => 'Ladda ner som fil',
'export-templates'  => 'Inkludera mallar',
'export-pagelinks'  => 'Inkludera länkade sidor till ett djup på:',

# Namespace 8 related
'allmessages'                   => 'Systemmeddelanden',
'allmessagesname'               => 'Namn',
'allmessagesdefault'            => 'Standardtext',
'allmessagescurrent'            => 'Nuvarande text',
'allmessagestext'               => 'Detta är en lista över alla meddelanden i namnrymden MediaWiki.
Besök [http://www.mediawiki.org/wiki/Localisation MediaWiki Localisation] eller [http://translatewiki.net translatewiki.net] om du vill bidra till översättningen av MediaWiki.',
'allmessagesnotsupportedDB'     => "Den här sidan kan inte användas eftersom '''\$wgUseDatabaseMessages''' är avstängd.",
'allmessages-filter-legend'     => 'Filtrera',
'allmessages-filter'            => 'Filtrera efter anpassningsgrad:',
'allmessages-filter-unmodified' => 'Ej modifierad',
'allmessages-filter-all'        => 'Alla',
'allmessages-filter-modified'   => 'Modifierad',
'allmessages-prefix'            => 'Filtrera efter prefix:',
'allmessages-language'          => 'Språk:',
'allmessages-filter-submit'     => 'Gå',

# Thumbnails
'thumbnail-more'           => 'Förstora',
'filemissing'              => 'Fil saknas',
'thumbnail_error'          => 'Fel vid skapande av miniatyrbild: $1',
'djvu_page_error'          => 'DjVu-sida utanför gränserna',
'djvu_no_xml'              => 'Kan inte hämta DjVu-filens XML',
'thumbnail_invalid_params' => 'Ogiltiga parametrar för miniatyrbilden',
'thumbnail_dest_directory' => 'Kan inte skapa målkatalogen',
'thumbnail_image-type'     => 'Bildtypen stöds inte',
'thumbnail_gd-library'     => 'Inkomplett GD library konfigurering: saknar funktionen $1',
'thumbnail_image-missing'  => 'Fil verkar saknas: $1',

# Special:Import
'import'                     => 'Importera sidor',
'importinterwiki'            => 'Transwiki-import',
'import-interwiki-text'      => 'Välj en wiki och sidtitel att importera.
Versionshistorik (datum och redaktörer) kommer att bevaras.
All överföring mellan wikier (transwiki) listas i  [[Special:Log/import|importloggen]].',
'import-interwiki-source'    => 'Källwiki/sida:',
'import-interwiki-history'   => 'Kopiera hela versionshistoriken för denna sida',
'import-interwiki-templates' => 'Inkludera alla mallar',
'import-interwiki-submit'    => 'Importera',
'import-interwiki-namespace' => 'Målnamnrymd:',
'import-upload-filename'     => 'Filnamn:',
'import-comment'             => 'Kommentar:',
'importtext'                 => 'Exportera filen från ursprungs-wikin genom att använda [[Special:Export|exportverktyget]], spara den till din hårddisk och ladda upp den här.',
'importstart'                => 'Importerar sidor....',
'import-revision-count'      => '$1 {{PLURAL:$1|version|versioner}}',
'importnopages'              => 'Det finns inga sidor att importera.',
'imported-log-entries'       => 'Importerade $1 {{PLURAL: $1 |loggpost|loggposter}}.',
'importfailed'               => 'Importen misslyckades: <nowiki>$1</nowiki>',
'importunknownsource'        => 'Okänd typ av importkälla',
'importcantopen'             => 'Misslyckades med att öppna importfilen.',
'importbadinterwiki'         => 'Felaktig interwiki-länk',
'importnotext'               => 'Tom eller ingen text',
'importsuccess'              => 'Importen är genomförd!',
'importhistoryconflict'      => 'Det föreligger en konflikt i versionshistoriken (kanske har denna sida importerats tidigare)',
'importnosources'            => 'Inga källor för transwiki-import har angivits, och direkt uppladdning av historik har stängts av.',
'importnofile'               => 'Ingen fil att importera har laddats upp.',
'importuploaderrorsize'      => 'Uppladdningen av importfilen misslyckades. Filen är större än vad som är tillåtet att ladda upp.',
'importuploaderrorpartial'   => 'Uppladdningen av importfilen misslyckades. Bara en del av filen laddades upp.',
'importuploaderrortemp'      => 'Uppladdningen av importfilen misslyckades. En temporär katalog saknas.',
'import-parse-failure'       => 'Tolkningsfel vid XML-import',
'import-noarticle'           => 'Inga sidor att importera!',
'import-nonewrevisions'      => 'Alla sidversioner hade importerats tidigare.',
'xml-error-string'           => '$1 på rad $2, kolumn $3 (byte $4): $5',
'import-upload'              => 'Ladda upp XML-data',
'import-token-mismatch'      => 'Sessionsdata har förlorats. Var god pröva igen.',
'import-invalid-interwiki'   => 'Kan inte importera från den angivna wikin.',

# Import log
'importlogpage'                    => 'Importlogg',
'importlogpagetext'                => 'Administrativa sidimporter med versionshistorik från andra wikier.',
'import-logentry-upload'           => 'importerade [[$1]] genom filuppladdning',
'import-logentry-upload-detail'    => '$1 {{PLURAL:$1|version|versioner}}',
'import-logentry-interwiki'        => 'överförde $1 mellan wikier',
'import-logentry-interwiki-detail' => '$1 {{PLURAL:$1|version|versioner}} från $2',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Din användarsida',
'tooltip-pt-anonuserpage'         => 'Användarsida för ip-numret du redigerar från',
'tooltip-pt-mytalk'               => 'Din diskussionssida',
'tooltip-pt-anontalk'             => 'Diskussion om redigeringar från det här ip-numret',
'tooltip-pt-preferences'          => 'Mina inställningar',
'tooltip-pt-watchlist'            => 'Listan över sidor du bevakar för ändringar',
'tooltip-pt-mycontris'            => 'Lista över dina bidrag',
'tooltip-pt-login'                => 'Du får gärna logga in, men det är inte nödvändigt',
'tooltip-pt-anonlogin'            => 'Du får gärna logga in, men det är inte nödvändigt',
'tooltip-pt-logout'               => 'Logga ut',
'tooltip-ca-talk'                 => 'Diskussion om innehållssidan',
'tooltip-ca-edit'                 => 'Du kan redigera den här sidan.
Vänligen använd förhandsgranskningsknappen innan du sparar.',
'tooltip-ca-addsection'           => 'Starta ett nytt avsnitt',
'tooltip-ca-viewsource'           => 'Den här sidan är skrivskyddad. Du kan se källtexten.',
'tooltip-ca-history'              => 'Tidigare versioner av sidan',
'tooltip-ca-protect'              => 'Skydda den här sidan',
'tooltip-ca-unprotect'            => 'Ta bort skrivskyddet från den här sidan',
'tooltip-ca-delete'               => 'Radera denna sida',
'tooltip-ca-undelete'             => 'Återställ alla redigeringar som gjorts innan sidan raderades',
'tooltip-ca-move'                 => 'Flytta den här sidan',
'tooltip-ca-watch'                => 'Lägg till sidan på din bevakningslista',
'tooltip-ca-unwatch'              => 'Ta bort denna sida från din bevakningslista',
'tooltip-search'                  => 'Sök på {{SITENAME}}',
'tooltip-search-go'               => 'Gå till sidan med detta namn om den finns',
'tooltip-search-fulltext'         => 'Sök efter sidor som innehåller denna text',
'tooltip-p-logo'                  => 'Besök huvudsidan',
'tooltip-n-mainpage'              => 'Besök huvudsidan',
'tooltip-n-mainpage-description'  => 'Besök huvudsidan',
'tooltip-n-portal'                => 'Om projektet, vad du kan göra, var man kan hitta saker',
'tooltip-n-currentevents'         => 'Hitta bakgrundsinformation om aktuella händelser',
'tooltip-n-recentchanges'         => 'Lista över senaste ändringar i wikin',
'tooltip-n-randompage'            => 'Gå till en slumpmässigt vald sida',
'tooltip-n-help'                  => 'Hjälp och information',
'tooltip-t-whatlinkshere'         => 'Lista över alla wikisidor som länkar hit',
'tooltip-t-recentchangeslinked'   => 'Visa senaste ändringarna av sidor som den här sidan länkar till',
'tooltip-feed-rss'                => 'RSS-matning för den här sidan',
'tooltip-feed-atom'               => 'Atom-matning för den här sidan',
'tooltip-t-contributions'         => 'Visa lista över bidrag från den här användaren',
'tooltip-t-emailuser'             => 'Skicka e-post till den här användaren',
'tooltip-t-upload'                => 'Ladda upp filer',
'tooltip-t-specialpages'          => 'Lista över alla specialsidor',
'tooltip-t-print'                 => 'Utskriftvänlig version av den här sidan',
'tooltip-t-permalink'             => 'Permanent länk till den här versionen av sidan',
'tooltip-ca-nstab-main'           => 'Visa innehållssidan',
'tooltip-ca-nstab-user'           => 'Visa användarsidan',
'tooltip-ca-nstab-media'          => 'Visa mediesidan',
'tooltip-ca-nstab-special'        => 'Detta är en specialsida; du kan inte redigera själva sidan',
'tooltip-ca-nstab-project'        => 'Visa projektsidan',
'tooltip-ca-nstab-image'          => 'Visa filsidan',
'tooltip-ca-nstab-mediawiki'      => 'Visa systemmeddelandet',
'tooltip-ca-nstab-template'       => 'Visa mallen',
'tooltip-ca-nstab-help'           => 'Visa hjälpsidan',
'tooltip-ca-nstab-category'       => 'Visa kategorisidan',
'tooltip-minoredit'               => 'Markera som mindre ändring',
'tooltip-save'                    => 'Spara dina ändringar',
'tooltip-preview'                 => 'Förhandsgranska dina ändringar, vänligen använd detta innan du sparar!',
'tooltip-diff'                    => 'Visa vilka förändringar du har gjort av texten.',
'tooltip-compareselectedversions' => 'Visa skillnaden mellan de två markerade versionerna av den här sidan.',
'tooltip-watch'                   => 'Lägg till den här sidan i din bevakningslista',
'tooltip-recreate'                => 'Återskapa sidan fast den har tagits bort',
'tooltip-upload'                  => 'Starta uppladdning',
'tooltip-rollback'                => '"Tillbakarullning" tar med en knapptryckning bort ändringar som gjorts av den som senast redigerade sidan',
'tooltip-undo'                    => '"Gör ogjord" återställer denna redigering och öppnar redigeringsrutan med förhandsgranskning.
Ger möjlighet att skriva en motivering i redigeringssammanfattningen',
'tooltip-preferences-save'        => 'Spara inställningar',
'tooltip-summary'                 => 'Skriv en kort sammanfattning',

# Stylesheets
'common.css'      => '/* CSS som skrivs här påverkar alla skal */',
'standard.css'    => '/* CSS som skrivs här kommer att påverka alla användare av skalet Standard */',
'nostalgia.css'   => '/* CSS som skrivs här kommer att påverka alla användare av skalet Nostalgi */',
'cologneblue.css' => '/* CSS som skrivs här kommer att påverka alla användare av skalet Cologne blå */',
'monobook.css'    => '/* CSS som skrivs här kommer att påverka alla användare av skalet Monobook */',
'myskin.css'      => '/* CSS som skrivs här kommer att påverka alla användare av skalet Mitt utseende */',
'chick.css'       => '/* CSS som skrivs här kommer att påverka alla användare av skalet Chick */',
'simple.css'      => '/* CSS som skrivs här kommer att påverka alla användare av skalet Enkel */',
'modern.css'      => '/* CSS som skrivs här kommer att påverka alla användare av skalet Modern */',
'vector.css'      => '/* CSS som skrivs här kommer att påverka alla användare av skalet Vector */',
'print.css'       => '/* CSS som skrivs här kommer att påverka utskriftsversionen */',

# Scripts
'common.js'      => '/* JavaScript som skrivs här körs varje gång en användare laddar en sida. */',
'standard.js'    => '/* JavaScript här kommer att laddas för dem som använder skalet Standard */',
'nostalgia.js'   => '/* JavaScript här kommer att laddas för dem som använder skalet Nostalgi */',
'cologneblue.js' => '/* JavaScript här kommer att laddas för dem som använder skalet Cologne blå */',
'monobook.js'    => '/* JavaScript här kommer att laddas för dem som använder skalet Monobook */',
'myskin.js'      => '/* JavaScript här kommer att laddas för dem som använder skalet Mitt utseende */',
'chick.js'       => '/* JavaScript här kommer att laddas för dem som använder skalet Chick */',
'simple.js'      => '/* JavaScript här kommer att laddas för dem som använder skalet Enkel */',
'modern.js'      => '/* JavaScript här kommer att laddas för dem som använder skalet Modern */',
'vector.js'      => '/* JavaScript här kommer att laddas för dem som använder skalet Vector */',

# Metadata
'nodublincore'      => 'Dublin Core RDF metadata avstängt på den här servern.',
'nocreativecommons' => 'Creative Commons RDF metadata avstängd på denna server.',
'notacceptable'     => 'Den här wiki-servern kan inte erbjuda data i ett format som din klient kan läsa.',

# Attribution
'anonymous'        => '{{PLURAL:$1|Anonym användare|Anonyma användare}} på {{SITENAME}}',
'siteuser'         => 'användaren $1 på {{SITENAME}}',
'anonuser'         => '{{SITENAME}} anonym användare $1',
'lastmodifiedatby' => 'Den här sidan ändrades senast kl. $2 den $1 av $3.',
'othercontribs'    => 'Baserad på arbete av $1.',
'others'           => 'andra',
'siteusers'        => '{{SITENAME}}-{{PLURAL:$2|användaren|användarna}} $1',
'anonusers'        => '{{SITENAME}} {{PLURAL:$2|anonym|anonyma}} användare $1',
'creditspage'      => 'Användare som bidragit till sidan',
'nocredits'        => 'Det finns ingen information tillgänglig om vem som bidragit till denna sida.',

# Spam protection
'spamprotectiontitle' => 'Spamfilter',
'spamprotectiontext'  => 'Sidan du ville spara blockerades av spamfiltret.
Detta orsakades troligen av en länk till en svartlistad webbplats.',
'spamprotectionmatch' => 'Följande text aktiverade vårt spamfilter: $1',
'spambot_username'    => 'MediaWikis spampatrull',
'spam_reverting'      => 'Återställer till den senaste versionen som inte innehåller länkar till $1',
'spam_blanking'       => 'Alla versioner innehöll en länk till $1, blankar',

# Info page
'infosubtitle'   => 'Information om sida',
'numedits'       => 'Antal redigeringar (sida): $1',
'numtalkedits'   => 'Antal redigeringar (diskussionssida): $1',
'numwatchers'    => 'Antal användare som bevakar sidan: $1',
'numauthors'     => 'Antal olika bidragsgivare (sida): $1',
'numtalkauthors' => 'Antal olika bidragsgivare (diskussionssida): $1',

# Skin names
'skinname-standard'    => 'Standard',
'skinname-nostalgia'   => 'Nostalgi',
'skinname-cologneblue' => 'Cologne blå',
'skinname-monobook'    => 'Monobook',
'skinname-myskin'      => 'Mitt utseende',
'skinname-chick'       => 'Chick',
'skinname-simple'      => 'Enkel',
'skinname-modern'      => 'Modern',
'skinname-vector'      => 'Vector',

# Math options
'mw_math_png'    => 'Rendera alltid PNG',
'mw_math_simple' => 'HTML om mycket enkel, annars PNG',
'mw_math_html'   => 'HTML om möjligt, annars PNG',
'mw_math_source' => 'Låt vara TeX (för textbaserade webbläsare)',
'mw_math_modern' => 'Rekommenderat för modern webbläsare',
'mw_math_mathml' => 'MathML om möjligt (experimentellt)',

# Math errors
'math_failure'          => 'Misslyckades med att tolka formel.',
'math_unknown_error'    => 'okänt fel',
'math_unknown_function' => 'okänd funktion',
'math_lexing_error'     => 'regelfel',
'math_syntax_error'     => 'syntaxfel',
'math_image_error'      => 'Konvertering till PNG-format misslyckades; kontrollera om latex, dvips, gs och convert är korrekt installerade',
'math_bad_tmpdir'       => 'Kan inte skriva till eller skapa temporär mapp för matematikresultat',
'math_bad_output'       => 'Kan inte skriva till eller skapa mapp för matematikresultat',
'math_notexvc'          => 'Applicationen texvc saknas; läs math/README för konfigureringsanvisningar.',

# Patrolling
'markaspatrolleddiff'                 => 'Märk som patrullerad',
'markaspatrolledtext'                 => 'Märk den här sidan som patrullerad',
'markedaspatrolled'                   => 'Markerad som patrullerad',
'markedaspatrolledtext'               => 'Den valda versionen av [[:$1]] har märkts som patrullerad.',
'rcpatroldisabled'                    => 'Patrullering av Senaste ändringar är avstängd.',
'rcpatroldisabledtext'                => 'Funktionen "patrullering av Senaste ändringar" är tillfälligt avstängd.',
'markedaspatrollederror'              => 'Kan inte markera som patrullerad',
'markedaspatrollederrortext'          => 'Det går inte att markera som patrullerad utan att ange version.',
'markedaspatrollederror-noautopatrol' => 'Du har inte tillåtelse att markera dina egna redigeringar som patrullerade.',

# Patrol log
'patrol-log-page'      => 'Patrulleringslogg',
'patrol-log-header'    => 'Detta är en logg över patrullerade sidversioner.',
'patrol-log-line'      => 'markerade $1 av $2 som patrullerad $3',
'patrol-log-auto'      => '(automatiskt)',
'patrol-log-diff'      => 'version $1',
'log-show-hide-patrol' => '$1 patrulleringslogg',

# Image deletion
'deletedrevision'                 => 'Raderade gammal sidversion $1',
'filedeleteerror-short'           => 'Fel vid radering av fil: $1',
'filedeleteerror-long'            => 'Fel inträffade vid raderingen av filen:

$1',
'filedelete-missing'              => 'Filen "$1" kan inte raderas eftersom den inte finns.',
'filedelete-old-unregistered'     => 'Den angivna filversionen "$1" finns inte i databasen.',
'filedelete-current-unregistered' => 'Den angivna filen "$1" finns inte i databasen.',
'filedelete-archive-read-only'    => 'Webbservern kan inte skriva till arkivkatalogen "$1".',

# Browsing diffs
'previousdiff' => '← Äldre redigering',
'nextdiff'     => 'Nyare redigering →',

# Media information
'mediawarning'         => "'''Varning''': Den här filen kan innehålla elak kod.
Om du kör den kan din dator skadas.<hr />",
'imagemaxsize'         => "Begränsa bilders storlek:<br />''(för filbeskrivningssidor)''",
'thumbsize'            => 'Storlek på minibild:',
'widthheightpage'      => '$1×$2, $3 {{PLURAL:$3|sida|sidor}}',
'file-info'            => '(filstorlek: $1, MIME-typ: $2)',
'file-info-size'       => '($1 × $2 pixel, filstorlek: $3, MIME-typ: $4)',
'file-nohires'         => '<small>Det finns ingen version med högre upplösning.</small>',
'svg-long-desc'        => '(SVG-fil, grundstorlek: $1 × $2 pixel, filstorlek: $3)',
'show-big-image'       => 'Högupplöst version',
'show-big-image-thumb' => '<small>Storlek på förhandsvisningen: $1 × $2 pixel</small>',
'file-info-gif-looped' => 'upprepad',
'file-info-gif-frames' => '$1 {{PLURAL:$1|ram|ramar}}',

# Special:NewFiles
'newimages'             => 'Galleri över nya filer',
'imagelisttext'         => 'Nedan finns en lista med <strong>$1</strong> {{PLURAL:$1|fil|filer}} sorterad <strong>$2</strong>.',
'newimages-summary'     => 'Den här specialsidan visar de senast uppladdade filerna.',
'newimages-legend'      => 'Filter',
'newimages-label'       => 'Filnamn (eller en del av det):',
'showhidebots'          => '($1 robotar)',
'noimages'              => 'Ingenting att se.',
'ilsubmit'              => 'Sök',
'bydate'                => 'efter datum',
'sp-newimages-showfrom' => 'Visa nya filer från och med kl. $2 den $1',

# Video information, used by Language::formatTimePeriod() to format lengths in the above messages
'hours-abbrev' => 't',

# Bad image list
'bad_image_list' => 'Listan fungerar enligt följande:

Listan tar enbart hänsyn till rader som börjar med asterisk (*). 
Den första länken på en rad måste vara en länk till en otillåten fil.
Övriga länkar på samma rad kommer att hanteras som undantag, det vill säga sidor där filen tillåts användas.',

# Metadata
'metadata'          => 'Metadata',
'metadata-help'     => 'Den här filen innehåller extrainformation som troligen lades till av en digitalkamera eller skanner när filen skapades. Om filen har modifierats kan det hända att vissa detaljer inte överensstämmer med den modifierade filen.',
'metadata-expand'   => 'Visa utökade detaljer',
'metadata-collapse' => 'Dölj utökade detaljer',
'metadata-fields'   => 'EXIF-fält som listas i det här meddelandet visas på bildsidan när metadatatabellen är minimerad.
Övriga fält är gömda som standard, men visas när tabellen expanderas.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* isospeedratings
* focallength',

# EXIF tags
'exif-imagewidth'                  => 'Bredd',
'exif-imagelength'                 => 'Höjd',
'exif-bitspersample'               => 'Bitar per komponent',
'exif-compression'                 => 'Komprimeringsalgoritm',
'exif-photometricinterpretation'   => 'Pixelsammansättning',
'exif-orientation'                 => 'Orientering',
'exif-samplesperpixel'             => 'Antal komponenter',
'exif-planarconfiguration'         => 'Dataarrangemang',
'exif-ycbcrsubsampling'            => 'Subsamplingsförhållande mellan Y och C',
'exif-ycbcrpositioning'            => 'Positionering av Y och C',
'exif-xresolution'                 => 'Upplösning i horisontalplan',
'exif-yresolution'                 => 'Upplösning i vertikalplan',
'exif-resolutionunit'              => 'Enhet för upplösning av X och Y',
'exif-stripoffsets'                => 'Offset till bilddata',
'exif-rowsperstrip'                => 'Antal rader per strip',
'exif-stripbytecounts'             => 'Byte per komprimerad strip',
'exif-jpeginterchangeformat'       => 'Offset till JPEG SOI',
'exif-jpeginterchangeformatlength' => 'Antal bytes JPEG-data',
'exif-transferfunction'            => 'Överföringsfunktion',
'exif-whitepoint'                  => 'Vitpunktens renhet',
'exif-primarychromaticities'       => 'Primärfärgernas renhet',
'exif-ycbcrcoefficients'           => 'Koefficienter för färgrymdstransformationsmatris',
'exif-referenceblackwhite'         => 'Referensvärden för svart och vitt',
'exif-datetime'                    => 'Ändringstidpunkt',
'exif-imagedescription'            => 'Bildtitel',
'exif-make'                        => 'Kameratillverkare',
'exif-model'                       => 'Kameramodell',
'exif-software'                    => 'Använd mjukvara',
'exif-artist'                      => 'Skapare',
'exif-copyright'                   => 'Upphovsrättsägare',
'exif-exifversion'                 => 'Exif-version',
'exif-flashpixversion'             => 'Flashpix-version som stöds',
'exif-colorspace'                  => 'Färgrymd',
'exif-componentsconfiguration'     => 'Komponentanalys',
'exif-compressedbitsperpixel'      => 'Bildkomprimeringsläge',
'exif-pixelydimension'             => 'Giltig bildbredd',
'exif-pixelxdimension'             => 'Giltig bildhöjd',
'exif-makernote'                   => 'Tillverkarkommentarer',
'exif-usercomment'                 => 'Kommentarer',
'exif-relatedsoundfile'            => 'Relaterad ljudfil',
'exif-datetimeoriginal'            => 'Exponeringstidpunkt',
'exif-datetimedigitized'           => 'Tidpunkt för digitalisering',
'exif-subsectime'                  => 'Ändringstidpunkt, sekunddelar',
'exif-subsectimeoriginal'          => 'Exponeringstidpunkt, sekunddelar',
'exif-subsectimedigitized'         => 'Digitaliseringstidpunkt, sekunddelar',
'exif-exposuretime'                => 'Exponeringstid',
'exif-exposuretime-format'         => '$1 sek ($2)',
'exif-fnumber'                     => 'Bländartal',
'exif-exposureprogram'             => 'Exponeringsprogram',
'exif-spectralsensitivity'         => 'Spektral känslighet',
'exif-isospeedratings'             => 'Filmhastighet (ISO)',
'exif-oecf'                        => 'Optoelektronisk konversionsfaktor',
'exif-shutterspeedvalue'           => 'Slutarhastighet',
'exif-aperturevalue'               => 'Bländare',
'exif-brightnessvalue'             => 'Ljusstyrka',
'exif-exposurebiasvalue'           => 'Exponeringsbias',
'exif-maxaperturevalue'            => 'Maximal bländare',
'exif-subjectdistance'             => 'Avstånd till motivet',
'exif-meteringmode'                => 'Mätmetod',
'exif-lightsource'                 => 'Ljuskälla',
'exif-flash'                       => 'Blixt',
'exif-focallength'                 => 'Linsens brännvidd',
'exif-subjectarea'                 => 'Motivområde',
'exif-flashenergy'                 => 'Blixteffekt',
'exif-spatialfrequencyresponse'    => 'Rumslig frekvensrespons',
'exif-focalplanexresolution'       => 'Upplösning i fokalplan x',
'exif-focalplaneyresolution'       => 'Upplösning i fokalplan y',
'exif-focalplaneresolutionunit'    => 'Enhet för upplösning i fokalplan',
'exif-subjectlocation'             => 'Motivets läge',
'exif-exposureindex'               => 'Exponeringsindex',
'exif-sensingmethod'               => 'Avkänningsmetod',
'exif-filesource'                  => 'Filkälla',
'exif-scenetype'                   => 'Scentyp',
'exif-cfapattern'                  => 'CFA-mönster',
'exif-customrendered'              => 'Anpassad bildbehandling',
'exif-exposuremode'                => 'Exponeringsläge',
'exif-whitebalance'                => 'Vitbalans',
'exif-digitalzoomratio'            => 'Digitalt zoomomfång',
'exif-focallengthin35mmfilm'       => 'Brännvidd på 35 mm film',
'exif-scenecapturetype'            => 'Motivprogram',
'exif-gaincontrol'                 => 'Bildförstärkning',
'exif-contrast'                    => 'Kontrast',
'exif-saturation'                  => 'Mättnad',
'exif-sharpness'                   => 'Skärpa',
'exif-devicesettingdescription'    => 'Beskrivning av apparatens inställning',
'exif-subjectdistancerange'        => 'Avståndsintervall till motiv',
'exif-imageuniqueid'               => 'Unikt bild-ID',
'exif-gpsversionid'                => 'Version för GPS-taggar',
'exif-gpslatituderef'              => 'Nordlig eller sydlig latitud',
'exif-gpslatitude'                 => 'Latitud',
'exif-gpslongituderef'             => 'Östlig eller västlig longitud',
'exif-gpslongitude'                => 'Longitud',
'exif-gpsaltituderef'              => 'Referenshöjd',
'exif-gpsaltitude'                 => 'Höjd',
'exif-gpstimestamp'                => 'GPS-tid (atomur)',
'exif-gpssatellites'               => 'Satelliter använda för mätning',
'exif-gpsstatus'                   => 'Mottagarstatus',
'exif-gpsmeasuremode'              => 'Mätmetod',
'exif-gpsdop'                      => 'Mätnoggrannhet',
'exif-gpsspeedref'                 => 'Hastighetsenhet',
'exif-gpsspeed'                    => 'GPS-mottagarens hastighet',
'exif-gpstrackref'                 => 'Referenspunkt för rörelsens riktning',
'exif-gpstrack'                    => 'Rörelsens riktning',
'exif-gpsimgdirectionref'          => 'Referens för bildens riktning',
'exif-gpsimgdirection'             => 'Bildens riktning',
'exif-gpsmapdatum'                 => 'Använd geodetisk data',
'exif-gpsdestlatituderef'          => 'Referenspunkt för målets latitud',
'exif-gpsdestlatitude'             => 'Målets latitud',
'exif-gpsdestlongituderef'         => 'Referenspunkt för målets longitud',
'exif-gpsdestlongitude'            => 'Målets longitud',
'exif-gpsdestbearingref'           => 'Referens för riktning mot målet',
'exif-gpsdestbearing'              => 'Riktning mot målet',
'exif-gpsdestdistanceref'          => 'Referenspunkt för avstånd till målet',
'exif-gpsdestdistance'             => 'Avstånd till målet',
'exif-gpsprocessingmethod'         => 'GPS-behandlingsmetodens namn',
'exif-gpsareainformation'          => 'GPS-områdets namn',
'exif-gpsdatestamp'                => 'GPS-datum',
'exif-gpsdifferential'             => 'Differentiell GPS-korrektion',

# EXIF attributes
'exif-compression-1' => 'Inte komprimerad',

'exif-unknowndate' => 'Okänt datum',

'exif-orientation-1' => 'Normal',
'exif-orientation-2' => 'Spegelvänd horisontellt',
'exif-orientation-3' => 'Roterad 180°',
'exif-orientation-4' => 'Spegelvänd vertikalt',
'exif-orientation-5' => 'Roterad 90° moturs och spegelvänd vertikalt',
'exif-orientation-6' => 'Roterad 90° medurs',
'exif-orientation-7' => 'Roterad 90° medurs och spegelvänd vertikalt',
'exif-orientation-8' => 'Roterad 90° moturs',

'exif-planarconfiguration-1' => 'chunkformat',
'exif-planarconfiguration-2' => 'planärformat',

'exif-componentsconfiguration-0' => 'saknas',

'exif-exposureprogram-0' => 'Inte definierad',
'exif-exposureprogram-1' => 'Manuell inställning',
'exif-exposureprogram-2' => 'Normalprogram',
'exif-exposureprogram-3' => 'Prioritet för bländare',
'exif-exposureprogram-4' => 'Prioritet för slutare',
'exif-exposureprogram-5' => 'Konstnärligt program (prioriterar skärpedjup)',
'exif-exposureprogram-6' => 'Rörelseprogram (prioriterar kortare slutartid)',
'exif-exposureprogram-7' => 'Porträttläge (för närbilder med bakgrunden ofokuserad)',
'exif-exposureprogram-8' => 'Landskapsläge (för foton av landskap med bakgrunden i fokus)',

'exif-subjectdistance-value' => '$1 meter',

'exif-meteringmode-0'   => 'Okänd',
'exif-meteringmode-1'   => 'Medelvärde',
'exif-meteringmode-2'   => 'Centrumviktat medelvärde',
'exif-meteringmode-3'   => 'Spotmätning',
'exif-meteringmode-4'   => 'Multispot',
'exif-meteringmode-5'   => 'Mönster',
'exif-meteringmode-6'   => 'Partiell',
'exif-meteringmode-255' => 'Annan',

'exif-lightsource-0'   => 'Okänd',
'exif-lightsource-1'   => 'Dagsljus',
'exif-lightsource-2'   => 'Lysrör',
'exif-lightsource-3'   => 'Glödlampa',
'exif-lightsource-4'   => 'Blixt',
'exif-lightsource-9'   => 'Klart väder',
'exif-lightsource-10'  => 'Molnigt',
'exif-lightsource-11'  => 'Skugga',
'exif-lightsource-12'  => 'Dagsljuslysrör (D 5700 – 7100K)',
'exif-lightsource-13'  => 'Dagsvitt lysrör (N 4600 – 5400K)',
'exif-lightsource-14'  => 'Kallvitt lysrör (W 3900 – 4500K)',
'exif-lightsource-15'  => 'Vitt lysrör (WW 3200 – 3700K)',
'exif-lightsource-17'  => 'Standardljus A',
'exif-lightsource-18'  => 'Standardljus B',
'exif-lightsource-19'  => 'Standardljus C',
'exif-lightsource-24'  => 'ISO studiobelysning',
'exif-lightsource-255' => 'Annan ljuskälla',

# Flash modes
'exif-flash-fired-0'    => 'Blixten utlöstes ej',
'exif-flash-fired-1'    => 'Blixten utlöstes',
'exif-flash-return-0'   => 'ingen funktion för upptäckande av pulserande ljus',
'exif-flash-return-2'   => 'pulserande ljus inte upptäckt',
'exif-flash-return-3'   => 'pulserande ljus upptäckt',
'exif-flash-mode-1'     => 'tvingad blixtutlösning',
'exif-flash-mode-2'     => 'blixten avstängd',
'exif-flash-mode-3'     => 'autoläge',
'exif-flash-function-1' => 'Ingen blixtfunktion',
'exif-flash-redeye-1'   => 'röda-ögon-reducering',

'exif-focalplaneresolutionunit-2' => 'tum',

'exif-sensingmethod-1' => 'Ej angivet',
'exif-sensingmethod-2' => 'Enchipsfärgsensor',
'exif-sensingmethod-3' => 'Tvåchipsfärgsensor',
'exif-sensingmethod-4' => 'Trechipsfärgsensor',
'exif-sensingmethod-5' => 'Färgsekventiell områdessensor',
'exif-sensingmethod-7' => 'Trilinjär sensor',
'exif-sensingmethod-8' => 'Färgsekventiell linjär sensor',

'exif-scenetype-1' => 'Direkt fotograferad bild',

'exif-customrendered-0' => 'Normal',
'exif-customrendered-1' => 'Anpassad',

'exif-exposuremode-0' => 'Automatisk exponering',
'exif-exposuremode-1' => 'Manuell exponering',
'exif-exposuremode-2' => 'Automatisk alternativexponering',

'exif-whitebalance-0' => 'Automatisk vitbalans',
'exif-whitebalance-1' => 'Manuell vitbalans',

'exif-scenecapturetype-0' => 'Standard',
'exif-scenecapturetype-1' => 'Landskap',
'exif-scenecapturetype-2' => 'Porträtt',
'exif-scenecapturetype-3' => 'Nattfotografering',

'exif-gaincontrol-0' => 'Ingen',
'exif-gaincontrol-1' => 'Ökning av lågnivåförstärkning',
'exif-gaincontrol-2' => 'Ökning av högnivåförstärkning',
'exif-gaincontrol-3' => 'Sänkning av lågnivåförstärkning',
'exif-gaincontrol-4' => 'Sänkning av högnivåförstärkning',

'exif-contrast-0' => 'Normal',
'exif-contrast-1' => 'Mjuk',
'exif-contrast-2' => 'Skarp',

'exif-saturation-0' => 'Normal',
'exif-saturation-1' => 'Låg mättnadsgrad',
'exif-saturation-2' => 'Hög mättnadsgrad',

'exif-sharpness-0' => 'Normal',
'exif-sharpness-1' => 'Mjuk',
'exif-sharpness-2' => 'Hård',

'exif-subjectdistancerange-0' => 'Okänd',
'exif-subjectdistancerange-1' => 'Makro',
'exif-subjectdistancerange-2' => 'Närbild',
'exif-subjectdistancerange-3' => 'Avståndsbild',

# Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => 'Nordlig latitud',
'exif-gpslatitude-s' => 'Sydlig latitud',

# Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => 'Östlig longitud',
'exif-gpslongitude-w' => 'Västlig longitud',

'exif-gpsstatus-a' => 'Mätning pågår',
'exif-gpsstatus-v' => 'Mätningsinteroperabilitet',

'exif-gpsmeasuremode-2' => 'Tvådimensionell mätning',
'exif-gpsmeasuremode-3' => 'Tredimensionell mätning',

# Pseudotags used for GPSSpeedRef
'exif-gpsspeed-k' => 'Kilometer i timmen',
'exif-gpsspeed-m' => 'Miles i timmen',
'exif-gpsspeed-n' => 'Knop',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'Sann bäring',
'exif-gpsdirection-m' => 'Magnetisk bäring',

# External editor support
'edit-externally'      => 'Redigera denna fil med hjälp av extern programvara',
'edit-externally-help' => '(Se [http://www.mediawiki.org/wiki/Manual:External_editors installationsinstruktionerna] för mer information)',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'alla',
'imagelistall'     => 'alla',
'watchlistall2'    => 'alla',
'namespacesall'    => 'alla',
'monthsall'        => 'alla',
'limitall'         => 'alla',

# E-mail address confirmation
'confirmemail'              => 'Bekräfta e-postadress',
'confirmemail_noemail'      => 'Du har inte angivit någon giltig e-postadress i dina [[Special:Preferences|inställningar]].',
'confirmemail_text'         => 'Innan du kan använda {{SITENAME}}s funktioner för e-post måste du bekräfta din e-postadress. Aktivera knappen nedan för att skicka en bekräftelsekod till din e-postadress. Mailet kommer att innehålla en länk, som innehåller en kod. Genom att klicka på den länken eller kopiera den till din webbläsares fönster för webbadresser, bekräftar du att din e-postadress fungerar.',
'confirmemail_pending'      => 'En bekräftelsekod har redan skickats till din epostadress. Om du skapade ditt konto nyligen, så kanske du vill vänta några minuter innan du begär en ny kod.',
'confirmemail_send'         => 'Skicka bekräftelsekod',
'confirmemail_sent'         => 'E-post med bekräftelse skickat.',
'confirmemail_oncreate'     => 'En bekräftelsekod skickades till din epostadress. Koden behövs inte för att logga in, men du behöver koden för att få tillgång till de epostbaserade funktionerna på wikin.',
'confirmemail_sendfailed'   => '{{SITENAME}} kunde inte skicka din e-postbekräftelse.
Kontrollera om e-postadressen innehåller ogiltiga tecken.

Mailservern svarade: $1',
'confirmemail_invalid'      => 'Ogiltig bekräftelsekod. Dess giltighetstid kan ha löpt ut.',
'confirmemail_needlogin'    => 'Du behöver $1 för att bekräfta din e-postadress',
'confirmemail_success'      => 'Din e-postadress har bekräftats. 
Du kan nu [[Special:UserLogin|logga in]] och använda wikin.',
'confirmemail_loggedin'     => 'Din e-postadress är nu bekräftad.',
'confirmemail_error'        => 'Någonting gick fel när din bekräftelse skulle sparas.',
'confirmemail_subject'      => 'Bekräftelse av e-postadress på {{SITENAME}}',
'confirmemail_body'         => 'Någon, troligen du, har från IP-adressen $1 registrerat användarkontot "$2" med denna e-postadress på {{SITENAME}}.

För att bekräfta att detta konto verkligen är ditt, och för att aktivera funktionerna för e-post på {{SITENAME}}, öppna denna länk i din webbläsare:

$3

Om det *inte* är du som registrerat kontot, följ denna länk för att avbryta bekräftelsen av e-postadressen:

$5

Denna bekräftelsekod kommer inte att fungera efter $4.',
'confirmemail_body_changed' => 'Någon, troligen du, har från IP-adressen $1
registrerat användarkontot "$2" med denna e-postadress på {{SITENAME}}.

För att bekräfta att detta konto verkligen är ditt, och för att aktivera
funktionerna för e-post på {{SITENAME}}, öppna denna länk i din webbläsare:

$3

Om det *inte* är du som registrerat kontot, följ denna länk
för att avbryta bekräftelsen av e-postadressen:

$5

Denna bekräftelsekod kommer inte att fungera efter $4.',
'confirmemail_invalidated'  => 'Bekräftelsen av e-postadressen har ogiltigförklarats',
'invalidateemail'           => 'Avbryt bekräftelse av e-postadress',

# Scary transclusion
'scarytranscludedisabled' => '[Interwiki-inklusion är inte aktiverad]',
'scarytranscludefailed'   => '[Hämtning av mall för $1 misslyckades]',
'scarytranscludetoolong'  => '[För lång URL]',

# Trackbacks
'trackbackbox'      => 'Till denna sida finns följande trackback:<br />
$1',
'trackbackremove'   => '([$1 Ta bort])',
'trackbacklink'     => 'Trackback',
'trackbackdeleteok' => 'Trackback har tagits bort.',

# Delete conflict
'deletedwhileediting' => "'''Varning''': Denna sida raderades efter att du började redigera!",
'confirmrecreate'     => "Användaren [[User:$1|$1]] ([[User talk:$1|diskussion]]) raderade den här sidan efter att du började redigera den med motiveringen:
: ''$2''
Bekräfta att du verkligen vill återskapa sidan.",
'recreate'            => 'Återskapa',

# action=purge
'confirm_purge_button' => 'OK',
'confirm-purge-top'    => 'Rensa denna sidas cache?',
'confirm-purge-bottom' => 'Rensning av en sida tömmer cachen och tvingar fram den senaste versionen.',

# Multipage image navigation
'imgmultipageprev' => '← föregående sida',
'imgmultipagenext' => 'nästa sida →',
'imgmultigo'       => 'Gå',
'imgmultigoto'     => 'Gå till sida $1',

# Table pager
'ascending_abbrev'         => 'stigande',
'descending_abbrev'        => 'fallande',
'table_pager_next'         => 'Nästa sida',
'table_pager_prev'         => 'Föregående sida',
'table_pager_first'        => 'Första sidan',
'table_pager_last'         => 'Sista sidan',
'table_pager_limit'        => 'Visa $1 poster per sida',
'table_pager_limit_submit' => 'Utför',
'table_pager_empty'        => 'Inga resultat',

# Auto-summaries
'autosumm-blank'   => 'Tömde sidan',
'autosumm-replace' => "Ersätter sidans innehåll med '$1'",
'autoredircomment' => 'Omdirigerar till [[$1]]',
'autosumm-new'     => "Skapade sidan med '$1'",

# Size units
'size-bytes'     => '$1 byte',
'size-kilobytes' => '$1 kbyte',
'size-megabytes' => '$1 Mbyte',
'size-gigabytes' => '$1 Gbyte',

# Live preview
'livepreview-loading' => 'Laddar…',
'livepreview-ready'   => 'Laddar… Färdig!',
'livepreview-failed'  => 'Live preview misslyckades!
Pröva vanlig förhandsgranskning istället.',
'livepreview-error'   => 'Lyckades inte ansluta: $1 "$2"
Pröva vanlig förhandsgranskning istället.',

# Friendlier slave lag warnings
'lag-warn-normal' => 'Ändringar under {{PLURAL:$1|den senaste sekunden|de $1 senaste sekunderna}} kanske inte visas i den här listan.',
'lag-warn-high'   => 'På grund av omfattande fördröjning i databasen visas kanske inte ändringar nyare än $1 {{PLURAL:$1|sekund|sekunder}} i den här listan.',

# Watchlist editor
'watchlistedit-numitems'       => 'Din bevakningslista innehåller {{PLURAL:$1|1 sida|$1 sidor}}, utöver diskussionsidor.',
'watchlistedit-noitems'        => 'Din bevakningslista innehåller inga sidor.',
'watchlistedit-normal-title'   => 'Redigera bevakningslista',
'watchlistedit-normal-legend'  => 'Ta bort sidor från bevakningslistan',
'watchlistedit-normal-explain' => 'Titlar på din bevakningslista visas nedan.
För att ta bort en titel, markera rutan bredvid den och klicka på "{{int:Watchlistedit-normal-submit}}".
Du kan också [[Special:Watchlist/raw|redigera listan i råformat]].',
'watchlistedit-normal-submit'  => 'Ta bort sidor',
'watchlistedit-normal-done'    => '{{PLURAL:$1|1 sida|$1 sidor}} togs bort från din bevakningslista:',
'watchlistedit-raw-title'      => 'Redigera bevakningslistan i råformat',
'watchlistedit-raw-legend'     => 'Redigera bevakningslistan i råformat',
'watchlistedit-raw-explain'    => 'Titlar på din bevakningslista visas nedan, och kan redigeras genom att lägga till och ta bort från listan;
en titel per rad.
När du är klar klickar du på "{{int:Watchlistedit-raw-submit}}".
Du kan också [[Special:Watchlist/edit|använda standardeditorn]].',
'watchlistedit-raw-titles'     => 'Sidor:',
'watchlistedit-raw-submit'     => 'Uppdatera bevakningslistan',
'watchlistedit-raw-done'       => 'Din bevakningslista har uppdaterats.',
'watchlistedit-raw-added'      => '{{PLURAL:$1|1 sida|$1 sidor}} lades till:',
'watchlistedit-raw-removed'    => '{{PLURAL:$1|1 sida|$1 sidor}} togs bort:',

# Watchlist editing tools
'watchlisttools-view' => 'Visa relevanta ändringar',
'watchlisttools-edit' => 'Visa och redigera bevakningslistan',
'watchlisttools-raw'  => 'Redigera bevakningslistan i råformat',

# Core parser functions
'unknown_extension_tag' => 'Okänd tagg "$1"',
'duplicate-defaultsort' => 'Varning: Standardsorteringsnyckeln "$2" tar över från den tidigare standardsorteringsnyckeln "$1".',

# Special:Version
'version'                          => 'Version',
'version-extensions'               => 'Installerade programtillägg',
'version-specialpages'             => 'Specialsidor',
'version-parserhooks'              => 'Parsertillägg',
'version-variables'                => 'Variabler',
'version-other'                    => 'Annat',
'version-mediahandlers'            => 'Mediahanterare',
'version-hooks'                    => 'Hakar',
'version-extension-functions'      => 'Tilläggsfunktioner',
'version-parser-extensiontags'     => 'Tilläggstaggar',
'version-parser-function-hooks'    => 'Parserfunktioner',
'version-skin-extension-functions' => 'Skaltilläggsfunktioner',
'version-hook-name'                => 'Namn',
'version-hook-subscribedby'        => 'Används av',
'version-version'                  => '(Version $1)',
'version-license'                  => 'Licens',
'version-software'                 => 'Installerad programvara',
'version-software-product'         => 'Produkt',
'version-software-version'         => 'Version',

# Special:FilePath
'filepath'         => 'Sökväg till fil',
'filepath-page'    => 'Fil:',
'filepath-submit'  => 'Sökväg',
'filepath-summary' => 'Den här sidan ger den fullständiga sökvägen till en fil. Bilder visas i full upplösning i din webbläsare, andra filtyper öppnas direkt i de program som är associerade till dem.

Ange filens namn utan prefixet "{{ns:file}}:".',

# Special:FileDuplicateSearch
'fileduplicatesearch'          => 'Sök efter dubblettfiler',
'fileduplicatesearch-summary'  => 'Sök efter dubblettfiler baserat på filernas hash-värden.

Skriv filnamnet utan prefixet "{{ns:file}}:" .',
'fileduplicatesearch-legend'   => 'Sök efter en dubblettfil',
'fileduplicatesearch-filename' => 'Filnamn:',
'fileduplicatesearch-submit'   => 'Sök',
'fileduplicatesearch-info'     => '$1 × $2 pixel<br />Filstorlek: $3<br />MIME-typ: $4',
'fileduplicatesearch-result-1' => 'Filen "$1" har inga identiska dubbletter.',
'fileduplicatesearch-result-n' => 'Filen "$1" har {{PLURAL:$2|1 identisk dubblett|$2 identiska dubbletter}}.',

# Special:SpecialPages
'specialpages'                   => 'Specialsidor',
'specialpages-note'              => '----
* Normala specialsidor.
* <strong class="mw-specialpagerestricted">Specialsidor med begränsad åtkomst.</strong>',
'specialpages-group-maintenance' => 'Underhållsrapporter',
'specialpages-group-other'       => 'Övriga specialsidor',
'specialpages-group-login'       => 'Inloggning/registrering',
'specialpages-group-changes'     => 'Senaste ändringar och loggar',
'specialpages-group-media'       => 'Filer och uppladdning',
'specialpages-group-users'       => 'Användare och behörigheter',
'specialpages-group-highuse'     => 'Sidor som används mycket',
'specialpages-group-pages'       => 'Listor över sidor',
'specialpages-group-pagetools'   => 'Sidverktyg',
'specialpages-group-wiki'        => 'Information och verktyg för wikin',
'specialpages-group-redirects'   => 'Omdirigerande specialsidor',
'specialpages-group-spam'        => 'Spamverktyg',

# Special:BlankPage
'blankpage'              => 'Tom sida',
'intentionallyblankpage' => 'Denna sida har avsiktligen lämnats tom.',

# External image whitelist
'external_image_whitelist' => '#Lämna den här raden precis som den är<pre>
#Skriv fragment av reguljära uttryck (bara delen som ska vara mellan //) här nedan
#Dessa kommer att jämföras med URL:er för externa (hotlinkade) bilder
#De som matchar kommer att visas som bilder, annars visas bara en länk till bilden
#Rader som börjar med # behandlas som kommentarer
#Detta är skiftläges-okänsligt

#Skriv alla fragment av reguljära uttryck ovanför den här raden. Lämna den här raden precis som den är</pre>',

# Special:Tags
'tags'                    => 'Giltiga ändringsmärken',
'tag-filter'              => 'Filter för [[Special:Tags|märken]]:',
'tag-filter-submit'       => 'Filter',
'tags-title'              => 'Märken',
'tags-intro'              => 'Denna sida listar märkena som mjukvaran kan markera en redigering med, och deras betydelse.',
'tags-tag'                => 'Märkesnamn',
'tags-display-header'     => 'Utseende på listor över ändringar',
'tags-description-header' => 'Full beskrivning av betydelse',
'tags-hitcount-header'    => 'Märkta ändringar',
'tags-edit'               => 'redigering',
'tags-hitcount'           => '$1 {{PLURAL:$1|ändring|ändringar}}',

# Database error messages
'dberr-header'      => 'Den här wikin har ett problem',
'dberr-problems'    => 'Ursäkta! Denna sajt har just nu tekniska problem.',
'dberr-again'       => 'Pröva med att vänta några minuter och ladda om.',
'dberr-info'        => '(Kan inte kontakta databasservern: $1)',
'dberr-usegoogle'   => 'Du kan pröva att söka med Google under tiden.',
'dberr-outofdate'   => 'Observera att deras index av vårt innehåll kan vara föråldrat.',
'dberr-cachederror' => 'Följande är en cachad kopia av den efterfrågade sidan, och kan vara föråldrad.',

# HTML forms
'htmlform-invalid-input'       => 'Det finns problem med din inmatning',
'htmlform-select-badoption'    => 'Värdet du valde är inte ett giltigt alternativ.',
'htmlform-int-invalid'         => 'Värdet du valde är inte ett heltal.',
'htmlform-float-invalid'       => 'Värdet du valde är inte en siffra.',
'htmlform-int-toolow'          => 'Värdet du valde är under minimumet på $1',
'htmlform-int-toohigh'         => 'Värdet du valde är över maximumet på $1',
'htmlform-required'            => 'Det här värdet krävs',
'htmlform-submit'              => 'Spara',
'htmlform-reset'               => 'Ogör ändringar',
'htmlform-selectorother-other' => 'Andra',

);
