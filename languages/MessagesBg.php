<?php
/** Bulgarian (Български)
 *
 * @package MediaWiki
 * @subpackage Language
 */
$namespaceNames = array(
	NS_MEDIA            => 'Медия',
	NS_SPECIAL          => 'Специални',
	NS_MAIN             => '',
	NS_TALK             => 'Беседа',
	NS_USER             => 'Потребител',
	NS_USER_TALK        => 'Потребител_беседа',
	# NS_PROJECT set by $wgMetaNamespace
	NS_PROJECT_TALK     => '$1_беседа',
	NS_IMAGE            => 'Картинка',
	NS_IMAGE_TALK       => 'Картинка_беседа',
	NS_MEDIAWIKI        => 'МедияУики',
	NS_MEDIAWIKI_TALK   => 'МедияУики_беседа',
	NS_TEMPLATE         => 'Шаблон',
	NS_TEMPLATE_TALK    => 'Шаблон_беседа',
	NS_HELP             => 'Помощ',
	NS_HELP_TALK        => 'Помощ_беседа',
	NS_CATEGORY         => 'Категория',
	NS_CATEGORY_TALK    => 'Категория_беседа'
);

$quickbarSettings = array(
	'Без меню', 'Неподвижно вляво', 'Неподвижно вдясно', 'Плаващо вляво', 'Плаващо вдясно'
);

$skinNames = array(
	'standard' => 'Класика',
	'nostalgia' => 'Носталгия',
	'cologneblue' => 'Кьолнско синьо',
	'smarty' => 'Падингтън',
	'montparnasse' => 'Монпарнас',
	'davinci' => 'ДаВинчи',
	'mono' => 'Моно',
	'monobook' => 'Монобук',
	'myskin' => 'Мой облик',
);

$datePreferences = false;

$bookstoreList = array(
	'books.bg'       => 'http://www.books.bg/ISBN/$1',
);

$magicWords = array(
#   ID                                 CASE  SYNONYMS
	'redirect'               => array( 0, '#redirect', '#пренасочване', '#виж' ),
	'notoc'                  => array( 0, '__NOTOC__', '__БЕЗСЪДЪРЖАНИЕ__' ),
	'forcetoc'               => array( 0, '__FORCETOC__', '__СЪССЪДЪРЖАНИЕ__' ),
	'toc'                    => array( 0, '__TOC__', '__СЪДЪРЖАНИЕ__'      ),
	'noeditsection'          => array( 0, '__NOEDITSECTION__', '__БЕЗ_РЕДАКТИРАНЕ_НА_РАЗДЕЛИ__' ),
	'start'                  => array( 0, '__START__', '__НАЧАЛО__'         ),
	'currentmonth'           => array( 1, 'CURRENTMONTH', 'ТЕКУЩМЕСЕЦ'      ),
	'currentmonthname'       => array( 1, 'CURRENTMONTHNAME', 'ТЕКУЩМЕСЕЦИМЕ' ),
	'currentmonthnamegen'    => array( 1, 'CURRENTMONTHNAMEGEN', 'ТЕКУЩМЕСЕЦИМЕРОД' ),
	'currentmonthabbrev'     => array( 1, 'CURRENTMONTHABBREV', 'ТЕКУЩМЕСЕЦСЪКР'    ),
	'currentday'             => array( 1, 'CURRENTDAY', 'ТЕКУЩДЕН'            ),
	'currentdayname'         => array( 1, 'CURRENTDAYNAME', 'ТЕКУЩДЕНИМЕ'     ),
	'currentyear'            => array( 1, 'CURRENTYEAR', 'ТЕКУЩАГОДИНА'       ),
	'currenttime'            => array( 1, 'CURRENTTIME', 'ТЕКУЩОВРЕМЕ'        ),
	'numberofarticles'       => array( 1, 'NUMBEROFARTICLES', 'БРОЙСТАТИИ'    ),
	'numberoffiles'          => array( 1, 'NUMBEROFFILES', 'БРОЙФАЙЛОВЕ'      ),
	'pagename'               => array( 1, 'PAGENAME', 'СТРАНИЦА'              ),
	'pagenamee'              => array( 1, 'PAGENAMEE', 'СТРАНИЦАИ'            ),
	'namespace'              => array( 1, 'NAMESPACE', 'ИМЕННОПРОСТРАНСТВО'   ),
	'subst'                  => array( 0, 'SUBST:', 'ЗАМЕСТ:'            ),
	'msgnw'                  => array( 0, 'MSGNW:', 'СЪОБЩNW:'           ),
	'end'                    => array( 0, '__END__', '__КРАЙ__'            ),
	'img_thumbnail'          => array( 1, 'thumbnail', 'thumb', 'мини'     ),
	'img_manualthumb'        => array( 1, 'thumbnail=$1', 'thumb=$1', 'мини=$1'),
	'img_right'              => array( 1, 'right', 'вдясно', 'дясно', 'д'  ),
	'img_left'               => array( 1, 'left', 'вляво', 'ляво', 'л'     ),
	'img_none'               => array( 1, 'none', 'н'                  ),
	'img_width'              => array( 1, '$1px', '$1пкс' , '$1п'         ),
	'img_center'             => array( 1, 'center', 'centre', 'център', 'центр', 'ц' ),
	'img_framed'             => array( 1, 'framed', 'enframed', 'frame', 'рамка', 'врамка' ),
	'int'                    => array( 0, 'INT:'                   ),
	'sitename'               => array( 1, 'SITENAME', 'ИМЕНАСАЙТА'       ),
	'ns'                     => array( 0, 'NS:', 'ИП:'                    ),
	'localurl'               => array( 0, 'LOCALURL:', 'ЛОКАЛЕНАДРЕС:'    ),
	'localurle'              => array( 0, 'LOCALURLE:', 'ЛОКАЛЕНАДРЕСИ:'  ),
	'server'                 => array( 0, 'SERVER', 'СЪРВЪР'       ),
	'servername'             => array( 0, 'SERVERNAME', 'ИМЕНАСЪРВЪРА'    ),
	'scriptpath'             => array( 0, 'SCRIPTPATH', 'ПЪТДОСКРИПТА'    ),
	'grammar'                => array( 0, 'GRAMMAR:', 'ГРАМАТИКА:' ),
	'notitleconvert'         => array( 0, '__NOTITLECONVERT__', '__NOTC__'),
	'nocontentconvert'       => array( 0, '__NOCONTENTCONVERT__', '__NOCC__'),
	'currentweek'            => array( 1, 'CURRENTWEEK', 'ТЕКУЩАСЕДМИЦА'),
	'currentdow'             => array( 1, 'CURRENTDOW'             ),
	'revisionid'             => array( 1, 'REVISIONID'             ),
);

$separatorTransformTable = array(',' => "\xc2\xa0", '.' => ',' );

