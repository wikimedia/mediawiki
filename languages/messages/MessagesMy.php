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
 * @author Purodha
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
'tog-rememberpassword' => 'ဤ​ကွန်​ပျူ​တာ​တွင်​ ကျွန်ုပ်ကို​မှတ်​ထား​ရန် (အများဆုံး $1 {{PLURAL:$1|ရက်|ရက်}}ကြာ)',
'tog-watchcreations'   => 'ကျွန်တော်ထွင်သည့်စာမျက်နှာများကို စောင့်​ကြည့်​စာ​ရင်း​ထဲ ပေါင်းထည့်ပါ',
'tog-watchdefault'     => 'ကျွန်ုပ် တည်းဖြတ်ခဲ့သည့် စာမျက်နှာများကို စောင့်ကြည့်စာရင်းသို့  ပေါင်းထည့်ပါ။',

'underline-always'  => 'အမြဲ',
'underline-never'   => 'ဘယ်သောအခါမျှ',
'underline-default' => 'ဘရောက်ဆာ default အတိုင်း',

# Font style option in Special:Preferences
'editfont-style'     => 'ဖောင့်စတိုင်ကို ပြုပြင်ရန် -',
'editfont-default'   => 'ဘရောက်ဆာ default အတိုင်း',
'editfont-monospace' => 'စာလုံးတိုင်းအရွယ်ညီသောဖောင့်',
'editfont-sansserif' => 'အတက်မပါသောဖောင့်',
'editfont-serif'     => 'အတက်ပါသောဖောင့်',

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
'pagecategories'         => '{{PLURAL:$1|ကဏ္ဍ|ကဏ္ဍ}}',
'category_header'        => 'ကဏ္ဍ "$1" မှ စာမျက်နှာများ',
'subcategories'          => 'အုပ်စုခွဲ',
'category-empty'         => 'ဗလာအုပ်စု',
'hidden-categories'      => '{{PLURAL:$1|ဝှက်ထားသော ကဏ္ဍ|ဝှက်ထားသော ကဏ္ဍများ}}',
'category-subcat-count'  => '{{PLURAL:$2|ဤကဏ္ဍတွင် အောက်ပါ ကဏ္ဍခွဲသာ ရှိသည်။ |ဤကဏ္ဍတွင် စုစုပေါင်း $2 ခု အနက်မှ အောက်ပါ {{PLURAL:$1|ကဏ္ဍခွဲ|ကဏ္ဍခွဲ $1 ခု}} ရှိသည်။}}',
'category-article-count' => '{{PLURAL:$2|ဤကဏ္ဍတွင် အောက်ပါစာမျက်နှာသာ ရှိသည်။|စုစုပေါင်း $2 အနက်မှ အောက်ပါ {{PLURAL:$1|စာမျက်နှာသည်|စာမျက်နှာ $1 ခုသည်}} ဤကဏ္ဍတွင် ရှိသည်။}}',
'listingcontinuesabbrev' => 'ပံ့ပိုး',

'about'         => 'အကြောင်း',
'article'       => 'စာမျက်နှာ',
'newwindow'     => '(ဝင်းဒိုးအသစ်တခုကိုဖွင့်ရန်)',
'cancel'        => 'မ​လုပ်​တော့​',
'moredotdotdot' => 'နောက်ထပ်...',
'mypage'        => 'ကျွန်ုပ် စာမျက်နှာ',
'mytalk'        => 'ဆွေးနွေးချက်',
'anontalk'      => 'ဤ IP address အတွက် ဆွေးနွေးရန်',
'navigation'    => 'အ​ညွှန်း​',
'and'           => '&#32;နှင့်',

# Cologne Blue skin
'qbfind'         => 'ရှာပါ',
'qbbrowse'       => 'ရှာဖွေလှန်လှောရန်',
'qbedit'         => 'ပြင်​ဆင်​ရန်​',
'qbpageoptions'  => 'ဤစာမျက်နှာ',
'qbpageinfo'     => 'မာတိကာ',
'qbmyoptions'    => 'ကျွန်ုပ် စာမျက်နှာများ',
'qbspecialpages' => 'အ​ထူး​စာ​မျက်​နှာ​',
'faq'            => 'မေးလေ့ရှိကြသည်များ',
'faqpage'        => 'Project:မေးလေ့ရှိကြသည်များ',

# Vector skin
'vector-action-addsection' => 'အကြောင်းအရာအသစ် ထပ်ထည့်ရန်',
'vector-action-delete'     => 'ဖျက်​ပါ​',
'vector-action-move'       => 'ရွှေ့ပါ',
'vector-action-protect'    => 'ထိမ်း​သိမ်း​ပါ​',
'vector-action-undelete'   => 'မဖျက်တော့ရန်',
'vector-action-unprotect'  => 'မကာကွယ်တော့ရန်',
'vector-view-create'       => 'စတင်ရေးသားရန်',
'vector-view-edit'         => 'ပြင်ရန်',
'vector-view-history'      => 'မှတ်တမ်းကြည့်ရန်',
'vector-view-view'         => 'ဖတ်ရန်',
'vector-view-viewsource'   => 'ရင်းမြစ်ကို ကြည့်ရန်',
'actions'                  => 'ဆောင်ရွက်ချက်များ',
'namespaces'               => 'အမည်ညွှန်းများ',
'variants'                 => 'အမျိုးမျိုးအပြားပြား',

'errorpagetitle'    => 'အမှား',
'returnto'          => '$1 သို့ ပြန်သွားရန်။',
'tagline'           => '{{SITENAME}} မှ',
'help'              => 'အ​ကူ​အ​ညီ​',
'search'            => 'ရှာ​ဖွေ​ရန်​',
'searchbutton'      => 'ရှာ​ဖွေ​ရန်​',
'go'                => 'သွား​ပါ​',
'searcharticle'     => 'သွား​ပါ​',
'history'           => 'စာမျက်နှာ မှတ်တမ်း',
'history_short'     => 'မှတ်​တမ်း​',
'updatedmarker'     => 'နောက်ဆုံးထာကြည့်ပြီးသည့်နောက်ပိုင်း တည်းဖြတ်ထားသည်။',
'info_short'        => 'သတင်းအချက်အလက်',
'printableversion'  => 'ပရင့်ထုတ်ရန်',
'permalink'         => 'ပုံ​သေ​လိပ်​စာ​',
'print'             => 'ပရင့်',
'edit'              => 'ပြင်​ဆင်​ရန်​',
'create'            => 'စတင်ရေးသားရန်',
'editthispage'      => 'ဤစာမျက်နှာကို ပြင်ရန်',
'create-this-page'  => 'ဤစာမျက်နှာကို စတင်ရေးသားရန်',
'delete'            => 'ဖျက်​ပါ​',
'deletethispage'    => 'ဤစာမျက်နှာဖျက်ပါ',
'undelete_short'    => '{{PLURAL:$1|တည်းဖြတ်မှုတစ်ခု|တည်းဖြတ်မှု $1 ခုတို့}}ကို မဖျက်တော့ရန်',
'protect'           => 'ထိမ်း​သိမ်း​ပါ​',
'protect_change'    => 'ပြောင်းလဲရန်',
'protectthispage'   => 'ဤစာမျက်နှာကို ကာကွယ်ရန်',
'unprotect'         => 'မကာကွယ်တော့ရန်',
'unprotectthispage' => 'ဤစာမျက်နှာကို မကာကွယ်တော့ရန်',
'newpage'           => 'စာမျက်နှာအသစ်',
'talkpage'          => 'ဆွေးနွေးရန်',
'talkpagelinktext'  => 'ဆွေးနွေးရန်',
'specialpage'       => 'အထူး စာမျက်နှာ',
'personaltools'     => 'ကိုယ်ပိုင် ကိရိယာများ',
'postcomment'       => 'အပိုင်းသစ်',
'articlepage'       => 'မာတိကာ ကြည့်ရန်',
'talk'              => 'ဆွေးနွေးချက်များ',
'views'             => 'ပုံပန်းသွင်ပြင်',
'toolbox'           => 'လက်စွဲ ကိရိယာများ',
'userpage'          => 'အသုံးပြုသူ၏ စာမျက်နှာကို ကြည့်ရန်',
'projectpage'       => 'ပရောဂျက်စာမျက်နှာကို ကြည့်ရန်',
'imagepage'         => 'ဖိုင်စာမျက်နှာကိုကြည့်ရန်',
'mediawikipage'     => 'မီဒီယာဝီကီစာမျက်နှာ',
'templatepage'      => 'တမ်းပလိတ်စာမျက်နှာကို ကြည့်ရန်',
'viewhelppage'      => 'ကူညီမယ့် စာမျက်နှာကိုကြည့်ရန်',
'categorypage'      => 'ကဏ္ဍများကို ကြည့်ရန်',
'viewtalkpage'      => 'ဆွေးနွေးမှုကို ကြည့်ရန်',
'otherlanguages'    => 'တခြား ဘာသာဖြင့်',
'redirectedfrom'    => '($1 မှ ပြန်ညွှန်းထားသည်)',
'redirectpagesub'   => 'စာမျက်နှာ ပြန်ညွှန်းရန်',
'lastmodifiedat'    => 'ဤစာမျက်နှာကို $1 ရက် $2 အချိန်တွင် နောက်ဆုံး ပြင်ဆင်ခဲ့သည်။',
'viewcount'         => 'ဤစာမျက်နှာကို {{PLURAL:$1|တစ်ကြိမ်|$1 ကြိမ်}} ဝင်ထားသည်။',
'protectedpage'     => 'ကာကွယ်ထားသည့် စာမျက်နှာ',
'jumpto'            => 'ဤနေရာသို့သွားရန် -',
'jumptonavigation'  => 'အ​ညွှန်း​',
'jumptosearch'      => 'ရှာ​ဖွေ​ရန်​',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => '{{SITENAME}} အကြောင်း',
'aboutpage'            => 'Project: အကြောင်းအရာ',
'copyright'            => '$1 အောက်တွင် ဤအကြောင်းအရာကို ရရှိနိုင်သည်။',
'copyrightpage'        => '{{ns:project}}: မူပိုင်ခွင့်',
'currentevents'        => 'လက်​ရှိ​လုပ်​ငန်း​များ​',
'currentevents-url'    => 'Project:လက်​ရှိ​လုပ်​ငန်း​များ​',
'disclaimers'          => 'သတိပြုစရာများ',
'disclaimerpage'       => 'Project: အထွေထွေ သတိပြုဖွယ်',
'edithelp'             => 'ပြင်​ဆင်​ရန် အ​ကူ​အ​ညီ​',
'edithelppage'         => 'Help: တည်းဖြတ်ခြင်း',
'helppage'             => 'Help: မာတိကာ',
'mainpage'             => 'ဗ​ဟို​စာ​မျက်​နှာ​',
'mainpage-description' => 'ဗ​ဟို​စာ​မျက်​နှာ​',
'policy-url'           => 'Project:မူ​ဝါ​ဒ',
'portal'               => 'စုစည်းဆွေးနွေးရာ',
'portal-url'           => 'Project:စုစည်းဆွေးနွေးရာ',
'privacy'              => 'ကိုယ်ပိုင်ရေးရာ မူဝါဒ',
'privacypage'          => 'Project: ကိုယ်ပိုင်ရေးရာ မူဝါဒ',

