<?php
/** Danish (dansk)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 */

$namespaceNames = array(
	NS_MEDIA            => 'Media',
	NS_SPECIAL          => 'Speciel',
	NS_TALK             => 'Diskussion',
	NS_USER             => 'Bruger',
	NS_USER_TALK        => 'Brugerdiskussion',
	NS_PROJECT_TALK     => '$1_diskussion',
	NS_FILE             => 'Fil',
	NS_FILE_TALK        => 'Fildiskussion',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki_diskussion',
	NS_TEMPLATE         => 'Skabelon',
	NS_TEMPLATE_TALK    => 'Skabelondiskussion',
	NS_HELP             => 'Hjælp',
	NS_HELP_TALK        => 'Hjælp_diskussion',
	NS_CATEGORY         => 'Kategori',
	NS_CATEGORY_TALK    => 'Kategoridiskussion',
);

$namespaceAliases = array(
	'$1-diskussion'        => NS_PROJECT_TALK,
	'Billede'              => NS_FILE,
	'Billeddiskussion'     => NS_FILE_TALK,
	'MediaWiki-diskussion' => NS_MEDIAWIKI_TALK,
	'Hjælp-diskussion'     => NS_HELP_TALK,
);

$specialPageAliases = array(
	'Activeusers'               => array( 'Aktive_Brugere' ),
	'Allmessages'               => array( 'MediaWiki-systemmeddelelser' ),
	'Allpages'                  => array( 'Alle_sider' ),
	'Ancientpages'              => array( 'Ældste_sider' ),
	'Blankpage'                 => array( 'Tom_Side' ),
	'Block'                     => array( 'Bloker_adresse' ),
	'Booksources'               => array( 'ISBN-søgning' ),
	'BrokenRedirects'           => array( 'Defekte_omdirigeringer' ),
	'Categories'                => array( 'Kategorier' ),
	'ChangePassword'            => array( 'Nulstil_kodeord' ),
	'ComparePages'              => array( 'Sammenlign_Sider' ),
	'Confirmemail'              => array( 'Bekræft_e-mail' ),
	'Contributions'             => array( 'Bidrag' ),
	'CreateAccount'             => array( 'Opret_konto' ),
	'Deadendpages'              => array( 'Blindgydesider' ),
	'DeletedContributions'      => array( 'Slettede_bidrag' ),
	'DoubleRedirects'           => array( 'Dobbelte_omdirigeringer' ),
	'Emailuser'                 => array( 'E-mail' ),
	'Export'                    => array( 'Eksporter' ),
	'Fewestrevisions'           => array( 'Sider_med_færrest_redigeringer' ),
	'FileDuplicateSearch'       => array( 'Filduplikatsøgning' ),
	'Filepath'                  => array( 'Filsti' ),
	'Import'                    => array( 'Importere' ),
	'Invalidateemail'           => array( 'Ugyldiggør_e-mail' ),
	'BlockList'                 => array( 'Blokerede_adresser' ),
	'LinkSearch'                => array( 'Link_Søgning' ),
	'Listadmins'                => array( 'Administratorer' ),
	'Listbots'                  => array( 'Robotter' ),
	'Listfiles'                 => array( 'Filer', 'Filliste' ),
	'Listgrouprights'           => array( 'Grupperettighedsliste' ),
	'Listredirects'             => array( 'Henvisninger' ),
	'Listusers'                 => array( 'Brugerliste', 'Bruger' ),
	'Lockdb'                    => array( 'Databasespærring' ),
	'Log'                       => array( 'Loglister' ),
	'Lonelypages'               => array( 'Forældreløse_sider' ),
	'Longpages'                 => array( 'Længste_sider' ),
	'MergeHistory'              => array( 'Sammenfletning_af_historikker' ),
	'MIMEsearch'                => array( 'MIME-type-søgning' ),
	'Mostcategories'            => array( 'Sider_med_flest_kategorier' ),
	'Mostimages'                => array( 'Mest_brugte_filer' ),
	'Mostinterwikis'            => array( 'Flest_interwikilinks' ),
	'Mostlinked'                => array( 'Sider_med_flest_henvisninger' ),
	'Mostlinkedcategories'      => array( 'Kategorier_med_flest_sider' ),
	'Mostlinkedtemplates'       => array( 'Hyppigst_brugte_skabeloner' ),
	'Mostrevisions'             => array( 'Sider_med_flest_redigeringer' ),
	'Movepage'                  => array( 'Flyt_side' ),
	'Mycontributions'           => array( 'Mine_bidrag' ),
	'Mypage'                    => array( 'Min_brugerside' ),
	'Mytalk'                    => array( 'Min_diskussionsside' ),
	'Newimages'                 => array( 'Nye_filer' ),
	'Newpages'                  => array( 'Nye_sider' ),
	'Popularpages'              => array( 'Populære_sider' ),
	'Preferences'               => array( 'Indstillinger' ),
	'Prefixindex'               => array( 'Præfiksindeks' ),
	'Protectedpages'            => array( 'Beskyttede_sider' ),
	'Protectedtitles'           => array( 'Beskyttede_titler' ),
	'Randompage'                => array( 'Tilfældig_side' ),
	'Randomredirect'            => array( 'Tilfældig_henvisning' ),
	'Recentchanges'             => array( 'Seneste_ændringer' ),
	'Recentchangeslinked'       => array( 'Relaterede_ændringer' ),
	'Revisiondelete'            => array( 'Versionssletning' ),
	'Search'                    => array( 'Søgning' ),
	'Shortpages'                => array( 'Korteste_sider' ),
	'Specialpages'              => array( 'Specialsider' ),
	'Statistics'                => array( 'Statistik' ),
	'Uncategorizedcategories'   => array( 'Ukategoriserede_kategorier' ),
	'Uncategorizedimages'       => array( 'Ukategoriserede_filer' ),
	'Uncategorizedpages'        => array( 'Ukategoriserede_sider' ),
	'Uncategorizedtemplates'    => array( 'Ukategoriserede_skabeloner' ),
	'Undelete'                  => array( 'Gendannelse' ),
	'Unlockdb'                  => array( 'Databaseåbning' ),
	'Unusedcategories'          => array( 'Ubrugte_kategorier' ),
	'Unusedimages'              => array( 'Ubrugte_filer' ),
	'Unusedtemplates'           => array( 'Ubrugte_skabeloner' ),
	'Unwatchedpages'            => array( 'Uovervågede_sider' ),
	'Userlogin'                 => array( 'Log_på', 'Brugerlogind' ),
	'Userlogout'                => array( 'Log_ud', 'Brugerlogud' ),
	'Userrights'                => array( 'Brugerrettigheder' ),
	'Wantedcategories'          => array( 'Ønskede_kategorier' ),
	'Wantedfiles'               => array( 'Ønskede_filer' ),
	'Wantedpages'               => array( 'Ønskede_sider' ),
	'Wantedtemplates'           => array( 'Ønskede_skabeloner' ),
	'Watchlist'                 => array( 'Overvågningsliste' ),
	'Whatlinkshere'             => array( 'Hvad_linker_hertil' ),
	'Withoutinterwiki'          => array( 'Manglende_interwikilinks' ),
);

$dateFormats = array(
	'mdy time' => 'H:i',
	'mdy date' => 'M j. Y',
	'mdy both' => 'M j. Y, H:i',

	'dmy time' => 'H:i',
	'dmy date' => 'j. F Y',
	'dmy both' => 'j. M Y, H:i',

	'ymd time' => 'H:i',
	'ymd date' => 'Y M j',
	'ymd both' => 'Y M j, H:i'
);

$bookstoreList = array(
	"Bibliotek.dk" => "http://bibliotek.dk/vis.php?base=dfa&origin=kommando&field1=ccl&term1=is=$1&element=L&start=1&step=10",
	"Bogguide.dk" => "http://www.bogguide.dk/find_boeger_bog.asp?ISBN=$1",
	'inherit' => true,
);

$separatorTransformTable = array( ',' => '.', '.' => ',' );
$linkTrail = '/^([a-zæøå]+)(.*)$/sDu';

