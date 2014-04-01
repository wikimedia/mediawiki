<?php
/** Urdu (اردو)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
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

$namespaceNames = array(
	NS_MEDIA            => 'وسیط',
	NS_SPECIAL          => 'خاص',
	NS_MAIN             => '',
	NS_TALK             => 'تبادلۂ_خیال',
	NS_USER             => 'صارف',
	NS_USER_TALK        => 'تبادلۂ_خیال_صارف',
	NS_PROJECT_TALK     => 'تبادلۂ_خیال_$1',
	NS_FILE             => 'ملف',
	NS_FILE_TALK        => 'تبادلۂ_خیال_ملف',
	NS_MEDIAWIKI        => 'میڈیاویکی',
	NS_MEDIAWIKI_TALK   => 'تبادلۂ_خیال_میڈیاویکی',
	NS_TEMPLATE         => 'سانچہ',
	NS_TEMPLATE_TALK    => 'تبادلۂ_خیال_سانچہ',
	NS_HELP             => 'معاونت',
	NS_HELP_TALK        => 'تبادلۂ_خیال_معاونت',
	NS_CATEGORY         => 'زمرہ',
	NS_CATEGORY_TALK    => 'تبادلۂ_خیال_زمرہ',
);

$namespaceAliases = array(
	'زریعہ'            => NS_MEDIA,
	'تصویر'            => NS_FILE,
	'تبادلۂ_خیال_تصویر'   => NS_FILE_TALK,
	'میڈیاوکی'          => NS_MEDIAWIKI,
	'تبادلۂ_خیال_میڈیاوکی' => NS_MEDIAWIKI_TALK,
);

$specialPageAliases = array(
	'Activeusers'               => array( 'متحرک_صارفین' ),
	'Allmessages'               => array( 'تمام_پیغامات' ),
	'Allpages'                  => array( 'تمام_صفحات' ),
	'Ancientpages'              => array( 'قدیم_صفحات' ),
	'Badtitle'                  => array( 'خراب_عنوان' ),
	'Blankpage'                 => array( 'خالی_صفحہ' ),
	'Block'                     => array( 'پابندی', 'دستور_شبکی_پابندی', 'پابندی_بر_صارف' ),
	'Booksources'               => array( 'کتابی_وسائل' ),
	'BrokenRedirects'           => array( 'شکستہ_رجوع_مکررات' ),
	'Categories'                => array( 'زمرہ_جات' ),
	'ChangeEmail'               => array( 'ڈاک_تبدیل' ),
	'ChangePassword'            => array( 'کلمہ_شناخت_تبدیل', 'تنظیم_کلمہ_شناخت' ),
	'ComparePages'              => array( 'موازنہ_صفحات' ),
	'Confirmemail'              => array( 'تصدیق_ڈاک' ),
	'Contributions'             => array( 'شراکتیں' ),
	'CreateAccount'             => array( 'تخلیق_کھاتہ' ),
	'Deadendpages'              => array( 'مردہ_صفحات' ),
	'DeletedContributions'      => array( 'حذف_شدہ_شراکتیں' ),
	'DoubleRedirects'           => array( 'دوہرے_رجوع_مکررات' ),
	'EditWatchlist'             => array( 'ترمیم_زیر_نظر' ),
	'Emailuser'                 => array( 'صارف_ڈاک' ),
	'Export'                    => array( 'برآمدگی' ),
	'Fewestrevisions'           => array( 'کم_نظر_ثانی_شدہ' ),
	'FileDuplicateSearch'       => array( 'دہری_ملف_تلاش' ),
	'Filepath'                  => array( 'راہ_ملف' ),
	'Import'                    => array( 'درآمدگی' ),
	'Invalidateemail'           => array( 'ڈاک_تصدیق_منسوخ' ),
	'JavaScriptTest'            => array( 'تجربہ_جاوا_اسکرپٹ' ),
	'BlockList'                 => array( 'فہرست_ممنوع', 'فہرست_دستور_شبکی_ممنوع' ),
	'LinkSearch'                => array( 'تلاش_روابط' ),
	'Listadmins'                => array( 'فہرست_منتظمین' ),
	'Listbots'                  => array( 'فہرست_روبہ_جات' ),
	'Listfiles'                 => array( 'فہرست_املاف', 'فہرست_تصاویر' ),
	'Listgrouprights'           => array( 'فہرست_اختیارات_گروہ', 'صارفی_گروہ_اختیارات' ),
	'Listredirects'             => array( 'فہرست_رجوع_مکررات' ),
	'Listusers'                 => array( 'فہرست_صارفین،_صارف_فہرست' ),
	'Log'                       => array( 'نوشتہ', 'نوشتہ_جات' ),
	'Lonelypages'               => array( 'یتیم_صفحات' ),
	'Longpages'                 => array( 'طویل_صفحات' ),
	'MergeHistory'              => array( 'ضم_تاریخچہ' ),
	'Movepage'                  => array( 'منتقلی_صفحہ' ),
	'Mycontributions'           => array( 'میرا_حصہ' ),
	'Mypage'                    => array( 'میرا_صفحہ' ),
	'Mytalk'                    => array( 'میری_گفتگو' ),
	'Myuploads'                 => array( 'میرے_زبراثقالات' ),
	'Newimages'                 => array( 'جدید_املاف', 'جدید_تصاویر' ),
	'Newpages'                  => array( 'جدید_صفحات' ),
	'PermanentLink'             => array( 'مستقل_ربط' ),
	'Popularpages'              => array( 'مقبول_صفحات' ),
	'Preferences'               => array( 'ترجیحات' ),
	'Prefixindex'               => array( 'اشاریہ_سابقہ' ),
	'Protectedpages'            => array( 'محفوظ_صفحات' ),
	'Protectedtitles'           => array( 'محفوظ_عناوین' ),
	'Randompage'                => array( 'تصادف', 'تصادفی_مقالہ' ),
	'Randomredirect'            => array( 'تصادفی_رجوع_مکرر' ),
	'Recentchanges'             => array( 'حالیہ_تبدیلیاں' ),
	'Recentchangeslinked'       => array( 'متعلقہ_تبدیلیاں' ),
	'Revisiondelete'            => array( 'حذف_اعادہ' ),
	'Search'                    => array( 'تلاش' ),
	'Shortpages'                => array( 'مختصر_صفحات' ),
	'Specialpages'              => array( 'خصوصی_صفحات' ),
	'Statistics'                => array( 'شماریات' ),
	'Uncategorizedcategories'   => array( 'غیر_زمرہ_بند_زمرہ_جات' ),
	'Uncategorizedimages'       => array( 'غیر_زمرہ_بند_املاف', 'غیر_زمرہ_بند_تصاویر' ),
	'Uncategorizedpages'        => array( 'غیر_زمرہ_بند_صفحات' ),
	'Uncategorizedtemplates'    => array( 'غیر_زمرہ_بند_سانچے' ),
	'Undelete'                  => array( 'بحال' ),
	'Unusedcategories'          => array( 'غیر_مستعمل_زمرہ_جات' ),
	'Unusedimages'              => array( 'غیر_مستعمل_املاف', 'غیر_مستعمل_تصاویر' ),
	'Unusedtemplates'           => array( 'غیر_مستعمل_سانچے' ),
	'Unwatchedpages'            => array( 'نادیدہ_صفحات' ),
	'Upload'                    => array( 'زبراثقال' ),
	'Userlogin'                 => array( 'داخل_نوشتگی' ),
	'Userlogout'                => array( 'خارج_نوشتگی' ),
	'Userrights'                => array( 'صارفی_اختیارات' ),
	'Version'                   => array( 'اخراجہ' ),
	'Wantedcategories'          => array( 'مطلوب_زمرہ_جات' ),
	'Wantedfiles'               => array( 'مطلوب_املاف' ),
	'Wantedpages'               => array( 'مطلوب_صفحات', 'شکستہ_روابط' ),
	'Wantedtemplates'           => array( 'مطلوب_سانچے' ),
	'Watchlist'                 => array( 'زیر_نظر_فہرست' ),
	'Whatlinkshere'             => array( 'یہاں_کس_کا_رابطہ' ),
	'Withoutinterwiki'          => array( 'بدون_بین_الویکی' ),
);

$magicWords = array(
	'redirect'                  => array( '0', '#رجوع_مکرر', '#REDIRECT' ),
	'notoc'                     => array( '0', '_فہرست_نہیں_', '__NOTOC__' ),
	'toc'                       => array( '0', '__فہرست__', '__TOC__' ),
	'noeditsection'             => array( '0', '__ناتحریرقسم__', '__NOEDITSECTION__' ),
	'pagename'                  => array( '1', 'نام_صفحہ', 'PAGENAME' ),
	'namespace'                 => array( '1', 'نام_فضا', 'NAMESPACE' ),
	'msg'                       => array( '0', 'پیغام:', 'MSG:' ),
	'subst'                     => array( '0', 'جا:', 'نقل:', 'SUBST:' ),
	'safesubst'                 => array( '0', 'محفوظ_جا:', 'محفوظ_نقل:', 'SAFESUBST:' ),
	'img_thumbnail'             => array( '1', 'تصغیر', 'thumbnail', 'thumb' ),
	'img_right'                 => array( '1', 'دائیں', 'right' ),
	'img_left'                  => array( '1', 'بائیں', 'left' ),
	'img_center'                => array( '1', 'درمیان', 'center', 'centre' ),
	'sitename'                  => array( '1', 'نام_موقع', 'SITENAME' ),
	'grammar'                   => array( '0', 'قواعد:', 'GRAMMAR:' ),
	'gender'                    => array( '0', 'جنس:', 'GENDER:' ),
	'special'                   => array( '0', 'خاص', 'special' ),
	'speciale'                  => array( '0', 'خاص_عنوان', 'speciale' ),
	'index'                     => array( '1', '__اشاریہ__', '__INDEX__' ),
	'noindex'                   => array( '1', '__نااشاریہ__', '__NOINDEX__' ),
);

