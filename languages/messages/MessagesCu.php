<?php
/** Church Slavic (Словѣньскъ)
 *
 * @ingroup Language
 * @file
 *
 * @author SPQRobin
 * @author Svetko
 * @author Nike
 * @author Siebrand
 * @author Wolliger Mensch
 * @author ОйЛ
 */

$separatorTransformTable = array(
	',' => ".",
	'.' => ','
);

$linkPrefixExtension = true;

$namespaceNames = array(
	NS_MEDIA          => 'Срѣ́дьства',
	NS_SPECIAL        => 'Наро́чьна',
	NS_MAIN           => '',
	NS_TALK           => 'Бєсѣ́да',
	NS_USER           => 'По́льꙃєватєл҄ь',
	NS_USER_TALK      => 'По́льꙃєватєлꙗ_бєсѣ́да',
	# NS_PROJECT set by \$wgMetaNamespace
	NS_PROJECT_TALK   => '{{grammar:genitive|$1}}_бєсѣ́да',
	NS_IMAGE          => 'Ви́дъ',
	NS_IMAGE_TALK     => 'Ви́да_бєсѣ́да',
	NS_MEDIAWIKI      => 'MediaWiki',
	NS_MEDIAWIKI_TALK => 'MediaWiki_бєсѣ́да',
	NS_TEMPLATE       => 'Обраꙁь́ць',
	NS_TEMPLATE_TALK  => 'Обраꙁь́ца_бєсѣ́да',
	NS_HELP           => 'По́мощь',
	NS_HELP_TALK      => 'По́мощи_бєсѣ́да',
	NS_CATEGORY       => 'Катигорі́ꙗ',
	NS_CATEGORY_TALK  => 'Катигорі́ѩ_бєсѣ́да',
);

$namespaceAliases = array(
	'Срѣдьства'                      => NS_MEDIA,
	'Нарочьна'                       => NS_SPECIAL,
	'Бесѣда'                         => NS_TALK,
	'Польѕевател҄ь'                  => NS_USER,
	'Польѕевател_бесѣда'             => NS_USER_TALK,
	'{{grammar:genitive|$1}}_бесѣда' => NS_PROJECT_TALK,
	'Видъ'                           => NS_IMAGE,
	'Вида_бесѣда'                    => NS_IMAGE_TALK,
	'MediaWiki_бесѣда'               => NS_MEDIAWIKI_TALK,
	'Образьць'                       => NS_TEMPLATE,
	'Образьца_бесѣда'                => NS_TEMPLATE_TALK,
	'Помощь'                         => NS_HELP,
	'Помощи_бесѣда'                  => NS_HELP_TALK,
	'Катигорї'                      => NS_CATEGORY,
	'Катигорїѩ_бесѣда'               => NS_CATEGORY_TALK,
);

$defaultDateFormat = 'mdy';

$dateFormats = array(
	'mdy time' => 'H:i',
	'mdy date' => 'xg j числа, Y',
	'mdy both' => 'H:i, xg j числа, Y',

	'dmy time' => 'H:i',
	'dmy date' => 'j F Y',
	'dmy both' => 'H:i, j F Y',

	'ymd time' => 'H:i',
	'ymd date' => 'Y F j',
	'ymd both' => 'H:i, Y F j',

	'ISO 8601 time' => 'xnH:xni:xns',
	'ISO 8601 date' => 'xnY-xnm-xnd',
	'ISO 8601 both' => 'xnY-xnm-xnd"T"xnH:xni:xns',
);

$linkTrail = '/^([a-zабвгдеєжѕзїіıићклмнопсстѹфхѡѿцчшщъыьѣюѥѧѩѫѭѯѱѳѷѵґѓђёјйљњќуўџэ҄я“»]+)(.*)$/sDu';

