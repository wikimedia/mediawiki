<?php
/** Belarusian (Беларуская мова)
  *
  * @package MediaWiki
  * @subpackage Language
  *
  * @author Ævar Arnfjörð Bjarmason <avarab@gmail.com>
  * @bug 1638, 2135
  * @link http://be.wikipedia.org/wiki/Talk:LanguageBe.php
  * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License
  * @license http://www.gnu.org/copyleft/fdl.html GNU Free Documentation License
  */

require_once('LanguageUtf8.php');

/* private */ $wgNamespaceNamesBe = array(
	NS_MEDIA		=> 'Мэдыя',
	NS_SPECIAL		=> 'Спэцыяльныя',
	NS_MAIN			=> '',
	NS_TALK			=> 'Абмеркаваньне',
	NS_USER			=> 'Удзельнік',
	NS_USER_TALK		=> 'Гутаркі_ўдзельніка',
	NS_PROJECT		=> $wgMetaNamespace,
	NS_PROJECT_TALK		=> 'Абмеркаваньне_' . $wgMetaNamespace,
	NS_IMAGE		=> 'Выява',
	NS_IMAGE_TALK		=> 'Абмеркаваньне_выявы',
	NS_MEDIAWIKI		=> 'MediaWiki',
	NS_MEDIAWIKI_TALK	=> 'Абмеркаваньне_MediaWiki',
	NS_TEMPLATE		=> 'Шаблён',
	NS_TEMPLATE_TALK	=> 'Абмеркаваньне_шаблёну',
	NS_HELP			=> 'Дапамога',
	NS_HELP_TALK		=> 'Абмеркаваньне_дапамогі',
	NS_CATEGORY		=> 'Катэгорыя',
	NS_CATEGORY_TALK	=> 'Абмеркаваньне_катэгорыі'
);

/* private */ $wgQuickbarSettingsBe = array(
	'Не паказваць', 'Замацаваная зьлева', 'Замацаваная справа', 'Рухомая зьлева'
);

/* private */ $wgSkinNamesBe = array(
	'standard' => 'Клясычны',
	'nostalgia' => 'Настальгія',
	'cologneblue' => 'Кёльнскі смутак',
	'davinci' => 'Да Вінчы',
	'mono' => 'Мона',
	'monobook' => 'Монакніга',
	'myskin' => 'MySkin',
	'chick' => 'Цыпа'
) + $wgSkinNamesEn;

