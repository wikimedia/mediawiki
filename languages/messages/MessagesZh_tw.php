<?php
/** Chinese (Taiwan) (‪中文(台灣)‬)
 *
 * To improve a translation please visit https://translatewiki.net
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
	'Allmessages'               => array( '所有訊息' ),
	'Ancientpages'              => array( '最舊頁面' ),
	'Block'                     => array( '封鎖使用者' ),
	'CreateAccount'             => array( '建立帳號' ),
	'FileDuplicateSearch'       => array( '搜尋重復檔案' ),
	'Invalidateemail'           => array( '無法識別的電郵位址' ),
	'LinkSearch'                => array( '搜尋網頁連結' ),
	'Listfiles'                 => array( '檔案清單' ),
	'Listredirects'             => array( '重新導向頁面清單' ),
	'Lockdb'                    => array( '鎖定資料庫' ),
	'MIMEsearch'                => array( 'MIME搜尋' ),
	'Newimages'                 => array( '新增檔案' ),
	'Randomredirect'            => array( '隨機重新導向頁面' ),
	'Recentchanges'             => array( '近期變動' ),
	'Revisiondelete'            => array( '刪除或恢復版本' ),
	'Unblock'                   => array( '解除封鎖' ),
	'Unlockdb'                  => array( '解除資料庫鎖定' ),
	'Unwatchedpages'            => array( '未被監視的頁面' ),
	'Userrights'                => array( '使用者權限' ),
	'Watchlist'                 => array( '監視清單' ),
	'Whatlinkshere'             => array( '連入頁面' ),
	'Withoutinterwiki'          => array( '沒有跨語言連結的頁面' ),
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

