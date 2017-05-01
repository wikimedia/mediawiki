<?php
/** Veps (vepsän kel’)
 *
 * To improve a translation please visit https://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Aig mest ei varasta
 * @author Andrijko Z.
 * @author Kaganer
 * @author Sura
 * @author Triple-ADHD-AS
 * @author Игорь Бродский
 */

$fallback = 'et';

$namespaceNames = [
	NS_MEDIA            => 'Media',
	NS_SPECIAL          => 'Specialine',
	NS_TALK             => 'Lodu',
	NS_USER             => 'Kävutai',
	NS_USER_TALK        => 'Lodu_kävutajas',
	NS_PROJECT_TALK     => 'Lodu_$1-saitas',
	NS_FILE             => 'Fail',
	NS_FILE_TALK        => 'Lodu_failas',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'Lodu_MediaWikiš',
	NS_TEMPLATE         => 'Šablon',
	NS_TEMPLATE_TALK    => 'Lodu_šablonas',
	NS_HELP             => 'Abu',
	NS_HELP_TALK        => 'Lodu_abus',
	NS_CATEGORY         => 'Kategorii',
	NS_CATEGORY_TALK    => 'Lodu_kategorijas',
];

$specialPageAliases = [
	'Allmessages'               => [ 'KaikTedotused' ],
	'Allpages'                  => [ 'KaikLehtesed' ],
	'Ancientpages'              => [ 'VanhadLehtpoled' ],
	'BrokenRedirects'           => [ 'RebitadudLäbikosketused' ],
	'Categories'                => [ 'Kategorijad' ],
	'Contributions'             => [ 'Tond' ],
	'CreateAccount'             => [ 'SätaRegistracii' ],
	'DoubleRedirects'           => [ 'KaksitadudLäbikosketused' ],
	'Export'                    => [ 'Eksport' ],
	'Listusers'                 => [ 'KävutajidenNimikirjutez' ],
	'Lonelypages'               => [ 'ÜksjäižedLehtpoled', 'ArmotomadLehtesed' ],
	'Longpages'                 => [ 'Pit\'kädLehtpoled' ],
	'Mycontributions'           => [ 'MinunTond' ],
	'Mypage'                    => [ 'MinunLehtpol\'' ],
	'Mytalk'                    => [ 'MinunLodu' ],
	'Newimages'                 => [ 'UdedFailad' ],
	'Newpages'                  => [ 'UdedLehtpoled' ],
	'Preferences'               => [ 'Järgendused' ],
	'Protectedpages'            => [ 'KaitudLehtpoled' ],
	'Protectedtitles'           => [ 'KaitudPälkirjutesed' ],
	'Recentchanges'             => [ 'TantoižedToižetused' ],
	'Search'                    => [ 'Ectä' ],
	'Shortpages'                => [ 'LühüdadLehtpoled' ],
	'Specialpages'              => [ 'SpecialižedLehtpoled' ],
	'Statistics'                => [ 'Statistikad' ],
	'Upload'                    => [ 'Jügutoitta' ],
	'Userlogin'                 => [ 'KävutajanTulendnimi' ],
	'Userlogout'                => [ 'KävutajanLäntend' ],
	'Version'                   => [ 'Versii' ],
	'Wantedfiles'               => [ 'VarastadudFailad' ],
	'Watchlist'                 => [ 'KaclendNimikirjutez' ],
];

$magicWords = [
	'img_right'                 => [ '1', 'oiged', 'paremal', 'right' ],
	'img_left'                  => [ '1', 'hura', 'vasakul', 'left' ],
	'img_none'                  => [ '1', 'eile', 'tühi', 'none' ],
	'img_width'                 => [ '1', '$1piks', '$1px' ],
	'img_border'                => [ '1', 'röun', 'ääris', 'border' ],
	'img_top'                   => [ '1', 'üläh', 'top' ],
	'img_middle'                => [ '1', 'kesk', 'middle' ],
	'img_bottom'                => [ '1', 'ala', 'bottom' ],
	'sitename'                  => [ '1', 'SAITANNIMI', 'KOHANIMI', 'SITENAME' ],
	'grammar'                   => [ '0', 'GRAMMATIK:', 'GRAMMAR:' ],
	'gender'                    => [ '0', 'SUGU:', 'GENDER:' ],
	'plural'                    => [ '0', 'ÄILUGU:', 'PLURAL:' ],
	'fullurl'                   => [ '0', 'TÄUZ\'URL:', 'KOGUURL:', 'FULLURL:' ],
	'index'                     => [ '1', '__INDEKS__', 'INDEKSIGA', '__INDEX__' ],
];

