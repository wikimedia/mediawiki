<?php
/** Eastern Mari (олык марий)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Amdf
 * @author Azim
 * @author Jose77
 * @author Kaganer
 * @author Lifeway
 * @author Сай
 * @author Санюн Вадик
 */

$fallback = 'ru';

$namespaceNames = array(
	NS_SPECIAL          => 'Лӱмын_ыштыме',
	NS_TALK             => 'Каҥашымаш',
	NS_USER             => 'Пайдаланыше',
	NS_USER_TALK        => 'Пайдаланышын_каҥашымашыже',
	NS_PROJECT_TALK     => '$1ын_каҥашымашыже',
	NS_FILE             => 'Файл',
	NS_FILE_TALK        => 'Файл_шотышто_каҥашымаш',
	NS_TEMPLATE         => 'Кышкар',
	NS_TEMPLATE_TALK    => 'Кышкар_шотышто_каҥашымаш',
	NS_HELP             => 'Полшык',
	NS_HELP_TALK        => 'Полшык_шотышто_каҥашымаш',
	NS_CATEGORY         => 'Категорий',
	NS_CATEGORY_TALK    => 'Категорий_шотышто_каҥашымаш',
);

$namespaceAliases = array(
	// Fallbacks for all 'ru' namespace aliases
	'Медиа'                              => NS_MEDIA,
	'Служебная'                          => NS_SPECIAL,
	'Обсуждение'                         => NS_TALK,
	'Участник'                           => NS_USER,
	'Обсуждение_участника'               => NS_USER_TALK,
	'Обсуждение_{{GRAMMAR:genitive|$1}}' => NS_PROJECT_TALK,
	'Файл'                               => NS_FILE,
	'Обсуждение_файла'                   => NS_FILE_TALK,
	'Обсуждение_MediaWiki'               => NS_MEDIAWIKI_TALK,
	'Шаблон'                             => NS_TEMPLATE,
	'Обсуждение_шаблона'                 => NS_TEMPLATE_TALK,
	'Справка'                            => NS_HELP,
	'Обсуждение_справки'                 => NS_HELP_TALK,
	'Категория'                          => NS_CATEGORY,
	'Обсуждение_категории'               => NS_CATEGORY_TALK,

	// Namspace changes
	'Пайдаланышын_каҥашымаш'    => NS_USER_TALK,
	'$1ын_каҥашымаш'            => NS_PROJECT_TALK,
	'Файлын_каҥашымаш'          => NS_FILE_TALK,
	'Ямдылык'                   => NS_TEMPLATE,
	'Ямдылык_шотышто_каҥашымаш' => NS_TEMPLATE_TALK,
	'Ямдылыкын_каҥашымаш'       => NS_TEMPLATE_TALK,
	'Полшыкын_каҥашымаш'        => NS_HELP_TALK,
	'Категорийын_каҥашымаш'     => NS_CATEGORY_TALK,
);

// Remove Russian aliases
$namespaceGenderAliases = array();

$specialPageAliases = array(
	'Blankpage'                 => array( 'Пуста_лаштык' ),
	'BrokenRedirects'           => array( 'Кӱрылтшӧ__вес_вере_колтымаш-влак' ),
	'Categories'                => array( 'Категорий-влак' ),
	'ComparePages'              => array( 'Лаштык-влакым_тергымаш' ),
	'Emailuser'                 => array( 'Пайдаланышылан_серышым_колташ' ),
	'Longpages'                 => array( 'Кужу_лаштык-влак' ),
	'Preferences'               => array( 'Келыштарымаш' ),
	'Recentchanges'             => array( 'Пытартыш_тӧрлатымаш-влак' ),
	'Search'                    => array( 'Кычалмаш' ),
	'Statistics'                => array( 'Иктешлымаш' ),
	'Watchlist'                 => array( 'Эскерымаш_лӱмер' ),
);

$magicWords = array(
	'img_right'                 => array( '1', 'пурла', 'справа', 'right' ),
	'img_left'                  => array( '1', 'шола', 'слева', 'left' ),
	'img_border'                => array( '1', 'чек', 'граница', 'border' ),
	'img_sub'                   => array( '1', 'йымалне', 'под', 'sub' ),
	'img_super'                 => array( '1', 'ӱмбалне', 'над', 'super', 'sup' ),
	'img_top'                   => array( '1', 'кӱшычын', 'сверху', 'top' ),
	'img_middle'                => array( '1', 'покшелне', 'посередине', 'middle' ),
	'img_bottom'                => array( '1', 'ӱлычын', 'снизу', 'bottom' ),
	'sitename'                  => array( '1', 'САЙТЛӰМ', 'НАЗВАНИЕ_САЙТА', 'SITENAME' ),
);

