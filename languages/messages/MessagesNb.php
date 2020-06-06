<?php
/** Norwegian Bokmål (norsk bokmål)
 *
 * To improve a translation please visit https://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Audun
 * @author Boivie
 * @author Brik
 * @author Byrial
 * @author Cocu
 * @author Danmichaelo
 * @author Dittaeva
 * @author Eirik
 * @author EivindJ
 * @author Event
 * @author Finnrind
 * @author Geitost
 * @author Guaca
 * @author H92
 * @author Haakon K
 * @author Harald Khan
 * @author Jeblad
 * @author Jsoby
 * @author Jóna Þórunn
 * @author Kjetil r
 * @author Kph
 * @author Kph-no
 * @author Laaknor
 * @author Najami
 * @author Nghtwlkr
 * @author Njardarlogar
 * @author Nsaa
 * @author Pcoombe
 * @author Pladask
 * @author Purodha
 * @author Qaqqalik
 * @author Samuelsen
 * @author Simen47
 * @author Simny
 * @author Sjurhamre
 * @author Stigmj
 * @author Teak
 * @author Wouterkoch
 * @author לערי ריינהארט
 */

$fallback = 'nn';

$bookstoreList = [
	'Antikvariat.net' => 'http://www.antikvariat.net/',
	'Frida' => 'http://wo.uio.no/as/WebObjects/frida.woa/wa/fres?action=sok&isbn=$1&visParametre=1&sort=alfabetisk&bs=50',
	'Bibsys' => 'http://ask.bibsys.no/ask/action/result?cmd=&kilde=biblio&fid=isbn&term=$1&op=and&fid=bd&term=&arstall=&sortering=sortdate-&treffPrSide=50',
	'Akademika' => 'http://www.akademika.no/sok.php?ts=4&sok=$1',
	'Haugenbok' => 'http://www.haugenbok.no/resultat.cfm?st=extended&isbn=$1',
	'Amazon.com' => 'https://www.amazon.com/exec/obidos/ISBN=$1'
];

$namespaceNames = [
	NS_MEDIA            => 'Medium',
	NS_SPECIAL          => 'Spesial',
	NS_TALK             => 'Diskusjon',
	NS_USER             => 'Bruker',
	NS_USER_TALK        => 'Brukerdiskusjon',
	NS_PROJECT_TALK     => '$1-diskusjon',
	NS_FILE             => 'Fil',
	NS_FILE_TALK        => 'Fildiskusjon',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki-diskusjon',
	NS_TEMPLATE         => 'Mal',
	NS_TEMPLATE_TALK    => 'Maldiskusjon',
	NS_HELP             => 'Hjelp',
	NS_HELP_TALK        => 'Hjelpdiskusjon',
	NS_CATEGORY         => 'Kategori',
	NS_CATEGORY_TALK    => 'Kategoridiskusjon',
];

$namespaceAliases = [
	'Bilde' => NS_FILE,
	'Bildediskusjon' => NS_FILE_TALK,
];

$separatorTransformTable = [ ',' => "\u{00A0}", '.' => ',' ];
$linkTrail = '/^([æøåa-z]+)(.*)$/sDu';

$datePreferenceMigrationMap = [
	'default',
	'mdy',
	'dmy',
	'ymd'
];
$defaultDateFormat = 'dmy';

$dateFormats = [
	'mdy time' => 'H:i',
	'mdy date' => 'M j., Y',
	'mdy both' => 'M j., Y "kl." H:i',

	'dmy time' => 'H:i',
	'dmy date' => 'j. M Y',
	'dmy both' => 'j. M Y "kl." H:i',

	'ymd time' => 'H:i',
	'ymd date' => 'Y M j.',
	'ymd both' => 'Y M j. "kl." H:i',
];

