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
	NS_MEDIA            => 'Срѣдьства',
	NS_SPECIAL          => 'Нарочьна',
	NS_TALK             => 'Бєсѣда',
	NS_USER             => 'Польꙃєватєл҄ь',
	NS_USER_TALK        => 'Польꙃєватєлꙗ_бєсѣда',
	NS_PROJECT_TALK     => '{{GRAMMAR:genitive|$1}}_бєсѣда',
	NS_FILE             => 'Дѣло',
	NS_FILE_TALK        => 'Дѣла_бєсѣда',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki_бєсѣда',
	NS_TEMPLATE         => 'Обраꙁьць',
	NS_TEMPLATE_TALK    => 'Обраꙁьца_бєсѣда',
	NS_HELP             => 'Помощь',
	NS_HELP_TALK        => 'Помощи_бєсѣда',
	NS_CATEGORY         => 'Катигорїꙗ',
	NS_CATEGORY_TALK    => 'Катигорїѩ_бєсѣда',
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
'pagecategories'         => '{{PLURAL:$1|Катигорїꙗ|Катигорїи|Катигорїѩ|Катигорїѩ}}',
'category_header'        => 'катигорїѩ ⁖ $1 ⁖ страницѧ',
'subcategories'          => 'подъкатигорїѩ',
'hidden-categories'      => '{{PLURAL:$1|съкрꙑта катигорїꙗ|съкрꙑти катигорїи|съкрꙑтꙑ катигорїѩ}}',
'category-subcat-count'  => '{{PLURAL:$2|Сѥи катигорїи тъкъмо сꙗ подъкатигорїꙗ ѥстъ|Сѥи катигорїи {{PLURAL:$1|ѥдина подъкатигорїꙗ ѥстъ|2 подъкатигорїи ѥстє|$1 подъкатигорїѩ сѫтъ}} · а вьсѩ жє подъкатигорїѩ число $2 ѥстъ}}',
'listingcontinuesabbrev' => '· вѧщє',

'linkprefix' => '/^(.*?)(„|«)$/sD',

'about'         => 'опьсаниѥ',
'article'       => 'члѣнъ',
'newwindow'     => '(иномь окънѣ)',
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

'errorpagetitle'    => 'блаꙁна',
'tagline'           => '{{grammar:genitive|{{SITENAME}}}} страница',
'help'              => 'помощь',
'search'            => 'исканиѥ',
'searchbutton'      => 'ищи',
'go'                => 'прѣиди',
'searcharticle'     => 'прѣиди',
'history'           => 'страницѧ їсторїꙗ',
'history_short'     => 'їсторїꙗ',
'printableversion'  => 'пєчатьнъ обраꙁъ',
'permalink'         => 'въиньна съвѧꙁь',
'print'             => 'пєчатаниѥ',
'edit'              => 'исправи',
'create'            => 'сътворѥниѥ',
'editthispage'      => 'си страницѧ исправлѥниѥ',
'create-this-page'  => 'си страницѧ сътворѥниѥ',
'delete'            => 'поничьжєниѥ',
'deletethispage'    => 'си страницѧ поничьжєниѥ',
'protect'           => 'ꙁабранѥниѥ',
'protect_change'    => 'иꙁмѣнѥниѥ',
'protectthispage'   => 'си страницѧ ꙁабранєниѥ',
'unprotect'         => 'поущєниѥ',
'newpage'           => 'нова страница',
'talkpage'          => 'си страницѧ бєсѣда',
'talkpagelinktext'  => 'бєсѣда',
'specialpage'       => 'нарочьна страница',
'personaltools'     => 'моꙗ орѫдиꙗ',
'postcomment'       => 'нова чѧсть',
'talk'              => 'бєсѣда',
'toolbox'           => 'орѫдиꙗ',
'otherlanguages'    => 'дроугꙑ ѩꙁꙑкꙑ',
'redirectedfrom'    => '(прѣнаправлѥниѥ отъ ⁖ $1 ⁖)',
'redirectpagesub'   => 'прѣнаправлѥниѥ',
'lastmodifiedat'    => 'страницѧ послѣдьнꙗ мѣна сътворѥна $2 · $1 бѣ ⁙',
'jumpto'            => 'прѣиди къ :',
'jumptonavigation'  => 'плаваниѥ',
'jumptosearch'      => 'исканиѥ',
'pool-errorunknown' => 'нєвѣдома блаꙁна',

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
'policy-url'           => 'Project:Полїтїка',
'portal'               => 'обьщєниꙗ съвѣтъ',
'portal-url'           => 'Project:Обьщєниꙗ съвѣтъ',

