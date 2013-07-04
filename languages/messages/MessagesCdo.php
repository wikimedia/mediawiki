<?php
/** Min Dong Chinese (Mìng-dĕ̤ng-ngṳ̄)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Xqt
 * @author Yejianfei
 */

$datePreferences = array(
	'default',
	'ISO 8601',
);
$defaultDateFormat = 'cdo';
$dateFormats = array(
	'cdo time' => 'H:i',
	'cdo date' => 'Y "nièng" n "nguŏk" j "hô̤" (l)',
	'cdo both' => 'Y "nièng" n "nguŏk" j "hô̤" (D) H:i',
);

$messages = array(
# User preference toggles
'tog-rememberpassword' => 'Giéu cī gá diêng-nō̤ gé diâng nguāi gì dióng-hô̤ gâe̤ng mĭk-mā (for a maximum of $1 {{PLURAL:$1|day|days}})',
'tog-watchcreations' => 'Gă-tiĕng nguāi kŭi gì hiĕk-miêng gáu nguāi gì gáng-sê-dăng',
'tog-watchdefault' => '添加我編輯其頁面共文件遘我其監視單',
'tog-watchmoves' => '添加我移動其頁面共文件遘我其監視單',
'tog-watchdeletion' => '添加我刪掉其頁面共文件遘我其監視單',
'tog-minordefault' => '默認共所有其編輯都當作過要修改',
'tog-previewontop' => '敆編輯框以前顯示預覽',
'tog-previewonfirst' => '敆頭蜀回編輯時候看預覽',
'tog-nocache' => '無讓瀏覽器頁面緩存',
'tog-enotifwatchlistpages' => '我其監視單有變時候，發電子郵件乞我',
'tog-enotifusertalkpages' => '我其討論頁有變時候，發電子郵件乞我',
'tog-enotifminoredits' => '即使是過要編輯，也著發電子郵件乞我',
'tog-shownumberswatching' => '顯示監視用戶其數量',
'tog-oldsig' => '存在其簽名',
'tog-fancysig' => '共簽名當成維基文本（無自動鏈接）',
'tog-showjumplinks' => '允許「跳遘」可訪問其鏈接',
'tog-uselivepreview' => '使即時預覽（需要JavaScript）（敆𡅏實驗）',
'tog-forceeditsummary' => '提醒我行遘蜀萆空白其編輯總結',
'tog-watchlisthideown' => 'Káung kī gáng-sê-dăng gà̤-dēng nguāi cê-gă gì siŭ-gāi',
'tog-watchlisthidebots' => 'Káung kī gáng-sê-dăng gà̤-dēng gì gĭ-ké-nè̤ng siŭ-gāi',
'tog-watchlisthideminor' => 'Káung kī gáng-sê-dăng gà̤-dēng gì guó-éu siŭ-gāi',
'tog-watchlisthideliu' => '共已經躒底其用戶其編輯趁監視單𡅏藏起咯',
'tog-watchlisthideanons' => '共匿名其用戶其編輯趁監視單𡅏藏起咯',
'tog-watchlisthidepatrolled' => '共巡查其編輯趁監視單𡅏藏起咯',
'tog-ccmeonemails' => 'Gié bĕk-nè̤ng diêng-piĕ sèng-âu iâ hók-cié siŏh hông gié ké̤ṳk nguāi cê-gă',
'tog-diffonly' => '伓使敆下底其顯示𣍐蜀様其地方顯示頁面內容',
'tog-showhiddencats' => '㪗藏類別',
'tog-norollbackdiff' => '敆回滾其時候，無叕𣍐蜀様其地方',
'tog-useeditwarning' => '我編輯頁面其時候離開，起動警告我蜀下',

'underline-always' => '直頭',
'underline-never' => '頭𡅏無',
'underline-default' => '皮膚或者瀏覽器默認其',

# Font style option in Special:Preferences
'editfont-style' => '編輯框其字體其樣式',
'editfont-default' => '瀏覽器默認',
'editfont-monospace' => '蜀様寬其字體',
'editfont-sansserif' => '無襯線其字體',
'editfont-serif' => '有襯線其字體',

# Dates
'sunday' => '禮拜',
'monday' => '拜一',
'tuesday' => '拜二',
'wednesday' => '拜三',
'thursday' => '拜四',
'friday' => '拜五',
'saturday' => '拜六',
'sun' => '禮拜',
'mon' => '拜一',
'tue' => '拜二',
'wed' => '拜三',
'thu' => '拜四',
'fri' => '拜五',
'sat' => '拜六',
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
'jan' => '一月',
'feb' => '二月',
'mar' => '三月',
'apr' => '四月',
'may' => '五月',
'jun' => '六月',
'jul' => '七月',
'aug' => '八月',
'sep' => '九月',
'oct' => '十月',
'nov' => '十一月',
'dec' => '十二月',
'january-date' => '一月$1號',
'february-date' => '二月$1號',
'march-date' => '三月$1號',
'april-date' => '四月$1號',
'may-date' => '五月$1號',
'june-date' => '六月$1號',
'july-date' => '七月$1號',
'august-date' => '八月$1號',
'september-date' => '九月$1號',
'october-date' => '十月$1號',
'november-date' => '十一月$1號',
'december-date' => '十二月$1號',

# Categories related messages
'pagecategories' => '類別',
'category_header' => '「$1」類別下底其頁面',
'subcategories' => '子類別',
'category-media-header' => '「$1」類別下底其媒體',
'category-empty' => "''茲類別下底現在無文章也無媒體。''",
'hidden-categories' => '共類別藏起咯',
'hidden-category-category' => '已經藏起其類別',
'category-subcat-count-limited' => '茲蜀萆類別下底有子類別',
'category-article-count' => '{{PLURAL:$2|茲蜀萆類別儷有下底蜀頁。|共總有$2頁，下底其茲$1頁敆茲蜀萆類別𡅏。}}',
'category-article-count-limited' => '下底$1頁敆茲蜀萆類別𡅏',
'category-file-count' => '茲蜀萆類別共總有$2萆文件，下底茲$1萆文件都敆茲蜀萆類別𡅏。',
'category-file-count-limited' => '下底其茲$1萆文件都敆茲蜀萆類別𡅏。',
'listingcontinuesabbrev' => '（繼續前斗）',
'index-category' => '索引其頁面',
'noindex-category' => '未索引其頁面',
'broken-file-category' => '獃其文件鏈接其頁面',

'about' => '關於',
'article' => '文章',
'newwindow' => '（敆新窗口打開）',
'cancel' => '取消',
'moredotdotdot' => '更価...',
'morenotlisted' => '固有未列出其',
'mypage' => '頁面',
'mytalk' => '我其討論',
'anontalk' => '茲隻IP其討論頁',
'navigation' => '引導',
'and' => '&#32;and',

# Cologne Blue skin
'qbfind' => '討',
'qbbrowse' => '覷蜀覷',
'qbedit' => '修改',
'qbpageoptions' => '茲蜀頁',
'qbmyoptions' => '我其頁面',
'qbspecialpages' => '特殊頁',
'faq' => '經稠碰著其問題',
'faqpage' => '經稠碰著其問題',

# Vector skin
'vector-action-addsection' => '加話題',
'vector-action-delete' => '刪掉咯',
'vector-action-move' => '移動',
'vector-action-protect' => '保護',
'vector-action-undelete' => '取消刪除',
'vector-action-unprotect' => '改變保護',
'vector-simplesearch-preference' => '允許簡化其搜索欄（儷有矢量皮膚才有）',
'vector-view-create' => '創建',
'vector-view-edit' => '修改',
'vector-view-history' => '看歷史',
'vector-view-view' => '讀',
'vector-view-viewsource' => '看源代碼',
'actions' => '動作',
'namespaces' => '命名空間',
'variants' => '變體',

'navigation-heading' => '導航菜單',
'errorpagetitle' => '鄭咯',
'returnto' => '轉去$1。',
'tagline' => '來源：{{SITENAME}}',
'help' => '幫助',
'search' => '討',
'searchbutton' => '討',
'go' => '去',
'searcharticle' => '去',
'history' => '頁面歷史',
'history_short' => '歷史',
'updatedmarker' => '趁我最後蜀回訪問開始更新',
'printableversion' => '會拍印其版本',
'permalink' => '永久鏈接',
'print' => '拍印',
'view' => '覷蜀覷',
'edit' => '修改',
'create' => '創建',
'editthispage' => '修改茲頁',
'create-this-page' => '創建茲蜀頁',
'delete' => '刪除',
'deletethispage' => '刪除茲頁',
'undeletethispage' => '恢復茲蜀頁',
'undelete_short' => '恢復$1回修改',
'viewdeleted_short' => '覷蜀覷$1回刪掉其修改',
'protect' => '保護',
'protect_change' => '改變',
'protectthispage' => '保護茲蜀頁',
'unprotect' => '改變保護其狀態',
'unprotectthispage' => '改變茲蜀頁其保護狀態',
'newpage' => '新頁',
'talkpage' => '討論茲頁',
'talkpagelinktext' => '討論',
'specialpage' => '特殊頁',
'personaltools' => '個人其家私',
'postcomment' => '新其蜀段',
'articlepage' => '覷蜀覷內容頁面',
'talk' => '討論',
'views' => '覷蜀覷',
'toolbox' => '家私',
'userpage' => '覷蜀覷用戶頁面',
'projectpage' => '看工程頁',
'imagepage' => '覷蜀覷文件頁面',
'mediawikipage' => '看消息頁',
'templatepage' => '看模板頁',
'viewhelppage' => '看幫助頁',
'categorypage' => '看分類頁',
'viewtalkpage' => '看討論',
'otherlanguages' => '其它其語言',
'redirectedfrom' => '（由$1重定向過來）',
'redirectpagesub' => '重定向頁',
'lastmodifiedat' => '茲頁面是著$2, $1時候修改其。',
'viewcount' => '茲蜀頁已經乞訪問$1回了。',
'protectedpage' => '保護頁',
'jumpto' => '跳遘：',
'jumptonavigation' => '引導：',
'jumptosearch' => '討：',
'view-pool-error' => '對不住，服務器茲蜀萆時候已弳過載了。
過価用戶敆𡅏覷茲蜀頁。
起動等仂久再來覷茲蜀頁。

$1',
'pool-timeout' => '等待鎖定其時間遘了',
'pool-queuefull' => '隊列池已經滿了',
'pool-errorunknown' => '𣍐八什乇鄭咯',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite' => '關於{{SITENAME}}',
'aboutpage' => 'Project:關於',
'copyright' => '內容敆$1下底會使獲得。',
'copyrightpage' => '{{ns:project}}：版權',
'currentevents' => '大樹下',
'currentevents-url' => 'Project:大樹下',
'disclaimers' => '無負責聲明',
'disclaimerpage' => 'Project:無負責聲明',
'edithelp' => '修改保護',
'helppage' => 'Help:目錄',
'mainpage' => '頭頁',
'mainpage-description' => '頭頁',
'policy-url' => 'Project:政策',
'portal' => '廳中',
'portal-url' => '工程：社區門戶',
'privacy' => '隱私政策',
'privacypage' => 'Project:隱私政策',

'badaccess' => '權限錯誤',
'badaccess-group0' => '汝𣍐使做汝要求其茲蜀萆動作。',
'badaccess-groups' => '汝要求其動作著$2底裏用戶才會做其：$1',

'versionrequired' => '需要版本$1其媒體維基',
'versionrequiredtext' => '需要媒體維基其版本$1來使茲蜀頁。
覷[[Special:Version|版本頁面]]。',

'ok' => '好',
'retrievedfrom' => '趁「$1」退過來',
'youhavenewmessages' => '汝有$1（$2）。',
'newmessageslink' => '新信息',
'newmessagesdifflink' => '最後其改變',
'youhavenewmessagesfromusers' => '汝有趁$3用戶（$2）來其$1萆信息',
'youhavenewmessagesmanyusers' => '汝有趁雅価用戶（$2）其$1信息',
'newmessageslinkplural' => '$1條新其信息',
'newmessagesdifflinkplural' => '最後其改變',
'youhavenewmessagesmulti' => '汝有趁$1來其新信息',
'editsection' => '修改',
'editold' => '修改',
'viewsourceold' => '看源代碼',
'editlink' => '修改',
'viewsourcelink' => '看源代碼',
'editsectionhint' => '修改段：$1',
'toc' => '目錄',
'showtoc' => '顯示',
'hidetoc' => '藏起',
'collapsible-collapse' => '崩潰',
'collapsible-expand' => '擴展',
'thisisdeleted' => '卜看或者恢復$1？',
'viewdeleted' => '看$1？',
'restorelink' => '$1萆乞刪掉其修改',
'feedlinks' => '訂閱：',
'feed-invalid' => '無乇使其下標填充類型',
'feed-unavailable' => '𣍐使聚合訂閱',
'site-rss-feed' => '$1 RSS 訂閱',
'site-atom-feed' => '$1原子訂閱',
'page-rss-feed' => '「$1」RSS訂閱',
'page-atom-feed' => '「$1」原子訂閱',
'red-link-title' => '$1（頁面無敆𡅏）',
'sort-descending' => '降序排序',
'sort-ascending' => '升序排序',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main' => '頁面',
'nstab-user' => '用戶頁',
'nstab-media' => '媒體頁',
'nstab-special' => '特殊頁面',
'nstab-project' => '工程頁',
'nstab-image' => '文件',
'nstab-mediawiki' => '消息',
'nstab-template' => '模板',
'nstab-help' => '幫助頁',
'nstab-category' => '類別',

# Main script and global functions
'nosuchaction' => '無茲蜀種行動',
'nosuchactiontext' => '茲蜀種URL指定其行動是𣍐合法其。',
'nosuchspecialpage' => '無總款其特殊頁',
'nospecialpagetext' => '<strong>汝請求蜀萆𣍐合法其特殊頁面。</strong>

合法其特殊頁面清單會使敆[[Special:SpecialPages|{{int:特殊頁面}}]]頁面討著',

# General errors
'error' => '鄭咯',
'databaseerror' => '數據庫有鄭',
'dberrortext' => '蜀萆數據庫查詢其語法錯誤發生咯。
茲可能代表茲軟件其蜀萆漏洞。
最後嘗試其數據庫查詢是：
<blockquote><code>$1</code></blockquote>
趁函數「<code>$2</code>」來其。
數據庫返回錯誤「<samp>$3: $4</samp>」。',
'dberrortextcl' => '蜀萆數據庫查詢語法錯誤發生咯。
最後蜀回嘗試其數據庫查詢是：
「$1」
趁函數「$2」來其。
數據庫返回錯誤「$3: $4」',
'laggedslavemode' => "'''警告：'''頁面可能無最近其更新。",
'readonly' => '數據庫乞鎖起咯',
'readonlytext' => 'Só-gé̤ṳ-kó cī-buàng ké̤ṳk nè̤ng sō̤ kī lāu, mâ̤-sāi siā sĭng dèu-mĕ̤k hĕ̤k có̤ siŭ-gāi, ô kō̤-nèng sê ôi-lāu nĭk-siòng mì-hô, cĭ-hâiu cêu â̤ ciáng-siòng.

Sō̤ kī só-gé̤ṳ-kó gì guāng-lī-uòng cūng-kuāng gāi-sék: $1',
'missingarticle-diff' => '（對比：$1、$2）',
'internalerror' => '內部錯誤',
'internalerror_info' => '內部錯誤：$1',
'cannotdelete' => '無能耐刪掉頁面或者文件「$1」。
可能茲已經共別儂刪掉咯了。',
'cannotdelete-title' => '無辦法刪掉頁面「$1」',
'delete-hook-aborted' => '刪除乞鉤子拍斷咯。
無給出解釋。',
'badtitle' => '獃其標題',
'perfcached' => '下底其數據乞緩存固加可能伓是最新其。$1條結果會敆緩存臺中討著。',
'perfcachedts' => '下底其數據已經緩存過了，最後更新遘$1。$4條結果會敆緩存臺中討著。',
'querypage-no-updates' => '茲蜀頁其更新乞禁止了。
數據嚽塊現刻時𣍐更新了。',
'wrong_wfQuery_params' => '敆wfQuery()其鄭其參數<br />
函數：$1<br />
查詢：$2',
'viewsource' => '看源代碼',
'viewsource-title' => '覷蜀覷$1其源代碼',
'actionthrottled' => '行動乞取消咯',
'protectedpagetext' => '茲頁已經乞保護起咯，𣍐使修改或者其它行動。',
'viewsourcetext' => '汝會使看共複製茲蜀頁其源代碼：',
'viewyourtext' => "汝會使覷蜀覷或者複製茲頁'''汝其修改'''其源代碼：",
'editinginterface' => "'''警告：'''汝敆𡅏修改其頁面廮𡅏提供茲蜀萆軟件其界面文本。
茲蜀頁其改變會影響遘其它用戶其用戶界面其顯示。
如果蔔想修改維基其翻譯，起動遘媒體維基本地化計劃[//translatewiki.net/wiki/Main_Page?setlang=en translatewiki.net]。",
'sqlhidden' => '（SQL查詢藏起咯）',
'namespaceprotected' => "汝𣍐使修改敆'''$1'''命名空間其頁面。",
'customcssprotected' => '汝𣍐使修改茲蜀萆CSS頁面，因為伊有別蜀隻用戶其設定。',
'customjsprotected' => '汝𣍐使修改茲蜀萆JavaScript頁面，因為伊有別蜀隻用戶其設定。',
'mycustomcssprotected' => '汝𣍐使修改茲蜀萆CSS頁面。',
'mycustomjsprotected' => '汝𣍐使修改茲蜀萆JavaScript頁面。',
'ns-specialprotected' => '𣍐使修改特殊頁面。',
'titleprotected' => "茲蜀萆標題共[[User:$1|$1]]保護其咯。
原因是「''$2''」。",
'exception-nologin' => '未躒底其',
'exception-nologin-text' => '茲蜀頁其行動卜挃汝躒底茲蜀萆維基百科。',

# Virus scanner
'virus-badscanner' => "獃其配置：𣍐八其病毒掃描器：''$1''",
'virus-scanfailed' => '掃描失敗（代碼$1）',
'virus-unknownscanner' => '𣍐八其反病毒：',

# Login and logout pages
'logouttext' => "'''汝現在躒出了。'''

汝會使使無名方式繼續覷{{SITENAME}}，或者汝會使蜀様或者𣍐蜀様其用戶<span class='plainlinks'>[$1 再躒底其]</span>。
注意有其頁面可能繼續顯示真像汝應經躒底其了，除開汝清理汝其瀏覽器緩存。",
'welcomeuser' => '歡迎，$1！',
'welcomecreation-msg' => '汝其賬戶已經開好了。
伓嗵𣍐記改蜀改汝其[[Special:Preferences|{{SITENAME}}設定]]。',
'yourname' => 'Ê̤ṳng-hô-miàng',
'userlogin-yourname' => '用戶名',
'userlogin-yourname-ph' => '輸底汝其用戶名',
'yourpassword' => 'Mĭk-mā',
'userlogin-yourpassword' => '密碼',
'userlogin-yourpassword-ph' => '輸底汝其密碼',
'createacct-yourpassword-ph' => '輸底蜀萆密碼',
'yourpasswordagain' => 'Dṳ̀ng-sĭng páh diē mĭk-mā',
'createacct-yourpasswordagain' => '確定密碼',
'createacct-yourpasswordagain-ph' => '再輸入蜀回密碼',
'remembermypassword' => '共我敆茲蜀萆瀏覽器其躒底記錄記定幾日（最価$1日）',
'userlogin-remembermypassword' => '保持我躒底其',
'userlogin-signwithsecure' => '使安全其連接',
'securelogin-stick-https' => '躒底以後保持HTTPS連接',
'yourdomainname' => '汝其域名：',
'password-change-forbidden' => '汝𣍐使敆茲蜀萆維基百科𡅏修改密碼。',
'externaldberror' => '可能是驗證數據庫鄭咯，或者是汝𣍐使升級汝其外部賬戶。',
'login' => '躒底',
'nav-login-createaccount' => '躒底/開賬戶',
'loginprompt' => 'Páh kŭi cookies ciáh â̤ diē {{SITENAME}}.',
'userlogin' => 'Láuk-diē / kŭi dióng-hô̤',
'userloginnocreate' => '躒底',
'logout' => '躒出',
'userlogout' => '躒出',
'notloggedin' => '未躒底',
'userlogin-noaccount' => '汝無賬戶？',
'userlogin-joinproject' => '共{{SITENAME}}加底其',
'nologin' => '汝無賬戶？$1',
'nologinlink' => '開蜀隻賬戶',
'createaccount' => '開賬戶',
'gotaccount' => "已經有賬戶了？'''$1'''。",
'gotaccountlink' => '躒底',
'userlogin-resetlink' => '躒底其資料𣍐記咯？',
'userlogin-resetpassword-link' => '重置汝其密碼',
'helplogin-url' => 'Help: 躒底',
'userlogin-helplink' => '[[{{MediaWiki:helplogin-url}}|幫助躒底]]',
'createacct-join' => '敆下底輸底汝其信息。',
'createacct-emailrequired' => '電子郵件地址',
'createacct-emailoptional' => '電子郵件地址（愛寫就寫）',
'createacct-email-ph' => '輸底汝其電子郵件地址',
'createaccountmail' => '使臨時其隨機密碼，共伊送遘下底其電子郵件地址',
'createacct-realname' => '實際其名字（愛寫就寫）',
'createaccountreason' => '原因：',
'createacct-reason' => '原因',
'createacct-reason-ph' => '汝奚勢復開蜀隻賬戶',
'createacct-captcha' => '安全檢查',
'createacct-imgcaptcha-ph' => '輸底汝敆懸頂看見其文字',
'createacct-submit' => '開賬戶',
'createacct-benefit-heading' => '{{SITENAME}}是共汝蜀様其儂做其。',
'createacct-benefit-body1' => '修改',
'createacct-benefit-body2' => '頁面',
'createacct-benefit-body3' => '最近其貢獻者',
'badretype' => '汝輸底其密碼𣍐蜀様。',
'userexists' => '用戶名已經乞別人使去了。
起動另外再起蜀萆名字。',
'loginerror' => '躒底有鄭',
'createacct-error' => '賬戶開出毛病咯',
'createaccounterror' => '無能獃開賬戶：$1',
'loginsuccesstitle' => '躒底成功',
'loginsuccess' => "'''汝現在已經「$1」其成功躒底{{SITENAME}}了。'''",
'nosuchuser' => '無總款其用戶名「$1」。
用户名是大小写敏感其。
检查汝其拼写，或者覷蜀覷[[Special:用戶躒底/開戶|開新賬戶]]。',
'nosuchusershort' => '無總款其用戶名「$1」。
檢查汝其拼寫。',
'wrongpassword' => '密碼鄭咯。
起動再查蜀下。',
'wrongpasswordempty' => '未輸入密碼。
請再查蜀下。',
'passwordtooshort' => '密碼著設最少$1萆字符。',
'password-name-match' => '汝其密碼硬著共汝其用戶名𣍐蜀様才會使其。',
'password-login-forbidden' => '茲蜀萆用戶名共密碼應經乞禁止去了。',
'mailmypassword' => '共新密碼發遘電子郵件',
'passwordsent' => '新密碼已經寄遘「$1」註冊其電子郵件地址了。
收遘後，請再躒底蜀頭部。',
'acct_creation_throttle_hit' => '使汝其IP訪問茲蜀萆維基百科訪問者其已經敆最後蜀日創建$1萆賬戶去了。茲蜀段時間最価若允許創建茲滿価萆賬戶。故此講使茲蜀萆IP訪問其儂敆現刻時𣍐使再開賬戶了。',
'emailauthenticated' => 'Nṳ̄ gì diêng-piĕ dê-cī găk $1 sèng-âu káuk-nêng guó lāu.',
'emailconfirmlink' => '確認汝其電子郵件地址',
'accountcreated' => '賬戶創建了',
'accountcreatedtext' => '[[{{ns:User}}:$1|$1]]([[{{ns:User talk}}:$1|talk]])用戶已經創建。',
'loginlanguagelabel' => '語言：$1',

# Change password dialog
'oldpassword' => '舊密碼：',
'newpassword' => '新密碼：',
'retypenew' => '確認密碼：',

# Edit page toolbar
'bold_sample' => '粗體文字',
'bold_tip' => '粗體文字',
'link_sample' => '鏈接標題',
'link_tip' => '內部鏈接',
'extlink_tip' => '外部鏈接（記𡅏http:// 開頭）',
'headline_sample' => '標題文字',
'headline_tip' => '第二等標題',
'media_sample' => 'Liê.ogg',
'media_tip' => '文件鏈接',

# Edit pages
'summary' => '總結：',
'subject' => '主題/標題：',
'minoredit' => '過要修改',
'watchthis' => '監視茲頁',
'savearticle' => '保存茲頁',
'preview' => '預覽',
'showpreview' => '顯示預覽',
'showdiff' => '看改變其部分',
'anoneditwarning' => "'''警告：'''汝未躒底。
汝起IP地址會乞記錄敆茲頁面修改歷史底裏。",
'summary-preview' => '總結預覽：',
'blockedtitle' => '用戶乞封鎖了',
'loginreqtitle' => '需要躒底',
'loginreqlink' => '躒底',
'loginreqpagetext' => '著$1才會使看其它頁面。',
'accmailtitle' => '密碼寄出了',
'accmailtext' => "共「$1」用戶其臨時產生其密碼已經發$2了。

茲蜀萆新其賬戶其密碼會使敆用戶躒底以後著''[[Special:ChangePassword|改密碼]]''頁面𡅏改變。",
'newarticle' => '（新）',
'newarticletext' => '汝已經跟鏈接跟遘無存在其頁面了。
卜想創建頁面，敆下底其框框𡅏拍字（覷蜀覷[[{{MediaWiki:Helppage}}|幫助頁面]]有無更更価其幫助）。
如果汝是無注意來遘茲蜀萆頁面，篤囇汝其瀏覽器上其「返回」按鈕。',
'anontalkpagetext' => "''茲是未躒底其用戶討論頁面。''
故此儂家著使數字IP來確定伊。
總款其IP地址會乞雅価用戶共享。
如果蜀隻未躒底其用戶見覺無關係其評論指向汝，起動[[Special:用戶躒底/開賬戶|開賬戶]]或者[[Special:用戶躒底|躒底]]來避免以後共其它未躒底其用戶混蜀堆。",
'noarticletext' => '現在敆茲蜀頁𡅏無文字。汝會使敆其它其頁面𡅏[[Special:討/{{PAGENAME}}|討蜀討茲蜀萆標題]]，<span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} 討相關其記錄]，或者[{{fullurl:{{FULLPAGENAME}}|action=edit}}編輯茲蜀頁]</span>。',
'clearyourcache' => "'''注意：'''保存以後，汝可能固著刷新汝其瀏覽器緩存來看遘變化。
* '''火狐/Safari：'''擪下''Shift''篤蜀篤''重新載入''，或者擪蜀擪''Ctrl+F5''或者''Ctrl+R'' （''⌘-R''敆Mac懸頂）
* '''Google Chrome：'''擪''Ctrl+Shift+R''（敆Mac𡅏使''⌘-Shift-R''）
* '''Internet Explorer：'''擪''Ctrl''其時候篤蜀篤''刷新''，或者擪''Ctrl+F5''
* '''Opera：'''敆''工具→首選項''𡅏清除緩存",
'previewnote' => "'''記定茲若是蜀萆預覽。'''
汝其改變固𡅏未保存！",
'continue-editing' => '行去編輯區',
'editing' => '修改 $1',
'editingsection' => '修改$1（段）',
'editingcomment' => '修改$1（新其蜀部分）',
'editconflict' => '修改對衝：$1',
'explainconflict' => "Bĕk-nè̤ng diŏh nṳ̄ tā-sĕng siŭ-gāi cī miêng hiĕk gì sèng-âu ô có̤ gì-tă siŭ-gāi.
Gà̤-dēng gì bēng-bēng hiēng-sê gì sê hiêng-câi có̤i sĭng gì bēng-buōng.
Nṳ̄ sū có̤ gì gāi-biéng găk â-dā̤ gì bēng-bēng diē-sié.
Nṳ̄ sṳ̆-iéu găk gà̤-dēng gì bēng-bēng diē-sié cīng-hăk nṳ̄ lâng ciáh nè̤ng sū có̤ gì gāi-biéng.
Iŏk-guō nṳ̄ dĭk-ciék áik \"{{int:savearticle}}\", '''nâ ô''' gà̤-dēng bēng-bēng diē-sié gì ùng-cê â̤ ké̤ṳk bō̤-còng.",
'yourtext' => '汝其文字',
'editingold' => "'''警告：汝現在𡅏修改已經過時其版本。'''
如果汝保存伊，趁茲以後其任何改變都變無了。",
'yourdiff' => '差別',
'readonlywarning' => "'''Gīng-gó̤: Ôi lāu mì-hô buōng câng, só-gé̤ṳ-kó ké̤ṳk sō̤ kī lāu, gó-chṳ̄ cī-lùng nṳ̄ mò̤ nièng-ngài bō̤-còng nṳ̄ gì siŭ-gāi. Chiāng sĕng bō̤-còng diŏh nṳ̄ diêng-nō̤ buōng-dê, dīng nék-gū mâing gái ché.'''",
'protectedpagewarning' => "'''GĪNG-GÓ̤: Ciā hiĕk ī-gĭng ké̤ṳk sō̤ kī go̤ lāu, nâ ô guāng-lī-uòng â̤ siŭ-gāi ĭ.'''",
'semiprotectedpagewarning' => "'''Cé̤ṳ-é:''' Ciā hiĕk-miêng ī-gĭng ké̤ṳk bō̤-hô, gó-chṳ̄ nâ ô láuk-diē gì ê̤ṳng-hô â̤-sāi siŭ-gāi ĭ.",
'templatesused' => 'Ciā hiĕk gà̤-dēng gì muò-bēng:',
'templatesusedpreview' => 'Ciā ché-káng-hiĕk gà̤-dēng gì muò-bēng:',
'templatesusedsection' => 'Cī dâung diē-sié gì muò-bēng:',
'template-protected' => '（保護）',
'template-semiprotected' => '（半保護）',
'recreate-moveddeleted-warn' => "'''注意：汝重新創建其茲蜀頁面以前已經乞刪掉了。'''

汝著考慮蜀下到底是伓是合適繼續去編輯茲蜀頁。茲蜀頁其刪除記錄共移動記錄都敆嚽塊共汝看：",

# "Undo" feature
'undo-summary' => '取消[[Special:Contributions/$2|$2]]（[[User talk:$2|Tō̤-lâung]]）其$1修改',

# Account creation failure
'cantcreateaccounttitle' => '無能獃開賬戶',

# History pages
'viewpagelogs' => '看茲頁其歷史',
'nohistory' => '茲頁無修改歷史。',
'currentrev' => '最新版本',
'revisionasof' => '$1其版本',
'previousrevision' => '←加舊其版本',
'nextrevision' => '加新其版本→',
'currentrevisionlink' => '最新版本',
'cur' => '仱',
'next' => '下',
'last' => '前',
'page_first' => '頭',
'page_last' => '尾',
'histlegend' => 'Chă-biék gēng-sōng: sōng-dĕk buóh bī-piâng gì bēng-buōng, gái áik "huòi-chiă" (\'\'enter\'\') hĕ̤k-ciā dā̤-dā̤ gì "Bī-piâng gēng-sōng bēng-buōng".<br />
Siók-mìng: (dāng) = gâe̤ng dék sĭng bēng-buōng bī-piâng, (sèng) = gâe̤ng sèng siŏh bēng-buōng bī-piâng, ~ = guó-éu siŭ-gāi.',
'histfirst' => '最早',
'histlast' => '最遲',
'historysize' => '（$1字節）',

# Revision feed
'history-feed-title' => '修改歷史',
'history-feed-description' => '維基百科敆茲頁其修改歷史',

# Revision deletion
'rev-delundel' => '㪗/藏',

# Diffs
'history-title' => '「$1」其修改歷史',
'difference-title' => '「$1」調整以後𣍐蜀樣其地方',
'difference-title-multipage' => '「$1」共「$2」臺中𣍐蜀樣其地方',
'difference-multipage' => '（臺中𣍐蜀様其地方）',
'lineno' => '第$1行：',
'compareselectedversions' => '比較選定版本',
'showhideselectedversions' => '顯/藏選定其調整',
'editundo' => '取消',
'diff-multi' => '（臺中有$2寫其$1萆版本無顯示）',

# Search results
'searchresults' => '討結果',
'searchresulttext' => '更更価關於討{{SITENAME}}其內容，覷蜀覷[[{{MediaWiki:Helppage}}|{{int:help}}]]。',
'searchsubtitle' => "汝是討'''[[:$1]]'''",
'searchsubtitleinvalid' => "汝討'''$1'''",
'prevn' => '前$1萆',
'nextn' => '後$1萆',
'viewprevnext' => '看（$1 {{int:pipe-separator}} $2）（$3）。',
'showingresults' => "Hiēng-sê téng #<b>$2</b> kăi-sṳ̄ gì {{PLURAL:$1|'''1'''|'''$1'''}} bĭk giék-guō.",
'showingresultsnum' => "Hiēng-sê téng #<b>$2</b> kăi-sṳ̄ gì {{PLURAL:$3|'''1'''|'''$3'''}} bĭk giék-guō.",

# Preferences page
'preferences' => '設定',
'mypreferences' => '我其設定',
'prefs-edits' => '修改數量：',
'prefsnologin' => '未躒底其',
'changepassword' => '改變密碼',
'prefs-skin' => '皮膚',
'datedefault' => '無設定',
'prefs-datetime' => '日期共時間',
'prefs-personal' => '用戶資料',
'prefs-rc' => '這般其改變',
'prefs-watchlist' => '監視單',
'prefs-misc' => '其它',
'saveprefs' => '保存',
'resetprefs' => '清除未保存其改變',
'searchresultshead' => '尋討',
'resultsperpage' => '每頁訪問量：',
'recentchangescount' => '這般改變其條目：',
'savedprefs' => '汝其設定已經乞保存了。',
'timezonelegend' => '時區：',
'localtime' => '當地時間：',
'timezoneuseserverdefault' => '使維基默認（$1）',
'timezoneuseoffset' => '其它（點出時差）',
'timezoneoffset' => '時差',
'servertime' => '服務器時間：',
'guesstimezone' => '填充敆瀏覽器𡅏',
'timezoneregion-africa' => '非洲',
'timezoneregion-america' => '美洲',
'timezoneregion-antarctica' => '南極洲',
'timezoneregion-arctic' => '北極',
'timezoneregion-asia' => '亞洲',
'timezoneregion-atlantic' => '大西洋',
'timezoneregion-australia' => '澳洲',
'timezoneregion-europe' => '歐洲',
'timezoneregion-indian' => '印度洋',
'timezoneregion-pacific' => '太平洋',
'allowemail' => '會肯別儂發電子郵件乞汝',
'prefs-searchoptions' => '尋討',
'prefs-namespaces' => '命名空間',
'prefs-files' => '文件',
'youremail' => '電子郵件：',
'username' => '{{GENDER:$1|用戶名}}：',
'uid' => '{{GENDER:$1|用戶}}ID:',
'prefs-registration' => '開賬戶時間',
'yourrealname' => '真實姓名：',
'yourlanguage' => '語言：',
'yournick' => '新其簽名：',
'email' => '電子郵件',
'prefs-help-email' => '電子郵件地址是愛寫就寫其，但是如果汝𣍐記密碼咯，密碼重置其時候需要茲。',

# User rights
'editusergroup' => '修改用戶組',

# Groups
'group' => '組：',
'group-bot' => '機器人',
'group-sysop' => '管理員',
'group-bureaucrat' => '官僚',

'group-bot-member' => '機器人',
'group-sysop-member' => '管理員',
'group-bureaucrat-member' => '官僚組',
'group-suppress-member' => '巡查員',

'grouppage-autoconfirmed' => '{{ns:project}}：自動確認用戶',
'grouppage-bot' => '{{ns:project}}：機器人',
'grouppage-sysop' => '{{ns:project}}：管理員',
'grouppage-bureaucrat' => '{{ns:project}}：官僚組',
'grouppage-suppress' => '{{ns:project}}：巡查員',

# Special:Log/newusers
'newuserlogpage' => '開賬戶日誌',

# Recent changes
'recentchanges' => '這般其改變',
'recentchanges-summary' => '敆維基茲頁跟蹤這般其改變。',
'rcnote' => "下底是{{PLURAL:$1|是 '''1'''改變|最後'''$1'''萆改變}}敆最後'''$2'''日，就像$4 $5。",
'rclistfrom' => '顯示由$1開始其新其改變',
'rcshowhideminor' => '$1過要修改',
'rcshowhidebots' => '$1機器人',
'rcshowhideliu' => '$1躒底用戶',
'rcshowhideanons' => '$1無名用戶',
'rcshowhidemine' => '$1我其修改',
'rclinks' => '顯示$2日以內產生其$1回改變<br />$3',
'diff' => '差',
'hist' => '史',
'hide' => '藏起',
'show' => '顯示',
'minoreditletter' => '~',
'newpageletter' => '!',
'boteditletter' => '^',

# Recent changes linked
'recentchangeslinked' => '相關其改變',
'recentchangeslinked-feed' => '相關其改變',
'recentchangeslinked-toolbox' => '相關其改變',

# Upload
'upload' => '上傳文件',
'uploadbtn' => '上傳文件',
'reuploaddesc' => '取消上傳，轉去上傳頁面',
'uploadnologin' => '未躒底',
'uploadnologintext' => '著[[Special:用戶躒底|躒底]]才會使上傳文件。',
'uploaderror' => '上傳有鄭',
'uploadlog' => '上傳日誌',
'uploadlogpage' => '上傳日誌',
'uploadlogpagetext' => 'Â-dā̤ sê gé-luŏh cī-bŏng ùng-giông siông-duòng gì dăng-dăng.',
'filename' => '文件名',
'filedesc' => '總結',
'fileuploadsummary' => '總結：',
'filesource' => '來源：',
'uploadedfiles' => '上傳文件',
'ignorewarning' => '無視警告保存文件',
'ignorewarnings' => '無視警告',
'fileexists' => 'Ī-gĭng ô siŏh bĭk dè̤ng miàng ùng-giông, nṳ̄ nâ mâ̤ káuk-dêng nṳ̄ sê-ng-sê dŏng-cĭng páh-sáung gāi-biéng ĭ, chiāng giēng-chă <strong>[[:$1]]</strong>.
[[$1|thumb]]',
'uploadwarning' => '上傳警告',
'savefile' => '保存文件',
'uploadedimage' => '上傳「[$1]]」',
'uploadvirus' => '茲文件有病！
細底：$1',
'sourcefilename' => '源文件名：',
'destfilename' => '目標文件名：',
'watchthisupload' => '監視茲文件',
'upload-success-subj' => '成功上傳',

# Special:ListFiles
'imgfile' => '文件',
'listfiles' => '文件單單',
'listfiles_date' => '日期',
'listfiles_name' => '名',
'listfiles_user' => '用戶',
'listfiles_size' => '尺寸',

# File description page
'file-anchor-link' => '文件',
'imagelinks' => '文件使用方法',
'linkstoimage' => '下底$1頁鏈接遘茲文件：',
'nolinkstoimage' => 'Mò̤ hiĕk-miêng lièng gáu ciā ùng-giông.',
'uploadnewversion-linktext' => 'Siông-duòng ciā ùng-giông gì sĭng bēng-buōng',

# MIME search
'download' => '下載',

# Unwatched pages
'unwatchedpages' => 'Mò̤ gáng-sê gì hiĕk-miêng',

# List redirects
'listredirects' => '重定向其單單',

# Unused templates
'unusedtemplateswlh' => '其它鏈接',

# Random page
'randompage' => '隨便罔看',

# Random redirect
'randomredirect' => '隨便重定向',

# Statistics
'statistics' => '統計',
'statistics-header-users' => '用戶統計',

'disambiguationspage' => 'Template:Gì-ngiê',

'brokenredirects-edit' => '改',
'brokenredirects-delete' => '刪',

'withoutinterwiki' => '無跨語言其鏈接',
'withoutinterwiki-summary' => 'Â-dā̤ hiĕk-miêng mò̤ lièng gáu gì-tă ngṳ̄-ngiòng bēng-buōng gì kuá wiki lièng-giék:',

'fewestrevisions' => 'Ké̤ṳk siŭ-gāi guó dék ciēu làu gì ùng-ciŏng',

# Miscellaneous special pages
'nbytes' => '{{PLURAL:$1|1|$1}} cê-ciék',
'nlinks' => '{{PLURAL:$1|1|$1}} ciáh lièng-giék',
'nmembers' => '{{PLURAL:$1|1|$1}} ciáh sìng-uòng',
'wantedcategories' => 'Buóh dĭh gì lôi-biék',
'wantedpages' => 'Buóh dĭh gì hiĕk',
'mostlinked' => 'Ké̤ṳk lièng-giék dék sâ̤ làu gì hiĕk',
'mostlinkedcategories' => 'Ké̤ṳk lièng-giék dék sâ̤ làu gì lôi-biék',
'mostcategories' => 'Ô dék sâ̤ lôi-biék gì ùng-ciŏng',
'mostimages' => 'Ké̤ṳk lièng-giék dék sâ̤ làu gì dù',
'mostrevisions' => 'Ké̤ṳk siŭ-gāi guó dék sâ̤ làu gì ùng-ciŏng',
'shortpages' => '短頁',
'longpages' => '長頁',
'protectedpages' => '保護頁',
'listusers' => '用戶單',
'newpages' => '新頁',
'newpages-username' => '用戶名：',
'ancientpages' => '最舊其頁面',
'move' => '移動',
'movethispage' => '移動茲頁',

# Book sources
'booksources' => '書源',
'booksources-search-legend' => '尋討書源',
'booksources-go' => '去',
'booksources-text' => 'Â-dā̤ sê mâ̤ cṳ̆ uōng-câng gì dăng-dăng, kō̤-nèng ô nṳ̄ buóh tō̤ gì cṳ̆ gì gáing sâ̤ séng-sék:',

# Special:Log
'specialloguserlabel' => '表演者：',
'speciallogtitlelabel' => '目標（稱呼或者用戶）：',
'log' => '日誌',
'alllogstext' => "Siông-diòng (''upload''), chēng (''deletion''), bō̤-hô (''protection''), hŭng-sō̤ (''blocking''), gâe̤ng guāng-lī-uòng (''sysop'') nĭk-cé ciòng-buô hiēng-sê diŏh â-dā̤. Nṳ̄ â̤-sāi gēng-sōng nĭk-cé lôi-biék, ê̤ṳng-hô gì miàng, hĕ̤k-ciā 1 tiŏng hiĕk lì gāng-huá giék-guō.",
'logempty' => 'Nĭk-cé diē-sié tō̤ mâ̤ diŏh hâung-mŭk.',

# Special:AllPages
'allpages' => '所有頁面',
'alphaindexline' => '$1遘$2',
'nextpage' => '下蜀頁（$1）',
'prevpage' => '前蜀頁（$1）',
'allpagesfrom' => 'Iù ciā cê-mō̤ kăi-sṳ̄ gì miàng:',
'allarticles' => '所有文章',
'allinnamespace' => '所有頁面（$1命名空間）',
'allnotinnamespace' => '所有頁面（無著$1命名空間）',
'allpagesprev' => '前蜀頁',
'allpagesnext' => '下蜀頁',
'allpagessubmit' => '去',
'allpagesprefix' => 'Áng cê-tàu hiēng-sê:',
'allpagesbadtitle' => 'Nṳ̄ sṳ̆-ĭk gì biĕu-dà̤ buōng câng mò̤ ciĕ-tì.',

# Special:Categories
'categories' => '類別',

# Special:DeletedContributions
'deletedcontributions' => 'Ké̤ṳk chēng lâi gì ê̤ṳng-hô góng-hióng',
'deletedcontributions-title' => 'Ké̤ṳk chēng lâi gì ê̤ṳng-hô góng-hióng',

# Special:LinkSearch
'linksearch-ok' => '尋討',

# Email user
'emailuser' => '寄電子郵件乞茲隻用戶',
'emailpage' => '寄電子郵件乞用戶',
'defemailsubject' => '{{SITENAME}}趁用戶「$1」𡅏底批',
'noemailtitle' => '無電子郵件地址',
'emailfrom' => '趁：',
'emailto' => '遘：',
'emailsubject' => '主題：',
'emailmessage' => '消息：',
'emailsend' => '寄',
'emailccme' => '共我其消息其副本寄我一份電子郵件。',
'emailsent' => '電子郵件發出了',
'emailsenttext' => '汝其電子郵件消息已經寄出了。',

# Watchlist
'watchlist' => '我其監視單',
'mywatchlist' => '我其監視單',
'nowatchlist' => 'Nṳ̄ gì gáng-sê-dăng gà̤-dēng mò̤ dèu-mĕ̤k.',
'watchnologin' => '未躒底',
'addedwatchtext' => '頁面「[[:$1]]」已經加遘汝其[[Special:Watchlist|監視單]]。以後敆茲蜀頁其改變共伊關聯其討論頁都會列敆嚽塊。',
'removewatch' => '趁汝其監視單臺中移去',
'removedwatchtext' => '頁面「[[:$1]]」已經趁[[Special:Watchlist|汝其監視單]]移去了。',
'watch' => '監視',
'watchthispage' => '監視茲頁',
'unwatch' => '伓使監視',
'unwatchthispage' => '停止監視',
'watchlist-details' => '$1頁敆汝其監視單𡅏，無算討論頁。',
'wlshowlast' => 'Hiēng-sê có̤i hâiu $1 dēng-cṳ̆ng $2 gĕ̤ng $3',

# Displayed when you click the "watch" button and it is in the process of watching
'watching' => '監視...',

# Delete
'deletepage' => '刪頁',
'confirm' => '確認',
'excontent' => "nô̤i-ṳ̀ng sê: '$1'",
'excontentauthor' => "nô̤i-ṳ̀ng sê: '$1' (bêng-chiā cáuk-ciā nâ ô '[[Special:Contributions/$2|$2]]')",
'exbeforeblank' => "dù táh cĭ-sèng gì nô̤i-ṳ̀ng sê: '$1'",
'historywarning' => "'''警告：'''汝卜想刪掉其頁面有蜀段大概$1版本其它歷史：",
'confirmdeletetext' => 'Nṳ̄ cūng-bê ciŏng ciā hiĕk-miêng hĕ̤k ùng-giông lièng ĭ găk só-gé̤ṳ-kó gì lĭk-sṳ̄ ciòng-buô chēng lâi. Chiāng nṳ̄ káuk-nêng: nṳ̄ dŏng-cĭng buóh siōng cūng-kuāng có̤, nṳ̄ liēu-gāi cūng-kuāng có̤ gì hâiu-guō, bêng-chiā nṳ̄ cūng-kuāng có̤ sê hù-hăk [[{{MediaWiki:Policy-url}}]].',
'actioncomplete' => '行動成功',
'deletedtext' => '"$1" ī-gĭng ké̤ṳk chēng lâi go̤ lāu. Cī-bŏng chēng hiĕk gì gé-liŏh dŭ gé diŏh $2.',
'dellogpage' => 'Chēng hiĕk nĭk-cé',
'dellogpagetext' => 'Â-dā̤ sê gé-liŏh cī-bŏng chēng hiĕk gì dăng-dăng.',
'deletionlog' => '刪除日誌',
'deletecomment' => '原因：',

# Rollback
'rollback' => 'Gâe̤ng siŭ-gāi duōng kó̤',
'rollback_short' => 'Duōng',
'rollbacklink' => 'duōng',
'rollbackfailed' => 'Duōng mâ̤ kó̤',
'cantrollback' => 'Mò̤ bâing-huák huòi-tó̤i siŭ-gāi; sèng 1 ciáh góng-hióng-ciā sê ciā hiĕk mì-ék gì cáuk-ciā.',
'alreadyrolled' => 'Mò̤ nièng-ngài huòi-tó̤i [[User:$2|$2]] ([[User talk:$2|Tō̤-lâung]]) có̤i âu sū có̤ gì [[$1]] siŭ-gāi; bĕk-nè̤ng ī-gĭng siū-gái hĕ̤k-ciā huòi-tó̤i ciā hiĕk-miêng go̤ lāu.

Có̤i âu gì siŭ-gāi sê [[User:$3|$3]] ([[User talk:$3|Tō̤-lâung]]) sū có̤ gì.',
'editcomment' => "修改評論是：「''$1''」。",
'revertpage' => '[[Special:Contributions/$2|$2]] ([[User talk:$2|Talk]]) sū có̤ gì siŭ-gāi duōng kó̤ [[User:$1|$1]] gì sèng 1 bĭk bēng-buōng',

# Protect
'protectlogpage' => '保護日誌',
'protect-title' => 'Bō̤-hô "$1"',
'prot_1movedto2' => '[[$1]] iè gáu [[$2]]',
'protect-legend' => 'Káuk-nêng bō̤-hô',
'protectcomment' => '原因：',
'protect-level-autoconfirmed' => '囇允許自動確認用戶',
'protect-level-sysop' => '囇允許管理員',
'protect-expiry-options' => '2 點鐘:2 hours,1 日:1 day,3 日:3 days,1 禮拜:1 week,2 禮拜:2 weeks,1 month:1 月日,3 月日:3 months,6 月日:6 months,1 nièng:1 year,永遠:infinite',
'restriction-type' => '權限：',
'restriction-level' => 'Âing-cié dēng-gék:',
'minimum-size' => 'Có̤i nâung chióh-cháung',
'maximum-size' => 'Có̤i duâi chióh-cháung',
'pagesize' => '(cê-ciék)',

# Restrictions (nouns)
'restriction-edit' => '修改',
'restriction-move' => '移動',

# Restriction levels
'restriction-level-sysop' => '全保護',
'restriction-level-autoconfirmed' => '半保護',
'restriction-level-all' => 'sū-iū dēng-gék',

# Undelete
'undeletepage' => 'Káng bêng-chiā hŭi-hók ké̤ṳk chēng lâi gì hiĕk-miêng',
'viewdeletedpage' => 'Káng chēng lâi gì hiĕk',
'undeleteextrahelp' => "Buóh gâe̤ng gó̤-lòng hiĕk dŭ hŭi-hók, chiāng ng-sāi sōng \"Hiĕk-miêng lĭk-sṳ̄\" â-dā̤ gì ăk-ăk, áik '''''Hŭi-hók''''' cêu â̤-sāi lāu. Buóh hŭi-hók gēng-sōng gì lĭk-sṳ̄, chiāng sōng-dĕk nṳ̄ buóh hŭi-hók gì hiĕk-miêng lĭk-sṳ̄ sèng-sāu gì ăk-ăk gái áik '''''Hŭi-hók'''''. Áik '''''Dṳ̀ng-sĭng siā''''' â̤ cháe̤ lâi pàng-lâung gáh-gáh gâe̤ng sōng-dĕk ăk-ăk.",
'undeletehistory' => 'If you restore the page, all revisions will be restored to the history.
If a new page with the same name has been created since the deletion, the restored revisions will appear in the prior history.',
'undeletebtn' => '恢復',
'undeletereset' => 'Dṳ̀ng-sĭng siā',
'undeletecomment' => '原因：',
'undelete-search-submit' => '尋討',

# Namespace form on various pages
'namespace' => '命名空間：',
'invert' => 'Huāng sōng',

# Contributions
'contributions' => '用戶貢獻',
'contributions-title' => '用戶對$1其貢獻',
'mycontris' => '我其貢獻',
'uctop' => '（當前）',
'month' => '趁月（共更早）：',
'year' => '趁年（共更早）：',

'sp-contributions-newbies' => 'Nâ hiēng-sê sĭng kŭi dióng-hô gì góng-hióng',
'sp-contributions-newbies-sub' => 'Ciáh lì gì',
'sp-contributions-blocklog' => '封鎖日誌',
'sp-contributions-deleted' => '開除來其用戶貢獻',
'sp-contributions-talk' => '討論',
'sp-contributions-search' => '尋討貢獻',
'sp-contributions-username' => 'IP地址或者用戶名：',
'sp-contributions-submit' => '尋討',

# What links here
'whatlinkshere' => '什乇鏈遘嚽塊',
'whatlinkshere-title' => '鏈接遘$1其頁面',
'linkshere' => "Â-dā̤ gì hiĕk-miêng lièng gáu '''[[:$1]]''':",
'nolinkshere' => "Mò̤ hiĕk-miêng lièng gáu '''[[:$1]]'''.",
'isredirect' => '重定向頁面',
'whatlinkshere-prev' => '{{PLURAL:$1|sèng|sèng $1 hâung}}',
'whatlinkshere-next' => '{{PLURAL:$1|â|â $1 hâung}}',
'whatlinkshere-links' => '← 鏈接',

# Block/unblock
'blockip' => '封鎖用戶',
'blockiptext' => 'Sāi-ê̤ṳng â-dā̤ gì dăng-dăng lì hŭng-sō̤ IP dê-cī hĕ̤k-ciā ê̤ṳng-hô-miàng gì siā guòng-âing. Cuòi nâ sê ôi lāu huòng-cī nè̤ng cáuk-ták wiki, bêng-chiā găi-dŏng hù-hăk [[{{MediaWiki:Policy-url}}|céng-cháik]]. Chiāng diŏh â-dā̤ siā giâ hŭng-sō̤ gì nguòng-ĭng (pī-ṳ̀-gōng, īng-ê̤ṳng ké̤ṳk cáuk-ták gì hiĕk-miêng).',
'ipadressorusername' => 'IP dê-cī hĕ̤k ê̤ṳng-hô-miàng:',
'ipbexpiry' => '過期：',
'ipbreason' => '原因：',
'ipbreasonotherlist' => '其它原因',
'ipbreason-dropdown' => '*Pū-tŭng hŭng-sō̤ nguòng-ĭng
** Gă-tiĕng gā gì séng-sék
** Dù lâi hiĕk-miêng nô̤i-ṳ̀ng
** Huák-buó bóng-só̤ séng-sék
** Luâng siā ùng-cê
** Có̤-hák / lièu-sê̤ṳ
** Luâng kŭi dŏ̤ dióng-hô̤
** Luâng kī ê̤ṳng-hô-miàng',
'ipbcreateaccount' => 'Huòng-cī kŭi dióng-hô̤',
'ipbemailban' => '防止用戶寄電子郵件',
'ipbenableautoblock' => 'Cê̤ṳ-dông hŭng-sō̤ ciā ê̤ṳng-hô siā-ê̤ṳng gì IP dê-cī',
'ipbsubmit' => 'Hŭng-sō̤ ciā ê̤ṳng-hô',
'ipbother' => '其它時間',
'ipboptions' => '2 dēng-cṳ̆ng:2 hours,1 gĕ̤ng:1 day,3 gĕ̤ng:3 days,1 lā̤-buái:1 week,2 lā̤-buái:2 weeks,1 nguŏk-nĭk:1 month,3 nguŏk-nĭk:3 months,6 nguŏk-nĭk:6 months,1 nièng:1 year,īng-uōng:infinite',
'ipbotheroption' => '其它',
'ipbotherreason' => 'Gì-tă nguòng-ĭng:',
'blockipsuccesssub' => 'Hŭng-sō̤ sìng-gŭng',
'blockipsuccesstext' => '[[Special:Contributions/$1|$1]]已經乞封鎖.<br />
覷蜀覷[[Special:BlockList|封鎖單]]來瀏覽封鎖。',
'ipb-edit-dropdown' => 'Siŭ-gāi hŭng-sō̤ nguòng-ĭng',
'ipb-unblock-addr' => 'Gāi-hŭng $1',
'ipb-unblock' => 'Gāi-hŭng siŏh ciáh ê̤ṳng-hô hĕ̤k IP dê-cī',
'ipb-blocklist' => 'Káng hŭng-sō̤ dăng-dăng',
'unblockip' => '開放用戶',
'ipusubmit' => '開放茲地址',
'unblocked' => '[[User:$1|$1]]已經乞開放了。',
'ipblocklist' => '乞封鎖其用戶',
'ipblocklist-legend' => 'Tō̤ siŏh ciáh ké̤ṳk hŭng-sō̤ gì ê̤ṳng-hô',
'ipblocklist-submit' => '尋討',
'infiniteblock' => '永遠',
'anononlyblock' => 'nâ mò̤-miàng ê̤ṳng-hô',
'createaccountblock' => 'huòng-cī kŭi dióng-hô̤',
'ipblocklist-empty' => 'Cī tiŏng hŭng-sō̤ dăng-dăng sê kĕ̤ng gì.',
'blocklink' => 'hŭng-sō̤',
'unblocklink' => 'gāi-hŭng',
'contribslink' => 'góng-hióng',
'blocklogpage' => 'Hŭng-sō̤ nĭk-cé',
'blocklogentry' => 'hŭng-sō̤ [[$1]], gáu $2 hâiu guó-gĭ, $3',
'block-log-flags-anononly' => 'nâ mò̤-miàng ê̤ṳng-hô',
'block-log-flags-nocreate' => 'huòng-cī kŭi dióng-hô̤',
'ipb_expiry_invalid' => 'Guó-gĭ sì-găng mò̤-hâu.',
'ipb_already_blocked' => '"$1" ī-gĭng ké̤ṳk nè̤ng hŭng-sō̤ lāu',

# Developer tools
'lockconfirm' => 'Ciáng-sê, sō̤ kī ciā só-gé̤ṳ-kó.',
'lockbtn' => 'Sō̤ kī só-gé̤ṳ-kó',
'unlockbtn' => 'Kŭi só-gé̤ṳ-kó',
'lockdbsuccesssub' => 'Só-gé̤ṳ-kó sō̤ hō̤ lāu',
'databasenotlocked' => 'Só-gé̤ṳ-kó mò̤ sō̤',

# Move page
'move-page-legend' => 'Iè-dông hiĕk-miêng',
'movepagetext' => "Sāi-ê̤ṳng â-dā̤ gì dăng-dăng â̤ gâe̤ng hiĕk-miêng dṳ̀ng-sĭng kī-miàng, bêng-chiā ĭ ciòng-buô lĭk-sṳ̄ dŭ â̤ ké̤ṳk iè gáu sĭng miàng â-dā̤. Gô miàng â̤ biéng có̤ dṳ̀ng-dêng-hióng hiĕk-miêng. Lièng gáu gô hiĕk dà̤-mĕ̤k gì lièng-giék dŭ mò̤ gāi-biéng; chiāng káuk-nêng mò̤ huák-sĕng sĕ̤ng dṳ̀ng-dêng-hióng (''double redirect'') hĕ̤k-ciā sê ngài dṳ̀ng-dêng-hióng (''broken redirect''). Nṳ̄ ô dăng-dŏng hô-cáik lièng-giék ĭng-nguòng â̤ lièng gáu ciáng-káuk gì sū-câi.

Cé̤ṳ-é, nâ ô găk sĭng dà̤-mĕ̤k gô-dā̤ mò̤ ùng-ciŏng (mò̤ bău-guăk páng hiĕk hĕ̤k-ciā sê mò̤ siŭ-gāi lĭk-sṳ̄ gì dṳ̀ng-dêng-hióng hiĕk) gì cìng-hióng â-dā̤, ciáh â̤ iè-dông. Cuòi cêu sê gōng, nṳ̄ â̤-sāi gâe̤ng hiĕk-miêng gì miàng gāi duōng go̤ iŏk-guō nṳ̄ tā-sĕng có̤ dâng go̤, dáng-sê nṳ̄ mâ̤-sāi hók-gái ī-gĭng còng-câi gì hiĕk-miêng.

<b>GĪNG-GÓ̤!</b> Cuòi ô kō̤-nèng sāng-sĕng mâ̤ ê̤ṳ-lâiu gì gāi-biéng; cūng-kuāng có̤ cĭ-sèng, chiāng káuk-nêng nṳ̄ liēu-gāi hâiu-guō.",
'movepagetalktext' => "Siŏng-guăng gì tō̤-lâung-hiĕk â̤ cê̤ṳ-dông gṳ̆ng ĭ iè gáu sĭng dà̤-mĕ̤k â-dā̤, '''dṳ̀-hĭ:'''
* Sĭng dà̤-mĕ̤k ī-gĭng ô siŏh hiĕk ô nô̤i-ṳ̀ng gì tō̤-lâung-hiĕk, hĕ̤k-ciā
* Nṳ̄ chṳ̄-siĕu â-dā̤ gì sōng-hâung.

Nâ cūng-kuāng, nṳ̄ â̤-sāi cê-gă iè-dông hĕ̤k-ciā sê hăk-biáng hiĕk-miêng.",
'movearticle' => 'Iè-dông ùng-ciŏng',
'movenologin' => 'Muôi láuk-diē',
'movenologintext' => 'Sĕng [[Special:UserLogin|láuk-diē]] ciáh â̤-sāi iè-dông hiĕk-miêng.',
'newtitle' => 'Gáu sĭng dà̤-mĕ̤k',
'move-watch' => 'Gáng-sê ciā hiĕk',
'movepagebtn' => 'Iè-dông hiĕk-miêng',
'pagemovedsub' => 'Iè-dông sìng-gŭng',
'talkexists' => "'''Hiĕk-miêng buōng-sĭng ī-gĭng ké̤ṳk iè-dông go̤ lāu, dáng-sê tō̤-lâung-hiĕk mò̤ nièng-ngài iè-dông ĭng-ôi sĭng biĕu-dà̤ â-dā̤ ī-gĭng ô siŏh tiŏng tō̤-lâung-hiĕk lāu. Chiāng nṳ̄ cê-gă gâe̤ng cī lâng hiĕk biáng lâ.'''",
'movedto' => 'iè gáu',
'movetalk' => 'Iè-dông siŏng-guăng tō̤-lâung hiĕk',
'movelogpage' => 'Iè-dông nĭk-cé',
'movelogpagetext' => 'Â-dā̤ sê ké̤ṳk iè-dông guó gì hiĕk-miêng gì dăng-dăng.',
'movereason' => 'Nguòng-ĭng',
'delete_and_move' => 'Chēng lâi bêng-chiā iè-dông',
'delete_and_move_confirm' => 'Ciáng-sê, chēng lâi cī miêng hiĕk',

# Namespace 8 related
'allmessages' => 'Hiê-tūng siĕu-sék',
'allmessagesname' => 'Miàng',
'allmessagesdefault' => 'Nguòng-sṳ̄ gì ùng-cê',
'allmessagescurrent' => 'Hiêng-sì gì ùng-cê',
'allmessagestext' => 'Cī tiŏng dăng-dăng sê MediaWiki miàng-kŭng-găng â̤ ciĕ-tì gì hiê-tūng siĕu-sék.',
'allmessagesnotsupportedDB' => "Mò̤ bâing-huák sāi-ê̤ṳng '''{{ns:special}}:Allmessages''', ĭng-ôi '''\$wgUseDatabaseMessages''' ī-gĭng cĕk lâi gó̤.",

# Tooltip help for the actions
'tooltip-search' => 'Sìng-tō̤ {{SITENAME}} [alt-f]',
'tooltip-save' => 'Bō̤-còng nṳ̄ gì gāi-biéng [alt-s]',
'tooltip-watch' => 'Gă-tiĕng ciā hiĕk-miêng gáu nṳ̄ gì gáng-sê-hiĕk [alt-w]',

# Attribution
'anonymous' => '{{SITENAME}} gì mò̤ miàng ê̤ṳng-hô.',
'lastmodifiedatby' => 'Ciā hiĕk-miêng sê diŏh $2, $1, iù  $3 có̤i-hâiu siŭ-gāi gì.',

# Image deletion
'deletedrevision' => 'Ī-gĭng chēng lâi gì bēng-buōng $1.',

# Browsing diffs
'previousdiff' => '← Sèng 1 hâung chă-biék',
'nextdiff' => 'Â 1 hâung chă-biék →',

# Media information
'file-nohires' => 'Cuòi sê có̤i duâi chióh-cháung.',

# Special:NewFiles
'showhidebots' => '($1 gĭ-ké-nè̤ng)',
'ilsubmit' => 'Sìng-tō̤',
'bydate' => 'áng nĭk-gĭ',

# Metadata
'metadata' => 'Nguòng-só-gé̤ṳ',

'exif-componentsconfiguration-0' => 'mò̤ còng-câi',

'exif-meteringmode-0' => 'Muôi báik',

'exif-lightsource-0' => 'Muôi báik',

'exif-subjectdistancerange-0' => 'Muôi báik',

# External editor support
'edit-externally' => 'Sāi nguôi-buô tiàng-sê̤ṳ piĕng-cék ciā ùng-giông',
'edit-externally-help' => 'Chăng-kō̤ [//www.mediawiki.org/wiki/Manual:External_editors setup instructions] liēu-gāi gáing sâ̤ séng-sék.',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => '所有',
'namespacesall' => '所有',
'monthsall' => 'gó̤-lòng nièng',

# Email address confirmation
'confirmemail' => 'Káuk-nêng diêng-piĕ dê-cī',
'confirmemail_invalid' => 'Káuk-nêng mā mò̤-hâu, kō̤-nèng ī-gĭng guó-gĭ lāu.',
'confirmemail_needlogin' => 'Chiāng nṳ̄ sĕng $1 nṳ̄ gì diêng-piĕ dê-cī.',
'confirmemail_loggedin' => 'Nṳ̄ gì diêng-piĕ dê-cī hiêng-câi ī-gĭng káuk-nêng lāu.',
'confirmemail_error' => 'Bō̤-còng nṳ̄  káuk-nêng gì sèng-hâiu huák-sĕng ông-dà̤ lāu.',
'confirmemail_body' => 'Tā-lĕng ô nè̤ng (kō̤-nèng sê nṳ̄) téng IP dê-cī $1 găk {{SITENAME}} sāi cī ciáh diêng-piĕ dê-cī cé̤ṳ-cháh lāu "$2" dióng-hô̤.

Ciā diêng-piĕ dê-cī nâ sê nṳ̄ gì, chiāng nṳ̄ páh kŭi â-dā̤ lièng-giék:

$3

Nâ-sāi ĭ *ng-sê* nṳ̄, chiāng mŏ̤h chák ĭ. Gáu $4, káuk-nêng-mā â̤ guó-gĭ.',

# Delete conflict
'deletedwhileediting' => 'Gīng-gó̤: Cī miêng hiĕk găk nṳ̄ kī-chiū siŭ-gāi cĭ hâiu ké̤ṳk chēng lâi go̤ lāu!',
'recreate' => 'Dṳ̀ng-sĭng kŭi',

# action=purge
'confirm_purge_button' => '好',

# Multipage image navigation
'imgmultipageprev' => '← 前蜀頁',
'imgmultipagenext' => '下蜀頁 →',
'imgmultigo' => '去！',

# Table pager
'ascending_abbrev' => 'sĭng',
'descending_abbrev' => 'gáung',
'table_pager_next' => 'Â 1 hiĕk',
'table_pager_prev' => 'Sèng 1 hiĕk',
'table_pager_first' => 'Tàu hiĕk',
'table_pager_last' => 'Muōi hiĕk',
'table_pager_limit' => 'Mūi hiĕk hiêng-sê $1 hâung',
'table_pager_limit_submit' => 'Kó̤',
'table_pager_empty' => 'Mò̤ giék-guō',

# Auto-summaries
'autosumm-blank' => 'Dù lâi ciòng-buô ùng-cê',
'autoredircomment' => 'Dṳ̀ng-sĭng dêng-hióng gáu [[$1]]',
'autosumm-new' => 'Sĭng hiĕk: $1',

# Live preview
'livepreview-loading' => 'Tĕ̤k-chṳ̄...',
'livepreview-ready' => 'Tĕ̤k-chṳ̄... Hō̤ lāu!',

# Watchlist editor
'watchlistedit-raw-title' => 'Siŭ-gāi nguòng-sṳ̄ gáng-sê-dăng',
'watchlistedit-raw-legend' => 'Siŭ-gāi nguòng-sṳ̄ gáng-sê-dăng',
'watchlistedit-raw-titles' => 'Dà̤-mĕ̤k:',
'watchlistedit-raw-submit' => 'Huăng-sĭng Gáng-sê-dăng',
'watchlistedit-raw-removed' => '{{PLURAL:$1|1|$1}} bĭk dà̤-mĕ̤k ké̤ṳk chēng lâi:',

# Watchlist editing tools
'watchlisttools-view' => 'Káng siŏng-guăng gāi-biéng',
'watchlisttools-edit' => 'Káng gâe̤ng siŭ-gāi gáng-sê-dăng',
'watchlisttools-raw' => 'Siŭ-gāi nguòng-sṳ̄ gáng-sê-dăng',

# Special:SpecialPages
'specialpages' => 'Dĕk-sṳ̀ hiĕk',

);