/* private */ $wgMagicWordsBe = array(
	MAG_REDIRECT		=> array( 0,	'#redirect', '#перанакіраваньне' ),
	MAG_NOTOC		=> array( 0,	'__NOTOC__', '__БЯЗЬ_ЗЬМЕСТУ__' ),
	MAG_FORCETOC		=> array( 0,	'__FORCETOC__', '__ЗЬМЕСТ_ПРЫМУСАМ__' ),
	MAG_TOC			=> array( 0,	'__TOC__', '__ЗЬМЕСТ__'  ),
	MAG_NOEDITSECTION	=> array( 0,	'__NOEDITSECTION__', '__БЕЗ_РЭДАГАВАНЬНЯ_СЭКЦЫІ__' ),
	MAG_START		=> array( 0,	'__START__', '__ПАЧАТАК__'),
	MAG_CURRENTMONTH	=> array( 1,	'CURRENTMONTH', 'БЯГУЧЫ_МЕСЯЦ'),
	MAG_CURRENTMONTHNAME	=> array( 1,	'CURRENTMONTHNAME', 'НАЗВА_БЯГУЧАГА_МЕСЯЦА'),
	MAG_CURRENTDAY		=> array( 1,	'CURRENTDAY', 'БЯГУЧЫ_ДЗЕНЬ'),
	MAG_CURRENTDAYNAME	=> array( 1,	'CURRENTDAYNAME', 'НАЗВА_БЯГУЧАГА_ДНЯ'),
	MAG_CURRENTYEAR		=> array( 1,	'CURRENTYEAR', 'БЯГУЧЫ_ГОД'),
	MAG_CURRENTTIME		=> array( 1,	'CURRENTTIME', 'БЯГУЧЫ_ЧАС'),
	MAG_NUMBEROFARTICLES	=> array( 1,	'NUMBEROFARTICLES', 'КОЛЬКАСЬЦЬ_АРТЫКУЛАЎ'),
	MAG_CURRENTMONTHNAMEGEN	=> array( 1,	'CURRENTMONTHNAMEGEN', 'НАЗВА_БЯГУЧАГА_МЕСЯЦА_Ў_РОДНЫМ_СКЛОНЕ' ),
	MAG_PAGENAME		=> array( 1,	'PAGENAME', 'НАЗВА_СТАРОНКІ' ),
	MAG_PAGENAMEE		=> array( 1,	'PAGENAMEE', 'НАЗВА_СТАРОНКІ_2' ),
	MAG_NAMESPACE		=> array( 1,	'NAMESPACE', 'ПРАСТОРА_НАЗВАЎ'),
	MAG_SUBST		=> array( 0,	'SUBST:', 'ПАДСТАНОЎКА:'),
	MAG_MSGNW		=> array( 0,	'MSGNW:', 'ПАВЕДАМЛЕНЬНЕ_БЯЗЬ_ВІКІ:' ),
	MAG_END			=> array( 0,	'__END__', '__КАНЕЦ__'   ),
	MAG_IMG_THUMBNAIL	=> array( 1,	'thumbnail', 'thumb', 'значак', 'міні'),
	MAG_IMG_RIGHT		=> array( 1,	'right', 'справа'	),
	MAG_IMG_LEFT		=> array( 1,	'left', 'зьлева'	 ),
	MAG_IMG_NONE		=> array( 1,	'none', 'няма'	   ),
	MAG_IMG_WIDTH		=> array( 1,	'$1px', '$1пкс'		   ),
	MAG_IMG_CENTER		=> array( 1,	'center', 'centre', 'цэнтар' ),
	MAG_IMG_FRAMED		=> array( 1,	'framed', 'enframed', 'frame', 'рамка' ),
	MAG_INT			=> array( 0,	'INT:'		   ),
	MAG_SITENAME		=> array( 1,	'SITENAME', 'НАЗВА_САЙТУ'),
	MAG_NS			=> array( 0,	'NS:', 'ПН:' ),
	MAG_LOCALURL		=> array( 0,	'LOCALURL:', 'ЛЯКАЛЬНЫ_АДРАС:' ),
	MAG_LOCALURLE		=> array( 0,	'LOCALURLE:', 'ЛЯКАЛЬНЫ_АДРАС_2' ),
	MAG_SERVER		=> array( 0,	'SERVER', 'СЭРВЭР' ),
	MAG_GRAMMAR		=> array( 0,	'GRAMMAR:', 'ГРАМАТЫКА:'	),
	MAG_NOTITLECONVERT	=> array( 0,	'__NOTITLECONVERT__', '__NOTC__', '__БЕЗ_КАНВЭРТАЦЫІ_НАЗВЫ__'),
	MAG_NOCONTENTCONVERT	=> array( 0,	'__NOCONTENTCONVERT__', '__NOCC__', '__БЕЗ_КАНВЭРТАЦЫІ_ТЭКСТУ__'),
	MAG_CURRENTWEEK		=> array( 1,	'CURRENTWEEK', 'БЯГУЧЫ_ТЫДЗЕНЬ'),
	MAG_CURRENTDOW		=> array( 1,	'CURRENTDOW', 'БЯГУЧЫ_ДЗЕНЬ_ТЫДНЯ'),
);

