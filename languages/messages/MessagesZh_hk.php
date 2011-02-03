<?php
/** Chinese (Hong Kong) (‪中文(香港)‬)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Horacewai2
 * @author Kayau
 * @author Mark85296341
 * @author PhiLiP
 * @author Waihorace
 * @author Wong128hk
 * @author Yukiseaside
 * @author Yuyu
 */

$fallback = 'zh-hant';

$fallback8bitEncoding = 'Big5-HKSCS';

$specialPageAliases = array(
	'Unblock'                   => array( '解除封禁' ),
	'RevisionMove'              => array( '移動版本' ),
	'ComparePages'              => array( '頁面比較' ),
);

$messages = array(
# User preference toggles
'tog-watchlisthidebots' => '監視列表中隱藏機械人的編輯',

# Dates
'january'   => '一月',
'february'  => '二月',
'march'     => '三月',
'april'     => '四月',
'may_long'  => '五月',
'june'      => '六月',
'july'      => '七月',
'august'    => '八月',
'september' => '九月',
'october'   => '十月',
'november'  => '十一月',
'december'  => '十二月',

'mainpagedocfooter' => '請參閱[http://meta.wikimedia.org/wiki/Help:Contents 用戶手冊]以獲得使用此 wiki 軟件的訊息！

== 入門 ==
* [http://www.mediawiki.org/wiki/Manual:Configuration_settings MediaWiki 配置設定清單]
* [http://www.mediawiki.org/wiki/Manual:FAQ MediaWiki 常見問題解答]
* [https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce MediaWiki 發佈郵件清單]',

'mytalk' => '我的討論頁',

'tagline'          => '從 {{SITENAME}}',
'search'           => '搜尋',
'printableversion' => '可打印版',
'permalink'        => '永久連接',
'print'            => '打印',
'specialpage'      => '特殊頁面',
'jumpto'           => '跳到：',
'jumptosearch'     => '搜尋',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutpage'   => 'Project:關於我們',
'privacy'     => '私隱政策',
'privacypage' => 'Project:私隱政策',

'red-link-title' => '$1 (頁面不存在)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-special' => '特殊頁面',

# Login and logout pages
'nav-login-createaccount' => '登入／創造帳戶',
'userlogin'               => '登入／創造帳戶',

# Revision deletion
'revdelete-suppress-text' => "壓制'''只'''應用於以下的情況:
* 不合適的個人資料
*: ''地址、電話號碼、身份證號碼等。''",

# Diffs
'editundo' => '撤銷',

# Search results
'search-mwsuggest-disabled' => '沒有意見',

# Preferences page
'prefs-help-gender' => '可選：用於軟件中的性別指定。此項資料將會被公開。',

# Groups
'group-bot' => '機械人',

'group-bot-member' => '機械人',

'grouppage-bot' => '{{ns:project}}:機械人',

# Recent changes
'recentchanges-label-bot' => '這次編輯是由機械人進行',
'rcshowhidebots'          => '$1機械人的編輯',

# Special:ActiveUsers
'activeusers-hidebots' => '隱藏機械人',

# Block/unblock
'contribslink' => '貢獻',

# Tooltip help for the actions
'tooltip-search'                 => '搜尋 {{SITENAME}}',
'tooltip-search-go'              => '若是真有其頁，則進入相同名字的頁面',
'tooltip-search-fulltext'        => '在此頁面內搜尋此文字',
'tooltip-n-mainpage'             => '回到首頁',
'tooltip-n-mainpage-description' => '回到首頁',
'tooltip-n-randompage'           => '跳到一個隨機抽取的頁面',
'tooltip-t-print'                => '這個頁面的可打印版本',

# Special:NewFiles
'showhidebots' => '($1機械人)',

# Special:SpecialPages
'specialpages' => '特殊頁面',

);
