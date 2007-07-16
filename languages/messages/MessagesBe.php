<?php
/** Belarusian normative (Беларуская мова)
  *
  * @addtogroup Language
  */

$skinNames = array(
	'standard'    => 'Клясычны',
	'nostalgia'   => 'Настальгія',
	'cologneblue' => 'Кёльнскі смутак',
	'monobook'    => 'Монакніга',
	'myskin'      => 'MySkin',
	'chick'       => 'Цыпа'
);

$bookstoreList = array(
	'OZ.by' => 'http://oz.by/search.phtml?what=books&isbn=$1',
	'Amazon.com' => 'http://www.amazon.com/exec/obidos/ISBN=$1'
);

$datePreferences = array(
	'default',
	'dmy',
	'ISO 8601',
);

$defaultDateFormat = 'dmy';

$dateFormats = array(
	'dmy time' => 'H:i',
	'dmy date' => 'j xg Y',
	'dmy both' => 'H:i, j xg Y',
);

$magicWords = array(
	'redirect'               => array( 0,    '#redirect' ),
	'notoc'                  => array( 0,    '__NOTOC__' ),
	'nogallery'              => array( 0,    '__NOGALLERY__' ),
	'forcetoc'               => array( 0,    '__FORCETOC__' ),
	'toc'                    => array( 0,    '__TOC__' ),
	'noeditsection'          => array( 0,    '__NOEDITSECTION__' ),
	'start'                  => array( 0,    '__START__' ),
	'currentmonth'           => array( 1,    'CURRENTMONTH' ),
	'currentmonthname'       => array( 1,    'CURRENTMONTHNAME' ),
	'currentmonthnamegen'    => array( 1,    'CURRENTMONTHNAMEGEN' ),
	'currentmonthabbrev'     => array( 1,    'CURRENTMONTHABBREV' ),
	'currentday'             => array( 1,    'CURRENTDAY' ),
	'currentday2'            => array( 1,    'CURRENTDAY2' ),
	'currentdayname'         => array( 1,    'CURRENTDAYNAME' ),
	'currentyear'            => array( 1,    'CURRENTYEAR' ),
	'currenttime'            => array( 1,    'CURRENTTIME' ),
	'numberofpages'          => array( 1,    'NUMBEROFPAGES' ),
	'numberofarticles'       => array( 1,    'NUMBEROFARTICLES' ),
	'numberoffiles'          => array( 1,    'NUMBEROFFILES' ),
	'numberofusers'          => array( 1,    'NUMBEROFUSERS' ),
	'pagename'               => array( 1,    'PAGENAME' ),
	'pagenamee'              => array( 1,    'PAGENAMEE' ),
	'namespace'              => array( 1,    'NAMESPACE' ),
	'namespacee'             => array( 1,    'NAMESPACEE' ),
	'talkspace'              => array( 1,    'TALKSPACE' ),
	'talkspacee'             => array( 1,    'TALKSPACEE' ),
	'subjectspace'           => array( 1,    'SUBJECTSPACE', 'ARTICLESPACE' ),
	'subjectspacee'          => array( 1,    'SUBJECTSPACEE', 'ARTICLESPACEE' ),
	'fullpagename'           => array( 1,    'FULLPAGENAME' ),
	'fullpagenamee'          => array( 1,    'FULLPAGENAMEE' ),
	'subpagename'  	         => array( 1,    'SUBPAGENAME' ),
	'subpagenamee'           => array( 1,    'SUBPAGENAMEE' ),
	'basepagename'           => array( 1,    'BASEPAGENAME' ),
	'basepagenamee'          => array( 1,    'BASEPAGENAMEE' ),
	'talkpagename'           => array( 1,    'TALKPAGENAME' ),
	'talkpagenamee'          => array( 1,    'TALKPAGENAMEE' ),
	'subjectpagename'        => array( 1,    'SUBJECTPAGENAME', 'ARTICLEPAGENAME' ),
	'subjectpagenamee'       => array( 1,    'SUBJECTPAGENAMEE', 'ARTICLEPAGENAMEE' ),
	'msg'                    => array( 0,    'MSG:' ),
	'subst'                  => array( 0,    'SUBST:' ),
	'msgnw'                  => array( 0,    'MSGNW:' ),
	'img_thumbnail'          => array( 1,    'thumbnail', 'thumb' ),
	'img_manualthumb'        => array( 1,    'thumbnail=$1', 'thumb=$1' ),
	'img_right'              => array( 1,    'right' ),
	'img_left'               => array( 1,    'left' ),
	'img_none'               => array( 1,    'none' ),
	'img_width'              => array( 1,    '$1px' ),
	'img_center'             => array( 1,    'center', 'centre' ),
	'img_framed'             => array( 1,    'framed', 'enframed', 'frame' ),
	'int'                    => array( 0,    'INT:' ),
	'sitename'               => array( 1,    'SITENAME' ),
	'ns'                     => array( 0,    'NS:' ),
	'localurl'               => array( 0,    'LOCALURL:' ),
	'localurle'              => array( 0,    'LOCALURLE:' ),
	'server'                 => array( 0,    'SERVER' ),
	'servername'             => array( 0,    'SERVERNAME' ),
	'scriptpath'             => array( 0,    'SCRIPTPATH' ),
	'grammar'                => array( 0,    'GRAMMAR:' ),
	'notitleconvert'         => array( 0,    '__NOTITLECONVERT__', '__NOTC__' ),
	'nocontentconvert'       => array( 0,    '__NOCONTENTCONVERT__', '__NOCC__' ),
	'currentweek'            => array( 1,    'CURRENTWEEK' ),
	'currentdow'             => array( 1,    'CURRENTDOW' ),
	'revisionid'             => array( 1,    'REVISIONID' ),
	'plural'                 => array( 0,    'PLURAL:' ),
	'fullurl'                => array( 0,    'FULLURL:' ),
	'fullurle'               => array( 0,    'FULLURLE:' ),
	'lcfirst'                => array( 0,    'LCFIRST:' ),
	'ucfirst'                => array( 0,    'UCFIRST:' ),
	'lc'                     => array( 0,    'LC:' ),
	'uc'                     => array( 0,    'UC:' ),
	'raw'                    => array( 0,    'RAW:' ),
	'displaytitle'           => array( 1,    'DISPLAYTITLE' ),
	'rawsuffix'              => array( 1,    'R' ),
	'newsectionlink'         => array( 1,    '__NEWSECTIONLINK__' ),
	'currentversion'         => array( 1,    'CURRENTVERSION' ),
	'urlencode'              => array( 0,    'URLENCODE:' ),
	'currenttimestamp'       => array( 1,    'CURRENTTIMESTAMP' ),
	'directionmark'          => array( 1,    'DIRECTIONMARK', 'DIRMARK' ),
	'language'               => array( 0,    '#LANGUAGE:' ),
	'contentlanguage'        => array( 1,    'CONTENTLANGUAGE', 'CONTENTLANG' ),
	'pagesinnamespace'       => array( 1,    'PAGESINNAMESPACE:', 'PAGESINNS:' ),
	'numberofadmins'         => array( 1,    'NUMBEROFADMINS' ),
	'formatnum'              => array( 0,    'FORMATNUM' ),
);