/* private */ $wgAllMessagesBe = array(
# Belarusian Cyrillic alphabet:
# Аа Бб Вв Гг Дд (ДЖдж ДЗдз) Ее Ёё Жж Зз Іі Йй Кк Лл Мм Нн Оо Пп Рр Сс Тт Уу Ўў Фф Хх Цц Чч Шш Ыы Ьь Ээ Юю Яя
# Short ([^a-z]): абвгд (ДЖдж ДЗдз) еёжзійклмнопрстуўфхцчшыьэюя
#
# Belarusian Latin alphabet:
# Aa Bb Cc Ćć Čč Dd (DŽdž DZdz) Ee Ff Gg Hh Ii Jj Kk Ll Łł Mm Be Ńń Oo Pp Rr Ss Śś Šš Tt Uu Ŭŭ Vv Yy Zz Źź Žž
# Short ([^a-z]): ćč (DŽdž)  łńśšŭźž

# Note: use /u (unicode) and /i to turn of case-sensativity.
'linktrail' => '/^([абвгґджзеёжзійклмнопрстуўфхцчшыьэюяćčłńśšŭźža-z]+)(.*)$/sDu',

'1movedto2' => '$1 перанесеная ў $2',
'1movedto2_redir' => '$1 перанесеная ў $2, выдаліўшы перанакіраваньне',
'about' => 'Пра',
'aboutpage' => 'Project:Пра {{SITENAME}}',
'aboutsite' => 'Пра {{SITENAME}}',
'accmailtext' => 'Пароль для \'$1\' быў адасланы па адрасу $2.',
'accmailtitle' => 'Пароль адасланы.',
'addedwatch' => 'Даданая ў сьпіс назіраньня',
'addgroup' => 'Дадаць групу',
'administrators' => 'Project:Адміністрацыя',
'allarticles' => 'Усе артыкулы',
'allmessages' => 'Усе сыстэмныя паведамленьні',
'allmessagescurrent' => 'Бягучы тэкст',
'allmessagesname' => 'Назва',
'allmessagestext' => 'Сьпіс усіх сыстэмных паведамленьняў, якія існуюць у прасторы назваў \'\'\'MediaWiki:\'\'\'.',
'allpages' => 'Усе старонкі',
'allpagesnext' => 'Наступныя',
'allpagesprev' => 'Папярэднія',
'allpagessubmit' => 'Паказаць',
'alphaindexline' => 'ад $1 да $2',
'ancientpages' => 'Найстарэйшыя старонкі',
'and' => 'і',
'anonymous' => 'Ананімныя ўдзельнікі і ўдзельніцы {{SITENAME}}',
'apr' => '04',
'april' => 'красавіка',
'article' => 'Артыкул',
'articlepage' => 'Паказаць артыкул',
'aug' => '08',
'august' => 'жніўня',
'badfilename' => 'Назва выявы была зьмененая на «$1».',
'badfiletype' => '«.$1» не зьяўляецца рэкамэндаваным фарматам для файлаў выяваў.',
'badipaddress' => 'Некарэктны IP адрас',
'badtitle' => 'Некарэктная назва',
'blanknamespace' => 'Артыкул',
'bydate' => 'па даце',
'byname' => 'па назьве',
'bysize' => 'па памеры',
'cancel' => 'Адмяніць',
'cannotdelete' => 'Немагчыма выдаліць указаную старонку альбо выяву. (Магчыма, яна ўжо выдаленая кімсьці іншым.)',
'categories' => 'Катэгорыі',
'categoriespagetext' => 'У вікі існуюць наступныя катэгорыі:',
'category' => 'катэгорыя',
'category_header' => 'Артыкулы ў катэгорыі \'$1\'',
'categoryarticlecount' => 'У катэгорыі ёсьць $1 артыкул(а,аў).',
'categoryarticlecount1' => 'У катэгорыі ёсьць $1 артыкул.',
'changepassword' => 'Зьмяніць пароль',
'compareselectedversions' => 'Параўнаць выбраныя вэрсіі',
'confirmprotecttext' => 'Вы сапраўды жадаеце абараніць гэтую старонку?',
'contribslink' => 'унёсак',
'contributions' => 'Унёсак удзельніка/удзельніцы',
'copyright' => 'Зьмест старонкі падпадае пад ліцэнзію $1.',
'copyrightpage' => 'Project:Аўтарскія правы',
'copyrightpagename' => 'Аўтарскія правы {{SITENAME}}',
'copyrightwarning' => '<strong>НІ Ў ЯКІМ РАЗЕ НЕ СТАЎЦЕ БЕЗ ДАЗВОЛУ ТЭКСТЫ, ЯКІЯ АБАРОНЕНЫЯ АЎТАРСКІМ ПРАВАМ</strong><br />
Please note that all contributions to {{SITENAME}} are
considered to be released under the $2
 (see $1 for details).
If you don\'t want your writing to be edited mercilessly and redistributed
at will, then don\'t submit it here.<br />
You are also promising us that you wrote this yourself, or copied it from a
public domain or similar free resource.<br />
<strong>DO NOT SUBMIT COPYRIGHTED WORK WITHOUT PERMISSION!</strong>',
'couldntremove' => 'Немагчыма выдаліць «$1»...',
'createaccount' => 'Стварыць новы рахунак',
'createaccountmail' => 'па электроннай пошце',
'creditspage' => 'Падзякі',
'cur' => 'бяг',
'currentevents' => 'Бягучыя падзеі',
'currentevents-url' => 'Бягучыя падзеі',
'currentrev' => 'Бягучая вэрсія',
'currentrevisionlink' => 'паказаць бягучую вэрсію',
'data' => 'Дадзеныя',
'databaseerror' => 'Памылка базы дадзеных',
'dateformat' => 'Фармат даты',
'deadendpages' => 'Тупіковыя старонкі',
'dec' => '12',
'december' => 'сьнежня',
'delete' => 'Выдаліць',
'deletecomment' => 'Прычына выдаленьня',
'deletedarticle' => 'выдалены «$1»',
'deletedrevision' => 'Выдаленая старая вэрсія $1.',
'deleteimg' => 'выдаліць',
'deleteimgcompletely' => 'Выдаліць усе вэрсіі',
'deletepage' => 'Выдаліць старонку',
'deletesub' => '(Выдаленьне «$1»)',
'deletethispage' => 'Выдаліць гэтую старонку',
'dellogpagetext' => 'Сьпіс апошніх выдаленьняў.',
'diff' => 'розьн',
'difference' => '(Адрозьненьні паміж вэрсіямі)',
'disambiguationstext' => 'The following pages link to a <i>disambiguation page</i>. They should link to the appropriate topic instead.<br />A page is treated as dismbiguation if it is linked from $1.<br />Links from other namespaces are <i>not</i> listed here.',
'disclaimerpage' => 'Project:Адмова ад адказнасьці',
'disclaimers' => 'Адмова ад адказнасьці',
'doubleredirects' => 'Двайныя перанакіраваньні',
'edit' => 'Рэдагаваць',
'editconflict' => 'Канфлікт рэдагаваньня: $1',
'editcurrent' => 'Рэдагаваць бягучую вэрсію гэтага артыкула',
'editgroup' => 'Рэдагаваць групу',
'edithelp' => 'Дапамога ў рэдагаваньні',
'edithelppage' => 'Дапамога:Рэдагаваньне',
'editing' => 'Рэдагаваньне: $1',
'editingcomment' => 'Рэдагаваньне: $1 (камэнтар)',
'editingsection' => 'Рэдагаваньне: $1 (сэкцыя)',
'editsection' => 'рэдагаваць',
'editthispage' => 'Рэдагаваць гэтую старонку',
'editusergroup' => 'Рэдагаваць групы ўдзельнікаў і ўдзельніц',
'emailflag' => 'Не прымаць электронную пошту ад іншых удзельнікаў і ўдзельніц',
'emailfrom' => 'Ад',
'emailmessage' => 'Паведамленьне',
'emailpage' => 'Даслаць ліст ўдзельніку ці ўдзельніцы па электроннай пошце',
'emailsend' => 'Даслаць',
'emailsubject' => 'Тэма',
'emailto' => 'Каму',
'emailuser' => 'Даслаць ліст па электроннай пошце гэтаму ўдзельніку/гэтай удзельніцы',
'error' => 'Памылка',
'errorpagetitle' => 'Памылка',
'exblank' => 'старонка была пустая',
'export' => 'Экспартаваць старонкі',
'exportcuronly' => 'Экспартаваць толькі бягучую вэрсію, бяз поўнай гісторыі',
'extlink_tip' => 'Зьнешняя спасылка (не забывайцеся пачынаць з http:// )',
'feb' => '02',
'february' => 'лютага',
'filecopyerror' => 'Немагчыма cкапіяваць файл «$1» у «$2».',
'filedeleteerror' => 'Немагчыма выдаліць файл «$1».',
'filedesc' => 'Апісаньне',
'filename' => 'Назва файла',
'filenotfound' => 'Немагчыма знайсьці файл «$1».',
'filerenameerror' => 'Немагчыма перайменаваць файл «$1» у «$2».',
'filesource' => 'Крыніца',
'friday' => 'пятніца',
'geo' => 'Геаграфічныя каардынаты',
'getimagelist' => 'атрыманьне сьпісу выяваў',
'go' => 'Старонка',
'help' => 'Дапамога',
'helppage' => 'Дапамога:Зьмест',
'hide' => 'схаваць',
'hidetoc' => 'схаваць',
'hist' => 'гіст',
'history' => 'Гісторыя старонкі',
'history_short' => 'Гісторыя',
'historywarning' => 'Папярэджаньне: у старонкі, якую Вы зьбіраецеся выдаліць, ёсьць гісторыя:',
'hr_tip' => 'Гарызантальная лінія (не выкарыстоўвайце часта)',
'ignorewarning' => 'Праігнараваць папярэджаньне і захаваць файл.',
'illegalfilename' => 'Назва файла «$1» зьмяшчае сымбалі, якія нельга выкарыстоўваць у назвах старонак. Калі ласка, зьмяніце назву файла і паспрабуйце загрузіць яго зноў.',
'ilsubmit' => 'Шукаць',
'image_sample' => 'Прыклад.jpg',
'imagelist' => 'Сьпіс выяваў',
'imagelisttext' => 'Сьпіс $1 выяваў, адсартаваных $2.',
'imagepage' => 'Паказаць старонку выявы',
'imgdelete' => 'выдаліць',
'imgdesc' => 'апісаньне',
'imghistory' => 'Гісторыя выявы',
'import' => 'Імпартаваць старонкі',
'importfailed' => 'Немагчыма імпартаваць: $1',
'info_short' => 'Інфармацыя',
'infosubtitle' => 'Інфармацыя пра старонку',
'internalerror' => 'Унутраная памылка',
'ip_range_invalid' => 'Некарэктны дыяпазон IP адрасоў.',
'ipaddress' => 'IP адрас/Імя ўдзельніка/ўдзельніцы',
'ipbreason' => 'Прычына',
'isredirect' => 'старонка-перанакіраваньне',
'jan' => '01',
'january' => 'студзеня',
'jul' => '07',
'july' => 'ліпеня',
'jun' => '06',
'june' => 'чэрвеня',
'lastmodified' => 'Гэтая старонка апошні раз рэдагавалася $1.',
'lastmodifiedby' => 'Гэтую старонку апошні раз рэдагаваў $2 $1.',
'link_tip' => 'Унутраная спасылка',
'linklistsub' => '(Сьпіс спасылак)',
'linkshere' => 'Наступныя старонкі спасылаюцца на гэтую:',
'linkstoimage' => 'Наступныя старонкі спасылаюцца на гэтую выяву:',
'listform' => 'сьпіс',
'listusers' => 'Сьпіс удзельнікаў і ўдзельніц',
'loadhist' => 'Загрузка гісторыі старонкі',
'loadingrev' => 'Загрузка вэрсіі для параўнаньня',
'localtime' => 'Мясцовы час',
'logout' => 'Выйсьці',
'lonelypages' => 'Старонкі-сіраціны',
'longpages' => 'Доўгія старонкі',
'mailmypassword' => 'Даслаць мне новы пароль',
'mainpage' => 'Галоўная старонка',
'maintenance' => 'Старонка падтрымкі',
'makesysopname' => 'Імя ўдзельніка/ўдзельніцы:',
'mar' => '03',
'march' => 'сакавіка',
'math_sample' => 'Зьмясьціце тут формулу',
'math_syntax_error' => 'сынтаксычная памылка',
'math_tip' => 'Матэматычная формула (LaTeX)',
'math_unknown_error' => 'невядомая памылка',
'math_unknown_function' => 'невядомая функцыя',
'may' => '05',
'may_long' => 'траўня',
'media_sample' => 'Прыклад.ogg',
'media_tip' => 'Спасылка на мэдыя-файл',
'minlength' => 'Назва выявы павінна быць не карацейшай за тры сымбалі.',
'minoredit' => 'Гэта дробная праўка',
'minoreditletter' => 'Д',
'monday' => 'панядзелак',
'move' => 'Перанесьці',
'movearticle' => 'Перанесьці старонку',
'movedto' => 'перанесеная ў',
'movepage' => 'Перанесьці старонку',
'movepagebtn' => 'Перанесьці старонку',
'movetalk' => 'Перанесьці таксама старонку «абмеркаваньня», калі гэта магчыма.',
'movethispage' => 'Перанесьці гэтую старонку',
'mw_math_html' => 'HTML калі магчыма, інакш PNG',
'mw_math_mathml' => 'MathML калі магчыма (экспэрымэнтальна)',
'mw_math_simple' => 'HTML у простых выпадках, інакш PNG',
'mycontris' => 'Мой унёсак',
'mypage' => 'Мая старонка',
'mytalk' => 'Мае размовы',
'navigation' => 'Навігацыя',
'newarticle' => '(Новы)',
'newbies' => 'Пачынаючыя ўдзельнікі і ўдзельніцы',
'newimages' => 'Галерэя новых выяваў',
'newmessages' => 'Вы атрымалі $1.',
'newmessageslink' => 'новыя паведамленьні',
'newpage' => 'Новая старонка',
'newpageletter' => 'Н',
'newpages' => 'Новыя старонкі',
'newpassword' => 'Новы пароль',
'newtitle' => 'Новая назва',
'newusersonly' => ' (толькі для новых удзельнікаў і ўдзельніц)',
'newwindow' => '(адчыняецца ў новым акне)',
'nextdiff' => 'Перайсьці да наступнай зьмены →',
'nextn' => 'наступныя $1',
'nextpage' => 'Наступная старонка ($1)',
'noarticletext' => '(Зараз тэкст на гэтай старонцы адсутнічае)',
'nodb' => 'Немагчыма выбраць базу дадзеных $1',
'noemailtitle' => 'Адрас электроннай пошты адсутнічае',
'nogomatch' => 'Старонкі з гэткай назвай не існуе.',
'nohistory' => 'Гісторыя зьменаў для гэтай старонкі адсутнічае.',
'noimages' => 'Выявы адсутнічаюць.',
'nolinkshere' => 'Ніводная старонка сюды не спасылаецца.',
'nolinkstoimage' => 'Ніводная старонка не спасылаецца на гэтую выяву.',
'nosuchaction' => 'Няма такога дзеяньня',
'nosuchspecialpage' => 'Такой спэцыяльнай старонкі не існуе',
'nosuchuser' => 'Не існуе ўдзельніка ці ўдзельніцы «$1».
Праверце напісаньне, альбо выкарыстайце форму ніжэй, каб стварыць новы рахунак ўдзельніка ці ўдзельніцы.',
'nosuchusershort' => 'Не існуе ўдзельніка ці ўдзельніцы «$1». Праверце напісаньне.',
'notanarticle' => 'Не артыкул',
'note' => '<strong>Заўвага: </strong>',
'nov' => '11',
'november' => 'лістапада',
'nowatchlist' => 'Ваш сьпіс назіраньня — пусты.',
'nowiki_sample' => 'Пішыце сюды нефарматаваны тэкст',
'nowiki_tip' => 'Ігнараваць вікі-фарматаваньне',
'nstab-category' => 'Катэгорыя',
'nstab-help' => 'Дапамога',
'nstab-image' => 'Выява',
'nstab-main' => 'Артыкул',
'nstab-media' => 'Мэдыя',
'nstab-mediawiki' => 'Паведамленьне',
'nstab-special' => 'Спэцыяльная',
'nstab-template' => 'Шаблён',
'nstab-user' => 'Старонка ўдзельніка/ўдзельніцы',
'nstab-wp' => 'Пра',
'numauthors' => 'Колькасьць розных аўтараў і аўтарак (артыкула): $1',
'numedits' => 'Колькасьць зьменаў (артыкула): $1',
'numtalkauthors' => 'Колькасьць розных аўтараў і аўтарак (старонкі абмеркаваньня): $1',
'numtalkedits' => 'Колькасьць зьменаў (старонкі абмеркаваньня): $1',
'numwatchers' => 'Колькасьць назіральнікаў і назіральніц: $1',
'nviews' => '$1 прагляд(у,аў)',
'oct' => '10',
'october' => 'кастрычніка',
'ok' => 'Добра',
'oldpassword' => 'Стары пароль',
'orig' => 'арыг',
'orphans' => 'Старонкі-сіраціны',
'otherlanguages' => 'На іншых мовах',
'others' => 'іншыя',
'pagemovedtext' => 'Старонка «[[$1]]» перанесеная ў «[[$2]]».',
'pagetitle' => '$1 - {{SITENAME}}',
'popularpages' => 'Папулярныя старонкі',
'portal' => 'Суполка',
'portal-url' => 'Project:Суполка',
'postcomment' => 'Пракамэнтаваць',
'preferences' => 'Устаноўкі',
'preview' => 'Прагляд',
'previousdiff' => '← Перайсьці да папярэдняй зьмены',
'prevn' => 'папярэднія $1',
'printableversion' => 'Вэрсія для друку',
'protect' => 'Абараніць',
'protectcomment' => 'Прычына для абароны',
'protectedarticle' => 'абаронены $1',
'protectedpage' => 'Абароненая старонка',
'protectsub' => '(Абарона «$1»)',
'protectthispage' => 'Абараніць гэтую старонку',
'qbbrowse' => 'Праглядзець',
'qbedit' => 'Рэдагаваць',
'qbfind' => 'Знайсьці',
'qbpageoptions' => 'Гэтая старонка',
'qbspecialpages' => 'Спэцыяльныя старонкі',
'randompage' => 'Выпадковая старонка',
'rclistfrom' => 'Паказаць зьмены з $1',
'rclsub' => '(да старонак, спасылкі на якія ёсьць на «$1»)',
'recentchanges' => 'Апошнія зьмены',
'recentchangeslinked' => 'Зьвязаныя праўкі',
'redirectedfrom' => '(Перанакіраваная з $1)',
'removechecked' => 'Выдаліць выбраныя старонкі са сьпісу назіраньня',
'removedwatch' => 'Выдаленая са сьпісу назіраньня',
'removedwatchtext' => 'Старонка «$1» была выдаленая з Вашага сьпісу назіраньня.',
'removingchecked' => 'Выдаленьне выбраных старонак са сьпісу назіраньня...',
'resultsperpage' => 'Колькасьць вынікаў на старонцы',
'retrievedfrom' => 'Атрымана з «$1»',
'returnto' => 'Вярнуцца да $1.',
'reupload' => 'Загрузіць зноў',
'reuploaddesc' => 'Вярнуцца да формы загрузкі.',
'revertimg' => 'вярнуць',
'revhistory' => 'Гісторыя зьменаў',
'revisionasof' => 'Вэрсія ад $1',
'revisionasofwithlink' => 'Вэрсія ад $1; $2<br />$3 | $4',
'revnotfound' => 'Вэрсія ня знойдзеная',
'rollback' => 'Адмяніць рэдагаваньні',
'saturday' => 'субота',
'savearticle' => 'Захаваць старонку',
'savefile' => 'Захаваць файл',
'savegroup' => 'Захаваць групу',
'saveprefs' => 'Захаваць перавагі',
'saveusergroups' => 'Захаваць групы ўдзельнікаў і ўдзельніц',
'search' => 'Пошук',
'searchresults' => 'Вынікі пошуку',
'searchresulttext' => 'Для атрыманьня больш падрабязнай інфармацыі аб пошуку, глядзіце [[Project:Пошук|Пошук]].',
'selflinks' => 'Старонкі, якія спасылаюцца на сябе',
'selflinkstext' => 'Наступныя старонкі ўтрымліваюць спасылкі на саміх сябе, чаго не павінна быць.',
'sep' => '09',
'september' => 'верасьня',
'servertime' => 'Бягучы час на сэрвэры',
'sharedupload' => 'Гэты файл знаходзіцца ў [[Commons:Галоўная старонка|ВікіСховішчы]]. Калі ласка, глядзіце \'\'\'[[Commons:Image:{{НАЗВА_СТАРОНКІ}}|яго апісаньне ]]\'\'\' там.',
'shortpages' => 'Кароткія старонкі',
'show' => 'паказаць',
'showbigimage' => 'Паказаць варыянт большага памеру ($1 × $2, $3 Кб)',
'showlast' => 'Паказаць $1 апошніх выяваў адсартаваных $2.',
'showpreview' => 'Праглядзець',
'showtoc' => 'паказаць',
'sig_tip' => 'Ваш подпіс і момант часу',
'sitesubtitle' => 'Вольная энцыкляпэдыя',
'sitesupport' => 'Ахвяраваньні',
'sitetitle' => '{{SITENAME}}',
'siteuser' => 'Удзельнік/удзельніца $1',
'specialloguserlabel' => 'Удзельнік/удзельніца:',
'specialpage' => 'Спэцыяльная старонка',
'specialpages' => 'Спэцыяльныя старонкі',
'spheading' => 'Спэцыяльныя старонкі для ўсіх удзельнікаў і ўдзельніц',
'statistics' => 'Статыстыка',
'storedversion' => 'Захаваная вэрсія',
'subcategories' => 'Падкатэгорыі',
'subcategorycount' => 'У гэтай катэгорыі — $1 падкатэгоры(я,і,й).',
'subcategorycount1' => 'У гэтай катэгорыі — $1 падкатэгорыя.',
'subject' => 'Тэма/назва',
'subjectpage' => 'Паказаць тэму',
'summary' => 'Кароткае апісаньне зьменаў',
'sunday' => 'нядзеля',
'tableform' => 'табліца',
'tagline' => 'Зьвесткі з сайту {{SITENAME}}.',
'talk' => 'Гутаркі',
'talkpage' => 'Абмеркаваць гэтую старонку',
'talkpagemoved' => 'Адпаведная старонка абмеркаваньня таксама перанесеная.',
'talkpagenotmoved' => 'Адпаведная старонка абмеркаваньня <strong>не</strong> перанесеная.',
'templatesused' => 'На гэтай старонцы выкарыстаныя наступныя шаблёны:',
'textboxsize' => 'Рэдагаваньне',
'thumbnail-more' => 'Павялічыць',
'thursday' => 'чацьвер',
'timezonelegend' => 'Часавы пояс',
'toc' => 'Зьмест',
'tog-editsection' => 'Дазволіць рэдагаваньне асобных сэкцыяў па спасылках [рэдагаваць]',
'tog-editwidth' => 'Поле рэдагаваньня ў поўную шырыню',
'tog-hideminor' => 'Хаваць дробныя зьмены ў сьпісе апошніх зьменаў',
'tog-showtoc' => 'Паказваць зьмест<br />(для старонак з колькасьцю сэкцый болей за 3)',
'tog-showtoolbar' => 'Паказваць панэль інструмэнтаў рэдагаваньня',
'tog-underline' => 'Падкрэсьліваць спасылкі',
'toolbox' => 'Інструмэнты',
'tooltip-minoredit' => 'Пазначыць гэтую зьмену як дробную [alt-i]',
'tooltip-save' => 'Захаваць Вашы зьмены [alt-s]',
'tooltip-watch' => 'Дадаць гэтую старонку ў Ваш сьпіс назіраньня [alt-w]',
'tuesday' => 'аўторак',
'unusedimages' => 'Выявы, якія не выкарыстоўваюцца',
'unwatch' => 'Не назіраць',
'unwatchthispage' => 'Перастаць назіраць',
'upload' => 'Загрузіць файл',
'uploadbtn' => 'Загрузіць файл',
'uploadedfiles' => 'Загружаныя файлы',
'uploadedimage' => 'загружаная «$1»',
'uploadlink' => 'Загрузіць выявы',
'uploadlogpagetext' => 'Сьпіс апошніх загружаных файлаў.',
'uploadtext' => '\'\'\'Перад тым, як загрузіць файл:\'\'\'

* Азнаёмцеся з \'\'\'[[Project:Правілы выкарыстаньня выяваў|правіламі выкарыстаньня выяваў]]\'\'\'.
* Праверце з дапамогай \'\'\'[[Special:Imagelist|сьпісу выяваў]]\'\'\', ці не загружаны гэты файл з іншай назвай.
* Выкарыстоўвайце наступныя \'\'\'фарматы\'\'\': [[JPG]] — для фотаздымкаў; [[GIF]] — для анімацыі; [[PNG]] — для іншых выяваў; [[OGG]] — для аўдыёфайлаў.
* Давайце файлам \'\'\'зразумелыя назвы\'\'\', якія адлюстроўваюць іх зьмест. Напрыклад: \'\'Janka Kupala, 1910.jpg\'\' замест \'\'JK1.jpg\'\'. Назву файла \'\'\' немагчыма \'\'\' зьмяніць пасьля загрузкі.
* Пытайцеся \'\'\'дазволу\'\'\' на публікацыю фотаздымка ва ўсіх людзей, якія там прысутнічаюць.

\'\'\'Пасьля таго, як выява загружаная:\'\'\'

* \'\'\'Абавязкова\'\'\' дадайце:
** \'\'\'дэталёвае апісаньне зьместу\'\'\';
** \'\'\'крыніцу\'\'\': файл створаны Вамі; адсканаваны з кнігі \'\'X\'\'; узяты з Інтэрнэт па адрасу \'\'Y\'\';
** для файлаў, якія зроблены \'\'\'ня\'\'\' Вамі, укажыце, ці атрымалі Вы \'\'\'дазвол\'\'\' на выкарыстаньне гэтага файла ў {{SITENAME}};
** \'\'\'ліцэнзіі\'\'\', згодна ўмоваў якіх магчыма распаўсюджваць файл.
* \'\'\'Выкарыстоўвайце файл\'\'\' у артыкуле(ах). Напрыклад: <code><nowiki>[[Image:file.jpg]]</nowiki></code> ці <code><nowiki>[[Image:file.jpg|thumb|200px|Апісаньне]]</nowiki></code> — для выяваў; <code><nowiki>[[Media:file.ogg]]</nowiki></code> — для аўдыёфайлаў.',
'userexists' => 'Выбранае Вамі імя ўдзельніка/ўдзельніцы ўжо выкарыстоўваецца кімсьці іншым. Калі ласка, выберыце іншае імя.',
'userlogout' => 'Выйсьці',
'val_table_header' => '<tr><th>Кляса</th>$1<th colspan=4>Меркаваньне</th>$1<th>Камэнтар</th></tr>',
'val_version' => 'Вэрсія',
'val_version_of' => 'Вэрсія $1',
'val_view_version' => 'Паказаць гэтую вэрсію',
'version' => 'Вэрсія',
'viewprevnext' => 'Паказаць ($1) ($2) ($3).',
'viewsource' => 'Паказаць крыніцу',
'viewtalkpage' => 'Паказаць абмеркаваньне',
'watch' => 'Назіраць',
'watchlist' => 'Мой сьпіс назіраньня',
'watchthis' => 'Назіраць за гэтай старонкай',
'watchthispage' => 'Назіраць за гэтай старонкай',
'wednesday' => 'серада',
'whatlinkshere' => 'Адкуль спасылаюцца на старонку',
'whitelistacctitle' => 'Вам не дазволена ствараць рахунак',
'wikititlesuffix' => '{{SITENAME}}',
'youremail' => 'Ваш адрас электроннай пошты (*)',
'yourname' => 'Ваша імя ўдзельніка/ўдзельніцы',
'yournick' => 'Ваша мянушка (для подпісаў)',
'yourpassword' => 'Ваш пароль',
'yourrealname' => 'Вашае сапраўднае імя*',
'yourtext' => 'Ваш тэкст',
);

