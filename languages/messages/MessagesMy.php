<?php
/** Burmese (မြန်မာဘာသာ)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Hakka
 * @author Hanzaw
 * @author Hintha
 * @author Lagoonaing
 * @author Lionslayer
 * @author Minnyoonthit
 * @author Myanmars
 * @author Myolay
 * @author Parabaik
 * @author Thanlwin
 * @author Thitaung
 * @author Umherirrender
 * @author Zawthet
 * @author ကိုရာဝီ
 */

$digitTransformTable = array(
	'0' => '၀',
	'1' => '၁',
	'2' => '၂',
	'3' => '၃',
	'4' => '၄',
	'5' => '၅',
	'6' => '၆',
	'7' => '၇',
	'8' => '၈',
	'9' => '၉',
);

$datePreferences = array(
	'default',
	'my normal',
	'my long',
	'ISO 8601',
);

$defaultDateFormat = 'my normal';

$dateFormats = array(
	'my normal time' => 'H:i',
	'my normal date' => 'j F Y',
	'my normal both' => ' H:i"၊" j F Y',

	'my long time' => 'H:i',
	'my long date' => 'Y "ဇန်နဝါရီ" F"လ" j "ရက်"',
	'my long both' => 'H:i"၊" Y "ဇန်နဝါရီ" F"လ" j "ရက်"',
);

