<?php
/** Armenian (Հայերեն)
 *
 * @file
 * @ingroup Languages
 */

$separatorTransformTable = [
	',' => "\u{00A0}", # nbsp
	'.' => ','
];
$minimumGroupingDigits = 2; // intentional deviation from CLDR (T262500)

$fallback8bitEncoding = 'UTF-8';

$linkPrefixExtension = true;

$namespaceNames = [
	NS_MEDIA            => 'Մեդիա',
	NS_SPECIAL          => 'Սպասարկող',
	NS_TALK             => 'Քննարկում',
	NS_USER             => 'Մասնակից',
	NS_USER_TALK        => 'Մասնակցի_քննարկում',
	NS_PROJECT_TALK     => '{{GRAMMAR:genitive|$1}}_քննարկում',
	NS_FILE             => 'Պատկեր',
	NS_FILE_TALK        => 'Պատկերի_քննարկում',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki_քննարկում',
	NS_TEMPLATE         => 'Կաղապար',
	NS_TEMPLATE_TALK    => 'Կաղապարի_քննարկում',
	NS_HELP             => 'Օգնություն',
	NS_HELP_TALK        => 'Օգնության_քննարկում',
	NS_CATEGORY         => 'Կատեգորիա',
	NS_CATEGORY_TALK    => 'Կատեգորիայի_քննարկում',
];

$namespaceAliases = [
	'Սպասարկող' => NS_SPECIAL,
];

$datePreferences = [
	'default',
	'mdy',
	'dmy',
	'ymd',
	'ISO 8601',
];

$defaultDateFormat = 'dmy or mdy';

$datePreferenceMigrationMap = [
	'default',
	'mdy',
	'dmy',
	'ymd'
];

$dateFormats = [
	'mdy time' => 'H:i',
	'mdy date' => 'xg j, Y',
	'mdy both' => 'H:i, xg j, Y',

	'dmy time' => 'H:i',
	'dmy date' => 'j xg Y',
	'dmy both' => 'H:i, j xg Y',

	'ymd time' => 'H:i',
	'ymd date' => 'Y xg j',
	'ymd both' => 'H:i, Y xg j',

	'ISO 8601 time' => 'xnH:xni:xns',
	'ISO 8601 date' => 'xnY-xnm-xnd',
	'ISO 8601 both' => 'xnY-xnm-xnd"T"xnH:xni:xns',
];

$bookstoreList = [
	'Amazon.com' => 'https://www.amazon.com/exec/obidos/ISBN=$1'
];

