<?php
/**
  * @addtogroup Language
  */

$fallback = 'zh-cn';

$namespaceNames = array(
	NS_MEDIA            => "媒體",
	NS_SPECIAL          => "特殊",
	NS_MAIN             => "",
	NS_TALK             => "討論",
	NS_USER             => "用戶",
	NS_USER_TALK        => "用戶討論",
	# NS_PROJECT set by $wgMetaNamespace
	NS_PROJECT_TALK     => '$1討論',
	NS_IMAGE            => "圖像",
	NS_IMAGE_TALK       => "圖像討論",
	NS_MEDIAWIKI        => "媒體維基",
	NS_MEDIAWIKI_TALK   => "媒體維基討論",
	NS_TEMPLATE         => "樣板",
	NS_TEMPLATE_TALK    => "樣板討論",
	NS_HELP             => "幫助",
	NS_HELP_TALK        => "幫助討論",
	NS_CATEGORY         => "分類",
	NS_CATEGORY_TALK    => "分類討論"
);

$namespaceAliases = array(
	"對話" => NS_TALK,
	"用戶對話" => NS_USER_TALK,
	"維基百科對話" => NS_PROJECT_TALK,
	"圖像對話" => NS_IMAGE_TALK,
);

$quickbarSettings = array(
        "無", /* "None" */
	"左側固定", /* "Fixed left" */
	"右側固定", /* "Fixed right" */
	"左側漂移" /* "Floating left" */
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

/* User toggles */
	"tog-underline" => "下劃鏈結", /* "Underline links", */
	"tog-highlightbroken" => "毀壞的鏈結格式<a href=\"\" class=\"new\">像這樣</a> (或者像這個<a href=\"\" class=\"internal\">?</a>)", /* "Format broken links <a href=\"\" class=\"new\">like this</a> (alternative: like this<a href=\"\" class=\"internal\">?</a>).", */
	"tog-justify"	=> "段落對齊", /* "Justify paragraphs", */
	"tog-hideminor" => "最近更改中隱藏細微修改", /* "Hide minor edits in recent changes", */
	"tog-usenewrc" => "最近更改增強（只適用部分流覽器）", /* "Enhanced recent changes (not for all browsers)", */
	"tog-numberheadings" => "標題自動編號",
	"tog-showtoolbar" => "顯示編輯工具欄",/* "Auto-number headings", */
   "tog-editondblclick" => "雙擊頁面編輯(JavaScript)",
	"tog-editsection"=>"允許通過點擊[編輯]鏈結編輯段落",
 	"tog-editsectiononrightclick"=>"允許右擊標題編輯段落(JavaScript)",
 	"tog-showtoc"=>"顯示目錄<br />(針對一頁超過3個標題的文章)",
	"tog-rememberpassword" => "下次登陸記住密碼",/* "Remember password across sessions", */
	"tog-editwidth" => "編輯欄位寬度",/* "Edit box has full width", */
	"tog-editondblclick" => "雙擊編輯頁面（Javascript）",/* "Edit pages on double click (JavaScript)", */
	"tog-watchdefault" => "監視新的以及更改過的文章",/* "Watch new and modified articles", */
	"tog-minordefault" => "細微編輯為默認設置",/* "Mark all edits minor by default", */
	"tog-previewontop" => "在編輯框上方顯示預覽", /* "Show preview before edit box and not after it" */



# Bits of text used by many pages:
#
"categories" => "頁面分類",
"pagecategories" => "頁面分類",
"category_header" => "類別”$1“中的條目",
"subcategories" => "子分類",
"mainpage"		=> "首頁",
"about"			=> "關於",
"aboutpage"		=> "{{ns:project}}:關於",
"help"			=> "幫助",
"helppage"		=> "{{ns:project}}:幫助",
"bugreports"	=> "錯誤報告",
"bugreportspage" => "{{ns:project}}:錯誤報告",
"faq"			=> "常見問題解答",
"faqpage"		=> "{{ns:project}}:常見問題解答",
"edithelp"		=> "編輯幫助",
"edithelppage"	=> "{{ns:project}}:如何編輯頁面",
"cancel"		=> "取消",
"qbfind"		=> "尋找",
"qbbrowse"		=> "瀏覽",
"qbedit"		=> "編輯",
"qbpageoptions" => "頁面選項",
"qbpageinfo"	=> "頁面信息",
"qbmyoptions"	=> "我的選項",
"mypage"		=> "我的頁面",
"mytalk"		=> "我的對話頁",
"currentevents" => "新聞動態",
"errorpagetitle" => "錯誤",
"returnto"		=> "返回到$1.",
"whatlinkshere"	=> "鏈入頁面",
"help"			=> "幫助",
"search"		=> "搜索",
"searchbutton"	=> "搜索",
"go"		=> "進入",
'searcharticle'		=> "進入",
"history"		=> "較早版本",
"printableversion" => "可列印版",
"editthispage"	=> "編輯本頁",
"deletethispage" => "刪除本頁",
"protectthispage" => "保護本頁",
"unprotectthispage" => "解除保護",
"newpage" => "新頁面",
"talkpage"		=> "討論本頁",
	 "postcomment"   => "發表評論",
"articlepage"	=> "查看文章",
"userpage" => "查看用戶頁",
"projectpage" => "查看元維基頁",
"imagepage" => 	"查看圖像頁面",
"viewtalkpage" => "查看討論",
"otherlanguages" => "其它語言",
"redirectedfrom" => "(重定向自$1)",
"lastmodifiedat"	=> "最後更改$2, $1.",
"viewcount"		=> "本頁面已經被瀏覽$1次。",
"protectedpage" => "被保護頁",
"nbytes"		=> "$1字節",
"ok"			=> "好",
"retrievedfrom" => "取自\"$1\"",
"newmessageslink" => "新信息",
"editsection"=>"編輯",
"editold"=>"編輯",
"toc" => "目錄",
"showtoc" => "顯示",
 	 "hidetoc" => "隱藏",

# weekdays, month names
'sunday'    => "星期日",
'monday'    => "星期一",
'tuesday'   => "星期二",
'wednesday' => "星期三",
'thursday'  => "星期四",
'friday'    => "星期五",
'saturday'  => "星期六",

'january'   => "一月",
'february'  => "二月",
'march'     => "三月",
'april'     => "四月",
'may_long'  => "五月",
'june'      => "六月",
'july'      => "七月",
'august'    => "八月",
'september' => "九月",
'october'   => "十月",
'november'  => "十一月",
'december'  => "十二月",

'jan'       => "一月",
'feb'       => "二月",
'mar'       => "三月",
'apr'       => "四月",
'may'       => "五月",
'jun'       => "六月",
'jul'       => "七月",
'aug'       => "八月",
'sep'       => "九月",
'oct'       => "十月",
'nov'       => "十一月",
'dec'       => "十二月",

# Main script and global functions
#
"nosuchaction"	=> "沒有這個命令",
"nosuchactiontext" => "URL請求的命令無法被{{SITENAME}}軟件識別。",
"nosuchspecialpage" => "沒有這個特殊頁。",

"nospecialpagetext" => "您請求的頁面無法被{{SITENAME}}軟件識別。",

# General errors
#
"error"			=> "錯誤",
"databaseerror" => "數據庫錯誤",
"dberrortext"	=> "數據庫指令語法錯誤。
這可能是由於非法搜索指令所引起的(見$5),
也可能是由於軟件自身的錯誤所引起。
最後一次數據庫指令是：
<blockquote><tt>$1</tt></blockquote>
來自於函數 \"<tt>$2</tt>\"。
MySQL返回錯誤 \"<tt>$3: $4</tt>\"。",
"noconnect"		=> "無法在$1上連接數據庫",
"nodb"			=> "無法選擇數據庫 $1",
"readonly"		=> "數據庫禁止訪問",
"enterlockreason" => "請輸入禁止訪問原因, 包括估計重新開放的時間",
"readonlytext"	=> "{{SITENAME}}數據庫目前禁止輸入新內容及更改，
這很可能是由於數據庫正在維修，之後即可恢復。
管理員有如下解釋:
<p>$1</p>",
"missingarticle" => "數據庫找不到文字\"$1\"。

<p>通常這是由於修訂歷史頁上過時的鏈接到已經被刪除的頁面所導致的。</p>

<p>如果情況不是這樣，您可能找到了軟件內的一個臭蟲。
請記錄下URL地址，並向管理員報告。</p>",
"internalerror" => "內部錯誤",
"filecopyerror" => "無法複製文件\"$1\"到\"$2\"。",
"filerenameerror" => "無法重命名文件\"$1\"到\"$2\"。",
"filedeleteerror" => "無法刪除文件\"$1\"。",
"filenotfound"	=> "找不到文件\"$1\"。",
"unexpected"	=> "不正常值：\"$1\"=\"$2\"。",
"formerror"		=> "錯誤：無法提交表單",
"badarticleerror" => "無法在本頁上進行此項操作。",
"cannotdelete"	=> "無法刪除選定的頁面或圖像（它可能已經被其他人刪除了）。",
"badtitle"		=> "錯誤的標題",
"badtitletext"	=> "所請求頁面的標題是無效的、不存在，跨語言或跨維基鏈接的標題錯誤。",
"perfdisabled" => "抱歉！由於此項操作有可能造成數據庫癱瘓，目前暫時無法使用。",
"perfdisabledsub" => "這裏是自$1的複製版本：",

# 登錄與登出
#
"logouttitle"	=> "用戶退出",
"logouttext"	=> "您現在已經退出。
您可以繼續以匿名方式使用{{SITENAME}}，或再次以相同或不同用戶身份登錄。",

"welcomecreation" => "<h2>歡迎，$1!</h2><p>您的帳號已經建立，不要忘記設置{{SITENAME}}個人參數。</p>",

"loginpagetitle" => "用戶登錄",
"yourname"		=> "您的用戶名",
"yourpassword"	=> "您的密碼",
"yourpasswordagain" => "再次輸入密碼",
"remembermypassword" => "下次登錄記住密碼。",
"loginproblem"	=> "<b>登錄有問題。</b><br />再試一次！",
"alreadyloggedin" => "<strong>用戶$1，您已經登錄了!</strong><br />",

"login"			=> "登錄",
"userlogin"		=> "用戶登錄",
"logout"		=> "退出",
"userlogout"	=> "用戶退出",
"createaccount"	=> "創建新帳號",
 "createaccountmail"     => "通過eMail",
"badretype"		=> "你所輸入的密碼並不相同。",
"userexists"	=> "您所輸入的用戶名已有人使用。請另選一個。",
"youremail"		=> "您的電子郵件*",
"yournick"		=> "綽號（簽名時用）",
"loginerror"	=> "登錄錯誤",
"noname"		=> "你沒有輸入一個有效的用戶名。",
"loginsuccesstitle" => "登錄成功",
"loginsuccess"	=> "你現在以 \"$1\"的身份登錄{{SITENAME}}。",
"nosuchuser"	=> "找不到用戶 \"$1\"。
檢查您的拼寫，或者用下面的表格建立一個新帳號。",
"wrongpassword"	=> "您輸入的密碼錯誤，請再試一次。",
"mailmypassword" => "將新密碼寄給我",
"passwordremindertitle" => "{{SITENAME}}密碼提醒",
"passwordremindertext" => "有人（可能是您，來自網址$1)要求我們將新的{{SITENAME}}登錄密碼寄給你。
用戶 \"$2\" 的密碼現在是 \"$3\"。
請立即登錄並更改密碼。",
"noemail"		=> "用戶\"$1\"沒有登記電子郵件地址。",
"passwordsent"	=> "用戶\"$1\"的新密碼已經寄往所登記的電子郵件地址。
請在收到後再登錄。",

# 編輯
#
"summary"		=> "簡述",
"subject"               => "主題",
"minoredit"		=> "這是一個小修改",
"watchthis"		=> "監視本頁",
"savearticle"	=> "保存本頁",
"preview"		=> "預覽",
"showpreview"	=> "顯示預覽",
"blockedtitle"	=> "用戶被封",
"blockedtext"	=> "您的用戶名或網址已被$1封。
理由是：<br />'''$2'''<p>您可以與$1向其他任何[[{{ns:project}}:管理員|管理員]]詢問。</p>",
 "whitelistedittitle" => "登錄後才可編輯",
 	 "whitelistedittext" => "您必須先[[特殊:登錄]]才可編輯頁面。",
 	 "whitelistreadtitle" => "登錄後才可閱讀",
 	 "whitelistreadtext" => "您必須先[[特殊:登錄]]才可閱讀頁面。",
 	 "whitelistacctitle" => "您被禁止建立帳號",
 	 "whitelistacctext" => "在本維基中建立帳號您必須先[[特殊:登錄]]並擁有相關權限。",
 	 "accmailtitle" => "密碼寄出",
 	 "accmailtext" => "'$1'的密碼已經寄到$2。",
"newarticle"	=> "（新）",
"newarticletext" =>
"您從一個鏈接進入了一個並不存在的頁面。
要創建該頁面，請在下面的編輯框中輸入內容（詳情參見[[{{ns:project}}:幫助|幫助頁面]]）。
如果您不小心來到本頁面，直接點擊您瀏覽器中的“返回”按鈕。",

"anontalkpagetext" => "---- ''這是一個還未建立帳號的匿名用戶的對話頁。我們因此只能用[[網址]]來與他／她聯絡。該網址可能由幾名用戶共享。如果您是一名匿名用戶並認為本頁上的評語與您無關，請[[特殊:登錄|創建新帳號或登錄]]以避免在未來於其他匿名用戶混淆。''",
"noarticletext" => "（本頁目前沒有內容）",
"updated"		=> "（已更新）",
"note"			=> "<strong>注意：</strong>",
"previewnote"	=> "請記住這只是預覽，內容還未保存！",
"previewconflict" => "這個預覽顯示了上面文字編輯區中的內容。它將在你選擇保存後出現。",
"editing"		=> "正在編輯$1",
'editinguser'		=> "正在編輯$1",
"editingsection"	=> "正在編輯$1 (段落)",
"editingcomment"	=> "正在編輯$1 (評論)",
"editconflict"	=> "編輯衝突：$1",
"explainconflict" => "有人在你開始編輯後更改了頁面。
上面的文字框內顯示的是目前本頁的內容。
你所做的修改顯示在下面的文字框中。
你應當將你所做的修改加入現有的內容中。
<b>只有</b>在上面文字框中的內容會在你點擊\"保存頁面\"後被保存。<br />",
"yourtext"		=> "您的文字",
"storedversion" => "已保存版本",
"editingold"	=> "<strong>警告：你正在編輯的是本頁的舊版本。
如果你保存它的話，在本版本之後的任何修改都會丟失。</strong>",
"yourdiff"		=> "差別",
/*"copyrightwarning" => "請注意對W{{SITENAME}}的任何貢獻都將被認為是在GNU自由文檔協議證書下發佈。
(細節請見$1).
如果您不希望您的文字被任意修改和再散佈，請不要提交。<br />
您同時也向我們保證你所提交的內容是你自己所作，或得自一個不受版權保護或相似自由的來源。
<strong>不要在未獲授權的情況下發表！</strong>",*/

"longpagewarning" => "<strong>警告：本頁長度達$1千位；一些瀏覽器將無法編輯長過三十二千位的文章。請考慮將本文切割成幾個小段落。</strong>",

"readonlywarning" => "<strong>警告：數據庫被鎖以進行維護，所以您目前將無法保存您的修改。您或許希望先將本斷文字複製並保存到文本文件，然後等一會兒再修改。</strong>",
"protectedpagewarning" => "<strong>警告：本頁已經被保護，只有擁有管理員權限的用戶才可修改。請確認您遵守
[[Project:Protected_page_guidelines|保護頁面守則]].</strong>",

# History pages
#
"revhistory"	=> "修訂歷史",
"nohistory"		=> "沒有本頁的修訂記錄。",
"revnotfound"	=> "沒有找到修訂記錄",
"revnotfoundtext" => "您請求的更早版本的修訂記錄沒有找到。
請檢查您請求本頁面用的URL是否正確。",
"loadhist"		=> "載入頁面修訂歷史",
"currentrev"	=> "當前修訂版本",
"revisionasof"	=> "$1的修訂版本",
"cur"			=> "當前",
"next"			=> "後繼",
"last"			=> "先前",
"orig"			=> "初始",
"histlegend"	=> "說明：(當前)指與當前修訂版本比較；(先前)指與前一個修訂版本比較，小 指細微修改。",

# Diffs
#
"difference"	=> "（修訂版本間差異）",
"loadingrev"	=> "載入修訂版本比較",
"lineno"		=> "第$1行：",
"editcurrent"	=> "編輯本頁的當前修訂版本",

# Search results
#
"searchresults" => "搜索結果",
"searchresulttext" => "有關搜索{{SITENAME}}的更多詳情,參見$1。",
"searchsubtitle"	=> "查詢\"[[:$1]]\"",
"searchsubtitleinvalid"	=> "查詢\"$1\"",
"badquery"		=> "搜索查詢不正確",
"badquerytext"	=> "我們無法處理您的查詢。
這可能是由於您試圖搜索一個短於3個字母的外文單詞，
或者您錯誤地輸入了搜索項，例如\"汽車和和火車\"。
請再嘗試一個新的搜索項。",
"matchtotals"	=> "搜索項\"$1\"與$2條文章的題目相符，和$3條文章相符。",

"noexactmatch" => "沒有文章與搜索項完全匹配，請嘗試完整文字搜索。",
"titlematches"	=> "文章題目相符",
"notitlematches" => "沒有找到匹配文章題目",
"textmatches"	=> "文章內容相符",
"notextmatches"	=> "沒有文章內容匹配",

"prevn"			=> "先前$1",
"nextn"			=> "之後$1",
"viewprevnext"	=> "查看 ($1) ($2) ($3).",
"showingresults" => "下面顯示<b>$1</b>條結果，從第<b>$2</b>條開始",
"nonefound"		=> "<strong>注意：</strong>失敗的搜索往往是由於試圖搜索諸如“的”或“和”之類的常見字所引起。",
"powersearch" => "搜索",
"powersearchtext" => "
搜索名字空間：<br />$1<br />$2列出重定向頁面；搜索$3 $9",

"searchdisabled" => "<p>{{SITENAME}}內部搜索功能由於高峰時段服務器超載而停止使用。
您可以暫時通過
<a href=\"http://google.com.tw/\">google</a>搜索{{SITENAME}}。
謝謝您的耐心。</p>",

# Preferences page
#
"preferences"	=> "參數設置",
"prefsnologin" => "還未登錄",
"prefsnologintext"	=> "您必須先[[Special:Userlogin|登錄]]才能設置個人參數。",
"prefsreset"	=> "參數重新設置。",
"qbsettings"	=> "快速導航條設置",
"changepassword" => "更改密碼",
"skin"			=> "皮膚",
"math"			=> "數學顯示",
"math_failure"		=> "無法解析",
"math_unknown_error"	=> "未知錯誤",
"math_unknown_function"	=> "未知函數",
"math_lexing_error"	=> "句法錯誤",
"math_syntax_error"	=> "語法錯誤",
"saveprefs"		=> "保存參數設置",
"resetprefs"	=> "重設參數",
"oldpassword"	=> "舊密碼",
"newpassword"	=> "新密碼",
"retypenew"		=> "重複新密碼",
"textboxsize"	=> "文字框尺寸",
"rows"			=> "行",
"columns"		=> "列",
"searchresultshead" => "搜索結果設定",
"resultsperpage" => "每頁顯示鏈接數",
"contextlines"	=> "每鏈接行數",
"contextchars"	=> "每行字數",
"stubthreshold" => "短條目顯示基本限制",
"recentchangescount" => "最近更改頁行數",
"savedprefs"	=> "您的個人參數設置已經保存。",
"timezonetext"	=> "輸入當地時間與服務器時間的時差。",
"localtime"	=> "當地時間",
"timezoneoffset" => "時差",

# Recent changes
#
"changes" => "更改",
"recentchanges" => "最近更改",
"recentchangestext" => "本頁跟蹤{{SITENAME}}內最新的更改。
[[{{ns:project}}:歡迎，新來者|歡迎，新來者]]！
請參見這些頁面：[[{{ns:project}}:常見問題解答|{{SITENAME}}常見問題解答]]、
[[{{ns:project}}:守則與指導|{{SITENAME}}守則]]
（特別是[[{{ns:project}}:命名常規|命名常規]]、
[[{{ns:project}}:中性的觀點|中立觀點]]）
和[[{{ns:project}}:最常見失禮行為|最常見失禮行為]]。

如果您希望{{SITENAME}}成功，那麼請您不要增加受其它[[{{ns:project}}:版權信息|版權]]
限制的材料，這一點將非常重要。相關的法律責任會傷害本項工程，所以請不要這樣做。
此外請參見",

"rcnote"		=> "下面是最近<strong>$2</strong>天內最新的<strong>$1</strong>次改動。",
"rcnotefrom"	=> "下面是自<b>$2</b>（最多顯示<b>$1</b>）。",
"rclistfrom"	=> "顯示自$1以來的新更改",
"rclinks"		=> "顯示最近 $2 天內最新的 $1 次改動。<br />$3",
"diff"			=> "差異",
"hist"			=> "歷史",
"hide"			=> "隱藏",
"show"			=> "顯示",
"minoreditletter" => "小",
"newpageletter" => "新",

# Upload
#
"upload"		=> "上載文件",
"uploadbtn"		=> "上載文件",
"reupload"		=> "重新上載",
"reuploaddesc"	=> "返回上載表單。",
"uploadnologin" => "未登錄",
"uploadnologintext"	=> "您必須先[[Special:Userlogin|登錄]]
才能上載文件。",
"uploaderror"	=> "上載錯誤",
"uploadtext"	=> "<strong>停止！</strong>在您上載之前，請先閱讀並遵守{{SITENAME}}<a href=\"" .
"{{localurle:Project:Image use policy}}\">圖像使用守則</a>。
<p>如果您要查看或搜索之前上載的圖像，
請到<a href=\"" . "{{localurle:Special:Imagelist}}" .
"\">已上載圖像列表</a>.
所有上載與刪除行為都被記錄在<a href=\"" .
"{{localurle:Project:Upload image}}\">上載紀錄</a>內。</p>
<p>使用下面的表單來上載用在條目內新的圖像文件。
在絕大多數瀏覽器內，你會看到一個\"瀏覽...\"按鈕，點擊它後就會跳出一個打開文件對話框。
選擇一個文件後文件名將出現在按鈕旁邊的文字框中。
您也必須點擊旁邊的複選框確認您所上載的文件並沒有違反相關版權法律。
點擊\"上載\" 按鈕完成上載程序。
如果您使用的是較慢的網絡連接的話那麼這個上載過程會需要一些時間。</p>
<p>我們建議照相圖片使用JPEG格式，繪圖及其他圖標圖像使用PNG格式，音像則使用OGG格式。
請使用具有描述性的語言來命名您的文件以避免混亂。
要在文章中加入圖像，使用以下形式的連接：
<b>[[圖像:檔案.jpg]]</b>或者<b>[[圖像:檔案.png|解釋文字]]</b>
或<b>[[媒體:檔案.ogg]]</b>來連接音像文件。</p>
<p>請注意在{{SITENAME}}頁面中，其他人可能會為了百科全書的利益而編輯或刪除您的上載文件，
而如果您濫用上載系統，您則有可能被禁止使用上載功能。</p>",
"uploadlog"		=> "上載紀錄",
"uploadlogpage" => "上載紀錄",
"uploadlogpagetext" => "以下是最近上載的文件的一覽表。
所有顯示的時間都是服務器時間。
<ul>
</ul>",
"filename"		=> "文件名",
"filedesc"		=> "簡述",
"copyrightpage" => "{{ns:project}}:版權信息",
"copyrightpagename" => "{{SITENAME}}版權",
"uploadedfiles"	=> "已上載文件",
"minlength"		=> "圖像名字必須至少有三個字母。",
"badfilename"	=> "圖像名已被改為\"$1\"。",
"badfiletype"	=> "\".$1\"不是所推薦的圖像文件格式。",
"largefile"		=> "我們建議圖像大小不超過百千位。",
"successfulupload" => "上載成功",
"fileuploaded"	=> "文件\"$1\"上載成功。
請根據連接($2)到圖像描述頁添加有關文件信息，例如它的來源，在何時由誰創造，
以及其他任何您知道的關於改圖像的信息。",
"uploadwarning" => "上載警告",
"savefile"		=> "保存文件",
"uploadedimage" => "已上載\"[[$1]]\"",

# Image list
#
"imagelist"		=> "圖像列表",
"imagelisttext"	=> "以下是按$2排列的$1幅圖像列表。",
"getimagelist"	=> "正在獲取圖像列表",
"ilsubmit"		=> "搜索",
"showlast"		=> "顯示按$2排列的最後$1幅圖像。",
"byname"		=> "名字",
"bydate"		=> "日期",
"bysize"		=> "大小",
"imgdelete"		=> "刪",
"imgdesc"		=> "述",
"imglegend"		=> "說明：(述) = 顯示/編輯圖像描述頁。",
"imghistory"	=> "圖像歷史",
"revertimg"		=> "回",
"deleteimg"		=> "刪",
"imghistlegend" => "題跋: (現) = 目前的圖像，(刪) = 刪除舊版本，
(複) = 恢復到舊版本。
<br /><i>點擊日期查看當天上載的圖像</i>.",
"imagelinks"	=> "圖像鏈接",

"linkstoimage"	=> "以下頁面連接到本圖像：",
"nolinkstoimage" => "沒有頁面連接到本圖像。",

# Statistics
#
"statistics"	=> "統計",
"sitestats"		=> "站點統計",
"userstats"		=> "用戶統計",
"sitestatstext" => "數據庫中共有 <b>$1</b> 頁頁面；
其中包括對話頁、關於{{SITENAME}}的頁面、最少量的\"stub\"頁、重定向的頁面，
以及未達到條目質量的頁面；除此之外還有 <b>$2</b> 頁可能是合乎標準的條目。
<p>從系統軟件升級以來，全站點共有頁面瀏覽 <b>$3</b> 次，
頁面編輯 <b>$4</b> 次，每頁平均編輯 <b>$5</b> 次，
各次編輯後頁面的每個版本平均瀏覽 <b>$6</b> 次。</p>",

# Maintenance Page
#
"disambiguations"	=> "消含糊頁",
"disambiguationspage"	=> "{{ns:project}}:鏈接到消歧義的頁面",
"disambiguationstext"	=> "以下的條目都有到消含糊頁的鏈接，但它們應該是鏈到適當的題目。<br />一個頁面會被視為消含糊頁如果它是鏈自$1.<br />由其它他名字空間來的鏈接<i>不會</i>在這兒被列出來。",
"doubleredirects"	=> "雙重重定向",
"doubleredirectstext"	=> "<b>請注意：</b> 這列表可能包括不正確的反應。
這通常表示在那頁面第一個#REDIRECT之下還有文字。<br />\n
每一行都包含到第一跟第二個重定向頁的鏈接，以及第二個重定向頁的第一行文字，
通常顯示的都會是\“真正\” 的目標頁面，也就是第一個重定向頁應該指向的條目。",
"brokenredirects"	=> "損壞的重定向頁",
"brokenredirectstext"	=> "以下的重定向頁指向的是不存在的條目。",

# Miscellaneous special pages
#
"lonelypages"	=> "孤立頁面",
"unusedimages"	=> "未使用圖像",
"popularpages"	=> "熱點條目",
"nviews"		=> "$1次瀏覽",
"wantedpages"	=> "待撰頁面",
"nlinks"		=> "$1個鏈接",
"allpages"		=> "所有頁面",
"randompage"	=> "隨機頁面",
"shortpages"	=> "短條目",
"longpages"		=> "長條目",
"listusers"		=> "用戶列表",
"specialpages"	=> "特殊頁面",
"spheading"		=> "特殊頁面",
"recentchangeslinked" => "鏈出更改",
"rclsub"		=> "（從 \"$1\"鏈出的頁面）",
"newpages"		=> "新頁面",
"intl"		=> "跨語言鏈接",
"movethispage"	=> "移動本頁",
"unusedimagestext" => "<p>請注意其他網站（例如其他語言版本的{{SITENAME}}）
有可能直接鏈接本圖像，所以這裏列出的圖像有可能依然被使用。</p>",
"booksources"	=> "戰外書源",
"booksourcetext" => "以下是鏈接到銷售書籍的網站列表，
因此有可能擁有您所尋找的圖書的進一步資料。
{{SITENAME}}與這些公司並沒有任何商業關係，因此本表不應該
被看作是一種背書。",
"alphaindexline" => "$1 到 $2",

# Email this user
#
"mailnologin"	=> "無電郵地址",
"mailnologintext" => "您必須先[[Special:Userlogin|登錄]]
並在[[Special:Preferences|參數設置]]
中有一個有效的電子郵件地址才可以電郵其他用戶。",
"emailuser"		=> "電子郵件該用戶",
"emailpage"		=> "電子郵件用戶",
"emailpagetext"	=> "如果該用戶已經在他或她的參數設置頁中輸入了有效的電子郵件地址，以下的表格將寄一個信息給該用戶。您在您參數設置中所輸入的電子郵件地址將出現在郵件“發件人”一欄中，這樣該用戶就可以回復您。",
"noemailtitle"	=> "無電子郵件地址",
"noemailtext"	=> "該用戶還沒有指定一個有效的電子郵件地址，
或者選擇不接受來自其他用戶的電子郵件。",

"emailfrom"		=> "發件人",
"emailto"		=> "收件人",
"emailsubject"	=> "主題",
"emailmessage"	=> "信息",
"emailsend"		=> "發送",
"emailsent"		=> "電子郵件已發送",
"emailsenttext" => "您的電子郵件已經發出。",

# Watchlist
#
"watchlist"		=> "監視列表",
"nowatchlist"	=> "您的監視列表為空。",
"watchnologin"	=> "未登錄",
"watchnologintext"	=> "您必須先[[Special:Userlogin|登錄]]
才能更改您的監視列表",
"addedwatch"	=> "加入到監視列表",
"addedwatchtext" => "<p>本頁（“$1”）已經被加入到您的<a href=\"" .
  "{{localurle:Special:Watchlist}}\">監視列表</a>中。
未來有關它或它的對話頁的任何修改將會在本頁中列出，
而且還會在<a href=\"" .
  "{{localurle:Special:Recentchanges}}\">最近更改列表</a>中
以<b>粗體</b>形式列出。</p>

<p>如果您之後想將該頁面從監視列表中刪除，點擊導航條中的“停止監視”鏈接。</p>",
"removedwatch"	=> "停止監視",
"removedwatchtext" => "頁面“$1”已經從您的監視頁面中移除。",
"watchthispage"	=> "監視本頁",
"unwatchthispage" => "停止監視",
"notanarticle"	=> "不是條目",
	 "watchnochange" => "在顯示的時間段內您所監視的頁面沒有更改。",
 	 "watchdetails" => "($1個頁面（不含對話頁）被監視；
 	 總共$2個頁面被編輯；
 	 $3...
 	 [$4 顯示並編輯完整列表].)",
 	 "watchmethod-recent" => "檢查被監視頁面的最近編輯",
 	 "watchmethod-list" => "checking watched pages for recent edits",
 	 "removechecked" => "將被選頁面從監視列表中移除",
 	 "watchlistcontains" => "您的監視列表包含$1個頁面。",
 	 "watcheditlist" => "這裏是您所監視的頁面的列表。要移除某一頁面，只要選擇該頁面然後點擊”移除頁面“按鈕。",
 	 "removingchecked" => "移除頁面...",
 	 "couldntremove" => "無法移除'$1'...",
 	 "iteminvalidname" => "頁面'$1'錯誤，無效命名...",
 	 "wlnote" => "以下是最近<b>$2</b>小時內的最後$1次修改。",

