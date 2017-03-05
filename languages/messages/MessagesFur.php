<?php
/** Friulian (furlan)
 *
 * To improve a translation please visit https://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 */

$fallback = 'it';

$namespaceNames = [
	NS_MEDIA            => 'Media',
	NS_SPECIAL          => 'Speciâl',
	NS_TALK             => 'Discussion',
	NS_USER             => 'Utent',
	NS_USER_TALK        => 'Discussion_utent',
	NS_PROJECT_TALK     => 'Discussion_$1',
	NS_FILE             => 'Figure',
	NS_FILE_TALK        => 'Discussion_figure',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'Discussion_MediaWiki',
	NS_TEMPLATE         => 'Model',
	NS_TEMPLATE_TALK    => 'Discussion_model',
	NS_HELP             => 'Jutori',
	NS_HELP_TALK        => 'Discussion_jutori',
	NS_CATEGORY         => 'Categorie',
	NS_CATEGORY_TALK    => 'Discussion_categorie',
];

$specialPageAliases = [
	'Allmessages'               => [ 'Messaçs' ],
	'Allpages'                  => [ 'DutisLisPagjinis' ],
	'Ancientpages'              => [ 'PagjinisPluiVieris' ],
	'Block'                     => [ 'BlocheIP' ],
	'Booksources'               => [ 'RicercjeISBN' ],
	'BrokenRedirects'           => [ 'ReIndreçamentsSbaliâts' ],
	'Categories'                => [ 'Categoriis' ],
	'ChangePassword'            => [ 'ReimpuestePerauleClâf' ],
	'Confirmemail'              => [ 'ConfermePuesteEletroniche' ],
	'Contributions'             => [ 'Contribûts', 'ContribûtsUtent' ],
	'CreateAccount'             => [ 'CreeIdentitât' ],
	'Deadendpages'              => [ 'PagjinisCenceJessude' ],
	'DoubleRedirects'           => [ 'ReIndreçamentsDoplis' ],
	'Emailuser'                 => [ 'MandeEmail' ],
	'Export'                    => [ 'Espuarte' ],
	'Import'                    => [ 'Impuarte' ],
	'BlockList'                 => [ 'IPBlocâts' ],
	'Listadmins'                => [ 'ListeAministradôrs' ],
	'Listbots'                  => [ 'ListeBots' ],
	'Listfiles'                 => [ 'Figuris' ],
	'Listredirects'             => [ 'ListeReIndreçaments' ],
	'Listusers'                 => [ 'Utents', 'ListeUtents' ],
	'Lockdb'                    => [ 'BlocheDB' ],
	'Log'                       => [ 'Regjistri', 'Regjistris' ],
	'Lonelypages'               => [ 'PagjinisSolitariis' ],
	'Longpages'                 => [ 'PagjinisPluiLungjis' ],
	'MIMEsearch'                => [ 'RicercjeMIME' ],
	'Movepage'                  => [ 'Môf', 'CambieNon' ],
	'Mycontributions'           => [ 'MieiContribûts' ],
	'Mypage'                    => [ 'MêPagjineUtent' ],
	'Mytalk'                    => [ 'MêsDiscussions' ],
	'Newimages'                 => [ 'GnovisFiguris' ],
	'Newpages'                  => [ 'GnovisPagjinis' ],
	'Preferences'               => [ 'Preferencis' ],
	'Prefixindex'               => [ 'Prefìs' ],
	'Protectedpages'            => [ 'PagjinisProtezudis' ],
	'Protectedtitles'           => [ 'TituiProtezûts' ],
	'Randompage'                => [ 'PagjineCasuâl' ],
	'Randomredirect'            => [ 'ReIndreçamentCasuâl' ],
	'Recentchanges'             => [ 'UltinsCambiaments' ],
	'Recentchangeslinked'       => [ 'CambiamentsLeâts' ],
	'Revisiondelete'            => [ 'ScanceleRevision' ],
	'Search'                    => [ 'Ricercje', 'Cîr' ],
	'Shortpages'                => [ 'PagjinisPluiCurtis' ],
	'Specialpages'              => [ 'PagjinisSpeciâls' ],
	'Statistics'                => [ 'Statistichis' ],
	'Uncategorizedcategories'   => [ 'CategoriisCenceCategorie' ],
	'Uncategorizedimages'       => [ 'FigurisCenceCategorie' ],
	'Uncategorizedpages'        => [ 'PagjinisCenceCategorie' ],
	'Uncategorizedtemplates'    => [ 'ModeiCenceCategorie' ],
	'Undelete'                  => [ 'Ripristine' ],
	'Unlockdb'                  => [ 'SblocheDB' ],
	'Unusedcategories'          => [ 'CategoriisNoDopradis' ],
	'Unusedimages'              => [ 'FigurisNoDopradis' ],
	'Unusedtemplates'           => [ 'ModeiNoDoprâts' ],
	'Unwatchedpages'            => [ 'PagjinisNoTignudisDiVoli' ],
	'Upload'                    => [ 'Cjame' ],
	'Userlogin'                 => [ 'Jentre' ],
	'Userlogout'                => [ 'Jes' ],
	'Userrights'                => [ 'PermèsUtents' ],
	'Wantedcategories'          => [ 'CategoriisDesideradis' ],
	'Watchlist'                 => [ 'TignudisDiVoli' ],
	'Whatlinkshere'             => [ 'Leams' ],
	'Withoutinterwiki'          => [ 'CenceInterwiki' ],
];

$datePreferences = false;
$defaultDateFormat = 'dmy';
$dateFormats = [
	'dmy time' => 'H:i',
	'dmy date' => 'j "di" M Y',
	'dmy both' => 'j "di" M Y "a lis" H:i',
];

$separatorTransformTable = [ ',' => "\xc2\xa0", '.' => ',' ];
