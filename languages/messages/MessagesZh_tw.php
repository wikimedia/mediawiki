<?php
/**
  * @addtogroup Language
  */

$fallback = 'zh-cn';

$namespaceNames = array(
	NS_MEDIA            => 'Media',
	NS_SPECIAL          => 'Special',
	NS_MAIN             => '',
	NS_TALK             => 'Talk',
	NS_USER             => 'User',
	NS_USER_TALK        => 'User_talk',
	# NS_PROJECT set by $wgMetaNamespace
	NS_PROJECT_TALK     => '$1_talk',
	NS_IMAGE            => 'Image',
	NS_IMAGE_TALK       => 'Image_talk',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki_talk',
	NS_TEMPLATE         => 'Template',
	NS_TEMPLATE_TALK    => 'Template_talk',
	NS_HELP             => 'Help',
	NS_HELP_TALK        => 'Help_talk',
	NS_CATEGORY         => 'Category',
	NS_CATEGORY_TALK    => 'Category_talk'
);

$namespaceAliases = array(
	"媒體" => NS_MEDIA,
	"特殊" => NS_SPECIAL,
	"對話" => NS_TALK, 
	"用戶" => NS_USER,
	"用戶對話" => NS_USER_TALK,
	# This has never worked so it's unlikely to annoy anyone if I disable it -- TS
	#"{{SITENAME}}_對話" => NS_PROJECT_TALK
	"圖像" => NS_IMAGE,
	"圖像對話" => NS_IMAGE_TALK,
	"樣板" => NS_TEMPLATE,
	"樣板討論" => NS_TEMPLATE_TALK,
	"幫助" => NS_HELP,
	"幫助討論" => NS_HELP_TALK,
	"分類" => NS_CATEGORY,
	"分類討論" => NS_CATEGORY_TALK,
);

$skinNames = array(
        "標準",/* "Standard" */
	"懷舊",/* "Nostalgia" */
	"科隆香水藍" /* "Cologne Blue" */
);

$bookstoreList = array(
	"博客來書店" => "http://www.books.com.tw/exep/openfind_book_keyword.php?cat1=4&key1=$1",
	"三民書店" => "http://www.sanmin.com.tw/page-qsearch.asp?ct=search_isbn&qu=$1",
	"天下書店" => "http://www.cwbook.com.tw/cw/TS.jsp?schType=product.isbn&schStr=$1",
	"新絲書店" => "http://www.silkbook.com/function/Search_List_Book.asp?item=5&text=$1"
);