/** @phpcs-require-sorted-array */
$magicWords = [
	'basepagename'              => [ '1', 'ՀԻՄՆԱԿԱՆ_ԷՋԻ_ԱՆՎԱՆՈՒՄԸ', 'BASEPAGENAME' ],
	'basepagenamee'             => [ '1', 'ՀԻՄՆԱԿԱՆ_ԷՋԻ_ԱՆՎԱՆՈՒՄԸ_2', 'BASEPAGENAMEE' ],
	'contentlanguage'           => [ '1', 'ՊԱՐՈՒՆԱԿՈՒԹՅԱՆ_ԼԵԶՈՒՆ', 'CONTENTLANGUAGE', 'CONTENTLANG' ],
	'currentday'                => [ '1', 'ԸՆԹԱՑԻՔ_ՕՐԸ', 'CURRENTDAY' ],
	'currentday2'               => [ '1', 'ԸՆԹԱՑԻՔ_ՕՐԸ_2', 'CURRENTDAY2' ],
	'currentdayname'            => [ '1', 'ԸՆԹԱՑԻՔ_ՕՐՎԱ_ԱՆՈՒՆԸ', 'CURRENTDAYNAME' ],
	'currentdow'                => [ '1', 'ԸՆԹԱՑԻՔ_ՇԱԲԱԹՎԱ_ՕՐԸ', 'CURRENTDOW' ],
	'currenthour'               => [ '1', 'ԸՆԹԱՑԻՔ_ԺԱՄԸ', 'CURRENTHOUR' ],
	'currentmonth'              => [ '1', 'ԸՆԹԱՑԻՔ_ԱՄԻՍԸ', 'CURRENTMONTH', 'CURRENTMONTH2' ],
	'currentmonthabbrev'        => [ '1', 'ԸՆԹԱՑԻՔ_ԱՄՍՎԱ_ԱՆՎԱՆ_ՀԱՊԱՎՈՒՄԸ', 'CURRENTMONTHABBREV' ],
	'currentmonthname'          => [ '1', 'ԸՆԹԱՑԻՔ_ԱՄՍՎԱ_ԱՆՈՒՆԸ', 'CURRENTMONTHNAME' ],
	'currentmonthnamegen'       => [ '1', 'ԸՆԹԱՑԻՔ_ԱՄՍՎԱ_ԱՆՈՒՆԸ_ՍԵՌ', 'CURRENTMONTHNAMEGEN' ],
	'currenttime'               => [ '1', 'ԸՆԹԱՑԻՔ_ԺԱՄԱՆԱԿԸ', 'CURRENTTIME' ],
	'currenttimestamp'          => [ '1', 'ԸՆԹԱՑԻՔ_ԺԱՄԱՆԱԿԻ_ԴՐՈՇՄ', 'CURRENTTIMESTAMP' ],
	'currentversion'            => [ '1', 'ԸՆԹԱՑԻՔ_ՏԱՐԲԵՐԱԿԸ', 'CURRENTVERSION' ],
	'currentweek'               => [ '1', 'ԸՆԹԱՑԻՔ_ՇԱԲԱԹԸ', 'CURRENTWEEK' ],
	'currentyear'               => [ '1', 'ԸՆԹԱՑԻՔ_ՏԱՐԻՆ', 'CURRENTYEAR' ],
	'defaultsort'               => [ '1', 'ԼՌՈՒԹՅԱՄԲ_ԴԱՍԱՎՈՐՈՒՄ՝', 'DEFAULTSORT:', 'DEFAULTSORTKEY:', 'DEFAULTCATEGORYSORT:' ],
	'directionmark'             => [ '1', 'ՆԱՄԱԿԻ_ՈՒՂՂՈՒԹՅՈՒՆԸ', 'DIRECTIONMARK', 'DIRMARK' ],
	'displaytitle'              => [ '1', 'ՑՈՒՅՑ_ՏԱԼ_ՎԵՐՆԱԳԻՐԸ', 'DISPLAYTITLE' ],
	'forcetoc'                  => [ '0', '__ՍՏԻՊԵԼ_ԲՈՎ__', '__FORCETOC__' ],
	'formatnum'                 => [ '0', 'ՁԵՎԵԼ_ԹԻՎԸ', 'FORMATNUM' ],
	'fullpagename'              => [ '1', 'ARTICLESPACE', 'ԷՋԻ_ԼՐԻՎ_ԱՆՎԱՆՈՒՄԸ', 'FULLPAGENAME' ],
	'fullpagenamee'             => [ '1', 'ԷՋԻ_ԼՐԻՎ_ԱՆՎԱՆՈՒՄԸ_2', 'FULLPAGENAMEE' ],
	'fullurl'                   => [ '0', 'ԼՐԻՎ_ՀԱՍՑԵՆ՝', 'FULLURL:' ],
	'fullurle'                  => [ '0', 'ԼՐԻՎ_ՀԱՍՑԵՆ_2՝', 'FULLURLE:' ],
	'grammar'                   => [ '0', 'ՀՈԼՈՎ՛', 'GRAMMAR:' ],
	'img_center'                => [ '1', 'կենտրոն', 'center', 'centre' ],
	'img_framed'                => [ '1', 'շրջափակել', 'frame', 'framed', 'enframed' ],
	'img_left'                  => [ '1', 'ձախից', 'left' ],
	'img_manualthumb'           => [ '1', 'մինի=$1', 'thumbnail=$1', 'thumb=$1' ],
	'img_none'                  => [ '1', 'առանց', 'none' ],
	'img_page'                  => [ '1', 'էջը=$1', 'էջ $1', 'page=$1', 'page $1' ],
	'img_right'                 => [ '1', 'աջից', 'right' ],
	'img_thumbnail'             => [ '1', 'մինի', 'thumb', 'thumbnail' ],
	'img_width'                 => [ '1', '$1փքս', '$1px' ],
	'int'                       => [ '0', 'ՆԵՐՔ՝', 'INT:' ],
	'language'                  => [ '0', '#ԼԵԶՈՒ՝', '#LANGUAGE' ],
	'lc'                        => [ '0', 'ՓՈՔՐԱՏԱՌ՝', 'LC:' ],
	'lcfirst'                   => [ '0', 'ՓՈՔՐԱՏԱՌ_ՍԿԶԲՆԱՏԱՌ՝', 'LCFIRST:' ],
	'localday'                  => [ '1', 'ՏԵՂԱԿԱՆ_ՕՐԸ', 'LOCALDAY' ],
	'localday2'                 => [ '1', 'ՏԵՂԱԿԱՆ_ՕՐԸ_2', 'LOCALDAY2' ],
	'localdayname'              => [ '1', 'ՏԵՂԱԿԱՆ_ՕՐՎԱ_ԱՆՈՒՆԸ', 'LOCALDAYNAME' ],
	'localdow'                  => [ '1', 'ՏԵՂԱԿԱՆ_ՇԱԲԱԹՎԱ_ՕՐԸ', 'LOCALDOW' ],
	'localhour'                 => [ '1', 'ՏԵՂԱԿԱՆ_ԺԱՄԸ', 'LOCALHOUR' ],
	'localmonth'                => [ '1', 'ՏԵՂԱԿԱՆ_ԱՄԻՍԸ', 'LOCALMONTH', 'LOCALMONTH2' ],
	'localmonthabbrev'          => [ '1', 'ՏԵՂԱԿԱՆ_ԱՄՍՎԱ_ԱՆՎԱՆ_ՀԱՊԱՎՈՒՄԸ', 'LOCALMONTHABBREV' ],
	'localmonthname'            => [ '1', 'ՏԵՂԱԿԱՆ_ԱՄՍՎԱ_ԱՆՈՒՆԸ', 'LOCALMONTHNAME' ],
	'localmonthnamegen'         => [ '1', 'ՏԵՂԱԿԱՆ_ԱՄՍՎԱ_ԱՆՈՒՆԸ_ՍԵՌ', 'LOCALMONTHNAMEGEN' ],
	'localtime'                 => [ '1', 'ՏԵՂԱԿԱՆ_ԺԱՄԱՆԱԿԸ', 'LOCALTIME' ],
	'localtimestamp'            => [ '1', 'ՏԵՂԱԿԱՆ_ԺԱՄԱՆԱԿԻ_ԴՐՈՇՄ', 'LOCALTIMESTAMP' ],
	'localurl'                  => [ '0', 'ՏԵՂԱԿԱՆ_ՀԱՍՑԵՆ՝', 'LOCALURL:' ],
	'localurle'                 => [ '0', 'ՏԵՂԱԿԱՆ_ՀԱՍՑԵՆ_2՝', 'LOCALURLE:' ],
	'localweek'                 => [ '1', 'ՏԵՂԱԿԱՆ_ՇԱԲԱԹՎԸ', 'LOCALWEEK' ],
	'localyear'                 => [ '1', 'ՏԵՂԱԿԱՆ_ՏԱՐԻՆ', 'LOCALYEAR' ],
	'msg'                       => [ '0', 'ՀՈՂՈՐԴ՝', 'MSG:' ],
	'msgnw'                     => [ '0', 'ՀՈՂՈՐԴ_ԱՌԱՆՑ_ՎԻՔԻԻ՝', 'MSGNW:' ],
	'namespace'                 => [ '1', 'ԱՆՎԱՆԱՏԱՐԱԾՔ', 'NAMESPACE' ],
	'namespacee'                => [ '1', 'ԱՆՎԱՆԱՏԱՐԱԾՔ_2', 'NAMESPACEE' ],
	'newsectionlink'            => [ '1', '__ՀՂՈՒՄ_ՆՈՐ_ԲԱԺՆԻ_ՎՐԱ__', '__NEWSECTIONLINK__' ],
	'nocontentconvert'          => [ '0', '__ԱՌԱՆՑ_ՊԱՐՈՒՆԱԿՈՒԹՅԱՆ_ՓՈՓՈԽՄԱՆ__', '__NOCONTENTCONVERT__', '__NOCC__' ],
	'noeditsection'             => [ '0', '__ԱՌԱՆՑ_ԲԱԺՆԻ_ԽՄԲԱԳՐՄԱՆ__', '__NOEDITSECTION__' ],
	'nogallery'                 => [ '0', '__ԱՌԱՆՑ_ՍՐԱՀԻ__', '__NOGALLERY__' ],
	'notitleconvert'            => [ '0', '__ԱՌԱՆՑ_ՎԵՐՆԱԳՐԻ_ՓՈՓՈԽՄԱՆ__', '__NOTITLECONVERT__', '__NOTC__' ],
	'notoc'                     => [ '0', '__ԱՌԱՆՑ_ԲՈՎ__', '__NOTOC__' ],
	'ns'                        => [ '0', 'ԱՏ՝', 'NS:' ],
	'numberofadmins'            => [ '1', 'ԱԴՄԻՆՆԵՐԻ_ՔԱՆԱԿԸ', 'NUMBEROFADMINS' ],
	'numberofarticles'          => [ '1', 'ՀՈԴՎԱԾՆԵՐԻ_ՔԱՆԱԿԸ', 'NUMBEROFARTICLES' ],
	'numberoffiles'             => [ '1', 'ՖԱՅԼԵՐԻ_ՔԱՆԱԿԸ', 'NUMBEROFFILES' ],
	'numberofpages'             => [ '1', 'ԷՋԵՐԻ_ՔԱՆԱԿԸ', 'NUMBEROFPAGES' ],
	'numberofusers'             => [ '1', 'ՄԱՍՆԱԿԻՑՆԵՐԻ_ՔԱՆԱԿԸ', 'NUMBEROFUSERS' ],
	'padleft'                   => [ '0', 'ԼՐԱՑՆԵԼ_ՁԱԽԻՑ', 'PADLEFT' ],
	'padright'                  => [ '0', 'ԼՐԱՑՆԵԼ_ԱՋԻՑ', 'PADRIGHT' ],
	'pagename'                  => [ '1', 'ԷՋԻ_ԱՆՈՒՆԸ', 'PAGENAME' ],
	'pagenamee'                 => [ '1', 'ԷՋԻ_ԱՆՈՒՆԸ_2', 'PAGENAMEE' ],
	'pagesinnamespace'          => [ '1', 'ԷՋԵՐ_ԱՆՎԱՆԱՏԱՐԱԾՔՈՒՄ՝', 'PAGESINNAMESPACE:', 'PAGESINNS:' ],
	'plural'                    => [ '0', 'ՀՈԳՆԱԿԻ՝', 'PLURAL:' ],
	'rawsuffix'                 => [ '1', 'Չ', 'R' ],
	'redirect'                  => [ '0', '#ՎԵՐԱՀՂՈՒՄ', '#REDIRECT' ],
	'revisionday'               => [ '1', 'ՏԱՐԲԵՐԱԿԻ_ՕՐԸ', 'REVISIONDAY' ],
	'revisionday2'              => [ '1', 'ՏԱՐԲԵՐԱԿԻ_ՕՐԸ_2', 'REVISIONDAY2' ],
	'revisionid'                => [ '1', 'ՏԱՐԲԵՐԱԿԻ_ՀԱՄԱՐԸ', 'REVISIONID' ],
	'revisionmonth'             => [ '1', 'ՏԱՐԲԵՐԱԿԻ_ԱՄԻՍԸ', 'REVISIONMONTH' ],
	'revisionyear'              => [ '1', 'ՏԱՐԲԵՐԱԿԻ_ՏԱՐԻՆ', 'REVISIONYEAR' ],
	'scriptpath'                => [ '0', 'ՍՔՐԻՊՏԻ_ՃԱՆԱՊԱՐՀԸ', 'SCRIPTPATH' ],
	'server'                    => [ '0', 'ՍԵՐՎԵՐԸ', 'SERVER' ],
	'servername'                => [ '0', 'ՍԵՐՎԵՐԻ_ԱՆՈՒՆԸ', 'SERVERNAME' ],
	'sitename'                  => [ '1', 'ԿԱՅՔԻ_ԱՆՈՒՆԸ', 'SITENAME' ],
	'special'                   => [ '0', 'սպասարկող', 'special' ],
	'subjectpagename'           => [ '1', 'ՀՈԴՎԱԾԻ_ԷՋԻ_ԱՆՎԱՆՈՒՄԸ', 'SUBJECTPAGENAME', 'ARTICLEPAGENAME' ],
	'subjectpagenamee'          => [ '1', 'ՀՈԴՎԱԾԻ_ԷՋԻ_ԱՆՎԱՆՈՒՄԸ_2', 'SUBJECTPAGENAMEE', 'ARTICLEPAGENAMEE' ],
	'subjectspace'              => [ '1', 'ՀՈԴՎԱԾՆԵՐԻ_ՏԱՐԱԾՔԸ', 'SUBJECTSPACE', 'ARTICLESPACE' ],
	'subjectspacee'             => [ '1', 'ՀՈԴՎԱԾՆԵՐԻ_ՏԱՐԱԾՔԸ_2', 'SUBJECTSPACEE', 'ARTICLESPACEE' ],
	'subpagename'               => [ '1', 'ԵՆԹԱԷՋԻ_ԱՆՎԱՆՈՒՄԸ', 'SUBPAGENAME' ],
	'subpagenamee'              => [ '1', 'ԵՆԹԱԷՋԻ_ԱՆՎԱՆՈՒՄԸ_2', 'SUBPAGENAMEE' ],
	'talkpagename'              => [ '1', 'ՔՆՆԱՐԿՄԱՆ_ԷՋԻ_ԱՆՎԱՆՈՒՄԸ', 'TALKPAGENAME' ],
	'talkpagenamee'             => [ '1', 'ՔՆՆԱՐԿՄԱՆ_ԷՋԻ_ԱՆՎԱՆՈՒՄԸ_2', 'TALKPAGENAMEE' ],
	'talkspace'                 => [ '1', 'ՔՆՆԱՐԿՄԱՆ_ՏԱՐԱԾՔԸ', 'TALKSPACE' ],
	'talkspacee'                => [ '1', 'ՔՆՆԱՐԿՄԱՆ_ՏԱՐԱԾՔԸ_2', 'TALKSPACEE' ],
	'toc'                       => [ '0', '__ԲՈՎ__', '__TOC__' ],
	'uc'                        => [ '0', 'ՄԵԾԱՏԱՌ՝', 'UC:' ],
	'ucfirst'                   => [ '0', 'ՄԵԾԱՏԱՌ_ՍԿԶԲՆԱՏԱՌ՝', 'UCFIRST:' ],
	'urlencode'                 => [ '0', 'ՄՇԱԿՎԱԾ_ՀԱՍՑԵ՛', 'URLENCODE:' ],
];