# Delete/protect/revert
#
"deletepage"	=> "刪除頁面",
"confirm"		=> "確認",
"confirmdelete" => "確認刪除",
"deletesub"		=> "（正在刪除“$1”）",
"confirmdeletetext" => "您即將從數據庫中永遠刪除一個頁面或圖像以及其歷史。
請確定您要進行此項操作，並且瞭解其後果，同時您的行為符合[[{{ns:project}}:守則與指導]]。",
"actioncomplete" => "操作完成",
"deletedtext"	=> "“$1”已經被刪除。
最近刪除的紀錄請參見$2。",
"deletedarticle" => "已刪除“$1”",

"dellogpage"	=> "刪除紀錄",
"dellogpagetext" => "以下是最近刪除的紀錄列表。
所有的時間都是使用服務器時間。
<ul>
</ul>",
"deletionlog"	=> "刪除紀錄",
"reverted"		=> "回降到早期版本",
"deletecomment"	=> "刪除理由",
"imagereverted" => "回降到早期版本操作完成。",
"rollback"		=> "恢復",
"rollbacklink"	=> "恢復",
"cantrollback"	=> "無法恢復編輯；最後的鞏縣者是本文的唯一作者。",
"revertpage"	=> "回降到$1的最後一次編輯",

# Undelete
"undelete" => "恢復被刪頁面",
"undeletepage" => "瀏覽及恢復被刪頁面",
"undeletepagetext" => "以下頁面已經被刪除，但依然在檔案中並可以被恢復。
檔案庫可能被定時清理。",
"undeletearticle" => "恢復被刪文章",
"undeleterevisions" => "$1版本存檔",
"undeletehistory" => "如果您恢復了該頁面，所有版本都會被恢復到修訂歷史中。
如果本頁刪除後有一個同名的新頁面建立，
被恢復的版本將會稱為較新的歷史，而新頁面的當前版本將無法被自動復原。",
"undeleterevision" => "刪除$1時的版本",
"undeletebtn" => "恢復！",
"undeletedarticle" => "已經恢復“$1”",

