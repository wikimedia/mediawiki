<?php
/** Friulian (furlan)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 */

$fallback = 'it';


$namespaceNames = array(
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
);

$specialPageAliases = array(
	'Allmessages'               => array( 'Messaçs' ),
	'Allpages'                  => array( 'DutisLisPagjinis' ),
	'Ancientpages'              => array( 'PagjinisPluiVieris' ),
	'Block'                     => array( 'BlocheIP' ),
	'Booksources'               => array( 'RicercjeISBN' ),
	'BrokenRedirects'           => array( 'ReIndreçamentsSbaliâts' ),
	'Categories'                => array( 'Categoriis' ),
	'ChangePassword'            => array( 'ReimpuestePerauleClâf' ),
	'Confirmemail'              => array( 'ConfermePuesteEletroniche' ),
	'Contributions'             => array( 'Contribûts', 'ContribûtsUtent' ),
	'CreateAccount'             => array( 'CreeIdentitât' ),
	'Deadendpages'              => array( 'PagjinisCenceJessude' ),
	'DoubleRedirects'           => array( 'ReIndreçamentsDoplis' ),
	'Emailuser'                 => array( 'MandeEmail' ),
	'Export'                    => array( 'Espuarte' ),
	'Import'                    => array( 'Impuarte' ),
	'BlockList'                 => array( 'IPBlocâts' ),
	'Listadmins'                => array( 'ListeAministradôrs' ),
	'Listbots'                  => array( 'ListeBots' ),
	'Listfiles'                 => array( 'Figuris' ),
	'Listredirects'             => array( 'ListeReIndreçaments' ),
	'Listusers'                 => array( 'Utents', 'ListeUtents' ),
	'Lockdb'                    => array( 'BlocheDB' ),
	'Log'                       => array( 'Regjistri', 'Regjistris' ),
	'Lonelypages'               => array( 'PagjinisSolitariis' ),
	'Longpages'                 => array( 'PagjinisPluiLungjis' ),
	'MIMEsearch'                => array( 'RicercjeMIME' ),
	'Movepage'                  => array( 'Môf', 'CambieNon' ),
	'Mycontributions'           => array( 'MieiContribûts' ),
	'Mypage'                    => array( 'MêPagjineUtent' ),
	'Mytalk'                    => array( 'MêsDiscussions' ),
	'Newimages'                 => array( 'GnovisFiguris' ),
	'Newpages'                  => array( 'GnovisPagjinis' ),
	'Popularpages'              => array( 'PagjinisPopolârs' ),
	'Preferences'               => array( 'Preferencis' ),
	'Prefixindex'               => array( 'Prefìs' ),
	'Protectedpages'            => array( 'PagjinisProtezudis' ),
	'Protectedtitles'           => array( 'TituiProtezûts' ),
	'Randompage'                => array( 'PagjineCasuâl' ),
	'Randomredirect'            => array( 'ReIndreçamentCasuâl' ),
	'Recentchanges'             => array( 'UltinsCambiaments' ),
	'Recentchangeslinked'       => array( 'CambiamentsLeâts' ),
	'Revisiondelete'            => array( 'ScanceleRevision' ),
	'Search'                    => array( 'Ricercje', 'Cîr' ),
	'Shortpages'                => array( 'PagjinisPluiCurtis' ),
	'Specialpages'              => array( 'PagjinisSpeciâls' ),
	'Statistics'                => array( 'Statistichis' ),
	'Uncategorizedcategories'   => array( 'CategoriisCenceCategorie' ),
	'Uncategorizedimages'       => array( 'FigurisCenceCategorie' ),
	'Uncategorizedpages'        => array( 'PagjinisCenceCategorie' ),
	'Uncategorizedtemplates'    => array( 'ModeiCenceCategorie' ),
	'Undelete'                  => array( 'Ripristine' ),
	'Unlockdb'                  => array( 'SblocheDB' ),
	'Unusedcategories'          => array( 'CategoriisNoDopradis' ),
	'Unusedimages'              => array( 'FigurisNoDopradis' ),
	'Unusedtemplates'           => array( 'ModeiNoDoprâts' ),
	'Unwatchedpages'            => array( 'PagjinisNoTignudisDiVoli' ),
	'Upload'                    => array( 'Cjame' ),
	'Userlogin'                 => array( 'Jentre' ),
	'Userlogout'                => array( 'Jes' ),
	'Userrights'                => array( 'PermèsUtents' ),
	'Wantedcategories'          => array( 'CategoriisDesideradis' ),
	'Watchlist'                 => array( 'TignudisDiVoli' ),
	'Whatlinkshere'             => array( 'Leams' ),
	'Withoutinterwiki'          => array( 'CenceInterwiki' ),
);

$datePreferences = false;
$defaultDateFormat = 'dmy';
$dateFormats = array(
	'dmy time' => 'H:i',
	'dmy date' => 'j "di" M Y',
	'dmy both' => 'j "di" M Y "a lis" H:i',
);

$separatorTransformTable = array( ',' => "\xc2\xa0", '.' => ',' );

