<?php
/** Norwegian Nynorsk (norsk nynorsk)
 *
 * @file
 * @ingroup Languages
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
 * @license GFDL-1.3-or-later
 * @license GPL-2.0-or-later
 *
 * @see https://meta.wikimedia.org/w/index.php?title=LanguageNn.php&action=history
 * @see https://nn.wikipedia.org/w/index.php?title=Brukar:Dittaeva/LanguageNn.php&action=history
 */

$fallback = 'no, nb';

$datePreferences = [
	'default',
	'dmyt',
	'short dmyt',
	'tdmy',
	'short dmyt',
	'ISO 8601',
];

$datePreferenceMigrationMap = [
	'default',
	'dmyt',
	'short dmyt',
	'tdmy',
	'short tdmy',
];

$defaultDateFormat = 'dmyt';

$dateFormats = [
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
];

$bookstoreList = [
	'Nasjonalbiblioteket' => 'https://www.nb.no/search?q=$1',
	'Oria' => 'https://bibsys-almaprimo.hosted.exlibrisgroup.com/primo-explore/search?query=isbn,contains,$1&search_scope=default_scope&vid=BIBSYS',
	'Bokkilden' => 'https://www.bokkilden.no/enkeltSok.do?enkeltsok=$1',
	'Akademika' => 'https://www.akademika.no/search?query=$1',
	'Amazon.co.uk' => 'https://www.amazon.co.uk/s?k=$1',
	'Amazon.de'    => 'https://www.amazon.de/s?k=$1',
	'Amazon.com'   => 'https://www.amazon.com/exec/obidos/ISBN=$1'
];

