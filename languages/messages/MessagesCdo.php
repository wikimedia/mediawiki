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
'sunday' => 'Lā̤-buái',
'monday' => 'Buái ék',
'tuesday' => 'Buái nê',
'wednesday' => 'Buái săng',
'thursday' => 'Buái sé',
'friday' => 'Buái ngô',
'saturday' => 'Buái lĕ̤k',
'sun' => 'LB',
'mon' => 'B1',
'tue' => 'B2',
'wed' => 'B3',
'thu' => 'B4',
'fri' => 'B5',
'sat' => 'B6',
'january' => 'Ék nguŏk',
'february' => 'Nê nguŏk',
'march' => 'Săng nguŏk',
'april' => 'Sé nguŏk',
'may_long' => 'Ngô nguŏk',
'june' => 'Lĕ̤k nguŏk',
'july' => 'Chék nguŏk',
'august' => 'Báik nguŏk',
'september' => 'Gāu nguŏk',
'october' => 'Sĕk nguŏk',
'november' => 'Sĕk-ék nguŏk',
'december' => 'Sĕk-nê nguŏk',
'january-gen' => 'Ék nguŏk',
'february-gen' => 'Nê nguŏk',
'march-gen' => 'Săng nguŏk',
'april-gen' => 'Sé nguŏk',
'may-gen' => 'Ngô nguŏk',
'june-gen' => 'Lĕ̤k nguŏk',
'july-gen' => 'Chék nguŏk',
'august-gen' => 'Báik nguŏk',
'september-gen' => 'Gāu nguŏk',
'october-gen' => 'Sĕk nguŏk',
'november-gen' => 'Sĕk-ék nguŏk',
'december-gen' => 'Sĕk-nê nguŏk',
'jan' => '1ng',
'feb' => '2ng',
'mar' => '3ng',
'apr' => '4ng',
'may' => '5ng',
'jun' => '6ng',
'jul' => '7ng',
'aug' => '8ng',
'sep' => '9ng',
'oct' => '10ng',
'nov' => '11ng',
'dec' => '12ng',
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
'pagecategories' => '{{PLURAL:$1|Lôi-biék|Lôi-biék}}',
'category_header' => '"$1" lôi-biék â-dā̤ gì ùng-ciŏng',
'subcategories' => 'Cṳ̄-lôi-biék',
'category-media-header' => '「$1」類別下底其媒體',
'category-empty' => "''Ciā lôi-biék â-dā̤ hiêng-câi mò̤ ùng-ciŏng iâ mò̤ muòi-tā̤ ùng-giông.''",
'hidden-categories' => '共類別藏起咯',
'hidden-category-category' => '已經藏起其類別',
'category-subcat-count-limited' => '茲蜀萆類別下底有子類別',
'category-article-count' => '{{PLURAL:$2|茲蜀萆類別儷有下底蜀頁。|共總有$2頁，下底其茲$1頁敆茲蜀萆類別𡅏。}}',
'category-article-count-limited' => '下底$1頁敆茲蜀萆類別𡅏',
'category-file-count' => '茲蜀萆類別共總有$2萆文件，下底茲$1萆文件都敆茲蜀萆類別𡅏。',
'category-file-count-limited' => '下底其茲$1萆文件都敆茲蜀萆類別𡅏。',
'listingcontinuesabbrev' => '(gié-sṳ̆k sèng-dāu)',
'index-category' => '索引其頁面',
'noindex-category' => '未索引其頁面',
'broken-file-category' => '獃其文件鏈接其頁面',

'about' => 'Guăng-ṳ̀',
'article' => 'Ùng-ciŏng',
'newwindow' => '(găk sĭng chŏng-tā̤ tāu kŭi)',
'cancel' => 'Chṳ̄-siĕu',
'moredotdotdot' => 'Gáing sâ̤...',
'morenotlisted' => '固有未列出其',
'mypage' => '頁面',
'mytalk' => '我其討論',
'anontalk' => 'Cī ciáh IP gì tō̤-lâung-hiĕk',
'navigation' => 'Īng-dô̤',
'and' => '&#32;gâe̤ng',

# Cologne Blue skin
'qbfind' => '討',
'qbbrowse' => '覷蜀覷',
'qbedit' => '修改',
'qbpageoptions' => '茲蜀頁',
'qbmyoptions' => '我其頁面',
'qbspecialpages' => 'Dĕk-sṳ̀ hiĕk',
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
'returnto' => 'Duōng kó̤ $1.',
'tagline' => 'Lài-nguòng: {{SITENAME}}',
'help' => 'Bŏng-cô',
'search' => 'Sìng-tō̤',
'searchbutton' => 'Tō̤',
'go' => 'Kó̤',
'searcharticle' => 'Kó̤',
'history' => 'Hiĕk-miêng lĭk-sṳ̄',
'history_short' => 'Lĭk-sṳ̄',
'updatedmarker' => '趁我最後蜀回訪問開始更新',
'printableversion' => 'Kō̤ páh-éng bēng-buōng',
'permalink' => 'Īng-giū lièng-giék',
'print' => 'Páh-éng',
'view' => '覷蜀覷',
'edit' => 'Siŭ-gāi',
'create' => '創建',
'editthispage' => 'Siŭ-gāi ciā hiĕk',
'create-this-page' => '創建茲蜀頁',
'delete' => 'Chēng',
'deletethispage' => 'Chēng ciā hiĕk',
'undeletethispage' => '恢復茲蜀頁',
'undelete_short' => '恢復$1回修改',
'viewdeleted_short' => '覷蜀覷$1回刪掉其修改',
'protect' => 'Bō̤-hô',
'protect_change' => '改變',
'protectthispage' => '保護茲蜀頁',
'unprotect' => '改變保護其狀態',
'unprotectthispage' => '改變茲蜀頁其保護狀態',
'newpage' => 'Sĭng hiĕk',
'talkpage' => 'Tō̤-lâung ciā hiĕk',
'talkpagelinktext' => 'Tō̤-lâung',
'specialpage' => 'Dĕk-sṳ̀ hiĕk',
'personaltools' => '個人其家私',
'postcomment' => '新其蜀段',
'articlepage' => '覷蜀覷內容頁面',
'talk' => 'Tō̤-lâung',
'views' => '覷蜀覷',
'toolbox' => 'Gă-sĭ',
'userpage' => '覷蜀覷用戶頁面',
'projectpage' => 'Káng gĕ̤ng-tiàng hiĕk',
'imagepage' => '覷蜀覷文件頁面',
'mediawikipage' => 'Káng siĕu-sék hiĕk',
'templatepage' => 'Káng muò-bēng hiĕk',
'viewhelppage' => 'Káng bŏng-cô hiĕk',
'categorypage' => 'Káng hŭng-lôi hiĕk',
'viewtalkpage' => 'Káng tō̤-lâung',
'otherlanguages' => 'Gì-tă gì ngṳ̄-ngiòng',
'redirectedfrom' => '(Iù $1 dêng-hióng lì gì)',
'redirectpagesub' => 'Dṳ̀ng-sĭng dêng-hióng hiĕk',
'lastmodifiedat' => 'Ciā hiĕk-miêng sê diŏh $2, $1 có̤i-hâiu siŭ-gāi gì.',
'viewcount' => '茲蜀頁已經乞訪問$1回了。',
'protectedpage' => 'Bō̤-hô hiĕk',
'jumpto' => 'Tiéu gáu:',
'jumptonavigation' => 'īng-dô̤',
'jumptosearch' => 'sìng-tō̤',
'view-pool-error' => '對不住，服務器茲蜀萆時候已弳過載了。
過価用戶敆𡅏覷茲蜀頁。
起動等仂久再來覷茲蜀頁。

