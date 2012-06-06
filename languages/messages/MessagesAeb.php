<?php
/**    زَوُن (   زَوُن)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Abanima
 * @author Csisc
 */

$messages = array(
# User preference toggles
'tog-underline' => 'ضع خطا تحت الوصلات:',
'tog-justify' => 'ساو الفقرات',
'tog-hideminor' => 'أخف التعديلات الطفيفة في أحدث التغييرات',
'tog-hidepatrolled' => 'أخف التعديلات المراجعة في أحدث التغييرات',
'tog-newpageshidepatrolled' => 'أخف الصفحات المراجعة من قائمة الصفحات الجديدة',
'tog-extendwatchlist' => 'مدد قائمة المراقبة لعرض كل التغييرات، وليس الأحدث فقط',
'tog-usenewrc' => 'استخدم أحدث التغييرات المحسنة (يتطلب جافاسكربت)',
'tog-numberheadings' => 'رقم العناوين تلقائيا',
'tog-showtoolbar' => 'أظهر شريط التحرير (يتطلب جافاسكربت)',
'tog-editondblclick' => 'عدل الصفحات عند الضغط المزدوج (جافاسكربت)',
'tog-editsection' => 'مكن تعديل الأقسام عن طريق وصلات [عدل]',
'tog-editsectiononrightclick' => 'فعل تعديل الأقسام بواسطة كبسة الفأرة اليمين على عناوين الأقسام (جافاسكريبت)',
'tog-showtoc' => 'اعرض فهرس المحتويات (للصفحات التي تحتوي على أكثر من 3 عناوين)',
'tog-rememberpassword' => 'تذكر دخولي على هذا المتصفح (إلى {{PLURAL:$1||يوم واحد|يومين|$1 أيام|$1 يومًا|$1 يوم}} كحد أقصى)',
'tog-watchcreations' => 'أضف الصفحات التي أنشئها إلى قائمة مراقبتي',
'tog-watchdefault' => 'أضف الصفحات التي أعدلها إلى قائمة مراقبتي',
'tog-watchmoves' => 'أضف الصفحات التي أنقلها إلى قائمة مراقبتي',
'tog-watchdeletion' => 'أضف الصفحات التي أحذفها إلى قائمة مراقبتي',
'tog-minordefault' => 'علم كل التعديلات طفيفة افتراضيا',
'tog-previewontop' => 'أظهر العرض المسبق قبل صندوق التحرير',
'tog-previewonfirst' => 'أظهر معاينة مع أول تعديل',
'tog-nocache' => 'عطّل تخزين المتصفح للصفحة',
'tog-enotifwatchlistpages' => 'أرسل لي رسالة إلكترونية عندما يتم تغيير صفحة في قائمة مراقبتي',
'tog-enotifusertalkpages' => 'أرسل لي رسالة إلكترونية عندما تعدل صفحة نقاشي',
'tog-enotifminoredits' => 'أرسل لي رسالة إلكترونية عن التعديلات الطفيفة للصفحات أيضا',
'tog-enotifrevealaddr' => 'أظهر عنوان بريدي الإلكتروني في رسائل الإخطار',
'tog-shownumberswatching' => 'اعرض عدد المستخدمين المراقبين',
'tog-oldsig' => 'التوقيع الحالي:',
'tog-fancysig' => 'عامل التوقيع كنص ويكي (بدون وصلة أوتوماتيكية)',
'tog-externaleditor' => 'استخدم محررا خارجيا بشكل افتراضي (للخبراء فقط، يحتاج إعدادات خاصة على حاسوبك) ([//www.mediawiki.org/wiki/Manual:External_editors مزيد من المعلومات.])',
'tog-externaldiff' => 'استخدم فرقا خارجيا بشكل افتراضي (للخبراء فقط، يحتاج إعدادات خاصة على حاسوبك) ([//www.mediawiki.org/wiki/Manual:External_editors للمزيد من المعلومات.])',
'tog-showjumplinks' => 'مكن وصلات "اذهب إلى" المساعدة',
'tog-uselivepreview' => 'استخدم الاستعراض السريع (جافاسكريبت) (تجريبي)',
'tog-forceeditsummary' => 'نبهني عند إدخال ملخص تعديل فارغ',
'tog-watchlisthideown' => 'أخف تعديلاتي من قائمة المراقبة',
'tog-watchlisthidebots' => 'أخف تعديلات البوت من قائمة المراقبة',
'tog-watchlisthideminor' => 'أخف التعديلات الطفيفة من قائمة المراقبة',
'tog-watchlisthideliu' => 'أخف تعديلات المستخدمين المسجلين من قائمة المراقبة',
'tog-watchlisthideanons' => 'أخف تعديلات المستخدمين المجهولين من قائمة المراقبة',
'tog-watchlisthidepatrolled' => 'أخف التعديلات المراجعة من قائمة المراقبة',
'tog-ccmeonemails' => 'أرسل لي نسخا من رسائل البريد الإلكتروني التي أرسلها للمستخدمين الآخرين',
'tog-diffonly' => 'لا تعرض محتوى الصفحة أسفل الفروقات',
'tog-showhiddencats' => 'أظهر التصنيفات المخفية',
'tog-norollbackdiff' => 'أزل الفرق بعد القيام باسترجاع',

'underline-always' => 'دائما',
'underline-never' => 'أبدا',
'underline-default' => 'تبعا لإعدادات المتصفح',

# Font style option in Special:Preferences
'editfont-style' => 'نمط خط منطقة التحرير:',
'editfont-default' => 'تبعا لإعدادات المتصفح',
'editfont-monospace' => 'خط ثابت العرض',
'editfont-sansserif' => 'خط بلا زوائد',
'editfont-serif' => 'خط بزوائد',

# Dates
'sunday' => 'ela7ad',
'monday' => 'elithnaine',
'tuesday' => 'etholatha',
'wednesday' => 'elirbi3a',
'thursday' => 'el5amis',
'friday' => 'eljom3a',
'saturday' => 'essibt',
'sun' => 'ela7ad',
'mon' => 'el ithnaine',
'tue' => 'ethlath',
'wed' => 'elirb3a',
'thu' => 'el5mis',
'fri' => 'ejjom3a',
'sat' => 'essibt',
'january' => 'janfi',
'february' => 'fivri',
'march' => 'mars',
'april' => 'avril',
'may_long' => 'mai',
'june' => 'juin',
'july' => 'juillia',
'august' => 'août',
'september' => 'septembre',
'october' => 'octobre',
'november' => 'novembre',
'december' => 'décembre',
'january-gen' => 'janfi',
'february-gen' => 'fivri',
'march-gen' => 'mars',
'april-gen' => 'avril',
'may-gen' => 'mai',
'june-gen' => 'juin',
'july-gen' => 'juillia',
'august-gen' => 'août',
'september-gen' => 'septembre',
'october-gen' => 'octobre',
'november-gen' => 'novembre',
'december-gen' => 'décembre',
'jan' => 'Janvi',
'feb' => 'fivri',
'mar' => 'mars',
'apr' => 'avril',
'may' => 'mai',
'jun' => 'Juin',
'jul' => 'juillia',
'aug' => 'août',
'sep' => 'septembre',
'oct' => 'octobre',
'nov' => 'novembre',
'dec' => 'décembre',

# Categories related messages
'pagecategories' => '{{PLURAL:$1|لا تصنيف|تصنيف|تصنيفان|تصنيفات}}',
'category_header' => 'صفحات تصنيف "$1"',
'subcategories' => 'التصنيفات الفرعية',
'category-media-header' => 'الوسائط في التصنيف "$1"',
'category-empty' => "''هذا التصنيف لا يحتوي حاليا على صفحات أو وسائط.''",
'hidden-categories' => '{{PLURAL:$1|لا تصنيف مخفيا|تصنيف مخفي|تصنيفان مخفيان|تصنيفات مخفية}}',
'hidden-category-category' => 'تصنيفات مخفية',
'category-subcat-count' => '{{PLURAL:$2|لا تصانيف فرعية في هذا التصنيف|هذا التصنيف فيه التصنيف الفرعي التالي فقط.|هذا التصنيف فيه {{PLURAL:$1||هذا التصنيف الفرعي|هذين التصنيفين الفرعيين|هذه ال$1 تصانيف الفرعية|هذه ال$1 تصنيفا فرعيا|هذه ال$1 تصنيف فرعي}}، من إجمالي $2.}}',
'category-subcat-count-limited' => 'هذا التصنيف فيه {{PLURAL:$1||التصنيف الفرعي التالي|التصنيفين الفرعيين التاليين|$1 تصانيف فرعية تالية|$1 تصنيفا فرعيا تاليا|$1 تصنيف فرعي تالي}}.',
'category-article-count' => '{{PLURAL:$2|لا يحتوي هذا التصنيف أي صفحات.|هذا التصنيف يحتوي على الصفحة التالية فقط.|{{PLURAL:$1||الصفحة التالية|الصفحتان التاليتان|ال$1 صفحات التالية|ال$1 صفحة التالية|ال$1 صفحة التالية}} في هذا التصنيف، من إجمالي $2.}}',
'category-article-count-limited' => '{{PLURAL:$1||الصفحة التالية|الصفحتان التاليتان|ال$1 صفحات التالية|ال$1 صفحة التالية|ال$1 صفحة التالية}} في التصنيف الحالي.',
'category-file-count' => '{{PLURAL:$2|لا يحتوي هذا التصنيف أي صفحات.|هذا التصنيف يحتوي على الصفحة التالية فقط.|{{PLURAL:$1||الصفحة التالية|الصفحتان التاليتان|ال$1 صفحات التالية|ال$1 صفحة التالية|ال$1 صفحة التالية}} في هذا التصنيف، من إجمالي $2.}}',
'category-file-count-limited' => '{{PLURAL:$1|الملف التالي|الملفان التاليان|ال$1 ملفات التالية|ال$1 ملفًا تاليًا|ال$1 ملف تالٍ}} في التصنيف الحالي.',
'listingcontinuesabbrev' => 'متابعة',
'index-category' => 'صفحات مفهرسة',
'noindex-category' => 'صفحات غير مفهرسة',
'broken-file-category' => 'صفحات تحتوي وصلات ملفات معطوبة',

'about' => 'عن',
'article' => 'صفحة محتوى',
'newwindow' => '(تفتح في نافذة جديدة)',
'cancel' => 'ifsa5',
'moredotdotdot' => 'المزيد...',
'mypage' => 'صفحتي',
'mytalk' => 'نقاشي',
'anontalk' => 'النقاش لعنوان الأيبي هذا',
'navigation' => 'إبحار',
'and' => '&#32;و',

# Cologne Blue skin
'qbfind' => 'جد',
'qbbrowse' => 'ara',
'qbedit' => 'modifi el page (baddelha)',
'qbpageoptions' => 'هذه الصفحة',
'qbpageinfo' => 'سياق النص',
'qbmyoptions' => 'صفحاتي',
'qbspecialpages' => 'الصفحات الخاصة',
'faq' => 'الأسئلة الأكثر تكرارا',
'faqpage' => 'Project:أسئلة متكررة',

# Vector skin
'vector-action-addsection' => 'أضف موضوعا',
'vector-action-delete' => 'احذف',
'vector-action-move' => 'انقل',
'vector-action-protect' => 'احم',
'vector-action-undelete' => 'استرجع الحذف',
'vector-action-unprotect' => 'غير الحماية',
'vector-simplesearch-preference' => 'مكّن مقترحات البحث المُحسّنة (لواجهة فكتور فقط)',
'vector-view-create' => 'أنشئ',
'vector-view-edit' => 'modifi el page (baddelha)',
'vector-view-history' => 'اعرض التاريخ',
'vector-view-view' => 'اقرأ',
'vector-view-viewsource' => 'اعرض المصدر',
'actions' => 'أفعال',
'namespaces' => 'النطاقات',
'variants' => 'المتغيرات',

'errorpagetitle' => 'ghalath',
'returnto' => 'ارجع إلى $1.',
'tagline' => 'عن {{SITENAME}}',
'help' => '3awenni ya3chek',
'search' => 'lawwej',
'searchbutton' => 'lawwej',
'go' => 'اذهب',
'searcharticle' => 'اذهب',
'history' => 'teri5 el milaf',
'history_short' => 'تاريخ',
'updatedmarker' => 'تم تحديثها منذ زيارتي الأخيرة',
'printableversion' => 'نسخة للطباعة',
'permalink' => 'وصلة دائمة',
'print' => 'itthba3',
'view' => 'عرض',
'edit' => 'modifi el page (baddelha)',
'create' => 'أنشئ',
'editthispage' => 'modifi hal page',
'create-this-page' => 'أنشئ هذه الصفحة',
'delete' => 'احذف',
'deletethispage' => 'احذف هذه الصفحة',
'undelete_short' => 'استرجاع {{PLURAL:$1|تعديل واحد|تعديلين|$1 تعديلات|$1 تعديل|$1 تعديلا}}',
'viewdeleted_short' => 'عرض {{PLURAL:$1|تعديل محذوف|$1 تعديلات محذوفة}}',
'protect' => 'احم',
'protect_change' => 'غير',
'protectthispage' => 'احم هذه الصفحة',
'unprotect' => 'غير الحماية',
'unprotectthispage' => 'غير حماية هذه الصفحة',
'newpage' => 'صفحات جديدة',
'talkpage' => 'ناقش هذه الصفحة',
'talkpagelinktext' => 'نقاش',
'specialpage' => 'صفحة خاصة',
'personaltools' => 'أدوات شخصية',
'postcomment' => 'قسم جديد',
'articlepage' => 'عرض صفحة المحتوى',
'talk' => 'نقاش',
'views' => 'معاينة',
'toolbox' => 'صندوق الأدوات',
'userpage' => 'عرض صفحة المستخدم',
'projectpage' => 'عرض صفحة المشروع',
'imagepage' => 'عرض صفحة الملف',
'mediawikipage' => 'عرض صفحة الرسالة',
'templatepage' => 'عرض صفحة القالب',
'viewhelppage' => 'عرض صفحة المساعدة',
'categorypage' => 'عرض صفحة التصنيف',
'viewtalkpage' => 'عرض النقاش',
'otherlanguages' => 'بلغات أخرى',
'redirectedfrom' => '(تم التحويل من $1)',
'redirectpagesub' => 'صفحة تحويل',
'lastmodifiedat' => 'آخر تعديل لهذه الصفحة في $2، $1.',
'viewcount' => '{{PLURAL:$1|لم تعرض هذه الصفحة أبدا|تم عرض هذه الصفحة مرة واحدة|تم عرض هذه الصفحة مرتين|تم عرض هذه الصفحة $1 مرات|تم عرض هذه الصفحة $1 مرة}}.',
'protectedpage' => 'صفحة محمية',
'jumpto' => 'اذهب إلى:',
'jumptonavigation' => 'إبحار',
'jumptosearch' => 'lawwej',
'view-pool-error' => 'عذرا، الخوادم منهكة حاليا.
يحاول مستخدمون كثر الوصول إلى هذه الصفحة.
من فضلك انتظر قليلا قبل أن تحاول الوصول إلى هذه الصفحة مجددا.

$1',
'pool-timeout' => 'انتهاء الانتظار للقفل',
'pool-queuefull' => 'طابور الاقتراع ملئ',
'pool-errorunknown' => 'خطأ غير معروف',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite' => 'عن {{SITENAME}}',
'aboutpage' => 'Project:عن',
'copyright' => 'المحتوى متوفر تحت $1.',
'copyrightpage' => '{{ns:project}}:حقوق النسخ',
'currentevents' => 'الأحداث الجارية',
'currentevents-url' => 'Project:الأحداث الجارية',
'disclaimers' => 'عدم مسؤولية',
'disclaimerpage' => 'Project:عدم مسؤولية عام',
'edithelp' => 'مساعدة التحرير',
'edithelppage' => 'Help:تحرير',
'helppage' => 'Help:محتويات',
'mainpage' => 'الصفحة الرئيسية',
'mainpage-description' => 'الصفحة الرئيسية',
'policy-url' => 'Project:سياسة',
'portal' => 'بوابة المجتمع',
'portal-url' => 'Project:بوابة المجتمع',
'privacy' => 'سياسة الخصوصية',
'privacypage' => 'Project:سياسة الخصوصية',

'badaccess' => 'خطأ في السماح',
'badaccess-group0' => 'ليس من المسموح لك تنفيذ الفعل الذي طلبته.',
'badaccess-groups' => 'الفعل الذي طلبته مقصور على المستخدمين في {{PLURAL:$2||مجموعة|واحدة من مجموعتي|واحدة من مجموعات}}: $1.',

'versionrequired' => 'تلزم نسخة $1 من ميدياويكي',
'versionrequiredtext' => 'تلزم النسخة $1 من ميدياويكي لاستعمال هذه الصفحة. انظر [[Special:Version|صفحة النسخة]]',

'ok' => 'ok',
'retrievedfrom' => 'تم الاسترجاع من "$1"',
'youhavenewmessages' => 'توجد لديك $1 ($2).',
'newmessageslink' => 'رسائل جديدة',
'newmessagesdifflink' => 'آخر تغيير',
'youhavenewmessagesmulti' => 'لديك رسائل جديدة على $1',
'editsection' => 'modifi el page (baddelha)',
'editold' => 'modifi el page (baddelha)',
'viewsourceold' => 'اعرض المصدر',
'editlink' => 'modifi el page (baddelha)',
'viewsourcelink' => 'اعرض المصدر',
'editsectionhint' => 'حرر القسم: $1',
'toc' => 'ta3li9at',
'showtoc' => 'اعرض',
'hidetoc' => 'أخف',
'collapsible-collapse' => 'اطو',
'collapsible-expand' => 'وسع',
'thisisdeleted' => 'أأعرض أو أسترجع $1؟',
'viewdeleted' => 'أأعرض $1؟',
'restorelink' => '{{PLURAL:$1|$1 تعديل محذوف|تعديلا واحدا محذوفا|تعديلين محذوفين|$1 تعديلات محذوفة|$1 تعديلا محذوفا|$1 تعديلا محذوفا}}',
'feedlinks' => 'التغذية:',
'feed-invalid' => 'نوع اشتراك التلقيم غير صحيح.',
'feed-unavailable' => 'التلقيمات غير متوفرة',
'site-rss-feed' => '$1 تلقيم أر إس إس',
'site-atom-feed' => '$1 تلقيم أتوم',
'page-atom-feed' => '$1 تلقيم أتوم',
'red-link-title' => '$1 (الصفحة غير موجودة)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main' => "ss'af7a",
'nstab-user' => 'صفحة مستخدم',
'nstab-special' => 'صفحة خاصة',
'nstab-project' => 'صفحة مشروع',
'nstab-image' => 'milaf (Fichier)',
'nstab-template' => 'قالب',
'nstab-category' => 'تصنيف',

# General errors
'missing-article' => 'لم تجد قاعدة البيانات النص الخاص بصفحة كان يجب أن تجدها، واسمها "$1" $2.

عادة ما يحدث هذا عند اتباع فرق قديم أو وصلة تاريخ تؤدي إلى صفحة حذفت.

إذا لم تكن هذه هي الحالة، فالمحتمل أنك وجدت خللا في البرنامج.
من فضلك أبلغ أحد [[Special:ListUsers/sysop|الإداريين]]، وأعطه وصلة إلى مسار هذه الصفحة.',
'missingarticle-rev' => '(رقم المراجعة: $1)',
'badtitle' => 'عنوان سيء',
'badtitletext' => 'عنوان الصفحة المطلوب إما غير صحيح أو فارغ، وربما الوصلة بين اللغات أو بين المشاريع خاطئة.
ومن الممكن وجود رموز لا تصلح للاستخدام في العناوين.',
'viewsource' => 'اعرض المصدر',

# Login and logout pages
'yourname' => 'اسم المستخدم:',
'yourpassword' => 'كلمة السر:',
'yourpasswordagain' => 'أعد كتابة كلمة السر:',
'remembermypassword' => 'تذكر دخولي على هذا الحاسوب (إلى {{PLURAL:$1||يوم واحد|يومين|$1 أيام|$1 يومًا|$1 يوم}} كحد أقصى)',
'login' => 'ادخل',
'nav-login-createaccount' => 'ادخل / أنشئ حسابا',
'loginprompt' => 'يجب أن تكون الكوكيز لديك مفعلة لتسجل الدخول إلى {{SITENAME}}.',
'userlogin' => 'ادخل / أنشئ حسابا',
'userlogout' => 'خروج',
'nologin' => "ألا تمتلك حسابا؟ '''$1'''.",
'nologinlink' => 'أنشئ حسابا',
'createaccount' => 'أنشئ حسابا',
'gotaccount' => "تمتلك حسابا بالفعل؟ '''$1'''.",
'gotaccountlink' => 'ادخل',
'userlogin-resetlink' => 'أنسيت بيانات الولوج؟',
'mailmypassword' => 'أرسل لي كلمة سر جديدة',
'loginlanguagelabel' => 'اللغة: $1',

# Edit page toolbar
'bold_sample' => 'نص غليظ',
'bold_tip' => 'نص غليظ',
'italic_sample' => 'نص مائل',
'italic_tip' => 'نص مائل',
'link_sample' => 'عنوان وصلة',
'link_tip' => 'وصلة داخلية',
'extlink_sample' => 'http://www.example.com عنوان الوصلة',
'extlink_tip' => 'وصلة خارجية (تذكر بادئة http://)',
'headline_sample' => 'نص عنوان رئيسي',
'headline_tip' => 'عنوان من المستوى الثاني',
'nowiki_sample' => 'أدخل النص غير المنسق هنا',
'nowiki_tip' => 'أهمل تهيئة الويكي',
'image_tip' => 'ملف مدرج',
'media_tip' => 'وصلة ملف',
'sig_tip' => 'توقيعك مع الساعة والتاريخ',
'hr_tip' => 'خط أفقي (تجنب الاستخدام بكثرة)',

# Edit pages
'summary' => 'ملخص:',
'minoredit' => 'هذا تعديل طفيف',
'watchthis' => 'راقب هذه الصفحة',
'savearticle' => 'احفظ الصفحة',
'preview' => 'معاينة',
'showpreview' => 'أظهر معاينة',
'showdiff' => 'أظهر التغييرات',
'anoneditwarning' => "'''تحذير:''' لم تقم بالدخول.
سيسجل عنوان الآيبي خاصتك في تاريخ هذه الصفحة.",
'newarticle' => '(جديد)',
'newarticletext' => "لقد تبعت وصلة لصفحة لم يتم إنشائها بعد.
لإنشاء هذه الصفحة ابدأ الكتابة في الصندوق بالأسفل (انظر في [[{{MediaWiki:Helppage}}|صفحة المساعدة]] للمزيد من المعلومات).
إذا كانت زيارتك لهذه الصفحة بالخطأ، اضغط على زر ''رجوع'' في متصفح الإنترنت لديك.",
'noarticletext' => 'لا يوجد حاليا أي نص في هذه الصفحة.
يمكنك [[Special:Search/{{PAGENAME}}|البحث عن عنوان هذه الصفحة]] في الصفحات الأخرى،
<span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} البحث في السجلات المتعلقة]،
أو [{{fullurl:{{FULLPAGENAME}}|action=edit}} تعديل هذه الصفحة]</span>.',
'noarticletext-nopermission' => 'لا يوجد حاليا أي نص في هذه الصفحة.يمكنك [[Special:Search/{{PAGENAME}}|البحث عن عنوان هذه الصفحة]] في الصفحات الأخرى,أو <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} بحث السجلات المتصلة]</span>.',
'previewnote' => "'''تذكر أن هذه مجرد معاينة للصفحة؛''''
لم تحفظ تغييراتك إلى الآن",
'editing' => 'تحرير $1',
'editingsection' => 'تحرير $1 (قسم)',
'templatesused' => '{{PLURAL:$1||القالب المستخدم|القالبان المستخدمان|القوالب المستخدمة}} في هذه الصفحة:',
'template-protected' => '(حماية كاملة)',
'template-semiprotected' => '(حماية جزئية)',
'hiddencategories' => '{{PLURAL:$1|هذه الصفحة غير موجودة في أي تصنايف مخفية|هذه الصفحة موجودة في تصنيف مخفي واحد|هذه الصفحة موجودة في تصنيفين مخفيين|هذه الصفحة موجودة في $1 تصانيف مخفية|هذه الصفحة موجودة في $1 تصنيفا مخفيا|هذه الصفحة موجودة في $1 تصنيف مخفي}}:',
'permissionserrorstext-withaction' => 'لا تملك الصلاحيات ل$2، لل{{PLURAL:$1||سبب التالي|سببين التاليين|أسباب التالية}}:',
'recreate-moveddeleted-warn' => "'''تحذير: أنت تقوم بإعادة إنشاء صفحة سبق حذفها.'''

