<?php
/** Sindhi (سنڌي)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Aursani
 */

$fallback8bitEncoding = 'windows-1256';
$rtl = true;

$namespaceNames = array(
	NS_MEDIA            => 'ذريعات',
	NS_SPECIAL          => 'خاص',
	NS_TALK             => 'بحث',
	NS_USER             => 'يوزر',
	NS_USER_TALK        => 'يوزر_بحث',
	NS_PROJECT_TALK     => '$1_بحث',
	NS_FILE             => 'عڪس',
	NS_FILE_TALK        => 'عڪس_بحث',
	NS_MEDIAWIKI        => 'ذريعات_وڪي',
	NS_MEDIAWIKI_TALK   => 'ذريعات_وڪي_بحث',
	NS_TEMPLATE         => 'سانچو',
	NS_TEMPLATE_TALK    => 'سنچو_بحث',
	NS_HELP             => 'مدد',
	NS_HELP_TALK        => 'مدد_بحث',
	NS_CATEGORY         => 'زمرو',
	NS_CATEGORY_TALK    => 'زمرو_بحث',
);

$specialPageAliases = array(
	'Allmessages'               => array( 'سڀ نياپا' ),
	'Allpages'                  => array( 'سڀ صفحا' ),
	'Ancientpages'              => array( 'قديم صفحا' ),
	'Block'                     => array( 'آءِ پي بندش' ),
	'Blockme'                   => array( 'مونکي بندشيو' ),
	'BrokenRedirects'           => array( 'ٽٽل چورڻا' ),
	'Categories'                => array( 'زمرا' ),
	'Confirmemail'              => array( 'برقٽپال تصديقيو' ),
	'Contributions'             => array( 'ڀاڱيداريون' ),
	'CreateAccount'             => array( 'کاتو کوليو' ),
	'Disambiguations'           => array( 'سلجھائپ' ),
	'DoubleRedirects'           => array( 'ٻٽا چورڻا' ),
	'Emailuser'                 => array( 'برقٽپال يوزر' ),
	'Export'                    => array( 'برآمد' ),
	'FileDuplicateSearch'       => array( 'ساڳيا فائيل ڳولا' ),
	'Filepath'                  => array( 'فائيل ڏس' ),
	'Import'                    => array( 'درآمد' ),
	'Invalidateemail'           => array( 'ناقابلڪار برقٽپال' ),
	'BlockList'                 => array( 'آءِ پي بندش فهرست' ),
	'Listadmins'                => array( 'منتظمين فهرست' ),
	'Listbots'                  => array( 'بوٽس فهرست' ),
	'Listfiles'                 => array( 'عڪس فهرست' ),
	'Listredirects'             => array( 'چورڻا فهرست' ),
	'Listusers'                 => array( 'يوزر فهرست' ),
	'Lockdb'                    => array( 'اعدادخانو بند' ),
	'Log'                       => array( 'لاگس' ),
	'Lonelypages'               => array( 'يتيم صفحا' ),
	'Longpages'                 => array( 'طويل صفحا' ),
	'MergeHistory'              => array( 'سوانح ضماءُ' ),
	'MIMEsearch'                => array( 'مائيم ڳولا' ),
	'Movepage'                  => array( 'صفحو چوريو' ),
	'Mycontributions'           => array( 'منهنجون ڀاڱيداريون' ),
	'Mypage'                    => array( 'منهنجو صفحو' ),
	'Mytalk'                    => array( 'مون سان ڳالهه' ),
	'Newimages'                 => array( 'نوان عڪس' ),
	'Newpages'                  => array( 'نوان صفحا' ),
	'Popularpages'              => array( 'مقبول صفحا' ),
	'Preferences'               => array( 'ترجيحات' ),
	'Prefixindex'               => array( 'اڳياڙي ڏسڻي' ),
	'Protectedpages'            => array( 'تحفظيل صفحا' ),
	'Protectedtitles'           => array( 'تحفظيل عنوان' ),
	'Randompage'                => array( 'بلا ترتيب' ),
	'Randomredirect'            => array( 'بلا ترتيب چورڻو' ),
	'Recentchanges'             => array( 'تازيون تبديليون' ),
	'Search'                    => array( 'ڳولا' ),
	'Shortpages'                => array( 'مختصر صفحا' ),
	'Specialpages'              => array( 'خاص صفحا' ),
	'Statistics'                => array( 'انگ اکر' ),
	'Uncategorizedcategories'   => array( 'اڻ زمرايل زمرا' ),
	'Uncategorizedimages'       => array( 'اڻ زمرايل عڪس' ),
	'Uncategorizedpages'        => array( 'اڻزمرايل صفحا' ),
	'Uncategorizedtemplates'    => array( 'اڻ زمرايل سانچا' ),
	'Undelete'                  => array( 'اڻ ڊاهيو' ),
	'Unlockdb'                  => array( 'اعدادخانو کول' ),
	'Unusedcategories'          => array( 'اڻ استعماليل زمرا' ),
	'Unusedimages'              => array( 'اڻ استعماليل عڪس' ),
	'Unusedtemplates'           => array( 'اڻ استعماليل سانچا' ),
	'Unwatchedpages'            => array( 'اڻٽيٽيل صفحا' ),
	'Upload'                    => array( 'چاڙهيو' ),
	'Userlogin'                 => array( 'يوزر لاگ اِن' ),
	'Userlogout'                => array( 'يوزر لاگ آئوٽ' ),
	'Userrights'                => array( 'يوزر حق' ),
	'Version'                   => array( 'ورزن' ),
	'Wantedcategories'          => array( 'گھربل زمرا' ),
	'Wantedpages'               => array( 'گھربل صفحا' ),
	'Watchlist'                 => array( 'ٽيٽ فهرست' ),
	'Whatlinkshere'             => array( 'هتان ڳنڍيل صفحا' ),
	'Withoutinterwiki'          => array( 'ري بين الوڪي' ),
);

