<?php
/** Traditional Chinese (‪中文(傳統字)‬)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Alexsh
 * @author Bencmq
 * @author FireJackey
 * @author Frankou
 * @author Gaoxuewei
 * @author Hakka
 * @author Horacewai2
 * @author Jidanni
 * @author Jimmy xu wrk
 * @author KaiesTse
 * @author Liangent
 * @author Mark85296341
 * @author Pbdragonwang
 * @author PhiLiP
 * @author Philip
 * @author Shinjiman
 * @author Skjackey tse
 * @author Waihorace
 * @author Wmr89502270
 * @author Wong128hk
 * @author Wrightbus
 * @author Yuyu
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
	'DoubleRedirects'           => array( '雙重重定向頁面' ),
	'BrokenRedirects'           => array( '損壞的重定向頁' ),
	'Disambiguations'           => array( '消歧義頁' ),
	'Userlogin'                 => array( '用戶登錄' ),
	'Userlogout'                => array( '用戶登出' ),
	'CreateAccount'             => array( '創建賬戶' ),
	'Preferences'               => array( '參數設置' ),
	'Watchlist'                 => array( '監視列表' ),
	'Recentchanges'             => array( '最近更改' ),
	'Upload'                    => array( '上傳文件' ),
	'Listfiles'                 => array( '文件列表' ),
	'Newimages'                 => array( '新建文件' ),
	'Listusers'                 => array( '用戶列表' ),
	'Listgrouprights'           => array( '群組權限' ),
	'Statistics'                => array( '統計信息' ),
	'Randompage'                => array( '隨機頁面' ),
	'Lonelypages'               => array( '孤立頁面' ),
	'Uncategorizedpages'        => array( '未歸類頁面' ),
	'Uncategorizedcategories'   => array( '未歸類分類' ),
	'Uncategorizedimages'       => array( '未歸類文件' ),
	'Uncategorizedtemplates'    => array( '未歸類模板' ),
	'Unusedcategories'          => array( '未使用分類' ),
	'Unusedimages'              => array( '未使用文件' ),
	'Wantedpages'               => array( '待撰頁面' ),
	'Wantedcategories'          => array( '待撰分類' ),
	'Wantedfiles'               => array( '需要的文件' ),
	'Wantedtemplates'           => array( '需要的模板' ),
	'Mostlinked'                => array( '最多鏈接頁面' ),
	'Mostlinkedcategories'      => array( '最多鏈接分類' ),
	'Mostlinkedtemplates'       => array( '最多鏈接模板' ),
	'Mostimages'                => array( '最多鏈接文件' ),
	'Mostcategories'            => array( '最多分類頁面' ),
	'Mostrevisions'             => array( '最多修訂頁面' ),
	'Fewestrevisions'           => array( '最少修訂頁面' ),
	'Shortpages'                => array( '短頁面' ),
	'Longpages'                 => array( '長頁面' ),
	'Newpages'                  => array( '最新頁面' ),
	'Ancientpages'              => array( '最早頁面' ),
	'Deadendpages'              => array( '斷鏈頁面' ),
	'Protectedpages'            => array( '已保護頁面' ),
	'Protectedtitles'           => array( '已保護標題' ),
	'Allpages'                  => array( '所有頁面' ),
	'Prefixindex'               => array( '前綴索引' ),
	'Ipblocklist'               => array( '封禁列表' ),
	'Unblock'                   => array( '解除封禁' ),
	'Specialpages'              => array( '特殊頁面' ),
	'Contributions'             => array( '用戶貢獻' ),
	'Emailuser'                 => array( '電郵用戶' ),
	'Confirmemail'              => array( '確認電子郵件' ),
	'Whatlinkshere'             => array( '鏈入頁面' ),
	'Recentchangeslinked'       => array( '鏈出更改' ),
	'Movepage'                  => array( '移動頁面' ),
	'Blockme'                   => array( '封禁我' ),
	'Booksources'               => array( '網絡書源' ),
	'Categories'                => array( '頁面分類' ),
	'Export'                    => array( '導出頁面' ),
	'Version'                   => array( '版本信息' ),
	'Allmessages'               => array( '所有信息' ),
	'Log'                       => array( '日誌' ),
	'Blockip'                   => array( '查封用戶' ),
	'Undelete'                  => array( '恢復被刪頁面' ),
	'Import'                    => array( '導入頁面' ),
	'Lockdb'                    => array( '鎖定數據庫' ),
	'Unlockdb'                  => array( '解除數據庫鎖定' ),
	'Userrights'                => array( '用戶權限' ),
	'MIMEsearch'                => array( 'MIME搜索' ),
	'FileDuplicateSearch'       => array( '搜索重複文件' ),
	'Unwatchedpages'            => array( '未被監視的頁面' ),
	'Listredirects'             => array( '重定向頁面列表' ),
	'Revisiondelete'            => array( '刪除或恢復版本' ),
	'Unusedtemplates'           => array( '未使用模板' ),
	'Randomredirect'            => array( '隨機重定向頁面' ),
	'Mypage'                    => array( '我的用戶頁' ),
	'Mytalk'                    => array( '我的討論頁' ),
	'Mycontributions'           => array( '我的貢獻' ),
	'Listadmins'                => array( '管理員列表' ),
	'Listbots'                  => array( '機器人列表' ),
	'Popularpages'              => array( '熱點頁面' ),
	'Search'                    => array( '搜索' ),
	'Resetpass'                 => array( '修改密碼' ),
	'Withoutinterwiki'          => array( '沒有跨語言鏈接的頁面' ),
	'MergeHistory'              => array( '合併歷史' ),
	'Filepath'                  => array( '文件路徑' ),
	'Invalidateemail'           => array( '不可識別的電郵地址' ),
	'Blankpage'                 => array( '空白頁面' ),
	'LinkSearch'                => array( '搜索網頁鏈接' ),
	'DeletedContributions'      => array( '已刪除的用戶貢獻' ),
	'Tags'                      => array( '標籤' ),
	'Activeusers'               => array( '活躍用戶' ),
	'RevisionMove'              => array( '版本移動' ),
	'ComparePages'              => array( '頁面比較' ),
	'Badtitle'                  => array( '不好的標題' ),
);

$bookstoreList = array(
	'博客來書店' => 'http://www.books.com.tw/exep/prod/booksfile.php?item=$1',
	'三民書店' => 'http://www.sanmin.com.tw/page-qsearch.asp?ct=search_isbn&qu=$1',
	'天下書店' => 'http://www.cwbook.com.tw/search/result1.jsp?field=2&keyWord=$1',
	'新絲路書店' => 'http://www.silkbook.com/function/Search_list_book_data.asp?item=5&text=$1'
);

$messages = array(
# User preference toggles
'tog-underline'               => '連結加底線：',
'tog-highlightbroken'         => '損毀連結格式為<a href="" class="new">這樣</a>（否則：像這樣<a href="" class="internal">?</a>）',
'tog-justify'                 => '段落對齊',
'tog-hideminor'               => '最近更改中隱藏小修改',
'tog-hidepatrolled'           => '於最近更改中隱藏巡查過的編輯',
'tog-newpageshidepatrolled'   => '於新頁面清單中隱藏巡查過的頁面',
'tog-extendwatchlist'         => '展開監視清單以顯示所有更改，不只是最近的',
'tog-usenewrc'                => '使用增強最近更改 （需要JavaScript）',
'tog-numberheadings'          => '標題自動編號',
'tog-showtoolbar'             => '顯示編輯工具欄 （需要JavaScript）',
'tog-editondblclick'          => '雙擊編輯頁面 （需要JavaScript）',
'tog-editsection'             => '允許通過點擊[編輯]連結編輯段落',
'tog-editsectiononrightclick' => '允許右擊標題編輯段落 （需要JavaScript）',
'tog-showtoc'                 => '顯示目錄 （針對一頁超過3個標題的頁面）',
'tog-rememberpassword'        => '在這個瀏覽器上記住我的登入資訊（可維持 $1 {{PLURAL:$1|天|天}}）',
'tog-watchcreations'          => '將我建立的頁面添加到我的監視列表中',
'tog-watchdefault'            => '將我更改的頁面添加到我的監視列表中',
'tog-watchmoves'              => '將我移動的頁面加入我的監視列表',
'tog-watchdeletion'           => '將我刪除的頁面加入我的監視列表',
'tog-minordefault'            => '預設將編輯設定為小編輯',
'tog-previewontop'            => '在編輯框上方顯示預覽',
'tog-previewonfirst'          => '第一次編輯時顯示原文內容的預覽',
'tog-nocache'                 => '禁止瀏覽器頁面快取',
'tog-enotifwatchlistpages'    => '當在我的監視列表中的頁面改變時發電子郵件給我',
'tog-enotifusertalkpages'     => '當我的對話頁發生改變時發電子郵件給我',
'tog-enotifminoredits'        => '即使是頁面的小修改也向我發電子郵件',
'tog-enotifrevealaddr'        => '在通知電子郵件中顯示我的電子郵件位址',
'tog-shownumberswatching'     => '顯示監視用戶的數目',
'tog-oldsig'                  => '原有簽名的預覽：',
'tog-fancysig'                => '將簽名以維基文字對待 （不產生自動連結）',
'tog-externaleditor'          => '預設使用外部編輯器 （進階者專用，需要在您的電腦上作出一些特別設定。[http://www.mediawiki.org/wiki/Manual:External_editors 更多信息。]）',
'tog-externaldiff'            => '預設使用外部差異分析 （進階者專用，需要在您的電腦上作出一些特別設定。[http://www.mediawiki.org/wiki/Manual:External_editors 更多信息。]）',
'tog-showjumplinks'           => '啟用「跳轉到」訪問連結',
'tog-uselivepreview'          => '使用實時預覽 （需要JavaScript） （試驗中）',
'tog-forceeditsummary'        => '當沒有輸入摘要時提醒我',
'tog-watchlisthideown'        => '監視列表中隱藏我的編輯',
'tog-watchlisthidebots'       => '監視列表中隱藏機器人的編輯',
'tog-watchlisthideminor'      => '監視列表中隱藏小修改',
'tog-watchlisthideliu'        => '監視列表中隱藏登入用戶',
'tog-watchlisthideanons'      => '監視列表中隱藏匿名用戶',
'tog-watchlisthidepatrolled'  => '監視清單中隱藏已巡查的編輯',
'tog-nolangconversion'        => '不進行用字轉換',
'tog-ccmeonemails'            => '當我寄電子郵件給其他用戶時，也寄一份複本到我的信箱。',
'tog-diffonly'                => '在比較兩個修訂版本差異時不顯示頁面內容',
'tog-showhiddencats'          => '顯示隱藏分類',
'tog-noconvertlink'           => '不轉換連結標題',
'tog-norollbackdiff'          => '進行回退後略過差異比較',

'underline-always'  => '總是使用',
'underline-never'   => '從不使用',
'underline-default' => '瀏覽器預設',

# Font style option in Special:Preferences
'editfont-style'     => '編輯區字型樣式：',
'editfont-default'   => '瀏覽器預設',
'editfont-monospace' => '固定間距字型',
'editfont-sansserif' => '無襯線字型',
'editfont-serif'     => '襯線字型',

# Dates
'sunday'        => '星期日',
'monday'        => '星期一',
'tuesday'       => '星期二',
'wednesday'     => '星期三',
'thursday'      => '星期四',
'friday'        => '星期五',
'saturday'      => '星期六',
'sun'           => '日',
'mon'           => '一',
'tue'           => '二',
'wed'           => '三',
'thu'           => '四',
'fri'           => '五',
'sat'           => '六',
'january'       => '1月',
'february'      => '2月',
'march'         => '3月',
'april'         => '4月',
'may_long'      => '5月',
'june'          => '6月',
'july'          => '7月',
'august'        => '8月',
'september'     => '9月',
'october'       => '10月',
'november'      => '11月',
'december'      => '12月',
'january-gen'   => '一月',
'february-gen'  => '二月',
'march-gen'     => '三月',
'april-gen'     => '四月',
'may-gen'       => '五月',
'june-gen'      => '六月',
'july-gen'      => '七月',
'august-gen'    => '八月',
'september-gen' => '九月',
'october-gen'   => '十月',
'november-gen'  => '十一月',
'december-gen'  => '十二月',
'jan'           => '1月',
'feb'           => '2月',
'mar'           => '3月',
'apr'           => '4月',
'may'           => '5月',
'jun'           => '6月',
'jul'           => '7月',
'aug'           => '8月',
'sep'           => '9月',
'oct'           => '10月',
'nov'           => '11月',
'dec'           => '12月',

# Categories related messages
'pagecategories'                 => '$1個分類',
'category_header'                => '類別「$1」中的頁面',
'subcategories'                  => '附分類',
'category-media-header'          => '"$1"分類中的媒體',
'category-empty'                 => "''這個分類中尚未包含任何頁面或媒體。''",
'hidden-categories'              => '$1個隱藏分類',
'hidden-category-category'       => '隱藏分類',
'category-subcat-count'          => '{{PLURAL:$2|這個分類中只有以下的附分類。|這個分類中有以下的$1個附分類，共有$2個附分類。}}',
'category-subcat-count-limited'  => '這個分類下有$1個附分類。',
'category-article-count'         => '{{PLURAL:$2|這個分類中只有以下的頁面。|這個分類中有以下的$1個頁面，共有$2個頁面。}}',
'category-article-count-limited' => '這個分類下有$1個頁面。',
'category-file-count'            => '{{PLURAL:$2|這個分類中只有以下的檔案。|這個分類中有以下的$1個檔案，共有$2個檔案。}}',
'category-file-count-limited'    => '這個分類下有$1個檔案。',
'listingcontinuesabbrev'         => '續',
'index-category'                 => '已做索引的頁面',
'noindex-category'               => '未做索引的頁面',

'mainpagetext'      => "'''已成功安裝 MediaWiki。'''",
'mainpagedocfooter' => '請參閱[http://meta.wikimedia.org/wiki/Help:Contents 用戶手冊]以獲得使用此 wiki 軟件的訊息！

== 入門 ==
* [http://www.mediawiki.org/wiki/Manual:Configuration_settings MediaWiki 配置設定清單]
* [http://www.mediawiki.org/wiki/Manual:FAQ MediaWiki 常見問題解答]
* [https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce MediaWiki 發佈郵件清單]',

'about'         => '關於',
'article'       => '內容頁面',
'newwindow'     => '（以新視窗開啟）',
'cancel'        => '取消',
'moredotdotdot' => '更多...',
'mypage'        => '我的頁面',
'mytalk'        => '我的對話頁',
'anontalk'      => '該IP的對話頁',
'navigation'    => '導覽',
'and'           => '和',

# Cologne Blue skin
'qbfind'         => '尋找',
'qbbrowse'       => '瀏覽',
'qbedit'         => '編輯',
'qbpageoptions'  => '頁面選項',
'qbpageinfo'     => '頁面訊息',
'qbmyoptions'    => '我的選項',
'qbspecialpages' => '特殊頁面',
'faq'            => '常見問題解答',
'faqpage'        => 'Project:常見問題解答',

# Vector skin
'vector-action-addsection'       => '加入主題',
'vector-action-delete'           => '刪除',
'vector-action-move'             => '移動',
'vector-action-protect'          => '保護',
'vector-action-undelete'         => '恢復被刪頁面',
'vector-action-unprotect'        => '解除保護',
'vector-simplesearch-preference' => '啟用加強搜尋建議（僅限 Vector 外觀）',
'vector-view-create'             => '建立',
'vector-view-edit'               => '編輯',
'vector-view-history'            => '檢視歷史',
'vector-view-view'               => '閱讀',
'vector-view-viewsource'         => '檢視原始碼',
'actions'                        => '動作',
'namespaces'                     => '名字空間',
'variants'                       => '變換',

'errorpagetitle'    => '錯誤',
'returnto'          => '返回到$1。',
'tagline'           => '出自{{SITENAME}}',
'help'              => '幫助',
'search'            => '搜尋',
'searchbutton'      => '搜尋',
'go'                => '進入',
'searcharticle'     => '進入',
'history'           => '頁面歷史',
'history_short'     => '歷史',
'updatedmarker'     => '我上次訪問以來的修改',
'info_short'        => '資訊',
'printableversion'  => '可列印版',
'permalink'         => '永久連結',
'print'             => '列印',
'view'              => '查看',
'edit'              => '編輯',
'create'            => '建立',
'editthispage'      => '編輯本頁',
'create-this-page'  => '建立本頁',
'delete'            => '刪除',
'deletethispage'    => '刪除本頁',
'undelete_short'    => '反刪除$1項修訂',
'viewdeleted_short' => '查看$1項已刪除的修訂',
'protect'           => '保護',
'protect_change'    => '更改',
'protectthispage'   => '保護本頁',
'unprotect'         => '解除保護',
'unprotectthispage' => '解除此頁保護',
'newpage'           => '新頁面',
'talkpage'          => '討論本頁',
'talkpagelinktext'  => '對話',
'specialpage'       => '特殊頁面',
'personaltools'     => '個人工具',
'postcomment'       => '新段落',
'articlepage'       => '檢視內容頁面',
'talk'              => '討論',
'views'             => '檢視',
'toolbox'           => '工具箱',
'userpage'          => '檢視用戶頁面',
'projectpage'       => '檢視計劃頁面',
'imagepage'         => '檢視檔案頁面',
'mediawikipage'     => '檢視使用者介面訊息',
'templatepage'      => '檢視模板頁面',
'viewhelppage'      => '檢視說明頁面',
'categorypage'      => '檢視分類頁面',
'viewtalkpage'      => '檢視討論頁面',
'otherlanguages'    => '其他語言',
'redirectedfrom'    => '（重定向自$1）',
'redirectpagesub'   => '重定向頁面',
'lastmodifiedat'    => '此頁面最後修訂於 $1 $2。',
'viewcount'         => '本頁面已經被瀏覽$1次。',
'protectedpage'     => '被保護頁',
'jumpto'            => '跳轉到:',
'jumptonavigation'  => '導覽',
'jumptosearch'      => '搜尋',
'view-pool-error'   => '抱歉，伺服器在這段時間中已經超出負荷。
太多用戶嘗試檢視這個頁面。
在嘗試訪問這個頁面之前請再稍等一會。

$1',
'pool-timeout'      => '等待鎖死時超時',
'pool-queuefull'    => '請求池已滿',
'pool-errorunknown' => '未知錯誤',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => '關於 {{SITENAME}}',
'aboutpage'            => 'Project:關於',
'copyright'            => '本站的全部文本內容在$1之條款下提供。',
'copyrightpage'        => '{{ns:project}}:版權訊息',
'currentevents'        => '現時事件',
'currentevents-url'    => 'Project:現時事件',
'disclaimers'          => '免責聲明',
'disclaimerpage'       => 'Project:一般免責聲明',
'edithelp'             => '編輯幫助',
'edithelppage'         => 'Help:如何編輯頁面',
'helppage'             => 'Help:目錄',
'mainpage'             => '首頁',
'mainpage-description' => '首頁',
'policy-url'           => 'Project:方針',
'portal'               => '社群入口',
'portal-url'           => 'Project:社群入口',
'privacy'              => '隱私權政策',
'privacypage'          => 'Project:隱私權政策',

'badaccess'        => '權限錯誤',
'badaccess-group0' => '你所請求執行的操作被禁止。',
'badaccess-groups' => '您剛才的請求只有{{PLURAL:$2|這個|這些}}用戶組的用戶才能使用：$1',

'versionrequired'     => '需要MediaWiki $1 版',
'versionrequiredtext' => '需要版本$1的 MediaWiki 才能使用此頁。參見[[Special:Version|版本頁]]。',

'ok'                      => '確定',
'retrievedfrom'           => '取自"$1"',
'youhavenewmessages'      => '您有$1（$2）。',
'newmessageslink'         => '新訊息',
'newmessagesdifflink'     => '上次更改',
'youhavenewmessagesmulti' => '您在 $1 有一條新訊息',
'editsection'             => '編輯',
'editold'                 => '編輯',
'viewsourceold'           => '檢視原始碼',
'editlink'                => '編輯',
'viewsourcelink'          => '檢視原始碼',
'editsectionhint'         => '編輯段落: $1',
'toc'                     => '目錄',
'showtoc'                 => '顯示',
'hidetoc'                 => '隱藏',
'collapsible-collapse'    => '摺叠',
'collapsible-expand'      => '展開',
'thisisdeleted'           => '檢視或恢復$1？',
'viewdeleted'             => '檢視$1？',
'restorelink'             => '$1個被刪除的版本',
'feedlinks'               => '訂閱:',
'feed-invalid'            => '無效的訂閱類型。',
'feed-unavailable'        => '聯合訂閱並無提供',
'site-rss-feed'           => '$1的RSS訂閱',
'site-atom-feed'          => '$1的Atom訂閱',
'page-rss-feed'           => '「$1」的RSS訂閱',
'page-atom-feed'          => '「$1」的Atom訂閱',
'red-link-title'          => '$1 （頁面未存在）',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => '頁面',
'nstab-user'      => '用戶頁面',
'nstab-media'     => '媒體頁面',
'nstab-special'   => '特殊頁面',
'nstab-project'   => '計劃頁面',
'nstab-image'     => '檔案',
'nstab-mediawiki' => '訊息',
'nstab-template'  => '模板',
'nstab-help'      => '幫助頁面',
'nstab-category'  => '分類',

# Main script and global functions
'nosuchaction'      => '這個命令不存在',
'nosuchactiontext'  => '該URL所指定的動作無效。
您可能打錯URL，或點了錯誤連結。
這也可能是{{SITENAME}}所使用的軟件出現了錯誤。',
'nosuchspecialpage' => '此特殊頁面不存在',
'nospecialpagetext' => '<strong>您請求的特殊頁面無效。</strong>

[[Special:SpecialPages|{{int:specialpages}}]]中載有所有有效特殊頁面的列表。',

# General errors
'error'                => '錯誤',
'databaseerror'        => '資料庫錯誤',
'dberrortext'          => '發生資料庫查詢語法錯誤。
可能是由於軟體自身的錯誤所引起。
最後一次資料庫查詢指令是:
<blockquote><tt>$1</tt></blockquote>
來自於函數 "<tt>$2</tt>"。
數據庫返回錯誤 "<tt>$3: $4</tt>"。',
'dberrortextcl'        => '發生了一個資料庫查詢語法錯誤。
最後一次的資料庫查詢是:
「$1」
來自於函數「$2」。
數據庫返回錯誤「$3: $4」。',
'laggedslavemode'      => '警告: 頁面可能不包含最近的更新。',
'readonly'             => '資料庫禁止訪問',
'enterlockreason'      => '請輸入禁止訪問原因, 包括估計重新開放的時間',
'readonlytext'         => '資料庫目前禁止輸入新內容及更改，
這很可能是由於資料庫正在維修，之後即可恢復。
管理員有如下解釋: $1',
'missing-article'      => '資料庫找不到文字"$1" $2。

<p>通常這是由於修訂歷史頁上過時的連結到已經被刪除的頁面所導致的。</p>

<p>如果情況不是這樣，您可能找到了軟體內的一個臭蟲。
請記錄下URL地址，並向[[Special:ListUsers/sysop|管理員]]報告。</p>',
'missingarticle-rev'   => '（修訂#: $1）',
'missingarticle-diff'  => '（差異: $1, $2）',
'readonly_lag'         => '附屬資料庫伺服器正在將快取更新到主伺服器，資料庫已被自動鎖定',
'internalerror'        => '內部錯誤',
'internalerror_info'   => '內部錯誤：$1',
'fileappenderrorread'  => '當附加時無法讀取"$1"。',
'fileappenderror'      => '不能附加"$1"到"$2"。',
'filecopyerror'        => '無法複製檔案"$1"到"$2"。',
'filerenameerror'      => '無法重新命名檔案"$1"到"$2"。',
'filedeleteerror'      => '無法刪除檔案"$1"。',
'directorycreateerror' => '無法建立目錄"$1"。',
'filenotfound'         => '找不到檔案"$1"。',
'fileexistserror'      => '無法寫入檔案"$1": 檔案已存在',
'unexpected'           => '不正常值："$1"="$2"。',
'formerror'            => '錯誤：無法提交表單',
'badarticleerror'      => '無法在此頁進行該操作。',
'cannotdelete'         => '無法刪除頁面或圖片"$1"。
它可能已經被其他人刪除了。',
'badtitle'             => '錯誤的標題',
'badtitletext'         => '所請求頁面的標題是無效的、不存在，跨語言或跨wiki連結的標題錯誤。它可能包含一個或更多的不能用於標題的字符。',
'perfcached'           => '下列是快取資料，因此可能不是最新的:',
'perfcachedts'         => '下列是快取資料，其最後更新時間是$1。',
'querypage-no-updates' => '目前禁止對此頁面進行更新。此處的資料將不能被立即重新整理。',
'wrong_wfQuery_params' => '錯誤的參數被傳遞到 wfQuery（）<br />
函數：$1<br />
查詢：$2',
'viewsource'           => '原始碼',
'viewsourcefor'        => '$1的原始碼',
'actionthrottled'      => '動作已壓制',
'actionthrottledtext'  => '基於反垃圾的考量，您現在於這段短時間之中限制去作這一個動作，而您已經超過這個上限。請在數分鐘後再嘗試。',
'protectedpagetext'    => '該頁面已被保護以防止編輯。',
'viewsourcetext'       => '{{GENDER:|你|妳|你}}可以檢視並複製本頁面的原始碼。',
'protectedinterface'   => '該頁提供了軟體的介面文字，它已被保護以防止隨意的修改。',
'editinginterface'     => "'''警告:''' 您正在編輯的頁面是用於提供軟體的介面文字。改變此頁將影響其他用戶的介面外觀。如要翻譯，請考慮使用[http://translatewiki.net/wiki/Main_Page?setlang=zh-hant translatewiki.net]，一個用來為MediaWiki軟件本地化的計劃。",
'sqlhidden'            => '（隱藏SQL查詢）',
'cascadeprotected'     => '這個頁面已經被保護，因為這個頁面被以下已標註"聯鎖保護"的{{PLURAL:$1|一個|多個}}被保護頁面包含:
$2',
'namespaceprotected'   => "您並沒有權限編輯'''$1'''名字空間的頁面。",
'customcssjsprotected' => '您並無許可權去編輯這個頁面，因為它包含了另一位用戶的個人設定。',
'ns-specialprotected'  => '特殊頁面是不可以編輯的。',
'titleprotected'       => "這個標題已經被[[User:$1|$1]]保護以防止建立。理由是''$2''。",

# Virus scanner
'virus-badscanner'     => "損壞設定: 未知的病毒掃瞄器: ''$1''",
'virus-scanfailed'     => '掃瞄失敗 （代碼 $1）',
'virus-unknownscanner' => '未知的防病毒:',

# Login and logout pages
'logouttext'                 => '您已經登出。

您可以以匿名方式繼續使用{{SITENAME}}，或以相同或不同用戶身份[[Special:UserLogin|登入]]。
請注意，如果你再次登入，此頁或會繼續顯示，直到您清除瀏覽器緩存。',
'welcomecreation'            => '== 歡迎，$1！ ==
您的賬號已經建立。
不要忘記設置[[Special:Preferences|{{SITENAME}}的個人參數]]。',
'yourname'                   => '您的使用者名稱：',
'yourpassword'               => '您的密碼：',
'yourpasswordagain'          => '再次輸入密碼:',
'remembermypassword'         => '在這個瀏覽器上記住我的登入資訊（可維持 $1 {{PLURAL:$1|天|天}}）',
'securelogin-stick-https'    => '登入後繼續以HTTPS連接',
'yourdomainname'             => '您的網域:',
'externaldberror'            => '這可能是由於驗證資料庫錯誤或您被禁止更新您的外部賬號。',
'login'                      => '登入',
'nav-login-createaccount'    => '登入／建立新賬號',
'loginprompt'                => '您必須允許瀏覽器紀錄 Cookie 才能成功登入 {{SITENAME}}。',
'userlogin'                  => '登入／建立新賬號',
'userloginnocreate'          => '登入',
'logout'                     => '登出',
'userlogout'                 => '登出',
'notloggedin'                => '未登入',
'nologin'                    => '您還沒有賬號嗎？$1。',
'nologinlink'                => '建立新賬號',
'createaccount'              => '建立新賬號',
'gotaccount'                 => '已經擁有賬號？$1。',
'gotaccountlink'             => '登入',
'createaccountmail'          => '通過電郵',
'createaccountreason'        => '理由：',
'badretype'                  => '您所輸入的密碼並不相同。',
'userexists'                 => '您所輸入的用戶名稱已經存在，請另選一個名稱。',
'loginerror'                 => '登入錯誤',
'createaccounterror'         => '無法建立賬戶：$1',
'nocookiesnew'               => '已成功創建新賬戶！偵測到您已關閉 Cookies，請開啟它並登入。',
'nocookieslogin'             => '本站利用 Cookies 進行用戶登入，偵測到您已關閉 Cookies，請開啟它並重新登入。',
'nocookiesfornew'            => '這位用戶的賬戶未建立，我們不能確認它的來源。
請肯定您已經開啟 cookies，重新載入後再試。',
'noname'                     => '{{GENDER:|你|妳|你}}沒有輸入一個有效的用戶名。',
'loginsuccesstitle'          => '登入成功',
'loginsuccess'               => '{{GENDER:|你|妳|你}}正在以"$1"的身份在{{SITENAME}}登入。',
'nosuchuser'                 => '找不到用戶 "$1"。
用戶名稱是有大小寫區分的。
檢查您的拼寫，或者用下面的表格[[Special:UserLogin/signup|建立一個新賬號]]。',
'nosuchusershort'            => '沒有一個名為「<nowiki>$1</nowiki>」的用戶。請檢查您輸入的文字是否有錯誤。',
'nouserspecified'            => '{{GENDER:|你|妳|你}}需要指定一個用戶名。',
'login-userblocked'          => '這位用戶已被封鎖。不容許登入。',
'wrongpassword'              => '您輸入的密碼錯誤，請再試一次。',
'wrongpasswordempty'         => '沒有輸入密碼！請重試。',
'passwordtooshort'           => '您的密碼不能少於$1個字元。',
'password-name-match'        => '您的密碼必須跟您的用戶名不相同。',
'password-login-forbidden'   => '這個用戶名稱及密碼的使用是被禁止的。',
'mailmypassword'             => '將新密碼寄給我',
'passwordremindertitle'      => '{{SITENAME}}的新臨時密碼',
'passwordremindertext'       => '有人（可能是您，來自IP位址$1）已請求{{SITENAME}}的新密碼 （$4）。
用戶"$2"的一個新臨時密碼現在已被設定好為"$3"。
如果這個動作是您所指示的，您便需要立即登入並選擇一個新的密碼。
您的臨時密碼會於{{PLURAL:$5|一|$5}}天內過期。

如果是其他人發出了該請求，或者您已經記起了您的密碼並不準備改變它，
您可以忽略此消息並繼續使用您的舊密碼。',
'noemail'                    => '用戶"$1"沒有登記電子郵件地址。',
'noemailcreate'              => '您需要提供一個有效的電子郵件地址',
'passwordsent'               => '用戶"$1"的新密碼已經寄往所登記的電子郵件地址。
請在收到後再登入。',
'blocked-mailpassword'       => '您的IP地址處於查封狀態而不允許編輯，為了安全起見，密碼恢復功能已被禁用。',
'eauthentsent'               => '一封確認信已經發送到所示的地址。在發送其它郵件到此賬戶前，您必須首先依照這封信中的指導確認這個電子郵件信箱真實有效。',
'throttled-mailpassword'     => '密碼提醒已經在前$1小時內發送。為防止濫用，限定在$1小時內僅發送一次密碼提醒。',
'mailerror'                  => '發送郵件錯誤: $1',
'acct_creation_throttle_hit' => '在這個wiki上的訪客利用您的IP地址在昨天創建了$1個賬戶，是在這段時間中的上限。
結果利用這個IP地址的訪客在這段時間中不能創建更多的賬戶。',
'emailauthenticated'         => '您的電子郵件地址已經於$2 $3確認有效。',
'emailnotauthenticated'      => '您的郵箱位址<strong>還沒被認証</strong>。以下功能將不會發送任何郵件。',
'noemailprefs'               => '在您的參數設置中指定一個電子郵件地址以使用此功能。',
'emailconfirmlink'           => '確認您的郵箱地址',
'invalidemailaddress'        => '郵箱地址格式不正確，請輸入正確的郵箱位址或清空該輸入框。',
'accountcreated'             => '已建立賬戶',
'accountcreatedtext'         => '$1的賬戶已經被建立。',
'createaccount-title'        => '在{{SITENAME}}中建立新賬戶',
'createaccount-text'         => '有人在{{SITENAME}}中利用您的電郵創建了一個名為 "$2" 的新賬戶（$4），密碼是 "$3" 。您應該立即登入並更改密碼。

如果該賬戶建立錯誤的話，您可以忽略此訊息。',
'usernamehasherror'          => '用戶名稱不可以包含切細字元',
'login-throttled'            => '您已經嘗試多次的登入動作。
請稍等多一會再試。',
'loginlanguagelabel'         => '語言: $1',
'suspicious-userlogout'      => '您登出的要求已經被拒絕，因為它可能是由已損壞的瀏覽器或者快取代理傳送。',

# E-mail sending
'php-mail-error-unknown' => '在 PHP 的 mail() 參數中的未知錯誤',

# JavaScript password checks
'password-strength'            => '預估密碼強度： $1',
'password-strength-bad'        => '差',
'password-strength-mediocre'   => '一般',
'password-strength-acceptable' => '可接受',
'password-strength-good'       => '好',
'password-retype'              => '再次輸入密碼',
'password-retype-mismatch'     => '密碼不匹配',

# Password reset dialog
'resetpass'                 => '更改密碼',
'resetpass_announce'        => '您是透過一個臨時的發送到郵件中的代碼登入的。要完成登入，您必須在這裡設定一個新密碼:',
'resetpass_text'            => '<!-- 在此處加入文字 -->',
'resetpass_header'          => '更改賬戶密碼',
'oldpassword'               => '舊密碼:',
'newpassword'               => '新密碼:',
'retypenew'                 => '確認密碼:',
'resetpass_submit'          => '設定密碼並登入',
'resetpass_success'         => '您的密碼已經被成功更改！
現在正為您登入...',
'resetpass_forbidden'       => '無法更改密碼',
'resetpass-no-info'         => '您必須登入後直接進入這個頁面。',
'resetpass-submit-loggedin' => '更改密碼',
'resetpass-submit-cancel'   => '取消',
'resetpass-wrong-oldpass'   => '無效的臨時或現有的密碼。
您可能已成功地更改了您的密碼，或者已經請求一個新的臨時密碼。',
'resetpass-temp-password'   => '臨時密碼:',

# Edit page toolbar
'bold_sample'     => '粗體文字',
'bold_tip'        => '粗體文字',
'italic_sample'   => '斜體文字',
'italic_tip'      => '斜體文字',
'link_sample'     => '連結標題',
'link_tip'        => '內部連結',
'extlink_sample'  => 'http://www.example.com 連結標題',
'extlink_tip'     => '外部連結（加前綴 http://）',
'headline_sample' => '大標題文字',
'headline_tip'    => '2級標題文字',
'math_sample'     => '在此插入數學公式',
'math_tip'        => '插入數學公式 （LaTeX）',
'nowiki_sample'   => '在此插入非格式文字',
'nowiki_tip'      => '插入非格式文字',
'image_tip'       => '嵌入檔案',
'media_tip'       => '檔案連結',
'sig_tip'         => '帶有時間的簽名',
'hr_tip'          => '水平線 （小心使用）',

# Edit pages
'summary'                          => '摘要：',
'subject'                          => '主題:',
'minoredit'                        => '這是一個小修改',
'watchthis'                        => '監視本頁',
'savearticle'                      => '儲存頁面',
'preview'                          => '預覽',
'showpreview'                      => '顯示預覽',
'showlivepreview'                  => '即時預覽',
'showdiff'                         => '顯示差異',
'anoneditwarning'                  => "'''警告：'''您沒有登入。
您的IP位址將記錄在此頁的編輯歷史中。",
'anonpreviewwarning'               => "''您沒有登入。保存頁面將會把您的IP位址記錄在此頁的編輯歷史中。''",
'missingsummary'                   => "'''提示:''' 您沒有提供一個編輯摘要。如果您再次單擊「{{int:savearticle}}」，您的編輯將不帶編輯摘要儲存。",
'missingcommenttext'               => '請在下面輸入評論。',
'missingcommentheader'             => "'''提示:''' 您沒有為此評論提供一個標題。如果您再次單擊「{{int:savearticle}}」，您的編輯將不帶標題儲存。",
'summary-preview'                  => '摘要預覽:',
'subject-preview'                  => '主題/標題預覽:',
'blockedtitle'                     => '用戶被查封',
'blockedtext'                      => "{{GENDER:|你|妳|你}}的用戶名或IP地址已經被$1查封。

這次查封是由$1所封的。當中的原因是''$2''。

* 這次查封開始的時間是：$8
* 這次查封到期的時間是：$6
* 對於被查封者：$7

{{GENDER:|你|妳|你}}可以聯絡$1或者其他的[[{{MediaWiki:Grouppage-sysop}}|管理員]]，討論這次查封。
除非{{GENDER:|你|妳|你}}已經在{{GENDER:|你|妳|你}}的[[Special:Preferences|賬號參數設置]]中設定了一個有效的電子郵件地址，否則{{GENDER:|你|妳|你}}是不能使用「電郵這位用戶」的功能。當設定了一個有效的電子郵件地址後，這個功能是不會封鎖的。

{{GENDER:|你|妳|你}}目前的IP地址是$3，而該查封ID是 #$5。 請在{{GENDER:|你|妳|你}}的查詢中註明以上所有的資料。",
'autoblockedtext'                  => "{{GENDER:|你|妳|你}}的IP地址已經被自動查封，由於先前的另一位用戶被$1所查封。
而查封的原因是：

:''$2''

* 這次查封的開始時間是：$8
* 這次查封的到期時間是：$6
* 對於被查封者：$7

{{GENDER:|你|妳|你}}可以聯絡$1或者其他的[[{{MediaWiki:Grouppage-sysop}}|管理員]]，討論這次查封。
除非{{GENDER:|你|妳|你}}已經在{{GENDER:|你|妳|你}}的[[Special:Preferences|賬號參數設置]]中設定了一個有效的電子郵件地址，否則{{GENDER:|你|妳|你}}是不能使用「電郵這位用戶」的功能。當設定了一個有效的電子郵件地址後，這個功能是不會封鎖的。

您現時正在使用的 IP 地址是 $3，查封ID是 #$5。 請在{{GENDER:|你|妳|你}}的查詢中註明以上所有的資料。",
'blockednoreason'                  => '無給出原因',
'blockedoriginalsource'            => "以下是'''$1'''的源碼：",
'blockededitsource'                => "{{GENDER:|你|妳|你}}對'''$1'''進行'''編輯'''的文字如下:",
'whitelistedittitle'               => '登入後才可編輯',
'whitelistedittext'                => '您必須先$1才可編輯頁面。',
'confirmedittext'                  => '在編輯此頁之前您必須確認您的郵箱位址。請透過[[Special:Preferences|偏好設定]]設定並驗證您的郵箱地址。',
'nosuchsectiontitle'               => '找不到段落',
'nosuchsectiontext'                => '您嘗試編輯的章節並不存在。
可能在您查看頁面時已經移動或刪除。',
'loginreqtitle'                    => '需要登入',
'loginreqlink'                     => '登入',
'loginreqpagetext'                 => '您必須$1才能檢視其它頁面。',
'accmailtitle'                     => '密碼已寄出',
'accmailtext'                      => "'[[User talk:$1|$1]]'的隨機產生密碼已經寄到$2。

這個新賬號的密碼可以在登入後的''[[Special:ChangePassword|更改密碼]]''頁面中更改。",
'newarticle'                       => '（新）',
'newarticletext'                   => '您進入了一個尚未建立的頁面。
要建立該頁面，請在下面的編輯框中輸入內容（詳情參見[[{{MediaWiki:Helppage}}|幫助]]）。
如果您是不小心來到此頁面，直接點擊您瀏覽器中的“返回”按鈕返回。',
'anontalkpagetext'                 => "---- ''這是一個還未建立賬號的匿名用戶的對話頁。我們因此只能用IP地址來與他／她聯絡。該IP地址可能由幾名用戶共享。如果您是一名匿名用戶並認為本頁上的評語與您無關，請[[Special:UserLogin/signup|創建新賬號]]或[[Special:UserLogin|登入]]以避免在未來於其他匿名用戶混淆。''",
'noarticletext'                    => '此頁目前沒有內容，您可以在其它頁[[Special:Search/{{PAGENAME}}|搜索此頁標題]]，
<span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} 搜索有關日誌]，
或[{{fullurl:{{FULLPAGENAME}}|action=edit}} 編輯此頁]</span>。',
'noarticletext-nopermission'       => '此頁目前沒有內容，您可以在其它頁[[Special:Search/{{PAGENAME}}|搜索此頁標題]]，
或<span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} 搜索有關日誌]</span>。',
'userpage-userdoesnotexist'        => '未曾創建用戶名「$1」。請在創建／編輯這個頁面前先檢查一下。',
'userpage-userdoesnotexist-view'   => '未曾建立用戶名「$1」。',
'blocked-notice-logextract'        => '這位用戶現正被封鎖。
下面有最近的封鎖紀錄以供參考：',
'clearyourcache'                   => "'''注意：在儲存以後，您必須清除瀏覽器的快取才能看到所作出的改變。'''
'''Mozilla / Firefox / Safari''': 按著 ''Shift'' 再點擊''重新整理''，或按下''Ctrl-F5''或''Ctrl-R''（在Macintosh上按下''Command-R''）；
'''Konqueror''': 只需點擊 ''重新整理''或按下''F5''；
'''Opera''': 在 ''工具→設定'' 中完整地清除它們的快取，或按下''Alt-F5''；
'''Internet Explorer''': 按著 ''Ctrl'' 再點擊 ''重新整理''，或按下 ''Ctrl-F5''。",
'usercssyoucanpreview'             => "'''提示:''' 在保存前請用「{{int:showpreview}}」按鈕來測試您新的 CSS 。",
'userjsyoucanpreview'              => "'''提示:''' 在保存前請用「{{int:showpreview}}」按鈕來測試您新的 JavaScript 。",
'usercsspreview'                   => "'''記住您只是在預覽您的個人 CSS。'''
'''還沒有儲存﹗'''",
'userjspreview'                    => "'''記住您只是在測試／預覽您的個人 JavaScript。'''
'''還沒有儲存﹗'''",
'sitecsspreview'                   => "'''記住你現在只是預覽此 CSS。'''
'''還沒有儲存！'''",
'sitejspreview'                    => "'''記住你現在只是預覽此 JavaScript 代碼。'''
'''還沒有儲存！'''",
'userinvalidcssjstitle'            => "'''警告:''' 不存在面板\"\$1\"。注意自訂的 .css 和 .js 頁要使用小寫標題，例如，{{ns:user}}:Foo/vector.css 不同於 {{ns:user}}:Foo/Vector.css。",
'updated'                          => '（已更新）',
'note'                             => "'''注意:'''",
'previewnote'                      => "'''請記住這只是預覽，內容尚未儲存！'''",
'previewconflict'                  => '這個預覽顯示了上面文字編輯區中的內容。它將在{{GENDER:|你|妳|你}}選擇保存後出現。',
'session_fail_preview'             => "'''抱歉！由於會話數據丟失，我們不能處理你的編輯。'''
請重試。
如果再次失敗，請嘗試[[Special:UserLogout|登出]]後重新登入。",
'session_fail_preview_html'        => "'''抱歉！部份資料已遺失，我們無法處理您的編輯。'''

''由於{{SITENAME}}已經開放原始 HTML 碼，預覽已經隱藏以預防 JavaScript 的攻擊。''

'''如果這個編輯過程沒有問題，請再試一次。如果仍然有問題，請[[Special:UserLogout|登出]]後再重新登入一次。'''",
'token_suffix_mismatch'            => "'''由於您用戶端中的編輯信符毀損了一些標點符號字元，為防止編輯的文字損壞，您的編輯已經被拒絕。'''
這種情況通常出現於使用含有很多臭蟲、以網絡為主的匿名代理服務的時候。",
'editing'                          => '正在編輯$1',
'editingsection'                   => '正在編輯$1（段落）',
'editingcomment'                   => '正在編輯$1（新段落）',
'editconflict'                     => '編輯衝突：$1',
'explainconflict'                  => "有人在{{GENDER:|你|妳|你}}開始編輯後更改了頁面。
上面的文字框內顯示的是目前本頁的內容。
{{GENDER:|你|妳|你}}所做的修改顯示在下面的文字框中。
{{GENDER:|你|妳|你}}應當將{{GENDER:|你|妳|你}}所做的修改加入現有的內容中。
'''只有'''在上面文字框中的內容會在{{GENDER:|你|妳|你}}點擊「{{int:savearticle}}」後被保存。",
'yourtext'                         => '您的文字',
'storedversion'                    => '已保存修訂版本',
'nonunicodebrowser'                => "'''警告: 您的瀏覽器不兼容Unicode編碼。'''這裡有一個工作區將使您能安全地編輯頁面: 非ASCII字元將以十六進製編碼模式出現在編輯框中。",
'editingold'                       => "'''警告：{{GENDER:|你|妳|你}}正在編輯的是本頁的舊版本。'''
如果{{GENDER:|你|妳|你}}保存它的話，在本版本之後的任何修改都會遺失。",
'yourdiff'                         => '差異',
'copyrightwarning'                 => "請注意您對{{SITENAME}}的所有貢獻都被認為是在$2下發佈，請檢視在$1的細節。
如果您不希望您的文字被任意修改和再散佈，請不要提交。<br />
您同時也要向我們保證您所提交的內容是您自己所作，或得自一個不受版權保護或相似自由的來源。
'''不要在未獲授權的情況下發表！'''<br />",
'copyrightwarning2'                => "請注意您對{{SITENAME}}的所有貢獻
都可能被其他貢獻者編輯，修改或刪除。
如果您不希望您的文字被任意修改和再散佈，請不要提交。<br />
您同時也要向我們保證您所提交的內容是您自己所作，或得自一個不受版權保護或相似自由的來源（參閱$1的細節）。
'''不要在未獲授權的情況下發表！'''",
'longpageerror'                    => "'''錯誤: 您所提交的文字長度有$1KB，這大於$2KB的最大值。'''該文本不能被儲存。",
'readonlywarning'                  => "'''警告: 資料庫被鎖定以進行維護，所以您目前將無法保存您的修改。'''您或許希望先將本段文字複製並保存到文字文件，然後等一會兒再修改。

鎖定資料庫的管理員有如下解釋：$1",
'protectedpagewarning'             => "'''警告：本頁已經被保護，只有擁有管理員許可權的用戶才可修改。'''
最近的日誌在下面提供以便參考：",
'semiprotectedpagewarning'         => "'''注意：'''本頁面被保護，僅限註冊用戶編輯。
最近的日誌在下面提供以便參考：",
'cascadeprotectedwarning'          => "'''警告：'''本頁已經被保護，只有擁有管理員權限的用戶才可修改，因為本頁已被以下連鎖保護的{{PLURAL:$1|一個|多個}}頁面所包含:",
'titleprotectedwarning'            => "'''警告：本頁面已被保護，需要[[Special:ListGroupRights|指定權限]]方可創建。'''
最近的日誌在下面提供以便參考：",
'templatesused'                    => '此頁面包含以下{{PLURAL:$1|模板|模板}}:',
'templatesusedpreview'             => '此次預覽中使用的{{PLURAL:$1|模板|模板}}有:',
'templatesusedsection'             => '在這個段落上使用的{{PLURAL:$1|模板|模板}}有:',
'template-protected'               => '（保護）',
'template-semiprotected'           => '（半保護）',
'hiddencategories'                 => '這個頁面是屬於$1個隱藏分類的成員:',
'edittools'                        => '<!-- 此處的文字將被顯示在編輯和上傳表單以下。 -->',
'nocreatetitle'                    => '創建頁面受限',
'nocreatetext'                     => '{{SITENAME}}限制了創建新頁面的功能。{{GENDER:|你|妳|你}}可以返回並編輯已有的頁面，或者[[Special:UserLogin|登錄或創建新賬戶]]。',
'nocreate-loggedin'                => '您並無許可權去創建新頁面。',
'sectioneditnotsupported-title'    => '不支持段落編輯',
'sectioneditnotsupported-text'     => '此頁面不支持段落編輯。',
'permissionserrors'                => '權限錯誤',
'permissionserrorstext'            => '根據以下的{{PLURAL:$1|原因|原因}}，您並無權限去做以下的動作:',
'permissionserrorstext-withaction' => '根據以下的{{PLURAL:$1|原因|原因}}，您並無權限去做$2:',
'recreate-moveddeleted-warn'       => "'''警告: {{GENDER:|你|妳|你}}現在重新建立一個先前曾經刪除過的頁面。'''

{{GENDER:|你|妳|你}}應該要考慮一下繼續編輯這一個頁面是否合適。
為方便起見，這一個頁面的刪除記錄已經在下面提供:",
'moveddeleted-notice'              => '這個頁面已經刪除。
這個頁面的刪除和移動日誌已在下面提供以便參考。',
'log-fulllog'                      => '檢視完整日誌',
'edit-hook-aborted'                => '編輯被鈎取消。
它並無給出解釋。',
'edit-gone-missing'                => '不能更新頁面。
它可能剛剛被刪除。',
'edit-conflict'                    => '編輯衝突。',
'edit-no-change'                   => '您的編輯已經略過，因為文字無任何改動。',
'edit-already-exists'              => '不可以建立一個新頁面。
它已經存在。',

# Parser/template warnings
'expensive-parserfunction-warning'        => '警告: 這個頁面有太多耗費的語法功能呼叫。

它應該少過$2次呼叫，現在有$1次呼叫。',
'expensive-parserfunction-category'       => '頁面中有太多耗費的語法功能呼叫',
'post-expand-template-inclusion-warning'  => '警告: 包含模板大小過大。
一些模板將不會包含。',
'post-expand-template-inclusion-category' => '模板包含上限已經超過的頁面',
'post-expand-template-argument-warning'   => '警告: 這個頁面有最少一個模參數有過大擴展大小。
這些參數會被略過。',
'post-expand-template-argument-category'  => '包含着略過模板參數的頁面',
'parser-template-loop-warning'            => '已偵測迴歸模板: [[$1]]',
'parser-template-recursion-depth-warning' => '已超過迴歸模板深度限制 （$1）',
'language-converter-depth-warning'        => '已超過字詞轉換器深度限制（$1）',

# "Undo" feature
'undo-success' => '該編輯可以被撤銷。請檢查以下對比以核實這正是您想做的，然後儲存以下更改以完成撤銷編輯。',
'undo-failure' => '由於中途的編輯不一致，此編輯不能撤銷。',
'undo-norev'   => '由於其修訂版本不存在或已刪除，此編輯不能撤銷。',
'undo-summary' => '取消由[[Special:Contributions/$2|$2]] （[[User talk:$2|對話]]）所作出的修訂 $1',

# Account creation failure
'cantcreateaccounttitle' => '無法建立賬號',
'cantcreateaccount-text' => "從這個IP地址 （<b>$1</b>） 建立賬號已經被[[User:$3|$3]]禁止。

當中被$3封禁的原因是''$2''",

# History pages
'viewpagelogs'           => '查詢這個頁面的日誌',
'nohistory'              => '沒有本頁的修訂記錄。',
'currentrev'             => '最新修訂版本',
'currentrev-asof'        => '在$1的最新修訂版本',
'revisionasof'           => '在$1所做的修訂版本',
'revision-info'          => '在$1由$2所做的修訂版本',
'previousrevision'       => '←上一修訂',
'nextrevision'           => '下一修訂→',
'currentrevisionlink'    => '最新修訂',
'cur'                    => '目前',
'next'                   => '後繼',
'last'                   => '先前',
'page_first'             => '最前',
'page_last'              => '最後',
'histlegend'             => "差異選擇：標記要比較修訂版本的單選按鈕並點擊底部的按鈕進行比較。<br />
說明：'''（{{int:cur}}）''' 指與最新修訂版本比較，'''（{{int:last}}）''' 指與前一個修訂修訂版本比較，'''{{int:minoreditletter}}''' = 小修改。",
'history-fieldset-title' => '瀏覽歷史',
'history-show-deleted'   => '僅已刪除的',
'histfirst'              => '最早版本',
'histlast'               => '最新版本',
'historysize'            => '（$1 位元組）',
'historyempty'           => '（空）',

# Revision feed
'history-feed-title'          => '修訂歷史',
'history-feed-description'    => '本站上此頁的修訂歷史',
'history-feed-item-nocomment' => '$1 在 $2',
'history-feed-empty'          => '所請求的頁面不存在。它可能已被刪除或重新命名。
嘗試[[Special:Search|搜索本站]]獲得相關的新建頁面。',

# Revision deletion
'rev-deleted-comment'         => '（註釋已移除）',
'rev-deleted-user'            => '（用戶名已移除）',
'rev-deleted-event'           => '（日誌動作已移除）',
'rev-deleted-user-contribs'   => '[用戶名或IP地址已移除 - 從貢獻中隱藏編輯]',
'rev-deleted-text-permission' => "該頁面修訂已被'''刪除'''。
在[{{fullurl:{{#Special:Log}}/delete|page={{PAGENAMEE}}}} 刪除日誌]中可以找到詳細的訊息。",
'rev-deleted-text-unhide'     => "該頁面修訂已經被'''刪除'''。
在[{{fullurl:{{#Special:Log}}/delete|page={{PAGENAMEE}}}} 刪除日誌]中可以找到詳細的訊息。
作為管理員，如果您想繼續的話，您可以仍然[$1 去檢視這次修訂]。",
'rev-suppressed-text-unhide'  => "該頁面修訂已經被'''廢止'''。
在[{{fullurl:{{#Special:Log}}/suppress|page={{PAGENAMEE}}}} 廢止日誌]中可以找到詳細的訊息。
作為管理員，如果您想繼續的話，您可以仍然[$1 去檢視這次修訂]。",
'rev-deleted-text-view'       => "該頁面修訂已經被'''刪除'''。作為管理員，您可以檢視它；
在[{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} 刪除日誌]中可以找到詳細的訊息。",
'rev-suppressed-text-view'    => "該頁面修訂已經被'''廢止'''。作為管理員，您可以檢視它；
在[{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} 廢止日誌]中可以找到詳細的訊息。",
'rev-deleted-no-diff'         => "因為其中一次修訂已被'''刪除'''，您不可以檢視這個差異。
在[{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} 刪除日誌]中可以找到更多的資料。",
'rev-suppressed-no-diff'      => "該頁面的其中一次修訂已經被'''刪除'''，你不可以查看這次的修訂。",
'rev-deleted-unhide-diff'     => "該頁面的其中一次修訂已經被'''刪除'''。
在[{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} 刪除日誌]中可以找到更多的資料。
作為管理員，如果您想繼續的話，您可以仍然[$1 去檢視這次修訂]。",
'rev-suppressed-unhide-diff'  => "該頁面的其中一次修訂已經被'''廢止'''。
在[{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} 廢止日誌]中可以找到更多的資料。
作為管理員，如果您想繼續的話，您可以仍然[$1 去檢視這次修訂]。",
'rev-deleted-diff-view'       => "差異中的一次修訂已被'''刪除'''。
作為管理員，您可以檢視此差異。詳細訊息可在[{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} 刪除日誌]中找到。",
'rev-suppressed-diff-view'    => "差異中的一次修訂已被'''廢止'''。
作為管理員，您可以檢視此差異。詳細訊息可在[{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} 廢止日誌]中找到。",
'rev-delundel'                => '顯示/隱藏',
'rev-showdeleted'             => '顯示',
'revisiondelete'              => '刪除/恢復刪除修訂',
'revdelete-nooldid-title'     => '無效的目標修訂',
'revdelete-nooldid-text'      => '您尚未指定一個目標修訂去進行這個功能、
所指定的修訂不存在，或者您嘗試去隱藏現時的修訂。',
'revdelete-nologtype-title'   => '沒有給出日誌類型',
'revdelete-nologtype-text'    => '您尚未指定一種日誌類型去做這個動作。',
'revdelete-nologid-title'     => '無效的日誌項目',
'revdelete-nologid-text'      => '您尚未指定一個目標日誌項目去進行這個動作或指定的項目不存在。',
'revdelete-no-file'           => '指定的檔案不存在。',
'revdelete-show-file-confirm' => '{{GENDER:|你|妳|你}}是否真的是想去檢視於$2 $3刪除 "$1" 的檔案修訂？',
'revdelete-show-file-submit'  => '是',
'revdelete-selected'          => "'''選取[[:$1]]的$2次修訂:'''",
'logdelete-selected'          => "'''選取'''$1'''的日誌項目:'''",
'revdelete-text'              => "'''刪除的修訂仍將顯示在頁面歷史中, 但它們的文字內容已不能被公眾訪問。'''
在{{SITENAME}}的其他管理員將仍能訪問隱藏的內容並透過與此相同的介面恢復刪除，除非網站工作者進行了一些附加的限制。",
'revdelete-confirm'           => '請確認您肯定去做的話，您就要明白到後果，以及這個程序符合[[{{MediaWiki:Policy-url}}|政策]]。',
'revdelete-suppress-text'     => "壓制'''只'''應在以下的情況下進行:
* 不合適的個人資料
*: ''住家地址、電話號碼、社群保安號碼等。''",
'revdelete-legend'            => '設定可見性之限制',
'revdelete-hide-text'         => '隱藏修訂文字',
'revdelete-hide-image'        => '隱藏檔案內容',
'revdelete-hide-name'         => '隱藏動作和目標',
'revdelete-hide-comment'      => '隱藏編輯摘要',
'revdelete-hide-user'         => '隱藏編輯者的用戶名/IP地址',
'revdelete-hide-restricted'   => '同時廢止由操作員以及其他用戶的資料',
'revdelete-radio-same'        => '(勿更改)',
'revdelete-radio-set'         => '是',
'revdelete-radio-unset'       => '否',
'revdelete-suppress'          => '同時廢止由操作員以及其他用戶的資料',
'revdelete-unsuppress'        => '在已恢復的修訂中移除限制',
'revdelete-log'               => '理由：',
'revdelete-submit'            => '應用於選取的{{PLURAL:$1|修訂}}',
'revdelete-logentry'          => '「[[$1]]」的修訂可見性已更改',
'logdelete-logentry'          => '「[[$1]]」的事件可見性已更改',
'revdelete-success'           => "'''修訂的可見性已經成功更新。'''",
'revdelete-failure'           => "'''修訂的可見性無法更新：'''
$1",
'logdelete-success'           => "'''事件的可見性已經成功設定。'''",
'logdelete-failure'           => "'''事件的可見性無法設定：'''
$1",
'revdel-restore'              => '更改可見性',
'revdel-restore-deleted'      => '已刪除的修訂版本',
'revdel-restore-visible'      => '可見的修訂版本',
'pagehist'                    => '頁面歷史',
'deletedhist'                 => '已刪除之歷史',
'revdelete-content'           => '內容',
'revdelete-summary'           => '編輯摘要',
'revdelete-uname'             => '用戶名',
'revdelete-restricted'        => '已應用限制至操作員',
'revdelete-unrestricted'      => '已移除對於操作員的限制',
'revdelete-hid'               => '隱藏 $1',
'revdelete-unhid'             => '不隱藏 $1',
'revdelete-log-message'       => '$1的$2次修訂',
'logdelete-log-message'       => '$1的$2項事件',
'revdelete-hide-current'      => '正在隱藏於$1 $2之項目錯誤：這個是現時的修訂，不可以隱藏。',
'revdelete-show-no-access'    => '正在顯示於$1 $2之項目錯誤：這個項目已經標示為"已限制"，您對它並無通行權。',
'revdelete-modify-no-access'  => '正在更改於$1 $2之項目錯誤：這個項目已經標示為"已限制"，您對它並無通行權。',
'revdelete-modify-missing'    => '正在更改項目ID $1錯誤：它在資料庫中遺失！',
'revdelete-no-change'         => '警告：於$1 $2之項目已經請求了可見性的設定。',
'revdelete-concurrent-change' => '正在更改於$1 $2之項目錯誤：當我們嘗試更改它的設定時，已經被另一些人更改過。請檢查紀錄。',
'revdelete-only-restricted'   => '在隱藏$1 $2的項目時發生錯誤：您不能在選擇了另一可見性選項後廢止管理員查看該項目。',
'revdelete-reason-dropdown'   => '*常用刪除理由
** 侵犯版權
** 不合適的個人資料',
'revdelete-otherreason'       => '其它／附加的理由：',
'revdelete-reasonotherlist'   => '其它理由',
'revdelete-edit-reasonlist'   => '編輯刪除埋由',
'revdelete-offender'          => '修訂著者：',

# Suppression log
'suppressionlog'     => '廢止日誌',
'suppressionlogtext' => '以下是刪除以及由操作員牽涉到內容封鎖的清單。
參看[[Special:IPBlockList|IP封鎖名單]]去參看現時進行中的禁止以及封鎖之名單。',

# Revision move
'moverevlogentry'              => '移動了$1的{{PLURAL:$3|一次修訂版本|$3次修訂版本}}至$2',
'revisionmove'                 => '由 "$1" 移動修訂版本',
'revmove-explain'              => '以下的修訂版本將會由$1移動至所指定的目標頁面。如果目標不存在的話，它就將會建立。否則，這些的修訂版本就將會合併到頁面歷史中。',
'revmove-legend'               => '設定目標頁面以及摘要',
'revmove-submit'               => '移動修訂版本到所選定的頁面上',
'revisionmoveselectedversions' => '移動已選取的修訂版本',
'revmove-reasonfield'          => '理由：',
'revmove-titlefield'           => '目標頁面：',
'revmove-badparam-title'       => '壞的參數',
'revmove-badparam'             => '您的請求含有不合法的者不足的參數。
請返回先前的頁面再試。',
'revmove-norevisions-title'    => '無效的目標修訂版本',
'revmove-norevisions'          => '您尚未指定一個或者多個目標修訂版本去做這項功能或者所指定的修訂版本不存在。',
'revmove-nullmove-title'       => '壞的標題',
'revmove-nullmove'             => '目標頁面不可以跟來源頁面相同。
請返回先前的頁面再輸入跟 "$1" 不相同的名字。',
'revmove-success-existing'     => '由[[$2]]中的{{PLURAL:$1|一次修訂版本|$1次修訂版本}}已經移動至現有的頁面[[$3]]。',
'revmove-success-created'      => '由[[$2]]中的{{PLURAL:$1|一次修訂版本|$1次修訂版本}}已經移動至新建的頁面[[$3]]。',

# History merging
'mergehistory'                     => '合併頁面歷史',
'mergehistory-header'              => '這一頁可以講您合併一個來源頁面的歷史到另一個新頁面中。
請確認這次更改會繼續保留該頁面先前的歷史版本。',
'mergehistory-box'                 => '合併兩個頁面的修訂:',
'mergehistory-from'                => '來源頁面:',
'mergehistory-into'                => '目的頁面:',
'mergehistory-list'                => '可以合併的編輯歷史',
'mergehistory-merge'               => '以下[[:$1]]的修訂可以合併到[[:$2]]。用該選項按鈕欄去合併只有在指定時間以前所創建的修訂。要留意的是使用導航連結便會重設這一欄。',
'mergehistory-go'                  => '顯示可以合併的編輯',
'mergehistory-submit'              => '合併修訂',
'mergehistory-empty'               => '沒有修訂可以合併',
'mergehistory-success'             => '[[:$1]]的$3次修訂已經成功地合併到[[:$2]]。',
'mergehistory-fail'                => '不可以進行歷史合併，請重新檢查該頁面以及時間參數。',
'mergehistory-no-source'           => '來源頁面$1不存在。',
'mergehistory-no-destination'      => '目的頁面$1不存在。',
'mergehistory-invalid-source'      => '來源頁面必須是一個有效的標題。',
'mergehistory-invalid-destination' => '目的頁面必須是一個有效的標題。',
'mergehistory-autocomment'         => '已經合併[[:$1]]去到[[:$2]]',
'mergehistory-comment'             => '已經合併[[:$1]]去到[[:$2]]: $3',
'mergehistory-same-destination'    => '來源頁面與目的頁面不可以相同',
'mergehistory-reason'              => '理由：',

# Merge log
'mergelog'           => '合併日誌',
'pagemerge-logentry' => '已合併[[$1]]到[[$2]] （修訂截至$3）',
'revertmerge'        => '解除合併',
'mergelogpagetext'   => '以下是一個最近由一個頁面的修訂歷史合併到另一個頁面的列表。',

# Diffs
'history-title'            => '「$1」的修訂歷史',
'difference'               => '（修訂版本間的差異）',
'difference-multipage'     => '（頁面間的差異）',
'lineno'                   => '第$1行：',
'compareselectedversions'  => '比較選定的修訂版本',
'showhideselectedversions' => '顯示／隱藏選定的修訂版本',
'editundo'                 => '撤銷',
'diff-multi'               => '（由{{PLURAL:$2|1名用戶|$2名用戶}}作出的{{PLURAL:$1|一個中途修訂版本|$1個中途修訂版本}}未被顯示）',
'diff-multi-manyusers'     => '（由多於$2名用戶作出的{{PLURAL:$1|一個中途修訂版本|$1個中途修訂版本}} 未被顯示）',

# Search results
'searchresults'                    => '搜尋結果',
'searchresults-title'              => '對「$1」的搜尋結果',
'searchresulttext'                 => '有關搜索{{SITENAME}}的更多詳情,參見[[{{MediaWiki:Helppage}}|{{int:help}}]]。',
'searchsubtitle'                   => '查詢\'\'\'[[:$1]]\'\'\'（[[Special:Prefixindex/$1|所有以 "$1" 開頭的頁面]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|所有連結到 "$1" 的頁面]]）',
'searchsubtitleinvalid'            => '查詢"$1"',
'toomanymatches'                   => '過多的匹配已回應，請嘗試一個不同的查詢',
'titlematches'                     => '頁面標題相符',
'notitlematches'                   => '沒有找到匹配頁面題目',
'textmatches'                      => '頁面內容相符',
'notextmatches'                    => '沒有頁面內容匹配',
'prevn'                            => '前$1個',
'nextn'                            => '後{{PLURAL:$1|$1}}個',
'prevn-title'                      => '前$1項結果',
'nextn-title'                      => '後$1項結果',
'shown-title'                      => '每頁顯示$1項結果',
'viewprevnext'                     => '檢視 （$1 {{int:pipe-separator}} $2） （$3）',
'searchmenu-legend'                => '搜尋選項',
'searchmenu-exists'                => "'''在這個 wiki 上已有一頁面叫做「[[:$1]]」。'''",
'searchmenu-new'                   => "'''在這個 wiki 上建立這個頁面「[[:$1]]」！'''",
'searchmenu-new-nocreate'          => '「$1」是一個無效的頁面名稱或無法被您創建。',
'searchhelp-url'                   => 'Help:目錄',
'searchmenu-prefix'                => '[[Special:PrefixIndex/$1|去瀏覽以此為首的頁面]]',
'searchprofile-articles'           => '內容頁面',
'searchprofile-project'            => '幫助和計劃頁面',
'searchprofile-images'             => '多媒體',
'searchprofile-everything'         => '全部',
'searchprofile-advanced'           => '進階',
'searchprofile-articles-tooltip'   => '在$1中搜尋',
'searchprofile-project-tooltip'    => '在$1中搜尋',
'searchprofile-images-tooltip'     => '搜尋檔案',
'searchprofile-everything-tooltip' => '搜尋全部（包括討論頁面）',
'searchprofile-advanced-tooltip'   => '在自定名字空間中度搜尋',
'search-result-size'               => '$1 （$2個字）',
'search-result-category-size'      => '$1位成員（$2個子分類，$3個檔案）',
'search-result-score'              => '相關度: $1%',
'search-redirect'                  => '（重定向 $1）',
'search-section'                   => '（段落 $1）',
'search-suggest'                   => '{{GENDER:|你|妳|你}}是否解: $1',
'search-interwiki-caption'         => '姊妹計劃',
'search-interwiki-default'         => '$1項結果:',
'search-interwiki-more'            => '（更多）',
'search-mwsuggest-enabled'         => '有建議',
'search-mwsuggest-disabled'        => '無建議',
'search-relatedarticle'            => '相關',
'mwsuggest-disable'                => '停用AJAX建議',
'searcheverything-enable'          => '在所有名字空間中搜尋',
'searchrelated'                    => '相關',
'searchall'                        => '所有',
'showingresults'                   => '下面顯示從第 <b>$2</b> 條開始的 <b>$1</b> 條結果：',
'showingresultsnum'                => "下面顯示從第 '''$2''' 條開始的 '''{{PLURAL:$3|1|$3}}''' 條結果。",
'showingresultsheader'             => "對'''$4'''的{{PLURAL:$5|第'''$1'''至第'''$3'''項結果|第'''$1 - $2'''項，共'''$3'''項結果}}",
'nonefound'                        => "'''注意''': 只有一些名字空間是會作為預設搜尋。嘗試''all:''去搜尋全部的頁面（包埋討論頁面、模板等），或可用需要的名字空間作為前綴。",
'search-nonefound'                 => '在查詢中無結果配合。',
'powersearch'                      => '進階搜尋',
'powersearch-legend'               => '進階搜尋',
'powersearch-ns'                   => '在以下的名字空間中搜尋：',
'powersearch-redir'                => '重新定向清單',
'powersearch-field'                => '搜尋',
'powersearch-togglelabel'          => '核取：',
'powersearch-toggleall'            => '所有',
'powersearch-togglenone'           => '無',
'search-external'                  => '外部搜索',
'searchdisabled'                   => '{{SITENAME}}由於性能方面的原因，全文搜索已被暫時停用。您可以暫時透過Google搜索。請留意他們的索引可能會過時。',

# Quickbar
'qbsettings'               => '快速導航條',
'qbsettings-none'          => '無',
'qbsettings-fixedleft'     => '左側固定',
'qbsettings-fixedright'    => '右側固定',
'qbsettings-floatingleft'  => '左側漂移',
'qbsettings-floatingright' => '右側漂移',

# Preferences page
'preferences'                   => '偏好設定',
'mypreferences'                 => '我的偏好設定',
'prefs-edits'                   => '編輯數量：',
'prefsnologin'                  => '還未登入',
'prefsnologintext'              => '您必須先<span class="plainlinks">[{{fullurl:{{#Special:UserLogin}}|returnto=$1}} 登入]</span>才能設置個人參數。',
'changepassword'                => '更改密碼',
'prefs-skin'                    => '外觀',
'skin-preview'                  => '預覽',
'prefs-math'                    => '數學公式',
'datedefault'                   => '預設值',
'prefs-datetime'                => '日期和時間',
'prefs-personal'                => '用戶資料',
'prefs-rc'                      => '最近更改',
'prefs-watchlist'               => '監視列表',
'prefs-watchlist-days'          => '監視列表中顯示記錄的天數:',
'prefs-watchlist-days-max'      => '最多 7 天',
'prefs-watchlist-edits'         => '在增強的監視列表中顯示的最多更改次數:',
'prefs-watchlist-edits-max'     => '最大數量：1000',
'prefs-watchlist-token'         => '監視列表密鑰：',
'prefs-misc'                    => '雜項',
'prefs-resetpass'               => '更改密碼',
'prefs-email'                   => '電郵選項',
'prefs-rendering'               => '外觀',
'saveprefs'                     => '儲存',
'resetprefs'                    => '清除未保存的更改',
'restoreprefs'                  => '恢復所有預設設定',
'prefs-editing'                 => '編輯',
'prefs-edit-boxsize'            => '編輯框尺寸',
'rows'                          => '列:',
'columns'                       => '欄:',
'searchresultshead'             => '搜尋結果設定',
'resultsperpage'                => '每頁顯示連結數',
'contextlines'                  => '每連結行數:',
'contextchars'                  => '每行字數:',
'stub-threshold'                => '<a href="#" class="stub">短頁面連結</a>格式門檻值 （位元組）:',
'stub-threshold-disabled'       => '已停用',
'recentchangesdays'             => '最近更改中的顯示日數:',
'recentchangesdays-max'         => '最多 $1 {{PLURAL:$1|天|天}}',
'recentchangescount'            => '預設顯示的編輯數：',
'prefs-help-recentchangescount' => '這個包括最近更改、頁面歷史以及日誌。',
'prefs-help-watchlist-token'    => '在這欄加入一個秘密鑰會生成一個對您監視列表中的 RSS 源。
任何一位知道在這個欄位上的匙可以閱讀到您的監視列表，請選擇一個安全的值。
這裡有一個任意生成的值，供您選擇：$1',
'savedprefs'                    => '您的個人偏好設定已經儲存。',
'timezonelegend'                => '時區：',
'localtime'                     => '當地時間:',
'timezoneuseserverdefault'      => '使用伺服器預設值',
'timezoneuseoffset'             => '其他 （指定偏移）',
'timezoneoffset'                => '時差¹:',
'servertime'                    => '伺服器時間:',
'guesstimezone'                 => '從瀏覽器填寫',
'timezoneregion-africa'         => '非洲',
'timezoneregion-america'        => '美洲',
'timezoneregion-antarctica'     => '南極洲',
'timezoneregion-arctic'         => '北極',
'timezoneregion-asia'           => '亞洲',
'timezoneregion-atlantic'       => '大西洋',
'timezoneregion-australia'      => '澳洲',
'timezoneregion-europe'         => '歐洲',
'timezoneregion-indian'         => '印度洋',
'timezoneregion-pacific'        => '太平洋',
'allowemail'                    => '接受來自其他用戶的郵件',
'prefs-searchoptions'           => '搜尋選項',
'prefs-namespaces'              => '頁面名稱空間',
'defaultns'                     => '否則在這些名字空間搜尋：',
'default'                       => '預設',
'prefs-files'                   => '檔案',
'prefs-custom-css'              => '自定CSS',
'prefs-custom-js'               => '自定JavaScript',
'prefs-common-css-js'           => '共用 CSS/JavaScript 於所有的外觀中：',
'prefs-reset-intro'             => '您可以利用這個頁面去重設您的參數設置到網站預設值。這個動作無法復原。',
'prefs-emailconfirm-label'      => '電子郵件確認：',
'prefs-textboxsize'             => '編輯框大小',
'youremail'                     => '電子郵件:',
'username'                      => '用戶名:',
'uid'                           => '用戶ID:',
'prefs-memberingroups'          => '{{PLURAL:$1|一|多}}組的成員:',
'prefs-registration'            => '註冊時間:',
'yourrealname'                  => '真實姓名：',
'yourlanguage'                  => '介面語言：',
'yourvariant'                   => '字體變換:',
'yournick'                      => '新簽名:',
'prefs-help-signature'          => '在討論頁面上的評論應該要用「<nowiki>~~~~</nowiki>」簽名，這樣便會轉換成{{GENDER:|你|妳|你}}的簽名以及一個時間截記。',
'badsig'                        => '錯誤的原始簽名。請檢查HTML標籤。',
'badsiglength'                  => '您的簽名過長。
它的長度不可超過$1個字元。',
'yourgender'                    => '性別：',
'gender-unknown'                => '未指定',
'gender-male'                   => '男',
'gender-female'                 => '女',
'prefs-help-gender'             => '可選：用於軟體中的性別指定。此項資料將會被公開。',
'email'                         => '電子郵件',
'prefs-help-realname'           => '真實姓名是可選的。
如果您選擇提供它，那它便用以對您的貢獻署名。',
'prefs-help-email'              => '電子郵件是可選的，但當您忘記您的密碼時需要將新密碼重設，就會用電郵寄回給您。',
'prefs-help-email-others'       => '您亦可以在您沒有公開自己的用戶身分時透過您的用戶頁或用戶討論頁與您聯繫。',
'prefs-help-email-required'     => '需要電子郵件地址。',
'prefs-info'                    => '基本資料',
'prefs-i18n'                    => '國際化',
'prefs-signature'               => '簽名',
'prefs-dateformat'              => '日期格式',
'prefs-timeoffset'              => '時間偏移',
'prefs-advancedediting'         => '進階選項',
'prefs-advancedrc'              => '進階選項',
'prefs-advancedrendering'       => '進階選項',
'prefs-advancedsearchoptions'   => '進階選項',
'prefs-advancedwatchlist'       => '進階選項',
'prefs-displayrc'               => '顯示選項',
'prefs-displaysearchoptions'    => '顯示選項',
'prefs-displaywatchlist'        => '顯示選項',
'prefs-diffs'                   => '差異',

# User preference: e-mail validation using jQuery
'email-address-validity-valid'   => '電子郵件地址有效',
'email-address-validity-invalid' => '請提供一個有效的電子郵件地址',

# User rights
'userrights'                   => '用戶權限管理',
'userrights-lookup-user'       => '管理用戶群組',
'userrights-user-editname'     => '輸入用戶名:',
'editusergroup'                => '編輯用戶群組',
'editinguser'                  => "正在更改用戶'''[[User:$1|$1]]''' （[[User talk:$1|{{int:talkpagelinktext}}]]{{int:pipe-separator}}[[Special:Contributions/$1|{{int:contribslink}}]]） 的用戶權限",
'userrights-editusergroup'     => '編輯用戶群組',
'saveusergroups'               => '保存用戶群組',
'userrights-groupsmember'      => '屬於:',
'userrights-groupsmember-auto' => '固有屬於:',
'userrights-groups-help'       => '您可以改動這位用戶所屬的群組:
* 已剔選的核取方塊代表該用戶屬於該群組。
* 未剔選的核取方塊代表該用戶不是屬於該群組。
* 有 * 項目表示一旦您加入該群組之後便不能移除它，反之亦然。',
'userrights-reason'            => '原因：',
'userrights-no-interwiki'      => '您並沒有權限去編輯在其它wiki上的用戶權限。',
'userrights-nodatabase'        => '資料庫$1不存在或並非為本地的。',
'userrights-nologin'           => '您必須要以操作員賬戶[[Special:UserLogin|登入]]之後才可以指定用戶權限。',
'userrights-notallowed'        => '您的賬戶無權限去指定用戶權限。',
'userrights-changeable-col'    => '您可以更改的群組',
'userrights-unchangeable-col'  => '您不可以更改的群組',

# Groups
'group'               => '群組:',
'group-user'          => '用戶',
'group-autoconfirmed' => '自動確認用戶',
'group-bot'           => '機器人',
'group-sysop'         => '操作員',
'group-bureaucrat'    => '行政員',
'group-suppress'      => '監督',
'group-all'           => '（全部）',

'group-user-member'          => '用戶',
'group-autoconfirmed-member' => '自動確認用戶',
'group-bot-member'           => '機器人',
'group-sysop-member'         => '操作員',
'group-bureaucrat-member'    => '行政員',
'group-suppress-member'      => '監督',

'grouppage-user'          => '{{ns:project}}:用戶',
'grouppage-autoconfirmed' => '{{ns:project}}:自動確認用戶',
'grouppage-bot'           => '{{ns:project}}:機器人',
'grouppage-sysop'         => '{{ns:project}}:操作員',
'grouppage-bureaucrat'    => '{{ns:project}}:行政員',
'grouppage-suppress'      => '{{ns:project}}:監督',

# Rights
'right-read'                  => '閱讀頁面',
'right-edit'                  => '編輯頁面',
'right-createpage'            => '建立頁面（不含討論頁面）',
'right-createtalk'            => '建立討論頁面',
'right-createaccount'         => '創建新用戶賬戶',
'right-minoredit'             => '標示作小編輯',
'right-move'                  => '移動頁面',
'right-move-subpages'         => '移動頁面跟它的子頁面',
'right-move-rootuserpages'    => '移動根用戶頁面',
'right-movefile'              => '移動檔案',
'right-suppressredirect'      => '當移動頁面時不建立來源頁面之重定向',
'right-upload'                => '上傳檔案',
'right-reupload'              => '覆蓋現有的檔案',
'right-reupload-own'          => '覆蓋由同一位上載的檔案',
'right-reupload-shared'       => '於本地無視共用媒體檔案庫上的檔案',
'right-upload_by_url'         => '由一個URL上載檔案',
'right-purge'                 => '不需要確認之下清除網站快取',
'right-autoconfirmed'         => '編輯半保護頁面',
'right-bot'                   => '視為一個自動程序',
'right-nominornewtalk'        => '小編輯不引發新訊息提示',
'right-apihighlimits'         => '在API查詢中使用更高的上限',
'right-writeapi'              => '使用API編寫',
'right-delete'                => '刪除頁面',
'right-bigdelete'             => '刪除大量歷史之頁面',
'right-deleterevision'        => '刪除及同反刪除頁面中的指定修訂',
'right-deletedhistory'        => '檢視已刪除之歷史項目，不含關聯的文本',
'right-deletedtext'           => '檢視已刪除修訂中之已刪除的字以及更改',
'right-browsearchive'         => '搜尋已刪除之頁面',
'right-undelete'              => '反刪除頁面',
'right-suppressrevision'      => '檢視及恢復由操作員隱藏之修訂',
'right-suppressionlog'        => '檢視私人的日誌',
'right-block'                 => '封鎖其他用戶防止編輯',
'right-blockemail'            => '封鎖用戶不可發電郵',
'right-hideuser'              => '封鎖用戶名，對公眾隱藏',
'right-ipblock-exempt'        => '繞過IP封鎖、自動封鎖以及範圍封鎖',
'right-proxyunbannable'       => '繞過Proxy的自動封鎖',
'right-unblockself'           => '自我解除封鎖',
'right-protect'               => '更改保護等級以及編輯保護頁面',
'right-editprotected'         => '編輯保護頁面（無連鎖保護）',
'right-editinterface'         => '編輯用戶界面',
'right-editusercssjs'         => '編輯其他用戶的CSS和JavaScript檔案',
'right-editusercss'           => '編輯其他用戶的CSS檔案',
'right-edituserjs'            => '編輯其他用戶的JavaScript檔案',
'right-rollback'              => '快速復原上位用戶對某一頁面之編輯',
'right-markbotedits'          => '標示復原編輯作機械人編輯',
'right-noratelimit'           => '沒有使用頻率限制',
'right-import'                => '由其它wiki中匯入頁面',
'right-importupload'          => '由檔案上載中匯入頁面',
'right-patrol'                => '標示其它的編輯作已巡查的',
'right-autopatrol'            => '將自己的編輯自動標示為已巡查的',
'right-patrolmarks'           => '檢視最近巡查標記更改',
'right-unwatchedpages'        => '檢視未監視之頁面',
'right-trackback'             => '遞交一個trackback',
'right-mergehistory'          => '合併頁面歷史',
'right-userrights'            => '編輯所有用戶的權限',
'right-userrights-interwiki'  => '編輯在其它wiki上的用戶權限',
'right-siteadmin'             => '鎖定和解除鎖定資料庫',
'right-reset-passwords'       => '重設其他用戶的密碼',
'right-override-export-depth' => '匯出含有五層深度連結頁面之頁面',
'right-sendemail'             => '發電子郵件給其他用戶',
'right-revisionmove'          => '移動修訂版本',
'right-disableaccount'        => '禁用賬號',

# User rights log
'rightslog'      => '用戶權限日誌',
'rightslogtext'  => '以下記錄了用戶權限的更改記錄。',
'rightslogentry' => '將 $1 的權限從 $2 改為 $3',
'rightsnone'     => '無',

# Associated actions - in the sentence "You do not have permission to X"
'action-read'                 => '閱讀這個頁面',
'action-edit'                 => '編輯這個頁面',
'action-createpage'           => '建立這個頁面',
'action-createtalk'           => '建立討論頁面',
'action-createaccount'        => '建立這個用戶賬戶',
'action-minoredit'            => '標示這個編輯為小的',
'action-move'                 => '移動這個頁面',
'action-move-subpages'        => '移動這個頁面跟它的子頁面',
'action-move-rootuserpages'   => '移動根用戶頁面',
'action-movefile'             => '移動這個檔案',
'action-upload'               => '上載這個檔案',
'action-reupload'             => '覆蓋這個現有的檔案',
'action-reupload-shared'      => '覆蓋在共用檔案庫上的檔案',
'action-upload_by_url'        => '由一個URL中上載檔案',
'action-writeapi'             => '用來寫API',
'action-delete'               => '刪除這個頁面',
'action-deleterevision'       => '刪除這次修訂',
'action-deletedhistory'       => '檢視這個頁面的刪除歷史',
'action-browsearchive'        => '搜尋已刪除的頁面',
'action-undelete'             => '反刪除這個頁面',
'action-suppressrevision'     => '翻查和恢復這次隱藏修訂',
'action-suppressionlog'       => '檢視這個私有日誌',
'action-block'                => '封鎖這位用戶的編輯',
'action-protect'              => '更改這個頁面的保護等級',
'action-import'               => '由另一個wiki匯入這個頁面',
'action-importupload'         => '由一個檔案上載中匯入這個頁面',
'action-patrol'               => '標示其它的編輯為已巡查的',
'action-autopatrol'           => '將您的編輯標示為已巡查的',
'action-unwatchedpages'       => '檢視未被人監視的頁面',
'action-trackback'            => '遞交一個trackback',
'action-mergehistory'         => '合併這個頁面的歷史',
'action-userrights'           => '編輯所有的權限',
'action-userrights-interwiki' => '編輯在其它wiki上用戶的權限',
'action-siteadmin'            => '鎖定和解除鎖定資料庫',
'action-revisionmove'         => '移動修訂',

# Recent changes
'nchanges'                          => '$1次更改',
'recentchanges'                     => '最近更改',
'recentchanges-legend'              => '最近更改選項',
'recentchangestext'                 => '跟蹤這個wiki上的最新更改。',
'recentchanges-feed-description'    => '追蹤此訂閱在 wiki 上的最近更改。',
'recentchanges-label-newpage'       => '這次編輯建立了一個新頁面',
'recentchanges-label-minor'         => '這是一個小編輯',
'recentchanges-label-bot'           => '這次編輯是由機器人進行',
'recentchanges-label-unpatrolled'   => '這次編輯尚未巡查過',
'rcnote'                            => "以下是在$4 $5，最近'''$2'''天內的'''$1'''次最近更改記錄:",
'rcnotefrom'                        => "下面是自'''$2'''（最多顯示'''$1'''）:",
'rclistfrom'                        => '顯示自$1以來的新更改',
'rcshowhideminor'                   => '$1小編輯',
'rcshowhidebots'                    => '$1機器人的編輯',
'rcshowhideliu'                     => '$1已登入用戶的編輯',
'rcshowhideanons'                   => '$1匿名用戶的編輯',
'rcshowhidepatr'                    => '$1巡查過的編輯',
'rcshowhidemine'                    => '$1我的編輯',
'rclinks'                           => '顯示最近$2天內最新的$1次改動。<br />$3',
'diff'                              => '差異',
'hist'                              => '歷史',
'hide'                              => '隱藏',
'show'                              => '顯示',
'minoreditletter'                   => '小',
'newpageletter'                     => '新',
'boteditletter'                     => '機',
'number_of_watching_users_pageview' => '[$1個關注用戶]',
'rc_categories'                     => '分類界限（以"|"分割）',
'rc_categories_any'                 => '任意',
'newsectionsummary'                 => '/* $1 */ 新段落',
'rc-enhanced-expand'                => '顯示細節 （需要 JavaScript）',
'rc-enhanced-hide'                  => '隱藏細節',

