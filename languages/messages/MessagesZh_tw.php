<?php
/** Chinese (Taiwan) (‪中文(台灣)‬)
 *
 * @ingroup Language
 * @file
 *
 * @author Alexsh
 * @author BobChao
 * @author Roc michael
 * @author Urhixidur
 * @author לערי ריינהארט
 */

$fallback = 'zh-hant';

$namespaceNames = array(
	NS_MEDIA            => '媒體',
	NS_SPECIAL          => '特殊',
	NS_MAIN             => '',
	NS_TALK             => '討論',
	NS_USER             => '使用者',
	NS_USER_TALK        => '使用者討論',
	# NS_PROJECT set by $wgMetaNamespace
	NS_PROJECT_TALK     => '$1討論',
	NS_FILE             => '圖片',
	NS_FILE_TALK        => '圖片討論',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki_talk',
	NS_TEMPLATE         => '模板',
	NS_TEMPLATE_TALK    => '模板討論',
	NS_HELP             => '使用說明',
	NS_HELP_TALK        => '使用說明討論',
	NS_CATEGORY         => '分類',
	NS_CATEGORY_TALK    => '分類討論'
);

$messages = array(
# User preference toggles
'tog-underline'            => '鏈結標注底線',
'tog-hideminor'            => '近期變動中隱藏細微修改',
'tog-usenewrc'             => '增強版近期變動 (JavaScript)',
'tog-watchcreations'       => '將我建立的頁面加入監視列表',
'tog-watchdefault'         => '將我更改的頁面加入監視列表',
'tog-watchmoves'           => '將我移動的頁面加入監視列表',
'tog-watchdeletion'        => '將我刪除的頁面加入監視列表',
'tog-minordefault'         => '預設將編輯設定為細微修改',
'tog-enotifwatchlistpages' => '當我監視的頁面改變時發電子郵件給我',
'tog-shownumberswatching'  => '顯示監視數目',
'tog-uselivepreview'       => '使用即時預覽 (JavaScript) (試驗中)',
'tog-watchlisthideminor'   => '監視列表中隱藏細微修改',
'tog-ccmeonemails'         => '當我寄電子郵件給其他使用者時，也寄一份複本到我的信箱。',

# Categories related messages
'subcategories'                 => '子分類',

'mainpagetext'      => "<big>'''已成功安裝 MediaWiki!'''</big>",
'mainpagedocfooter' => '請參閱 [http://meta.wikimedia.org/wiki/Help:Contents 使用者手冊] 以獲得使用此 wiki 軟體的訊息！

== 入門 ==

* [http://www.mediawiki.org/wiki/Manual:Configuration_settings MediaWiki 配置設定清單]
* [http://www.mediawiki.org/wiki/Manual:FAQ MediaWiki 常見問題解答]
* [https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce MediaWiki 發佈郵件清單]',

# Metadata in edit box
'help'              => '使用說明',
'search'            => '搜尋',
'history'           => '修訂記錄',
'userpage'          => '查看使用者頁面',
'projectpage'       => '查看計畫頁面',
'mediawikipage'     => '檢視使用者介面訊息',
'lastmodifiedat'    => '本頁最後更動時間在 $1 $2。', # $1 date, $2 time
'jumptosearch'      => '搜尋',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'copyright'            => '本站的文字內容除另有聲明外，全部以 $1 條款授權使用。',
'edithelp'             => '編輯說明',
'faq'                  => '常見問題解答',
'faqpage'              => 'Project:常見問題解答',
'helppage'             => 'Help:目錄',
'portal'               => '社群入口',
'portal-url'           => 'Project:社群入口',

'badaccess-groups' => '您剛才的請求只有{{PLURAL:$2|這個|這些}}使用者組的使用者才能使用: $1',

'thisisdeleted'           => '查看或復原$1?',
'site-rss-feed'           => '訂閱 $1 的 RSS 資料來源',
'site-atom-feed'          => '訂閱 $1 的 Atom 資料來源',
'page-rss-feed'           => '訂閱「$1」的 RSS 資料來源',
'page-atom-feed'          => '訂閱「$1」的 Atom 資料來源',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-user'      => '使用者頁面',
'nstab-project'   => '計畫頁面',
'nstab-image'     => '檔案',
'nstab-mediawiki' => '介面',
'nstab-help'      => '說明頁面',

# General errors
'actionthrottledtext'  => '系統因為反垃圾編輯的考量，禁止如此頻繁地修改資料，請數分鐘後再嘗試。',

# Login and logout pages
'logouttitle'                => '使用者退出',
'logouttext'                 => '您現在已經退出。
您可以繼續以匿名方式使用{{SITENAME}}，或再次以相同或不同使用者身份登入。',
'loginpagetitle'             => '使用者登入',
'yourname'                   => '您的使用者名:',
'externaldberror'            => '這可能是由於驗證資料庫錯誤或您被禁止更新您的外部帳號。',
'login'                      => '登入',
'nav-login-createaccount'    => '登入／建立新帳號',
'userlogin'                  => '登入／建立新帳號',
'nologin'                    => '您還沒有帳號嗎？$1。',
'nologinlink'                => '建立新帳號',
'createaccount'              => '建立新帳號',
'gotaccount'                 => '已經擁有帳號？$1。',
'username'                   => '使用者名:',
'uid'                        => '使用者ID:',
'yournick'                   => '暱稱:',
'badsiglength'               => '暱稱過長；它的長度必須在$1個字元以下。',
'prefs-help-realname'        => '真實姓名是選填的，如果您選擇提供它，那它便用以對您的貢獻署名。',
'prefs-help-email'           => '電子郵件是選填的，但當啟用它後可以在您沒有公開自己的使用者身分時透過您的使用者頁或使用者討論頁與您聯繫。',
'nocookiesnew'               => '已成功建立新帳戶！偵測到您已關閉 Cookies，請開啟它並登入。',
'nocookieslogin'             => '本站利用 Cookies 進行使用者登入，偵測到您已關閉 Cookies，請開啟它並重新登入。',
'noname'                     => '你沒有輸入一個有效的使用者帳號。',
'nosuchuser'                 => '找不到使用者 "$1"。
檢查您的拼寫，或者用下面的表格建立一個新帳號。',
'nosuchusershort'            => '沒有一個名為「<nowiki>$1</nowiki>」的使用者。請檢查您輸入的文字是否有錯誤。',
'nouserspecified'            => '你需要指定一個使用者帳號。',
'passwordtooshort'           => '您的密碼不正確或太短，不能少於$1個字元，而且必須跟使用者名不同。',
'noemail'                    => '使用者"$1"沒有登記電子郵件地址。',
'passwordsent'               => '使用者"$1"的新密碼已經寄往所登記的電子郵件地址。
請在收到後再登入。',
'blocked-mailpassword'       => '由於這個使用者被封鎖，我們暫時禁止您請求申請新密碼。造成不便敬請見諒',
'eauthentsent'               => '一封確認信已經發送到所示的地址。在發送其它郵件到此帳號前，您必須首先依照這封信中的指導確認這個電子郵件信箱真實有效。',
'emailconfirmlink'           => '確認您的電子郵件地址',
'invalidemailaddress'        => '電子郵件地址格式不正確，請輸入正確的電子郵件地址或清空該輸入框。',
'accountcreated'             => '已建立帳號',
'accountcreatedtext'         => '$1的帳號已經被建立。',
'createaccount-title'        => '在{{SITENAME}}中建立新帳號',
'createaccount-text'         => '有人在{{SITENAME}}中為 $2 建立了一個新帳號($4)。 "$2" 的密碼是 "$3" 。您應該立即登入並更改密碼。

如果該帳號建立錯誤的話，您可以忽略此信息。',

# Edit pages
'minoredit'                 => '這是一個細微修改',
'blockedtitle'              => '使用者被封鎖',
'confirmedittext'           => '在編輯此頁之前您必須確認您的電子郵件地址。請透過[[Special:Preferences|參數設定]]設定並驗証您的電子郵件地址。',
'newarticletext'            => '您進入了一個尚未建立的頁面。
要建立該頁面，請在下面的編輯框中輸入內容(詳情參見[[Help:說明|說明]])。
如果您是不小心來到此頁面，直接點擊您瀏覽器中的"返回"按鈕返回。',
'anontalkpagetext'          => "---- ''這是一個還未建立帳號的匿名使用者的對話頁。我們因此只能用IP地址來與他／她聯絡。該IP地址可能由幾名使用者共享。如果您是一名匿名使用者並認為本頁上的評語與您無關，請[[Special:UserLogin|建立新帳號或登入]]以避免在未來於其他匿名使用者混淆。''",
'noarticletext'             => '此頁目前沒有內容，您可以在其它頁[[Special:Search/{{PAGENAME}}|搜尋此頁標題]]或[{{fullurl:{{NAMESPACE}}:{{PAGENAME}}|action=edit}} 編輯此頁]。',
'userpage-userdoesnotexist' => '使用者帳號「$1」未曾建立。請在建立／編輯這個頁面前先檢查一下。',
'token_suffix_mismatch'     => '<strong>由於您使用者端中的編輯信符毀損了一些標點符號字元，為防止編輯的文字損壞，您的編輯已經被拒絕。
這種情況通常出現於使用含有很多臭蟲、以網絡為主的匿名代理服務的時候。</strong>',
'protectedpagewarning'      => '<strong>警告: 本頁已經被保護，只有擁有管理員許可權的使用者才可修改。</strong>',
'semiprotectedpagewarning'  => "'''注意:''' 本頁面被鎖定，僅限註冊使用者編輯。",
'cascadeprotectedwarning'   => '警告: 本頁已經被保護，只有擁有管理員權限的使用者才可修改，因為本頁已被以下連鎖保護的{{PLURAL:$1|一個|多個}}頁面所包含:',
'nocreatetitle'             => '建立頁面受限',

# Account creation failure
'cantcreateaccounttitle' => '無法建立帳號',
'cantcreateaccount-text' => "從這個IP地址 (<b>$1</b>) 建立帳號已經被[[User:$3|$3]]禁止。

當中被$3封鎖的原因是''$2''",

# Revision feed
'history-feed-title'          => '修訂沿革',
'history-feed-description'    => '本站上此頁的修訂沿革',
'history-feed-empty'          => '所請求的頁面不存在。它可能已被刪除或重新命名。
嘗試[[Special:Search|搜尋本站]]獲得相關的新建頁面。',

# Revision deletion
'rev-deleted-user'            => '(使用者名已移除)',
'rev-deleted-text-permission' => '<div class="mw-warning plainlinks">該頁面修訂已經被從公共文件中移除。
在[{{fullurl:Special:Log/delete|page={{PAGENAMEE}}}} 刪除日誌]中您可能會檢視到詳細的訊息。</div>',
'revisiondelete'              => '刪除/復原刪除修訂',
'revdelete-nooldid-text'      => '您沒有指定此操作的目標修訂。',
'revdelete-hide-user'         => '隱藏編輯者的使用者名/IP',

# History merging
'mergehistory'         => '合併修訂記錄',

# Merge log
'mergelogpagetext'   => '以下是一個最近由一個頁面的修訂沿革合併到另一個頁面的列表。',

# Diffs
'history-title'           => '「$1」的修訂沿革',

# Search results
'searchresults'         => '搜尋結果',
'searchresulttext'      => '有關搜尋{{SITENAME}}的更多詳情,參見[[{{MediaWiki:Helppage}}|{{int:help}}]]。',
'noexactmatch'          => "'''沒找到標題為\"\$1\"的頁面。''' 您可以[[:\$1|建立此頁面]]。",

# Preferences page
'preferences'              => '偏好設定',
'mypreferences'            => '我的偏好設定',
'prefs-personal'           => '使用者資料',
'prefs-rc'                 => '近期變動',
'recentchangesdays'        => '近期變動中的顯示日數:',
'recentchangescount'       => '近期變動中的編輯數:',
'savedprefs'               => '您的個人偏好設定已經保存。',
'allowemail'               => '接受來自其他使用者的郵件',
'defaultns'                => '預設搜尋的名字空間',

# User rights
'userrights'               => '使用者權限管理', # Not used as normal message but as header for the special page itself
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
'rightslog'      => '使用者權限日誌',
'rightslogtext'  => '以下記錄了使用者權限的更改記錄。',

# Recent changes
'recentchanges'                     => '近期變動',
'recentchanges-feed-description'    => '跟蹤此訂閱在 wiki 上的近期變動。',
'rcshowhideminor'                   => '$1細微修改',
'rcshowhideliu'                     => '$1具名使用者的編輯',
'rcshowhideanons'                   => '$1匿名使用者的編輯',
'number_of_watching_users_pageview' => '[$1個關注使用者]',

# Recent changes linked
'recentchangeslinked'          => '相關頁面修訂記錄',
'recentchangeslinked-title'    => '$1 內連結頁面的修訂記錄',
'recentchangeslinked-summary'  => "這一個特殊頁面列示這一頁連出頁面的近期變動。在您監視列表中的頁面會以'''粗體'''表示。",

# Upload
'fileexists-thumbnail-yes'    => '這個檔案好像是一幅圖片的縮圖版本<i>(縮圖)</i>。請檢查清楚該檔案<strong><tt>$1</tt></strong>。<br />
如果檢查後的檔案是同原本圖片的大小是一樣的話，就不用再上載多一幅縮圖。',

'upload-proto-error'      => '協訂錯誤',

# Special:ListFiles
'listfiles_user'        => '使用者',

# File description page
'filehist-revert'           => '復原',
'filehist-user'             => '使用者',
'shareduploadwiki'          => '請參閱$1以了解其相關資訊。',

# File reversion
'filerevert'                => '復原$1',
'filerevert-legend'         => '復原檔案',
'filerevert-intro'          => '<span class="plainlinks">您現正在復原\'\'\'[[Media:$1|$1]]\'\'\'到[$4 於$2 $3的版本]。</span>',
'filerevert-defaultcomment' => '已經復原到於$1 $2的版本',
'filerevert-submit'         => '復原',
'filerevert-success'        => '<span class="plainlinks">\'\'\'[[Media:$1|$1]]\'\'\'已經復原到[$4 於$2 $3的版本]。</span>',

# Statistics
'statistics-header-users' => '使用者統計',

'disambiguations'      => '消歧義',
'disambiguations-text' => '以下的頁面都有到<b>消歧義頁</b>的鏈接,
但它們應該是連到適當的標題。<br />
個頁面會被視為消含糊頁如果它是連自[[MediaWiki:Disambiguationspage]]。',

# Miscellaneous special pages
'popularpages'            => '熱門頁面',
'listusers'               => '使用者列表',
'newpages-username'       => '使用者帳號:',

# Special:Log
'specialloguserlabel'  => '使用者:',

# Special:ListUsers
'listusersfrom'      => '給定顯示使用者條件:',
'listusers-noresult' => '找不到使用者。',

# E-mail user
'mailnologin'     => '無E-mail地址',
'mailnologintext' => '您必須先[[Special:UserLogin|登入]]
並在[[Special:Preferences|偏好設定]]
中有一個有效的e-mail地址才可以E-mail其他使用者。',
'emailuser'       => 'E-mail該使用者',
'emailpage'       => 'E-mail使用者',
'emailpagetext'   => '如果該使用者已經在他或她的偏好設定頁中輸入了有效的e-mail地址，以下的表格將寄一個訊息給該使用者。您在您偏好設定中所輸入的e-mail地址將出現在郵件「發件人」一欄中，這樣該使用者就可以回覆您。',
'emailccme'       => '將我的消息的副本發送一份到我的E-mail信箱。',
'emailsent'       => '電子郵件已發送',

# Watchlist
'addedwatchtext'       => "頁面\"[[:\$1]]\"已經被加入到您的[[Special:Watchlist|監視清單]]中。
將來有關此頁面及其討論頁的任何修改將會在那裡列出，
而且還會在[[Special:RecentChanges|近期變動]]中
以'''粗體'''形式列出以使起更容易識別。",

# Displayed when you click the "watch" button and it is in the process of watching
'enotif_impersonal_salutation' => '{{SITENAME}}使用者',
'enotif_anon_editor'           => '匿名使用者$1',

# Delete
'reverted'              => '復原到早期版本',

# Rollback
'rollback'         => '復原',
'rollback_short'   => '復原',
'rollbacklink'     => '復原',
'rollbackfailed'   => '無法復原',
'cantrollback'     => '無法復原編輯；最後的貢獻者是本文的唯一作者。',
'alreadyrolled'    => '無法復原由[[User:$2|$2]] ([[User talk:$2|討論]])進行的[[$1]]的最後編輯；
其他人已經編輯或是復原了該頁。

最後編輯者: [[User:$3|$3]] ([[User talk:$3|討論]] | [[Special:Contributions/$3|{{int:contribslink}}]])。',
'revertpage'       => '復原由[[Special:Contributions/$2|$2]] ([[User talk:$2|對話]])的編輯；更改回[[User:$1|$1]]的最後一個版本', # Additionally available: $3: revid of the revision reverted to, $4: timestamp of the revision reverted to, $5: revid of the revision reverted from, $6: timestamp of the revision reverted from
'rollback-success' => '復原由$1的編輯；更改回$2的最後一個版本。',

# Undelete
'undelete'                     => '復原被刪頁面',
'undeletepage'                 => '瀏覽及復原被刪頁面',
'undeleteextrahelp'            => "復原整個頁面時，請清除所有複選框後按 '''''復原''''' 。 復原特定版本時，請選擇相應版本前的複選框後按'''''復原''''' 。按 '''''重設''''' 將清除評論內容及所有複選框。",
'undeletehistory'              => '如果您復原了該頁面，所有版本都會被復原到修訂沿革中。
如果本頁刪除後有一個同名的新頁面建立，被復原的版本將會稱為較新的歷史。',
'undeletebtn'                  => '復原',
'undeletedarticle'             => '已經復原「$1」',
'undeletedrevisions'           => '$1個修訂版本已經復原',
'undeletedrevisions-files'     => '$1 個版本和 $2 個檔案被復原',
'undeletedfiles'               => '$1 個檔案被復原',
'cannotundelete'               => '復原失敗；可能之前已經被其他人復原。',
'undeletedpage'                => "<big>'''$1已經被復原'''</big> 請參考[[Special:Log/delete|刪除日誌]]來查詢刪除及復原記錄。",

# Contributions
'contributions' => '使用者編修記錄',
'mycontris'     => '我的編修記錄',
'contribsub2'   => '$1的編修記錄 ($2)',

'sp-contributions-newbies'     => '只顯示新建立之使用者的編修記錄',
'sp-contributions-newbies-sub' => '新手',
'sp-contributions-blocklog'    => '封鎖記錄',
'sp-contributions-username'    => 'IP位址或使用者名稱：',

# Block/unblock
'blockip'                     => '封鎖使用者',
'ipadressorusername'          => 'IP地址或使用者名:',
'ipbanononly'                 => '僅阻止匿名使用者',
'ipbcreateaccount'            => '阻止建立新帳號',
'ipbemailban'                 => '阻止使用者傳送E-mail',
'ipbenableautoblock'          => '自動封鎖此使用者最後所用的IP位址，以及後來試圖編輯所用的所有位址',
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
'unblocked'                   => '[[User:$1|$1]] 的封鎖已經解除。',
'unblocked-id'                => '封鎖 $1 已經被移除',
'ipblocklist-legend'          => '搜尋一位已經被封鎖的使用者',
'ipblocklist-username'        => '使用者名稱或IP地址:',
'anononlyblock'               => '僅限匿名使用者',
'noautoblockblock'            => '禁用自動封鎖',
'ipblocklist-empty'           => '封鎖列表為空。',
'ipblocklist-no-results'      => '所要求的IP地址/使用者名沒有被封鎖。',
'blocklogentry'               => '[[$1]]已被封鎖 $3 ，終止時間為$2',
'blocklogtext'                => '這是關於使用者封鎖和解除封鎖操作的記錄。被自動封鎖的IP地址沒有被列出。請參閱[[Special:IPBlockList|被封鎖的IP地址和使用者列表]]。',
'block-log-flags-anononly'    => '僅限匿名使用者',
'block-log-flags-nocreate'    => '禁止此IP/使用者建立新帳號',
'block-log-flags-noautoblock' => '停用自動封鎖',
'range_block_disabled'        => '只有管理員才能建立禁止封鎖的範圍。',
'ipb_cant_unblock'            => '錯誤: 找不到封鎖ID$1。可能已經解除封鎖。',
'ipb_blocked_as_range'        => '錯誤: 該IP $1 無直接封鎖，不可以解除封鎖。但是它是在 $2 的封鎖範圍之內，該段範圍是可以解除封鎖的。',
'blockme'                     => '封鎖我',

# Move page
'movepagetext'            => "用下面的表單來重新命名一個頁面，並將其修訂沿革同時移動到新頁面。
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
'articleexists'           => '該名字的頁面已經存在，或者您選擇的名字無效。請再選一個名字。',
'talkexists'              => '頁面本身移動成功，
但是由於新標題下已經有對話頁存在，所以對話頁無法移動。請手工合併兩個頁面。',

# Namespace 8 related
'allmessages'               => '系統介面',
'allmessagesname'           => '名稱',
'allmessagesdefault'        => '預設的文字',
'allmessagescurrent'        => '當前的文字',

# Tooltip help for the actions
'tooltip-pt-userpage'             => '我的使用者頁',
'tooltip-pt-anonuserpage'         => '您編輯本站所用IP的對應使用者頁',
'tooltip-pt-preferences'          => '我的偏好設定',
'tooltip-pt-mycontris'            => '我的編修記錄',
'tooltip-ca-undelete'             => '將這個頁面復原到被刪除以前的狀態',
'tooltip-t-contributions'         => '查看該使用者的編修記錄',
'tooltip-t-emailuser'             => '向該使用者發送電子郵件',
'tooltip-ca-nstab-help'           => '查看說明頁面',
'tooltip-minoredit'               => '標記為細微修改',

# Attribution
'anonymous'        => '{{SITENAME}}的匿名{{PLURAL:$1|使用者|使用者}}',
'siteuser'         => '{{SITENAME}}使用者$1',
'siteusers'        => '{{SITENAME}}{{PLURAL:$2|使用者|使用者}}$1',

# Patrol log
'patrol-log-page' => '巡查記錄',
'patrol-log-line' => '已經標示$1/$2版做已巡查的$3',

# Special:NewFiles
'newimages'     => '新建圖片畫廊',

# EXIF tags
'exif-bitspersample'               => '每像素位元數',
'exif-usercomment'                 => '使用者註釋',
'exif-focalplanexresolution'       => 'X軸焦平面解析度',
'exif-focalplaneyresolution'       => 'Y軸焦平面解析度',

# E-mail address confirmation
'confirmemail'            => '確認電子郵件地址',
'confirmemail_noemail'    => '您沒有在您的[[Special:Preferences|使用者設定]]裡面輸入一個有效的 email 位址。',
'confirmemail_text'       => '此網站要求您在使用郵件功能之前驗證您的電子郵件地址。
點擊以下按鈕可向您的郵箱發送一封確認郵件。該郵件包含有一行代碼連結；
請在您的瀏覽器中加載此連結以確認您的電子郵件地址是有效的。',
'confirmemail_pending'    => '一個確認代碼已經被發送到您的郵箱，您可能需要等幾分鐘才能收到。如果無法收到，請在申請一個新的確認碼！',
);
