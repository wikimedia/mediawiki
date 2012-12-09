<?php
/** Kirghiz (Кыргызча)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author AidaBishkek
 * @author Aidabishkek
 * @author Amire80
 * @author Chorobek
 * @author Growingup
 * @author Muratjumashev
 * @author Ztimur
 */

$namespaceNames = array(
	NS_MEDIA            => 'Медиа',
	NS_SPECIAL          => 'Атайын',
	NS_TALK             => 'Баарлашуу',
	NS_USER             => 'Колдонуучу',
	NS_USER_TALK        => 'Колдонуучунун_баарлашуулары',
	NS_PROJECT_TALK     => '$1_баарлашуу',
	NS_FILE             => 'Файл',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_TEMPLATE         => 'Калып',
	NS_HELP             => 'Жардам',
	NS_CATEGORY         => 'Категория',
);

$messages = array(
# User preference toggles
'tog-underline' => 'Шилтемелердин алдын сызуу:',
'tog-justify' => 'Тексти барактын жазысы боюнча түздөө',
'tog-hideminor' => 'Соңку өзгөрүүлөрдө, майда өзгөрүүлөрдү жашыруу',
'tog-usenewrc' => 'Акыркы өзгөрүүлөрдүн жакшыртылган тизмесин колдонуу (JavaScript талап кылынат)',
'tog-numberheadings' => 'Башсаптарды автоматтык түрдө номурлоо',
'tog-showtoolbar' => 'Оңдоо учурунда аспаптар тактасын көрсөтүү (JavaScript талап кылынат)',
'tog-editondblclick' => 'Эки басып баракты оңдоо (JavaScript талап кылынат)',
'tog-editsection' => 'Ар бир секция үчүн «оңдоо» шилтемеси',
'tog-oldsig' => 'Кезектеги кол:',
'tog-showhiddencats' => 'Жашырылган категорияларды көрсөтүү',

'underline-always' => 'Дайыма',
'underline-never' => 'Эч качан',

# Font style option in Special:Preferences
'editfont-style' => 'Оңдоо талаасынын арибинин стили:',
'editfont-default' => 'Браузердин шрифти',
'editfont-monospace' => 'Моножазы шрифт',

# Dates
'sunday' => 'Жекшемби',
'monday' => 'Дүйшөмбү',
'tuesday' => 'Шейшемби',
'wednesday' => 'Шаршемби',
'thursday' => 'Бейшемби',
'friday' => 'Жума',
'saturday' => 'Ишемби',
'sun' => 'Жк',
'mon' => 'Дш',
'tue' => 'Ше',
'wed' => 'Ша',
'thu' => 'Бш',
'fri' => 'Жм',
'sat' => 'Иш',
'january' => 'Январь (Үчтүн айы)',
'february' => 'Февраль (Бирдин айы)',
'march' => 'Март (Жалган куран)',
'april' => 'Апрель (Чын куран)',
'may_long' => 'Май (Бугу)',
'june' => 'Июнь (Кулжа)',
'july' => 'Июль (Теке)',
'august' => 'Август (Баш оона)',
'september' => 'Сентябрь (Аяк оона)',
'october' => 'Октябрь (Тогуздун айы)',
'november' => 'Ноябрь (Жетинин айы)',
'december' => 'Декабрь (Бештин айы)',
'january-gen' => 'Январь (Үчтүн айы)',
'february-gen' => 'Февраль (Бирдин айы)',
'march-gen' => 'Март (Жалган куран)',
'april-gen' => 'Апрель (Чын куран)',
'may-gen' => 'Май (Бугу)',
'june-gen' => 'Июнь (Кулжа)',
'july-gen' => 'Июль (Теке)',
'august-gen' => 'Август (Баш оона)',
'september-gen' => 'Сентябрь (Аяк оона)',
'october-gen' => 'Октябрь (Тогуздун айы)',
'november-gen' => 'Ноябрь (Жетинин айы)',
'december-gen' => 'Декабрь (Бештин айы)',
'jan' => 'Янв',
'feb' => 'Фев',
'mar' => 'Март',
'apr' => 'Апр',
'may' => 'Май',
'jun' => 'Июнь',
'jul' => 'Июль',
'aug' => 'Авг',
'sep' => 'Сент',
'oct' => 'Окт',
'nov' => 'Ноя',
'dec' => 'Дек',

# Categories related messages
'pagecategories' => '{{PLURAL:$1|Категория|Категориялар}}',
'category_header' => '"$1" категориядагы барактар',
'subcategories' => 'Ички категориялар',
'category-media-header' => '"$1" категориясындагы медиафайлдар',
'category-empty' => "''Бул категорияда азыр эч бир барак же файл жок.''",
'hidden-categories' => '{{PLURAL:$1|Жашырылган категория|Жашырылган категориялар}}',
'hidden-category-category' => 'Жашырылган категориялар',
'category-subcat-count' => '{{PLURAL:$2|Бул категория төмөнкү көмөк категорияны камтыйт.|Бул категорияда жалпы $2, {{PLURAL:$1|көмөк категория|$1 көмөк категория}} бар.}}',
'category-subcat-count-limited' => 'Бул категорияда {{PLURAL:$1|$1|$1|$1}} ички категория бар.',
'category-article-count' => '{{PLURAL:$2|Бул категория төмөнкү баракты камтыйт.|Бул категорияда жалпы $2, төмөнкү {{PLURAL:$1|барак|$1 барак}} бар.}}',
'category-article-count-limited' => 'Бул категорияда {{PLURAL:$1|$1 барак}} бар.',
'category-file-count' => '{{PLURAL:$2|Бул категория төмөнкү файлды камтыйт.|Бул категорияда жалпы $2, төмөнкү {{PLURAL:$1|файл|$1 файл}} бар.}}',
'category-file-count-limited' => 'Бул категорияда {{PLURAL:$1|$1|$1|$1}} файл бар.',
'listingcontinuesabbrev' => 'уланд.',
'index-category' => 'Индекстелген барактар',
'noindex-category' => 'Индекстелбеген барактар',
'broken-file-category' => 'Файлдарга туура эмес шилтеме берген барактар',

'about' => 'Тууралуу',
'article' => 'Макала',
'newwindow' => '(жаңы терезеде ачылат)',
'cancel' => 'Жокко чыгаруу',
'moredotdotdot' => 'Уландысы...',
'mypage' => 'Барак',
'mytalk' => 'Талкуулоо',
'anontalk' => 'Бул IP дарек үчүн талкуу',
'navigation' => 'Навигация',
'and' => '&#32;жана',

# Cologne Blue skin
'qbfind' => 'Табуу',
'qbbrowse' => 'Карап чыгуу',
'qbedit' => 'Оңдоо',
'qbpageoptions' => 'Бул барак',
'qbmyoptions' => 'Барактарым',
'qbspecialpages' => 'Атайын барактар',
'faq' => 'КБС',
'faqpage' => 'Project:КБС',

# Vector skin
'vector-action-addsection' => 'Тема кошумчалоо',
'vector-action-delete' => 'Өчүрүү',
'vector-action-move' => 'Аталышын өзгөртуу',
'vector-action-protect' => 'Коргоо',
'vector-action-undelete' => 'Калыбына келтирүү',
'vector-action-unprotect' => 'Коргоону өзгөртүү',
'vector-view-create' => 'Жаратуу',
'vector-view-edit' => 'Оңдоо',
'vector-view-history' => 'Тарыхын кароо',
'vector-view-view' => 'Окуу',
'vector-view-viewsource' => 'Кайнарын кароо',
'actions' => 'Аракеттер',
'namespaces' => 'Аталыш топтому',
'variants' => 'Варианттар',

'navigation-heading' => 'Навигация менюсу',
'errorpagetitle' => 'Ката',
'returnto' => '$1 барагына кайтуу.',
'tagline' => '{{SITENAME}} дан',
'help' => 'Жардам',
'search' => 'Издөө',
'searchbutton' => 'Издөө',
'go' => 'Өтүү',
'searcharticle' => 'Алга',
'history' => 'Барактын тарыхы',
'history_short' => 'Тарых',
'printableversion' => 'Басма үлгүсү',
'permalink' => 'Туруктуу шилтеме',
'print' => 'Басып чыгаруу',
'view' => 'Кароо',
'edit' => 'Оңдоо',
'create' => 'Жаратуу',
'editthispage' => 'Бул баракты оңдоо',
'create-this-page' => 'Бул баракты түзүү',
'delete' => 'Өчүрүү',
'deletethispage' => 'Бул баракты өчүрүү',
'protect' => 'Коргоо',
'protect_change' => 'өзгөртүү',
'protectthispage' => 'Бул баракты коргоо',
'unprotect' => 'Коргоону өзгөртүү',
'newpage' => 'Жаңы барак',
'talkpage' => 'Бул баракты талкууга алуу',
'talkpagelinktext' => 'талкуулоо',
'specialpage' => 'Атайын барак',
'personaltools' => 'Жеке аспаптар',
'postcomment' => 'Жаңы бөлүм',
'articlepage' => 'Макаланы кароо',
'talk' => 'Талкуулоо',
'views' => 'Көрсөтүүлөр',
'toolbox' => 'Аспаптар',
'userpage' => 'Катышуучунун барагын кароо',
'projectpage' => 'Долбоор барагын кароо',
'imagepage' => 'Файлдын барагын кароо',
'mediawikipage' => 'Кабардын  барагын кароо',
'templatepage' => 'Калыптын барагын кароо',
'viewhelppage' => 'Жардам барагы',
'categorypage' => 'Категория барагын көрсөтүү',
'viewtalkpage' => 'Талкууну кароо',
'otherlanguages' => 'Башка тилдерде',
'redirectedfrom' => '($1 барагындан багытталды)',
'redirectpagesub' => 'Айдама барак',
'lastmodifiedat' => 'Бул барак соңку жолу $1, $2 өзгөртүлгөн.',
'viewcount' => 'Бул барак {{PLURAL:$1|$1|$1}} жолу ачылды.',
'protectedpage' => 'Корголгон барак',
'jumpto' => 'Өтүү:',
'jumptonavigation' => 'багыттоо',
'jumptosearch' => 'издөө',
'pool-errorunknown' => 'Белгисиз ката',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite' => '{{SITENAME}} тууралуу',
'aboutpage' => 'Project:тууралуу',
'copyrightpage' => '{{ns:project}}:Автордук укуктар',
'currentevents' => 'Учурдагы окуялар',
'currentevents-url' => 'Project:Учурдагы окуялар',
'disclaimers' => 'Жоопкерчиликтен баш тартуу',
'disclaimerpage' => 'Project:Жалпы жоопкерчиликтен баш тартуу',
'edithelp' => 'Оңдоого жардам',
'edithelppage' => 'Help:Оңдоо',
'helppage' => 'Help:Мазмун',
'mainpage' => 'Башбарак',
'mainpage-description' => 'Башбарак',
'policy-url' => 'Project:Эрежелер',
'portal' => 'Жамаат порталы',
'portal-url' => 'Project:Жамаат порталы',
'privacy' => 'Маалыматты купуя сактоо саясаты',
'privacypage' => 'Project:Маалыматты купуя сактоо саясаты',

'badaccess' => 'Кирүү катасы',

'ok' => 'OK',
'retrievedfrom' => '"$1" булагындан алынды',
'youhavenewmessages' => 'Сизге $1 ($2) бар.',
'newmessageslink' => 'жаңы билдирүүлөр',
'newmessagesdifflink' => 'соңку өзгөрүү',
'youhavenewmessagesmulti' => 'Сизге $1 жаңы кат бар.',
'editsection' => 'оңдоо',
'editold' => 'оңдоо',
'viewsourceold' => 'байкоо',
'editlink' => 'оңдоо',
'viewsourcelink' => 'Байкоо',
'editsectionhint' => '$1 бөлүмүн оңдоо',
'toc' => 'Мазмун',
'showtoc' => 'көрсөтүү',
'hidetoc' => 'Жашыруу',
'collapsible-collapse' => 'Түрүү',
'collapsible-expand' => 'Жаюу',
'viewdeleted' => 'Көрүү $1?',
'site-rss-feed' => '$1 RSS тилкеси',
'site-atom-feed' => '$1 Atom агымы',
'page-atom-feed' => '"$1" Atom агымы',
'red-link-title' => '$1 (мындай барак жок)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main' => 'Макала',
'nstab-user' => 'Колдонуучунун барагы',
'nstab-media' => 'Мультимедиа',
'nstab-special' => 'Атайын барак',
'nstab-project' => 'Долбоордун барагы',
'nstab-image' => 'Файл',
'nstab-mediawiki' => 'Билдирме',
'nstab-template' => 'Шаблон',
'nstab-help' => 'Жардам',
'nstab-category' => 'Категория',

# General errors
'error' => 'Ката',
'databaseerror' => 'Маалымат базасынын катасы',
'missing-article' => 'Табылууга тийиш «$1» $2 деп аталган баракта текст маалыматтар базасында табылган жок.

Бул сыяктуу абал өчүрүлгөн барактын өзгөрүүлөрдүн тарыхына эски шилтеме менен өткөндө учурайт.

Эгерде башка себеби бар болсо, анда Сиз программалык жабдууда ката таптыңыз. Кичи пейилдикке, ушул URL көрсөтүп [[Special:ListUsers/sysop|администраторлордун]] бирине кабарлап коюңуз.',
'missingarticle-rev' => '(версия#: $1)',
'internalerror' => 'Ички ката',
'internalerror_info' => 'Ички ката: $1',
'fileappenderrorread' => 'Аягына кошуу үчүн «$1» файлы ачылбады.',
'fileappenderror' => '"$1" файлы "$2" файлынын аягына кошулбады.',
'filecopyerror' => '"$1" файлы "$2" файлына көчүрүлбөдү.',
'filedeleteerror' => '"$1" файлын өчүрүүгө болбоду.',
'directorycreateerror' => '"$1" каталогун түзүүгө болбоду.',
'filenotfound' => '"$1" файлын табууга мүмкүн эмес.',
'fileexistserror' => '"$1" файлына жазууга болбоду: Мурдатан бар.',
'unexpected' => 'Күтүлбөгөн маани: "$1"="$2".',
'formerror' => 'Ката: Форманы жөнөтүүгө болбоду.',
'badarticleerror' => 'Бул аракетти бул баракта аткарууга болбой.',
'cannotdelete-title' => '"$1" барагын өчүрүүгө болбойт',
'badtitle' => 'Ыксыз аталыш',
'badtitletext' => 'Талап кылынган барак аталышы туура эмес, бош, же тилдер-аралык же уики-аралык аталышы туура эмес шилтемеленген.
Балким аталышта колдонулбай турган бир же андан көп белги камтылган.',
'viewsource' => 'Кароо',
'viewsource-title' => '$1 барагынын баштапкы кодун көрүү',
'ns-specialprotected' => 'Атайын барактарды оңдоого мүмкүн эмес.',

# Virus scanner
'virus-scanfailed' => 'скандоо катасы (код $1)',
'virus-unknownscanner' => 'белгисиз антивирус:',

# Login and logout pages
'welcomeuser' => 'Кош келиңиз, $1!',
'yourname' => 'Колдонуучу аты',
'yourpassword' => 'Сырсөз',
'yourpasswordagain' => 'Сырсөздү кайра жазыңыз',
'remembermypassword' => 'Бул браузерде каттоо маалыматтарымды эске тут (эң көп $1 {{PLURAL:$1|күн|күн}})',
'yourdomainname' => 'Сиздин домен',
'login' => 'Кирүү',
'nav-login-createaccount' => 'Кирүү / Каттоо',
'loginprompt' => '{{SITENAME}} сайтына кирүү үчүн «cookies» колдонууга уруксатыңыз керек .',
'userlogin' => 'Кирүү / Каттоо',
'userloginnocreate' => 'Кирүү',
'logout' => 'Чыгуу',
'userlogout' => 'Чыгуу',
'notloggedin' => 'Сиз системага кире элексиз',
'nologin' => 'Каттай элексизби? $1.',
'nologinlink' => 'Каттоону баштоо',
'createaccount' => 'Эсеп жазуусун жаратуу',
'gotaccount' => 'Катталгансызбы? $1.',
'gotaccountlink' => 'Кирүү',
'userlogin-resetlink' => 'Кирүүчү маалыматарыңызды унутуп калдыңызбы?',
'createaccountmail' => 'Электрондук дарек боюнча',
'createaccountreason' => 'Себеби:',
'badretype' => 'Сиз киргизген сырсөздөр дал келишпейт',
'userexists' => 'Сиз тандаган колдонуучунун аты бош эмес.',
'loginerror' => 'Колдонуучуну таанууда ката кетти',
'createaccounterror' => '$1 эсебин түзүү мүмкүн эмес',
'loginsuccesstitle' => 'Сиз ийгиликтүү кирдиңиз',
'wrongpassword' => 'Ката сырсөз киргизилди. Кайтадан аракет кылып көрүңүз.',
'wrongpasswordempty' => 'Сырсөз киргизилген жок. Кайтадан аракет кылып көрүңүз.',
'mailmypassword' => 'Жаңы сырсөздү e-mail аркылуу жиберүү',
'emailconfirmlink' => 'Электрондук дарегиңизди ырастаңыз',
'accountcreated' => 'Эсеп жазуусу жаратылды',
'loginlanguagelabel' => 'Тил: $1',

# Change password dialog
'resetpass' => 'Сырсөздү өзгөртүү',
'resetpass_header' => 'Эсеп жазуусунун сырсөзүн өзгөртүү',
'oldpassword' => 'Эски сырсөз:',
'newpassword' => 'Жаңы сырсөз:',
'retypenew' => 'Жаңы сырсөздү кайта киргизиңиз:',
'resetpass_forbidden' => 'Сырсөздү өзгөртүүгө мүмкүн эмес',
'resetpass-submit-loggedin' => 'Сырсөздү өзгөртүү',
'resetpass-submit-cancel' => 'Жокко чыгаруу',
'resetpass-temp-password' => 'Убактылуу сырсөз:',

# Special:PasswordReset
'passwordreset' => 'Сырсөздү түшүрүү',
'passwordreset-legend' => 'Сырсөздү түшүрүү',
'passwordreset-username' => 'Колдонуучу аты:',
'passwordreset-domain' => 'Домен:',
'passwordreset-email' => 'E-mail дарек:',
'passwordreset-emailelement' => 'Колдонуучу аты: $1
Убактылуу сырсөз: $2',

# Special:ChangeEmail
'changeemail' => 'E-mail даректи өзгөртүү',
'changeemail-oldemail' => 'Кезектеги e-mail дарек:',
'changeemail-newemail' => 'Жаңы e-mail дарек:',
'changeemail-none' => '(жок)',
'changeemail-submit' => "E-mail'ди өзгөртүү",
'changeemail-cancel' => 'Жокко чыгаруу',

# Edit page toolbar
'bold_sample' => 'Кара текст',
'bold_tip' => 'Кара текст',
'italic_sample' => 'Курсив текст',
'italic_tip' => 'Курсив текст',
'link_sample' => 'Шилтеме аты',
'link_tip' => 'Ички шилтеме',
'extlink_sample' => 'http://www.example.com шилтеме аталышы',
'extlink_tip' => 'Сырткы шилтемелерге (http:// префиксин койгонду унутпаңыз)',
'headline_sample' => 'Ат тексти',
'headline_tip' => '2-деңгээлдеги баш аты',
'nowiki_sample' => 'Форматталбаган текстти бул жерге киргизиңиз',
'nowiki_tip' => 'Уики-форматтоого көңүл бурбоо',
'image_tip' => 'Кыстарылган файл',
'media_tip' => 'Файлга шилтеме',
'sig_tip' => 'Кол тамгаңыз жана убакыт мөөрү',
'hr_tip' => 'Туурасынын сызык (жыш колдонбоңуз)',

# Edit pages
'summary' => 'Кыска түшүндүрүү:',
'minoredit' => 'Майда оңдоо',
'watchthis' => 'Бул баракты көзөмөлдөө',
'savearticle' => 'Баракты сактоо',
'preview' => 'Алдын ала көрүү',
'showpreview' => 'Алдын ала көрсөтүү',
'showlivepreview' => 'Ылдам карап чыгуу',
'showdiff' => 'Өзгөртүүлөрдү көрсөтүү',
'anoneditwarning' => "'''Эскертүү:''' Сиз каттоодон өткөн жоксуз.
IP дарегиңиз бул барактын оңдоо тарыхына жазылат.",
'blockedtitle' => 'Катышуучу тосмолонгон',
'blockedtext' => 'Сиздин колдонуучу атыңыз же IP дарегиңиз тосмолонгон',
'blockednoreason' => 'себеби көрсөтүлгөн жок',
'nosuchsectiontitle' => 'Бөлүктүү табууга мүмкүн эмес',
'loginreqtitle' => 'Колдонуучунун аты талап кылынат',
'loginreqlink' => 'Кирүү',
'accmailtitle' => 'Сырсөз жөнөтүлдү.',
'accmailtext' => ' [[User talk:$1|$1]] үчүн сырсөз $2 ге жөнөтүлдү.',
'newarticle' => '(Жаңы)',
'newarticletext' => "Сиз ачыла элек баракка шилтемени бастыңыз.
Бул баракты ачуу үчүн, ылдый жактагы терезечеге жаза баштаңыз (кошумча маалымат алуу үчүн [[{{MediaWiki:Helppage}}|жардам барагы]] караңыз).
Эгерде Сиз бул жерге жаңылыштык менен кирип калсаңыз, анда браузериңизде '''артка''' баскычын басыңыз.",
'noarticletext' => "Азыр бул баракта текст жок.
Сиз [[Special:Search/{{PAGENAME}}|ушул аталыш менен баракты изде]] башка барактарда 
<span class=\"plainlinks\">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} тийиштүү жазууларды таба аласыз],
же '''[{{fullurl:{{FULLPAGENAME}}|action=edit}} ошондой аталыш менен барак ача аласыз]'''</span>.",
'noarticletext-nopermission' => 'Азыр бул баракта текст жок.
Сиз башка барактардан [[Special:Search/{{PAGENAME}}|ушул аталыш менен баракты издөө]] салып,
же <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} тийиштүү жазууларды таба аласыз]</span>.',
'userpage-userdoesnotexist' => '"$1" Мындай колдонуучу катталган эмес. Ушул баракты түзүүнү же оңдогонду каалганыңыз анык болсун',
'updated' => '(Жаңыртылды)',
'note' => "'''Эскертүү:'''",
'previewnote' => "'''Бул алдын ала көрүнүшү гана болгонун эсиңизге алыңыз.'''
Өзгөртүүлөрүңүз сактала элек!",
'continue-editing' => 'Редакторлоону улантуу',
'session_fail_preview' => 'Кечиресиз, байланыш үзүлгөндүктөн сиздин өзгөртүүлөр сакталган жок. Дагы бир жолу аракет кылып көрүңүз. Болбосо, [[Special:UserLogout|logging out]] аткарып, кайра кирип көрүңүз.',
'editing' => 'Оңдоо $1',
'creating' => '$1 түзүлүүдө',
'editingsection' => '$1 (бөлүмү) оңдолууда',
'editingcomment' => ' $1 оңдолууда (жаңы бөлүм)',
'yourtext' => 'Текстиңиз',
'yourdiff' => 'Айырмалар',
'templatesused' => 'Бул баракта колдонулган {{PLURAL:$1|калып |калыптар}}:',
'template-protected' => '(корголгон)',
'template-semiprotected' => '(жарым-жартылай корголгон)',
'hiddencategories' => 'Бул барак {{PLURAL:$1|1 жашыруун категориянын|$1 жашыруун категориялардын}} мүчөсү:',
'nocreatetitle' => 'Барактарды жаратуу чектелген',
'permissionserrorstext-withaction' => 'Сизге $2, төмөнкү {{PLURAL:$1|себеп|себеп}} менен уруксат жок:',
'recreate-moveddeleted-warn' => "'''Эскертүү: Сиз мурун өчүрүлгөн баракты кайра баштап жатасыз.'''