$specialPageAliases = [
	'Activeusers'               => [ 'Aktive_brukere' ],
	'Allmessages'               => [ 'Alle_systembeskjeder' ],
	'AllMyUploads'              => [ 'Alle_mine_opplastinger' ],
	'Allpages'                  => [ 'Alle_sider' ],
	'Ancientpages'              => [ 'Gamle_sider' ],
	'Badtitle'                  => [ 'Ugyldig_tittel' ],
	'Blankpage'                 => [ 'Blank_side' ],
	'Block'                     => [ 'Blokker', 'Blokker_IP', 'Blokker_bruker' ],
	'Booksources'               => [ 'Bokkilder' ],
	'BrokenRedirects'           => [ 'Ødelagte_omdirigeringer' ],
	'Categories'                => [ 'Kategorier' ],
	'ChangeEmail'               => [ 'Endre_e-post' ],
	'ChangePassword'            => [ 'Endre_passord', 'TIlbakestill_passord' ],
	'ComparePages'              => [ 'Sammenlign_sider' ],
	'Confirmemail'              => [ 'Bekreft_e-postadresse' ],
	'Contributions'             => [ 'Bidrag' ],
	'CreateAccount'             => [ 'Opprett_konto' ],
	'Deadendpages'              => [ 'Blindveisider' ],
	'DeletedContributions'      => [ 'Slettede_bidrag' ],
	'DoubleRedirects'           => [ 'Doble_omdirigeringer' ],
	'EditWatchlist'             => [ 'Rediger_overvåkningsliste' ],
	'Emailuser'                 => [ 'E-post' ],
	'ExpandTemplates'           => [ 'Utvid_maler' ],
	'Export'                    => [ 'Eksporter' ],
	'Fewestrevisions'           => [ 'Færrest_revisjoner' ],
	'FileDuplicateSearch'       => [ 'Filduplikatsøk' ],
	'Filepath'                  => [ 'Filsti' ],
	'Import'                    => [ 'Importer' ],
	'Invalidateemail'           => [ 'Ugyldiggjøre_e-post' ],
	'JavaScriptTest'            => [ 'Javascript-test' ],
	'BlockList'                 => [ 'Blokkeringsliste', 'IP-blokkeringsliste' ],
	'LinkSearch'                => [ 'Lenkesøk' ],
	'Listadmins'                => [ 'Administratorliste', 'Administratorer' ],
	'Listbots'                  => [ 'Robotliste', 'Liste_over_roboter' ],
	'Listfiles'                 => [ 'Filliste', 'Bildeliste', 'Billedliste' ],
	'Listgrouprights'           => [ 'Grupperettigheter' ],
	'Listredirects'             => [ 'Omdirigeringsliste' ],
	'Listusers'                 => [ 'Brukerliste' ],
	'Lockdb'                    => [ 'Lås_database' ],
	'Log'                       => [ 'Logg', 'Logger' ],
	'Lonelypages'               => [ 'Foreldreløse_sider' ],
	'Longpages'                 => [ 'Lange_sider' ],
	'MergeHistory'              => [ 'Flett_historikk' ],
	'MIMEsearch'                => [ 'MIME-søk' ],
	'Mostcategories'            => [ 'Flest_kategorier' ],
	'Mostimages'                => [ 'Mest_lenkede_filer', 'Flest_filer', 'Flest_bilder' ],
	'Mostinterwikis'            => [ 'Flest_interrwikilenker' ],
	'Mostlinked'                => [ 'Mest_lenkede_sider', 'Mest_lenket' ],
	'Mostlinkedcategories'      => [ 'Mest_lenkede_kategorier', 'Mest_brukte_kategorier' ],
	'Mostlinkedtemplates'       => [ 'Mest_lenkede_maler', 'Mest_brukte_maler' ],
	'Mostrevisions'             => [ 'Flest_revisjoner' ],
	'Movepage'                  => [ 'Flytt_side' ],
	'Mycontributions'           => [ 'Mine_bidrag' ],
	'MyLanguage'                => [ 'Mitt_språk' ],
	'Mypage'                    => [ 'Min_side' ],
	'Mytalk'                    => [ 'Min_diskusjon' ],
	'Myuploads'                 => [ 'Mine_opplastinger' ],
	'Newimages'                 => [ 'Nye_filer', 'Nye_bilder' ],
	'Newpages'                  => [ 'Nye_sider' ],
	'PagesWithProp'             => [ 'Sider_med_egenskap' ],
	'PasswordReset'             => [ 'Nullstill_passord' ],
	'PermanentLink'             => [ 'Permanent_lenke' ],
	'Preferences'               => [ 'Innstillinger' ],
	'Prefixindex'               => [ 'Prefiksindeks' ],
	'Protectedpages'            => [ 'Beskyttede_sider' ],
	'Protectedtitles'           => [ 'Beskyttede_titler' ],
	'Randompage'                => [ 'Tilfeldig', 'Tilfeldig_side' ],
	'RandomInCategory'          => [ 'Tilfeldig_fra_kategori' ],
	'Randomredirect'            => [ 'Tilfeldig_omdirigering' ],
	'Recentchanges'             => [ 'Siste_endringer' ],
	'Recentchangeslinked'       => [ 'Relaterte_endringer' ],
	'Redirect'                  => [ 'Omdirigering' ],
	'Revisiondelete'            => [ 'Revisjonssletting' ],
	'Search'                    => [ 'Søk' ],
	'Shortpages'                => [ 'Korte_sider' ],
	'Specialpages'              => [ 'Spesialsider' ],
	'Statistics'                => [ 'Statistikk' ],
	'Tags'                      => [ 'Tagger' ],
	'TrackingCategories'        => [ 'Sporingskategorier' ],
	'Unblock'                   => [ 'Avblokker' ],
	'Uncategorizedcategories'   => [ 'Ukategoriserte_kategorier' ],
	'Uncategorizedimages'       => [ 'Ukategoriserte_filer', 'Ukategoriserte_bilder' ],
	'Uncategorizedpages'        => [ 'Ukategoriserte_sider' ],
	'Uncategorizedtemplates'    => [ 'Ukategoriserte_maler' ],
	'Undelete'                  => [ 'Gjenopprett' ],
	'Unlockdb'                  => [ 'Åpne_database' ],
	'Unusedcategories'          => [ 'Ubrukte_kategorier' ],
	'Unusedimages'              => [ 'Ubrukte_filer', 'Ubrukte_bilder' ],
	'Unusedtemplates'           => [ 'Ubrukte_maler' ],
	'Unwatchedpages'            => [ 'Uovervåkede_sider' ],
	'Upload'                    => [ 'Last_opp' ],
	'Userlogin'                 => [ 'Logg_inn' ],
	'Userlogout'                => [ 'Logg_ut' ],
	'Userrights'                => [ 'Brukerrettigheter' ],
	'Version'                   => [ 'Versjon' ],
	'Wantedcategories'          => [ 'Ønskede_kategorier' ],
	'Wantedfiles'               => [ 'Ønskede_filer' ],
	'Wantedpages'               => [ 'Ønskede_sider', 'Ødelagte_lenker' ],
	'Wantedtemplates'           => [ 'Ønskede_maler' ],
	'Watchlist'                 => [ 'Overvåkningsliste', 'Overvåkingsliste' ],
	'Whatlinkshere'             => [ 'Lenker_hit' ],
	'Withoutinterwiki'          => [ 'Uten_interwiki' ],
];