/** @phpcs-require-sorted-array */
$magicWords = [
	'articlepath'               => [ '0', 'ARTIKKELSTIG', 'ARTICLEPATH' ],
	'basepagename'              => [ '1', 'HOVUDSIDENAMN', 'BASEPAGENAME' ],
	'contentlanguage'           => [ '1', 'INNHALDSSPRÅK', 'CONTENTLANGUAGE', 'CONTENTLANG' ],
	'currentday'                => [ '1', 'DAGNO', 'DAGNÅ', 'CURRENTDAY' ],
	'currentday2'               => [ '1', 'DAGNO2', 'DAGNÅ2', 'CURRENTDAY2' ],
	'currentdayname'            => [ '1', 'DAGNONAMN', 'DAGNÅNAVN', 'CURRENTDAYNAME' ],
	'currentdow'                => [ '1', 'VEKEDAGNRNO', 'UKEDAGNRNÅ', 'CURRENTDOW' ],
	'currenthour'               => [ '1', 'TIMENO', 'CURRENTHOUR' ],
	'currentmonth'              => [ '1', 'MÅNADNO', 'MÅNEDNÅ', 'CURRENTMONTH', 'CURRENTMONTH2' ],
	'currentmonthabbrev'        => [ '1', 'MÅNADNOKORT', 'MÅNEDNÅKORT', 'CURRENTMONTHABBREV' ],
	'currentmonthname'          => [ '1', 'MÅNADNONAMN', 'MÅNEDNÅNAVN', 'CURRENTMONTHNAME' ],
	'currenttime'               => [ '1', 'TIDNO', 'TIDNÅ', 'CURRENTTIME' ],
	'currentversion'            => [ '1', 'VERSJONNO', 'CURRENTVERSION' ],
	'currentweek'               => [ '1', 'VEKENRNO', 'UKENRNÅ', 'CURRENTWEEK' ],
	'currentyear'               => [ '1', 'ÅRNO', 'ÅRNÅ', 'CURRENTYEAR' ],
	'defaultsort'               => [ '1', 'STANDARDSORTERING', 'SORTERINGSNYKEL', 'SORTERINGSNØKKEL', 'DEFAULTSORT:', 'DEFAULTSORTKEY:', 'DEFAULTCATEGORYSORT:' ],
	'displaytitle'              => [ '1', 'VISTITTEL', 'DISPLAYTITLE' ],
	'filepath'                  => [ '0', 'FILSTIG', 'FILEPATH:' ],
	'forcetoc'                  => [ '0', '__ALLTIDINNHALDSLISTE__', '__ALLTIDINNHOLDSLISTE__', '__FORCETOC__' ],
	'formatdate'                => [ '0', 'datoformat', 'formatdate', 'dateformat' ],
	'formatnum'                 => [ '0', 'FORMATTAL', 'FORMATNUM' ],
	'fullpagename'              => [ '1', 'FULLTSIDENAMN', 'FULLPAGENAME' ],
	'gender'                    => [ '0', 'KJØNN:', 'GENDER:' ],
	'grammar'                   => [ '0', 'GRAMMATIKK:', 'GRAMMAR:' ],
	'hiddencat'                 => [ '1', '__GØYMDKAT__', '__LØYNDKAT__', '__HIDDENCAT__' ],
	'img_center'                => [ '1', 'sentrum', 'center', 'centre' ],
	'img_framed'                => [ '1', 'ramme', 'ramma', 'frame', 'framed', 'enframed' ],
	'img_frameless'             => [ '1', 'rammelaus', 'frameless' ],
	'img_lang'                  => [ '1', 'språk=$1', 'lang=$1' ],
	'img_left'                  => [ '1', 'venstre', 'left' ],
	'img_link'                  => [ '1', 'lenkje=$1', 'lenke=$1', 'link=$1' ],
	'img_manualthumb'           => [ '1', 'mini=$1', 'miniatyr=$1', 'thumbnail=$1', 'thumb=$1' ],
	'img_none'                  => [ '1', 'ingen', 'none' ],
	'img_page'                  => [ '1', 'side=$1', 'side_$1', 'page=$1', 'page $1' ],
	'img_right'                 => [ '1', 'høgre', 'høyre', 'right' ],
	'img_thumbnail'             => [ '1', 'mini', 'miniatyr', 'thumb', 'thumbnail' ],
	'img_width'                 => [ '1', '$1pk', '$1px' ],
	'language'                  => [ '0', '#SPRÅK', '#LANGUAGE' ],
	'lc'                        => [ '0', 'SMÅ:', 'LC:' ],
	'lcfirst'                   => [ '0', 'LFYRST:', 'LFØRST:', 'LCFIRST:' ],
	'localurl'                  => [ '0', 'LOKALLENKJE:', 'LOKALLENKE:', 'LOCALURL:' ],
	'localurle'                 => [ '0', 'LOKALLENKJEE:', 'LOKALLENKEE:', 'LOCALURLE:' ],
	'localweek'                 => [ '1', 'LOKALVEKE', 'LOCALWEEK' ],
	'msg'                       => [ '0', 'MLD:', 'MSG:' ],
	'msgnw'                     => [ '0', 'IKWIKMELD:', 'MSGNW:' ],
	'namespace'                 => [ '1', 'NAMNEROM', 'NAVNEROM', 'NAMESPACE' ],
	'noeditsection'             => [ '0', '__INGABOLKENDRING__', '__INGABOLKREDIGERING__', '__INGENDELENDRING__', '__NOEDITSECTION__' ],
	'nogallery'                 => [ '0', '__INKJEGALLERI__', '__NOGALLERY__' ],
	'notoc'                     => [ '0', '__INGAINNHALDSLISTE__', '__INGENINNHOLDSLISTE__', '__NOTOC__' ],
	'ns'                        => [ '0', 'NR:', 'NS:' ],
	'numberofactiveusers'       => [ '1', 'AKTIVEBRUKARAR', 'NUMBEROFACTIVEUSERS' ],
	'numberofadmins'            => [ '1', 'ADMINTAL', 'ADMINISTRATORTAL', 'NUMBEROFADMINS' ],
	'numberofarticles'          => [ '1', 'INNHALDSSIDETAL', 'INNHOLDSSIDETALL', 'NUMBEROFARTICLES' ],
	'numberofedits'             => [ '1', 'ENDRINGSTAL', 'NUMBEROFEDITS' ],
	'numberoffiles'             => [ '1', 'FILTAL', 'NUMBEROFFILES' ],
	'numberofpages'             => [ '1', 'SIDETAL', 'NUMBEROFPAGES' ],
	'numberofusers'             => [ '1', 'BRUKARTAL', 'NUMBEROFUSERS' ],
	'pageid'                    => [ '0', 'SIDEID', 'PAGEID' ],
	'pagename'                  => [ '1', 'SIDENAMN', 'SIDENAVN', 'PAGENAME' ],
	'pagenamee'                 => [ '1', 'SIDENAMNE', 'SIDENAVNE', 'PAGENAMEE' ],
	'pagesincategory'           => [ '1', 'SIDERIKAT', 'SIDERIKATEGORI', 'PAGESINCATEGORY', 'PAGESINCAT' ],
	'pagesincategory_all'       => [ '0', 'alle', 'all' ],
	'pagesincategory_files'     => [ '0', 'filer', 'files' ],
	'pagesincategory_pages'     => [ '0', 'sider', 'pages' ],
	'pagesincategory_subcats'   => [ '0', 'underkategoriar', 'subcats' ],
	'pagesinnamespace'          => [ '1', 'SIDERINAMNEROM', 'PAGESINNAMESPACE:', 'PAGESINNS:' ],
	'pagesize'                  => [ '1', 'SIDESTORLEIK', 'PAGESIZE' ],
	'plural'                    => [ '0', 'FLEIRTAL:', 'PLURAL:' ],
	'protectionlevel'           => [ '1', 'VERNENIVÅ', 'PROTECTIONLEVEL' ],
	'redirect'                  => [ '0', '#OMDIRIGER', '#omdiriger', '#REDIRECT' ],
	'revisionday'               => [ '1', 'VERSJONSDAG', 'REVISIONDAY' ],
	'revisionday2'              => [ '1', 'VERSJONSDAG2', 'REVISIONDAY2' ],
	'revisionid'                => [ '1', 'VERSJONSID', 'REVISIONID' ],
	'revisionmonth'             => [ '1', 'VERSJONSMÅNAD', 'REVISIONMONTH' ],
	'revisionmonth1'            => [ '1', 'VERSJONSMÅNAD1', 'REVISIONMONTH1' ],
	'revisiontimestamp'         => [ '1', 'VERSJONSTIDSTEMPEL', 'REVISIONTIMESTAMP' ],
	'revisionuser'              => [ '1', 'VERSJONSBRUKAR', 'REVISIONUSER' ],
	'revisionyear'              => [ '1', 'VERSJONSÅR', 'REVISIONYEAR' ],
	'safesubst'                 => [ '0', 'TRYGGLIMINN:', 'SAFESUBST:' ],
	'scriptpath'                => [ '0', 'SKRIPTSTIG', 'SKRIPTSTI', 'SCRIPTPATH' ],
	'server'                    => [ '0', 'TENAR', 'TJENER', 'SERVER' ],
	'servername'                => [ '0', 'TENARNAMN', 'TJENERNAVN', 'SERVERNAME' ],
	'sitename'                  => [ '1', 'NETTSTADNAMN', 'SITENAME' ],
	'special'                   => [ '0', 'spesial', 'special' ],
	'subpagename'               => [ '1', 'UNDERSIDENAMN', 'SUBPAGENAME' ],
	'subst'                     => [ '0', 'LIMINN:', 'SUBST:' ],
	'tag'                       => [ '0', 'merke', 'tag' ],
	'talkpagename'              => [ '1', 'DISKUSJONSSIDENAMN', 'TALKPAGENAME' ],
	'toc'                       => [ '0', '__INNHALDSLISTE__', '__INNHOLDSLISTE__', '__TOC__' ],
	'uc'                        => [ '0', 'STORE:', 'UC:' ],
	'ucfirst'                   => [ '0', 'SFYRST:', 'SFØRST:', 'UCFIRST:' ],
	'url_path'                  => [ '0', 'STIG', 'PATH' ],
];

