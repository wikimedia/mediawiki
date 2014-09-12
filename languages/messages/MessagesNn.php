<?php
/** Norwegian Nynorsk (norsk nynorsk)
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
 * @author Jeblad
 * @author Jon Harald Søby
 * @author Jorunn
 * @author Kaganer
 * @author Marinsb
 * @author Najami
 * @author Nghtwlkr
 * @author Njardarlogar
 * @author Olve Utne
 * @author Pcoombe
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
	'img_link'                  => array( '1', 'lenkje=$1', 'lenke=$1', 'link=$1' ),
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
	'defaultsort'               => array( '1', 'STANDARDSORTERING', 'SORTERINGSNYKEL', 'SORTERINGSNØKKEL', 'DEFAULTSORT:', 'DEFAULTSORTKEY:', 'DEFAULTCATEGORYSORT:' ),
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
	'Activeusers'               => array( 'Verksame_brukarar', 'Aktive_brukarar' ),
	'Allmessages'               => array( 'Alle_systemmeldingar' ),
	'AllMyUploads'              => array( 'Alle_opplastingane_mine' ),
	'Allpages'                  => array( 'Alle_sider' ),
	'Ancientpages'              => array( 'Gamle_sider' ),
	'Badtitle'                  => array( 'Dårleg_tittel' ),
	'Blankpage'                 => array( 'Tom_side' ),
	'Block'                     => array( 'Blokker' ),
	'Booksources'               => array( 'Bokkjelder' ),
	'BrokenRedirects'           => array( 'Blindvegsomdirigeringar' ),
	'Categories'                => array( 'Kategoriar' ),
	'ChangeEmail'               => array( 'Endra_e-post', 'Endre_e-post' ),
	'ChangePassword'            => array( 'Nullstill_passord' ),
	'ComparePages'              => array( 'Samanlikna_sider', 'Samanlikne_sider', 'Samanlikn_sider' ),
	'Confirmemail'              => array( 'Stadfest_e-postadresse' ),
	'Contributions'             => array( 'Bidrag' ),
	'CreateAccount'             => array( 'Opprett_konto' ),
	'Deadendpages'              => array( 'Blindvegsider' ),
	'DeletedContributions'      => array( 'Sletta_brukarbidrag' ),
	'DoubleRedirects'           => array( 'Doble_omdirigeringar' ),
	'EditWatchlist'             => array( 'Endra_overvakingsliste', 'Endre_overvakingsliste' ),
	'Emailuser'                 => array( 'E-post' ),
	'Export'                    => array( 'Eksport' ),
	'Fewestrevisions'           => array( 'Færrast_endringar' ),
	'FileDuplicateSearch'       => array( 'Filduplikatsøk' ),
	'Filepath'                  => array( 'Filstig', 'Filsti' ),
	'Import'                    => array( 'Importer' ),
	'Invalidateemail'           => array( 'Gjer_e-post_ugyldig' ),
	'JavaScriptTest'            => array( 'Utrøyning_av_JavaScript', 'JavaScript-test' ),
	'BlockList'                 => array( 'Blokkeringsliste' ),
	'LinkSearch'                => array( 'Lenkjesøk', 'Lenkesøk' ),
	'Listadmins'                => array( 'Administratorliste', 'Administratorar' ),
	'Listbots'                  => array( 'Bottliste', 'Bottar', 'Robotliste', 'Robotar' ),
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
	'Mostinterwikis'            => array( 'Flest_interwikilenkjer', 'Mest_interwiki' ),
	'Mostlinked'                => array( 'Mest_lenka_sider', 'Mest_lenkja_sider' ),
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
	'PermanentLink'             => array( 'Permanent_lenkje', 'Permanent_lenke' ),
	'Popularpages'              => array( 'Populære_sider' ),
	'Preferences'               => array( 'Innstillingar' ),
	'Prefixindex'               => array( 'Prefiksindeks' ),
	'Protectedpages'            => array( 'Verna_sider' ),
	'Protectedtitles'           => array( 'Verna_sidenamn' ),
	'Randompage'                => array( 'Tilfeldig_side' ),
	'Randomredirect'            => array( 'Tilfeldig_omdirigering' ),
	'Recentchanges'             => array( 'Siste_endringar', 'Siste_endringane' ),
	'Recentchangeslinked'       => array( 'Relaterte_endringar' ),
	'Revisiondelete'            => array( 'Versjonssletting' ),
	'Search'                    => array( 'Søk' ),
	'Shortpages'                => array( 'Korte_sider', 'Stutte_sider' ),
	'Specialpages'              => array( 'Spesialsider', 'Særsider' ),
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