'badaccess' => 'ခွင့်ပြုချက်မှ အမှား',

'ok'                      => 'အိုကေ',
'retrievedfrom'           => '"$1" မှ ရယူရန်',
'youhavenewmessages'      => 'သင့်တွင် $1 ($2) ရှိသည်။',
'newmessageslink'         => 'မက်ဆေ့ အသစ်',
'newmessagesdifflink'     => 'နောက်ဆုံးအပြောင်းအလဲ',
'youhavenewmessagesmulti' => '$1 မှာ မက်ဆေ့အသစ်များ ရှိသည်',
'editsection'             => 'ပြင်​ဆင်​ရန်​',
'editold'                 => 'ပြင်​ဆင်​ရန်​',
'viewsourceold'           => 'ရင်းမြစ်ကို ကြည့်ရန်',
'editlink'                => 'ပြင်​ဆင်​ရန်',
'viewsourcelink'          => 'ရင်းမြစ်ကို ကြည့်ရန်',
'editsectionhint'         => 'ဤအပိုင်းကို တည်းဖြတ်ရန် - $1',
'toc'                     => 'မာတိကာ',
'showtoc'                 => 'ပြ',
'hidetoc'                 => 'ဝှက်',
'site-rss-feed'           => 'RSS feed $1 ခု',
'site-atom-feed'          => 'Atom feed $1 ခု',
'page-rss-feed'           => 'RSS feed "$1" ခု',
'page-atom-feed'          => 'Atom feed "$1" ခု',
'red-link-title'          => '$1 (ဤစာမျက်နှာ မရှိပါ)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'စာ​မျက်​နှာ​',
'nstab-user'      => 'အ​သုံး​ပြု​သူ​၏ ​စာ​မျက်​နှာ​',
'nstab-special'   => 'အထူး စာမျက်နှာ',
'nstab-project'   => 'ပရောဂျက်စာမျက်နှာ',
'nstab-image'     => 'ဖိုင်',
'nstab-mediawiki' => 'မက်ဆေ့',
'nstab-template'  => 'တမ်းပလိတ်',
'nstab-category'  => 'ကဏ္ဍ',

# General errors
'missing-article'    => 'စာမျက်နှာ "$1" မှ $2 ကို ရှာတွေ့သင့်သည်ဖြစ်သော်လည်း ဒေတာဘေ့(စ်) သည် ရှာမတွေ့ပါ။

ယင်းသည် ဖျက်ထားပြီးသား diff သို့မဟုတ် မှတ်တမ်းလင့် တစ်ခုကြောင့် ဖြစ်လေ့ရှိသည်။

ယင်းသို့မဟုတ်ပါက သင်သည် ဤဆော့ဝဲအတွင်းမှ အမှားတစ်ခုကို တွေ့နေခြင်းဖြစ်ကောင်းဖြစ်မည်။ ဤသည်ကို [[Special:ListUsers/sysop|administrator]] သို့ ကျေးဇူးပြု၍ သတင်းပို့ပေးပါ။ URL လင့်ကိုပါ ထည့်သွင်းဖော်ပြပေးပါရန်။',
'missingarticle-rev' => '(တည်းဖြတ်မူ#: $1)',
'badtitletext'       => 'တောင်းဆိုထားသော စာမျက်နှာ ခေါင်းစဉ်သည် တရားမဝင်ပါ (သို့) ဗလာဖြစ်နေသည် (သို့) အခြားဘာသာများ(inter-language or inter-wiki title)သို့ မှားယွင်းစွာ လင့်ချိတ်ထားသည်။',
'viewsource'         => 'ရင်းမြစ်ကို ကြည့်ရန်',

# Login and logout pages
'welcomecreation'         => '== မင်္ဂလာပါ $1! ==
သင့်အကောင့်ကို ဖန်တီးပြီးပါပြီ။
[[Special:Preferences|{{SITENAME}} စိတ်​ကြိုက်​ရွေးချယ်စရာတို့]]ကို ပြောင်းရန် မမေ့ပါနှင့်။',
'yourname'                => 'မှတ်​ပုံ​တင်​အ​မည်:',
'yourpassword'            => 'စကားဝှက် -',
'yourpasswordagain'       => 'ပြန်​ရိုက်​ပါ:',
'remembermypassword'      => 'ဤ​ကွန်​ပျူ​တာ​တွင်​ ကျွနု်ပ်ကို​မှတ်​ထား​ရန် (အများဆုံး $1 {{PLURAL:$1|ရက်|ရက်}}ကြာ)',
'login'                   => 'Log in ဝင်ရန်',
'nav-login-createaccount' => 'Log in ၀င်ရန်/ အကောင့် လုပ်ရန်',
'userlogin'               => 'Log in ၀င်ရန်/ အကောင့် လုပ်ရန်',
'userloginnocreate'       => 'Log in ဝင်ရန်',
'logout'                  => 'Log out ထွက်ရန်',
'userlogout'              => 'Log out ထွက်ရန်',
'notloggedin'             => 'logged in ဝင်မထားပါ',
'nologinlink'             => 'အကောင့်လုပ်ရန်',
'createaccount'           => 'အကောင့်လုပ်ရန်',
'gotaccountlink'          => 'Log in ဝင်ရန်',
'createaccountreason'     => 'အ​ကြောင်း​ပြ​ချက် -',
'loginsuccesstitle'       => 'Login ဝင်​ခြင်း အောင်မြင်သည်။',
'mailmypassword'          => 'စကားဝှက်အသစ်ကို အီးမေး ပို့ရန်',
'loginlanguagelabel'      => 'ဘာသာ: $1',

# Password reset dialog
'oldpassword' => 'စကားဝှက် အဟောင်း -',
'newpassword' => 'စကားဝှက် အသစ် -',
'retypenew'   => 'စကားဝှက် အသစ်ကို ထပ်ရိုက်ပါ -',

# Edit page toolbar
'bold_sample'     => 'စာလုံးမည်း',
'bold_tip'        => 'စာလုံးမည်း',
'italic_sample'   => 'စာလုံး အစောင်း',
'italic_tip'      => 'စာလုံး အစောင်း',
'link_sample'     => 'လင့် ခေါင်းစဥ်',
'link_tip'        => 'အတွင်းပိုင်း လင့်',
'extlink_sample'  => 'http://www.example.com လင့် ခေါင်းစဉ်',
'extlink_tip'     => 'ပြင်ပလင့်များ (http:// ကို ရှေ့ဆုံးမှ ထည့်ရေးရန် မမေ့ပါနှင့်)',
'headline_sample' => 'ခေါင်းကြီးစာသား',
'headline_tip'    => 'အဆင့် ၂ ခေါင်းစီး',
'math_sample'     => 'ဤနေရာတွင် သင်္ချာပုံသေနည်း သုံးရန်',
'math_tip'        => 'သင်္ချာပုံသေနည်း (LaTeX)',
'nowiki_sample'   => 'ဖောမတ်မလုပ်ထားသော စာများကို ဤနေရာတွင် ထည့်ရန်',
'nowiki_tip'      => 'ဝီကီပုံစံ ဖော်မတ်များကို လျစ်လျူရှုရန်',
'image_tip'       => 'Embedded ထည့်ထားသော ဖိုင်',
'media_tip'       => 'ဖိုင်လင့်',
'sig_tip'         => 'အချိန်ပါပြသော သင့်လက်မှတ်',
'hr_tip'          => 'မျဉ်းလဲ (စိစစ်သုံးရန်)',

