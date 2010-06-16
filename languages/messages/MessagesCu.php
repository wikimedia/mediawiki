<?php
/** Church Slavic (Словѣ́ньскъ / ⰔⰎⰑⰂⰡⰐⰠⰔⰍⰟ)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Omnipaedista
 * @author Svetko
 * @author Wolliger Mensch
 * @author ОйЛ
 */

$separatorTransformTable = array(
	',' => ".",
	'.' => ','
);

$linkPrefixExtension = true;

$namespaceNames = array(
	NS_MEDIA            => 'Срѣ́дьства',
	NS_SPECIAL          => 'Наро́чьна',
	NS_TALK             => 'Бєсѣ́да',
	NS_USER             => 'По́льꙃєватєл҄ь',
	NS_USER_TALK        => 'По́льꙃєватєлꙗ_бєсѣ́да',
	NS_PROJECT_TALK     => '{{GRAMMAR:genitive|$1}}_бєсѣ́да',
	NS_FILE             => 'Дѣ́ло',
	NS_FILE_TALK        => 'Дѣ́ла_бєсѣ́да',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki_бєсѣ́да',
	NS_TEMPLATE         => 'Обраꙁь́ць',
	NS_TEMPLATE_TALK    => 'Обраꙁьца́_бєсѣ́да',
	NS_HELP             => 'По́мощь',
	NS_HELP_TALK        => 'По́мощи бєсѣ́да',
	NS_CATEGORY         => 'Катигорі́ꙗ',
	NS_CATEGORY_TALK    => 'Катигорі́ѩ_бєсѣ́да',
);

$namespaceAliases = array(
	'Срѣдьства'                      => NS_MEDIA,
	'Нарочьна'                       => NS_SPECIAL,
	'Бесѣда'                         => NS_TALK,
	'Польѕевател҄ь'                  => NS_USER,
	'Польѕевател_бесѣда'             => NS_USER_TALK,
	'{{grammar:genitive|$1}}_бесѣда' => NS_PROJECT_TALK,
	'Ви́дъ'                          => NS_FILE,
	'Видъ'                           => NS_FILE,
	'Ви́да_бєсѣ́да'                   => NS_FILE_TALK,
	'Вида_бесѣда'                    => NS_FILE_TALK,
	'MediaWiki_бесѣда'               => NS_MEDIAWIKI_TALK,
	'Образьць'                       => NS_TEMPLATE,
	'Образьца_бесѣда'                => NS_TEMPLATE_TALK,
	'Помощь'                         => NS_HELP,
	'Помощи_бесѣда'                  => NS_HELP_TALK,
	'Катигорї'                      => NS_CATEGORY,
	'Катигорїѩ_бесѣда'               => NS_CATEGORY_TALK,
);

