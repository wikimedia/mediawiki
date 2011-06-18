<?php
/** Armenian (Հայերեն)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Chaojoker
 * @author Pandukht
 * @author Reedy
 * @author Ruben Vardanyan (me@RubenVardanyan.com)
 * @author Teak
 * @author Togaed
 * @author Xelgen
 * @author לערי ריינהארט
 */

$separatorTransformTable = array(
	',' => "\xc2\xa0", # nbsp
	'.' => ','
);

$fallback8bitEncoding = 'UTF-8';

$linkPrefixExtension = true;

$namespaceNames = array(
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
);

$namespaceAliases = array(
	'Սպասարկող' => NS_SPECIAL,
);


$datePreferences = array(
	'default',
	'mdy',
	'dmy',
	'ymd',
	'ISO 8601',
);

$defaultDateFormat = 'dmy or mdy';

$datePreferenceMigrationMap = array(
	'default',
	'mdy',
	'dmy',
	'ymd'
);

$dateFormats = array(
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
);

$bookstoreList = array(
	'Amazon.com' => 'http://www.amazon.com/exec/obidos/ISBN=$1'
);

$magicWords = array(
	'redirect'              => array( '0', '#ՎԵՐԱՀՂՈՒՄ', '#REDIRECT' ),
	'notoc'                 => array( '0', '__ԱՌԱՆՑ_ԲՈՎ__', '__NOTOC__' ),
	'nogallery'             => array( '0', '__ԱՌԱՆՑ_ՍՐԱՀԻ__', '__NOGALLERY__' ),
	'forcetoc'              => array( '0', '__ՍՏԻՊԵԼ_ԲՈՎ__', '__FORCETOC__' ),
	'toc'                   => array( '0', '__ԲՈՎ__', '__TOC__' ),
	'noeditsection'         => array( '0', '__ԱՌԱՆՑ_ԲԱԺՆԻ_ԽՄԲԱԳՐՄԱՆ__', '__NOEDITSECTION__' ),
	'currentmonth'          => array( '1', 'ԸՆԹԱՑԻՔ_ԱՄԻՍԸ', 'CURRENTMONTH', 'CURRENTMONTH2' ),
	'currentmonthname'      => array( '1', 'ԸՆԹԱՑԻՔ_ԱՄՍՎԱ_ԱՆՈՒՆԸ', 'CURRENTMONTHNAME' ),
	'currentmonthnamegen'   => array( '1', 'ԸՆԹԱՑԻՔ_ԱՄՍՎԱ_ԱՆՈՒՆԸ_ՍԵՌ', 'CURRENTMONTHNAMEGEN' ),
	'currentmonthabbrev'    => array( '1', 'ԸՆԹԱՑԻՔ_ԱՄՍՎԱ_ԱՆՎԱՆ_ՀԱՊԱՎՈՒՄԸ', 'CURRENTMONTHABBREV' ),
	'currentday'            => array( '1', 'ԸՆԹԱՑԻՔ_ՕՐԸ', 'CURRENTDAY' ),
	'currentday2'           => array( '1', 'ԸՆԹԱՑԻՔ_ՕՐԸ_2', 'CURRENTDAY2' ),
	'currentdayname'        => array( '1', 'ԸՆԹԱՑԻՔ_ՕՐՎԱ_ԱՆՈՒՆԸ', 'CURRENTDAYNAME' ),
	'currentyear'           => array( '1', 'ԸՆԹԱՑԻՔ_ՏԱՐԻՆ', 'CURRENTYEAR' ),
	'currenttime'           => array( '1', 'ԸՆԹԱՑԻՔ_ԺԱՄԱՆԱԿԸ', 'CURRENTTIME' ),
	'currenthour'           => array( '1', 'ԸՆԹԱՑԻՔ_ԺԱՄԸ', 'CURRENTHOUR' ),
	'localmonth'            => array( '1', 'ՏԵՂԱԿԱՆ_ԱՄԻՍԸ', 'LOCALMONTH', 'LOCALMONTH2' ),
	'localmonthname'        => array( '1', 'ՏԵՂԱԿԱՆ_ԱՄՍՎԱ_ԱՆՈՒՆԸ', 'LOCALMONTHNAME' ),
	'localmonthnamegen'     => array( '1', 'ՏԵՂԱԿԱՆ_ԱՄՍՎԱ_ԱՆՈՒՆԸ_ՍԵՌ', 'LOCALMONTHNAMEGEN' ),
	'localmonthabbrev'      => array( '1', 'ՏԵՂԱԿԱՆ_ԱՄՍՎԱ_ԱՆՎԱՆ_ՀԱՊԱՎՈՒՄԸ', 'LOCALMONTHABBREV' ),
	'localday'              => array( '1', 'ՏԵՂԱԿԱՆ_ՕՐԸ', 'LOCALDAY' ),
	'localday2'             => array( '1', 'ՏԵՂԱԿԱՆ_ՕՐԸ_2', 'LOCALDAY2' ),
	'localdayname'          => array( '1', 'ՏԵՂԱԿԱՆ_ՕՐՎԱ_ԱՆՈՒՆԸ', 'LOCALDAYNAME' ),
	'localyear'             => array( '1', 'ՏԵՂԱԿԱՆ_ՏԱՐԻՆ', 'LOCALYEAR' ),
	'localtime'             => array( '1', 'ՏԵՂԱԿԱՆ_ԺԱՄԱՆԱԿԸ', 'LOCALTIME' ),
	'localhour'             => array( '1', 'ՏԵՂԱԿԱՆ_ԺԱՄԸ', 'LOCALHOUR' ),
	'numberofpages'         => array( '1', 'ԷՋԵՐԻ_ՔԱՆԱԿԸ', 'NUMBEROFPAGES' ),
	'numberofarticles'      => array( '1', 'ՀՈԴՎԱԾՆԵՐԻ_ՔԱՆԱԿԸ', 'NUMBEROFARTICLES' ),
	'numberoffiles'         => array( '1', 'ՖԱՅԼԵՐԻ_ՔԱՆԱԿԸ', 'NUMBEROFFILES' ),
	'numberofusers'         => array( '1', 'ՄԱՍՆԱԿԻՑՆԵՐԻ_ՔԱՆԱԿԸ', 'NUMBEROFUSERS' ),
	'pagename'              => array( '1', 'ԷՋԻ_ԱՆՈՒՆԸ', 'PAGENAME' ),
	'pagenamee'             => array( '1', 'ԷՋԻ_ԱՆՈՒՆԸ_2', 'PAGENAMEE' ),
	'namespace'             => array( '1', 'ԱՆՎԱՆԱՏԱՐԱԾՔ', 'NAMESPACE' ),
	'namespacee'            => array( '1', 'ԱՆՎԱՆԱՏԱՐԱԾՔ_2', 'NAMESPACEE' ),
	'talkspace'             => array( '1', 'ՔՆՆԱՐԿՄԱՆ_ՏԱՐԱԾՔԸ', 'TALKSPACE' ),
	'talkspacee'            => array( '1', 'ՔՆՆԱՐԿՄԱՆ_ՏԱՐԱԾՔԸ_2', 'TALKSPACEE' ),
	'subjectspace'          => array( '1', 'ՀՈԴՎԱԾՆԵՐԻ_ՏԱՐԱԾՔԸ', 'SUBJECTSPACE', 'ARTICLESPACE' ),
	'subjectspacee'         => array( '1', 'ՀՈԴՎԱԾՆԵՐԻ_ՏԱՐԱԾՔԸ_2', 'SUBJECTSPACEE', 'ARTICLESPACEE' ),
	'fullpagename'          => array( '1', 'ARTICLESPACE', 'ԷՋԻ_ԼՐԻՎ_ԱՆՎԱՆՈՒՄԸ', 'FULLPAGENAME' ),
	'fullpagenamee'         => array( '1', 'ԷՋԻ_ԼՐԻՎ_ԱՆՎԱՆՈՒՄԸ_2', 'FULLPAGENAMEE' ),
	'subpagename'           => array( '1', 'ԵՆԹԱԷՋԻ_ԱՆՎԱՆՈՒՄԸ', 'SUBPAGENAME' ),
	'subpagenamee'          => array( '1', 'ԵՆԹԱԷՋԻ_ԱՆՎԱՆՈՒՄԸ_2', 'SUBPAGENAMEE' ),
	'basepagename'          => array( '1', 'ՀԻՄՆԱԿԱՆ_ԷՋԻ_ԱՆՎԱՆՈՒՄԸ', 'BASEPAGENAME' ),
	'basepagenamee'         => array( '1', 'ՀԻՄՆԱԿԱՆ_ԷՋԻ_ԱՆՎԱՆՈՒՄԸ_2', 'BASEPAGENAMEE' ),
	'talkpagename'          => array( '1', 'ՔՆՆԱՐԿՄԱՆ_ԷՋԻ_ԱՆՎԱՆՈՒՄԸ', 'TALKPAGENAME' ),
	'talkpagenamee'         => array( '1', 'ՔՆՆԱՐԿՄԱՆ_ԷՋԻ_ԱՆՎԱՆՈՒՄԸ_2', 'TALKPAGENAMEE' ),
	'subjectpagename'       => array( '1', 'ՀՈԴՎԱԾԻ_ԷՋԻ_ԱՆՎԱՆՈՒՄԸ', 'SUBJECTPAGENAME', 'ARTICLEPAGENAME' ),
	'subjectpagenamee'      => array( '1', 'ՀՈԴՎԱԾԻ_ԷՋԻ_ԱՆՎԱՆՈՒՄԸ_2', 'SUBJECTPAGENAMEE', 'ARTICLEPAGENAMEE' ),
	'msg'                   => array( '0', 'ՀՈՂՈՐԴ՝', 'MSG:' ),
	'msgnw'                 => array( '0', 'ՀՈՂՈՐԴ_ԱՌԱՆՑ_ՎԻՔԻԻ՝', 'MSGNW:' ),
	'img_thumbnail'         => array( '1', 'մինի', 'thumbnail', 'thumb' ),
	'img_manualthumb'       => array( '1', 'մինի=$1', 'thumbnail=$1', 'thumb=$1' ),
	'img_right'             => array( '1', 'աջից', 'right' ),
	'img_left'              => array( '1', 'ձախից', 'left' ),
	'img_none'              => array( '1', 'առանց', 'none' ),
	'img_width'             => array( '1', '$1փքս', '$1px' ),
	'img_center'            => array( '1', 'կենտրոն', 'center', 'centre' ),
	'img_framed'            => array( '1', 'շրջափակել', 'framed', 'enframed', 'frame' ),
	'img_page'              => array( '1', 'էջը=$1', 'էջ $1', 'page=$1', 'page $1' ),
	'int'                   => array( '0', 'ՆԵՐՔ՝', 'INT:' ),
	'sitename'              => array( '1', 'ԿԱՅՔԻ_ԱՆՈՒՆԸ', 'SITENAME' ),
	'ns'                    => array( '0', 'ԱՏ՝', 'NS:' ),
	'localurl'              => array( '0', 'ՏԵՂԱԿԱՆ_ՀԱՍՑԵՆ՝', 'LOCALURL:' ),
	'localurle'             => array( '0', 'ՏԵՂԱԿԱՆ_ՀԱՍՑԵՆ_2՝', 'LOCALURLE:' ),
	'server'                => array( '0', 'ՍԵՐՎԵՐԸ', 'SERVER' ),
	'servername'            => array( '0', 'ՍԵՐՎԵՐԻ_ԱՆՈՒՆԸ', 'SERVERNAME' ),
	'scriptpath'            => array( '0', 'ՍՔՐԻՊՏԻ_ՃԱՆԱՊԱՐՀԸ', 'SCRIPTPATH' ),
	'grammar'               => array( '0', 'ՀՈԼՈՎ՛', 'GRAMMAR:' ),
	'notitleconvert'        => array( '0', '__ԱՌԱՆՑ_ՎԵՐՆԱԳՐԻ_ՓՈՓՈԽՄԱՆ__', '__NOTITLECONVERT__', '__NOTC__' ),
	'nocontentconvert'      => array( '0', '__ԱՌԱՆՑ_ՊԱՐՈՒՆԱԿՈՒԹՅԱՆ_ՓՈՓՈԽՄԱՆ__', '__NOCONTENTCONVERT__', '__NOCC__' ),
	'currentweek'           => array( '1', 'ԸՆԹԱՑԻՔ_ՇԱԲԱԹԸ', 'CURRENTWEEK' ),
	'currentdow'            => array( '1', 'ԸՆԹԱՑԻՔ_ՇԱԲԱԹՎԱ_ՕՐԸ', 'CURRENTDOW' ),
	'localweek'             => array( '1', 'ՏԵՂԱԿԱՆ_ՇԱԲԱԹՎԸ', 'LOCALWEEK' ),
	'localdow'              => array( '1', 'ՏԵՂԱԿԱՆ_ՇԱԲԱԹՎԱ_ՕՐԸ', 'LOCALDOW' ),
	'revisionid'            => array( '1', 'ՏԱՐԲԵՐԱԿԻ_ՀԱՄԱՐԸ', 'REVISIONID' ),
	'revisionday'           => array( '1', 'ՏԱՐԲԵՐԱԿԻ_ՕՐԸ', 'REVISIONDAY' ),
	'revisionday2'          => array( '1', 'ՏԱՐԲԵՐԱԿԻ_ՕՐԸ_2', 'REVISIONDAY2' ),
	'revisionmonth'         => array( '1', 'ՏԱՐԲԵՐԱԿԻ_ԱՄԻՍԸ', 'REVISIONMONTH' ),
	'revisionyear'          => array( '1', 'ՏԱՐԲԵՐԱԿԻ_ՏԱՐԻՆ', 'REVISIONYEAR' ),
	'plural'                => array( '0', 'ՀՈԳՆԱԿԻ՝', 'PLURAL:' ),
	'fullurl'               => array( '0', 'ԼՐԻՎ_ՀԱՍՑԵՆ՝', 'FULLURL:' ),
	'fullurle'              => array( '0', 'ԼՐԻՎ_ՀԱՍՑԵՆ_2՝', 'FULLURLE:' ),
	'lcfirst'               => array( '0', 'ՓՈՔՐԱՏԱՌ_ՍԿԶԲՆԱՏԱՌ՝', 'LCFIRST:' ),
	'ucfirst'               => array( '0', 'ՄԵԾԱՏԱՌ_ՍԿԶԲՆԱՏԱՌ՝', 'UCFIRST:' ),
	'lc'                    => array( '0', 'ՓՈՔՐԱՏԱՌ՝', 'LC:' ),
	'uc'                    => array( '0', 'ՄԵԾԱՏԱՌ՝', 'UC:' ),
	'displaytitle'          => array( '1', 'ՑՈՒՅՑ_ՏԱԼ_ՎԵՐՆԱԳԻՐԸ', 'DISPLAYTITLE' ),
	'rawsuffix'             => array( '1', 'Չ', 'R' ),
	'newsectionlink'        => array( '1', '__ՀՂՈՒՄ_ՆՈՐ_ԲԱԺՆԻ_ՎՐԱ__', '__NEWSECTIONLINK__' ),
	'currentversion'        => array( '1', 'ԸՆԹԱՑԻՔ_ՏԱՐԲԵՐԱԿԸ', 'CURRENTVERSION' ),
	'urlencode'             => array( '0', 'ՄՇԱԿՎԱԾ_ՀԱՍՑԵ՛', 'URLENCODE:' ),
	'currenttimestamp'      => array( '1', 'ԸՆԹԱՑԻՔ_ԺԱՄԱՆԱԿԻ_ԴՐՈՇՄ', 'CURRENTTIMESTAMP' ),
	'localtimestamp'        => array( '1', 'ՏԵՂԱԿԱՆ_ԺԱՄԱՆԱԿԻ_ԴՐՈՇՄ', 'LOCALTIMESTAMP' ),
	'directionmark'         => array( '1', 'ՆԱՄԱԿԻ_ՈՒՂՂՈՒԹՅՈՒՆԸ', 'DIRECTIONMARK', 'DIRMARK' ),
	'language'              => array( '0', '#ԼԵԶՈՒ՝', '#LANGUAGE:' ),
	'contentlanguage'       => array( '1', 'ՊԱՐՈՒՆԱԿՈՒԹՅԱՆ_ԼԵԶՈՒՆ', 'CONTENTLANGUAGE', 'CONTENTLANG' ),
	'pagesinnamespace'      => array( '1', 'ԷՋԵՐ_ԱՆՎԱՆԱՏԱՐԱԾՔՈՒՄ՝', 'PAGESINNAMESPACE:', 'PAGESINNS:' ),
	'numberofadmins'        => array( '1', 'ԱԴՄԻՆՆԵՐԻ_ՔԱՆԱԿԸ', 'NUMBEROFADMINS' ),
	'formatnum'             => array( '0', 'ՁԵՎԵԼ_ԹԻՎԸ', 'FORMATNUM' ),
	'padleft'               => array( '0', 'ԼՐԱՑՆԵԼ_ՁԱԽԻՑ', 'PADLEFT' ),
	'padright'              => array( '0', 'ԼՐԱՑՆԵԼ_ԱՋԻՑ', 'PADRIGHT' ),
	'special'               => array( '0', 'սպասարկող', 'special' ),
	'defaultsort'           => array( '1', 'ԼՌՈՒԹՅԱՄԲ_ԴԱՍԱՎՈՐՈՒՄ՝', 'DEFAULTSORT:', 'DEFAULTSORTKEY:', 'DEFAULTCATEGORYSORT:' ),
);

$specialPageAliases = array(
	'Allmessages'               => array( 'Բոլորուղերձները' ),
	'Allpages'                  => array( 'Բոլորէջերը' ),
	'Ancientpages'              => array( 'Ամենահինէջերը' ),
	'Block'                     => array( 'Արգելափակելip' ),
	'Blockme'                   => array( 'Արգելափակել' ),
	'Booksources'               => array( 'Գրքայինաղբյուրները' ),
	'BrokenRedirects'           => array( 'Կոտրվածվերահղումները' ),
	'Categories'                => array( 'Կատեգորիաները' ),
	'ChangePassword'            => array( 'Նորգաղտնաբառ' ),
	'Contributions'             => array( 'Ներդրումները' ),
	'Deadendpages'              => array( 'Հղումչպարունակողէջերը' ),
	'Disambiguations'           => array( 'Երկիմաստէջերը' ),
	'DoubleRedirects'           => array( 'Կրկնակիվերահղումները' ),
	'Emailuser'                 => array( 'Գրելնամակ' ),
	'Export'                    => array( 'Արտահանելէջերը' ),
	'FileDuplicateSearch'       => array( 'Կրկնօրինակֆայլերիորոնում' ),
	'Import'                    => array( 'Ներմուծել' ),
	'BlockList'                 => array( 'ԱրգելափակվածIPները' ),
	'Listadmins'                => array( 'Ադմիններիցանկը' ),
	'Listfiles'                 => array( 'Պատկերներիցանկը' ),
	'Listredirects'             => array( 'Ցույցտալվերահղումները' ),
	'Listusers'                 => array( 'Մասնակիցներիցանկը' ),
	'Lockdb'                    => array( 'Կողպելտհ' ),
	'Log'                       => array( 'Տեղեկամատյան' ),
	'Lonelypages'               => array( 'Միայնակէջերը' ),
	'Longpages'                 => array( 'Երկարէջերը' ),
	'MIMEsearch'                => array( 'MIMEՈրոնում' ),
	'Mostcategories'            => array( 'Ամենաշատկատեգորիաներով' ),
	'Mostimages'                => array( 'Ամենաշատօգտագործվողնկարները' ),
	'Mostlinked'                => array( 'Ամենաշատհղումներով' ),
	'Mostlinkedcategories'      => array( 'Շատհղվողկատեգորիաները' ),
	'Mostrevisions'             => array( 'Ամենաշատփոփոխվող' ),
	'Movepage'                  => array( 'Տեղափոխելէջը' ),
	'Mycontributions'           => array( 'Իմներդրումները' ),
	'Mypage'                    => array( 'Իմէջը' ),
	'Mytalk'                    => array( 'Իմքննարկումները' ),
	'Newimages'                 => array( 'Նորպատկերներ' ),
	'Newpages'                  => array( 'Նորէջերը' ),
	'Popularpages'              => array( 'Հանրաճանաչէջերը' ),
	'Preferences'               => array( 'Նախընտրությունները' ),
	'Prefixindex'               => array( 'Որոնումնախածանցով' ),
	'Randompage'                => array( 'Պատահականէջ' ),
	'Randomredirect'            => array( 'Պատահականվերահղում' ),
	'Recentchanges'             => array( 'Վերջինփոփոխությունները' ),
	'Recentchangeslinked'       => array( 'Կապվածէջերիփոփոխությունները' ),
	'Revisiondelete'            => array( 'Տարբերակիհեռացում' ),
	'Search'                    => array( 'Որոնել' ),
	'Shortpages'                => array( 'Կարճէջերը' ),
	'Specialpages'              => array( 'Սպասարկողէջերը' ),
	'Statistics'                => array( 'Վիճակագրություն' ),
	'Uncategorizedcategories'   => array( 'Չդասակարգվածկատեգորիաները' ),
	'Uncategorizedimages'       => array( 'Չդասակարգվածպատկերները' ),
	'Uncategorizedpages'        => array( 'Չդասակարգվածէջերը' ),
	'Uncategorizedtemplates'    => array( 'Չդասակարգվածկաղապարները' ),
	'Undelete'                  => array( 'Վերականգնել' ),
	'Unlockdb'                  => array( 'Բացանելտհ' ),
	'Unusedcategories'          => array( 'Չօգտագործվածկատեգորիաները' ),
	'Unusedimages'              => array( 'Չօգտագործվածպատկերները' ),
	'Unusedtemplates'           => array( 'Չօգտագործվողկաղապարները' ),
	'Unwatchedpages'            => array( 'Չհսկվողէջերը' ),
	'Upload'                    => array( 'Բեռնել' ),
	'Userlogin'                 => array( 'Մասնակցիմուտք' ),
	'Userlogout'                => array( 'Մասնակցիելք' ),
	'Userrights'                => array( 'Մասնակցիիրավունքները' ),
	'Version'                   => array( 'Տարբերակ' ),
	'Wantedcategories'          => array( 'Անհրաժեշտկատեգորիաները' ),
	'Wantedpages'               => array( 'Անհրաժեշտէջերը' ),
	'Watchlist'                 => array( 'Հսկողությանցանկը' ),
	'Whatlinkshere'             => array( 'Այստեղհղվողէջերը' ),
);

$linkTrail = '/^([a-zաբգդեզէըթժիլխծկհձղճմյնշոչպջռսվտրցւփքօֆև«»]+)(.*)$/sDu';

