<?php
/** Chinese (Hong Kong) (中文（香港）‎)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Gakmo
 * @author Horacewai2
 * @author Justincheng12345
 * @author Kayau
 * @author Liflon
 * @author Mark85296341
 * @author Openerror
 * @author PhiLiP
 * @author Shizhao
 * @author Simon Shek
 * @author Waihorace
 * @author Wong128hk
 * @author Yukiseaside
 * @author Yuyu
 */

$fallback = 'zh-hant, zh-hans';

$fallback8bitEncoding = 'Big5-HKSCS';

$specialPageAliases = array(
	'ComparePages'              => array( '頁面比較' ),
	'Unblock'                   => array( '解除封禁' ),
);

$messages = array(
# User preference toggles
'tog-underline' => '連結加底線：',
'tog-hideminor' => '近期變動中隱藏細微修改',
'tog-hidepatrolled' => '於近期變動中隱藏巡查過的編輯',
'tog-newpageshidepatrolled' => '於新頁面清單中隱藏巡查過的頁面',
'tog-extendwatchlist' => '展開監視清單以顯示所有更改',
'tog-usenewrc' => '在最近更改和監視列表中整合同一頁的修改',
'tog-numberheadings' => '標題自動編號',
'tog-showtoolbar' => '顯示編輯工具欄',
'tog-editondblclick' => '雙擊編輯頁面',
'tog-editsectiononrightclick' => '允許右擊標題編輯段落',
'tog-rememberpassword' => '在這個瀏覽器上記住我的登入資訊（可維持 $1 {{PLURAL:$1|天|天}}）',
'tog-watchcreations' => '將我建立的頁面及檔案添加到我的監視列表中',
'tog-watchdefault' => '將我更改的頁面及檔案添加到我的監視列表中',
'tog-watchmoves' => '將我移動的頁面及檔案添加到我的監視列表',
'tog-watchdeletion' => '將我刪除的頁面及檔案添加到我的監視列表',
'tog-minordefault' => '預設將編輯設定為小編輯',
'tog-previewontop' => '在編輯框上方顯示預覽',
'tog-previewonfirst' => '首次編輯時顯示原文內容預覽',
'tog-enotifwatchlistpages' => '當監視列表中的頁面或檔案改變時發電子郵件給我',
'tog-enotifusertalkpages' => '當我的對話頁有更改時發電子郵件通知我',
'tog-enotifminoredits' => '即使是頁面或檔案的小修改也向我發電子郵件',
'tog-enotifrevealaddr' => '在通知電子郵件中顯示我的電子郵件位址',
'tog-shownumberswatching' => '顯示監視用戶的數目',
'tog-oldsig' => '原有簽名：',
'tog-fancysig' => '將簽名以維基文字對待 （不產生自動連結）',
'tog-uselivepreview' => '使用實時預覽（試驗中）',
'tog-forceeditsummary' => '當沒有輸入摘要時提醒我',
'tog-watchlisthideown' => '監視列表中隱藏我的編輯',
'tog-watchlisthidebots' => '監視列表中隱藏機械人的編輯',
'tog-watchlisthideminor' => '監視列表中隱藏小修改',
'tog-watchlisthideliu' => '監視列表中隱藏登入用戶',
'tog-watchlisthideanons' => '監視列表中隱藏匿名用戶',
'tog-watchlisthidepatrolled' => '監視清單中隱藏已巡查的編輯',
'tog-ccmeonemails' => '當我寄電子郵件給其他用戶時，寄一份複本到我的信箱。',
'tog-diffonly' => '在比較兩個修訂版本差異時不顯示頁面內容',
'tog-showhiddencats' => '顯示隱藏分類',
'tog-norollbackdiff' => '回退後略過差異比較',
'tog-useeditwarning' => '當離開頁面時編輯仍未儲存，請提醒我',
'tog-prefershttps' => '登入時永遠使用安全連線',

'underline-always' => '總是使用',
'underline-never' => '從不使用',
'underline-default' => '面板或瀏覽器預設',

# Font style option in Special:Preferences
'editfont-style' => '編輯區字型樣式：',
'editfont-default' => '瀏覽器預設',
'editfont-monospace' => '固定間距字型',
'editfont-sansserif' => '無襯線字型',
'editfont-serif' => '襯線字型',

# Dates
'sunday' => '星期日',
'monday' => '星期一',
'tuesday' => '星期二',
'wednesday' => '星期三',
'thursday' => '星期四',
'friday' => '星期五',
'saturday' => '星期六',
'sun' => '日',
'mon' => '一',
'tue' => '二',
'wed' => '三',
'thu' => '四',
'fri' => '五',
'sat' => '六',
'january' => '一月',
'february' => '二月',
'march' => '三月',
'april' => '四月',
'may_long' => '五月',
'june' => '六月',
'july' => '七月',
'august' => '八月',
'september' => '九月',
'october' => '十月',
'november' => '十一月',
'december' => '十二月',
'january-gen' => '一月',
'february-gen' => '二月',
'march-gen' => '三月',
'april-gen' => '四月',
'may-gen' => '五月',
'june-gen' => '六月',
'july-gen' => '七月',
'august-gen' => '八月',
'september-gen' => '九月',
'october-gen' => '十月',
'november-gen' => '十一月',
'december-gen' => '十二月',
'jan' => '1月',
'feb' => '2月',
'mar' => '3月',
'apr' => '4月',
'may' => '5月',
'jun' => '6月',
'jul' => '7月',
'aug' => '8月',
'sep' => '9月',
'oct' => '10月',
'nov' => '11月',
'dec' => '12月',
'january-date' => '1月$1日',
'february-date' => '2月$1日',
'march-date' => '三月$1日',
'april-date' => '四月$1日',
'may-date' => '五月$1日',
'june-date' => '六月$1日',
'july-date' => '七月$1日',
'august-date' => '八月$1日',
'september-date' => '九月$1日',
'october-date' => '十月$1日',
'november-date' => '十一月$1日',
'december-date' => '十二月$1日',

# Categories related messages
'pagecategories' => '$1個分類',
'category_header' => '分類中的頁面「$1」',
'subcategories' => '子分類',
'category-media-header' => '「$1」分類中的媒體',
'category-empty' => "''這個分類中尚未包含任何頁面或媒體。''",
'hidden-categories' => '$1個隱藏分類',
'hidden-category-category' => '隱藏分類',
'category-subcat-count' => '{{PLURAL:$2|這個分類中只有以下的子分類。|這個分類中有以下的$1個子分類，共有$2個子分類。}}',
'category-subcat-count-limited' => '這個分類下有$1個子分類。',
'category-article-count' => '{{PLURAL:$2|這個分類中只有以下的頁面。|這個分類中有以下的$1個頁面，共有$2個頁面。}}',
'category-article-count-limited' => '這個分類下有$1個頁面。',
'category-file-count' => '{{PLURAL:$2|這個分類中只有以下的檔案。|這個分類中有以下的$1個檔案，共有$2個檔案。}}',
'category-file-count-limited' => '這個分類下有$1個檔案。',
'listingcontinuesabbrev' => '續',
'index-category' => '已索引的頁面',
'noindex-category' => '未索引的頁面',
'broken-file-category' => '包含損壞檔案連結的頁面',

'about' => '關於',
'article' => '內容頁面',
'newwindow' => '（於新視窗開啟）',
'cancel' => '取消',
'moredotdotdot' => '更多...',
'morenotlisted' => '這不是完整的列表。',
'mytalk' => '討論頁',
'anontalk' => '此IP位址的討論頁',
'navigation' => '導航',
'and' => '和',

# Cologne Blue skin
'qbfind' => '搜尋',
'qbbrowse' => '瀏覽',
'qbedit' => '編輯',
'qbpageoptions' => '本頁',
'qbmyoptions' => '我的用戶頁面',
'faq' => '常見問題',
'faqpage' => 'Project:常見問題',

# Vector skin
'vector-action-addsection' => '新增主題',
'vector-action-delete' => '刪除',
'vector-action-undelete' => '恢復',
'vector-action-unprotect' => '修改保護狀態',
'vector-view-history' => '歷史',
'vector-view-view' => '閱覽',
'vector-view-viewsource' => '查看原始碼',
'variants' => '變體',

'navigation-heading' => '導航菜單',
'returnto' => '回到$1。',
'tagline' => '從 {{SITENAME}}',
'help' => '幫助',
'search' => '搜尋',
'searcharticle' => '提交',
'updatedmarker' => '自從我上次查看後已更新',
'printableversion' => '可打印版',
'permalink' => '永久連接',
'print' => '打印',
'undeletethispage' => '恢復本頁',
'undelete_short' => '恢復{{PLURAL:$1|1次編輯|$1次編輯}}',
'viewdeleted_short' => '查看{{PLURAL:$1|1次已刪的編輯|$1次已刪的編輯}}',
'protect_change' => '修改',
'unprotect' => '修改保護狀態',
'unprotectthispage' => '修改本頁的保護狀態',
'talkpage' => '對本頁進行討論',
'talkpagelinktext' => '討論頁',
'specialpage' => '特殊頁面',
'postcomment' => '新章節',
'articlepage' => '查看內容頁面',
'views' => '外觀',
'userpage' => '查看用戶頁',
'projectpage' => '查看計劃頁面',
'imagepage' => '查看檔案頁面',
'mediawikipage' => '查看訊息頁面',
'templatepage' => '查看模板頁',
'viewhelppage' => '查看幫助頁面',
'categorypage' => '查看分類頁面',
'viewtalkpage' => '查看討論頁',
'redirectpagesub' => '重定向頁',
'lastmodifiedat' => '本頁最後更改於$1$2。',
'viewcount' => '本頁的瀏覽次數為{{PLURAL:$1|1次|$1次}}。',
'protectedpage' => '受保護的頁面',
'jumpto' => '跳到：',
'jumptonavigation' => '導航',
'jumptosearch' => '搜尋',
'view-pool-error' => '抱歉，伺服器現時超出負荷。
有太多用戶想要查看本頁。
請先稍候片刻才再嘗試查看本頁。

$1',
'pool-timeout' => '等待鎖時超出了時限',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage).
'aboutsite' => '關於{{SITENAME}}',
'aboutpage' => 'Project:關於我們',
'copyright' => '除非另外註明，否則內容以$1提供。',
'copyrightpage' => '{{ns:project}}:版權',
'edithelp' => '編輯方面的幫助',
'mainpage' => '主頁',
'mainpage-description' => '主頁',
'portal' => '社群區入口',
'portal-url' => 'Project:社群入口',
'privacy' => '私隱政策',
'privacypage' => 'Project:私隱政策',

