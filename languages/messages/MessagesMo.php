<?php
/** Moldavian (Молдовеняскэ)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Comp1089
 * @author Node ue
 * @author לערי ריינהארט
 */

$fallback = 'ro';

$specialPageAliases = array(
	'CreateAccount'             => array( 'КреареКонт' ),
	'Preferences'               => array( 'Преферинце' ),
	'Recentchanges'             => array( 'Модификэрьреченте' ),
);

$messages = array(
# Dates
'sun'           => 'Дум',
'january'       => 'януарие',
'february'      => 'фебруарие',
'march'         => 'мартие',
'april'         => 'априлие',
'may_long'      => 'май',
'june'          => 'юние',
'july'          => 'юлие',
'august'        => 'аугуст',
'september'     => 'септембрие',
'october'       => 'октомбрие',
'november'      => 'ноембрие',
'december'      => 'дечембрие',
'january-gen'   => 'януарие',
'february-gen'  => 'фебруарие',
'march-gen'     => 'мартие',
'april-gen'     => 'априлие',
'may-gen'       => 'май',
'june-gen'      => 'юние',
'july-gen'      => 'юлие',
'august-gen'    => 'аугуст',
'september-gen' => 'септембрие',
'october-gen'   => 'октомбрие',
'november-gen'  => 'ноембрие',
'december-gen'  => 'дечембрие',
'jan'           => 'яну',
'feb'           => 'феб',
'mar'           => 'мар',
'apr'           => 'апр',
'may'           => 'май',
'jun'           => 'юни',
'jul'           => 'юли',
'aug'           => 'ауг',
'sep'           => 'сеп',
'oct'           => 'окт',
'nov'           => 'ное',
'dec'           => 'деч',

# Categories related messages
'pagecategories'        => '{{PLURAL:$1|Категоирие|Категорий}}',
'hidden-categories'     => '{{PLURAL:$1|категорие аскунсэ|категорий аскунсе}}',
'category-subcat-count' => "{{PLURAL:$2|Ачастэ категорие концине доар урмэтоаря субкатегорие.|Ачастэ категорие концине {{PLURAL:$1|урмэтоаря субкатегорие|урмэтоареле $1 субкатегорий}}, динтр'ун тотал де $2.}}",

'newwindow'  => "(се дескиде ынтр'о ферястрэ ноуэ)",
'mytalk'     => 'Дискуцииле меле',
'navigation' => 'Навигаре',

'tagline'          => 'Де ла {{SITENAME}}',
'help'             => 'Ажутор',
'search'           => 'Каутэ',
'searchbutton'     => 'Каутэ',
'searcharticle'    => 'Дуче',
'history'          => 'Историкул паӂиний',
'history_short'    => 'Историк',
'printableversion' => 'Версиуне де типэрит',
'permalink'        => 'Легэтурэ неынчетатэ',
'edit'             => 'Editează - Едитязэ',
'create'           => 'Креязэ',
'protect_change'   => 'скимбэ',
'newpage'          => 'Паӂина ноуэ',
'talkpagelinktext' => 'Дискуций',
'personaltools'    => 'Унелте персонале',
'talk'             => 'Дискуций',
'views'            => 'Визуализэрь',
'toolbox'          => 'Кутие де унелте',
'otherlanguages'   => 'Ын алте лимбь',
'redirectedfrom'   => '(Редирекционат де ла $1)',
'lastmodifiedat'   => 'Ултима модификаре $2, $1.',
'jumpto'           => 'Салт ла:',
'jumptonavigation' => 'навигацие',
'jumptosearch'     => 'кэутаре',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'Деспре {{SITENAME}}',
'aboutpage'            => 'Project:Деспре',
'copyright'            => 'Концинутул есте диспонибил суб $1.',
'copyrightpage'        => '{{ns:project}}:Дрептурь де аутор',
'disclaimers'          => 'Деклараций',
'disclaimerpage'       => 'Project:Декларацие ӂенералэ',
'edithelp'             => 'Ажутор пентру едитаре',
'edithelppage'         => 'Help:Едитаре',
'mainpage'             => 'Прима паӂина',
'mainpage-description' => 'Прима паӂина',
'privacy'              => 'Политика де интимитате',
'privacypage'          => 'Project:Политика де интимитате',

'retrievedfrom'   => 'Адус де ла "$1"',
'editsection'     => 'едитязэ',
'editold'         => 'едитязэ',
'editlink'        => 'едитязэ',
'viewsourcelink'  => 'везь сурса',
'editsectionhint' => 'Едитязэ секциуня: $1',
'toc'             => 'Таблэ де материй',
'showtoc'         => 'аратэ',
'hidetoc'         => 'аскунде',
'site-rss-feed'   => '$1 Агрегат RSS',
'site-atom-feed'  => '$1 Агрегат Atom',
'page-rss-feed'   => '$1 Агрегат RSS',
'page-atom-feed'  => '«$1» Агрегат Atom',
'red-link-title'  => '$1 (паӂина ну егзистэ)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'    => 'Паӂинэ',
'nstab-user'    => 'Паӂина утилизаторулуй',
'nstab-special' => 'Сервичий',
'nstab-project' => 'Паӂина проектулуй',
'nstab-image'   => 'Фишиер',

# General errors
'missing-article' => 'База де дате ну гэсеште текстул уней паӂинь каре ар фи требуит гэсит, нумит «$1» $2.

Ын мод нормал фаптул есте кауза де урмэриря уней диф неактуализатэ сау а уней легэтурь дин историк спре о паӂинэ каре а фост штярсэ.

Дакэ ну ачеста е мотивул, с-ар путя сэ фи гэсит ун буг ын програм.
Те рог анунцэ ачест аспект унуй [[Special:ListUsers/sysop|администратор]], индикынду-ь адреса УРЛ.',
'viewsource'      => 'Везь сурса',

# Login and logout pages
'nav-login-createaccount' => 'Креазэ конт / Аутентификаре',
'userlogin'               => 'Креязэ конт / Аутентификаре',
'userlogout'              => 'Ынкиде сесиуня',

# Edit page toolbar
'bold_sample'     => 'Текст алдин',
'bold_tip'        => 'Текст алдин',
'italic_sample'   => 'Текст курсив',
'italic_tip'      => 'Текст курсив',
'link_sample'     => 'Титлул легэтурий',
'link_tip'        => 'Легэтурэ интернэ',
'extlink_sample'  => 'http://www.example.com титлул легэтурий',
'extlink_tip'     => 'Легэтурэ екстернэ (ну уита префиксул http://)',
'headline_sample' => 'Текст де титлу',
'headline_tip'    => 'Титлу де нивел 2',
'math_sample'     => 'Интроду формула аичь',
'math_tip'        => 'Формулэ математикэ (LaTeX)',
'nowiki_sample'   => 'Интроду текст неформатат аичь',
'nowiki_tip'      => 'Игнорэ форматаря вики',
'image_tip'       => 'Фишиер инсерат',
'media_tip'       => 'Легэтурэ ла фишиер',
'sig_tip'         => 'Семнэтура та дататэ',
'hr_tip'          => 'Линие оризонталэ (фолосеште-о кумпэтат)',

# Edit pages
'summary'            => 'Резумат:',
'subject'            => 'Субьект/титлу:',
'minoredit'          => 'Ачаста есте о едитаре минорэ',
'watchthis'          => 'Привеште ачастэ паӂинэ',
'savearticle'        => 'Салвязэ паӂина',
'showpreview'        => 'Аратэ превизуализаре',
'showdiff'           => 'Аратэ диференце',
'noarticletext'      => 'Ын ачест момент ну есте ничь ун текст ын ачастэ паӂинэ.
Поць [[Special:Search/{{PAGENAME}}|кэута ачест титлу]] ын алте паӂинь,
<span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} кэута ынреӂистрэрь ын журнале], сау [{{fullurl:{{FULLPAGENAME}}|action=edit}} креа ачастэ паӂинэ]</span>.',
'copyrightwarning'   => "Рецине кэ тоате контрибуцииле ла {{SITENAME}} сынт дистрибуите суб личенца $2 (везь $1 пентру деталий).
Дакэ ну дорешть ка чея че скрий сэ фие модификат фэрэ милэ ши редистрибуит ын вое, атунчь ну тримите материалеле респективе аичь.<br />
Де асеменя, не асигурь кэ чея че ай скирс а фост композицие проприе сау копие динтр1о ресурсэ публикэ сау либерэ.
'''Ну интродуче материале ку дрептурь де аутор фэрэ пермисиуне!'''",
'template-protected' => '(протежат)',