$messages = array(
# User preference toggles
'tog-underline'               => 'ընդգծել հղումները՝',
'tog-highlightbroken'         => 'Ցույց տալ չգործող հղումները <a href="" class="new">այսպես</a> (այլապես՝ այսպես<a href="" class="internal">(՞)</a>)։',
'tog-justify'                 => 'Հավասարացնել տեքստը էջի լայնությամբ',
'tog-hideminor'               => 'Թաքցնել չնչին խմբագրումները վերջին փոփոխությունների ցանկից',
'tog-hidepatrolled'           => 'Թաքցնել պարեկված խմբագրումները վերջին փոփոխությունների ցանկից',
'tog-newpageshidepatrolled'   => 'Թաքցնել պարեկված էջերը նոր էջերի ցանկից',
'tog-extendwatchlist'         => 'Ընդարձակել հսկացանկը՝ ցույց տալով բոլոր փոփոխությունները, այլ ոչ միայն վերջինները',
'tog-usenewrc'                => 'Օգտագործել վերջին փոփոխությունների լավացված ցանկ (պահանջում է JavaScript)',
'tog-numberheadings'          => 'Ինքնաթվագրել վերնագրերը',
'tog-showtoolbar'             => 'Ցույց տալ խմբագրումների գործիքների վահանակը (JavaScript)',
'tog-editondblclick'          => 'Խմբագրել էջերը կրկնակի մատնահարմամբ (JavaScript)',
'tog-editsection'             => 'Ցույց տալ [խմբագրել] հղումը ամեն բաժնի համար',
'tog-editsectiononrightclick' => 'Խմբագրել բաժինները վերնագրի աջ մատնահարմամբ (JavaScript)',
'tog-showtoc'                 => 'Ցույց տալ բովանդակությունը (3  կամ ավել վերնագրեր ունեցող էջերի համար)',
'tog-rememberpassword'        => 'Հիշել իմ մասնակցի հաշիվը այս համակարգչում (for a maximum of $1 {{PLURAL:$1|day|days}})',
'tog-watchcreations'          => 'Ավելացնել իմ ստեղծած էջերը հսկացանկին',
'tog-watchdefault'            => 'Ավելացնել իմ խմբագրած էջերը հսկացանկին',
'tog-watchmoves'              => 'Ավելացնել իմ տեղափոխած էջերը հսկացանկին',
'tog-watchdeletion'           => 'Ավելացնել իմ ջնջած էջերը հսկացանկին',
'tog-minordefault'            => 'Նշել խմբագրումները որպես չնչին ըստ լռության',
'tog-previewontop'            => 'Ցույց տալ նախադիտումը խմբագրման դաշտից առաջ',
'tog-previewonfirst'          => 'Նախադիտել մինչև առաջին խմբագրությունը',
'tog-nocache'                 => 'Արգելել էջերի գրանցումը հիշողության մեջ',
'tog-enotifwatchlistpages'    => 'էլ-փոստով տեղեկացնել հսկվող էջերում փոփոխությունների մասին',
'tog-enotifusertalkpages'     => 'էլ-փոստով տեղեկացնել իմ քննարկման էջի փոփոխության մասին',
'tog-enotifminoredits'        => 'էլ-փոստով տեղեկացնել էջերի նաև չնչին խմբագրումների մասին',
'tog-enotifrevealaddr'        => 'Ցույց տալ իմ էլ-փոստի հասցեն ծանուցման նամակներում',
'tog-shownumberswatching'     => 'Ցույց տալ էջ հսկող մասնակիցների թիվը',
'tog-oldsig'                  => 'Առկա ստորագրության նախադիտում.',
'tog-fancysig'                => 'Ստորագրությունը վիքիտեքստի տեսքով (առանց ավտոմատ հղման)',
'tog-externaleditor'          => 'Օգտագործել արտաքին խմբագրիչ ըստ լռության (պահանջում է հատուկ նախընտրություններ ձեր համակարգչում)',
'tog-externaldiff'            => 'Օգտագործել տարբերակների համեմատման արտաքին ծրագիր ըստ լռության (պահանջում է հատուկ նախընտրություններ ձեր համակարգչում)',
'tog-showjumplinks'           => 'Միացնել «անցնել դեպի» օգնական հղումները',
'tog-uselivepreview'          => 'Օգտագործել ուղիղ նախադիտում (JavaScript) (Փորձնական)',
'tog-forceeditsummary'        => 'Նախազգուշացնել փոփոխությունների ամփոփումը դատարկ թողնելու մասին',
'tog-watchlisthideown'        => 'Թաքցնել իմ խմբագրումները հսկացանկից',
'tog-watchlisthidebots'       => 'Թաքցնել բոտերի խմբագրումները հսկացանկից',
'tog-watchlisthideminor'      => 'Թաքցնել չնչին խմբագրումները հսկացանկից',
'tog-watchlisthideliu'        => 'Թաքցնել մուտք գործած մասնակիցների խմբագրումները հսկացանկից',
'tog-watchlisthideanons'      => 'Թաքցնել անանուն մասնակիցների խմբագրումները հսկացանկից',
'tog-watchlisthidepatrolled'  => 'Թաքցնել պարեկված խմբագրումները հսկացանկից',
'tog-nolangconversion'        => 'Անջատել գրի համակարգի փոփոխումը',
'tog-ccmeonemails'            => 'Ուղարկել ինձ իմ կողմից մյուս մասնակիցներին ուղարկված նամակների պատճեններ',
'tog-diffonly'                => 'Չցուցադրել էջի պարունակությունը տարբերությունների ներքևից',
'tog-showhiddencats'          => 'Ցուցադրել թաքնված կատեգորիաները',
'tog-norollbackdiff'          => 'Չցուցադրել տարբերությունները հետ գլորելուց հետո',

'underline-always'  => 'Միշտ',
'underline-never'   => 'Երբեք',
'underline-default' => 'Օգտագործել զննարկիչի նախընտրությունները',

# Font style option in Special:Preferences
'editfont-style'     => 'Խմբագրման շրջանի տառատեսակի ձևը.',
'editfont-default'   => 'Զննարկիչի լռելյայն տառատեսակը',
'editfont-monospace' => 'Միալայն տառատեսակ',
'editfont-sansserif' => 'Սանս-սերիֆ տառատեսակ',
'editfont-serif'     => 'Սերիֆ տառատեսակ',

# Dates
'sunday'        => 'Կիրակի',
'monday'        => 'Երկուշաբթի',
'tuesday'       => 'Երեքշաբթի',
'wednesday'     => 'Չորեքշաբթի',
'thursday'      => 'Հինգշաբթի',
'friday'        => 'Ուրբաթ',
'saturday'      => 'Շաբաթ',
'sun'           => 'Կիր',
'mon'           => 'Երկ',
'tue'           => 'Երե',
'wed'           => 'Չոր',
'thu'           => 'Հին',
'fri'           => 'Ուրբ',
'sat'           => 'Շաբ',
'january'       => 'Հունվար',
'february'      => 'Փետրվար',
'march'         => 'Մարտ',
'april'         => 'Ապրիլ',
'may_long'      => 'Մայիս',
'june'          => 'Հունիս',
'july'          => 'Հուլիս',
'august'        => 'Օգոստոս',
'september'     => 'Սեպտեմբեր',
'october'       => 'Հոկտեմբեր',
'november'      => 'Նոյեմբեր',
'december'      => 'Դեկտեմբեր',
'january-gen'   => 'Հունվարի',
'february-gen'  => 'Փետրվարի',
'march-gen'     => 'Մարտի',
'april-gen'     => 'Ապրիլի',
'may-gen'       => 'Մայիսի',
'june-gen'      => 'Հունիսի',
'july-gen'      => 'Հուլիսի',
'august-gen'    => 'Օգոստոսի',
'september-gen' => 'Սեպտեմբերի',
'october-gen'   => 'Հոկտեմբերի',
'november-gen'  => 'Նոյեմբերի',
'december-gen'  => 'Դեկտեմբերի',
'jan'           => 'հունվ',
'feb'           => 'փետ',
'mar'           => 'մար',
'apr'           => 'ապր',
'may'           => 'մայ',
'jun'           => 'հուն',
'jul'           => 'հուլ',
'aug'           => 'օգո',
'sep'           => 'սեպ',
'oct'           => 'հոկ',
'nov'           => 'նոյ',
'dec'           => 'դեկ',

# Categories related messages
'pagecategories'                 => '{{PLURAL:$1|Կատեգորիա|Կատեգորիաներ}}',
'category_header'                => '«$1» կատեգորիայի հոդվածները',
'subcategories'                  => 'Ենթակատեգորիաներ',
'category-media-header'          => '«$1» կատեգորիայի մեդիան',
'category-empty'                 => "''Այս կատեգորիան ներկայումս դատարկ է։''",
'hidden-categories'              => '{{PLURAL:$1|Թաքցված կատեգորիա|Թաքցված կատեգորիաներ}}',
'hidden-category-category'       => 'Թաքցված կատեգորիաներ',
'category-subcat-count'          => '{{PLURAL:$2|Այս կատեգորիան ունի միայն հետևյալ ենթակատեգորիան։|Ստորև  {{PLURAL:$1|բերված է այս կատեգորիայի $1 ենթակատեգորիան|բերված է այս կատեգորիայի $1 ենթակատեգորիա}}՝ $2-ից։}}',
'category-subcat-count-limited'  => 'Այս կատեգորիան ունի հետևյալ {{PLURAL:$1|ենթակատեգորիան|$1 ենթակատեգորիաները}}։',
'category-article-count'         => '{{PLURAL:$2|Այս կատեգորիան ունի միայն հետևյալ էջը։|Ստորև բերված է այս կատեգորիայի {{PLURAL:$1|էջ|$1 էջ}}՝ $2-ից։}}',
'category-article-count-limited' => 'Այս կատեգորիան պարունակում է հետևյալ {{PLURAL:$1|էջը|$1 էջերը}}։',
'category-file-count'            => '{{PLURAL:$2|Այս կատեգորիան պարունակում է միայն հետևյալ նիշքը։|Ստորև {{PLURAL:$1|բերված է այս կատեգորիայի $1 նիշքը|բերված է այս կատեգորիայի $1 նիշք}}՝ $2-ից։}}',
'category-file-count-limited'    => 'Այս կատեգորիան պարունակում է հետևյալ {{PLURAL:$1|նիշքը|$1 նիշքերը}}։',
'listingcontinuesabbrev'         => 'շարունակ.',
'index-category'                 => 'Ինդեքսավորված էջեր',
'noindex-category'               => 'Ինդեքսավորված էջեր չկան',

'mainpagetext'      => "'''«MediaWiki» ծրագիրը հաջողությամբ տեղադրվեց։'''",
'mainpagedocfooter' => "Այցելեք [http://meta.wikimedia.org/wiki/Help:Contents User's Guide]՝ վիքի ծրագրային ապահովման օգտագործման մասին տեղեկությունների համար։

== Որոշ օգտակար ռեսուրսներ ==

* [http://www.mediawiki.org/wiki/Manual:Configuration_settings Configuration settings list]
* [http://www.mediawiki.org/wiki/Manual:FAQ MediaWiki FAQ]
* [https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce MediaWiki release mailing list]",

'about'         => 'Էությունը',
'article'       => 'Հոդված',
'newwindow'     => '(բացվելու է նոր պատուհանի մեջ)',
'cancel'        => 'Բեկանել',
'moredotdotdot' => 'Ավելին...',
'mypage'        => 'Իմ էջը',
'mytalk'        => 'Իմ քննարկումները',
'anontalk'      => 'Քննարկում այս IP-հասցեի համար',
'navigation'    => 'Շրջել կայքում',
'and'           => '&#32;և',

# Cologne Blue skin
'qbfind'         => 'Գտնել',
'qbbrowse'       => 'Թերթել',
'qbedit'         => 'Խմբագրել',
'qbpageoptions'  => 'Այս էջը',
'qbpageinfo'     => 'Հոդվածի մասին',
'qbmyoptions'    => 'Իմ էջերը',
'qbspecialpages' => 'Սպասարկող էջեր',
'faq'            => 'ՀՏՀ',
'faqpage'        => 'Project:ՀՏՀ',

# Vector skin
'vector-action-addsection' => 'Ավելացնել քննարկում',
'vector-action-delete'     => 'Ջնջել',
'vector-action-move'       => 'Տեղափոխել',
'vector-action-protect'    => 'Պաշտպանել',
'vector-action-undelete'   => 'Վերականգնել',
'vector-action-unprotect'  => 'Հանել պաշտպանումից',
'vector-view-create'       => 'Ստեղծել',
'vector-view-edit'         => 'Խմբագրել',
'vector-view-history'      => 'Դիտել պատմությունը',
'vector-view-view'         => 'Կարդալ',
'vector-view-viewsource'   => 'Դիտել ելատեքստը',
'actions'                  => 'Գործողություններ',
'namespaces'               => 'Անվանատարածքներ',
'variants'                 => 'Տարբերակներ',

'errorpagetitle'    => 'Սխալ',
'returnto'          => 'Վերադառնալ $1։',
'tagline'           => '{{SITENAME}}յից՝ ազատ հանրագիտարանից',
'help'              => 'Օգնություն',
'search'            => 'Որոնում',
'searchbutton'      => 'Որոնել',
'go'                => 'Անցնել',
'searcharticle'     => 'Անցնել',
'history'           => 'Էջի պատմություն',
'history_short'     => 'Պատմություն',
'updatedmarker'     => 'թարմացվել է իմ վերջին այցից հետո',
'info_short'        => 'Տեղեկություն',
'printableversion'  => 'Տպելու տարբերակ',
'permalink'         => 'Մշտական հղում',
'print'             => 'Տպել',
'edit'              => 'Խմբագրել',
'create'            => 'Ստեղծել',
'editthispage'      => 'Խմբագրել այս էջը',
'create-this-page'  => 'Ստեղծել այս էջը',
'delete'            => 'Ջնջել',
'deletethispage'    => 'Ջնջել այս էջը',
'undelete_short'    => 'Վերականգնել {{PLURAL:$1|մեկ խմբագրում|$1 խմբագրում}}',
'protect'           => 'Պաշտպանել',
'protect_change'    => 'փոխել',
'protectthispage'   => 'Պաշտպանել այս էջը',
'unprotect'         => 'Հանել պաշտպանումից',
'unprotectthispage' => 'Հանել այս էջը պաշտպանումից',
'newpage'           => 'Նոր էջ',
'talkpage'          => 'Քննարկել այս էջը',
'talkpagelinktext'  => 'Քննարկում',
'specialpage'       => 'Սպասարկող էջ',
'personaltools'     => 'Անձնական գործիքներ',
'postcomment'       => 'Նոր բաժին',
'articlepage'       => 'Դիտել հոդվածը',
'talk'              => 'Քննարկում',
'views'             => 'Դիտումները',
'toolbox'           => 'Գործիքներ',
'userpage'          => 'Դիտել մասնակցի էջը',
'projectpage'       => 'Դիտել նախագծի էջը',
'imagepage'         => 'Դիտել նիշքի էջը',
'mediawikipage'     => 'Դիտել ուղերձի էջը',
'templatepage'      => 'Դիտել կաղապարի էջը',
'viewhelppage'      => 'Դիտել օգնության էջը',
'categorypage'      => 'Դիտել կատեգորիայի էջը',
'viewtalkpage'      => 'Դիտել քննարկումը',
'otherlanguages'    => 'Այլ լեզուներով',
'redirectedfrom'    => '(Վերահղված է $1-ից)',
'redirectpagesub'   => 'Վերահղման էջ',
'lastmodifiedat'    => 'Այս էջը վերջին անգամ փոփոխվել է $2, $1։',
'viewcount'         => 'Այս էջին դիմել են {{PLURAL:$1|մեկ անգամ|$1 անգամ}}։',
'protectedpage'     => 'Պաշտպանված էջ',
'jumpto'            => 'Անցնել՝',
'jumptonavigation'  => 'նավարկություն',
'jumptosearch'      => 'որոնում',
'view-pool-error'   => 'Ներեցեք՝ սերվերները գերբեռնված են այս պահին։
Չափից շատ օգտվողներ փորձում են դիտել այս էջը։
Խնդրում ենք սպասել որոշ ժամանակ էջը դիտելու կրկին հայցում անելուց առաջ։

$1',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => '{{grammar:genitive|{{SITENAME}}}} մասին',
'aboutpage'            => 'Project:Էությունը',
'copyright'            => 'Կայքի բովանդակությունը գտնվում է «$1» արտոնագրի տակ։',
'copyrightpage'        => '{{ns:project}}:Հեղինակային իրավունքներ',
'currentevents'        => 'Ընթացիկ իրադարձություններ',
'currentevents-url'    => 'Project:Ընթացիկ իրադարձություններ',
'disclaimers'          => 'Ազատում պատասխանատվությունից',
'disclaimerpage'       => 'Project:Ազատում պատասխանատվությունից',
'edithelp'             => 'Խմբագրման ուղեցույց',
'edithelppage'         => 'Help:Խմբագրում',
'helppage'             => 'Help:Գլխացանկ',
'mainpage'             => 'Գլխավոր էջ',
'mainpage-description' => 'Գլխավոր էջ',
'policy-url'           => 'Project:Կանոնակարգ',
'portal'               => 'Խորհրդարան',
'portal-url'           => 'Project:Խորհրդարան',
'privacy'              => 'Գաղտնիության քաղաքականություն',
'privacypage'          => 'Project:Գաղտնիության քաղաքականություն',

'badaccess'        => 'Թույլատրման սխալ',
'badaccess-group0' => 'Ձեզ չի թույլատրվում կատարել տվյալ գործողությունը։',
'badaccess-groups' => 'Տվյալ գործողությունը կարող են կատարել միայն $1 {{PLURAL:$2|խմբի|խմբերի}} մասնակիցները։',

'versionrequired'     => 'Պահանջվում է MediaWiki ծրագրի $1 տարբերակը',
'versionrequiredtext' => 'Այս էջի օգտագործման համար պահանջվում է MediaWiki ծրագրի $1 տարբերակը։ Տես [[Special:Version|տարբերակի էջը]]։',

'ok'                      => 'OK',
'pagetitle'               => '$1 — {{SITENAME}}',
'retrievedfrom'           => 'Ստացված է «$1» էջից',
'youhavenewmessages'      => 'Դուք ունեք $1 ($2)։',
'newmessageslink'         => 'նոր ուղերձներ',
'newmessagesdifflink'     => 'վերջին փոփոխությունը',
'youhavenewmessagesmulti' => 'Դուք նոր ուղերձներ եք ստացել $1 վրա',
'editsection'             => 'խմբագրել',
'editold'                 => 'խմբագրել',
'viewsourceold'           => 'դիտել ելատեքստը',
'editlink'                => 'խմբագրել',
'viewsourcelink'          => 'դիտել ելատեքստը',
'editsectionhint'         => 'Խմբագրել բաժինը. $1',
'toc'                     => 'Բովանդակություն',
'showtoc'                 => 'ցույց տալ',
'hidetoc'                 => 'թաքցնել',
'thisisdeleted'           => 'Դիտե՞լ կամ վերականգնե՞լ $1։',
'viewdeleted'             => 'Դիտե՞լ $1։',
'restorelink'             => '{{PLURAL:$1|մեկ ջնջված խմբագրում|$1 ջնջված խմբագրում}}',
'feedlinks'               => 'Սնուցման տեսակ.',
'feed-invalid'            => 'Սխալ բաժանորդագրման սնուցման տեսակ։',
'feed-unavailable'        => 'Սինդիկացման սնուցումներն անջատված են',
'site-rss-feed'           => '$1 RSS Սնուցում',
'site-atom-feed'          => '$1 Atom Սնուցում',
'page-rss-feed'           => '«$1» RSS Սնուցում',
'page-atom-feed'          => '«$1» Atom Սնուցում',
'red-link-title'          => '$1 (էջը գոյություն չունի)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Էջ',
'nstab-user'      => 'Մասնակցի էջ',
'nstab-media'     => 'Մեդիա էջ',
'nstab-special'   => 'Սպասարկող էջ',
'nstab-project'   => 'Նախագծի էջ',
'nstab-image'     => 'Նիշք',
'nstab-mediawiki' => 'Ուղերձ',
'nstab-template'  => 'Կաղապար',
'nstab-help'      => 'Օգնության էջ',
'nstab-category'  => 'Կատեգորիա',

# Main script and global functions
'nosuchaction'      => 'Նման գործողություն չկա',
'nosuchactiontext'  => 'URL-ում նշված գործողությունը սխալ է։
Հնարավոր է դուք վրիպակ եք թույլ տվել URL-ի մուտքագրման ժամանակ կամ հետևել եք սխալ հղմամբ։
Սա կարող է նաև լինել {{SITENAME}} նախագծում օգտագործվող ծրագրի սխալ։',
'nosuchspecialpage' => 'Նման սպասարկող էջ չկա',
'nospecialpagetext' => '<strong>Ձեր հայցված սպասարկող էջը գոյություն չունի։</strong>

Տեսեք [[Special:SpecialPages|սպասարկող էջերի ցանկը]]։',

# General errors
'error'                => 'Սխալ',
'databaseerror'        => 'Տվյալների բազայի սխալ',
'dberrortext'          => 'Հայտնաբերվել է տվյալների բազային հայցի շարահյուսության սխալ։
Սա կարող է լինել ծրագրային ապահովման սխալից։
Տվյալների բազային վերջին հայցն էր․
<blockquote><tt>$1</tt></blockquote>
հետևյալ ֆունկցիայի մարմնից <tt>«$2»</tt>։
Տվյլաների բազայի վերադարձրած սխալն է․ <tt>«$3: $4»</tt>։',
'dberrortextcl'        => 'Հայտնաբերվել է տվյալների բազային հայցի շարահյուսության սխալ։
Տվյալների բազային վերջին հայցն էր.
«$1»
հետևյալ ֆունկցիայի մարմնից <tt>«$2»</tt>։
Տվյալների բազայի վերադարձրած սխալն է. <tt>«$3: $4»</tt>։',
'laggedslavemode'      => 'Զգուշացում. էջը կարող է չպարունակել վերջին փոփոխությունները։',
'readonly'             => 'Տվյալների բազան կողպված է',
'enterlockreason'      => 'Նշեք կողպման պատճառը և մոտավոր ժամկետը',
'readonlytext'         => 'Տվյալների բազան ներկայումս կողպված է նոր էջերի և փոփոխությունների համար՝ հավանաբար նախատեսված սպասարկման կապակցությամբ, որից հետո այն կվերադարձվի սովորական վիճակի։

Ադմինիստրատորը, որը կողպել է այն, թողել է հետևյալ բացատրությունը.
$1',
'missing-article'      => 'Տվյալների բազայում չգտնվեց որոնվող էջի տեքստը, որը պետք է գտնվեր «$1» անվանմամբ $2։

Սա սովորաբար պատահում է հեռացված էջի պատմության կամ փոփոխությունների հնացած հղմամբ հետևելու արդյունքում։

Եթե պատճառը դա չէ, ապա դուք հավանաբար սխալ եք գտել ծրագրային ապահովման մեջ։
Խնդրում ենք սրա մասին հայտնել որևէ [[Special:ListUsers/sysop|ադմինիստրատորի]]՝ ընդգրկելով URL-ը։',
'missingarticle-rev'   => '(տարբերակ № $1)',
'missingarticle-diff'  => '(Տարբ. $1, $2)',
'readonly_lag'         => 'Տվյալների բազան ավտոմատիկ կողպվել է ժամանակավորապես՝ մինչև ՏԲ-ի երկրորդական սերվերը չհամաժամանակեցվի առաջնայինի հետ։',
'internalerror'        => 'Ներքին սխալ',
'internalerror_info'   => 'Ներքին սխալ. $1',
'fileappenderror'      => 'Չհաջողվեց ավելացնել «$1» «$2»-ին։',
'filecopyerror'        => 'Չհաջողվեց պատճենել «$1» նիշքը «$2» նիշքի մեջ։',
'filerenameerror'      => 'Չհաջողվեց «$1» նիշքը վերանվանել «$2»։',
'filedeleteerror'      => 'Չհաջողվեց ջնջել «$1» ֆայլը։',
'directorycreateerror' => 'Չհաջողվեց ստեղծել «$1» պանակը։',
'filenotfound'         => 'Չհաջողվեց գտնել «$1» ֆայլը։',
'fileexistserror'      => 'Չհաջողվեց գրել «$1» նիշքին. նիշքը գոյություն ունի։',
'unexpected'           => 'Անսպասելի արժեք. «$1»=«$2»։',
'formerror'            => 'Սխալ. չհաջողվեց փոխանցել տվյալները',
'badarticleerror'      => 'Տվյալ գործողությունը չի կարող կատարվել այս էջում։',
'cannotdelete'         => 'Չհաջողվեց ջնջել «$1» էջը կամ ֆայլը։
Հավանաբար այն արդեն ջնջվել է մեկ այլ մասնակցի կողմից։',
'badtitle'             => 'Անընդունելի անվանում',
'badtitletext'         => 'Հարցված էջի անվանումը անընդունելի է, դատարկ է կամ սխալ միջ-լեզվական կամ ինտերվիքի անվանում է։ Հնարավոր է, որ այն պարունակում է անթույլատրելի սիմվոլներ։',
'perfcached'           => 'Հետևյալ տվյալները վերցված են քեշից և հնարավոր է չարտացոլեն վերջին փոփոխությունները։',
'perfcachedts'         => 'Հետևյալ տվյալները վերցված են քեշից և վերջին անգամ թարմացվել են $1։',
'querypage-no-updates' => 'Այս էջի փոփոխությունները ներկայումս արգելված են։ Այստեղի տվյալները այժմ չեն թարմացվի։',
'wrong_wfQuery_params' => 'Անթույլատրելի պարամետրեր wfQuery() ֆունկցիայի համար<br />
Ֆունկցիա. $1<br />
Հայցում. $2',
'viewsource'           => 'Դիտել ելատեքստը',
'viewsourcefor'        => '«$1» էջի',
'actionthrottled'      => 'Գործողությունը արգելափակվեց',
'actionthrottledtext'  => 'Որպես հակա-սպամային միջոց, այս գործողության չափից շատ կատարումը կարճ ժամանակահատվածի ընթացքում սահմանափակված է։ Խնդրում ենք փորձել կրկին մի քանի րոպե անց։',
'protectedpagetext'    => 'Այս էջը կողպված խմբագրման համար։',
'viewsourcetext'       => 'Դուք կարող եք դիտել և պատճենել այս էջի ելատեքստը.',
'protectedinterface'   => 'Այս էջը պարունակում է ծրագրային ապահովման ինտերֆեյսի ուզերձ և կողպված է չարաշահումների կանխարգելման նպատակով։.',
'editinginterface'     => "'''Զգուշացում.''' Դուք խմբագրում եք ծրագրային ապահովման ինտերֆեյսի տեքստ պարունակող էջ։ Այս էջի փոփոխությունը կանդրադառնա այլ մասնակիցներին տեսանելի ինտերֆեյսի տեսքի վրա։
Թարգմանությունների համար նախընտրելի է օգտագործել [http://translatewiki.net/wiki/Main_Page?setlang=hy translatewiki.net]՝ MediaWiki ծրագրի տեղայնացման նախագիծը։",
'sqlhidden'            => '(SQL հայցումը թաքցված է)',
'cascadeprotected'     => 'Այս էջը պաշտպանված է խմբագրումից, քանի որ ընդգրկված է հետևյալ {{PLURAL:$1|էջի|էջերի}} տեքստում, {{PLURAL:$1|որը|որոնք}} պաշտպանվել {{PLURAL:$1|է|են}} կասկադային հնարավորությամբ.
$2',
'namespaceprotected'   => 'Դուք չունեք «$1» անվանատարածքի էջերի խմբագրման իրավունք։',
'ns-specialprotected'  => '«{{ns:special}}» անվանատարածքի էջերը չեն կարող խմբագրվել։',
'titleprotected'       => "Այս անվանմամբ էջի ստեղծումը արգելվել է [[User:$1|$1]] մասնակցի կողմից։
Տրված պատճառն է՝ ''$2''։",

# Virus scanner
'virus-badscanner'     => "Սխալ կարգավորւմ։ Անծանոթ վիրուսների զննիչ. ''$1''",
'virus-scanfailed'     => 'զննման սխալ (կոդ $1)',
'virus-unknownscanner' => 'անծանոթ հակավիրուս.',

# Login and logout pages
'logouttext'                 => "'''Դուք դուրս եկաք համակարգից։'''

Դուք կարող եք շարունակել օգտագործել {{SITENAME}} կայքը անանուն, կամ [[Special:UserLogin|կրկին մուտք գործել համակարգ]] նույն կամ մեկ այլ մասնակցի անվամբ։ Ի նկատի ունեցեք, որ որոշ էջեր կարող են ցուցադրվել այնպես՝ ինչպես եթե դեռ համակարգում լինեիք մինչև որ չջնջեք ձեր զննարկիչի հիշապահեստը։",
'welcomecreation'            => '== Բարի՛ գալուստ, $1 ==
Ձեր հաշիվը ստեղծված է։
Չմոռանաք անձնավորել ձեր [[Special:Preferences|նախընտրությունները]]։',
'yourname'                   => 'Մասնակցի անուն.',
'yourpassword'               => 'Գաղտնաբառ.',
'yourpasswordagain'          => 'Կրկնեք գաղտնաբառը.',
'remembermypassword'         => 'Հիշել իմ մուտքագրված տվյալները այս համակարգչում ($1 {{PLURAL:$1|օրից|օրից}} ոչ ավել ժամկետով)',
'yourdomainname'             => 'Ձեր դոմենը.',
'externaldberror'            => 'Տեղի է ունեցել վավերացման արտաքին տվյալների բազայի սխալ, կամ դուք չունեք բավարար իրավունքներ ձեր արտաքին հաշվի փոփոխման համար։',
'login'                      => 'Մտնել',
'nav-login-createaccount'    => 'Մտնել / Գրանցվել',
'loginprompt'                => '{{SITENAME}} մուտք գործելու համար հարկավոր է քուքիները թույլատրել։',
'userlogin'                  => 'Մտնել / Գրանցվել',
'userloginnocreate'          => 'Մտնել',
'logout'                     => 'Ելնել',
'userlogout'                 => 'Ելնել',
'notloggedin'                => 'Դուք չեք մտել համակարգ',
'nologin'                    => "Դեռևս չե՞ք գրանցվել։ '''$1'''։",
'nologinlink'                => 'Ստեղծեք մասնակցային հաշիվ',
'createaccount'              => 'Ստեղծել նոր մասնակցային հաշիվ',
'gotaccount'                 => "Դուք արդեն գրանցվա՞ծ եք։ '''$1'''։",
'gotaccountlink'             => 'Մուտք գործեք համակարգ',
'createaccountmail'          => 'էլ-փոստով',
'badretype'                  => 'Ձեր մուտքագրած գաղտնաբառերը չեն համընկնում։',
'userexists'                 => 'Այս մասնակցի անունը արդեն զբաղված է։ Խնդրում ենք ընտրել մեկ այլ անուն։',
'loginerror'                 => 'Մասնակցի մուտքի սխալ',
'createaccounterror'         => 'Չհաջողվեց ստեղծել մասնակցային հաշիվ. $1',
'nocookiesnew'               => 'Մասնակցային հաշիվը ստեղծված է, սակայն մուտքը համակարգ չհաջողվեց։ {{SITENAME}} կայքը օգտագործում է «քուքիներ» մասնակիցների վավերացման համար։ Ձեր մոտ «քուքիները» արգելված են։ Խնդրում ենք թույլատրել սրանք, ապա մտնել համակարգ ձեր նոր մասնակցի անունով և գաղտնաբառով։',
'nocookieslogin'             => '{{SITENAME}} կայքը օգտագործում է «քուքիներ» մասնակիցների վավերացման համար։ Ձեր մոտ «քուքիները» արգելված են։ Խնդրում ենք թույլատրել սրանք և փորձել կրկին։',
'noname'                     => 'Դուք չեք նշել թույլատրելի մասնակցային անուն։',
'loginsuccesstitle'          => 'Բարեհաջող մուտք',
'loginsuccess'               => "'''Դուք մուտք գործեցիք {{SITENAME}}, որպես \"\$1\"։'''",
'nosuchuser'                 => '$1 անունով մասնակից գոյություն չունի։
Մասնակիցների անունները զգայուն են մեծատառերի նկատմամբ։
Ստուգեք ձեր ուղղագրությունը կամ [[Special:UserLogin/signup|ստեղծեք նոր մասնակցի հաշիվ]]։',
'nosuchusershort'            => '<nowiki>$1</nowiki> անունով մասնակից գոյություն չունի։ Ստուգեք ձեր ուղղագրությունը։',
'nouserspecified'            => 'Հարկավոր է նշել մասնակցային անուն։',
'login-userblocked'          => 'Այս մասնակիցը արգելափակված է: Մուտքը արգելված է:',
'wrongpassword'              => 'Մուտքագրված գաղտնաբառը սխալ էր։ Խնդրում ենք կրկին փորձել։',
'wrongpasswordempty'         => 'Մուտքագրված գաղտնաբառը դատարկ էր։ Խնդրում ենք կրկին փորձել։',
'passwordtooshort'           => 'Գաղտնաբառը պետք է պարունակի առնվազն {{PLURAL:$1|1 սիմվոլ|$1 սիմվոլ}}։',
'password-name-match'        => 'Գաղտնաբառը պետք է տարբեր լինել ձեր մասնակցի անունից։',
'mailmypassword'             => 'Ուղարկել նոր գաղտնաբառ էլ–փոստով',
'passwordremindertitle'      => 'Նոր ժամանակավոր գաղտնաբառ {{grammar:genitive|{{SITENAME}}}} համար',
'passwordremindertext'       => 'Ինչ-որ մեկը (հավանաբար դուք՝ $1 IP-հասցեից) խնդրել է նոր գաղտնաբառ {{grammar:genitive|{{SITENAME}}}} ($4)։ «$2» մասնակցի ժամանակավոր գաղտնաբառն է՝ <code>$3</code>։ Եթե սա իսկապես ձեր մտադրություններ, ապա ձեզ հարկավոր է մտնել համակարգ և փոխել գաղտնաբառը։ Ձեր ժամանակավոր գաղտնաբառը գործելու է {{PLURAL:$5|օր|$5 օր}}։

Եթե դուք չեք արել այսպիսի հայցում կամ արդեն հիշել եք ձեր գաղտնաբառը և մտադրություն չունեք այն փոխել, ապա կարող եք անտեսել այս ուղերձը և շարունակել օգտվել ձեր հին գաղտնաբառից։',
'noemail'                    => '«$1» մասնակցի համար էլ-փոստի հասցե չի նշվել։',
'noemailcreate'              => 'Պահանջվում է տրամադրել գործող էլ-հասցե',
'passwordsent'               => 'Նոր գաղտնաբառ է ուղարկվել $1 մասնակցի համար նշված էլ-փոստի հասցեին։

Խնդրում ենք կրկին ներկայանալ համակարգին այն ստանալուց հետո։',
'blocked-mailpassword'       => 'Ձեր IP հասցեից խմբագրումները արգելափակված են, և հետևաբար արգելված է նաև գաղտնաբառի վերականգնումը՝ հետագա չարաշահումների կանխման նպատակով։',
'eauthentsent'               => 'Առաջարկված էլ-հասցեին ուղարկվել է վավերացման նամակ։
Մինչև որևէ այլ ուղերջներ կուղարկվեն այդ հասցեին, ձեզ անհրաժեշտ է հետևել նամակում նկարագրված գործողություններին՝ հաշվի ձեզ պատկանելու փաստը վավերացնելու համար։',
'throttled-mailpassword'     => 'Գաղտնաբառի հիշեցման ուղերձ արդեն ուղարկվել է վերջին {{PLURAL:$1|ժամվա|$1 ժամվա}} ընթացքում։ Չարաշահման կանխարգելման նպատակով թույլատրվում է միայն մեկ գաղտնաբառի հիշեցում ամեն {{PLURAL:$1|ժամվա|$1 ժամվա}} ընթացքում։',
'mailerror'                  => 'Փոստի ուղարկման սխալ. $1',
'acct_creation_throttle_hit' => 'Վերջին օրվա ընթացքում ձեր IP-հասցեից ստեղծվել է {{PLURAL:$1|1 մասնակցի հաշվիվ|$1 մասնակցի հաշվիվ}}, ինչը այս ժամանակաշրջանում առավելագույն թույլատրելի քանակն է։
Այս պատճառով այդ IP-հասցեից այցելուները չեն կարող այլևս հաշիվ ստեղծել այս պահին։',
'emailauthenticated'         => 'Ձեր էլ-փոստի հասցեն վավերացվել է $2, $3-ին։',
'emailnotauthenticated'      => 'Ձեր էլ-փոստի հասցեն դեռ վավերացված չէ։ Հետևյալ հնարավորությունների գործածումը անջատված է։',
'noemailprefs'               => 'Այս հնարավորության գործածման համար անհրաժեշտ է նշել էլ-փոստի հասցե։',
'emailconfirmlink'           => 'Վավերացնել ձեր էլ-փոստի հասցեն',
'invalidemailaddress'        => 'Նշված էլ-փոստի հասցեն անընդունելի է, քանի որ այն ունի անթույլատրելի ֆորմատ։ Խնդրում ենք նշել ճշմարիտ հասցե կամ այս դաշտը թողնել դատարկ։',
'accountcreated'             => 'Հաշիվը ստեղծված է',
'accountcreatedtext'         => '$1 մասնակցի հաշիվը ստեղծված է։',
'createaccount-title'        => '{{SITENAME}}. մասնակցային հաշվի ստեղծում',
'createaccount-text'         => 'Ինչ-որ մեկը ստեղծել է «$2» անվանմամբ մասնակցային հաշիվ «$3» գաղտնաբառով {{SITENAME}} ($4) նախագծում՝ նշելով ձեր էլ-հասցեն։ Ձեզ անհրաժեշտ է մտնել համակարգ և փոխել գաղտնաբառը։

Կարող եք անտեսել այս հաղորդագրությունը, եթե հաշիվը ստեղծվել է սխալմամբ։',
'usernamehasherror'          => 'Մասնակցի անունը չի կարող պարունակել «#» նիշը։',
'login-throttled'            => 'Դուք կատարել եք չափից շատ մուտքի փորձ։
Խնդրում ենք սպասել որոշ ժամանակ կրկին փորձելուց առաջ։',
'loginlanguagelabel'         => 'Լեզու. $1',

# Change password dialog
'resetpass'                 => 'Փոխել գաղտնաբառը',
'resetpass_announce'        => 'Դուք ներկայացել եք էլ-փոստով ստացված ժամանակավոր գաղտնաբառով։ Համակարգ մուտքի համար անհրաժեշտ է նոր գաղտնաբառ ընտրել այստեղ.',
'resetpass_text'            => '<!-- Ավելացնել տեքստը այստեղ -->',
'resetpass_header'          => 'Փոխել մասնակցային հաշվի գաղտնաբառը',
'oldpassword'               => 'Հին գաղտնաբառը.',
'newpassword'               => 'Նոր գաղտնաբառը.',
'retypenew'                 => 'Հաստատեք նոր գաղտնաբառը.',
'resetpass_submit'          => 'Հաստատել գաղտնաբառը և մտնել համակարգ',
'resetpass_success'         => 'Ձեր գաղտնաբառը փոխված է։ Մուտք համակարգ…',
'resetpass_forbidden'       => 'Գաղտնաբառը չի կարող փոխվել',
'resetpass-no-info'         => 'Այս էջին ուղիղ դիմելու համար անհրաժեշտ է մտնել համակարգ։',
'resetpass-submit-loggedin' => 'Փոխել գաղտնաբառը',
'resetpass-wrong-oldpass'   => 'Սխալ ժամանակավոր կամ ընթացիկ գաղտնաբառ։
Հնարավոր է, որ դուք արդեն բարեհաջող փոխել եք գաղտնաբարը կամ հայցել եք նոր ժամանակավոր գաղտնաբառ։',
'resetpass-temp-password'   => 'Ժամանակավոր գաղտնաբառ.',

# Edit page toolbar
'bold_sample'     => 'Թավատառ տեքստ',
'bold_tip'        => 'Թավատառ տեքստ',
'italic_sample'   => 'Շեղատառ տեքստ',
'italic_tip'      => 'Շեղատառ տեքստ',
'link_sample'     => 'Հղման վերնագիր',
'link_tip'        => 'Ներքին հղում',
'extlink_sample'  => 'http://www.example.com հղման վերնագիրը',
'extlink_tip'     => 'Արտաքին հղում (հիշեք http:// նախածանցը)',
'headline_sample' => 'Ենթագլուխ',
'headline_tip'    => 'Ենթագլուխ',
'nowiki_sample'   => 'Մուտքագրեք չձևավորված տեքստը այստեղ',
'nowiki_tip'      => 'Անտեսել վիքի ձևավորումը',
'image_tip'       => 'Ներդրված նիշք',
'media_tip'       => 'Հղում նիշքին',
'sig_tip'         => 'Ձեր ստորագրությունը ամսաթվով',
'hr_tip'          => 'Հորիզոնական գիծ (միայն անհրաժեշտության դեպքում)',

# Edit pages
'summary'                          => 'Ամփոփում:',
'subject'                          => 'Վերնագիր:',
'minoredit'                        => 'Սա չնչին խմբագրում է',
'watchthis'                        => 'Հսկել այս էջը',
'savearticle'                      => 'Հիշել էջը',
'preview'                          => 'Նախադիտում',
'showpreview'                      => 'Նախադիտել',
'showlivepreview'                  => 'Ուղիղ նախադիտում',
'showdiff'                         => 'Կատարված փոփոխությունները',
'anoneditwarning'                  => "'''Զգուշացում.''' Դուք չեք մտել համակարգ։ Ձեր IP հասցեն կգրանցվի այս էջի խմբագրումների պատմության մեջ։",
'missingsummary'                   => "'''Հիշեցում.''' Դուք չեք տվել խմբագրման ամփոփում։ «Հիշել» կոճակի կրկնակի մատնահարման դեպքում փոփոխությունները կհիշվեն առանց ամփոփման։",
'missingcommenttext'               => 'Խնդրում ենք մեկնաբանություն ավելացնել ստորև։',
'missingcommentheader'             => "'''Հիշեցում.''' Դուք չեք նշել մեկնաբանության վերնագիրը։ «Հիշել» կոճակի կրկնակի մատնահարման դեպքում ձեր մեկնաբանությունը կհիշվի առանց վերնագրի։",
'summary-preview'                  => 'Ամփոփման նախադիտում:',
'subject-preview'                  => 'Վերնագրի նախադիտում:',
'blockedtitle'                     => 'Մասնակիցը արգելափակված է',
'blockedtext'                      => "'''Ձեր մասնակցի անունը կամ IP-հասցեն արգելափակված է։'''

Արգելափակումը կատարվել է $1 ադմինիստրատորի կողմից։
Տրված պատճառն է. ''«$2»''

* Արգելափակման սկիզբ՝ $8
* Արգելափակման մարում՝ $6
* Արգելափակվել է՝ $7

Դուք կարող եք կապնվել $1 մասնակցի կամ մեկ այլ [[{{MediaWiki:Grouppage-sysop}}|ադմինիստրատորի]] հետ՝ ձեր արգելափակումը քննարկելու նպատակով։
Դուք չեք կարող օգտվել` «էլ-նամակ ուղարկել այս մասնակցին» հնարավորությունից, քանի դեռ ինքներդ գործող էլ-փոստի հասցե չէք  նշել ձեր [[Special:Preferences|մասնակցի նախընտրություններում]] և չեք արգելափակվել այս հնարավորւությունը օգտագործելուց։

Ձեր ընթացիկ IP-հասցեն է` $3, արգելափակման իդենտիֆիկատորը՝ #$5։
Խնդրում ենք նշել այս տվյալները ձեր հարցումների ժամանակ։",
'autoblockedtext'                  => "Ձեր IP-հասցեն ավտոմատիկ արգելափակված է, քանի որ այն օգտագործվել է այլ մասնակցի կողմից, որը արգելափակվել է $1 ադմինիստրատորի կողմից։

Տրված պատճառն է.
:''«$2»''

* Արգելափակման սկիզբ՝ $8
* Արգելափակման մարում՝ $6
* Արգելափակվել է՝ $7

Դուք կարող եք կապնվել $1 մասնակցի կամ մեկ այլ [[{{MediaWiki:Grouppage-sysop}}|ադմինիստրատորի]] հետ՝ ձեր արգելափակումը քննարկելու նպատակով։

Դուք չեք կարող օգտվել «էլ-նամակ ուղարկել այս մասնակցին» հնարավորությունից քանի դեռ ինքներդ գործող էլ-փոստի հասցե չէք  նշել ձեր [[Special:Preferences|մասնակցի նախընտրություններում]] և չեք արգելափակվել այս հնարավորությից օգտվելուց։

Ձեր ընթացիկ IP-հասցեն է $3, արգելափակման իդենտիֆիկատորը՝ $5։
Խնդրում ենք նշել այս տվյալները ձեր հարցումների ժամանակ։",
'blockednoreason'                  => 'պատճառ չի տրվել',
'blockedoriginalsource'            => "«'''$1'''» էջի տեքստը բերված է ստորև։",
'blockededitsource'                => "«'''$1'''» էջի '''ձեր խմբագրումները''' հետևյալն են.",
'whitelistedittitle'               => 'Խմբագրման համար հարկավոր է մտնել համակարգ',
'whitelistedittext'                => 'Անհրաժեշտ է $1 էջերը խմբագրելու համար։',
'confirmedittext'                  => 'Էջերի խմբագրումից առաջ անհրաժեշտ է վավերացնել էլ-հասցեն։
Խնդրում ենք նշել և վավերացնել ձեր էլ-փոստի հասցեն ձեր [[Special:Preferences|նախընտրությունների]] մեջ։',
'nosuchsectiontitle'               => 'Այսպիսի բաժին գոյություն չունի',
'nosuchsectiontext'                => 'Դուք փորձել եք խմբագրել գոյություն չունեցող բաժին։',
'loginreqtitle'                    => 'Անհրաժեշտ է մտնել համակարգ',
'loginreqlink'                     => 'մտնել համակարգ',
'loginreqpagetext'                 => 'Անհրաժեշտ է $1 այլ էջեր դիտելու համար։',
'accmailtitle'                     => 'Գաղտնաբառն ուղարկված է։',
'accmailtext'                      => "[[User talk:$1|$1]] մասնակցի համար պատահական նշերից կազմված գաղտնաբառը ուղարկված է $2 հասցեին։

Համակարգ մուտք գործելուն պես կարող եք ''[[Special:ChangePassword|փոխել գաղտնաբառը]]''։",
'newarticle'                       => '(Նոր)',
'newarticletext'                   => "Դուք հղվել եք դեռևս գոյություն չունեցող էջի։ Էջը ստեղծելու համար սկսեք տեքստի մուտքագրումը ներքևի արկղում (այցելեք [[{{MediaWiki:Helppage}}|օգնության էջը]]՝ մանրամասն տեղեկությունների համար)։ Եթե դուք սխալմամբ եք այստեղ հայտնվել, ապա մատնահարեք ձեր զննարկիչի '''back''' կոճակը։",
'anontalkpagetext'                 => "----''Այս քննարկման էջը պատկանում է անանուն մասնակցին, որը դեռ չի ստեղծել մասնակցային հաշիվ կամ չի մտել համակարգ մասնակցի անունով։
Այդ իսկ պատճառով օգտագործվում է թվային IP-հասցեն։
Նման IP-հասցեից կարող են օգտվել մի քանի մասնակիցներ։
Եթե դուք անանուն մասնակից եք, բայց կարծում եք, որ ուրիշներին վերաբերող դիտողությունները արվում են ձեր հասցեով, ապա խնդրում ենք պարզապես [[Special:UserLogin/signup|գրանցվել]] կամ [[Special:UserLogin|մտնել համակարգ]], որպեսզի հետագայում ձեզ չշփոթեն այլ անանուն մասնակիցների հետ։''",
'noarticletext'                    => 'Ներկայումս այս էջում որևէ տեքստ չկա։
Դուք կարող եք [[Special:Search/{{PAGENAME}}|որոնել այս անվանումը]] այլ էջերում, <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} որոնել համապատասխան տեղեկամատյանները] կամ [{{fullurl:{{FULLPAGENAME}}|action=edit}} ստեղծել նոր էջ այս անվանմամբ]</span>։',
'noarticletext-nopermission'       => 'Ներկայումս այս էջում որևէ տեքստ չկա։
Դուք կարող եք [[Special:Search/{{PAGENAME}}|որոնել այս անվանունը]] այլ էջերում կամ <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} որոնել այն տեղեկամատյաններում]</span>։',
'userpage-userdoesnotexist'        => '«$1» անվանմամբ մասնակից գոյություն չունի։
Խնդրում ենք հավաստիանալ նրանում, թե արդյոք ուզում եք ստեղծել/խմբագրել այս էջը։',
'userpage-userdoesnotexist-view'   => '«$1» անվանմամբ գրանցված մասնակից չկա։',
'clearyourcache'                   => "'''Ծանուցում. Հիշելուց հետո կատարված փոփոխությունները տեսնելու համար մաքրեք ձեր զննարկիչի հիշապահեստը. '''
'''Mozilla / Firefox / Safari'''՝ ''Ctrl+Shift+R''  (''Cmd+Shift+R'' Mac OS X-ում)
'''Konqueror'''՝ ''F5''
'''Opera'''՝ ''Tools→Preferences'' ընտրացանկից։
'''Internet Explorer'''՝ ''Ctrl+F5''",
'usercssyoucanpreview'             => "'''Հուշում.''' Էջը հիշելուց առաջ օգտվեք «{{int:showpreview}}» կոճակից՝ ձեր նոր CSS-նիշքը ստուգելու համար։",
'userjsyoucanpreview'              => "'''Հուշում.''' Էջը հիշելուց առաջ օգտվեք «{{int:showpreview}}» կոճակից՝ ձեր նոր JS-նիշքը ստուգելու համար։",
'usercsspreview'                   => "'''Նկատի ունեցեք, որ դուք միայն նախադիտում եք ձեր մասնակցի CSS-նիշքը. այն դեռ հիշված չէ՛։'''",
'userjspreview'                    => "'''Նկատի ունեցեք, որ դուք միայն նախադիտում եք ձեր մասնակցի JavaScript-նիշքը. այն դեռ հիշված չէ՛։'''",
'userinvalidcssjstitle'            => "'''Զգուշացում.''' «$1» տեսք չի գտնվել։ Ի նկատի ունեցեք, որ մասնակցային .css և .js էջերը ունեն փոքրատառ անվանումներ, օր.՝ «{{ns:user}}:Ոմն/vector.css», և ոչ թե «{{ns:user}}:Ոմն/Vector.css»։",
'updated'                          => '(Թարմացված)',
'note'                             => "'''Ծանուցում.'''",
'previewnote'                      => "'''Սա միայն նախադիտումն է. ձեր կատարած փոփոխությունները դեռ չե՛ն հիշվել։'''",
'previewconflict'                  => 'Այս նախադիտումը արտապատկերում է վերևի խմբագրման դաշտում եղած տեքստը այնպես, ինչպես այն կերևա հիշվելուց հետո։',
'session_fail_preview'             => "'''Ցավոք՝ չհաջողվեց հիշել ձեր խմբագրումները սեսիայի տվյալների կորստի պատճառով։
Խնդրում ենք կրկին փորձել։ Սխալի կրկնման դեպքում՝ փորձեք դուրս գալ, ապա կրկին մտնել համակարգ։'''",
'session_fail_preview_html'        => "'''Ցավոք՝ չհաջողվեց հիշել ձեր խմբագրումները սեսիայի տվյալների կորստի պատճառով։'''

''Քանի որ {{SITENAME}} նախագիծը թույլատրում է հում HTML, նախադիտումը անջատված է JavaScript-գրոհի կանխման նպատակով։''

'''Եթե սա բարեխիղճ խմբագրման փորձ է, խնդրում ենք փորձել կրկին։ Սխալի կրկնման դեպքում՝ փորձեք [[Special:UserLogout|դուրս գալ]], ապա կրկին մտնել համակարգ։'''",
'token_suffix_mismatch'            => "'''Ձեր խմբագրումը մերժվել է, քանի որ ձեր օգտագործած ծրագիրը աղավաղել է կետադրության նշանները խմբագրման դաշտում։ Խմբագրումը մերժվել է էջի տեքստի խաթարումը կանխելու նպատակով։ Սա երբեմն պայմանավորված է սխալներ պարունակող անանվանեցնող վեբ-փոխարինորդ (proxy) ծառայության օգտագործմամբ։'''",
'editing'                          => 'Խմբագրում. $1',
'editingsection'                   => 'Խմբագրում. $1 (բաժին)',
'editingcomment'                   => 'Խմբագրում $1 (նոր բաժին)',
'editconflict'                     => 'Խմբագրման ընդհարում. $1',
'explainconflict'                  => "Մեկ այլ մասնակից փոփոխել է այս տեքստը ձեր խմբագրման ընթացքում։
Վերին խմբագրման դաշտում ընդգրկված է ընթացիկ տեքստը, որն ենթակա է հիշման։
Ձեր խմբագրումներով տեքստը գտնվում է ստորին դաշտում։
Որպեսզի ձեր փոփոխությունները հիշվեն, միաձուլեք դրանք վերին տեքստի մեջ։
«{{int:savearticle}}» կոճակին սեղմելով կհիշվի '''միայն''' վերևվի դաշտի տեքստը:",
'yourtext'                         => 'Ձեր տեքստը',
'storedversion'                    => 'Պահված տարբերակ',
'nonunicodebrowser'                => "'''ԶԳՈՒՇԱՑՈՒՄ. Ձեր զննարկիչը չունի Յունիկոդ ապահովում։ Հոդվածներ խմբագրելիս բոլոր ոչ-ASCII սիմվլոները փոխարինվելու են իրենց տասնվեցական կոդերով։'''",
'editingold'                       => "'''ԶԳՈՒՇԱՑՈՒՄ. Դուք խմբագրում եք այս էջի հնացած տարբերակ։ Էջը հիշելուց հետո հետագա տարբերակներում կատարված փոփոխությունները կկորեն։'''",
'yourdiff'                         => 'Տարբերությունները',
'copyrightwarning'                 => "Հաշվի առեք, որ տեքստի յուրաքանչյուր լրացում և փոփոխություն համարվում է $2 արտոնագրի համաձայն թողարկված (տե՛ս $1 մանրամասների համար)։ Եթե չեք ցանկանում, որ ձեր նյութը անողոքաբար խմբագրվի ու ազատորեն տարածվի, ապա մի՛ տեղադրեք այն այստեղ։<br />
Նյութն այստեղ տեղադրելով՝ դուք նաև հավաստիացնում եք մեզ, որ այն գրված է ձեր կողմից կամ վերցված է ազատ տարածում և բովանդակային փոփոխություններ թույլատրող աղբյուրներից։
'''ԱՌԱՆՑ ԹՈՒՅԼՏՎՈՒԹՅԱՆ ՄԻ՛ ՏԵՂԱԴՐԵՔ ՀԵՂԻՆԱԿԱՅԻՆ ԻՐԱՎՈՒՆՔՆԵՐՈՎ ՊԱՇՏՊԱՆՎԱԾ ՆՅՈՒԹԵՐ։'''",
'copyrightwarning2'                => "Խնդրում ենք ի նկատի ունենալ, որ {{SITENAME}} կայքում արված բոլոր ներդրումները կարող են խմբագրվել, վերամշակվել կամ ջնջվել ուրիշ մասնակիցների կողմից։ Եթե դուք չեք ցանկանում, որ ձեր նյութը խմբագրվի, ապա մի՛ տեղադրեք այն այստեղ։<br /> Դուք նաև հավաստիացնում եք մեզ, որ նյութը գրված է ձեր կողմից կամ վերցված է ազատ տարածում և պարունակության փոփոխություններ թույլատրող աղբյուրներից (մանրամասնությունների համար տես $1)։ '''ՄԻ՛ ՏԵՂԱԴՐԵՔ ՀԵՂԻՆԱԿԱՅԻՆ ԻՐԱՎՈՒՆՔՆԵՐՈՎ ՊԱՇՏՊԱՆՎԱԾ ՆՅՈՒԹԵՐ ԱՌԱՆՑ ԹՈՒՅԼԱՏՐՈՒԹՅԱՆ։'''",
'longpageerror'                    => "'''ՍԽԱԼ. Ներկայացված տեքստը ունի $1 կիլոբայթ երկարություն, ինչը գերազանցում է սահմանված $2 ԿԲ առավելագույն չափը։ Էջը չի կարող հիշվել։'''",
'readonlywarning'                  => "'''ԶԳՈՒՇԱՑՈՒՄ. Տվյալների բազան կողպվել է սպասարկման նպատակով, և դուք չեք կարող հիշել ձեր կատարած փոփոխությունները այս պահին։
Հավանաբար իմաստ ունի պատճենել տեքստը տեքստային նիշքի մեջ և պահել այն՝ հետագայում նախագծում ավելացնելու համար։'''

Տվյալների բազան կողպած ադմինիստրատորը թողել է հետևյալ բացատրությունը. $1",
'protectedpagewarning'             => "'''ԶԳՈՒՇԱՑՈՒՄ. Այս էջը պաշտպանված է փոփոխություններից. այն կարող են խմբագրել միայն ադմինիստրատորները։'''",
'semiprotectedpagewarning'         => "'''Ծանուցում.''' Այս էջը պաշտպանված է. այն կարող են խմբագրել միայն գրանցված մասնակիցները։",
'cascadeprotectedwarning'          => "'''Զգուշացում.''' Այս էջը պաշտպանված է և կարող է խմբագրվել միայն ադմինիստրատորների կողմից, քանի որ այն ընդգրկված է հետևյալ կասկադային-պաշտպանմամբ {{PLURAL:$1|էջում|էջերում}}.",
'titleprotectedwarning'            => "'''Զգուշացում. Այս էջը պաշտպանված է. այն կարող են խմբագրել միայն [[Special:ListGroupRights|համապատասխան իրավունքներով]] մասնակիցները։'''",
'templatesused'                    => 'Այս էջում օգտագործված {{PLURAL:$1|կաղապարը|կաղապարները}}.',
'templatesusedpreview'             => 'Այս նախադիտման մեջ օգտագործված {{PLURAL:$1|կաղապարը|կաղապարները}}.',
'templatesusedsection'             => 'Այս բաժնում օգտագործված {{PLURAL:$1|կաղապարը|կաղապարները}}.',
'template-protected'               => '(պաշտպանված)',
'template-semiprotected'           => '(կիսապաշտպանված)',
'hiddencategories'                 => 'Այս էջը պատկանում է հետևյալ {{PLURAL:$1|1 թաքնված կատեգորիային|$1 թաքնված կատեգորիաներին}}.',
'edittools'                        => '<!-- Այստեղ տեղադրված տեքստը կցուցադրվի խմբագրման և բեռնման ձևերի տակ։ -->',
'nocreatetitle'                    => 'Էջերի ստեղծումը սահմանափակված է',
'nocreatetext'                     => '{{SITENAME}} կայքում էջերի ստեղծման հնարավորությունը սահմանափակված է։
Դուք կարող եք վերադառնալ և խմբագրել գոյություն ունեցող էջ կամ էլ [[Special:UserLogin|գրանցվել կամ մտնել համակարգ]]։',
'nocreate-loggedin'                => 'Դուք չունեք նոր էջեր ստեղծելու թույլտվություն։',
'permissionserrors'                => 'Թույլատրության Սխալներ',
'permissionserrorstext'            => 'Ձեզ չի թույլատրվում դա անել հետևյալ {{PLURAL:$1|պատճառով|պատճառներով}}.',
'permissionserrorstext-withaction' => 'Ձեզ չի թույլատրվում $2 հետևյալ {{PLURAL:$1|պատճառով|պատճառներով}}.',
'recreate-moveddeleted-warn'       => "'''Զգուշացում. դուք փորձում եք վերստեղծել մի էջ, որը ջնջվել է նախկինում։'''

Խնդրում ենք վերանայել ձեր խմբագրման նպատակահարմարությունը։ Հարմարության համար ստորև բերված են այս էջի ջնջման և տեղափոխման տեղեկամատյանները։",
'moveddeleted-notice'              => 'Այս էջը հեռացված է։
Էջի մասին գրառումները տեղափոխման և ջնջման տեղեկամատյանից բերված են ստորև տեղեկության համար։',
'edit-conflict'                    => 'Խմբագրման ընհարում։',
'edit-no-change'                   => 'Ձեր խմբագրումը անտեսվել է, քանի որ ոչ մի փոփոխություն չի կատարվել տեքստի մեջ։',

# "Undo" feature
'undo-success' => 'Խմբագրումը կարող է հետ շրջվել։ Ստուգեք տարբերակների համեմատությունը ստորև, որպեսզի համոզվեք, որ դա է ձեզ հետաքրքրող փոփոխությունը և մատնահարեք «Հիշել էջը»՝ գործողությունն ավարտելու համար։',
'undo-failure' => 'Խմբագրումը չի կարող հետ շրջվել միջանկյալ խմբագրումների ընդհարման պատճառով։',
'undo-summary' => 'Հետ է շրջվում $1 խմբագրումը, որի հեղինակն է՝ [[Special:Contributions/$2|$2]] ([[User talk:$2|քննարկում]])',

# Account creation failure
'cantcreateaccounttitle' => 'Չհաջողվեց ստեղծել մասնակցային հաշիվ',
'cantcreateaccount-text' => "Այս IP-հասցեից ('''$1''') մասնակցային հաշվի ստեղծումը արգելափակվել է [[User:$3|$3]] մասնակցի կողմից։

$3 մասնակիցը տվել է հետևյալ պատճառը. ''$2''",

# History pages
'viewpagelogs'           => 'Դիտել այս էջի տեղեկամատյանները',
'nohistory'              => 'Այս էջը չունի խմբագրումների պատմություն։',
'currentrev'             => 'Ընթացիկ տարբերակ',
'currentrev-asof'        => 'Ընթացիկ տարբերակը $1-ի դրությամբ',
'revisionasof'           => '$1-ի տարբերակ',
'revision-info'          => '$1 տարբերակ, $2',
'previousrevision'       => '←Նախորդ տարբերակ',
'nextrevision'           => 'Հաջորդ տարբերակ→',
'currentrevisionlink'    => 'Ընթացիկ տարբերակ',
'cur'                    => 'ընթ',
'next'                   => 'հաջորդ',
'last'                   => 'նախ',
'page_first'             => 'առաջին',
'page_last'              => 'վերջին',
'histlegend'             => "Տարբերությունների համեմատում. դրեք նշման կետեր այն տարբերակների կողքին, որոնք ուզում եք համեմատել և սեղմեք ներքևում գտնվող կոճակը։<br />
Պարզաբանում. (ընթ) = համեմատել ընթացիկ տարբերակի հետ,
(նախ) = համեմատել նախորդ տարբերակի հետ,<br />'''չ''' = չնչին խմբագրում",
'history-fieldset-title' => 'Դիտել պատմությունը',
'history-show-deleted'   => 'Միայն ջնջված',
'histfirst'              => 'Առաջին',
'histlast'               => 'Վերջին',
'historysize'            => '({{PLURAL:$1|1 բայթ|$1 բայթ}})',
'historyempty'           => '(դատարկ)',

# Revision feed
'history-feed-title'          => 'Փոփոխությունների պատմություն',
'history-feed-description'    => 'Վիքիի այս էջի փոփոխումների պատմություն',
'history-feed-item-nocomment' => '$1՝ $2',
'history-feed-empty'          => 'Հայցված էջը գոյություն չունի։
Հնարավոր է այն ջնջվել է վիքիից կամ վերանվանվել։
Փորձեք [[Special:Search|որոնել վիքիում]] նոր համանման էջեր։',

# Revision deletion
'rev-deleted-comment'         => '(մեկնաբանությունը հեռացված է)',
'rev-deleted-user'            => '(մասնակցի անունը ջնջված է)',
'rev-deleted-event'           => '(գրությունը հեռացված է)',
'rev-deleted-text-permission' => 'Էջի այս տարբերակը հեռացված է։
Հնարավոր է մանրամասնություններ լինեն [{{fullurl:{{ns:special}}:Log/delete|page={{PAGENAMEE}}}} ջնջման տեղեկամատյանում]։',
'rev-deleted-text-view'       => "Էջի այս տարբերակը '''ջնջված''' է։
Որպես ադմինիստրատոր դուք կարող եք դիտել այն։
Հնարավոր է ջնջման մանրամասնություններ լինեն [{{fullurl:{{ns:special}}:Log/delete|page={{FULLPAGENAMEE}}}} ջնջման տեղեկամատյանում]։",
'rev-delundel'                => 'ցույց տալ/թաքցնել',
'revisiondelete'              => 'Ջնջել/վերականգնել տարբերակները',
'revdelete-nooldid-title'     => 'Անվավեր նպատակային տարբերակ',
'revdelete-nooldid-text'      => 'Դուք կա՛մ չեք նշել նպատակային տարբերակ(ներ)ը այս ֆունկցիայի կատարման համար, կա՛մ նպատակային տարբերակը գոյություն չունի, կա՛մ էլ դուք փորձում եք թաքցնել ընթացիկ տարբերակը։',
'revdelete-selected'          => "'''[[:$1]] էջի ընտրված {{PLURAL:$2|տարբերակը|տարբերակները}}.'''",
'logdelete-selected'          => "'''Տեղեկամատյանի ընտրված {{PLURAL:$1|գրառումը|գրառումները}}.'''",
'revdelete-text'              => "'''Ջնջված տարբերակները երևալու են էջերի պատմության մեջ և տեղեկամատյաններում, բայց դրանց պարունակության մի մասը հասարակ այցելուներին չի ցուցադրվելու։'''

Ադմինիստրատորները հնարավորություն կունենան դիտել թաքցված պարունակությունը, ինչպես նաև վերականգնել այն այս նույն ինտերֆեյսի միջոցով, բացառությամբ ավելորդ սահմանափակումների դեպքում։",
'revdelete-legend'            => 'Սահմանել տեսանելիության սահմանափակումներ',
'revdelete-hide-text'         => 'Թաքցնել տարբերակի տեքստը',
'revdelete-hide-image'        => 'Թաքցնել նիշքի պարունակությունը',
'revdelete-hide-name'         => 'Թաքցնել գործողությունը և առարկան',
'revdelete-hide-comment'      => 'Թաքցնել մեկնաբանությունը',
'revdelete-hide-user'         => 'Թաքցնել հեղինակի մասնակցի անունը/IP',
'revdelete-hide-restricted'   => 'Թաքցնել տվյալները և՛ ադմինիստրատորներից, և՛ այլ մասնակիցներից',
'revdelete-radio-set'         => 'Այո',
'revdelete-radio-unset'       => 'Ոչ',
'revdelete-suppress'          => 'Թաքցնել տվյալները ադմինիստրատորներից և մյուսներից նոյնպես',
'revdelete-unsuppress'        => 'Հանել սահմանափակումները վերականգնված տարբերակներից',
'revdelete-log'               => 'Պատճառ.',
'revdelete-submit'            => 'Կիրառել ընտրված տարբերակի վրա',
'revdelete-logentry'          => '«[[$1]]»-ի տարբերակների տեսանելիությունը փոփոխված է',
'logdelete-logentry'          => '«[[$1]]»-ի իրադարձությունների տեսանելիությունը փոփոխված է',
'revdelete-success'           => "'''Տարբերակի տեսանելիությունը փոփոխված է։'''",
'logdelete-success'           => "'''Իրադարձության տեսանելիությունը փոփոխված է։'''",
'revdel-restore'              => 'Փոխել տեսանելիությունը',
'pagehist'                    => 'Էջի պատմություն',
'deletedhist'                 => 'Ջնջումների պատմություն',
'revdelete-otherreason'       => 'Ուրիշ/հավելյալ պատճառ՝',
'revdelete-reasonotherlist'   => 'Ուրիշ պատճառ',
'revdelete-edit-reasonlist'   => 'Խմբագրել ջնջման պատճառների ցանկը',
'revdelete-offender'          => 'Էջի տարբերակի հեղինակ՝',

# Merge log
'revertmerge' => 'Անջատել',

# Diffs
'history-title'           => '«$1» էջի փոփոխումների պատմություն',
'difference'              => '(Խմբագրումների միջև եղած տարբերությունները)',
'lineno'                  => 'Տող  $1.',
'compareselectedversions' => 'Համեմատել ընտրած տարբերակները',
'editundo'                => 'հետ շրջել',
'diff-multi'              => '({{PLURAL:$1|$1 միջանկյալ տարբերակ|$1 միջանկյալ տարբերակ}} ցուցադրված չէ։)',

# Search results
'searchresults'                    => 'Որոնման արդյունքներ',
'searchresults-title'              => '«$1»-ի որոնման արդյունքներ',
'searchresulttext'                 => '{{SITENAME}} կայքում որոնման մասին տեղեկությունների համար այցելեք [[{{MediaWiki:Helppage}}|{{int:help}}]] էջը։',
'searchsubtitle'                   => "Դուք որոնել եք «'''[[:$1]]'''» ([[Special:Prefixindex/$1|այս անվանմամբ սկսվող էջերը]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|այս անվանվանը հղվող էջերը]])",
'searchsubtitleinvalid'            => 'Դուք որոնել եք «$1»',
'titlematches'                     => 'Համընկած հոդվածների անվանումներ',
'notitlematches'                   => 'Չկան համընկած հոդվածների անվանումներ',
'textmatches'                      => 'Համընկած տեքստերով էջեր',
'notextmatches'                    => 'Չկան համընկած տեքստերով էջեր',
'prevn'                            => 'նախորդ {{PLURAL:$1|$1}}',
'nextn'                            => 'հաջորդ {{PLURAL:$1|$1}}',
'viewprevnext'                     => 'Դիտել ($1 {{int:pipe-separator}} $2) ($3)',
'searchmenu-legend'                => 'Որոնման ընտրանքներ',
'searchmenu-exists'                => "'''Այս վիքիում, գոյություն ունի \"[[:\$1]]\" անվանումով էջը։'''",
'searchmenu-new'                   => "'''Ստեղծե՛լ \"[[:\$1]]\" էջը այս վիքիում'''",
'searchhelp-url'                   => 'Help:Գլխացանկ',
'searchmenu-prefix'                => '[[Special:PrefixIndex/$1|Ցուցադրել այս նախածանցով սկսվող էջերը]]',
'searchprofile-articles'           => 'Հիմնական էջեր',
'searchprofile-project'            => 'Օգնության և նախագծերի էջեր',
'searchprofile-images'             => 'Մուլտիմեդիա',
'searchprofile-everything'         => 'Ամենուրեք',
'searchprofile-advanced'           => 'Ընդլայնված',
'searchprofile-articles-tooltip'   => 'Որոնել $1ում',
'searchprofile-project-tooltip'    => 'Որոնել $1ում',
'searchprofile-images-tooltip'     => 'Նիշքերի որոնում',
'searchprofile-everything-tooltip' => 'Որոնել բոլոր էջերում (այդ թվում քննարկման)',
'searchprofile-advanced-tooltip'   => 'Որոնել նշված անվանատարածքներում',
'search-result-size'               => '$1 ({{PLURAL:$2|1 բառ|$2 բառ}})',
'search-redirect'                  => '(վերահղում $1)',
'search-section'                   => '(բաժին $1)',
'search-suggest'                   => 'Դուք ի նկատի ունե՞ք՝ $1',
'search-interwiki-caption'         => 'Կից նախագծեր',
'search-interwiki-default'         => '$1 արդյունք.',
'search-interwiki-more'            => '(էլի)',
'search-mwsuggest-enabled'         => 'առաջարկներով',
'search-mwsuggest-disabled'        => 'առանց առաջարկների',
'search-relatedarticle'            => 'Հարակից',
'mwsuggest-disable'                => 'Անջատել AJAX հուշումներ',
'searcheverything-enable'          => 'Որոնել բոլոր անվանատարածքներում',
'searchrelated'                    => 'հարակից',
'searchall'                        => 'բոլոր',
'showingresults'                   => "Ստորև բերված է մինչև {{PLURAL:$1|'''1''' արդյունք|'''$1''' արդյունք}}՝ սկսած №&nbsp;<strong>$2</strong>-ից։",
'showingresultsnum'                => "Ստորև բերված է {{PLURAL:$3|'''1''' արդյունք|'''$3''' արդյունք}}` սկսած №&nbsp;<strong>$2</strong>-ից։",
'showingresultsheader'             => "{{PLURAL:$5|'''$1''' արդյունք '''$3'''-ից|'''$1 - $2''' արդյունքներ '''$3'''-ից}}  '''$4'''-ի համար",
'nonefound'                        => "'''Ծանուցում'''։ Լռությամբ որոնումը կատարվում է ոչ բոլոր անվանատարածքներում։ Բոլոր անվանատարածքներում որոնելու համար (ներառյալ քննարկման էջերը, կաղապարները և այլն) օգտագործեք ''all:'' նախածանցը կամ նշեք ցանկացած անվանատարածքի անվանումը որպես նախածանց։",
'search-nonefound'                 => 'Որոնմանը համապատասխանող արդյունքներ չեն գտնվել։',
'powersearch'                      => 'Ընդլայնված որոնում',
'powersearch-legend'               => 'Ընդլայնված որոնում',
'powersearch-ns'                   => 'Որոնել անվանատարածքում.',
'powersearch-redir'                => 'Ցույց տալ վերահղումները',
'powersearch-field'                => 'Որոնել',
'powersearch-togglelabel'          => 'Նշել․',
'powersearch-toggleall'            => 'Բոլորը',
'powersearch-togglenone'           => 'Ոչ մեկը',
'search-external'                  => 'Արտաքին որոնում',
'searchdisabled'                   => '{{SITENAME}} կայքի ներքին որոնումը անջատված է։ Դուք կարող եք որոնել կայքի պարունակությունը արտաքին որոնման շարժիչներով (Google, Yahoo...), սակայն, ի նկատի ունեցեք, որ կայքի իրենց ինդեքսները կարող են հնացած լինել։',