$messages = array(
# User preference toggles
'tog-underline' => 'Кузе кылвер-влакым ӱлычын удыралаш?',
'tog-justify' => 'Абзацым лопкыт дене тӧрлаш',
'tog-hideminor' => 'Пытартыш тӧрлатымаш-влак радам гыч изи тӧрлатымаш-влакым кораҥдаш',
'tog-hidepatrolled' => 'Тергыме тӧрлатымаш-влакым пытартыш тӧрлатымаш лӱмерыште шылташ',
'tog-newpageshidepatrolled' => 'Тергыме лаштык-влакым у лаштык лӱмерыште шылташ',
'tog-extendwatchlist' => 'Чыла вашталтышым, а пытартыш гына огылым ончыкташлан эскерыме лӱмерым кугемдаш',
'tog-usenewrc' => 'У тӧрлатымаш саемдыме лӱмерым кучылташ (JavaScript кӱлеш)',
'tog-numberheadings' => 'Вуймутым автоматик йӧн дене радамлаш',
'tog-showtoolbar' => 'Тӧрлатымаш ӱзгараҥам ончыкташ (JavaScript кӱлеш)',
'tog-showtoc' => 'Вуймут радамым ончыкташ (3 деч шуко вуймутан лаштык-влаклан)',
'tog-rememberpassword' => 'Тиде компьютерышто мыйын шолыпмутым шарнаш (эн шуко $1 {{PLURAL:$1|кечылын|кечылан}})',
'tog-watchcreations' => 'Мыйын ыштыме лаштык-влакым эскерыме лӱмерыш ешараш',
'tog-watchdefault' => 'Мыйын тӧрлатыме лаштык-влакым эскерыме лӱмерыш ешараш',
'tog-watchmoves' => 'Мыйын лӱмым вашталтыме лаштык-влакым эскерыме лӱмерыш ешараш',
'tog-watchdeletion' => 'Мыйын шӧрымӧ лаштык-влакым эскерыме лӱмерыш ешараш',
'tog-minordefault' => 'Посна каласыме огыл гын, чыла тӧрлатымашым изи тӧрлатымаш гай палемдаш',
'tog-previewontop' => 'Тӧрлатымаш тӧрза деч ончыч ончылгоч ончымашым шындаш',
'tog-previewonfirst' => 'Ончылгоч ончымашым икымше тӧрлатымаш годым ончыкташ',
'tog-nocache' => 'Лаштыкым кешироватлымым чараш',
'tog-enotifwatchlistpages' => 'Мыйын эскерыме лӱмер гыч лаштыкыште тӧрлатымыш нерген электрон почто гоч шижтараш',
'tog-enotifusertalkpages' => 'Мыйын каҥашымаш лаштыкыште тӧрлатымыш нерген электрон почто гоч шижтараш',
'tog-oldsig' => 'Кызытсе кидпале',
'tog-watchlisthideown' => 'Эскерыме лӱмер гыч мыйын тӧрлатымашым кораҥдаш',
'tog-watchlisthidebots' => 'Эскерыме лӱмер гыч бот-влакын тӧрлатымашыштым кораҥдаш',
'tog-watchlisthideminor' => 'Эскерыме лӱмер гыч изи тӧрлатымаш-влакым кораҥдаш',
'tog-ccmeonemails' => 'Моло ушнышо-влаклан колтымо серышын копийжым мыламат колташ',
'tog-diffonly' => 'Кок версийым таҥастарыме годым лаштыкыште возымым ончыкташ огыл',
'tog-showhiddencats' => 'Шылтыме категорийым ончыкташ',

'underline-always' => 'Кеч-кунам',
'underline-never' => 'Нигунам',
'underline-default' => 'Браузерысе келыштарымаш дене пайдаланаш',

# Dates
'sunday' => 'Рушарня',
'monday' => 'Шочмо',
'tuesday' => 'Кушкыжмо',
'wednesday' => 'Вӱргече',
'thursday' => 'Изарня',
'friday' => 'Кугарня',
'saturday' => 'Шуматкече',
'sun' => 'Рш',
'mon' => 'Шч',
'tue' => 'Кш',
'wed' => 'Вр',
'thu' => 'Из',
'fri' => 'Кг',
'sat' => 'Шм',
'january' => 'Шорыкйол',
'february' => 'Пургыж',
'march' => 'Ӱярня',
'april' => 'Вӱдшор',
'may_long' => 'Ага',
'june' => 'Пеледыш',
'july' => 'Сӱрем',
'august' => 'Сорла',
'september' => 'Идым',
'october' => 'Шыжа',
'november' => 'Кылме',
'december' => 'Теле',
'january-gen' => 'Шорыкйол',
'february-gen' => 'Пургыж',
'march-gen' => 'Ӱярня',
'april-gen' => 'Вӱдшор',
'may-gen' => 'Ага',
'june-gen' => 'Пеледыш',
'july-gen' => 'Сӱрем',
'august-gen' => 'Сорла',
'september-gen' => 'Идым',
'october-gen' => 'Шыжа',
'november-gen' => 'Кылме',
'december-gen' => 'Теле',
'jan' => 'Шорыкйол',
'feb' => 'Пургыж',
'mar' => 'Ӱярня',
'apr' => 'Вӱдшор',
'may' => 'Ага',
'jun' => 'Пеледыш',
'jul' => 'Сӱрем',
'aug' => 'Сорла',
'sep' => 'Идым',
'oct' => 'Шыжа',
'nov' => 'Кылме',
'dec' => 'Теле',

# Categories related messages
'pagecategories' => '{{PLURAL:$1|Категорий|Категорий}}',
'category_header' => '"$1" категорийыште лаштык-влак',
'subcategories' => 'Ӱлылкатегорий-влак',
'category-media-header' => '"$1" категорийыште файл-влак',
'category-empty' => "''Ты жаплан тиде категорийыште нимоат уке.''",
'hidden-categories' => '{{PLURAL:$1|Шылтыме категорий|Шылтыме категорий-влак}}',
'hidden-category-category' => 'Шылтымо категорий-влак',
'category-subcat-count' => '{{PLURAL:$2|Тиде категорийыш ик ӱлылкатегорий гына пура.|{{PLURAL:$1|Тыгай $1 ӱлылкатегорий|Тыгане $1 ӱлылкатегорий-влак}} тиде категорийыште, чыла $2.}}',
'category-article-count' => '{{PLURAL:$2|Тиде категорийыш ик лаштык гына пура.|{{PLURAL:$1|Тыгай $1 лаштык|Тыгане $1 лаштык-влак}} тиде категорийыште, чыла $2.}}',
'category-file-count' => '{{PLURAL:$2|Тиде категорийыш ик лаштык гына пура.|{{PLURAL:$1|$1 лаштык|$1 лаштык}} тиде категорийыште, чылажге $2.}}',
'listingcontinuesabbrev' => '(умбакыжым)',
'noindex-category' => 'Шотыш налдыме лаштык-влак',

'about' => 'Нерген',
'article' => 'Возымо лаштык',
'newwindow' => '(у тӧрзаште почылтеш)',
'cancel' => 'Чараш',
'moredotdotdot' => 'Рашрак...',
'mypage' => 'Лаштык',
'mytalk' => 'Каҥашымаш',
'anontalk' => 'Каҥашымаш тиде IP нерген',
'navigation' => 'Навигаций',

# Cologne Blue skin
'qbfind' => 'Муаш',
'qbedit' => 'Тӧрлаташ',
'qbpageoptions' => 'Тиде лаштык',
'qbmyoptions' => 'Мыйын лаштык-влак',
'qbspecialpages' => 'Лӱмын ыштыме лаштык-влак',
'faq' => 'ЧӱВаЙо (Чӱчкыдын вашлиялтше йодыш-влак)',

# Vector skin
'vector-action-addsection' => 'У ӱжашым тӱҥалаш',
'vector-action-delete' => 'Шӧраш',
'vector-action-move' => 'Кусараш',
'vector-action-protect' => 'Тӧрлатымаш деч аралаш',
'vector-action-undelete' => 'Шӧрымым пӧртылаш',
'vector-action-unprotect' => 'Оролым вашталташ',
'vector-view-create' => 'Ышташ',
'vector-view-edit' => 'Тӧрлаташ',
'vector-view-history' => 'Эртымгорным ончалаш',
'vector-view-view' => 'Лудаш',
'vector-view-viewsource' => 'Тӱҥалтыш текстым ончалаш',
'actions' => 'Сомылка-влак',
'namespaces' => 'Лӱм-влак ора',
'variants' => 'Вариант-влак',

'errorpagetitle' => 'Йоҥылыш',
'returnto' => '$1 деке пӧртылаш.',
'tagline' => '{{SITENAME}} гыч',
'help' => 'Полшык',
'search' => 'Кычалмаш',
'searchbutton' => 'Кычалаш',
'go' => 'Куснаш',
'searcharticle' => 'Куснаш',
'history' => 'Лаштыкын эртымгорно',
'history_short' => 'Эртымгорно',
'printableversion' => 'Савыкташлан келыштарыме',
'permalink' => 'Эре улшо кылвер',
'print' => 'Савык',
'edit' => 'Тӧрлаташ',
'create' => 'Ышташ',
'editthispage' => 'Тӧрлаташ тиде лаштыкым',
'create-this-page' => 'Тиде лаштыкым ышташ',
'delete' => 'Шӧраш',
'deletethispage' => 'Тиде лаштыкым шӧраш',
'protect' => 'Аралаш',
'protect_change' => 'вашталташ',
'protectthispage' => 'Тиде  лаштыкым тӧрлатымаш деч аралаш',
'newpage' => 'У лаштык',
'talkpage' => 'Тиде лаштыкым каҥашаш',
'talkpagelinktext' => 'Каҥашымаш',
'specialpage' => 'Лӱмын ыштыме лаштык',
'personaltools' => 'Паша ӱзгар',
'postcomment' => 'У ужаш',
'articlepage' => 'Лаштыкыште возымым ончыкташ',
'talk' => 'Каҥашымаш',
'views' => 'Ончалаш',
'toolbox' => 'Ӱзгар-влак',
'userpage' => 'Пайдаланышын лаштыкым ончалаш',
'imagepage' => 'Файлын лаштыкым ончалаш',
'templatepage' => 'Ямдылыкын лаштыкым ончалаш',
'viewhelppage' => 'Полшык лаштыкым ончалаш',
'categorypage' => 'Категорийын лаштыкым ончалаш',
'viewtalkpage' => 'Ончалаш каҥашымашым',
'otherlanguages' => 'Вес йылме дене',
'redirectedfrom' => '(Колтымо $1 гыч)',
'redirectpagesub' => 'Вес вере колтышо лаштык',
'lastmodifiedat' => 'Тиде лаштыкым пытартыш гана $2 $1 тӧрлымӧ.',
'protectedpage' => 'Тӧрлатымаш деч аралыме лаштык',
'jumpto' => 'Куснаш:',
'jumptonavigation' => 'навигацийыш',
'jumptosearch' => 'кычалмаш',
'pool-errorunknown' => 'Палыдыме йоҥылыш',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage).
'aboutsite' => '{{SITENAME}} нерген',
'aboutpage' => 'Project:Нерген',
'copyright' => 'Лаштыкыште возымо $1 йӧн дене почмо.',
'copyrightpage' => '{{ns:project}}:Автор кертыж',
'currentevents' => 'Кызытсе событий',
'currentevents-url' => 'Project:Мо кызыт лийын',
'disclaimers' => 'Вуйшиймаш деч кораҥмаш',
'disclaimerpage' => 'Project:Вуйшиймаш деч кораҥмаш',
'edithelp' => 'Тӧрлатымаш полыш',
'helppage' => 'Help:Полшык',
'mainpage' => 'Тӱҥ лаштык',
'mainpage-description' => 'Тӱҥ лаштык',
'portal' => 'Тӱшка',
'portal-url' => 'Project:Пашазе-влакын тӱшкашт',
'privacy' => 'Конфиденциальность политике',
'privacypage' => 'Project:Конфиденциальность политике',

'badaccess' => 'Пурымаште йоҥылыш',

'ok' => 'Йӧра',
'retrievedfrom' => 'Налме вер — "$1"',
'youhavenewmessages' => 'Тендан $1 уло ($2).',
'newmessageslink' => 'У серыш',
'newmessagesdifflink' => 'пытартыш тӧрлатымаш',
'editsection' => 'тӧрлаташ',
'editold' => 'тӧрлаташ',
'viewsourceold' => 'тӱҥалтыш текстым ончалаш',
'editlink' => 'тӧрлаташ',
'viewsourcelink' => 'тӱҥалтыш текстым ончалаш',
'editsectionhint' => '$1 ужашым тӧрлаташ',
'toc' => 'Вуйлымаш',
'showtoc' => 'ончыкташ',
'hidetoc' => 'шылташ',
'viewdeleted' => 'Ончалаш $1?',
'site-rss-feed' => '$1 RSS-кыл',
'site-atom-feed' => '$1 Atom-кыл',
'page-rss-feed' => '"$1" RSS-кыл',
'page-atom-feed' => '"$1" Atom-кыл',
'red-link-title' => '$1 (тыгай лаштык уке)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main' => 'Лаштык',
'nstab-user' => 'Пайдаланышын лаштыкше',
'nstab-media' => 'Мультимедиа',
'nstab-special' => 'Лӱмын ыштыме лаштык',
'nstab-project' => 'Проект нерген',
'nstab-image' => 'Файл',
'nstab-mediawiki' => 'Увертыш',
'nstab-template' => 'Ямдылык',
'nstab-help' => 'Полыш лаштык',
'nstab-category' => 'Категорий',

# Main script and global functions
'nosuchspecialpage' => 'Тыгай спецлаштык уке.',

