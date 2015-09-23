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

$namespaceNames = array(
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
);

$specialPageAliases = array(
	'Allmessages'               => array( 'KaikTedotused' ),
	'Allpages'                  => array( 'KaikLehtesed' ),
	'Ancientpages'              => array( 'VanhadLehtpoled' ),
	'BrokenRedirects'           => array( 'RebitadudLäbikosketused' ),
	'Categories'                => array( 'Kategorijad' ),
	'Contributions'             => array( 'Tond' ),
	'CreateAccount'             => array( 'SätaRegistracii' ),
	'DoubleRedirects'           => array( 'KaksitadudLäbikosketused' ),
	'Export'                    => array( 'Eksport' ),
	'Listusers'                 => array( 'KävutajidenNimikirjutez' ),
	'Lonelypages'               => array( 'ÜksjäižedLehtpoled', 'ArmotomadLehtesed' ),
	'Longpages'                 => array( 'Pit\'kädLehtpoled' ),
	'Mycontributions'           => array( 'MinunTond' ),
	'Mypage'                    => array( 'MinunLehtpol\'' ),
	'Mytalk'                    => array( 'MinunLodu' ),
	'Newimages'                 => array( 'UdedFailad' ),
	'Newpages'                  => array( 'UdedLehtpoled' ),
	'Preferences'               => array( 'Järgendused' ),
	'Protectedpages'            => array( 'KaitudLehtpoled' ),
	'Protectedtitles'           => array( 'KaitudPälkirjutesed' ),
	'Recentchanges'             => array( 'TantoižedToižetused' ),
	'Search'                    => array( 'Ectä' ),
	'Shortpages'                => array( 'LühüdadLehtpoled' ),
	'Specialpages'              => array( 'SpecialižedLehtpoled' ),
	'Statistics'                => array( 'Statistikad' ),
	'Upload'                    => array( 'Jügutoitta' ),
	'Userlogin'                 => array( 'KävutajanTulendnimi' ),
	'Userlogout'                => array( 'KävutajanLäntend' ),
	'Version'                   => array( 'Versii' ),
	'Wantedfiles'               => array( 'VarastadudFailad' ),
	'Watchlist'                 => array( 'KaclendNimikirjutez' ),
);

$magicWords = array(
	'img_right'                 => array( '1', 'oiged', 'paremal', 'right' ),
	'img_left'                  => array( '1', 'hura', 'vasakul', 'left' ),
	'img_none'                  => array( '1', 'eile', 'tühi', 'none' ),
	'img_width'                 => array( '1', '$1piks', '$1px' ),
	'img_border'                => array( '1', 'röun', 'ääris', 'border' ),
	'img_top'                   => array( '1', 'üläh', 'top' ),
	'img_middle'                => array( '1', 'kesk', 'middle' ),
	'img_bottom'                => array( '1', 'ala', 'bottom' ),
	'sitename'                  => array( '1', 'SAITANNIMI', 'KOHANIMI', 'SITENAME' ),
	'grammar'                   => array( '0', 'GRAMMATIK:', 'GRAMMAR:' ),
	'gender'                    => array( '0', 'SUGU:', 'GENDER:' ),
	'plural'                    => array( '0', 'ÄILUGU:', 'PLURAL:' ),
	'fullurl'                   => array( '0', 'TÄUZ\'URL:', 'KOGUURL:', 'FULLURL:' ),
	'index'                     => array( '1', '__INDEKS__', 'INDEKSIGA', '__INDEX__' ),
);

