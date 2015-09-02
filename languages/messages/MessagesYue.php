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

$namespaceNames = array(
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
);

$namespaceAliases = array(
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
);

$specialPageAliases = array(
	'Activeusers'               => array( '活躍用戶名單' ),
	'Allmessages'               => array( '系統信息' ),
	'Allpages'                  => array( '所有頁' ),
	'Ancientpages'              => array( '舊版' ),
	'Blankpage'                 => array( '空版' ),
	'Block'                     => array( '封' ),
	'Booksources'               => array( '書本來源' ),
	'BrokenRedirects'           => array( '斷鏈' ),
	'Categories'                => array( '分類' ),
	'ChangePassword'            => array( '改密碼' ),
	'ComparePages'              => array( '比較頁面' ),
	'Confirmemail'              => array( '確認電郵' ),
	'Contributions'             => array( '貢獻' ),
	'CreateAccount'             => array( '開戶' ),
	'Deadendpages'              => array( '掘頭頁' ),
	'DeletedContributions'      => array( '刪咗嘅貢獻' ),
	'DoubleRedirects'           => array( '雙重跳轉' ),
	'EditWatchlist'             => array( '改監視清單' ),
	'Emailuser'                 => array( '電郵用戶' ),
	'Export'                    => array( '匯出' ),
	'Fewestrevisions'           => array( '最少修訂版本' ),
	'FileDuplicateSearch'       => array( '搵重複文件' ),
	'Filepath'                  => array( '檔案路徑' ),
	'Import'                    => array( '匯入' ),
	'Invalidateemail'           => array( '錯電郵' ),
	'BlockList'                 => array( '封咗嘅列表' ),
	'LinkSearch'                => array( '搵連結' ),
	'Listadmins'                => array( '管理員列表' ),
	'Listbots'                  => array( '機械人列表' ),
	'Listfiles'                 => array( '檔案列表' ),
	'Listgrouprights'           => array( '用戶組權限' ),
	'Listredirects'             => array( '重定向列表' ),
	'Listusers'                 => array( '用戶列表' ),
	'Lockdb'                    => array( '鎖資料庫' ),
	'Log'                       => array( '日誌' ),
	'Lonelypages'               => array( '無鏈頁面' ),
	'Longpages'                 => array( '長頁' ),
	'MergeHistory'              => array( '合併歷史' ),
	'MIMEsearch'                => array( 'MIME搜索' ),
	'Mostcategories'            => array( '最多分類' ),
	'Mostimages'                => array( '最多鏈嘅檔案' ),
	'Mostlinked'                => array( '最多鏈嘅頁' ),
	'Mostlinkedcategories'      => array( '最多鏈嘅分類' ),
	'Mostlinkedtemplates'       => array( '最多鏈嘅模' ),
	'Mostrevisions'             => array( '最多版本' ),
	'Movepage'                  => array( '搬頁' ),
	'Mycontributions'           => array( '我嘅貢獻' ),
	'MyLanguage'                => array( '我個話' ),
	'Mypage'                    => array( '我嘅頁面' ),
	'Mytalk'                    => array( '我嘅傾偈' ),
	'Myuploads'                 => array( '我嘅上傳' ),
	'Newimages'                 => array( '新文件' ),
	'Newpages'                  => array( '新版' ),
	'PasswordReset'             => array( '重設密碼' ),
	'PermanentLink'             => array( '永久鏈' ),
	'Preferences'               => array( '喜好設定' ),
	'Prefixindex'               => array( '全部頁嘅前綴' ),
	'Protectedpages'            => array( '保護頁' ),
	'Protectedtitles'           => array( '保護咗嘅標題' ),
	'Randompage'                => array( '是但一版' ),
	'Randomredirect'            => array( '是但一個跳轉' ),
	'Recentchanges'             => array( '最近修改' ),
	'Recentchangeslinked'       => array( '外鏈修改' ),
	'Revisiondelete'            => array( '修訂版本刪除' ),
	'Search'                    => array( '搜索' ),
	'Shortpages'                => array( '短版' ),
	'Specialpages'              => array( '專門版' ),
	'Statistics'                => array( '統計' ),
	'Tags'                      => array( '標籤' ),
	'Unblock'                   => array( '解封' ),
	'Uncategorizedcategories'   => array( '無樓上嘅分類' ),
	'Uncategorizedimages'       => array( '無分類嘅檔案' ),
	'Uncategorizedpages'        => array( '無分類嘅頁' ),
	'Uncategorizedtemplates'    => array( '無分類嘅模' ),
	'Undelete'                  => array( '反刪除' ),
	'Unlockdb'                  => array( '解鎖資料庫' ),
	'Unusedcategories'          => array( '未用分類' ),
	'Unusedimages'              => array( '未用檔案' ),
	'Unusedtemplates'           => array( '未用模' ),
	'Unwatchedpages'            => array( '無人監視嘅版' ),
	'Upload'                    => array( '上傳' ),
	'Userlogin'                 => array( '簽到' ),
	'Userlogout'                => array( '簽走' ),
	'Userrights'                => array( '用戶權限' ),
	'Version'                   => array( '版本' ),
	'Wantedcategories'          => array( '要求嘅分類' ),
	'Wantedfiles'               => array( '要求嘅文件' ),
	'Wantedpages'               => array( '要求嘅頁面' ),
	'Wantedtemplates'           => array( '要求嘅模' ),
	'Watchlist'                 => array( '監視清單' ),
	'Whatlinkshere'             => array( '邊度鏈去呢版' ),
	'Withoutinterwiki'          => array( '無連去其他話嘅版' ),
);

$bookstoreList = array(
	'AddALL' => 'http://www.addall.com/New/Partner.cgi?query=$1&type=ISBN',
	'Barnes & Noble' => 'http://search.barnesandnoble.com/bookSearch/isbnInquiry.asp?isbn=$1',
	'亞馬遜' => 'http://www.amazon.com/exec/obidos/ISBN=$1',
	'博客來書店' => 'http://www.books.com.tw/exep/prod/booksfile.php?item=$1',
	'三民書店' => 'http://www.sanmin.com.tw/page-qsearch.asp?ct=search_isbn&qu=$1',
	'天下書店' => 'http://www.cwbook.com.tw/search/result1.jsp?field=2&keyWord=$1',
	'新絲路書店' => 'http://www.silkbook.com/function/Search_list_book_data.asp?item=5&text=$1'
);

$datePreferences = array(
	'default',
	'yue dmy',
	'yue mdy',
	'yue ymd',
	'ISO 8601',
);

$defaultDateFormat = 'yue';

$dateFormats = array(
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
);