# Edit pages
'summary'                          => 'အ​ကျဉ်း​ချုပ်​ -',
'subject'                          => 'အကြောင်းအရာ/ခေါင်းကြီးပိုင်း -',
'minoredit'                        => 'သာ​မန် ​ပြင်​ဆင်​မှု ​ဖြစ်​သည်​',
'watchthis'                        => 'ဤစာမျက်နှာကို စောင့်ကြည့်ရန်',
'savearticle'                      => 'ဤစာမျက်နှာကို သိမ်းရန်',
'preview'                          => 'နမူနာ',
'showpreview'                      => 'န​မူ​နာ​ပြ​ရန်',
'showlivepreview'                  => 'နမူနာအရှင်',
'showdiff'                         => 'ပြင်​ဆင်​ထား​သည်​များ​ကို​ ပြရန်',
'anoneditwarning'                  => "'''သတိပေးချက် - ''' သင်သည် logged in ဝင်မထားပါ။
ဤစာမျက်နှာ၏ တည်းဖြတ်မှတ်တမ်းတွင် သင့် IP address ကို မှတ်သားထားမည် ဖြစ်သည်။",
'summary-preview'                  => 'အ​ကျဉ်း​ချုပ်​န​မူ​နာ:',
'blockednoreason'                  => 'အကြောင်းပြချက် မပေးထားပါ',
'whitelistedittitle'               => 'ပြင်ဆင်ရန် log in ဝင်ထားဖို့ လိုသည်',
'loginreqtitle'                    => 'login ဝင်ထားရန် လိုသည်',
'loginreqlink'                     => 'log in ဝင်ရန်',
'accmailtitle'                     => 'စကားဝှက်ကို ပို့ပြီးပြီ',
'newarticle'                       => '(အသစ်)',
'newarticletext'                   => "သင်သည် မရှိသေးသော စာမျက်နှာလင့် ကို ရောက်လာခြင်းဖြစ်သည်။
စာမျက်နှာအသစ်စတင်ရန် အောက်မှ သေတ္တာထဲတွင် စတင်ရိုက်ထည့်ပါ (နောက်ထပ် သတင်းအချက်အလက်များအတွက်[[{{MediaWiki:Helppage}}|အကူအညီ စာမျက်နှာ]]ကို ကြည့်ပါ)။
မတော်တဆရောက်လာခြင်း ဖြစ်ပါက ဘရောက်ဆာ၏ နောက်ပြန်ပြန်သွားသော'''back''' ခလုတ်ကို နှိပ်ပါ။",
'noarticletext'                    => 'ဤစာမျက်နှာတွင် ယခုလက်ရှိတွင် ဘာစာသားမှ မရှိပါ။
သင်သည် အခြားစာမျက်နှာများတွင် [[Special:Search/{{PAGENAME}}|ဤစာမျက်နှာ၏ ခေါင်းစဉ်ကို ရှာနိုင်သည်]]၊ <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} ဆက်စပ်ရာ Logs များကို ရှာနိုင်သည်]၊ သို့မဟုတ် [{{fullurl:{{FULLPAGENAME}}|action=edit}} ဤစာမျက်နှာကို တည်းဖြတ်နိုင်သည်]</span>။',
'previewnote'                      => "'''ဤသည်မှာ နမူနာ ကြည့်နေခြင်းသာဖြစ်ကြောင်း မမေ့ပါနှင့်။'''
သင်ပြောင်းလဲထားသည်များကို မသိမ်းရသေးပါ။",
'editing'                          => '$1 ကို တည်းဖြတ်နေသည်',
'editingsection'                   => '$1 (အပိုင်း) ကို ပြင်ဆင်နေသည်။',
'copyrightwarning'                 => "{{SITENAME}} တွင် ရေးသားမှုအားလုံးကို $2 အောက်တွင် ဖြန့်ဝေရန် ဆုံးဖြတ်ပြီး ဖြစ်သည်ကို ကျေးဇူးပြု၍ သတိပြုပါ။။ (အသေးစိတ်ကို $1 တွင်ကြည့်ပါ။)
အကယ်၍ သင့်ရေးသားချက်များကို အညှာအတာမရှိ တည်းဖြတ်ခံရခြင်း၊ စိတ်တိုင်းကျ ဖြန့်ဝေခံရခြင်းတို့ကို အလိုမရှိပါက ဤနေရာတွင် မတင်ပါနှင့်။<br />
သင်သည် ဤဆောင်းပါးကို သင်ကိုယ်တိုင်ရေးသားခြင်း၊ သို့မဟုတ် အများပြည်သူဆိုင်ရာဒိုမိန်းများ၊ ယင်းကဲ့သို့ လွတ်လပ်သည့် ရင်းမြစ်မှ ကူးယူထားခြင်း ဖြစ်ကြောင်းလည်း ဝန်ခံ ကတိပြုပါသည်။
'''မူပိုင်ခွင့်ရှိသော စာ၊ပုံများကို ခွင့်ပြုချက်မရှိဘဲ မတင်ပါနှင့်။'''",
'templatesused'                    => '{{PLURAL:$1|တမ်းပလိတ်|တမ်းပလိတ်}} ခုကို ဤစာမျက်နှာကို သုံးထားသည် -',
'templatesusedpreview'             => '{{PLURAL:$1|တမ်းပလိတ်|တမ်းပလိတ်}} ခုကို ဤနမူနာတွင် သုံးထားသည် -',
'template-protected'               => '(ကာကွယ်ထားသည်)',
'template-semiprotected'           => '(တစ်စိတ်တစ်ပိုင်း ကာကွယ်ထားသည်)',
'hiddencategories'                 => 'ဤစာမျက်နှာသည် {{PLURAL:$1|ဝှက်ထားသော ကဏ္ဍတစ်ခု|ဝှက်ထားသော ကဏ္ဍ $1 ခု}} ၏ အဖွဲ့ဝင် ဖြစ်သည်။',
'permissionserrorstext-withaction' => 'အောက်ပါ အကြောင်းပြချက် {{PLURAL:$1|ခု|ခု}} ကြောင့် $2 အတွက် ခွင့်ပြုချက်မရှိပါ -',

# History pages
'viewpagelogs'           => 'ဤစာမျက်နှာအတွက် မှတ်တမ်းများကို ကြည့်ရန်',
'currentrev-asof'        => '$1 က နောက်ဆုံး တည်းဖြတ်မူ',
'revisionasof'           => '$1 ရက်နေ့က မူ',
'previousrevision'       => 'မူဟောင်း',
'nextrevision'           => 'ပိုသစ်သော တည်းဖြတ်မူ →',
'currentrevisionlink'    => 'နောက်ဆုံး မူ',
'cur'                    => 'လက်ရှိ',
'last'                   => 'ယခုမတိုင်မီ',
'page_first'             => 'ပထမဆုံး',
'page_last'              => 'အနောက်ဆုံး',
'histlegend'             => "တည်းဖြတ်မူများကို နှိုင်းယှဥ်ရန် radio boxes လေးများကို မှတ်သားပြီးနောက် Enter ရိုက်ချပါ သို့ အောက်ခြေမှ ခလုတ်ကို နှိပ်ပါ။<br />
Legend: '''({{int:cur}})''' = နောက်ဆုံးမူနှင့် ကွဲပြားချက် '''({{int:last}})''' = ယင်းရှေ့မူနှင့် ကွဲပြားချက်, '''{{int:minoreditletter}}''' = အရေးမကြီးသော ပြုပြင်မှု.",
'history-fieldset-title' => 'မှတ်တမ်းရှာကြည့်ရန်',
'histfirst'              => '​အစောဆုံး',
'histlast'               => 'နောက်ဆုံး',

# Revision deletion
'rev-delundel'              => 'ပြရန်/ဝှက်ရန်',
'revdelete-log'             => 'အ​ကြောင်း​ပြ​ချက်:',
'revdel-restore'            => 'မည်မျှ ရှုမြင်နိုင်သည်ကို ပြောင်းရန်',
'revdelete-otherreason'     => 'အခြားသော/နောက်ထပ် အကြောင်းပြချက် -',
'revdelete-reasonotherlist' => 'အခြား အကြောင်းပြချက်',

# Merge log
'revertmerge' => 'ပြန်ခွဲထုတ်ရန်',

# Diffs
'history-title'           => '"$1" ၏ တည်းဖြတ်မူ မှတ်တမ်းများ',
'difference'              => 'တည်းဖြတ်မူများ အကြား ကွဲပြားမှုများ',
'lineno'                  => 'စာကြောင်း $1:',
'compareselectedversions' => 'ရွေးချယ်ထားသော မူများကို နှိုင်းယှဥ်ရန်',
'editundo'                => 'နောက်ပြန် ပြန်ပြင်ရန်',

# Search results
'searchresults'             => 'ရှာဖွေမှု ရလဒ်များ',
'searchresults-title'       => '"$1" အတွက် ရှာတွေ့သည့် ရလဒ်များ',
'searchresulttext'          => '{{SITENAME}} ကို ရှာရာတွင် နောက်ထပ် သတင်းအချက်အလက်များအတွက် [[{{MediaWiki:Helppage}}|{{int:help}}]] ကို ကြည့်ပါ။',
'searchsubtitle'            => 'သင်သည် \'\'\'[[:$1]]\'\'\' ကို ရှာဖွေခဲ့သည်။ ([[Special:Prefixindex/$1|"$1" ဖြင့်စသော စာမျက်နှာအားလုံး]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|"$1" ကို လမ်းညွှန်ထားသော စာမျက်နှာအားလုံး]])',
'searchsubtitleinvalid'     => "'''$1''' အတွက် ရှာထားသည်",
'notitlematches'            => 'ဤခေါင်းစဉ်နှင့် ကိုက်ညီသောစာမျက်နှာမရှိပါ',
'notextmatches'             => 'ဤခေါင်းစဉ်နှင့် ကိုက်ညီသောစာမျက်နှာမရှိပါ',
'prevn'                     => 'နောက်သို့ {{PLURAL:$1|$1}}',
'nextn'                     => 'ရှေ့သို့ {{PLURAL:$1|$1}}',
'viewprevnext'              => '($1 {{int:မှ}} $2) အထိကြား ရလဒ် ($3) ခုကို ကြည့်ရန်',
'search-result-size'        => '$1 ({{PLURAL:$2|စကားလုံး 1 လုံး|စကားလုံး $2 လုံး}})',
'search-redirect'           => '($1 သို့ ပြန်ညွှန်းသည်)',
'search-section'            => '(အပိုင်း $1)',
'search-suggest'            => '$1 ဟု ဆိုလိုပါသလား။',
'search-interwiki-caption'  => 'ညီအစ်မ ပရောဂျက်များ',
'search-interwiki-default'  => 'ရလဒ် $1 ခု -',
'search-interwiki-more'     => '(နောက်ထပ်)',
'search-mwsuggest-enabled'  => 'အကြံပြုချက်များနှင့်တကွ',
'search-mwsuggest-disabled' => 'အကြံပြုချက် မရှိပါ',
'search-relatedarticle'     => 'ဆက်နွယ်သော',
'mwsuggest-disable'         => 'AJAX အကြံပြုချက်များကို ပိတ်ထားရန်',
'searcheverything-enable'   => 'အမည်ညွှန်းအားလုံးတွင် ရှာရန်',
'searchrelated'             => 'ဆက်နွယ်သော',
'searchall'                 => 'အားလုံး',
'nonefound'                 => "'''Note''': Only some namespaces are searched by default.
ပုံမှန်အားဖြင့် အမည်ညွှန်းအချို့ကိုသာ ရှာပေးမည်ဖြစ်သည်။
Try prefixing your query with ''all:'' to search all content (including talk pages, templates, etc), or use the desired namespace as prefix.
ရှာရာတွင် ''all:''ကို ရှေ့ဆုံးမှ prefix ထည့်ပြီး ရှာဖွေခြင်းဖြင့် ရှိရှိသမျှ စာမျက်နှာများတွင် (ဆွေးနွေးချက်များ၊ တမ်းပလိတ်များ စသည်) ရှာနိုင်သည်။ သို့မဟုတ် သင်အလိုရှိရာ အမည်ညွှန်းကို prefix ထည့်ပြီး ရှာပါ။",
'powersearch'               => 'အထူးပြု ရှာ​ဖွေ​ရန်​',
'powersearch-legend'        => 'အထူးပြု ရှာဖွေရန်',
'powersearch-ns'            => 'အမည်ညွှန်းတို့တွင် ရှာရန် -',
'powersearch-redir'         => 'ပြန်ညွှန်းသည့် လင့်များကို စာရင်းပြုစုရန်',
'powersearch-field'         => 'ဤအကြောင်းအရာအတွက် ရှာဖွေရန်',
'powersearch-togglelabel'   => 'စစ်ဆေးရန် -',
'powersearch-toggleall'     => 'အားလုံး',
'powersearch-togglenone'    => 'အမည်ညွှန်းမရှိ',
'search-external'           => 'အပြင်တွင် ရှာရန်',