$messages = array(
# Dates
'sunday'        => 'нєдѣ́лꙗ',
'monday'        => 'понедѣл҄ьникъ',
'tuesday'       => 'въторьникъ',
'wednesday'     => 'срѣда',
'thursday'      => 'четврьтъкъ',
'friday'        => 'пѧтъкъ',
'saturday'      => 'сѫбота',
'sun'           => 'н҃д',
'mon'           => 'п҃н',
'tue'           => 'в҃т',
'wed'           => 'с҃р',
'thu'           => 'ч҃т',
'fri'           => 'п҃т',
'sat'           => 'с҃б',
'january'       => 'їанѹарїи',
'february'      => 'февроуа́рїи',
'march'         => 'мартїи',
'april'         => 'апрїлїи',
'may_long'      => 'ма́їи',
'june'          => 'їоу́нїи',
'july'          => 'їоу́лїи',
'august'        => 'аѵгѹстъ',
'september'     => 'септемврїи',
'october'       => 'октѡврїи',
'november'      => 'ноемврїи',
'december'      => 'декемврїи',
'january-gen'   => 'їанѹарї',
'february-gen'  => 'фєвроуа́рїꙗ',
'march-gen'     => 'мартї',
'april-gen'     => 'апрїлї',
'may-gen'       => 'ма́їꙗ',
'june-gen'      => 'їоу́нїꙗ',
'july-gen'      => 'їоу́лїꙗ',
'august-gen'    => 'аѵгѹста',
'september-gen' => 'септемврї',
'october-gen'   => 'октѡврї',
'november-gen'  => 'ноемврї',
'december-gen'  => 'декемврї',
'jan'           => 'ꙗ҃н',
'feb'           => 'фе҃в',
'mar'           => 'ма҃р',
'apr'           => 'ап҃р',
'may'           => 'маи',
'jun'           => 'їо҃ун',
'jul'           => 'їо҃ул',
'aug'           => 'аѵ҃г',
'sep'           => 'се҃п',
'oct'           => 'ок҃т',
'nov'           => 'но҃е',
'dec'           => 'де҃к',

# Categories related messages
'pagecategories'  => '{{PLURAL:$1|Катигорі́ꙗ|Катигорі́и|Катигорі́ѩ|Катигорі́ѩ}}',
'category_header' => 'страни́цѧ катигорі́́ѩ ⁖ $1 ⁖',

'linkprefix' => '/^(.*?)(„|«)$/sD',

'qbedit'         => 'испра́ви',
'qbpageoptions'  => 'си страни́ца',
'qbmyoptions'    => 'моꙗ́ страни́цѧ',
'qbspecialpages' => 'наро́чьнꙑ страни́цѧ',
'mypage'         => 'моꙗ́ страни́ца',
'mytalk'         => 'моꙗ́ бєсѣ́да',
'navigation'     => 'пла́ваниѥ',
'and'            => 'и',

'help'             => 'по́мощь',
'search'           => 'иска́ниѥ',
'searchbutton'     => 'ищи́',
'go'               => 'прѣиди́',
'searcharticle'    => 'прѣиди́',
'history'          => 'страни́цѧ їсторі́ꙗ',
'history_short'    => 'їсторі́ꙗ',
'printableversion' => 'пєча́тьнъ о́браꙁъ',
'permalink'        => 'въи́ньна съвѧ́ꙁь',
'edit'             => 'испра́ви',
'delete'           => 'поничьжє́ниѥ',
'protect'          => 'ꙁабранѥ́ниѥ',
'unprotect'        => 'поущє́ниѥ',
'newpage'          => 'но́ва страни́ца',
'talkpagelinktext' => 'бєсѣ́да',
'specialpage'      => 'наро́чьна страни́ца',
'talk'             => 'бєсѣ́да',
'toolbox'          => 'орѫ́диꙗ',
'otherlanguages'   => 'ДРОУГꙐ́ ѨꙀꙐКꙐ́',
'redirectpagesub'  => 'прѣнаправлѥ́ниѥ',
'lastmodifiedat'   => 'страни́цѧ послѣ́дьнꙗ мѣ́на сътворѥна́ $2 · $1 бѣ ⁙', # $1 date, $2 time
'jumptonavigation' => 'пла́ваниѥ',
'jumptosearch'     => 'иска́ниѥ',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'О {{grammar:instrumental|{{SITENAME}}}}',
'aboutpage'            => 'Project:О сѥ́мь опꙑтьствова́нии',
'copyright'            => 'по́дъ прощє́ниѥмь $1 пьса́но ѥ́стъ',
'currentevents'        => 'сѫ́щѧѩ вѣ́щи',
'currentevents-url'    => 'Project:Сѫ́щѧѩ вѣ́щи',
'edithelppage'         => 'Help:Исправлѥ́ниѥ страни́цѧ',
'mainpage'             => 'гла́вьна страни́ца',
'mainpage-description' => 'гла́вьна страни́ца',
'portal'               => 'обьщє́ниꙗ съвѣ́тъ',
'portal-url'           => 'Project:Обьщє́ниꙗ съвѣ́тъ',
'sitesupport'          => 'даꙗ́ниꙗ',

'newmessageslink'     => 'но́ви напь́саниꙗ',
'newmessagesdifflink' => 'послѣ́дьнꙗ мѣ́на',
'editsection'         => 'испра́ви',
'editold'             => 'испра́ви',
'hidetoc'             => 'съкрꙑи',
'red-link-title'      => '$1 (ѥщє нє напь́сано ѥ́стъ)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'члѣ́нъ',
'nstab-user'      => 'по́льꙃєватєл҄ь',
'nstab-media'     => 'срѣ́дьства',
'nstab-special'   => 'наро́чьна',
'nstab-project'   => 'съвѣ́тъ',
'nstab-image'     => 'ви́дъ',
'nstab-mediawiki' => 'напьса́ниѥ',
'nstab-template'  => 'обраꙁь́ць',
'nstab-help'      => 'страни́ца по́мощи',
'nstab-category'  => 'катигорі́ꙗ',

# General errors
'viewsource' => 'страни́цѧ исто́чьнъ о́браꙁъ',

# Login and logout pages
'loginpagetitle'     => 'Въходъ',
'yourname'           => 'твоѥ́ и́мѧ',
'yourpassword'       => 'Таино слово напиши',
'yourpasswordagain'  => 'Опакы таиноѥ слово напиши',
'login'              => 'Въниди',
'userlogin'          => 'Въниди / съзижди си мѣсто',
'logout'             => 'ис̾хо́дъ',
'userlogout'         => 'ис̾хо́дъ',
'createaccount'      => 'Cъзижди си мѣсто',
'gotaccount'         => 'Мѣсто ти ѥстъ ли? $1.',
'gotaccountlink'     => 'Въниди',
'userexists'         => 'Сѫще польѕевател имѧ пьса. Ино изобрѧщи.',
'username'           => 'по́льꙃєватєлꙗ и́мѧ :',
'uid'                => 'по́льꙃєватєлꙗ число́ :',
'yourlanguage'       => 'ѩꙁꙑ́къ :',
'yournick'           => 'аѵто́графъ :',
'loginerror'         => 'Въхода блазна',
'loginlanguagelabel' => 'ѩꙁꙑ́къ : $1',

# Edit pages
'summary'        => 'опьса́ниѥ',
'savearticle'    => 'съхранѥ́ниѥ',
'loginreqlink'   => 'Въниди',
'newarticle'     => '(но́въ)',
'clearyourcache' => '<big>НАРОЧИ́ТО:</big> По съхранѥ́нии мо́жєши обити́ своѥго́ съмотри́ла съхра́нъ да ви́дѣлъ би мѣ́нꙑ ⁙ Mozilla ли Firefox ли Safari ли жьмꙑ́и Shift а мꙑ́шиѭ жьми́ Reload и́ли жьми́ Ctrl-Shift-R (Cmd-Shift-R вън Apple Mac) ⁙ IE ли жьмꙑ́и Ctrl а мꙑ́шиѭ жьми́ Refresh и́ли жьми́ Ctrl-F5 ⁙ Konqueror ли жьми́ кро́мѣ Reload и́ли F5 ⁙ О́пєрꙑ по́льꙃєватєльмъ мо́жєть бꙑ́ти ноужда́ пльнѣ пони́чьжити и́хъ съмотри́ла съхра́нъ въ Tools > Preferences',
'note'           => '<strong>НАРОЧИ́ТО:</strong>',
'editing'        => 'исправлѥ́ниѥ: $1',

# Revision feed
'history-feed-title' => 'мѣ́нъ їсторі́ꙗ',

# Revision deletion
'revdelete-uname' => 'по́льꙃєватєлꙗ и́мѧ',

# Search results
'search-result-size'       => '$1 ({{PLURAL:$2|$2 сло́во|$2 сло́ва|$2 словє́съ}})',
'search-interwiki-caption' => 'ро́дьствьна опꙑтьствова́ниꙗ',
'searchall'                => 'вьсꙗ́',
'powersearch'              => 'ищи́',

# Preferences page
'mypreferences'       => 'мои строи',
'prefs-rc'            => 'послѣ́дьнѩ мѣ́нꙑ',
'searchresultshead'   => 'иска́ниѥ',
'prefs-searchoptions' => 'иска́ниꙗ строи́',

# Groups
'group-user'       => 'по́льꙃєватєлє',
'group-bot'        => 'аѵтома́ти',
'group-sysop'      => 'съмотри́тєлє',
'group-bureaucrat' => 'Чинода́тєлє',

'group-user-member'       => 'по́льꙃєватєл҄ь',
'group-bot-member'        => 'аѵтома́тъ',
'group-sysop-member'      => 'съмотри́тєл҄ь',
'group-bureaucrat-member' => 'чинода́тєл҄ь',

'grouppage-user'       => '{{ns:project}}:По́льꙃєватєлє',
'grouppage-bot'        => '{{ns:project}}:Аѵтома́ти',
'grouppage-sysop'      => '{{ns:project}}:Съмотри́тєлє',
'grouppage-bureaucrat' => '{{ns:project}}:Чинода́тєлє',

# Recent changes
'nchanges'        => '$1 {{PLURAL:$1|мѣ́на|мѣ́нꙑ|мѣ́нъ}}',
'recentchanges'   => 'послѣ́дьнѩ мѣ́нꙑ',
'rcshowhideminor' => '$1 ма́лꙑ мѣ́нꙑ',
'rcshowhidebots'  => '$1 аѵтома́тъ',
'hist'            => 'їс҃т',
'hide'            => 'съкрꙑи',
'minoreditletter' => 'м҃',
'newpageletter'   => 'н҃',

# Recent changes linked
'recentchangeslinked' => 'съвѧ́ꙁанꙑ страни́цѧ',

# Upload
'upload'        => 'положє́ниѥ дѣ́ла',
'uploadbtn'     => 'положє́ниѥ дѣ́ла',
'uploadedimage' => '⁖ [[$1]] ⁖ положє́нъ ѥ́стъ',

# Special:Imagelist
'imgfile'        => 'дѣ́ло',
'imagelist_name' => 'и́мѧ',
'imagelist_user' => 'по́льꙃєватєл҄ь',

# Image description page
'filehist-deleteone' => 'поничьжє́ниѥ',
'filehist-user'      => 'по́льꙃєватєл҄ь',
'imagelinks'         => 'съвѧ́ꙁи',

# File deletion
'filedelete-submit' => 'поничьжє́ниѥ',

# MIME search
'download' => 'поѩ́ти',

# Random page
'randompage' => 'страни́ца въ нєꙁаа́пѫ',

# Statistics
'statistics'    => 'Статїстїка',
'sitestats'     => '{{SITENAME}} статїстїка',
'userstats'     => 'Польѕевателъ статїстїка',
'sitestatstext' => "Википедїи '''$1''' {{PLURAL:$1|страница ѥстъ|страници ѥсте|страницѧ сѫтъ|страниць сѫтъ}}, посрѣдѣ {{PLURAL:$1|ѩже|ѥюже|ихъже|ихъже}} и бесѣды, и страницѧ о Википедїи, и ѕѣло малы статїѩ, и прѣнаправлѥни, и дрѹгы страницѧ сѫтъ, ѩже истиньны статїѩ не сѫтъ. Бежихъ Википедїи '''$2''' {{PLURAL:$2|страница ѥстъ|страници ѥсте|страницѧ сѫтъ|страниць сѫтъ}} ѩже {{PLURAL:$2|истиньна статї ѥстъ|истиньнѣ статїи ѥсте|истиньны статїѩ сѫтъ|истиньны статїѩ сѫтъ}}. Википедїи '''$8''' {{PLURAL:$8|дѣло ѥстъ|дѣлѣ ѥсте|дѣла сѫтъ|дѣлъ сѫтъ}}. О прьваѥго Википедїѩ дьне '''$4''' {{PLURAL:$4|исправлѥниѥ сътворѥно ѥстъ|исправлѥнии сътворѥнѣ ѥсте|исправлѥни сътворѥна сѫтъ|исправлѥнии сътворѥна сѫтъ}}. Сѥ значитъ ко кажьдо страница '''$5''' исправлѥни иматъ. [http://www.mediawiki.org/wiki/Manual:Job_queue Дѣиствъ чрѣды] дльгота '''$7''' ѥстъ.",

'brokenredirects-edit'   => '(испра́ви)',
'brokenredirects-delete' => '(поничьжє́ниѥ)',

# Miscellaneous special pages
'listusers'         => 'по́льꙃєватєлъ катало́гъ',
'newpages'          => 'но́ви члѣ́ни',
'newpages-username' => 'по́льꙃєватєлꙗ и́мѧ :',
'move'              => 'прѣимєнова́ниѥ',

# Book sources
'booksources-go' => 'прѣиди́',

# Special:Log
'specialloguserlabel' => 'по́льꙃєватєл҄ь:',
'log-search-submit'   => 'прѣиди́',

# Special:Allpages
'allpages'       => 'вьсѩ́ страни́цѧ',
'allpagesfrom'   => 'Страницѧ видѣти хощѫ съ начѧльнами бѹкъвами:',
'allarticles'    => 'вьсѩ́ страни́цѧ',
'allpagessubmit' => 'прѣиди́',

# Special:Categories
'categories' => 'катигорі́ѩ',

# E-mail user
'emailuser' => 'Посъли епїстолѫ',

# Watchlist
'watchlist'   => 'моꙗ́ блюдє́ниꙗ',
'mywatchlist' => 'Моꙗ́ блюдє́ниꙗ',
'watch'       => 'блюдє́ниѥ',
'unwatch'     => 'оста́ви блюдє́ниѥ',

'created' => 'сътворѥ́нъ ѥ́стъ',

# Delete/protect/revert
'excontent'       => "вънѫтри бѣ: '$1'",
'excontentauthor' => "вънѫтри бѣ: '$1' (и послѣдьн҄ии дѣтел҄ь бѣ '[[Special:Contributions/$2|$2]]')",
'delete-legend'   => 'поничьжє́ниѥ',

# Restrictions (nouns)
'restriction-edit'   => 'испра́ви',
'restriction-move'   => 'прѣимєнова́ниѥ',
'restriction-upload' => 'положє́ниѥ',

# Contributions
'contributions' => 'по́льꙃєватєлꙗ добродѣꙗ́ниꙗ',
'mycontris'     => 'моꙗ́ добродѣꙗ́ниꙗ',
'uctop'         => '(послѣ́дьнꙗ мѣ́на)',

# What links here
'whatlinkshere'       => 'дос̑ьдє́щьнѩ съвѧ́ꙁи',
'whatlinkshere-page'  => 'страни́ца :',
'isredirect'          => 'прѣнаправлѥ́ниѥ',
'whatlinkshere-links' => '← съвѧ́ꙁи',

# Block/unblock
'blockip'            => 'Загради польѕеватель',
'ipblocklist-submit' => 'иска́ниѥ',
'contribslink'       => 'добродѣꙗ́ниꙗ',

# Move page
'1movedto2'       => '[[$1]] нарєчє́нъ [[$2]] ѥ́стъ',
'1movedto2_redir' => '[[$1]] нарєчє́нъ [[$2]] врьхоу́ прѣнаправлѥ́ниꙗ ѥ́стъ.',

# Namespace 8 related
'allmessagesname' => 'и́мѧ',

# Tooltip help for the actions
'tooltip-pt-logout'       => 'ис̾хо́дъ',
'tooltip-p-logo'          => 'гла́вьна страни́ца',
'tooltip-n-recentchanges' => 'послѣ́дьнъ мѣ́нъ катало́гъ',

# Special:Newimages
'ilsubmit' => 'Ищи',

# 'all' in various places, this might be different for inflected languages
'namespacesall' => 'вьсꙗ́',

# Multipage image navigation
'imgmultigo' => 'прѣиди́',

# Table pager
'table_pager_limit_submit' => 'прѣиди́',

# Special:Version
'version'                  => 'о́браꙁъ', # Not used as normal message but as header for the special page itself
'version-version'          => 'о́браꙁъ',
'version-software-version' => 'о́браꙁъ',

# Special:Filepath
'filepath-page' => 'дѣ́ло :',

# Special:SpecialPages
'specialpages' => 'наро́чьнꙑ страни́цѧ',

);