$1',
'pool-timeout' => '等待鎖定其時間遘了',
'pool-queuefull' => '隊列池已經滿了',
'pool-errorunknown' => '𣍐八什乇鄭咯',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite' => 'Guăng-ṳ̀ {{SITENAME}}',
'aboutpage' => 'Project:Guăng-ṳ̀',
'copyright' => 'Buōng câng gì cṳ̆-lâiu dŭ sê gŏng-gé̤ṳ $1 huák-buó gì.',
'copyrightpage' => '{{ns:project}}:Bēng-guòng',
'currentevents' => 'Duâi chéu â',
'currentevents-url' => 'Project:Duâi chéu â',
'disclaimers' => 'Mò̤ hô-cáik sĭng-mìng',
'disclaimerpage' => 'Project:Mò̤ hô-cáik sĭng-mìng',
'edithelp' => 'Siŭ-gāi bŏng-cô',
'helppage' => 'Help:Mŭk-liŏh',
'mainpage' => 'Tàu Hiĕk',
'mainpage-description' => 'Tàu Hiĕk',
'policy-url' => 'Project:Céng-cháik',
'portal' => 'Tiăng-dŏng',
'portal-url' => '工程：社區門戶',
'privacy' => 'Ṳ̄ng-sṳ̆ céng-cháik',
'privacypage' => 'Project:Ṳ̄ng-sṳ̆ céng-cháik',

'badaccess' => '權限錯誤',
'badaccess-group0' => '汝𣍐使做汝要求其茲蜀萆動作。',
'badaccess-groups' => '汝要求其動作著$2底裏用戶才會做其：$1',

'versionrequired' => '需要版本$1其媒體維基',
'versionrequiredtext' => '需要媒體維基其版本$1來使茲蜀頁。
覷[[Special:Version|版本頁面]]。',

'ok' => 'Hō̤',
'retrievedfrom' => '趁「$1」退過來',
'youhavenewmessages' => 'Nṳ̄ ô $1 ($2).',
'newmessageslink' => 'sĭng làu-uâ',
'newmessagesdifflink' => 'sèng 1 huòi gāi-biéng',
'youhavenewmessagesfromusers' => '汝有趁$3用戶（$2）來其$1萆信息',
'youhavenewmessagesmanyusers' => '汝有趁雅価用戶（$2）其$1信息',
'newmessageslinkplural' => '$1條新其信息',
'newmessagesdifflinkplural' => '最後其改變',
'youhavenewmessagesmulti' => '汝有趁$1來其新信息',
'editsection' => 'Siŭ-gāi',
'editold' => 'Siŭ-gāi',
'viewsourceold' => '看源代碼',
'editlink' => '修改',
'viewsourcelink' => '看源代碼',
'editsectionhint' => 'Siŭ-gāi dâung: $1',
'toc' => 'Mŭk-liŏh',
'showtoc' => 'tāu',
'hidetoc' => 'káung',
'collapsible-collapse' => '崩潰',
'collapsible-expand' => '擴展',
'thisisdeleted' => 'Buóh káng hĕ̤k-ciā huŏi-hók $1?',
'viewdeleted' => 'Káng $1?',
'restorelink' => '{{PLURAL:$1|Ék|$1}} bĭk ké̤ṳk chēng lâi gì siŭ-gāi',
'feedlinks' => 'Cê̤ṳ-hăk:',
'feed-invalid' => '無乇使其下標填充類型',
'feed-unavailable' => '𣍐使聚合訂閱',
'site-rss-feed' => '$1 RSS 訂閱',
'site-atom-feed' => '$1原子填充',
'page-rss-feed' => '「$1」RSS填充',
'page-atom-feed' => '「$1」原子填充',
'red-link-title' => '$1（頁面無敆𡅏）',
'sort-descending' => '降序排序',
'sort-ascending' => '升序排序',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main' => 'Ùng-ciŏng',
'nstab-user' => 'Ê̤ṳng-hô hiĕk',
'nstab-media' => 'Muòi-tā̤ hiĕk',
'nstab-special' => '特殊頁面',
'nstab-project' => 'Gĕ̤ng-tiàng hiĕk',
'nstab-image' => 'Ùng-giông',
'nstab-mediawiki' => 'Siĕu-sék',
'nstab-template' => 'Muò-bēng',
'nstab-help' => 'Bŏng-cô hiĕk',
'nstab-category' => 'Lôi-biék',

# Main script and global functions
'nosuchaction' => '無茲蜀種行動',
'nosuchactiontext' => '茲蜀種URL指定其行動是𣍐合法其。',
'nosuchspecialpage' => 'Mò̤ cūng-kuāng gì dĕk-sṳ̀ hiĕk',
'nospecialpagetext' => '<strong>汝請求蜀萆𣍐合法其特殊頁面。</strong>

合法其特殊頁面清單會使敆[[Special:SpecialPages|{{int:特殊頁面}}]]頁面討著',

