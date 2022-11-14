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
	NS_SPECIAL          => 'बिसेस',
	NS_TALK             => 'बार्ता',
	NS_USER             => 'सदस्स',
	NS_USER_TALK        => 'सदस्स_बार्ता',
	NS_PROJECT_TALK     => '$1_बार्ता',
	NS_FILE             => 'सञ्चिका',
	NS_FILE_TALK        => 'सञ्चिका_बार्ता',
	NS_MEDIAWIKI        => 'मीडियाबिकि',
	NS_MEDIAWIKI_TALK   => 'मीडियाबिकि_बार्ता',
	NS_TEMPLATE         => 'साञ्चा',
	NS_TEMPLATE_TALK    => 'साञ्चा_बार्ता',
	NS_HELP             => 'सहायता',
	NS_HELP_TALK        => 'सहायता_बार्ता',
	NS_CATEGORY         => 'बर्ग',
	NS_CATEGORY_TALK    => 'बर्ग_बार्ता',
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

$digitGroupingPattern = "#,##,##0.###";

$linkTrail = "/^([a-z\x{0900}-\x{0963}\x{0966}-\x{A8E0}-\x{A8FF}]+)(.*)$/sDu";