Бул баракты кайра кайтаруу чындап керек экендигине көзүңүз жетсин.
Ыңгайлуулук үчүн төмөндө өчүрүүлөрдүн жана өзгөртүүлөрдүн тизмеси берилген:",
'moveddeleted-notice' => 'Бул барак өчүрүлгөн.
Маалымат үчүн төмөндө өчүрүүлөрдүн жана өзгөртүүлөрдүн тизмеси берилген.',
'edit-conflict' => 'Өзгөртүүлөрдүн конфликти',
'edit-already-exists' => 'Жаңы барак түзүү мүмкүн эмес. Мындай барак бар',

# Content models
'content-model-wikitext' => 'уики-текст',
'content-model-text' => 'жөнөкөй текст',
'content-model-javascript' => 'JavaScript',
'content-model-css' => 'CSS',

# Parser/template warnings
'post-expand-template-inclusion-warning' => "'''Эскертүү:''' Камтылган калыптардын өлчөмү өтө чоң.
Кээ бир калыптар камтылбайт.",
'post-expand-template-inclusion-category' => 'Камтылган калыптардын өлчөмү ашып кеткен барактар',
'post-expand-template-argument-warning' => "'''Эскертүү:''' Бул барак, жок дегенде, абдан чоң көлөмдүү калыптын бир жүйөсүн камтыйт жана  жайылганда өлчөмү абдан чоң болуп кетет. 
Ушул сыяктуу жүйөлөр аттатылды.",
'post-expand-template-argument-category' => 'Калыптардын аттатылган жүйөлөрүн камтыган барактар',
'parser-template-loop-warning' => 'Калыптарда айланма бар:[[$1]]',