# General errors
'error' => '鄭咯',
'databaseerror' => 'Só-gé̤ṳ-kó ô dâng',
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
'readonly' => 'Só-gé̤ṳ-kó ké̤ṳk sō̤ kī',
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
'querypage-no-updates' => 'Cī-buàng buōng hiĕk-miêng mâ̤ huăng-sĭng. Só-gé̤ṳ iâ mâ̤ huăng-sĭng.',
'wrong_wfQuery_params' => '敆wfQuery()其鄭其參數<br />
函數：$1<br />
查詢：$2',
'viewsource' => 'Káng nguòng-dâi-mā',
'viewsource-title' => '覷蜀覷$1其源代碼',
'actionthrottled' => '行動乞取消咯',
'protectedpagetext' => '茲頁已經乞保護起咯，𣍐使修改或者其它行動。',
'viewsourcetext' => 'Nṳ̄ â̤-sāi káng gâe̤ng hók-cié ciā hiĕk gì nguòng-dâi-mā:',
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
'login' => 'Láuk-diē',
'nav-login-createaccount' => '躒底/開賬戶',
'loginprompt' => 'Páh kŭi cookies ciáh â̤ diē {{SITENAME}}.',
'userlogin' => 'Láuk-diē / kŭi dióng-hô̤',
'userloginnocreate' => '躒底',
'logout' => 'Láuk-chók',
'userlogout' => 'Láuk-chók',
'notloggedin' => '未躒底',
'userlogin-noaccount' => '汝無賬戶？',
'userlogin-joinproject' => '共{{SITENAME}}加底其',
'nologin' => '汝無賬戶？$1',
'nologinlink' => 'Kŭi 1 ciáh sĭng dióng-hô̤',
'createaccount' => 'Kŭi dióng-hô̤',
'gotaccount' => "Ī-gĭng ô dióng-hô lāu? '''$1'''.",
'gotaccountlink' => 'Láuk-diē',
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
'loginerror' => 'Láuk-diē ô dâng',
'loginsuccesstitle' => 'Láuk-diē sìng-gŭng',
'loginsuccess' => "'''Nṳ̄ hiêng-câi ī-gĭng ī \"\$1\" gì sĭng-hông láuk diē {{SITENAME}} lāu.'''",
'nosuchuser' => 'Mò̤ cūng-kuāng gì ê̤ṳng-hô-miàng "$1". Giēng-chă nṳ̄ gì pĭng-siā, hĕ̤k-ciā kŭi 1 ciáh sĭng dióng-hô̤.',
'nosuchusershort' => 'Mò̤ cūng-kuāng gì ê̤ṳng-hô-miàng "$1". Giēng-chă nṳ̄ gì pĭng-siā',
'wrongpassword' => 'Mĭk-mā dâng gó̤. Chiāng gái ché siŏh â.',
'wrongpasswordempty' => 'Muôi sṳ̆-ĭk mĭk-mā. Chiāng gái ché siŏh â.',
'passwordtooshort' => 'Nṳ̄ gì mĭk-mā kák dōi gó̤. Có̤i kī-mā diŏh ô $1 bĭk cê-mō̤.',
'mailmypassword' => 'Gié ké̤ṳk nguāi mĭk-mā',
'passwordsent' => 'Sĭng mĭk-mā ī-gĭng gié gáu "$1" sū cé̤ṳ-cháh gì diêng-piĕ dê-cī lāu.
Siŭ gáu hâiu, chiāng gái láuk diē siŏh làu.',
'acct_creation_throttle_hit' => 'Dó̤i-bók-cê̤ṳ, nṳ̄ ī-gĭng kŭi guó $1 dióng-hô̤, mâ̤-sāi gái kŭi 1 ciáh lāu.',
'emailauthenticated' => 'Nṳ̄ gì diêng-piĕ dê-cī găk $1 sèng-âu káuk-nêng guó lāu.',
'emailconfirmlink' => 'Káuk-nêng nṳ̄ gì diêng-piĕ dê-cī',
'accountcreated' => 'Dióng-hô̤ châung-gióng lāu',
'accountcreatedtext' => '$1 gì ê̤ṳng-hô dióng-hô̤ ī-gĭng châung-gióng lāu.',
'loginlanguagelabel' => 'Ngṳ̄-ngiòng: $1',

# Change password dialog
'oldpassword' => 'Gô mĭk-mā:',
'newpassword' => 'Sĭng mĭk-mā:',
'retypenew' => 'Káuk-nêng sĭng mĭk-mā:',

# Edit page toolbar
'bold_sample' => 'Chŭ-tā̤ ùng-cê',
'bold_tip' => 'Chŭ-tā̤ ùng-cê',
'link_sample' => 'Lièng-giék biĕu-dà̤',
'link_tip' => 'Nô̤i-buô lièng-giék',
'extlink_tip' => 'Nguôi-buô lièng-giék (gé lā̤ http:// sṳ̀-tàu)',
'headline_sample' => 'Biĕu-dà̤ ùng-cê',
'headline_tip' => 'Dâ̤ 2 cèng biĕu-dà̤',
'media_sample' => 'Liê.ogg',
'media_tip' => 'Mùi-tā̤ ùng-giông lièng-giék',

# Edit pages
'summary' => 'Cūng-giék:',
'subject' => 'Dà̤-mĕ̤k/biĕu-dà̤:',
'minoredit' => 'Guó-éu siŭ-gāi',
'watchthis' => 'Gáng-sê ciā hiĕk',
'savearticle' => 'Bō̤-còng ciā hiĕk',
'preview' => 'Ché káng mâing',
'showpreview' => 'Ché káng mâing',
'showdiff' => 'Káng gāi-biéng gì buô-hông',
'anoneditwarning' => "'''GĪNG-GÓ̤:''' Nṳ̄ muôi láuk-diē.
Nṳ̄ gì IP dê-cī â̤ ké̤ṳk gé diŏh ciā hiĕk-miêng siŭ-gāi lĭk-sṳ̄ diē-sié.",
'summary-preview' => 'Cūng-giék ché-káng:',
'blockedtitle' => 'Ê̤ṳng-hô ké̤ṳk hŭng-sō̤ lāu',
'loginreqtitle' => 'Chiāng sĕng láuk-diē',
'loginreqlink' => 'Láuk-diē',
'loginreqpagetext' => 'Sĕng $1 ciáh â̤-sāi káng gì-tă hiĕk-miêng.',
'accmailtitle' => 'Mĭk-mā gié chók lāu.',
'accmailtext' => '"$1" gì mĭk-mā ī-gĭng gié ké̤ṳk $2 lāu.',
'newarticle' => '(Sĭng)',
'newarticletext' => "Nṳ̄ téng 1 ciáh lièng-giék lì gáu cī miêng gó muôi còng-câi gì hiĕk. Buóh kī-chiū piĕng-siā ciā hiĕk, chiāng diŏh â-dā̤ gì bēng-bēng diē-sié páh cê (chăng-kō̤ [[Help:Mŭk-liŏh]] liēu-gāi gáing sâ̤ séng-sék). Iŏk-sṳ̄ nṳ̄ huák-hiêng cê-gă giàng dâng gó̤, nâ dĭh áik báuk-lāng-ké (''browser'') gì \"'''duōng kó̤ sèng 1 hiĕk'''\" (''back'') cêu â̤-sāi lāu.",
'anontalkpagetext' => "----''Cī tiŏng tō̤-lâung-hiĕk mò̤ gó-dêng gì dióng-hô̤, nâ ô 1 ciáh IP dê-cī. Chiāng cé̤ṳ-é: Kō̤-nèng ng-nié 1 ciáh nè̤ng sāi-ê̤ṳng cī ciáh IP dê-cī. Iŏk-sṳ̄ nṳ̄ gó muôi kŭi 1 ciáh dióng-hô̤ bêng-chiā giéng-gáe̤k ciā làu-uâ sê làu ké̤ṳk nṳ̄ gì, chiāng nṳ̄ [[Special:UserLogin|kŭi 1 ciáh dióng-hô̤ hĕ̤k-ciā láuk-diē]], cêu â̤ piáh-miēng ī-hâiu gái huák-sĕng cūng-kuāng ông-dà̤.''",
'noarticletext' => 'Ciā hiĕk-miêng gà̤-dēng mò̤ ùng-cê. Nṳ̄ â̤-sāi găk gì-tă hiĕk-miêng [[Special:Search̤/{{PAGENAME}}|sìng-tō̤ ĭ gì biĕu-dà̤]] hĕ̤k-ciā [{{fullurl:{{FULLPAGENAME}}|action=edit}} cê-gă siā].',
'clearyourcache' => "'''Cé̤ṳ-é:''' Bō̤-còng cĭ hâiu, kō̤-nèng diŏh tĕ̤ng táh báuk-lāng-ké gì ká̤-chṳ̄ ciáh â̤ káng-giéng diŏh gāi-biéng. '''Mozilla / Firefox / Safari:''' áik ''Reload'' sèng-âu áik diâng ''Shift'', hĕ̤k-ciā áik ''Ctrl-Shift-R'' (Apple Mac sê ''Cmd-Shift-R''); '''IE:''' áik ''Refresh'' sèng-âu áik diâng ''Ctrl'', hĕ̤k-ciā áik ''Ctrl-F5''; '''Konqueror:''' nâ sāi áik ''Reload'', hĕ̤k-ciā áik ''F5''; '''Opera''' ê̤ṳng-hô buóh tĕ̤ng táh ká̤-chṳ̄, chiāng sāi gă-sĭ ''Tools→Preferences''.",
'previewnote' => "'''Cé̤ṳ-é: Cuòi nâ sê ché káng ùng-cê gì iông-sék; nṳ̄ sū có̤ gì siŭ-gāi gó muôi bō̤-còng!'''",
'editing' => 'Siŭ-gāi $1',
'editingsection' => 'Siŭ-gāi $1 (dâung)',
'editingcomment' => 'Siŭ-gāi $1 (pàng-lâung)',
'editconflict' => 'Siŭ-gāi dó̤i-chṳ̆ng: $1',
'explainconflict' => "Bĕk-nè̤ng diŏh nṳ̄ tā-sĕng siŭ-gāi cī miêng hiĕk gì sèng-âu ô có̤ gì-tă siŭ-gāi.
Gà̤-dēng gì bēng-bēng hiēng-sê gì sê hiêng-câi có̤i sĭng gì bēng-buōng.
Nṳ̄ sū có̤ gì gāi-biéng găk â-dā̤ gì bēng-bēng diē-sié.
Nṳ̄ sṳ̆-iéu găk gà̤-dēng gì bēng-bēng diē-sié cīng-hăk nṳ̄ lâng ciáh nè̤ng sū có̤ gì gāi-biéng.
Iŏk-guō nṳ̄ dĭk-ciék áik \"{{int:savearticle}}\", '''nâ ô''' gà̤-dēng bēng-bēng diē-sié gì ùng-cê â̤ ké̤ṳk bō̤-còng.",
'yourtext' => 'Nṳ̄ gì ùng-cê',
'editingold' => "'''GĪNG-GÓ̤: Nṳ̄ hiêng-câi lā̤ siŭ-gāi ciā hiĕk-miêng ī-gĭng guó-gĭ gì bēng-buōng. Nṳ̄ nâ bō̤-còng ĭ, cī ciáh gô bēng-buōng cĭ-hâiu gì siŭ-gāi cêu mò̤ lāu.'''",
'yourdiff' => 'Chă-biék',
'readonlywarning' => "'''Gīng-gó̤: Ôi lāu mì-hô buōng câng, só-gé̤ṳ-kó ké̤ṳk sō̤ kī lāu, gó-chṳ̄ cī-lùng nṳ̄ mò̤ nièng-ngài bō̤-còng nṳ̄ gì siŭ-gāi. Chiāng sĕng bō̤-còng diŏh nṳ̄ diêng-nō̤ buōng-dê, dīng nék-gū mâing gái ché.'''",
'protectedpagewarning' => "'''GĪNG-GÓ̤: Ciā hiĕk ī-gĭng ké̤ṳk sō̤ kī go̤ lāu, nâ ô guāng-lī-uòng â̤ siŭ-gāi ĭ.'''",
'semiprotectedpagewarning' => "'''Cé̤ṳ-é:''' Ciā hiĕk-miêng ī-gĭng ké̤ṳk bō̤-hô, gó-chṳ̄ nâ ô láuk-diē gì ê̤ṳng-hô â̤-sāi siŭ-gāi ĭ.",
'templatesused' => 'Ciā hiĕk gà̤-dēng gì muò-bēng:',
'templatesusedpreview' => 'Ciā ché-káng-hiĕk gà̤-dēng gì muò-bēng:',
'templatesusedsection' => 'Cī dâung diē-sié gì muò-bēng:',
'template-protected' => '(bō̤-hô)',
'template-semiprotected' => '(buáng bō̤-hô)',
'recreate-moveddeleted-warn' => "'''Gīng-gó̤: Nṳ̄ ciŏng-buóh dṳ̀ng-sĭng kŭi siŏh tiŏng gô-dā̤ ké̤ṳk chēng lâi gì hiĕk.'''

