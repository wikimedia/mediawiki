<?php
/** Danish (dansk)
 *
 * To improve a translation please visit https://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 */

$namespaceNames = [
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
];

$namespaceAliases = [
	'$1-diskussion'        => NS_PROJECT_TALK,
	'Billede'              => NS_FILE,
	'Billeddiskussion'     => NS_FILE_TALK,
	'MediaWiki-diskussion' => NS_MEDIAWIKI_TALK,
	'Hjælp-diskussion'     => NS_HELP_TALK,
];

$specialPageAliases = [
	'Activeusers'               => [ 'Aktive_Brugere' ],
	'Allmessages'               => [ 'MediaWiki-systemmeddelelser' ],
	'Allpages'                  => [ 'Alle_sider' ],
	'Ancientpages'              => [ 'Ældste_sider' ],
	'Blankpage'                 => [ 'Tom_Side' ],
	'Block'                     => [ 'Bloker_adresse' ],
	'Booksources'               => [ 'ISBN-søgning' ],
	'BrokenRedirects'           => [ 'Defekte_omdirigeringer' ],
	'Categories'                => [ 'Kategorier' ],
	'ChangePassword'            => [ 'Nulstil_kodeord' ],
	'ComparePages'              => [ 'Sammenlign_Sider' ],
	'Confirmemail'              => [ 'Bekræft_e-mail' ],
	'Contributions'             => [ 'Bidrag' ],
	'CreateAccount'             => [ 'Opret_konto' ],
	'Deadendpages'              => [ 'Blindgydesider' ],
	'DeletedContributions'      => [ 'Slettede_bidrag' ],
	'DoubleRedirects'           => [ 'Dobbelte_omdirigeringer' ],
	'Emailuser'                 => [ 'E-mail' ],
	'Export'                    => [ 'Eksporter' ],
	'Fewestrevisions'           => [ 'Sider_med_færrest_redigeringer' ],
	'FileDuplicateSearch'       => [ 'Filduplikatsøgning' ],
	'Filepath'                  => [ 'Filsti' ],
	'Import'                    => [ 'Importere' ],
	'Invalidateemail'           => [ 'Ugyldiggør_e-mail' ],
	'BlockList'                 => [ 'Blokerede_adresser' ],
	'LinkSearch'                => [ 'Link_Søgning' ],
	'Listadmins'                => [ 'Administratorer' ],
	'Listbots'                  => [ 'Robotter' ],
	'Listfiles'                 => [ 'Filer', 'Filliste' ],
	'Listgrouprights'           => [ 'Grupperettighedsliste' ],
	'Listredirects'             => [ 'Henvisninger' ],
	'Listusers'                 => [ 'Brugerliste', 'Bruger' ],
	'Lockdb'                    => [ 'Databasespærring' ],
	'Log'                       => [ 'Loglister' ],
	'Lonelypages'               => [ 'Forældreløse_sider' ],
	'Longpages'                 => [ 'Længste_sider' ],
	'MergeHistory'              => [ 'Sammenfletning_af_historikker' ],
	'MIMEsearch'                => [ 'MIME-type-søgning' ],
	'Mostcategories'            => [ 'Sider_med_flest_kategorier' ],
	'Mostimages'                => [ 'Mest_brugte_filer' ],
	'Mostinterwikis'            => [ 'Flest_interwikilinks' ],
	'Mostlinked'                => [ 'Sider_med_flest_henvisninger' ],
	'Mostlinkedcategories'      => [ 'Kategorier_med_flest_sider' ],
	'Mostlinkedtemplates'       => [ 'Hyppigst_brugte_skabeloner' ],
	'Mostrevisions'             => [ 'Sider_med_flest_redigeringer' ],
	'Movepage'                  => [ 'Flyt_side' ],
	'Mycontributions'           => [ 'Mine_bidrag' ],
	'Mypage'                    => [ 'Min_brugerside' ],
	'Mytalk'                    => [ 'Min_diskussionsside' ],
	'Newimages'                 => [ 'Nye_filer' ],
	'Newpages'                  => [ 'Nye_sider' ],
	'Preferences'               => [ 'Indstillinger' ],
	'Prefixindex'               => [ 'Præfiksindeks' ],
	'Protectedpages'            => [ 'Beskyttede_sider' ],
	'Protectedtitles'           => [ 'Beskyttede_titler' ],
	'Randompage'                => [ 'Tilfældig_side' ],
	'Randomredirect'            => [ 'Tilfældig_henvisning' ],
	'Recentchanges'             => [ 'Seneste_ændringer' ],
	'Recentchangeslinked'       => [ 'Relaterede_ændringer' ],
	'Revisiondelete'            => [ 'Versionssletning' ],
	'Search'                    => [ 'Søgning' ],
	'Shortpages'                => [ 'Korteste_sider' ],
	'Specialpages'              => [ 'Specialsider' ],
	'Statistics'                => [ 'Statistik' ],
	'Uncategorizedcategories'   => [ 'Ukategoriserede_kategorier' ],
	'Uncategorizedimages'       => [ 'Ukategoriserede_filer' ],
	'Uncategorizedpages'        => [ 'Ukategoriserede_sider' ],
	'Uncategorizedtemplates'    => [ 'Ukategoriserede_skabeloner' ],
	'Undelete'                  => [ 'Gendannelse' ],
	'Unlockdb'                  => [ 'Databaseåbning' ],
	'Unusedcategories'          => [ 'Ubrugte_kategorier' ],
	'Unusedimages'              => [ 'Ubrugte_filer' ],
	'Unusedtemplates'           => [ 'Ubrugte_skabeloner' ],
	'Unwatchedpages'            => [ 'Uovervågede_sider' ],
	'Userlogin'                 => [ 'Log_på', 'Brugerlogind' ],
	'Userlogout'                => [ 'Log_ud', 'Brugerlogud' ],
	'Userrights'                => [ 'Brugerrettigheder' ],
	'Wantedcategories'          => [ 'Ønskede_kategorier' ],
	'Wantedfiles'               => [ 'Ønskede_filer' ],
	'Wantedpages'               => [ 'Ønskede_sider' ],
	'Wantedtemplates'           => [ 'Ønskede_skabeloner' ],
	'Watchlist'                 => [ 'Overvågningsliste' ],
	'Whatlinkshere'             => [ 'Hvad_linker_hertil' ],
	'Withoutinterwiki'          => [ 'Manglende_interwikilinks' ],
];

$dateFormats = [
	'mdy time' => 'H:i',
	'mdy date' => 'M j. Y',
	'mdy both' => 'M j. Y, H:i',

	'dmy time' => 'H:i',
	'dmy date' => 'j. F Y',
	'dmy both' => 'j. M Y, H:i',

	'ymd time' => 'H:i',
	'ymd date' => 'Y M j',
	'ymd both' => 'Y M j, H:i'
];

$bookstoreList = [
	"Bibliotek.dk" => "http://bibliotek.dk/vis.php?base=dfa&origin=kommando&field1=ccl&term1=is=$1&element=L&start=1&step=10",
	"Bogguide.dk" => "http://www.bogguide.dk/find_boeger_bog.asp?ISBN=$1",
	'inherit' => true,
];

$separatorTransformTable = [ ',' => '.', '.' => ',' ];
$linkTrail = '/^([a-zæøå]+)(.*)$/sDu';
