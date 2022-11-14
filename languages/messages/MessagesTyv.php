<?php
/** Tuvinian (тыва дыл)
 *
 * @file
 * @ingroup Languages
 *
 * @author Agilight
 * @author Andrijko Z.
 * @author Kaganer
 * @author Krice from Tyvanet.com
 * @author Sborsody
 * @author friends at tyvawiki.org
 * @author לערי ריינהארט
 */

$fallback = 'ru';

$fallback8bitEncoding = "windows-1251";

$namespaceNames = [
	NS_MEDIA            => 'Медиа',
	NS_SPECIAL          => 'Тускай',
	NS_TALK             => 'Чугаа',
	NS_USER             => 'Ажыглакчы',
	NS_USER_TALK        => 'Ажыглакчы_чугаа',
	NS_PROJECT_TALK     => '$1_чугаа',
	NS_FILE             => 'Файл',
	NS_FILE_TALK        => 'Файл_чугаа',
	NS_MEDIAWIKI        => 'МедиаВики',
	NS_MEDIAWIKI_TALK   => 'МедиаВики_чугаа',
	NS_TEMPLATE         => 'Майык',
	NS_TEMPLATE_TALK    => 'Майык_чугаа',
	NS_HELP             => 'Дуза',
	NS_HELP_TALK        => 'Дуза_чугаа',
	NS_CATEGORY         => 'Аңгылал',
	NS_CATEGORY_TALK    => 'Аңгылал_чугаа',
];

$namespaceAliases = [
	'Aжыглакчы'          => NS_USER,
	'Aжыглакчы_чугаа'    => NS_USER_TALK,
	'Aжыглакчы_чугаазы'  => NS_USER_TALK,
	'$1_чугаазы'         => NS_PROJECT_TALK,
	'Файл_чугаазы'       => NS_FILE_TALK,
	'МедиаВики_чугаазы'  => NS_MEDIAWIKI_TALK,
	'Майык_чугаазы'      => NS_TEMPLATE_TALK,
	'Дуза_чугаазы'       => NS_HELP_TALK,
	'Категория'          => NS_CATEGORY,
	'Категория_чугаазы'  => NS_CATEGORY_TALK,
];

$namespaceGenderAliases = []; // T259003

/** @phpcs-require-sorted-array */
$specialPageAliases = [
	'Activeusers'               => [ 'Идекпейжилер' ],
	'Allmessages'               => [ 'Шупту_медеглелдер' ],
	'AllMyUploads'              => [ 'Шупту_файлдарым' ],
	'Allpages'                  => [ 'Шупту_арыннар' ],
	'Badtitle'                  => [ 'Хоржок_ат-сып' ],
	'Blankpage'                 => [ 'Куруг_арын' ],
	'Block'                     => [ 'Дуглаар' ],
	'Booksources'               => [ 'Ном_үндезини' ],
	'BrokenRedirects'           => [ 'Үзүлген_көжүрүглер' ],
	'Categories'                => [ 'Аңгылалдар' ],
	'ChangeEmail'               => [ 'Э-шуудаң_солуур' ],
	'ChangePassword'            => [ 'Уруң_солуур' ],
	'ComparePages'              => [ 'Арыннар_деңнээр' ],
	'Confirmemail'              => [ 'Э-шуудаң_бадыткаар' ],
	'MyLanguage'                => [ 'Дылым' ],
];

