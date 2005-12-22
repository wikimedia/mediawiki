<?php
/** Arabic (العربية)
  *
  * @package MediaWiki
  * @subpackage Language
  */

/** This is an UTF-8 language  */
require_once('LanguageUtf8.php');

/* private */ $wgNamespaceNamesAr = array(
	NS_MEDIA            => 'ملف',
	NS_SPECIAL          => 'خاص',
	NS_MAIN             => '',
	NS_TALK             => 'نقاش',
	NS_USER             => 'مستخدم',
	NS_USER_TALK        => 'نقاش_المستخدم',
	NS_PROJECT          => 'ويكيبيديا',
	NS_PROJECT_TALK     => 'نقاش_ويكيبيديا',
	NS_IMAGE            => 'صورة',
	NS_IMAGE_TALK       => 'نقاش_الصورة',
	NS_MEDIAWIKI        => 'ميدياويكي',
	NS_MEDIAWIKI_TALK   => 'نقاش_ميدياويكي',
	NS_TEMPLATE         => 'Template',
	NS_TEMPLATE_TALK    => 'نقاش_Template',
	NS_HELP             => 'مساعدة',
	NS_HELP_TALK        => 'نقاش_المساعدة',
	NS_CATEGORY         => 'تصنيف',
	NS_CATEGORY_TALK    => 'نقاش_التصنيف'
) + $wgNamespaceNamesEn;


