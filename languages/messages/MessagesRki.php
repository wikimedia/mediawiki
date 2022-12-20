<?php
/** Arakanese (ရခိုင်)
 *
 * @file
 * @ingroup Languages
 *
 * @author YaThaWinTha
 */

$namespaceNames = [
	NS_MEDIA            => 'မီဒီယာ',
	NS_SPECIAL          => 'အထူး',
	NS_TALK             => 'ဆွီးနွီးချက်',
	NS_USER             => 'အသုံးပြုလူ',
	NS_USER_TALK        => 'အသုံးပြုလူ_ဆွီးနွီးချက်',
	NS_PROJECT_TALK     => '$1_ဆွီးနွီးချက်',
	NS_FILE             => 'ဖိုင်',
	NS_FILE_TALK        => 'ဖိုင်_ဆွီးနွီးချက်',
	NS_MEDIAWIKI        => 'မီဒီယာဝီကီ',
	NS_MEDIAWIKI_TALK   => 'မီဒီယာဝီကီ_ဆွီးနွီးချက်',
	NS_TEMPLATE         => 'တမ်းပလိတ်',
	NS_TEMPLATE_TALK    => 'တမ်းပလိတ်_ဆွီးနွီးချက်',
	NS_HELP             => 'အကူအညီ',
	NS_HELP_TALK        => 'အကူအညီ_ဆွီးနွီးချက်',
	NS_CATEGORY         => 'ကဏ္ဍ',
	NS_CATEGORY_TALK    => 'ကဏ္ဍ_ဆွီးနွီးချက်',
];

$digitTransformTable = [
	'0' => '၀',
	'1' => '၁',
	'2' => '၂',
	'3' => '၃',
	'4' => '၄',
	'5' => '၅',
	'6' => '၆',
	'7' => '၇',
	'8' => '၈',
	'9' => '၉',
];

$datePreferences = [
	'default',
	'my normal',
	'my long',
	'ISO 8601',
];

$defaultDateFormat = 'my normal';

$dateFormats = [
	'my normal time' => 'H:i',
	'my normal date' => 'j F Y',
	'my normal both' => ' H:i"၊" j F Y',

	'my long time' => 'H:i',
	'my long date' => 'Y F"လ" j "ရက်"',
	'my long both' => 'H:i"၊" Y F"လ" j "ရက်"',
];