'pagetitle'           => '$1 · {{SITENAME}}',
'retrievedfrom'       => 'поѩто иꙁ ⁖ $1 ⁖',
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
'nologin'                 => 'мѣсто ти нѣстъ ли ? $1',
'nologinlink'             => 'съꙁижди си мѣсто',
'createaccount'           => 'съꙁижди си мѣсто',
'gotaccount'              => 'мѣсто ти ѥстъ ли? $1',
'gotaccountlink'          => 'въниди',
'createaccountreason'     => 'какъ съмꙑслъ :',
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
'link_tip'       => 'вънѫтрьнꙗ съвѧꙁь',
'extlink_sample' => 'http://www.example.com съвѧꙁи имѧ',
'extlink_tip'    => 'вънѣщьнꙗ съвѧꙁь (помьни о http://)',
'sig_tip'        => 'твои аѵтографъ и нꙑнѣшьна врѣмѧ и дьнь',

# Edit pages
'summary'                    => 'опьсаниѥ :',
'minoredit'                  => 'малаꙗ мѣна',
'watchthis'                  => 'си страницѧ блюдєниѥ',
'savearticle'                => 'съхранѥниѥ',
'showpreview'                => 'мѣнꙑ поꙁьрѣниѥ (бєꙁ съхранѥниꙗ)',
'blockedtitle'               => 'польꙃєватєл҄ь ꙁаграждєнъ ѥстъ',
'loginreqlink'               => 'въниди',
'newarticle'                 => '(новъ)',
'noarticletext'              => 'нꙑнѣ с̑ьдє ничєсожє нє напьсано ѥстъ ⁙
[[Special:Search/{{PAGENAME}}|си страницѧ имѧ искати]] дроугꙑ страницѧ ·
<span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} съвѧꙁанꙑ їсторїѩ видѣти] ·
или [{{fullurl:{{FULLPAGENAME}}|action=edit}} ѭжє исправити]</span> можєши',
'noarticletext-nopermission' => 'нꙑнѣ с̑ьдє ничєсожє нє напьсано ѥстъ ⁙
[[Special:Search/{{PAGENAME}}|си страницѧ имѧ искати]] дроугꙑ страницѧ или
<span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} съвѧꙁанꙑ їсторїѩ видѣти]</span> можєши',
'userpage-userdoesnotexist'  => 'польꙃєватєльска мѣста ⁖ $1 ⁖ нꙑнѣ нѣстъ ⁙
прѣдъ сътворѥниѥмь или исправлѥниѥмь си страницѧ помꙑсли жє ащє исто тъ дѣиство ноуждьно ли',
'clearyourcache'             => 'НАРОЧИТО: По съхранѥнии можєши обити своѥго съмотрила съхранъ да видѣлъ би мѣнꙑ ⁙ Mozilla ли Firefox ли Safari ли жьмꙑи Shift а мꙑшиѭ жьми Reload или жьми Ctrl-Shift-R (Cmd-Shift-R вън Apple Mac)  ⁙ Konqueror ли жьми кромѣ Reload или F5 ⁙ Опєрꙑ польꙃєватєльмъ можєть бꙑти ноужда пльнѣ поничьжити ихъ съмотрила съхранъ въ Tools > Preferences ⁙ IE ли жьмꙑи Ctrl а мꙑшиѭ жьми Refresh или жьми Ctrl-F5',
'note'                       => "'''НАРОЧИТО:'''",
'editing'                    => 'исправлѥниѥ: $1',
'editingsection'             => 'исправлѥниѥ ⁖ $1 ⁖ (чѧсть)',
'editingcomment'             => 'исправлѥниѥ ⁖ $1 ⁖ (нова чѧсть)',
'templatesused'              => 'сѥѩ страницѧ {{PLURAL:$1|сь обраꙁьць польꙃоуѥтъ сѧ ѥстъ|с҄и обраꙁьца польꙃоуѭтъ сѧ ѥстє|с҄и обраꙁьци польꙃоуѭтъ сѧ сѫтъ}} :',
'template-protected'         => '(ꙁабранєно ѥстъ)',

