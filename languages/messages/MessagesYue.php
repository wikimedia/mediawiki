<?php
/** Cantonese (粵語)
 *
 * To improve a translation please visit https://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Anson2812
 * @author Antonytse
 * @author Horacewai2
 * @author Justincheng12345
 * @author Kaganer
 * @author KaiesTse
 * @author Mark85296341
 * @author Nemo bis
 * @author Simon Shek
 * @author Waihorace
 * @author William915
 * @author Wong128hk
 * @author Xiaomingyan
 * @author Yfdyh000
 */

$namespaceNames = [
	NS_MEDIA            => '媒體',
	NS_SPECIAL          => '特別',
	NS_TALK             => '傾偈',
	NS_USER             => '用戶',
	NS_USER_TALK        => '用戶傾偈',
	NS_PROJECT_TALK     => '$1傾偈',
	NS_FILE             => '文件',
	NS_FILE_TALK        => '文件傾偈',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki傾偈',
	NS_TEMPLATE         => '模',
	NS_TEMPLATE_TALK    => '模傾偈',
	NS_HELP             => '幫手',
	NS_HELP_TALK        => '幫手傾偈',
	NS_CATEGORY         => '分類',
	NS_CATEGORY_TALK    => '分類傾偈',
];

$namespaceAliases = [
	"媒体" 			=> NS_MEDIA,
	"特殊" 			=> NS_SPECIAL,
	"對話" 			=> NS_TALK,
	"对话" 			=> NS_TALK,
	"討論" 			=> NS_TALK,
	"讨论" 			=> NS_TALK,
	"用户" 			=> NS_USER,
	"用戶_對話" 		=> NS_USER_TALK,
	"用户_对话" 		=> NS_USER_TALK,
	"用戶_討論" 		=> NS_USER_TALK,
	"用户_讨论" 		=> NS_USER_TALK,
	'$1_傾偈'		=> NS_PROJECT_TALK,
	"檔" 			=> NS_FILE,
	"檔案" 			=> NS_FILE,
	"档" 			=> NS_FILE,
	"档案" 			=> NS_FILE,
	"圖" 			=> NS_FILE,
	"圖像" 			=> NS_FILE,
	"图" 			=> NS_FILE,
	"图像" 			=> NS_FILE,
	'Image'                 => NS_FILE,
	'Image_talk'            => NS_FILE_TALK,
	"檔_討論" 		=> NS_FILE_TALK,
	"档_讨论" 		=> NS_FILE_TALK,
	"檔案_討論" 		=> NS_FILE_TALK,
	"档案_讨论" 		=> NS_FILE_TALK,
	"圖_討論" 		=> NS_FILE_TALK,
	"图_讨论" 		=> NS_FILE_TALK,
	"圖像_討論" 		=> NS_FILE_TALK,
	"图像_讨论" 		=> NS_FILE_TALK,
	'MediaWiki_傾偈'	=> NS_FILE_TALK,
	"模_討論" 		=> NS_TEMPLATE_TALK,
	"模_讨论" 		=> NS_TEMPLATE_TALK,
	"幫助" 			=> NS_HELP,
	"說明" 			=> NS_HELP,
	"帮手" 			=> NS_HELP,
	"帮助" 			=> NS_HELP,
	"说明" 			=> NS_HELP,
	"幫手_討論" 		=> NS_HELP_TALK,
	"幫助_討論" 		=> NS_HELP_TALK,
	"說明_討論" 		=> NS_HELP_TALK,
	"帮手_讨论" 		=> NS_HELP_TALK,
	"帮助_讨论" 		=> NS_HELP_TALK,
	"说明_讨论" 		=> NS_HELP_TALK,
	"類" 			=> NS_CATEGORY,
	"类" 			=> NS_CATEGORY,
	"分类" 			=> NS_CATEGORY,
	"類_討論" 		=> NS_CATEGORY_TALK,
	"分類_討論" 		=> NS_CATEGORY_TALK,
	"类_讨论" 		=> NS_CATEGORY_TALK,
	"分类_讨论" 		=> NS_CATEGORY_TALK,
];