يجب عليك التيقن من أن الاستمرار بتحرير هذه الصفحة ملائم.
سجلا الحذف والنقل لهذه الصفحة معروضان هنا للتيسير:",
'moveddeleted-notice' => 'هذه الصفحة تم حذفها.
سجلا الحذف والنقل للصفحة معروضان بالأسفل كمرجع.',

# Parser/template warnings
'post-expand-template-inclusion-warning' => "'''تحذير:''' حجم تضمين القالب كبير جدا.
بعض القوالب لن تضمن.",
'post-expand-template-inclusion-category' => 'الصفحات حيث تم تجاوز حجم تضمين القالب',
'post-expand-template-argument-warning' => "'''تحذير:''' هذه الصفحة تحتوي على عامل قالب واحد على الأقل له حجم تمدد كبير جدا.
هذه العوامل تم حذفها.",
'post-expand-template-argument-category' => 'صفحات تحتوي مدخلات القالب المحذوفة',

# History pages
'viewpagelogs' => 'اعرض سجلات هذه الصفحة',
'currentrev-asof' => 'المراجعة الحالية بتاريخ $1',
'revisionasof' => 'مراجعة $1',
'revision-info' => 'مراجعة $1 بواسطة $2',
'previousrevision' => '→ مراجعة أقدم',
'nextrevision' => 'مراجعة أحدث ←',
'currentrevisionlink' => 'المراجعة الحالية',
'cur' => 'الحالي',
'last' => 'السابق',
'histlegend' => 'اختيار الفرق: علم على صناديق النسخ للمقارنة واضغط قارن بين النسخ المختارة أو الزر بالأسفل.<br />
مفتاح: (الحالي) = الفرق مع النسخة الحالية
(السابق) = الفرق مع النسخة السابقة، ط = تغيير طفيف',
'history-fieldset-title' => 'تصفح التاريخ',
'history-show-deleted' => 'المحذوفة فقط',
'histfirst' => 'أول',
'histlast' => 'آخر',