# Quickbar
'qbsettings-none'          => 'အမည်ညွှန်းမရှိ',
'qbsettings-fixedleft'     => 'ဘယ်ဘက်ကို အသေထားရန်',
'qbsettings-fixedright'    => 'ညာဘက်ကို အသေထားရန်',
'qbsettings-floatingleft'  => 'ဘယ်ဘက်ကို အရှင်ထားရန်',
'qbsettings-floatingright' => 'ညာဘက်ကို အရှင်ထားရန်',

# Preferences page
'preferences'               => '​ရွေး​ချယ်​စ​ရာ​များ​',
'mypreferences'             => '​ရွေး​ချယ်​စ​ရာ​များ​',
'prefs-edits'               => 'တည်းဖြတ်မှုအရေအတွက် -',
'prefsnologin'              => 'logged in ဝင်မထားပါ',
'changepassword'            => 'စကားဝှက် ပြောင်းရန်',
'prefs-skin'                => 'အသွင်အပြင်',
'skin-preview'              => 'နမူနာ',
'prefs-math'                => 'သင်္ချာ',
'datedefault'               => 'မရွေးချယ်',
'prefs-datetime'            => 'နေ့စွဲနှင့် အချိန်',
'prefs-personal'            => 'အသုံးပြုသူ ပရိုဖိုင်',
'prefs-rc'                  => 'လတ်​တ​လောအ​ပြောင်း​အ​လဲ​',
'prefs-watchlist'           => 'စောင့်ကြည့်စာရင်း',
'prefs-watchlist-days'      => 'စောင့်ကြည့်စာရင်းတွင် ပြရန်နေ့များ',
'prefs-watchlist-days-max'  => 'အလွန်ဆုံး ၇ ရက်',
'prefs-watchlist-edits'     => 'ချဲ့ထားသော စောင့်ကြည့်စာရင်းတွင် ပြရန် အပြောင်းအလဲတို့၏ အများဆုံး အရေအတွက်',
'prefs-watchlist-edits-max' => 'အများဆုံးအရေအတွက် - ၁ဝဝဝ',
'prefs-watchlist-token'     => 'စောင့်ကြည့်စာရင်း တိုကင် -',
'prefs-misc'                => 'အသေးအမွှား',
'prefs-resetpass'           => 'စကားဝှက် ပြောင်းရန်',
'prefs-email'               => 'အီးမေးအတွက် ရွေးချယ်စရာ',
'prefs-rendering'           => 'ပုံပန်းသွင်ပြင်',
'saveprefs'                 => 'သိမ်းရန်',
'resetprefs'                => 'မသိမ်းရသေးသော အပြောင်းအလဲများကို ရှင်းလင်းရန်',
'restoreprefs'              => 'မူလဆက်တင်များသို့ အားလုံး ပြန်ပြောင်းရန်',
'searchresultshead'         => 'ရှာ​ဖွေ​ရန်​',
'timezoneregion-africa'     => 'အာဖရိက',
'timezoneregion-america'    => 'အမေရိက',
'timezoneregion-antarctica' => 'အန္တာတိက',
'youremail'                 => 'အီ​မေး:',
'username'                  => 'မှတ်​ပုံ​တင်​အ​မည်:',
'uid'                       => 'မှတ်​ပုံ​တင်​ID:',
'yourrealname'              => 'နာမည်ရင်း:',
'yourlanguage'              => 'ဘာသာ:',
'yournick'                  => 'ဆိုင်း:',
'email'                     => 'အီ​မေး​',

# User rights
'userrights-reason' => 'အ​ကြောင်း​ပြ​ချက်:',

# Groups
'group-sysop' => 'အက်ဒမင်များ',
'group-all'   => '(အားလုံး)',

'grouppage-bot'        => 'ဘော့များ',
'grouppage-sysop'      => '{{ns:project}}: အက်ဒမင်များ',
'grouppage-bureaucrat' => 'ဗျူရိုကရက်များ',

# Rights
'right-move'   => 'စာမျက်နှာကိုရွှေ့ပြောင်းပါ',
'right-upload' => 'ဖိုင်တင်ရန်ပါ',
'right-delete' => 'စာမျက်နှာများကိုဖျက်ပါ။',

# User rights log
'rightslog' => 'အသုံးပြုသူ၏ အခွင့်အရေးများ မှတ်တမ်း',

# Associated actions - in the sentence "You do not have permission to X"
'action-edit'          => 'ဤစာမျက်နှာကို ပြင်ရန်',
'action-createpage'    => 'စာမျက်နှာများ စတင်ရေးသားရန်',
'action-createtalk'    => 'ဆွေးနွေးချက်စာမျက်နှာများ စတင်ရေးသားရန်',
'action-createaccount' => 'ဤအသုံးပြုသူအကောင့်ကို ဖန်တီးရန်',
'action-minoredit'     => 'ဤတည်းြဖတ်မှုကို အရေးမကြီးဟု မှတ်သားရန်',
'action-move'          => 'ဤစာမျက်နှာကို ရွှေ့ပြောင်းရန်',

# Recent changes
'nchanges'                       => 'ပြောင်းလဲချက် $1 {{PLURAL:$1|ခု|ခု}}',
'recentchanges'                  => 'လတ်​တ​လောအ​ပြောင်း​အ​လဲ​',
'recentchanges-legend'           => 'လတ်တလောအပြောင်းအလဲများအတွက် ရွေးချယ်စရာများ',
'recentchanges-feed-description' => 'ဤ feed ထဲတွင် ဝီကီ၏ လတ်တလောပြောင်းလဲမှုများကို နောက်ကြောင်းခံလိုက်ရန်',
'rcnote'                         => "အောက်ပါတို့သည် ပြီးခဲ့သော {{PLURAL:$2|ရက်|'''$2''' ရက်}}, $5, $4 ရက်စွဲအရ နောက်ဆုံး{{PLURAL:$1|ပြောင်းလဲမှု '''တစ်''' ခု|ပြောင်းလဲမှု '''$1''' ခု}}ဖြစ်သည်။",
'rcnotefrom'                     => "အောက်ပါတို့သည် '''$2''' ကတည်းက အ​ပြောင်းအလဲများ ြဖစ်သည် ('''$1''' ခု ြပထားသည်)။",
'rclistfrom'                     => '$1 မှစသော အပြောင်းအလဲအသစ်များကို ပြရန်',
'rcshowhideminor'                => 'အရေးမကြီးသော ပြင်ဆင်မှု $1ရန်',
'rcshowhidebots'                 => 'ဘော့ $1ရန်',
'rcshowhideliu'                  => 'logged-in ဝင်နေသော အသုံးပြုသူ $1ရန်',
'rcshowhideanons'                => 'အမည်မသိ အသုံးပြုသူ $1ရန်',
'rcshowhidepatr'                 => 'စောင့်ြကပ်တည်းဖြတ်မှု $1ရန်',
'rcshowhidemine'                 => 'ကျွနု်ပ်တည်းဖြတ်ထားသည်များ $1ရန်',
'rclinks'                        => '$2 ရက်အတွင်းမှ နောက်ဆုံးပြင်ဆင်ချက် $1 ခုကို ပြရန်</br> $3',
'diff'                           => 'ကွဲပြားမှု',
'hist'                           => 'မှတ်တမ်း',
'hide'                           => 'ဝှက်',
'show'                           => 'ပြ',
'minoreditletter'                => 'အသေး',
'newpageletter'                  => 'အသစ်',
'boteditletter'                  => 'ဘော့',
'rc-enhanced-expand'             => 'အသေးစိတ် ပြရန် (JavaScript လိုအပ်သည်)',
'rc-enhanced-hide'               => 'အသေးစိတ် မပြရန်',

# Recent changes linked
'recentchangeslinked'         => 'ဆက်​စပ်သော​ အ​ပြောင်း​အ​လဲ​များ​',
'recentchangeslinked-feed'    => 'ဆက်စပ်သော ​အ​ပြောင်း​အ​လဲ​များ​',
'recentchangeslinked-toolbox' => 'ဆက်​စပ်​သော​အ​ပြောင်း​အ​လဲ​များ​',
'recentchangeslinked-title'   => '"$1" နှင့် ဆက်စပ်သော အပြောင်းအလဲများ',
'recentchangeslinked-summary' => 'ဤသည်မှာ သီးသန့်ပြထားသော စာမျက်နှာ (သို့ သီးသန့်ကဏ္ဍများ) မှ ညွှန်းထားသော စာမျက်နှာများ၏ လတ်တလော ပြောင်းလဲမှုများ၏ စာရင်းဖြစ်သည်။ [[Special:Watchlist|စောင့်ကြည့်စာရင်း]] မှ စာမျက်နှာများကို စာလုံးမည်းဖြင့် ပြထားသည်။',
'recentchangeslinked-page'    => 'စာမျက်နှာ အမည် -',
'recentchangeslinked-to'      => 'ပေးထားသော စာမျက်နှာများအစား လင့်များနှင့် ဆက်စပ်နေသာ စာမျက်နှာများ၏ အပြောင်းအလဲများကို ပြရန်',

# Upload
'upload'            => 'ဖိုင်​တင်​ရန်​',
'uploadbtn'         => 'ဖိုင်​တင်​ရန်​',
'uploadnologin'     => 'logged in ဝင်မထားပါ',
'uploadlogpage'     => 'Upload တင်သည့် မှတ်တမ်း',
'filename'          => 'ဖိုင်အမည်',
'filedesc'          => 'အ​ကျဉ်း​ချုပ်​',
'fileuploadsummary' => 'အ​ကျဉ်း​ချုပ်:',
'uploadedimage'     => '"[[$1]]" ပုံကို တင်ပြီးပြီဖြစ်သည်',
'watchthisupload'   => 'ဤဖိုင်အား စောင့်ကြည့်ရန်',

# Special:ListFiles
'imgfile'        => 'ဖိုင်',
'listfiles'      => 'ဖိုင်စာရင်း',
'listfiles_date' => 'နေ့စွဲ',

