<?php
/** East Frisian (Oostfräisk)
 * @file
 * @ingroup Languages
 */

$fallback = 'de';

$namespaceNames = [
	NS_MEDIA            => 'Medium',
	NS_SPECIAL          => 'Spesjóól',
	NS_TALK             => 'Diskusjoon',
	NS_USER             => 'Benütser',
	NS_USER_TALK        => 'Benütser_diskusjoon',
	NS_PROJECT_TALK     => '$1_diskusjoon',
	NS_FILE             => 'Dóótäj',
	NS_FILE_TALK        => 'Dóótäj_diskusjoon',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki_diskusjoon',
	NS_TEMPLATE         => 'Föörlóóeğ',
	NS_TEMPLATE_TALK    => 'Föörlóóeğ_diskusjoon',
	NS_HELP             => 'Hüelp',
	NS_HELP_TALK        => 'Hüelp_diskusjoon',
	NS_CATEGORY         => 'Kategoorii',
	NS_CATEGORY_TALK    => 'Kategoorii_diskusjoon',
];

$linkTrail = '/^([a-zäöüßâêîóôûğ]+)(.*)$/sDu';

$dateFormats = [
	'dmy time' => 'H:i "üer"',
	'dmy date' => 'j. F Y',
	'dmy both' => 'j. F Y, H:i "üer"',
];