$messages = array(

# User toggles
'tog-underline'      => 'Подчертаване на препратките',
'tog-highlightbroken' => 'Показване на невалидните препратки <a href="#" class="new">така</a> (алтернативно: така<a href="#" class="internal">?</a>)',
'tog-justify'        => 'Двустранно подравняване на абзаците',
'tog-hideminor'      => 'Скриване на малки редакции в последните промени',
'tog-usenewrc'       => 'Подобряване на последните промени (Javascript)',
'tog-numberheadings' => 'Номериране на заглавията',
'tog-showtoolbar'    => 'Помощна лента за редактиране (Javascript)',
'tog-editondblclick' => 'Редактиране при двойно щракване (Javascript)',
'tog-editsection'    =>'Възможност за редактиране на раздел чрез препратка [редактиране]',
'tog-editsectiononrightclick' => 'Възможност за редактиране на раздел при щракване с десния бутон върху заглавие на раздел (Javascript)',
'tog-showtoc'        =>'Показване на съдържание (за страници с повече от три раздела)',
'tog-rememberpassword' => 'Запомняне между сесиите',
'tog-editwidth'      => 'Максимална ширина на кутията за редактиране',
'tog-watchdefault'   => 'Добавяне на редактираните страници към списъка за наблюдение',
'tog-minordefault'   => 'Отбелязване на всички промени като малки по подразбиране',
'tog-previewontop'   => 'Показване на предварителния преглед преди текстовата кутия, а не след нея',
'tog-previewonfirst' => 'Показване на предварителен преглед при първа редакция',
'tog-nocache'        => 'Без складиране на страниците',
'tog-fancysig' => 'Без превръщане на подписа в препратка към потребителската страница',
'tog-externaleditor' => 'Използване на външен редактор по подразбиране',
'tog-externaldiff' => 'Използване на външна програма за разлики по подразбиране',

'underline-always'  => 'Винаги',
'underline-never'   => 'Никога',
'underline-default' => 'Според настройките на браузъра',
'skinpreview' => '(Предварителен преглед)',

# Dates
'sunday' => 'неделя',
'monday' => 'понеделник',
'tuesday' => 'вторник',
'wednesday' => 'сряда',
'thursday' => 'четвъртък',
'friday' => 'петък',
'saturday' => 'събота',
'january' => 'януари',
'february' => 'февруари',
'march' => 'март',
'april' => 'април',
'may_long' => 'май',
'june' => 'юни',
'july' => 'юли',
'august' => 'август',
'september' => 'септември',
'october' => 'октомври',
'november' => 'ноември',
'december' => 'декември',
'jan' => 'яну',
'feb' => 'фев',
'mar' => 'мар',
'apr' => 'апр',
'may' => 'май',
'jun' => 'юни',
'jul' => 'юли',
'aug' => 'авг',
'sep' => 'сеп',
'oct' => 'окт',
'nov' => 'ное',
'dec' => 'дек',

# Bits of text used by many pages:
#
'categories' => 'Категории',
'category' => 'категория',
'category_header' => 'Страници в категория „$1“',
'subcategories' => 'Подкатегории',
'linktrail'   => '/^([a-zабвгдежзийклмнопрстуфхцчшщъыьэюя]+)(.*)$/sDu',
'linkprefix'  => '/^(.*?)([a-zA-Z\x80-\xff]+)$/sD',
'mainpage'    => 'Начална страница',
'mainpagetext'  => 'Уики-системата беше успешно инсталирана.',
'mainpagedocfooter' => 'Моля, разгледайте [http://meta.wikimedia.org/wiki/MediaWiki_i18n документацията] и [http://meta.wikimedia.org/wiki/MediaWiki_User%27s_Guide ръководството] за подробна информация относно МедияУики.

Актуална версия на българския езиков файл можете да откриете на [http://meta.wikimedia.org/wiki/LanguageBg.php Мета].',
'portal'     => 'Портал за общността',
'portal-url' => 'Project:Портал',
'about'      => 'За {{SITENAME}}',
'aboutsite' => 'За {{SITENAME}}',
'aboutpage'    => 'Project:За {{SITENAME}}',
'article'   => 'Страница',
'help'      => 'Помощ',
'helppage'    => 'Help:Съдържание',
'bugreports'  => 'Съобщения за грешки',
'bugreportspage' => 'Project:Съобщения за грешки',
'sitesupport'   => 'Дарения',
'sitesupport-url' => 'Project:Подкрепа',
'faq'      => 'ЧЗВ',
'faqpage'    => 'Project:ЧЗВ',
'edithelp'    => 'Помощ при редактиране',
'newwindow'   => '(отваря се в нов прозорец)',
'edithelppage'  => 'Help:Как_се_редактират_страници',
'cancel'    => 'Отказ',
'qbfind'    => 'Търсене',
'qbbrowse'    => 'Избор',
'qbedit'    => 'Редактиране',
'qbpageoptions' => 'Настройки за страницата',
'qbpageinfo'  => 'Информация за страницата',
'qbmyoptions'  => 'Моите настройки',
'qbspecialpages' => 'Специални страници',
'moredotdotdot' => 'Още...',
'mypage'    => 'Моята страница',
'mytalk'    => 'Моята беседа',
'anontalk'  => 'Беседа за адреса',
'navigation' => 'Навигация',

# Metadata in edit box
'metadata' => '<b>Метаданни</b> (<a href="$1">разяснение</a>)',

'currentevents' => 'Текущи събития',
'currentevents-url' => 'Текущи събития',
'disclaimers' => 'Условия за ползване',
'disclaimerpage' => 'Project:Условия за ползване',
'errorpagetitle' => 'Грешка',
'returnto'    => 'Обратно към $1.',
'tagline'  => 'от {{SITENAME}}',
'whatlinkshere'  => 'Какво сочи насам',
'help'      => 'Помощ',
'search'    => 'Търсене',
'searchbutton'    => 'Търсене',
'go'    => 'Отваряне',
'history'    => 'История',
'history_short' => 'История',
'updatedmarker' => 'има промяна (от последното ми влизане)',
'info_short'    => 'Информация',
'printableversion' => 'Версия за печат',
'permalink'     => 'Постоянна препратка',
'print' => 'Печат',
'edit' => 'Редактиране',
'editthispage'  => 'Редактиране',
'delete' => 'Изтриване',
'deletethispage' => 'Изтриване',
'undelete_short' => 'Възстановяване на $1 редакции',
'protect' => 'Защита',
'protectthispage' => 'Защита',
'unprotect' => 'Сваляне на защитата',
'unprotectthispage' => 'Сваляне на защитата',
'newpage' => 'Нова страница',
'talkpage'    => 'Дискусионна страница',
'specialpage' => 'Специална страница',
'personaltools' => 'Лични инструменти',
'postcomment'   => 'Оставяне на съобщение',
'articlepage'  => 'Преглед на страница',
'talk' => 'Беседа',
'views' => 'Прегледи',
'toolbox' => 'Инструменти',
'userpage' => 'Потребителска страница',
'projectpage' => 'Основна страница',
'imagepage' =>   'Преглед на файл',
'viewtalkpage' => 'Преглед на беседа',
'otherlanguages' => 'На други езици',
'redirectedfrom' => '(пренасочване от $1)',
'lastmodified'  => 'Последна промяна на страницата: $1.',
'viewcount'    => 'Страницата е била преглеждана $1 пъти.',
'copyright'     => 'Съдържанието е достъпно при условията на $1.',
'protectedpage' => 'Защитена страница',
'administrators' => 'Project:Администратори',
'sysoptitle'  => 'Изискване на администраторски права',
'sysoptext'    => 'Желаното действие може да се изпълни само от администратори.
Вижте $1.',
'developertitle' => 'Изискват се права на разработчик',
'developertext'  => 'Желаното действие може да се изпълни само от разработчици.
Вижте $1.',
'badaccess'     => 'Грешка при достъп', # Permission error
'badaccesstext' => 'Желаното действие може да се изпълнява само от потребители с права на „$2“.
Вижте $1.',
'versionrequired' => 'Изисква се версия $1 на МедияУики',
'versionrequiredtext' => 'За да използвате тази страница, е необходима версия $1 на МедияУики. Вижте [[Special:Version]].',
'nbytes'    => '$1 байта',
'ok'      => 'Добре',
'retrievedfrom' => 'Взето от „$1“.',
'newmessageslink' => 'нови съобщения',
'editsection' => 'редактиране',
'editold' => 'редактиране',
'toc' => 'Съдържание',
'showtoc' => 'показване',
'hidetoc' => 'скриване',
'thisisdeleted' => 'Преглед или възстановяване на $1?',
'viewdeleted' => 'Преглед на $1?',
'restorelink' => '$1 изтрити редакции',
'feedlinks' => 'Feed:',

# Short words for each namespace, by default used in the 'article' tab in monobook
'nstab-main' => 'Страница',
'nstab-user' => 'Потребител',
'nstab-media' => 'Медия',
'nstab-special' => 'Специална страница',
'nstab-project' => 'Проект',
'nstab-image' => 'Файл',
'nstab-mediawiki' => 'Съобщение',
'nstab-template' => 'Шаблон',
'nstab-help' => 'Помощ',
'nstab-category' => 'Категория',

# Main script and global functions
#
'nosuchaction'  => 'Няма такова действие',
'nosuchactiontext' => 'Действието, указано от мрежовия адрес, не се разпознава от системата.',
'nosuchspecialpage' => 'Няма такава специална страница',
'nospecialpagetext' => 'Отправихте заявка за невалидна [[Special:Specialpages|специална страница]].',

# General errors
#
'error'      => 'Грешка',
'databaseerror' => 'Грешка при работа с базата от данни',
'dberrortext'  => 'Възникна синтактична грешка при заявка към базата от данни.
Последната заявка към базата от данни беше:
<blockquote><tt>$1</tt></blockquote>
при функцията „<tt>$2</tt>“.
MySQL дава грешка „<tt>$3: $4</tt>“.',
'dberrortextcl' => 'Възникна синтактична грешка при заявка към базата от данни.
Последната заявка беше:
„$1“
при функцията „$2“.
MySQL дава грешка „$3: $4“.',
'noconnect'    => '<p>В момента има технически трудности и не може да се осъществи връзка с базата от данни.</p>
<p>$1</p>
<p>Моля, опитайте отново по-късно. Извиняваме се за неудобството.</p>',
'nodb'      => 'Неуспех при избирането на база от данни $1',
'cachederror'  => 'Показано е складирано копие на желаната страница, което евентуално може да е остаряло.',
'laggedslavemode'   => 'Внимание: Страницата може да не съдържа последните обновявания.',
'readonly'    => 'Базата от данни е затворена за промени',
'enterlockreason' => 'Посочете причина за затварянето, като дадете и приблизителна оценка кога базата от данни ще бъде отново отворена',
'readonlytext'  => 'Базата от данни е временно затворена за промени – вероятно за рутинна поддръжка, след която ще бъде отново на разположение.
Администраторът, който я е затворил, дава следното обяснение:
$1',
'missingarticle' => 'Текстът на страницата „$1“ не беше намерен в базата от данни.

Това обикновено е причинено от последване на остаряла разлика или препратка от историята към изтрита страница.

Ако не това е причината, е възможно да сте открили грешка в системата.
Моля, съобщете за това на администратор, като включите и името на страницата.',
'readonly_lag' => 'Базата от данни беше автоматично заключена, докато подчинените сървъри успеят да се съгласуват с основния сървър.',
'internalerror' => 'Вътрешна грешка',
'filecopyerror' => 'Файлът „$1“ не можа да бъде копиран като „$2“.',
'filerenameerror' => 'Файлът „$1“ не можа да бъде преименуван на „$2“.',
'filedeleteerror' => 'Файлът „$1“ не можа да бъде изтрит.',
'filenotfound'  => 'Файлът „$1“ не беше намерен.',
'unexpected'  => 'Неочаквана стойност: „$1“=„$2“.',
'formerror'    => 'Възникна грешка при изпращане на формуляра',
'badarticleerror' => 'Действието не може да се изпълни върху страницата.',
'cannotdelete'  => 'Указаната страница или файл не можа да бъде изтрит(а). Възможно е вече да е изтрит(а) от някой друг.',
'badtitle'    => 'Невалидно заглавие',
'badtitletext'  => 'Желаното заглавие на страница е невалидно, празно или неправилна препратка към друго уики.',
'perfdisabled' => 'Съжаляваме! Това свойство е временно изключено,
защото забавя базата от данни дотам, че никой не може да използва уикито.',
'perfdisabledsub' => 'Съхранен екземпляр от $1:',
'perfcached' => 'Следните данни са извлечени от склада и затова може да не отговарят на текущото състояние:',
'wrong_wfQuery_params' => 'Невалидни аргументи за wfQuery()<br />
Функция: $1<br />
Заявка: $2',
'viewsource' => 'Защитена страница',
'protectedtext' => 'Страницата е затворена за промени. Съществуват няколко причини това да е така, моля, вижте [[Project:Защитена_страница]].

Можете да прегледате и копирате изходния код на страницата:',
'sqlhidden' => '(Заявка на SQL — скрита)',

# Login and logout pages
#
'logouttitle'  => 'Излизане на потребител',
'logouttext'  => 'Излязохте от системата.

Можете да продължите да използвате {{SITENAME}} анонимно или да влезете отново като друг потребител. Обърнете внимание, че някои страници все още ще се показват така, сякаш сте влезли, докато не изтриете кеш-паметта на браузъра.',

'welcomecreation' => '== Добре дошли, $1! ==

Вашата сметка беше успешно открита. Сега можете да промените настройките на {{SITENAME}} по Ваш вкус.',

'loginpagetitle' => 'Влизане в системата',
'yourname'    => 'Потребителско име',
'yourpassword'  => 'Парола',
'yourpasswordagain' => 'Въведете повторно парола',
'remembermypassword' => 'Запомняне на паролата',
'yourdomainname'       => 'Домейн',
# TODO
'externaldberror'      => 'There was either an external authentication database error or you are not allowed to update your external account.',
'loginproblem'  => '<b>Имаше проблем с влизането Ви.</b><br />Опитайте отново!',
'alreadyloggedin' => '<strong>$1, вече сте влезли в системата!</strong>',
'login'      => 'Влизане',
'loginprompt'  => "За влизане в {{SITENAME}} е необходимо да въведете потребителското си име и парола и да натиснете бутона '''Влизане''', като за да бъде това успешно, бисквитките (cookies) трябва да са разрешени в браузъра Ви.

Ако все още не сте регистрирани (нямате открита сметка), лесно можете да сторите това, като просто въведете желаните от Вас потребителско име и парола (двукратно) и щракнете върху '''Регистриране'''.",
'userlogin'    => 'Регистриране или влизане',
'logout'    => 'Излизане',
'userlogout'  => 'Излизане',
'notloggedin'  => 'Не сте влезли',
'createaccount'  => 'Регистриране',
'createaccountmail'     => 'с писмо по електронната поща',
'badretype'    => 'Въведените пароли не съвпадат.',
'userexists'  => 'Въведеното потребителско име вече се използва. Моля, изберете друго име.',
'youremail'    => 'Е-поща *',
'yourrealname' => 'Истинско име *',
'yourlanguage'  => 'Език',
'yourvariant'  => 'Вариант',
'yournick'    => 'Псевдоним (за подписи чрез <code><nowiki>~~~~</nowiki></code>)',
'email'       => 'Е-поща',
'prefs-help-email'      => '* <strong>Електронна поща</strong> <em>(незадължително)</em>: Позволява на хората да се свържат с Вас, без да се налага да им съобщавате адреса си, а също може да се използва, за да Ви се изпрати нова парола, ако случайно забравите сегашната си.',
# TODO
'prefs-help-email-enotif' => 'This address is also used to send you email notifications if you enabled the options.',
'prefs-help-realname'   => '* <strong>Истинско име</strong> <em>(незадължително)</em>: Ако го посочите, на него ще бъдат приписани Вашите приноси.',
'loginerror'  => 'Грешка при влизане',
'nocookiesnew'  => 'Потребителската сметка беше създадена, но все още не сте влезли. {{SITENAME}} използва бисквитки при влизане на потребителите. Моля, разрешете бисквитките във Вашия браузър, тъй като те са забранени, и след това влезте с потребителското си име и парола.',
'nocookieslogin'        => '{{SITENAME}} използва бисквитки (cookies) за запис на влизанията. Моля, разрешете бисквитките във Вашия браузър, тъй като те са забранени, и опитайте отново.',
'noname'    => 'Не указахте валидно потребителско име.',
'loginsuccesstitle' => 'Успешно влизане',
'loginsuccess'  => 'Влязохте в {{SITENAME}} като „$1“.',
'nosuchuser'  => 'Няма потребител с името „$1“.
Проверете изписването или се регистрирайте, използвайки долния формуляр.',
'nosuchusershort' => 'Няма потребител с името „$1“. Проверете изписването.',
'wrongpassword'  => 'Въведената парола е невалидна (или липсва). Моля, опитайте отново.',
'mailmypassword' => 'Изпращане на нова парола',
'passwordremindertitle' => 'Напомняне за парола от {{SITENAME}}',
'passwordremindertext' => 'Някой (най-вероятно Вие, от IP-адрес $1) помоли да Ви изпратим нова парола за влизане в {{SITENAME}}.
Паролата за потребителя „$2“ е „$3“.
Сега би трябвало да влезете в системата и да смените паролата си.',
'noemail'    => 'Няма записана електронна поща за потребителя „$1“.',
'passwordsent'  => 'Нова парола беше изпратена на електронната поща на „$1“.
Моля, влезте отново, след като я получите.',
# TODO
'eauthentsent'  =>  'A confirmation email has been sent to the nominated email address.
Before any other mail is sent to the account, you will have to follow the instructions in the email, to confirm that the account is actually yours.',
'mailerror' => 'Грешка при изпращане на писмо: $1',
'acct_creation_throttle_hit' => 'Съжаляваме, създали сте вече $1 сметки и нямате право на повече.',
# TODO
'emailauthenticated'    => 'Your email address was authenticated on $1.',
'emailnotauthenticated' => 'Your email address is <strong>not yet authenticated</strong>. No email will be sent for any of the following features.',
'noemailprefs'          => '<strong>No email address has been specified</strong>, the following features will not work.',
'emailconfirmlink' => 'Confirm your e-mail address',
'invalidemailaddress'   => 'The email address cannot be accepted as it appears to have an invalid format. Please enter a well-formatted address or empty that field.',

# Edit page toolbar
'bold_sample'      => 'Получер текст',
'bold_tip'         => 'Получер (удебелен) текст',
'italic_sample'    => 'Курсивен текст',
'italic_tip'       => 'Курсивен (наклонен) текст',
'link_sample'      => 'Име на препратка',
'link_tip'         => 'Вътрешна препратка',
'extlink_sample'   => 'http://www.primer.com Име на препратката',
'extlink_tip'      => 'Външна препратка (не забравяйте http:// отпред)',
'headline_sample'  => 'Заглавен текст',
'headline_tip'     => 'Заглавие',
'math_sample'      => 'Тук въведете формулата',
'math_tip'         => 'Математическа формула (LaTeX)',
'nowiki_sample'    => 'Тук въведете текст',
'nowiki_tip'       => 'Пренебрегване на форматиращите команди',
'image_sample'     => 'Пример.jpg',
'image_tip'        => 'Вмъкване на картинка',
'media_sample'     => 'Пример.ogg',
'media_tip'        => 'Препратка към файл',
'sig_tip'          => 'Вашият подпис заедно с времева отметка',
'hr_tip'           => 'Хоризонтална линия (използвайте пестеливо)',

# Edit pages
#
'summary'       => 'Резюме',
'subject'       => 'Тема/заглавие',
'minoredit'     => 'Това е малка промяна.',
'watchthis'     => 'Наблюдаване на страницата',
'savearticle'   => 'Съхранение',
'preview'       => 'Предварителен преглед',
'showpreview'   => 'Предварителен преглед',
'showdiff'      => 'Показване на промените',
'blockedtitle'  => 'Потребителят е блокиран',
'blockedtext'   => "Вашето потребителско име (или IP-адрес) е блокирано от $1.
Причината за това е:<br />''$2''<p>Можете да се свържете с $1 или с някой от останалите [[Project:Администратори|администратори]], за да обсъдите това.

Можете да използвате услугата „'''Пращане писмо на потребителя'''“ единствено, ако сте посочили валидна електронна поща в [[Special:Preferences|настройките]] си.

Вашият IP-адрес е $3. Моля, вмъквайте този адрес във всяко питане, което правите.",
'whitelistedittitle' => 'Необходимо е да влезете, за да може да редактирате',
'whitelistedittext' => 'Необходимо е да [[Special:Userlogin|влезете]], за да може да редактирате страници.',
'whitelistreadtitle' => 'Необходимо е да влезете, за да може да четете страници',
'whitelistreadtext' => 'Необходимо е да [[Special:Userlogin|влезете]], за да може да четете страници.',
'whitelistacctitle' => 'Не ви е позволено да създавате сметка',
'whitelistacctext' => 'За да Ви бъде позволено създаването на сметки, трябва да [[Special:Userlogin|влезете]] и да имате подходящото разрешение.',
'loginreqtitle' => 'Изисква се влизане',
'loginreqlink' => 'влизане',
'loginreqpagetext' => 'Необходимо е да $1, за да може да разглеждате други страници.',
'accmailtitle' => 'Паролата беше изпратена.',
'accmailtext' => 'Паролата за „$1“ беше изпратена на $2.',
'newarticle'  => '(нова)',
'newarticletext' => "<div style=\"font-size:small; color:#033; border:thin solid #aaa; padding:.4em\">Последвали сте препратка към страница, която все още не съществува.
За да я създадете, започнете да пишете в долната текстова кутия
(вижте '''[[Project:Помощ|помощната страница]]''' за повече информация).
Ако сте дошли тук погрешка, просто натиснете '''бутона за връщане''' на Вашия браузър.

Вашата добавка ще бъде видима '''веднага''' след съхранението, затова ако просто искате да изпробвате как работят нещата, използвайте нашия '''[[Project:Пясъчник|пясъчник]]'''.</div>",
'anontalkpagetext' => "----
''Това е дискусионната страница на анонимен потребител, който  все още няма сметка или не я използва. Затова се налага да използваме IP-адрес, за да го/я идентифицираме. Такъв адрес може да се споделя от няколко потребители.''

''Ако сте анонимен потребител и мислите, че тези неуместни коментари са отправени към Вас, моля [[Special:Userlogin|регистрирайте се или влезте в системата]], за да избегнете евентуално бъдещо объркване с други анонимни потребители.''",
'noarticletext' => "(Тази страница все още не съществува. Можете да я създадете, като щракнете на '''Редактиране'''.)",
'clearyourcache' => "'''Бележка:''' След съхранението е необходимо да изтриете кеша на браузъра, за да видите промените:
'''Mozilla / Firefox / Safari:''' натиснете бутона ''Shift'' и щракнете върху ''Презареждане'' (''Reload''), или изберете клавишната комбинация ''Ctrl-Shift-R'' (''Cmd-Shift-R'' за Apple Mac);
'''IE:''' натиснете ''Ctrl'' и щракнете върху ''Refresh'', или клавишната комбинация ''CTRL-F5'';
'''Konqueror:''' щракнете върху ''Презареждане'' или натиснете ''F5'';
'''Opera:''' вероятно е необходимо да изчистите кеша през менюто ''Tools&rarr;Preferences''.",
'usercssjsyoucanpreview' => '<strong>Съвет:</strong> Използвайте бутона „Предварителен преглед“, за да изпробвате новия код на css/js преди съхранението.',
'usercsspreview' => "'''Не забравяйте, че това е само предварителен преглед на кода на CSS, страницата все още не е съхранена!'''",
'userjspreview' => "'''Не забравяйте, че това е само изпробване/предварителен преглед на кода на Javascript, страницата все още не е съхранена!'''",
'updated'    => '(актуализирана)',
'note'      => '<strong>Забележка:</strong>',
'previewnote'  => 'Не забравяйте, че това е само предварителен преглед и страницата все още не е съхранена!',
'previewconflict' => 'Този предварителен преглед отразява текста в горната текстова кутия така, както би се показал, ако съхраните.',
'editing'    => 'Редактиране на „$1“',
'editingsection'    => 'Редактиране на „$1“ (раздел)',
'editingcomment'    => 'Редактиране на „$1“ (нов раздел)',
'editconflict'  => 'Различна редакция: $1',
'explainconflict' => 'Някой друг вече е променил тази страница, откакто започнахте да я редактирате.
Горната текстова кутия съдържа текущия текст на страницата без Вашите промени, които са показани в долната кутия. За да бъдат и те съхранени, е необходимо ръчно да ги преместите в горното поле, тъй като <b>единствено</b> текстът в него ще бъде съхранен при натискането на бутона „Съхранение“.<br />',
'yourtext'    => 'Вашият текст',
'storedversion' => 'Съхранена версия',
'nonunicodebrowser' => '<strong>ВНИМАНИЕ: Браузърът Ви не поддържа Уникод. За да можете спокойно да редактирате страници, всички символи, невключени в ASCII-таблицата, ще бъдат заменени с шестнадесетични кодове.</strong>',
'editingold'  => '<strong>ВНИМАНИЕ: Редактирате остаряла версия на страницата.
Ако съхраните, всякакви промени, направени след тази версия, ще бъдат изгубени.</strong>',
'yourdiff'    => 'Разлики',
'copyrightwarning' => '<div style="color:black; background-color:#FFFFEE; border:thin solid #999; padding:0.5em">
Моля, обърнете внимание на това, че всички приноси към {{SITENAME}} се публикуват при условията на $2 (за подробности вижте $1).
Ако не сте съгласни Вашата писмена работа да бъде променяна и разпространявана без ограничения, не я публикувайте.<br />

Също потвърждавате, че <strong>Вие</strong> сте написали материала или сте използвали <strong>свободни ресурси</strong> – <em>обществено достояние</em> или друг свободен източник.
Ако сте ползвали чужди материали, за които имате разрешение, непременно посочете източника.

<div style="color:#EE0000; background-color:#FFFFEE; font-weight:bold; font-size:1.1em; font-variant:small-caps; text-align:center;">Не публикувайте произведения с авторски права без разрешение!</div>
</div>',
'copyrightwarning2' => '<div style="color:black; background-color:#FFFFEE; border:thin solid #999; padding:0.5em">
Моля, обърнете внимание на това, че всички приноси към {{SITENAME}} могат да бъдат редактирани, променяни или премахвани от останалите сътрудници.
Ако не сте съгласни Вашата писмена работа да бъде променяна без ограничения, не я публикувайте.<br />
Също потвърждавате, че <strong>Вие</strong> сте написали материала или сте използвали <strong>свободни ресурси</strong> – <em>обществено достояние</em> или друг свободен източник.
Ако сте ползвали чужди материали, за които имате разрешение, непременно посочете източника.

<div style="color:#ee0000; background-color:#ffffee; font-weight:bold; font-size:1.1em; font-variant:small-caps; text-align:center;">Не публикувайте произведения с авторски права без разрешение!</div>
</div>',
'longpagewarning' => '<strong>ВНИМАНИЕ: Страницата има размер $1 килобайта; някои браузъри могат да имат проблеми при редактиране на страници по-големи от 32 КБ.
Моля, обмислете дали страницата не може да се раздели на няколко по-малки части.</strong>',
'readonlywarning' => '<strong>ВНИМАНИЕ: Базата от данни беше затворена за поддръжка, затова в момента промените Ви не могат да бъдат съхранени. Ако желаете, можете да съхраните страницата като текстов файл и да се опитате да я публикувате по-късно.</strong>',
'protectedpagewarning' => '<strong>ВНИМАНИЕ: Страницата е защитена и само администратори могат да я редактират.
Моля, следвайте [[Project:Защитена страница|указанията за защитена страница]].</strong>',
'templatesused' => 'Шаблони, използвани на страницата:',

# History pages
#
'revhistory'      => 'История на версиите',
'nohistory'       => 'Няма редакционна история за тази страница.',
'revnotfound'     => 'Версията не е открита',
'revnotfoundtext' => 'Желаната стара версия на страницата не беше открита.
Моля, проверете адреса, който използвахте за достъп до страницата.',
'loadhist'        => 'Зареждане история на страницата',
'currentrev'      => 'Текуща версия',
'revisionasof'    => 'Версия от $1',
'previousrevision'      => '←По-стара версия',
'nextrevision'          => 'По-нова версия→',
'currentrevisionlink'   => 'преглед на текущата версия',
'cur'             => 'тек',
'next'            => 'след',
'last'            => 'посл',
'orig'            => 'ориг',
'histlegend'      => '<i>Разлики:</i> Изберете версиите, които желаете да сравните, чрез превключвателите срещу тях и натиснете &lt;Enter&gt; или бутона за сравнение.<br />
<i>Легенда:</i> (<b>тек</b>) = разлика с текущата версия, (<b>посл</b>) = разлика с предишната версия, <b>м</b>&nbsp;=&nbsp;малка промяна',
'deletedrev' => '[изтрита]',
'histfirst' => 'Първи',
'histlast' => 'Последни',
# Diffs
#
'difference'   => '(Разлики между версиите)',
'loadingrev'   => 'зареждане на версии за функцията <em>разл</em>',
'lineno'       => 'Ред $1:',
'editcurrent'  => 'Редактиране на текущата версия на страницата',
'selectnewerversionfordiff' => 'Избиране на нова версия за сравнение',
'selectolderversionfordiff' => 'Избиране на стара версия за сравнение',
'compareselectedversions' => 'Сравнение на избраните версии',

# Search results
#
'searchresults' => 'Резултати от търсенето',
'searchresulttext' => 'За повече информация относно {{SITENAME}}, вижте [[Project:Търсене|Търсене в {{SITENAME}}]].',
'searchsubtitle'  => 'За заявка „[[:$1]]“',
'searchsubtitleinvalid'  => 'За заявка „$1“',
'badquery'    => 'Лошо формулирана заявка за търсене',
'badquerytext'  => 'Вашата заявка не можа да бъде обработена.
Вероятно сте се опитали да търсите дума с по-малко от три букви, което все още не се поддържа.
Възможно е и да сте сгрешили в изписването на израза, например: „риба и и везни“.
Моля, опитайте с нова заявка.',
'matchtotals'  => 'Заявката „$1“ отговаря на $2 заглавия на страници и на текста на $3 страници.',
'noexactmatch' => 'В {{SITENAME}} не съществува страница с това заглавие. Можете да я \'\'\'[[:$1|създадете]]\'\'\'.',
'titlematches'  => 'Съответствия в заглавията на страници',
'notitlematches' => 'Няма съответствия в заглавията на страници',
'textmatches'  => 'Съответствия в текста на страници',
'notextmatches'  => 'Няма съответствия в текста на страници',
'prevn'      => 'предишни $1',
'nextn'      => 'следващи $1',
'viewprevnext'  => 'Преглед ($1) ($2) ($3).',
'showingresults' => 'Показване на до <b>$1</b> резултата, като се започва от номер <b>$2</b>.',
'showingresultsnum' => 'Показване на <b>$3</b> резултата, като се започва от номер <b>$2</b>.',
'nonefound'    => "'''Забележка''': Безрезултатните търсения често са причинени от това, че се търсят основни думи като „има“ или „от“, които не се индексират, или от това, че се търсят повече от една думи, тъй като се показват само страници, съдържащи всички зададени понятия.",
'powersearch' => 'Търсене',
'powersearchtext' => '
Търсене в именни пространства:<br />
$1<br />
$2 Показване на пренасочвания &nbsp; Търсене на $3 $4',
'searchdisabled' => 'Търсенето в {{SITENAME}} е временно изключено поради голямото натоварване на сървъра. Междувременно можете да търсите чрез Google. Обърнете внимание обаче, че е възможно съхранените при тях страници да са остарели.',
'googlesearch' => '
	<form method="get" action="http://www.google.com/search" style="margin-left:135px">
		<div>
			<input type="hidden" name="domains" value="{{SERVER}}" />
			<input type="hidden" name="num" value="50" />
			<input type="hidden" name="ie" value="$2" />

			<input type="hidden" name="oe" value="$2" />
			<input type="text" name="q" size="31" maxlength="255" value="$1" />
			<input type="submit" name="btnG" value="Google Search" />
		</div>
		<div style="font-size:90%">
			<input type="radio" name="sitesearch" id="gwiki" value="{{SERVER}}"
				checked="checked" /><label for="gwiki">{{SITENAME}}</label>
			<input type="radio" name="sitesearch" id="gWWW" value="" />
			<label for="gWWW">Мрежата</label>
	</div>
	</form>

Можете да използвате следната препратка, за да създадете или редактирате страницата: <a href="/w/index.php?title=$1&action=edit">$1</a>',
'blanknamespace' => '(Основно)',

# Preferences page
#
'preferences'  => 'Настройки',
'prefsnologin' => 'Не сте влезли',
'prefsnologintext'  => 'Необходимо е да [[Special:Userlogin|влезете]], за да може да променяте потребителските си настройки.',
'prefsreset'  => 'Стандартните настройки бяха възстановени.',
'qbsettings'  => 'Лента за бърз избор',
'changepassword' => 'Смяна на парола',
'skin'      => 'Облик',
'math'      => 'Математически формули',
'dateformat'  => 'Формат на датата',
'math_failure'    => 'Неуспех при разбора',
'math_unknown_error'  => 'непозната грешка',
'math_unknown_function'  => 'непозната функция',
'math_lexing_error'  => 'лексикална грешка',
'math_syntax_error'  => 'синтактична грешка',
'math_image_error' => 'Превръщането към PNG не сполучи. Проверете дали latex, dvips и gs са правилно инсталирани.',
'math_bad_tmpdir' => 'Невъзможно е писането или създаването на временна папка за математическите операции',
'math_bad_output' => 'Невъзможно е писането или създаването на изходяща папка за математическите операции',
'math_notexvc'  => 'Липсва изпълнимият файл на texvc. Моля, прегледайте math/README за информация относно конфигурирането.',
'prefs-personal' => 'Потребителски данни',
'prefs-rc' => 'Последни промени и мъничета',
'prefs-misc' => 'Други настройки',
'saveprefs'    => 'Съхранение',
'resetprefs'  => 'Възстановяване на стандартните настройки',
'oldpassword'  => 'Стара парола',
'newpassword'  => 'Нова парола',
'retypenew'    => 'Нова парола повторно',
'textboxsize'  => 'Редактиране',
'rows'      => 'Редове',
'columns'    => 'Колони',
'searchresultshead' => 'Търсене',
'resultsperpage' => 'Резултати на страница',
'contextlines'  => 'Редове за резултат',
'contextchars'  => 'Знаци от контекста на ред',
'stubthreshold' => 'Определяне като къси страници до',
'recentchangescount' => 'Брой заглавия в последни промени',
'savedprefs'  => 'Вашите настройки бяха съхранени.',
'timezonelegend' => 'Времева зона',
'timezonetext'  => '¹ Броят часове, с които Вашето местно време се различава от това на сървъра (UTC).',
'localtime'  => 'Местно време',
'timezoneoffset' => 'Отместване¹',
'servertime'  => 'Време на сървъра',
'guesstimezone' => 'Попълване чрез браузъра',
'defaultns'    => 'Търсене в тези именни пространства по подразбиране:',
'default'      => 'по подразбиране',
'files'        => 'Файлове',

# User levels special page

'userrights-lookup-user' => 'Управляване на потребителските групи',#Manage user groups
'userrights-user-editname' => 'Въведете потребителско име:', #Enter a username:
'editusergroup' => 'Редактиране на потребителските групи', #Edit User Groups

# user groups editing
'userrights-editusergroup' => 'Редактиране на потребителските групи',#Edit user groups
'saveusergroups' => 'Съхранение на потребителските групи',#Save User Groups
'userrights-groupsmember' => 'Член на:',
'userrights-groupsavailable' => 'Групи на разположение:',
'userrights-groupshelp' => 'Изберете групите, към които искате той да бъде прибавен или от които да бъде премахнат. Неизбраните групи няма да бъдат променени. Можете да отизберете група чрез <CTRL> + ляв бутон на мишката',
'userrights-logcomment' => 'Смяна на груповата принадлежност от $1 към $2',


# Recent changes
#
'changes' => 'промени',
'recentchanges' => 'Последни промени',
'recentchangestext' => 'Проследяване на последните промени в {{SITENAME}}.

Легенда: <b>тек</b> = разлика на текущата версия,
<b>ист</b> = история на версиите, <b>м</b>&nbsp;=&nbsp;малка промяна, <b class="newpage">Н</b>&nbsp;=&nbsp;новосъздадена страница',
'rcnote'    => 'Показани са последните <strong>$1</strong> промени през последните <strong>$2</strong> дни.',
'rcnotefrom'  => 'Дадени са промените от <b>$2</b> (до <b>$1</b> показани).',
'rclistfrom'  => 'Показване на промени, като се започва от $1.',
'rclinks'    => 'Показване на последните $1 промени през последните $2 дни<br />$3',
'diff'      => 'разл',
'hist'      => 'ист',
'hide'      => 'Скриване',
'show'      => 'Показване',
'minoreditletter' => 'м',
'newpageletter' => 'Н',
'sectionlink' => '→',
'number_of_watching_users_pageview'     => '[$1 наблюдаващ(и) потребител(и)]',

# Upload
'upload'    => 'Качване',
'uploadbtn'    => 'Качване',
'reupload'    => 'Повторно качване',
'reuploaddesc'  => 'Връщане към формуляра за качване.',
'uploadnologin' => 'Не сте влезли',
'uploadnologintext'  => 'Необходимо е да [[Special:Userlogin|влезете]], за да може да качвате файлове.',
'upload_directory_read_only' => 'Сървърът няма достъп за писане до папката за качване „$1“.',
'uploaderror'  => 'Грешка при качване',
'uploadtext'  => "
Използвайте долния формуляр, за да качвате файлове, които ще можете да използвате в страниците.
В повечето браузъри ще видите бутон „Browse...“ (ако използвате преведен интерфейс, можете да видите „Избор на файл...“, „Избор...“ и др.), който ще отвори основния за вашата операционна система диалогов прозорец за избиране на файл.

За да включите картинка (файл) в страница, използвайте една от следните препратки: '''<nowiki>[[{{ns:Image}}:картинка.jpg]]</nowiki>''' или '''<nowiki>[[{{ns:Image}}:картинка.png|алтернативен текст]]</nowiki>''' или '''<nowiki>[[{{ns:Media}}:звук.ogg]]</nowiki>''' за музикални файлове.

За да прегледате съществуващите в базата от данни файлове, разгледайте [[Special:Imagelist|списъка с качените файлове]].
Качванията и изтриванията се записват в [[Special:Log/upload|дневника на качванията]].",
'uploadlog'    => 'дневник на качванията',
'uploadlogpage' => 'Дневник на качванията',
'uploadlogpagetext' => 'Списък на последните качвания.',
'filename'    => 'Име на файл',
'filedesc'    => 'Описание',
'fileuploadsummary' => 'Описание:',
'filestatus' => 'Авторско право',
'filesource' => 'Изходен код',
'copyrightpage' => 'Project:Авторски права',
'copyrightpagename' => 'авторските права в {{SITENAME}}',
'uploadedfiles'  => 'Качени файлове',
'minlength'   => 'Имената на файловете трябва да съдържат поне три знака.',
'illegalfilename' => 'Името на файла „$1“ съдържа знаци, които не са позволени в заглавия на страници. Моля, преименувайте файла и се опитайте да го качите отново.',
'badfilename' => 'Файлът беше преименуван на „$1“.',
'badfiletype' => 'Файловият формат „.$1“ не се препоръчва за картинки.',
'largefile'   => 'Препоръчва се файловете да не надвишават $1 байта, размерът на този файл е $2 байта.',
'largefileserver' => 'Файлът е по-голям от допустимия от сървъра размер.',
'emptyfile'   => 'Каченият от Вас файл е празен. Това може да е предизвикано от грешка в името на файла. Моля, уверете се дали наистина искате да го качите.',
'fileexists'  => 'Вече съществува файл с това име! Моля, прегледайте $1, ако не сте сигурни дали искате да го промените.',
'successfulupload' => 'Качването беше успешно',
'fileuploaded'  => 'Файлът „$1“ беше успешно качен.
Моля, последвайте препратката: ($2) към страницата за описание и въведете малко информация за файла – кога и от кого е създаден и всякаква друга информация, която може да имате за него. Ако това е картинка, можете да я вмъкнете в някоя страница по следния начин: <tt><nowiki>[[Картинка:$1|мини|Описание]]</nowiki></tt>',
'uploadwarning' => 'Предупреждение при качване',
'savefile'    => 'Съхраняване на файл',
'uploadedimage' => 'качена „[[$1]]“',
'uploaddisabled' => 'Съжаляваме, качванията бяха спрени.',
'uploadscripted' => 'Файлът съдържа HTML или скриптов код, който може да бъде погрешно  интерпретиран от браузъра.',
'uploadcorrupt' => 'Файлът е повреден или е с неправилно разширение. Моля, проверете го и го качете отново.',
'uploadvirus' => 'Файлът съдържа вирус! Подробности: $1',
'sourcefilename' => 'Първоначално име',
'destfilename' => 'Целево име',

'license' => 'Лицензиране',
'nolicense' => 'Нищо не е избрано',

# Image list

'imagelist'    => 'Списък на файловете',
'imagelisttext'  => 'Списък от $1 файла, сортирани $2.',
'getimagelist'  => 'донасяне на списъка с файлове',
'ilsubmit'    => 'Търсене',
'showlast'    => 'Показване на последните $1 файла, сортирани $2.',
'byname'    => 'по име',
'bydate'    => 'по дата',
'bysize'    => 'по размер',
'imgdelete'    => 'изтр',
'imgdesc'    => 'опис',
'imglegend'    => 'Легенда: (опис) = показване/редактиране на описанието на файла.',
'imghistory'  => 'История на файла',
'revertimg'    => 'връщ',
'deleteimg'    => 'изтр',
'deleteimgcompletely' => 'Изтриване на всички версии на файла',
'imghistlegend' => 'Легенда: (тек) = текущият файл, (изтр) = изтриване на съответната версия, (връщ) = възвръщане към съответната версия.
<br /><i>Щракнете върху датата, за да видите файла, качен на тази дата</i>.',
'imagelinks'  => 'Препратки към файла',
'linkstoimage'  => 'Следните страници сочат към файла:',
'nolinkstoimage' => 'Няма страници, сочещи към файла.',
'sharedupload' => 'Този файл е споделен и може да бъде използван от други проекти.',
'shareduploadwiki' => 'Моля, разгледайте $1 за по-нататъшна информация.',
'shareduploadwiki-linktext' => 'описателната страница на файла',
'noimage'       => 'Не съществува файл с това име, можете $1.',
'noimage-linktext' => 'да го качите',
'uploadnewversion-linktext' => 'Качване на нова версия на файла',

# Statistics
#
'statistics'  => 'Статистика',
'sitestats'    => 'Страници',
'userstats'    => 'Потребители',
'sitestatstext' => "Базата от данни съдържа '''$1''' страници.
Това включва всички страници от всички именни пространства в {{SITENAME}} (''Основно'', Беседа, {{ns:Project}}, Потребител, Категория, ...). Измежду тях '''$2''' страници се смятат за действителни (изключват се пренасочванията и страниците, несъдържащи препратки).

Имало е '''$4''' редакции на страници откакто уикито беше пуснато. Това прави средно по '''$5''' редакции на страница.",
'userstatstext' => "Има '''$1''' регистрирани потребители, като '''$2''' от тях (или '''$4%''') са администратори (вижте $3).",

# Maintenance Page
#
'disambiguations'  => 'Пояснителни страници',
'disambiguationspage'  => 'Шаблон:Пояснение',
'disambiguationstext'  => 'Следните страници сочат към <i>пояснителна страница</i>. Вместо това те би трябвало да сочат към съответната тема.<br /> Страница се определя като „<i>пояснителна</i>“, ако към нея се сочи от $1.<br />Тук <i>не</i> са посочени препратки от други именни пространства.',
'doubleredirects'  => 'Двойни пренасочвания',
'doubleredirectstext' => 'Всеки ред съдържа препратки към първото и второто пренасочване, както и първия ред на текста на второто пренасочване, който обикновено посочва „<i>истинската</i>“ целева страница, към която първото пренасочване би трябвало да сочи.',
'brokenredirects'  => 'Невалидни пренасочвания',
'brokenredirectstext'  => 'Следните пренасочващи страници сочат към несъществуващи страници.',


# Miscellaneous special pages
#
'lonelypages'  => 'Страници сираци',
'uncategorizedpages' => 'Некатегоризирани страници',
'uncategorizedcategories' => 'Некатегоризирани категории',
'unusedcategories' => 'Неизползвани категории',
'unusedimages'  => 'Неизползвани файлове',
'popularpages'  => 'Известни страници',
'nviews'    => '$1 прегледа',
'wantedpages'  => 'Желани страници',
'mostlinked'    => 'Най-препращани страници',
'nlinks'    => '$1 препратки',
'allpages'    => 'Всички страници',
'prefixindex'   => 'Азбучен списък на представки',
'randompage'  => 'Случайна страница',
'shortpages'  => 'Кратки страници',
'longpages'    => 'Дълги страници',
'deadendpages'  => 'Задънени страници',
'listusers'    => 'Списък на потребителите',
'specialpages'  => 'Специални страници',
'spheading'    => 'Специални страници за всички потребители',
'restrictedpheading'    => 'Специални страници с ограничен достъп',
'recentchangeslinked' => 'Свързани промени',
'rclsub'    => '(на страници, сочени от „$1“)',
'newpages'    => 'Нови страници',
'ancientpages'    => 'Стари страници',
'intl'          => 'Междуезикови препратки',
'move' => 'Преместване',
'movethispage'  => 'Преместване на страницата',
'unusedimagestext' => 'Моля, обърнете внимание на това, че други сайтове могат да сочат към картинката чрез пряк адрес и въпреки това тя може да се намира в списъка.',
'unusedcategoriestext' => 'Следните категории съществуват, но никоя страница или категория не ги използва.',
'booksources'  => 'Източници на книги',
'categoriespagetext' => 'В {{SITENAME}} съществуват следните категории.',
'data'  => 'Данни',
'userrights' => 'Управление на потребителските права',
'groups' => 'Потребителски групи',

'booksourcetext' => 'Показани са препратки към други сайтове, които продават нови и използвани книги и могат да имат допълнителна информация за книгите, които търсите.',
'isbn'  => 'ISBN',
'alphaindexline' => 'от $1 до $2',
'version'       => 'Версия',
'log'           => 'Дневници',
'alllogstext'   => 'Смесено показване на дневниците на качванията, изтриванията, защитата, блокиранията и бюрократите.
Можете да ограничите прегледа, като изберете вид на дневника, потребителско име или определена страница.',

# Special:Allpages
'nextpage'          => 'Следваща страница ($1)',
'allpagesfrom'      => 'Показване на страниците, като се започва от:',
'allarticles'       => 'Всички страници',
'allinnamespace'    => 'Всички страници (именно пространство $1)',
'allnotinnamespace' => 'Всички страници (без именно пространство $1)',
'allpagesprev'      => 'Предишна',
'allpagesnext'      => 'Следваща',
'allpagessubmit'    => 'Отиване',

# Email this user
#
'mailnologin'  => 'Няма електронна поща',
'mailnologintext' => 'Необходмимо е да [[Special:Userlogin|влезете]] и да посочите валидна електронна поща в [[Special:Preferences|настройките]] си, за да може да пращате писма на други потребители.',
'emailuser'    => 'Пращане писмо на потребителя',
'emailpage'    => 'Пращане писмо на потребител',
'emailpagetext'  => 'Ако потребителят е посочил валидна електронна поща в настройките си, чрез долния формуляр можете да му изпратите съобщение. Адресът, записан в настройките Ви, ще се появи в полето „От“ на изпратеното писмо, така че получателят ще е в състояние да Ви отговори.',
'usermailererror'  => 'Пощенският обект даде грешка:', # Mail object returned error:
'defemailsubject'  => 'Писмо от {{SITENAME}}',
'noemailtitle'  => 'Няма електронна поща',
'noemailtext'  => 'Потребителят не е посочил валидна електронна поща или е избрал да не получава писма от други потребители.',
'emailfrom'    => 'От',
'emailto'    => 'До',
'emailsubject'  => 'Относно',
'emailmessage'  => 'Съобщение',
'emailsend'    => 'Изпращане',
'emailsent'    => 'Писмото е изпратено',
'emailsenttext' => 'Писмото Ви беше изпратено.',

# Watchlist
#
'watchlist'    => 'Моят списък за наблюдение',
'nowatchlist'  => 'Списъкът Ви за наблюдение е празен.',
'watchnologin'  => 'Не сте влезли',
'watchnologintext'  => 'Необходимо е да [[Special:Userlogin|влезете]], за да може да променяте списъка си за наблюдение.',
'addedwatch'  => 'Добавено в списъка за наблюдение',
'addedwatchtext' => "Страницата „'''$1'''“ беше добавена към [[Special:Watchlist|списъка Ви за наблюдение]].
Нейните бъдещи промени, както и на съответната й дискусионна страница, ще се описват там, а тя ще се появява с '''удебелен шрифт''' в [[Special:Recentchanges|списъка на последните промени]], което ще направи по-лесно избирането й.

Ако по-късно искате да премахнете страницата от списъка си за наблюдение, щракнете на „''Спиране на наблюдение''“.",
'removedwatch'  => 'Премахнато от списъка за наблюдение',
'removedwatchtext' => 'Страницата „$1“ беше премахната от списъка Ви за наблюдение.',
'watch' => 'Наблюдаване',
'watchthispage'  => 'Наблюдаване на страницата',
'unwatch' => 'Спиране на наблюдение',
'unwatchthispage' => 'Спиране на наблюдение',
'notanarticle'  => 'Не е страница',
'watchnochange' => 'Никоя от наблюдаваните страници не е била редактирана в показаното време.',
# TODO съобщението отново е съкратено, да се провери по-късно, може да е нечия грешка
'watchdetails' => '* $1 наблюдавани страници (без дискусионни), $2 редактирани страници в избраното време
* Метод на заявката: $3
* [[Special:Watchlist/edit|Показване и редактиране на пълния списък]]',
# TODO
'wlheader-enotif'        => '* Email notification is enabled.',
'wlheader-showupdated'   => "* Pages which have been changed since you last visited them are shown in '''bold'''",
'watchmethod-recent' => 'проверка на последните промени за наблюдавани страници',
'watchmethod-list' => 'проверка на наблюдаваните страници за скорошни редакции',
'removechecked' => 'Премахване на избраните от списъка за наблюдение',
'watchlistcontains' => 'Списъкът Ви за наблюдение съдържа $1 страници.',
'watcheditlist' => 'В азбучен ред са показани наблюдаваните от Вас основни страници. Отметнете кутийките на страниците, които искате да премахнете от списъка Ви за наблюдение и натиснете бутона „Премахване на избраните“ (изтриването на основна страница предизвиква изтриването и на съответната й дискусионна страница и обратно).',
'removingchecked' => 'Премахване на избраните от списъка за наблюдение...',
'couldntremove' => 'Неуспех при премахването на „$1“...',
'iteminvalidname' => 'Проблем с „$1“, грешно име...',
'wlnote' => 'Показани са последните $1 промени през последните <b>$2</b> часа.',
'wlshowlast' => 'Показване на последните $1 часа $2 дни $3',
'wlsaved' => 'Това е съхранена версия на списъка Ви за наблюдение.',
'wlhideshowown'         => '$1 на моите редакции.',
'wlhideshowbots'        => '$1 на редакции на ботове.',

'enotif_mailer'  => '{{SITENAME}} Notification Mailer',
'enotif_reset'   => 'Отбелязване на всички страници като посетени',
'enotif_newpagetext'=> 'Това е нова страница.',
'changed'        => 'променена',
'created'        => 'създадена',
'enotif_subject' 	=> '{{SITENAME}} page $PAGETITLE has been $CHANGEDORCREATED by $PAGEEDITOR',
'enotif_lastvisited' => 'Прегледайте $1 за всички промени след последното ви посещение.',

# Delete/protect/revert
#
'deletepage'  => 'Изтриване на страница',
'confirm'    => 'Потвърждение',
'excontent' => 'съдържанието беше: „$1“',
'excontentauthor' => 'съдържанието беше: „$1“ (като единственият автор беше „$2“)',
'exbeforeblank' => 'съдържанието преди изпразването беше: „$1“',
'exblank' => 'страницата беше празна',
'confirmdelete' => 'Потвърждение за изтриване',
'deletesub'    => '(Изтриване на „$1“)',
'historywarning' => 'Внимание: Страницата, която ще изтриете, има история:',
'confirmdeletetext' => 'На път сте безвъзвратно да изтриете страница или файл, заедно с цялата й (му) история, от базата от данни.
Моля, потвърдете, че искате това, разбирате последствията и правите това в съответствие с нашата [[Project:Линия на поведение|линия на поведение]].',
#'confirmcheck'  => 'Да, наистина искам да я изтрия.', # не се ползва
'actioncomplete' => 'Действието беше изпълнено',
'deletedtext'  => 'Страницата „$1“ беше изтрита. Вижте $2 за запис на последните изтривания.',
'deletedarticle' => 'изтрита „[[$1]]“',
'dellogpage'  => 'Дневник на изтриванията',
'dellogpagetext' => 'Списък на последните изтривания.',
'deletionlog'  => 'дневника на изтриванията',
'reverted'    => 'Възвръщане към предишна версия',
'deletecomment'  => 'Причина за изтриването',
'imagereverted' => 'Възвръщането към предишна версия беше успешно.',
'rollback'    => 'Връщане назад на промените', #Roll back edits
'rollback_short' => 'Връщане',
'rollbacklink'  => 'връщане', #rollback
'rollbackfailed' => 'Връщането не сполучи', #Rollback failed
'cantrollback'  => 'Промяната не може да се извърши. Последният автор е единственият собственик на страницата.',
'alreadyrolled'  => 'Редакцията на [[$1]], направена от [[Потребител:$2|$2]] ([[Потребител беседа:$2|Беседа]]), не може да се върне назад. Някой друг вече е редактирал страницата или е върнал назад промените.

Последната редакция е на [[Потребител:$3|$3]] ([[Потребител беседа:$3|Беседа]]).',
# only shown if there is an edit comment
'editcomment' => 'Коментарът на редакцията е бил: „<i>$1</i>“.',
'revertpage'  => 'Премахване на [[Special:Contributions/$2|редакции на $2]], възвръщане към последната версия на $1',
# TODO
'sessionfailure' => 'There seems to be a problem with your login session; this action has been canceled as a precaution against session hijacking. Please hit „back“ and reload the page you came from, then try again.',
'protectlogpage' => 'Дневник на защитата',
'protectlogtext' => 'Списък на защитите и техните сваляния за страницата.
За повече информация вижте [[Project:Защитена страница]].',
'protectedarticle' => 'защитена „[[$1]]“',
'unprotectedarticle' => 'сваляне на защитата на „[[$1]]“',
'protectsub' => '(Защитаване на „$1“)',
'confirmprotecttext' => 'Наистина ли искате да защитите страницата?',
'confirmprotect' => 'Потвърдете защитата',
'protectmoveonly' => 'Защита само от премествания',
'protectcomment' => 'Причина за защитата',
'unprotectsub' => '(Сваляне на защитата на „$1“)',
'confirmunprotecttext' => 'Наистина ли искате да свалите защитата на страницата?',
'confirmunprotect' => 'Потвърдете свалянето на защитата',
'unprotectcomment' => 'Причина за сваляне на защитата',

# Undelete
'undelete' => 'Преглед на изтрити страници',
'undeletepage' => 'Преглед и възстановяване на изтрити страници',
'viewdeletedpage' => 'Преглед на изтрити страници',
'undeletepagetext' => 'Следните страници бяха изтрити, но се намират все още
в архива и могат да бъдат възстановени. Архивът може да се почиства от време на време.',
'undeletearticle' => 'Възстановяване на изтрита страница',
'undeletehistorynoadmin' => 'Тази страница е била изтрита. В резюмето отдолу е посочена причината за това, заедно с информация за потребителите, редактирали страницата преди изтриването й. Конкретното съдържание на изтритите версии е достъпно само за администратори.',
'undeleterevisions' => '$1 версии архивирани',
'undeletehistory' => 'Ако възстановите страницата, всички версии ще бъдат върнати в историята.
Ако след изтриването е създадена страница със същото име, възстановените версии ще се появят като по-ранна история, а текущата версия на страницата няма да бъде автоматично заменена.',
'undeleterevision' => 'Изтрита версия на $1',
'undeletebtn' => 'Възстановяване!',
'undeletedarticle' => '„[[$1]]“ беше възстановена',
'undeletedrevisions' => '$1 версии бяха възстановени',

# Namespace form on various pages
'namespace' => 'Именно пространство:',
'invert' => 'Обръщане на избора',

# Contributions
#
'contributions'  => 'Приноси',
'mycontris' => 'Моите приноси',
'contribsub'  => 'За $1',
'nocontribs'  => 'Не са намерени промени, отговарящи на критерия.',
'ucnote'    => 'Показани са последните <b>$1</b> промени, извършени от този потребител през последните <b>$2</b> дни.',
'uclinks'    => 'Показване на последните $1 промени; показване на последните $2 дни.',
'uctop'    => ' (последна)',
'newbies'  => 'новаци',

# What links here
#
'whatlinkshere'  => 'Какво сочи насам',
'notargettitle' => 'Няма цел',
'notargettext'  => 'Не указахте целева страница или потребител, върху която/който да се изпълни действието.',
'linklistsub'  => '(Списък с препратки)',
'linkshere'    => 'Следните страници сочат насам:',
'nolinkshere'  => 'Няма страници, сочещи насам.',
'isredirect'  => 'пренасочваща страница',

# Block/unblock IP
#
'blockip'    => 'Блокиране на потребител',
'blockiptext'  => 'Използвайте долния формуляр, за да забраните правото на писане
на определен IP-адрес или потребител.
Това трябва да се направи само, за да се предотвратят прояви на вандализъм,
и в съответствие с [[Project:Линия_на_поведение|линията на поведение]] на {{SITENAME}}.
Посочете също и причина за блокирането (например, заглавия на страници, станали обект на вандализъм).

Срокът за изтичане на блокирането се въвежда според установения формат на ГНУ, описан в [http://www.gnu.org/software/tar/manual/html_chapter/tar_7.html ръководството], например: „1 hour“, „2 days“, „next Wednesday“, „1 January 2017“. Неограничено блокиране може да се зададе чрез „indefinite“ или „infinite“.',
'ipaddress'    => 'IP-адрес',
'ipadressorusername' => 'IP-адрес или потребител',
'ipbexpiry'    => 'Срок',
'ipbreason'    => 'Причина',
'ipbsubmit'    => 'Блокиране на потребителя',
'ipbother'     => 'Друг срок',
'ipboptions'   => 'Два часа:2 hours,Един ден:1 day,Три дни:3 days,Една седмица:1 week,Две седмици:2 weeks,Един месец:1 month,Три месеца:3 months,Шест месеца:6 months,Една година:1 year,Докато свят светува:infinite',
'ipbotheroption' => 'друг',
'badipaddress'  => 'Невалиден IP-адрес или грешно име на потребител',
'blockipsuccesssub' => 'Блокирането беше успешно',
'blockipsuccesstext' => '„[[{{ns:Special}}:Contributions/$1|$1]]“ беше блокиран.
<br />Вижте [[{{ns:Special}}:Ipblocklist|списъка на блокираните IP-адреси]], за да прегледате всички блокирания.',
'unblockip'    => 'Отблокиране на потребител',
'unblockiptext'  => 'Използвайте долния формуляр, за да възстановите правото на писане на по-рано блокиран IP-адрес или потребител.',
'ipusubmit'    => 'Отблокиране на адреса',
'ipblocklist'  => 'Списък на блокирани IP-адреси и потребители',
'blocklistline'  => '$1, $2 е блокирал $3 ($4)',
'infiniteblock' => 'неограничено',
'expiringblock' => 'изтича на $1',
'ipblocklistempty' => 'Списъкът на блокиранията е празен.',
'blocklink'    => 'блокиране',
'unblocklink'  => 'отблокиране',
'contribslink'  => 'приноси',
'autoblocker'  => 'Бяхте автоматично блокиран, тъй като неотдавна IP-адресът ви е бил ползван от текущо блокирания потребител „$1“. Причината за неговото блокиране е: „\'\'\'$2\'\'\'“.',
'blocklogpage'  => 'Дневник на блокиранията',
'blocklogentry' => 'блокиране на „[[$1]]“ със срок на изтичане $2',
'blocklogtext'  => 'Това е дневник на блокиранията и отблокиранията, извършени от този потребител. Автоматично блокираните IP-адреси не са показани. Вижте  [[Special:Ipblocklist|списъка на блокираните IP-адреси]] за текущото състояние на блокиранията.',
'unblocklogentry'       => 'отблокиране на „$1“',
'range_block_disabled'  => 'Възможността на администраторите да задават интервали при IP-адресите е изключена.',
'ipb_expiry_invalid'    => 'Невалиден срок на изтичане.',
'ip_range_invalid'      => 'Невалиден интервал за IP-адреси.',
'proxyblocker'  => 'Блокировач на проксита',
'proxyblockreason'      => 'Вашият IP-адрес беше блокиран, тъй като е отворено прокси. Моля, свържете се с Вашия доставчик на интернет и го информирайте за този сериозен проблем в сигурността.',
'proxyblocksuccess'     => 'Готово.',
'sorbs'         => 'SORBS DNSBL',
'sorbsreason'   => 'Вашият IP-адрес е записан като отворено прокси в [http://www.sorbs.net SORBS] DNSBL.',
'sorbs_create_account_reason' => 'Вашият IP-адрес е записан като отворено прокси в [http://www.sorbs.net SORBS] DNSBL. Не можете да създадете сметка.',

# Developer tools
#
'lockdb'    => 'Заключване на базата от данни',
'unlockdb'    => 'Отключване на базата от данни',
'lockdbtext'  => 'Заключването на базата от данни ще попречи на всички потребители да редактират страници, да сменят своите настройки, да редактират своите списъци за наблюдение и на всички други техни действия, изискващи промени в базата от данни.
Моля, потвърдете, че искате точно това и ще отключите базата от данни,
когато привършите с работата по подръжката.',
'unlockdbtext'  => 'Отключването на базата от данни ще възстанови способността на потребителите да редактират страници, да сменят своите настройки, да редактират своите списъци за наблюдение и изпълнението на всички други действия, изискващи промени в базата от данни.
Моля, потвърдете, че искате точно това.',
'lockconfirm'  => 'Да, наистина искам да заключа базата от данни.',
'unlockconfirm'  => 'Да, наистина искам да отключа базата от данни.',
'lockbtn'    => 'Заключване на базата от данни',
'unlockbtn'    => 'Отключване на базата от данни',
'locknoconfirm' => 'Не сте отметнали кутийката за потвърждение.',
'lockdbsuccesssub' => 'Заключването на базата от данни беше успешно',
'unlockdbsuccesssub' => 'Отключването на базата от данни беше успешно',
'lockdbsuccesstext' => 'Базата от данни на {{SITENAME}} беше заключена.
<br />Не забравяйте да отключите базата от данни, когато привършите с работата по поддръжката.',
'unlockdbsuccesstext' => 'Базата от данни на {{SITENAME}} беше отключена.',

# Make sysop
'makesysoptitle'        => 'Превръщане на потребител в администратор',
'makesysoptext'         => 'Този формуляр се използва от бюрократи за превръщане на обикновени потребители в администратори.

Въведете името на потребителя в полето и натиснете бутона, за да направите съответния потребител администратор',
'makesysopname'         => 'Име на потребителя:',
'makesysopsubmit'       => 'Превръщане на потребителя в администратор',
'makesysopok'           => '<b>Потребителят „$1“ беше направен администратор</b>.',
'makesysopfail'         => '<b>Потребителят „$1“ не беше направен администратор. (Правилно ли въведохте името?)</b>',
'setbureaucratflag' => 'Вдигане на флага „бюрократ“',
'setstewardflag'    => 'Вдигане на флага „стюард“',
'rightslogtext'         => 'Това е дневник на промените на потребителски права.',
'rights'                => 'Права:',
'set_user_rights'       => 'Даване на потребителски права',
'user_rights_set'       => '<b>Потребителските права на „$1“ са променени</b>',
'set_rights_fail'       => '<b>Потребителските права на „$1“ не бяха променени. (Правилно ли въведохте името?)</b>',
'makesysop'             => 'Превръщане на потребител в администратор',
'already_sysop'         => 'Този потребител беше вече администратор',
'already_bureaucrat'    => 'Този потребител беше вече бюрократ',
'already_steward'       => 'Този потребител беше вече стюард',

# Move page
#
'movepage'    => 'Преместване на страница',
'movepagetext'  => "Посредством долния формуляр можете да преименувате страница, премествайки цялата й история на новото име. Старото заглавие ще се превърне в пренасочваща страница.
Препратките към старата страница няма да бъдат променени; затова проверете за двойни или невалидни пренасочвания.
Вие сами би трябвало да се убедите в това, дали препратките продължават да сочат там, където се предполага.

Страницата '''няма''' да бъде преместена, ако вече съществува страница с новото име, освен ако е празна или пренасочване и няма редакционна история.

'''ВНИМАНИЕ!'''
Това може да е голяма и неочаквана промяна за известна страница. Уверете се, че разбирате последствията, преди да продължите.",
'movepagetalktext' => 'Съответната дискусионна страница, ако съществува, ще бъде автоматично преместена заедно с нея, \'\'\'освен ако:\'\'\'
* не местите страницата от едно именно пространство в друго,
* вече съществува непразна дискусионна страница с това име или
* не сте отметнали долната кутийка.

В тези случаи, ако желаете, ще е необходимо да преместите страницата ръчно.',
'movearticle'  => 'Преместване на страница',
'movenologin'  => 'Не сте влезли',
'movenologintext' => 'Необходимо е да [[Special:Userlogin|влезете]], за да може да премествате страници.',
'newtitle'    => 'Към ново заглавие',
'movepagebtn'  => 'Преместване',
'pagemovedsub'  => 'Преместването беше успешно',
'pagemovedtext' => 'Страницата „[[$1]]“ беше преместена под името „[[$2]]“.',
'articleexists' => 'Вече съществува страница с това име или името, което сте избрали, е невалидно. Моля, изберете друго име.',
'talkexists'  => "'''Страницата беше успешно преместена, но без съответната дискусионна страница, защото под новото име има една съществуваща. Моля, обединете ги ръчно.'''",
'movedto'    => 'преместена като',
'movetalk'    => 'Преместване и на дискусионната страница, ако е приложимо.',
'talkpagemoved' => 'Съответната дискусионна страница също беше преместена.',
'talkpagenotmoved' => 'Съответната дискусионна страница <strong>не</strong> беше преместена.',
'1movedto2' => '„$1“ преместена като „$2“',
'1movedto2_redir' => '„$1“ преместена като „$2“ (върху пренасочване)',
'movelogpage' => 'Дневник на преместванията',
'movelogpagetext' => 'По-долу е показан списък на преместванията.',
'movereason'    => 'Причина',
'revertmove'    => 'връщане',
'delete_and_move' => 'Изтриване и преместване',
'delete_and_move_text'  =>
'== Наложително изтриване ==

Целевата страница „[[$1]]“ вече съществува. Искате ли да я изтриете, за да освободите място за преместването?',
'delete_and_move_reason' => 'Изтрита, за да се освободи място за преместване',
'selfmove' => 'Страницата не може да бъде преместена, тъй като целевото име съвпада с първоначалното й заглавие.',
'immobile_namespace' => 'Целевото заглавие е от специален тип. Не е възможно местенето на страници в това именно пространство.',

# Export
'export'     => 'Изнасяне на страници',
'exporttext'    => "Тук можете да изнесете като XML текста и историята на една или повече страници. Получените данни можете да:
* вмъкнете в друг сайт, използващ софтуера на МедияУики,
* обработвате или
* просто запазите за лично ползване.

За да изнесете няколко страници, въвеждайте всяко ново заглавие на '''нов ред'''. След това изберете дали искате само текущата версия (заедно с информация за последната редакция) или всички версии (заедно с текущата) на страницата.

Ако желаете само текущата версия, бихте могли да използвате препратка от вида [[Special:Export/България]] за страницата [[България]].",
'exportcuronly' => 'Включване само на текущата версия, а не на цялата история',

# Namespace 8 related

'allmessages'   => 'Системни съобщения',
'allmessagesname' => 'Име',
'allmessagesdefault' => 'Текст по подразбиране',
'allmessagescurrent' => 'Текущ текст',
'allmessagestext' => 'Това е списък на системните съобщения, намиращи се в именното пространство „МедияУики“',
'allmessagesnotsupportedUI' => 'Текущо избраният език за интерфейса <b>$1</b> не се поддържа от <em>Специални:AllMessages</em> на сайта.',
'allmessagesnotsupportedDB' => 'Възможността за използване на страници от именното пространство „МедияУики“ за генериране на интерфейсните съобщения е изключена (<code>LocalSettings.php: wgUseDatabaseMessages = false</code>).',
# Thumbnails
'thumbnail-more' => 'Увеличаване',
'missingimage'   => '<b>Липсваща картинка</b><br /><i>$1</i>',
'filemissing'    => 'Липсващ файл',

# Special:Import
'import'        => 'Внасяне на страници',
'importinterwiki' => 'Внасяне чрез Трансуики',
'importtext'    => 'Моля, изнесете файла от изходното уики, използвайки инструмента „Special:Export“, съхранете го на Вашия диск и го качете тук.',
'importfailed'  => 'Внасянето беше неуспешно: $1',
'importnotext'  => 'Празно',
'importsuccess' => 'Внасянето беше успешно!',
'importhistoryconflict' => 'Съществува версия от историята, която си противоречи с тази (възможно е страницата да е била вече внесена)',
# TODO
'importnosources' => 'No transwiki import sources have been defined and direct history uploads are disabled.',

# Keyboard access keys for power users
'accesskey-search' => 'f',
'accesskey-minoredit' => 'i',
'accesskey-save' => 's',
'accesskey-preview' => 'p',
'accesskey-diff' => 'v',
'accesskey-compareselectedversions' => 'v',

# tooltip help for the main actions
'tooltip-search' => 'Претърсване на {{SITENAME}} [alt-f]',
'tooltip-minoredit' => 'Отбелязване на промяната като малка [alt-i]',
'tooltip-save' => 'Съхраняване на промените [alt-s]',
'tooltip-preview' => 'Предварителен преглед, моля, използвайте го преди да съхраните! [alt-p]',
'tooltip-diff' => 'Показване на направените от Вас промени по текста [alt-d]',
'tooltip-compareselectedversions' => 'Показване на разликите между двете избрани версии на страницата [alt-v]',
'tooltip-watch' => 'Добавяне на страницата към списъка Ви за наблюдение [alt-w]',

# stylesheets
'Monobook.css' => '/* чрез редактиране на този файл можете да промените облика Monobook */',
#'Monobook.js' => '/* чрез редактиране на този файл можете да добавяте функции на Javascript за облика Monobook */',

# TODO: превод
# Metadata
'nodublincore' => 'Dublin Core RDF metadata disabled for this server.',
'nocreativecommons' => 'Creative Commons RDF metadata disabled for this server.',
'notacceptable' => 'Сървърът не може да предостави данни във формат, който да се разпознава от клиента Ви.',

# Attribution
'anonymous' => 'Анонимен потребител(и) на {{SITENAME}}',
'siteuser'  => 'потребители на {{SITENAME}} $1',
'lastmodifiedby' => 'Последната промяна на страницата е извършена от $2 на $1.',
'and' => 'и',
'othercontribs' => 'Основаващо се върху работа на $1.',
'others' => 'други',
'siteusers' => 'потребителите на {{SITENAME}} $1',
'creditspage' => 'Библиография и източници',//'Page credits',
'nocredits' => 'Няма въведени източници или библиография',//'There is no credits info available for this page.',

# Spam protection
'spamprotectiontitle' => 'Филтър за защита от спам',
'spamprotectiontext' => 'Страницата, която искахте да съхраните, беше блокирана от филтъра против спам. Това обикновено е причинено от препратка към външен сайт.',
'spamprotectionmatch' => 'Следният текст предизвика включването на филтъра: $1',
'subcategorycount' => 'Тази категория има $1 подкатегории.',
'categoryarticlecount' => 'Тази категория съдържа $1 страници.',
'listingcontinuesabbrev' => ' продълж.',

# Info page
'infosubtitle' => 'Информация за страницата',
'numedits' => 'Брой редакции (страница):',
'numtalkedits' => 'Брой редакции (дискусионна страница):',
'numwatchers' => 'Брой наблюдатели:',
'numauthors' => 'Брой различни автори (страница):',
'numtalkauthors' => 'Брой различни автори (дискусионна страница):',

# Math
'mw_math_png'    => 'Използване винаги на PNG',
'mw_math_simple' => 'HTML при опростен TeX, иначе PNG',
'mw_math_html'   => 'HTML по възможност, иначе PNG',
'mw_math_source' => 'Оставяне като TeX (за текстови браузъри)',
'mw_math_modern' => 'Препоръчително за нови браузъри',
'mw_math_mathml' => 'MathML по възможност (експериментално)',

# Patrolling
'markaspatrolleddiff'   => 'Отбелязване като проверена версия',//'Mark as patrolled',
'markaspatrolledtext'   => 'Отбелязване на версията като проверена',//'Mark this article as patrolled',
'markedaspatrolled'     => 'Проверена версия', //'Marked as patrolled',
'markedaspatrolledtext' => 'Избраната версия беше отбелязана като проверена.',//'The selected revision has been marked as patrolled.',
'rcpatroldisabled'      => 'Патрулът е деактивиран', //'Recent Changes Patrol disabled',
'rcpatroldisabledtext'  => 'Патрулът на последните промени е деактивиран',//'The Recent Changes Patrol feature is currently disabled.',


# Monobook.js: tooltips and access keys for monobook
'Monobook.js' => '
 /* чрез редактиране на този файл можете да промените някои неща на Javascript за облика Monobook */
 /* tooltips and access keys */
 var ta = new Object();
 ta[\'pt-userpage\'] = new Array(\'.\',\'Вашата потребителска страница\');
 ta[\'pt-anonuserpage\'] = new Array(\'.\',\'Потребителската страница за адреса, от който редактирате\');
 ta[\'pt-mytalk\'] = new Array(\'n\',\'Вашата дискусионна страница\');
 ta[\'pt-anontalk\'] = new Array(\'n\',\'Дискусия относно редакциите от този адрес\');
 ta[\'pt-preferences\'] = new Array(\'\',\'Вашите настройки\');
 ta[\'pt-watchlist\'] = new Array(\'l\',\'Списък на страници, чиито промени сте избрали да наблюдавате\');
 ta[\'pt-mycontris\'] = new Array(\'y\',\'Списък на Вашите приноси\');
 ta[\'pt-login\'] = new Array(\'o\',\'В момента не сте влезли. Насърчаваме Ви да влезете, въпреки че не е задължително.\');
 ta[\'pt-anonlogin\'] = new Array(\'o\',\'Насърчаваме Ви да влезете, въпреки че не е задължително.\');
 ta[\'pt-logout\'] = new Array(\'o\',\'Излизане от {{SITENAME}}\');
 ta[\'ca-talk\'] = new Array(\'t\',\'Беседа относно страницата\');
 ta[\'ca-edit\'] = new Array(\'e\',\'Можете да редактирате страницата. Моля, използвайте бутона за предварителен преглед преди да съхраните.\');
 ta[\'ca-addsection\'] = new Array(\'+\',\'Добавяне на коментар към страницата\');
 ta[\'ca-viewsource\'] = new Array(\'e\',\'Страницата е защитена. Можете да разгледате изходния й код.\');
 ta[\'ca-history\'] = new Array(\'h\',\'Предишни версии на страницата\');
 ta[\'ca-protect\'] = new Array(\'=\',\'Защитаване на страницата\');
 ta[\'ca-delete\'] = new Array(\'d\',\'Изтриване на страницата\');
 ta[\'ca-undelete\'] = new Array(\'d\',\'Възстановяване на изтрити редакции на страницата\');
 ta[\'ca-move\'] = new Array(\'m\',\'Преместване на страницата\');
 ta[\'ca-watch\'] = new Array(\'w\',\'Добавяне на страницата към списъка Ви за наблюдение\');
 ta[\'ca-unwatch\'] = new Array(\'w\',\'Премахване на страницата от списъка Ви за наблюдение\');
 ta[\'search\'] = new Array(\'f\',\'Претърсване на {{SITENAME}}\');
 ta[\'p-logo\'] = new Array(\'\',\'Началната страница\');
 ta[\'n-mainpage\'] = new Array(\'z\',\'Началната страница\');
 ta[\'n-portal\'] = new Array(\'\',\'Информация за проекта\');
 ta[\'n-currentevents\'] = new Array(\'\',\'Информация за текущите събития по света\');
 ta[\'n-recentchanges\'] = new Array(\'r\',\'Списък на последните промени в {{SITENAME}}\');
 ta[\'n-randompage\'] = new Array(\'x\',\'Случайна страница\');
 ta[\'n-help\'] = new Array(\'\',\'Помощната страница\');
 ta[\'n-sitesupport\'] = new Array(\'\',\'Подкрепете {{SITENAME}}\');
 ta[\'t-whatlinkshere\'] = new Array(\'j\',\'Списък на всички страници, сочещи насам\');
 ta[\'t-recentchangeslinked\'] = new Array(\'k\',\'Последните промени на страници, сочени от тази страница\');
 ta[\'feed-rss\'] = new Array(\'\',\'RSS feed за страницата\');
 ta[\'feed-atom\'] = new Array(\'\',\'Atom feed за страницата\');
 ta[\'t-contributions\'] = new Array(\'\',\'Показване на приносите на потребителя\');
 ta[\'t-emailuser\'] = new Array(\'\',\'Изпращане на писмо на потребителя\');
 ta[\'t-upload\'] = new Array(\'u\',\'Качване на файлове\');
 ta[\'t-specialpages\'] = new Array(\'q\',\'Списък на всички специални страници\');
 ta[\'ca-nstab-main\'] = new Array(\'c\',\'Преглед на основната страница\');
 ta[\'ca-nstab-user\'] = new Array(\'c\',\'Преглед на потребителската страница\');
 ta[\'ca-nstab-media\'] = new Array(\'c\',\'Преглед на медийната страница\');
 ta[\'ca-nstab-special\'] = new Array(\'\',\'Това е специална страница, която не може да се редактира.\');
 ta[\'ca-nstab-project\'] = new Array(\'c\',\'Преглед на проектната страница\');
 ta[\'ca-nstab-image\'] = new Array(\'c\',\'Преглед на страницата на файла\');
 ta[\'ca-nstab-mediawiki\'] = new Array(\'c\',\'Преглед на системното съобщение\');
 ta[\'ca-nstab-template\'] = new Array(\'c\',\'Преглед на шаблона\');
 ta[\'ca-nstab-help\'] = new Array(\'c\',\'Преглед на помощната страница\');
 ta[\'ca-nstab-category\'] = new Array(\'c\',\'Преглед на категорийната страница\');',

# image deletion
'deletedrevision' => 'Изтрита стара версия $1.',

# browsing diffs
'previousdiff' => '← Предишна разлика',
'nextdiff' => 'Следваща разлика →',

'imagemaxsize' => 'Ограничаване на картинките на описателните им страници до:',
'thumbsize'     => 'Размери на миникартинките:',
'showbigimage' => 'Сваляне на версия с високо качество ($1x$2, $3 КБ)',
'newimages' => 'Галерия на новите файлове',
'showhidebots' => '($1 на ботове)',
'noimages'  => 'Няма нищо.',

# short names for language variants used for language conversion links.
# to disable showing a particular link, set it to 'disable', e.g.
# 'variantname-zh-sg' => 'disable',
'variantname-zh-cn' => 'cn',
'variantname-zh-tw' => 'tw',
'variantname-zh-hk' => 'hk',
'variantname-zh-sg' => 'sg',
'variantname-zh' => 'zh',

# labels for User: and Title: on Special:Log pages
'specialloguserlabel' => 'Потребител:',
'speciallogtitlelabel' => 'Заглавие:',

'passwordtooshort' => 'Паролата Ви е прекалено къса: трябва да съдържа поне $1 знака.',

# Media Warning
# TODO Превод
'mediawarning' => '\'\'\'Внимание\'\'\': This file may contain malicious code, by executing it your system may be compromised.
<hr />',

'fileinfo' => '$1 КБ, MIME type: <code>$2</code>',

# Metadata
'metadata' => 'Метаданни',
# TODO Превод
# Exif tags
'exif-imagewidth' =>'Ширина',
'exif-imagelength' =>'Височина',
'exif-artist' =>'Автор',


# external editor support
'edit-externally' => 'Редактиране на файла чрез външно приложение',
'edit-externally-help' => 'За повече информация прегледайте [http://meta.wikimedia.org/wiki/Help:External_editors указанията за настройките].',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'всички',
'imagelistall' => 'всички',
'watchlistall1' => 'всички',
'watchlistall2' => 'всички',
'namespacesall' => 'всички',

# TODO
# E-mail address confirmation
'confirmemail_invalid' => 'Грешен код за потвърждение. Вероятно кодът за потвърждение е остарял.',
'confirmemail_success' => 'Адресът ви за електронна поща беше потвърден. Вече можете да влезете и да се наслаждавате на уикито.',
'confirmemail_loggedin' => 'Адресът ви за електронна поща беше потвърден.',

# Inputbox extension, may be useful in other contexts as well
'tryexact' => 'Пълно и точно съвпадение',
'searchfulltext' => 'Претърсване на целия текст',
'createarticle' => 'Създаване на статия',

# Trackbacks
'trackbackbox' => '<div id="mw_trackbacks">
Trackbacks for this article:<br />
$1
</div>',
'trackbackremove' => ' ([$1 Изтриване])',
#'trackbacklink' => 'Trackback',
#'trackbackdeleteok' => 'The trackback was successfully deleted.',

# delete conflict

'deletedwhileediting' => 'Внимание: Страницата е била изтрита, откакто сте започнали да я редактирате!',
'confirmrecreate' => 'Потребител [[Потребител:$1|$1]] ([[Потребител беседа:$1|беседа]]) е изтрил страницата, откакто сте започнали да я редактирате, давайки следното обяснение:
: \'\'$2\'\'
Моля, потвърдете, че наистина желаете да създадете страницата отново.',
'recreate' => 'Ново създаване',
'tooltip-recreate' => '',

'unit-pixel' => 'px',
);


?>
