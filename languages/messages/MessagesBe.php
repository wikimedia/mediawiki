<?php
/** Belarusian (Беларуская)
 *
 * @addtogroup Language
 *
 * @author Yury Tarasievich
 * @author G - ג
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
'tog-watchcreations'      => 'Старонкі, створаныя мной, дадаюцца да назіранага',
'tog-watchdefault'        => 'Старонкі, праўленыя мной, дадаюцца да назіранага',
'tog-watchmoves'          => 'Старонкі, перанесеныя мной, дадаюцца да назіранага',
'tog-watchdeletion'       => 'Старонкі, сцёртыя мной, дадаюцца да назіранага',
'tog-minordefault'        => "Кожная праўка пачынаецца як ''дробная''",
'tog-previewontop'        => 'Паказваць падгляд перад полем рэдактара',
'tog-previewonfirst'      => 'Паказваць падгляд пры першай праўцы',
'tog-shownumberswatching' => 'Паказваць колькасць назіральнікаў',
'tog-watchlisthideown'    => 'Не паказваць у назіраным сваіх правак',
'tog-watchlisthidebots'   => 'Не паказваць у назіраным правак, зробленых робатамі',
'tog-watchlisthideminor'  => 'Не паказваць у назіраным дробных правак',

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
'pagecategories'        => '{{PLURAL:$1|Катэгорыя|Катэгорыі}}',
'category_header'       => 'Складнікі ў катэгорыі “$1”',
'subcategories'         => 'Падкатэгорыі',
'category-media-header' => 'Мультымедыя ў катэгорыі &quot;$1&quot;',
'category-empty'        => "''Зараз у катэгорыі няма аніводнай старонкі або мультымедыйнага файла.''",

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
'qbspecialpages' => 'Адмысловыя старонкі',
'moredotdotdot'  => 'Яшчэ...',
'mypage'         => 'Свая старонка',
'mytalk'         => 'Размовы',
'anontalk'       => 'Размова для гэтага IP',
'navigation'     => 'Навігацыя',

'errorpagetitle'    => 'Памылка',
'returnto'          => 'Вярнуцца да $1.',
'tagline'           => 'З {{GRAMMAR:родны|{{SITENAME}}}}.',
'help'              => 'Даведка',
'search'            => 'Знайсці',
'searchbutton'      => 'Тэкст',
'go'                => 'Ісці',
'searcharticle'     => 'Артыкул',
'history'           => 'Гісторыя старонкі',
'history_short'     => 'Гісторыя',
'info_short'        => 'Інфармацыя',
'printableversion'  => 'Для друку',
'permalink'         => 'Нязменная спасылка',
'print'             => 'Друкаваць',
'edit'              => 'Рэдагаваць',
'editthispage'      => 'Правіць гэту старонку',
'delete'            => 'сцерці',
'deletethispage'    => 'Сцерці гэту старонку',
'undelete_short'    => 'Аднавіць {{PLURAL:$1|адну праўку|$1 правак}}',
'protect'           => 'Ахова',
'protectthispage'   => 'Пачаць ахоўваць гэтую старонку',
'unprotect'         => 'зняць ахову',
'unprotectthispage' => 'Зняць ахову з гэтай старонкі',
'newpage'           => 'Новая старонка',
'talkpage'          => 'Размовы пра гэтую старонку',
'talkpagelinktext'  => 'размова',
'personaltools'     => 'Асабістыя прылады',
'postcomment'       => 'Пакінуць заўвагу',
'articlepage'       => 'Паказаць старонку змесціва',
'talk'              => 'Размовы',
'views'             => 'Віды',
'toolbox'           => 'Прылады',
'userpage'          => 'Паказаць старонку ўдзельніка',
'imagepage'         => 'Гл. старонку выявы',
'viewhelppage'      => 'Паказаць старонку даведкі',
'categorypage'      => 'Гл. старонку катэгорыі',
'viewtalkpage'      => 'Паказаць размову',
'otherlanguages'    => 'На іншых мовах',
'redirectedfrom'    => '(Пасля перасылкі з $1)',
'redirectpagesub'   => 'Старонка-перасылка',
'lastmodifiedat'    => 'Апошняе змяненне старонкі адбылося $2, $1.', # $1 date, $2 time
'viewcount'         => 'Гэту старонку адкрывалі {{plural:$1|адзін раз|$1 разоў}}.',
'protectedpage'     => 'Старонка пад аховай',
'jumpto'            => 'Перайсці да:',
'jumptonavigation'  => 'рух',
'jumptosearch'      => 'знайсці',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'         => 'Пра {{GRAMMAR:вінавальны|{{SITENAME}}}}',
'aboutpage'         => '{{ns:Project}}:Пра {{GRAMMAR:вінавальны|{{SITENAME}}}}',
'bugreports'        => 'Пра памылкі',
'copyright'         => 'Матэрыял даступны на ўмовах $1.',
'copyrightpagename' => 'Аўтарскія правы {{GRAMMAR:родны|{{SITENAME}}}}',
'currentevents'     => 'Актуальныя падзеі',
'currentevents-url' => '{{ns:project}}:Актуальныя падзеі',
'disclaimers'       => 'Адмовы ад адказнасці',
'edithelp'          => 'Даведка рэдагавальнага акна',
'faq'               => 'ЧАПЫ',
'faqpage'           => '{{ns:project}}:ЧАПЫ',
'helppage'          => '{{ns:help}}:Змест',
'mainpage'          => 'Першая старонка',
'portal'            => 'Супольнасць',
'portal-url'        => '{{ns:project}}:Супольнасць',
'privacy'           => 'Палітыка прыватнасці',
'sitesupport'       => 'Ахвяраванні',

'badaccess'        => 'Памылка ў дазволах',
'badaccess-group0' => 'Вам не дазволена выконваць аперацыю, па якую вы звярталіся.',
'badaccess-group1' => 'Аперацыя, па якую вы звярталіся, дазволена толькі ўдзельнікам з групы $1.',
'badaccess-group2' => 'Аперацыя, па якую вы звярталіся, дазволена толькі ўдзельнікам з адной з груп $1.',
'badaccess-groups' => 'Аперацыя, па якую вы звярталіся, дазволена толькі ўдзельнікам з адной з груп $1.',

'versionrequired'     => 'Патрабуецца MediaWiki версіі $1',
'versionrequiredtext' => 'Каб карыстацца гэтай старонкай, патрабуецца MediaWiki версіі $1. Гл. [[Special:Version]]',

'pagetitle'               => '$1 — Вікіпедыя',
'retrievedfrom'           => 'Узята з "$1"',
'youhavenewmessages'      => 'Вы маеце $1 ($2).',
'newmessageslink'         => 'новыя паведамленні',
'newmessagesdifflink'     => 'розн. з найноўшай версіяй',
'youhavenewmessagesmulti' => 'У вас ёсць новыя паведамленні на $1',
'editsection'             => 'правіць',
'editold'                 => 'правіць',
'editsectionhint'         => 'Правіць раздзел: $1',
'toc'                     => 'Змест',
'showtoc'                 => 'паказаць',
'hidetoc'                 => 'не паказваць',
'viewdeleted'             => 'Ці паказаць $1?',
'restorelink'             => '{{PLURAL:$1|адна сцёртая праўка|$1 сцёртых правак}}',
'feedlinks'               => 'Струмень:',
'feed-invalid'            => 'Недапушчальны тып струмяня навін.',
'site-rss-feed'           => '$1 струмень RSS',
'site-atom-feed'          => '$1 струмень Atom',
'page-rss-feed'           => '"$1" струмень RSS',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Артыкул',
'nstab-user'      => 'Свая Старонка',
'nstab-media'     => 'Мультымедыя',
'nstab-special'   => 'Адмысловая',
'nstab-project'   => 'Старонка праекту',
'nstab-image'     => 'Файл',
'nstab-mediawiki' => 'Паведамленне',
'nstab-template'  => 'Шаблон',
'nstab-help'      => 'Старонка даведкі',
'nstab-category'  => 'Катэгорыя',

# General errors
'error'                => 'Памылка',
'databaseerror'        => 'Памылка базы дадзеных',
'dberrortext'          => 'Памылка ў сінтаксісе звароту ў базу даных.
Магчыма, прычына ў памылцы ў праграмным забеспячэнні.
Апошні зварот у базу, які спрабаваўся:

&lt;blockquote&gt;&lt;tt&gt;$1&lt;/tt&gt;&lt;/blockquote&gt;
з абсягу функцыі &quot;&lt;tt&gt;$2&lt;/tt&gt;&quot;.
Памылка, вернутая з MySQL &quot;&lt;tt&gt;$3: $4&lt;/tt&gt;&quot;.',
'dberrortextcl'        => 'Памылка ў сінтаксісе звароту ў базу даных. 
Апошні зварот у базу, які спрабаваўся:

&quot;$1&quot;
з абсягу функцыі &quot;$2&quot;.
Памылка, вернутая з MySQL &quot;$3: $4&quot;',
'filecopyerror'        => 'Не ўдалося капіраваць файл &quot;$1&quot; у &quot;$2&quot;.',
'filerenameerror'      => 'Не ўдалося назваць файл &quot;$1&quot; назвай &quot;$2&quot;.',
'filedeleteerror'      => 'Не ўдалося сцерці файл &quot;$1&quot;.',
'filenotfound'         => 'Не ўдалося знайсці файл &quot;$1&quot;.',
'unexpected'           => 'Нечаканае значэнне: &quot;$1&quot;=&quot;$2&quot;.',
'formerror'            => 'Памылка: не ўдалося падаць форму',
'badarticleerror'      => 'Аперацыя не дазволена на гэтай старонцы.',
'badtitle'             => 'Няправільная назва',
'badtitletext'         => 'Назва старонкі, па якую звярталіся, аказалася недапушчальнай, пустой, або няправільна прылучанай між-моўнай ці між-вікі назвай. Магчыма, у ёй ёсць знакі, якія нельга ўжываць у назвах.',
'perfcachedts'         => 'Кэшавыя звесткі, дата апошняй актуалізацыі $1.',
'wrong_wfQuery_params' => 'Недапушчальныя параметры wfQuery()&lt;br /&gt;

Функцыя: $1&lt;br /&gt;
Зварот: $2',
'viewsource'           => 'Паказаць выточны тэкст',
'viewsourcefor'        => 'для $1',
'viewsourcetext'       => 'Можна бачыць і капіраваць крынічны тэкст гэтай старонкі:',
'editinginterface'     => "'''Увага:''' Вы мяняеце старонку, якая ўжываецца, каб паказваць інтэрфейсны тэкст гэтага праграмнага забеспячэння. Праўкі, зробленыя тут, зменяць выгляд інтэрфейсу для ўсіх удзельнікаў.",

# Login and logout pages
'welcomecreation'            => '== Вітаем, $1! == Ваш  рахунак быў створаны. Не забудзьцеся дапасаваць свае настаўленні ў {{SITENAME}}.',
'yourname'                   => 'Імя ўдзельніка',
'yourpassword'               => 'Пароль',
'yourpasswordagain'          => 'Паўтарыце пароль',
'remembermypassword'         => 'Памятаць мяне',
'yourdomainname'             => 'Ваш дамен',
'externaldberror'            => 'Або памылка вонкавай аўтэнтыкацыі ў базе дадзеных, або вам не дазволена абнаўляць свой вонкавы рахунак.',
'login'                      => 'Увайсці ў сістэму',
'loginprompt'                => 'Каб уваходзіць у сістэму {{SITENAME}}, трэба дазволіць у браўзеры квіткі (кукі).',
'userlogin'                  => 'Увайсці ў сістэму / стварыць рахунак',
'logout'                     => 'Выйсці з сістэмы',
'userlogout'                 => 'Выйсці з сістэмы',
'nologin'                    => 'Не маеце свайго рахунку? $1.',
'nologinlink'                => 'Завесці рахунак',
'createaccount'              => 'Стварыць рахунак',
'gotaccount'                 => 'Ужо маеце рахунак? $1.',
'gotaccountlink'             => 'Увайсці ў сістэму',
'createaccountmail'          => 'праз эл.пошту',
'badretype'                  => 'Уведзеныя паролі не аднолькавыя.',
'userexists'                 => 'Такое імя ўдзельніка ўжо занятае. Калі ласка, выберыце іншае.',
'youremail'                  => 'Эл.пошта *',
'username'                   => 'Імя ўдзельніка:',
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
'passwordtooshort'           => 'Гэта занадта кароткі пароль. Трэба мець найменей $1 знакаў у паролі.',
'mailmypassword'             => 'Адаслаць пароль эл.поштай',
'passwordremindertitle'      => 'Нагаданне пра пароль ад {{SITENAME}}',
'eauthentsent'               => 'Пацверджанне было адасланае эл.поштай на азначаны адрас эл.пошты.
Каб туды, у далейшым, трапляла іншая эл.пошта адсюль, патрабуецца выканаць інструкцыі, выкладзеныя ў гэтым эл.паведамленні, каб пацвердзіць сваё права на рахунак эл.пошты.',
'mailerror'                  => 'Памылка адсылання эл.пошты: $1',
'acct_creation_throttle_hit' => 'У вас ужо створаны $1 рахункаў, і большая колькасць не дазваляецца.',
'emailconfirmlink'           => 'Пацвердзіце ваш адрас эл.пошты',
'accountcreated'             => 'Створаны рахунак',
'accountcreatedtext'         => 'Створаны рахунак удзельніка $1.',
'loginlanguagelabel'         => 'Мова: $1',

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
'nowiki_sample'   => 'Гэта нефарматаваны тэкст',
'nowiki_tip'      => 'Без вікі-фарматавання',
'image_sample'    => 'Напрыклад.jpg',
'image_tip'       => 'Выява ў тэксце',
'media_sample'    => 'Напрыклад.ogg',
'media_tip'       => 'Спасылка на медыя-файл',
'sig_tip'         => 'Ваш подпіс і адзначаны час',
'hr_tip'          => 'Гарызантальная рыса (не злоўжывайце гэтым)',

# Edit pages
'summary'                => 'Тлумачэнне',
'subject'                => 'Тэма/загаловак',
'minoredit'              => 'Дробная праўка',
'watchthis'              => 'Назіраць за гэтай старонкай',
'savearticle'            => 'Запісаць',
'preview'                => 'Падгляд',
'showpreview'            => 'Як будзе',
'showdiff'               => 'Розніца',
'anoneditwarning'        => 'Вы не ўвайшлі ў сістэму. Таму, калі вы запішаце старонку, у яе гісторыю трапіць ваш адрас IP.',
'missingsummary'         => "'''Нагадваем''': вы не ўпісалі тлумачэння для сваёй праўкі. Калі націснуць Запісаць яшчэ раз, праўка будзе замацавана без тлумачэння.",
'summary-preview'        => 'Перадпаказ апісання',
'blockedtitle'           => 'Удзельнік заблакаваны',
'blockedtext'            => "&lt;big&gt;'''Ваша імя ўдзельніка або адрас IP былі пастаўлены пад блок.'''&lt;/big&gt; Блок быў пастаўлены ўдзельнікам: $1. Пададзеная прычына: ''$2''. Вы можаце звярнуцца да $1 або да аднаго з іншых [[{{ns:project}}:Administrators|адміністратараў]], каб паразмаўляць пра гэты блок. Заўважце, што без пацверджанага ўласнага адрасу эл.пошты ў [[Special:Preferences|настаўленнях]] вы не можаце выкарыстаць &quot;эл.пошту да гэтай асобы&quot;. Калі ў вас ёсць рахунак, свае настаўленні вы можаце правіць нават пад блокам. Ваш адрас IP $3 і нумар блоку #$5. Дадавайце або адну з дзвюх, або абедзве ідэнтыфікацыі да кожнага звароту, які будзеце рабіць.",
'blockedoriginalsource'  => "Крынічны тэкст '''$1''' паказаны ніжэй:",
'blockededitsource'      => "Тэкст '''вашых правак''' у '''$1''' паказаны ніжэй:",
'whitelistedittitle'     => 'Каб правіць старонку, трэба ўвайсці ў сістэму',
'whitelistedittext'      => 'Належыць $1 каб правіць старонкі.',
'whitelistreadtitle'     => 'Каб чытаць, патрэбны ўваход у сістэму',
'whitelistreadtext'      => 'Трэба [[Special:Userlogin|ўвайсці ў сістэму]] каб адкрываць старонкі.',
'whitelistacctitle'      => 'Вам не дазволена ствараць рахункаў',
'whitelistacctext'       => 'Каб мець дазвол на стварэнне рахункаў у гэтай Вікі вам трэба [[Special:Userlogin|ўвайсці ў сістэму]] і мець неабходныя паўнамоцтвы.',
'confirmedittitle'       => 'Для рэдагавання патрабуецца пацверджаны адрас эл.пошты',
'loginreqtitle'          => 'Патрабуецца ўваход у сістэму',
'accmailtitle'           => 'Быў адасланы пароль.',
'accmailtext'            => 'Пароль для &quot;$1&quot; быў адасланы на $2.',
'newarticle'             => '(Новы)',
'newarticletext'         => 'Вы перайшлі да старонкі, якой яшчэ няма, і таму трапілі сюды. Каб пачаць новую старонку, пішыце яе тэкст у ніжэйпаказаным акне рэдагавання (падрабязнасці бач у [[{{MediaWiki:helppage}}|даведцы]]). Калі вы тут выпадкова, проста націсніце "назад" у браўзеры.',
'anontalkpagetext'       => "----''Гэта старонка размовы з ананімным удзельнікам, які або не мае свайго рахунку, або ім не карыстаўся. Таму дзеля яго ці яе ідэнтыфікацыі мы мусім выкарыстаць лічбавы Адрас IP. Такі адрас IP могуць дзяліць між сабою некалькі асоб. Калі вы ананімны ўдзельнік, і лічыце, што атрымліваеце няслушныя заўвагі,[[Special:Userlogin|завядзіце сабе рахунак або ўвайдзіце ў сістэму]], каб вас больш не блыталі з іншымі ананімнымі ўдзельнікамі.''",
'noarticletext'          => 'Такая старонка яшчэ не існуе. Вы можаце [[Special:Search/{{PAGENAME}}|пашукаць такога тэксту]] ў іншых артыкулах, або [{{fullurl:{{FULLPAGENAME}}|action=edit}} стварыць новы артыкул].',
'usercssjsyoucanpreview' => "&lt;strong&gt;Наменка:&lt;/strong&gt; Пакарыстайцеся кнопкай &quot;''{{:{{ns:mediawiki}}:showpreview}}''&quot;, каб выпрабаваць новы код CSS/JS, ''перш'' чым яго запісваць.",
'previewnote'            => '&lt;strong&gt;Гэта толькі падгляд; змяненні яшчэ не былі замацаваныя!&lt;/strong&gt;',
'editing'                => 'Правім: $1',
'editingsection'         => 'Правім $1 (раздзел)',
'editingcomment'         => 'Правім: $1 (каментар)',
'editconflict'           => 'Канфлікт правак: $1',
'yourtext'               => 'Свой тэкст',
'editingold'             => "&lt;strong&gt;УВАГА: Вы правіце такую версію артыкула, якая не з'яўляецца актуальнай.
Калі вы яе зараз запішаце, то страціце змены ў артыкуле, зробленыя пасля колішняга запісу гэтай версіі.&lt;/strong&gt;",
'yourdiff'               => 'Адрозненні',
'copyrightwarning'       => 'Заўважце, што ўсе ўклады на {{SITENAME}} лічацца выданымі на ўмовах $2 (бач падрабязнасці на $1). Калі вы не жадаеце, каб вашыя матэрыялы бязлітасна правіліся, і свабодна распаўсюджваліся, то і не аддавайце іх сюды.<br />
Таксама вы нам абяцаеце, што напісалі гэта самі, або скапіравалі з рэсурсу, які знаходзіцца ў публічнай уласнасці, або з аналагічнага свабоднага рэсурсу.
<strong>НЕ КЛАДЗІЦЕ СЮДЫ, БЕЗ АДПАВЕДНАГА ДАЗВОЛУ, МАТЭРЫЯЛУ, ЯКІ АХОЎВАЕЦЦА АЎТАРСКІМ ПРАВАМ!</strong>',
'copyrightwarning2'      => 'Заўважце, што кожны ўклад на {{SITENAME}} можа быць папраўлены, зменены або выдалены іншымі ўдзельнікамі. Калі вы не жадаеце, каб вашыя матэрыялы бязлітасна правіліся, то і не давайце іх сюды.<br />
Таксама вы нам абяцаеце, што напісалі гэта самі, або скапіравалі з рэсурсу, які знаходзіцца ў публічнай уласнасці, або з аналагічнага свабоднага рэсурсу (бач падрабязнасці на $1).
<strong>НЕ КЛАДЗІЦЕ СЮДЫ, БЕЗ АДПАВЕДНАГА ДАЗВОЛУ, МАТЭРЫЯЛУ, ЯКІ АХОЎВАЕЦЦА АЎТАРСКІМ ПРАВАМ!</strong>',
'longpagewarning'        => "<strong>УВАГА: Старонка дасягае аб'ёму $1 кілабайтаў; некаторыя браўзеры не адольваюць старонак з аб'ёмам, блізкім ці большым за 32 Кб.
Падумайце, ці можна падзяліць старонку на некалькі меншых.</strong>",
'protectedpagewarning'   => '&lt;strong&gt;УВАГА:  Гэтая старонка пастаўлена пад ахову, і таму яе могуць правіць толькі адміністратары. Праверце, ці Вы кіруецеся [[{{ns:project}}:Праца з засцераганымі старонкамі|правіламі працы са старонкамі пад аховай]].&lt;/strong&gt;',
'templatesused'          => 'Шаблоны на гэтай старонцы:',
'templatesusedpreview'   => 'Шаблоны ў гэтым падглядзе:',
'templatesusedsection'   => 'Шаблоны ў гэтым раздзеле:',
'template-protected'     => '(ахоўваецца)',
'template-semiprotected' => '(часткова ахоўвацца)',
'nocreatetext'           => 'На гэтай пляцоўцы абмежаваныя магчымасці стварэння новых старонак.
Вы можаце папрацаваць з існуючай старонкай, або [[Special:Userlogin|увайсці ў сістэму ці завесці сабе рахунак]].',
'recreate-deleted-warn'  => "'''Увага: Вы аднаўляеце старонку, якая раней была сцёрта.'''

Трэба падумаць, ці варта далей працаваць з гэтай старонкай.
Вось журнал сціранняў для гэтай старонкі:",

# Account creation failure
'cantcreateaccounttitle' => 'Немагчыма стварыць рахунак',

# History pages
'revhistory'          => 'Гісторыя версій',
'viewpagelogs'        => 'Паказаць журналы для гэтай старонкі',
'revnotfound'         => 'Версія не знойдзена',
'revnotfoundtext'     => 'Не ўдалося знайсці ранейшую версію гэтага артыкула, па якую вы звярталіся.
Праверце URL, праз які вы спрабавалі адкрыць старонку.',
'loadhist'            => 'Счытваецца гісторыя старонкі',
'currentrev'          => 'Актуальная версія',
'revisionasof'        => 'Версія ад $1',
'revision-info'       => 'Версія ад $1, аўтар $2',
'previousrevision'    => '← Папярэдн. версія',
'nextrevision'        => 'Навейшая версія →',
'currentrevisionlink' => 'Актуальная версія',
'cur'                 => 'з актуальн.',
'next'                => 'наступ.',
'last'                => 'з папярэд.',
'orig'                => 'арыг.',
'page_first'          => 'перш.',
'page_last'           => 'апошн.',
'histlegend'          => 'Выбар розніцы: адзначце радыё-боксы версій, якія трэба параўнаць і націсніце enter або кнопку, што ўнізе.&lt;br /&gt; Тлумачэнне: (з актуальн.) = розніца з актуальнай версіяй, (з папярэд.) = розніца з папярэдняй версіяй, д = дробная праўка.',
'deletedrev'          => '[сцёртая]',
'histfirst'           => 'Самае старое',
'histlast'            => 'Самае новае',

# Revision feed
'history-feed-title'          => 'Гісторыя версій',
'history-feed-description'    => 'Гісторыя версій гэтай старонкі',
'history-feed-item-nocomment' => '$1 на $2', # user at time

# Diffs
'history-title'           => 'Гісторыя версій "$1"',
'difference'              => '(Розніца між версіямі)',
'loadingrev'              => 'счытваецца версія для параўнання',
'lineno'                  => 'Радок $1:',
'editcurrent'             => 'Правіць актуальную версію старонкі',
'compareselectedversions' => 'Параўнаць азначаныя версіі',
'editundo'                => 'адкат',
'diff-multi'              => '(Не паказан{{plural:$1|а адна прамежкавая версія|ы $1 прамежкавых версій}}.)',

# Search results
'searchresults'     => 'Вынікі пошуку',
'noexactmatch'      => "'''Няма старонкі з назвай \"\$1\".''' Вы можаце яе [[:\$1|стварыць]].",
'prevn'             => 'папярэдн. $1',
'nextn'             => 'наступ. $1',
'viewprevnext'      => 'Гл. ($1) ($2) ($3).',
'showingresults'    => 'Ніжэй паказаныя да &lt;b&gt;$1&lt;/b&gt; вынікаў, пачаўшы з нумару &lt;b&gt;$2&lt;/b&gt;.',
'showingresultsnum' => 'Ніжэй паказаныя &lt;b&gt;$3&lt;/b&gt; вынікаў, пачаўшы з нумару #&lt;b&gt;$2&lt;/b&gt;.',
'powersearch'       => 'Знайсці',

# Preferences page
'preferences'           => 'Настаўленні',
'mypreferences'         => 'Настáўленні',
'prefsnologintext'      => 'Каб правіць асабістыя настаўленні, трэба [[Special:Userlogin|ўвайсці ў сістэму]].',
'prefsreset'            => 'Настаўленні вернуты да пачатковых з архіву.',
'changepassword'        => 'Правіць пароль',
'skin'                  => 'Кажух',
'math'                  => 'Матэматыка',
'dateformat'            => 'Фармат даты',
'datedefault'           => 'Не вызначана',
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
'prefs-watchlist'       => 'Спіс назіранага',
'prefs-watchlist-days'  => 'Кольк. дзён для паказу ў назіраным:',
'prefs-watchlist-edits' => 'Кольк. правак для паказу ў пашыраным відзе назіранага:',
'prefs-misc'            => 'Рознае',
'saveprefs'             => 'Запісаць',
'resetprefs'            => 'Да пачатковых',
'oldpassword'           => 'Стары пароль:',
'newpassword'           => 'Новы пароль:',
'rows'                  => 'Радкі:',
'columns'               => 'Калонкі:',
'searchresultshead'     => 'Знайсці',
'timezonelegend'        => 'Часавы пояс',
'localtime'             => 'Мясцовы час',
'servertime'            => 'Актуальны час на серверы',
'guesstimezone'         => 'Запоўніць з аглядальніка',
'allowemail'            => 'Атрымліваць эл.пошту ад іншых удзельнікаў',
'defaultns'             => 'Шукаць у гэтых прасторах назваў, калі не загадана іначай:',
'default'               => 'прадвызначэнні',
'files'                 => 'Файлы',

# User rights
'userrights-lookup-user'     => 'Распараджацца групамі ўдзельнікаў',
'userrights-user-editname'   => 'Увядзіце імя ўдзельніка:',
'userrights-editusergroup'   => 'Распараджацца групамі ўдзельніка',
'userrights-groupsmember'    => 'У групе:',
'userrights-groupsavailable' => 'Наяўныя групы:',
'userrights-reason'          => 'Тлумачэнне змянення:',

# Groups
'group'            => 'Група:',
'group-bot'        => 'Боты',
'group-sysop'      => 'Адміністратары',
'group-bureaucrat' => 'Бюракраты',
'group-all'        => '(усе)',

'group-bot-member'        => 'Бот',
'group-sysop-member'      => 'Адміністратар',
'group-bureaucrat-member' => 'Бюракрат',

# User rights log
'rightslog'  => 'Журнал правоў удзельнікаў',
'rightsnone' => '(няма)',

# Recent changes
'nchanges'                          => '$1 {{PLURAL:$1|мена|менаў}}',
'recentchanges'                     => 'Апошнія змены',
'recentchangestext'                 => 'Сачыце за апошнімі зменамі ў {{GRAMMAR:месны|{{SITENAME}}}} на гэтай старонцы.',
'recentchanges-feed-description'    => 'Сачыць за найбольш актуальнымі змяненнямі ў віксе праз гэты струмень навін.',
'rcnote'                            => 'Ніжэй паказаныя апошнія &lt;strong&gt;$1&lt;/strong&gt; зменаў у апошнія &lt;strong&gt;$2&lt;/strong&gt; дзён, па стане на $3.',
'rcnotefrom'                        => 'Ніжэй знаходзяцца змены з &lt;b&gt;$2&lt;/b&gt; (да &lt;b&gt;$1&lt;/b&gt; на старонку).',
'rclistfrom'                        => 'Паказаць змены з $1',
'rcshowhideminor'                   => '$1 дробных правак',
'rcshowhidebots'                    => '$1 робатаў',
'rcshowhideliu'                     => '$1 удзельнікаў, якія ўвайшлі ў сістэму',
'rcshowhideanons'                   => '$1 ананімных удзельнікаў',
'rcshowhidepatr'                    => '$1 патруляваных правак',
'rcshowhidemine'                    => '$1 свае праўкі',
'rclinks'                           => 'Паказаць апошнія $1 зменаў за мінулыя $2 дзён&lt;br /&gt;$3',
'diff'                              => 'розн.',
'hist'                              => 'гіст.',
'hide'                              => 'не паказваць',
'show'                              => 'паказваць',
'minoreditletter'                   => 'д',
'newpageletter'                     => 'Н',
'boteditletter'                     => 'р',
'number_of_watching_users_pageview' => '[$1 назіральнік/аў]',

# Recent changes linked
'recentchangeslinked'          => 'Звязаныя праўкі',
'recentchangeslinked-title'    => 'Змяненні, якія датычаць $1',
'recentchangeslinked-noresult' => 'Без змяненняў на далучаных старонках за азначаны перыяд.',
'recentchangeslinked-summary'  => "Гэтая адмысловая старонка пералічвае апошнія змяненні на далучаных старонках. Старонкі, назіраныя вамі, выдзелены '''стылем'''.",

# Upload
'upload'                      => 'Укласці файл',
'uploadbtn'                   => 'Укласці файл',
'uploadnologintext'           => 'Каб укладваць файлы, трэба [[Special:Userlogin|ўвайсці ў сістэму]].',
'uploadlogpage'               => 'Журнал укладанняў',
'filename'                    => 'Назва файла',
'filedesc'                    => 'Тлумачэнне',
'fileuploadsummary'           => 'Тлумачэнне:',
'filestatus'                  => 'Статус па аўтарскіх правах',
'filesource'                  => 'Крыніца',
'ignorewarning'               => 'Не зважаць на папярэджанне і запісаць файл.',
'ignorewarnings'              => 'Ігнараваць усе папярэджанні',
'illegalfilename'             => 'У назве файла «$1» ёсць такія знакі, якія не дазваляюцца ў назвах старонак. Калі ласка, паспрабуйце загрузіць файл ізноў, але пад іншай назвай.',
'badfilename'                 => 'Назва файла зменена на &quot;$1&quot;.',
'emptyfile'                   => 'Здаецца, што файл, укладзены вамі, пусты. Магчыма, здарылася памылка ў назве файла? Праверце, ці вы сапраўды хацелі ўкласці менавіта гэты файл.',
'fileexists'                  => 'Ужо існуе файл з такою назвай, праверце $1, калі не ўпэўнены, што жадаеце мяняць яго змесціва.',
'fileexists-thumbnail-yes'    => 'Файл падобны на выяву скарочанага памеру <i>(драбніца)</i>. Праверце файл <strong><tt>$1</tt></strong>.<br />
Калі правераны файл мае змест і памеры, аднолькавыя з гэтым, то дадатковае ўкладанне драбніцы непатрэбнае.',
'file-thumbnail-no'           => 'Назва файла пачынаецца з <strong><tt>$1</tt></strong>. Так можа называцца выява скарочанага памеру <i>(драбніца)</i>.
Калі гэтая выява сапраўды запісаная ў найлепшым, якое ў вас ёсць, разрозненні, то ўкладайце яе, а іначай лепей памяняць назву файла.',
'fileexists-forbidden'        => 'Ужо існуе файл з такою назвай; калі ласка, паўтарыце працэдуру ўкладання файла, але з іншай назвай. [[Image:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => 'У агульным сховішчы ўжо існуе файл з такою назвай; калі ласка, паўтарыце працэдуру ўкладання файла, але з іншай назвай. [[Image:$1|thumb|center|$1]]',
'savefile'                    => 'Запісаць файл',
'uploadedimage'               => 'укладзена "[[$1]]"',
'uploadvirus'                 => 'Файл утрымлівае вірус! Падрабязнасці: $1',
'destfilename'                => 'Назва мэтавага файла',
'watchthisupload'             => 'Назіраць за гэтай старонкай',
'filewasdeleted'              => 'Файл з такою назвай быў раней укладзены сюды, а потым сцёрты. Варта паглядзець у $1 перад тым, як укладаць яго нанова.',

'license'            => 'Ліцэнзіяванне',
'upload_source_url'  => ' (сапраўдны, публічна дасягальны URL)',
'upload_source_file' => ' (файл на вашай машыне)',

# Image list
'imagelist'                 => 'Усе файлы',
'imagelisttext'             => "Ніжэй даецца спіс з '''$1''' {{plural:$1|файла|файлаў}} у парадку $2.",
'getimagelist'              => 'атрымліваем спіс файлаў',
'ilsubmit'                  => 'Знайсці',
'showlast'                  => 'Паказ. апошнія $1 файлаў у парадку $2.',
'byname'                    => 'п. назваў',
'bydate'                    => 'п. датаў',
'bysize'                    => "п. аб'ёмаў",
'imgdelete'                 => 'сцерці',
'filehist'                  => 'Гісторыя файла',
'filehist-help'             => 'Націснуць на даце з часам, каб паказаць файл, якім ён тады быў.',
'filehist-current'          => 'актуальн.',
'filehist-datetime'         => 'Дата і час',
'filehist-user'             => 'Удзельнік',
'filehist-dimensions'       => 'Памеры',
'filehist-filesize'         => "Аб'ём файла",
'filehist-comment'          => 'Тлумачэнне',
'imagelinks'                => 'Спасылкі',
'linkstoimage'              => 'Старонкі, якія спасылаюцца на файл:',
'nolinkstoimage'            => 'Няма старонак, якія б спасылаліся на файл.',
'sharedupload'              => 'Гэты файл паданы для супольнага карыстання, і можа быць выкарыстаны ў іншых праектах.',
'noimage'                   => 'Няма файла з такой назвай, вы можаце $1.',
'noimage-linktext'          => 'укласці',
'uploadnewversion-linktext' => 'Укласці новую версію гэтага файла',
'imagelist_date'            => 'Дата',
'imagelist_name'            => 'Назва',
'imagelist_user'            => 'Удзельнік',
'imagelist_size'            => 'Памер у байтах',
'imagelist_description'     => 'Апісанне',
'imagelist_search_for'      => 'Знайсці назву выявы:',

# MIME search
'mimesearch' => 'Пошук паводле зместу файла',
'mimetype'   => 'Тып MIME:',
'download'   => 'узяць сабе',

# Unwatched pages
'unwatchedpages' => 'Старонкі, якія не назіраюцца',

# List redirects
'listredirects' => 'Усе перасылкі',

# Unused templates
'unusedtemplates' => 'Шаблоны, якія не выкарыстаны',

# Random page
'randompage' => 'Выпадковая старонка',

# Random redirect
'randomredirect' => 'Выпадковая перасылка',

# Statistics
'statistics'             => 'Статыстыка',
'sitestats'              => '{{SITENAME}}: статыстычныя звесткі',
'userstats'              => 'Статыстыка ўдзельніка',
'userstatstext'          => "Ёсць '''$1''' зарэгістраваных удзельнікаў, з якіх '''$2''' ('''$4%''') з'яўляюцца $5.",
'statistics-mostpopular' => 'Самыя папулярныя старонкі',

'disambiguations'     => 'Неадназначнасці',
'disambiguationspage' => '[[Шаблон:Неадназначнасць]]',

'doubleredirects'     => 'Падвойныя перасылкі',
'doubleredirectstext' => 'Кожны радок утрымлівае спасылкі на першую і другую перасылкі, а таксама мэту другой перасылкі, якая звычайна і ёсць &quot;сапраўдная&quot; мэтавая старонка, на якую павінна была паказваць першая перасылка.',

'brokenredirects'     => 'Паламаныя перасылкі',
'brokenredirectstext' => 'Наступныя перасылкі спасылаюцца на неіснуючыя старонкі:',

'withoutinterwiki'        => 'Старонкі без адпаведных іншамоўных',
'withoutinterwiki-header' => 'Спіс артыкулаў без спасылак на іншамоўныя версіі:',

'fewestrevisions' => 'Артыкулы з найменшай колькасцю версій',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|байт|байта|байтаў}}',
'ncategories'             => '$1 {{PLURAL:$1|катэгорыя|катэгорыі|катэгорый}}',
'nlinks'                  => '$1 {{PLURAL:$1|спасылка|спасылак}}',
'nmembers'                => '$1 {{PLURAL:$1|складнік|складнікі|складнікаў}}',
'nrevisions'              => '$1 {{PLURAL:$1|версія|версіі|версій}}',
'nviews'                  => '$1 {{PLURAL:$1|паказ|паказаў}}',
'lonelypages'             => 'Старонкі без спасылак на іх',
'uncategorizedpages'      => 'Старонкі без катэгорый',
'uncategorizedcategories' => 'Катэгорыі без катэгорый',
'uncategorizedimages'     => 'Выявы без катэгорый',
'uncategorizedtemplates'  => 'Шаблоны без катэгорый',
'unusedcategories'        => 'Катэгорыі без складнікаў',
'unusedimages'            => 'Файлы, якія не выкарыстаны',
'popularpages'            => 'Папулярныя старонкі',
'wantedcategories'        => 'Вельмі патрэбныя катэгорыі',
'wantedpages'             => 'Вельмі патрэбныя старонкі',
'mostlinked'              => 'Старонкі, на якія найчасцей спасылаюцца',
'mostlinkedcategories'    => 'Катэгорыі з найбольшай колькасцю складнікаў',
'mostlinkedtemplates'     => 'Шаблоны ў частым выкарыстанні',
'mostcategories'          => 'Артыкулы ў найбольшай кольк. катэгорый',
'mostimages'              => 'Выявы ў частым выкарыстанні',
'mostrevisions'           => 'Артыкулы з найбольшай колькасцю версій',
'allpages'                => 'Усе старонкі',
'prefixindex'             => 'Пошук старонак па пачатку назвы',
'shortpages'              => "Старонкі малога аб'ёму",
'longpages'               => "Старонкі вялікага аб'ёму",
'deadendpages'            => 'Старонкі без спасылак',
'deadendpagestext'        => 'Спіс старонак без спасылак на тутэйшыя артыкулы.',
'protectedpages'          => 'Старонкі пад аховай',
'listusers'               => 'Усе ўдзельнікі',
'specialpages'            => 'Адмысловыя старонкі',
'spheading'               => 'Адмысловыя старонкі агульнага карыстання',
'restrictedpheading'      => 'Адмысловыя старонкі абмежаванага карыстання',
'rclsub'                  => '(да старонак, на якія спасылаецца "$1")',
'newpages'                => 'Новыя старонкі',
'newpages-username'       => 'Імя ўдзельніка:',
'ancientpages'            => 'Найстарэйшыя старонкі',
'move'                    => 'Перанесці',
'movethispage'            => 'Перанесці гэтую старонку',

# Book sources
'booksources'    => 'Кнігі',
'booksources-go' => 'Пошук',

'categoriespagetext' => 'Спіс катэгорый гэтай вікі-пляцоўкі:',
'data'               => 'Дадзеныя',
'userrights'         => 'Распараджэнне правамі ўдзельніка',
'groups'             => 'Групы ўдзельніка',
'alphaindexline'     => '$1 да $2',
'version'            => 'Версія',

# Special:Log
'specialloguserlabel'  => 'Удзельнік:',
'speciallogtitlelabel' => 'Загаловак:',
'log'                  => 'Журналы',
'all-logs-page'        => 'Усе журналы',
'alllogstext'          => 'Спалучаны паказ журналаў укладанняў файлаў, выдалення і аховы старонак, блакавання ўдзельнікаў, і іншых адміністрацыйных дзеянняў.
Выгляд паказанага можна ўдакладняць, выбіраючы тып журнала, або імя ўдзельніка, або назву старонкі.',

# Special:Allpages
'nextpage'          => 'Наступная старонка ($1)',
'prevpage'          => 'Папярэдняя старонка ($1)',
'allpagesfrom'      => 'Паказваць старонкі ад:',
'allarticles'       => 'Усе артыкулы',
'allinnamespace'    => 'Усе артыкулы (прастора назваў $1)',
'allnotinnamespace' => 'Усе старонкі (не ў прасторы назваў $1)',
'allpagesprev'      => 'Папярэдняе',
'allpagesnext'      => 'Наступнае',
'allpagessubmit'    => 'Ісці',
'allpagesprefix'    => 'Паказваць старонкі з прэфіксам:',
'allpagesbadtitle'  => 'Гэтая назва старонкі недапушчальная або ўтрымлівае між-моўны або між-вікавы прэфікс. Магчыма, у назве ёсць знак ці знакі, якія нельга ўжываць у назвах.',

# E-mail user
'emailuser'       => 'Напісаць у эл.пошту ўдзельніка',
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
'watchlist'            => 'Мой спіс назіранага',
'mywatchlist'          => 'Назіранае',
'watchlistfor'         => "(для '''$1''')",
'nowatchlist'          => 'Ваш спіс назіранага зараз пусты.',
'watchlistanontext'    => 'Каб бачыць або правіць складнікі назіранага, трэба $1.',
'watchnologintext'     => 'Каб правіць свой спіс назіранага, трэба [[Special:Userlogin|ўвайсці ў сістэму]].',
'addedwatch'           => 'Дапісана да назіранага',
'addedwatchtext'       => "Старонка &quot;[[:$1]]&quot; была дададзена да [[{{ns:Special}}:Watchlist|назіраных]] вамі.
Змяненні, якія адбудуцца з гэтай старонкай і з Размовай пра яе, будуць паказвацца там, і старонка будзе '''вылучацца шрыфтам''' у [[{{ns:Special}}:Recentchanges|спісе нядаўніх змяненняў]], каб лягчэй пазнаваць яе.

Калі вы не пажадаеце больш назіраць за гэтай старонкай, націсніце &quot;Не назіраць&quot; у бакоўцы.",
'watch'                => 'Назіраць',
'watchthispage'        => 'Назіраць за гэтай старонкай',
'unwatch'              => 'Не назіраць',
'unwatchthispage'      => 'Спыніць назіранне',
'watchnochange'        => 'Ніводзін з назіраных складнікаў не быў зменены за паказаны перыяд.',
'watchlist-details'    => 'Назіраю $1 старонак &lt;!--{{PLURAL:$1|$1 старонку|$1 старонак}}--&gt; без уліку размоўных.',
'wlheader-enotif'      => '* Працуе апавяшчанне праз эл.пошту.',
'wlheader-showupdated' => "* Старонкі, якія былі зменены пасля вашага апошняга іх наведвання, паказаны '''абрысам шрыфту'''.",
'watchmethod-recent'   => 'правяраем нядаўнія праўкі ў назіраных старонках',
'watchmethod-list'     => 'правяраем наяўнасць нядаўніх правак ў назіраных старонках',
'watchlistcontains'    => 'У вашым спісе назіранага $1 старонак.',
'wlnote'               => 'Ніжэй пададзены апошнія $1 змяненняў за апошнія &lt;b&gt;$2&lt;/b&gt; гадз.',
'wlshowlast'           => 'Паказваць апошнія $1 гадз. $2 дзён $3',
'watchlist-show-bots'  => 'паказваць праўкі робатаў',
'watchlist-hide-bots'  => 'не паказваць правак робатаў',
'watchlist-show-own'   => 'паказваць мае праўкі',
'watchlist-hide-own'   => 'не паказваць маіх правак',
'watchlist-show-minor' => 'паказваць дробныя праўкі',
'watchlist-hide-minor' => 'не паказваць дробных правак',

# Displayed when you click the "watch" button and it's in the process of watching
'watching'   => 'Дапісваецца да назіранага...',
'unwatching' => 'Спыняем назіранне...',

'enotif_newpagetext' => 'Гэта новая старонка.',
'changed'            => 'зменена',
'created'            => 'створана',

# Delete/protect/revert
'deletepage'                  => 'Сцерці старонку',
'confirm'                     => 'Пацвердзіць',
'exbeforeblank'               => "змесціва перад ачысткаю было: '$1'",
'exblank'                     => 'старонка была пустой',
'confirmdelete'               => 'Пацвердзіць сціранне',
'deletesub'                   => '(Сціраем &quot;$1&quot;)',
'historywarning'              => 'Увага: Старонка, якую вы хочаце сцерці, мае гісторыю:',
'actioncomplete'              => 'Завершана аперацыя',
'deletedtext'                 => '&quot;$1&quot; было выдалена.
Бач $2 па журнал нядаўніх выдаленняў.',
'deletedarticle'              => 'сцёрты &quot;[[$1]]&quot;',
'dellogpage'                  => 'Журнал сціранняў',
'dellogpagetext'              => 'Ніжэй паказаны спіс самых нядаўніх сціранняў.',
'deletionlog'                 => 'журнал сціранняў',
'reverted'                    => 'Адкочана да ранейшай версіі',
'deletecomment'               => 'Прычына сцірання',
'rollback'                    => 'Адкаціць праўкі',
'rollback_short'              => 'Адкат',
'rollbacklink'                => 'адкат',
'rollbackfailed'              => 'Не ўдалося адкаціць',
'cantrollback'                => 'Немагчыма адкаціць праўку; апошні аўтар гэта адзіны аўтар на гэтай старонцы.',
'alreadyrolled'               => 'Немагчыма адкаціць апошнюю праўку ў [[$1]]
аўтарства [[User:$2|$2]] ([[User talk:$2|Размова]]); за гэты час нехта іншы ўжо правіў або адкатваў старонку.

Аўтарства апошняй праўкі: [[User:$3|$3]] ([[User talk:$3|Размова]]).',
'editcomment'                 => 'Тлумачэнне праўкі: &quot;&lt;i&gt;$1&lt;/i&gt;&quot;.', # only shown if there is an edit comment
'protectlogpage'              => 'Журнал аховы',
'protectedarticle'            => 'пад аховай «[[$1]]»',
'unprotectedarticle'          => 'знятая ахова з &quot;[[$1]]&quot;',
'protectsub'                  => '(Ахова «$1»)',
'confirmprotect'              => 'Пацверджанне пачатку аховы',
'protectcomment'              => 'Прычына пастаноўкі пад ахову',
'unprotectsub'                => '(Здымаем ахову з &quot;$1&quot;)',
'protect-level-autoconfirmed' => 'Забарона для нерэгістраваных удзельнікаў',
'protect-level-sysop'         => 'Толькі для адміністратараў',
'protect-cascade'             => 'Каскад - ахоўваць таксама і ўсе тыя старонкі, які ўлучаюцца ў гэтую.',

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
'undeletedarticle'         => 'адноўлены &quot;[[$1]]&quot;',
'undeletedrevisions'       => '$1 версій адноўлены',
'undeletedrevisions-files' => '$1 версій і $2 файл(аў) адноўлены',
'undeletedfiles'           => '$1 файл(аў) адноўлены',

# Namespace form on various pages
'namespace'      => 'Прастора назваў:',
'invert'         => 'Пазначыць наадварот',
'blanknamespace' => '(Артыкулы)',

# Contributions
'contributions' => 'Уклад удзельніка',
'mycontris'     => 'Свой уклад',
'contribsub2'   => 'Для $1 ($2)',
'uctop'         => ' (верх)',
'month'         => 'Ад месяцу (і раней):',
'year'          => 'Ад году (і раней):',

'sp-contributions-newest'      => 'Найноўшыя',
'sp-contributions-oldest'      => 'Найстарэйшыя',
'sp-contributions-newer'       => 'Навейшыя $1',
'sp-contributions-older'       => 'Старэйшыя $1',
'sp-contributions-newbies'     => 'Паказваць толькі ўклады з новых рахункаў',
'sp-contributions-newbies-sub' => 'Для новых рахункаў',
'sp-contributions-blocklog'    => 'Журнал забаронаў',
'sp-contributions-search'      => 'Знайсці ўклад',
'sp-contributions-username'    => 'Адрас IP або імя ўдзельніка:',
'sp-contributions-submit'      => 'Пошук',

# What links here
'whatlinkshere'       => 'Сюды спасылаюцца',
'whatlinkshere-title' => 'Старонкі, якія спасылаюцца на $1',
'linklistsub'         => '(спіс спасылак)',
'linkshere'           => "Старонкі, якія спасылаюцца на '''[[:$1]]''':",
'nolinkshere'         => "Няма старонак, якія б спасылаліся на '''[[:$1]]'''.",
'isredirect'          => 'старонка-перасылка',
'istemplate'          => 'уключэнне',
'whatlinkshere-prev'  => '{{PLURAL:$1|папярэдняя|папярэднія $1}}',
'whatlinkshere-next'  => '{{PLURAL:$1|наступная|наступныя $1}}',
'whatlinkshere-links' => '← спасылкі',

# Block/unblock
'blockip'             => 'Заблакаваць удзельніка',
'ipaddress'           => 'Адрас IP',
'ipbreason'           => 'Прычына',
'ipbother'            => 'Іншы час',
'ipbotheroption'      => 'іншае',
'badipaddress'        => 'Недапушчальны адрас IP',
'blockipsuccesssub'   => 'Паспяховае блакаванне',
'ipblocklist'         => 'Усе заблакаваныя IP-адрасы і ўдзельнікі',
'blocklistline'       => '$1, $2 заблакаваны $3 ($4)',
'infiniteblock'       => 'бясконца',
'expiringblock'       => 'канчаецца $1',
'anononlyblock'       => 'толькі ананімы',
'createaccountblock'  => 'стварэнне рахунку заблакавана',
'blocklink'           => 'заблакаваць',
'unblocklink'         => 'адблакаваць',
'contribslink'        => 'уклад',
'autoblocker'         => "Аўтаматычны блок таму што вашым адрасам IP нядаўна карыстаўся &quot;[[User:$1|$1]]&quot;. Блакаванне $1's патлумачана так: &quot;'''$2'''&quot;",
'blocklogpage'        => 'Журнал блокаў',
'blocklogentry'       => 'пастаўлены блок на &quot;[[$1]]&quot;, з часам трывання $2',
'ipb_already_blocked' => '&quot;$1&quot; ужо знаходзіцца пад блокам',
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
'move-watch'              => 'Назіраць за старонкай',
'movepagebtn'             => 'Перанесці старонку',
'articleexists'           => 'Старонка з такой назвай ужо існуе, або
вамі выбрана недапушчальнае імя.
Выберыце іншае імя.',
'movedto'                 => 'перанесена ў',
'movetalk'                => 'Перанесці таксама старонку размовы.',
'talkpagemoved'           => 'Адпаведная старонка размовы была перанесеная таксама.',
'talkpagenotmoved'        => 'Адпаведная старонка размовы &lt;strong&gt;не была&lt;/strong&gt; перанесена.',
'1movedto2'               => '[[$1]] перанесена ў [[$2]]',
'1movedto2_redir'         => '[[$1]] перанесеная ў [[$2]] з выдаленнем перасылкі',
'movelogpage'             => 'Журнал пераносаў',
'movelogpagetext'         => 'Ніжэй падаецца спіс пераносаў старонак.',
'movereason'              => 'Тлумачэнне',
'revertmove'              => 'адкат',
'delete_and_move'         => 'Выдаліць і перанесці',
'delete_and_move_text'    => '==Патрабуецца сціранне==

Ужо існуе артыкул з мэтавай назвай &quot;[[$1]]&quot;. Дык ці жадаеце сцерці яго, каб зрабіць месца для пераносу?',
'delete_and_move_confirm' => 'Так, сцерці старонку',
'delete_and_move_reason'  => 'Сцёрта, каб зрабіць месца для пераносу',
'immobile_namespace'      => 'Мэтавая назва належыць да спецыяльнага тыпу; у гэтую прастору назваў немагчыма пераносіць старонкі.',

# Export
'export'        => 'Экспартаваць старонкі',
'exportcuronly' => 'Экспартаваць толькі актуальную версію, без поўнай гісторыі',
'export-submit' => 'Экспартаваць',

# Namespace 8 related
'allmessages'               => 'Сістэмныя паведамленні',
'allmessagesname'           => 'Назва',
'allmessagesdefault'        => 'Прадвызначаны тэкст',
'allmessagescurrent'        => 'Актуальны тэкст',
'allmessagestext'           => 'Спіс усіх сістэмных паведамленняў, наяўных у прасторы назваў MediaWiki.',
'allmessagesnotsupportedDB' => "Немагчыма паказаць '''{{ns:Special}}:{{:{{ns:mediawiki}}:Allmessages}}''', таму што не працуе '''\$wgUseDatabaseMessages'''.",
'allmessagesfilter'         => 'Фільтр назваў паведамленняў:',
'allmessagesmodified'       => 'Паказваць толькі змененыя',

# Thumbnails
'thumbnail-more'           => 'Павялічыць',
'missingimage'             => '&lt;b&gt;Прапушчаная выява&lt;/b&gt;&lt;br /&gt;&lt;i&gt;$1&lt;/i&gt;',
'filemissing'              => 'Адсутны файл',
'thumbnail_error'          => 'Памылка пры стварэнні драбніцы: $1',
'djvu_page_error'          => 'Старонка DjVu па-за інтэрвалам',
'djvu_no_xml'              => 'Не ўдалося ўзяць XML для файла DjVu',
'thumbnail_invalid_params' => 'Няправільныя параметры драбніцы',

# Special:Import
'import'                     => 'Імпартаваць старонкі',
'import-interwiki-history'   => 'Капіраваць усе гістарычныя версіі гэтай старонкі',
'import-interwiki-submit'    => 'Імпартаваць',
'import-interwiki-namespace' => 'Перанесці старонкі ў прастору назваў:',
'importfailed'               => 'Не ўдалося імпартаваць: $1',

# Import log
'importlogpage' => 'Журнал імпартаванняў',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Свая старонка',
'tooltip-pt-mytalk'               => 'Свае размовы',
'tooltip-pt-preferences'          => 'Свае настáўленні',
'tooltip-pt-watchlist'            => 'Пералік старонак, за змяненнямі ў якіх вы сочыце',
'tooltip-pt-mycontris'            => 'Пералік уласных укладаў',
'tooltip-pt-login'                => 'Уваходзіць у сістэму неабавязкова, але вас вельмі запрашаюць гэтак зрабіць.',
'tooltip-pt-logout'               => 'Выйсці з сістэмы',
'tooltip-ca-talk'                 => 'Размовы пра змест гэтай старонкі',
'tooltip-ca-edit'                 => 'Старонку можна правіць; ужывайце папярэдні паказ перад замацоўваннем.',
'tooltip-ca-addsection'           => 'Дадаць заўвагу да гэтай размовы.',
'tooltip-ca-viewsource'           => 'Гэтая старонка ахоўваецца, але можна паглядзець яе крынічны тэкст.',
'tooltip-ca-protect'              => 'Паставіць ахову на старонку',
'tooltip-ca-delete'               => 'Сцерці гэтую старонку',
'tooltip-ca-move'                 => 'Перанесці гэтую старонку пад іншую назву',
'tooltip-ca-watch'                => 'Дадаць гэтую старонку да свайго спісу назіраных старонак',
'tooltip-ca-unwatch'              => 'Выняць гэтую старонку з вашага спісу назіранага',
'tooltip-search'                  => 'Знайсці ў {{SITENAME}}',
'tooltip-n-mainpage'              => 'Адкрыць Першую старонку',
'tooltip-n-portal'                => 'Аб гэтым праекце, чым можна заняцца, дзе што шукаць',
'tooltip-n-currentevents'         => 'Атрымаць інфармацыю пра актуальныя падзеі',
'tooltip-n-recentchanges'         => 'Пералік нядаўніх змяненняў у віксе.',
'tooltip-n-randompage'            => 'Паказаць выпадковую старонку',
'tooltip-n-help'                  => 'Дзе можна атрымаць тлумачэнні.',
'tooltip-n-sitesupport'           => 'Падтрымайце нас',
'tooltip-t-whatlinkshere'         => 'Спіс вікі-старонак, што спасылаюцца сюды',
'tooltip-feed-rss'                => 'RSS-струмень гэтай старонкі',
'tooltip-t-contributions'         => 'Паказаць пералік укладаў гэтага ўдзельніка',
'tooltip-t-emailuser'             => 'Адаслаць удзельніку ліст эл.пошты',
'tooltip-t-upload'                => 'Укласці выяву або мультымедыйны файл',
'tooltip-t-specialpages'          => 'Пералік усіх адмысловых старонак',
'tooltip-ca-nstab-user'           => 'Паказаць уласную старонку ўдзельніка',
'tooltip-ca-nstab-project'        => 'Паказаць старонку праекта',
'tooltip-ca-nstab-image'          => 'Паказаць старонку выявы (файла)',
'tooltip-ca-nstab-template'       => 'Паказаць шаблон',
'tooltip-ca-nstab-help'           => 'Паказаць старонку даведкі',
'tooltip-ca-nstab-category'       => 'Паказаць старонку катэгорыі',
'tooltip-minoredit'               => 'Падаць гэтую праўку як дробную',
'tooltip-save'                    => 'Замацаваць свае змяненні',
'tooltip-preview'                 => 'Паказаць, якім будзе вынік — ужывайце перад замацоўваннем!',
'tooltip-diff'                    => 'Паказаць, што вы мяняеце ў тэксце.',
'tooltip-compareselectedversions' => 'Паказаць розніцу паміж дзвюмя азначанымі версіямі гэтай старонкі.',
'tooltip-watch'                   => 'Дапісаць старонку да спісу назіранага',

# Attribution
'anonymous'        => 'Ананімныя ўдзельнікі і ўдзельніцы {{GRAMMAR:родны|{{SITENAME}}}}',
'siteuser'         => 'удзельнік $1 з {{SITENAME}}',
'lastmodifiedatby' => 'Апошняе змяненне старонкі адбылося $2, $1 аўтарства $3.', # $1 date, $2 time, $3 user
'and'              => 'і',
'othercontribs'    => 'На аснове працы $1.',
'others'           => 'іншае',
'siteusers'        => 'удзельнік або ўдзельнікі $1 з {{SITENAME}}',
'creditspage'      => 'Аўтарства старонкі',

# Spam protection
'subcategorycount'       => 'У гэтай катэгорыі $1 {{PLURAL:$1|падкатэгорыя|падкатэгорыі|падкатэгорый}}.',
'categoryarticlecount'   => 'У гэтай катэгорыі $1 {{PLURAL:$1|артыкул|артыкулы|артыкулаў}}.',
'category-media-count'   => 'У гэтай катэгорыі $1 {{PLURAL:$1|файл|файлы|файлаў}}.',
'listingcontinuesabbrev' => 'працяг',

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
'deletedrevision'       => 'Сцёрта старая версія $1',
'filedeleteerror-short' => 'Памылка пры сціранні файла: $1',

# Browsing diffs
'previousdiff' => '← Да папярэдн. праўкі',
'nextdiff'     => 'Да наступн. праўкі →',

# Media information
'imagemaxsize'         => 'Абмежаваныя памеры выяваў на адпаведных тлумачальных старонках:',
'thumbsize'            => 'Памеры драбніцы:',
'widthheightpage'      => '$1×$2, $3 старонак',
'file-info'            => "(аб'ём файла: $1, тып MIME: $2)",
'file-info-size'       => "($1 × $2 кропак, аб'ём файла: $3, тып MIME: $4)",
'file-nohires'         => '<small>Без версіі ў лепшым разрозненні.</small>',
'svg-long-desc'        => "(файл SVG, намінальна $1 × $2 кропак, аб'ём файла: $3)",
'show-big-image'       => 'Найлепшае разрозненне',
'show-big-image-thumb' => '<small>Памеры гэтага перадпаказу: $1 × $2 кропак</small>',

# Special:Newimages
'newimages'    => 'Новыя файлы',
'showhidebots' => '($1 робатаў)',
'noimages'     => 'Тут нічога няма.',

# Bad image list
'bad_image_list' => 'Афармленне гэтага такое:

Улічваюцца толькі складнікі спісаў (радкі, пачатыя з зорачкі *). Першая спасылка ў радку павінна быць спасылкай на кепскую выяву.
Усе наступныя спасылкі ў тым самым радку лічацца выняткамі, г.зн. старонкамі, у у якія дазволена ўстаўляць гэтую выяву.',

# Metadata
'metadata'          => 'Мета-дадзеныя',
'metadata-help'     => 'У файле ёсць дадатковыя звесткі, магчыма, што ад лічбавай фотакамеры ці ад сканера, з дапамогай якіх быў створаны файл. Калі арыгінальны змест файла быў зменены, то некаторыя з гэтых звестак могуць быць ужо неактуальнымі ў дачыненні да змененага файла.',
'metadata-expand'   => 'Паказваць падрабязнасці',
'metadata-collapse' => 'Не паказваць падрабязнасці',
'metadata-fields'   => 'Палі мета-даных EXIF, пералічаныя тут, будуць паказвацца на старонцы выявы і тады, калі табліца мета-даных згорнута. Іншыя палі не паказваюцца адразу.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* focallength',

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
'edit-externally'      => 'Правіць файл у вонкавай праграме',
'edit-externally-help' => 'Глядзіце [http://meta.wikimedia.org/wiki/Help:External_editors інструкцыі па настаўлянню (англ.)] па больш падрабязнасцяў.',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'усе',
'imagelistall'     => 'усе',
'watchlistall2'    => 'усе',
'namespacesall'    => 'усе',
'monthsall'        => 'усе',

# E-mail address confirmation
'confirmemail'           => 'Пацвердзіць адрас эл.пошты',
'confirmemail_noemail'   => 'У [[Special:Preferences|вашых настаўленнях]] няма дапушчальнага адрасу эл.пошты.',
'confirmemail_send'      => 'Адаслаць код пацверджання',
'confirmemail_sent'      => 'Адасланы ліст эл.пошты з пацверджаннем.',
'confirmemail_invalid'   => 'Няправільны код пацверджання. Магчыма, неактуальны код.',
'confirmemail_needlogin' => 'Вам трэба зрабіць $1 каб пацвердзіць свой адрас эл.пошты.',
'confirmemail_loggedin'  => 'Зараз ваш адрас эл.пошты стаўся пацверджаным.',
'confirmemail_error'     => 'Неакрэсленая памылка пры запісванні пацверджання.',

# Delete conflict
'deletedwhileediting' => 'Увага: гэтая старонка была сцёрта пасля таго, як вы пачалі з ёй працаваць!',
'confirmrecreate'     => "Удзельнік [[{{ns:User}}:$1|$1]] ([[{{ns:User talk}}:$1|размова]]) сцёр гэты артыкул пасля таго, як вы пачалі працу з ім, падаўшы прычыну:
: ''$2''
Пацвердзіце свой намер аднавіць гэты артыкул.",

'unit-pixel' => 'крпк',

# HTML dump
'redirectingto' => 'Перасылаемся да [[$1]]...',

# action=purge
'confirm_purge' => 'Ці ачысціць кэш для гэтай старонкі?

$1',

# AJAX search
'searchcontaining' => "Знайсці артыкулы, у якіх ёсць ''$1''.",
'searchnamed'      => "Знайсці артыкулы з назвай ''$1''.",
'articletitles'    => "Артыкулы, чые назвы пачынаюцца з ''$1''",
'hideresults'      => 'Не паказваць вынікаў',

# Multipage image navigation
'imgmultipageprev' => '← папярэдняя старонка',
'imgmultipagenext' => 'наступная старонка →',
'imgmultigo'       => 'Ісці!',
'imgmultigotopre'  => 'Ісці на старонку',

# Table pager
'table_pager_next'         => 'Наступная старонка',
'table_pager_prev'         => 'Папярэдняя старонка',
'table_pager_first'        => 'Першая старонка',
'table_pager_last'         => 'Апошняя старонка',
'table_pager_limit'        => 'Па $1 складнікаў на старонцы',
'table_pager_limit_submit' => 'Ісці',
'table_pager_empty'        => 'Без вынікаў',

# Auto-summaries
'autoredircomment' => 'Перасылае да [[$1]]',
'autosumm-new'     => 'Новая старонка: $1',

# Watchlist editing tools
'watchlisttools-view' => 'Паказаць, што мянялася',
'watchlisttools-edit' => 'Паказаць і правіць назіранае',
'watchlisttools-raw'  => 'Правіць нефарматаванае назіранае',

);