# Recent changes linked
'recentchangeslinked'          => '連出更改',
'recentchangeslinked-feed'     => '連出更改',
'recentchangeslinked-toolbox'  => '連出更改',
'recentchangeslinked-title'    => '對於「$1」有關的連出更改',
'recentchangeslinked-noresult' => '在這一段時間中連結的頁面並無更改。',
'recentchangeslinked-summary'  => "這一個特殊頁面列示''由''所給出的一個頁面之連結到頁面的最近更改（或者是對於指定分類的成員）。
在[[Special:Watchlist|您的監視列表]]中的頁面會以'''粗體'''顯示。",
'recentchangeslinked-page'     => '頁面名稱:',
'recentchangeslinked-to'       => '顯示連到所給出的頁面',

# Upload
'upload'                      => '上傳檔案',
'uploadbtn'                   => '上傳檔案',
'reuploaddesc'                => '取消上載並返回上載表單',
'upload-tryagain'             => '提交修改後的檔案描述',
'uploadnologin'               => '未登入',
'uploadnologintext'           => '您必須先[[Special:UserLogin|登入]]
才能上載檔案。',
'upload_directory_missing'    => '上傳目錄（$1）遺失，不能由網頁伺服器建立。',
'upload_directory_read_only'  => '上傳目錄（$1）不存在或無寫權限。',
'uploaderror'                 => '上載錯誤',
'upload-recreate-warning'     => "'''警告：一個相同名字的檔案曾經被刪除或者移動至別處。'''

