<?php
/** Breton (brezhoneg)
 *
 * To improve a translation please visit https://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 */

$namespaceNames = array(
	NS_MEDIA            => 'Media',
	NS_SPECIAL          => 'Dibar',
	NS_TALK             => 'Kaozeal',
	NS_USER             => 'Implijer',
	NS_USER_TALK        => 'Kaozeadenn_Implijer',
	NS_PROJECT_TALK     => 'Kaozeadenn_$1',
	NS_FILE             => 'Restr',
	NS_FILE_TALK        => 'Kaozeadenn_Restr',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'Kaozeadenn_MediaWiki',
	NS_TEMPLATE         => 'Patrom',
	NS_TEMPLATE_TALK    => 'Kaozeadenn_Patrom',
	NS_HELP             => 'Skoazell',
	NS_HELP_TALK        => 'Kaozeadenn_Skoazell',
	NS_CATEGORY         => 'Rummad',
	NS_CATEGORY_TALK    => 'Kaozeadenn_Rummad',
);

$namespaceAliases = array(
	'Skeudenn'            => NS_FILE,
	'Kaozeadenn_Skeudenn' => NS_FILE_TALK,
);

$specialPageAliases = array(
	'Activeusers'               => array( 'ImplijerienOberiant' ),
	'Allmessages'               => array( 'HollGemennadennoù' ),
	'Allpages'                  => array( 'AnHollBajennoù' ),
	'Ancientpages'              => array( 'PajennoùKozh' ),
	'Badtitle'                  => array( 'TitlFall' ),
	'Block'                     => array( 'Stankañ' ),
	'Booksources'               => array( 'MammennoùLevr' ),
	'BrokenRedirects'           => array( 'AdkasoùTorr' ),
	'Categories'                => array( 'Rummadoù' ),
	'ChangePassword'            => array( 'KemmañGer-tremen' ),
	'ComparePages'              => array( 'KeñveriañPajennoù' ),
	'Confirmemail'              => array( 'KadarnaatPostel' ),
	'Contributions'             => array( 'Degasadennoù' ),
	'CreateAccount'             => array( 'KrouiñKont' ),
	'DoubleRedirects'           => array( 'AdksaoùDoubl' ),
	'Emailuser'                 => array( 'PostelImplijer' ),
	'ExpandTemplates'           => array( 'PatromoùAstennet' ),
	'Export'                    => array( 'Ezporzhiañ' ),
	'Import'                    => array( 'Enporzhiañ' ),
	'LinkSearch'                => array( 'KlaskLiamm' ),
	'Listadmins'                => array( 'RollMerourien' ),
	'Listbots'                  => array( 'RollBotoù' ),
	'Listfiles'                 => array( 'RollSkeudennoù' ),
	'Listgrouprights'           => array( 'RollGwirioùStrollad' ),
	'Listredirects'             => array( 'RollañAdkasoù' ),
	'Listusers'                 => array( 'RollImplijerien' ),
	'Log'                       => array( 'Marilh' ),
	'Lonelypages'               => array( 'PajennoùEnoUnan' ),
	'Longpages'                 => array( 'PajennoùHir' ),
	'MergeHistory'              => array( 'KendeuziñIstor' ),
	'Mostlinkedtemplates'       => array( 'PatromoùImplijetañ' ),
	'Movepage'                  => array( 'AdkasPajenn' ),
	'Mycontributions'           => array( 'MaDegasadennoù' ),
	'MyLanguage'                => array( 'MaYezh' ),
	'Mypage'                    => array( 'MaFajenn' ),
	'Mytalk'                    => array( 'MaC\'haozeadennoù' ),
	'Newimages'                 => array( 'RestroùNevez', 'SkeudennoùNevez' ),
	'Newpages'                  => array( 'PajennoùNevez' ),
	'Preferences'               => array( 'Penndibaboù' ),
	'Protectedpages'            => array( 'PajennoùGwarezet' ),
	'Protectedtitles'           => array( 'TitloùGwarezet' ),
	'Randompage'                => array( 'DreZegouezh' ),
	'Recentchanges'             => array( 'KemmoùDiwezhañ' ),
	'Recentchangeslinked'       => array( 'KemmoùKar' ),
	'Search'                    => array( 'Klask' ),
	'Shortpages'                => array( 'PajennoùBerr' ),
	'Specialpages'              => array( 'PajennoùDibar' ),
	'Statistics'                => array( 'Stadegoù' ),
	'Tags'                      => array( 'Balizennoù' ),
	'Unblock'                   => array( 'Distankañ' ),
	'Uncategorizedcategories'   => array( 'RummadoùDirumm' ),
	'Uncategorizedimages'       => array( 'RestroùDirumm' ),
	'Uncategorizedpages'        => array( 'PajennoùDirumm' ),
	'Uncategorizedtemplates'    => array( 'PatromoùDirumm' ),
	'Undelete'                  => array( 'Diziverkañ' ),
	'Unusedcategories'          => array( 'RummadoùDizimplij' ),
	'Unusedimages'              => array( 'RestroùDizimplij' ),
	'Unusedtemplates'           => array( 'PatromoùDizimplij' ),
	'Unwatchedpages'            => array( 'PajennoùNannEvezhiet' ),
	'Upload'                    => array( 'Pellgargañ' ),
	'Userlogin'                 => array( 'KevreañImplijer' ),
	'Userlogout'                => array( 'DigevreañImplijer' ),
	'Userrights'                => array( 'GwirioùImplijer' ),
	'Version'                   => array( 'Stumm' ),
	'Wantedcategories'          => array( 'RummadoùGoulennet' ),
	'Wantedfiles'               => array( 'RestroùGoulennet' ),
	'Wantedpages'               => array( 'LiammoùTorr' ),
	'Wantedtemplates'           => array( 'PatromoùGoulennet' ),
	'Watchlist'                 => array( 'Roll_evezhiañ' ),
	'Whatlinkshere'             => array( 'PetraGasBetekAmañ' ),
	'Withoutinterwiki'          => array( 'HepEtrewiki' ),
);

