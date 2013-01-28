<?php
/** Norwegian Nynorsk (norsk (nynorsk)‎)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Boivie
 * @author Cocu
 * @author Dittaeva
 * @author Diupwijk
 * @author Eirik
 * @author Finnrind
 * @author Frokor
 * @author Geitost
 * @author Gunnernett
 * @author Guttorm Flatabø
 * @author H92
 * @author Harald Khan
 * @author Jon Harald Søby
 * @author Jorunn
 * @author Kaganer
 * @author Marinsb
 * @author Najami
 * @author Nghtwlkr
 * @author Njardarlogar
 * @author Olve Utne
 * @author Ranveig
 * @author Shauni
 * @author Urhixidur
 * @author לערי ריינהארט
 */

/**
  * @license http://www.gnu.org/copyleft/fdl.html GNU Free Documentation License
  * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License
  *
  * @see http://meta.wikimedia.org/w/index.php?title=LanguageNn.php&action=history
  * @see http://nn.wikipedia.org/w/index.php?title=Brukar:Dittaeva/LanguageNn.php&action=history
  */


$datePreferences = array(
	'default',
	'dmyt',
	'short dmyt',
	'tdmy',
	'short dmyt',
	'ISO 8601',
);

$datePreferenceMigrationMap = array(
	'default',
	'dmyt',
	'short dmyt',
	'tdmy',
	'short tdmy',
);

$defaultDateFormat = 'dmyt';

$dateFormats = array(
	/*
	'Standard',
	'15. januar 2001 kl. 16:12',
	'15. jan. 2001 kl. 16:12',
	'16:12, 15. januar 2001',
	'16:12, 15. jan. 2001',
	'ISO 8601' => '2001-01-15 16:12:34'
 */
	'dmyt time' => 'H:i',
	'dmyt date' => 'j. F Y',
	'dmyt both' => 'j. F Y "kl." H:i',

	'short dmyt time' => 'H:i',
	'short dmyt date' => 'j. M. Y',
	'short dmyt both' => 'j. M. Y "kl." H:i',

	'tdmy time' => 'H:i',
	'tdmy date' => 'j. F Y',
	'tdmy both' => 'H:i, j. F Y',

	'short tdmy time' => 'H:i',
	'short tdmy date' => 'j. M. Y',
	'short tdmy both' => 'H:i, j. M. Y',
);

$bookstoreList = array(
	'Bibsys'       => 'http://ask.bibsys.no/ask/action/result?kilde=biblio&fid=isbn&lang=nn&term=$1',
	'BokBerit'     => 'http://www.bokberit.no/annet_sted/bocker/$1.html',
	'Bokkilden'    => 'http://www.bokkilden.no/ProductDetails.aspx?ProductId=$1',
	'Haugenbok'    => 'http://www.haugenbok.no/resultat.cfm?st=hurtig&isbn=$1',
	'Akademika'    => 'http://www.akademika.no/sok.php?isbn=$1',
	'Gnist'        => 'http://www.gnist.no/sok.php?isbn=$1',
	'Amazon.co.uk' => 'http://www.amazon.co.uk/exec/obidos/ISBN=$1',
	'Amazon.de'    => 'http://www.amazon.de/exec/obidos/ISBN=$1',
	'Amazon.com'   => 'http://www.amazon.com/exec/obidos/ISBN=$1'
);

$magicWords = array(
	'redirect'                  => array( '0', '#omdiriger', '#REDIRECT' ),
	'notoc'                     => array( '0', '__INGAINNHALDSLISTE__', '__INGENINNHOLDSLISTE__', '__NOTOC__' ),
	'nogallery'                 => array( '0', '__INKJEGALLERI__', '__NOGALLERY__' ),
	'forcetoc'                  => array( '0', '__ALLTIDINNHALDSLISTE__', '__ALLTIDINNHOLDSLISTE__', '__FORCETOC__' ),
	'toc'                       => array( '0', '__INNHALDSLISTE__', '__INNHOLDSLISTE__', '__TOC__' ),
	'noeditsection'             => array( '0', '__INGABOLKENDRING__', '__INGABOLKREDIGERING__', '__INGENDELENDRING__', '__NOEDITSECTION__' ),
	'currentmonth'              => array( '1', 'MÅNADNO', 'MÅNEDNÅ', 'CURRENTMONTH', 'CURRENTMONTH2' ),
	'currentmonthname'          => array( '1', 'MÅNADNONAMN', 'MÅNEDNÅNAVN', 'CURRENTMONTHNAME' ),
	'currentmonthabbrev'        => array( '1', 'MÅNADNOKORT', 'MÅNEDNÅKORT', 'CURRENTMONTHABBREV' ),
	'currentday'                => array( '1', 'DAGNO', 'DAGNÅ', 'CURRENTDAY' ),
	'currentday2'               => array( '1', 'DAGNO2', 'DAGNÅ2', 'CURRENTDAY2' ),
	'currentdayname'            => array( '1', 'DAGNONAMN', 'DAGNÅNAVN', 'CURRENTDAYNAME' ),
	'currentyear'               => array( '1', 'ÅRNO', 'ÅRNÅ', 'CURRENTYEAR' ),
	'currenttime'               => array( '1', 'TIDNO', 'TIDNÅ', 'CURRENTTIME' ),
	'currenthour'               => array( '1', 'TIMENO', 'CURRENTHOUR' ),
	'numberofpages'             => array( '1', 'SIDETAL', 'NUMBEROFPAGES' ),
	'numberofarticles'          => array( '1', 'INNHALDSSIDETAL', 'INNHOLDSSIDETALL', 'NUMBEROFARTICLES' ),
	'numberoffiles'             => array( '1', 'FILTAL', 'NUMBEROFFILES' ),
	'numberofusers'             => array( '1', 'BRUKARTAL', 'NUMBEROFUSERS' ),
	'numberofactiveusers'       => array( '1', 'AKTIVEBRUKARAR', 'NUMBEROFACTIVEUSERS' ),
	'numberofedits'             => array( '1', 'ENDRINGSTAL', 'NUMBEROFEDITS' ),
	'numberofviews'             => array( '1', 'VISINGSTAL', 'TALPÅVISINGAR', 'NUMBEROFVIEWS' ),
	'pagename'                  => array( '1', 'SIDENAMN', 'SIDENAVN', 'PAGENAME' ),
	'pagenamee'                 => array( '1', 'SIDENAMNE', 'SIDENAVNE', 'PAGENAMEE' ),
	'namespace'                 => array( '1', 'NAMNEROM', 'NAVNEROM', 'NAMESPACE' ),
	'fullpagename'              => array( '1', 'FULLTSIDENAMN', 'FULLPAGENAME' ),
	'subpagename'               => array( '1', 'UNDERSIDENAMN', 'SUBPAGENAME' ),
	'basepagename'              => array( '1', 'HOVUDSIDENAMN', 'BASEPAGENAME' ),
	'talkpagename'              => array( '1', 'DISKUSJONSSIDENAMN', 'TALKPAGENAME' ),
	'msg'                       => array( '0', 'MLD:', 'MSG:' ),
	'subst'                     => array( '0', 'LIMINN:', 'SUBST:' ),
	'safesubst'                 => array( '0', 'TRYGGLIMINN:', 'SAFESUBST:' ),
	'msgnw'                     => array( '0', 'IKWIKMELD:', 'MSGNW:' ),
	'img_thumbnail'             => array( '1', 'mini', 'miniatyr', 'thumbnail', 'thumb' ),
	'img_manualthumb'           => array( '1', 'mini=$1', 'miniatyr=$1', 'thumbnail=$1', 'thumb=$1' ),
	'img_right'                 => array( '1', 'høgre', 'høyre', 'right' ),
	'img_left'                  => array( '1', 'venstre', 'left' ),
	'img_none'                  => array( '1', 'ingen', 'none' ),
	'img_width'                 => array( '1', '$1pk', '$1px' ),
	'img_center'                => array( '1', 'sentrum', 'center', 'centre' ),
	'img_framed'                => array( '1', 'ramme', 'ramma', 'framed', 'enframed', 'frame' ),
	'img_frameless'             => array( '1', 'rammelaus', 'frameless' ),
	'img_page'                  => array( '1', 'side=$1', 'side_$1', 'page=$1', 'page $1' ),
	'img_link'                  => array( '1', 'lenkje=$1', 'lenkja=$1', 'link=$1' ),
	'sitename'                  => array( '1', 'NETTSTADNAMN', 'SITENAME' ),
	'ns'                        => array( '0', 'NR:', 'NS:' ),
	'localurl'                  => array( '0', 'LOKALLENKJE:', 'LOKALLENKE:', 'LOCALURL:' ),
	'localurle'                 => array( '0', 'LOKALLENKJEE:', 'LOKALLENKEE:', 'LOCALURLE:' ),
	'articlepath'               => array( '0', 'ARTIKKELSTIG', 'ARTICLEPATH' ),
	'pageid'                    => array( '0', 'SIDEID', 'PAGEID' ),
	'server'                    => array( '0', 'TENAR', 'TJENER', 'SERVER' ),
	'servername'                => array( '0', 'TENARNAMN', 'TJENERNAVN', 'SERVERNAME' ),
	'scriptpath'                => array( '0', 'SKRIPTSTIG', 'SKRIPTSTI', 'SCRIPTPATH' ),
	'grammar'                   => array( '0', 'GRAMMATIKK:', 'GRAMMAR:' ),
	'gender'                    => array( '0', 'KJØNN:', 'GENDER:' ),
	'currentweek'               => array( '1', 'VEKENRNO', 'UKENRNÅ', 'CURRENTWEEK' ),
	'currentdow'                => array( '1', 'VEKEDAGNRNO', 'UKEDAGNRNÅ', 'CURRENTDOW' ),
	'localweek'                 => array( '1', 'LOKALVEKE', 'LOCALWEEK' ),
	'revisionid'                => array( '1', 'VERSJONSID', 'REVISIONID' ),
	'revisionday'               => array( '1', 'VERSJONSDAG', 'REVISIONDAY' ),
	'revisionday2'              => array( '1', 'VERSJONSDAG2', 'REVISIONDAY2' ),
	'revisionmonth'             => array( '1', 'VERSJONSMÅNAD', 'REVISIONMONTH' ),
	'revisionmonth1'            => array( '1', 'VERSJONSMÅNAD1', 'REVISIONMONTH1' ),
	'revisionyear'              => array( '1', 'VERSJONSÅR', 'REVISIONYEAR' ),
	'revisiontimestamp'         => array( '1', 'VERSJONSTIDSTEMPEL', 'REVISIONTIMESTAMP' ),
	'revisionuser'              => array( '1', 'VERSJONSBRUKAR', 'REVISIONUSER' ),
	'plural'                    => array( '0', 'FLEIRTAL:', 'PLURAL:' ),
	'lcfirst'                   => array( '0', 'LFYRST:', 'LFØRST:', 'LCFIRST:' ),
	'ucfirst'                   => array( '0', 'SFYRST:', 'SFØRST:', 'UCFIRST:' ),
	'lc'                        => array( '0', 'SMÅ:', 'LC:' ),
	'uc'                        => array( '0', 'STORE:', 'UC:' ),
	'displaytitle'              => array( '1', 'VISTITTEL', 'DISPLAYTITLE' ),
	'currentversion'            => array( '1', 'VERSJONNO', 'CURRENTVERSION' ),
	'language'                  => array( '0', '#SPRÅK:', '#LANGUAGE:' ),
	'contentlanguage'           => array( '1', 'INNHALDSSPRÅK', 'CONTENTLANGUAGE', 'CONTENTLANG' ),
	'pagesinnamespace'          => array( '1', 'SIDERINAMNEROM', 'PAGESINNAMESPACE:', 'PAGESINNS:' ),
	'numberofadmins'            => array( '1', 'ADMINTAL', 'ADMINISTRATORTAL', 'NUMBEROFADMINS' ),
	'formatnum'                 => array( '0', 'FORMATTAL', 'FORMATNUM' ),
	'special'                   => array( '0', 'spesial', 'special' ),
	'filepath'                  => array( '0', 'FILSTIG', 'FILEPATH:' ),
	'tag'                       => array( '0', 'merke', 'tag' ),
	'hiddencat'                 => array( '1', '__GØYMDKAT__', '__LØYNDKAT__', '__HIDDENCAT__' ),
	'pagesincategory'           => array( '1', 'SIDERIKAT', 'SIDERIKATEGORI', 'PAGESINCATEGORY', 'PAGESINCAT' ),
	'pagesize'                  => array( '1', 'SIDESTORLEIK', 'PAGESIZE' ),
	'protectionlevel'           => array( '1', 'VERNENIVÅ', 'PROTECTIONLEVEL' ),
	'formatdate'                => array( '0', 'datoformat', 'formatdate', 'dateformat' ),
	'url_path'                  => array( '0', 'STIG', 'PATH' ),
	'pagesincategory_all'       => array( '0', 'alle', 'all' ),
	'pagesincategory_pages'     => array( '0', 'sider', 'pages' ),
	'pagesincategory_subcats'   => array( '0', 'underkategoriar', 'subcats' ),
	'pagesincategory_files'     => array( '0', 'filer', 'files' ),
);