Nṳ̄ găi-dŏng sṳ̆-liòng lâ, sié lŏ̤h piĕng-cĭk ciā hiĕk-miêng ô gák céng-cháik mò̤. Ôi lāu că-sùng lê-biêng, ciā hiĕk-miêng gì chēng hiĕk nĭk-cé găk cŭ-uái â̤ tō̤ diŏh:",

# "Undo" feature
'undo-summary' => 'Chṳ̄-siĕu [[Special:Contributions/$2|$2]] ([[User talk:$2|Tō̤-lâung]]) gì $1 hô̤ siŭ-gāi',

# Account creation failure
'cantcreateaccounttitle' => 'Mò̤ nièng-ngài kŭi dióng-hô̤',

# History pages
'viewpagelogs' => 'Káng cī miêng hiĕk gì nĭk-cé',
'nohistory' => 'Ciā hiĕk mò̤ siŭ-gāi lĭk-sṳ̄.',
'currentrev' => 'Hiêng-sì bēng-buōng',
'revisionasof' => '$1 gì bēng-buōng',
'previousrevision' => '←Gă gô gì bēng-buōng',
'nextrevision' => 'Gă sĭng gì bēng-buōng→',
'currentrevisionlink' => 'Hiêng-sì bēng-buōng',
'cur' => 'dāng',
'next' => 'â',
'last' => 'sèng',
'page_first' => 'tàu',
'page_last' => 'muōi',
'histlegend' => 'Chă-biék gēng-sōng: sōng-dĕk buóh bī-piâng gì bēng-buōng, gái áik "huòi-chiă" (\'\'enter\'\') hĕ̤k-ciā dā̤-dā̤ gì "Bī-piâng gēng-sōng bēng-buōng".<br />
Siók-mìng: (dāng) = gâe̤ng dék sĭng bēng-buōng bī-piâng, (sèng) = gâe̤ng sèng siŏh bēng-buōng bī-piâng, ~ = guó-éu siŭ-gāi.',
'histfirst' => 'Có̤i cā',
'histlast' => 'Có̤i dì',
'historysize' => '({{PLURAL:$1|1|$1}} cê-ciék)',

# Revision feed
'history-feed-title' => 'Siŭ-gāi lĭk-sṳ̄',
'history-feed-description' => 'Wiki gà̤-dēng cī miêng hiĕk gì siŭ-gāi lĭk-sṳ̄',

# Revision deletion
'rev-delundel' => 'tāu/káung',

# Diffs
'history-title' => '"$1" gì siŭ-gāi lĭk-sṳ̄',
'lineno' => 'Dâ̤ $1 hòng:',
'compareselectedversions' => 'Bī-piâng gēng-sōng bēng-buōng',
'editundo' => 'chṳ̄-siĕu',
'diff-multi' => '(Dài-dŏng ô {{PLURAL:$1|ék|$1}} bĭk bēng-buōng mò̤ hiēng-sê.)',