$magicWords = array(
	'redirect'                => array( '0', '#چوريو', '#REDIRECT' ),
	'localmonth'              => array( '1', 'مقاميمهينو', 'LOCALMONTH', 'LOCALMONTH2' ),
	'localmonthname'          => array( '1', 'مقاميمهينونالو', 'LOCALMONTHNAME' ),
	'localday'                => array( '1', 'مقاميڏينهن', 'LOCALDAY' ),
	'localday2'               => array( '1', 'مقاميڏينهن2', 'LOCALDAY2' ),
	'localdayname'            => array( '1', 'مقاميڏينهننالو', 'LOCALDAYNAME' ),
	'localyear'               => array( '1', 'مقاميسال', 'LOCALYEAR' ),
	'localtime'               => array( '1', 'مقاميوقت', 'LOCALTIME' ),
	'localhour'               => array( '1', 'مقاميڪلاڪ', 'LOCALHOUR' ),
	'numberofpages'           => array( '1', 'صفحنجوتعداد', 'NUMBEROFPAGES' ),
	'numberofarticles'        => array( '1', 'مضموننجوتعداد', 'NUMBEROFARTICLES' ),
	'numberoffiles'           => array( '1', 'فائيلنجوتعداد', 'NUMBEROFFILES' ),
	'numberofusers'           => array( '1', 'يوزرسجوتعداد', 'NUMBEROFUSERS' ),
	'numberofedits'           => array( '1', 'ترميمنجوتعداد', 'NUMBEROFEDITS' ),
	'pagename'                => array( '1', 'صفحيجوعنوان', 'PAGENAME' ),
	'namespace'               => array( '1', 'نانئپولار', 'NAMESPACE' ),
	'talkspace'               => array( '1', 'بحثپولار', 'TALKSPACE' ),
	'subjectspace'            => array( '1', 'مضمونپولار', 'SUBJECTSPACE', 'ARTICLESPACE' ),
	'fullpagename'            => array( '1', 'صحفيجوپورونالو', 'FULLPAGENAME' ),
	'msg'                     => array( '0', 'نياپو:', 'MSG:' ),
	'img_right'               => array( '1', 'ساڄو', 'right' ),
	'img_left'                => array( '1', 'کاٻو', 'left' ),
	'img_none'                => array( '1', 'ڪجهنه', 'none' ),
	'img_width'               => array( '1', '$1 عڪسلون', '$1px' ),
	'img_center'              => array( '1', 'مرڪز', 'center', 'centre' ),
	'img_top'                 => array( '1', 'سِرُ', 'top' ),
	'img_middle'              => array( '1', 'وچ', 'middle' ),
	'img_bottom'              => array( '1', 'تَرُ', 'bottom' ),
	'sitename'                => array( '1', 'سرزميننالو', 'SITENAME' ),
	'ns'                      => array( '0', 'نپ', 'NS:' ),
	'localurl'                => array( '0', 'مقامييوآريل', 'LOCALURL:' ),
	'grammar'                 => array( '0', 'وياڪرڻ', 'GRAMMAR:' ),
	'currentweek'             => array( '1', 'هلندڙهفتو', 'CURRENTWEEK' ),
	'currentdow'              => array( '1', 'اڄوڪوڏينهن', 'CURRENTDOW' ),
	'localweek'               => array( '1', 'مقاميهفتو', 'LOCALWEEK' ),
	'plural'                  => array( '0', 'جمع', 'PLURAL:' ),
	'fullurl'                 => array( '0', 'مڪمليوآريل', 'FULLURL:' ),
	'currenttimestamp'        => array( '1', 'هلندڙوقتمهر', 'CURRENTTIMESTAMP' ),
	'localtimestamp'          => array( '1', 'مقاميوقتمهر', 'LOCALTIMESTAMP' ),
	'directionmark'           => array( '1', 'طرفنشان', 'DIRECTIONMARK', 'DIRMARK' ),
	'language'                => array( '0', '#ٻولي:', '#LANGUAGE:' ),
	'contentlanguage'         => array( '1', 'موادٻولي', 'CONTENTLANGUAGE', 'CONTENTLANG' ),
	'pagesinnamespace'        => array( '1', 'نپ۾صفحا', 'PAGESINNAMESPACE:', 'PAGESINNS:' ),
	'numberofadmins'          => array( '1', 'منتظمينجوتعداد', 'NUMBEROFADMINS' ),
	'special'                 => array( '0', 'خاص', 'special' ),
	'filepath'                => array( '0', 'فائيلڏس', 'FILEPATH:' ),
	'hiddencat'               => array( '1', '__ لڪل زمرو __', '__HIDDENCAT__' ),
	'pagesincategory'         => array( '1', 'زمريجاصفحا', 'PAGESINCATEGORY', 'PAGESINCAT' ),
	'pagesize'                => array( '1', 'صفحيجيماپ', 'PAGESIZE' ),
);