# History pages
'viewpagelogs'         => 'си страницѧ їсторїѩ',
'cur'                  => 'нꙑ҃н',
'last'                 => 'пс҃лд',
'page_first'           => 'прьва страница',
'page_last'            => 'послѣдьнꙗ страница',
'history-show-deleted' => 'тъкъмо поничьжєнꙑ мѣнꙑ',
'histfirst'            => 'прьвꙑ',
'histlast'             => 'послѣдьнꙗ',
'historysize'          => '{{PLURAL:$1|1 баитъ|$1 баита|$1 баитъ}}',
'historyempty'         => '(поусто)',

# Revision feed
'history-feed-title'          => 'мѣнъ їсторїꙗ',
'history-feed-item-nocomment' => '$1 при $2',

# Revision deletion
'rev-delundel'               => 'каꙁаниѥ / съкрꙑтиѥ',
'rev-showdeleted'            => 'виждь',
'revdelete-show-file-submit' => 'да',
'revdelete-radio-set'        => 'да',
'revdelete-radio-unset'      => 'нѣтъ',
'revdelete-log'              => 'какъ съмꙑслъ :',
'pagehist'                   => 'страницѧ їсторїꙗ',
'deletedhist'                => 'поничьжєна їсторїꙗ',
'revdelete-summary'          => 'мѣнꙑ опьсаниѥ',
'revdelete-uname'            => 'польꙃєватєлꙗ имѧ',
'revdelete-otherreason'      => 'инъ или допльнитєл҄ьнъ съмꙑслъ :',
'revdelete-reasonotherlist'  => 'инъ съмꙑслъ',

# History merging
'mergehistory-reason' => 'какъ съмꙑслъ :',

# Search results
'searchresults'                  => 'исканиꙗ слѣдьствиѥ',
'searchresults-title'            => 'исканиꙗ ⁖ $1 ⁖ слѣдьствиѥ',
'viewprevnext'                   => 'виждь ($1 {{int:pipe-separator}} $2) ($3)',
'searchmenu-new'                 => "'''страницѫ ⁖ [[:$1]] ⁖ сътворити можєши'''",
'searchhelp-url'                 => 'Help:Каталогъ',
'searchprofile-articles'         => 'члѣни',
'searchprofile-project'          => 'опꙑтьствовании и помощи страницѧ',
'searchprofile-images'           => 'дѣла',
'searchprofile-everything'       => 'вьсѩ страницѧ',
'searchprofile-articles-tooltip' => 'ищи въ $1',
'search-result-size'             => '$1 ({{PLURAL:$2|$2 слово|$2 слова|$2 словєсъ}})',
'search-redirect'                => '(прѣнаправлєниѥ $1)',
'search-section'                 => '(чѧсть $1)',
'search-interwiki-caption'       => 'родьствьна опꙑтьствованиꙗ',
'search-interwiki-more'          => '(вѧщє)',
'searchall'                      => 'вьсꙗ',
'powersearch'                    => 'ищи',
'powersearch-redir'              => 'прѣнаправлѥниꙗ',