# Search results
'searchresults' => 'Sìng-tō̤ giék-guō',
'searchresulttext' => 'Buóh liēu-gāi diŏh {{SITENAME}} sìng-tō̤ ùng-ciŏng gì gáing sâ̤ séng-sék, chiāng chăng-kō̤ [[{{ns:project}}:Sìng-tō̤]].',
'searchsubtitle' => "Nṳ̄ sìng-tō̤ '''[[:$1]]'''",
'searchsubtitleinvalid' => "Nṳ̄ sìng-tō̤ '''$1'''",
'prevn' => 'sèng {{PLURAL:$1|$1}} hâung',
'nextn' => 'â {{PLURAL:$1|$1}} hâung',
'viewprevnext' => 'Káng ($1 {{int:pipe-separator}} $2) ($3).',
'showingresults' => "Hiēng-sê téng #<b>$2</b> kăi-sṳ̄ gì {{PLURAL:$1|'''1'''|'''$1'''}} bĭk giék-guō.",
'showingresultsnum' => "Hiēng-sê téng #<b>$2</b> kăi-sṳ̄ gì {{PLURAL:$3|'''1'''|'''$3'''}} bĭk giék-guō.",

# Preferences page
'preferences' => 'Siék-diâng',
'mypreferences' => 'Nguāi gì siék-diâng',
'prefs-edits' => 'Siŭ-gāi ché̤ṳ-só:',
'changepassword' => 'Gāi-biéng mĭk-mā',
'prefs-skin' => 'Puòi-hŭ',
'datedefault' => 'Mò̤ siék-diâng',
'prefs-datetime' => 'Nĭk-gĭ gâe̤ng sì-găng',
'prefs-personal' => 'Ê̤ṳng-hô cṳ̆-lâiu',
'prefs-rc' => 'Cī-bŏng gì gāi-biéng',
'prefs-watchlist' => 'Gáng-sê-dăng',
'prefs-misc' => 'Gì-tă',
'saveprefs' => 'Bō̤-còng',
'resetprefs' => 'Dṳ̀ng-sĭng siék-diâng',
'searchresultshead' => 'Sìng-tō̤',
'resultsperpage' => 'Mūi hiĕk huōng-ông-liông:',
'recentchangescount' => 'Cī-bŏng gāi-biéng gì dà̤-mĕ̤k:',
'savedprefs' => 'Nṳ̄ gì siék-diâng ī-gĭng ké̤ṳk bō̤-còng hō̤ lāu.',
'timezonelegend' => 'Sì-kṳ̆',
'localtime' => 'Buōng-dê sì-găng',
'timezoneoffset' => 'Sì-chă¹',
'servertime' => 'Hŭk-ô-ké sì-găng',
'allowemail' => 'Â̤ kīng bĕk-nè̤ng huák diêng-piĕ ké̤ṳk nṳ̄',
'prefs-files' => 'Ùng-giông',
'youremail' => 'Diêng-piĕ:',
'username' => 'Ê̤ṳng-hô-miàng:',
'uid' => 'Ê̤ṳng-hô ID:',
'yourrealname' => 'Cĭng miàng:',
'yourlanguage' => 'Ngṳ̄-ngiòng:',
'yournick' => 'Nguôi-hô̤:',
'email' => 'Diêng-piĕ',
'prefs-help-email' => '* Diêng-piĕ (kō̤-sōng): Â̤-kīng bĕk-nè̤ng mâ̤ báik nṳ̄ sĭng-hông cêu dĭk-ciék tŭng-guó nṳ̄ gì ê̤ṳng-hô-hiĕk hĕ̤k tō̤-lâung-hiĕk lièng-hiê nṳ̄.',

# User rights
'editusergroup' => 'Siŭ-gāi Ê̤ṳng-hô Cū',

# Groups
'group' => 'Cū:',
'group-bot' => 'Gĭ-ké-nè̤ng',
'group-sysop' => 'Guāng-lī-uòng',
'group-bureaucrat' => 'Guăng-lièu',

'group-bot-member' => 'Gĭ-ké-nè̤ng',
'group-sysop-member' => 'Guāng-lī-uòng',
'group-bureaucrat-member' => 'Guăng-lièu-cū',

# Special:Log/newusers
'newuserlogpage' => 'Kŭi dióng-hô̤ nĭk-cé',

# Recent changes
'recentchanges' => 'Cī-bŏng gì gāi-biéng',
'recentchanges-summary' => 'Găk cī hiĕk dŭi-sùi wiki cī-bŏng dék sĭng gì gāi-biéng.',
'rcnote' => 'Â-dā̤ sê <strong>{{PLURAL:$1|ék|$2}}</strong> gĕ̤ng ī-nô̤i (hiêng-câi sê $3) dék sĭng gì <strong>{{PLURAL:$1|1|$1}}</strong> hâung gāi-biéng.',
'rclistfrom' => 'Hiēng-sê iù $1 kăi-sṳ̄ gì sĭng gāi-biéng',
'rcshowhideminor' => '$1 guó-éu siŭ-gāi',
'rcshowhidebots' => '$1 gĭ-ké-nè̤ng',
'rcshowhideliu' => '$1 láuk-diē ê̤ṳng-hô',
'rcshowhideanons' => '$1 mò̤-miàng ê̤ṳng-hô',
'rcshowhidemine' => '$1 nguāi gì siŭ-gāi',
'rclinks' => 'Hiēng-sê $2 gĕ̤ng ī-nô̤i dék sĭng gì $1 hâung gāi-biéng<br />$3',
'diff' => 'chă',
'hist' => 'sṳ̄',
'hide' => 'Káung kī',
'show' => 'Hiēng-sê',
'minoreditletter' => '~',
'newpageletter' => '!',
'boteditletter' => '^',

# Recent changes linked
'recentchangeslinked' => 'Siŏng-guăng gì gāi-biéng',
'recentchangeslinked-feed' => 'Siŏng-guăng gì gāi-biéng',
'recentchangeslinked-toolbox' => 'Siŏng-guăng gì gāi-biéng',

# Upload
'upload' => 'Siông-duòng ùng-giông',
'uploadbtn' => 'Siông-duòng ùng-giông',
'reuploaddesc' => 'Duōng kó̤ siông-duòng dăng-dăng.',
'uploadnologin' => 'Mò̤ láuk-diē',
'uploadnologintext' => 'Sĕng [[Special:UserLogin|láuk-diē]] ciáh â̤-sāi siông-duòng ùng-giông.',
'uploaderror' => 'Siông-duòng ô dâng',
'uploadlog' => 'siông-duòng nĭk-cé',
'uploadlogpage' => 'Siông-duòng nĭk-cé',
'uploadlogpagetext' => 'Â-dā̤ sê gé-luŏh cī-bŏng ùng-giông siông-duòng gì dăng-dăng.',
'filename' => 'Ùng-giông-miàng',
'filedesc' => 'Cūng-giék',
'fileuploadsummary' => 'Cūng-giék:',
'filesource' => 'Lài-nguòng:',
'uploadedfiles' => 'Siông-duòng ùng-giông',
'ignorewarning' => 'Mò̤ sê̤ṳ gīng-gó̤ bō̤-còng ùng-giông.',
'ignorewarnings' => 'Mò̤ sê̤ṳ gīng-gó̤',
'fileexists' => 'Ī-gĭng ô siŏh bĭk dè̤ng miàng ùng-giông, nṳ̄ nâ mâ̤ káuk-dêng nṳ̄ sê-ng-sê dŏng-cĭng páh-sáung gāi-biéng ĭ, chiāng giēng-chă <strong>[[:$1]]</strong>.
[[$1|thumb]]',
'uploadwarning' => 'Siông-duòng gīng-gó̤',
'savefile' => 'Bō̤-còng ùng-giông',
'uploadedimage' => 'siông-duòng "[[$1]]"',
'uploadvirus' => 'Ciā ùng-giông ô bêng-dŭk! Sá̤-ciék: $1',
'sourcefilename' => 'Nguòng-sṳ̄ ùng-giông-miàng:',
'destfilename' => 'Mŭk-biĕu ùng-giông-miàng:',
'watchthisupload' => 'Gáng-sê ciā hiĕk',
'upload-success-subj' => 'Siông-diòng sìng-gŭng',

