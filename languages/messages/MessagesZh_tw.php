<?php
/** Chinese (Taiwan) (‪中文(台灣)‬)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Alexsh
 * @author Andrew971218
 * @author BobChao
 * @author Ianbu
 * @author Jidanni
 * @author Mark85296341
 * @author Pbdragonwang
 * @author PhiLiP
 * @author Roc michael
 * @author Shizhao
 * @author Urhixidur
 * @author Wong128hk
 * @author Zerng07
 * @author לערי ריינהארט
 */

$fallback = 'zh-hant, zh-hans';

$namespaceNames = array(
	NS_USER             => '使用者',
	NS_USER_TALK        => '使用者討論',
	NS_HELP             => '使用說明',
	NS_HELP_TALK        => '使用說明討論',
);

$namespaceAliases = array(
	'Image' => NS_FILE,
	'Image_talk' => NS_FILE_TALK,
	"圖片" => NS_FILE,
	"圖片討論" => NS_FILE_TALK,
);

$specialPageAliases = array(
	'Ancientpages'              => array( '最舊頁面' ),
	'Block'                     => array( '查封用戶' ),
	'FileDuplicateSearch'       => array( '搜索重復文件' ),
	'Invalidateemail'           => array( '無法識別的電郵地址' ),
	'LinkSearch'                => array( '搜索網頁鏈接' ),
	'Listredirects'             => array( '重定向頁面列表' ),
	'Lockdb'                    => array( '鎖定數據庫' ),
	'MIMEsearch'                => array( 'MIME搜索' ),
	'Randomredirect'            => array( '隨機重定向頁面' ),
	'Recentchanges'             => array( '近期變動' ),
	'Revisiondelete'            => array( '刪除或恢復版本' ),
	'Unblock'                   => array( '解除封鎖' ),
	'Unlockdb'                  => array( '解除數據庫鎖定' ),
	'Unwatchedpages'            => array( '未被監視的頁面' ),
	'Userrights'                => array( '用戶權限' ),
	'Withoutinterwiki'          => array( '沒有跨語言鏈接的頁面' ),
);

$datePreferences = array(
	'default',
	'minguo',
	'minguo shorttext',
	'minguo text',
	'minguo fulltext',
	'CNS 7648',
	'CNS 7648 compact',
	'ISO 8601',
);

$defaultDateFormat = 'zh';

$dateFormats = array(
	'zh time'                => 'H:i',
	'zh date'                => 'Y年n月j日 (l)',
	'zh both'                => 'Y年n月j日 (D) H:i',

	'minguo time'            => 'H:i',
	'minguo date'            => 'xoY年n月j日 (l)',
	'minguo both'            => 'xoY年n月j日 (D) H:i',

	'minguo shorttext time'  => 'H:i',
	'minguo shorttext date'  => '民xoY年n月j日 (l)',
	'minguo shorttext both'  => '民xoY年n月j日 (D) H:i',

	'minguo text time'       => 'H:i',
	'minguo text date'       => '民國xoY年n月j日 (l)',
	'minguo text both'       => '民國xoY年n月j日 (D) H:i',

	'minguo fulltext time'   => 'H:i',
	'minguo fulltext date'   => '中華民國xoY年n月j日 (l)',
	'minguo fulltext both'   => '中華民國xoY年n月j日 (D) H:i',

	'CNS 7648 time'          => 'H:i',
	'CNS 7648 date'          => '"R.O.C." xoY-m-d (l)',
	'CNS 7648 both'          => '"R.O.C." xoY-m-d (D) H:i',

	'CNS 7648 compact time'  => 'H:i',
	'CNS 7648 compact date'  => '"ROC" xoY-m-d (l)',
	'CNS 7648 compact both'  => '"ROC" xoY-m-d (D) H:i',
);

