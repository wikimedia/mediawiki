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
 * @author Obaid Raza
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
	'AllMyUploads'              => [ 'میرے_تمام_اپلوڈ', 'میری_تمام_فائلیں' ],
	'Allpages'                  => [ 'تمام_صفحات' ],
	'ApiHelp'                   => [ 'آے_پی_آئی_معاونت' ],
	'ApiSandbox'                => [ 'اے_پی_آئی_تختہ_مشق' ],
	'Ancientpages'              => [ 'قدیم_صفحات' ],
	'AutoblockList'             => [ 'فہرست_خودکار_پابندی' ],
	'Badtitle'                  => [ 'خراب_عنوان' ],
	'Blankpage'                 => [ 'خالی_صفحہ' ],
	'Block'                     => [ 'پابندی', 'آئی_پی_پابندی', 'آئی_پی_پتہ_پابندی', 'پابندی_بر_صارف' ]
	'Booksources'               => [ 'کتابی_وسائل' ],
	'BotPasswords'              => [ 'روبہ_کے_پاسورڈ' ],
	'BrokenRedirects'           => [ 'شکستہ_رجوع_مکررات' ],
	'Categories'                => [ 'زمرہ_جات' ],
	'ChangeContentModel'        => [ 'تبدیلی_مواد_ماڈل' ],
	'ChangeCredentials'         => [ 'تبدیلی_حساس_معلومات' ],
	'ChangeEmail'               => [ 'ڈاک_تبدیل' ],
	'ChangePassword'            => [ 'تبدیلی_پاسورڈ', 'کلمہ_شناخت_تبدیل', 'تنظیم_کلمہ_شناخت' ],
	'ComparePages'              => [ 'موازنہ_صفحات' ],
	'Confirmemail'              => [ 'تصدیق_ڈاک' ],
	'Contributions'             => [ 'شراکتیں' ],
	'CreateAccount'             => [ 'تخلیق_کھاتہ' ],
	'Deadendpages'              => [ 'مردہ_صفحات' ],
	'DeletedContributions'      => [ 'حذف_شدہ_شراکتیں' ],
	'Diff'                      => [ 'فرق' ],
	'DoubleRedirects'           => [ 'دوہرے_رجوع_مکررات' ],
	'EditTags'                  => [ 'ترمیم_ٹیگ' ],
	'EditWatchlist'             => [ 'ترمیم_زیر_نظر_فہرست', 'ترمیم_زیر_نظر' ],
	'Emailuser'                 => [ 'صارف_ڈاک', 'برقی_ڈاک' ],
	'ExpandTemplates'           => [ 'توسیع_سانچہ_جات' ],
	'Export'                    => [ 'برآمد', 'برآمدگی' ],
	'Fewestrevisions'           => [ 'کمترین_نسخہ', 'کم_نظر_ثانی_شدہ' ],
	'FileDuplicateSearch'       => [ 'تلاش_دوہری_فائل', 'دہری_ملف_تلاش' ],
	'Filepath'                  => [ 'راہ_فائل', 'راہ_ملف' ],
	'GoToInterwiki'             => [ 'بین_الویکی_پر_جائیں' ],
	'Import'                    => [ 'درآمد', 'درآمدگی' ],
	'Invalidateemail'           => [ 'ڈاک_تصدیق_منسوخ' ],
	'JavaScriptTest'            => [ 'تجربہ_جاوا_اسکرپٹ' ],
	'BlockList'                 => [ 'فہرست_ممنوعین', 'فہرست_ممنوع', 'فہرست_دستور_شبکی_ممنوع' ],
	'LinkSearch'                => [ 'تلاش_روابط' ],
	'LinkAccounts'              => [ 'کھاتے_مربوط_کریں' ],
	'Listadmins'                => [ 'فہرست_منتظمین' ],
	'Listbots'                  => [ 'فہرست_روبہ_جات' ],
	'Listfiles'                 => [ 'فائلوں_کی_فہرست', 'فہرست_تصاویر' ],
	'Listgrouprights'           => [ 'فہرست_اختیارات_گروہ', 'صارفی_گروہ_اختیارات' ],
	'Listgrants'                => [ 'فہرست_عطیات' ],
	'Listredirects'             => [ 'فہرست_رجوع_مکررات' ],
	'ListDuplicatedFiles'       => [ 'دوہری_فائلوں_کی_فہرست' ],
	'Listusers'                 => [ 'فہرست_صارفین' ],
	'Lockdb'                    => [ 'ڈیٹابیس_مقفل' ],
	'Log'                       => [ 'نوشتہ', 'نوشتہ_جات' ],
	'Lonelypages'               => [ 'یتیم_صفحات' ],
	'Longpages'                 => [ 'طویل_صفحات' ],
	'MediaStatistics'           => [ 'شماریات_میڈیا' ],
	'MergeHistory'              => [ 'ضم_تاریخچہ' ],
	'MIMEsearch'                => [ 'ایم_آئی_ایم_ای_تلاش' ],
	'Mostcategories'            => [ 'بیشتر_زمرہ_جات' ],
	'Mostimages'                => [ 'بیشتر_تصویریں', 'بیشتر_مربوط_تصویریں', 'بیشتر_فائلیں' ],
	'Mostinterwikis'            => [ 'بیشتر_بین_الویکی_رعابط' ],
	'Mostlinked'                => [ 'بیشتر_مربوط_صفحات', 'بیشتر_مربوط' ],
	'Mostlinkedcategories'      => [ 'بیشتر_مربوط_زمرہ_جات', 'بیشتر_مستعمل_زمرہ_جات' ],
	'Mostlinkedtemplates'       => [ 'بیشتر_مستعمل_صفحات', 'بیشتر_مربوط_سانچے', 'بیشتر_مستعمل_سانچے' ],
	'Mostrevisions'             => [ 'بیشتر_نسخے' ],
	'Movepage'                  => [ 'منتقلی_صفحہ' ],
	'Mycontributions'           => [ 'میری_شراکتیں', 'میرا_حصہ' ],
	'MyLanguage'                => [ 'میری_زبان' ],
	'Mypage'                    => [ 'میرا_صفحہ' ],
	'Mytalk'                    => [ 'میرا_تبادلہ_خیال',  'میری_گفتگو' ],
	'Myuploads'                 => [ 'میرے_اپلوڈ', 'میرے_زبراثقالات', 'میری_فائلیں' ],
	'Newimages'                 => [ 'جدید_فائلیں', 'جدید_املاف', 'جدید_تصاویر', 'نئی_فائلیں', 'نئی_املاف', 'نئی_تصاویر', 'نئی_تصویریں', 'جدید_تصویریں' ],
	'Newpages'                  => [ 'جدید_صفحات' ],
	'PagesWithProp'             => [ 'صفحات_مع_خاصیت', 'صفحات_بلحاظ_خاصیت' ],
	'PageData'                  => [ 'معلومات_صفحہ' ],
	'PageLanguage'              => [ 'صفحہ_کی_زبان' ],
	'PasswordReset'             => [ 'پاسورڈ_کی_ترتیب_نو' ],
	'PermanentLink'             => [ 'مستقل_ربط' ],
	'Preferences'               => [ 'ترجیحات' ],
	'Prefixindex'               => [ 'اشاریہ_سابقہ' ],
	'Protectedpages'            => [ 'محفوظ_صفحات' ],
	'Protectedtitles'          => [ 'محفوظ_عناوین' ],
	'Randompage'                => [ 'جستہ', 'جستہ_جستہ', 'تصادف', 'تصادفی_مقالہ' ],
	'RandomInCategory'          => [ 'جستہ_جستہ_زمرہ' ],
	'Randomredirect'            => [ 'جستہ_جستہ_رجوع_مکرر', 'تصادفی_رجوع_مکرر' ],
	'Randomrootpage'            => [ 'جستہ_جستہ_بنیادی_صفحہ' ],
	'Recentchanges'             => [ 'حالیہ_تبدیلیاں' ],
	'Recentchangeslinked'       => [ 'متعلقہ_حالیہ_تبدیلیاں', 'متعلقہ_تبدیلیاں' ],
	'Redirect'                  => [ 'رجوع_مکرر' ],
	'RemoveCredentials'         => [ 'حذف_حساس_معلومات' ],
	'ResetTokens'               => [ 'ٹوکنوں_کی_ترتیب_نو' ],
	'Revisiondelete'            => [ 'حذف_نسخہ', 'حذف_نظر_ثانی', 'حذف_اعادہ' ],
	'RunJobs'                   => [ 'تعمیل_امور' ],
	'Search'                    => [ 'تلاش' ],
	'Shortpages'                => [ 'مختصر_صفحات' ],
	'Specialpages'              => [ 'خصوصی_صفحات' ],
	'Statistics'                => [ 'شماریات' ],
	'Tags'                      => [ 'ٹیگ', 'ٹیگز' ],
	'TrackingCategories'        => [ 'متلاشی_زمرہ_جات' ],
	'Unblock'                   => [ 'پابندی_ختم' ],
	'Uncategorizedcategories'   => [ 'غیر_زمرہ_بند_زمرہ_جات' ],
	'Uncategorizedimages'       => [ 'غیر_زمرہ_بند_فائلیں', 'غیر_زمرہ_بند_املاف', 'غیر_زمرہ_بند_تصاویر' ],
	'Uncategorizedpages'        => [ 'غیر_زمرہ_بند_صفحات' ],
	'Uncategorizedtemplates'    => [ 'غیر_زمرہ_بند_سانچے' ],
	'Undelete'                  => [ 'بحال' ],
	'UnlinkAccounts'            => [ 'کھاتے_غیر_مربوط_کریں' ],
	'Unlockdb'                  => [ 'ڈیٹابیس_قفل_کھولیں' ],
	'Unusedcategories'          => [ 'غیر_مستعمل_زمرہ_جات' ],
	'Unusedimages'              => [  'غیر_مستعمل_فائلیں', 'غیر_مستعمل_املاف', 'غیر_مستعمل_تصاویر', 'غیر_مستعمل_تصویریں' ],
	'Unusedtemplates'           => [ 'غیر_مستعمل_سانچے' ],
	'Unwatchedpages'            => [ 'نادیدہ_صفحات' ],
	'Upload'                    => [ 'اپلوڈ', 'زبراثقال', 'زبر_اثقال' ],
	'UploadStash'               => [ 'اجتماعی_اپلوڈ' ],
	'Userlogin'                 => [ 'داخل_ہوں', 'داخل_نوشتگی' ],
	'Userlogout'                => [ 'خارج_ہوں', 'خارج_نوشتگی' ],
	'Userrights'                => [ 'اختیارات_صارف', 'صارفی_اختیارات' ],
	'Version'                   => [ 'نسخہ', 'اخراجہ' ],
	'Wantedcategories'          => [ 'مطلوبہ_زمرہ_جات' ],
	'Wantedfiles'               => [ 'مطلوبہ_فائلیں', 'مطلوبہ_املاف' ],
	'Wantedpages'               => [ 'مطلوبہ_صفحات', 'شکستہ_روابط' ],
	'Wantedtemplates'           => [ 'مطلوبہ_سانچے' ],
	'Watchlist'                 => [ 'زیر_نظر_فہرست' ],
	'Whatlinkshere'             => [ 'مربوط_صفحات', 'یہاں_کس_کا_رابطہ' ],
	'Withoutinterwiki'          => [ 'بدون_بین_الویکی' ],
];

# LinkTrail for Urdu language
$linkTrail = "/^([ابپتٹثجچحخدڈذر​ڑ​زژسشصضطظعغفقکگل​م​نوؤہھیئےآأءۃ]+)(.*)$/sDu";