# History pages
'revisionasof'     => 'Версиуня де ла дата $1',
'previousrevision' => 'Версиуня антериоарэ',
'cur'              => 'акт',
'last'             => 'преч',
'histfirst'        => 'Примеле',
'histlast'         => 'Ултимеле',

# Revision deletion
'rev-delundel'   => 'аратэ/аскунде',
'revdel-restore' => 'скимбэ визибилитатя',

# Merge log
'revertmerge' => 'Анулязэ ымбинаря',

# Diffs
'difference' => '(Диференца динтре версиунь)',
'lineno'     => 'Линия $1:',
'editundo'   => 'десфаче',

# Search results
'searchresults'             => 'Резултателе кэутэрий',
'searchresults-title'       => 'Резултателе кэутэрий пентру «$1»',
'searchresulttext'          => 'Пентру май мулте деталий деспре кэутаря ын {{SITENAME}}, везь [[{{MediaWiki:Helppage}}|{{int:help}}]].',
'searchsubtitle'            => 'Ай кэутат \'\'\'[[:$1]]\'\'\' ([[Special:Prefixindex/$1|тоате паӂиниле каре ынчеп ку "$1"]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|тоате паӂиниле каре се лягэ де "$1"]])',
'notitlematches'            => 'Ничь ун резултат ын титлуриле артиколелор',
'notextmatches'             => 'Ничь ун резултат ын текстеле артиколелор',
'prevn'                     => 'антериоареле {{PLURAL:$1|$1}}',
'nextn'                     => 'урмэтоареле {{PLURAL:$1|$1}}',
'viewprevnext'              => 'Везь ($1 {{int:pipe-separator}} $2) ($3).',
'search-result-size'        => '$1 ({{PLURAL:$2|1 кувынт|$2 кувинте}})',
'search-redirect'           => '(редирекционаре кэтре $1)',
'search-section'            => '(секциуня $1)',
'search-suggest'            => 'Аць дорит сэ скриець: $1',
'search-interwiki-caption'  => 'Проекте ынрудите',
'search-mwsuggest-enabled'  => 'ку суӂестий',
'search-mwsuggest-disabled' => 'фэрэ суӂестий',
'nonefound'                 => "'''Нотэ''': Нумай унеле спаций де нуме сынт кэутате импличит.
Ынчеркэ сэ пуй ка ши префикс ал кэутэрий ''all:'' пентру а кэута ын тот концинутул (инклузынд ши паӂиниле де дискуций, формате, етч), сау фолосеште спациул де нуме дорит ка ши префикс.",
'powersearch'               => 'Кэутаре авансатэ',
'powersearch-legend'        => 'Кэутаре авансатэ',
'powersearch-ns'            => 'Кэутаре ын спацииле де нуме:',
'powersearch-redir'         => 'Афишазэ редиректэриле',
'powersearch-field'         => 'Каутэ дупэ',