# Revision feed
'history-feed-item-nocomment' => '$1 في $2',

# Revision deletion
'rev-delundel' => 'أظهر/أخف',
'revdel-restore' => 'تغيير الرؤية',
'revdel-restore-deleted' => 'مراجعات محذوفة',
'revdel-restore-visible' => 'مراجعات مرئية',

# Merge log
'revertmerge' => 'إلغاء الدمج',

# Diffs
'history-title' => 'تاريخ مراجعة "$1"',
'lineno' => 'سطر $1:',
'compareselectedversions' => 'قارن بين النسختين المختارتين',
'editundo' => 'تراجع',
'diff-multi' => '({{PLURAL:$1||مراجعة واحدة متوسطة غير معروضة أجراها|مراجعتان متوسطتان غير معروضتين أجراهما|$1 مراجعات متوسطة غير معروضة أجراها|$1 مراجعة متوسطة غير معروضة أجراها}} {{PLURAL:$2||مستخدم واحد|مستخدمان|$2 مستخدمين|$2 مستخدمًا|$2 مستخدم}}.)',

# Search results
'searchresults' => 'el resultats',
'searchresults-title' => 'نتائج البحث عن "$1"',
'prevn' => '{{PLURAL:$1|$1}} السابقة',
'nextn' => '{{PLURAL:$1|$1}} التالية',
'prevn-title' => '$1 {{PLURAL:$1|نتيجة|نتيجة}} سابقة',
'nextn-title' => '$1 {{PLURAL:$1|نتيجة|نتيجة}} سابقة',
'shown-title' => 'عرض $1 {{PLURAL:$1|نتيجة|نتيجة}} لكل صفحة',
'viewprevnext' => 'عرض ($1 {{int:pipe-separator}} $2) ($3).',
'searchmenu-exists' => "'''famma ss'af7a ismha \"[[:\$1]]\" fi hedha el wiki.'''",
'searchmenu-new' => "'''أنشئ الصفحة \"[[:\$1]]\" في هذا الويكي!'''",
'searchprofile-articles' => 'صفحات المحتوى',
'searchprofile-project' => 'صفحات المساعدة والمشروع',
'searchprofile-images' => 'الوسائط المتعددة',
'searchprofile-everything' => 'كل شيء',
'searchprofile-advanced' => 'متقدم',
'searchprofile-articles-tooltip' => 'ابحث في $1',
'searchprofile-project-tooltip' => 'ابحث في $1',
'searchprofile-images-tooltip' => 'ابحث عن ملفات',
'searchprofile-everything-tooltip' => 'ابحث في كل المحتوى (شاملا صفحات النقاش)',
'searchprofile-advanced-tooltip' => 'ابحث في النطاقات المخصصة',
'search-result-size' => '$1 ({{PLURAL:$2|لا كلمات|كلمة واحدة|كلمتان|$2 كلمات|$2 كلمة}})',
'search-result-category-size' => '{{PLURAL:$1|لا أعضاء|عضو واحد|عضوان|$1 أعضاء|$1 عضوًا|$1 عضو}} ({{PLURAL:$2|لا تصانيف فرعية|تصنيف فرعي واحد|تصنيفان فرعيان|$2 تصنيفات فرعية|$2 تصنيفًا فرعيًا|$2 تصنيف فرعي}} و{{PLURAL:$3|لا ملفات|ملف واحد|ملفان|$3 ملفات|$3 ملفًا|$3 ملف}})',
'search-redirect' => '(تحويلة $1)',
'search-section' => '(قسم $1)',
'search-suggest' => 'هل كنت تقصد: $1',
'searchrelated' => 'مرتبطة',
'searchall' => 'الكل',
'showingresultsheader' => "{{PLURAL:$5|النتيجة '''$1''' من'''$3'''|النتائج '''$1 - $2''' من'''$3'''}} ل'''$4'''",
'search-nonefound' => 'لا توجد نتائج تطابق الاستعلام.',

