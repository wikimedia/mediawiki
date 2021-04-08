<?php
/** Breton (brezhoneg)
 *
 * To improve a translation please visit https://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 */

$fallback = 'fr';

$namespaceNames = [
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
];

$namespaceAliases = [
	'Skeudenn'            => NS_FILE,
	'Kaozeadenn_Skeudenn' => NS_FILE_TALK,
];

$namespaceGenderAliases = [];

/** @phpcs-require-sorted-array */
$specialPageAliases = [
	'Activeusers'               => [ 'ImplijerienOberiant' ],
	'Allmessages'               => [ 'HollGemennadennoù' ],
	'Allpages'                  => [ 'AnHollBajennoù' ],
	'Ancientpages'              => [ 'PajennoùKozh' ],
	'Badtitle'                  => [ 'TitlFall' ],
	'Block'                     => [ 'Stankañ' ],
	'Booksources'               => [ 'MammennoùLevr' ],
	'BrokenRedirects'           => [ 'AdkasoùTorr' ],
	'Categories'                => [ 'Rummadoù' ],
	'ChangePassword'            => [ 'KemmañGer-tremen' ],
	'ComparePages'              => [ 'KeñveriañPajennoù' ],
	'Confirmemail'              => [ 'KadarnaatPostel' ],
	'Contributions'             => [ 'Degasadennoù' ],
	'CreateAccount'             => [ 'KrouiñKont' ],
	'DoubleRedirects'           => [ 'AdksaoùDoubl' ],
	'Emailuser'                 => [ 'PostelImplijer' ],
	'ExpandTemplates'           => [ 'PatromoùAstennet' ],
	'Export'                    => [ 'Ezporzhiañ' ],
	'Import'                    => [ 'Enporzhiañ' ],
	'LinkSearch'                => [ 'KlaskLiamm' ],
	'Listadmins'                => [ 'RollMerourien' ],
	'Listbots'                  => [ 'RollBotoù' ],
	'Listfiles'                 => [ 'RollSkeudennoù' ],
	'Listgrouprights'           => [ 'RollGwirioùStrollad' ],
	'Listredirects'             => [ 'RollañAdkasoù' ],
	'Listusers'                 => [ 'RollImplijerien' ],
	'Log'                       => [ 'Marilh' ],
	'Lonelypages'               => [ 'PajennoùEnoUnan' ],
	'Longpages'                 => [ 'PajennoùHir' ],
	'MergeHistory'              => [ 'KendeuziñIstor' ],
	'Mostlinkedtemplates'       => [ 'PatromoùImplijetañ' ],
	'Movepage'                  => [ 'AdkasPajenn' ],
	'Mycontributions'           => [ 'MaDegasadennoù' ],
	'MyLanguage'                => [ 'MaYezh' ],
	'Mypage'                    => [ 'MaFajenn' ],
	'Mytalk'                    => [ 'MaC\'haozeadennoù' ],
	'Newimages'                 => [ 'RestroùNevez', 'SkeudennoùNevez' ],
	'Newpages'                  => [ 'PajennoùNevez' ],
	'Preferences'               => [ 'Penndibaboù' ],
	'Protectedpages'            => [ 'PajennoùGwarezet' ],
	'Protectedtitles'           => [ 'TitloùGwarezet' ],
	'Randompage'                => [ 'DreZegouezh' ],
	'Recentchanges'             => [ 'KemmoùDiwezhañ' ],
	'Recentchangeslinked'       => [ 'KemmoùKar' ],
	'Search'                    => [ 'Klask' ],
	'Shortpages'                => [ 'PajennoùBerr' ],
	'Specialpages'              => [ 'PajennoùDibar' ],
	'Statistics'                => [ 'Stadegoù' ],
	'Tags'                      => [ 'Balizennoù' ],
	'Unblock'                   => [ 'Distankañ' ],
	'Uncategorizedcategories'   => [ 'RummadoùDirumm' ],
	'Uncategorizedimages'       => [ 'RestroùDirumm' ],
	'Uncategorizedpages'        => [ 'PajennoùDirumm' ],
	'Uncategorizedtemplates'    => [ 'PatromoùDirumm' ],
	'Undelete'                  => [ 'Diziverkañ' ],
	'Unusedcategories'          => [ 'RummadoùDizimplij' ],
	'Unusedimages'              => [ 'RestroùDizimplij' ],
	'Unusedtemplates'           => [ 'PatromoùDizimplij' ],
	'Unwatchedpages'            => [ 'PajennoùNannEvezhiet' ],
	'Upload'                    => [ 'Pellgargañ' ],
	'Userlogin'                 => [ 'KevreañImplijer' ],
	'Userlogout'                => [ 'DigevreañImplijer' ],
	'Userrights'                => [ 'GwirioùImplijer' ],
	'Version'                   => [ 'Stumm' ],
	'Wantedcategories'          => [ 'RummadoùGoulennet' ],
	'Wantedfiles'               => [ 'RestroùGoulennet' ],
	'Wantedpages'               => [ 'LiammoùTorr' ],
	'Wantedtemplates'           => [ 'PatromoùGoulennet' ],
	'Watchlist'                 => [ 'Roll_evezhiañ' ],
	'Whatlinkshere'             => [ 'PetraGasBetekAmañ' ],
	'Withoutinterwiki'          => [ 'HepEtrewiki' ],
];