$messages = array(
# User preference toggles
'tog-showtoolbar'         => 'سنوارپ اوزار دٻي ڏيکاريو (جاوا اسڪرپٽ)',
'tog-watchcreations'      => 'منهنجا سرجيل صفحا منهنجي ٽيٽ فهرست ۾ رکو',
'tog-watchdefault'        => 'منهنجا ترميميل صفحا منهنجي ٽيٽ فهرست تي رکو',
'tog-watchdeletion'       => 'آئون جيڪي صفحا ڊاهيان، سي منهنجي ٽيٽ فهرست تي رکو',
'tog-previewontop'        => 'ترميمي باڪس مٿان پيش نگاهه ڏيکاريو',
'tog-previewonfirst'      => 'پهرين ترميم تي پيش نگاهه ڏيکاريو',
'tog-enotifusertalkpages' => 'منهنجي مباحثي صفحي ۾ تبديليءَ جي صورت ۾ مون کي برق ٽپال اماڻيو',
'tog-enotifminoredits'    => 'صفحن ۾ معمولي ترميمن جي صورت ۾ به مون کي برق ٽپال ڪريو',
'tog-shownumberswatching' => 'ٽيٽيندڙ يوزرس جو تعداد ڏيکاريو',
'tog-ccmeonemails'        => 'ٻين يوزرس ڏانهن منهنجي موڪليل برق ٽپال جو پرت مون کي اماڻيو',
'tog-diffonly'            => 'تفاوت هيٺان صفحي جو مواد نه ڏيکاريو',
'tog-showhiddencats'      => 'لڪل زمرا ڏيکاريو',

'underline-always' => 'هميشه',
'underline-never'  => 'ڪڏهن به نه',

# Dates
'sunday'        => 'آچر',
'monday'        => 'سومر',
'tuesday'       => 'اڱارو',
'wednesday'     => 'اربع',
'thursday'      => 'خميس',
'friday'        => 'جمعو',
'saturday'      => 'ڇنڇر',
'sun'           => 'آچر',
'mon'           => 'سومر',
'tue'           => 'اڱارو',
'wed'           => 'اربع',
'thu'           => 'خميس',
'fri'           => 'جمعو',
'sat'           => 'ڇنڇر',
'january'       => 'جنوري',
'february'      => 'فيبروري',
'march'         => 'مارچ',
'april'         => 'اپريل',
'may_long'      => 'مَي',
'june'          => 'جُونِ',
'july'          => 'جُولاءِ',
'august'        => 'آگسٽ',
'september'     => 'سيپٽمبر',
'october'       => 'آڪٽوبر',
'november'      => 'نومبر',
'december'      => 'ڊسمبر',
'january-gen'   => 'جنوري',
'february-gen'  => 'فيبروري',
'march-gen'     => 'مارچ',
'april-gen'     => 'اپريل',
'may-gen'       => 'مَي',
'june-gen'      => 'جُونِ',
'july-gen'      => 'جُولاءِ',
'august-gen'    => 'آگسٽ',
'september-gen' => 'سيپٽمبر',
'october-gen'   => 'آڪٽوبر',
'november-gen'  => 'نومبر',
'december-gen'  => 'ڊسمبر',
'jan'           => 'جنوري',
'feb'           => 'فيبروري',
'mar'           => 'مارچ',
'apr'           => 'اپريل',
'may'           => 'مَي',
'jun'           => 'جُونِ',
'jul'           => 'جُولاءِ',
'aug'           => 'آگسٽ',
'sep'           => 'سيپٽمبر',
'oct'           => 'آڪٽوبر',
'nov'           => 'نومبر',
'dec'           => 'ڊسمبر',

# Categories related messages
'category_header'          => '"$1" زمري جا صفحا',
'subcategories'            => 'ذيلي زمرا',
'category-media-header'    => ' "$1" زمري اندر ذريعات',
'category-empty'           => "''في الوقت هن زمري ۾ ڪي به صفحا يا ذريعات شامل ناهن.''",
'hidden-category-category' => 'لڪل زمرا',
'listingcontinuesabbrev'   => 'جاري..',

'about'         => 'بابت',
'article'       => 'مسوَدو',
'newwindow'     => '(نئين کڙڪيءَ ۾ کلندو)',
'cancel'        => 'رد',
'moredotdotdot' => 'اڃا...',
'mypage'        => 'منهنجو صفحو',
'mytalk'        => 'مون سان ڳالهه',
'and'           => '&#32;۽',

# Cologne Blue skin
'qbfind'         => 'ڳوليو',
'qbbrowse'       => 'جھانگيو',
'qbedit'         => 'سنواريو',
'qbpageoptions'  => 'هيءُ صفحو',
'qbmyoptions'    => 'منهنجا صفحا',
'qbspecialpages' => 'خاص صفحا',
'faq'            => 'ڪپوس',
'faqpage'        => 'Project:ڪپوس',

# Vector skin
'vector-action-delete'    => 'ڊاھيو',
'vector-action-move'      => 'چوريو',
'vector-action-protect'   => 'تحفظيو',
'vector-action-unprotect' => 'اڻتحفظيو',
'vector-view-create'      => 'سرجيو',
'vector-view-edit'        => 'سنواريو',
'vector-view-viewsource'  => 'ڪوڊ ڏسو',

'errorpagetitle'    => 'چُڪَ',
'returnto'          => '$1 ڏانهن وَرو.',
'tagline'           => '{{SITENAME}} طرفان',
'help'              => 'مدد',
'search'            => 'ڳولا',
'searchbutton'      => 'ڳوليو',
'go'                => 'کوليو',
'searcharticle'     => 'کوليو',
'history'           => 'صفحي جي سوانح',
'history_short'     => 'سوانح',
'printableversion'  => 'ڇپائتو پرت',
'permalink'         => 'مسقتل ڳنڍڻو',
'print'             => 'ڇاپيو',
'edit'              => 'سنواريو',
'create'            => 'سرجيو',
'editthispage'      => 'هيءُ صفحو سنواريو',
'create-this-page'  => 'اهو صفحو نئين سر جوڙيو',
'delete'            => 'ڊاھيو',
'deletethispage'    => 'هيءُ صفحو ڊاهيو',
'undelete_short'    => 'اڻڊاهيو {{PLURAL:$1|هڪ ترميم|$1 ترميمون}}',
'protect'           => 'تحفظيو',
'protectthispage'   => 'هيءُ صفحو تحفظيو',
'unprotect'         => 'اڻتحفظيو',
'unprotectthispage' => 'هيءُ صفحو اڻتحفظيو',
'newpage'           => 'نئون صفحو',
'talkpage'          => 'هن صفحي تي بحث ڪريو',
'talkpagelinktext'  => 'بحث',
'specialpage'       => 'خاص صفحو',
'personaltools'     => 'ذاتي اوزار',
'postcomment'       => 'تاثرات درج ڪريو',
'articlepage'       => 'مسودو ڏسو',
'talk'              => 'بحث',
'views'             => 'ڏيٺون',
'toolbox'           => 'اوزاردٻي',
'userpage'          => 'يوزر صفحو ڏسو',
'projectpage'       => 'رٿائي صفحو ڏسو',
'imagepage'         => 'ذريعاتي صفحو ڏسو',
'mediawikipage'     => 'نياپي جو صفحو ڏسو',
'templatepage'      => 'سانچي جو صفحو ڏسو',
'viewhelppage'      => 'امدادي صفحو ڏسو',
'categorypage'      => 'زمراتي صفحو ڏسو',
'viewtalkpage'      => 'بحث ڏسو',
'otherlanguages'    => 'ٻين ٻولين ۾',
'redirectedfrom'    => '($1 کان چوريل)',
'redirectpagesub'   => 'چوريل صفحو',
'lastmodifiedat'    => 'هيءُ صفحو آخري ڀيرو $2، $1ع تي ترميميو ويو هو.',
'viewcount'         => 'هيءُ صفحو {{PLURAL:$1|دفعو|$1 دفعا}} ڏسجي چڪو آهي.',
'protectedpage'     => 'تحفظيل صفحو',
'jumptosearch'      => 'ڳولا',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => '{{SITENAME}} بابت',
'aboutpage'            => 'Project:بابت',
'copyright'            => 'سمورو مواد $1 تحت ميسر ڪجي ٿو',
'copyrightpage'        => '{{ns:project}}:حق ۽ واسطا',
'currentevents'        => 'ھاڻوڪا واقعا',
'currentevents-url'    => 'Project: اعداد',
'disclaimers'          => 'غيرجوابداريناما',
'disclaimerpage'       => 'Project:عام غيرجوابدارينامو',
'edithelp'             => 'مدد براءِ ترميم',
'edithelppage'         => 'Help:سنوارڻ',
'helppage'             => 'Help:فهرست',
'mainpage'             => 'مُک صفحو',
'mainpage-description' => 'مُک صفحو',
'policy-url'           => 'Project:پاليسي',
'portal'               => 'نياتي باب',
'portal-url'           => 'Project:نياتي باب',
'privacy'              => 'ذاتيات پاليسي',
'privacypage'          => 'Project:ذاتيات پاليسي',

'ok'                  => 'ٺيڪ',
'retrievedfrom'       => '"$1" تان ورتل',
'youhavenewmessages'  => 'توهان لاءِ $1 ($2) آهن.',
'newmessageslink'     => 'نوان نياپا',
'newmessagesdifflink' => 'آخري تبديلي',
'editsection'         => 'سنواريو',
'editold'             => 'سنواريو',
'viewsourceold'       => 'ڪوڊ ڏسو',
'editlink'            => 'سنواريو',
'viewsourcelink'      => 'ڪوڊ ڏسو',
'editsectionhint'     => 'سنواريو سيڪشن: $1',
'toc'                 => 'فهرست',
'showtoc'             => 'ڏيکاريو',
'hidetoc'             => 'لڪايو',
'viewdeleted'         => '$1 ڏسندا؟',
'feedlinks'           => 'روان رسد:',
'site-rss-feed'       => '$1 آر ايس ايس روان رسد',
'site-atom-feed'      => '$1 اڻو روان رسد',
'page-rss-feed'       => '"$1" RSS برق مواد',
'page-atom-feed'      => '"$1" اڻو روان رسد',
'red-link-title'      => '$1 (اڃا لکيل ناهي)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'صفحو',
'nstab-user'      => 'تعارفي صفحو',
'nstab-media'     => 'ذريعاتي صفحو',
'nstab-special'   => 'خاص صفحو',
'nstab-project'   => 'رٿائي صفحو',
'nstab-image'     => 'فائيل',
'nstab-mediawiki' => 'نياپو',
'nstab-template'  => 'سانچو',
'nstab-help'      => 'امدادي صفحو',
'nstab-category'  => 'زمرو',

# Main script and global functions
'nosuchspecialpage' => 'اهڙو ڪو به خاص صفحو ناهي',

# General errors
'error'               => 'چُڪَ',
'databaseerror'       => 'اعدادخاني ۾ چڪ',
'readonly'            => 'اعدادخانو بنديل',
'missingarticle-diff' => '(تفاوت: $1، $2)',
'internalerror'       => 'اندروني خرابي',
'internalerror_info'  => 'داخلي چُڪَ: $1',
'filerenameerror'     => '"$1" نالي فائيل تي نئون نالو "$2" رکجي نه سگھجو.',
'filedeleteerror'     => '"$1" فائيل ڊهي نه سگھيو.',
'filenotfound'        => '"$1" نالي فائيل لڀجي نه سگھيو.',
'unexpected'          => 'غير متوقع قدر: "$1"="$2".',
'badtitle'            => 'غيردرست عنوان',
'viewsource'          => 'ڪوڊ ڏسو',
'protectedpagetext'   => 'هيءُ صفحو ترميمن کان تحفظيل آهي.',
'viewsourcetext'      => 'توهان هن صفحي جو ڪوڊ ڏسي ۽ نقل ڪري سگھو ٿا:',
'namespaceprotected'  => "توهان کي نانءُ پولار '''$1''' جا صفحا سنوارڻ جا اختيار ناهن.",
'ns-specialprotected' => 'خاص صفحا سنواري نٿا سگھجن.',

# Login and logout pages
'yourname'                   => 'يُوزرنانءُ:',
'yourpassword'               => 'ڳجھو لفظ:',
'remembermypassword'         => 'هن ڳڻپيوڪر تي مون کي ياد رکو (for a maximum of $1 {{PLURAL:$1|day|days}})',
'login'                      => 'لاگ اِن',
'nav-login-createaccount'    => 'لاگ اِن ٿيو / کاتو کوليو',
'loginprompt'                => '{{SITENAME}} ۾ لاگ اِن ٿيڻ لاءِ ڪوڪيز جي قبوليت لازمي آهي.',
'userlogin'                  => 'لاگ اِن ٿيو / کاتو کوليو',
'logout'                     => 'لاگ آئوٽ',
'userlogout'                 => 'لاگ آئوٽ',
'nologin'                    => "پنهنجو کاتو نه ٿا رکو؟ '''$1'''.",
'nologinlink'                => 'نئون کاتو کوليو',
'createaccount'              => 'کاتو کوليو',
'gotaccount'                 => "ڇا اڳي ئي کاتو رکو ٿا؟ '''$1'''.",
'gotaccountlink'             => 'لاگ اِن',
'createaccountmail'          => 'بذريعه برق ٽپال',
'loginsuccesstitle'          => 'لاگ اِن ڪامياب',
'loginsuccess'               => "'''هاڻي توهان {{SITENAME}} تي بطور \"\$1\" لاگ اِن ٿيل آهيو.'''",
'nosuchuser'                 => '"$1" نالي سان ڪو به يوزر نه آهي. هِجي چڪاسيو، يا نئون کاتو کوليو.',
'nosuchusershort'            => '"$1" نالي ڪو به يُوزر ناهي.
هِجي جي پڪ ڪندا.',
'nouserspecified'            => 'توهان کي ڪو يوزرنانءُ ڄاڻائڻو پوندو.',
'wrongpassword'              => 'ڏنل ڳجھو لفظ غير درست آهي. مهرباني ڪري ٻيهر ڪوشش ڪندا.',
'wrongpasswordempty'         => 'ڏنل ڳجھو لفظ خالي هو. مهرباني ڪري وري ڪوشش ڪندا.',
'passwordtooshort'           => 'توهان جو ڳجھو لفظ ناقابلڪار آهي يا تمام ننڍو آهي. اهو توهان جي يُوزرنانءُ کان لازماً مختلف ۽ {{PLURAL:$1|1 اکر|$1 اکرن}} کان ڊگھو هوڻ گھرجي.',
'mailmypassword'             => 'ڳجھو لفظ برق ٽپاليو',
'passwordremindertitle'      => '{{SITENAME}} لاءِ نئون عارضي ڳجھو لفظ',
'passwordremindertext'       => 'ڪنهن (شايد توهان آءِ پي پتي $1 تان) اسان کي {{SITENAME}} ($4) لاءِ نئون ڳجھو لفظ اماڻڻ جي گھُرَ ڪئي.

هاڻي يوزر "$2" لاءِ ڳجھو لفظ "$3" آهي. توهان کي هينئر ئي لاگ اِن ٿي پنهنجو ڳجھو لفظ تبديل ڪرڻ گھرجي.

جيڪڏهن اها گھُرَ اوهان نه ڪئي هئي، يا هاڻي اوهان کي پنهنجو ڳجھو لفظ ياد اچي ويو آهي ۽ توهان ان کي تبديل ڪرڻ نه ٿا چاهيو، ته توهان هن نياپي کي نظر انداز ڪندي پنهنجو پراڻو ڳجھو لفظ ئي استعمال ڪري سگھو ٿا.',
'noemail'                    => 'يُوزر "$1" جي ڪو به برق ٽپال پتو درج ٿيل ناهي.',
'passwordsent'               => 'يوزر "$1" لاءِ هڪ نئون ڳجھو لفظ برق ٽپال ذريعي اماڻيو ويو آهي.  مهرباني ڪري اهو حاصل ڪرڻ بعد لاگ اِن ٿيندا.',
'mailerror'                  => 'ٽپال اماڻڻ ۾ چُڪَ: $1',
'acct_creation_throttle_hit' => 'معاف ڪجَو، اوهان اڳي ئي $1 کاتا کولي چڪا آهيو. ان کان وڌيڪ نه ٿا کولي سگھجن.',
'accountcreated'             => 'کاتو کلي چڪو',
'accountcreatedtext'         => '$1 نالي يوزر کاتو کلي چڪو آھي.',

# Change password dialog
'retypenew' => 'نئون ڳجھو لفظ ٻيهر ٽائيپ ڪندا:',

# Edit page toolbar
'bold_sample'     => 'گهري تحرير',
'bold_tip'        => 'گهري لکت',
'italic_sample'   => 'ترڇي لکت',
'italic_tip'      => 'ترڇي لکت',
'link_sample'     => 'ڳنڍڻي جو عنوان',
'link_tip'        => 'داخلي ڳنڍڻو',
'extlink_sample'  => 'http://www.example.com ڳنڍڻي جو عنوان',
'extlink_tip'     => 'خارجي ڳنڍڻو (اڳياڙي http://  نه وساريندا)',
'headline_sample' => 'سرخي',
'headline_tip'    => 'سطح 2 جي سرخي',
'image_tip'       => 'جَڙيل فائيل',
'media_tip'       => 'فائيل جو ڳنڍڻو',
'sig_tip'         => 'توهان جي صحيح بمع اوقاتي مهر',
'hr_tip'          => 'افقي لڪير (غيرضروري استعمال کان پاسو ڪندا)',

# Edit pages
'summary'                    => 'تَتُ:',
'subject'                    => 'موضوع/سُرخي:',
'minoredit'                  => 'هيءَ هڪ معمولي ترميم آهي',
'watchthis'                  => 'هيءُ صفحو سانڍيو',
'savearticle'                => 'صفحو سانڍيو',
'preview'                    => 'پيش نگاهه',
'showpreview'                => 'پيش نگاهه',
'showdiff'                   => 'تبديليون ڏيکاريو',
'anoneditwarning'            => "'''خبردار:''' توهان لاگ اِن ٿيل ناهيو.
هن صفحي جي سوانح ۾ توهان جو آءِ پي پتو درج ڪيو ويندو.",
'missingcommenttext'         => 'براءِ مهرباني هيٺ پنهنجا تاثرات درج ڪندا.',
'summary-preview'            => 'تت تي پيش نگاهه:',
'blockedtitle'               => 'يُوزر بندشيل آهي.',
'blockedtext'                => "'''توهان جي يوزرنانءُ يا آءِ پي کي بندشيو ويو آهي.'''

بندش $1 هنئي. جڏهن ته ڄاڻايل سبب ''$2'' آهي.


* بندش جو آغاز: $8
* بندش جو انجام: $6
* بندش جو هدف: $7

اهڙي روڪ تي بحث ڪرڻ لاءِ توهان $1 يا ڪنهن ٻي [[{{MediaWiki:Grouppage-sysop}}|منتظم]] سان رابطو ڪري سگھو ٿا. جيڪڏهن توهان جو درست [[Special:ترجيحات|کاتو ترجيحات]] ۾ درست برق ٽپال پتو درج ٿيل نه آهي ته توهان 'هن يوزر کي برق ٽپال ڪريو' وارو فيچر نه ٿا 
You cannot use the 'e-mail this user' feature unless a valid e-mail address is specified in your [[Special:Preferences|account preferences]] and you have not been blocked from using it.
استعمال ڪري سگھو. توهان جو هاڻوڪو آءِ پي پتو $3 آهي، ۽ بندش سڃاڻپ $5 آهي. مهرباني ڪري ڪنهن به پڇا ڳاڇا يا لهوچڙ  لاءِ انهن مان ڪنهن هڪ يا ٻنهي جو حوالو ڏيندا.",
'blockednoreason'            => 'سبب اڻڄاڻايل',
'accmailtitle'               => 'ڳجھو لفظ اماڻجي چڪو.',
'newarticle'                 => '(نئون)',
'newarticletext'             => "توهان اهڙي صفحي جو ڳنڍڻو وٺي هتي پهتا آهيو، جيڪو اڃا وجود نه ٿو رکي. اهڙو صفحو جوڙڻ لاءِ هيٺين باڪس ۾ ٽائيپ ڪرڻ شروع ڪريو (وڌيڪ ڄاڻڻ لاءِ [[{{MediaWiki:Helppage}}|امدادي صفحو]] ڏسندا). جي توهان هتي غلطيءَ ۾ اچي ويا آهيو ته رڳو پنهنجي جهانگُوءَ جو '''back''' بٽڻ ڪلڪ ڪندا.",
'previewnote'                => "'''هيءَ محظ پيش نگاهه آهي، ترميمون اڃا سانڍجون ناهن!'''",
'editing'                    => 'زير ترميم $1',
'editingsection'             => 'زير ترميم $1 (سيڪشن)',
'copyrightwarning'           => "ياد رکندا ته {{SITENAME}} لاءِ سموريون ڀاڱيداريون $2 تحت پڌريون ڪجن ٿيون (تفصيلن لاءِ $1 ڏسندا). اوهان جي تحرير کي {{SITENAME}} جي قائدن تحت ترميمي سگهجي ٿو. جيڪڏهن اوهان نه ٿا چاهيو ته اوهان جي لکڻين کي بي رحميءَ سان ترميميو وڃي يا ورهائي عام ڪيو وڃي ته پوءِ پنهنجي لکڻي هتي جمع نه ڪرايو. پنهنجو مواد هتي جمع ڪرڻ جو مطلب هوندو ته توهان کي جمع ڪرايل مواد جي مفت فراهمي ۽ کُليل تبديليءَ تي ڪو به اعتراز ناهي.<br />
توهان اهڙي پڪ ڏيڻ جا پابند پڻ آهيو ته توهان جو جمع ڪرايل مواد توهان جو پنهنجو لکيل آهي يا وري توهان ڪنهن مفت وسيلي تان ڪاپي ڪيو آهي.
'''تحفظيل حق ۽ واسطا رکندڙ مواد واسطيدار مالڪ کان اڳواٽ اجازت وٺڻ کان سواءِ هتي جمع نه ڪريو.'''",
'templatesused'              => 'هن صفحي تي استعمال ٿيندڙ سانچا:',
'templatesusedpreview'       => 'هن پيش نگاهه ۾ استعمال ٿيل سانچا:',
'template-protected'         => '(تحفظيل)',
'template-semiprotected'     => '(نيم تحفظيل)',
'nocreatetext'               => '{{SITENAME}} نوان صفحا سرجڻ جي روڪَ ڪئي آهي.
توهان اڳي ئي موجود صفحن کي سنواري سگھو ٿا، يا [[Special:UserLogin|لاگ اِن ٿي يا نئون کاتو کولي سگھو ٿا]].',
'recreate-moveddeleted-warn' => "'''خبردار: توهان اهڙو صفحو نئين سر سرجي رهيا آهيو جيڪو اڳي ڊاٺو ويو آهي.'''

بهتر ٿيندو ته توهان سوچي وٺو ته ڇا ان صفحي کي سنوارڻ بهتر ٿيندو.
توهآن جي سهوليت خاطر هتي ان صفحي جو ڊاٺ لاگ ميسر ڪجي ٿو:",

# History pages
'viewpagelogs'        => 'هن صفحي جا لاگ ڏسو',
'currentrev'          => 'هاڻوڪو مسودو',
'revisionasof'        => '$1 وارو پرت',
'revision-info'       => '$1 تي $2 جي ترميم بعد مسودو',
'previousrevision'    => '←اڃا پراڻو پرت',
'nextrevision'        => 'اڃا نئون پرت→',
'currentrevisionlink' => 'هاڻوڪو پرت',
'cur'                 => 'ھاڻوڪو',
'last'                => 'پويون',
'page_first'          => 'پهريون',
'page_last'           => 'آخري',
'histfirst'           => 'اوائلي ترين',
'histlast'            => 'تازوترين',
'historyempty'        => '(خالي)',

# Revision feed
'history-feed-item-nocomment' => '$2 تي $1',

# Revision deletion
'pagehist' => 'صفحي جي سوانح',

# Diffs
'history-title'           => '"$1" جي سوانح',
'difference'              => '(مسودن درميان تفاوت)',
'lineno'                  => 'سِٽَ $1:',
'compareselectedversions' => 'چونڊيل پرت ڀيٽيو',
'editundo'                => 'اڻڪريو',
'diff-multi'              => '({{PLURAL:$1|هڪ وسطي مسودو|$1 وسطي مسودا}} لڪايل.)',

# Search results
'prevn'             => 'پويان {{PLURAL:$1|$1}}',
'nextn'             => 'اڳيان {{PLURAL:$1|$1}}',
'viewprevnext'      => 'ڏسو ($1 {{int:pipe-separator}} $2) ($3)',
'searchhelp-url'    => 'Help:فهرست',
'powersearch'       => 'نفيس ڳولا',
'powersearch-redir' => 'چورڻن جي فهرست ڏيکاريو',

# Preferences page
'preferences'         => 'ترجيحات',
'mypreferences'       => 'منهنجون ترجيحات',
'changepassword'      => 'ڳجھو لفظ تبديل ڪريو',
'skin-preview'        => 'پيش نگاهه',
'datedefault'         => 'بلا ترجيحا',
'prefs-datetime'      => 'تاريخ ۽ وقت',
'allowemail'          => 'ٻين يُوزرس کان ايندڙ برق ٽپال بحال ڪريو',
'yourrealname'        => 'اصل نالو:',
'badsiglength'        => 'اها صحيح تمام وڏي آهي.
$1 {{PLURAL:$1|اکر|اکرن}} کان ننڍي هوڻ گھرجي.',
'prefs-help-realname' => 'اصل نالو اختياري آهي.
جيڪڏهن توهان اصل نالو ڄاڻائڻ جو فيصلو ٿا ڪريو، ته اهو توهان کي مڃتا ڏيڻ لاءِ ڪم آندو ويندو.',

# User rights
'userrights-reason' => 'سبب:',

# Groups
'group'      => 'گروپ:',
'group-user' => 'يوزرس',

'grouppage-sysop' => '{{ns:project}}:منتظمين',

# Rights
'right-undelete' => 'ڪو صفحو اڻڊاهيو',

# User rights log
'rightslog' => 'يُوزر حق لاگ',

# Recent changes
'nchanges'                       => '$1 {{PLURAL:$1|تبديلي|تبديليون}}',
'recentchanges'                  => 'تازيون تبديليون',
'recentchanges-feed-description' => 'ۡهن روان رسد ۾ آيل تازيون تبديليون لهو',
'rcnote'                         => "پوين {{PLURAL:$2|ڏينهن|'''$2''' ڏينهن}} ۾، يعني $3 تائين آيل {{PLURAL:$1| '''1''' تبديلي|'''$1''' تبديليون}} هيٺ پيش ڪجن ٿيون.",
'rcnotefrom'                     => "هيٺ '''$2''' کان ٿيندڙ تبديليون پيش ڪجن ٿيون ('''$1''' تائين ڏيکارجن ٿيون).",
'rclistfrom'                     => '$1 کان شروع ٿيندڙ نيون تبديليون',
'rcshowhideminor'                => '$1 معمولي ترميمون',
'rcshowhidebots'                 => '$1 بوٽس',
'rcshowhideliu'                  => '$1 لاگ اِن ٿيل يوزرس',
'rcshowhideanons'                => '$1 نامعلوم يُوزرس',
'rcshowhidepatr'                 => '$1 تاڻيل ترميمون',
'rcshowhidemine'                 => 'منهنجون ترميمون $1',
'rclinks'                        => 'پوين $2 ڏينهن ۾ آيل پويون $1 تبديليون ڏيکاريو <br />$3',
'diff'                           => 'تفاوت',
'hist'                           => 'سوانح',
'hide'                           => 'لڪايو',
'show'                           => 'ڏيکاريو',
'minoreditletter'                => 'م',
'newpageletter'                  => 'ن',
'boteditletter'                  => 'گ',

# Recent changes linked
'recentchangeslinked'          => 'لاڳاپيل تبديليون',
'recentchangeslinked-feed'     => 'لاڳاپيل تبديليون',
'recentchangeslinked-toolbox'  => 'لاڳاپيل تبديليون',
'recentchangeslinked-title'    => '"$1" سان لاڳاپيل تبديليون',
'recentchangeslinked-noresult' => 'ڄاڻايل مدي دوران ڳنڍيل صفحن ۾ ڪا به تبديلي ناهي ٿي.',

# Upload
'upload'        => 'فائيل چاڙهيو',
'uploadbtn'     => 'فائيل چاڙهيو',
'uploadlogpage' => 'چاڙهه لاگ',
'uploadedimage' => '"[[$1]]" چاڙهيو ويو',

# Special:ListFiles
'listfiles' => 'فائيل فهرست',

# File description page
'file-anchor-link'          => 'فائيل',
'filehist'                  => 'فائيل جي سوانح',
'filehist-help'             => 'ڪنهن به تاريخ/وقت تي ڪلڪ ڪري ڏسندا ته تڏڻي اهو فائيل ڪيئن هو.',
'filehist-current'          => 'هاڻوڪو',
'filehist-datetime'         => 'تاريخ/وقت',
'filehist-user'             => 'يُوزر',
'filehist-dimensions'       => 'ماپَ',
'filehist-filesize'         => 'فائيل سائيز',
'filehist-comment'          => 'تاثرات',
'imagelinks'                => 'ڳنڍڻا',
'linkstoimage'              => 'هن فائيل سان {{PLURAL:$1|هيٺيون صفحو ڳنڍيل آهي |$1 هيٺيان صفحا ڳنڍيل آهن}}:',
'nolinkstoimage'            => 'هن فائيل سان ڪو به صفحو ڳنڍيل ناهي.',
'sharedupload'              => 'هيءَ هڪ شراڪتي چاڙهه آهي، تنهنڪري ان کي ٻيون رٿائون به استعمال ڪري سگھن ٿيون.',
'uploadnewversion-linktext' => 'هن فائيل جو نئون پرت چاڙهيو',

# MIME search
'mimesearch' => 'مائيم ڳولا',

# List redirects
'listredirects' => 'چورڻن جي فهرست',

# Unused templates
'unusedtemplates' => 'اڻ استعماليل سانچا',

# Random page
'randompage' => 'بلاترتيب صفحو',

# Random redirect
'randomredirect' => 'بلا ترتيب چورڻو',

# Statistics
'statistics' => 'انگ اکر',

'disambiguations' => 'سلجھائپ صفحا',

'doubleredirects' => 'ٻٽا چورڻا',

'brokenredirects'        => 'ٽٽل چورڻا',
'brokenredirects-edit'   => 'سنواريو',
'brokenredirects-delete' => 'ڊاهيو',

'withoutinterwiki' => 'ڪنهن به ٻي ٻوليءَ سان نه ڳنڍيل صفحا',

'fewestrevisions' => 'گھٽانگھٽ ترميميل صفحا',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|ٻاٽڻ|ٻاٽڻيون}}',
'nlinks'                  => '$1 {{PLURAL:$1|ڳنڍڻو|ڳنڍڻا}}',
'nmembers'                => '$1 {{PLURAL:$1|رڪن|رڪنَ}}',
'lonelypages'             => 'يتيم صفحا',
'uncategorizedpages'      => 'اڻ زمريل صفحا',
'uncategorizedcategories' => 'اڻزمرايل زمرا',
'uncategorizedimages'     => 'اڻزمرايل فائيل',
'uncategorizedtemplates'  => 'اڻزمرايل سانچا',
'unusedcategories'        => 'اڻ استعماليل زمرا',
'unusedimages'            => 'اڻ استعماليل فائيلس',
'wantedcategories'        => 'گھربل زمرا',
'wantedpages'             => 'گھربل صفحا',
'mostlinked'              => 'صفحن سان وڌانوڌ ڳنڍيندڙ',
'mostlinkedcategories'    => 'زمرن سان وڌانوڌ ڳنڍيل',
'mostlinkedtemplates'     => 'گھڻي کان گھڻا سانچا رکندڙ',
'mostcategories'          => 'گھڻي کان گھڻا زمرا رکندڙ صفحا',
'mostimages'              => 'وڌانوڌ ڳنڍيندڙ فائيل',
'mostrevisions'           => 'وڌانوڌ ترميميل صفحا',
'prefixindex'             => 'اڳياڙي ڏسڻي',
'shortpages'              => 'مختصر صفحا',
'longpages'               => 'طويل صفحا',
'deadendpages'            => 'اڻ ڳنڍيندڙ صفحا',
'protectedpages'          => 'تحفظيل صفحا',
'listusers'               => 'يُوزر فهرست',
'newpages'                => 'نوان صفحا',
'ancientpages'            => 'قديم ترين صفحا',
'move'                    => 'چوريو',
'movethispage'            => 'هيءُ صفحو چوريو',