# Preferences page
'preferences'               => 'строи',
'mypreferences'             => 'мои строи',
'changepassword'            => 'таина словєсє иꙁмѣнѥниѥ',
'prefs-rc'                  => 'послѣдьнѩ мѣнꙑ',
'prefs-watchlist'           => 'блюдєниꙗ',
'prefs-resetpass'           => 'таина словєсє иꙁмѣнѥниѥ',
'saveprefs'                 => 'съхранѥниѥ',
'prefs-editing'             => 'исправлѥниѥ',
'rows'                      => 'рѧдꙑ :',
'searchresultshead'         => 'исканиѥ',
'timezoneregion-africa'     => 'Афрїка',
'timezoneregion-america'    => 'Амєрїка',
'timezoneregion-antarctica' => 'Антарктїка',
'timezoneregion-arctic'     => 'Арктїка',
'timezoneregion-asia'       => 'Асїꙗ',
'timezoneregion-atlantic'   => 'Атлантїчьскъ ѡкєанъ',
'timezoneregion-australia'  => 'Аѵстралїꙗ',
'timezoneregion-europe'     => 'Єѵрѡпа',
'timezoneregion-indian'     => 'Їндїискъ ѡкєанъ',
'timezoneregion-pacific'    => 'Тихꙑи ѡкєанъ',
'prefs-searchoptions'       => 'исканиꙗ строи',
'prefs-namespaces'          => 'имєнъ просторꙑ',
'prefs-files'               => 'дѣла',
'username'                  => 'польꙃєватєлꙗ имѧ :',
'uid'                       => 'польꙃєватєлꙗ число :',
'yourrealname'              => 'истиньно имѧ :',
'yourlanguage'              => 'ѩꙁꙑкъ :',
'yournick'                  => 'аѵтографъ :',
'yourgender'                => 'полъ :',
'gender-male'               => 'мѫжъ',
'gender-female'             => 'жєна',
'prefs-signature'           => 'аѵтографъ',

# User rights
'userrights-reason' => 'какъ съмꙑслъ :',

# Groups
'group'            => 'чинъ :',
'group-user'       => 'польꙃєватєлє',
'group-bot'        => 'аѵтомати',
'group-sysop'      => 'съмотритєлє',
'group-bureaucrat' => 'чинодатєлє',

'group-user-member'       => 'польꙃєватєл҄ь',
'group-bot-member'        => 'аѵтоматъ',
'group-sysop-member'      => 'съмотритєл҄ь',
'group-bureaucrat-member' => 'чинодатєл҄ь',

'grouppage-user'       => '{{ns:project}}:Польꙃєватєлє',
'grouppage-bot'        => '{{ns:project}}:Аѵтомати',
'grouppage-sysop'      => '{{ns:project}}:Съмотритєлє',
'grouppage-bureaucrat' => '{{ns:project}}:Чинодатєлє',

# User rights log
'rightslog' => 'чинодатєльства їсторїꙗ',

# Associated actions - in the sentence "You do not have permission to X"
'action-edit' => 'си страницѧ исправлєниѥ',

# Recent changes
'nchanges'                    => '$1 {{PLURAL:$1|мѣна|мѣнꙑ|мѣнъ}}',
'recentchanges'               => 'послѣдьнѩ мѣнꙑ',
'recentchanges-legend'        => 'послѣдьн҄ь мѣнъ строи',
'recentchangestext'           => 'с҄ьдє послѣдьнѩ мѣнꙑ сѥѩ викиопꙑтьствованиꙗ видѣти можєши',
'recentchanges-label-newpage' => 'по сѥи мѣнꙑ нова страница сътворѥна ѥстъ',
'recentchanges-label-minor'   => 'малаꙗ мѣна',
'recentchanges-label-bot'     => 'сѭ мѣноу аѵтоматъ сътворихъ',
'rcnote'                      => "нижѣ {{PLURAL:$1|'''1''' послѣдьнѭ мѣнѫ|'''$1''' послѣдьни мѣнꙑ|'''$1''' послѣдьнъ мѣнъ|'''$1''' послѣдьнъ мѣнъ}} ꙁа {{PLURAL:$2|дьнь|'''$2''' дьнꙗ|'''$2''' дьнъ|'''$2''' дьнъ}} · ꙗко нꙑнѣ $5 · $4 лѣта",
'rcshowhideminor'             => '$1 малꙑ мѣнꙑ',
'rcshowhidebots'              => '$1 аѵтоматъ',
'rcshowhideliu'               => '$1 польꙃєватєлъ · ѩжє съꙁижьдє сѥ мѣсто · мѣнꙑ',
'rcshowhideanons'             => '$1 анѡнѷмьнъ польꙃєватєлъ мѣнꙑ',
'rcshowhidemine'              => '$1 моꙗ мѣнꙑ',
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
'recentchangeslinked-page'    => 'страницѧ имѧ :',