# General errors
'error' => 'Йоҥылыш',
'missing-article' => 'Тыгай текст дене возымо лаштык базыште муалтын огыл, "$1" $2.

Кунам тый тоштемше кылвер почеш шӧрымӧ вашталтымаш лаштыкыш (але эртымгорно лаштыкыш) куснет, тыге лийын кертеш.

Тыге огыл гын, очыни, тый программыште йоҥылышым муынат.
Тидын нерген URL-ым ончыктен [[Special:ListUsers/sysop|сайтвиктарышым]] шижтаре.',
'missingarticle-rev' => '(тӱрлык#: $1)',
'internalerror' => 'Кӧргысӧ йоҥылыш',
'internalerror_info' => 'Кӧргысӧ йоҥылыш: $1',
'filecopyerror' => '«$1» гыч «$2» файлыш копийым ышташ ок лий.',
'fileexistserror' => '«$1» файлыш возыкым ышташ лийдыме: файл уло.',
'unexpected' => 'Келшыдыме кугыт: «$1»=«$2».',
'cannotdelete-title' => '"$1" лаштыкым шӧраш ок лий',
'badtitle' => 'Уда лӱм',
'badtitletext' => 'Йодмо лаштыкын лӱмжӧ йоҥылыш, але яра, але йылме кокла але интер-вики лӱмжӧ йоҥылыш. Але лӱмыштӧ кӱлдымӧ тамга улыт.',
'viewsource' => 'Тӱҥалтыш текст',

# Virus scanner
'virus-badscanner' => "Келыштарымаш йоҥылыш: палыдыме вирус сканер: ''$1''",
'virus-unknownscanner' => 'палыдыме антивирус:',

# Login and logout pages
'yourname' => 'Пайдаланышын лӱмжӧ:',
'yourpassword' => 'Шолыпмут:',
'yourpasswordagain' => 'Шолыпмутым угыч пуртымаш:',
'remembermypassword' => 'Тиде компьютерыште мыйым шарнаш (эн шуко $1 {{PLURAL:$1|кечылан|кечылан}})',
'yourdomainname' => 'Тендан домен:',
'login' => 'Шке денет палымым ыште',
'nav-login-createaccount' => 'Пураш/Регистрацийым эрте',
'loginprompt' => '{{SITENAME}} тый денет палыме лиймашлан, cookies чӱкталтын улшаш.',
'userlogin' => 'Шке денет палымым ыште/Регистрацийым эрте',
'logout' => 'Лекташ',
'userlogout' => 'Лекташ',
'nologin' => "Тый регистрацийым эше эртен отыл? '''$1'''.",
'nologinlink' => 'Регистрацийым эрте',
'createaccount' => 'Регистрацийым эрте',
'gotaccount' => "Тый регистрацийым эртенат? '''$1'''.",
'gotaccountlink' => 'Шке денет палымым ыште',
'userlogin-resetlink' => 'Лӱмдам але шолыпмутдам монденда?',
'createaccountmail' => 'Кӱчык жаплан чокым ыштыме шолыпмутым мылам e-mail дене колташ',
'nosuchuser' => '"$1" лӱман пайдаланыше уке.
Пайдаланышын лӱмыштӧ йӱкпале-влакын кугытшо тӱрыс лийшаш.
Лӱмым чын возымым терге але [[Special:UserLogin/signup|регистрацийым эрте]].',
'nosuchusershort' => '"$1" лӱман пайдаланыше уке.
Лӱмым чын возымым терге.',
'nouserspecified' => 'Тылат пайдаланышын лӱмжым пуртыман.',
'wrongpassword' => 'Тый йоҥылыш шолыпмутым пуртенат.
Эше ик гана ыштен ончо.',
'wrongpasswordempty' => 'Тый яра шолыпмутым пуртенат.
Эше ик гана ыштен ончо.',
'passwordtooshort' => 'Шолыпмут {{PLURAL:$1|1 символ|$1 символ}} деч шагал огыл лийшаш.',
'mailmypassword' => 'У шолыпмутым колташ',
'passwordremindertitle' => '{{SITENAME}} сайтлан жаплан ыштыме у шолыпмут',
'passwordremindertext' => '{{SITENAME}} сайтлан ($4) $1 IP адрес гыч ала кӧ (але тый шкеак) у шолыпмутым йодын. "$2" пайдаланышылан жаплан ыштыме у шолыпмутым ыштыме да "$3" электрон адресыш колтымо. Тидым тый йодынат гын, системыш у шолыпмут дене пуро.

Йодмашым вес еҥ ыштен гын, але тый шке шолыпмутетым шарненат гын, тиде увертышым шотыш налде, тошто шолыпмут дене пайдалане.',
'noemail' => '"$1" пайдаланыше электрон адресым палемден огыл.',
'passwordsent' => 'У шолыпмутым "$1" пайдаланышын электрон адресышкыже колтымо. Шолыпмутым налмеке системыш угыч пуро.',
'eauthentsent' => 'Пеҥгыдемдымаш дене серышым темлыме электрон адресыш колтымо. Электрон почто адресын тыйын улмым пеҥгыдемдаш, серышыште улшо инструкцийым шукто.',
'emailauthenticated' => 'Тыйын почто адресетым пеҥгыдемдыме $2, $3.',
'loginlanguagelabel' => 'Йылме: $1',

# Change password dialog
'resetpass' => 'Шолыпмутым вашталташ',
'oldpassword' => 'Тошто шолыпмут:',
'newpassword' => 'У шолыпмут:',
'retypenew' => 'Пеҥгыдемдыза у шолыпмутым:',
'resetpass-submit-loggedin' => 'Шолыпмутым вашталташ',

# Special:PasswordReset
'passwordreset-username' => 'Пайдаланышын лӱмжӧ',

# Edit page toolbar
'bold_sample' => 'Кӱжгӧ текст',
'bold_tip' => 'Кӱжгӧ текст',
'italic_sample' => 'Шӧрын текст',
'italic_tip' => 'Шӧрын текст',
'link_sample' => 'Кылверын лӱмжӧ',
'link_tip' => 'Кӧргысӧ кылвер',
'extlink_sample' => 'http://www.example.com кылверын лӱмжӧ',
'extlink_tip' => 'Ӧрдыж кылвер (http:// префиксым ит мондо)',
'headline_sample' => 'Вуймут',
'headline_tip' => '2-шо кӱкшытан вуймут',
'nowiki_sample' => 'Форматироватлыдыме текстым тышке шынде',
'nowiki_tip' => 'Вики-форматированийым шотыш налаш огыл',
'image_tip' => 'Пуртымо сӱрет',
'media_tip' => 'Пуртымо медиа-файл',
'sig_tip' => 'Тыйын кидпалет, шындыме жап да кече',
'hr_tip' => 'Тореш (чӱчкыдын ит кучылт)',