# Book sources
'booksources' => 'ڪتابي وسيلا',

# Special:Log
'specialloguserlabel'  => 'يُوزر:',
'speciallogtitlelabel' => 'عنوان:',
'log'                  => 'لاگس',
'all-logs-page'        => 'سڀئي لاگس',

# Special:AllPages
'allpages'       => 'سڀ صفحا',
'alphaindexline' => '$1 کان $2',
'nextpage'       => 'اڳيون صفحو ($1)',
'prevpage'       => 'پويون صفحو ($1)',
'allpagesfrom'   => 'ھتان شروع ٿيندڙ صفحا نمايو',
'allarticles'    => 'سمورا مضمون',
'allpagesprev'   => 'اڳوڻو',
'allpagessubmit' => 'ھلو',
'allpagesprefix' => 'صفحا نمايو بمع اڳياڙي:',

# Special:Categories
'categories' => 'زمرا',

# E-mail user
'emailuser' => 'هن يوزر کي برق ٽپال اماڻيو',

# Watchlist
'watchlist'         => 'منهنجي ٽيٽ فهرست',
'mywatchlist'       => 'منهنجي ٽيٽ فهرست',
'addedwatchtext'    => "صفحو بعنوان \"[[:\$1]]\" اوهان جي [[Special:Watchlist|ٽيٽ فهرست]] ۾ شامل ٿي ويو. استقبالي تبديليون هتي درج ٿينديون وينديون. اهو صفحو [[Special:RecentChanges|تازين تبديلين]] واري صفحي تي '''گهرن''' اکرن ۾ نمايان ڪري ڏيکاريو ويندو. جيڪڏهن اوهان اهو سڀ نه ٿا چاهيو ته '''اڻ ٽيٽيو''' تي ڪلڪ ڪريو.",
'removedwatchtext'  => 'صفحو بعنوان "[[:$1]]" توهان جي ٽيٽ فهرست مان هٽي چڪو آهي.',
'watch'             => 'ٽيٽيو',
'watchthispage'     => 'هيءُ صفحو ٽيٽيو',
'unwatch'           => 'اڻ ٽيٽيو',
'unwatchthispage'   => 'ٽيٽڻ ڇڏيو',
'watchlist-details' => 'مباحثي صفحن کان سواءِ {{PLURAL:$1|$1 صفحو|$1 صفحا}} ٽيٽيل.',
'wlshowlast'        => 'پوين $1 ڪلاڪن $2 ڏينهن جا $3 ڏيکاريو',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'ٽيٽيندي...',
'unwatching' => 'اڻ ٽيٽيندي...',

