<?php
/** Urdu (اردو)
 *
 * To improve a translation please visit https://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Chris H
 * @author Istabani
 * @author Meno25
 * @author Muhammad Shuaib
 * @author Noor2020
 * @author O.bangash
 * @author Rachitrali
 * @author Reedy
 * @author Tahir mq
 * @author Wamiq
 * @author Wisesabre
 * @author ZxxZxxZ
 * @author לערי ריינהארט
 * @author زكريا
 * @author سمرقندی
 * @author محبوب عالم
 * @author පසිඳු කාවින්ද
 */

$fallback8bitEncoding = 'windows-1256';
$rtl = true;

$namespaceNames = [
	NS_MEDIA            => 'میڈیا',
	NS_SPECIAL          => 'خاص',
	NS_TALK             => 'تبادلۂ_خیال',
	NS_USER             => 'صارف',
	NS_USER_TALK        => 'تبادلۂ_خیال_صارف',
	NS_PROJECT_TALK     => 'تبادلۂ_خیال_$1',
	NS_FILE             => 'فائل',
	NS_FILE_TALK        => 'تبادلۂ_خیال_فائل',
	NS_MEDIAWIKI        => 'میڈیاویکی',
	NS_MEDIAWIKI_TALK   => 'تبادلۂ_خیال_میڈیاویکی',
	NS_TEMPLATE         => 'سانچہ',
	NS_TEMPLATE_TALK    => 'تبادلۂ_خیال_سانچہ',
	NS_HELP             => 'معاونت',
	NS_HELP_TALK        => 'تبادلۂ_خیال_معاونت',
	NS_CATEGORY         => 'زمرہ',
	NS_CATEGORY_TALK    => 'تبادلۂ_خیال_زمرہ',
];

$namespaceAliases = [
	'وسیط'            => NS_MEDIA,
	'زریعہ'            => NS_MEDIA,
	'تصویر'            => NS_FILE,
	'تبادلۂ_خیال_تصویر'   => NS_FILE_TALK,
	'ملف'            => NS_FILE,
	'تبادلۂ_خیال_ملف'   => NS_FILE_TALK,
	'میڈیاوکی'          => NS_MEDIAWIKI,
	'تبادلۂ_خیال_میڈیاوکی' => NS_MEDIAWIKI_TALK,
];

