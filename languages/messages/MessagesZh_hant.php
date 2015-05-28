<?php
/** Traditional Chinese (中文（繁體）‎)
 *
 * To improve a translation please visit https://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Alexsh
 * @author Anakmalaysia
 * @author Andrew971218
 * @author Bencmq
 * @author Breawycker
 * @author Byfserag
 * @author Ch.Andrew
 * @author Cwlin0416
 * @author Danny0838
 * @author FireJackey
 * @author Frankou
 * @author Gakmo
 * @author Gaoxuewei
 * @author Hakka
 * @author Horacewai2
 * @author Hydra
 * @author Hzy980512
 * @author Jidanni
 * @author Jimmy xu wrk
 * @author Justincheng12345
 * @author Kaganer
 * @author KaiesTse
 * @author Kuailong
 * @author Lauhenry
 * @author Liangent
 * @author Liflon
 * @author Littletung
 * @author Liuxinyu970226
 * @author Mark85296341
 * @author Oapbtommy
 * @author Openerror
 * @author Pbdragonwang
 * @author PhiLiP
 * @author Philip
 * @author Radish10cm
 * @author Shinjiman
 * @author Shirayuki
 * @author Shizhao
 * @author Simon Shek
 * @author Skjackey tse
 * @author StephDC
 * @author Waihorace
 * @author Wmr89502270
 * @author Wong128hk
 * @author Wrightbus
 * @author Xiaomingyan
 * @author Yfdyh000
 * @author Yuyu
 * @author 乌拉跨氪
 */

$fallback = 'zh-hans';

$fallback8bitEncoding = 'windows-950';

$namespaceNames = array(
	NS_MEDIA            => '媒體',
	NS_SPECIAL          => '特殊',
	NS_TALK             => '討論',
	NS_USER             => '用戶',
	NS_USER_TALK        => '用戶討論',
	NS_PROJECT_TALK     => '$1討論',
	NS_FILE             => '檔案',
	NS_FILE_TALK        => '檔案討論',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki討論',
	NS_TEMPLATE         => '模板',
	NS_TEMPLATE_TALK    => '模板討論',
	NS_HELP             => '幫助',
	NS_HELP_TALK        => '幫助討論',
	NS_CATEGORY         => '分類',
	NS_CATEGORY_TALK    => '分類討論',
);

$namespaceAliases = array(
	"媒體" => NS_MEDIA,
	"特殊" => NS_SPECIAL,
	"對話" => NS_TALK,
	"討論" => NS_TALK,
	"用戶" => NS_USER,
	"用戶對話" => NS_USER_TALK,
	"用戶討論" => NS_USER_TALK,
	# This has never worked so it's unlikely to annoy anyone if I disable it -- TS
	# "{{SITENAME}}_對話" => NS_PROJECT_TALK
	"圖像" => NS_FILE,
	"檔案" => NS_FILE,
	"文件" => NS_FILE,
	'Image' => NS_FILE,
	'Image_talk' => NS_FILE_TALK,
	"圖像對話" => NS_FILE_TALK,
	"圖像討論" => NS_FILE_TALK,
	"檔案對話" => NS_FILE_TALK,
	"檔案討論" => NS_FILE_TALK,
	"文件對話" => NS_FILE_TALK,
	"文件討論" => NS_FILE_TALK,
	"樣板" => NS_TEMPLATE,
	"模板" => NS_TEMPLATE,
	"樣板對話" => NS_TEMPLATE_TALK,
	"樣板討論" => NS_TEMPLATE_TALK,
	"模板對話" => NS_TEMPLATE_TALK,
	"模板討論" => NS_TEMPLATE_TALK,
	"幫助" => NS_HELP,
	"幫助對話" => NS_HELP_TALK,
	"幫助討論" => NS_HELP_TALK,
	"分類" => NS_CATEGORY,
	"分類對話" => NS_CATEGORY_TALK,
	"分類討論" => NS_CATEGORY_TALK,
);