/** @phpcs-require-sorted-array */
$magicWords = [
	'currentversion'            => [ '1', 'STUMMRED', 'CURRENTVERSION' ],
	'fullpagename'              => [ '1', 'ANVPAJENNKLOK', 'FULLPAGENAME' ],
	'fullpagenamee'             => [ '1', 'ANVPAJENNKLOKSK', 'FULLPAGENAMEE' ],
	'fullurl'                   => [ '0', 'URLKLOK:', 'FULLURL:' ],
	'gender'                    => [ '0', 'JENER:', 'GENDER:' ],
	'grammar'                   => [ '0', 'YEZHADUR:', 'GRAMMAR:' ],
	'img_bottom'                => [ '1', 'traoñ', 'bottom' ],
	'img_center'                => [ '1', 'kreizenn', 'center', 'centre' ],
	'img_left'                  => [ '1', 'kleiz', 'left' ],
	'img_link'                  => [ '1', 'liamm=$1', 'link=$1' ],
	'img_middle'                => [ '1', 'kreiz', 'middle' ],
	'img_none'                  => [ '1', 'netra', 'none' ],
	'img_page'                  => [ '1', 'pajenn=$1', 'pajenn $1', 'page=$1', 'page $1' ],
	'img_right'                 => [ '1', 'dehou', 'right' ],
	'img_sub'                   => [ '1', 'is', 'sub' ],
	'img_top'                   => [ '1', 'krec\'h', 'top' ],
	'language'                  => [ '0', '#YEZH:', '#LANGUAGE:' ],
	'namespace'                 => [ '1', 'ESAOUENNANV', 'NAMESPACE' ],
	'namespacee'                => [ '1', 'ESAOUENNANVSK', 'NAMESPACEE' ],
	'numberofactiveusers'       => [ '1', 'NIVERAIMPLIJERIENOBERIANT', 'NUMBEROFACTIVEUSERS' ],
	'numberofarticles'          => [ '1', 'NIVERABENNADOU', 'NUMBEROFARTICLES' ],
	'numberofedits'             => [ '1', 'NIVERAZEGASEDENNOU', 'NUMBEROFEDITS' ],
	'numberoffiles'             => [ '1', 'NIVERARESTROU', 'NUMBEROFFILES' ],
	'numberofpages'             => [ '1', 'NIVERABAJENNOU', 'NUMBEROFPAGES' ],
	'numberofusers'             => [ '1', 'NIVERAIMPLIJERIEN', 'NUMBEROFUSERS' ],
	'pagename'                  => [ '1', 'ANVPAJENN', 'PAGENAME' ],
	'pagenamee'                 => [ '1', 'ANVPAJENNSK', 'PAGENAMEE' ],
	'pagesize'                  => [ '1', 'MENTPAJENN', 'PAGESIZE' ],
	'plural'                    => [ '0', 'LIESTER:', 'PLURAL:' ],
	'redirect'                  => [ '0', '#ADKAS', '#REDIRECT' ],
	'server'                    => [ '0', 'SERVIJER', 'SERVER' ],
	'servername'                => [ '0', 'ANVSERVIJER', 'SERVERNAME' ],
	'sitename'                  => [ '1', 'ANVLEC\'HIENN', 'SITENAME' ],
	'special'                   => [ '0', 'dibar', 'special' ],
	'subpagename'               => [ '1', 'ANVISPAJENN', 'SUBPAGENAME' ],
	'url_path'                  => [ '0', 'HENT', 'PATH' ],
];

$bookstoreList = [
	'Amazon.fr'    => 'https://www.amazon.fr/exec/obidos/ISBN=$1',
	'alapage.fr'   => 'http://www.alapage.com/mx/?tp=F&type=101&l_isbn=$1&donnee_appel=ALASQ&devise=&',
	'fnac.com'     => 'http://www3.fnac.com/advanced/book.do?isbn=$1',
	'chapitre.com' => 'http://www.chapitre.com/frame_rec.asp?isbn=$1',
];

$datePreferences = false;
$defaultDateFormat = 'dmy';
$dateFormats = [
	'dmy time' => 'H:i',
	'dmy date' => 'j M Y',
	'dmy both' => 'j M Y "da" H:i',
];

$separatorTransformTable = [ ',' => "\u{00A0}", '.' => ',' ];
$linkTrail = "/^((?:c\'h|C\'H|C\'h|c’h|C’H|C’h|[a-zA-ZàâçéèêîôûäëïöüùñÇÉÂÊÎÔÛÄËÏÖÜÀÈÙÑ])+)(.*)$/sDu";