# Quickbar
'qbsettings'               => 'Արագ անցման վահանակ',
'qbsettings-none'          => 'Չցուցադրել',
'qbsettings-fixedleft'     => 'Ձախից անշարժ',
'qbsettings-fixedright'    => 'Աջից անշարժ',
'qbsettings-floatingleft'  => 'Ձախից լողացող',
'qbsettings-floatingright' => 'Աջից լողացող',

# Preferences page
'preferences'                   => 'Նախընտրություններ',
'mypreferences'                 => 'Իմ նախընտրությունները',
'prefs-edits'                   => 'Խմբագրումների քանակը.',
'prefsnologin'                  => 'Դուք չեք մտել համակարգ',
'prefsnologintext'              => 'Մասնակցային նախընտրությունները փոփոխելու համար անհրաժեշտ է <span class="plainlinks">[{{fullurl:{{#Special:UserLogin}}|returnto=$1}} մտնել համակարգ]</span>։',
'changepassword'                => 'Փոխել գաղտնաբառը',
'prefs-skin'                    => 'Տեսք',
'skin-preview'                  => 'նախադիտել',
'datedefault'                   => 'Առանց նախընտրության',
'prefs-datetime'                => 'Օր ու ժամ',
'prefs-personal'                => 'Անձնական',
'prefs-rc'                      => 'Վերջին փոփոխություններ',
'prefs-watchlist'               => 'Հսկացանկ',
'prefs-watchlist-days'          => 'Հսկացանկում ցուցադրվող օրերի թիվը՝',
'prefs-watchlist-days-max'      => '(7 օրից ոչ ավել)',
'prefs-watchlist-edits'         => 'Ընդարձակված հսկացանկում ցուցադրվող օրերի թիվը՝',
'prefs-watchlist-edits-max'     => '(1000-ից ոչ ավել)',
'prefs-watchlist-token'         => 'Հսկացանկի կտրոն.',
'prefs-misc'                    => 'Այլ',
'prefs-resetpass'               => 'Փոխել գաղտնաբառը',
'prefs-email'                   => 'Էլ-հասցեի ընտրանքներ',
'prefs-rendering'               => 'Արտաքին տեսք',
'saveprefs'                     => 'Հիշել',
'resetprefs'                    => 'Անտեսել փոփոխությունները',
'restoreprefs'                  => 'Զրոյացնել, բերելով իլռելայն նախընտրանքներին',
'prefs-editing'                 => 'Խմբագրում',
'prefs-edit-boxsize'            => 'Խմբագրման պատուհանի չափը։',
'rows'                          => 'Տողեր`',
'columns'                       => 'Սյունակներ',
'searchresultshead'             => 'Որոնում',
'resultsperpage'                => 'Արդյունքների քանակը մեկ էջում.',
'stub-threshold'                => '<a href="#" class="stub">Պատառ հոդվածների հղումների</a> ձևավորման որոշման սահմանը.',
'recentchangesdays'             => 'Վերջին փոփոխություններում ցուցադրվող օրերի թիվը՝',
'recentchangesdays-max'         => '($1 {{PLURAL:$1|օրից|օրից}} ոչ ավել)',
'recentchangescount'            => 'Խմբագրումների թիվը ըստ լռության.',
'prefs-help-recentchangescount' => 'Ներառում է վերջին փոփոխությունները, էջերի պատմությունը և տեղեկամատյանները։',
'prefs-help-watchlist-token'    => 'Այս դաշտը գաղտնի բանալիով լռացնելը կբերի ձեր հսկողության ցանկի RSS ժապավենի ստեղծմանը։
Ցանկացած մեկը, ով գիտի այս բանալին, կարող է կարդալ ձեր հսկողության ցանկը, այդ պատճառով ընտրեք գաղտնի արժեք։
Դուք կարող եք օգտագործել այս պատահականորեն գեներացված բանալին՝ $1',
'savedprefs'                    => 'Ձեր նախընտրությունները հիշված են։',
'timezonelegend'                => 'Ժամային գոտի.',
'localtime'                     => 'Տեղական ժամանակ.',
'timezoneuseserverdefault'      => 'Օգտագործել սերվերի ժամանակը',
'timezoneuseoffset'             => 'Այլ (նշեք տարբերությունը)',
'timezoneoffset'                => 'Տարբերություն¹.',
'servertime'                    => 'Սերվերի ժամանակ.',
'guesstimezone'                 => 'Լրացնել բրաուզերից',
'timezoneregion-africa'         => 'Աֆրիկա',
'timezoneregion-america'        => 'Ամերիկա',
'timezoneregion-antarctica'     => 'Անտարկտիկա',
'timezoneregion-arctic'         => 'Արկտիկա',
'timezoneregion-asia'           => 'Ասիա',
'timezoneregion-atlantic'       => 'Ատլանտյան օվկիանոս',
'timezoneregion-australia'      => 'Ավստրալիա',
'timezoneregion-europe'         => 'Եվրոպա',
'timezoneregion-indian'         => 'Հնդկական Օվկիանոս',
'timezoneregion-pacific'        => 'Խաղաղ օվկիանոս',
'allowemail'                    => 'Թույլատրել էլ-նամակներ մյուս մասնակիցներից',
'prefs-searchoptions'           => 'Որոնման ընտրանքներ',
'prefs-namespaces'              => 'Անվանատարածք',
'defaultns'                     => 'Հակառակ դեպքում, որոնել այս անվանատարծքներում․',
'default'                       => 'լռությամբ',
'prefs-files'                   => 'Նիշքեր',
'prefs-custom-css'              => 'Անհատական CSS',
'prefs-custom-js'               => 'Անհատական JS',
'prefs-reset-intro'             => 'Այս էջում հնաարավոր է զրոյացնել ձեր բոլոր նախընրանքները, փոխելով դրանք իլռելայն նախընտրանքների։
Գործողությունը հաստատելուց հետո, նածընտրանքները հնաարվոր չի լինելու հետ բերել։',
'prefs-emailconfirm-label'      => 'Էլ-փոստի վավերացում․',
'prefs-textboxsize'             => 'Խմբագրման պատուհանի չափը',
'youremail'                     => 'Էլեկտրոնային փոստ.',
'username'                      => 'Մասնակցի անուն.',
'uid'                           => 'Մասնակցի իդենտիֆիկատոր.',
'prefs-memberingroups'          => 'Անդամակցության {{PLURAL:$1|խումբ|խմբեր}}.',
'prefs-registration'            => 'Գրանցման ամսաթիվը․',
'yourrealname'                  => 'Ձեր իրական անունը.',
'yourlanguage'                  => 'Ինտերֆեյսի լեզուն.',
'yourvariant'                   => 'Լեզվական տարբերակ',
'yournick'                      => 'Ստորագրություն.',
'prefs-help-signature'          => 'Քննարկման էջերում մեկնաբանությունները անհրաժեշտ է ստորագրել "<nowiki>~~~~</nowiki>" նշանագրով, որը կվերածվի ձեր ստորագրությանը և ամսաթվին։',
'badsig'                        => 'Սխալ ստորագրություն. ստուգեք HTML-թեգերը։',
'badsiglength'                  => 'Ստորագրությունը շատ երկար է։
Այն պետք է լինի {{PLURAL:$1|սիմվոլից|սիմվոլից}} ոչ ավել։',
'yourgender'                    => 'Սեռ․',
'gender-unknown'                => 'Չնշված',
'gender-male'                   => 'Արական',
'gender-female'                 => 'Իգական',
'prefs-help-gender'             => 'Ոչ պարտադիր դաշտ․ ծրագիրը օգտագործում է այս տվյալը մասնակցին ճիշտ դիմելու համար։
Այս տեղեկությունը բոլորին տեսանլի է լինելու։',
'email'                         => 'Էլ-փոստ',
'prefs-help-realname'           => 'Իրական անունը պարտադիր չէ, սակայն եթե դուք նշեք դա, ապա այն կօգտագործվի ձեր փոփոխությունների իրական անվանը վերագրման համար։',
'prefs-help-email'              => 'Էլեկտրոնային փոստի մուտքագրումը պարտադիր չէ, սակայն սա թույլ կտա մյուս մասնակիցներին կապնվել ձեզ հետ ձեր մասնակցի կամ մասնակցի քննարկման էջի միջոցով՝ առանց ձեր անձի կամ ձեր էլեկտրոնային հասցեի բացահայտման։',
'prefs-help-email-required'     => 'Էլ-փոստի հասցեն նշելը պարտադիր է։',
'prefs-info'                    => 'Հիմնական տեղեկություններ',
'prefs-i18n'                    => 'Միջազգայնացում',
'prefs-signature'               => 'Ստորագրություն',
'prefs-dateformat'              => 'Ամսաթվի ձևաչափ',
'prefs-timeoffset'              => 'Ժամային տարբերություն',
'prefs-advancedediting'         => 'Ընդլայնված ընրանքներ',
'prefs-advancedrc'              => 'Ընդլայնված ընրանքներ',
'prefs-advancedrendering'       => 'Ընդլայնված ընրանքներ',
'prefs-advancedsearchoptions'   => 'Ընդլայնված ընրանքներ',
'prefs-advancedwatchlist'       => 'Ընդլայնված ընրանքներ',
'prefs-displayrc'               => 'Ցուցադրման ընտրանքներ',
'prefs-diffs'                   => 'Տարբերություններ',