# History pages
'viewpagelogs' => 'Бул барак үчүн журналды көрсөтүү',
'nohistory' => 'Бул барактын өзгөртүүлөр тарыхы жок',
'currentrev' => 'Акыркы версиясы',
'currentrev-asof' => '$1 -га соңку версиясы',
'revisionasof' => '$1 -деги абалы',
'revision-info' => '$1 карата $2 тарабынан жасалган версия',
'previousrevision' => 'Мурунку версиясы',
'nextrevision' => 'Жаңыраак версиясы →',
'currentrevisionlink' => 'Соңку версиясы',
'cur' => 'учрдг.',
'next' => 'кийинки',
'last' => 'соңку',
'page_first' => 'биринчи',
'page_last' => 'акыркы',
'histlegend' => "Айырмаларды тандоо: Салыштырыла турган версияларлын тушундагы тегеректерди белгилеп туруп \"Enter\"-ди же астындагы баскычты бас.<br />
Түшүндүрүү: '''({{int:cur}})''' = соңку версиясынан айырма, '''({{int:last}})''' = мурунку версиясынан айырма, '''{{int:minoreditletter}}''' = майда оңдоо.",
'history-fieldset-title' => 'Тарыхын кароо',
'history-show-deleted' => 'Өчүрүлгөндөрдү гана',
'histfirst' => 'Эң эски',
'histlast' => 'Соңку',
'historyempty' => 'бош',

