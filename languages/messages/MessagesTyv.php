<?php
/** Tuvinian (тыва дыл)
 *
 * To improve a translation please visit https://translatewiki.net
 *
 * @ingroup Language
 * @file
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

$namespaceNames = array(
	NS_MEDIA            => 'Медиа',
	NS_SPECIAL          => 'Тускай',
	NS_TALK             => 'Чугаа',
	NS_USER             => 'Aжыглакчы',
	NS_USER_TALK        => 'Aжыглакчы_чугаа',
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
);

$namespaceAliases = array(
	'Aжыглакчы_чугаазы'  => NS_USER_TALK,
	'$1_чугаазы'         => NS_PROJECT_TALK,
	'Файл_чугаазы'       => NS_FILE_TALK,
	'МедиаВики_чугаазы'  => NS_MEDIAWIKI_TALK,
	'Майык_чугаазы'      => NS_TEMPLATE_TALK,
	'Дуза_чугаазы'       => NS_HELP_TALK,
	'Категория'          => NS_CATEGORY,
	'Категория_чугаазы'  => NS_CATEGORY_TALK,
);

$specialPageAliases = array(
	'Activeusers'               => array( 'Идекпейжилер' ),
	'Allmessages'               => array( 'Шупту_медеглелдер' ),
	'AllMyUploads'              => array( 'Шупту_файлдарым' ),
	'Allpages'                  => array( 'Шупту_арыннар' ),
	'Badtitle'                  => array( 'Хоржок_ат-сып' ),
	'Blankpage'                 => array( 'Куруг_арын' ),
	'Block'                     => array( 'Дуглаар' ),
	'Booksources'               => array( 'Ном_үндезини' ),
	'BrokenRedirects'           => array( 'Үзүлген_көжүрүглер' ),
	'Categories'                => array( 'Аңгылалдар' ),
	'ChangeEmail'               => array( 'Э-шуудаң_солуур' ),
	'ChangePassword'            => array( 'Уруң_солуур' ),
	'ComparePages'              => array( 'Арыннар_деңнээр' ),
	'Confirmemail'              => array( 'Э-шуудаң_бадыткаар' ),
	'MyLanguage'                => array( 'Дылым' ),
);

$magicWords = array(
	'redirect'                  => array( '0', '#көжүрүлге', '#ШИГЛЕДИР', '#перенаправление', '#перенапр', '#REDIRECT' ),
	'notoc'                     => array( '0', '__ЭГЕ_ЧОК__', '__БЕЗ_ОГЛАВЛЕНИЯ__', '__БЕЗ_ОГЛ__', '__NOTOC__' ),
	'nogallery'                 => array( '0', '__ГАЛЕРЕЯ_ЧОК__', '__БЕЗ_ГАЛЕРЕИ__', '__NOGALLERY__' ),
	'forcetoc'                  => array( '0', '__АЛБАН_ЭГЕ__', '__ОБЯЗАТЕЛЬНОЕ_ОГЛАВЛЕНИЕ__', '__ОБЯЗ_ОГЛ__', '__FORCETOC__' ),
	'toc'                       => array( '0', '__ДОПЧУ__', '__ОГЛАВЛЕНИЕ__', '__ОГЛ__', '__TOC__' ),
	'noeditsection'             => array( '0', '__ҮЛЕГ_ЭДИЛГЕЗИ_ЧОК__', '__БЕЗ_РЕДАКТИРОВАНИЯ_РАЗДЕЛА__', '__NOEDITSECTION__' ),
	'currentmonth'              => array( '1', '__АМГЫ_АЙ', '__АМГЫ_АЙ_2__', 'ТЕКУЩИЙ_МЕСЯЦ', 'ТЕКУЩИЙ_МЕСЯЦ_2', 'CURRENTMONTH', 'CURRENTMONTH2' ),
	'currentmonth1'             => array( '1', '__АМГЫ_АЙ_1__', 'ТЕКУЩИЙ_МЕСЯЦ_1', 'CURRENTMONTH1' ),
	'currentmonthname'          => array( '1', 'АМГЫАЙНЫҢАДЫ', 'НАЗВАНИЕ_ТЕКУЩЕГО_МЕСЯЦА', 'CURRENTMONTHNAME' ),
	'currentday'                => array( '1', 'АМГЫХҮН', 'ТЕКУЩИЙ_ДЕНЬ', 'CURRENTDAY' ),
	'currentday2'               => array( '1', 'АМГЫХҮН2', 'ТЕКУЩИЙ_ДЕНЬ_2', 'CURRENTDAY2' ),
	'currentdayname'            => array( '1', 'АМГЫХҮННҮҢАДЫ', 'НАЗВАНИЕ_ТЕКУЩЕГО_ДНЯ', 'CURRENTDAYNAME' ),
	'currentyear'               => array( '1', 'АМГЫЧЫЛ', 'ТЕКУЩИЙ_ГОД', 'CURRENTYEAR' ),
	'currenttime'               => array( '1', 'АМГЫҮЕ', 'ТЕКУЩЕЕ_ВРЕМЯ', 'CURRENTTIME' ),
	'currenthour'               => array( '1', 'АМГЫШАК', 'ТЕКУЩИЙ_ЧАС', 'CURRENTHOUR' ),
	'numberofpages'             => array( '1', 'АРЫННАРНЫҢСАНЫ', 'КОЛИЧЕСТВО_СТРАНИЦ', 'NUMBEROFPAGES' ),
	'numberofarticles'          => array( '1', 'ЧҮҮЛДЕРНИҢСАНЫ', 'КОЛИЧЕСТВО_СТАТЕЙ', 'NUMBEROFARTICLES' ),
	'numberoffiles'             => array( '1', 'ФАЙЛДАРНЫҢСАНЫ', 'КОЛИЧЕСТВО_ФАЙЛОВ', 'NUMBEROFFILES' ),
	'numberofusers'             => array( '1', 'АЖЫГЛАКЧЫЛАРНЫҢСАНЫ', 'КОЛИЧЕСТВО_УЧАСТНИКОВ', 'NUMBEROFUSERS' ),
	'numberofedits'             => array( '1', 'ӨСКЕРЛИИШКИННЕРНИҢСАНЫ', 'КОЛИЧЕСТВО_ПРАВОК', 'NUMBEROFEDITS' ),
	'pagename'                  => array( '1', 'АРЫННЫҢАДЫ', 'НАЗВАНИЕ_СТРАНИЦЫ', 'PAGENAME' ),
	'namespace'                 => array( '1', 'АТТАРДЕЛГЕМИ', 'ПРОСТРАНСТВО_ИМЁН', 'NAMESPACE' ),
	'namespacee'                => array( '1', 'АТТАРДЕЛГЕМИ2', 'ПРОСТРАНСТВО_ИМЁН_2', 'NAMESPACEE' ),
	'namespacenumber'           => array( '1', 'АТТАРДЕЛГЕМИНИҢСАНЫ', 'НОМЕР_ПРОСТРАНСТВА_ИМЁН', 'NAMESPACENUMBER' ),
	'talkspace'                 => array( '1', 'ЧУГААДЕЛГЕМИ', 'ПРОСТРАНСТВО_ОБСУЖДЕНИЙ', 'TALKSPACE' ),
	'talkspacee'                => array( '1', 'ЧУГААДЕЛГЕМИ2', 'ПРОСТРАНСТВО_ОБСУЖДЕНИЙ_2', 'TALKSPACEE' ),
	'img_right'                 => array( '1', 'оң', 'справа', 'right' ),
	'img_left'                  => array( '1', 'солагай', 'слева', 'left' ),
	'img_center'                => array( '1', 'төп', 'центр', 'center', 'centre' ),
	'sitename'                  => array( '1', 'САЙТТЫҢАДЫ', 'НАЗВАНИЕ_САЙТА', 'SITENAME' ),
	'ns'                        => array( '0', 'АД:', 'ПИ:', 'NS:' ),
	'nse'                       => array( '0', 'АД2:', 'ПИК:', 'NSE:' ),
	'currentweek'               => array( '1', 'АМГЫЧЕДИХОНУК', 'ТЕКУЩАЯ_НЕДЕЛЯ', 'CURRENTWEEK' ),
	'currentdow'                => array( '1', 'АМГЫЧЕДИХОНУКТУҢХҮНҮ', 'ТЕКУЩИЙ_ДЕНЬ_НЕДЕЛИ', 'CURRENTDOW' ),
	'raw'                       => array( '0', 'ЧИГ:', 'НЕОБРАБ:', 'RAW:' ),
	'language'                  => array( '0', '#ДЫЛ:', '#ЯЗЫК:', '#LANGUAGE:' ),
	'special'                   => array( '0', 'тускай', 'служебная', 'special' ),
	'tag'                       => array( '0', 'демдек', 'метка', 'тег', 'тэг', 'tag' ),
	'hiddencat'                 => array( '1', '__ЧАЖЫТ_АҢГЫЛАЛ__', '__СКРЫТАЯ_КАТЕГОРИЯ__', '__HIDDENCAT__' ),
	'pagesincategory'           => array( '1', '__АҢГЫЛАЛ_АРЫННАРЫ__', 'СТРАНИЦ_В_КАТЕГОРИИ', 'PAGESINCATEGORY', 'PAGESINCAT' ),
	'pagesincategory_all'       => array( '0', 'шупту', 'все', 'all' ),
	'pagesincategory_pages'     => array( '0', 'арыннар', 'страницы', 'pages' ),
	'pagesincategory_files'     => array( '0', 'файлдар', 'файлы', 'files' ),
);

$bookstoreList = array(
	'ОЗОН' => 'http://www.ozon.ru/?context=advsearch_book&isbn=$1',
	'Books.Ru' => 'http://www.books.ru/shop/search/advanced?as%5Btype%5D=books&as%5Bname%5D=&as%5Bisbn%5D=$1&as%5Bauthor%5D=&as%5Bmaker%5D=&as%5Bcontents%5D=&as%5Binfo%5D=&as%5Bdate_after%5D=&as%5Bdate_before%5D=&as%5Bprice_less%5D=&as%5Bprice_more%5D=&as%5Bstrict%5D=%E4%E0&as%5Bsub%5D=%E8%F1%EA%E0%F2%FC&x=22&y=8',
	'Яндекс.Маркет' => 'http://market.yandex.ru/search.xml?text=$1',
	'Amazon.com' => 'http://www.amazon.com/exec/obidos/ISBN=$1',
	'AddALL' => 'http://www.addall.com/New/Partner.cgi?query=$1&type=ISBN',
	'Barnes & Noble' => 'http://shop.barnesandnoble.com/bookSearch/isbnInquiry.asp?isbn=$1'
);