# User rights
'userrights'                => 'Մասնակիցների իրավունքների կառավարում',
'userrights-lookup-user'    => 'Մասնակիցների խմբերի կառավարում',
'userrights-user-editname'  => 'Մուտքագրեք մասնակցի անուն.',
'editusergroup'             => 'Խմբագրել մասնակիցների խմբերը',
'editinguser'               => '<b>$1</b> մասնակցի համար ([[User talk:$1|{{int:talkpagelinktext}}]] | [[Special:Contributions/$1|{{int:contribslink}}]])',
'userrights-editusergroup'  => 'Խմբագրել մասնակցի խմբերը',
'saveusergroups'            => 'Հիշել մասնակցի խմբերը',
'userrights-groupsmember'   => 'Անդամ է.',
'userrights-reason'         => 'Պատճառ.',
'userrights-changeable-col' => 'Խմբեր, որոնք դուք կարող եք ձևափոխել',

# Groups
'group'               => 'Խումբ.',
'group-user'          => 'Մասնակիցներ',
'group-autoconfirmed' => 'Ավտովավերացված մասնակիցներ',
'group-bot'           => 'Բոտեր',
'group-sysop'         => 'Ադմիններ',
'group-bureaucrat'    => 'Բյուրոկրատներ',
'group-suppress'      => 'Հսկիչներ',
'group-all'           => '(բոլոր)',