# Revision feed
'history-feed-title' => 'Өзгөртүүлөр тарыхы',
'history-feed-item-nocomment' => '$1, $2 карата',

# Revision deletion
'rev-delundel' => 'көрсөтүү/жашыруу',
'rev-showdeleted' => 'көрсөтүү',
'revdelete-show-file-submit' => 'Ооба',
'revdelete-hide-image' => 'Файл мазмунун жашыруу',
'revdelete-radio-same' => '(өзгөртпөө)',
'revdelete-radio-set' => 'Ооба',
'revdelete-radio-unset' => 'Жок',
'revdelete-log' => 'Себеп:',
'revdel-restore' => 'көрүнүшүн өзгөртүү',
'revdel-restore-deleted' => 'өчүрүлгөн версиялар',
'revdel-restore-visible' => 'көрүнүүчү версиялары',
'pagehist' => 'Барактын тарыхы',
'deletedhist' => 'Өчүрүүлөрдүн тарыхы',
'revdelete-reasonotherlist' => 'Башка себеп',
'revdelete-edit-reasonlist' => 'Өчүрүү себептерин оңдоо',
'revdelete-offender' => 'Барак версиясынын автору:',

# History merging
'mergehistory' => 'Барактардын тарыхын бириктирүү',
'mergehistory-from' => 'Баштапкы барак:',
'mergehistory-submit' => 'Версияларды бириктирүү',
'mergehistory-reason' => 'Себеп',

# Merge log
'mergelog' => 'Бириктирүүлөрдүн журналы',
'revertmerge' => 'Бөлүү',

# Diffs
'history-title' => '"$1" өзгөрүүлөр тарыхы',
'lineno' => '$1 -сап:',
'compareselectedversions' => 'Тандалган версияларды салыштыруу',
'editundo' => 'жокко чыгаруу',
'diff-multi' => '({{PLURAL:$2|колдонуучу|$2 колдонуучу}} тарабынан жасалган {{PLURAL:$1|аралык версия|$1 аралык версия}} көрсөтүлгөн жок)',

# Search results
'searchresults' => 'Издөө жыйынтыктары',
'searchresults-title' => '"$1" үчүн издөө жыйынтыктары',
'prevn' => 'абалкы {{PLURAL:$1|$1}}',
'nextn' => 'кийинки{{PLURAL:$1|$1}}',
'prevn-title' => 'Мурунку $1 {{PLURAL:$1|жыйынтык}}',
'nextn-title' => 'Кийинки $1 {{PLURAL:$1|жыйынтык}}',
'shown-title' => 'Барактан $1 {{PLURAL:$1|жыйынтыкты}} көрсөтүү',
'viewprevnext' => '($1 {{int:pipe-separator}} $2) ($3) кароо',
'searchmenu-legend' => 'Издөө опциялары',
'searchmenu-exists' => "'''Бул Уикиде \"[[:\$1]]\" деп аталган барак бар.'''",
'searchmenu-new' => "'''Бул Уикиде \"[[:\$1]]\" барагын түз!'''",
'searchhelp-url' => 'Help:Мазмун',
'searchprofile-articles' => 'Негизги барактар',
'searchprofile-project' => 'Жардам жана долбоор барактары',
'searchprofile-images' => 'Мултимедиа',
'searchprofile-everything' => 'Баары',
'searchprofile-advanced' => 'Кеңейтилген',
'searchprofile-articles-tooltip' => '$1 -де издөө',
'searchprofile-project-tooltip' => '$1 -де издөө',
'searchprofile-images-tooltip' => 'Файлдарды издөө',
'searchprofile-everything-tooltip' => 'Баардык барактардан (талкуу барактарды кошо) издөө',
'searchprofile-advanced-tooltip' => 'Белгиленген аталыш топтомдорунан издөө',
'search-result-size' => '$1 ({{PLURAL:$2|1 сөз|$2 сөз}})',
'search-result-category-size' => '{{PLURAL:$1|1 мүчө|$1 мүчө}} ({{PLURAL:$2|1 көмөк категория|$2 көмөк категория}}, {{PLURAL:$3|1 файл|$3 файл}})',
'search-redirect' => '($1 кайра багыттоо)',
'search-section' => '($1 бөлүмү)',
'search-suggest' => 'Ушуну кааладыңызбы: $1',
'search-interwiki-caption' => 'Тектеш долбоорлор',
'search-interwiki-default' => '$1 жыйын.:',
'search-interwiki-more' => '(көбүрөөк)',
'searchrelated' => 'байланыштуу',
'searchall' => 'баары',
'showingresultsheader' => "'''$4''' үчүн {{PLURAL:$5|'''$3''' жыйынтыктан '''$1'''-и|'''$1 - $2''' -дан '''$3''' жыйынтык}}",
'search-nonefound' => 'Талапка төп маалымат табылган жок.',
'powersearch' => 'Кеңейтилген издөө',
'powersearch-legend' => 'Кеңейтилген издөө',
'powersearch-field' => 'Издөө',
'powersearch-toggleall' => 'Баары',
'powersearch-togglenone' => 'Эчтеке',
'search-external' => 'Тышкы издөө',

# Quickbar
'qbsettings' => 'Навигация панели',
'qbsettings-none' => 'Көрсөтпөө',

# Preferences page
'preferences' => 'Ырастоолор',
'mypreferences' => 'Ырастоолор',
'prefs-edits' => 'Өзгөртүүлөрдүн саны',
'changepassword' => 'Сырсөздү өзгөртүү',
'skin-preview' => 'Алдын ала көрүү',
'prefs-beta' => 'Бета-мүмкүнчүлүктөр',
'prefs-datetime' => 'Дата жана убакыт',
'prefs-user-pages' => 'Катышуучунун барактары',
'prefs-rc' => 'Соңку өзгөрүүлөр',
'prefs-watchlist' => 'Байкоо тизмеси',
'prefs-resetpass' => 'Сырсөздү өзгөртүү',
'prefs-email' => 'Электрондук почтанын параметрлери',
'saveprefs' => 'Сактоо',
'prefs-editing' => 'Редакторлоо',
'rows' => 'Сап:',
'columns' => 'Тилке:',
'searchresultshead' => 'Издөө',
'stub-threshold-disabled' => 'Өчүрүлгөн',
'timezonelegend' => 'Сааттык алкак:',
'localtime' => 'Жердик убакыт:',
'timezoneoffset' => 'Жылыш¹:',
'servertime' => 'Сервер убагы:',
'timezoneregion-africa' => 'Африка',
'timezoneregion-america' => 'Америка',
'timezoneregion-antarctica' => 'Антарктика',
'timezoneregion-arctic' => 'Арктика',
'timezoneregion-asia' => 'Азия',
'timezoneregion-atlantic' => 'Атлантика океаны',
'timezoneregion-australia' => 'Австралия',
'timezoneregion-europe' => 'Европа',
'timezoneregion-indian' => 'Индий океаны',
'timezoneregion-pacific' => 'Тынч океаны',
'prefs-searchoptions' => 'Издөө',
'default' => 'жарыяланбасча',
'prefs-files' => 'Файлдар',
'youremail' => 'Электрондук дарек:',
'username' => 'Катышуучунун аты:',
'uid' => 'Колдонуучунун ID си:',
'yourrealname' => 'Анык атыңыз:',
'yourlanguage' => 'Тил:',
'yourvariant' => 'Мазмундун тил варианты:',
'yournick' => 'Жаңы колуңуз:',
'yourgender' => 'Жыныс:',
'gender-male' => 'Эркек',
'gender-female' => 'Аял',
'email' => 'Электрондук дарек',
'prefs-help-email' => 'Электрондук дарек милдетүү эмес, бирок сырсөздү унутуп калсаңыз ал сырсөздү жиберүүгө керек.',
'prefs-help-email-others' => 'Ошондой эле башкалар сиз менен колдонуучу же талкуу барактарыңыздагы шилтеме аркылуу байланыш түзүүгө уруксат берүүнү тандай аласыз.
Электрондук дарегиңиз башка кодонуучуларга байланыш түзгөндө көрүнбөйт.',
'prefs-info' => 'Негизги маалыматтары',
'prefs-i18n' => 'Интернационализация',
'prefs-signature' => 'Кол тамга',
'prefs-dateformat' => 'Дата форматы',
'prefs-advancedediting' => 'Кеңейтилген ырастоолор',
'prefs-advancedrc' => 'Кеңейтилген ырастоолор',
'prefs-advancedrendering' => 'Кеңейтилген ырастоолор',
'prefs-advancedsearchoptions' => 'Кеңейтилген ырастоолор',
'prefs-advancedwatchlist' => 'Кеңейтилген ырастоолор',
'prefs-displayrc' => 'Көрсөтүүнүн ырастоолору',
'prefs-displaysearchoptions' => 'Көрсөтүүнүн ырастоолору',
'prefs-displaywatchlist' => 'Көрсөтүүнүн ырастоолору',