# File description page
'file-anchor-link'          => 'ဖိုင်',
'filehist'                  => 'ဖိုင်မှတ်တမ်း',
'filehist-help'             => 'ဖိုင်ကို ယင်းနေ့စွဲ အတိုင်း မြင်နိုင်ရန် နေ့စွဲ/အချိန် တစ်ခုခုပေါ်တွင် ကလစ်နှိပ်ပါ။',
'filehist-deleteall'        => 'အားလုံးဖျက်',
'filehist-deleteone'        => 'ဖျက်',
'filehist-revert'           => 'ပြန်ပြောင်းရန်',
'filehist-current'          => 'ကာလပေါ်',
'filehist-datetime'         => 'နေ့စွဲ/အချိန်',
'filehist-thumb'            => 'နမူနာပုံငယ်',
'filehist-thumbtext'        => '$1 ရက်က မူအတွက် နမူနာပုံငယ်',
'filehist-nothumb'          => 'နမူနာပုံငယ်မပါ',
'filehist-user'             => 'အသုံးပြုသူ',
'filehist-dimensions'       => 'မှတ်တမ်း ဒိုင်မန်းရှင်းများ',
'filehist-filesize'         => 'ဖိုင်ဆိုက်',
'filehist-comment'          => 'မှတ်ချက်',
'filehist-missing'          => 'ဖိုင်ပျောက်နေသည်',
'imagelinks'                => 'ဖိုင်အဆက်အသွယ်များ',
'linkstoimage'              => 'ဤဖိုင်သို့ အောက်ပါ {{PLURAL:$1|စာမျက်နှာလင့်|စာမျက်နှာလင့် $1 ခု}} -',
'nolinkstoimage'            => 'ဤဖိုင်သို့လင့်ထားသော စာမျက်နှာမရှိပါ။',
'morelinkstoimage'          => 'ဤဖိုင်သို့[[Special:WhatLinksHere/$1|နောက်ထပ်လင့်များ]] ကိုကြည့်ပါ။',
'sharedupload'              => 'ဤဖိုင်သည် $1 မှဖြစ်ပြီး အခြားပရောဂျက်များတွင် သုံးကောင်းသုံးလိမ့်မည်။',
'filepage-nofile'           => 'ဤအမည်ဖြင့် မည်သည့်ဖိုင်မှ မရှိပါ။',
'filepage-nofile-link'      => 'ဤအမည်ဖြင့် မည်သည့်ဖိုင်မှ မရှိပါ။ သိုရာတွင် ယင်းကို [$1 upload တင်]နိုင်သည်။',
'uploadnewversion-linktext' => 'ဤဖိုင်၏ နောက်ဆုံး version ကို upload တင်ရန်',
'shared-repo-from'          => '$1 ထံမှ',

# File reversion
'filerevert'                => '$1 ကို ပြန်ပြောင်းရန်',
'filerevert-legend'         => 'ဖိုင်ကို ပြန်ပြောင်းရန်',
'filerevert-comment'        => 'အ​ကြောင်း​ပြ​ချက် -',
'filerevert-defaultcomment' => '$2 ရက်နေ့ $1 အချိန်မှ မူသို့ ပြန်ပြောင်းရန်',
'filerevert-submit'         => 'ပြောင်းရန်',

# File deletion
'filedelete'                  => '$1 ကိုဖျက်ပါ',
'filedelete-legend'           => 'ဖိုင်ကိုဖျက်ပါ',
'filedelete-comment'          => 'အ​ကြောင်း​ပြ​ချက်:',
'filedelete-submit'           => 'ဖျက်',
'filedelete-otherreason'      => 'အခြားသော/နောက်ထပ် အကြောင်းပြချက် -',
'filedelete-reason-otherlist' => 'အခြား အကြောင်းပြချက်',

# Unused templates
'unusedtemplateswlh' => 'အခြားလိပ်စာများ',

# Random page
'randompage' => 'ကျ​ပန်း​စာ​မျက်​နှာ​',

# Random redirect
'randomredirect' => 'ကျပန်းပြန်ညွှန်း',

# Statistics
'statistics' => 'စာရင်းအင်း',

'brokenredirects-edit'   => 'ပြင်​ဆင်​ရန်',
'brokenredirects-delete' => 'ဖျက်​ပါ',

'withoutinterwiki-submit' => 'ပြ',

# Miscellaneous special pages
'nbytes'            => '$1 {{PLURAL:$1|ဘိုက်|ဘိုက်}}',
'nmembers'          => '$1 {{PLURAL:$1|မန်ဘာ|မန်ဘာ}} ယောက်',
'prefixindex'       => 'ရှေ့ဆုံးမှ prefix ပါသော စာမျက်နှာ အားလုံး',
'shortpages'        => 'စာမျက်နှာတို',
'newpages'          => 'စာမျက်နှာအသစ်',
'newpages-username' => 'မှတ်​ပုံ​တင်​အ​မည်:',
'ancientpages'      => 'အဟောင်းဆုံးစာမျက်နှာ',
'move'              => 'ရွှေ့ရန်',
'movethispage'      => 'ဤစာမျက်နှာကို ရွှေ့ပြောင်းရန်',
'pager-newer-n'     => '{{PLURAL:$1|ပိုသစ်သော တစ်ခု|ပိုသစ်သော $1 ခု}}',
'pager-older-n'     => '{{PLURAL:$1|ပိုဟောင်းသော တစ်ခု|ပိုဟောင်းသော $1 ခု}}',

# Book sources
'booksources'               => 'မှီငြမ်း စာအုပ်များ',
'booksources-search-legend' => 'စာအုပ်ရင်းမြစ်များကို ရှာရန်',
'booksources-go'            => 'သွား​ပါ​',

# Special:Log
'log' => 'မှတ်​တမ်း​များ​',

# Special:AllPages
'allpages'       => 'စာမျက်နှာအားလုံး',
'alphaindexline' => '$1 မှ $2 အထိ',
'prevpage'       => 'ယခင် စာမျက်နှာ ($1)',
'allpagesfrom'   => 'ဤမှစသော စာမျက်နှာများကို ပြနေသည် -',
'allpagesto'     => 'ဤသည်တွင်ဆုံးသော စာမျက်နှာများကို ပြရန် -',
'allarticles'    => 'စာမျက်နှာအားလုံး',
'allpagessubmit' => 'သွား​ပါ​',

# Special:LinkSearch
'linksearch' => 'ပြင်ပ လင့်များ',

# Special:ListUsers
'listusers-submit' => 'ပြ',

# Special:Log/newusers
'newuserlogpage'          => 'အသုံးပြုသူအသစ်ရောက်လာခြင်း မှတ်တမ်း',
'newuserlog-create-entry' => 'အသုံးပြုသူအသစ်',

# Special:ListGroupRights
'listgrouprights-members' => '(မန်ဘာ စာရင်း)',

# E-mail user
'emailuser' => 'ဤ​အ​သုံး​ပြု​သူ​အား​အီး​မေး​ပို့​ပါ​',
'emailsend' => 'ပို့',

# Watchlist
'watchlist'         => 'စောင့်ကြည့်စာရင်း',
'mywatchlist'       => 'စောင့်ကြည့်စာရင်း',
'addedwatch'        => 'စောင့်ကြည့်စာရင်းသို့ ပေါင်းထည့်ပြီး',
'addedwatchtext'    => '"[[:$1]]" စာမျက်နှာကို [[Special:Watchlist|စောင့်ကြည့်စာရင်း]]ထဲ ပေါင်းထည့်ပြီးဖြစ်သည်။ နောက်ပိုင်းအပြောင်းအလဲများနှင့် ၎င်းနှင့် ဆက်နွယ်နေသော ဆွေးနွေးချက် စာမျက်နှာကို ယင်းနေရာတွင် စာရင်းပြုစုထားမည် ဖြစ်သည်။ ရွေးချယ်ရ လွယ်ကူစေရန် စာမျက်နှာသည် [[Special:RecentChanges|လတ်တလော အပြောင်းအလဲများစာရင်း]]တွင် စာလုံးမည်းဖြင့် ပေါ်လာမည်ဖြစ်သည်။',
'removedwatch'      => 'စောင့်ကြည့်စာရင်းမှ ဖယ်ရန်',
'removedwatchtext'  => '"[[:$1]]" စာမျက်နှာကို [[Special:Watchlist|စောင့်ကြည့်စာရင်း]] မှ ဖယ်ထုတ်ပြီး ဖြစ်သည်။',
'watch'             => 'စောင့်ကြည့်ရန်',
'watchthispage'     => 'ဤစာမျက်နှာကို စောင့်ကြည့်ရန်',
'unwatch'           => 'ဆက်လက် စောင့်မကြည့်တော့ရန်',
'watchlist-details' => '{{PLURAL:$1|စာမျက်နှာ $1 ခု|စာမျက်နှာ $1 ခု}} သည် သင့်စောင့်ကြည့်စာရင်းတွင် ရှိသည်။ ဆွေးနွေးချက်စာမျက်နှာများကို ထည့်တွက် မထားပါ။',
'wlshowlast'        => 'နောက်ဆုံး $1 နာရီ $2 ရက် $3 ကိုပြရန်',
'watchlist-options' => 'စောင့်ကြည့်စာရင်းအတွက် ရွေးချယ်စရာများ',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'စောင့်ကြည့်လျက်ရှိ...',
'unwatching' => 'စောင့်မကြည့်တော့...',

# Delete
'deletepage'            => 'စာမျက်နှာကိုဖျက်ပါ',
'confirm'               => 'အတည်ပြု',
'delete-confirm'        => '"$1"ကို ဖျက်ပါ',
'delete-legend'         => 'ဖျက်',
'confirmdeletetext'     => '↓ သင်သည် စာမျက်နှာတစ်ခုကို ယင်း၏ မှတ်တမ်းများနှင့်တကွ ဖျက်ပစ်တော့မည် ဖြစ်သည်။
ဤသို့ ဖျက်ပစ်ရန် သင် အမှန်တကယ် ရည်ရွယ်လျက်  နောက်ဆက်တွဲ အကျိုးဆက်များကို သိရှိနားလည်ပြီး [[{{MediaWiki:Policy-url}}|မူဝါဒ]] အတိုင်းလုပ်ဆောင်နေခြင်းဖြစ်ကြောင်းကို အတည်ပြုပေးပါ။',
'actioncomplete'        => 'လုပ်ဆောင်ချက် ပြီးပြီ',
'deletedtext'           => '"<nowiki>$1</nowiki>" ကို ဖျက်ပစ်လိုက်ပြီးဖြစ်သည်။
လတ်တလောဖျက်ထားသည်များ၏ မှတ်တမ်းကို $2 တွင် ကြည့်ပါ။',
'deletedarticle'        => '[[$1]] ကို ဖျက်လိုက်သည်',
'dellogpage'            => 'ဖျက်ထားသည်များ မှတ်တမ်း',
'deletecomment'         => 'အ​ကြောင်း​ပြ​ချက် -',
'deleteotherreason'     => 'အခြားသော/နောက်ထပ် အကြောင်းပြချက် -',
'deletereasonotherlist' => 'အခြား အကြောင်းပြချက်',

# Rollback
'rollbacklink' => 'နောက်ပြန် ပြန်သွားရန်',