這個頁面的刪除和移動日誌在這裏提供以便參考：",
'uploadtext'                  => "使用下面的表單來上傳檔案。
要檢視或搜尋以前上傳的檔案，可以進入[[Special:FileList|檔案上傳清單]]，（重新）上傳也將在[[Special:Log/upload|上傳日誌]]中記錄，而刪除將在[[Special:Log/delete|刪除日誌]]中記錄。

要在頁面中加入檔案，使用以下其中一種形式的連結：
* '''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:File.jpg]]</nowiki></tt>'''使用檔案的完整版本
* '''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:File.png|200px|thumb|left|替換文字]]</nowiki></tt>'''使用放置於左側的一個框內的 200 像素寬的圖片，同時使用「替換文字」作為描述
* '''<tt><nowiki>[[</nowiki>{{ns:media}}<nowiki>:File.ogg]]</nowiki></tt>'''直接連結到檔案而不顯示檔案",
'upload-permitted'            => '准許的檔案類型: $1。',
'upload-preferred'            => '建議的檔案類型: $1。',
'upload-prohibited'           => '禁止的檔案類型: $1。',
'uploadlog'                   => '上載紀錄',
'uploadlogpage'               => '上載紀錄',
'uploadlogpagetext'           => '以下是最近上載的檔案的一覽表。
檢視[[Special:NewFiles|新檔案畫廊]]去看更富圖片的總覽。',
'filename'                    => '檔案名',
'filedesc'                    => '檔案描述',
'fileuploadsummary'           => '檔案描述:',
'filereuploadsummary'         => '檔案更改:',
'filestatus'                  => '版權狀態:',
'filesource'                  => '來源：',
'uploadedfiles'               => '已上載檔案',
'ignorewarning'               => '忽略警告並儲存檔案',
'ignorewarnings'              => '忽略所有警告',
'minlength1'                  => '檔案名字必須至少有一個字母。',
'illegalfilename'             => '檔案名“$1”包含有頁面標題所禁止的字符。請改名後重新上傳。',
'badfilename'                 => '檔案名已被改為「$1」。',
'filetype-mime-mismatch'      => '檔案擴展名 ".$1" 不配所偵測檔案的MIME類型 ($2)。',
'filetype-badmime'            => 'MIME類別"$1"不是容許的檔案格式。',
'filetype-bad-ie-mime'        => '不可以上傳這個檔案，因為 Internet Explorer 會將它偵測為 "$1"，它是一種不容許以及有潛在危險性之檔案類型。',
'filetype-unwanted-type'      => "'''\".\$1\"'''是一種不需要的檔案類型。
建議的{{PLURAL:\$3|一種|多種}}檔案類型有\$2。",
'filetype-banned-type'        => "'''\".\$1\"'''是一種不准許的檔案類型。
容許的{{PLURAL:\$3|一種|多種}}檔案類型有\$2。",
'filetype-missing'            => '該檔案名稱並沒有副檔名 （像 ".jpg"）。',
'empty-file'                  => '您所提交的檔案為空檔案。',
'file-too-large'              => '您所提交的檔案過大。',
'filename-tooshort'           => '檔案名過短。',
'filetype-banned'             => '此類檔案被禁止。',
'verification-error'          => '檔案未通過驗證。',
'hookaborted'                 => '您所嘗試的修改被擴展鈎捨棄。',
'illegal-filename'            => '檔案名非法。',
'overwrite'                   => '不允許覆蓋現有檔案。',
'unknown-error'               => '發生未知錯誤。',
'tmp-create-error'            => '無法建立臨時檔案。',
'tmp-write-error'             => '臨時檔案寫入發生錯誤。',
'large-file'                  => '建議檔案大小不能超過 $1；本檔案大小為 $2。',
'largefileserver'             => '這個檔案的大小比伺服器配置允許的大小還要大。',
'emptyfile'                   => '您所上傳的檔案不存在。這可能是由於檔案名鍵入錯誤。請檢查您是否真的要上傳此檔案。',
'fileexists'                  => "已存在相同名稱的檔案，如果您無法確定您是否要改變它，請檢查'''<tt>[[:$1]]</tt>'''。 [[$1|thumb]]",
'filepageexists'              => "這個檔案的描述頁已於'''<tt>[[:$1]]</tt>'''建立，但是這個名稱的檔案尚未存在。因此您所輸入的摘要不會顯示在該描述頁中。如要摘要在該處中出現，您必需手動編輯它。
[[$1|thumb]]",
'fileexists-extension'        => "一個相似檔名的檔案已經存在: [[$2|thumb]]
* 上載檔案的檔名: '''<tt>[[:$1]]</tt>'''
* 現有檔案的檔名: '''<tt>[[:$2]]</tt>'''
請選擇一個不同的名字。",
'fileexists-thumbnail-yes'    => "這個檔案好像是一幅圖片的縮圖版本''（縮圖）''。 [[$1|thumb]]
請檢查清楚該檔案'''<tt>[[:$1]]</tt>'''。
如果檢查後的檔案是同原本圖片的大小是一樣的話，就不用再上載多一幅縮圖。",
'file-thumbnail-no'           => "此圖片的檔案名稱以'''<tt>$1</tt>'''開始。它好像某幅圖片的縮小版本''（縮圖）''。
如果{{GENDER:|你|妳|你}}有該圖片的完整大小版本，請上載它；否則請修改檔名。",
'fileexists-forbidden'        => '已存在相同名稱的檔案，且不能覆蓋；請返回並用一個新的名稱來上傳此檔案。[[File:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => '在共享檔案庫中已存在此名稱的檔案。
如果{{GENDER:|你|妳|你}}仍然想去上載它的話，請返回並用一個新的名稱來上傳此檔案。[[File:$1|thumb|center|$1]]',
'file-exists-duplicate'       => '這個檔案是跟以下的{{PLURAL:$1|一|多}}個檔案重覆:',
'file-deleted-duplicate'      => '一個相同名稱的檔案 （[[$1]]） 在先前刪除過。您應該在重新上傳之前檢查一下該檔案之刪除紀錄。',
'uploadwarning'               => '上載警告',
'uploadwarning-text'          => '請修改以下的檔案描述並重試。',
'savefile'                    => '保存檔案',
'uploadedimage'               => '已上載「[[$1]]」',
'overwroteimage'              => '已經上載"[[$1]]"的新版本',
'uploaddisabled'              => '上傳己停用。',
'copyuploaddisabled'          => '通過網址上傳功能未開通。',
'uploadfromurl-queued'        => '上傳已被列入隊列。',
'uploaddisabledtext'          => '檔案上傳不可用。',
'php-uploaddisabledtext'      => 'PHP 檔案上載已經停用。請檢查 file_uploads 設定。',
'uploadscripted'              => '該檔案包含可能被網路瀏覽器錯誤解釋的 HTML 或腳本代碼。',
'uploadvirus'                 => '該檔案包含有病毒！
詳情: $1',
'upload-source'               => '來源檔案',
'sourcefilename'              => '來源檔案名：',
'sourceurl'                   => '來源網址：',
'destfilename'                => '目標檔案名：',
'upload-maxfilesize'          => '檔案最大限制大小: $1',
'upload-description'          => '檔案描述',
'upload-options'              => '上載選項',
'watchthisupload'             => '監視這個檔案',
'filewasdeleted'              => '之前已經有一個同名檔案被上傳後又被刪除了。在上傳此檔案之前您需要檢查$1。',
'upload-wasdeleted'           => "'''警告: 您現在重新上傳一個先前曾經刪除過的檔案。'''