/** @phpcs-require-sorted-array */
$magicWords = [
	'currentday'                => [ '1', 'АМГЫХҮН', 'ТЕКУЩИЙ_ДЕНЬ', 'CURRENTDAY' ],
	'currentday2'               => [ '1', 'АМГЫХҮН2', 'ТЕКУЩИЙ_ДЕНЬ_2', 'CURRENTDAY2' ],
	'currentdayname'            => [ '1', 'АМГЫХҮННҮҢАДЫ', 'НАЗВАНИЕ_ТЕКУЩЕГО_ДНЯ', 'CURRENTDAYNAME' ],
	'currentdow'                => [ '1', 'АМГЫЧЕДИХОНУКТУҢХҮНҮ', 'ТЕКУЩИЙ_ДЕНЬ_НЕДЕЛИ', 'CURRENTDOW' ],
	'currenthour'               => [ '1', 'АМГЫШАК', 'ТЕКУЩИЙ_ЧАС', 'CURRENTHOUR' ],
	'currentmonth'              => [ '1', '__АМГЫ_АЙ', '__АМГЫ_АЙ_2__', 'ТЕКУЩИЙ_МЕСЯЦ', 'ТЕКУЩИЙ_МЕСЯЦ_2', 'CURRENTMONTH', 'CURRENTMONTH2' ],
	'currentmonth1'             => [ '1', '__АМГЫ_АЙ_1__', 'ТЕКУЩИЙ_МЕСЯЦ_1', 'CURRENTMONTH1' ],
	'currentmonthname'          => [ '1', 'АМГЫАЙНЫҢАДЫ', 'НАЗВАНИЕ_ТЕКУЩЕГО_МЕСЯЦА', 'CURRENTMONTHNAME' ],
	'currenttime'               => [ '1', 'АМГЫҮЕ', 'ТЕКУЩЕЕ_ВРЕМЯ', 'CURRENTTIME' ],
	'currentweek'               => [ '1', 'АМГЫЧЕДИХОНУК', 'ТЕКУЩАЯ_НЕДЕЛЯ', 'CURRENTWEEK' ],
	'currentyear'               => [ '1', 'АМГЫЧЫЛ', 'ТЕКУЩИЙ_ГОД', 'CURRENTYEAR' ],
	'forcetoc'                  => [ '0', '__АЛБАН_ЭГЕ__', '__ОБЯЗАТЕЛЬНОЕ_ОГЛАВЛЕНИЕ__', '__ОБЯЗ_ОГЛ__', '__FORCETOC__' ],
	'hiddencat'                 => [ '1', '__ЧАЖЫТ_АҢГЫЛАЛ__', '__СКРЫТАЯ_КАТЕГОРИЯ__', '__HIDDENCAT__' ],
	'img_center'                => [ '1', 'төп', 'центр', 'center', 'centre' ],
	'img_left'                  => [ '1', 'солагай', 'слева', 'left' ],
	'img_right'                 => [ '1', 'оң', 'справа', 'right' ],
	'language'                  => [ '0', '#ДЫЛ:', '#ЯЗЫК:', '#LANGUAGE:' ],
	'namespace'                 => [ '1', 'АТТАРДЕЛГЕМИ', 'ПРОСТРАНСТВО_ИМЁН', 'NAMESPACE' ],
	'namespacee'                => [ '1', 'АТТАРДЕЛГЕМИ2', 'ПРОСТРАНСТВО_ИМЁН_2', 'NAMESPACEE' ],
	'namespacenumber'           => [ '1', 'АТТАРДЕЛГЕМИНИҢСАНЫ', 'НОМЕР_ПРОСТРАНСТВА_ИМЁН', 'NAMESPACENUMBER' ],
	'noeditsection'             => [ '0', '__ҮЛЕГ_ЭДИЛГЕЗИ_ЧОК__', '__БЕЗ_РЕДАКТИРОВАНИЯ_РАЗДЕЛА__', '__NOEDITSECTION__' ],
	'nogallery'                 => [ '0', '__ГАЛЕРЕЯ_ЧОК__', '__БЕЗ_ГАЛЕРЕИ__', '__NOGALLERY__' ],
	'notoc'                     => [ '0', '__ЭГЕ_ЧОК__', '__БЕЗ_ОГЛАВЛЕНИЯ__', '__БЕЗ_ОГЛ__', '__NOTOC__' ],
	'ns'                        => [ '0', 'АД:', 'ПИ:', 'NS:' ],
	'nse'                       => [ '0', 'АД2:', 'ПИК:', 'NSE:' ],
	'numberofarticles'          => [ '1', 'ЧҮҮЛДЕРНИҢСАНЫ', 'КОЛИЧЕСТВО_СТАТЕЙ', 'NUMBEROFARTICLES' ],
	'numberofedits'             => [ '1', 'ӨСКЕРЛИИШКИННЕРНИҢСАНЫ', 'КОЛИЧЕСТВО_ПРАВОК', 'NUMBEROFEDITS' ],
	'numberoffiles'             => [ '1', 'ФАЙЛДАРНЫҢСАНЫ', 'КОЛИЧЕСТВО_ФАЙЛОВ', 'NUMBEROFFILES' ],
	'numberofpages'             => [ '1', 'АРЫННАРНЫҢСАНЫ', 'КОЛИЧЕСТВО_СТРАНИЦ', 'NUMBEROFPAGES' ],
	'numberofusers'             => [ '1', 'АЖЫГЛАКЧЫЛАРНЫҢСАНЫ', 'КОЛИЧЕСТВО_УЧАСТНИКОВ', 'NUMBEROFUSERS' ],
	'pagename'                  => [ '1', 'АРЫННЫҢАДЫ', 'НАЗВАНИЕ_СТРАНИЦЫ', 'PAGENAME' ],
	'pagesincategory'           => [ '1', '__АҢГЫЛАЛ_АРЫННАРЫ__', 'СТРАНИЦ_В_КАТЕГОРИИ', 'PAGESINCATEGORY', 'PAGESINCAT' ],
	'pagesincategory_all'       => [ '0', 'шупту', 'все', 'all' ],
	'pagesincategory_files'     => [ '0', 'файлдар', 'файлы', 'files' ],
	'pagesincategory_pages'     => [ '0', 'арыннар', 'страницы', 'pages' ],
	'raw'                       => [ '0', 'ЧИГ:', 'НЕОБРАБ:', 'RAW:' ],
	'redirect'                  => [ '0', '#көжүрүлге', '#ШИГЛЕДИР', '#перенаправление', '#перенапр', '#REDIRECT' ],
	'sitename'                  => [ '1', 'САЙТТЫҢАДЫ', 'НАЗВАНИЕ_САЙТА', 'SITENAME' ],
	'special'                   => [ '0', 'тускай', 'служебная', 'special' ],
	'tag'                       => [ '0', 'демдек', 'метка', 'тег', 'тэг', 'tag' ],
	'talkspace'                 => [ '1', 'ЧУГААДЕЛГЕМИ', 'ПРОСТРАНСТВО_ОБСУЖДЕНИЙ', 'TALKSPACE' ],
	'talkspacee'                => [ '1', 'ЧУГААДЕЛГЕМИ2', 'ПРОСТРАНСТВО_ОБСУЖДЕНИЙ_2', 'TALKSPACEE' ],
	'toc'                       => [ '0', '__ДОПЧУ__', '__ОГЛАВЛЕНИЕ__', '__ОГЛ__', '__TOC__' ],
];

$bookstoreList = [
	'ОЗОН' => 'http://www.ozon.ru/?context=advsearch_book&isbn=$1',
	'Books.Ru' => 'http://www.books.ru/shop/search/advanced?as%5Btype%5D=books&as%5Bname%5D=&as%5Bisbn%5D=$1&as%5Bauthor%5D=&as%5Bmaker%5D=&as%5Bcontents%5D=&as%5Binfo%5D=&as%5Bdate_after%5D=&as%5Bdate_before%5D=&as%5Bprice_less%5D=&as%5Bprice_more%5D=&as%5Bstrict%5D=%E4%E0&as%5Bsub%5D=%E8%F1%EA%E0%F2%FC&x=22&y=8',
	'Яндекс.Маркет' => 'http://market.yandex.ru/search.xml?text=$1',
	'Amazon.com' => 'https://www.amazon.com/exec/obidos/ISBN=$1',
	'AddALL' => 'http://www.addall.com/New/Partner.cgi?query=$1&type=ISBN',
	'Barnes & Noble' => 'http://shop.barnesandnoble.com/bookSearch/isbnInquiry.asp?isbn=$1'
];