# Preferences page
'mypreferences' => 'Преферинцеле меле',

# Groups
'group-sysop' => 'Администраторь',

'grouppage-sysop' => '{{ns:project}}:Администраторь',

# Recent changes
'recentchanges'        => 'Скимбэрь реченте',
'recentchanges-legend' => 'Опциунь скимбэрь реченте',
'rclistfrom'           => 'Аратэ модификэриле ынчепынд де ла $1',
'rcshowhideminor'      => '$1 модификэриле миноре',
'rcshowhideanons'      => '$1 утилизаторь анонимь',
'rcshowhidemine'       => '$1 едитэриле меле',
'rclinks'              => 'Аратэ ултимеле $1 модификэрь дин ултимеле $2 зиле.<br />$3',
'diff'                 => 'диф',
'hist'                 => 'ист',
'hide'                 => 'аскунде',
'show'                 => 'аратэ',
'minoreditletter'      => 'м',
'newpageletter'        => 'Н',
'rc-enhanced-expand'   => 'Аратэ деталий (нечеситэ JavaScript)',
'rc-enhanced-hide'     => 'Аскунде деталииле',

# Recent changes linked
'recentchangeslinked'         => 'Скимбарь корелате',
'recentchangeslinked-feed'    => 'Скимбарь корелате',
'recentchangeslinked-toolbox' => 'Скимбарь корелате',
'recentchangeslinked-summary' => "Ачаста есте о листэ а скимбэрилор ефектуате речент асупра паӂинилор ку легэтурь де ла о анумитэ паӂинэ (сау асупра мембрилор уней анумите категорий).
Паӂиниле пе каре ле [[Special:Watchlist|привь]] апар ын '''алдине'''.",
'recentchangeslinked-page'    => 'Нумеле паӂиний:',

