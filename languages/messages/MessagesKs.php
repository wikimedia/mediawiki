<?php
/** Kashmiri (کٲشُر)
 *
 * @file
 * @ingroup Languages
 *
 * @author Rachitrali
 * @author Rk_kaul (on ks.wikipedia.org)
 * @author VibhasKS
 * @author Iflaq
 */

$rtl = true;

$namespaceNames = [
	NS_MEDIA            => 'میڈیا',
	NS_SPECIAL          => 'خاص',
	NS_TALK             => 'کَتھ',
	NS_USER             => 'رُکُن',
	NS_USER_TALK        => 'رُکُن_کَتھ',
	NS_PROJECT_TALK     => '$1_کَتھ',
	NS_FILE             => 'فَیِل',
	NS_FILE_TALK        => 'فَیِل_کَتھ',
	NS_MEDIAWIKI        => 'میٖڈیاوِکی',
	NS_MEDIAWIKI_TALK   => 'میٖڈیاوِکی_کَتھ',
	NS_TEMPLATE         => 'فرما',
	NS_TEMPLATE_TALK    => 'فرما_کَتھ',
	NS_HELP             => 'مَدَتھ',
	NS_HELP_TALK        => 'مَدَتھ_کَتھ',
	NS_CATEGORY         => 'زٲژ',
	NS_CATEGORY_TALK    => 'زٲژ_کَتھ',
];

// https://phabricator.wikimedia.org/T304790
$namespaceAliases = [
	'بَحَژ'          => NS_TALK,
	'رُکُن_بَحَژ'      => NS_USER_TALK,
	'$1_بَحَژ'       => NS_PROJECT_TALK,
	'فَیِل_بَحَژ'      => NS_FILE_TALK,
	'میڈیاوکی'     => NS_MEDIAWIKI,
	'میڈیاوکی_بَحَژ' => NS_MEDIAWIKI_TALK,
	'فرما_بَحَژ'     => NS_TEMPLATE_TALK,
	'پَلزُن'         => NS_HELP,
	'پَلزُن_بَحَژ'     => NS_HELP_TALK,
	'زٲژ_بَحَژ'      => NS_CATEGORY_TALK,
];

$digitTransformTable = [
	'0' => '٠', # U+0660
	'1' => '١', # U+0661
	'2' => '٢', # U+0662
	'3' => '٣', # U+0663
	'4' => '٤', # U+0664
	'5' => '٥', # U+0665
	'6' => '٦', # U+0666
	'7' => '٧', # U+0667
	'8' => '٨', # U+0668
	'9' => '٩', # U+0669
];

$separatorTransformTable = [
	'.' => '٫', # U+066B
	',' => '٬', # U+066C
];
