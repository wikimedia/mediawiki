<?php
/** Armenian (Հայերեն)
 *
 * To improve a translation please visit https://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 */

$separatorTransformTable = [
	',' => "\xc2\xa0", # nbsp
	'.' => ','
];

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

$magicWords = [
	'redirect'                  => [ '0', '#ՎԵՐԱՀՂՈՒՄ', '#REDIRECT' ],
	'notoc'                     => [ '0', '__ԱՌԱՆՑ_ԲՈՎ__', '__NOTOC__' ],
	'nogallery'                 => [ '0', '__ԱՌԱՆՑ_ՍՐԱՀԻ__', '__NOGALLERY__' ],
	'forcetoc'                  => [ '0', '__ՍՏԻՊԵԼ_ԲՈՎ__', '__FORCETOC__' ],
	'toc'                       => [ '0', '__ԲՈՎ__', '__TOC__' ],
	'noeditsection'             => [ '0', '__ԱՌԱՆՑ_ԲԱԺՆԻ_ԽՄԲԱԳՐՄԱՆ__', '__NOEDITSECTION__' ],
	'currentmonth'              => [ '1', 'ԸՆԹԱՑԻՔ_ԱՄԻՍԸ', 'CURRENTMONTH', 'CURRENTMONTH2' ],
	'currentmonthname'          => [ '1', 'ԸՆԹԱՑԻՔ_ԱՄՍՎԱ_ԱՆՈՒՆԸ', 'CURRENTMONTHNAME' ],
	'currentmonthnamegen'       => [ '1', 'ԸՆԹԱՑԻՔ_ԱՄՍՎԱ_ԱՆՈՒՆԸ_ՍԵՌ', 'CURRENTMONTHNAMEGEN' ],
	'currentmonthabbrev'        => [ '1', 'ԸՆԹԱՑԻՔ_ԱՄՍՎԱ_ԱՆՎԱՆ_ՀԱՊԱՎՈՒՄԸ', 'CURRENTMONTHABBREV' ],
	'currentday'                => [ '1', 'ԸՆԹԱՑԻՔ_ՕՐԸ', 'CURRENTDAY' ],
	'currentday2'               => [ '1', 'ԸՆԹԱՑԻՔ_ՕՐԸ_2', 'CURRENTDAY2' ],
	'currentdayname'            => [ '1', 'ԸՆԹԱՑԻՔ_ՕՐՎԱ_ԱՆՈՒՆԸ', 'CURRENTDAYNAME' ],
	'currentyear'               => [ '1', 'ԸՆԹԱՑԻՔ_ՏԱՐԻՆ', 'CURRENTYEAR' ],
	'currenttime'               => [ '1', 'ԸՆԹԱՑԻՔ_ԺԱՄԱՆԱԿԸ', 'CURRENTTIME' ],
	'currenthour'               => [ '1', 'ԸՆԹԱՑԻՔ_ԺԱՄԸ', 'CURRENTHOUR' ],
	'localmonth'                => [ '1', 'ՏԵՂԱԿԱՆ_ԱՄԻՍԸ', 'LOCALMONTH', 'LOCALMONTH2' ],
	'localmonthname'            => [ '1', 'ՏԵՂԱԿԱՆ_ԱՄՍՎԱ_ԱՆՈՒՆԸ', 'LOCALMONTHNAME' ],
	'localmonthnamegen'         => [ '1', 'ՏԵՂԱԿԱՆ_ԱՄՍՎԱ_ԱՆՈՒՆԸ_ՍԵՌ', 'LOCALMONTHNAMEGEN' ],
	'localmonthabbrev'          => [ '1', 'ՏԵՂԱԿԱՆ_ԱՄՍՎԱ_ԱՆՎԱՆ_ՀԱՊԱՎՈՒՄԸ', 'LOCALMONTHABBREV' ],
	'localday'                  => [ '1', 'ՏԵՂԱԿԱՆ_ՕՐԸ', 'LOCALDAY' ],
	'localday2'                 => [ '1', 'ՏԵՂԱԿԱՆ_ՕՐԸ_2', 'LOCALDAY2' ],
	'localdayname'              => [ '1', 'ՏԵՂԱԿԱՆ_ՕՐՎԱ_ԱՆՈՒՆԸ', 'LOCALDAYNAME' ],
	'localyear'                 => [ '1', 'ՏԵՂԱԿԱՆ_ՏԱՐԻՆ', 'LOCALYEAR' ],
	'localtime'                 => [ '1', 'ՏԵՂԱԿԱՆ_ԺԱՄԱՆԱԿԸ', 'LOCALTIME' ],
	'localhour'                 => [ '1', 'ՏԵՂԱԿԱՆ_ԺԱՄԸ', 'LOCALHOUR' ],
	'numberofpages'             => [ '1', 'ԷՋԵՐԻ_ՔԱՆԱԿԸ', 'NUMBEROFPAGES' ],
	'numberofarticles'          => [ '1', 'ՀՈԴՎԱԾՆԵՐԻ_ՔԱՆԱԿԸ', 'NUMBEROFARTICLES' ],
	'numberoffiles'             => [ '1', 'ՖԱՅԼԵՐԻ_ՔԱՆԱԿԸ', 'NUMBEROFFILES' ],
	'numberofusers'             => [ '1', 'ՄԱՍՆԱԿԻՑՆԵՐԻ_ՔԱՆԱԿԸ', 'NUMBEROFUSERS' ],
	'pagename'                  => [ '1', 'ԷՋԻ_ԱՆՈՒՆԸ', 'PAGENAME' ],
	'pagenamee'                 => [ '1', 'ԷՋԻ_ԱՆՈՒՆԸ_2', 'PAGENAMEE' ],
	'namespace'                 => [ '1', 'ԱՆՎԱՆԱՏԱՐԱԾՔ', 'NAMESPACE' ],
	'namespacee'                => [ '1', 'ԱՆՎԱՆԱՏԱՐԱԾՔ_2', 'NAMESPACEE' ],
	'talkspace'                 => [ '1', 'ՔՆՆԱՐԿՄԱՆ_ՏԱՐԱԾՔԸ', 'TALKSPACE' ],
	'talkspacee'                => [ '1', 'ՔՆՆԱՐԿՄԱՆ_ՏԱՐԱԾՔԸ_2', 'TALKSPACEE' ],
	'subjectspace'              => [ '1', 'ՀՈԴՎԱԾՆԵՐԻ_ՏԱՐԱԾՔԸ', 'SUBJECTSPACE', 'ARTICLESPACE' ],
	'subjectspacee'             => [ '1', 'ՀՈԴՎԱԾՆԵՐԻ_ՏԱՐԱԾՔԸ_2', 'SUBJECTSPACEE', 'ARTICLESPACEE' ],
	'fullpagename'              => [ '1', 'ARTICLESPACE', 'ԷՋԻ_ԼՐԻՎ_ԱՆՎԱՆՈՒՄԸ', 'FULLPAGENAME' ],
	'fullpagenamee'             => [ '1', 'ԷՋԻ_ԼՐԻՎ_ԱՆՎԱՆՈՒՄԸ_2', 'FULLPAGENAMEE' ],
	'subpagename'               => [ '1', 'ԵՆԹԱԷՋԻ_ԱՆՎԱՆՈՒՄԸ', 'SUBPAGENAME' ],
	'subpagenamee'              => [ '1', 'ԵՆԹԱԷՋԻ_ԱՆՎԱՆՈՒՄԸ_2', 'SUBPAGENAMEE' ],
	'basepagename'              => [ '1', 'ՀԻՄՆԱԿԱՆ_ԷՋԻ_ԱՆՎԱՆՈՒՄԸ', 'BASEPAGENAME' ],
	'basepagenamee'             => [ '1', 'ՀԻՄՆԱԿԱՆ_ԷՋԻ_ԱՆՎԱՆՈՒՄԸ_2', 'BASEPAGENAMEE' ],
	'talkpagename'              => [ '1', 'ՔՆՆԱՐԿՄԱՆ_ԷՋԻ_ԱՆՎԱՆՈՒՄԸ', 'TALKPAGENAME' ],
	'talkpagenamee'             => [ '1', 'ՔՆՆԱՐԿՄԱՆ_ԷՋԻ_ԱՆՎԱՆՈՒՄԸ_2', 'TALKPAGENAMEE' ],
	'subjectpagename'           => [ '1', 'ՀՈԴՎԱԾԻ_ԷՋԻ_ԱՆՎԱՆՈՒՄԸ', 'SUBJECTPAGENAME', 'ARTICLEPAGENAME' ],
	'subjectpagenamee'          => [ '1', 'ՀՈԴՎԱԾԻ_ԷՋԻ_ԱՆՎԱՆՈՒՄԸ_2', 'SUBJECTPAGENAMEE', 'ARTICLEPAGENAMEE' ],
	'msg'                       => [ '0', 'ՀՈՂՈՐԴ՝', 'MSG:' ],
	'msgnw'                     => [ '0', 'ՀՈՂՈՐԴ_ԱՌԱՆՑ_ՎԻՔԻԻ՝', 'MSGNW:' ],
	'img_thumbnail'             => [ '1', 'մինի', 'thumb', 'thumbnail' ],
	'img_manualthumb'           => [ '1', 'մինի=$1', 'thumbnail=$1', 'thumb=$1' ],
	'img_right'                 => [ '1', 'աջից', 'right' ],
	'img_left'                  => [ '1', 'ձախից', 'left' ],
	'img_none'                  => [ '1', 'առանց', 'none' ],
	'img_width'                 => [ '1', '$1փքս', '$1px' ],
	'img_center'                => [ '1', 'կենտրոն', 'center', 'centre' ],
	'img_framed'                => [ '1', 'շրջափակել', 'frame', 'framed', 'enframed' ],
	'img_page'                  => [ '1', 'էջը=$1', 'էջ $1', 'page=$1', 'page $1' ],
	'int'                       => [ '0', 'ՆԵՐՔ՝', 'INT:' ],
	'sitename'                  => [ '1', 'ԿԱՅՔԻ_ԱՆՈՒՆԸ', 'SITENAME' ],
	'ns'                        => [ '0', 'ԱՏ՝', 'NS:' ],
	'localurl'                  => [ '0', 'ՏԵՂԱԿԱՆ_ՀԱՍՑԵՆ՝', 'LOCALURL:' ],
	'localurle'                 => [ '0', 'ՏԵՂԱԿԱՆ_ՀԱՍՑԵՆ_2՝', 'LOCALURLE:' ],
	'server'                    => [ '0', 'ՍԵՐՎԵՐԸ', 'SERVER' ],
	'servername'                => [ '0', 'ՍԵՐՎԵՐԻ_ԱՆՈՒՆԸ', 'SERVERNAME' ],
	'scriptpath'                => [ '0', 'ՍՔՐԻՊՏԻ_ՃԱՆԱՊԱՐՀԸ', 'SCRIPTPATH' ],
	'grammar'                   => [ '0', 'ՀՈԼՈՎ՛', 'GRAMMAR:' ],
	'notitleconvert'            => [ '0', '__ԱՌԱՆՑ_ՎԵՐՆԱԳՐԻ_ՓՈՓՈԽՄԱՆ__', '__NOTITLECONVERT__', '__NOTC__' ],
	'nocontentconvert'          => [ '0', '__ԱՌԱՆՑ_ՊԱՐՈՒՆԱԿՈՒԹՅԱՆ_ՓՈՓՈԽՄԱՆ__', '__NOCONTENTCONVERT__', '__NOCC__' ],
	'currentweek'               => [ '1', 'ԸՆԹԱՑԻՔ_ՇԱԲԱԹԸ', 'CURRENTWEEK' ],
	'currentdow'                => [ '1', 'ԸՆԹԱՑԻՔ_ՇԱԲԱԹՎԱ_ՕՐԸ', 'CURRENTDOW' ],
	'localweek'                 => [ '1', 'ՏԵՂԱԿԱՆ_ՇԱԲԱԹՎԸ', 'LOCALWEEK' ],
	'localdow'                  => [ '1', 'ՏԵՂԱԿԱՆ_ՇԱԲԱԹՎԱ_ՕՐԸ', 'LOCALDOW' ],
	'revisionid'                => [ '1', 'ՏԱՐԲԵՐԱԿԻ_ՀԱՄԱՐԸ', 'REVISIONID' ],
	'revisionday'               => [ '1', 'ՏԱՐԲԵՐԱԿԻ_ՕՐԸ', 'REVISIONDAY' ],
	'revisionday2'              => [ '1', 'ՏԱՐԲԵՐԱԿԻ_ՕՐԸ_2', 'REVISIONDAY2' ],
	'revisionmonth'             => [ '1', 'ՏԱՐԲԵՐԱԿԻ_ԱՄԻՍԸ', 'REVISIONMONTH' ],
	'revisionyear'              => [ '1', 'ՏԱՐԲԵՐԱԿԻ_ՏԱՐԻՆ', 'REVISIONYEAR' ],
	'plural'                    => [ '0', 'ՀՈԳՆԱԿԻ՝', 'PLURAL:' ],
	'fullurl'                   => [ '0', 'ԼՐԻՎ_ՀԱՍՑԵՆ՝', 'FULLURL:' ],
	'fullurle'                  => [ '0', 'ԼՐԻՎ_ՀԱՍՑԵՆ_2՝', 'FULLURLE:' ],
	'lcfirst'                   => [ '0', 'ՓՈՔՐԱՏԱՌ_ՍԿԶԲՆԱՏԱՌ՝', 'LCFIRST:' ],
	'ucfirst'                   => [ '0', 'ՄԵԾԱՏԱՌ_ՍԿԶԲՆԱՏԱՌ՝', 'UCFIRST:' ],
	'lc'                        => [ '0', 'ՓՈՔՐԱՏԱՌ՝', 'LC:' ],
	'uc'                        => [ '0', 'ՄԵԾԱՏԱՌ՝', 'UC:' ],
	'displaytitle'              => [ '1', 'ՑՈՒՅՑ_ՏԱԼ_ՎԵՐՆԱԳԻՐԸ', 'DISPLAYTITLE' ],
	'rawsuffix'                 => [ '1', 'Չ', 'R' ],
	'newsectionlink'            => [ '1', '__ՀՂՈՒՄ_ՆՈՐ_ԲԱԺՆԻ_ՎՐԱ__', '__NEWSECTIONLINK__' ],
	'currentversion'            => [ '1', 'ԸՆԹԱՑԻՔ_ՏԱՐԲԵՐԱԿԸ', 'CURRENTVERSION' ],
	'urlencode'                 => [ '0', 'ՄՇԱԿՎԱԾ_ՀԱՍՑԵ՛', 'URLENCODE:' ],
	'currenttimestamp'          => [ '1', 'ԸՆԹԱՑԻՔ_ԺԱՄԱՆԱԿԻ_ԴՐՈՇՄ', 'CURRENTTIMESTAMP' ],
	'localtimestamp'            => [ '1', 'ՏԵՂԱԿԱՆ_ԺԱՄԱՆԱԿԻ_ԴՐՈՇՄ', 'LOCALTIMESTAMP' ],
	'directionmark'             => [ '1', 'ՆԱՄԱԿԻ_ՈՒՂՂՈՒԹՅՈՒՆԸ', 'DIRECTIONMARK', 'DIRMARK' ],
	'language'                  => [ '0', '#ԼԵԶՈՒ՝', '#LANGUAGE:' ],
	'contentlanguage'           => [ '1', 'ՊԱՐՈՒՆԱԿՈՒԹՅԱՆ_ԼԵԶՈՒՆ', 'CONTENTLANGUAGE', 'CONTENTLANG' ],
	'pagesinnamespace'          => [ '1', 'ԷՋԵՐ_ԱՆՎԱՆԱՏԱՐԱԾՔՈՒՄ՝', 'PAGESINNAMESPACE:', 'PAGESINNS:' ],
	'numberofadmins'            => [ '1', 'ԱԴՄԻՆՆԵՐԻ_ՔԱՆԱԿԸ', 'NUMBEROFADMINS' ],
	'formatnum'                 => [ '0', 'ՁԵՎԵԼ_ԹԻՎԸ', 'FORMATNUM' ],
	'padleft'                   => [ '0', 'ԼՐԱՑՆԵԼ_ՁԱԽԻՑ', 'PADLEFT' ],
	'padright'                  => [ '0', 'ԼՐԱՑՆԵԼ_ԱՋԻՑ', 'PADRIGHT' ],
	'special'                   => [ '0', 'սպասարկող', 'special' ],
	'defaultsort'               => [ '1', 'ԼՌՈՒԹՅԱՄԲ_ԴԱՍԱՎՈՐՈՒՄ՝', 'DEFAULTSORT:', 'DEFAULTSORTKEY:', 'DEFAULTCATEGORYSORT:' ],
];

$specialPageAliases = [
	'Allmessages'               => [ 'Բոլորուղերձները' ],
	'Allpages'                  => [ 'Բոլորէջերը' ],
	'Ancientpages'              => [ 'Ամենահինէջերը' ],
	'Block'                     => [ 'Արգելափակել այփին' ],
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
	'BlockList'                 => [ 'Արգելափակված այփի ները' ],
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