# Preferences page
'mypreferences' => 'تفضيلاتي',
'youremail' => 'البريد:',
'yourrealname' => 'الاسم الحقيقي:',
'prefs-help-email' => 'عنوان البريد الإلكتروني هو أمر اختياري، ولكن ستحتاج لإعادة تعيين كلمة المرور، إن نسيت كلمة المرور الخاصة بك.',
'prefs-help-email-others' => 'يمكنك أيضا اختيار للسماح للآخرين الاتصال بك عن طريق صفحة المستخدم أو نقاش المستخدم الخاص بك دون الحاجة إلى الكشف عن الهوية الخاصة بك.',

# Associated actions - in the sentence "You do not have permission to X"
'action-edit' => 'modifi hal page',

# Recent changes
'nchanges' => '{{PLURAL:$1|لا تغييرات|تغيير واحد|تغييران|$1 تغييرات|$1 تغييرا|$1 تغيير}}',
'recentchanges' => 'أحدث التغييرات',
'recentchanges-legend' => 'خيارات أحدث التغييرات',
'recentchanges-summary' => 'تابع أحدث التغييرات للويكي عبر هذه التلقيمة.',
'recentchanges-feed-description' => 'تابع أحدث التغييرات للويكي عبر هذه التلقيمة.',
'recentchanges-label-newpage' => 'أنشأ هذا التعديل صفحة جديدة',
'recentchanges-label-minor' => 'هذا تعديل طفيف',
'recentchanges-label-bot' => 'أجرى هذا التعديل بوت',
'recentchanges-label-unpatrolled' => 'لم يراجع هذا التعديل إلى الآن',
'rcnote' => "بالأسفل {{PLURAL:$1|لا توجد تغييرات|التغيير الأخير|آخر تغييرين|آخر '''$1''' تغييرات|آخر '''$1''' تغييرا|آخر '''$1''' تغيير}} في {{PLURAL:$2||'''اليوم''' الماضي|'''اليومين''' الماضيين|ال'''$2''' أيام الماضية|ال'''$2''' يوما الماضيا|ال'''$2''' يوم الماضي}}، كما في $5، $4.",
'rcnotefrom' => "بالأسفل التغييرات منذ '''$2''' (إلى '''$1''' معروضة).",
'rclistfrom' => 'أظهر التغييرات بدءا من $1',
'rcshowhideminor' => '$1 التعديلات الطفيفة',
'rcshowhidebots' => '$1 البوتات',
'rcshowhideliu' => '$1 المستخدمين المسجلين',
'rcshowhideanons' => '$1 المستخدمين المجهولين',
'rcshowhidepatr' => '$1 التعديلات المراجعة',
'rcshowhidemine' => '$1 تعديلاتي',
'rclinks' => 'أظهر آخر $1 تعديل في آخر $2 يوم<br />$3',
'diff' => 'فرق',
'hist' => 'تاريخ',
'hide' => 'أخف',
'show' => 'اعرض',
'minoreditletter' => 'thafif',
'newpageletter' => 'jadid',
'boteditletter' => 'bot',
'rc-enhanced-expand' => 'عرض التفاصيل (يتطلب جافاسكريبت)',
'rc-enhanced-hide' => 'أخفِ التفاصيل',