# Edit pages
'summary' => 'Тӧрлатымаш нерген:',
'subject' => 'Теме/вуймут:',
'minoredit' => 'Тиде изи вашталтыш',
'watchthis' => 'Тиде лаштыкым эскераш',
'savearticle' => 'Лаштыкым аралаш',
'preview' => 'Ончылгоч ончымаш',
'showpreview' => 'Ончылгоч ончымаш',
'showdiff' => 'Тӧрлатымашым ончыкташ',
'anoneditwarning' => "'''Тӱткӧ лий:''': Тый авторизацийым эртен отыл. Тыйын IP-адресет лаштыкын вашталтымаш эртымгорныштыжо возалт кодеш.",
'summary-preview' => 'Тӧрлатымаш нерген ончылгоч ончымаш:',
'accmailtitle' => 'Шолыпмут колтымо.',
'newarticle' => '(У)',
'newarticletext' => "Тыгай лӱман лаштык уке.
Лаштыкым ышташлан ӱлнӧ возаш тӱҥал (сайынрак палашлан [[{{MediaWiki:Helppage}}|полшыкым]] ончал).
Тый тышке йонгылыш логалынат гын, браузерыште '''шенгек''' полдышым темдал.",
'noarticletext' => 'Кызытсе жаплан тиде лаштыкыште нимом возымо огыл.
Тый тиде лаштыкын лӱмжым вес лаштык-влакыште [[Special:Search/{{PAGENAME}}|кычалын]] кертат, але <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} журнал-влакыште кычалын] кертат, але [{{fullurl:{{FULLPAGENAME}}|action=edit}} тыгай лӱман лаштыкым ышташ] кертат</span>.',
'clearyourcache' => "'''Замечание.''' Возможно, после сохранения вам придётся очистить кэш своего браузера, чтобы увидеть изменения.
* '''Firefox / Safari:''' Удерживая клавишу ''Shift'', нажмите на панели инструментов ''Обновить'' либо нажмите ''Ctrl-F5'' или ''Ctrl-R'' (''⌘-R'' на Mac)
* '''Google Chrome:''' Нажмите ''Ctrl-Shift-R'' (''⌘-Shift-R'' на Mac)
* '''Internet Explorer:''' Удерживая ''Ctrl'', нажмите ''Обновить'' либо нажмите ''Ctrl-F5''
* '''Opera:''' Выберите очистку кэша в меню ''Инструменты → Настройки''",
'previewnote' => "'''Тиде ончылгоч ончымаш гына;
вашталтыш-влакым эше аралыме огыл!'''",
'editing' => 'Тӧрлаталтеш $1',
'editingsection' => 'Тӧрлаталтеш $1 (ужаш)',
'yourtext' => 'Тендан текст',
'yourdiff' => 'Ойыртем',
'copyrightwarning' => "Шотыш нал, чыла пашам {{SITENAME}} проектыш $2 лицензий почеш лукмо семын шотлыман($1 ончал). 
Возыметым нигӧлан пайдаланаш, тӧрлаташ ынет пу гын тышке тудым ит шыҥдаре.<br />
Тыгак текстым шке возымо але тудым эрыкан вер гыч налме шотышто мутым пуэт.<br />
'''АВТОР АЛЕ ТУДЫН ПРАВАМ АРАЛЫШЕ-ВЛАК ДЕЧ ЙОДДЕ МАТЕРИАЛЫМ ИТ ШЫҤДАРЕ!'''",
'templatesused' => 'Тиде лаштыкыште кучылтмо {{PLURAL:$1|ямдылык|ямдылык-влак}}:',
'templatesusedpreview' => 'Тиде лаштыкыште кучылтмо {{PLURAL:$1|ямыдылык|ямдылык-влак}}:',
'template-protected' => '(тӧрлаташ чарыме)',
'template-semiprotected' => '(верын аралыме)',
'hiddencategories' => 'Тиде лаштык $1 {{PLURAL:$1|шылтыме категорийыш|шылтыме категорийыш}} лектеш:',
'permissionserrorstext-withaction' => "Тыйын '''$2''' кертмашет шагал. Тиде {{PLURAL:$1|амал ден|амал дене}}:",
'recreate-moveddeleted-warn' => "'''Йолташ, тиде лаштыкым тиддеч ончыч шӧреныт.''' Тудым илаҥдарыме деч ончыч, тыгай лаштык кӱлешак мо - тергыман. Ӱлнырак шӧрымаш да лӱм вашталтымаш журнал-влакым шергал лекташ лиеш.",
'moveddeleted-notice' => 'Тиде лаштык шӧралтын.
Лаштыклан шӧрымӧ да кусарыме нерген журнал ӱлнӧ ончыктымо.',

# History pages
'viewpagelogs' => 'Тиде лаштыклан журнал-влакым ончыкташ',
'currentrev' => 'Кызытсе тӱрлык',
'currentrev-asof' => '$1 кечын кызытсе тӱрлык',
'revisionasof' => '$1 тӱрлык',
'revision-info' => '$1; $2 деч версий',
'previousrevision' => '← Ончычсо тӱрлык',
'nextrevision' => 'Весе →',
'currentrevisionlink' => 'Кызытсе',
'cur' => 'кызыт',
'next' => 'весе',
'last' => 'ончычсо',
'page_first' => 'икымше',
'page_last' => 'пытартыш',
'histlegend' => "Таҥастарашлаш ӱлыл версийыште ойырымаш полдышым да Enter-ым темдал.<br />
Умылтарымаш: (кызыт) = кызытсе версий деч ойыртем, (ончычсо) = ончычсо версий деч ойыртем, '''и''' = изи тӧрлатымаш.",
'history-fieldset-title' => 'Эртымгорным ончыкташ',
'histfirst' => 'эн тошто',
'histlast' => 'эн у',
'historyempty' => '(яра)',

# Revision feed
'history-feed-item-nocomment' => '$1 $2што',

# Revision deletion
'rev-delundel' => 'ончыкташ/шылташ',
'rev-showdeleted' => 'ончыкташ',
'revdelete-hide-image' => 'Файл кӧргым шылташ',
'revdelete-hide-user' => 'Тӧрлатышын лӱмжым шылташ',
'revdelete-radio-set' => 'Йӧ',
'revdelete-radio-unset' => 'Уке',
'revdel-restore' => 'Койымашым вашталташ',
'pagehist' => 'Лаштыкын эртымгорно',
'deletedhist' => 'Шӧрымо эртымгорно',
'revdelete-otherreason' => 'Вес/ешартыш амал:',
'revdelete-reasonotherlist' => 'Вес амал',

# History merging
'mergehistory-reason' => 'Амал:',

# Merge log
'revertmerge' => 'Ойыраш',

# Diffs
'history-title' => '$1лан тӱрлык эртымгорно',
'lineno' => '$1 корно:',
'compareselectedversions' => 'Ойырымо версий-влакым таҥастараш',
'editundo' => 'чараш',
'diff-multi' => '({{PLURAL:$1|не показана $1 промежуточная версия|не показаны $1 промежуточные версии|не показаны $1 промежуточных версий}} {{PLURAL:$2|$2 участника|$2 участников}})',

# Search results
'searchresults' => 'Кычалын мумо',
'searchresults-title' => '«$1»лан кычалын мумо',
'searchresulttext' => "{{SITENAME}}'ыште кычалмаш нерген шукырак палнеда гын, [[{{MediaWiki:Helppage}}|полышым]] ончыза.",
'searchsubtitle' => 'Тый кычалынат: \'\'\'[[:$1]]\'\'\' ([[Special:Prefixindex/$1|чыла лаштык-влакым, кудыжо тӱҥалыт: "$1"]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|чыла лаштык-влакым, "$1" дене кылым палемдат]])',
'searchsubtitleinvalid' => "Тый кычалыч '''$1'''",
'notitlematches' => 'Лаштык-влакын лӱмыштышт икгайлык уке',
'notextmatches' => 'Лаштык-влакыште икгайлык возымо уке',
'prevn' => 'кодшо {{PLURAL:$1|$1}}',
'nextn' => 'весе {{PLURAL:$1|$1}}',
'prevn-title' => 'Кодшо $1 {{PLURAL:$1|результат}}',
'nextn-title' => 'Весе $1 {{PLURAL:$1|результат}}',
'shown-title' => 'Лаштыкыште $1 {{PLURAL:$1|возымаш|возымашым}} ончыкташ',
'viewprevnext' => 'Ончал ($1 {{int:pipe-separator}} $2) ($3)',
'searchmenu-new' => "'''Тиде вики-проектыште «[[:$1]]» лӱман лаштыкым ышташ!'''",
'searchprofile-articles' => 'Возымо лаштык-влак',
'searchprofile-project' => 'Полыш да проект лаштык',
'searchprofile-images' => 'Мультимедий',
'searchprofile-everything' => 'Чыла',
'searchprofile-advanced' => 'Кумдарак',
'searchprofile-articles-tooltip' => 'Кычалмаш $1ште',
'searchprofile-project-tooltip' => 'Кычалмаш $1ште',
'searchprofile-images-tooltip' => 'Файл-влакым кычалмаш',
'searchprofile-everything-tooltip' => 'Чыла лаштык-влакыште кычалаш (каҥашымаш лаштык-влакыштат)',
'searchprofile-advanced-tooltip' => 'Искать в заданных пространствах имён',
'search-result-size' => '$1 ({{PLURAL:$2|$2 мут|$2 мут}})',
'search-result-category-size' => '$1 {{PLURAL:$1|вхождение|вхождения|вхождений}} ($2 {{PLURAL:$2|подкатегория|подкатегории|подкатегорий}}, $3 {{PLURAL:$3|файл|файла|файлов}}).',
'search-redirect' => '($1 вес вере колтымаш)',
'search-section' => '(ужаш $1)',
'search-suggest' => 'Але те $1 возынеда ыле',
'search-interwiki-caption' => 'Родо проект-влак',
'search-interwiki-default' => "$1'ште мумо:",
'search-interwiki-more' => '(эше)',
'searchrelated' => 'кылдалтше',
'searchall' => 'чыла',
'showingresultsheader' => "'''$4'''лан {{PLURAL:$5|'''$3''' гыч '''$1''' результат|'''$3''' гыч '''$1 - $2''' результат}}",
'nonefound' => "'''Ешартыш''':  Посна палемдыме огыл гын, кычалмаш южо лӱм-влак коклаште гына эрта. Чыла лаштык-влак коклаште кычалашлан (каҥашымаш, ямдылык-влак да т.м.) шке йодмашыштет ''all:'' префиксым кучылт, але кӱлешан лӱм-влакым палемде.",
'search-nonefound' => 'Тыйын йодышет почеш нимо муалтын огыл',
'powersearch' => 'Сайынрак кычал',
'powersearch-legend' => 'Сайынрак кычалаш',
'powersearch-ns' => 'Кычалаш тиде лӱм-влакын кумдыкышт-влакыште:',
'powersearch-redir' => 'Вес вере колтымо лаштык-влакым ончыкташ',
'powersearch-field' => 'Кычалаш',
'powersearch-togglelabel' => 'Сайлаш:',
'powersearch-toggleall' => 'Чыла',
'powersearch-togglenone' => 'Нимо',

# Preferences page
'preferences' => 'Келыштарымаш',
'mypreferences' => 'Келыштарымаш',
'prefs-edits' => 'Тӧрлатымаш чот:',
'changepassword' => 'Шолыпмутым вашталташ',
'prefs-skin' => 'Сӧрастарыме йӧн',
'skin-preview' => 'Ончылгоч ончымаш',
'prefs-datetime' => 'Кече да жап',
'prefs-personal' => 'Пайдаланышын профильже',
'prefs-rc' => 'Шукертсе огыл тӧрлымаш-влак',
'prefs-watchlist' => 'Эскерымаш лӱмер',
'prefs-watchlist-days' => 'Мыняр кече эскерымаш лӱмерыште ончыкталтеш?',
'prefs-watchlist-edits' => 'Мыняр тӧрлатымашым эскерымаш лӱмерыштет ончыктыман?',
'prefs-misc' => 'Тӱрлӧ',
'prefs-resetpass' => 'Шолыпмутым вашталташ',
'prefs-email' => 'Электрон почто келыштарымаш',
'prefs-rendering' => 'Тӱжвал сын',
'saveprefs' => 'Аралаш',
'resetprefs' => 'Тӧрлатымым шотыш налаш огыл',
'restoreprefs' => 'Тӱҥалтыш келыштарымашым пӧртылташ',
'prefs-editing' => 'Тöрлатымаш',
'searchresultshead' => 'Кычалме',
'savedprefs' => 'Келыштарымашетым аралыме.',
'timezonelegend' => 'Шагат ÿштö:',
'localtime' => 'Верысе жап:',
'timezoneregion-africa' => 'Африка',
'timezoneregion-america' => 'Америка',
'timezoneregion-antarctica' => 'Антарктика',
'timezoneregion-arctic' => 'Арктика',
'timezoneregion-asia' => 'Азий',
'timezoneregion-atlantic' => 'Атлантик таптеҥыз',
'timezoneregion-australia' => 'Австралий',
'timezoneregion-europe' => 'Европо',
'timezoneregion-indian' => 'Индий таптеҥыз',
'allowemail' => 'Вес ушнышо-влак деч электрон почтым налаш кӧнаш',
'prefs-searchoptions' => 'Кычалаш',
'prefs-namespaces' => 'Лӱм-влакын кумдыкышт-влак',
'default' => 'тӱҥалтыш',
'prefs-files' => 'Файл-влак',
'prefs-emailconfirm-label' => 'Электрон почто пеҥгыдемдыме:',
'youremail' => 'Электрон почто:',
'username' => '{{GENDER:$1|Пайдаланышын лӱмжӧ|Пайдаланышын лӱмжӧ}}:',
'uid' => '{{GENDER:$1|Пайдаланышын}} ID-же:',
'prefs-memberingroups' => '{{PLURAL:$1|Тӱшкаште шогышо|Тӱшка-влакыште шогышо}}:',
'yourrealname' => 'Чын лӱмжӧ:',
'yourlanguage' => 'Йылме:',
'yournick' => 'Кидпале:',
'gender-male' => 'Пӧръеҥ',
'gender-female' => 'Ӱдырамаш',
'email' => 'Электрон почто',
'prefs-help-email' => 'Электрон почтын адресым лучо возен кодыза. Трук шолыпмутым мондеда - шолпмутым Википедий электрон адресышкыда колта.',
'prefs-help-email-others' => 'Моло пайдаланыше-влак тендан дене электрон почто гоч кылым кучен кертыт. Ты годым почтыдан адресше нигӧлан ок кой, лач лаштыкыштыда але каҥашымаш лаштыкыштыда серышым возашлан кылвер пыжыктыме лиеш.',
'prefs-i18n' => 'Калык коклаште',
'prefs-signature' => 'Кидпале',

# Groups
'group-bot' => 'Бот-влак',
'group-sysop' => 'Сайтвиктарыше-влак',
'group-all' => '(чыла)',

'group-bot-member' => 'бот',

'grouppage-bot' => '{{ns:project}}:Бот-влак',
'grouppage-sysop' => '{{ns:project}}:Сайтвиктарыше-влак',

# Special:Log/newusers
'newuserlogpage' => 'У пайдаланыше регистрацийым эртарыме журнал',

# User rights log
'rightslog' => 'Пайдаланышын кертыж нерген журнал',

# Associated actions - in the sentence "You do not have permission to X"
'action-edit' => 'тиде лаштыкым тӧрлаташ',

# Recent changes
'nchanges' => '$1 {{PLURAL:$1|тӧрлатымаш}}',
'recentchanges' => 'Пытартыш тӧрлатымаш-влак',
'recentchanges-legend' => 'Пытартыш тӧрлатымаш-влакын келыштарымашышт',
'recentchanges-summary' => 'Тиде лаштыкыште пытартыш тӧрлатымашым шекланаш.',
'recentchanges-feed-description' => 'Тиде кылыште пытартыш тӧрлатымашым шекланаш.',
'recentchanges-label-newpage' => 'Тиде тӧрлатымаш дене у лаштык ышталтын',
'recentchanges-label-minor' => 'Тиде изи тӧрлатымаш',
'recentchanges-label-bot' => 'Тиде тӧрлатымашым бот ыштен',
'recentchanges-label-unpatrolled' => 'Тиде тӧрлатымашым нигӧ терген огыл',
'rcnote' => "Ӱлнӧ {{PLURAL:$1|'''1'''|'''$1'''}} вашталтыш пытартыш {{PLURAL:$2||'''$2'''}} кечылан, $5-лан, $4-лан.",
'rcnotefrom' => "Ниже перечислены изменения с '''$2''' (не более '''$1''').",
'rclistfrom' => '$1 гыч тӱҥалын у вашталтымашым ончыкташ',
'rcshowhideminor' => 'Изи тӧрлатымашым $1',
'rcshowhidebots' => 'Бот-влакым $1',
'rcshowhideliu' => 'Шолып пайдаланыше-влакым $1',
'rcshowhideanons' => 'Ончыкталтше пайдаланыше-влакым $1',
'rcshowhidepatr' => '$1 тергыме тӧрлатымаш',
'rcshowhidemine' => 'Мыйын тӧрлымым $1',
'rclinks' => 'Пытартыш $2 кечылан $1 вашталтымашым ончыкташ<br />$3',
'diff' => 'ойырт.',
'hist' => 'эрт.',
'hide' => 'шылташ',
'show' => 'ончыкташ',
'minoreditletter' => 'и',
'newpageletter' => 'У',
'boteditletter' => 'б',
'rc-enhanced-expand' => 'Тичмашын ончыкташ',
'rc-enhanced-hide' => 'Рашлык-влакым шылташ',

# Recent changes linked
'recentchangeslinked' => 'Ваш кылдалтше тӧрлатымаш-влак',
'recentchangeslinked-feed' => 'Ваш кылдалтше тӧрлатымаш-влак',
'recentchangeslinked-toolbox' => 'Ваш кылдалтше тӧрлатымаш-влак',
'recentchangeslinked-title' => '"$1" лаштык дене кылдалтше тӧрлатымаш-влак',
'recentchangeslinked-summary' => "Это список недавних изменений в страницах, на которые ссылается указанная страница (или входящих в указанную категорию).
Страницы, входящие в [[Special:Watchlist|ваш список наблюдения]] '''выделены'''.",
'recentchangeslinked-page' => 'Лаштыкын лӱмжӧ:',
'recentchangeslinked-to' => 'Тиде лаштык дене кылдалтше лаштык-влакыште тӧрлатымашым ончыкташ тидын олмеш',

# Upload
'upload' => 'Файлым пурташ',
'uploadbtn' => 'Файлым пурташ',
'uploadlogpage' => 'Оптымаш-влак журнал',
'filedesc' => 'Файл нерген кӱчыкын увертараш',
'fileuploadsummary' => 'Тидын нерген кӱчыкын:',
'uploadedimage' => '«[[$1]]» пуртыш',
'watchthisupload' => 'Тиде файлым эскераш',

'license-header' => 'Лицензирований',

# Special:ListFiles
'imgfile' => 'файл',
'listfiles_user' => 'Пайдаланыше',

# File description page
'file-anchor-link' => 'Файл',
'filehist' => 'Файлын эртымгорно',
'filehist-help' => 'Файл ончыч могай ыле - ончалнет гын, кече/жапым темдал.',
'filehist-deleteone' => 'шӧраш',
'filehist-revert' => 'пӧртылташ',
'filehist-current' => 'кызыт',
'filehist-datetime' => 'Кече/жап',
'filehist-thumb' => 'Иземдыме сӱрет',
'filehist-thumbtext' => '$1лан изирак сӱрет',
'filehist-user' => 'Пайдаланыше',
'filehist-dimensions' => 'Кугытшо',
'filehist-filesize' => 'Файлын кугытшо',
'filehist-comment' => 'Файл нерген:',
'imagelinks' => 'Файлым кучылтмаш',
'linkstoimage' => 'Тиде {{PLURAL:$1|$1 лаштык саде файл дене кылдалтын|$1 лаштык-влак саде файл дене кылдалтыныт}}:',
'nolinkstoimage' => 'Тиде файл дене кылдалтше ик лаштыкат уке.',
'sharedupload' => 'Тиде файлын верже: $1, туге гынат, тудым моло веренат кучылташ лиеш.',
'uploadnewversion-linktext' => 'Тиде файлын у тӱрлыкшым пурташ',

# File deletion
'filedelete-comment' => 'Амал:',
'filedelete-submit' => 'Шӧраш',
'filedelete-otherreason' => 'Вес/ешартыш амал:',
'filedelete-reason-otherlist' => 'Вес амал',

# List redirects
'listredirects' => 'Вес вере колтымаш-влак',

# Random page
'randompage' => 'Чокым лаштык',

# Statistics
'statistics' => 'Иктешлымаш',
'statistics-header-pages' => 'Лаштык коклам иктешлымаш',
'statistics-header-edits' => 'Тӧрлатымаш коклам иктешлымаш',
'statistics-header-views' => 'Ончымаш коклам иктешлымаш',
'statistics-header-users' => 'Пайдаланыше коклам иктешлымаш',
'statistics-header-hooks' => 'Тӱрлӧ коклам иктешлымаш',
'statistics-articles' => 'Возымо лаштык-влак',
'statistics-pages' => 'Лаштык-влак',
'statistics-pages-desc' => 'Чыла лаштык-влак (каҥашымаш-влак, вес вере колтымаш-влак да тулеч моло)',
'statistics-files' => 'Пуртымо файл-влак',
'statistics-edits' => '{{SITENAME}} лаштыкым чылажге мыняр гана тӧрлатыме',
'statistics-edits-average' => 'Ик лаштыкым покшел тӧрлымӧ чот',
'statistics-views-total' => 'Чылажге ончымо',
'statistics-views-peredit' => 'Ик тӧрлатымашлан ончымо',
'statistics-users' => 'Регистрацийым эртыше [[Special:ListUsers|пайдаланыше-влак]]',
'statistics-users-active' => 'Чӱчкыдын пайдаланыше-влак',
'statistics-users-active-desc' => 'Пытартыш {{PLURAL:$1|кечыште|$1 кечыште}} иктаж-мом ыштыше пайдаланыше-влак',
'statistics-mostpopular' => 'Эн чӱчкыдын ончымо лаштык-влак',

'brokenredirects' => 'Пудыртымо вес вере колтымаш-влак',
'brokenredirects-edit' => 'тӧрлаташ',
'brokenredirects-delete' => 'шӧраш',

'withoutinterwiki-submit' => 'ончыкташ',

# Miscellaneous special pages
'nbytes' => '$1 {{PLURAL:$1|байт|байт}}',
'nmembers' => '$1 {{PLURAL:$1|лаштык|лаштык-влак}}',
'nviews' => '$1 {{PLURAL:$1|ончымо|ончымо-влак}}',
'lonelypages' => 'Тулык лаштык-влак',
'wantedcategories' => 'Ыштыман категорий-влак',
'wantedpages' => 'Ыштышаш лаштык-влак',
'wantedfiles' => 'Ыштыман файл-влак',
'wantedtemplates' => 'Ыштыман ямдылык-влак',
'prefixindex' => 'Чыла лаштык-влак префикс дене',
'shortpages' => 'Кӱчык лаштык-влак',
'longpages' => 'Кужу лаштык-влак',
'protectedpages' => 'Тӧрлатымаш деч аралыме лаштык-влак',
'usercreated' => '$1, $2 шагатлан {{GENDER:$3|регистрацийым эртен|регистрацийым эртен}}',
'newpages' => 'У лаштык-влак',
'newpages-username' => 'Пайдаланышын лӱмжӧ:',
'move' => 'Кусараш',
'movethispage' => 'Тиде лаштыкым кусараш',
'pager-newer-n' => '{{PLURAL:$1|вес|вес}}',
'pager-older-n' => '{{PLURAL:$1|ончычсо|ончычсо}}',

# Book sources
'booksources' => 'Негызым пыштыше кнага-влак',
'booksources-search-legend' => 'Негызым пыштыше книгам кычалаш',
'booksources-go' => 'Муаш',

# Special:Log
'specialloguserlabel' => 'Пайдаланыше:',
'log' => 'Журнал-влак',

# Special:AllPages
'allpages' => 'Чыла лаштык-влак',
'alphaindexline' => '$1 $2 марте',
'prevpage' => 'Ончычсо лаштык ($1)',
'allpagesfrom' => 'Лукташ тыгай лӱман лаштык-влакым, кудыжо тӱҥалыт:',
'allpagesto' => 'кудыжо пытат:',
'allarticles' => 'Чыла лаштык-влак',
'allpagessubmit' => 'Кычалаш',

# Special:Categories
'categories' => 'Категорий-влак',

# Special:LinkSearch
'linksearch' => 'Ӧрдыж кылвер-влак',
'linksearch-ns' => 'Лӱм-влакын кумдыкышт:',
'linksearch-ok' => 'Кычал',
'linksearch-line' => '$2 лаштыкыште $1 ончыкталтын',

# Special:ListUsers
'listusers-submit' => 'ончыкташ',
'listusers-blocked' => '(йӧн петырыме)',

# Special:ActiveUsers
'activeusers' => 'Чӱчкыдын пайдаланыше-влак',
'activeusers-count' => 'Пытартыш $3 {{PLURAL:$3|кечыште|кечылаште}} $1 {{PLURAL:$1|тӧрлатымаш|тӧрлатымаш-влак}}',
'activeusers-hidebots' => 'Бот-влакым шылташ',
'activeusers-hidesysops' => 'Сайтвиктарыше-влакым шылташ',

# Special:ListGroupRights
'listgrouprights-members' => '(тӱшкаште улшо-влак)',

# Email user
'emailuser' => 'Пайдаланыше дек серыш',

# Watchlist
'watchlist' => 'Эскерымаш лӱмер',
'mywatchlist' => 'Эскерымаш лӱмер',
'watchlistfor2' => '$1 лан ($2)',
'addedwatchtext' => "\"[[:\$1]]\" лаштыкым тыйын [[Special:Watchlist|эскерымаш лӱмерыш]] ешарыме.
Тиде лаштыкын да тудын каҥашымаш лаштыкым умбакысе тӧрлатымашым тиде спискыште ончыктымо лиеш да, сайрак ужаш манын, [[Special:RecentChanges|пытартыш тӧрлатымаш лӱмерыште]] '''кӱжгӧ шрифт''' дене ойырымо.",
'removedwatchtext' => '«[[:$1]]» лаштыкым [[Special:Watchlist|тыйын эскерыме лӱмер]] гыч кораҥдыме.',
'watch' => 'Эскераш',
'watchthispage' => 'Тиде лаштыкым эскераш',
'unwatch' => 'Эскерыман огыл',
'unwatchthispage' => 'Эскерымым чарнаш',
'watchlist-details' => 'Эскерымаш лӱмерыштет $1 {{PLURAL:$1|лаштык}}, каҥашымаш лаштык-влакым шотлыде',
'watchlistcontains' => 'Тыйын лӱмерыште $1 {{PLURAL:$1|лаштык|лаштык}}.',
'wlshowlast' => 'Пытартыш $1 шагат $2 кечылан $3 ончыкташ',
'watchlist-options' => 'Эскерыме лӱмерын келыштарымаш',

# Displayed when you click the "watch" button and it is in the process of watching
'watching' => 'Эскерымаш лӱмерыш ешарымаш...',
'unwatching' => 'Эскерымаш лӱмер гыч шӧрымаш...',

# Delete
'deletepage' => 'Лаштыкым шӧраш',
'delete-confirm' => 'Шӧраш "$1"',
'delete-legend' => 'Шӧраш',
'historywarning' => 'Тӱтко лий: шӧраш шонымо лаштыкет вашталтыш-влак нерген эртымгорным нумалеш:',
'confirmdeletetext' => 'Тый тиде лаштыкым пӱтынь эртымгорныже дене йӧршын шӧрынет.
Пеҥгыдемде тидым [[{{MediaWiki:Policy-url}}|правил]] почеш ыштыметым да, мо тидын деч вара лиймым, умылыметым.',
'actioncomplete' => 'Ыштыме',
'deletedtext' => '«$1» шӧрымӧ.
Шӧрымаш лӱмер гыч $2лан ончо.',
'dellogpage' => 'Шӧрымӧ нерген журнал',
'deletionlog' => 'шӧрымӧ нерген журнал',
'deletecomment' => 'Амал:',
'deleteotherreason' => 'Вес/ешартыш амал:',
'deletereasonotherlist' => 'Вес амал',

# Rollback
'rollbacklink' => 'пӧртылаш',

# Protect
'protectlogpage' => 'Тӧрлатымаш деч аралыме нерген журнал',
'protectedarticle' => '«[[$1]]» тӧрлатымаш деч аралыме лийын',
'modifiedarticleprotection' => '"[[$1]]" лаштыкын  шыгыремдымашын чотшым вашталтыме.',
'protectcomment' => 'Амал:',
'protectexpiry' => 'Мучашлалтеш:',
'protect_expiry_invalid' => 'Йоҥылыш мучашлалтше жап.',
'protect_expiry_old' => 'Мучашлалтше жап эртен.',
'protect-text' => "Тыште тый '''$1''' лаштыкын шыгыремдымашыжым ончалаш да тӧрлаташ кертат.",
'protect-locked-access' => "Тыйын лаштыкын шыгыремдымашыжым тӧрлаш кертмешет шагал.
Ӱлнӧ '''$1''' лаштыкын кызытсе келыштарымаш.",
'protect-cascadeon' => 'Тиде лаштыкым тӧрлатымаш деч аралыме.  
Каскадный аралымашан {{PLURAL:$1|лаштык-влак}} тудо пура.',
'protect-default' => 'Чыла пайдаланыше-влаклан йӧным пуаш',
'protect-fallback' => '«$1» кертеж кӱлеш',
'protect-level-autoconfirmed' => 'Регистрацийым эртыдыме да у пайдаланыше-влак деч петыраш',
'protect-level-sysop' => 'Сайтвиктарыше-влак гына',
'protect-summary-cascade' => 'кылдалтше',
'protect-expiring' => '$1 мучашлалтеш (UTC)',
'protect-cascade' => 'Тиде лаштыкыш пурышо лаштыш-влакым аралаш (кылдалтше аралтыш)',
'protect-cantedit' => 'Тый тиде лаштыкын шыгыремдымашыжым тӧрлатен от керт, тидлан тылат кертеж пуалтын огыл.',
'protect-otherreason' => 'Вес/ешартыш амал:',
'protect-otherreason-op' => 'вес/ешартыш амал',
'restriction-type' => 'Кертеж:',
'restriction-level' => 'Тыгай шыгыремдаш:',

# Undelete
'undeletelink' => 'ончалаш/тӧрлатен шындаш',
'undeleteviewlink' => 'ончыкташ',
'undelete-search-submit' => 'Кычал',

# Namespace form on various pages
'namespace' => 'Лӱм-влакын кумдыкышт:',
'invert' => 'инвертировать выделенное',
'blanknamespace' => '(Тӱҥ)',

# Contributions
'contributions' => 'Пайдаланышын пашаже',
'contributions-title' => '$1 пайдаланышын паша',
'mycontris' => 'Мыйын паша',
'contribsub2' => '$1 лан ($2)',
'uctop' => '(пытартыш)',
'month' => 'Могай тылзе гыч тӱҥалаш?',
'year' => 'Могай ий гыч тӱҥалаш?',

'sp-contributions-newbies' => 'У пайдалнышын гына пашам ончыкташ',
'sp-contributions-blocklog' => 'блокирований журнал',
'sp-contributions-uploads' => 'пуртымаш-влак',
'sp-contributions-logs' => 'Журнал-влак',
'sp-contributions-talk' => 'каҥашымаш',
'sp-contributions-search' => 'Пашам кычалаш',
'sp-contributions-username' => 'IP-адрес ала пайдаланышын лӱмжӧ:',
'sp-contributions-toponly' => 'Показывать только правки, являющиеся последними версиями',
'sp-contributions-submit' => 'Кычалаш',

# What links here
'whatlinkshere' => 'Тышке кондышо кылвер-влак',
'whatlinkshere-title' => '"$1" дене лаштык-влак кылым палемдат',
'whatlinkshere-page' => 'Лаштык:',
'linkshere' => "'''[[:$1]]''' лаштык дене кылдалтше лаштык-влак:",
'nolinkshere' => "'''[[:$1]]''' лаштык дене тетла нимогай лаштык кылдалтын огыл",
'nolinkshere-ns' => "Тыгай лӱм-влакын кумдыкышто '''[[:$1]]''' лаштык дене нимогай вес лаштык-влак кылым огыт кучо.",
'isredirect' => 'вес вере колтышо лаштык',
'istemplate' => 'пуртымаш',
'isimage' => '!!FUZZY! файллан кылвер',
'whatlinkshere-prev' => '{{PLURAL:$1|ончычсо|$1 ончычсо}}',
'whatlinkshere-next' => '{{PLURAL:$1|вес|$1 вес}}',
'whatlinkshere-links' => '← кылвер-влак',
'whatlinkshere-hideredirs' => 'вес вере колтымаш-влакым $1',
'whatlinkshere-hidetrans' => 'пуртымашым $1',
'whatlinkshere-hidelinks' => 'кылвер-влакым $1',
'whatlinkshere-hideimages' => 'сӱрет деке кылвер-влакым $1',
'whatlinkshere-filters' => 'Фильтр-влак',

# Block/unblock
'blockip' => 'Пайдаланышылан йӧным петыраш',
'ipbreason' => 'Амал:',
'ipbreasonotherlist' => 'Вес амал',
'ipboptions' => '2 жап:2 hours,1 кече:1 day,3 кече:3 days,1 арня:1 week,2 арня:2 weeks,1 тылзе:1 month,3 тылзе:3 months,6 тылзе:6 months,1 ий:1 year,нимучашдымылык:infinite',
'ipbotherreason' => 'Вес/ешартыш амал:',
'ipblocklist' => 'Блокироватлыме пайдаланыше-влак',
'ipblocklist-submit' => 'Кычал',
'blocklink' => 'йӧным петыраш',
'unblocklink' => 'йӧным почаш',
'change-blocklink' => 'йӧным вашталташ',
'contribslink' => 'паша',
'blocklogpage' => 'Блокирований журнал',
'blocklogentry' => '[[$1]] лан йӧным петрен $2 $3 мучашлалтеш',
'unblocklogentry' => '$1лан йӧным почмо',
'block-log-flags-nocreate' => 'у пайдаланыше-влаклан регистрацийым чактарыме',

# Move page
'move-page-legend' => 'Лаштыкым кусараш',
'movepagetext' => "Ӱлыл формо дене пайдаланен, тый лаштыкын лӱмым вашталтен кертат, тудын вашталтыме эртымгорныже у верыш кусарыме.
Тошто лӱмыштӧ у лӱмыш колтымо лаштык кодеш.
Тый тошто лӱмыш колтымо лаштык-влакым шке семын вашталтке кертат.
Тый тидым ынет ыште гын, [[Special:DoubleRedirects|кокытан]] да [[Special:BrokenRedirects|пудыргышо вес вере колтымашым]] терге.
Тый палемдыме верыш кылвер-влаклан шуйнымылан да тушко ончыктымылан вуйын шогет.

Шотыш нал: кунам у лӱман лаштык уло, тудо '''ок''' кусаралт. Тыге огыл, кунам лаштык вес вере кусаралтеш але тудо яра да вашталтымаш эртымгорныже уке.
Тый лаштыкым йонгылыш кусаренат гын менгешла тудым тошто лӱмыш кусарен кертат, но тый уже улшо лаштыкым ӱштын от керт, манын ончыкта.

'''Тӱтко лий!'''
Чӱчкыдын кучылтмо лаштыклан тиде кугу вашталтышым ыштен кертеш;
Умбаке кайыме деч ончыч шоналте, тый тидын деч вара лиймым умылет.",
'movepagetalktext' => "Тиде лаштыкын каҥашымаш лаштык шке семын огеш кусно, '''тидлан амалже:'''
*Тыгай лӱман яра огыл каҥашымаш лаштык уло ала
*Ӱлыч кайыкым от корангде.

Тыгай годым тылат лаштыкым шке кидет дене кусараш але иктеш ушнаш кӱлеш.",
'movearticle' => 'Тиде лаштыкым кусараш:',
'newtitle' => 'У лӱм:',
'move-watch' => 'Тиде лаштыкым эскераш',
'movepagebtn' => 'Лаштыкым кусараш',
'pagemovedsub' => 'Кусарымаш сайын эртен',
'movepage-moved' => '\'\'\'"$1" лаштыкым "$2" лаштыкыш кусарыме\'\'\'',
'movepage-moved-redirect' => 'Вес вере колтымаш ыштыме.',
'movepage-moved-noredirect' => 'Вес вере колтымаш ыштыме огыл.',
'articleexists' => 'Тыгай лӱман лаштык уло але тиде лӱмым кучылташ огеш лий. Вес лӱмым ойыро.',
'talkexists' => "'''Лаштыкым кусарыме гынат, тудын каҥашымаш лаштыкшым тыгай лӱман лаштык улмылан кӧра кусараш огеш лий. Нуным шке кидет дене иктыш ушно.'''",
'movedto' => 'лаштыкыш кусарыме',
'movetalk' => 'Каҥашымаш лаштыкым кусараш',
'movelogpage' => 'Кусарыме нерген журнал',
'movereason' => 'Амал:',
'revertmove' => 'мӧҥгешла пӧртылаш',

# Export
'export' => 'Лаштык-влакым келыштараш',

# Namespace 8 related
'allmessagesname' => 'Лӱм',
'allmessagesdefault' => 'Текст по умолчанию',
'allmessages-filter-all' => 'Чыла',

# Thumbnails
'thumbnail-more' => 'Кугемдаш',
'thumbnail_error' => 'Изи сӱретым ыштыме годым йоҥылыш: $1',

# Tooltip help for the actions
'tooltip-pt-userpage' => 'Тыйын лаштыкет',
'tooltip-pt-mytalk' => 'Тыйын каҥашымаш лаштыкет',
'tooltip-pt-preferences' => 'Мыйын келыштарымашем',
'tooltip-pt-watchlist' => 'Мыйын эскерыме лаштык-влак лӱмер',
'tooltip-pt-mycontris' => 'Тыйын пашатым эскерыме лаштык',
'tooltip-pt-login' => 'Шке денет палымым ыштет гын сайрак лиеш; такшым тидым ыштыдеат кертат.',
'tooltip-pt-logout' => 'Системе гыч лекташ',
'tooltip-ca-talk' => 'Лаштыкыште возымым каҥашаш',
'tooltip-ca-edit' => 'Тый тиде лаштыкым тӧрлатен кертат. Лаштыкым аралыме деч ончыч тудым тергаш ит мондо.',
'tooltip-ca-addsection' => 'У ужашым тӱҥалаш',
'tooltip-ca-viewsource' => 'Тиде лаштыкым аралыме.
Тый тудын тӱҥалтыш текстшым ончалын кертат.',
'tooltip-ca-history' => 'Лаштыкым ондаксе тӧрлатымаш',
'tooltip-ca-protect' => 'Тиде лаштыкым тӧрлатымаш деч аралаш',
'tooltip-ca-delete' => 'Тиде лаштыкым шӧраш',
'tooltip-ca-move' => 'Тиде лаштыкым кусараш',
'tooltip-ca-watch' => 'Тиде лаштыкым тыйын эскерыме лӱмерыш ешараш',
'tooltip-ca-unwatch' => 'Тиде лаштыкым тыйын эскерымашет гыч кораҥдаш',
'tooltip-search' => '{{SITENAME}} лаштыкыште кычалаш',
'tooltip-search-go' => 'Тиде лӱман лаштыкыш куснаш, тыгайже уло гын',
'tooltip-search-fulltext' => 'Тыгай мут дене лаштыкым кычалаш',
'tooltip-p-logo' => 'Тӱҥ лаштык',
'tooltip-n-mainpage' => 'Тӱҥ лаштыкыш куснаш',
'tooltip-n-mainpage-description' => 'Тӱҥ лаштыкыш куснаш',
'tooltip-n-portal' => 'Проект нерген, мом тый ыштен кертат, мо кушто уло',
'tooltip-n-currentevents' => 'Мо лийме нерген нерген пытартыш увер',
'tooltip-n-recentchanges' => 'Пытартыш вашталтымаш лӱмер',
'tooltip-n-randompage' => 'Лаштыкым чокым ойыраш',
'tooltip-n-help' => 'Википедийым кучылтмо да тӧрлатыме шотышто полшык.',
'tooltip-t-whatlinkshere' => 'Тышке кондышо лаштык-влакын лӱмерышт',
'tooltip-t-recentchangeslinked' => 'Тиде лаштык дене кылдалтше пытартыш тӧрлатымаш-влак',
'tooltip-feed-rss' => 'Тиде лаштыклан RSS-кыл',
'tooltip-feed-atom' => 'Тиде лаштыклан Atom-кыл',
'tooltip-t-contributions' => 'Пайдаланышын ыштыме пашажым ончалаш',
'tooltip-t-emailuser' => 'Тиде пайдаланышылан электрон серышым возаш',
'tooltip-t-upload' => 'Файл-влакым пурташ',
'tooltip-t-specialpages' => 'Лӱмын ыштыме лаштык-влак',
'tooltip-t-print' => 'Савыкташлан келыштараш',
'tooltip-t-permalink' => 'Тиде лашткыш кондышо кылвер (ссылка)',
'tooltip-ca-nstab-main' => 'Лаштыкыште возымым ончыкташ',
'tooltip-ca-nstab-user' => 'Пайдаланышын лаштыкшым ончалаш',
'tooltip-ca-nstab-special' => 'Тиде лӱмын ыштыме лаштык, тудым тый тӧрлатен от керт',
'tooltip-ca-nstab-project' => 'Проект нерген лаштыкым ончыкташ',
'tooltip-ca-nstab-image' => 'Файлын лаштыкшым ончалаш',
'tooltip-ca-nstab-template' => 'Ямдылыкым ончыкташ',
'tooltip-ca-nstab-category' => 'Категорийын лаштыкым ончыкташ',
'tooltip-minoredit' => 'Тиде тӧрлатымашым „изи” семын палемдаш',
'tooltip-save' => 'Тыйын тӧрлатымашетым аралаш',
'tooltip-preview' => 'Лаштыкым аралыме деч ончыч ончылгоч ончал!',
'tooltip-diff' => 'Ончыкташ, могай тӧрлатымашым тый ыштенат.',
'tooltip-compareselectedversions' => 'Кок ойырымо лаштык версийын ойыртемым ончалаш.',
'tooltip-watch' => 'Тиде лаштыкым эскерымаш лаштыкышкет ешараш',
'tooltip-rollback' => '"Пӧртылаш" ик темдалтыш дене пытартыш пайдаланышын тӧрлатымашым мӧҥгешла пӧртылеш',
'tooltip-undo' => '"Чараш" тиде тӧрлатымашым мӧҥгешла пӧртыла да ончылгоч ончымашым почеш.
Тый тӧрлатымаш амалже нерген возымо верыште  возын кертат.',

# Browsing diffs
'previousdiff' => '← Ончычсо тӧрлатымаш-влак',
'nextdiff' => 'Вес тӧрлатымаш →',

# Media information
'widthheightpage' => '$1 × $2, $3 {{PLURAL:$3|лаштык|лаштык}}',
'file-info-size' => '$1 × $2 пиксел, файлын кугытшо: $3, MIME-тип: $4',
'file-nohires' => 'Кугурак чаплык уке.',
'svg-long-desc' => 'SVG файл, шкенжын кугытшо: $1 × $2 пиксел, файлын кугытшо: $3',
'show-big-image' => 'Шкенжын чаплыкше',

# Special:NewFiles
'newimages-legend' => 'Фильтр',
'showhidebots' => '(Бот-влакым $1 )',
'ilsubmit' => 'Кычал',

# Bad image list
'bad_image_list' => 'Формат тыгай лийшаш:

Лӱмерын ужашыже-влак гына шотыш налалташ тӱналалтыт (* дене туҥалше корно-влак).
Корнышто икымше кылвер шӱкшӧ файлыш кылвер семын лийшаш.
Тиде корнышто вара лийше кылвер-влак лийын кертдыме семын ончалтыт: файлыш кылверан лаштык-влак.',

# Metadata
'metadata' => 'Метаданный-влак',
'metadata-help' => 'Тиде файлыште фотоаппаратын але сканерын данныже-влак улыт.
Ышталтме деч вара файлым тӧрлатеныт гын, южо данныйже тиде файллан келшыдыме лийын кертеш.',
'metadata-expand' => 'Ешартыш рашлык-влакым ончыкташ',
'metadata-collapse' => 'Ешартыш рашлык-влакым шылташ',
'metadata-fields' => 'Поля метаданных изображения, перечисленные в этом списке, будут показаны на странице изображения при свёрнутой таблице метаданных. Остальные поля будут по умолчанию скрыты.
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
'edit-externally' => 'Файлым ӧрдыж программыште тӧрлаташ',
'edit-externally-help' => '(Сайрак палашлан ончал [//www.mediawiki.org/wiki/Manual:External_editors шындымаш нерген туныктымашым])',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'чыла',
'namespacesall' => 'чыла',
'monthsall' => 'чыла',

# action=purge
'confirm_purge_button' => 'Йӧра',

# Multipage image navigation
'imgmultipageprev' => '← ончычсо лаштык',
'imgmultipagenext' => 'вес лаштык →',

# Table pager
'table_pager_next' => 'Вес лаштык',
'table_pager_prev' => 'Ончычсо лаштык',
'table_pager_limit_submit' => 'Кычалаш',

# Auto-summaries
'autoredircomment' => '[[$1]] лаштыкыш колтымаш',
'autosumm-new' => "У лаштык '$1' дене тӱҥалеш",

# Watchlist editing tools
'watchlisttools-view' => 'Келшыше тӧрлатымаш-влакым ончалаш',
'watchlisttools-edit' => 'Эскерыме лӱмерым ончалаш да тӧрлаташ',
'watchlisttools-raw' => 'Эскерыме лӱмерым текст семын тӧрлаш',

# Core parser functions
'duplicate-defaultsort' => 'Внимание. Ключ сортировки по умолчанию «$2» переопределяет прежний ключ сортировки по умолчанию «$1».',

# Special:Version
'version-specialpages' => 'Лӱмын ыштыме лаштык-влак',

# Special:FileDuplicateSearch
'fileduplicatesearch-submit' => 'Кычалаш',

# Special:SpecialPages
'specialpages' => 'Лӱмын ыштыме лаштык-влак',
'specialpages-group-other' => 'Моло лӱмын ыштыме лаштык-влак',
'specialpages-group-login' => 'Пурымаш / регистрацийым эрташ',
'specialpages-group-users' => 'Пайдаланыше-влак да нунын йӧн-влак',
'specialpages-group-highuse' => 'Чӱчкыдын кучылтмо лаштык-влак',
'specialpages-group-pages' => 'Лаштык лӱмер-влак',
'specialpages-group-pagetools' => 'Лаштык ӱзгар-влак',
'specialpages-group-redirects' => 'Вес вере колтышо спецлаштык-влак',

# External image whitelist
'external_image_whitelist' => ' #Оставьте эту строчку такой, как она есть<pre>
#Разместите здесь фрагменты регулярных выражений (ту часть, что находится между //)
#они будут соотнесены с URL внешних изображений.
#Подходящие будут показаны как изображения, остальные будут показаны как ссылки на изображения.
#Строки, начинающиеся с # считаются комментариями.
#Строки не чувствительны к регистру

#Размещайте фрагменты регулярных выражений над этой строчкой. Оставьте эту строчку такой, как она есть.</pre>',

);