'enotif_newpagetext' => 'هيءُ هڪ نئون صفحو آهي.',
'changed'            => 'تبديل ٿي ويو',
'created'            => 'ٺهي چڪو',

# Delete
'deletepage'            => 'صفحو ڊاهيو',
'confirm'               => 'پڪ ڪريو',
'historywarning'        => 'خبردار: جيڪو صفحو توهان ڊاهڻ وارا آهيو، تنهن جي هڪ سوانح آهي:',
'confirmdeletetext'     => 'توهان هڪ صفحي کي ان جي سموري سوانح سميت ڊاهڻ وارا آهيو. مهرباني ڪري پڪ ڪندا ته توهان اهو ئي ڪرڻ گھرو ٿا، ۽ اهو ته توهان ان جي نتيجن کان واقف آهيو، ۽ اهو پڻ ته توهان اهو ڪم [[{{MediaWiki:Policy-url}}|پاليسي]]ءَ مطابق ڪري رهيا آهيو.',
'actioncomplete'        => 'ڪم پُورو',
'deletedtext'           => '"$1" ڊهي چڪو آهي.
تازو ڊاٺل صفحن جي فهرست لاءِ $2 ڏسندا.',
'dellogpage'            => 'ڊاٺ لاگ',
'deletecomment'         => 'سبب:',
'deleteotherreason'     => 'اڃا ڪو ٻيو سبب:',
'deletereasonotherlist' => 'ٻيو سبب',