# Upload
'upload'              => 'положєниѥ дѣла',
'uploadbtn'           => 'положєниѥ дѣла',
'uploadlog'           => 'дѣлъ положєниꙗ їсторїꙗ',
'uploadlogpage'       => 'дѣлъ положєниꙗ їсторїꙗ',
'filename'            => 'дѣла имѧ',
'filedesc'            => 'опьсаниѥ',
'fileuploadsummary'   => 'опьсаниѥ:',
'uploadedimage'       => '⁖ [[$1]] ⁖ положєнъ ѥстъ',
'watchthisupload'     => 'си дѣла блюдєниѥ',
'upload-success-subj' => 'дѣло положєно ѥстъ',

'license'        => 'прощєниѥ :',
'license-header' => 'прощєниѥ',

# Special:ListFiles
'imgfile'        => 'дѣло',
'listfiles'      => 'дѣлъ каталогъ',
'listfiles_name' => 'и́мѧ',
'listfiles_user' => 'польꙃєватєл҄ь',
'listfiles_size' => 'мѣра',

# File description page
'file-anchor-link'   => 'дѣло',
'filehist'           => 'дѣла їсторїꙗ',
'filehist-deleteone' => 'поничьжєниѥ',
'filehist-current'   => 'нꙑнѣщьн҄ь обраꙁъ',
'filehist-datetime'  => 'дьнь / врѣмѧ',
'filehist-user'      => 'польꙃєватєл҄ь',
'filehist-filesize'  => 'дѣла мѣра',
'filehist-comment'   => 'опьсаниѥ',
'imagelinks'         => 'дѣла польꙃєваниѥ',
'sharedupload'       => 'сѥ дѣло въ $1 съхранѥно ѥстъ дѣла · ѥгожє дроугꙑ опꙑтьствованиѩ польꙃєвати могѫтъ',

# File reversion
'filerevert-comment' => 'какъ съмꙑслъ :',

# File deletion
'filedelete'         => 'поничьжєниѥ $1',
'filedelete-legend'  => 'дѣла поничьжєниѥ',
'filedelete-comment' => 'какъ съмꙑслъ :',
'filedelete-submit'  => 'поничьжєниѥ',

# MIME search
'mimetype' => 'MIME тѷпъ :',
'download' => 'поѩти',

# List redirects
'listredirects' => 'прѣнаправлѥниꙗ',

# Random page
'randompage' => 'страница въ нєꙁаапѫ',

# Random redirect
'randomredirect' => 'прѣнаправлѥниє въ нєꙁаапѫ',

# Statistics
'statistics'              => 'статїстїка',
'statistics-header-pages' => 'страницѧ статїстїка',
'statistics-header-edits' => 'мѣнъ статїстїка',
'statistics-header-users' => 'польꙃєватєлъ статїстїка',
'statistics-articles'     => 'истиньнꙑ члѣни',
'statistics-pages'        => 'страницѧ',
'statistics-files'        => 'положєнꙑ дѣла',

'disambiguations'     => 'страницѧ ижє съвѧꙁи съ мъногосъмꙑслиꙗ имѫтъ',
'disambiguationspage' => 'Template:мъногосъмꙑслиѥ',

'brokenredirects-edit'   => 'исправи',
'brokenredirects-delete' => 'поничьжєниѥ',

# Miscellaneous special pages
'nbytes'            => '$1 {{PLURAL:$1|баитъ|баита|баитъ}}',
'nlinks'            => '$1 {{PLURAL:$1|съвѧꙁь|съвѧꙁи|съвѧꙁии}}',
'shortpages'        => 'кратъкꙑ страницѧ',
'listusers'         => 'польꙃєватєлъ каталогъ',
'newpages'          => 'нови члѣни',
'newpages-username' => 'польꙃєватєлꙗ имѧ :',
'move'              => 'прѣимєнованиѥ',
'movethispage'      => 'си страницѧ прѣимєнованиѥ',