# Contributions
#
"contributions"	=> "用戶貢獻",
"mycontris" => "我的貢獻",
"contribsub"	=> "為$1",
"nocontribs"	=> "沒有找到符合特徵的更改。",
"ucnote"		=> "以下是該用戶最近<b><$2/b>天內的最後<b>$1</b>次修改。",
"uclinks"		=> "參看最後$1次修改；參看最後$2天。",
"uctop"		=> " (頂)" ,

# What links here
#
"whatlinkshere"	=> "鏈入頁面",
"notargettitle" => "無目標",
"notargettext"	=> "您還沒有指定一個目標頁面或用戶以進行此項操作。",
"linklistsub"	=> "(鏈接列表)",
"linkshere"		=> "以下頁面鏈接到這裏：",
"nolinkshere"	=> "沒有頁面鏈接到這裏。",
"isredirect"	=> "重定向頁",

# Block/unblock IP
#
"blockip"		=> "查封網址",
"blockiptext"	=> "用下面的表單來禁止來自某一特定網址的修改權限。
只有在為防止破壞，及符合[[{{ns:project}}:守則與指導]]的情況下才可採取此行動。
請在下面輸入一個具體的理由（例如引述一個被破壞的頁面）。",
"ipaddress"		=> "網址",
"ipbreason"		=> "原因",
"ipbsubmit"		=> "查封該地址",
"badipaddress"	=> "網址不正確。",
"blockipsuccesssub" => "查封成功",
"blockipsuccesstext" => "網址“$1”已經被查封。
<br />參看[[特殊:被封網址列表|被封網址列表]]以復審查封。",
"unblockip"		=> "解除禁封網址",
"unblockiptext"	=> "用下面的表單來恢復先前被禁封的網址的書寫權。",
"ipusubmit"		=> "解除禁封",
"ipblocklist"	=> "被封網址列表",
"blocklistline"	=> "$1，$2禁封$3 ($4)",
"blocklink"		=> "禁封",
"unblocklink"	=> "解除禁封",
"contribslink"	=> "貢獻",