$messages = array(
# User preference toggles
'tog-underline'               => '下劃鏈結',
'tog-highlightbroken'         => '毀壞的鏈結格式<a href="" class="new">像這樣</a> (或者像這個<a href="" class="internal">?</a>)',
'tog-justify'                 => '段落對齊',
'tog-hideminor'               => '最近更改中隱小修改',
'tog-usenewrc'                => '進階最近更改（JavaScript）',
'tog-numberheadings'          => '標題自動編號',
'tog-showtoolbar'             => '顯示編輯工具欄',
'tog-editondblclick'          => '雙擊編輯頁面（Javascript）',
'tog-editsection'             => '允許通過點擊[編輯]鏈結編輯段落',
'tog-editsectiononrightclick' => '允許右擊標題編輯段落(JavaScript)',
'tog-showtoc'                 => '顯示目錄<br />(針對一頁超過3個標題的文章)',
'tog-rememberpassword'        => '下次登入記住密碼',
'tog-editwidth'               => '編輯欄位寬度',
'tog-watchcreations'          => '將我創建的頁面添加到我的監視列表中',
'tog-watchdefault'            => '將我更改的頁面添加到我的監視列表中',
'tog-watchmoves'              => '將我移動的頁面加入我的監視列表',
'tog-minordefault'            => '細微編輯為默認設置',
'tog-previewontop'            => '在編輯框上方顯示預覽',
'tog-previewonfirst'          => '第一次編輯時顯示原文內容的預覽',
'tog-nocache'                 => '停用頁面快取',
'tog-shownumberswatching'     => '顯示監視用戶的數目',
'tog-fancysig'                => '使用原始簽名 (不產生自動連結)',
'tog-showjumplinks'           => '啟用「跳轉到」訪問連結',
'tog-uselivepreview'          => '使用實時預覽（JavaScript）',
'tog-watchlisthideown'        => '監視列表中隱藏我的編輯',
'tog-watchlisthidebots'       => '監視列表中隱藏機器人的編輯',
'tog-watchlisthideminor'      => '監視列表中隱藏小修改',
'tog-ccmeonemails'            => '當我寄電子郵件給其他用戶時，也寄一份複本到我的信箱。',
'tog-diffonly'                => '在比較兩個修訂版本差異時不顯示條目內容',

'underline-always'  => '有',
'underline-never'   => '無',
'underline-default' => '瀏覽器預設',

'skinpreview' => '(預覽)',

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

# Bits of text used by many pages
'categories'      => '頁面分類',
'pagecategories'  => '頁面分類',
'category_header' => '類別「$1」中的條目',
'subcategories'   => '附分類',

'about'         => '關於',
'article'       => '條目',
'cancel'        => '取消',
'qbfind'        => '尋找',
'qbbrowse'      => '瀏覽',
'qbedit'        => '編輯',
'qbpageoptions' => '頁面選項',
'qbpageinfo'    => '頁面訊息',
'qbmyoptions'   => '我的選項',
'mypage'        => '我的頁面',
'mytalk'        => '我的對話頁',
'anontalk'      => '該IP的對話頁',
'navigation'    => '導航',

'errorpagetitle'    => '錯誤',
'returnto'          => '返回到$1.',
'help'              => '幫助',
'search'            => '搜索',
'searchbutton'      => '搜索',
'go'                => '進入',
'searcharticle'     => '進入',
'history'           => '較早版本',
'history_short'     => '沿革',
'printableversion'  => '可列印版',
'edit'              => '編輯',
'editthispage'      => '編輯本頁',
'delete'            => '刪除',
'deletethispage'    => '刪除本頁',
'undelete_short'    => '恢復以前$1個版本',
'protect'           => '封鎖',
'protectthispage'   => '保護本頁',
'unprotect'         => '解除保護',
'unprotectthispage' => '解除保護',
'newpage'           => '新頁面',
'talkpage'          => '討論本頁',
'specialpage'       => '特殊頁面',
'personaltools'     => '個人工具',
'postcomment'       => '發表評論',
'articlepage'       => '查看文章',
'talk'              => '討論',
'toolbox'           => '工具',
'userpage'          => '查看用戶頁',
'projectpage'       => '查看計劃頁面',
'imagepage'         => '查看圖像頁面',
'templatepage'      => '檢視模板頁面',
'viewhelppage'      => '檢視說明頁面',
'viewtalkpage'      => '查看討論',
'otherlanguages'    => '其它語言',
'redirectedfrom'    => '(重定向自$1)',
'lastmodifiedat'    => '最後更改$2, $1.', # $1 date, $2 time
'viewcount'         => '本頁面已經被瀏覽$1次。',
'protectedpage'     => '被保護頁',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'         => '關於{{SITENAME}}',
'aboutpage'         => '{{ns:project}}:關於',
'bugreports'        => '錯誤報告',
'bugreportspage'    => '{{ns:project}}:錯誤報告',
'copyright'         => '本站所有內容允許以下方式利用: $1',
'copyrightpagename' => '{{SITENAME}}版權',
'copyrightpage'     => '{{ns:project}}:版權訊息',
'currentevents'     => '新聞動態',
'disclaimers'       => '免責聲明',
'edithelp'          => '編輯幫助',
'edithelppage'      => '{{ns:project}}:如何編輯頁面',
'faq'               => '常見問題解答',
'faqpage'           => '{{ns:project}}:常見問題解答',
'helppage'          => '{{ns:project}}:幫助',
'mainpage'          => '首頁',
'portal'            => '社區',
'sitesupport'       => '贊助',
'sitesupport-url'   => '{{ns:project}}:贊助',

'badaccess'        => '您沒有此權限',
'badaccess-group1' => '對不起！您執行的操作只能由 $1 使用。',
'badaccess-group2' => '對不起！您執行的操作只能由 $1 使用。',
'badaccess-groups' => '對不起！您執行的操作只能由 $1 這個群組的成員使用。',

'versionrequired'     => '需要MediaWiki $1 版',
'versionrequiredtext' => '本頁需要MediaWiki $1 版。請參閱[[Special:Version]]。',

'ok'                 => 'OK',
'retrievedfrom'      => '取自"$1"',
'youhavenewmessages' => '您有$1（$2）。',
'newmessageslink'    => '新訊息',
'editsection'        => '編輯',
'editold'            => '編輯',
'toc'                => '目錄',
'showtoc'            => '顯示',
'hidetoc'            => '隱藏',
'thisisdeleted'      => '查看或恢復$1 ?',
'viewdeleted'        => '檢視$1',

# Short words for each namespace, by default used in the 'article' tab in monobook
'nstab-main'      => '條目',
'nstab-user'      => '用戶頁面',
'nstab-special'   => '特殊',
'nstab-project'   => '關於',
'nstab-image'     => '圖像',
'nstab-mediawiki' => '介面',
'nstab-template'  => '模板',
'nstab-help'      => '幫助',
'nstab-category'  => '分類',

# Main script and global functions
'nosuchaction'      => '沒有這個命令',
'nosuchactiontext'  => 'URL請求的命令無法被{{SITENAME}}軟體識別。',
'nosuchspecialpage' => '沒有這個特殊頁。',
'nospecialpagetext' => '您請求的頁面無法被{{SITENAME}}軟體識別。',

# General errors
'error'                => '錯誤',
'databaseerror'        => '資料庫錯誤',
'dberrortext'          => '資料庫指令語法錯誤。
這可能是由於非法搜索指令所引起的(見$5),
也可能是由於軟體自身的錯誤所引起。
最後一次資料庫指令是：
<blockquote><tt>$1</tt></blockquote>
來自於函數 "<tt>$2</tt>"。
MySQL返回錯誤 "<tt>$3: $4</tt>"。',
'noconnect'            => '無法在$1上連接資料庫',
'nodb'                 => '無法選擇資料庫 $1',
'readonly'             => '資料庫禁止訪問',
'enterlockreason'      => '請輸入禁止訪問原因, 包括估計重新開放的時間',
'readonlytext'         => '{{SITENAME}}資料庫目前禁止輸入新內容及更改，
這很可能是由於資料庫正在維修，之後即可恢復。
管理員有如下解釋:
<p>$1</p>',
'missingarticle'       => '資料庫找不到文字"$1"。

<p>通常這是由於修訂歷史頁上過時的連結到已經被刪除的頁面所導致的。</p>

<p>如果情況不是這樣，您可能找到了軟體內的一個臭蟲。
請記錄下URL地址，並向管理員報告。</p>',
'internalerror'        => '內部錯誤',
'filecopyerror'        => '無法複製文件"$1"到"$2"。',
'filerenameerror'      => '無法重命名文件"$1"到"$2"。',
'filedeleteerror'      => '無法刪除文件"$1"。',
'filenotfound'         => '找不到文件"$1"。',
'unexpected'           => '不正常值："$1"="$2"。',
'formerror'            => '錯誤：無法提交表單',
'badarticleerror'      => '無法在本頁上進行此項操作。',
'cannotdelete'         => '無法刪除選定的頁面或圖像（它可能已經被其他人刪除了）。',
'badtitle'             => '錯誤的標題',
'badtitletext'         => '所請求頁面的標題是無效的、不存在，跨語言或跨wiki連結的標題錯誤。',
'perfdisabled'         => '抱歉！由於此項操作有可能造成資料庫癱瘓，目前暫時無法使用。',
'perfdisabledsub'      => '這裡是自$1的複製版本：', # obsolete?
'wrong_wfQuery_params' => '錯誤的參數導致wfQuery()<br />函數：$1<br />查詢：$2',
'viewsource'           => '原始檔',
'viewsourcefor'        => '： $1',
'viewsourcetext'       => '你可以檢視並複製本頁面的原始碼。',
'sqlhidden'            => '(隱藏SQL查詢)',

# Login and logout pages
'logouttitle'                => '用戶退出',
'logouttext'                 => '您現在已經退出。
您可以繼續以匿名方式使用{{SITENAME}}，或再次以相同或不同用戶身份登入。',
'welcomecreation'            => '<h2>歡迎，$1!</h2><p>您的帳號已經建立，不要忘記設置{{SITENAME}}個人參數。</p>',
'loginpagetitle'             => '用戶登入',
'yourname'                   => '您的用戶名',
'yourpassword'               => '您的密碼',
'yourpasswordagain'          => '再次輸入密碼',
'remembermypassword'         => '下次登入記住密碼。',
'yourdomainname'             => '您的網域',
'loginproblem'               => '<b>登入有問題。</b><br />再試一次！',
'alreadyloggedin'            => '<strong>用戶$1，您已經登入了!</strong><br />',
'login'                      => '登入',
'loginprompt'                => '您必須允許瀏覽器紀錄Cookie才能成功登入 {{SITENAME}} 並順利進行操作',
'userlogin'                  => '登入／建立新帳號',
'logout'                     => '登出',
'userlogout'                 => '用戶登出',
'createaccount'              => '建立新帳號',
'createaccountmail'          => '通過e-Mail',
'badretype'                  => '你所輸入的密碼並不相同。',
'userexists'                 => '您所輸入的用戶名稱已經存在，請另選一個。',
'youremail'                  => '您的電子郵件*',
'username'                   => '帳號名稱：',
'uid'                        => '用戶編號：',
'yourrealname'               => '真實姓名*',
'yourlanguage'               => '介面語言',
'yourvariant'                => '字體變換',
'yournick'                   => '綽號（簽名時用）',
'prefs-help-realname'        => '*<strong>真實姓名</strong>（可選）：用以對您的貢獻署名。<br />',
'loginerror'                 => '登入錯誤',
'prefs-help-email'           => '*<strong>電子郵件</strong>（可選）：讓他人通過網站在不知道您的電子郵件地址的情況下通過電子郵件與您聯絡，以及通過電子郵件取得遺忘的密碼。',
'noname'                     => '你沒有輸入一個有效的用戶名。',
'loginsuccesstitle'          => '登入成功',
'loginsuccess'               => '你現在以 "$1"的身份登入{{SITENAME}}。',
'nosuchuser'                 => '找不到用戶 "$1"。
檢查您的拼寫，或者用下面的表格建立一個新帳號。',
'wrongpassword'              => '您輸入的密碼錯誤，請再試一次。',
'wrongpasswordempty'         => '沒有輸入密碼！請重試。',
'mailmypassword'             => '將新密碼寄給我',
'passwordremindertitle'      => '{{SITENAME}}密碼提醒',
'passwordremindertext'       => '有人（可能是您，來自IP地址$1)要求我們將新的{{SITENAME}}登入密碼寄給你。
用戶 "$2" 的密碼現在是 "$3"。
請立即登入並更改密碼。',
'noemail'                    => '用戶"$1"沒有登記電子郵件地址。',
'passwordsent'               => '用戶"$1"的新密碼已經寄往所登記的電子郵件地址。
請在收到後再登入。',
'blocked-mailpassword'       => '由於這個用戶被封禁，我們暫時禁止您請求申請新密碼。造成不便敬請見諒',
'throttled-mailpassword'     => '密碼提醒已經在前$1小時內發送。為防止濫用，限定在$1小時內僅發送一次密碼提醒。',
'acct_creation_throttle_hit' => '對不起，您已經註冊了$1賬號。你不能再註冊了。',

# Edit page toolbar
'bold_sample' => '粗體文字',
'bold_tip'    => '粗體文字',
'sig_tip'     => '帶有時間的簽名',

# Edit pages
'summary'                   => '簡述',
'subject'                   => '主題',
'minoredit'                 => '這是一個小修改',
'watchthis'                 => '監視本頁',
'savearticle'               => '保存本頁',
'preview'                   => '預覽',
'showpreview'               => '顯示預覽',
'showlivepreview'           => '即時預覽',
'showdiff'                  => '顯示差異',
'anoneditwarning'           => '<b>注意:<br />您現在沒有[[Special:Userlogin|登入]]。您的[[IP地址]]將被記錄並顯示在本頁的<span class="plainlinks">[{{fullurl:{{FULLPAGENAME}}|action=history}} 編輯歷史]中',
'summary-preview'           => '編輯摘要預覽',
'subject-preview'           => '主題/標題預覽',
'blockedtitle'              => '用戶被封',
'blockedtext'               => "您的用戶名或IP地址已被$1封。
理由是：<br />'''$2'''<p>您可以與$1向其他任何[[{{ns:project}}:管理員|管理員]]詢問。</p>",
'whitelistedittitle'        => '登入後才可編輯',
'whitelistedittext'         => '您必須先[[Special:Userlogin|登入]]才可編輯頁面。',
'whitelistreadtitle'        => '登入後才可閱讀',
'whitelistreadtext'         => '您必須先[[Special:Userlogin|登入]]才可閱讀頁面。',
'whitelistacctitle'         => '您被禁止建立帳號',
'whitelistacctext'          => '在本Wiki中建立帳號您必須先[[Special:Userlogin|登入]]並擁有相關許可權。',
'accmailtitle'              => '密碼寄出',
'accmailtext'               => "'$1'的密碼已經寄到$2。",
'newarticle'                => '（新）',
'newarticletext'            => '您從一個連結進入了一個並不存在的頁面。
要創建該頁面，請在下面的編輯框中輸入內容（詳情參見[[{{ns:project}}:幫助|幫助頁面]]）。
如果您不小心來到本頁面，直接點擊您瀏覽器中的「返回」按鈕。',
'anontalkpagetext'          => "---- ''這是一個還未建立帳號的匿名用戶的對話頁。我們因此只能用IP地址來與他／她聯絡。該IP地址可能由幾名用戶共享。如果您是一名匿名用戶並認為本頁上的評語與您無關，請[[Special:Userlogin|創建新帳號或登入]]以避免在未來於其他匿名用戶混淆。''",
'noarticletext'             => '（本頁目前沒有內容）',
'clearyourcache'            => "'''注意：''' 保存設置後，要清掉瀏覽器的緩存才能生效：'''Mozilla / Firefox:''' ''Ctrl-Shift-R'', '''Internet Explorer:''' ''Ctrl-F5'', '''Safari:''' ''Cmd-Shift-R'', '''Konqueror:''' ''F5''。",
'userjspreview'             => "'''請注意您現在只是在測試/預覽您定義的JavaScript腳本，內容還沒有保存！'''",
'updated'                   => '（已更新）',
'note'                      => '<strong>注意：</strong>',
'previewnote'               => '請記住這只是預覽，內容還未保存！',
'previewconflict'           => '這個預覽顯示了上面文字編輯區中的內容。它將在你選擇保存後出現。',
'session_fail_preview'      => '<strong>很抱歉！由於部份資料遺失，我們無法處理您的編輯。請再試一次，如果仍然失敗，請登出後重新登入。</strong>',
'session_fail_preview_html' => '<strong>很抱歉！部份資料已遺失，我們無法處理您的編輯。</strong><strong>如果這個編輯過程沒有問題，請再試一次。如果仍然有問題，請登出後再重新登入一次。</strong>',
'editing'                   => '正在編輯$1',
'editinguser'               => '正在編輯$1',
'editingsection'            => '正在編輯$1 (段落)',
'editingcomment'            => '正在編輯$1 (評論)',
'editconflict'              => '編輯衝突：$1',
'explainconflict'           => '有人在你開始編輯後更改了頁面。
上面的文字框內顯示的是目前本頁的內容。
你所做的修改顯示在下面的文字框中。
你應當將你所做的修改加入現有的內容中。
<b>只有</b>在上面文字框中的內容會在你點擊"保存頁面"後被保存。<br />',
'yourtext'                  => '您的文字',
'storedversion'             => '已保存版本',
'editingold'                => '<strong>警告：你正在編輯的是本頁的舊版本。
如果你保存它的話，在本版本之後的任何修改都會丟失。</strong>',
'yourdiff'                  => '差別',
'longpagewarning'           => '<strong>警告：本頁長度達$1KB；一些瀏覽器將無法編輯長過32KB文章。請考慮將本文切割成幾個小段落。</strong>',
'readonlywarning'           => '<strong>警告：資料庫被鎖以進行維護，所以您目前將無法保存您的修改。您或許希望先將本斷文字複製並保存到文本文件，然後等一會兒再修改。</strong>',
'protectedpagewarning'      => '<strong>警告：本頁已經被保護，只有擁有管理員許可權的用戶才可修改。請確認您遵守
[[Project:Protected_page_guidelines|保護頁面守則]].</strong>',
'semiprotectedpagewarning'  => "'''注意：''' 本頁面被鎖定，僅限註冊用戶編輯。",
'templatesused'             => '在這個頁面上使用的模板有：',
'templatesusedpreview'      => '此次預覽中使用的模板有：',
'templatesusedsection'      => '在這個段落上使用的模板有：',
'template-protected'        => '已保護',
'template-semiprotected'    => '已半保護',

# "Undo" feature
'undo-success' => '<b>注意：</b>該編輯可以被撤銷。請檢查下面的對照表以確認你要撤銷的內容，然後按下「{{int:savearticle}}」完成撤銷編輯。',
'undo-failure' => '由於兩次之間的編輯不一致，無法撤銷到此次編輯',
'undo-summary' => '取消由[[Special:Contributions/$2|$2]] ([[User talk:$2|對話]])所作出的修訂 $1',

# History pages
'revhistory'      => '修訂歷史',
'viewpagelogs'    => '查詢這個頁面的日誌',
'nohistory'       => '沒有本頁的修訂記錄。',
'revnotfound'     => '沒有找到修訂記錄',
'revnotfoundtext' => '您請求的更早版本的修訂記錄沒有找到。
請檢查您請求本頁面用的URL是否正確。',
'loadhist'        => '載入頁面修訂歷史',
'currentrev'      => '當前修訂版本',
'revisionasof'    => '$1的修訂版本',
'cur'             => '當前',
'next'            => '後繼',
'last'            => '先前',
'orig'            => '初始',
'histlegend'      => '說明：(當前)指與當前修訂版本比較；(先前)指與前一個修訂版本比較，小 指小修改。',

# Diffs
'difference'                => '（修訂版本間差異）',
'loadingrev'                => '載入修訂版本比較',
'lineno'                    => '第$1行：',
'editcurrent'               => '編輯本頁的當前修訂版本',
'selectnewerversionfordiff' => '選擇一個更早的版本做比對。',
'selectolderversionfordiff' => '選擇一個更早的版本做比對。',

# Search results
'searchresults'         => '搜索結果',
'searchresulttext'      => '有關搜索{{SITENAME}}的更多詳情,參見$1。',
'searchsubtitle'        => '查詢"[[:$1]]"',
'searchsubtitleinvalid' => '查詢"$1"',
'badquery'              => '搜索查詢不正確',
'badquerytext'          => '我們無法處理您的查詢。
這可能是由於您試圖搜索一個短於3個字母的外文單詞，
或者您錯誤地輸入了搜索項，例如"汽車和和火車"。
請再嘗試一個新的搜索項。',
'matchtotals'           => '搜索項"$1"與$2條文章的題目相符，和$3條文章相符。',
'noexactmatch'          => '沒有文章與搜索項完全匹配，請嘗試完整文字搜索。',
'titlematches'          => '文章題目相符',
'notitlematches'        => '沒有找到匹配文章題目',
'textmatches'           => '文章內容相符',
'notextmatches'         => '沒有文章內容匹配',
'prevn'                 => '先前$1',
'nextn'                 => '之後$1',
'viewprevnext'          => '查看 ($1) ($2) ($3).',
'showingresults'        => '下面顯示<b>$1</b>條結果，從第<b>$2</b>條開始',
'nonefound'             => '<strong>注意：</strong>失敗的搜索往往是由於試圖搜索諸如「的」或「和」之類的常見字所引起。',
'powersearch'           => '搜索',
'powersearchtext'       => '
搜索名字空間：<br />$1<br />$2列出重定向頁面；搜索$3 $9',
'searchdisabled'        => '<p>{{SITENAME}}內部搜索功能由於高峰時段伺服器超載而停止使用。
您可以暫時通過
<a href="http://google.com/">google</a>搜索{{SITENAME}}。
謝謝您的耐心。</p>',

# Preferences page
'preferences'              => '參數設置',
'mypreferences'            => '我的參數設置',
'prefsnologin'             => '還未登入',
'prefsnologintext'         => '您必須先[[Special:Userlogin|登入]]才能設置個人參數。',
'prefsreset'               => '參數重新設置。',
'qbsettings'               => '快速導航條設置',
'qbsettings-none'          => '無',
'qbsettings-fixedleft'     => '左側固定',
'qbsettings-fixedright'    => '右側固定',
'qbsettings-floatingleft'  => '左側漂移',
'qbsettings-floatingright' => '右側漂移',
'changepassword'           => '更改密碼',
'skin'                     => '面板',
'math'                     => '數學顯示',
'math_failure'             => '無法解析',
'math_unknown_error'       => '未知錯誤',
'math_unknown_function'    => '未知函數',
'math_lexing_error'        => '句法錯誤',
'math_syntax_error'        => '語法錯誤',
'prefs-personal'           => '用戶數據',
'prefs-rc'                 => '最近更新',
'prefs-misc'               => '雜項',
'saveprefs'                => '保存參數設置',
'resetprefs'               => '重設參數',
'oldpassword'              => '舊密碼',
'newpassword'              => '新密碼',
'retypenew'                => '重複新密碼',
'textboxsize'              => '文字框尺寸',
'rows'                     => '行',
'columns'                  => '列',
'searchresultshead'        => '搜索結果設定',
'resultsperpage'           => '每頁顯示連結數',
'contextlines'             => '每連結行數',
'contextchars'             => '每行字數',
'stubthreshold'            => '短條目顯示基本限制',
'recentchangescount'       => '最近更改頁行數',
'savedprefs'               => '您的個人參數設置已經保存。',
'timezonelegend'           => '時區',
'timezonetext'             => '輸入當地時間與伺服器時間的時差。',
'localtime'                => '當地時間',
'timezoneoffset'           => '時差',
'servertime'               => '目前伺服器時間',
'allowemail'               => '允許其他用戶寄發電子郵件給您',
'defaultns'                => '預設的搜尋範圍',
'default'                  => '預設',

# User rights
'userrights-lookup-user'     => '管理用戶群組',
'userrights-user-editname'   => '請輸入您要更動的用戶名稱：',
'userrights-editusergroup'   => '編輯用戶群組',
'saveusergroups'             => '保存用戶群組',
'userrights-groupsmember'    => '屬於：',
'userrights-groupsavailable' => '可用群組：',
'userrights-groupshelp'      => '請選擇您想讓用戶加入或退出的群組。沒有選擇的群組將不會被改變。您也可以用"CTRL + 左擊滑鼠"複選或取消已經選擇的群組。',

# User rights log
'rightslog'      => '用戶權限日誌',
'rightslogtext'  => '以下記錄了用戶權限的更改記錄。',
'rightslogentry' => '將 $1 的權限從 $2 改為 $3',
'rightsnone'     => '(無)',

# Recent changes
'recentchanges'     => '最近更改',
'recentchangestext' => '本頁跟蹤{{SITENAME}}內最新的更改。
[[{{ns:project}}:歡迎，新來者|歡迎，新來者]]！
請參見這些頁面：[[{{ns:project}}:常見問題解答|{{SITENAME}}常見問題解答]]、
[[{{ns:project}}:守則與指導|{{SITENAME}}守則]]
（特別是[[{{ns:project}}:命名常規|命名常規]]、
[[{{ns:project}}:中性的觀點|中立觀點]]）
和[[{{ns:project}}:最常見失禮行為|最常見失禮行為]]。

如果您希望{{SITENAME}}成功，那麼請您不要增加受其它[[{{ns:project}}:版權訊息|版權]]
限制的材料，這一點將非常重要。相關的法律責任會傷害本項工程，所以請不要這樣做。
此外請參見',
'rcnote'            => '下面是最近<strong>$2</strong>天內最新的<strong>$1</strong>次改動。',
'rcnotefrom'        => '下面是自<b>$2</b>（最多顯示<b>$1</b>）。',
'rclistfrom'        => '顯示自$1以來的新更改',
'rclinks'           => '顯示最近 $2 天內最新的 $1 次改動。<br />$3',
'diff'              => '差異',
'hist'              => '歷史',
'hide'              => '隱藏',
'show'              => '顯示',
'minoreditletter'   => '小',
'newpageletter'     => '新',

# Recent changes linked
'recentchangeslinked' => '鏈出更改',

# Upload
'upload'            => '上傳檔案',
'uploadbtn'         => '上傳檔案',
'reupload'          => '重新上載',
'reuploaddesc'      => '返回上載表單。',
'uploadnologin'     => '未登入',
'uploadnologintext' => '您必須先[[Special:Userlogin|登入]]
才能上載文件。',
'uploaderror'       => '上載錯誤',
'uploadtext'        => '<strong>停止！</strong>在您上載之前，請先閱讀並遵守{{SITENAME}}[[Project:Image use policy|圖像使用守則]]。
<p>如果您要查看或搜索之前上載的圖像，
請到[[Special:Imagelist|已上載圖像列表]].
所有上載與刪除行為都被記錄在[[Project:上載紀錄]]內。</p>
<p>使用下面的表單來上載用在條目內新的圖像文件。
在絕大多數瀏覽器內，你會看到一個"瀏覽..."按鈕，點擊它後就會跳出一個打開文件對話框。
選擇一個文件後文件名將出現在按鈕旁邊的文字框中。
您也必須點擊旁邊的複選框確認您所上載的文件並沒有違反相關版權法律。
點擊"上載" 按鈕完成上載程序。
如果您使用的是較慢的網絡連接的話那麼這個上載過程會需要一些時間。</p>
<p>我們建議照相圖片使用JPEG格式，繪圖及其他圖標圖像使用PNG格式，音像則使用OGG格式。
請使用具有描述性的語言來命名您的文件以避免混亂。
要在文章中加入圖像，使用以下形式的連接：
<b><nowiki>[[</nowiki>{{ns:image}}:檔案.jpg]]</b>或者<b><nowiki>[[</nowiki>{{ns:image}}:檔案.png|解釋文字]]</b>
或<b><nowiki>[[</nowiki>{{ns:media}}:檔案.ogg]]</b>來連接音像文件。</p>
<p>請注意在{{SITENAME}}頁面中，其他人可能會為了百科全書的利益而編輯或刪除您的上載文件，
而如果您濫用上載系統，您則有可能被禁止使用上載功能。</p>',
'uploadlog'         => '上載紀錄',
'uploadlogpage'     => '上載紀錄',
'uploadlogpagetext' => '以下是最近上載的文件的一覽表。
所有顯示的時間都是伺服器時間。
<ul>
</ul>',
'filename'          => '文件名',
'filedesc'          => '簡述',
'uploadedfiles'     => '已上載文件',
'minlength'         => '圖像名字必須至少有三個字母。',
'badfilename'       => '圖像名已被改為"$1"。',
'successfulupload'  => '上載成功',
'fileuploaded'      => '文件"$1"上載成功。
請根據連接($2)到圖像描述頁添加有關文件訊息，例如它的來源，在何時由誰創造，
以及其他任何您知道的關於改圖像的訊息。',
'uploadwarning'     => '上載警告',
'savefile'          => '保存文件',
'uploadedimage'     => '已上載"[[$1]]"',
'uploadvirus'       => '這個檔案損壞或者副檔名有錯誤。請檢查檔案再重新上傳。',
'sourcefilename'    => '來源檔案名稱',
'watchthisupload'   => '監視本檔案',

# Image list
'imagelist'                 => '圖像列表',
'imagelisttext'             => '以下是按$2排列的$1幅圖像列表。',
'getimagelist'              => '正在獲取圖像列表',
'ilsubmit'                  => '搜索',
'showlast'                  => '顯示按$2排列的最後$1幅圖像。',
'byname'                    => '名字',
'bydate'                    => '日期',
'bysize'                    => '大小',
'imgdelete'                 => '刪',
'imgdesc'                   => '述',
'imglegend'                 => '說明：(述) = 顯示/編輯圖像描述頁。',
'imghistory'                => '圖像歷史',
'revertimg'                 => '復',
'deleteimg'                 => '刪',
'deleteimgcompletely'       => '刪',
'imghistlegend'             => '題跋: (現) = 目前的圖像，(刪) = 刪除舊版本，
(復) = 恢復到舊版本。
<br /><i>點擊日期查看當天上載的圖像</i>.',
'imagelinks'                => '圖像連結',
'linkstoimage'              => '以下頁面連接到本圖像：',
'nolinkstoimage'            => '沒有頁面連接到本圖像。',
'sharedupload'              => '本檔案來自其他網站',
'shareduploadwiki'          => '請參閱$1以了解其相關資訊。',
'shareduploadwiki-linktext' => '檔案說明頁面',
'uploadnewversion-linktext' => '上傳該檔案的新版本',

# Unwatched pages
'unwatchedpages' => '未被監視的頁面',

# Unused templates
'unusedtemplates'     => '未使用的模板',
'unusedtemplatestext' => '本頁面列出模板名字空間下所有未被其他頁面使用的頁面。請在刪除這些模板前檢查其他鏈入該模板的頁面。',
'unusedtemplateswlh'  => '其他連結',

# Statistics
'statistics'             => '統計',
'sitestats'              => '站點統計',
'userstats'              => '用戶統計',
'sitestatstext'          => '資料庫中共有 <b>$1</b> 頁頁面；
其中包括對話頁、關於{{SITENAME}}的頁面、最少量的"stub"頁、重定向的頁面，
以及未達到條目質量的頁面；除此之外還有 <b>$2</b> 頁可能是合乎標準的條目。
<p>從系統軟體升級以來，全站點共有頁面瀏覽 <b>$3</b> 次，
頁面編輯 <b>$4</b> 次，每頁平均編輯 <b>$5</b> 次，
各次編輯後頁面的每個版本平均瀏覽 <b>$6</b> 次。</p>',
'userstatstext'          => "本站目前有'''$1'''個註冊用戶。其中'''$2'''人（即'''$4%'''）為管理員（參見$3）。",
'statistics-mostpopular' => '被查閱次數最多的頁面',

'disambiguations'     => '消含糊頁',
'disambiguationspage' => '{{ns:project}}:連結到消歧義的頁面',

'doubleredirects'     => '雙重重定向',
'doubleredirectstext' => '<b>請注意：</b> 這列表可能包括不正確的反應。
這通常表示在那頁面第一個#REDIRECT之下還有文字。<br />

每一行都包含到第一跟第二個重定向頁的連結，以及第二個重定向頁的第一行文字，
通常顯示的都會是「真正」的目標頁面，也就是第一個重定向頁應該指向的條目。',

'brokenredirects'        => '損壞的重定向頁',
'brokenredirectstext'    => '以下的重定向頁指向的是不存在的條目。',
'brokenredirects-edit'   => '(編輯)',
'brokenredirects-delete' => '(刪除)',

# Miscellaneous special pages
'nbytes'                  => '$1位元組',
'nlinks'                  => '$1個連結',
'nviews'                  => '$1次瀏覽',
'specialpage-empty'       => '本頁面沒有內容。',
'lonelypages'             => '孤立頁面',
'uncategorizedpages'      => '待分類頁面',
'uncategorizedcategories' => '待分類類別',
'uncategorizedimages'     => '待分類圖片',
'unusedcategories'        => '未使用的分類',
'unusedimages'            => '未使用圖像',
'popularpages'            => '熱點條目',
'wantedcategories'        => '需要的分類',
'wantedpages'             => '待撰頁面',
'allpages'                => '所有頁面',
'randompage'              => '隨機頁面',
'shortpages'              => '短條目',
'longpages'               => '長條目',
'listusers'               => '用戶列表',
'specialpages'            => '特殊頁面',
'spheading'               => '特殊頁面',
'rclsub'                  => '（從 "$1"鏈出的頁面）',
'newpages'                => '新頁面',
'ancientpages'            => '舊條目',
'intl'                    => '跨語言連結',
'move'                    => '移動',
'movethispage'            => '移動本頁',
'unusedimagestext'        => '<p>請注意其他網站（例如其他語言版本的{{SITENAME}}）
有可能直接連結本圖像，所以這裡列出的圖像有可能依然被使用。</p>',
'unusedcategoriestext'    => '以下分類雖然存在，但其中沒有任何條目或子分類。',

# Book sources
'booksources'               => '站外書源',
'booksources-search-legend' => '尋找站外書源',
'booksources-go'            => '送出',
'booksources-text'          => '以下是一份銷售新書或二手書的列表，並可能有你正尋找的書的進一步訊息：',

'categoriespagetext' => '以下列出所有的頁面分類。',
'userrights'         => '用戶權限管理',
'alphaindexline'     => '$1 到 $2',
'version'            => '版本',

# Special:Log
'specialloguserlabel'  => '用戶：',
'speciallogtitlelabel' => '標題：',
'alllogstext'          => '綜合顯示上傳、刪除、保護、查封以及站務日誌。 你可以選擇記錄類型，用戶名稱或是相關頁面來縮小查詢範圍。',

# Special:Allpages
'allarticles'       => '所有條目',
'allinnamespace'    => '所有 $1 名字空間的條目',
'allnotinnamespace' => '所有頁面 (不包括 $1 名字空間)',
'allpagesprev'      => '上一頁',
'allpagesnext'      => '下一頁',
'allpagessubmit'    => '提交',
'allpagesbadtitle'  => '您輸入的頁面標題錯誤，或是有跨語言/網站的前綴字。也可能是含有不能使用於標題的符號。',

# E-mail user
'mailnologin'     => '無電郵地址',
'mailnologintext' => '您必須先[[Special:Userlogin|登入]]
並在[[Special:Preferences|參數設置]]
中有一個有效的e-mail地址才可以電郵其他用戶。',
'emailuser'       => 'E-mail該用戶',
'emailpage'       => 'E-mail用戶',
'emailpagetext'   => '如果該用戶已經在他或她的參數設置頁中輸入了有效的e-mail地址，以下的表格將寄一個訊息給該用戶。您在您參數設置中所輸入的e-mail地址將出現在郵件「發件人」一欄中，這樣該用戶就可以回覆您。',
'usermailererror' => '目標郵件地址返回錯誤：',
'noemailtitle'    => '無e-mail地址',
'noemailtext'     => '該用戶還沒有指定一個有效的e-mail地址，
或者選擇不接受來自其他用戶的e-mail。',
'emailfrom'       => '發件人',
'emailto'         => '收件人',
'emailsubject'    => '主題',
'emailmessage'    => '訊息',
'emailsend'       => '發送',
'emailsent'       => '電子郵件已發送',
'emailsenttext'   => '您的電子郵件已經發出。',

# Watchlist
'watchlist'            => '監視列表',
'mywatchlist'          => '我的監視列表',
'watchlistfor'         => "('''$1'''的監視列表)",
'nowatchlist'          => '您的監視列表為空。',
'watchlistanontext'    => '如果您想查閱您的監視列表，請$1。',
'watchlistcount'       => "'''您的監視列表中共有 $1 個項目，包括討論頁。'''",
'watchlistcleartext'   => '確定要移除所有的項目嗎？',
'watchlistclearbutton' => '清除監視列表',
'watchlistcleardone'   => '您的監視列表已經清除完畢。總共清除 $1 個項目。',
'watchnologin'         => '未登入',
'watchnologintext'     => '您必須先[[Special:Userlogin|登入]]
才能更改您的監視列表',
'addedwatch'           => '加入到監視列表',
'addedwatchtext'       => '本頁（「$1」）已經被加入到您的[[Special:Watchlist|監視列表]]中。
未來有關它或它的對話頁的任何修改將會在本頁中列出，
[[Special:Recentchanges|最近更改列表]]中
以<b>粗體</b>形式列出。</p>

<p>如果您之後想將該頁面從監視列表中刪除，點擊導航條中的「停止監視」連結。</p>',
'removedwatch'         => '停止監視',
'removedwatchtext'     => '頁面「$1」已經從您的監視頁面中移除。',
'watch'                => '監視',
'watchthispage'        => '監視本頁',
'unwatch'              => '停止監視',
'unwatchthispage'      => '停止監視',
'notanarticle'         => '不是條目',
'watchnochange'        => '在顯示的時間段內您所監視的頁面沒有更改。',
'watchdetails'         => '* 不包含討論頁，您的監視列表共有 $1 頁。
* [[Special:Watchlist/edit|顯示或修改您的監視列表]]
* [[Special:Watchlist/clear|移除全部的頁面]]',
'wlheader-enotif'      => '*已經啟動電子郵件通知功能。',
'wlheader-showupdated' => "* 在你上次檢視後有被修改過的頁面會顯示為'''粗體'''",
'watchmethod-recent'   => '檢查被監視頁面的最近編輯',
'watchmethod-list'     => '檢查最近編輯的被監視頁面',
'removechecked'        => '將被選頁面從監視列表中移除',
'watchlistcontains'    => '您的監視列表包含$1個頁面。',
'watcheditlist'        => '這裡是您所監視的頁面的列表。要移除某一頁面，只要選擇該頁面然後點擊「移除頁面」按鈕。',
'removingchecked'      => '移除頁面...',
'couldntremove'        => "無法移除'$1'...",
'iteminvalidname'      => "頁面'$1'錯誤，無效命名...",
'wlnote'               => '以下是最近<b>$2</b>小時內的最後$1次修改。',
'wlshowlast'           => '顯示最近$1小時；$2天；$3的修改。',
'wlsaved'              => '您的監視列表如下：',
'watchlist-show-bots'  => '顯示機器人的編輯',
'watchlist-hide-bots'  => '隱藏機器人編輯',
'watchlist-show-own'   => '顯示我的修改',
'watchlist-hide-own'   => '隱藏我的修改',
'watchlist-show-minor' => '顯示小修改',
'watchlist-hide-minor' => '隱藏小修改',
'wldone'               => '完成。',

# Displayed when you click the "watch" button and it's in the process of watching
'unwatching' => '正在停止監視…',

# Delete/protect/revert
'deletepage'         => '刪除頁面',
'confirm'            => '確認',
'confirmdelete'      => '確認刪除',
'deletesub'          => '（正在刪除「$1」）',
'confirmdeletetext'  => '您即將從資料庫中永遠刪除一個頁面或圖像以及其歷史。
請確定您要進行此項操作，並且了解其後果，同時您的行為符合[[{{ns:project}}:守則與指導]]。',
'actioncomplete'     => '操作完成',
'deletedtext'        => '「$1」已經被刪除。
最近刪除的紀錄請參見$2。',
'deletedarticle'     => '已刪除「$1」',
'dellogpage'         => '刪除紀錄',
'dellogpagetext'     => '以下是最近刪除的紀錄列表。
所有的時間都是使用伺服器時間。
<ul>
</ul>',
'deletionlog'        => '刪除紀錄',
'reverted'           => '恢復到早期版本',
'deletecomment'      => '刪除理由',
'imagereverted'      => '恢復到早期版本操作完成。',
'rollback'           => '恢復',
'rollback_short'     => '恢復',
'rollbacklink'       => '恢復',
'rollbackfailed'     => '無法恢復',
'cantrollback'       => '無法恢復編輯；最後的貢獻者是本文的唯一作者。',
'revertpage'         => '恢復到$1的最後一次編輯',
'sessionfailure'     => '您的登入資訊似乎有問題，為防止此該訊息被攔截，本次操作已經取消，請按「上一頁」重新載入。',
'unprotectedarticle' => '$1解除保護',
'unprotectsub'       => '（正在解除「$1」的保護）',
'unprotectcomment'   => '解除保護原因',

# Undelete
'undelete'                 => '恢復被刪頁面',
'undeletepage'             => '瀏覽及恢復被刪頁面',
'viewdeletedpage'          => '檢視被刪除的頁面',
'undeletepagetext'         => '以下頁面已經被刪除，但依然在檔案中並可以被恢復。
檔案庫可能被定時清理。',
'undeleteextrahelp'        => "恢復整個頁面時，請清除所有複選框後按 '''''恢復''''' 。 恢復特定版本時，請選擇相應版本前的複選框後按'''''恢復''''' 。按 '''''重設''''' 將清除評論內容及所有複選框。",
'undeleterevisions'        => '$1版本存檔',
'undeletehistory'          => '如果您恢復了該頁面，所有版本都會被恢復到修訂歷史中。
如果本頁刪除後有一個同名的新頁面建立，
被恢復的版本將會稱為較新的歷史，而新頁面的當前版本將無法被自動復原。',
'undeletehistorynoadmin'   => '這個條目已經被刪除，刪除原因顯示在下方編輯摘要中。被刪除前的所有修訂版本，連同刪除前貢獻用戶等等
細節只有[[Wikipedia:管理員|管理員]]可以看見。',
'undelete-revision'        => '刪除版本 $1 自 $2：',
'undeleterevision-missing' => '此版本的內容不正確或已經遺失。可能連結錯誤、被移除或已經被恢復。',
'undeletebtn'              => '恢復！',
'undeletecomment'          => '原因',
'undeletedarticle'         => '已經恢復「$1」',
'undeletedrevisions'       => '$1個修訂版本已經恢復',
'undeletedrevisions-files' => '$1 個版本和 $2 個文件被恢復',
'undeletedfiles'           => '$1 個文件被恢復',
'cannotundelete'           => '恢復失敗；可能之前已經被其他人恢復。',
'undeletedpage'            => "<big>'''$1已經被恢復'''</big> 請參考[[Special:Log/delete|刪除日誌]]來查詢刪除及恢復記錄。",
'undelete-header'          => '如要查詢最近的記錄請參閱[[Special:Log/delete|刪除日誌]]。',
'undelete-search-box'      => '搜尋已刪除頁面',
'undelete-search-prefix'   => '顯示頁面自：',
'undelete-search-submit'   => '搜尋',
'undelete-no-results'      => '刪除裡錄裡沒有符合的結果。',

# Contributions
'contributions' => '用戶貢獻',
'mycontris'     => '我的貢獻',
'contribsub'    => '為$1',
'nocontribs'    => '沒有找到符合特徵的更改。',
'ucnote'        => '以下是該用戶最近<b>$2</b>天內的最後<b>$1</b>次修改。',
'uclinks'       => '參看最後$1次修改；參看最後$2天。',
'uctop'         => ' (頂)',

'sp-contributions-newest'      => '最新',
'sp-contributions-newer'       => '前$1次',
'sp-contributions-older'       => '後$1次',
'sp-contributions-newbies-sub' => '新手',
'sp-contributions-blocklog'    => '封禁記錄',

# What links here
'whatlinkshere' => '鏈入頁面',
'notargettitle' => '無目標',
'notargettext'  => '您還沒有指定一個目標頁面或用戶以進行此項操作。',
'linklistsub'   => '(連結列表)',
'linkshere'     => '以下頁面連結到這裡：',
'nolinkshere'   => '沒有頁面連結到這裡。',
'isredirect'    => '重定向頁',

# Block/unblock
'blockip'                     => '查封IP地址',
'blockiptext'                 => '用下面的表單來禁止來自某一特定IP地址的修改許可權。
只有在為防止破壞，及符合[[{{ns:project}}:守則與指導]]的情況下才可採取此行動。
請在下面輸入一個具體的理由（例如引述一個被破壞的頁面）。',
'ipaddress'                   => 'IP地址',
'ipbreason'                   => '原因',
'ipbsubmit'                   => '查封該地址',
'badipaddress'                => 'IP地址不正確。',
'blockipsuccesssub'           => '查封成功',
'blockipsuccesstext'          => 'IP地址「$1」已經被查封。
<br />參看[[Special:Ipblocklist|被封IP地址列表]]以覆審查封。',
'unblockip'                   => '解除禁封IP地址',
'unblockiptext'               => '用下面的表單來恢復先前被禁封的IP地址的書寫權。',
'ipusubmit'                   => '解除禁封',
'unblocked'                   => '[[{{ns:2}}:$1|$1]] 的封禁已經解除。',
'ipblocklist'                 => '被封IP地址列表',
'blocklistline'               => '$1，$2禁封$3 ($4)',
'blocklink'                   => '禁封',
'unblocklink'                 => '解除禁封',
'contribslink'                => '貢獻',
'autoblocker'                 => '你的IP和被封了的 "$1" 是一樣的。封鎖原因： "$2".',
'blocklogpage'                => '封鎖記錄',
'blocklogentry'               => '封鎖 $1, $2',
'blocklogtext'                => '這是關於用戶封禁和解除封禁操作的記錄。被自動封禁的IP地址沒有被列出。請參閱[[Special:Ipblocklist|被查封的IP地址和用戶列表]]。',
'unblocklogentry'             => '「[[$1]]」已被解封',
'block-log-flags-anononly'    => '僅限匿名用戶',
'block-log-flags-nocreate'    => '禁止此IP/用戶建立新帳戶',
'block-log-flags-autoblock'   => '自動封鎖此用戶使用過的IP',
'sorbsreason'                 => '您的IP位址被[http://www.sorbs.net SORBS] DNSBL列為屬於開放代理服務器.',
'sorbs_create_account_reason' => '由於您的IP位址被[http://www.sorbs.net SORBS] DNSBL列為屬於開放代理服務器，所以您無法建立帳號。',

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

# Move page
'movepage'         => '移動頁面',
'movepagetext'     => "用下面的表單來重命名一個頁面，並將其修訂歷史同時移動到新頁面。
老的頁面將成為新頁面的重定向頁。
連結到老頁面的連結並不會自動更改；
請檢查雙重或損壞重定向連結。
您應當負責確定所有連結依然會鏈到指定的頁面。

注意如果新頁面已經有內容的話，頁面將'''不會'''被移動，
除非新頁面無內容或是重定向頁，而且沒有修訂歷史。
這意味著您再必要時可以在移動到新頁面後再移回老的頁面，
同時您也無法覆蓋現有頁面。

<b>警告！</b>
對一個經常被訪問的頁面而言這可能是一個重大與唐突的更改；
請在行動前先了結其所可能帶來的後果。",
'movepagetalktext' => "有關的對話頁（如果有的話）將被自動與該頁面一起移動，'''除非'''：
*您將頁面移動到不同的名字空間；
*新頁面已經有一個包含內容的對話頁，或者
*您不勾選下面的覆選框。

在這些情況下，您在必要時必須手工移動或合併頁面。",
'movearticle'      => '移動頁面',
'movenologin'      => '未登入',
'movenologintext'  => '您必須是一名登記用戶並且[[Special:Userlogin|登入]]
後才可移動一個頁面。',
'newtitle'         => '新標題',
'movepagebtn'      => '移動頁面',
'pagemovedsub'     => '移動成功',
'pagemovedtext'    => '頁面「[[$1]]」已經移動到「[[$2]]」。',
'articleexists'    => '該名字的頁面已經存在，或者您選擇的名字無效。請再選一個名字。',
'talkexists'       => '頁面本身移動成功，
但是由於新標題下已經有對話頁存在，所以對話頁無法移動。請手工合併兩個頁面。',
'movedto'          => '移動到',
'movetalk'         => '如果可能的話，請同時移動對話頁。',
'talkpagemoved'    => '相應的對話頁也已經移動。',
'talkpagenotmoved' => '相應的對話頁<strong>沒有</strong>被移動。',
'1movedto2'        => '$1移動到$2',
'1movedto2_redir'  => '$1重定向到$2',
'selfmove'         => '原始標題與目標標題相同，您不能移動一頁覆蓋本身。',

# Namespace 8 related
'allmessages'               => '系統介面',
'allmessagesname'           => '名稱',
'allmessagesdefault'        => '預設的文字',
'allmessagescurrent'        => '當前的文字',
'allmessagestext'           => '這裡列出所有可定製的系統介面。',
'allmessagesnotsupportedUI' => 'Special:Allmessages不支援您目前使用的介面語言<b>$1</b>。',
'allmessagesnotsupportedDB' => '系統介面功能處於關閉狀態 (wgUseDatabaseMessages)。',
'allmessagesfilter'         => '正則表達式過濾條件：',
'allmessagesmodified'       => '僅顯示修改過的',

# Thumbnails
'thumbnail-more'  => '放大',
'thumbnail_error' => '無法產生縮圖，原因：$1',

# Tooltip help for the actions
'tooltip-pt-userpage'             => '我的用戶頁',
'tooltip-pt-anonuserpage'         => '您編輯本站所用IP的對應用戶頁',
'tooltip-pt-mytalk'               => '我的對話頁',
'tooltip-pt-anontalk'             => '對於來自此IP地址編輯的討論',
'tooltip-pt-preferences'          => '參數設置',
'tooltip-pt-watchlist'            => '監視列表',
'tooltip-pt-mycontris'            => '我的貢獻列表',
'tooltip-pt-login'                => '建議您登入，儘管並非必須。',
'tooltip-pt-anonlogin'            => '建議您登入，儘管並非必須。',
'tooltip-pt-logout'               => '登出',
'tooltip-ca-talk'                 => '關於條目正文的討論',
'tooltip-ca-edit'                 => '您可以編輯此頁，請在保存之前先預覽一下。',
'tooltip-ca-addsection'           => '於本討論頁增加新的討論主題',
'tooltip-ca-viewsource'           => '該頁面已被保護。你可以查看該頁原始碼。',
'tooltip-ca-history'              => '本頁面的早前版本。',
'tooltip-ca-protect'              => '保護該頁面',
'tooltip-ca-delete'               => '刪除本頁',
'tooltip-ca-undelete'             => '將這個頁面恢復到被刪除以前的狀態',
'tooltip-ca-move'                 => '移動本頁',
'tooltip-ca-watch'                => '將此頁面加入監視列表',
'tooltip-ca-unwatch'              => '將此頁面從監視列表中移除',
'tooltip-search'                  => '搜尋{{SITENAME}}',
'tooltip-p-logo'                  => '首頁',
'tooltip-n-mainpage'              => '訪問首頁',
'tooltip-n-portal'                => '關於本計畫、你可以做什麼、應該如何做',
'tooltip-n-currentevents'         => '提供當前新聞事件的背景資料',
'tooltip-n-recentchanges'         => '列出{{SITENAME}}中的最近修改',
'tooltip-n-randompage'            => '隨機載入一個頁面',
'tooltip-n-help'                  => '尋求幫助',
'tooltip-n-sitesupport'           => '如果您在{{SITENAME}}受益良多，您可以考慮贊助我們',
'tooltip-t-whatlinkshere'         => '列出所有與本頁相鏈的頁面',
'tooltip-t-recentchangeslinked'   => '頁面鏈出所有頁面的更改',
'tooltip-feed-rss'                => '訂閱本頁面歷史的RSS資訊',
'tooltip-feed-atom'               => '訂閱本頁面歷史的Atom訊息',
'tooltip-t-contributions'         => '查看該用戶的貢獻列表',
'tooltip-t-emailuser'             => '向該用戶發送電子郵件',
'tooltip-t-upload'                => '上傳圖像或多媒體檔',
'tooltip-t-specialpages'          => '全部特殊頁面的列表',
'tooltip-ca-nstab-main'           => '查看頁面內容',
'tooltip-ca-nstab-user'           => '查看用戶頁',
'tooltip-ca-nstab-media'          => '查看多媒體檔案資訊頁面',
'tooltip-ca-nstab-special'        => '本頁面會隨著資料庫的數據即時更新，任何人均不能直接編輯',
'tooltip-ca-nstab-project'        => '查看項目頁面',
'tooltip-ca-nstab-image'          => '查詢圖片頁面',
'tooltip-ca-nstab-mediawiki'      => '查看系統資訊',
'tooltip-ca-nstab-template'       => '查看模板',
'tooltip-ca-nstab-help'           => '查看幫助頁面',
'tooltip-ca-nstab-category'       => '查看分類頁面',
'tooltip-minoredit'               => '標記為小修改',
'tooltip-save'                    => '保存您的修改',
'tooltip-preview'                 => '預覽您的編輯，請先使用本功能再保存！',
'tooltip-diff'                    => '顯示您對條目的貢獻',
'tooltip-compareselectedversions' => '查看本頁被點選的兩個版本間的差異',
'tooltip-watch'                   => '將此頁加入您的監視列表',
'tooltip-recreate'                => '重建該頁面，無論是否被刪除。',

# Attribution
'anonymous' => '匿名用戶',
'siteuser'  => '{{SITENAME}} 用戶 $1',
'and'       => '和',
'siteusers' => '{{SITENAME}} 用戶 $1',

# Spam protection
'spamprotectiontitle'  => '垃圾過濾器',
'spamprotectiontext'   => '垃圾過濾器禁止保存您剛才提交的頁面，這可能是由於您所加入的外部網站鏈接所產生的問題。',
'spamprotectionmatch'  => '觸發了我們的垃圾過濾器的文本如下：$1',
'subcategorycount'     => '這個分類下有$1個子分類。',
'categoryarticlecount' => '該類頁面共有 $1 條目',

# Math options
'mw_math_png'    => '永遠使用PNG圖像',
'mw_math_simple' => '如果是簡單的公式使用HTML，否則使用PNG圖像',
'mw_math_html'   => '如果可以用HTML，否則用PNG圖像',
'mw_math_source' => '顯示為TeX代碼(使用文字瀏覽器時)',
'mw_math_modern' => '推薦為新版瀏覽器使用',

# Media information
'thumbsize'            => '縮圖大小：',
'show-big-image'       => '檢視原始尺寸圖像',
'show-big-image-thumb' => '<small>預覽尺寸： $1 × $2 像素</small>',

'showhidebots' => '($1機器人)',

/*
Short names for language variants used for language conversion links.
To disable showing a particular link, set it to 'disable', e.g.
'variantname-zh-sg' => 'disable',
Variants for Chinese language
*/
'variantname-zh-cn' => '大陸簡體',
'variantname-zh-tw' => '台灣繁體',
'variantname-zh-hk' => '香港繁體',
'variantname-zh-sg' => '新加坡簡體',
'variantname-zh'    => '不轉換',

# 'all' in various places, this might be different for inflected languages
'watchlistall1' => '全部',
'watchlistall2' => '全部',

# Inputbox extension, may be useful in other contexts as well
'tryexact'       => '轉到',
'searchfulltext' => '全文檢索',

# Scary transclusion
'scarytranscludedisabled' => '[跨wiki轉換代碼被禁止]',
'scarytranscludefailed'   => '[模板$1讀取失敗！]',
'scarytranscludetoolong'  => '[URL 地址太長！]',

# Trackbacks
'trackbackbox'      => '<div id="mw_trackbacks">本條目的 trackbacks：<br />$1 </div>',
'trackbackremove'   => ' ([$1 删除])',
'trackbacklink'     => '迴響',
'trackbackdeleteok' => 'Trackback 刪除成功。',

'youhavenewmessagesmulti' => '您在 $1 有一條新訊息',

'searchcontaining' => "搜索包含''$1''的條目。",
'searchnamed'      => '搜索名稱為 <i>$1</i> 的條目。',

# Table pager
'table_pager_next'         => '下一頁',
'table_pager_prev'         => '上一頁',
'table_pager_first'        => '第一頁',
'table_pager_last'         => '最末頁',
'table_pager_limit'        => '每頁顯示 $1 筆記錄',
'table_pager_limit_submit' => '送出',
'table_pager_empty'        => '沒有結果',

# Auto-summaries
'autoredircomment' => '重定向到[[$1]]', # This should be changed to the new naming convention, but existed beforehand

);

?>