# Book sources
'booksources-go' => 'прѣиди',

# Special:Log
'specialloguserlabel'  => 'польꙃєватєл҄ь:',
'speciallogtitlelabel' => 'страницѧ имѧ :',
'log'                  => 'їсторїѩ',
'all-logs-page'        => 'вьсѩ обьщѧ їсторїѩ',

# Special:AllPages
'allpages'       => 'вьсѩ страницѧ',
'alphaindexline' => 'отъ $1 до $2',
'allpagesfrom'   => 'страницѧ видѣти хощѫ съ начѧльнами боукъвами :',
'allarticles'    => 'вьсѩ страницѧ',
'allpagessubmit' => 'прѣиди',

# Special:Categories
'categories' => 'катигорїѩ',

# Special:DeletedContributions
'deletedcontributions'       => 'поничьжєнꙑ добродѣꙗниꙗ',
'deletedcontributions-title' => 'поничьжєнꙑ добродѣꙗниꙗ',

# Special:LinkSearch
'linksearch'    => 'вънѣщьн҄ь съвѧꙁь исканиѥ',
'linksearch-ok' => 'ищи',

# Special:ListUsers
'listusers-submit' => 'виждь',

# Special:Log/newusers
'newuserlogpage'              => 'новъ мѣстъ сътворѥниꙗ їсторїꙗ',
'newuserlog-create-entry'     => 'новъ польꙃєватєл҄ь',
'newuserlog-autocreate-entry' => 'польꙃєватєлꙗ мѣсто аѵтоматичьно сътворєно ѥстъ',

# E-mail user
'emailuser' => 'посъли єпїстолѫ',

# Watchlist
'watchlist'        => 'моꙗ блюдєниꙗ',
'mywatchlist'      => 'моꙗ блюдєниꙗ',
'addedwatchtext'   => "страница ⁖ [[:$1]] ⁖ нꙑнѣ подъ твоимь [[Special:Watchlist|блюдєниѥмь]] ѥстъ ⁙
всꙗ ѥѩ и ѥѩжє бєсѣдꙑ мѣнꙑ страницѧ ⁖ [[Special:Watchlist|моꙗ блюдєниꙗ]] ⁖ покаꙁанꙑ сѫтъ и  [[Special:RecentChanges|послѣдьнъ мѣнъ]] каталоꙃѣ '''чрьнꙑимъ''' сѧ авлꙗѭтъ",
'removedwatchtext' => 'страница ⁖ [[:$1]] ⁖ нꙑнѣ твоѥго [[Special:Watchlist|блюдєниꙗ]] иꙁнєсєна ѥстъ',
'watch'            => 'блюдєниѥ',
'watchthispage'    => 'си страницѧ блюдєниѥ',
'unwatch'          => 'остави блюдєниѥ',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'блюдєниѥ ...',
'unwatching' => 'оставьлєниѥ блюдєниꙗ ...',

'created' => 'сътворѥнъ ѥстъ',

# Delete
'deletepage'      => 'поничьжєниѥ',
'excontent'       => "вънѫтри бѣ: '$1'",
'excontentauthor' => "вънѫтри бѣ : '$1' (и послѣдьн҄ии дѣтєл҄ь бѣ '[[Special:Contributions/$2|$2]]')",
'delete-legend'   => 'поничьжєниѥ',
'actioncomplete'  => 'дѣиство сътворєно ѥстъ',
'deletedtext'     => 'страница ⁖ $1 ⁖ поничьжєна ѥстъ ⁙
виждь ⁖ $2 ⁖ послѣдьнъ поничьжєниѩ дѣлꙗ',
'deletedarticle'  => '⁖ [[$1]] ⁖ поничьжєнъ ѥстъ',
'dellogpage'      => 'поничьжєниꙗ їсторїꙗ',
'deletionlog'     => 'поничьжєниꙗ їсторїꙗ',
'deletecomment'   => 'какъ съмꙑслъ :',