/** @phpcs-require-sorted-array */
$specialPageAliases = [
	'Allmessages'               => [ 'Բոլորուղերձները' ],
	'Allpages'                  => [ 'Բոլորէջերը' ],
	'Ancientpages'              => [ 'Ամենահինէջերը' ],
	'Block'                     => [ 'Արգելափակել_այփին' ],
	'BlockList'                 => [ 'Արգելափակված_այփի_ները' ],
	'Booksources'               => [ 'Գրքայինաղբյուրները' ],
	'BrokenRedirects'           => [ 'Կոտրվածվերահղումները' ],
	'Categories'                => [ 'Կատեգորիաները' ],
	'ChangePassword'            => [ 'Նորգաղտնաբառ' ],
	'Contributions'             => [ 'Ներդրումները' ],
	'Deadendpages'              => [ 'Հղումչպարունակողէջերը' ],
	'DoubleRedirects'           => [ 'Կրկնակիվերահղումները' ],
	'Emailuser'                 => [ 'Գրելնամակ' ],
	'Export'                    => [ 'Արտահանելէջերը' ],
	'FileDuplicateSearch'       => [ 'Կրկնօրինակֆայլերիորոնում' ],
	'Import'                    => [ 'Ներմուծել' ],
	'Listadmins'                => [ 'Ադմիններիցանկը' ],
	'Listfiles'                 => [ 'Պատկերներիցանկը' ],
	'Listredirects'             => [ 'Ցույցտալվերահղումները' ],
	'Listusers'                 => [ 'Մասնակիցներիցանկը' ],
	'Lockdb'                    => [ 'Կողպելտհ' ],
	'Log'                       => [ 'Տեղեկամատյան' ],
	'Lonelypages'               => [ 'Միայնակէջերը' ],
	'Longpages'                 => [ 'Երկարէջերը' ],
	'MIMEsearch'                => [ 'MIMEՈրոնում' ],
	'Mostcategories'            => [ 'Ամենաշատկատեգորիաներով' ],
	'Mostimages'                => [ 'Ամենաշատօգտագործվողնկարները' ],
	'Mostlinked'                => [ 'Ամենաշատհղումներով' ],
	'Mostlinkedcategories'      => [ 'Շատհղվողկատեգորիաները' ],
	'Mostrevisions'             => [ 'Ամենաշատփոփոխվող' ],
	'Movepage'                  => [ 'Տեղափոխելէջը' ],
	'Mycontributions'           => [ 'Իմներդրումները' ],
	'Mypage'                    => [ 'Իմէջը' ],
	'Mytalk'                    => [ 'Իմքննարկումները' ],
	'Newimages'                 => [ 'Նորպատկերներ' ],
	'Newpages'                  => [ 'Նորէջերը' ],
	'Preferences'               => [ 'Նախընտրությունները' ],
	'Prefixindex'               => [ 'Որոնումնախածանցով' ],
	'Randompage'                => [ 'Պատահականէջ' ],
	'Randomredirect'            => [ 'Պատահականվերահղում' ],
	'Recentchanges'             => [ 'Վերջինփոփոխությունները' ],
	'Recentchangeslinked'       => [ 'Կապվածէջերիփոփոխությունները' ],
	'Revisiondelete'            => [ 'Տարբերակիհեռացում' ],
	'Search'                    => [ 'Որոնել' ],
	'Shortpages'                => [ 'Կարճէջերը' ],
	'Specialpages'              => [ 'Սպասարկողէջերը' ],
	'Statistics'                => [ 'Վիճակագրություն' ],
	'Uncategorizedcategories'   => [ 'Չդասակարգվածկատեգորիաները' ],
	'Uncategorizedimages'       => [ 'Չդասակարգվածպատկերները' ],
	'Uncategorizedpages'        => [ 'Չդասակարգվածէջերը' ],
	'Uncategorizedtemplates'    => [ 'Չդասակարգվածկաղապարները' ],
	'Undelete'                  => [ 'Վերականգնել' ],
	'Unlockdb'                  => [ 'Բացանելտհ' ],
	'Unusedcategories'          => [ 'Չօգտագործվածկատեգորիաները' ],
	'Unusedimages'              => [ 'Չօգտագործվածպատկերները' ],
	'Unusedtemplates'           => [ 'Չօգտագործվողկաղապարները' ],
	'Unwatchedpages'            => [ 'Չհսկվողէջերը' ],
	'Upload'                    => [ 'Բեռնել' ],
	'Userlogin'                 => [ 'Մասնակցիմուտք' ],
	'Userlogout'                => [ 'Մասնակցիելք' ],
	'Userrights'                => [ 'Մասնակցիիրավունքները' ],
	'Version'                   => [ 'Տարբերակ' ],
	'Wantedcategories'          => [ 'Անհրաժեշտկատեգորիաները' ],
	'Wantedpages'               => [ 'Անհրաժեշտէջերը' ],
	'Watchlist'                 => [ 'Հսկողությանցանկը' ],
	'Whatlinkshere'             => [ 'Այստեղհղվողէջերը' ],
];

$linkTrail = '/^([a-zաբգդեզէըթժիլխծկհձղճմյնշոչպջռսվտրցւփքօֆև«»]+)(.*)$/sDu';
