<?php
/** Igbo (Igbo)
 *
 * To improve a translation please visit https://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 */

$namespaceNames = [
	NS_MEDIA            => 'Midia',
	NS_SPECIAL          => 'Ihü_kárírí',
	NS_TALK             => 'Okwu',
	NS_USER             => 'Ọbanife',
	NS_USER_TALK        => 'Okwu_ọbanife',
	NS_PROJECT_TALK     => 'Okwu_$1',
	NS_FILE             => 'Usòrò',
	NS_FILE_TALK        => 'Okwu_usòrò',
	NS_MEDIAWIKI        => 'MidiaWiki',
	NS_MEDIAWIKI_TALK   => 'Okwu_MidiaWiki',
	NS_TEMPLATE         => 'Àtụ',
	NS_TEMPLATE_TALK    => 'Okwu_àtụ',
	NS_HELP             => 'Nkwadọ',
	NS_HELP_TALK        => 'Okwu_nkwadọ',
	NS_CATEGORY         => 'Òtù',
	NS_CATEGORY_TALK    => 'Okwu_òtù',
];

$namespaceAliases = [
	'Nká'                  => NS_MEDIA,
	'Ọ\'bànifé'            => NS_USER,
	'Okwu_ọ\'bànifé'       => NS_USER_TALK,
	'Ákwúkwó_orünotu'      => NS_FILE,
	'Okwu_ákwúkwó_orünotu' => NS_FILE_TALK,
	'NkáWiki'              => NS_MEDIAWIKI,
	'Okwu_NkáWiki'         => NS_MEDIAWIKI_TALK,
	'Nkwádọ'               => NS_HELP,
	'Okwu_nkwádọ'          => NS_HELP_TALK,
	'Ébéonọr'              => NS_CATEGORY,
	'Okwu_ébéonọr'         => NS_CATEGORY_TALK,
];

$specialPageAliases = [
	'Allpages'                  => [ 'IhüNílé' ],
	'Blankpage'                 => [ 'HịcháIhü' ],
	'Export'                    => [ 'MēKọFùtá' ],
	'Filepath'                  => [ 'UzọrAkwúkwóOrünotu' ],
	'Import'                    => [ 'BàÍfé' ],
	'Mypage'                    => [ 'IhüNkèm' ],
	'Mytalk'                    => [ 'OkwuNkèm' ],
	'Preferences'               => [ 'Ọtúm_dọsẹrẹ_ihem' ],
	'Specialpages'              => [ 'IhüKá' ],
	'Undelete'                  => [ 'Ábàkàshịkwàlà' ],
	'Upload'                    => [ 'TinyéIheNélú' ],
	'Userlogin'                 => [ 'Ọ\'bàniféÍBànyé' ],
	'Userlogout'                => [ 'Ọ\'bàniféÍFụtá' ],
];

$magicWords = [
	'redirect'                  => [ '0', '#KÚFÙ', '#REDIRECT' ],
	'fullpagename'              => [ '1', 'ÁHÀNÍLÉNKÈIHÜ', 'FULLPAGENAME' ],
	'msg'                       => [ '0', 'OZI:', 'MSG:' ],
	'img_right'                 => [ '1', 'áká_ịkẹngạ', 'right' ],
	'img_left'                  => [ '1', 'áká_èkpè', 'left' ],
	'img_top'                   => [ '1', 'élú', 'top' ],
	'img_middle'                => [ '1', 'ẹtítì', 'middle' ],
	'img_text_bottom'           => [ '1', 'okpúrù-ede', 'text-bottom' ],
	'displaytitle'              => [ '1', 'ZIÍSHÍ', 'DISPLAYTITLE' ],
	'pagesize'                  => [ '1', 'ÀSÁIHÜ', 'PAGESIZE' ],
];
