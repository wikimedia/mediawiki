<?php
/** ئۇيغۇرچە (ئۇيغۇرچە)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Alfredie
 * @author Sahran
 */

$rtl = true;

$namespaceNames = array(
	NS_MEDIA            => 'ۋاسىتە',
	NS_SPECIAL          => 'ئالاھىدە',
	NS_TALK             => 'مۇنازىرە',
	NS_USER             => 'ئىشلەتكۈچى',
	NS_USER_TALK        => 'ئىشلەتكۈچى مۇنازىرىسى',
	NS_PROJECT_TALK     => 'مۇنازىرىسى$1',
	NS_FILE             => 'ھۆججەت',
	NS_FILE_TALK        => 'ھۆججەت مۇنازىرىسى',
	NS_MEDIAWIKI_TALK   => 'MediaWiki مۇنازىرىسى',
	NS_TEMPLATE         => 'قېلىپ',
	NS_TEMPLATE_TALK    => 'قېلىپ مۇنازىرىسى',
	NS_HELP             => 'ياردەم',
	NS_HELP_TALK        => 'ياردەم مۇنازىرىسى',
	NS_CATEGORY         => 'تۈر',
	NS_CATEGORY_TALK    => 'تۈر مۇنازىرىسى',
);

$messages = array(
# User preference toggles
'tog-underline'               => 'ئۇلانما ئاستى سىزىقى:',
'tog-highlightbroken'         => 'ئۈزۈلگەن ئۇلانما فورماتى <a href="" class="new"> مۇشۇنىڭغا ئوخشاش </a> (ياكى بۇنىڭغا ئوخشاش<a href="" class="internal">؟</a>)',
'tog-justify'                 => 'ئابزاس توغرىلا',
'tog-hideminor'               => 'يېقىنقى ئۆزگەرتىشتە ئازراقلا تەھرىرنى يوشۇر',
'tog-hidepatrolled'           => 'يېقىنقى ئۆزگەرتىشتە كۆزەتكەن تەھرىرنى يوشۇر',
'tog-newpageshidepatrolled'   => 'يېڭى بەت تىزىملىكىدە كۆزەتكەن تەھرىرنى يوشۇر',
'tog-extendwatchlist'         => 'كۈچەيتىلگەن كۆزەت تىزىملىكىدە يېقىنقى ئۆزگەرتىشنىلا كۆرسەتمەي بەلكى ھەممە ئۆزگەرتىشنى كۆرسەت',
'tog-usenewrc'                => 'كۈچەيتىلگەن يېقىنقى ئۆزگەرتىشنى قوزغات (JavaScript زۆرۈر)',
'tog-numberheadings'          => 'ماۋزۇغا ئۆزلۈكىدىن تەرتىپ نومۇرى قوش',
'tog-showtoolbar'             => 'تەھرىر قورال ستونىنى كۆرسەت (JavaScript زۆرۈر)',
'tog-editondblclick'          => 'قوش چەككەندە بەت تەھرىرلە (JavaScript زۆرۈر)',
'tog-editsection'             => '[تەھرىر] ئۇلانمىسىنى چېكىپ ئابزاس تەھرىرلەشكە يول قوي',
'tog-editsectiononrightclick' => 'ماۋزۇنى چاشقىنەكتە ئوڭ چېكىپ ئابزاس تەھرىرلەشكە يول قوي (JavaScript زۆرۈر)',
'tog-showtoc'                 => 'مەزمۇن جەدۋىلى كۆرسەت (بىر بەتتە 3 تىن ئارتۇق ماۋزۇ بار بەتكە قارىتىلغان)',
'tog-rememberpassword'        => 'بۇ كومپيۇتېردا كىرگىنىمنى ئەستە ساقلا',
'tog-editwidth'               => 'تەھرىرلەش رامكىسىنى كېڭەيتىپ پۈتۈن ئېكرانغا تولدۇر',
'tog-watchcreations'          => 'مەن قۇرغان بەتنى كۆزەت تىزىملىكىمگە قوش',
'tog-watchdefault'            => 'مەن تەھرىرلىگەن بەتنى كۆزەت تىزىملىكىمگە قوش',
'tog-watchmoves'              => 'مەن يۆتكىگەن بەتنى كۆزەت تىزىملىكىمگە قوش',
'tog-watchdeletion'           => 'مەن ئۆچۈرگەن بەتنى كۆزەت تىزىملىكىمگە قوش',
'tog-minordefault'            => 'ھەممە تەھرىرلەشنى ئازراقلا تەھرىرگە تەڭشە',
'tog-previewontop'            => 'تەھرىر رامكىسىنىڭ ئۈستىدە ئالدىن كۆزىتىشنى كۆرسەت',
'tog-previewonfirst'          => 'تۇنجى قېتىم تەھرىرلىگەندە ئالدىن كۆزىتىشنى كۆرسەت',
'tog-nocache'                 => 'بەت غەملەشنى چەكلە',
'tog-enotifwatchlistpages'    => 'كۆزەت تىزىملىكىمدىكى بەت ئۆزگەرگەندە ئېلخەت يوللا',
'tog-enotifusertalkpages'     => 'مۇنازىرە بېتىم ئۆزگەرگەندە ئېلخەت يوللا',
'tog-enotifminoredits'        => 'بەت ئازراقلا تەھرىرلەنگەندىمۇ ئېلخەت يوللا',
'tog-enotifrevealaddr'        => 'ئۇقتۇرۇش ئېلخەت تىزىملىكىدە ئېلخەت ئادرېسىمنى ئاشكارىلا',
'tog-shownumberswatching'     => 'بۇ بەتنى كۆزىتىۋاتقان ئىشلەتكۈچى سانىنى كۆرسەت',
'tog-fancysig'                => 'ئىمزاغا wiki تېكستى سۈپىتىدە مۇئامىلە قىل (ئۆزلۈكىدىن ئۇلانما ھاسىل بولمايدۇ)',
'tog-externaleditor'          => 'كۆڭۈلدىكى ئەھۋالدا سىرتقى تەھرىرلىگۈچ ئىشلەت (ئالىي ئىشلەتكۈچىگە تەمىنلىنىدۇ، كومپيۇتېرىڭىزدا بىر قىسىم ئالاھىدە تەڭشەش ئېلىپ بېرىشىڭىز لازىم)',
'tog-externaldiff'            => 'كۆڭۈلدىكى ئەھۋالدا سىرتقى پەرق تەھلىلى ئىشلەت (ئالىي ئىشلەتكۈچىگە تەمىنلىنىدۇ، كومپيۇتېرىڭىزدا بىر قىسىم ئالاھىدە تەڭشەش ئېلىپ بېرىشىڭىز لازىم)',
'tog-showjumplinks'           => '"ئاتلا" زىيارەت ئۇلانمىسىنى قوزغات',
'tog-uselivepreview'          => 'رىئال ۋاقىتلىق ئالدىن كۆزىتىشنى ئىشلەت (JavaScript زۆرۈر) (سىناق)',
'tog-forceeditsummary'        => 'ئۈزۈندە كىرگۈزمىگەندە مېنى ئەسكەرت',
'tog-watchlisthideown'        => 'كۆزەت تىزىملىكىدىن مېنىڭ تەھرىرلىگىنىمنى يوشۇر',
'tog-watchlisthidebots'       => 'كۆزەت تىزىملىكىدىن ماشىنا ئادەم تەھرىرلىگەننى يوشۇر',
'tog-watchlisthideminor'      => 'كۆزەت تىزىملىكىدىن ئازراقلا تەھرىرلىگەننى يوشۇر',
'tog-watchlisthideliu'        => 'كۆزەت تىزىملىكىدە تىزىمغا كىرگەن ئىشلەتكۈچىلەرنى يوشۇر',
'tog-watchlisthideanons'      => 'كۆزەت تىزىملىكىدە ئىمزاسىز ئىشلەتكۈچىلەرنى يوشۇر',
'tog-watchlisthidepatrolled'  => 'كۆزەت تىزىملىكىدىن كۆزىتىلگەن تەھرىرنى يوشۇر',
'tog-ccmeonemails'            => 'مەن باشقىلارغا يوللىغان ئېلخەتنى ئۆزەمگىمۇ بىر نۇسخا يوللا',
'tog-diffonly'                => 'تۈزىتىلگەن ئىككى نەشرىنىڭ پەرقىنى سېلىشتۇرغاندا بەت مەزمۇنىنى كۆرسەتمە',
'tog-showhiddencats'          => 'يوشۇرۇن تۈرلەرنى كۆرسەت',
'tog-norollbackdiff'          => 'قايتۇرۇشنى ئىجرا قىلغاندىن كېيىن پەرقنى كۆرسەتمە',

'underline-always'  => 'دائىم',
'underline-never'   => 'ھەرگىز',
'underline-default' => 'توركۆرگۈ كۆڭۈلدىكى',

# Dates
'sunday'        => 'يەكشەنبە',
'monday'        => 'دۈشەنبە',
'tuesday'       => 'سەيشەنبە',
'wednesday'     => 'چارشەنبە',
'thursday'      => 'پەيشەنبە',
'friday'        => 'جۈمە',
'saturday'      => 'شەنبە',
'sun'           => 'يەكشەنبە',
'mon'           => 'دۈشەنبە',
'tue'           => 'سەيشەنبە',
'wed'           => 'چارشەنبە',
'thu'           => 'پەيشەنبە',
'fri'           => 'جۈمە',
'sat'           => 'شەنبە',
'january'       => 'قەھرىتان',
'february'      => 'ھۇت',
'march'         => 'نەۋرۇز',
'april'         => 'ئۇمۇت',
'may_long'      => 'باھار',
'june'          => 'سەپەر',
'july'          => 'چىللە',
'august'        => 'تومۇز',
'september'     => 'مىزان',
'october'       => 'ئوغۇز',
'november'      => 'ئوغلاق',
'december'      => 'كۆنەك',
'january-gen'   => 'قەھرىتان',
'february-gen'  => 'ھۇت',
'march-gen'     => 'نەۋرۇز',
'april-gen'     => 'ئۇمۇت',
'may-gen'       => 'باھار',
'june-gen'      => 'سەپەر',
'july-gen'      => 'چىللە',
'august-gen'    => 'تومۇز',
'september-gen' => 'مىزان',
'october-gen'   => 'ئوغۇز',
'november-gen'  => 'ئوغلاق',
'december-gen'  => 'كۆنەك',
'jan'           => 'قەھرىتان',
'feb'           => 'ھۇت',
'mar'           => 'نەۋرۇز',
'apr'           => 'ئۇمۇت',
'may'           => 'باھار',
'jun'           => 'سەپەر',
'jul'           => 'چىللە',
'aug'           => 'تومۇز',
'sep'           => 'مىزان',
'oct'           => 'ئوغۇز',
'nov'           => 'ئوغلاق',
'dec'           => 'كۆنەك',

# Categories related messages
'pagecategories'                 => '$1 تۈر',
'category_header'                => '"$1" تۈردىكى بەتلەر',
'subcategories'                  => 'تارماق تۈر',
'category-media-header'          => '"$1" تۈردىكى ۋاسىتە',
'category-empty'                 => "''بۇ تۈردە ھېچقانداق بەت ياكى ۋاسىتە يوق.''",
'hidden-categories'              => '$1 يوشۇرۇن تۈر',
'hidden-category-category'       => 'يوشۇرۇن تۈرلەر',
'category-subcat-count'          => '{{PLURAL:$2|بۇ تۈرنىڭ تۆۋەندىكى تارماق تۈرلىرى بار.|بۇ تۈرنىڭ تۆۋەندىكى {{PLURAL:$1|تارماق بېتى|$1 }}، جەمئى $2 بېتى بار.}}',
'category-subcat-count-limited'  => 'بۇ تۈرنىڭ تۆۋەندىكى $1 تارماق تۈرى بار.',
'category-article-count'         => '{{PLURAL:$2|بۇ تۈردە تۆۋەندىكى بەتلا بار | {{PLURAL:$1|بۇ تۈردە تۆۋەندىكى |$1 بەت}} جەمئى $2 بەت بار.}}',
'category-article-count-limited' => 'بۇ تۈردە $1 بەت بار.',
'category-file-count'            => '{{PLURAL:$2| بۇ تۈرنىڭ تۆۋەندىكى ھۆججىتىلا بار.| بۇ تۈردە تۆۋەندىكى $1 ھۆججەت، جەمئى $2 ھۆججەت بار.}}',
'category-file-count-limited'    => 'نۆۋەتتىكى تۈردە تۆۋەندىكى $1 ھۆججەت بار.',
'listingcontinuesabbrev'         => 'داۋاملاشتۇر',

'mainpagetext'      => "<big>'''MediaWiki مۇۋەپپەقىيەتلىك قاچىلاندى.'''</big>",
'mainpagedocfooter' => '[http://meta.wikimedia.org/wiki/Help:Contents ئىشلەتكۈچى قوللانمىسى] نى زىيارەت قىلىپ wiki يۇمشاق دېتالىنى ئىشلىتىش ئۇچۇرىغا ئېرىشىڭ.

== دەسلەپكى ساۋات ==
* [http://www.mediawiki.org/wiki/Manual:Configuration_settings سەپلىمە تەڭشەك تىزىملىكى]
* [http://www.mediawiki.org/wiki/Manual:FAQ MediaWiki كۆپ ئۇچرايدىغان مەسىلىلەرگە جاۋاب]
* [https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce MediaWiki تارقاتقان ئېلخەت تىزىملىكى]',

'about'         => 'ھەققىدە',
'article'       => 'مەزمۇن بېتى',
'newwindow'     => '(يېڭى كۆزنەكتە ئاچ)',
'cancel'        => 'ۋاز كەچ',
'moredotdotdot' => 'تەپسىلىي…',
'mypage'        => 'بەتىم',
'mytalk'        => 'مۇنازىرە بېتىم',
'anontalk'      => 'بۇ IP نىڭ مۇنازىرە بېتى',
'navigation'    => 'يولباشچى',
'and'           => '&#32;ۋە',

# Cologne Blue skin
'qbfind'         => 'ئىزدە',
'qbbrowse'       => 'كۆز يۈگۈرت',
'qbedit'         => 'ئۆزگەرتىش',
'qbpageoptions'  => 'بۇ بەت',
'qbpageinfo'     => 'كونتېكست',
'qbmyoptions'    => 'بەتلەرىم',
'qbspecialpages' => 'ئالاھىدە بەتلەرى',

# Metadata in edit box
'metadata_help' => 'مېتا سانلىق مەلۇماتى:',

'errorpagetitle'    => 'خاتالىق',
'returnto'          => '$1 غا قايت.',
'tagline'           => 'ئورنى {{SITENAME}}',
'help'              => 'ياردەم',
'search'            => 'ئىزدە',
'searchbutton'      => 'ئىزدە',
'go'                => 'كۆچۈش',
'searcharticle'     => 'يۆتكەل',
'history'           => 'بەت تارىخى',
'history_short'     => 'تارىخ',
'updatedmarker'     => 'مەن ئالدىنقى قېتىم زىيارەت قىلغاندىن بۇيانقى يېڭىلانغىنى',
'info_short'        => 'ئۇچۇر',
'printableversion'  => 'باسقىلى بولىدىغان نەشرى',
'permalink'         => 'مەڭگۈلۈك ئۇلانما',
'print'             => 'باس',
'edit'              => 'تەھرىر',
'create'            => 'قۇر',
'editthispage'      => 'بۇ بەتنى تەھرىرلە',
'create-this-page'  => 'بۇ بەتنى قۇر',
'delete'            => 'ئۆچۈر',
'deletethispage'    => 'بۇ بەتنى ئۆچۈر',
'undelete_short'    => 'ئۆچۈرۈلگەن $1 تەھرىر ئەسلىگە كەلتۈرۈلدى',
'protect'           => 'قوغدا',
'protect_change'    => 'ئۆزگەرت',
'protectthispage'   => 'بۇ بەتنى قوغدا',
'unprotect'         => 'قوغدىما',
'unprotectthispage' => 'بۇ بەتنى قوغدىما',
'newpage'           => 'يېڭى بەت',
'talkpage'          => 'بۇ بەتنىڭ مۇنازىرىسى',
'talkpagelinktext'  => 'مۇنازىرە',
'specialpage'       => 'ئالاھىدە بەت',
'personaltools'     => 'شەخسىي قوراللار',
'postcomment'       => 'يېڭى ئابزاس',
'articlepage'       => 'مەزمۇن بېتىنى كۆرسەت',
'talk'              => 'مۇنازىرە',
'views'             => 'كۆرۈنۈش',
'toolbox'           => 'قورال ستونى',
'userpage'          => 'ئىشلەتكۈچى بېتىنى كۆرسەت',
'projectpage'       => 'قۇرۇلۇش بېتىنى كۆرسەت',
'imagepage'         => 'ھۆججەت بېتىنى كۆرسەت',
'mediawikipage'     => 'ئۇچۇر بېتىنى كۆرسەت',
'templatepage'      => 'قېلىپ بېتىنى كۆرسەت',
'viewhelppage'      => 'ياردەم بېتىنى كۆرسەت',
'categorypage'      => 'كاتېگورىيە بېتىنى كۆرسەت',
'viewtalkpage'      => 'مۇنازىرە كۆرسەت',
'otherlanguages'    => 'باشقا تىلاردا',
'redirectedfrom'    => '(قايتا نىشان بەلگىلەش ئورنى $1)',
'redirectpagesub'   => 'قايتا نىشان بەلگىلەنگەن بەت',
'lastmodifiedat'    => 'بۇ بەتنى $1 ئاخىرقى قېتىم $2 دا ئۆزگەرتكەن.',
'viewcount'         => 'بۇ بەت $1 قېتىم زىيارەت قىلىندى.',
'protectedpage'     => 'قوغدالغان بەت',
'jumpto'            => 'ئاتلا:',
'jumptonavigation'  => 'يولباشچى',
'jumptosearch'      => 'ئىزدە',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => '{{SITENAME}} ھەققىدە',
'aboutpage'            => 'Project:ھەققىدە',
'copyright'            => 'بۇ بېكەتتىكى بارلىق تېكست مەزمۇنى $1 ماددىسىغا ئاساسەن تەمىنلىنىدۇ.',
'copyrightpagename'    => '{{SITENAME}} نەشر ھوقۇقى',
'copyrightpage'        => '{{ns:project}}:نەشر ئۇچۇرى',
'currentevents'        => 'نۆۋەتتىكى ھادىسە',
'currentevents-url'    => 'Project:نۆۋەتتىكى ھادىسە',
'disclaimers'          => 'جاۋابكارلىقنى كەچۈرۈم قىلىش باياناتى',
'disclaimerpage'       => 'Project:ئادەتتىكى جاۋابكارلىقنى كەچۈرۈم قىلىش باياناتى',
'edithelp'             => 'تەھرىرلەش ياردىمى',
'edithelppage'         => 'Help:تەھرىرلەۋاتىدۇ',
'helppage'             => 'Help:مەزمۇنى',
'mainpage'             => 'باش بەت',
'mainpage-description' => 'باش بەت',
'policy-url'           => 'Project:تاكتىكا',
'portal'               => 'ئىجتىمائى رايون باش بېتى',
'portal-url'           => 'Project:ئىجتىمائى رايون باش بېتى',
'privacy'              => 'شەخسىيەت تاكتىكىسى',
'privacypage'          => 'Project:شەخسىيەت تاكتىكىسى',

'badaccess'        => 'ھوقۇق چېكى خاتالىقى',
'badaccess-group0' => 'سىزنىڭ بايىقى ئىلتىماسىڭىزنى ئىجرا قىلىشقا يول قويمايدۇ.',
'badaccess-groups' => 'سىزنىڭ بايىقى ئىلتىماسىڭىزنى{{PLURAL:$2|بۇ گۇرۇپپا|بىر گۇرۇپپا}}: $1. نىڭ ئىشلەتكۈچىلىرىلا ئىشلىتەلەيدۇ: $1.',

'versionrequired'     => 'MediaWiki نىڭ $1 نەشرى زۆرۈر',
'versionrequiredtext' => '$1 نەشرىدىكى MediaWiki بولغاندىلا ئاندىن بۇ بەتنى ئىشلىتەلەيدۇ.

[[Special:Version|نەشر بېتى]] نى كۆرۈڭ.',

'ok'                      => 'جەزملە',
'retrievedfrom'           => '"$1" دىن ئېرىشكەن',
'youhavenewmessages'      => 'سىزدە $1 ($2) بار.',
'newmessageslink'         => 'يېڭى ئۇچۇر',
'newmessagesdifflink'     => 'ئاخىرقى ئۆزگەرتىش',
'youhavenewmessagesmulti' => '$1 يېڭى ئۇچۇرىڭىز بار',
'editsection'             => 'تەھرىر',
'editold'                 => 'تەھرىر',
'viewsourceold'           => 'مەنبەنى كۆرسەت',
'editlink'                => 'تەھرىر',
'viewsourcelink'          => 'مەنبەنى كۆرسەت',
'editsectionhint'         => 'ئابزاس تەھرىر: $1',
'toc'                     => 'مەزمۇنى',
'showtoc'                 => 'كۆرسەت',
'hidetoc'                 => 'يوشۇر',
'thisisdeleted'           => 'كۆرسەت ياكى ئەسلىگە كەلتۈر $1 ؟',
'viewdeleted'             => '$1 كۆرسەت؟',
'restorelink'             => '$1 ئۆچۈرۈلگەن نەشرى',
'feedlinks'               => 'قانال:',
'feed-invalid'            => 'ئىناۋەتسىز مۇشتەرى بولغان قانال تىپى.',
'feed-unavailable'        => 'بىرلەشمە قانال ئىناۋەتسىز',
'site-rss-feed'           => '$1 RSS قانالى',
'site-atom-feed'          => '$1 نىڭ Atom قانالى',
'page-rss-feed'           => '"$1" نىڭ RSS قانىلى',
'page-atom-feed'          => '"$1" نىڭ Atom قانىلى',
'red-link-title'          => '$1 (بەت مەۋجۇد ئەمەس)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'بەت',
'nstab-user'      => 'ئىشلەتكۈچى بېتى',
'nstab-media'     => 'ۋاسىتە بېتى',
'nstab-special'   => 'ئالاھىدە بەت',
'nstab-project'   => 'قۇرۇلۇش بېتى',
'nstab-image'     => 'ھۆججەت',
'nstab-mediawiki' => 'ئۇچۇر',
'nstab-template'  => 'قېلىپ',
'nstab-help'      => 'ياردەم بەتى',
'nstab-category'  => 'تۈر',

# Main script and global functions
'nosuchaction'      => 'بۇنداق مەشغۇلات يوق',
'nosuchactiontext'  => 'بۇ مەشغۇلات بېكىتكەن URL ئىناۋەتسىز.

URL نى خاتا كىرگۈزۈپ قالدىڭىز ياكى خاتا ئۇلانمىغا ئەگەشتىڭىز.

 {{SITENAME}} بېكەت يۇمشاق دېتالىنىڭ خاتالىقى بولۇشى مۇمكىن.',
'nosuchspecialpage' => 'بۇنىڭغا ئوخشاش ئالاھىدە بەت يوق',
'nospecialpagetext' => "<big>'''سىز ئىلتىماس قىلغان ئالاھىدە بەت ئىناۋەتسىز.'''</big>


[[Special:SpecialPages|{{int:specialpages}}]] دە بارلىق ئىناۋەتلىك بەتلەر تىزىملىكى بار.",

# General errors
'error'                => 'خاتالىق',
'databaseerror'        => 'ساندان خاتالىقى',
'dberrortext'          => 'ساندان سۈرۈشتۈرۈشتە گرامماتىكىلىق خاتالىق يۈز بەردى.

يۇمشاق دېتالنىڭ ئۆزىدىكى خاتالىقتىن كېلىپ چىققان بولۇشى مۇمكىن.

ئاخىرقى قېتىملىق ساندان سۈرۈشتۈرۈش بۇيرۇقى:

<blockquote><tt>$1</tt></blockquote>

 "<tt>$2</tt>"فۇنكسىيىدىن كەلگەن.

MySQL قايتۇرغان خاتالىق "<tt>$3: $4</tt>".',
'dberrortextcl'        => 'ساندان سۈرۈشتۈرۈشتە گرامماتىكىلىق خاتالىق يۈز بەردى.

ئاخىرقى قېتىملىق ساندان سۈرۈشتۈرۈش بۇيرۇقى:

"$1"

"$2" فۇنكسىيىدىن كەلگەن.

MySQL قايتۇرغان خاتالىقى"$3: $4"',
'laggedslavemode'      => 'ئاگاھلاندۇرۇش: بەت يېقىنقى يېڭىلاشنى ئۆز ئىچىگە ئالمىغان بولۇشى مۇمكىن.',
'readonly'             => 'ساندان قۇلۇپلانغان',
'enterlockreason'      => 'قۇلۇپلىنىش سەۋەبىنى كىرگۈزۈڭ، قايتا ئېچىشنىڭ مۆلچەر ۋاقتىنىمۇ ئۆز ئىچىگە ئالىدۇ',
'readonlytext'         => 'نۆۋەتتە ساندان يېڭى مەزمۇن كىرگۈزۈش ياكى ئۆزگەرتىشنى چەكلەيدۇ، بۇ سانداننىڭ ئاسرىلىۋاتقانلىقىدىن بولۇشى مۇمكىن، تاماملانغاندىن كېيىنلا ئەسلىگە كېلىدۇ.


باشقۇرغۇچىنىڭ تۆۋەندىكىدەك چۈشەندۈرۈشى بار: $1',
'missing-article'      => 'ساندان تور بەتتىن \\"$1\\" $2 ئاتلىق مەزمۇننى تاپالمىدى.

 بۇ ئادەتتە ئۆزگەرتىش تارىخ بېتىدىكى ۋاقتى ئۆتكەن ئۇلانمىنىڭ ئۆچۈرۈلگەن بەتكە ئۇلانغانلىقىدىن كېلىپ چىقىدۇ.

 ئەگەر بۇ خىل ئەھۋال بولمىسا، يۇمشاق دېتالنىڭ خاتالىقىدىن بىرنى بايقىغان بولۇشىڭىز مۇمكىن.
 سىز URL ئادرېسنى خاتىرىلىۋېلىپ، [[Special:ListUsers/sysop|باشقۇرغۇچى]] غا مەلۇم قىلىڭ.',
'missingarticle-rev'   => '(تۈزىتىش#: $1)',
'missingarticle-diff'  => '(پەرق: $1، $2)',
'readonly_lag'         => 'قوشۇمچە ساندان مۇلازىمىتىر غەملەكنى ئاساسىي مۇلازىمىتىرغا يېڭىلاۋاتىدۇ، ساندان ئۆزلۈكىدىن قۇلۇپلاندى',
'internalerror'        => 'ئىچكى خاتالىق',
'internalerror_info'   => 'ئىچكى خاتالىق: $1',
'filecopyerror'        => '"$1" ھۆججەتنى"$2" غا كۆچۈرەلمىدى.',
'filerenameerror'      => '"$1" ھۆججەتنىڭ ئاتىنى "$2" غا ئۆزگەرتەلمىدى.',
'filedeleteerror'      => '"$1" ھۆججەتنى ئۆچۈرەلمىدى.',
'directorycreateerror' => '"$1" مۇندەرىجىنى قۇرالمىدى.',
'filenotfound'         => '"$1" ھۆججەتنى تاپالمىدى.',
'fileexistserror'      => '"$1" ھۆججەتكە يازالمىدى: ھۆججەت مەۋجۇد',
'unexpected'           => 'كۈتۈلمىگەن قىممەت: "$1"="$2".',
'formerror'            => 'خاتالىق: جەدۋەلنى يوللىيالمىدى',
'badarticleerror'      => 'مەزكۇر بەتتە بۇ خىل مەشغۇلاتنى ئېلىپ بارغىلى بولمايدۇ.',
'cannotdelete'         => 'بەلگىلەنگەن ھۆججەت ياكى بەتنى ئۆچۈرەلمىدى.

ئۇ باشقىلار تەرىپىدىن ئۆچۈرۈلگەن بولۇشى مۇمكىن.',
'badtitle'             => 'خاتا ماۋزۇ',
'badtitletext'         => 'ئىلتىماس قىلىنغان بەتنىڭ ماۋزۇسى ئىناۋەتسىز، مەۋجۇد ئەمەس، تىل ھالقىغان ياكى wiki ئۇلانمىسىدىن ھالقىغان ماۋزۇ خاتالىقى.
ئۇ بىر ياكى بىر قانچە ماۋزۇغا ئىشلەتكىلى بولمايدىغان ھەرپنى ئۆز ئىچىگە ئالغان.',
'perfcached'           => 'تۆۋەندىكىسى غەملەك سانلىق مەلۇماتى، شۇڭلاشقا يېڭى بولماسلىقى مۇمكىن.',
'viewsource'           => 'مەنبەنى كۆرسەت',

# Virus scanner
'virus-unknownscanner' => 'نامەلۇم ۋىرۇسخور',

# Login and logout pages
'yourname'                => 'ئىشلەتكۈچى ئاتى:',
'yourpassword'            => 'ئىم:',
'yourpasswordagain'       => 'ئاچقۇچنى قايتا بەسىڭ:',
'remembermypassword'      => 'بۇ كومپيۇتېردا كىرگىنىمنى ئەستە ساقلا',
'login'                   => 'تىزىمغا كىر',
'nav-login-createaccount' => 'كىر / ھېسابات قۇر',
'userlogin'               => 'تىزىمغا كىر/ھېسابات قۇر',
'logout'                  => 'تىزىمدىن چىق',
'userlogout'              => 'تىزىمدىن چىق',
'nologinlink'             => 'ھېساباتتىن بىرنى قۇر',
'createaccount'           => 'ھېسابات قۇر',
'createaccountmail'       => 'ئېلخەتتە',
'userexists'              => 'كىرگۈزگەن ئىشلەتكۈچى ئاتى ئىشلىتىلىۋاتىدۇ.
باشقا ئاتنى تاللاڭ.',
'loginerror'              => 'تىزىمغا كىرىش خاتالىقى',
'noname'                  => 'سىز تېخى ئىناۋەتلىك ئىشلەتكۈچى ئاتىنى بەلگىلىمىدىڭىز.',
'loginsuccesstitle'       => 'تىزىمغا كىرىش مۇۋەپپەقىيەتلىك',
'loginsuccess'            => "'''سىز {{SITENAME}} غا \"\$1\" سالاھىيىتىدە كىردىڭىز.'''",
'wrongpassword'           => 'كىرگۈزگەن ئىم خاتا.
قايتا سىناڭ.',
'mailmypassword'          => 'يېڭى ئىمنى ئېخەتكە ئەۋەت',
'loginlanguagelabel'      => 'تىل: $1',

# Password reset dialog
'oldpassword'             => 'كونا ئىم:',
'newpassword'             => 'يېڭى ئىم:',
'retypenew'               => 'يېڭى ئىمنى قايتا كىرگۈزۈڭ:',
'resetpass_forbidden'     => 'ئىمنى ئۆزگەرتەلمىدى',
'resetpass-temp-password' => 'ۋاقىتلىق ئىم:',

# Edit page toolbar
'bold_sample'     => 'توم خەت',
'bold_tip'        => 'توم خەت',
'italic_sample'   => 'يانتۇ خەت',
'italic_tip'      => 'يانتۇ خەت',
'link_sample'     => 'ئۇلانما ماۋزۇ',
'link_tip'        => 'ئىچكى ئۇلانما',
'extlink_sample'  => 'http://www.example.com ئۇلانما ماۋزۇسى',
'extlink_tip'     => 'سىرتقى ئۇلانما (http:// ئالدى قوشۇلغۇچى قوشۇڭ)',
'headline_sample' => 'ماۋزۇ تېكستى',
'headline_tip'    => '2- دەرىجىلىك ماۋزۇ',
'math_sample'     => 'بۇ جايغا فورمۇلا قىستۇر',
'math_tip'        => 'ماتېماتېكىلىق فورمۇلا (LaTeX)',
'nowiki_sample'   => 'فورماتى يوق تېكست قىستۇر',
'nowiki_tip'      => 'wiki فورماتىغا پەرۋا قىلما',
'image_tip'       => 'سىڭدۈرمە ھۆججەت',
'media_tip'       => 'ھۆججەت ئۇلىنىشى',
'sig_tip'         => 'ۋاقىت تامغىلىق ئىمزايىڭىز',
'hr_tip'          => 'توغرىسىغا سىزىق (ئېھتىيات بىلەن ئىشلىتىڭ)',

# Edit pages
'summary'                          => 'مۇھىم مەزمۇن:',
'subject'                          => 'تېما/ماۋزۇ:',
'minoredit'                        => 'بۇ ئازراقلا تەھرىرلەش',
'watchthis'                        => 'بۇ بەتنى كۆزەت',
'savearticle'                      => 'بەت ساقلا',
'preview'                          => 'ئالدىن كۆزەت',
'showpreview'                      => 'ئالدىن كۆزىتىشنى كۆرسەت',
'showlivepreview'                  => 'رىئال ۋاقىتلىق ئالدىن كۆزىتىش',
'showdiff'                         => 'ئۆزگەرتىشنى كۆرسەت',
'anoneditwarning'                  => "'''ئاگاھلاندۇرۇش:''' سىز تېخى كىرمىدىڭىز.
 سىزنىڭ IP ئادرېسىڭىز بۇ بەتنىڭ تەھرىرلەش تارىخىغا خاتىرىلىنىدۇ.",
'summary-preview'                  => 'ئۈزۈندە ئالدىن كۆزىتىش:',
'blockedtitle'                     => 'ئىشلەتكۈچى چەكلەنگەن',
'blockednoreason'                  => 'سەۋەبى يوق',
'loginreqlink'                     => 'تىزىمغا كىر',
'newarticle'                       => '(يېڭى)',
'newarticletext'                   => 'سىز تېخى قۇرۇلمىغان بەتكە كىردىڭىز.
 بۇ بەتنى قۇرسىڭىز، تۆۋەندىكى تەھرىرلەش رامكىسىغا مەزمۇن كىرگۈزۈڭ(تەپسىلاتىنى  [[{{MediaWiki:ياردەم بېتى}}|ياردەم بېتى]]دىن كۆرۈڭ)',
'noarticletext'                    => 'بۇ بەتتە ھازىرچە مەزمۇن يوق.
 سىز باشقا بەتتە [[Special:Search/{{PAGENAME}}|بۇ بەتنىڭ ماۋزۇسىنى ئىزدىيەلەيسىز]] ياكى 
<span class=\\"plainlinks\\">[{{fullurl:Special:Log|page={{urlencode:{{FULLPAGENAME}}}}}} مۇناسىۋەتلىك خاتىرىسىنى ئىزدىيەلەيسىز،],
[{{fullurl:{{FULLPAGENAME}}|action=edit}} بۇ بەتنى تەھرىرلىيەلەيسىز]</span>',
'note'                             => "'''ئىزاھات:'''",
'previewnote'                      => "'''ئېسىڭىزدە بولسۇنكى بۇ پەقەتلا ئالدىن كۆزىتىش.'''
ئۆزگەرتكەن مەزمۇنىڭىز تېخى ساقلانمىدى!",
'editing'                          => '$1 تەھرىرلەۋاتىدۇ',
'editingsection'                   => '$1 تەھرىرلەۋاتىدۇ (ئابزاس)',
'copyrightwarning'                 => "دىققەت، سىزنىڭ {{SITENAME}} دىكى بارلىق تۆھپىلىرىڭىز $2 دا ئېلان قىلىنىدۇ دەپ قارىلىدۇ ($1 نىڭ تەپسىلاتىدىن كۆرۈڭ).
ئەگەر يازمىڭىزنىڭ خالىغانچە ئۆزگەرتىلىشى ياكى قايتا تارقىلىشىنى خالىمىسىڭىز، يوللىماڭ. <br />
 سىز يوللىغان مەزمۇننىڭ ئۆزىڭىزنىڭ يازغانلىقى ياكى يەرلىك تور دائىرىسىدىن ياكى ئەركىن مەنبەدىن كەلگەنلىكىگە كاپالەتلىك قىلىڭ. 
'''ئىجازەتكە ئېرىشمەي تۇرۇپ يوللىماڭ!'''",
'templatesused'                    => 'بۇ بەتتە ئىشلىتىلگەن قېلىپ:',
'templatesusedpreview'             => 'بۇ قېتىملىق ئالدىن كۆزىتىشكە ئىشلەتكەن قېلىپلار:',
'template-protected'               => '(قوغدالغان)',
'template-semiprotected'           => '(يېرىم قوغدالغان)',
'hiddencategories'                 => 'بۇ بەت $1 يوشۇرۇن تۈرنىڭ ئەزالىرىغا تەۋە:',
'permissionserrorstext-withaction' => '{{PLURAL:$1|سەۋەب|سەۋەبلەر}} تۈپەيلىدىن $2 مەشغۇلاتى ئېلىپ بېرىش ھوقۇقىڭىز يوق:',

# History pages
'viewpagelogs'           => 'بۇ بەتنىڭ خاتىرىسىنى كۆرسەت',
'currentrev-asof'        => '$1 نىڭ نۆۋەتتىكى تۈزىتىلگەن نەشرى',
'revisionasof'           => '$1 تۈزەتكەن نەشرى',
'previousrevision'       => '← كونا نەشرى',
'nextrevision'           => 'يېڭىراق تۈزىتىش →',
'currentrevisionlink'    => 'نۆۋەتتىكى تۈزىتىش',
'cur'                    => 'نۆۋەتتىكى',
'last'                   => 'ئالدى',
'histlegend'             => "پەرق تاللاش: سېلىشتۇرىدىغان ئۆزگەرتىلگەن نەشرىنىڭ يەككە تاللاش كۇنۇپكىسىغا بەلگە سېلىپ، ئاستىدىكى كۇنۇپكىنى چېكىپ سېلىشتۇرۇڭ. <br />
چۈشەندۈرۈش: '''({{int:cur}})'' نۆۋەتتىكى نەشرى بىلەن سېلىشتۇرۇشنى كۆرسىتىدۇ.
 '''({{int:last}})''' ئالدىنقى ئۆزگەرتىلگەن نەشرى بىلەن سېلىشتۇرۇشنى كۆرسىتىدۇ.
 '''{{int:minoreditletter}}''' ئازراقلا ئۆزگەرتىش.",
'history-fieldset-title' => 'كۆز يۈگۈرتۈش تارىخى',
'histfirst'              => 'دەسلەپكى',
'histlast'               => 'ئاخىرقى',

# Revision deletion
'rev-delundel'   => 'كۆرسەت/يوشۇر',
'revdel-restore' => 'كۆرۈنۈشچانلىقنى ئۆزگەرت',
'pagehist'       => 'بەتنىڭ تارىخى',

# Merge log
'revertmerge' => 'پارچىلا',

# Diffs
'history-title'           => '"$1" نىڭ ئۆزگەرتىش خاتىرىسى',
'difference'              => '(تۈزەتكەن نەشرىلىرىنىڭ پەرقى)',
'lineno'                  => '$1 -قۇر:',
'compareselectedversions' => 'تاللانغان نەشرىنى سېلىشتۇر',
'editundo'                => 'يېنىۋال',

# Search results
'searchresults'             => 'ئىزدەش نەتىجىسى',
'searchresults-title'       => '"$1" نىڭ ئىزدەش نەتىجىسى',
'searchresulttext'          => '{{SITENAME}}ھەققىدىكى تەپسىلىي ئۇچۇرغا ئېرىشمەكچى بولسىڭىز، [[{{MediaWiki:Helppage}}|{{int:help}}]]نى كۆرۈڭ',
'searchsubtitle'            => '\'\'\'[[:$1]]\'\'\' ([[Special:Prefixindex/$1|"$1" بىلەن باشلانغان بارلىق تور بەت]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1| "$1" غا ئۇلانغان بەتنى]]) ئىزدە',
'searchsubtitleinvalid'     => "'''$1''' ئىزدە",
'noexactmatch'              => "'''\"\$1\" ماۋزۇلۇق بەت تېپىلمىدى.'''
سىز [[:\$1|  بۇ بەتنى قۇرالايسىز]].",
'noexactmatch-nocreate'     => "'''\"\$1\"ماۋزۇلۇق بەت تېپىلمىدى.'''",
'notitlematches'            => 'بەت ماۋزۇسىغا ماس كېلىدىغان تۈر يوق',
'notextmatches'             => 'ماس كېلىدىغان بەت مەزمۇنى يوق',
'prevn'                     => 'ئالدى $1',
'nextn'                     => 'كەينى $1',
'viewprevnext'              => 'كۆرسەت ($1) ($2) ($3)',
'search-result-size'        => '$1 $2 سۆز',
'search-redirect'           => '($1 قايتا نىشانلا)',
'search-section'            => '(ئابزاس $1)',
'search-suggest'            => 'ئىزدىمەكچى بولغىنىڭىز: $1',
'search-interwiki-caption'  => 'ھەمشىرە قۇرۇلۇشلار',
'search-interwiki-default'  => '$1 نەتىجە:',
'search-interwiki-more'     => '(تېخىمۇ كۆپ)',
'search-mwsuggest-enabled'  => 'تەكلىپ بار',
'search-mwsuggest-disabled' => 'تەكلىپ يوق',
'showingresultstotal'       => "تۆۋەندە '''$1'''{{PLURAL:$4|- $2'''}}- تۈر، جەمئى '''$3''' تۈرنىڭ نەتىجىسىنى كۆرسىتىدۇ",
'nonefound'                 => "'''دىققەت''': كۆڭۈلدىكى ئەھۋالدا بىر قىسىم ئات بوشلۇقى بەتلىرىلا ئىزدىلىدۇ.
ئىزدەش جۈملىڭىزنىڭ ئالدىغا ''all:'' ئالدى قوشۇلغۇچىسى قوشۇپ سىناڭ، بۇنداق بولغاندا ھەممە بەت (مۇنازىرە بېتى، قېلىپ قاتارلىقلارنى ئۆز ئىچىگە ئالىدۇ)تىن ئىزدەيدۇ، ياكى لازىملىق ئات بوشلۇقى ئالدى قوشۇلغۇچى قىلىنسىمۇ بولىدۇ.",
'powersearch'               => 'ئالىي ئىزدەش',
'powersearch-legend'        => 'ئالىي ئىزدەش',
'powersearch-ns'            => 'ئات بوشلۇقىدىن ئىزدە:',
'powersearch-redir'         => 'قايتا نىشانلانغان بەت تىزىملىكى',
'powersearch-field'         => 'ئىزدە',

# Preferences page
'preferences'               => 'مايىللىق',
'mypreferences'             => 'مايىللىق تەڭشىكىم',
'searchresultshead'         => 'ئىزدەش',
'timezoneregion-asia'       => 'ئاسىيا',
'timezoneregion-europe'     => 'ياۋروپا',
'youremail'                 => 'ئېلخەت:',
'username'                  => 'ئىشلەتكۇچى ئىسمى:',
'yourrealname'              => 'ﺗﻮﻟﯘﻕ ئىسىم:',
'yourlanguage'              => 'تىل:',
'yournick'                  => 'ئىمزا:',
'yourgender'                => 'جىنسى:',
'gender-unknown'            => 'بەلگىلەنمىگەن',
'gender-male'               => 'ئەر',
'gender-female'             => 'ئايال',
'email'                     => 'ئېلخەت',
'prefs-help-email-required' => 'ئېلخەت ئارېس زۆرۈر.',

# Groups
'group-sysop' => 'باشقۇرغۇچى',

'grouppage-sysop' => '{{ns:project}}:باشقۇرغۇچى',

# User rights log
'rightslog' => 'ئىشلەتكۈچى ھوقۇق خاتىرىسى',

# Associated actions - in the sentence "You do not have permission to X"
'action-edit' => 'بۇ بەتنى تەھرىرلە',

# Recent changes
'nchanges'                       => '$1 قېتىملىق ئۆزگەرتىش',
'recentchanges'                  => 'يېقىنقى ئۆزگەرتىشلەر',
'recentchanges-legend'           => 'يېقىنقى ئۆزگەرتىش تاللانمىسى',
'recentchanges-feed-description' => 'بۇ قانالنىڭ wiki دىكى يېقىنقى ئۆزگىرىشىنى ئىز قوغلا.',
'rcnote'                         => "تۆۋەندىكى $4 $5 يېقىنقى '''$2''' كۈن ئىچىدىكى '''$1''' قېتىملىق ئۆزگەرتىش خاتىرىسى",
'rclistfrom'                     => '$1 دىن باشلانغان يېڭى ئۆزگەرتىشنى كۆرسەت',
'rcshowhideminor'                => '$1 ئازراقلا تەھرىر',
'rcshowhidebots'                 => '$1 ماشىنا ئادەمنىڭ تەھرىرى',
'rcshowhideliu'                  => '$1 تىزىمغا كىرگەن ئىشلەتكۈچىنىڭ تەھرىرى',
'rcshowhideanons'                => '$1 ئىمزاسىز ئىشلەتكۈچى تەھرىرى',
'rcshowhidemine'                 => '$1 مېنىڭ تەھرىرىم',
'rclinks'                        => 'يېقىنقى $2 كۈن ئىچىدىكى ئەڭ يېڭى  $1 قېتىملىق ئۆزگەرتىشنى كۆرسەت. <br />$3',
'diff'                           => 'پەرق',
'hist'                           => 'تارىخ',
'hide'                           => 'يوشۇر',
'show'                           => 'كۆرسەت',
'minoreditletter'                => 'ئازراقلا',
'newpageletter'                  => 'يېڭى',
'boteditletter'                  => 'ماشىنا ئادەم',
'rc-enhanced-expand'             => 'تەپسىلاتىنى كۆرسەت (JavaScript قوللىشى زۆرۈر)',
'rc-enhanced-hide'               => 'تەپسىلاتىنى يوشۇر',

# Recent changes linked
'recentchangeslinked'         => 'مۇناسىۋەتلىك ئۆزگەرتىشلەر',
'recentchangeslinked-title'   => '"$1" مۇناسىۋەتلىك ئۇلانما ئۆزگەردى',
'recentchangeslinked-summary' => "بۇ ئالاھىدە بەت يۈزى كۆرسەتكەن بەتتىن ئۇلىنىپ چىققان يېقىنقى ئۆزگەرتىش تىزىملىكى (ياكى ئالاھىدە تۈرنىڭ ئەزاسى).
 [[Special:Watchlist|كۆزەت تىزىملىكىڭىز]] دىكى بەت يۈزى '''توم''' كۆرسىتىلىدۇ.",
'recentchangeslinked-page'    => 'بەت ئاتى:',
'recentchangeslinked-to'      => 'بېرىلگەن بەتكە ئۇلانغان ئۆزگەرتىشنى كۆرسەت',

# Upload
'upload'        => 'ھۆججەت يۈكلە',
'uploadlogpage' => 'خاتىرە يۈكلە',
'uploadedimage' => '"[[$1]]" يۈكلەنگەن',

# File description page
'filehist'                  => 'ھۆججەت تارىخى',
'filehist-help'             => 'چېسلا/ۋاقىت چېكىلسە ئەينى ۋاقىتتا كۆرۈلگەن ھۆججەتنى كۆرسىتىدۇ.',
'filehist-current'          => 'نۆۋەتتىكى',
'filehist-datetime'         => 'چېسلا/ۋاقىت',
'filehist-thumb'            => 'كىچىك سۈرەت',
'filehist-thumbtext'        => '$1 نىڭ كىچىك سۈرەت نەشرى',
'filehist-user'             => 'ئىشلەتكۈچى',
'filehist-dimensions'       => 'ئۆلچەم',
'filehist-comment'          => 'ئىزاھات',
'imagelinks'                => 'ھۆججەت ئۇلىنىشى',
'linkstoimage'              => 'تۆۋەندىكى $1 بەت بۇ ھۆججەتكە ئۇلانغان:',
'sharedupload'              => 'بۇ ھۆججەت $1 دىن كەلگەن، ئۇ باشقا قۇرۇلۇشتا ئىشلىتىلىۋاتقان بولۇشى مۇمكىن.',
'uploadnewversion-linktext' => 'بۇ ھۆججەتنىڭ يېڭى نەشرىنى يۈكلە',

# Statistics
'statistics' => 'ستاتىستىكا',

# Miscellaneous special pages
'nbytes'            => '$1 بايت',
'nmembers'          => '$1 ئەزا',
'prefixindex'       => 'ھەممە بەتنىڭ ئالدى قوشۇلغۇچىسى',
'newpages'          => 'يېڭى بەتلەر',
'newpages-username' => 'ئىشلەتكۇچى ئىسمى:',
'move'              => 'يۆتكە',
'movethispage'      => 'بۇ بەتنى يۆتكە',
'pager-newer-n'     => 'يېڭى $1 قېتىم',
'pager-older-n'     => 'كونا $1 قېتىم',

# Book sources
'booksources'               => 'كىتاب مەنبەسى',
'booksources-search-legend' => 'كىتاب مەنبەسى ئىزدە',
'booksources-go'            => 'يۆتكەل',

# Special:Log
'log' => 'خاتىرە',

# Special:AllPages
'allpages'       => 'ھەممە بەت',
'alphaindexline' => '$1 دىن $2',
'prevpage'       => 'ئالدىنقى بەت ($1)',
'allpagesfrom'   => 'باشلانغان بەتنى كۆرسەت:',
'allpagesto'     => 'بۇ جايدىن ئاياغلاشقان بەتنى كۆرسەت:',
'allarticles'    => 'ھەممە بەت',
'allpagessubmit' => 'يۆتكەل',

# Special:LinkSearch
'linksearch'    => 'سىرتقى ئۇلانما',
'linksearch-ok' => 'ئىزدەش',

# Special:Log/newusers
'newuserlogpage'          => 'ئىشلەتكۈچى قۇرغان خاتىرە',
'newuserlog-create-entry' => 'يېڭى ئىشلەتكۈچى ھېساباتى',

# Special:ListGroupRights
'listgrouprights-members' => '(ئەزالار تىزىملىكى)',

# E-mail user
'emailuser' => 'بۇ ئىشلەتكۈچىگە ئېلخەت يوللا',

# Watchlist
'watchlist'         => 'كۆزەت تىزىملىكىم',
'mywatchlist'       => 'كۆزەت تىزىملىكىم',
'watchlistfor'      => "('''$1''' نىڭ كۆزەت تىزىملىكى)",
'addedwatch'        => 'كۆزەت تىزىملىكىگە قوشۇلدى',
'addedwatchtext'    => "\"[[:\$1]]\" بېتى [[Special:Watchlist|كۆزەت تىزىملىكى]]ڭىزگە قوشۇلدى.
كەلگۈسىدە بۇ بەت ۋە ئۇنىڭ مۇنازىرە بېتىگە مۇناسىۋەتلىك ھەر قانداق ئۆزگەرتىش شۇ جايدا كۆرسىتىلىپلا قالماستىن، 
بەلكى  [[Special:RecentChanges|يېقىنقى ئۆزگەرتىش تىزىملىكى]]دىمۇ تېخىمۇ ئاسان پەرقلەندۈرۈش ئۈچۈن '''توم''' شەكلىدە كۆرسىتىلىدۇ.",
'removedwatch'      => 'كۆزەت تىزىملىكىدىن چىقىرىۋەتتى',
'removedwatchtext'  => '"[[:$1]]" بېتى [[Special:Watchlist|كۆزەت تىزىملىكىڭىز]]دىن چىقىرىۋېتىلدى.',
'watch'             => 'كۆزەت',
'watchthispage'     => 'بۇ بەتنى كۆزەت',
'unwatch'           => 'كۆزەت قىلما',
'watchlist-details' => 'كۆزەت تىزىملىكىڭىزدە $1 بەت بار، مۇنازىرە بېتىنى ئۆز ئىچىگە ئالمايدۇ.',
'wlshowlast'        => 'يېقىنقى $1 سائەت $2 كۈن $3 نىڭ ئۆزگەرتىشىنى كۆرسەت',
'watchlist-options' => 'كۆزەت تىزىملىك تاللانما',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'كۆزەت قىلىۋاتىدۇ…',
'unwatching' => 'كۆزەت قىلمايۋاتىدۇ…',

# Delete
'deletepage'            => 'بەت ئۆچۈر',
'confirmdeletetext'     => 'سىز بىر بەتنىڭ ھەممە تارىخىنى قوشۇپ ئۆچۈرمەكچى.
بۇ مەشغۇلاتنى جەزملەپ، ئاقىۋىتىنى چۈشىنىڭ، شۇنىڭ بىلەن بىللە سىزنىڭ قىلمىشىڭىز  [[{{MediaWiki:Policy-url}}|شەخسىيەت تاكتىكىسى]] غا ئۇيغۇن بولسۇن.',
'actioncomplete'        => 'مەشغۇلات تامام',
'deletedtext'           => '"<nowiki>$1</nowiki>" ئۆچۈرۈلدى.
 يېقىندا ئۆچۈرۈلگەن خاتىرىنى $2 دىن كۆرۈڭ.',
'deletedarticle'        => '"[[$1]]"ئۆچۈرۈلگەن',
'dellogpage'            => 'ئۆچۈرۈش خاتىرىسى',
'deletecomment'         => 'ئۆچۈرۈش سەۋەبى:',
'deleteotherreason'     => 'باشقا/قوشۇمچە سەۋەب:',
'deletereasonotherlist' => 'باشقا سەۋەب',

# Rollback
'rollbacklink' => 'ئەسلىگە قايتۇر',

# Protect
'protectlogpage'              => 'قوغداش خاتىرىسى',
'protectedarticle'            => 'قوغدالغان "[[$1]]"',
'modifiedarticleprotection'   => '"[[$1]]" نىڭ قوغداش دەرىجىسى ئۆزگەردى',
'protectcomment'              => 'ئىزاھات:',
'protectexpiry'               => 'قەرەلى:',
'protect_expiry_invalid'      => 'قەرەلى توشۇش ۋاقتى ئىناۋەتسىز.',
'protect_expiry_old'          => 'قەرەلى توشۇش ۋاقتى ئۆتۈپ كەتكەن.',
'protect-unchain'             => 'يۆتكەش ھوقۇقى قۇلۇپى ئېچىلدى',
'protect-text'                => "بۇ جايدا '''<nowiki>$1</nowiki>''' بېتىنىڭ قوغداش دەرىجىسىنى كۆرەلەيسىز ۋە ئۆزگەرتەلەيسىز.",
'protect-locked-access'       => "ھېساباتىڭىزنىڭ بەت قوغداش دەرىجىسىنى ئۆزگەرتىش ھوقۇقى يوق.
تۆۋەندىكىسى '''$1''' بەتنىڭ نۆۋەتتىكى تەڭشىكى:",
'protect-cascadeon'           => 'تۆۋەندىكى {{PLURAL:$1|بىر|بىر قانچە}} بەت مەزكۇر بەتنى ئۆز ئىچىگە ئېلىش بىلەن بىللە زەنجىرسىمان قوغداش قوزغىتىلغان.
 شۇڭلاشقا بۇ بەتمۇ قوغدالغان. بۇ بەتنىڭ قوغداش دەرىجىسىنى ئۆزگەرتەلەيسىز، ئەمما زەنجىرسىمان قوغداشقا تەسىر كۆرسەتمەيدۇ.',
'protect-default'             => 'ھەممە ئىشلەتكۈچىگە يول قوي',
'protect-fallback'            => '"$1" نىڭ ئىجازىتى زۆرۈر.',
'protect-level-autoconfirmed' => 'يېڭى ۋە تىزىملاتمىغان ئىشلەتكۈچى چەكلىنىدۇ',
'protect-level-sysop'         => 'باشقۇرغۇچىلا',
'protect-summary-cascade'     => 'زەنجىرسىمان قۇلۇپ',
'protect-expiring'            => ' $1 (UTC) توختىتىلغان',
'protect-cascade'             => 'بۇ بەت ئۆز ئىچىگە ئالغان بەتنى قوغدا (زەنجىرسىمان قۇلۇپتا قوغدالغان).',
'protect-cantedit'            => 'سىز بۇ بەتنىڭ قوغداش دەرىجىسىنى ئۆزگەرتەلمەيسىز، چۈنكى ئۇنى تەھرىرلەش ھوقۇقىڭىز يوق.',
'restriction-type'            => 'ھوقۇق چېكى:',
'restriction-level'           => 'چەكلىمە دەرىجىسى:',

# Undelete
'undeletelink'           => 'كۆرسەت/ئەسلىگە كەلتۈر',
'undeletedarticle'       => '"[[$1]]" ئەسلىگە كەلتۈرۈلدى',
'undelete-search-submit' => 'ئىزدەش',

# Namespace form on various pages
'namespace'      => 'ئات بوشلۇقى',
'invert'         => 'ئەكسىچە تاللا',
'blanknamespace' => '(ئاساسىي)',

# Contributions
'contributions'       => 'ئىشلەتكۈچى تۆھپىسى',
'contributions-title' => '$1 نىڭ ئىشلەتكۈچى تۆھپىسى',
'mycontris'           => 'تۆھپەم',
'contribsub2'         => '$1 نىڭ تۆھپىسى ($2)',
'uctop'               => '(ئۈستى)',
'month'               => 'ئايدىن بۇيان (ياكى ئىلگىرى):',
'year'                => 'يىلدىن بۇيان (ياكى ئىلگىرى):',

'sp-contributions-newbies'  => 'يېڭى قۇرۇلغان ئىشلەتكۈچى تۆھپىسىنىلا كۆرسەت',
'sp-contributions-blocklog' => 'چەكلەنگەن خاتىرە',
'sp-contributions-search'   => 'تۆھپە ئىزدە',
'sp-contributions-username' => 'IP ئادرېس ياكى ئىشلەتكۈچى ئاتى:',
'sp-contributions-submit'   => 'ئىزدە',

# What links here
'whatlinkshere'            => 'بۇ جايدىكى ئۇلانما',
'whatlinkshere-title'      => '"$1" بەتكە ئۇلانغان',
'whatlinkshere-page'       => 'بەت:',
'linkshere'                => "تۆۋەندىكى بەتلەر '''[[:$1]]'''غا ئۇلانغان:",
'isredirect'               => 'قايتا نىشان بەلگىلەنگەن بەت',
'istemplate'               => 'ئۆز ئىچىگە ئالغان',
'isimage'                  => 'سۈرەت ئۇلانما',
'whatlinkshere-prev'       => 'ئالدى $1',
'whatlinkshere-next'       => 'كەينى $1',
'whatlinkshere-links'      => '← ئۇلانما',
'whatlinkshere-hideredirs' => '$1 قايتا نىشان بەلگىلە',
'whatlinkshere-hidetrans'  => '$1 ئۆز ئىچىگە ئالغان',
'whatlinkshere-hidelinks'  => '$1 ئۇلانما',
'whatlinkshere-filters'    => 'سۈزگۈچلەر',

# Block/unblock
'blockip'                  => 'چەكلەنگەن ئىشلەتكۈچى',
'ipboptions'               => '2 سائەت:2 hours,1 كۈن:1 day,3 كۈن:3 days,1 ھەپتە:1 week,2 ھەپتە:2 weeks,1 ئاي:1 month,3 ئاي:3 months,6 ئاي:6 months,1 يىل:1 year,چەكسىز:infinite',
'ipblocklist'              => 'چەكلەنگەن IP ئادرېس ۋە ئىشلەتكۈچى ئاتى',
'ipblocklist-submit'       => 'ئىزدەش',
'blocklink'                => 'چەكلە',
'unblocklink'              => 'چەكلەشنى توختات',
'change-blocklink'         => 'ئۆزگەرتىش چەكلەنگەن',
'contribslink'             => 'تۆھپىكارلار',
'blocklogpage'             => 'چەكلەنگەن خاتىرە',
'blocklogentry'            => '[[$1]] چەكلەندى، قەرەلى توشۇش ۋاقتى $2 $3',
'unblocklogentry'          => '$1 چەكلەش بىكار قىلىنغان',
'block-log-flags-nocreate' => 'ھېسابات قۇرۇش چەكلەنگەن',

# Move page
'movepagetext'     => "تۆۋەندىكى جەدۋەلنى ئىشلىتىپ بىر بەتنىڭ ئاتىنى ئۆزگەرتىپ، شۇنىڭ بىلەن بىللە تۈزىتىش نەشر تارىخىنى يېڭى بەتكە يۆتكەڭ.
كونا بەت يېڭى بەتنىڭ قايتا نىشان بەلگىلەنگەن بېتى بولىدۇ.
سىز بەلگىلەنگەن ئەسلىدىكى ماۋزۇنىڭ قايتا نىشان بەلگىلىشىنى يېڭىلىيالايسىز.
ئەگەر تاللىمىسىڭىز، [[Special:DoubleRedirects|قوش]] ياكى [[Special:BrokenRedirects|بۇزۇلغان قايتا نىشان بەلگىلەش]]نى تەكشۈرۈڭ.
سىز بارلىق ئۇلانمىلارنىڭ يەنىلا بەلگىلەنگەن بەتكە ئۇلىنىشىغا كاپالەتلىك قىلىشقا مەسئۇل بولۇشىڭىز لازىم.

دىققەت، ئەگەر يېڭى بەتتە مەزمۇن بولسا، بەت '''ھەرگىز'''يۆتكەلمەيدۇ،
يېڭى بەتنىڭ مەزمۇنى يوق بولسا ياكى قايتا نىشان بەلگىلەش بېتى بولمىسا ھەمدە تۈزىتىش تارىخى بولمىسا ئاندىن بولىدۇ.
بۇ زۆرۈر تېپىلغاندا يېڭى بەتكە يۆتكىگەندىن كېيىن ئاندىن كونا بەتنى يۈتكىسىڭىز بولىدىغانلىقىنى بىلدۈرىدۇ،
شۇنىڭ بىلەن بىللە مەۋجۇد بەتنى قاپلىۋېتەلمەيسىز.

'''ئاگاھلاندۇرۇش!'''
دائىم زىيارەت قىلىنىدىغان تور ؛بەتكە نىسبەتەن بۇ زور ياكى  ئويلانمايلا ئېلىپ بېرىلغان ئۆزگەرتىش
مەشغۇلات قىلىشتىن ئىلگىرى ئېلىپ كېلىدىغان ئاقىۋىتىنى چۈشىنىڭ.",
'movepagetalktext' => "مۇناسىۋەتلىك مۇنازىرە بېتى مۇشۇ بەت بىلەن بىللە ئۆزلۈكىدىن يۆتكىلىدۇ، ئۇنداق '''بولمىغاندا:'''
*يېڭى بەتنىڭ مەزمۇنى بار مۇنازىرە بېتى مەۋجۇد ياكى 
*تۆۋەندىكى كۆپ تاللاش رامكىسىنى تاللىمىدىڭىز.

بۇ خىل ئەھۋاللاردا، زۆرۈر تېپىلغاندا ئۆزىڭىز بەتنى يۆتكىشىڭىز ياكى بىرلەشتۈرۈشىڭىز لازىم.",
'movearticle'      => 'بەت يۆتكە:',
'newtitle'         => 'يېڭى ماۋزۇ:',
'move-watch'       => 'بۇ بەتنى كۆزەت',
'movepagebtn'      => 'بەت يۆتكە',
'pagemovedsub'     => 'مۇۋەپپەقىيەتلىك يۆتكەلدى',
'movepage-moved'   => '<big>\'\'\'"$1" دىن\\"$2" گە يۆتكەلدى\'\'\'</big>',
'articleexists'    => 'مۇشۇ ئاتلىق بەت مەۋجۇد ياكى سىز تاللىغان بەت ئاتى ئىناۋەتسىز.
باشقا ئات تاللاڭ.',
'talkexists'       => "'''بەتنىڭ ئۆزىنى يۆتكەش مۇۋەپپەقىيەتلىك، ئەمما مۇنازىرە بېتىنى يۆتكىيەلمىدى،چۈنكى يېڭى ماۋزۇلۇق مۇنازىرە بېتى مەۋجۇد.
ئۇلارنى ئۆزىڭىز قولدا بىرلەشتۈرۈڭ.'''",
'movedto'          => 'يۆتكەلگەن ئورنى',
'movetalk'         => 'يۆتكەش ئۇلانغان مۇنازىرە بېتى',
'1movedto2'        => '[[$1]] دىن [[$2]] غا يۆتكەلدى',
'1movedto2_redir'  => 'قايتا نىشان بەلگىلەپ [[$1]] دىن [[$2]] غا يۆتكەلدى',
'movelogpage'      => 'خاتىرىنى يۆتكە',
'movereason'       => 'سەۋەب:',
'revertmove'       => 'قايتۇر',

# Export
'export' => 'بەت چىقار',

# Thumbnails
'thumbnail-more' => 'چوڭايت',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'ئىشلەتكۈچى بېتىڭىز',
'tooltip-pt-mytalk'               => 'مۇنازىرە بېتىڭىز',
'tooltip-pt-preferences'          => 'مايىللىق تەڭشىىڭىز',
'tooltip-pt-watchlist'            => 'ئۆزگىرىشنى كۆزەت قىلىۋاتقان بەت تىزىملىكىڭىز',
'tooltip-pt-mycontris'            => 'تۆھپە تىزىملىكىڭىز',
'tooltip-pt-login'                => 'تىزىمغا كىرىشىڭىزنى تەۋسىيە قىلىمىز ئەمما مەجبۇرىي ئەمەس',
'tooltip-pt-logout'               => 'تىزىمدىن چىق',
'tooltip-ca-talk'                 => 'بەت مەزمۇنى ھەققىدىكى مۇنازىرە',
'tooltip-ca-edit'                 => 'بۇ بەتنى تەھرىرلىيەلەيسىز.
ساقلاشتىن ئىلگىرى ئالدىن كۆزەت كۇنۇپكىسىنى ئىشلىتىڭ',
'tooltip-ca-addsection'           => 'يېڭى بىر سۆزلىشىش باشلا',
'tooltip-ca-viewsource'           => 'بۇ بەت قوغدالغان.
مەنبە ھۆججىتىنى كۆرەلەيسىز',
'tooltip-ca-history'              => 'بۇ بەتنىڭ بۇرۇنقى نەشرى',
'tooltip-ca-protect'              => 'بۇ بەتنى قوغدا',
'tooltip-ca-delete'               => 'بۇ بەتنى ئۆچۈر',
'tooltip-ca-move'                 => 'بۇ بەتنى يۆتكە',
'tooltip-ca-watch'                => 'بۇ بەتنى كۆزەت تىزىملىكىگە قوش',
'tooltip-ca-unwatch'              => 'بۇ بەتنى كۆزەت تىزىملىكىمدىن چىقىرىۋەت',
'tooltip-search'                  => '{{SITENAME}} ئىزدە',
'tooltip-search-go'               => 'ئەگەر بۇ ئاتتىكى بەت مەۋجۇد بولسا شۇ بەتكە يۆتكەل',
'tooltip-search-fulltext'         => 'بۇ تېكست بار بەتنى ئىزدە',
'tooltip-n-mainpage'              => 'باش بەتنى زىيارەت قىل',
'tooltip-n-portal'                => 'بۇ قۇرۇلۇش ھەققىدە، سىز نېمە ئىش قىلالايسىز، قانداق قىلىش لازىم',
'tooltip-n-currentevents'         => 'نۆۋەتتىكى ھادىسىنىڭ ئارقا كۆرۈنۈش ئۇچۇرىنى ئىزدە',
'tooltip-n-recentchanges'         => 'wiki بېتىدىكى يېقىنقى ئۆزگىرىش تىزىملىكى',
'tooltip-n-randompage'            => 'خالىغان بەتنى يۈكلە',
'tooltip-n-help'                  => 'ياردەم ئىزدەيدىغان ئورۇن',
'tooltip-t-whatlinkshere'         => 'بۇ جايغا ئۇلانغان ھەممە wiki بېتىنى كۆرسەت',
'tooltip-t-recentchangeslinked'   => 'بۇ بەتكە ئۇلانغان بەتنىڭ يېقىنقى ئۆزگىرىشى',
'tooltip-feed-rss'                => 'بۇ بەتنىڭ RSS قانىلى',
'tooltip-feed-atom'               => 'بۇ بەتنىڭ Atom قانىلى',
'tooltip-t-contributions'         => 'بۇ ئىشلەتكۈچىنىڭ تۆھپە تىزىملىكىنى كۆرسەت',
'tooltip-t-emailuser'             => 'بۇ ئىشلەتكۈچىگە ئېلخەت يوللا',
'tooltip-t-upload'                => 'ھۆججەتلەرنى يۈكلە',
'tooltip-t-specialpages'          => 'بارلىق ئالاھىدە بەتلەر تىزىملىكى',
'tooltip-t-print'                 => 'بۇ بەتنىڭ باسقىلى بولىدىغان نەشرى',
'tooltip-t-permalink'             => 'ئۆزگەرتىلگەن نەشرىدىكى بۇ بەتنىڭ مەڭگۈلۈك ئۇلانمىسى',
'tooltip-ca-nstab-main'           => 'مەزمۇن بېتىنى كۆرسەت',
'tooltip-ca-nstab-user'           => 'ئىشلەتكۈچى بېتىنى كۆرسەت',
'tooltip-ca-nstab-special'        => 'بۇ ئالاھىدە بەت، بۇ بەتنى تەھرىرلىيەلمەيسىز.',
'tooltip-ca-nstab-project'        => 'قۇرۇلۇش بېتىنى كۆرسەت',
'tooltip-ca-nstab-image'          => 'ھۆججەت بېتى كۆرسەت',
'tooltip-ca-nstab-template'       => 'قېلىپ كۆرسەت',
'tooltip-ca-nstab-category'       => 'تۈر بېتىنى كۆرسەت',
'tooltip-minoredit'               => 'بۇنىڭغا ئازراقلا تەھرىرلەش بەلگىسى قوي',
'tooltip-save'                    => 'ئۆزگەرتىشىڭىزنى ساقلاڭ',
'tooltip-preview'                 => 'ئۆزگەرتىشىڭىزنى ئالدىن كۆزىتىڭ، ساقلاشتىن بۇرۇن بۇ ئىقتىدارنى ئىشلىتىڭ!',
'tooltip-diff'                    => 'بۇ تېكستكە ئېلىپ بارغان ئۆزگەرتىشنى كۆرسەت',
'tooltip-compareselectedversions' => 'بۇ بەتتە تاللانغان ئىككى نەشرىنىڭ پەرقىنى كۆرسەت',
'tooltip-watch'                   => 'بۇ بەتنى كۆزەت تىزىملىكىگە قوش',
'tooltip-rollback'                => '"ئەسلىگە قايتۇر" بىر چېكىلسە ئالدىنقى تۆھپىكارنىڭ تەھرىرىلىگەن ھالىتىگە قايتۇرىدۇ.',
'tooltip-undo'                    => '\\"يېنىۋال\\"  تەھرىرلەش ھالىتىدە ئەسلىگە كەلتۈرۈش ئۈچۈن ئالدىن كۆزىتىش ھالىتىدىن تەھرىرلەشنى ئاچىدۇ
ئۇ قىسقىچە مەزمۇنغا سەۋەبىنى قوشۇشغا يول قويىدۇ.',

# Browsing diffs
'previousdiff' => '← ئالدىنقى نەشرى',
'nextdiff'     => 'يېڭى نەشرى →',

# Media information
'file-info-size'       => '($1×$2 پىكسېل، ھۆججەت چوڭلۇقى: $3، MIME تىپى: $4)',
'file-nohires'         => '<small>يۇقىرىراق پەرق ئېتىش نىسبىتى يوق.</small>',
'svg-long-desc'        => '(SVG ھۆججىتى، ئاتاقتىكى چوڭلۇقى $1×$2 نۇقتا، ھۆججەت چوڭلۇقى: $3)',
'show-big-image'       => 'تولۇق ئېنىقلىق دەرىجىسى',
'show-big-image-thumb' => '<small>بۇ ئالدىن كۆزىتىشنىڭ چوڭلۇقى: $1 × $2 نۇقتا</small>',

# Special:NewFiles
'ilsubmit' => 'ئىزدەش',

# Bad image list
'bad_image_list' => 'تۆۋەندىكى فورماتتا يېزىڭ:

پەقەت (* بىلەن باشلانغان) كۆرسىتىلگەن تۈرلەرلا ئويلىشىلىدۇ.
ھەر بىر قۇرنىڭ بىرىنچى ئۇلانمىسى چوقۇم بۇزۇلغان ھۆججەتكە ئۇلىنىشى لازىم.
 بۇ ھۆججەت قايسى بەتلەردە كۆرسىتىلىشىدىن قەتئىي نەزەر،
 ئوخشاش بىر قۇرنىڭ ئاخىرىدىكى ئۇلانما مۇستەسنا دەپ قارىلىدۇ،',

# Metadata
'metadata'          => 'مېتا سانلىق مەلۇماتى',
'metadata-help'     => 'بۇ ھۆججەت كېڭەيتىلگەن تەپسىلاتنى ئۆز ئىچىگە ئالغان. بۇ ئۇچۇرلارنى رەقەملىك ئاپپارات ياكى سكاننېر قۇرغان ياكى رەقەملەشتۈرۈش جەريانىدا قوشۇلغان بولۇشى مۇمكىن.
ئەگەر بۇ ھۆججەتنىڭ ئەسلى ھۆججىتى ئۆزگەرتىلسە، بىر قىسىم ئۇچۇرلار ئۆزگەرتىلگەندىن كېيىنكى ھۆججەتتە تولۇق ئەكس ئەتمەيدۇ.',
'metadata-expand'   => 'كېڭەيتىلگەن تەپسىلاتنى كۆرسەت',
'metadata-collapse' => 'كېڭەيتىلگەن تەپسىلاتنى يوشۇر',
'metadata-fields'   => 'بۇ ئۇچۇردا كۆرسىتىلگەن EXIF مېتا سانلىق مەلۇماتى سۆز بۆلىكى سۈرەت كۆرسىتىلىدىغان بەتتە بولىدۇ،
 مېتا سانلىق مەلۇمات بۇزۇلغاندا تۆۋەندىكى ئۇچۇرنىلا كۆرسىتىدۇ، باشقا مېتا سانلىق مەلۇماتلار كۆڭۈلدىكى ئەھۋالدا يوشۇرۇن تۇرىدۇ.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* focallength',

# External editor support
'edit-externally'      => 'بۇ ھۆججەتنى سىرتقى قوللىنىشچان پروگراممىدا تەھرىرلە',
'edit-externally-help' => '( [http://www.mediawiki.org/wiki/Manual:External_editors تەڭشەك قەدىمى] نى كۆرۈپ تەپسىلاتىنى چۈشىنىڭ)',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'ھەممىسى',
'namespacesall' => 'ھەممىسى',
'monthsall'     => 'ھەممىسى',

# action=purge
'confirm_purge_button' => 'ماقۇل',

# Multipage image navigation
'imgmultipageprev' => '← ئالدىنقى بەت',
'imgmultipagenext' => 'كېيىنكى بەت →',
'imgmultigo'       => 'كۆچۈش!',

# Table pager
'table_pager_next'         => 'كېيىنكى بەت',
'table_pager_prev'         => 'ئالدىنقى بەت',
'table_pager_first'        => 'بىرىنچى بەت',
'table_pager_last'         => 'ئەڭ ئاخىرقى بەت',
'table_pager_limit_submit' => 'كۆچۈش',

# Watchlist editing tools
'watchlisttools-view' => 'مۇناسىۋەتلىك ئۆزگەرتىشنى كۆرسەت',
'watchlisttools-edit' => 'كۆزەت تىزىملىكىنى كۆرۈپ تەھرىرلەش',
'watchlisttools-raw'  => 'ئەسلى كۆزەت تىزىملىك تەھرىرى',

# Special:SpecialPages
'specialpages' => 'ئالاھىدە بەتلەر',

# Special:Tags
'tags-edit' => 'ئۆزگەرتىش',

);