$namespaceNames = array(
	NS_MEDIA            => 'Filpeikar',
	NS_SPECIAL          => 'Spesial',
	NS_TALK             => 'Diskusjon',
	NS_USER             => 'Brukar',
	NS_USER_TALK        => 'Brukardiskusjon',
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

$specialPageAliases = array(
	'Allmessages'               => array( 'Alle_systemmeldingar' ),
	'Allpages'                  => array( 'Alle_sider' ),
	'Ancientpages'              => array( 'Gamle_sider' ),
	'Blankpage'                 => array( 'Tom_side' ),
	'Block'                     => array( 'Blokker' ),
	'Blockme'                   => array( 'Blokker_meg' ),
	'Booksources'               => array( 'Bokkjelder' ),
	'BrokenRedirects'           => array( 'Blindvegsomdirigeringar' ),
	'Categories'                => array( 'Kategoriar' ),
	'ChangePassword'            => array( 'Nullstill_passord' ),
	'Confirmemail'              => array( 'Stadfest_e-postadresse' ),
	'Contributions'             => array( 'Bidrag' ),
	'CreateAccount'             => array( 'Opprett_konto' ),
	'Deadendpages'              => array( 'Blindvegsider' ),
	'DeletedContributions'      => array( 'Sletta_brukarbidrag' ),
	'Disambiguations'           => array( 'Fleirtydingssider' ),
	'DoubleRedirects'           => array( 'Doble_omdirigeringar' ),
	'Emailuser'                 => array( 'E-post' ),
	'Export'                    => array( 'Eksport' ),
	'Fewestrevisions'           => array( 'Færrast_endringar' ),
	'FileDuplicateSearch'       => array( 'Filduplikatsøk' ),
	'Filepath'                  => array( 'Filstig', 'Filsti' ),
	'Import'                    => array( 'Importer' ),
	'Invalidateemail'           => array( 'Gjer_e-post_ugyldig' ),
	'BlockList'                 => array( 'Blokkeringsliste' ),
	'LinkSearch'                => array( 'Lenkjesøk' ),
	'Listadmins'                => array( 'Administratorliste', 'Administratorar' ),
	'Listbots'                  => array( 'Bottliste', 'Bottar' ),
	'Listfiles'                 => array( 'Filliste' ),
	'Listgrouprights'           => array( 'Grupperettar' ),
	'Listredirects'             => array( 'Omdirigeringsliste' ),
	'Listusers'                 => array( 'Brukarliste' ),
	'Lockdb'                    => array( 'Lås_database' ),
	'Log'                       => array( 'Logg', 'Loggar' ),
	'Lonelypages'               => array( 'Foreldrelause_sider' ),
	'Longpages'                 => array( 'Lange_sider' ),
	'MergeHistory'              => array( 'Flettehistorie' ),
	'MIMEsearch'                => array( 'MIME-søk' ),
	'Mostcategories'            => array( 'Flest_kategoriar' ),
	'Mostimages'                => array( 'Mest_brukte_filer' ),
	'Mostlinked'                => array( 'Mest_lenka_sider' ),
	'Mostlinkedcategories'      => array( 'Mest_brukte_kategoriar' ),
	'Mostlinkedtemplates'       => array( 'Mest_brukte_malar' ),
	'Mostrevisions'             => array( 'Flest_endringar' ),
	'Movepage'                  => array( 'Flytt_side' ),
	'Mycontributions'           => array( 'Bidraga_mine' ),
	'Mypage'                    => array( 'Sida_mi' ),
	'Mytalk'                    => array( 'Diskusjonssida_mi' ),
	'Myuploads'                 => array( 'Opplastingane_mine' ),
	'Newimages'                 => array( 'Nye_filer' ),
	'Newpages'                  => array( 'Nye_sider' ),
	'PermanentLink'             => array( 'Permanent_lenkje' ),
	'Popularpages'              => array( 'Populære_sider' ),
	'Preferences'               => array( 'Innstillingar' ),
	'Prefixindex'               => array( 'Prefiksindeks' ),
	'Protectedpages'            => array( 'Verna_sider' ),
	'Protectedtitles'           => array( 'Verna_sidenamn' ),
	'Randompage'                => array( 'Tilfeldig_side' ),
	'Randomredirect'            => array( 'Tilfeldig_omdirigering' ),
	'Recentchanges'             => array( 'Siste_endringar' ),
	'Recentchangeslinked'       => array( 'Relaterte_endringar' ),
	'Revisiondelete'            => array( 'Versjonssletting' ),
	'Search'                    => array( 'Søk' ),
	'Shortpages'                => array( 'Korte_sider' ),
	'Specialpages'              => array( 'Spesialsider' ),
	'Statistics'                => array( 'Statistikk' ),
	'Tags'                      => array( 'Merke' ),
	'Uncategorizedcategories'   => array( 'Ukategoriserte_kategoriar' ),
	'Uncategorizedimages'       => array( 'Ukategoriserte_filer' ),
	'Uncategorizedpages'        => array( 'Ukategoriserte_sider' ),
	'Uncategorizedtemplates'    => array( 'Ukategoriserte_malar' ),
	'Undelete'                  => array( 'Attopprett' ),
	'Unlockdb'                  => array( 'Opne_database' ),
	'Unusedcategories'          => array( 'Ubrukte_kategoriar' ),
	'Unusedimages'              => array( 'Ubrukte_filer' ),
	'Unusedtemplates'           => array( 'Ubrukte_malar' ),
	'Unwatchedpages'            => array( 'Uovervaka_sider' ),
	'Upload'                    => array( 'Last_opp' ),
	'Userlogin'                 => array( 'Logg_inn' ),
	'Userlogout'                => array( 'Logg_ut' ),
	'Userrights'                => array( 'Brukarrettar' ),
	'Version'                   => array( 'Versjon' ),
	'Wantedcategories'          => array( 'Etterspurde_kategoriar' ),
	'Wantedfiles'               => array( 'Etterspurde_filer' ),
	'Wantedpages'               => array( 'Etterspurde_sider' ),
	'Wantedtemplates'           => array( 'Etterspurde_malar' ),
	'Watchlist'                 => array( 'Overvakingsliste' ),
	'Whatlinkshere'             => array( 'Lenkjer_hit' ),
	'Withoutinterwiki'          => array( 'Utan_interwiki' ),
);

$separatorTransformTable = array(
	',' => "\xc2\xa0",
	'.' => ','
);
$linkTrail = '/^([æøåa-z]+)(.*)$/sDu';

$messages = array(
# User preference toggles
'tog-underline' => 'Strek under lenkjer:',
'tog-justify' => 'Blokkjusterte avsnitt',
'tog-hideminor' => 'Gøym småplukk i lista over siste endringar',
'tog-hidepatrolled' => 'Gøym patruljerte endringar i lista over siste endringar',
'tog-newpageshidepatrolled' => 'Gøym patruljerte sider frå lista over nye sider',
'tog-extendwatchlist' => 'Utvid overvakingslista til å vise alle endringane, ikkje berre dei siste',
'tog-usenewrc' => 'Grupper endringar etter side i siste endringane og på overvakingslista mi (krev JavaScript)',
'tog-numberheadings' => 'Vis nummererte overskrifter',
'tog-showtoolbar' => 'Vis endringsknappar (JavaScript)',
'tog-editondblclick' => 'Endre sider med dobbeltklikk (JavaScript)',
'tog-editsection' => 'Endre avsnitt ved hjelp av [endre]-lenkje',
'tog-editsectiononrightclick' => 'Endre avsnitt ved å høgreklikke på avsnittsoverskrift (JavaScript)',
'tog-showtoc' => 'Vis innhaldsliste (for sider med meir enn tre bolkar)',
'tog-rememberpassword' => 'Hugs innlogginga mi med denne nettlesaren (for høgst {{PLURAL:$1|éin dag|$1 dagar}})',
'tog-watchcreations' => 'Legg til sidene eg opprettar og filene eg lastar opp på overvakingslista mi',
'tog-watchdefault' => 'Legg til sidene og filene eg endrar på overvakingslista mi',
'tog-watchmoves' => 'Legg til sidene og filene eg flytter på overvakingslista mi',
'tog-watchdeletion' => 'Legg til sidene og filene eg slettar på overvakingslista mi',
'tog-minordefault' => 'Merk endringar som «småplukk» som standard',
'tog-previewontop' => 'Vis førehandsvisinga før endringsboksen',
'tog-previewonfirst' => 'Førehandsvis første endring',
'tog-nocache' => 'Deaktiver nettlesaren sitt mellomlager («cache»)',
'tog-enotifwatchlistpages' => 'Send meg ein e-post når ei side eller ei fil på overvakingslista mi vert endra',
'tog-enotifusertalkpages' => 'Send e-post når brukarsida mi vert endra',
'tog-enotifminoredits' => 'Send meg e-post sjølv for mindre endringar på sider og filer',
'tog-enotifrevealaddr' => 'Vis e-postadressa mi i endrings-e-post',
'tog-shownumberswatching' => 'Vis kor mange som overvakar sida',
'tog-oldsig' => 'Noverande signatur:',
'tog-fancysig' => 'Handsam signaturar som wikitekst (utan automatisk lenking)',
'tog-externaleditor' => 'Bruk eit eksternt handsamingsprogram som standard (berre for vidarekomne, krev eit spesielt oppsett på maskina di. [//www.mediawiki.org/wiki/Manual:External_editors Meir informasjon.])',
'tog-externaldiff' => 'Bruk eit eksternt skilnadprogram som standard (berre for vidarekomne, krev eit spesielt oppsett på maskina di.
[//www.mediawiki.org/wiki/Manual:External_editors Meir informasjon.])',
'tog-showjumplinks' => 'Slå på «gå til»-lenkjer',
'tog-uselivepreview' => 'Bruk levande førehandsvising (eksperimentelt JavaScript)',
'tog-forceeditsummary' => 'Spør meg når eg ikkje har skrive noko i endringssamandraget',
'tog-watchlisthideown' => 'Gøym endringane mine i overvakingslista',
'tog-watchlisthidebots' => 'Gøym endringar gjorde av robotar i overvakingslista',
'tog-watchlisthideminor' => 'Gøym småplukk i overvakingslista',
'tog-watchlisthideliu' => 'Gøym endringar av innlogga brukarar i overvakingslista.',
'tog-watchlisthideanons' => 'Gøym endringar av anonyme brukarar i overvakingslista.',
'tog-watchlisthidepatrolled' => 'Gøym patruljerte endringar i overvakingslista',
'tog-ccmeonemails' => 'Send meg kopi av e-postane eg sender til andre brukarar',
'tog-diffonly' => 'Ikkje vis sideinnhaldet under skilnadene mellom versjonane',
'tog-showhiddencats' => 'Vis gøymde kategoriar',
'tog-noconvertlink' => 'Slå av konvertering av sidetitlar',
'tog-norollbackdiff' => 'Ikkje vis skilnad etter attenderulling',

'underline-always' => 'Alltid',
'underline-never' => 'Aldri',
'underline-default' => 'Drakt- eller nettlesarstandard',

# Font style option in Special:Preferences
'editfont-style' => 'Endre stilen for skrifttypen i området:',
'editfont-default' => 'Nettlesar i utgangspunktet',
'editfont-monospace' => 'Skrift med fast breidd',
'editfont-sansserif' => 'Skrifttype utan seriffar',
'editfont-serif' => 'Skrifttype med seriffar',

# Dates
'sunday' => 'søndag',
'monday' => 'måndag',
'tuesday' => 'tysdag',
'wednesday' => 'onsdag',
'thursday' => 'torsdag',
'friday' => 'fredag',
'saturday' => 'laurdag',
'sun' => 'søn',
'mon' => 'mån',
'tue' => 'tys',
'wed' => 'ons',
'thu' => 'tor',
'fri' => 'fre',
'sat' => 'lau',
'january' => 'januar',
'february' => 'februar',
'march' => 'mars',
'april' => 'april',
'may_long' => 'mai',
'june' => 'juni',
'july' => 'juli',
'august' => 'august',
'september' => 'september',
'october' => 'oktober',
'november' => 'november',
'december' => 'desember',
'january-gen' => 'januar',
'february-gen' => 'februar',
'march-gen' => 'mars',
'april-gen' => 'april',
'may-gen' => 'mai',
'june-gen' => 'juni',
'july-gen' => 'juli',
'august-gen' => 'august',
'september-gen' => 'september',
'october-gen' => 'oktober',
'november-gen' => 'november',
'december-gen' => 'desember',
'jan' => 'jan',
'feb' => 'feb',
'mar' => 'mar',
'apr' => 'apr',
'may' => 'mai',
'jun' => 'jun',
'jul' => 'jul',
'aug' => 'aug',
'sep' => 'sep',
'oct' => 'okt',
'nov' => 'nov',
'dec' => 'des',

# Categories related messages
'pagecategories' => '{{PLURAL:$1|Kategori|Kategoriar}}',
'category_header' => 'Artiklar i kategorien «$1»',
'subcategories' => 'Underkategoriar',
'category-media-header' => 'Media i kategorien «$1»',
'category-empty' => "''Kategorien inneheld for tida ingen sider eller mediefiler.''",
'hidden-categories' => '{{PLURAL:$1|Gøymd kategori|Gøymde kategoriar}}',
'hidden-category-category' => 'Gøymde kategoriar',
'category-subcat-count' => 'Kategorien har {{PLURAL:$2|berre denne underkategorien|{{PLURAL:$1|denne underkategorien|desse $1 underkategoriane}}, av totalt $2}}.',
'category-subcat-count-limited' => 'Kategorien har {{PLURAL:$1|denne underkategorien|desse $1 underkategoriane}}.',
'category-article-count' => 'Kategorien inneheld {{PLURAL:$2|berre denne sida|{{PLURAL:$1|denne sida|desse $1 sidene}}, av totalt $2}}.',
'category-article-count-limited' => 'Følgjande {{PLURAL:$1|side|$1 sider}} er i denne kategorien.',
'category-file-count' => 'Kategorien inneheld {{PLURAL:$2|berre den følgjande fila|dei følgjande {{PLURAL:$1|fil|$1 filene}}, av totalt $2}}.',
'category-file-count-limited' => 'Følgjande {{PLURAL:$1|fil|$1 filer}} er i denne kategorien.',
'listingcontinuesabbrev' => 'vidare',
'index-category' => 'Indekserte sider',
'noindex-category' => 'Ikkje-indekserte sider',
'broken-file-category' => 'Sider med brotne fillenkjer',

'linkprefix' => '/^(.*?)([a-zA-Z\\x80-\\xff]+)$/sD',

'about' => 'Om',
'article' => 'Innhaldsside',
'newwindow' => '(vert opna i eit nytt vindauge)',
'cancel' => 'Avbryt',
'moredotdotdot' => 'Meir …',
'mypage' => 'Sida mi',
'mytalk' => 'Diskusjon',
'anontalk' => 'Diskusjonside for denne IP-adressa',
'navigation' => 'Navigering',
'and' => '&#32;og',

# Cologne Blue skin
'qbfind' => 'Finn',
'qbbrowse' => 'Bla gjennom',
'qbedit' => 'Endre',
'qbpageoptions' => 'Denne sida',
'qbpageinfo' => 'Samanheng',
'qbmyoptions' => 'Sidene mine',
'qbspecialpages' => 'Spesialsider',
'faq' => 'OSS',
'faqpage' => 'Project:OSS',

# Vector skin
'vector-action-addsection' => 'Nytt emne',
'vector-action-delete' => 'Slett',
'vector-action-move' => 'Flytt',
'vector-action-protect' => 'Vern',
'vector-action-undelete' => 'Gjenopprett',
'vector-action-unprotect' => 'Endra vern',
'vector-simplesearch-preference' => 'Slå på forenkla søkjefelt (berre for Vector-drakta)',
'vector-view-create' => 'Opprett',
'vector-view-edit' => 'Endre',
'vector-view-history' => 'Sjå historikken',
'vector-view-view' => 'Les',
'vector-view-viewsource' => 'Sjå kjelda',
'actions' => 'Handlingar',
'namespaces' => 'Namnerom',
'variants' => 'Variantar',

'errorpagetitle' => 'Feil',
'returnto' => 'Attende til $1.',
'tagline' => 'Frå {{SITENAME}}',
'help' => 'Hjelp',
'search' => 'Søk',
'searchbutton' => 'Søk',
'go' => 'Vis',
'searcharticle' => 'Vis',
'history' => 'Sidehistorikk',
'history_short' => 'Historikk',
'updatedmarker' => 'oppdatert etter førre vitjinga mi',
'printableversion' => 'Utskriftsversjon',
'permalink' => 'Fast lenkje',
'print' => 'Skriv ut',
'view' => 'Sjå',
'edit' => 'Endre',
'create' => 'Opprett',
'editthispage' => 'Endre sida',
'create-this-page' => 'Opprett sida',
'delete' => 'Slett',
'deletethispage' => 'Slett denne sida',
'undelete_short' => 'Attopprett {{PLURAL:$1|éin versjon|$1 versjonar}}',
'viewdeleted_short' => 'Vis {{PLURAL:$1|éin sletta versjon|$1 sletta versjonar}}',
'protect' => 'Vern',
'protect_change' => 'endre',
'protectthispage' => 'Vern denne sida',
'unprotect' => 'Endra vern',
'unprotectthispage' => 'Endra vernet av sida',
'newpage' => 'Ny side',
'talkpage' => 'Diskuter sida',
'talkpagelinktext' => 'Diskusjon',
'specialpage' => 'Spesialside',
'personaltools' => 'Personlege verktøy',
'postcomment' => 'Ny bolk',
'articlepage' => 'Vis innhaldsside',
'talk' => 'Diskusjon',
'views' => 'Visningar',
'toolbox' => 'Verktøy',
'userpage' => 'Vis brukarside',
'projectpage' => 'Sjå prosjektsida',
'imagepage' => 'Vis filside',
'mediawikipage' => 'Vis systemmeldingsside',
'templatepage' => 'Vis malside',
'viewhelppage' => 'Vis hjelpeside',
'categorypage' => 'Vis kategoriside',
'viewtalkpage' => 'Vis diskusjon',
'otherlanguages' => 'På andre språk',
'redirectedfrom' => '(Omdirigert frå $1)',
'redirectpagesub' => 'Omdirigeringsside',
'lastmodifiedat' => 'Sida vart sist endra $1 kl. $2.',
'viewcount' => 'Sida er vist {{PLURAL:$1|éin gong|$1 gonger}}.',
'protectedpage' => 'Verna side',
'jumpto' => 'Gå til:',
'jumptonavigation' => 'navigering',
'jumptosearch' => 'søk',
'view-pool-error' => 'Diverre er filtenarane nett no opptekne.
For mange brukarar prøver å sjå denne sida.
Vent ei lita stund, før du prøver å sjå på sida.

$1',
'pool-timeout' => 'Tidsavbrot under venting på låsing.',
'pool-queuefull' => 'Køen er full.',
'pool-errorunknown' => 'Ukjend feil',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite' => 'Om {{SITENAME}}',
'aboutpage' => 'Project:Om',
'copyright' => 'Innhaldet er utgjeve under $1.',
'copyrightpage' => '{{ns:project}}:Opphavsrett',
'currentevents' => 'Aktuelt',
'currentevents-url' => 'Project:Aktuelt',
'disclaimers' => 'Atterhald',
'disclaimerpage' => 'Project:Atterhald',
'edithelp' => 'Hjelp til endring',
'edithelppage' => 'Help:Endring',
'helppage' => 'Help:Innhald',
'mainpage' => 'Hovudside',
'mainpage-description' => 'Hovudside',
'policy-url' => 'Project:Retningsliner',
'portal' => 'Brukarportal',
'portal-url' => 'Project:Brukarportal',
'privacy' => 'Personvern',
'privacypage' => 'Project:Personvern',

'badaccess' => 'Tilgangsfeil',
'badaccess-group0' => 'Du har ikkje lov til å utføre handlinga du ba om.',
'badaccess-groups' => 'Handlinga du ba om kan berre utførast av brukarar i {{PLURAL:$2|gruppa|gruppene}} $1.',

'versionrequired' => 'Versjon $1 av MediaWiki er påkravd',
'versionrequiredtext' => 'Ein må ha versjon $1 av MediaWiki for å bruke denne sida. Sjå [[Special:Version|versjonssida]].',

'ok' => 'OK',
'retrievedfrom' => 'Henta frå «$1»',
'youhavenewmessages' => 'Du har $1 ($2).',
'newmessageslink' => 'nye meldingar',
'newmessagesdifflink' => 'sjå skilnad',
'youhavenewmessagesfromusers' => 'Du har $1 frå {{PLURAL:$3|ein annan brukar| $3 brukarar}} ($2).',
'youhavenewmessagesmanyusers' => 'Du har $1 frå mange brukarar ($2).',
'newmessageslinkplural' => '{{PLURAL:$1|ei ny melding|nye meldingar}}',
'newmessagesdifflinkplural' => 'siste {{PLURAL:$1|endringa|endringane}}',
'youhavenewmessagesmulti' => 'Du har nye meldingar på $1',
'editsection' => 'endre',
'editold' => 'endre',
'viewsourceold' => 'sjå kjelda',
'editlink' => 'endre',
'viewsourcelink' => 'vis kjelde',
'editsectionhint' => 'Endre bolk: $1',
'toc' => 'Innhaldsliste',
'showtoc' => 'vis',
'hidetoc' => 'gøym',
'collapsible-collapse' => 'Slå saman.',
'collapsible-expand' => 'Vid ut',
'thisisdeleted' => 'Sjå eller attopprett $1?',
'viewdeleted' => 'Sjå historikk for $1?',
'restorelink' => '{{PLURAL:$1|Éin sletta versjon|$1 sletta versjonar}}',
'feedlinks' => 'Abonnementskjelde:',
'feed-invalid' => 'Ugyldig abonnementstype.',
'feed-unavailable' => 'Det er ingen kjelder til abonnement',
'site-rss-feed' => '$1 RSS-kjelde',
'site-atom-feed' => '$1 Atom-kjelde',
'page-rss-feed' => '«$1» RSS-kjelde',
'page-atom-feed' => '«$1» Atom-kjelde',
'red-link-title' => '$1 (sida finst ikkje)',
'sort-descending' => '↓Sorter fallande',
'sort-ascending' => '↓Sorter stigande',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main' => 'Side',
'nstab-user' => 'Brukarside',
'nstab-media' => 'Filside',
'nstab-special' => 'Spesialside',
'nstab-project' => 'Prosjektside',
'nstab-image' => 'Fil',
'nstab-mediawiki' => 'Systemmelding',
'nstab-template' => 'Mal',
'nstab-help' => 'Hjelp',
'nstab-category' => 'Kategori',

# Main script and global functions
'nosuchaction' => 'Funksjonen finst ikkje',
'nosuchactiontext' => 'Handlinga som er oppgjeven i adressa er ugyldig.
Du har kanskje stava adressa feil, eller følgt ei feil lenkja.
Dette kan òg skuldast ein feil i programvara som er nytta av {{SITENAME}}.',
'nosuchspecialpage' => 'Det finst inga slik spesialside',
'nospecialpagetext' => 'Du har bede om ei spesialside som ikkje finst. Lista over spesialsider finn du [[Special:SpecialPages|her]].',

# General errors
'error' => 'Feil',
'databaseerror' => 'Databasefeil',
'dberrortext' => 'Det oppstod ein syntaksfeil i ein databaseførespurnad. Dette kan tyda på feil i programvara. Siste førespurnaden til databasen var: <blockquote><code>$1</code></blockquote> frå funksjonen «<code>$2</code>». Databasen returnerte feilen «<samp>$3: $4</samp>».',
'dberrortextcl' => 'Det oppstod ein syntaksfeil i databaseførespurnaden.
Den sist prøvde førespurnaden var: «$1» frå funksjonen «$2».
Databasen returnerte feilen «$3: $4».',
'laggedslavemode' => 'Åtvaring: Det er mogleg at sida ikkje er heilt oppdatert.',
'readonly' => 'Databasen er skriveverna',
'enterlockreason' => 'Skriv ein grunn for vernet, inkludert eit overslag for kva tid det vil bli oppheva',
'readonlytext' => 'Databasen er akkurat no skriveverna, truleg for rutinemessig vedlikehald. Administratoren som verna han har gjeve denne forklaringa:

$1',
'missing-article' => 'Databasen burde ha funne sida «$1» $2, men det gjorde han ikkje.

Dei vanlegaste årsakene til denne feilen er ei lenkje til ein skilnad mellom forskjellige versjonar eller lenkjer til ein gammal versjon av ei side som har vorte sletta.

Om det ikkje er tilfellet kan du ha funne ein feil i programvara.
Meld gjerne problemet til ein [[Special:ListUsers/sysop|administrator]] og oppgje då adressa til sida.',
'missingarticle-rev' => '(versjon $1)',
'missingarticle-diff' => '(jamføring av versjon $1 og $2)',
'readonly_lag' => 'Databasen er mellombels skriveverna for at databasetenarane skal kunna synkronisere seg mot kvarandre',
'internalerror' => 'Intern feil',
'internalerror_info' => 'Intern feil: $1',
'fileappenderrorread' => 'Klarte ikkje å lese «$1» når data skulle leggast til.',
'fileappenderror' => 'Kunne ikkje leggja "$1" til "$2".',
'filecopyerror' => 'Kunne ikkje kopiere fila frå «$1» til «$2».',
'filerenameerror' => 'Kunne ikkje døype om fila frå «$1» til «$2».',
'filedeleteerror' => 'Kunne ikkje slette fila «$1».',
'directorycreateerror' => 'Kunne ikkje opprette mappa «$1».',
'filenotfound' => 'Kunne ikkje finne fila «$1».',
'fileexistserror' => 'Kunne ikkje skrive til fila «$1», ho eksisterer allereie',
'unexpected' => 'Uventa verdi: «$1»=«$2».',
'formerror' => 'Feil: Kunne ikkje sende skjema',
'badarticleerror' => 'Handlinga kan ikkje utførast på denne sida.',
'cannotdelete' => 'Kunne ikkje slette sida eller fila «$1».
Ho kan allereie vere sletta av andre.',
'cannotdelete-title' => 'Kan ikkje sletta sida «$1»',
'delete-hook-aborted' => 'Slettinga vart avbroten av ein funksjon.
Funksjonen oppgav inga årsak.',
'badtitle' => 'Feil i tittelen',
'badtitletext' => 'Den ønskte tittelen var ulovleg, tom eller feillenkja frå ein annan wiki. Kanskje inneheld han eitt eller fleire teikn som ikkje kan brukast i sidetitlar.',
'perfcached' => 'Dei følgjande dataa er frå mellomlageret åt tenaren og er ikkje utan vidare oppdatert. Høgst {{PLURAL:$1|eitt resultat er tilgjengeleg|$1 resultat er tilgjengelege}} i mellomlageret.',
'perfcachedts' => 'Desse dataa er mellomlagra, og vart sist oppdaterte $1. Høgst {{PLURAL:$4|eitt resultat er tilgjengeleg|$4 resultat er tilgjengelege}} i mellomlageret.',
'querypage-no-updates' => 'Oppdatering av denne sida er slått av, og data her vil ikkje verte fornya.',
'wrong_wfQuery_params' => 'Feil parameter gjevne til wfQuery()<br />Funksjon: $1<br />Førespurnad: $2',
'viewsource' => 'Sjå kjelda',
'viewsource-title' => 'Sjå kjelda til $1',
'actionthrottled' => 'Handlinga vart stoppa',
'actionthrottledtext' => 'For å hindre spamming kan du ikkje utføre denne handlinga for mange gonger på kort tid. Ver venleg og prøv igjen litt seinare.',
'protectedpagetext' => 'Sida har vorte verna for å hindra endring eller andre handlingar.',
'viewsourcetext' => 'Du kan sjå og kopiere kjeldekoden til denne sida:',
'viewyourtext' => "Du kan sjå og kopiera kjelda til '''endringane dine''' på sida:",
'protectedinterface' => 'Denne sida inneheld tekst nytta av brukargrensesnittet for programvara på wikien, og er låst for å hindra hærverk.
For å leggja til eller endra omsetjingar for alle wikiar, gjer vel å nytta [//translatewiki.net/wiki/Main_Page?setlang=nn translatewiki.net], prosjektet for lokalisering av MediaWiki.',
'editinginterface' => "'''Åtvaring:''' Du endrar på ei side som inneheld tekst nytta av brukargrensesnittet for programvara.
Endringar på denne sida påverkar utsjånaden til brukargrensesnittet for dei andre brukarane av wikien.
For å leggja til eller endra omsetjingar, gjer vel å nytta [//translatewiki.net/wiki/Main_Page?setlang=nn translatewiki.net], prosjektet for lokalisering av MediaWiki.",
'sqlhidden' => '(SQL-førespurnaden er gøymd)',
'cascadeprotected' => 'Denne sida er verna mot endring fordi ho er inkludert i {{PLURAL:$1|den opplista sida|dei opplista sidene}} som har djupvern slått på:
$2',
'namespaceprotected' => "Du har ikkje tilgang til å endre sidene i '''$1'''-namnerommet.",
'customcssprotected' => '↓Du har ikkje tilgang til å endre denne sida, fordi ho inneheld ein annan brukar sine personlege innstillingar.',
'customjsprotected' => '↓Du har ikkje tilgang til å endra denne JavaScript-sida fordi ho inneheld ein annen brukar sine personlege innstillingar.',
'ns-specialprotected' => 'Sider i {{ns:special}}-namnerommet kan ikkje endrast.',
'titleprotected' => "Denne sidetittelen er verna mot oppretting av [[User:$1|$1]].
Grunnen som er gjeven er: ''$2''.",
'filereadonlyerror' => 'Kan ikkje endra fila «$1» av di filsamlinga «$2» er skriveverna.

Administratoren som låste filsamlinga oppgav den fylgjande årsaka: «$3».',
'invalidtitle-knownnamespace' => 'Ugyldig tittel med namnerommet «$2» og teksten «$3»',
'invalidtitle-unknownnamespace' => 'Ugyldig tittel med ukjend namneromstal $1 og teksten «$2»',
'exception-nologin' => 'Ikkje innlogga',
'exception-nologin-text' => 'Sida eller handlinga krev at du er innlogga på wikien.',

# Virus scanner
'virus-badscanner' => "Dårleg konfigurasjon: ukjend virusskanner: ''$1''",
'virus-scanfailed' => 'skanning mislukkast (kode $1)',
'virus-unknownscanner' => 'ukjend antivirusprogram:',

# Login and logout pages
'logouttext' => "'''Du er no utlogga.'''

Du kan no halde fram å bruke {{SITENAME}} anonymt, eller du kan [[Special:UserLogin|logge inn att]]  med same kontoen eller ein annan brukar kan logge inn.
Ver merksam på at nokre sider framleis kan visast fram som om du er innlogga fram til du slettar mellomlageret til nettlesaren din.",
'welcomecreation' => '== Hjarteleg velkommen til {{SITENAME}}, $1! ==
Brukarkontoen din er oppretta.
Hugs at du kan endre på [[Special:Preferences|innstillingane]] dine.',
'yourname' => 'Brukarnamn:',
'yourpassword' => 'Passord:',
'yourpasswordagain' => 'Skriv opp att passordet',
'remembermypassword' => 'Hugs innlogginga mi på denne datamaskinen (høgst {{PLURAL:$1|éin dag|$1 dagar}})',
'securelogin-stick-https' => 'Fortset HTTPS-tilkopling etter innlogging.',
'yourdomainname' => 'Domenet ditt',
'password-change-forbidden' => 'Du kan ikkje endra passord på denne wikien.',
'externaldberror' => 'Det var anten ein ekstern databasefeil i tilgjengekontrollen, eller du har ikkje løyve til å oppdatere den eksterne kontoen din.',
'login' => 'Logg inn',
'nav-login-createaccount' => 'Lag brukarkonto / logg inn',
'loginprompt' => 'Nettlesaren din må godta informasjonskapslar for at du skal kunna logge inn.',
'userlogin' => 'Lag brukarkonto / logg inn',
'userloginnocreate' => 'Logg inn',
'logout' => 'Logg ut',
'userlogout' => 'Logg ut',
'notloggedin' => 'Ikkje innlogga',
'nologin' => "Har du ingen brukarkonto? '''$1'''.",
'nologinlink' => 'Registrer deg',
'createaccount' => 'Opprett ny konto',
'gotaccount' => "Har du ein brukarkonto? '''$1'''.",
'gotaccountlink' => 'Logg inn',
'userlogin-resetlink' => 'Har du gløymd påloggingsopplysingane dine?',
'createaccountmail' => 'over e-post',
'createaccountreason' => 'Årsak:',
'badretype' => 'Passorda du skreiv inn er ikkje like.',
'userexists' => 'Brukarnamnet er alt i bruk. Vel eit anna brukarnamn.',
'loginerror' => 'Innloggingsfeil',
'createaccounterror' => 'Kunne ikkje oppretta kontoen:  $1',
'nocookiesnew' => 'Brukarkontoen vart oppretta, men du er ikkje innlogga. {{SITENAME}} bruker informasjonskapslar for å logge inn brukarar,
nettlesaren din er innstilt for ikkje å godta desse. Etter at du har endra innstillingane slik at nettlesaren godtek informasjonskapslar, kan du logge inn med det nye brukarnamnet og passordet ditt.',
'nocookieslogin' => '{{SITENAME}} bruker informasjonskapslar for å logge inn brukarar, nettlesaren din er innstilt for ikkje å godta desse.
Etter at du har endra innstillingane slik at nettlesaren godtek informasjonskapslar kan du prøve å logge inn på nytt.',
'nocookiesfornew' => 'Brukarkontoen blei ikkje oppretta ettersom kjelda ikkje kunne stadfestast.
Sjå etter om du tek imot informasjonskapslar (cookies), last ned sida ein gong til og prøv igjen.',
'noname' => 'Du har ikkje oppgjeve gyldig brukarnamn.',
'loginsuccesstitle' => 'Du er no innlogga',
'loginsuccess' => 'Du er no innlogga som «$1».',
'nosuchuser' => 'Det finst ikkje nokon brukar med brukarnamnet «$1».
Brukarnamn skil mellom stor og liten bokstav. Sjekk at du har skrive brukarnamet rett eller [[Special:UserLogin/signup|opprett ein ny konto]].',
'nosuchusershort' => 'Det finst ikkje nokon brukar med brukarnamnet «$1». Sjekk at du har skrive rett.',
'nouserspecified' => 'Du må oppgje eit brukarnamn.',
'login-userblocked' => 'Denne brukaren er blokkert. Innlogging er ikkje tillate.',
'wrongpassword' => 'Du har oppgjeve eit ugyldig passord. Prøv om att.',
'wrongpasswordempty' => 'Du oppgav ikkje noko passord. Ver venleg og prøv igjen.',
'passwordtooshort' => 'Passord må innehalda minst {{PLURAL:$1|eitt teikn|$1 teikn}}.',
'password-name-match' => 'Passordet ditt lyt vera noko anna enn brukarnamnet ditt.',
'password-login-forbidden' => 'Bruk av dette brukarnamnet og passordet er vorte forbode.',
'mailmypassword' => 'Send nytt passord',
'passwordremindertitle' => 'Nytt passord til {{SITENAME}}',
'passwordremindertext' => 'Nokon (truleg du, frå IP-adressa $1) bad oss sende deg eit nytt passord til {{SITENAME}} ($4). Eit mellombels passord for «$2» er oppretta, og er sett til «$3». Om det var det du ville, må du logge inn
og velje eit nytt passord no.
Mellombelspassordet ditt vil slutte å fungere om {{PLURAL:$5|éin dag|$5 dagar}}.

Dersom denne førespurnaden blei utført av nokon andre, eller om du kom på passordet og ikkje lenger ønsker å endre det, kan du ignorere denne meldinga og halde fram med å bruke det gamle passordet.',
'noemail' => 'Det er ikkje registrert noka e-postadresse åt brukaren «$1».',
'noemailcreate' => 'Du må oppgje ei gyldig e-postadresse',
'passwordsent' => 'Eit nytt passord er sendt åt e-postadressa registrert på brukaren «$1».',
'blocked-mailpassword' => 'IP-adressa di er blokkert frå å endre sider, og du kan difor heller ikkje få nytt passord. Dette er for å hindre misbruk.',
'eauthentsent' => 'Ein stadfestings-e-post er sendt til den oppgjevne e-postadressa. For at adressa skal kunna brukast, må du følgje instruksjonane i e-posten for å stadfeste at ho faktisk tilhøyrer deg.',
'throttled-mailpassword' => 'Ei passordpåminning er allereie sendt {{PLURAL:$1|den siste timen|dei siste $1 timane}}. For å hindre misbruk vert det berre sendt ut nytt passord ein gong kvar {{PLURAL:$1|time|$1. time}}.',
'mailerror' => 'Ein feil oppstod ved sending av e-post: $1',
'acct_creation_throttle_hit' => 'Vitjande på denne wikien som nytta IP-adressa di har alt oppretta {{PLURAL:$1|éin konto|$1 kontoar}} den siste dagen, noko som er det høgaste tillate talet i denne tidsperioden.
Grunna dette vil ikkje vitjande som nyttar denne IP-adressa kunna oppretta nye kontoar nett no.',
'emailauthenticated' => 'E-postadressa di vart stadfesta $2 $3.',
'emailnotauthenticated' => 'E-postadressa di er enno ikkje stadfest. Dei følgjande funksjonane kan ikkje bruke ho.',
'noemailprefs' => 'Oppgje ei e-postadresse i innstillingane dine for at desse funksjonane skal verke.',
'emailconfirmlink' => 'Stadfest e-post-adressa di',
'invalidemailaddress' => 'E-postadressa kan ikkje nyttast sidan formatet truleg er feil. Skriv ei fungerande adresse eller tøm feltet.',
'cannotchangeemail' => 'Epost-adresser knytta til brukarkonti kan ikkje endrast på denne wikien.',
'emaildisabled' => 'Denne nettstaden kan ikkje senda e-postar.',
'accountcreated' => 'Brukarkonto oppretta',
'accountcreatedtext' => 'Brukarkontoen til $1 er oppretta.',
'createaccount-title' => 'Oppretting av brukarkonto på {{SITENAME}}',
'createaccount-text' => 'Nokon oppretta ein brukarkonto for $2 på {{SITENAME}} ($4). Passordet til «$2» er «$3». Du bør logge inn og endre passordet ditt med ein gong.

Du kan sjå bort frå denne meldinga dersom kontoen vart oppretta med eit uhell.',
'usernamehasherror' => 'Brukarnamn kan ikkje innehalda nummerteikn.',
'login-throttled' => 'Du har prøvd å logge inn for mange gonger. Ver venleg og vent før du prøver igjen.',
'login-abort-generic' => 'Innlogginga er avbroten.',
'loginlanguagelabel' => 'Språk: $1',
'suspicious-userlogout' => 'Førespurnaden din om å logge ut vart nekta fordi han såg ut til å vere sendt av ein øydelagt nettlesar eller mellomtenar.',

# E-mail sending
'php-mail-error-unknown' => 'Ukjend feil i PHPs mail()-funksjon',
'user-mail-no-addy' => '↓Prøvde å senda e-post utan e-postadresse',

# Change password dialog
'resetpass' => 'Endra passord',
'resetpass_announce' => 'Du logga inn med eit mellombels passord du fekk på e-post. For å fullføre innlogginga må du lage eit nytt passord her:',
'resetpass_text' => '<!-- Legg til tekst her -->',
'resetpass_header' => 'Endra passord',
'oldpassword' => 'Gammalt passord',
'newpassword' => 'Nytt passord',
'retypenew' => 'Nytt passord om att',
'resetpass_submit' => 'Oppgje passord og logg inn',
'resetpass_success' => 'Passordet ditt er no nullstilt! Loggar inn...',
'resetpass_forbidden' => 'Passord kan ikkje endrast',
'resetpass-no-info' => 'Du må vera innlogga for å få direktetilgang til denne sida.',
'resetpass-submit-loggedin' => 'Endra passord',
'resetpass-submit-cancel' => 'Avbryt',
'resetpass-wrong-oldpass' => 'Feil mellombels eller noverande passord.
Du kan allereie ha byta passordet, eller ha bede om å få eit nytt mellombels passord.',
'resetpass-temp-password' => 'Mellombels passord:',

# Special:PasswordReset
'passwordreset' => 'Attendestilling av passord',
'passwordreset-text' => '↓Fyll ut dette skjemaet for å motta ei påminning om kontoopplysningane dine i ein e-post.',
'passwordreset-legend' => '↓Nullstill passordet',
'passwordreset-disabled' => '↓Tilbakestilling av passord er ikkje aktivert på denne wikien',
'passwordreset-pretext' => '↓{{PLURAL:$1||Tast inn ein av datadelane nedanfor}}',
'passwordreset-username' => 'Brukarnamn:',
'passwordreset-domain' => 'Domene:',
'passwordreset-capture' => 'Vis resulterande epost',
'passwordreset-capture-help' => 'Huk av her dersom du vil sjå eposten (med førebels passord) i tillegg til at han blir sendt til brukaren.',
'passwordreset-email' => '↓E-postadresse:',
'passwordreset-emailtitle' => '↓Kontodetaljar på {{SITENAME}}',
'passwordreset-emailtext-ip' => 'Nokon (sannsynlegvis deg, frå IP-adressa $1) bad om ei påminning for kontodetaljane dine for {{SITENAME}} ($4). {{PLURAL:$3|Den fylgjande brukarkontoen|Dei fylgjande brukarkontoane}} er assosierte med denne e-postadressa:

$2

{{PLURAL:$3|Dette mellombels passordet|Desse mellombels passorda}} vil verta ugilde om {{PLURAL:$5|éin dag|$5 dagar}}.
Du bør logga inn og velja eit nytt passord no. Om nokon andre enn deg bad om denne påminninga, eller du har kome i hug det opphavlege passordet og ikkje lenger ynskjer å endra det, kan du sjå bort frå denne meldinga og halda fram med å nytta det gamle passordet ditt.',
'passwordreset-emailtext-user' => 'Brukaren $1 på {{SITENAME}} bad om ei påminning for kontodetaljane dine for {{SITENAME}} ($4). {{PLURAL:$3|Den fylgjande brukarkontoen|Dei fylgjande brukarkontoane}} er assosierte med denne e-postadressa:

$2

{{PLURAL:$3|Dette mellombels passordet|Desse mellombels passorda}} vil verta ugilde om {{PLURAL:$5|éin dag|$5 dagar}}.
Du bør logga inn og velja eit nytt passord no. Om nokon andre enn deg bad om denne påminninga, eller du har kome i hug det opphavlege passordet og ikkje lenger ynskjer å endra det, kan du sjå bort frå denne meldinga og halda fram med å nytta det gamle passordet ditt.',
'passwordreset-emailelement' => '↓Brukarnamn: $1
Mellombels passord: $2',
'passwordreset-emailsent' => '↓Ei påminning har vorte sendt på e-post.',
'passwordreset-emailsent-capture' => 'Eposten under er sendt ut som ei påminning.',
'passwordreset-emailerror-capture' => 'Ein påminnings-e-post vart oppretta, og er vist nedanfor; men det lukkast ikkje å senda han til brukaren: $1',

# Special:ChangeEmail
'changeemail' => '↓Endre e-postadresse',
'changeemail-header' => '↓Endre kontoen si e-postadresse',
'changeemail-text' => '↓Fyll ut dette skjemaet for å endra di e-postadresse. Du må oppgje passordet ditt for å stadfesta endringa.',
'changeemail-no-info' => '↓Du må vera pålogga for å få tilgang direkte til denne sida.',
'changeemail-oldemail' => '↓Noverande e-postadresse:',
'changeemail-newemail' => 'Ny e-postadresse:',
'changeemail-none' => '↓(ingen)',
'changeemail-submit' => '↓Endre e-post',
'changeemail-cancel' => '↓Avbryt',

# Edit page toolbar
'bold_sample' => 'Halvfeit skrift',
'bold_tip' => 'Halvfeit skrift',
'italic_sample' => 'Kursivskrift',
'italic_tip' => 'Kursivskrift',
'link_sample' => 'Lenkjetittel',
'link_tip' => 'Intern lenkje',
'extlink_sample' => 'http://www.example.com lenkjetittel',
'extlink_tip' => 'Ekstern lenkje (hugs http:// prefiks)',
'headline_sample' => 'Overskriftstekst',
'headline_tip' => '2. nivå-overskrift',
'nowiki_sample' => 'Skriv uformatert tekst her',
'nowiki_tip' => 'Sjå bort frå wikiformatering',
'image_sample' => 'Døme.jpg',
'image_tip' => 'Bilete eller lenkje til filomtale',
'media_sample' => 'Døme.ogg',
'media_tip' => 'Filpeikar',
'sig_tip' => 'Signaturen din med tidsstempel',
'hr_tip' => 'Vassrett line',

# Edit pages
'summary' => 'Samandrag:',
'subject' => 'Emne/overskrift:',
'minoredit' => 'Småplukk',
'watchthis' => 'Overvak sida',
'savearticle' => 'Lagra sida',
'preview' => 'Førehandsvising',
'showpreview' => 'Førehandsvis',
'showlivepreview' => 'Levande førehandsvising',
'showdiff' => 'Vis skilnader',
'anoneditwarning' => "'''Åtvaring:''' Du er ikkje innlogga. IP-adressa di vert lagra i historikken for sida.",
'anonpreviewwarning' => "''Du er ikkje innlogga. Lagrar du vil IP-adressa di verta førd opp i endringshistorikken til denne sida.''",
'missingsummary' => "'''Påminning:''' Du har ikkje skrive noko endringssamandrag. Dersom du trykkjer «Lagre» ein gong til, vert endringa di lagra utan.",
'missingcommenttext' => 'Ver venleg og skriv ein kommentar nedanfor.',
'missingcommentheader' => "'''Påminning:''' Du har ikkje oppgjeve noko emne/overskrift for denne kommentaren.
Dersom du trykkjer «{{int:savearticle}}» ein gong til, vert endringa di lagra utan.",
'summary-preview' => 'Førehandsvising av endringssamandraget:',
'subject-preview' => 'Førehandsvising av emne/overskrift:',
'blockedtitle' => 'Brukaren er blokkert',
'blockedtext' => "'''Brukarnamnet ditt eller IP-adressa di er blokkert'''

Blokkeringa vart gjord av $1.
Denne grunnen vart gjeven: ''$2''.

* Blokkeringa byrja: $8
* Blokkeringa endar: $6
* Blokkeringa var meint for: $7

Du kan kontakte $1 eller ein annan [[{{MediaWiki:Grouppage-sysop}}|administrator]] for å diskutere blokkeringa.
Ver merksam på at du ikkje kan bruke «send e-post til brukar»-funksjonen så lenge du ikkje har ei gyldig e-postadresse registrert i [[Special:Preferences|innstillingane dine]]. Du kan heller ikkje bruke funksjonen dersom du er blokkert frå å sende e-post.
IP-adressa di er $3, og blokkeringsnummeret er $5.
Tak med alle opplysningane over ved eventuelle førespurnader.",
'autoblockedtext' => "IP-adressa di er automatisk blokkert fordi ho vart brukt av ein annan brukar som vart blokkert av $1. Grunne til dette vart gjeve som: ''$2''.

* Blokkeringa byrja: $8
* Blokkeringa går ut: $6
* Blokkeringa er meint for: $7

Du kan kontakte $1 eller ein annan [[{{MediaWiki:Grouppage-sysop}}|administrator]] for å diskutere blokkeringa. Ver merksam på at du ikkje kan bruke «send e-post til brukar»-funksjonen så lenge du ikkje har ei gyldig e-postadresse registrert i [[Special:Preferences|innstillingane dine]].

IP-adressa di er $3, og blokkeringnummeret ditt er #$5.
Ver venleg og opplyse dette ved eventuelle førespurnader.",
'blockednoreason' => 'inga grunngjeving',
'whitelistedittext' => 'Du lyt $1 for å endre sider.',
'confirmedittext' => 'Du må stadfeste e-postadressa di før du kan endre sidene. Ver venleg og legg inn og stadfest e-postadressa di i [[Special:Preferences|innstillingane dine]].',
'nosuchsectiontitle' => 'Kan ikkje finna bolk',
'nosuchsectiontext' => 'Du prøvde å endre ein bolk som ikkje finst.
Han kan ha vorten flytta eller sletta medan du såg på sida.',
'loginreqtitle' => 'Innlogging trengst',
'loginreqlink' => 'logge inn',
'loginreqpagetext' => 'Du lyt $1 for å lesa andre sider.',
'accmailtitle' => 'Passord er sendt.',
'accmailtext' => "Eit tilfeldig laga passord for [[User talk:$1|$1]] er sendt til $2.

Passordet for den nye kontoen kan verta endra på ''[[Special:ChangePassword|endra passord]]''-sida etter innlogging.",
'newarticle' => '(Ny)',
'newarticletext' => "Du har følgt ei lenkje til ei side som ikkje finst enno.
For å opprette sida, kan du skrive i boksen under (sjå [[{{MediaWiki:Helppage}}|hjelpesida]] for meir informasjon).
Hamna du her ved ein feil, klikk på '''attende'''-knappen i nettlesaren din.",
'anontalkpagetext' => "----''Dette er ei diskusjonsside for ein anonym brukar som ikkje har oppretta konto eller ikkje har logga inn.
Vi er difor nøydde til å bruke den numeriske IP-adressa til å identifisere brukaren. Same IP-adresse kan vere knytt til fleire brukarar. Om du er ein anonym brukar og meiner at du har fått irrelevante kommentarar på ei slik side, [[Special:UserLogin/signup|opprett ein brukarkonto]] eller [[Special:UserLogin|logg inn]] slik at vi unngår framtidige forvekslingar med andre anonyme brukarar.''",
'noarticletext' => 'Det er nett no ikkje noko tekst på denne sida.
Du kan [[Special:Search/{{PAGENAME}}|søkja etter sidetittelen]] i andre sider, <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} søkja i dei relaterte loggane]
eller [{{fullurl:{{FULLPAGENAME}}|action=edit}} endra denne sida]</span>.',
'noarticletext-nopermission' => 'Der er nett no ikkje noko tekst på denne sida.
Du kan [[Special:Search/{{PAGENAME}}|søkja etter sidetittelen]] i andre sider
eller <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} søkja i dei relaterte loggane]</span>, men du har ikkje løyve til å oppretta denne sida.',
'missing-revision' => 'Versjonen #$1 av sida med namnet «{{PAGENAME}}» finst ikkje.

Dette skriv seg som oftast frå at ei forelda historikklenkje vart fylgd til ei side som er sletta.
Detaljar kan ein finna i [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} sletteloggen].',
'userpage-userdoesnotexist' => 'Brukarkontoen «<nowiki>$1</nowiki>» finst ikkje. Vil du verkeleg opprette/endre denne sida?',
'userpage-userdoesnotexist-view' => 'Brukarkontoen «$1» er ikkje oppretta.',
'blocked-notice-logextract' => 'Denne brukaren er for tida blokkert.
Det siste elementet i blokkeringsloggen er oppgjeve nedanfor:',
'clearyourcache' => "'''Merk:''' Etter lagring vil det kanskje vera naudsynt at nettlesaren slettar mellomlageret sitt for at endringane skal tre i kraft.
* '''Firefox og Safari:''' Haldt nede ''Shift'' medan du klikkar på ''Oppdater'', eller trykk anten ''Ctrl-F5'' eller ''Ctrl-R'' (''⌘-R'' på Mac)
* '''Google Chrome:''' Trykk ''Ctrl-Shift-R'' (''⌘-Shift-R'' på Mac)
* '''Internet Explorer:''' Haldt nede ''Ctrl'' medan du klikkar ''Oppdater'', eller trykk ''Ctrl-F5.''
* '''Opera:''' Tøm mellomlageret i ''Verktøy → Innstillingar''.",
'usercssyoucanpreview' => "'''Tips:''' Bruk «{{int:showpreview}}»-knappen for å teste den nye CSS- eller JavaScript-koden din før du lagrar.",
'userjsyoucanpreview' => "''Tips:''' Bruk «{{int:showpreview}}»-knappen for å teste den nye CSS- eller JavaScript-koden din før du lagrar.",
'usercsspreview' => "'''Hugs at dette berre er ei førehandsvising av din eigen CSS og at han ikkje er lagra enno!'''",
'userjspreview' => "'''Hugs at du berre testar ditt eige JavaScript, det har ikkje vorte lagra enno!!'''",
'sitecsspreview' => "'''Hugs at du berre førehandsviser dette stilarket. '''
'''Det er ikkje lagra enno!'''",
'sitejspreview' => "'''Hugs at du berre førehandsviser denne JavaScript-koden.'''
'''Han er ikkje lagra enno!'''",
'userinvalidcssjstitle' => "'''Åtvaring:''' Det finst ikkje noka sidedrakt som heiter «$1». Hugs på at vanlege .css- og .js-sider brukar titlar med små bokstavar, til dømes {{ns:user}}:Døme/vector.css, og ikkje {{ns:user}}:Døme/Vector.css.",
'updated' => '(Oppdatert)',
'note' => "'''Merk:'''",
'previewnote' => "'''Hugs at dette berre er ei førehandsvising.'''
Endringane dine er ikkje lagra enno!",
'continue-editing' => 'Gå til endringsområdet',
'previewconflict' => 'Dette er ei førehandsvising av teksten i endringsboksen over, slik han vil sjå ut om du lagrar han',
'session_fail_preview' => "'''Orsak! Endringa di kunne ikkje lagrast. Ver venleg og prøv ein gong til. Dersom det framleis ikkje går, prøv å logge deg ut og inn att.'''",
'session_fail_preview_html' => "'''Beklagar! Endringa di kunne ikkje lagrast.'''

''Fordi {{SITENAME}} har rå HTML-kode slått på, er førehandsvisinga gøymd grunna fare for JavaScript-angrep.''

