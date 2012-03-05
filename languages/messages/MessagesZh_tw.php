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
 * @author BobChao
 * @author Jidanni
 * @author Mark85296341
 * @author PhiLiP
 * @author Roc michael
 * @author Urhixidur
 * @author Wong128hk
 * @author לערי ריינהארט
 */

$specialPageAliases = array(
	'Disambiguations'           => array( '消歧義頁' ),
	'Recentchanges'             => array( '近期變動' ),
	'Ancientpages'              => array( '最舊頁面' ),
	'Unblock'                   => array( '解除封锁' ),
	'Blockme'                   => array( '封禁我' ),
	'Blockip'                   => array( '查封用戶' ),
	'Lockdb'                    => array( '鎖定數據庫' ),
	'Unlockdb'                  => array( '解除數據庫鎖定' ),
	'Userrights'                => array( '用戶權限' ),
	'MIMEsearch'                => array( 'MIME搜索' ),
	'FileDuplicateSearch'       => array( '搜索重復文件' ),
	'Unwatchedpages'            => array( '未被監視的頁面' ),
	'Listredirects'             => array( '重定向頁面列表' ),
	'Revisiondelete'            => array( '刪除或恢復版本' ),
	'Randomredirect'            => array( '隨機重定向頁面' ),
	'Withoutinterwiki'          => array( '沒有跨語言鏈接的頁面' ),
	'Invalidateemail'           => array( '無法識別的電郵地址' ),
	'LinkSearch'                => array( '搜索網頁鏈接' ),
);

$fallback = 'zh-hant';

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

