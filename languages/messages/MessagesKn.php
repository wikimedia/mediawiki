<?php
/** Kannada (ಕನ್ನಡ)
 *
 * To improve a translation please visit https://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Akoppad
 * @author Ashwath Mattur <ashwatham@gmail.com> https://en.wikipedia.org/wiki/User:Ashwatham
 * @author Dimension10
 * @author Dipin
 * @author HPN
 * @author Hari Prasad Nadig <hpnadig@gmail.com> https://en.wikipedia.org/wiki/User:Hpnadig
 * @author Kaganer
 * @author Ktkaushik
 * @author M G Harish
 * @author Mana
 * @author NamwikiTL
 * @author Nayvik
 * @author Nk rahul14
 * @author Omshivaprakash
 * @author Prashwiki
 * @author Shankar
 * @author Shushruth
 * @author Teju2friends
 * @author The Evil IP address
 * @author VASANTH S.N.
 * @author לערי ריינהארט
 */

$namespaceNames = [
	NS_MEDIA            => 'ಮೀಡಿಯ',
	NS_SPECIAL          => 'ವಿಶೇಷ',
	NS_TALK             => 'ಚರ್ಚೆಪುಟ',
	NS_USER             => 'ಸದಸ್ಯ',
	NS_USER_TALK        => 'ಸದಸ್ಯರ_ಚರ್ಚೆಪುಟ',
	NS_PROJECT_TALK     => '$1_ಚರ್ಚೆ',
	NS_FILE             => 'ಚಿತ್ರ',
	NS_FILE_TALK        => 'ಚಿತ್ರ_ಚರ್ಚೆಪುಟ',
	NS_MEDIAWIKI        => 'ಮೀಡಿಯವಿಕಿ',
	NS_MEDIAWIKI_TALK   => 'ಮೀಡೀಯವಿಕಿ_ಚರ್ಚೆ',
	NS_TEMPLATE         => 'ಟೆಂಪ್ಲೇಟು',
	NS_TEMPLATE_TALK    => 'ಟೆಂಪ್ಲೇಟು_ಚರ್ಚೆ',
	NS_HELP             => 'ಸಹಾಯ',
	NS_HELP_TALK        => 'ಸಹಾಯ_ಚರ್ಚೆ',
	NS_CATEGORY         => 'ವರ್ಗ',
	NS_CATEGORY_TALK    => 'ವರ್ಗ_ಚರ್ಚೆ',
];

$digitTransformTable = [
	'0' => '೦', # U+0CE6
	'1' => '೧', # U+0CE7
	'2' => '೨', # U+0CE8
	'3' => '೩', # U+0CE9
	'4' => '೪', # U+0CEA
	'5' => '೫', # U+0CEB
	'6' => '೬', # U+0CEC
	'7' => '೭', # U+0CED
	'8' => '೮', # U+0CEE
	'9' => '೯', # U+0CEF
];

$digitGroupingPattern = "##,##,###";
