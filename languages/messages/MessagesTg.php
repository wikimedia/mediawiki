<?php
/** Tajik (Тоҷикӣ)
 *
 * @addtogroup Language
 *
 * @author Francis Tyers
 * @author SPQRobin
 * @author Gangleri
 * @author Soroush
 * @author G - ג
 * @author FrancisTyers
 */

$namespaceNames = array(
	NS_MEDIA          => "Медиа",
	NS_SPECIAL        => "Вижа",
	NS_MAIN           => "",
	NS_TALK           => "Баҳс",
	NS_USER           => "Корбар",
	NS_USER_TALK      => "Баҳси_корбар",
	# NS_PROJECT set by $wgMetaNamespace
	NS_PROJECT_TALK   => "Баҳси_$1",
	NS_IMAGE          => "Акс",
	NS_IMAGE_TALK     => "Баҳси_акс",
	NS_MEDIAWIKI      => "Медиавики",
	NS_MEDIAWIKI_TALK => "Баҳси_медиавики",
	NS_TEMPLATE       => "Шаблон",
	NS_TEMPLATE_TALK  => "Баҳси_шаблон",
	NS_HELP           => "Роҳнамо",
	NS_HELP_TALK      => "Баҳси_роҳнамо",
	NS_CATEGORY       => "Гурӯҳ",
	NS_CATEGORY_TALK  => "Баҳси_гурӯҳ",
);

$linkTrail = '/^([a-zабвгдеёжзийклмнопрстуфхчшъэюяғӣқўҳҷцщыь]+)(.*)$/sDu';