$specialPageAliases = [
	'Activeusers'               => [ 'متحرک_صارفین' ],
	'Allmessages'               => [ 'تمام_پیغامات' ],
	'Allpages'                  => [ 'تمام_صفحات' ],
	'Ancientpages'              => [ 'قدیم_صفحات' ],
	'Badtitle'                  => [ 'خراب_عنوان' ],
	'Blankpage'                 => [ 'خالی_صفحہ' ],
	'Block'                     => [ 'پابندی', 'آئی_پی_پتہ_پابندی', 'پابندی_بر_صارف' ],
	'Booksources'               => [ 'کتابی_وسائل' ],
	'BrokenRedirects'           => [ 'شکستہ_رجوع_مکررات' ],
	'Categories'                => [ 'زمرہ_جات' ],
	'ChangeEmail'               => [ 'ڈاک_تبدیل' ],
	'ChangePassword'            => [ 'کلمہ_شناخت_تبدیل', 'تنظیم_کلمہ_شناخت' ],
	'ComparePages'              => [ 'موازنہ_صفحات' ],
	'Confirmemail'              => [ 'تصدیق_ڈاک' ],
	'Contributions'             => [ 'شراکتیں' ],
	'CreateAccount'             => [ 'تخلیق_کھاتہ' ],
	'Deadendpages'              => [ 'مردہ_صفحات' ],
	'DeletedContributions'      => [ 'حذف_شدہ_شراکتیں' ],
	'DoubleRedirects'           => [ 'دوہرے_رجوع_مکررات' ],
	'EditWatchlist'             => [ 'ترمیم_زیر_نظر' ],
	'Emailuser'                 => [ 'صارف_ڈاک' ],
	'Export'                    => [ 'برآمد', 'برآمدگی' ],
	'Fewestrevisions'           => [ 'کم_نظر_ثانی_شدہ' ],
	'FileDuplicateSearch'       => [ 'تلاش_دوہری_فائل', 'دہری_ملف_تلاش' ],
	'Filepath'                  => [ 'راہ_فائل', 'راہ_ملف' ],
	'Import'                    => [ 'درآمد', 'درآمدگی' ],
	'Invalidateemail'           => [ 'ڈاک_تصدیق_منسوخ' ],
	'JavaScriptTest'            => [ 'تجربہ_جاوا_اسکرپٹ' ],
	'BlockList'                 => [ 'فہرست_ممنوع', 'فہرست_دستور_شبکی_ممنوع' ],
	'LinkSearch'                => [ 'تلاش_روابط' ],
	'Listadmins'                => [ 'فہرست_منتظمین' ],
	'Listbots'                  => [ 'فہرست_روبہ_جات' ],
	'Listfiles'                 => [ 'فائلوں_کی_فہرست', 'فہرست_تصاویر' ],
	'Listgrouprights'           => [ 'فہرست_اختیارات_گروہ', 'صارفی_گروہ_اختیارات' ],
	'Listredirects'             => [ 'فہرست_رجوع_مکررات' ],
	'Listusers'                 => [ 'فہرست_صارفین' ],
	'Log'                       => [ 'نوشتہ', 'نوشتہ_جات' ],
	'Lonelypages'               => [ 'یتیم_صفحات' ],
	'Longpages'                 => [ 'طویل_صفحات' ],
	'MergeHistory'              => [ 'ضم_تاریخچہ' ],
	'Movepage'                  => [ 'منتقلی_صفحہ' ],
	'Mycontributions'           => [ 'میری_شراکتیں', 'میرا_حصہ' ],
	'Mypage'                    => [ 'میرا_صفحہ' ],
	'Mytalk'                    => [ 'میری_گفتگو' ],
	'Myuploads'                 => [ 'میرے_اپلوڈ', 'میرے_زبراثقالات' ],
	'Newimages'                 => [ 'جدید_فائلیں', 'جدید_املاف', 'جدید_تصاویر' ],
	'Newpages'                  => [ 'جدید_صفحات' ],
	'PermanentLink'             => [ 'مستقل_ربط' ],
	'Preferences'               => [ 'ترجیحات' ],
	'Prefixindex'               => [ 'اشاریہ_سابقہ' ],
	'Protectedpages'            => [ 'محفوظ_صفحات' ],
	'Protectedtitles'           => [ 'محفوظ_عناوین' ],
	'Randompage'                => [ 'تصادف', 'تصادفی_مقالہ' ],
	'Randomredirect'            => [ 'تصادفی_رجوع_مکرر' ],
	'Recentchanges'             => [ 'حالیہ_تبدیلیاں' ],
	'Recentchangeslinked'       => [ 'متعلقہ_تبدیلیاں' ],
	'Revisiondelete'            => [ 'حذف_نظر_ثانی', 'حذف_اعادہ' ],
	'Search'                    => [ 'تلاش' ],
	'Shortpages'                => [ 'مختصر_صفحات' ],
	'Specialpages'              => [ 'خصوصی_صفحات' ],
	'Statistics'                => [ 'شماریات' ],
	'Tags'                      => [ 'ٹیگ', 'ٹیگز' ],
	'Unblock'                   => [ 'پابندی_ختم' ],
	'Uncategorizedcategories'   => [ 'غیر_زمرہ_بند_زمرہ_جات' ],
	'Uncategorizedimages'       => [ 'غیر_زمرہ_بند_فائلیں', 'غیر_زمرہ_بند_املاف', 'غیر_زمرہ_بند_تصاویر' ],
	'Uncategorizedpages'        => [ 'غیر_زمرہ_بند_صفحات' ],
	'Uncategorizedtemplates'    => [ 'غیر_زمرہ_بند_سانچے' ],
	'Undelete'                  => [ 'بحال' ],
	'Unusedcategories'          => [ 'غیر_مستعمل_زمرہ_جات' ],
	'Unusedimages'              => [ 'غیر_مستعمل_فائلیں', 'غیر_مستعمل_املاف', 'غیر_مستعمل_تصاویر' ],
	'Unusedtemplates'           => [ 'غیر_مستعمل_سانچے' ],
	'Unwatchedpages'            => [ 'نادیدہ_صفحات' ],
	'Upload'                    => [ 'اپلوڈ', 'زبراثقال' ],
	'Userlogin'                 => [ 'داخل_نوشتگی' ],
	'Userlogout'                => [ 'خارج_نوشتگی' ],
	'Userrights'                => [ 'صارفی_اختیارات' ],
	'Version'                   => [ 'نسخہ', 'اخراجہ' ],
	'Wantedcategories'          => [ 'مطلوبہ_زمرہ_جات' ],
	'Wantedfiles'               => [ 'مطلوبہ_فائلیں', 'مطلوبہ_املاف' ],
	'Wantedpages'               => [ 'مطلوبہ_صفحات', 'شکستہ_روابط' ],
	'Wantedtemplates'           => [ 'مطلوبہ_سانچے' ],
	'Watchlist'                 => [ 'زیر_نظر_فہرست' ],
	'Whatlinkshere'             => [ 'مربوط_صفحات', 'یہاں_کس_کا_رابطہ' ],
	'Withoutinterwiki'          => [ 'بدون_بین_الویکی' ],
];

$magicWords = [
	'redirect'                  => [ '0', '#رجوع_مکرر', '#REDIRECT' ],
	'notoc'                     => [ '0', '_فہرست_نہیں_', '__NOTOC__' ],
	'toc'                       => [ '0', '__فہرست__', '__TOC__' ],
	'noeditsection'             => [ '0', '__ناتحریرقسم__', '__NOEDITSECTION__' ],
	'pagename'                  => [ '1', 'نام_صفحہ', 'PAGENAME' ],
	'namespace'                 => [ '1', 'نام_فضا', 'NAMESPACE' ],
	'msg'                       => [ '0', 'پیغام:', 'MSG:' ],
	'subst'                     => [ '0', 'جا:', 'نقل:', 'SUBST:' ],
	'safesubst'                 => [ '0', 'محفوظ_جا:', 'محفوظ_نقل:', 'SAFESUBST:' ],
	'img_thumbnail'             => [ '1', 'تصغیر', 'thumb', 'thumbnail' ],
	'img_right'                 => [ '1', 'دائیں', 'right' ],
	'img_left'                  => [ '1', 'بائیں', 'left' ],
	'img_center'                => [ '1', 'درمیان', 'center', 'centre' ],
	'sitename'                  => [ '1', 'نام_موقع', 'SITENAME' ],
	'grammar'                   => [ '0', 'قواعد:', 'GRAMMAR:' ],
	'gender'                    => [ '0', 'جنس:', 'GENDER:' ],
	'special'                   => [ '0', 'خاص', 'special' ],
	'speciale'                  => [ '0', 'خاص_عنوان', 'speciale' ],
	'index'                     => [ '1', '__اشاریہ__', '__INDEX__' ],
	'noindex'                   => [ '1', '__نااشاریہ__', '__NOINDEX__' ],
];
