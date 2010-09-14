<?php
/** Kabardian (Cyrillic) (къэбэрдеибзэ/qabardjajəbza (Cyrillic))
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Bogups
 * @author Алёшка
 * @author Тамэ Балъкъэрхэ
 */

$fallback = 'ru';

$messages = array(
# User preference toggles
'tog-underline'             => 'ТехьэпIэхэр щIэтхъэн:',
'tog-highlightbroken'       => 'ЩымыIэ техьэпIэхэр къэгъэлъэгъуэн <a href="" class="new">мыбы хуэду</a> (ар мыхъумэ мыпхуэдэу<a href="" class="internal">?</a>).',
'tog-justify'               => 'БгъуагъкIэ напэкIуэцIыр зэгъэзэхуэн',
'tog-hideminor'             => 'ГъэпщкIун: кIуэдкIэ зыхэмылэжьыхьа, щIэуэ яхъуэжа тхылъым',
'tog-hidepatrolled'         => 'ГъэпшкIун гъэтэрэзыгъуэ щIэлъыплъахэм я тхылъ гъэтэрэзыгъуэщIэхэр',
'tog-newpageshidepatrolled' => 'ГъэпшкIун напэкIуэцI щIэлъыплъахэм я тхылъ напэкIуэцI-щIэхэр',
'tog-extendwatchlist'       => 'ЩӀэлъыплъыгъуэм и тхылъышхуэ, яужырэй къуэдейхэм нэмыщӀу зэхъуэкӀыгъуэ псори хэту',
'tog-usenewrc'              => 'Гъэтэрэзыгъуэ щӀэхэм я тхылъ нэхъыфӀыр къэгъэсэбэпын (JavaScript хуэныкъуэ)',
'tog-numberheadings'        => 'Псалъащхьэхэм автоматику номер ятын',
'tog-showtoolbar'           => 'Гъэтэрэзыным идежь ищхьэ пэнелыр гъэлъэгъуэн (JavaScript)',
'tog-editondblclick'        => 'ТӀуанэ текъузэгъуэкӀэ напэкӀуэцӀхэр гъэтэрэзын (requires JavaScript)',
'tog-editsection'           => 'Лъэныкъуэ къэс техьэпӀэ [гъэтэрэзын] гъэлъэгъуэн',
'tog-rememberpassword'      => 'Компьютерым си логиныр щыхъумэн (for a maximum of $1 {{PLURAL:$1|day|days}})',
'tog-watchcreations'        => 'Сэ сщIа напэкIуэцIхэр сызыкIэлъыплъ списокым хэлъхьэн',
'tog-watchdefault'          => 'Сэ схъуэжа напэкIуэцIхэр сызыкIэлъыплъ списокым хэлъхьэн',
'tog-watchmoves'            => 'Зи цIэ схъуэжа напэкIуэцIхэр сызыкIэлъыплъ списокым хэлъхьэн',
'tog-watchdeletion'         => 'Сэ тезгъэкIыжа напэкIуэцIхэр сызыкIэлъыплъ списокым хэлъхьэн',
'tog-nocache'               => 'Кеш щIа напэкIуэцIхэр гъэбыдэн',
'tog-watchlisthideown'      => 'Си хъуэжапхъэхэр сызыкIэлъыплъ спискэм хэгъэпщкIухьым',
'tog-watchlisthidebots'     => 'Ботым ихъуэжахэр сызыкIэлъыплъ спискэм щыгъэпщкIуын',
'tog-watchlisthideminor'    => 'МащIэу хъуэжахэр сызыкIэлъыплъ спискэм щыгъэпщкIуын',
'tog-showhiddencats'        => 'ГъэпщкIуа категориехэр къэгъэлъэгъуэжын',

'underline-always'  => 'Сыт щыгъуи',
'underline-never'   => 'ЗейкI',
'underline-default' => 'Браузерым и теухуапхъэхэр къэгъэсэбэпын',

# Dates
'sunday'        => 'Тхьэмахуэ',
'monday'        => 'Блыщхьэ',
'tuesday'       => 'Гъубж',
'wednesday'     => 'Бэрэжьей',
'thursday'      => 'Махуэку',
'friday'        => 'Мэрем',
'saturday'      => 'Щэбэт',
'sun'           => 'Тхьм',
'mon'           => 'Блщ',
'tue'           => 'Гбж',
'wed'           => 'Бржь',
'thu'           => 'Мку',
'fri'           => 'Мрм',
'sat'           => 'Щбт',
'january'       => 'ЩIышылэ(01)',
'february'      => 'Мазае(02)',
'march'         => 'Гъатхэпэ(03)',
'april'         => 'Мэлыжьыхь(04)',
'may_long'      => 'Накъыгъэ(05)',
'june'          => 'Мэкъуауэгъуэ(06)',
'july'          => 'Бадзэуэгъуэ(07)',
'august'        => 'ШыщхьэIу(08)',
'september'     => 'ФокIадэ(09)',
'october'       => 'Жэпуэгъуэ(10)',
'november'      => 'ЩакIуэгъуэ(11)',
'december'      => 'Дыгъэгъазэ(12)',
'january-gen'   => 'ЩIышылэ(01)',
'february-gen'  => 'Мазае(02)',
'march-gen'     => 'Гъатхэпэ(03)',
'april-gen'     => 'Мэлыжьыхь(04)',
'may-gen'       => 'Накъыгъэ(05)',
'june-gen'      => 'Мэкъуауэгъуэ(06)',
'july-gen'      => 'Бадзэуэгъуэ(07)',
'august-gen'    => 'ШыщхьэIу(08)',
'september-gen' => 'ФокIадэ(09)',
'october-gen'   => 'Жэпуэгъуэ(10)',
'november-gen'  => 'ЩакIуэгъуэ(11)',
'december-gen'  => 'Дыгъэгъазэ(12)',
'jan'           => 'ЩIш',
'feb'           => 'Мзе',
'mar'           => 'Гъп',
'apr'           => 'Мжьхь',
'may'           => 'Нкъ',
'jun'           => 'Мкъу',
'jul'           => 'Бдз',
'aug'           => 'ШIу',
'sep'           => 'Фдэ',
'oct'           => 'Жэп',
'nov'           => 'ЩкIу',
'dec'           => 'Дгъз',

# Categories related messages
'pagecategories'         => '{{PLURAL:$1|Категориэ|Категориэхэр}}',
'category_header'        => 'НапэкIуэцIхэр "$1" категориэм',
'subcategories'          => 'КатегориэцӀыкӀухэр',
'category-empty'         => '"Мы категорием иджыпстукIэ зыри илъкъым."',
'hidden-categories'      => '{{PLURAL:$1|Категориэ гъэпшкӀуа|Категориэ гъэпшкӀуахэр}}',
'category-subcat-count'  => '{{PLURAL:$2|Мы категориэм хиубыдэр категориэцӀыкӀу къэдей.|{{PLURAL:$1|Гъэлъэгъуар $1 категориэцӀыкӀу|Гъэлъэгъуар $1 категориэцӀыкӀуху|Гъэлъэгъуар $1 категориэцӀыкӀухэм}} $2 ящыщ.}}',
'category-article-count' => '{{PLURAL:$2|Мы категориэм зы напэкӀуцӀ нэхъ хэткъым.|{{PLURAL:$1|Гъэлъэгъуар $1 напэкӀуэцӀ|Гъэлъэгъуар $1 напэкӀуэцӀу|Гъэлъэгъуар $1 напэкӀуэцхӀу}} категориэм еуэ $2.}}',
'listingcontinuesabbrev' => '(пыщэпхъэр)',
'index-category'         => 'Индекс зырат напэкIуэцIхэр',
'noindex-category'       => 'НапэкIуэцI индекс зыхуэмыщIахэр',

'mainpagetext' => "'''Вики-движок \"MediaWiki\"-р хъэрзынэ дыдэу тетха хъуащ.'''",

'about'         => 'Тетхыхьа',
'article'       => 'Тхыгъэ',
'newwindow'     => '(щхьэгъумбжэщIэм)',
'cancel'        => 'ЩIегъуэжын',
'moredotdotdot' => 'АкIэ',
'mypage'        => 'Си напэкIуэцI',
'mytalk'        => 'Си тепсэлъыхьуэгъуэм и напэкIуэцI',
'navigation'    => 'Навигацэ',
'and'           => 'икIи',

# Cologne Blue skin
'qbfind'         => 'Лъыхъуэн',
'qbbrowse'       => 'Хэплъэн',
'qbedit'         => 'Хъуэжын',
'qbpageoptions'  => 'НапэкIуэцIым и теухуапхъэхэр',
'qbpageinfo'     => 'НапэкIуэцIым теухуауэ',
'qbmyoptions'    => 'Си теухуапхъэхэр',
'qbspecialpages' => 'СпецнапэкIуэцIхэр',
'faq'            => 'FAQ',
'faqpage'        => 'Project:FAQ',

# Vector skin
'vector-action-delete'   => 'ИгъэкIыжын',
'vector-action-move'     => 'ЦIэр хъуэжын',
'vector-action-protect'  => 'Хъумэн',
'vector-action-undelete' => 'ЗыфIэгъэувэжын',
'vector-view-view'       => 'Еджэн',

'errorpagetitle'   => 'Щыуагъэ',
'returnto'         => '$1 напэІуэцІым кІуэжын.',
'tagline'          => 'Къыздихар {{grammar:genitive|{{SITENAME}}}}',
'help'             => 'ДэӀэпыкъуэгъуэ',
'search'           => 'Къэгъуэтын',
'searchbutton'     => 'Къэгъуэтын',
'searcharticle'    => 'ЕкIуэкIын',
'history'          => 'Тхыдэ',
'history_short'    => 'Тхыдэ',
'info_short'       => 'Информацэ',
'printableversion' => 'Печатым теухуа версие',
'permalink'        => 'ТехьэпӀэ зэмыхъуэкӀ',
'print'            => 'Печать',
'edit'             => 'Гъэтэрэзын',
'create'           => 'ЩIын',
'editthispage'     => 'Мы напэкIуэцIыр гъэтэрэзын',
'create-this-page' => 'Мыбы и напэкIуэцI щIын',
'delete'           => 'ТегъэкIын',
'protect'          => 'Хъумэн',
'protect_change'   => 'зэхъуэкIын',
'newpage'          => 'НапэкIуэцIыщIэ',
'talkpage'         => 'НапэкIуэцIым тепсэлъыхьын',
'talkpagelinktext' => 'Тепсэлъыхьыгъуэ',
'personaltools'    => 'Уи Iэмэпсымэхэр',
'postcomment'      => 'РазделыщIэ',
'articlepage'      => 'Статьям хэплъэн',
'talk'             => 'Тепсэлъэхьыгъуэ',
'views'            => 'Зыхэплъахэр',
'toolbox'          => 'Iэмэпсымэхэр',
'imagepage'        => 'Файлым и напэкIуэцIым еплъын',
'mediawikipage'    => 'Тхыгъэм и напэкIуэцIым еплъын',
'templatepage'     => 'Шаблоным и напэкIуэцIым хэплъэн',
'viewhelppage'     => 'ЩIэупщIэм и напэкIуэцI',
'categorypage'     => 'Категорием и напэкIуэцIым хэплъэн',
'otherlanguages'   => 'НэмыщӀ бзэхэмкӀэ',
'redirectedfrom'   => '($1 мыбы къикIащ)',
'redirectpagesub'  => 'НапэкIуэцI-егъэкӀуэкӀа',
'lastmodifiedat'   => 'Иужь дыдэу напэкIуэцIыр щахъуэжар: $1, $2 тэлайхэм ирихьэлIэу.',
'jumpto'           => 'Мыбы кIуэн:',
'jumptonavigation' => 'навигацэ',
'jumptosearch'     => 'лъыхъуэн',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => '{{grammar:genitive|{{SITENAME}}}} -м теухуауэ',
'aboutpage'            => 'Project:Теухуауэ',
'copyright'            => 'Мыбы итыр къэутIыпщащ зытещIыхьари: $1.',
'copyrightpage'        => '{{ns:project}}:ЗиIэдакъэм и пӀалъэ',
'currentevents'        => 'КъекIуэкI Iуэхугъуэхэр',
'disclaimers'          => 'Жэуап Iыгъыныр зыщхьэщыхын',
'disclaimerpage'       => 'Project:Пщэрылъу къэмыштэн',
'edithelp'             => 'Гъэтэрэзыным и щӀэупщӀэ',
'edithelppage'         => 'Help:Гъэтэрэзыным и дэIэпыкъуэгъу',
'helppage'             => 'Help:ДэIэпыкъуэгъу',
'mainpage'             => 'НапэкӀуэцӀ нэхъыщхьэ',
'mainpage-description' => 'НапэкIуэцI нэхъыщхьэ',
'policy-url'           => 'Project:Хабзэхэр',
'portal'               => 'Лъэпкъыгъуэ ӀухьэпӀэ',
'portal-url'           => 'Project:Лъэпкъыгъуэ ӀухьэпӀэ',
'privacy'              => 'Конфиденциалым теухуа хабзэр',
'privacypage'          => 'Project:Конфиденциалым теухуа хабзэр',

'badaccess'        => 'Техьэным щыуагъэ иIэщ',
'badaccess-group0' => 'Ебгъэжьа Ӏохугъуэр быщӀэн пӀалъэ уиӀэкъым.',
'badaccess-groups' => 'Егъэжьа Ӏохугъуэр зыщӀэн пӀалъэ зиӀэр {{PLURAL:$2|гупхэм|гупым}} $1 хэтхэра',

'versionrequired'     => 'MediaWiki и версиэ $1 хуэныкъуэщ',
'versionrequiredtext' => 'Мы напэкӀуэцӀым елэжьын щхьэкӀэ MediaWiki версиэ $1 хуэныкъуэ. Еплъ [[Special:Version|къагъэсэбэп ПО-хэм я къэӀохугъуэ]].',

'ok'                      => 'ОК',
'retrievedfrom'           => 'Къыздырахар: "$1"',
'youhavenewmessages'      => 'КъыпхуэкӀуауэ уиӀэ $1 ($2).',
'newmessageslink'         => 'тхыгъэщIэхэр',
'newmessagesdifflink'     => 'иужьрей зэхъуэкІыныгъэр',
'youhavenewmessagesmulti' => 'КъыпхуэкӀуауэ уиӀэ тхыгъэщӀэхэр $1 идеж',
'editsection'             => 'гъэтэрэзын',
'editold'                 => 'гъэтэрэзын',
'viewsourceold'           => 'къызыхэкIа кодым хэплъэн',
'editlink'                => 'гъэтэрэзын',
'viewsourcelink'          => 'къызыхэкIа кодым еплъын',
'editsectionhint'         => 'Секцэр гъэтэрэзын: $1',
'toc'                     => 'Хэтхахэр',
'showtoc'                 => 'гъэлъэгъуэн',
'hidetoc'                 => 'гъэпщкIуын',
'thisisdeleted'           => 'Еплъын иэ зэфӀэгъэувэжын $1?',
'viewdeleted'             => 'Еплъын $1?',
'restorelink'             => '{{PLURAL:$1|$1 гъэтэрэзыгъуэ ихар|$1 гъэтэрэзыгъуэ ихахэр|$1 гъэтэрэзыгъуэ ихыжахэр}}',
'feedlinks'               => 'Хуэду:',
'feed-invalid'            => 'ӀэпэщӀэдз тыныгъэм и типыр тэрэзкъым.',
'feed-unavailable'        => 'Синдикациэ тыныгъэхэр хъухэкъым',
'site-rss-feed'           => '$1 — RSS-тыныгъэ',
'site-atom-feed'          => '$1 — Atom-тыныгъэ',
'page-rss-feed'           => '$1 — RSS-тыныгъэ',
'page-atom-feed'          => '$1 — Atom-тыныгъэ',
'red-link-title'          => '$1 (апхуэдэ напэкIуэцI щыIэкъым)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Тхыгъэ',
'nstab-user'      => 'ЦIыхухэт',
'nstab-media'     => 'Медием и напэкIуэцI',
'nstab-special'   => 'Лэжыгъэ напэкӀуэцӀ',
'nstab-project'   => 'Проэктым теухуауэ',
'nstab-image'     => 'Файл',
'nstab-mediawiki' => 'Тхыгъэ',
'nstab-template'  => 'Шаблон',
'nstab-help'      => 'ДэIэпыкъуэгъу',
'nstab-category'  => 'Категориэ',

# Main script and global functions
'nosuchaction'      => 'Апхуэдэ Ӏохугъуэ щыӀэкъым',
'nosuchactiontext'  => 'Ӏохугъуэ URL-м етар тэрэзкъым.
URL щиптхэм хэукъуэгъуэ быщӀа хъунщ иэ техэпӀэ нэмыщӀымкӀэ укӀуа.
Абым нэмыщӀу проэкт {{SITENAME}} хуэкъуэгъуэ зэриӀэр игъэлъэгъуэфыну.',
'nosuchspecialpage' => 'Апхуэдэ лэжыгъэ напэкӀуэцӀ щыӀэкъым',
'nospecialpagetext' => '<strong>НапэкӀуэцӀ лэжыгъэ узлъыхъуэм хуэдэ щыӀэкъым.</strong>

НапэкӀуэцӀ лэжыгъэ щыӀэхэм я тхылъ: [[Special:SpecialPages|{{int:specialpages}}]].',

# General errors
'error'                => 'Щыуагъэ',
'databaseerror'        => 'Ӏохугъуэлъэм и щыуагъэ',
'dberrortext'          => 'Ӏохугъуэлъэм и щӀэлъэуэн синтаксисым и щыуагъэ къахэкӀа.
Абым программэ къэтыным щыуагъэ иӀэфыну къокӀыр.
Яужырей Ӏохугъуэлъэм и щӀэлъэуэныр:
<blockquote><tt>$1</tt></blockquote>
функциэм къыхэкӀа <tt>«$2»</tt>.
Ӏохугъуэлъэм щыуагъэр къитыжащ <tt>«$3: $4»</tt>.',
'dberrortextcl'        => 'Ӏохугъуэлъэм и щӀэлъэуэн синтаксисым и щыуагъэ къахэкӀа. 
Яужырей Ӏохугъуэлъэм и щӀэлъэуэныр:
$1
«$2» функциэм къыхэкӀа. 
Ӏохугъуэлъэм щыуагъэр къитыжащ «$3: $4».',
'laggedslavemode'      => 'Гу лъытэ: напэкӀуэцӀым яужырэй къэгъэщӀэрыщӀэгъуэхэр хэмылъынкӀи мэхъур.',
'readonly'             => 'Ӏохугъуэлъэм итхэныр теубыдауэ щытщ',
'enterlockreason'      => 'Теубыдэгъуэр къызхэкӀамрэ зэман зэрекӀуэкӀынумрэ къэӀоху.',
'readonlytext'         => 'ТхыгъэщӀэхэр хэлъхьэнымрэ пэмыщӀ зэхъуэкӀыгъуэ щӀынымрэ иджыпсту теубыдауэ щытщ: план-лэжьыгъэ ирагъэкӀуэкӀхэм теухуауэ.
Администратэр зэтезубыдам къиӀохур мыра:
$1',
'missing-article'      => 'Ӏохугъуэлъэм зыщэлъаӀуэ напэкӀуэцӀым и тхылъ хэлъкъым, къэгъуэтын хуэятэр, «$1» $2.
Апхуэду щыхъур гъэтэрэзыгъуэхэм я техьэпӀэ жьы хъуахэм щытехьэкӀэ, напэкӀуэцӀ ирахыжам.
Лажьэ зиӀэр армырамэ, программэ тыгъэм и щыуагъэ къэбгъуэта хъунщ.
[[Special:ListUsers/sysop|Тхьэмадэм]] ар къыхуэӀоху, URL-ри игъусу.',
'missingarticle-rev'   => '(версиэ № $1)',
'missingarticle-diff'  => '(зэщхьэщыкӀыгъуэ: $1, $2)',
'readonly_lag'         => 'Ӏохугъуэлъэр зэман гуэрэкӀэ автоматику зэтриубыда зэхъуэкӀыгъуэ щӀыным щхьэкӀэ, Ӏохугъуэлъэм и тӀуанэ серверым япэрем теху синхронизациэ иримгъэкӀуэкӀ щыкӀэ.',
'internalerror'        => 'И кӀуэцӀ щыуагъэ',
'internalerror_info'   => 'И кӀуэцӀ щыуагъэ: $1',
'fileappenderrorread'  => 'Хэлъхьэгъуэм идежь «$1» гурыӀуэгъуэ хъуакъым.',
'fileappenderror'      => '«$2»-м «$1»-р щӀэрылъхьэн хъуакъым.',
'filecopyerror'        => '«$1»-м и копиэ «$2»-м хуэкӀуэкъым.',
'filerenameerror'      => '«$1»-м и цӀэр «$2»-кӀэ зэхъуэкӀыфкъым.',
'filedeleteerror'      => 'Файл «$1»-р ирихыфкъым.',
'directorycreateerror' => '«$1»-м и директориэ ищӀыфкъым.',
'filenotfound'         => 'Файл «$1»-р игъуэтыфкъым.',
'fileexistserror'      => 'Файл «$1»-р иритхэфкъым: апхуэдэ файл щыӀэщ.',
'unexpected'           => 'Мыхьэнэ темыхуэ: «$1»=«$2».',
'formerror'            => 'Щыуагъэ: Ӏохугъуэ формэр егъэхьын хъукъым',
'badarticleerror'      => 'А лэжьыгъэр мы напэкӀуэцӀым егъэкӀуэкӀыфынукъым.',
'cannotdelete'         => 'НапэкӀуэцӀыр иэ файл «$1»-р ихыфкъым.
НэмыщӀ гуэрэм ирихагъэнкӀ хъун.',
'badtitle'             => 'ЦӀэр хъунукъым',
'badtitletext'         => 'УзщӀэупщӀэ напэкӀуэцӀым и цӀэр тэрэзкъым, нэщӀ, мытэрэзу интервикир иэ бзэ-зэхуэкур щытщ. И цӀэм дэмыгъэ емыкӀуэалъэ хэтынкӀи хъун.',
'perfcached'           => 'Мы Ӏохугъуэхэр кэшым къыхахэ, яужырей зэхъуэкӀыгъуэхэр химыубыдэнкӀи мэхъу.',
'perfcachedts'         => 'Мы Ӏохугъуэхэр кэшым къыхахэ, яужыреуэ къыщыгъэщӀэрэщӀыжар $1.',
'querypage-no-updates' => 'Мы напэкӀуэцӀым и къэгъэщӀэрэщӀыныр джыпсту теубыдауэ щытщ.
Ӏохугъуэ мыде хэтхэр актуализациэ хъунукъым.',
'viewsource'           => 'Хэплъэн',

# Login and logout pages
'yourname'                => 'Уи цIэр:',
'yourpassword'            => 'Пэролыр:',
'yourpasswordagain'       => 'Иджыри зэ пэролыр:',
'remembermypassword'      => 'Сызэрихьэр компьютерым щыIыгъын (махуэу $1 {{PLURAL:$1|щIимыгъуу|щIимыгъуу}})',
'login'                   => 'Системэм зыкъегъэцIыхуын',
'nav-login-createaccount' => 'Ихьэн/щIэуэ зитхэн',
'userlogin'               => 'Ихьэн/зыхэтхэн',
'logout'                  => 'ИкӀыжын',
'userlogout'              => 'ИкӀыжын',
'nologinlink'             => 'Аккаунт щІын',
'mailmypassword'          => 'ПэролыщIэ къеIыхын',
'loginlanguagelabel'      => 'Бзэ: $1',

# Password reset dialog
'resetpass'         => 'Пэролым и хъуэжын',
'oldpassword'       => 'Паролыжьыр:',
'newpassword'       => 'ПаролыщIэр:',
'retypenew'         => 'ПаролыщIэр иджырэ зэ итхэж:',
'resetpass_submit'  => 'Паролыр итхи ихьэ',
'resetpass_success' => 'Уи паролыр хъуэжа хъуащ! Иджыпсту системэм йохьэ...',

# Edit page toolbar
'bold_sample'     => 'Ӏуву щӀын хьэрыфхэр',
'bold_tip'        => 'Ӏуву щӀын хьэрыфхэр',
'italic_sample'   => 'Текстыр укъуэншауэ',
'italic_tip'      => 'Хьэрыфхэр укъэншауэ щӀын',
'link_sample'     => 'ТехьэпӀэм и цIэр',
'link_tip'        => 'КІуэцІ техьэпІэ',
'extlink_sample'  => 'http://www.example.com техьэпӀэхэм я псэлъащхьэ',
'extlink_tip'     => 'ТехьэпӀэ зэIухар (зыщывмыгъэгъупщэ http:// префиксыр)',
'headline_sample' => 'Псалъащхьэм и тхылъ',
'headline_tip'    => 'ТІуанэ щхьэгъэ псалъащхьэ',
'math_sample'     => 'Мыбдеж формулэ итхэ',
'math_tip'        => 'Математикэм тещIыхьауэ формулэ (LaTeX)',
'nowiki_sample'   => 'Формациэ мыщӀа тхыгъэр мыбдеж игъэувэ',
'nowiki_tip'      => 'Вики-форматыр Iухын',
'image_tip'       => 'Файл кIуэцIылъу',
'media_tip'       => 'Файлым и техьэпӀэ',
'sig_tip'         => 'Уи ӀэпэщӀэдзымрэ зэман щытехуэмрэ',
'hr_tip'          => 'ЩӀэтхъэгъуэ щӀыхь (куэдрэ къэвмыгъэмэбэп)',

# Edit pages
'summary'                          => 'Хъуэжахэм тепсэлъыхь:',
'subject'                          => 'Темэ/псалъащхьэ:',
'minoredit'                        => 'МащIэу хъуэжа',
'watchthis'                        => 'Мы напэкIуэцIыр список узыхэплъэм хэлъхьэн',
'savearticle'                      => 'НапэкIуэцIыр итхэн',
'preview'                          => 'Япэ-еплъ',
'showpreview'                      => 'Хэплъэн япэ щIыкIэ',
'showdiff'                         => 'ЗэхъуэкIыныгъэ хэлъхьахэр',
'anoneditwarning'                  => "'''Гу лъытэ!''': ЗыкъебгъэцӀыхуакъым системэм. Уи IP-адресыр иритхэнущ  напэкӀуэцӀым и зэхъуэкӀыныгъэ тхыдэм.",
'missingcommenttext'               => 'Кхъа, илъабжьэм итхэ уи тхыгъэр.',
'summary-preview'                  => 'Аннотациэр:',
'blockednoreason'                  => 'щхьэусыгъуэр итхакъым',
'accmailtitle'                     => 'Пэролыр егъэхьащ.',
'newarticle'                       => '(ЩIэуэ)',
'newarticletext'                   => 'НапэкӀуэцӀ иджыри щымыӀэм утехуащ техьэпӀэмкэ.
Ар быщӀын щхьэкӀэ, и щӀагъым щӀэт игъувапӀэм тхылъ итхэ (еплъ [[{{MediaWiki:Helppage}}|щӀэупщӀэгъуэхэм я напэкӀуэцӀ]]).
ГъуэщэгъуэкӀэ мыбым утехуамэ, уи браузерым гъэзэжыгъуэ иӀэм текъузи зэфэкащ.',
'noarticletext'                    => "Иджыпсту мы напэкӀуэцӀыр нэщӀ.
Узхуэныкъуэм [[Special:Search/{{PAGENAME}}|игугъ бгъуэтыфыну]] нэгъуэщӀ напэкӀуэцӀхэм, <span class=\"plainlinks\">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} тхылъхэм абым теухуа тхыгъэхэм], иэ '''[{{fullurl:{{FULLPAGENAME}}|action=edit}} апхуэдэцӀэ зиӀэ напэкӀуцӀ быщӀыфынущ]'''</span>.",
'previewnote'                      => "'''Мыр япэ-еплъ къуэдей, тхылъыр иджыри итхакъым!'''",
'editing'                          => 'Гъэтэрэзын: $1',
'editingsection'                   => 'Гъэтэрэзын $1 (секцэр)',
'copyrightwarning'                 => "Гу лъытэ! Хэлъхьэгъу, зэхъуэкӀыгъу хъуар лицензиэ $2 къалъытэну (еплъ $1).
Ухуэмемэ уи тхыгъэхэр хуиту зэбгырагъэкӀыну, ягъэтэрэзыну зыхуеуэ хъуам, мыбдежьым йомылъхьэ.<br />
Абым пэмыкӀыу мы тхыгъэхэм уэ зиIэдакъу ущыту лъытэгъуэ ибот, иэ копиэ къипхауэ
хуиту зэхэгъэкӀынрэ, гъэтэрэзынрэ зиӀэ тхыгъэм.<br />
'''ЗИIЭДАКЪЭМ И ПӀАЛЪЭ УИМЫӀУ, ТХЫЛЪ МЫБДЕЖЬ ХОМЫЛЪХЬЭ!'''",
'templatesused'                    => '{{PLURAL:$1|Шаблон|Шаблонхэр}} напэкӀуэцӀым и версиэ екӀуэкӀым хэтхэр:',
'templatesusedpreview'             => '{{PLURAL:$1|Шаблон|Шаблонхэр}} напэкӀуэцӀым и япэ-еплъым хэтхэр:',
'template-protected'               => '(хъумащ)',
'template-semiprotected'           => '(иныкъуэр теубыдащ)',
'hiddencategories'                 => 'Мы напэкӀуэцӀыр зхэхьэр $1 {{PLURAL:$1|1 категориэ зэхуэща|$1 категориэ зэхуэщахэр}}:',
'permissionserrorstext-withaction' => "«'''$2'''» Iуэхугъуэр пщIэну ухуиткъым, абы {{PLURAL:$1|и щхьэусыгъуэр|и щхьэусыгъуэхэр}}:",

# History pages
'viewpagelogs'           => 'Мы напэкIуэцIым щхьэкӀэ тхылъыр гъэлъэгъуэн',
'currentrev-asof'        => 'Версиэ екӀуэкӀыр $1 дежь',
'revisionasof'           => '$1 версие',
'previousrevision'       => '← Ипэ итыр',
'nextrevision'           => 'КъыкӀэлъыкӀуэр →',
'currentrevisionlink'    => 'КъекIуэкI версиер',
'cur'                    => 'къекIуэкIыр',
'next'                   => 'кIэлъыкIуэр',
'last'                   => 'ипэ.',
'page_first'             => 'ипэр',
'page_last'              => 'иужьдыдэр',
'histlegend'             => "Гулъытыгъуэ: ({{int:екӀуэкӀ}}) — екӀуэкӀ версиэм зэрыщхьэщыкӀ; ({{int:япэрыт}}) — япэрыт версиэм зэрышъхьэщыкӀ; '''{{int:цӀыкӀу}}''' — цӀыкӀу зэхъуэкӀыгъуэ",
'history-fieldset-title' => 'Тхыдэм хэплъэн',
'history-show-deleted'   => 'ТегъэкIыжам фIэкI',
'histfirst'              => 'жьыдыдэхэр',
'histlast'               => 'Куэд мыщIахэр',
'historyempty'           => '(нэщIщ)',

# Revision deletion
'rev-delundel'               => 'зыIухын/зыIулъхьэн',
'rev-showdeleted'            => 'гъэлъэгъуэн',
'revdelete-show-file-submit' => 'НытIэ',
'revdel-restore'             => 'лъагъукIэр хъуэжын',

# Merge log
'revertmerge' => 'Зыхэдзын',

# Diffs
'history-title'           => '$1 - зэхъуэкIыныгъэм и тхыдэ',
'difference'              => '(Іэмалхэм я зэрызыщхьэщыкІыгъуэр)',
'lineno'                  => 'Сатыр $1:',
'compareselectedversions' => 'Хэха версиэхэр зэгъэпщэн',
'editundo'                => 'щIегъуэжын',

# Search results
'searchresults'             => 'Лъыхъуэным къыхэкӀахэр',
'searchresults-title'       => 'Ммыбы "$1" и лъыхъуэным къыхэкӀахэр',
'searchresulttext'          => 'Информациэ нэхъыбэ ухуэныкъуэмэ проэктым дежь напэкӀуэцӀ лъыхъуэнымкӀэ, еплъ [[{{MediaWiki:Helppage}}|щӀэупщӀэгъуэ лъэныкъуэм]].',
'searchsubtitle'            => 'Лъыхъуэгъуэ «[[:$1]]» ([[Special:Prefixindex/$1|напэкӀуэцӀу хъуар, апхуэдэцӀэкӀэ къэжьу]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|апхуэдэцӀэм техьэхэр]])',
'searchsubtitleinvalid'     => "ЩӀэупщӀэгъуэ '''$1'''",
'notitlematches'            => 'Зэтехуэ хэткъым напэкIуэцIхэм я цIэм',
'notextmatches'             => 'Зэтехуэ хэткъым напэкIуэцIхэм кІуэцІылъхэм',
'prevn'                     => 'япэ итар {{PLURAL:$1|$1}}',
'nextn'                     => 'яуж кӀуэр {{PLURAL:$1|$1}}',
'viewprevnext'              => 'Еплъын ($1 {{int:pipe-separator}} $2) ($3)',
'search-result-size'        => '$1 ({{PLURAL:$2|псалъэу $2|псалъэу $2|псалъэу $2}})',
'search-redirect'           => '(егъэкIуэкIын $1)',
'search-section'            => '(секцэ $1)',
'search-suggest'            => 'Узлъыхъуар мыра хъунщ: $1',
'search-interwiki-caption'  => 'Проэкт къыдэщӀхэр',
'search-interwiki-default'  => '$1 къыхэкӀар:',
'search-interwiki-more'     => '(иджыри)',
'search-mwsuggest-enabled'  => 'чэнджэщ иIэу',
'search-mwsuggest-disabled' => 'чэнджэщыншэу',
'nonefound'                 => "'''Гулъытыгъуэ.''' Тэрэзу имытхамэ узхуэныкъуэр, лъыхъуэгъуэр лъэныкъу хъуамкӀи ирегъэкӀуэкӀ. Къэгъэсэбэп ''all:'' пыгъувэгъуэр, зэгъэзэхуэгъуэ иӀэн щхьэкӀэ (хэтхэм я тепсэлъыхьыныгъэр, шаблонхэр, нымыщӀхэр джоуэ хиубыдэным щхьэ), иэ узхуэныкъуэ лъэныкъуэр итхэ.",
'powersearch'               => 'Убгъуауэ лъыхъу',
'powersearch-legend'        => 'Убгъуауэ лъыхъу',
'powersearch-ns'            => 'ЦIэзэхэтыгъуэм щылъыхъуэн',
'powersearch-redir'         => 'ЕгъэзэкӀахэри гъэлъэгъуэн',
'powersearch-field'         => 'Лъыхъуэн',

# Preferences page
'preferences'   => 'Зэгъэзэхуэпхъэхэр',
'mypreferences' => 'Си зэгъэзэхуэгъуэхэр',

# Groups
'group-sysop' => 'Тхьэмадэхэр',

'grouppage-sysop' => '{{ns:project}}:Тхьэмадэхэр',

# User rights log
'rightslog' => 'Хэтым пӀалъэ иӀэхэм я тхылъ',

# Associated actions - in the sentence "You do not have permission to X"
'action-edit' => 'мы напэкIуэцIыр гъэтэрэзын',

# Recent changes
'nchanges'                       => '$1 {{PLURAL:$1|зэхъуэкӀыгъуэ|зэхъуэкӀыгъуэхэр}}',
'recentchanges'                  => 'Гъэтэрэзыгъуэ щIэхэр',
'recentchanges-legend'           => 'Гъэтэрэзыгъуэ щӀэхэм я зэгъэзэхуэгъуэ',
'recentchanges-feed-description' => 'Вики и иужьырей зэхъуэкIыныгъэхэм кIэлъылъын мыбы и потокым.',
'rcnote'                         => "{{PLURAL:$1|Яужырей '''$1''' зэхъуэкыгъуэ|Яужырей '''$1''' зэхъуэкыгъуэхэр}} '''$2''' {{PLURAL:$2|махуэ|махуэм}}, зэман $5 $4.",
'rclistfrom'                     => 'ЗэхъуэкӀыгъуэхэр гъэлъэгъуэн $1 щыкӀэдзауэ',
'rcshowhideminor'                => '$1 мащІэу яхъуэжахэр',
'rcshowhidebots'                 => 'Боту $1',
'rcshowhideliu'                  => 'ЦIыхухэту, ихьауэ $1',
'rcshowhideanons'                => '$1 анонимну',
'rcshowhidemine'                 => '$1 сгъэтэрэзахэр',
'rclinks'                        => 'ЗэхъуэкӀыгъуэхэр яужырейхэр $1 гъэлъэгъуэн $2 махуэ<br />$3',
'diff'                           => 'зэмылI.',
'hist'                           => 'тхыдэ',
'hide'                           => 'ГъэпщкIун',
'show'                           => 'Гъэлъэгъуэн',
'minoreditletter'                => 'цӀ',
'newpageletter'                  => 'Н',
'boteditletter'                  => 'б',
'rc-enhanced-expand'             => 'Нэхъыбэ къэгъэлъагъуэн (JavaScript къегъэсэбэп)',
'rc-enhanced-hide'               => 'Гъэхуа жыІэхэр Іухын',

# Recent changes linked
'recentchangeslinked'         => 'ЗэпыщIа гъэтэрэзыгъуэхэр',
'recentchangeslinked-title'   => 'Гъэтэрэзыгъуэ зэпыщӀахэр $1 щхьэкӀэ',
'recentchangeslinked-summary' => "НапэкӀуэцӀхэм я яужырей зэхъуэкӀыгъуэхэм я тхылъ, напэкӀуэцӀ гъэлъэгъуар зтехьэ (иэ категориэ гъэлъэгъуам хэхьэ).
НапэкӀуэцӀ хэтхэр [[Special:Watchlist|уи щӀэлъыплъыгъуэм]] '''къыхэгэкӀауэ''' щытщ",
'recentchangeslinked-page'    => 'НапэкIуэцIым и цIэр:',
'recentchangeslinked-to'      => 'Пхэнжу, къэгъэлъэгъуэн напэкІуэцІхэм я хъуэжахэр, къыхэпха напэкІуэцІым къекІуэкІхэм',

# Upload
'upload'        => 'Файл илъхьэн',
'uploadlogpage' => 'Иралъхьахэм и тхыдэ-тхылъыр',
'uploadedimage' => 'изылъхьар "[[$1]]"',

# File description page
'filehist'                  => 'Файлым и тхыдэ',
'filehist-help'             => 'Махуэ/зэманым текъузэ файлыр дэпщэщ дэуэду щытами уеплъынумэ',
'filehist-current'          => 'иджырер',
'filehist-datetime'         => 'Махуэ/Зэман',
'filehist-thumb'            => 'КӀэщӀу',
'filehist-thumbtext'        => 'КӀэщӀу $1 версиэм щхьэкӀэ',
'filehist-user'             => 'ЦIыхухэт',
'filehist-dimensions'       => 'инагъыр',
'filehist-comment'          => 'Гулъытыгъуэ',
'imagelinks'                => 'Файлым и техьэпӀэхэр',
'linkstoimage'              => '{{PLURAL:$1|Мы напэкӀуэцӀыр $1 тохьэ|Мы напэкӀуэцӀхэр $1 тохьэхэ}} мы файлым:',
'sharedupload'              => 'Мы файлыр $1 ящыщ, нэмыщӀ проэктхэми къагъэсэбэпыфыну.',
'uploadnewversion-linktext' => 'Файлым и версиэщIэ илъхьэн',

# Statistics
'statistics' => 'Статистикэ',

# Miscellaneous special pages
'nbytes'        => '$1 {{PLURAL:$1|байт|байту|байтхэр}}',
'nmembers'      => '$1 {{PLURAL:$1|лъэныкъуэ|лъэныкъуэм|лъэныкъуэхэр}}',
'prefixindex'   => 'Мы префикс зыIэ напэкIуэцIу хъуар',
'newpages'      => 'НапэкIуэцIыщIэхэр',
'move'          => 'ЦӀэр хъуэжын',
'movethispage'  => 'Мы напэкӀуэцӀым и цӀэр хъуэжын',
'pager-newer-n' => '{{PLURAL:$1|нэхъ щӀэуэ 1|нэхъ щӀэуэху $1}}',
'pager-older-n' => '{{PLURAL:$1|нэхъ жьыуэ 1|нэхъ жьыху $1}}',

# Book sources
'booksources'               => 'Тхылъ къыздихар',
'booksources-search-legend' => 'Тхылъым и хъыбар лъыхъуэн',
'booksources-go'            => 'Къэгъуэтын',

# Special:Log
'log' => 'Тхылъхэр',

# Special:AllPages
'allpages'       => 'НапэкIуэцIухъуар',
'alphaindexline' => '$1-м щыщIэдзауэ $2-м нэс',
'prevpage'       => 'Япэреуэ кӀуа напэкIуэцIыр ($1)',
'allpagesfrom'   => 'МыбыкIэ щIидзэ напэкIуэцIхэр къихын:',
'allpagesto'     => 'Къихыныр къэгъэувыIэн:',
'allarticles'    => 'НапэкIуэцIухъуар',
'allpagessubmit' => 'ЩIын',

# Special:LinkSearch
'linksearch' => 'КІуэцІ техьэпІэхэр',

# Special:Log/newusers
'newuserlogpage'          => 'ЦIыхухэтхэм зэрызыратхэм и къебжэкI',
'newuserlog-create-entry' => 'ЦIыхухэтыщIэ',

# Special:ListGroupRights
'listgrouprights-members' => '(гупым и тхылъ)',

# E-mail user
'emailuser' => 'Тхыгъэ хуэтхын',

# Watchlist
'watchlist'         => 'Си кӀэлъыплъыгъуэхэм я тхылъ',
'mywatchlist'       => 'СызыкӀэлъыплъхэм я тхылъ',
'addedwatch'        => 'КIэлъыплъыгъуэхэм я тхылъым хэтхащ',
'addedwatchtext'    => 'НапэкӀуцӀ «[[:$1]]» уи [[Special:Watchlist|щӀэлъыплъыгъуэхэм я тхылъым]] халъхьа.
ЯужкӀэ мы напэкӀуэцӀым и зэхъуэкӀыныгъэхэмрэ абым епха напэкӀуэцӀ тепсэлъыхьыныгъэмрэ мы тхылъым къыхэкӀху хъунущ, икӀи хьэрыф бгъуэуэ къыхэгъэкӀауэ напэкӀуэцӀ [[Special:RecentChanges|зэхъуэкӀыныгъэ щӀэхэм я тхылъэхэм]] хэтыну, нэхъыфӀу къэлъэгъуным щхьэкӀэ.',
'removedwatch'      => 'ЩӀэлъыплъыгъуэ тхылъым хэгъэкӀыжащ',
'removedwatchtext'  => 'НапэкӀуэцӀ «[[:$1]]» уи [[Special:Watchlist|щӀэлъыплъыгъуэ тхылъым]] хэгъэкӀа.',
'watch'             => 'КӀэлъплъын',
'watchthispage'     => 'НапэкIуэцIым кIэлъыплъын',
'unwatch'           => 'КIэлъымыплъын',
'watchlist-details' => 'Уи щӀэлъыплъыгъуэ тхылъым $1 {{PLURAL:$1|напэкӀуэцӀ|напэкӀуэцӀу}}, напэкӀуэцӀ тепсэлъыхьыгъуэхэр хэмыту',
'wlshowlast'        => 'Гъэлъэгъуэн кӀуа $1 сэхьэтым $2 махуэ $3',
'watchlist-options' => 'ЩӀэлъыплъыгъуэхэм я тхылъ зэгъэзэхуэгъуэ',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'СызыкӀэлъыплъ тхылъым хэлъхьэн...',
'unwatching' => 'СызыкӀэлъыплъ тхылъым хэхын',

# Delete
'deletepage'            => 'НапэкIуэцIыр ихын',
'confirmdeletetext'     => 'НапэкІуэцІыр (иэ сурэтыр) зэрщыту ирахыным, и тхыдэ зэхъуэкІыгъуэри игъусу ущІэлъэуащ.
Иджыри зэ, аразыгъуэ етын щхьэкІэ абым техьэж, уи гугъэм къыхэкІынур къэзэрыбгурыІуэр гъэунэхуным щхьэкІэ, а быщІэри [[{{MediaWiki:Policy-url}}]] итым и хабзэм зэртехуэр.',
'actioncomplete'        => 'Лэжьыгъэр гъэзэнщIащ',
'deletedtext'           => '«<nowiki>$1</nowiki>» ираха.
Еплъ $2 яужыреуэ ирахахэм ярахахэм я тхылъ.',
'deletedarticle'        => 'ихащ «[[$1]]»',
'dellogpage'            => 'Ирахыжахэм я тхылъ',
'deletecomment'         => 'Щхьэусыгъуэ:',
'deleteotherreason'     => 'НэгъуэщI щхьэусыгъуэ/щIыгъупхъэ:',
'deletereasonotherlist' => 'НэгъуэщI щхьэусыгъуэ',

# Rollback
'rollbacklink' => 'къегъэзэн',

# Protect
'protectlogpage'              => 'Протектым и тхылъ',
'protectedarticle'            => 'Хъумэным щӀэт напэкӀуэцӀ «[[$1]]»',
'modifiedarticleprotection'   => 'НапэкӀуэцӀ "[[$1]]" и хъумэныгъэпӀэр зэхъуэкӀауэ щытщ',
'protectcomment'              => 'Щхьэусыгъуэ:',
'protectexpiry'               => 'Еухыр:',
'protect_expiry_invalid'      => 'Хъумэным и зэманыр щиухар тэрэзкъым.',
'protect_expiry_old'          => 'Иухыгъуэ зэманыр - блэкӀам.',
'protect-text'                => "Мыбдежым уэплъыфыну икӀи  напэкӀуэцӀ '''<nowiki>$1</nowiki>''' и хъумэныгъэр зэпхъуэкӀыфыну.",
'protect-locked-access'       => "Уи аккаунтым пӀалъэ иӀэр ирикъукъым хъумэныгъэр зэхъуэкӀыным щхьэкӀэ. ЕкӀуэкӀ зэгъэзэхуэгъуэхэр напэкӀуэцӀ '''$1''' щхьэкэ:",
'protect-cascadeon'           => 'Мы напэкӀуэцӀыр хъумэным щӀэтщ, ар зытеухуар {{PLURAL:$1|яужкӀэ напэкӀуэцӀ итым| яужкӀэ напэкӀуэцӀ итхэм}} каскад хъумэныгъэ зэратетым щхьэкӀэ. Мы напэкӀуэцӀым и хъумэныгъэр зэпхъуэкӀыфыну, ауэ каскад хъумэныгъэм ар зыкӀи къыхуэхъунукъым.',
'protect-default'             => 'Хъумэншэщ',
'protect-fallback'            => '"$1" пӀалъэ ухуэныкъуэщ',
'protect-level-autoconfirmed' => 'ЦӀыхухэтыщӀэмрэ щӀэуэ къыхыхьахэмрэ щыхъумэн',
'protect-level-sysop'         => 'Тхьэмадэхэм фӀэкӀа',
'protect-summary-cascade'     => 'каскаду',
'protect-expiring'            => 'йокӀыр $1 (UTC)',
'protect-cascade'             => 'НапэкӀуэцӀыр хъумэн, напэкӀуэцӀым хэтхэри (каскаднэ хъумэныгъэ)',
'protect-cantedit'            => 'Мы напэкӀуэцӀым и хъумэныгъэр пхъуэжыфынукъым, абым щхьэкӀэ пӀалъэ зыхуэныкъуэр уиӀэкъым.',
'restriction-type'            => 'ПӀалъэр:',
'restriction-level'           => 'Хуитыныгъэм и уровень:',

# Undelete
'undeletelink'     => 'хэплъэн/ипIэ игъэувэжын',
'undeletedarticle' => 'зыщIыжар "[[$1]]"',

# Namespace form on various pages
'namespace'      => 'ЦIэхэм и пространствэ:',
'invert'         => 'Гу лъытэн',
'blanknamespace' => '(Нэхъыщхьэ)',

# Contributions
'contributions'       => 'ЦIыхухэты хилъхьахэр',
'contributions-title' => 'Хэтым и хэлъхьэгъуэ $1',
'mycontris'           => 'Си хэлъхьэгъуэхэр',
'contribsub2'         => 'Хэлъхьэгъуэ $1 ($2)',
'uctop'               => '(яужырер)',
'month'               => 'Мазэм щыщIэдзауэ (икIи нэхъ пасэу):',
'year'                => 'Мы илъэсым щыщIэдзауэ (е нэхъпасэжу):',

'sp-contributions-newbies'  => 'Аккаунт щӀэхэм я хэлъхьэгъуэ къуэдер гъэлъэгъуэн',
'sp-contributions-blocklog' => 'теубыдыныгъэхэр',
'sp-contributions-search'   => 'Хэлъхьэгъуэм лъыхъуэн',
'sp-contributions-username' => 'IP-адрес иэ хэтым и цIэр:',
'sp-contributions-submit'   => 'Къэгъуэтын',

# What links here
'whatlinkshere'            => 'ТехьэпӀэхэр мыбдеж',
'whatlinkshere-title'      => '«$1» техьэ напэкІуэцІхэр',
'whatlinkshere-page'       => 'НапэкIуэцI:',
'linkshere'                => "Мыбым '''[[:$1]]'''  тохьэ напэкӀуэцӀхэр:",
'isredirect'               => 'напэкIуэцI-егъэкIуэкIа',
'istemplate'               => 'хэгъэхьэныгъэ',
'isimage'                  => 'сурэтым и техьэпӀэ',
'whatlinkshere-prev'       => '{{PLURAL:$1|япэрыт|япэрытхэр $1}}',
'whatlinkshere-next'       => '{{PLURAL:$1|къыкӀэлъыкӀуэр|къыкӀэлъыкӀуэхэр $1}}',
'whatlinkshere-links'      => '← техьэпӀэхэр',
'whatlinkshere-hideredirs' => '$1 уезыгъэкІуэкІхэр',
'whatlinkshere-hidetrans'  => '$1 хэтхэныгъэхэр',
'whatlinkshere-hidelinks'  => '$1 техьэпӀэхэр',
'whatlinkshere-filters'    => 'Филтырхэр',

# Block/unblock
'blockip'                  => 'Теубыдэн',
'ipboptions'               => 'сэхьэтитӀ:2 hours,1 махуэ:1 day,махуищ:3 days,1 тхьэмахуэ:1 week,тхьэмахуитӀ:2 weeks,1 мазэ:1 month,мазищ:3 months,мазих:6 months,1 илъэс:1 year,сытщыгъуи:infinite',
'ipblocklist'              => 'IP-адресрэ аккаунт зэтеубыдахэр',
'blocklink'                => 'гъэбыдэн',
'unblocklink'              => 'зэӀухыжьын',
'change-blocklink'         => 'блокировкэр зэхъуэкIын',
'contribslink'             => 'хэлъхьэгъуэ',
'blocklogpage'             => 'Теубыдыныгъэхэм я тхылъ',
'blocklogentry'            => 'Триубыда [[$1]] $2 $3 нэгъунэ',
'unblocklogentry'          => 'Теубыдыныгъэр $1 триха',
'block-log-flags-nocreate' => 'аккаунт регистрациэхэр теубыдауэ щытщ',

# Move page
'movepagetext'     => "ИщӀагъым щӀэт формэр къэбгъэсэбэпмэ, напэкӀуэцӀым и цӀэр зэпхъуэкӀыну, и зэхъуэкӀыгъуэхэм я тхыдэ-тхылъри дэщӀыгъу пӀэ щӀэм иувэнущ.
И цӀэжъым и цӀэщӀэмкӀэ тригъыхьурэ ищӀынущ.
Автоматику къэбгъэщӀэрэщӀыжьыфыну тегъэхьэпӀэр, цӀэжьым тетыр.
Ар умыщӀэмэ, щӀэлъыплъ, [[Special:DoubleRedirects|тӀуанэ]] иэ [[Special:BrokenRedirects|зэбгырыдза тегъэхьэпӀэхэр]] щымыӀэмэ.
Уи пщэм илъщ техьэпэхэр зыхуэныкъуэ лъэныкъуэмкӀэ трагъэхьэным щхьэкӀэ.

Гу лъытэ! НапэкӀуэцӀым и цӀэр зэхъуэкӀынукъым, апхуэдэцӀэ зэрихьу напэкӀуэцӀ щыӀэхэмэ, иэ тегъэувауэ иэ гъэтэрэзыгъуэхэм я тхыдэр нэщӀу щытмэ.
Абым къикӀыр напэкӀуэцӀым и цӀэуэ щытар аргуэру тепӀуэжыфынущ, зэрихьа къуэдеуэ щытам, щуэгъуэкӀэ зэпхъуэкӀамэ, ауэ щыуэгъуэкӀэ напэкӀуэцӀ щыӀэр тебгъэкӀыфынукъым.

'''ГУЛЪЫТЫГЪУЭ!'''
ЦӀэзэхъуэкӀыным напэкӀуцӀ ''цӀэрыӀуэхэм'' гузэвэгъуэ узэмжа бжыгъэхэр къыхэкӀыфынущ.
Арэзыгъуэ иӀэн щхьэкӀэ иджыри зэ егушыпс а быщӀэм къыхэкӀынур къызэрыбгурыӀуэр.",
'movepagetalktext' => "НапэкӀуэц тепсэлъыхьыгъуэ хэлъхьами автоматику и цӀэр зэхъуэкӀыну, '''мыбы хуэдэхэм щымыхъукӀэ:'''

* Мы нэщӀу, напэкӀуэцӀ тепсэлъыхьыгъуэ щыӀэщ, апхуэдэ цӀэ зэрихьу иэ
* ИщӀыгъ-игъувапӀэм деж щӀэптхъакъым.

Апхуэдэм дежь, напэкӀуэцӀхэр уэр-уэру ӀэрыщӀу зэхэбгъэхьэжынущ, хуэныкъу щытмэ.",
'movearticle'      => 'НапэкӀуэцӀым и цӀэр хъуэжын',
'newtitle'         => 'ЩIэуэ и цIэр',
'move-watch'       => 'НапэкІуэцІыр узыкІэлъыплъ къебжэкІым хэтхэн',
'movepagebtn'      => 'НапэкӀуэцӀым и цӀэр хъуэжын',
'pagemovedsub'     => 'НапэкIуэцIым и цIэр хъуэжащ',
'movepage-moved'   => "'''«$1» напэкIуэцIым и цIэр хъуэжащ мыпхуэдэу: «$2»'''",
'articleexists'    => 'Апхуэдэ цӀэ зезыхьэ напэкӀуэц щыӀэщ, иэ хуэмыкӀуэ цӀэ иптха.
НэмыщӀ цӀэ къыхэхын хуеуэ щытщ.',
'talkexists'       => "'''НапэкӀуэцӀым и цӀэр зэхъуэкӀащ, ауэ напэкӀуэцӀ тепсэлъыхьыгъуэм и цӀэр зэпхъуэкӀ хъукъым, апхуэдэ напэкӀуэцӀ зэрщыӀэм щхьэкӀэ. Уэр-уэру ӀэрыщӀкӀэ зэхэбгъэхьэн хуэйхэ.'''",
'movedto'          => 'зэдзэкIащ мыпхуэдэу',
'movetalk'         => 'НапэкӀуэцӀ тепсэлъыхьыгъуэ теухуам и цӀэр зэхъуэкӀын',
'1movedto2'        => '«[[$1]]» - мыбы къикӀыу «[[$2]]» - мыпхуэдэу и цӀэр хъуэжын',
'1movedto2_redir'  => '«[[$1]]»-м и цIэр хъуэжащ «[[$2]]» перенаправлением ищхьэкIэ',
'movelogpage'      => 'ЦӀэхъуэкӀынхэм я тхылъ',
'movereason'       => 'Щхьэусыгъуэ:',
'revertmove'       => 'гъэзэжын',

# Export
'export' => 'НапэкIуэцIхэр экспорт щIын',

# Thumbnails
'thumbnail-more' => 'Ин щIын',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Уи хэтыгъуэм и напэкIуэцI',
'tooltip-pt-mytalk'               => 'Уи тепсэлъыхьуэгъуэм напэкIуэцIыр',
'tooltip-pt-preferences'          => 'Уи зэгъэзэхуапхъэхэр',
'tooltip-pt-watchlist'            => 'НапэкӀуэцӀхэм я тхылъ, зи зэхъуэкӀыныгъэхэм узкэлъыплъ',
'tooltip-pt-mycontris'            => 'Уи гъэтэрэзыгъуэхэм я тхылъ',
'tooltip-pt-login'                => 'Мыбдеж системэм зиптхэфынущ, ауэ ар Iэмалыншэкъым.',
'tooltip-pt-logout'               => 'ИкӀыжын',
'tooltip-ca-talk'                 => 'НапэкIуэцIым итым тепсэлъыхьын',
'tooltip-ca-edit'                 => 'НапэкIуэцIыр пхъуэж хъунущ. Иптхэным ипэ къихуэу еплъыж.',
'tooltip-ca-addsection'           => 'КъудамэщIэ щIэдзэн',
'tooltip-ca-viewsource'           => 'Мы напэкIуэцIыр и зэхъуэкIыныгъэр гъэбыдащ, ауэ ухуитщ къызыхэкIа текстым уеплъынууи копие пщIынууи',
'tooltip-ca-history'              => 'НапэкIуэцIым и зэхъуэкӀынэгъэ тхыдэтхылъ',
'tooltip-ca-protect'              => 'Хъуэжьынхэм мы напэкӀуэцӀыр щыхъумэн',
'tooltip-ca-delete'               => 'Мы напэкIуэцIыр ихын',
'tooltip-ca-move'                 => 'НапэкӀуэцӀым и цӀэр хъуэжын',
'tooltip-ca-watch'                => 'Мы напэкIуэцIыр узыкІэлъыплъ тхылъым хэлъхьэн',
'tooltip-ca-unwatch'              => 'Мы напэкIуэцIыр узыкІэлъыплъ тхылъым хэхын',
'tooltip-search'                  => 'Мы псалъэр къэлъыхъуэн',
'tooltip-search-go'               => 'Мыпхуэдабзэ цIэ зиIэ напэкIуэцIым кIуэн',
'tooltip-search-fulltext'         => 'Мы тхылъыр зыхэт напэкIуэцIхэр къэгъуэтын',
'tooltip-n-mainpage'              => 'НапэкIуэцI нэхъыщхьэм кIуэн',
'tooltip-n-mainpage-description'  => 'НапэкIуэцI нэхъыщхьэм кIуэн',
'tooltip-n-portal'                => 'Проетым теухуауэ, уэ епщIэфынур, дэнэ сыт щыIэми',
'tooltip-n-currentevents'         => 'Хъыбар къекуэкӀхэм я тхылъ',
'tooltip-n-recentchanges'         => 'Иужьырей зэхъуэкIыныгъэхэм я тхылъ',
'tooltip-n-randompage'            => 'ЗэрамыщIэкIэ хэха напэкIуэцI еплъын',
'tooltip-n-help'                  => 'Проэктым и дэIэпыкъуэгъу «{{SITENAME}}»',
'tooltip-t-whatlinkshere'         => 'Мы напэкIуэцым и цIэр къэзыIу хъуам  я тхылъ',
'tooltip-t-recentchangeslinked'   => 'Мы напэкӀуэцӀым зызэхуигъазэ напэкӀуэцӀхэм я яужьрей зэхъуэкӀыныгъэхэр',
'tooltip-feed-rss'                => 'НапэкІуэцІым щхьэкӀэ RSS пыщІэн',
'tooltip-feed-atom'               => 'НапэкІуэцІым щхьэкӀэ Atom пыщІэн',
'tooltip-t-contributions'         => 'НапэкӀуэцэм я тӀхылъ, мы хэтым зэрихъуэкӀаху',
'tooltip-t-emailuser'             => 'Мы хэтым и e-mail-м хуэтхын',
'tooltip-t-upload'                => 'Файл илъхьэн',
'tooltip-t-specialpages'          => 'Лэжьыгъэ напэкIуэцIхэм я тхылъ',
'tooltip-t-print'                 => 'НапэкIуэцIым и версие, печатым щхьа',
'tooltip-t-permalink'             => 'Мы напэкуэцым и версиэ зэмыхъуэкӀым и техьэпӀэ',
'tooltip-ca-nstab-main'           => 'Тхыгъэм кӀуэцӀылъыр',
'tooltip-ca-nstab-user'           => 'ЦIыхухэтым и напэкIуэцIыр къэгъэлъэгъуэн',
'tooltip-ca-nstab-special'        => 'Лэжыгъэ напэкӀуэцӀ, гъэтэрэзыным щхьэкӀэ зэтеубыдауэ щытщ',
'tooltip-ca-nstab-project'        => 'НапэкІуэцІым и проэкт',
'tooltip-ca-nstab-image'          => 'Файлым и напэкIуэцI',
'tooltip-ca-nstab-template'       => 'Шаблоным и напэкӀуэцӀ',
'tooltip-ca-nstab-category'       => 'Категориэм и напэкIуэцI',
'tooltip-minoredit'               => 'ЗэхъуэкІыныгъэр жьгъейуэ къэлъытэн',
'tooltip-save'                    => 'Уи гъэтэрэзыгъуэхэр хъумэн',
'tooltip-preview'                 => 'НапэкІуэцІым и япэ-еплъ, итхэным ипэ къэгъэсэбэп!',
'tooltip-diff'                    => 'Япэрей тхыгъэм и хуэлъытыгъуэкӀэ зэхъуэкӀыныгъэ халъхьэхэр гъэлъэгъуэн.',
'tooltip-compareselectedversions' => 'Версиэ тӀууэ хэхам я зэщхьэщыкыгъуэр гъэлъэгъуэн',
'tooltip-watch'                   => 'Мы напэкIуэцIыр уи кӀэлъыплъыгъуэ тхылъым хэлъхьэн',
'tooltip-rollback'                => 'Зы текъузэкIэ зэхъуэкIыныгъэхэр Ӏухын, яужырей редакторым ищIахэр',
'tooltip-undo'                    => 'Гъэтэрэзыгъуэ хэлъхьар Ӏухауэ япэ-еплъ щӀын, хэхыжыным гурыӀуэгъуэ иӀу гъэлъэгъуэным щхьэкӀэ.',

# Browsing diffs
'previousdiff' => ' ← Япэрыт гъэтэрэзыгъуэр',
'nextdiff'     => 'КъыкIэлъыкIуэ гъэтэрэзыгъуэр →',

# Media information
'file-info-size'       => '($1 × $2 пикселу, файлым и инагъыр: $3, MIME-тип: $4)',
'file-nohires'         => '<small>Ин плъыфэу къэгъэлъэгъуэн щыIэкъым.</small>',
'svg-long-desc'        => '(SVG файл, номиналу $1 × $2 пиксел, файлым и инагъыр: $3)',
'show-big-image'       => 'Сурэтыр нэхъ къабзэу',
'show-big-image-thumb' => '<small>Япэ-еплъым и инагъ: пиксел: $1 × $2</small>',

# Bad image list
'bad_image_list' => 'Форматыр зэрщытын хуэр:

Гулытэ зиӀэнур тхылъым и дэмыгъэхэм (сатыр дэмыгъэ * къежьэхэр).
Сатырым и техьэпӀэ япэрейм сурэт иплъхьэ мыхъунум и техьэпӀэ иӀэн хуэй.
Яужырыт техьэпӀэхэр хэгъэкӀа хуэду къилъытэну, сурэтыр здиплъэ хъуну тхыгъэхэр.',

# Metadata
'metadata'          => 'Метаданнэхэр',
'metadata-help'     => 'Файлым дэӀэпыкъуэгъу кэӀуэтэгъуэхэр хэлъ, диджитал камерэхэм иэ сканерхэм халъхьэхэр. Файлыр хэлъхьа яуж ягъэтэрэзамэ, къэӀуэтэгъуэ гуэрэхэр сурэт итым ехэм темыхуэнкӀэ хъунущ.',
'metadata-expand'   => 'Дээпыкъуэгъу кэӀуэтэгъуэхэр гъэлъэгъуэн',
'metadata-collapse' => 'Дээпыкъуэгъу кэӀуэтэгъуэхэр гъэпшкӀун',
'metadata-fields'   => 'Метаданнэхэр, мыбы кърибжэкІхэр къызэрыгуэкІыу сурэтым и напэкІуэцІым къщридзэнущ, адрейхэр гъэпщкІуау щытынущ.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* isospeedratings
* focallength',

# External editor support
'edit-externally'      => 'Файлыр гъэтэрэзын, нэгъуэщӀ программэ и сэбэпкӀэ',
'edit-externally-help' => '(нэхъыбу еплъ [http://www.mediawiki.org/wiki/Manual:External_editors илъхьэным и тепсэлъыхьыгъуэ])',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'псори',
'namespacesall' => 'псори',
'monthsall'     => 'псори',

# Watchlist editing tools
'watchlisttools-view' => 'Тхылъым хэт напэкIуэцIхэм щыщу хъуэжахэр',
'watchlisttools-edit' => 'Еплъын/гъэтэрэзын тхылъыр',
'watchlisttools-raw'  => 'Тхылъ хуэдэу гъэтэрэзын',

# Special:SpecialPages
'specialpages' => 'СпецнапэкIуэцIхэр',

);
