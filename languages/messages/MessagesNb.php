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

$bookstoreList = array(
	'Antikvariat.net' => 'http://www.antikvariat.net/',
	'Frida' => 'http://wo.uio.no/as/WebObjects/frida.woa/wa/fres?action=sok&isbn=$1&visParametre=1&sort=alfabetisk&bs=50',
	'Bibsys' => 'http://ask.bibsys.no/ask/action/result?cmd=&kilde=biblio&fid=isbn&term=$1&op=and&fid=bd&term=&arstall=&sortering=sortdate-&treffPrSide=50',
	'Akademika' => 'http://www.akademika.no/sok.php?ts=4&sok=$1',
	'Haugenbok' => 'http://www.haugenbok.no/resultat.cfm?st=extended&isbn=$1',
	'Amazon.com' => 'http://www.amazon.com/exec/obidos/ISBN=$1'
);

$namespaceNames = array(
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
);

$namespaceAliases = array(
	'Bilde' => NS_FILE,
	'Bildediskusjon' => NS_FILE_TALK,
);

$separatorTransformTable = array( ',' => "\xc2\xa0", '.' => ',' );
$linkTrail = '/^([æøåa-z]+)(.*)$/sDu';

$dateFormats = array(
	'mdy time' => 'H:i',
	'mdy date' => 'M j., Y',
	'mdy both' => 'M j., Y "kl." H:i',

	'dmy time' => 'H:i',
	'dmy date' => 'j. M Y',
	'dmy both' => 'j. M Y "kl." H:i',

	'ymd time' => 'H:i',
	'ymd date' => 'Y M j.',
	'ymd both' => 'Y M j. "kl." H:i',
);