'''Dersom dette er eit heilt vanleg forsøk på endring, prøv ein gong til. Dersom det framleis ikkje går, prøv å logge deg ut og inn att.'''",
'token_suffix_mismatch' => "'''Endringa di vart avvist fordi klienten/nettlesaren din lagar teiknfeil i teksten. Dette vart gjort for å hindre øydelegging av teksten på sida. Slikt kan av og til hende når ein brukar feilprogrammerte og vevbaserte anonyme proxytenester.'''",
'edit_form_incomplete' => 'Delar av redigeringsskjemaet nådde ikkje fram til tenaren; dobbelsjekk at redigeringa er korrekt, og prøv om att.',
'editing' => 'Endrar $1',
'creating' => 'Opprettar $1',
'editingsection' => 'Endrar $1 (bolk)',
'editingcomment' => 'Endrar $1 (ny bolk)',
'editconflict' => 'Endringskonflikt: $1',
'explainconflict' => "Nokon annan har endra teksten sidan du byrja å skrive.
Den øvste boksen inneheld den noverande teksten.
Skilnaden mellom den lagra versjonen og din endra versjon er viste under.
Versjonen som du har endra er i den nedste boksen.
Du lyt flette endringane dine saman med den noverande teksten.
'''Berre''' teksten i den øvste tekstboksen vil bli lagra når du klikkar på «{{int:savearticle}}».",
'yourtext' => 'Teksten din',
'storedversion' => 'Den lagra versjonen',
'nonunicodebrowser' => "'''ÅTVARING: Nettlesaren din støttar ikkje «Unicode».
For å omgå problemet blir teikn utanfor ASCII-standarden viste som heksadesimale kodar.'''<br />",
'editingold' => "'''ÅTVARING: Du endrar ein gammal versjon av denne sida. Om du lagrar ho, vil alle endringar gjorde etter denne versjonen bli overskrivne.''' (Men dei kan hentast fram att frå historikken.)<br />",
'yourdiff' => 'Skilnader',
'copyrightwarning' => "Merk deg at alle bidrag til {{SITENAME}} er å rekne som utgjevne under $2 (sjå $1 for detaljar). Om du ikkje vil ha teksten endra og kopiert under desse vilkåra, kan du ikkje leggje han her.<br />
Teksten må du ha skrive sjølv, eller kopiert frå ein ressurs som er kompatibel med vilkåra eller ikkje verna av opphavsrett.

'''LEGG ALDRI INN MATERIALE SOM ANDRE HAR OPPHAVSRETT TIL UTAN LØYVE FRÅ DEI!'''",
'copyrightwarning2' => "Merk deg at alle bidrag til {{SITENAME}} kan bli endra, omskrive og fjerna av andre bidragsytarar. Om du ikkje vil ha teksten endra under desse vilkåra, kan du ikkje leggje han her.<br />
Teksten må du ha skrive sjølv eller ha kopiert frå ein ressurs som er kompatibel med vilkåra eller ikkje verna av opphavsrett (sjå $1 for detaljar).

'''LEGG ALDRI INN MATERIALE SOM ANDRE HAR OPPHAVSRETT TIL UTAN LØYVE FRÅ DEI!'''",
'longpageerror' => "'''Feil: Teksten du sende inn er {{PLURAL:$1|éin kilobyte|$1 kilobyte}} stor, noko som er større enn øvstegrensa på {{PLURAL:$2|éin kilobyte|$2 kilobyte}}.''' Han kan difor ikkje lagrast.",
'readonlywarning' => "'''ÅTVARING: Databasen er skriveverna på grunn av vedlikehald, så du kan ikkje lagre endringane dine akkurat no. Det kan vera lurt å kopiere teksten din til ei tekstfil, så du kan lagre han her seinare.'''

Systemadministratoren som låste databasen gav denne årsaka: $1",
'protectedpagewarning' => "'''ÅTVARING: Denne sida er verna, slik at berre administratorar kan endra henne.'''
Det siste loggelementet er oppgjeve under som referanse:",
'semiprotectedpagewarning' => "'''Merk:''' Denne sida er verna slik at berre registrerte brukarar kan endre henne.
Det siste loggelementet er oppgjeve under som referanse:",
'cascadeprotectedwarning' => "'''Åtvaring:''' Denne sida er verna så berre brukarar med administratortilgang kan endre henne. Dette er fordi ho er inkludert i {{PLURAL:$1|denne djupverna sida|desse djupverna sidene}}:",
'titleprotectedwarning' => "'''Åtvaring: Denne sida er verna, så berre [[Special:ListGroupRights|nokre brukarar]] kan opprette henne.'''
Det siste loggelementet er oppgjeve under som referanse:",
'templatesused' => '{{PLURAL:$1|Mal|Malar}} som er brukte på denne sida:',
'templatesusedpreview' => '{{PLURAL:$1|Mal som er brukt|Malar som er brukte}} i førehandsvisinga:',
'templatesusedsection' => '{{PLURAL:$1|Mal som er brukt|Malar som er brukte}} i denne bolken:',
'template-protected' => '(verna)',
'template-semiprotected' => '(delvis verna)',
'hiddencategories' => 'Denne sida er med i {{PLURAL:$1|éin gøymd kategori|$1 gøymde kategoriar}}:',
'edittools' => '<!-- Teksten her vert vist mellom tekstboksen og «Lagre»-knappen når ein endrar ei side. -->',
'nocreatetitle' => 'Avgrensa sideoppretting',
'nocreatetext' => '{{SITENAME}} har avgrensa tilgang til å opprette nye sider.
Du kan gå attende og endre ei eksisterande side, [[Special:UserLogin|logge inn eller opprette ein brukarkonto]].',
'nocreate-loggedin' => 'Du har ikkje tilgang til å opprette nye sider.',
'sectioneditnotsupported-title' => 'Endring av bolkar er ikkje støtta',
'sectioneditnotsupported-text' => 'Endring av bolkar er ikkje støtta på denne sida.',
'permissionserrors' => 'Tilgangsfeil',
'permissionserrorstext' => 'Du har ikkje tilgang til å gjere dette, {{PLURAL:$1|grunnen|grunnane}} til det finn du her:',
'permissionserrorstext-withaction' => 'Du har ikkje løyve til å $2 {{PLURAL:$1|på grunn av|av desse grunnane}}:',
'recreate-moveddeleted-warn' => "'''Åtvaring: Du attopprettar ei side som tidlegare har vorte sletta.'''

Du bør tenkje over om det er høveleg å halde fram med å endre denne sida.
Sletteloggen for sida finn du her:",
'moveddeleted-notice' => 'Sida er vorten sletta. Sletteloggen og flytteloggen er viste nedanfor for referanse.',
'log-fulllog' => 'Sjå full loggføring',
'edit-hook-aborted' => 'Endring avbroten av ein funksjon, utan forklaring.',
'edit-gone-missing' => 'Kunne ikkje oppdatere sida.
Det ser ut til at ho er sletta.',
'edit-conflict' => 'Endringskonflikt.',
'edit-no-change' => 'Redigeringa di vart ignorert fordi det ikkje vart gjort endringar i teksten.',
'edit-already-exists' => 'Kunne ikkje opprette ny side fordi ho alt eksisterer.',
'defaultmessagetext' => 'Standard meldingstekst',

# Parser/template warnings
'expensive-parserfunction-warning' => 'Åtvaring: Denne sida inneheld for mange prosesskrevande parserfunksjonar.

Det burde vere færre enn {{PLURAL:$2|$2|$2}}, men er no {{PLURAL:$1|$1|$1}}.',
'expensive-parserfunction-category' => 'Sider med for mange prosesskrevande parserfunksjonar',
'post-expand-template-inclusion-warning' => 'Åtvaring: Storleiken på malar som er inkluderte er for stor.
Nokre malar vert ikkje inkluderte.',
'post-expand-template-inclusion-category' => 'Sider som inneheld for store malar',
'post-expand-template-argument-warning' => 'Åtvaring: Sida inneheld ein eller fleire malparameterar som vert for lange når dei utvidast.
Desse parameterane har vorte utelatne.',
'post-expand-template-argument-category' => 'Sider med utelatne malparameterar',
'parser-template-loop-warning' => 'Malløkka oppdaga: [[$1]]',
'parser-template-recursion-depth-warning' => 'Malen er inkludert for mange gonger ($1)',
'language-converter-depth-warning' => 'Språkomformaren si djubdegrense vart overstege ($1)',
'node-count-exceeded-category' => 'Sider der talet på knutepunkt er overskride',
'node-count-exceeded-warning' => 'Sida har overskride talet på knutepunkt',
'expansion-depth-exceeded-category' => 'Sider der utvidingsdjupna er overskriden',
'expansion-depth-exceeded-warning' => 'Sida har overskride utvidingsdjupna',
'parser-unstrip-loop-warning' => 'Det vart oppdaga ei løkke i Unstrip-funksjonen',
'parser-unstrip-recursion-limit' => 'Rekursjonsgrensa for Unstrip-funksjonen er overskriden ($1)',
'converter-manual-rule-error' => 'Det vart oppdaga ein feil i ein manuell språkkonverteringsregel',

# "Undo" feature
'undo-success' => 'Endringa kan attenderullast. Ver venleg og sjå over skilnadene nedanfor for å vere sikker på at du vil attenderulle. Deretter kan du lagre attenderullinga.',
'undo-failure' => 'Endringa kunne ikkje attenderullast grunna konflikt med endringar som er gjorde i mellomtida.',
'undo-norev' => 'Endringa kunne ikkje fjernast fordi han ikkje finst eller vart sletta',
'undo-summary' => 'Rullar attende versjon $1 av [[Special:Contributions/$2|$2]] ([[User talk:$2|diskusjon]])',

# Account creation failure
'cantcreateaccounttitle' => 'Kan ikkje opprette brukarkonto',
'cantcreateaccount-text' => "Kontooppretting frå denne IP-adressa ('''$1''') er blokkert av [[User:$3|$3]].

Grunnen som vart gjeven av $3 er ''$2''",

# History pages
'viewpagelogs' => 'Vis loggane for sida',
'nohistory' => 'Det finst ikkje nokon historikk for denne sida.',
'currentrev' => 'Versjonen no',
'currentrev-asof' => 'Versjonen no frå $1',
'revisionasof' => 'Versjonen frå $1',
'revision-info' => 'Versjonen frå $1 av $2',
'previousrevision' => '← Eldre versjon',
'nextrevision' => 'Nyare versjon →',
'currentrevisionlink' => 'Versjonen no',
'cur' => 'no',
'next' => 'neste',
'last' => 'førre',
'page_first' => 'fyrste',
'page_last' => 'siste',
'histlegend' => 'Merk av for dei versjonane du vil samanlikne og trykk [Enter] eller klikk på knappen nedst på sida.<br />Forklaring: (no) = skilnad frå den noverande versjonen, (førre) = skilnad frå den førre versjonen, <b>s</b> = småplukk',
'history-fieldset-title' => 'Finn dato',
'history-show-deleted' => 'Berre sletta',
'histfirst' => 'Første',
'histlast' => 'Siste',
'historysize' => '({{PLURAL:$1|1 byte|$1 byte}})',
'historyempty' => '(tom)',

# Revision feed
'history-feed-title' => 'Endringshistorikk',
'history-feed-description' => 'Endringshistorikk for denne sida på wikien',
'history-feed-item-nocomment' => '$1 på $2',
'history-feed-empty' => 'Den etterspurde sida finst ikkje. Ho kan vere sletta frå wikien, eller vere flytta. Prøv å [[Special:Search|søke på wikien]] for relevante nye sider.',

# Revision deletion
'rev-deleted-comment' => '(endringssamandrag fjerna)',
'rev-deleted-user' => '(brukarnamnet er fjerna)',
'rev-deleted-event' => '(fjerna loggoppføring)',
'rev-deleted-user-contribs' => '[brukarnamn eller IP-adresse fjerna - endringa er gøymd frå bidraga]',
'rev-deleted-text-permission' => "Denne sideversjonen er vorten '''sletta'''.
Det kan vere detaljar i [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} sletteloggen].",
'rev-deleted-text-unhide' => "Denne sideversjonen er vorten '''sletta'''.
Det finst kanskje detaljar i [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} sletteloggen].
Som administrator kan du framleis [$1 sjå denne versjonen] om du ynskjer å halde fram.",
'rev-suppressed-text-unhide' => "Denne versjonen har vorten '''gøymd'''.
Det finst kanskje meir informasjon i [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} utelatingsloggen].
Som administrator kan du framleis [$1 sjå versjonen] om du ynskjer å halde fram.",
'rev-deleted-text-view' => "Denne sideversjonen er vorten '''sletta'''.
Som administrator kan du sjå han. Det finst kanskje detaljar i [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} sletteloggen].",
'rev-suppressed-text-view' => "Denne sideversjonen har vorten '''gøymd'''.
Som administrator kan du sjå han. Det finst kanskje meir informasjon i [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} utelatingsloggen].",
'rev-deleted-no-diff' => "Du kan ikkje vise denne skilnaden fordi ein av versjonane er vorten '''sletta'''.
Det finst kanskje detaljar i [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} sletteloggen].",
'rev-suppressed-no-diff' => "Du kan ikkje sjå denne skilnaden av di ein av versjonane er vorten '''sletta'''.",
'rev-deleted-unhide-diff' => "Ein av versjonane i denne skilnaden er vorten '''sletta'''.
Det finst detaljar i [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} sletteloggen].
Du kan framleis [$1 sjå skilnaden] om du ynskjer å halda fram.",
'rev-suppressed-unhide-diff' => "Ein av sideversjonane i denne lista over versjonar er vorten '''løynd'''.
Det finst detaljar i [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} løyneloggen].
Du kan framleis [$1 sjå skilnaden] om du ynskjer å halda fram.",
'rev-deleted-diff-view' => "Ein av versjonane i skilnaden er vorten '''sletta'''.
Du kan sjå skilnaden; detaljar finst i [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} sletteloggen].",
'rev-suppressed-diff-view' => "Ei av endringane i skilnaden er vorten '''løynd'''.
Du kan sjå skilnaden; detaljar finst i [{{fullurl:{{#Special:Log}}/suppcess|page={{FULLPAGENAMEE}}}} løyneloggen].",
'rev-delundel' => 'vis/gøym',
'rev-showdeleted' => 'syn',
'revisiondelete' => 'Slett/attopprett versjonar',
'revdelete-nooldid-title' => 'Ugyldig målversjon',
'revdelete-nooldid-text' => 'Du har ikkje oppgjeve kva for versjon(ar) du vil utføre denne handlinga på, versjonen eksisterer ikkje, eller du prøver å gøyme den noverande versjonen.',
'revdelete-nologtype-title' => 'Ingen loggtype oppgjeven',
'revdelete-nologtype-text' => 'Du har ikkje oppgjeve ein loggtype som denne handlinga skal verta utførd på.',
'revdelete-nologid-title' => 'Ugyldig loggelement',
'revdelete-nologid-text' => 'Du har anten ikkje oppgjeve eit loggelement som denne funksjonen skal nytta, eller det oppgjeve loggelementet finst ikkje.',
'revdelete-no-file' => 'Fila som vart synt til finst ikkje.',
'revdelete-show-file-confirm' => 'Er du viss på at du ynskjer å sjå ein sletta versjon av fila "<nowiki>$1</nowiki>" frå $2 ved $3?',
'revdelete-show-file-submit' => 'Ja',
'revdelete-selected' => "'''{{PLURAL:$2|Vald versjon|Valde versjonar}} av [[:$1]]:'''",
'logdelete-selected' => "'''{{PLURAL:$1|Vald loggoppføring|Valde loggoppføringar}}:'''",
'revdelete-text' => "Sletta versjonar og oppføringar vert framleis synlege i sidehistorikken og loggane, men delar av innhaldet deira vert ikkje lenger offentleggjort.'''
Andre administratorar på {{SITENAME}} kan framleis sjå det gøymde innhaldet og attopprette det, med mindre fleire avgrensingar vert lagde inn av sideoperatørane.",
'revdelete-confirm' => 'Stadfest at du ynskjer å gjera dette, at du skjønar konsekvensane, og at du gjer det i samsvar med [[{{MediaWiki:Policy-url}}|retningslinene]].',
'revdelete-suppress-text' => "Løyning av sideversjonar bør '''berre''' verta nytta i dei fylgjande tilfella:
* Upassanda personleg informasjon
*: ''heimeadresser og -telefonnummer,  personnummer, osb.''",
'revdelete-legend' => 'Vel avgrensing for synlegdom',
'revdelete-hide-text' => 'Gøym versjonsteksten',
'revdelete-hide-image' => 'Skjul filinnhald',
'revdelete-hide-name' => 'Gøym handling og sidenamn',
'revdelete-hide-comment' => 'Gøym endringssamandraga',
'revdelete-hide-user' => 'Gøym brukarnamn/IP-adresse',
'revdelete-hide-restricted' => 'Løyn data frå administratorar slik som med andre brukarar',
'revdelete-radio-same' => '(ikkje endra)',
'revdelete-radio-set' => 'Ja',
'revdelete-radio-unset' => 'Nei',
'revdelete-suppress' => 'Fjern informasjon frå administratorar også',
'revdelete-unsuppress' => 'Fjern avgrensingane på dei attoppretta versjonane',
'revdelete-log' => 'Årsak:',
'revdelete-submit' => 'Bruk på {{PLURAL:$1|den valde versjonen|dei valde versjonane}}',
'revdelete-success' => "'''Endringa av versjonsvisinga var vellukka.'''",
'revdelete-failure' => "'''Kunne ikkje oppatera korleis versjonen vert synt:'''
$1",
'logdelete-success' => "'''Visinga av loggoppføringar er endra.'''",
'logdelete-failure' => "'''Korleis loggen skal vera synleg kunne ikkje verta stilt inn:'''
$1",
'revdel-restore' => 'endra synlegdomen',
'revdel-restore-deleted' => 'sletta versjonar',
'revdel-restore-visible' => 'synlege versjonar',
'pagehist' => 'Sidehistorikk',
'deletedhist' => 'Sletta historikk',
'revdelete-hide-current' => 'Feil under skjuling av objektet datert $2, $1: dette er den gjeldande revisjonen.
Han kan ikkje skjulast.',
'revdelete-show-no-access' => 'Feil under vising av objekt datert $2, $1: dette objektet har vorte markert "avgrensa".
Du har ikkje tilgjenge til det.',
'revdelete-modify-no-access' => 'Feil ved endringa av eininga datert $2, $1: denne eininga har vorte markert som "avgrensa".
Du har ikkje tilgang til henne.',
'revdelete-modify-missing' => 'Feil ved endring av eininga med ID $1: ho finst ikkje i databasen!',
'revdelete-no-change' => "'''Åtvaring:''' objektet datert $2, $1 hadde allereie etterspurt innstillingar for korleis eininga skal vera synleg.",
'revdelete-concurrent-change' => 'Feil ved endring av eininga datert $2, $1: statusen ser ut til å ha vorte endra av einkvan annan medan du prøvde å endre ho.
Sjekk gjerne loggføringa.',
'revdelete-only-restricted' => 'Feil under gøyming av objektet datert $2 $1: du kan ikkje gøyma objekt for administratorar utan å i tillegg velja eit av dei andre visingsvala.',
'revdelete-reason-dropdown' => '*Vanlege grunnar til sletting
** Brot på opphavsrett
** Kommentar eller personleg informasjon som ikkje høver seg
** Brukarnamn som ikkje høver seg
** Mogeleg falskt sladder',
'revdelete-otherreason' => 'Anna årsak, eller tilleggsårsak:',
'revdelete-reasonotherlist' => 'Annan grunn',
'revdelete-edit-reasonlist' => 'Endre grunnar til sletting',
'revdelete-offender' => 'Forfattar av denne versjonen:',

# Suppression log
'suppressionlog' => 'Logg over historikkfjerningar',
'suppressionlogtext' => 'Under er ei liste over slettingar og blokkeringar som er gøymde frå administratorane.
Sjå [[Special:BlockList|blokkeringslista]] for oversikta over gjeldande blokkeringar.',

# History merging
'mergehistory' => 'Flett sidehistorikkar',
'mergehistory-header' => 'Denne sida lar deg flette historikken til to sider.
Pass på at den nye sida også har innhald frå den innfletta sida.',
'mergehistory-box' => 'Flett historikkane til to sider:',
'mergehistory-from' => 'Kjeldeside',
'mergehistory-into' => 'Målside:',
'mergehistory-list' => 'Flettbar endringshistorikk',
'mergehistory-merge' => 'Versjonane nedanfor frå [[:$1]] kan flettast med [[:$2]]. Du kan velje å berre flette dei versjonane som kom før tidspunktet som er oppgjeve i tabellen. Merk at bruk av lenkjene nullstiller denne kolonnen.',
'mergehistory-go' => 'Vis flettbare endringar',
'mergehistory-submit' => 'Flett versjonane',
'mergehistory-empty' => 'Ingen endringar kan flettast.',
'mergehistory-success' => '{{PLURAL:$3|Éin versjon|$3 versjonar}} av [[:$1]] er fletta til [[:$2]].',
'mergehistory-fail' => 'Kunne ikkje utføre fletting av historikkane, ver venleg og dobbelsjekk sidene og versjonane du har valt.',
'mergehistory-no-source' => 'Kjeldesida $1 finst ikkje.',
'mergehistory-no-destination' => 'Målsida $1 finst ikkje.',
'mergehistory-invalid-source' => 'Kjeldesida må ha ein gyldig tittel.',
'mergehistory-invalid-destination' => 'Målsida må ha ein gyldig tittel.',
'mergehistory-autocomment' => 'Fletta «[[:$1]]» inn i «[[:$2]]»',
'mergehistory-comment' => 'Fletta «[[:$1]]» inn i «[[:$2]]»: $3',
'mergehistory-same-destination' => 'Kjelde- og målside kan ikkje vere den same.',
'mergehistory-reason' => 'Årsak:',

# Merge log
'mergelog' => 'Flettingslogg',
'pagemerge-logentry' => 'fletta [[$1]] til [[$2]] (versjonar fram til $3)',
'revertmerge' => 'Fjern fletting',
'mergelogpagetext' => 'Nedanfor finn du ei liste over dei siste flettingane av ein sidehistorikk til ein annan.',

# Diffs
'history-title' => '$1: Versjonshistorikk',
'difference-title' => 'Skilnad mellom versjonar av «$1»',
'difference-title-multipage' => '$1 og $2: Skilnad mellom sidene',
'difference-multipage' => '(Skilnad mellom sider)',
'lineno' => 'Line $1:',
'compareselectedversions' => 'Samanlikn valde versjonar',
'showhideselectedversions' => 'Vis/løyn valde versjonar',
'editundo' => 'angre',
'diff-multi' => '({{PLURAL:$1|Éin mellomversjon|$1 mellomversjonar}} frå {{PLURAL:$2|éin brukar|$2 brukarar}} er ikkje {{PLURAL:$1|vist|viste}})',
'diff-multi-manyusers' => '({{PLURAL:$1|Ein mellomversjon|$1 mellomversjonar}} av meir enn $2 {{PLURAL:$2|brukar|brukarar}}  er ikkje {{PLURAL:$1|vist|viste}})',
'difference-missing-revision' => '{{PLURAL:$2|Éin versjon|$2 versjonar}} av skilnaden ($1) vart ikkje funne.

Dette skriv seg som oftast frå at ein har fylgd ei forelda versjonslenkje til ei side som er sletta.
Detaljar kan ein finna i [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} sletteloggen].',

# Search results
'searchresults' => 'Søkjeresultat',
'searchresults-title' => 'Søkjeresultat for «$1»',
'searchresulttext' => 'For meir info om søkjefunksjonen i {{SITENAME}}, sjå [[{{MediaWiki:Helppage}}|Hjelp]].',
'searchsubtitle' => "Du søkte etter '''[[:$1]]''' ([[Special:Prefixindex/$1|alle sider som byrjar med «$1»]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|alle sider som lenkjer til «$1»]])",
'searchsubtitleinvalid' => 'Du søkte etter «$1»',
'toomanymatches' => 'Søket gav for mange treff, prøv ei anna spørjing',
'titlematches' => 'Sidetitlar med treff på førespurnaden',
'notitlematches' => 'Ingen sidetitlar hadde treff på førespurnaden',
'textmatches' => 'Sider med treff på førespurnaden',
'notextmatches' => 'Ingen sider hadde treff på førespurnaden',
'prevn' => 'førre {{PLURAL:$1|$1}}',
'nextn' => 'neste {{PLURAL:$1|$1}}',
'prevn-title' => 'Førre $1 {{PLURAL:$1|resultat|resultat}}',
'nextn-title' => 'Neste $1 {{PLURAL:$1|resultat|resultat}}',
'shown-title' => 'Syn $1 {{PLURAL:$1|resultat|resultat}} for kvar side',
'viewprevnext' => 'Vis ($1 {{int:pipe-separator}} $2) ($3)',
'searchmenu-legend' => 'Søkjeval',
'searchmenu-exists' => "* Sida '''[[$1]]'''",
'searchmenu-new' => "'''Opprett sida «[[:$1|$1]]» på denne wikien.'''",
'searchhelp-url' => 'Help:Innhald',
'searchmenu-prefix' => '[[Special:PrefixIndex/$1|Sjå gjennom alle sider med denne forstavinga]]',
'searchprofile-articles' => 'Innhaldssider',
'searchprofile-project' => 'Hjelp- og prosjektsider',
'searchprofile-images' => 'Multimedia',
'searchprofile-everything' => 'Alt',
'searchprofile-advanced' => 'Avansert',
'searchprofile-articles-tooltip' => 'Søk i $1',
'searchprofile-project-tooltip' => 'Søk i $1',
'searchprofile-images-tooltip' => 'Søk etter filer',
'searchprofile-everything-tooltip' => 'Søk i alt innhald (inkludert diskusjonssider)',
'searchprofile-advanced-tooltip' => 'Søk i visse namnerom',
'search-result-size' => '$1 ({{PLURAL:$2|eitt|$2}} ord)',
'search-result-category-size' => '{{PLURAL:$1|1 medlem|$1 medlemmer}} ({{PLURAL:$2|1 underkategori|$2 underkategoriar}}, {{PLURAL:$3|1 fil|$3 filer}})',
'search-result-score' => 'Relevans: $1&nbsp;%',
'search-redirect' => '(omdirigering $1)',
'search-section' => '(bolken $1)',
'search-suggest' => 'Meinte du: «$1»',
'search-interwiki-caption' => 'Systerprosjekt',
'search-interwiki-default' => '$1-resultat:',
'search-interwiki-more' => '(meir)',
'search-relatedarticle' => 'Relatert',
'mwsuggest-disable' => 'Slå av AJAX-forslag',
'searcheverything-enable' => 'Søk i alle namneroma',
'searchrelated' => 'relatert',
'searchall' => 'alle',
'showingresults' => "Nedanfor er opp til {{PLURAL:$1|'''eitt''' resultat|'''$1''' resultat}} som byrjar med nummer '''$2''' vist{{PLURAL:$1||e}}.",
'showingresultsnum' => "Nedanfor er {{PLURAL:$3|'''eitt''' resultat|'''$3''' resultat}} som byrjar med nummer '''$2''' vist.",
'showingresultsheader' => "{{PLURAL:$5|Resultat '''$1''' av '''$3'''|Resultat '''$1 - $2''' av '''$3'''}} for '''$4'''",
'nonefound' => "'''Merk:''' Som standard blir det berre søkt i enkelte namnerom.
For å søkja i alle, bruk prefikset ''all:'' (det inkluderer diskusjonssider, malar etc.), eller bruk det ønskte namnerommet som prefiks.",
'search-nonefound' => 'Ingen resultat svarte til førespurnaden.',
'powersearch' => 'Søk',
'powersearch-legend' => 'Avansert søk',
'powersearch-ns' => 'Søk i namnerom:',
'powersearch-redir' => 'Vis omdirigeringar',
'powersearch-field' => 'Søk etter',
'powersearch-togglelabel' => 'Hak av:',
'powersearch-toggleall' => 'Alle',
'powersearch-togglenone' => 'Ingen',
'search-external' => 'Eksternt søk',
'searchdisabled' => 'Søkjefunksjonen på {{SITENAME}} er slått av akkurat no.
I mellomtida kan du søkje gjennom Google.
Ver merksam på at registra deira kan vera utdaterte.',

# Quickbar
'qbsettings' => 'Snøggmeny',
'qbsettings-none' => 'Ingen',
'qbsettings-fixedleft' => 'Venstre',
'qbsettings-fixedright' => 'Høgre',
'qbsettings-floatingleft' => 'Flytande venstre',
'qbsettings-floatingright' => 'Flytande høgre',
'qbsettings-directionality' => 'Fast, avhengig av kva retning språket ditt vert lese',

# Preferences page
'preferences' => 'Innstillingar',
'mypreferences' => 'Innstillingar',
'prefs-edits' => 'Tal på endringar:',
'prefsnologin' => 'Ikkje innlogga',
'prefsnologintext' => 'Du må vere <span class="plainlinks">[{{fullurl:{{#Special:UserLogin}}|returnto=$1}} logga inn]</span> for å endre brukarinnstillingane.',
'changepassword' => 'Skift passord',
'prefs-skin' => 'Drakt',
'skin-preview' => 'førehandsvis',
'datedefault' => 'Standard',
'prefs-beta' => 'Betafunksjonar',
'prefs-datetime' => 'Dato og klokkeslett',
'prefs-labs' => 'Testfunksjonar',
'prefs-user-pages' => 'Brukarsider',
'prefs-personal' => 'Brukaropplysningar',
'prefs-rc' => 'Siste endringar',
'prefs-watchlist' => 'Overvakingsliste',
'prefs-watchlist-days' => 'Tal på dagar som viser i overvakingslista:',
'prefs-watchlist-days-max' => 'Høgst {{PLURAL:$1|éin dag|$1 dagar}}',
'prefs-watchlist-edits' => 'Talet på endringar som viser i den utvida overvakingslista:',
'prefs-watchlist-edits-max' => 'Høgst 1000',
'prefs-watchlist-token' => 'Emne på overvakingslista:',
'prefs-misc' => 'Andre',
'prefs-resetpass' => 'Endra passord',
'prefs-changeemail' => '↓Endre e-postadresse',
'prefs-setemail' => '↓Oppgje ei e-postadresse',
'prefs-email' => 'Val for e-post',
'prefs-rendering' => 'Utsjånad',
'saveprefs' => 'Lagre',
'resetprefs' => 'Rull attende',
'restoreprefs' => 'Hent attende alle standardinnstillingane',
'prefs-editing' => 'Endring',
'prefs-edit-boxsize' => 'Storleiken på redigeringsvindauget.',
'rows' => 'Rekkjer',
'columns' => 'Kolonnar',
'searchresultshead' => 'Søk',
'resultsperpage' => 'Resultat per side',
'stub-threshold' => 'Grense (i byte) for at frø/spirer skal formaterast <a href="#" class="stub">slik</a>:',
'stub-threshold-disabled' => 'Deaktivert',
'recentchangesdays' => 'Tal på dagar som viser på siste endringar:',
'recentchangesdays-max' => '(høgst $1 {{PLURAL:$1|dag|dagar}})',
'recentchangescount' => 'Tal på endringar som viser som standard:',
'prefs-help-recentchangescount' => 'Dette inkluderer nylege endringar, sidehistorikk og loggar.',
'prefs-help-watchlist-token' => 'Om du fyller ut dette feltet med eit hemmeleg tal, vil det opprettast ei RSS opplisting for overvakingslista di.
Alle som veit det rette talet vil vera i stand til å lesa overvakingslista di, så vél gjerne ein trygg verdi.
Her er det framlegg til eit tal som kan nyttast, tilfelleleg henta fram: $1',
'savedprefs' => 'Brukarinnstillingane er lagra.',
'timezonelegend' => 'Tidssone:',
'localtime' => 'Lokaltid:',
'timezoneuseserverdefault' => '↓Nytt standardinnstillinga til wikien ($1)',
'timezoneuseoffset' => 'Anna (oppgje skilnad)',
'timezoneoffset' => 'Skilnad¹:',
'servertime' => 'Tenartid:',
'guesstimezone' => 'Hent tidssone frå nettlesaren',
'timezoneregion-africa' => 'Afrika',
'timezoneregion-america' => 'Amerika',
'timezoneregion-antarctica' => 'Antarktis',
'timezoneregion-arctic' => 'Arktis',
'timezoneregion-asia' => 'Asia',
'timezoneregion-atlantic' => 'Atlanterhavet',
'timezoneregion-australia' => 'Australia',
'timezoneregion-europe' => 'Europa',
'timezoneregion-indian' => 'Indiahavet',
'timezoneregion-pacific' => 'Stillehavet',
'allowemail' => 'Tillat e-post frå andre brukarar',
'prefs-searchoptions' => 'Søk',
'prefs-namespaces' => 'Namnerom',
'defaultns' => 'Søk elles i desse namneromma:',
'default' => 'standard',
'prefs-files' => 'Filer',
'prefs-custom-css' => 'Eigendefinert CSS',
'prefs-custom-js' => 'Eigendefinert JavaScript',
'prefs-common-css-js' => 'Delt CSS/JavaScript for alle draktene:',
'prefs-reset-intro' => 'Du kan nytta denne sida til å tilbakestilla innstillingane dine til standardinnstillingane.
Dette kan ikkje tilbakestillast.',
'prefs-emailconfirm-label' => 'Stadfesting av e-post:',
'prefs-textboxsize' => 'Storleiken til redigeringsvindauga',
'youremail' => 'E-post:',
'username' => 'Brukarnamn:',
'uid' => 'Brukar-ID:',
'prefs-memberingroups' => 'Medlem av {{PLURAL:$1|denne gruppa|desse gruppene}}:',
'prefs-registration' => 'Registreringstid:',
'yourrealname' => 'Verkeleg namn:',
'yourlanguage' => 'Språk:',
'yourvariant' => 'Språkvariant for innhald:',
'prefs-help-variant' => 'Varianten eller ortografien som du føretrekkjer at innhaldet i wikien vert vist på.',
'yournick' => 'Signatur:',
'prefs-help-signature' => 'Kommentarar på diskusjonssider bør alltid signerast med «<nowiki>~~~~</nowiki>», som vil konverterast til signaturen din med tidspunkt.',
'badsig' => 'Ugyldig råsignatur, sjekk HTML-kodinga.',
'badsiglength' => 'Signaturen din er for lang. Han må vere under {{PLURAL:$1|eitt teikn|$1 teikn}}.',
'yourgender' => 'Kjønn:',
'gender-unknown' => 'Ikkje oppgjeve',
'gender-male' => 'Mann',
'gender-female' => 'Kvinne',
'prefs-help-gender' => 'Valfritt: nytta for at programvara skal retta seg til brukaren med rett kjønn i systemmeldingar. Denne informasjonen vil vera offentleg.',
'email' => 'E-post',
'prefs-help-realname' => '* Namn (valfritt): Om du vel å fylle ut dette feltet, vil informasjonen bli brukt til å godskrive arbeid du har gjort.',
'prefs-help-email' => 'Å oppgje e-postadresse er valfritt, men lar deg ta i mot nytt passord om du gløymer det gamle.',
'prefs-help-email-others' => 'Du kan òg velje å la andre brukarar kontakte deg på e-post via brukarsida di utan å røpe identiteten din.',
'prefs-help-email-required' => 'E-postadresse må oppgjevast.',
'prefs-info' => 'Grunnleggjande informasjon',
'prefs-i18n' => 'Internasjonalisering',
'prefs-signature' => 'Signatur',
'prefs-dateformat' => 'Datoformat',
'prefs-timeoffset' => 'Tidsforskuving',
'prefs-advancedediting' => 'Avanserte val',
'prefs-advancedrc' => 'Avanserte val',
'prefs-advancedrendering' => 'Avanserte val',
'prefs-advancedsearchoptions' => 'Avanserte val',
'prefs-advancedwatchlist' => 'Avanserte val',
'prefs-displayrc' => 'Val for vising',
'prefs-displaysearchoptions' => 'Val for vising',
'prefs-displaywatchlist' => 'Val for vising',
'prefs-diffs' => 'Skilnader',

