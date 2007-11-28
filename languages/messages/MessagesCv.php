<?php
/** Chuvash
 *
 * @addtogroup Language
 */

$fallback = 'ru';
$linkPrefixExtension = true;

$namespaceNames = array(
	NS_MEDIA            => 'Медиа',
	NS_SPECIAL          => 'Ятарлă',
	NS_MAIN             => '',
	NS_TALK             => 'Сӳтсе явасси',
	NS_USER             => 'Хутшăнакан',
	NS_USER_TALK        => 'Хутшăнаканăн_канашлу_страници',
	# NS_PROJECT set by $wgMetaNamespace
	NS_PROJECT_TALK     => '$1_сӳтсе_явмалли',
	NS_IMAGE            => 'Ӳкерчĕк',
	NS_IMAGE_TALK       => 'Ӳкерчĕке_сӳтсе_явмалли',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki_сӳтсе_явмалли',
	NS_TEMPLATE         => 'Шаблон',
	NS_TEMPLATE_TALK    => 'Шаблона_сӳтсе_явмалли',
	NS_HELP             => 'Пулăшу',
	NS_HELP_TALK        => 'Пулăшăва_сӳтсе_явмалли',
	NS_CATEGORY         => 'Категори',
	NS_CATEGORY_TALK    => 'Категорине_сӳтсе_явмалли',
);

$linkTrail = '/^([a-zа-яĕçăӳ"»]+)(.*)$/sDu';