$specialPageAliases = array(
	'Activeusers'               => array( '活躍使用者' ),
	'Allmessages'               => array( '所有訊息' ),
	'AllMyUploads'              => array( '所有我的上傳', '所有我的檔案', '所有本人上載', '所有本人檔案' ),
	'Allpages'                  => array( '所有頁面' ),
	'ApiHelp'                   => array( 'Api使用說明' ),
	'Ancientpages'              => array( '最舊頁面', '最早頁面' ),
	'Badtitle'                  => array( '無效標題' ),
	'Blankpage'                 => array( '空白頁面' ),
	'Block'                     => array( '封鎖', '封鎖IP', '封鎖使用者', '封禁', '封禁IP', '封禁使用者' ),
	'Booksources'               => array( '書籍來源', '網路書源' ),
	'BrokenRedirects'           => array( '損壞的重新導向', '損壞的重定向頁' ),
	'Categories'                => array( '分類', '頁面分類' ),
	'ChangeEmail'               => array( '更改信箱', '修改郵箱' ),
	'ChangePassword'            => array( '更改密碼', '修改密碼', '密碼重設' ),
	'ComparePages'              => array( '頁面比較' ),
	'Confirmemail'              => array( '確認信箱', '確認電郵' ),
	'Contributions'             => array( '使用者貢獻', '用戶貢獻' ),
	'CreateAccount'             => array( '建立帳號', '建立帳戶' ),
	'Deadendpages'              => array( '無連結頁面', '斷鏈頁面' ),
	'DeletedContributions'      => array( '已刪除的貢獻', '已刪除的用戶貢獻' ),
	'Diff'                      => array( '編輯差異' ),
	'DoubleRedirects'           => array( '雙重的重新導向', '雙重重定向頁面' ),
	'EditWatchlist'             => array( '編輯監視清單', '編輯監視列表' ),
	'Emailuser'                 => array( '寄信給使用者', '寄信', '電郵使用者' ),
	'ExpandTemplates'           => array( '展開模板' ),
	'Export'                    => array( '匯出', '匯出頁面' ),
	'Fewestrevisions'           => array( '最少修訂頁面' ),
	'FileDuplicateSearch'       => array( '重複檔案搜尋', '搜尋重複檔案' ),
	'Filepath'                  => array( '檔案路徑' ),
	'Import'                    => array( '匯入', '匯入頁面' ),
	'Invalidateemail'           => array( '無效的信箱' ),
	'JavaScriptTest'            => array( 'JavaScript測試' ),
	'BlockList'                 => array( '封鎖清單', 'IP封鎖清單', '封禁列表', 'IP封禁列表' ),
	'LinkSearch'                => array( '連結搜尋', '搜尋網頁連結' ),
	'Listadmins'                => array( '管理員清單', '管理員列表' ),
	'Listbots'                  => array( '機器人清單', '機械人列表' ),
	'Listfiles'                 => array( '檔案清單', '圖片清單', '檔案列表', '圖像列表' ),
	'Listgrouprights'           => array( '群組權限清單', '使用者群組權限', '群組權限列表' ),
	'Listredirects'             => array( '重新導向清單', '重定向頁面列表' ),
	'ListDuplicatedFiles'       => array( '重複檔案清單', '重複檔案列表' ),
	'Listusers'                 => array( '使用者清單', '使用者列表' ),
	'Lockdb'                    => array( '鎖定資料庫', '鎖定數據庫' ),
	'Log'                       => array( '日誌' ),
	'Lonelypages'               => array( '孤立頁面' ),
	'Longpages'                 => array( '過長的頁面', '長頁面' ),
	'MediaStatistics'           => array( '媒體統計' ),
	'MergeHistory'              => array( '合併歷史' ),
	'MIMEsearch'                => array( 'MIME搜尋' ),
	'Mostcategories'            => array( '最多分類的頁面', '最多分類頁面' ),
	'Mostimages'                => array( '被連結最多的檔案', '最多連結檔案' ),
	'Mostinterwikis'            => array( '最多_Interwiki_連結的頁面', '最多跨wiki連結' ),
	'Mostlinked'                => array( '被連結最多的頁面', '最多連結頁面' ),
	'Mostlinkedcategories'      => array( '被連結最多的分類', '最多連結分類' ),
	'Mostlinkedtemplates'       => array( '被引用最多的頁面', '被連結最多的模板', '被使用最多的模板' ),
	'Mostrevisions'             => array( '最多修訂的頁面', '最多修訂頁面' ),
	'Movepage'                  => array( '移動頁面' ),
	'Mycontributions'           => array( '我的貢獻' ),
	'MyLanguage'                => array( '我的語言' ),
	'Mypage'                    => array( '我的使用者頁面', '我的用戶頁' ),
	'Mytalk'                    => array( '我的對話', '我的討論頁' ),
	'Myuploads'                 => array( '我的上傳', '我的上載', '我的檔案' ),
	'Newimages'                 => array( '新增檔案', '新增圖片' ),
	'Newpages'                  => array( '新增頁面', '新頁面' ),
	'PagesWithProp'             => array( '擁有屬性的頁面', '帶屬性頁面' ),
	'PageLanguage'              => array( '頁面語言' ),
	'PasswordReset'             => array( '重設密碼' ),
	'PermanentLink'             => array( '靜態連結', '永久連結' ),
	'Preferences'               => array( '偏好設定' ),
	'Prefixindex'               => array( '字首索引', '前綴索引' ),
	'Protectedpages'            => array( '受保護頁面', '已保護頁面' ),
	'Protectedtitles'           => array( '受保護標題', '已保護標題' ),
	'Randompage'                => array( '隨機頁面' ),
	'RandomInCategory'          => array( '隨機分類頁面', '於分類中隨機' ),
	'Randomredirect'            => array( '隨機重新導向', '隨機重定向頁面' ),
	'Recentchanges'             => array( '最近變更', '最近更改' ),
	'Recentchangeslinked'       => array( '已連結的最近變更', '相關變更', '連出更改' ),
	'Redirect'                  => array( '重新導向', '重定向' ),
	'ResetTokens'               => array( '重設密鑰', '覆寫令牌' ),
	'Revisiondelete'            => array( '修訂刪除', '刪除或恢復版本' ),
	'RunJobs'                   => array( '執行作業', '運行工作' ),
	'Search'                    => array( '搜尋' ),
	'Shortpages'                => array( '過短的頁面', '短頁面' ),
	'Specialpages'              => array( '特殊頁面' ),
	'Statistics'                => array( '統計資訊' ),
	'Tags'                      => array( '標籤' ),
	'TrackingCategories'        => array( '追蹤分類', '跟蹤分類' ),
	'Unblock'                   => array( '解除封鎖', '解除封禁', '解禁' ),
	'Uncategorizedcategories'   => array( '未分類的分類', '未歸類分類' ),
	'Uncategorizedimages'       => array( '未分類的檔案', '未分類的圖片', '未歸類檔案' ),
	'Uncategorizedpages'        => array( '未分類的頁面', '未歸類頁面' ),
	'Uncategorizedtemplates'    => array( '未分類的模板', '未歸類模板' ),
	'Undelete'                  => array( '取消刪除' ),
	'Unlockdb'                  => array( '解除鎖定資料庫', '解除資料庫鎖定' ),
	'Unusedcategories'          => array( '未使用的分類', '未使用分類' ),
	'Unusedimages'              => array( '未使用的檔案', '未使用檔案' ),
	'Unusedtemplates'           => array( '未使用的模板', '未使用模板' ),
	'Unwatchedpages'            => array( '未監視的頁面', '未被監視的頁面' ),
	'Upload'                    => array( '上傳', '上載檔案' ),
	'UploadStash'               => array( '上傳儲藏庫' ),
	'Userlogin'                 => array( '使用者登入' ),
	'Userlogout'                => array( '使用者登出' ),
	'Userrights'                => array( '使用者權限' ),
	'Version'                   => array( '版本', '版本資訊' ),
	'Wantedcategories'          => array( '需要的分類', '待撰分類' ),
	'Wantedfiles'               => array( '需要的檔案' ),
	'Wantedpages'               => array( '需要的頁面', '待撰頁面' ),
	'Wantedtemplates'           => array( '需要的模板' ),
	'Watchlist'                 => array( '監視清單' ),
	'Whatlinkshere'             => array( '連入頁面' ),
	'Withoutinterwiki'          => array( '無跨wiki連結頁面', '無跨維基連結頁面' ),
);