$messages = array(
# User preference toggles
'tog-watchcreations' => 'ကျွန်တော်ထွင်သည့်စာမျက်နှာများကို စောင့်​ကြည့်​စာ​ရင်း​ထဲ ပေါင်းထည့်ပါ',

'underline-always' => 'အမြဲ',
'underline-never'  => 'ဘယ်သောအခါမျှ',

# Dates
'sunday'        => 'တ​နင်္ဂ​နွေ​',
'monday'        => 'တ​နင်္လာ​',
'tuesday'       => 'အင်္ဂါ​',
'wednesday'     => 'ဗုဒ္ဒ​ဟူး​',
'thursday'      => 'ကြာ​သာ​ပ​တေး​',
'friday'        => 'သော​ကြာ​',
'saturday'      => 'စ​နေ​',
'sun'           => 'တနင်္ဂနွေ',
'mon'           => 'တနင်္ဂလာ',
'tue'           => 'အင်္ဂါ',
'wed'           => 'ဗုဒ္ဓဟူး',
'thu'           => 'ကြာသပတေး',
'fri'           => 'သောကြာ',
'sat'           => 'စနေ',
'january'       => 'ဇန်​န​ဝါ​ရီ​',
'february'      => 'ဖေ​ဖော်​ဝါ​ရီ​',
'march'         => 'မတ်​',
'april'         => 'ဧ​ပြီ​',
'may_long'      => 'မေ​',
'june'          => 'ဇွန်​',
'july'          => 'ဇူ​လိုင်​',
'august'        => 'ဩ​ဂုတ်​',
'september'     => 'စက်​တင်​ဘာ​',
'october'       => 'အောက်​တို​ဘာ​',
'november'      => 'နို​ဝင်​ဘာ​',
'december'      => 'ဒီ​ဇင်​ဘာ​',
'january-gen'   => 'ဇန်​န​ဝါ​ရီ​',
'february-gen'  => 'ဖေ​ဖော်​ဝါ​ရီ​',
'march-gen'     => 'မတ်​',
'april-gen'     => 'ဧ​ပြီ​',
'may-gen'       => 'မေ​',
'june-gen'      => 'ဇွန်​',
'july-gen'      => 'ဇူ​လိုင်​',
'august-gen'    => 'ဩ​ဂုတ်​',
'september-gen' => 'စက်​တင်​ဘာ​',
'october-gen'   => 'အောက်​တို​ဘာ​',
'november-gen'  => 'နို​ဝင်​ဘာ​',
'december-gen'  => 'ဒီ​ဇင်​ဘာ​',
'jan'           => 'ဇန်',
'feb'           => 'ဖေ',
'mar'           => 'မတ်',
'apr'           => 'ဧ',
'may'           => 'မေ​',
'jun'           => 'ဇွန်',
'jul'           => 'ဇူ',
'aug'           => 'ဩ',
'sep'           => 'စက်',
'oct'           => 'အောက်',
'nov'           => 'နို',
'dec'           => 'ဒီ',

# Categories related messages
'pagecategories' => '{{PLURAL:$1|အမျိုးအစား|အမျိုးအစား}}',
'subcategories'  => 'အုပ်စုခွဲ',
'category-empty' => 'ဗလာအုပ်စု',

'about'      => 'အကြောင်း',
'article'    => 'စာမျက်နှာ',
'newwindow'  => '(ဝင်းဒိုးအသစ်တခုကိုဖွင့်ပါ)',
'cancel'     => 'မ​လုပ်​တော့​ပါ​',
'mypage'     => 'ကျွန်ုပ် စာမျက်နှာ',
'mytalk'     => 'ကျွန်​တော့် ဆွေးနွေးချက်',
'navigation' => 'အ​ညွှန်း​',
'and'        => 'နဲ့',

# Cologne Blue skin
'qbfind'         => 'ရှာပါ',
'qbedit'         => 'ပြင်​ဆင်​ရန်​',
'qbspecialpages' => 'အ​ထူး​စာ​မျက်​နှာ​',

# Vector skin
'vector-action-move' => 'ရွှေ့ပါ',
'vector-view-edit'   => 'ပြင်ပါ',
'vector-view-view'   => 'ဖတ်ရန်',

'tagline'          => '{{SITENAME}} မှ',
'help'             => 'အ​ကူ​အ​ညီ​',
'search'           => 'ရှာ​ဖွေ​ရန်​',
'searchbutton'     => 'ရှာ​ဖွေ​ရန်​',
'go'               => 'သွား​ပါ​',
'searcharticle'    => 'သွား​ပါ​',
'history_short'    => 'မှတ်​တမ်း​',
'info_short'       => 'သတင်းအချက်အလက်',
'printableversion' => 'ပ​ရင်​တာ​ ထုတ်​ရန်​',
'permalink'        => 'ပုံ​သေ​လိပ်​စာ​',
'print'            => 'ပရင့်',
'edit'             => 'ပြင်​ဆင်​ရန်​',
'create'           => 'စတင်ရေးသားရန်',
'editthispage'     => 'ဤစာမျက်နှာကို ပြင်ရန်',
'delete'           => 'ဖျက်​ပါ​',
'protect'          => 'ထိမ်း​သိမ်း​ပါ​',
'protect_change'   => 'ပြောင်းလဲရန်',
'newpage'          => 'စာမျက်နှာအသစ်',
'talkpage'         => 'ပြောရေးဆိုရာ',
'talkpagelinktext' => 'ဆွေးနွေးရန်',
'personaltools'    => 'ကိုယ်ပိုင် ကိရိယာများ',
'talk'             => 'ဆွေးနွေးချက်များ',
'views'            => 'ပုံပန်းသွင်ြပင်',
'toolbox'          => 'တန်​ဆာ​ပ​လာ​',
'mediawikipage'    => 'မီဒီယာဝီကီစာမျက်နှာ',
'viewhelppage'     => 'ကူညီမယ့် စာမျက်နှာကိုကြည့်ရန်',
'otherlanguages'   => 'အခြား ဘာသာစကားများဖြင့်',
'lastmodifiedat'   => 'ဤစာမျက်နှာကို $1 ရက် $2 အချိန်တွင် နောက်ဆုံး ပြင်ဆင်ခဲ့သည်။',
'jumpto'           => 'ဤနေရာသို့သွားရန် -',
'jumptonavigation' => 'အ​ညွှန်း​',
'jumptosearch'     => 'ရှာ​ဖွေ​ရန်​',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => '{{SITENAME}}အကြောင်း',
'aboutpage'            => 'Project: အကြောင်းအရာ',
'copyright'            => '$1 အောက်တွင် ဤအကြောင်းအရာကို ရရှိနိုင်သည်။',
'copyrightpage'        => 'မူပိုင်ခွင့်',
'currentevents'        => 'လက်​ရှိ​လုပ်​ငန်း​များ​',
'currentevents-url'    => 'Project:လက်​ရှိ​လုပ်​ငန်း​များ​',
'disclaimers'          => 'သ​တိ​ပေး​ချက်​များ​',
'disclaimerpage'       => 'Project: အထွေထွေ သတိပေးချက်',
'edithelp'             => 'ပြင်​ဆင်​ခြင်း​အ​ကူ​အ​ညီ​',
'edithelppage'         => 'အကူအညီ:တည်းဖြတ်ခြင်း',
'mainpage'             => 'ဗ​ဟို​စာ​မျက်​နှာ​',
'mainpage-description' => 'ဗ​ဟို​စာ​မျက်​နှာ​',
'portal'               => 'ပြော​ရေး​ဆို​ရာ​',
'privacy'              => 'ကိုယ်ပိုင်ပေါ်လစီ',
'privacypage'          => 'Project: ကိုယ်ပိုင် ပေါ်လစီ',

'retrievedfrom'           => '"$1" မှ ရယူရန်',
'newmessageslink'         => 'သ​တင်း​အ​သစ်​',
'newmessagesdifflink'     => 'နောက်ဆုံးအပြောင်းအလဲ',
'youhavenewmessagesmulti' => 'You have new messages on $1',
'editsection'             => 'ပြင်​ဆင်​ရန်​',
'editold'                 => 'ပြင်​ဆင်​ရန်​',
'editlink'                => 'ပြင်​ဆင်​ရန်',
'editsectionhint'         => 'ဤအပိုင်းကို တည်းဖြတ်ရန် - $1',
'toc'                     => 'မာတိကာ',
'showtoc'                 => 'ပြ',
'hidetoc'                 => 'ဝှက်',
'site-rss-feed'           => 'RSS feed $1 ခု',
'site-atom-feed'          => 'Atom feed $1 ခု',
'red-link-title'          => '$1 (ဤစာမျက်နှာ မရှိပါ)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'     => 'စာ​မျက်​နှာ​',
'nstab-user'     => 'မှတ်​ပုံ​တင်​အ​သုံး​ပြု​သူ​၏​စာ​မျက်​နှာ​',
'nstab-special'  => 'အထူး စာမျက်နှာ',
'nstab-image'    => 'ဖိုင်',
'nstab-category' => 'ကဏ္ဍ',

# General errors
'missing-article' => 'စာမျက်နှာ "$1" မှ $2 ကို ရှာတွေ့သင့်သည်ဖြစ်သော်လည်း ဒေတာဘေ့(စ်) သည် ရှာမတွေ့ပါ။

ယင်းသည် ဖျက်ထားပြီးသား diff သို့မဟုတ် မှတ်တမ်းလင့် တစ်ခုကြောင့် ဖြစ်လေ့ရှိသည်။

ယင်းသို့မဟုတ်ပါက သင်သည် ဤဆော့ဝဲအတွင်းမှ အမှားတစ်ခုကို တွေ့နေခြင်းဖြစ်ကောင်းဖြစ်မည်။ ဤသည်ကို [[Special:ListUsers/sysop|administrator]] သို့ ကျေးဇူးပြု၍ သတင်းပို့ပေးပါ။ URL လင့်ကိုပါ ထည့်သွင်းဖော်ပြပေးပါရန်။',
'viewsource'      => 'ဆို့​ကို​ပြ​ပါ​',

# Login and logout pages
'welcomecreation'         => 'မင်္ဂ​လာ​ပါ​ $1။ သင့်​အား​မှတ်​ပုံ​တင်​ပြီး​ပါ​ပြီ။​ ဝီ​ကီ​အ​တွက်​သင့်​စိတ်​ကြိုက်​များ​ကို​ရွေး​ချယ်​နိုင်​ပါ​သည်။​',
'yourname'                => 'မှတ်​ပုံ​တင်​အ​မည်:',
'yourpassword'            => 'လှို့​ဝှက်​စ​ကား​လုံး:',
'yourpasswordagain'       => 'ပြန်​ရိုက်​ပါ:',
'remembermypassword'      => 'ဤ​ကွန်​ပျူ​တာ​တွင်​ကျွန်​တော့​ကို​မှတ်​ထား​ပါ​ (for a maximum of $1 {{PLURAL:$1|day|days}})',
'login'                   => 'Log in',
'nav-login-createaccount' => 'မှတ်​ပုံ​တင်​ဖြင့်​ဝင်​ရန်​ / မှတ်​ပုံ​တင်​ပြု​လုပ်​ရန်',
'userlogin'               => 'မှတ်ပုံတင်ဖြင့် ၀င်ပါ၊ မှတ်ပုံတင်ပြုလုပ်ပါ။',
'logout'                  => 'ထွက်​ပါ​',
'userlogout'              => 'Log out ထွက်ရန်',
'notloggedin'             => 'Not logged in',
'nologinlink'             => 'မှတ်​ပုံ​တင်​ပြု​လုပ်​ပါ​',
'createaccount'           => 'မှတ်​ပုံ​တင်​ပြု​လုပ်​ပါ​',
'gotaccountlink'          => 'Log in',
'loginsuccesstitle'       => 'မှတ်​ပုံ​တင်​ဖြင့်​ဝင်​ခြင်းအောင်မြင်သည်။',
'loginlanguagelabel'      => 'ဘာသာ: $1',

# Password reset dialog
'oldpassword' => 'ဝှက်​စ​ကား​လုံးအဟောင်း:',
'newpassword' => 'ဝှက်​စ​ကား​လုံးအသစ်:',
'retypenew'   => 'ဝှက်​စ​ကား​လုံးပအသစ်ကိုထပ်ရိုက်ပါ:',

# Edit page toolbar
'bold_tip'      => 'စာလုံးမည်း',
'italic_sample' => 'စာလုံးဆောင်း',
'italic_tip'    => 'စာသားဆောင်း',
'math_sample'   => 'သည်မှာသင်္ချာပုံသေနည်းအားထည့်',
'math_tip'      => 'သင်္ချာပုံသေနည်း (LaTeX)',
'hr_tip'        => 'မျဉ်းလဲ',

# Edit pages
'summary'            => 'အ​ကျဉ်း​ချုပ်​:',
'subject'            => 'အကြောင်းအရာ/ခေါင်းကြီးပိုင်း :',
'minoredit'          => 'သာ​မန်​ပြင်​ဆင်​မှု​ဖြစ်​ပါ​သည်​',
'watchthis'          => 'ဤစာမျက်နှာကိုစောင့်ကြည့်ပါ',
'savearticle'        => 'ဤစာမျက်နှာကို သိမ်းရန်',
'preview'            => 'နမူနာ',
'showpreview'        => 'န​မူ​နာ​ပြ​ပါ​',
'showlivepreview'    => 'နမူနာအရှင်',
'showdiff'           => 'ပြင်​ဆင်​ထား​သည်​များ​ကို​ပြ​ပါ​',
'summary-preview'    => 'အ​ကျဉ်း​ချုပ်​န​မူ​နာ:',
'whitelistedittitle' => 'ပြင်​ဆင်​ခြင်း​သည်​မှတ်​ပုံ​တင်​ရန်​လို​သည်​',
'loginreqtitle'      => 'မှတ်​ပုံ​တင်​ဖြင့်​ဝင်​ဖို့လိုပါတယ်',
'loginreqlink'       => 'log in',
'accmailtitle'       => 'ဝှက်​စ​ကား​လုံးကိုပို့ပြီးပြီ',
'newarticle'         => '(အသစ်)',
'editing'            => 'တည်းဖြတ်ရန်',
'copyrightwarning'   => 'ကျေးဇူးပြု၍ {{SITENAME}}တွင် ရေးသားမှုအားလုံးကို $2 အောက်တွင် ထုတ်လုပ်ရန် ဆုံးဖြတ်ပြီး ဖြစ်သည်။ (အသေးစိတ်ကို $1 ကိုကြည့်ပါ။',
'template-protected' => '(ကာကွယ်ထားပြီး)',

# History pages
'revisionasof'     => '$1 ရက်နေ့က မူ',
'previousrevision' => 'မူဟောင်း',
'page_first'       => 'ပထမဆုံး',
'page_last'        => 'အနောက်ဆုံး',
'histfirst'        => '​အစောဆုံး',
'histlast'         => 'နောက်ဆုံး',

# Revision deletion
'rev-delundel'   => 'ပြရန်/ဝှက်ရန်',
'revdel-restore' => 'မည်မျှ ရှုမြင်နိုင်သည်ကို ပြောင်းရန်',

# Merge log
'revertmerge' => 'ပြန်ခွဲထုတ်ရန်',

# Diffs
'lineno'   => 'စာကြောင်းး $1:',
'editundo' => 'နောက်ပြန် ပြန်ပြင်ရန်',

# Search results
'searchresults'             => 'ရှာဖွေမှု ရလာဒ်များ',
'searchresults-title'       => '"$1" အတွက် ရှာတွေ့သည့် ရလာဒ်များ',
'searchresulttext'          => '{{SITENAME}} ကို ရှာရာတွင် နောက်ထပ် သတင်းအချက်အလက်များအတွက် [[{{MediaWiki:Helppage}}|{{int:help}}]] ကို ကြည့်ပါ။',
'searchsubtitle'            => 'သင်သည် \'\'\'[[:$1]]\'\'\' ကို ရှာဖွေခဲ့သည်။ ([[Special:Prefixindex/$1|"$1" ဖြင့်စသော စာမျက်နှာအားလုံး]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|"$1" ကို လမ်းညွှန်ထားသော စာမျက်နှာအားလုံး]])',
'notitlematches'            => 'ဤခေါင်းစဉ်နှင့် ကိုက်ညီသောစာမျက်နှာမရှိပါ',
'prevn'                     => 'နောက်သို့ {{PLURAL:$1|$1}}',
'nextn'                     => 'ရှေ့သို့ {{PLURAL:$1|$1}}',
'viewprevnext'              => '($1 {{int:မှ}} $2) အထိကြား ရလာဒ် ($3) ခုကို ကြည့်ရန်',
'search-result-size'        => '$1 ({{PLURAL:$2|စကားလုံး 1 လုံး|စကားလုံး $2 လုံး}})',
'search-redirect'           => '($1 သို့ ပြန်ညွှန်းသည်)',
'search-section'            => '(အပိုင်း $1)',
'search-suggest'            => '$1 ဟု ဆိုလိုပါသလား။',
'search-mwsuggest-enabled'  => 'အကြံပြုချက်များနှင့်တကွ',
'search-mwsuggest-disabled' => 'အကြံပြုချက် မရှိပါ',
'powersearch'               => 'အထူးပြု ရှာ​ဖွေ​ရန်​',
'powersearch-legend'        => 'အထူးပြု ရှာဖွေရန်',
'powersearch-ns'            => 'အမည်ညွှန်းတို့တွင် ရှာရန် -',
'powersearch-redir'         => 'ပြန်ညွှန်းသည့် လင့်များကို စာရင်းပြုစုရန်',
'powersearch-field'         => 'ဤအကြောင်းအရာအတွက် ရှာဖွေရန်',

# Preferences page
'preferences'       => 'ကျွန်​တော့​ရွေး​ချယ်​စ​ရာ​များ​',
'mypreferences'     => '​ရွေး​ချယ်​စ​ရာ​များ​',
'prefsnologin'      => 'Not logged in',
'changepassword'    => 'ဝှက်​စ​ကား​လုံးကိုပြောင်းပါ',
'skin-preview'      => 'နမူနာ',
'prefs-math'        => 'သင်္ချာ',
'prefs-datetime'    => 'နေ့စွဲနှင့် အချိန်',
'searchresultshead' => 'ရှာ​ဖွေ​ရန်​',
'youremail'         => 'အီ​မေး:',
'username'          => 'မှတ်​ပုံ​တင်​အ​မည်:',
'uid'               => 'မှတ်​ပုံ​တင်​ID:',
'yourrealname'      => 'နာမည်ရင်း:',
'yourlanguage'      => 'ဘာသာ:',
'yournick'          => 'ဆိုင်း:',
'email'             => 'အီ​မေး​',

# Groups
'group-all' => '(အားလုံး)',

# Recent changes
'recentchanges'      => 'လတ်​တ​လော​အ​ပြောင်း​အ​လဲ​များ​',
'rcshowhideminor'    => 'အသေးအမွှာပြင်ဆင်ရန်$1',
'rcshowhidebots'     => 'စက်ရုပ်များ$1',
'hist'               => 'မှတ်တမ်း',
'hide'               => 'ဝှက်',
'show'               => 'ပြ',
'minoreditletter'    => 'သေး',
'newpageletter'      => 'သစ်',
'boteditletter'      => 'ဆ',
'rc-enhanced-expand' => 'အသေးစိတ်ပြပါ(JavaScript ဖြင့်သာ)',

# Recent changes linked
'recentchangeslinked'         => 'ဆက်​စပ်သော​ အ​ပြောင်း​အ​လဲ​များ​',
'recentchangeslinked-feed'    => 'ဆက်​ဆပ်​သော​အ​ပြောင်း​အ​လဲ​များ​',
'recentchangeslinked-toolbox' => 'ဆက်​ဆပ်​သော​အ​ပြောင်း​အ​လဲ​များ​',
'recentchangeslinked-summary' => 'ဤသည်မှာ သီးသန့်ပြထားသော စာမျက်နှာ (သို့ သီးသန့်အမျိုးအစားဝင်များ) မှ ညွှန်းထားသော စာမျက်နှာများ၏ လတ်တလော ပြောင်းလဲမှုများ၏ စာရင်းဖြစ်သည်။ [[Special:Watchlist|your watchlist]] မှ စာမျက်နှာများကို စာလုံးမည်းဖြင့် ပြထားသည်။',

# Upload
'upload'            => 'ဖိုင်​တင်​ရန်​',
'uploadbtn'         => 'ဖိုင်​တင်​ရန်​',
'uploadnologin'     => 'Not logged in',
'filename'          => 'ဖိုင်အမည်',
'filedesc'          => 'အ​ကျဉ်း​ချုပ်​',
'fileuploadsummary' => 'အ​ကျဉ်း​ချုပ်:',
'uploadedimage'     => 'ပုံတင်ရန်',
'watchthisupload'   => 'ဤ​စာ​မျက်​နှာ​အား​စောင့်​ကြည့်​ပါ​',

# Special:ListFiles
'imgfile'        => 'ဖိုင်',
'listfiles'      => 'ဖိုင်စာရင်း',
'listfiles_date' => 'နေ့စွဲ',

# File description page
'file-anchor-link'   => 'ဖိုင်',
'filehist'           => 'ဖိုင်မှတ်တမ်း',
'filehist-deleteall' => 'အားလုံးဖျက်',
'filehist-deleteone' => 'ဖျက်',
'filehist-current'   => 'ကာလပေါ်',
'filehist-datetime'  => 'နေ့စွဲ/အချိန်',
'filehist-user'      => 'အသုံးပြုသူ',
'filehist-filesize'  => 'ဖိုင်ဆိုက်',
'filehist-comment'   => 'မှတ်ချက်',
'imagelinks'         => 'ဖိုင်အဆက်အသွယ်များ',

# File deletion
'filedelete'        => '$1 ကိုဖျက်ပါ',
'filedelete-legend' => 'ဖိုင်ကိုဖျက်ပါ',
'filedelete-submit' => 'ဖျက်',

# Unused templates
'unusedtemplateswlh' => 'အခြားလိပ်စာများ',

# Random page
'randompage' => 'ကျ​ပန်း​စာ​မျက်​နှာ​',

# Statistics
'statistics' => 'စာရင်းအင်း',

'brokenredirects-edit'   => 'ပြင်​ဆင်​ရန်',
'brokenredirects-delete' => 'ဖျက်​ပါ',

'withoutinterwiki-submit' => 'ပြ',

# Miscellaneous special pages
'nbytes'            => '$1 {{PLURAL:$1|ဘိုက်|ဘိုက်}}',
'shortpages'        => 'စာမျက်နှာတို',
'newpages'          => 'စာမျက်နှာအသစ်',
'newpages-username' => 'မှတ်​ပုံ​တင်​အ​မည်:',
'ancientpages'      => 'အဟောင်းဆုံးစာမျက်နှာ',
'move'              => 'ရွေ့​ပြောင်း​ပါ​',
'movethispage'      => 'ဤစာမျက်နှာအားရွှေ့ပြောင်းပါ',

# Book sources
'booksources'    => 'မှီငြမ်း စာအုပ်များ',
'booksources-go' => 'သွား​ပါ​',

# Special:AllPages
'allpages'       => 'စာမျက်နှာအားလုံး',
'allarticles'    => 'စာမျက်နှာအားလုံး',
'allpagessubmit' => 'သွား​ပါ​',

# Special:ListUsers
'listusers-submit' => 'ပြ',

# Special:Log/newusers
'newuserlog-create-entry' => 'အသုံးပြုသူအသစ်',

# E-mail user
'emailsend' => 'ပို့',

# Watchlist
'watchlist'     => 'My watchlist',
'mywatchlist'   => 'စောင့်ကြည့်စာရင်း',
'watch'         => 'စောင့်ကြည့်ရန်',
'watchthispage' => 'Watch this page',
'unwatch'       => 'ဆက်လက် စောင့်မကြည့်တော့ရန်',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'စောင့်ကြည့်လျက်ရှိ...',
'unwatching' => 'စောင့်မကြည့်တော့...',

# Delete
'deletepage'     => 'စာမျက်နှာကိုဖျက်ပါ',
'confirm'        => 'အတည်ပြု',
'delete-confirm' => '"$1"ကို ဖျက်ပါ',
'delete-legend'  => 'ဖျက်',
'deletedarticle' => '[[$1]] ကို ဖျက်လိုက်သည်',

# Protect
'prot_1movedto2'         => '[[$1]]  မှ​ [[$2]] သို့​',
'protect-level-sysop'    => 'အက်ဒမင်များသာ',
'protect-cantedit'       => 'ကာကွယ်ထားသောစာမျက်နှာဖြစ်သည့်အတွက် ပြင်ဆင်၍ မရနိုင်ပါ။ အဘယ့်ကြောင့်ဆိုသော် သင့်မှာ တည်းဖြတ်နိုင်ခွင့် မရှိ၍ ဖြစ်ပါသည်။',
'protect-expiry-options' => '၂ နာရီ:2 hours,၁ နေ့:1 day,၃ နေ့:3 days,၁ ပတ်:1 week,၂ ပတ်:2 weeks,၁ လ:1 month,၃ လ:3 months,၆ လ:6 months,၁ နှစ်:1 year,အနန္တ:infinite',
'restriction-type'       => 'အခွင့်:',
'restriction-level'      => 'ကန့်သတ်ထားသော level:',

# Restrictions (nouns)
'restriction-edit'   => 'ပြင်​ဆင်​ရန်​',
'restriction-move'   => 'ရွေ့​ပြောင်း​ပါ​',
'restriction-create' => 'ထွင်',

# Undelete
'undeletelink'           => 'စောင့်ကြည့်ရန်/ပြန်လည်ထိန်းသိမ်းရန်',
'undelete-search-submit' => 'ရှာ​ဖွေ​ရန်​',

# Namespace form on various pages
'namespace'      => 'အမည်ညွှန်း -',
'blanknamespace' => '(ပင်မ)',

# Contributions
'contributions' => 'မှတ်​ပုံ​တင်​အ​သုံး​ပြု​သူ​:ပံ​ပိုး​မှု​များ​',
'mycontris'     => 'ကျွန်​တော့် ရေးသားချက်များ',
'contribsub2'   => '$1အတွက် ($2)',
'uctop'         => '(အထိပ်)',

'sp-contributions-submit' => 'ရှာ​ဖွေ​ရန်​',

# What links here
'whatlinkshere' => 'ဒီမှာ ဘာလင့်တွေရှိလဲ',

# Block/unblock
'ipbreason'          => 'အ​ကြောင်း​ပြ​ချက်:',
'ipbother'           => 'အခြားအချိန်:',
'ipboptions'         => '၂ နာရီ:2 hours,၁ နေ့:1 day,၃ နေ့:3 days,၁ ပတ်:1 week,၂ ပတ်:2 weeks,၁ လ:1 month,၃ လ:3 months,၆ လ:6 months,၁ နှစ်:1 year,အနန္တ:infinite',
'ipbotheroption'     => 'အခြား',
'ipblocklist-submit' => 'ရှာ​ဖွေ​ရန်​',
'expiringblock'      => '$1 $2 ဆုံးမည်',
'blocklink'          => 'တားဆီးကန့်သတ်ရန်',
'unblocklink'        => 'လင့် ပြန်ဖြေရန်',
'change-blocklink'   => 'စာကြောင်းအမည် ပြောင်းရန်',
'contribslink'       => 'ရေးသားချက်များ',

# Move page
'move-page-legend' => 'စာ​မျက်​နှာ​အား​ရွေ့​ပြောင်း​ပါ​',
'movearticle'      => 'စာ​မျက်​နှာ​အား​ရွေ့​ပြောင်း​ပါ​',
'movenologin'      => 'Not logged in',
'newtitle'         => 'ခေါင်းစဉ်အသစ်သို့:',
'movepagebtn'      => 'စာ​မျက်​နှာ​အား​ရွေ့​ပြောင်း​ပါ​',
'pagemovedsub'     => 'ပြောင်းရွှေ့ခြင်းအောင်မြင်သည်',
'movedto'          => 'ရွေ့​ပြောင်း​ရန်​နေ​ရာ​',
'1movedto2'        => '[[$1]]  မှ​ [[$2]] သို့​',
'movereason'       => 'အ​ကြောင်း​ပြ​ချက်​',
'revertmove'       => 'ပြောင်းရန်',

# Namespace 8 related
'allmessages'     => 'စ​နစ်​၏​သ​တင်း​များ​',
'allmessagesname' => 'အမည်',

# Thumbnails
'thumbnail-more' => 'ပုံကြီးချဲ့ရန်',

# Tooltip help for the actions
'tooltip-pt-userpage'            => 'ကိုယ်ပိုင်စာမျက်နှာ',
'tooltip-pt-mytalk'              => 'ကျွနု်ပ်၏ ဆွေးနွေးချက်များ',
'tooltip-pt-preferences'         => 'ကျွန်​တော့​ရွေး​ချယ်​စ​ရာ​များ​',
'tooltip-pt-login'               => 'မှတ်ပုံတင်ဖြင့် ဝင်ရန် အားပေးပါသည်။ သို့သော် မှတ်ပုံမတင်မနေရ မဟုတ်ပါ။',
'tooltip-pt-logout'              => 'ထွက်​ပါ​',
'tooltip-ca-talk'                => 'မာတိကာ စာမျက်နှာအတွက် ဆွေးနွေးချက်များ',
'tooltip-ca-edit'                => 'ဤစာမျက်နှာကို တည်းဖြတ်နိုင်သည်။ ကျေးဇူးပြု၍ မသိမ်းခင် နမူနာ ခလုတ်ကိုနှိပ်ပြီး ကြည့်ပေးပါ။',
'tooltip-ca-history'             => 'ဤစာမျက်နှာ၏ ယခင်မူများ',
'tooltip-ca-protect'             => 'ဤစာမျက်နှာကို ထိန်းသိမ်းပါ',
'tooltip-ca-delete'              => 'ဤစာမျက်နှာဖျက်ပါ',
'tooltip-ca-move'                => 'ဤ​စာ​မျက်​နှာ​အား​ရွေ့​ပြောင်း​ပါ​',
'tooltip-search'                 => '{{SITENAME}} ကို ရှာရန်',
'tooltip-search-go'              => 'ဤအမည်နှင့် ထပ်တူညီသော စာမျက်နှာရှိပါက ယင်းသို့ သွားရန်',
'tooltip-search-fulltext'        => 'ဤစာလုံးများပါသော စာမျက်နှာကို ရှာရန်',
'tooltip-n-mainpage'             => 'ဗဟို စာမျက်နှာသို့ သွားရန်',
'tooltip-n-mainpage-description' => 'ဗဟို စာမျက်နှာသို့ သွားရန်',
'tooltip-n-portal'               => 'ပရောဂျက်အကြောင်း၊ သင်ဘာလုပ်ပေးနိုင်သည် နှင့် ဘယ်နေရာတွင် ရှာဖွေရန်များ',
'tooltip-n-currentevents'        => 'လက်ရှိ အဖြစ်အပျက်များမှ နောက်ခံ အချက်အလက်များကို ရှာရန်',
'tooltip-n-recentchanges'        => 'ဝီကီမှ လတ်တလောအပြောင်းအလဲများ စာရင်း',
'tooltip-n-randompage'           => 'ကျပန်းစာမျက်နှာ ပြရန်',
'tooltip-n-help'                 => 'ရှာဖွေဖော်ထုတ်ရန်နေရာ',
'tooltip-t-whatlinkshere'        => 'ဤနေရာသို့ လမ်းညွှန်ထားသည့် စာမျက်နှာများ၏ စာရင်း',
'tooltip-t-recentchangeslinked'  => 'ဤစာမျက်နှာမှ ညွှန်းထားသည့် စာမျက်နှာများမှ လတ်တလော အပြောင်းအလဲများ',
'tooltip-t-upload'               => 'ဖိုင်တင်ရန်',
'tooltip-t-specialpages'         => 'အထူး စာမျက်နှာ စာရင်းများ',
'tooltip-t-print'                => 'ဤစာမျက်နှာ၏ ပရင့်ထုတ်နိုင်သောမူ',
'tooltip-t-permalink'            => 'ယခုမူအတွက် ပုံသေလိပ်စာ',
'tooltip-ca-nstab-main'          => 'မာတိကာ ကြည့်ရန်',
'tooltip-ca-nstab-special'       => 'ဤသည်မှာ အထူးစာမျက်နှာဖြစ်သည်။ ဤစာမျက်နှာကို ပြင်ဆင် မရနိုင်ပါ။',
'tooltip-ca-nstab-image'         => 'ဖိုင်စာမျက်နှာကိုကြည့်ပါ။',
'tooltip-save'                   => 'ပြင်ဆင်ရန်သိမ်းပါ',

# Special:NewFiles
'ilsubmit' => 'ရှာ​ဖွေ​ရန်​',

# Bad image list
'bad_image_list' => 'ဖောမတ်ပုံစံမှာ အောက်ပါအတိုင်းဖြစ်သည်။

စာရင်းတွင်ထည့်သွင်းထားသော အရာများကိုသာ ထည့်သွင်းစဉ်းစားမည်။ (ခရေပွင့် * ဖြင့်စသော စာကြောင်းများ)
စာကြောင်း၏ ပထမဆုံး လင့်သည် ဖိုင်ညံ့ ဖြစ်ရမည်။
ယင်းစာကြောင်းတွင်ပင် နောက်ထပ်လာသောလင့်များကို ချွင်းချက်အဖြစ် စဉ်းစားမည်။ ဆိုလိုသည်မှာ ၎င်းလင့်များတွင်လည်း အဆိုပါ ဖိုင်ညံ့ ပါကောင်း ပါဝင်နေမည်။',

# EXIF tags
'exif-exposuretime-format' => '$1 စက္ကန့် ($2)',
'exif-gpsaltitude'         => 'အမြင့်',

'exif-meteringmode-255' => 'အခြား',

'exif-lightsource-1' => 'နေ့အလင်း',

'exif-focalplaneresolutionunit-2' => 'လက်မှတ်',

'exif-scenecapturetype-3' => 'ညနေပုံ',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'အားလုံး',
'imagelistall'     => 'အားလုံး',
'watchlistall2'    => 'အားလုံး',
'namespacesall'    => 'အားလုံး',
'monthsall'        => 'အားလုံး',

# E-mail address confirmation
'confirmemail' => 'အီးမေးကိုအတည်ပြုပါ',

# Multipage image navigation
'imgmultigo' => 'သွား​ပါ!',

# Table pager
'table_pager_limit_submit' => 'သွား​ပါ​',

# Auto-summaries
'autosumm-new' => 'စာမျက်နှာအသစ်: $1',

# Watchlist editing tools
'watchlisttools-edit' => 'စောင့်ကြည့်စာရင်းများကို ကြည့်ပြီး တည်းဖြတ်ပါ။',

# Special:Version
'version-other' => 'အခြား',

# Special:FilePath
'filepath-page' => 'ဖိုင်:',

# Special:SpecialPages
'specialpages' => 'အ​ထူး ​စာ​မျက်​နှာ​များ',

);