/* private */ $wgAllMessagesAr = array(
# Dates
'sunday' => 'الأحد',
'monday' => 'الإثنين',
'tuesday' => 'الثلاثاء',
'wednesday' => 'الأربعاء',
'thursday' => 'الخميس',
'friday' => 'الجمعة',
'saturday' => 'السبت',
'january' => 'يناير',
'february' => 'فبراير',
'march' => 'مارس',
'april' => 'ابريل',
'may_long' => 'مايو',
'june' => 'يونيو',
'july' => 'يوليو',
'august' => 'أغسطس',
'september' => 'سبتمبر',
'november' => 'نوفمبر',
'december' => 'ديسمبر',

# Bits of text used by many pages:
#
'mainpage'		=> 'الصفحة الرئيسية',
'mytalk'		=> 'صفحة نقاشي',
'history_short' => 'تاريخ الصفحة',
'edit' => 'عدل هذه الصفحة',
'delete' => 'حذف هذه الصفحة',
'protect' => 'صفحة محمية',
'talk' => 'ناقش هذه الصفحة',

# Watchlist
#
'watch' => 'راقب هذه الصفحة',
'watchthispage'		=> 'راقب هذه الصفحة',
'unwatch' => 'توقف عن مراقبة الصفحة',
'unwatchthispage' 	=> 'توقف عن مراقبة الصفحة',


# copy and paste from wikipedia:
'1movedto2' => '$1 تم نقلها إلى $2',
'1movedto2_redir' => 'تم نقل $1 فوق التحويلة $2',
'about' => 'حول',
'aboutpage' => 'ويكيبيديا:حول',
'accmailtext' => 'تم إرسال كلمة السر الخاصة بـ \'$1\' إلى العنوان $2.',
'accmailtitle' => 'تم إرسال كلمة السر.',
'acct_creation_throttle_hit' => 'معذرة، لقد أقمت $1 حساب. لا يممكنك عمل المزيد.',
'actioncomplete' => 'انتهاء العملية',
'addedwatch' => 'تمت الإضافة لقائمة المراقبة',
'addedwatchtext' => 'تمت إضافة الصفحة  "$1" إلى <a href="/wiki/Special:Watchlist">قائمة المراقبة</a> لديك. سيتم وضع التغييرات القادمة على هذه الصفحة، وصفحة النقاش الخاصة بها سيتم وضعها هناك. وسيتم إظهار إسم الصفحة بخط <b>عريض</b> في صفحة <a href="/wiki/Special:Recentchanges">أحدث التغييرات</a> لتسهيل تحديدها وإكتشافها.</p>

<p>
إذا كنت تريد إزالة الصفحة من قائمة المراقبة لديك، إضغط على "توقف عن مراقبة الصفحة" في اللوحة أسفل الصفحة.',
'administrators' => 'ويكيبيديا:إداريين',
'allmessages' => 'كافة رسائل النظام',
'allpages' => 'كل الصفحات',
'allpagessubmit' => 'اذهب',
'alphaindexline' => '$1 إلى $2',
'alreadyloggedin' => '<strong>المستخدم $1, انت مسجل للدخول من قبل!</strong><br />',
'ancientpages' => 'المقالات القديمة',
'anontalkpagetext' => '----
هذه صفحة نقاش لمستخدم مجهول، وهو المستخدم الذي لم يقم بإنشاء حساب في ويكيبيديا، أو لا يستعمل ذلك الحساب.
لذا يتم إستعمال رقم ال IP للتعريف به. من الممكن أن يشترك عدد من المستخدمين بنفس رقم ال IP. إذا كنت مستخدم مجهول
وترى أن رسائل خير موجهة لك قد وصلتك، من الممكن أن تقوم [[Special:Userlogin|بإنشاء حساب أو القيام بالدخول]]
حتى يزول الخلط بينك وبين المستخدمين المجهولين الآخرين.',
'anonymous' => 'مستخدم مجهول لويكيبيديا',
'article' => 'مقالة',
'articleexists' => 'يوجد صفحة بهذا الإسم،
أو أن الإسم الذي تم إختياره غير صالح.
يرجى إختيار إسم آخر.',
'articlepage' => 'عرض المقالة',
'badfilename' => 'تم تغيير إسم الصورة إلى "$1".',
'badipaddress' => 'لا يوجد مستخدم بهذا الإسم',
'badquery' => 'نص بحث خاطئ',
'badretype' => 'كلمات السر التي أدخلتها غير متطابقة.',
'badtitle' => 'عنوان خاطئ',
'blanknamespace' => 'مقالات',
'blockedtext' => 'إسم المستخدم أو عنوان ال IP الخاص بك تم منعه من قبل $1.
سبب المنع هو: <br />\'\'$2\'\' <p>
من الممكن الإتصال مع $1 للنقاش حول المنع، أو من الممكن الإتصال مع أحد [[Wikipedia:Administrators|الإداريين]] حول ذلك.

تذكر أنه لا يمكن لك إستعمال خاصية إرسال رسائل إلكترونية للمستخدمين إلا إذا كنت قد وضعت عنوان بريدي صحيح في صفحة [[Special:Preferences|التفضيلات]] الخاصة بك.

عنوان ال IP الخاص بك هو $3. يرجى إضافته في أي رسالة للتساؤل حول المنع.',
'blockedtitle' => 'المستخدم ممنوع',
'blockip' => 'منع مستخدم',
'blocklink' => 'منع مستخدم',
'blocklogentry' => 'منع "$1" لفترة زمنية مدتها $2',
'bold_sample' => 'نص عريض',
'bold_tip' => 'نص عريض',
'booksources' => 'مصدر كتاب',
'brokenredirects' => 'وصلات مكسورة',
'brokenredirectstext' => 'الوصلات التالية تشير لصفحات غير موجودة.',
'bugreports' => 'تقارير الأخطاء',
'bydate' => 'على التاريخ',
'byname' => 'على الإسم',
'bysize' => 'على الحجم',
'cancel' => 'إلغاء العملية',
'categories' => 'تصنيفات الصفحة',
'categoriespagetext' => 'التصنيفات التالية موجودة في ويكيبيديا',
'category' => 'تصنيف',
'category_header' => 'المقالات في التصنيف "$1"',
'categoryarticlecount' => 'يوجد $1 مقال في هذا التصنيف.',
'categoryarticlecount1' => 'هناك $1 مقال  هذا التصنيف.',
'changepassword' => 'غير كلمة السر',
'changes' => 'تغييرات',
'columns' => 'أعمدة',
'compareselectedversions' => 'قارن بين النسخ المختارة',
'confirm' => 'تأكيد',
'confirmdelete' => 'تأكيد الحذف',
'confirmprotect' => 'تأكيد الحماية',
'confirmprotecttext' => 'هل أنت متأكد انك تريد حماية هذه الصفحة؟',
'confirmunprotect' => 'تأكيد إزالة الحماية',
'confirmunprotecttext' => 'هل أنت متأكد انك تريد إزالة الحماية عن هذه الصفحة؟',
'contribslink' => 'مساهمات',
'contribsub' => 'للمستخدم $1',
'contributions' => 'مساهمات المستخدم',
'copyright' => 'المحتويات تحت  $1.',
'copyrightpage' => 'ويكيبيديا:حقوق النسخ',
'copyrightpagename' => 'حقوق النسخ في ويكيبيديا',
'copyrightwarning' => 'يرجى الملاحظة أن جميع المساهمات هنا خاضعة وصادرة تحت ترخيص
جنو للوثائق الحرة (أنظر في $1 للمزيد من التفاصيل)
إذا لم ترد أن تخضع كتابتك للتعديل والتوزيع الحر، لا تضعها هنا.
<br />
كما أنك تتعهد بأنك قمت بكتابة ما هو موجود بنفسك، أو قمت بنسخها
من مصدر يخضع ضمن الملكية العامة، أو مصدر حر آخر.
<strong>لا ترسل أي عمل ذو حقوق محفوظة بدون الإذن من صاحب الحق</strong>.',
'createaccount' => 'إنشاء حساب جديد',
'createaccountmail' => 'عبر البريد الإلكتروني',
'cur' => 'الحالي',
'currentevents' => 'احداث حالية',
'currentrev' => 'النسخة الحالية',
'databaseerror' => 'خطأ في قاعدة البيانات',
'dateformat' => 'صيغة التاريخ',
'deadendpages' => 'صفحات نهاية مسدودة',
'defaultns' => 'أبحث في هذه النطاقات بشكل أفتراضي:',
'defemailsubject' => 'رسالة من ويكيبيديا',
'deletecomment' => 'سبب الحذف',
'deletedarticle' => 'تم حذف "$1"',
'deletedtext' => '"$1" تم حذفها.
انظر في $2 لسجل آخر عمليات الحذف.',
'deleteimg' => 'حذف',
'deletepage' => 'حذف الصفحة',
'deletesub' => '(حذف "$1")',
'deletethispage' => 'حذف هذه الصفحة',
'deletionlog' => 'سجل الحذف',
'dellogpage' => 'سجل_الحذف',
'diff' => 'فرق',
'disclaimerpage' => 'ويكيبيديا:عدم_مسؤولية_عام',
'disclaimers' => 'عدم مسؤولية',
'doubleredirects' => 'وصلات مزدوجة',
'editcurrent' => 'حرر النسخة الحالية من هذه الصفحة',
'edithelp' => 'مساعدة التحرير',
'edithelppage' => 'ويكيبيديا:مساعدة التحرير',
'editing' => 'تحرير $1',
'editingold' => '<strong> تحذير: أنت تقوم الآن بتحرير نسخة قديمة من هذه الصفحة. إذا قمت بحفظها، سيتم فقدات كافة التغييرات التي حدثت بعد هذه النسخة. </strong>',
'editsection' => 'تحرير',
'editthispage' => 'عدل هذه الصفحة',
'emailflag' => 'عدم تلقي الرسائل من المستخدمين الآخرين',
'emailforlost' => '* إدخال عنوانك البريدي أمر إختياري، لكنه يسمح لك بإرسال رسائل للأعضاء في الموقع من دون الكشف عن عنوانك لهم، كما أنه يساعدك في حال نسيانك لكلمة السر.',
'emailfrom' => 'من',
'emailmessage' => 'نص الرسالة',
'emailpage' => 'أرسل رسالة للمستخدم',
'emailpagetext' => 'لو أن هذا المستخدم قد قام بإدخال عنوان بريدي صحيح في تفضيلاته،
فسيتم إرسال رسالة واحدة له بالنموذج أدناه.
العنوان الذي قمت أنت بإدخاله لك في تفضيلات المستخدم،
سيظهر في مكان المرسل في الرسالة التي سترسل له، ليتمكن من الرد عليك.',
'emailsend' => 'إرسال',
'emailsent' => 'تم إرسال الرسالة',
'emailsenttext' => 'تم إرسال رسالتك الإلكترونية.',
'emailsubject' => 'العنوان',
'emailto' => 'إلى',
'emailuser' => 'أرسل رسالة لهذا المستخدم',
'error' => 'خطأ',
'errorpagetitle' => 'خطأ',
'excontent' => 'المحتوى كان: \'$1\'',
'excontentauthor' => 'المحتوى كان: \'$1\' (و المساهم الوحيد كان \'$2\')',
'explainconflict' => 'لقد قام أحد ما بتعديل الصفحة بعد أن بدأت انت بتحريرها.
صندوق النصوص العلوي يحتوي على النص الموجود حاليا في الصفحة.
والتغييرات التي قمت أنت بها موجودة في الصندوق في أسفل الصفحة.
يجب أن تقوم بدمج تغييراتك في النص الموجود حاليا.
<b>فقط</b> ما هو موجود في الصندوق العلوي هو ما سيتم حفظه وإستعاله عند الضغط على زر "حفظ الصفحة".
<p>',
'export' => 'صدّر صفحات',
'faq' => 'الأسئلة الأكثر تكرارا',
'faqpage' => 'ويكيبيديا:أسئلة متكررة',
'filecopyerror' => 'لا يمكن نسخ الملف من  "$1" إلى "$2".',
'filedeleteerror' => 'لا يمكن حذف الملف "$1".',
'filedesc' => 'وصف قصير',
'filename' => 'إسم الملف',
'filenotfound' => 'لا يمكن إيجاد الملف "$1".',
'filerenameerror' => 'لا يمكن غيير إسم الملف من  "$1" إلى "$2".',
'filesource' => 'مصدر',
'go' => 'إذهب',
'headline_sample' => 'نص عنوان رئيسي',
'headline_tip' => 'عنوان من المستوى الثاني',
'help' => 'مساعدة',
'helppage' => 'ويكيبيديا:مساعدة',
'hide' => 'إخفاء',
'hidetoc' => 'إخفاء',
'hist' => 'تاريخ',
'histlegend' => 'مفتاح: (الحالي) = الفرق مع النسخة الحالية
(السابق) = الفروقات مع النسخة السابقة، ط = تغيير طفيف',
'history' => 'تاريخ الصفحة',
'ilsubmit' => 'بحث',
'imagelist' => 'قائمة الصور',
'imagepage' => 'عرض صفحة الصورة',
'imgdelete' => 'حذف',
'imgdesc' => 'وصف',
'imghistory' => 'تاريخ الصورة',
'internalerror' => 'خطأ داخلي',
'intl' => 'وصلات بين لغات الموسوعة',
'invert' => 'عكس الإختيار',
'ipblocklist' => 'قائمة أسماء الأعضاء و عناوين ال IP الممنوعة',
'ipboptions' => '2 hours,1 day,3 days,1 week,2 weeks,1 month,3 months,6 months,1 year,infinite',
'ipbreason' => 'السبب',
'isredirect' => 'صفحة تحويل',
'italic_sample' => 'نص مائل',
'italic_tip' => 'نص مائل',
'last' => 'السابق',
'lastmodified' => 'أخر تعديل لهذه الصفحة كان في $1.',
'lineno' => 'سطر $1:',
'link_sample' => 'عنوان وصلة',
'linkshere' => 'الصفحات التالية تحتوي على وصلة إلى هنا:',
'linkstoimage' => 'الصفحات التالية تحتوي على وصلة لهذه الصورة:',
'listform' => 'قائمة',
'listusers' => 'قائمة الأعضاء',
'loadhist' => 'تحميل تاريخ الصفحة',
'localtime' => 'عرض الوقت المحلي',
'log' => 'تحميل و حذف',
'login' => 'دخول',
'loginerror' => 'خطأ في الدخول',
'loginpagetitle' => 'تسجيل الدخول للمستخدم',
'loginproblem' => '<b>حدثت مشكلة أثناء الدخول.</b><br />يرجى المحاولة مرى أخرى!',
'loginprompt' => 'يجب أن يدعم متصفحك الكوكيز Cookies لتتمكن من الدخول.',
'loginsuccess' => 'لقد قمت بتسجيل الدخول لويكيبيديا بإسم "$1".',
'loginsuccesstitle' => 'تم الدخول بشكل صحيح',
'logout' => 'خروج',
'logouttext' => 'أنت الآن غير مسجل الدخول للنظام.
تستطيع المتابعة بإستعمال ويكيبيديا كمجهول، أو الدخول مرة أخرى بنفس الإسم أو بإسم آخر. من الممكن أن ترى بعض الصفحات في الموسوعة كما وأنك مسجل في النظام.، وذلك بسبب إستعمال الصفحات المخبأة Cache في المنتصفح لديك.',
'logouttitle' => 'تسجيل الخروج للمستخدم',
'lonelypages' => 'صفحات يتيمة',
'longpages' => 'صفحات طويلة',
'mailmypassword' => 'أرسل لي كلمة السر عبر البريد الإلكتروني.',
'mailnologin' => 'لا يوجد عنوان للإرسال',
'mailnologintext' => 'يجب أن تقوم <a href="/wiki/Special:Userlogin">بتسجيل الدخول</a>
وتوفير بريد إلكتروني صالح في صفحة  <a href="/wiki/Special:Preferences">التفضيلات</a>
لتتمكن من إرسال الرسائل لمستخدمين آخرين.',
'maintenance' => 'صفحة الصيانة',
'maintenancebacklink' => 'العودة لصفحة الصيانة',
'minoredit' => 'هذا تعديل طفيف',
'minoreditletter' => 'ط',
'mispeelings' => 'صفحات بأخطاء إملائية',
'mispeelingspage' => 'قائمة بالأخطاء الإملائية الشائعة',
'missinglanguagelinks' => 'وصلات مفقودة للغات',
'missinglanguagelinksbutton' => 'إبحث عن وصلة لغة مفقودة ل',
'moredotdotdot' => 'المزيد...',
'move' => 'نقل',
'movearticle' => 'نقل صفحة',
'movedto' => 'تم نقلها إلى',
'movelogpage' => 'سجل النقل',
'movenologin' => 'غير مسجل',
'movepage' => 'نقل صفحة',
'movepagebtn' => 'أنقل الصفحة',
'movepagetalktext' => 'صفحة النقاش المرفقة بالمقالة سيتم نقلها كذلك، إذا وجدت. ولكن لا يتم نقل صفحة النقاش في الحالات التالية:
* نقل الصفحة عبر نطاقات namespaces مختلفة.
* يوجد صفحة نقاش غير فارغة تحت العنوان الجديد للمقالة.
* قمت بإزالة إختيار نقل صفحة النقاش في الأسفل.

وفي الحالات أعلاه، يجب عليك نقل أو دمج محتويات صفحة النقاش يدويا، إذا رغب في ذلك.',
'movepagetext' => 'بإستعمال النموذج أدنه، تستطيع تغيير أسم الصفحة،
ونقل تاريخ الصفحة للإسم الجديد.
سيتم أنشاء تحويل من العنوان القديم، للصفحة بالعنوان الجديد.
لكن، لن يتم تغيير الوصلات في الصفحات التي تتصل بهذه الصفحة، لذا عليك
[[Special:Maintenance|التأكد]] من عدم وجود وصلات مقطوعة، أو وصلات متتالية،
للتأكد من أن المقالات تتصل مع بعضها بشكل مناسب.

يرجى الملاحظة انه \'\'\'لن يتم\'\'\' نقل الصفحة إذا وجدت صفحة بالإسم الجديد،
إلا إذا كانت صفحة فارغة، أو صفحة تحويل، ولا تاريخ لها. وهذا يعني أنك لا تستطيع
وضع صفحة مكان صفحة، كما أنه من الممكن إرجاع الصفحة لمكانها في حال تم النقل بشكل خاطئ.

<b>تحذير!</b>
قد يكون لنقل الصفحة آثار كبيرة، وتغييرا غير متوقع بالنسبة للصفحات المشهورة.
يرجى فهم وإدارك عواقب نقل الصفحات قبل القيام به.',
'movereason' => 'السبب',
'movetalk' => 'أنقل صفحة \'\'\'النقاش\'\'\' أن أمكن.',
'movethispage' => 'أنقل هذه الصفحة',
'mycontris' => 'مساهماتي',
'mypage' => 'صفحتي',
'namespace' => 'النطاق:',
'namespacesall' => 'الكل',
'navigation' => 'تصفح',
'nbytes' => '$1 بايت',
'nchanges' => '$1 تغييرات',
'newarticle' => '(جديد)',
'newarticletext' => 'لقد تبعت وصلة لصفحة لم يتم إنشائها بعد.
لإنشاء هذه الصفحة إبدأ بالكتابة في الصندوق بالأسفل.
(أنظر في [[ويكيبيديا:مساعدة|صفحة المساعدة]] للمزيد من المعلومات)
إذا كانت زيارتك لهذه الصفحة بالخطأ، إضغم على زر \'\'رجوع\'\' في متصفح الإنترنت لديك.',
'newimages' => 'معرض الصور الجديدة',
'newmessages' => 'لديك $1.',
'newmessageslink' => 'رسائل جديدة',
'newpage' => 'صفحة جديدة',
'newpageletter' => 'ج',
'newpages' => 'صفحات جديدة',
'newpassword' => 'كلمة السر الجديدة',
'newtitle' => 'إلى العنوان الجديد',
'newusersonly' => ' (للمستخدمين الجدد فقط)',
'newwindow' => '(يفتح في شباك جديد)',
'next' => 'التالي',
'nextn' => '$1 التالية',
'nlinks' => '$1 وصلة',
'noarticletext' => '(لا يوجد حاليا أي نص في هذه الصفحة)',
'noemail' => 'لا يوجد أي عنوان بريدي مسجل للمستخدم "$1".',
'noemailtext' => 'لم يحدد هذا المستخدم عنوان بريد إلكتروني صحيح،
أو طلب عدم إستلام الرسائل من المستخدمين الآخرين.',
'noemailtitle' => 'لا يوجد عنوان بريد إلكتروني',
'nogomatch' => 'لا يوجد صفحة بنفس العنوان، حاول البحث بشكل مفصل أكثر من خلال إستعمال صندوق البحث أدناه. بإمكانك أيضاً إنشاء <a href="$1" class="new">صفحة جديدة</a> بالعنوان الذي طلبته.',
'nohistory' => 'لا يوجد تاريخ للتغييرات لهذه الصفحة.',
'nolinkshere' => 'لا يوجد صفحات تصل لهذه الصفحة.',
'nolinkstoimage' => 'لا يوجد صفحات تصل لهذه الصورة.',
'noname' => 'لم تحدد إسم مستخدم صحيح.',
'nospecialpagetext' => 'لقد طلبت صفحة خاصة لا يمكن التعرف عليها من قبل نظام الويكي.',
'nosuchspecialpage' => 'لا يوجد صفحة خاصة بهذا الإسم',
'nosuchuser' => 'لا يوجد مستخدم بالإسم "$1".
تأكد من إملاء الإسم، أو إستعمل النموذج الموجود في الأسفل لإنشاء مستخدم جديد.',
'note' => '<strong>ملاحظة:</strong>',
'notextmatches' => 'لم يتم إيجاد أي نص مطابق',
'notitlematches' => 'لم يتم إيجاد أي عنوان مطابق',
'notloggedin' => 'غير مسجل',
'nowatchlist' => 'لا يوجد شيء في قائمة مراقبتك.',
'nowiki_tip' => 'أهمل تهيئة الويكي',
'nstab-category' => 'تصنيف',
'nstab-help' => 'مساعدة',
'nstab-image' => 'صورة',
'nstab-main' => 'مقالة',
'nstab-mediawiki' => 'رسالة',
'nstab-special' => 'خاص',
'nstab-template' => 'قالب',
'nstab-user' => 'صفحة مستخدم',
'nstab-wp' => 'حول',
'ok' => 'موافق',
'oldpassword' => 'كلمة السر القديمة',
'orig' => 'الأصلي',
'orphans' => 'الصفحات اليتيمة',
'otherlanguages' => ' لغات أخرى',
'pagemovedsub' => 'تم النقل بنجاح',
'pagemovedtext' => 'تم نقل الصفحة "[[$1]]" إلى "[[$2]]".',
'passwordremindertitle' => 'تذكير بكلمة السر من ويكيبيديا',
'passwordsent' => 'تم إرسال كلمة سر جديدة إلى العنوان البريدي المسجل للمستخدم "$1".
يرجى محاولة تسجيل الدخول مرة أخرى عند إستلامها.',
'popularpages' => 'الصفحات المشهورة',
'portal' => 'بوابة المجتمع',
'portal-url' => 'ويكيبيديا:بوابة المجتمع',
'postcomment' => 'أرسل تعليق',
'powersearch' => 'بحث',
'preferences' => 'تفضيلات',
'prefsnologin' => 'غير مسجل',
'preview' => 'عرض مسبق',
'previewnote' => 'تذكر، هذا فقط عرض مسبق للصفحة، ولم يتم حفظه بعد!',
'prevn' => '$1 السابقة',
'printableversion' => 'نسخة للطباعة',
'printsubtitle' => '(من http://ar.wikipedia.org)',
'protectcomment' => 'سبب الحماية',
'protectedarticle' => 'حماية [[$1]]',
'protectedpage' => 'صفحة محمية',
'protectlogpage' => 'سجل_الحماية',
'protectpage' => 'صفحة محمية',
'protectthispage' => 'حماية هذه الصفحة',
'qbbrowse' => 'تصفح',
'qbedit' => 'تحرير',
'qbfind' => 'بحث',
'qbmyoptions' => 'صفحاتي',
'qbpageinfo' => 'سياق النص',
'qbpageoptions' => 'هذه الصفحة',
'qbsettings' => 'خيارات لوحة الوصلات',
'qbspecialpages' => 'الصفحات الخاصّة',
'randompage' => 'صفحة عشوائية',
'rclinks' => 'أظهر آخر $1 تعديل في آخر $2 يوم، $3',
'rclistfrom' => 'أظهر التغييرات بدأ من $1',
'rcliu' => '; $1 تعديل من مستخدم مسجل',
'rcloaderr' => 'تحميل التغييرات الأخيرة',
'rclsub' => '(لصفحات تصل بها الصفحة "$1")',
'rcnote' => 'في الأسفل ستجد آخر <strong>$1</strong> تعديل في آخر <strong>$2</strong> أيام.',
'rcnotefrom' => 'في الأسفل التغييرات منذ <b>$2</b> (ولغاية <b>$1</b>).',
'readonly' => 'قاعدة البيانات مغلقة',
'readonlytext' => 'قاعدة البيانات مغلقة حاليا أمام الإضافات والتعديلات، السبب غالبا ما يكون الصيانة، وستعود قاعدة البيانات للوضع الطبيعي قريبا.
عندما تم أغلاق قاعدة البيانات أمام التعديلات والإضافات تم أعطاء السبب التالي:
<p>$1',
'recentchanges' => 'أحدث التغييرات',
'recentchangescount' => 'عدد العناوين في صفحة أحدث التغييرات',
'recentchangeslinked' => 'تغييرات ذات علاقة',
'recentchangestext' => 'تابع آخر التغييرات في الموسوعة من هذه الصفحة.',
'redirectedfrom' => '(تم التحويل من $1)',
'remembermypassword' => 'تذكر كلمة السر عبر الجلسات.',
'removechecked' => 'حذف المواد المختارة من قائمة المراقبة.',
'removedwatch' => 'تم الحذف من قائمة المراقبة',
'removedwatchtext' => 'تم حذف الصفحة "$1" من قائمة مراقبتك.',
'removingchecked' => 'حذف الصفحات المطلوبة من قائمة المراقبة...',
'returnto' => 'أرجع إلى $1.',
'retypenew' => 'أعد كتابة كلمة السر الجديدة',
'revertpage' => 'إسترجاع المقال حتى أخر تعديل من قبل $1',
'revhistory' => 'تاريخ التغييرات',
'revnotfound' => 'النسخة غير موجودة',
'rows' => 'أسطر',
'savearticle' => 'حفظ الصفحة',
'savedprefs' => 'تم حفظ تفضيلاتك.',
'savefile' => 'حفظ الملف',
'saveprefs' => 'حفظ التفضيلات',
'scarytranscludefailed' => '[Template fetch failed; sorry]',
'search' => 'بحث',
'searchdisabled' => '<p>عذرا! لقد تم إيقاف ميزة البحث في النصوص بشكل مؤقت، لأسباب تتعلق بتأثيرها على الأداء العام. في الوقت الحالي من الممكن أن تستعمل محرك البحث جووجل Google بدل من خاصية البحث في النصوص. من الممكن أن لا يكون البحث في جووجل يشمل آخر التعديلات والصفحات.
</p>',
'searchquery' => 'لصيغة البحث "$1"',
'searchresults' => 'نتائج البحث',
'searchresultshead' => 'خيارات نتائج البحث',
'searchresulttext' => 'للمزيد من المعلومات حول البحث في ويكيبيديا، راجع [[ويكيبيديا:تصفح]].',
'selflinks' => 'صفحات بوصلات ذاتية',
'servertime' => 'الوقت في الأجهزة الخادمة الآن هو',
'shortpages' => 'صفحات قصيرة',
'show' => 'عرض',
'showdiff' => 'أظهر الفرق',
'showhideminor' => '$1 التعديلات الطفيفة',
'showpreview' => 'عرض التعديلات',
'showtoc' => 'إظهار',
'sidebar' => '
* navigation
** mainpage|mainpage
** portal-url|portal
** currentevents-url|currentevents
** recentchanges-url|recentchanges
** randompage-url|randompage
** helppage|help
** sitesupport-url|sitesupport',
'sitestats' => 'إحصاءات الموقع',
'sitestatstext' => 'يوجد <b>$1</b> صفحة في قاعدة بيانات الموسوعة العربية، وهذا يشمل صفحات النقاش، والصفحات الخاصة بنظام ويكيبيديا، والمقالات الصغيرة التي تحتاج تطوير، والتحويلات، وغيرها مما لا يرقى لأن يكون مقالا. إذا تم أهمال تلك الصفحات، فإن عدد الصفحات التي قد تحتوي على مقالات يكون <b>$2</b>.<p>
تم عرض الصفحات <b>$3</b> مرة، وعدد التعديلات على الصفحات<b>$4</b> تعديل، منذ إنشاء الموسوعة العربية في يوليو/تموز 2003.
وهذا يعني أن معدل التعديل لكل صفحة <b>$5</b> تعديل، ومعدل عرض كل صفحة <b>$6</b> عرض.

<div dir="ltr">
There are <b>$1</b> total pages in the database.
This includes "talk" pages, pages about Wikipedia, minimal "stub"
pages, redirects, and others that probably don\'t qualify as articles.
Excluding those, there are <b>$2</b> pages that are probably legitimate
articles.<p>
There have been a total of <b>$3</b> page views, and <b>$4</b> page edits
since the Arabic wikipedia creation in July 2003.
That comes to <b>$5</b> average edits per page, and <b>$6</b> views per edit.

</div>',
'sitesubtitle' => ' الموسوعة الحرة',
'sitesupport' => 'التبرعات',
'sitetitle' => 'ويكيبيديا',
'specialpage' => 'صفحة خاصة',
'specialpages' => 'الصفحات الخاصّة',
'spheading' => 'الصفحات الخاصة لكل المستخدمين',
'statistics' => 'إحصاءات',
'storedversion' => 'النسخة المخزنة',
'subcategories' => 'التصنيفات الفرعية',
'subcategorycount' => 'يوجد $1 تصنيف فرعي في هذا التصنيف.',
'subcategorycount1' => 'هناك تصنيف فرعي واحد في هذا التصنيف.',
'subject' => 'موضوع',
'subjectpage' => 'عرض العنوان',
'successfulupload' => 'تحميل الملف بنجاح',
'summary' => 'ملخص',
'sysoptitle' => 'يتطلب صلاحيات مشغل نظام Sysop',
'talkexists' => 'تم نقل الصفحة بنجاح، لكن لم
يتم نقل صفحة النقاش المرافقة، بسبب وجود صفحة نقاش
مسبقا تحت العوان الجديد.
يرجى نقل محتويات صفحة النقاش يدويا، ودمجها مع المحتويات السابقة.',
'talkpage' => 'ناقش هذه الصفحة',
'talkpagemoved' => 'تم نقل صفحة النقاش أيضا.',
'talkpagenotmoved' => '<strong>لم</strong> يتم نقل صفحة النقاش.',
'templatesused' => 'القوالب المستخدمة في هذه الصفحة:',
'textboxsize' => 'أبعاد صندوق النصوص',
'thumbnail-more' => 'تكبير',
'thumbsize' => 'Thumbnail size :',
'toc' => 'فهرست',
'tog-underline' => 'Underline links',
'toolbox' => 'أدوات',
'uclinks' => 'عرض آخر $1 تعديل;  عرض أخر $2 يوم.',
'ucnote' => 'في الأسفل ستجد آخر <b>$1</b> تعديل لهذا المستخدم في <b>$2</b> أيام.',
'uctop' => ' (أعلى)',
'unblocklink' => 'رفع المنع عن مستخدم',
'uncategorizedcategories' => 'تصنيفات غير مصنفة',
'uncategorizedpages' => 'صفحات غير مصنفة',
'undelete' => 'إرجاع صفحات محذوفة',
'unprotect' => 'أزل الحماية',
'unprotectcomment' => 'سبب إزالة الحماية',
'unprotectedarticle' => 'ازالة حماية [[$1]]',
'unprotectthispage' => 'أزل الحماية عن الصفحة',
'unusedimages' => 'صور غير مستعملة',
'upload' => 'تحميل ملف',
'uploadbtn' => 'تحميل الملف',
'uploaddisabled' => 'عذرا، تم إيقاف خاصية تحميل الملفات.',
'uploadedfiles' => 'الملفات المحملة',
'uploadedimage' => 'تم تحميل "$1"',
'uploaderror' => 'خطأ في التحميل',
'uploadlink' => 'تحميل الصور',
'uploadlogpagetext' => 'في الأسفل قائمة بأخر الملفات التي تم تحميلها.
كل الأوقات المعروضة هي حسب توقيت الأجهزةالخادمة (UTC).
<ul>
</ul>',
'uploadnologin' => 'لم تقم بتسجيل الدخول',
'uploadnologintext' => 'يجب أن تكون <a href="/wiki/Special:Userlogin">مسجلا الدخول</a>
لتتمكن من تحميل الملفات.',
'uploadtext' => '<strong>توقف!</strong>
قبل تحميل أي ملف تأكد من قرائتك وثيقة <a href="/wiki/Wikipedia:Image_use_policy">سياسة إستعمال الصور</a>.
إذا وجد ملف بنفس الإسم في ويكيبيديا سيتم إستبدال الملف بدون أي تحذير أو إشعار مسبق.
فإذا لم يكن الملف تحديثا لملف مسبق، تأكد من أن إسم الملف غير موجود من قبل.
لعرض أو البحث في الملفات التي تم تحميلها من قبل إذهب إلى <a href="/wiki/Special:Imagelist">قائمة الملفات المحملة</a>.
يتم تسجيل كل حالات التحميل والحذث للملفات في <a href="/wiki/Wikipedia:Upload_log">سجل التحميل</a>.
<p>إستعمل النموذج الموجود الموجود في الأسفل لتحميل الصور التي تساعد على توضيح مقالاتك.
في أغلب متصفحات الإنترنت سترى زر "إستعراض..." الذي سيفتح لك شاشة إختيار الملفات. إختيار الملف سيملأ الخانة المجاورة للزر بإسم الملف وموقعه.
يجب عليك كذلك التأكيد على أنك لم تخالف أي حقوق نسخ من خلال تحميلك للملف.
إضغط على زر "تحميل الملف" لإنهاء عملية التحميل.
قد تأخذ هذه العملية بعض الوقت، بناء على سرعة الإتصال للإنترنت لديك.
<p>أنواع الملفات المفضلة هنا هي: JPEG للصور الفوتوغرافية، PNG للرسومات وصور الصغيرة، و OGG لملفات الصوتية.
يرجى تسمية الملفات بشكل يوضح المحتوي، وذلك لمنع أي إلتباس.
لوضع الصورة في مقالتك، إستعمل وصلة على النحو التالي:<b><nowiki>[[صورة:file.jpg]]</nowiki></b> أو <b><nowiki>[[صورة:file.jpg|نص بديل]]</nowiki></b> أو <b><nowiki>[[media:file.ogg]]</nowiki></b> للملفات الصوتية.
<p>تذكر أنه بما أن هذا نظام [[ويكي]]، فقد يقوم آخرون بتغيير أو بحذف الملفات التي تم تحميلها إذا كانوا يعتقدون أن هذا في مصلحة الموسوعة. وقد يتم منعك من تحميل الملفات إذا تبين أنك تستعمل النظام بشكل مسيئ.',
'uploadwarning' => 'تحذير تحميل الملفات',
'userexists' => 'إسم المستخدم الذي إخترته مستخدم من قبل، يرجى إختيار إسم مستخدم آخر.',
'userlogin' => 'دخول',
'userlogout' => 'خروج',
'userpage' => 'عرض صفحة المستخدم',
'userstats' => 'إحصاءات المستخدم',
'userstatstext' => 'يوجد <b>$1</b> عضو مسجل. ومنهم <b>$2</b> إداريين. (أنظر $3)
<div dir="ltr">
There are <b>$1</b> registered users.
<b>$2</b> of these are administrators (see $3).
</div>',
'version' => 'رقم النسخة',
'viewcount' => 'تم عرض محتويات هذه الصفحة $1 مرة.',
'viewsource' => 'عرض المصدر للمقالة',
'viewtalkpage' => 'عرض النقاش',
'wantedpages' => 'صفحات مطلوبة',
'watchdetails' => '($1  صفحة يتم مراقبتها، بدون عد صفحات النقاش;
$2 تحرير تم على الصفحات منذ بدأ المراقبة;
$3...
<a href=\'$4\'>عرض وتحرير القائمة الكاملة</a>.)',
'watcheditlist' => 'فيما يلي قائمة مرتبة أبجديا للصفحات الموجودة في قائمة المراقبة لديك.
أختر الصفحات التري تريد إزالتها من خلال الإشارة عليها من الصندوق بجانبها.
وإضغط على زر \'حذف المختارات\' في آخر الصفحة.',
'watchlist' => 'قائمة مراقبتي',
'watchlistcontains' => 'تحتوي قائمة المراقبة لديك على $1 صفحة.',
'watchlistsub' => '(للمستخدم "$1")',
'watchmethod-list' => 'إظهار التحريرات في الصفحات المراقبة',
'watchmethod-recent' => 'تفحص التغييرات الأخيرة في قائمة المراقة لديك',
'watchnochange' => 'لم يتم تعديل أي صفحة في قائمة المراقبة لديك خلال الفترة المحددة.',
'watchnologin' => 'غير مسجل',
'watchnologintext' => 'يجب أن تقوم  <a href="/wiki/Special:Userlogin">بتسجيل الدخول</a>
لتتمكن من تعديل قائمة المراقبة لديك.',
'watchthis' => 'راقب هذه الصفحة',
'welcomecreation' => '<h2>أهلا بك يا , $1!</h2><p> تم إنشاء حسابك.
لا تنسى أن تقوم بتغيير وتحديد تفضيلاتك في ويكيبيديا.',
'whatlinkshere' => 'ماذا يرتبط هنا؟',
'whitelistacctitle' => 'لا يسمح لك بإنشاء إشتراك',
'whitelistedittext' => 'يجب عليك  [[Special:Userlogin|تسجيل الدخول]] لتتمكن من تعديل الصفحات.',
'whitelistedittitle' => 'الدخول ضروري للقيام بالتعديل',
'whitelistreadtext' => 'يجب عليك [[Special:Userlogin|تسجيل الدخول]] لتتمكن من قراءة المقالات.',
'whitelistreadtitle' => 'تسجيل الدخول ضروري للقراءة',
'wikipediapage' => 'عرض الصفحة العامة',
'wlnote' => 'في الأسفل آخر $1 تعديل في آخر <b>$2</b> ساعة.',
'wlsaved' => 'هذه نسخة مخزنة من قائمة المراقبة لديك.',
'wlshowlast' => 'عرض أخر $1 ساعات $2 أيام $3',
'wrongpassword' => 'كلمة السر التي أدخلتها غير صحيحة، يرجى إعادة المحاولة.',
'yourdiff' => 'الفروقات',
'youremail' => 'بريدك الإلكتروني*',
'yourname' => 'اسمك',
'yournick' => 'اللقب الخاص بك (للتواقيع)',
'yourpassword' => 'كلمة السر خاصتك',
'yourpasswordagain' => 'أعد كتابة كلمة السر',
'yourrealname' => 'اسمك الحقيقي*',
'yourtext' => 'النص الذي كتبته',
);