$namespaceNames = array(
        NS_MEDIA            => 'Мультымедыя',
        NS_SPECIAL          => 'Адмысловае',
        NS_MAIN             => '',
        NS_TALK             => 'Размовы',
        NS_USER             => 'Удзельнік',
        NS_USER_TALK        => 'Размовы_з_удзельнікам',
	# NS_PROJECT set by $wgMetaNamespace
        NS_PROJECT_TALK     => '$1_размовы',
        NS_IMAGE            => 'Выява',
        NS_IMAGE_TALK       => 'Размовы_пра_выяву',
        NS_MEDIAWIKI        => 'MediaWiki',
        NS_MEDIAWIKI_TALK   => 'Размовы_пра_MediaWiki',
        NS_TEMPLATE         => 'Шаблон',
        NS_TEMPLATE_TALK    => 'Размовы_пра_шаблон',
        NS_HELP             => 'Даведка',
        NS_HELP_TALK        => 'Размовы_пра_даведку',
        NS_CATEGORY         => 'Катэгорыя',
        NS_CATEGORY_TALK    => 'Размовы_пра_катэгорыю'
);

$separatorTransformTable = array(',' => '.', '.' => ',' );

$linkTrail = '/^([абвгґджзеёжзійклмнопрстуўфхцчшыьэюяćčłńśšŭźža-z]+)(.*)$/sDu';