$specialPageAliases = [
	'Activeusers'               => [ '活躍用戶名單' ],
	'Allmessages'               => [ '系統信息' ],
	'Allpages'                  => [ '所有頁' ],
	'Ancientpages'              => [ '舊版' ],
	'Blankpage'                 => [ '空版' ],
	'Block'                     => [ '封' ],
	'Booksources'               => [ '書本來源' ],
	'BrokenRedirects'           => [ '斷鏈' ],
	'Categories'                => [ '分類' ],
	'ChangePassword'            => [ '改密碼' ],
	'ComparePages'              => [ '比較頁面' ],
	'Confirmemail'              => [ '確認電郵' ],
	'Contributions'             => [ '貢獻' ],
	'CreateAccount'             => [ '開戶' ],
	'Deadendpages'              => [ '掘頭頁' ],
	'DeletedContributions'      => [ '刪咗嘅貢獻' ],
	'DoubleRedirects'           => [ '雙重跳轉' ],
	'EditWatchlist'             => [ '改監視清單' ],
	'Emailuser'                 => [ '電郵用戶' ],
	'Export'                    => [ '匯出' ],
	'Fewestrevisions'           => [ '最少修訂版本' ],
	'FileDuplicateSearch'       => [ '搵重複文件' ],
	'Filepath'                  => [ '檔案路徑' ],
	'Import'                    => [ '匯入' ],
	'Invalidateemail'           => [ '錯電郵' ],
	'BlockList'                 => [ '封咗嘅列表' ],
	'LinkSearch'                => [ '搵連結' ],
	'Listadmins'                => [ '管理員列表' ],
	'Listbots'                  => [ '機械人列表' ],
	'Listfiles'                 => [ '檔案列表' ],
	'Listgrouprights'           => [ '用戶組權限' ],
	'Listredirects'             => [ '重定向列表' ],
	'Listusers'                 => [ '用戶列表' ],
	'Lockdb'                    => [ '鎖資料庫' ],
	'Log'                       => [ '日誌' ],
	'Lonelypages'               => [ '無鏈頁面' ],
	'Longpages'                 => [ '長頁' ],
	'MergeHistory'              => [ '合併歷史' ],
	'MIMEsearch'                => [ 'MIME搜索' ],
	'Mostcategories'            => [ '最多分類' ],
	'Mostimages'                => [ '最多鏈嘅檔案' ],
	'Mostlinked'                => [ '最多鏈嘅頁' ],
	'Mostlinkedcategories'      => [ '最多鏈嘅分類' ],
	'Mostlinkedtemplates'       => [ '最多鏈嘅模' ],
	'Mostrevisions'             => [ '最多版本' ],
	'Movepage'                  => [ '搬頁' ],
	'Mycontributions'           => [ '我嘅貢獻' ],
	'MyLanguage'                => [ '我個話' ],
	'Mypage'                    => [ '我嘅頁面' ],
	'Mytalk'                    => [ '我嘅傾偈' ],
	'Myuploads'                 => [ '我嘅上傳' ],
	'Newimages'                 => [ '新文件' ],
	'Newpages'                  => [ '新版' ],
	'PasswordReset'             => [ '重設密碼' ],
	'PermanentLink'             => [ '永久鏈' ],
	'Preferences'               => [ '喜好設定' ],
	'Prefixindex'               => [ '全部頁嘅前綴' ],
	'Protectedpages'            => [ '保護頁' ],
	'Protectedtitles'           => [ '保護咗嘅標題' ],
	'Randompage'                => [ '是但一版' ],
	'Randomredirect'            => [ '是但一個跳轉' ],
	'Recentchanges'             => [ '最近修改' ],
	'Recentchangeslinked'       => [ '外鏈修改' ],
	'Revisiondelete'            => [ '修訂版本刪除' ],
	'Search'                    => [ '搜索' ],
	'Shortpages'                => [ '短版' ],
	'Specialpages'              => [ '專門版' ],
	'Statistics'                => [ '統計' ],
	'Tags'                      => [ '標籤' ],
	'Unblock'                   => [ '解封' ],
	'Uncategorizedcategories'   => [ '無樓上嘅分類' ],
	'Uncategorizedimages'       => [ '無分類嘅檔案' ],
	'Uncategorizedpages'        => [ '無分類嘅頁' ],
	'Uncategorizedtemplates'    => [ '無分類嘅模' ],
	'Undelete'                  => [ '反刪除' ],
	'Unlockdb'                  => [ '解鎖資料庫' ],
	'Unusedcategories'          => [ '未用分類' ],
	'Unusedimages'              => [ '未用檔案' ],
	'Unusedtemplates'           => [ '未用模' ],
	'Unwatchedpages'            => [ '無人監視嘅版' ],
	'Upload'                    => [ '上傳' ],
	'Userlogin'                 => [ '簽到' ],
	'Userlogout'                => [ '簽走' ],
	'Userrights'                => [ '用戶權限' ],
	'Version'                   => [ '版本' ],
	'Wantedcategories'          => [ '要求嘅分類' ],
	'Wantedfiles'               => [ '要求嘅文件' ],
	'Wantedpages'               => [ '要求嘅頁面' ],
	'Wantedtemplates'           => [ '要求嘅模' ],
	'Watchlist'                 => [ '監視清單' ],
	'Whatlinkshere'             => [ '邊度鏈去呢版' ],
	'Withoutinterwiki'          => [ '無連去其他話嘅版' ],
];

$bookstoreList = [
	'AddALL' => 'http://www.addall.com/New/Partner.cgi?query=$1&type=ISBN',
	'Barnes & Noble' => 'http://search.barnesandnoble.com/bookSearch/isbnInquiry.asp?isbn=$1',
	'亞馬遜' => 'http://www.amazon.com/exec/obidos/ISBN=$1',
	'博客來書店' => 'http://www.books.com.tw/exep/prod/booksfile.php?item=$1',
	'三民書店' => 'http://www.sanmin.com.tw/page-qsearch.asp?ct=search_isbn&qu=$1',
	'天下書店' => 'http://www.cwbook.com.tw/search/result1.jsp?field=2&keyWord=$1',
	'新絲路書店' => 'http://www.silkbook.com/function/Search_list_book_data.asp?item=5&text=$1'
];

$datePreferences = [
	'default',
	'yue dmy',
	'yue mdy',
	'yue ymd',
	'ISO 8601',
];

$defaultDateFormat = 'yue';

$dateFormats = [
	'yue time' => 'H:i',
	'yue date' => 'Y年n月j號 (l)',
	'yue both' => 'Y年n月j號 (D) H:i',

	'yue dmy time' => 'H:i',
	'yue dmy date' => 'j-n-Y',
	'yue dmy both' => 'j-n-Y H:i',

	'yue mdy time' => 'H:i',
	'yue mdy date' => 'n-j-Y',
	'yue mdy both' => 'n-j-Y H:i',

	'yue ymd time' => 'H:i',
	'yue ymd date' => 'Y-n-j',
	'yue ymd both' => 'Y-n-j H:i',
];

