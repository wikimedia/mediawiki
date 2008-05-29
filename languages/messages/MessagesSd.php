<?php
/** Sindhi (سنڌي)
 *
 * @ingroup Language
 * @file
 *
 * @author SPQRobin
 * @author Siebrand
 * @author Aursani
 * @author Jon Harald Søby
 * @author Nike
 */

$rtl = true;

$messages = array(
# User preference toggles
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

'skinpreview' => '(پيش نگاهه)',

# Dates
'sunday'     => 'آچر',
'monday'     => 'سومر',
'tuesday'    => 'اڱارو',
'wednesday'  => 'اربع',
'thursday'   => 'خميس',
'friday'     => 'جمعو',
'saturday'   => 'ڇنڇر',
'sun'        => 'آچر',
'mon'        => 'سومر',
'tue'        => 'اڱارو',
'wed'        => 'اربع',
'thu'        => 'خميس',
'fri'        => 'جمعو',
'sat'        => 'ڇنڇر',
'january'    => 'جنوري',
'february'   => 'فيبروري',
'april'      => 'اپريل',
'june'       => 'جُونِ',
'august'     => 'آگسٽ',
'april-gen'  => 'اپريل',
'august-gen' => 'آگسٽ',
'apr'        => 'اپريل',
'jun'        => 'جُونِ',
'aug'        => 'آگسٽ',
'sep'        => 'سيپٽمبر',
'oct'        => 'آڪٽوبر',

# Categories related messages
'subcategories'            => 'ذيلي زمرا',
'category-media-header'    => ' "$1" زمري اندر ذريعات',
'hidden-category-category' => 'لڪل زمرا', # Name of the category where hidden categories will be listed
'listingcontinuesabbrev'   => 'جاري..',

'about'          => 'بابت',
'article'        => 'مسوَدو',
'qbbrowse'       => 'جھانگيو',
'qbedit'         => 'سنواريو',
'qbpageoptions'  => 'هيءُ صفحو',
'qbspecialpages' => 'خاص صفحا',
'moredotdotdot'  => 'اڃا...',
'mypage'         => 'منهنجو صفحو',
'mytalk'         => 'مون سان ڳالهه',
'and'            => '۽',

'errorpagetitle'    => 'چُڪَ',
'tagline'           => '{{سرزميننانءُ}} کان',
'search'            => 'ڳول',
'go'                => 'کوليو',
'history'           => 'صفحي جي سوانح',
'history_short'     => 'تاريخ',
'info_short'        => 'معلومات',
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
'postcomment'       => 'تاثرات درج ڪريو',
'articlepage'       => 'مسودو ڏسو',
'talk'              => 'بحث',
'userpage'          => 'يوزر صفحو ڏسو',
'projectpage'       => 'رٿائي صفحو ڏسو',
'imagepage'         => 'ذريعاتي صفحو ڏسو',
'mediawikipage'     => 'نياپي جو صفحو ڏسو',
'templatepage'      => 'سانچي جو صفحو ڏسو',
'viewhelppage'      => 'امدادي صفحو ڏسو',
'categorypage'      => 'زمراتي صفحو ڏسو',
'otherlanguages'    => 'ٻين ٻولين ۾',
'redirectpagesub'   => 'چوريل صفحو',
'viewcount'         => 'هيءُ صفحو {{PLURAL:$1|دفعو|$1 دفعا}} ڏسجي چڪو آهي.',
'protectedpage'     => 'تحفظيل صفحو',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => '{{SITENAME}} بابت',
'aboutpage'            => 'Project:بابت',
'copyright'            => 'سمورو مواد $1 تحت ميسر ڪجي ٿو',
'copyrightpagename'    => '{{SITENAME}} حق ۽ واسطا',
'copyrightpage'        => '{{ns:project}}:حق ۽ واسطا',
'currentevents'        => 'ھاڻوڪا واقعا',
'currentevents-url'    => 'Project: اعداد',
'disclaimers'          => 'غيرجوابداريناما',
'faq'                  => 'ڪپوس',
'faqpage'              => 'Project:ڪپوس',
'mainpage'             => 'مُک صفحو',
'mainpage-description' => 'مُک صفحو',
'policy-url'           => 'Project:پاليسي',

'youhavenewmessages' => 'توهان لاءِ $1 ($2) آهن.',
'newmessageslink'    => 'نوان نياپا',
'editsection'        => 'سنواريو',
'editold'            => 'سنواريو',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'صفحو',
'nstab-special'   => 'خاص',
'nstab-image'     => 'فائيل',
'nstab-mediawiki' => 'نياپو',
'nstab-template'  => 'سانچو',

# General errors
'databaseerror'       => 'اعدادخاني ۾ چڪ',
'readonly'            => 'اعدادخانو بنديل',
'missingarticle-diff' => '(تفاوت: $1، $2)',
'internalerror'       => 'اندروني خرابي',
'viewsource'          => 'ڪوڊ ڏسو',
'viewsourcefor'       => 'براءِ $1',
'viewsourcetext'      => 'توهان هن صفحي جو ڪوڊ ڏسي ۽ نقل ڪري سگھو ٿا:',
'ns-specialprotected' => 'خاص صفحا سنواري نٿا سگھجن.',

# Login and logout pages
'logouttitle'                => 'يوزر لاگ آئوٽ',
'yourname'                   => 'يُوزرنانءُ:',
'yourpassword'               => 'ڳجھو لفظ:',
'logout'                     => 'لاگ آئوٽ',
'createaccount'              => 'کاتو کوليو',
'gotaccountlink'             => 'لاگ اِن',
'createaccountmail'          => 'بذريعه برق ٽپال',
'yourrealname'               => 'اصل نالو:',
'badsiglength'               => 'اها صحيح تمام وڏي آهي.
$1 {{PLURAL:$1|اکر|اکرن}} کان ننڍي هوڻ گھرجي.',
'loginsuccesstitle'          => 'لاگ اِن ڪامياب',
'nouserspecified'            => 'توهان کي ڪو يوزرنانءُ ڄاڻائڻو پوندو.',
'mailerror'                  => 'ٽپال اماڻڻ ۾ چُڪَ: $1',
'acct_creation_throttle_hit' => 'معاف ڪجَو، اوهان اڳي ئي $1 کاتا کولي چڪا آهيو. ان کان وڌيڪ نه ٿا کولي سگھجن.',
'accountcreated'             => 'کاتو کلي چڪو',
'accountcreatedtext'         => '$1 نالي يوزر کاتو کلي چڪو آھي.',

# Edit page toolbar
'bold_sample' => 'گهري تحرير',
'hr_tip'      => 'افقي لڪير (غيرضروري استعمال کان پاسو ڪندا)',

# Edit pages
'savearticle'          => 'صفحو سانڍيو',
'missingcommenttext'   => 'براءِ مهرباني هيٺ پنهنجا تاثرات درج ڪندا.',
'blockedtitle'         => 'يُوزر بندشيل آهي.',
'blockednoreason'      => 'سبب اڻڄاڻايل',
'accmailtitle'         => 'ڳجھو لفظ اماڻجي چڪو.',
'templatesusedpreview' => 'هن پيش نگاهه ۾ استعمال ٿيل سانچا:',

# History pages
'viewpagelogs' => 'هن صفحي جا لاگ ڏسو',
'currentrev'   => 'هاڻوڪو مسودو',
'cur'          => 'ھاڻوڪو',
'page_first'   => 'پهريون',
'histfirst'    => 'اوائلي ترين',
'histlast'     => 'تازوترين',
'historyempty' => '(خالي)',

# Revision deletion
'pagehist' => 'صفحي جي سوانح',

# Diffs
'difference' => '(مسودن درميان تفاوت)',

# Search results
'viewprevnext'      => 'ڏسو ($1) ($2) ($3)',
'powersearch-redir' => 'چورڻن جي فهرست ڏيکاريو',

# Preferences page
'preferences'    => 'ترجيحات',
'changepassword' => 'ڳجھو لفظ تبديل ڪريو',
'datedefault'    => 'بلا ترجيحا',
'datetime'       => 'تاريخ ۽ وقت',
'allowemail'     => 'ٻين يُوزرس کان ايندڙ برق ٽپال بحال ڪريو',

# User rights
'userrights-reason' => 'تبديليءَ جو سبب:',

# Groups
'group'      => 'گروپ:',
'group-user' => 'يوزرس',

# Rights
'right-undelete' => 'ڪو صفحو اڻڊاهيو',

# Recent changes
'rcshowhidebots' => '$1 بوٽس',
'rcshowhideliu'  => '$1 لاگ اِن ٿيل يوزرس',
'hist'           => 'سوانح',

# Recent changes linked
'recentchangeslinked-title' => '"$1" سان لاڳاپيل تبديليون',

# Image description page
'sharedupload'                   => 'هيءَ هڪ شراڪتي چاڙهه آهي، تنهنڪري ان کي ٻيون رٿائون به استعمال ڪري سگھن ٿيون.',
'shareduploadduplicate-linktext' => 'ڪو ٻيو فائيل',
'noimage-linktext'               => 'اهو چاڙهيو',

# MIME search
'mimesearch' => 'مائيم ڳولا',

# List redirects
'listredirects' => 'چورڻن جي فهرست',

# Unused templates
'unusedtemplates' => 'اڻ استعماليل سانچا',

'disambiguations' => 'سلجھائپ صفحا',

'doubleredirects' => 'ٻٽا چورڻا',

'brokenredirects'        => 'ٽٽل چورڻا',
'brokenredirects-edit'   => '(سنواريو)',
'brokenredirects-delete' => '(ڊاهيو)',

'withoutinterwiki' => 'ڪنهن به ٻي ٻوليءَ سان نه ڳنڍيل صفحا',

# Miscellaneous special pages
'lonelypages'             => 'يتيم صفحا',
'uncategorizedpages'      => 'اڻ زمريل صفحا',
'uncategorizedcategories' => 'اڻزمرايل زمرا',
'uncategorizedimages'     => 'اڻزمرايل فائيل',
'uncategorizedtemplates'  => 'اڻزمرايل سانچا',
'wantedcategories'        => 'گھربل زمرا',
'wantedpages'             => 'گھربل صفحا',
'shortpages'              => 'مختصر صفحا',
'longpages'               => 'طويل صفحا',
'protectedpages'          => 'تحفظيل صفحا',
'listusers'               => 'يُوزر فهرست',
'ancientpages'            => 'قديم ترين صفحا',
'move'                    => 'چوريو',

# Special:Allpages
'allpages'       => 'سڀ صفحا',
'alphaindexline' => '$1 کان $2',
'allpagesfrom'   => 'ھتان شروع ٿيندڙ صفحا نمايو',
'allarticles'    => 'سمورا مضمون',
'allpagesprev'   => 'اڳوڻو',
'allpagessubmit' => 'ھلو',

# Special:Categories
'categories' => 'زمرا',

# E-mail user
'emailuser' => 'هن يوزر کي برق ٽپال اماڻيو',

# Watchlist
'watchlistfor'        => "(براءِ '''$1''')",
'addedwatch'          => 'ٽيٽ فھرست ۾ شامل ڪيو ويو.',
'removedwatch'        => 'ٽيٽ فهرست مان هٽايو ويو',
'watch'               => 'ٽيٽيو',
'unwatch'             => 'اڻ ٽيٽيو',
'unwatchthispage'     => 'ٽيٽڻ ڇڏيو',
'watchlist-hide-bots' => 'بوٽ جون ڪيل ترميمون لڪايو',
'watchlist-hide-own'  => 'منهنجون ڪيل ترميمون لڪايو',

'enotif_newpagetext' => 'هيءُ هڪ نئون صفحو آهي.',
'changed'            => 'تبديل ٿي ويو',
'created'            => 'ٺهي چڪو',

# Delete/protect/revert
'deletepage'            => 'صفحو ڊاهيو',
'confirm'               => 'پڪ ڪريو',
'actioncomplete'        => 'عمل مڪمل',
'deletecomment'         => 'ڊاهڻ جو سبب:',
'deleteotherreason'     => 'اڃا ڪو ٻيو سبب:',
'deletereasonotherlist' => 'ٻيو سبب',
'rollbacklink'          => 'واپس ورايو',
'protect-legend'        => 'تحفظڻ جي پڪ ڪريو',
'protectcomment'        => 'تاثرات:',
'protect-locked-access' => 'توهان جو کاتو صفحن جي تحفظاتي سطح تبديلي ڪرڻ جا اختيار نه ٿو رکي. هيٺ صفحي جون وقوعات (سيٽڱس) پيش ڪجن ٿيون <strong>$1</strong>:',
'protect-level-sysop'   => 'صرف منتظمين',
'protect-cascade'       => 'هن صفحي ۾ شامل صفحن کي تحفظيو (تحفظ در تحفظ)',
'restriction-type'      => 'اجازتنامو:',
'pagesize'              => '(ٻاٽڻيون)',

# Undelete
'undelete-error-short' => 'هيءُ فائيل اڻڊاهيندي چُڪَ ٿي آهي: $1',

# Contributions
'contributions' => 'يوزر جون ڀاڱيداريون',

# What links here
'istemplate' => 'شموليت',

# Block/unblock
'blockip'                  => 'يُوزر کي روڪيو',
'ipboptions'               => '2 ڪلاڪ:2 ڪلاڪ،1 ڏينهن:1 ڏينهن،3 ڏينهن:3 ڏينهن،1 هفتو:1 هفتو،2 هفتا:2 هفتا،1 مهينو:1 مهينو،3 مهينا:3 مهينا،6 مهينا:6 مهينا،1 سال:1 سال،لامحدود:لامحدود', # display1:time1,display2:time2,...
'badipaddress'             => 'ناقابلڪار آءِ پي پتو',
'infiniteblock'            => 'لامحدود',
'anononlyblock'            => 'فقط نامعلوم',
'noautoblockblock'         => 'خودڪار بندش روڪيل',
'createaccountblock'       => 'کاتو کولڻ جي روڪَ ٿيل',
'blocklink'                => 'بندشيو',
'unblocklink'              => 'اڻبندشيو',
'contribslink'             => 'ڀاڱيداريون',
'blocklogentry'            => '"[[$1]]" کي بندشيو ويو $2 جي عرصي لاء',
'block-log-flags-anononly' => 'فقط نامعلوم يوزرس',

# Move page
'newtitle'                => 'نئين عنوان ڏانهن:',
'move-watch'              => 'هيءُ صفحو ٽيٽيو',
'movepagebtn'             => 'صفحو چوريو',
'pagemovedsub'            => 'چورڻ جو عمل ڪامياب ٿيو',
'movereason'              => 'سبب:',
'revertmove'              => 'ورايو',
'delete_and_move_confirm' => 'جي ها، صفحو ڊاهيو',
'delete_and_move_reason'  => 'چورڻ جو عمل ممڪن بنائڻ لاءِ ڊاٺو ويو',

# Namespace 8 related
'allmessages'         => 'سرشتائي نياپا',
'allmessagesname'     => 'نالو',
'allmessagescurrent'  => 'موجوده تحرير',
'allmessagesfilter'   => 'نياپي نانءُ ڇاڻي:',
'allmessagesmodified' => 'صرف ترميم شدھ ڏيکاريو',

# Import log
'importlogpage' => 'درآمد لاگ',

# Tooltip help for the actions
'tooltip-ca-addsection' => 'هن بحث تي تاثرات درج ڪرايو',
'tooltip-n-mainpage'    => 'مک صفحو گھمو',
'tooltip-t-print'       => 'هن صفحي جو ڇاپائتو پرت',
'tooltip-ca-nstab-help' => 'امدادي صفحو ڏسو',

# Attribution
'anonymous' => '{{SITENAME}} جا نامعلوم يوزرس',

# Browsing diffs
'previousdiff' => '← اڳوڻو تفاوت',
'nextdiff'     => 'نئون تفاوت -->',

# Media information
'file-nohires'         => '<small>اڃا سنهو تحلل ميسر ناهي.</small>',
'show-big-image'       => 'سنهو ترين تحلل',
'show-big-image-thumb' => '<small>هن پيش نگاهه جي ماپ: $1 × $2 عڪسلون</small>',

# Special:Newimages
'noimages' => 'ڏسڻ لاءِ ڪجھه ناهي.',
'bydate'   => 'تاريخوار',

# Metadata
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

'exif-orientation-3' => '180° موڙيل', # 0th row: bottom; 0th column: right

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

# Pseudotags used for GPSSpeedRef and GPSDestDistanceRef
'exif-gpsspeed-k' => 'ڪلوميٽر في ڪلاڪ',
'exif-gpsspeed-m' => 'ميل في ڪلاڪ',
'exif-gpsspeed-n' => 'ڳنڍيون',

# External editor support
'edit-externally-help' => 'وڌيڪ معلومات لاءِ [http://meta.wikimedia.org/wiki/Help:External_editors هدايتون براءِ تنصيبڪاري] ڏسندا.',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'سڀ',
'imagelistall'     => 'سڀ',
'watchlistall2'    => 'سڀ',
'namespacesall'    => 'سڀ',
'monthsall'        => 'سڀ',

# E-mail address confirmation
'confirmemail_success' => 'توھان جي برق ٽپال جي پڪ ڪئي وئي آھي. ھاڻِ توھان لاگ ان ٿي وڪيءَ جو مزو وٺي سگھو ٿا',

# Delete conflict
'recreate' => 'ورسجيو',

# AJAX search
'articletitles' => "''$1'' سان شروع ٿيندڙ مضمون",

# Watchlist editing tools
'watchlisttools-view' => 'لاڳاپيل تبديليون ڏسو',

# Special:Version
'version' => 'ورزن', # Not used as normal message but as header for the special page itself

# Special:SpecialPages
'specialpages-group-users' => 'يوزرس ۽ حق',

);