$messages = array(
# User preference toggles
'tog-numberheadings' => 'шуморагузори~и худкори инвонҳо',
'tog-showtoolbar'    => 'Намойиши навори абзори виройиш (JavaScript)',
'tog-previewonfirst' => 'Нишондодани пешнамойиш дар нухустин виройиш',

# Dates
'january'     => 'Январ',
'april'       => 'Апрел',
'july'        => 'Июл',
'august'      => 'Август',
'january-gen' => 'Январ',
'april-gen'   => 'Апрел',
'july-gen'    => 'Июл',
'august-gen'  => 'Август',
'apr'         => 'Апр',
'aug'         => 'Авг',

'about'      => 'Дар бораи',
'mytalk'     => 'Гуфтугӯи ман',
'anontalk'   => 'Баҳс бо ин IP',
'navigation' => 'Гаштан',

'help'           => 'Роҳнамо',
'search'         => 'Ҷустуҷӯ',
'go'             => 'Рав',
'history'        => 'Таърих',
'history_short'  => 'Таърих',
'edit'           => 'Вироиш',
'delete'         => 'Ҳазф',
'specialpage'    => 'Саҳифаи вижа',
'talk'           => 'Мубоҳисавии',
'otherlanguages' => 'бо забонҳои дигар',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'     => 'Дар бораи {{SITENAME}}',
'aboutpage'     => '{{ns:project}}:Дар бораи',
'currentevents' => 'Вокеаҳои кунунӣ',
'mainpage'      => 'Саҳифаи Аслӣ',
'portal'        => 'Вуруди корбарон',
'sitesupport'   => 'Кӯмаки молӣ',

# Short words for each namespace, by default used in the 'article' tab in monobook
'nstab-main'     => 'Мақола',
'nstab-user'     => 'Саҳифаи корбар',
'nstab-template' => 'Шаблон',
'nstab-category' => 'Гурӯҳ',

# Login and logout pages
'logout'                     => 'Хуруҷ аз систем',
'loginsuccesstitle'          => 'Вуруд бо муваффақият',
'acct_creation_throttle_hit' => 'Бубахшед, Шумо аллакай $1 ҳисобҳо сохтед. Шумо бештар сохта наметавонед.',
'accountcreated'             => 'Ҳисоби ҷадид сохта шуд',
'accountcreatedtext'         => 'Ҳисоби корбар барои $1 сохта шуд.',
'loginlanguagelabel'         => 'Забон: $1',

# Edit pages
'anoneditwarning'  => "'''Огоҳӣ:''' Шумо вуруд накардаед. Суроғаи IP Шумо дар вироишоти ин саҳифа сабт хоҳад шуд.",
'accmailtitle'     => 'Калимаи убур фиристода шуд.',
'accmailtext'      => 'Калимаи убур барои "$1" ба $2 фиристода шуд.',
'copyrightwarning' => 'Ҳамаи ҳиссагузорӣ ба {{SITENAME}} аз рӯи қонунҳои зерин $2 (нигаред $1 барои маълумоти бештар) ҳиссагузорӣ мешаванд. Агар Шумо намехоҳед, ки навиштаҷоти Шумо вироиш ва паҳн нашаванд, Шумо метавонед ин мақоларо нафиристед.<br /> Шумо ваъда медиҳед, ки худатон ин мақоларо навиштед ё ки аз сарчашмаҳои кушод нусхабардорӣ кардаед. <strong>АСАРҲОИ ҚОБИЛИ ҲУҚУҚИ МУАЛЛИФРО БЕ ИҶОЗАТ НАФИРИСТЕД!</strong>',

# Preferences page
'preferences' => 'тарҷиҳот',
'allowemail'  => 'Иҷозат додани e-mail аз дигар корбарон',

# Recent changes
'recentchanges' => 'Таъғироти охирин',

# Recent changes linked
'recentchangeslinked' => 'Таъғироти монандӣ',

# Upload
'upload' => 'Фиристодани файл',

# Image list
'ilsubmit'       => 'Ҷустуҷӯи',
'imagelist_user' => 'Корбар',

# Miscellaneous special pages
'allpages'     => 'Ҳамаи саҳифаҳо',
'randompage'   => 'Саҳифаҳои тасодуфӣ',
'specialpages' => 'Саҳифаҳои вижа',
'ancientpages' => 'Саҳифаҳои кӯҳнатарин',
'move'         => 'Кӯчонидан',

'alphaindexline' => '$1 то $2',

# Special:Log
'all-logs-page' => 'Ҳамаи сабтҳо',

# Special:Allpages
'allarticles'    => 'Ҳамаи мақолаҳо',
'allinnamespace' => 'Ҳамаи саҳифаҳо ($1 namespace)',
'allpagesprev'   => 'Пешина',
'allpagesnext'   => 'Баъдина',
'allpagessubmit' => 'Рав',

# E-mail user
'emailuser' => 'Фиристодани email ба ин корбар',

# Watchlist
'watchlist'      => 'Феҳристи назаротӣ ман',
'addedwatchtext' => "Ин саҳифа \"[[:\$1]]\" ва [[{{ns:special}}:Watchlist|феҳристи назаротӣ]] Шумо илова шуд.
Дигаргуниҳои ояндаи ин саҳифа ва саҳифи баҳси алоқаманд дар рӯихати онҷо хоҳад шуд,
ва саҳифа '''ғафс''' дар [[{{ns:special}}:Recentchanges|рӯихати тағйироти охирин]] барои бо осони дарёфт кардан хоҳад ба назар расид.

Агар шумо дертар аз феҳристи назаротатон ин саҳифаро ҳазв кардан хоҳед, дар меню \"Назар накардан\"-ро пахш кунед.",
'watch'          => 'Назар кардан',
'unwatch'        => 'Назар накардан',

# Delete/protect/revert
'actioncomplete' => 'Амал иҷро шуд',

# Contributions
'mycontris' => 'Хиссагузории ман',

# What links here
'whatlinkshere' => 'Пайвандҳои дар ин сахифа',

# Block/unblock
'ipbreason' => 'Сабаб',

# Namespace 8 related
'allmessages'         => 'Пайёмҳои системавӣ',
'allmessagesname'     => 'Ном',
'allmessagesdefault'  => 'Матни қарордодӣ',
'allmessagescurrent'  => 'Матни кунунӣ',
'allmessagesfilter'   => 'Филтри номи пайём:',
'allmessagesmodified' => 'Фақат тағйирдодаро нишон деҳ',

# Attribution
'and' => 'ва',

# 'all' in various places, this might be different for inflected languages
'imagelistall' => 'ҳама',

# Auto-summaries
'autosumm-new' => 'Саҳифаи нав: $1',

# Iranian month names
'iranian-calendar-m1'  => 'Ҳамал',
'iranian-calendar-m2'  => 'Савр',
'iranian-calendar-m3'  => 'Ҷавзо',
'iranian-calendar-m4'  => 'Саратон',
'iranian-calendar-m5'  => 'Асад',
'iranian-calendar-m6'  => 'Сунбула',
'iranian-calendar-m7'  => 'Мизон',
'iranian-calendar-m8'  => 'Ақраб',
'iranian-calendar-m9'  => 'Қавс',
'iranian-calendar-m10' => 'Ҷадӣ',
'iranian-calendar-m11' => 'Далв',
'iranian-calendar-m12' => 'Ҳут',

);