# User rights
'userrights-reason' => 'Себеп:',

# Groups
'group' => 'Топ:',
'group-user' => 'Катышуучулар',
'group-bot' => 'Боттор',
'group-sysop' => 'Администраторлор',
'group-bureaucrat' => 'Бюрократтар',
'group-all' => '(баары)',

'group-user-member' => '{{GENDER:$1|катышуучу}}',
'group-bot-member' => '{{GENDER:$1|бот}}',
'group-sysop-member' => '{{GENDER:$1|администратор}}',
'group-bureaucrat-member' => '{{GENDER:$1|бюрократ}}',

'grouppage-user' => '{{ns:project}}:Катышуучулар',
'grouppage-bot' => '{{ns:project}}:Боттор',
'grouppage-sysop' => '{{ns:project}}:Администраторлор',

# Rights
'right-edit' => 'Барактарды оңдоо',
'right-move' => 'Барактарды ташуу',
'right-movefile' => 'Файлдарды ташуу',
'right-upload' => 'Файлдарды жүктөө',
'right-delete' => 'Барактарды өчүрүү',
'right-browsearchive' => 'Өчүрүлгөн барактарды издөө',
'right-suppressionlog' => 'Жеке журналдарды көрүү',
'right-userrights' => 'Бүт катышуучулардын укуктарын оңдоо',

# User rights log
'rightsnone' => '(жок)',

# Associated actions - in the sentence "You do not have permission to X"
'action-read' => 'бул баракты окуу',
'action-edit' => 'бул баракты оңдоо',
'action-createpage' => 'барактарды жаратуу',
'action-createtalk' => 'талкуулоо барагын жаратуу',
'action-createaccount' => 'бул эсеп жазуусун жаратуу',
'action-upload' => 'бул файлды жүктөө',
'action-delete' => 'бул баракты өчүрүү',
'action-suppressionlog' => 'бул жеке журналды көрүү',
'action-userrights' => 'бүт катышуучулардын укуктарын оңдоо',
'action-sendemail' => 'электрондук каттарды жөнөтүү',

# Recent changes
'nchanges' => '$1 {{PLURAL:$1|өзгөрүү|өзгөрүү}}',
'recentchanges' => 'Соңку өзгөрүүлөр',
'recentchanges-legend' => 'Соңку өзгөртүүлөрдүн ырастоолору',
'recentchanges-summary' => 'Уикидеги соңку өзгөрүүлөрдү ушул барактан көзөмөлдө.',
'recentchanges-feed-description' => 'Ушул агымдагы уикидеги соңку өзгөрүүлөрдү көзөмөлдө.',
'recentchanges-label-newpage' => 'Бул оңдоо жаңы баракты жаратты',
'recentchanges-label-minor' => 'Бул майда оңдоо',
'recentchanges-label-bot' => 'Бул оңдоо бот тарабынан жасалды',
'recentchanges-label-unpatrolled' => 'Бул оңдоо көзөмөлдөн өтө элек.',
'rcnote' => "Ылдый жакта $5, $4 карата соңку {{PLURAL:$2|күндө|'''$2''' күндө}} жасалган {{PLURAL:$1| '''1''' өзгөрүү| '''$1''' өзгөрүү}}.",
'rcnotefrom' => "'''$2''' -тан өзгөрүүлөр ылдый жакта ('''$1''' чейин көрсөтүлдү).",
'rclistfrom' => '$1 күнүнөн баштап жаңы өзгөртүүлөрдү көрсөтүү',
'rcshowhideminor' => 'Майда оңдоолорду $1',
'rcshowhidebots' => 'ботторду $1',
'rcshowhideliu' => '$1 катталган колдонуучу',
'rcshowhideanons' => 'Жашыруун колдонуучулар $1',
'rcshowhidepatr' => 'Көзөмөл алдындагы оңдоолорду $1',
'rcshowhidemine' => 'Оңдоолорумду $1',
'rclinks' => 'Соңку $2 күндө жасалган акыркы $1 өзгөртүүлөрдү көрсөтүү<br />$3',
'diff' => 'айырма',
'hist' => 'тарых',
'hide' => 'Жашыруу',
'show' => 'Көрсөтүү',
'minoreditletter' => 'м',
'newpageletter' => 'Ж',
'boteditletter' => 'б',
'rc_categories_any' => 'Каалаган',
'rc-enhanced-expand' => 'Кошумча маалыматтарды көрсөтүү (JavaScript талап кылынат)',
'rc-enhanced-hide' => 'Кошумча маалыматтарды жашыруу',

# Recent changes linked
'recentchangeslinked' => 'Тиешелүү өзгөрүүлөр',
'recentchangeslinked-feed' => 'Тиешелүү өзгөрүүлөр',
'recentchangeslinked-toolbox' => 'Тиешелүү өзгөрүүлөр',
'recentchangeslinked-title' => '"$1" үчүн тийиштүү өзгөртүүлөр',
'recentchangeslinked-noresult' => 'Берилген мөөнөттө шилтемеленген барактарда өзгөртүү жок.',
'recentchangeslinked-summary' => 'Бул көрсөтүлгөн (же көрсөтүлгөн категорияга кирген) барактан шилтемеленген барактардагы жакын арада жасалган өзгөрүүлөрдүн тизмеси.
[[Special:Watchlist|Байкоо тизмеңиз]]деги барактар калын арип менен белгиленген.',
'recentchangeslinked-page' => 'Барактын аталышы',
'recentchangeslinked-to' => 'Белгиленген барактан шилтемеленген барактардын ордуна өзгөртүулөрдү көрсөтүү',

# Upload
'upload' => 'Файлды жүктөө',
'uploadbtn' => 'Файлды жүктөө',
'uploaderror' => 'Жүктөө катасы',
'uploadlog' => 'жүктөө журналы',
'uploadlogpage' => 'Жүктөөлөрдүн тизмеси',
'filename' => 'Файл аты',
'filedesc' => 'Кыска түшүндүрмө',
'fileuploadsummary' => 'Кыскача баяндама:',
'filereuploadsummary' => 'Файлдагы өзгөрүүлөр:',
'filesource' => 'Булак:',
'uploadedfiles' => 'Жүктөлгөн файлдар',
'savefile' => 'Файлды сактоо',
'uploadedimage' => '"[[$1]]" жүктөлдү',
'upload-source' => 'Баштапкы файл',
'sourcefilename' => 'Булактын файл аты:',
'sourceurl' => 'Булактын URL-дареги:',
'upload-description' => 'Файл баяндамасы',
'upload-success-subj' => 'Ийгиликтүү жүктөлдү',
'upload-failure-subj' => 'Жүктөө көйгөйү',

'upload-proto-error' => 'Туура эмес протокол',
'upload-file-error' => 'Ички ката',
'upload-unknown-size' => 'Белгисиз өлчөм',

# File backend
'backend-fail-closetemp' => 'Убактылуу файлды жабуу оңунан чыкпай жатат.',

# HTTP errors
'http-invalid-url' => 'Туура эмес URL: $1',

'license' => 'Лицензиялоо:',
'license-header' => 'Лицензиялоо',

# Special:ListFiles
'imgfile' => 'файл',
'listfiles' => 'Файлдар тизмеси',
'listfiles_thumb' => 'Миниатюра',
'listfiles_date' => 'Дата',
'listfiles_name' => 'Ат',
'listfiles_user' => 'Катышуучу',
'listfiles_size' => 'Өлчөм',
'listfiles_description' => 'Баяндама',
'listfiles_count' => 'Версиялар',