$messages = array(
# User preference toggles
'tog-underline'            => '連結標注底線',
'tog-hideminor'            => '近期變動中隱藏細微修改',
'tog-usenewrc'             => '增強版近期變動 (JavaScript)',
'tog-enotifwatchlistpages' => '當我監視的頁面改變時發電子郵件給我',
'tog-shownumberswatching'  => '顯示監視數目',
'tog-uselivepreview'       => '使用即時預覽 (JavaScript) (試驗中)',
'tog-watchlisthideminor'   => '監視列表中隱藏細微修改',
'tog-ccmeonemails'         => '當我寄電子郵件給其他使用者時，也寄一份複本到我的信箱。',

# Categories related messages
'subcategories' => '子分類',

'mainpagedocfooter' => '請參閱 [http://meta.wikimedia.org/wiki/Help:Contents 使用者手冊] 以獲得使用此 wiki 軟體的訊息！

== 入門 ==

* [http://www.mediawiki.org/wiki/Manual:Configuration_settings MediaWiki 配置設定清單]
* [http://www.mediawiki.org/wiki/Manual:FAQ MediaWiki 常見問題解答]
* [https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce MediaWiki 發佈郵件清單]',

'help'           => '使用說明',
'search'         => '搜尋',
'history'        => '修訂記錄',
'protect_change' => '更改保護',
'postcomment'    => '發表評論',
'userpage'       => '檢視使用者頁面',
'projectpage'    => '檢視計畫頁面',
'lastmodifiedat' => '本頁最後更動時間在 $1 $2。',
'jumptosearch'   => '搜尋',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'copyright'  => '本站的文字內容除另有聲明外，全部以 $1 條款授權使用。',
'edithelp'   => '編輯說明',
'portal'     => '社群入口',
'portal-url' => 'Project:社群入口',

'badaccess-groups' => '您剛才的請求只有{{PLURAL:$2|這個|這些}}使用者組的使用者才能使用: $1',

'thisisdeleted'  => '檢視或復原$1?',
'site-rss-feed'  => '訂閱 $1 的 RSS 資料來源',
'site-atom-feed' => '訂閱 $1 的 Atom 資料來源',
'page-rss-feed'  => '訂閱「$1」的 RSS 資料來源',
'page-atom-feed' => '訂閱「$1」的 Atom 資料來源',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-user'      => '使用者頁面',
'nstab-project'   => '計畫頁面',
'nstab-mediawiki' => '介面',
'nstab-help'      => '說明頁面',

# Main script and global functions
'nosuchactiontext' => '這個wiki無法識別URL請求的命令',

# General errors
'readonlytext'         => '資料庫目前禁止輸入新內容及更改，
這很可能是由於資料庫正在維修，之後即可復原。
管理員有如下解釋:
<p>$1</p>',
'cannotdelete'         => '無法刪除選定的頁面或圖片（它可能已經被其他人刪除了）。',
'actionthrottledtext'  => '系統因為反垃圾編輯的考量，禁止如此頻繁地修改資料，請數分鐘後再嘗試。',
'viewsourcetext'       => '你可以檢視並複製本頁面的原始碼。',
'editinginterface'     => "'''警告:''' 您正在編輯的頁面是用於提供軟體的介面文字。改變此頁將影響其他使用者的介面外觀。",
'customcssjsprotected' => '您並無許可權去編輯這個頁面，因為它包含了另一位使用者的個人設定。',
'ns-specialprotected'  => '在{{ns:special}}名字空間中的頁面是不可以編輯的。',

# Login and logout pages
'logouttext'                 => '您現在已經退出。

您可以繼續以匿名方式使用{{SITENAME}}，或再次以相同或不同使用者身份登入。',
'welcomecreation'            => '<h2>歡迎，$1!</h2><p>您的帳號已經建立，不要忘記設定{{SITENAME}}個人參數。</p>',
'yourname'                   => '您的使用者名:',
'nav-login-createaccount'    => '登入／建立新帳號',
'userlogin'                  => '登入／建立新帳號',
'nologin'                    => '您還沒有帳號嗎？$1。',
'nologinlink'                => '建立新帳號',
'createaccount'              => '建立新帳號',
'gotaccount'                 => '已經擁有帳號？$1。',
'badretype'                  => '你所輸入的密碼並不相同。',
'userexists'                 => '您所輸入的使用者名稱已經存在，請另選一個。',
'nocookiesnew'               => '已成功建立新帳號！偵測到您已關閉 Cookies，請開啟它並登入。',
'nocookieslogin'             => '本站利用 Cookies 進行使用者登入，偵測到您已關閉 Cookies，請開啟它並重新登入。',
'noname'                     => '你沒有輸入一個有效的使用者帳號。',
'loginsuccess'               => '你現在以 "$1"的身份登入{{SITENAME}}。',
'nosuchuser'                 => '找不到使用者 "$1"。
檢查您的拼寫，或者用下面的表格[[Special:UserLogin/signup|建立一個新帳號]]。',
'nosuchusershort'            => '沒有一個名為「<nowiki>$1</nowiki>」的使用者。請檢查您輸入的文字是否有錯誤。',
'nouserspecified'            => '你需要指定一個使用者帳號。',
'passwordtooshort'           => '您的密碼不正確或太短，不能少於$1個字元，而且必須跟使用者名不同。',
'passwordremindertitle'      => '{{SITENAME}}密碼提醒',
'passwordremindertext'       => '有人(可能是您，來自IP位址$1)要求我們將新的{{SITENAME}} ($4) 的登入密碼寄給您。使用者"$2"的密碼現在是"$3"。請立即登入並更改密碼。如果是其他人發出了該請求，或者您已經記起了您的密碼並不準備改變它，您可以忽略此消息並繼續使用您的舊密碼。',
'noemail'                    => '使用者"$1"沒有登記電子郵件地址。',
'passwordsent'               => '使用者"$1"的新密碼已經寄往所登記的電子郵件地址。
請在收到後再登入。',
'blocked-mailpassword'       => '由於這個使用者被封鎖，我們暫時禁止您請求申請新密碼。造成不便敬請見諒',
'eauthentsent'               => '一封確認信已經發送到所示的地址。在發送其它郵件到此帳號前，您必須首先依照這封信中的指導確認這個電子郵件信箱真實有效。',
'acct_creation_throttle_hit' => '對不起，您已經註冊了$1帳號。你不能再註冊了。',
'emailauthenticated'         => '您的電子郵件地址已經於$1確認有效。',
'emailnotauthenticated'      => '您的電子郵件地址<strong>還沒被認證</strong>。以下功能將不會發送任何郵件。',
'noemailprefs'               => '指定一個電子郵件地址以使用此功能',
'emailconfirmlink'           => '確認您的電子郵件地址',
'invalidemailaddress'        => '電子郵件地址格式不正確，請輸入正確的電子郵件地址或清空該輸入框。',
'accountcreated'             => '已建立帳號',
'accountcreatedtext'         => '$1的帳號已經被建立。',
'createaccount-title'        => '在{{SITENAME}}中建立新帳號',
'createaccount-text'         => '有人在{{SITENAME}}中為 $2 建立了一個新帳號($4)。 "$2" 的密碼是 "$3" 。您應該立即登入並更改密碼。

如果該帳號建立錯誤的話，您可以忽略此訊息。',

# Password reset dialog
'resetpass'           => '重設帳號密碼',
'resetpass_announce'  => '您是透過臨時發送到郵件中的代碼登入的。要完成登入，您必須在這裡設定一個新密碼:',
'resetpass_header'    => '重設密碼',
'oldpassword'         => '舊密碼',
'newpassword'         => '新密碼',
'resetpass_success'   => '您的密碼已經被成功更改﹗現下正為您登入...',
'resetpass_forbidden' => '無法在此 wiki 上更改密碼',

# Edit page toolbar
'image_tip' => '嵌入圖片',
'media_tip' => '媒體檔案連結',

# Edit pages
'summary'                    => '摘要',
'minoredit'                  => '這是一個細微修改',
'blockedtitle'               => '使用者被封鎖',
'confirmedittext'            => '在編輯此頁之前您必須確認您的電子郵件地址。請透過[[Special:Preferences|參數設定]]設定並驗証您的電子郵件地址。',
'accmailtext'                => "'$1'的密碼已經寄到$2。",
'newarticletext'             => '您進入了一個尚未建立的頁面。
要建立該頁面，請在下面的編輯框中輸入內容(詳情參見[[{{MediaWiki:Helppage}}|說明]])。
如果您是不小心來到此頁面，直接點擊您瀏覽器中的"返回"按鈕返回。',
'anontalkpagetext'           => "---- ''這是一個還未建立帳號的匿名使用者的對話頁。我們因此只能用IP地址來與他／她聯絡。該IP地址可能由幾名使用者共享。如果您是一名匿名使用者並認為本頁上的評語與您無關，請[[Special:UserLogin|建立新帳號或登入]]以避免在未來於其他匿名使用者混淆。''",
'noarticletext'              => '此頁目前沒有內容，您可以在其它頁[[Special:Search/{{PAGENAME}}|搜尋此頁標題]]或[{{fullurl:{{NAMESPACE}}:{{PAGENAME}}|action=edit}} 編輯此頁]。',
'userpage-userdoesnotexist'  => '使用者帳號「$1」未曾建立。請在建立／編輯這個頁面前先檢查一下。',
'clearyourcache'             => "'''注意:''' 在儲存以後, 您必須清除瀏覽器的快取才能看到所作出的改變。 '''Mozilla / Firefox / Safari:''' 按著 ''Shift'' 再點擊''重新整理''(或按下''Ctrl-Shift-R''，在蘋果Mac上按下''Cmd-Shift-R'')；'''IE:''' 按著 ''Ctrl'' 再點擊 ''重新整理''，或按下 ''Ctrl-F5''；'''Konqueror:''' 只需點擊 ''重新整理''；'''Opera:''' 使用者需要在 ''工具-設定'' 中完整地清除它們的快取。",
'usercsspreview'             => "'''注意您只是在預覽您的個人 CSS, 還沒有儲存﹗'''",
'userjspreview'              => "'''注意您只是在測試／預覽您的個人 JavaScript，還沒有儲存﹗'''",
'previewnote'                => "'''請記住這只是預覽，內容還未保存！'''",
'session_fail_preview'       => "'''很抱歉！由於部份資料遺失，我們無法處理您的編輯。請再試一次，如果仍然失敗，請登出後重新登入。'''",
'session_fail_preview_html'  => "'''很抱歉！部份資料已遺失，我們無法處理您的編輯。''''''如果這個編輯過程沒有問題，請再試一次。如果仍然有問題，請登出後再重新登入一次。'''",
'token_suffix_mismatch'      => "'''由於您使用者端中的編輯信符毀損了一些標點符號字元，為防止編輯的文字損壞，您的編輯已經被拒絕。
這種情況通常出現於使用含有很多臭蟲、以網路為主的匿名代理服務的時候。'''",
'editingcomment'             => '正在編輯$1 (評論)',
'storedversion'              => '已保存版本',
'nonunicodebrowser'          => "'''警告: 您的瀏覽器不相容Unicode編碼。這裡有一個工作區將使您能安全地編輯頁面: 非ASCII字元將以十六進製編碼模式出現在編輯框中。'''",
'editingold'                 => "'''警告：你正在編輯的是本頁的舊版本。
如果你保存它的話，在本版本之後的任何修改都會遺失。'''",
'longpagewarning'            => "'''警告: 本頁長度達$1 kB；一些瀏覽器將無法編輯長過32KB頁面。請考慮將本文切割成幾個小段落。'''",
'longpageerror'              => "'''錯誤: 您所提交的文字長度有$1KB，這大於$2KB的最大值。該文字不能被儲存。'''",
'protectedpagewarning'       => "'''警告: 本頁已經被保護，只有擁有管理員許可權的使用者才可修改。'''",
'semiprotectedpagewarning'   => "'''注意:''' 本頁面被鎖定，僅限註冊使用者編輯。",
'cascadeprotectedwarning'    => '警告: 本頁已經被保護，只有擁有管理員權限的使用者才可修改，因為本頁已被以下連鎖保護的{{PLURAL:$1|一個|多個}}頁面所包含:',
'nocreatetitle'              => '建立頁面受限',
'nocreatetext'               => '此網站限制了建立新頁面的功能。你可以返回並編輯已有的頁面，或者[[Special:UserLogin|登錄或建立新帳號]]。',
'nocreate-loggedin'          => '您在這個wiki中並無許可權去建立新頁面。',
'recreate-moveddeleted-warn' => "'''警告: 你現在重新建立一個先前曾經刪除過的頁面。'''

你應該要考慮一下繼續編輯這一個頁面是否合適。
為方便起見，這一個頁面的刪除記錄已經在下面提供:",
'edit-hook-aborted'          => '編輯被鉤取消。
它並無給出解釋。',

# Parser/template warnings
'post-expand-template-argument-category' => '包含著略過模板參數的頁面',

# Account creation failure
'cantcreateaccounttitle' => '無法建立帳號',
'cantcreateaccount-text' => "從這個IP地址 (<b>$1</b>) 建立帳號已經被[[User:$3|$3]]禁止。

當中被$3封鎖的原因是''$2''",

# History pages
'histlegend' => '差異選擇: 標記要比較版本的單選按鈕並點擊底部的按鈕進行比較。<br />
說明: (目前) 指與目前版本比較，(先前) 指與前一個修訂版本比較，小 = 細微修改。',

# Revision feed
'history-feed-title'       => '修訂沿革',
'history-feed-description' => '本站上此頁的修訂沿革',
'history-feed-empty'       => '所請求的頁面不存在。它可能已被刪除或重新命名。
嘗試[[Special:Search|搜尋本站]]獲得相關的新建頁面。',

# Revision deletion
'rev-deleted-user'            => '(使用者名已移除)',
'rev-deleted-event'           => '(項目已移除)',
'rev-deleted-text-permission' => '該頁面修訂已經被從公共文件中移除。
在[{{fullurl:{{#Special:Log}}/suppress|page={{PAGENAMEE}}}} 刪除日誌]中您可能會檢視到詳細的訊息。',
'rev-deleted-text-view'       => '該頁面修訂已經被從公共文件中移除。作為此網站的管理員，您可以檢視它；
在[{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} 刪除日誌]中您可能會檢視到詳細的訊息。',
'revisiondelete'              => '刪除/復原刪除修訂',
'revdelete-nooldid-title'     => '沒有目標修訂',
'revdelete-nooldid-text'      => '您沒有指定此操作的目標修訂。',
'logdelete-selected'          => "'''選取的$1個日誌項目:'''",
'revdelete-text'              => "'''刪除的修訂仍將顯示在修訂記錄中, 但它們的文字內容已不能被公眾訪問。'''

在此網站的其他管理員將仍能訪問隱藏的內容並透過與此相同的介面復原刪除，除非網站工作者進行了一些附加的限制。",
'revdelete-legend'            => '設定修訂限制:',
'revdelete-hide-user'         => '隱藏編輯者的使用者名/IP',
'revdelete-hide-restricted'   => '將此限制同樣應用於管理員',
'revdelete-suppress'          => '同時壓制由操作員以及其他使用者的資料',
'revdelete-unsuppress'        => '在已復原的修訂中移除限制',
'revdelete-success'           => '修訂的可見性已經成功設定。',
'logdelete-success'           => '事件的可見性已經成功設定。',

# History merging
'mergehistory'        => '合併修訂記錄',
'mergehistory-header' => "這一頁可以講您合併一個來源頁面的歷史到另一個新頁面中。
請確認這次更改會繼續保留該頁面先前的歷史版本。

'''最少該來源頁面的現時修訂必定會保持。'''",
'mergehistory-merge'  => '以下[[:$1]]的修訂可以合併到[[:$2]]。用該選項按鈕欄去合併只有在指定時間以前所建立的修訂。要留意的是使用導航連結便會重設這一欄。',

# Merge log
'mergelogpagetext' => '以下是一個最近由一個頁面的修訂沿革合併到另一個頁面的列表。',

# Diffs
'history-title'           => '「$1」的修訂沿革',
'compareselectedversions' => '比較選定的版本',

# Search results
'searchresults'    => '搜尋結果',
'searchresulttext' => '有關搜尋{{SITENAME}}的更多詳情,參見[[{{MediaWiki:Helppage}}|{{int:help}}]]。',
'searchsubtitle'   => '查詢"[[:$1]]"',
'nonefound'        => '<strong>注意：</strong>失敗的搜尋往往是由於試圖搜尋諸如「的」或「和」之類的常見字所引起。',
'powersearch'      => '搜尋',
'searchdisabled'   => '{{SITENAME}}由於性能方面的原因，全文搜尋已被暫時停用。您可以暫時透過Google搜尋。請留意他們的索引可能會過時。',

# Preferences page
'preferences'          => '偏好設定',
'mypreferences'        => '我的偏好設定',
'prefsnologintext'     => '您必須先[[Special:UserLogin|登入]]才能設定個人參數。',
'prefs-personal'       => '使用者資料',
'prefs-rc'             => '近期變動',
'prefs-watchlist-days' => '監視列表中顯示記錄的最長天數:',
'saveprefs'            => '保存偏好設定',
'resetprefs'           => '重設參數',
'searchresultshead'    => '搜尋結果設定',
'recentchangesdays'    => '近期變動中的顯示日數:',
'recentchangescount'   => '近期變動中的編輯數:',
'savedprefs'           => '您的個人偏好設定已經保存。',
'timezonelegend'       => '時區',
'localtime'            => '當地時間',
'timezoneoffset'       => '時差¹',
'servertime'           => '伺服器時間',
'allowemail'           => '接受來自其他使用者的郵件',
'defaultns'            => '預設搜尋的名字空間',
'username'             => '使用者名:',
'uid'                  => '使用者ID:',
'yournick'             => '暱稱:',
'badsig'               => '錯誤的原始簽名；請檢查HTML標籤。',
'badsiglength'         => '暱稱過長；它的長度必須在$1個字元以下。',
'prefs-help-realname'  => '真實姓名是選填的，如果您選擇提供它，那它便用以對您的貢獻署名。',
'prefs-help-email'     => '電子郵件是選填的，但當啟用它後可以在您沒有公開自己的使用者身分時透過您的使用者頁或使用者討論頁與您聯繫。',

# User rights
'userrights'               => '使用者權限管理',
'userrights-lookup-user'   => '管理使用者群組',
'userrights-user-editname' => '輸入使用者帳號:',
'editusergroup'            => '編輯使用者群組',
'editinguser'              => "正在編輯使用者'''[[User:$1|$1]]''' ([[User talk:$1|{{int:talkpagelinktext}}]] | [[Special:Contributions/$1|{{int:contribslink}}]])",
'userrights-editusergroup' => '編輯使用者群組',
'saveusergroups'           => '保存使用者群組',

# Groups
'group-autoconfirmed' => '自動確認使用者',

'group-autoconfirmed-member' => '自動確認使用者',

'grouppage-autoconfirmed' => '{{ns:project}}:自動確認使用者',

# User rights log
'rightslog'     => '使用者權限日誌',
'rightslogtext' => '以下記錄了使用者權限的更改記錄。',

# Recent changes
'recentchanges'                     => '近期變動',
'recentchanges-feed-description'    => '跟蹤此訂閱在 wiki 上的近期變動。',
'rcnotefrom'                        => '下面是自<b>$2</b>(最多顯示<b>$1</b>):',
'rcshowhideminor'                   => '$1細微修改',
'rcshowhidebots'                    => '$1機器人的編輯',
'rcshowhideliu'                     => '$1具名使用者的編輯',
'rcshowhideanons'                   => '$1匿名使用者的編輯',
'rcshowhidepatr'                    => ' $1檢查過的編輯',
'number_of_watching_users_pageview' => '[$1個關注使用者]',

# Recent changes linked
'recentchangeslinked'         => '相關頁面修訂記錄',
'recentchangeslinked-feed'    => '相關頁面修訂記錄',
'recentchangeslinked-toolbox' => '相關頁面修訂記錄',
'recentchangeslinked-title'   => '$1 內連結頁面的修訂記錄',
'recentchangeslinked-summary' => "這一個特殊頁面列示這一頁連出頁面的近期變動。在您監視列表中的頁面會以'''粗體'''表示。",

# Upload
'reuploaddesc'                => '返回上載表單。',
'uploadtext'                  => "使用下面的表單來上傳用在頁面內新的圖片檔案。
要檢視或搜尋以前上傳的圖片
可以進入[[Special:FileList|圖片清單]]，
上傳和刪除將在[[Special:Log/upload|上傳日誌]]中記錄。

要在頁面中加入圖片，使用以下形式的連接:
'''<nowiki>[[</nowiki>{{ns:file}}:file.jpg<nowiki>]]</nowiki>'''，
'''<nowiki>[[</nowiki>{{ns:file}}:file.png|替換文字<nowiki>]]</nowiki>''' 或
'''<nowiki>[[</nowiki>{{ns:media}}:file.ogg<nowiki>]]</nowiki>'''。",
'uploadlogpagetext'           => '以下是最近上載的檔案的一覽表。',
'ignorewarning'               => '忽略警告並儲存檔案。',
'illegalfilename'             => '檔案名"$1"包含有頁面標題所禁止的字符。請改名後重新上傳。',
'badfilename'                 => '檔案名已被改為"$1"。',
'hookaborted'                 => '您所嘗試的修改被擴展鉤捨棄。',
'fileexists-thumbnail-yes'    => "這個檔案好像是一幅圖片的縮圖版本''(縮圖)''。 [[$1|thumb]]
請檢查清楚該檔案'''<tt>[[:$1]]</tt>'''。
如果檢查後的檔案是同原本圖片的大小是一樣的話，就不用再上載多一幅縮圖。",
'file-thumbnail-no'           => "該檔名是以'''<tt>$1</tt>'''開始。它好像一幅圖片的縮圖版本''(縮圖)''。
如果你有該圖片的完整大小，如不是請再修改檔名。",
'fileexists-forbidden'        => '已存在相同名稱的檔案；請返回並用一個新的名稱來上傳此檔案。[[File:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => '在共享檔案庫中已存在此名稱的檔案；請返回並用一個新的名稱來上傳此檔案。[[File:$1|thumb|center|$1]]',
'uploaddisabledtext'          => '檔案上傳在此網站不可用。',
'watchthisupload'             => '監視此頁',

'upload-proto-error'     => '協訂錯誤',
'upload-file-error-text' => '當試圖在伺服器上建立臨時檔案時發生內部錯誤。請與系統管理員聯繫。',
'upload-misc-error-text' => '在上傳時發生未知的錯誤. 請驗証使用了正確並可訪問的 URL，然後進行重試。如果問題仍然存在，請與系統管理員聯繫。',

# Special:ListFiles
'listfiles_search_for' => '按圖片名稱搜尋:',
'listfiles_user'       => '使用者',

# File description page
'filehist-deleteone' => '刪除這個',
'filehist-revert'    => '復原',
'filehist-user'      => '使用者',
'imagelinks'         => '連結',

# File reversion
'filerevert'                => '復原$1',
'filerevert-legend'         => '復原檔案',
'filerevert-intro'          => '<span class="plainlinks">您現正在復原\'\'\'[[Media:$1|$1]]\'\'\'到[$4 於$2 $3的版本]。</span>',
'filerevert-defaultcomment' => '已經復原到於$1 $2的版本',
'filerevert-submit'         => '復原',
'filerevert-success'        => '<span class="plainlinks">\'\'\'[[Media:$1|$1]]\'\'\'已經復原到[$4 於$2 $3的版本]。</span>',

# File deletion
'filedelete-intro'      => "您現正刪除'''[[Media:$1|$1]]'''。",
'filedelete-intro-old'  => '<span class="plainlinks">你現正刪除\'\'\'[[Media:$1|$1]]\'\'\'於[$4 $2 $3]的版本。</span>',
'filedelete-comment'    => '註解:',
'filedelete-nofile'     => "'''$1'''在這個網站中不存在。",
'filedelete-nofile-old' => "在已指定屬性的情況下，這裡沒有'''$1'''於 $2 $3 的版本。",

# MIME search
'mimesearch' => 'MIME 搜尋',

# Unused templates
'unusedtemplatestext' => '本頁面列出模板名字空間下所有未被其他頁面使用的頁面。請在刪除這些模板前檢查其他鏈入該模板的頁面。',

# Random page
'randompage-nopages' => '在這個名字空間中沒有頁面。',

# Random redirect
'randomredirect-nopages' => '在這個名字空間中沒有重定向頁面。',

# Statistics
'statistics-header-users' => '使用者統計',

'disambiguations'      => '消歧義',
'disambiguations-text' => '以下的頁面都有到<b>消歧義頁</b>的鏈接,
但它們應該是連到適當的標題。<br />
個頁面會被視為消含糊頁如果它是連自[[MediaWiki:Disambiguationspage]]。',

'withoutinterwiki-summary' => '以下的頁面是未有語言鏈接到其它語言版本:',

# Miscellaneous special pages
'lonelypagestext'     => '以下頁面尚未被這個wiki中的其它頁面連結。',
'uncategorizedimages' => '待分類圖片',
'unusedimages'        => '未使用圖片',
'popularpages'        => '熱門頁面',
'mostimages'          => '最多連結圖片',
'prefixindex'         => '前綴索引',
'deadendpagestext'    => '以下頁面沒有連結到這個wiki中的其它頁面。',
'listusers'           => '使用者列表',
'newpages-username'   => '使用者帳號:',
'unusedimagestext'    => '請注意其它網站可能直接透過 URL 連結此圖片，所以這裡列出的圖片有可能依然被使用。',
'notargettext'        => '您還沒有指定一個目標頁面或使用者以進行此項操作。',

# Special:Log
'specialloguserlabel' => '使用者:',
'alllogstext'         => '綜合顯示上傳、刪除、保護、封鎖以及站務日誌。',

# Special:Categories
'categoriespagetext' => '以下列出所有的頁面分類。
[[Special:UnusedCategories|Unused categories]] are not shown here.
Also see [[Special:WantedCategories|wanted categories]].',

# Special:ListUsers
'listusersfrom'      => '給定顯示使用者條件:',
'listusers-noresult' => '找不到使用者。',

# Special:ActiveUsers
'activeusers-hidebots' => '隱藏機器人',

# E-mail user
'mailnologin'     => '無E-mail地址',
'mailnologintext' => '您必須先[[Special:UserLogin|登入]]
並在[[Special:Preferences|偏好設定]]
中有一個有效的e-mail地址才可以E-mail其他使用者。',
'emailuser'       => 'E-mail該使用者',
'emailpage'       => 'E-mail使用者',
'emailpagetext'   => '如果該使用者已經在他或她的偏好設定頁中輸入了有效的e-mail地址，以下的表格將寄一個訊息給該使用者。您在您偏好設定中所輸入的e-mail地址將出現在郵件「發件人」一欄中，這樣該使用者就可以回覆您。',
'noemailtext'     => '該使用者還沒有指定一個有效的e-mail地址，
或者選擇不接受來自其他使用者的e-mail。',
'emailfrom'       => '發件人',
'emailto'         => '收件人',
'emailsubject'    => '主題',
'emailmessage'    => '訊息',
'emailccme'       => '將我的消息的副本發送一份到我的E-mail信箱。',

# Watchlist
'addedwatchtext'    => "頁面\"[[:\$1]]\"已經被加入到您的[[Special:Watchlist|監視清單]]中。
將來有關此頁面及其討論頁的任何修改將會在那裡列出，
而且還會在[[Special:RecentChanges|近期變動]]中
以'''粗體'''形式列出以使起更容易識別。",
'removedwatchtext'  => '頁面「[[:$1]]」已經從您的監視頁面中移除。',
'watchlist-details' => '不包含討論頁，您的監視列表共有 $1 頁。',

'enotif_impersonal_salutation' => '{{SITENAME}}使用者',
'enotif_anon_editor'           => '匿名使用者$1',
'enotif_body'                  => '親愛的 $WATCHINGUSERNAME,

$PAGEEDITOR 已經在 $PAGEEDITDATE $CHANGEDORCREATED{{SITENAME}}的 $PAGETITLE 頁面，請到 $PAGETITLE_URL 檢視目前版本。

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

回饋和進一步的說明:
{{fullurl:{{MediaWiki:Helppage}}}}',

# Delete
'confirmdeletetext' => '您即將從資料庫中永遠刪除一個頁面或圖片以及其歷史。
請確定您要進行此項操作，並且了解其後果，同時您的行為符合[[{{MediaWiki:Policy-url}}]]。',
'deletedtext'       => '「<nowiki>$1</nowiki>」已經被刪除。
最近刪除的紀錄請參見$2。',
'deletedarticle'    => '已刪除「$1」',
'dellogpagetext'    => '以下是最近刪除的紀錄列表。
所有的時間都是使用伺服器時間。',
'reverted'          => '復原到早期版本',
'deletecomment'     => '原因：',

# Rollback
'rollback'         => '復原',
'rollback_short'   => '復原',
'rollbacklink'     => '復原',
'rollbackfailed'   => '無法復原',
'cantrollback'     => '無法復原編輯；最後的貢獻者是本文的唯一作者。',
'alreadyrolled'    => '無法復原由[[User:$2|$2]] ([[User talk:$2|討論]])進行的[[$1]]的最後編輯；
其他人已經編輯或是復原了該頁。

最後編輯者: [[User:$3|$3]] ([[User talk:$3|討論]]{{int:pipe-separator}}[[Special:Contributions/$3|{{int:contribslink}}]])。',
'editcomment'      => "編輯說明: \"''\$1''\"。",
'revertpage'       => '復原由[[Special:Contributions/$2|$2]] ([[User talk:$2|對話]])的編輯；更改回[[User:$1|$1]]的最後一個版本',
'rollback-success' => '復原由$1的編輯；更改回$2的最後一個版本。',

# Protect
'protect-title'               => '正在保護"$1"',
'protect-locked-blocked'      => "您不能在被封鎖時更改保護級別。
以下是'''$1'''現時的保護級別:",
'protect-locked-access'       => "您的帳號權限不能修改保護級別。
以下是'''$1'''現時的保護級別:",
'protect-cascadeon'           => '以下的{{PLURAL:$1|一個|多個}}頁面包含著本頁面的同時，啟動了連鎖保護，因此本頁面目前也被保護，未能編輯。您可以設定本頁面的保護級別，但這並不會對連鎖保護有所影響。',
'protect-default'             => '(預設)',
'protect-level-autoconfirmed' => '禁止未註冊使用者',
'protect-expiry-options'      => '2小時:2 hours,1天:1 day,1周:1 week,2周:2 weeks,1個月:1 month,3個月:3 months,6個月:6 months,1年:1 year,永久:infinite',
'maximum-size'                => '最大大小',

# Undelete
'undelete'                     => '復原被刪頁面',
'undeletepage'                 => '瀏覽及復原被刪頁面',
'undeletepagetext'             => '以下頁面已經被刪除，但依然在檔案中並可以被復原。
檔案庫可能被定時清理。',
'undeleteextrahelp'            => "復原整個頁面時，請清除所有複選框後按 '''''復原''''' 。 復原特定版本時，請選擇相應版本前的複選框後按'''''復原''''' 。按 '''''重設''''' 將清除評論內容及所有複選框。",
'undeletehistory'              => '如果您復原了該頁面，所有版本都會被復原到修訂沿革中。
如果本頁刪除後有一個同名的新頁面建立，被復原的版本將會稱為較新的歷史。',
'undeleterevdel'               => '如果把最新修訂部份刪除，反刪除便無法進行。如果遇到這種情況，您必須反選或反隱藏最新已刪除的修訂。對於您沒有權限去檢視的修訂是無法復原的。',
'undeletehistorynoadmin'       => '這個頁面已經被刪除，刪除原因顯示在下方編輯摘要中。被刪除前的所有修訂版本，連同刪除前貢獻使用者等等細節只有管理員可以看見。',
'undelete-revision'            => '刪除$1時由$3（在$2）所編寫的修訂版本:',
'undeleterevision-missing'     => '此版本的內容不正確或已經遺失。可能連結錯誤、被移除或已經被復原。',
'undeletebtn'                  => '復原',
'undeletedarticle'             => '已經復原「$1」',
'undeletedrevisions'           => '$1個修訂版本已經復原',
'undeletedrevisions-files'     => '$1 個版本和 $2 個檔案被復原',
'undeletedfiles'               => '$1 個檔案被復原',
'cannotundelete'               => '復原失敗；可能之前已經被其他人復原。',
'undeletedpage'                => "'''$1已經被復原''' 請參考[[Special:Log/delete|刪除日誌]]來查詢刪除及復原記錄。",
'undelete-missing-filearchive' => '由於檔案存檔 ID $1 不在資料庫中，不能在檔案存檔中復原。它可能已經反刪除了。',

# Contributions
'contributions' => '使用者編修記錄',
'mycontris'     => '我的編修記錄',
'contribsub2'   => '$1的編修記錄 ($2)',
'uctop'         => ' (最新修改)',

'sp-contributions-newbies'    => '只顯示新建立之使用者的編修記錄',
'sp-contributions-blocklog'   => '封鎖記錄',
'sp-contributions-userrights' => '使用者權限管理',
'sp-contributions-username'   => 'IP位址或使用者名稱：',

# What links here
'whatlinkshere-title' => '鏈接到$1的頁面',

# Block/unblock
'blockip'                     => '封鎖使用者',
'ipadressorusername'          => 'IP地址或使用者名:',
'ipbreason-dropdown'          => '*一般的封鎖理由
** 屢次增加不實資料
** 刪除頁面內容
** 外部連結廣告
** 在頁面中增加無意義文字
** 無禮的行為、攻擊／騷擾別人
** 濫用多個帳號
** 不能接受的使用者名',
'ipbanononly'                 => '僅阻止匿名使用者',
'ipbcreateaccount'            => '阻止建立新帳號',
'ipbemailban'                 => '阻止使用者傳送E-mail',
'ipbenableautoblock'          => '自動封鎖此使用者最後所用的IP位址，以及後來試圖編輯所用的所有位址',
'ipbsubmit'                   => '封鎖該地址',
'ipbhidename'                 => '在封鎖日誌、活躍封鎖列表以及使用者列表中隱藏使用者名／IP',
'blockipsuccesssub'           => '封鎖成功',
'blockipsuccesstext'          => '[[Special:Contributions/$1|$1]]已經被封鎖。
<br />參看[[Special:IPBlockList|被封IP地址列表]]以覆審封鎖。',
'ipb-edit-dropdown'           => '編輯封鎖原因',
'ipb-unblock-addr'            => '解除封鎖$1',
'ipb-unblock'                 => '解除禁封使用者名或IP地址',
'ipb-blocklist'               => '檢視現有的封鎖',
'unblockip'                   => '解除禁封IP地址',
'unblockiptext'               => '用下面的表單來復原先前被禁封的IP地址的書寫權。',
'ipusubmit'                   => '解除禁封',
'unblocked'                   => '[[User:$1|$1]] 的封鎖已經解除。',
'unblocked-id'                => '封鎖 $1 已經被移除',
'ipblocklist-legend'          => '搜尋一位已經被封鎖的使用者',
'ipblocklist-username'        => '使用者名稱或IP地址:',
'anononlyblock'               => '僅限匿名使用者',
'noautoblockblock'            => '禁用自動封鎖',
'createaccountblock'          => '禁止建立帳號',
'ipblocklist-empty'           => '封鎖列表為空。',
'ipblocklist-no-results'      => '所要求的IP地址/使用者名沒有被封鎖。',
'blocklink'                   => '禁封',
'blocklogentry'               => '[[$1]]已被封鎖 $3 ，終止時間為$2',
'blocklogtext'                => '這是關於使用者封鎖和解除封鎖操作的記錄。被自動封鎖的IP地址沒有被列出。請參閱[[Special:IPBlockList|被封鎖的IP地址和使用者列表]]。',
'block-log-flags-anononly'    => '僅限匿名使用者',
'block-log-flags-nocreate'    => '禁止此IP/使用者建立新帳號',
'block-log-flags-noautoblock' => '停用自動封鎖',
'range_block_disabled'        => '只有管理員才能建立禁止封鎖的範圍。',
'ipb_cant_unblock'            => '錯誤: 找不到封鎖ID$1。可能已經解除封鎖。',
'ipb_blocked_as_range'        => '錯誤: 該IP $1 無直接封鎖，不可以解除封鎖。但是它是在 $2 的封鎖範圍之內，該段範圍是可以解除封鎖的。',
'blockme'                     => '封鎖我',
'sorbsreason'                 => '您的IP位址被 DNSBL列為屬於開放代理服務器.',
'sorbs_create_account_reason' => '由於您的IP位址被 DNSBL列為屬於開放代理服務器，所以您無法建立帳號。',

# Developer tools
'lockdbtext'   => '鎖住資料庫將禁止所有使用者進行編輯頁面、更改參數、編輯監視列表以及其他需要更改資料庫的操作。
請確認您的決定，並且保證您在維護工作結束後會重新開放資料庫。',
'unlockdbtext' => '開放資料庫將會復原所有使用者進行編輯頁面、修改參數、編輯監視列表以及其他需要更改資料庫的操作。
請確認您的決定。',

# Move page
'movepagetext'    => "用下面的表單來重新命名一個頁面，並將其修訂沿革同時移動到新頁面。
老的頁面將成為新頁面的重定向頁。
連結到老頁面的連結並不會自動更改；
請檢查雙重或損壞重定向連結。
您應當負責確定所有連結依然會連到指定的頁面。

注意如果新頁面已經有內容的話，頁面將'''不會'''被移動，
除非新頁面無內容或是重定向頁，而且沒有修訂沿革。
這意味著您再必要時可以在移動到新頁面後再移回老的頁面，
同時您也無法覆蓋現有頁面。

<b>警告！</b>
對一個經常被訪問的頁面而言這可能是一個重大與唐突的更改；
請在行動前先了結其所可能帶來的後果。",
'movenologintext' => '您必須是一名登記使用者並且[[Special:UserLogin|登入]]
後才可移動一個頁面。',
'movenotallowed'  => '您在這個wiki中度並沒有許可權去移動頁面。',
'movetalk'        => '如果可能的話，請同時移動對話頁。',
'movelogpagetext' => '以下是已經移動的頁面清單:',
'revertmove'      => '復原該移動',

# Export
'exporttext'      => '您可以將特定頁面或一組頁面的文字以及編輯歷史以 XML 格式匯出；這樣可以將有關頁面透過"[[Special:Import|匯入頁面]]"頁面匯入到另一個運行 MediaWiki 的網站。

要匯出頁面，請在下面的文字框中輸入頁面標題，每行一個標題，
並選擇你是否需要匯出帶有修訂記錄的以前的版本，
或是只選擇匯出帶有最後一次編輯訊息的目前版本。

此外你還可以利用連結匯出檔案，例如你可以使用[[{{#Special:Export}}/{{MediaWiki:Mainpage}}]]匯出"[[{{MediaWiki:Mainpage}}]]"頁面。',
'export-download' => '提供一個檔案以供另存',

# Namespace 8 related
'allmessages'               => '系統介面',
'allmessagestext'           => '這裡列出所有可定製的系統介面。',
'allmessagesnotsupportedDB' => '系統介面功能處於關閉狀態 (wgUseDatabaseMessages)。',

# Thumbnails
'thumbnail_error' => '建立縮圖錯誤: $1',

# Special:Import
'import-interwiki-history'   => '複製此頁的所有歷史版本',
'import-interwiki-namespace' => '將頁面轉移到名字空間:',
'importtext'                 => '請使用 Special:Export 功能從源 wiki 匯出檔案，儲存到您的磁片並上傳到這裡。',
'importfailed'               => '匯入失敗: $1',
'importsuccess'              => '匯入成功﹗',
'importhistoryconflict'      => '存在衝突的修訂沿革(可能在之前已經匯入過此頁面)',

# Tooltip help for the actions
'tooltip-pt-userpage'             => '我的使用者頁',
'tooltip-pt-anonuserpage'         => '您編輯本站所用IP的對應使用者頁',
'tooltip-pt-mytalk'               => '我的對話頁',
'tooltip-pt-preferences'          => '我的偏好設定',
'tooltip-pt-watchlist'            => '我的監視列表',
'tooltip-pt-mycontris'            => '我的編修記錄',
'tooltip-ca-addsection'           => '於本討論頁增加新的討論主題',
'tooltip-ca-history'              => '本頁面的早前版本。',
'tooltip-ca-undelete'             => '將這個頁面復原到被刪除以前的狀態',
'tooltip-p-logo'                  => '首頁',
'tooltip-n-help'                  => '尋求說明',
'tooltip-feed-rss'                => '訂閱本修訂記錄的RSS資訊',
'tooltip-feed-atom'               => '訂閱本修訂記錄的Atom訊息',
'tooltip-t-contributions'         => '檢視該使用者的編修記錄',
'tooltip-t-emailuser'             => '向該使用者發送電子郵件',
'tooltip-t-upload'                => '上傳圖片或多媒體檔',
'tooltip-t-permalink'             => '這個頁面版本的永久連結',
'tooltip-ca-nstab-user'           => '檢視使用者頁',
'tooltip-ca-nstab-image'          => '查詢圖片頁面',
'tooltip-ca-nstab-template'       => '檢視模板',
'tooltip-ca-nstab-help'           => '檢視說明頁面',
'tooltip-ca-nstab-category'       => '檢視分類頁面',
'tooltip-minoredit'               => '標記為細微修改',
'tooltip-compareselectedversions' => '檢視本頁被點選的兩個版本間的差異',
'tooltip-rollback'                => '『{{int:rollbacklink}}』可以一按恢復上一位貢獻者對這個頁面的編輯',
'tooltip-undo'                    => '『{{int:editundo}}』可以在編輯模式上開啟編輯表格以便復原。它容許在摘要中加入原因。',

# Attribution
'anonymous' => '{{SITENAME}}的匿名{{PLURAL:$1|使用者|使用者}}',
'siteuser'  => '{{SITENAME}}使用者$1',
'anonuser'  => '{{SITENAME}}匿名使用者$1',
'siteusers' => '{{SITENAME}}{{PLURAL:$2|使用者|使用者}}$1',
'anonusers' => '{{SITENAME}}匿名{{PLURAL:$2|使用者|使用者}}$1',

# Spam protection
'spamprotectiontext' => '垃圾過濾器禁止保存您剛才提交的頁面，這可能是由於您所加入的外部網站連結所產生的問題。',
'spam_reverting'     => '復原到不包含連結至$1的最近版本',

# Math options
'mw_math_png'    => '永遠使用PNG圖片',
'mw_math_simple' => '如果是簡單的公式使用HTML，否則使用PNG圖片',
'mw_math_html'   => '如果可以用HTML，否則用PNG圖片',

# Patrolling
'markedaspatrolledtext' => '選定的版本已被標記為已檢查.',

# Patrol log
'patrol-log-page' => '巡查記錄',
'patrol-log-line' => '已經標示$1/$2版做已巡查的$3',

# Browsing diffs
'previousdiff' => '←上一個',
'nextdiff'     => '下一個→',

# Media information
'imagemaxsize'         => '在圖片描述頁對圖片大小限制為:',
'file-nohires'         => '<small>無更高解析度可提供。</small>',
'show-big-image'       => '完整解析度',
'show-big-image-thumb' => '<small>這幅縮圖的解析度: $1 × $2 像素</small>',

# Special:NewFiles
'newimages'     => '新建圖片畫廊',
'imagelisttext' => '以下是按$2排列的$1個檔案列表。',
'showhidebots'  => '(機器人$1)',
'noimages'      => '無可檢視圖片。',

# Bad image list
'bad_image_list' => '請根據以下的格式去編寫:

只有列示項目（以 * 開頭的項目）會被考慮。第一個連結一定要連接去壞圖片中。
然後在同一行的連結會考慮作例外，即是幅圖片可以在哪一個頁面中同時顯示。',

/*
Short names for language variants used for language conversion links.
To disable showing a particular link, set it to 'disable', e.g.
'variantname-zh-sg' => 'disable',
Variants for Chinese language
*/
'variantname-zh-tw' => '台灣繁體',

# Metadata
'metadata-help' => '此檔案中包含有擴展的訊息。這些訊息可能是由數位相機或掃描儀在建立或數字化過程中所添加的。

如果此檔案的源檔案已經被修改，一些訊息在修改後的檔案中將不能完全反映出來。',

# EXIF tags
'exif-bitspersample'             => '每像素位元數',
'exif-photometricinterpretation' => '像素合成',
'exif-samplesperpixel'           => '像素數',
'exif-xresolution'               => '水準解析度',
'exif-yresolution'               => '垂直解析度',
'exif-resolutionunit'            => 'X 軸與 Y 軸解析度單位',
'exif-stripoffsets'              => '圖片數據區',
'exif-imagedescription'          => '圖片標題',
'exif-compressedbitsperpixel'    => '圖片壓縮模式',
'exif-pixelydimension'           => '有效圖片寬度',
'exif-pixelxdimension'           => '有效圖片高度',
'exif-usercomment'               => '使用者註釋',
'exif-focalplanexresolution'     => 'X軸焦平面解析度',
'exif-focalplaneyresolution'     => 'Y軸焦平面解析度',
'exif-focalplaneresolutionunit'  => '焦平面解析度單位',
'exif-customrendered'            => '自定義圖片處理',
'exif-imageuniqueid'             => '唯一圖片ID',
'exif-gpsimgdirectionref'        => '圖片方位參照',
'exif-gpsimgdirection'           => '圖片方位',

'exif-lightsource-2'  => '螢光燈',
'exif-lightsource-12' => '日光螢光燈（色溫 D 5700    7100K）',
'exif-lightsource-13' => '日溫白色螢光燈（N 4600    5400K）',
'exif-lightsource-14' => '冷白色螢光燈（W 3900    4500K）',
'exif-lightsource-15' => '白色螢光 （WW 3200    3700K）',

# External editor support
'edit-externally-help' => '請參見[http://www.mediawiki.org/wiki/Manual:External_editors 設定步驟]了解詳細資訊。',

# E-mail address confirmation
'confirmemail'            => '確認電子郵件地址',
'confirmemail_noemail'    => '您沒有在您的[[Special:Preferences|使用者設定]]裡面輸入一個有效的 email 位址。',
'confirmemail_text'       => '此網站要求您在使用郵件功能之前驗證您的電子郵件地址。
點擊以下按鈕可向您的郵箱發送一封確認郵件。該郵件包含有一行代碼連結；
請在您的瀏覽器中加載此連結以確認您的電子郵件地址是有效的。',
'confirmemail_sendfailed' => '不能發送確認郵件，請檢查電子郵件地址是否包含非法字元。

郵件傳送員回應: $1',
'confirmemail_needlogin'  => '您需要$1以確認您的電子郵件地址。',
'confirmemail_success'    => '您的郵箱已經被確認。您現下可以登錄並使用此網站了。',
'confirmemail_loggedin'   => '您的電子郵件地址現下已被確認。',
'confirmemail_subject'    => '{{SITENAME}}電子郵件地址確認',

# Scary transclusion
'scarytranscludefailed'  => '[抱歉，模板$1讀取失敗]',
'scarytranscludetoolong' => '[抱歉，URL 地址太長]',

# Delete conflict
'confirmrecreate' => '在您編輯這個頁面後，使用者[[User:$1|$1]]([[User talk:$1|對話]])以下列原因刪除了這個頁面: $2。請在重新建立頁面前三思。',

# Auto-summaries
'autosumm-blank'   => '移除所有頁面內容',
'autosumm-replace' => "正在將頁面替換為 '$1'",
'autoredircomment' => '正在重定向到 [[$1]]',
'autosumm-new'     => '新頁面: $1',

# Live preview
'livepreview-failed' => '即時預覽失敗! 嘗試標準預覽。',

# Special:Version
'version-parserhooks'           => '語法鉤',
'version-hooks'                 => '鉤',
'version-parser-function-hooks' => '語法函數鉤',
'version-hook-name'             => '鉤名',

);