您應該要考慮一下繼續上傳一個檔案頁面是否合適。
為方便起見，這一個檔案的刪除記錄已經在下面提供:",
'filename-bad-prefix'         => "您上傳的檔案名稱是以'''「$1」'''作為開頭，通常這種沒有含意的檔案名稱是由數碼相機中自動編排。請在您的檔案中重新選擇一個更加有意義的檔案名稱。",
'upload-success-subj'         => '上傳成功',
'upload-success-msg'          => '您在[$2]的上传已经成功，可以在这里找到：[[:{{ns:file}}:$1]]',
'upload-failure-subj'         => '上傳問題',
'upload-failure-msg'          => '您在[$2]的上傳出現了問題：

$1',
'upload-warning-subj'         => '上傳警告',
'upload-warning-msg'          => '您自[$2]的上傳出錯。您可以返回[[Special:Upload/stash/$1|上傳表單]]並更正問題。',

'upload-proto-error'        => '協議錯誤',
'upload-proto-error-text'   => '遠程上傳要求 URL 以 <code>http://</code> 或 <code>ftp://</code> 開頭。',
'upload-file-error'         => '內部錯誤',
'upload-file-error-text'    => '當試圖在伺服器上創建臨時檔案時發生內部錯誤。請與[[Special:ListUsers/sysop|管理員]]聯繫。',
'upload-misc-error'         => '未知的上傳錯誤',
'upload-misc-error-text'    => '在上傳時發生未知的錯誤。請驗証使用了正確並可訪問的 URL，然後進行重試。如果問題仍然存在，請與[[Special:ListUsers/sysop|管理員]]聯繫。',
'upload-too-many-redirects' => '在網址中有太多重新定向',
'upload-unknown-size'       => '未知的大小',
'upload-http-error'         => '已發生一個HTTP錯誤：$1',

# Special:UploadStash
'uploadstash'          => '上傳貯藏',
'uploadstash-summary'  => '這個頁面提供已經上傳（或者上傳中）但未發佈到wiki之檔案存取。這些檔案除了上傳的用戶之外不會被其他人可見。',
'uploadstash-clear'    => '清除貯藏檔案',
'uploadstash-nofiles'  => '{{GENDER:|你|妳|你}}沒有已貯藏的檔案。',
'uploadstash-badtoken' => '進行這個動作不成功，或者{{GENDER:|你|妳|你}}的編輯資訊已經過期。請再試。',
'uploadstash-errclear' => '清除檔案不成功。',
'uploadstash-refresh'  => '更新檔案清單',

# img_auth script messages
'img-auth-accessdenied' => '拒絕存取',
'img-auth-nopathinfo'   => 'PATH_INFO遺失。
您的伺服器還沒有設定這個資料。
它可能是以CGI為本，不支援img_auth。
參閱http://www.mediawiki.org/wiki/Manual:Image_Authorization。',
'img-auth-notindir'     => '所請求的路徑不在已經設定的上載目錄。',
'img-auth-badtitle'     => '不能夠由"$1"建立一個有效標題。',
'img-auth-nologinnWL'   => '您而家並未登入，"$1"不在白名單上。',
'img-auth-nofile'       => '檔案"$1"不存在。',
'img-auth-isdir'        => '您嘗試過存取一個目錄"$1"。
只是可以存取檔案。',
'img-auth-streaming'    => '串流中"$1"。',
'img-auth-public'       => 'img_auth.php的功能是由一個公共wiki中輸出檔案。
這個wiki是已經設定做一個公共wiki。
基於保安最佳化，img_auth.php已經停用。',
'img-auth-noread'       => '用戶無存取權去讀"$1"。',

# HTTP errors
'http-invalid-url'      => '無效的URL：$1',
'http-invalid-scheme'   => '不支援含有「$1」的URL。',
'http-request-error'    => '未知的錯誤令到HTTP請求失敗。',
'http-read-error'       => 'HTTP讀取錯誤。',
'http-timed-out'        => 'HTTP請求已過時。',
'http-curl-error'       => '擷取URL時出錯：$1',
'http-host-unreachable' => '無法到達URL。',
'http-bad-status'       => '進行HTTP請求時出現問題：$1 $2',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6'       => '無法訪問 URL',
'upload-curl-error6-text'  => '無法訪問所提供的 URL。請再次檢查該 URL 是否正確，並且網站的訪問是否正常。',
'upload-curl-error28'      => '上傳超時',
'upload-curl-error28-text' => '網站回應時間過長。請檢查此網站的訪問是否正常，過一會再進行嘗試。您可能需要在網路訪問空閒時間再次進行嘗試。',

'license'            => '授權:',
'license-header'     => '授權',
'nolicense'          => '未選定',
'license-nopreview'  => '（無預覽可用）',
'upload_source_url'  => ' （一個有效的，可公開訪問的 URL）',
'upload_source_file' => ' （在您電腦上的一個檔案）',