$messages = array(
# User preference toggles
'tog-underline'           => 'Падкрэсліваць спасылкі:',
'tog-numberheadings'      => 'Аўта-нумараванне загалоўкаў',
'tog-showtoolbar'         => 'Паказваць стужку рэдактара (ЯваСкрыпт)',
'tog-editondblclick'      => 'Правіць старонкі пасля падвойнага пстрыкання (JavaScript)',
'tog-showtoc'             => 'Паказваць змест (для старонак, дзе больш за 3 загалоўкі)',
'tog-rememberpassword'    => 'Памятаць ад сеансу да сеансу',
'tog-previewontop'        => 'Паказваць падгляд перад полем рэдактара',
'tog-previewonfirst'      => 'Пказваць падгляд пры першай праўцы',
'tog-shownumberswatching' => 'Паказваць колькасць назіральнікаў',

'underline-always' => 'Заўсёды',
'underline-never'  => 'Ніколі',

# Dates
'sunday'        => 'Нядзеля',
'monday'        => 'Панядзелак',
'tuesday'       => 'Аўторак',
'wednesday'     => 'Серада',
'thursday'      => 'Чацвер',
'friday'        => 'Пятніца',
'saturday'      => 'Субота',
'sun'           => 'Нд',
'mon'           => 'Пн',
'tue'           => 'Аў',
'wed'           => 'Ср',
'thu'           => 'Чц',
'fri'           => 'Пт',
'sat'           => 'Сб',
'january'       => 'Студзень',
'february'      => 'Люты',
'march'         => 'Сакавік',
'april'         => 'Красавік',
'may_long'      => 'Травень',
'june'          => 'Чэрвень',
'july'          => 'Ліпень',
'august'        => 'Жнівень',
'september'     => 'Верасень',
'october'       => 'Кастрычнік',
'november'      => 'Лістапад',
'december'      => 'Снежань',
'january-gen'   => 'Студзень',
'february-gen'  => 'Люты',
'march-gen'     => 'Сакавік',
'april-gen'     => 'Красавік',
'may-gen'       => 'Травень',
'june-gen'      => 'Чэрвень',
'july-gen'      => 'Ліпень',
'august-gen'    => 'Жнівень',
'september-gen' => 'Верасень',
'october-gen'   => 'Кастрычнік',
'november-gen'  => 'Лістапад',
'december-gen'  => 'Снежань',
'jan'           => 'Сту',
'feb'           => 'Лют',
'mar'           => 'Сак',
'apr'           => 'Кра',
'may'           => 'Травень',
'jun'           => 'Чэр',
'jul'           => 'Ліп',
'aug'           => 'Жні',
'sep'           => 'Вер',
'oct'           => 'Кас',
'nov'           => 'Ліс',
'dec'           => 'Сне',

# Bits of text used by many pages
'categories'            => 'Катэгорыі',
'category_header'       => 'Складнікі ў катэгорыі “$1”',

'about'          => 'Што гэта',
'article'        => 'Старонка змесціва',
'newwindow'      => '(адкрыецца ў новым акне)',
'cancel'         => 'Нічога',
'qbfind'         => 'Знайсці',
'qbbrowse'       => 'Выбраць',
'qbedit'         => 'Правіць',
'qbpageoptions'  => 'Гэтая старонка',
'qbpageinfo'     => 'Кантэкст',
'qbmyoptions'    => 'Свае старонкі',
'qbspecialpages' => 'Спецыяльныя старонкі',
'moredotdotdot'  => 'Яшчэ...',
'mypage'         => 'Свая старонка',
'mytalk'         => 'Свае размовы',
'anontalk'       => 'Размова для гэтага IP',
'navigation'     => 'Рух',

'errorpagetitle'    => 'Памылка',
'help'              => 'Даведка',
'search'            => 'Знайсці',
'searchbutton'      => 'Тэкст',
'go'                => 'Ісці',
'searcharticle'     => 'Назва',
'history'           => 'Гісторыя старонкі',
'history_short'     => 'Гісторыя',
'info_short'        => 'Інфармацыя',
'permalink'         => 'Нязменная спасылка',
'print'             => 'Друкаваць',
'edit'              => 'Правіць',
'editthispage'      => 'Правіць гэту старонку',
'delete'            => 'сцерці',
'deletethispage'    => 'Сцерці гэту старонку',
'undelete_short'    => 'Аднавіць {{PLURAL:$1|адну праўку|$1 правак}}',
'protect'           => 'Засцерагаць',
'protectthispage'   => 'Засцерагчы старонку',
'unprotectthispage' => 'Зняць засцераганне з гэтай старонкі',
'newpage'           => 'Новая старонка',
'personaltools'     => 'Асабістыя прылады',
'postcomment'       => 'Пакінуць заўвагу',
'articlepage'       => 'Паказаць старонку змесціва',
'talk'              => 'Размова',
'views'             => 'Віды',
'toolbox'           => 'Скрынка прылад',
'userpage'          => 'Паказаць старонку карыстальніка',
'imagepage'         => 'Гл. старонку рысунку',
'viewhelppage'      => 'Паказаць старонку даведкі',
'categorypage'      => 'Гл. старонку катэгорыі',
'viewtalkpage'      => 'Паказаць размову',
'otherlanguages'    => 'На іншых мовах',
'redirectedfrom'    => '(Пасля перасылкі з <tt>$1</tt>)',
'redirectpagesub'   => 'Старонка перасылкі',
'lastmodifiedat'    => 'Апошняе змяненне старонкі адбылося $2, $1.', # $1 date, $2 time
'viewcount'         => 'Гэту старонку адкрывалі {{plural:$1|адзін раз|$1 разоў}}.',
'protectedpage'     => 'Засцераганая старонка',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'         => 'Аб {{SITENAME}}',
'aboutpage'         => '{{ns:project}}:Аб праекце',
'currentevents'     => 'Актуальныя падзеі',
'currentevents-url' => 'Актуальныя падзеі',
'faq'               => 'ЧАПЫ',
'faqpage'           => '{{ns:project}}:ЧАПЫ',
'helppage'          => '{{ns:project}}:Змест',
'mainpage'          => 'Першая старонка',
'portal'            => 'Партал супольнасці',
'portal-url'        => '{{ns:project}}:Партал супольнасці',

'badaccess'        => 'Памылка ў дазволах',
'badaccess-group0' => 'Вам не дазволена выконваць аперацыю, па якую вы звярталіся.',
'badaccess-group1' => 'Аперацыя, па якую вы звярталіся, абмежавана карыстальнікамі з групы $1.',
'badaccess-group2' => 'Аперацыя, па якую вы звярталіся, абмежавана карыстальнікамі з адной з груп $1.',
'badaccess-groups' => 'Аперацыя, па якую вы звярталіся, абмежавана карыстальнікамі з адной з груп $1.',

'versionrequired'     => 'Патрабуецца MediaWiki версіі $1',
'versionrequiredtext' => 'Каб карыстацца гэтай старонкай, патрабуецца MediaWiki версіі $1. Гл. [[Special:Version]]',

'youhavenewmessages'  => 'Вы маеце $1 ($2).',
'newmessageslink'     => 'новыя паведамленні',
'newmessagesdifflink' => 'розн. з найноўшай версіяй',
'editsection'         => 'правіць',
'editold'             => 'правіць',
'editsectionhint'     => 'Правіць раздзел: $1',
'toc'                 => 'Змесціва',
'viewdeleted'         => 'Ці паказаць $1?',
'restorelink'         => '{{PLURAL:$1|адна сцёртая праўка|$1 сцёртых правак}}',
'feedlinks'           => 'Струмень:',
'feed-invalid'        => 'Недапушчальны тып струмяня навін.',

# Short words for each namespace, by default used in the 'article' tab in monobook
'nstab-main'      => 'Артыкул',
'nstab-user'      => 'Карыстальнік',
'nstab-media'     => 'Мультымедыя',
'nstab-special'   => 'Спецыяльнае',
'nstab-project'   => 'Старонка праекту',
'nstab-image'     => 'Файл',
'nstab-mediawiki' => 'Паведамленне',
'nstab-template'  => 'Шаблон',
'nstab-help'      => 'Даведка',
'nstab-category'  => 'Катэгорыя',

# General errors
'error'                => 'Памылка',
'databaseerror'        => 'Памылка базы дадзеных',
'filecopyerror'        => 'Не ўдалося капіраваць файл "$1" у "$2".',
'filerenameerror'      => 'Не ўдалося назваць файл "$1" назвай "$2".',
'filedeleteerror'      => 'Не ўдалося сцерці файл "$1".',
'filenotfound'         => 'Не ўдалося знайсці файл "$1".',
'unexpected'           => 'Нечаканае значэнне: "$1"="$2".',
'formerror'            => 'Памылка: не ўдалося падаць форму',
'badarticleerror'      => 'Аперацыя не дазволена на гэтай старонцы.',
'badtitle'             => 'Няправільная назва',
'badtitletext'         => 'Назва старонкі, па якую звярталіся, аказалася недапушчальнай, пустой, або няправільна прылучанай між-моўнай ці між-вікі назвай. Магчыма, у ёй ёсць знакі, якія нельга ўжываць у назвах.',
'wrong_wfQuery_params' => 'Недапушчальныя параметры wfQuery()<br />
Функцыя: $1<br />
Зварот: $2',
'viewsource'           => 'Паказаць выточны тэкст',
'viewsourcefor'        => 'для $1',

# Login and logout pages
'welcomecreation'            => '== Вітаем, $1! == Ваш  рахунак быў створаны. Не забудзьцеся дапасаваць свае настаўленні ў {{SITENAME}}.',
'yourname'                   => 'Імя карыстальніка',
'yourpassword'               => 'Пароль',
'yourpasswordagain'          => 'Паўтарыце пароль',
'remembermypassword'         => 'Памятаць мяне',
'yourdomainname'             => 'Ваш дамен',
'externaldberror'            => 'Або памылка вонкавай аўтэнтыкацыі ў базе дадзеных, або вам не дазволена абнаўляць свой вонкавы рахунак.',
'alreadyloggedin'            => '<strong>Карыстальнік $1, вы ўжо ўвайшлі ў сістэму!</strong><br />',
'login'                      => 'Увайсці ў сістэму',
'userlogin'                  => 'Увайсці ў сістэму / стварыць рахунак',
'logout'                     => 'Выйсці з сістэмы',
'userlogout'                 => 'Выйсці з сістэмы',
'createaccount'              => 'Стварыць рахунак',
'gotaccount'                 => 'Ужо маеце рахунак? $1.',
'gotaccountlink'             => 'Увайсці ў сістэму',
'createaccountmail'          => 'праз эл.пошту',
'badretype'                  => 'Уведзеныя паролі не аднолькавыя.',
'userexists'                 => 'Такое імя карыстальніка ўжо занятае. Калі ласка, выберыце іншае імя.',
'youremail'                  => 'Эл.пошта *',
'username'                   => 'Імя карыстальніка:',
'yourrealname'               => 'Сапраўднае імя *',
'yourlanguage'               => 'Мова:',
'yourvariant'                => 'Варыянт',
'yournick'                   => 'Псеўданім:',
'badsig'                     => 'Недапушчальны крынічны тэкст подпісу; праверце тэгі HTML.',
'email'                      => 'Эл.пошта',
'loginerror'                 => 'Памылка ўваходу',
'loginsuccesstitle'          => 'Паспяховы ўваход у сістэму',
'wrongpassword'              => 'Уведзены няправільны пароль. Паспрабуйце нанова.',
'wrongpasswordempty'         => 'Быў уведзены пусты пароль. Паспрабуйце нанова.',
'mailmypassword'             => 'Адаслаць пароль эл.поштай',
'passwordremindertitle'      => 'Нагаданне пра пароль ад {{SITENAME}}',
'mailerror'                  => 'Памылка адсылання эл.пошты: $1',
'acct_creation_throttle_hit' => 'У вас ужо створаны $1 рахункаў, і большая колькасць не дазваляецца.',
'emailconfirmlink'           => 'Пацвердзіце ваш адрас эл.пошты',
'accountcreated'             => 'Створаны рахунак',
'accountcreatedtext'         => 'Створаны рахунак карыстальніка $1.',

# Edit page toolbar
'bold_sample'     => 'Цёмны тэкст',
'bold_tip'        => 'Цёмны тэкст',
'italic_sample'   => 'Курсіўны тэкст',
'italic_tip'      => 'Курсіўны тэкст',
'link_sample'     => 'Назва спасылкі',
'link_tip'        => 'Унутраная спасылка',
'extlink_sample'  => 'http://www.example.com назва спасылкі',
'extlink_tip'     => 'Вонкавая спасылка (памятайце аб прэфіксе http://)',
'headline_sample' => 'Тэкст загалоўка',
'headline_tip'    => 'Загаловак 2 узроўню',
'math_sample'     => 'Уставіць формулу тут',
'math_tip'        => 'Матэматычная формула (LaTeX)',
'image_sample'    => 'Напрыклад.jpg',
'media_sample'    => 'Напрыклад.ogg',
'media_tip'       => 'Спасылка на медыя-файл',
'sig_tip'         => 'Ваш подпіс і адзначаны час',
'hr_tip'          => 'Гарызантальная рыса (не злоўжывайце гэтым)',

# Edit pages
'summary'               => 'Тлумачэнне',
'minoredit'             => 'Гэта меншая праўка',
'watchthis'             => 'Назіраць за гэтай старонкай',
'savearticle'           => 'Запісаць старонку',
'preview'               => 'Падгляд',
'showdiff'              => 'Паказаць змяненні',
'anoneditwarning'       => 'Вы не ўвайшлі ў сістэму. Таму, калі вы запішаце старонку, у яе гісторыю трапіць ваш адрас IP.',
'missingsummary'        => "'''Нагадваем''': вы не ўпісалі тлумачэння для сваёй праўкі. Калі націснуць Запісаць яшчэ раз, праўка будзе замацавана без тлумачэння.",
'blockedtitle'          => 'Карыстальнік заблакаваны',
'blockedtext'           => "<big>'''Ваша імя карыстальніка або адрас IP былі пастаўлены пад блок.'''</big> Блок пастаўлены карыстальнікам: \$1. Пададзеная прычына: ''\$2''. Вы можаце звярнуцца да \$1 або да аднаго з іншых [[{{ns:project}}:Administrators|адміністратараў]], каб абмеркаваць гэты блок. Заўважце, што без пацверджанага ўласнага адрасу эл.пошты ў [[Special:Preferences|настаўленнях]] вы не можаце выкарыстаць \"эл.пошту да гэтай асобы\". Калі ў вас ёсць рахунак, свае настаўленні вы можаце правіць нават пад блокам. Ваш адрас IP \$3 і нумар блоку #\$5. Дадавайце або адну з дзвюх, або абедзве ідэнтыфікацыі да кожнага звароту, які будзеце рабіць.",
'blockedoriginalsource' => "Крынічны тэкст '''$1''' паказаны ніжэй:",
'blockededitsource'     => "Тэкст '''вашых правак''' у '''$1''' паказаны ніжэй:",
'whitelistedittitle'    => 'Каб рэдагаваць, трэба ўвайсці ў сістэму',
'whitelistedittext'     => 'Належыць $1 каб правіць старонкі.',
'whitelistreadtitle'    => 'Каб чытаць, патрэбны ўваход у сістэму',
'whitelistreadtext'     => 'Трэба [[Special:Userlogin|ўвайсці ў сістэму]] каб адкрываць старонкі.',
'whitelistacctitle'     => 'Вам не дазволена ствараць рахункаў',
'whitelistacctext'      => 'Каб мець дазвол на стварэнне рахункаў у гэтай Вікі вам трэба [[Special:Userlogin|ўвайсці ў сістэму]] і мець неабходныя паўнамоцтвы.',
'confirmedittitle'      => 'Для рэдагавання патрабуецца пацверджаны адрас эл.пошты',
'loginreqtitle'         => 'Патрабуецца ўваход у сістэму',
'accmailtitle'          => 'Быў адасланы пароль.',
'accmailtext'           => 'Пароль для "$1" быў адасланы на $2.',
'newarticle'            => '(Новы)',
'anontalkpagetext'      => "----''Гэта старонка размовы для ананімнага карыстальніка, які або не стварыў яшчэ рахунку, або ім не карыстаўся. Таму дзеля яго ці яе ідэнтыфікацыі мы мусім выкарыстаць лічбавы Адрас IP. Такі адрас IP могуць дзяліць між сабою некалькі асоб. Калі вы ананімны карыстальнік, і лічыце, што атрымліваеце няслушныя заўвагі,[[Special:Userlogin|запішыцеся ў аўтары або ўвайдзіце ў сістэму]], каб вас больш не блыталі з іншымі ананімнымі карыстальнікамі.''",
'previewnote'           => '<strong>Гэта толькі падгляд; змяненні яшчэ не былі замацаваныя!</strong>',
'importing'             => 'Імпартуем $1',
'editing'               => 'Правім $1',
'editingsection'        => 'Правім $1 (раздзел)',
'editingcomment'        => 'Правім $1 (каментар)',
'editconflict'          => 'Канфлікт правак: $1',
'yourtext'              => 'Свой тэкст',
'yourdiff'              => 'Адрозненні',

# Account creation failure
'cantcreateaccounttitle' => 'Немагчыма стварыць рахунак',

# History pages
'revhistory'          => 'Гісторыя версій',
'viewpagelogs'        => 'Паказаць журналы для гэтай старонкі',
'loadhist'            => 'Счытваецца гісторыя старонкі',
'currentrev'          => 'Актуальная версія',
'previousrevision'    => '&larr; Папярэдн. версія',
'nextrevision'        => 'Навейшая версія &rarr;',
'currentrevisionlink' => 'Актуальная версія',
'cur'                 => 'з актуальн.',
'next'                => 'наступ.',
'last'                => 'з папярэд.',
'orig'                => 'арыг.',
'histlegend'          => 'Выбар розніцы: адзначце радыё-боксы версій, якія трэба параўнаць і націсніце enter або кнопку, што ўнізе.<br />
Тлумачэнне: (з актуальн.) = розніца з актуальнай версіяй,
(з папярэд.) = розніца з папярэдняй версіяй, M = меншая праўка.',
'deletedrev'          => '[сцёртая]',
'histfirst'           => 'Самае старое',
'histlast'            => 'Самае новае',

# Revision feed
'history-feed-title'       => 'Гісторыя версій',
'history-feed-description' => 'Гісторыя версій гэтай старонкі',

# Diffs
'difference'              => '(Розніца між версіямі)',
'lineno'                  => 'Радок $1:',
'editcurrent'             => 'Правіць найноўшую версію старонкі',
'compareselectedversions' => 'Параўнаць азначаныя версіі',
'diff-multi'              => '(Не паказан{{plural:$1|а адна прамежкавая версія|ы $1 прамежкавых версій}}.)',

# Search results
'searchresults'     => 'Вынікі пошуку',
'badquery'          => 'Няправільна складзены пошукавы зварот',
'badquerytext'      => 'Не ўдалося апрацаваць ваш зварот.
Магчыма, з-за таго, што шукаўся тэкст, карацейшы за 3 літары,
а гэта яшчэ не падтрымліваецца.
Магчыма, што вы проста няправільна ўпісалі шуканы тэкст.
Паспрабуйце яшчэ, з іншым тэкстам.',
'matchtotals'       => 'Зварот "$1" дае ў выніку $2 назваў старонак
і тэкст $3 старонак.',
'prevn'             => 'папярэдн. $1',
'nextn'             => 'наступ. $1',
'viewprevnext'      => 'Гл. ($1) ($2) ($3).',
'showingresults'    => 'Ніжэй паказаныя да <b>$1</b> вынікаў, пачаўшы з нумару <b>$2</b>.',
'showingresultsnum' => 'Ніжэй паказаныя <b>$3</b> вынікаў, пачаўшы з нумару #<b>$2</b>.',
'powersearch'       => 'Знайсці',

# Preferences page
'preferences'           => 'Настаўленні',
'mypreferences'         => 'Свае настаўленні',
'prefsnologintext'      => 'Каб правіць асабістыя настаўленні, трэба [[Special:Userlogin|ўвайсці ў сістэму]].',
'prefsreset'            => 'Настаўленні вернуты да пачатковых з архіву.',
'changepassword'        => 'Правіць пароль',
'skin'                  => 'Кажух',
'math'                  => 'Матэматыка',
'dateformat'            => 'Фармат даты',
'datetime'              => 'Дата і час',
'math_failure'          => 'Не ўдалося разабраць',
'math_unknown_error'    => 'невядомая памылка',
'math_unknown_function' => 'невядомая функцыя',
'math_syntax_error'     => 'памылка сінтаксісу',
'math_image_error'      => 'Не ўдалося ператварыць PNG; праверце правільнасць інсталяцыі пакетаў latex, dvips, gs, convert',
'math_bad_tmpdir'       => 'Немагчыма запісаць у або стварыць тымчасовы каталог для матэматыкі',
'math_bad_output'       => 'Немагчыма запісаць у або стварыць выводны каталог для матэматыкі',
'math_notexvc'          => 'Не знойдзены выканальны модуль texvc; аб яго настаўленнях чытайце ў math/README.',
'prefs-rc'              => 'Нядаўнія змяненні',
'prefs-watchlist'       => 'Назіранае',
'prefs-watchlist-days'  => 'Кольк. дзён для паказу ў назіраным:',
'prefs-watchlist-edits' => 'Кольк. правак для паказу ў пашыраным відзе назіранага:',
'prefs-misc'            => 'Рознае',
'saveprefs'             => 'Запісаць',
'resetprefs'            => 'Да пачатковага',
'oldpassword'           => 'Стары пароль:',
'newpassword'           => 'Новы пароль:',
'rows'                  => 'Радкі:',
'columns'               => 'Калонкі:',
'searchresultshead'     => 'Знайсці',
'localtime'             => 'Мясцовы час',
'servertime'            => 'Серверны час',
'guesstimezone'         => 'Запоўніць з аглядальніка',
'allowemail'            => 'Дазволіць эл.пошту ад іншых карыстальнікаў',
'defaultns'             => 'Шукаць у гэтых назваглядах, калі не загадана іначай:',
'default'               => 'прадвызначэнні',
'files'                 => 'Файлы',

# User rights
'userrights-lookup-user'     => 'Распараджацца групамі карыстальнікаў',
'userrights-user-editname'   => 'Увядзіце імя карыстальніка:',
'userrights-editusergroup'   => 'Правіць групы карыстальніка',
'userrights-groupsmember'    => 'У групе:',
'userrights-groupsavailable' => 'Наяўныя групы:',

# Groups
'group'            => 'Група:',
'group-bot'        => 'Боты',
'group-sysop'      => 'Сісопы',
'group-bureaucrat' => 'Бюракраты',
'group-all'        => '(усе)',

'group-bot-member'        => 'Бот',
'group-sysop-member'      => 'Сісоп',
'group-bureaucrat-member' => 'Бюракрат',

# User rights log
'rightsnone' => '(няма)',

# Recent changes
'recentchanges'                     => 'Нядаўнія змяненні',
'rcshowhideminor'                   => '$1 меншых правак',
'rcshowhidebots'                    => '$1 робатаў',
'rcshowhideliu'                     => '$1 карыстальнікаў, якія ўвайшлі ў сістэму',
'rcshowhideanons'                   => '$1 ананімных карыстальнікаў',
'rcshowhidepatr'                    => '$1 патруляваных правак',
'rcshowhidemine'                    => '$1 свае праўкі',
'hide'                              => 'Не паказваць',
'show'                              => 'Паказваць',
'minoreditletter'                   => 'м',
'newpageletter'                     => 'Н',
'boteditletter'                     => 'р',
'number_of_watching_users_pageview' => '[$1 назіральнік/аў]',

# Upload
'upload'                      => 'Укласці файл',
'uploadbtn'                   => 'Укласці файл',
'uploadnologintext'           => 'Каб укладваць файлы, трэба [[Special:Userlogin|ўвайсці ў сістэму]].',
'filename'                    => 'Назва файла',
'filedesc'                    => 'Тлумачэнне',
'fileuploadsummary'           => 'Тлумачэнне:',
'filestatus'                  => 'Статус па аўтарскіх правах',
'filesource'                  => 'Крыніца',
'ignorewarning'               => 'Не зважаць на папярэджанне і запісаць файл.',
'ignorewarnings'              => 'Ігнараваць усе папярэджанні',
'badfilename'                 => 'Назва файла зменена на "$1".',
'fileexists'                  => 'Ужо існуе файл з такою назвай, праверце $1, калі не ўпэўнены, што жадаеце мяняць яго змесціва.',
'fileexists-forbidden'        => 'Ужо існуе файл з такою назвай; калі ласка, паўтарыце працэдуру ўкладання файла, але з іншай назвай. [[Image:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => 'У агульным сховішчы ўжо існуе файл з такою назвай; калі ласка, паўтарыце працэдуру ўкладання файла, але з іншай назвай. [[Image:$1|thumb|center|$1]]',
'savefile'                    => 'Запісаць файл',
'uploadvirus'                 => 'Файл утрымлівае вірус! Падрабязнасці: $1',
'destfilename'                => 'Назва мэтавага файла',
'watchthisupload'             => 'Назіраць за гэтай старонкай',
'filewasdeleted'              => 'Файл з такою назвай быў раней укладзены сюды, а потым сцёрты. Варта паглядзець у $1 перад тым, як укладаць яго нанова.',

'license'            => 'Ліцэнзіяванне',
'upload_source_url'  => ' (сапраўдны, публічна дасягальны URL)',
'upload_source_file' => ' (файл на вашай машыне)',

# Image list
'imagelist'                 => 'Спіс файлаў',
'imagelisttext'             => "Ніжэй даецца спіс з '''$1''' {{plural:$1|файла|файлаў}} у парадку $2.",
'imagelistforuser'          => 'Тут паказаны толькі тыя рысункі, якія ўклаў(-ла) $1.',
'getimagelist'              => 'атрымліваем спіс файлаў',
'ilsubmit'                  => 'Знайсці',
'showlast'                  => 'Паказ. апошнія $1 файлаў у парадку $2.',
'byname'                    => 'п. назваў',
'bydate'                    => 'п. датаў',
'bysize'                    => "п. аб'ёмаў",
'imglegend'                 => 'Азначэнні: (desc) = паказаць/правіць апісанне файла.',
'imghistory'                => 'Гісторыя файла',
'deleteimgcompletely'       => 'Сцерці ўсе версіі гэтага файла',
'imagelinks'                => 'Спасылкі',
'uploadnewversion-linktext' => 'Укласці новую версію гэтага файла',
'imagelist_date'            => 'Дата',
'imagelist_name'            => 'Назва',
'imagelist_user'            => 'Карыстальнік',
'imagelist_size'            => 'Памер у байтах',
'imagelist_description'     => 'Апісанне',
'imagelist_search_for'      => 'Знайсці назву рысунку:',

# MIME search
'mimetype' => 'Тып MIME:',

# Unwatched pages
'unwatchedpages' => 'Не-назіраныя старонкі',

# Random redirect
'randomredirect' => 'Выпадковая перасылка',

# Statistics
'statistics'    => 'Статыстыка',
'userstats'     => 'Статыстыка карыстальніка',
'userstatstext' => "Ёсць '''$1''' зарэгістраваных карыстальнікаў, з якіх '''$2''' ('''$4%''') з'яўляюцца $5.",

'doubleredirects' => 'Падвоеныя перасылкі',

'brokenredirects'     => 'Зламаныя перасылкі',
'brokenredirectstext' => 'Наступныя перасылкі паказваюць на няісныя старонкі:',

# Miscellaneous special pages
'nbytes'               => '$1 {{PLURAL:$1|байт|байтаў}}',
'ncategories'          => '$1 {{PLURAL:$1|катэгорыя|катэгорый}}',
'nlinks'               => '$1 {{PLURAL:$1|спасылка|спасылак}}',
'nmembers'             => '$1 {{PLURAL:$1|удзельнік|удзельнікаў}}',
'nviews'               => '$1 {{PLURAL:$1|паказ|паказаў}}',
'lonelypages'          => 'Вельмі адзінокія старонкі',
'unusedcategories'     => 'Нявыкарыстаныя катэгорыі',
'unusedimages'         => 'Нявыкарыстаныя файлы',
'popularpages'         => 'Папулярныя старонкі',
'wantedcategories'     => 'Вельмі патрэбныя катэгорыі',
'wantedpages'          => 'Вельмі патрэбныя старонкі',
'mostcategories'       => 'Артыкулы ў найбольшай кольк. катэгорый',
'mostrevisions'        => 'Артыкулы з найбольшай кольк. версій',
'allpages'             => 'Усе старонкі',
'randompage'           => 'Выпадковая старонка',
'shortpages'           => 'Кароткія старонкі',
'longpages'            => 'Вельмі доўгія старонкі',
'listusers'            => 'Спіс карыстальнікаў',
'specialpages'         => 'Спецыяльныя старонкі',
'newpages'             => 'Новыя старонкі',
'newpages-username'    => 'Імя карыстальніка:',
'ancientpages'         => 'Найстарэйшыя старонкі',
'move'                 => 'Перанесці',
'movethispage'         => 'Перанесці гэту старонку',

'data'           => 'Дадзеныя',
'userrights'     => 'Распараджэнне правамі карыстальніка',
'groups'         => 'Групы карыстальніка',
'alphaindexline' => '$1 да $2',
'version'        => 'Версія',

# Special:Log
'speciallogtitlelabel' => 'Загаловак:',
'log'                  => 'Журналы',
'alllogstext'          => 'Спалучаны паказ журналаў працы з файламі, сцірання, засцерагання і сістэмных аперацый.
Паказ можна ўдакладняць, выбіраючы тып журналу, імя карыстальніка або пэўную старонку.',

# Special:Allpages
'nextpage'          => 'Наступная старонка ($1)',
'allpagesfrom'      => 'Паказваць старонкі ад:',
'allarticles'       => 'Усе артыкулы',
'allinnamespace'    => 'Усе артыкулы ($1 назвагляд)',
'allnotinnamespace' => 'Усе старонкі (не ў назваглядзе $1)',
'allpagesprev'      => 'Папярэдняе',
'allpagesnext'      => 'Наступнае',
'allpagessubmit'    => 'Ісці',
'allpagesprefix'    => 'Паказваць старонкі з прэфіксам:',
'allpagesbadtitle'  => 'Гэтая назва старонкі недапушчальная або ўтрымлівае між-моўны або між-вікавы прэфікс. Магчыма, у назве ёсць знак ці знакі, якія нельга ўжываць у назвах.',

# E-mail user
'emailuser'       => 'Напісаць у эл.пошту гэтаму карыстальніку',
'emailpage'       => 'Напісаць у эл.пошту',
'defemailsubject' => 'эл.пошта {{SITENAME}}',
'emailfrom'       => 'Ад каго',
'emailto'         => 'Каму',
'emailsubject'    => 'Тэма',
'emailmessage'    => 'Ліст',
'emailsend'       => 'Адаслаць',
'emailsent'       => 'Эл.пошта адаслана',
'emailsenttext'   => 'Ваш ліст эл.пошты быў адасланы.',

# Watchlist
'watchlist'            => 'Сваё назіранае',
'mywatchlist'            => 'Сваё назіранае',
'watchlistfor'         => "(для '''$1''')",
'watchlistanontext'    => 'Каб бачыць або правіць складнікі назіранага, трэба $1.',
'watchnologintext'     => 'Каб правіць свой спіс назіранага, трэба [[Special:Userlogin|ўвайсці ў сістэму]].',
'addedwatch'           => 'Дапісана да назіранага',
'addedwatchtext'       => "Старонка \"[[:\$1]]\" была дададзена да [[Special:Watchlist|назіраных]] вамі.
Змяненні, якія адбудуцца з гэтай старонкай і з Размовай пра яе, будуць паказвацца там, і старонка будзе '''вылучацца шрыфтам''' у [[Special:Recentchanges|спісе нядаўніх змяненняў]], каб лягчэй пазнаваць яе.

Калі вы не пажадаеце больш назіраць за гэтай старонкай, націсніце \"Не назіраць\" у бакоўцы.",
'watch'                => 'Назіраць',
'watchthispage'        => 'Назіраць за гэтай старонкай',
'unwatch'              => 'Не назіраць',
'unwatchthispage'      => 'Спыніць назіранне',
'watchnochange'        => 'Ніводзін з назіраных складнікаў не быў зменены за паказаны перыяд.',
'wlheader-enotif'      => '* Працуе апавяшчанне праз эл.пошту.',
'wlheader-showupdated' => "* Старонкі, якія былі зменены пасля вашага апошняга іх наведвання, паказаны '''абрысам шрыфту'''.",
'watchmethod-recent'   => 'правяраем нядаўнія праўкі ў назіраных старонках',
'watchmethod-list'     => 'правяраем наяўнасць нядаўніх правак ў назіраных старонках',
'watchlistcontains'    => 'У вашым спісе назіранага $1 старонак.',
'wlnote'               => 'Ніжэй пададзены апошнія $1 змяненняў за апошнія <b>$2</b> гадз.',
'wlshowlast'           => 'Паказваць апошнія $1 гадз. $2 дзён $3',
'wlsaved'              => 'Гэта запісаная версія вашага спісу назіранага.',

'enotif_newpagetext' => 'Гэта новая старонка.',
'changed'            => 'зменена',
'created'            => 'створана',

# Delete/protect/revert
'deletepage'           => 'Сцерці старонку',
'confirm'              => 'Пацвердзіць',
'exbeforeblank'        => "змесціва перад ачысткаю было: '$1'",
'exblank'              => 'старонка была пустой',
'confirmdelete'        => 'Пацвердзіць сціранне',
'deletesub'            => '(Сціраем "$1")',
'historywarning'       => 'Увага: Старонка, якую вы хочаце сцерці, мае гісторыю:',
'actioncomplete'       => 'Завершана аперацыя',
'deletedarticle'       => 'сцёрты "[[$1]]"',
'dellogpage'           => 'Журнал сціранняў',
'dellogpagetext'       => 'Ніжэй паказаны спіс самых нядаўніх сціранняў.',
'deletionlog'          => 'журнал сціранняў',
'deletecomment'        => 'Прычына сцірання',
'imagereverted'        => 'Ранейшая версія была вернута паспяхова.',
'rollback'             => 'Адкаціць праўкі',
'rollback_short'       => 'Адкат',
'rollbacklink'         => 'адкат',
'rollbackfailed'       => 'Не ўдалося адкаціць',
'cantrollback'         => 'Немагчыма адкаціць праўку; апошні аўтар гэта адзіны аўтар на гэтай старонцы.',
'alreadyrolled'        => 'Немагчыма адкаціць апошнюю праўку ў [[$1]]
аўтарства [[User:$2|$2]] ([[User talk:$2|Размова]]); за гэты час нехта іншы ўжо правіў або адкатваў старонку.

Аўтарства апошняй праўкі: [[User:$3|$3]] ([[User talk:$3|Размова]]).',
'editcomment'          => 'Тлумачэнне праўкі: "<i>$1</i>".', # only shown if there is an edit comment
'protectlogpage'       => 'Журнал засцераганняў',
'unprotectedarticle'   => 'знята засцераганне з "[[$1]]"',
'confirmprotect'       => 'Пацвердзіце засцераганне',
'protectcomment'       => 'Прычына засцерагання',
'unprotectsub'         => '(Здымаем засцераганне з "$1")',
'protect-level-sysop'  => 'Толькі для сісопаў',

# Restrictions (nouns)
'restriction-edit' => 'Правіць',
'restriction-move' => 'Перанесці',

# Undelete
'undelete'                 => 'Паказаць сцёртыя старонкі',
'undeletepage'             => 'Паказаць і аднавіць сцёртыя старонкі',
'viewdeletedpage'          => 'Паказаць сцёртыя старонкі',
'undeletebtn'              => 'Аднавіць',
'undeletereset'            => 'Да пачатковага',
'undeletecomment'          => 'Каментар:',
'undeletedarticle'         => 'адноўлены "[[$1]]"',
'undeletedrevisions'       => '$1 версій адноўлены',
'undeletedrevisions-files' => '$1 версій і $2 файл(аў) адноўлены',
'undeletedfiles'           => '$1 файл(аў) адноўлены',

# Namespace form on various pages
'namespace' => 'Назвагляд:',

# Contributions
'contributions' => 'Унёсак карыстальніка',
'mycontris'     => 'Свае ўнёскі',
'contribsub2'    => 'Для $1 ($2)',

# What links here
'whatlinkshere' => 'Сюды спасылаюцца',
'linklistsub'   => '(спіс спасылак)',

# Block/unblock
'blockip'             => 'Заблакаваць карыстальніка',
'ipaddress'           => 'Адрас IP',
'ipbreason'           => 'Прычына',
'ipbother'            => 'Іншы час',
'ipbotheroption'      => 'іншае',
'badipaddress'        => 'Недапушчальны адрас IP',
'blockipsuccesssub'   => 'Паспяховае блакаванне',
'blocklistline'       => '$1, $2 заблакаваны $3 ($4)',
'infiniteblock'       => 'бясконца',
'expiringblock'       => 'канчаецца $1',
'anononlyblock'       => 'толькі ананімы',
'createaccountblock'  => 'стварэнне рахунку заблакавана',
'blocklink'           => 'заблакаваць',
'autoblocker'         => 'Аўтаматычны блок таму што вашым адрасам IP нядаўна карыстаўся "[[User:$1|$1]]". Блакаванне $1\'s патлумачана так: "\'\'\'$2\'\'\'"',
'blocklogpage'        => 'Журнал блокаў',
'blocklogentry'       => 'пастаўлены блок на "[[$1]]", з часам трывання $2',
'ipb_already_blocked' => '"$1" ужо знаходзіцца пад блокам',
'proxyblocksuccess'   => 'Зроблена.',

# Developer tools
'lockdb'              => 'Замкнуць базу дадзеных',
'unlockdb'            => 'Адмыкнуць базу дадзеных',
'unlockconfirm'       => 'Так, сапраўды жадаю адмыкнуць базу дадзеных.',
'lockbtn'             => 'Замкнуць базу дадзеных',
'unlockbtn'           => 'Адмыкнуць базу дадзеных',
'unlockdbsuccesssub'  => 'Быў зняты замок з базы дадзеных',
'unlockdbsuccesstext' => 'База дадзеных была адмыкнутая.',
'databasenotlocked'   => 'База дадзеных не замкнутая.',

# Move page
'movepage'                => 'Перанесці старонку',
'movearticle'             => 'Перанесці старонку',
'movepagebtn'             => 'Перанесці старонку',
'articleexists'           => 'Старонка з такой назвай ужо існуе, або
вамі выбрана недапушчальнае імя.
Выберыце іншае імя.',
'movedto'                 => 'перанесена ў',
'movetalk'                => 'Перанесці таксама звязаную старонку размовы',
'1movedto2'               => '[[$1]] перанесена ў [[$2]]',
'1movedto2_redir'         => '[[$1]] перанесена ў [[$2]] паводле перасылкі',
'movelogpage'             => 'Журнал пераносаў',
'movelogpagetext'         => 'Ніжэй падаецца спіс пераносаў старонак.',
'movereason'              => 'Тлумачэнне',
'delete_and_move'         => 'Сцерці і перанесці',
'delete_and_move_confirm' => 'Так, сцерці старонку',
'delete_and_move_reason'  => 'Сцёрта, каб зрабіць месца для пераносу',
'immobile_namespace'      => 'Мэтавая назва належыць да спецыяльнага тыпу; у гэты назвагляд пераносіць старонкі немагчыма.',

# Export
'export'        => 'Экспартаваць старонкі',
'exportcuronly' => 'Улучаць толькі актуальную версію, без поўнай гісторыі',
'export-submit' => 'Экспартаваць',

# Namespace 8 related
'allmessages'               => 'Сістэмныя паведамленні',
'allmessagesname'           => 'Назва',
'allmessagesdefault'        => 'Прадвызначаны тэкст',
'allmessagescurrent'        => 'Актуальны тэкст',
'allmessagestext'           => 'Гэта спіс сістэмных паведамленняў, наяўных у назваглядзе MediaWiki.',
'allmessagesnotsupportedUI' => 'Мова інтэрфейсу <b>$1</b> не падтрымліваеца ў Special:Allmessages гэтай пляцоўкі.',
'allmessagesnotsupportedDB' => "Немагчыма паказаць '''Special:Allmessages''', таму што не працуе '''\$wgUseDatabaseMessages'''.",
'allmessagesfilter'         => 'Фільтр назваў паведамленняў:',
'allmessagesmodified'       => 'Паказваць толькі змененыя',

# Thumbnails
'missingimage' => '<b>Прапушчаны рысунак</b><br /><i>$1</i>',
'filemissing'  => 'Адсутны файл',

# Special:Import
'import'                     => 'Імпартаваць старонкі',
'import-interwiki-history'   => 'Капіраваць усе гістарычныя версіі гэтай старонкі',
'import-interwiki-submit'    => 'Імпартаваць',
'import-interwiki-namespace' => 'Перанесці старонкі ў назвагляд:',
'importfailed'               => 'Не ўдалося імпартаваць: $1',

# Tooltip help for the actions
'tooltip-search' => 'Знайсці ў {{SITENAME}}',
'tooltip-save'   => 'Замацаваць свае змяненні',
'tooltip-watch'  => 'Дадаць старонку да назіранага',

# Attribution
'anonymous'        => 'Ананімны карыстальнік(-і) з {{SITENAME}}',
'siteuser'         => 'карыстальнік $1 з {{SITENAME}}',
'lastmodifiedatby' => 'Апошняе змяненне старонкі адбылося $2, $1 аўтарства $3.', # $1 date, $2 time, $3 user
'and'              => 'і',
'othercontribs'    => 'На аснове працы $1.',
'others'           => 'іншае',
'siteusers'        => 'карыстальнік(-і) $1 з {{SITENAME}}',
'creditspage'      => 'Аўтарства старонкі',

# Spam protection
'categoryarticlecount' => 'У катэгорыі {{PLURAL:$1|адзін артыкул|$1 артыкулаў}}.',

# Info page
'numedits'       => 'Кольк. правак (тэксту): $1',
'numtalkedits'   => 'Кольк. правак (у размове): $1',
'numwatchers'    => 'Кольк. назіральнікаў: $1',
'numauthors'     => 'Кольк. розных аўтараў (тэксту): $1',
'numtalkauthors' => 'Кольк. розных аўтараў (у размове): $1',

# Math options
'mw_math_png'    => 'Заўсёды вырабляць PNG',
'mw_math_simple' => 'HTML калі вельмі простае, іначай PNG',
'mw_math_html'   => 'HTML калі магчыма, іначай PNG',
'mw_math_source' => 'Пакідаць у выглядзе TeX (для тэкставых гледачоў)',
'mw_math_modern' => 'Рэкамендуецца сучасным гледачам',
'mw_math_mathml' => 'MathML калі магчыма (эксперыментальнае)',

# Image deletion
'deletedrevision' => 'Сцёрта старая версія $1.',

# Browsing diffs
'previousdiff' => '&larr; Да папярэдн. праўкі',
'nextdiff'     => 'Да наступн. праўкі &rarr;',

# Media information
'imagemaxsize' => 'Абмяжоўваць памеры рысункаў на іх тлумачальных старонках:',

'showhidebots' => '($1 робатаў)',

'passwordtooshort' => 'Гэта занадта кароткі пароль. Трэба мець найменей $1 знакаў у паролі.',

# Metadata
'metadata'          => 'Мета-дадзеныя',
'metadata-expand'   => 'Паказваць падрабязнасці',
'metadata-collapse' => 'Не паказваць падрабязнасці',

# EXIF tags
'exif-imagewidth'            => 'Шырыня',
'exif-imagelength'           => 'Вышыня',
'exif-orientation'           => 'Арыентацыя',
'exif-datetime'              => 'Дата і час змянення файла',
'exif-artist'                => 'Аўтар',
'exif-exifversion'           => 'Версія Exif',
'exif-flashpixversion'       => 'Падтрымліваецца версія Flashpix',
'exif-datetimeoriginal'      => 'Дата і час стварэння дадзеных',
'exif-datetimedigitized'     => 'Дата і час лічбавання',
'exif-exposuretime'          => 'Час вытрымкі',
'exif-exposuretime-format'   => '$1 сек ($2)',
'exif-aperturevalue'         => 'Апертура',
'exif-brightnessvalue'       => 'Яркасць',
'exif-lightsource'           => 'Крыніца святла',
'exif-flash'                 => 'Сполах',
'exif-focallength'           => 'Фокусная адлегласць лінзы',
'exif-focallength-format'    => '$1 мм',
'exif-flashenergy'           => 'Энергія сполаху',
'exif-filesource'            => 'Крыніца файла',
'exif-exposuremode'          => 'Рэжым вытрымкі',
'exif-focallengthin35mmfilm' => 'Фокусная адлегласць 35 мм плёнкі',
'exif-contrast'              => 'Кантраст',
'exif-gpsaltitude'           => 'Вышыня',
'exif-gpsareainformation'    => 'Назва мясцовасці GPS',
'exif-gpsdatestamp'          => 'Дата GPS',

'exif-orientation-1' => 'Звычайна', # 0th row: top; 0th column: left

'exif-exposureprogram-0' => 'Не вызначана',
'exif-exposureprogram-1' => 'Самастойна',
'exif-exposureprogram-7' => 'Партрэтны лад (здымкі ў набліжэнні, асноведзь па-за фокусам)',
'exif-exposureprogram-8' => 'Пейзажны лад (здымкі прасторы, асноведзь у фокусе)',

'exif-meteringmode-0'   => 'Невядома',
'exif-meteringmode-1'   => 'Сярэдняе',
'exif-meteringmode-5'   => 'Узор',
'exif-meteringmode-6'   => 'часткова',
'exif-meteringmode-255' => 'Іншае',

'exif-lightsource-0' => 'Невядома',
'exif-lightsource-4' => 'Сполах',

'exif-focalplaneresolutionunit-2' => 'цаляў',

'exif-exposuremode-0' => 'Аўта-вытрымка',
'exif-exposuremode-1' => 'Самастойная вытрымка',

'exif-scenecapturetype-0' => 'Стандартна',
'exif-scenecapturetype-1' => 'Альбом',
'exif-scenecapturetype-2' => 'Кніга',

'exif-gaincontrol-0' => 'Няма',

'exif-contrast-0' => 'Звычайны',
'exif-contrast-1' => 'Мяккі',
'exif-contrast-2' => 'Высокі',

'exif-saturation-0' => 'Звычайна',

'exif-sharpness-0' => 'Звычайны',
'exif-sharpness-1' => 'Мяккі',
'exif-sharpness-2' => 'Высокі',

'exif-subjectdistancerange-0' => 'Невядома',
'exif-subjectdistancerange-1' => 'Макрас',

# External editor support
'edit-externally' => 'Правіць файл у вонкавай праграме',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'усе',
'imagelistall'     => 'усе',
'watchlistall2'    => 'усе',
'namespacesall'    => 'усе',

# E-mail address confirmation
'confirmemail'           => 'Пацвердзіць адрас эл.пошты',
'confirmemail_noemail'   => 'У [[Special:Preferences|вашых настаўленнях]] няма дапушчальнага адрасу эл.пошты.',
'confirmemail_send'      => 'Адаслаць код пацверджання',
'confirmemail_sent'      => 'Адасланы ліст эл.пошты з пацверджаннем.',
'confirmemail_invalid'   => 'Няправільны код пацверджання. Магчыма, неактуальны код.',
'confirmemail_needlogin' => 'Вам трэба зрабіць $1 каб пацвердзіць свой адрас эл.пошты.',
'confirmemail_loggedin'  => 'Зараз ваш адрас эл.пошты стаўся пацверджаным.',
'confirmemail_error'     => 'Неакрэсленая памылка пры запісванні пацверджання.',

# Inputbox extension, may be useful in other contexts as well
'createarticle' => 'Пачаць артыкул',

# Delete conflict
'deletedwhileediting' => 'Увага: гэтая старонка была сцёрта пасля таго, як вы пачалі рэдагаванне!',
'confirmrecreate'     => "Карыстальнік [[User:$1|$1]] ([[User talk:$1|размова]]) сцёр гэты артыкул пасля таго, як вы пачалі працу з ім, падаўшы прычыну:
: ''$2''
Пацвердзіце свой намер аднавіць гэты артыкул.",

'unit-pixel' => 'крпк',

# HTML dump
'redirectingto' => 'Перасылаемся да [[$1]]...',

# action=purge
'confirm_purge' => 'Ці ачысціць кэш для гэтай старонкі?

$1',

'youhavenewmessagesmulti' => 'У вас ёсць новыя паведамленні на $1',

'searchcontaining' => "Знайсці артыкулы, у якіх ёсць ''$1''.",
'searchnamed'      => "Знайсці артыкулы з назвай ''$1''.",
'articletitles'    => "Артыкулы, чые назвы пачынаюцца з ''$1''",
'hideresults'      => 'Не паказваць вынікаў',

'loginlanguagelabel' => 'Мова: $1',

# Multipage image navigation
'imgmultipageprev' => '&larr; папярэдняя старонка',
'imgmultipagenext' => 'наступная старонка &rarr;',
'imgmultigo'       => 'Ісці!',
'imgmultigotopre'  => 'Ісці на старонку',

# Table pager
'table_pager_next'         => 'Наступная старонка',
'table_pager_prev'         => 'Папярэдняя старонка',
'table_pager_first'        => 'Першая старонка',
'table_pager_last'         => 'Апошняя старонка',
'table_pager_limit_submit' => 'Ісці',
'table_pager_empty'        => 'Без вынікаў',

# Auto-summaries
'autoredircomment' => 'Перасылаемся да [[$1]]', # This should be changed to the new naming convention, but existed beforehand

);


