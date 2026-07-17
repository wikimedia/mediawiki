<?php
/** Magahi (मगही)
 *
 * @file
 * @ingroup Languages
 *
 * @author ऐक्टिवेटेड्
 */

$fallback = 'hi';
$namespaceNames = [
	NS_MEDIA            => 'मीडिया',
	NS_SPECIAL          => 'विशेष',
	NS_TALK             => 'वार्ता',
	NS_USER             => 'प्रयोगकर्ता',
	NS_USER_TALK        => 'प्रयोगकर्ता_वार्ता',
	NS_PROJECT_TALK     => '$1_वार्ता',
	NS_FILE             => 'सञ्चिका',
	NS_FILE_TALK        => 'सञ्चिका_वार्ता',
	NS_MEDIAWIKI        => 'मीडियाविकि',
	NS_MEDIAWIKI_TALK   => 'मीडियाविकि_वार्ता',
	NS_TEMPLATE         => 'साँचा',
	NS_TEMPLATE_TALK    => 'साँचा_वार्ता',
	NS_HELP             => 'सहयोग',
	NS_HELP_TALK        => 'सहयोग_वार्ता',
	NS_CATEGORY         => 'श्रेणी',
	NS_CATEGORY_TALK    => 'श्रेणी_वार्ता',
];

$digitTransformTable = [
	'0' => '०', # U+0966
	'1' => '१', # U+0967
	'2' => '२', # U+0968
	'3' => '३', # U+0969
	'4' => '४', # U+096A
	'5' => '५', # U+096B
	'6' => '६', # U+096C
	'7' => '७', # U+096D
	'8' => '८', # U+096E
	'9' => '९', # U+096F
];

$numberingSystem = 'deva';

$digitGroupingPattern = "#,##,##0.###";

$linkTrail = "/^([a-z\x{0900}-\x{0963}\x{0966}-\x{A8E0}-\x{A8FF}]+)(.*)$/sDu";