# File description page
'file-anchor-link' => 'Файл',
'filehist' => 'Файлдын тарыхы',
'filehist-help' => 'Файлдын ошол учурдагы көрүнүшүн кароо үчүн күнү/сааты бөлүмүнө басыңыз',
'filehist-deleteall' => 'баарын өчүрүү',
'filehist-deleteone' => 'өчүрүү',
'filehist-revert' => 'кайтаруу',
'filehist-current' => 'кезектеги',
'filehist-datetime' => 'Дата/Убакыт',
'filehist-thumb' => 'Миниатюра',
'filehist-thumbtext' => '$1 -дагы версиясы үчүн кичирейтилген сүрөтү',
'filehist-nothumb' => 'Миниатюра жок',
'filehist-user' => 'Катышуучу',
'filehist-dimensions' => 'Өлчөмдөр',
'filehist-filesize' => 'Файл өлчөмү',
'filehist-comment' => 'Эскертүү',
'filehist-missing' => 'Файл жок болот',
'imagelinks' => 'Файлды колдонуу',
'linkstoimage' => 'Бул файлга болгон {{PLURAL:$1|шилтеме|$1 шилтеме}} :',
'nolinkstoimage' => 'Бул файлга шилтеме берген барак жок.',
'sharedupload-desc-here' => 'Бул файл $1 -дан  жана башка долбоорлордо пайдаланылышы мүмкүн.
Төмөндө анын [$2 файлды сыпаттоо барагы]нан сыпаттамасы көрсөтүлгөн.',

# File reversion
'filerevert-comment' => 'Себеп:',

# File deletion
'filedelete' => '$1 — өчүрүү',
'filedelete-legend' => 'Файлды өчүрүү',
'filedelete-comment' => 'Себеп:',
'filedelete-submit' => 'Өчүрүү',

# MIME search
'download' => 'жүктөп алуу',

# Unused templates
'unusedtemplates' => 'Колдонулбаган шаблондор',
'unusedtemplateswlh' => 'башка шилтемелер',

# Random page
'randompage' => 'Тушкелди макала',

# Statistics
'statistics' => 'Статистика',
'statistics-header-users' => 'Катышуучулар боюнча статистика',
'statistics-pages' => 'Барак',

'disambiguationspage' => 'Template:көп маанилүү',

'brokenredirects-edit' => 'оңдоо',
'brokenredirects-delete' => 'өчүрүү',

'withoutinterwiki-submit' => 'Көрсөтүү',

# Miscellaneous special pages
'nbytes' => '$1 {{PLURAL:$1|байт|байт}}',
'nmembers' => '$1{{PLURAL:$1|мүчө|мүчө}}',
'unusedcategories' => 'Колдонулбаган категориялар',
'unusedimages' => 'Колдонулбаган файлдар',
'popularpages' => 'Популярдуу барактар',
'prefixindex' => 'Префикс менен бардык барактар',
'shortpages' => 'Кыска макалалар',
'listusers' => 'Колдонуучулар тизмеси',
'usercreated' => "$1 күнү $2'да {{GENDER:$3|катталды}}.",
'newpages' => 'Жаңы барактар',
'newpages-username' => 'Колдонуучу аты:',
'ancientpages' => 'Эң эски барактар',
'move' => 'Аталышын өзгөртүү',
'movethispage' => 'Бул баракты ташуу',
'pager-newer-n' => '{{PLURAL:$1|жаңыраак 1|жаңыраак $1}}',
'pager-older-n' => '{{PLURAL:$1|эскирээк 1|эскирээк $1}}',

# Book sources
'booksources' => 'Китеп тууралуу маалыматтар',
'booksources-search-legend' => 'Китеп тууралуу маалыматтарды издөө',
'booksources-go' => 'Табуу',

# Special:Log
'specialloguserlabel' => 'Аткаруучу:',
'speciallogtitlelabel' => 'Аталышы:',
'log' => 'Журналдар',

# Special:AllPages
'allpages' => 'Бардык барактар',
'alphaindexline' => '$1 -дан $2 чейин',
'nextpage' => 'Кийинки барак ($1)',
'allpagesfrom' => '-дан башталган барактарды көрсөтүү:',
'allarticles' => 'Бүт барактар',
'allpagesprev' => 'Абалкы',
'allpagesnext' => 'Кийинки',
'allpagessubmit' => 'Аткаруу',
'allpagesprefix' => '- префикси менен барактарды көрсөтүү',

# Special:Categories
'categories' => 'Категориялар',

# Special:LinkSearch
'linksearch-ok' => 'Издөө',
'linksearch-line' => '$1-га $2-дан шилтеме берилди',

# Special:ListUsers
'listusers-submit' => 'Көрсөтүү',
'listusers-noresult' => 'Колдонуучу табылган жок.',
'listusers-blocked' => '(блоктолду)',

# Special:ActiveUsers
'activeusers' => 'Активдүү колдонуучулардын тизмеси',
'activeusers-hidebots' => 'Ботторду жашыруу',
'activeusers-noresult' => 'Колдонуучулар табылган жок.',

# Special:Log/newusers
'newuserlogpage' => 'Жаңы колдонуучулардын тизмеси',

# Special:ListGroupRights
'listgrouprights-group' => 'Топ',
'listgrouprights-rights' => 'Укутар',
'listgrouprights-members' => '(мүчөлөрдүн тизмеси)',

# E-mail user
'emailuser' => 'Бул колдонуучуга кат жиберүү',
'emailusername' => 'Колдонуучу аты:',
'emailusernamesubmit' => 'Жөнөтүү',
'emailfrom' => '- дан',
'emailto' => 'Кимге:',
'emailsubject' => 'Тема:',
'emailmessage' => 'Билдирме',
'emailsend' => 'Жөнөтүү',

# Watchlist
'watchlist' => 'Көзөмөл тизмем',
'mywatchlist' => 'Көзөмөл тизме',
'watchlistfor2' => '$1 үчүн $2',
'watchnologin' => 'Катталган жок',
'watch' => 'Көзөмөлдөө',
'unwatch' => 'Көзөлдөбөө',
'watchlist-details' => 'Талкуу барактарын эсепке албаганда көзөмөл тизмеңизде {{PLURAL:$1|$1 барак|$1 барак}} бар.',
'watchlistcontains' => 'Байкоо тизмеңизде $1 {{PLURAL:$1|барак бар|барак бар}}.',
'wlshowlast' => 'Соңку $1 саат $2 күн $3 көрсөтүү.',
'watchlist-options' => 'Көзөмөл тизменин ырастоолору',

# Delete
'deletepage' => 'Баракты өчүрүү',
'confirm' => 'Аныктоо',
'delete-legend' => 'Өчүрүү',
'actioncomplete' => 'Иш-аракет жыйынтыкталды',
'actionfailed' => 'Аракет натыйжасыз болду',
'dellogpage' => 'Өчүрүүлөрдүн журналы',
'deletecomment' => 'Себеп:',
'deletereasonotherlist' => 'Башка себеп',

# Rollback
'rollbacklink' => 'кайтаруу',

# Protect
'protectlogpage' => 'Коргоо тизмеси',
'protectedarticle' => '"[[$1]]" корголгон',
'protectcomment' => 'Себеп:',
'restriction-type' => 'Укуктар:',

# Restrictions (nouns)
'restriction-edit' => 'Оңдоо',
'restriction-move' => 'Ташуу',
'restriction-create' => 'Жаратуу',
'restriction-upload' => 'Жүктөө',

# Undelete
'undeletebtn' => 'Калыбына келтирүү',
'undeletelink' => 'кароо/калыбына келтирүү',
'undeleteviewlink' => 'кароо',
'undeletereset' => 'Түшүрүү',
'undeletecomment' => 'Себеп:',
'undelete-search-submit' => 'Издөө',

# Namespace form on various pages
'namespace' => 'Аталыштар мейкиндиги:',
'invert' => 'Белгиленгенди текскерилетүү',
'blanknamespace' => '(Негизги)',

# Contributions
'contributions' => 'Колдонуучунун салымдары',
'contributions-title' => '$1 үчүн колдонуучунун салымдары',
'mycontris' => 'Салымдар',
'contribsub2' => '$1 үчүн ($2)',
'uctop' => '(соңку)',
'month' => 'Айынан (же андан мурун):',
'year' => 'Жылынан (жана андан мурун):',

'sp-contributions-newbies' => 'Жаңы колдонуучулардын гана салымдарын көрсөт',
'sp-contributions-blocklog' => 'тосмолоолордун журналы',
'sp-contributions-uploads' => 'жүктөөлөр',
'sp-contributions-logs' => 'журналдар',
'sp-contributions-talk' => 'талкуулоо',
'sp-contributions-search' => 'Салымдарымды издөө',
'sp-contributions-username' => 'IP-дарек же катышуучунун аты:',
'sp-contributions-toponly' => 'Соңку версиялары болгон оңдоолорду гана көрсөтүү',
'sp-contributions-submit' => 'Издөө',