'group-user-member'          => 'մասնակից',
'group-autoconfirmed-member' => 'Ավտովավերացված մասնակից',
'group-bot-member'           => 'Բոտ',
'group-sysop-member'         => 'Ադմին',
'group-bureaucrat-member'    => 'Բյուրոկրատ',
'group-suppress-member'      => 'Հսկիչ',

'grouppage-user'          => '{{ns:project}}:Մասնակիցներ',
'grouppage-autoconfirmed' => '{{ns:project}}:Ավտովավերացված մասնակից',
'grouppage-bot'           => '{{ns:project}}:Բոտ',
'grouppage-sysop'         => '{{ns:project}}:Ադմինիստրատոր',
'grouppage-bureaucrat'    => '{{ns:project}}:Բյուրոկրատ',
'grouppage-suppress'      => '{{ns:project}}:Հսկիչ',

# Rights
'right-edit'   => 'էջերի խմբագրում',
'right-upload' => 'Նիշքերի բեռնում',
'right-delete' => 'Էջերի ջնջում',

# User rights log
'rightslog'      => 'Մասնակցի իրավունքների տեղեկամատյան',
'rightslogtext'  => 'Սա մասնակիցների իրավունքների փոփոխությունների տեղեկամատյանն է։',
'rightslogentry' => '$1 մասնակցի անդամակցությունը փոխվել է $2-ից $3',
'rightsnone'     => '(ոչ մի)',

# Associated actions - in the sentence "You do not have permission to X"
'action-edit' => 'խմբագրել այս էջը',

# Recent changes
'nchanges'                          => '$1 {{PLURAL:$1|փոփոխություն|փոփոխություն}}',
'recentchanges'                     => 'Վերջին փոփոխություններ',
'recentchanges-legend'              => 'Վերջին փոփոխությունների նախընտրություններ',
'recentchangestext'                 => 'Հետևեք վիքիում կատարված վերջին փոփոխություններին այս էջում։',
'recentchanges-feed-description'    => 'Հետևեք վիքիում կատարված վերջին փոփոխություններին այս սնուցման մեջ։',
'recentchanges-label-newpage'       => 'Այս խմբագրմամբ ստեղծվել է նոր էջ',
'recentchanges-label-minor'         => 'Սա չնչին խմբագրում է',
'recentchanges-label-bot'           => 'Այս խմբագրումը կատարվել է բոտի կողմից',
'recentchanges-label-unpatrolled'   => 'Այս խմբագրումը դեռ չի պարեկվել',
'rcnote'                            => 'Ստորև բերված են վերջին <strong>$1</strong> փոփոխությունները վերջին <strong>$2</strong> {{PLURAL:$2|օրվա|օրվա}} ընթացքում՝ $5, $4-ի դրությամբ։',
'rcnotefrom'                        => "Ստորև բերված են փոփոխությունները սկսած՝ '''$2''' (մինչև՝ '''$1''')։",
'rclistfrom'                        => 'Ցույց տալ նոր փոփոխությունները սկսած $1',
'rcshowhideminor'                   => '$1 չնչին խմբագրումները',
'rcshowhidebots'                    => '$1 բոտերին',
'rcshowhideliu'                     => '$1 մուտք գործած մասնակիցներին',
'rcshowhideanons'                   => '$1 անանուն մասնակիցներին',
'rcshowhidepatr'                    => '$1 ստուգված խմբագրումները',
'rcshowhidemine'                    => '$1 իմ խմբագրումները',
'rclinks'                           => 'Ցույց տալ վերջին $1 փոփոխությունները վերջին $2 օրվա ընթացքում<br />$3',
'diff'                              => 'տարբ',
'hist'                              => 'պատմ',
'hide'                              => 'Թաքցնել',
'show'                              => 'Ցույց տալ',
'minoreditletter'                   => 'չ',
'newpageletter'                     => 'Ն',
'boteditletter'                     => 'բ',
'number_of_watching_users_pageview' => '[$1 հսկող {{PLURAL:$1|մասնակից|մասնակիցներին}}]',
'rc_categories'                     => 'Սահմանափակել կատեգորիաներով (բաժանեք «|» նշանով)',
'rc_categories_any'                 => 'Բոլոր',
'newsectionsummary'                 => '/* $1 */ Նոր բաժին',
'rc-enhanced-expand'                => 'Ցույց տալ մանրամասներ (պահանջում է JavaScript)',
'rc-enhanced-hide'                  => 'Թաքցնել մանրամասները',

# Recent changes linked
'recentchangeslinked'          => 'Կապված փոփոխություններ',
'recentchangeslinked-feed'     => 'Կապված փոփոխություններ',
'recentchangeslinked-toolbox'  => 'Կապված փոփոխություններ',
'recentchangeslinked-title'    => '«$1» էջին կապված փոփոխությունները',
'recentchangeslinked-noresult' => 'Կապակցված էջերում նշված ժամանակաընթացքում փոփոխություններ չեն եղել։',
'recentchangeslinked-summary'  => "Այս սպասարկող էջում բերված են հղվող էջերում կատարված վերջին փոփոխությունները։ Ձեր հսկացանկի էջերը ներկայացված են '''թավատառ'''։",
'recentchangeslinked-page'     => 'Էջի անվանումը՝',
'recentchangeslinked-to'       => 'Հակառա՛կը. ցույց տալ այս էջին հղող էջերի փոփոխությունները։',

# Upload
'upload'                      => 'Բեռնել նիշք',
'uploadbtn'                   => 'Բեռնել նիշք',
'reuploaddesc'                => 'Վերադառնալ բեռնման ձևին։',
'uploadnologin'               => 'Դուք չեք մտել համակարգ',
'uploadnologintext'           => 'Նիշքեր բեռնելու համար անհրաժեշտ է [[Special:UserLogin|մտնել համակարգ]]։',
'upload_directory_read_only'  => 'Վեբ-սերվերը չունի գրելու իրավունք բեռնումների թղթապանակում ($1)։',
'uploaderror'                 => 'Բեռնման սխալ',
'uploadtext'                  => "Նիշք բեռնելու համար օգտագործեք ստորև բերված ձևը։
Նախկինում բեռնված նիշքերը դիտելու կամ որոնելու համար այցելեք [[Սպասարկող:Պատկերներիցանկը|բեռնված նիշքերի ցանկը]]։ Բեռնումները գրանցվում են [[Սպասարկող:Տեղեկամատյան/upload|բեռնման տեղեկամատյանում]], ջնջումները՝ [[Սպասարկող:Տեղեկամատյան/delete|ջնջման տեղեկամատյանում]]։

Այս նիշքը որևէ էջում ընդգրկելու համար օգտագործեք հետևյալ հղման ձևերը.
* '''<nowiki>[[</nowiki>{{ns:file}}<nowiki>:Նիշք.jpg]]</nowiki>''' - ամբողջական չափի պատկեր տեղադրելու համար,
* '''<nowiki>[[</nowiki>{{ns:file}}<nowiki>:Նիշք.png|200px|thumb|left|այլ. տեքստ]]</nowiki>''' - 200 փիքսել լայնությամբ տարբերակը ձախ կողմում շրջանակի մեջ և «այլ․ տեքստ» բացատրությամբ տեղադրելու համար
* '''<nowiki>[[</nowiki>{{ns:media}}<nowiki>:Նիշք.ogg]]</nowiki>''' - նիշքին ուղիղ հղման համար",
'uploadlog'                   => 'բեռնման տեղեկամատյան',
'uploadlogpage'               => 'Բեռնման տեղեկամատյան',
'uploadlogpagetext'           => 'Ստորև բերված է ամենավերջին բեռնված նիշքերի ցանկը։
Տե՛ս [[Սպասարկող:Նորպատկերներ|նոր նիշքերի սրահը]]՝ ավելի պատկերավոր դիտման համար։',
'filename'                    => 'Նիշքի անվանում',
'filedesc'                    => 'Ամփոփում',
'fileuploadsummary'           => 'Նկարագրություն՝',
'filestatus'                  => 'Հեղինակային իրավունքի կարգավիճակ.',
'filesource'                  => 'Աղբյուր՝',
'uploadedfiles'               => 'Բեռնված նիշքեր',
'ignorewarning'               => 'Անտեսել զգուշացումը և պահպանել նիշքը ամեն դեպքում։',
'ignorewarnings'              => 'Անտեսել բոլոր նախազգուշացումները',
'minlength1'                  => 'Նիշքի անվանումը պետք է պարունակի առնվազն մեկ տառ',
'illegalfilename'             => '«$1» նիշքի անվանումը պարունակում է սիմվոլներ, որոնք անթույլատրելի են էջերի անվանումներում։ Խնդրում ենք վերանվանել նիշքը և այն կրկին բեռնել։',
'badfilename'                 => 'Պատկերի վերանվանվել է «$1» անվանման։',
'filetype-badmime'            => '«$1» MIME-տեսակով նիշքերի բեռնումը արգելված է։',
'filetype-missing'            => 'Նիշքը չունի ընդլայնում (օրինակ՝ «.jpg»)։',
'large-file'                  => 'Խորհուրդ է տրվում չօգտագործել $1 բայթից մեծ նիշքեր. այս նիշքի չափն է՝  $2 բայթ։',
'largefileserver'             => 'Այս նիշքը սպասարկիչի թույլատրած առավելագույն չափից մեծ է։',
'emptyfile'                   => 'Ձեր բեռնած նիշքը ըստ երևույթին դատարկ է։ Հնարավոր է սա նիշքի անվանման մեջ տառասխալի հետևանք է։ Խնդրում ենք ստուգել, թե արդյոք իսկապես ուզում եք բեռնել այս նիշքը։',
'fileexists'                  => "Այսպիսի անվանմամբ նիշք արդեն գոյություն ունի։ Խնդրում ենք ստուգել '''<tt>[[:$1]]</tt>''', եթե դուք համոզված չեք, որ ուզում եք այն փոխարինել։
[[$1|thumb]]",
'fileexists-extension'        => "Գոյություն ունի համանման անվանմամբ նիշք՝ [[$2|thumb]]
* Բեռնված նիշքի անվանում՝ '''<tt>[[:$1]]</tt>'''
* Գոյություն ունեցող նիշքի անվանում՝ '''<tt>[[:$2]]</tt>'''
Խնդրում ենք ընտրել մեկ այլ անվանում։",
'fileexists-thumbnail-yes'    => "Նիշքը ըստ երևույթին փոքրացված պատճեն է ''(պատկերիկ)''։ [[$1|thumb]]
Խնդրում ենք ստուգել '''<tt>[[:$1]]</tt>''' նիշքը։
Եթե նշված նիշքը նույն պատկերն է բնօրինակ չափով, ապա հարկովոր չէ բեռնել նրա փոքրացված պատճենը։",
'file-thumbnail-no'           => "Նիշքի անվանման սկիզբն է՝ '''<tt>$1</tt>'''։ 
Հավանաբար սա փոքրացված պատճեն է ''(պատկերիկ)''։ 
Եթե դուք այս պատկերը ամբողջական լուծաչափով ունեք, ապա խնդրում ենք բեռնել այն, հակառակ դեպքում՝ խնդրում ենք փոխել նիշքի անվանումը։",
'fileexists-forbidden'        => 'Այսպիսի անվանմամբ նիշք արդեն գոյություն ունի։ Խնդրում ենք հետ վերադառնալ և բեռնել նիշքը նոր անվանմամբ։ [[File:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => 'Այսպիսի անվանմամբ նիշք արդեն գոյություն ունի նիշքերի ընդհանուր զետեղարանում։ Խնդրում ենք հետ վերադառնալ և բեռնել նիշքը նոր անվանմամբ։ [[File:$1|thumb|center|$1]]',
'uploadwarning'               => 'Զգուշացում',
'savefile'                    => 'Հիշել նիշքը',
'uploadedimage'               => 'բեռնվեց «[[$1]]»',
'overwroteimage'              => 'բեռնվեց «[[$1]]» նիշքի նոր տարբերակ',
'uploaddisabled'              => 'Բեռնումները արգելված են',
'uploaddisabledtext'          => 'Նիշքերի բեռնումը արգելափակված է։',
'uploadscripted'              => 'Այս նիշքը պարունակում է HTML-կոդ կամ գրվածք (սկրիպտ), որը կարող է սխալ մեկնաբանվել զննարկիչի կողմից։',
'uploadvirus'                 => 'Նիշքը պարունակում է վիրո՜ւս։ Տես $1',
'sourcefilename'              => 'Սկզբնական նիշք՝',
'destfilename'                => 'Նիշքի նոր անվանում՝',
'watchthisupload'             => 'Հսկել այս նիշքը',
'filewasdeleted'              => 'Այս անվանմամբ նիշք նախկինում բեռնվել է և հետագայում ջնջվել։ Այն կրկին բեռնելուց առաջ խնդրում ենք ստուգել $1։',
'upload-wasdeleted'           => "'''Զգուշացում. Դուք փորձում եք բեռնել նախկինում ջնջված նիշք։'''

Խնդրում ենք վերանայել նիշքի բեռնման նպատակահարմարությունը։
Այս նիշքի ջնջման տեղեկամատյանը բերված է ստորև.",
'filename-bad-prefix'         => "Բեռնվող նիշքի անվանումը սկսվում է '''<tt>«$1»</tt>''' արտահայտությամբ, որը ոչ-նկարագրական է և սովորաբար տրվում է թվային լուսանկարչական ապարատների կողմից։ Խնդրում ենք ընտրել ավելի նկարագրական անվանում ձեր նիշքի համար։",
'upload-success-subj'         => 'Բեռնումը կատարված է',

'upload-proto-error'      => 'Սխալ պրոտոկոլ',
'upload-proto-error-text' => 'Հեռավոր բեռնումը պահանջում է URL-հասցե, որը սկսվում է <code>http://</code> կամ <code>ftp://</code> նախածանցով։',
'upload-file-error'       => 'Ներքին սխալ',
'upload-file-error-text'  => 'Տեղի ունեցավ ներքին սխալ՝ սերվերի վրա ժամանակավոր նիշք ստեղծելիս։ Խնդրում ենք կապնվել համակարգային [[Special:ListUsers/sysop|ադմինիստրատորի]] հետ։',
'upload-misc-error'       => 'Բեռնման անհայտ սխալ',
'upload-misc-error-text'  => 'Տեղի ունեցավ անհայտ սխալ բեռնման ընթացքում։ Խնդրում ենք ստուգել URL-հասցեի ճշտությունն ու հասանելիությունը և փորձել կրկին։ Սխալի կրկնման դեպքում կապնվեք համակարգային ադմինիստրատորի հետ։',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6'       => 'URL-հասցեն անհասանելի է',
'upload-curl-error6-text'  => 'Նշված URL-հասցեն անհասանելի է։ Խնդրում ենք ստուգել հասցեի ճշգրտությունը և կայքի գործունությունը։',
'upload-curl-error28'      => 'Բեռնման ժամհատնում',
'upload-curl-error28-text' => 'Կայքի պատասխանը ձգձգվում է։ Խնդրում ենք ստուգել կայքի գործունությունը, սպասել մի որոշ ժամանակ և փորձել կրկին։ Արժե գործողությունը փորձել կայքի քիչ բեռնվածության ժամանակ։',

'license'            => 'Արտոնագրում․',
'license-header'     => 'Արտոնագրում',
'nolicense'          => 'Ընտրված չէ',
'license-nopreview'  => '(Նախադիտումը մատչելի չէ)',
'upload_source_url'  => ' (գործուն, հանրամատչելի URL-հասցե)',
'upload_source_file' => ' (նիշք ձեր համակարգչի վրա)',

# Special:ListFiles
'listfiles_search_for'  => 'Որոնել պատկերի անվանմամբ.',
'imgfile'               => 'նիշք',
'listfiles'             => 'Նիշքերի ցանկ',
'listfiles_date'        => 'Օր/Ժամ',
'listfiles_name'        => 'Անվանում',
'listfiles_user'        => 'Մասնակից',
'listfiles_size'        => 'Չափ',
'listfiles_description' => 'Նկարագրություն',
'listfiles_count'       => 'Տարբերակ',

# File description page
'file-anchor-link'          => 'Նիշք',
'filehist'                  => 'Նիշքի պատմություն',
'filehist-help'             => 'Մատնահարեք օրվան/ժամին՝ նիշքի այդ պահին տեսքը դիտելու համար։',
'filehist-deleteall'        => 'ջնջել բոլորը',
'filehist-deleteone'        => 'ջնջել',
'filehist-revert'           => 'հետ շրջել',
'filehist-current'          => 'ընթացիկ',
'filehist-datetime'         => 'Օր/Ժամ',
'filehist-thumb'            => 'Մանրապատկեր',
'filehist-thumbtext'        => '$1 տարբերակի մանրապատկերը',
'filehist-nothumb'          => 'Մանրապատկեր չկա',
'filehist-user'             => 'Մասնակից',
'filehist-dimensions'       => 'Օբյեկտի չափը',
'filehist-filesize'         => 'Նիշքի չափ',
'filehist-comment'          => 'Մեկնաբանություն',
'imagelinks'                => 'Հղումներ նիշքին',
'linkstoimage'              => 'Հետևյալ {{PLURAL:$1|էջը հղվում է|$1 էջերը հղվում են}} այս նիշքին՝',
'nolinkstoimage'            => 'Այս նիշքին հղվող էջեր չկան։',
'sharedupload'              => 'Այս նիշքը $1 զետեղարանից է և կարող է օգտագործվել այլ նախագծերում։',
'sharedupload-desc-here'    => 'Այս նիշքը $1-ից է և թուլատրելի է այլ նախագծերի կողմից օգտագործվել։ [$2 Նիշքի նկարագրման էջի] նկարագրությունը ներկայացված է ներքո։',
'uploadnewversion-linktext' => 'Բեռնել այս նիշքի նոր տարբերակ',

# File reversion
'filerevert'                => 'Հետ շրջել $1',
'filerevert-legend'         => 'Հետ շրջել նիշք',
'filerevert-intro'          => "Դուք հետ եք շրջում '''[[Media:$1|$1]]''' նիշքը [$4 տարբերակի՝ $3, $2 պահով]։",
'filerevert-comment'        => 'Մեկնաբանություն.',
'filerevert-defaultcomment' => 'Հետ է շրջվում հին տարբերակին՝ $2, $1 պահով',
'filerevert-submit'         => 'Հետ շրջել',
'filerevert-success'        => "'''[[Media:$1|$1]]''' նիշքը հետ է շրջվել [$4 տարբերակին՝ $3, $2 պահով]։",
'filerevert-badversion'     => 'Այս նիշքի նախորդ տեղական տարբերակ նշված ժամդրոշմով չկա։',

# File deletion
'filedelete'                  => 'Ջնջում $1',
'filedelete-legend'           => 'Ջնջել նիշքը',
'filedelete-intro'            => "Դուք պատրաստվում եք ջնջել '''[[Media:$1|$1]]''' նիշքը իր ամբողջ պատմությամբ։",
'filedelete-intro-old'        => "Դուք ջնջում եք '''[[Media:$1|$1]]''' նիշքի [$4 $3, $2 պահով] տարբերակը։",
'filedelete-comment'          => 'Պատճառ.',
'filedelete-submit'           => 'Ջնջել',
'filedelete-success'          => "'''$1''' նիշքը ջնջված է։",
'filedelete-success-old'      => "'''[[Media:$1|$1]]''' նիշքի $3, $2 պահով տարբերակը ջնջված է։",
'filedelete-nofile'           => "'''$1''' գոյություն չունի։",
'filedelete-nofile-old'       => "'''$1''' նիշքի նշված հատկանիշներով արխիվային տարբերակ չկա։",
'filedelete-otherreason'      => 'Այլ/հավելյալ պատճառ․',
'filedelete-reason-otherlist' => 'Ուրիշ պատճառ',
'filedelete-edit-reasonlist'  => 'Խմբագրել ջնջման պատճառների ցանկը',

# MIME search
'mimesearch'         => 'Որոնել MIME-տեսակով',
'mimesearch-summary' => 'Այս էջը հնարավորություն է տալիս զտել նիշքերը իրենց MIME-տեսակով։ Գրելաձև. օբյեկտի_տեսակ/ենթատեսակ, օրինակ՝ <tt>image/jpeg</tt>։',
'mimetype'           => 'MIME-տեսակ.',
'download'           => 'Ներբեռնել',

# Unwatched pages
'unwatchedpages' => 'Չհսկվող էջեր',

# List redirects
'listredirects' => 'Վերահղումների ցանկ',

# Unused templates
'unusedtemplates'     => 'Չօգտագործվող կաղապարներ',
'unusedtemplatestext' => 'Այս էջում բերված են կաղապարների անվանատարածքի բոլոր էջերը, որոնք ընդգրկված չեն այլ էջերում։ Ջնջելուց առաջ չմոռանաք ստուգել այլ հղումները կաղապարներին։',
'unusedtemplateswlh'  => 'այլ հղումներ',

# Random page
'randompage'         => 'Պատահական էջ',
'randompage-nopages' => 'Այս անվանատարածքում էջեր չկան։',

# Random redirect
'randomredirect'         => 'Պատահական վերահղում',
'randomredirect-nopages' => 'Այս անվանատարածքում վերահղումներ չկան։',

# Statistics
'statistics'                   => 'Վիճակագրություն',
'statistics-header-pages'      => 'Էջերի վիճակագրություն',
'statistics-header-edits'      => 'Խմբագրումների վիճակագրություն',
'statistics-header-views'      => 'Դիտումների վիճակագրություն',
'statistics-header-users'      => 'Մասնակիցների վիճակագրություն',
'statistics-header-hooks'      => 'Այլ վիճակագրություն',
'statistics-articles'          => 'Հոդվածներ',
'statistics-pages'             => 'Էջեր',
'statistics-pages-desc'        => 'Վիքիի բոլոր էջերը՝ ներառյալ քննարկման էջերը, վերահղումները և այլն',
'statistics-files'             => 'Բեռնված նիշքեր',
'statistics-edits'             => 'Խմբագրումները {{SITENAME}} կայքի տեղադրումից ի վեր',
'statistics-edits-average'     => 'Էջի խմբագրումների միջին թիվը',
'statistics-views-total'       => 'Ընդհանուր դիտումներ',
'statistics-views-peredit'     => 'Դիտումներ ամեն մի խմբագրման համար',
'statistics-users'             => 'Գրանցված [[Special:ListUsers|մասնակիցներ]]',
'statistics-users-active'      => 'Ակտիվ մասնակիցներ',
'statistics-users-active-desc' => 'Մասնակիցներ, որոնք որևէ գործողություն են կատարել վերջին {{PLURAL:$1|օրվա|$1 օրվա}} ընթացքում',
'statistics-mostpopular'       => 'Ամենահաճախ դիտվող էջեր',

'disambiguations'      => 'Երկիմաստության փարատման էջեր',
'disambiguationspage'  => 'Template:Երկիմաստ',
'disambiguations-text' => 'Հետևյալ էջերը հղում են երկիմաստության փարատման էջերին։
Փոխարենը նրանք, հավանաբար, պետք է հղեն համապատասխան թեմային։<br />
Էջը համարվում է երկիմաստության փարատման էջ, եթե այն պարունակում է [[MediaWiki:Disambiguationspage]] էջում ընդգրկված կաղապարներից որևէ մեկը։',

'doubleredirects'            => 'Կրկնակի վերահղումներ',
'doubleredirectstext'        => 'Այս էջում բերված են վերահղման էջերին վերահղող էջերը։ Յուրաքանչյուր տող պարունակում է հղումներ դեպի առաջին և երկրորդ վերահղումները, ինչպես նաև երկրորդ վերահղման նպատակային էջի առաջին տողը, որում սովորաբար նշված է էջի անվանումը, որին պետք է հղի նաև առաջին վերահղումը։',
'double-redirect-fixed-move' => '«[[$1]]» էջը վերանվանված է և այժմ վերահղում է «[[$2]]» էջին։',
'double-redirect-fixer'      => 'Վերահղումների շտկիչ',

'brokenredirects'        => 'Կոտրված վերահղումներ',
'brokenredirectstext'    => 'Հետևյալ վերահղումները տանում են գոյություն չունեցող էջերի.',
'brokenredirects-edit'   => 'խմբագրել',
'brokenredirects-delete' => 'ջնջել',