$specialPageAliases = array(
	'Activeusers'               => array( 'Aktive_brukere' ),
	'Allmessages'               => array( 'Alle_systembeskjeder' ),
	'AllMyUploads'              => array( 'Alle_mine_opplastinger' ),
	'Allpages'                  => array( 'Alle_sider' ),
	'Ancientpages'              => array( 'Gamle_sider' ),
	'Badtitle'                  => array( 'Ugyldig_tittel' ),
	'Blankpage'                 => array( 'Blank_side' ),
	'Block'                     => array( 'Blokker', 'Blokker_IP', 'Blokker_bruker' ),
	'Booksources'               => array( 'Bokkilder' ),
	'BrokenRedirects'           => array( 'Ødelagte_omdirigeringer' ),
	'Categories'                => array( 'Kategorier' ),
	'ChangeEmail'               => array( 'Endre_e-post' ),
	'ChangePassword'            => array( 'Endre_passord', 'TIlbakestill_passord' ),
	'ComparePages'              => array( 'Sammenlign_sider' ),
	'Confirmemail'              => array( 'Bekreft_e-postadresse' ),
	'Contributions'             => array( 'Bidrag' ),
	'CreateAccount'             => array( 'Opprett_konto' ),
	'Deadendpages'              => array( 'Blindveisider' ),
	'DeletedContributions'      => array( 'Slettede_bidrag' ),
	'DoubleRedirects'           => array( 'Doble_omdirigeringer' ),
	'EditWatchlist'             => array( 'Rediger_overvåkningsliste' ),
	'Emailuser'                 => array( 'E-post' ),
	'ExpandTemplates'           => array( 'Utvid_maler' ),
	'Export'                    => array( 'Eksporter' ),
	'Fewestrevisions'           => array( 'Færrest_revisjoner' ),
	'FileDuplicateSearch'       => array( 'Filduplikatsøk' ),
	'Filepath'                  => array( 'Filsti' ),
	'Import'                    => array( 'Importer' ),
	'Invalidateemail'           => array( 'Ugyldiggjøre_e-post' ),
	'JavaScriptTest'            => array( 'Javascript-test' ),
	'BlockList'                 => array( 'Blokkeringsliste', 'IP-blokkeringsliste' ),
	'LinkSearch'                => array( 'Lenkesøk' ),
	'Listadmins'                => array( 'Administratorliste', 'Administratorer' ),
	'Listbots'                  => array( 'Robotliste', 'Liste_over_roboter' ),
	'Listfiles'                 => array( 'Filliste', 'Bildeliste', 'Billedliste' ),
	'Listgrouprights'           => array( 'Grupperettigheter' ),
	'Listredirects'             => array( 'Omdirigeringsliste' ),
	'Listusers'                 => array( 'Brukerliste' ),
	'Lockdb'                    => array( 'Lås_database' ),
	'Log'                       => array( 'Logg', 'Logger' ),
	'Lonelypages'               => array( 'Foreldreløse_sider' ),
	'Longpages'                 => array( 'Lange_sider' ),
	'MergeHistory'              => array( 'Flett_historikk' ),
	'MIMEsearch'                => array( 'MIME-søk' ),
	'Mostcategories'            => array( 'Flest_kategorier' ),
	'Mostimages'                => array( 'Mest_lenkede_filer', 'Flest_filer', 'Flest_bilder' ),
	'Mostinterwikis'            => array( 'Flest_interrwikilenker' ),
	'Mostlinked'                => array( 'Mest_lenkede_sider', 'Mest_lenket' ),
	'Mostlinkedcategories'      => array( 'Mest_lenkede_kategorier', 'Mest_brukte_kategorier' ),
	'Mostlinkedtemplates'       => array( 'Mest_lenkede_maler', 'Mest_brukte_maler' ),
	'Mostrevisions'             => array( 'Flest_revisjoner' ),
	'Movepage'                  => array( 'Flytt_side' ),
	'Mycontributions'           => array( 'Mine_bidrag' ),
	'MyLanguage'                => array( 'Mitt_språk' ),
	'Mypage'                    => array( 'Min_side' ),
	'Mytalk'                    => array( 'Min_diskusjon' ),
	'Myuploads'                 => array( 'Mine_opplastinger' ),
	'Newimages'                 => array( 'Nye_filer', 'Nye_bilder' ),
	'Newpages'                  => array( 'Nye_sider' ),
	'PagesWithProp'             => array( 'Sider_med_egenskap' ),
	'PasswordReset'             => array( 'Nullstill_passord' ),
	'PermanentLink'             => array( 'Permanent_lenke' ),
	'Preferences'               => array( 'Innstillinger' ),
	'Prefixindex'               => array( 'Prefiksindeks' ),
	'Protectedpages'            => array( 'Beskyttede_sider' ),
	'Protectedtitles'           => array( 'Beskyttede_titler' ),
	'Randompage'                => array( 'Tilfeldig', 'Tilfeldig_side' ),
	'RandomInCategory'          => array( 'Tilfeldig_fra_kategori' ),
	'Randomredirect'            => array( 'Tilfeldig_omdirigering' ),
	'Recentchanges'             => array( 'Siste_endringer' ),
	'Recentchangeslinked'       => array( 'Relaterte_endringer' ),
	'Redirect'                  => array( 'Omdirigering' ),
	'Revisiondelete'            => array( 'Revisjonssletting' ),
	'Search'                    => array( 'Søk' ),
	'Shortpages'                => array( 'Korte_sider' ),
	'Specialpages'              => array( 'Spesialsider' ),
	'Statistics'                => array( 'Statistikk' ),
	'Tags'                      => array( 'Tagger' ),
	'Unblock'                   => array( 'Avblokker' ),
	'Uncategorizedcategories'   => array( 'Ukategoriserte_kategorier' ),
	'Uncategorizedimages'       => array( 'Ukategoriserte_filer', 'Ukategoriserte_bilder' ),
	'Uncategorizedpages'        => array( 'Ukategoriserte_sider' ),
	'Uncategorizedtemplates'    => array( 'Ukategoriserte_maler' ),
	'Undelete'                  => array( 'Gjenopprett' ),
	'Unlockdb'                  => array( 'Åpne_database' ),
	'Unusedcategories'          => array( 'Ubrukte_kategorier' ),
	'Unusedimages'              => array( 'Ubrukte_filer', 'Ubrukte_bilder' ),
	'Unusedtemplates'           => array( 'Ubrukte_maler' ),
	'Unwatchedpages'            => array( 'Uovervåkede_sider' ),
	'Upload'                    => array( 'Last_opp' ),
	'Userlogin'                 => array( 'Logg_inn' ),
	'Userlogout'                => array( 'Logg_ut' ),
	'Userrights'                => array( 'Brukerrettigheter' ),
	'Version'                   => array( 'Versjon' ),
	'Wantedcategories'          => array( 'Ønskede_kategorier' ),
	'Wantedfiles'               => array( 'Ønskede_filer' ),
	'Wantedpages'               => array( 'Ønskede_sider', 'Ødelagte_lenker' ),
	'Wantedtemplates'           => array( 'Ønskede_maler' ),
	'Watchlist'                 => array( 'Overvåkningsliste', 'Overvåkingsliste' ),
	'Whatlinkshere'             => array( 'Lenker_hit' ),
	'Withoutinterwiki'          => array( 'Uten_interwiki' ),
);