# Special:ListFiles
'listfiles-summary'     => '這個特殊頁面顯示所有上傳過的檔案。
預設中最後上傳的檔案會顯示在這個列表中的最頂處。
點擊一欄的標題去改變這個排列。',
'listfiles_search_for'  => '按檔案名稱搜索:',
'imgfile'               => '檔案',
'listfiles'             => '檔案列表',
'listfiles_thumb'       => '縮圖',
'listfiles_date'        => '日期',
'listfiles_name'        => '名稱',
'listfiles_user'        => '用戶',
'listfiles_size'        => '大小',
'listfiles_description' => '描述',
'listfiles_count'       => '版本',

# File description page
'file-anchor-link'                  => '檔案',
'filehist'                          => '檔案歷史',
'filehist-help'                     => '點擊日期／時間以檢視當時出現過的檔案。',
'filehist-deleteall'                => '刪除全部',
'filehist-deleteone'                => '刪除',
'filehist-revert'                   => '恢復',
'filehist-current'                  => '目前',
'filehist-datetime'                 => '日期／時間',
'filehist-thumb'                    => '縮圖',
'filehist-thumbtext'                => '於$1的縮圖版本',
'filehist-nothumb'                  => '沒有縮圖',
'filehist-user'                     => '用戶',
'filehist-dimensions'               => '維度',
'filehist-filesize'                 => '檔案大小',
'filehist-comment'                  => '註解',
'filehist-missing'                  => '檔案遺失',
'imagelinks'                        => '檔案連結',
'linkstoimage'                      => '以下的$1個頁面連接到本檔案:',
'linkstoimage-more'                 => '多於$1個頁面連接到這個檔案。
下面的清單只列示了連去這個檔案的最首$1個頁面。
一個[[Special:WhatLinksHere/$2|完整的清單]]可以提供。',
'nolinkstoimage'                    => '沒有頁面連接到本檔案。',
'morelinkstoimage'                  => '檢視連接到這個檔案的[[Special:WhatLinksHere/$1|更多連結]]。',
'redirectstofile'                   => '以下的$1個檔案重新定向到這個檔案:',
'duplicatesoffile'                  => '以下的$1個檔案跟這個檔案重覆（[[Special:FileDuplicateSearch/$2|更多細節]]）：',
'sharedupload'                      => '該檔案來自於$1，它可能在其它計劃項目中被應用。',
'sharedupload-desc-there'           => '該檔案來自於$1，它可能在其它計劃項目中被應用。
請參閱在[$2 檔案描述頁面]以了解其相關資訊。',
'sharedupload-desc-here'            => '該檔案來自於$1，它可能在其它計劃項目中被應用。
它在[$2 檔案描述頁面]那邊上的描述於下面顯示。',
'filepage-nofile'                   => '不存在此名稱的檔案。',
'filepage-nofile-link'              => '不存在此名稱的檔案，但您可以[$1 上傳它]。',
'uploadnewversion-linktext'         => '上傳該檔案的新版本',
'shared-repo-from'                  => '出自$1',
'shared-repo'                       => '一個共用檔案庫',
'shared-repo-name-wikimediacommons' => '維基共享資源',

# File reversion
'filerevert'                => '恢復$1',
'filerevert-legend'         => '恢復檔案',
'filerevert-intro'          => "您現正在恢復檔案'''[[Media:$1|$1]]'''到[$4 於$2 $3的版本]。",
'filerevert-comment'        => '理由：',
'filerevert-defaultcomment' => '已經恢復到於$1 $2的版本',
'filerevert-submit'         => '恢復',
'filerevert-success'        => "'''[[Media:$1|$1]]'''已經恢復到[$4 於$2 $3的版本]。",
'filerevert-badversion'     => '這個檔案所提供的時間截記並無先前的本地版本。',

# File deletion
'filedelete'                  => '刪除$1',
'filedelete-legend'           => '刪除檔案',
'filedelete-intro'            => "您現正刪除檔案'''[[Media:$1|$1]]'''。",
'filedelete-intro-old'        => "{{GENDER:|你|妳|你}}現正刪除'''[[Media:$1|$1]]'''於[$4 $2 $3]的版本。",
'filedelete-comment'          => '理由：',
'filedelete-submit'           => '刪除',
'filedelete-success'          => "'''$1'''已經刪除。",
'filedelete-success-old'      => "'''[[Media:$1|$1]]'''於 $2 $3 的版本已經刪除。",
'filedelete-nofile'           => "'''$1'''不存在。",
'filedelete-nofile-old'       => "在已指定屬性的情況下，這裡沒有'''$1'''的保存版本。",
'filedelete-otherreason'      => '其它／附加的理由:',
'filedelete-reason-otherlist' => '其它理由',
'filedelete-reason-dropdown'  => '
*常用刪除理由
** 侵犯版權
** 重覆檔案',
'filedelete-edit-reasonlist'  => '編輯刪除埋由',
'filedelete-maintenance'      => '當在維護時已經暫時停用檔案刪除和恢復。',

# MIME search
'mimesearch'         => 'MIME 搜尋',
'mimesearch-summary' => '本頁面啟用檔案MIME類型過濾器。輸入︰內容類型/子類型，如 <tt>image/jpeg</tt>。',
'mimetype'           => 'MIME 類型:',
'download'           => '下載',

# Unwatched pages
'unwatchedpages' => '未被監視的頁面',

# List redirects
'listredirects' => '重定向頁面清單',

# Unused templates
'unusedtemplates'     => '未使用的模板',
'unusedtemplatestext' => '本頁面列出{{ns:template}}名字空間下所有未被其他頁面使用的頁面。請在刪除這些模板前檢查其他連入該模板的頁面。',
'unusedtemplateswlh'  => '其他連結',

# Random page
'randompage'         => '隨機頁面',
'randompage-nopages' => '在以下的{{PLURAL:$2|名字空間}}中沒有頁面：$1',

# Random redirect
'randomredirect'         => '隨機重定向頁面',
'randomredirect-nopages' => '在 "$1" 名字空間中沒有重定向頁面。',

# Statistics
'statistics'                   => '統計',
'statistics-header-pages'      => '頁面統計',
'statistics-header-edits'      => '編輯統計',
'statistics-header-views'      => '檢視統計',
'statistics-header-users'      => '用戶統計',
'statistics-header-hooks'      => '其它統計',
'statistics-articles'          => '內容頁面',
'statistics-pages'             => '頁面',
'statistics-pages-desc'        => '在wiki上的所有頁面，包括對話頁面、重新定向等',
'statistics-files'             => '已經上傳的檔案',
'statistics-edits'             => '自從{{SITENAME}}設定的頁面編輯數',
'statistics-edits-average'     => '每一頁面的平均編輯數',
'statistics-views-total'       => '檢視總數',
'statistics-views-total-desc'  => '不存在頁面和特殊頁面的查看數未計入',
'statistics-views-peredit'     => '每次編輯檢視數',
'statistics-users'             => '已註冊[[Special:ListUsers|用戶]]',
'statistics-users-active'      => '活躍用戶',
'statistics-users-active-desc' => '在前$1天中操作過的用戶',
'statistics-mostpopular'       => '被查閱次數最多的頁面',

'disambiguations'      => '消含糊頁',
'disambiguationspage'  => 'Template:disambig
Template:消含糊
Template:消除含糊
Template:消歧义
Template:消除歧义
Template:消歧義
Template:消除歧義',
'disambiguations-text' => '以下的頁面都有到<b>消含糊頁</b>的連結，但它們應該是連到適當的標題。<br />一個頁面會被視為消含糊頁如果它是連自[[MediaWiki:Disambiguationspage]]。',

'doubleredirects'            => '雙重重定向頁面',
'doubleredirectstext'        => '這一頁列出所有重定向頁面重定向到另一個重定向頁的頁面。每一行都包含到第一和第二個重定向頁面的連結，以及第二個重定向頁面的目標，通常顯示的都會是"真正"的目標頁面，也就是第一個重定向頁面應該指向的頁面。
<del>已劃去</del>的為已經解決之項目。',
'double-redirect-fixed-move' => '[[$1]]已經完成移動，它現在重新定向到[[$2]]。',
'double-redirect-fixer'      => '重新定向修正器',

'brokenredirects'        => '損壞的重定向頁',
'brokenredirectstext'    => '以下的重定向頁指向的是不存在的頁面:',
'brokenredirects-edit'   => '編輯',
'brokenredirects-delete' => '刪除',

'withoutinterwiki'         => '未有語言連結的頁面',
'withoutinterwiki-summary' => '以下的頁面是未有語言連結到其它語言版本。',
'withoutinterwiki-legend'  => '前綴',
'withoutinterwiki-submit'  => '顯示',

'fewestrevisions' => '最少修訂的頁面',

# Miscellaneous special pages
'nbytes'                  => '$1位元組',
'ncategories'             => '$1個分類',
'nlinks'                  => '$1個連結',
'nmembers'                => '$1個成員',
'nrevisions'              => '$1個修訂',
'nviews'                  => '$1次瀏覽',
'nimagelinks'             => '用於$1個頁面中',
'ntransclusions'          => '用於$1個頁面中',
'specialpage-empty'       => '這個報告的結果為空。',
'lonelypages'             => '孤立頁面',
'lonelypagestext'         => '以下頁面尚未被{{SITENAME}}中的其它頁面連結或被之包含。',
'uncategorizedpages'      => '待分類頁面',
'uncategorizedcategories' => '待分類類別',
'uncategorizedimages'     => '待分類檔案',
'uncategorizedtemplates'  => '待分類模板',
'unusedcategories'        => '未使用的分類',
'unusedimages'            => '未使用圖片',
'popularpages'            => '熱點頁面',
'wantedcategories'        => '需要的分類',
'wantedpages'             => '待撰頁面',
'wantedpages-badtitle'    => '在結果組上的無效標題: $1',
'wantedfiles'             => '需要的檔案',
'wantedtemplates'         => '需要的模板',
'mostlinked'              => '最多連結頁面',
'mostlinkedcategories'    => '最多連結分類',
'mostlinkedtemplates'     => '最多連結模板',
'mostcategories'          => '最多分類頁面',
'mostimages'              => '最多連結檔案',
'mostrevisions'           => '最多修訂頁面',
'prefixindex'             => '所有頁面之前綴',
'shortpages'              => '短頁面',
'longpages'               => '長頁面',
'deadendpages'            => '斷連頁面',
'deadendpagestext'        => '以下頁面沒有連結到{{SITENAME}}中的其它頁面。',
'protectedpages'          => '已保護頁面',
'protectedpages-indef'    => '只有無期之保護頁面',
'protectedpages-cascade'  => '只有連鎖之保護頁面',
'protectedpagestext'      => '以下頁面已經被保護以防止移動或編輯',
'protectedpagesempty'     => '在這些參數下沒有頁面正在保護。',
'protectedtitles'         => '已保護的標題',
'protectedtitlestext'     => '以下的頁面已經被保護以防止建立',
'protectedtitlesempty'    => '在這些參數之下並無標題正在保護。',
'listusers'               => '用戶列表',
'listusers-editsonly'     => '只顯示有編輯的用戶',
'listusers-creationsort'  => '按建立日期排序',
'usereditcount'           => '$1 次編輯',
'usercreated'             => '於$1 $2建立',
'newpages'                => '最新頁面',
'newpages-username'       => '用戶名:',
'ancientpages'            => '最舊頁面',
'move'                    => '移動',
'movethispage'            => '移動本頁',
'unusedimagestext'        => '下列檔案未有嵌入任何頁面但它仍然存在。
請注意其它網站可能直接透過 URL 連結此檔案，所以這裡列出的圖片有可能依然被使用。',
'unusedcategoriestext'    => '雖然沒有被其它頁面或者分類所採用，但列表中的分類頁依然存在。',
'notargettitle'           => '無目標',
'notargettext'            => '您還沒有指定一個目標頁面或用戶以進行此項操作。',
'nopagetitle'             => '無目標頁面',
'nopagetext'              => '您所指定的目標頁面並不存在。',
'pager-newer-n'           => '新$1次',
'pager-older-n'           => '舊$1次',
'suppress'                => '監督',
'querypage-disabled'      => '此特殊頁面基於效能的原因已經被停用。',

# Book sources
'booksources'               => '網路書源',
'booksources-search-legend' => '尋找網路書源',
'booksources-go'            => '送出',
'booksources-text'          => '以下是一份銷售新書或二手書的列表，並可能有{{GENDER:|你|妳|你}}正尋找的書的進一步訊息：',
'booksources-invalid-isbn'  => '提供的ISBN號碼並不正確，請檢查原始複製來源號碼是否有誤。',

# Special:Log
'specialloguserlabel'  => '用戶︰',
'speciallogtitlelabel' => '標題：',
'log'                  => '日誌',
'all-logs-page'        => '所有公共日誌',
'alllogstext'          => '綜合{{SITENAME}}的顯示上傳、刪除、保護、查封以及站務日誌。',
'logempty'             => '在日誌中不存在匹配項。',
'log-title-wildcard'   => '搜尋以這個文字開始的標題',

# Special:AllPages
'allpages'          => '所有頁面',
'alphaindexline'    => '$1 到 $2',
'nextpage'          => '下一頁（$1）',
'prevpage'          => '上一頁（$1）',
'allpagesfrom'      => '顯示從此處開始的頁面:',
'allpagesto'        => '顯示從此處結束的頁面:',
'allarticles'       => '所有頁面',
'allinnamespace'    => '所有頁面（屬於$1名字空間）',
'allnotinnamespace' => '所有頁面（不屬於$1名字空間）',
'allpagesprev'      => '前',
'allpagesnext'      => '後',
'allpagessubmit'    => '提交',
'allpagesprefix'    => '顯示具有此前綴（名字空間）的頁面:',
'allpagesbadtitle'  => '給定的頁面標題是非法的，或者具有一個內部語言或內部 wiki 的前綴。它可能包含一個或更多的不能用於標題的字元。',
'allpages-bad-ns'   => '在{{SITENAME}}中沒有一個叫做"$1"的名字空間。',

# Special:Categories
'categories'                    => '頁面分類',
'categoriespagetext'            => '以下的{{PLURAL:$1|分類}}中包含了頁面或媒體。
[[Special:UnusedCategories|未用分類]]不會在這裏列示。
請同時參閱[[Special:WantedCategories|需要的分類]]。',
'categoriesfrom'                => '顯示由此項起之分類:',
'special-categories-sort-count' => '按數量排列',
'special-categories-sort-abc'   => '按字母排列',

# Special:DeletedContributions
'deletedcontributions'             => '已刪除的用戶貢獻',
'deletedcontributions-title'       => '已刪除的用戶貢獻',
'sp-deletedcontributions-contribs' => '貢獻',

# Special:LinkSearch
'linksearch'       => '外部連結',
'linksearch-pat'   => '搜尋網址:',
'linksearch-ns'    => '名字空間：',
'linksearch-ok'    => '搜尋',
'linksearch-text'  => '可以使用類似"*.wikipedia.org"的萬用字元。<br />
已支援：<tt>$1</tt>',
'linksearch-line'  => '$1 連自 $2',
'linksearch-error' => '萬用字元僅可在主機名稱的開頭使用。',

# Special:ListUsers
'listusersfrom'      => '給定顯示用戶條件:',
'listusers-submit'   => '顯示',
'listusers-noresult' => '找不到用戶。',
'listusers-blocked'  => '（已封鎖）',

# Special:ActiveUsers
'activeusers'            => '活躍用戶列表',
'activeusers-intro'      => '這個是在最近$1天之內有一些動作的用戶列表。',
'activeusers-count'      => '於$3天內的$1次編輯',
'activeusers-from'       => '顯示用戶開始於：',
'activeusers-hidebots'   => '隱藏機器人',
'activeusers-hidesysops' => '隱藏管理員',
'activeusers-noresult'   => '找不到用戶。',

# Special:Log/newusers
'newuserlogpage'              => '新進用戶名冊',
'newuserlogpagetext'          => '這是一個最近被創建用戶的新日誌',
'newuserlog-byemail'          => '密碼已由電子郵件寄出',
'newuserlog-create-entry'     => '新的使用者賬號',
'newuserlog-create2-entry'    => '已創建$1的新賬戶',
'newuserlog-autocreate-entry' => '已自動建立賬戶',

# Special:ListGroupRights
'listgrouprights'                      => '用戶群組權限',
'listgrouprights-summary'              => '以下面是一個在這個wiki中定義出來的用戶權限清單，以及它們的存取權。
更多有關個別權限的細節可以在[[{{MediaWiki:Listgrouprights-helppage}}|這裏]]找到。',
'listgrouprights-key'                  => '* <span class="listgrouprights-granted">已授予的權限</span>
* <span class="listgrouprights-revoked">已撤除的權限</span>',
'listgrouprights-group'                => '群組',
'listgrouprights-rights'               => '權限',
'listgrouprights-helppage'             => 'Help:群組權限',
'listgrouprights-members'              => '（成員清單）',
'listgrouprights-addgroup'             => '加入的{{PLURAL:$2|一個|多個}}群組: $1',
'listgrouprights-removegroup'          => '移除的{{PLURAL:$2|一個|多個}}群組: $1',
'listgrouprights-addgroup-all'         => '入所有群組',
'listgrouprights-removegroup-all'      => '移除所有群組',
'listgrouprights-addgroup-self'        => '在自己的賬戶中加入的{{PLURAL:$2|一個|多個}}群組: $1',
'listgrouprights-removegroup-self'     => '在自己的賬戶中移除的{{PLURAL:$2|一個|多個}}群組: $1',
'listgrouprights-addgroup-self-all'    => '在自己的賬戶中加入所有群組',
'listgrouprights-removegroup-self-all' => '在自己的賬戶中移除所有群組',

# E-mail user
'mailnologin'          => '無電郵地址',
'mailnologintext'      => '您必須先[[Special:UserLogin|登入]]
並在[[Special:Preferences|偏好設定]]
中有一個有效的 e-mail 地址才可以電郵其他用戶。',
'emailuser'            => 'E-mail該用戶',
'emailpage'            => 'E-mail用戶',
'emailpagetext'        => '您可以用下面的表格去寄一封電郵給這位用戶。
您在[[Special:Preferences|您參數設置]]中所輸入的e-mail地址將出現在郵件「發件人」一欄中，這樣該用戶就可以回覆您。',
'usermailererror'      => '目標郵件地址返回錯誤：',
'defemailsubject'      => '{{SITENAME}}電子郵件',
'usermaildisabled'     => '用戶電郵已停用',
'usermaildisabledtext' => '您不可以發送電郵到這個wiki上的其他用戶',
'noemailtitle'         => '無e-mail地址',
'noemailtext'          => '該用戶還沒有指定一個有效的e-mail地址。',
'nowikiemailtitle'     => '不容許電子郵件',
'nowikiemailtext'      => '這位用戶選擇不接收其他用戶的電子郵件。',
'email-legend'         => '發一封電子郵件至另一位{{SITENAME}}用戶',
'emailfrom'            => '發件人：',
'emailto'              => '收件人：',
'emailsubject'         => '主題：',
'emailmessage'         => '訊息：',
'emailsend'            => '發送',
'emailccme'            => '將我的消息的副本發送一份到我的電郵信箱。',
'emailccsubject'       => '將您的訊息複製到 $1: $2',
'emailsent'            => '電子郵件已發送',
'emailsenttext'        => '您的電子郵件已經發出。',
'emailuserfooter'      => '這封電郵是由$1寄給$2經{{SITENAME}}的「電郵用戶」功能發出的。',

# User Messenger
'usermessage-summary' => '給系統消息。',
'usermessage-editor'  => '系統界面',

# Watchlist
'watchlist'            => '監視列表',
'mywatchlist'          => '我的監視列表',
'watchlistfor2'        => '$1的監視列表 $2',
'nowatchlist'          => '您的監視列表為空。',
'watchlistanontext'    => '請$1以檢視或編輯您的監視列表。',
'watchnologin'         => '未登入',
'watchnologintext'     => '您必須先[[Special:UserLogin|登入]]
才能更改您的監視列表',
'addedwatch'           => '加入到監視列表',
'addedwatchtext'       => "頁面「[[:$1]]」已經被加入到您的[[Special:Watchlist|監視清單]]中。將來有關此頁面及其討論頁的任何修改將會在那裡列出，而且還會在[[Special:RecentChanges|近期變動]]中以'''粗體'''形式列出以使起更容易識別。",
'removedwatch'         => '已停止監視',
'removedwatchtext'     => '[[:$1]]已經從[[Special:Watchlist|您的監視頁面]]中移除。',
'watch'                => '監視',
'watchthispage'        => '監視本頁',
'unwatch'              => '取消監視',
'unwatchthispage'      => '停止監視',
'notanarticle'         => '不是頁面',
'notvisiblerev'        => '上次由不同用戶所作的修訂版本已經刪除',
'watchnochange'        => '在顯示的時間段內您所監視的頁面沒有更改。',
'watchlist-details'    => '不包含討論頁，有 $1 頁在您的監視列表上。',
'wlheader-enotif'      => '* 已經啟動電子郵件通知功能。',
'wlheader-showupdated' => "* 在{{GENDER:|你|妳|你}}上次檢視後有被修改過的頁面會顯示為'''粗體'''",
'watchmethod-recent'   => '檢查被監視頁面的最近編輯',
'watchmethod-list'     => '檢查最近編輯的被監視頁面',
'watchlistcontains'    => '您的監視列表包含$1個頁面。',
'iteminvalidname'      => "頁面 '$1' 錯誤，無效命名...",
'wlnote'               => '以下是最近<b>$2</b>小時內的最後$1次修改。',
'wlshowlast'           => '顯示最近$1小時；$2天；$3的修改。',
'watchlist-options'    => '監視列表選項',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => '正在監視...',
'unwatching' => '正在停止監視...',

'enotif_mailer'                => '{{SITENAME}}郵件通知器',
'enotif_reset'                 => '將所有頁面標為已閱讀',
'enotif_newpagetext'           => '這是新建頁面。',
'enotif_impersonal_salutation' => '{{SITENAME}}用戶',
'changed'                      => '修改了',
'created'                      => '建立了',
'enotif_subject'               => '{{SITENAME}}有頁面 $PAGETITLE 被 $PAGEEDITOR $CHANGEDORCREATED',
'enotif_lastvisited'           => '檢視您上次訪問後的所有更改請參閱$1。',
'enotif_lastdiff'              => '檢視更改請參閱$1。',
'enotif_anon_editor'           => '匿名用戶$1',
'enotif_body'                  => '親愛的 $WATCHINGUSERNAME，

$PAGEEDITOR 已經在 $PAGEEDITDATE $CHANGEDORCREATED{{SITENAME}}的 $PAGETITLE 頁面，請到 $PAGETITLE_URL 檢視目前修訂版本。

$NEWPAGE

編輯摘要: $PAGESUMMARY $PAGEMINOREDIT

聯繫此編輯者:

郵件: $PAGEEDITOR_EMAIL

本站: $PAGEEDITOR_WIKI

在您訪問此頁之前，將來的更改將不會向您發通知。您也可以重設您所有監視頁面的通知標記。

                {{SITENAME}}通知系統