'withoutinterwiki'         => 'Լեզվային հղումներ չպարունակող էջեր',
'withoutinterwiki-summary' => 'Հետևյալ էջերը չունեն լեզվական հղումներ.',
'withoutinterwiki-legend'  => 'Նախածանց',
'withoutinterwiki-submit'  => 'Ցուցադրել',

'fewestrevisions' => 'Ամենաքիչ վերափոխումներով հոդվածներ',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|բայթ|բայթ}}',
'ncategories'             => '$1 {{PLURAL:$1|կատեգորիա|կատեգորիաներ}}',
'nlinks'                  => '$1 {{PLURAL:$1|հղում|հղումներ}}',
'nmembers'                => '$1 {{PLURAL:$1|անդամ|անդամ}}',
'nrevisions'              => '$1 {{PLURAL:$1|տարբերակ|տարբերակներ}}',
'nviews'                  => '$1 {{PLURAL:$1|դիտում|դիտումներ}}',
'specialpage-empty'       => 'Հայցումը արդյունքներ չվերադարձրեց։',
'lonelypages'             => 'Որբ էջեր',
'lonelypagestext'         => 'Հետևյալ էջերին չկան հղումներ այս վիքիի այլ էջերից։',
'uncategorizedpages'      => 'Չդասակարգված էջեր',
'uncategorizedcategories' => 'Չդասակարգված կատեգորիաներ',
'uncategorizedimages'     => 'Չդասակարգված պատկերներ',
'uncategorizedtemplates'  => 'Չդասակարգված կաղապարներ',
'unusedcategories'        => 'Չօգտագործվող կատեգորիաներ',
'unusedimages'            => 'Չօգտագործվող նիշքեր',
'popularpages'            => 'Հաճախ այցելվող էջեր',
'wantedcategories'        => 'Անհրաժեշտ կատեգորիաներ',
'wantedpages'             => 'Անհրաժեշտ էջեր',
'wantedfiles'             => 'Անհրաժեշտ նիշքեր',
'wantedtemplates'         => 'Անհրաժեշտ կաղապարներ',
'mostlinked'              => 'Էջեր, որոնց շատ են հղվում',
'mostlinkedcategories'    => 'Կատեգորիաներ, որոնց շատ են հղվում',
'mostlinkedtemplates'     => 'Կաղապարներ, որոնց շատ են հղվում',
'mostcategories'          => 'Ամենաշատ կատեգորիաներով էջեր',
'mostimages'              => 'Ամենաշատ օգտագործվող նկարներ',
'mostrevisions'           => 'Ամենաշատ վերափոխումներով հոդվածներ',
'prefixindex'             => 'Բոլոր էջերը ըստ սկզբնատառի',
'shortpages'              => 'Կարճ էջեր',
'longpages'               => 'Երկար էջեր',
'deadendpages'            => 'Հղումներ չպարունակող էջեր',
'deadendpagestext'        => 'Հետևյալ էջերը չունեն հղումներ վիքիի այլ էջերին։',
'protectedpages'          => 'Պաշտպանված էջեր',
'protectedpagestext'      => 'Հետևյալ էջերը պաշտպանված են վերանվանումից կամ տեղափոխումից։',
'protectedpagesempty'     => 'Ներկայումս չկան պաշտպանված էջեր նշված պարամետրերով։',
'protectedtitles'         => 'Պաշտպանված անվանումներ',
'listusers'               => 'Մասնակիցների ցանկ',
'newpages'                => 'Նոր էջեր',
'newpages-username'       => 'Մասնակից՝',
'ancientpages'            => 'Ամենահին էջերը',
'move'                    => 'Տեղափոխել',
'movethispage'            => 'Տեղափոխել այս էջը',
'unusedimagestext'        => 'Խնդրում ենք ի նկատի ունեցեք, որ այլ կայքեր կարող են անմիջապես հղել որևէ պատկերի ուղիղ URL-հղմամբ, և ուստի պատկերը կարող է ակտիվ օգտագործվել՝ չնայած այս ցանկում ընդգրկվելուն։',
'unusedcategoriestext'    => 'Հետևյալ կատեգորիաաների էջերը գոյություն ունեն, սակայն չեն պարունակում ոչ մի էջ կամ ենթակատեգորիա։',
'notargettitle'           => 'Նպատակը նշված չէ',
'notargettext'            => 'Դուք չեք նշել նպատակային էջ կամ մասնակից այս ֆունկցիայի գործածման համար։',
'pager-newer-n'           => '{{PLURAL:$1|ավելի թարմ 1|ավելի թարմ $1}}',
'pager-older-n'           => '{{PLURAL:$1|ավելի հին 1|ավելի հին $1}}',

# Book sources
'booksources'               => 'Գրքային աղբյուրներ',
'booksources-search-legend' => 'Գրքի մասին տեղեկությունների որոնում',
'booksources-go'            => 'Անցնել',
'booksources-text'          => 'Ստորև բերված են հղումներ դեպի արտաքին կայքեր, որտեղ կգտնեք հավելյալ տեղեկություններ գրքի մասին։ Սրանց մեջ ընդգրկված են ցանցային գրախանութներ և ընդհանուր գրադարանային կատալոգներ։',
'booksources-invalid-isbn'  => 'Նշված ISBN համարը ըստ երևույթի սխալ է պարունակում։ Համոզվեք որ համարը մուտքագրելիս, սխալ չի պատահել։',

# Special:Log
'specialloguserlabel'  => 'Մասնակից.',
'speciallogtitlelabel' => 'Անվանում.',
'log'                  => 'Տեղեկամատյաններ',
'all-logs-page'        => 'Բոլոր տեղեկամատյանները',
'alllogstext'          => '{{SITENAME}} կայքի տեղեկամատյանների համընդհանուր ցանկ։
Դուք կարող եք սահմանափակել արդյունքները ըստ տեղեկամատյանի տեսակի, մասնակցի անունի կամ համապատասխան էջի։',
'logempty'             => 'Տեղեկամատյանում չկան համընկնող տարրեր։',
'log-title-wildcard'   => 'Որոնել այս տեքստով սկսվող անվանումներ',

# Special:AllPages
'allpages'          => 'Բոլոր էջերը',
'alphaindexline'    => '$1 -ից՝ $2',
'nextpage'          => 'Հաջորդ էջը ($1)',
'prevpage'          => 'Նախորդ էջը ($1)',
'allpagesfrom'      => 'Ցույց տալ էջերը, որոնք սկսվում են՝',
'allpagesto'        => 'Ցույց տալ էջերը, որոնք ավարտվում են՝',
'allarticles'       => 'Բոլոր հոդվածները',
'allinnamespace'    => 'Բոլոր էջերը ($1 անվանատարածք)',
'allnotinnamespace' => 'Բոլոր էջերը (ոչ $1 անվանատարածքում)',
'allpagesprev'      => 'Նախորդ',
'allpagesnext'      => 'Հաջորդ',
'allpagessubmit'    => 'Անցնել',
'allpagesprefix'    => 'Ցույց տալ հետևյալ նախածանցով էջերը՝',
'allpagesbadtitle'  => 'Տվյալ էջի անվանումը անթույլատրելի է։ Այն պարունակում է միջ-լեզվական կամ ինտերվիքի նախածանց, կամ էլ անվանումներում այնթույլատրելի սիմվոլներ։',
'allpages-bad-ns'   => '{{SITENAME}} կայքը չունի «$1» անվանատարածք։',

# Special:Categories
'categories'         => 'Կատեգորիաներ',
'categoriespagetext' => 'Հետևյալ կատեգորիաները պարունակում են էջեր կամ մեդիա.
[[Special:UnusedCategories|Unused categories]] are not shown here.
Also see [[Special:WantedCategories|wanted categories]].',

# Special:DeletedContributions
'deletedcontributions'             => 'Մասնակցի ջնջված ներդրում',
'deletedcontributions-title'       => 'Մասնակցի ջնջված ներդրում',
'sp-deletedcontributions-contribs' => 'ներդրում',

# Special:LinkSearch
'linksearch'    => 'Արտաքին հղումներ',
'linksearch-ok' => 'Որոնել',

# Special:ListUsers
'listusersfrom'      => 'Ցուցադրել մասնակիցներին՝ սկսած.',
'listusers-submit'   => 'Ցուցադրել',
'listusers-noresult' => 'Այդպիսի մասնակիցներ չգտնվեցին։',
'listusers-blocked'  => '(արգելափակված)',

# Special:ActiveUsers
'activeusers' => 'Ակտիվ մասնակիցների ցանկ',

# Special:Log/newusers
'newuserlogpage'              => 'Մասնակիցների գրանցման տեղեկամատյան',
'newuserlogpagetext'          => 'Սա նոր մասնակիցների գրանցման տեղեկամատյանն է.',
'newuserlog-create-entry'     => 'Նոր մասնակից',
'newuserlog-create2-entry'    => 'ստեղծեց նոր մասնակցային հաշիվ $1',
'newuserlog-autocreate-entry' => 'Ավտոմատիկ հաշվի ստեղծում',

# Special:ListGroupRights
'listgrouprights-members' => '(անդամների ցանկ)',

# E-mail user
'mailnologin'     => 'Ուղարկման հասցե չկա',
'mailnologintext' => 'Անհրաժեշտ է [[Special:UserLogin|մտնել համակարգ]] և ունենալ գործող էլ-փոստի հասցե ձեր [[Special:Preferences|նախընտրություններում]]՝ ուրիշ մասնակիցներին էլեկտրոնային նամակներ ուղարկելու համար։',
'emailuser'       => 'էլ-նամակ ուղարկել այս մասնակցին',
'emailpage'       => 'Էլ-նամակ ուղարկել մասնակցին',
'emailpagetext'   => 'Եթե այս մասնակիցը նշել է գործող էլ-փոստի հասցե իր նախընտրություններում, ապա ստորև բերված ձևով հնարավոր է ուղարկել նրան էլ-նամակ։
Այն էլ-հասցեն, որը դուք նշել եք ձեր նախընտրություններում, կերևա «Ումից» դաշտում, ուստի ստացողը հնարավորություն կունենա պատասխանել։',
'usermailererror' => 'Նամակն ուղարկելիս սխալ է վերադարձվել.',
'defemailsubject' => '{{SITENAME}} e-mail',
'noemailtitle'    => 'Չկա էլ-փոստի հասցե',
'noemailtext'     => 'Այս մասնակիցը չի նշել էլ-փոստի հասցե կամ նախընտրել է չստանալ էլ-նամակներ այլ մասնակիցներից։',
'emailfrom'       => 'Ումից.',
'emailto'         => 'Ում.',
'emailsubject'    => 'Թեմա.',
'emailmessage'    => 'Ուղերձ.',
'emailsend'       => 'Ուղարկել',
'emailccme'       => 'Ուղարկել ինձ իմ նամակի պատճեն։',
'emailccsubject'  => 'Ձեր՝ $1 մասնակցին նամակի պատճեն. $2',
'emailsent'       => 'Էլ-նամակը ուղարկված է',
'emailsenttext'   => 'Ձեր էլ-ուղերձն ուղարկված է։',

# Watchlist
'watchlist'            => 'Իմ հսկողության ցանկը',
'mywatchlist'          => 'Իմ հսկացանկը',
'nowatchlist'          => 'Ձեր հսկողության ցանկը դատարկ է։',
'watchlistanontext'    => 'Անհրաժեշտ է $1՝ հսկացանկը դիտելու կամ խմբագրելու համար։',
'watchnologin'         => 'Չեք մտել համակարգ',
'watchnologintext'     => 'Անհրաժեշտ է [[Special:UserLogin|մտնել համակարգ]]՝ հսկացանկը փոփոխելու համար։',
'addedwatch'           => 'Ավելացված է հսկացանկին',
'addedwatchtext'       => '«[[:$1]]» էջը ավելացված է ձեր [[Special:Watchlist|հսկացանկին]]։ Այս էջի և նրան կապված քննարկումների էջի հետագա փոփոխությունները կգրանցվեն այդտեղ, և կցուցադրվեն թավատառ [[Special:RecentChanges|վերջին փոփոխությունների]] ցանկում։

Հետագայում հսկացանկից էջը հեռացնելու ցանկության դեպքում մատնահարեք էջի վերնամասի ընտրացանկում գտնվող «հանել հսկումից» կոճակին։',
'removedwatch'         => 'Հանված է հսկման ցանկից',
'removedwatchtext'     => '«[[:$1]]» էջը հանված է [[Special:Watchlist|ձեր հսկացանկից]]։',
'watch'                => 'Հսկել',
'watchthispage'        => 'Հսկել այս էջը',
'unwatch'              => 'Հանել հսկումից',
'unwatchthispage'      => 'Հանել հսկումից',
'notanarticle'         => 'Հոդված չէ',
'watchnochange'        => 'Ոչ մի հսկվող էջ չի փոփոխվել ցուցադրվող ժամանակահատվածում։',
'watchlist-details'    => 'Ձեր հսկացանկում կա {{PLURAL:$1|$1 էջ|$1 էջ}}` քննարկման էջերը չհաշված։',
'wlheader-enotif'      => '* Էլ-փոստով տեղեկացումը միացված է։',
'wlheader-showupdated' => "* Էջերը, որոնք փոփոխվել են ձեր դրանց վերջին այցից հետո բերված են '''թավատառ'''։",
'watchmethod-recent'   => 'վերջին փոփոխությունները հսկվող էջերի համար',
'watchmethod-list'     => 'հսկվող էջերի վերջին փոփոխությունները',
'watchlistcontains'    => 'Ձեր հսկացանկում կա $1 {{PLURAL:$1|էջ|էջ}}։',
'iteminvalidname'      => 'Խնդիր «$1» տարրի հետ, անթույլատրելի անվանում...',
'wlnote'               => "Ստորև բերված {{PLURAL:$1|է վերջին փոփոխությունը|են վերջին '''$1''' փոփոխությունները}} վերջին <strong>$2</strong> ժամվա ընթացքում։",
'wlshowlast'           => 'Ցուցադրել վերջին $1 ժամերը $2 օրերը $3',
'watchlist-options'    => 'Հսկացանկի նախընտրություններ',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'Հսկվում է...',
'unwatching' => 'Հանվում է հսկումից...',

'enotif_mailer'                => '{{grammar:genitive|{{SITENAME}}}} Տեղեկացման ծառայություն',
'enotif_reset'                 => 'Նշել բոլոր էջերը այցելված',
'enotif_newpagetext'           => 'Սա նոր էջ է։',
'enotif_impersonal_salutation' => '{{grammar:genitive|{{SITENAME}}}} մասնակից',
'changed'                      => 'փոփոխված է',
'created'                      => 'ստեղծված է',
'enotif_subject'               => '{{grammar:genitive|{{SITENAME}}}} «$PAGETITLE» էջը $CHANGEDORCREATED $PAGEEDITOR մասնակցի կողմից',
'enotif_lastvisited'           => 'Տես $1՝ ձեր վերջին այցից ի վեր կատարված փոփոխությունների համար։',
'enotif_lastdiff'              => 'Տես $1՝ այս փոփոխությունը դիտելու համար։',
'enotif_anon_editor'           => 'անանուն մասնակից $1',
'enotif_body'                  => 'Հարգելի $WATCHINGUSERNAME,

$PAGEEDITDATE {{grammar:genitive|{{SITENAME}}}} «$PAGETITLE» էջը $CHANGEDORCREATED $PAGEEDITOR մասնակցի կողմից, տես $PAGETITLE_URL ՝ ընթացիկ տարբերակի համար։

$NEWPAGE

Խմբագրման ամփոփում. $PAGESUMMARY $PAGEMINOREDIT

Կապվել խմբագրողի հետ.
էլ-փոստ՝ $PAGEEDITOR_EMAIL
վիքի՝ $PAGEEDITOR_WIKI

Հետագա տեղեկացումներ այս էջի փոփոխումների մասին չեն լինի, մինչև որ չայցելեք այն։ Դուք կարող եք նաև փոփոխել տեղեկացման դրոշները ձեր կողմից հսկվող բոլոր էջերի համար ձեր հսկացանկում։

             {{grammar:genitive|{{SITENAME}}}} տեղեկացման ծառայություն

