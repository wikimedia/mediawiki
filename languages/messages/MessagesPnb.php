<?php
/** Western Punjabi (پنجابی)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Arslan
 * @author Khalid Mahmood
 * @author Rachitrali
 * @author ZaDiak
 */

$linkPrefixExtension = true;
$fallback8bitEncoding = 'windows-1256';

$rtl = true;

$messages = array(
# User preference toggles
'tog-underline'               => 'حوڑ تھلے لین:',
'tog-highlightbroken'         => 'فارمیٹ ٹٹا جوڑ <a href="" class="new">like this</a> (alternative: like this<a href="" class="internal">?</a>)',
'tog-justify'                 => 'پیراگراف ثابت کرو',
'tog-hideminor'               => 'چھوٹیاں تبدیلیاں چھپاؤ',
'tog-hidepatrolled'           => 'ویکھیاں تبدیلیاں لکاؤ',
'tog-newpageshidepatrolled'   => 'نویاں صفیاں توں ویکھیاں تبدیلیاں لکاؤ',
'tog-extendwatchlist'         => 'نظر تھلے رکھے صفحے نوں ودھاو, تاں جے اوہ تبدیلیاں جیڑیاں کم دے قابل نیں ویکھیاں جا سکن',
'tog-usenewrc'                => 'تھوڑا خر پہلے کیتیاں گیاں تبدیلیاں ورتو',
'tog-numberheadings'          => 'آپ نمبر دین والیاں سرخیاں',
'tog-showtoolbar'             => 'ایڈٹ ٹولبار وکھاؤ',
'tog-editondblclick'          => 'صفیاں تے ڈبل کلک کرن تے تبدیلیاں لیاؤ',
'tog-editsection'             => 'سیکشن ایڈیٹنگ جوڑاں نال بناؤ',
'tog-editsectiononrightclick' => 'سیکشن سرخی تے تبدیلی لیاؤ سجی کلک نال',
'tog-showtoc'                 => 'آرٹیکل دی لسٹ دسو (3 توں چوکھیاں سرخیاں والے صفیاں دی)',
'tog-rememberpassword'        => 'اس براؤزر تے میرا ورتن ناں یاد رکھو ($1 {{PLURAL:$1|دن|دناں}} واسطے)',
'tog-watchcreations'          => 'جیڈے صفحے میں بناندا واں اوہ میری اکھ تھلے کر دیو',
'tog-watchdefault'            => 'جیڈے صفحیاں چ میں لکھداں اوہ میری اکھ تھلے کر دیو',
'tog-watchmoves'              => 'جیڈے صفحے میں لے چلداں اوہ میری اکھ تھلے کر دیو',
'tog-watchdeletion'           => 'جیڈے صفحے میں مٹانداں اوہ میری اکھ تھلے کر دیو',
'tog-minordefault'            => 'ساریاں تبدیلیاں نوں نکا ڈیفالٹ نال دسو۔',
'tog-previewontop'            => 'ایڈٹ باکس توں پہلے پریویو وکھاؤ',
'tog-previewonfirst'          => 'پہلی تبدیلی تے پریویو وکھاؤ',
'tog-nocache'                 => 'براؤزر چ صفحے دی کیشنگ روک دیو',
'tog-enotifwatchlistpages'    => 'اگر میری اکھ تھلیاں صفحیاں چوں کسے چ تبدیلی ہوۓ، تے مینوں ای میل کر دیو',
'tog-enotifusertalkpages'     => 'اگر میرے گلاں باتاں آلے صفحے چ کوئی تبدیلی کرے، تے مینوں ای میل کر دیو',
'tog-enotifminoredits'        => 'صفحیاں چ چھوٹیاں موٹیاں تبدیلیاں تے وی مینوں ای میل کر دیو',
'tog-enotifrevealaddr'        => 'میرے ای میل دے پتے نوں سندیسے آلی ای میل دے وچ وکھاؤ۔',
'tog-shownumberswatching'     => 'ویکھن آلے لوکاں دی گنتی وکھاؤ۔',
'tog-oldsig'                  => 'ہن والے دسخط:',
'tog-fancysig'                => 'دستخط نوں وکی ٹیکسڈ ونگوں؎ ورتو(without an automatic link)',
'tog-externaleditor'          => 'ہمیشہ بارلا لکھن والا ورتو (ماہر لوکاں واسطے، اس واسطے تواڑے کمپیوٹر تے خاص تبدیلیاں چائیدیاں نیں۔ [http://www.mediawiki.org/wiki/Manual:External_editors مزید معلومات.])',
'tog-externaldiff'            => '
ہمیشہ بارلا تبدیلی کرن والا ورتو (ماہر لوکاں واسطے، اس واسطے تواڑے کمپیوٹر تے خاص تبدیلیاں چائیدیاں نیں۔ [http://www.mediawiki.org/wiki/Manual:External_editors مزید معلومات۔])',
'tog-showjumplinks'           => '"ایدر چلو" نوں رلن والے جوڑان نال جوڑو',
'tog-uselivepreview'          => 'لائیو پریویو ورتو',
'tog-forceeditsummary'        => 'مینون اوسے ویلے دسو جدوں خالی سمری تے آؤ۔',
'tog-watchlisthideown'        => 'میری اپنی لکھائی نوں اکھ تھلیوں لکاؤ',
'tog-watchlisthidebots'       => 'بوٹ دی لکھائی اکھ تھلیوں لکاؤ',
'tog-watchlisthideminor'      => 'چھوٹی موٹی لکھائی اکھ تھلیوں لکاؤ',
'tog-watchlisthideliu'        => 'تبدیلیاں نوں لکاؤ اوناں لاگ ان ورتن والیاں کولوں جیہڑے واچلسٹ تے نیں۔',
'tog-watchlisthideanons'      => 'تبدیلیاں واجلسٹ دے گمنام ورتن والیاں توں لکاؤ',
'tog-watchlisthidepatrolled'  => 'نکی لکھائی اکھ تھلوں لکاؤ',
'tog-ccmeonemails'            => 'مینوں اوہناں ای میلاں دیاں کاپیاں بھیجو جیہڑیاں میں دوجیاں نوں بھیجاں۔',
'tog-diffonly'                => 'تبدیلی توں علاوہ صفحہ نا وکھاؤ',
'tog-showhiddencats'          => 'لکیاں کیٹاگریاں وکھاؤ',
'tog-norollbackdiff'          => 'صفحے دی واپسی تے تبدیلی کڈ دو',

'underline-always'  => 'ہمیشہ',
'underline-never'   => 'کدی وی نئیں',
'underline-default' => 'براؤزر ڈیفالٹ',

# Font style option in Special:Preferences
'editfont-style'     => 'تبدیلی کرن ویلے دی لکھائی دا خط:',
'editfont-default'   => 'براؤزر ڈیفالٹ',
'editfont-monospace' => 'اکوجۓ حالی تھاں آلا خط',
'editfont-sansserif' => 'سانز-سیرف خط',
'editfont-serif'     => 'سیرف خط',

# Dates
'sunday'        => 'اتوار',
'monday'        => 'پیر',
'tuesday'       => 'منگل',
'wednesday'     => 'بدھ',
'thursday'      => 'جمعرات',
'friday'        => 'جمعہ',
'saturday'      => 'ہفتہ',
'sun'           => 'اتوار',
'mon'           => 'سوموار',
'tue'           => 'منگل',
'wed'           => 'بدھ',
'thu'           => 'جمعرات',
'fri'           => 'جمعہ',
'sat'           => 'ہفتہ',
'january'       => 'جنوری',
'february'      => 'فروری',
'march'         => 'مارچ',
'april'         => 'اپریل',
'may_long'      => 'مئی',
'june'          => 'جون',
'july'          => 'جولائی',
'august'        => 'اگست',
'september'     => 'ستمبر',
'october'       => 'اکتوبر',
'november'      => 'نومبر',
'december'      => 'دسمبر',
'january-gen'   => 'جنوری',
'february-gen'  => 'فروری',
'march-gen'     => 'مارچ',
'april-gen'     => 'اپریل',
'may-gen'       => 'مئی',
'june-gen'      => 'جون',
'july-gen'      => 'جولائی',
'august-gen'    => 'اگست',
'september-gen' => 'ستمبر',
'october-gen'   => 'اکتوبر',
'november-gen'  => 'نومبر',
'december-gen'  => 'دسمبر',
'jan'           => 'جنوری',
'feb'           => 'فروری',
'mar'           => 'مارچ',
'apr'           => 'اپریل',
'may'           => 'مئی',
'jun'           => 'جون',
'jul'           => 'جولائی',
'aug'           => 'اگست',
'sep'           => 'ستمبر',
'oct'           => 'اکتوبر',
'nov'           => 'نومبر',
'dec'           => 'دسمبر',

# Categories related messages
'pagecategories'                 => '{{PLURAL:$1|گٹھ|گٹھیاں}}',
'category_header'                => '"$1" کیٹاگری وچ صفحے',
'subcategories'                  => 'تھلے آلی کیٹاگری',
'category-media-header'          => 'اس "$1" کیٹاگری وچ میڈيا',
'category-empty'                 => "''اس کیٹاگری وچ کوئی صفحہ یا میڈیا موجود نہیں۔''",
'hidden-categories'              => '{{PLURAL:$1|چھپی گٹھ|چھپی گٹھیاں}}',
'hidden-category-category'       => 'لکائیاں ٹولیاں',
'category-subcat-count'          => '{{PLURAL:$2|اس گٹھ دی صرف اکو تھلے آلی نکی گٹھ اے|اس گٹھ دیاں $2 چوں   {{PLURAL:$1|نکی گٹھ|$1 نکی گٹھیاں}}}} نیں۔',
'category-subcat-count-limited'  => 'اس گٹھ چ اے {{PLURAL:$1|نکی گٹھ|$1 نکیاں گٹھاں}} نیں۔',
'category-article-count'         => '{{PLURAL:$2|اس گٹھ چ اکو تھلے آلا صفحہ اے۔|تھلے {{PLURAL:$1|آلا صفحہ|آلے صفحے}} $2 چوں اس گٹھ دے صفحے نیں۔}}',
'category-article-count-limited' => 'اس گٹھ چ اے {{PLURAL:$1|صفحہ اے|$1 صفحے نیں}}۔',
'category-file-count'            => '{{PLURAL:$2|اس گٹھ چ صرف اکو اے فائل اے۔$2 چوں، اے {{PLURAL:$1|فائل اس گٹھ چ اے|$1 فائلاں اس گٹھ چ نیں۔}}۔}}',
'category-file-count-limited'    => 'اس گٹھ چ اے {{PLURAL:$1|فائل اے|$1 فائلاں نیں}}۔',
'listingcontinuesabbrev'         => 'جاری',
'index-category'                 => 'انڈیکسڈ صفے',
'noindex-category'               => 'نان انڈیکسڈ صفے',
'broken-file-category'           => 'ٹٹے ہوۓ جوڑاں آلے صفحے',

'about'         => 'بارے چ',
'article'       => 'مضمون آلا صفحہ',
'newwindow'     => '(نئی ونڈو چ کھولو)',
'cancel'        => 'ختم',
'moredotdotdot' => 'مزید۔۔۔۔',
'mypage'        => 'میرا صفحہ',
'mytalk'        => 'میریاں گلاں',
'anontalk'      => 'اس آئی پی آسطے گل کرو',
'navigation'    => 'تلاش',
'and'           => '&#32;and',

# Cologne Blue skin
'qbfind'         => 'کھوج',
'qbbrowse'       => 'لبو',
'qbedit'         => 'لکھو',
'qbpageoptions'  => 'اے صفحہ',
'qbpageinfo'     => 'ماحول',
'qbmyoptions'    => 'میرے صفحے',
'qbspecialpages' => 'خاص صفحے',
'faq'            => 'FAQ',
'faqpage'        => 'Project:FAQ',

# Vector skin
'vector-action-addsection'       => 'مضمون پاؤ',
'vector-action-delete'           => 'مکاؤ',
'vector-action-move'             => 'ٹرو',
'vector-action-protect'          => 'بچاؤ',
'vector-action-undelete'         => 'واپس لیاؤ',
'vector-action-unprotect'        => 'تبدیلی بچاؤ',
'vector-simplesearch-preference' => 'کھوج چ چنگے مشورے آن کرو',
'vector-view-create'             => 'بناؤ',
'vector-view-edit'               => 'لکھو',
'vector-view-history'            => 'تریخ وکھاؤ',
'vector-view-view'               => 'پڑھو',
'vector-view-viewsource'         => 'ویکھو',
'actions'                        => 'کم',
'namespaces'                     => 'ناواں دی جگہ:',
'variants'                       => 'قسماں',

'errorpagetitle'    => 'مسئلہ',
'returnto'          => 'واپس $1 چلو',
'tagline'           => 'سے {{SITENAME}}',
'help'              => 'مدد',
'search'            => 'کھوج',
'searchbutton'      => 'کھوج',
'go'                => 'جاؤ',
'searcharticle'     => 'چلو جی',
'history'           => 'پچھلے کم',
'history_short'     => 'ریکارڈ',
'updatedmarker'     => 'میرے پچھلی وار آن توں مگروں دیاں تبدیلیاں',
'printableversion'  => 'چھپن آلا صفحہ',
'permalink'         => 'پکا تعلق',
'print'             => 'چھاپو',
'view'              => 'وکھالہ',
'edit'              => 'لکھو',
'create'            => 'بناؤ',
'editthispage'      => 'اس صفحہ تے لکھو',
'create-this-page'  => 'اے صفحہ بناؤ',
'delete'            => 'مٹاؤ',
'deletethispage'    => 'اے صفحہ مٹاؤ',
'undelete_short'    => 'مٹانا واپس {{انیک:$1|اکتبدیلی|$1 تبدیلی}}',
'viewdeleted_short' => 'ویکھو {{PLURAL:$1|اک مٹائی گئی تبدیلی|$1 مٹائیاں گئیاں تبدیلیاں}}',
'protect'           => 'بچاؤ',
'protect_change'    => 'تبدیل کرو',
'protectthispage'   => 'اے صفحہ بچاؤ',
'unprotect'         => 'اینا بچاؤ',
'unprotectthispage' => 'اے صفحہ اینا بچاؤ',
'newpage'           => 'نیا صفحہ',
'talkpage'          => 'اس صفحے دے بارے چ گل بات کرو',
'talkpagelinktext'  => 'گل بات',
'specialpage'       => 'خاص صفحہ',
'personaltools'     => 'ذاتی اوزار',
'postcomment'       => 'نویں ونڈ',
'articlepage'       => 'مضمون آلا صفحہ',
'talk'              => 'گل بات',
'views'             => 'منظر',
'toolbox'           => 'اوزار',
'userpage'          => 'ورتن آلے دا صفحہ ویکھو',
'projectpage'       => 'منصوبے آلا صفحہ ویکھو',
'imagepage'         => 'فائل آلا صفحہ ویکھو',
'mediawikipage'     => 'سنیعا آلا صفحہ ویکھو',
'templatepage'      => 'سچے آلا صفحہ ویکھو',
'viewhelppage'      => 'مدد آلا صفحہ ویکھو',
'categorypage'      => 'گٹھ آلا صفحہ ویکھو',
'viewtalkpage'      => 'گلاں باتاں وکھاؤ',
'otherlanguages'    => 'دوجیاں زبانں وچ',
'redirectedfrom'    => '(لیایا گیا $1)',
'redirectpagesub'   => 'صفحہ ریڈائریکٹ کرو',
'lastmodifiedat'    => 'This page was last modified on $1, at $2.
اس صفحے نوں آخری آری $1 تریخ نوں $2 وجے بدلیا گیا۔',
'viewcount'         => 'اس صفحے نوں {{PLURAL:$1|اک واری|$1 واری}} کھولیا گیا اے۔',
'protectedpage'     => 'بجایا صفحہ',
'jumpto'            => 'جاو:',
'jumptonavigation'  => 'مدد',
'jumptosearch'      => 'کھوج',
'view-pool-error'   => '$1',
'pool-timeout'      => 'تالے لئی انتضار',
'pool-queuefull'    => 'چنوتی کرن ل‏ئی بندے پورے نیں۔',
'pool-errorunknown' => 'انجان غلطی',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'بارے چ {{SITENAME}}',
'aboutpage'            => 'Project:بارے وچ',
'copyright'            => 'مال $1 دے تھلے ہے گا اے۔',
'copyrightpage'        => '{{ns:project}}:نقل دے حق',
'currentevents'        => 'اج کل دے واقعات',
'currentevents-url'    => 'Project:اج کل دے واقعات',
'disclaimers'          => 'منکرنا',
'disclaimerpage'       => 'Project:عام منکرنا',
'edithelp'             => 'لکھن وچ مدد',
'edithelppage'         => 'Help:لکھنا',
'helppage'             => 'Help:فہرست',
'mainpage'             => 'پہلا صفہ',
'mainpage-description' => 'پہلا صفہ',
'policy-url'           => 'Project:پالیسی',
'portal'               => 'بیٹھک',
'portal-url'           => 'Project:بیٹھک',
'privacy'              => 'حفاظتی پالیسی',
'privacypage'          => 'Project:حفاظتی پالیسی',

'badaccess'        => 'اجازت دے وچ غلطی اے',
'badaccess-group0' => 'تھاونوں ایس کم دی اجازت نیں جیہڑا تسیں آکھیا اے۔',
'badaccess-groups' => 'جیڑا کم تسی کرنا چا رۓ او اوہ صرف {{PLURAL:$2|اس گروپ|ایناں گروپاں}} دے ورتن آلے کر سکدے نیں: $1۔',

'versionrequired'     => 'میڈیا وکی دا $1 ورژن چائیدا اے۔',
'versionrequiredtext' => 'میڈیا وکی دا $1 ورژن اس صفحے نوں ویکھن واسطے چائیدا اے۔
[[Special:Version|ورژن آلا صفحہ]] وکیھو',

'ok'                      => 'ٹھیک اے',
'retrievedfrom'           => 'توں لیا "$1"',
'youhavenewmessages'      => 'تواڈے لئی $1 ($2).',
'newmessageslink'         => 'نواں سنیآ',
'newmessagesdifflink'     => 'آخری تبدیلی',
'youhavenewmessagesmulti' => 'تھاڈے ل‏ی $1 تے نوں سنیعہ اے۔',
'editsection'             => 'لکھو',
'editold'                 => 'لکھو',
'viewsourceold'           => 'لکھیا ویکھو',
'editlink'                => 'لکھو',
'viewsourcelink'          => 'لکھائی وکھاؤ',
'editsectionhint'         => 'حصہ لکھو: $1',
'toc'                     => 'حصے',
'showtoc'                 => 'کھولو',
'hidetoc'                 => 'چپھاؤ',
'collapsible-collapse'    => 'ڈگنا',
'collapsible-expand'      => 'ودھاؤ',
'thisisdeleted'           => '$1 ویکھو یا واپس لاؤ',
'viewdeleted'             => 'ویکھو $1 ؟',
'restorelink'             => '{{PLURAL:$1|اک مٹائی گئی تبدیلی|$1 مٹائیاں گئیاں تبدیلیاں}}',
'feedlinks'               => 'دسو:',
'feed-invalid'            => 'ناں منی جان والی سبسکرپشن فیڈ ٹائپ',
'feed-unavailable'        => 'سنڈیکیشن فیڈز کوئی نیں۔',
'site-rss-feed'           => '$1 RSS Feed',
'site-atom-feed'          => '$1 Atom Feed',
'page-rss-feed'           => '"$1" RSS Feed',
'page-atom-feed'          => '"$1" Atom Feed',
'red-link-title'          => '$1 (اے صفحہ حلے تک نئیں بنایا گیا)',
'sort-descending'         => 'ونڈ تھلے ول',
'sort-ascending'          => 'ونڈ اتے ول',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'صفحہ',
'nstab-user'      => 'ورتن والے دا صفحہ',
'nstab-media'     => 'میڈیا آلا صفحہ',
'nstab-special'   => 'خاص صفحہ',
'nstab-project'   => 'منصوبے دا صفحہ',
'nstab-image'     => 'فائل',
'nstab-mediawiki' => 'سنیعا',
'nstab-template'  => 'سانچہ',
'nstab-help'      => 'مدد آلا صفحہ',
'nstab-category'  => 'کیٹاگری',

# Main script and global functions
'nosuchaction'      => 'کوئی ایسا کم نئیں',
'nosuchactiontext'  => 'یو آر ایل نال دسیا کم نئیں ہوےکدا۔
تساں ہوسکدا اے یو ار ایل غلط ٹائپ کردتی ہووے۔
ایہ اک بگ نوں وی دسدا اے سوفٹویر چ جینوں {{سائٹناں}} نے ورتیا',
'nosuchspecialpage' => 'انج دا کوئی خاص صفحہ نئیں',
'nospecialpagetext' => '<سٹرانگ>تساں اک ناں منیا جان والا خاص صفہ منگیا اے.</سٹرانگ>

اکلسٹ خاص منے جان والے صفیاں تے ایتھے مل سکدی اے[[خاص:خاص صفے|{{انٹ:خاص صفے}}]].',

# General errors
'error'                => 'مسئلا',
'databaseerror'        => 'ڈیٹابیس دی غلطی',
'dberrortext'          => 'اک ڈیٹابیس کویری سنٹیکس غلطی ہوگئی اے۔
اے سوفٹویر چ اک بگ وی ہوسکدا اے۔
آخری کوشش کیتی ڈیٹابیس کھوج:
<بلاک کوٹ><ٹیٹی>$1</ٹیٹی></بلاک کوٹ>',
'dberrortextcl'        => 'اکڈیٹابیس کویری سنٹیکس غلطی ہوگئی اے
آخری ڈیٹابیس کویری سی:
"$1"
فنکش دے اندروں "$2"
ڈیٹابیس ریٹرنڈ غلطی "$3: $4"',
'laggedslavemode'      => "'''خبردار:''' صفے تے نیڑےتریڈے ہون والیاں تبدیلیاں کوئی نیں۔",
'readonly'             => 'ڈیٹابیس تے تالا',
'enterlockreason'      => 'تالا لان دی وجہ دسو تے اہ وی دسو جے کدوں تالا کھلے گا',
'readonlytext'         => 'مکھیا جینے ایہ تالا لایا سی اونے ایہ وجہ دسی اے:$1',
'missing-article'      => 'وکیپیڈیا نوں تواڈے لفظ "$1" $2 دے نال دا صفحہ نئیں لبیا جیڑا کے اینوں کھوج لینا چائیدا سی۔

اے مسئلہ عام طور تے اس ویلے ہوندا اے جدوں تسی کسی پرانے جوڑ یا فیر کسی صفحے دی تاریخ چ جا کے جوڑ تے کلک کر دے اوہ۔

اگر انج نئیں فیر تسی سافٹویئر چ اک مسئلا لب لیا اے۔ توانوں اے گل کسی مکھیے نوں دسو۔',
'missingarticle-rev'   => '(رویژن#: $1)',
'missingarticle-diff'  => '(فرق: $1،$2)',
'readonly_lag'         => 'ایہ ڈیٹابیس اپنے آپ تالے چ اندی اے تے اودوں تھلواں ڈیٹا بیس اتلے نوں جا رلدا اے۔',
'internalerror'        => 'اندر دا مسئلا',
'internalerror_info'   => 'اندر دا مسئلا: $1',
'fileappenderrorread'  => '"$1" پڑھیا ناں جا سکیا جوڑدیاں',
'fileappenderror'      => '"$1" نال "2$" جوڑیا ناں جاسکیا۔',
'filecopyerror'        => '"$1" توں  "$2" تک فائل کاپی ناں ہوسکی۔',
'filerenameerror'      => '"$1" دا ناں بدل کے "$2" نا رکھیا جاسکیا۔',
'filedeleteerror'      => 'فائل "$1" نا مٹائی جاسکی۔',
'directorycreateerror' => 'ڈائریکٹری "$1" نئیں بنا جاسکی۔',
'filenotfound'         => 'فائل "$1" نا لبی جاسکی۔',
'fileexistserror'      => '"$1" xjNg fNlF gkel ojvkrl: xjNg hlKl jc.',
'unexpected'           => 'امید ناء ہون والا مل:"$1"="$2".',
'formerror'            => 'مسئلا: فارم نا پیجیا سکیا',
'badarticleerror'      => 'اے کم اس صفحے تے نئیں ہو سکدا۔',
'cannotdelete'         => 'صفحہ یا فائل "$1" نوں مٹایا نا جاسکیا۔
اینوں پہلاں توں ای کسے نے مٹایا ہوۓ گا۔',
'badtitle'             => 'پیڑا عنوان',
'badtitletext'         => 'منگیا گۓ صفحہ دا ناں غلط اے، خالی اے یا غلط تریقے نال جوڑیا گیا اے۔<div/>
ہوسکدا اے ایدے چ اک دو ھندسے ایسے ہون جیڑے عنوان وچ استعمال نہیں کیتے جاسکدے۔',
'perfcached'           => 'اے ڈیٹا پرانا بچایا ہویا اے تے ایدے چ نویاں گلاں نئیں ہون گیاں۔',
'perfcachedts'         => 'اے ڈیپا پرانا اے تے اینوں آخری آری $1 نوں نواں کیتا گیا سی۔',
'querypage-no-updates' => 'اس صفحے نوں اپڈیٹ فلحال نئیں کیتا جا سکدا۔
ایدا مال ہلے نواں نئیں کیتا جاۓ گا۔',
'wrong_wfQuery_params' => 'غلط پیرامیٹرز وفکویریدے()<br />
فنکشن: $1<br />
کویری: $2',
'viewsource'           => 'ویکھو',
'actionthrottled'      => 'اے کم کئی واری کیتا گیا اے',
'actionthrottledtext'  => 'سپام روک کم لئی، توانوں ایس کم توں کئی واری اک تھوڑے ویلے چ روکیا گیا اے  پر تساں اپنی حد توں اگے ودے۔ 
مہربانی کرکے تھوڑے چر مگروں  فیر کوشش کرنا۔',
'protectedpagetext'    => 'اس صفحے دے اتے تبدیلی کرن نوں روکیا گیا اے۔',
'viewsourcetext'       => 'تسی اس صفحے دی لکھائی نوں ویکھ تے نقل کر سکدے او:',
'protectedinterface'   => 'اے صفحے سافٹویئر نوں ورتن دی تھاں دیندا اے تے ایدے غلط ورتن نوں روکن واسطے اینوں بچایا ہویا اے۔',
'editinginterface'     => "'''خبردار:''' تسیں اک ایسا صفہ بدل رۓ او جیہڑا مکھی صفے دے سوفٹویر نوں لکھت دیندا اے۔ ایس صفے ج تبدیلی ورتنن والیاں دے مکھی صفے دے وکھالے نوں بدل دے گی۔ بولی وٹاندرے لئی، مہربانی کرکے میڈیاوکی بولی ویونت [http://translatewiki.net/wiki/Main_Page?setlang=en translatewiki.net] ول ویکھو۔",
'sqlhidden'            => '(SQL کھوج چھپائی ہوئي اے)',
'cascadeprotected'     => 'ایس صفے نوں تبدیلی توں بچایا گیا اے، کیوں جے ایہ تھلے دتے گۓ {{انیک:$1|صفے|صفے}} چ ہیگا اے تے اینوں کیسکیڈنگ چنوتی نال بچایا گیا اے: $2',
'namespaceprotected'   => "'''$1''' ناں دے صفحے تسی نئیں لکھ سکدے۔",
'customcssprotected'   => 'تسی اے CSS صفحے نوں تبدیل نئیں کر سکدے کیونجے ایدے کسے دوجے ورتن آلے دیاں من پسند تانگاں نیں۔',
'customjsprotected'    => 'تسی اے JavaScript  صفحے نوں تبدیل نئیں کر سکدے کیونجے ایدے کسے دوجے ورتن آلے دیاں من پسند تانگاں نیں۔',
'ns-specialprotected'  => 'خاص صفحے تبدیل نئیں کیتے جاسکدے۔',
'titleprotected'       => 'اس ناں نوں [[User:$1|$1]] نئیں بناسکدا۔
اس دی وجہ اے دسی گئی اے: "\'\'$2\'\'"۔',

# Virus scanner
'virus-badscanner'     => "غلط تریقہ کار: انجان وائرس کھوجی: ''$1''",
'virus-scanfailed'     => 'کھوج نا ہوسکی (کوڈ $1)',
'virus-unknownscanner' => 'اندیکھا اینٹیوائرس:',

# Login and logout pages
'logouttext'                 => "'''تسی لاگ آؤٹ ہوگۓ او.'''
تسی   {{SITENAME}} نوں گمنامی چ ورت سکدے او یا تسی [[Special:UserLogin|لاگ ان دوبارہ]] ہوجاؤ اوسے ناں توں یا وکھرے ورتن والے توں۔ اے گل چیتے رکھنا جے کج صفیاں تے تسی لاگ ان دسے جاؤگے جدوں تک تسی اپنے براؤزر دے کاشے نوں صاف ناں کرلو۔
You can continue to use {{SITENAME}} anonymously, or you can [[Special:UserLogin|log in again]] as the same or as a different user.
Note that some pages may continue to be displayed as if you were still logged in, until you clear your browser cache.",
'welcomecreation'            => '== جی آیاں نوں, $1! ==
تواڈا کھاتا بن گیا اے۔
اپنیاں [[Special:Preferences|{{SITENAME}} تانگاں]] بدلنا نا پلنا۔',
'yourname'                   => 'ورتن والہ:',
'yourpassword'               => 'کنجی:',
'yourpasswordagain'          => 'کنجی دوبارہ لکھو:',
'remembermypassword'         => 'اس براؤزر تے میرا ورتن ناں یاد رکھو ($1 {{PLURAL:$1|دن|دناں}} واسطے)',
'securelogin-stick-https'    => 'لاک ان ہون دے مگروں HTTPS  نال جڑے روو۔',
'yourdomainname'             => 'تواڈا علاقہ:',
'externaldberror'            => 'ڈیٹابیس چ توانوں پہچاننے چ کوئی مسئلہ ہویا اے یا فیر تسی اپنا بارلا کھاتا نئیں بدل سکدے۔',
'login'                      => 'اندر آؤ جی',
'nav-login-createaccount'    => 'اندر آؤ / کھاتہ کھولو',
'loginprompt'                => 'اندر آنے آستے تواڈیاں کوکیز آن ہونیاں چائیدیاں نے {{SITENAME}}.',
'userlogin'                  => 'اندر آؤ / کھاتہ کھولو',
'userloginnocreate'          => 'اندر آؤ جی',
'logout'                     => 'لاگ توں باہر',
'userlogout'                 => 'باہر آؤ',
'notloggedin'                => 'لاگ ان نئیں ہوۓ او',
'nologin'                    => "تواڈا کھاتہ نہیں اے؟ '''$1'''۔",
'nologinlink'                => 'کھاتہ بناؤ',
'createaccount'              => 'کھاتہ بناؤ',
'gotaccount'                 => "تواڈا پہلے توں کھاتہ ہے؟ '''$1'''",
'gotaccountlink'             => 'اندر آؤ',
'userlogin-resetlink'        => 'اپنے لاگ ان ہون دیاں شیواں پل گۓ؟',
'createaccountmail'          => 'ای میل دے نال',
'createaccountreason'        => 'وجہ:',
'badretype'                  => 'تواڈی کنجی صحیح نئیں۔',
'userexists'                 => 'اے ورتن ناں پہلاں توں ای کوئی ورت ریا اے۔
مہربانی کر کے کوئی دوجا چن لو۔',
'loginerror'                 => 'لاگ ان چ مسئلا اے',
'createaccounterror'         => 'اے کھاتا نئیں بنایا جا سکیا: $1',
'nocookiesnew'               => 'اے کھاتا بن گیا اے مگر تسی لاگ ان نئیں ہو سکے۔
{{SITENAME}} لاگ ان کرن واسطے cookies منگدی اے۔
تساں نیں cookies بند کیتیاں ہوئیاں نیں۔
اوناں نوں آن کردو تے فیر اپنے ورتن ناں تے کنجی نال لاگ ان ہو جاؤ۔',
'nocookieslogin'             => '{{SITENAME}} لاگ ان کرن واسطے cookies منگدی اے۔
تساں نیں cookies بند کیتیاں ہوئیاں نیں۔
اوناں نوں آن کردو تے فیر اپنے ورتن ناں تے کنجی نال لاگ ان ہو جاؤ۔',
'nocookiesfornew'            => 'کھاتا نئیں بنایا جا سکیا، کیونجے اسی اس کھاتے دی تھاں نئیں پتہ کر سکے۔
اے پکا کرلو کے تساں نے cookies آن کیتیاں ہوئیاں نیں، فیر اے صفحہ دوبارہ کھولو تے کوشش کرو۔',
'noname'                     => 'تسی کوئی پکا ورتن آلا ناں نئیں رکھ رۓ۔',
'loginsuccesstitle'          => 'تسی لاگن ہوگۓ او',
'loginsuccess'               => "'''ہن تسی {{SITENAME}} تے \"\$1\" دے ناں توں لاگ ان او'''",
'nosuchuser'                 => 'اس $1 ناں نال کوئی ورتن آلا نہیں۔
اپنی لکھائی درست کرو یا نیا [[Special:UserLogin/signup|کھاتہ بناؤ]]۔',
'nosuchusershort'            => 'اس "$1" ناں دا کوئی ورتن آلا نہيں اے۔

اپنی الف، بے چیک کرو۔',
'nouserspecified'            => 'توانوں اپنا ورتن آلا ناں دسنا ہوۓ گا۔',
'login-userblocked'          => 'اے ورتن آلے روکیا ہویا اے۔ اے لاگ ان نئیں کرسکدا۔',
'wrongpassword'              => 'تواڈی کنجی سہی نہیں۔<br />
فیر سہی ٹرائی مارو۔',
'wrongpasswordempty'         => 'تواڈی کنجی کم نہیں کر رہی۔<br />
فیر ٹرائی مارو۔',
'passwordtooshort'           => 'کنجی کم از کم {{PLURAL:$1|1 ہندسے|$1 ہندسیاں}} دی ہونی چائیدی اے۔',
'password-name-match'        => 'کنجی ورتن ناں توں مختلف ہونی چائیدی اے۔',
'password-login-forbidden'   => 'اس ورتن ناں یا کنجی دا ورتن تے پابندی اے۔',
'mailmypassword'             => 'نئی کنجی ای میل کرو',
'passwordremindertitle'      => '{{SITENAME}} لئی نوی عارضی کنجی',
'passwordremindertext'       => 'کسے نے (غالبن تسی $1 آئی پی پتے توں) نوی کنجی ($4){{SITENAME}} واسطے منگی۔ اک عارضی کنجی ورتن والے "$2" دے لئی بنائی گئی سی تے "$3" تے سیٹ کر دتی گئی سی۔ اگر اے تواڈا کم اے تے توانوں اندر آکے اک نوی $5 کنجی چننی پۓ گی۔

اگر کسے ہور نے اے درخواست کیتی اے یا تسی اپنی پرانی کنجی لب لئی اے تے تسی اینوں بدلنا نئیں چاندے تے تسی اس سنعے نوں چھڈو تے پرانی کنجی استعمال کرو۔',
'noemail'                    => 'اس ورتن والے "$1" دا کوئی ای میل پتہ نئیں ہے گا۔',
'noemailcreate'              => 'کوئی ٹھیک ای میل لکھ دیو۔',
'passwordsent'               => 'اک نوی کنجی اس ای میل "$1" تے پیجی جاچکی اے۔<br />
جدوں توانوں اے ملے تسی دوبارہ لاگن ہو۔',
'blocked-mailpassword'       => 'تواڈے IP پتے تے تبدیلی کرن تے روک اے، تے تسی کنجی وی واپس نئیں لیا سکدے تاکے ایدا غلط ورت نا ہوۓ۔',
'eauthentsent'               => 'اک کنفرمیشن ای میل دتے گۓ ای میل پتے تے پیج دتی گئی اے۔ اس توں پہلاں کہ کوئی دوجی ای میل کھاتے تے پیجی جاۓ، توانوں ای میل چ دتیاں ہدایات تے عمل کرنا ہوۓ گا، تا کے اے پکا ہو سکے کہ اے کھاتہ تواڈا ہی اے۔',
'throttled-mailpassword'     => 'اک کنجی بارے سنیعہ پہلے ای پیجیا جاچکیا اے، پچھلے {{انیک:$1|کینٹہ|$1 کینٹے}} چ۔ 
کوئی غلط کم ہون توں پہلے صرف اک کنجی سنیغہ {{انیک:$1|کینٹہ|$1 کینٹے}} پیجیا جائیکا۔',
'mailerror'                  => 'چٹھی پیجن چ غلطی: $1',
'acct_creation_throttle_hit' => 'ایس وکی تے آن والے تے تواڈے آئی پی پتے ورتدیاں ہویاں {{انیک:$1|1 کھاتہ|$1 کھاتے}} پچھلے دن چ جیہڑا کی ایس ویلے چ زیادہ توں زیادہ دی اجازت اے۔ 
ایس لئی ایتھے آن والے  تے ایس آئی پی پتے نوں ورتن والے ایس ویلے ہور کھاتہ نئیں کھول سکدے۔',
'emailauthenticated'         => 'تھواڈا ای-میل پتہ $2 نوں $3 تے پکا کیتا گیا۔',
'emailnotauthenticated'      => 'تھواڈا ای-میل پتہ ہجے پکا نئیں ہویا۔
کوئی ای-میل اینج دے فیچر والی نئیں پیجی جاۓ گی۔',
'noemailprefs'               => 'ایناں فیچراں نوں کم کرن لئی اپنیاں تانگاں چ ای-میل پتہ دسو۔',
'emailconfirmlink'           => 'ای میل پتہ پکا کرو',
'invalidemailaddress'        => 'ایہ ای-میل پتہ نئیں چلے گا کیوں جے اے ناں منے جان والے فارمیٹ تے بنیا ہویا اے۔
مہربانی کرکے منے جان والے فارمیٹ پتے نوں دسو یا فیر اے تھاں خالی چھڈ دیو۔',
'cannotchangeemail'          => 'ایس وکی تے کھاتہ ای-میل پتے نئیں بدلے جاسکدے۔',
'accountcreated'             => 'کھاتہ کھل گیا',
'accountcreatedtext'         => 'ورتن کھاتہ $1 لئی بنا دتا گیا اے۔',
'createaccount-title'        => '{{SITENAME}} لئی کھاتے دا بننا۔',
'createaccount-text'         => 'کسے نیں تھواڈے ای-میل پتے تے {{SITENAME}} تے  ($4)، ناں "$2"، تے کنجی "$3" نال کھاتہ کھولیا اے۔ تسی ہن ای لاگ ان ہووو تے اپنی کنجی بدلو۔
تسی ایس سنیعے نوں رہن دیو اگر کھاتہ غلطی نال کھلیا جے۔',
'usernamehasherror'          => 'ورتن والے ناں چ ہیش کیریکٹر نئیں ہوسکدے۔',
'login-throttled'            => 'تسی تھوڑا چر پہلے لاگ ان ہون دی چوکھی واری کوشش کرچکے او۔
تھوڑا صبر کرو تے فیر کوشش کرنا۔',
'login-abort-generic'        => 'تسی لاگ ان نئیں ہوسکے۔',
'loginlanguagelabel'         => 'بولی: $1',
'suspicious-userlogout'      => 'تھواڈی لاگ آؤٹ ہوں دی کوشش رک گئی اینج لگدا اے  جیویں اے ٹٹے براؤزر یا کیشنگ پراکسی توں پیجیا گیا سی۔',

# E-mail sending
'php-mail-error-unknown' => 'PHP میل دے کم چ کوئی انجانی غلطی۔',
'user-mail-no-addy'      => 'ای-میل پتے بنا ای-میل کلن دی کوشش۔',

# Change password dialog
'resetpass'                 => 'کنجی بدلو',
'resetpass_announce'        => 'تسی اک کچے ای-میل کود تے لاگ ان ہوگۓ او۔
لاگ ان مکان لئی تھوانوں ایتھے اک نویں کنجی بنانی پوے گی:',
'resetpass_header'          => 'کھاتے دی کنجی بدلو',
'oldpassword'               => 'پرانی کنجی:',
'newpassword'               => 'نوی کنجی:',
'retypenew'                 => 'نئی کنجی دوبارہ لکھو:',
'resetpass_submit'          => 'کنجی رکھو تے لاگ ان ہو جاو',
'resetpass_success'         => 'تھواڈی کنجی بدلی جاچکی اے!
تسی لاگ ان ہورۓ او۔۔۔۔۔۔',
'resetpass_forbidden'       => 'کنجی بدلی نئیں جاسکدی',
'resetpass-no-info'         => 'تسی لاگ ان ہوکے ای اس صفحے نوں ویکھ سکدے او۔',
'resetpass-submit-loggedin' => 'کنجی بدلو',
'resetpass-submit-cancel'   => 'ختم',
'resetpass-wrong-oldpass'   => 'غلط عارضی یا ہلے دی کنجی۔
تساں نے شاید اپنی کنجی بدل لئی ہوۓ یا عارضی کنجی دی درخواست کیتی ہوۓ۔',
'resetpass-temp-password'   => 'عارضی کنجی:',

# Special:PasswordReset
'passwordreset'                    => 'کنجی واپس لیاؤ',
'passwordreset-text'               => 'اے فارم مکمل کرکے اپنے کھاتے دی معلومات اپنے ای-میل تے منگوالو۔',
'passwordreset-legend'             => 'کنجی واپس لیاؤ',
'passwordreset-disabled'           => 'اس وکی تے کنجی واپس نئیں لیائی جاسکدی۔',
'passwordreset-pretext'            => '{{PLURAL:$1||تھلے دتے ہوۓ ڈیٹا چوں اک ایتھے دیو}}',
'passwordreset-username'           => 'ورتن ناں:',
'passwordreset-domain'             => 'ڈومین',
'passwordreset-capture'            => 'آن والی ای-میل ویکھو؟',
'passwordreset-capture-help'       => 'اگر تسیں اے ڈبہ چیک کروگے ای-میل (عارضی کنجی نال) وکھائی جاۓ گی توانوں تے پیجی وی جاۓ گی۔',
'passwordreset-email'              => 'ای-میل پتہ:',
'passwordreset-emailtitle'         => '{{SITENAME}} دے اتے کھاتے دی معلومات:',
'passwordreset-emailtext-ip'       => 'کسے نے (خورے تساں  آئی پی پتے $1) تواڈے کھاتے دا ویروا منگیا اے {{سائیٹناں}} ($4) لئی۔ تھلے دتا گیا ورتنوالا {{انیک:$3|کھاتہ|کھاتے}} ایس ای-میل پتے نال جوڑ رکھدا اے:

2$

{{انیک:$3|اے عاوضی کنجی|اے عارضی کنجیاں}} {{انیک:$5|اک دن|$5 دناں}} چ مک جاوے گی۔ تسیں لاگان ہوو تے اپنی اک نويں کنجی چنو. اگر کسے ہور نے اے کنجی والی چٹھی پیجی اے یا توانوں پرانی کنجی یاد آگئی اے تے تسیں پرانی کنجی نال ای کم چلانا چاندے او تے تسیں ایس سنیعے نوں پل جاؤ تے پرانی کنجی ای ورتو۔',
'passwordreset-emailtext-user'     => 'ورتنوالے $1 نے {{سائیٹناں}} تے تواڈے کھاتے بارے پچھیا اے {{سائیٹناں}} لئی ($4)۔ تھلے دتا گیا ورتن {{انیک:$3|کھاتہ|کھاتے}} ایس ای-میل نال جڑدا اے۔

$2

{{انیک:$3|ایہ عارضی کنجی|اے عارضی کنجیاں}} مک جائیگا {{انیک:$5|اک دن|$5 دن}}۔ تسیں ہن لاکان ہوو تے نویں کنجی چنو۔ اگر کسے ہور نے اے چٹھی پیجی یا توانوں اپنی پہلی کنجی یاد آگئی اے تے تسیں اونوں بدلنا نئیں چاندے تے تسیں ایس سنیعے نوں پھل جاؤ تے پرانی کنجی نال ای کم چلاؤ۔',
'passwordreset-emailelement'       => 'ورتن ناں: $1
عارضی کنجی: $2',
'passwordreset-emailsent'          => 'یاد کران واسطے اک ای-میل پیج دتی گئی اے۔',
'passwordreset-emailsent-capture'  => 'اک یاد کران والی ای-میل پیج دتی گئی اے، جیہڑی تھلے دسی گئی اے۔',
'passwordreset-emailerror-capture' => 'اک یادکراؤ ای-میل بنائی گئی اے، جیہڑی کہ تھلے دسی گئی اے، پر ورتن والے تک پیجنا نئیں ہوسکیا:$1',

# Special:ChangeEmail
'changeemail'          => 'ای-میل پتہ بدلو',
'changeemail-header'   => 'کھاتے دا ای-میل پتہ بدلو',
'changeemail-text'     => 'اس فارم نوں پورا کر کے ای-میل پتہ بدلو۔ اس کم نوں پورا کرن واسطے توانوں اپنی کنجی لکھنی پۓ گی۔',
'changeemail-no-info'  => 'تسی لاگ ان ہوکے ای اس صفحے نوں ویکھ سکدے او۔',
'changeemail-oldemail' => 'ہلے دا ای-میل پتہ:',
'changeemail-newemail' => 'نواں ای-میل پتہ:',
'changeemail-none'     => '(کوئی نئیں)',
'changeemail-submit'   => 'ای-میل بدلو',
'changeemail-cancel'   => 'ختم',

# Edit page toolbar
'bold_sample'     => 'موٹی لکھائی',
'bold_tip'        => 'موٹی لکھائی',
'italic_sample'   => 'ترچھی لکھائی',
'italic_tip'      => 'ترچھی لکھائی',
'link_sample'     => 'جوڑ',
'link_tip'        => 'اندرونی جوڑ',
'extlink_sample'  => 'http://www.example.com جوڑ دا ناں',
'extlink_tip'     => 'بیرونی جوڑ (remember http:// prefix)',
'headline_sample' => 'شہ سرخی',
'headline_tip'    => 'دوسرے درجے دی سرخی',
'nowiki_sample'   => 'فارمیٹ نہ ہوئی لکھائی ایتھے پاؤ',
'nowiki_tip'      => 'وکی فارمیٹ رھندیو۔',
'image_tip'       => 'وچ مورت لگاؤ',
'media_tip'       => 'فائل دا جوڑ',
'sig_tip'         => 'تواڈے دستخط ویلے دے نال',
'hr_tip'          => 'سدھی لکیر',

# Edit pages
'summary'                          => 'خلاصہ:',
'subject'                          => 'موضوع/شہ صرحی:',
'minoredit'                        => 'اے نکا جیا کم اے',
'watchthis'                        => 'اس صفحے تے نظر رکھو',
'savearticle'                      => 'کم بچاؤ',
'preview'                          => 'وکھاؤ',
'showpreview'                      => 'کچا کم ویکھو',
'showlivepreview'                  => 'جیندا کچا کم',
'showdiff'                         => 'تبدیلیاں وکھاؤ',
'anoneditwarning'                  => "<div/>'''خبردار''' تسی اندر نہیں آۓ
تواڈا ''آئی پی'' پتہ فائل فائل وچ لکھیا جاۓ گا۔",
'anonpreviewwarning'               => "''تسی ہلے لاگ ان نئیں ہوۓ،۔ کم بچاؤ گے تے تواڈا IP پتہ صفحے دی تریخ چ لکھ لیا جاۓ گا۔''",
'missingsummary'                   => "'''یادکرائی:''' تساں تبدیلی دی سمری نئیں دتی۔  اگر تسیں \"{{int:savearticle}}\" نوں کلک کروگے تواڈیاں تبدیلیاں اک دے بنا بچ جان گیاں۔",
'missingcommenttext'               => 'تھلے اپنی گل لکھو۔',
'missingcommentheader'             => "'''یادکرائی:''' تساں ایس گل تے سرخی / سرناواں نئیں دتا۔
اگر تسیں  \"{{int:savearticle}}\" دوبارہ کلک کردے اوہ تواڈی تبدیلی اک توں بنا بچاۓ گا۔",
'summary-preview'                  => 'کچے کم دا خلاصہ:',
'subject-preview'                  => 'سرناواں / سرخی وکھالہ:',
'blockedtitle'                     => 'ورتن آلے نوں روکیا ہویا اے',
'blockedtext'                      => "'''تواڈا ورتن والا ناں یا فیر آئی پی ایڈریس روک دتا گیا اے۔'''

توانوں $1 نے روکیا اے۔<br />
ایدی وجہ ''$2'' اے۔

* رکوائی دی پہل:$8
* رکوائی دا انت:$6
* روکیا جان آلا:$7

تسی $1 نال مل ملسکدے او یا اک ہور [[{{MediaWiki:Grouppage-sysop}}|ایڈمنسٹریٹر]] نال روک دے بارے چ گل بات کر سکدے او۔<br />
تسی اس ورتن آلے نوں ای میل نئیں کر سکدے جدوں تک توانوں کوئی ای میل ایڈریس نا دتا جاۓ تے توانوں اس دے استعمال توں روکیا نا گیا ہوۓ۔
تواڈا موجودہ آئی پی پتہ $3 اے تے روکی گئی آئی ڈی #$5 اے۔
مہربانی کر کے کوئی وی سوال جواب کرن آسطے اتے دتیاں گئیاں تفصیلات ضرور دیو۔",
'autoblockedtext'                  => 'تواڈا آئی پی پتہ  اپنے آپ روک دتا گیا جے کیوں جے اینوں اک ہور ورتن والا ورت ریا سی۔ جینوں $1 نے روک دتا اے۔
وجہ ایہ دتی کئی اے:

:\'\'$2\'\'
*روک دا شروع: $8
*روک دا مکن: $6
* روکے گۓ:$7

تسیں $1 نال گل کرو یا کسے مکھۓ نال روک تے گل کرو۔

ایہ گل یاد رکھو جے تسیں "ایس ورتن والے نوں ای-میل کرو" والا فیخر نئیں ورت سکدے جدوں تک تواڈے کول اک پکا ای-میل پتہ تے جینوں ورتن تے  روک ناں لگی ہووے۔
تھواڈا ہن دا آئی پی پتہ $3 اے تے روکی آئی ڈی #$5۔
کسے وی سوال جواب لئي اوپر دسیاں گلاں وی شامل کرو۔',
'blockednoreason'                  => 'کوئی وجہ نئیں دسی گئی',
'whitelistedittext'                => 'تھواڈے کول $1 ہونا چآغیدا اے صفے تبدیل کرن لئی۔',
'confirmedittext'                  => 'توانوں اپنا ای-میل پتہ پکا کرنا چائیدا اے تبدیلیاں کرن توں پہلے۔
مہربانی کرکے اپنا ای-میل پتہ بناؤ تے پکا کرو [[خاص:چنوتیاں|ورتن چنوتیاں]]',
'nosuchsectiontitle'               => 'اے ہو جیا کوئی ٹوٹا نئیں',
'nosuchsectiontext'                => 'تساں اک ایسے پاسے نوں بدلن دی کوشش کیتی اے جیہڑا ہے ای نئیں۔
خورے جدوں تسی تک رۓ سے اودوں اینوں مٹادتا گیا ہووۓ یا بدل دتا گیا ہووے۔',
'loginreqtitle'                    => 'لاگ ان چائیدا اے',
'loginreqlink'                     => 'لاگ ان ہو جاو',
'loginreqpagetext'                 => 'دوجے صفے ویکھن لئی $1 لازمی اے۔',
'accmailtitle'                     => 'کنجی پیج دتی گئی اے۔',
'accmailtext'                      => "اک کنجی [[ورتن گل بات:$1|$1]] $2 نوں پیج دتی گئی اے۔
ایس نویں کھاتے دی کنجی بدلی جاسکدی  اے ''[[خاص:کنجی بدلو]]'' صفے تے لاگ ان ہون تے۔",
'newarticle'                       => '(نواں)',
'newarticletext'                   => 'تسی ایسے صفحے دے جوڑ توں ایتھے پہنچے او جیڑا ھلے تک نہیں بنیا۔<br />
اس صفحہ بنانے آسطے تھلے دتے گۓ ڈبے وچ لکھنا شروع کر دیو(زیادہ رہنمائی آستے اے ویکھو [[{{MediaWiki:Helppage}}|<br />مدد دا صفحہ]])۔
اگر تسی ایتھے غلطی نال پہنچے او تے اپنے کھوجی توں "بیک" دا بٹن دبا دیو۔',
'anontalkpagetext'                 => "----'' ایہ اک گمنام ورتن والے دا گل بات دا صفہ اے جینے ہلے کھاتہ نئیں کھولیا یا او اینون ورتدا نئیں۔
سانوں فیر نمبراں والا آئی پی پتہ ورتنا پوے گا اونوں لئی. ایہو جیا آئی پی پتہ گئی ورتن والے ورت سکدے نیں۔ 
اگر تسیں اک گمنام ورتن والے او تے اے مسوس کردے او جے پیڑی گل بات تواڈی بارے ہوئی اے، مہربانی کرکے [[خاص:ورتنلاگان/کھاتہ کھولو|کھاتہ بناؤ]] یا [[خاص:ورتنلاگان]] اگے کسے مسلے توں بچن گمنام ورتن والیاں کولوں",
'noarticletext'                    => 'اس ویلے اس صفحے تے کج نہیں لکھیا ہویا تسیں [[Special:Search/{{PAGENAME}}|اس صفحے دے ناں نوں دوجے صفحیاں تے کھوج سکدے او]] یا فیر [{{fullurl:{{FULLPAGENAME}}|action=edit}} اس صفحے نوں لکھ سکدے او۔]',
'noarticletext-nopermission'       => 'ایس ویلے ایس صفے تے کوئی لکھت نئیں۔ 
تسیں [[خاص:کھوج/{{صفہ ناں}}|صفہ سرناویں لئی کھوجو]] دوسریاں صفیاں تے،
یا <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} search the related logs]</span>',
'userpage-userdoesnotexist'        => 'ورتن کھاتہ "$1" رجسٹر نئیں ہویا۔
مہربانی کرکے دسو جے تسیں ای ھفا بنانا/بدلنا چاندے او۔',
'userpage-userdoesnotexist-view'   => "''$1'' کھاتا رجسٹرڈ نئیں اے۔",
'blocked-notice-logextract'        => 'اے ورتن آلے تے روک اے۔
روکن دی وجہ اے دسی گئی اے:',
'clearyourcache'                   => "'''نوٹ:''' بچان مگروں توانوں اپنے براؤزر دے کاشے توں بار جانا پوے تبدیلیاں ویکھن لئی۔
*'''فائرفوکس / سفاری:'''  ''شفٹ'' پھڑی رکھو ریلوڈ تے کلکنگ کردیاں ہویاں''Ctrl-F5'' or ''Ctrl-R'' (''Command-R'' میک تے)
* '''گوکل کروم:''' دباؤ ''Ctrl-Shift-R'' (''Command-Shift-R'' میک تے)
'''انٹرنیٹ ایکسپلورر:''' hold ''Ctrl'' کلک کردیاں ''Refresh'', یا دباؤ ''Ctrl-F5''
'''کونکرر:'''کلک ریلوڈ یا ''F5'' دباؤ۔
'''اوپیرا:''' کاشے نوں صاف کرو ''آوزار → تانگاں''",
'usercssyoucanpreview'             => "'''ٹوٹکہ:''' ورتو بٹن اپنی نویں  \"{{int:showpreview}}\"  CSS بچان توں پہلے.",
'userjsyoucanpreview'              => "'''ٹوٹکہ:''' ورتو بٹن اپنی نویں  \"{{int:showpreview}}\"  JavaScript  بچان توں پہلے.",
'usercsspreview'                   => "'''یادرکھو جے تسی اپنی ورتن  CSS دا کچا کم ویکھ رۓ او.'''
'''Iاے ہلے بچائی نئیں گئ!'''",
'userjspreview'                    => "'''یاد رکھو بے  تسی صرف اپنی ورتن 'JavaScript چیک کررۓ او''
'''اینوں ہجے بچایا نئیں گیا!'''",
'sitecsspreview'                   => "'''یادرکھو جے تسی اپنی ورتن  CSS دا کچا کم ویکھ رۓ او.'''
'''Iاے ہلے بچائی نئیں گئ!'''",
'sitejspreview'                    => "'''یاد رکھو بے  تسی صرف ایہ 'JavaScript کوڈ چیک کررۓ او''
'''اینوں ہجے بچایا نئیں گیا!'''",
'userinvalidcssjstitle'            => "'''خبردار:''' \"\$1\" سکن نئیں اے۔
Custom .css تے .js pages use a lowercase title, e.g. {{ns:user}}:Foo/vector.css as opposed to {{ns:user}}:Foo/Vector.css.",
'updated'                          => '(نواں کیتا گیا)',
'note'                             => "'''نوٹ:'''",
'previewnote'                      => "'''اے ہلے کچا کم اے؛ تبدیلیاں بچائیاں نہیں گئیاں'''",
'previewconflict'                  => 'اے وکھالہ لکھت نوں تبدیلی ایریا تے اے اینج لگے گا اگر تسیں بچان دی چنوتی کردے او۔',
'session_fail_preview'             => "'''معاف کرنا! اسیں تواڈی لکھت اگے نیں کرسکدے جے ڈیٹا کم کيا جے'''
مہربانی کرکے فیر کوشش کرو.
اگر ہلے وی اے کم نئیں کردا کوشش کرو  [[خاص:ورتنلاگبار|بار جارۓ او]] تے اندر آ رۓ او.",
'session_fail_preview_html'        => "'''معاف کرن! ایس سینش دے ڈیٹ دے کم ہون باجوں تواڈیاں لکھتاں سانبھ نئیں سکدے۔'''
'' کیوں جے {{سائیٹناں}} نے گجا آیج ثی ایم ایل قابل کیتا اے، وکھالہ لکیا اے  جاواسکرپٹ دے ہلے توں بچن لئی''
'''اگ تاں اے ٹھیک لکھت کوشش اے تے فیر کوشش کرو'''
اگر اے ہلے وی نئیں چل رئی [[خاص:ورتن لاگ آؤٹ|کھاتے چوں باو]] تے کوشش کرو تے فیر لاگ ان ہوو۔",
'token_suffix_mismatch'            => "'''تواڈی لکھت نئیں منی گئی کیوں جے تواڈے بندے نے پنکچوایشن کیریکٹر لکھت ٹوکن چ رلاۓ.'''
ایس تبدیلی نوں لکھت چ خرابی نوں روکن لئی روکیا گیا اے۔.
اے اودوں ہوندا اے جدوں تسیں گمنام سرور ورتدے او۔.",
'edit_form_incomplete'             => "''' ایڈٹ فارم دے کج پاسے سرور تک نئيں اپڑے؛ دو واری چیک کرو جے تھواڈیاں تبدیلیاں بچیاں نیں تے فیر کوشش کرو'''",
'editing'                          => 'تسی "$1" لکھ رہے او',
'editingsection'                   => '$1 دا حصہ لکھ رہے او',
'editingcomment'                   => '$1 بدل ریاں (نواں پاسہ)',
'editconflict'                     => 'تبدیلی رپھڑ: $1',
'explainconflict'                  => "جدوں تسیں لکھنا شروع کیتا کسے ہور نے صفہ بدل دتا اے۔ اتلا لکھت تھاں چ چ لکھت ہے جیویں اوہ ہن ہیگی اے۔
تواڈیاں تبدیلیاں تھلویں لکھت چ دسیاں جاریاں نیں۔ توانوں اپنیاں تبدیلیاں ہن دی لکھت چ رلانیاں پین گیاں۔
توانوں اپنیاں تبدیلیاںہن دی لکھت چ رلانیاں پین گیا۔
'''صرف''' اتلی لکھت ددی تھاں بچائی جاسیگی جدوں تسیں \"{{int:savearticle}}\" دباؤ گے",
'yourtext'                         => 'تواڈی لکھائی',
'storedversion'                    => 'سانبیا ورژن',
'nonunicodebrowser'                => "'''خبردار: تھواڈا براؤزر تے یونیکوڈ نئیں چلدا۔'''
اک کم تھانوں ایس قابل کریگا جے بچت نال صفے بچا سکو: non-ASCII کیریکٹر تبدیلی ڈبے چ ہیکساڈیسیمل کوڈ دسن گے۔",
'editingold'                       => "'''خبردار: تسیں ایس صفے دی پرانی ریوین بدل رۓ او۔'''
اگر تسیں اینوں بچاندےاو، ایس ریوین مگروں کوئی وی تبدیلی مک جائیگی۔",
'yourdiff'                         => 'تبدیلیاں',
'copyrightwarning'                 => "مہربانی کر کے اے گل یاد رکھ لو کے سارے کم {{SITENAME}} ایتھے $2 دے تھلے آن گے (زیادہ علم واسطے $1 تکو)۔<br />
اگر تسی نئیں چاندے کے تواڑی لکھائی نوں بے رحمی نال ٹھیک کیتا جاۓ تے نالے اپنی مرضی نال اونھوں چھاپیا جاۓ تے ایتدے مت لکھو۔<br />
تسی اے وی ساڈے نال وعدہ کر رہے او کہ اینوں تسی آپ لکھیا اے یا فیر کسی پبلک ڈومین توں یا ایہو جۓ کسے آزاد ذریعے توں نقل کیتا اے۔<br />
'''ایتھے او کم بغیر اجازت توں نا لکھو جیدے حق راکھویں نے '''",
'copyrightwarning2'                => "مہربانی کرکے اے گل یا رکھو جے {{سائیٹناں}} تے دوجیاں دے ہتھوں سارے کم بدلے، لکھے یا ہٹاۓ جاسکدے نیں ۔
اگر تسیں نئیں چاندے جے تواڈیاں لکھتاں  بے رحمی نال تبدیل کیتیاں جان تے فیر اپنی لکھتاں ایتھے ناں لکھو<br />
تسیں ساڈے نال اے وی وعدہ کر رۓ او جے تساں اینوں آپ لکھیا اے، یایا کسے لوکاں آستے کاپی کرن والی تھاں توں کاپی کیتا اے یا اینج دے کھلے سورس توں ( پوری گل لئی $1 ویکھو)
'''کاپی حق والےکم بنا اجازت دے ایتھے ناں پیش کرو'''",
'longpageerror'                    => "'''غلطی : تھواڈی دتی گئی لکھت  $1 کلوبائٹ لمی اے، جیہڑی کے ود توں ود $2 کلوبائٹو توں وی وڈی اے۔'''
اینوں نئیں بچایا جاسکدا۔",
'readonlywarning'                  => "'''خبردار: ڈیٹابیس نوں تالا لگیا اے جے مرمت ہورئی اے ایس تواڈیاں لکھتاں بچائیاں نئیں جاسکدیاں.'''
تسیں  چاؤ گے جے ہن کاپی کرلو لکھت اک لکھت فائل چ تے بچا لو فیر ورتن لئی۔

.

مکھیا جینے اے تالا لایا اے اونے  اے گل کیتی اے: $1",
'protectedpagewarning'             => "'''خبردار: ایس صفے نوں بچایا گیا اے ایس لئی اینوں صرف مکھیا ای بدل سکدے نیں.'''
آخری لکھت رکارڈ تھلے اتے پتے لئ دتا کیا جے::",
'semiprotectedpagewarning'         => "'''نوٹ:''' ایس صفے نوں بچایا گیا اے تے رجسٹر ورتن والے ای ایدے چ تبدیلی کرسکدے نیں۔
آخری لاگ لکھت تھلے اتے پتے لئی دتی گئی اے۔",
'cascadeprotectedwarning'          => "'''خبردار:''' ایس صفے نوں بچایا گیا اے ایس لئی مکھیا ای اینوں بدل سکدے نیں کیوں جے اے تھلے دتے گۓ بچاؤ {{انیک:$1|صفہ|صفے}} چ دتا گیا اے:",
'titleprotectedwarning'            => "'''خبردار: ایہ صفہ بچایا گیا اے[[خاص:ٹولی لسٹ حق|خاص حق]] دے بنان دی لوڑ اے.'''
تھلے نیڑے دی لاگ لکھت دتی گ‏ی اے اتے پتے ل‏ی:",
'templatesused'                    => 'اس صفحے تے  ورتے گۓ {{PLURAL:$1|سانچے|سانچہ}}:',
'templatesusedpreview'             => 'اس کچے کم تے ورتے گئے {{انیک:$1|سانچے|سانچہ}} :',
'templatesusedsection'             => '{{انیک:$1|سچہ|سچے}} ایس ٹوٹے چ ورتے گۓ:',
'template-protected'               => '(بچایا گیا)',
'template-semiprotected'           => '(کج بچایا ہویا)',
'hiddencategories'                 => 'اے صفحہ {{PLURAL:$1|1 چھپی گٹھ|$1 چپھی گٹھیاں}} دا رکن اے:',
'nocreatetitle'                    => 'صفحہ بنانے دی حد اے',
'nocreatetext'                     => '{{SITENAME}} نے نۓ صفحے بنانے تے پابندی لائی اے۔<br />
تسی واپس جا کے پہلاں توں موجود صفحیاں تے لکھ سکدے او یا فیر [[Special:UserLogin|اندر آؤ یا نواں کھاتہ کھولو۔]]',
'nocreate-loggedin'                => 'توانوں نواں صفحہ بنانے دی اجازت نئیں۔',
'sectioneditnotsupported-title'    => 'سیکشن ایڈیٹنگ ناٹ سپورٹڈ',
'sectioneditnotsupported-text'     => 'ایس صفے تے پاسہ تبدیلی نئیں ہوسکدی۔',
'permissionserrors'                => 'توانوں اجازت چ کوئی مسئلا اے',
'permissionserrorstext'            => 'تھلے دتیاں گیا وجاں توں توانوں اینوں تبدیل کرن دی اجازت نئیں۔',
'permissionserrorstext-withaction' => 'تواڈے کول $2 کرن دی اجازت نئیں اے۔ اس دی {{PLURAL:$1|وجہ|وجوہات}} نیں۔',
'recreate-moveddeleted-warn'       => "'''خبردار: تسی اک پہلاں توں مٹایا ہویا صفحہ دوبارا لکھ رہے او۔'''

توانوں اے گل سوچنی چائیدی اے کہ اینو لکھنا کوئی عقلمنداں دا کم اے۔<div/>
تواڈی سہولت آسطے مٹان دا لاگ ایتھے موجود اے۔",
'moveddeleted-notice'              => 'اس صفحے نوں مٹا دتا گیا اے۔
مٹان دا لاگ تھلے دتا گیا اے۔',
'log-fulllog'                      => 'پوری لاگ ویکھو',
'edit-hook-aborted'                => 'تبدیلی ہک نال رکی۔ بنا وجہ توں۔',
'edit-gone-missing'                => 'اے صفہ نواں نئیں ہوسکیا۔
لکدا اے مٹا دتا گیا۔',
'edit-conflict'                    => 'تبدیلی رپھڑ۔',
'edit-no-change'                   => 'تواڈی تبدیلی ول کوئی توجہ نئیں، کیوں جے لکھت چ کوئی تبدیلی نئیں۔',
'edit-already-exists'              => 'نواں صفہ نئیں بن سکدا۔
ایہ پہلے ای ہیگا اے۔',

# Parser/template warnings
'expensive-parserfunction-warning'        => "'''خبردار:''' ایس صفے تے چوکھیاں ساریاں پارسر کلز نیں۔
ایدے چ 2$ توں تھوڑی {{انیک:$2|کال|کالاں}} ہونیاں چاسیدیاں نیں، ایتھے {{انیک:$1|ہن $1 کال|ہن $1 کالاں}}",
'expensive-parserfunction-category'       => 'صفے بعوت ساریاں ایکسپنسو فنکشن کالز نال',
'post-expand-template-inclusion-warning'  => "'''خبردار:''' ٹمپلیٹ ناپ چوکھا وڈا اے۔
کج ٹمپلیٹ نئیں پاۓ جان گے۔",
'post-expand-template-inclusion-category' => 'صفے جتھے ٹمپلیٹ ناپ وڈا ہوگیا اے۔',
'post-expand-template-argument-warning'   => "'''خبردار:''' ایس صفے تے اک ٹمپلیٹ گل بات دتی گئی اے جیہڑا چوکھا وڈا اے۔
اے گلاں کڈ دتیاں گیاں نیں۔",
'post-expand-template-argument-category'  => 'صفے جناں چ کڈے گۓ ٹمپلیٹ دیاں گلاں نیں۔',
'parser-template-loop-warning'            => 'ٹمپلیٹ لوپ لب لئی گئی: [[$1]]',
'parser-template-recursion-depth-warning' => 'ٹمپلیٹ ریکرشن ڈپتھ لمٹ ودی ($1)',
'language-converter-depth-warning'        => 'بولی بدلن دی ولگن ودی ($1)',

# "Undo" feature
'undo-success' => 'تبدیلیاں واپس ہوسکدیاں نیں۔
تھلے فرق ویکھو اے ویکھن لئی جے ایہو ای تسی چاندے او، تے تھلے تبدیلیاں بچاؤ، تبدیلیاں مکاں دی روک نوں۔',
'undo-failure' => 'تبدیلی واپس نئیں ہوسکدی وشکار ہویاں تبدیلیاں ہون دی وجہ توں۔',
'undo-norev'   => 'تبدیلی واپس نئیں ہوسکدی کیوں جے ایہ ہے ای نئیں یا مٹا دتی گئی اے۔',
'undo-summary' => '1$ دی کیتی ہوئی ریوین [[خاص:کم/$2|$2]] ([[ورتن گل:$2|گل]]) واپس کرو',

# Account creation failure
'cantcreateaccounttitle' => 'کھاتہ نئیں کھول سکدے',
'cantcreateaccount-text' => 'کھاتہ بنانا ایس آئی پی پتے لئی [[ورتن والا:$3|$3]] نے روک دتی اے۔
$3 نے $2 وجہ دسی اے۔',

# History pages
'viewpagelogs'           => 'صفحے دے لاگ ویکھو',
'nohistory'              => 'اس صفحے دی پرانی لکھائی دی کوئی تاریخ نئیں۔',
'currentrev'             => 'ہن آلی تبدیلی',
'currentrev-asof'        => '$1 ویلے دا صفحہ',
'revisionasof'           => 'دی تبدیلیاں $1',
'revision-info'          => '$2 نے $1 تے اے لکھیا',
'previousrevision'       => '← اوس توں پچھلا کم',
'nextrevision'           => 'نویں تبدیلی →',
'currentrevisionlink'    => 'موجودہ حالت',
'cur'                    => 'موجودہ',
'next'                   => 'اگلا',
'last'                   => 'آخری',
'page_first'             => 'پہلا',
'page_last'              => 'آخری',
'histlegend'             => 'ڈف سلیکشن: وکھری تبدیلیاں دا مقابلا کرن واسطے ریڈیو ڈبیاں تے نشان لاؤ تے اینٹر یا تھلے دتا گیا بٹن دباؤ۔<br />
لیجنڈ: (موجودہ) = موجودہ تبدیلی نال مقابلہ،
(آخری) = پچھلی تبدیلی توں فرق، M = تھوڑی تبدیلی',
'history-fieldset-title' => 'ریکارڈ ویکھو',
'history-show-deleted'   => 'صرف مٹایا گیا اے۔',
'histfirst'              => 'سب توں پہلا',
'histlast'               => 'سب توں نواں',
'historysize'            => '({{انیک:$1|1 بائٹ|$1 بائٹس}})',
'historyempty'           => '(خالی)',

# Revision feed
'history-feed-title'          => 'ریوین رکارڈ',
'history-feed-description'    => 'ریوین رکارڈ ایس صفے لئی وکی تے اے۔',
'history-feed-item-nocomment' => '$2 نوں $1',
'history-feed-empty'          => 'چائیدا صفہ ہے ای نئیں۔
ہوسکدا اے اینوں وکی توں ہٹا دتا گیا ہووے یا مٹادتا گیا ہووے۔
[[خاص:کھوج|وکی تے کھوج]] کرو چائیدے نویں صفیاں لئی۔',

# Revision deletion
'rev-deleted-comment'         => 'تبدیلی سمری مٹادتی گئی۔',
'rev-deleted-user'            => '(ورتن آلا ناں مٹ گیا)',
'rev-deleted-event'           => '(لاگ ایکشن ہٹادتا گیا)',
'rev-deleted-user-contribs'   => 'ورتن ناں یا آئی پی پتہ ہٹا دتا گیا - تبدیلیاں کماں دی لسٹ چوں لکائیاں',
'rev-deleted-text-permission' => 'ایس صفے دیاں ریویناں مٹا دتیاں گیاں نیں۔
اوناں بارے پوری کل [{{fullurl:{{#Special:Log}}/مٹاؤ|صفہ={{FULLPAGENAMEE}}}} مٹان لاگ] لبی جاسکدی اے۔',
'rev-deleted-text-unhide'     => 'ایس ڈف دیاں ریویناں چوں اک دبا دتی گئی اے۔ لمیاں گلاں [{{fullurl:{{#خاص:Log}}/suppress|page={{FULLPAGENAMEE}}}} دبن لاگ] چ لبیاں جاسکدیاں نیں۔
تسیں ہلے وی [$1 ایہ ڈف دیکھو] اگر تسیں اگے جاؤ۔
اے ویکھ کے [$1 ایس ریوین نوں ویکھو] تسیں اگے جاسکدے او اگر تسیں جانا چاندے او۔',
'rev-suppressed-text-unhide'  => 'ایس صفے دی ریوین دبادتی گئی اے۔ لمیاں گلاں [{{fullurl:{{#خاص:Log}}/suppress|page={{FULLPAGENAMEE}}}} دبن لاگ] چ لبیاں جاسکدیاں نیں۔
تسیں ہلے وی [$1 ایہ ڈف دیکھو] اگر تسیں اگے جاؤ۔',
'rev-deleted-text-view'       => 'ایس صفے دیاں ریویناں مٹا دتیاں گیاں نیں۔
تسیں اینوں ویکھ سکدے او پوری گل [{{fullurl:{{#خاص:لاگ}}/مٹاؤ|صفے={{FULLPAGENAMEE}}}} مٹان لاگ] چ ویکھی جاسکدی اے۔',
'rev-suppressed-text-view'    => 'اے طفا دبا دتا گیا اے۔
تسیں اینوں ویکھ سکدے او۔؛ لمی گل ایتھے [{{fullurl:{{#خاص:لاگ}}/دباؤ|صفہ={{پوراصفہناں}}}} دبان لاگ] ویھکی جاسکدی اے۔',
'rev-deleted-no-diff'         => 'تسیں ایس ڈف نوں نئیں مٹا سکدے کیوں جے ریویناں چوں اک مٹادتی گئی اے۔
پوری گل [{{پورییوآرایل:{{#خاص:لاگ}}/مٹاؤ|صفہ={{پوراصفہناں}}}}لاک مٹاؤ] تے ویکھی جاسکدی اے۔',
'rev-suppressed-no-diff'      => 'تسیں اے فرق نئیں ویکھ سکدے ریویناں چوں اک مٹا دتی گئی اے۔',
'rev-deleted-unhide-diff'     => 'ایس ڈف دیاڑ ریریناں چوں اک مٹا دتی گئی اے۔
لمی گل [{{پورییوآرایل:{{#خاط:لاگ}}/مٹاؤ|صفہ={{پورا صفہ ناں}}}} مٹان لاگ] تے ویکھی جاسکدی اے۔
تسیں ہلے وی [$1 ایہ ڈف ویکھو] اگر تسیں اگے جانا چاندے او۔',
'rev-suppressed-unhide-diff'  => 'ایس ڈف دیاں ریویناں چوں اک دبا دتی گئی اے۔ لمیاں گلاں [{{fullurl:{{#خاص:Log}}/suppress|page={{FULLPAGENAMEE}}}} دبن لاگ] چ لبیاں جاسکدیاں نیں۔
تسیں ہلے وی [$1 ایہ ڈف دیکھو] اگر تسیں اگے جاؤ۔',
'rev-deleted-diff-view'       => 'ایس ڈف دیاں ریویناں چوں اک مٹادتی گئی اے۔
تسیں ایس ڈف نوں ویکھ سکدے او لمیاں گلاں [{{fullurl:{{#خاص:لاگ}}/delete|page={{FULLPAGENAMEE}}}} مٹان لاگ]',
'rev-suppressed-diff-view'    => 'ایس ڈف دیاں ریویناں چوں اک دبا دتا گیا اے۔
تسیں اے ڈف ویکھ سکدے او؛ لمی گل [{{پورییوآرایل:{{#خاص:لاگ}}/دباؤ|صفہ={{پورا صفہناں}}}} دبن لاگ] ویکھی جاسکدی اے۔',
'rev-delundel'                => 'وکھاؤ/لکاؤ',
'rev-showdeleted'             => 'وکھاؤ',
'revisiondelete'              => 'ریوژن مٹاؤ یا واپس کرو',
'revdelete-nooldid-title'     => 'ناں منی جان والی تارگٹ ریوین',
'revdelete-nooldid-text'      => 'تساں یا تے اک تارگٹ دی ریوین نئیں دسی ایس کم نوں کرن لئی،
خاص ریوین ہے نئیں، یا فیر تسیں ہن دی تبدیلی نوں لکارۓ او۔',
'revdelete-nologtype-title'   => 'لاگ ٹائپ نئیں دسی گئی۔',
'revdelete-nologtype-text'    => 'ایہ کم کرن لئی تساں لاگ ٹائپ نئیں دسی۔',
'revdelete-nologid-title'     => 'ناں منی جان والی لاگ انٹری',
'revdelete-nologid-text'      => 'تساں یا تے اک خاص تارگٹ لاگ ایوینٹ دسیا ایس کم نوں کرن لئی یا خاص انٹری ہے ای نئیں',
'revdelete-no-file'           => 'فائل جیہڑی کئی گئی اے ہے ای نئیں۔',
'revdelete-show-file-confirm' => 'تساں نوں کیا پک اے جے تسیں فائل "<nowiki>$1</nowiki>" دی مٹائی ریوین  $2 توں $3 تک؟',
'revdelete-show-file-submit'  => 'ہاں',
'logdelete-selected'          => "'''{{انیک:$1|چنیا لاگ واقعہ|چنے لاگ واقعے}}:'''",
'revdelete-text'              => "'''مٹائیاں ریویناں تے واقعے صفے دے رکارڈ تے لاگ چ دسن گے، پر اودا کج حصہ عام لوکاں ل‏ی لکیا ہووے گا'''
دوجے مکھیا  {{سائیٹناں}} ہلے وی ایس قابل نیں جے لکی لکھت نوں ویکھ سکن تے اینوں واپس لے آن دوبارہ اودوں تک جے ایدے تے ہور روکاں ناں لا دتیا جان.",
'revdelete-suppress-text'     => "دبانا اودوں ای ٹھیک اے جدوں اے تھلے دتے کۓ مسلیاں لئی ہووے۔
* غلط جانکاری
**تھوڑی اپنے بارے جانکاری
*:''کعر دا پتہ تے فون نمبر.''",
'revdelete-hide-text'         => 'ریوژن ٹیکسٹ لکاؤ',
'revdelete-hide-image'        => 'فائل دا مواد لکاؤ',
'revdelete-hide-name'         => 'کم تے نشانہ چھپاؤ',
'revdelete-hide-comment'      => 'لکھن دے بارے چ صلاع لکاؤ',
'revdelete-hide-user'         => 'لکھن آلے دا ناں/آئی پی پتہ لکاؤ',
'revdelete-radio-set'         => 'ہاں',
'revdelete-radio-unset'       => 'نئیں',
'revdelete-suppress'          => 'چھڈن دی چنوتی',
'revdelete-unsuppress'        => 'واپس کیتیاں ریویناں چ روکاں نوں ہٹاؤ۔',
'revdelete-log'               => 'وجہ:',
'revdelete-success'           => "'''ریوین وکھالہ کامیابی نال نواں کردتا گیا اے.'''",
'revdelete-failure'           => "'''ریوین وکھالہ نویں نئیں کیتی جاسکدی:'''
$1",
'logdelete-success'           => "'''لاک وکھالہ کامیابی نال سیٹ کردتا گیا.'''",
'logdelete-failure'           => "'''لاک وکھالہ ویکھیا نئیں جاسکدا:'''
$1",
'revdel-restore'              => 'وکھالا بدلو',
'revdel-restore-deleted'      => 'مٹائیاں ریویناں',
'revdel-restore-visible'      => 'دےدیاں ریویناں',
'pagehist'                    => 'صفحے دی تاریخ',
'deletedhist'                 => 'مٹائی گئی تاریخ',
'revdelete-hide-current'      => 'آئیٹم نوں $2 تے $1 تریخاں چ لکان چ غلطی۔
ایہ نئیں لکائی جاسکدی۔',
'revdelete-show-no-access'    => 'غلطی ہوئی اے 2$، 1$ آئٹم دے دسن چ: ایہ آئٹم حد چ اے۔
تسیں ایدے تک نئیں جاسکدے او۔',
'revdelete-modify-no-access'  => '$2، $1 تریخ دی آئٹم بدلن چ غلطی ہوئی: ایہ آئٹم نوں حد چ رکھیا اے۔ تواڈی ایتھوں تک رسائی نئیں۔',
'revdelete-modify-missing'    => 'آئی ڈی آئیٹم $1 توں بدلن چ فلطی: ایہ ڈیٹابیس چ نئیں اے۔',
'revdelete-no-change'         => "'''خبردار:''' $2 تریخ دی آئیٹم، $1 پہلے ای دیس سیٹنگ لئی آکھی جاچکی اے۔",
'revdelete-concurrent-change' => 'آئیٹم نوں بدل دیاں غلطی $1 تریخ تے $2 ویلے نوں: ایدا سٹیٹس لگدا اے بدل دتا اے کسے نیں جدوں تسیں بدلن دی کوشش کیتی۔ مہربانی کرکے لاگ ویکھو۔',
'revdelete-only-restricted'   => '$1، $2 تریخاں دی آئیٹم  لکان چ غلطی: تسیں مکھیاں دے ویکھن توں نئیں لکا سکدے دوجے ویکھن والیاں چنوتیاں توں۔',
'revdelete-reason-dropdown'   => '*مٹان دیاں عام وجہاں
** کاپی حق توڑنا
** تھوڑی ذاتی جانکاری
** غلط دساں',
'revdelete-otherreason'       => ':دوجی وجہ',
'revdelete-reasonotherlist'   => 'ہور وجہ',
'revdelete-edit-reasonlist'   => 'مٹانے دی وجہ لکھو',
'revdelete-offender'          => 'ریوین لکھاری',

# Suppression log
'suppressionlog'     => 'دبان لاگ',
'suppressionlogtext' => 'تھلے مٹان تے روکن دے کم دی لسٹ اے جیہڑا مکھیاواں کولوں لکیا اے۔
[[خاص:روک لسٹ|آئی پی روک لسٹ]] ویکھو  ہن دی اوپریشنل بنداں تے روکاں تے۔',

# History merging
'mergehistory'                     => 'رلے صفہ رکارڈ',
'mergehistory-header'              => 'ایہ صفہ توانوں اک سورس صفے دیاں ریویناں دا رکارڈ اک ہور صفے چ رلان دیوے گا۔
ایہ گل پکی کرو جے تبدیلی رکارڈ دی لکاتاری نوں رکھے گی۔',
'mergehistory-box'                 => 'دوصفیاں دیاں رلیاں ریویني',
'mergehistory-from'                => 'ذریعے آلا صفحہ:',
'mergehistory-into'                => 'اصلی صفحہ:',
'mergehistory-list'                => 'رلنوالا لکھت رکارڈ',
'mergehistory-merge'               => 'تھلے دتیاں گیاں ریویناں [[:$1]] نوں [[:$2]] چ رلایا جاسادا اے۔
ریڈیو بٹن کالم نوں ورتو، رلان لئی صرف ریویناں بناں لئی خاص ویلے توں پہلے یا اوس ویلے۔
اے گل یاد رکھو جے لبن جوڑ ایس کالم نوں ریسیٹ کردین گے۔',
'mergehistory-go'                  => 'رلن والیاں لکھتاں وکھاؤ',
'mergehistory-submit'              => 'ریویسن رلاؤ',
'mergehistory-empty'               => 'کوئی ریوین رلائی نئیں جاسکدی',
'mergehistory-success'             => '$3 {{PLURAL:$3|ریوین}} [[:$1]] دی [[:$2]] چ رلا دتی گئی اے۔',
'mergehistory-fail'                => 'رکارڈ کٹھا نئیں کیتا جاسکدا، صفہ دوبارہ ویکھو تے ویلے دا پیرامیٹر چیک کرو۔',
'mergehistory-no-source'           => 'سورس صفہ ہے نئیں۔',
'mergehistory-no-destination'      => 'اپڑن صفہ ہے ای نئیں۔',
'mergehistory-invalid-source'      => 'سورس صفے دا سرناواں ٹھیک ہونا چائیدا اے۔',
'mergehistory-invalid-destination' => 'اپڑن صفے دا سرناواں ٹھیک ہونا چائیدا اے۔',
'mergehistory-autocomment'         => '[[:$1]] رلایا [[:$2]] وچ',
'mergehistory-comment'             => '[[:$1]] رلایا [[:$2]] چ : $3',
'mergehistory-same-destination'    => 'سورس تے لبن والے صفے اک نئیں ہوسکدے۔',
'mergehistory-reason'              => 'وجہ:',

# Merge log
'mergelog'           => 'لاگ رلاؤ',
'pagemerge-logentry' => '[[$1]] رلایا [[$2]] چ  (ریوین  $3)',
'revertmerge'        => 'وکھریاں کرو',
'mergelogpagetext'   => 'تھلے اک صفے والے کٹھے کیتے گۓ صفے دی لسٹ اے۔',

# Diffs
'history-title'            => '"$1" دا ریکارڈ',
'difference'               => '(صفحیاں وچ فرق)',
'difference-multipage'     => '(صفیاں چ فرق)',
'lineno'                   => 'لیک $1:',
'compareselectedversions'  => 'چنے صفحے آپنے سامنے کرو',
'showhideselectedversions' => 'وکھاؤ/لکاؤ چنیاں دہرائیاں',
'editundo'                 => 'واپس',
'diff-multi'               => '!!!!({{PLURAL:$1|One intermediate revision|$1 درمیانی تبدیلی}} نئیں وکھائی گئی۔)',
'diff-multi-manyusers'     => '({{انیک:$1|اک وشکارلی ریوین|$1 وشکارلیاں ریویناں}} توں ود $2 {{انیک:$2|ورتن والا|ورتن والا}} نئیں دسی گئی)',

# Search results
'searchresults'                    => 'کھوج دا نتارا',
'searchresults-title'              => '"$1" دے کھوج نتارے',
'searchresulttext'                 => 'وکیپیڈیا چ کھوجن دے بارے چ ہور معلومات آستے کھوجن دا صفحہ ویکھو',
'searchsubtitle'                   => "تواڈی لفظ '''[[:$1]] آستے کھوج",
'searchsubtitleinvalid'            => "'''$1''' آستے کھوج کیتی",
'toomanymatches'                   => 'چوکھے سارے رلدے جوڑے سامنے آے نیں، اک ہور کھوج دی کوشش کرو۔',
'titlematches'                     => 'صفے دا سرناواں رلدا اے',
'notitlematches'                   => 'اے لفظ کسی صفحے دے ناں چ نئیں اے۔',
'textmatches'                      => 'صفہ لکھت رلدا',
'notextmatches'                    => 'کوئی صفح نئیں لبیا',
'prevn'                            => 'پہلا {{PLURAL:$1|$1}}',
'nextn'                            => 'اگلا {{PLURAL:$1|$1}}',
'prevn-title'                      => 'پہلے $1 {{PLURAL:$1|نتیجے}}',
'nextn-title'                      => 'اگلے $1 {{PLURAL:$1|نتیجے}}',
'shown-title'                      => 'وکھاؤ $1 {{PLURAL:$1|نتیجے}}',
'viewprevnext'                     => 'ویکھو ($1 {{int:pipe-separator}} $2) ($3)',
'searchmenu-legend'                => 'چنوتیاں کھوجو',
'searchmenu-exists'                => "'''ایس وکی تے \"[[:\$1]]\" ناں دا صفہ ہے۔.'''",
'searchmenu-new'                   => "'''ایس وکی تے \"[[:\$1]]\" بناؤ'''",
'searchhelp-url'                   => 'Help:فہرست',
'searchmenu-prefix'                => '[[خاص:پریفکسانڈکس/$1|ایس پریفکس نال صفے کھوجو]]',
'searchprofile-articles'           => 'لسٹ صفے',
'searchprofile-project'            => 'مدد تے ویونت صفے',
'searchprofile-images'             => 'ملٹیمیڈیا',
'searchprofile-everything'         => 'ہرشے',
'searchprofile-advanced'           => 'اگلا',
'searchprofile-articles-tooltip'   => '$1 چ لبو',
'searchprofile-project-tooltip'    => '$1 چ لبو',
'searchprofile-images-tooltip'     => 'فائلاں لئی لبو',
'searchprofile-everything-tooltip' => 'سارا مواد لبو (گل بات والے صفے وی)',
'searchprofile-advanced-tooltip'   => 'کسٹم ناواں چ لبو',
'search-result-size'               => '$1 ({{PLURAL:$2|1 لفظ|$2 الفاظ}})',
'search-result-category-size'      => '{{انیک:$1|1 سنگی|$1 سنگی}} ({{انیک:$2|1 نکیاں گٹھاں|$2 نکیاں گٹھاں}}, {{انیک:$3|1 فائل|$3 فائلاں}})',
'search-result-score'              => 'مہاندرا:  $1%',
'search-redirect'                  => '($1 ریڈائریکٹ)',
'search-section'                   => '($1 ٹوٹا)',
'search-suggest'                   => 'تسی $1 دی گل تے نئیں کر رۓ:',
'search-interwiki-caption'         => 'نال دے منصوبے',
'search-interwiki-default'         => '$1 نتارے:',
'search-interwiki-more'            => '(اور)',
'search-mwsuggest-enabled'         => 'صلاع دے نال',
'search-mwsuggest-disabled'        => 'کوئی صلاع نئیں',
'search-relatedarticle'            => 'جڑیاں',
'mwsuggest-disable'                => 'اجاکس مشورے نکارہ کرو',
'searcheverything-enable'          => 'ہر ناں چ لبو',
'searchrelated'                    => 'جڑیا',
'searchall'                        => 'سارے',
'showingresults'                   => "تھلیوں دسے گۓ  {{انیک:$1|'''1''' نتیجہ|'''$1''' نتیجے}}  شروع #'''$2'''.",
'showingresultsnum'                => "تھلے دسدا اے {{انیک:$3|'''1''' نتیجہ|'''$3''' نتیجے}} #'''$2''' توں ٹرن والے۔",
'showingresultsheader'             => "{{انیک:$5|نتیجہ '''$1''' دا '''$3'''|نتیجے '''$1 - $2''' دے '''$3'''}} لئی '''$4'''",
'nonefound'                        => "'''صفحیاں دے ناں ڈیفالٹ تے کھوجے جاندے نیں'''
اپنے لفظ توں پہلاں ''all:'' لا کے کھوجو۔ اس نال گلاں باتاں آلے صفحے، سچے وغیرہ سب چ تواڈا لفظ کھوجیا جاۓ گل۔",
'search-nonefound'                 => 'سوال نال رلدے کوئی نتارے نئیں سن۔',
'powersearch'                      => 'ودیا کھوج',
'powersearch-legend'               => 'ہور کھوج',
'powersearch-ns'                   => 'ناں الیاں جگہاں چ لبو:',
'powersearch-redir'                => 'ریڈائریکٹس دی لسٹ وکھاؤ',
'powersearch-field'                => 'لئی کھوج',
'powersearch-togglelabel'          => 'ویکھو:',
'powersearch-toggleall'            => 'سارے',
'powersearch-togglenone'           => 'کوئی نئیں',
'search-external'                  => 'باہر دی کھوج',
'searchdisabled'                   => '{{SITENAME}} کھوج کم نئیں کررئی۔
تسیں گوگل تے کھوج کرو۔
اے گل یاد رکھنا جے انڈیکس {{SITENAME}} دے پرانے ہون۔',

# Quickbar
'qbsettings'                => 'کوئکبار',
'qbsettings-none'           => 'کوئی نئیں',
'qbsettings-fixedleft'      => 'فکسڈ کھبے',
'qbsettings-fixedright'     => 'فکسڈ سجے',
'qbsettings-floatingleft'   => 'ہلدا کھبے',
'qbsettings-floatingright'  => 'ہلدا سجے',
'qbsettings-directionality' => '!!!!فکسڈ، تھاڈی بولی تے لپی نال',

# Preferences page
'preferences'                   => 'تانگاں',
'mypreferences'                 => 'میریاں تانگاں',
'prefs-edits'                   => 'تبدیلیاں دی گنتی:',
'prefsnologin'                  => 'لاگ ان نئیں او',
'prefsnologintext'              => 'تسیں لازمی <span class="plainlinks">[{{fullurl:{{#Special:UserLogin}}|returnto=$1}} لاگ ان]</span> ورتن تانگاں سیٹ کرنا.',
'changepassword'                => 'کنجی بدلو',
'prefs-skin'                    => 'کھل',
'skin-preview'                  => 'کچا کم',
'datedefault'                   => 'خاص پسند نئیں',
'prefs-beta'                    => 'بیٹا فیچرز',
'prefs-datetime'                => 'تاریخ تے ویلہ',
'prefs-labs'                    => 'لیبز فیچرز',
'prefs-personal'                => 'ورتن آلے دا پروفائل',
'prefs-rc'                      => 'نویاں تبدیلیاں',
'prefs-watchlist'               => 'نظر تھلے صفحے',
'prefs-watchlist-days'          => 'اکھ تھلے رکھی لسٹ چ دسے گۓ دن:',
'prefs-watchlist-days-max'      => 'زیادہ توں زیادہ 7 دن',
'prefs-watchlist-edits'         => 'ودائی ہوئی اکھ تھلے رکھی لسٹ چ زیادہ توں زیادہ نمبر دسو۔',
'prefs-watchlist-edits-max'     => 'سب تون وڈا نمبر: 1000',
'prefs-watchlist-token'         => 'واچلسٹ ٹوکن:',
'prefs-misc'                    => 'رلیا ملیا',
'prefs-resetpass'               => 'کنجی بدلو',
'prefs-changeemail'             => 'ای-میل بدلو',
'prefs-setemail'                => 'ای-میل پتہ سیٹ کرو',
'prefs-email'                   => 'ای-میل چنوتیاں',
'prefs-rendering'               => 'وکھالہ',
'saveprefs'                     => 'بچاؤ',
'resetprefs'                    => 'ناں بچائیاں ہویاں تبدیلیاں مکاؤ',
'restoreprefs'                  => 'ڈیفالٹ سیٹنگز دوبارہ لیاؤ',
'prefs-editing'                 => 'لکھائی',
'prefs-edit-boxsize'            => 'تبدیلی کرن والی ونڈو دا ناپ',
'rows'                          => 'قطار:',
'columns'                       => 'کالم:',
'searchresultshead'             => 'کھوج',
'resultsperpage'                => 'ہر صفے ویکھیا گیا:',
'stub-threshold'                => 'بوآ <a href="#" class="stub">stub link</a> formatting (bytes): لئی',
'stub-threshold-disabled'       => 'ناکارہ',
'recentchangesdays'             => 'نیڑے دیاں ہویاں تبدیلیاں چ دن دسو:',
'recentchangesdays-max'         => 'میکسیمم $1 {{انیک:$1|دن|دن}}',
'recentchangescount'            => 'ڈیفالٹ چ تبدیلیاں دی گنتی:',
'prefs-help-recentchangescount' => 'ہن دیاں تبدیلیاں صفیاں دے رکارڈ تے لاگاں ہیگیاں نیں۔',
'prefs-help-watchlist-token'    => 'ایس فیلڈ نوں پعرنا اک لکی کنجی نال تواڈی اکھ تھلے رکھی لسٹ لئی اک آر ایس ایس فیڈ بناۓ گا۔ کوئی وی ایس فیلڈ چ جیہڑا کنجی جاندا اے  تواڈی اکھ تھلے رکھی  لسٹ نوں پڑھ لے گا، ایس لئی اک پکا نمبر چنو۔
ایتھے اک الٹا پلٹا نمبر دتا گیا اے جینوں تسیں ورت سکدے او: $1',
'savedprefs'                    => 'تواڈیاں تانگاں بچا لئیاں گئیاں نیں۔',
'timezonelegend'                => 'ویلے دا علاقہ',
'localtime'                     => 'مقامی ویلا:',
'timezoneuseserverdefault'      => 'وکی ڈیفالٹ ($1) ورتو۔',
'timezoneuseoffset'             => 'دوجے (آفسٹ دسو)',
'timezoneoffset'                => 'آفسیٹ:',
'servertime'                    => 'سرور دا ویلا:',
'guesstimezone'                 => 'براؤزر توں پعرو۔',
'timezoneregion-africa'         => 'افریقہ',
'timezoneregion-america'        => 'امریکہ',
'timezoneregion-antarctica'     => 'انٹارکٹکا',
'timezoneregion-arctic'         => 'آرکٹک',
'timezoneregion-asia'           => 'ایشیاء',
'timezoneregion-atlantic'       => 'بحر اوقیانوس',
'timezoneregion-australia'      => 'آسٹریلیا',
'timezoneregion-europe'         => 'یورپ',
'timezoneregion-indian'         => 'بحر ہند',
'timezoneregion-pacific'        => 'بحر الکاحل',
'allowemail'                    => 'دوجے ورتن آلیاں توں ای-میل آن دیو',
'prefs-searchoptions'           => 'چنوتیاں کھوجو',
'prefs-namespaces'              => 'ناواں دی جگہ:',
'defaultns'                     => 'نئیں تے ایناں ناں تھاواں تے کھوج کرو:',
'default'                       => 'ڈیفالٹ',
'prefs-files'                   => 'فائلاں',
'prefs-custom-css'              => 'کسٹم سی ایس ایس',
'prefs-custom-js'               => 'کسٹم جاواسکرپٹ',
'prefs-common-css-js'           => 'سی ایس ایس/جاواسکرپٹ شئیر کرو ہر وکھالے لئی:',
'prefs-reset-intro'             => 'تسیں ایس صفے نوں کسے سائٹ دی ڈیفالٹ دی چنوتیاں مرضی دیاں کرن ورت سکدے او۔

اے واپس نئیں ہوسکدا۔',
'prefs-emailconfirm-label'      => 'ای-میل کنفرمیشن:',
'prefs-textboxsize'             => 'تبدیلی کرن والی ونڈو دا ناپ',
'youremail'                     => 'ای میل:',
'username'                      => 'ورتن آلے دا ناں:',
'uid'                           => 'ورتن والے دی آئی ڈی',
'prefs-memberingroups'          => 'سنگی {{PLURAL:$1|ٹولی|ٹولیاں}}:',
'prefs-registration'            => 'رجسٹریشن ویلہ:',
'yourrealname'                  => 'اصلی ناں:',
'yourlanguage'                  => 'بولی:',
'yourvariant'                   => 'ورتی بولی دی اک ہور ونڈ:',
'prefs-help-variant'            => 'تسیں وکھرے یا اورتوگرافی چنی اے ایس وکی دیاں لکھتاں نوں دکھان لئی۔',
'yournick'                      => 'دسخط:',
'prefs-help-signature'          => 'گل بات صفے تے "<nowiki>~~~~</nowiki>"  دے نال دسخط ہونے چائیدے نیں جناں نوں دسخط تے ویلے چ دسیا جائیگا۔',
'badsig'                        => 'ناں منیا جان والا کچا دسخط۔
ایچ ٹی ایم ایل ٹیگ۔',
'badsiglength'                  => 'تھواڈے دسخط بعوت لمبے نیں۔

اے $1 توں لمبے ناں ہون۔',
'yourgender'                    => 'جنس',
'gender-unknown'                => 'نئیں دسیا گیا۔',
'gender-male'                   => 'نر',
'gender-female'                 => 'مادہ',
'prefs-help-gender'             => 'اوپشنل: جینڈر-کوریکٹ ٹھیک کرن لئی ورتیا گیا
ایہ جانکاری عام ہونی چائیدی اے۔',
'email'                         => 'ای میل',
'prefs-help-realname'           => 'اصل ناں تواڈی مرزی تے اے۔<br />
اگر تسی اینو دے دیو گۓ تے اے تواڈا کم اس ناں نال لکھیا جاۓ گا۔',
'prefs-help-email'              => 'ای-میل پتہ اوپشنل اے، پر کنجی ٹھیک کرن لئی ورتیا جاندا اے، کیا تسیں اپنی کنجی پعل جاؤگے۔',
'prefs-help-email-others'       => 'تسیں آپ چن سکدے او جے توانوں ملیا جاوے ای-میل توں  تواڈے جوڑ توں تواڈے ورتن والے یا گل بات صفے تے۔
تواڈا ای-میل پتہ نئیں دسیا جاندا جدوں دوجے ورتن والے توانوں ملدے نیں۔',
'prefs-help-email-required'     => 'ای میل پتہ چائیدا اے۔',
'prefs-info'                    => 'مڈلی جانکاری',
'prefs-i18n'                    => 'انٹرنیشنلائزیشن',
'prefs-signature'               => 'دسخط',
'prefs-dateformat'              => 'تریخ فارمیٹ',
'prefs-timeoffset'              => 'ٹائم آفسیٹ',
'prefs-advancedediting'         => 'ہور چنوتیاں',
'prefs-advancedrc'              => 'ہور چنوتیاں',
'prefs-advancedrendering'       => 'ہور چنوتیاں',
'prefs-advancedsearchoptions'   => 'ہور چنوتیاں',
'prefs-advancedwatchlist'       => 'ہور چنوتیاں',
'prefs-displayrc'               => 'چنوتیاں دسو',
'prefs-displaysearchoptions'    => 'چنوتیاں دسو',
'prefs-displaywatchlist'        => 'چنوتیاں دسو',
'prefs-diffs'                   => 'ڈفز',

# User preference: e-mail validation using jQuery
'email-address-validity-valid'   => 'ای_میل پتہ ٹھیک لگدا اے۔',
'email-address-validity-invalid' => 'چلن والا ای-میل پتہ دسو',

# User rights
'userrights'                   => 'ورتن والیاں دے حقاں دا سعاب کتاب',
'userrights-lookup-user'       => 'ورتن ٹولی بچاؤ',
'userrights-user-editname'     => 'اک ورتن والا ناں لکھو:',
'editusergroup'                => 'ورتن ٹولی چ تبدیلی',
'editinguser'                  => "تواڈے ورتن حق بدل رۓ آں '''[[ورتن والا:$1|$1]]''' $2۔",
'userrights-editusergroup'     => 'ورتن ٹولی',
'saveusergroups'               => 'ورتن ٹولی بچاؤ',
'userrights-groupsmember'      => 'سنگی اے:',
'userrights-groupsmember-auto' => 'سنگی اے:',
'userrights-groups-help'       => 'تسیں ایس ورتن والے دیاں ٹولیاں بدل سکدے او:
* اک چیکڈ ڈبی دا اے مطلب اے جے ورتن والا ایس ٹولی ج اے۔
* اک ناں چیک ڈبی دا ایہ مطلب اے جے ورتن والا ایس ٹولی چ نئیں۔
*اے* دسدا اے جے  تسیں ٹولی ہٹا نئیں سکدے جدوں تسیں اینوں جوڑ دیو یا ایس توں الٹ۔',
'userrights-reason'            => 'وجہ:',
'userrights-no-interwiki'      => 'تساں نوں ورتن حق بدلن دی اجازت دوسرے وکی تے نئیں۔',
'userrights-nodatabase'        => 'ڈیٹابیس $1 ہے ای نئیں یا لوکل نئیں۔',
'userrights-nologin'           => 'تسیں لازمی [[خاص:ورتنلاگان|لاگ ان]] اک مکھیا کھاتے نال  اپنے ح‍اں لئی۔',
'userrights-notallowed'        => 'تواڈے کھاتے نوں اے اجازت نئیں جے اے ورتن حق دے سکے۔',
'userrights-changeable-col'    => 'ٹولیاں جیہڑیاں تسی بدل ےکدے او۔',
'userrights-unchangeable-col'  => 'ٹولیاں جیہڑیاں تسی بدل نئیں سکدے',

# Groups
'group'               => 'ٹولی:',
'group-user'          => 'ورتن آلے',
'group-autoconfirmed' => 'اپنے آپ منے گۓ ورتن والے',
'group-bot'           => 'بوٹ',
'group-sysop'         => 'مکھیۓ',
'group-bureaucrat'    => 'بیوروکریٹ',
'group-suppress'      => 'چھڈیا گیا',
'group-all'           => '(سارے)',

'group-user-member'          => '{{جنس:$1|ورتن والا}}',
'group-autoconfirmed-member' => '{{جنس:$1|اپنے آپ منے گۓ ورتن والے}}',
'group-bot-member'           => '{{جنس:$1|بوٹ}}',
'group-sysop-member'         => '{{جنس:$1|مکھیا}}',
'group-bureaucrat-member'    => '{{جنس:$1|بیوروکریٹ}}',
'group-suppress-member'      => '{{جنس:$1|چھڈی گئی}}',

'grouppage-user'          => '{{ns:project}}:ورتن آلے',
'grouppage-autoconfirmed' => '{{ns:project}}:اپنے آپ پکا ہون والا ورتن والا',
'grouppage-bot'           => '{{ns:project}}:بوٹ',
'grouppage-sysop'         => '{{ns:project}}:ایڈمنسٹریٹر',
'grouppage-bureaucrat'    => '{{ns:project}}:بیوروکریٹ',
'grouppage-suppress'      => '{{ns:project}}:چھڈیا گیا',

# Rights
'right-read'                  => 'صفحے پڑھو',
'right-edit'                  => 'صفحے لکھو',
'right-createpage'            => 'صفحے بناؤ (جیڑے کے گلاں باتاں آلے نئیں نیں)۔',
'right-createtalk'            => 'گلاں باتاں آلے صفحے بناؤ',
'right-createaccount'         => 'ورتن آلیاں دے نوے اکاونٹ کھولو',
'right-minoredit'             => 'لکھائی نوں چھوٹیاں موٹیاں قرار دے دیو',
'right-move'                  => 'صفحے لے چلو',
'right-move-subpages'         => 'اس صفحے نوں تے ایدے نال دے جڑے صفحیاں نوں لے چلو',
'right-move-rootuserpages'    => 'ورتن جڑ صفے لے چلو',
'right-movefile'              => 'فائلاں لے چلو۔',
'right-suppressredirect'      => 'جدوں صفے بل رۓ ہوو تے سورس توں ریڈائرکٹس ناں بناؤ',
'right-upload'                => 'فائل چڑہاؤ',
'right-reupload'              => 'پہلاں دی لکھی ہوئی فائل دے اتے لکھو',
'right-reupload-own'          => 'آپ چڑھائیاں ہوئیاں فائلاں تے لکھو۔',
'right-reupload-shared'       => 'رلی میڈیا فائلاں تے چڑھاؤ',
'right-upload_by_url'         => 'ۃڈي توں چرھائی گئی فاغلاں',
'right-purge'                 => 'جیہڑے صفے دی پک ناں ہووے اوس دی سائٹ کاشے صاف کرو',
'right-autoconfirmed'         => 'کج بچاۓ گۓ صفے نوں تبدیل کرو۔',
'right-bot'                   => 'اپنے آپ ہوندے کم ورگا ورتو',
'right-nominornewtalk'        => 'نکیاں تبدیلیاں کوئی نين گل بات والے صفے تے جیہڑیاں نویں سنیعے نون ٹران',
'right-apihighlimits'         => 'API  کھوجاں چ آخدی جد تک جاؤ',
'right-writeapi'              => 'API دا ورتن',
'right-delete'                => 'صفحے مٹاؤ',
'right-bigdelete'             => 'لمبیاں تاریخاں آلے صفحے مٹاؤ',
'right-deleterevision'        => 'مٹاؤ تے واپس لیاؤ صفیاں دیاں خاص ریوین',
'right-deletedhistory'        => 'مٹایا ہویا ریکارڈ ویکھو بنا اودیاں لکھتاں دے۔',
'right-deletedtext'           => 'مٹائی لکھت تے مٹیاں ریویناں دیاں تبدیلیاں ویکھو۔',
'right-browsearchive'         => 'مٹاۓ ہوۓ صفحے کھوجو',
'right-undelete'              => 'مٹایا صفحہ واپس لیاو',
'right-suppressrevision'      => 'اوہ ریویناں  نوں دوبارہ لیاؤ تے ویکھو جیہڑیاں مکھیاں توں لکیاں نیں۔',
'right-suppressionlog'        => 'پرائیویٹ لاگز ویکھو',
'right-block'                 => 'دوجے ورتن والیاں نوں لکھن توں روکو',
'right-blockemail'            => 'ورتن آلے نوں ای میل پیجن توں روکو',
'right-hideuser'              => 'لوکاں توں چھپاندے ہویاں اک ورتن آلے نوں روکو',
'right-ipblock-exempt'        => 'آئی پی روک، اپنے آپ روکاں تے رینج روکاں ول تعیاں ناں دیو',
'right-proxyunbannable'       => 'پراکسیز دے اپنے آپ روکاں تے تعیاں ناں دیو',
'right-unblockself'           => 'اپنے آپ کھولو',
'right-protect'               => 'بچاؤ پدھر نوں بدلو تے بچاۓ صفیاں نوں بدلو',
'right-editprotected'         => 'بچاۓ صفے بدلو',
'right-editinterface'         => 'ورتن وکھالہ بدلو',
'right-editusercssjs'         => 'دوجے ورتن والیاں دیاں  CSS  تے JavaScript  فائلاں بدلو',
'right-editusercss'           => 'دوجے ورتن والیاں دیاں CSS  فائلاں بدلو',
'right-edituserjs'            => 'دوجے ورتن والیاں دیاں  JavaScript  فائلاں بدلو',
'right-rollback'              => 'جلدی نال آخری ورتن والے دیاں تبدیلیاں اک خاص صفے تے واپس کرو۔',
'right-markbotedits'          => 'پچھے کیتیاں تبدیلیاں تے بوٹ دی تبدیلی دا نشان لگاؤ',
'right-noratelimit'           => 'ریٹ حد تے ناں پریشان ہوو',
'right-import'                => 'دوجے وکیاں توں صفے لیاؤ',
'right-importupload'          => 'چڑھائی ہووئی فائل توں صفے لیاؤ',
'right-patrol'                => 'دوجے تبدیلیاں تے گشت دا نشاں لاؤ',
'right-autopatrol'            => 'اپنیاں تبدیلیاں تے اپنے آپ گشت دا نشان لگ جاوے۔',
'right-patrolmarks'           => 'ہن ہوئیاں تبدیلیاں تے گشت نشان ویکھو',
'right-unwatchedpages'        => 'بنا اکھ تھلے رکھیاں صفیاں دی لسٹ ویکھو',
'right-trackback'             => 'اک ٹریکبیک دسو',
'right-mergehistory'          => 'صفیاں دا رکارڈ رلاؤ',
'right-userrights'            => 'تمام ورتن آلیاں دے حق رلکھو',
'right-userrights-interwiki'  => 'ورتن والے دے تبدیلی دے حقاں نوں دوجے وکی تے ورتو۔',
'right-siteadmin'             => 'ڈیٹابیس نوں کھولو تے بند کرو',
'right-override-export-depth' => '5 تک صفے تے جڑے صفے لے کے جاؤ',
'right-sendemail'             => 'دوجے ورتن والیاں نوں ای-میل کرو',
'right-passwordreset'         => 'کنجی بدلی ای-میلاں نوں وکھاؤ',

# User rights log
'rightslog'                  => 'ورتن والے دے حقاں دی لاگ',
'rightslogtext'              => 'ورتن حقاں چ تبدیلیاں دی اے لاگ اے۔',
'rightslogentry'             => 'ٹولی ممرشپ $1 لئی $2 توں $3 تک بدلو۔',
'rightslogentry-autopromote' => '$2 توں اپنے آپ $3 تک ودایا گیا۔',
'rightsnone'                 => '(کوئی وی نئیں)',

# Associated actions - in the sentence "You do not have permission to X"
'action-read'                 => 'اس صفحے نوں پڑھو',
'action-edit'                 => 'اس صفحے تے لکھو',
'action-createpage'           => 'صفحے بناؤ',
'action-createtalk'           => 'گلاں باتاں آلا صفحہ بناؤ',
'action-createaccount'        => 'اے ورتن والا کھاتہ کھولو',
'action-minoredit'            => 'ایس تبدیلی نوں نکا دسو۔',
'action-move'                 => 'اس صفحے نوں لے جاؤ',
'action-move-subpages'        => 'اس صفحے نوں تے ایدے نال دے جڑے صفحیاں نوں لے چلو',
'action-move-rootuserpages'   => 'ورتن جڑ صفے لے چلو',
'action-movefile'             => 'اس فائل نوں لے جاؤ',
'action-upload'               => 'اس فائل نوں اتے چاڑو',
'action-reupload'             => 'اس پہلاں توں موجود فائل دے اتے لکھو',
'action-reupload-shared'      => 'سانجھی ریپوزیٹری تے ایس فائل تے ہور جڑھاؤ',
'action-upload_by_url'        => 'کسے URL توں اے فائل چڑھاؤ',
'action-writeapi'             => 'API دا ورتن',
'action-delete'               => 'اس صفحے نوں مٹا دیو',
'action-deleterevision'       => 'ایس ریوین نوں مٹاؤ',
'action-deletedhistory'       => 'صفے دا مٹایا ہویا رکارڈ ویکھو',
'action-browsearchive'        => 'مٹاۓ گۓ صفحے کھوجو',
'action-undelete'             => 'اس صفحے نوں واپس لیاؤ',
'action-suppressrevision'     => 'ویکھو تے لکی ریوین نوں فیر لے اؤ۔',
'action-suppressionlog'       => 'ایس پرائیویٹ لاگ نوں ویکھو',
'action-block'                => 'اس ورتن آلے نوں لکھن توں روکو',
'action-protect'              => 'اس صفحے دے بچاؤ دا درجہ بدلو',
'action-import'               => 'کسے ہور وکی توں اے صفہ لے کے آؤ',
'action-importupload'         => 'چڑھائی ہووئی فائل توں صفے لیاؤ',
'action-patrol'               => 'دوجے دیاں تبدیلیاں تے گشت دا نشاں لاؤ',
'action-autopatrol'           => 'کیا تساں تبدیلی دے نشاں تے گشت دا نشان لایا',
'action-unwatchedpages'       => 'بنا اکھ تھلے رکھیاں صفیاں دی لسٹ ویکھو',
'action-trackback'            => 'اک ٹریکبیک دسو',
'action-mergehistory'         => 'ایس صفے دا رکارڈ رلاؤ',
'action-userrights'           => 'ورتن ح‌ق چ تبدیلی کرو',
'action-userrights-interwiki' => 'ورتن حقاں نوں دوجے وکیاں تے تبدیل کرو۔',
'action-siteadmin'            => 'ڈیٹابیس نوں کھولو یا بند کرو',

# Recent changes
'nchanges'                          => '$1 {{PLURAL:$1|change|تبدیلیاں}}',
'recentchanges'                     => 'نویاں تبدیلیاں',
'recentchanges-legend'              => 'نویاں تبدیلیاں دیاں راواں',
'recentchangestext'                 => 'ایس وکی تے نیڑے نیڑے ہون والیاں تبدیلیاں ایس صفے تے دسو۔',
'recentchanges-feed-description'    => 'اس فیڈ وچ وکی تے ہوئیاں نویاں تبدیلیاں نو ویکھو۔',
'recentchanges-label-newpage'       => 'ایس تبدیلی نے نواں صفہ بنایا اے۔',
'recentchanges-label-minor'         => 'اے اک چھوٹی تبدیلی اے۔',
'recentchanges-label-bot'           => 'ایس تبدیلی نوں بوٹ نے کیتا اے۔',
'recentchanges-label-unpatrolled'   => 'ایس تبدیلی تے ہلے گشت نئیں ہوئی۔',
'rcnote'                            => "تھلے $5،$4 تک {{PLURAL:$2|آخری '''$2''' دناں دی }} {{PLURAL:$1|'''$1''' تبدیلیاں نیں}}۔",
'rcnotefrom'                        => "ہلے تک '''$2''' توں '''$1''' تبدیلیاں تھلے دتیاں گئیاں نیں۔",
'rclistfrom'                        => '$1 توں ہونے آلیاں نویاں تبدیلیاں وکھاؤ',
'rcshowhideminor'                   => '$1 معمولی تبدیلیاں',
'rcshowhidebots'                    => '$1 بوٹ',
'rcshowhideliu'                     => '$1 ورتن آلے اندر نیں',
'rcshowhideanons'                   => '$1 گمنام ورتن والے',
'rcshowhidepatr'                    => '$1 ویکھی گئی لکھائی',
'rcshowhidemine'                    => '$1 میرے کم',
'rclinks'                           => 'آخری $2 دناں دیاں $1 تبدیلیاں وکھاؤ<br />$3',
'diff'                              => 'فرق',
'hist'                              => 'پچھلا کم',
'hide'                              => 'چھپاؤ',
'show'                              => 'وکھاؤ',
'minoreditletter'                   => 'چھوٹا کم',
'newpageletter'                     => 'نواں',
'boteditletter'                     => 'بوٹ',
'number_of_watching_users_pageview' => '$1 ویکھن والے {{انیک:$1|ورتن والا|والے}}]',
'rc_categories'                     => 'گٹھاں دی حد (وکھرے کرو "|")',
'rc_categories_any'                 => 'کوئی',
'newsectionsummary'                 => '/* $1 */ نواں پاسہ',
'rc-enhanced-expand'                => 'لمبی کہانی وکھاؤ (جاوا سکرپٹ چائیدا اے)',
'rc-enhanced-hide'                  => 'لمبی کہانی لکاؤ',

# Recent changes linked
'recentchangeslinked'          => 'ملدیاں جلدیاں تبدیلیاں',
'recentchangeslinked-feed'     => 'ملدیاں جلدیاں تبدیلیاں',
'recentchangeslinked-toolbox'  => 'ملدیاں جلدیاں تبدیلیاں',
'recentchangeslinked-title'    => '"$1" نال تعلق آلیاں تبدیلیاں',
'recentchangeslinked-noresult' => 'جڑیاں صفحیاں چ دتے ہوۓ ویلے چ کوئی تبدیلیاں نہیں۔',
'recentchangeslinked-summary'  => "اے اوناں تبدیلیاں دی لسٹ اے جیڑیاں تھوڑا چر پہلاں بنائیاں گئیاں اوناں صفحیاں تے جیڑے خاص صفحے تے جڑدے نے یا کسی خاص کیٹاگری دے ممبراں نوں۔<br />
تواڈی [[Special:Watchlist|اکھ تھلے صفحے]] '''موٹے''' نیں۔",
'recentchangeslinked-page'     => 'صفحے دا ناں:',
'recentchangeslinked-to'       => 'کھلے ہوۓ صفحے دی بجاۓ ایدے نال جڑے صفحے دیاں نویاں تبدیلیاں وکھاؤ',

# Upload
'upload'                      => 'فائل چڑھاؤ',
'uploadbtn'                   => 'فائل چڑھاؤ',
'reuploaddesc'                => 'فائل چڑانا چھڑو تے فائل چڑانے آلے فارم تے واپس ٹرو',
'upload-tryagain'             => 'فائل دی بدلی لکھت دسو',
'uploadnologin'               => 'لاگ ان نئیں ہوۓ',
'uploadnologintext'           => 'توانوں لازمی [[خاص:ورتنلاگان|لاگان]] ایس فائل نوں چڑھان لئی.',
'upload_directory_missing'    => 'چڑھان ڈائریکٹری ($1) نئیں لب رئی تے ویبسرور کولوں نئیں بن سکدی۔',
'upload_directory_read_only'  => 'چرھان ڈاغریکٹری ($1) ویبسرور ہتھوں نئیں لکھی جاسکدی۔',
'uploaderror'                 => 'فائل چڑاندیاں مسئلا ہویا اے',
'upload-recreate-warning'     => "'''حبردار: اک فائل اوس ناں دی مٹادتی گئی یا اودی تھاں بدل دتی گئی اے۔.'''

مٹان تے تھاں بدلن دی لاگ ایتھے دسن لئی دتی گئی اے۔:",
'uploadtext'                  => "فائلااں چڑھان لئی تھلے فارم ورتو۔
ویکھن لئی یا پہلے توں چڑھائیاں فائلاں کھوجن لئی [[خاص:فائللسٹ|چڑھاغیاں فائلاں دی لسٹ]] ، دوبارہ چڑھاؤ یا لاگ ان  [[خاص:لاگ/چڑھاؤ|چڑھان لاگ]] ، مٹان [[خاص:لاگ/مٹاؤ|مٹاؤ لاگ]]۔

اک فائل نوں اک صفے تف لیان لئی تھلے دتے گۓ فارم ورتو:

*'''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:File.jpg]]</nowiki></tt>''' فائل دا پورا ورین ورتن لئی
* '''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:File.png|200px|thumb|left|alt text]]</nowiki></tt>''' 200 پکسل چوڑا ورتنا
*'''<tt><nowiki>[[</nowiki>{{ns:media}}<nowiki>:File.ogg]]</nowiki></tt>''' سدا سدا فائل جوڑنا بنا فائل دسے",
'upload-permitted'            => 'جناں فائلاں دی اجازت اے: $1۔',
'upload-preferred'            => 'جیہڑیاں فائلاں دوجیاں نالوں ودیا: $1۔',
'upload-prohibited'           => 'روکیاں گیاں فائلاں: $1',
'uploadlog'                   => 'اپلوڈ لاگ',
'uploadlogpage'               => 'اپلوڈ لاگ',
'uploadlogpagetext'           => 'تھلے سب توں نویاں چڑھائیاں گیاں فائلاں دی لسٹ اے۔
[[خاص:خبر فائل|نویاں فاغلاں دی گیلری]] ویکھو ۔',
'filename'                    => 'فائل دا ناں',
'filedesc'                    => 'خلاصہ',
'fileuploadsummary'           => 'خلاصہ:',
'filereuploadsummary'         => 'فائل بدلدی اے:',
'filestatus'                  => 'کاپی رائٹ سٹیٹس',
'filesource'                  => 'ذریعہ:',
'uploadedfiles'               => 'اتے چڑھائیاں گئیاں فائلاں',
'ignorewarning'               => 'پاویں روک ہووے فیر وی فائل بچاؤ',
'ignorewarnings'              => 'کسے وی خبرداری ول تعیان ناں دیو۔',
'minlength1'                  => 'فائل ناں کعٹ توں کعٹ اک اکرے تے ہونا چائیدا اے۔',
'illegalfilename'             => 'فائل ناں "$1" وچ کیریکٹر نیں جیہڑے صفے دے سرناویں لئی نئیں ورتے جاسکدے۔
مہربانی کرکے فائل دا ہور ناں رکھو تے اینون فیر چڑھاؤ۔',
'filename-toolong'            => 'فائل ناں 240 بائٹ توں لمبا نئیں ہونا چائیدا۔',
'badfilename'                 => 'فائل ناں "$1" رکھ دتا گیا اے۔',
'filetype-mime-mismatch'      => 'فائل ایکسٹنشن ".$1" لبی ہوئی مائم ٹائپ فائل ($2) نال میچ نئیں کر رئی۔',
'filetype-badmime'            => 'مائم ٹائپ فائلز "$1" نوں خڑھان دی اجازت نئیں۔',
'filetype-bad-ie-mime'        => 'ایس فائل نوں نئیں چڑھایا جاسکدا کیوں جے انٹرنیٹ ایکسپلورر نیں اینوں "$1" لئی کھوجیا اے،
جیدی اجازت نئیں تے ایہ وچوں اک خراب فائل اے۔',
'filetype-unwanted-type'      => "'''\".\$1\"''' اک نئیں چائیدی ٹائپ فائل اے۔
{{انیک:\$3|فائل ونڈ|فائل ونڈاں}} ودیا \$2۔",
'filetype-banned-type'        => '\'\'\'".$1"\'\'\' {{انیک:$4|اینج دی فائل دی اجازت نئیں|اینج دیاں فائلاں دی اجازت نئیں}}
اجازت دتی {{انیک:$3|فائل ٹائپ اے|فائل ٹائپ نیں}} 2$۔',
'filetype-missing'            => 'ایس فائل دی کوئی ایکسٹنشن نئیں (جیویں ".jpg")۔',
'empty-file'                  => 'جیڑی فائل تسی دسی اے اوہ حالی اے۔',
'file-too-large'              => 'جیڑی فائل تسی دسی اے اوہ بوت وڈی اے۔',
'filename-tooshort'           => 'اس فائل دا ناں بوت چھوٹا اے۔',
'filetype-banned'             => 'اس قسم دی فائل تے پابندی اے۔',
'verification-error'          => 'ایس فائل نے فائل ویریفیکیشن پاس نئیں کیتی۔',
'hookaborted'                 => 'جیڑی تبدیلی تسی کرنا چاہی اے، اونوں اک ایکسٹنشن کنڈے نیں بند کردتا اے۔',
'illegal-filename'            => 'اس فائل دے ناں تے پابندی اے۔',
'overwrite'                   => 'اک ہونی فائل تے ہور لکھن دی اجازت نئیں۔',
'unknown-error'               => 'اک انجان غلطی ہوگئی اے۔',
'tmp-create-error'            => 'کچی فاؤل ناں بنائی جاسکی۔',
'tmp-write-error'             => 'کچی فائل لکھدیاں غلطی۔',
'large-file'                  => 'اے گل ہوچکی اے جے فائلاں $1 توں وڈیاں ناں ہون؛ ایہ فائل $2 اے۔',
'largefileserver'             => 'ایڈی وڈی فائل دی اجازت سرور نوں نئیں۔',
'emptyfile'                   => 'جیہڑی فائل تساں چڑھائی اے خالی لکدی اے۔
اے ہوسکدا اے فائل ناں چ کسے ٹائپو توں ہووے۔
مہربانی کرکے چیک کرو تسیں اصل چ ایس فائل نون چڑھاناں جاندے او؟',
'windows-nonascii-filename'   => 'اے وکی فائل ناں جناں چ کوئی خاص کیریکٹر ہووے سپورٹ نئیں کردا۔',
'fileexists'                  => "اک فائل ایس ناں نال پہلے ای ہے مہربانی کرکے '''<tt>[[:$1]]</tt>'''  ویکھو
اگر تھانوں یقین نئیں اگ تسیں اینون بدلنا چاندے اوہ۔
[[$1|thumb]]",
'filepageexists'              => "ایس فائل دا دسن والا صفہ پہلے ای '''<tt>[[:$1]]</tt>''' تے بنایا جاچکیا اے، پر این ناں دی کوئی فائل ایس ویلے نئیں ہیگی۔
سمری جیہڑی تسیں لکھو گے اوہ دسن والے صفے تے نئیں دسے گی۔
اپنی سموری اوتھے دیکھن لئی توانوں اپنے ہتھیں اینون تبدیل کرنا پوے گا۔
[[$1|thumb]]",
'fileexists-extension'        => "ایس ناں دی شائل ہیگی اے: [[$2|thumb]]
* چڑھائی گئی فائل دا ناں: '''<tt>[[:$1]]</tt>'''
* ہیگی فائل دا ناں: '''<tt>[[:$2]]</tt>'''
مہربانی کرکے وکھرا ناں چنو.",
'fileexists-thumbnail-yes'    => "ایہ اک ناپ دی مورت دی فائل دسدی اے ''(تھمبنیل)''.
[[$1|thumb]]
مہربانی کرکے فائل ویکھو '''<tt>[[:$1]]</tt>'''.
اگر ویکھی فائل اوسے مورت دے اصل ناپ دی اے تے فیر ایہ ضروری نئیں جے اک فالتو تھمبنیل چڑھائی جاۓ۔.",
'file-thumbnail-no'           => "فائل ناں '''<tt>$1</tt>''' توں شروع ہوندا اے۔
اے اک نکے ناپ دی مورت لگدی اے ''(تھمبنیل)''۔
اگر تواڈے کول ایہ مورت پورے وڈے ناپ چ اے تسیں اینوں چڑھا سکدے او، نئیں تے فیر ایس فائل دا ناں بدلو۔",
'fileexists-forbidden'        => 'ایس ناں دی فائل پہلے ای ہیگی اے تے اودے اتے نئیں لکھیا جاسکدا۔
اگر تسیں ہلے وی اپنی فائل چڑھاناں چاندے اوہ مہربانی کرکے نویں ناں نال چڑھاؤ۔
[[فائل:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => 'ایس ناں دی فائل پہلے ای رلیاں فاغلاں دی کٹھ چ ہیگی اے۔
اگر تسیں ہلے وی اپنی فائل چڑھانا چاندے او تے فیر نویں ناں نال چڑھاؤ۔
[[فائل:$1|thumb|center|$1]]',
'file-exists-duplicate'       => 'ایہ فائل ایناں {{PLURAL:$1|فائل|فائلاں}} دی کاپی اے۔',
'file-deleted-duplicate'      => 'ایس فائل ([[:$1]]) نال اک رلڈی فائل پہلے مٹائی جاچکی اے۔
توانوں اینون جڑھان توں پہلے اوس فائل دا رکارڈ ویکھ لینا چائیدا اے۔',
'uploadwarning'               => 'فائل چڑانے توں خبردار',
'uploadwarning-text'          => 'تھلے فائل بارے دس بدلو تے فیر کوشش کرو۔',
'savefile'                    => 'فائل بچاؤ',
'uploadedimage'               => 'چڑھائی گئی"[[$1]]"',
'overwroteimage'              => '"[[$1]]" دا اک نواں ورین چڑھاؤ',
'uploaddisabled'              => 'فائل چڑانا بند اے',
'copyuploaddisabled'          => 'یو آر این لے چڑھانا نکارہ کیتا۔',
'uploadfromurl-queued'        => 'تواڈی چڑھدی فائل نوں لین چ لا دتا گیا اے۔',
'uploaddisabledtext'          => 'فائل چڑانے چ رکاوٹ اے۔',
'php-uploaddisabledtext'      => 'پی ایچ پی چ فائل چڑھانا نکارہ کیتا ہویا جے۔
مہربانی کرکے فائل چڑھان دی سیٹنک ویکھو۔',
'uploadscripted'              => 'ایس فائل چ  ایچ ٹی ایم ایل یا سکرپٹ کوڈ ہیگا اے جینوں کسے ویب براؤزر نے غلط سمجیا ہووے۔',
'uploadvirus'                 => 'اس فائل چ وائرس اے! تفصیل: $1',
'uploadjava'                  => 'ایہ فائل اک زپ فائل اے جیدے چ جاوا کلاس فائل اے۔
جاوا فائلاں نوں چڑھان دی اجازت نئیں کیوں جے او بچاؤ ولاں توں بچ کے لنکدے نیں۔',
'upload-source'               => 'سورس فائل',
'sourcefilename'              => 'فائل دے ذریعے دا ناں:',
'sourceurl'                   => 'سورس یو آر ایل',
'destfilename'                => 'وکی دے اتے فائل دا ناں:',
'upload-maxfilesize'          => 'فائل دا زيادہ توں زيادہ ناپ: $1',
'upload-description'          => 'شائل دس',
'upload-options'              => 'چڑھان چنوتیاں',
'watchthisupload'             => 'اس فائل تے نظر رکھو',
'filewasdeleted'              => 'ایس ناں دی فائل پہلے چڑھائی گئی تے فیر مٹا دتی گئی۔
توانوں $1 نوں ویکھنا چائیدا اے اینوں چڑھان توں پہلے۔',
'filename-bad-prefix'         => "فائل ناں جینوں تسیں چڑھا رۓ او '''\"\$1\"''' توں ٹردا اے، جیہڑک دسدا تے پر ڈجیٹل کیمریاں چ اپنے آپ  آجاندا اے۔
مہربانی کرکے کوئی ہور سدا ناں چنو۔",
'upload-success-subj'         => 'فائل چڑھ گئی اے',
'upload-success-msg'          => 'تواڈا [$2] توں فائل چڑھانا ٹھیک ریا۔ اے ایتھے [[:{{ns:file}}:$1]] ہیگی اے۔',
'upload-failure-subj'         => 'چڑھان رپھڑ',
'upload-failure-msg'          => 'تھاڈی چڑھائی ہوئی [$2] فائل نال رپھڑ: $1',
'upload-warning-subj'         => 'فائل چڑانے توں خبردار',
'upload-warning-msg'          => 'تھواڈی [$2] توں چڑھائی گئی فائل چ رپھڑ اے۔ تسیں [[خاص:چڑھاؤ/پرے کرو/$1|چڑھان فارم]] ول جاسکدے رپھڑ مکان لئی۔',

'upload-proto-error'        => 'غلط پروٹوکول',
'upload-proto-error-text'   => 'دوروں چڑھائی لئی فائل لئی ضروری اے جے اودی یوآرایل <code>http://</code> یا <code>ftp://</code> توں ٹرے۔',
'upload-file-error'         => 'اندر دا مسئلا',
'upload-file-error-text'    => 'اک انٹرنل غلطی ہوگئی  سرور تے اک گچی فائل بناندیاں ہویاں۔ مہربانی کرکے کسے مکھۓ نال گل کرو۔',
'upload-misc-error'         => 'اتے چڑاندیاں انجان مسئلا اے',
'upload-misc-error-text'    => 'فائل جڑھاندیاں ہویاں اک انجانی غلطی ہوگئی اے
مہربانی کرکے ویکھو جے یو آر ایل ٹھیک اے  تے اودے تک اپڑیا جاسکدا اے تے دوبارہ کوشش کرو۔
اگر غلطی ریندی اے، کسے مکھۓ نوں ملو۔',
'upload-too-many-redirects' => 'یو آر ایل چ  وچ چوکھے سارے ریڈائرکس نیں۔',
'upload-unknown-size'       => 'انجان تاریخ',
'upload-http-error'         => 'اک ایچ ٹی ٹی پی غلطی ہوئی:$1',

# ZipDirectoryReader
'zip-file-open-error' => 'اک غلطی لبی زپ چیک لئی فائل کھولدیاں',
'zip-wrong-format'    => 'دسی گئی فائل زپ فائل نئین سی۔',
'zip-bad'             => 'قائل خراب اے یا فیر ناں پڑھی جان والی زپ فائل اے۔
اے ٹھیک ول ناں سیکیورٹی لئی نئیں ویکھی جاےکدی۔',
'zip-unsupported'     => 'یہ فائل اک زپ فائل اے جیدے چ ‌زپ فیچر نیں میڈیاوکی ولوں سہارا ناں دتے گۓ۔
سیکیورٹی لئی اینوں ٹھیک چیک نئیں کیتا جاسکدا۔',

# Special:UploadStash
'uploadstash'          => 'چڑھائیاں لکاؤ',
'uploadstash-summary'  => 'اے صفہ چڑھائیآں فائلاۂ تک لے جاندا اے (یا چڑھان دے کم چ لگیا اے) پر ہجے وکی تے نئیں چھپیا۔
اے فائلاں کسے نوں نئیں دسدیاں پر اوس ورتن والے نوں جینے چڑھائیاں نیں۔',
'uploadstash-clear'    => 'لکائیاں فائلاں صاف کرو۔',
'uploadstash-nofiles'  => 'تواڈے کول کوئی لکائیاں فائلاں نئیں۔',
'uploadstash-badtoken' => 'اے کم نئیں ہوسکیا، خورے تواڈے تبدیلی دے حق مک گۓ نیں۔ فیر کوشش کرو۔',
'uploadstash-errclear' => 'فائلاں نئیں مکایاں جاسکیاں۔',
'uploadstash-refresh'  => 'فائلاں دی لسٹ تازی کرو۔',

# img_auth script messages
'img-auth-accessdenied'     => 'اپڑنن روک',
'img-auth-nopathinfo'       => 'گمی راہ بارے دس۔
تواڈا سرور ایس جانکاری نوں نئیں گزار سکدا۔
اے ۓۂـ- بیسد ہوسکدی اے یا تے img_auth نوں سپورٹ کردی اے۔
ویکھو http://www.mediawiki.org/wiki/Manual:Image_Authorization',
'img-auth-notindir'         => 'پچھیا گیا راہ بنائی گئی ڈائریکٹری چ نئیں اے۔',
'img-auth-badtitle'         => '"$1" توں اک پکا سرناواں بنان چ ہار',
'img-auth-nologinnWL'       => 'تسیں لاگان نئیں ہووے "$1"  چٹی لسٹ چ نئیں۔',
'img-auth-nofile'           => 'فائل "$1"  کوئی نئیں ہیگی۔',
'img-auth-isdir'            => 'تسیں اک ڈائریکٹری "$1"  نے جان دی کوشش کر رۓ اوہ۔
صرف فائل تک جان دی اجازت اے۔',
'img-auth-streaming'        => 'سٹریمنگ "$1"۔',
'img-auth-public'           => 'img_auth.php دا کم کسے پرائیویٹ وکی چوں فائلاں آؤٹ پٹ کرنا اے۔
ایہ وکی اک پبلک وکی اے۔
بہت زیادہ بچاؤ لئی img_auth.php نوں نکارہ کیتا گیا اے۔',
'img-auth-noread'           => 'ورتن والے دی "$1" نوں پڑھن تک پونچ نئیں۔',
'img-auth-bad-query-string' => 'ایس یوآرایل چ اک ناں منی جان والی کویری سٹرنگ اے۔',

# HTTP errors
'http-invalid-url'      => 'ناں منی جان والی یو آر ایل: $1',
'http-invalid-scheme'   => '"$1" سکیم والیاں یو آر ایل نوں سپورٹ نئیں کیتا جاندا۔',
'http-request-error'    => 'ایچ ٹی ٹی پی  دی مانگ کسے انجان غلطی باجوں ناں منی گئی۔',
'http-read-error'       => 'ایچ ٹی ٹی پی  غلطی پڑھدی اے۔',
'http-timed-out'        => 'ایچ ٹی ٹی پی  دی مانگ ویلیوں بار۔',
'http-curl-error'       => 'ایچ ٹی ٹی پی  : $1 لیان چ غلطی۔',
'http-host-unreachable' => 'ایچ ٹی ٹی پی  تک ناں پونچ سکیا۔',
'http-bad-status'       => 'ایچ ٹی ٹی پی : $1 $2 دی مانگ ویلے رپھڑ',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6'       => 'یو آر ایل تک ناں پونچ سکیا۔',
'upload-curl-error6-text'  => 'دتی گئی  یو ار ایل  تک ناں اپڑیا جاسکیا۔
مہربانی کرکے دو واری ویکھو جے یو ار ایل  ٹھیک اے تے سائیٹ ٹھیک اے۔',
'upload-curl-error28'      => 'فائل اتے چڑانے دا ویلا مک گیا اے',
'upload-curl-error28-text' => 'سائیٹ نے چوکھا ویلہ لیا جواب دین ج۔
دیکھو سائیٹ ٹھیک اے، تھوڑا چر صبر کرو تے فیر کوشش کرو۔
توانوں اک تھوڑے مصروف ویلے کوشش کرنی چاغیدی اے۔',

'license'            => 'لاغسنسنگ',
'license-header'     => 'لاغسنسنگ',
'nolicense'          => 'انچنی',
'license-nopreview'  => '(کچا کم نئیں ویکھ سکدے او)',
'upload_source_url'  => '(اک پکی لوکاں دی رسائی والی یو ار ایل)',
'upload_source_file' => ' (تواڈے کمپیوٹر تے اک فائل)',

# Special:ListFiles
'listfiles-summary'     => 'ایس خاص صفے تے ساریاں چڑھائیاں فائلاں  دسیاں نیں۔
جدوں ورتن والا اینوں فلٹر کرے، صرف اوہ فائلاں جتھے ورتن والے نیں نویں ورین دیاں فائلاں چڑھاغیاں ہون دسیاں جاندیاں نیں۔',
'listfiles_search_for'  => 'میڈیا نان نوں کھوجو:',
'imgfile'               => 'فائل',
'listfiles'             => 'فائل لسٹ',
'listfiles_thumb'       => 'نکی مورت',
'listfiles_date'        => 'تاریخ',
'listfiles_name'        => 'ناں',
'listfiles_user'        => 'ورتن آلا',
'listfiles_size'        => 'ناپ',
'listfiles_description' => 'تفصیل',
'listfiles_count'       => 'ورژن',

# File description page
'file-anchor-link'          => 'فائل',
'filehist'                  => 'پچھلی حالت',
'filehist-help'             => 'فائل نو اس ویلے دی حالت وچ ویکھن واسطے تاریخ/ویلے تے کلک کرو۔',
'filehist-deleteall'        => 'سب نوں مٹاؤ',
'filehist-deleteone'        => 'مٹاؤ',
'filehist-revert'           => 'واپس',
'filehist-current'          => 'موجودہ',
'filehist-datetime'         => 'تاریخ/ویلہ',
'filehist-thumb'            => 'نکی مورت',
'filehist-thumbtext'        => '$1 ورثن دی نکی مورت',
'filehist-nothumb'          => 'کوئی تھمبنیل نئیں۔',
'filehist-user'             => 'ورتن والا',
'filehist-dimensions'       => 'پاسے',
'filehist-filesize'         => 'فائل دا ناپ',
'filehist-comment'          => 'راۓ',
'filehist-missing'          => 'فائل گواچی ہوئی اے',
'imagelinks'                => 'فائل ورتن',
'linkstoimage'              => 'تھلے دتے گۓ {{PLURAL:$1|$1 صفحے}} اس فائل نال جڑدے نے',
'linkstoimage-more'         => '$1 توں چوکھے {{انیک:$1|صفہ جوڑ|صفہ جوڑ}} ایس فائل نوں۔
تھلے دتی گئی لسٹ {{انیک:$1|پہلا صفہ جوڑ|پہلا $1 صفہ جوڑ}} ایس فائل نال دسدی اے۔
اک خاص [[خاص:ایتھے کی جوڑ نیں/$2|پوری لسٹ]] ہیگی اے۔',
'nolinkstoimage'            => 'اس فائل نال جڑیا کوئی صفحہ نہیں۔',
'morelinkstoimage'          => 'ایس فائل نوں [[خاص:کی جوڑ ایتھے/$1|ہور جوڑ]] ویکھو',
'linkstoimage-redirect'     => '$1 (فائل ریڈائیدکٹ) $2',
'duplicatesoffile'          => 'تھلے دتیاں گیاں {{انیک:$1فائل دوہری اے|$1 فائلاں دوہریاں نیں} ایس فائل دیاں ([[خاص:کاپی فائل کھوج/$2|ہور گلاں]]) کاپی نیں۔',
'sharedupload'              => 'اے فائل $1 مشترکہ اپلوڈ اے تے اے دوجے منصوبے وی استعمال کر سکدے نے۔',
'sharedupload-desc-there'   => 'ایہ فائل $1 توں اے تے اینوں دوجے ویونت وی ورت سکدے نیں۔
مہربانی کرکے [$2 فائل دس صفہ] ویکھو ہور دساں لئی۔',
'sharedupload-desc-here'    => 'ایہ فائل $1 توں اے تے دوجیاں ویونتاں تے وی ورتی جاےکدی اے۔
گل بات اس دے [$2 فائل گل بات صفہ]  تے تھلے دتی گئی۔',
'filepage-nofile'           => 'ایس ناں دی کوئی فائل نئیں اے۔',
'filepage-nofile-link'      => 'ایس ناں دی کوئی فائل نئیں اے پر تسیں $1 چرھا سکدے او۔',
'uploadnewversion-linktext' => 'اس فائل دا نوا ورژن چھڑھاؤ',
'shared-repo-from'          => '$1 توں',
'shared-repo'               => 'اک سانجی ریپوزیٹری',

# File reversion
'filerevert'                => '$1 واپس',
'filerevert-legend'         => 'فائل پچھلی حالت چ لے جاؤ',
'filerevert-intro'          => "تسیں فائل '''[[میڈیا:$1|$1]]''' نوں واپس کرن والے او [$4 ورین $3, $2] ول۔",
'filerevert-comment'        => 'وجہ:',
'filerevert-defaultcomment' => 'ورین $1، $2 ول واپس',
'filerevert-submit'         => 'واپس',
'filerevert-success'        => "'''[[میڈیا:$1|$1]]''' واپس کردتا گیا [$4 ورین$3, $2] ول۔",
'filerevert-badversion'     => 'ایس فائل دا پہلا کوئی ورین نئیں ویلے ناں۔',

# File deletion
'filedelete'                  => '$1 مٹاؤ',
'filedelete-legend'           => 'فائل مٹاؤ',
'filedelete-intro'            => "تسی '''[[میڈیا:$1|$1]]''' مٹا رۓ او۔",
'filedelete-intro-old'        => "تسیں '''[[Media:$1|$1]]''' دا ورین مٹا رۓ او [$4 $3, $2] توں۔",
'filedelete-comment'          => 'وجہ:',
'filedelete-submit'           => 'مٹاؤ',
'filedelete-success'          => "'''$1''' مٹایا جا چکیا اے۔",
'filedelete-success-old'      => "'''[[میڈیا:$1|$1]]''' دا ورین ، $3، $2 مٹادتا گیا اے۔",
'filedelete-nofile'           => "'''$1''' نئیں اے۔",
'filedelete-nofile-old'       => "'''$1''' دا کوئی آرکائیوڈ ورین نئیں خاص اٹریبیوٹس نال۔",
'filedelete-otherreason'      => ':دوجی وجہ',
'filedelete-reason-otherlist' => 'ہور وجہ',
'filedelete-reason-dropdown'  => '*مٹان دیاں عام وجہاں
** کاپی حق نوں چھیڑنا
**دوہری فائل',
'filedelete-edit-reasonlist'  => 'مٹانے دی وجہ لکھو',
'filedelete-maintenance'      => 'فائلاں دا مٹانا تے واپس کرنا مرمرت باجوں کج چر لئی روک دتا گیا اے۔',

# MIME search
'mimesearch'         => 'MIME کھوج',
'mimesearch-summary' => 'ایہ صفہ فاغلاں نوں اوناں دی مائم ٹائپ  لئی نتارا قابل کردا اے.
انپٹ: contenttype/subtype, e.g. <tt>مورت/jpeg</tt>.',
'mimetype'           => 'مائم ٹائپ',
'download'           => 'فائل کاپی کرو',

# Unwatched pages
'unwatchedpages' => 'اندیکھے صفحے',

# List redirects
'listredirects' => 'لسٹ ریڈائریکٹس',

# Unused templates
'unusedtemplates'     => 'نا استعمال ہوۓ سچے',
'unusedtemplatestext' => 'ایس صفے چ  سارے صفیاں دی لسٹ اے {{ns:ٹمپلیٹ}} تے جیہڑے کسے ہور صفے نال نئیں رلے۔  ٹمپلیٹ تے  ہور جوڑ ویکھ لو مٹان توں پہلے۔',
'unusedtemplateswlh'  => 'دوجے جوڑ',

# Random page
'randompage'         => 'ملے جلے صفحے',
'randompage-nopages' => 'ایتھے کوئی صفے نئیں تھلے دتے گۓ {{انیک:$2|ناںتھاں|ناںتھانواں}}: $1',

# Random redirect
'randomredirect'         => 'بے پترتیب ریڈائریکٹ',
'randomredirect-nopages' => '"$1" ناں نال کوئی ریڈائرکٹ نئیں۔',

# Statistics
'statistics'                   => 'حساب کتاب',
'statistics-header-pages'      => 'صفحے دا حساب کتاب',
'statistics-header-edits'      => 'تبدیلیاں دا حساب کتاب',
'statistics-header-views'      => 'ویکھن دا حساب کتاب',
'statistics-header-users'      => 'ورتن آلیاں دا حساب کتاب',
'statistics-header-hooks'      => 'دوجے حساب کتاب',
'statistics-articles'          => 'لسٹ صفے',
'statistics-pages'             => 'صفحے',
'statistics-pages-desc'        => 'اس وکی دے سارے صفحے، گل بات، اگے ٹور آلے تے دوجے صفحے ملا کے۔',
'statistics-files'             => 'اتے چڑھائیاں گئیاں فائلاں',
'statistics-edits'             => 'صفہ تبدیلیاں {{سائٹناں}} تون بنائی گئی۔',
'statistics-edits-average'     => 'اوسط تبدیلی اک صفے تے۔',
'statistics-views-total'       => 'کل وکھالے۔',
'statistics-views-total-desc'  => 'ناں ہون والے صفیاں تے خاص صفیاں دے وکھالے نئیں ہیگے۔',
'statistics-views-peredit'     => 'تبدیلی سعاب نال وکھالے',
'statistics-users'             => 'رجسٹر [[خاص:ورتنلسٹ|ورتنوالا]]',
'statistics-users-active'      => 'کامے ورتاوے',
'statistics-users-active-desc' => 'ورتنوالے جناں نیں پچھلے {{انیک:$1|دن|$1 دناں}}  چ۔',
'statistics-mostpopular'       => 'سب توں بوتے ویکھے گۓ صفجے',

'disambiguations'      => 'اک جۓ صفحے',
'disambiguationspage'  => 'ٹمپلیٹ: ڈسایمبگ',
'disambiguations-text' => "تھلے دتے گۓ صفیاں دا اک '''گنجل کھول''' نال جوڑ اے۔
ایدے بجاۓ ایدا جوڑ کسے ٹھیک سرناویں نال جوڑو<br />
اک صفہ گنجل کھول صفے لئی منیا جائیگا  اگر اے اک ٹمپلیٹ ورتدا جیدا جوڑ [[MediaWiki:Disambiguationspage]] نال ہووے۔",

'doubleredirects'                   => 'دوہری ریڈیرکٹس',
'doubleredirectstext'               => 'ایس صفے تے اوناں صفیاں دی لسٹ اے جیہڑے ریڈائرکٹ کردے نیں دوجے ریڈائرکٹ صفیاں ول۔
ہر قطار چ جوڑ نیں  پہلے تے دوجے ریڈائرکٹ نال ، نال دوجے دیڑائرکٹ ول دا تارگٹ نیں جیہڑا کے ٹھیک تارگٹ صفہ ہوندا اے جیہڑا کہ پہلے ریڈائرکٹ نوں اشارہ کرنا چائیدا اے۔
<del>کراسڈ</del> اینٹریاں حل ہوگیاں نیں۔',
'double-redirect-fixed-move'        => '[[$1]] نوں بدل دتا گیا اے۔
اے ہن [[$2]] نوں ریڈائرکٹ اے۔',
'double-redirect-fixed-maintenance' => '[[$1]] توں [[$2]] تک دوہرے ریڈائرکٹ ٹھیک کرنا۔',
'double-redirect-fixer'             => 'ریڈائرکٹ فکسر',

'brokenredirects'        => 'ٹٹے ہوۓ ریڈائریکٹس',
'brokenredirectstext'    => 'تھلے دتے گۓ ریڈائریکس ناں ہون والے صفیاں نوں جڑدے نین:',
'brokenredirects-edit'   => 'لکھو',
'brokenredirects-delete' => 'مٹاؤ',

'withoutinterwiki'         => 'او صفحہ جناں دا دوجی بولیاں نال جوڑ نہیں',
'withoutinterwiki-summary' => 'تھلے دتے کۓ صفے دوجیاں بولیاں ورین نال نئیں جڑدے۔',
'withoutinterwiki-legend'  => 'پریفکس',
'withoutinterwiki-submit'  => 'وکھاو',

'fewestrevisions' => 'سب توں کٹ تبدیلیاں والے صفحے',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|بائیٹ|بائیٹ}}',
'ncategories'             => '$1 {{انیک:$1|گٹھ|گٹھاں}}',
'nlinks'                  => '$1 {{PLURAL:$1|link|جوڑ}}',
'nmembers'                => '$1 {{PLURAL:$1|member|ممبران}}',
'nrevisions'              => '$1 {{انیک:$1|ریوین}}',
'nviews'                  => '$1 {{انیک:$1|جوڑ|انیک}}',
'nimagelinks'             => 'تے ورتیا $1 {{انیک:$1|صفہ|صفے}}',
'ntransclusions'          => '$1 تے ورتے  {{انیک:$1|صفہ|صفے}}',
'specialpage-empty'       => 'ایس رپورٹ دے کوئی نتیجے نئیں۔',
'lonelypages'             => 'لاوارث صفحے',
'lonelypagestext'         => 'تھلے دتے گۓ صفیاں نوں دوجیاں صفیاں نوں {{سائیٹناں}} تے نئیں جوڑیا گیا۔',
'uncategorizedpages'      => 'بغیر کیٹاگریاں والے صفحے',
'uncategorizedcategories' => 'بفیر کیٹاگریاں دے کیٹاگری',
'uncategorizedimages'     => 'بغیر کیٹاگریاں آلیاں فائلاں',
'uncategorizedtemplates'  => 'بغیر کیٹاگریاں آلے سچے',
'unusedcategories'        => 'نا استعمال ہوئیاں کیٹاگریاں',
'unusedimages'            => 'نا استعمال ہوئیاں فائلاں',
'popularpages'            => 'مشہور صفحے',
'wantedcategories'        => 'چاھیدیاں کیٹاگریاں',
'wantedpages'             => 'چائیدے صفحے',
'wantedpages-badtitle'    => 'ناں منیا جان والا سرناواں رزلٹ سیٹ چ:$1',
'wantedfiles'             => 'چائیدے صفحے',
'wantedtemplates'         => 'چائیدے ٹمپلیٹ',
'mostlinked'              => 'سب توں بوتے جوڑاں آۂے صفحے',
'mostlinkedcategories'    => 'سب توں بوتیاں جڑیاں کیٹاگریاں',
'mostlinkedtemplates'     => 'سب توں زیادہ جوڑ والے سچے',
'mostcategories'          => 'سب توں بوتیاں کیٹاگریاں آلے صفحے',
'mostimages'              => 'سب توں زیادہ تعلق آلیاں فائلاں',
'mostrevisions'           => 'سب توں بوتے تبدیلیاں آلے صفحے',
'prefixindex'             => 'سابقہ انڈیکس',
'shortpages'              => 'چھوٹے صفحے',
'longpages'               => 'لمبے صفحے',
'deadendpages'            => 'لاتعلق صفحے',
'deadendpagestext'        => 'تھلے دتے گۓ صفیاں نوں {{سائیٹناں}} تے دوجیاں صفیاں تے نئیں جوڑیا گیا۔',
'protectedpages'          => 'بچاۓ گۓ صفحے',
'protectedpages-indef'    => 'صرف انڈیفینٹ بچاؤ',
'protectedpages-cascade'  => 'کیسکیڈنگ بچاؤ صرف',
'protectedpagestext'      => 'تھلے دتے کے صفے ہٹان یا تبدیلی تون بچاۓ گے نیں۔',
'protectedpagesempty'     => 'ایناں ولگناں نال کوئی صفے نئیں بچاۓ گۓ۔',
'protectedtitles'         => 'بچاۓ ہوۓ صفحے',
'protectedtitlestext'     => 'تھلے دتے گۓ سرناویں بنان توں بچاۓ گۓ نیں۔',
'protectedtitlesempty'    => 'ایناں ولگناں نال کوئی سرناویں نئیں بچاۓ گۓ۔',
'listusers'               => 'ورتن والیاں دے ناں',
'listusers-editsonly'     => 'تبدیلیاں کرن والے ورتن والے ای دسو۔',
'listusers-creationsort'  => 'بنان تریخ توں وکھریاں کرو۔',
'usereditcount'           => '$1 {{انیک:$1|تبدیلی|تبدیلیاں}}',
'usercreated'             => '{{جنس:$3|بنائی گئی}} نوں $1 تے $2',
'newpages'                => 'نوے صفحے',
'newpages-username'       => 'ورتن آلا ناں:',
'ancientpages'            => 'سب توں پرانے صفحے',
'move'                    => 'لے چلو',
'movethispage'            => 'اس صفحے نوں لے چلو',
'unusedimagestext'        => 'تھلے دتیاں فائلاں ہے نیں پر اوہ کسے صفے چ نئیں لگیاں۔
مہربانی کرکے ایہ گل یاد رکھو جے دوجیاں ویب سائیٹاں  اک ڈائریکٹ یو آر ایل نال فائل نال جوڑ کرسکدیاں نیں۔ اینج ایتھے لسٹ چ آسکدیاں نیں ورتن چ ہون تے وی۔',
'unusedcategoriestext'    => 'تھلے دتے گٹھ صفے ہیگے نیں، کوئی ہور صفہ یا گٹھ ایناں نوں ناں ورتے۔',
'notargettitle'           => 'بغیر نشانے توں',
'notargettext'            => 'تساں کوئی تارگٹ صفہ نئیں دسیا،  یا ورتن والا جیہڑا ایہ کم کرے',
'nopagetitle'             => 'اس طرح دا کوئی صفحہ نئیں اے',
'nopagetext'              => 'تارگٹ صفہ جیہڑا تساں دسیا اے ہے ای نئیں۔',
'pager-newer-n'           => '{{PLURAL:$1|newer 1|زیادہ نواں $1}}',
'pager-older-n'           => '{{PLURAL:$1|older 1|زیادہ پرانا $1}}',
'suppress'                => 'چھڈیا گیا',
'querypage-disabled'      => 'اے صفہ ناکارہ کیتا گیا پرفارمنس وجہ توں۔',

# Book sources
'booksources'               => 'حوالہ کتاب',
'booksources-search-legend' => 'اس مضمون تے کتاباں لبو',
'booksources-go'            => 'جاؤ',
'booksources-text'          => 'تھلے اوناں جوڑاں دی لسٹ اے جتھے نویاں تے پرانیاں کتاباں وکدیاں نیں، تے ہور وی جانکاری ہوسکدی اے  کتاباں بارے تسیں ویکھدے او:',
'booksources-invalid-isbn'  => 'دتی گئی آئی ایس بی این چلدی نئیں لکدی ؛ اصل سورس نوں چیک کرو کاپی کرن توں پہلے۔',

# Special:Log
'specialloguserlabel'  => 'ورتن والا:',
'speciallogtitlelabel' => 'ناں:',
'log'                  => 'لاگز',
'all-logs-page'        => 'سارے لاگ پبلک',
'alllogstext'          => '{{سائیٹناں}} دیاں ہیگیاں لوگز دا کٹھا وکھالہ۔
تسیں اک لوگ ٹائپ، ورتن ناں، افیکٹڈ صفے نوں چن کے وکھالہ تنگ کرسکدے اوہ،',
'logempty'             => 'لاگ چ کوئي رلدیاں شیواں نئیں۔',
'log-title-wildcard'   => 'ایناں بولاں نال شروع ہون والے سرنویں لبو۔',

# Special:AllPages
'allpages'          => 'سارے صفحے',
'alphaindexline'    => '$1 توں $2',
'nextpage'          => 'اگلا صفحہ ($1)',
'prevpage'          => 'پچھلا صفحہ ($1)',
'allpagesfrom'      => 'اس جگہ توں شروع ہونے آلے صفحے وکھاؤ:',
'allpagesto'        => 'اس تے ختم ہون آلے صفحے وکھاؤ:',
'allarticles'       => 'سارے صفحے',
'allinnamespace'    => 'سارے صفے ($1 ناں)',
'allnotinnamespace' => 'سارے صفے ($1 ناں چ نئیں)',
'allpagesprev'      => 'پچھلا',
'allpagesnext'      => 'اگلا',
'allpagessubmit'    => 'چلو',
'allpagesprefix'    => 'پریفکس نال صفحے وکھاؤ:',
'allpagesbadtitle'  => 'دتا گیا سرناواں کم نئیں کردا یا ایدے ناں انٹر لینگويج یا انٹر وکی پریفکس لگیا اے۔
ایدے چ اک یا چوکھے کیریکٹر ہوسکدے نیں جیہڑے سرناویاں چ نئیں ورتے جاسکدے۔',
'allpages-bad-ns'   => '{{سائیٹناں}} چ ناں تھاں "$1" نئیں اے۔',

# Special:Categories
'categories'                    => 'کیٹاگریاں',
'categoriespagetext'            => 'تھلے {{انیک:$1|گٹھ چ|گٹھاں چ}} صفے یا میڈیا۔
[[خاص:ناں ورتیاں گٹھاں|ناں ورتیاں گٹھاں]] ایتھے نئیں دسے گۓ۔
ایہ وی ویکھو [[خاص:چائیدیاں گٹھاں|چائیدیاں گٹھاں]]',
'categoriesfrom'                => 'گٹھاں وکھاؤ جیہڑیاں شروع ہون:',
'special-categories-sort-count' => 'گنتی سعاب نال وکھریاں کرو',
'special-categories-sort-abc'   => 'ا ب دے سعاب نال ونڈو',

# Special:DeletedContributions
'deletedcontributions'             => 'ورتن والے دے کم مٹادتے گۓ۔',
'deletedcontributions-title'       => 'ورتن والے دے کم مٹادتے گۓ۔',
'sp-deletedcontributions-contribs' => ' کم',

# Special:LinkSearch
'linksearch'       => 'باہر دے جوڑ دی کھوج',
'linksearch-pat'   => 'کھوج راہ:',
'linksearch-ns'    => 'ناں دی جگہ:',
'linksearch-ok'    => 'کھوج',
'linksearch-text'  => 'وائلڈکارڈز جیویں کہ "*.wikipedia.org" ورتے جاسکدے نیں۔<br />
منے گۓ پروٹوکول:<tt>$1</tt>',
'linksearch-line'  => '$1 نوں $2 نال جوڑیا',
'linksearch-error' => 'وکیکارڈو میزبان دے ناں دے شروع چ دس سکدے نیں۔',

# Special:ListUsers
'listusersfrom'      => 'ورتن والے ایس توں شروع ہون والے دسو:',
'listusers-submit'   => 'وکھاؤ',
'listusers-noresult' => 'ورتن آلا نئیں لبیا۔',
'listusers-blocked'  => '(روکیا گیا)',

# Special:ActiveUsers
'activeusers'            => 'کم کرن والیاں دی لسٹ',
'activeusers-intro'      => 'اے اوناں ورتن والیاں دی لسٹ اے جنان پچھلے $1 دناں چ کم کیتا اے۔',
'activeusers-count'      => '$1 {{انیک:$1|تبدیلی|تبدیلیاں}} پچھلے{{انیک:$3|دن|$3 دن}}',
'activeusers-from'       => 'ورتن والے ایس توں شروع ہون والے دسو:',
'activeusers-hidebots'   => 'بوٹ چھپاؤ',
'activeusers-hidesysops' => 'مکھۓ لکاؤ',
'activeusers-noresult'   => 'کوئی ورتن والا نئیں لبیا۔',

# Special:Log/newusers
'newuserlogpage'     => 'ورتاوا بنان آلی لاگ',
'newuserlogpagetext' => 'اے ورتن والا بنان دی لاگ اے۔',

# Special:ListGroupRights
'listgrouprights'                      => 'ورتن ٹرلی حق',
'listgrouprights-summary'              => 'تھلے اک لسٹ اے ورتن ٹولیاں دی ای وکی تے، اپنے رلدے حقاں نال۔ 

ہربندے دے ح‍ق‍اں [[{{میڈیاوکی:لسٹٹولی حق}}|ہور جانکاری]]',
'listgrouprights-key'                  => '* <span class="listgrouprights-granted">حق دتا گیا</span>
* <span class="listgrouprights-revoked">حق راپس</span>',
'listgrouprights-group'                => 'ٹولی',
'listgrouprights-rights'               => 'حق',
'listgrouprights-helppage'             => '!!!!مدد:ٹولی حق',
'listgrouprights-members'              => '(رکناں دی لسٹ)',
'listgrouprights-addgroup'             => '{رلاؤ{انیک:$2|ٹولی|ٹولیاں}}: $1',
'listgrouprights-removegroup'          => 'جوڑو {{انیک:$2|ٹولی|ٹولیاں}}: $1',
'listgrouprights-addgroup-all'         => 'ساریاں ٹولیاں جورو',
'listgrouprights-removegroup-all'      => 'ساریاں ٹولیاں ہٹاؤ',
'listgrouprights-addgroup-self'        => 'جوڑو {{انیک:$2|ٹولی|ٹولیاں}} اپنے کھاتے چ: $1',
'listgrouprights-removegroup-self'     => 'ہٹاؤ {{انیک:$2|ٹولی|ٹولیاں}} اپنے کھاتے چوں: $1',
'listgrouprights-addgroup-self-all'    => 'ساریاں ٹولیاں کٹھیاں کرو کھاتہ لئی',
'listgrouprights-removegroup-self-all' => 'ایس کھاتے توں ساریاں ٹولیاں ہٹاؤ',

# E-mail user
'mailnologin'          => 'ناں پیح پتہ',
'mailnologintext'      => 'تسیں لازمی لاگان ہوو تے اک پکا ای-میل پتہ تواڈی [[خاص:تانگاں|تانگ]] چ ہووے تاں جے دوجے ورتن والے توانوں ای-میل کرسکن۔',
'emailuser'            => 'اس ورتن والے نو ای میل کرو',
'emailpage'            => 'ای-میل ورتن والا',
'emailpagetext'        => 'تسیں تھلے دتا گیا فارم  ورت سکدے اوہ ایس ورتن والے نوں ای-میل سنیعہ کلن لئی۔ 
ای-میل پتہ تساں [[خاص:تانگاں|تواڈے ورتن تانکآں]] چ پایا اے  ای-میل توں تواڈا پتہ دسے گا جتھے چٹھی چلی تاں جے چٹھی لین والا توانوں سدا جواب دے سکے۔',
'usermailererror'      => 'میل واپسی قلطی:',
'defemailsubject'      => '{{SITENAME}}ای-میل ورتن والے "$1" توں',
'usermaildisabled'     => 'ورتن ای-میل ناکارہ',
'usermaildisabledtext' => 'ایس وکی تے تسیں دوجے ورتن والیاں نوں ای-میل نئیں پیج سکدے۔',
'noemailtitle'         => 'کوئی ای-میل پتہ نئیں۔',
'noemailtext'          => 'ایس ورتن والے نیں کوئی پکا ای-میل پتہ نئیں دسیا۔',
'nowikiemailtitle'     => 'ای-میل اجازت نئیں۔',
'nowikiemailtext'      => 'ایس ورتن والے نیں دوجے ورتن والیاں کولوں ای-میل لین دا نئیں کیا۔',
'emailnotarget'        => 'لین والے ۂئی ناں ہون والا ورتن ناں۔',
'emailtarget'          => 'لین والے دا ورتن ناں لکھو',
'emailusername'        => 'ورتن آلے دا ناں:',
'emailusernamesubmit'  => 'پیجو',
'email-legend'         => 'دوجے ورتن والے نوں {{سائٹ ناں}} ای-میل پیجو',
'emailfrom'            => 'توں:',
'emailto'              => 'نوں:',
'emailsubject'         => 'مضمون:',
'emailmessage'         => 'سنیعا:',
'emailsend'            => 'پیجو',
'emailccme'            => 'میرے سنیعے دی مینوں اک ای-میل کاپی پیجو۔',
'emailccsubject'       => 'تھواڈے سنیعے دی کاپی $1 نوں:$2',
'emailsent'            => 'ای-میل پیج دتی گئی۔',
'emailsenttext'        => 'تھواڈا ای-میل سنیعہ پیج دتا گیا اے۔',
'emailuserfooter'      => 'ایہ ای-میل 1$ نے پیجی 2$  نوں {{سائیٹناں}} تے ای-میل ورتن فنکشن نال',

# User Messenger
'usermessage-summary' => 'پربندھ چھڈن سنیعہ',
'usermessage-editor'  => 'پربندھ ڈاکیا۔',

# Watchlist
'watchlist'            => 'میریاں اکھاں تھلے وچ',
'mywatchlist'          => 'میری نظر وچ',
'watchlistfor2'        => '$1 تے $2 ل‏ی',
'nowatchlist'          => 'تھواڈی اکھ تھلے لسٹ چ کوئی شے نئیں۔',
'watchlistanontext'    => 'مہربانی کرکے $1 نوں ویکھو یا اپنی اکھ تھلے رکھی لسٹ نوں بدلو۔',
'watchnologin'         => 'لاگ ان نئیں ہوۓ او',
'watchnologintext'     => 'توانوں لازمی [[خاص:ورتنلاگان|لاگان]] ہونا پووے گا اپنی اکھ تھلے رکھی لسٹ نوں بدلن لئی۔',
'addwatch'             => 'اکھ تھلے کرو',
'addedwatchtext'       => 'اے صفحہ "[[:$1]] تواڈیاں اکھاں تھلے آگیا اے۔<br />
مستقبل وچ اس صفحہ تے ایدے بارے چ گل بات نویاں تبدیلیاں وچ موٹے نظر آن گے تا کہ آسانی نال کھوجیا جا سکے۔',
'removewatch'          => 'اکھ تھلیوں ہٹاؤ',
'removedwatchtext'     => 'ایہ صفحہ "[[:$1]]" [[خاص:اکھ تھلے رکھی لسٹ|تہاڈی اکھ ]]تھلوں ہٹا لیتا گیا اے۔',
'watch'                => 'نظر رکھو',
'watchthispage'        => 'اس صفحے تے اکھ رکھو',
'unwatch'              => 'نظر ھٹاؤ',
'unwatchthispage'      => 'اکھ رکھنا چھڈو',
'notanarticle'         => 'لکھن صفہ نئیں۔',
'notvisiblerev'        => 'آخری ریوین کسے ہور ورتن والے دی مٹادتی گئی اے۔',
'watchnochange'        => 'دسے گۓ ویلے چ تھواڈیاں ویکھیاںشیواں نئیں بدلیاں گیاں۔',
'watchlist-details'    => '{{PLURAL:$1|$1 صفحہ|$1 صفحہ}} تواڈی اکھ تھلے گلاں باتاں شامل نہیں۔',
'wlheader-enotif'      => 'ای-میل نوٹیفیکیشن قابل',
'wlheader-showupdated' => '*  صفے جیہڑے بدلے کۓ تھواڈے آخری وار آن مکرون  اونان نوں موٹا کرکے دسیا گیا اے۔',
'watchmethod-recent'   => 'نیڑے ہویاں تبدیلیاں چائیدے صفیاں دیاں ویکھے جان والے صفیاں لئی۔',
'watchmethod-list'     => 'ویکھے کے صفے نیڑے ہون والیاں تبدیلیاں دی پڑتال',
'watchlistcontains'    => 'تھواڈی اکھ تھلے رکھی لسٹ چ $1 صفے نیں۔',
'iteminvalidname'      => "'$1' نال رپھڑ، ناں غلط",
'wlnote'               => "تھلے {{انیک:$1|آخری تبدیلی|آخری تبدیلیاں '''$1''' }} آخر تے {{انیک:$2|کینٹہ|'''$2''' کینٹے}}.",
'wlshowlast'           => 'آخری $1 گھنٹے $2 دن $3 وکھاؤ',
'watchlist-options'    => 'نظر تھلے رکھن دیاں راہواں',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'       => 'نظر تھلے۔۔۔۔',
'unwatching'     => 'نظروں اولے',
'watcherrortext' => '"$1" دی سیٹنگ بد دیاں اک غلطی ہوئی جے',

'enotif_mailer'                => '{{سائٹ ناں}} نوٹینیکیشن میلر',
'enotif_reset'                 => 'سارے ویکھے گۓ صفحیاں تے نشان لاؤ',
'enotif_newpagetext'           => 'اے نواں صفحہ اے۔',
'enotif_impersonal_salutation' => '{{ساغٹ ناں}} ورتن والا',
'changed'                      => 'بدلیا',
'created'                      => 'بن گیا',
'enotif_subject'               => '{{سائیٹتھاں}} صفہ $صفہ سرخی  $تبدیلی بنائی  $صفہ لکھاری',
'enotif_lastvisited'           => '$1 تبدیلیاں ویکھو اپنے آخری واری آن مکروں',
'enotif_lastdiff'              => '$1 ویکھو ایس تبدیلی نون ویکھن لئی۔',
'enotif_anon_editor'           => 'گم نام ورتن آلا $1',
'enotif_body'                  => ' $پیارے ورتن والے,


The {{SITENAME}} page $PAGETITLE has been $CHANGEDORCREATED on $PAGEEDITDATE by $PAGEEDITOR, see $PAGETITLE_URL for the current revision.
{{سائیٹناں}}  صفہ $صفہ سرناواں نوں $بدلیابنادتاگیا جے $صفہتبدیلیتریخ نوں $صفہ لکھاری نے، $صفہسرفاواں_یو آر ایل ویکھو ہن دی ریوین لئی۔
$نواںصفہ$

لکھاری سمری: $صفہ سمری $نکیصفہتبدیلی



لکھن والے نوں لکھو:
mail: $صفہلکھاری_ایمیل
وکی: $صفہلکھاری_وکی

ہور گلاں ایس بارے نئیں دسیاں جان گیاں جد تک تسین ایتھے فیر نئیں آندے.
تسیں فیر سیٹ کرسکدے اپنے اپنے سارے ویکھے صفیاں لئی اپنی اکھ تھلے رکھی لسٹ چ۔
			 تواڈا {{سائیٹناں}} بیلی نوٹیفیکیشن پربندھ

--
اپنی ای-میل نوٹیفیکیشن سیٹنگ ویکھن لئی، تھلے دسے گے پتے تے جاؤ
{{canonicalurl:{{#خاص:تانگاں}}}}

اپنی اکھتھلے رکھی لسٹ چ تبدیلی لئی ویکھو
{{canonicalurl:{{#special:EditWatchlist}}}}


اپنی اکھ تھلے رکھی لسٹ چوں صفے نوں مٹان لئی
$UNWATCHURL

فیڈبیک تے ہور مدد لئی:
{{canonicalurl:{{MediaWiki:Helppage}}}}',

# Delete
'deletepage'             => 'صفحہ مٹاؤ',
'confirm'                => 'پکا کرو',
'excontent'              => 'مواد: "$1"',
'excontentauthor'        => 'لکھت سی:"$1" (تے صرف اک لکھاری سی "[[خاص:کم/$2|$2]]")',
'exbeforeblank'          => 'صاف ہون توں پہلے مواد سی: "$1"',
'exblank'                => 'صفحہ خالی سی',
'delete-confirm'         => '"$1" مٹاؤ',
'delete-legend'          => 'مٹاؤ',
'historywarning'         => 'ہوشیار: او صفحہ جس نوں تسی مٹانے لگے او دی $1 {{انیک:$1|ریوین|ریویناں}}: دا ریکارڈ موجود اے۔',
'confirmdeletetext'      => 'تسی اک صفحہ اسدی تاریخ دے نال مٹان لگے او۔
کیا تسی اے ای کرنا چاہندے او کیا تسی اس دے نتیجے نوں جاندے او کہ تسی اے کم [[{{MediaWiki:Policy-url}}|پالیسی]] دے مطابق کر رہے او۔',
'actioncomplete'         => 'کم ہوگیا',
'actionfailed'           => 'کم ناں ہویا',
'deletedtext'            => '"$1" مٹایا جا چکیا اے۔<br />
نیڑے نیڑے مٹاۓ گۓ ریکارڈ نوں دیکن آسطے $2 ایتھے چلو۔',
'dellogpage'             => 'مٹان آلی لاگ',
'dellogpagetext'         => 'تھلے نویاں مٹائے گۓ صفحیاں دی لسٹ اے۔',
'deletionlog'            => 'مٹان آلی لاگ',
'reverted'               => 'پہلی ریوین ول واپس',
'deletecomment'          => 'وجہ:',
'deleteotherreason'      => 'دوجی/ہور وجہ:',
'deletereasonotherlist'  => 'ہور وجہ',
'deletereason-dropdown'  => '*مٹان دیاں عام وجہاں
**لکھن والا کہ ریا اے
**کاپی حق توڑنا
**وینڈالزم',
'delete-edit-reasonlist' => 'مٹانے دیاں وجہ لکھو',
'delete-toobig'          => 'ایس صفے دی اک لمبی تبدیلی دی تریخ اے $1 توں ود {{انیک:$1|ریوین|ریویناں}}
ایے صفیاں دے مٹان تے کج روک اے {{سائیٹناں }} دی اچانک خرابی توں بچن لئی۔',
'delete-warning-toobig'  => 'ایس صفے دی تبدیلی دی اک لمی تریخ اے۔ $1 توں ود {{انیک:$1|ریوین|ریویناں}}۔
اینوں مٹان تے {{SITENAME}} دے ڈیٹا اوپریشنز چ مسلہ بن سکدا اے۔
سوچ سمج کے اگے ودو۔',

# Rollback
'rollback'          => 'لکھائیاں واپس کرو',
'rollback_short'    => 'واپس کرو',
'rollbacklink'      => 'واپس',
'rollbackfailed'    => 'واپس کرن ناکام',
'cantrollback'      => 'تبدیلی واپس نئیں ہوسکدی؛
آخری لکھاری ای ایدا اکو لکھاری سی۔',
'alreadyrolled'     => '[[:$1]] دی آخری تبدیلی جیہڑی [[ورتنوالا:$2|$2]]  نے ([[ورتن گل بات:$2|گل بات]]{{int:pipe-separator}}[[خاص:کم/$2|{{int:contribslink}}]]); کیتی؛ واپس نئیں ہوسکدی
کسے ہور نے تبدیلی یا پچھے نوں پہلے ای کردتا اے۔

صفے تے آخری تبدیلی [[ورتن والا:$3|$3]] ([[ورتن گل بات:$3|گل بات]]{{int:pipe-separator}}[[خاص:کم/$3|{{int:contribslink}}]]) نے کیتی۔',
'editcomment'       => "تبدیلی دی سمری:\"''\$1''\".",
'revertpage'        => 'پلٹائیاں گیاں تبدیلیاں [[خاص:کم/$2|$2]] ([[ورتن گل بات:$2|گل بات]]) [[ورتن والا:$1|$1]] دی آخری ریوین تک',
'revertpage-nouser' => 'بدلیاں گیاں تبدیلیاں (ورتن ناں ہٹادتا گیا) واپس آخری ریوین تک [[ورتن والا:$1|$1]]',
'rollback-success'  => '$1 دیاں بدلیاں گیاں تبدیلیاں؛
$2 نے آخری ریوین تک واپس کیتا۔',

# Edit tokens
'sessionfailure-title' => 'سیشن ناکام',
'sessionfailure'       => 'لگدا اے تواڈے لاگان سیشن چ کوئی رپھڑ اے؛
ایہ کم مکادتا گیا جے سیشن دی ہائیجیکنگ توں بچن لئی۔
پچھلے صفے تے واپس چلو، صفہ دوبارہ چڑھاؤ تے فیر کوشش کرو۔',

# Protect
'protectlogpage'              => 'بچت لاگ',
'protectlogtext'              => 'تھلے صفے نوں بچان لئی تبدیلیاں دی لسٹ اے۔
[[خاص:بچاۓ صفے|بچاۓ صفیاں دی لسٹ]] ویکھو ہن دے اوپریشنل صفیاں دے بچاؤ دی لسٹ ویکھو۔',
'protectedarticle'            => '"[[$1]]" بچایا گیا اے',
'modifiedarticleprotection'   => '"[[$1]]" آستے بچاؤ بدلیا',
'unprotectedarticle'          => '"[[$1]]" توں بچاؤ ہٹا لیا گیا۔',
'movedarticleprotection'      => 'بچاؤ سیٹنگ "[[$2]]" توں "[[$1]]" ول پلٹی۔',
'protect-title'               => '"$1" لئی بچاؤ پدھر تبدیل۔',
'prot_1movedto2'              => '[[$1]] ول بدلی [[$2]]',
'protect-legend'              => 'بچاؤ پکا کرو',
'protectcomment'              => 'وجہ:',
'protectexpiry'               => 'انت ہوندا اے:',
'protect_expiry_invalid'      => 'اکسپائری ٹیم غلط اے۔',
'protect_expiry_old'          => 'ایدا اکسپائری ٹائم گزر چکیا اے۔',
'protect-unchain-permissions' => 'ہور بچت چنوتیاں کھولو',
'protect-text'                => "تسی اس صفحے دے حفاظتی درجے نوں تک تے تبدیل کر سکدے او'''$1'''.",
'protect-locked-blocked'      => "جدوں تھواڈے تے روک ہووے تے  تے تسیں بچاؤ پڈھر نئیں بدل سکدے۔
اے وے ہن دی سیٹنگ '''$1''' صفہ لئی۔",
'protect-locked-dblock'       => "اک ایکٹو ڈیٹا بیس تالے باجوں بچاؤ پدھر نوں بدلیا نئیں جاسکدا۔
صفہ '''$1''' دیاں ہن دیاں سیٹنگز :",
'protect-locked-access'       => "تواڈا کھاتہ اجازت نہیں دیندا کہ تسی صفحے دے حفاظتی درجے نوں تبدیل کرو۔<br />
ایتھے صفحے آسطے موجودہ ترتیب نے '''$1''':",
'protect-cascadeon'           => 'اے صفحہ ایس ویلے بچایا گیا کیوجہ اے اونھاں {{PLURAL:$1|page, which has|صفحیاں وچ شامل اے }} جیناں دی کسکیڈنگ حفاظت آن اے۔

تسی اس صفحے دا بچاؤ لیول نوں تبدیل کرسکدے او لیکن اے اودھے کسکیڈنگ بچاؤ تے اثر نئیں کریگی۔',
'protect-default'             => 'ساریاں نوں جان دیو',
'protect-fallback'            => '"$1" دی اجازت دی لوڑ اے',
'protect-level-autoconfirmed' => 'غیر تسلیم شدہ ورتن والے نوں روکو',
'protect-level-sysop'         => 'صرف سائسوپس',
'protect-summary-cascade'     => 'کسکیڈنگ',
'protect-expiring'            => 'ختم ہوندا اے $1 (UTC)',
'protect-expiring-local'      => 'انت $1',
'protect-expiry-indefinite'   => 'بے انت',
'protect-cascade'             => 'اس صفحے وچ شامل صفحیاں نوں بچاؤ (کسکیڈنگ ح‌فاظت)۔',
'protect-cantedit'            => 'تسی اس صفحے دے حفاظتی درجے نوں نہیں بدل سکدے کیونکہ توانوں اس کم دی اجازت نہیں اے۔',
'protect-othertime'           => 'دوجے ویلے:',
'protect-othertime-op'        => 'دوجے ویلے:',
'protect-existing-expiry'     => 'ہن دا مکن ویاہ: $3، $2',
'protect-otherreason'         => ':دوجی وجہ',
'protect-otherreason-op'      => 'ہور وجہ',
'protect-dropdown'            => '*بچاؤ دیاں عام وجہاں
** زیادہ وینڈالزم
** زیادہ سپامنگ
**  بے مقصد لکھت چگڑے
** زیادہ ویکھیا جان والا صفہ',
'protect-edit-reasonlist'     => 'تبدیلی دیاں وجہ لکھو',
'protect-expiry-options'      => '1 کینٹہ:1 کینٹہ,1 دن:1 دن,1 ہفتہ:1 ہفتہ,2 ہفتہ:2 ہفتہ,1 معینہ:1 معینہ,3 معینے:3 معینے,6 معینے:6 معینے,1 ورہ:1 ورہ,انگنت:انگنت',
'restriction-type'            => 'اجازت:',
'restriction-level'           => 'حفاظتی درجہ:',
'minimum-size'                => 'چھوٹا ترین ناپ',
'maximum-size'                => 'وڈآ ترین ناپ:',
'pagesize'                    => '(بائٹ)',

# Restrictions (nouns)
'restriction-edit'   => 'لکھو',
'restriction-move'   => 'لے چلو',
'restriction-create' => 'بناؤ',
'restriction-upload' => 'اتے چاڑو',

# Restriction levels
'restriction-level-sysop'         => 'پوری طرح بچایا ہویا',
'restriction-level-autoconfirmed' => 'کج بچایا گیا',
'restriction-level-all'           => 'کسے وی درجے تے',

# Undelete
'undelete'                     => 'مٹاۓ گۓ صفحے ویکھو',
'undeletepage'                 => 'مٹاۓ گۓ صفحے ویکھو تے واپس لے آؤ',
'undeletepagetitle'            => "'''تھلے مٹایاں ریوین [[:$1|$1]]'''",
'viewdeletedpage'              => 'مٹاۓ گۓ صفحے ویکھو',
'undeletepagetext'             => 'تھلے دتے گۓ {{انیک:$1|صفہ مٹا دتا گیا اے پر|$1 صفے مٹا دتے گۓ نیں پر}} ہلے وی آرکائیو ج نیں تے والس لیاۓ جاسکدے نیں۔
آرکئیو نوں صاف کیتا جاسکدا اے۔',
'undelete-fieldset-title'      => 'ریویین واپس',
'undeleteextrahelp'            => "صفے دی ساری تریخ واپس لیاں لئی سارے چیکبوکسز دے ٹھیک مٹادیو تے '''''{{int:undeletebtn}}''''' تے کلک کرو
چونویں واپسی کرن لئی اوناں ڈبیاں نوں کلک کرو جناں نال جڑیاں ریویناں نوں واپس کرنا اے تے '''''{{int:undeletebtn}}''''' تے کلک کرو۔",
'undeleterevisions'            => '$1 {{انیک:$1|ریوین|ریویناں}} آرکائیو چ',
'undeletehistory'              => 'اگر تسیں صفہ واپس کردے او، ساریاں ریویناں رکارڈ چ واپس ہوجان گیاں۔
اگر اک نواں صفہ اوسے ناں نال  مٹان دے مگروں، واپس کیتیاں ریویناں پہلے رکارڈ چ دسن گیاں۔',
'undeleterevdel'               => 'مٹاؤ واپسی نئیں ہووے گی  اگر ایہ اتے دے صفے تے ہوندی اے یا فائل ریویناں کج کج مٹائیاں جاندیاں نیں۔
ایہو جے کیسز چ،  تسیں لازی انچیک یا سامنے کرو مٹائیاں دیوناں۔',
'undeletehistorynoadmin'       => 'صفہ مٹا دتا گیا اے۔
مٹان دی وجہ تھلے دتی سمری چ دسی گئی اے، ورتن والیاں دی بارے دساں بارے جناں ایہ صفہ تبدیل کیتا مٹان توں پہلے۔
ایناں مٹائیاں ریویناں دی اصل لکھت  صرف مکھیاواں کول اے۔',
'undelete-revision'            => '$1 دیاں مٹائیاں گیاں ریویناں (4$ دی 5$ تے ) 3$ توں:',
'undeleterevision-missing'     => 'ناں منی جان والی یا غیب ریوین۔
تواڈے  کول اک خراب جوڑ ہوسکدا اے یا یا ریوین ہوسکدا اے واپس کردتی جاۓ یا آرکائیو توں ہٹا دتی جاوے۔',
'undelete-nodiff'              => 'کوئی پہلی ریوین ناں لبی۔',
'undeletebtn'                  => 'بحال کرو',
'undeletelink'                 => 'ویکھو/بحال کرو',
'undeleteviewlink'             => 'وکھالہ',
'undeletereset'                => 'پہلی حالت تے لے آؤ',
'undeleteinvert'               => 'وچوں چناؤ',
'undeletecomment'              => 'وجہ',
'undeletedrevisions'           => '{{انیک:$1|1 ریوین|$1 ریویناں}} واپس',
'undeletedrevisions-files'     => '{{انیک:$1|1 ریوین|$1 ریویناں}} تے {{انیک:$2|1 فائل|$2 فائلاں}} واپس',
'undeletedfiles'               => '{{انیک:$1|1 فائل|$1 فائلاں}} واپس',
'cannotundelete'               => 'مٹاؤ واپسی فیل:
کسے ہور نے حورے پہلے ای صفہ واپس اردتا اے۔',
'undeletedpage'                => "'''$1 واپس کردتی گئی اے'''

 [[خاص:لاگ/مٹاؤ|مٹان لاگ]] نوں ویکھو نیڑے دے مٹان تے واپسی دے رکارڈ لئی۔.",
'undelete-header'              => '[[خاص:لاگ/مٹاؤ|مٹان لاگ]] نوں ویکھو نیڑے دے مٹاۓ گۓ دے رکارڈ لئی۔.',
'undelete-search-box'          => 'مٹاۓ گۓ صفحے کھوجو',
'undelete-search-prefix'       => 'صفے وکھاؤ جیہڑے شروع ہون:',
'undelete-search-submit'       => 'کھوجو',
'undelete-no-results'          => 'مٹائی آرکائیو چ کوئی رلدے صفے نئیں لبے۔',
'undelete-filename-mismatch'   => 'فائل مٹاؤ واپسی نئیں ہوسکدی ٹائمسٹیمپ نال $1 : فائل ناں نئیں جڑدے',
'undelete-bad-store-key'       => 'ویلے $1 نال فائل ریوین دی مٹاؤ واپسی نئیں ہوسکدی:مٹان توں پہلے فائل غیب سی۔',
'undelete-cleanup-error'       => 'ناں ورتی گئی آرکائیو فائل "$1" دے مٹانے چ غلطی۔',
'undelete-missing-filearchive' => 'فائل آرکائیو آئی ڈی $1 نوں واپس کرن چ ناکامی کیوں جے اے ڈیٹابیس نئیں اے۔
خورے اے پہلے ای مٹ چکی ہووے۔',
'undelete-error-short'         => 'فاغل واپس کرن چ غلطی: $1',
'undelete-error-long'          => 'فائل واپس کرن  لگیاں غلطیاں ہوئیاں:
$1',
'undelete-show-file-confirm'   => 'تساں نوں کیا پک اے جے تسیں فائل "<نوکی>$1</نوکی>" دی مٹائی ریوین  $2 توں $3 تک ویکھنا چاندے او؟',
'undelete-show-file-submit'    => 'ہاں جی',

# Namespace form on various pages
'namespace'                     => 'ناں دی جگہ:',
'invert'                        => 'وچوں چناؤ',
'tooltip-invert'                => 'ایس ڈبے نوں ویکھو تبدیلیاں چھپان لئی چونویں ناںتھاں تے (تے رلدے ناںتھاں اگر چیک کیتے جان)',
'namespace_association'         => 'رلدے ناں تھاں',
'tooltip-namespace_association' => 'ایس ڈبے نون وی ویکھو گل بات یا ناںتھاں  چونویں ناںتھاں نال رلدا۔',
'blanknamespace'                => '(خاص)',

# Contributions
'contributions'       => 'ورتن آلے دا حصہ',
'contributions-title' => '$1 دے کم',
'mycontris'           => 'میرا کم',
'contribsub2'         => '$1 آستے ($2)',
'nocontribs'          => 'ایناں ناپاں نال رلدیاں کوئی تبدیلیاں نئیں لبیاں۔',
'uctop'               => '(اتے)',
'month'               => 'مہینے توں (تے پہلاں):',
'year'                => 'سال توں (تے پہلاں):',

'sp-contributions-newbies'             => 'صرف نویں ورتن والیاں دے کم وکھاؤ',
'sp-contributions-newbies-sub'         => 'نویں کھاتیاں آستے',
'sp-contributions-newbies-title'       => 'نویں کھاتے چ ورتن والے دے کم',
'sp-contributions-blocklog'            => 'لاگ روکو',
'sp-contributions-deleted'             => 'ورتن والے دے کم مٹادتے گۓ۔',
'sp-contributions-uploads'             => 'چڑھائیاں فائلاں',
'sp-contributions-logs'                => 'لاگز',
'sp-contributions-talk'                => 'گل بات',
'sp-contributions-userrights'          => 'ورتن والیاں دے حقاں دا سعاب کتاب',
'sp-contributions-blocked-notice'      => 'ایس ورتن والے تے اجکل روک اے۔ 
روکن لاگ چ ایدے بارے تھلے لکھیا اے۔',
'sp-contributions-blocked-notice-anon' => 'ایس آئی پی پتے تے اجکل روک اے۔ 
روکن لاگ چ ایدے بارے تھلے لکھیا اے۔',
'sp-contributions-search'              => 'حصے پان آلیاں دی تلاش',
'sp-contributions-username'            => 'آئی پی پتہ یا ورتن آلا ناں:',
'sp-contributions-toponly'             => 'صرف اوہ تبدیلیاں وکھاؤ جیہڑیاں سب توں نیڑے ویلے ہویاں نیں۔',
'sp-contributions-submit'              => 'کھوجو',

# What links here
'whatlinkshere'            => 'ایتھے کیدا تعلق اے',
'whatlinkshere-title'      => 'او صفحات جیڑے "$1" نال جڑے نے',
'whatlinkshere-page'       => 'صفحہ:',
'linkshere'                => "تھلے دتے گۓ صفحے اس دے نال جڑدے نے '''[[:$1]]''':",
'nolinkshere'              => "'''[[:$1]]''' دے نال کسے دا جوڑ نہیں",
'nolinkshere-ns'           => "چنے ناں چ کسے صفے دا '''[[:$1]]''' نال جوڑ نئیں۔",
'isredirect'               => 'ریڈائرکٹ صفحہ',
'istemplate'               => 'ملن',
'isimage'                  => 'مورت دا جوڑ',
'whatlinkshere-prev'       => '{{PLURAL:$1|پچھل $1ا|پچھلا}}',
'whatlinkshere-next'       => '{{PLURAL:$1|اگلا $1|اگلا}}',
'whatlinkshere-links'      => '← تعلق',
'whatlinkshere-hideredirs' => '$1 ریڈائریکٹس',
'whatlinkshere-hidetrans'  => '$1 ٹرانسکلوژن',
'whatlinkshere-hidelinks'  => '$1 جوڑ',
'whatlinkshere-hideimages' => '$1 مورت جوڑ',
'whatlinkshere-filters'    => 'نتارے',

# Block/unblock
'autoblockid'                     => 'اپنے آپ روک #$1',
'block'                           => 'ورتن آلے نوں روکو',
'unblock'                         => 'ورتن آلے تے روک بند کرو',
'blockip'                         => 'اس ورتن والے نو روکو',
'blockip-title'                   => 'ورتن آلے نوں روکو',
'blockip-legend'                  => 'ورتن آلے نوں روکو',
'blockiptext'                     => 'تھلے دتا گیا فارم ورتو کسے خاص آئی پی پتے یا ورتن ناں  نوں لکھن روک لئی۔ ایہ صرف ونڈالزم توں بچن لئی اے، تے [[{{میڈیاوکیپولیسی:پولیسی-یوآر ایل}}|پولیسی]] دے نال اے۔ 
تھلے خاص وجہاں دسو (ادھارن لئی خاص صفیاں دی دس دیو جیہڑے خراب کیتے گۓ۔)',
'ipadressorusername'              => 'آئی پی پتہ یا ورتن آلے دا ناں:',
'ipbexpiry'                       => 'انت:',
'ipbreason'                       => 'وجہ:',
'ipbreasonotherlist'              => 'ہور وجہ',
'ipbreason-dropdown'              => '*روکن دیاں عام وجہاں
** غلط جانکاری دینا
** صفیاں توں مواد مٹانا
** بارلیاں ویب سائٹاں نال غلط جوڑ جوڑنا
** خراب / احمفانہ مواد صفیاں چ پانا
** دوجیاں نوں ڈرانا
** کھاتیاں نوں خراب کرنا
** ناں منیا جان والا ورتن ناں ورتنا',
'ipb-hardblock'                   => 'لاگ ان ہووے ورتن والیاں نوں  ایس آئی پی پتے نوں ورتن توں روکو',
'ipbcreateaccount'                => 'کھاتہ کھولنا روکو',
'ipbemailban'                     => 'ورتن آلے نوں ای میل پیجن توں روکو',
'ipbenableautoblock'              => 'اپنے آپ ای ایس ورتن والے نے جیہڑا آخری آئی پی پتہ ورتیا اے اونوں روکو، تے کوئی وی فیر ورتے جان والے آئی پی پتے۔',
'ipbsubmit'                       => 'اس ورتن آلے نوں روکو',
'ipbother'                        => 'دوجے ویلے:',
'ipboptions'                      => 'دو کینٹے:2 hours,1 دن:1 day,3 دن:3 days,1 ہفتہ:1 week,2 ہفتے:2 weeks,1 مہینہ:1 month,3 مہینے:3 months,6 مہینے:6 months,1 سال:1 year,بے انت:infinite',
'ipbotheroption'                  => 'دوجا',
'ipbotherreason'                  => 'دوجیاں ہور وجہ:',
'ipbhidename'                     => 'ورتن ناں نوں تبدیلیاں تے لسٹاں توں بچاؤ',
'ipbwatchuser'                    => 'ایس ورتن والے دے ورتن تے گل بات صفے تے اکھ رکھو۔',
'ipb-disableusertalk'             => 'ایس ورتن والے نوں جدوں تک ایدے تے روک اے اپنے گلبات صفے چ تبدیلی کرن توں روکو',
'ipb-change-block'                => 'ایناں تبدیلیاں نال ایس ورتن والے نوں فیر روکو',
'ipb-confirm'                     => 'روک پکی کرو',
'badipaddress'                    => 'آئی پی پتہ ٹھیک نئیں',
'blockipsuccesssub'               => 'روک کامیاب',
'blockipsuccesstext'              => '[[خاص:کم/$1|$1]] روک دتے گۓ نیں.<br />
ویکھو [[خاص:روک لسٹ|آئی پی روک لسٹ]] روکاں ویکھن لئی۔.',
'ipb-blockingself'                => 'تسیں اپنے آپ تے آپ روک لان والے او! توانوں یقین اے جے تسیں ایہ کرنا جاندے او؟',
'ipb-confirmhideuser'             => "تسیں اک ورتن والے تے روک لان لگے 'لکیا ورتن والا' نال۔ ایہ ایس ورتن والے دا ناں ساریاں لسٹاں تے لاگاں چوں دبالوے گا۔ کیا توانوں یقین اے جے تسیں ایہو ای کرنا چاندے او۔",
'ipb-edit-dropdown'               => 'روک دی وجہ تبدیل کرو',
'ipb-unblock-addr'                => '$1 توں روک ہٹاؤ',
'ipb-unblock'                     => 'ورتن والا یا آئی پی پتہ کھولو',
'ipb-blocklist'                   => 'روکیاں گياں نوں ویکھو',
'ipb-blocklist-contribs'          => '$1 دے کم',
'unblockip'                       => 'ورتن آلے تے روک بند کرو',
'unblockiptext'                   => 'تھلے دتا گیا فارم ورتو لکھن دی ازادی لین لئی پہلاں توں روکے آئی پی پتے  یا ورتن ناں لئی۔',
'ipusubmit'                       => 'ایس روک نوں ہٹاؤ',
'unblocked'                       => '[[ورتن والے:$1|$1]] توں روک ہٹا دتی گئی۔',
'unblocked-range'                 => '$1 توں روک ہٹا دتی گئی اے',
'unblocked-id'                    => 'روک $1 ہٹادتی گئی اے۔',
'blocklist'                       => 'روکے گۓ ورتن والے',
'ipblocklist'                     => 'بند کیتے گۓ آئی پی پتے تے ورتن والیاں دے ناں',
'ipblocklist-legend'              => 'روکیا گیا ورتن والا لبو',
'blocklist-userblocks'            => 'روکے کۓ کھاتے لکاؤ',
'blocklist-tempblocks'            => 'تھوڑے چر لئی روکے گۓ لکاؤ',
'blocklist-addressblocks'         => 'کلے آئی پی بلاکس لکاؤ',
'blocklist-rangeblocks'           => 'رینج روکے گۓ لکاؤ',
'blocklist-timestamp'             => 'ویلے دی مہر',
'blocklist-target'                => 'تارگٹ',
'blocklist-expiry'                => 'انت ہوندا اے:',
'blocklist-by'                    => 'روکن ایڈمن',
'blocklist-params'                => 'روک ولگن',
'blocklist-reason'                => 'وجہ:',
'ipblocklist-submit'              => 'کھوجو',
'ipblocklist-localblock'          => 'لوکل روک',
'ipblocklist-otherblocks'         => 'دوجیاں روکاں',
'infiniteblock'                   => 'بے انت',
'expiringblock'                   => '$1 نوں $2 بجے ایکسپائری مک رئی اے۔',
'anononlyblock'                   => 'گمنام',
'noautoblockblock'                => 'اپنے آپ روک نکارہ',
'createaccountblock'              => 'کھاتا کھولنے تے پابندی اے',
'emailblock'                      => 'ای میل روک دتی گئی اے',
'blocklist-nousertalk'            => 'اپنا گل بات والا صفہ آپ تبدیل نئیں کرسکدا۔',
'ipblocklist-empty'               => 'روک لسٹ خالی اے۔',
'ipblocklist-no-results'          => 'پچھیا گیا IP پتے نوں نئیں روکیا گیا۔',
'blocklink'                       => 'روک',
'unblocklink'                     => 'روک ختم',
'change-blocklink'                => 'روک نوں بدلو',
'contribslink'                    => 'حصے داری',
'emaillink'                       => 'ای-میل پیجو',
'autoblocker'                     => 'اپنے آپ روکیا گیا کیوں جے تواڈا آئی پی پتہ "[[ورتن والا:$1|$1]]" نے ورتیا۔$1 دی روک دی وجہ :"$2"',
'blocklogpage'                    => 'لاگ روکو',
'blocklog-showlog'                => 'ایس ورتن والے نوں پہلے روکیا گیا سی۔
تھلے اتے پتے لئی روک لاگ دتی گئی اے۔',
'blocklog-showsuppresslog'        => 'اے ورتن والا روکیا گیا اے تے لکیا سی۔
روک لاگ تھلے ویکھن لئی دتی گئی اے۔',
'blocklogentry'                   => 'روک دتا گیا تے اے رکاوٹ دا ویلا $2 $3 مک جاۓ گا [[$1]]',
'reblock-logentry'                => 'روک سیٹنگ [[$1]] لئی بدل دتی گئی اے جیدا مکن ویلہ $2 $3 اے۔',
'blocklogtext'                    => 'اے کماں نوں روکن یا کھولن دا ورتن لاگ اے۔ اپنے آپ روکے گۓ آئی پی پتے لسٹ چ نئیں۔

[[خاص:روک لسٹ|آئی پی روک لسٹ]] ویکھو ہن دے روکے گۓ یا بند کیتے پتیاں دی لسٹ لئی۔
[[خاص:روکلسٹ|آئی پی روک لسٹ]] دی لسٹ ویکھو ہن دے روکے گۓ ورتن والیاں دی',
'unblocklogentry'                 => '$1 توں روک ہٹا لئی گئی اے',
'block-log-flags-anononly'        => 'گم نام ورتن آلا',
'block-log-flags-nocreate'        => 'کھاتا کھولنے تے پابندی اے',
'block-log-flags-noautoblock'     => 'اپنے آپ روک نکارہ',
'block-log-flags-noemail'         => 'ای میل روکی گئی اے',
'block-log-flags-nousertalk'      => 'اپنا گل بات والا صفہ آپ تبدیل نئیں کرسکدا۔',
'block-log-flags-angry-autoblock' => 'ودیا روک کم کر رئی اے۔',
'block-log-flags-hiddenname'      => 'ورتن والے دا ناں لکیا',
'range_block_disabled'            => 'رینج روک دی پربندھک طاقت کم نئیں کردی۔',
'ipb_expiry_invalid'              => 'مکن ویلہ مک گیا۔',
'ipb_expiry_temp'                 => 'لکے ورتن والے تے روک پکی ہونی چائیدی اے۔',
'ipb_hide_invalid'                => 'ایس کھاتے نوں نئیں روکیا جاسکیا، ایدے چ کئی تبدیلیاں ہوسکدیاں نئیں',
'ipb_already_blocked'             => '"$1" پہلاں توں ہی روکیا ہویا اے۔',
'ipb-needreblock'                 => '$1 پہلے ای روکیا ہویا اے۔ تسیں سیٹنک بدلنا چاندے او۔',
'ipb-otherblocks-header'          => 'دوجیاں {{انیک:$1|روک|روکاں}}',
'unblock-hideuser'                => 'تسین ایس ورتن والے تے لگی روک نئیں کھول سکدے ایدا ورتن ناں لکیا اے۔',
'ipb_cant_unblock'                => 'غلطی: روکی آئی ڈی $1 نئیں لبی۔  ایدی روک پہلے ای کھول دتی گئی ہووے۔',
'ipb_blocked_as_range'            => 'غلطی: آئی پی پتہ $1 سدا نئیں روکیا گیا تے اینج نئیں کھولیا جاسکدا۔
اینوں $2 دی رینج چ روکیا گیا، جینوں کھولیا جاسکدا اے۔',
'ip_range_invalid'                => 'ناں منی جان والی آئی پی رینج۔',
'ip_range_toolarge'               => 'رینج روکاں /$1 توں وڈیاں دی اجازت نئیں۔',
'blockme'                         => 'مینوں روکو',
'proxyblocker'                    => 'دوروں روکن والا',
'proxyblocker-disabled'           => 'اس کم نوں روک دتا گیا اے۔',
'proxyblockreason'                => 'تواڈا آئی پی پتہ تے روک لگادتی گئی جے کیوں جے اے اک کھلا پراکسی اے۔
مہربانی کرکے اپنے انٹرنٹ سروس دین والے نال  یا تکنیکی مدد دین والے نال تے اوناں ایس بچاؤ خطرے بارے دسو۔',
'proxyblocksuccess'               => 'ہوگیا۔',
'sorbsreason'                     => 'تیرا آئی پی پتہ اک کھلی پراکسی وانگوں دتا گیا اے ڈی این ایس بی ایل چ {{سائیٹناں}} نے۔',
'sorbs_create_account_reason'     => 'تواڈا پتہ اک کھلا پراکسی لسٹ چ اے ڈی این ایس بی ایل نال {{سائیٹناں}} چ۔
تسیں اک کھاتہ نئیں کھول سکدے۔',
'cant-block-while-blocked'        => 'جدوں تواڈے تے روک لگی ہووے تے تسیں دوجیاں تے روک نئیں لاسکدے۔',
'cant-see-hidden-user'            => 'جس ورتن والے تے تسیں روک لارۓ اوہ اوہ پہلے روکیا جا چکیا اے تے لکیا اے۔
کیوں جے تواڈے کول لکن ورتن والے دے ح‌ نئیں  تسیں ورتن والے دے روک ناں ویکھ سکدے او ناں بدل سکدے او۔',
'ipbblocked'                      => 'تسیں دوجے ورتن والیاں تے ناں ای روک لا سکدے اوہ تے ناں ای دوجیاں دی روک کھول سکدے او، کیوں جے تسیں آپ ای روکے ہووے اوہ۔',
'ipbnounblockself'                => 'تسیں اپنے آپ تے روک نئیں ہٹا سکدے۔',

# Developer tools
'lockdb'              => 'ڈیٹابیس تے تالا لاؤ',
'unlockdb'            => 'ڈیٹابیس دا تالا کھولو',
'lockdbtext'          => 'ڈیٹابیس تے تالا لان تے سارے ورتن والیاں دی روک لگ جاۓ کی جے صفہ تبدیل کرسکن، اپنیاں تانگاں بدل سکن اپنی اکھ تھلے رکھی لسٹ نوں بدل سکن یا ہور شیواں جناں دی ڈیٹابیس چ تبدیلی دی لوڑ اے۔
مہربانی کرکے پکا کرو جے تسیں ایہ ای کرنا چاندے او تے جدوں تواڈا مرمت دا کم ہوگیا تے تے تسیں ڈیٹابیس کھول دیوگے۔',
'unlockdbtext'        => 'ڈیٹابیس دا تالا کھولن نال سارے ورتن والے صفے تبدیل کرسکن گے، اپنیاں تانگاں بدل سکن گے، اپنی اکھ تھلے رکھی لسٹ بدل سکن گے یا دوجیاں شیواں جناں دی ڈیٹابیس چ لوڑ اے۔
مہربانی کرکے پکا کرو جے تسیں ایہو ای چاندے اوہ۔',
'lockconfirm'         => 'ہاں، میں ڈیٹابیس تے تالا لانا چاندا واں۔',
'unlockconfirm'       => 'ہاں، میں سچی ڈیٹابیس دا تالا کھولنا چاندا واں۔',
'lockbtn'             => 'ڈیٹابیس تے تالا لاؤ',
'unlockbtn'           => 'ڈیٹابیس دا تالا کھولو',
'locknoconfirm'       => 'تساں پکا کرن والا ڈبہ چیک نئیں کیتا۔',
'lockdbsuccesssub'    => 'ڈیٹابیس تے تالا لگ گیا',
'unlockdbsuccesssub'  => 'ڈیٹابیس دا تالا کھل گیا',
'lockdbsuccesstext'   => 'اے ڈیٹا بیس تے تالا اے <br />
مرمت کرن مگروں یاد نال [[خاص:تالاکھولو|تالا کھول دیو]]',
'unlockdbsuccesstext' => 'ڈیٹابیس دا تالا کھل گیا اے۔',
'lockfilenotwritable' => 'ڈیٹابیس فائل تے لکھت نئیں ہوسکدی۔
ڈیٹابیس نوں کھولنا یا تالا لانا اے ویب سرور دی لکھت دی لوڑ اے۔',
'databasenotlocked'   => 'ڈیٹابیس تے تالا نئیں لگیا۔',
'lockedbyandtime'     => '(توں {{جنس:$1|$1}} نوں $2 وجے $3)',

# Move page
'move-page'                    => '$1 لے چلو',
'move-page-legend'             => 'صفحے لے چلو',
'movepagetext'                 => "تھلے دتے گۓ فـارم نوں استعمال کرکے اس صفحہ دا ناں دوبارہ رکھیا جا سکدا اے، نال ہی اس نال جڑے تاریخچہ وی نۓ ناں نال جڑ جاۓ گی۔ اسدے بعد توں اس صفحے دا پرانا ناں ، نۓ ناں دی جانب -- ریڈائریکٹ کیتے گۓ صفحہ -- بن جاۓ گا۔ لیکن اے یاد رکھو کہ دوجے صفحیاں تے، پرانے صفحہ دی جانب دتے گۓ جوڑ تبدیل نئیں ہونگے؛ اس بات نوں یقینی بنانا ضروری اے کہ کوئی دوہرہ -- پلٹایا گیا جوڑ -- نہ رہ جاۓ۔

لہذا اے یقینی بنانا تواڈی ذمہ داری اے کہ سارے جوڑ ٹھیک صفحیاں دی جانب رہنمائی کردے رین۔

اے گل وی ذہن نشین کرلو کہ اگر نۓ منتخب کردہ ناں دا صفحہ پہلاں توں ہی موجود ہو تو ہوسکدا اے کہ صفحہ منتقل نہ ہوۓ ؛ ہاں اگر پہلے توں موجود صفحہ خالی اے  یا اوہ صرف اک -- ریڈائیرکٹ کیتا گیا صفحہ -- ہوۓ تے اس دے نال کوئی تاریخچہ جڑیا نہ ہووے تے ناں بدلیا جاۓ گا۔ گویا، کسی غلطی دی صورت وچ تسی صفحہ نوں دوبارہ اسی پرانے ناں دی جانب منتقل کرسکدے اوہ تے اس طرح پہلے توں موجود کسی صفحہ وچ کوئی مٹانا یا غلطی نئیں ہوۓ گی۔

''' خبردار '''
 کسی اہم تے مشہور صفحہ دے ناں دی تبدیلی، اچانک تے پریشانی آلی گل وی ہوسکدی اے اس لئی؛ تبدیلی توں پہلاں مہربانی کر کے یقین کرلو کہ تسی اسدے نتائج جاندے او۔",
'movepagetext-noredirectfixer' => "تھلے دتا گیا فارم ورت کے صفے دا نواں ناں رکھیا جاسکدا اے، ایدا سارا رکارڈ نویں ناں ول کلیا جاسکدا اے۔ پرانا ناں اک ریڈائرکٹ صفہ بن کے نویں سرناویں ول جاوے گا۔
لازمی ویکھو [[خاص:ڈبلریڈائرکٹ|ڈبل]] یا [[خاص:ٹٹیاریڈائرکٹ|ٹٹیا ریڈائرکٹ]] نوں۔
تسیں ذمہدار او ایس گل دے جے جوڑ اوتھے ای جڑن جتھے اوناں نوں جڑنا چائیدا اے۔

اے گل یاد رکھن والی جے صفہ ہٹایا نئیں جائیگا  اگر نویں سرناویں تے پہلے ای صفہ ہیگا اے جدوں تک اے صفا خالی یا ریڈائرکٹ اے تے جیدا کوئی پرانا رکارڈ ناں ہووے۔

'''خبردار'''
اے اک چوکھے پڑھے جان والے صفے لئی انہونی تی ڈر والی گل اے؛ مہربانی کرکے اے گل پکی کرلو جے تسیں ہون والی کماں نوں جاندے اوہ تے ایدے نتاریاں نوں وی۔",
'movepagetalktext'             => "ایس نال جڑیا ہویا گلاں باتاں آلا صفحہ خودبخود ہی ایدھے نال ٹر جاۓ گا
'''اگر نئیں تے'''
*اک لکھیا گیا گلاں باتاں والا صفحہ نۓ ناں توں پہلاں توں ہی موجود اے۔
*تسی تھلے دتے گۓ ڈبے نوں مٹا دیو۔

ایوجیاں مسئلیاں چ توانوں دوویں صفحیاں نوں آپے ہی ملانے ہوۓ گا اگر تسی چاندے او۔",
'movearticle'                  => 'صفحہ لے چلو:',
'moveuserpage-warning'         => "'''خبردار''' تسیں اک ورتن صفہ ہلا رۓ اوہ۔ مہربانی کرکے اے گل یادرکھو جے صفہ ہلایا جائیگا تے ورتن والے دا ناں نئیں بدلیا جائیگا۔",
'movenologin'                  => 'لاگ ان نئیں ہوۓ او',
'movenologintext'              => 'تواڈا لازمی رجسٹرڈ ورتنوالا ہونا چائیدا اے [[خاص:ورتنلاگان|لاگڈان]] صفے نوں ہلان لئی۔',
'movenotallowed'               => 'تواڈے کول صفحے لے چلن دی اجازت نئیں اے۔',
'movenotallowedfile'           => 'تواڈے کول صفحے لے چلن دی اجازت نئیں اے۔',
'cant-move-user-page'          => 'تھواڈے کول ورتن والے صفے (نکیاں نوں چھڈ کے) نوں دوجے تھاں لجان دی اجازت نئیں۔',
'cant-move-to-user-page'       => 'تھوانوں اک صفے نوں ورتن والے صفے ول لجان دی اجازت نئیں (سواے نکے ورتن والے صفے دے)',
'newtitle'                     => 'نوے عنوان ول:',
'move-watch'                   => 'صفحے اکھ تھلے رکھو',
'movepagebtn'                  => 'صفحہ لے جاؤ',
'pagemovedsub'                 => 'لے جانا کامیاب ریا',
'movepage-moved'               => '\'\'\'"$1" نوں "$2" لے جایا گیا اے\'\'\'',
'movepage-moved-redirect'      => 'اک ریڈائرکٹ بنا دتا گیا اے۔',
'movepage-moved-noredirect'    => 'ریڈائرکٹ بنانا روک دتا گیا اے۔',
'articleexists'                => 'اس ناں دا صفحہ یا تے پہلاں توں ہی موجود اے یا فیر جیڑا ناں تسی چنیا اے درست نہیں۔<br />
کوئی دوجا ناں چنو۔',
'cantmove-titleprotected'      => 'تسیں ایتھے صفہ نئیں لیا سکدے، کیوں جے نواں سرناواں بنان توں بچا دتا گیا اے۔',
'talkexists'                   => "'''اے صفحہ کامیابی دے نال ے جایا گیا مگر ایدا گلاں باتاں آلا صفحہ رنہیں لے جایا جا سکدا کیونکہ اک نیا اسی ناں نال موجود اے۔ ایناں نوں ہتھ نال ملا دیو۔'''",
'movedto'                      => 'لے جایا گیا',
'movetalk'                     => 'تبدیلی نال جڑیاں گلاں باتاں والا صفحہ',
'move-subpages'                => 'نکے صفیاں نوں نوں لے چلو ($1 تک)',
'move-talk-subpages'           => 'گل بات صفے دے نکے صفے لے چلو ($1 تک)',
'movepage-page-exists'         => 'صفہ $1 پہلے ای ہیگا اے تے ایدے تے اپنے آپ نئیں لکھیا جاسکدا۔',
'movepage-page-moved'          => 'صفہ $1 نوں $2 ول لجایا گیا اے۔',
'movepage-page-unmoved'        => 'صفہ $1 ، $2 ول نئیں لجایا جاسکدا۔',
'movepage-max-pages'           => '1$ دے زیادہ توں زیادہ {{انیک:$1|صفہ|صفے}} تھاں بدلاۓ گۓ نیں تے کوئی ہور اپنے آپ نئیں بدلیا جائیگا۔',
'movelogpage'                  => 'ناں تبدیل کرن دا لاگ',
'movelogpagetext'              => 'تھلے سارے صفے دے پلٹن دی لسٹ دتی گئی اے۔',
'movesubpage'                  => '{{انیک:$1|نکا صفہ|نکےصفے}}',
'movesubpagetext'              => 'ایس صفے دے $1 {{انیک:$1|نکا صفہ|نکے صفے}}',
'movenosubpage'                => 'ایس صفے دے کوئی نکے صفے نئیں۔',
'movereason'                   => 'وجہ:',
'revertmove'                   => 'واپس',
'delete_and_move'              => 'مٹاؤ تے لے جاؤ',
'delete_and_move_text'         => '== مٹان دی لوڑ ==
پونچن والا صفہ "[[:$1]]" پہلے ای موجود.
کیا تسیں اینون مٹادینا چاندے او تھاں بدلن دی گل بنان لئی؟',
'delete_and_move_confirm'      => 'آہو، صفحہ مٹا دیو',
'delete_and_move_reason'       => 'مٹایا گیا ایتھوں "[[$1]]" ٹورن لئی۔',
'selfmove'                     => 'سورس تے منزل سرناویں اک ای نیں۔
اپنے آپ ول صفہ نئیں لجایا جاسکدا۔',
'immobile-source-namespace'    => '"$1" ناں تھاں ول صفے نئیں موڈے جاسکدے۔',
'immobile-target-namespace'    => '"$1" ناں تھاں ول صفے نئیں موڈے جاسکدے۔',
'immobile-target-namespace-iw' => 'انٹروکی لنک صفہ لجان لئی ٹھیک تارگٹ نئیں۔',
'immobile-source-page'         => 'ایہ صفہ ہلایا نئیں جاسکدا۔',
'immobile-target-page'         => 'اوس سرواویں ول نئیں لجا سکدے۔',
'imagenocrossnamespace'        => 'بنا فائل ناں تھاں نئیں موڑ سکدے۔',
'nonfile-cannot-move-to-file'  => 'نان-فائل نوں ناں تھاں ول نئیں لجا سکدے۔',
'imagetypemismatch'            => 'اے نویں فائل ایکسٹنشن ایس ٹائپ نال میخ نئیں کردی۔',
'imageinvalidfilename'         => 'تارگٹ فائل ناں نئیں چلدا۔',
'fix-double-redirects'         => ' کسے ریڈائریکٹ نوں نواں کرو جیہڑا اصل سرناویں ول جاوے۔',
'move-leave-redirect'          => 'پچھے اک ریڈائرکٹ چھڈو',
'protectedpagemovewarning'     => "'''خبردار''' اے صفہ بچایا گیا اے تاں جے صرف مکھیا ورتن والے اینوں لجا سکن۔
نویں لاگ انٹری تھلے اتے پتے لئی دسی کئی اے:",
'semiprotectedpagemovewarning' => "'''نوٹ:''' ایہ صفہ نوں بچایا گیا اے تاں جے کھاتے والے ورتن والے ای اینوں ایتھں لجا سکن۔
آخری لاگ انٹری اتے پتے لئی تھلے دتی گئی اے:",
'move-over-sharedrepo'         => '== فائل ہیگی ==
فائل نون ایس ٹائٹل[[:$1]]  ول لجانا اک ہور فائل تے اینوں چڑھا دے گا۔.',
'file-exists-sharedrepo'       => 'جیہڑا فائل ناں چنیاں گیا جے اوہ پہلے ای اک سانجی چ ورتیا جاریا اے۔
مہربانی کرکے اک ہور ناں چنو۔',

# Export
'export'            => 'صفحے باہر پیجو',
'exporttext'        => 'تسیں اے لکھت اگے پیج سکدے او تے لکھت تریخ اک خاص صفے دی یا کسے ایکس ایم ایل چ صفیاں صفیاں چ لپیٹی۔ اے کسے ہور وکی چ وی لیایا جاسکدا اے میڈیاوکی ورتدیاں [[خاص:لیاؤ|صفے لیاؤ]] دی راہ۔

صفے بار لجان لئی، تھلے دتے گۓ لکھت ڈبے چ سرناواں لکھو، اک سرناواں اک لائن چ  اور چنوں کیا جے تسیں  ہن دی ریوین چاندے پرانیاں دے ناۂ نال، رکارڈ صفہ لین چ، یا ہن دی ریوین  آخری تبدیلی دی جانکاری نال۔

دوجے کیس چ تسیں جوڑ وی ورت سکدے او، ادھارن لئی [[{{#خاص:لجاؤ}}/{{میڈیاوکی:پہلا صفہ}}]] صفہ "[[{{میڈیاوکی:پہلا صفہ}}]]" لئی۔',
'exportcuronly'     => 'صرف ہن والیاں ریوین نال نئں۔ پورا ریکارڈ نیں۔',
'exportnohistory'   => "'''نوٹ:''' صفیاں دا پورا ریکارڈ ایس فارم نال لیجانا کم دیاں وجہاں باجوں روک دتا گیا اے۔",
'export-submit'     => 'برامد کرو',
'export-addcattext' => 'اس ٹولی توں صفحے شامل کرو:',
'export-addcat'     => 'شامل کرو',
'export-addnstext'  => 'ناں توں صفے جوڑو:',
'export-addns'      => 'جوڑو',
'export-download'   => 'فائل دے طور تے بچاؤ',
'export-templates'  => 'سچہ شامل کرو',
'export-pagelinks'  => 'جوڑ والے صفے جوڑو ایتھوں تک:',

# Namespace 8 related
'allmessages'                   => 'سسٹم سنیآ',
'allmessagesname'               => 'ناں',
'allmessagesdefault'            => 'ڈیفالٹ لکھائی',
'allmessagescurrent'            => 'موجودہ لکھائی',
'allmessagestext'               => 'ایہ لسٹ اے پربندھ سنیعیاں دی  جیہڑے میڈیاوکی دی ناں تھاں تے ہیگے نیں۔
مہربانی کرکے [http://www.mediawiki.org/wiki/Localisation MediaWiki Localisation] تے [http://translatewiki.net translatewiki.net] تے جاؤ۔
اگر تسیں میڈیا وکی دے بولی وٹاندرے چ کم کرنا چاندے او۔',
'allmessagesnotsupportedDB'     => "اے صفہ نئیں ورتیا جاسکدا کیوں جے '''\$wgUseDatabaseMessages''' روک دتا گیا اے۔",
'allmessages-filter-legend'     => 'فلٹر',
'allmessages-filter'            => 'کسٹمائزیشن سٹیٹ فلٹر ہوئي:',
'allmessages-filter-unmodified' => 'ناں بدلیا گیا',
'allmessages-filter-all'        => 'سارے',
'allmessages-filter-modified'   => 'بدلیا',
'allmessages-prefix'            => 'پریفکس نال نتارو:',
'allmessages-language'          => 'بولی:',
'allmessages-filter-submit'     => 'چلو',

# Thumbnails
'thumbnail-more'           => 'وڈا کرو',
'filemissing'              => 'فائل گواچی ہوئی اے',
'thumbnail_error'          => '$1 دی نکی مورت بناندیاں مسئلہ',
'djvu_page_error'          => 'DjVu  صفہ رینج توں بار',
'djvu_no_xml'              => 'DjVu  فائل لئی XML  ناں لیایا جاسکیا',
'thumbnail_invalid_params' => 'تھمبنیل دے پیرامیٹر ناں منن جوگے',
'thumbnail_dest_directory' => 'ڈیسٹینیشن ڈآئریکٹری بنان چ نکامی',
'thumbnail_image-type'     => 'مورت ٹائپ بے سہارا',
'thumbnail_gd-library'     => 'انکمپلیٹ جیڈی لائبریری کنفگریشن: فنکشن $1 مسنگ',
'thumbnail_image-missing'  => 'لکدا ایہ اے فائل کوئی نیں: $1',

# Special:Import
'import'                     => 'صفحے لیاؤ',
'importinterwiki'            => 'ٹرانسوکی امپورٹ',
'import-interwiki-text'      => 'اک وکی تے صفہ سرناواں لیاں لئی چنو۔
ریوین تریخاں تے لکھاری ناں بچا لۓ جان گۓ۔
سارے وکیاں وشکار لیان کم [[خاص:لاگ/لیاؤ|لیان لاگ]] تے لاگڈ نیں۔',
'import-interwiki-source'    => 'سورس وکی/صفہ:',
'import-interwiki-history'   => 'ایس صفے لئی سارا ریرین ریکارڈ کاپی کرو۔',
'import-interwiki-templates' => 'سارے سخے رلاؤ',
'import-interwiki-submit'    => 'لے آؤ',
'import-interwiki-namespace' => 'انت ناں',
'import-upload-filename'     => 'فائل دا ناں',
'import-comment'             => 'راۓ',
'importtext'                 => 'مہربانی کرکے سورس وکی توں فائل نوں اگے پیجو [[سپیشل:ایکسپورٹ یوٹیلیٹی]] ورتدیاں ہویاں۔',
'importstart'                => 'صفحے لیا رۓ آں۔۔۔۔۔',
'import-revision-count'      => '$1 {{PLURAL:$1|ریوین}}',
'importnopages'              => 'لانے آسطے کوئی صفحہ نئیں۔',
'imported-log-entries'       => '!!{{انیک:$1|لاگ انٹریلاگ انٹریاں}}!!لیاندی گئی $1 {{انیک:$1|لاگ انٹری}}.',
'importfailed'               => 'لیانا فیل: <نوکی>$1</نوکی>',
'importunknownsource'        => 'انجان لیان سورس ٹائپ',
'importcantopen'             => 'لیاندی گئی فائل نئیں کھولی جاسکی',
'importbadinterwiki'         => 'پیڑا انٹروکی لنک',
'importnotext'               => 'خالی یا کوئی لکھائی نئیں',
'importsuccess'              => 'لے کے آگۓ آں!',
'importhistoryconflict'      => 'رپھڑ پان والی رکارڈ ریوین ہیگی (خبرے اے اے صفہ پہلے لیایا گیا ہووے)',
'importnosources'            => 'کوئی ٹرانسوکی امپورٹ سورسز نئیں دسیا گیا تے ڈائرکٹ رکارڈ چڑھاۓ کم نئیں کر رۓ۔',
'importnofile'               => 'لیاندی ہوئی کوئی فائل نئیں چڑہائی گئی۔',
'importuploaderrorsize'      => 'لیائیاں گئیاں فائلاں دا چڑھان فیل۔
اے فائل اجازت دتے گۓ چڑھان ناپ توں ود اے۔',
'importuploaderrorpartial'   => 'لیائی گئی فائل دا چڑھان فیل۔
فائل اد پچدی ای چڑھائی گئی سی۔',
'importuploaderrortemp'      => 'لیائی فائل دا چڑھان فیل۔
کچا فولڈر نئیں دسدا۔',
'import-parse-failure'       => 'XML  امپورٹ پارس ناکام',
'import-noarticle'           => 'لیانے آسطے کوئی صفحہ نئیں!',
'import-nonewrevisions'      => 'ساریاں ریوین پہلے لیائیاں گیاں۔',
'xml-error-string'           => '$1 لین $2 تے، کالم $3 (بائٹ $4 ):$5',
'import-upload'              => 'XML  ڈیٹا چڑھاؤ',
'import-token-mismatch'      => 'سیشن ڈیٹا دا کعاٹا۔
مہربانی کرکے فیر کوشش کرو۔',
'import-invalid-interwiki'   => 'ایے خاص وکی توں نئیں لیا سکدا۔',
'import-error-edit'          => 'صفہ "$1" نئیں لیایا گیا کیوں جے تھوانوں اینوں ایڈٹ کرن دی اجازت نئیں۔',
'import-error-create'        => 'صفہ "$1" نئیں لیایا گیا کیوں جے تھانوں ایدی اجازت نئیں۔',

# Import log
'importlogpage'                    => 'لاگ لے کے آؤ',
'importlogpagetext'                => 'پربنھک لیان صفیاں دا ایڈٹ رکارڈ نال دوجے وکیاں توں۔',
'import-logentry-upload'           => 'لیائی [[$1]] فائل چڑھاؤ',
'import-logentry-upload-detail'    => '!!$1 {{انیک:$1|ریوین}}',
'import-logentry-interwiki'        => 'ٹرانسوکیڈ  $1',
'import-logentry-interwiki-detail' => '$1 {{انیک:$1|ریوین}} $2 توں',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'میرا صفحہ',
'tooltip-pt-anonuserpage'         => 'ورتن صفہ IP  پتے لئی تسی تبدیل کر رۓ او۔۔۔',
'tooltip-pt-mytalk'               => 'میریاں گلاں',
'tooltip-pt-anontalk'             => 'ایس IP  پتے دیاں تبدیلیاں تے گل بات',
'tooltip-pt-preferences'          => 'میریاں تانگاں',
'tooltip-pt-watchlist'            => 'او صفحے جنہاں وچ تبدیلیاں تسی ویکھ رہے او',
'tooltip-pt-mycontris'            => 'میرے کم',
'tooltip-pt-login'                => 'جی صدقے اندر آؤ، پر اے لازمی نہیں۔',
'tooltip-pt-anonlogin'            => 'اے بہتر اے کہ لاگ ان ہو جاؤ، لیکن فیر وی اے لازمی نئیں۔',
'tooltip-pt-logout'               => 'باہر آؤ',
'tooltip-ca-talk'                 => 'اس صفحے دے بار وچ گل بات',
'tooltip-ca-edit'                 => 'تسیں اس صفحے تے لکھ سکدے او۔
محفوظ کرن توں پہلاں کچے کم نوں ویکھ لو۔',
'tooltip-ca-addsection'           => 'اس گل بات وچ حصہ لے لو۔',
'tooltip-ca-viewsource'           => 'اے صفحہ بچایا گیا اے۔
تسی اینو صرف ویکھ سکدے او۔',
'tooltip-ca-history'              => 'اس صفحے دا پرانہ ورژن۔',
'tooltip-ca-protect'              => 'اس صفحے نوں بچاؤ',
'tooltip-ca-unprotect'            => 'ایس صفے دا بچاؤ بدلو۔',
'tooltip-ca-delete'               => 'اس صفحے نوں مٹاؤ',
'tooltip-ca-undelete'             => 'ایس صفے دیاں تبدیلیاں نوں واپس لیاؤ ایس توں پہلے کے اے مٹ جاۓ۔',
'tooltip-ca-move'                 => 'اس صفحے نوں لے چلو',
'tooltip-ca-watch'                => 'اس صفحہ تے نظر رکھو',
'tooltip-ca-unwatch'              => 'اس صفحے توں نظر ہٹاؤ',
'tooltip-search'                  => 'کھوج {{SITENAME}}',
'tooltip-search-go'               => 'اس ناں دے صفحے تے چلو، اگر اے ہے گا اے',
'tooltip-search-fulltext'         => 'اس لفظ نوں صفحیاں چ لبو',
'tooltip-p-logo'                  => 'پہلا صفہ',
'tooltip-n-mainpage'              => 'پہلے صفحے دی سیر',
'tooltip-n-mainpage-description'  => 'پہلے ورقے تے جاؤ',
'tooltip-n-portal'                => 'منصوبے دے بارے وچ، توسی کی کر سکدے او تے کنج کھوج سکدے او',
'tooltip-n-currentevents'         => 'موجودہ حالات تے پچھلیاں معلومات دیکھو',
'tooltip-n-recentchanges'         => 'وکی تے نویاں تبدیلیاں۔',
'tooltip-n-randompage'            => 'بیترتیب صفحے کھولو۔',
'tooltip-n-help'                  => 'مدد لینے آلی جگہ۔',
'tooltip-t-whatlinkshere'         => 'اس نال جڑے سارے وکی صفحے۔',
'tooltip-t-recentchangeslinked'   => 'اس صفحے توں جڑے صفحیاں چ نویاں تبدیلیاں',
'tooltip-feed-rss'                => 'RSS feed for this page',
'tooltip-feed-atom'               => 'Atom feed for this page',
'tooltip-t-contributions'         => 'اس ورتن والے دے کم ویکھو',
'tooltip-t-emailuser'             => 'اس ورتن والے نو ای میل کرو',
'tooltip-t-upload'                => 'فائل چڑھاؤ',
'tooltip-t-specialpages'          => 'سارے خاص صفحے',
'tooltip-t-print'                 => 'اس صفحے دا چھپنے آلا ورژن ویکھو',
'tooltip-t-permalink'             => 'اس صفحے دے اس ورژن نال پرماننٹ لنک',
'tooltip-ca-nstab-main'           => 'مواد آلا صفحہ ویکھو',
'tooltip-ca-nstab-user'           => 'ورتن آلے دا صفحہ ویکھو',
'tooltip-ca-nstab-media'          => 'میڈیا آلا صفحہ ویکھو',
'tooltip-ca-nstab-special'        => 'اے اک خاص صفحہ اے، تےی اے صفحہ آپے نئیں لکھ سکدے',
'tooltip-ca-nstab-project'        => 'منصوبے دا صفحہ ویکھو',
'tooltip-ca-nstab-image'          => 'فائل دا صفحہ ویکھو',
'tooltip-ca-nstab-mediawiki'      => 'پربندھ سنیعہ ویکھو',
'tooltip-ca-nstab-template'       => 'سانچہ تکو',
'tooltip-ca-nstab-help'           => 'مدد دا صفحہ ویکھو',
'tooltip-ca-nstab-category'       => 'کیٹاگری آلا صفحہ ویکھو',
'tooltip-minoredit'               => 'انیو نکے کم چ گنو',
'tooltip-save'                    => 'اپنا کم بچالو',
'tooltip-preview'                 => 'کچا کم ویکھو، اس بٹن نوں بچان توں پہلاں استعمال کرو!۔',
'tooltip-diff'                    => 'اس عبارت وچ کیتیاں تبدیلیاں وکھاؤ۔',
'tooltip-compareselectedversions' => 'چنے ہوۓ صفحیاں وچ فرق ویکھو۔',
'tooltip-watch'                   => 'اس صفحے تے نظر رکھو',
'tooltip-recreate'                => 'ایہ صفہ دوبارہ بناؤ پاویں اے مٹادتا گیا ہووے۔',
'tooltip-upload'                  => 'فائل چڑھانا شروع کرو',
'tooltip-rollback'                => '"رول بیک" اک کلک چ صفحے نوں پچھلی حالت چ لے چلے گا',
'tooltip-undo'                    => '"واپس" تے کلک کرن نال توانوں صفحہ کچا وکھایا جاۓ گا۔
اس نال تسی واپس کرن دی وجہ لکھ سکو گے۔',
'tooltip-preferences-save'        => 'تانگاں بچاؤ',
'tooltip-summary'                 => 'اک نکی سمری پاؤ',

# Metadata
'notacceptable' => 'وکی سرور توانوں اوس فارمیٹ چ ڈیٹا نئیں دے سکدا جیدے چ اوہ پڑھ سکے۔',

# Attribution
'anonymous'        => '{{SITENAME}} دے گمنام ورتاوے۔',
'siteuser'         => '{{SITENAME}} ورتن والا $1',
'anonuser'         => '{{SITENAME}} گمنام ورتن والا $1',
'lastmodifiedatby' => 'ایہ صفہ آخری واری $1 $2 تے $3 نوں تبدیل کیتا گیا۔',
'othercontribs'    => '$1 دے کم تے چلدا اے۔',
'others'           => 'دوجے',
'siteusers'        => '{{SITENAME}} {{انیک:$2|ورتن والا}} $1',
'anonusers'        => '{{SITENAME}} گمنام {{انیک:$2|ورتن والا}} $1',
'creditspage'      => 'صفہ کریڈٹس',
'nocredits'        => 'ایس صفے لئی کوئی کریڈٹ جانکاری نئیں اے۔',

# Spam protection
'spamprotectiontitle' => 'سپام بچاؤ فلٹر',
'spamprotectiontext'  => 'لکھت جیہڑی تسی بچانا چاندے او اونوں سپام فلٹر نے روکیا اے۔
ایہ خورے کسے جوڑ دی بارلے  بلیکلسٹڈ سائٹ نال ہون توں ہوئی اے۔',
'spamprotectionmatch' => 'تھلے دتی گئی لکھت نے ساڈے سپام فلٹر نوں چلایا: $1',
'spambot_username'    => 'میڈیاوکی سپام سفائی',
'spam_reverting'      => 'آخری ریوین ول جیدے چ $1 دے جوڑ ناں ہون۔',
'spam_blanking'       => 'سارے ریوین جناں چ $1 نوں جوڑ نیں، طاف کیتا جاریا اے۔',

# Info page
'pageinfo-title'            => '"$1" لئی جانکاری',
'pageinfo-header-edits'     => 'تبدیلیاں',
'pageinfo-header-watchlist' => 'اکھ تھلے رکھی لسٹ',
'pageinfo-header-views'     => 'وکھالے',
'pageinfo-subjectpage'      => 'صفہ',
'pageinfo-talkpage'         => 'گل بات صفہ',
'pageinfo-watchers'         => 'ویکھن والے',
'pageinfo-edits'            => 'تبدیلیاں گنتی',
'pageinfo-authors'          => 'وکھرے لکھاریاں دی گنتی',
'pageinfo-views'            => 'را‎ ۓ گنتی',
'pageinfo-viewsperedit'     => 'تبدیلی سعاب نال وکھالے',

# Patrolling
'markaspatrolleddiff'                 => 'ویکھے گۓ دا نشان لاؤ',
'markaspatrolledtext'                 => 'ایس صفے تے ویکھن دا نشان لاؤ',
'markedaspatrolled'                   => 'ویکھن دا نشان لاؤ',
'markedaspatrolledtext'               => '[[:$1]] دے چنے وکھالے تے ویکھن دا نشان لگاؤ۔',
'rcpatroldisabled'                    => 'نیڑے تریڑے ہون والیاں تبدیلیاں دا ویکھن ناکارہ',
'rcpatroldisabledtext'                => 'ہنے ہوۓ پٹرول فیچر ایس ویلے کم نئیں کردے۔',
'markedaspatrollederror'              => 'گشت دا نشان نئیں لگ سکدا',
'markedaspatrollederrortext'          => 'تھوانوں گشت دا نشان لان لئی ریوین دسنی پۓ گی۔',
'markedaspatrollederror-noautopatrol' => 'تھوانوں اے اجازت نئیں جے تسی اپنی تبدیلیاں تے گشت دا نشان لاؤ۔',

# Patrol log
'patrol-log-page'      => 'گشت لاگ',
'patrol-log-header'    => 'اے گست لائیآں ہوئیآن ریوین دی لاگ اے۔',
'log-show-hide-patrol' => '$1 گشت لاگ',

# Image deletion
'deletedrevision'                 => 'پرانیاں مٹائیاں ریوین $1',
'filedeleteerror-short'           => 'فاغل مٹان چ غلطی: $1',
'filedeleteerror-long'            => 'فائل مٹان لگیاں غلطیاں ہوئیاں:
$1',
'filedelete-missing'              => 'فائل "$1" نئیں مٹائی جاسکدی اے ہے ای ںغیں۔',
'filedelete-old-unregistered'     => 'دسی گئی فائل ریوین "$1" ڈیٹابیس چ نئیں اے۔',
'filedelete-current-unregistered' => 'دسی گئی فائل "$1" ڈیٹابیس چ نئیں۔',
'filedelete-archive-read-only'    => "آرکائیو ڈائریکٹری '$1' لکھن قابل نئیں ویبسرور توں",

# Browsing diffs
'previousdiff' => '← پرانی لکھائی',
'nextdiff'     => 'نویں لکھائی →',

# Media information
'mediawarning'           => "'''خبردار''' : اینج دی فائل چ غلط کوڈ ہوسکدا اے۔ 
اینوں ورت کے تسیں اپنے کمپیوٹر نوں خراب کرسکدے او۔",
'imagemaxsize'           => "مورت ناپ حد:<br />''(دسن والیاں فائل صفیاں لئی)''",
'thumbsize'              => 'تھمبنیل ناپ',
'widthheightpage'        => '$1×$2, $3 {{انیک:$3|صفہ|وفے}}',
'file-info'              => 'فائل ناپ: $1، MIME  ٹائپ: $2',
'file-info-size'         => 'پکسل:$1 × $2, فائل سائز: $3, مائم ٹائپ: $4',
'file-info-size-pages'   => '$1 × $2 پکسل, فائل ناپ: $3, مائم ٹائپ: $4, $5 {{انیک:$5|صفہ|صفے}}',
'file-nohires'           => 'اس توں وڈی فوٹو موجود نہیں۔',
'svg-long-desc'          => 'ایس وی جی فائل، پکسل:$1 × $2، فائل سائز: $3',
'show-big-image'         => 'وڈی مورت',
'show-big-image-preview' => 'ایس وکھالے دا ناپ: $1۔',
'show-big-image-other'   => 'دوجے {{انیک:$2|ریزولوشن|ریزولوشنز}}: $1.',
'show-big-image-size'    => '$1 × $2 پکسلز',
'file-info-gif-looped'   => 'لوپڈ',
'file-info-gif-frames'   => '$1 {{انیک:$1|فریم|فریمز}}',
'file-info-png-looped'   => 'لوپڈ',
'file-info-png-repeat'   => 'چلایا $1 {{انیک:$1|واری}}',
'file-info-png-frames'   => '$1 {{انیک:$1|فریم}}',

# Special:NewFiles
'newimages'             => 'نئی فائلاں دی نگری',
'imagelisttext'         => "تھلے اک لسٹ دتی گئی اے '''$1''' {{انیک:$1|فائل}} وکھریاں کیتیاں $2.",
'newimages-summary'     => 'اے خاص صفہ آخری چڑھائیاں فائلاں دسدا اے۔',
'newimages-legend'      => 'فلٹر',
'newimages-label'       => 'ففائل ناں (یا ایدا انگ)',
'showhidebots'          => '(بوٹ $1)',
'noimages'              => 'ویکھن آسطے کج نئیں۔',
'ilsubmit'              => 'کھوجو',
'bydate'                => 'تاریخ نال',
'sp-newimages-showfrom' => '$1، $2 توں نویاں فائلاں دسو',

# Video information, used by Language::formatTimePeriod() to format lengths in the above messages
'seconds' => '$1 {{انیک:$1|سکنٹ|سکنٹاں}}',
'minutes' => ' {{انیک:$1|منٹ|منٹاں}}',
'hours'   => ' {{انیک:$1|کینٹا|کینٹے}}',
'days'    => ' {{انیک:$1|دن|دناں}}',
'ago'     => '$1 پہلے',

# Bad image list
'bad_image_list' => 'فارمیٹ اینج اے:۔

صرف لسٹ آلیاں چیزاں (او لائناں جیڑیاں * توں شروع ہوندیاں نے) تے فور کیتا جاندا اے۔ لائن تے پہلا جوڑ لازمی طور تے غلط فائل نال جڑدا اے۔ اسے لائن تے کوئی بعد چ آنے آلا جوڑ خاص سمجھیا جاۓ گا یعنی او صفحے جتھے فائل ان لائن ہو سکدی اے۔',

# Metadata
'metadata'          => 'میٹا ڈیٹا',
'metadata-help'     => 'اس فائل وچ ہور وی معلومات نے، شاید او ڈیجیٹل کیمرے یا سکینر نے پائیاں گئیاں نے جس نال اینو کچھیا یا ڈیجیٹل بنایا گیا اے۔<div/>
اگر فائل نو ایدی اصلی حالت توں تبدیل کیتا گیا اے تے کجھ تفصیلات تبدیل ہوئی فائل دے بارے چ نئیں دسن گیاں۔',
'metadata-expand'   => 'ہور تفصیلات دسو',
'metadata-collapse' => 'تفصیلات چھپاؤ',
'metadata-fields'   => 'ایگزف میٹاڈیٹا ایتھے دتے گۓ مورت آلے صفحے تے دتے جان گے جدوں میٹاڈیٹا ٹیبل کھلیا ہوۓ گا۔ باقی چیزاں بائی ڈیفالٹ چھپیاں رہن گئیاں
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* isospeedratings
* focallength
* artist
* copyright
* imagedescription
* gpslatitude
* gpslongitude
* gpsaltitude',

# EXIF tags
'exif-imagewidth'                  => 'چوڑائی',
'exif-imagelength'                 => 'اچائی',
'exif-bitspersample'               => 'بٹو اک کمپوننٹ چ',
'exif-compression'                 => 'کمپریشن سکیم',
'exif-photometricinterpretation'   => 'پکسل کمپوزیشن',
'exif-orientation'                 => 'اورینٹیشن',
'exif-samplesperpixel'             => 'کمپونینٹ گنتی',
'exif-planarconfiguration'         => 'ڈیٹا ارینجمنٹ',
'exif-ycbcrsubsampling'            => 'سبسیمپلنگ ریشو وآئی توں سی۔',
'exif-ycbcrpositioning'            => 'وآئی تے سی دی تھاں',
'exif-xresolution'                 => 'ہوریزنٹل ریزولوشن',
'exif-yresolution'                 => 'ورٹیکل ریزولوشن',
'exif-stripoffsets'                => 'امیج ڈیٹا لوکیشن',
'exif-rowsperstrip'                => 'اک سٹرپ چ قطاراں',
'exif-stripbytecounts'             => 'کمپریسڈ سٹرپ چ بائٹس',
'exif-jpeginterchangeformat'       => 'JPEG SOI توں آفسیٹ',
'exif-jpeginterchangeformatlength' => 'JPEG  دے بائٹس',
'exif-whitepoint'                  => 'وائٹ پوائنٹ کرومیٹیسٹی',
'exif-primarychromaticities'       => 'کرومیٹیسٹیز آف پرآمریٹیز',
'exif-ycbcrcoefficients'           => 'کلر سپیس ٹرانسنارمیشن میٹرکس کوایفیشینٹز',
'exif-referenceblackwhite'         => 'کالے چٹے جوڑے دی ریفرنس ویلیو',
'exif-datetime'                    => 'فائل بدلن دی تاریخ تے ویلا',
'exif-imagedescription'            => 'مورت دا ناں',
'exif-make'                        => 'کیمرہ بنانے آلا',
'exif-model'                       => 'کیمرا ماڈل',
'exif-software'                    => 'استعمال ہویا سافٹویر',
'exif-artist'                      => 'بنانے آلا',
'exif-copyright'                   => 'کاپی رائٹ مالک',
'exif-exifversion'                 => 'ایگزف ورین',
'exif-flashpixversion'             => 'سپورٹڈ فلیشپکس ورین',
'exif-colorspace'                  => 'رنگ سپیس',
'exif-componentsconfiguration'     => 'ہر انگ دا مطلب',
'exif-compressedbitsperpixel'      => 'امیج کمپریشن موڈ',
'exif-pixelydimension'             => 'امیج چوڑائی',
'exif-pixelxdimension'             => 'امیج اچائی',
'exif-usercomment'                 => 'ورتن آلے دی صلاع',
'exif-relatedsoundfile'            => 'رلدی آڈیو فائل',
'exif-datetimeoriginal'            => 'تریخ تے ویلہ ڈیٹا جنریشن دا',
'exif-datetimedigitized'           => 'ڈجیٹائزنگ دا ویلہ تے تریخ',
'exif-subsectime'                  => 'تریخ ویلہ سبسیکنڈز',
'exif-subsectimeoriginal'          => 'تریخ ویلہ اورجنل سبسیکنڈز',
'exif-subsectimedigitized'         => 'تریخ ویلہ ڈجیٹا‎زڈ سبسیکنڈز',
'exif-exposuretime'                => 'ایکسپویر ویلہ',
'exif-exposuretime-format'         => '$1 سکنٹ ($2)',
'exif-fnumber'                     => 'ایف نمبر',
'exif-exposureprogram'             => 'ایکسپویر پروگرام',
'exif-spectralsensitivity'         => 'سحیکٹرل سنسیٹیوٹی',
'exif-isospeedratings'             => 'ISO  سپیڈ ریٹنگ',
'exif-shutterspeedvalue'           => '!!!!شٹر دی تیزی',
'exif-aperturevalue'               => '!!اپیکس!!اپرچر',
'exif-brightnessvalue'             => 'اپیکس چانن',
'exif-exposurebiasvalue'           => 'اپیکس کھلن بیاس',
'exif-maxaperturevalue'            => 'وڈا لینڈ اپرچر',
'exif-subjectdistance'             => 'کھچن والی چیز دا پینڈا',
'exif-meteringmode'                => 'میٹرنگ موڈ',
'exif-lightsource'                 => 'روشنی دا ذریعہ',
'exif-flash'                       => 'فلیش',
'exif-focallength'                 => 'لنز فوکل لنتھ',
'exif-subjectarea'                 => 'سبجکٹ ایریا',
'exif-flashenergy'                 => 'فلیش دی طاقت',
'exif-focalplanexresolution'       => 'فوکل پلین ‌x ریزولوشن',
'exif-focalplaneyresolution'       => 'فوکل پلین  Y ریزولوشن',
'exif-focalplaneresolutionunit'    => 'فوکل پلین ریزولوشن سنٹر',
'exif-subjectlocation'             => 'ہن والی تھاں',
'exif-exposureindex'               => 'ایکسپویر انڈیکس',
'exif-sensingmethod'               => 'سینسنگ ول',
'exif-filesource'                  => 'فائل دا ذریعہ',
'exif-scenetype'                   => 'سین ٹائپ',
'exif-customrendered'              => 'کسٹم امیج پروسیسنگ',
'exif-exposuremode'                => 'ایکسپویر موڈ',
'exif-whitebalance'                => 'چٹی پدھر',
'exif-digitalzoomratio'            => 'ڈجیٹل زوم ریشو',
'exif-focallengthin35mmfilm'       => '35 م م دی فلم دا فوکل لنتھ۔',
'exif-scenecapturetype'            => 'سین کیپچر ٹائپ',
'exif-gaincontrol'                 => 'سین کنٹرول',
'exif-contrast'                    => 'فرق',
'exif-saturation'                  => 'سیجوریشن',
'exif-sharpness'                   => 'صفائی',
'exif-devicesettingdescription'    => 'ڈیوائس سیٹنگ ڈسکرپشن',
'exif-subjectdistancerange'        => 'شے دے دور ہون دی رینج',
'exif-imageuniqueid'               => 'امیج دی خاص نشانی',
'exif-gpsversionid'                => 'GPS  ٹیگ ورین',
'exif-gpslatituderef'              => 'اتر یا دکھن لیٹیچیوڈ',
'exif-gpslatitude'                 => 'لیٹیچیوڈ',
'exif-gpslongituderef'             => 'چڑھدے یا لیندے لیٹیچیوڈ',
'exif-gpslongitude'                => 'طول بلد',
'exif-gpsaltituderef'              => 'اچائی دس',
'exif-gpsaltitude'                 => 'اچائی',
'exif-gpstimestamp'                => 'جی پی ایس ویلہ (ایٹمی کعڑی)',
'exif-gpssatellites'               => 'ناپن لئی سیٹلائٹ ورتیا گیا اے۔',
'exif-gpsstatus'                   => 'ریسیور سٹیٹس',
'exif-gpsmeasuremode'              => 'ناپ موڈ',
'exif-gpsdop'                      => 'ناپ پریسیین',
'exif-gpsspeedref'                 => 'تیزی دا ناپ',
'exif-gpsspeed'                    => 'جی پی ایس ریسیور دی سپیڈ',
'exif-gpstrackref'                 => 'ٹرن دی ڈائریکشن دا اتہ پتہ',
'exif-gpstrack'                    => 'چلن دی راہ',
'exif-gpsimgdirectionref'          => 'امیج دی ڈائریکشن دا اتہ پتہ',
'exif-gpsimgdirection'             => 'مورت دی راہ',
'exif-gpsmapdatum'                 => 'جیوڈیٹک سروے ڈیٹا ورتیا گیا اے۔',
'exif-gpsdestlatituderef'          => 'پینڈے لیٹیچیوڈ دا اتہ پتہ',
'exif-gpsdestlatitude'             => 'لیٹیچیوڈ پینڈا',
'exif-gpsdestlongituderef'         => 'پینڈے لونگیچیوڈ دا اتہ پتہ',
'exif-gpsdestlongitude'            => 'پینڈے دی لونگیچیوڈ',
'exif-gpsdestbearingref'           => 'پینڈے دی بیرنگ دا اتہ پتہ',
'exif-gpsdestbearing'              => 'پینڈے دی بیرنگ',
'exif-gpsdestdistanceref'          => 'پینڈے توں پینڈے دا اتہ پتہ',
'exif-gpsdestdistance'             => 'پونچن والی تھاں نوں پینڈا',
'exif-gpsprocessingmethod'         => 'جی پی ایس پروسیسنگ ول دا ناں',
'exif-gpsareainformation'          => 'جی پی ایس علاقے دا ناں',
'exif-gpsdatestamp'                => 'جی پی ایس تریخ',
'exif-gpsdifferential'             => 'جی پی ایس ڈفرینشیل کوریکشن',
'exif-jpegfilecomment'             => 'جے پی ای جی شائل کومنٹ',
'exif-keywords'                    => 'خاص شبد',
'exif-worldregioncreated'          => 'دنیا دی تھاں جتھے اے مورت لئی گئی',
'exif-countrycreated'              => 'دیس جتھے اے مورت بنائی کئی',
'exif-countrycodecreated'          => 'دیس دا کوڈ جتھے اے مورت بنائی کئی',
'exif-provinceorstatecreated'      => 'صوبہ جتھے اے مورت بنائی گئی',
'exif-citycreated'                 => 'شہر جتھے اے مورت بنائی کئی',
'exif-sublocationcreated'          => 'شہر دی اوہ تھاں جتھے اے مورت بنائی کئی',
'exif-worldregiondest'             => 'دنیا دے تھاں',
'exif-countrydest'                 => 'دیس',
'exif-countrycodedest'             => 'دیس دا کوڈ دتا گیا اے',
'exif-provinceorstatedest'         => 'صوبہ دسیا گیا اے',
'exif-citydest'                    => 'شہر دےیا گیا اے۔',
'exif-sublocationdest'             => 'شہر دی تھاں دسی گئی اے۔',
'exif-objectname'                  => 'نکی سرخی',
'exif-specialinstructions'         => 'خاص دساں',
'exif-headline'                    => 'سرخی',
'exif-credit'                      => 'کریڈٹ/دین والا',
'exif-source'                      => 'سورس',
'exif-editstatus'                  => 'مورت دا ایڈیٹوریل سٹیٹس',
'exif-urgency'                     => 'جلدی',
'exif-fixtureidentifier'           => 'فکسچر ناں',
'exif-locationdest'                => 'تھاں بارے',
'exif-locationdestcode'            => 'تھاں کوڈ دتا گیا اے',
'exif-objectcycle'                 => 'دن دا ویلہ جس لئی اے میڈیا بنایا گیا اے',
'exif-contact'                     => 'پتہ',
'exif-writer'                      => 'لکھاری',
'exif-languagecode'                => 'بولی',
'exif-iimversion'                  => 'آئی آئی ایم ورین',
'exif-iimcategory'                 => 'گٹھ',
'exif-iimsupplementalcategory'     => 'ہور گٹھاں',
'exif-datetimeexpires'             => 'ایس دے مگروں ناں ورتو',
'exif-datetimereleased'            => 'بنی',
'exif-originaltransmissionref'     => 'اصل ٹرن والی تھاں دا کوڈ',
'exif-identifier'                  => 'لبن والا',
'exif-lens'                        => 'لینز ورتے گۓ',
'exif-serialnumber'                => 'کیمرہ نمبر',
'exif-cameraownername'             => 'کیمرے دا مالک',
'exif-label'                       => 'لیبل',
'exif-datetimemetadata'            => 'تریخ جدون میٹاڈیٹا بدلے گۓ۔',
'exif-nickname'                    => 'مورت دا انفورمل ناں',
'exif-rating'                      => 'سعاب (5 چوں)',
'exif-rightscertificate'           => 'حق دے سعاب کتاب دا سرٹیفیکیٹ',
'exif-copyrighted'                 => 'کاپی رائٹ سٹیٹس',
'exif-copyrightowner'              => 'کاپی رائٹ مالک',
'exif-usageterms'                  => 'ورتن شرطاں',
'exif-webstatement'                => 'اونلائن کاپی رائٹ لکھت',
'exif-originaldocumentid'          => 'اصل کاغذ دی خاص نشانی',
'exif-licenseurl'                  => 'کاپی رائٹ لاغسنس لئی یوآرایل',
'exif-morepermissionsurl'          => 'لائسنس دی ہور جانکاری',
'exif-attributionurl'              => 'جدون دوبارہ ورتو تے جوڑ دیو',
'exif-preferredattributionname'    => 'جدوں دوبارہ ورتو تے بنان والے دا ناں وی دسو',
'exif-pngfilecomment'              => 'پی این جی فائل کومنٹ',
'exif-disclaimer'                  => 'منکرنا',
'exif-contentwarning'              => 'لکھت توں خبردار',
'exif-giffilecomment'              => 'جی آئی ایف شائل کومنٹ',
'exif-intellectualgenre'           => 'آئیٹم ٹائپ',
'exif-subjectnewscode'             => 'سبجیکٹ کوڈ',
'exif-scenecode'                   => 'آئی پی ٹی سی سین کوڈ',
'exif-event'                       => 'ہوند دسی گئی۔',
'exif-organisationinimage'         => 'آرگنائزیشن دسی گئی',
'exif-personinimage'               => 'بندہ دسیا گیا',
'exif-originalimageheight'         => 'مورت دی اچائی کٹن توں پہلے',
'exif-originalimagewidth'          => 'مورت دی چوڑائی کٹن توں پہلے',

# EXIF attributes
'exif-compression-1' => 'کھولی گئی',
'exif-compression-2' => 'سی سی آئی ٹی ٹی گروپ 3 1-ڈائمینشنل موڈیفائیڈ ہفمین رن فل لنتھ انکوڈنگ',
'exif-compression-3' => 'سی سی آئی ٹی ٹی گروپ 3 فیکس اینکوڈنگ',
'exif-compression-4' => 'سی سی آئی ٹی ٹی گروپ 4 فیکس اینکوڈنگ',

'exif-copyrighted-true'  => 'حق بچاۓ',
'exif-copyrighted-false' => 'لوکاں کول',

'exif-unknowndate' => 'انجان تاریخ',

'exif-orientation-1' => 'عام',
'exif-orientation-2' => 'ہوریزنٹلی کرو',
'exif-orientation-3' => 'موڑیا گیا 180°',
'exif-orientation-4' => 'ورٹیکلی موڑو',
'exif-orientation-5' => '90° CCW موڑیا گیا تے تے ورٹیکلی کیتا گیا۔',
'exif-orientation-6' => '90° CCW موڑیا گیا',
'exif-orientation-7' => '90° CW تے فیر ورٹیکلی موڑیا گیا۔',
'exif-orientation-8' => '90° CW موڑیا گیا',

'exif-planarconfiguration-1' => 'چنکی فارمیٹ',
'exif-planarconfiguration-2' => 'پلانر فارمیٹ',

'exif-colorspace-65535' => 'ناں ناپیا گیا',

'exif-componentsconfiguration-0' => 'ہے نئیں',

'exif-exposureprogram-0' => 'بیان نئیں کیتا گیا',
'exif-exposureprogram-1' => 'طریقہ',
'exif-exposureprogram-2' => 'عام پروگرام',
'exif-exposureprogram-3' => 'اپرچر پراورٹی',
'exif-exposureprogram-4' => 'شٹر پراورٹی',
'exif-exposureprogram-5' => 'کریٹو پروگرام (فیلڈ ڈونگائی ول مڑیا)',
'exif-exposureprogram-6' => 'ایکشن پروگرام (تیز شٹر سپیڈ ول مڑیا)',
'exif-exposureprogram-7' => 'پورٹریٹ موڈ (نیڑے دیاں فوٹوواں لئی جناں دا پچھا فوکس توں باہر اے)',
'exif-exposureprogram-8' => 'لینڈسکیپ موڈ (لینڈسکیپ مورتاں لئی جناں دا پچھا فوکس چ اے)',

'exif-subjectdistance-value' => '$1 میٹر',

'exif-meteringmode-0'   => 'انجان',
'exif-meteringmode-1'   => 'اوسط',
'exif-meteringmode-2'   => 'سنٹر ویٹڈ ایورج',
'exif-meteringmode-3'   => 'جگہ',
'exif-meteringmode-4'   => 'ملٹی-سپاٹ',
'exif-meteringmode-5'   => 'نمونے',
'exif-meteringmode-6'   => 'کج حصہ',
'exif-meteringmode-255' => 'دوجے',

'exif-lightsource-0'   => 'انجان',
'exif-lightsource-1'   => 'دن دا چانن',
'exif-lightsource-2'   => 'فلورسنٹ',
'exif-lightsource-3'   => 'ٹنگسٹن (انکینڈسنٹ چانن)',
'exif-lightsource-4'   => 'فلیش',
'exif-lightsource-9'   => 'چنگا موسم',
'exif-lightsource-10'  => 'بدل آلا موسم',
'exif-lightsource-11'  => 'سایہ',
'exif-lightsource-12'  => 'در چانن فلورسنٹ (D 5700 – 7100K)',
'exif-lightsource-13'  => 'دن دا چانن فلورسنٹ (N 4600 – 5400K)',
'exif-lightsource-14'  => 'ٹھنڈی چٹی فلورسنٹ',
'exif-lightsource-15'  => 'چٹی فلورسنٹ (WW 3200 – 3700K)',
'exif-lightsource-17'  => 'سٹینڈرڈ چانن اے',
'exif-lightsource-18'  => 'سٹینڈرڈ چانن بی',
'exif-lightsource-19'  => 'سٹینڈرڈ چانن سی',
'exif-lightsource-24'  => 'ISO  سٹوڈیو ٹنگسٹن',
'exif-lightsource-255' => 'روشنی دے ہور ذریعے',

# Flash modes
'exif-flash-fired-0'    => 'فلیش نئیں چلی',
'exif-flash-fired-1'    => 'فلیش چلی',
'exif-flash-return-0'   => 'نو سٹروب ریٹرن ڈیٹیکشن فنکشن',
'exif-flash-return-2'   => 'سٹروب ریٹرن  چانن ناں دسیا',
'exif-flash-return-3'   => 'سٹروب ریٹرن چانن دسیا',
'exif-flash-mode-1'     => 'لازمی فلیش فائرنگ',
'exif-flash-mode-2'     => 'لازمی فلیش سپریشن',
'exif-flash-mode-3'     => 'آٹو موڈ',
'exif-flash-function-1' => 'نو فلیش فنکشن',
'exif-flash-redeye-1'   => 'لال اکھ مکاؤ موڈ',

'exif-focalplaneresolutionunit-2' => 'انچ',

'exif-sensingmethod-1' => 'غیر واضح',
'exif-sensingmethod-2' => 'اک-چپ کلر ایریا سنسر',
'exif-sensingmethod-3' => 'دو-چپ رنگ ایریا سنسر',
'exif-sensingmethod-4' => 'تن-چپ کلر ایریا سنسر',
'exif-sensingmethod-5' => 'کلر سیکونشل ایریا سنسر',
'exif-sensingmethod-7' => 'ٹریلینیر سنسر',
'exif-sensingmethod-8' => 'کلر سیکونشل لینیر سنسر',

'exif-filesource-3' => 'ڈجیٹل سٹل کیمرا',

'exif-scenetype-1' => 'اک سدی کھچی مورت',

'exif-customrendered-0' => 'عام طریقہ',
'exif-customrendered-1' => 'اپنی مرضی دا طریقہ',

'exif-exposuremode-0' => 'آٹو ایکسپویر',
'exif-exposuremode-1' => 'مینول ایکسپویر',
'exif-exposuremode-2' => 'آٹو بریکٹ',

'exif-whitebalance-0' => 'آپ چٹا ٹھیک کرے',
'exif-whitebalance-1' => 'ہتھ نال چٹا بیلنس',

'exif-scenecapturetype-0' => 'معیاری',
'exif-scenecapturetype-1' => 'لینڈسکیپ',
'exif-scenecapturetype-2' => 'پورٹریٹ',
'exif-scenecapturetype-3' => 'رات دا منظر',

'exif-gaincontrol-0' => 'کوئی نئیں',
'exif-gaincontrol-1' => 'لو گین اپ',
'exif-gaincontrol-2' => 'ہائی گین اپ',
'exif-gaincontrol-3' => 'لو گین ڈاؤن',
'exif-gaincontrol-4' => 'ہائی گین ڈاؤن',

'exif-contrast-0' => 'عام',
'exif-contrast-1' => 'نرم',
'exif-contrast-2' => 'سخت',

'exif-saturation-0' => 'عام',
'exif-saturation-1' => 'لو سیچوریشن',
'exif-saturation-2' => 'ہائی سیچوریشن',

'exif-sharpness-0' => 'عام',
'exif-sharpness-1' => 'نرم',
'exif-sharpness-2' => 'سخت',

'exif-subjectdistancerange-0' => 'انجان',
'exif-subjectdistancerange-1' => 'ماکرو',
'exif-subjectdistancerange-2' => 'نیڑے دا منظر',
'exif-subjectdistancerange-3' => 'دور دا منظر',

# Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => 'اتر لیٹیچیوڈ',
'exif-gpslatitude-s' => 'دکھن لیٹیچیوڈ',

# Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => 'چڑھدا لونگیچیوڈ',
'exif-gpslongitude-w' => 'لیندا لونگیچیوڈ',

# Pseudotags used for GPSAltitudeRef
'exif-gpsaltitude-above-sealevel' => '$1 {{انیک:$1|میٹر}} سمندر پدھر',
'exif-gpsaltitude-below-sealevel' => '$1 {{انیک:$1|میٹر}} پدھر توں تھلے',

'exif-gpsstatus-a' => 'ناپیا جاریا',
'exif-gpsstatus-v' => 'ناپ انٹراوپریٹبلٹی',

'exif-gpsmeasuremode-2' => 'دو پاسیاں دا ناپ',
'exif-gpsmeasuremode-3' => 'تن پاسیاں دا ناپ',

# Pseudotags used for GPSSpeedRef
'exif-gpsspeed-k' => 'کلومیٹر فی کینٹہ',
'exif-gpsspeed-m' => 'میل فی کینٹہ',
'exif-gpsspeed-n' => 'ناٹ',

# Pseudotags used for GPSDestDistanceRef
'exif-gpsdestdistance-k' => 'کلومیٹر',
'exif-gpsdestdistance-m' => 'میل',
'exif-gpsdestdistance-n' => 'سمندری میل',

'exif-gpsdop-excellent' => 'شاندار ($1)',
'exif-gpsdop-good'      => 'اچھا ($1)',
'exif-gpsdop-moderate'  => 'درمیانہ ($1)',
'exif-gpsdop-fair'      => 'سوہنا ($1)',
'exif-gpsdop-poor'      => 'ماڑا ($1)',

'exif-objectcycle-a' => 'صرف سویرے',
'exif-objectcycle-p' => 'صرف شام',
'exif-objectcycle-b' => 'صرف شام تے سویرے',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'سدھا راہ',
'exif-gpsdirection-m' => 'مقناطیسی راہ',

'exif-ycbcrpositioning-1' => 'وشکار',
'exif-ycbcrpositioning-2' => 'رلیاں تھانواں',

'exif-dc-contributor' => 'حصےدار',
'exif-dc-coverage'    => 'سپیٹیل یا ٹمپورل سکوپ آف میڈیا',
'exif-dc-date'        => 'تریخ',
'exif-dc-publisher'   => 'چھاپن والا',
'exif-dc-relation'    => 'رلدا میڈیا',
'exif-dc-rights'      => 'حق',
'exif-dc-source'      => 'سورس میڈیا',
'exif-dc-type'        => 'میڈیا منڈ',

'exif-rating-rejected' => 'چھڈیا',

'exif-isospeedratings-overflow' => '65535 نالوں وڈا',

'exif-iimcategory-ace' => 'آرٹس، رہتل تے مزے',
'exif-iimcategory-clj' => 'جرم تے قنون',
'exif-iimcategory-dis' => 'تباہی تے حادسے',
'exif-iimcategory-fin' => 'کم کاج تے کاروبار',
'exif-iimcategory-edu' => 'پرھائی',
'exif-iimcategory-evn' => 'محول',
'exif-iimcategory-hth' => 'صحت',
'exif-iimcategory-hum' => 'انسانی شوق',
'exif-iimcategory-lab' => 'مزدور',
'exif-iimcategory-lif' => 'جیون تے ارام',
'exif-iimcategory-pol' => 'سیاست',
'exif-iimcategory-rel' => 'موہب تے یقین',
'exif-iimcategory-sci' => 'سائینس تے ٹیکنالوجی',
'exif-iimcategory-soi' => 'سماجی اشو',
'exif-iimcategory-spo' => 'کھیڈاں',
'exif-iimcategory-war' => 'لڑائی چگڑے تے افراتفری',
'exif-iimcategory-wea' => 'موسم',

'exif-urgency-normal' => 'نارمل ($1)',
'exif-urgency-low'    => 'تھلے کرکے ($1)',
'exif-urgency-high'   => 'اچا ($1)',
'exif-urgency-other'  => '($1)  ورتن ڈیفائینڈ پراورٹی',

# External editor support
'edit-externally'      => 'بارلا سافٹ ویئر استعال کردے ہوۓ اے فائل لکھو',
'edit-externally-help' => 'زیادہ معلومات آسطے اے [http://www.mediawiki.org/wiki/Manual:External_editors] ویکھو۔',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'سارے',
'namespacesall' => 'سارے',
'monthsall'     => 'سارے',
'limitall'      => 'سارے',

# E-mail address confirmation
'confirmemail'              => 'ای میل پتہ پکا کرو',
'confirmemail_noemail'      => 'تواڈے کول اک پکا ای-میل پتہ نئیں اے جیہڑا [[خاص:تانگاں|ورتن تانگاں]]',
'confirmemail_text'         => '{{سائیٹناں}}  دی ایہ لوڑ اے جے تسیں اپنا ای-میل پتہ پکا کرواؤ ای-میل فیچر ورتن توں پہلے۔ تھلے دتے گۓ بٹن تے پکی کرن چٹھی پیجو اپنے پتے تے منگوان لئی کلک کرو۔
چٹھی چ اک جوڑ ہووے گا کوڈ نال؛
اپنے براؤزر چ جوڑ لکھو اپنے ای-میل پتے نوں پکا کرن لئی۔',
'confirmemail_pending'      => 'اک پکا کرن والا کوڈ  توانوں پہلے ای پیجیا جاچکیا اے؛ اگر تساں پہلے ای اپنا کھاتہ بنایا اے توانوں تھوڑا چر ویکھنا چائیدا اودے پونچن لئی نواں کوڈ منگن توں پہلاں۔',
'confirmemail_send'         => 'کنفرمیشن کوڈ پیجو',
'confirmemail_sent'         => 'کنفرمیشن ای-میل پیج دتی گئی۔',
'confirmemail_oncreate'     => 'اک کنفرمیشن کوڈ تواڈے ای-میل پتے تے پیج دتی گئی اے۔
ایس کوڈ دی لاگ ان ہون لی  لوڑ نئیں، پر تھوانوں ایدی لوڑ اے دین دی وکی چ کسے وی ای-میل فیچر نوں قابل کرن لئی۔',
'confirmemail_sendfailed'   => '{{SITENAME}} توں تساں دی کنفرم ہون دی ای-میل نئیں آئی۔
مہربانی کرکے اپنا ای-میل پتہ چیک کرو اکریاں دی غلطی لئی۔

میلر واپس: $1',
'confirmemail_invalid'      => 'ناں منیا جان والا کنفرمیشن کوڈ۔
کوڈ لگدا اے ایکسپائر ہوچکیا اے۔',
'confirmemail_needlogin'    => 'تھوانوں $1 دی لوڑ اے اپنا ای-میل کنفرم کرن لئی۔',
'confirmemail_success'      => 'تھواڈا ای-میل پتہ پکا ہوچکیا اے۔
تسی ہن [[خاص:ورتنلاگان|لاگان]] ہوسکدے اے تے وکی دے مزے کن سکدے او۔',
'confirmemail_loggedin'     => 'تھواڈا ای-میل پتہ ہن پکا ہوچکیا اے۔',
'confirmemail_error'        => 'تھواڈی کنفرمیشن نوں بچاندیاں ہویاں کوئی چیز غلط ہوگئی اے۔',
'confirmemail_subject'      => '{{SITENAME}} ای-میل پتہ کنفرمیشن',
'confirmemail_body'         => 'کسے نیں خبرے تساں ای آئی پی پتے $1 توں،
اک کھاتہ  "$2" ایس ای میل پتے نال  {{SITENAME}}   تے بنایا اے۔

اے گل پکا کرن لئی جے ایہ اکاؤنٹ تھواڈا ای اے تے ای-میل دے فیدے {{SITENAME}} تے ٹورن لئی اپنے براؤزر چ اے لنک کھولو:

$3

اگر تسی کھاتہ رجسٹر نئیں نئیں کیتا، تے ایس لنگ تے اؤ ای-میل پتے دی کنفرمیشن نوں واپس کرن لئی:

$5

ایس کنفرمیشن کوڈ دی تریخ $4 نوں مک جائیگی۔',
'confirmemail_body_changed' => 'کسے نیں، خورے تساں، آئی پی پتے $1 توں 
کھاتہ \'$" دا ای-میل پتہ بدل دتا اے {{سائٹ تھاں }} تے۔

اے گل پکی کرن لی جے ایہ کھاتہ تواڈا اے تے اینوں {{سائٹ تھاں }} تے ای-میل فیچرز دوبارہ چلان لئی، اپنے برآؤزر چ  اے جوڑ کھولو:

$3

اگر کھاتہ تواڈا نئیں، ایس جوڑ تے اپڑو ای-میل پتہ دی کنفرمیشن نوں مکان لئی

$5

ایہ کنفرمیشن کوڈ $4 نوں مک جاؤگا۔',
'confirmemail_body_set'     => 'کسے نیں، خورے تساں، آئی پی پتے $1 توں 
کھاتہ \'$" دا ای-میل پتہ بدل دتا اے {{سائٹ تھاں }} تے۔

اے گل پکی کرن لی جے ایہ کھاتہ تواڈا اے تے اینوں {{سائٹ تھاں }} تے ای-میل فیچرز دوبارہ چلان لئی، اپنے برآؤزر چ  اے جوڑ کھولو:

$3

اگر کھاتہ تواڈا نئیں، ایس جوڑ تے اپڑو ای-میل پتہ دی کنفرمیشن نوں مکان لئی

$5

ایہ کنفرمیشن کوڈ $4 نوں مک جاؤگا۔',
'confirmemail_invalidated'  => 'ای-میل پکا کرنا واپس',
'invalidateemail'           => 'ای-میل پکا کرنا واپس کرو',

# Scary transclusion
'scarytranscludedisabled' => 'انٹروکی رلانا روک دتا گیا۔',
'scarytranscludefailed'   => '[ٹمپلیٹ $1 لئی لے کے آنا ناکام]',
'scarytranscludetoolong'  => '[URL چوکھی لمبی اے]',

# Trackbacks
'trackbackbox'      => 'ایس صفے لئی پچھے:<br />
$1',
'trackbackremove'   => '([$1 مٹاؤ])',
'trackbacklink'     => 'پچھلا راہ لبنا',
'trackbackdeleteok' => 'پچھلا راہ مٹا دتا گیا',

# Delete conflict
'deletedwhileediting'      => "'''خبردار''': تھواڈے لکھن مکرون اے صفہ مٹا دتا گیا!",
'confirmrecreate'          => "ورتن والا [[ورتن والا:$1|$1]]([[ورتن گل بات:$1|گل بات]]) ایہ صفہ مٹادتا اے جدوں تساں وجہ دس کے تبدیل کرن شروع کیتا:
: ''$2''
مہربانی کرکے کنفرم کرو جے تسی اے صفہ واقعی بنانا چاندے او۔",
'confirmrecreate-noreason' => 'ورتن والا [[ورتن والا:$1|$1]] ([[ورتن گل بات:$1|گل بات]]) نے تواڈے تبدیلی کرن مگروں اے صفہ مٹا دتا اے۔
مہربانی کرکے اے گل پکی کرو جے تسی واقعی اے صفہ بنانا چاندے او۔',
'recreate'                 => 'دوبارہ بناؤ',

# action=purge
'confirm_purge_button' => 'ٹھیکھ ہے',
'confirm-purge-top'    => 'ایس صفے دا کاشے بدلو ؟',
'confirm-purge-bottom' => 'اک صفہ صاف کرن نال نویاں تبدیلیاں دس پین گیاں۔',

# action=watch/unwatch
'confirm-watch-button'   => 'اوکے',
'confirm-watch-top'      => 'اپنے اکھ تھلے رکھے صفیاں چ ایس صفے نوں رلاؤ۔',
'confirm-unwatch-button' => 'اوکے',
'confirm-unwatch-top'    => 'ایس صفے نوں اپنے اکھ تھلے رکھے صفیاں چوں ہٹاؤ۔',

# Multipage image navigation
'imgmultipageprev' => '← پچھلا صفحہ',
'imgmultipagenext' => 'اگلا صفحہ →',
'imgmultigo'       => 'جاؤ!',
'imgmultigoto'     => '$1 تے جاؤ',

# Table pager
'ascending_abbrev'         => 'اے ایس سی',
'descending_abbrev'        => 'ڈی ایایس سی',
'table_pager_next'         => 'اگلا صفحہ',
'table_pager_prev'         => 'پچھلا صفحہ',
'table_pager_first'        => 'پہلا صفہ',
'table_pager_last'         => 'آخری صفہ',
'table_pager_limit'        => '$1 وکھاؤ ہر صفے تے',
'table_pager_limit_label'  => 'آئیٹم صفے تے:',
'table_pager_limit_submit' => 'چلو',
'table_pager_empty'        => 'کوئی نتارہ نئیں',

# Auto-summaries
'autosumm-blank'   => 'ایس صفے نوں خالی کرو',
'autosumm-replace' => '"$1" نال مواد بدلو',
'autoredircomment' => 'صفے نوں [[$1]] ول ریڈائرکٹ کرو',
'autosumm-new'     => '"$1" نال صفہ بنایا گیا۔',

# Live preview
'livepreview-loading' => 'لوڈنگ',
'livepreview-ready'   => 'لوڈنگ۔۔۔۔۔۔تیار!',
'livepreview-failed'  => 'لائیو وکھالہ ناکام!
نارمل وکھالے دی کوشش کرو۔',
'livepreview-error'   => 'جوڑن چ ناکام: $1 "$2"
نارمل وکھالہ کوشش کرو۔',

# Friendlier slave lag warnings
'lag-warn-normal' => '$1 توں نویاں تبدیلیاں {{PLURAL:$1|سکنٹ}}',
'lag-warn-high'   => 'تیز ڈیٹاسرور لاگ ، $1 توں نویاں تبدیلیاں {{انیک:$1|سکنٹ|سکنٹ}} ہوسکدا اے ایس لسٹ ناں دسے جان۔',

# Watchlist editor
'watchlistedit-numitems'       => 'تھواڈے اکھ تھلے رکھے صفیاں گل بات والے صفے کڈکے {{PLURAL:$1|1 سرخی|$1 سرخی}} نیں۔',
'watchlistedit-noitems'        => 'تھواڈی اکھ تھلے رکھے صفیاں دی لسٹ خالی اے۔',
'watchlistedit-normal-title'   => ' اکھ تھلے رکھی ہوئی نو تبدیل کرو',
'watchlistedit-normal-legend'  => 'اکھ تھلیوں ہٹا لو',
'watchlistedit-normal-explain' => 'تواڈی اکھ تھلے رکھی لسٹ دے سرناویں تھلے دتے گۓ نیں۔
اک سرناویں نوں ہٹان لئی، اوس توں اگلے ڈبے نوں ویکھو تے 
"{{int:Watchlistedit-normal-submit}}" تے کلک کرو۔
تسیں [[خاص:ایڈٹواچلسٹ/کچی|کچی لسٹ تبدیل کرو]]',
'watchlistedit-normal-submit'  => 'ٹائیٹلز ہٹاؤ',
'watchlistedit-normal-done'    => '{انیک:$1|1 سرناواں سی|$1 سرناویں سن}}',
'watchlistedit-raw-title'      => 'کچی اکھ تھلے رکھی ہوئی نو تبدیل کرو',
'watchlistedit-raw-legend'     => 'کچی اکھ تھلے رکھی ہوئی نو تبدیل کرو',
'watchlistedit-raw-explain'    => 'سرناویں تواڈی اک تھلے رکھی لسٹ دے تھلے دتے نیں، تے ایناں چ وادا کعاٹا کرکے تبدیلی کیتی جاسکدی اے؛
اک سرناواں اک لین چوں۔
جدوں مک جاۓ تے "{{int:Watchlistedit-raw-submit}}" تے کلک کرو۔
تسیں [[خاص:ایڈٹواچلسٹ|سٹینڈرڈ ایڈیٹر نوں چنو]]',
'watchlistedit-raw-titles'     => 'ناں:',
'watchlistedit-raw-submit'     => ' اکھ تھلے رکھی ہوئی نو تبدیل کرو',
'watchlistedit-raw-done'       => 'تھواڈی اکھ تھلے رکھی لسٹ نویں کر دتی گئی اے۔',
'watchlistedit-raw-added'      => '{{انیک:$1|1 سرناواں|$1 سرناویں}} جوڑیا گیا:',
'watchlistedit-raw-removed'    => '{{انیک:$1|1 سرناواں|$1 سرناویں}} سرناواں:',

# Watchlist editing tools
'watchlisttools-view' => 'ملدیاں ہوئیاں تبدیلیاں ویکھو',
'watchlisttools-edit' => 'اکھ تھلے رکھے ہوۓ صفحیاں نوں ویکھو تے تبدیل کرو',
'watchlisttools-raw'  => 'کچی اکھ تھلے رکھی ہوئی نو تبدیل کرو',

# Signatures
'signature' => '[[{{ns:user}}:$1|$2]] ([[{{ns:ورتن گل بات}}:$1|گل بات]])',

# Core parser functions
'unknown_extension_tag' => 'انجان ایکسٹنشن ٹیگ "$1"',
'duplicate-defaultsort' => '\'\'\'خبردار:\'\'\' ڈیفالٹ چابی "$2" پہلی ڈیفالٹ چابی "$1" دے اتے لگ گئی اے۔',

# Special:Version
'version'                       => 'ورژن',
'version-extensions'            => 'انسٹالڈ کیتیاں گیاں ایکسٹنشن',
'version-specialpages'          => 'خاص صفحے',
'version-parserhooks'           => 'پارسر ہکز',
'version-variables'             => 'ویریایبلز',
'version-antispam'              => 'سپام بچاؤ',
'version-skins'                 => 'کھل',
'version-other'                 => 'دوجے',
'version-mediahandlers'         => 'میڈیا ہینڈلرز',
'version-hooks'                 => 'ہکز',
'version-extension-functions'   => 'ایکسٹنشن فنکشن',
'version-parser-extensiontags'  => 'پاسر ایکسٹنشن ٹیگز',
'version-parser-function-hooks' => 'پاسر فنکشن ہکز',
'version-hook-name'             => 'ہک ناں',
'version-hook-subscribedby'     => 'جینے لئی',
'version-version'               => '(ورین $1)',
'version-license'               => 'لائیسنس',
'version-poweredby-credits'     => "ایس وکی نوں '''[http://www.mediawiki.org/ میڈیاوکی]''', copyright © 2001-$1 $2. چلاندا اے۔",
'version-poweredby-others'      => 'دوجے',
'version-license-info'          => 'میڈیاوکی اک مفت سوفٹویر اے؛ تسیں اینوں ونڈ سکدے اوہ تے گنو جنرل پبلک لسنس دیاں شرطاں تے جیہڑیاں فری سوفٹویر فاؤنڈیشن نے چھاپیاں نیں ایدے چ تبدیلی کرسکدے اوہ لسنس دے ورین 2 نال، یا اپنی مرضی نال کسے وی ہور ورین فیر بنن والے ورین نوں۔

میڈیاوکی ایس آس نال ونڈیا گیا اے جے ایہ فیدا دیوے گا پر ایدی کوئی وارنٹی نئیں ؛ کسے خاص کم لئی ٹھیک ہون دی وارنٹی توں وی بنا۔ گنو جنرل پبلک لسنس ویکھو ہور گلاں لئی۔

تسیں ایس پروکرام نال لے چکے اوہ [{{سرور}}{{سکرپٹراہ}}/جنرل پبلک لسنس دی کاپی] ایس کم نال ؛ اگر نئیں تے  چٹھی لکھو 
the Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA or [http://www.gnu.org/licenses/old-licenses/gpl-2.0.html read it online]',
'version-software'              => 'سافٹوئر چڑھ گیا۔',
'version-software-product'      => 'پراڈکٹ',
'version-software-version'      => 'ورژن',

# Special:FilePath
'filepath'         => 'فائل راہ',
'filepath-page'    => 'فائل:',
'filepath-submit'  => 'چلو',
'filepath-summary' => 'اے خاص صفہاک فائل دا پورا راہ دسدا اے۔
مورتاں پورے ریزولوشن چ دسیاں گیاں نئیں، دوجیاں فائل ٹائپ نوں نوں اوناں دے رلدے پروکراماں نال ٹوریا گیا اے۔',

# Special:FileDuplicateSearch
'fileduplicatesearch'           => 'دوہری فائلاں دی کھوج کرو',
'fileduplicatesearch-summary'   => 'دوہریاں فائلاں دی کھوج ہیش ویلیو تے اے۔',
'fileduplicatesearch-legend'    => 'دوہری  دی کھوج کرو۔',
'fileduplicatesearch-filename'  => 'فائل دا ناں',
'fileduplicatesearch-submit'    => 'کھوج',
'fileduplicatesearch-info'      => '$1 × $2 پکسل<br />فائل ناپ: $3<br />مائم ٹائپ: $4',
'fileduplicatesearch-result-1'  => '"$1" فائل ورگی رلدی فائل کوئی نیں۔',
'fileduplicatesearch-result-n'  => "فائل ''1$'' چ {{انیک:$2|1 رلدی نقل|$2 رلدیاں نقلں}} نیں۔",
'fileduplicatesearch-noresults' => '"$1" ناں دی کوئی فائل نئیں لبی۔',

# Special:SpecialPages
'specialpages'                   => 'خاص صفحے',
'specialpages-note'              => '----
* نارمل خاص صفے.
* <span class="mw-specialpagerestricted">روکے گۓ خاص صفے.</span>
* <span class="mw-specialpagecached">کاشے خاص صفے (پرانے ہوگۓ ہون).</span>',
'specialpages-group-maintenance' => 'مرمت رپورٹ',
'specialpages-group-other'       => 'ہور خاص صفے',
'specialpages-group-login'       => 'لاگان / کھاتہ کھولو',
'specialpages-group-changes'     => 'اج کل ہون والیاں تبدیلیاں تے لاگز',
'specialpages-group-media'       => 'میڈیا رپورٹس تے چڑھیاں فائلاں',
'specialpages-group-users'       => 'ورتن والے تے حق',
'specialpages-group-highuse'     => 'بعوت ورتے جان والے صفے',
'specialpages-group-pages'       => 'صفیاں دی لسٹ',
'specialpages-group-pagetools'   => 'صفہ اوزار',
'specialpages-group-wiki'        => 'وکی ڈیٹیا تے اوزار',
'specialpages-group-redirects'   => 'خاص صفیاں نوں ریڈائرکٹ کرنا',
'specialpages-group-spam'        => 'سپام روک اوزار',

# Special:BlankPage
'blankpage'              => 'خالی صفہ',
'intentionallyblankpage' => 'اے صفہ جان بج اے خالی رکھیا گیا اے۔',

# External image whitelist
'external_image_whitelist' => ' #ایس لین نوں اینج ای چھڈدیو جنج ایہ ہیگی اے<pre>
#تھلے ریگولر گلاں دسو ((صرف اوہ پاسہ جیہڑا ایناں دے وشکار اے //) //) 
#بارلیاں مورتاں دے یو آر ایل نال ایناں نوں رلایا جایگا
#اوہ جیہڑے رلدے نیں  اوناں نوں مورتاں لئی دسیا جائیگا نئیں تے اک جوڑ مورت نال دتا جائیگا۔
#ایس توں ٹرن والیاں لیناں  # کومنٹ سمجیاں جان گیاں۔
#ایہ کیس سینسیٹو ایہ۔

#لین توں اتے ریجیکس ٹوٹے رکھو. ایہ جنج ہے ایس لین نوں اینج ای رہن دیر۔</pre>',

# Special:Tags
'tags'                    => 'منے ہوۓ تبدیلی دے ٹیگ',
'tag-filter'              => '[[خاص:ٹیگ]] نتارا:',
'tag-filter-submit'       => 'فلٹر',
'tags-title'              => 'ٹیگز',
'tags-intro'              => 'ایس صفے تے ٹیگ دی لسٹ اے جینوں سوفٹوئیر تبدیلی دا نشان لا سکدا اے۔',
'tags-tag'                => 'ٹیگ ناں',
'tags-display-header'     => 'بدلی ہوئی لسٹ چ وکھالہ',
'tags-description-header' => 'شبداں دی پوری جانکاری',
'tags-hitcount-header'    => 'تبدیلیاں ٹیگ',
'tags-edit'               => 'تبدیل',
'tags-hitcount'           => '$1 {{PLURAL:$1|change|تبدیلیاں}}',

# Special:ComparePages
'comparepages'                => 'صفے سامنے کرو',
'compare-selector'            => 'صفیاں تے دوبارہ ویکھ دیکھو',
'compare-page1'               => 'صفہ 1',
'compare-page2'               => 'صفہ 2',
'compare-rev1'                => 'دوبارہ وکھالہ 1',
'compare-rev2'                => 'دوبارہ وکھالہ 2',
'compare-submit'              => 'امنے سامنے کرو',
'compare-invalid-title'       => 'سرخی جیہڑی تساں چنی اے ایدی اجازت نئیں۔',
'compare-title-not-exists'    => 'ٹائیٹل جیہڑا تساں چنیاں اوہ ہے ای نئیں۔',
'compare-revision-not-exists' => 'دوبارہ وکھالہ جیہڑا تساں دسیا اے ہے ای نئیں۔',

# Database error messages
'dberr-header'      => 'ایس وکی چ کوئی مسلہ اے۔',
'dberr-problems'    => 'معاف کرنا !
ایس صفے تے تکنیکی مسلے آرۓ نیں۔',
'dberr-again'       => 'تھو ڑے منٹ انتظار کرو تے دوبارہ لوڈ کرو۔',
'dberr-info'        => '(ڈیٹابیس سرور نال میل نئیں ہوسکیا:$1)',
'dberr-usegoogle'   => 'تسیں گوکل راہیں کھوج کر سکدے او۔',
'dberr-outofdate'   => 'اے نوٹ کرو جے اوناں دے انڈیکس ساڈے مواد چوں پرانے ناں ہون۔',
'dberr-cachederror' => 'اے کاشے کاپی اے منگے ہوۓ صفے دی تے ہوسکدا اے پرانی ہووے۔',

# HTML forms
'htmlform-invalid-input'       => 'تھواڈے دتے گۓ مواد چ مسلے نیں۔',
'htmlform-select-badoption'    => 'جیہڑا نمبر دتا اے اوہ منی ہوئی چنوتی نئیں۔',
'htmlform-int-invalid'         => 'جیہڑا نمبر تساں دسیا اے اوہ انٹیجر نغیں۔',
'htmlform-float-invalid'       => 'جو تسیں دسیا اے اوہ نمبر نیں۔',
'htmlform-int-toolow'          => 'جو تساں دسیا اے اوہ کعٹ توں کعٹ  $1 توں وی تھلے اے۔',
'htmlform-int-toohigh'         => 'جو تساں دسیا اے اوہ $1 دے سب توں چوکھے نمبر توں وی اتے اے۔',
'htmlform-required'            => 'اے نمبر چائیدا اے۔',
'htmlform-submit'              => 'رکھو',
'htmlform-reset'               => 'تبدیلیاں واپس',
'htmlform-selectorother-other' => 'ہور',

# SQLite database support
'sqlite-has-fts' => '$1 پوری لکھت کھوج مدد نال',
'sqlite-no-fts'  => '$1 بنا کسے لکھت مدد دے',

# New logging system
'logentry-delete-delete'              => '$1 {{جنس:$2|مٹایا}} صفہ $3',
'logentry-delete-restore'             => '$1 {{جنس:$2|بچایا}} صفہ $3',
'logentry-delete-event'               => '$1 {{جنس:$2|پلٹے}}وکھالہ {{انیاک:$5|اک لاگ ایونٹ|$5 لاگ ایونٹس}} on $3: $4',
'logentry-delete-revision'            => '$1 {{جنس:$2|پلٹی}} وکھالہ {{انیک:$5|اک ریوین|$5 ریویناں}} صفے تے $3: $4',
'logentry-delete-event-legacy'        => '$1 {{جنس:$2|بدلی}} لاگ کماں دا وکھالہ $3 تے',
'logentry-delete-revision-legacy'     => '$1 {{جنس:$2|بدلی}} ریوین دا وکھالہ صفہ $3',
'logentry-suppress-delete'            => '$1 {{جنس:$2|دبایا}} صفہ $3',
'logentry-suppress-event'             => '$1 لکا کے {{جنس:$2|بدلی}} {{انیک:$5|اک لاگ کم|$5 لاگ کم}} دا وکھالہ $3 تے: $4',
'logentry-suppress-revision'          => '$1 لکا کے {{جنس:$2|بدلی}} {{انیک:$5|ریوین|$5 ریویناں}} دا وکھالہ $3 تے: $4',
'logentry-suppress-event-legacy'      => '$1 لکا کے{{جنس:$2|بدلیا}} لاگ کماں دا وکھالہ',
'logentry-suppress-revision-legacy'   => '$1 لکا کے {{جنس:$2|بدلی}} ریویناں دا وکھالہ صفہ $3 تے۔',
'revdelete-content-hid'               => 'مواد لکیا',
'revdelete-summary-hid'               => 'لکھت سمری لکی',
'revdelete-uname-hid'                 => 'ورتن والے دا ناں لکیا',
'revdelete-content-unhid'             => 'مواد ناں لکیا',
'revdelete-summary-unhid'             => 'لکھت سمری ناں لکی',
'revdelete-uname-unhid'               => 'ورتن والے دا ناں ںئیں لکیا',
'revdelete-restricted'                => 'مکھیاں تے روکاں لگیاں',
'revdelete-unrestricted'              => 'مکھیاں تے روکاں لتھیاں',
'logentry-move-move'                  => '$1 {{جنس:$2|پلٹی}} صفہ $3 توں $4',
'logentry-move-move-noredirect'       => '$1 {{جنس:$2|پلٹی}} صفہ $3 توں $4 اک ڑیڈائرکٹ چھڈے بنا',
'logentry-move-move_redir'            => '$1 {{جنس:$2|پلٹی}} صفہ $3 توں $4 ریڈائرکٹ',
'logentry-move-move_redir-noredirect' => '$1 {{جنس:$2|پلٹی}} صفہ $3 توں $4 اک ریڈائرکٹ دے بنا کسے ریڈائرکٹ دتیاں',
'logentry-patrol-patrol'              => '$1 {{جنس:$2|نشان لگی}} ریوین $4 صفہ $3 ویکھی گئی۔',
'logentry-patrol-patrol-auto'         => 'اپنے آپ $1 {{جنس:$2|نشان لگی}} $4 ریوین صفہ $3 دی ویکھی گئی',
'logentry-newusers-newusers'          => '$1 {{جنس:$2|بنایا گیا}} اک ورتن والا کھاتہ۔',
'logentry-newusers-create'            => '$1 {{جنس:$2|بنایا}} اک ورتن والا کھاتہ',
'logentry-newusers-create2'           => '$1 {{جنس:$2|بنایا}} {{جنس:$4|اک ورتن کھاتہ}} $3',
'logentry-newusers-autocreate'        => 'کھاتہ $1 اپنے آپ ای {{جنس:$2|بنایا گیا}} بنایا گیا۔',
'newuserlog-byemail'                  => 'کنجی ای-میل راہ پیج دتی گئی۔',

);