$messages = array(
'underline-always' => 'Яланах',

# Dates
'monday'    => 'Тунтикун',
'tuesday'   => 'Ытларикун',
'thursday'  => 'Кĕçнерникун',
'friday'    => 'Эрнекун',
'january'   => 'Кăрлач',
'march'     => 'Пуш',
'april'     => 'Ака',
'may_long'  => 'Çу',
'june'      => 'Çěртме',
'july'      => 'Утă',
'august'    => 'Çурла',
'september' => 'Авăн',
'october'   => 'Юпа',
'november'  => 'Чӳк',
'december'  => 'Раштав',
'jan'       => 'Кăр',
'mar'       => 'Пуш',
'apr'       => 'Ака',
'may'       => 'Çу',
'jun'       => 'Çěр',
'jul'       => 'Утă',
'aug'       => 'Çур',
'sep'       => 'Авн',
'oct'       => 'Юпа',
'nov'       => 'Чӳк',
'dec'       => 'Раш',

# Bits of text used by many pages
'categories'     => 'Категорисем',
'pagecategories' => 'Категорисем',

'mainpage'   => 'Тĕп страницă',

'aboutsite'      => '{{SITENAME}} çинчен',
'aboutpage'      => 'Project:çинчен',
'help'           => 'Пулăшу',
'helppage'       => 'Help:Пулăшу',
'sitesupport'    => 'Пожертвованисем',
'edithelp'       => 'Улшăнусене кĕртме пулăшакан пулăшу',
'edithelppage'   => 'Help:Улшăнусене кĕртме пулăшакан пулăшу',
'qbspecialpages' => 'Ятарлӑ страницӑсем',

'currentevents'     => 'Хыпарсем',
'currentevents-url' => 'Хыпарсем',

'search'           => 'Шырасси',
'searchbutton'     => 'Шырасси',
'go'               => 'Куç',
'searcharticle'    => 'Куç',
'history'          => 'Истори',
'history_short'    => 'Истори',
'printableversion' => 'Пичетлемелли верси',
'permalink'        => 'Яланхи вырăн',
'edit'             => 'Тӳрлетӳ',
'editthispage'     => 'Страницăна тӳрлетесси',
'delete'           => 'Кăларса пăрахасси',
'undelete_short'   => '$1 тӳрлетӳсене каялла тавăр',
'talkpage'         => 'Сӳтсе явасси',
'specialpage'      => 'Ятарлă страницă',
'talk'             => 'Сӳтсе явасси',
'toolbox'          => 'Ĕç хатĕрĕсем',
'otherlanguages'   => 'Урăх чěлхесем',
'lastmodifiedat'   => 'Ку страницăна юлашки улăштарнă вăхăт: $2, $1.', # $1 date, $2 time
'jumptonavigation' => 'навигаци',
'jumptosearch'     => 'Шырав',

'toc'     => 'Тупмалли',
'hidetoc' => 'кӑтартмалла мар',

# Short words for each namespace, by default used in the 'article' tab in monobook
'nstab-user'    => 'Хутшăнакан страници',
'nstab-special' => 'Ятарлă',
'nstab-help'    => 'пулăшу',

# General errors
'laggedslavemode' => 'Асăрхăр! Страница çинче юлашки улшăнусене кăтартмасăр пултарнă.',

# Edit pages
'minoredit'      => 'Кунта пěчěк улшăнусем кăна кěртнě',
'watchthis'      => 'Ку страницăна кěртекен  улшăнусем  хыççăн сăнамалла',
'previewnote'    => 'Ку страницăна халлěхе çырса хуман. Эсир ку страницă мěнле пулассине кăна куратăр!!',
'editing'        => '$1 тӳрлетни',
'editinguser'    => '$1 тӳрлетни',
'editingsection' => '$1 тӳрлетни (статья разделě)',
'editingcomment' => '$1 тӳрлетни (кӗске анлантарӑвӗ)',
'templatesused'  => 'Ку страница çинче усă курнă шаблонсем:',

# History pages
'previousrevision' => '&larr;Малтанхи верси',
'nextrevision'     => 'Çěнěрех верси→',
'next'             => 'тепěр',
'deletedrev'       => '[кăларса пăрахнă]',
'histfirst'        => 'Пĕрремĕш',
'histlast'         => 'Юлашки',

# Search results
'showingresults' => 'Аяларах эсир <b>#$2</b> пуçласа кăтартнă <b>$1</b> йĕркене куратăр.',

# Preferences page
'oldpassword' => 'Кивě пароль',
'newpassword' => 'Çěнě пароль',
'textboxsize' => 'Тӳрлетни',
'rows'        => 'Йěркесем',
'localtime'   => 'Вырăнти вăхăт',
'servertime'  => 'Сервер вăхăчě',
'files'       => 'Файлсем',

# User rights log
'rightslogtext' => 'Ку пользовательсен прависене улăштарниссен журналě',

# Recent changes
'recentchanges' => 'Юлашки улшăнусем',
'rcnote'        => 'Юлашки <strong>$2</strong> кун хушшинчи <strong>$1</strong> улшăнусем. Халě пěтěмпе  <strong>{{NUMBEROFARTICLES}}</strong> статья.',
'rclistfrom'    => 'Юлашки улшăнусене $1 вăхăтран пуçласа кăтартнă',
'rclinks'       => 'Юлашки $2 кун хушшинче тунă $1 улшăнусене кăтартмалла<br />$3',
'hide'          => 'кăтартмалла мар',
'show'          => 'кăтартмалла',
'newpageletter' => 'Ç',

# Upload
'upload'         => 'Файла кĕртесси',
'uploadlog'      => 'Файлсене кĕртнин логĕ',
'uploadedfiles'  => 'Кĕртнĕ файлсем',
'ignorewarnings' => 'Асăрхаттарусене шута илмелле мар',
'uploaddisabled' => 'Каçарăр та сайта халĕ нимĕн те кĕртме юрамаст.',

# Image list
'imagelist' => 'Ӳкерчěксен списокě',
'ilsubmit'  => 'Шырамалла',

# Miscellaneous special pages
'nviews'                  => '$1 хут пăхнă',
'uncategorizedpages'      => 'Каталогсăр страницăсем',
'uncategorizedcategories' => 'Каталога кĕртмен категорисем',
'allpages'                => 'Пěтěм страницăсем',
'randompage'              => 'Ăнсăртран илнě страницă',
'deadendpages'            => 'Ниăçта та урăх ертмен страницăсем',
'listusers'               => 'Хутшăнакансен списокĕ',
'specialpages'            => 'Ятарлă страницăсем',
'spheading'               => 'Пěтěм пользовательсем валли ятарлă страницăсем',
'recentchangeslinked'     => 'Çыхăннă улшăнусем',
'newpages'                => 'Çěнě страницăсем',
'ancientpages'            => 'Чи кивĕ статьясем',

'data' => 'Кун',

# Special:Allpages
'nextpage'     => 'Тепěр страницă ($1)',
'allpagesnext' => 'Тепěр',

# Delete/protect/revert
'deletepage'     => 'Кăларса парахнă статьясем',
'rollback'       => 'Тÿрлетÿсене каялла куçарасси',
'rollback_short' => 'Каялла куçарасси',
'rollbackfailed' => 'Каялла куçарнă çухна йăнăш тухнă',

# Undelete
'undelete'           => 'Кăларса пăрахнă страницăсене пăх',
'undeleterevisions'  => 'Архивра пурĕ $1 верси',
'undeletebtn'        => 'Каялла тавăр!',
'undeletedarticle'   => '«[[$1]]» каялла тавăрнă',
'undeletedrevisions' => '$1 кăларса пăрахнă тӳрлетӳсене каялла тавăрнă',

# Contributions
'uclinks' => 'Юлашки $1 улшăнусене пăх; юлашки $2 кун хушшинче тунисене пăх.',
'uctop'   => ' (пуçламăш)',

# What links here
'whatlinkshere' => 'Кунта килекен ссылкăсем',
'notargettitle' => 'Тĕллевне кăтартман',
'linklistsub'   => '(ссылкăсен списокĕ)',

# Block/unblock
'ipbreason'       => 'Сăлтавĕ',
'unblockip'       => 'IP-адреса блокировкăран калар',
'unblocklink'     => 'блокировкăран кăлар',
'unblocklogentry' => '«$1» блокировкăран кăларнă',

# Move page
'movepage'         => 'Страницăна куçарнă',
'pagemovedsub'     => 'Куçарас ěç тěрěс иртрě',
'talkpagemoved'    => 'Сӳтсе явмалли страницăн ятне те улăштартăмăр.',
'talkpagenotmoved' => 'Сӳтсе явмалли страницăн ятне улăштарма пултараймарăмăр.',
'1movedto2'        => '$1 $2 çине куçарнă',
'delete_and_move'  => 'Кăларса пăрахса куçарасси',

# Thumbnails
'thumbnail-more' => 'Пысăклатмалли',

# Tooltip help for the actions
'tooltip-pt-userpage'    => 'Пользователь страници',
'tooltip-pt-preferences' => 'Настройкӑсем',
'tooltip-ca-talk'        => 'Статьяна сӳтсе явасси',
'tooltip-ca-edit'        => 'Эсир ку страницӑна тӳрлетме пултаратӑр. Тархасшӑн ҫырса хӑваричен страницӑ мӗнле пулассине пӑхӑр.',
'tooltip-ca-addsection'  => 'Кӗске ӑнлантару хушма пултаратӑр.',
'tooltip-ca-viewsource'  => 'Ку страницӑна эсир улӑштарма пултараймастӑр. Ӑна мӗнле ҫырнине кӑна пӑхма пултаратӑр.',
'tooltip-ca-protect'     => 'Улӑшратусенчен сыхласси',
'tooltip-ca-delete'      => 'Страницӑна кӑларса пӑрахмалли',
'tooltip-ca-move'        => 'Страницӑна урӑх ҫӗре куҫарасси',
'tooltip-ca-watch'       => 'Ку страницӑ хыҫҫӑн сӑнама пуҫласси',
'tooltip-ca-unwatch'     => 'Ку страницӑ хыҫҫӑн урӑх сӑнамалла мар',
'tooltip-search'         => 'Шырав',
'tooltip-p-logo'         => 'Тӗп страницӑ',
'tooltip-save'           => 'Тӳрлетӳсене астуса хăвармалла',
'tooltip-watch'          => 'Çак страницăна пăхса тăмаллисем шутне хуш',

# Attribution
'and'    => 'тата',
'others' => 'ыттисем',

# Spam protection
'listingcontinuesabbrev' => '(малалли)',

# Info page
'numedits' => 'Улшăнусен шучĕ (статьясем): $1',

# Trackbacks
'trackbackremove' => ' ([$1 кăларса пăрах])',

);