# Developer tools
#
"lockdb"		=> "禁止更改數據庫",
"unlockdb"		=> "開放更改數據庫",
"lockdbtext"	=> "鎖住數據庫將禁止所有用戶進行編輯頁面、更改參數、編輯監視列表以及其他需要更改數據庫的操作。
請確認您的決定，並且保證您在維護工作結束後會重新開放數據庫。",
"unlockdbtext"	=> "開放數據庫將會恢復所有用戶進行編輯頁面、修改參數、編輯監視列表以及其他需要更改數據庫的操作。
請確認您的決定。",
"lockconfirm"	=> "是的，我確實想要封鎖數據庫。",
"unlockconfirm"	=> "是的，我確實想要開放數據庫。",
"lockbtn"		=> "數據庫上鎖",
"unlockbtn"		=> "開放數據庫",
"locknoconfirm" => "您並沒有勾選確認按鈕。",
"lockdbsuccesssub" => "數據庫成功上鎖",

"unlockdbsuccesssub" => "數據庫開放",
"lockdbsuccesstext" => "{{SITENAME}}數據庫已經上鎖。
<br />請記住在維護完成後重新開放數據庫。",
"unlockdbsuccesstext" => "{{SITENAME}}數據庫重新開放。",

# Move page
#
"movepage"		=> "移動頁面",
"movepagetext"	=> "用下面的表單來重命名一個頁面，並將其修訂歷史同時移動到新頁面。
老的頁面將成為新頁面的重定向頁。
鏈接到老頁面的鏈接並不會自動更改；
請[[特殊:檢查|檢查]]雙重或損壞重定向鏈接。
您應當負責確定所有鏈接依然會鏈到指定的頁面。