'badaccess-group0' => '您無權執行您所提出的行動。',
'badaccess-groups' => '您所提出的行動只有{{PLURAL:$2|此群組|以下群組之一}}才可執行：$1。',

'versionrequired' => '需要$1版本的MediaWiki',
'versionrequiredtext' => '要使用本頁的話需要$1版本的MediaWiki。
請見[[Special:版本|版本頁面]]。',

'retrievedfrom' => '擷取自$1',
'red-link-title' => '$1 (頁面不存在)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-special' => '特殊頁面',

# Login and logout pages
'nav-login-createaccount' => '登入／創造帳戶',
'userlogin' => '登入／創造帳戶',

# Edit pages
'editing' => '正在編輯「$1」',

# Revision deletion
'rev-deleted-comment' => '（註釋已除）',
'rev-deleted-event' => '（日誌已除）',
'revdelete-suppress-text' => "壓制'''只'''應用於以下的情況:
* 不合適的個人資料
*: ''地址、電話號碼、身份證號碼等。''",

# Diffs
'editundo' => '撤銷',

# Preferences page
'prefs-help-gender' => '可選：用於軟件中的性別指定。此項資料將會被公開。',

# Groups
'group-bot' => '機械人',

'group-bot-member' => '機械人',

'grouppage-bot' => '{{ns:project}}:機械人',

# Recent changes
'recentchanges-label-bot' => '這次編輯是由機械人進行',
'rcshowhidebots' => '$1機械人的編輯',

# Special:ActiveUsers
'activeusers-hidebots' => '隱藏機械人',

# Block/unblock
'contribslink' => '貢獻',

# Tooltip help for the actions
'tooltip-search' => '搜尋 {{SITENAME}}',
'tooltip-search-go' => '若是真有其頁，則進入相同名字的頁面',
'tooltip-search-fulltext' => '在此頁面內搜尋此文字',
'tooltip-n-mainpage' => '回到首頁',
'tooltip-n-mainpage-description' => '回到首頁',
'tooltip-n-randompage' => '跳到一個隨機抽取的頁面',
'tooltip-t-print' => '這個頁面的可打印版本',
'interlanguage-link-title' => '$1 – $2',

# Special:NewFiles
'showhidebots' => '($1機械人)',

/*
Short names for language variants used for language conversion links.
Variants for Chinese language
*/
'variantname-zh-hant' => '‪繁體中文',

# Special:SpecialPages
'specialpages' => '特殊頁面',

);