# Upload
'upload' => 'Тримите фишиер',

# File description page
'file-anchor-link'    => 'Фишиер',
'filehist'            => 'Историкул фишиерулуй',
'filehist-help'       => "Апасэ пе '''Дата ши ора''' пентру а ведя версиуня тримисэ атунчь.",
'filehist-current'    => 'актуалэ',
'filehist-datetime'   => 'Дата/Ора',
'filehist-thumb'      => 'Миниатурэ',
'filehist-thumbtext'  => 'Миниатурэ пентру версиуня дин $1',
'filehist-user'       => 'Утилизатор',
'filehist-dimensions' => 'Дименсиунь',
'filehist-comment'    => 'Коментариу',
'imagelinks'          => 'Легэтурь',
'linkstoimage'        => '{{PLURAL:$1|Урмэтоаря паӂинэ тримите спре|Урмэтоареле $1 паӂинь тримит спре}} ачест фишиер:',

# Miscellaneous special pages
'nbytes'        => '{{PLURAL:$1|ун октет|$1 октець}}',
'move'          => 'Депласязэ',
'pager-newer-n' => 'română (ro)
{{PLURAL:$1|1 май ноу|$1 май ной}}',
'pager-older-n' => '{{PLURAL:$1|1|$1}} май векь',

# Special:AllPages
'allpages'       => 'Тоате паӂиниле',
'alphaindexline' => '$1 пынэ ла $2',
'allpagessubmit' => 'Дуче',

# Watchlist
'watchlist'   => 'Паӂинь привите',
'mywatchlist' => 'Паӂинь привите',
'watch'       => 'Привеште',
'unwatch'     => 'Ну май привеште',

# Delete
'deletedarticle' => 'а штерс "[[$1]]"',

# Rollback
'rollbacklink' => 'ревино',

# Undelete
'undeletelink' => 'визуализязэ/рестаурязэ',

# Namespace form on various pages
'namespace'      => 'Спациу де нуме:',
'invert'         => 'Инверсязэ селекция',
'blanknamespace' => '(Принчиал)',

# Contributions
'contributions' => 'Контрибуцииле утилизаторулуй',
'mycontris'     => 'Контрибуцииле меле',
'month'         => 'Дин луна (ши май ынаинте):',
'year'          => 'Дин анул (ши май ынаинте):',

'sp-contributions-talk' => 'Дискуций',

# What links here
'whatlinkshere'       => 'Че се лягэ аичь',
'whatlinkshere-links' => '← легэтурь',

# Block/unblock
'blocklink'        => 'блокязэ',
'unblocklink'      => 'деблокязэ',
'change-blocklink' => 'модификэ блокаря',
'contribslink'     => 'контрибуций',

# Move page
'revertmove' => 'ревино',

