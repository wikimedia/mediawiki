<?php
/** Burmese (မြန်မာဘာသာ)
 *
 * To improve a translation please visit https://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Erikoo
 * @author Hakka
 * @author Hanzaw
 * @author Hintha
 * @author Htawmonzel
 * @author Lagoonaing
 * @author Liangent
 * @author Lionslayer
 * @author Minnyoonthit
 * @author Myanmars
 * @author Myolay
 * @author Ninjastrikers
 * @author Parabaik
 * @author Purodha
 * @author Saiddzone
 * @author Thanlwin
 * @author Thitaung
 * @author Zawthet
 * @author ကိုရာဝီ
 */

$digitTransformTable = [
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
];

$namespaceNames = [
	NS_MEDIA            => 'မီဒီယာ',
	NS_SPECIAL          => 'အထူး',
	NS_TALK             => 'ဆွေးနွေးချက်',
	NS_USER             => 'အသုံးပြုသူ',
	NS_USER_TALK        => 'အသုံးပြုသူ_ဆွေးနွေးချက်',
	NS_PROJECT_TALK     => '$1_ဆွေးနွေးချက်',
	NS_FILE             => 'ဖိုင်',
	NS_FILE_TALK        => 'ဖိုင်_ဆွေးနွေးချက်',
	NS_MEDIAWIKI        => 'မီဒီယာဝီကီ',
	NS_MEDIAWIKI_TALK   => 'မီဒီယာဝီကီ_ဆွေးနွေးချက်',
	NS_TEMPLATE         => 'တမ်းပလိတ်',
	NS_TEMPLATE_TALK    => 'တမ်းပလိတ်_ဆွေးနွေးချက်',
	NS_HELP             => 'အကူအညီ',
	NS_HELP_TALK        => 'အကူအညီ_ဆွေးနွေးချက်',
	NS_CATEGORY         => 'ကဏ္ဍ',
	NS_CATEGORY_TALK    => 'ကဏ္ဍ_ဆွေးနွေးချက်',
];