# Recent changes linked
'recentchangeslinked' => 'تغييرات ذات علاقة',
'recentchangeslinked-toolbox' => 'تغييرات ذات علاقة',
'recentchangeslinked-title' => 'التغييرات المرتبطة ب "$1"',
'recentchangeslinked-noresult' => 'لم تحدث تعديلات في الصفحات التي لها وصلات هنا خلال الفترة المحددة.',
'recentchangeslinked-summary' => "هذه قائمة بالتغييرات التي تمت حديثا للصفحات الموصولة من صفحة معينة (أو إلى الأعضاء ضمن تصنيف معين).
الصفحات في [[Special:Watchlist|قائمة مراقبتك]] '''عريضة'''",
'recentchangeslinked-page' => 'اسم الصفحة:',
'recentchangeslinked-to' => 'أظهر التغييرات للصفحات الموصولة للصفحة المعطاة عوضا عن ذلك',

# Upload
'upload' => 'ارفع ملفات',
'uploadlogpage' => 'سجل الرفع',
'filedesc' => 'ملخص:',
'uploadedimage' => 'رفع "[[$1]]"',

'license' => 'ترخيص:',
'license-header' => 'licence',

# File description page
'file-anchor-link' => 'milaf (Fichier)',
'filehist' => 'teri5 el milaf',
'filehist-help' => 'اضغط على وقت/زمن لرؤية الملف كما بدا في هذا الزمن.',
'filehist-revert' => 'استرجع',
'filehist-current' => 'حالي',
'filehist-datetime' => 'وقت/زمن',
'filehist-thumb' => 'صورة مصغرة',
'filehist-thumbtext' => 'تصغير للنسخة بتاريخ $1',
'filehist-user' => 'مستخدم',
'filehist-dimensions' => 'الأبعاد',
'filehist-comment' => 'ta3li9at',
'imagelinks' => 'استخدام الملف',
'linkstoimage' => '{{PLURAL:$1||الصفحة التالية تصل|الصفحتان التاليتان تصلان|ال$1 صفحات التالية تصل|ال$1 صفحة التالية تصل}} إلى هذا الملف:',
'nolinkstoimage' => 'لا توجد صفحات تصل لهذا الملف.',
'sharedupload-desc-here' => 'هذا الملف من $1 ويمكن استخدامه بواسطة المشاريع الأخرى.
الوصف على [$2 صفحة وصف الملف] هناك معروض بالأسفل.',