# Thumbnails
'thumbnail-more' => 'Екстинде',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Паӂина та де утилизатор',
'tooltip-pt-mytalk'               => 'Паӂина та де дискуций',
'tooltip-pt-preferences'          => 'Преферинцеле меле',
'tooltip-pt-watchlist'            => 'Листа паӂинилор пе каре ле мониторизезь',
'tooltip-pt-mycontris'            => 'Листэ де контрибуцииле тале',
'tooltip-pt-login'                => 'Есте ынкуражат сэ се аутентификэ, дешь ачест лукру ну есте облигаториу.',
'tooltip-pt-logout'               => 'Ынкиде сесиуня',
'tooltip-ca-talk'                 => 'Дискуцие деспре артикол',
'tooltip-ca-edit'                 => 'Поате едита ачастэ паӂинэ. Ле ругэм сэ превизуализязэ концинутул ынаинте де салваре.',
'tooltip-ca-addsection'           => 'Адаугэ о ноуэ секциуне.',
'tooltip-ca-viewsource'           => 'Ачастэ паӂинэ есте протежатэ.
Поць визуализа доар кодул сурсэ',
'tooltip-ca-history'              => 'Версиуниле антериоаре але ачестей паӂиний',
'tooltip-ca-move'                 => 'Депласязэ ачастэ паӂинэ',
'tooltip-ca-watch'                => 'Адаугэ ла листа де паӂинь привите',
'tooltip-search'                  => 'Кэутаре ын {{SITENAME}}',
'tooltip-search-go'               => 'Ду-те ла паӂина ку ачест нуме ексакт дакэ егзистэ',
'tooltip-search-fulltext'         => 'Каутэ паӂиниле пентру ачест текст',
'tooltip-n-mainpage'              => 'Визитязэ паӂина принчипалэ',
'tooltip-n-mainpage-description'  => 'Визитязэ паӂина принчипалэ',
'tooltip-n-portal'                => 'Деспре проект, че поате фаче, унде гэсеште солуций.',
'tooltip-n-currentevents'         => 'Гэсеште информаций деспре ынтымпларе курентэ',
'tooltip-n-recentchanges'         => 'Листа ултимелор скимбэрь реализате ын ачест вики.',
'tooltip-n-randompage'            => 'Мерӂе спре о паӂинэ алятоаре',
'tooltip-n-help'                  => 'Локул ын каре гэсешть ажутор.',
'tooltip-t-whatlinkshere'         => 'Листа тутурор паӂинилор вики каре кондук спре ачастэ паӂинэ',
'tooltip-t-recentchangeslinked'   => 'Скимбэрь реченте ын легэтурэ ку ачастэ паӂинэ',
'tooltip-t-contributions'         => 'Везь листа де контрибуций але ачестуй утилизатор',
'tooltip-t-upload'                => 'Тримите имаӂинь сау фишиере медия',
'tooltip-t-specialpages'          => 'Листа тутурор паӂинилор де сервичиу',
'tooltip-t-print'                 => 'Версиуня де типэрит а ачестей паӂинь',
'tooltip-t-permalink'             => 'Легэтура перманентэ кэтре ачастэ версиуне а паӂиний',
'tooltip-ca-nstab-main'           => 'Везь паӂина де концинут',
'tooltip-ca-nstab-user'           => 'Везь паӂина де утилизатор',
'tooltip-ca-nstab-special'        => 'Ачаста есте о паӂинэ спечиалэ, ну о поць модифика директ.',
'tooltip-ca-nstab-project'        => 'Везь паӂина проектулуй',
'tooltip-ca-nstab-image'          => 'Везь паӂина фишиерулуй',
'tooltip-minoredit'               => 'Маркязэ ачастэ едитаре ка фиинд минорэ',
'tooltip-save'                    => 'Салвязэ скимбэриле тале',
'tooltip-preview'                 => 'Превизуализаря модофикэрилор тале, фолосеште-о те ругэм ынаинте де а салва!',
'tooltip-diff'                    => 'Аратэ-мь модификэриле ефектуате асупра текстулуй',
'tooltip-compareselectedversions' => 'Везь диференцеле ынтре челе доуэ версиунь селектате де пе ачастэ паӂинэ',
'tooltip-watch'                   => 'Адаугэ ла листа де паӂинь привите',
'tooltip-rollback'                => "«Ревино» анулязэ модификаря/модификэриле де пе ачастэ паӂинэ а ултимулуй контрибуитор принтр'о сингурэ апэсаре",

# Media information
'file-info-size'       => '$1 × $2 пиксель, мэриме фишиер: $3, тип MIME: $4',
'file-nohires'         => '<small>Резолуций май марь ну сынт диспонибиле.</small>',
'show-big-image-thumb' => '<small>Мэримя ачестей превизуализэрь: $1 × $2 пиксель</small>',

# Bad image list
'bad_image_list' => 'Форматул есте умэторул:

Нумай елементеле уней листе (линий че ынчеп ку *) сынт луате ын консидераре.
Прима легэтурэ де пе линие требуе сэ фие спре ун фишиер дефектуос.
Орьче легэтурь че урмязэ пе ачеяшь линие сынт консидерате ексчепций, адикэ паӂинь унде фишиерул поате апэря инклус директ.',

# Metadata
'metadata-help'     => 'Ачест фишиер концине информаций суплиментаре, интродусе пробабил де апаратул фотографик диӂитал сау сканерул каре л-а ӂенерат.
Дакэ фишиерул а фост модификат ынтре тимп, есте посибил ка унеле деталий сэ ну май фие валабиле.',
'metadata-collapse' => 'Аскунде деталий суплиментаре',

# 'all' in various places, this might be different for inflected languages
'namespacesall' => 'тоате',
'monthsall'     => 'тоате',

# Special:SpecialPages
'specialpages' => 'Сервичий',

);