$magicWords = array(
	'redirect'                  => array( '0', '#重新導向', '#重定向', '#REDIRECT' ),
	'notoc'                     => array( '0', '__無目錄__', '__无目录__', '__NOTOC__' ),
	'nogallery'                 => array( '0', '__無圖庫__', '__无图库__', '__NOGALLERY__' ),
	'forcetoc'                  => array( '0', '__強制目錄__', '__强显目录__', '__FORCETOC__' ),
	'toc'                       => array( '0', '__目錄__', '__目录__', '__TOC__' ),
	'noeditsection'             => array( '0', '__無段落編輯__', '__无编辑段落__', '__无段落编辑__', '__NOEDITSECTION__' ),
	'currentmonth'              => array( '1', '本月', '本月2', 'CURRENTMONTH', 'CURRENTMONTH2' ),
	'currentmonthabbrev'        => array( '1', '本月縮寫', '本月简称', 'CURRENTMONTHABBREV' ),
	'currentday'                => array( '1', '今天', 'CURRENTDAY' ),
	'currenttime'               => array( '1', '目前時間', '当前时间', '此时', 'CURRENTTIME' ),
	'numberofpages'             => array( '1', '頁面數', '页面数', 'NUMBEROFPAGES' ),
	'numberofarticles'          => array( '1', '文章數', '条目数', 'NUMBEROFARTICLES' ),
	'numberoffiles'             => array( '1', '檔案數', '文件数', 'NUMBEROFFILES' ),
	'numberofusers'             => array( '1', '使用者人數量', '用户数', 'NUMBEROFUSERS' ),
	'numberofactiveusers'       => array( '1', '活躍使用者人數', '活跃用户数', 'NUMBEROFACTIVEUSERS' ),
	'pagename'                  => array( '1', '頁面名稱', '页名', '页面名', '页面名称', 'PAGENAME' ),
	'namespace'                 => array( '1', '命名空間', '名字空间', 'NAMESPACE' ),
	'namespacenumber'           => array( '1', '命名空間數', '名字空间编号', 'NAMESPACENUMBER' ),
	'talkspace'                 => array( '1', '對話空間', '讨论空间', '讨论名字空间', 'TALKSPACE' ),
	'rootpagename'              => array( '1', '根頁面名稱', 'ROOTPAGENAME' ),
	'rootpagenamee'             => array( '1', '根頁面名稱E', 'ROOTPAGENAMEE' ),
	'msg'                       => array( '0', '訊息:', 'MSG:' ),
	'subst'                     => array( '0', '替換:', '替代:', 'SUBST:' ),
	'safesubst'                 => array( '0', '安全替換:', '安全替代:', 'SAFESUBST:' ),
	'img_thumbnail'             => array( '1', '縮圖', '缩略图', 'thumbnail', 'thumb' ),
	'img_manualthumb'           => array( '1', '縮圖=$1', '缩略图=$1', 'thumbnail=$1', 'thumb=$1' ),
	'img_right'                 => array( '1', '右', 'right' ),
	'img_left'                  => array( '1', '左', 'left' ),
	'img_none'                  => array( '1', '無', '无', 'none' ),
	'img_width'                 => array( '1', '$1像素', '$1px' ),
	'img_center'                => array( '1', '置中', '居中', 'center', 'centre' ),
	'img_framed'                => array( '1', '有框', 'framed', 'enframed', 'frame' ),
	'img_frameless'             => array( '1', '無框', '无框', 'frameless' ),
	'img_lang'                  => array( '1', '語言=$1', 'lang=$1' ),
	'img_page'                  => array( '1', '頁=$1', '$1頁', '页数=$1', '$1页', 'page=$1', 'page $1' ),
	'img_border'                => array( '1', '邊框', '边框', 'border' ),
	'img_sub'                   => array( '1', '下標', 'sub' ),
	'img_super'                 => array( '1', '上標', 'super', 'sup' ),
	'img_top'                   => array( '1', '垂直置頂', 'top' ),
	'img_text_top'              => array( '1', '文字置頂', 'text-top' ),
	'img_middle'                => array( '1', '垂直置中', 'middle' ),
	'img_bottom'                => array( '1', '垂直置底', 'bottom' ),
	'img_text_bottom'           => array( '1', '文字置底', 'text-bottom' ),
	'img_link'                  => array( '1', '連結=$1', '链接=$1', 'link=$1' ),
	'img_alt'                   => array( '1', '替代文字', '替代=$1', '替代文本=$1', 'alt=$1' ),
	'img_class'                 => array( '1', '類別=$1', '类=$1', 'class=$1' ),
	'sitename'                  => array( '1', '網站名稱', '站点名称', 'SITENAME' ),
	'ns'                        => array( '0', '命名空間:', '名字空间:', 'NS:' ),
	'nse'                       => array( '0', '命名空間E:', '名字空间E:', 'NSE:' ),
	'localurl'                  => array( '0', '本地URL:', 'LOCALURL:' ),
	'localurle'                 => array( '0', '本地URLE:', 'LOCALURLE:' ),
	'pageid'                    => array( '0', '頁面ID', '页面ID', 'PAGEID' ),
	'server'                    => array( '0', '伺服器', '服务器', 'SERVER' ),
	'servername'                => array( '0', '伺服器名稱', '服务器名', 'SERVERNAME' ),
	'gender'                    => array( '0', '性別:', '性:', '性别:', 'GENDER:' ),
	'notitleconvert'            => array( '0', '__不轉換標題__', '__不转换标题__', '__NOTITLECONVERT__', '__NOTC__' ),
	'nocontentconvert'          => array( '0', '__不轉換內容__', '__不转换内容__', '__NOCONTENTCONVERT__', '__NOCC__' ),
	'revisionuser'              => array( '1', '修訂使用者', 'REVISIONUSER' ),
	'displaytitle'              => array( '1', '顯示標題', '显示标题', 'DISPLAYTITLE' ),
	'currentversion'            => array( '1', '目前版本', '当前版本', 'CURRENTVERSION' ),
	'language'                  => array( '0', '#語言:', '#语言:', '#LANGUAGE:' ),
	'contentlanguage'           => array( '1', '內容語言', '内容语言', 'CONTENTLANGUAGE', 'CONTENTLANG' ),
	'numberofadmins'            => array( '1', '管理員數', '管理员数', 'NUMBEROFADMINS' ),
	'hiddencat'                 => array( '1', '__隱藏分類__', '__隐藏分类__', '__HIDDENCAT__' ),
	'staticredirect'            => array( '1', '__靜態重新導向__', '__静态重定向__', '__STATICREDIRECT__' ),
	'url_query'                 => array( '0', '查詢', 'QUERY' ),
	'pagesincategory_pages'     => array( '0', '頁面', 'pages' ),
	'pagesincategory_files'     => array( '0', '檔案', 'files' ),
);

$bookstoreList = array(
	'博客來書店' => 'http://www.books.com.tw/exep/prod/booksfile.php?item=$1',
	'三民書店' => 'http://www.sanmin.com.tw/page-qsearch.asp?ct=search_isbn&qu=$1',
	'天下書店' => 'http://www.cwbook.com.tw/search/result1.jsp?field=2&keyWord=$1',
	'新絲路書店' => 'http://www.silkbook.com/function/Search_list_book_data.asp?item=5&text=$1'
);

