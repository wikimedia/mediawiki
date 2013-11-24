<?php
/** Komi (коми)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Comp1089
 * @author Yufereff
 * @author ОйЛ
 */

$fallback = 'ru';

$namespaceNames = array(
	NS_SPECIAL          => 'Отсасян',
	NS_TALK             => 'Сёрнитанiн',
	NS_USER             => 'Пырысь',
	NS_USER_TALK        => 'Пырыськӧд_сёрнитанiн',
	NS_FILE             => 'Файл',
	NS_FILE_TALK        => 'Файл_донъялӧм',
	NS_MEDIAWIKI        => 'МедиаВики',
	NS_MEDIAWIKI_TALK   => 'МедиаВики_донъялӧм',
	NS_TEMPLATE         => 'Шаблон',
	NS_TEMPLATE_TALK    => 'Шаблон_донъялӧм',
);

$namespaceAliases = array(
	// Backward compat. Fallbacks from 'ru'.
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
	'Обсуждение_категории'               => NS_CATEGORY_TALK
);

$messages = array(
# Dates
'sunday' => 'вежалун',
'monday' => 'выльлун',
'tuesday' => 'воторник',
'wednesday' => 'середа',
'thursday' => 'четверг',
'friday' => 'пекнича',
'saturday' => 'субöта',
'january' => 'тӧв шӧр тӧлысь',
'february' => 'урасьӧм тӧлысь',
'march' => 'рака тӧлысь',
'april' => 'кос му тӧлысь',
'may_long' => 'ода кора тӧлысь',
'june' => 'лӧддза-номъя тӧлысь',
'july' => 'сора тӧлысь',
'august' => 'моз тӧлысь',
'september' => 'кӧч тӧлысь',
'october' => 'йирым тӧлысь',
'november' => 'вӧльгым тӧлысь',
'december' => 'ӧшым тӧлысь',
'january-gen' => 'тӧв шӧр',
'february-gen' => 'урасьӧм',
'march-gen' => 'рака',
'april-gen' => 'кос му',
'may-gen' => 'ода кора',
'june-gen' => 'лӧддза-номъя',
'july-gen' => 'сора',
'august-gen' => 'моз',
'september-gen' => 'кӧч',
'october-gen' => 'йирым',
'november-gen' => 'вӧльгым',
'december-gen' => 'ӧшым',

# Categories related messages
'pagecategories' => '{{PLURAL:$1|Категория|Категория}}',

'article' => 'Гижӧд',
'cancel' => 'Дугӧдны',
'mytalk' => 'Сёрнитан лист бокӧй',

# Cologne Blue skin
'qbfind' => 'Корсьысьӧм',
'qbedit' => 'Веськӧдны',

# Vector skin
'vector-action-move' => 'Ним вежны',
'vector-view-edit' => 'Вежны',
'vector-view-view' => 'Лыддьыны',
'namespaces' => 'Ним пространствояс',

'search' => 'Корсьысьӧм',
'searchbutton' => 'Аддзыны',
'searcharticle' => 'Вуджны',
'history_short' => 'Важвылӧм',
'printableversion' => 'Лэдзӧм версия',
'permalink' => 'Вежласьтӧм ыстӧд',
'edit' => 'Вежны',
'delete' => 'Бырӧдны',
'protect' => 'Дорйыны',
'newpage' => 'Выль лист бок',
'talkpagelinktext' => 'сёрнитанін',
'talk' => 'Сёрнитанін',
'toolbox' => 'Инструментъяс',
'otherlanguages' => 'Мӧд кывъясӧн',
'jumptosearch' => 'корсьысьӧм',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite' => '{{SITENAME}} йылысь',
'currentevents' => 'Быд лунся лоӧмтор',
'mainpage' => 'Медшӧр лист бок',
'mainpage-description' => 'Медшӧр лист бок',
'portal' => 'Йитчӧм',
'portal-url' => 'Project:Йитчӧм портал',

'newmessageslink' => 'выль юӧртӧмъяс',
'editsection' => 'веськӧдны',
'editold' => 'веськӧдны',
'editlink' => 'вежны',
'editsectionhint' => '«$1» секция веськӧдны',
'red-link-title' => '$1 (гижӧд абу)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main' => 'Гижӧд',
'nstab-user' => 'Пырысь',
'nstab-project' => 'Проект йылысь',
'nstab-category' => 'Категория',

# Login and logout pages
'yourname' => 'Пырысьлӧн ним:',
'login' => 'Висьтасьны',
'nav-login-createaccount' => 'Висьтасьны / гижсьыны',
'userlogin' => 'Висьтасьны али гижсьыны',
'logout' => 'Сеанс эштӧдӧм',
'userlogout' => 'Сеанс эштӧдӧм',
'gotaccountlink' => 'Висьтасьӧй',
'loginlanguagelabel' => 'Кыв: $1',

# Edit pages
'savearticle' => 'Лист бокӧс гижны',
'newarticle' => '(Выль)',

# History pages
'currentrev' => 'Быд лунся версия',

# Search results
'search-result-size' => '$1 ({{PLURAL:$2|$2 кыв}})',

# Preferences page
'timezonelegend' => 'Час кытш',

# Recent changes
'recentchanges' => 'Выль веськӧдӧмъяс',
'hide' => 'Дзебны',
'newpageletter' => 'В',
'boteditletter' => 'б',

# Recent changes linked
'recentchangeslinked-page' => 'Гижӧдлӧн ним:',

# Upload
'upload' => 'Файл сӧвтны',
'uploadbtn' => 'Файл сӧвтны',

# File description page
'filehist-datetime' => 'Кадпас/кад',
'filehist-user' => 'Пырысь',
'filehist-comment' => 'Пасйӧд',

# Random page
'randompage' => 'Кӧсйытӧг гижӧд',

# Miscellaneous special pages
'newpages' => 'Выль лист бокъяс',
'move' => 'Ним вежны',

# Special:Log
'specialloguserlabel' => 'Пырысь:',
'log' => 'Журналъяс',

# Special:AllPages
'allarticles' => 'Став гижӧдъяс',

# Watchlist
'mywatchlist' => 'Видзӧдӧм лыддьӧгӧй',

# Delete
'deletepage' => 'Лист бокӧс бырӧдны',
'deletereason-dropdown' => '* Типовые причины удаления
** вандализм
** по запросу автора
** нарушение авторских прав
* MediaWiki
** Дубликат сообщения с translatewiki.net',

# Protect
'protect-level-sysop' => 'Администраторъяс сӧмын',

# Namespace form on various pages
'namespace' => 'Ним пространство:',

# Contributions
'contributions' => 'Вӧлысьлӧн чӧжӧс',
'mycontris' => 'Чӧжӧсӧй',

# What links here
'whatlinkshere' => 'Ыстӧдъяс татчӧ',

# Block/unblock
'contribslink' => 'чӧжӧс',

# Move page
'newtitle' => 'Выль ним',
'movepagebtn' => 'Лист бокӧс ним вежны',

# Namespace 8 related
'allmessages' => 'Система юӧртӧмъяс',

# Tooltip help for the actions
'tooltip-pt-userpage' => 'Пырысьлӧн лист бокӧй',
'tooltip-ca-talk' => 'Гижӧдлӧн сёрнитӧм лист бокӧй али Википедиялӧн дӧнъялӧм лист бокӧй',
'tooltip-ca-move' => 'Лист боклӧн ним вежны',

# Special:SpecialPages
'specialpages' => 'Торъя лист бокъяс',

);