# Rollback
'rollbacklink' => 'واپس ورايو',

# Protect
'protectlogpage'              => 'تحفظ لاگ',
'prot_1movedto2'              => '[[$1]] کي چوري [[$2]] تي رکيو ويو',
'protect-legend'              => 'تحفظڻ جي پڪ ڪريو',
'protectcomment'              => 'سبب:',
'protectexpiry'               => 'اختتام:',
'protect_expiry_invalid'      => 'انجامي مدو ناقابلڪار آهي.',
'protect_expiry_old'          => 'انجامي مدو ماضيءَ ۾ آهي.',
'protect-text'                => "توهان '''$1''' صفحي جي تحفظاتي سطح ڏسي ۽ بدلائي سگھو ٿا.",
'protect-locked-access'       => "توهان جو کاتو صفحن جي تحفظاتي سطح تبديلي ڪرڻ جا اختيار نه ٿو رکي. هيٺ صفحي جون وقوعات (سيٽڱس) پيش ڪجن ٿيون '''$1''':",
'protect-cascadeon'           => 'هيءُ صفحو في الوقت تحفظيل آهي، ڇاڪاڻ ته اهو هيٺين {{PLURAL:$1|صفحي|صفحن}} جو حصو آهي، جنهن تي تحفظ در تحفظ لاڳو ٿيل آهي.',
'protect-fallback'            => '"$1" جي اجازت گھرجي',
'protect-level-autoconfirmed' => 'غيرکاتيدار يُوزرس کي بندشيو',
'protect-level-sysop'         => 'صرف منتظمين',
'protect-summary-cascade'     => 'تحفظ در تحفظ',
'protect-cascade'             => 'هن صفحي ۾ شامل صفحن کي تحفظيو (تحفظ در تحفظ)',
'protect-cantedit'            => 'توهان هن صفحي جي تحفظاتي سطح نٿا بدلائي سگھو، ڇاڪاڻ ته توهان ان کي سنوارڻ جي اجازت نٿا رکو.',
'protect-expiry-options'      => '2 ڪلاڪ:2 hours،1 ڏينهن:1 day،3 ڏينهن:3 days،1 هفتو:1 week،2 هفتا:2 weeks،1 مهينو:1 month،3 مهينا:3 months،6 مهينا:6 months،1 سال:1 year،لامحدود:infinite',
'restriction-type'            => 'اجازتنامو:',
'restriction-level'           => 'روڪ سطح:',
'pagesize'                    => '(ٻاٽڻيون)',