# Random page
'randompage' => 'صفحة عشوائية',

# Statistics
'statistics' => 'إحصاءات',

'disambiguationspage' => 'Template:توضيح',

# Miscellaneous special pages
'nbytes' => '$1 {{PLURAL:$1|بايت|بايت}}',
'nmembers' => '{{PLURAL:$1|لا أعضاء|عضو واحد|عضوان|$1 أعضاء|$1 عضوا|$1 عضو}}',
'prefixindex' => 'كل الصفحات بالبادئة',
'usercreated' => '{{GENDER:$3|أنشأه|أنشأته}} في $1 الساعة $2',
'newpages' => 'صفحات جديدة',
'move' => 'انقل',
'pager-newer-n' => '{{PLURAL:$1|أقدم 1|أقدم $1}}',
'pager-older-n' => '{{PLURAL:$1|أقدم 1|أقدم $1}}',

# Book sources
'booksources' => 'مصادر كتاب',
'booksources-search-legend' => 'البحث عن مصادر الكتب',
'booksources-go' => 'اذهب',

# Special:Log
'log' => 'سجلات',

# Special:AllPages
'allpages' => "kol ess'afa7at",
'alphaindexline' => '$1 إلى $2',
'allarticles' => "kol ess'afa7at",
'allpagessubmit' => 'اذهب',

# Special:Categories
'categories' => 'تصنيفات',

# Special:LinkSearch
'linksearch-line' => '$1 موصولة من $2',

# Special:Log/newusers
'newuserlogpage' => 'سجل إنشاء المستخدمين',