# Special:ListFiles
'imgfile' => 'ùng-giông',
'listfiles' => 'Ùng-giông dăng-dăng',
'listfiles_date' => 'Nĭk-gĭ',
'listfiles_name' => 'Miàng',
'listfiles_user' => 'Ê̤ṳng-hô',
'listfiles_size' => 'Chióh-cháung',

# File description page
'file-anchor-link' => 'Ùng-giông',
'imagelinks' => 'Lièng-giék',
'linkstoimage' => 'Â-dā̤ gì hiĕk-miêng lièng gáu ciā ùng-giông:',
'nolinkstoimage' => 'Mò̤ hiĕk-miêng lièng gáu ciā ùng-giông.',
'uploadnewversion-linktext' => 'Siông-duòng ciā ùng-giông gì sĭng bēng-buōng',

# MIME search
'download' => 'hâ-diòng',

# Unwatched pages
'unwatchedpages' => 'Mò̤ gáng-sê gì hiĕk-miêng',

# List redirects
'listredirects' => 'Dṳ̀ng-sĭng dêng-hióng hiĕk dăng-dăng',

# Unused templates
'unusedtemplateswlh' => 'gì-tă lièng-giék',

# Random page
'randompage' => 'Sùi-biêng muōng káng',

# Random redirect
'randomredirect' => 'Muōng káng dṳ̀ng-sĭng dêng-hióng',

# Statistics
'statistics' => 'Só-gé̤ṳ',
'statistics-header-users' => 'Ê̤ṳng-hô só-gé̤ṳ',

'disambiguationspage' => 'Template:Gì-ngiê',

'brokenredirects-edit' => 'gāi',
'brokenredirects-delete' => 'chēng',

'withoutinterwiki' => 'Mò̤ kuá wiki gì hiĕk',
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
'shortpages' => 'Dōi hiĕk',
'longpages' => 'Dòng hiĕk',
'protectedpages' => 'Bō̤-hô hiĕk',
'listusers' => 'Ê̤ṳng-hô-dăng',
'newpages' => 'Sĭng hiĕk',
'newpages-username' => 'Ê̤ṳng-hô-miàng:',
'ancientpages' => 'Dék gô gì hiĕk-miêng',
'move' => 'Iè-dông',
'movethispage' => 'Iè-dông ciā hiĕk',

# Book sources
'booksources' => 'Cṳ̆ nguòng',
'booksources-search-legend' => 'Sìng-tō̤ cṳ̆ nguòng',
'booksources-go' => 'Kó̤',
'booksources-text' => 'Â-dā̤ sê mâ̤ cṳ̆ uōng-câng gì dăng-dăng, kō̤-nèng ô nṳ̄ buóh tō̤ gì cṳ̆ gì gáing sâ̤ séng-sék:',

# Special:Log
'specialloguserlabel' => 'Ê̤ṳng-hô:',
'speciallogtitlelabel' => 'Dà̤-mĕ̤k:',
'log' => 'Nĭk-cé',
'alllogstext' => "Siông-diòng (''upload''), chēng (''deletion''), bō̤-hô (''protection''), hŭng-sō̤ (''blocking''), gâe̤ng guāng-lī-uòng (''sysop'') nĭk-cé ciòng-buô hiēng-sê diŏh â-dā̤. Nṳ̄ â̤-sāi gēng-sōng nĭk-cé lôi-biék, ê̤ṳng-hô gì miàng, hĕ̤k-ciā 1 tiŏng hiĕk lì gāng-huá giék-guō.",
'logempty' => 'Nĭk-cé diē-sié tō̤ mâ̤ diŏh hâung-mŭk.',

# Special:AllPages
'allpages' => 'Sū-iū hiĕk-miêng',
'alphaindexline' => '$1 gáu $2',
'nextpage' => 'Â 1 hiĕk ($1)',
'prevpage' => 'Sèng 1 hiĕk ($1)',
'allpagesfrom' => 'Iù ciā cê-mō̤ kăi-sṳ̄ gì miàng:',
'allarticles' => 'Sū-iū ùng-ciŏng',
'allinnamespace' => 'Sū-iū hiĕk-miêng ($1 miàng-kŭng-găng)',
'allnotinnamespace' => 'Sū-iū hiĕk-miêng (mò̤ diŏh $1 miàng-kŭng-găng)',
'allpagesprev' => 'Sèng 1 hiĕk',
'allpagesnext' => 'Â 1 hiĕk',
'allpagessubmit' => 'Kó̤',
'allpagesprefix' => 'Áng cê-tàu hiēng-sê:',
'allpagesbadtitle' => 'Nṳ̄ sṳ̆-ĭk gì biĕu-dà̤ buōng câng mò̤ ciĕ-tì.',

# Special:Categories
'categories' => 'Lôi-biék',

# Special:DeletedContributions
'deletedcontributions' => 'Ké̤ṳk chēng lâi gì ê̤ṳng-hô góng-hióng',
'deletedcontributions-title' => 'Ké̤ṳk chēng lâi gì ê̤ṳng-hô góng-hióng',

# Special:LinkSearch
'linksearch-ok' => 'Sìng-tō̤',

# Email user
'emailuser' => 'Gié diêng-piĕ ké̤ṳk ĭ',
'emailpage' => 'Gié diêng-piĕ ké̤ṳk ĭ',
'defemailsubject' => '{{SITENAME}} diêng-piĕ',
'noemailtitle' => 'Mò̤ diêng-piĕ dê-cī',
'emailfrom' => 'Iù',
'emailto' => 'Ké̤ṳk',
'emailsubject' => 'Ciō-dà̤',
'emailmessage' => 'Siĕu-sék',
'emailsend' => 'Gié',
'emailsent' => 'Diêng-piĕ huák chók lāu',
'emailsenttext' => 'Nṳ̄ gì diêng-piĕ siĕu-sék ī-gĭng gié chók lāu.',

# Watchlist
'watchlist' => 'Nguāi gì gáng-sê-dăng',
'mywatchlist' => 'Nguāi gì gáng-sê-dăng',
'nowatchlist' => 'Nṳ̄ gì gáng-sê-dăng gà̤-dēng mò̤ dèu-mĕ̤k.',
'watchnologin' => 'Mò̤ láuk diē',
'addedwatchtext' => "\"[[:\$1]]\" ī-gĭng gă-tiĕng gáu nṳ̄ gì [[Special:Watchlist|gáng-sê-dăng]] lāu. Â-nĭk, ciā hiĕk gâe̤ng ĭ tō̤-lâung hiĕk gì gāi-biéng cêu â̤ hiēng-sê diŏh hē̤-nē̤; bêng-chiā, nṳ̄ gáng-sê gì hiĕk găk \"[[Special:RecentChanges|Có̤i-gê̤ṳng gì gāi-biéng]]\" dăng-dăng gà̤-dēng gì cê-tā̤ â̤ có̤ '''chŭ-chŭ-nuóh'''.