注意如果新頁面已經有內容的話，頁面將'''不會'''被移動，
除非新頁面無內容或是重定向頁，而且沒有修訂歷史。
這意味著您再必要時可以在移動到新頁面後再移回老的頁面，
同時您也無法覆蓋現有頁面。

<b>警告！</b>
對一個經常被訪問的頁面而言這可能是一個重大與唐突的更改；
請在行動前先了結其所可能帶來的後果。",
"movepagetalktext" => "有關的對話頁（如果有的話）將被自動與該頁面一起移動，'''除非'''：
*您將頁面移動到不同的名字空間；
*新頁面已經有一個包含內容的對話頁，或者
*您不勾選下麵的複選框。

在這些情況下，您在必要時必須手工移動或合併頁面。",
"movearticle"	=> "移動頁面",
"movenologin"	=> "未登錄",
"movenologintext" => "您必須是一名登記用戶並且[[Special:Userlogin|登錄]]
後才可移動一個頁面。",
"newtitle"		=> "新標題",
"movepagebtn"	=> "移動頁面",
"pagemovedsub"	=> "移動成功",
"pagemovedtext" => "頁面“[[$1]]”已經移動到“[[$2]]”。",
"articleexists" => "該名字的頁面已經存在，或者您選擇的名字無效。請再選一個名字。",
"talkexists"	=> "頁面本身移動成功，
但是由於新標題下已經有對話頁存在，所以對話頁無法移動。請手工合併兩個頁面。",
"movedto"		=> "移動到",
"movetalk"		=> "如果可能的話，請同時移動對話頁。",
"talkpagemoved" => "相應的對話頁也已經移動。",
"talkpagenotmoved" => "相應的對話頁<strong>沒有</strong>被移動。",


