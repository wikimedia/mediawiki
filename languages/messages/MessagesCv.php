<?php
/** Chuvash (Чăвашла)
 *
 * @ingroup Language
 * @file
 *
 * @author Siebrand
 * @author PCode
 * @author Chavash
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
# User preference toggles
'tog-extendwatchlist'      => 'Пĕтĕм улшăнусене кăтартакан анлă сăнав списокĕ',
'tog-numberheadings'       => 'Заголовоксене хăй тĕллĕн номерлесе пымалла.',
'tog-showtoolbar'          => 'Тӳрлетнĕ чухне çӳлти хатĕрсен хăмине кăтартмалла (JavaScript)',
'tog-showtoc'              => 'Тупмаллине кăтартмалла (3, е ытларах заголовок пулсан)',
'tog-watchcreations'       => 'Эпĕ тунă страницăсене сăнав списокне кĕртмелле',
'tog-watchdefault'         => 'Тӳрлетнĕ страницăсене сăнав списокне кĕртмелле',
'tog-watchmoves'           => 'Страница ятне эпĕ улаштарсан вĕсене сăнав списокне кĕртмелле',
'tog-watchdeletion'        => 'Эпĕ кăларса пăрахнă страницăсене сăнав списокне кĕртмелле',
'tog-previewontop'         => 'Тӳрлетӳ чӳречине кăтартас умĕн малтанхи курăмне кăтармалла',
'tog-previewonfirst'       => 'Пĕрремĕш хут тӳрлетнĕ чухне малтанхи курăнăва кăтартмалла',
'tog-enotifwatchlistpages' => 'Сăнав списокĕнчи статяна тӳрлетӳ кĕртсен электронлă почта урлă пĕлтермелле',
'tog-enotifusertalkpages'  => 'Ман канашлу страницинче улшăнусем пулсан мана эл. почта урлă пĕлтермелле',
'tog-enotifminoredits'     => 'Пĕчĕк улшăнусене те эл. почта урлă пĕлтермелле',
'tog-enotifrevealaddr'     => 'Асăрхаттару пĕлтерĕвĕсенче ман эл. адреса кăтартмалла',
'tog-shownumberswatching'  => 'Страницăна миçе хутшăнакан сăнав списокне кĕртнине кăтартмалла',
'tog-fancysig'             => 'Хăвăр тунă алă пуснин вики-паллисем',
'tog-externaldiff'         => 'Палăртман чухне версисене танлаштарма тулашри программăна усă курмалла',
'tog-showjumplinks'        => '«... патне куç» хушма каçăсене усă курмалла',
'tog-uselivepreview'       => 'Хăвăрт кăтартакан малтанхи курăнăва усă курмалла (JavaScript, экспериментлă)',
'tog-watchlisthideown'     => 'Сăнав списокĕнче эпĕ тунă тӳрлетӳсене кăтартмалла мар',
'tog-watchlisthidebots'    => 'Сăнав списокĕнче бот тӳрлетĕвĕсене кăтартмалла мар',
'tog-watchlisthideminor'   => 'Сăнав списокĕнче пĕчĕк улшăнусене кăтартмалла мар',
'tog-ccmeonemails'         => 'Эп ытти хутшăнакансем патне янă çырусен копине мана та ярса памалла.',
'tog-diffonly'             => 'Икĕ версине танлаштарнă чухне страница ăшлăхне кăтартмалла мар',

'underline-always'  => 'Яланах',
'underline-default' => 'Браузер ĕнерлевĕсене усă курмалла',

# Dates
'sunday'    => 'вырсарникун',
'monday'    => 'Тунтикун',
'tuesday'   => 'Ытларикун',
'thursday'  => 'Кĕçнерникун',
'friday'    => 'Эрнекун',
'tue'       => 'Ыт',
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
'categories'            => 'Категорисем',
'pagecategories'        => 'Категорисем',
'category_header'       => '«$1» категорири статьясем',
'category-media-header' => '«$1» категорири файлсем',
'category-empty'        => "''Хальхи вăхăтра ку категори пушă.''",

'mainpagetext'      => '<big>«MediaWiki» вики-движока лартасси ăнăçлă вĕçленчĕ.</big>',
'mainpagedocfooter' => 'Ку википе ĕçлеме пулăшакан информацине [http://meta.wikimedia.org/wiki/%D0%9F%D0%BE%D0%BC%D0%BE%D1%89%D1%8C:%D0%A1%D0%BE%D0%B4%D0%B5%D1%80%D0%B6%D0%B0%D0%BD%D0%B8%D0%B5 усăç руководствинче] тупма пултаратăр.

== Пулăшма пултарĕç ==
* [http://www.mediawiki.org/wiki/Manual:Configuration_settings Ĕнерлевсен списокĕ];
* [http://www.mediawiki.org/wiki/Manual:FAQ MediaWiki тăрăх час-часах ыйтакан ыйтусемпе хуравсем];
* [http://lists.wikimedia.org/mailman/listinfo/mediawiki-announce MediaWiki çĕнĕ верси тухнине пĕлтерекен рассылка].',

'article'        => 'Статья',
'qbpageinfo'     => 'Страница çинчен',
'qbspecialpages' => 'Ятарлӑ страницӑсем',
'mypage'         => 'Ман страница',
'mytalk'         => 'Ман канашлу страници',
'and'            => 'тата',

# Metadata in edit box
'metadata_help' => 'Метаданнăйсем:',

'returnto'         => '$1 страницăна таврăн.',
'help'             => 'Пулăшу',
'search'           => 'Шырасси',
'searchbutton'     => 'Шырасси',
'go'               => 'Куç',
'searcharticle'    => 'Куç',
'history'          => 'Истори',
'history_short'    => 'Истори',
'info_short'       => 'Информаци',
'printableversion' => 'Пичетлемелли верси',
'permalink'        => 'Яланхи вырăн',
'edit'             => 'Тӳрлетӳ',
'editthispage'     => 'Страницăна тӳрлетесси',
'delete'           => 'Кăларса пăрахасси',
'undelete_short'   => '$1 тӳрлетӳсене каялла тавăр',
'talkpage'         => 'Сӳтсе явасси',
'specialpage'      => 'Ятарлă страницă',
'personaltools'    => 'Ман хатĕрсем',
'postcomment'      => 'Хуравла',
'talk'             => 'Сӳтсе явасси',
'toolbox'          => 'Ĕç хатĕрĕсем',
'imagepage'        => 'Ӳкерчĕк страницине пăх',
'mediawikipage'    => 'Пĕлтерӳ страницине кăтарт',
'categorypage'     => 'Категори страницине пăх',
'otherlanguages'   => 'Урăх чěлхесем',
'lastmodifiedat'   => 'Ку страницăна юлашки улăштарнă вăхăт: $2, $1.', # $1 date, $2 time
'viewcount'        => 'Ку страницăна $1 хут пăхнă.',
'jumptonavigation' => 'навигаци',
'jumptosearch'     => 'Шырав',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'         => '{{SITENAME}} çинчен',
'aboutpage'         => 'Project:çинчен',
'copyright'         => 'Ку ăшлăх $1 килĕшӳллĕн сарăлать.',
'currentevents'     => 'Хыпарсем',
'currentevents-url' => 'Project:Хыпарсем',
'disclaimers'       => 'Яваплăха тивĕçтерменни',
'disclaimerpage'    => 'Project:Яваплăха тивĕçтерменни',
'edithelp'          => 'Улшăнусене кĕртме пулăшакан пулăшу',
'edithelppage'      => 'Help:Улшăнусене кĕртме пулăшакан пулăшу',
'faqpage'           => 'Project:ЫйХу',
'helppage'          => 'Help:Пулăшу',
'mainpage'          => 'Тĕп страницă',
'policy-url'        => 'Project:Йĕркесем',
'portal-url'        => 'Project:Портал',
'privacy'           => 'Конфиденциальность йĕркисем',
'privacypage'       => 'Project:Конфиденциальность йĕркисем',
'sitesupport'       => 'Пожертвованисем',
'sitesupport-url'   => 'Project:Нимелĕх',

'badaccess' => 'Доступ йăнăшĕ',

'versionrequiredtext' => 'Ку страницăпа ĕслемешкĕн сире MediaWiki-н $1 версийĕ кирлĕ. [[Special:Version|Усă куракан программăсен версийĕсем çинчен пĕлтерекен информацине]] пăх.',

'ok'                      => 'OK',
'retrievedfrom'           => 'Çăлкуç — «$1»',
'youhavenewmessages'      => 'Сирĕн $1 пур ($2).',
'youhavenewmessagesmulti' => '$1-та çĕнĕ пĕлтерӳсем пур.',
'toc'                     => 'Тупмалли',
'hidetoc'                 => 'кӑтартмалла мар',
'restorelink'             => '$1 кăларса пăрахнă тӳрлетĕве',
'feedlinks'               => 'Çапла кур:',
'feed-invalid'            => 'Çырăнмашкăн ку канал тĕсĕ каймасть.',
'feed-unavailable'        => '{{SITENAME}} сайтри синдикаци хăйăвĕсем тупăнмарĕç',
'site-rss-feed'           => '$1 — RSS-хăю',
'site-atom-feed'          => '$1 - Atom хăю',
'page-rss-feed'           => '«$1» - RSS хăю',
'page-atom-feed'          => '«$1» - Atom хăю',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Статья',
'nstab-user'      => 'Хутшăнакан страници',
'nstab-special'   => 'Ятарлă',
'nstab-image'     => 'Файл',
'nstab-mediawiki' => 'Пĕлтерӳ',
'nstab-template'  => 'Шаблон',
'nstab-help'      => 'пулăшу',

# General errors
'databaseerror'        => 'Пĕлĕм пуххин йăнăшĕ',
'dberrortext'          => 'Пĕлĕм пуххине янă ыйтăвĕнче синтаксис йăнăшĕ пур.
Пĕлĕм пуххине янă юлашки ыйту:
<blockquote><tt>$1</tt></blockquote>
<tt>«$2»</tt> функци ыйтнă.
MySQL çак йăнăша тавăрнă <tt>«$3: $4»</tt>.',
'dberrortextcl'        => 'Пĕлĕм пуххине янă ыйтăвĕнче синтаксис йăнăшĕ пур.
Пĕлĕм пуххине янă юлашки ыйту:
«$1»
«$2» функци ыйтнă.
MySQL çак йăнăша тавăрнă «$3: $4».',
'noconnect'            => 'Каçарăр та, хальхи вăхăтра техника йăнăшне пула пĕлĕм пуххин серверĕпе çыханаймастпăр.<br />
$1',
'nodb'                 => '$1 пĕлĕм пуххине усă курма май çук',
'cachederror'          => 'Аяларах эсир ыйтнă страницăн кэшри копине куратăр; вăл кивелме те пултарнă.',
'laggedslavemode'      => 'Асăрхăр! Страница çинче юлашки улшăнусене кăтартмасăр пултарнă.',
'readonly'             => 'Пĕлĕм пуххине çырассине чарса хунă',
'enterlockreason'      => 'Чарнин сăлтавне тата палăртнă вăхăта кăтартăр.',
'readonlytext'         => 'Çĕнĕ статьясене кĕртессипе улăштарассине чарнă: план тăрăх иртекен ĕçсем пулма пултараççĕ.
Ĕçлеме чарнă опаратор çак ăнлантарăва хăварнă:
$1',
'readonly_lag'         => 'Иккĕмĕш шайри пĕлĕм пуххи серверĕ пĕрремĕш шайрипе синхронизацилениччен пĕлĕм пуххине улшăнусем кĕртессине чарса лартнă.',
'internalerror_info'   => 'Шалти йăнăш: $1',
'filecopyerror'        => '«$1» файла «$2» çине копилеймерĕмер.',
'directorycreateerror' => '«$1» директорине тума май çук.',
'fileexistserror'      => '«$1» файла çырма май çук: ку ятлă файл пур.',
'unexpected'           => 'Тĕрĕс мар пĕлтерĕш: «$1»=«$2».',
'formerror'            => 'Йăнăш: формăри даннăйсене леçме май çук',
'cannotdelete'         => 'Эсир кăтартнă страницăна е файла кăларса пăрахаймастпăр. Ăна, тен, урăххи кăларса пăрахнă?',
'badtitletext'         => 'Эсир кăтартнă статья ячĕ тĕрĕс мар, пушă, е чĕлхе хушшинчипе интервики ячĕ тĕрĕс мар. Ятра усă курма юраман паллăсене çырнинче пулма пултарать.',
'perfdisabled'         => 'Каçарăр та, сервер йывăррăн ĕçленине пула ку ĕçе вăхăтлăха тума май çук.',
'perfcached'           => 'Ку даннăйсене кэшран илнĕ, çавна май унта юлашки улшăнусем палăрмасăр пултараççĕ.',
'perfcachedts'         => 'Ку даннăйсене кэшран илнĕ, юлашки хут вăл $1 вăхăтра çĕнелнĕ.',
'querypage-no-updates' => 'Ку страницăна хальхи вăхăтра улăштарма чарнă. Ку даннăйсене хальхи вăхăтра çĕнетме май çук.',
'wrong_wfQuery_params' => 'wfQuery() функцине тĕрĕс мар параметрсем панă<br />
Функци: $1<br />
Ыйту: $2',
'namespaceprotected'   => 'Сирĕн «$1» ят уçлăхĕнчи статьясене тӳрлетмелли май çук..',
'customcssjsprotected' => 'Ку страницăра тепĕр хутшăнаканăн ятарлă ĕнерлевĕсем вырнаçнă, çавна май эсир ăна тӳрлетейместĕр.',
'ns-specialprotected'  => '«{{ns:special}}» ят уçлăхĕнчи страницăсене эсир тӳрлетейместĕр.',
'titleprotected'       => "Ку ятлă страницăна хатĕрлессине [[Хутшăнакан:$1|$1]] хутшăнакан чарса хунă.
Çак сăлтава кăтартнă: ''$2''.",

# Login and logout pages
'logouttitle'               => 'Сайтран тух',
'logouttext'                => 'Эсир сире палласа илмен пек ĕçлетĕр. Сире сайт ятпа мар, IP-адрес урлă паллать. Эсир анонимла, е малтанхи евĕрлĕ çĕнĕ сеанс уçса, е  урăх ятпа ĕçлеме пултаратăр. Хăш-пĕр страницăсем эсир сайта кĕнĕ пек курăнма пултараççĕ, ăна тӳрлетмешкĕн браузер кэшне çĕнетĕр.',
'yourpassword'              => 'Вăрттăн сăмах:',
'yourpasswordagain'         => 'Вăрттăн сăмах тепре çырăр:',
'loginproblem'              => '<span style="color:red">Хутшăнакана палаймарăмăр.</span>',
'loginprompt'               => 'Сайта кĕрес тесен эсир «cookies» усă курма чармалла мар.',
'gotaccount'                => 'Эсир регистрациленсе-и? $1.',
'gotaccountlink'            => 'Сайта кĕр',
'createaccountmail'         => 'эл. почта тăрăх',
'youremail'                 => 'Электронлă почта:',
'yournick'                  => 'Сирĕн ят (алă пусмашкăн):',
'email'                     => 'Эл. почта',
'loginerror'                => 'Хутшăнакана палласа илеймерĕмĕр',
'prefs-help-email'          => 'Электронлă почта (вăл кирлисем шутне кĕмест пулин те) ытти хутшăнакансене сирĕнпе ун урлă çыхăнма май парать. Çыхăну тытнă вăхăтра ыттисем сирĕн адреса пĕлеймеççĕ.',
'prefs-help-email-required' => 'Электронлă почтăн адресне кăтартмалла.',
'loginsuccesstitle'         => 'Сайта кĕтĕмĕр',
'wrongpassword'             => 'Эсир кăтартнă вăрттăн сăмах тĕрĕс мар. Урăххине кăтартăр.',
'mailmypassword'            => 'Çĕнĕ вăрттăн сăмаха ярса ил',
'passwordremindertitle'     => '{{grammar:genitive|{{SITENAME}}}} хутшăнаканăн вăрттăн сăмахне асаилтересси',
'passwordsent'              => 'Çĕнĕ вăрттăн сăмаха $1 хутшăнакан кăтартнă эл. почтăн адресĕпе ятăмар.

Вăрттăн сăмаха илнĕ хыççăн, тархасшăн, сайта çĕнĕрен кĕрĕр.',
'mailerror'                 => 'Çыру яраймарăмăр, йăнăш тухрĕ: $1',
'emailconfirmlink'          => 'Хăвăр эл. почтăн адресне çирĕплетĕр',

# Password reset dialog
'resetpass_header'  => 'Пароле пăрахăçла',
'resetpass_missing' => 'Формăра даннăйсем çук.',

# Edit page toolbar
'bold_sample'   => 'Çурмахулăм текст',
'bold_tip'      => 'Çурмахулăм текст',
'italic_sample' => 'Тайлăк текст',
'italic_tip'    => 'Тайлăк текст',
'math_sample'   => 'Формулăна кунта кĕртĕр',
'math_tip'      => 'Математика формули (LaTeX форматпа)',
'nowiki_sample' => 'Кунта фотматламан текста кĕртĕр.',
'nowiki_tip'    => 'Вики-ĕрешлевне пăхмалла мар',
'media_tip'     => 'Медиа-файл çине каçă',
'sig_tip'       => 'Сирĕн алă пусни тата вăхăт',

# Edit pages
'summary'               => 'Улшăнусен кĕске ăнлантарăвĕ',
'minoredit'             => 'Кунта пěчěк улшăнусем кăна кěртнě',
'watchthis'             => 'Ку страницăна кěртекен  улшăнусем  хыççăн сăнамалла',
'missingcommenttext'    => 'Аяларах, тархасшăн, хăвар пĕлтерĕве çырăр.',
'blockedtext'           => "<big>'''Ку аккаунта е IP-адреса ĕçлеме чарнă.'''</big>

$1 администратор ĕçлеме чарнă. Сăлтавĕ çакă: ''«$2»''.

* Чарнă вăхăт: $8
* Çак вăхăтчен чарнă: $6
* Çакна чарнă: $7

$1 хутшăнакан патне, е ытти [[{{MediaWiki:Grouppage-sysop}}|администраторсем]] патне эсир çыру ярса меншĕн ĕçлеме чарнине сӳтсе явма пултаратăр.

Асăрхар: [[{{ns:special}}:Preferences|хăвăрăн ĕнерлевсенче]] эл. почта адресне çирĕплетмен пулсан, е сайта кĕмен пулсан — администратор патне çыру яраймастăр. Администратор сире çыру яма чарнă пулсан — ку хутĕнче те çыру яраймăр.

Сирĕн IP-адрес — $3, чару идентификаторĕ — #$5. Çырусенче, тархасшăн, вĕсене пĕлтерĕр.",
'loginreqtitle'         => 'Сайта кĕмелле',
'loginreqlink'          => 'сайта кĕр',
'loginreqpagetext'      => 'Ытти страницăсене курмашкăн сирĕн $1.',
'accmailtext'           => '$1 вăрттăн сăмахне кунта леçрĕмĕр: $2.',
'note'                  => '<strong>Ăнлантаркăч:</strong>',
'previewnote'           => '<strong>Ку страницăна халлěхе çырса хуман. Эсир ку страницă мěнле пулассине кăна куратăр!</strong>',
'editing'               => '$1 тӳрлетни',
'editinguser'           => "тӳрлетни '''[[User:$1|$1]]''' ([[User talk:$1|{{int:talkpagelinktext}}]] | [[Special:Contributions/$1|{{int:contribslink}}]])",
'editingsection'        => '$1 тӳрлетни (статья разделě)',
'editingcomment'        => '$1 тӳрлетни (кӗске анлантарӑвӗ)',
'editconflict'          => 'Тӳрлетнĕ вăхăтра тавлашу тухрĕ: $1',
'titleprotectedwarning' => '<strong>Асăрхаттару. Ку страницăпа ĕçлеме чарнă, ăна хăш-пĕр хутшăнакан кăна хатерлеме пултарать.</strong>',
'templatesused'         => 'Ку страница çинче усă курнă шаблонсем:',
'nocreatetitle'         => 'Страницăсене хатĕрлессине чакарнă',

# History pages
'revisionasof'     => '$1 верси',
'revision-info'    => '$1 версийĕ; $2',
'previousrevision' => '&larr;Малтанхи верси',
'nextrevision'     => 'Çěнěрех верси→',
'next'             => 'тепěр',
'deletedrev'       => '[кăларса пăрахнă]',
'histfirst'        => 'Пĕрремĕш',
'histlast'         => 'Юлашки',
'historysize'      => '$1 байт',

# Revision deletion
'rev-deleted-comment' => '(комментарине кăларса пăрахнă)',
'rev-deleted-user'    => '(автор ятне хуратнă)',

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

# Recent changes linked
'recentchangeslinked' => 'Çыхăннă улшăнусем',

# Upload
'upload'         => 'Файла кĕртесси',
'uploadlog'      => 'Файлсене кĕртнин логĕ',
'uploadedfiles'  => 'Кĕртнĕ файлсем',
'ignorewarnings' => 'Асăрхаттарусене шута илмелле мар',
'uploaddisabled' => 'Каçарăр та сайта халĕ нимĕн те кĕртме юрамаст.',

# Image list
'imagelist' => 'Ӳкерчěксен списокě',
'ilsubmit'  => 'Шырамалла',

# Random page
'randompage' => 'Ăнсăртран илнě страницă',

# Miscellaneous special pages
'nviews'                  => '$1 хут пăхнă',
'uncategorizedpages'      => 'Каталогсăр страницăсем',
'uncategorizedcategories' => 'Каталога кĕртмен категорисем',
'allpages'                => 'Пěтěм страницăсем',
'deadendpages'            => 'Ниăçта та урăх ертмен страницăсем',
'listusers'               => 'Хутшăнакансен списокĕ',
'specialpages'            => 'Ятарлă страницăсем',
'spheading'               => 'Пěтěм пользовательсем валли ятарлă страницăсем',
'newpages'                => 'Çěнě страницăсем',
'ancientpages'            => 'Чи кивĕ статьясем',
'notargettitle'           => 'Тĕллевне кăтартман',

'categoriespagetext' => 'Викинче çак категорисем пур.',
'data'               => 'Кун',

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
'others' => 'ыттисем',

# Spam protection
'listingcontinuesabbrev' => '(малалли)',

# Info page
'numedits' => 'Улшăнусен шучĕ (статьясем): $1',

# Trackbacks
'trackbackremove' => ' ([$1 кăларса пăрах])',

);