Iŏk-sṳ̄ nṳ̄ buóh-siōng téng nṳ̄ gáng-sê-dăng gà̤-dēng dṳ̀ lâi ciā hiĕk, áik kóng-cié-dèu (''sidebar'') siông gì \"ng-sāi gáng-sê\", cêu â̤-sāi lāu.",
'removedwatchtext' => '"[[:$1]]" hiĕk ī-gĭng téng nṳ̄ gì gáng-sê-dăng gà̤-dēng chēng lâi gó̤.',
'watch' => 'Gáng-sê',
'watchthispage' => 'Gáng-sê ciā hiĕk',
'unwatch' => 'Ng-sāi gáng-sê',
'watchlist-details' => '{{PLURAL:$1|$1|$1}} tiŏng hiĕk ké̤ṳk gáng-sê, mò̤ bău-guăk tō̤-lâung-hiĕk.',
'wlshowlast' => 'Hiēng-sê có̤i hâiu $1 dēng-cṳ̆ng $2 gĕ̤ng $3',

# Displayed when you click the "watch" button and it is in the process of watching
'watching' => 'Gáng-sê...',

# Delete
'deletepage' => 'Chēng hiĕk',
'confirm' => 'Káuk-nêng',
'excontent' => "nô̤i-ṳ̀ng sê: '$1'",
'excontentauthor' => "nô̤i-ṳ̀ng sê: '$1' (bêng-chiā cáuk-ciā nâ ô '[[Special:Contributions/$2|$2]]')",
'exbeforeblank' => "dù táh cĭ-sèng gì nô̤i-ṳ̀ng sê: '$1'",
'historywarning' => 'Gīng-gó̤: Nṳ̄ buóh-ái chēng lâi gì hiĕk-miêng ô lĭk-sṳ̄:',
'confirmdeletetext' => 'Nṳ̄ cūng-bê ciŏng ciā hiĕk-miêng hĕ̤k ùng-giông lièng ĭ găk só-gé̤ṳ-kó gì lĭk-sṳ̄ ciòng-buô chēng lâi. Chiāng nṳ̄ káuk-nêng: nṳ̄ dŏng-cĭng buóh siōng cūng-kuāng có̤, nṳ̄ liēu-gāi cūng-kuāng có̤ gì hâiu-guō, bêng-chiā nṳ̄ cūng-kuāng có̤ sê hù-hăk [[{{MediaWiki:Policy-url}}]].',
'actioncomplete' => 'Cék-hèng sìng-gŭng',
'deletedtext' => '"$1" ī-gĭng ké̤ṳk chēng lâi go̤ lāu. Cī-bŏng chēng hiĕk gì gé-liŏh dŭ gé diŏh $2.',
'dellogpage' => 'Chēng hiĕk nĭk-cé',
'dellogpagetext' => 'Â-dā̤ sê gé-liŏh cī-bŏng chēng hiĕk gì dăng-dăng.',
'deletionlog' => 'chēng hiĕk nĭk-cé',
'deletecomment' => 'Nguòng-ĭng',

# Rollback
'rollback' => 'Gâe̤ng siŭ-gāi duōng kó̤',
'rollback_short' => 'Duōng',
'rollbacklink' => 'duōng',
'rollbackfailed' => 'Duōng mâ̤ kó̤',
'cantrollback' => 'Mò̤ bâing-huák huòi-tó̤i siŭ-gāi; sèng 1 ciáh góng-hióng-ciā sê ciā hiĕk mì-ék gì cáuk-ciā.',
'alreadyrolled' => 'Mò̤ nièng-ngài huòi-tó̤i [[User:$2|$2]] ([[User talk:$2|Tō̤-lâung]]) có̤i âu sū có̤ gì [[$1]] siŭ-gāi; bĕk-nè̤ng ī-gĭng siū-gái hĕ̤k-ciā huòi-tó̤i ciā hiĕk-miêng go̤ lāu.

Có̤i âu gì siŭ-gāi sê [[User:$3|$3]] ([[User talk:$3|Tō̤-lâung]]) sū có̤ gì.',
'editcomment' => "Siŭ-gāi pàng-lâung sê: \"''\$1''\".",
'revertpage' => '[[Special:Contributions/$2|$2]] ([[User talk:$2|Talk]]) sū có̤ gì siŭ-gāi duōng kó̤ [[User:$1|$1]] gì sèng 1 bĭk bēng-buōng',

# Protect
'protectlogpage' => 'Bō̤-hô nĭk-cé',
'protect-title' => 'Bō̤-hô "$1"',
'prot_1movedto2' => '[[$1]] iè gáu [[$2]]',
'protect-legend' => 'Káuk-nêng bō̤-hô',
'protectcomment' => 'Nguòng-ĭng',
'protect-level-autoconfirmed' => 'Hŭng-sō̤ mò̤ cé̤ṳ-cháh ê̤ṳng-hô̤',
'protect-level-sysop' => 'Nâ guāng-lī-uòng',
'protect-expiry-options' => '2 dēng-cṳ̆ng:2 hours,1 gĕ̤ng:1 day,3 gĕ̤ng:3 days,1 lā̤-buái:1 week,2 lā̤-buái:2 weeks,1 month:1 nguŏk-nĭk,3 nguŏk-nĭk:3 months,6 nguŏk-nĭk:6 months,1 nièng:1 year,īng-uōng:infinite',
'restriction-type' => 'Guòng-âing:',
'restriction-level' => 'Âing-cié dēng-gék:',
'minimum-size' => 'Có̤i nâung chióh-cháung',
'maximum-size' => 'Có̤i duâi chióh-cháung',
'pagesize' => '(cê-ciék)',

# Restrictions (nouns)
'restriction-edit' => 'Siŭ-gāi',
'restriction-move' => 'Iè-dông',

# Restriction levels
'restriction-level-sysop' => 'cuòng bō̤-hô',
'restriction-level-autoconfirmed' => 'buáng bō̤-hô',
'restriction-level-all' => 'sū-iū dēng-gék',

# Undelete
'undeletepage' => 'Káng bêng-chiā hŭi-hók ké̤ṳk chēng lâi gì hiĕk-miêng',
'viewdeletedpage' => 'Káng chēng lâi gì hiĕk',
'undeleteextrahelp' => "Buóh gâe̤ng gó̤-lòng hiĕk dŭ hŭi-hók, chiāng ng-sāi sōng \"Hiĕk-miêng lĭk-sṳ̄\" â-dā̤ gì ăk-ăk, áik '''''Hŭi-hók''''' cêu â̤-sāi lāu. Buóh hŭi-hók gēng-sōng gì lĭk-sṳ̄, chiāng sōng-dĕk nṳ̄ buóh hŭi-hók gì hiĕk-miêng lĭk-sṳ̄ sèng-sāu gì ăk-ăk gái áik '''''Hŭi-hók'''''. Áik '''''Dṳ̀ng-sĭng siā''''' â̤ cháe̤ lâi pàng-lâung gáh-gáh gâe̤ng sōng-dĕk ăk-ăk.",
'undeletehistory' => 'Nṳ̄ nâ hŭi-hók ciā hiĕk-miêng, sū-iū bēng-buōng dŭ â̤ hŭi-hók gáu siŭ-gāi lĭk-sṳ̄ diē-sié. Iŏk-sṳ̄ ciā hiĕk-miêng ké̤ṳk chēng lâi cĭ hâiu bô ô kŭi siŏh tiŏng dè̤ng miàng gì sĭng hiĕk-miêng, huòi câi-cā ké̤ṳk chēng lâi gì bēng-buōng â̤ chók-hiêng diŏh dék sĭng gì lĭk-sṳ̄ diē-sié, dáng-sê, sĭng hiĕk-miêng gì hiêng-sì bēng-buōng ĭng-nguòng mò̤ biéng.',
'undeletebtn' => 'Hŭi-hók',
'undeletereset' => 'Dṳ̀ng-sĭng siā',
'undeletecomment' => 'Pàng-lâung:',
'undelete-search-submit' => 'Sìng-tō̤',

