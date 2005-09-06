<?php
/** Bulgarian (Български)
 *
 * @package MediaWiki
 * @subpackage Language
 */

/* private */ $wgNamespaceNamesBg = array(
	NS_MEDIA            => 'Медия',
	NS_SPECIAL          => 'Специални',
	NS_MAIN             => '',
	NS_TALK             => 'Беседа',
	NS_USER             => 'Потребител',
	NS_USER_TALK        => 'Потребител_беседа',
	NS_PROJECT          => $wgMetaNamespace,
	NS_PROJECT_TALK     => $wgMetaNamespace . '_беседа',
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

/* private */ $wgQuickbarSettingsBg = array(
	'Без меню', 'Неподвижно вляво', 'Неподвижно вдясно', 'Плаващо вляво', 'Плаващо вдясно'
);

/* private */ $wgSkinNamesBg = array(
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

# Validation types
$wgValidationTypesBg = array (
	'0' => 'Стил|Ужасна|Чудесна|5',//'Style|Awful|Awesome|5',
	'1' => 'Законност|Незаконна|Законна|5',//'Legal|Illegal|Legal|5',
	'2' => 'Пълнота|Недоразвита|Обширна и подробна|5',//'Completeness|Stub|Extensive|5',
	'3' => 'Факти|Съвсем необосновани|Сериозно обосновани|5',//'Facts|Wild guesses|Solid as a rock|5',
	'4' => 'Подходяща за 1.0 (печат)|Не|Да|2',//'Suitable for 1.0 (paper)|No|Yes|2',
	'5' => 'Подходяща за 1.0 (компакт-диск)|Не|Да|2',//'Suitable for 1.0 (CD)|No|Yes|2'
) ;

/* private */ $wgDateFormatsBg = array();

/* private */ $wgBookstoreListBg = array(
	'books.bg'       => 'http://www.books.bg/ISBN/$1',
);

/* private */ $wgMagicWordsBg = array(
#   ID                                 CASE  SYNONYMS
	MAG_REDIRECT             => array( 0, '#redirect', '#пренасочване', '#виж' ),
	MAG_NOTOC                => array( 0, '__NOTOC__', '__БЕЗСЪДЪРЖАНИЕ__' ),
	MAG_FORCETOC             => array( 0, '__FORCETOC__', '__СЪССЪДЪРЖАНИЕ__' ),
	MAG_TOC                  => array( 0, '__TOC__', '__СЪДЪРЖАНИЕ__'      ),
	MAG_NOEDITSECTION        => array( 0, '__NOEDITSECTION__', '__БЕЗ_РЕДАКТИРАНЕ_НА_РАЗДЕЛИ__' ),
	MAG_START                => array( 0, '__START__', '__НАЧАЛО__'         ),
	MAG_CURRENTMONTH         => array( 1, 'CURRENTMONTH', 'ТЕКУЩМЕСЕЦ'      ),
	MAG_CURRENTMONTHNAME     => array( 1, 'CURRENTMONTHNAME', 'ТЕКУЩМЕСЕЦИМЕ' ),
	MAG_CURRENTMONTHNAMEGEN  => array( 1, 'CURRENTMONTHNAMEGEN', 'ТЕКУЩМЕСЕЦИМЕРОД' ),
	MAG_CURRENTMONTHABBREV   => array( 1, 'CURRENTMONTHABBREV', 'ТЕКУЩМЕСЕЦСЪКР'    ),
	MAG_CURRENTDAY           => array( 1, 'CURRENTDAY', 'ТЕКУЩДЕН'            ),
	MAG_CURRENTDAYNAME       => array( 1, 'CURRENTDAYNAME', 'ТЕКУЩДЕНИМЕ'     ),
	MAG_CURRENTYEAR          => array( 1, 'CURRENTYEAR', 'ТЕКУЩАГОДИНА'       ),
	MAG_CURRENTTIME          => array( 1, 'CURRENTTIME', 'ТЕКУЩОВРЕМЕ'        ),
	MAG_NUMBEROFARTICLES     => array( 1, 'NUMBEROFARTICLES', 'БРОЙСТАТИИ'    ),
	MAG_NUMBEROFFILES        => array( 1, 'NUMBEROFFILES', 'БРОЙФАЙЛОВЕ'      ),
	MAG_PAGENAME             => array( 1, 'PAGENAME', 'СТРАНИЦА'              ),
	MAG_PAGENAMEE            => array( 1, 'PAGENAMEE', 'СТРАНИЦАИ'            ),
	MAG_NAMESPACE            => array( 1, 'NAMESPACE', 'ИМЕННОПРОСТРАНСТВО'   ),
	MAG_SUBST                => array( 0, 'SUBST:', 'ЗАМЕСТ:', 'З'            ),
	MAG_MSGNW                => array( 0, 'MSGNW:', 'СЪОБЩNW:', 'Н'           ),
	MAG_END                  => array( 0, '__END__', '__КРАЙ__'            ),
	MAG_IMG_THUMBNAIL        => array( 1, 'thumbnail', 'thumb', 'мини'     ),
	MAG_IMG_MANUALTHUMB      => array( 1, 'thumbnail=$1', 'thumb=$1', 'мини=$1'),
	MAG_IMG_RIGHT            => array( 1, 'right', 'вдясно', 'дясно', 'д'  ),
	MAG_IMG_LEFT             => array( 1, 'left', 'вляво', 'ляво', 'л'     ),
	MAG_IMG_NONE             => array( 1, 'none', 'н'                  ),
	MAG_IMG_WIDTH            => array( 1, '$1px', '$1пкс' , '$1п'         ),
	MAG_IMG_CENTER           => array( 1, 'center', 'centre', 'център', 'центр', 'ц' ),
	MAG_IMG_FRAMED           => array( 1, 'framed', 'enframed', 'frame', 'рамка', 'врамка' ),
	MAG_INT                  => array( 0, 'INT:'                   ),
	MAG_SITENAME             => array( 1, 'SITENAME', 'ИМЕНАСАЙТА'       ),
	MAG_NS                   => array( 0, 'NS:', 'ИП:'                    ),
	MAG_LOCALURL             => array( 0, 'LOCALURL:', 'ЛОКАЛЕНАДРЕС:'    ),
	MAG_LOCALURLE            => array( 0, 'LOCALURLE:', 'ЛОКАЛЕНАДРЕСИ:'  ),
	MAG_SERVER               => array( 0, 'SERVER', 'СЪРВЪР'       ),
	MAG_SERVERNAME           => array( 0, 'SERVERNAME', 'ИМЕНАСЪРВЪРА'    ),
	MAG_SCRIPTPATH           => array( 0, 'SCRIPTPATH', 'ПЪТДОСКРИПТА'    ),
	MAG_GRAMMAR              => array( 0, 'GRAMMAR:', 'ГРАМАТИКА:' ),
	MAG_NOTITLECONVERT       => array( 0, '__NOTITLECONVERT__', '__NOTC__'),
	MAG_NOCONTENTCONVERT     => array( 0, '__NOCONTENTCONVERT__', '__NOCC__'),
	MAG_CURRENTWEEK          => array( 1, 'CURRENTWEEK', 'ТЕКУЩАСЕДМИЦА'),
	MAG_CURRENTDOW           => array( 1, 'CURRENTDOW'             ),
	MAG_REVISIONID           => array( 1, 'REVISIONID'             ),
);

#-------------------------------------------------------------------
# Default messages
#-------------------------------------------------------------------
# Allowed characters in keys are: A-Z, a-z, 0-9, underscore (_) and
# hyphen (-). If you need more characters, you may be able to change
# the regex in MagicWord::initRegex

/* private */ $wgAllMessagesBg = array(
# The navigation toolbar, int: is used here to make sure that the appropriate
# messages are automatically pulled from the user-selected language file.

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
'tog-enotifwatchlistpages'      => 'Send me an email on page changes',
'tog-enotifusertalkpages'       => 'Send me an email when my user talk page is changed',
'tog-enotifminoredits'          => 'Send me an email also for minor edits of pages',
'tog-enotifrevealaddr'          => 'Reveal my email address in notification mails',
'tog-shownumberswatching'       => 'Show the number of watching users',
'tog-showupdated'               => 'Show update marker ',
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
'linktrail'   => '/^((?:[a-z]|а|б|в|г|д|е|ж|з|и|й|к|л|м|н|о|п|р|с|т|у|ф|х|ц|ч|ш|щ|ъ|ы|ь|э|ю|я)+)(.*)$/sD',
'linkprefix'  => '/^(.*?)([a-zA-Z\x80-\xff]+)$/sD',
'mainpage'    => 'Начална страница',
'mainpagetext'  => 'Уики-системата беше успешно инсталирана.',
'mainpagedocfooter' => 'Моля, разгледайте [http://meta.wikimedia.org/wiki/MediaWiki_i18n документацията] и [http://meta.wikimedia.org/wiki/MediaWiki_User%27s_Guide ръководството] за подробна информация относно МедияУики.

Актуална версия на българския езиков файл можете да откриете на [http://meta.wikimedia.org/wiki/LanguageBg.php Мета].',
# БЕЛЕЖКА: За да изключите „Портала за общността“ в заглавните препратки,
# въведете 'portal' => '-'
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
'metadata_page' => 'Project:Метаданни',
 
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
'go'    => 'Отваряне',
'history'    => 'История',
'history_short' => 'История',
'updatedmarker' => '<span class="updatedmarker">&nbsp;има промяна (от последното ми влизане)&nbsp;</span>',
'info_short'    => 'Информация',
'printableversion' => 'Версия за печат',
'permalink'     => 'Постоянна препратка',
'print' => 'Печат',
'edit' => 'Редактиране',
'editthispage'  => 'Редактиране',
'delete' => 'Изтриване',
'deletethispage' => 'Изтриване',
'undelete_short1' => 'Възстановяване на една редакция',
'undelete_short' => 'Възстановяване на $1 редакции',
'protect' => 'Защита',
'protectthispage' => 'Защита',
'unprotect' => 'Сваляне на защитата',
'unprotectthispage' => 'Сваляне на защитата',
'verify' => 'Одобряване', # Verify
'verifythispage' => 'Одобряване на страницата',
'unverify' => 'Неодобряване', # Unverify
'unverifythispage' => 'Неодобряване на страницата',
'newpage' => 'Нова страница',
'talkpage'    => 'Дискусионна страница',
'specialpage' => 'Специална страница',
'personaltools' => 'Лични инструменти',
'postcomment'   => 'Оставяне на съобщение',
'addsection'   => '+',
'articlepage'  => 'Преглед на страница',
'subjectpage'  => 'Преглед на тема', # For compatibility
'talk' => 'Беседа',
'views' => 'Прегледи',
'toolbox' => 'Инструменти',
'userpage' => 'Потребителска страница',
'wikipediapage' => 'Основна страница',
'imagepage' =>   'Преглед на файл',
'viewtalkpage' => 'Преглед на беседа',
'otherlanguages' => 'На други езици',
'redirectedfrom' => '(пренасочване от $1)',
'lastmodified'  => 'Последна промяна на страницата: $1.',
'viewcount'    => 'Страницата е била преглеждана $1 пъти.',
'copyright'     => 'Съдържанието е достъпно при условията на $1.',
'poweredby'     => '{{SITENAME}} се задвижва от [http://www.mediawiki.org/ МедияУики], софтуер за уики с отворен код.',
'printsubtitle' => '(от {{SERVER}})',
'protectedpage' => 'Защитена страница',
'verifiedpage' => 'Страницата е в процес на одобрение от администраторите. Версиите, които все още не са одобрени, няма да бъдат показвани. <br/> Можете да разгледате текущата версия: $1',
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
'sitetitle'    => '{{SITENAME}}',
'pagetitle'    => '$1 — {{SITENAME}}',
'sitesubtitle' => '',
'retrievedfrom' => 'Взето от „$1“.',
'newmessages' => 'Имате $1.',
'newmessageslink' => 'нови съобщения',
'editsection' => 'редактиране',
'toc' => 'Съдържание',
'showtoc' => 'показване',
'hidetoc' => 'скриване',
'thisisdeleted' => 'Преглед или възстановяване на $1?',
'viewdeleted' => 'Преглед на $1?',
'restorelink1' => 'една изтрита редакция',
'restorelink' => '$1 изтрити редакции',
'feedlinks' => 'Feed:',
'sitenotice' => '', # равностойността на wgSiteNotice

# Short words for each namespace, by default used in the 'article' tab in monobook
'nstab-main' => 'Страница',
'nstab-user' => 'Потребител',
'nstab-media' => 'Медия',
'nstab-special' => 'Специална страница',
'nstab-wp' => 'Проект',
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
MySQL дава грешка „$3: $4“.'."\n",
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
Заявка: $2
',
'viewsource' => 'Защитена страница',
'protectedtext' => 'Страницата е затворена за промени. Съществуват няколко причини това да е така, моля, вижте [[Project:Защитена_страница]].

Можете да прегледате и копирате изходния код на страницата:',
'sqlhidden' => '(Заявка на SQL — скрита)',

# Login and logout pages
#
'logouttitle'  => 'Излизане на потребител',
'logouttext'  => 'Излязохте от системата.

Можете да продължите да използвате {{SITENAME}} анонимно или да влезете отново като друг потребител. Обърнете внимание, че някои страници все още ще се показват така, сякаш сте влезли, докато не изтриете кеш-паметта на браузъра.'."\n",

'welcomecreation' => '== Добре дошли, $1! ==

Вашата сметка беше успешно открита. Сега можете да промените настройките на {{SITENAME}} по Ваш вкус.',

'loginpagetitle' => 'Влизане в системата',
'yourname'    => 'Потребителско име',
'yourpassword'  => 'Парола',
'yourpasswordagain' => 'Въведете повторно парола',
'newusersonly'  => ' (само за нови потребители)',
'remembermypassword' => 'Запомняне на паролата',
'yourdomainname'       => 'Домейн',
# TODO
'externaldberror'      => 'There was either an external authentication database error or you are not allowed to update your external account.',
'loginproblem'  => '<b>Имаше проблем с влизането Ви.</b><br />Опитайте отново!',
'alreadyloggedin' => '<strong>$1, вече сте влезли в системата!</strong>'."\n",
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
'emailforlost'  => '* Полетата, обозначени със звездичка, са незадължителни.<br /> Посочването на електронна поща позволява на хората да се свържат с Вас, без да се налага да им съобщавате адреса си, а също може да се използва, за да Ви се изпрати нова парола, ако случайно забравите сегашната си.',
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
'loginend'   => '<p>За да се регистрирате, просто въведете потребителско име и парола (два пъти) и щракнете върху бутона „<b>Регистриране</b>“.</p>
<p>
Следващия път е достатъчно да попълните само първите две полета и да щракнете върху „<b>Влизане</b>“.</p>',
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
'talkpagetext' => '<!-- МедияУики:talkpagetext -->',
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
'note'      => '<strong>Забележка:</strong> ',
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
# REPLACE THE COPYRIGHT WARNING IF YOUR SITE ISN'T GFDL!
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
Моля, проверете адреса, който използвахте за достъп до страницата.'."\n",
'loadhist'        => 'Зареждане история на страницата',
'currentrev'      => 'Текуща версия',
'revisionasof'    => 'Версия от $1',
'revisionasofwithlink'  => 'Версия от $1; $2<br />$3 | $4',
'previousrevision'      => '←По-стара версия',
'nextrevision'          => 'По-нова версия→',
'currentrevisionlink'   => 'преглед на текущата версия',
'cur'             => 'тек',
'next'            => 'след',
'last'            => 'посл',
'orig'            => 'ориг',
'histlegend'      => '<i>Разлики:</i> Изберете версиите, които желаете да сравните, чрез превключвателите срещу тях и натиснете &lt;Enter&gt; или бутона за сравнение.<br/>
<i>Легенда:</i> (<b>тек</b>) = разлика с текущата версия, (<b>посл</b>) = разлика с предишната версия, <b>м</b>&nbsp;=&nbsp;малка промяна',
'history_copyright' => '-',
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
'searchquery'  => 'За заявка „$1“',
'badquery'    => 'Лошо формулирана заявка за търсене',
'badquerytext'  => 'Вашата заявка не можа да бъде обработена.
Вероятно сте се опитали да търсите дума с по-малко от три букви, което все още не се поддържа.
Възможно е и да сте сгрешили в изписването на израза, например: „риба и и везни“.
Моля, опитайте с нова заявка.',
'matchtotals'  => 'Заявката „$1“ отговаря на $2 заглавия на страници и на текста на $3 страници.',
'nogomatch' => 'В {{SITENAME}} не съществува страница с това заглавие. Можете да я \'\'\'[[$1|създадете]]\'\'\'.',
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
'prefslogintext' => 'Влязохте като „$1“.
Вашият вътрешен номер e $2.

Вижте [[Project:Настройки|помощта за потребителските настройки]], за да се ориентирате при избора.',
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
'emailflag'    => 'Забраняване на писма от други потребители',
'defaultns'    => 'Търсене в тези именни пространства по подразбиране:',
'default'      => 'по подразбиране',
'files'        => 'Файлове',

# User levels special page

# switching pan
'groups-lookup-group' => 'Управляване на групови права',#Manage group rights
'groups-group-edit' => 'Съществуващи групи: ',
'editgroup' => 'Редактиране на група',
'addgroup' => 'Добавяне на група',

'userrights-lookup-user' => 'Управляване на потребителските групи',#Manage user groups
'userrights-user-editname' => 'Въведете потребителско име: ',#Enter a username: 
'editusergroup' => 'Редактиране на потребителските групи',#Edit User Groups

# group editing
'groups-editgroup' => 'Редактиране на група',
'groups-addgroup' => 'Добавяне на група',
# TODO
'groups-editgroup-preamble' => 'If the name or description starts with a colon, the remainder will be treated as a message name, and hence the text will be localised using the MediaWiki namespace',
'groups-editgroup-name' => 'Име на група: ',
'groups-editgroup-description' => 'Определение на група (до 255 знака):<br />',
'savegroup' => 'Съхранение на група',
'groups-tableheader'        => 'ID || Име || Описание || Права',
'groups-existing'           => 'Съществуващи групи',
'groups-noname'             => 'Please specify a valid group name',
'groups-already-exists'     => 'A group of that name already exists',
'addgrouplogentry'          => 'Added group $2',
'changegrouplogentry'       => 'Changed group $2',
'renamegrouplogentry'       => 'Renamed group $2 to $3',

# user groups editing
'userrights-editusergroup' => 'Редактиране на потребителските групи',#Edit user groups
'saveusergroups' => 'Съхранение на потребителските групи',#Save User Groups
'userrights-groupsmember' => 'Член на:',
'userrights-groupsavailable' => 'Групи на разположение:',
'userrights-groupshelp' => 'Изберете групите, към които искате той да бъде прибавен или от които да бъде премахнат. Неизбраните групи няма да бъдат променени. Можете да отизберете група чрез <CTRL> + ляв бутон на мишката',
'userrights-logcomment' => 'Смяна на груповата принадлежност от $1 към $2',

# Default group names and descriptions
#
'group-anon-name'       => 'Анонимен',
'group-anon-desc'       => 'Анонимни потребители',
'group-loggedin-name'   => 'Потребител',
'group-loggedin-desc'   => 'Обикновени влезли потребители',
'group-admin-name'      => 'Администратор',
'group-admin-desc'      => 'Доверените потребители могат да блокират потребители и да трият страници',
'group-bureaucrat-name' => 'Бюрократ',
'group-bureaucrat-desc' => 'Бюрократите могат да дават администраторски права',
'group-steward-name'    => 'Стюард',
'group-steward-desc'    => 'Неограничен достъп',

# Recent changes
#
'changes' => 'промени',
'recentchanges' => 'Последни промени',
'recentchanges-url' => 'Special:Recentchanges',
# This is the default text, and can be overriden by editing [[{{SITENAME}}:Recentchanges]]
'recentchangestext' => 'Проследяване на последните промени в {{SITENAME}}.

Легенда: <b>тек</b> = разлика на текущата версия,
<b>ист</b> = история на версиите, <b>м</b>&nbsp;=&nbsp;малка промяна, <b class="newpage">Н</b>&nbsp;=&nbsp;новосъздадена страница',
'rcloaderr'    => 'Зареждане на последни промени',
'rcnote'    => 'Показани са последните <strong>$1</strong> промени през последните <strong>$2</strong> дни.',
'rcnotefrom'  => 'Дадени са промените от <b>$2</b> (до <b>$1</b> показани).',
'rclistfrom'  => 'Показване на промени, като се започва от $1.',
'rclinks'    => 'Показване на последните $1 промени през последните $2 дни<br />$3',
'showhideminor' => '$1 на малки промени | $2 на ботове | $3 на влезли потребители | $4 на проверени редакции ',
'rchide'    => '$4; $1 на малки промени; $2 на вторични именни пространства; $3 на многократни редакции.',
'rcliu'      => '; $1 редакции от влезли потребители',
'diff'      => 'разл',
'hist'      => 'ист',
'hide'      => 'Скриване',
'show'      => 'Показване',
'tableform'    => 'таблица',
'listform'    => 'списък',
'nchanges'    => '$1 промени',
'minoreditletter' => 'м',
'newpageletter' => 'Н',
'sectionlink' => '→',
'number_of_watching_users_RCview'       => '[$1]',
'number_of_watching_users_pageview'     => '[$1 наблюдаващ(и) потребител(и)]',

# Upload
'upload'    => 'Качване',
'uploadbtn'    => 'Качване',
'uploadlink'  => 'Качване на файлове',
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
'ignorewarning'  => 'Съхраняване на файла въпреки предупрежденията.',
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
'shareddescriptionfollows' => '-',
'noimage'       => 'Не съществува файл с това име, можете $1.',
'noimage-linktext' => 'да го качите',
'uploadnewversion' => '[$1 Качване на нова версия на файла]',

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
'maintenance'    => 'Страница за поддръжка',
'maintnancepagetext'  => 'Тази страница съдържа няколко удобни инструмента за всекидневна поддръжка. Някои от тези функции доста натоварват базата от данни, затова, моля, не обновявайте страницата след всяка Ваша операция, ;-)',
'maintenancebacklink'  => 'Обратно към страницата за поддръжка',
'disambiguations'  => 'Пояснителни страници',
'disambiguationspage'  => 'Шаблон:Пояснение',
'disambiguationstext'  => 'Следните страници сочат към <i>пояснителна страница</i>. Вместо това те би трябвало да сочат към съответната тема.<br /> Страница се определя като „<i>пояснителна</i>“, ако към нея се сочи от $1.<br />Тук <i>не</i> са посочени препратки от други именни пространства.',
'doubleredirects'  => 'Двойни пренасочвания',
'doubleredirectstext' => 'Всеки ред съдържа препратки към първото и второто пренасочване, както и първия ред на текста на второто пренасочване, който обикновено посочва „<i>истинската</i>“ целева страница, към която първото пренасочване би трябвало да сочи.',
'brokenredirects'  => 'Невалидни пренасочвания',
'brokenredirectstext'  => 'Следните пренасочващи страници сочат към несъществуващи страници.',
'selflinks'    => 'Страници с препратки към себе си',
'selflinkstext'    => 'Следните страници правят препратки към себе се, което не би трябвало да е така.',
'mispeelings'           => 'Страници с правописни грешки',
'mispeelingstext'  => 'Следните страници съдържат основни правописни грешки, които са
изброени в $1. Правилните форми може да се посочат в скоби: (ето така).',
'mispeelingspage'       => 'Списък на основни правописни грешки',
'missinglanguagelinks'  => 'Липсващи езикови препратки',
'missinglanguagelinksbutton' => 'Търсене на липсващи езикови препратки за',
'missinglanguagelinkstext'   => 'Тези страници <i>не</i> сочат към техните копия в $1. Пренасочванията и подстраниците <i>не са</i> показани.',


# Miscellaneous special pages
#
'orphans'    => 'Страници сираци',
'geo'          => 'Гео-координати',
'validate'     => 'Validate page',
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
'randompage-url'=> 'Special:Random',
'shortpages'  => 'Кратки страници',
'longpages'    => 'Дълги страници',
'deadendpages'  => 'Задънени страници',
'listusers'    => 'Списък на потребителите',
'specialpages'  => 'Специални страници',
'spheading'    => 'Специални страници за всички потребители',
'restrictedpheading'    => 'Специални страници с ограничен достъп',
'protectpage'  => 'Защитена страница',
'recentchangeslinked' => 'Свързани промени',
'rclsub'    => '(на страници, сочени от „$1“)',
'debug'      => 'Отстраняване на грешки',
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
'rfcurl' =>  'http://www.ietf.org/rfc/rfc$1.txt',
'pubmedurl' =>  'http://www.ncbi.nlm.nih.gov/entrez/query.fcgi?cmd=Retrieve&db=pubmed&dopt=Abstract&list_uids=$1',
'alphaindexline' => 'от $1 до $2',
'version'       => 'Версия',
'log'           => 'Дневници',
'alllogstext'   => 'Смесено показване на дневниците на качванията, изтриванията, защитата, блокиранията и бюрократите.
Можете да ограничите прегледа, като изберете вид на дневника, потребителско име или определена страница.',

# Special:Allpages
'nextpage'          => 'Следваща страница ($1)',
'allpagesfrom'      => 'Показване на страниците, като се започва от:',
'allarticles'       => 'Всички страници',
'allnonarticles'    => 'Всички страници (без статии)',
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
'usermailererror'  => 'Пощенският обект даде грешка: ', # Mail object returned error:
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
'watchlistsub'  => '(за потребител „$1“)',
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
* [[Special:Watchlist/edit|Показване и редактиране на пълния списък]]
',
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
'wlshow'                => 'Показване',
'wlhide'                => 'Скриване',

'enotif_mailer'  => '{{SITENAME}} Notification Mailer',
'enotif_reset'   => 'Отбелязване на всички страници като посетени',
'enotif_newpagetext'=> 'Това е нова страница.',
'changed'        => 'променена',
'created'        => 'създадена',
'enotif_subject' 	=> '{{SITENAME}} page $PAGETITLE has been $CHANGEDORCREATED by $PAGEEDITOR',
'enotif_lastvisited' => 'Прегледайте $1 за всички промени след последното ви посещение.',
'enotif_body' => 'Dear $WATCHINGUSERNAME,

the {{SITENAME}} page $PAGETITLE has been $CHANGEDORCREATED on $PAGEEDITDATE by $PAGEEDITOR, see $PAGETITLE_URL for the current version.

$NEWPAGE

Editor\'s summary: $PAGESUMMARY $PAGEMINOREDIT

Contact the editor:
mail: $PAGEEDITOR_EMAIL
wiki: $PAGEEDITOR_WIKI

There will be no other notifications in case of further changes unless you visit this page.
You could also reset the notification flags for all your watched pages on your watchlist.

              Your friendly {{SITENAME}} notification system

--
To change your watchlist settings, visit
{{SERVER}}{{localurl:Special:Watchlist/edit}}

Feedback and further assistance:
{{SERVER}}{{localurl:Help:Contents}}',

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
'historywarning' => 'Внимание: Страницата, която ще изтриете, има история: ',
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

Последната редакция е на [[Потребител:$3|$3]] ([[Потребител беседа:$3|Беседа]]). ',
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
'undeletedtext'   => 'Страницата [[$1]] беше успешно възстановена.
Вижте [[Special:Log/delete|дневника на изтриванията]] за запис на последните изтривания и възстановявания.',

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
'contribs-showhideminor' => '$1 малки промени',

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
'noblockreason' => 'Необходимо е да посочите причина за блокирането.',
'blockipsuccesssub' => 'Блокирането беше успешно',
'blockipsuccesstext' => '„[[{{ns:Special}}:Contributions/$1|$1]]“ беше блокиран.
<br />Вижте [[{{ns:Special}}:Ipblocklist|списъка на блокираните IP-адреси]], за да прегледате всички блокирания.',
'unblockip'    => 'Отблокиране на потребител',
'unblockiptext'  => 'Използвайте долния формуляр, за да възстановите правото на писане на по-рано блокиран IP-адрес или потребител.',
'ipusubmit'    => 'Отблокиране на адреса',
'ipusuccess'  => '„[[$1]]“ беше отблокиран',
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
'ip_range_invalid'      => 'Невалиден интервал за IP-адреси.'."\n",
'proxyblocker'  => 'Блокировач на проксита',
'proxyblockreason'      => 'Вашият IP-адрес беше блокиран, тъй като е отворено прокси. Моля, свържете се с Вашия доставчик на интернет и го информирайте за този сериозен проблем в сигурността.',
'proxyblocksuccess'     => 'Готово.'."\n",
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
'bureaucratlog'         => 'Дневник на бюрократите',
'rightslogtext'         => 'Това е дневник на промените на потребителски права.',
'bureaucratlogentry'    => 'Смяна на груповата принадлежност на $1: от $2 към $3',
'rights'                => 'Права:',
'set_user_rights'       => 'Даване на потребителски права',
'user_rights_set'       => '<b>Потребителските права на „$1“ са променени</b>',
'set_rights_fail'       => '<b>Потребителските права на „$1“ не бяха променени. (Правилно ли въведохте името?)</b>',
'makesysop'             => 'Превръщане на потребител в администратор',
'already_sysop'         => 'Този потребител беше вече администратор',
'already_bureaucrat'    => 'Този потребител беше вече бюрократ',
'already_steward'       => 'Този потребител беше вече стюард',
 
#TODO: превод
# Validation
'val_yes' => 'Да',
'val_no' => 'Не',
'val_of' => '$1 of $2',
'val_revision' => 'Версия',
'val_time' => 'Време',
'val_user_stats_title' => 'Validation overview of user $1',
'val_my_stats_title' => 'My validation overview',
'val_list_header' => '<th>#</th><th>Тема</th><th>Range</th><th>Действие</th>',
'val_add' => 'Добавяне',
'val_del' => 'Изтриване',
'val_show_my_ratings' => 'Show my validations',
'val_revision_number' => 'Revision #$1',
'val_warning' => '<b>Никога <i>повече</i> не променяйте нещо тук без <i>изричното</i> съгласие на общността!</b>',
'val_rev_for' => 'Версии на $1',
'val_details_th_user' => 'Потребител $1',
'val_validation_of' => 'Validation of "$1"',
'val_revision_of' => 'Revision of $1',
'val_revision_changes_ok' => 'Your ratings have been stored!',
'val_rev_stats' => 'See the validation statistics for "$1" <a href="$2">here</a>',
'val_revision_stats_link' => 'подробности',
'val_iamsure' => 'Check this box if you really mean it!',
'val_details_th' => '<sub>Потребител</sub> \\ <sup>Тема</sup>',
'val_clear_old' => 'Clear my older validation data',
'val_merge_old' => 'Use my previous assessment where selected \'No opinion\'',
'val_form_note' => "'''Съвет:''' Merging your data means that for the article revision you select, all options where you have specified ''no opinion'' will be set to the value and comment of the most recent revision for which you have expressed an opinion. For example, if you want to change a single option for a newer revision, but also keep your other settings for this article in this revision, just select which option you intend to ''change'', and merging will fill in the other options with your previous settings.",
'val_noop' => 'Без мнение',
'val_topic_desc_page' => 'Project:Validation topics',
'val_votepage_intro' => 'Change this text <a href="{{SERVER}}{{localurl:MediaWiki:Val_votepage_intro}}">here</a>!',
'val_percent' => '<b>$1%</b><br />($2 от $3 точки<br />от $4 потребители)',
'val_percent_single' => '<b>$1%</b><br />($2 от $3 точки<br />от един потребител)',
'val_total' => 'Общо',
'val_version' => 'Версия',
'val_tab' => 'Валидиране',
'val_this_is_current_version' => 'това е последната версия',
'val_version_of' => 'Версия от $1' ,
'val_table_header' => '<tr><th>Клас</th>$1<th colspan="4">Мнение</th>$1<th>Коментар</th></tr>'."\n",
'val_stat_link_text' => 'Validation statistics for this article',
'val_view_version' => 'Преглед на версията',
'val_validate_version' => 'Валидиране на версията',
'val_user_validations' => 'Потребителят has validated $1 страници.',
'val_no_anon_validation' => 'Необходимо е да [[Special:Userlogin|влезете]], за да може да валидирате статия.',
'val_validate_article_namespace_only' => 'Only articles can be validated. This page is <i>not</i> in the article namespace.',
'val_validated' => 'Validation done.',
'val_article_lists' => 'Списък на валидирани страници',
'val_page_validation_statistics' => 'Page validation statistics за $1',

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
'missingimage'   => '<b>Липсваща картинка</b><br /><i>$1</i>'."\n",
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
'subcategorycount1' => 'Тази категория има една подкатегория',
'categoryarticlecount' => 'Тази категория съдържа $1 страници.',
'categoryarticlecount1' => 'Тази категория съдържа една страница.',
# TODO: превод
'usenewcategorypage' => "1\n\nSet first character to „0“ to disable the new category page layout.",
'listingcontinuesabbrev' => ' продълж.',

# Info page
'infosubtitle' => 'Информация за страницата',
'numedits' => 'Брой редакции (страница): ',
'numtalkedits' => 'Брой редакции (дискусионна страница): ',
'numwatchers' => 'Брой наблюдатели: ',
'numauthors' => 'Брой различни автори (страница): ',
'numtalkauthors' => 'Брой различни автори (дискусионна страница): ',

# Math
'mw_math_png'    => 'Използване винаги на PNG',
'mw_math_simple' => 'HTML при опростен TeX, иначе PNG',
'mw_math_html'   => 'HTML по възможност, иначе PNG',
'mw_math_source' => 'Оставяне като TeX (за текстови браузъри)',
'mw_math_modern' => 'Препоръчително за нови браузъри',
'mw_math_mathml' => 'MathML по възможност (експериментално)',

# Patrolling
'markaspatrolleddiff'   => 'Отбелязване като проверена версия',//'Mark as patrolled',
'markaspatrolledlink'   => '[$1]',
'markaspatrolledtext'   => 'Отбелязване на версията като проверена',//'Mark this article as patrolled',
'markedaspatrolled'     => 'Проверена версия', //'Marked as patrolled',
'markedaspatrolledtext' => 'Избраната версия беше отбелязана като проверена.',//'The selected revision has been marked as patrolled.',
'rcpatroldisabled'      => 'Патрулът е деактивиран', //'Recent Changes Patrol disabled',
'rcpatroldisabledtext'  => 'Патрулът на последните промени е деактивиран',//'The Recent Changes Patrol feature is currently disabled.',


# Monobook.js: tooltips and access keys for monobook
'Monobook.js' => '
 /* чрез редактиране на този файл можете да промените някои неща на Javascript за облика Monobook */
 /* tooltips and access keys */
 ta = new Object();
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
 ta[\'ca-nstab-wp\'] = new Array(\'c\',\'Преглед на проектната страница\');
 ta[\'ca-nstab-image\'] = new Array(\'c\',\'Преглед на страницата на файла\');
 ta[\'ca-nstab-mediawiki\'] = new Array(\'c\',\'Преглед на системното съобщение\');
 ta[\'ca-nstab-template\'] = new Array(\'c\',\'Преглед на шаблона\');
 ta[\'ca-nstab-help\'] = new Array(\'c\',\'Преглед на помощната страница\');
 ta[\'ca-nstab-category\'] = new Array(\'c\',\'Преглед на категорийната страница\');
',

# image deletion
'deletedrevision' => 'Изтрита стара версия $1.',

# browsing diffs
'previousdiff' => '← Предишна разлика',
'nextdiff' => 'Следваща разлика →',

'imagemaxsize' => 'Ограничаване на картинките на описателните им страници до: ',
'thumbsize'     => 'Размери на миникартинките: ',
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
'specialloguserlabel' => 'Потребител: ',
'speciallogtitlelabel' => 'Заглавие: ',

'passwordtooshort' => 'Паролата Ви е прекалено къса: трябва да съдържа поне $1 знака.',

# Media Warning
# TODO Превод
'mediawarning' => '\'\'\'Внимание\'\'\': This file may contain malicious code, by executing it your system may be compromised.
<hr>',

'fileinfo' => '$1 КБ, MIME type: <code>$2</code>',

# Metadata
'metadata' => 'Метаданни',
# TODO Превод
# Exif tags
'exif-imagewidth' =>'Ширина',
'exif-imagelength' =>'Височина',
'exif-bitspersample' =>'Bits per component',
'exif-compression' =>'Compression scheme',
'exif-photometricinterpretation' =>'Pixel composition',
'exif-orientation' =>'Orientation',
'exif-samplesperpixel' =>'Number of components',
'exif-planarconfiguration' =>'Data arrangement',
'exif-ycbcrsubsampling' =>'Subsampling ratio of Y to C',
'exif-ycbcrpositioning' =>'Y and C positioning',
'exif-xresolution' =>'Horizontal direction',
'exif-yresolution' =>'Vertical resolution',
'exif-resolutionunit' =>'Unit of X and Y resolution',
'exif-stripoffsets' =>'Image data location',
'exif-rowsperstrip' =>'Number of rows per strip',
'exif-stripbytecounts' =>'Bytes per compressed strip',
'exif-jpeginterchangeformat' =>'Offset to JPEG SOI',
'exif-jpeginterchangeformatlength' =>'Bytes of JPEG data',
'exif-transferfunction' =>'Transfer function',
'exif-whitepoint' =>'White point chromaticity',
'exif-primarychromaticities' =>'Chromaticities of primarities',
'exif-ycbcrcoefficients' =>'Color space transformation matrix coefficients',
'exif-referenceblackwhite' =>'Pair of black and white reference values',
'exif-datetime' =>'File change date and time',
'exif-imagedescription' =>'Image title',
'exif-make' =>'Camera manufacturer',
'exif-model' =>'Camera model',
'exif-software' =>'Software used',
'exif-artist' =>'Автор',
'exif-copyright' =>'Copyright holder',
'exif-exifversion' =>'Exif version',
'exif-flashpixversion' =>'Supported Flashpix version',
'exif-colorspace' =>'Color space',
'exif-componentsconfiguration' =>'Meaning of each component',
'exif-compressedbitsperpixel' =>'Image compression mode',
'exif-pixelydimension' =>'Valid image width',
'exif-pixelxdimension' =>'Valind image height',
'exif-makernote' =>'Manufacturer notes',
'exif-usercomment' =>'User comments',
'exif-relatedsoundfile' =>'Related audio file',
'exif-datetimeoriginal' =>'Date and time of data generation',
'exif-datetimedigitized' =>'Date and time of digitizing',
'exif-subsectime' =>'DateTime subseconds',
'exif-subsectimeoriginal' =>'DateTimeOriginal subseconds',
'exif-subsectimedigitized' =>'DateTimeDigitized subseconds',
'exif-exposuretime' =>'Exposure time',
'exif-fnumber' =>'F Number',
'exif-exposureprogram' =>'Exposure Program',
'exif-spectralsensitivity' =>'Spectral sensitivity',
'exif-isospeedratings' =>'ISO speed rating',
'exif-oecf' =>'Optoelectronic conversion factor',
'exif-shutterspeedvalue' =>'Shutter speed',
'exif-aperturevalue' =>'Aperture',
'exif-brightnessvalue' =>'Brightness',
'exif-exposurebiasvalue' =>'Exposure bias',
'exif-maxaperturevalue' =>'Maximum land aperture',
'exif-subjectdistance' =>'Subject distance',
'exif-meteringmode' =>'Metering mode',
'exif-lightsource' =>'Light source',
'exif-flash' =>'Flash',
'exif-focallength' =>'Lens focal length',
'exif-subjectarea' =>'Subject area',
'exif-flashenergy' =>'Flash energy',
'exif-spatialfrequencyresponse' =>'Spatial frequency response',
'exif-focalplanexresolution' =>'Focal plane X resolution',
'exif-focalplaneyresolution' =>'Focal plane Y resolution',
'exif-focalplaneresolutionunit' =>'Focal plane resolution unit',
'exif-subjectlocation' =>'Subject location',
'exif-exposureindex' =>'Exposure index',
'exif-sensingmethod' =>'Sensing method',
'exif-filesource' =>'File source',
'exif-scenetype' =>'Scene type',
'exif-cfapattern' =>'CFA pattern',
'exif-customrendered' =>'Custom image processing',
'exif-exposuremode' =>'Exposure mode',
'exif-whitebalance' =>'White Balance',
'exif-digitalzoomratio' =>'Digital zoom ratio',
'exif-focallengthin35mmfilm' =>'Focal length in 35 mm film',
'exif-scenecapturetype' =>'Scene capture type',
'exif-gaincontrol' =>'Scene control',
'exif-contrast' =>'Contrast',
'exif-saturation' =>'Saturation',
'exif-sharpness' =>'Sharpness',
'exif-devicesettingdescription' =>'Device settings description',
'exif-subjectdistancerange' =>'Subject distance range',
'exif-imageuniqueid' =>'Unique image ID',
'exif-gpsversionid' =>'GPS tag version',
'exif-gpslatituderef' =>'North or South Latitude',
'exif-gpslatitude' =>'Latitude',
'exif-gpslongituderef' =>'East or West Longitude',
'exif-gpslongitude' =>'Longitude',
'exif-gpsaltituderef' =>'Altitude reference',
'exif-gpsaltitude' =>'Altitude',
'exif-gpstimestamp' =>'GPS time (atomic clock)',
'exif-gpssatellites' =>'Satellites used for measurement',
'exif-gpsstatus' =>'Receiver status',
'exif-gpsmeasuremode' =>'Measurement mode',
'exif-gpsdop' =>'Measurement precision',
'exif-gpsspeedref' =>'Speed unit',
'exif-gpsspeed' =>'Speed of GPS receiver',
'exif-gpstrackref' =>'Reference for direction of movement',
'exif-gpstrack' =>'Direction of movement',
'exif-gpsimgdirectionref' =>'Reference for direction of image',
'exif-gpsimgdirection' =>'Direction of image',
'exif-gpsmapdatum' =>'Geodetic survey data used',
'exif-gpsdestlatituderef' =>'Reference for latitude of destination',
'exif-gpsdestlatitude' =>'Latitude destination',
'exif-gpsdestlongituderef' =>'Reference for longitude of destination',
'exif-gpsdestlongitude' =>'Longitude of destination',
'exif-gpsdestbearingref' =>'Reference for bearing of destination',
'exif-gpsdestbearing' =>'Bearing of destination',
'exif-gpsdestdistanceref' =>'Reference for distance to destination',
'exif-gpsdestdistance' =>'Distance to destination',
'exif-gpsprocessingmethod' =>'Name of GPS processing method',
'exif-gpsareainformation' =>'Name of GPS area',
'exif-gpsdatestamp' =>'GPS date',
'exif-gpsdifferential' =>'GPS differential correction',

# Make & model, can be wikified in order to link to the camera and model name

'exif-make-value' => '$1',
'exif-model-value' =>'$1',
'exif-software-value' => '$1',

# Exif attributes

'exif-compression-1' => 'Uncompressed',
'exif-compression-6' => 'JPEG',

'exif-photometricinterpretation-1' => 'RGB',
'exif-photometricinterpretation-6' => 'YCbCr',

'exif-orientation-1' => 'Normal', // 0th row: top; 0th column: left
'exif-orientation-2' => 'Flipped horizontally', // 0th row: top; 0th column: right
'exif-orientation-3' => 'Rotated 180°', // 0th row: bottom; 0th column: right
'exif-orientation-4' => 'Flipped vertically', // 0th row: bottom; 0th column: left
'exif-orientation-5' => 'Rotated 90° CCW and flipped vertically', // 0th row: left; 0th column: top
'exif-orientation-6' => 'Rotated 90° CW', // 0th row: right; 0th column: top
'exif-orientation-7' => 'Rotated 90° CW and flipped vertically', // 0th row: right; 0th column: bottom
'exif-orientation-8' => 'Rotated 90° CCW', // 0th row: left; 0th column: bottom

'exif-planarconfiguration-1' => 'chunky format',
'exif-planarconfiguration-2' => 'planar format',

'exif-resolutionunit-i' => '$1 dpi',
'exif-resolutionunit-c' => '$1 dpc',

'exif-colorspace-1' => 'sRGB',
'exif-colorspace-ffff.h' => 'FFFF.H',

'exif-componentsconfiguration-0' => 'does not exist',
'exif-componentsconfiguration-1' => 'Y',
'exif-componentsconfiguration-2' => 'Cb',
'exif-componentsconfiguration-3' => 'Cr',
'exif-componentsconfiguration-4' => 'R',
'exif-componentsconfiguration-5' => 'G',
'exif-componentsconfiguration-6' => 'B',

'exif-exposureprogram-0' => 'Not defined',
'exif-exposureprogram-1' => 'Manual',
'exif-exposureprogram-2' => 'Normal program',
'exif-exposureprogram-3' => 'Aperture priority',
'exif-exposureprogram-4' => 'Shutter priority',
'exif-exposureprogram-5' => 'Creative program (biased toward depth of field)',
'exif-exposureprogram-6' => 'Action program (biased toward fast shutter speed)',
'exif-exposureprogram-7' => 'Portrait mode (for closeup photos with the background out of focus)',
'exif-exposureprogram-8' => 'Landscape mode (for landscape photos with the background in focus)',

'exif-subjectdistance-value' => '$1 metres',

'exif-meteringmode-0' => 'Unknown',
'exif-meteringmode-1' => 'Average',
'exif-meteringmode-2' => 'CenterWeightedAverage',
'exif-meteringmode-3' => 'Spot',
'exif-meteringmode-4' => 'MultiSpot',
'exif-meteringmode-5' => 'Pattern',
'exif-meteringmode-6' => 'Partial',
'exif-meteringmode-255' => 'Other',

'exif-lightsource-0' => 'Unknown',
'exif-lightsource-1' => 'Daylight',
'exif-lightsource-2' => 'Fluorescent',
'exif-lightsource-3' => 'Tungsten (incandescent light)',
'exif-lightsource-4' => 'Flash',
'exif-lightsource-9' => 'Fine weather',
'exif-lightsource-10' => 'Clody weather',
'exif-lightsource-11' => 'Shade',
'exif-lightsource-12' => 'Daylight fluorescent (D 5700–7100K)',
'exif-lightsource-13' => 'Day white fluorescent (N 4600–5400K)',
'exif-lightsource-14' => 'Cool white fluorescent (W 3900–4500K)',
'exif-lightsource-15' => 'White fluorescent (WW 3200–3700K)',
'exif-lightsource-17' => 'Standard light A',
'exif-lightsource-18' => 'Standard light B',
'exif-lightsource-19' => 'Standard light C',
'exif-lightsource-20' => 'D55',
'exif-lightsource-21' => 'D65',
'exif-lightsource-22' => 'D75',
'exif-lightsource-23' => 'D50',
'exif-lightsource-24' => 'ISO studio tungsten',
'exif-lightsource-255' => 'Other light source',

'exif-focalplaneresolutionunit-2' => 'inches',

'exif-sensingmethod-1' => 'Undefined',
'exif-sensingmethod-2' => 'One-chip color area sensor',
'exif-sensingmethod-3' => 'Two-chip color area sensor',
'exif-sensingmethod-4' => 'Three-chip color area sensor',
'exif-sensingmethod-5' => 'Color sequential area sensor',
'exif-sensingmethod-7' => 'Trilinear sensor',
'exif-sensingmethod-8' => 'Color sequential linear sensor',

'exif-filesource-3' => 'DSC',

'exif-scenetype-1' => 'A directly photographed image',

'exif-customrendered-0' => 'Normal process',
'exif-customrendered-1' => 'Custom process',

'exif-exposuremode-0' => 'Auto exposure',
'exif-exposuremode-1' => 'Manual exposure',
'exif-exposuremode-2' => 'Auto bracket',

'exif-whitebalance-0' => 'Auto white balance',
'exif-whitebalance-1' => 'Manual white balance',

'exif-scenecapturetype-0' => 'Standard',
'exif-scenecapturetype-1' => 'Landscape',
'exif-scenecapturetype-2' => 'Portrait',
'exif-scenecapturetype-3' => 'Night scene',

'exif-gaincontrol-0' => 'None',
'exif-gaincontrol-1' => 'Low gain up',
'exif-gaincontrol-2' => 'High gain up',
'exif-gaincontrol-3' => 'Low gain down',
'exif-gaincontrol-4' => 'High gain down',

'exif-contrast-0' => 'Normal',
'exif-contrast-1' => 'Soft',
'exif-contrast-2' => 'Hard',

'exif-saturation-0' => 'Normal',
'exif-saturation-1' => 'Low saturation',
'exif-saturation-2' => 'High saturation',

'exif-sharpness-0' => 'Normal',
'exif-sharpness-1' => 'Soft',
'exif-sharpness-2' => 'Hard',

'exif-subjectdistancerange-0' => 'Unknown',
'exif-subjectdistancerange-1' => 'Macro',
'exif-subjectdistancerange-2' => 'Close view',
'exif-subjectdistancerange-3' => 'Distant view',

// Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => 'North latitude',
'exif-gpslatitude-s' => 'South latitude',

// Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => 'East longitude',
'exif-gpslongitude-w' => 'West longitude',

'exif-gpsstatus-a' => 'Measurement in progress',
'exif-gpsstatus-v' => 'Measurement interoperability',

'exif-gpsmeasuremode-2' => '2-dimensional measurement',
'exif-gpsmeasuremode-3' => '3-dimensional measurement',

// Pseudotags used for GPSSpeedRef and GPSDestDistanceRef
'exif-gpsspeed-k' => 'Kilometres per hour',
'exif-gpsspeed-m' => 'Miles per hour',
'exif-gpsspeed-n' => 'Knots',

// Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'True direction',
'exif-gpsdirection-m' => 'Magnetic direction', 

# external editor support
'edit-externally' => 'Редактиране на файла чрез външно приложение',
'edit-externally-help' => 'За повече информация прегледайте [http://meta.wikimedia.org/wiki/Help:External_editors указанията за настройките].',

# 'all' in various places, this might be different for inflicted languages
'recentchangesall' => 'всички',
'imagelistall' => 'всички',
'watchlistall1' => 'всички',
'watchlistall2' => 'всички',
'namespacesall' => 'всички',

# TODO
# E-mail address confirmation
'confirmemail' => 'Confirm E-mail address',
'confirmemail_text' => 'This wiki requires that you validate your e-mail address
before using e-mail features. Activate the button below to send a confirmation
mail to your address. The mail will include a link containing a code; load the
link in your browser to confirm that your e-mail address is valid.',
'confirmemail_send' => 'Mail a confirmation code',
'confirmemail_sent' => 'Confirmation e-mail sent.',
'confirmemail_sendfailed' => 'Could not send confirmation mail. Check address for invalid characters.',
'confirmemail_invalid' => 'Грешен код за потвърждение. Вероятно кодът за потвърждение е остарял.',
'confirmemail_success' => 'Адресът ви за електронна поща беше потвърден. Вече можете да влезете и да се наслаждавате на уикито.',
'confirmemail_loggedin' => 'Адресът ви за електронна поща беше потвърден.',
'confirmemail_error' => 'Something went wrong saving your confirmation.',

'confirmemail_subject' => '{{SITENAME}} e-mail address confirmation',
'confirmemail_body' => 'Someone, probably you from IP address $1, has registered an
account "$2" with this e-mail address on {{SITENAME}}.

To confirm that this account really does belong to you and activate
e-mail features on {{SITENAME}}, open this link in your browser:

$3

If this is *not* you, don\'t follow the link. This confirmation code
will expire at $4.
',

# Inputbox extension, may be useful in other contexts as well
'tryexact' => 'Пълно и точно съвпадение',
'searchfulltext' => 'Претърсване на целия текст',
'createarticle' => 'Създаване на статия',

# TODO
# Scary transclusion
'scarytranscludedisabled' => '[Interwiki transcluding is disabled]',
'scarytranscludefailed' => '[Template fetch failed for $1; sorry]',
'scarytranscludetoolong' => '[URL is too long; sorry]',

# Trackbacks
'trackbackbox' => '<div id="mw_trackbacks">
Trackbacks for this article:<br/>
$1
</div>
',
'trackback' => '; $4$5 : [$2 $1]'."\n",
'trackbackexcerpt' => '; $4$5 : [$2 $1]: <nowiki>$3</nowiki>'."\n",
'trackbackremove' => ' ([$1 Изтриване])',
'trackbacklink' => 'Trackback',
'trackbackdeleteok' => 'The trackback was successfully deleted.',

# delete conflict

'deletedwhileediting' => 'Внимание: Страницата е била изтрита, откакто сте започнали да я редактирате!',
'confirmrecreate' => 'Потребител [[Потребител:$1|$1]] ([[Потребител беседа:$1|беседа]]) е изтрил страницата, откакто сте започнали да я редактирате, давайки следното обяснение:
: \'\'$2\'\'
Моля, потвърдете, че наистина желаете да създадете страницата отново.',
'recreate' => 'Ново създаване',
'tooltip-recreate' => '',

'unit-pixel' => 'px',
);

/** This is an UTF-8 language */
require_once( 'LanguageUtf8.php' );

/**
 * @package MediaWiki
 * @subpackage Language
 */
class LanguageBg extends LanguageUtf8 {

	/**
	* Exports $wgBookstoreListBg
	* @return array
	*/
	function getBookstoreList () {
		global $wgBookstoreListBg;
		return $wgBookstoreListBg;
	}
	
	/**
	* Exports $wgNamespaceNamesBg
	* @return array
	*/
	function getNamespaces() {
		global $wgNamespaceNamesBg;
		return $wgNamespaceNamesBg;
	}

	/**
	* Exports $wgQuickbarSettingsBg
	* @return array
	*/
	function getQuickbarSettings() {
		global $wgQuickbarSettingsBg;
		return $wgQuickbarSettingsBg;
	}

	/**
	* Exports $wgSkinNamesBg
	* @return array
	*/
	function getSkinNames() {
		global $wgSkinNamesBg;
		return $wgSkinNamesBg;
	}

	/**
	* Exports $wgValidationTypesBg
	* @return array
	*/
	function getValidationTypes() {
		global $wgValidationTypesBg;
		return $wgValidationTypesBg;
	}

	/**
	* Exports $wgDateFormatsBg
	* @return array
	*/
	function getDateFormats() {
		global $wgDateFormatsBg;
		return $wgDateFormatsBg;
	}

	function getMessage( $key ) {
		global $wgAllMessagesBg;
		if ( isset( $wgAllMessagesBg[$key] ) ) {
			return $wgAllMessagesBg[$key];
		} else {
			return parent::getMessage( $key );
		}
	}

	/**
	* Exports $wgMagicWordsBg
	* @return array
	*/
	function getMagicWords()  {
		global $wgMagicWordsBg;
		return $wgMagicWordsBg;
	}
	
	
	function formatNum( $number ) {
		return str_replace(array('.', ','), array(',', '&nbsp;'), $number);
	}
}
?>