# Protect
'protectlogpage'              => 'ကာကွယ်မှုများ၏ မှတ်တမ်း',
'protectedarticle'            => '"[[$1]]" ကို ကာကွယ်ထားသည်',
'modifiedarticleprotection'   => '"[[$1]]" ၏ ကာကွယ်မှု အဆင့်ကို ပြောင်းရန်',
'prot_1movedto2'              => '[[$1]]  မှ​ [[$2]] သို့​',
'protectcomment'              => 'အ​ကြောင်း​ပြ​ချက်:',
'protectexpiry'               => 'သက်တမ်းကုန်လွန်မည် -',
'protect_expiry_invalid'      => 'သက်တမ်းကုန်လွန်မည့် အချိန်သည် တရားမဝင်ပါ။',
'protect_expiry_old'          => 'သက်တမ်းသည် အတိတ်ကာလတွင် ကုန်လွန်ခဲ့ပြီး ဖြစ်သည်။',
'protect-text'                => "'''<nowiki>$1</nowiki>''' စာမျက်နှာအတွက် ကာကွယ်မှုအဆင့်ကို ဤနေရာတွင် ကြည့်ရှုပြောင်းလဲနိုင်သည်။",
'protect-locked-access'       => "သင့်အကောင့်သည် စာမျက်နှာ၏ ကာကွယ်မှုအဆင့်ကို ပြောင်းလဲနိုင်ရန် ခွင့်ပြုချက် မရှိပါ။
ဤသည်မှာ '''$1''' စာမျက်နှာအတွက် လက်ရှိ settings သတ်မှတ်ချက်များ ဖြစ်သည်။",
'protect-cascadeon'           => 'ပြန်စီစဉ်ခြင်း cascading ကို ကာကွယ်ထားသော အောက်ပါ{{PLURAL:$1|စာမျက်နှာ|စာမျက်နှာများ}} ပါဝင်နေသောကြောင့် ဤစာမျက်နှာကို လက်ရှိတွင် ကာကွယ်ထားသည်။
ဤစာမျက်နှာ၏ ကာကွယ်မှုအဆင့်ကို ပြောင်းလဲသော်လည်း ပြန်စီစဉ်ခြင်းကို အကျိုးသက်ရောက်လိမ့်မည် မဟုတ်။',
'protect-default'             => 'အသုံးပြုသူ အားလုံးကို ခွင့်ပြုရန်',
'protect-fallback'            => '"$1" ခွင့်ပြုချက် လိုအပ်သည်',
'protect-level-autoconfirmed' => 'အသုံးပြုသူ အသစ်နှင့် မှတ်ပုံမတင်ရသေးသူများကို ပိတ်ပင်တားဆီးထားရန်',
'protect-level-sysop'         => 'အက်ဒမင်များသာ',
'protect-summary-cascade'     => 'အစီအစဥ်ကျအောင် စီနေသည်',
'protect-expiring'            => '$1 (UTC) တွင် သက်တမ်းကုန်မည်',
'protect-cascade'             => 'ဤစာမျက်နှာထဲတွင်ပါသော စာမျက်နှာများကို ကာကွယ်ထားရန် (စီစဥ်ခြင်းကို တားဆီးခြင်း)',
'protect-cantedit'            => 'ကာကွယ်ထားသောစာမျက်နှာဖြစ်သည့်အတွက် ပြင်ဆင်၍ မရနိုင်ပါ။ အဘယ့်ကြောင့်ဆိုသော် သင့်မှာ တည်းဖြတ်ပိုင်ခွင့် မရှိ၍ ဖြစ်ပါသည်။',
'protect-otherreason'         => 'အခြားသော/နောက်ထပ် အကြောင်းပြချက် -',
'protect-otherreason-op'      => 'အခြား အကြောင်းပြချက်',
'protect-expiry-options'      => '၁ နာရီ:1 hour,၁ နေ့:1 day,၁ ပတ်:1 week,၂ ပတ်:2 weeks,၁ လ:1 month,၃ လ:3 months,၆ လ:6 months,၁ နှစ်:1 year,အနန္တ:infinite',
'restriction-type'            => 'ခွင့်ပြုချက် -',
'restriction-level'           => 'ကန့်သတ်ထားသော level:',

# Restrictions (nouns)
'restriction-edit'   => 'ပြင်​ဆင်​ရန်​',
'restriction-move'   => 'ရွှေ့ရန်',
'restriction-create' => 'ထွင်',

# Undelete
'undelete-nodiff'        => 'မည်သည့် ယခင်မူကိုမှ မတွေ့ပါ။',
'undeletebtn'            => 'ပြန်လည် ထိန်းသိမ်းရန်',
'undeletelink'           => 'စောင့်ကြည့်ရန်/ပြန်လည်ထိန်းသိမ်းရန်',
'undeleteviewlink'       => 'ကြည့်ရန်',
'undeletereset'          => 'Reset ချရန်',
'undeleteinvert'         => 'selection ကို ပြောင်းပြန်လှန်ရန်',
'undeletecomment'        => 'အ​ကြောင်း​ပြ​ချက် -',
'undeletedarticle'       => '"[[$1]]" ကို ပြန်လည် ပြောင်းလဲလိုက်ပြီး',
'undeletedrevisions'     => '{{PLURAL:$1|မူတစ်ခု|မူ $1 ခု}} ကိုပြန်လည် ထိန်းသိမ်းပြီး',
'undelete-search-submit' => 'ရှာ​ဖွေ​ရန်​',

# Namespace form on various pages
'namespace'      => 'အမည်ညွှန်း -',
'invert'         => 'selection ကို ပြောင်းပြန်လှန်ရန်',
'blanknamespace' => '(ပင်မ)',

# Contributions
'contributions'       => 'အသုံးပြုသူတို့၏ ပံ့ပိုးပေးမှုများ',
'contributions-title' => '$1 အတွက် အသုံးပြုသူ၏ ပံ့ပိုးမှုများ',
'mycontris'           => 'ပံ့ပိုးထားမှုများ',
'contribsub2'         => '$1အတွက် ($2)',
'uctop'               => '(ထိပ်)',
'month'               => 'အဆိုပါ လမှစ၍ ( အဆိုပါလထက်လည်း စောသော) :',
'year'                => 'အဆိုပါ နှစ်မှစ၍ ( အဆိုပါနှစ်ထက်လည်း စောသော) :',

'sp-contributions-newbies'  => 'အကောင့်အသစ်များ၏ ပံ့ပိုးမှုများကိုသာ ပြရန်',
'sp-contributions-blocklog' => 'ပိတ်ပင်တားဆီးမှု မှတ်တမ်း',
'sp-contributions-search'   => 'ပံ့ပိုးမှုများကို ရှာရန်',
'sp-contributions-username' => 'IP address သို့ -',
'sp-contributions-submit'   => 'ရှာ​ဖွေ​ရန်​',

# What links here
'whatlinkshere'            => 'ဒီမှာ ဘာလင့်တွေရှိလဲ',
'whatlinkshere-title'      => '"$1" ကို လင့်ထားသော စာမျက်နှာများ',
'whatlinkshere-page'       => 'စာမျက်နှာ -',
'linkshere'                => "အောက်ပါစာမျက်နှာများသည် '''[[:$1]]''' သို့ လင့်ထားသည် -",
'isredirect'               => 'ပြန်ညွှန်းသော စာမျက်နှာ',
'istemplate'               => 'ထည့်သွင်းကူးယူချက်',
'isimage'                  => 'ပုံလင့်',
'whatlinkshere-prev'       => '{{PLURAL:$1|နောက်သို့|နောက်သို့ $1}}',
'whatlinkshere-next'       => '{{PLURAL:$1|ရှေ့သို့|ရှေ့သို့ $1}}',
'whatlinkshere-links'      => '← လင့်များ',
'whatlinkshere-hideredirs' => 'redirect ပြန်ညွှန်း $1 ခု',
'whatlinkshere-hidetrans'  => 'ထည့်သွင်းကူးယူချက် $1 ခု',
'whatlinkshere-hidelinks'  => 'လင့် $1 ခု',
'whatlinkshere-hideimages' => 'ပုံလင့် $1 ခု',
'whatlinkshere-filters'    => 'စိစစ်မှုများ',

# Block/unblock
'blockip'                  => 'အသုံးပြုသူကို ပိတ်ပင်ရန်',
'blockip-title'            => 'အသုံးပြုသူကို ပိတ်ပင်ရန်',
'blockip-legend'           => 'အသုံးပြုသူကို ပိတ်ပင်ရန်',
'ipaddress'                => 'အိုင်ပီလိပ်စာ -',
'ipadressorusername'       => 'အိုင်ပီလိပ်စာ သို့ အသုံးပြုသူအမည် -',
'ipbreason'                => 'အ​ကြောင်း​ပြ​ချက်:',
'ipbanononly'              => 'အမည်မသိ အသုံးပြုသူများကိုသာ ပိတ်ပင်ရန်',
'ipbcreateaccount'         => 'အကောင့်အသစ်ပြုလုပ်ခြင်းကို တားဆီးရန်',
'ipbemailban'              => 'အီးမေးပို့ခြင်းမှ အသုံးပြုသူကို တားဆီးရန်',
'ipbother'                 => 'အခြားအချိန်:',
'ipboptions'               => '၂ နာရီ:2 hours,၁ ရက်:1 day,၃ ရက်:3 days,၁ ပတ်:1 week,၂ ပတ်:2 weeks,၁ လ:1 month,၃ လ:3 months,၆ လ:6 months,၁ နှစ်:1 year,အနန္တ:infinite',
'ipbotheroption'           => 'အခြား',
'ipbotherreason'           => 'အခြားသော/နောက်ထပ် အကြောင်းပြချက် -',
'ipblocklist'              => 'IP addresses နှင့် အသုံးပြုသူအမည်ကို ပိတ်ပင်တားဆီးထားသည်။',
'ipblocklist-submit'       => 'ရှာ​ဖွေ​ရန်​',
'expiringblock'            => '$1 ရက် $2 အချိန်တွင် သက်တမ်းကုန်မည်',
'blocklink'                => 'တားဆီးကန့်သတ်ရန်',
'unblocklink'              => 'လင့် ပြန်ဖြေရန်',
'change-blocklink'         => 'စာကြောင်းအမည် ပြောင်းရန်',
'contribslink'             => 'ပံ့ပိုး',
'blocklogpage'             => 'ပိတ်ပင်တားဆီးမှု မှတ်တမ်း',
'blocklogentry'            => '[[$1]] ကို $2 ကြာအောင် ပိတ်ပင် တားဆီးလိုက်သည် $3',
'unblocklogentry'          => '$1 ကို ပိတ်ထားရာမှ ပြန်ဖွင့်ရန်',
'block-log-flags-nocreate' => 'အကောင့်ဖန်တီးခြင်းကို ပိတ်ထားသည်',