class LanguageBe extends LanguageUtf8 {

	// Namespaces
	function getNamespaces() {
		global $wgNamespaceNamesBe;
		return $wgNamespaceNamesBe;
	}

	// Quickbar
	function getQuickbarSettings() {
		global $wgQuickbarSettingsBe;
		return $wgQuickbarSettingsBe;
	}

	// Skins
	function getSkinNames() {
		global $wgSkinNamesBe;
		return $wgSkinNamesBe;
	}

	// Magic words
	function getMagicWords() {
		global $wgMagicWordsBe;
		return $wgMagicWordsBe;
	}

	// The date and time format
	function date( $ts, $adj = false ) {
		if ( $adj ) { $ts = $this->userAdjust( $ts ); } # Adjust based on the timezone setting.
		// 20050310001506 => 10.03.2005
		$date = (substr( $ts, 6, 2 )) . '.' . substr( $ts, 4, 2 ) . '.' . substr( $ts, 0, 4 );
		return $date;
	}

	function time( $ts, $adj = false ) {
		if ( $adj ) { $ts = $this->userAdjust( $ts ); }
		// 20050310001506 => 00:15
		$time = substr( $ts, 8, 2 ) . ':' . substr( $ts, 10, 2 );
		return $time;
	}

	function timeanddate( $ts, $adj = false ) {
		return $this->time( $ts, $adj ) . ', ' .$this->date( $ts, $adj );
	}

	function getMessage( $key ) {
		global $wgAllMessagesBe;
		if( isset( $wgAllMessagesBe[$key] ) ) {
			return $wgAllMessagesBe[$key];
		} else {
			return parent::getMessage( $key );
		}
	}

	function formatNum( $number ) {
		return strtr($number, '.,', ',.' );
	}
}
?>
