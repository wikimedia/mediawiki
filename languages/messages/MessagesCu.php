<?php
/** Church Slavic (Словѣ́ньскъ / ⰔⰎⰑⰂⰡⰐⰠⰔⰍⰟ)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Krinkle
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
'sunday'        => 'нєдѣлꙗ',
'monday'        => 'понедѣл҄ьникъ',
'tuesday'       => 'въторьникъ',
'wednesday'     => 'срѣда',
'thursday'      => 'чєтврьтъкъ',
'friday'        => 'пѧтъкъ',
'saturday'      => 'сѫбота',
'sun'           => 'н҃д',
'mon'           => 'п҃н',
'tue'           => 'в҃т',
'wed'           => 'с҃р',
'thu'           => 'ч҃т',
'fri'           => 'п҃т',
'sat'           => 'с҃б',
'january'       => 'їаноуарїи',
'february'      => 'фєвроуарїи',
'march'         => 'мартїи',
'april'         => 'апрїлїи',
'may_long'      => 'маїи',
'june'          => 'їоунїи',
'july'          => 'їоулїи',
'august'        => 'аѷгоустъ',
'september'     => 'сєптємврїи',
'october'       => 'октѡврїи',
'november'      => 'ноємврїи',
'december'      => 'дєкємврїи',
'january-gen'   => 'їаноуарїꙗ',
'february-gen'  => 'фєвроуарїꙗ',
'march-gen'     => 'мартїꙗ',
'april-gen'     => 'апрїлїꙗ',
'may-gen'       => 'маїꙗ',
'june-gen'      => 'їоунїꙗ',
'july-gen'      => 'їоулїꙗ',
'august-gen'    => 'аѷгоуста',
'september-gen' => 'сєптємврїꙗ',
'october-gen'   => 'октѡврїꙗ',
'november-gen'  => 'ноємврїꙗ',
'december-gen'  => 'дєкємврїꙗ',
'jan'           => 'ꙗ҃н',
'feb'           => 'фє҃в',
'mar'           => 'ма҃р',
'apr'           => 'ап҃р',
'may'           => 'маїи',
'jun'           => 'їо҃ун',
'jul'           => 'їо҃ул',
'aug'           => 'аѵ҃г',
'sep'           => 'сє҃п',
'oct'           => 'ок҃т',
'nov'           => 'но҃є',
'dec'           => 'дє҃к',

# Categories related messages
'pagecategories'         => '{{PLURAL:$1|Катигорі́ꙗ|Катигорі́и|Катигорі́ѩ|Катигорі́ѩ}}',
'category_header'        => 'катигорїѩ ⁖ $1 ⁖ страницѧ',
'subcategories'          => 'подъкатигорїѩ',
'listingcontinuesabbrev' => '· вѧщє',

'linkprefix' => '/^(.*?)(„|«)$/sD',

'about'         => 'опьсаниѥ',
'article'       => 'члѣнъ',
'moredotdotdot' => 'вѧщє ···',
'mypage'        => 'моꙗ страница',
'mytalk'        => 'моꙗ бєсѣда',
'navigation'    => 'плаваниѥ',
'and'           => '&#32;и',

# Cologne Blue skin
'qbedit'         => 'исправи',
'qbpageoptions'  => 'си страни́ца',
'qbmyoptions'    => 'моꙗ страницѧ',
'qbspecialpages' => 'нарочьнꙑ страницѧ',

# Vector skin
'vector-action-delete'    => 'поничьжєниѥ',
'vector-action-move'      => 'прѣимєнованиѥ',
'vector-action-protect'   => 'ꙁабранѥниѥ',
'vector-action-unprotect' => 'поущєниѥ',
'vector-view-create'      => 'сътворѥниѥ',
'vector-view-edit'        => 'исправи',
'vector-view-history'     => 'їсторїꙗ',
'vector-view-view'        => 'чьтѥниѥ',
'vector-view-viewsource'  => 'страницѧ источьнъ обраꙁъ',
'actions'                 => 'дѣиства',
'namespaces'              => 'имєнъ просторꙑ',

'errorpagetitle'   => 'блаꙁна',
'tagline'          => '{{grammar:genitive|{{SITENAME}}}} страница',
'help'             => 'помощь',
'search'           => 'исканиѥ',
'searchbutton'     => 'ищи',
'go'               => 'прѣиди',
'searcharticle'    => 'прѣиди',
'history'          => 'страницѧ їсторїꙗ',
'history_short'    => 'їсторїꙗ',
'printableversion' => 'пєчатьнъ обраꙁъ',
'permalink'        => 'въиньна съвѧꙁь',
'print'            => 'пєчатаниѥ',
'edit'             => 'исправи',
'create'           => 'сътворѥниѥ',
'editthispage'     => 'си страницѧ исправлѥниѥ',
'create-this-page' => 'си страницѧ сътворѥниѥ',
'delete'           => 'поничьжєниѥ',
'deletethispage'   => 'си страницѧ поничьжєниѥ',
'protect'          => 'ꙁабранѥниѥ',
'protectthispage'  => 'си страницѧ ꙁабранєниѥ',
'unprotect'        => 'поущєниѥ',
'newpage'          => 'нова страница',
'talkpage'         => 'си страницѧ бєсѣда',
'talkpagelinktext' => 'бєсѣда',
'specialpage'      => 'нарочьна страница',
'personaltools'    => 'моꙗ орѫдиꙗ',
'talk'             => 'бєсѣда',
'toolbox'          => 'орѫдиꙗ',
'otherlanguages'   => 'дроугꙑ ѩꙁꙑкꙑ',
'redirectedfrom'   => '(прѣнаправлѥниѥ отъ ⁖ $1 ⁖)',
'redirectpagesub'  => 'прѣнаправлѥниѥ',
'lastmodifiedat'   => 'страницѧ послѣдьнꙗ мѣна сътворѥна $2 · $1 бѣ ⁙',
'jumpto'           => 'прѣиди къ :',
'jumptonavigation' => 'плаваниѥ',
'jumptosearch'     => 'исканиѥ',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'О {{grammar:instrumental|{{SITENAME}}}}',
'aboutpage'            => 'Project:О сѥмь опꙑтьствовании',
'copyright'            => 'подъ прощєниѥмь $1 пьсано ѥстъ',
'copyrightpage'        => '{{ns:project}}:Творьцъ права',
'currentevents'        => 'сѫщѧѩ вѣщи',
'currentevents-url'    => 'Project:Сѫщѧѩ вѣщи',
'edithelp'             => 'помощь по исправлѥниѭ',
'edithelppage'         => 'Help:Исправлѥниѥ страницѧ',
'helppage'             => 'Help:Каталогъ',
'mainpage'             => 'главьна страница',
'mainpage-description' => 'главьна страница',
'portal'               => 'обьщєниꙗ съвѣтъ',
'portal-url'           => 'Project:Обьщєниꙗ съвѣтъ',

'youhavenewmessages'  => '$1 тєбѣ напьсанꙑ сѫтъ ($2)',
'newmessageslink'     => 'нови напьсаниꙗ',
'newmessagesdifflink' => 'послѣдьнꙗ мѣна',
'editsection'         => 'исправи',
'editold'             => 'исправи',
'viewsourceold'       => 'страницѧ источьнъ обраꙁъ',
'editlink'            => 'исправи',
'viewsourcelink'      => 'страницѧ источьнъ обраꙁъ',
'editsectionhint'     => 'исправлѥниѥ чѧсти : $1',
'toc'                 => 'каталогъ',
'showtoc'             => 'виждь',
'hidetoc'             => 'съкрꙑи',
'viewdeleted'         => '$1 видєти хощєши ;',
'red-link-title'      => '$1 (си страницѧ нѣстъ)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'члѣнъ',
'nstab-user'      => 'польꙃєватєл҄ь',
'nstab-media'     => 'срѣдьства',
'nstab-special'   => 'нарочьна',
'nstab-project'   => 'съвѣтъ',
'nstab-image'     => 'дѣло',
'nstab-mediawiki' => 'напьсаниѥ',
'nstab-template'  => 'обраꙁьць',
'nstab-help'      => 'страница помощи',
'nstab-category'  => 'катигорїꙗ',

# General errors
'error'         => 'блаꙁна',
'viewsource'    => 'страницѧ источьнъ обраꙁъ',
'viewsourcefor' => '$1 дѣлꙗ',

# Login and logout pages
'yourname'                => 'твоѥ имѧ',
'yourpassword'            => 'таино слово напиши',
'yourpasswordagain'       => 'опакꙑ таиноѥ слово напиши',
'login'                   => 'въниди',
'nav-login-createaccount' => 'въниди / съꙁижди си мѣсто',
'userlogin'               => 'въниди / съꙁижди си мѣсто',
'userloginnocreate'       => 'въниди',
'logout'                  => 'ис̾ходъ',
'userlogout'              => 'ис̾ходъ',
'nologinlink'             => 'съꙁижди си мѣсто',
'createaccount'           => 'съꙁижди си мѣсто',
'gotaccount'              => "Мѣсто ти ѥстъ ли? '''$1'''.",
'gotaccountlink'          => 'Въниди',
'userexists'              => 'сѫщє польꙃєватєлꙗ имѧ пьса ⁙ ино иꙁобрѧщи',
'loginerror'              => 'въхода блаꙁна',
'accountcreated'          => 'мѣсто сътворєно ѥстъ',
'loginlanguagelabel'      => 'ѩꙁꙑкъ : $1',

# Change password dialog
'resetpass'                 => 'таина словєсє иꙁмѣнѥниѥ',
'oldpassword'               => 'старо таино слово :',
'newpassword'               => 'ново таино слово :',
'resetpass-submit-loggedin' => 'таина словєсє иꙁмѣнѥниѥ',

# Special:PasswordReset
'passwordreset-username' => 'польꙃєватєлꙗ имѧ :',

# Edit page toolbar
'link_sample'    => 'съвѧꙁи имѧ',
'extlink_sample' => 'http://www.example.com съвѧꙁи имѧ',
'sig_tip'        => 'твои аѵтографъ и нꙑнѣшьна врѣмѧ и дьнь',

# Edit pages
'summary'                    => 'опьсаниѥ :',
'minoredit'                  => 'малаꙗ мѣна',
'watchthis'                  => 'си страницѧ блюдєниѥ',
'savearticle'                => 'съхранѥниѥ',
'showpreview'                => 'мѣнꙑ поꙁьрѣниѥ (бєꙁ съхранѥниꙗ)',
'loginreqlink'               => 'въниди',
'newarticle'                 => '(новъ)',
'noarticletext'              => 'нꙑнѣ с̑ьдє ничєсожє нє напьсано ѥстъ ⁙
[[Special:Search/{{PAGENAME}}|си страницѧ имѧ искати]] дроугꙑ страницѧ ·
<span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} съвѧꙁанꙑ їсторїѩ видѣти] ·
или [{{fullurl:{{FULLPAGENAME}}|action=edit}} ѭжє исправити]</span> можєши',
'noarticletext-nopermission' => 'нꙑнѣ с̑ьдє ничєсожє нє напьсано ѥстъ ⁙
[[Special:Search/{{PAGENAME}}|си страницѧ имѧ искати]] дроугꙑ страницѧ или
<span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} съвѧꙁанꙑ їсторїѩ видѣти]</span> можєши',
'clearyourcache'             => 'НАРОЧИТО: По съхранѥнии можєши обити своѥго съмотрила съхранъ да видѣлъ би мѣнꙑ ⁙ Mozilla ли Firefox ли Safari ли жьмꙑи Shift а мꙑшиѭ жьми Reload или жьми Ctrl-Shift-R (Cmd-Shift-R вън Apple Mac)  ⁙ Konqueror ли жьми кромѣ Reload или F5 ⁙ Опєрꙑ польꙃєватєльмъ можєть бꙑти ноужда пльнѣ поничьжити ихъ съмотрила съхранъ въ Tools > Preferences ⁙ IE ли жьмꙑи Ctrl а мꙑшиѭ жьми Refresh или жьми Ctrl-F5',
'note'                       => "'''НАРОЧИТО:'''",
'editing'                    => 'исправлѥниѥ: $1',
'editingsection'             => 'исправлѥниѥ ⁖ $1 ⁖ (чѧсть)',
'templatesused'              => 'сѥѩ страницѧ {{PLURAL:$1|сь обраꙁьць польꙃоуѥтъ сѧ ѥстъ|с҄и обраꙁьца польꙃоуѭтъ сѧ ѥстє|с҄и обраꙁьци польꙃоуѭтъ сѧ сѫтъ}} :',
'template-protected'         => '(ꙁабранєно ѥстъ)',

# History pages
'viewpagelogs' => 'си страницѧ їсторїѩ',
'cur'          => 'нꙑ҃н',
'last'         => 'пс҃лд',
'page_first'   => 'прьва страница',
'page_last'    => 'послѣдьнꙗ страница',
'histlast'     => 'послѣдьнꙗ',
'historyempty' => '(поусто)',

# Revision feed
'history-feed-title'          => 'мѣнъ їсторїꙗ',
'history-feed-item-nocomment' => '$1 при $2',

# Revision deletion
'rev-delundel'               => 'каꙁаниѥ / съкрꙑ́тиѥ',
'rev-showdeleted'            => 'ви́ждь',
'revdelete-show-file-submit' => 'да',
'revdelete-radio-set'        => 'да',
'revdelete-radio-unset'      => 'нѣ́тъ',
'revdelete-uname'            => 'по́льꙃєватєлꙗ и́мѧ',

# Search results
'searchresults'                  => 'исканиꙗ слѣдьствиѥ',
'searchresults-title'            => 'иска́ниꙗ ⁖ $1 ⁖ слѣдьствиѥ',
'viewprevnext'                   => 'виждь ($1 {{int:pipe-separator}} $2) ($3)',
'searchmenu-new'                 => "'''страни́цѫ ⁖ [[:$1]] ⁖ сътвори́ти мо́жєши'''",
'searchhelp-url'                 => 'Help:Ката́логъ',
'searchprofile-articles'         => 'члѣ́ни',
'searchprofile-images'           => 'дѣла́',
'searchprofile-everything'       => 'вьсѩ́ страни́цѧ',
'searchprofile-articles-tooltip' => 'ищи въ $1',
'search-result-size'             => '$1 ({{PLURAL:$2|$2 сло́во|$2 сло́ва|$2 словє́съ}})',
'search-redirect'                => '(прѣнаправлє́ниѥ $1)',
'search-section'                 => '(чѧ́сть $1)',
'search-interwiki-caption'       => 'ро́дьствьна опꙑтьствова́ниꙗ',
'search-interwiki-more'          => '(вѧ́щє)',
'searchall'                      => 'вьсꙗ́',
'powersearch'                    => 'ищи́',

# Preferences page
'preferences'         => 'строи',
'mypreferences'       => 'мои строи',
'changepassword'      => 'таина словєсє иꙁмѣнѥ́ниѥ',
'prefs-rc'            => 'послѣ́дьнѩ мѣ́нꙑ',
'prefs-watchlist'     => 'блюдє́ниꙗ',
'prefs-resetpass'     => 'таина словєсє иꙁмѣнѥ́ниѥ',
'searchresultshead'   => 'исканиѥ',
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
'nchanges'                    => '$1 {{PLURAL:$1|мѣ́на|мѣ́нꙑ|мѣ́нъ}}',
'recentchanges'               => 'послѣдьнѩ мѣнꙑ',
'recentchanges-legend'        => 'послѣдьн҄ь мѣнъ строи',
'recentchangestext'           => 'с҄ьдє послѣдьнѩ мѣнꙑ сѥѩ викиопꙑтьствованиꙗ видѣти можєши',
'recentchanges-label-newpage' => 'по сѥи мѣнꙑ нова страница сътворѥна ѥстъ',
'recentchanges-label-minor'   => 'малаꙗ мѣна',
'recentchanges-label-bot'     => 'сѭ мѣноу аѵтоматъ сътворихъ',
'rcnote'                      => "нижѣ {{PLURAL:$1|'''1''' послѣ́дьнѭ мѣ́нѫ|'''$1''' послѣ́дьни мѣ́нꙑ|'''$1''' послѣ́дьнъ мѣ́нъ|'''$1''' послѣ́дьнъ мѣ́нъ}} ꙁа {{PLURAL:$2|дьнь|'''$2''' дьнꙗ|'''$2''' дьнъ|'''$2''' дьнъ}} · ꙗко нꙑнѣ $5 · $4 лѣ́та",
'rcshowhideminor'             => '$1 ма́лꙑ мѣ́нꙑ',
'rcshowhidebots'              => '$1 аѵтома́тъ',
'rcshowhideliu'               => '$1 по́льꙃєватєлъ · ѩжє съꙁижьдє сѥ мѣ́сто · мѣ́нꙑ',
'rcshowhideanons'             => '$1 анѡнѷмьнъ по́льꙃєватєлъ мѣ́нꙑ',
'rcshowhidemine'              => '$1 моꙗ́ мѣ́нꙑ',
'rclinks'                     => '$1 послѣдьн҄ь  мѣнъ · ѩжє $2 послѣдьни дьни створѥнꙑ сѫтъ · каꙁаниѥ<br />$3',
'diff'                        => 'ра҃ꙁн',
'hist'                        => 'їс҃т',
'hide'                        => 'съкрꙑи',
'show'                        => 'виждь',
'minoreditletter'             => 'м҃л',
'newpageletter'               => 'н҃в',
'boteditletter'               => 'а҃ѵ',

# Recent changes linked
'recentchangeslinked'         => 'съвѧꙁанꙑ страницѧ',
'recentchangeslinked-feed'    => 'съвѧꙁанꙑ страницѧ',
'recentchangeslinked-toolbox' => 'съвѧꙁанꙑ страницѧ',
'recentchangeslinked-page'    => 'страни́цѧ и́мѧ :',

# Upload
'upload'              => 'положєниѥ дѣла',
'uploadbtn'           => 'положєниѥ дѣла',
'uploadlog'           => 'дѣ́лъ положє́ниꙗ їсторі́ꙗ',
'uploadlogpage'       => 'дѣ́лъ положє́ниꙗ їсторі́ꙗ',
'filename'            => 'дѣ́ла и́мѧ',
'filedesc'            => 'опьса́ниѥ',
'fileuploadsummary'   => 'опьса́ниѥ:',
'uploadedimage'       => '⁖ [[$1]] ⁖ положє́нъ ѥ́стъ',
'watchthisupload'     => 'си дѣ́ла блюдє́ниѥ',
'upload-success-subj' => 'дѣ́ло положєно ѥ́стъ',

'license'        => 'прощєниѥ :',
'license-header' => 'прощєниѥ',

# Special:ListFiles
'imgfile'        => 'дѣ́ло',
'listfiles'      => 'дѣ́лъ ката́логъ',
'listfiles_name' => 'и́мѧ',
'listfiles_user' => 'по́льꙃєватєл҄ь',
'listfiles_size' => 'мѣ́ра',

# File description page
'file-anchor-link'   => 'ви́дъ',
'filehist'           => 'дѣ́ла їсторі́ꙗ',
'filehist-deleteone' => 'поничьжє́ниѥ',
'filehist-current'   => 'нꙑнѣщьн҄ь о́браꙁъ',
'filehist-datetime'  => 'дьнь / врѣ́мѧ',
'filehist-user'      => 'по́льꙃєватєл҄ь',
'imagelinks'         => 'дѣ́ла съвѧ́ꙁи',
'sharedupload'       => 'сѥ дѣ́ло въ $1 съхранѥ́но ѥ́стъ дѣ́ла · ѥгожє дроугꙑ́ опꙑтьствова́ниѩ по́льꙃєвати мо́гѫтъ',

# File deletion
'filedelete-submit' => 'поничьжє́ниѥ',

# MIME search
'mimetype' => 'MIME тѵ́пъ :',
'download' => 'поѩ́ти',

# List redirects
'listredirects' => 'прѣнаправлѥ́ниꙗ',

# Random page
'randompage' => 'страница въ нєꙁаапѫ',

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

'brokenredirects-edit'   => 'исправи',
'brokenredirects-delete' => 'поничьжє́ниѥ',

# Miscellaneous special pages
'nbytes'            => '$1 {{PLURAL:$1|ба́итъ|ба́ита|ба́итъ}}',
'nlinks'            => '$1 {{PLURAL:$1|съвѧ́ꙁь|съвѧ́ꙁѧ|съвѧ́ꙁи}}',
'shortpages'        => 'кратъкꙑ страни́цѧ',
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
'linksearch'    => 'вънѣщьнѩ съвѧ́ꙁи',
'linksearch-ok' => 'ищи́',

# Special:ListUsers
'listusers-submit' => 'виждь',

# Special:Log/newusers
'newuserlogpage'              => 'но́въ мѣ́стъ сътворѥ́ниꙗ їсторі́ꙗ',
'newuserlog-create-entry'     => 'но́въ по́льꙃєватєл҄ь',
'newuserlog-autocreate-entry' => 'по́льꙃєватєлꙗ мѣ́сто аѵтомати́чьно сътворєно́ ѥ́стъ',

# E-mail user
'emailuser' => 'посъли єпїстолѫ',

# Watchlist
'watchlist'        => 'моꙗ блюдєниꙗ',
'mywatchlist'      => 'моꙗ блюдєниꙗ',
'addedwatch'       => 'страни́ца нꙑнѣ по́дъ твоимь блюдє́ниѥмь ѥ́стъ',
'addedwatchtext'   => "страни́ца ⁖ [[:$1]] ⁖ нꙑнѣ по́дъ твоимь [[Special:Watchlist|блюдє́ниѥмь]] ѥ́стъ ⁙
всꙗ ѥѩ и ѥѩжє бєсѣдꙑ мѣ́нꙑ страни́цѧ ⁖ [[Special:Watchlist|моꙗ́ блюдє́ниꙗ]] ⁖ покаꙁанꙑ сѫ́тъ и  [[Special:RecentChanges|послѣ́дьнъ мѣ́нъ]] ката́лоꙃѣ '''чрьнꙑимъ''' сѧ авлꙗѭтъ",
'removedwatchtext' => 'страни́ца ⁖ [[:$1]] ⁖ нꙑнѣ твоѥго [[Special:Watchlist|блюдє́ниꙗ]] иꙁнєсєна ѥ́стъ',
'watch'            => 'блюдє́ниѥ',
'watchthispage'    => 'си страни́цѧ блюдє́ниѥ',
'unwatch'          => 'оста́ви блюдє́ниѥ',

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
'restriction-edit'   => 'исправи',
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
'mycontris'           => 'моꙗ добродѣꙗниꙗ',
'contribsub2'         => 'по́льꙃєватєлꙗ и́мѧ ⁖ $1 ⁖ ѥ́стъ ($2)',
'uctop'               => '(послѣ́дьнꙗ мѣ́на)',

'sp-contributions-blocklog' => 'ꙁаграждє́ниꙗ їсторі́ꙗ',
'sp-contributions-deleted'  => 'пони́чьжєнꙑ добродѣꙗ́ниꙗ',
'sp-contributions-talk'     => 'бєсѣ́да',
'sp-contributions-username' => 'IP число или по́льꙃєватєлꙗ и́мѧ :',
'sp-contributions-submit'   => 'ищи́',

# What links here
'whatlinkshere'            => 'дос̑ьдєщьнѩ съвѧꙁи',
'whatlinkshere-title'      => 'страни́цѧ ижє съ ⁖ $1 ⁖ съвѧ́ꙁи имѫтъ',
'whatlinkshere-page'       => 'страни́ца :',
'isredirect'               => 'прѣнаправлѥ́ниѥ',
'whatlinkshere-links'      => '← съвѧ́ꙁи',
'whatlinkshere-hideredirs' => '$1 прѣнаправлѥ́ниꙗ',
'whatlinkshere-hidelinks'  => '$1 съвѧ́ꙁи',

# Block/unblock
'blockip'            => 'ꙁагради́ по́льꙃєватєл҄ь',
'ipadressorusername' => 'IP число или по́льꙃєватєлꙗ и́мѧ :',
'ipblocklist-submit' => 'исканиѥ',
'blocklink'          => 'ꙁагради́',
'contribslink'       => 'добродѣꙗ́ниꙗ',
'blocklogpage'       => 'ꙁаграждє́ниꙗ їсторі́ꙗ',

# Move page
'move-page'               => 'прѣимєнова́ниѥ ⁖ $1 ⁖',
'move-page-legend'        => 'страни́цѧ прѣимєнова́ниѥ',
'movearticle'             => 'страни́ца :',
'newtitle'                => 'но́во и́мѧ :',
'move-watch'              => 'си страни́цѧ блюдє́ниѥ',
'movepagebtn'             => 'прѣимєнова́ниѥ',
'pagemovedsub'            => 'прѣимєнова́ниѥ сътворѥно́ ѥ́стъ',
'movepage-moved'          => "'''⁖ $1 ⁖ нарєчє́нъ ⁖ $2⁖ ѥ́стъ'''",
'movepage-moved-redirect' => 'прѣнаправлѥ́ниѥ сътворѥно́ бѣ',
'movedto'                 => 'прѣимєновано въ',
'movetalk'                => 'си страни́цѧ бєсѣ́дꙑ прѣимєнова́ниѥ',
'1movedto2'               => '⁖ [[$1]] ⁖ нарєчє́нъ ⁖ [[$2]] ⁖ ѥ́стъ',
'1movedto2_redir'         => '[[$1]] нарєчє́нъ [[$2]] врьхоу́ прѣнаправлѥ́ниꙗ ѥ́стъ.',
'movelogpage'             => 'прѣимєнова́ниꙗ їсторі́ꙗ',

# Namespace 8 related
'allmessages'     => 'сѷсти́мьнꙑ напьса́ниꙗ',
'allmessagesname' => 'и́мѧ',

# Tooltip help for the actions
'tooltip-pt-userpage'            => 'твоꙗ́ по́льꙃєватєл҄ьска страни́ца',
'tooltip-pt-mytalk'              => 'твоꙗ́ бєсѣ́дꙑ страни́ца',
'tooltip-pt-preferences'         => 'твои строи',
'tooltip-pt-mycontris'           => 'твоꙗ́ добродѣꙗ́ниѩ ката́логъ',
'tooltip-pt-logout'              => 'ис̾хо́дъ',
'tooltip-ca-talk'                => 'си страни́цѧ бєсѣ́да',
'tooltip-ca-viewsource'          => 'си страни́ца ꙁабранєна́ ѥ́стъ ⁙
ѥѩ исто́чьнъ о́браꙁъ ви́дєти мо́жєщи',
'tooltip-ca-protect'             => 'си страни́цѧ ꙁабранє́ниѥ',
'tooltip-ca-delete'              => 'си страни́цѧ поничьжє́ниѥ',
'tooltip-ca-move'                => 'си страни́цѧ прѣимєнова́ниѥ',
'tooltip-ca-watch'               => 'си страни́цѧ блюдє́ниѥ',
'tooltip-search'                 => 'ищи {{{grammar:genitive|{{SITENAME}}}}} страни́цѧ',
'tooltip-p-logo'                 => 'гла́вьна страни́ца',
'tooltip-n-mainpage'             => 'ви́ждь гла́вьнѫ страни́цѫ',
'tooltip-n-mainpage-description' => 'ви́ждь гла́вьнѫ страни́цѫ',
'tooltip-n-recentchanges'        => 'послѣ́дьн҄ь мѣ́нъ ката́логъ',
'tooltip-t-upload'               => 'положє́ниѥ дѣ́лъ',
'tooltip-t-specialpages'         => 'вьсѣѩ наро́чьнъ страни́цѧ ката́логъ',
'tooltip-t-print'                => 'сѥѩ страни́цѧ пєча́тьнъ о́браꙁъ',
'tooltip-ca-nstab-special'       => 'си наро́чьна страни́ца ѥ́стъ · ѥѩжє иꙁмѣнꙗ́ти нє можєши',
'tooltip-watch'                  => 'си страни́цѧ блюдє́ниѥ',

# Media information
'file-info-size' => '$1 × $2 п҃ѯ · дѣ́ла мѣ́ра : $3 · MIME тѵ́пъ : $4',
'svg-long-desc'  => 'дѣ́ло SVG · обꙑ́чьнъ о́браꙁъ : $1 × $2 п҃ѯ · дѣ́ла мѣ́ра : $3',
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
'specialpages' => 'нарочьнꙑ страницѧ',

# Special:Tags
'tags-edit' => 'исправи',

);