--
要改變您的監視列表設定，請參閱
{{fullurl:{{#special:Watchlist}}/edit}}

要刪除您監視清單中的該頁面，請參閱
$UNWATCHURL

回饋和進一步的幫助:
{{fullurl:{{MediaWiki:Helppage}}}}',

# Delete
'deletepage'             => '刪除頁面',
'confirm'                => '確認',
'excontent'              => '內容為: 「$1」',
'excontentauthor'        => '內容為：「$1」（而且唯一貢獻者為「[[Special:Contributions/$2|$2]]」）',
'exbeforeblank'          => '被清空前的內容為：「$1」',
'exblank'                => '頁面為空',
'delete-confirm'         => '刪除「$1」',
'delete-legend'          => '刪除',
'historywarning'         => "'''警告: '''您將要刪除的頁內含有約$1次{{PLURAL:$1|修訂|修訂}}的歷史：",
'confirmdeletetext'      => '您即將刪除一個頁面或圖片以及其歷史。
請確定您要進行此項操作，並且了解其後果，同時您的行為符合[[{{MediaWiki:Policy-url}}]]。',
'actioncomplete'         => '操作完成',
'actionfailed'           => '操作失敗',
'deletedtext'            => '「<nowiki>$1</nowiki>」已經被刪除。最近刪除的記錄請參見$2。',
'deletedarticle'         => '已刪除「[[$1]]」',
'suppressedarticle'      => '已廢止「[[$1]]」',
'dellogpage'             => '刪除紀錄',
'dellogpagetext'         => '以下是最近的刪除的列表。',
'deletionlog'            => '刪除紀錄',
'reverted'               => '恢復到早期版本',
'deletecomment'          => '理由：',
'deleteotherreason'      => '其它／附加的理由:',
'deletereasonotherlist'  => '其它理由',
'deletereason-dropdown'  => '*常用刪除理由
** 作者請求
** 侵犯版權
** 破壞',
'delete-edit-reasonlist' => '編輯刪除理由',
'delete-toobig'          => '這個頁面有一個十分大量的編輯歷史，超過$1次修訂。刪除此類頁面的動作已經被限制，以防止在{{SITENAME}}上的意外擾亂。',
'delete-warning-toobig'  => '這個頁面有一個十分大量的編輯歷史，超過$1次修訂。刪除它可能會擾亂{{SITENAME}}的資料庫操作；在繼續此動作前請小心。',

# Rollback
'rollback'          => '恢復編輯',
'rollback_short'    => '恢復',
'rollbacklink'      => '恢復',
'rollbackfailed'    => '無法恢復',
'cantrollback'      => '無法恢復編輯；最後的貢獻者是本文的唯一作者。',
'alreadyrolled'     => '無法回退由[[User:$2|$2]]（[[User talk:$2|討論]]{{int:pipe-separator}}[[Special:Contributions/$2|{{int:contribslink}}]]在[[:$1]]上的編輯；其他人已經編輯或者回退了該頁。

該頁最後的編輯者是[[User:$3|$3]]（[[User talk:$3|討論]]{{int:pipe-separator}}[[Special:Contributions/$3|{{int:contribslink}}]]）。',
'editcomment'       => "編輯摘要: \"''\$1''\"。",
'revertpage'        => '已恢復由[[Special:Contributions/$2|$2]]（[[User talk:$2|對話]]）的編輯至[[User:$1|$1]]的最後一個修訂版本',
'revertpage-nouser' => '恢復由（移除了的用戶名）的編輯到[[User:$1|$1]]的最後一個修訂版本',
'rollback-success'  => '已恢復$1的編輯；
更改回$2的最後修訂版本。',

# Edit tokens
'sessionfailure-title' => '登入資訊失敗',
'sessionfailure'       => '似乎您的登錄會話有問題；
為了防止會話劫持，這個操作已經被取消。
請返回先前的頁面，重新載入該頁面，然後重試。',

# Protect
'protectlogpage'              => '保護日誌',
'protectlogtext'              => '下面是頁面保護和取消保護的列表。請參考[[Special:ProtectedPages|保護頁面清單]]以檢視目前進行的頁面保護。',
'protectedarticle'            => '已保護"[[$1]]"',
'modifiedarticleprotection'   => '已經更改 "[[$1]]" 的保護等級',
'unprotectedarticle'          => '已解除保護"[[$1]]"',
'movedarticleprotection'      => '已將「[[$2]]」的保護設定移動至「[[$1]]」',
'protect-title'               => '正在更改"$1"的保護等級',
'prot_1movedto2'              => '[[$1]]移動到[[$2]]',
'protect-legend'              => '確認保護',
'protectcomment'              => '理由：',
'protectexpiry'               => '到期：',
'protect_expiry_invalid'      => '輸入的終止時間無效。',
'protect_expiry_old'          => '終止時間已過去。',
'protect-unchain-permissions' => '解除鎖定更多的保護選項',
'protect-text'                => "{{GENDER:|你|妳|你}}可以在這裡瀏覽和修改對頁面'''<nowiki>$1</nowiki>'''的保護級別。",
'protect-locked-blocked'      => "您不能在被查封時更改保護級別。
以下是'''$1'''現時的保護級別:",
'protect-locked-dblock'       => "在資料庫鎖定時無法更改保護級別。
以下是'''$1'''現時的保護級別:",
'protect-locked-access'       => "您的賬戶權限不能修改保護級別。
以下是'''$1'''現時的保護級別:",
'protect-cascadeon'           => '以下的{{PLURAL:$1|一個|多個}}頁面包含着本頁面的同時，啟動了連鎖保護，因此本頁面目前也被保護，未能編輯。您可以設定本頁面的保護級別，但這並不會對連鎖保護有所影響。',
'protect-default'             => '容許所有用戶',
'protect-fallback'            => '需要"$1"的許可',
'protect-level-autoconfirmed' => '禁止新的和未註冊的用戶',
'protect-level-sysop'         => '僅操作員',
'protect-summary-cascade'     => '連鎖',
'protect-expiring'            => '終止於 $1 （UTC）',
'protect-expiry-indefinite'   => '無期',
'protect-cascade'             => '保護本頁中包含的頁面 （連鎖保護）',
'protect-cantedit'            => '您無法更改這個頁面的保護等級，因為您沒有權限去編輯它。',
'protect-othertime'           => '其它時間:',
'protect-othertime-op'        => '其它時間',
'protect-existing-expiry'     => '現時到期之時間: $2 $3',
'protect-otherreason'         => '其它／附加的理由:',
'protect-otherreason-op'      => '其它理由',
'protect-dropdown'            => '*通用保護理由
** 過量的破壞
** 過量的灌水
** 反生產性編輯戰
** 高流量頁面',
'protect-edit-reasonlist'     => '編輯保護理由',
'protect-expiry-options'      => '1小時:1 hour,1天:1 day,1周:1 week,2周:2 weeks,1個月:1 month,3個月:3 months,6個月:6 months,1年:1 year,永久:infinite',
'restriction-type'            => '權限:',
'restriction-level'           => '限制級別:',
'minimum-size'                => '最小大小',
'maximum-size'                => '最大大小:',
'pagesize'                    => '（位元組）',

# Restrictions (nouns)
'restriction-edit'   => '編輯',
'restriction-move'   => '移動',
'restriction-create' => '建立',
'restriction-upload' => '上傳',

# Restriction levels
'restriction-level-sysop'         => '全保護',
'restriction-level-autoconfirmed' => '半保護',
'restriction-level-all'           => '任何級別',

# Undelete
'undelete'                     => '恢復被刪頁面',
'undeletepage'                 => '瀏覽及恢復被刪頁面',
'undeletepagetitle'            => "'''以下包含[[:$1]]的已刪除之修訂版本'''。",
'viewdeletedpage'              => '檢視被刪除的頁面',
'undeletepagetext'             => '以下的$1個頁面已經被刪除，但依然在檔案中並可以被恢復。
檔案庫可能被定時清理。',
'undelete-fieldset-title'      => '恢復修訂',
'undeleteextrahelp'            => "恢復整個頁面時，請清除所有複選框後按 '''''{{int:undeletebtn}}''''' 。
恢復特定版本時，請選擇相應版本前的複選框後按'''''{{int:undeletebtn}}''''' 。
按 '''''{{int:undeletereset}}''''' 將清除評論內容及所有複選框。",
'undeleterevisions'            => '$1版本存檔',
'undeletehistory'              => '如果您恢復了該頁面，所有版本都會被恢復到修訂歷史中。
如果本頁刪除後有一個同名的新頁面建立，被恢復的版本將會出現在先前的歷史中。',
'undeleterevdel'               => '如果把最新修訂部份刪除，反刪除便無法進行。如果遇到這種情況，您必須反選或反隱藏最新已刪除的修訂。',
'undeletehistorynoadmin'       => '這個頁面已經被刪除，刪除原因顯示在下方編輯摘要中。被刪除前的所有修訂版本，連同刪除前貢獻用戶等等細節只有管理員可以看見。',
'undelete-revision'            => '刪除$1時由$3（在$4 $5）所編寫的修訂版本:',
'undeleterevision-missing'     => '此版本的內容不正確或已經遺失。可能連結錯誤、被移除或已經被恢復。',
'undelete-nodiff'              => '找不到先前的修訂版本。',
'undeletebtn'                  => '恢復',
'undeletelink'                 => '檢視／恢復',
'undeleteviewlink'             => '檢視',
'undeletereset'                => '重設',
'undeleteinvert'               => '反向選擇',
'undeletecomment'              => '理由：',
'undeletedarticle'             => '已經恢復「$1」',
'undeletedrevisions'           => '$1個修訂版本已經恢復',
'undeletedrevisions-files'     => '$1 個版本和 $2 個檔案被恢復',
'undeletedfiles'               => '$1 個檔案被恢復',
'cannotundelete'               => '恢復失敗；可能之前已經被其他人恢復。',
'undeletedpage'                => "'''$1已經被恢復''' 請參考[[Special:Log/delete|刪除日誌]]來查詢刪除及恢復記錄。",
'undelete-header'              => '如要查詢最近的記錄請參閱[[Special:Log/delete|刪除日誌]]。',
'undelete-search-box'          => '搜尋已刪除頁面',
'undelete-search-prefix'       => '顯示頁面自:',
'undelete-search-submit'       => '搜尋',
'undelete-no-results'          => '刪除記錄裡沒有符合的結果。',
'undelete-filename-mismatch'   => '不能刪除帶有時間截記的檔案修訂 $1: 檔案不匹配',
'undelete-bad-store-key'       => '不能刪除帶有時間截記的檔案修訂 $1: 檔案於刪除前遺失。',
'undelete-cleanup-error'       => '刪除無用的存檔檔案 "$1" 時發生錯誤。',
'undelete-missing-filearchive' => '由於檔案存檔 ID $1 不在資料庫中，不能在檔案存檔中恢復。它可能已經反刪除了。',
'undelete-error-short'         => '反刪除檔案時發生錯誤: $1',
'undelete-error-long'          => '當進行反刪除檔案時遇到錯誤:

$1',
'undelete-show-file-confirm'   => '確定要檢視在 $2 $3 ，"<nowiki>$1</nowiki>"的已刪除修訂版本嗎？',
'undelete-show-file-submit'    => '是',

# Namespace form on various pages
'namespace'      => '名字空間：',
'invert'         => '反向選擇',
'blanknamespace' => '（主）',

# Contributions
'contributions'       => '用戶貢獻',
'contributions-title' => '$1的用戶貢獻',
'mycontris'           => '我的貢獻',
'contribsub2'         => '$1的貢獻 （$2）',
'nocontribs'          => '沒有找到符合特徵的更改。',
'uctop'               => '（最新修改）',
'month'               => '從該月份 （或更早）:',
'year'                => '從該年份 （或更早）:',

'sp-contributions-newbies'             => '只顯示新建立之用戶的貢獻',
'sp-contributions-newbies-sub'         => '新手',
'sp-contributions-newbies-title'       => '新手的用戶貢獻',
'sp-contributions-blocklog'            => '封禁記錄',
'sp-contributions-deleted'             => '已刪除的用戶貢獻',
'sp-contributions-uploads'             => '上載',
'sp-contributions-logs'                => '日誌',
'sp-contributions-talk'                => '對話',
'sp-contributions-userrights'          => '用戶權限管理',
'sp-contributions-blocked-notice'      => '這位用戶現時正在被封鎖中。
最近的封鎖日誌項目在下面提供以便參考：',
'sp-contributions-blocked-notice-anon' => '這個IP地址現時正在被封鎖中。
最近的封鎖日誌項目在下面提供以便參考：',
'sp-contributions-search'              => '搜尋貢獻記錄',
'sp-contributions-username'            => 'IP位址或用戶名稱：',
'sp-contributions-toponly'             => '只顯示最新修訂版本的編輯',
'sp-contributions-submit'              => '搜尋',

# What links here
'whatlinkshere'            => '連入頁面',
'whatlinkshere-title'      => '連結到「$1」的頁面',
'whatlinkshere-page'       => '頁面：',
'linkshere'                => '以下頁面連結到[[:$1]]：',
'nolinkshere'              => '沒有頁面連結到[[:$1]]。',
'nolinkshere-ns'           => '在所選的名字空間內沒有頁面連結到[[:$1]]。',
'isredirect'               => '重定向頁',
'istemplate'               => '包含',
'isimage'                  => '檔案連結',
'whatlinkshere-prev'       => '前$1個',
'whatlinkshere-next'       => '後$1個',
'whatlinkshere-links'      => '← 連入',
'whatlinkshere-hideredirs' => '$1重定向',
'whatlinkshere-hidetrans'  => '$1包含',
'whatlinkshere-hidelinks'  => '$1連結',
'whatlinkshere-hideimages' => '$1檔案連結',
'whatlinkshere-filters'    => '過濾器',

# Block/unblock
'blockip'                         => '封禁用戶',
'blockip-title'                   => '封禁用戶',
'blockip-legend'                  => '查封用戶',
'blockiptext'                     => '用下面的表單來禁止來自某一特定IP地址的修改許可權。
只有在為防止破壞，及符合[[{{MediaWiki:Policy-url}}|守則]]的情況下才可採取此行動。
請在下面輸入一個具體的理由（例如引述一個被破壞的頁面）。',
'ipaddress'                       => 'IP 位址：',
'ipadressorusername'              => 'IP地址或用戶名:',
'ipbexpiry'                       => '期限：',
'ipbreason'                       => '原因：',
'ipbreasonotherlist'              => '其它原因',
'ipbreason-dropdown'              => '*一般的封禁理由
** 屢次增加不實資料
** 刪除頁面內容
** 外部連結廣告
** 在頁面中增加無意義文字
** 無禮的行為、攻擊／騷擾別人
** 濫用多個賬號
** 不能接受的用戶名',
'ipbanononly'                     => '僅阻止匿名用戶',
'ipbcreateaccount'                => '阻止創建新賬號',
'ipbemailban'                     => '阻止用戶傳送電郵',
'ipbenableautoblock'              => '自動查封此用戶最後所用的IP位址，以及後來試圖編輯所用的所有位址',
'ipbsubmit'                       => '查封該地址',
'ipbother'                        => '其它時間:',
'ipboptions'                      => '2小時:2 hours,1天:1 day,3天:3 days,1周:1 week,2周:2 weeks,1個月:1 month,3個月:3 months,6個月:6 months,1年:1 year,永久:infinite',
'ipbotheroption'                  => '其他',
'ipbotherreason'                  => '其它／附帶原因:',
'ipbhidename'                     => '在編輯及列表中隱藏用戶名',
'ipbwatchuser'                    => '監視這位用戶的用戶頁面以及其對話頁面',
'ipballowusertalk'                => '當被封鎖時容許這位用戶去編輯自己的討論頁面',
'ipb-change-block'                => '利用這些設定重新封鎖用戶',
'badipaddress'                    => '無效IP地址',
'blockipsuccesssub'               => '查封成功',
'blockipsuccesstext'              => '[[Special:Contributions/$1|$1]]已經被查封。
<br />參看[[Special:IPBlockList|被封IP地址列表]]以覆審查封。',
'ipb-edit-dropdown'               => '編輯查封原因',
'ipb-unblock-addr'                => '解封$1',
'ipb-unblock'                     => '解除禁封用戶名或IP地址',
'ipb-blocklist'                   => '檢視現有的封禁',
'ipb-blocklist-contribs'          => '$1的貢獻',
'unblockip'                       => '解封用戶',
'unblockiptext'                   => '用下面的表單來恢復先前被查封的IP位址或用戶的寫權限。',
'ipusubmit'                       => '移除這個封鎖',
'unblocked'                       => '[[User:$1|$1]] 的封禁已經解除。',
'unblocked-id'                    => '封禁 $1 已經被移除',
'ipblocklist'                     => '被封IP地址列表',
'ipblocklist-legend'              => '搜尋一位已經被查封的用戶',
'ipblocklist-username'            => '用戶名稱或IP地址:',
'ipblocklist-sh-userblocks'       => '$1賬戶封鎖',
'ipblocklist-sh-tempblocks'       => '$1臨時封鎖',
'ipblocklist-sh-addressblocks'    => '$1單IP封鎖',
'ipblocklist-submit'              => '搜尋',
'ipblocklist-localblock'          => '本地封鎖',
'ipblocklist-otherblocks'         => '其他{{PLURAL:$1|封鎖|封鎖}}',
'blocklistline'                   => '$1，$3被$2查封（$4）',
'infiniteblock'                   => '永久',
'expiringblock'                   => '$1 $2 到期',
'anononlyblock'                   => '僅限匿名用戶',
'noautoblockblock'                => '禁用自動查封',
'createaccountblock'              => '禁止創建賬戶',
'emailblock'                      => '禁止電子郵件',
'blocklist-nousertalk'            => '禁止編輯自己的用戶討論頁',
'ipblocklist-empty'               => '查封列表為空。',
'ipblocklist-no-results'          => '所要求的IP地址/用戶名沒有被查封。',
'blocklink'                       => '查封',
'unblocklink'                     => '解除禁封',
'change-blocklink'                => '更改封禁',
'contribslink'                    => '貢獻',
'autoblocker'                     => '因為您與“[[User:$1|$1]]”共享一個IP地址而被自動查封。
$1被封禁的理由是“$2”',
'blocklogpage'                    => '查封日誌',
'blocklog-showlog'                => '這位用戶曾經被封鎖過。在下列提供封鎖記錄以便參考：',
'blocklog-showsuppresslog'        => '這位用戶曾經被封鎖和隱藏過。在下列提供廢止記錄以便參考：',
'blocklogentry'                   => '“[[$1]]”已被查封，終止時間為$2 $3',
'reblock-logentry'                => '更改[[$1]]的封禁設定時間 $2 $3',
'blocklogtext'                    => '這是關於用戶封禁和解除封禁操作的記錄。被自動封禁的IP地址沒有被列出。請參閱[[Special:IPBlockList|被查封的IP地址和用戶列表]]。',
'unblocklogentry'                 => '$1已被解封',
'block-log-flags-anononly'        => '僅限匿名用戶',
'block-log-flags-nocreate'        => '建立賬號已禁用',
'block-log-flags-noautoblock'     => '停用自動封禁',
'block-log-flags-noemail'         => '禁止電子郵件',
'block-log-flags-nousertalk'      => '禁止編輯自己的用戶討論頁',
'block-log-flags-angry-autoblock' => '加強自動封鎖已啟用',
'block-log-flags-hiddenname'      => '隱藏用戶名稱',
'range_block_disabled'            => '只有管理員才能創建禁止查封的範圍。',
'ipb_expiry_invalid'              => '無效的終止時間。',
'ipb_expiry_temp'                 => '隱藏用戶名封鎖必須是永久性的。',
'ipb_hide_invalid'                => '不能壓止這個賬戶；它可能有太多編輯。',
'ipb_already_blocked'             => '已經封鎖「$1」',
'ipb-needreblock'                 => '== 已經封鎖 ==
$1已經被封鎖。您是否想更改這個設定？',
'ipb-otherblocks-header'          => '其他{{PLURAL:$1|封鎖|封鎖}}',
'ipb_cant_unblock'                => '錯誤: 找不到查封ID$1。可能已經解除封禁。',
'ipb_blocked_as_range'            => '錯誤: 該IP $1 無直接查封，不可以解除封禁。但是它是在 $2 的查封範圍之內，該段範圍是可以解除封禁的。',
'ip_range_invalid'                => '無效的IP範圍。',
'ip_range_toolarge'               => '大於 /$1 的封鎖範圍是不容許的。',
'blockme'                         => '查封我',
'proxyblocker'                    => '代理封鎖器',
'proxyblocker-disabled'           => '這個功能已經停用。',
'proxyblockreason'                => '您的IP位址是一個開放的代理，它已經被封鎖。請聯繫您的網際網路服務提供商或技術支援者並告知告知他們該嚴重的安全問題。',
'proxyblocksuccess'               => '完成。',
'sorbsreason'                     => '您的IP位址在{{SITENAME}}中被 DNSBL列為屬於開放代理服務器。',
'sorbs_create_account_reason'     => '由於您的IP位址在{{SITENAME}}中被 DNSBL列為屬於開放代理服務器，所以您無法建立賬號。',
'cant-block-while-blocked'        => '當您被封鎖時不可以封鎖其他用戶。',
'cant-see-hidden-user'            => '您現正嘗試封鎖的用戶已經被封鎖或隱藏。
您現在沒有隱藏用戶的權限，您不可以檢視或者編輯這位用戶的封鎖。',
'ipbblocked'                      => '您無法封禁或解封其他用戶，因為您自己已被封禁',
'ipbnounblockself'                => '您不容許自我解除封禁',

# Developer tools
'lockdb'              => '禁止更改資料庫',
'unlockdb'            => '開放更改資料庫',
'lockdbtext'          => '鎖住資料庫將禁止所有用戶進行編輯頁面、更改參數、編輯監視列表以及其他需要更改資料庫的操作。
請確認您的決定，並且保證您在維護工作結束後會重新開放資料庫。',
'unlockdbtext'        => '開放資料庫將會恢復所有用戶進行編輯頁面、修改參數、編輯監視列表以及其他需要更改資料庫的操作。
請確認您的決定。',
'lockconfirm'         => '是的，我確實想要封鎖資料庫。',
'unlockconfirm'       => '是的，我確實想要開放資料庫。',
'lockbtn'             => '資料庫上鎖',
'unlockbtn'           => '開放資料庫',
'locknoconfirm'       => '您並沒有勾選確認按鈕。',
'lockdbsuccesssub'    => '資料庫成功上鎖',
'unlockdbsuccesssub'  => '資料庫開放',
'lockdbsuccesstext'   => '{{SITENAME}}資料庫已經上鎖。
<br />請記住在維護完成後重新開放資料庫。',
'unlockdbsuccesstext' => '{{SITENAME}}資料庫重新開放。',
'lockfilenotwritable' => '資料庫鎖定檔案不可寫入。要鎖定和解鎖資料庫，該檔案必須對網路伺服器可寫入。',
'databasenotlocked'   => '資料庫沒有鎖定。',

# Move page
'move-page'                    => '移動$1',
'move-page-legend'             => '移動頁面',
'movepagetext'                 => "用下面的表單來重新命名一個頁面，並將其修訂歷史同時移動到新頁面。
老的頁面將成為新頁面的重定向頁。
您可以自動地更新指到原標題的重定向。
如果您選擇不去做的話，請檢查[[Special:DoubleRedirects|雙重]]或[[Special:BrokenRedirects|損壞重定向]]連結。
您應當負責確定所有連結依然會連到指定的頁面。

注意如果新頁面已經有內容的話，頁面將'''不會'''被移動，
除非新頁面無內容或是重定向頁，而且沒有修訂歷史。
這意味著您再必要時可以在移動到新頁面後再移回老的頁面，
同時您也無法覆蓋現有頁面。

'''警告！'''
對一個經常被訪問的頁面而言這可能是一個重大與唐突的更改；
請在行動前先了解其所可能帶來的後果。",
'movepagetext-noredirectfixer' => "用下面的表單來重命名一個頁面，並將其修訂歷史同時移動到新頁面。
老的頁面將成為新頁面的重定向頁。
請檢查[[Special:DoubleRedirects|雙重重定向]]或[[Special:BrokenRedirects|損壞重定向]]連結。
您應當負責確定所有連結依然會連到指定的頁面。

注意如果新頁面已經有內容的話，頁面將'''不會'''被移動，
除非新頁面無內容或是重定向頁，而且沒有修訂歷史。
這意味著您再必要時可以在移動到新頁面後再移回老的頁面，
同時您也無法覆蓋現有頁面。

'''警告！'''
對一個經常被訪問的頁面而言這可能是一個重大與唐突的更改；
請在行動前先確定您了解其所可能帶來的後果。",
'movepagetalktext'             => "有關的對話頁（如果有的話）將被自動與該頁面一起移動，'''除非'''：
*您將頁面移動到不同的名字空間；
*新頁面已經有一個包含內容的對話頁，或者
*您不勾選下面的覆選框。

在這些情況下，您在必要時必須手工移動或合併頁面。",
'movearticle'                  => '移動頁面:',
'moveuserpage-warning'         => "'''警告：'''您將會移動一個用戶頁面。請留意該頁面在移動後該用戶的名字是''不會''變更的。",
'movenologin'                  => '未登入',
'movenologintext'              => '您必須是一名登記用戶並且[[Special:UserLogin|登入]]
後才可移動一個頁面。',
'movenotallowed'               => '您並沒有許可權去移動頁面。',
'movenotallowedfile'           => '您並沒有許可權去移動檔案。',
'cant-move-user-page'          => '您並沒有許可權去移動用戶頁面（它的子頁面除外）。',
'cant-move-to-user-page'       => '您並沒有許可權去移動到用戶頁面（它的子頁面除外）。',
'newtitle'                     => '新標題:',
'move-watch'                   => '監視來源以及目標頁',
'movepagebtn'                  => '移動頁面',
'pagemovedsub'                 => '移動成功',
'movepage-moved'               => "'''「$1」已經移動到「$2」'''",
'movepage-moved-redirect'      => '一個重新定向已經被創建。',
'movepage-moved-noredirect'    => '已經壓制創建重新定向。',
'articleexists'                => '該名字的頁面已經存在，或者您選擇的名字無效。請再選一個名字。',
'cantmove-titleprotected'      => '您不可以移動這個頁面到這個位置，因為該新標題已經被保護以防止建立。',
'talkexists'                   => '頁面本身移動成功，
但是由於新標題下已經有對話頁存在，所以對話頁無法移動。請手工合併兩個頁面。',
'movedto'                      => '移動到',
'movetalk'                     => '移動關聯的對話頁',
'move-subpages'                => '移動子頁面（上至$1頁）',
'move-talk-subpages'           => '移動子對話頁面（上至$1頁）',
'movepage-page-exists'         => '頁面 $1 已經存在，不可以自動地覆寫。',
'movepage-page-moved'          => '頁面 $1 已經移動到 $2。',
'movepage-page-unmoved'        => '頁面 $1 不可以移動到 $2。',
'movepage-max-pages'           => '最多有$1個頁面已經移動同時不可以自動地再移動更多。',
'1movedto2'                    => '[[$1]]移動到[[$2]]',
'1movedto2_redir'              => '[[$1]]移動到重定向頁[[$2]]',
'move-redirect-suppressed'     => '已禁止重新定向',
'movelogpage'                  => '移動日誌',
'movelogpagetext'              => '以下是所有移動的頁面清單:',
'movesubpage'                  => '{{PLURAL:$1|子頁面|子頁面}}',
'movesubpagetext'              => '這個頁面有$1個子頁面列示如下。',
'movenosubpage'                => '這個頁面沒有子頁面。',
'movereason'                   => '原因',
'revertmove'                   => '恢復該移動',
'delete_and_move'              => '刪除並移動',
'delete_and_move_text'         => '==需要刪除==

目標頁面"[[:$1]]"已經存在。{{GENDER:|你|妳|你}}確認需要刪除原頁面並以進行移動嗎？',
'delete_and_move_confirm'      => '是的，刪除此頁面',
'delete_and_move_reason'       => '刪除以便移動',
'selfmove'                     => '原始標題與目標標題相同，您不能移動一頁覆蓋本身。',
'immobile-source-namespace'    => '不可以在空間名「$1」上移動頁面',
'immobile-target-namespace'    => '不可以將頁面移動到「$1」空間名中',
'immobile-target-namespace-iw' => '垮維基連結在移動頁面中是無效的目標。',
'immobile-source-page'         => '這個頁面不能移動。',
'immobile-target-page'         => '無法移動至目標標題中。',
'imagenocrossnamespace'        => '不可以移動檔案到非檔案名字空間',
'nonfile-cannot-move-to-file'  => '不可以移動非檔案到檔案名字空間',
'imagetypemismatch'            => '該新副檔名不匹配它的類型',
'imageinvalidfilename'         => '目標檔案名稱是無效的',
'fix-double-redirects'         => '更新指到原先標題的任何重新定向',
'move-leave-redirect'          => '留下重新定向',
'protectedpagemovewarning'     => "'''警告：'''這個頁面已經被保護，只有擁有管理員權限的用戶才可以移動它。
最近的日誌在下面提供以便參考：",
'semiprotectedpagemovewarning' => "'''注意：'''這個頁面已經被保護，只有已經註冊的用戶才可以移動它。
最近的日誌在下面提供以便參考：",
'move-over-sharedrepo'         => '== 檔案已存在 ==
[[:$1]]已於共享資源存在，將檔案移動到此標題會覆蓋共享資源中的檔案。',
'file-exists-sharedrepo'       => '同名檔案已於共享資源存在。
請選擇另一個檔案名。',

# Export
'export'            => '匯出頁面',
'exporttext'        => '您可以將特定頁面或一組頁面的文字以及編輯歷史以 XML 格式匯出；這樣可以將有關頁面透過「[[Special:Import|匯入頁面]]」頁面匯入到另一個執行 MediaWiki 的網站。

要匯出頁面，請在下面的文字框中輸入頁面標題，每行一個標題，
並選擇{{GENDER:|你|妳|你}}是否需要匯出帶有頁面歷史的以前的修訂版本，
或是只選擇匯出帶有最後一次編輯訊息的目前修訂版本。

此外{{GENDER:|你|妳|你}}還可以利用連結匯出檔案，例如{{GENDER:|你|妳|你}}可以使用 [[{{#Special:Export}}/{{MediaWiki:Mainpage}}]] 匯出「[[{{MediaWiki:Mainpage}}]]」頁面。',
'exportcuronly'     => '僅包含目前的修訂，而不是全部的歷史。',
'exportnohistory'   => "----
'''注意:''' 由於性能原因，從此表單匯出頁面的全部歷史已被停用。",
'export-submit'     => '匯出',
'export-addcattext' => '由分類中加入頁面:',
'export-addcat'     => '加入',
'export-addnstext'  => '由名字空間中加入頁面:',
'export-addns'      => '加入',
'export-download'   => '另存為檔案',
'export-templates'  => '包含模板',
'export-pagelinks'  => '包含到這個深度連結之頁面:',

# Namespace 8 related
'allmessages'                   => '系統界面',
'allmessagesname'               => '名稱',
'allmessagesdefault'            => '預設的訊息文字',
'allmessagescurrent'            => '現時的訊息文字',
'allmessagestext'               => '這裡列出所有可定製的系統界面。
如果想貢獻正宗的MediaWiki本地化的話，請參閱[http://www.mediawiki.org/wiki/Localisation MediaWiki本地化]以及[http://translatewiki.net translatewiki.net]。',
'allmessagesnotsupportedDB'     => "這個頁面無法使用，因為'''\$wgUseDatabaseMessages'''已被設定關閉。",
'allmessages-filter-legend'     => '過濾',
'allmessages-filter'            => '以自定狀況過濾：',
'allmessages-filter-unmodified' => '未修改',
'allmessages-filter-all'        => '所有',
'allmessages-filter-modified'   => '曾修改',
'allmessages-prefix'            => '以前綴過濾：',
'allmessages-language'          => '語言：',
'allmessages-filter-submit'     => '往',

# Thumbnails
'thumbnail-more'           => '放大',
'filemissing'              => '無法找到檔案',
'thumbnail_error'          => '創建縮圖錯誤: $1',
'djvu_page_error'          => 'DjVu頁面超出範圍',
'djvu_no_xml'              => '無法在DjVu檔案中擷取XML',
'thumbnail_invalid_params' => '不正確的縮圖參數',
'thumbnail_dest_directory' => '無法建立目標目錄',
'thumbnail_image-type'     => '圖片類型不支援',
'thumbnail_gd-library'     => '未完成的GD設定: 功能遺失 $1',
'thumbnail_image-missing'  => '檔案似乎遺失: $1',

# Special:Import
'import'                     => '匯入頁面',
'importinterwiki'            => '跨 wiki 匯入',
'import-interwiki-text'      => '選擇一個 wiki 和頁面標題以進行匯入。
修訂日期和編輯者名字將同時被儲存。
所有的跨 wiki 匯入操作被記錄在[[Special:Log/import|匯入日誌]]。',
'import-interwiki-source'    => '來源維基／頁面：',
'import-interwiki-history'   => '複製此頁的所有歷史修訂版本',
'import-interwiki-templates' => '包含所有模板',
'import-interwiki-submit'    => '匯入',
'import-interwiki-namespace' => '目的名字空間:',
'import-upload-filename'     => '檔案名：',
'import-comment'             => '註解:',
'importtext'                 => '請使用[[Special:Export|匯出功能]]從來源 wiki 匯出檔案，
儲存到您的磁片並上傳到這裡。',
'importstart'                => '正在匯入頁面...',
'import-revision-count'      => '$1個修訂',
'importnopages'              => '沒有匯入的頁面。',
'imported-log-entries'       => '匯入了$1項日誌記錄。',
'importfailed'               => '匯入失敗: <nowiki>$1</nowiki>',
'importunknownsource'        => '未知的源匯入類型',
'importcantopen'             => '無法打開匯入檔案',
'importbadinterwiki'         => '損壞的內部 wiki 連結',
'importnotext'               => '空或沒有文字',
'importsuccess'              => '匯入完成！',
'importhistoryconflict'      => '存在衝突的修訂歷史（可能在之前已經匯入過此頁面）',
'importnosources'            => '跨Wiki匯入源沒有定義，同時不允許直接的歷史上傳。',
'importnofile'               => '沒有上傳匯入檔案。',
'importuploaderrorsize'      => '上載匯入檔案失敗。檔案大於可以允許的上傳大小。',
'importuploaderrorpartial'   => '上載匯入檔案失敗。檔案只有部份已經上傳。',
'importuploaderrortemp'      => '上載匯入檔案失敗。臨時資料夾已遺失。',
'import-parse-failure'       => 'XML匯入語法失敗',
'import-noarticle'           => '沒有頁面作匯入！',
'import-nonewrevisions'      => '所有的修訂已經在先前匯入。',
'xml-error-string'           => '$1 於行$2，欄$3 （$4位元組）: $5',
'import-upload'              => '上傳XML資料',
'import-token-mismatch'      => '小節資料遺失。請再嘗試。',
'import-invalid-interwiki'   => '不能在指定的wiki匯入。',

# Import log
'importlogpage'                    => '匯入日誌',
'importlogpagetext'                => '來自其它 wiki 的行政性的帶編輯歷史匯入頁面。',
'import-logentry-upload'           => '透過檔案上傳匯入的$1',
'import-logentry-upload-detail'    => '$1個修訂',
'import-logentry-interwiki'        => '跨 wiki $1',
'import-logentry-interwiki-detail' => '來自$2的$1個修訂',

# Tooltip help for the actions
'tooltip-pt-userpage'             => '您的使用者頁面',
'tooltip-pt-anonuserpage'         => '您編輯本站所用IP的對應用戶頁',
'tooltip-pt-mytalk'               => '您的對話頁',
'tooltip-pt-anontalk'             => '對於來自此IP地址編輯的對話',
'tooltip-pt-preferences'          => '您的偏好設定',
'tooltip-pt-watchlist'            => '您所監視頁面的更改列表',
'tooltip-pt-mycontris'            => '您的貢獻列表',
'tooltip-pt-login'                => '建議您登入，儘管並非必須。',
'tooltip-pt-anonlogin'            => '建議您登入，儘管並非必須。',
'tooltip-pt-logout'               => '登出',
'tooltip-ca-talk'                 => '關於頁面正文的討論',
'tooltip-ca-edit'                 => '您可以編輯此頁，請在保存之前先預覽一下。',
'tooltip-ca-addsection'           => '開始一個新小節',
'tooltip-ca-viewsource'           => '該頁面已被保護。{{GENDER:|你|妳|你}}可以檢視該頁原始碼。',
'tooltip-ca-history'              => '本頁面的早前修訂版本',
'tooltip-ca-protect'              => '保護這個頁面',
'tooltip-ca-unprotect'            => '解除保護這個頁面',
'tooltip-ca-delete'               => '刪除本頁',
'tooltip-ca-undelete'             => '將這個頁面恢復到被刪除以前的狀態',
'tooltip-ca-move'                 => '移動本頁',
'tooltip-ca-watch'                => '將此頁面加入監視列表',
'tooltip-ca-unwatch'              => '將此頁面從監視列表中移除',
'tooltip-search'                  => '搜尋該網站',
'tooltip-search-go'               => '如果相同的標題存在的話便直接前往該頁面',
'tooltip-search-fulltext'         => '搜尋該文字的頁面',
'tooltip-p-logo'                  => '訪問首頁',
'tooltip-n-mainpage'              => '訪問首頁',
'tooltip-n-mainpage-description'  => '訪問首頁',
'tooltip-n-portal'                => '關於本計劃、{{GENDER:|你|妳|你}}可以做什麼、應該如何做',
'tooltip-n-currentevents'         => '提供目前新聞事件的背景資料',
'tooltip-n-recentchanges'         => '列出該網站中的最近修改',
'tooltip-n-randompage'            => '隨機載入一個頁面',
'tooltip-n-help'                  => '尋求幫助',
'tooltip-t-whatlinkshere'         => '列出所有與本頁相連的頁面',
'tooltip-t-recentchangeslinked'   => '頁面連出所有頁面的更改',
'tooltip-feed-rss'                => '訂閱本頁面歷史的RSS資訊',
'tooltip-feed-atom'               => '訂閱本頁面歷史的Atom訊息',
'tooltip-t-contributions'         => '檢視該用戶的貢獻列表',
'tooltip-t-emailuser'             => '向該用戶發送電子郵件',
'tooltip-t-upload'                => '上傳檔案',
'tooltip-t-specialpages'          => '全部特殊頁面的列表',
'tooltip-t-print'                 => '這個頁面的可列印版本',
'tooltip-t-permalink'             => '這個頁面修訂版本的永久連結',
'tooltip-ca-nstab-main'           => '檢視頁面內容',
'tooltip-ca-nstab-user'           => '檢視使用者頁面',
'tooltip-ca-nstab-media'          => '檢視多媒體檔案資訊頁面',
'tooltip-ca-nstab-special'        => '本頁面會隨著資料庫的數據即時更新，任何人均不能直接編輯',
'tooltip-ca-nstab-project'        => '檢視項目頁面',
'tooltip-ca-nstab-image'          => '檢視檔案頁面',
'tooltip-ca-nstab-mediawiki'      => '檢視系統資訊',
'tooltip-ca-nstab-template'       => '檢視模板',
'tooltip-ca-nstab-help'           => '檢視幫助頁面',
'tooltip-ca-nstab-category'       => '檢視分類頁面',
'tooltip-minoredit'               => '標記為小修改',
'tooltip-save'                    => '保存您的修改',
'tooltip-preview'                 => '預覽您的編輯，請先使用本功能再保存！',
'tooltip-diff'                    => '顯示您對頁面的貢獻',
'tooltip-compareselectedversions' => '檢視本頁被點選的兩個修訂版本間的差異',
'tooltip-watch'                   => '將此頁加入您的監視列表',
'tooltip-recreate'                => '重建該頁面，無論是否被刪除。',
'tooltip-upload'                  => '開始上傳',
'tooltip-rollback'                => '『反轉』可以一按恢復上一位貢獻者對這個頁面的編輯',
'tooltip-undo'                    => '『復原』可以在編輯模式上開啟編輯表格以便復原。它容許在摘要中加入原因。',
'tooltip-preferences-save'        => '儲存使用偏好',
'tooltip-summary'                 => '輸入一個簡短的摘要',

# Stylesheets
'common.css'      => '/* 此處的 CSS 將應用於所有的面板 */',
'standard.css'    => '/* 此處的 CSS 將影響使用標準面板的用戶 */',
'nostalgia.css'   => '/* 此處的 CSS 將影響使用懷舊面板的用戶 */',
'cologneblue.css' => '/* 此處的 CSS 將影響使用科隆香水藍面板的用戶 */',
'monobook.css'    => '/* 此處的 CSS 將影響使用 Monobook 面板的用戶 */',
'myskin.css'      => '/* 此處的 CSS 將影響使用 MySkin 面板的用戶 */',
'chick.css'       => '/* 此處的 CSS 將影響使用 Chick 面板的用戶 */',
'simple.css'      => '/* 此處的 CSS 將影響使用 Simple 面板的用戶 */',
'modern.css'      => '/* 此處的 CSS 將影響使用 Modern 面板的用戶 */',
'vector.css'      => '/* 此處的 CSS 將影響使用 Vector 面板的用戶 */',
'print.css'       => '/* 此處的 CSS 將影響打印輸出 */',
'handheld.css'    => '/* 此處的 CSS 將影響在 $wgHandheldStyle 設定手提裝置面板 */',

# Scripts
'common.js'      => '/* 此處的JavaScript將載入於所有用戶每一個頁面。 */',
'standard.js'    => '/* 此處的JavaScript將載入於使用標準面板的用戶 */',
'nostalgia.js'   => '/* 此處的JavaScript將載入於使用懷舊面板的用戶 */',
'cologneblue.js' => '/* 此處的JavaScript將載入於使用科隆香水藍面板的用戶 */',
'monobook.js'    => '/* 此處的JavaScript將載入於使用Monobook面板的用戶 */',
'myskin.js'      => '/* 此處的JavaScript將載入於使用MySkin面板的用戶 */',
'chick.js'       => '/* 此處的JavaScript將載入於使用Chick面板的用戶 */',
'simple.js'      => '/* 此處的JavaScript將載入於使用Simple面板的用戶 */',
'modern.js'      => '/* 此處的JavaScript將載入於使用Modern面板的用戶 */',
'vector.js'      => '/* 此處的JavaScript將載入於使用Vector面板的用戶 */',

# Metadata
'nodublincore'      => 'Dublin Core RDF 元數據在該伺服器不可使用。',
'nocreativecommons' => 'Creative Commons RDF 元數據在該伺服器不可使用。',
'notacceptable'     => '該網站伺服器不能提供您的客戶端能識別的數據格式。',

# Attribution
'anonymous'        => '{{SITENAME}}的匿名{{PLURAL:$1|用戶|用戶}}',
'siteuser'         => '{{SITENAME}}用戶$1',
'anonuser'         => '{{SITENAME}}匿名用戶$1',
'lastmodifiedatby' => '此頁由 $3 於 $1 $2 的最後更改。',
'othercontribs'    => '在$1的工作基礎上。',
'others'           => '其他',
'siteusers'        => '{{SITENAME}}{{PLURAL:$2|用戶|用戶}}$1',
'anonusers'        => '{{SITENAME}}匿名{{PLURAL:$2|用戶|用戶}}$1',
'creditspage'      => '頁面致謝',
'nocredits'        => '該頁沒有致謝名單訊息。',

# Spam protection
'spamprotectiontitle' => '垃圾過濾器',
'spamprotectiontext'  => '您要保存的文字被垃圾過濾器阻止。
這可能是由於一個連往匹配黑名單的外部站點的連結引起的。',
'spamprotectionmatch' => '觸發了我們的垃圾過濾器的文本如下：$1',
'spambot_username'    => 'MediaWiki 廣告清除',
'spam_reverting'      => '恢復到不包含連結至$1的最近修訂版本',
'spam_blanking'       => '所有包含連結至$1的修訂，清空',

# Info page
'infosubtitle'   => '頁面訊息',
'numedits'       => '編輯數 （頁面）: $1',
'numtalkedits'   => '編輯數 （討論頁）: $1',
'numwatchers'    => '監視者數目: $1',
'numauthors'     => '作者數量 （頁面）: $1',
'numtalkauthors' => '作者數量 （討論頁）: $1',

# Skin names
'skinname-standard'    => '標準',
'skinname-nostalgia'   => '懷舊',
'skinname-cologneblue' => '科隆香水藍',
'skinname-modern'      => '現代',

# Math options
'mw_math_png'    => '永遠使用PNG圖片',
'mw_math_simple' => '如果是簡單的公式使用HTML，否則使用PNG圖片',
'mw_math_html'   => '如果可以用HTML，否則用PNG圖片',
'mw_math_source' => '顯示為TeX代碼 （使用文字瀏覽器時）',
'mw_math_modern' => '推薦為新版瀏覽器使用',
'mw_math_mathml' => '儘可能使用MathML （試驗中）',

# Math errors
'math_failure'          => '解析失敗',
'math_unknown_error'    => '未知錯誤',
'math_unknown_function' => '未知函數',
'math_lexing_error'     => '句法錯誤',
'math_syntax_error'     => '語法錯誤',
'math_image_error'      => 'PNG 轉換失敗；請檢查是否正確安裝了 latex, dvipng（或dvips + gs + convert）',
'math_bad_tmpdir'       => '無法寫入或建立數學公式臨時目錄',
'math_bad_output'       => '無法寫入或建立數學公式輸出目錄',
'math_notexvc'          => '"texvc"執行檔案遺失；請參照 math/README 進行配置。',

# Patrolling
'markaspatrolleddiff'                 => '標記為已巡查',
'markaspatrolledtext'                 => '標記此頁面為已巡查',
'markedaspatrolled'                   => '標記為已檢查',
'markedaspatrolledtext'               => '[[:$1]]的已選定修訂版本已被標識為已巡查。',
'rcpatroldisabled'                    => '最新更改檢查被關閉',
'rcpatroldisabledtext'                => '最新更改檢查的功能目前已關閉。',
'markedaspatrollederror'              => '不能標誌為已檢查',
'markedaspatrollederrortext'          => '{{GENDER:|你|妳|你}}需要指定某個版本才能標誌為已檢查。',
'markedaspatrollederror-noautopatrol' => '您無法將{{GENDER:|你|妳|你}}自己所作的更改標記為已檢查。',

# Patrol log
'patrol-log-page'      => '巡查日誌',
'patrol-log-header'    => '這是已巡查的修訂版本的日誌。',
'patrol-log-line'      => '$2的版本$1已被標記為已巡查$3',
'patrol-log-auto'      => '（自動）',
'patrol-log-diff'      => '修訂 $1',
'log-show-hide-patrol' => '$1巡查記錄',

# Image deletion
'deletedrevision'                 => '已刪除舊版本$1',
'filedeleteerror-short'           => '刪除檔案發生錯誤: $1',
'filedeleteerror-long'            => '當刪除檔案時遇到錯誤:

$1',
'filedelete-missing'              => '因為檔案 "$1" 不存在，所以它不可以刪除。',
'filedelete-old-unregistered'     => '所指定的檔案修訂 "$1" 在資料庫中不存在。',
'filedelete-current-unregistered' => '所指定的檔案 "$1" 在資料庫中不存在。',
'filedelete-archive-read-only'    => '存檔目錄 "$1" 在網頁伺服器中不可寫。',

# Browsing diffs
'previousdiff' => '←上一版本',
'nextdiff'     => '下一版本→',

# Media information
'mediawarning'         => "'''警告''': 該檔案類型可能包含惡意代碼。
執行它可能對您的系統帶來危險。",
'imagemaxsize'         => "影像大小限制:<br />''（用在檔案描述頁面中）''",
'thumbsize'            => '略圖大小:',
'widthheightpage'      => '$1×$2, $3頁',
'file-info'            => '檔案大小: $1, MIME 類型: $2',
'file-info-size'       => '$1 × $2 像素，檔案大小：$3，MIME類型：$4',
'file-nohires'         => '<small>無更高解像度可提供。</small>',
'svg-long-desc'        => 'SVG檔案，表面大小： $1 × $2 像素，檔案大小：$3',
'show-big-image'       => '完整解像度',
'show-big-image-thumb' => '<small>這幅縮圖的解像度: $1 × $2 像素</small>',
'file-info-gif-looped' => '循環',
'file-info-gif-frames' => '$1幀',
'file-info-png-looped' => '循環',
'file-info-png-repeat' => '已播放$1次',
'file-info-png-frames' => '$1幀',

# Special:NewFiles
'newimages'             => '新建圖片畫廊',
'imagelisttext'         => "以下是按$2排列的'''$1'''個檔案列表。",
'newimages-summary'     => '這個特殊頁面中顯示最後已上傳的檔案。',
'newimages-legend'      => '過濾',
'newimages-label'       => '檔案名稱（或它的一部份）:',
'showhidebots'          => '(機器人$1)',
'noimages'              => '無可檢視圖片。',
'ilsubmit'              => '搜尋',
'bydate'                => '按日期',
'sp-newimages-showfrom' => '從$1 $2開始顯示新檔案',

# Bad image list
'bad_image_list' => '請按照下列格式編寫：

只有（以 * 開頭）列出的項目會被考慮。每一行的第一個連結必須是不雅文件的連結。
然後同一行後方的連結會被視為例外，即是該文件可以在哪些頁面內被顯示。',

/*
Short names for language variants used for language conversion links.
To disable showing a particular link, set it to 'disable', e.g.
'variantname-zh-sg' => 'disable',
Variants for Chinese language
*/
'variantname-zh-hans' => '簡體',
'variantname-zh-hant' => '繁體',
'variantname-zh-cn'   => '大陸簡體',
'variantname-zh-tw'   => '台灣正體',
'variantname-zh-hk'   => '香港繁體',
'variantname-zh-sg'   => '新加坡簡體',
'variantname-zh'      => '不轉換',

# Metadata
'metadata'          => '元數據',
'metadata-help'     => '此檔案中包含有擴展的訊息。這些訊息可能是由數位相機或掃描儀在創建或數字化過程中所添加的。

如果此檔案的源檔案已經被修改，一些訊息在修改後的檔案中將不能完全反映出來。',
'metadata-expand'   => '顯示詳細資料',
'metadata-collapse' => '隱藏詳細資料',
'metadata-fields'   => '在本訊息中所列出的 EXIF 元數據域將包含在圖片顯示頁面,
當元數據表損壞時只顯示以下訊息，其他的元數據預設為隱藏。
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* isospeedratings
* focallength',

# EXIF tags
'exif-imagewidth'                  => '寬度',
'exif-imagelength'                 => '高度',
'exif-bitspersample'               => '每象素比特數',
'exif-compression'                 => '壓縮方案',
'exif-photometricinterpretation'   => '象素合成',
'exif-orientation'                 => '方位',
'exif-samplesperpixel'             => '象素數',
'exif-planarconfiguration'         => '數據排列',
'exif-ycbcrsubsampling'            => '黃色對洋紅二次抽樣比率',
'exif-ycbcrpositioning'            => '黃色和洋紅配置',
'exif-xresolution'                 => '水準分辨率',
'exif-yresolution'                 => '垂直分辨率',
'exif-resolutionunit'              => 'X 軸與 Y 軸分辨率單位',
'exif-stripoffsets'                => '圖片數據區',
'exif-rowsperstrip'                => '每帶行數',
'exif-stripbytecounts'             => '每壓縮帶位元組數',
'exif-jpeginterchangeformat'       => 'JPEG SOI 偏移',
'exif-jpeginterchangeformatlength' => 'JPEG 數據位元組',
'exif-transferfunction'            => '轉移功能',
'exif-whitepoint'                  => '白點色度',
'exif-primarychromaticities'       => '主要色度',
'exif-ycbcrcoefficients'           => '顏色空間轉換矩陣系數',
'exif-referenceblackwhite'         => '黑白參照值對',
'exif-datetime'                    => '檔案更改日期和時間',
'exif-imagedescription'            => '圖片標題',
'exif-make'                        => '照相機製造商',
'exif-model'                       => '照相機型號',
'exif-software'                    => '所用軟體',
'exif-artist'                      => '作者',
'exif-copyright'                   => '版權所有者',
'exif-exifversion'                 => 'Exif 版本',
'exif-flashpixversion'             => '支援的 Flashpix 版本',
'exif-colorspace'                  => '顏色空間',
'exif-componentsconfiguration'     => '每分量含義',
'exif-compressedbitsperpixel'      => '圖片壓縮模式',
'exif-pixelydimension'             => '有效圖片寬度',
'exif-pixelxdimension'             => '有效圖片高度',
'exif-makernote'                   => '製造商註釋',
'exif-usercomment'                 => '用戶註釋',
'exif-relatedsoundfile'            => '相關的音頻檔案',
'exif-datetimeoriginal'            => '數據產生時間',
'exif-datetimedigitized'           => '數字化處理時間',
'exif-subsectime'                  => '日期時間秒',
'exif-subsectimeoriginal'          => '原始日期時間秒',
'exif-subsectimedigitized'         => '數字化日期時間秒',
'exif-exposuretime'                => '曝光時間',
'exif-exposuretime-format'         => '$1 秒 （$2）',
'exif-fnumber'                     => '光圈（F值）',
'exif-exposureprogram'             => '曝光模式',
'exif-spectralsensitivity'         => '感光',
'exif-isospeedratings'             => 'ISO 速率',
'exif-oecf'                        => '光電轉換因子',
'exif-shutterspeedvalue'           => '快門速度',
'exif-aperturevalue'               => '光圈',
'exif-brightnessvalue'             => '亮度',
'exif-exposurebiasvalue'           => '曝光補償',
'exif-maxaperturevalue'            => '最大陸地光圈',
'exif-subjectdistance'             => '物距',
'exif-meteringmode'                => '測量模式',
'exif-lightsource'                 => '光源',
'exif-flash'                       => '閃光燈',
'exif-focallength'                 => '焦距',
'exif-subjectarea'                 => '主體區域',
'exif-flashenergy'                 => '閃光燈強度',
'exif-spatialfrequencyresponse'    => '空間頻率附應',
'exif-focalplanexresolution'       => 'X軸焦平面分辨率',
'exif-focalplaneyresolution'       => 'Y軸焦平面分辨率',
'exif-focalplaneresolutionunit'    => '焦平面分辨率單位',
'exif-subjectlocation'             => '主題位置',
'exif-exposureindex'               => '曝光指數',
'exif-sensingmethod'               => '感光模式',
'exif-filesource'                  => '檔案源',
'exif-scenetype'                   => '場景類型',
'exif-cfapattern'                  => 'CFA 模式',
'exif-customrendered'              => '自訂圖片處理',
'exif-exposuremode'                => '曝光模式',
'exif-whitebalance'                => '白平衡',
'exif-digitalzoomratio'            => '數字變焦比率',
'exif-focallengthin35mmfilm'       => '35毫米膠片焦距',
'exif-scenecapturetype'            => '情景拍攝類型',
'exif-gaincontrol'                 => '場景控制',
'exif-contrast'                    => '對比度',
'exif-saturation'                  => '飽和度',
'exif-sharpness'                   => '銳化',
'exif-devicesettingdescription'    => '設備設定描述',
'exif-subjectdistancerange'        => '主體距離範圍',
'exif-imageuniqueid'               => '唯一圖片ID',
'exif-gpsversionid'                => 'GPS 標籤（tag）版本',
'exif-gpslatituderef'              => '北緯或南緯',
'exif-gpslatitude'                 => '緯度',
'exif-gpslongituderef'             => '東經或西經',
'exif-gpslongitude'                => '經度',
'exif-gpsaltituderef'              => '海拔正負參照',
'exif-gpsaltitude'                 => '海拔',
'exif-gpstimestamp'                => 'GPS 時間（原子時鐘）',
'exif-gpssatellites'               => '測量使用的衛星',
'exif-gpsstatus'                   => '接收器狀態',
'exif-gpsmeasuremode'              => '測量模式',
'exif-gpsdop'                      => '測量精度',
'exif-gpsspeedref'                 => '速度單位',
'exif-gpsspeed'                    => 'GPS 接收器速度',
'exif-gpstrackref'                 => '運動方位參照',
'exif-gpstrack'                    => '運動方位',
'exif-gpsimgdirectionref'          => '圖片方位參照',
'exif-gpsimgdirection'             => '圖片方位',
'exif-gpsmapdatum'                 => '使用地理測繪數據',
'exif-gpsdestlatituderef'          => '目標緯度參照',
'exif-gpsdestlatitude'             => '目標緯度',
'exif-gpsdestlongituderef'         => '目標經度的參照',
'exif-gpsdestlongitude'            => '目標經度',
'exif-gpsdestbearingref'           => '目標方位參照',
'exif-gpsdestbearing'              => '目標方位',
'exif-gpsdestdistanceref'          => '目標距離參照',
'exif-gpsdestdistance'             => '目標距離',
'exif-gpsprocessingmethod'         => 'GPS 處理方法名稱',
'exif-gpsareainformation'          => 'GPS 區域名稱',
'exif-gpsdatestamp'                => 'GPS 日期',
'exif-gpsdifferential'             => 'GPS 差動修正',

# EXIF attributes
'exif-compression-1' => '未壓縮',

'exif-unknowndate' => '未知的日期',

'exif-orientation-1' => '標準',
'exif-orientation-2' => '水準翻轉',
'exif-orientation-3' => '旋轉180°',
'exif-orientation-4' => '垂直翻轉',
'exif-orientation-5' => '旋轉90° 逆時針並垂直翻轉',
'exif-orientation-6' => '旋轉90° 順時針',
'exif-orientation-7' => '旋轉90° 順時針並垂直翻轉',
'exif-orientation-8' => '旋轉90° 逆時針',

'exif-planarconfiguration-1' => '矮胖格式',
'exif-planarconfiguration-2' => '平面格式',

'exif-componentsconfiguration-0' => '不存在',

'exif-exposureprogram-0' => '未定義',
'exif-exposureprogram-1' => '手動',
'exif-exposureprogram-2' => '標準程式',
'exif-exposureprogram-3' => '光圈優先模式',
'exif-exposureprogram-4' => '快門優先模式',
'exif-exposureprogram-5' => '藝術程式（景深優先）',
'exif-exposureprogram-6' => '運動程式（快速快門速度優先）',
'exif-exposureprogram-7' => '肖像模式（適用於背景在焦距以外的近距攝影）',
'exif-exposureprogram-8' => '風景模式（適用於背景在焦距上的風景照片）',

'exif-subjectdistance-value' => '$1米',

'exif-meteringmode-0'   => '未知',
'exif-meteringmode-1'   => '平均水準',
'exif-meteringmode-2'   => '中心加權平均測量',
'exif-meteringmode-3'   => '點測',
'exif-meteringmode-4'   => '多點測',
'exif-meteringmode-5'   => '模式測量',
'exif-meteringmode-6'   => '局部測量',
'exif-meteringmode-255' => '其他',

'exif-lightsource-0'   => '未知',
'exif-lightsource-1'   => '日光燈',
'exif-lightsource-2'   => '熒光燈',
'exif-lightsource-3'   => '鎢絲燈（白熾燈）',
'exif-lightsource-4'   => '閃光燈',
'exif-lightsource-9'   => '晴天',
'exif-lightsource-10'  => '多雲',
'exif-lightsource-11'  => '深色調陰影',
'exif-lightsource-12'  => '日光熒光燈（色溫 D 5700    7100K）',
'exif-lightsource-13'  => '日溫白色熒光燈（N 4600    5400K）',
'exif-lightsource-14'  => '冷白色熒光燈（W 3900    4500K）',
'exif-lightsource-15'  => '白色熒光 （WW 3200    3700K）',
'exif-lightsource-17'  => '標準燈光A',
'exif-lightsource-18'  => '標準燈光B',
'exif-lightsource-19'  => '標準燈光C',
'exif-lightsource-24'  => 'ISO攝影棚鎢燈',
'exif-lightsource-255' => '其他光源',

# Flash modes
'exif-flash-fired-0'    => '閃光燈無開火',
'exif-flash-fired-1'    => '閃光燈開火',
'exif-flash-return-0'   => '無頻閃觀測器功能',
'exif-flash-return-2'   => '頻閃觀測器未偵測到光',
'exif-flash-return-3'   => '頻閃觀測器偵測到光',
'exif-flash-mode-1'     => '強制閃光燈開火',
'exif-flash-mode-2'     => '強制壓制閃光燈',
'exif-flash-mode-3'     => '自動模式',
'exif-flash-function-1' => '無閃光燈功能',
'exif-flash-redeye-1'   => '紅眼減退模式',

'exif-focalplaneresolutionunit-2' => '英寸',

'exif-sensingmethod-1' => '未定義',
'exif-sensingmethod-2' => '一塊彩色區域傳感器',
'exif-sensingmethod-3' => '兩塊彩色區域傳感器',
'exif-sensingmethod-4' => '三塊彩色區域傳感器',
'exif-sensingmethod-5' => '連續彩色區域傳感器',
'exif-sensingmethod-7' => '三線傳感器',
'exif-sensingmethod-8' => '連續彩色線性傳感器',

'exif-scenetype-1' => '直接照像圖片',

'exif-customrendered-0' => '標準處理',
'exif-customrendered-1' => '自定義處理',

'exif-exposuremode-0' => '自動曝光',
'exif-exposuremode-1' => '手動曝光',
'exif-exposuremode-2' => '自動曝光感知調節',

'exif-whitebalance-0' => '自動白平衡',
'exif-whitebalance-1' => '手動白平衡',

'exif-scenecapturetype-0' => '標準',
'exif-scenecapturetype-1' => '風景',
'exif-scenecapturetype-2' => '肖像',
'exif-scenecapturetype-3' => '夜景',

'exif-gaincontrol-0' => '無',
'exif-gaincontrol-1' => '低增益',
'exif-gaincontrol-2' => '高增益',
'exif-gaincontrol-3' => '低減益',
'exif-gaincontrol-4' => '高減益',

'exif-contrast-0' => '標準',
'exif-contrast-1' => '低',
'exif-contrast-2' => '高',

'exif-saturation-0' => '標準',
'exif-saturation-1' => '低飽和度',
'exif-saturation-2' => '高飽和度',

'exif-sharpness-0' => '標準',
'exif-sharpness-1' => '低',
'exif-sharpness-2' => '高',

'exif-subjectdistancerange-0' => '未知',
'exif-subjectdistancerange-1' => '自動處理程式（宏）',
'exif-subjectdistancerange-2' => '近景',
'exif-subjectdistancerange-3' => '遠景',

# Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => '北緯',
'exif-gpslatitude-s' => '南緯',

# Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => '東經',
'exif-gpslongitude-w' => '西經',

'exif-gpsstatus-a' => '測量過程',
'exif-gpsstatus-v' => '互動測量',

'exif-gpsmeasuremode-2' => '二維測量',
'exif-gpsmeasuremode-3' => '三維測量',

# Pseudotags used for GPSSpeedRef
'exif-gpsspeed-k' => '公里每小時',
'exif-gpsspeed-m' => '英里每小時',
'exif-gpsspeed-n' => '海里每小時（節）',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => '真方位',
'exif-gpsdirection-m' => '地磁方位',

# External editor support
'edit-externally'      => '用外部程式編輯此檔案',
'edit-externally-help' => '（請參見[http://www.mediawiki.org/wiki/Manual:External_editors 設定步驟]了解詳細資訊）',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => '全部',
'imagelistall'     => '全部',
'watchlistall2'    => '全部',
'namespacesall'    => '全部',
'monthsall'        => '全部',
'limitall'         => '全部',

# E-mail address confirmation
'confirmemail'              => '確認郵箱位址',
'confirmemail_noemail'      => '您沒有在您的[[Special:Preferences|用戶設定]]裡面輸入一個有效的 email 位址。',
'confirmemail_text'         => '{{SITENAME}}要求您在使用郵件功能之前驗證您的郵箱位址。
點擊以下按鈕可向您的郵箱發送一封確認郵件。該郵件包含有一行代碼連結；
請在您的瀏覽器中加載此連結以確認您的郵箱位址是有效的。',
'confirmemail_pending'      => '一個確認碼已經被發送到您的郵箱，您可能需要等幾分鐘才能收到。如果無法收到，請再申請一個新的確認碼。',
'confirmemail_send'         => '郵發確認代碼',
'confirmemail_sent'         => '確認郵件已發送。',
'confirmemail_oncreate'     => '一個確認代碼已經被發送到您的郵箱。該代碼並不要求您進行登入，
但若您要啟用在此 wiki 上的任何基於電子郵件的功能，您必須先提交此代碼。',
'confirmemail_sendfailed'   => '{{SITENAME}}無法發送確認郵件，請檢查郵箱位址是否包含非法字元。

郵件傳送員回應: $1',
'confirmemail_invalid'      => '無效的確認碼，該代碼可能已經過期。',
'confirmemail_needlogin'    => '您需要$1以確認您的郵箱位址。',
'confirmemail_success'      => '您的郵箱已經被確認。您現在可以[[Special:UserLogin|登錄]]並使用此網站了。',
'confirmemail_loggedin'     => '您的郵箱位址現下已被確認。',
'confirmemail_error'        => '{{GENDER:|你|妳|你}}的確認過程發生錯誤。',
'confirmemail_subject'      => '{{SITENAME}}郵箱位址確認',
'confirmemail_body'         => '擁有IP位址$1的用戶（可能是您）在{{SITENAME}}創建了賬戶"$2"，並提交了您的電子郵箱位址。

請確認這個賬戶是屬於您的，並同時啟用在{{SITENAME}}上的
電子郵件功能。請在瀏覽器中打開下面的連結:

$3

如果您*未*註冊賬戶，
請打開下面的連結去取消電子郵件確認:

$5

確認碼會在$4過期。',
'confirmemail_body_changed' => '擁有IP位址$1的用戶（可能是您）在{{SITENAME}}更改了賬戶"$2"的電子郵箱位址。

請確認這個賬戶是屬於您的，並同時重新啟用在{{SITENAME}}上的
電子郵件功能。請在瀏覽器中打開下面的連結:

$3

如果這個賬戶*不是*屬於您的，
請打開下面的連結去取消電子郵件確認:

$5

確認碼會在$4過期。',
'confirmemail_invalidated'  => '電郵地址確認已取消',
'invalidateemail'           => '取消電郵確認',

# Scary transclusion
'scarytranscludedisabled' => '[跨wiki轉換代碼不可用]',
'scarytranscludefailed'   => '[模板$1讀取失敗]',
'scarytranscludetoolong'  => '[URL 地址太長]',

# Trackbacks
'trackbackbox'      => '此頁面的引用:<br />
$1',
'trackbackremove'   => '（[$1刪除]）',
'trackbacklink'     => '迴響',
'trackbackdeleteok' => 'Trackback 刪除成功。',

# Delete conflict
'deletedwhileediting' => '警告: 此頁在您開始編輯之後已經被刪除﹗',
'confirmrecreate'     => "在您開始編輯這個頁面後，用戶[[User:$1|$1]] （[[User talk:$1|對話]]）以下列原因刪除了這個頁面：
: ''$2''
請確認在您重新創建頁面前三思。",
'recreate'            => '重建',

# action=purge
'confirm_purge_button' => '確定',
'confirm-purge-top'    => '要清除此頁面的快取嗎?',
'confirm-purge-bottom' => '清理一頁將會清除快取以及強迫顯示最現時之修訂版本。',

# Separators for various lists, etc.
'comma-separator' => '、',
'word-separator'  => '',
'parentheses'     => '（$1）',

# Multipage image navigation
'imgmultipageprev' => '← 上一頁',
'imgmultipagenext' => '下一頁 →',
'imgmultigo'       => '確定！',
'imgmultigoto'     => '到第$1頁',

# Table pager
'ascending_abbrev'         => '升',
'descending_abbrev'        => '遞減',
'table_pager_next'         => '下一頁',
'table_pager_prev'         => '上一頁',
'table_pager_first'        => '第一頁',
'table_pager_last'         => '最末頁',
'table_pager_limit'        => '每頁顯示 $1 筆記錄',
'table_pager_limit_label'  => '每頁項目數︰',
'table_pager_limit_submit' => '送出',
'table_pager_empty'        => '沒有結果',

# Auto-summaries
'autosumm-blank'   => '清空頁面',
'autosumm-replace' => '以「$1」替換內容',
'autoredircomment' => '重定向頁面到[[$1]]',
'autosumm-new'     => '以內容「$1」創建新頁面',

# Size units
'size-bytes' => '$1 位元組',

# Live preview
'livepreview-loading' => '正在載入…',
'livepreview-ready'   => '正在載入… 完成!',
'livepreview-failed'  => '實時預覽失敗!
嘗試標準預覽。',
'livepreview-error'   => '連接失敗: $1 "$2"。
嘗試標準預覽。',

# Friendlier slave lag warnings
'lag-warn-normal' => '過去$1秒內的更改未必會在這個清單中顯示。',
'lag-warn-high'   => '由於資料庫的過度延遲，過去$1秒內的更改未必會在這個清單中顯示。',

# Watchlist editor
'watchlistedit-numitems'       => '您的監視列表中共有$1個標題，當中不包括對話頁面。',
'watchlistedit-noitems'        => '您的監視列表並無標題。',
'watchlistedit-normal-title'   => '編輯監視列表',
'watchlistedit-normal-legend'  => '從監視列表中移除標題',
'watchlistedit-normal-explain' => '在您的監視列表中的標題在下面顯示。要移除一個標題，在它前面剔一下，接著點擊「{{int:Watchlistedit-normal-submit}}」。您亦可以[[Special:Watchlist/raw|編輯原始監視列表]]。',
'watchlistedit-normal-submit'  => '移除標題',
'watchlistedit-normal-done'    => '$1個標題已經從您的監視列表中移除:',
'watchlistedit-raw-title'      => '編輯原始監視列表',
'watchlistedit-raw-legend'     => '編輯原始監視列表',
'watchlistedit-raw-explain'    => '您的監視列表中的標題在下面顯示，同時亦都可以透過編輯這個表去加入以及移除標題；一行一個標題。當完成以後，點擊{{int:Watchlistedit-raw-submit}}。{{GENDER:|你|妳|你}}亦都可以去用[[Special:Watchlist/edit|標準編輯器]]。',
'watchlistedit-raw-titles'     => '標題：',
'watchlistedit-raw-submit'     => '更新監視列表',
'watchlistedit-raw-done'       => '您的監視列表已經更新。',
'watchlistedit-raw-added'      => '已經加入了$1個標題:',
'watchlistedit-raw-removed'    => '已經移除了$1個標題:',

# Watchlist editing tools
'watchlisttools-view' => '檢視有關更改',
'watchlisttools-edit' => '檢視並編輯監視列表',
'watchlisttools-raw'  => '編輯原始監視列表',

# Core parser functions
'unknown_extension_tag' => '不明的擴展標籤 "$1"',
'duplicate-defaultsort' => '警告: 預設的排序鍵 "$2" 覆蓋先前的預設排序鍵 "$1"。',

# Special:Version
'version'                          => '版本',
'version-extensions'               => '已經安裝的擴展',
'version-specialpages'             => '特殊頁面',
'version-parserhooks'              => '語法鈎',
'version-variables'                => '變數',
'version-antispam'                 => '垃圾防止',
'version-skins'                    => '外觀',
'version-other'                    => '其他',
'version-mediahandlers'            => '媒體處理器',
'version-hooks'                    => '鈎',
'version-extension-functions'      => '擴展函數',
'version-parser-extensiontags'     => '語法擴展標籤',
'version-parser-function-hooks'    => '語法函數鈎',
'version-skin-extension-functions' => '面版擴展函數',
'version-hook-name'                => '鈎名',
'version-hook-subscribedby'        => '利用於',
'version-version'                  => '（版本 $1）',
'version-license'                  => '授權',
'version-poweredby-credits'        => "這個 Wiki 由 '''[http://www.mediawiki.org/ MediaWiki]''' 驅動，版權所有 © 2001-$1 $2。",
'version-poweredby-others'         => '其他',
'version-license-info'             => 'MediaWiki為自由軟件；您可依據自由軟件基金會所發表的GNU通用公共授權條款規定，就本程式再為發佈與／或修改；無論您依據的是本授權的第二版或（您自行選擇的）任一日後發行的版本。

MediaWiki是基於使用目的而加以發佈，然而不負任何擔保責任；亦無對適售性或特定目的適用性所為的默示性擔保。詳情請參照GNU通用公共授權。

您應已收到附隨於本程式的[{{SERVER}}{{SCRIPTPATH}}/COPYING GNU通用公共授權的副本]；如果沒有，請寫信至自由軟件基金會：51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA，或[http://www.gnu.org/licenses/old-licenses/gpl-2.0.html 線上閱讀]。',
'version-software'                 => '已經安裝的軟件',
'version-software-product'         => '產品',
'version-software-version'         => '版本',

# Special:FilePath
'filepath'         => '檔案路徑',
'filepath-page'    => '檔案名：',
'filepath-submit'  => '前往',
'filepath-summary' => '這個特殊頁面擷取一個檔案的完整路徑。圖片會以完整的解像度顯示，其它的檔案類型會以同它們已關聯程式啟動。

請輸入檔名，不要包含"{{ns:file}}:"開頭。',

# Special:FileDuplicateSearch
'fileduplicatesearch'          => '選擇重覆檔案',
'fileduplicatesearch-summary'  => '用重覆檔案的切細值去找出檔案是否重覆。

輸入檔名時不需要輸入 "{{ns:file}}:" 開頭。',
'fileduplicatesearch-legend'   => '找重覆',
'fileduplicatesearch-filename' => '檔案名稱:',
'fileduplicatesearch-submit'   => '找',
'fileduplicatesearch-info'     => '$1 × $2 像素<br />檔案大小：$3<br />MIME 類型：$4',
'fileduplicatesearch-result-1' => '檔案 "$1" 無完全相同的重覆。',
'fileduplicatesearch-result-n' => '檔案 "$1" 有$2項完全相同的重覆。',

# Special:SpecialPages
'specialpages'                   => '特殊頁面',
'specialpages-note'              => '----
* 標準特殊頁面。
* <strong class="mw-specialpagerestricted">有限制的特殊頁面。</strong>',
'specialpages-group-maintenance' => '維護報告',
'specialpages-group-other'       => '其它特殊頁面',
'specialpages-group-login'       => '登入／創建',
'specialpages-group-changes'     => '最近更改和日誌',
'specialpages-group-media'       => '媒體報告和上傳',
'specialpages-group-users'       => '用戶和權限',
'specialpages-group-highuse'     => '高度使用頁面',
'specialpages-group-pages'       => '頁面清單',
'specialpages-group-pagetools'   => '頁面工具',
'specialpages-group-wiki'        => 'Wiki 資料和工具',
'specialpages-group-redirects'   => '重新定向特殊頁面',
'specialpages-group-spam'        => '反垃圾工具',

# Special:BlankPage
'blankpage'              => '空白頁面',
'intentionallyblankpage' => '這個頁面是為空白',

# External image whitelist
'external_image_whitelist' => ' #留下這行一樣的文字<pre>
#在下面（//之中間部份）輸入正規表達式
#這些將會跟外部（已超連結的）圖片配合
#那些配合到出來的會顯示成圖片，否則就只會顯示成連結
#有 # 開頭的行會當成註解
#大小寫並無區分

#在這行上面輸入所有的regex。留下這行一樣的文字</pre>',

# Special:Tags
'tags'                    => '有效更改過的標籤',
'tag-filter'              => '[[Special:Tags|標籤]]過濾器:',
'tag-filter-submit'       => '過濾器',
'tags-title'              => '標籤',
'tags-intro'              => '這個頁面列示出在軟件中已標示的編輯，以及它們的解釋。',
'tags-tag'                => '標籤名稱',
'tags-display-header'     => '在更改清單中的出現方式',
'tags-description-header' => '解釋完整描述',
'tags-hitcount-header'    => '已加上標籤的更改',
'tags-edit'               => '編輯',
'tags-hitcount'           => '$1次更改',

# Special:ComparePages
'comparepages'     => '比較頁面',
'compare-selector' => '比較頁面的修訂',
'compare-page1'    => '第1頁',
'compare-page2'    => '第2頁',
'compare-rev1'     => '修訂版本1',
'compare-rev2'     => '修訂版本2',
'compare-submit'   => '比較',

# Database error messages
'dberr-header'      => '這個 wiki 出現了問題',
'dberr-problems'    => '抱歉！
這個網站出現了一些技術上的問題。',
'dberr-again'       => '嘗試等候數分鐘後，然後再試。',
'dberr-info'        => '（無法連繫到資料庫伺服器: $1）',
'dberr-usegoogle'   => '在現階段您可以嘗試透過 Google 搜尋。',
'dberr-outofdate'   => '留意他們索引出來之內容可能不是最新的。',
'dberr-cachederror' => '這個是所要求出來的快取複本，可能不是最新的。',

# HTML forms
'htmlform-invalid-input'       => '您輸入的內容存在問題',
'htmlform-select-badoption'    => '您所指定的值不是有效的選項。',
'htmlform-int-invalid'         => '您所指定的值不是一個整數。',
'htmlform-float-invalid'       => '您所指定的值不是一個數字。',
'htmlform-int-toolow'          => '您所指定的值低於最小值$1',
'htmlform-int-toohigh'         => '您所指定的值高於最大值$1',
'htmlform-required'            => '此值是必填項',
'htmlform-submit'              => '遞交',
'htmlform-reset'               => '撤銷更改',
'htmlform-selectorother-other' => '其他',

# SQLite database support
'sqlite-has-fts' => '帶全文搜尋的版本$1',
'sqlite-no-fts'  => '不帶全文搜尋的版本$1',

# Special:DisableAccount
'disableaccount'             => '禁用用戶賬戶',
'disableaccount-user'        => '用戶名：',
'disableaccount-reason'      => '理由：',
'disableaccount-confirm'     => "禁用此用戶賬戶。
該使用者將無法登入、重設其密碼或收到電子郵件通知。如果用戶目前仍保持登錄，其賬戶將被強制登出。
''留意若無系統管理員的干預，被禁用的賬戶不可重新啟用。''",
'disableaccount-mustconfirm' => '請確認您的確要禁用此賬戶。',
'disableaccount-nosuchuser'  => '用戶賬戶「$1」不存在。',
'disableaccount-success'     => '用戶賬戶「$1」已被永久禁用。',
'disableaccount-logentry'    => '永久禁用用戶賬戶[[$1]]',

);