# some untranslated messages as of 1.4 beta1
'1movedto2' => "$1移動到$2", //"$1 moved to $2",
'1movedto2_redir' => "$1重定向到$2", //"$1 moved to $2 over redirect",
'acct_creation_throttle_hit' => "對不起，您已經註冊了$1賬號。你不能再註冊了。", //"Sorry, you have already created $1 accounts. You can't make any more.",
'allarticles' => "所有條目", //"All articles",
'allmessages' => "系統界面", //"All system messages",
'allmessagesnotsupportedDB' => "系統界面功能處於關閉狀態 (wgUseDatabaseMessages)。", //"Special:AllMessages not supported because wgUseDatabaseMessages is off.",
'allmessagestext' => "這裡列出所有可定製的系統界面。", //"This is a list of all system messages available in the MediaWiki: namespace.",
'allinnamespace' => "所有 $1 名字空間的條目", //"All pages ($1 namespace)",
'allpagesnext' => "下一頁", //"Next",
'allpagesprev' => "上一頁", //"Previous",
'allpagessubmit' => "提交", //"Go",
'ancientpages' => "老條目", //"Oldest pages",
'and' => "和", //"and",
'anontalk' => "該IP的對話頁", //"Talk for this IP",
'anonymous' => "匿名用戶", //"Anonymous user(s) of 1.4",
'article' => "條目", //"Content page",
'autoblocker' => "你的IP和被封了的 \"$1\" 是一樣的。封鎖原因： \"$2\".",//"Autoblocked because you share an IP address with \"$1\". Reason \"$2\".",
'blocklogentry' => "封鎖 $1, $2",//"blocked \"$1\" with an expiry time of $2",
'blocklogpage' => "封鎖記錄", //"Block_log",
'categoriespagetext' => "以下列出所有的頁面分類。", //"The following categories exists in the wiki.",
'categoryarticlecount' => "該類頁面共有 $1 條目", //There are $1 articles in this category.",
'clearyourcache' => "'''注意：''' 保存設置後，要清掉瀏覽器的緩存才能生效：'''Mozilla / Firefox:''' ''Ctrl-Shift-R'', '''Internet Explorer:''' ''Ctrl-F5'', '''Safari:''' ''Cmd-Shift-R'', '''Konqueror:''' ''F5''。",
'edit' => "編輯", //"Edit",
'navigation' => "導航", //"Navigation",
'nstab-category' => "分類", //"Category",
'nstab-help' => "幫助",//"Help",
'nstab-image' => "圖像",//"Image",
'nstab-main' => "條目", //"Article",
'nstab-mediawiki' => "界面",//"Message",
'nstab-special' => "特殊",//"Special",
'nstab-template' => "模板", //"Template",
'nstab-user' => "用戶頁面", //"User page",
'nstab-project' => "關於", //"About",
'portal' => "社區",//"Community portal",
'prefs-help-realname' => "*<strong>真實姓名</strong>（可選）：用以對您的貢獻署名。<br />",
'prefs-help-email' => "*<strong>點子郵件</strong>（可選）：讓他人通過網站在不知道您的電子郵件地址的情況下通過電子郵件與您聯絡，以及通過電子郵件取得遺忘的密碼。", /*"* <strong>Real name</strong> (optional): if you choose to provide it this will be used for giving you attribution for your work.<br />
* <strong>Email</strong> (optional): Enables people to contact you through the website without you having to reveal your
email address to them, and it can be used to send you a new password if you forget it.", */
'prefs-misc' => "雜項", //"Misc settings",
'prefs-personal' => "用戶數據",//"User data",
'prefs-rc' => "最近更新", //"Recent changes and stub display",
'skin' => "面板", //"Skin",
'talk' => "討論",//"Discussion",
'timezonelegend' => "時區", //"Time zone",
'toolbox' => "工具",//"Toolbox",
'watch' => "監視",//"Watch",
'yourlanguage' => "界面語言", //"Your language"
'yourrealname' => "真實姓名*", //"Your real name"
'yourvariant' => "中文字體", //"language variant"

'personaltools' => "個人工具", //"personaltools""
'history_short' => "沿革",
"protect" => "封鎖",
"delete" => "刪除",
"move" => "移動",
"tog-nocache" => "停用頁面快取",
"specialpage" => "特殊頁面",
"defaultns" => "預設的搜尋範圍",
"default" => "預設",
"aboutsite" => "關於 {{SITENAME}}",
"disclaimers" => "免責聲明",
"tog-fancysig" => "使用原始簽名 (不產生自動連結)",
"tog-previewonfirst" => "第一次編輯時顯示原文內容的預覽",
"copyright" => "本站所有內容允許以下方式利用: $1",
"loginprompt" => "您必須允許瀏覽器紀錄Cookie才能成功登入 {{SITENAME}} 並順利進行操作",
);


?>