# Special:ListGroupRights
'listgrouprights-members' => '(قائمة الأعضاء)',

# E-mail user
'emailuser' => 'إرسال رسالة لهذا المستخدم',

# Watchlist
'watchlist' => 'قائمة مراقبتي',
'mywatchlist' => 'قائمة مراقبتي',
'watchlistfor2' => 'ل$1 $2',
'watch' => 'راقب',
'unwatch' => 'أوقف المراقبة',
'watchlist-details' => '{{PLURAL:$1||صفحة واحدة|صفحتان|$1 صفحات|$1 صفحة}} في قائمة مراقبتك، بدون عد صفحات النقاش.',
'wlshowlast' => 'عرض آخر $1 ساعات $2 أيام $3',
'watchlist-options' => 'خيارات قائمة المراقبة',

# Delete
'actioncomplete' => 'انتهاء العملية',
'actionfailed' => 'الفعل فشل',
'dellogpage' => 'سجل الحذف',

# Rollback
'rollbacklink' => 'استرجع',

# Protect
'protectlogpage' => 'سجل الحماية',
'protectedarticle' => 'حمى "[[$1]]"',

# Undelete
'undeletelink' => 'اعرض/استعد',
'undeleteviewlink' => 'اعرض',

# Namespace form on various pages
'namespace' => 'النطاق',
'invert' => 'اعكس الاختيار',
'blanknamespace' => '(رئيسي)',

# Contributions
'contributions' => 'مساهماتي',
'contributions-title' => 'مساهمات المستخدم $1',
'mycontris' => 'مساهماتي',
'contribsub2' => 'ل$1 ($2)',
'uctop' => '(top)',
'month' => 'من سنة (وأقدم):',
'year' => 'من سنة (وأقدم):',

'sp-contributions-newbies' => 'اعرض مساهمات الحسابات الجديدة فقط',
'sp-contributions-blocklog' => 'سجل المنع',
'sp-contributions-uploads' => 'مرفوعات',
'sp-contributions-logs' => 'سجلات',
'sp-contributions-talk' => 'نقاش',
'sp-contributions-search' => 'بحث عن مساهمات',
'sp-contributions-username' => 'عنوان أيبي أو اسم مستخدم:',
'sp-contributions-toponly' => 'أظهر أعلى المراجعات فقط',
'sp-contributions-submit' => 'lawwej',

# What links here
'whatlinkshere' => 'ماذا يصل هنا',
'whatlinkshere-title' => 'الصفحات التي تصل إلى "$1"',
'whatlinkshere-page' => "ss'af7a:",
'linkshere' => "الصفحات التالية تصل إلى '''[[:$1]]''':",
'nolinkshere' => "لا توجد صفحات تصل إلى '''[[:$1]]'''.",
'isredirect' => 'صفحة تحويل',
'istemplate' => 'مضمن',
'isimage' => 'وصلة ملف',
'whatlinkshere-prev' => '{{PLURAL:$1|previous|previous $1}}',
'whatlinkshere-next' => '{{PLURAL:$1|القادمة|ال$1 القادمة}}',
'whatlinkshere-links' => '← وصلات',
'whatlinkshere-hideredirs' => '$1 التحويلات',
'whatlinkshere-hidetrans' => '$1 التضمينات',
'whatlinkshere-hidelinks' => '$1 الوصلات',
'whatlinkshere-hideimages' => '$1 وصلة صورة',
'whatlinkshere-filters' => 'مرشحات',

# Block/unblock
'ipboptions' => 'ساعتين:2 hours,يوم واحد:1 day,3 أيام:3 days,أسبوع واحد:1 week,أسبوعين:2 weeks,شهر واحد:1 month,3 أشهر:3 months,6 أشهر:6 months,سنة واحدة:1 year,دائم:infinite',
'ipblocklist' => 'المستخدمون الممنوعون',
'blocklink' => 'امنع',
'unblocklink' => 'ارفع المنع',
'change-blocklink' => 'تغيير المنع',
'contribslink' => 'مساهمات',
'blocklogpage' => 'سجل المنع',
'blocklogentry' => 'منع "[[$1]]" لفترة زمنية مدتها $2 $3',
'block-log-flags-nocreate' => 'إنشاء الحسابات ممنوع',

# Move page
'movelogpage' => 'سجل النقل',
'revertmove' => 'استرجع',

# Export
'export' => 'تصدير صفحات',

# Namespace 8 related
'allmessagesname' => 'الاسم',
'allmessagesdefault' => 'النص الافتراضي',

# Thumbnails
'thumbnail-more' => 'كبّر',
'thumbnail_error' => 'خطأ في إنشاء صورة مصغرة: $1',