# Undelete
'undeletebtn'          => 'بحاليو',
'undelete-error-short' => 'هيءُ فائيل اڻڊاهيندي چُڪَ ٿي آهي: $1',

# Namespace form on various pages
'namespace'      => 'نانءُ پولار:',
'invert'         => 'چونڊ ابتيو',
'blanknamespace' => '(مُک)',

# Contributions
'contributions' => 'يوزر جون ڀاڱيداريون',
'mycontris'     => 'منهنجون ڀاڱيداريون',
'contribsub2'   => 'براءِ $1 ($2)',
'uctop'         => '(سِرُ)',
'month'         => 'مهينو (۽ اڳوڻيون):',
'year'          => 'سال (۽ اڳوڻيون):',

'sp-contributions-newbies-sub' => 'نون کاتن لاءِ',
'sp-contributions-blocklog'    => 'بنسش لاگ',
'sp-contributions-talk'        => 'بحث',

# What links here
'whatlinkshere'       => 'هتان ڇا ڳنڍيل آهي',
'whatlinkshere-title' => '$1 سان ڳنڍيل صفحا',
'linkshere'           => "هيٺيان صفحا '''[[:$1]]''' سان ڳنڍيل آهن:",
'nolinkshere'         => "'''[[:$1]]''' سان ڪو به صفحو ڳنڍيل ناهي.",
'isredirect'          => 'چورڻو صفحو',
'istemplate'          => 'شموليت',
'whatlinkshere-prev'  => '{{PLURAL:$1|پويون|پويون $1}}',
'whatlinkshere-next'  => '{{PLURAL:$1|اڳيون|اڳيان $1}}',
'whatlinkshere-links' => '← ڳنڍڻا',

# Block/unblock
'blockip'                  => 'يُوزر کي روڪيو',
'ipboptions'               => '2 ڪلاڪ:2 hours،1 ڏينهن:1 day،3 ڏينهن:3 days،1 هفتو:1 week،2 هفتا:2 weeks،1 مهينو:1 month،3 مهينا:3 months،6 مهينا:6 months،1 سال:1 year،لامحدود:infinite',
'badipaddress'             => 'ناقابلڪار آءِ پي پتو',
'ipblocklist'              => 'بندشيل يوزرنانءُ ۽ آءِ پي پتا',
'infiniteblock'            => 'لامحدود',
'anononlyblock'            => 'فقط نامعلوم',
'noautoblockblock'         => 'خودڪار بندش روڪيل',
'createaccountblock'       => 'کاتو کولڻ جي روڪَ ٿيل',
'blocklink'                => 'بندشيو',
'unblocklink'              => 'اڻبندشيو',
'contribslink'             => 'ڀاڱيداريون',
'blocklogpage'             => 'بندش لاگ',
'blocklogentry'            => '"[[$1]]" کي بندشيو ويو $2 $3 جي عرصي لاء',
'block-log-flags-anononly' => 'فقط نامعلوم يوزرس',

# Move page
'movepagetext'            => "هيٺيون فارم استعمال ڪندي ڪنهن صفحي کي نئون عنوان ڏئي سگھجي ٿو، جنهن سان سمورو صفحو نئين عنوان ڏانهن هليو ويندو. اڳوڻو عنوان نئين عنوان ڏانهن چورڻو بنجي ويندو. ان ڳالهه جي پڪ ڪرڻ ذميواري توهان تي آهي ته ڳنڍڻا اتي ئي وٺي وڃن ٿا جتي انهن کي وٺي وڃڻ گھرجي.

ياد رکندا ته جيڪڏهن نئين عنوان سان اڳي ئي ڪو مضمون موجود آهي ته پوءِ صفحو '''نه''' چوريو ويندو، سوا ان جي ته موجوده صفحو محظ خالي آهي يا ڪا به سوانح نه رکندڙ ڪو چورڻو آهي.

'''خبردار!'''
اها هڪ مقبول صفحي لاءِ ڪا غير متوقه ۽ انتهائي اڻوڻندڙ تبديلي ثابت ٿي سگھي ٿي؛ براءِ مهرباني اڳتي وڌڻ کان اڳ پڪ ڪندا ته توهان اها تبديلي آڻڻ جي نتيجن کان چڱيءَ ريت واقف آهيو.",
'movepagetalktext'        => 'واسطيدار مباحثي صفحو پاڻهي ئي چوريو ويندو ماهسوا:

*نئين عنوان سان هڪ اڻ پورو يعني غير خالي مباحثي صفحو اڳي ئي وجود رکندو هجي، يا
*توهان هيٺين باڪس کي اڻ ٽِڪ ڪريو