# What links here
'whatlinkshere' => 'Шилтемелерди бул жакка',
'whatlinkshere-title' => '"$1" -га шилтеме берген барактар',
'whatlinkshere-page' => 'Барак:',
'linkshere' => "'''[[:$1]]''' барагына шилтеме берген барактар:",
'nolinkshere' => "'''[[:$1]]''' барагына шилтеме берген барак жок.",
'isredirect' => 'кайра багыттоо барагы',
'istemplate' => 'кошуу',
'isimage' => 'файл шилтемеси',
'whatlinkshere-prev' => '{{PLURAL:$1|мурунку|мурунку $1}}',
'whatlinkshere-next' => '{{PLURAL:$1|кийинки|кийинки $1}}',
'whatlinkshere-links' => '← шилтемелер',
'whatlinkshere-hideredirs' => 'Багыттоолорду $1',
'whatlinkshere-hidetrans' => '$1 кошуулары',
'whatlinkshere-hidelinks' => 'Шилтемелерди $1',
'whatlinkshere-hideimages' => 'Сүрөт шилтемелерин $1',
'whatlinkshere-filters' => 'Фильтрлер',

# Block/unblock
'block' => 'Катышуучуну блокко салуу',
'blockip' => 'Катышуучуну блокко салуу',
'blockip-title' => 'Катышуучуну блокко салуу',
'blockip-legend' => 'Катышуучуну блокко салуу',
'ipadressorusername' => 'IP дарек же колдонуучу аты:',
'ipbreason' => 'Себеп:',
'ipbreasonotherlist' => 'Башка себеп',
'ipbother' => 'Башка убакыт:',
'ipboptions' => '2 саат:2 hours,1 күн:1 day,3 күн:3 days,1 жума:1 week,2 жума:2 weeks,1 ай:1 month,3 ай:3 months,6 ай:6 months,1 жыл:1 year,мөөнөтсүз:infinite',
'ipbotheroption' => 'башка',
'ipblocklist' => 'Тосмолонгон катышуучулар',
'blocklist-reason' => 'Себеп',
'ipblocklist-submit' => 'Издөө',
'blocklink' => 'блокко салуу',
'unblocklink' => 'блоктон алуу',
'change-blocklink' => 'блоктоону өзгөртүү',
'contribslink' => 'салым',
'emaillink' => 'кат жиберүү',
'blocklogpage' => 'Тосмолоолордун журналы',
'blocklogentry' => '[[$1]] тосмолонду, тосмолоо мөөнөтү: $2 $3',
'block-log-flags-nocreate' => 'эсеп жазуусун жаратуу өчүрүлгөн',

# Move page
'movelogpage' => 'Өзгөртүлгөн аттардын журналы',
'movereason' => 'Себеп:',
'revertmove' => 'кайтаруу',
'delete_and_move_confirm' => 'Ооба, бул баракты өчүрөм',

# Export
'export' => 'Барактарды экспорттоо',
'export-addcat' => 'Кошуу',
'export-addns' => 'Кошуу',

# Namespace 8 related
'allmessages' => 'Системалык билдирмелер',
'allmessagesname' => 'Ат',
'allmessagesdefault' => 'Белгиленген билдирүүнүн тексти',
'allmessagescurrent' => 'Учурдагы текст',
'allmessages-filter-legend' => 'Фильтр',
'allmessages-filter-all' => 'Баары',
'allmessages-language' => 'Тил:',
'allmessages-filter-submit' => 'Өтүү',

# Thumbnails
'thumbnail-more' => 'Чоңойтуу',
'thumbnail_error' => 'Кичирейтилген сүрөттү түзүүдө ката: $1',

# Special:Import
'import-interwiki-submit' => 'Импорттоо',
'import-upload-filename' => 'Файл аты:',
'import-comment' => 'Эскертүү:',

# Import log
'importlogpage' => 'Импорт журналы',

# Tooltip help for the actions
'tooltip-pt-userpage' => 'Катышуучу барагыңыз',
'tooltip-pt-mytalk' => 'Талкуулоо барагыңыз',
'tooltip-pt-preferences' => 'Ырастоолоруңуз',
'tooltip-pt-watchlist' => 'Өзгөрүүлөрүн көзөмөлгө алган барактардын тизмеси',
'tooltip-pt-mycontris' => 'Салымдарыңыздын тизмеси',
'tooltip-pt-login' => 'Сизге системада катталууга сунуш кылынат, бирок милдеттүү эмес',
'tooltip-pt-logout' => 'Чыгуу',
'tooltip-ca-talk' => 'Барактын мазмуну боюнча талкуулоо',
'tooltip-ca-edit' => 'Сиз бул баракты оңдой аласыз. Кичи пейилдикке, сактоодон мурда алдын ала көрсөтүү нукуурун колдонуңуз.',
'tooltip-ca-addsection' => 'Жаңы бөлүмдү баштөө',
'tooltip-ca-viewsource' => 'Бул барак корголгон.
Сиз анын кайнарын көрө аласыз',
'tooltip-ca-history' => 'Бул барактын мурунку оңдоолору',
'tooltip-ca-protect' => 'Бул баракты коргоо',
'tooltip-ca-delete' => 'Бул баракты өчүрүү',
'tooltip-ca-move' => 'Баракты көчүрүү',
'tooltip-ca-watch' => 'Бул баракты көзөмөл тизмеңизге кошуңуз',
'tooltip-ca-unwatch' => 'Бул баракты көзөмөл тизмеңизден алып салыңыз',
'tooltip-search' => '{{SITENAME}} издөө',
'tooltip-search-go' => 'Так ушундай аталыштагы баракты көрсөтүү',
'tooltip-search-fulltext' => 'Ушул текст менен барактарды издөө',
'tooltip-p-logo' => 'Башбаракка өтүү',
'tooltip-n-mainpage' => 'Башбаракка өтүү',
'tooltip-n-mainpage-description' => 'Башбаракка өтүү',
'tooltip-n-portal' => 'Долбоор тууралуу, эмне жасай аласыз, кайсы жерде эмне бар жөнүндө',
'tooltip-n-currentevents' => 'Учурдагы окуялар тууралуу кошумча маалымат табуу',
'tooltip-n-recentchanges' => 'Уикидеги соңку өзгөртүүлөрдүн тизмеси',
'tooltip-n-randompage' => 'Иретсиз түрдө бир баракты ачуу',
'tooltip-n-help' => 'Маалымат алуу үчүн',
'tooltip-t-whatlinkshere' => 'Ушул жерге шилтемеси бар бардык уики барактардын тизмеси',
'tooltip-t-recentchangeslinked' => 'Бул барактан шилтеме берилген барактардагы соңку өзгөрүүлөр',
'tooltip-feed-atom' => 'Бул барак үчүн Atom агымы',
'tooltip-t-contributions' => 'Бул колдонуучунун салымдарынын тизмеси',
'tooltip-t-emailuser' => 'Бул колдонуучуга кат жиберүү',
'tooltip-t-upload' => 'Файлдарды жүктөө',
'tooltip-t-specialpages' => 'Бардык атайын барактардын тизмеси',
'tooltip-t-print' => 'Бул барактын басып чыгарууга ылайыктуу түрү',
'tooltip-t-permalink' => 'Барактын бул версиясына туруктуу шилтеме',
'tooltip-ca-nstab-main' => 'Барактын мазмунун кароо',
'tooltip-ca-nstab-user' => 'Катышуучунун барагын көрсөтүү',
'tooltip-ca-nstab-special' => 'Бул атайын барак, аны оңдой албайсыз',
'tooltip-ca-nstab-project' => 'Долбоор барагын көрүү',
'tooltip-ca-nstab-image' => 'Файл барагын көрүү',
'tooltip-ca-nstab-template' => 'Шаблонду көрүү',
'tooltip-ca-nstab-category' => 'Категория барагын көрүү',
'tooltip-minoredit' => 'Муну майда оңдоо деп белгилөө',
'tooltip-save' => 'Өзгөртүүлөрдү сактоо',
'tooltip-preview' => 'Кичи пейлдикке, өзгөртүүлөрдү алдын ала көрсөтүүнү сактоодон мурун колдонуңуз!',
'tooltip-diff' => 'Тексттке киргизген өзгөртүүлөрдү көрсөтүү',
'tooltip-compareselectedversions' => 'Бул барактын тандалган эки версиясынын айырмаларын кароо',
'tooltip-watch' => 'Бул баракты көзөмөл тизмеңизге кошуңуз',
'tooltip-rollback' => '"Кайтар" бир баскыч менен бул барактын соңку оңдоочусунун өзгөртүүлөрүн алып салат',
'tooltip-undo' => 'Киргизилген оңдоону алып салат жана жокко чыгаруунун себебин белгилөөгө мүмкүнчүлүк берип алдын ала көрсөтүүнү ачат',
'tooltip-preferences-save' => 'Ырастоолорду сактоо',
'tooltip-summary' => 'Кыска баяндаманы киргизиңиз',

# Attribution
'others' => 'башкалар',
'creditspage' => 'Алкыштар',