# Tooltip help for the actions
'tooltip-pt-userpage' => 'صفحة المستخدم الخاصة بك',
'tooltip-pt-mytalk' => 'صفحة نقاشك',
'tooltip-pt-preferences' => 'تفضيلاتي',
'tooltip-pt-watchlist' => 'قائمة الصفحات التي تراقب التغييرات التي تحدث بها',
'tooltip-pt-mycontris' => 'قائمة مساهماتك',
'tooltip-pt-login' => 'يفضل أن تسجل الدخول، لكنه ليس إلزاميا.',
'tooltip-pt-logout' => 'خروج',
'tooltip-ca-talk' => 'نقاش عن صفحة المحتوى',
'tooltip-ca-edit' => 'يمكنك تعديل هذه الصفحة.
من فضلك استخدم زر العرض المسبق قبل الحفظ.',
'tooltip-ca-addsection' => 'ابدأ قسما جديدا',
'tooltip-ca-viewsource' => 'هذه الصفحة محمية.
يمكنك رؤية مصدرها.',
'tooltip-ca-history' => 'النسخ السابقة لهذه الصفحة',
'tooltip-ca-protect' => 'احم هذه الصفحة',
'tooltip-ca-delete' => 'احذف هذه الصفحة',
'tooltip-ca-move' => 'علم هذه الصفحة',
'tooltip-ca-watch' => 'أضف هذه الصفحة إلى قائمة مراقبتك',
'tooltip-ca-unwatch' => 'أزل هذه الصفحة من قائمة مراقبتك',
'tooltip-search' => 'lawwej fi {{SITENAME}}',
'tooltip-search-go' => 'اذهب إلى صفحة بالاسم نفسه إن وجدت',
'tooltip-search-fulltext' => 'ابحث في الصفحات عن هذا النص',
'tooltip-p-logo' => "ara ess'af7a elraïssia",
'tooltip-n-mainpage' => "ara ess'af7a elraïssia",
'tooltip-n-mainpage-description' => "ara ess'af7a elraïssia",
'tooltip-n-portal' => 'حول المشروع، ماذا يمكن أن تفعل، أين يمكن أن تجد ما تحتاجه',
'tooltip-n-currentevents' => 'مطالعة سريعة لأهم الأحداث الجارية',
'tooltip-n-recentchanges' => 'قائمة أحدث التغييرات في الويكي.',
'tooltip-n-randompage' => 'حمل صفحة عشوائية',
'tooltip-n-help' => 'المكان للمساعدة',
'tooltip-t-whatlinkshere' => 'قائمة بكل صفحات الويكي التي تصل هنا',
'tooltip-t-recentchangeslinked' => 'أحدث التغييرات في الصفحات الموصولة من هذه الصفحة',
'tooltip-feed-atom' => 'تلقيم أتوم لهذه الصفحة',
'tooltip-t-contributions' => 'رؤية قائمة مساهمات هذا المستخدم',
'tooltip-t-emailuser' => 'أرسل رسالة لهذا المستخدم',
'tooltip-t-upload' => 'ارفع ملفات',
'tooltip-t-specialpages' => 'قائمة بكل الصفحات الخاصة',
'tooltip-t-print' => 'نسخة للطباعة لهذه الصفحة',
'tooltip-t-permalink' => 'وصلة دائمة لهذه النسخة من الصفحة',
'tooltip-ca-nstab-main' => 'رؤية صفحة المحتوى',
'tooltip-ca-nstab-user' => 'اعرض صفحة المستخدم',
'tooltip-ca-nstab-special' => 'هذه صفحة خاصة، لا تستطيع أن تعدل الصفحة نفسها',
'tooltip-ca-nstab-project' => 'رؤية صفحة المحتوى',
'tooltip-ca-nstab-image' => 'رؤية صفحة الملف',
'tooltip-ca-nstab-template' => 'رؤية القالب',
'tooltip-ca-nstab-category' => 'رؤية صفحة التصنيف',
'tooltip-minoredit' => 'علم على هذا كتعديل طفيف',
'tooltip-save' => 'احفظ تغييراتك',
'tooltip-preview' => 'اعرض تغييراتك، من فضلك استخدم هذا قبل الحفظ!',
'tooltip-diff' => 'اعرض التغييرات التي قمت بها للنص.',
'tooltip-compareselectedversions' => 'شاهد الفروق بين النسختين المختارتين من هذه الصفحة.',
'tooltip-watch' => 'أضف هذه الصفحة إلى قائمة مراقبتك',
'tooltip-rollback' => '"استرجاع" تسترجع التعديل (التعديلات)  في هذه الصفحة للمساهم الأخير بضغطة واحدة.',
'tooltip-undo' => '"رجوع" تسترجع هذا التعديل وتفتح نافذة التعديل في نمط العرض المسبق. تسمح بإضافة سبب في الملخص.',
'tooltip-summary' => 'أدخل ملخصا قصيرا',

# Browsing diffs
'previousdiff' => '→ التعديل السابق',
'nextdiff' => 'التعديل اللاحق ←',

# Media information
'file-info-size' => '$1 × $2 بكسل حجم الملف: $3، نوع MIME: $4',
'file-nohires' => 'لا توجد دقة أعلى متوفرة.',
'svg-long-desc' => 'ملف SVG، أبعاده $1 × $2 بكسل، حجم الملف: $3',
'show-big-image' => 'دقة كاملة',

# Bad image list
'bad_image_list' => 'الصيغة كالتالي:

فقط عناصر القائمة (السطور التي تبدأ ب *) تؤخذ في الاعتبار.
يجب أن تكون أول وصلة في السطر وصلة لملف سيىء.
أي وصلات تالية في السطر نفسه تعتبر استثناءات، أي صفحات قد يكون الملف فيها سطريا.',

# Metadata
'metadata' => 'بيانات ميتا',
'metadata-help' => 'هذا الملف يحتوي على معلومات إضافية، غالبا ما تكون أضيفت من قبل الكاميرا الرقمية أو الماسح الضوئي المستخدم في إنشاء الملف.
إذا كان الملف قد عدل عن حالته الأصلية، فبعض التفاصيل قد لا تعبر عن الملف المعدل.',
'metadata-fields' => 'حقول معطيات الميتا الموجودة في هذه الرسالة سوف تعرض في صفحة الصورة عندما يكون جدول معطيات الميتا مضغوطا.
الحقول الأخرى ستكون مخفية افتراضيا.
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

# External editor support
'edit-externally' => 'عدل هذا الملف باستخدام تطبيق خارجي',
'edit-externally-help' => '(انظر [//www.mediawiki.org/wiki/Manual:External_editors تعليمات الإعداد] لمزيد من المعلومات)',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'الكل',
'namespacesall' => 'الكل',
'monthsall' => 'الكل',

# Watchlist editing tools
'watchlisttools-view' => 'اعرض التغييرات المرتبطة',
'watchlisttools-edit' => 'اعرض قائمة المراقبة وعدلها',
'watchlisttools-raw' => 'عدل قائمة المراقبة الخام',

# Core parser functions
'duplicate-defaultsort' => '\'\'\'تحذير:\'\'\' مفتاح الترتيب الافتراضي "$2" يتجاوز مفتاح الترتيب الافتراضي السابق "$1".',

# Special:SpecialPages
'specialpages' => 'الصفحات الخاصة',

# External image whitelist
'external_image_whitelist' => ' #<pre>اترك هذا السطر تماما كما هو
#ضع منثورات التعبيرات المنتظمة (فقط الجزء الذي يذهب بين //) بالأسفل
#هذه ستتم مطابقتها مع مسارات الصور الخرجية (الموصولة بشكل مباشر)
#هذه التي تطابق سيتم عرضها كصور، غير ذلك فقط وصلة إلى الصورة سيتم عرضها
#السطور التي تبدأ ب# تتم معاملتها كتعليقات
#هذا لا يتأثر بحالة الحروف

#ضع كل منثورات التعبيرات المنتظمة فوق هذا السطر. اترك هذا السطر تماما كما هو</pre>',

# Special:Tags
'tag-filter' => 'مرشح [[Special:Tags|الوسوم]]:',

);