# Protect
'protectlogpage'      => 'ꙁабранѥниꙗ їсторїꙗ',
'protectedarticle'    => '⁖ [[$1]] ⁖ ꙁабранѥна ѥстъ',
'prot_1movedto2'      => '⁖ [[$1]] ⁖ нарєчєнъ ⁖ [[$2]] ⁖ ѥстъ',
'protectcomment'      => 'какъ съмꙑслъ :',
'protect-level-sysop' => 'толико съмотритєлє',

# Restrictions (nouns)
'restriction-edit'   => 'исправи',
'restriction-move'   => 'прѣимєнованиѥ',
'restriction-upload' => 'положєниѥ',

# Undelete
'undeletecomment'        => 'какъ съмꙑслъ :',
'undelete-search-submit' => 'ищи',

# Namespace form on various pages
'namespace'      => 'имєнъ просторъ:',
'blanknamespace' => '(главьно)',

# Contributions
'contributions'       => 'польꙃєватєлꙗ добродѣꙗниꙗ',
'contributions-title' => 'польꙃєватєлꙗ ⁖ $1 ⁖ добродѣꙗниꙗ',
'mycontris'           => 'моꙗ добродѣꙗниꙗ',
'contribsub2'         => 'польꙃєватєлꙗ имѧ ⁖ $1 ⁖ ѥстъ ($2)',
'uctop'               => '(послѣдьнꙗ мѣна)',

'sp-contributions-blocklog' => 'ꙁаграждєниꙗ їсторїꙗ',
'sp-contributions-deleted'  => 'поничьжєнꙑ добродѣꙗниꙗ',
'sp-contributions-talk'     => 'бєсѣда',
'sp-contributions-username' => 'IP число или польꙃєватєлꙗ имѧ :',
'sp-contributions-submit'   => 'ищи',

# What links here
'whatlinkshere'            => 'дос̑ьдєщьнѩ съвѧꙁи',
'whatlinkshere-title'      => 'страницѧ ижє съ ⁖ $1 ⁖ съвѧꙁи имѫтъ',
'whatlinkshere-page'       => 'страница :',
'isredirect'               => 'прѣнаправлѥниѥ',
'istemplate'               => 'внѫтри страницѧ',
'whatlinkshere-links'      => '← съвѧꙁи',
'whatlinkshere-hideredirs' => '$1 прѣнаправлѥниꙗ',
'whatlinkshere-hidelinks'  => '$1 съвѧꙁи',

# Block/unblock
'blockip'            => 'ꙁагради польꙃєватєл҄ь',
'ipadressorusername' => 'IP число или польꙃєватєлꙗ имѧ :',
'ipbreason'          => 'какъ съмꙑслъ :',
'ipblocklist'        => 'ꙁаграждєнꙑ польꙃєватєлє',
'blocklist-reason'   => 'какъ съмꙑслъ',
'ipblocklist-submit' => 'исканиѥ',
'blocklink'          => 'ꙁагради',
'contribslink'       => 'добродѣꙗниꙗ',
'blocklogpage'       => 'ꙁаграждєниꙗ їсторїꙗ',

# Move page
'move-page'               => 'прѣимєнованиѥ ⁖ $1 ⁖',
'move-page-legend'        => 'страницѧ прѣимєнованиѥ',
'movearticle'             => 'страница :',
'newtitle'                => 'ново имѧ :',
'move-watch'              => 'си страницѧ блюдєниѥ',
'movepagebtn'             => 'прѣимєнованиѥ',
'pagemovedsub'            => 'прѣимєнованиѥ сътворѥно ѥстъ',
'movepage-moved'          => "'''⁖ $1 ⁖ нарєчєнъ ⁖ $2⁖ ѥстъ'''",
'movepage-moved-redirect' => 'прѣнаправлѥниѥ сътворѥно бѣ',
'movedto'                 => 'прѣимєновано въ',
'movetalk'                => 'си страницѧ бєсѣдꙑ прѣимєнованиѥ',
'1movedto2'               => '⁖ [[$1]] ⁖ нарєчєнъ ⁖ [[$2]] ⁖ ѥстъ',
'1movedto2_redir'         => '[[$1]] нарєчєнъ [[$2]] врьхоу прѣнаправлѥниꙗ ѥстъ.',
'movelogpage'             => 'прѣимєнованиꙗ їсторїꙗ',
'movereason'              => 'какъ съмꙑслъ :',
'move-leave-redirect'     => 'прѣнаправлѥниꙗ сътворѥниѥ',