$magicWords = array(
	'redirect'                  => array( '0', '#OMDIRIGERING', '#REDIRECT' ),
	'notoc'                     => array( '0', '__INGENINNHOLDSFORTEGNELSE__', '__NOTOC__' ),
	'nogallery'                 => array( '0', '__INTETGALLERI__', '__NOGALLERY__' ),
	'forcetoc'                  => array( '0', '__TVINGINNHOLDSFORTEGNELSE__', '__FORCETOC__' ),
	'toc'                       => array( '0', '__INNHOLDSFORTEGNELSE__', '__TOC__' ),
	'noeditsection'             => array( '0', '__INGENSEKSJONSREDIGERING__', '__NOEDITSECTION__' ),
	'currentmonth'              => array( '1', 'NÅVÆRENDEMÅNED', 'NÅVÆRENDEMÅNED2', 'CURRENTMONTH', 'CURRENTMONTH2' ),
	'currentmonth1'             => array( '1', 'NÅVÆRENDEMÅNED1', 'CURRENTMONTH1' ),
	'currentmonthname'          => array( '1', 'NÅVÆRENDEMÅNEDSNAVN', 'CURRENTMONTHNAME' ),
	'currentmonthnamegen'       => array( '1', 'NÅVÆRENDEMÅNEDSNAVNGEN', 'CURRENTMONTHNAMEGEN' ),
	'currentmonthabbrev'        => array( '1', 'NÅVÆRENDEMÅNEDSNAVNKORT', 'CURRENTMONTHABBREV' ),
	'currentday'                => array( '1', 'NÅVÆRENDEDAG', 'CURRENTDAY' ),
	'currentday2'               => array( '1', 'NÅVÆRENDEDAG2', 'CURRENTDAY2' ),
	'currentdayname'            => array( '1', 'NÅVÆRENDEDAGSNAVN', 'CURRENTDAYNAME' ),
	'currentyear'               => array( '1', 'NÅVÆRENDEÅR', 'CURRENTYEAR' ),
	'currenttime'               => array( '1', 'NÅVÆRENDETID', 'CURRENTTIME' ),
	'currenthour'               => array( '1', 'NÅVÆRENDETIME', 'CURRENTHOUR' ),
	'localmonth'                => array( '1', 'LOKALMÅNED', 'LOKALMÅNED2', 'LOCALMONTH', 'LOCALMONTH2' ),
	'localmonth1'               => array( '1', 'LOKALMÅNED1', 'LOCALMONTH1' ),
	'localmonthname'            => array( '1', 'LOKALMÅNEDSNAVN', 'LOCALMONTHNAME' ),
	'localmonthnamegen'         => array( '1', 'LOKALMÅNEDSNAVNGEN', 'LOCALMONTHNAMEGEN' ),
	'localmonthabbrev'          => array( '1', 'LOKALMÅNEDSNAVNKORT', 'LOCALMONTHABBREV' ),
	'localday'                  => array( '1', 'LOKALDAG', 'LOCALDAY' ),
	'localday2'                 => array( '1', 'LOKALDAG2', 'LOCALDAY2' ),
	'localdayname'              => array( '1', 'LOKALDAGSNAVN', 'LOCALDAYNAME' ),
	'localyear'                 => array( '1', 'LOKALTÅR', 'LOCALYEAR' ),
	'localtime'                 => array( '1', 'LOKALTID', 'LOCALTIME' ),
	'localhour'                 => array( '1', 'LOKALTIME', 'LOCALHOUR' ),
	'numberofpages'             => array( '1', 'ANTALLSIDER', 'NUMBEROFPAGES' ),
	'numberofarticles'          => array( '1', 'ANTALLARTIKLER', 'NUMBEROFARTICLES' ),
	'numberoffiles'             => array( '1', 'ANTALLFILER', 'NUMBEROFFILES' ),
	'numberofusers'             => array( '1', 'ANTALLBRUKERE', 'NUMBEROFUSERS' ),
	'numberofactiveusers'       => array( '1', 'ANTALLAKTIVEBRUKERE', 'NUMBEROFACTIVEUSERS' ),
	'numberofedits'             => array( '1', 'ANTALLREDIGERINGER', 'NUMBEROFEDITS' ),
	'pagename'                  => array( '1', 'SIDENAVN', 'PAGENAME' ),
	'pagenamee'                 => array( '1', 'SIDENAVNE', 'PAGENAMEE' ),
	'namespace'                 => array( '1', 'NAVNEROM', 'NAMESPACE' ),
	'namespacee'                => array( '1', 'NAVNEROME', 'NAMESPACEE' ),
	'talkspace'                 => array( '1', 'DISKUSJONSROM', 'TALKSPACE' ),
	'talkspacee'                => array( '1', 'DISKUSJONSROME', 'TALKSPACEE' ),
	'subjectspace'              => array( '1', 'SUBJEKTROM', 'ARTIKKELROM', 'SUBJECTSPACE', 'ARTICLESPACE' ),
	'subjectspacee'             => array( '1', 'SUBJEKTROME', 'ARTIKKELROME', 'SUBJECTSPACEE', 'ARTICLESPACEE' ),
	'fullpagename'              => array( '1', 'FULLTSIDENAVN', 'FULLPAGENAME' ),
	'fullpagenamee'             => array( '1', 'FULLTSIDENAVNE', 'FULLPAGENAMEE' ),
	'subpagename'               => array( '1', 'UNDERSIDENAVN', 'SUBPAGENAME' ),
	'subpagenamee'              => array( '1', 'UNDERSIDENAVNE', 'SUBPAGENAMEE' ),
	'basepagename'              => array( '1', 'GRUNNSIDENAVN', 'BASEPAGENAME' ),
	'basepagenamee'             => array( '1', 'GRUNNSIDENAVNE', 'BASEPAGENAMEE' ),
	'talkpagename'              => array( '1', 'DISKUSJONSSIDENAVN', 'TALKPAGENAME' ),
	'talkpagenamee'             => array( '1', 'DISKUSJONSSIDENAVNE', 'TALKPAGENAMEE' ),
	'subjectpagename'           => array( '1', 'SUBJEKTSIDENAVN', 'ARTIKKELSIDENAVN', 'SUBJECTPAGENAME', 'ARTICLEPAGENAME' ),
	'subjectpagenamee'          => array( '1', 'SUBJEKTSIDENAVNE', 'ARTIKKELSIDENAVNE', 'SUBJECTPAGENAMEE', 'ARTICLEPAGENAMEE' ),
	'img_thumbnail'             => array( '1', 'miniatyr', 'mini', 'thumbnail', 'thumb' ),
	'img_manualthumb'           => array( '1', 'miniatyr=$1', 'mini=$1', 'thumbnail=$1', 'thumb=$1' ),
	'img_right'                 => array( '1', 'høyre', 'right' ),
	'img_left'                  => array( '1', 'venstre', 'left' ),
	'img_none'                  => array( '1', 'ingen', 'none' ),
	'img_center'                => array( '1', 'sentrer', 'senter', 'midtstilt', 'center', 'centre' ),
	'img_framed'                => array( '1', 'ramme', 'framed', 'enframed', 'frame' ),
	'img_frameless'             => array( '1', 'rammeløs', 'ingenramme', 'frameless' ),
	'img_page'                  => array( '1', 'side=$1', 'side $1', 'page=$1', 'page $1' ),
	'img_upright'               => array( '1', 'portrett', 'portrett=$1', 'portrett_$1', 'upright', 'upright=$1', 'upright $1' ),
	'img_baseline'              => array( '1', 'grunnlinje', 'baseline' ),
	'img_top'                   => array( '1', 'topp', 'top' ),
	'img_middle'                => array( '1', 'midt', 'middle' ),
	'img_bottom'                => array( '1', 'bunn', 'bottom' ),
	'img_text_bottom'           => array( '1', 'tekst-bunn', 'text-bottom' ),
	'img_link'                  => array( '1', 'lenke=$1', 'link=$1' ),
	'ns'                        => array( '0', 'NR:', 'NS:' ),
	'localurl'                  => array( '0', 'LOKALURL:', 'LOCALURL:' ),
	'localurle'                 => array( '0', 'LOKALURLE:', 'LOCALURLE:' ),
	'articlepath'               => array( '0', 'ARTIKKELSTI', 'ARTICLEPATH' ),
	'server'                    => array( '0', 'TJENER', 'SERVER' ),
	'servername'                => array( '0', 'TJENERNAVN', 'SERVERNAME' ),
	'scriptpath'                => array( '0', 'SKRIPTSTI', 'SCRIPTPATH' ),
	'stylepath'                 => array( '0', 'STILSTI', 'STYLEPATH' ),
	'grammar'                   => array( '0', 'GRAMMATIKK:', 'GRAMMAR:' ),
	'gender'                    => array( '0', 'KJØNN:', 'GENDER:' ),
	'notitleconvert'            => array( '0', '__INGENTITTELKONVERTERING__', '__NOTITLECONVERT__', '__NOTC__' ),
	'nocontentconvert'          => array( '0', '__INGENINNHOLDSKONVERTERING__', '__NOCONTENTCONVERT__', '__NOCC__' ),
	'currentweek'               => array( '1', 'NÅVÆRENDEUKE', 'CURRENTWEEK' ),
	'currentdow'                => array( '1', 'NÅVÆRENDEUKEDAG', 'CURRENTDOW' ),
	'localweek'                 => array( '1', 'LOKALUKE', 'LOCALWEEK' ),
	'localdow'                  => array( '1', 'LOKALUKEDAG', 'LOCALDOW' ),
	'revisionid'                => array( '1', 'REVISJONSID', 'REVISIONID' ),
	'revisionday'               => array( '1', 'REVISJONSDAG', 'REVISIONDAY' ),
	'revisionday2'              => array( '1', 'REVISJONSDAG2', 'REVISIONDAY2' ),
	'revisionmonth'             => array( '1', 'REVISJONSMÅNED', 'REVISIONMONTH' ),
	'revisionmonth1'            => array( '1', 'REVISJONSMÅNED1', 'REVISIONMONTH1' ),
	'revisionyear'              => array( '1', 'REVISJONSÅR', 'REVISIONYEAR' ),
	'revisiontimestamp'         => array( '1', 'REVISJONSTIDSSTEMPEL', 'REVISIONTIMESTAMP' ),
	'revisionuser'              => array( '1', 'REVISJONSBRUKER', 'REVISIONUSER' ),
	'plural'                    => array( '0', 'FLERTALL:', 'PLURAL:' ),
	'raw'                       => array( '0', 'RÅ:', 'RAW:' ),
	'displaytitle'              => array( '1', 'VISTITTEL', 'DISPLAYTITLE' ),
	'newsectionlink'            => array( '1', '__NYSEKSJONSLENKE__', '__NEWSECTIONLINK__' ),
	'nonewsectionlink'          => array( '1', '__INGENNYSEKSJONSLENKE__', '__NONEWSECTIONLINK__' ),
	'currentversion'            => array( '1', 'NÅVÆRENDEVERSJON', 'CURRENTVERSION' ),
	'currenttimestamp'          => array( '1', 'NÅVÆRENDETIDSSTEMPEL', 'CURRENTTIMESTAMP' ),
	'localtimestamp'            => array( '1', 'LOKALTTIDSSTEMPEL', 'LOCALTIMESTAMP' ),
	'contentlanguage'           => array( '1', 'INNHOLDSSPRÅK', 'CONTENTLANGUAGE', 'CONTENTLANG' ),
	'pagesinnamespace'          => array( '1', 'SIDERINAVNEROM:', 'PAGESINNAMESPACE:', 'PAGESINNS:' ),
	'numberofadmins'            => array( '1', 'ANTALLADMINISTRATORER', 'NUMBEROFADMINS' ),
	'special'                   => array( '0', 'spesial', 'special' ),
	'defaultsort'               => array( '1', 'STANDARDSORTERING', 'DEFAULTSORT:', 'DEFAULTSORTKEY:', 'DEFAULTCATEGORYSORT:' ),
	'filepath'                  => array( '0', 'FILSTI:', 'FILEPATH:' ),
	'hiddencat'                 => array( '1', '__SKJULTKATEGORI__', '__HIDDENCAT__' ),
	'pagesincategory'           => array( '1', 'SIDERIKATEGORI', 'PAGESINCATEGORY', 'PAGESINCAT' ),
	'pagesize'                  => array( '1', 'SIDESTØRRELSE', 'PAGESIZE' ),
	'index'                     => array( '1', '__INDEKSER__', '__INDEX__' ),
	'noindex'                   => array( '1', '__INGENINDEKSERING__', '__NOINDEX__' ),
	'numberingroup'             => array( '1', 'NUMMERIGRUPPE', 'NUMBERINGROUP', 'NUMINGROUP' ),
	'staticredirect'            => array( '1', '__STATISTOMDIRIGERING__', '__STATICREDIRECT__' ),
	'protectionlevel'           => array( '1', 'BESKYTTELSESNIVÅ', 'PROTECTIONLEVEL' ),
	'formatdate'                => array( '0', 'datoformat', 'formatdate', 'dateformat' ),
	'url_path'                  => array( '0', 'STI', 'PATH' ),
	'url_query'                 => array( '0', 'SPØRRING', 'QUERY' ),
);