# Move page
'move-page-legend' => 'စာ​မျက်​နှာ​ကို ရွှေ့ပြောင်းရန်',
'movepagetext'     => "အောက်ပါပုံစံကို အသုံးပြုပါက စာမျက်နှာကို အမည်ပြောင်းလဲပေးမည် ဖြစ်ပြီး အမည်သစ်သို့ ယင်း၏ မှတ်တမ်းနှင့်တကွ ရွှေ့ပေးမည် ဖြစ်သည်။
အမည်ဟောင်းသည် အမည်သစ်သို့ ပြန်ညွှန်းပေးမည် ဖြစ်သည်။
သင်သည် မူလခေါင်းစဉ်သို့ ပြန်ညွှန်းများကို အလိုအလျောက် အပ်ဒိတ် update လုပ်နိုင်သည်။
အကယ်၍ မပြုလုပ်လိုပါက [[Special:DoubleRedirects|နှစ်ခါထပ်]][[Special:BrokenRedirects|ပြန်ညွှန်း အပျက်များ]] ကို မှတ်သားရန် မမေ့ပါနှင့်။
လင့်များ ညွှန်းလိုသည့် နေရာသို့ ညွှန်ပြနေရန် သင့်တွင် တာဝန် ရှိသည်။

အကယ်၍ ခေါင်းစဉ်အသစ်တွင် စာမျက်နှာတစ်ခု ရှိနှင့်ပြီး ဖြစ်ပါက (သို့) ယင်းစာမျက်နှာသည် အလွတ်မဖြစ်ပါက (သို့) ပြန်ညွှန်းတစ်ခု မရှိပါက (သို့) ယခင်က ပြုပြင်ထားသော မှတ်တမ်း မရှိပါက စာမျက်နှာသည် '''ရွေ့မည်မဟုတ်''' သည်ကို သတိပြုပါ။ 
ဆိုလိုသည်မှာ သင်သည် အမှားတစ်ခု ပြုလုပ်မိပါက စာမျက်နှာကို ယခင်အမည်ကို ပြန်လည် ပြောင်းလဲပေးနိုင်သည်။ ရှိပြီသားစာမျက်နှာတစ်ခုကို စာမျက်နှာ အသစ်နှင့် ပြန်အုပ် overwrite ခြင်း မပြုနိုင်။

'''သတိပေးချက်!'''
ဤသည်မှာ လူဖတ်များသော စာမျက်နှာတစ်ခုဖြစ်ပါက မမျှော်လင့်ထားသော၊ ကြီးမားသော အပြောင်းအလဲတစ်ခု ဖြစ်ပေါ်လာနိုင်သည်။
ထို့ကြောင့် ဆက်လက် မဆောင်ရွက်မီ သင်သည် နောက်ဆက်တွဲ အကျိုးဆက်များကို နားလည်ကြောင်း ကျေးဇူးပြု၍ သေချာပါစေ။",
'movepagetalktext' => "↓ ဆက်နွယ်နေသော ဆွေးနွေးချက် စာမျက်နှာကို '''အောက်ပါအကြောင်းများ မရှိခဲ့ပါက''' အလိုအလျောက် ရွှေ့ပစ်မည် ဖြစ်သည်။
*အကယ်၍ ဗလာမဟုတ်သော ဆွေးနွေးချက်စာမျက်နှာသည် အမည်အသစ်အောက်တွင် ရှိနှင့်ပြီး ဖြစ်ခြင်း (သို့)
*အောက်ပါ သေတ္တာငယ် box ကို မှတ်သားခြင်း။