--
Ձեր հսկացանկի նախընտրությունները փոխելու համար այցելեք՝
{{fullurl:{{#special:EditWatchlist}}}}

Հետադարձ կապ և օգնություն՝
{{fullurl:{{MediaWiki:Helppage}}}}',

# Delete
'deletepage'            => 'Ջնջել էջը',
'confirm'               => 'Հաստատել',
'excontent'             => 'բովանդակությունը սա էր՝ «$1»',
'excontentauthor'       => 'Պարունակությունն էր. «$1» (և միակ հեղինակն էր՝ [[Special:Contributions/$2|$2]])',
'exbeforeblank'         => 'պարունակությունը մինչև մաքրումը. «$1»',
'exblank'               => 'էջը դատարկ էր',
'delete-confirm'        => '$1 ― ջնջում',
'delete-legend'         => 'Ջնջում',
'historywarning'        => 'Զգուշացում. էջը, որը դուք պատրաստվում եք ջնջել ունի փոփոխությունների պատմություն։',
'confirmdeletetext'     => 'Դուք պատրաստվում եք ընդմիշտ ջնջել էջը կամ պատկերը տվյալների բազայից իր փոփոխությունների պատմությամբ հանդերձ։ Խնդրում ենք հաստատել, որ դուք իրոք մտադրված եք դա անել, հասկանում եք դրա հետևանքները և գործում եք [[{{MediaWiki:Policy-url}}|կանոնադրության]] սահմաններում։',
'actioncomplete'        => 'Գործողությունը ավարտված  է',
'deletedtext'           => '«<nowiki>$1</nowiki>» էջը ջնջված է։
Տես $2՝ վերջին ջնջումների պատմության համար։',
'deletedarticle'        => 'ջնջված է «[[$1]]»',
'dellogpage'            => 'Ջնջման տեղեկամատյան',
'dellogpagetext'        => 'Ստորև բերված է ամենավերջին ջնջումների ցանկը։',
'deletionlog'           => 'ջնջման տեղեկամատյան',
'reverted'              => 'Հետ է շրջվել նախորդ տարբերակի',
'deletecomment'         => 'Պատճառ.',
'deleteotherreason'     => 'Լրացուցիչ պատճառ',
'deletereasonotherlist' => 'Ուրիշ պատճառ',

# Rollback
'rollback'         => 'Հետ գլորել խմբագրումները',
'rollback_short'   => 'Հետ գլորել',
'rollbacklink'     => 'հետ գլորել',
'rollbackfailed'   => 'Հետ գլորումը ձախողվեց',
'cantrollback'     => 'Չհաջողվեց հետ շրջել խմբագրումը։ Վերջին ներդրումը կատարվել է էջի միակ հեղինակի կողմից։',
'alreadyrolled'    => 'Չհաջողվեց հետ գլորել [[:$1]] էջի վերջին խմբագրումները՝ կատարված [[User:$2|$2]] ([[User talk:$2|Քննարկում]]) մասնակցի կողմից։ Մեկ ուրիշը արդեն խմբագրել է կամ հետ է գլորել էջը։

Վերջին խմբագրումը կատարվել է [[User:$3|$3]] ([[User talk:$3|Քննարկում]]) մասնակցի կողմից։',
'editcomment'      => "Խմբագրման մեկնաբանումն էր. «''$1''»։",
'revertpage'       => '[[Special:Contributions/$2|$2]] ([[User talk:$2|քննարկում]]) մասնակցի խմբագրումները հետ են շրջվել [[User:$1|$1]] մասնակցի վերջին տարբերակին։',
'rollback-success' => 'Հետ են շրջվել $1 մասնակցի խմբագրումները. վերադարձվել է $2 մասնակցի վերջին տարբերակին։',

# Edit tokens
'sessionfailure' => 'Կարծես խնդիր է առաջացել կապված ձեր ընթացիկ աշխատանքային սեսիայի հետ.
այս գործողությունը բեկանվել է սեսիայի հափշտակման կանխման նպատակով։
Խնդրում ենք սեղմել «back» կոճակը և վերբեռնել այն էջը որտեղից եկել եք ու փորձել կրկին։',

# Protect
'protectlogpage'              => 'Պաշտպանման տեղեկամատյան',
'protectlogtext'              => 'Ստորև բերված է պաշտպանված և պաշտպանումից հանված էջերի ցանկը։ Տես նաև [[Special:ProtectedPages|ներկայումս պաշտպանված էջերի ցանկը]]։',
'protectedarticle'            => 'պաշտպանվեց «[[$1]]» էջը',
'modifiedarticleprotection'   => 'փոխվեց պաշտպանման մակարդակը «[[$1]]» էջի համար',
'unprotectedarticle'          => 'պաշտպանումը հանված է «[[$1]]» էջից',
'protect-title'               => '«$1» էջի պաշտպանման մակարդակի հաստատում',
'prot_1movedto2'              => '«[[$1]]» վերանվանված է «[[$2]]»',
'protect-legend'              => 'Հաստատել պաշտպանումը',
'protectcomment'              => 'Պատճառ.',
'protectexpiry'               => 'Մարում.',
'protect_expiry_invalid'      => 'Անթույլատրելի մարման ժամկետ։',
'protect_expiry_old'          => 'Մարման ժամկետը անցյալում է։',
'protect-text'                => "Այստեղ դուք կարող եք դիտել և փոխել '''<nowiki>$1</nowiki>''' էջի պաշտպանման մակարդակը։",
'protect-locked-blocked'      => "Դուք չեք կարող փոխել էջի պաշտպանման մակարդակը քանի դեռ արգելափակված եք։ Էջի ընթացիկ կարգավորումն է՝ '''$1'''.",
'protect-locked-dblock'       => "Պաշտպանման մակարդակը չի կարող փոխվել տվյալների բազայի կողպման պատճառով։ Էջի ընթացիկ կարգավորումն է՝ '''$1'''.",
'protect-locked-access'       => "Ձեր մասնակցային հաշիվը չունի էջի պաշտպանման մակարդակը փոփոխելու իրավունք։
Էջի ընթացիկ կարգավորումն է՝ '''$1'''.",
'protect-cascadeon'           => 'Այս էջը ներկայումս պաշտպանված է, քանի որ այն ընդգրկված է հետևյալ {{PLURAL:$1|էջում, որը պաշտպանվել է|էջերում, որոնք պաշտպանվել են}} կասկադային պաշտպանմամբ։ Դուք կարող եք փոխել պաշտպանման մակարդակը, բայց դա չի ազդի կասկադային պաշտպանման վրա։',
'protect-default'             => 'Թույլատրել բոլոր մասնակիցներին',
'protect-fallback'            => 'Անհրաժեշտ է «$1» իրավունք',
'protect-level-autoconfirmed' => 'Պաշտպանել նոր և չգրանցված մասնակիցներից',
'protect-level-sysop'         => 'Միայն ադմինիստրատորներ',
'protect-summary-cascade'     => 'կասկադային',
'protect-expiring'            => 'մարում՝ $1 (UTC)',
'protect-cascade'             => 'Պաշտպանել այս էջում ընդգրկված էջերը (կասկադային պաշտպանում)',
'protect-cantedit'            => 'Դուք չեք կարող փոխել այս էջի պաշտպանության մակարդակը, քանի որ ձեզ չի թույլատրվում խմբագրել այն։',
'protect-edit-reasonlist'     => 'Խմբագրել պաշտպանման պատճառների ցանկը',
'protect-expiry-options'      => '2 ժամ:2 hours,1 օր:1 day,3 օր:3 days,1 շաբաթ:1 week,2 շաբաթ:2 weeks,1 ամիս:1 month,3 ամիս:3 months,6 ամիս:6 months,1 տարի:1 year,առհավետ:infinite',
'restriction-type'            => 'Իրավունքներ.',
'restriction-level'           => 'Սահմանափակման մակարդակ.',
'minimum-size'                => 'Նվազագույն չափ',
'maximum-size'                => 'Առավելագույն չափ:',
'pagesize'                    => '(բայթ)',

# Restrictions (nouns)
'restriction-edit'   => 'Խմբագրում',
'restriction-move'   => 'Տեղափոխում',
'restriction-create' => 'Ստեղծում',
'restriction-upload' => 'Բեռնում',

# Restriction levels
'restriction-level-sysop'         => 'լրիվ պաշտպանված',
'restriction-level-autoconfirmed' => 'կիսապաշտպանված',
'restriction-level-all'           => 'բոլոր մակարդակները',

# Undelete
'undelete'                     => 'Դիտել ջնջված էջերը',
'undeletepage'                 => 'Դիտել և վերականգնել ջնջված էջերը',
'viewdeletedpage'              => 'Դիտել ջնջված էջերը',
'undeletepagetext'             => 'Հետևյալ էջերը ջնջվել են, բայց դեռ գտնվում են արխիվում և կարող են վերականգնվել։ Արխիվը կարող է պարբերաբար մաքրվել։',
'undeleteextrahelp'            => "Էջի լրիվ վերականգնման համար բոլոր արկղերը թողեք առանց նշումների և սեղմեք '''«Վերականգնել»''' կոճակը։ Մասնակի վերականգնման համար նշեք վերականգնման ենթակա տարբերակների արկղերը և սեղմեք '''«Վերականգնել»'''։ '''«Մաքրել»''' կոճակին սեղմելիս կմաքրվի մեկնաբանության դաշտը և բոլոր նշումները։",
'undeleterevisions'            => 'արխիվում կա $1 տարբերակ',
'undeletehistory'              => 'Եթե դուք վերականգնեք էջը, նրա բոլոր տարբերակները կվերականգնվեն փոփոխությունների պատմության մեջ։
Եթե էջի ջնջումից հետո ստեղծվել է նոր էջ նույն անվանմամբ, ապա վերականգնված տարբերակները կհայտնվեն էջի նախկին պատմության մեջ, և ընթացիկ տարբերակները ավտոմատ չեն փոխարինվի։',
'undeleterevdel'               => 'Վերականգնումը չի կատարվի, եթե այն բերելու է վերջին տարբերակի մասնակի ջնջման։ 
Այսպիսի դեպքերում հարկավոր է նշումից հանել կամ ցուցադրել վերջին ջնջված տարբերակները։',
'undeletehistorynoadmin'       => 'Էջը ջնջվել է։ Ջնջման պատճառը և էջը խմբագրած մասնակիցների անունները բերված են ստորև։ Այս ջնջված տարբերակների բուն տեքստերը կարող են դիտել միայն ադմինիստրատորները։',
'undelete-revision'            => '«$1» էջի $3 մասնակցի կողմից ջնջված տարբերակ ($2 պահով).',
'undeleterevision-missing'     => 'Սխալ կամ գոյություն չունեցող տարբերակ։ Հնարավոր է դուք անցել եք սխալ հղմամբ, կամ տարբերակը վերականգնվել է, կամ էլ ջնջվել արխիվից։',
'undeletebtn'                  => 'Վերականգնել',
'undeletelink'                 => 'դիտել/վերականգնել',
'undeletereset'                => 'Մաքրել',
'undeleteinvert'               => 'Շրջել ընտրությունը',
'undeletecomment'              => 'Մեկնաբանություն.',
'undeletedarticle'             => '«[[$1]]» վերականգնված է',
'undeletedrevisions'           => 'վերականգնվեց $1 տարբերակ',
'undeletedrevisions-files'     => 'վերականգնվեց $1 {{PLURAL:$1|տարբերակ}} և  $2 {{PLURAL:$2|նիշք}}',
'undeletedfiles'               => 'վերականգնվեց $1 {{PLURAL:$1|նիշք}}',
'cannotundelete'               => 'Վերականգնումը ձախողվեց։ Հնարավոր է մեկ ուրիշն արդեն վերականգնել է այս էջը։',
'undeletedpage'                => "'''«$1» էջը վերականգնված է։'''

Տես [[Special:Log/delete|ջնջման տեղեկամատյանը]]` վերջին ջնջումների և վերականգնումների համար։",
'undelete-header'              => 'Տես [[Special:Log/delete|ջնջման տեղեկամատյանը]]՝ վերջին ջնջումների և վերականգնումների համար։',
'undelete-search-box'          => 'Որոնել ջնջված էջերը',
'undelete-search-prefix'       => 'Ցուց տալ էջերը նախածանցով.',
'undelete-search-submit'       => 'Որոնել',
'undelete-no-results'          => 'Համընկնող էջեր չեն գտնվել ջնջված էջերի արխիվում։',
'undelete-filename-mismatch'   => 'Չհաջողվեց վերականգնել նիշքի $1 ժամդրոշմով տարբերակը. նիշքի անվան անհամապատասխանություն',
'undelete-bad-store-key'       => 'Չհաջողվեց վերականգնել նիշքի $1 ժամդրոշմով տարբերակը. նիշքը բացակայում էր ջնջումից առաջ։',
'undelete-cleanup-error'       => 'Տեղի ունեցավ սխալ չօգտագործվող արխիվացված «$1» նիշքը ջնջելիս։',
'undelete-missing-filearchive' => 'Չհաջողվեց վերականգնել $1 արխիվային իդենտիֆիկատորով նիշքը, քանի որ այն բացակայում է տվյալների բազայից։ Հնարավոր է այն արդեն վերականգնվել է։',
'undelete-error-short'         => 'Նայլի վերականգնման սխալ. $1',
'undelete-error-long'          => 'Տեղի են ունեցել սխալներ նիշքը վերականգնելու ընթացքում.

$1',

# Namespace form on various pages
'namespace'      => 'Անվանատարածք.',
'invert'         => 'շրջել ընտրությունը',
'blanknamespace' => '(Գլխավոր)',

# Contributions
'contributions'       => 'Մասնակցի ներդրում',
'contributions-title' => '$1 մասնակցի ներդրումը',
'mycontris'           => 'Իմ ներդրումը',
'contribsub2'         => '$1-ի ներդրումները ($2)',
'nocontribs'          => 'Այս չափանիշներին համապատասխանող փոփոխություններ չեն գտնվել։',
'uctop'               => ' (վերջինը)',
'month'               => 'Սկսած ամսից (և վաղ)՝',
'year'                => 'Սկսած տարեթվից (և վաղ)՝',

'sp-contributions-newbies'     => 'Ցույց տալ միայն նորաստեղծ հաշիվներից կատարված ներդրումները',
'sp-contributions-newbies-sub' => 'Նոր մասնակցային հաշիվներից',
'sp-contributions-blocklog'    => 'Արգելափակման տեղեկամատյան',
'sp-contributions-deleted'     => 'Մասնակցի ջնջված ներդրում',
'sp-contributions-logs'        => 'տեղեկամատյաններ',
'sp-contributions-talk'        => 'Քննարկում',
'sp-contributions-userrights'  => 'Մասնակիցների իրավունքների կառավարում',
'sp-contributions-search'      => 'Որոնել ներդրումները',
'sp-contributions-username'    => 'IP-հասե կամ մասնակցի անուն.',
'sp-contributions-submit'      => 'Որոնել',

# What links here
'whatlinkshere'            => 'Այստեղ հղվող էջերը',
'whatlinkshere-title'      => 'Էջեր, որոնք հղում են դեպի «$1»',
'whatlinkshere-page'       => 'Էջ.',
'linkshere'                => "Հետևյալ էջերը հղում են '''[[:$1]]''' էջին.",
'nolinkshere'              => "Ուրիշ էջերից '''[[:$1]]''' էջին հղումներ չկան։",
'nolinkshere-ns'           => "Ընտրված անվանատարածքում '''[[:$1]]''' էջին հղվող էջեր չկան։",
'isredirect'               => 'վերահղման էջ',
'istemplate'               => 'ներառում',
'isimage'                  => 'պատկերի հղումներ',
'whatlinkshere-prev'       => '{{PLURAL:$1|նախորդ|նախորդ $1}}',
'whatlinkshere-next'       => '{{PLURAL:$1|հաջորդ|հաջորդ $1}}',
'whatlinkshere-links'      => '← հղումներ',
'whatlinkshere-hideredirs' => '$1 վերահղում',
'whatlinkshere-hidetrans'  => '$1 ներառումները',
'whatlinkshere-hidelinks'  => '$1 հղում',
'whatlinkshere-filters'    => 'Զտիչներ',

# Block/unblock
'blockip'                     => 'Մասնակցի արգելափակում',
'blockip-legend'              => 'Մասնակցի արգելափակում',
'blockiptext'                 => 'Օգտագործեք ստորև բերված ձևը որոշակի IP-հասցեից կամ մասնակցի անունից գրելու հնարավորությունը արգելափակելու համար։
Նման բան հարկավոր է անել միայն վանդալության կանխարգելման նպատակով և համաձայն [[{{MediaWiki:Policy-url}}|կանոնակարգի]]։
Նշեք արգելափակման որոշակի պատճառը ստորև (օրինակ՝ նշեք այն էջը, որում վանդալություն է տեղի ունեցել)։',
'ipadressorusername'          => 'IP-հասցե կամ մասնակցի անուն.',
'ipbexpiry'                   => 'Մարման ժամկետ.',
'ipbreason'                   => 'Պատճառ.',
'ipbreasonotherlist'          => 'Այլ պատճառ',
'ipbreason-dropdown'          => '*Արգելափակման սովորական պատճառներ
** Կեղծ տեղեկությունների ներմուծում
** Էջերից նյութերի հեռացում
** Արտաքին կայքերին հղումների սպամ
** Անիմաստ/անկապ տեքստի ներմուծում էջերում
** Վարկաբեկող/ահաբեկող պահվածք
** Բազմաթիվ մասնակցային հաշիվների չարաշահում
** Անպատշաճ մասնակցի անուն',
'ipbcreateaccount'            => 'Կանխարգելել մասնակցային հաշվի ստեղծումը',
'ipbemailban'                 => 'Կանխարգելել մասնակցի կողմից էլ-նամակների ուղարկումը',
'ipbenableautoblock'          => 'Ավտոմատիկ արգելափակել այս մասնակցի վերջին IP-հասցեն և բոլոր հետագա IP-հասցեները, որոնցից նա կփորձի խբագրումներ կատարել',
'ipbsubmit'                   => 'Արգելափակել այս մասնակցին',
'ipbother'                    => 'Այլ ժամկետ.',
'ipboptions'                  => '2 ժամ:2 hours,1 օր:1 day,3 օր:3 days,1 շաբաթ:1 week,2 շաբաթ:2 weeks,1 ամիս:1 month,3 ամիս:3 months,6 ամիս:6 months,1 տարի:1 year,առհավետ:infinite',
'ipbotheroption'              => 'այլ',
'ipbotherreason'              => 'Այլ/հավելյալ պատճառներ.',
'ipbhidename'                 => 'Թաքցնել մասնակցի անունը արգելափակման տեղեկամատյանից, գործող արգելափակումների ցանկից և մասնակիցների ցանկից։',
'badipaddress'                => 'Սխալ IP-հասցե',
'blockipsuccesssub'           => 'Արգելափակումը կատարված է',
'blockipsuccesstext'          => '[[Special:Contributions/$1|«$1»]] արգելափակված է։
<br />Տես [[Special:IPBlockList|արգելափակված IP-հասցեների ցանկը]]։',
'ipb-edit-dropdown'           => 'Խմբագրել արգելափակման պատճառները',
'ipb-unblock-addr'            => 'Անարգելել $1 մասնակցին',
'ipb-unblock'                 => 'Անարգելել որևէ մասնակից կամ IP-հասցե',
'ipb-blocklist'               => 'Դիտել գործող արգելափակումները',
'ipb-blocklist-contribs'      => '$1 մասնակցի ներդրումը',
'unblockip'                   => 'Անարգելել մասնակցին',
'unblockiptext'               => 'Օգտագործեք ստորև ձևը՝ նախկինում արգելափակված IP-հասցեի կամ մասնակցի գրելու հնարավորությունը վերականգնելու համար։',
'ipusubmit'                   => 'Հանել արգելափակումը',
'unblocked'                   => '[[User:$1|$1]] մասնակիցը անարգելված է։',
'unblocked-id'                => '$1 արգելափակումը հանված է',
'ipblocklist'                 => 'Արգելափակված IP-հասցեները և մասնակիցները',
'ipblocklist-legend'          => 'Արգելափակված մասնակցի որոնում',
'ipblocklist-submit'          => 'Որոնել',
'infiniteblock'               => 'ընդմիշտ',
'expiringblock'               => 'կմարվի $1 $2',
'anononlyblock'               => 'միայն անանուն',
'noautoblockblock'            => 'ավտոմատ արգելափակումը անջատված է',
'createaccountblock'          => 'մասնակցային հաշվի ստեղծումը արգելափակված է',
'emailblock'                  => 'էլ-փոստը արգելափակված',
'blocklist-nousertalk'        => 'չի կարող խմբագրել իր քննարկման էջը',
'ipblocklist-empty'           => 'Արգելափակումների ցանկը դատարկ է։',
'ipblocklist-no-results'      => 'Նշված IP-հասցեն կամ մասնակցի անունը արգելափակված չէ։',
'blocklink'                   => 'արգելափակել',
'unblocklink'                 => 'անարգելել',
'change-blocklink'            => 'փոխել արգելափակումը',
'contribslink'                => 'ներդրում',
'autoblocker'                 => 'Դուք ավտոմատիկ արգելափակվել եք «$1» մասնակցի հետ ձեր IP-հասցեի համընկնելու պատճառով։ Նրա արգելափակման պատճառն է՝ «$2»։',
'blocklogpage'                => 'Արգելափակման տեղեկամատյան',
'blocklogentry'               => 'արգելափակվել է  [[$1]]. արգելափակման ժամկետն է՝  $2 $3',
'blocklogtext'                => 'Սա մասնակիցների արգելափակման և անարգելման գործողությունների տեղեկամատյանն է։
Ավտոմատիկ արգելափակված IP-հասցեները ընդգրկված չեն այստեղ։
Տես [[Special:BlockList|նաերկայումս գործող արգելափակումների ցանկը]]։',
'unblocklogentry'             => 'անարգելված է $1',
'block-log-flags-anononly'    => 'միայն անանուն մասնակիցներ',
'block-log-flags-nocreate'    => 'մասնակցային հաշվի ստեղծումը արգելված է',
'block-log-flags-noautoblock' => 'ավտոմատ արգելափակումը անջատված է',
'block-log-flags-noemail'     => 'էլ-փոստը արգելափակված է',
'range_block_disabled'        => 'Ադմինիստրատորների կողմից լայնույթի արգելափակման հնարավորությունը անջատված է։',
'ipb_expiry_invalid'          => 'Մարման ժամկետը անթույլատրելի է',
'ipb_already_blocked'         => '«$1» մասնակիցը արդեն արգելափակված է',
'ipb_cant_unblock'            => 'Սխալ. «$1» իդենտիֆիկատորով արգելափակում չի գտնվել։ Հնարավոր է այն արդեն անարգելվել է։',
'ip_range_invalid'            => 'IP-հասցեների անթույլատրելի լայնույթ։',
'proxyblocker'                => 'Փոխանորդի արգելափակում',
'proxyblockreason'            => 'Ձեր IP-հասցեն արգելափակվել է, քանի որ այն ազատ օգտագործման փոխանորդ է։ Խնդրում ենք կապնվել ձեր ցանցային կամ տեխնիկական ծառայության տրամադրողի հետ և տեղեկացնել այս լուրջ անվտանգության խնդրի մասին։',
'proxyblocksuccess'           => 'Արված է։',
'sorbsreason'                 => 'Ձեր IP-հասցեն հաշվված է որպես ազատ օգտագործման փոխանորդ DNSBL ցանկում։',
'sorbs_create_account_reason' => 'Ձեր IP-հասցեն հաշվված է որպես ազատ օգտագործման փոխանորդ DNSBL ցանկում։ Դուք չեք կարող ստեղծել մասնակցային հաշիվ։',

# Developer tools
'lockdb'              => 'Կողպել տվյալների բազան',
'unlockdb'            => 'Բանալ տվյալների բազան',
'lockdbtext'          => 'Տվյլաների բազայի կողպումը կընդհատի բոլոր մասնակիցների կողմից էջերի խմբագրման, իրենց նախընտրությունների փոփոխման և այլ հնարավորությունները, որոնք պահանջում են տվյալների բազայի փոփոխություններ։ Խնդրում ենք հաստատել, որ դուք մտադրված եք դա անել և կբանաք տվյալների բազան սպասարկման ավարտից հետո։',
'unlockdbtext'        => 'Տվյլաների բազայի բանումը կվերականգնի բոլոր մասնակիցների կողմից էջերի խմբագրման, իրենց նախընտրությունների փոփոխման և այլ հնարավորությունները, որոնք պահանջում են տվյալների բազայի փոփոխություններ։ Խնդրում ենք հաստատել, որ դուք մտադրված եք դա անել։',
'lockconfirm'         => 'Այո, ես իսկապես ուզում եմ կողպել տվյալների բազան։',
'unlockconfirm'       => 'Այո, ես իսկապես ուզում եմ բանալ տվյալների բազան։',
'lockbtn'             => 'Կողպել տվյալների բազան',
'unlockbtn'           => 'Բանալ տվյալների բազան',
'locknoconfirm'       => 'Դուք չեք նշել հաստատման արկղը։',
'lockdbsuccesssub'    => 'Տվյալների բազան կողպված է',
'unlockdbsuccesssub'  => 'Տվյալների բազան բանված է։',
'lockdbsuccesstext'   => 'Տվյալների բազան կողպված է։<br />
Չմոռանաք [[Special:UnlockDB|բանալ այն]] սպասարկման ավարտից հետո։',
'unlockdbsuccesstext' => 'Տվյալների բազան բանված է։',
'lockfilenotwritable' => 'Տվյալների բազայի կողպման նիշքը գրելի չէ։ Տվյալների բազան կողպելու կամ բանալու համար ցանցի սպասարկիչը պետք է ունենա այս նիշքը փոփոխելու իրավունք։',
'databasenotlocked'   => 'Տվյալների բազան կողպված չէ։',

# Move page
'move-page'               => '$1 — տեղափոխում',
'move-page-legend'        => 'Տեղափոխել էջը',
'movepagetext'            => "Ստորև բերված ձևով կարող եք վերանվանել էջը՝ միաժամանակ տեղափոխելով նրա պատմությունը նոր անվանմանը։
Հին էջը կդառնա վերահղման էջ դեպի նոր անվանումը։
Դուք կարող եք ավտոմատիկ կերպով թարմացնել հին անվանմանը տանող վերահղումները։
Եթե ընտրեք չանել դա, ապա խնդրում ենք ստուգել [[Special:DoubleRedirects|կրկնակի]] կամ [[Special:BrokenRedirects|չգործող վերահղումների]] առկայությունը։
Դուք պատասխանատու եք ստուգել, որպեսզի հղումները շարունակեն տանել այնտեղ, ուր պետք է տանեն։

Ի նկատի ունեցեք, որ էջը '''չի''' տեղափոխվի, եթե նոր անվանմամբ էջ արդեն գոյություն ունի՝ բացառությամբ այն դեպքերի, երբ էջը դատարկ է կամ վերահղման էջ է, և չունի նախկին փոփոխումների պատմություն
Սա նշանակում է, որ դուք կարող եք հետ տեղափոխել էջը հին անվանմանը, եթե տեղափոխումը կատարվել է սխալմամբ և նաև չեք կարող արդեն գոյություն ունեցող էջը վրագրել։

'''ԶԳՈՒՇԱՑՈ՜ՒՄ'''
Այս գործողությունը կարող է ունենալ արմատական ազդեցություն ''ժողովրդական'' էջի համար։
Շարունակելուց առաջ խնդրում ենք համոզվել նրանում, որ դուք հասկանում եք հնարավոր հետևանքները։",
'movepagetalktext'        => "Կցված քննարկման էջը ավտոմատիկ կտեղափոխվի էջի հետ՝ '''բացառությամբ դեպքերի, երբ'''.
*Գոյություն ունի ոչ-դատարկ քքնարկման էջ նոր անվանման տակ
*Դուք հանել եք նշումը ստորև արկղից

Այսպիսի դեպքերում հարկավոր է տեղափոխել կամ միաձուլել էջերը ձեռքով, եթե դա ցանկանաք։",
'movearticle'             => 'Տեղափոխել էջը',
'movenologin'             => 'Դուք չեք մտել համակարգ',
'movenologintext'         => 'Անհրաժեշտ է [[Special:UserLogin|մտնել համակարգ]]՝ էջը տեղափոխելու համար։',
'movenotallowed'          => 'Դուք չունեք էջերի տեղափոխման իրավունք։',
'newtitle'                => 'Նոր անվանում.',
'move-watch'              => 'Հսկել էջը',
'movepagebtn'             => 'Տեղափոխել էջը',
'pagemovedsub'            => 'Էջը տեղափոխվեց',
'movepage-moved'          => "'''«$1» էջը վերանվանվել է «$2»'''",
'movepage-moved-redirect' => 'Ստեղծվել է վերահղում։',
'articleexists'           => 'Այդ անվանմամբ էջ արդեն գոյություն ունի կամ ձեր ընտրած անվանումը անթույլատրելի է։
Խնդրում ենք ընտրել այլ անվանում։',
'talkexists'              => "'''Էջը հաջողությամբ տեղափոխվեց, սակայն կցված քննարկման էջը հնարավոր չէր տեղափոխել, քանի որ նոր անվանմամբ էջ արդեն գոյություն ուներ։ Խնդրում ենք միաձուլել դրանք ձեռքով։'''",
'movedto'                 => 'տեղափոխված է',
'movetalk'                => 'Տեղափոխել զուգակցված քննարկման էջը',
'1movedto2'               => '«[[$1]]» վերանվանված է «[[$2]]»',
'1movedto2_redir'         => '«[[$1]]» վերանվանված է «[[$2]]» վերահղմամբ',
'movelogpage'             => 'Տեղափոխման տեղեկամատյան',
'movelogpagetext'         => 'Ստորև բերված է վերանվանված էջերի ցանկը։',
'movereason'              => 'Պատճառ.',
'revertmove'              => 'հետ շրջել',
'delete_and_move'         => 'Ջնջել և տեղափոխել',
'delete_and_move_text'    => '==Պահանջվում է ջնջում==

«[[:$1]]» անվանմամբ էջ արդեն գոյություն ունի։ Ուզո՞ւմ եք այն ջնջել՝ տեղափոխումը իրականացնելու համար։',
'delete_and_move_confirm' => 'Այո, ջնջել էջը',
'delete_and_move_reason'  => 'Ջնջված է՝ տեղափոխման տեղ ազատելու համար',
'selfmove'                => 'Ելակետային և նոր անվանումները համընկնում են. անհնար է տեղափոխել էջը ինքն իրեն։',
'move-leave-redirect'     => 'Թողնել վերահղում։',

# Export
'export'            => 'Արտածել էջերը',
'exporttext'        => 'Դուք կարող եք արտածել որևէ էջի կամ էջերի ամբողջության տեքստերը և փոփոխումների պատմությունները XML ֆորմատով, որը այնուհետև կարող է ներմուծվել այլ վիքի՝ օգտագործելով MediaWiki ծրագրի [[Special:Import|ներմուծման էջը]]։

Էջեր արտածելու համար մուտքագրեք դրանց անվանումները խմբագրման դաշտում՝ մեկ անվանում ամեն տողում, և ընտրեք՝ ցանկանում եք արտածել ամբողջ պատմությունները, թե միայն ընթացիկ տարբերակները, վերջին խմբագրումների մասին տեղեկությունների հետ միասին։

Վերջին տարբերակը արտածելու համար դուք կարող եք նաև օգտագործել հատուկ հղումներ, օր.՝ [[{{MediaWiki:Mainpage}}]] էջի համար հղման հասցեն է [[{{#Special:Export}}/{{MediaWiki:Mainpage}}]]։',
'exportcuronly'     => 'Ընդգրկել միայն ընթացիկ տարբերակը, առանց լրիվ պատմության',
'exportnohistory'   => "----
'''Ծանուցում.''' էջերի փոփոխումների լրիվ պատմության արտածումը այս ձևի միջոցով անջատված է արտադրողականության նկատառումներով։",
'export-submit'     => 'Արտածել',
'export-addcattext' => 'Ավելացնել էջեր կատեգորիայից.',
'export-addcat'     => 'Ավելացնել',
'export-download'   => 'Առաջարկել հիշել որպես նիշք',

# Namespace 8 related
'allmessages'               => 'Համակարգային ուղերձներ',
'allmessagesname'           => 'Ուղերձ',
'allmessagesdefault'        => 'Լռությամբ տեքստ',
'allmessagescurrent'        => 'Ընթացիկ տեքստ',
'allmessagestext'           => 'Ստորև բերված է «MediaWiki» անվանատարածքի բոլոր համակարգային ուղերձների ցանկը։
Please visit [http://www.mediawiki.org/wiki/Localisation MediaWiki Localisation] and [http://translatewiki.net translatewiki.net] if you wish to contribute to the generic MediaWiki localisation.',
'allmessagesnotsupportedDB' => "Այս էջը չի գործում, քանի որ '''\$wgUseDatabaseMessages''' հատկանիշը անջատված է։",

# Thumbnails
'thumbnail-more'           => 'Ընդարձակել',
'filemissing'              => 'Նման նիշք չկա',
'thumbnail_error'          => 'Պատկերիկի ստեղծման սխալ. $1',
'djvu_page_error'          => 'DjVu էջը լայնույթից դուրս է',
'djvu_no_xml'              => 'Չհաջողվեց ստեղծել XML DjVu նիշքի համար',
'thumbnail_invalid_params' => 'Պատկերիկի սխալ պարամետրեր',
'thumbnail_dest_directory' => 'Չհաջողվեց ստեղծել նպատակային թղթապանակ',

# Special:Import
'import'                     => 'Էջերի ներմուծում',
'importinterwiki'            => 'Միջվիքի ներմուծում',
'import-interwiki-text'      => 'Նշեք վիքի և ներմուծվող էջի անվանումը։
Փոփոխումների ժամանակները և խմբագրողների անունները կպահպանվեն։
Բոլոր միջվիքի ներմուծման գործողությունները գրանցվում են [[Special:Log/import|ներմուծման տեղեկամատյանում]]։',
'import-interwiki-history'   => 'Պատճենել այս էջի փոփոխումների լրիվ պատմությունը',
'import-interwiki-submit'    => 'Ներմուծել',
'import-interwiki-namespace' => 'Տեղադրել էջերը անվանատարածքում.',
'import-comment'             => 'Մեկնաբանություն.',
'importtext'                 => 'Խնդրում ենք արտածեք էջը ելակետային վիքիից օգտագործելով [[Special:Export|արտածման գործիք]], հիշեք այն նիշքի տեսքով ձեր համակարգչի վրա և այնուհետև, բեռնեք այն այստեղ։',
'importstart'                => 'Էջերի ներմուծում...',
'import-revision-count'      => '$1 տարբերակ',
'importnopages'              => 'Չկան էջեր ներմուծման համար։',
'importfailed'               => 'Ներմուծումը ձախողվեց. $1',
'importunknownsource'        => 'Ներմուծման անհայտ ելակետային տեսակ',
'importcantopen'             => 'Չհաջողվեց բացել ներմուծման նիշքը',
'importbadinterwiki'         => 'Սխալ միջվիքի հղում',
'importnotext'               => 'Տեքստ չկա',
'importsuccess'              => 'Ներմուծումն ավարտվե՜ց։',
'importhistoryconflict'      => 'Գոյություն ունեցող տարբերակների ընդհարում (հավանաբար այս էջն արդեն ներմուծվել է նախկինում)',
'importnosources'            => 'Միջվիքի ներմուծման աղբյուր ընտրված չէ, իսկ փոփոխումների պատմության ուղիղ ներմուծումը անջատված է։',
'importnofile'               => 'Ներմուծման նիշք չի բեռնվել։',

# Import log
'importlogpage'                    => 'Ներմուծման տեղեկամատյան',
'importlogpagetext'                => 'Ադմինիստրատորների կողմից այլ վիքիներից իրենց պատմությունների հետ էջերի ներմուծումներ։',
'import-logentry-upload'           => 'ներմուծվել է «[[$1]]» նիշքի բեռնումով',
'import-logentry-upload-detail'    => '$1 տարբերակ',
'import-logentry-interwiki'        => '«$1»՝ միջվիքի ներմուծմամբ',
'import-logentry-interwiki-detail' => '$1 տարբերակ $2-ից',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Ձեր մասնակցի էջը',
'tooltip-pt-anonuserpage'         => 'Ձեր IP-հասցեի մասնակցային էջը',
'tooltip-pt-mytalk'               => 'Ձեր քննարկման էջը',
'tooltip-pt-anontalk'             => 'IP-հասցեից կատարված խմբագրումների քննարկում',
'tooltip-pt-preferences'          => 'Ձեր նախընտրությունները',
'tooltip-pt-watchlist'            => 'Ձեր հսկողության տակ գտնվող էջերի ցանկը',
'tooltip-pt-mycontris'            => 'Ձեր ներդրումների ցանկը',
'tooltip-pt-login'                => 'Կոչ ենք անում մտնել համակարգ, սակայն դա պարտադիր չէ',
'tooltip-pt-anonlogin'            => 'Կոչ ենք ձեզ անում մուտք գործել համակարգ, սակայն դա պարտադիր չէ',
'tooltip-pt-logout'               => 'Դուրս գալ համակարգից',
'tooltip-ca-talk'                 => 'Քննարկումներ այս էջի բովանդակության մասին',
'tooltip-ca-edit'                 => 'Դուք կարող էք խմբագրել այս էջը։ Խնդրում ենք օգտագործել նախադիտման կոճակը հիշելուց առաջ։',
'tooltip-ca-addsection'           => 'Ստեղծել նոր բաժին',
'tooltip-ca-viewsource'           => 'Այս էջը պաշտպանված է։ Դուք կարող եք դիտել նրա ելատեքստը։',
'tooltip-ca-history'              => 'Այս էջի խմբագրումների պատմությունը',
'tooltip-ca-protect'              => 'Պաշտպանել այս էջը',
'tooltip-ca-delete'               => 'Ջնջել այս էջը',
'tooltip-ca-undelete'             => 'Վերականգնել այս էջի խմբագրումները՝ կատարված ջնջումից առաջ',
'tooltip-ca-move'                 => 'Վերանվանել էջը',
'tooltip-ca-watch'                => 'Ավելացնել այս էջը ձեր հսկողության ցանկին',
'tooltip-ca-unwatch'              => 'Հանել այս էջը ձեր հսկողության ցանկից',
'tooltip-search'                  => 'Որոնել {{SITENAME}} կայքում',
'tooltip-search-go'               => 'Անցնել այս ճշգրիտ անվանումով էջին',
'tooltip-search-fulltext'         => 'Գտնել այս տեքստով էջերը',
'tooltip-p-logo'                  => 'Գլխավոր էջ',
'tooltip-n-mainpage'              => 'Այցելեք Գլխավոր Էջը',
'tooltip-n-mainpage-description'  => 'Անցնել գլխավոր էջ',
'tooltip-n-portal'                => 'Նախագծի մասին, որտեղ գտնել ինչը, ինչով կարող եք օգնել',
'tooltip-n-currentevents'         => 'Տեղեկություններ ընթացիկ իրադարձությունների մասին',
'tooltip-n-recentchanges'         => 'Վիքիում կատարված վերջին փոփոխությունների ցանկը',
'tooltip-n-randompage'            => 'Այցելեք պատահական էջ',
'tooltip-n-help'                  => '{{SITENAME}} նախագծի ուղեցույց',
'tooltip-t-whatlinkshere'         => 'Այս էջին հղվող բոլոր վիքի էջերի ցանկը',
'tooltip-t-recentchangeslinked'   => 'Այս էջից կապված էջերի վերջին փոփոխությունները',
'tooltip-feed-rss'                => 'Այս էջի RSS սնուցումը',
'tooltip-feed-atom'               => 'Այս էջի Atom սնուցումը',
'tooltip-t-contributions'         => 'Դիտել այս մասնակցի ներդրումների ցանկը',
'tooltip-t-emailuser'             => 'Ուղարկել էլ-նամակ այս մասնակցին',
'tooltip-t-upload'                => 'Բեռնել պատկերներ կամ մեդիա նիշքեր',
'tooltip-t-specialpages'          => 'Բոլոր սպասարկող էջերի ցանկը',
'tooltip-t-print'                 => 'Այս էջի տպելու տարբերակ',
'tooltip-t-permalink'             => 'Էջի այս տարբերակի մշտական հղում',
'tooltip-ca-nstab-main'           => 'Դիտել հոդվածը',
'tooltip-ca-nstab-user'           => 'Դիտել մասնակցի էջը',
'tooltip-ca-nstab-media'          => 'Դիտել մեդիա-նիշքի էջը',
'tooltip-ca-nstab-special'        => 'Սա սպասարկող էջ է, դուք չեք կարող հենց իրեն խմբագրել',
'tooltip-ca-nstab-project'        => 'Դիտել նախագծի էջը',
'tooltip-ca-nstab-image'          => 'Դիտել պատկերի էջը',
'tooltip-ca-nstab-mediawiki'      => 'Դիտել համակարգային ուղերձը',
'tooltip-ca-nstab-template'       => 'Դիտել կաղապարը',
'tooltip-ca-nstab-help'           => 'Դիտել օգնության էջը',
'tooltip-ca-nstab-category'       => 'Դիտել կատեգորիայի էջը',
'tooltip-minoredit'               => 'Նշել այս խմբագրումը որպես չնչին',
'tooltip-save'                    => 'Հիշել ձեր կատարած փոփոխությունները',
'tooltip-preview'                 => 'Նախադիտել ձեր կատարած փոփոխությունները։ Խնդրում ենք օգտագործե՛ք սա մինչև էջի հիշելը։',
'tooltip-diff'                    => 'Ցույց տալ ձեր կատարած փոփոխությունները այս էջում',
'tooltip-compareselectedversions' => 'Դիտել այս էջի ընտրված երկու տարբերակների միջև տարբերությունները',
'tooltip-watch'                   => 'Ավելացնել այս էջը ձեր հսկողության ցանկին',
'tooltip-recreate'                => 'Վերստեղծել այս էջը, չնայած նրան, որ այն ջնջվել է նախկինում',
'tooltip-upload'                  => 'Սկսել բեռնումը',
'tooltip-rollback'                => 'Մեկ սեղմումով հետ շրջել վերջին մասնակցի կատարած բոլոր խմբագրումները',
'tooltip-undo'                    => 'Հետ շրջել կատարված փոփոխությունը և բացել խմբագրման ձևը նախադիտման ռեժիմով՝ թույլ տալով ավելացնել հետ շրջման պատճառը։',

# Stylesheets
'common.css'   => '/** Այստեղ տեղադրված CSS կոդը կկիրառվի բոլոր տեսքերի վրա */',
'monobook.css' => '/* Այստեղ տեղադրված CSS կոդը կկիրառվի Monobook տեսքի վրա*/',

# Scripts
'common.js'   => '/* Այստեղ տեղադրված JavaScript կոդը կբեռնվի բոլոր մասնակիցների համար էջերի բոլոր դիմումների ժամանակ */',
'monobook.js' => '/* Հնացած է. օգտագործեք [[MediaWiki:common.js]] */',

# Metadata
'notacceptable' => 'Վիքի-սպասարկիչը չի կարող տվյլաները տրամադրել ձեր զննարկիչի կողմից կարդացվող ֆորմատով։',

# Attribution
'anonymous'        => '{{grammar:genitive|{{SITENAME}}}} անանուն մասնակիցները',
'siteuser'         => '{{grammar:genitive|{{SITENAME}}}} մասնակից $1',
'lastmodifiedatby' => 'Այս էջը վերջին անգամ փոփոխվել է $2, $1 $3 մասնակցի կողմից։',
'othercontribs'    => 'Հիմնված է {{grammar:genitive|$1}} գործի վրա։',
'others'           => 'այլոք',
'siteusers'        => '{{grammar:genitive|{{SITENAME}}}} մասնակից(ներ) $1',
'creditspage'      => 'Երախտիքներ',
'nocredits'        => 'Այս էջի հեղինակների մասին տեղեկություններ չկան։',

# Spam protection
'spamprotectiontitle' => 'Սպամ-պաշտպանման զտիչ',
'spamprotectiontext'  => 'Էջը, որը դուք փորձում եք հիշել արգելափակվել է սպամի զտիչի կողմից։ Սա հավանաբար արտաքին կայքին հղման պատճառով է։',
'spamprotectionmatch' => 'Սպամ զտիչին գործադրած տեքստն է. $1.',
'spambot_username'    => 'Սպամի մաքրում',
'spam_reverting'      => 'Հետ է շրջվում վերջին տարբերակի, որը չի պարունակում հղումներ դեպի $1',
'spam_blanking'       => 'Բոլոր տարբերակները պարունակում են հղումներ դեպի $1, մաքրում',

# Info page
'infosubtitle'   => 'Տեղեկությունների էջի մասին',
'numedits'       => 'Խմբագրումների թիվ (հոդված). $1',
'numtalkedits'   => 'Խմբագրումների թիվ (քննարկման էջ). $1',
'numwatchers'    => 'Հսկողների թիվ. $1',
'numauthors'     => 'Տարբեր հեղինակների թիվ (հոդված). $1',
'numtalkauthors' => 'Տարբեր հեղինակների թիվ (քննարկման էջ). $1',

# Skin names
'skinname-standard'    => 'Դասական',
'skinname-nostalgia'   => 'Հայրենաբաղձություն',
'skinname-cologneblue' => 'Քյոլնի թախիծ',
'skinname-monobook'    => 'ՄիաԳիրք',
'skinname-myskin'      => 'ԻմՏեսք',
'skinname-chick'       => 'Ծիտ',
'skinname-simple'      => 'Պարզ',

# Patrolling
'markaspatrolleddiff'                 => 'Նշել որպես ստուգված',
'markaspatrolledtext'                 => 'Նշել այս էջը որպես ստուգված',
'markedaspatrolled'                   => 'Նշված է որպես ստուգված',
'markedaspatrolledtext'               => 'Ընտրված տարբերակը նշված է որպես ստուգված։',
'rcpatroldisabled'                    => 'Վերջին Փոփոխությունների Պարեկումն անջատված է',
'rcpatroldisabledtext'                => 'Վերջին Փոփոխությունների Պարեկման հնարավորությունը անջատված է:',
'markedaspatrollederror'              => 'Չհաջողվեց նշել որպես ստուգված',
'markedaspatrollederrortext'          => 'Անհրաժեշտ է ընտրել տարբերակ՝ որպես ստուգված նշելու համար։',
'markedaspatrollederror-noautopatrol' => 'Ձեզ չի թույլատրվում ձեր կատարած փոփոխությունները նշել որպես ստուգված։',

# Patrol log
'patrol-log-page' => 'Պարեկման տեղեկամատյան',
'patrol-log-line' => 'նշագրված է $1՝ $2-ից, պարեկվել է $3',
'patrol-log-auto' => '(ավտոմատ)',

# Image deletion
'deletedrevision'                 => 'Ջնջված է հին տարբերակը $1',
'filedeleteerror-short'           => 'Նիշքի ջնջման սխալ. $1',
'filedeleteerror-long'            => 'Տեղի են ունեցել սխալներ նիշքի ջնջման ընթացքում.

$1',
'filedelete-missing'              => '«$1» նիշքը չի կարող ջնջվել, քանի որ այն գոյություն չունի։',
'filedelete-old-unregistered'     => 'Ձեր նշած «$1» նիշքի տարբերակը չկա տվյալների բազայում։',
'filedelete-current-unregistered' => 'Նշված «$1» նիշքը գոյություն չունի տվյալների բազայում։',
'filedelete-archive-read-only'    => '«$1» արխիվային թղթապանակը գրելի չէ վեբ-սերվերի կողմից։',

# Browsing diffs
'previousdiff' => '← Նախորդ խմբագրում',
'nextdiff'     => 'Հաջորդ խմբագրում →',

# Media information
'mediawarning'    => "'''Զգուշացում'''. այս նիշքի տեսակը կարող է պարունակել վնասակար ծրագրային կոդ։ Այն կիրարկելը կարող է վտանգել ձեր համակարգը։",
'imagemaxsize'    => 'Պատկերի էջում պատկերի չափի սահմանափակում.',
'thumbsize'       => 'Պատկերների փոքրացված չափ.',
'widthheightpage' => '$1 × $2, $3 էջեր',
'file-info'       => 'նիշքի չափ՝ $1, MIME-տեսակ՝ $2',
'file-info-size'  => '$1 × $2 փիքսել, նիշքի չափը՝ $3, MIME-տեսակը՝ $4',
'file-nohires'    => '<small>Բարձր թույլատվությամբ տարբերակ չկա։</small>',
'svg-long-desc'   => 'SVG-նիշք, անվանապես $1 × $2 փիքսել, նիշքի չափը՝ $3',
'show-big-image'  => 'Լրիվ թույլատվությամբ',

# Special:NewFiles
'newimages'             => 'Նոր նիշքերի սրահ',
'imagelisttext'         => "Ստորև բերված է '''$1''' {{PLURAL:$1|նիշքի}} ցանկ՝ դասավորված ըստ $2։",
'showhidebots'          => '($1 բոտերին)',
'noimages'              => 'Տեսնելու բան չկա։',
'ilsubmit'              => 'Որոնել',
'bydate'                => 'ըստ ամսաթվի',
'sp-newimages-showfrom' => 'Ցույց տալ նոր պատկերները՝ սկսած $1 ժամը $2',

# Video information, used by Language::formatTimePeriod() to format lengths in the above messages
'video-dims'     => '$1, $2 × $3',
'seconds-abbrev' => 'վ',
'minutes-abbrev' => 'ր',
'hours-abbrev'   => 'ժ',

# Bad image list
'bad_image_list' => 'Գրաձևը հետևյալն է.

Հաշվի են առնվելու միայն ցանկի տարրերը (* սիմվոլով սկսվող տողերը)։
Տողի առաջին հղումը պետք է լինի դեպի արգելված պատկերը։
Տողի հետագա հղումները ընկալվելու են որպես բացառություններ, այսինքն էջեր, որտեղ նշված պատկերի փակցնումը չի արգելվում։',

# Metadata
'metadata'          => 'Մետատվյալներ',
'metadata-help'     => 'Նիշքը պարունակում է ընդարձակ տվյալները, հավանաբար ավելացված թվային լուսանկարչական ապարատի կամ սկաների կողմից, որոնք օգտագործվել են նկարը ստեղծելու կամ թվայնացնելու համար։
Եթե նիշքը ձևափոխվել է ստեղծումից ի վեր, ապա որոշ տվյալները կարող են չհամապատասխանել ձևափոխված նիշքին։',
'metadata-expand'   => 'Ցուց տալ ընդարձակ տվյալները',
'metadata-collapse' => 'Թաքցնել ընդարձակ տվյլաները',
'metadata-fields'   => 'EXIF մետատվյալների այն դաշտերը, որոնք նշված ենք այս ուղերձի մեջ, կցուցադրվեն պատկերի էջուն լռությամբ։ Այլ տվյալները լռությամբ կթաքցվեն։
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* isospeedratings
* focallength
* artist
* copyright
* imagedescription
* gpslatitude
* gpslongitude
* gpsaltitude',

# EXIF tags
'exif-imagewidth'  => 'Լայնք',
'exif-imagelength' => 'Բարձրություն',
'exif-artist'      => 'Հեղինակ',

'exif-componentsconfiguration-0' => 'գոյություն չունի',

# External editor support
'edit-externally'      => 'Խմբագրել այս նիշքը արտաքին խմբագրիչով',
'edit-externally-help' => '(Մանրամասնությունների համար տես [http://www.mediawiki.org/wiki/Manual:External_editors տեղակայման հրահանգները])',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'բոլոր',
'namespacesall' => 'բոլոր',
'monthsall'     => 'բոլոր',
'limitall'      => 'բոլոր',

# E-mail address confirmation
'confirmemail'            => 'Էլ-հասցեի վավերացում',
'confirmemail_noemail'    => 'Դուք չեք նշել գործող էլ-հասցե ձեր [[Special:Preferences|նախընտրություններում]]։',
'confirmemail_text'       => 'Այս վիքիում անհրաժեշտ է վավերացնել էլ-հասցեն մինչև էլ-փոստի վրա հիմնված հնարավորությունների օգտագործելը։ Մատնահարեք ստորև կոճակին՝ ձեր հասցեին վավերացման նամակ ուղարկելու համար։ Ուղերձում կգտնեք վավերացման կոդով հղում, որին հետևելով կվավերացնեք ձեր էլ-հասցեն։',
'confirmemail_pending'    => 'Վավերացման կոդով նամակն արդեն ուղարկվել է։ Եթե դուք նորերս եք ստեղծել մասնակցային հաշիվը, ապա, հավանաբար, արժե սպասել մի քանի րոպե մինչև նամակի ժամանելը՝ նոր կոդ հայցելուց առաջ։',
'confirmemail_send'       => 'Ուղարկել վավերացման ուղերձ',
'confirmemail_sent'       => 'Վավերացման ուղերձը ուղարկված է։',
'confirmemail_oncreate'   => 'Վավերացման կոդով նամակը ուղարկվել է ձեր նշված էլ-հասցեով։
Այս կոդը պարտադիր չէ համակարգ մտնելու համար, սակայն այն անհրաժեշտ է տրամադրել այս վիքիում էլ-փոստի վրա հիմնված հնարավորությունները ակտիվացնելու համար։',
'confirmemail_sendfailed' => 'Վավերացման նամակի ուղարկումը չհաջողվեց։ Ստուգեք հասցեի ճշտությունը։

Սերվերի պատասխանն էր. $1',
'confirmemail_invalid'    => 'Սխալ վավերացման կոդ։ Հնարավոր է կոդի ժամկետն անցած լինի։',
'confirmemail_needlogin'  => 'Ձեզ անհրաժեշտ է $1՝ ձեր էլ-փոստի հասցեն վավերացնելու համար։',
'confirmemail_success'    => 'Ձեր էլ-փոստի հասցեն վավերացված է։ Դուք կարող եք մտնել համակարգ և օգտվել վիքիից։',
'confirmemail_loggedin'   => 'Ձեր էլ-փոստի հասցեն վավերացված է։',
'confirmemail_error'      => 'Տեղի է ունեցել սխալ էլ-փոստի հասցեի վավերացման ընթացքում։',
'confirmemail_subject'    => '{{SITENAME}}. էլ-հասցեի վավերացման հայց',
'confirmemail_body'       => 'Ինչ-որ մեկը, հավանաբար դուք, $1 IP-հասցեից գրանցվել է {{SITENAME}} նախագծի կայքում՝ ստեղծելով «$2» մասնակցային հաշիվը, և նշել է ձեր էլ-փոստի հասցեն։

Էլ-հասցեն վավերացնելու և {{SITENAME}} կայքի էլ-փոստի վրա հիմնված հնարավորությունները ակտիվացնելու համար մատնահարեք ստորև բերված հղման վրա.

$3

Եթե դուք անտեղյակ եք այս հայցից, ապա խնդրում ենք պարզապես անտեսել սույն ուղերձը։ Վավերցման կոդը գերծելու է մինչև $4։',

# Scary transclusion
'scarytranscludedisabled' => '[«Interwiki transcluding» հնարավորությունը անջատված է]',
'scarytranscludefailed'   => '[Ցավոք՝ $1 կաղապարի կանչը ձախողվեց]',
'scarytranscludetoolong'  => '[Ցավոք՝ URL-հասցեն չափից երկար է]',

# Trackbacks
'trackbackbox'      => 'Այս էջի Trackback-ները.<br />
$1',
'trackbackremove'   => '([$1 ջնջել])',
'trackbacklink'     => 'Trackback',
'trackbackdeleteok' => 'Trackback-ը հաջողությամբ հեռացվեց։',

# Delete conflict
'deletedwhileediting' => 'Զգուշացում. ձեր խմբագրման ընթացքում այս էջը ջնջվել է։',
'confirmrecreate'     => "[[User:$1|$1]] ([[User talk:$1|քննարկում]]) մասնակիցը ջնջել է այս էջը ձեր խմաբգրումը սկսելուց հետո՝ հետևյալ պատճառով.
: ''$2''
Խնդրում ենք հաստատել, որ դուք իսկապես ուզում եք վերստեղծել այս էջը։",
'recreate'            => 'Վերստեղծել',

'unit-pixel' => ' փիքսել',

# action=purge
'confirm_purge_button' => 'OK',
'confirm-purge-top'    => 'Մաքրե՞լ այս էջի քեշը։',

# Multipage image navigation
'imgmultipageprev' => '← նախորդ էջ',
'imgmultipagenext' => 'հաջորդ էջ →',
'imgmultigo'       => 'Անցնե՜լ',

# Table pager
'ascending_abbrev'         => 'աճմ. կարգ.',
'descending_abbrev'        => 'նվազ',
'table_pager_next'         => 'Հաջորդ էջ',
'table_pager_prev'         => 'Նախորդ էջ',
'table_pager_first'        => 'Առաջին էջ',
'table_pager_last'         => 'Վերջին էջ',
'table_pager_limit'        => 'Ցույց տալ $1 իր մեկ էջում',
'table_pager_limit_submit' => 'Անցնել',
'table_pager_empty'        => 'Գտնված չէ',

# Auto-summaries
'autosumm-blank'   => 'Ջնջվում է էջի ամբողջ պարունակությունը',
'autosumm-replace' => "Փոխվում է էջը '$1'-ով",
'autoredircomment' => 'Վերահղվում է դեպի [[$1]]',
'autosumm-new'     => 'Նոր էջ. $1',

# Size units
'size-bytes'     => '$1 բայթ',
'size-kilobytes' => '$1 ԿԲ',
'size-megabytes' => '$1 ՄԲ',
'size-gigabytes' => '$1 ԳԲ',

# Live preview
'livepreview-loading' => 'Բեռնվում է…',
'livepreview-ready'   => 'Բեռնվում է… Պատրա՜ստ է։',
'livepreview-failed'  => 'Ուղիղ նախադիտումը ձախողվեց։ Փորձեք օգտվել հասարակ նախադիտմամբ։',
'livepreview-error'   => 'Չհաջողվեց կապ հաստատել. $1 «$2»։ Փորձեք օգտվել հասարակ նախադիտմամբ։',

# Friendlier slave lag warnings
'lag-warn-normal' => 'Վերջին $1 վայրկյանի ընթացքում կատարված փափախությունները հնարավոր է չլինեն այս ցանկում։',
'lag-warn-high'   => 'Տվյալների բազայի մեծ հապաղման պատճառով վերջին $1 {{PLURAL:$1|վայրկյանում|վայրկյանում}} կատարված խմբագրումները հնարավոր է չերևան այս ցանկում։',

# Watchlist editor
'watchlistedit-numitems'       => 'Ձեր հսկացանկը պարունակում է {{PLURAL:$1|1 անվանում|$1 անվանում}}՝ քննարկման էջերը չհաշված։',
'watchlistedit-noitems'        => 'Ձեր հսկացանկը չի պարունակում ոչ մի անվանում։',
'watchlistedit-normal-title'   => 'Հսկացանկի խմբագրում',
'watchlistedit-normal-legend'  => 'Հեռացնել անվանումները հսկացանկից',
'watchlistedit-normal-explain' => 'Ձեր հսկացանկի անվանումները բերված են ստորև։
Անվանումը հեռացնելու համար նշեք անվանման կողքի արկղում և մատնահարեք Հեռացնել Անվանումները։
Դուք կարող եք նաև [[Special:EditWatchlist/raw|խմբագրել հում ցանկը]]։',
'watchlistedit-normal-submit'  => 'Հեռացնել Անվանումները',
'watchlistedit-normal-done'    => '{{PLURAL:$1|1 անվանում|$1 անվանում}} հեռացվեց ձեր հսկացանկից.',
'watchlistedit-raw-title'      => 'Հում հսկացանկի խմբագրում',
'watchlistedit-raw-legend'     => 'Խմբագրել հում հսկացանկը',
'watchlistedit-raw-explain'    => 'Ձեր հսկացանկի անվանումները բերված են ստորև։ Դուք կարող եք այն խմբագրել հեռացնելով եղածները կամ ավելացնելով նոր անվանումներ՝ ամեն տողում մեկ անվանում։ Ավարտելուց հետո մատնահարեք Թարմացնել Հսկացանկը։ Կարող եք նաև [[Special:EditWatchlist|օգտագործել ստանդարտ խմբագրիչը]]։',
'watchlistedit-raw-titles'     => 'Անվանումներ.',
'watchlistedit-raw-submit'     => 'Թարմացնել Հսկացանկը',
'watchlistedit-raw-done'       => 'Ձեր հսկացանկը թարմացված է։',
'watchlistedit-raw-added'      => 'Ավելացվեց {{PLURAL:$1|1 անվանում|$1 անվանում}}.',
'watchlistedit-raw-removed'    => 'Հեռացվեց {{PLURAL:$1|1 անվանում|$1 անվանում}}.',

# Watchlist editing tools
'watchlisttools-view' => 'Փոփոխությունները հսկացանկում',
'watchlisttools-edit' => 'Դիտել և խմբագրել հսկացանկը',
'watchlisttools-raw'  => 'Խմբագրել հում հսկացանկը',

# Special:Version
'version' => 'MediaWiki տարբերակը',

# Special:FileDuplicateSearch
'fileduplicatesearch-submit' => 'Որոնել',

# Special:SpecialPages
'specialpages'                   => 'Սպասարկող էջեր',
'specialpages-note'              => '----
* Հասարակ հատուկ էջեր։
* <strong class="mw-specialpagerestricted">Սահմանափակված հատուկ էջեր։</strong>',
'specialpages-group-maintenance' => 'Տեխնիկական սպասարկման տեղեկատուներ',
'specialpages-group-other'       => 'Այլ հատուկ էջեր',
'specialpages-group-login'       => 'Մտնել / Գրանցվել',
'specialpages-group-changes'     => 'Վերջին փոփոխություններ և տեղեկամատյաններ',
'specialpages-group-media'       => 'Մեդիա նյութերի տեղեկատուներ և բեռնում',
'specialpages-group-users'       => 'Մասնակիցներ և իրավունքներ',
'specialpages-group-highuse'     => 'Հաճախակի օգտագործվող էջեր',
'specialpages-group-pages'       => 'Էջերի ցանկեր',
'specialpages-group-pagetools'   => 'Էջերի գործիքներ',
'specialpages-group-wiki'        => 'Վիքի-տվյալներ և գործիքներ',
'specialpages-group-redirects'   => 'Վերահղող հատուկ էջեր',
'specialpages-group-spam'        => 'Սպամի գործիքներ',

# Special:BlankPage
'blankpage'              => 'Դատարկ էջ',
'intentionallyblankpage' => 'Այս էջը միտումնավոր դատարկ է թողված։',

# Database error messages
'dberr-header'   => 'Այս վիքիում խնդիրներ են առաջացել',
'dberr-problems' => 'Այս կայքում առաջացել են տեխնիկական խնդիրներ։ Հայցում ենք ձեր ներողությունը։',
'dberr-again'    => 'Փորձեք մի քանի րոպե սպասել և վերաբեռնել էջը։',

);