class LanguageAr extends LanguageUtf8 {
	var $digitTransTable = array(
		'0' => '٠',
		'1' => '١',
		'2' => '٢',
		'3' => '٣',
		'4' => '٤',
		'5' => '٥',
		'6' => '٦',
		'7' => '٧',
		'8' => '٨',
		'9' => '٩',
		'%' => '٪',
		'.' => '٫',
		',' => '٬'
	);

	function getNamespaces() {
		global $wgNamespaceNamesAr;
		return $wgNamespaceNamesAr;
	}

	function getMonthAbbreviation( $key ) {
		/* No abbreviations in Arabic */
		return $this->getMonthName( $key );
	}

	function isRTL() {
		return true;
	}

	function linkPrefixExtension() {
		return true;
	}

	function getDefaultUserOptions() {
		$opt = parent::getDefaultUserOptions();

		# Swap sidebar to right side by default
		$opt['quickbar'] = 2;

		# Underlines seriously harm legibility. Force off:
		$opt['underline'] = 0;
		return $opt ;
	}

	function fallback8bitEncoding() {
		return 'windows-1256';
	}

	function getMessage( $key ) {
		global $wgAllMessagesAr;
		if( isset( $wgAllMessagesAr[$key] ) ) {
			return $wgAllMessagesAr[$key];
		} else {
			return parent::getMessage( $key );
		}
	}

	function formatNum( $number ) {
		global $wgTranslateNumerals;
		if( $wgTranslateNumerals ) {
			return strtr( $number, $this->digitTransTable );
		} else {
			return $number;
		}
	}
}

?>