انهن صورتن ۾، جيڪڏهن توهان چاهيو ته صفحي کي پاڻ چوري يا ضمائي سگھو ٿا.',
'movearticle'             => 'صفحو چوريو:',
'newtitle'                => 'نئين عنوان ڏانهن:',
'move-watch'              => 'هيءُ صفحو ٽيٽيو',
'movepagebtn'             => 'صفحو چوريو',
'pagemovedsub'            => 'چورڻ جو عمل ڪامياب ٿيو',
'movepage-moved'          => '\'\'\'"$1" کي چوري "$2" تي رکيو ويو آهي\'\'\'',
'articleexists'           => 'ان نالي سان صفحو اڳي ئي وجود رکي ٿو، يا ته توهان جو ڏنل نالو ناقابلڪار آهي.',
'talkexists'              => "
'''موادي صفحو پاڻ ته ڪاميابيءَ سان چُري ويو، پر لاڳاپيل مباحثي صفحو چُري نه سگھيو ڇاڪاڻ ته نئين عنوان تي اڳي ئي هڪ مباحثي صفحو موجود آهي. براءِ مهرباني انهن ٻنهي هٿ سان ضمائيندا.",
'movedto'                 => 'چوريو ويو برسر',
'movetalk'                => 'لاڳاپيل مباحثي صفحو چوريو',
'movelogpage'             => 'چورڻ لاگ',
'movereason'              => 'سبب:',
'revertmove'              => 'ورايو',
'delete_and_move_confirm' => 'جي ها، صفحو ڊاهيو',
'delete_and_move_reason'  => 'چورڻ جو عمل ممڪن بنائڻ لاءِ ڊاٺو ويو',

# Export
'export' => 'صفحا برآمديو',

# Namespace 8 related
'allmessages'        => 'سرشتائي نياپا',
'allmessagesname'    => 'نالو',
'allmessagescurrent' => 'موجوده تحرير',

# Thumbnails
'thumbnail-more' => 'وڏو ڪريو',

# Import log
'importlogpage' => 'درآمد لاگ',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'منهنجو تعارفي صفحو',
'tooltip-pt-mytalk'               => 'منهنجو مباحثي صفحو',
'tooltip-pt-preferences'          => 'منهنجون ترجيحات',
'tooltip-pt-watchlist'            => 'توهان جي ٽيٽ فهرست ۾ شامل صفحا',
'tooltip-pt-mycontris'            => 'منهنجون ڀاڱيداريون',
'tooltip-pt-login'                => 'توهان کي همٿائجي ٿو ته توهان لاگ اِن ٿيو، بهرحال اهو لازمي ناهي.',
'tooltip-pt-logout'               => 'لاگ آئوٽ',
'tooltip-ca-talk'                 => 'موادي صفحي تي بحث',
'tooltip-ca-edit'                 => 'توهان هيءُ صفحو سنواري سگھو ٿا. مهرباني ڪري سانڍڻ کان اڳ پيش نگاهه جو اختيار استعمال ڪندا.',
'tooltip-ca-addsection'           => 'هن بحث تي تاثرات درج ڪرايو',
'tooltip-ca-viewsource'           => 'هيءُ صفحو تحفظيل آهي. توهان ان جو ڪوڊ ڏسي سگھو ٿا.',
'tooltip-ca-protect'              => 'هيءُ صفحو تحفظيو',
'tooltip-ca-delete'               => 'هيءُ صفحو ڊاهيو',
'tooltip-ca-move'                 => 'هيءُ صفحو چوريو',
'tooltip-ca-watch'                => 'هيءُ صفحو پنهنجي ٽيٽ فهرست ۾ شامل ڪريو',
'tooltip-ca-unwatch'              => 'هيءُ صفحو پنهنجي ٽيٽ فهرست مان هٽايو',
'tooltip-search'                  => 'جھڙتيو {{SITENAME}}',
'tooltip-n-mainpage'              => 'مک صفحو گھمو',
'tooltip-n-portal'                => 'هن رٿا بابت، توهان ڇا ٿا ڪري سگھو، ڪهڙي شَي ڪٿي ملندي',
'tooltip-n-currentevents'         => 'تازن واقعن تي تفصيلي ڄاڻ لهو',
'tooltip-n-recentchanges'         => 'هن وڪيءَ ۾ تازين تبديلين جي فهرست.',
'tooltip-n-randompage'            => 'بلاترتيب ڪو به صفحو اتاريو',
'tooltip-n-help'                  => 'ڳولي لهڻ جي جاءِ.',
'tooltip-t-whatlinkshere'         => 'هتان ڳنڍيل سمورا وڪي صفحا',
'tooltip-t-contributions'         => 'هن يُوزر جون ڀاڱيداريون ڏسو',
'tooltip-t-emailuser'             => 'هن يُوزر کي برق ٽپال اماڻيو',
'tooltip-t-upload'                => 'فائيل چاڙهيو',
'tooltip-t-specialpages'          => 'سڀني خاص صفحن جي فهرست',
'tooltip-t-print'                 => 'هن صفحي جو ڇاپائتو پرت',
'tooltip-ca-nstab-user'           => 'هن جو يُوزر صفحو ڏسو',
'tooltip-ca-nstab-project'        => 'رٿائي صفحو ڏسو',
'tooltip-ca-nstab-image'          => 'هن فائيل جو صفحو ڏسو',
'tooltip-ca-nstab-template'       => 'سانچو ڏسو',
'tooltip-ca-nstab-help'           => 'امدادي صفحو ڏسو',
'tooltip-ca-nstab-category'       => 'هن زمري جو صفحو ڏسو',
'tooltip-minoredit'               => 'ان کي هڪ معمولي ترميم ڄاڻايو',
'tooltip-save'                    => 'پنهنجون ڪيل تبديليون سانڍيو',
'tooltip-preview'                 => 'سانڍڻ کان اڳ براءِ مهرباني پنهنجي تبديلين تي پيش نگاهه وجھندا!',
'tooltip-diff'                    => 'پنهنجون ڪيل تبديليون ڏسو.',
'tooltip-compareselectedversions' => 'هن صفحي جن ٻن چونڊيل پرتن درميان تفاوت ڏسو.',
'tooltip-watch'                   => 'هيءُ صفحو پنهنجي ٽيٽ فهرست ۾ شامل ڪريو',

# Attribution
'anonymous' => '{{SITENAME}} جا نامعلوم يوزرس',

# Browsing diffs
'previousdiff' => '← اڳوڻو تفاوت',
'nextdiff'     => 'نئون تفاوت -->',

# Media information
'file-info-size' => '$1 × $2 عڪسلون، فائيل سائيز: $3، MIME ٽائيپ: $4',
'file-nohires'   => 'اڃا سنهو تحلل ميسر ناهي.',
'svg-long-desc'  => 'ايس وي جي فائيل، اٽڪل $1 × $2 عڪسلون، فائيل سائيز: $3',
'show-big-image' => 'سنهو ترين تحلل',

# Special:NewFiles
'newimages' => 'نون فائيلن جي گيلري',
'noimages'  => 'ڏسڻ لاءِ ڪجھه ناهي.',
'bydate'    => 'تاريخوار',

# Metadata
'metadata'          => 'اعدادِ اعداد',
'metadata-expand'   => 'توسيعي تفصيل ڏيکاريو',
'metadata-collapse' => 'توسيعي تفصيل لڪايو',

# EXIF tags
'exif-imagewidth'       => 'ويڪر',
'exif-imagelength'      => 'اوچائي',
'exif-bitspersample'    => 'ٻٽڻيون في جُز',
'exif-samplesperpixel'  => 'جزن جو تعداد',
'exif-xresolution'      => 'افقي تحلل',
'exif-yresolution'      => 'عمودي تحلل',
'exif-stripoffsets'     => 'عڪسي اعداد جي مڪانيت',
'exif-imagedescription' => 'عڪس عنوان',
'exif-saturation'       => 'رچاءُ',
'exif-gpslatitude'      => 'ويڪرائي ڦاڪَ',
'exif-gpslongituderef'  => 'اڀرندي يا الهندي ڊگھائي ڦاڪَ',
'exif-gpslongitude'     => 'ڊگھائي ڦاڪَ',
'exif-gpstrack'         => 'چرپر جو طرف',

'exif-unknowndate' => 'نامعلوم تاريخ',

'exif-orientation-3' => '180° موڙيل',

'exif-componentsconfiguration-0' => 'وجود نه ٿو رکي',

'exif-lightsource-0'   => 'نامعلوم',
'exif-lightsource-255' => 'روشنيءَ جو ٻيو ذريعو',

'exif-focalplaneresolutionunit-2' => 'انچ',

'exif-scenecapturetype-0' => 'معياري',

'exif-subjectdistancerange-0' => 'نامعلوم',
'exif-subjectdistancerange-3' => 'ڏورانهين نگاهه',

# Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-s' => 'ڏاکڻي ويڪرائي ڦاڪَ',

# Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => 'اڀرندي ڊگھائي ڦاڪَ',

'exif-gpsmeasuremode-2' => '2-رخي ماپ',
'exif-gpsmeasuremode-3' => '3-رخي ماپ',

# Pseudotags used for GPSSpeedRef
'exif-gpsspeed-k' => 'ڪلوميٽر في ڪلاڪ',
'exif-gpsspeed-m' => 'ميل في ڪلاڪ',
'exif-gpsspeed-n' => 'ڳنڍيون',

# External editor support
'edit-externally'      => 'هيءُ فائيل ڪنهن خارجي منتقڪريءَ سان سنواريو',
'edit-externally-help' => 'وڌيڪ معلومات لاءِ [//www.mediawiki.org/wiki/Manual:External_editors هدايتون براءِ تنصيبڪاري] ڏسندا.',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'سڀ',
'namespacesall' => 'سڀ',
'monthsall'     => 'سڀ',

# E-mail address confirmation
'confirmemail_success' => 'توھان جي برق ٽپال جي پڪ ڪئي وئي آھي. ھاڻِ توھان لاگ ان ٿي وڪيءَ جو مزو وٺي سگھو ٿا',

# Delete conflict
'recreate' => 'ورسجيو',

# Watchlist editing tools
'watchlisttools-view' => 'لاڳاپيل تبديليون ڏسو',
'watchlisttools-edit' => 'ٽيٽ فهرست ڏسو ۽ سنواريو',
'watchlisttools-raw'  => 'ڪچي ٽيٽ فهرست سنواريو',

# Special:Version
'version' => 'ورزن',

# Special:SpecialPages
'specialpages'             => 'خاص صفحا',
'specialpages-group-users' => 'يوزرس ۽ حق',

);