$magicWords = array(
	'redirect'              => array( '0', '#ПРѢНАПРАВЛЄНИѤ', '#REDIRECT' ),
	'language'              => array( '0', '#ѨꙀꙐКЪ:', '#LANGUAGE:' ),
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
'january'       => 'їаноуа́рїи',
'february'      => 'февроуа́рїи',
'march'         => 'мартїи',
'april'         => 'апрі́лїи',
'may_long'      => 'ма́їи',
'june'          => 'їоу́нїи',
'july'          => 'їоу́лїи',
'august'        => 'аѵ́гоустъ',
'september'     => 'сєптє́мврїи',
'october'       => 'октѡ́врїи',
'november'      => 'ноє́мврїи',
'december'      => 'дєкє́мврїи',
'january-gen'   => 'їаноуа́рїꙗ',
'february-gen'  => 'фєвроуа́рїꙗ',
'march-gen'     => 'ма́ртїꙗ',
'april-gen'     => 'апрі́лїꙗ',
'may-gen'       => 'ма́їꙗ',
'june-gen'      => 'їоу́нїꙗ',
'july-gen'      => 'їоу́лїꙗ',
'august-gen'    => 'аѵ́гоуста',
'september-gen' => 'сєптє́мврїꙗ',
'october-gen'   => 'октѡ́врїꙗ',
'november-gen'  => 'ноє́мврїꙗ',
'december-gen'  => 'дєкє́мврїꙗ',
'jan'           => 'ꙗ҃н',
'feb'           => 'фє҃в',
'mar'           => 'ма҃р',
'apr'           => 'ап҃р',
'may'           => 'маи',
'jun'           => 'їо҃ун',
'jul'           => 'їо҃ул',
'aug'           => 'аѵ҃г',
'sep'           => 'сє҃п',
'oct'           => 'ок҃т',
'nov'           => 'но҃є',
'dec'           => 'дє҃к',

# Categories related messages
'pagecategories'         => '{{PLURAL:$1|Катигорі́ꙗ|Катигорі́и|Катигорі́ѩ|Катигорі́ѩ}}',
'category_header'        => 'катигорі́ѩ ⁖ $1 ⁖ страни́цѧ',
'subcategories'          => 'соубкатигорі́ѩ',
'listingcontinuesabbrev' => '· вѧ́щє',

'linkprefix' => '/^(.*?)(„|«)$/sD',

'about'      => 'опьса́ниѥ',
'mypage'     => 'моꙗ́ страни́ца',
'mytalk'     => 'моꙗ́ бєсѣ́да',
'navigation' => 'пла́ваниѥ',
'and'        => '&#32;и',

# Cologne Blue skin
'qbedit'         => 'испра́ви',
'qbpageoptions'  => 'си страни́ца',
'qbmyoptions'    => 'моꙗ́ страни́цѧ',
'qbspecialpages' => 'наро́чьнꙑ страни́цѧ',

# Vector skin
'vector-action-move'        => 'прѣимєнова́ниѥ',
'vector-namespace-category' => 'катигорі́ꙗ',
'vector-view-edit'          => 'испра́ви',
'vector-view-viewsource'    => 'страни́цѧ исто́чьнъ о́браꙁъ',

'errorpagetitle'   => 'блаꙁна',
'tagline'          => '{{grammar:genitive|{{SITENAME}}}} страни́ца',
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
'create'           => 'сътворѥ́ниѥ',
'editthispage'     => 'си страни́цѧ исправлѥ́ниѥ',
'create-this-page' => 'си страни́цѧ сътворѥ́ниѥ',
'delete'           => 'поничьжє́ниѥ',
'deletethispage'   => 'си страни́цѧ поничьжє́ниѥ',
'protect'          => 'ꙁабранѥ́ниѥ',
'protectthispage'  => 'си страни́цѧ ꙁабранє́ниѥ',
'unprotect'        => 'поущє́ниѥ',
'newpage'          => 'но́ва страни́ца',
'talkpage'         => 'си страни́цѧ бєсѣ́да',
'talkpagelinktext' => 'бєсѣ́да',
'specialpage'      => 'наро́чьна страни́ца',
'personaltools'    => 'моꙗ́ орѫ́диꙗ',
'talk'             => 'бєсѣ́да',
'toolbox'          => 'орѫ́диꙗ',
'otherlanguages'   => 'ДРОУГꙐ́ ѨꙀꙐКꙐ́',
'redirectedfrom'   => '(прѣнаправлѥ́ниѥ о́тъ ⁖ $1 ⁖)',
'redirectpagesub'  => 'прѣнаправлѥ́ниѥ',
'lastmodifiedat'   => 'страни́цѧ послѣ́дьнꙗ мѣ́на сътворѥна́ $2 · $1 бѣ ⁙',
'jumptonavigation' => 'пла́ваниѥ',
'jumptosearch'     => 'иска́ниѥ',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'О {{grammar:instrumental|{{SITENAME}}}}',
'aboutpage'            => 'Project:О сѥ́мь опꙑтьствова́нии',
'copyright'            => 'по́дъ прощє́ниѥмь $1 пьса́но ѥ́стъ',
'copyrightpage'        => '{{ns:project}}:Творь́цъ права́',
'currentevents'        => 'сѫ́щѧѩ вѣ́щи',
'currentevents-url'    => 'Project:Сѫ́щѧѩ вѣ́щи',
'edithelp'             => 'по́мощь по исправлѥ́ниѭ',
'edithelppage'         => 'Help:Исправлѥ́ниѥ страни́цѧ',
'mainpage'             => 'гла́вьна страни́ца',
'mainpage-description' => 'гла́вьна страни́ца',
'portal'               => 'обьщє́ниꙗ съвѣ́тъ',
'portal-url'           => 'Project:Обьщє́ниꙗ съвѣ́тъ',

'newmessageslink'     => 'но́ви напь́саниꙗ',
'newmessagesdifflink' => 'послѣ́дьнꙗ мѣ́на',
'editsection'         => 'испра́ви',
'editold'             => 'испра́ви',
'viewsourceold'       => 'страни́цѧ исто́чьнъ о́браꙁъ',
'editlink'            => 'испра́ви',
'viewsourcelink'      => 'страни́цѧ исто́чьнъ о́браꙁъ',
'editsectionhint'     => 'исправлѥ́ниѥ чѧ́сти : $1',
'showtoc'             => 'ви́ждь',
'hidetoc'             => 'съкрꙑи',
'viewdeleted'         => '$1 ви́дєти хо́щєши ;',
'red-link-title'      => '$1 (си страни́цѧ нѣ́стъ)',

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
'viewsource'    => 'страни́цѧ исто́чьнъ о́браꙁъ',
'viewsourcefor' => '$1 дѣлꙗ',

# Login and logout pages
'yourname'                => 'твоѥ́ и́мѧ',
'yourpassword'            => 'Таино слово напиши',
'yourpasswordagain'       => 'Опакы таиноѥ слово напиши',
'login'                   => 'Въниди',
'nav-login-createaccount' => 'въниди / съꙁи́жди си мѣ́сто',
'userlogin'               => 'въниди / съꙁи́жди си мѣ́сто',
'logout'                  => 'ис̾хо́дъ',
'userlogout'              => 'ис̾хо́дъ',
'createaccount'           => 'Cъзижди си мѣсто',
'gotaccount'              => "Мѣсто ти ѥстъ ли? '''$1'''.",
'gotaccountlink'          => 'Въниди',
'userexists'              => 'сѫщє по́льꙃєватєлꙗ и́мѧ пьса ⁙ ино иꙁобрѧщи',
'loginerror'              => 'Въхода блазна',
'accountcreated'          => 'мѣ́сто сътворєно́ ѥ́стъ',
'loginlanguagelabel'      => 'ѩꙁꙑ́къ : $1',

# Edit page toolbar
'link_sample'    => 'съвѧ́ꙁи и́мѧ',
'extlink_sample' => 'http://www.example.com съвѧ́ꙁи и́мѧ',
'math_tip'       => 'маѳиматїчьна формоула (LaTeX)',

# Edit pages
'summary'            => 'опьса́ниѥ :',
'minoredit'          => 'ма́лаꙗ мѣ́на',
'watchthis'          => 'си страни́цѧ блюдє́ниѥ',
'savearticle'        => 'съхранѥ́ниѥ',
'loginreqlink'       => 'Въниди',
'newarticle'         => '(но́въ)',
'clearyourcache'     => '<big>НАРОЧИ́ТО:</big> По съхранѥ́нии мо́жєши обити́ своѥго́ съмотри́ла съхра́нъ да ви́дѣлъ би мѣ́нꙑ ⁙ Mozilla ли Firefox ли Safari ли жьмꙑ́и Shift а мꙑ́шиѭ жьми́ Reload и́ли жьми́ Ctrl-Shift-R (Cmd-Shift-R вън Apple Mac)  ⁙ Konqueror ли жьми́ кро́мѣ Reload и́ли F5 ⁙ О́пєрꙑ по́льꙃєватєльмъ мо́жєть бꙑ́ти ноужда́ пльнѣ пони́чьжити и́хъ съмотри́ла съхра́нъ въ Tools > Preferences ⁙ IE ли жьмꙑ́и Ctrl а мꙑ́шиѭ жьми́ Refresh и́ли жьми́ Ctrl-F5',
'note'               => "'''НАРОЧИ́ТО:'''",
'editing'            => 'исправлѥ́ниѥ: $1',
'editingsection'     => 'исправлѥ́ниѥ ⁖ $1 ⁖ (чѧ́сть)',
'templatesused'      => 'сѥѩ страни́цѧ {{PLURAL:$1|сь обраꙁь́ць по́льꙃоуѥтъ сѧ ѥ́стъ|с҄и обраꙁьца́ по́льꙃоуѭтъ сѧ ѥстє́|с҄и обраꙁьци́ по́льꙃоуѭтъ сѧ сѫ́тъ}} :',
'template-protected' => '(ꙁабранєно ѥ́стъ)',

# History pages
'viewpagelogs' => 'си страни́цѧ їсторі́ѩ',
'cur'          => 'нꙑ҃н',
'last'         => 'пс҃лд',
'page_first'   => 'прь́ва страни́ца',
'page_last'    => 'послѣ́дьнꙗ страни́ца',
'histlast'     => 'послѣ́дьнꙗ',
'historyempty' => '(поу́сто)',

# Revision feed
'history-feed-title'          => 'мѣ́нъ їсторі́ꙗ',
'history-feed-item-nocomment' => '$1 при $2',

# Revision deletion
'revdelete-uname' => 'по́льꙃєватєлꙗ и́мѧ',

# Search results
'searchresults'            => 'иска́ниꙗ ито́гъ',
'searchresults-title'      => 'иска́ниꙗ ⁖ $1 ⁖ ито́гъ',
'searchprofile-images'     => 'дѣ́ла',
'search-result-size'       => '$1 ({{PLURAL:$2|$2 сло́во|$2 сло́ва|$2 словє́съ}})',
'search-redirect'          => '(прѣнаправлє́ниѥ $1)',
'search-section'           => '(чѧ́сть $1)',
'search-interwiki-caption' => 'ро́дьствьна опꙑтьствова́ниꙗ',
'searchall'                => 'вьсꙗ́',
'powersearch'              => 'ищи́',

# Preferences page
'preferences'         => 'строи',
'mypreferences'       => 'мои строи',
'prefs-rc'            => 'послѣ́дьнѩ мѣ́нꙑ',
'prefs-watchlist'     => 'блюдє́ниꙗ',
'searchresultshead'   => 'иска́ниѥ',
'prefs-searchoptions' => 'иска́ниꙗ строи́',
'prefs-files'         => 'дѣла́',
'username'            => 'по́льꙃєватєлꙗ и́мѧ :',
'uid'                 => 'по́льꙃєватєлꙗ число́ :',
'yourrealname'        => 'и́стиньно и́мѧ :',
'yourlanguage'        => 'ѩꙁꙑ́къ :',
'yournick'            => 'аѵто́графъ :',
'gender-male'         => 'мѫжъ',
'gender-female'       => 'жєна',

# Groups
'group-user'       => 'по́льꙃєватєлє',
'group-bot'        => 'аѵтома́ти',
'group-sysop'      => 'съмотри́тєлє',
'group-bureaucrat' => 'чинода́тєлє',

'group-user-member'       => 'по́льꙃєватєл҄ь',
'group-bot-member'        => 'аѵтома́тъ',
'group-sysop-member'      => 'съмотри́тєл҄ь',
'group-bureaucrat-member' => 'чинода́тєл҄ь',

'grouppage-user'       => '{{ns:project}}:По́льꙃєватєлє',
'grouppage-bot'        => '{{ns:project}}:Аѵтома́ти',
'grouppage-sysop'      => '{{ns:project}}:Съмотри́тєлє',
'grouppage-bureaucrat' => '{{ns:project}}:Чинода́тєлє',

# User rights log
'rightslog' => 'чинода́тєльства їсторі́ꙗ',

# Associated actions - in the sentence "You do not have permission to X"
'action-edit' => 'си страни́цѧ исправлє́ниѥ',

# Recent changes
'nchanges'        => '$1 {{PLURAL:$1|мѣ́на|мѣ́нꙑ|мѣ́нъ}}',
'recentchanges'   => 'послѣ́дьнѩ мѣ́нꙑ',
'rcnote'          => "нижѣ {{PLURAL:$1|'''1''' послѣ́дьнѭ мѣ́нѫ|'''$1''' послѣ́дьни мѣ́нꙑ|'''$1''' послѣ́дьнъ мѣ́нъ|'''$1''' послѣ́дьнъ мѣ́нъ}} ꙁа {{PLURAL:$2|дьнь|'''$2''' дьнꙗ|'''$2''' дьнъ|'''$2''' дьнъ}} · ꙗко нꙑнѣ $5 · $4 лѣ́та",
'rcshowhideminor' => '$1 ма́лꙑ мѣ́нꙑ',
'rcshowhidebots'  => '$1 аѵтома́тъ',
'rcshowhideliu'   => '$1 по́льꙃєватєлъ · ѩжє съꙁижьдє сѥ мѣ́сто · мѣ́нꙑ',
'rcshowhideanons' => '$1 анонѷмьнъ по́льꙃєватєлъ мѣ́нꙑ',
'rcshowhidemine'  => '$1 моꙗ́ мѣ́нꙑ',
'diff'            => 'ра҃ꙁн',
'hist'            => 'їс҃т',
'hide'            => 'съкрꙑи',
'show'            => 'ви́ждь',
'minoreditletter' => 'м҃л',
'newpageletter'   => 'н҃в',
'boteditletter'   => 'а҃ѵ',

# Recent changes linked
'recentchangeslinked'         => 'съвѧ́ꙁанꙑ страни́цѧ',
'recentchangeslinked-feed'    => 'съвѧ́ꙁанꙑ страни́цѧ',
'recentchangeslinked-toolbox' => 'съвѧ́ꙁанꙑ страни́цѧ',
'recentchangeslinked-page'    => 'страни́цѧ и́мѧ :',

# Upload
'upload'            => 'положє́ниѥ дѣ́ла',
'uploadbtn'         => 'положє́ниѥ дѣ́ла',
'uploadlog'         => 'дѣ́лъ положє́ниꙗ їсторі́ꙗ',
'uploadlogpage'     => 'дѣ́лъ положє́ниꙗ їсторі́ꙗ',
'filename'          => 'дѣ́ла и́мѧ',
'filedesc'          => 'опьса́ниѥ',
'fileuploadsummary' => 'опьса́ниѥ:',
'successfulupload'  => 'дѣ́ло положєно ѥ́стъ',
'uploadedimage'     => '⁖ [[$1]] ⁖ положє́нъ ѥ́стъ',
'watchthisupload'   => 'си дѣ́ла блюдє́ниѥ',

# Special:ListFiles
'imgfile'        => 'дѣ́ло',
'listfiles'      => 'дѣ́лъ ката́логъ',
'listfiles_name' => 'и́мѧ',
'listfiles_user' => 'по́льꙃєватєл҄ь',
'listfiles_size' => 'мѣ́ра',

# File description page
'file-anchor-link'   => 'ви́дъ',
'filehist-deleteone' => 'поничьжє́ниѥ',
'filehist-current'   => 'нꙑнѣщьн҄ь о́браꙁъ',
'filehist-datetime'  => 'дьнь / врѣ́мѧ',
'filehist-user'      => 'по́льꙃєватєл҄ь',
'imagelinks'         => 'дѣ́ла съвѧ́ꙁи',

# File deletion
'filedelete-submit' => 'поничьжє́ниѥ',

# MIME search
'mimetype' => 'MIME тѵ́пъ :',
'download' => 'поѩ́ти',

# Random page
'randompage' => 'страни́ца въ нєꙁаа́пѫ',

# Random redirect
'randomredirect' => 'прѣнаправлѥ́ниє въ нєꙁаа́пѫ',

# Statistics
'statistics'              => 'Статїстїка',
'statistics-header-pages' => 'страни́цѧ статїстїка',
'statistics-header-edits' => 'мѣ́нъ статїстїка',
'statistics-header-users' => 'по́льꙃєватєлъ статїстїка',
'statistics-articles'     => 'истиньнꙑ члѣ́ни',
'statistics-pages'        => 'страни́цѧ',
'statistics-files'        => 'положєнꙑ дѣла́',

'disambiguations'     => 'мъногосъмꙑ́слиꙗ',
'disambiguationspage' => 'Template:мъногосъмꙑ́слиѥ',

'brokenredirects-edit'   => 'испра́ви',
'brokenredirects-delete' => 'поничьжє́ниѥ',

# Miscellaneous special pages
'nbytes'            => '$1 {{PLURAL:$1|ба́итъ|ба́ита|ба́итъ}}',
'nlinks'            => '$1 {{PLURAL:$1|съвѧ́ꙁь|съвѧ́ꙁѧ|съвѧ́ꙁи}}',
'listusers'         => 'по́льꙃєватєлъ ката́логъ',
'newpages'          => 'но́ви члѣ́ни',
'newpages-username' => 'по́льꙃєватєлꙗ и́мѧ :',
'move'              => 'прѣимєнова́ниѥ',
'movethispage'      => 'си страни́цѧ прѣимєнова́ниѥ',

# Book sources
'booksources-go' => 'прѣиди́',

# Special:Log
'specialloguserlabel'  => 'по́льꙃєватєл҄ь:',
'speciallogtitlelabel' => 'страни́цѧ и́мѧ :',
'log'                  => 'їсторі́ѩ',
'all-logs-page'        => 'вьсѩ́ їсторі́ѩ',

# Special:AllPages
'allpages'       => 'вьсѩ́ страни́цѧ',
'alphaindexline' => 'о́тъ $1 до $2',
'allpagesfrom'   => 'страни́цѧ видѣ́ти хощѫ́ съ начѧ́льнами боу́къвами :',
'allarticles'    => 'вьсѩ́ страни́цѧ',
'allpagessubmit' => 'прѣиди́',

# Special:Categories
'categories' => 'катигорі́ѩ',

# Special:DeletedContributions
'deletedcontributions'       => 'пони́чьжєнꙑ добродѣꙗ́ниꙗ',
'deletedcontributions-title' => 'пони́чьжєнꙑ добродѣꙗ́ниꙗ',

# Special:LinkSearch
'linksearch-ok' => 'ищи́',

# Special:ListUsers
'listusers-submit' => 'ви́ждь',

# Special:Log/newusers
'newuserlogpage'              => 'но́въ мѣ́стъ сътворѥ́ниꙗ їсторі́ꙗ',
'newuserlog-create-entry'     => 'но́въ по́льꙃєватєл҄ь',
'newuserlog-autocreate-entry' => 'по́льꙃєватєлꙗ мѣ́сто аѵтомати́чьно сътворєно́ ѥ́стъ',

# E-mail user
'emailuser' => 'посъли єпїстолѫ',

# Watchlist
'watchlist'      => 'моꙗ́ блюдє́ниꙗ',
'mywatchlist'    => 'Моꙗ́ блюдє́ниꙗ',
'watchlistfor'   => "(по́льꙃєватєлꙗ и́мѧ '''$1''' ѥ́стъ)",
'addedwatchtext' => "страни́ца ⁖ [[:$1]] ⁖ нꙑнѣ по́дъ твоимь [[Special:Watchlist|блюдє́ниѥмь]] ѥ́стъ ⁙
всꙗ ѥѩ и ѥѩжє бєсѣдꙑ мѣ́нꙑ страни́цѧ ⁖ [[Special:Watchlist|моꙗ́ блюдє́ниꙗ]] ⁖ покаꙁанꙑ сѫ́тъ и  [[Special:RecentChanges|послѣ́дьнъ мѣ́нъ]] ката́лоꙃѣ '''чрьнꙑимъ''' сѧ авлꙗѭтъ",
'watch'          => 'блюдє́ниѥ',
'watchthispage'  => 'си страни́цѧ блюдє́ниѥ',
'unwatch'        => 'оста́ви блюдє́ниѥ',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'блюдє́ниѥ ...',
'unwatching' => 'оставьлє́ниѥ блюдє́ниꙗ ...',

'created' => 'сътворѥ́нъ ѥ́стъ',

# Delete
'deletepage'      => 'поничьжє́ниѥ',
'excontent'       => "вънѫтри бѣ: '$1'",
'excontentauthor' => "вънѫтри́ бѣ : '$1' (и послѣ́дьн҄ии дѣ́тєл҄ь бѣ '[[Special:Contributions/$2|$2]]')",
'delete-legend'   => 'поничьжє́ниѥ',
'actioncomplete'  => 'дѣ́иство сътворєно́ ѥ́стъ',
'deletedtext'     => 'страни́ца ⁖ <nowiki>$1</nowiki> ⁖ поничьжєна ѥ́стъ ⁙ 
виждь ⁖ $2 ⁖ послѣ́дьнъ поничьжє́ниѩ дѣлꙗ́',
'deletedarticle'  => '⁖ [[$1]] ⁖ поничьжє́нъ ѥ́стъ',
'dellogpage'      => 'поничьжє́ниꙗ їсторі́ꙗ',
'deletionlog'     => 'поничьжє́ниꙗ їсторі́ꙗ',

# Protect
'protectlogpage'      => 'ꙁабранѥ́ниꙗ їсторі́ꙗ',
'protectedarticle'    => '⁖ [[$1]] ⁖ ꙁабранѥна ѥ́стъ',
'prot_1movedto2'      => '⁖ [[$1]] ⁖ нарєчє́нъ ⁖ [[$2]] ⁖ ѥ́стъ',
'protect-level-sysop' => 'то́лико съмотри́тєлє',

# Restrictions (nouns)
'restriction-edit'   => 'испра́ви',
'restriction-move'   => 'прѣимєнова́ниѥ',
'restriction-upload' => 'положє́ниѥ',

# Undelete
'undelete-search-submit' => 'ищи́',

# Namespace form on various pages
'namespace'      => 'имє́нъ просто́ръ:',
'blanknamespace' => '(гла́вьно)',

# Contributions
'contributions'       => 'по́льꙃєватєлꙗ добродѣꙗ́ниꙗ',
'contributions-title' => 'по́льꙃєватєлꙗ ⁖ $1 ⁖ добродѣꙗ́ниꙗ',
'mycontris'           => 'моꙗ́ добродѣꙗ́ниꙗ',
'contribsub2'         => 'по́льꙃєватєлꙗ и́мѧ ⁖ $1 ⁖ ѥ́стъ ($2)',
'uctop'               => '(послѣ́дьнꙗ мѣ́на)',

'sp-contributions-blocklog' => 'ꙁаграждє́ниꙗ їсторі́ꙗ',
'sp-contributions-deleted'  => 'пони́чьжєнꙑ добродѣꙗ́ниꙗ',
'sp-contributions-talk'     => 'бєсѣ́да',
'sp-contributions-submit'   => 'ищи́',

# What links here
'whatlinkshere'           => 'дос̑ьдє́щьнѩ съвѧ́ꙁи',
'whatlinkshere-title'     => 'страни́цѧ ижє съ ⁖ $1 ⁖ съвѧ́ꙁи имѫтъ',
'whatlinkshere-page'      => 'страни́ца :',
'isredirect'              => 'прѣнаправлѥ́ниѥ',
'whatlinkshere-links'     => '← съвѧ́ꙁи',
'whatlinkshere-hidelinks' => '$1 съвѧ́ꙁи',

# Block/unblock
'blockip'            => 'ꙁагради́ по́льꙃєватєл҄ь',
'ipblocklist-submit' => 'иска́ниѥ',
'blocklink'          => 'ꙁагради́',
'contribslink'       => 'добродѣꙗ́ниꙗ',
'blocklogpage'       => 'ꙁаграждє́ниꙗ їсторі́ꙗ',

# Move page
'move-page'        => 'прѣимєнова́ниѥ ⁖ $1 ⁖',
'move-page-legend' => 'страни́цѧ прѣимєнова́ниѥ',
'movearticle'      => 'страни́ца :',
'newtitle'         => 'но́во и́мѧ :',
'move-watch'       => 'си страни́цѧ блюдє́ниѥ',
'movepagebtn'      => 'прѣимєнова́ниѥ',
'pagemovedsub'     => 'прѣимєнова́ниѥ сътворѥно́ ѥ́стъ',
'movepage-moved'   => "'''⁖ $1 ⁖ нарєчє́нъ ⁖ $2⁖ ѥ́стъ'''",
'movedto'          => 'прѣимєновано въ',
'movetalk'         => 'си страни́цѧ бєсѣ́дꙑ прѣимєнова́ниѥ',
'1movedto2'        => '⁖ [[$1]] ⁖ нарєчє́нъ ⁖ [[$2]] ⁖ ѥ́стъ',
'1movedto2_redir'  => '[[$1]] нарєчє́нъ [[$2]] врьхоу́ прѣнаправлѥ́ниꙗ ѥ́стъ.',
'movelogpage'      => 'прѣимєнова́ниꙗ їсторі́ꙗ',

# Namespace 8 related
'allmessages'     => 'сѷсти́мьнꙑ напьса́ниꙗ',
'allmessagesname' => 'и́мѧ',

# Tooltip help for the actions
'tooltip-pt-userpage'            => 'твоꙗ́ по́льꙃєватєл҄ьска страни́ца',
'tooltip-pt-mytalk'              => 'твоꙗ́ бєсѣ́дꙑ страни́ца',
'tooltip-pt-preferences'         => 'твоꙗ́ строи',
'tooltip-pt-mycontris'           => 'твоꙗ́ добродѣꙗ́ниѩ ката́логъ',
'tooltip-pt-logout'              => 'ис̾хо́дъ',
'tooltip-ca-viewsource'          => 'си страни́ца ꙁабранєна́ ѥ́стъ ⁙
ѥѩ исто́чьнъ о́браꙁъ ви́дєти мо́жєщи',
'tooltip-ca-protect'             => 'си страни́цѧ ꙁабранє́ниѥ',
'tooltip-ca-delete'              => 'си страни́цѧ поничьжє́ниѥ',
'tooltip-ca-move'                => 'си страни́цѧ прѣимєнова́ниѥ',
'tooltip-ca-watch'               => 'си страни́цѧ блюдє́ниѥ',
'tooltip-p-logo'                 => 'гла́вьна страни́ца',
'tooltip-n-mainpage'             => 'ви́ждь гла́вьнѫ страни́цѫ',
'tooltip-n-mainpage-description' => 'ви́ждь гла́вьнѫ страни́цѫ',
'tooltip-n-recentchanges'        => 'послѣ́дьнъ мѣ́нъ ката́логъ',
'tooltip-t-upload'               => 'положє́ниѥ дѣ́лъ',
'tooltip-t-specialpages'         => 'вьсѣѩ наро́чьнъ страни́цѧ ката́логъ',
'tooltip-watch'                  => 'си страни́цѧ блюдє́ниѥ',

# Media information
'file-info-size' => '($1 × $2 п҃ѯ · дѣ́ла мѣ́ра : $3 · MIME тѵ́пъ : $4)',
'svg-long-desc'  => '(дѣ́ло SVG · обꙑ́чьнъ о́браꙁъ : $1 × $2 п҃ѯ · дѣ́ла мѣ́ра : $3)',
'show-big-image' => 'пль́нъ ви́да о́браꙁъ',

# Special:NewFiles
'ilsubmit' => 'ищи́',

# EXIF tags
'exif-artist' => 'творь́ць',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'вьсꙗ́',
'namespacesall' => 'вьсꙗ́',
'monthsall'     => 'вьсѩ́',

'unit-pixel' => 'п҃ѯ',

# Multipage image navigation
'imgmultigo' => 'прѣиди́',

# Table pager
'table_pager_limit_submit' => 'прѣиди́',

# Auto-summaries
'autosumm-new' => 'но́ва страни́ца сътворєна́  мѣ́роѭ $1 ѥ́стъ',

# Size units
'size-bytes' => '$1 Б҃',

# Special:Version
'version'                  => 'MediaWiki о́браꙁъ',
'version-version'          => '(о́браꙁъ $1)',
'version-license'          => 'прощє́ниѥ',
'version-software-version' => 'о́браꙁъ',

# Special:FilePath
'filepath-page' => 'дѣ́ло :',

# Special:FileDuplicateSearch
'fileduplicatesearch-submit' => 'ищи́',

# Special:SpecialPages
'specialpages' => 'наро́чьнꙑ страни́цѧ',

# Special:Tags
'tags-edit' => 'испра́ви',

);