# Info page
'pageinfo-header-edits' => 'Оңдоо тарыхы',
'pageinfo-toolboxlink' => 'Барак жөнүндө маалымат',
'pageinfo-contentpage-yes' => 'Ооба',
'pageinfo-protect-cascading-yes' => 'Ооба',

# Browsing diffs
'previousdiff' => '← Эскисин оңдоо',
'nextdiff' => 'Жаңысын оңдоо →',

# Media information
'file-info-size' => '$1 × $2 пиксель, файлдын көлөмү: $3, MIME түрү: $4',
'file-nohires' => 'Мындан чоңураак чечим жок.',
'svg-long-desc' => 'SVG файл, шарттуу түрдө $1 × $2 пиксел, файлдын көлөмү: $3',
'show-big-image' => 'Толук чечими',

# Special:NewFiles
'newimages' => 'Жаңы файлдардын галереясы',
'newimages-legend' => 'Фильтр',
'ilsubmit' => 'Издөө',
'bydate' => 'дата боюнча',

# Bad image list
'bad_image_list' => 'Төмөнкү калыпта болуш керек:

Тизмедегилер гана окулат (* белги менен башталган саптар).
Саптын биринчи шилтемеси койгонго тыюу салынган файлга шилтеме болуш керек.
Ошол саптагы кийинки шилтемелер айрыкча каралып, же файл киргизиле бере турган макалалар.',

# Metadata
'metadata' => 'Метамаалыматтар',
'metadata-help' => 'Бул файл адатта санарип камера же сканнер кошуучу маалыматтарды камтыйт. 
Эгерде файл баштапкы абалынан өзгөртүлсө, анда анын кээ бир сыпаттары толук чагылдырылбашы мүмкүн.',
'metadata-fields' => 'Төмөндө тизмеленген сүрөт метамаалыматтарынын саптары метамаалыматтардын жадыбалы түрүлүү учурда сүрөт барагына кошумчаланат.
Калгандары баштапкы абалда (өзгөртүлбөсө) көргөзүлбөйт.
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
'exif-artist' => 'Автор',
'exif-filesource' => 'Файл булагы',
'exif-contrast' => 'Карама-каршылык',
'exif-keywords' => 'Ачкыч сөздөр',
'exif-source' => 'Булак',
'exif-languagecode' => 'Тил',

'exif-subjectdistance-value' => '$1 метр',

'exif-meteringmode-0' => 'Белгисиз',
'exif-meteringmode-255' => 'Башка',

'exif-lightsource-0' => 'Белгисиз',
'exif-lightsource-4' => 'Жарк',
'exif-lightsource-11' => 'Көлөкө',

# Flash modes
'exif-flash-mode-3' => 'автоматтык режим',

'exif-focalplaneresolutionunit-2' => 'дюйм',

'exif-scenecapturetype-0' => 'Стандарттуу',
'exif-scenecapturetype-1' => 'Ландшафт',
'exif-scenecapturetype-2' => 'Портрет',
'exif-scenecapturetype-3' => 'Түнкү тартуу',

'exif-gaincontrol-0' => 'Жок',

'exif-subjectdistancerange-0' => 'Белгисиз',
'exif-subjectdistancerange-1' => 'Макротартуу',

# Pseudotags used for GPSDestDistanceRef
'exif-gpsdestdistance-k' => 'Километр',
'exif-gpsdestdistance-m' => 'Миля',

'exif-gpsdop-excellent' => 'Мыкты ($1)',
'exif-gpsdop-good' => 'Жакшы ($1)',
'exif-gpsdop-moderate' => 'Орточо ($1)',
'exif-gpsdop-poor' => 'Начар ($1)',

'exif-dc-contributor' => 'Авторлоштор',
'exif-dc-date' => 'Дата(лар)',
'exif-dc-publisher' => 'Бастыруучу',
'exif-dc-rights' => 'Укуктар',
'exif-dc-source' => 'Баштапкы медиа',
'exif-dc-type' => 'Медианын түрү',

'exif-iimcategory-fin' => 'Экономика жана бизнес',
'exif-iimcategory-edu' => 'Билим',
'exif-iimcategory-evn' => 'Айлана чөйрө',
'exif-iimcategory-hth' => 'Ден соолук',
'exif-iimcategory-lab' => 'Эмгек',
'exif-iimcategory-pol' => 'Саясат',
'exif-iimcategory-rel' => 'Дин жана ишеним',
'exif-iimcategory-sci' => 'Илим жана техника',
'exif-iimcategory-soi' => 'Социалдык маселелер',
'exif-iimcategory-spo' => 'Спорт',
'exif-iimcategory-wea' => 'Аба ырайы',

# External editor support
'edit-externally' => 'Бул файлды сырткы программа колдонуу аркылуу оңдоо',
'edit-externally-help' => '(Толук маалымат алуу үчүн [//www.mediawiki.org/wiki/Manual:External_editors setup instructions] барагына кайрылсаңыз болот)',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'баары',
'namespacesall' => 'баары',
'monthsall' => 'баары',
'limitall' => 'баары',

# E-mail address confirmation
'confirmemail' => 'Электрондук даректи аныктоо',
'confirmemail_loggedin' => 'Электрондук дарегиңиз аныкталды.',

# Delete conflict
'recreate' => 'Кайтадан жаратуу',

# action=purge
'confirm_purge_button' => 'OK',

# action=watch/unwatch
'confirm-watch-button' => 'ОК',
'confirm-unwatch-button' => 'ОК',

# Multipage image navigation
'imgmultipageprev' => '← мурунку барак',
'imgmultipagenext' => 'кийинки барак →',
'imgmultigo' => 'Өтүү!',

# Table pager
'table_pager_next' => 'Кийинки барак',
'table_pager_prev' => 'Мурунку барак',
'table_pager_first' => 'Биринчи барак',
'table_pager_last' => 'Акыркы барак',
'table_pager_limit_submit' => 'Аткаруу',

# Live preview
'livepreview-loading' => 'Жүктөө...',

# Watchlist editing tools
'watchlisttools-view' => 'Тийиштүү өзгөрүүлөрдү көрсөтүү',
'watchlisttools-edit' => 'Көзөмөл тизмесин кароо жана оңдоо',
'watchlisttools-raw' => 'Жетиле элек көзөмөл тизмени оңдоо',

# Core parser functions
'duplicate-defaultsort' => '\'\'\'Эскертүү:\'\'\' "$2" белгиленген ылгоочу ачкыч "$1" мурунку белгиленген ылгоочу ачкычты жокко чыгарат.',

# Special:Version
'version' => 'Версия',
'version-variables' => 'Өзгөрмөлөр',
'version-other' => 'Башка',
'version-version' => '(Версия $1)',
'version-license' => 'Лицензия',
'version-software' => 'Орнотулган программалык камсыздоо',
'version-software-product' => 'Продукт',
'version-software-version' => 'Версия',
'version-entrypoints-header-url' => 'URL',

# Special:FilePath
'filepath' => 'Файл жолу',
'filepath-page' => 'Файл:',
'filepath-submit' => 'Өтүү',

# Special:SpecialPages
'specialpages' => 'Атайын барактар',
'specialpages-group-pagetools' => 'Барак аспаптары',

# Special:BlankPage
'blankpage' => 'Бош барак',

# External image whitelist
'external_image_whitelist' => ' #Бул сапты болгондой калтыруу<pre>
#Туруктуу айтылыштардын бөлүмдөрүн (// арасындагы бөлүмүн гана) астына жайгаштыру 
#Алар сырткы сүрөттөрдүн URL менен байланыштырылат
#Ылайыктуулары сүрөт болуп көрсөтүлөт, калгандары сүрөттөргө шилтеме болуп көрсөтүлөт
## менен башталган саптар, түшүндүрмө болуп эсептелет
#Баш же кичине тамга айырмасыз

#Туруктуу айтылыштардын бөлүмдөрүн ушул саптын үстүнө жайгаштыр. Бул сапты болгондой калтыруу.</pre>',

# Special:Tags
'tag-filter' => '[[Special:Tags|Белги]] фильтри:',
'tag-filter-submit' => 'Фильтрдөө',
'tags-title' => 'Белгилер',
'tags-edit' => 'оңдоо',

# Special:ComparePages
'compare-submit' => 'Салыштыруу',

# HTML forms
'htmlform-submit' => 'Жөнөтүү',
'htmlform-selectorother-other' => 'Башка',

# Feedback
'feedback-subject' => 'Тема:',
'feedback-message' => 'Билдирме:',
'feedback-cancel' => 'Жокко чыгаруу',
'feedback-close' => 'Даяр',

# Search suggestions
'searchsuggest-search' => 'Издөө',

# API errors
'api-error-unknown-code' => 'Белгисиз ката: "$1".',
'api-error-unknownerror' => 'Белгисиз ката: «$1».',

);