$specialPageAliases = [
	'Activeusers'               => [ 'တက်ကြွလှုပ်ရှားသည့်အသုံးပြုသူများ' ],
	'AllMyUploads'              => [ 'ကျွန်ုပ်၏ဖိုင်တင်မှုအားလုံး', 'ကျွန်ုပ်၏ဖိုင်များအားလုံး' ],
	'Allmessages'               => [ 'စာလွှာများအားလုံး' ],
	'Allpages'                  => [ 'စာမျက်နှာအားလုံး' ],
	'ApiHelp'                   => [ 'အေပီအိုင်အကူအညီ' ],
	'Ancientpages'              => [ 'အဟောင်းဆုံးစာမျက်နှာများ' ],
	'AutoblockList'             => [ 'အလိုအလျောက်ပိတ်ပင်စာရင်း' ],
	'Badtitle'                  => [ 'ညံ့ဖျင်းသောခေါင်းစဉ်' ],
	'Blankpage'                 => [ 'ဗလာစာမျက်နှာ' ],
	'Block'                     => [ 'ပိတ်ပင်', 'အိုင်ပီပိတ်ပင်', 'အသုံးပြုသူပိတ်ပင်' ],
	'BlockList'                 => [ 'ပိတ်ပင်စာရင်း', 'ပိတ်ပင်စာရင်းများ', 'အိုင်ပီပိတ်ပင်စာရင်း' ],
	'Booksources'               => [ 'မှီငြမ်းစာအုပ်များ' ],
	'BotPasswords'              => [ 'ဘော့စကားဝှက်များ' ],
	'BrokenRedirects'           => [ 'ကျိုးပျက်နေသောပြန်ညွှန်းများ' ],
	'Categories'                => [ 'ကဏ္ဍများ' ],
	'ChangeEmail'               => [ 'အီးမေးလ်ပြောင်းရန်' ],
	'ChangePassword'            => [ 'စကားဝှက်ပြောင်းရန်', 'စကားဝှက်ပြန်ချိန်ညှိရန်' ],
	'ComparePages'              => [ 'စာမျက်နှာများနှိုင်းယှဉ်' ],
	'Confirmemail'              => [ 'အီးမေးလ်အတည်ပြု' ],
	'Contributions'             => [ 'ဆောင်ရွက်ချက်များ', 'ပံ့ပိုး' ],
	'CreateAccount'             => [ 'အကောင့်ဖန်တီးရန်' ],
	'Deadendpages'              => [ 'လမ်းဆုံးနေသောစာမျက်နှာများ' ],
	'DeletedContributions'      => [ 'ဖျက်လိုက်သောပံ့ပိုးမှုများ' ],
	'Diff'                      => [ 'ကွဲပြားမှု' ],
	'DoubleRedirects'           => [ 'နှစ်ဆင့်ပြန်ပြန်ညွှန်းများ' ],
	'EditWatchlist'             => [ 'စောင့်ကြည့်စာရင်းတည်းဖြတ်' ],
	'Fewestrevisions'           => [ 'တည်းဖြတ်မူအနည်းဆုံးစာမျက်နှာများ' ],
	'FileDuplicateSearch'       => [ 'ထပ်တူဖိုင်ရှာဖွေခြင်း' ],
	'Filepath'                  => [ 'ဖိုင်လမ်းကြောင်း' ],
	'LinkAccounts'              => [ 'အကောင့်များချိတ်ဆက်ခြင်း' ],
	'LinkSearch'                => [ 'လင့်ရှာဖွေခြင်း' ],
	'Listadmins'                => [ 'စီမံခန့်ခွဲသူများစာရင်း' ],
	'Listbots'                  => [ 'ဘော့များစာရင်း' ],
	'Listfiles'                 => [ 'ဖိုင်များစာရင်း', 'ဖိုင်စာရင်း', 'ပုံစာရင်း' ],
	'Listgrouprights'           => [ 'အုပ်စုအခွင့်အရေးများစာရင်း', 'အသုံးပြုသူအုပ်စုအခွင့်အရေးများ' ],
	'Listredirects'             => [ 'ပြန်ညွှန်းသည့်လင့်များစာရင်း' ],
	'Listusers'                 => [ 'အသုံးပြုသူများစာရင်း' ],
	'Log'                       => [ 'မှတ်တမ်း', 'မှတ်တမ်းများ' ],
	'Lonelypages'               => [ 'မိဘမဲ့စာမျက်နှာများ' ],
	'Longpages'                 => [ 'ရှည်လျားသောစာမျက်နှာများ' ],
	'MediaStatistics'           => [ 'မီဒီယာစာရင်းအင်း' ],
	'MergeHistory'              => [ 'ရာဇဝင်ပေါင်းစည်းခြင်း' ],
	'MIMEsearch'                => [ 'MIMEရှာဖွေခြင်း' ],
	'Mostcategories'            => [ 'ကဏ္ဍအများဆုံးပါသောစာမျက်နှာများ' ],
	'Mostimages'                => [ 'အများဆုံးချိတ်ဆက်ထားသည့်ဖိုင်များ', 'အများဆုံးဖိုင်များ', 'အများဆုံးပုံများ' ],
	'Mostlinked'                => [ 'အများဆုံးချိတ်ဆက်ထားသည့်စာမျက်နှာများ', 'အများဆုံးချိတ်ဆက်မှု' ],
	'Mostlinkedcategories'      => [ 'အများဆုံးချိတ်ဆက်ထားသည့်ကဏ္ဍများ', 'အများဆုံးအသုံးပြုထားသည့်ကဏ္ဍများ' ],
	'Mostrevisions'             => [ 'တည်းဖြတ်မှုအများဆုံးများ' ],
	'Movepage'                  => [ 'စာမျက်နှာရွေ့ပြောင်း' ],
	'Mycontributions'           => [ 'ကျွန်ုပ်၏ဆောင်ရွက်ချက်များ' ],
	'MyLanguage'                => [ 'ကျွန်ုပ်၏ဘာသာစကား' ],
	'Mypage'                    => [ 'ကျွန်ုပ်၏စာမျက်နှာ' ],
	'Mytalk'                    => [ 'ကျွန်ုပ်၏ဆွေးနွေးချက်' ],
	'Myuploads'                 => [ 'ကျွန်ုပ်၏ဖိုင်တင်မှုများ', 'ကျွန်ုပ်၏ဖိုင်များ' ],
	'Newimages'                 => [ 'ဖိုင်အသစ်များ', 'ပုံအသစ်များ' ],
	'Newpages'                  => [ 'စာမျက်နှာအသစ်များ' ],
	'Pagedata'                  => [ 'စာမျက်နှာဒေတာ' ],
	'PasswordPolicies'          => [ 'စကားဝှက်မူဝါဒများ' ],
	'PasswordReset'             => [ 'စကားဝှက်အသစ်ပြုလုပ်ရန်' ],
	'PermanentLink'             => [ 'ပုံသေလိပ်စာ' ],
	'Preferences'               => [ 'ရွေးချယ်စရာများ' ],
	'Prefixindex'               => [ 'ရှေ့ဆက်ပါသောစာမျက်နှာအားလုံး' ],
	'Protectedpages'            => [ 'ကာကွယ်ထားသောစာမျက်နှာများ' ],
	'Protectedtitles'           => [ 'ကာကွယ်ထားသောခေါင်းစဉ်များ' ],
	'Randompage'                => [ 'ကျပန်း', 'ကျပန်းစာမျက်နှာ' ],
	'RandomInCategory'          => [ 'ကဏ္ဍတွင်းရှိကျပန်းစာမျက်နှာ' ],
	'Randomredirect'            => [ 'ကျပန်းပြန်ညွှန်း' ],
	'Randomrootpage'            => [ 'ကျပန်းအခြေစာမျက်နှာ' ],
	'Recentchanges'             => [ 'လတ်တလောအပြောင်းအလဲများ' ],
	'Redirect'                  => [ 'ပြန်ညွှန်း' ],
	'Search'                    => [ 'ရှာဖွေရန်' ],
	'Shortpages'                => [ 'စာမျက်နှာတိုများ' ],
	'Specialpages'              => [ 'အထူးစာမျက်နှာများ' ],
	'Statistics'                => [ 'စာရင်းအင်းများ', 'စာရင်းအင်း' ],
	'Tags'                      => [ 'စာတွဲများ' ],
	'TrackingCategories'        => [ 'နောက်ယောင်ခံကဏ္ဍများ' ],
	'Unblock'                   => [ 'ပိတ်ပင်မှုပြန်ဖွင့်ရန်' ],
	'Uncategorizedcategories'   => [ 'ကဏ္ဍမခွဲထားသောကဏ္ဍများ' ],
	'Uncategorizedimages'       => [ 'ကဏ္ဍမခွဲထားသောဖိုင်များ', 'ကဏ္ဍမခွဲထားသော_ပုံများ' ],
	'Uncategorizedpages'        => [ 'ကဏ္ဍမခွဲထားသောစာမျက်နှာများ' ],
	'Uncategorizedtemplates'    => [ 'ကဏ္ဍမခွဲထားသောတမ်းပလိတ်များ' ],
	'Undelete'                  => [ 'မဖျက်တော့ရန်' ],
	'Unusedcategories'          => [ 'အသုံးပြုမထားသောကဏ္ဍများ' ],
	'Unusedimages'              => [ 'အသုံးပြုမထားသောဖိုင်များ', 'အသုံးပြုမထားသောပုံများ' ],
	'Unusedtemplates'           => [ 'အသုံးပြုမထားသောတမ်းပလိတ်များ' ],
	'Unwatchedpages'            => [ 'မစောင့်ကြည့်တော့သောစာမျက်နှာများ' ],
	'Upload'                    => [ 'ဖိုင်တင်ရန်' ],
	'Version'                   => [ 'ဗားရှင်း' ],
	'Wantedcategories'          => [ 'အလိုရှိသောကဏ္ဍများ' ],
	'Wantedfiles'               => [ 'အလိုရှိသောဖိုင်များ' ],
	'Wantedpages'               => [ 'အလိုရှိသောစာမျက်နှာများ', 'ကျိုးပျက်နေသောလင့်များ' ],
	'Wantedtemplates'           => [ 'အလိုရှိသောတမ်းပလိတ်များ' ],
	'Watchlist'                 => [ 'စောင့်ကြည့်စာရင်း' ],
	'Whatlinkshere'             => [ 'ဘယ်ကလင့်ထားလဲ' ],
	'Withoutinterwiki'          => [ 'ဘာသာစကားလင့်မပါသောစာမျက်နှာများ' ],
];

$datePreferences = [
	'default',
	'my normal',
	'my long',
	'ISO 8601',
];

$defaultDateFormat = 'my normal';

$dateFormats = [
	'my normal time' => 'H:i',
	'my normal date' => 'j F Y',
	'my normal both' => ' H:i"၊" j F Y',

	'my long time' => 'H:i',
	'my long date' => 'Y "ဇန်နဝါရီ" F"လ" j "ရက်"',
	'my long both' => 'H:i"၊" Y "ဇန်နဝါရီ" F"လ" j "ရက်"',
];