# Namespace 8 related
'allmessages'     => 'сѷстимьнꙑ напьсаниꙗ',
'allmessagesname' => 'имѧ',

# Tooltip help for the actions
'tooltip-pt-userpage'            => 'твоꙗ польꙃєватєл҄ьска страница',
'tooltip-pt-mytalk'              => 'твоꙗ бєсѣдꙑ страница',
'tooltip-pt-preferences'         => 'твои строи',
'tooltip-pt-mycontris'           => 'твоѩ добродѣꙗнии каталогъ',
'tooltip-pt-logout'              => 'ис̾ходъ',
'tooltip-ca-talk'                => 'си страницѧ бєсѣда',
'tooltip-ca-viewsource'          => 'си страница ꙁабранєна ѥстъ ⁙
ѥѩ источьнъ обраꙁъ видєти можєши',
'tooltip-ca-protect'             => 'си страницѧ ꙁабранєниѥ',
'tooltip-ca-delete'              => 'си страницѧ поничьжєниѥ',
'tooltip-ca-move'                => 'си страницѧ прѣимєнованиѥ',
'tooltip-ca-watch'               => 'си страницѧ блюдєниѥ',
'tooltip-search'                 => 'ищи {{{grammar:genitive|{{SITENAME}}}}} страницѧ',
'tooltip-p-logo'                 => 'главьна страница',
'tooltip-n-mainpage'             => 'виждь главьноу страницѫ',
'tooltip-n-mainpage-description' => 'виждь главьноу страницѫ',
'tooltip-n-recentchanges'        => 'послѣдьн҄ь мѣнъ каталогъ',
'tooltip-t-contributions'        => 'виждь польꙃєватєлꙗ добродѣꙗнии каталогъ',
'tooltip-t-upload'               => 'положєниѥ дѣлъ',
'tooltip-t-specialpages'         => 'вьсѣѩ нарочьнъ страницѧ каталогъ',
'tooltip-t-print'                => 'сѥѩ страницѧ пєчатьнъ обраꙁъ',
'tooltip-ca-nstab-special'       => 'си нарочьна страница ѥстъ · ѥѩжє иꙁмѣнꙗти нє можєши',
'tooltip-watch'                  => 'си страницѧ блюдєниѥ',

# Media information
'file-info-size' => '$1 × $2 п҃ѯ · дѣла мѣра : $3 · MIME тѷпъ : $4',
'svg-long-desc'  => 'дѣло SVG · обꙑчьнъ обраꙁъ : $1 × $2 п҃ѯ · дѣла мѣра : $3',
'show-big-image' => 'пльнъ вида обраꙁъ',

# Special:NewFiles
'ilsubmit' => 'ищи',

# EXIF tags
'exif-artist' => 'творьць',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'вьсꙗ',
'namespacesall' => 'вьсꙗ',
'monthsall'     => 'вьсѩ',

'unit-pixel' => 'п҃ѯ',

# Multipage image navigation
'imgmultigo' => 'прѣиди',

# Table pager
'table_pager_limit_submit' => 'прѣиди',

# Auto-summaries
'autosumm-new' => 'нова страница ⁖ $1 ⁖ сътворєна  ѥстъ',

# Size units
'size-bytes'     => '$1 Б҃',
'size-kilobytes' => '$1 Х҃Б',

# Special:Version
'version'                  => 'MediaWiki обраꙁъ',
'version-version'          => '(обраꙁъ $1)',
'version-license'          => 'прощєниѥ',
'version-software-version' => 'обраꙁъ',

# Special:FilePath
'filepath-page' => 'дѣло :',

# Special:FileDuplicateSearch
'fileduplicatesearch-submit' => 'ищи',

# Special:SpecialPages
'specialpages' => 'нарочьнꙑ страницѧ',

# Special:Tags
'tags-edit' => 'исправи',

);