$namespaceNames = [
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
];

/** @phpcs-require-sorted-array */
$specialPageAliases = [
	'Activeusers'               => [ 'Verksame_brukarar', 'Aktive_brukarar' ],
	'Allmessages'               => [ 'Alle_systemmeldingar' ],
	'AllMyUploads'              => [ 'Alle_opplastingane_mine' ],
	'Allpages'                  => [ 'Alle_sider' ],
	'Ancientpages'              => [ 'Gamle_sider' ],
	'ApiHelp'                   => [ 'API-hjelp' ],
	'ApiSandbox'                => [ 'API-sandkasse' ],
	'AutoblockList'             => [ 'Automatisk_blokkeringsliste' ],
	'Badtitle'                  => [ 'Ugyldig_tittel', 'Dårleg_tittel' ],
	'Blankpage'                 => [ 'Tom_side' ],
	'Block'                     => [ 'Blokker' ],
	'BlockList'                 => [ 'Blokkeringsliste' ],
	'Booksources'               => [ 'Bokkjelder' ],
	'BotPasswords'              => [ 'Botpassord', 'Robotpassord' ],
	'BrokenRedirects'           => [ 'Blindvegsomdirigeringar' ],
	'Categories'                => [ 'Kategoriar' ],
	'ChangeContentModel'        => [ 'Endra_innhaldsmodell' ],
	'ChangeCredentials'         => [ 'Endre_akkreditiv' ],
	'ChangeEmail'               => [ 'Endra_e-post', 'Endre_e-post' ],
	'ChangePassword'            => [ 'Nullstill_passord' ],
	'ComparePages'              => [ 'Samanlikna_sider', 'Samanlikne_sider', 'Samanlikn_sider' ],
	'Confirmemail'              => [ 'Stadfest_e-postadresse' ],
	'Contribute'                => [ 'Bidra' ],
	'Contributions'             => [ 'Bidrag' ],
	'CreateAccount'             => [ 'Opprett_konto' ],
	'Deadendpages'              => [ 'Blindvegsider' ],
	'DeletedContributions'      => [ 'Sletta_brukarbidrag' ],
	'DoubleRedirects'           => [ 'Doble_omdirigeringar' ],
	'EditPage'                  => [ 'Endra_side', 'Endre_side', 'Endr_side' ],
	'EditRecovery'              => [ 'Endringsatterreising' ],
	'EditTags'                  => [ 'Endra_merke', 'Endre_merke', 'Endr_merke' ],
	'EditWatchlist'             => [ 'Endra_overvakingsliste', 'Endre_overvakingsliste', 'Endr_overvakingsliste' ],
	'Emailuser'                 => [ 'E-post' ],
	'Export'                    => [ 'Eksport' ],
	'Fewestrevisions'           => [ 'Færrast_endringar' ],
	'FileDuplicateSearch'       => [ 'Filduplikatsøk' ],
	'Filepath'                  => [ 'Filstig', 'Filsti' ],
	'Import'                    => [ 'Importer' ],
	'Invalidateemail'           => [ 'Gjer_e-post_ugyldig' ],
	'JavaScriptTest'            => [ 'Utrøyning_av_JavaScript', 'JavaScript-test' ],
	'LinkAccounts'              => [ 'Knyt_til_kontoar', 'Knyt_til_konti' ],
	'LinkSearch'                => [ 'Lenkjesøk', 'Lenkesøk' ],
	'Listadmins'                => [ 'Administratorliste', 'Administratorar' ],
	'Listbots'                  => [ 'Bottliste', 'Bottar', 'Robotliste', 'Robotar' ],
	'ListDuplicatedFiles'       => [ 'Liste_over_duplikatfiler' ],
	'Listfiles'                 => [ 'Filliste' ],
	'Listgrants'                => [ 'Liste_over_tildelingar' ],
	'Listgrouprights'           => [ 'Grupperettar' ],
	'Listredirects'             => [ 'Omdirigeringsliste' ],
	'Listusers'                 => [ 'Brukarliste' ],
	'Lockdb'                    => [ 'Lås_database' ],
	'Log'                       => [ 'Logg', 'Loggar' ],
	'Lonelypages'               => [ 'Foreldrelause_sider' ],
	'Longpages'                 => [ 'Lange_sider' ],
	'MediaStatistics'           => [ 'Mediestatistikk' ],
	'MergeHistory'              => [ 'Flett_historikk', 'Flettehistorie' ],
	'MIMEsearch'                => [ 'MIME-søk' ],
	'Mostcategories'            => [ 'Flest_kategoriar' ],
	'Mostimages'                => [ 'Mest_brukte_filer' ],
	'Mostinterwikis'            => [ 'Flest_interwikilenkjer', 'Mest_interwiki' ],
	'Mostlinked'                => [ 'Mest_lenka_sider', 'Mest_lenkja_sider' ],
	'Mostlinkedcategories'      => [ 'Mest_brukte_kategoriar' ],
	'Mostlinkedtemplates'       => [ 'Mest_brukte_malar' ],
	'Mostrevisions'             => [ 'Flest_endringar' ],
	'Movepage'                  => [ 'Flytt_side' ],
	'Mycontributions'           => [ 'Bidraga_mine' ],
	'MyLanguage'                => [ 'Språket_mitt' ],
	'Mylog'                     => [ 'Loggen_min' ],
	'Mypage'                    => [ 'Sida_mi' ],
	'Mytalk'                    => [ 'Diskusjonssida_mi' ],
	'Myuploads'                 => [ 'Opplastingane_mine' ],
	'Newimages'                 => [ 'Nye_filer' ],
	'Newpages'                  => [ 'Nye_sider' ],
	'NewSection'                => [ 'Ny_seksjon', 'Nytt_avsnitt', 'Ny_bolk' ],
	'PageData'                  => [ 'Sidedata' ],
	'PageHistory'               => [ 'Sidehistorikk' ],
	'PageInfo'                  => [ 'Sideinfo' ],
	'PageLanguage'              => [ 'Sidespråk' ],
	'PagesWithProp'             => [ 'Sider_med_eigenskap' ],
	'PasswordPolicies'          => [ 'Passordreglar' ],
	'PasswordReset'             => [ 'Nullstill_passord' ],
	'PermanentLink'             => [ 'Permanent_lenkje', 'Permanent_lenke' ],
	'Preferences'               => [ 'Innstillingar' ],
	'Prefixindex'               => [ 'Prefiksindeks' ],
	'Protectedpages'            => [ 'Verna_sider' ],
	'Protectedtitles'           => [ 'Verna_sidenamn' ],
	'RandomInCategory'          => [ 'Tilfeldig_frå_kategori' ],
	'Randompage'                => [ 'Tilfeldig_side' ],
	'Randomredirect'            => [ 'Tilfeldig_omdirigering' ],
	'Randomrootpage'            => [ 'Tilfeldig_rotside' ],
	'Recentchanges'             => [ 'Siste_endringar', 'Siste_endringane' ],
	'Recentchangeslinked'       => [ 'Relaterte_endringar' ],
	'Redirect'                  => [ 'Omdiriger' ],
	'Renameuser'                => [ 'Døyp_om_brukar' ],
	'ResetTokens'               => [ 'Nullstill_nøklar', 'Nullstill_nyklar', 'Nullstill_lyklar' ],
	'Revisiondelete'            => [ 'Versjonssletting' ],
	'Search'                    => [ 'Søk' ],
	'Shortpages'                => [ 'Korte_sider', 'Stutte_sider' ],
	'Specialpages'              => [ 'Spesialsider', 'Særsider' ],
	'Statistics'                => [ 'Statistikk' ],
	'Tags'                      => [ 'Merke' ],
	'TrackingCategories'        => [ 'Sporingskategoriar' ],
	'Unblock'                   => [ 'Avblokker' ],
	'Uncategorizedcategories'   => [ 'Ukategoriserte_kategoriar' ],
	'Uncategorizedimages'       => [ 'Ukategoriserte_filer' ],
	'Uncategorizedpages'        => [ 'Ukategoriserte_sider' ],
	'Uncategorizedtemplates'    => [ 'Ukategoriserte_malar' ],
	'Undelete'                  => [ 'Attopprett' ],
	'UnlinkAccounts'            => [ 'Fjern_kontotilknytting' ],
	'Unlockdb'                  => [ 'Opne_database' ],
	'Unusedcategories'          => [ 'Ubrukte_kategoriar' ],
	'Unusedimages'              => [ 'Ubrukte_filer' ],
	'Unusedtemplates'           => [ 'Ubrukte_malar' ],
	'Unwatchedpages'            => [ 'Uovervaka_sider' ],
	'Upload'                    => [ 'Last_opp' ],
	'UploadStash'               => [ 'Opplastingsstash' ],
	'Userlogin'                 => [ 'Logg_inn' ],
	'Userlogout'                => [ 'Logg_ut' ],
	'Userrights'                => [ 'Brukarrettar' ],
	'Version'                   => [ 'Versjon' ],
	'Wantedcategories'          => [ 'Etterspurde_kategoriar' ],
	'Wantedfiles'               => [ 'Etterspurde_filer' ],
	'Wantedpages'               => [ 'Etterspurde_sider' ],
	'Wantedtemplates'           => [ 'Etterspurde_malar' ],
	'Watchlist'                 => [ 'Overvakingsliste' ],
	'Whatlinkshere'             => [ 'Lenkjer_hit' ],
	'Withoutinterwiki'          => [ 'Utan_interwiki' ],
];

$separatorTransformTable = [
	',' => "\u{00A0}",
	'.' => ','
];

$linkTrail = '/^([æøåáéêòóôa-z]+)(.*)$/sDu';