$magicWords = [
	'redirect'                  => [ '0', '#OMDIRIGERING', '#REDIRECT' ],
	'notoc'                     => [ '0', '__INGENINNHOLDSFORTEGNELSE__', '__NOTOC__' ],
	'nogallery'                 => [ '0', '__INTETGALLERI__', '__NOGALLERY__' ],
	'forcetoc'                  => [ '0', '__TVINGINNHOLDSFORTEGNELSE__', '__FORCETOC__' ],
	'toc'                       => [ '0', '__INNHOLDSFORTEGNELSE__', '__TOC__' ],
	'noeditsection'             => [ '0', '__INGENSEKSJONSREDIGERING__', '__NOEDITSECTION__' ],
	'currentmonth'              => [ '1', 'NÅVÆRENDEMÅNED', 'NÅVÆRENDEMÅNED2', 'CURRENTMONTH', 'CURRENTMONTH2' ],
	'currentmonth1'             => [ '1', 'NÅVÆRENDEMÅNED1', 'CURRENTMONTH1' ],
	'currentmonthname'          => [ '1', 'NÅVÆRENDEMÅNEDSNAVN', 'CURRENTMONTHNAME' ],
	'currentmonthnamegen'       => [ '1', 'NÅVÆRENDEMÅNEDSNAVNGEN', 'CURRENTMONTHNAMEGEN' ],
	'currentmonthabbrev'        => [ '1', 'NÅVÆRENDEMÅNEDSNAVNKORT', 'CURRENTMONTHABBREV' ],
	'currentday'                => [ '1', 'NÅVÆRENDEDAG', 'CURRENTDAY' ],
	'currentday2'               => [ '1', 'NÅVÆRENDEDAG2', 'CURRENTDAY2' ],
	'currentdayname'            => [ '1', 'NÅVÆRENDEDAGSNAVN', 'CURRENTDAYNAME' ],
	'currentyear'               => [ '1', 'NÅVÆRENDEÅR', 'CURRENTYEAR' ],
	'currenttime'               => [ '1', 'NÅVÆRENDETID', 'CURRENTTIME' ],
	'currenthour'               => [ '1', 'NÅVÆRENDETIME', 'CURRENTHOUR' ],
	'localmonth'                => [ '1', 'LOKALMÅNED', 'LOKALMÅNED2', 'LOCALMONTH', 'LOCALMONTH2' ],
	'localmonth1'               => [ '1', 'LOKALMÅNED1', 'LOCALMONTH1' ],
	'localmonthname'            => [ '1', 'LOKALMÅNEDSNAVN', 'LOCALMONTHNAME' ],
	'localmonthnamegen'         => [ '1', 'LOKALMÅNEDSNAVNGEN', 'LOCALMONTHNAMEGEN' ],
	'localmonthabbrev'          => [ '1', 'LOKALMÅNEDSNAVNKORT', 'LOCALMONTHABBREV' ],
	'localday'                  => [ '1', 'LOKALDAG', 'LOCALDAY' ],
	'localday2'                 => [ '1', 'LOKALDAG2', 'LOCALDAY2' ],
	'localdayname'              => [ '1', 'LOKALDAGSNAVN', 'LOCALDAYNAME' ],
	'localyear'                 => [ '1', 'LOKALTÅR', 'LOCALYEAR' ],
	'localtime'                 => [ '1', 'LOKALTID', 'LOCALTIME' ],
	'localhour'                 => [ '1', 'LOKALTIME', 'LOCALHOUR' ],
	'numberofpages'             => [ '1', 'ANTALLSIDER', 'NUMBEROFPAGES' ],
	'numberofarticles'          => [ '1', 'ANTALLARTIKLER', 'NUMBEROFARTICLES' ],
	'numberoffiles'             => [ '1', 'ANTALLFILER', 'NUMBEROFFILES' ],
	'numberofusers'             => [ '1', 'ANTALLBRUKERE', 'NUMBEROFUSERS' ],
	'numberofactiveusers'       => [ '1', 'ANTALLAKTIVEBRUKERE', 'NUMBEROFACTIVEUSERS' ],
	'numberofedits'             => [ '1', 'ANTALLREDIGERINGER', 'NUMBEROFEDITS' ],
	'pagename'                  => [ '1', 'SIDENAVN', 'PAGENAME' ],
	'pagenamee'                 => [ '1', 'SIDENAVNE', 'PAGENAMEE' ],
	'namespace'                 => [ '1', 'NAVNEROM', 'NAMESPACE' ],
	'namespacee'                => [ '1', 'NAVNEROME', 'NAMESPACEE' ],
	'talkspace'                 => [ '1', 'DISKUSJONSROM', 'TALKSPACE' ],
	'talkspacee'                => [ '1', 'DISKUSJONSROME', 'TALKSPACEE' ],
	'subjectspace'              => [ '1', 'SUBJEKTROM', 'ARTIKKELROM', 'SUBJECTSPACE', 'ARTICLESPACE' ],
	'subjectspacee'             => [ '1', 'SUBJEKTROME', 'ARTIKKELROME', 'SUBJECTSPACEE', 'ARTICLESPACEE' ],
	'fullpagename'              => [ '1', 'FULLTSIDENAVN', 'FULLPAGENAME' ],
	'fullpagenamee'             => [ '1', 'FULLTSIDENAVNE', 'FULLPAGENAMEE' ],
	'subpagename'               => [ '1', 'UNDERSIDENAVN', 'SUBPAGENAME' ],
	'subpagenamee'              => [ '1', 'UNDERSIDENAVNE', 'SUBPAGENAMEE' ],
	'basepagename'              => [ '1', 'GRUNNSIDENAVN', 'BASEPAGENAME' ],
	'basepagenamee'             => [ '1', 'GRUNNSIDENAVNE', 'BASEPAGENAMEE' ],
	'talkpagename'              => [ '1', 'DISKUSJONSSIDENAVN', 'TALKPAGENAME' ],
	'talkpagenamee'             => [ '1', 'DISKUSJONSSIDENAVNE', 'TALKPAGENAMEE' ],
	'subjectpagename'           => [ '1', 'SUBJEKTSIDENAVN', 'ARTIKKELSIDENAVN', 'SUBJECTPAGENAME', 'ARTICLEPAGENAME' ],
	'subjectpagenamee'          => [ '1', 'SUBJEKTSIDENAVNE', 'ARTIKKELSIDENAVNE', 'SUBJECTPAGENAMEE', 'ARTICLEPAGENAMEE' ],
	'img_thumbnail'             => [ '1', 'miniatyr', 'mini', 'thumbnail', 'thumb' ],
	'img_manualthumb'           => [ '1', 'miniatyr=$1', 'mini=$1', 'thumbnail=$1', 'thumb=$1' ],
	'img_right'                 => [ '1', 'høyre', 'right' ],
	'img_left'                  => [ '1', 'venstre', 'left' ],
	'img_none'                  => [ '1', 'ingen', 'none' ],
	'img_center'                => [ '1', 'sentrer', 'senter', 'midtstilt', 'center', 'centre' ],
	'img_framed'                => [ '1', 'ramme', 'framed', 'enframed', 'frame' ],
	'img_frameless'             => [ '1', 'rammeløs', 'ingenramme', 'frameless' ],
	'img_page'                  => [ '1', 'side=$1', 'side $1', 'page=$1', 'page $1' ],
	'img_upright'               => [ '1', 'portrett', 'portrett=$1', 'portrett_$1', 'upright', 'upright=$1', 'upright $1' ],
	'img_baseline'              => [ '1', 'grunnlinje', 'baseline' ],
	'img_top'                   => [ '1', 'topp', 'top' ],
	'img_middle'                => [ '1', 'midt', 'middle' ],
	'img_bottom'                => [ '1', 'bunn', 'bottom' ],
	'img_text_bottom'           => [ '1', 'tekst-bunn', 'text-bottom' ],
	'img_link'                  => [ '1', 'lenke=$1', 'link=$1' ],
	'ns'                        => [ '0', 'NR:', 'NS:' ],
	'localurl'                  => [ '0', 'LOKALURL:', 'LOCALURL:' ],
	'localurle'                 => [ '0', 'LOKALURLE:', 'LOCALURLE:' ],
	'articlepath'               => [ '0', 'ARTIKKELSTI', 'ARTICLEPATH' ],
	'server'                    => [ '0', 'TJENER', 'SERVER' ],
	'servername'                => [ '0', 'TJENERNAVN', 'SERVERNAME' ],
	'scriptpath'                => [ '0', 'SKRIPTSTI', 'SCRIPTPATH' ],
	'stylepath'                 => [ '0', 'STILSTI', 'STYLEPATH' ],
	'grammar'                   => [ '0', 'GRAMMATIKK:', 'GRAMMAR:' ],
	'gender'                    => [ '0', 'KJØNN:', 'GENDER:' ],
	'notitleconvert'            => [ '0', '__INGENTITTELKONVERTERING__', '__NOTITLECONVERT__', '__NOTC__' ],
	'nocontentconvert'          => [ '0', '__INGENINNHOLDSKONVERTERING__', '__NOCONTENTCONVERT__', '__NOCC__' ],
	'currentweek'               => [ '1', 'NÅVÆRENDEUKE', 'CURRENTWEEK' ],
	'currentdow'                => [ '1', 'NÅVÆRENDEUKEDAG', 'CURRENTDOW' ],
	'localweek'                 => [ '1', 'LOKALUKE', 'LOCALWEEK' ],
	'localdow'                  => [ '1', 'LOKALUKEDAG', 'LOCALDOW' ],
	'revisionid'                => [ '1', 'REVISJONSID', 'REVISIONID' ],
	'revisionday'               => [ '1', 'REVISJONSDAG', 'REVISIONDAY' ],
	'revisionday2'              => [ '1', 'REVISJONSDAG2', 'REVISIONDAY2' ],
	'revisionmonth'             => [ '1', 'REVISJONSMÅNED', 'REVISIONMONTH' ],
	'revisionmonth1'            => [ '1', 'REVISJONSMÅNED1', 'REVISIONMONTH1' ],
	'revisionyear'              => [ '1', 'REVISJONSÅR', 'REVISIONYEAR' ],
	'revisiontimestamp'         => [ '1', 'REVISJONSTIDSSTEMPEL', 'REVISIONTIMESTAMP' ],
	'revisionuser'              => [ '1', 'REVISJONSBRUKER', 'REVISIONUSER' ],
	'plural'                    => [ '0', 'FLERTALL:', 'PLURAL:' ],
	'raw'                       => [ '0', 'RÅ:', 'RAW:' ],
	'displaytitle'              => [ '1', 'VISTITTEL', 'DISPLAYTITLE' ],
	'newsectionlink'            => [ '1', '__NYSEKSJONSLENKE__', '__NEWSECTIONLINK__' ],
	'nonewsectionlink'          => [ '1', '__INGENNYSEKSJONSLENKE__', '__NONEWSECTIONLINK__' ],
	'currentversion'            => [ '1', 'NÅVÆRENDEVERSJON', 'CURRENTVERSION' ],
	'currenttimestamp'          => [ '1', 'NÅVÆRENDETIDSSTEMPEL', 'CURRENTTIMESTAMP' ],
	'localtimestamp'            => [ '1', 'LOKALTTIDSSTEMPEL', 'LOCALTIMESTAMP' ],
	'contentlanguage'           => [ '1', 'INNHOLDSSPRÅK', 'CONTENTLANGUAGE', 'CONTENTLANG' ],
	'pagesinnamespace'          => [ '1', 'SIDERINAVNEROM:', 'PAGESINNAMESPACE:', 'PAGESINNS:' ],
	'numberofadmins'            => [ '1', 'ANTALLADMINISTRATORER', 'NUMBEROFADMINS' ],
	'special'                   => [ '0', 'spesial', 'special' ],
	'defaultsort'               => [ '1', 'STANDARDSORTERING', 'DEFAULTSORT:', 'DEFAULTSORTKEY:', 'DEFAULTCATEGORYSORT:' ],
	'filepath'                  => [ '0', 'FILSTI:', 'FILEPATH:' ],
	'hiddencat'                 => [ '1', '__SKJULTKATEGORI__', '__HIDDENCAT__' ],
	'pagesincategory'           => [ '1', 'SIDERIKATEGORI', 'PAGESINCATEGORY', 'PAGESINCAT' ],
	'pagesize'                  => [ '1', 'SIDESTØRRELSE', 'PAGESIZE' ],
	'index'                     => [ '1', '__INDEKSER__', '__INDEX__' ],
	'noindex'                   => [ '1', '__INGENINDEKSERING__', '__NOINDEX__' ],
	'numberingroup'             => [ '1', 'NUMMERIGRUPPE', 'NUMBERINGROUP', 'NUMINGROUP' ],
	'staticredirect'            => [ '1', '__STATISTOMDIRIGERING__', '__STATICREDIRECT__' ],
	'protectionlevel'           => [ '1', 'BESKYTTELSESNIVÅ', 'PROTECTIONLEVEL' ],
	'formatdate'                => [ '0', 'datoformat', 'formatdate', 'dateformat' ],
	'url_path'                  => [ '0', 'STI', 'PATH' ],
	'url_query'                 => [ '0', 'SPØRRING', 'QUERY' ],
];