$magicWords = array(
	'redirect'                  => array( '0', '#ADKAS', '#REDIRECT' ),
	'numberofpages'             => array( '1', 'NIVERABAJENNOU', 'NUMBEROFPAGES' ),
	'numberofarticles'          => array( '1', 'NIVERABENNADOU', 'NUMBEROFARTICLES' ),
	'numberoffiles'             => array( '1', 'NIVERARESTROU', 'NUMBEROFFILES' ),
	'numberofusers'             => array( '1', 'NIVERAIMPLIJERIEN', 'NUMBEROFUSERS' ),
	'numberofactiveusers'       => array( '1', 'NIVERAIMPLIJERIENOBERIANT', 'NUMBEROFACTIVEUSERS' ),
	'numberofedits'             => array( '1', 'NIVERAZEGASEDENNOU', 'NUMBEROFEDITS' ),
	'pagename'                  => array( '1', 'ANVPAJENN', 'PAGENAME' ),
	'pagenamee'                 => array( '1', 'ANVPAJENNSK', 'PAGENAMEE' ),
	'namespace'                 => array( '1', 'ESAOUENNANV', 'NAMESPACE' ),
	'namespacee'                => array( '1', 'ESAOUENNANVSK', 'NAMESPACEE' ),
	'fullpagename'              => array( '1', 'ANVPAJENNKLOK', 'FULLPAGENAME' ),
	'fullpagenamee'             => array( '1', 'ANVPAJENNKLOKSK', 'FULLPAGENAMEE' ),
	'subpagename'               => array( '1', 'ANVISPAJENN', 'SUBPAGENAME' ),
	'img_right'                 => array( '1', 'dehou', 'right' ),
	'img_left'                  => array( '1', 'kleiz', 'left' ),
	'img_none'                  => array( '1', 'netra', 'none' ),
	'img_center'                => array( '1', 'kreizenn', 'center', 'centre' ),
	'img_page'                  => array( '1', 'pajenn=$1', 'pajenn $1', 'page=$1', 'page $1' ),
	'img_sub'                   => array( '1', 'is', 'sub' ),
	'img_top'                   => array( '1', 'krec\'h', 'top' ),
	'img_middle'                => array( '1', 'kreiz', 'middle' ),
	'img_bottom'                => array( '1', 'traoñ', 'bottom' ),
	'img_link'                  => array( '1', 'liamm=$1', 'link=$1' ),
	'sitename'                  => array( '1', 'ANVLEC\'HIENN', 'SITENAME' ),
	'server'                    => array( '0', 'SERVIJER', 'SERVER' ),
	'servername'                => array( '0', 'ANVSERVIJER', 'SERVERNAME' ),
	'grammar'                   => array( '0', 'YEZHADUR:', 'GRAMMAR:' ),
	'gender'                    => array( '0', 'JENER:', 'GENDER:' ),
	'plural'                    => array( '0', 'LIESTER:', 'PLURAL:' ),
	'fullurl'                   => array( '0', 'URLKLOK:', 'FULLURL:' ),
	'currentversion'            => array( '1', 'STUMMRED', 'CURRENTVERSION' ),
	'language'                  => array( '0', '#YEZH:', '#LANGUAGE:' ),
	'special'                   => array( '0', 'dibar', 'special' ),
	'pagesize'                  => array( '1', 'MENTPAJENN', 'PAGESIZE' ),
	'url_path'                  => array( '0', 'HENT', 'PATH' ),
);

$bookstoreList = array(
	'Amazon.fr'    => 'http://www.amazon.fr/exec/obidos/ISBN=$1',
	'alapage.fr'   => 'http://www.alapage.com/mx/?tp=F&type=101&l_isbn=$1&donnee_appel=ALASQ&devise=&',
	'fnac.com'     => 'http://www3.fnac.com/advanced/book.do?isbn=$1',
	'chapitre.com' => 'http://www.chapitre.com/frame_rec.asp?isbn=$1',
);

$datePreferences = false;
$defaultDateFormat = 'dmy';
$dateFormats = array(
	'dmy time' => 'H:i',
	'dmy date' => 'j M Y',
	'dmy both' => 'j M Y "da" H:i',
);

$separatorTransformTable = array( ',' => "\xc2\xa0", '.' => ',' );
$linkTrail = "/^((?:c\'h|C\'H|C\'h|c’h|C’H|C’h|[a-zA-ZàâçéèêîôûäëïöüùñÇÉÂÊÎÔÛÄËÏÖÜÀÈÙÑ])+)(.*)$/sDu";

