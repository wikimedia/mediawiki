<?php
/** Saraiki (Arabic script) (سرائیکی)
 *
 * To improve a translation please visit https://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Sraiki
 */

$fallback = 'ur, pnb';

$rtl = true;

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
	'%' => '٪', # U+066a
];

$namespaceNames = [
	NS_MEDIA            => 'میڈیا',
	NS_SPECIAL          => 'خاص',
	NS_TALK             => 'ڳالھ_مہاڑ',
	NS_USER             => 'ورتݨ_آلا',
	NS_USER_TALK        => 'ورتݨ_آلے_دی_ڳالھ_مہاڑ',
	NS_PROJECT_TALK     => '$1_ڳالھ_مہاڑ',
	NS_FILE             => 'فائل',
	NS_FILE_TALK        => 'فائل_ڳالھ_مہاڑ',
	NS_MEDIAWIKI        => 'میڈیا_وکی',
	NS_MEDIAWIKI_TALK   => 'میڈیا_وکی_ڳالھ_مہاڑ',
	NS_TEMPLATE         => 'سانچہ',
	NS_TEMPLATE_TALK    => 'سانچہ_ڳالھ_مہاڑ',
	NS_HELP             => 'مدد',
	NS_HELP_TALK        => 'مدد_ڳالھ_مہاڑ',
	NS_CATEGORY         => 'ونکی',
	NS_CATEGORY_TALK    => 'ونکی_ڳالھ_مہاڑ',
];

$linkTrail = "/^([ابپتٹثجچحخدڈذرڑزژسشصضطظعغفقکگلمنںوؤہھیئےآأءۃٻڄݙڋڰڳݨ]+)(.*)$/sDu";