ဤကိစ္စမျိုး ကြုံလာခဲ့ပါက သင် ဆန္ဒရှိလျှင် စာမျက်နှာကို မိမိကိုယ်တိုင် သွားရောက်ရွှေ့ပြောင်း ပေါင်းစပ်နိုင်သည်။",
'movearticle'      => 'စာ​မျက်​နှာ​ကို ရွှေ့ပြောင်းရန် -',
'movenologin'      => 'logged in ဝင်မထားပါ',
'newtitle'         => 'ခေါင်းစဉ်အသစ်သို့:',
'move-watch'       => 'မူရင်းစာမျက်နှာနှင့် ဦးတည်ထားသော စာမျက်နှာတို့ကို စောင့်ကြည့်ရန်',
'movepagebtn'      => 'စာ​မျက်​နှာ​ကို ရွှေ့ပြောင်းရန်',
'pagemovedsub'     => 'ပြောင်းရွှေ့ခြင်းအောင်မြင်သည်',
'movepage-moved'   => '\'\'\'"$1" ကို "$2" သို့ ရွှေ့ပြီးဖြစ်သည်\'\'\'',
'articleexists'    => 'ထိုအမည်ဖြင့် စာမျက်နှာတစ်ခု ရှိနှင့်ပြီးဖြစ်သည် (သို့) သင်ရွေးလိုက်သော အမည်သည် တရားမဝင်ပါ။
ကျေးဇူးပြု၍ အခြားအမည်တစ်ခုကို ရွေးပေးပါ။',
'talkexists'       => "'''စာမျက်နှာကို အောင်မြင်စွာ ရွှေ့ပြီးဖြစ်သည်။ သို့သော် ဆွေးနွေးချက် စာမျက်နှာကိုမူ ရွေ့မရနိုင်ပါ။ အကြောင်းမှာ ခေါင်းစဥ်အသစ်တွင် ရှိပြီးဖြစ်သောကြောင့် ဖြစ်သည်။
ကျေးဇူးပြု၍ ယင်းတို့ကို မိမိဘာသာ ပြန်ပေါင်းပေးပါ။'''",
'movedto'          => 'ရွေ့​ပြောင်း​ရန်​နေ​ရာ​',
'movetalk'         => 'ယှက်နွယ်နေသော ဆွေးနွေးချက်စာမျက်နှာများကို ရွှေ့ရန်',
'1movedto2'        => '[[$1]]  မှ​ [[$2]] သို့​',
'1movedto2_redir'  => 'redirect ပြန်ညွှန်းရာတွင် [[$1]] မှ [[$2]] သို့ ရွှေ့ပြီးဖြစ်သည်',
'movelogpage'      => 'မှတ်တမ်းရွှေ့ရန်',
'movereason'       => 'အ​ကြောင်း​ပြ​ချက် -',
'revertmove'       => 'ပြောင်းရန်',

# Export
'export' => 'စာမျက်နှာများကို Export ထုတ်ရန်',

# Namespace 8 related
'allmessages'     => 'စ​နစ်​၏​သ​တင်း​များ​',
'allmessagesname' => 'အမည်',

# Thumbnails
'thumbnail-more' => 'ပုံကြီးချဲ့ရန်',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'ကိုယ်ပိုင်စာမျက်နှာ',
'tooltip-pt-mytalk'               => 'ကျွနု်ပ်၏ ဆွေးနွေးချက်များ',
'tooltip-pt-preferences'          => 'ကျွန်​တော့​ရွေး​ချယ်​စ​ရာ​များ​',
'tooltip-pt-watchlist'            => 'အပြောင်းအလဲများအတွက် စောင့်ကြည့်နေသော စာမျက်နှာများ၏ စာရင်း',
'tooltip-pt-mycontris'            => 'သင့်ပံ့ပိုးမှုများ၏ စာရင်း',
'tooltip-pt-login'                => 'မှတ်ပုံတင်ဖြင့် log in ဝင်ရန် အားပေးပါသည်။ သို့သော် မှတ်ပုံမတင်မနေရ မဟုတ်ပါ။',
'tooltip-pt-anonlogin'            => 'မှတ်ပုံတင်ဖြင့် log in ဝင်ရန် အားပေးပါသည်။ သို့သော် မှတ်ပုံမတင်မနေရ မဟုတ်ပါ။',
'tooltip-pt-logout'               => 'ထွက်​ပါ​',
'tooltip-ca-talk'                 => 'မာတိကာ စာမျက်နှာအတွက် ဆွေးနွေးချက်များ',
'tooltip-ca-edit'                 => 'ဤစာမျက်နှာကို တည်းဖြတ်နိုင်သည်။ ကျေးဇူးပြု၍ မသိမ်းခင် နမူနာ ခလုတ်ကိုနှိပ်ပြီး ကြည့်ပေးပါ။',
'tooltip-ca-addsection'           => 'အပိုင်းသစ်တစ်ခု စရန်',
'tooltip-ca-viewsource'           => 'ဤစာမျက်နှာကို တည်းဖြတ်ခြင်းမှ တားဆီးထားသည်။
ရင်းမြစ် စာသားများကို ကြည့်ရှုနိုင်သည်။',
'tooltip-ca-history'              => 'ဤစာမျက်နှာ၏ ယခင်မူများ',
'tooltip-ca-protect'              => 'ဤစာမျက်နှာကို ထိန်းသိမ်းပါ',
'tooltip-ca-unprotect'            => 'ဤစာမျက်နှာကို မကာကွယ်တော့ရန်',
'tooltip-ca-delete'               => 'ဤစာမျက်နှာဖျက်ပါ',
'tooltip-ca-move'                 => 'ဤစာမျက်နှာကို ရွှေ့ပြောင်းရန်',
'tooltip-ca-watch'                => 'ဤစာမျက်နှာကို စောင့်ကြည့်စာရင်းသို့ ထည့်ရန်',
'tooltip-ca-unwatch'              => 'ဤစာမျက်နှာကို စောင့်ကြည့်စာရင်းမှ ဖြုတ်ရန်',
'tooltip-search'                  => '{{SITENAME}} ကို ရှာရန်',
'tooltip-search-go'               => 'ဤအမည်နှင့် ထပ်တူညီသော စာမျက်နှာရှိပါက ယင်းသို့ သွားရန်',
'tooltip-search-fulltext'         => 'ဤစာလုံးများပါသော စာမျက်နှာကို ရှာရန်',
'tooltip-p-logo'                  => 'ဗဟိုစာမျက်နှာသို့ သွားရန်',
'tooltip-n-mainpage'              => 'ဗဟိုစာမျက်နှာသို့ သွားရန်',
'tooltip-n-mainpage-description'  => 'ဗဟိုစာမျက်နှာသို့ သွားရန်',
'tooltip-n-portal'                => 'ပရောဂျက်အကြောင်း၊ သင်ဘာလုပ်ပေးနိုင်သည် နှင့် ဘယ်နေရာတွင် ရှာဖွေရန်များ',
'tooltip-n-currentevents'         => 'လက်ရှိ အဖြစ်အပျက်များမှ နောက်ခံ အချက်အလက်များကို ရှာရန်',
'tooltip-n-recentchanges'         => 'ဝီကီမှ လတ်တလောအပြောင်းအလဲများ စာရင်း',
'tooltip-n-randompage'            => 'ကျပန်းစာမျက်နှာ ပြရန်',
'tooltip-n-help'                  => 'ရှာဖွေဖော်ထုတ်ရန်နေရာ',
'tooltip-t-whatlinkshere'         => 'ဤနေရာသို့ လမ်းညွှန် လင့်ထားသည့် ဝီကီစာမျက်နှာများ၏ စာရင်း',
'tooltip-t-recentchangeslinked'   => 'ဤစာမျက်နှာမှ ညွှန်းထားသည့် စာမျက်နှာများမှ လတ်တလော အပြောင်းအလဲများ',
'tooltip-feed-rss'                => 'ဤစာမျက်နှာအတွက် RSS feed',
'tooltip-feed-atom'               => 'ဤစာမျက်နှာအတွက် Atom feed',
'tooltip-t-contributions'         => 'ဤအသုံးပြုသူ၏ ပံ့ပိုးပေးမှုများကို ကြည့်ရန်',
'tooltip-t-emailuser'             => 'ဤအသုံးပြုသူထံ အီးမေး ပို့ရန်',
'tooltip-t-upload'                => 'ဖိုင်တင်ရန်',
'tooltip-t-specialpages'          => 'အထူး စာမျက်နှာ စာရင်းများ',
'tooltip-t-print'                 => 'ဤစာမျက်နှာ၏ ပရင့်ထုတ်နိုင်သောမူ',
'tooltip-t-permalink'             => 'ယခုမူအတွက် ပုံသေလိပ်စာ',
'tooltip-ca-nstab-main'           => 'မာတိကာ ကြည့်ရန်',
'tooltip-ca-nstab-user'           => 'အသုံးပြုသူ၏ စာမျက်နှာကို ကြည့်ရန်',
'tooltip-ca-nstab-special'        => 'ဤသည်မှာ အထူးစာမျက်နှာဖြစ်သည်။ ဤစာမျက်နှာကို ပြင်ဆင် မရနိုင်ပါ။',
'tooltip-ca-nstab-project'        => 'ပရောဂျက်စာမျက်နှာကို ကြည့်ရန်',
'tooltip-ca-nstab-image'          => 'ဖိုင်စာမျက်နှာကိုကြည့်ပါ။',
'tooltip-ca-nstab-template'       => 'တမ်းပလိတ်ကို ကြည့်ရန်',
'tooltip-ca-nstab-category'       => 'ကဏ္ဍများကို ကြည့်ရန်',
'tooltip-minoredit'               => 'အရေးမကြီးသော တည်းဖြတ်မှုအဖြစ် မှတ်သားရန်',
'tooltip-save'                    => 'ပြောင်းလဲထားသည်များကို သိမ်းရန်',
'tooltip-preview'                 => 'သင်ပြင်ထားသည်များကို နမူနာကြည့်ရန်ဖြစ်သည်။ ကျေးဇူးပြု၍ မသိမ်းခင် သုံးပေးပါ။',
'tooltip-diff'                    => 'ဘယ်စာသား ​ပြောင်းလိုက်သည်ကို ြပရန်',
'tooltip-compareselectedversions' => 'ရွေးချယ်ထားသော မူနှစ်ခု၏ ကွဲပြားချက်များကို ကြည့်ရန်',
'tooltip-watch'                   => 'ဤစာမျက်နှာကို စောင့်ကြည့်စာရင်းသို့ ပေါင်းထည့်ရန်',
'tooltip-rollback'                => '"နောက်ပြန် ပြန်သွားခြင်း" သည် ဤစာမျက်နှာ၏ နောက်ဆုံး တည်းဖြတ်မူသို့ ကလစ် တစ်ချက်ဖြင့် ပြန်ပြောင်းပေးသည်။',
'tooltip-undo'                    => '"နောက်ပြန် ပြန်သွားခြင်း" သည် ဤ တည်းဖြတ်ခြင်းကို နောက်ပြန်ပြန်ဆုတ်ပြီး နမူနာပုံနှင့်တကွ တည်းဖြတ်ရန် ပြန်ဖွင့်မည် ဖြစ်သည်။ အဘယ်ကြောင့် နောက်ပြန်သွားသည်ကို အကျဉ်းချုပ် အကြောင်းပြချက် ရေးနိုင်သည်။',

# Info page
'numedits'       => 'တည်းဖြတ်မှု အရေအတွက် (စာမျက်နှာ) - $1',
'numtalkedits'   => 'တည်းဖြတ်မှုအရေအတွက် (ဆွေးနွေးချက် စာမျက်နှာ) - $1',
'numwatchers'    => 'စောင့်ကြည့်သူ အရေအတွက် - $1',
'numauthors'     => 'အဝေးရောက် စာရေးသူ အရေအတွက် - $1',
'numtalkauthors' => 'အဝေးရောက် စာရေးသူ အရေအတွက် (ဆွေးနွေးချက်စာမျက်နှာ) - $1',

# Math options
'mw_math_png'    => 'PNG ဖိုင်အဖြစ် အမြဲပုံဖော်ရန်',
'mw_math_simple' => 'လွန်စွာရိုးရှင်းပါက HTML အဖြစ် သို့မဟုတ်ပါက PNG အဖြစ်',
'mw_math_html'   => 'ဖြစ်နိုင်လျှင် HTML သို့မဟုတ်ပါက PNG အဖြစ်',
'mw_math_source' => 'TeX အဖြစ်ထားခဲ့ပါ (စာသားသာပြသည့် ဘရောက်ဇာများအတွက်)',
'mw_math_modern' => 'ခေတ်ပေါ်ဘရောက်ဇာများအတွက် အကြံပြုသည်',

# Browsing diffs
'previousdiff' => '← တည်းဖြတ်မူ အဟောင်း',
'nextdiff'     => 'ပိုသစ်သော တည်းဖြတ်မှု',

# Media information
'file-info-size'       => '($1 × $2 pixels, ဖိုင်အရွယ်အစား - $3, MIME အမျိုးအစား $4)',
'file-nohires'         => '<small>သည်ထက်ကြီးသော resolution မရှိပါ.</small>',
'svg-long-desc'        => '(SVG ဖိုင်, $1 × $2 pixels ကို အကြံပြုသည်, ဖိုင်အရွယ်အစား - $3)',
'show-big-image'       => 'resolution အပြည့်',
'show-big-image-thumb' => '<small>နမူနာကြည့်ရန် အရွယ်အစား - $1 × $2 pixels</small>',

# Special:NewFiles
'ilsubmit' => 'ရှာ​ဖွေ​ရန်​',

# Bad image list
'bad_image_list' => 'ဖောမတ်ပုံစံမှာ အောက်ပါအတိုင်းဖြစ်သည်။

စာရင်းတွင်ထည့်သွင်းထားသော အရာများကိုသာ ထည့်သွင်းစဉ်းစားမည်။ (ခရေပွင့် * ဖြင့်စသော စာကြောင်းများ)
စာကြောင်း၏ ပထမဆုံး လင့်သည် ဖိုင်ညံ့ ဖြစ်ရမည်။
ယင်းစာကြောင်းတွင်ပင် နောက်ထပ်လာသောလင့်များကို ချွင်းချက်အဖြစ် စဉ်းစားမည်။ ဆိုလိုသည်မှာ ၎င်းလင့်များတွင်လည်း အဆိုပါ ဖိုင်ညံ့ ပါကောင်း ပါဝင်နေမည်။',

# Metadata
'metadata'          => 'Metadata',
'metadata-help'     => 'ဤဖိုင်တွင် သတင်းအချက်အလက် အပိုများ ပါဝင်သည်။ ဒီဂျစ်တယ် ကင်မရာ သို့ စကင်နာများက ထည့်ပေါင်းပေးလိုက်ခြင်း ဖြစ်ကောင်းဖြစ်မည်။
အကယ်၍ ဖိုင်ကို မူရင်းအခြေအနေမှ ပြုပြင်လိုက်ပါက အသေးစိတ်အချို့သည် ပြုပြင်ထားသောဖိုင်တွင် အပြည့်အစုံ ပြန်ပါလာမည်မဟုတ်။',
'metadata-expand'   => 'ချဲ့ထားသော အသေးစိတ်များကို ပြရန်',
'metadata-collapse' => 'ချဲ့ထားသော အသေးစိတ်များကို ပြရန်',
'metadata-fields'   => 'metadata table ကို ဖွင့်ချလိုက်သောအခါ ဤမက်ဆေ့တွင် စာရင်းလုပ်ထားသော EXIF metadata fields သည် ပုံစာမျက်နှာပြရာတွင် ပါဝင်မည်ဖြစ်သည်။
အခြားအရာများကိုမူ ပုံမှန်အားဖြင့် ဝှက်ထားမည် ဖြစ်သည်။
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* isospeedratings
* focallength',

# EXIF tags
'exif-exposuretime-format' => '$1 စက္ကန့် ($2)',
'exif-gpsaltitude'         => 'အမြင့်',

'exif-meteringmode-255' => 'အခြား',

'exif-lightsource-1' => 'နေ့အလင်း',

'exif-focalplaneresolutionunit-2' => 'လက်မှတ်',

'exif-scenecapturetype-3' => 'ညနေပုံ',

# External editor support
'edit-externally'      => 'ပြင်ပ application တစ်ခုခုကိုသုံး၍ ဤဖိုင်ကို ပြင်ရန်',
'edit-externally-help' => '(နောက်ထပ်သတင်းအချက်အလက်များအတွက်[http://www.mediawiki.org/wiki/Manual:External_editors တပ်ဆင်မှု လမ်းညွှန်များ] ကို ကြည့်ရန်)',

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
'autosumm-new' => 'စာလုံး "$1" လုံးႏွင့္ စာမ်က္ႏွာအသစ္ စလုပ္ရန္',

# Watchlist editing tools
'watchlisttools-view' => 'ကိုက်ညီသော အပြောင်းအလဲများကို ကြည့်ရန်',
'watchlisttools-edit' => 'စောင့်ကြည့်စာရင်းများကို ကြည့်ပြီး တည်းဖြတ်ပါ။',
'watchlisttools-raw'  => 'စောင့်ကြည့်စာရင်း အကြမ်းကို တည်းဖြတ်ရန်',

# Special:Version
'version-other' => 'အခြား',

# Special:FilePath
'filepath-page' => 'ဖိုင်:',

# Special:SpecialPages
'specialpages' => 'အ​ထူး ​စာ​မျက်​နှာ​များ',

# HTML forms
'htmlform-selectorother-other' => 'အခြား',

);