# User preference: e-mail validation using jQuery
'email-address-validity-valid' => 'E-postadressa ser ut til å vera gyldig',
'email-address-validity-invalid' => 'Skriv inn ei gyldig e-postaddresse.',

# User rights
'userrights' => 'Administrering av brukartilgang',
'userrights-lookup-user' => 'Administrer brukargrupper',
'userrights-user-editname' => 'Skriv inn brukarnamn:',
'editusergroup' => 'Endre brukargrupper',
'editinguser' => "Endrar brukarrettane til brukaren '''[[User:$1|$1]]''' $2",
'userrights-editusergroup' => 'Endre brukargrupper',
'saveusergroups' => 'Lagre brukargrupper',
'userrights-groupsmember' => 'Medlem av:',
'userrights-groupsmember-auto' => 'Implisitt medlem av:',
'userrights-groups-help' => 'Du kan endre kva for grupper denne brukaren er medlem av.
* Ein krossa boks tyder at brukaren er medlem av denne gruppa.
* Ein ikkjekrossa boks tyder at brukaren ikkje er medlem av denne gruppa.
* Ein * tyder at du ikkje kan fjerne gruppemedlemskapen etter at du har lagt han til, eller omvendt.',
'userrights-reason' => 'Årsak:',
'userrights-no-interwiki' => 'Du har ikkje tilgang til å endre brukartilgangar på andre wikiar.',
'userrights-nodatabase' => 'Databasen $1 finst ikkje eller er ikkje lokal.',
'userrights-nologin' => 'Du må [[Special:UserLogin|logge inn]] med ein administrator- og/eller byråkratkonto for å endre brukartilgangar.',
'userrights-notallowed' => 'Kontoen din har ikkje løyve til å leggja til eller fjerna brukarrettar.',
'userrights-changeable-col' => 'Grupper du kan endre',
'userrights-unchangeable-col' => 'Grupper du ikkje kan endre',

# Groups
'group' => 'Gruppe:',
'group-user' => 'Brukarar',
'group-autoconfirmed' => 'Automatisk godkjende brukarar',
'group-bot' => 'Robotar',
'group-sysop' => 'Administratorar',
'group-bureaucrat' => 'Byråkratar',
'group-suppress' => 'Historikkfjernarar',
'group-all' => '(alle)',

'group-user-member' => '{{GENDER:$1|brukar}}',
'group-autoconfirmed-member' => '{{GENDER:$1|automatisk godkjend brukar}}',
'group-bot-member' => '{{GENDER:$1|robot}}',
'group-sysop-member' => '{{GENDER:$1|administrator}}',
'group-bureaucrat-member' => '{{GENDER:$1|byråkrat}}',
'group-suppress-member' => '{{GENDER:$1|historikkfjernar}}',

'grouppage-user' => '{{ns:project}}:Brukarar',
'grouppage-autoconfirmed' => '{{ns:project}}:Automatisk godkjende brukarar',
'grouppage-bot' => '{{ns:project}}:Robotar',
'grouppage-sysop' => '{{ns:project}}:Administratorar',
'grouppage-bureaucrat' => '{{ns:project}}:Byråkratar',
'grouppage-suppress' => '{{ns:project}}:Historikkfjerning',

# Rights
'right-read' => 'Sjå sider',
'right-edit' => 'Endre sider',
'right-createpage' => 'Opprette sider (som ikkje er diskusjonssider)',
'right-createtalk' => 'Opprette diskusjonssider',
'right-createaccount' => 'Opprette nye brukarkontoar',
'right-minoredit' => 'Merke endringar som småplukk',
'right-move' => 'Flytte sider',
'right-move-subpages' => 'Flytte sider med undersider',
'right-move-rootuserpages' => 'Flytte hovudbrukarsider',
'right-movefile' => 'Flytta filer',
'right-suppressredirect' => 'Treng ikkje lage omdirigering frå det gamle namnet når sida vert flytta',
'right-upload' => 'Laste opp filer',
'right-reupload' => 'Skrive over ei eksisterande fil',
'right-reupload-own' => 'Skrive over eigne filer',
'right-reupload-shared' => 'Skrive over delte filer lokalt',
'right-upload_by_url' => 'Laste opp ei fil frå ei nettadresse',
'right-purge' => 'Reinse mellomlageret for sider',
'right-autoconfirmed' => 'Endre halvlåste sider',
'right-bot' => 'Bli handsama som ein automatisk prosess.',
'right-nominornewtalk' => 'Mindre endringar på diskujsonssida gjev ikkje beskjed om at du har nye meldingar.',
'right-apihighlimits' => 'Bruke API med høgare grenser',
'right-writeapi' => 'Redigere via API',
'right-delete' => 'Slette sider',
'right-bigdelete' => 'Slette sider med lange historikkar',
'right-deletelogentry' => 'Sletta og attoppretta visse loggoppføringar',
'right-deleterevision' => 'Slette og gjenopprette enkeltendringar av sider',
'right-deletedhistory' => 'Sjå sletta sidehistorikk utan tilhøyrande sidetekst',
'right-deletedtext' => 'Sjå sletta tekst og endringar i høve til sletta versjonar',
'right-browsearchive' => 'Søk i sletta sider',
'right-undelete' => 'Attopprett sider',
'right-suppressrevision' => 'Sjå og gjenopprett skjulte siderevisjonar',
'right-suppressionlog' => 'Sjå private loggar',
'right-block' => 'Blokkere andre brukarar frå å redigere',
'right-blockemail' => 'Blokkere brukarar frå å sende e-post',
'right-hideuser' => 'Blokkere eit brukarnamn og skjule det frå ålmenta.',
'right-ipblock-exempt' => 'Kan gjere endringar frå blokkerte IP-adresser',
'right-proxyunbannable' => 'Kan gjere endringar frå blokkerte proxyar',
'right-unblockself' => 'Avblokkera seg sjølve',
'right-protect' => 'Endra vernenivå og verna sider',
'right-editprotected' => 'Endre verna sider',
'right-editinterface' => 'Redigere brukargrensesnittet',
'right-editusercssjs' => 'Endre andre brukarar sine CSS- og JS-filer',
'right-editusercss' => 'Endre andre brukarar sine CSS-filer',
'right-edituserjs' => 'Endre andre brukarar sine JS-filer',
'right-rollback' => 'Snøgt rulla attende endringane til den siste brukaren som endra ei viss side',
'right-markbotedits' => 'Markere tilbakerullingar som robotendringar',
'right-noratelimit' => 'Vert ikkje påverka av snøggleiksgrenser',
'right-import' => 'Importere sider frå andre wikiar',
'right-importupload' => 'Importere sider via opplasting',
'right-patrol' => 'Markere endringar som godkjende',
'right-autopatrol' => 'Få sine eigne endringar automatisk merkte som godkjende',
'right-patrolmarks' => 'Vis godkjende endringar i siste endringar',
'right-unwatchedpages' => 'Sjå lista over sider som ikkje er overvaka',
'right-mergehistory' => 'Flette sidehistorikkar',
'right-userrights' => 'Endre alle brukarrettar',
'right-userrights-interwiki' => 'Endre rettar for brukarar på andre wikiar',
'right-siteadmin' => 'Låse og låse opp databasen',
'right-override-export-depth' => 'Eksporter sider inkludert lenkte sider til ei djupn på 5',
'right-sendemail' => 'Senda e-post til andre brukarar',
'right-passwordreset' => 'Sjå e-postar for passord som er stilte attende',

# User rights log
'rightslog' => 'Brukartilgangslogg',
'rightslogtext' => 'Dette er ein logg over endringar av brukartilgang.',
'rightslogentry' => 'endra brukartilgangen til $1 frå $2 til $3',
'rightslogentry-autopromote' => '↓vart automatisk forfremja frå $2 til $3',
'rightsnone' => '(ingen)',

# Associated actions - in the sentence "You do not have permission to X"
'action-read' => 'sjå denne sida',
'action-edit' => 'endre denne sida',
'action-createpage' => 'opprette sider',
'action-createtalk' => 'opprette diskusjonssider',
'action-createaccount' => 'opprette denne brukarkontoen',
'action-minoredit' => 'merke denne endringa som småplukk',
'action-move' => 'flytte denne sida',
'action-move-subpages' => 'flytte denne sida og undersidene hennar',
'action-move-rootuserpages' => 'flytte hovudbrukarsider',
'action-movefile' => 'flytta denne fila',
'action-upload' => 'laste opp denne fila',
'action-reupload' => 'skrive over den noverande fila',
'action-reupload-shared' => 'skrive over denne fila i fellesdatabasen',
'action-upload_by_url' => 'laste påå denne fila frå ein URL',
'action-writeapi' => 'bruke skrive-API',
'action-delete' => 'slette denne sida',
'action-deleterevision' => 'slette denne endringa',
'action-deletedhistory' => 'sjå slettehistorikken til sida',
'action-browsearchive' => 'søke i sletta sider',
'action-undelete' => 'attopprette denne sida',
'action-suppressrevision' => 'sjå og attopprette denne skjulte endringa',
'action-suppressionlog' => 'sjå denne private loggen',
'action-block' => 'blokkere denne brukaren frå å gjere endringar',
'action-protect' => 'endre vernenivået til sida',
'action-rollback' => 'snøgt rulla attende endringane til den siste brukaren som endra ei viss side',
'action-import' => 'importere denne sida frå ein annan wiki',
'action-importupload' => 'importere denne sida frå ei opplasta fil',
'action-patrol' => 'merke andre endringar av andre brukar som patruljert',
'action-autopatrol' => 'merke endringane dine som partuljert',
'action-unwatchedpages' => 'vise lista over uovervaka sider',
'action-mergehistory' => 'flette historikken til denne sida',
'action-userrights' => 'endre alle brukarrettar',
'action-userrights-interwiki' => 'endre brukarrettar for brukarar på andre wikiar',
'action-siteadmin' => 'låse eller låse opp databasen',
'action-sendemail' => 'senda e-postar',

# Recent changes
'nchanges' => '{{PLURAL:$1|Éi endring|$1 endringar}}',
'recentchanges' => 'Siste endringar',
'recentchanges-legend' => 'Alternativ for siste endringar',
'recentchanges-summary' => 'På denne sida ser du dei sist endra sidene i {{SITENAME}}.',
'recentchanges-feed-description' => 'Fylg med på dei siste endringane på denne wikien med dette abonnementet.',
'recentchanges-label-newpage' => 'Endringa oppretta ei ny side',
'recentchanges-label-minor' => 'Endringa er småplukk',
'recentchanges-label-bot' => 'Denne endringa vart gjort av ein bot',
'recentchanges-label-unpatrolled' => 'Endringa er ikkje patruljert enno',
'rcnote' => "Nedanfor er {{PLURAL:$1|den siste endringa gjord|dei siste '''$1''' endringane gjorde}} {{PLURAL:$2|den siste dagen|dei siste '''$2''' dagane}}, for $4, kl. $5.",
'rcnotefrom' => "Nedanfor vert opp til '''$1''' endringar sidan  ''' $2''' viste.",
'rclistfrom' => 'Vis nye endringar sidan $1',
'rcshowhideminor' => '$1 småplukk',
'rcshowhidebots' => '$1 robotar',
'rcshowhideliu' => '$1 innlogga brukarar',
'rcshowhideanons' => '$1 anonyme brukarar',
'rcshowhidepatr' => '$1 godkjende endringar',
'rcshowhidemine' => '$1 endringane mine',
'rclinks' => 'Vis dei siste $1 endringane dei siste $2 dagane<br />$3',
'diff' => 'skil',
'hist' => 'hist',
'hide' => 'Gøym',
'show' => 'Vis',
'minoreditletter' => 's',
'newpageletter' => 'n',
'boteditletter' => 'b',
'number_of_watching_users_pageview' => '[{{PLURAL:$1|Éin brukar|$1 brukarar}} overvakar]',
'rc_categories' => 'Avgrens til kategoriar (skilde med «|»)',
'rc_categories_any' => 'Alle',
'rc-change-size-new' => '$1 {{PLURAL:$1|byte}} etter endringa',
'newsectionsummary' => '/* $1 */ ny bolk',
'rc-enhanced-expand' => 'Vis detaljar (krev JavaScript)',
'rc-enhanced-hide' => 'Skjul detaljar',
'rc-old-title' => 'opphavleg oppretta som «$1»',

# Recent changes linked
'recentchangeslinked' => 'Relaterte endringar',
'recentchangeslinked-feed' => 'Relaterte endringar',
'recentchangeslinked-toolbox' => 'Relaterte endringar',
'recentchangeslinked-title' => 'Endringar relaterte til «$1»',
'recentchangeslinked-noresult' => 'Det er ikkje gjort endringar på sidene som var lenkja hit i den oppgjevne perioden.',
'recentchangeslinked-summary' => "Dette er ei liste over nylege endringar som er gjorde på sider som vert lenkja til frå ei oppgjeven side (eller på medlemer av ein oppgjeven kategori). Sider på [[Special:Watchlist|overvakingslista di]] er '''utheva'''.",
'recentchangeslinked-page' => 'Sidenamn:',
'recentchangeslinked-to' => 'Vis endringar på sider som lenkjer til den gitte sida i staden',

# Upload
'upload' => 'Last opp fil',
'uploadbtn' => 'Last opp fil',
'reuploaddesc' => 'Attende til opplastingsskjemaet.',
'upload-tryagain' => 'Send inn endra filskildring',
'uploadnologin' => 'Ikkje innlogga',
'uploadnologintext' => 'Du lyt vera [[Special:UserLogin|innlogga]] for å kunna laste opp filer.',
'upload_directory_missing' => 'Opplastingsmappa ($1) manglar og kunne ikkje opprettast av tenaren.',
'upload_directory_read_only' => 'Opplastingsmappa ($1) er skriveverna.',
'uploaderror' => 'Feil under opplasting av fil',
'upload-recreate-warning' => "'''Åtvaring: Ei fil med dette namnet er vorten sletta eller flytta.'''

Slette- og flytteloggen til sida er gjeven opp her:",
'uploadtext' => "Bruk skjemaet under for å laste opp filer.
For å sjå eller søkje i eksisterande filer, gå til [[Special:FileList|fillista]]. Opplastingar vert òg lagra i [[Special:Log/upload|opplastingsloggen]], og slettingar i [[Special:Log/delete|sletteloggen]].

