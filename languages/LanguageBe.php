<?php
# Belarusian (Беларуская мова)
# File by Ævar Arnfjörð Bjarmason and translations by 
# be:EugeneZelenko, be:Monk (and others)
#
# This file is dual-licensed under GFDL and GPL.
#
# See: http://bugzilla.wikimedia.org/show_bug.cgi?id=1638
#      http://be.wikipedia.org/wiki/Talk:LanguageBe.php

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
);

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
	MAG_MSG			=> array( 0,	'MSG:', 'ПАВЕДАМЛЕНЬНЕ:' ),
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

'aboutpage' => 'Project:Пра Вікіпэдыю',
'accmailtext' => 'Пароль для \'$1\' быў адасланы па адрасу $2.',
'addedwatch' => 'Даданая ў сьпіс назіраньня',
'addgroup' => 'Дадаць групу',
'administrators' => 'Project:Адміністратары',
'affirmation' => 'Я пацьвярджаю, што ўладальнік/уладальніца аўтарскіх правоў на гэты файл згодзен/згодная распаўсюджваць яго адпаведна з умовамі ліцэнзіі $1.',
'all' => 'усе',
'allarticles' => 'Усе артыкулы',
'allmessages' => 'Усе сыстэмныя паведамленьні',
'allpages' => 'Усе старонкі',
'allpagesnext' => 'Наступныя',
'allpagesprev' => 'Папярэднія',
'and' => 'і',
'anonymous' => 'Ананімныя ўдзельнікі і ўдзельніцы Вікіпэдыі',
'apr' => '04',
'april' => 'красавіка',
'articlenamespace' => '(артыкулы)',
'aug' => '08',
'august' => 'жніўня',
'badfilename' => 'Назва выявы была зьмененая на «$1».',
'badfiletype' => '«.$1» не зьяўляецца рэкамэндаваным фарматам для файлаў выяваў.',
'badipaddress' => 'Некарэктны IP адрас',
'badtitle' => 'Некарэктная назва',
'bydate' => 'па даце',
'byname' => 'па назьве',
'bysize' => 'па памеры',
'cancel' => 'Адмяніць',
'categories' => 'Катэгорыі',
'category' => 'катэгорыя',
'category_header' => 'Артыкулы ў катэгорыі \'$1\'',
'categoryarticlecount' => 'У катэгорыі ёсьць $1 артыкул(а,аў).',
'changepassword' => 'Зьмяніць пароль',
'compareselectedversions' => 'Параўнаць выбраныя вэрсіі',
'confirmcheck' => 'Так, я сапраўды жадаю выдаліць гэта.',
'contributions' => 'Унёсак удзельніка/удзельніцы',
'copyright' => 'Зьмест старонкі падпадае пад ліцэнзію $1.',
'copyrightwarning' => '<strong>НІ Ў ЯКІМ РАЗЕ НЕ СТАЎЦЕ БЕЗ ДАЗВОЛУ ТЭКСТЫ, ЯКІЯ АБАРОНЕНЫЯ АЎТАРСКІМ ПРАВАМ</strong><br />
Please note that all contributions to {{SITENAME}} are
considered to be released under the $2
(see $1 for details).
If you don\'t want your writing to be edited mercilessly and redistributed
at will, then don\'t submit it here.<br />
You are also promising us that you wrote this yourself, or copied it from a
public domain or similar free resource.<br />
<strong>DO NOT SUBMIT COPYRIGHTED WORK WITHOUT PERMISSION!</strong>',
'createaccount' => 'Стварыць новы рахунак',
'cur' => 'бяг',
'currentevents' => 'Бягучыя падзеі',
'currentevents-url' => 'Бягучыя падзеі',
'currentrev' => 'Бягучая вэрсія',
'currentrevisionlink' => 'паказаць бягучую вэрсію',
'data' => 'Дадзеныя',
'dateformat' => 'Фармат даты',
'deadendpages' => 'Тупіковыя артыкулы',
'dec' => '12',
'december' => 'сьнежня',
'delete' => 'Выдаліць',
'deletecomment' => 'Прычына выдаленьня',
'deletedarticle' => 'выдалены «$1»',
'deletedrevision' => 'Выдаленая старая вэрсія $1.',
'deleteimg' => 'выдаліць',
'deleteimgcompletely' => 'Выдаліць усе вэрсіі',
'deletepage' => 'Выдаліць старонку',
'deletethispage' => 'Выдаліць гэтую старонку',
'diff' => 'розьн',
'difference' => '(Адрозьненьні паміж вэрсіямі)',
'disclaimers' => 'Адмова ад адказнасьці',
'edit' => 'Рэдагаваць',
'editconflict' => 'Канфлікт рэдагаваньня: $1',
'editcurrent' => 'Рэдагаваць бягучую вэрсію гэтага артыкула',
'editgroup' => 'Рэдагаваць групу',
'editing' => 'Рэдагаваньне: $1',
'editingcomment' => 'Рэдагаваньне: $1 (камэнтар)',
'editingsection' => 'Рэдагаваньне: $1 (сэкцыя)',
'editsection' => 'рэдагаваць',
'editthispage' => 'Рэдагаваць гэтую старонку',
'emailfrom' => 'Ад',
'emailsend' => 'Даслаць',
'emailsubject' => 'Тэма',
'emailto' => 'Каму',
'error' => 'Памылка',
'errorpagetitle' => 'Памылка',
'exblank' => 'старонка была пустая',
'export' => 'Экспартаваць старонкі',
'feb' => '02',
'february' => 'лютага',
'filecopyerror' => 'Немагчыма cкапіяваць файл \'$1\' у \'$2\'.',
'filedeleteerror' => 'Немагчыма выдаліць файл \'$1\'.',
'filedesc' => 'Апісаньне',
'filename' => 'Назва файла',
'filenotfound' => 'Немагчыма знайсьці файл \'$1\'.',
'filerenameerror' => 'Немагчыма перайменаваць файл \'$1\' у \'$2\'.',
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
'image_sample' => 'Прыклад.jpg',
'imagelist' => 'Сьпіс выяваў',
'imagepage' => 'Паказаць старонку выявы',
'imgdelete' => 'выдаліць',
'imgdesc' => 'апісаньне',
'imghistory' => 'Гісторыя выявы',
'import' => 'Імпартаваць старонкі',
'info_short' => 'Інфармацыя',
'internalerror' => 'Унутраная памылка',
'ip_range_invalid' => 'Некарэктны дыяпазон IP адрасоў.',
'ipaddress' => 'IP адрас/Імя ўдзельніка/ўдзельніцы',
'ipbreason' => 'Прычына',
'jan' => '01',
'january' => 'студзеня',
'jul' => '07',
'july' => 'ліпеня',
'jun' => '06',
'june' => 'чэрвеня',
'lastmodified' => 'Гэтая старонка апошні раз рэдагавалася $1.',
'lastmodifiedby' => 'Гэтую старонку апошні раз рэдагаваў $2 $1.',
'linklistsub' => '(Сьпіс спасылак)',
'linkshere' => 'Наступныя старонкі спасылаюцца на гэтую:',
'linkstoimage' => 'Наступныя старонкі спасылаюцца на гэтую выяву:',
'listadmins' => 'Сьпіс адміністратараў і адміністратарак',
'listform' => 'сьпіс',
'listusers' => 'Сьпіс удзельнікаў і ўдзельніц',
'localtime' => 'Мясцовы час',
'logout' => 'Выйсьці',
'lonelypages' => 'Старонкі-сіраціны',
'longpages' => 'Доўгія старонкі',
'mailmypassword' => 'Даслаць мне новы пароль',
'mar' => '03',
'march' => 'сакавіка',
'math_syntax_error' => 'сынтаксычная памылка',
'math_unknown_error' => 'невядомая памылка',
'math_unknown_function' => 'невядомая функцыя',
'may' => '05',
'may_long' => 'траўня',
'media_sample' => 'Прыклад.ogg',
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
'mycontris' => 'Мой унёсак',
'mypage' => 'Мая старонка',
'mytalk' => 'Мае размовы',
'navigation' => 'Навігацыя',
'newarticle' => '(Новы)',
'newimages' => 'Галерэя новых выяваў',
'newmessages' => 'Вы атрымалі $1.',
'newpage' => 'Новая старонка',
'newpageletter' => 'Н',
'newpages' => 'Новыя старонкі',
'newpassword' => 'Новы пароль',
'nextdiff' => 'Перайсьці да наступнай зьмены &rarr;',
'nextn' => 'наступныя $1',
'nextpage' => 'Наступная старонка ($1)',
'noaffirmation' => 'Вы павінныя пацьвердзіць, што загрузка гэтага файла не парушае нічыіх аўтарскіх правоў.',
'nodb' => 'Немагчыма выбраць базу дадзеных $1',
'noemailtitle' => 'Адрас электроннай пошты адсутнічае',
'nogomatch' => '<span style=\'font-size: 135%; font-weight: bold; margin-left: .6em\'>Старонкі з гэткай назвай не існуе.</span>
<span style=\'display: block; margin: 1.5em 2em\'>Вы можаце <b><a href=\'$1\' class=\'new\'>стварыць старонку</a></b>, калі ўпэўнены, што Вікіпэдыя не зьмяшчае яе пад іншай назвай ці ў іншым правапісе.</span>
<span style=\'display:block; margin-left:.2em\'>Калі ласка, пашукайце гэтыя словы ва ўсёй Вікіпэдыі.</span>',
'nohistory' => 'Гісторыя зьменаў для гэтай старонкі адсутнічае.',
'noimages' => 'Выявы адсутнічаюць.',
'nolinkshere' => 'Ніводная старонка сюды не спасылаецца.',
'nolinkstoimage' => 'Ніводная старонка не спасылаецца на гэтую выяву.',
'nov' => '11',
'november' => 'лістапада',
'nstab-category' => 'Катэгорыя',
'nstab-help' => 'Дапамога',
'nstab-image' => 'Выява',
'nstab-main' => 'Артыкул',
'nstab-mediawiki' => 'Паведамленьне',
'nstab-template' => 'Шаблён',
'nstab-user' => 'Старонка ўдзельніка/ўдзельніцы',
'oct' => '10',
'october' => 'кастрычніка',
'ok' => 'Добра',
'otherlanguages' => 'На іншых мовах',
'pagetitle' => '$1 - Вікіпэдыя',
'popularpages' => 'Папулярныя старонкі',
'portal' => 'Суполка',
'portal-url' => 'Project:Суполка',
'preferences' => 'Устаноўкі',
'preview' => 'Прагляд',
'previousdiff' => '&larr; Перайсьці да папярэдняй зьмены',
'prevn' => 'папярэднія $1',
'printableversion' => 'Вэрсія для друку',
'qbedit' => 'Рэдагаваць',
'qbpageoptions' => 'Гэтая старонка',
'qbspecialpages' => 'Спэцыяльныя старонкі',
'randompage' => 'Выпадковая старонка',
'recentchanges' => 'Апошнія зьмены',
'recentchangeslinked' => 'Зьвязаныя праўкі',
'returnto' => 'Вярнуцца да $1.',
'saturday' => 'субота',
'savearticle' => 'Захаваць старонку',
'savefile' => 'Захаваць файл',
'savegroup' => 'Захаваць групу',
'saveprefs' => 'Захаваць перавагі',
'search' => 'Пошук',
'searchresults' => 'Вынікі пошуку',
'sep' => '09',
'september' => 'верасьня',
'show' => 'паказаць',
'showlast' => 'Паказаць $1 апошніх выяваў адсартаваных $2.',
'showpreview' => 'Праглядзець',
'showtoc' => 'паказаць',
'sitesettings-images' => 'Выявы',
'sitesettings-permissions' => 'Правы',
'sitesettings-wgUseCategoryMagic' => 'Дазволіць катэгорыі',
'sitesubtitle' => 'Вольная энцыкляпэдыя',
'sitesupport' => 'Ахвяраваньні',
'sitetitle' => 'Вікіпэдыя',
'siteuser' => 'Удзельнік/удзельніца Вікіпэдыі $1',
'specialpage' => 'Спэцыяльная старонка',
'specialpages' => 'Спэцыяльныя старонкі',
'statistics' => 'Статыстыка',
'summary' => 'Кароткае апісаньне зьменаў',
'sunday' => 'нядзеля',
'tableform' => 'табліца',
'talk' => 'Гутаркі',
'talkpage' => 'Абмеркаваць гэтую старонку',
'thursday' => 'чацьвер',
'toc' => 'Зьмест',
'toolbox' => 'Інструмэнты',
'tuesday' => 'аўторак',
'unwatch' => 'Не назіраць',
'upload' => 'Загрузіць файл',
'uploadbtn' => 'Загрузіць файл',
'uploadedfiles' => 'Загружаныя файлы',
'uploadedimage' => 'загружаная \'[[$1]]\'',
'uploadlink' => 'Загрузіць выявы',
'uploadtext' => '\'\'\'Перад тым, як загрузіць файл:\'\'\'
* Азнаёмцеся з \'\'\'[[Project:Правілы выкарыстаньня выяваў|правіламі выкарыстаньня выяваў у Вікіпэдыі]]\'\'\'.
* Праверце з дапамогай \'\'\'[[Special:Imagelist|сьпісу выяваў]]\'\'\', ці не загружаны гэты файл з іншай назвай.
* Выкарыстоўвайце наступныя \'\'\'фарматы\'\'\': [[JPG]] — для фотаздымкаў; [[GIF]] — для анімацыі; [[PNG]] — для іншых выяваў; [[OGG]] — для аўдыёфайлаў.
* Давайце файлам \'\'\'зразумелыя назвы\'\'\', якія адлюстроўваюць іх зьмест. Напрыклад: \'\'\Janka Kupala, 1910.jpg\'\' замест \'\'JK1.jpg\'\'. Назву файла \'\'\' немагчыма \'\'\' зьмяніць пасьля загрузкі.
* Пытайцеся \'\'\'дазволу\'\'\' на публікацыю фотаздымка ва ўсіх людзей, якія там прысутнічаюць.
* Калі Вы хочаце загрузіць файл з іншай Вікіпэдыі, які вольна распаўсюджваецца (звычайна: \'\'\'GFDL\'\'\', \'\'\'public domain\'\'\', \'\'\'Creative Commons\'\'\'), уважліва праверце, ці не прысутнічае ён на \'\'\'[[commons:|Wikimedia Commons]]\'\'\'. Файлы адтуль можна выкарыстоўваць гэтак жа, як лякальныя, без аніякай загрузкі. Нават, калі гэтага файла Вы там не знайшлі, усе роўна мае сэнс [[commons:Special:Upload|загрузіць яго на Wikimedia Commons]].
\'\'\'Пасьля таго, як выява загружаная:\'\'\'
* \'\'\'Абавязкова\'\'\' дадайце:
** \'\'\'дэталёвае апісаньне зьместу\'\'\';
** \'\'\'крыніцу\'\'\': файл створаны Вамі; адсканаваны з кнігі \'\'X\'\'; узяты з Інтэрнэт па адрасу \'\'Y\'\';
** для файлаў, якія зроблены \'\'\'ня\'\'\' Вамі, укажыце, ці атрымалі Вы \'\'\'дазвол\'\'\' на выкарыстаньне гэтага файла ў Вікіпэдыі;
** \'\'\'ліцэнзіі\'\'\', згодна ўмоваў якіх магчыма распаўсюджваць файл.
* \'\'\'Выкарыстоўвайце файл\'\'\' у артыкуле(ах). Напрыклад: <code><nowiki>[[Image:file.jpg]]</nowiki></code> ці <code><nowiki>[[Image:file.jpg|thumb|200px|Апісаньне]]</nowiki></code> — для выяваў; <code><nowiki>[[Media:file.ogg]]</nowiki></code> — для аўдыёфайлаў.',
'userlevels-addgroup' => 'Дадаць групу',
'userlevels-editgroup' => 'Рэдагаваць групу',
'userlevels-editgroup-name' => 'Назва групы:',
'userlevels-group-edit' => 'Існуючыя групы:',
'userlevels-groupsavailable' => 'Даступныя групы:',
'userlogout' => 'Выйсьці',
'val_version' => 'Вэрсія',
'val_version_of' => 'Вэрсія $1',
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
'wikititlesuffix' => 'Вікіпэдыя',
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

	function getNsText( $index ) {
		global $wgNamespaceNamesBe;
		return $wgNamespaceNamesBe[$index];
	}

	function getNsIndex( $text ) {
		global $wgNamespaceNamesBe;

		foreach ( $wgNamespaceNamesBe as $i => $n ) {
			if ( 0 == strcasecmp( $n, $text ) ) { return $i; }
		}
		return false;
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
	function getMagicWords() 
	{
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
}
?>