# Namespace form on various pages
'namespace' => 'Miàng-kŭng-găng:',
'invert' => 'Huāng sōng',

# Contributions
'contributions' => 'Ê̤ṳng-hô góng-hióng',
'mycontris' => 'Nguāi gì góng-hióng',
'uctop' => ' (dék sĭng)',
'month' => 'Téng nguŏk-hông (gâe̤ng gáing cā):',
'year' => 'Téng nièng-hông (gâe̤ng gáing cā):',

'sp-contributions-newbies' => 'Nâ hiēng-sê sĭng kŭi dióng-hô gì góng-hióng',
'sp-contributions-newbies-sub' => 'Ciáh lì gì',
'sp-contributions-blocklog' => 'Hŭng-sō̤ nĭk-cé',
'sp-contributions-deleted' => 'Ké̤ṳk chēng lâi gì ê̤ṳng-hô góng-hióng',
'sp-contributions-talk' => 'Tō̤-lâung',
'sp-contributions-search' => 'Sìng-tō̤ góng-hióng',
'sp-contributions-username' => 'IP dê-cī hĕ̤k ê̤ṳng-hô-miàng:',
'sp-contributions-submit' => 'Sìng-tō̤',

# What links here
'whatlinkshere' => 'Diē-nē̤ lièng gáu cē̤-nē̤',
'whatlinkshere-title' => 'Lièng gáu $1 gì hiĕk-miêng',
'linkshere' => "Â-dā̤ gì hiĕk-miêng lièng gáu '''[[:$1]]''':",
'nolinkshere' => "Mò̤ hiĕk-miêng lièng gáu '''[[:$1]]'''.",
'isredirect' => 'dṳ̀ng-sĭng dêng-hióng hiĕk',
'whatlinkshere-prev' => '{{PLURAL:$1|sèng|sèng $1 hâung}}',
'whatlinkshere-next' => '{{PLURAL:$1|â|â $1 hâung}}',
'whatlinkshere-links' => '← lièng-giék',

# Block/unblock
'blockip' => 'Hŭng-sō̤ ê̤ṳng-hô',
'blockiptext' => 'Sāi-ê̤ṳng â-dā̤ gì dăng-dăng lì hŭng-sō̤ IP dê-cī hĕ̤k-ciā ê̤ṳng-hô-miàng gì siā guòng-âing. Cuòi nâ sê ôi lāu huòng-cī nè̤ng cáuk-ták wiki, bêng-chiā găi-dŏng hù-hăk [[{{MediaWiki:Policy-url}}|céng-cháik]]. Chiāng diŏh â-dā̤ siā giâ hŭng-sō̤ gì nguòng-ĭng (pī-ṳ̀-gōng, īng-ê̤ṳng ké̤ṳk cáuk-ták gì hiĕk-miêng).',
'ipadressorusername' => 'IP dê-cī hĕ̤k ê̤ṳng-hô-miàng:',
'ipbexpiry' => 'Guó-gĭ:',
'ipbreason' => 'Nguòng-ĭng',
'ipbreasonotherlist' => 'Bĕk gì nguòng-ĭng',
'ipbreason-dropdown' => '*Pū-tŭng hŭng-sō̤ nguòng-ĭng
** Gă-tiĕng gā gì séng-sék
** Dù lâi hiĕk-miêng nô̤i-ṳ̀ng
** Huák-buó bóng-só̤ séng-sék
** Luâng siā ùng-cê
** Có̤-hák / lièu-sê̤ṳ
** Luâng kŭi dŏ̤ dióng-hô̤
** Luâng kī ê̤ṳng-hô-miàng',
'ipbcreateaccount' => 'Huòng-cī kŭi dióng-hô̤',
'ipbemailban' => 'Huòng-cī ê̤ṳng-hô gié diêng-piĕ',
'ipbenableautoblock' => 'Cê̤ṳ-dông hŭng-sō̤ ciā ê̤ṳng-hô siā-ê̤ṳng gì IP dê-cī',
'ipbsubmit' => 'Hŭng-sō̤ ciā ê̤ṳng-hô',
'ipbother' => 'Gì-tă sì-găng',
'ipboptions' => '2 dēng-cṳ̆ng:2 hours,1 gĕ̤ng:1 day,3 gĕ̤ng:3 days,1 lā̤-buái:1 week,2 lā̤-buái:2 weeks,1 nguŏk-nĭk:1 month,3 nguŏk-nĭk:3 months,6 nguŏk-nĭk:6 months,1 nièng:1 year,īng-uōng:infinite',
'ipbotheroption' => 'gì-tă',
'ipbotherreason' => 'Gì-tă nguòng-ĭng:',
'blockipsuccesssub' => 'Hŭng-sō̤ sìng-gŭng',
'blockipsuccesstext' => '[[Special:Contributions/$1|$1]] ké̤ṳk hŭng-sō̤ lāu.
<br />Kó̤ [[Special:BlockList|IP hŭng-sō̤ dăng-dăng]] káng hŭng-sō̤ séng-sék.',
'ipb-edit-dropdown' => 'Siŭ-gāi hŭng-sō̤ nguòng-ĭng',
'ipb-unblock-addr' => 'Gāi-hŭng $1',
'ipb-unblock' => 'Gāi-hŭng siŏh ciáh ê̤ṳng-hô hĕ̤k IP dê-cī',
'ipb-blocklist' => 'Káng hŭng-sō̤ dăng-dăng',
'unblockip' => 'Gāi-hŭng ê̤ṳng-hô',
'ipusubmit' => 'Gāi-hŭng ciā dê-cī',
'unblocked' => '[[User:$1|$1]] ī-gĭng ké̤ṳk gāi-hŭng lāu',
'ipblocklist' => 'Ké̤ṳk hŭng-sō̤ gì IP dê-cī gâe̤ng ê̤ṳng-hô-miàng gì dăng-dăng',
'ipblocklist-legend' => 'Tō̤ siŏh ciáh ké̤ṳk hŭng-sō̤ gì ê̤ṳng-hô',
'ipblocklist-submit' => 'Sìng-tō̤',
'infiniteblock' => 'īng-uōng',
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
'watchlistall2' => 'sū-iū',
'namespacesall' => 'sū-iū',
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
'confirm_purge_button' => 'Hō̤',

# Multipage image navigation
'imgmultipageprev' => '← sèng 1 hiĕk',
'imgmultipagenext' => 'â 1 hiĕk →',
'imgmultigo' => 'Kó̤!',

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