For å bruke ei fil på ei side, bruk ei lenkje på eit liknande format:
*'''<code><nowiki>[[</nowiki>{{ns:file}}:Filnamn.jpg<nowiki>]]</nowiki></code>''' for å bruke biletet i opphavleg form
*'''<code><nowiki>[[</nowiki>{{ns:file}}:Filnamn.png|200px|mini|venstre|Alternativ tekst<nowiki>]]</nowiki></code>''' for å bruke biletet med ei breidd på 200&nbsp;pikslar, venstrestilt og med «Alternativ tekst» som bilettekst
*'''<code><nowiki>[[</nowiki>{{ns:media}}:Filnamn.ogg<nowiki>]]</nowiki></code>''' for å lenkje direkte til fila utan å vise ho",
'upload-permitted' => 'Godtekne filtypar: $1.',
'upload-preferred' => 'Føretrekte filtypar: $1.',
'upload-prohibited' => 'Ikkje godtekne filtypar: $1.',
'uploadlog' => 'opplastingslogg',
'uploadlogpage' => 'Opplastingslogg',
'uploadlogpagetext' => 'Dette er ei liste over filer som nyleg er lasta opp.',
'filename' => 'Filnamn',
'filedesc' => 'Skildring',
'fileuploadsummary' => 'Skildring:',
'filereuploadsummary' => 'Filendringar:',
'filestatus' => 'Opphavsrettsstatus:',
'filesource' => 'Kjelde:',
'uploadedfiles' => 'Filer som er opplasta',
'ignorewarning' => 'Sjå bort frå åtvaringa og lagre fila likevel',
'ignorewarnings' => 'Oversjå åtvaringar',
'minlength1' => 'Filnamn må ha minst eitt teikn.',
'illegalfilename' => 'Filnamnet «$1» inneheld teikn som ikkje er tillatne i sidetitlar. Skift namn på fila og prøv på nytt.',
'filename-toolong' => 'Filnamn kan ikkje vera lengre enn 240 byte.',
'badfilename' => 'Namnet på fila har vorte endra til «$1».',
'filetype-mime-mismatch' => 'Filendinga «.$1» samsvarar ikkje med MIME-typen som er funnen i fila ($2).',
'filetype-badmime' => 'Filer av MIME-typen «$1» kan ikkje lastast opp.',
'filetype-bad-ie-mime' => 'Kan ikkje lasta opp fila då Internet Explorer ville merka ho som "$1", ein ikkje-tillate og potensielt farleg filtype.',
'filetype-unwanted-type' => "«'''.$1'''» er ein uynskt filtype.
{{PLURAL:$3|Føretrekt filtype er|Føretrekte filtypar er}} $2.",
'filetype-banned-type' => "'''«.$1»''' er ikkje {{PLURAL:$4|ein tillaten filtype|tillatne filtypar}}.
{{PLURAL:$3|Tillaten filtype|Tillatne filtypar}} er $2.",
'filetype-missing' => 'Fila har inga ending (som t.d. «.jpg»).',
'empty-file' => 'Fila du leverte var tom.',
'file-too-large' => 'Fila du leverte var for stor.',
'filename-tooshort' => 'Filnamnet er for kort.',
'filetype-banned' => 'Denne filtypen er ikkje tillaten.',
'verification-error' => 'Denne fila klarde ikkje verifiseringsprossesen.',
'hookaborted' => 'Endringa du freista vart avbroten av ei utviding.',
'illegal-filename' => 'Filnamnet er ikkje tillate.',
'overwrite' => 'Det er ikkje tillate å skriva over ei eksisterande fil.',
'unknown-error' => 'Det oppstod ein ukjend feil.',
'tmp-create-error' => 'Kunne ikkje oppretta mellombels fil.',
'tmp-write-error' => 'Feil ved skriving av midlertidig fil.',
'large-file' => 'Det er tilrådd at filene ikkje er større enn $1, denne fila er $2.',
'largefileserver' => 'Denne fila er større enn det tenaren tillèt.',
'emptyfile' => 'Det ser ut til at fila du lasta opp er tom. Dette kan komma av ein skrivefeil i filnamnet. Sjekk og tenk etter om du verkeleg vil laste opp fila.',
'windows-nonascii-filename' => 'Wikien stør ikkje filnamn med spesialteikn.',
'fileexists' => 'Ei fil med dette namnet finst allereie, sjekk <strong>[[:$1]]</strong> om du ikkje er sikker på om du vil endre namnet.
[[$1|thumb]]',
'filepageexists' => 'Skildringssida for denne fila finst allereie på <strong>[[:$1]]</strong>, men det finst ikkje noka fil med dette namnet. Endringssamandraget du skriv inn vert ikkje vist på skildringssida. For at det skal dukke opp der, må du skrive det inn på skildringssida manuelt etter å ha lasta opp fila.
[[$1|thumb]]',
'fileexists-extension' => 'Ei fil med eit liknande namn finst allereie: [[$2|thumb]]
* Namnet på fila du lastar opp: <strong>[[:$1]]</strong>
* Namnet på den eksisterande fila: <strong>[[:$2]]</strong>
Ver venleg og vel eit anna namn.',
'fileexists-thumbnail-yes' => 'Fila ser ut til å vere eit bilete med redusert storleik. [[$1|thumb]]
Ver venleg og sjekk fila <strong>[[:$1]]</strong>.
Dersom denne er det same biletet i original storleik, er det ikkje nødvendig å laste opp ein mindre versjon.',
'file-thumbnail-no' => "Filnamnet byrjar med <strong>$1</strong>.
Det ser ut til å vere eit bilte med redusert storleik''(miniatyrbilete)''.
Om du har dette bilete i stor utgåve, så last det opp eller endre filnamnet på denne fila.",
'fileexists-forbidden' => 'Ei fil med dette namnet finst allereie, og ho kan ikkje verte skriven over.
Om du framleis ynskjer å laste opp fila, lyt du gå attende og nytte eit anna namn. [[File:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => 'Ei fil med dette namnet finst frå før i det delte fillageret.
Om du framleis ønskjer å laste opp fila, gå tilbake og last ho opp med eit anna namn. [[File:$1|thumb|center|$1]]',
'file-exists-duplicate' => 'Denne fila er ein duplikat av følgjande {{PLURAL:$1|fil|filer}}:',
'file-deleted-duplicate' => 'Ei identisk fil ([[:$1]]) er tidlegare sletta. Du bør sjekka slettehistorikken hennar før du held fram med å lasta henne opp på nytt.',
'uploadwarning' => 'Opplastingsåtvaring',
'uploadwarning-text' => 'Ver venleg og endra filskildringa nedanfor og prøv på nytt',
'savefile' => 'Lagre fil',
'uploadedimage' => 'lasta opp «[[$1]]»',
'overwroteimage' => 'lasta opp ein ny versjon av «[[$1]]»',
'uploaddisabled' => 'Beklagar, funksjonen for opplasting er deaktivert på denne nettenaren.',
'copyuploaddisabled' => 'Opplasting gjennom URL er slege av.',
'uploadfromurl-queued' => 'Opplastinga di er sett i kø.',
'uploaddisabledtext' => 'Filopplasting er slått av.',
'php-uploaddisabledtext' => 'PHP-filopplasting er deaktivert. Sjå innstillinga for file_uploads.',
'uploadscripted' => 'Fila inneheld HTML- eller skriptkode som feilaktig kan bli tolka og køyrd av nettlesarar.',
'uploadvirus' => 'Fila innheld virus! Detaljar: $1',
'uploadjava' => 'Fila er ei ZIP-fil som inneheld ei Java .class-fil.
Opplasting av Java-filer er ikkje tillate av di dei kan gå utanom tryggingsavgrensingane.',
'upload-source' => 'Kjeldefil',
'sourcefilename' => 'Filsti:',
'sourceurl' => 'Kjelde-URL:',
'destfilename' => 'Målfilnamn:',
'upload-maxfilesize' => 'Maksimal filstorleik: $1',
'upload-description' => 'Filskildring',
'upload-options' => 'Val for opplasting',
'watchthisupload' => 'Overvak denne fila',
'filewasdeleted' => 'Ei fil med same namnet er tidlegare vorte lasta opp og sletta. Du bør sjekke $1 før du prøver å laste henne opp att.',
'filename-bad-prefix' => "Namnet på fila du lastar opp byrjar med '''«$1»''', som er eit inkjeseiande namn som vanlegvis vert gjeve til bilete automatisk av digitale kamera. Ver venleg og vel eit meir skildrande namn på fila di.",
'filename-prefix-blacklist' => ' #<!-- leave this line exactly as it is --> <pre>
# Syntaksen er som følgjer:
#  * Alt frå teiknet «#» til slutten av linja er ein kommentar
#  * Alle linjer som ikkje er blanke er ei forstaving som vanlegvis vert nytta automatisk av digitale kamera
CIMG # Casio
DSC_ # Nikon
DSCF # Fuji
DSCN # Nikon
DUW # nokre mobiltelefontypar
IMG # generisk
JD # Jenoptik
MGP # Pentax
PICT # div.
  #</pre> <!-- leave this line exactly as it is -->',
'upload-success-subj' => 'Opplastinga er ferdig',
'upload-success-msg' => 'Opplastinga di frå [$2] var vellukka. Han er tilgjengeleg her: [[:{{ns:file}}:$1]]',
'upload-failure-subj' => 'Opplastingsproblem',
'upload-failure-msg' => 'Det oppstod eit problem med opplastinga di frå [$2]:

$1',
'upload-warning-subj' => 'Opplastingsåtvaring',
'upload-warning-msg' => 'Det oppstod eit problem med opplastinga di frå [$2]. Du kan gå attende til [[Special:Upload/stash/$1|opplastingsskjemaet]] for å løysa dette problemet.',

'upload-proto-error' => 'Feil protokoll',
'upload-proto-error-text' => 'Fjernopplasting krev nettadresser som byrjar med <code>http://</code> eller <code>ftp://</code>.',
'upload-file-error' => 'Intern feil',
'upload-file-error-text' => 'Ein intern feil oppstod under forsøk på å lage ei mellombels fil på tenaren. Ver venleg og ta kontakt med ein [[Special:ListUsers/sysop|administrator]].',
'upload-misc-error' => 'Ukjend feil ved opplastinga',
'upload-misc-error-text' => 'Ein ukjend feil oppstod under opplastinga. Ver venleg og stadfest at nettadressa er gyldig og tilgjengeleg, og prøv ein gong til. Dersom problemet held fram, ta kontakt med ein [[Special:ListUsers/sysop|administrator]].',
'upload-too-many-redirects' => 'URL-en inneheldt for mange omdirigeringar',
'upload-unknown-size' => 'Ukjend storleik',
'upload-http-error' => 'Ein HTTP-feil oppstod: $1',
'upload-copy-upload-invalid-domain' => 'Kopiopplastingar er ikkje tilgjengelege frå dette domenet.',

# File backend
'backend-fail-stream' => 'Kunne ikkje strøyma fila «$1».',
'backend-fail-backup' => 'Kunne ikkje tryggingskopiera fila «$1».',
'backend-fail-notexists' => 'Fila $1 finst ikkje.',
'backend-fail-hashes' => 'Kunne ikkje henta filnummer for samanlikning.',
'backend-fail-notsame' => 'Ein ikkje-identisk fil finst alt på «$1».',
'backend-fail-invalidpath' => '$1 er ikkje ein gyldig lagringsstig.',
'backend-fail-delete' => 'Kunne ikkje sletta fila «$1».',
'backend-fail-alreadyexists' => 'Fila $1 finst frå før.',
'backend-fail-store' => 'Kunne ikkje lagra fila «$1» på «$2».',
'backend-fail-copy' => 'Kunne ikkje kopiera fila «$1» til «$2».',
'backend-fail-move' => 'Kunne ikkje flytta fila «$1» til «$2».',
'backend-fail-opentemp' => 'Kunne ikkje opna mellombels fil.',
'backend-fail-writetemp' => 'Kunne ikkje skriva til mellombels fil.',
'backend-fail-closetemp' => 'Kunne ikkje lata att mellombels fil.',
'backend-fail-read' => 'Kunne ikkje lesa fila «$1».',
'backend-fail-create' => 'Kunne ikkje oppretta fila «$1».',
'backend-fail-maxsize' => 'Kunne ikkje skriva fila «$1» av di ho er større enn {{PLURAL:$2|éin byte|$2 byte}}.',
'backend-fail-readonly' => "Largingsbaksystemet «$1» er for tida skriveverna. Oppgjeven grunn er: «''$2''»",
'backend-fail-synced' => 'Fila «$1» er i ei inkonsistent stode i dei interne lagringsbaksystema',
'backend-fail-connect' => 'Kunne ikkje kopla til filbaksystemet «$1».',
'backend-fail-internal' => 'Ein ukjend feil oppstod i lagringsbaksystemet «$1».',
'backend-fail-contenttype' => 'Kunne ikkje avgjera innhaldstypen til fila som skulle lagrast på «$1».',
'backend-fail-batchsize' => 'Baksystemet vart gjeve ei gruppe med $1 {{PLURAL:$1|filoperasjon|filoperasjonar}}; grensa er $2 {{PLURAL:$2|operasjon|operasjonar}}.',
'backend-fail-usable' => 'Kunne ikkje lesa eller skriva fila «$1» grunna vantande rettar eller mapper/kjerald.',

# File journal errors
'filejournal-fail-dbconnect' => 'Kunne ikkje kopla til journaldatabasen for lagringsbaksystemet «$1».',
'filejournal-fail-dbquery' => 'Kunne ikkje oppdatera journaldatabasen for lagringsbaksystemet «$1».',

# Lock manager
'lockmanager-notlocked' => 'Kunne ikkje låsa opp «$1» av di han ikkje er låst',
'lockmanager-fail-closelock' => 'Kunne ikkje lata att låsefila for «$1».',
'lockmanager-fail-deletelock' => 'Kunne ikkje sletta låsefila for «$1».',
'lockmanager-fail-acquirelock' => 'Kunne ikkje henta lås for «$1».',
'lockmanager-fail-openlock' => 'Kunne ikkje opna låsefila for «$1».',
'lockmanager-fail-releaselock' => 'Kunne ikkje løysa låsen for «$1».',
'lockmanager-fail-db-bucket' => 'Kunne ikkje kontakta nok låsedatabasar i bytta $1.',
'lockmanager-fail-db-release' => 'Kunne ikkje løysa låsane på databasen $1.',
'lockmanager-fail-svr-acquire' => 'Kunne ikkje henta låsane på tenaren $1.',
'lockmanager-fail-svr-release' => 'Kunne ikkje løysa låsane på tenaren $1.',

# ZipDirectoryReader
'zip-file-open-error' => 'Det oppstod ein feil under opninga av fila for ZIP-undersøking.',
'zip-wrong-format' => 'Den oppgjevne fila var ikkje ei ZIP-fil',
'zip-bad' => 'Fila er ei skadd eller på annan måte uleseleg ZIP-fil.
Ho kan ikkje tryggingskontrollerast.',
'zip-unsupported' => 'Fila er ei ZIP-fil son nyttar ZIP-funksjonar som ikkje er stødde av MediaWiki.
Ho kan ikkje tryggingskontrollerast godt nok.',

# Special:UploadStash
'uploadstash' => 'Lasta opp løynd samling',
'uploadstash-summary' => 'Denne sida gjev tilgang til filer som er opplasta (eller i ferd med å verta det), men som ikkje er publiserte til wikien. Desse filene er ikkje synlege for andre enn opplastaren.',
'uploadstash-clear' => 'Fjerna filer i den løynde samlinga',
'uploadstash-nofiles' => 'Du har ingen filer i den løynde samlinga.',
'uploadstash-badtoken' => 'Utføringa av handlinga lukkast ikkje; kan henda av di endringsrettane dine har gått ut. Freista om att.',
'uploadstash-errclear' => 'Fjerning av filene var mislykka.',
'uploadstash-refresh' => 'Oppdater fillista',
'invalid-chunk-offset' => 'Ugild delforskuving',

# img_auth script messages
'img-auth-accessdenied' => 'Tilgjenge avslått',
'img-auth-nopathinfo' => 'PATH_INFO saknar.
Filtenaren din er ikkje sett opp for å gje denne informasjonen.
Han kan vera CGI-basert og ikkje stø img_auth.
Sjå https://www.mediawiki.org/wiki/Manual:Image_Authorization.',
'img-auth-notindir' => 'Den ynskte filstien er ikkje i den oppsette opplastingskatalogen',
'img-auth-badtitle' => 'Kan ikkje laga ein gyldig ttitel ut frå "$1".',
'img-auth-nologinnWL' => 'Du er ikkje logga inn, og "$1" er ikkje på kvitlista.',
'img-auth-nofile' => 'Fila "$1" finst ikkje',
'img-auth-isdir' => 'Du prøver å få tilgjenge til katalogen "$1".
Berre tilgjenge til filer er tillete.',
'img-auth-streaming' => 'Sendar "$1".',
'img-auth-public' => 'Funksjonen til img_auth.php er å laga filer frå ein privat wiki.
Denne wikien er sett opp som ein ålmennt tilgjengeleg wiki.
For best tryggleik, er img_auth.php sett ut av funksjon.',
'img-auth-noread' => 'Brukaren har ikkje rettar til å lesa «$1».',
'img-auth-bad-query-string' => 'URL-en har ein ugild spørjestreng.',

# HTTP errors
'http-invalid-url' => 'Ugyldig URL: $1',
'http-invalid-scheme' => 'URL-ar med  «$1»-førestavinga er ikkje støtta.',
'http-request-error' => 'HTTP-førespurnaden feila grunna ein ukjend feil.',
'http-read-error' => 'HTTP-lesefeil.',
'http-timed-out' => 'Tidsavbrot på HTTP-førespurnad.',
'http-curl-error' => 'Feil under henting av nettadressa: $1',
'http-host-unreachable' => 'Kunne ikkje nå nettadressa',
'http-bad-status' => 'Det var eit problem under HTTP-førespurnaden: $1 $2',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6' => 'Kunne ikkje nå nettadressa',
'upload-curl-error6-text' => 'Nettadressa som er oppgjeve kunne ikkje nåast. Ver venleg og dobbelsjekk at nettadressa er rett og at sida fungerer.',
'upload-curl-error28' => 'Opplastinga fekk tidsavbrot',
'upload-curl-error28-text' => 'Sida brukte for lang tid på å svare. Ver venleg og sjekk om sida fungerer, vent litt og prøv ein gong til. Det kan også vere lurt å prøve på ei tid med mindre nettrafikk.',

'license' => 'Lisensiering:',
'license-header' => 'Lisensiering:',
'nolicense' => 'Ingen lisens er vald',
'license-nopreview' => '(Førehandsvising er ikkje tilgjengeleg)',
'upload_source_url' => ' (ei gyldig, offentleg tilgjengeleg nettadresse)',
'upload_source_file' => ' (ei fil på datamaskina di)',

# Special:ListFiles
'listfiles-summary' => 'Spesialsida viser alle opplasta filer.
Når ho er filtrert etter brukar, vert berre filene der brukaren lasta opp den siste versjonen viste.',
'listfiles_search_for' => 'Søk etter filnamn:',
'imgfile' => 'fil',
'listfiles' => 'Filliste',
'listfiles_thumb' => 'Miniatyrbilete',
'listfiles_date' => 'Dato',
'listfiles_name' => 'Namn',
'listfiles_user' => 'Brukar',
'listfiles_size' => 'Storleik',
'listfiles_description' => 'Skildring',
'listfiles_count' => 'Versjonar',

# File description page
'file-anchor-link' => 'Fil',
'filehist' => 'Filhistorikk',
'filehist-help' => 'Klikk på dato/klokkeslett for å sjå fila slik ho var på det tidspunktet.',
'filehist-deleteall' => 'slett alle',
'filehist-deleteone' => 'slett',
'filehist-revert' => 'rulla attende',
'filehist-current' => 'noverande',
'filehist-datetime' => 'Dato/klokkeslett',
'filehist-thumb' => 'Miniatyrbilete',
'filehist-thumbtext' => 'Miniatyrbilete av versjonen frå $1',
'filehist-nothumb' => 'Ingen miniatyrbilete',
'filehist-user' => 'Brukar',
'filehist-dimensions' => 'Oppløysing',
'filehist-filesize' => 'Filstorleik',
'filehist-comment' => 'Kommentar',
'filehist-missing' => 'Fila manglar',
'imagelinks' => 'Filbruk',
'linkstoimage' => '{{PLURAL:$1|Den følgjande sida|Dei følgjande $1 sidene}} har lenkjer til denne fila:',
'linkstoimage-more' => 'Meir enn $1 {{PLURAL:$1|side|sider}} lenkjer til denne fila.
Følgjande liste viser {{PLURAL:$1|den første sida|dei $1 første sidene}}.
Ei [[Special:WhatLinksHere/$2|fullstendig liste]] er tilgjengeleg.',
'nolinkstoimage' => 'Det finst ikkje noka side med lenkje til denne fila.',
'morelinkstoimage' => 'Vis [[Special:WhatLinksHere/$1|fleire lenkjer]] til denne fila.',
'linkstoimage-redirect' => '$1 (filomdirigering) $2',
'duplicatesoffile' => 'Følgjande {{PLURAL:$1|fil er ein dublett|filer er dublettar}} av denne fila ([[Special:FileDuplicateSearch/$2|fleire detaljar]]):',
'sharedupload' => 'Denne fila er frå $1 og kan verta brukt av andre prosjekt.',
'sharedupload-desc-there' => 'Denne fila er frå $1 og kan verta nytta av andre prosjekt.
Sjå [$2 filskildringssida] for meir informasjon.',
'sharedupload-desc-here' => 'Denne fila er frå $1 og kan verta nytta av andre prosjekt.
Skildringa frå [$2 filskildringssida] der er vist nedanfor.',
'sharedupload-desc-edit' => 'Fila er frå $1 og kan vera nytta på andre prosjekt.
Du vil kan henda endra skildringa på [$2 filskildringssida] hennar der.',
'sharedupload-desc-create' => 'Fila er frå $1 og kan vera nytta på andre prosjekt.
Du vil kan henda endra skildringa på [$2 filskildringssida] hennar der.',
'filepage-nofile' => 'Det finst ikkje noka fil med dette namnet.',
'filepage-nofile-link' => 'Inga fil med dette namnet finst, men du kan [$1 lasta ho opp].',
'uploadnewversion-linktext' => 'Last opp ny versjon av denne fila',
'shared-repo-from' => 'frå $1',
'shared-repo' => 'eit sams fillager',
'upload-disallowed-here' => 'Du kan ikkje overskriva denne fila.',

# File reversion
'filerevert' => 'Rull attende $1',
'filerevert-legend' => 'Rull attende fila',
'filerevert-intro' => "Du rullar attende '''[[Media:$1|$1]]''' til [$4 versjonen frå $3, $2].",
'filerevert-comment' => 'Årsak:',
'filerevert-defaultcomment' => 'Rulla attende til versjonen frå $2, $1',
'filerevert-submit' => 'Rull attende',
'filerevert-success' => "'''[[Media:$1|$1]]''' er rulla attende til [$4 versjonen frå $3, $2].",
'filerevert-badversion' => 'Det finst ingen tidlegare lokal versjon av denne fila frå det oppgjevne tidspunktet.',

# File deletion
'filedelete' => 'Slett $1',
'filedelete-legend' => 'Slett fil',
'filedelete-intro' => "Du er i ferd med å sletta fila '''[[Media:$1|$1]]''' i lag med heile historikken hennar.",
'filedelete-intro-old' => "Du slettar versjonen av '''[[Media:$1|$1]]''' frå [$4 $3, $2].",
'filedelete-comment' => 'Årsak:',
'filedelete-submit' => 'Slett',
'filedelete-success' => "'''$1''' er sletta.",
'filedelete-success-old' => "Versjonen av '''[[Media:$1|$1]]''' frå $3, $2 er sletta.",
'filedelete-nofile' => "'''$1''' finst ikkje.",
'filedelete-nofile-old' => "Det finst ingen arkivert versjon av '''$1''' med dei oppgjevne attributta.",
'filedelete-otherreason' => 'Annan grunn/tilleggsgrunn:',
'filedelete-reason-otherlist' => 'Annan grunn',
'filedelete-reason-dropdown' => '*Vanlege grunnar for sletting
** Brot på opphavsretten
** Ligg dobbelt',
'filedelete-edit-reasonlist' => 'Endre grunnar til sletting',
'filedelete-maintenance' => 'Sletting og attoppretting af filer er mellombels ikkje mogleg på grunn av vedlikehald.',
'filedelete-maintenance-title' => 'Kan ikkje sletta fila',

# MIME search
'mimesearch' => 'MIME-søk',
'mimesearch-summary' => 'Denne sida gjer filtrering av filer etter MIME-type mogleg. Skriv inn: innhaldstype/undertype, t.d. <code>image/jpeg</code>.',
'mimetype' => 'MIME-type:',
'download' => 'last ned',

# Unwatched pages
'unwatchedpages' => 'Uovervaka sider',

# List redirects
'listredirects' => 'Omdirigeringsliste',

# Unused templates
'unusedtemplates' => 'Ubrukte malar',
'unusedtemplatestext' => 'Denne sida viser alle sidene i mal-namnerommet ({{ns:template}}:) som ikkje er brukte på andre sider. Hugs også å sjå etter andre lenkjer til malane før du slettar dei.',
'unusedtemplateswlh' => 'andre lenkjer',

# Random page
'randompage' => 'Tilfeldig side',
'randompage-nopages' => 'Det finst ingen sider i {{PLURAL:$2|det fylgjande namneromet|dei fylgjande namneroma}}: $1.',

# Random redirect
'randomredirect' => 'Tilfeldig omdirigering',
'randomredirect-nopages' => 'Det finst ingen omdirigeringar i namnerommet «$1».',

# Statistics
'statistics' => 'Statistikk',
'statistics-header-pages' => 'Sidestatistikk',
'statistics-header-edits' => 'Endringsstatistikk',
'statistics-header-views' => 'Visingsstatistikk',
'statistics-header-users' => 'Brukarstatistikk',
'statistics-header-hooks' => 'Anna statistikk',
'statistics-articles' => 'Innhaldssider',
'statistics-pages' => 'Sider',
'statistics-pages-desc' => 'Alle sider på wikien, inkludert diskusjonssider, omdirigeringar o.l.',
'statistics-files' => 'Opplasta filer',
'statistics-edits' => 'Endringar sidan {{SITENAME}} vart oppretta',
'statistics-edits-average' => 'Gjennomsnittleg tal på endringar per side',
'statistics-views-total' => 'Totalt visningstal',
'statistics-views-total-desc' => 'Visingar av sider som ikkje finst og spesialsider er ikkje tekne med',
'statistics-views-peredit' => 'Visingar per endring',
'statistics-users' => 'Registrerte [[Special:ListUsers|brukarar]]',
'statistics-users-active' => 'Aktive brukarar',
'statistics-users-active-desc' => 'Brukarar som har utført handlingar {{PLURAL:$1|i dag|dei siste $1 dagane}}',
'statistics-mostpopular' => 'Mest viste sider',

'disambiguations' => 'Sider som lenkjer til fleirtydingssider',
'disambiguationspage' => 'Template:Fleirtyding',
'disambiguations-text' => "Dei fylgjande sidene inneheld minst éi lenkje til ei '''fleirtydingsside'''.
Dei bør kan henda lenkja til ei meir passande side i staden.<br />
Ei side vert handsama som ei fleirtydingsside om ho nyttar ein mal som er lenkja til frå [[MediaWiki:Disambiguationspage]].",

'doubleredirects' => 'Doble omdirigeringar',
'doubleredirectstext' => 'Kvar line inneheld lenkjer til den første og den andre omdirigeringa, og den første lina frå den andre omdirigeringsteksten. Det gjev som regel den «rette» målartikkelen, som den første omdirigeringa skulle ha peikt på. <del>Overstrykne</del> liner har vorte retta på.',
'double-redirect-fixed-move' => '[[$1]] har blitt flytta, og er no ei omdirigering til [[$2]]',
'double-redirect-fixed-maintenance' => 'Rettar dobbel omdirigering frå [[$1]] til [[$2]].',
'double-redirect-fixer' => 'Omdirigeringsfiksar',

'brokenredirects' => 'Blindvegsomdirigeringar',
'brokenredirectstext' => 'Dei følgjande omdirigeringane viser til ei side som ikkje finst:',
'brokenredirects-edit' => 'endre',
'brokenredirects-delete' => 'slett',

'withoutinterwiki' => 'Sider utan lenkjer til andre språk',
'withoutinterwiki-summary' => 'Desse sidene manglar lenkjer til sider på andre språk:',
'withoutinterwiki-legend' => 'Prefiks',
'withoutinterwiki-submit' => 'Vis',

'fewestrevisions' => 'Sidene med færrast endringar',

# Miscellaneous special pages
'nbytes' => '$1 {{PLURAL:$1|byte|byte}}',
'ncategories' => '$1 {{PLURAL:$1|kategori|kategoriar}}',
'ninterwikis' => '{{PLURAL:$1|éin interwiki|$1 interwikiar}}',
'nlinks' => '{{PLURAL:$1|Éi lenkje|$1 lenkjer}}',
'nmembers' => '$1 {{PLURAL:$1|medlem|medlemmer}}',
'nrevisions' => '{{PLURAL:$1|Éin versjon|$1 versjonar}}',
'nviews' => '{{PLURAL:$1|Éi vising|$1 visingar}}',
'nimagelinks' => 'Brukt på $1 {{PLURAL:$1|side|sider}}',
'ntransclusions' => 'brukt på $1 {{PLURAL:$1|side|sider}}',
'specialpage-empty' => 'Det er ingen resultat for denne rapporten.',
'lonelypages' => 'Foreldrelause sider',
'lonelypagestext' => 'Desse sidene er ikkje lenkja til eller inkluderte på andre sider på {{SITENAME}}.',
'uncategorizedpages' => 'Ukategoriserte sider',
'uncategorizedcategories' => 'Ukategoriserte kategoriar',
'uncategorizedimages' => 'Ukategoriserte filer',
'uncategorizedtemplates' => 'Ukategoriserte malar',
'unusedcategories' => 'Ubrukte kategoriar',
'unusedimages' => 'Ubrukte filer',
'popularpages' => 'Populære sider',
'wantedcategories' => 'Etterspurde kategoriar',
'wantedpages' => 'Etterspurde sider',
'wantedpages-badtitle' => 'Ugyldig tittel mellom resultata: $1',
'wantedfiles' => 'Etterspurde filer',
'wantedfiletext-cat' => 'Desse filene er nytta men finst ikkje. Filer frå utannettstadlege samlingar kan vera lista opp sjølv om dei finst. Slike falske positivar vert <del>strokne ut</del>. Sider som nyttar filer som ikkje finst vert lista opp i [[:$1]].',
'wantedfiletext-nocat' => 'Desse filene er nytta men finst ikkje. Filer frå utannettstadlege samlingar kan vera lista opp sjølv om dei finst. Slike falske positivar vert <del>strokne ut</del>.',
'wantedtemplates' => 'Etterspurde malar',
'mostlinked' => 'Sidene med flest lenkjer til seg',
'mostlinkedcategories' => 'Mest brukte kategoriar',
'mostlinkedtemplates' => 'Mest brukte malar',
'mostcategories' => 'Sidene med flest kategoriar',
'mostimages' => 'Mest brukte filer',
'mostinterwikis' => 'Sidene med flest interwikiar',
'mostrevisions' => 'Sidene med flest endringar',
'prefixindex' => 'Alle sider med forstaving',
'prefixindex-namespace' => 'Alle sider med førefeste ($1-namnerommet)',
'shortpages' => 'Korte sider',
'longpages' => 'Lange sider',
'deadendpages' => 'Blindvegsider',
'deadendpagestext' => 'Desse sidene har ikkje lenkjer til andre sider på {{SITENAME}}.',
'protectedpages' => 'Verna sider',
'protectedpages-indef' => 'Berre vern på uavgrensa tid',
'protectedpages-cascade' => 'Berre djupvern',
'protectedpagestext' => 'Desse sidene er verna mot flytting og endring',
'protectedpagesempty' => 'Ingen sider er verna på den valde måten akkurat no.',
'protectedtitles' => 'Verna sidenamn',
'protectedtitlestext' => 'Desse sidene er verna mot oppretting',
'protectedtitlesempty' => 'Ingen sider er verna på den valde måten akkurat no.',
'listusers' => 'Brukarliste',
'listusers-editsonly' => 'Vis berre brukarar med endringar',
'listusers-creationsort' => 'Sorter etter opprettingsdato',
'usereditcount' => '{{PLURAL:$1|éi endring|$1 endringar}}',
'usercreated' => '{{GENDER:$3|Oppretta}} den $1 $2',
'newpages' => 'Nye sider',
'newpages-username' => 'Brukarnamn:',
'ancientpages' => 'Eldste sider',
'move' => 'Flytt',
'movethispage' => 'Flytt denne sida',
'unusedimagestext' => 'Dei fylgjande filene finst, men vert ikkje nytta på noka side.
Merk at andre internettsider kan ha direkte lenkjer til filer, og difor kan filene vera nytta aktivt trass i at dei er lista opp her.',
'unusedcategoriestext' => 'Dei følgjande kategorisidene er oppretta, sjølv om ingen artikkel eller kategori brukar dei.',
'notargettitle' => 'Inkje mål',
'notargettext' => 'Du har ikkje spesifisert noka målside eller nokon brukar å bruke denne funksjonen på.',
'nopagetitle' => 'Målsida finst ikkje',
'nopagetext' => 'Sida du ville flytte finst ikkje.',
'pager-newer-n' => '{{PLURAL:$1|nyare|nyare $1}}',
'pager-older-n' => '{{PLURAL:$1|eldre|eldre $1}}',
'suppress' => 'Historikkfjerning',
'querypage-disabled' => 'Spesialsida er slegen av for skuld yting.',

# Book sources
'booksources' => 'Bokkjelder',
'booksources-search-legend' => 'Søk etter bokkjelder',
'booksources-go' => 'Gå',
'booksources-text' => 'Nedanfor finn du ei liste over lenkjer til andre nettstader som sel nye og brukte bøker, og desse kan ha meir informasjon om bøker du leitar etter:',
'booksources-invalid-isbn' => 'Det oppgjevne ISBN-nummeret er ugyldig; sjekk med kjelda di om du har oppgjeve det rett.',

# Special:Log
'specialloguserlabel' => 'Utøvar:',
'speciallogtitlelabel' => 'Mål (tittel eller brukar):',
'log' => 'Loggar',
'all-logs-page' => 'Alle offentlege loggar',
'alllogstext' => 'Kombinert vising av alle loggane på {{SITENAME}}. Du kan avgrense resultatet ved å velje loggtype, brukarnamn eller den sida som er påverka (hugs å skilje mellom store og små bokstavar)',
'logempty' => 'Ingen element i loggen passar.',
'log-title-wildcard' => 'Søk i titlar som byrjar med denne teksten',
'showhideselectedlogentries' => 'Vis/gøym valde loggoppføringar',

# Special:AllPages
'allpages' => 'Alle sider',
'alphaindexline' => '$1 til $2',
'nextpage' => 'Neste side ($1)',
'prevpage' => 'Førre sida ($1)',
'allpagesfrom' => 'Vis sider frå:',
'allpagesto' => 'Vis sider til og med:',
'allarticles' => 'Alle sider',
'allinnamespace' => 'Alle sider ($1-namnerommet)',
'allnotinnamespace' => 'Alle sider (ikkje i $1-namnerommet)',
'allpagesprev' => 'Førre',
'allpagesnext' => 'Neste',
'allpagessubmit' => 'Vis',
'allpagesprefix' => 'Vis sider med prefikset:',
'allpagesbadtitle' => 'Det oppgjevne sidenamnet var ugyldig eller hadde eit interwiki-prefiks. Det kan også ha hatt eitt eller fleire teikn som ikkje kan brukast i sidenamn.',
'allpages-bad-ns' => '{{SITENAME}} har ikkje namnerommet «$1».',
'allpages-hide-redirects' => 'Gøym omdirigeringar',

# SpecialCachedPage
'cachedspecial-viewing-cached-ttl' => 'Du ser på ei mellomlagra versjon av sida, som kan vera opp til $1 gamal.',
'cachedspecial-viewing-cached-ts' => 'Du ser på ein mellomlagra versjon av sida, som ikkje tarv vera heilt oppdatert.',
'cachedspecial-refresh-now' => 'Sjå siste.',

# Special:Categories
'categories' => 'Kategoriar',
'categoriespagetext' => 'Følgjande {{PLURAL:$1|category contains|kategoriar inneheld}} sider eller media.
[[Special:UnusedCategories|Unytta kategoriar]] vert ikkje vist her.
Sjå òg [[Special:WantedCategories|ønska kategoriar]].',
'categoriesfrom' => 'Vis kategoriar frå og med:',
'special-categories-sort-count' => 'sorter etter storleik',
'special-categories-sort-abc' => 'sorter alfabetisk',

# Special:DeletedContributions
'deletedcontributions' => 'Sletta brukarbidrag',
'deletedcontributions-title' => 'Sletta brukarbidrag',
'sp-deletedcontributions-contribs' => 'bidrag',

# Special:LinkSearch
'linksearch' => 'Søk i eksterne lenkjer',
'linksearch-pat' => 'Søkemønster:',
'linksearch-ns' => 'Namnerom:',
'linksearch-ok' => 'Søk',
'linksearch-text' => 'Jokerteikn som «*.wikipedia.org» kan nyttast.
Det er påkravt med eit toppnivådomene, til dømes «*.org».<br />
Støtta protokollar: <code>$1</code> (nyttar http:// som standard om ingen protokoll er oppgjeven)',
'linksearch-line' => '$2 lenkjer til $1',
'linksearch-error' => 'Jokerteikn kan berre nyttast føre tenarnamnet.',

# Special:ListUsers
'listusersfrom' => 'Vis brukarnamna frå og med:',
'listusers-submit' => 'Vis',
'listusers-noresult' => 'Ingen brukarnamn vart funne.',
'listusers-blocked' => '(konto blokkert)',

# Special:ActiveUsers
'activeusers' => 'Liste over aktive brukarar',
'activeusers-intro' => 'Dette er ei liste over brukarar som har hatt ei eller anna form for aktivitet innanfor {{PLURAL:$1|den siste dagen|dei siste dagane}}.',
'activeusers-count' => '{{PLURAL:$1|Éi handling|$1 handlingar}} {{PLURAL:$3|det siste døgeret|dei siste $3 døgra}}',
'activeusers-from' => 'Vis brukarar frå og med:',
'activeusers-hidebots' => 'Skjul botar',
'activeusers-hidesysops' => 'Skjul administratorar',
'activeusers-noresult' => 'Ingen brukarar funne.',

# Special:Log/newusers
'newuserlogpage' => 'Brukaropprettingslogg',
'newuserlogpagetext' => 'Dette er ein logg over oppretta brukarkontoar.',

# Special:ListGroupRights
'listgrouprights' => 'Rettar for brukargrupper',
'listgrouprights-summary' => 'Følgjande liste viser brukargruppene som er definert på denne wikien, og kvar rettar dei har. Meir informasjon om dei ulike rettane ein kan ha finn ein [[{{MediaWiki:Listgrouprights-helppage}}|her]].',
'listgrouprights-key' => '* <span class="listgrouprights-granted">Innvilga rettar</span>
* <span class="listgrouprights-granted">Tilbaketrukne rettar</span>',
'listgrouprights-group' => 'Gruppe',
'listgrouprights-rights' => 'Tilgangar',
'listgrouprights-helppage' => 'Help:Gruppetilgangar',
'listgrouprights-members' => '(liste over medlemmer)',
'listgrouprights-addgroup' => 'Kan leggje til {{PLURAL:$2|gruppa|gruppene}}: $1',
'listgrouprights-removegroup' => 'Kan fjerne {{PLURAL:$2|gruppa|gruppene}}: $1',
'listgrouprights-addgroup-all' => 'Kan leggje til alle grupper',
'listgrouprights-removegroup-all' => 'Kan fjerne alle grupper',
'listgrouprights-addgroup-self' => 'Kan leggja til {{PLURAL:$2|gruppa|gruppene}} til eigen konto: $1',
'listgrouprights-removegroup-self' => 'Kan ta vekk {{PLURAL:$2|gruppe|grupper}} frå eigen konto: $1',
'listgrouprights-addgroup-self-all' => 'Kan leggja til alle gruppene til sin eigen konto',
'listgrouprights-removegroup-self-all' => 'Kan ta vekk alle gruppene frå sin eigen konto',

# E-mail user
'mailnologin' => 'Inga avsendaradresse',
'mailnologintext' => 'Du lyt vera [[Special:UserLogin|innlogga]] og ha ei gyldig e-postadresse sett i [[Special:Preferences|brukarinnstillingane]] for å sende e-post åt andre brukarar.',
'emailuser' => 'Send e-post åt denne brukaren',
'emailuser-title-target' => 'Send epost åt {{GENDER:$1|brukaren}}',
'emailuser-title-notarget' => 'Send e-post åt brukar',
'emailpage' => 'Send e-post åt brukar',
'emailpagetext' => 'Du kan nytte skjemaet nedanfor til å sende ein e-post til denne {{GENDER:$1|brukaren}}.
E-postadressa du har sett i [[Special:Preferences|innstillingane dine]] vil dukke opp i «frå»-feltet på denne e-posten, så mottakaren er i stand til å svare.',
'usermailererror' => 'E-post systemet gav feilmelding:',
'defemailsubject' => '{{SITENAME}} epost frå brukar "$1"',
'usermaildisabled' => 'Brukare-post slegen av',
'usermaildisabledtext' => 'Du kan ikkje senda e-postar til andre brukarar på wikien',
'noemailtitle' => 'Inga e-postadresse',
'noemailtext' => 'Denne brukaren har ikkje oppgjeve ei gyldig e-postadresse.',
'nowikiemailtitle' => 'Ingen e-post tillaten',
'nowikiemailtext' => 'Denne brukaren har vald å ikkje motta e-postar frå andre brukarar.',
'emailnotarget' => 'Ikkje-eksisterande eller ugyldig brukarnamn for mottakar.',
'emailtarget' => 'Skriv inn brukarnamnet til mottakaren',
'emailusername' => 'Brukarnamn:',
'emailusernamesubmit' => 'Send',
'email-legend' => 'Send ein e-post til ein annan {{SITENAME}}-brukar',
'emailfrom' => 'Frå:',
'emailto' => 'Åt:',
'emailsubject' => 'Emne:',
'emailmessage' => 'Melding:',
'emailsend' => 'Send',
'emailccme' => 'Send meg ein kopi av meldinga mi.',
'emailccsubject' => 'Kopi av meldinga di til $1: $2',
'emailsent' => 'E-posten er sendt',
'emailsenttext' => 'E-posten er sendt.',
'emailuserfooter' => 'E-posten vart sendt av $1 til $2 via «Send e-post»-funksjonen på {{SITENAME}}.',

# User Messenger
'usermessage-summary' => 'Lèt att ei systemmelding.',
'usermessage-editor' => 'Systembodbringar',

# Watchlist
'watchlist' => 'Overvakingsliste',
'mywatchlist' => 'Overvakingsliste',
'watchlistfor2' => 'For $1 $2',
'nowatchlist' => 'Du har ikkje noko i overvakingslista di.',
'watchlistanontext' => 'Du lyt $1 for å vise eller endre sider på overvakingslista di.',
'watchnologin' => 'Ikkje innlogga',
'watchnologintext' => 'Du lyt vera [[Special:UserLogin|innlogga]] for å kunna endre overvakingslista.',
'addwatch' => '↓Legg til i overvakingslista',
'addedwatchtext' => 'Sida «[[:$1]]» er lagd til i [[Special:Watchlist|overvakingslista]] di. Framtidige endringar av henne og den tilhøyrande diskusjonssida hennar vil bli oppførde der.',
'removewatch' => 'Fjerna frå overvakingslista',
'removedwatchtext' => 'Sida «[[:$1]]» er fjerna frå [[Special:Watchlist|overvakingslista di]].',
'watch' => 'Overvak',
'watchthispage' => 'Overvak sida',
'unwatch' => 'Fjern overvaking',
'unwatchthispage' => 'Fjern overvaking',
'notanarticle' => 'Ikkje innhaldsside',
'notvisiblerev' => 'Sideversjonen er sletta',
'watchnochange' => 'Ingen av sidene i overvakingslista er endra i den valde perioden.',
'watchlist-details' => '{{PLURAL:$1|Éi side|$1 sider}} er overvaka, utanom diskusjonssider.',
'wlheader-enotif' => '* Funksjonen for endringsmeldingar per e-post er på.',
'wlheader-showupdated' => "* Sider som har vorte endra sidan du sist såg på dei er '''utheva'''",
'watchmethod-recent' => 'sjekkar siste endringar for dei overvaka sidene',
'watchmethod-list' => 'sjekkar om dei overvaka sidene har blitt endra i det siste',
'watchlistcontains' => 'Overvakingslista di inneheld {{PLURAL:$1|éi side|$1 sider}}.',
'iteminvalidname' => 'Problem med «$1», ugyldig namn...',
'wlnote' => "Nedanfor er {{PLURAL:$1|den siste endringa|dei siste '''$1''' endringane}} {{PLURAL:$2|den siste timen|dei siste '''$2''' timane}}, for $3, kl. $4.",
'wlshowlast' => 'Vis siste $1 timane $2 dagane $3',
'watchlist-options' => 'Alternativ for overvakingslista',

# Displayed when you click the "watch" button and it is in the process of watching
'watching' => 'Overvakar...',
'unwatching' => 'Fjernar frå overvakinglista...',
'watcherrortext' => 'Det oppstod ein feil under endringa av overvakingsinnstillingane dine for «$1».',

'enotif_mailer' => '{{SITENAME}}-endringsmeldingssendar',
'enotif_reset' => 'Merk alle sidene som vitja',
'enotif_newpagetext' => 'Dette er ei ny side.',
'enotif_impersonal_salutation' => '{{SITENAME}}-brukar',
'changed' => 'endra',
'created' => 'oppretta',
'enotif_subject' => '{{SITENAME}}-sida $PAGETITLE har vorte $CHANGEDORCREATED av $PAGEEDITOR',
'enotif_lastvisited' => 'Sjå $1 for alle endringane sidan siste vitjing.',
'enotif_lastdiff' => 'Sjå $1 for å sjå denne endringa.',
'enotif_anon_editor' => 'anonym brukar $1',
'enotif_body' => 'Kjære $WATCHINGUSERNAME,


{{SITENAME}}-sida $PAGETITLE er vorten $CHANGEDORCREATED $PAGEEDITDATE av $PAGEEDITOR, sjå $PAGETITLE_URL for den gjeldande versjonen.

$NEWPAGE

Endringssamandraget var: $PAGESUMMARY $PAGEMINOREDIT

Kontakt brukaren:
e-post: $PAGEEDITOR_EMAIL
wiki: $PAGEEDITOR_WIKI

Du får ikkje fleire endringsvarsel minder du vitjar sida på nytt.
Du kan dessutan nullstilla varselflagga for alle sidene på overvakingslista di.

Helsing det venlege meldingssystemet ditt for {{SITENAME}}

--
For å endra innstillingane dine for e-postvarsling, vitja
{{canonicalurl:{{#special:Preferences}}}}

For å endra innstillingane for overvakingslista di, vitja
{{canonicalurl:{{#special:EditWatchlist}}}}

For å fjerna sita frå overvakingslista di, vitja
$UNWATCHURL

Attendemelding og hjelp:
{{canonicalurl:{{MediaWiki:Helppage}}}}',

# Delete
'deletepage' => 'Slett sida',
'confirm' => 'Stadfest',
'excontent' => 'innhaldet var: «$1»',
'excontentauthor' => 'innhaldet var: «$1» (og den einaste bidragsytaren var «[[Special:Contributions/$2|$2]]»)',
'exbeforeblank' => 'innhaldet før sida vart tømd var: «$1»',
'exblank' => 'sida var tom',
'delete-confirm' => 'Slett «$1»',
'delete-legend' => 'Slett',
'historywarning' => "'''Åtvaring:''' Sida du held på å slette har ein historikk med om lag $1 {{PLURAL:$1|versjon|versjonar}}:",
'confirmdeletetext' => 'Du held på å varig slette ei side eller eit bilete saman med heile den tilhøyrande historikken frå databasen. Stadfest at du verkeleg vil gjere dette, at du skjønar konsekvensane, og at du gjer dette i tråd med [[{{MediaWiki:Policy-url}}|retningslinene]].',
'actioncomplete' => 'Ferdig',
'actionfailed' => 'Handlinga kunne ikkje verta utførd',
'deletedtext' => '«$1» er sletta. Sjå $2en for eit oversyn over dei siste slettingane.',
'dellogpage' => 'Slettelogg',
'dellogpagetext' => 'Her er ei liste over dei siste slettingane.',
'deletionlog' => 'slettelogg',
'reverted' => 'Attenderulla til ein tidlegare versjon',
'deletecomment' => 'Årsak:',
'deleteotherreason' => 'Annan grunn:',
'deletereasonotherlist' => 'Annan grunn',
'deletereason-dropdown' => '*Vanlege grunnar for sletting
** På førespurnad frå forfattaren
** Brot på opphavsretten
** Hærverk',
'delete-edit-reasonlist' => 'Endre grunnar til sletting',
'delete-toobig' => 'Denne sida har ein stor endringsshistorikk, med over {{PLURAL:$1|$1&nbsp;endring|$1&nbsp;endringar}}. Sletting av slike sider er avgrensa for å unngå utilsikta forstyrring av {{SITENAME}}.',
'delete-warning-toobig' => 'Denne sida har ein lang endringshistorikk, med meir enn {{PLURAL:$1|$1&nbsp;endring|$1&nbsp;endringar}}. Dersom du slettar henne kan det forstyrre handlingar i databasen til {{SITENAME}}, ver varsam.',

# Rollback
'rollback' => 'Rull attende endringar',
'rollback_short' => 'Rull attende',
'rollbacklink' => 'rull attende',
'rollbacklinkcount' => 'rull attende {{PLURAL:$1|éi endring|$1 endringar}}',
'rollbacklinkcount-morethan' => 'rull attende meir enn {{PLURAL:$1|éi endring|$1 endringar}}',
'rollbackfailed' => 'Kunne ikkje rulle attende',
'cantrollback' => 'Kan ikkje rulle attende fordi den siste brukaren er den einaste forfattaren.',
'alreadyrolled' => 'Kan ikkje rulla attende den siste endringa på [[:$1]] gjord av [[User:$2|$2]] ([[User talk:$2|diskusjon]]{{int:pipe-separator}}[[Special:Contributions/$2|{{int:contribslink}}]]) av di nokon andre alt har endra eller attenderulla sida.

Den siste endringa vart gjord av [[User:$3|$3]] ([[User talk:$3|brukardiskusjon]]{{int:pipe-separator}}[[Special:Contributions/$3|{{int:contribslink}}]]).',
'editcomment' => "Samandraget for endringa var: «''$1''».",
'revertpage' => 'Attenderulla endring gjord av [[Special:Contributions/$2|$2]] ([[User talk:$2|diskusjon]]) til siste versjonen av [[User:$1|$1]]',
'revertpage-nouser' => 'Tilbakestilte endringar av (brukarnamn fjerna) til den siste versjonen av [[User:$1|$1]]',
'rollback-success' => 'Rulla attende endringane av $1, attende til siste versjonen av $2.',

# Edit tokens
'sessionfailure-title' => 'Feil med omgangen.',
'sessionfailure' => 'Det ser ut til å vera eit problem med innloggingsøkta di. Handlinga er vorten avbroten for å vera føre var mot kidnapping av økta. Bruk attendeknappen i nettlesaren din og prøv om att.',

# Protect
'protectlogpage' => 'Vernelogg',
'protectlogtext' => 'Nedanfor er ei liste over endringar i vern.
Sjå [[Special:ProtectedPages|lista over verna sider]] for lista over vern som nett no er verksame.',
'protectedarticle' => 'verna «[[$1]]»',
'modifiedarticleprotection' => 'endra nivået på vernet av «[[$1]]»',
'unprotectedarticle' => 'fjerna vern av «[[$1]]»',
'movedarticleprotection' => 'flytta verneinnstillingar frå «[[$2]]» til «[[$1]]»',
'protect-title' => 'Vernar «$1»',
'protect-title-notallowed' => 'Sjå vernenivået til «$1»',
'prot_1movedto2' => '«[[$1]]» flytt til «[[$2]]»',
'protect-badnamespace-title' => 'Namnerommet kan ikkje vernast',
'protect-badnamespace-text' => 'Sider i dette namnerommet kan ikkje vernast.',
'protect-legend' => 'Stadfest vern',
'protectcomment' => 'Grunngjeving:',
'protectexpiry' => 'Endar:',
'protect_expiry_invalid' => 'Utløpstida er ugyldig.',
'protect_expiry_old' => 'Utløpstida har allereie vore.',
'protect-unchain-permissions' => 'Lås opp fleire alternativ for vern',
'protect-text' => "Her kan du kan sjå og endre på graden av vern for sida '''$1'''.",
'protect-locked-blocked' => "Du kan ikkje endre nivå på vern medan du er blokkert. Dette er dei noverande innstillingane for sida '''$1''':",
'protect-locked-dblock' => "Du kan ikkje endre nivå på vern fordi databasen er låst akkurat no. Dette er dei noverande innstillingane for sida '''$1''':",
'protect-locked-access' => "Brukarkontoen din har ikkje tilgang til endring av vern.
Her er dei noverande innstillingane for sida '''$1''':",
'protect-cascadeon' => 'Denne sida er verna fordi ho er inkludert på {{PLURAL:$1|den opplista sida|dei opplista sidene}} som har djupvern slått på. Du kan endre på nivået til vernet av denne sida, men det vil ikkje ha innverknad på djupvernet.',
'protect-default' => 'Tillat alle brukarar',
'protect-fallback' => 'Berre tillat brukarar med løyvet «$1»',
'protect-level-autoconfirmed' => 'Berre tillat autostadfeste brukarar',
'protect-level-sysop' => 'Berre tillat administratorar',
'protect-summary-cascade' => 'djupvern',
'protect-expiring' => 'endar $1 (UTC)',
'protect-expiring-local' => 'endar $1',
'protect-expiry-indefinite' => 'uavgrensa',
'protect-cascade' => 'Vern alle sidene som er inkludert på denne sida (djupvern)',
'protect-cantedit' => 'Du kan ikkje endre vernenivået på sida fordi du ikkje har tilgang til å endre henne.',
'protect-othertime' => 'Anna tid:',
'protect-othertime-op' => 'anna tid',
'protect-existing-expiry' => 'Gjeldande utløpstid: $3 $2',
'protect-otherreason' => 'Anna/ytterlegare årsak:',
'protect-otherreason-op' => 'Anna årsak',
'protect-dropdown' => '*Vanlege verneårsaker
** Gjenteke hærverk
** Gjenteke spam
** Endringskrig
** Side med mange vitjande',
'protect-edit-reasonlist' => 'Endrar verneårsaker',
'protect-expiry-options' => '1 time:1 hour,1 dag:1 day,1 veke:1 week,2 veker:2 weeks,1 månad:1 month,3 månader:3 months,6 månader:6 months,1 år:1 year,endelaus:infinite',
'restriction-type' => 'Tilgang:',
'restriction-level' => 'Avgrensingsnivå:',
'minimum-size' => 'Minimumstorleik',
'maximum-size' => 'Maksimumstorleik:',
'pagesize' => '(byte)',

# Restrictions (nouns)
'restriction-edit' => 'Endring',
'restriction-move' => 'Flytting',
'restriction-create' => 'Opprett',
'restriction-upload' => 'Last opp',

# Restriction levels
'restriction-level-sysop' => 'heilt verna',
'restriction-level-autoconfirmed' => 'delvis verna',
'restriction-level-all' => 'alle nivå',

# Undelete
'undelete' => 'Sletta sider',
'undeletepage' => 'Sletta sider',
'undeletepagetitle' => "'''Følgjande innhald er sletta versjonar av [[:$1]]'''.",
'viewdeletedpage' => 'Sjå sletta sider',
'undeletepagetext' => '{{PLURAL:$1|Den følgjande sida er sletta, men ho|Dei følgjande $1 sidene er sletta, men dei}} finst enno i arkivet og kan attopprettast. Arkivet blir periodevis sletta.',
'undelete-fieldset-title' => 'Attenderull endringar',
'undeleteextrahelp' => "For å attoppretta heile historikken til sida, lat alle boksane vera tomme og trykk '''''{{int:undeletebtn}}'''''.
For å berre attopretta delar av historikken, hak av boksane til dei relevante endringane og trykk '''''{{int:undeletebtn}}'''''.",
'undeleterevisions' => '{{PLURAL:$1|Éin versjon arkivert|$1 versjonar arkiverte}}',
'undeletehistory' => 'Om du gjenopprettar sida vil alle endringar i historikken også bli gjenoppretta. Dersom ei ny side med same namn er oppretta etter slettinga, vil dei gjenoppretta endringane dukke opp før denne i endringshistorikken.',
'undeleterevdel' => 'Gjenoppretting kan ikkje utførast om det resulterer i at den øvste endringa delvis vert sletta. I slike tilfelle må du fjerne merkinga av den siste sletta endringa.',
'undeletehistorynoadmin' => 'Ein eller fleire versjonar av denne sida har blitt sletta.
Grunnlaget for sletting er oppgjeve under, saman med informasjon om kven som sletta og når versjonane vart sletta.
Innhaldet i dei sletta versjonane er berre tilgjengeleg for administratorar.',
'undelete-revision' => 'Sletta versjon av $1 (per $4 $5) av $3:',
'undeleterevision-missing' => 'Ugyldig eller manglande versjon. Lenkja kan vere feil, eller han kan vere fjerna frå arkivet.',
'undelete-nodiff' => 'Fann ingen eldre versjonar.',
'undeletebtn' => 'Attopprett',
'undeletelink' => 'sjå/attopprett',
'undeleteviewlink' => 'syn',
'undeletereset' => 'Nullstill',
'undeleteinvert' => 'Inverter val',
'undeletecomment' => 'Årsak:',
'undeletedrevisions' => '{{PLURAL:$1|Éin versjon|$1 versjonar}} attoppretta.',
'undeletedrevisions-files' => '{{PLURAL:$1|Éin versjon|$1 versjonar}} og {{PLURAL:$2|éi fil|$2 filer}} er attoppretta',
'undeletedfiles' => '{{PLURAL:$1|Éi fil|$1 filer}} er attoppretta',
'cannotundelete' => 'Feil ved attoppretting, andre kan allereie ha attoppretta sida.',
'undeletedpage' => "'''$1 er attoppretta'''

Sjå [[Special:Log/delete|sletteloggen]] for eit oversyn over sider som nyleg er sletta eller attoppretta.",
'undelete-header' => 'Sjå [[Special:Log/delete|sletteloggen]] for dei sist sletta sidene.',
'undelete-search-title' => 'Søk i sletta sider',
'undelete-search-box' => 'Søk i sletta sider',
'undelete-search-prefix' => 'Vis sider frå og med:',
'undelete-search-submit' => 'Søk',
'undelete-no-results' => 'Fann ingen treff i arkivet over sletta sider.',
'undelete-filename-mismatch' => 'Filversjonen med tidstrykk $1 kan ikkje attopprettast: filnamnet samsvarer ikkje.',
'undelete-bad-store-key' => 'Kan ikkje gjenopprette filutgåva med tidstrykk $1: fil mangla før sletting',
'undelete-cleanup-error' => 'Feil ved sletting av den ubrukte arkivfila «$1».',
'undelete-missing-filearchive' => 'Kunne ikkje attopprette filarkivet med nummer $1 fordi det ikkje ligg i databasen. Det kan allereie ver attoppretta.',
'undelete-error' => 'Feil under attoppretting av sida.',
'undelete-error-short' => 'Veil ved sletting av fila: $1',
'undelete-error-long' => 'Feil ved attoppretting av fila:

$1',
'undelete-show-file-confirm' => 'Er du sikker på at du vil visa ein sletta versjon av fila «<nowiki>$1</nowiki>» frå den $2 klokka $3?',
'undelete-show-file-submit' => 'Ja',

# Namespace form on various pages
'namespace' => 'Namnerom:',
'invert' => 'Vreng val',
'tooltip-invert' => 'Hak av boksen for å gøyma endringar på sider i det valde namnerommet (og det tilknytte namnerommet om det er haka av)',
'namespace_association' => 'Tilknytt namnerom',
'tooltip-namespace_association' => 'Hak av boksen for at diskusjonssida eller emnenamnerommet knytt til det valde namnerommet skal vera med òg',
'blanknamespace' => '(Hovud)',

# Contributions
'contributions' => 'Brukarbidrag',
'contributions-title' => 'Bidrag av $1',
'mycontris' => 'Bidrag',
'contribsub2' => 'For $1 ($2)',
'nocontribs' => 'Det vart ikkje funne nokon endringar gjorde av denne brukaren.',
'uctop' => ' (øvst)',
'month' => 'Månad:',
'year' => 'År:',

'sp-contributions-newbies' => 'Vis berre bidrag frå nye brukarar',
'sp-contributions-newbies-sub' => 'Frå nye brukarkontoar',
'sp-contributions-newbies-title' => 'Brukarbidrag av nye brukarar',
'sp-contributions-blocklog' => 'blokkeringslogg',
'sp-contributions-deleted' => 'sletta brukarbidrag',
'sp-contributions-uploads' => '↓opplastingar',
'sp-contributions-logs' => 'loggar',
'sp-contributions-talk' => 'diskusjon',
'sp-contributions-userrights' => 'administrering av brukartilgang',
'sp-contributions-blocked-notice' => 'Denne brukaren er for tida blokkert. Den siste oppføringa i blokkeringsloggen er synt nedanfor:',
'sp-contributions-blocked-notice-anon' => 'Denne IP-adressa er for tida blokkert. Den siste oppføringa i blokkeringsloggen er synt nedanfor:',
'sp-contributions-search' => 'Søk etter bidrag',
'sp-contributions-username' => 'IP-adresse eller brukarnamn:',
'sp-contributions-toponly' => 'Einast vis endringar som er dei siste på sida.',
'sp-contributions-submit' => 'Søk',

# What links here
'whatlinkshere' => 'Lenkjer hit',
'whatlinkshere-title' => 'Sider som har lenkje til «$1»',
'whatlinkshere-page' => 'Side:',
'linkshere' => "Desse sidene har lenkjer til '''[[:$1]]''':",
'nolinkshere' => "Ingen sider har lenkjer til '''[[:$1]]'''.",
'nolinkshere-ns' => "Ingen sider har lenkje til '''[[:$1]]''' i det valde namnerommet.",
'isredirect' => 'omdirigeringsside',
'istemplate' => 'inkludert som mal',
'isimage' => 'fillenkje',
'whatlinkshere-prev' => '{{PLURAL:$1|førre|førre $1}}',
'whatlinkshere-next' => '{{PLURAL:$1|neste|neste $1}}',
'whatlinkshere-links' => '← lenkjer',
'whatlinkshere-hideredirs' => '$1 omdirigeringar',
'whatlinkshere-hidetrans' => '$1 inkluderingar',
'whatlinkshere-hidelinks' => '$1 lenkjer',
'whatlinkshere-hideimages' => '$1 fillenkjer',
'whatlinkshere-filters' => 'Filter',

# Block/unblock
'autoblockid' => 'Autoblokker #$1',
'block' => 'Blokker brukar',
'unblock' => 'Opphev blokkeringa av brukaren',
'blockip' => 'Blokker brukar',
'blockip-title' => 'Blokker brukar',
'blockip-legend' => 'Blokker brukar',
'blockiptext' => 'Bruk skjemaet nedanfor for å blokkere skrivetilgangen frå ei spesifikk IP-adresse eller brukarnamn. Dette bør berre gjerast for å hindre hærverk, og i samsvar med [[{{MediaWiki:Policy-url}}|retningslinene]].',
'ipadressorusername' => 'IP-adresse eller brukarnamn',
'ipbexpiry' => 'Opphøyrstid:',
'ipbreason' => 'Årsak:',
'ipbreasonotherlist' => 'Annan grunn',
'ipbreason-dropdown' => '*Vanlege grunnar for blokkering
** Legg inn usann tekst/tull
** Fjernar innhald frå sider
** Legg inn reklamelenkjer til eksterne nettstader
** Sjikane/plaging av andre brukarar
** Misbruk ved hjelp av fleire brukarkontoar
** Uansvarleg brukarnamn',
'ipb-hardblock' => 'Hindre innlogga frå å endre sider frå denne IP-adressa.',
'ipbcreateaccount' => 'Hindre kontooppretting',
'ipbemailban' => 'Hindre sending av e-post til andre brukarar',
'ipbenableautoblock' => 'Blokker den førre IP-adressa som vart brukt av denne brukaren automatisk, og alle andre IP-adresser brukaren prøver å endre sider med i framtida',
'ipbsubmit' => 'Blokker brukaren',
'ipbother' => 'Anna tid',
'ipboptions' => '2 timar:2 hours,1 dag:1 day,3 dagar:3 days,1 veke:1 week,2 veker:2 weeks,1 månad:1 month,3 månader:3 months,6 månader:6 months,1 år:1 year,endelaus:infinite',
'ipbotheroption' => 'anna tid',
'ipbotherreason' => 'Annan grunn/tilleggsgrunn:',
'ipbhidename' => 'Gøym brukarnamnet frå endringar og lister',
'ipbwatchuser' => 'Overvak brukarsida og diskusjonssida til brukaren',
'ipb-disableusertalk' => 'Hindra brukaren i å endra eiga diskusjonsside medan blokkeringa gjeld',
'ipb-change-block' => 'Blokker brukaren på nytt med desse innstillingane',
'ipb-confirm' => 'Stadfest blokkering',
'badipaddress' => 'IP-adressa er ugyldig eller blokkering av brukarar er slått av på tenaren.',
'blockipsuccesssub' => 'Blokkeringa er utførd',
'blockipsuccesstext' => '«[[Special:Contributions/$1|$1]]» er blokkert.<br />
Sjå [[Special:BlockList|blokkeringslista]] for alle blokkeringane.',
'ipb-blockingself' => 'Du er i ferd med å blokkera deg sjølv. Er du viss på at du ynskjer gjera dette?',
'ipb-confirmhideuser' => 'Du er i ferd med å blokkere ein brukar med "skjult brukar" aktivert. Brukarens namn vil verte skjult i alle lister og loggoppføringar. Er du sikker på at du vil gjere dette?',
'ipb-edit-dropdown' => 'Endre grunnane for blokkering',
'ipb-unblock-addr' => 'Opphev blokkeringa av $1',
'ipb-unblock' => 'Opphev blokkeringa av eit brukarnamn eller ei IP-adresse',
'ipb-blocklist' => 'Vis gjeldande blokkeringar',
'ipb-blocklist-contribs' => 'Bidrag frå $1',
'unblockip' => 'Opphev blokkering',
'unblockiptext' => 'Bruk skjemaet nedanfor for å oppheve blokkeringa av ein tidlegare blokkert brukar.',
'ipusubmit' => 'Opphev blokkering',
'unblocked' => 'Blokkeringa av [[User:$1|$1]] er oppheva',
'unblocked-range' => '$1 vart avblokkert',
'unblocked-id' => 'Blokkering $1 er oppheva',
'blocklist' => 'Blokkerte brukarar',
'ipblocklist' => 'Blokkerte IP-adresser og brukarnamn',
'ipblocklist-legend' => 'Finn ein blokkert brukar',
'blocklist-userblocks' => 'Gøym kontoblokkeringar',
'blocklist-tempblocks' => 'Gøym mellombelse blokkeringar',
'blocklist-addressblocks' => 'Gøym einskilde IP-blokkeringar',
'blocklist-rangeblocks' => 'Gøym intervallblokkeringar',
'blocklist-timestamp' => 'Tidsmerke',
'blocklist-target' => 'Mål',
'blocklist-expiry' => 'Endar',
'blocklist-by' => 'Blokkerande admin',
'blocklist-params' => 'Blokkeringsparametrar',
'blocklist-reason' => 'Årsak',
'ipblocklist-submit' => 'Søk',
'ipblocklist-localblock' => 'Lokal blokkering',
'ipblocklist-otherblocks' => '{{PLURAL:$1|Anna blokkering|Andre blokkeringar}}',
'infiniteblock' => 'uendeleg opphøyrstid',
'expiringblock' => 'endar den $1 ved $2',
'anononlyblock' => 'berre anonyme',
'noautoblockblock' => 'automatisk blokkering slått av',
'createaccountblock' => 'kontooppretting blokkert',
'emailblock' => 'sending av e-post blokkert',
'blocklist-nousertalk' => 'kan ikkje endre si eiga diskusjonsside',
'ipblocklist-empty' => 'Lista over blokkeringar er tom.',
'ipblocklist-no-results' => 'Det etterspurde brukarnamnet eller IP-adressa er ikkje blokkert.',
'blocklink' => 'blokker',
'unblocklink' => 'opphev blokkering',
'change-blocklink' => 'endra blokkering',
'contribslink' => 'bidrag',
'emaillink' => 'send e-post',
'autoblocker' => 'Automatisk blokkert fordi du deler IP-adresse med [[User:$1|$1]]. Grunngjeving gjeve for blokkeringa av $1 var: «$2».',
'blocklogpage' => 'Blokkeringslogg',
'blocklog-showlog' => 'Denne brukaren har tidlegare vorte blokkert.
Blokkeringsloggen er sett opp nedanfor, som referanse.',
'blocklog-showsuppresslog' => 'Denne brukaren har tidlegare vorte blokkert og skjult.
Loggføringa er synt nedanfor som referanse:',
'blocklogentry' => 'Blokkerte «[[$1]]» med opphøyrstid $2 $3',
'reblock-logentry' => 'endra blokkeringsinnstillingar for [[$1]] med tida $2 $3',
'blocklogtext' => 'Dette er ein logg over blokkeringar og oppheving av blokkeringar gjorde.
IP-adresser som blir automatisk blokkerte er ikkje lista her. Sjå [[Special:BlockList|blokkeringslista]] for alle aktive blokkeringar.',
'unblocklogentry' => 'oppheva blokkering av «$1»',
'block-log-flags-anononly' => 'berre anonyme brukarar',
'block-log-flags-nocreate' => 'kontooppretting slått av',
'block-log-flags-noautoblock' => 'automatisk blokkering slått av',
'block-log-flags-noemail' => 'sending av e-post blokkert',
'block-log-flags-nousertalk' => 'kan ikkje endre eiga diskusjonsside',
'block-log-flags-angry-autoblock' => 'utvida autoblokkering aktivert',
'block-log-flags-hiddenname' => 'brukarnamn gøymt',
'range_block_disabled' => 'Funksjonen for blokkering av IP-adresse-seriar er inaktivert på tenaren.',
'ipb_expiry_invalid' => 'Ugyldig opphørstid.',
'ipb_expiry_temp' => 'For å skjule brukarnamnet må blokkeringa vere permanent.',
'ipb_hide_invalid' => 'Kan ikkje halda nede denne kontoen; han har kan henda for mange endringar.',
'ipb_already_blocked' => '«$1» er allereie blokkert',
'ipb-needreblock' => '$1 er alt blokkert. Vil du endre innstillingane?',
'ipb-otherblocks-header' => '{{PLURAL:$1|Anna blokkering|Andre blokkeringar}}',
'unblock-hideuser' => 'Du kan ikkje heve blokkeringa av denne brukaren, av di brukarnamnet har blitt gøymd.',
'ipb_cant_unblock' => 'Feil: Fann ikkje blokkeringsnummeret $1. Blokkeringa kan vere oppheva allereie.',
'ipb_blocked_as_range' => 'Feil: IP-en $1 er ikkje direkte blokkert og kan ikkje opphevast. Adressa er blokkert som ein del av blokkeringa av IP-intervallet $2. Denne blokkeringa kan opphevast.',
'ip_range_invalid' => 'Ugyldig IP-adresseserie.',
'ip_range_toolarge' => 'Blokkering av IP-seriar større enn /$1 er ikkje tillate.',
'blockme' => 'Blokker meg',
'proxyblocker' => 'Proxy-blokkerar',
'proxyblocker-disabled' => 'Denne funksjonen er slått av.',
'proxyblockreason' => 'Du er blokkert frå å endre fordi IP-adressa di tilhøyrer ein open mellomtenar (proxy). Du bør kontakte internettleverandøren din eller kundesørvis og gje dei beskjed, ettersom dette er eit alvorleg sikkerheitsproblem.',
'proxyblocksuccess' => 'Utført.',
'sorbsreason' => 'IP-adressa di er lista som ein open mellomtenar i DNSBL.',
'sorbs_create_account_reason' => 'IP-adressa di er lista som ein open mellomtenar i DNSBL, og difor får du ikkje registrert deg.',
'cant-block-while-blocked' => 'Du kan ikkje blokkere andre medan du sjølv er blokkert.',
'cant-see-hidden-user' => 'Brukaren du prøver å blokkera har allereie vorte blokkert og skjult. Sidan du ikkje har rett til å skjula brukarar, kan du ikkje sjå eller endra blokkeringa til brukaren.',
'ipbblocked' => 'Du kan ikkje blokkera eller avblokkera andre brukarar sidan du sjølv er blokkert',
'ipbnounblockself' => 'Du kan ikkje avblokkera deg sjølv',

# Developer tools
'lockdb' => 'Skrivevern (lock) database',
'unlockdb' => 'Opphev skrivevern (unlock) av databasen',
'lockdbtext' => 'Å skriveverne databasen vil gjere det umogleg for alle brukarar å endre sider, brukarinnstillingar, overvakingslister og andre ting som krev endringar i databasen. Stadfest at du ønskjer å gjera dette, og at du vil låse opp databasen att når vedlikehaldet er ferdig.',
'unlockdbtext' => 'Å oppheva skrivevernet på databasen fører til at alle brukarar kan endre sider, brukarinnstillingar, overvakingslister og andre ting som krev endringar i databasen att. Stadfest at du ønskjer å gjera dette.',
'lockconfirm' => 'Ja, eg vil verkeleg skriveverne databasen.',
'unlockconfirm' => 'Ja, eg vil verkeleg oppheva skrivevernet på databasen.',
'lockbtn' => 'Skrivevern databasen',
'unlockbtn' => 'Opphev skrivevern på databasen',
'locknoconfirm' => 'Du har ikkje stadfest handlinga.',
'lockdbsuccesssub' => 'Databasen er no skriveverna',
'unlockdbsuccesssub' => 'Skrivevernet på databasen er no oppheva',
'lockdbsuccesstext' => 'Databasen er no skriveverna. <br />Hugs å [[Special:UnlockDB|oppheve skrivevernet]] når du er ferdig med vedlikehaldet.',
'unlockdbsuccesstext' => 'Skrivevernet er oppheva.',
'lockfilenotwritable' => 'Kan ikkje skrive til databasen si låsefil. For å låse eller opne databasen, må tenaren kunne skrive til denne fila.',
'databasenotlocked' => 'Databasen er ikkje låst.',
'lockedbyandtime' => 'av $1 den $2 kl. $3',

# Move page
'move-page' => 'Flytt $1',
'move-page-legend' => 'Flytt side',
'movepagetext' => "Ved å bruka skjemaet nedanfor kan du få omdøypt ei side og flytt heile historikken til det nye namnet.
Den gamle tittelen vil verta ei omdirigeringsside til den nye.
Du kan oppdatera omdirigeringar som peikar til den opphavlege tittelen automatisk.
Vel du å ikkje gjera dette, pass på å sjå etter [[Special:DoubleRedirects|doble]] eller [[Special:BrokenRedirects|øydelagde omdirigeringar]].

Merk at sida '''ikkje''' vert flytt dersom det alt finst ei side med den nye tittelen, minder ho er ei omdirigering og ikkje har nokon endringshistorikk. Detter tyder at du kan omdøypa ei side attende til der ho vart omdøypt frå om du gjorde eit mistak, og du kan ikkje skriva over sider som finst.

'''ÅTVARING!'''
Dette kan vera ei drastisk og uventa endring for ei populær side; ver viss på at du skjøner konsekvensane av dette før du held fram.",
'movepagetext-noredirectfixer' => "Nyttar ein skjemaet under får ein døypt om ei side og flytt heile historikken til det nye namnet. 
Den gamle tittelen vil verta ei omdirigeringsside for den nye tittelen. 
Pass på å sjå etter [[Special:DoubleRedirects|doble]] eller [[Special:BrokenRedirects|uverksame]] omdirigeringar. 
Du er ansvarleg for at alle lenkjene stadig peikar dit det er meininga at dei skal føra.

Merk at sida '''ikkje''' vert flytt dersom det alt finst ei side med den nye tittelen, om ho då ikkje er tom eller ei omdirigeringsside utan endringshistorikk.
Dette vil seia at du kan døypa om ei side til det gamle namnet hennar om du gjer ein feil, og dessutan at du ikkje kan skriva over ei side som finst.

'''ÅTVARING!'''
Dette kan vera ei drastisk og uventa endring for ei populær side;
ver viss på at du skjøner konsekvensane av flyttinga før du held fram.",
'movepagetalktext' => "Den tilhøyrande diskusjonssida, om ho finst, vert automatisk flytt med sida '''minder:'''
*ei ikkje-tom diskusjonsside alt finst under det nye namnet, eller
*du fjernar avhakinga i boksen nedanfor.

I desse falla lyt du flytta eller fletta sida manuelt, om ynskjeleg.",
'movearticle' => 'Flytt side:',
'moveuserpage-warning' => "'''Åtvaring:''' Du er i ferd med å flytta ei brukarside. Merk at berre sida vert flytt og at brukarnamnet '''ikkje''' vert endra.",
'movenologin' => 'Ikkje innlogga',
'movenologintext' => 'Du lyt vera registrert brukar og vera [[Special:UserLogin|innlogga]] for å flytte ei side.',
'movenotallowed' => 'Du har ikkje tilgang til å flytte sider.',
'movenotallowedfile' => 'Du har ikkje løyve til å flytta filer.',
'cant-move-user-page' => 'Du har ikkje løyve til å flytte brukarsider (bortsett frå undersider).',
'cant-move-to-user-page' => 'Du har ikkje løyve til å flytte brukarsider (bortsett frå undersider).',
'newtitle' => 'Til ny tittel:',
'move-watch' => 'Overvak sida',
'movepagebtn' => 'Flytt side',
'pagemovedsub' => 'Flyttinga er gjennomførd',
'movepage-moved' => "'''«$1» er flytt til «$2»'''",
'movepage-moved-redirect' => 'Det er oppretta ei omdirigering.',
'movepage-moved-noredirect' => 'Det vart ikkje oppretta ei omdirigering.',
'articleexists' => 'Ei side med det namnet finst allereie, eller det namnet du har valt er ikkje gyldig. Vel eit anna namn.',
'cantmove-titleprotected' => 'Du kan ikkje flytte sida hit, fordi det nye sidenamnet er verna mot oppretting.',
'talkexists' => "'''Innhaldssida vart flytt, men diskusjonssida som høyrer til kunne ikkje flyttast fordi det allereie finst ei side med den nye tittelen. Du lyt difor flette dei saman manuelt.'''",
'movedto' => 'er flytt til',
'movetalk' => 'Flytt diskusjonssida òg om ho finst.',
'move-subpages' => 'Flytt undersider (opp til $1)',
'move-talk-subpages' => 'Flytt undersider av diskusjonssida (opp til $1)',
'movepage-page-exists' => 'Sida $1 finst alt og kan ikkje skrivast over automatisk.',
'movepage-page-moved' => 'Sida $1 er flytt til $2.',
'movepage-page-unmoved' => 'Sida $1 kunne ikkje flyttast til $2.',
'movepage-max-pages' => 'Grensa på {{PLURAL:$1|éi side|$1 sider}} er nådd; ingen fleire sider kjem til å verte flytta automatisk.',
'movelogpage' => 'Flyttelogg',
'movelogpagetext' => 'Under er ei liste over sider som er flytte.',
'movesubpage' => '{{PLURAL:$1|Underside|Undersider}}',
'movesubpagetext' => 'Denne sida har {{PLURAL:$1|éi underside som vert synt|$1 undersider som vert synte}} nedanfor.',
'movenosubpage' => 'Denne sida har ingen undersider.',
'movereason' => 'Årsak:',
'revertmove' => 'flytt attende',
'delete_and_move' => 'Slett og flytt',
'delete_and_move_text' => '== Sletting påkravd ==

Målsida «[[:$1]]» finst allereie. Vil du slette ho for å gje rom for flytting?',
'delete_and_move_confirm' => 'Ja, slett sida',
'delete_and_move_reason' => 'Sletta for å gje rom for flytting frå «[[$1]]»',
'selfmove' => 'Kjelde- og måltitlane er like; kan ikkje flytte sida over seg sjølv.',
'immobile-source-namespace' => 'Kan ikkje flytte sider i namnerommet «$1»',
'immobile-target-namespace' => 'Kan ikkje flytte sider til namnerommet «$1»',
'immobile-target-namespace-iw' => 'Interwikilenkja er ikkje eit gyldig mål for flytting av sider.',
'immobile-source-page' => 'Denne sida kan ikkje flyttast.',
'immobile-target-page' => 'Kan ikkje flytte til det målnamnet.',
'imagenocrossnamespace' => 'Kan ikkje flytte bilete til andre namnerom enn biletnamnerommet',
'nonfile-cannot-move-to-file' => 'Kan ikkje flytta ikkje-filer til filnamnerommet.',
'imagetypemismatch' => 'Den nye filendinga høver ikkje til filtypen',
'imageinvalidfilename' => 'Målnamnet er ugyldig',
'fix-double-redirects' => 'Oppdater omdirigeringar som viser til den gamle tittelen',
'move-leave-redirect' => 'La det vere att ei omdirigering',
'protectedpagemovewarning' => "'''ÅTVARING:''' Denne sida er verna, slik at berre brukarar med administratorrettar kan flytta henne.
Det siste loggelementet er oppgjeve under som referanse:",
'semiprotectedpagemovewarning' => "'''Merk:''' Denne sida er verna, slik at berre registrerte brukarar kan flytta henne.
Det siste loggelementet er oppgjeve under som referanse:",
'move-over-sharedrepo' => '== Fila finnst ==
[[:$1]] finst på ei delt kjelde. Om du flyttar ei fil til dette namnet, vil du overstyra den delte fila.',
'file-exists-sharedrepo' => 'Det valde filnamnet er allereie i bruk på ei delt kjelde.
Ver venleg og velg eit anna namn.',

# Export
'export' => 'Eksporter sider',
'exporttext' => 'Du kan eksportere teksten og endringshistorikken til ei bestemt side eller ei gruppe sider, pakka inn i litt XML.
Dette kan så importerast til ein annan wiki som brukar MediaWiki-programvara gjennom [[Special:Import|import-sida]].

For å eksportere sider, skriv inn titlar i tekstboksen under, ein tittel per linje, og velg om du vil ha berre noverande versjon, eller alle versjonar i historikken.

Dersom du berre vil ha noverande versjon, kan du også bruke ei lenkje, til dømes [[{{#Special:Export}}/{{MediaWiki:Mainpage}}]] for sida «[[{{MediaWiki:Mainpage}}]]».',
'exportall' => 'Eksporter alle sider',
'exportcuronly' => 'Berre eksporter siste versjonen, ikkje med heile historikken.',
'exportnohistory' => "----
'''Merk:''' Å eksportere heile sidehistorikkar gjennom dette skjemaet er slått av grunna problem med ytinga.",
'exportlistauthors' => 'Tak med ei heil liste over bidragsytarar for kvar side',
'export-submit' => 'Eksporter',
'export-addcattext' => 'Legg til sider frå kategori:',
'export-addcat' => 'Legg til',
'export-addnstext' => 'Legg til sider frå namnerommet:',
'export-addns' => 'Legg til',
'export-download' => 'Lagre som fil',
'export-templates' => 'Inkluder malane',
'export-pagelinks' => 'Inkluder lenkja sider med ei djupn på:',

# Namespace 8 related
'allmessages' => 'Systemmeldingar',
'allmessagesname' => 'Namn',
'allmessagesdefault' => 'Standardtekst',
'allmessagescurrent' => 'Gjeldande meldingstekst',
'allmessagestext' => 'Dette er ei liste over systemmeldingar i MediaWiki-namnerommet.
Vitja [//www.mediawiki.org/wiki/Localisation MediaWiki Localisation] og [//translatewiki.net translatewiki.net] om du ynskjer å bidra til den generelle omsetjinga av MediaWiki.',
'allmessagesnotsupportedDB' => "Denne sida kan ein ikkje bruka fordi «'''\$wgUseDatabaseMessages'''» er slått av.",
'allmessages-filter-legend' => 'Filtrer',
'allmessages-filter' => 'Filtrer etter tilpassingsgrad:',
'allmessages-filter-unmodified' => 'Ikkje endra',
'allmessages-filter-all' => 'Alle',
'allmessages-filter-modified' => 'Endra',
'allmessages-prefix' => 'Filtrer etter prefiks:',
'allmessages-language' => 'Språk:',
'allmessages-filter-submit' => 'Gå',

# Thumbnails
'thumbnail-more' => 'Forstørr',
'filemissing' => 'Fila manglar',
'thumbnail_error' => 'Feil ved oppretting av miniatyrbilete: $1',
'djvu_page_error' => 'DjVu-sida er utanfor rekkjevidd',
'djvu_no_xml' => 'Klarte ikkje hente inn XML for DjVu-fila',
'thumbnail-temp-create' => 'Kan ikkje oppretta mellombels fil for miniatyrbilete',
'thumbnail-dest-create' => 'Kunne ikkje lagra miniatyrbiletet til lagringsmålet',
'thumbnail_invalid_params' => 'Ugyldige miniatyrparameterar',
'thumbnail_dest_directory' => 'Klarte ikkje å opprette målmappe',
'thumbnail_image-type' => 'Bilettypen er ikkje stødd',
'thumbnail_gd-library' => 'Ufullstendig oppsett av GD library: manglar funksjonen $1',
'thumbnail_image-missing' => 'Fila ser ut til å saknast: $1',

# Special:Import
'import' => 'Importer sider',
'importinterwiki' => 'Transwikiimport',
'import-interwiki-text' => 'Vel ei wiki og ei side å importere. Endringssdatoer og brukarar som har medverka vert bevart. Alle transwiki-importeringar vert vist i [[Special:Log/import|importloggen]].',
'import-interwiki-source' => 'Kjeldewiki/sida:',
'import-interwiki-history' => 'Kopier all historikken for denne sida',
'import-interwiki-templates' => 'Inkluder alle malar',
'import-interwiki-submit' => 'Importer',
'import-interwiki-namespace' => 'Målnamnerom:',
'import-interwiki-rootpage' => 'Målrotside (valfri):',
'import-upload-filename' => 'Filnamn:',
'import-comment' => 'Kommentar:',
'importtext' => 'Lagre fila frå kjeldewikien med [[Special:Export|eksporteringsverktøyet]] på din eigen datamaskin, og last henne så opp her.',
'importstart' => 'Importerer sidene…',
'import-revision-count' => '$1 {{PLURAL:$1|versjon|versjonar}}',
'importnopages' => 'Ingen sider å importere.',
'imported-log-entries' => 'Importerte {{PLURAL:$1|eitt loggelement|$1 loggelement}}.',
'importfailed' => 'Importeringa var mislukka: $1',
'importunknownsource' => 'Ukjend importkjeldetype',
'importcantopen' => 'Kunne ikkje opne importfil',
'importbadinterwiki' => 'Ugyldig interwikilenkje',
'importnotext' => 'Tom eller ingen tekst',
'importsuccess' => 'Importeringa er ferdig!',
'importhistoryconflict' => 'Det kan vera at det er konflikt i historikken (kanskje sida vart importert før)',
'importnosources' => 'Ingen kjelder for transwikiimport er oppgjevne og funksjonen for opplasting av historikk er deaktivert.',
'importnofile' => 'Inga importfil er lasta opp.',
'importuploaderrorsize' => 'Opplastinga av importfila var mislukka. Fila er større enn det som er lov å laste opp.',
'importuploaderrorpartial' => 'Opplastinga av importfila var mislukka. Fila vart berre delvis lasta opp.',
'importuploaderrortemp' => 'Opplastinga av importfila var mislukka. Ei mellombels mappe manglar.',
'import-parse-failure' => 'Feil i tolking av XML-import',
'import-noarticle' => 'Ingen sider å importere!',
'import-nonewrevisions' => 'Alle versjonar var importert frå før.',
'xml-error-string' => '$1 på rad $2, kolonne $3 (byte: $4): $5',
'import-upload' => 'Last opp XML-data',
'import-token-mismatch' => 'Mista sesjonsdata. Ver venleg og prøv om att.',
'import-invalid-interwiki' => 'Kan ikkje importera frå den valde wikien.',
'import-error-edit' => '«$1» blei ikkje importert av di du ikkje har løyve til å redigere henne.',
'import-error-create' => 'Side $1 blei ikkje importert av di du ikkje har løyve til å redigere henne.',
'import-error-interwiki' => 'Sida «$1» vart ikkje importert sidan namnet hennar er reservert for ekstern lenking (interwiki).',
'import-error-special' => 'Sida «$1» vart ikkje importert sidan ho høyrer til eit spesialnamnerom som ikkje tillèt sider.',
'import-error-invalid' => 'Sida «$1» vart ikkje importert sidan namnet er ugildt.',
'import-options-wrong' => '{{PLURAL:$2|Galt val|Gale val}}: <nowiki>$1</nowiki>',
'import-rootpage-invalid' => 'Den oppgjevne rotsida er ein ugild tittel',
'import-rootpage-nosubpage' => 'Namnerommet «$1» til rotsida tillèt ikkje undersider.',

# Import log
'importlogpage' => 'Importeringslogg',
'importlogpagetext' => 'Administrativ import av sider med endringshistorikk frå andre wikiar.',
'import-logentry-upload' => 'importerte [[$1]] frå opplasta fil',
'import-logentry-upload-detail' => '{{PLURAL:$1|Éin versjon|$1 versjonar}}',
'import-logentry-interwiki' => 'overførte $1 mellom wikiar',
'import-logentry-interwiki-detail' => '{{PLURAL:$1|Éin versjon|$1 versjonar}} frå $2',

# JavaScriptTest
'javascripttest' => 'JavaScript-utrøyning',
'javascripttest-disabled' => 'Funksjonen er ikkje påslegen på wikien.',
'javascripttest-title' => 'Køyrer $1-utrøyningar',
'javascripttest-pagetext-noframework' => 'Sida er reservert for køyring av JavaScript-utrøyningar.',
'javascripttest-pagetext-unknownframework' => 'Ukjent utrøyningsrammeverk: «$1».',
'javascripttest-pagetext-frameworks' => 'Vel eitt av dei fylgjande utrøyningsrammeverka: $1',
'javascripttest-pagetext-skins' => 'Vel ei drakt som utrøyningane skal køyrast med:',
'javascripttest-qunit-intro' => 'Sjå [$1 utrøyningsdokumentasjon] på mediawiki.org.',
'javascripttest-qunit-heading' => 'MediaWiki JavaScript QUnit testsuite',

# Tooltip help for the actions
'tooltip-pt-userpage' => 'Brukarsida di',
'tooltip-pt-anonuserpage' => 'Brukarsida for ip-adressa du endrar under',
'tooltip-pt-mytalk' => 'Diskusjonssida di',
'tooltip-pt-anontalk' => 'Diskusjon om endringar gjorde av denne ip-adressa',
'tooltip-pt-preferences' => 'Innstillingane dine',
'tooltip-pt-watchlist' => 'Liste over sidene du overvakar.',
'tooltip-pt-mycontris' => 'Liste over bidraga dine',
'tooltip-pt-login' => 'Det er ikkje obligatorisk å logga inn, men medfører mange fordelar.',
'tooltip-pt-anonlogin' => 'Det er ikkje obligatorisk å logga inn, men medfører mange fordelar.',
'tooltip-pt-logout' => 'Logg ut',
'tooltip-ca-talk' => 'Diskusjon om innhaldssida',
'tooltip-ca-edit' => 'Du kan endre denne sida. Bruk førehandsvisings-knappen før du lagrar.',
'tooltip-ca-addsection' => 'Start ein ny bolk',
'tooltip-ca-viewsource' => 'Denne sida er verna, men du kan sjå kjeldeteksten.',
'tooltip-ca-history' => 'Eldre versjonar av sida',
'tooltip-ca-protect' => 'Vern denne sida',
'tooltip-ca-unprotect' => 'Endra vernet av sida',
'tooltip-ca-delete' => 'Slett denne sida',
'tooltip-ca-undelete' => 'Attopprett denne sida',
'tooltip-ca-move' => 'Flytt denne sida',
'tooltip-ca-watch' => 'Legg til sida i overvakingslista di',
'tooltip-ca-unwatch' => 'Fjern sida frå overvakingslista di',
'tooltip-search' => 'Søk gjennom {{SITENAME}}',
'tooltip-search-go' => 'Gå til ei side med dette namnet om ho finst',
'tooltip-search-fulltext' => 'Søk etter sider som inneheld denne teksten',
'tooltip-p-logo' => 'Hovudside',
'tooltip-n-mainpage' => 'Gå til hovudsida',
'tooltip-n-mainpage-description' => 'Gå til hovudsida',
'tooltip-n-portal' => 'Om prosjektet, kva du kan gjera, kvar du finn saker og ting',
'tooltip-n-currentevents' => 'Aktuelt',
'tooltip-n-recentchanges' => 'Liste over dei siste endringane som er gjorde på wikien.',
'tooltip-n-randompage' => 'Vis ei tilfeldig side',
'tooltip-n-help' => 'Hjelp til å bruke alle funksjonane.',
'tooltip-t-whatlinkshere' => 'Liste over alle wikisidene som har lenkjer hit',
'tooltip-t-recentchangeslinked' => 'Siste endringar på sider denne sida lenkjer til',
'tooltip-feed-rss' => 'RSS-mating for denne sida',
'tooltip-feed-atom' => 'Atom-mating for denne sida',
'tooltip-t-contributions' => 'Sjå liste over bidrag frå denne brukaren',
'tooltip-t-emailuser' => 'Send ein e-post til denne brukaren',
'tooltip-t-upload' => 'Last opp filer',
'tooltip-t-specialpages' => 'Liste over spesialsider',
'tooltip-t-print' => 'Utskriftsversjon av sida',
'tooltip-t-permalink' => 'Fast lenkje til denne versjonen av sida',
'tooltip-ca-nstab-main' => 'Vis innhaldssida',
'tooltip-ca-nstab-user' => 'Vis brukarsida',
'tooltip-ca-nstab-media' => 'Direktelenkje (filpeikar) til fil',
'tooltip-ca-nstab-special' => 'Dette er ei spesialside, du kan ikkje endre ho',
'tooltip-ca-nstab-project' => 'Vis prosjektside',
'tooltip-ca-nstab-image' => 'Vis filside',
'tooltip-ca-nstab-mediawiki' => 'Vis systemmelding',
'tooltip-ca-nstab-template' => 'Vis mal',
'tooltip-ca-nstab-help' => 'Vis hjelpeside',
'tooltip-ca-nstab-category' => 'Vis kategoriside',
'tooltip-minoredit' => 'Merk dette som småplukk',
'tooltip-save' => 'Lagra endringane dine',
'tooltip-preview' => 'Førehandsvis endringane dine, bruk denne funksjonen før du lagrar!',
'tooltip-diff' => 'Sjå kva endringar du gjorde i teksten',
'tooltip-compareselectedversions' => 'Sjå endringane mellom dei valde versjonane av denne sida.',
'tooltip-watch' => 'Legg sida til i overvakingslista di [alt-w]',
'tooltip-watchlistedit-normal-submit' => 'Fjerna titlar',
'tooltip-watchlistedit-raw-submit' => 'Oppdater overvakingslista',
'tooltip-recreate' => 'Ved å trykkje på «Nyopprett» vert sida oppretta på nytt.',
'tooltip-upload' => 'Start opplastinga',
'tooltip-rollback' => '«Rulla attende»-knappen rullar med eitt klikk attende endringa(ne) på sida gjorde av den siste bidragsytaren',
'tooltip-undo' => '«Gjer om» attenderullar endringar og opnar endringsvindauga med førehandsvising. Gjer at ein kan leggje til ei årsak samandragsboksen.',
'tooltip-preferences-save' => 'Lagra innstillingar',
'tooltip-summary' => 'Skriv inn eit kort samandrag',

# Stylesheets
'common.css' => '/* CSS plassert i denne fila vil gjelde for alle utsjånader. */',
'standard.css' => '/* CSS i denne fila vil gjelde alle som nyttar drakta Standard */',
'nostalgia.css' => '/* CSS i denne fila vil gjelde alle som nyttar drakta Nostalgia */',
'cologneblue.css' => '/* CSS i denne fila vil gjelde alle som nyttar drakta Kølnerblå */',
'monobook.css' => '/* CSS-tekst som vert plassert her, endrar utsjånaden til sidedrakta Monobook */',
'myskin.css' => '/* CSS i denne fila vil gjelde alle som nyttar drakta MySkin */',
'chick.css' => '/* CSS i denne fila vil gjelde alle som nyttar drakta Chick */',
'simple.css' => '/* CSS i denne fila vil gjelde alle som nyttar drakta Simple */',
'modern.css' => '/* CSS i denne fila vil gjelde alle som nyttar drakta Modern */',
'print.css' => '/* CSS i denne fila vil påverke utskriftsversjonen */',
'handheld.css' => '/* CSS i denne fila vil gjelde alle handheldte innretnigar konfigurert i $wgHandheldStyle */',

# Scripts
'common.js' => '/* Javascript i denne fila vil gjelde for alle drakter. */',
'standard.js' => '/* Javascript i denne fila vil gjelde for brukarar av drakta Standard */',
'nostalgia.js' => '/* Javascript i denne fila vil gjelde for brukarar av drakta Nostalgia */',
'cologneblue.js' => '/* Javascript i denne fila vil gjelde for brukarar av drakta Kølnerblå */',
'monobook.js' => '/* Javascript i denne fila vil gjelde for brukarar av drakta Monobook */',
'myskin.js' => '* Javascript i denne fila vil gjelde for brukarar av drakta MySkin */',
'chick.js' => '* Javascript i denne fila vil gjelde for brukarar av drakta Chick */',
'simple.js' => '* Javascript i denne fila vil gjelde for brukarar av drakta Simple */',
'modern.js' => '* Javascript i denne fila vil gjelde for brukarar av drakta Modern */',

# Metadata
'notacceptable' => 'Wikitenaren kan ikkje gje data i noko format som programmet ditt kan lesa.',

# Attribution
'anonymous' => '{{PLURAL:$1|anonym brukar|anonyme brukarar}} av {{SITENAME}}',
'siteuser' => '{{SITENAME}}-brukaren $1',
'anonuser' => '{{SITENAME}} anonym brukar $1',
'lastmodifiedatby' => 'Sida vart sist endra den $1 kl. $2 av $3.',
'othercontribs' => 'Basert på arbeid av $1.',
'others' => 'andre',
'siteusers' => '{{SITENAME}}-{{PLURAL:$2|brukaren|brukarane}} $1',
'anonusers' => '{{PLURAL:$2|den anonyme brukaren|dei anonyme brukararane}} $1 på {{SITENAME}}',
'creditspage' => 'Sidegodskriving',
'nocredits' => 'Det finst ikkje ikkje nokon godskrivingsinformasjon for denne sida.',

# Spam protection
'spamprotectiontitle' => 'Filter for vern mot reklame',
'spamprotectiontext' => 'Sida du prøvde å lagre vart blokkert av spamfilteret. Dette kjem truleg av at ei ekstern lenkje på sida er svartelista.',
'spamprotectionmatch' => 'Den følgjande teksten utløyste reklamefilteret: $1',
'spambot_username' => 'MediaWiki si spamopprydding',
'spam_reverting' => 'Attenderullar til siste versjon utan lenkje til $1',
'spam_blanking' => 'Alle versjonar inneheldt lenkje til $1, tømmer sida',
'spam_deleting' => 'Alle versjonane inneheldt lenkjer til $1, slettar.',

# Info page
'pageinfo-title' => 'Informasjon om «$1»',
'pageinfo-not-current' => 'Diverre er det umogeleg å gje ut denne informasjonen for gamle versjonar.',
'pageinfo-header-basic' => 'Grunnleggjande informasjon',
'pageinfo-header-edits' => 'Endringshistorikk',
'pageinfo-header-restrictions' => 'Sidevern',
'pageinfo-header-properties' => 'Sideeigenskapar',
'pageinfo-display-title' => 'Visingstittel',
'pageinfo-default-sort' => 'Standard sorteringsnykel',
'pageinfo-length' => 'Sidelengd (i byte)',
'pageinfo-article-id' => 'Side-ID',
'pageinfo-robot-policy' => 'Søkjemotorstode',
'pageinfo-robot-index' => 'Kan indekserast',
'pageinfo-robot-noindex' => 'Kan ikkje indekserast',
'pageinfo-views' => 'Tal på visningar',
'pageinfo-watchers' => 'Tal på overvakarar av sida',
'pageinfo-redirects-name' => 'Omdirigeringar til sida',
'pageinfo-subpages-name' => 'Undersider av sida',
'pageinfo-subpages-value' => '$1 ({{PLURAL:$2|éi omdirigering|$2 omdirigeringar}}; {{PLURAL:$3|éi ikkje-omdirigering|$3 ikkje-omdirigeringar}})',
'pageinfo-firstuser' => 'Sideopprettar',
'pageinfo-firsttime' => 'Dato for opprettinga av sida',
'pageinfo-lastuser' => 'Siste forfattaren',
'pageinfo-lasttime' => 'Dato for siste endringa',
'pageinfo-edits' => 'Totalt tal på endringar',
'pageinfo-authors' => 'Totalt tal på ulike forfattarar',
'pageinfo-recent-edits' => 'Tal på nylege endringar (innan dei siste $1)',
'pageinfo-recent-authors' => 'Tal på nylege forfattarar',
'pageinfo-magic-words' => '{{PLURAL:$1|Trylleord}} ($1)',
'pageinfo-hidden-categories' => '{{PLURAL:$1|Løynd kategori|Løynde kategoriar}} ($1)',
'pageinfo-templates' => '{{PLURAL:$1|Inkludert mal|Inkluderte malar}} ($1)',

# Skin names
'skinname-standard' => 'Klassisk',
'skinname-nostalgia' => 'Nostalgi',
'skinname-cologneblue' => 'Kölnerblå',
'skinname-monobook' => 'MonoBook',
'skinname-myskin' => 'MiDrakt',
'skinname-chick' => 'Chick',
'skinname-simple' => 'Enkel',
'skinname-modern' => 'Moderne',

# Patrolling
'markaspatrolleddiff' => 'Merk som patruljert',
'markaspatrolledtext' => 'Merk innhaldssida som patruljert',
'markedaspatrolled' => 'Merk som patruljert',
'markedaspatrolledtext' => 'Den valde versjonen av [[:$1]] er vorten merkt som patruljert.',
'rcpatroldisabled' => 'Siste-endringar-patruljering er deaktivert',
'rcpatroldisabledtext' => 'Patruljeringsfunksjonen er deaktivert.',
'markedaspatrollederror' => 'Kan ikkje merke sida som patruljert',
'markedaspatrollederrortext' => 'Du må markere ein versjon for å kunne godkjenne.',
'markedaspatrollederror-noautopatrol' => 'Ein har ikkje høve til å merkje sine eigne endringar som godkjende.',

# Patrol log
'patrol-log-page' => 'Patruljeringslogg',
'patrol-log-header' => 'Dette er ein logg over patruljerte sideversjonar.',
'log-show-hide-patrol' => '$1 patruljeringslogg',

# Image deletion
'deletedrevision' => 'Slett gammal versjon $1',
'filedeleteerror-short' => 'Feil ved sletting av fila: $1',
'filedeleteerror-long' => 'Det vart ein feil under filslettinga av:

$1',
'filedelete-missing' => 'Det finst ikkje noko fil som heiter «$1», og difor går det heller ikkje å slette noko slik fil.',
'filedelete-old-unregistered' => 'Filversjonen «$1» finst ikkje i databasen.',
'filedelete-current-unregistered' => 'Fila «$1» finst ikkje i databasen.',
'filedelete-archive-read-only' => 'Tenaren har ikkje skrivetilgang til arkivkatalogen «$1».',

# Browsing diffs
'previousdiff' => '← Eldre endring',
'nextdiff' => 'Nyare endring →',

# Media information
'mediawarning' => "'''Åtvaring''': Denne fila kan innehalda skadeleg programkode, ved å køyra programmet kan systemet ditt ta skade.",
'imagemaxsize' => "Avgrens storleiken for bilete:<br />''(for sider som skildrar filer)''",
'thumbsize' => 'Miniatyrstørrelse:',
'widthheightpage' => '$1 × $2, {{PLURAL:$3|éi side|$3 sider}}',
'file-info' => 'filstorleik: $1, MIME-type: $2',
'file-info-size' => '$1 × $2 pikslar, filstorleik: $3, MIME-type: $4',
'file-info-size-pages' => '$1 × $2 pikslar, filstorleik: $3, MIME-type: $4, {{PLURAL:$5|éi side|$5 sider}}',
'file-nohires' => 'Høgare oppløysing er ikkje tilgjengeleg.',
'svg-long-desc' => 'SVG-fil, standardoppløysing: $1 × $2 pikslar, filstorleik: $3',
'svg-long-desc-animated' => 'Animert SVG-fil, standardoppløysing $1 × $2 pikslar, filstorleik: $3',
'show-big-image' => 'Full oppløysing',
'show-big-image-preview' => 'Storleik på førehandsvising: $1.',
'show-big-image-other' => '{{PLURAL:$2|Anna oppløysing|Andre oppløysingar}}: $1.',
'show-big-image-size' => '$1 × $2 pikslar',
'file-info-gif-looped' => 'gjentatt',
'file-info-gif-frames' => '$1 {{PLURAL:$1|rame|ramer}}',
'file-info-png-looped' => '↓oppatteke',
'file-info-png-repeat' => 'spela av {{PLURAL:$1|éin gong|$1 gonger}}',
'file-info-png-frames' => '$1 {{PLURAL:$1|bilete|bilete}}',
'file-no-thumb-animation' => "'''Merk: Grunna tekniske avgrensingar vil ikkje miniatyrbilete av fila verta animerte.'''",
'file-no-thumb-animation-gif' => "'''Merk: Grunna tekniska avgrensingar vil ikkje miniatyrbilete av høgoppløyselege GIF-bilete som dette verta animerte.'''",

# Special:NewFiles
'newimages' => 'Filgalleri',
'imagelisttext' => 'Her er ei liste med {{PLURAL:$1|éi fil sortert|$1 filer sorterte}} $2.',
'newimages-summary' => 'Denne spesialsida syner dei sist opplasta filene.',
'newimages-legend' => 'Filnamn',
'newimages-label' => 'Filnamn (eller ein del av det):',
'showhidebots' => '($1 robotar)',
'noimages' => 'Her er ingen filer som kan visast.',
'ilsubmit' => 'Søk',
'bydate' => 'etter dato',
'sp-newimages-showfrom' => 'Vis nye filer frå og med $2 $1',

# Video information, used by Language::formatTimePeriod() to format lengths in the above messages
'seconds' => '{{PLURAL:$1|$1 sekund|$1 sekund}}',
'minutes' => '{{PLURAL:$1|$1 minutt|$1 minutt}}',
'hours' => '{{PLURAL:$1|$1 time|$1 timar}}',
'days' => '{{PLURAL:$1|$1 dag|$1 dagar}}',
'ago' => '$1 sidan',

# Bad image list
'bad_image_list' => 'Formatet er slik:

Berre liner som startar med asterisk (*) vert tekne med.
Den fyrste lenkja på ei line må gå til ei uønskt fil.
Alle andre lenkjer på same line vert sett på som unnatak, med andre ord sider der fila kan brukast.',

# Metadata
'metadata' => 'Utvida informasjon',
'metadata-help' => 'Fila inneheld tilleggsopplysningar, mest sannsynleg frå digitalkameraet eller skannaren som vart brukt til å lage eller digitalisere henne.
Dersom fila har vore endra sidan ho vart oppretta, kan nokre av opplysningane vere feil.',
'metadata-expand' => 'Vis utvida opplysningar',
'metadata-collapse' => 'Gøym utvida opplysningar',
'metadata-fields' => 'Biletmetadatafelta opplista i meldinga er med på filskildringssida når metadatatabellen er samanslegen.
Andre er gøymde som standard.
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

# EXIF tags
'exif-imagewidth' => 'Breidd',
'exif-imagelength' => 'Høgd',
'exif-bitspersample' => 'Bitar per komponent',
'exif-compression' => 'Komprimeringsteknikk',
'exif-photometricinterpretation' => 'Pikselsamansetjing',
'exif-orientation' => 'Retning',
'exif-samplesperpixel' => 'Tal komponentar',
'exif-planarconfiguration' => 'Dataarrangement',
'exif-ycbcrsubsampling' => 'Subsamplingstilhøve mellom Y og C',
'exif-ycbcrpositioning' => 'Y- og C-posisjon',
'exif-xresolution' => 'Oppløysing i breidda',
'exif-yresolution' => 'Oppløysing i høgda',
'exif-stripoffsets' => 'Plassering for biletdata',
'exif-rowsperstrip' => 'Tal rader per stripe',
'exif-stripbytecounts' => 'Tal byte per kompimerte stripe',
'exif-jpeginterchangeformat' => 'Offset til JPEG SOI',
'exif-jpeginterchangeformatlength' => 'Byte JPEG-data',
'exif-whitepoint' => 'Kvitpunktsreinleik',
'exif-primarychromaticities' => 'Reinheita til primærfargane',
'exif-ycbcrcoefficients' => 'Koeffisientar for fargeromstransformasjonsmatrise',
'exif-referenceblackwhite' => 'Svart og kvitt referanseverdipar',
'exif-datetime' => 'Dato og tid endra',
'exif-imagedescription' => 'Tittel',
'exif-make' => 'Kameraprodusent',
'exif-model' => 'Kameramodell',
'exif-software' => 'Programvare brukt',
'exif-artist' => 'Skapar',
'exif-copyright' => 'Opphavsrettsleg eigar',
'exif-exifversion' => 'Exif-versjon',
'exif-flashpixversion' => 'Støtta Flashpix versjon',
'exif-colorspace' => 'Fargerom',
'exif-componentsconfiguration' => 'Komponentanalyse',
'exif-compressedbitsperpixel' => 'Komprimerte bits pr. pixel',
'exif-pixelydimension' => 'Biletbreidd',
'exif-pixelxdimension' => 'Bilethøgd',
'exif-usercomment' => 'Brukarkommentarar',
'exif-relatedsoundfile' => 'Tilknytt lydfil',
'exif-datetimeoriginal' => 'Dato og tid laga',
'exif-datetimedigitized' => 'Dato og tid digitalisert',
'exif-subsectime' => 'Dato og tid subsekund',
'exif-subsectimeoriginal' => 'Dato og tid laga subsekund',
'exif-subsectimedigitized' => 'Dato og tid digitalisert subsekund',
'exif-exposuretime' => 'Eksponeringstid',
'exif-exposuretime-format' => '$1 sekund ($2)',
'exif-fnumber' => 'F-nummer',
'exif-exposureprogram' => 'Eksponeringsprogram',
'exif-spectralsensitivity' => 'Spektralsensitivitet',
'exif-isospeedratings' => 'Lyskjensle (ISO)',
'exif-shutterspeedvalue' => 'APEX-lukkarfart',
'exif-aperturevalue' => 'APEX-blendartal',
'exif-brightnessvalue' => 'APEX-lysstyrke',
'exif-exposurebiasvalue' => 'Eksponeringsinnstilling',
'exif-maxaperturevalue' => 'Maksimal blendar',
'exif-subjectdistance' => 'Motivavstand',
'exif-meteringmode' => 'Lysmålarmodus',
'exif-lightsource' => 'Lyskjelde',
'exif-flash' => 'Blits',
'exif-focallength' => 'Linsefokallengd',
'exif-subjectarea' => 'Motivområde',
'exif-flashenergy' => 'Blitsstyrke',
'exif-focalplanexresolution' => 'Oppløysing i fokalplan X',
'exif-focalplaneyresolution' => 'Oppløysing i fokalplan Y',
'exif-focalplaneresolutionunit' => 'Oppløysingseining for fokalplanet',
'exif-subjectlocation' => 'Motivplassering',
'exif-exposureindex' => 'Eksponeringsindeks',
'exif-sensingmethod' => 'Sensor',
'exif-filesource' => 'Filkjelde',
'exif-scenetype' => 'Scenetype',
'exif-customrendered' => 'Tilpassa biletehandsaming',
'exif-exposuremode' => 'Eksponeringsmodus',
'exif-whitebalance' => 'Kvitbalanse',
'exif-digitalzoomratio' => 'Digital zoom-rate',
'exif-focallengthin35mmfilm' => '(Tilsvarande) brennvidd ved 35 mm film',
'exif-scenecapturetype' => 'Motivtype',
'exif-gaincontrol' => 'Scenekontroll',
'exif-contrast' => 'Kontrast',
'exif-saturation' => 'Metting',
'exif-sharpness' => 'Skarpleik',
'exif-devicesettingdescription' => 'Apparatinnstilling',
'exif-subjectdistancerange' => 'Motivavstandsområde',
'exif-imageuniqueid' => 'Unik bilete-ID',
'exif-gpsversionid' => 'GPS-merke-versjon',
'exif-gpslatituderef' => 'Nordleg eller sørleg breiddegrad',
'exif-gpslatitude' => 'Breiddegrad',
'exif-gpslongituderef' => 'Austleg eller vestleg lengdegrad',
'exif-gpslongitude' => 'Lengdegrad',
'exif-gpsaltituderef' => 'Høgdereferanse',
'exif-gpsaltitude' => 'Høgd over havet',
'exif-gpstimestamp' => 'GPS-tid (atomklokke)',
'exif-gpssatellites' => 'Satellittar brukt for å måle',
'exif-gpsstatus' => 'GPS-Mottakarstatus',
'exif-gpsmeasuremode' => 'Målemodus',
'exif-gpsdop' => 'Målepresisjon',
'exif-gpsspeedref' => 'Fartsmåleining',
'exif-gpsspeed' => 'Fart på GPS-mottakar',
'exif-gpstrackref' => 'Referanse for rørsleretning',
'exif-gpstrack' => 'Rørsleretning',
'exif-gpsimgdirectionref' => 'Referanse for retning åt biletet',
'exif-gpsimgdirection' => 'Retninga åt biletet',
'exif-gpsmapdatum' => 'Geodetisk kartleggingsdata brukt',
'exif-gpsdestlatituderef' => 'Referanse for målbreiddegrad',
'exif-gpsdestlatitude' => 'Målbreiddegrad',
'exif-gpsdestlongituderef' => 'Referanse for mållengdegrad',
'exif-gpsdestlongitude' => 'Mållengdegrad',
'exif-gpsdestbearingref' => 'Referanse for retning mot målet',
'exif-gpsdestbearing' => 'Retning mot målet',
'exif-gpsdestdistanceref' => 'Referanse for avstand til mål',
'exif-gpsdestdistance' => 'Avstand til mål',
'exif-gpsprocessingmethod' => 'Namn på GPS-handsamingsmetode',
'exif-gpsareainformation' => 'Namn på GPS-område',
'exif-gpsdatestamp' => 'GPS-dato',
'exif-gpsdifferential' => 'Differensiell GPS-retting',
'exif-jpegfilecomment' => 'JPEG-filkommentar',
'exif-keywords' => 'Nøkkelord',
'exif-worldregioncreated' => 'Verdsregionen biletet blei teke i',
'exif-countrycreated' => 'Land biletet blei teke i',
'exif-countrycodecreated' => 'Landkoden for der biletet blei teke',
'exif-provinceorstatecreated' => 'Provins, delstat eller region der biletet blei teke',
'exif-citycreated' => 'By biletet blei teke i',
'exif-sublocationcreated' => 'Bydel bilete blei teke i',
'exif-worldregiondest' => 'Verdsregionen vist',
'exif-countrydest' => 'Land vist',
'exif-countrycodedest' => 'Landkode vist',
'exif-provinceorstatedest' => 'Provins, delstat eller region vist',
'exif-citydest' => 'By vist',
'exif-sublocationdest' => 'Bydel vist',
'exif-objectname' => 'Kort tittel',
'exif-specialinstructions' => 'Spesieller instuksjonar',
'exif-headline' => 'Overskrift',
'exif-credit' => 'Opphavrettseigar/filgjevar',
'exif-source' => 'Kjelde',
'exif-editstatus' => 'Den redaksjonelle stoda til biletet',
'exif-urgency' => 'Prioritet',
'exif-fixtureidentifier' => 'Namn på tidgjengt emne',
'exif-locationdest' => 'Avbilda stad',
'exif-locationdestcode' => 'Koden til staden som er avbilda',
'exif-objectcycle' => 'Tid på dagen mediet er meint for',
'exif-contact' => 'Kontaktinformasjon',
'exif-writer' => 'Forfattar',
'exif-languagecode' => 'Språk',
'exif-iimversion' => 'IIM-versjon',
'exif-iimcategory' => 'Kategori',
'exif-iimsupplementalcategory' => 'Tilleggskategoriar',
'exif-datetimeexpires' => 'Skal ikkje nyttast etter',
'exif-datetimereleased' => 'Frigjeve',
'exif-originaltransmissionref' => 'Opphavleg stadkode for overføring',
'exif-identifier' => 'Kjennemerke',
'exif-lens' => 'Objektiv',
'exif-serialnumber' => 'Serienummeret på kameraet',
'exif-cameraownername' => 'Eigar av kameraet',
'exif-label' => 'Merkelapp',
'exif-datetimemetadata' => 'Datoen metadata sist vart endra',
'exif-nickname' => 'Det uformelle namnet på biletet',
'exif-rating' => 'Vurdering (av 5)',
'exif-rightscertificate' => 'Retthandsamingssertifikat',
'exif-copyrighted' => 'Opphavsrettsstode',
'exif-copyrightowner' => 'Opphavsrettseigar',
'exif-usageterms' => 'Bruksvilkår',
'exif-webstatement' => 'Opphavsrettsfråsegn på nett',
'exif-originaldocumentid' => 'Unik ID til originaldokumentet',
'exif-licenseurl' => 'URL for opphavsrettsløyve',
'exif-morepermissionsurl' => 'Alternativ løyveinformasjon',
'exif-attributionurl' => 'Når dette verket vert nytta, lenkja til',
'exif-preferredattributionname' => 'Når dette verket vert nytta, godskriv',
'exif-pngfilecomment' => 'PNG-filkommentar',
'exif-disclaimer' => 'Atterhald',
'exif-contentwarning' => 'Innholdsåtvaring',
'exif-giffilecomment' => 'GIF-filkommentar',
'exif-intellectualgenre' => 'Elementtype',
'exif-subjectnewscode' => 'Emnekode',
'exif-scenecode' => 'IPTC-scenekode',
'exif-event' => 'Avbilda hending',
'exif-organisationinimage' => 'Avbilda organisasjon',
'exif-personinimage' => 'Avbilda person',
'exif-originalimageheight' => 'Høgda på biletet før det vart beskåren',
'exif-originalimagewidth' => 'Bredda på biletet før det vart beskåren',

# EXIF attributes
'exif-compression-1' => 'Ukomprimert',
'exif-compression-2' => 'CCITT Gruppe 3 1-dimensjonal modifisert Huffman-kjøyrelengdekoding',
'exif-compression-3' => 'CCITT Gruppe 3 faks-koding',
'exif-compression-4' => 'CCITT Gruppe 4 faks-koding',

'exif-copyrighted-true' => 'Verna av opphavsrett',
'exif-copyrighted-false' => 'Ikkje verna av opphavsrett',

'exif-unknowndate' => 'Ukjend dato',

'exif-orientation-1' => 'Normal',
'exif-orientation-2' => 'Spegla vassrett',
'exif-orientation-3' => 'Rotert 180°',
'exif-orientation-4' => 'Spegla loddrett',
'exif-orientation-5' => 'Rotert 90° motsols og spegla vassrett',
'exif-orientation-6' => 'Rotert 90° motsols',
'exif-orientation-7' => 'Rotert 90° medsols og spegla loddrett',
'exif-orientation-8' => 'Rotert 90° medsols',

'exif-planarconfiguration-1' => 'grovformat',
'exif-planarconfiguration-2' => 'planærformat',

'exif-colorspace-65535' => 'Ukalibrert',

'exif-componentsconfiguration-0' => 'finst ikkje',

'exif-exposureprogram-0' => 'Ikkje bestemt',
'exif-exposureprogram-1' => 'Manuelt',
'exif-exposureprogram-2' => 'Normalt program',
'exif-exposureprogram-3' => 'Blendarprioritet',
'exif-exposureprogram-4' => 'Lukkarprioritet',
'exif-exposureprogram-5' => 'Kreativt program (mest mogleg skarpt)',
'exif-exposureprogram-6' => 'Handlingsprogram (med vekt på snøgg lukkar)',
'exif-exposureprogram-7' => 'Portrettmodus (for nærbilete med uskarp bakgrunn)',
'exif-exposureprogram-8' => 'Landskapsmodus (for landskapsbilete med skarp bakgrunn)',

'exif-subjectdistance-value' => '$1 meter',

'exif-meteringmode-0' => 'Ukjent',
'exif-meteringmode-1' => 'Snittmåling',
'exif-meteringmode-2' => 'Snittmåling med vekt på midten',
'exif-meteringmode-3' => 'Punktmåling',
'exif-meteringmode-4' => 'Fleirpunktsmåling',
'exif-meteringmode-5' => 'Mønster',
'exif-meteringmode-6' => 'Delvis',
'exif-meteringmode-255' => 'Annan',

'exif-lightsource-0' => 'Ukjent',
'exif-lightsource-1' => 'Dagslys',
'exif-lightsource-2' => 'Fluorescerande',
'exif-lightsource-3' => 'Glødelampe',
'exif-lightsource-4' => 'Blits',
'exif-lightsource-9' => 'Fint vêr',
'exif-lightsource-10' => 'Overskya vêr',
'exif-lightsource-11' => 'Skugge',
'exif-lightsource-12' => 'Fluorescerande dagslys (D 5700 – 7100K)',
'exif-lightsource-13' => 'Dag, kvitt, fluorescerande (N 4600 – 5400K)',
'exif-lightsource-14' => 'Kjølig, kvitt, fluorescerande (W 3900 – 4500K)',
'exif-lightsource-15' => 'Kvitt fluorescerande (WW 3200 – 3700K)',
'exif-lightsource-17' => 'Standardlys A',
'exif-lightsource-18' => 'Standardlys B',
'exif-lightsource-19' => 'Standardlys C',
'exif-lightsource-24' => 'ISO studio kunstljos',
'exif-lightsource-255' => 'Anna lyskjelde',

# Flash modes
'exif-flash-fired-0' => 'Blitzen vart ikkje utløyst',
'exif-flash-fired-1' => 'Blitz utløyst',
'exif-flash-return-0' => 'ingen funksjon for å oppdage pulserande lys',
'exif-flash-return-2' => 'pulserande lys ikkje oppdaga',
'exif-flash-return-3' => 'pulserande lys oppdaga',
'exif-flash-mode-1' => 'tvungen blitzutløysing',
'exif-flash-mode-2' => 'tvungen blitz stengd',
'exif-flash-mode-3' => 'automatisk modus',
'exif-flash-function-1' => 'Ingen blitzfunksjon',
'exif-flash-redeye-1' => 'redusering av raude auge',

'exif-focalplaneresolutionunit-2' => 'tommar',

'exif-sensingmethod-1' => 'Ikkje bestemt',
'exif-sensingmethod-2' => 'Einbrikka fargeområdesensor',
'exif-sensingmethod-3' => 'Tobrikka fargeområdesensor',
'exif-sensingmethod-4' => 'Trebrikka fargeområdesensor',
'exif-sensingmethod-5' => 'Fargesekvensiell områdesensor',
'exif-sensingmethod-7' => 'Trilinær sensor',
'exif-sensingmethod-8' => 'Fargesekvensiell lineærsensor',

'exif-filesource-3' => 'Digitalt stillbiletekamera',

'exif-scenetype-1' => 'Direkte fotografert bilete',

'exif-customrendered-0' => 'Normal prosess',
'exif-customrendered-1' => 'Tilpassa prosess',

'exif-exposuremode-0' => 'Autoeksponert',
'exif-exposuremode-1' => 'Manuelt eksponert',
'exif-exposuremode-2' => 'Automatisk alternativeksponering',

'exif-whitebalance-0' => 'Automatisk kvitbalanse',
'exif-whitebalance-1' => 'Manuell kvitbalanse',

'exif-scenecapturetype-0' => 'Standard',
'exif-scenecapturetype-1' => 'Landskap',
'exif-scenecapturetype-2' => 'Portrett',
'exif-scenecapturetype-3' => 'Nattscene',

'exif-gaincontrol-0' => 'Ingen',
'exif-gaincontrol-1' => 'Auke av lågnivåforsterking',
'exif-gaincontrol-2' => 'Auke av høgnivåforsterking',
'exif-gaincontrol-3' => 'Minking av lågnivåforsterking',
'exif-gaincontrol-4' => 'Minking av høgnivåforsterking',

'exif-contrast-0' => 'Normal',
'exif-contrast-1' => 'Mjuk',
'exif-contrast-2' => 'Hard',

'exif-saturation-0' => 'Normal',
'exif-saturation-1' => 'Låg metting',
'exif-saturation-2' => 'Høg metting',

'exif-sharpness-0' => 'Normal',
'exif-sharpness-1' => 'Mjuk',
'exif-sharpness-2' => 'Hard',

'exif-subjectdistancerange-0' => 'Ukjent',
'exif-subjectdistancerange-1' => 'Makro',
'exif-subjectdistancerange-2' => 'Nært',
'exif-subjectdistancerange-3' => 'Fjernt',

# Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => 'Nordleg breiddegrad',
'exif-gpslatitude-s' => 'Sørleg breiddegrad',

# Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => 'Austleg lengdegrad',
'exif-gpslongitude-w' => 'Vestleg lengdegrad',

# Pseudotags used for GPSAltitudeRef
'exif-gpsaltitude-above-sealevel' => '{{PLURAL:$1|Éin|$1}} meter over havet',
'exif-gpsaltitude-below-sealevel' => '{{PLURAL:$1|Éin|$1}} meter under havet',

'exif-gpsstatus-a' => 'Måling pågår',
'exif-gpsstatus-v' => 'Målingsinteroperabilitet',

'exif-gpsmeasuremode-2' => 'todimensjonalt målt',
'exif-gpsmeasuremode-3' => 'tredimensjonalt målt',

# Pseudotags used for GPSSpeedRef
'exif-gpsspeed-k' => 'Kilometer per time',
'exif-gpsspeed-m' => 'Engelsk mil per time',
'exif-gpsspeed-n' => 'Knop',

# Pseudotags used for GPSDestDistanceRef
'exif-gpsdestdistance-k' => 'Kilometer',
'exif-gpsdestdistance-m' => 'Miles',
'exif-gpsdestdistance-n' => 'Nautiske mil',

'exif-gpsdop-excellent' => 'Utmerkt ($1)',
'exif-gpsdop-good' => 'God ($1)',
'exif-gpsdop-moderate' => 'Moderat ($1)',
'exif-gpsdop-fair' => 'Medels ($1)',
'exif-gpsdop-poor' => 'Dårleg ($1)',

'exif-objectcycle-a' => 'Berre morgon',
'exif-objectcycle-p' => 'Berre kveld',
'exif-objectcycle-b' => 'Både morgon og kveld',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'Verkeleg retning',
'exif-gpsdirection-m' => 'Magnetisk retning',

'exif-ycbcrpositioning-1' => 'Sentrert',
'exif-ycbcrpositioning-2' => 'Samanfallande',

'exif-dc-contributor' => 'Bidragsytarar',
'exif-dc-coverage' => 'Rom- eller tidssutstrekning til medium',
'exif-dc-date' => 'Dato(ar)',
'exif-dc-publisher' => 'Utgjevar',
'exif-dc-relation' => 'Skylde medium',
'exif-dc-rights' => 'Rettar',
'exif-dc-source' => 'Mediakilde',
'exif-dc-type' => 'Mediatype',

'exif-rating-rejected' => 'Avvist',

'exif-isospeedratings-overflow' => 'Større enn 65535',

'exif-iimcategory-ace' => 'Kunst, kultur og underhaldning',
'exif-iimcategory-clj' => 'Kriminalitet og jura',
'exif-iimcategory-dis' => 'Katastrofar og ulukker',
'exif-iimcategory-fin' => 'Økonomi og næringsliv',
'exif-iimcategory-edu' => 'Utdanning',
'exif-iimcategory-evn' => 'Miljø',
'exif-iimcategory-hth' => 'Helse',
'exif-iimcategory-hum' => 'Menneskeleg interesse',
'exif-iimcategory-lab' => 'Arbeidskraft',
'exif-iimcategory-lif' => 'Livsstil og fritid',
'exif-iimcategory-pol' => 'Politikk',
'exif-iimcategory-rel' => 'Religion og livssyn',
'exif-iimcategory-sci' => 'Vitskap og teknologi',
'exif-iimcategory-soi' => 'Sosiale problem',
'exif-iimcategory-spo' => 'Sport',
'exif-iimcategory-war' => 'Krig, konflikt og uro',
'exif-iimcategory-wea' => 'Vær',

'exif-urgency-normal' => 'Normal ($1)',
'exif-urgency-low' => 'Låg ($1)',
'exif-urgency-high' => 'Høg ($1)',
'exif-urgency-other' => 'Brukardefinert prioritet ($1)',

# External editor support
'edit-externally' => 'Endre denne fila med eit eksternt program',
'edit-externally-help' => '(Sjå [//www.mediawiki.org/wiki/Manual:External_editors oppsettsinstruksjonane] for meir informasjon)',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'alle',
'namespacesall' => 'alle',
'monthsall' => 'alle',
'limitall' => 'alle',

# E-mail address confirmation
'confirmemail' => 'Stadfest e-postadresse',
'confirmemail_noemail' => 'Du har ikkje gjeve ei gyldig e-postadresse i [[Special:Preferences|innstillingane dine]].',
'confirmemail_text' => '{{SITENAME}} krev at du stadfester e-postadressa di
før du får brukt funksjonar knytt til e-post. Klikk på knappen under for å sende ei stadfestingsmelding
til adressa di. E-posten kjem med ei lenkje som har ein kode; opne
lenkja i nettlesaren din for å stadfeste at e-postadressa di er gyldig.',
'confirmemail_pending' => 'Ein stadfestingskode har alt vorte send til deg på e-post;
gjer vel å vente nokre minutt før du ber om ny kode om du nett har oppretta kontoen din.',
'confirmemail_send' => 'Send stadfestingsmelding',
'confirmemail_sent' => 'Stadfestingsmelding er sendt.',
'confirmemail_oncreate' => 'Ein stadfestingskode er no send til e-postadressa di.
Koden trengst ikkje for å få logga seg inn, men er naudsynd om ein skal aktivere e-postbaserte tenester på denne wikien.',
'confirmemail_sendfailed' => '{{SITENAME}} klarte ikkje å sende stadfestingsmelding.
Sjekk e-postadressa for ugyldige teikn.

E-postsendaren gav denne meldinga: $1',
'confirmemail_invalid' => 'Feil stadfestingskode. Koden er kanskje for forelda.',
'confirmemail_needlogin' => 'Du må $1 for å stadfeste e-postadressa di.',
'confirmemail_success' => 'E-postadressa di er stadfest. Du kan no logge inn og kose deg med {{SITENAME}}.',
'confirmemail_loggedin' => 'E-postadressa di er stadfest.',
'confirmemail_error' => 'Noko gjekk gale når stadfestinga di skulle lagrast.',
'confirmemail_subject' => 'Stadfesting av e-postadresse frå {{SITENAME}}',
'confirmemail_body' => 'Nokon, truleg du, frå IP-adressa $1, har registrert kontoen «$2» med di e-postadresse på {{SITENAME}}.

For å stadfeste at denne kontoen faktisk høyrer til deg og for å slå på
funksjonar tilknytt e-post på {{SITENAME}} må du opne denne lenkja i nettlesaren din:

$3

Dersom dette *ikkje* er deg, følg denne lenkja for avbryte stadfestinga av e-postadressa:

$5

Denne stadfestingskoden vert forelda $4.',
'confirmemail_body_changed' => 'Nokon, truleg deg, frå IP-adressa $1, har endra e-postadressa til kontoen «$2» på {{SITENAME}} til denne e-postadressa.

For å stadfesta at denne kontoen faktisk høyrer til deg, og for å slå på
funksjonar knytte til e-post på {{SITENAME}}, opna denne lenkja i nettlesaren din:

$3

Om brukarkontoen *ikkje* høyrer til deg, fylg denne lenkja for å bryta av stadfestinga av e-postadressa:

$5

Denne stadfestingskoden vert forelda $4.',
'confirmemail_body_set' => 'Nokon, truleg deg, frå IP-adressa $1, har sett e-postadressa til kontoen «$2» på {{SITENAME}} til denne e-postadressa.

For å stadfesta at denne kontoen faktisk høyrer til deg, og for å slå på
funksjonar knytte til e-post på {{SITENAME}}, opna denne lenkja i nettlesaren din:

$3

Om brukarkontoen *ikkje* høyrer til deg, fylg denne lenkja for å bryta av stadfestinga av e-postadressa:

$5

Denne stadfestingskoden vert forelda $4.',
'confirmemail_invalidated' => 'Stadfestinga av e-postadresse er avbrote',
'invalidateemail' => 'Avbryt stadfestinga av e-postadressa',

# Scary transclusion
'scarytranscludedisabled' => '[Interwiki-tilkopling er slått av]',
'scarytranscludefailed' => '[Henting av mal for $1 gjekk ikkje]',
'scarytranscludetoolong' => '[URL-en er for lang]',

# Delete conflict
'deletedwhileediting' => "'''Åtvaring:''' Denne sida har vorte sletta etter du starta å endre henne!",
'confirmrecreate' => "Brukaren «[[User:$1|$1]]» ([[User talk:$1|brukardiskusjon]]) sletta denne sida medan du endra henne, og gav denne grunnen: ''$2''

Du må stadfeste at du verkeleg vil nyopprette denne sida.",
'confirmrecreate-noreason' => 'Brukaren [[User:$1|$1]] ([[User talk:$1|diskusjon]]) sletta sida etter at du byrja å endra henne. Stadfest at du verkeleg ynskjer å oppretta sida på nytt.',
'recreate' => 'Attopprett',

# action=purge
'confirm_purge_button' => 'OK',
'confirm-purge-top' => 'Vil du slette tenarane sin mellomlagra versjon av denne sida?',
'confirm-purge-bottom' => 'Reinsing av ei side slettar mellomlageret og tvinger fram den nyaste versjonen.',

# action=watch/unwatch
'confirm-watch-button' => 'OK',
'confirm-watch-top' => 'Legg denne sida til i overvakingslista di?',
'confirm-unwatch-button' => 'OK',
'confirm-unwatch-top' => 'Fjern denne sida frå overvakingslista di?',

# Multipage image navigation
'imgmultipageprev' => '← førre sida',
'imgmultipagenext' => 'neste side →',
'imgmultigo' => 'Gå!',
'imgmultigoto' => 'Gå til sida $1',

# Table pager
'ascending_abbrev' => 'stigande',
'descending_abbrev' => 'synkande',
'table_pager_next' => 'Neste side',
'table_pager_prev' => 'Førre sida',
'table_pager_first' => 'Fyrste sida',
'table_pager_last' => 'Siste sida',
'table_pager_limit' => 'Vis $1 element per side',
'table_pager_limit_label' => 'Element per side:',
'table_pager_limit_submit' => 'Gå',
'table_pager_empty' => 'Ingen resultat',

# Auto-summaries
'autosumm-blank' => 'Tømde sida',
'autosumm-replace' => 'Erstattar innhaldet på sida med «$1»',
'autoredircomment' => 'Omdirigerer til [[$1]]',
'autosumm-new' => 'Oppretta sida med «$1»',

# Live preview
'livepreview-loading' => 'Lastar inn&nbsp;…',
'livepreview-ready' => 'Lastar inn… Ferdig!',
'livepreview-failed' => 'Levande førehandsvising var mislykka. Prøv vanleg førehandsvising.',
'livepreview-error' => 'Tilkoplinga var mislykka: $1 «$2». Prøv vanleg førehandsvising.',

# Friendlier slave lag warnings
'lag-warn-normal' => 'Endringar som er nyare enn {{PLURAL:$1|sekund|sekund}} er ikkje viste på denne lista.',
'lag-warn-high' => 'På grunn av stor databaseforseinking, er ikkje endringar som er nyare enn {{PLURAL:$1|sekund|sekund}} viste på denne lista.',

# Watchlist editor
'watchlistedit-numitems' => 'Overvakingslista di inneheld {{PLURAL:$1|éi side|$1 sider}} (diskusjonssider ikkje medrekna).',
'watchlistedit-noitems' => 'Overvakingslista di er tom.',
'watchlistedit-normal-title' => 'Endre overvakingslista',
'watchlistedit-normal-legend' => 'Fjern sider frå overvakingslista',
'watchlistedit-normal-explain' => 'Sidene på overvakingslista di er viste nedanfor.
For å fjerne ei side, kryss av boksen ved sida av sida du vil fjerne og klikk på «{{int:Watchlistedit-normal-submit}}».
Du kan òg [[Special:EditWatchlist/raw|endre overvakingslista i råformat]].',
'watchlistedit-normal-submit' => 'Fjern sider',
'watchlistedit-normal-done' => '{{PLURAL:$1|Éi side|$1 sider}} vart fjerna frå overvakingslista di:',
'watchlistedit-raw-title' => 'Endre på overvakingslista i råformat',
'watchlistedit-raw-legend' => 'Endre på overvakingslista i råformat',
'watchlistedit-raw-explain' => 'Sidene på overvakingslista di er viste nedanfor, og lista kan endrast ved å legge til eller fjerne sider frå lista;
ei side per line.
Når du er ferdig, klikk «{{int:Watchlistedit-raw-submit}}».
Du kan òg [[Special:EditWatchlist|nytte standardverktøyet]].',
'watchlistedit-raw-titles' => 'Sider:',
'watchlistedit-raw-submit' => 'Oppdater overvakingslista',
'watchlistedit-raw-done' => 'Overvakingslista er oppdatert.',
'watchlistedit-raw-added' => '{{PLURAL:$1|Éi side vart lagt til|$1 sider vart lagde til}}:',
'watchlistedit-raw-removed' => '{{PLURAL:$1|Éi side|$1 sider}} vart fjerna:',

# Watchlist editing tools
'watchlisttools-view' => 'Vis relevante endringar',
'watchlisttools-edit' => 'Vis og endre overvakingslista',
'watchlisttools-raw' => 'Endre på overvakingslista i råformat',

# Signatures
'signature' => '[[{{ns:user}}:$1|$2]] ([[{{ns:user_talk}}:$1|diskusjon]])',

# Core parser functions
'unknown_extension_tag' => 'Ukjend tilleggsmerking «$1»',
'duplicate-defaultsort' => 'Åtvaring: Standarsorteringa «$2» tar over for den tidlegare sorteringa «$1».',

# Special:Version
'version' => 'Versjon',
'version-extensions' => 'Installerte utvidingar',
'version-specialpages' => 'Spesialsider',
'version-parserhooks' => 'Parsertillegg',
'version-variables' => 'Variablar',
'version-antispam' => 'Hindring av spam',
'version-skins' => 'Draktar',
'version-other' => 'Anna',
'version-mediahandlers' => 'Mediahandsamarar',
'version-hooks' => 'Tilleggsuttrykk',
'version-extension-functions' => 'Utvidingsfunksjonar',
'version-parser-extensiontags' => 'Parserutvidingsmerke',
'version-parser-function-hooks' => 'Parserfunksjonstillegg',
'version-hook-name' => 'Namn på tillegg',
'version-hook-subscribedby' => 'Brukt av',
'version-version' => '(versjon $1)',
'version-license' => 'Lisens',
'version-poweredby-credits' => "Denne wikien er driven av '''[//www.mediawiki.org/ MediaWiki]''', copyright © 2001-$1 $2.",
'version-poweredby-others' => 'andre',
'version-license-info' => 'MediaWiki er fri programvare; du kan redistribuera det og/eller modifisera det under krava i GNU General Public License som publisert av Free Software Foundation; anten versjon 2 av lisensen, eller (om du ynskjer det) ein kvar seinare versjon.

MediaWiki er distribuert i håp om at det vil vera nyttig, men UTAN NOKON GARANTI; ikkje eingong ein implisitt garanti for at det KAN SELJAST eller at det EIGNAR SEG TIL EIT VISST FØREMÅL. Sjå GNU General Public License for fleire detaljar.

Du skal ha motteke [{{SERVER}}{{SCRIPTPATH}}/COPYING ein kopi av GNU General Public License] saman med dette programmet; om ikkje, skriv til Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA eller [//www.gnu.org/licenses/old-licenses/gpl-2.0.html les det på nettet].',
'version-software' => 'Installert programvare',
'version-software-product' => 'Produkt',
'version-software-version' => 'Versjon',
'version-entrypoints' => 'URL-ar til inngangspunkt',
'version-entrypoints-header-entrypoint' => 'Inngangspunkt',
'version-entrypoints-header-url' => 'URL',

# Special:FilePath
'filepath' => 'Filsti',
'filepath-page' => 'Fil:',
'filepath-submit' => 'Gå',
'filepath-summary' => 'Denne spesialsida svarar med den fullstendige stigen til ei fil.
Bilete vert viste i full oppløysing, andre filtypar vert starta direkte i dei tilknytte programma sine.',

# Special:FileDuplicateSearch
'fileduplicatesearch' => 'Søk etter duplikatfiler',
'fileduplicatesearch-summary' => 'Søk etter duplikatfiler basert på hash-verdiane deira.',
'fileduplicatesearch-legend' => 'Søk etter ei duplikatfil',
'fileduplicatesearch-filename' => 'Filnamn:',
'fileduplicatesearch-submit' => 'Søk',
'fileduplicatesearch-info' => '$1 × $2 pikslar<br />Filstorleik: $3<br />MIME-type: $4',
'fileduplicatesearch-result-1' => 'Det er ingen kopiar av fila «$1».',
'fileduplicatesearch-result-n' => 'Det er {{PLURAL:$2|éin kopi|$2 kopiar}} av fila «$1».',
'fileduplicatesearch-noresults' => 'Fann inga fil med namnet «$1».',

# Special:SpecialPages
'specialpages' => 'Spesialsider',
'specialpages-note' => '----
* Vanlege spesialsider.
* <span class="mw-specialpagerestricted">Spesialsider med avgrensa tilgang.</span>',
'specialpages-group-maintenance' => 'Vedlikehaldsrapportar',
'specialpages-group-other' => 'Andre spesialsider',
'specialpages-group-login' => 'Logga inn / oppretta brukarkonto',
'specialpages-group-changes' => 'Siste endringar og loggar',
'specialpages-group-media' => 'Medierapportar og opplastingar',
'specialpages-group-users' => 'Brukarar og brukartilgangar',
'specialpages-group-highuse' => 'Mykje brukte sider',
'specialpages-group-pages' => 'Sidelister',
'specialpages-group-pagetools' => 'Sideverktøy',
'specialpages-group-wiki' => 'Data og verktøy',
'specialpages-group-redirects' => 'Omdirigerande spesialsider',
'specialpages-group-spam' => 'Spamverktøy',

# Special:BlankPage
'blankpage' => 'Tom side',
'intentionallyblankpage' => 'Denne sida er tom med vilje',

# External image whitelist
'external_image_whitelist' => ' #La denne linja vere som ho er<pre>
#Skriv fragment av regulære uttrykk (delen som går mellom //) nedanfor
#Desse vil verte sjekka mot adresser til bilete frå eksterne sider
#Dei som vert godkjend vil visast, elles vil det verte gjeve ei lenkje til bilete
#Linjer som byrjar med # vert rekna som kommentarar
#Det vert ikkje skilt mellom små og store bokstavar

#Skriv alle fragment av regulære uttrykk over denne lina. La denne linja vere som ho er</pre>',

# Special:Tags
'tags' => 'Gyldige endringsmerke',
'tag-filter' => '[[Special:Tags|Merke]]filter:',
'tag-filter-submit' => 'Filtrer',
'tags-title' => 'Merke',
'tags-intro' => 'Denne sida listar opp merka som programvara kan merkja ei endring med, og kva desse tyder.',
'tags-tag' => 'Merkenamn',
'tags-display-header' => 'Utsjånad på endringslister',
'tags-description-header' => 'Fullstendig skildring av tyding',
'tags-hitcount-header' => 'Merkte endringar',
'tags-edit' => 'endra',
'tags-hitcount' => '{{PLURAL:$1|éi endring|$1 endringar}}',

# Special:ComparePages
'comparepages' => 'Samanlikna sider',
'compare-selector' => 'Samanlikn sideversjonar',
'compare-page1' => 'Side 1',
'compare-page2' => 'Side 2',
'compare-rev1' => 'Versjon 1',
'compare-rev2' => 'Versjon 2',
'compare-submit' => 'Samanlikna',
'compare-invalid-title' => 'Tittelen du oppgav er ugild.',
'compare-title-not-exists' => 'Tittelen du oppgav finst ikkje.',
'compare-revision-not-exists' => 'Versjonen du oppgav finst ikkje.',

# Database error messages
'dberr-header' => 'Denne wikien har eit problem',
'dberr-problems' => 'Nettstaden har tekniske problem.',
'dberr-again' => 'Venta nokre minutt og last sida inn på nytt.',
'dberr-info' => '(Kan ikkje kontakta databasetenaren: $1)',
'dberr-usegoogle' => 'Du kan søkja gjennom Google i mellomtida.',
'dberr-outofdate' => 'Merk at versjonane deira av innhaldet vårt kan vera forelda.',
'dberr-cachederror' => 'Fylgjande er ein mellomlagra kopi av den etterspurde sida, og er, kan henda, ikkje den siste versjonen av ho.',

# HTML forms
'htmlform-invalid-input' => 'Det finst problem med innskrivinga di',
'htmlform-select-badoption' => 'Verdien du valde er ikkje eit gyldig alternativ.',
'htmlform-int-invalid' => 'Verdien du valde er ikkje eit heiltal.',
'htmlform-float-invalid' => 'Verdien du valde er ikkje eit tal.',
'htmlform-int-toolow' => 'Verdien du valde er under minstetalet på $1',
'htmlform-int-toohigh' => 'Verdien du valde er over høgste moglege tal $1',
'htmlform-required' => 'Denne verdien er påkravd',
'htmlform-submit' => 'Lagre',
'htmlform-reset' => 'Gjer om endringar',
'htmlform-selectorother-other' => 'Andre',

# SQLite database support
'sqlite-has-fts' => '$1 med støtte for fulltekstsøk',
'sqlite-no-fts' => '$1 utan støtte for fulltekstsøk',

# New logging system
'logentry-delete-delete' => '$1 sletta sida $3',
'logentry-delete-restore' => '$1 attoppretta sida $3',
'logentry-delete-event' => '$1 endra synlegdomen av {{PLURAL:$5|éi loggoppføring|$5 loggoppføringar}} på $3: $4',
'logentry-delete-revision' => '$1 endra synlegdomen til {{PLURAL:$5|éin versjon|$5 versjonar}} på sida $3: $4',
'logentry-delete-event-legacy' => '$1 endra synlegdomen til loggoppføringar på $3',
'logentry-delete-revision-legacy' => '$1 endra synlegdomen til versjonar på sida $3',
'logentry-suppress-delete' => '$1 gøymde sida $3',
'logentry-suppress-event' => '$1 endra i løyndom synlegdomen til {{PLURAL:$5|éi logghending|$5 logghendingar}} på $3: $4',
'logentry-suppress-revision' => '$1 endra i løyndom synlegdomen til {{PLURAL:$5|éin versjon|$5 versjonar}} på sida $3: $4',
'logentry-suppress-event-legacy' => '$1 endra i løyndom synlegdomen til logghendingar på $3',
'logentry-suppress-revision-legacy' => '$1 endra i løyndom synlegdomen til versjonar på sida $3',
'revdelete-content-hid' => 'innhald gøymt',
'revdelete-summary-hid' => 'endringsamandrag gøymt',
'revdelete-uname-hid' => 'brukarnamn gøymt',
'revdelete-content-unhid' => 'innhald gjort synleg',
'revdelete-summary-unhid' => 'endringssamandrag gjort synleg',
'revdelete-uname-unhid' => 'brukarnamn gjort synleg',
'revdelete-restricted' => 'la til avgrensingar for administratorar',
'revdelete-unrestricted' => 'fjerna avgrensingar for administratorar',
'logentry-move-move' => '$1 flytte sida $3 til $4',
'logentry-move-move-noredirect' => '$1 flytte sida $3 til $4 utan å lata etter ei omdirigering',
'logentry-move-move_redir' => '$1 flytte sida $3 til $4 over ei omdirigering',
'logentry-move-move_redir-noredirect' => '$1 flytte sida $3 til $4 over ei omdirigering utan å lata etter ei omdirigering',
'logentry-patrol-patrol' => '$1 merkte versjon $4 av sida $3 som patruljert',
'logentry-patrol-patrol-auto' => '$1 merkte automatisk versjon $4 av sida $3 som patruljert',
'logentry-newusers-newusers' => 'Brukarkontoen $1 vart oppretta',
'logentry-newusers-create' => 'Brukarkontoen $1 vart oppretta',
'logentry-newusers-create2' => 'Brukarkontoen $3 vart oppretta av $1',
'logentry-newusers-autocreate' => 'Kontoen $1 vart oppretta av seg sjølv',
'newuserlog-byemail' => 'passordet er sendt på e-post',

# Feedback
'feedback-bugornote' => 'Er du klar til å skildra ein teknisk vanske i detalj, gjer vel å [$1 rapportera inn ein feil].
Om ikkje kan du nytta det enkle skjemaet under. Merknaden din vert lagd til på sida «[$3 $2]», i lag med brukarnamnet ditt og kva for nettlesar du nyttar.',
'feedback-subject' => 'Emne:',
'feedback-message' => 'Melding:',
'feedback-cancel' => 'Bryt av',
'feedback-submit' => 'Send attendemelding',
'feedback-adding' => 'Legg til attendemeldinga til sida...',
'feedback-error1' => 'Feil: Ukjent resultat frå API',
'feedback-error2' => 'Feil: Brigdinga gjekk ikkje',
'feedback-error3' => 'Feil: Saknar svar frå API',
'feedback-thanks' => 'Takk! Attendemeldinga di er lagd inn på sida «[$2 $1]».',
'feedback-close' => 'Gjort',
'feedback-bugcheck' => 'Bra! No lyt du berre sjå etter om han er ein av dei [$1 kjende feila].',
'feedback-bugnew' => 'Eg såg etter. Rapporter ein ny feil',

# Search suggestions
'searchsuggest-search' => 'Søk',
'searchsuggest-containing' => 'som inneheld …',

# API errors
'api-error-badaccess-groups' => 'Du har ikkje løyve til å lasta opp filer til wikien.',
'api-error-badtoken' => 'Intern feil: ugild token.',
'api-error-copyuploaddisabled' => 'Opplasting etter URL er avslege på tenaren.',
'api-error-duplicate' => 'Det finst {{PLURAL:$1|[$2 ei anna fil]|[$2 andre filer]}} på nettstaden med same innhaldet.',
'api-error-duplicate-archive' => 'Det fanst {{PLURAL:$1|[$2 ei anna fil]|[$2 andre filer]}} på nettstaden med det same innhaldet, men {{PLURAL:$1|ho|dei}} vart sletta.',
'api-error-duplicate-archive-popup-title' => '{{PLURAL:$1|Tvifelt fil|Tvifelte filer}} som alt er sletta',
'api-error-duplicate-popup-title' => '{{PLURAL:$1|Tvifelt fil|Tvifelte filer}}',
'api-error-empty-file' => 'Fila du sende var tom.',
'api-error-emptypage' => 'Det er ikkje tillate å oppretta nye tomme sider.',
'api-error-fetchfileerror' => 'Intern feil: Noko gjekk gale då fila vart henta.',
'api-error-fileexists-forbidden' => 'Ei fil med namnet «$1» finst alt, og kan ikkje skrivast over.',
'api-error-fileexists-shared-forbidden' => 'Ei fil med namnet «$1» finst alt i den delte filsamlinga, og kan ikkje skrivast over.',
'api-error-file-too-large' => 'Fila du sende var for stor.',
'api-error-filename-tooshort' => 'Filnamnet er for stutt.',
'api-error-filetype-banned' => 'Denne filtypen er ikkje tillaten.',
'api-error-filetype-banned-type' => '$1 er ikkje {{PLURAL:$4|ein tillaten filtype|tillatne filtypar}}. {{PLURAL:$3|Tillaten filtype|Tillatne filtypar}} er $2.',
'api-error-filetype-missing' => 'Fila saknar ei ending.',
'api-error-hookaborted' => 'Endringa du freista vart avbroten av ei utviding.',
'api-error-http' => 'Intern feil: kan ikkje kopla til tenaren.',
'api-error-illegal-filename' => 'Filnamnet er ikkje tillate.',
'api-error-internal-error' => 'Intern feil: Noko gjekk gale med handsaminga av opplastinga di til wikien.',
'api-error-invalid-file-key' => 'Intern feil: Fila vart ikkje funnen i mellombels lagringsplass.',
'api-error-missingparam' => 'Intern feil: det saknar parametrar i førespurnaden.',
'api-error-missingresult' => 'Intern feil: kunne ikkje avgjera om koperinga var vellukka.',
'api-error-mustbeloggedin' => 'Du lyt vera innlogga for å lasta opp filer.',
'api-error-mustbeposted' => 'Intern feil: førespurnad krev HTTP POST.',
'api-error-noimageinfo' => 'Opplastinga gjekk greitt, men tenaren gav oss ikkje noko informasjon om fila.',
'api-error-nomodule' => 'Intern feil: ingen opplastingsmodul er vald.',
'api-error-ok-but-empty' => 'Intern feil: ikkje noko svar frå tenaren.',
'api-error-overwrite' => 'Det er ikkje tillate å skriva over filer som alt finst.',
'api-error-stashfailed' => 'Intern feil: tenaren greidde ikkje å lagra ei mellombels fil.',
'api-error-timeout' => 'Tenaren svara ikkje innan tida svar var venta.',
'api-error-unclassified' => 'Det oppstod ein ukjend feil.',
'api-error-unknown-code' => 'Ukjend feil: «$1»',
'api-error-unknown-error' => 'Intern feil: Noko gjekk gale då fila di vart freista lasta opp.',
'api-error-unknown-warning' => 'Ukjend åtvaring: $1',
'api-error-unknownerror' => 'Ukjend feil: «$1».',
'api-error-uploaddisabled' => 'Det er ikkje høve til å lasta opp filer til wikien.',
'api-error-verification-error' => 'Fila kan vera øydelagd eller ha rang filending.',

# Durations
'duration-seconds' => '$1 {{PLURAL:$1|sekund|sekund}}',
'duration-minutes' => '$1 {{PLURAL:$1|minutt|minutt}}',
'duration-hours' => '$1 {{PLURAL:$1|time|timar}}',
'duration-days' => '$1 {{PLURAL:$1|dag|dagar}}',
'duration-weeks' => '$1 {{PLURAL:$1|veke|veker}}',
'duration-years' => '$1 {{PLURAL:$1|år|år}}',
'duration-decades' => '$1 {{PLURAL:$1|tiår|tiår}}',
'duration-centuries' => '$1 {{PLURAL:$1|hundreår|hundreår}}',
'duration-millennia' => '$1 {{PLURAL:$1|tusenår|tusenår}}',

);
