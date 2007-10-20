<?php
/** Armenian (Հայերեն)
  *
  * @addtogroup Language
  *
  * Based on MessagesEn.php revision 18716 (2007-21-02)
  * and hy.wikipedia MediaWiki namespace (2007-24-04)
  *
  * @author Ruben Vardanyan (me@RubenVardanyan.com)
  * @author Teak
  * @author Togaed
  * 
  * ՈՒՇԱԴՐՈՒԹՅՈՒՆ, ՄԻ ՓՈՓՈԽԵՔ ԱՅՍ ՖԱՅԼԸ
  *
  * Եթե անհրաժեշտ է կատարել փոփոխություն ինտերֆեյսի առանձին տողերի մեջ,
  * ապա կատարեք դա խմբագրելով MediaWiki:* տիպի ֆայլերը։
  * Դրանց ցուցակը կարող եք տեսնել այս էջում՝ Special:Allmessages։
  *
  */

$separatorTransformTable = array(
	',' => "\xc2\xa0", # nbsp
	'.' => ','
);

$fallback8bitEncoding = 'UTF-8';

$linkPrefixExtension = true;

$namespaceNames = array(
	NS_MEDIA            => 'Մեդիա',
	NS_SPECIAL          => 'Սպասարկողէջ',
	NS_MAIN	            => '',
	NS_TALK	            => 'Քննարկում',
	NS_USER             => 'Մասնակից',
	NS_USER_TALK        => 'Մասնակցի_քննարկում',
	# NS_PROJECT set by $wgMetaNamespace
	NS_PROJECT_TALK     => '{{GRAMMAR:genitive|$1}}_քննարկում',
	NS_IMAGE            => 'Պատկեր',
	NS_IMAGE_TALK       => 'Պատկերի_քննարկում',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki_քննարկում',
	NS_TEMPLATE         => 'Կաղապար',
	NS_TEMPLATE_TALK    => 'Կաղապարի_քննարկում',
	NS_HELP             => 'Օգնություն',
	NS_HELP_TALK        => 'Օգնության_քննարկում',
	NS_CATEGORY         => 'Կատեգորիա',
	NS_CATEGORY_TALK    => 'Կատեգորիայի_քննարկում',
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
#   ID                                 CASE  SYNONYMS
	'redirect'               => array( 0,    '#REDIRECT', '#ՎԵՐԱՀՂՈՒՄ' ),
	'notoc'                  => array( 0,    '__NOTOC__', '__ԱՌԱՆՑ_ԲՈՎ__' ),
	'nogallery'              => array( 0,    '__NOGALLERY__', '__ԱՌԱՆՑ_ՍՐԱՀԻ__' ),
	'forcetoc'               => array( 0,    '__FORCETOC__', '__ՍՏԻՊԵԼ_ԲՈՎ__'),
	'toc'                    => array( 0,    '__TOC__' , '__ԲՈՎ__' ),
	'noeditsection'          => array( 0,    '__NOEDITSECTION__', '__ԱՌԱՆՑ_ԲԱԺՆԻ_ԽՄԲԱԳՐՄԱՆ__' ),
	'currentmonth'           => array( 1,    'CURRENTMONTH', 'ԸՆԹԱՑԻՔ_ԱՄԻՍԸ' ),
	'currentmonthname'       => array( 1,    'CURRENTMONTHNAME', 'ԸՆԹԱՑԻՔ_ԱՄՍՎԱ_ԱՆՈՒՆԸ' ),
	'currentmonthnamegen'    => array( 1,    'CURRENTMONTHNAMEGEN', 'ԸՆԹԱՑԻՔ_ԱՄՍՎԱ_ԱՆՈՒՆԸ_ՍԵՌ' ),
	'currentmonthabbrev'     => array( 1,    'CURRENTMONTHABBREV', 'ԸՆԹԱՑԻՔ_ԱՄՍՎԱ_ԱՆՎԱՆ_ՀԱՊԱՎՈՒՄԸ' ),
	'currentday'             => array( 1,    'CURRENTDAY', 'ԸՆԹԱՑԻՔ_ՕՐԸ' ),
	'currentday2'            => array( 1,    'CURRENTDAY2', 'ԸՆԹԱՑԻՔ_ՕՐԸ_2' ),
	'currentdayname'         => array( 1,    'CURRENTDAYNAME', 'ԸՆԹԱՑԻՔ_ՕՐՎԱ_ԱՆՈՒՆԸ' ),
	'currentyear'            => array( 1,    'CURRENTYEAR', 'ԸՆԹԱՑԻՔ_ՏԱՐԻՆ' ),
	'currenttime'            => array( 1,    'CURRENTTIME', 'ԸՆԹԱՑԻՔ_ԺԱՄԱՆԱԿԸ' ),
	'currenthour'            => array( 1,    'CURRENTHOUR', 'ԸՆԹԱՑԻՔ_ԺԱՄԸ' ),
	'localmonth'             => array( 1,    'LOCALMONTH', 'ՏԵՂԱԿԱՆ_ԱՄԻՍԸ' ),
	'localmonthname'         => array( 1,    'LOCALMONTHNAME', 'ՏԵՂԱԿԱՆ_ԱՄՍՎԱ_ԱՆՈՒՆԸ' ),
	'localmonthnamegen'      => array( 1,    'LOCALMONTHNAMEGEN', 'ՏԵՂԱԿԱՆ_ԱՄՍՎԱ_ԱՆՈՒՆԸ_ՍԵՌ' ),
	'localmonthabbrev'       => array( 1,    'LOCALMONTHABBREV', 'ՏԵՂԱԿԱՆ_ԱՄՍՎԱ_ԱՆՎԱՆ_ՀԱՊԱՎՈՒՄԸ' ),
	'localday'               => array( 1,    'LOCALDAY', 'ՏԵՂԱԿԱՆ_ՕՐԸ' ),
	'localday2'              => array( 1,    'LOCALDAY2', 'ՏԵՂԱԿԱՆ_ՕՐԸ_2' ),
	'localdayname'           => array( 1,    'LOCALDAYNAME', 'ՏԵՂԱԿԱՆ_ՕՐՎԱ_ԱՆՈՒՆԸ' ),
	'localyear'              => array( 1,    'LOCALYEAR', 'ՏԵՂԱԿԱՆ_ՏԱՐԻՆ' ),
	'localtime'              => array( 1,    'LOCALTIME','ՏԵՂԱԿԱՆ_ԺԱՄԱՆԱԿԸ' ),
	'localhour'              => array( 1,    'LOCALHOUR','ՏԵՂԱԿԱՆ_ԺԱՄԸ' ),
	'numberofpages'          => array( 1,    'NUMBEROFPAGES','ԷՋԵՐԻ_ՔԱՆԱԿԸ' ),
	'numberofarticles'       => array( 1,    'NUMBEROFARTICLES','ՀՈԴՎԱԾՆԵՐԻ_ՔԱՆԱԿԸ' ),
	'numberoffiles'          => array( 1,    'NUMBEROFFILES','ՖԱՅԼԵՐԻ_ՔԱՆԱԿԸ' ),
	'numberofusers'          => array( 1,    'NUMBEROFUSERS','ՄԱՍՆԱԿԻՑՆԵՐԻ_ՔԱՆԱԿԸ' ),
	'pagename'               => array( 1,    'PAGENAME','ԷՋԻ_ԱՆՈՒՆԸ' ),
	'pagenamee'              => array( 1,    'PAGENAMEE','ԷՋԻ_ԱՆՈՒՆԸ_2' ),
	'namespace'              => array( 1,    'NAMESPACE','ԱՆՎԱՆԱՏԱՐԱԾՔ' ),
	'namespacee'             => array( 1,    'NAMESPACEE','ԱՆՎԱՆԱՏԱՐԱԾՔ_2' ),
	'talkspace'              => array( 1,    'TALKSPACE','ՔՆՆԱՐԿՄԱՆ_ՏԱՐԱԾՔԸ' ),
	'talkspacee'             => array( 1,    'TALKSPACEE','ՔՆՆԱՐԿՄԱՆ_ՏԱՐԱԾՔԸ_2' ),
	'subjectspace'           => array( 1,    'SUBJECTSPACE', 'ARTICLESPACE', 'ՀՈԴՎԱԾՆԵՐԻ_ՏԱՐԱԾՔԸ' ),
	'subjectspacee'          => array( 1,    'SUBJECTSPACEE', 'ARTICLESPACEE', 'ՀՈԴՎԱԾՆԵՐԻ_ՏԱՐԱԾՔԸ_2' ),
	'fullpagename'           => array( 1,    'FULLPAGENAME', 'ARTICLESPACE', 'ԷՋԻ_ԼՐԻՎ_ԱՆՎԱՆՈՒՄԸ' ),
	'fullpagenamee'          => array( 1,    'FULLPAGENAMEE', 'ԷՋԻ_ԼՐԻՎ_ԱՆՎԱՆՈՒՄԸ_2' ),
	'subpagename'            => array( 1,    'SUBPAGENAME', 'ԵՆԹԱԷՋԻ_ԱՆՎԱՆՈՒՄԸ' ),
	'subpagenamee'           => array( 1,    'SUBPAGENAMEE', 'ԵՆԹԱԷՋԻ_ԱՆՎԱՆՈՒՄԸ_2' ),
	'basepagename'           => array( 1,    'BASEPAGENAME', 'ՀԻՄՆԱԿԱՆ_ԷՋԻ_ԱՆՎԱՆՈՒՄԸ' ),
	'basepagenamee'          => array( 1,    'BASEPAGENAMEE', 'ՀԻՄՆԱԿԱՆ_ԷՋԻ_ԱՆՎԱՆՈՒՄԸ_2' ),
	'talkpagename'           => array( 1,    'TALKPAGENAME', 'ՔՆՆԱՐԿՄԱՆ_ԷՋԻ_ԱՆՎԱՆՈՒՄԸ' ),
	'talkpagenamee'          => array( 1,    'TALKPAGENAMEE', 'ՔՆՆԱՐԿՄԱՆ_ԷՋԻ_ԱՆՎԱՆՈՒՄԸ_2' ),
	'subjectpagename'        => array( 1,    'SUBJECTPAGENAME', 'ARTICLEPAGENAME', 'ՀՈԴՎԱԾԻ_ԷՋԻ_ԱՆՎԱՆՈՒՄԸ' ),
	'subjectpagenamee'       => array( 1,    'SUBJECTPAGENAMEE', 'ARTICLEPAGENAMEE', 'ՀՈԴՎԱԾԻ_ԷՋԻ_ԱՆՎԱՆՈՒՄԸ_2' ),
	'msg'                    => array( 0,    'MSG:', 'ՀՈՂՈՐԴ՝' ),
	'msgnw'                  => array( 0,    'MSGNW:', 'ՀՈՂՈՐԴ_ԱՌԱՆՑ_ՎԻՔԻԻ՝' ),
	'img_thumbnail'          => array( 1,    'thumbnail', 'thumb', 'մինի' ),
	'img_manualthumb'        => array( 1,    'thumbnail=$1', 'thumb=$1', 'մինի=$1'),
	'img_right'              => array( 1,    'right', 'աջից' ),
	'img_left'               => array( 1,    'left', 'ձախից' ),
	'img_none'               => array( 1,    'none', 'առանց' ),
	'img_width'              => array( 1,    '$1px', '$1փքս' ),
	'img_center'             => array( 1,    'center', 'centre', 'կենտրոն' ),
	'img_framed'             => array( 1,    'framed', 'enframed', 'frame', 'շրջափակել' ),
	'img_page'               => array( 1,    'page=$1', 'page $1', 'էջը=$1', 'էջ $1' ),
	'int'                    => array( 0,    'INT:' , 'ՆԵՐՔ՝' ),
	'sitename'               => array( 1,    'SITENAME', 'ԿԱՅՔԻ_ԱՆՈՒՆԸ' ),
	'ns'                     => array( 0,    'NS:', 'ԱՏ՝' ),
	'localurl'               => array( 0,    'LOCALURL:', 'ՏԵՂԱԿԱՆ_ՀԱՍՑԵՆ՝' ),
	'localurle'              => array( 0,    'LOCALURLE:', 'ՏԵՂԱԿԱՆ_ՀԱՍՑԵՆ_2՝' ),
	'server'                 => array( 0,    'SERVER', 'ՍԵՐՎԵՐԸ' ),
	'servername'             => array( 0,    'SERVERNAME', 'ՍԵՐՎԵՐԻ_ԱՆՈՒՆԸ' ),
	'scriptpath'             => array( 0,    'SCRIPTPATH', 'ՍՔՐԻՊՏԻ_ՃԱՆԱՊԱՐՀԸ' ),
	'grammar'                => array( 0,    'GRAMMAR:' , 'ՀՈԼՈՎ՛' ),
	'notitleconvert'         => array( 0,    '__NOTITLECONVERT__', '__NOTC__', '__ԱՌԱՆՑ_ՎԵՐՆԱԳՐԻ_ՓՈՓՈԽՄԱՆ__' ),
	'nocontentconvert'       => array( 0,    '__NOCONTENTCONVERT__', '__NOCC__', '__ԱՌԱՆՑ_ՊԱՐՈՒՆԱԿՈՒԹՅԱՆ_ՓՈՓՈԽՄԱՆ__' ),
	'currentweek'            => array( 1,    'CURRENTWEEK', 'ԸՆԹԱՑԻՔ_ՇԱԲԱԹԸ' ),
	'currentdow'             => array( 1,    'CURRENTDOW', 'ԸՆԹԱՑԻՔ_ՇԱԲԱԹՎԱ_ՕՐԸ' ),
	'localweek'              => array( 1,    'LOCALWEEK', 'ՏԵՂԱԿԱՆ_ՇԱԲԱԹՎԸ' ),
	'localdow'               => array( 1,    'LOCALDOW', 'ՏԵՂԱԿԱՆ_ՇԱԲԱԹՎԱ_ՕՐԸ' ),
	'revisionid'             => array( 1,    'REVISIONID', 'ՏԱՐԲԵՐԱԿԻ_ՀԱՄԱՐԸ' ),
	'revisionday'            => array( 1,    'REVISIONDAY', 'ՏԱՐԲԵՐԱԿԻ_ՕՐԸ' ),
	'revisionday2'           => array( 1,    'REVISIONDAY2', 'ՏԱՐԲԵՐԱԿԻ_ՕՐԸ_2' ),
	'revisionmonth'          => array( 1,    'REVISIONMONTH', 'ՏԱՐԲԵՐԱԿԻ_ԱՄԻՍԸ' ),
	'revisionyear'           => array( 1,    'REVISIONYEAR', 'ՏԱՐԲԵՐԱԿԻ_ՏԱՐԻՆ' ),
	'plural'                 => array( 0,    'PLURAL:', 'ՀՈԳՆԱԿԻ՝' ),
	'fullurl'                => array( 0,    'FULLURL:', 'ԼՐԻՎ_ՀԱՍՑԵՆ՝' ),
	'fullurle'               => array( 0,    'FULLURLE:', 'ԼՐԻՎ_ՀԱՍՑԵՆ_2՝' ),
	'lcfirst'                => array( 0,    'LCFIRST:', 'ՓՈՔՐԱՏԱՌ_ՍԿԶԲՆԱՏԱՌ՝' ),
	'ucfirst'                => array( 0,    'UCFIRST:', 'ՄԵԾԱՏԱՌ_ՍԿԶԲՆԱՏԱՌ՝' ),
	'lc'                     => array( 0,    'LC:', 'ՓՈՔՐԱՏԱՌ՝' ),
	'uc'                     => array( 0,    'UC:', 'ՄԵԾԱՏԱՌ՝' ),
	'displaytitle'           => array( 1,    'DISPLAYTITLE', 'ՑՈՒՅՑ_ՏԱԼ_ՎԵՐՆԱԳԻՐԸ' ),
	'rawsuffix'              => array( 1,    'R', 'Չ' ),
	'newsectionlink'         => array( 1,    '__NEWSECTIONLINK__', '__ՀՂՈՒՄ_ՆՈՐ_ԲԱԺՆԻ_ՎՐԱ__' ),
	'currentversion'         => array( 1,    'CURRENTVERSION', 'ԸՆԹԱՑԻՔ_ՏԱՐԲԵՐԱԿԸ' ),
	'urlencode'              => array( 0,    'URLENCODE:', 'ՄՇԱԿՎԱԾ_ՀԱՍՑԵ՛' ),
	'currenttimestamp'       => array( 1,    'CURRENTTIMESTAMP', 'ԸՆԹԱՑԻՔ_ԺԱՄԱՆԱԿԻ_ԴՐՈՇՄ' ),
	'localtimestamp'         => array( 1,    'LOCALTIMESTAMP', 'ՏԵՂԱԿԱՆ_ԺԱՄԱՆԱԿԻ_ԴՐՈՇՄ' ),
	'directionmark'          => array( 1,    'DIRECTIONMARK', 'DIRMARK', 'ՆԱՄԱԿԻ_ՈՒՂՂՈՒԹՅՈՒՆԸ' ),
	'language'               => array( 0,    '#LANGUAGE:', '#ԼԵԶՈՒ՝' ),
	'contentlanguage'        => array( 1,    'CONTENTLANGUAGE', 'CONTENTLANG', 'ՊԱՐՈՒՆԱԿՈՒԹՅԱՆ_ԼԵԶՈՒՆ' ),
	'pagesinnamespace'       => array( 1,    'PAGESINNAMESPACE:', 'PAGESINNS:', 'ԷՋԵՐ_ԱՆՎԱՆԱՏԱՐԱԾՔՈՒՄ՝' ),
	'numberofadmins'         => array( 1,    'NUMBEROFADMINS', 'ԱԴՄԻՆՆԵՐԻ_ՔԱՆԱԿԸ' ),
	'formatnum'              => array( 0,    'FORMATNUM', 'ՁԵՎԵԼ_ԹԻՎԸ' ),
	'padleft'                => array( 0,    'PADLEFT', 'ԼՐԱՑՆԵԼ_ՁԱԽԻՑ' ),
	'padright'               => array( 0,    'PADRIGHT', 'ԼՐԱՑՆԵԼ_ԱՋԻՑ' ),
	'special'                => array( 0,    'special', 'սպասարկող' ),
	'defaultsort'			 => array( 1,	 'DEFAULTSORT:', 'ԼՌՈՒԹՅԱՄԲ_ԴԱՍԱՎՈՐՈՒՄ՝' ),
);

$specialPageAliases = array(
	'DoubleRedirects'           => array( 'Կրկնակիվերահղումները' ),
	'BrokenRedirects'           => array( 'Կոտրվածվերահղումները' ),
	'Disambiguations'           => array( 'Երկիմաստէջերը' ),
	'Userlogin'                 => array( 'Մասնակցիմուտք' ),
	'Userlogout'                => array( 'Մասնակցիելք' ),
	'Preferences'               => array( 'Նախընտրությունները' ),
	'Watchlist'                 => array( 'Հսկողությանցանկը' ),
	'Recentchanges'             => array( 'Վերջինփոփոխությունները' ),
	'Upload'                    => array( 'Բեռնել' ),
	'Imagelist'                 => array( 'Պատկերներիցանկը' ),
	'Newimages'                 => array( 'Նորպատկերներ' ),
	'Listusers'                 => array( 'Մասնակիցներիցանկը' ),
	'Statistics'                => array( 'Վիճակագրություն' ),
	'Randompage'                => array( 'Պատահականէջ' ),
	'Lonelypages'               => array( 'Միայնակէջերը' ),
	'Uncategorizedpages'        => array( 'Չդասակարգվածէջերը' ),
	'Uncategorizedcategories'   => array( 'Չդասակարգվածկատեգորիաները' ),
	'Uncategorizedimages'       => array( 'Չդասակարգվածպատկերները' ),
	'Unusedcategories'          => array( 'Չօգտագործվածկատեգորիաները' ),
	'Unusedimages'              => array( 'Չօգտագործվածպատկերները' ),
	'Wantedpages'               => array( 'Անհրաժեշտէջերը' ),
	'Wantedcategories'          => array( 'Անհրաժեշտկատեգորիաները' ),
	'Mostlinked'                => array( 'Ամենաշատհղումներով' ),
	'Mostlinkedcategories'      => array( 'Շատհղվողկատեգորիաները' ),
	'Mostcategories'            => array( 'Ամենաշատկատեգորիաներով' ),
	'Mostimages'                => array( 'Ամենաշատօգտագործվողնկարները' ),
	'Mostrevisions'             => array( 'Ամենաշատփոփոխվող' ),
	'Shortpages'                => array( 'Կարճէջերը' ),
	'Longpages'                 => array( 'Երկարէջերը' ),
	'Newpages'                  => array( 'Նորէջերը' ),
	'Ancientpages'              => array( 'Ամենահինէջերը' ),
	'Deadendpages'              => array( 'Հղումչպարունակողէջերը' ),
	'Allpages'                  => array( 'Բոլորէջերը' ),
	'Prefixindex'               => array( 'Որոնումնախածանցով' ) ,
	'Ipblocklist'               => array( 'ԱրգելափակվածIPները' ),
	'Specialpages'              => array( 'Սպասարկողէջերը' ),
	'Contributions'             => array( 'Ներդրումները' ),
	'Emailuser'                 => array( 'Գրելնամակ' ),
	'Whatlinkshere'             => array( 'Այստեղհղվողէջերը' ),
	'Recentchangeslinked'       => array( 'Կապվածէջերիփոփոխությունները' ),
	'Movepage'                  => array( 'Տեղափոխելէջը' ),
	'Blockme'                   => array( 'Արգելափակել' ),
	'Booksources'               => array( 'Գրքայինաղբյուրները' ),
	'Categories'                => array( 'Կատեգորիաները' ),
	'Export'                    => array( 'Արտահանելէջերը' ),
	'Version'                   => array( 'Տարբերակ' ),
	'Allmessages'               => array( 'Բոլորուղերձները' ),
	'Log'                       => array( 'Տեղեկամատյան' ),
	'Blockip'                   => array( 'Արգելափակելip' ),
	'Undelete'                  => array( 'Վերականգնել' ),
	'Import'                    => array( 'Ներմուծել' ),
	'Lockdb'                    => array( 'Կողպելտհ' ),
	'Unlockdb'                  => array( 'Բացանելտհ' ),
	'Userrights'                => array( 'Մասնակցիիրավունքները' ),
	'MIMEsearch'                => array( 'MIMEՈրոնում' ),
	'Unwatchedpages'            => array( 'Չհսկվողէջերը' ),
	'Listredirects'             => array( 'Ցույցտալվերահղումները' ),
	'Listinterwikis'            => array( 'Ցույցտալինտերվիքիները' ),
	'Revisiondelete'            => array( 'Տարբերակիհեռացում' ),
	'Unusedtemplates'           => array( 'Չօգտագործվողկաղապարները' ),
	'Randomredirect'            => array( 'Պատահականվերահղում' ),
	'Mypage'                    => array( 'Իմէջը' ),
	'Mytalk'                    => array( 'Իմքննարկումները' ),
	'Mycontributions'           => array( 'Իմներդրումները' ),
	'Listadmins'                => array( 'Ադմիններիցանկը' ),
	'Popularpages'              => array( 'Հանրաճանաչէջերը' ),
	'Search'                    => array( 'Որոնել' ),
	'Resetpass'                 => array( 'Նորգաղտնաբառ' ),
);

$linkTrail = '/^([a-zաբգդեզէըթժիլխծկհձղճմյնշոչպջռսվտրցւփքօֆև«»]+)(.*)$/sDu';

$messages = array(
# User preference toggles
'tog-underline'               => 'ընդգծել հղումները՝',
'tog-highlightbroken'         => 'Ցույց տալ չգործող հղումները <a href="" class="new">այսպես</a> (այլապես՝ այսպես<a href="" class="internal">(՞)</a>)։',
'tog-justify'                 => 'Հավասարացնել տեքստը էջի լայնությամբ',
'tog-hideminor'               => 'Թաքցնել չնչին խմբագրումները վերջին փոփոխությունների ցանկից',
'tog-extendwatchlist'         => 'Ընդարձակել հսկացանկը՝ ցույց տալով բոլոր փոփոխությունները',
'tog-usenewrc'                => 'Վերջին փոփոխությունների լավացված ցանկ (JavaScript)',
'tog-numberheadings'          => 'Ինքնաթվագրել վերնագրերը',
'tog-showtoolbar'             => 'Ցույց տալ խմբագրումների գործիքների վահանակը (JavaScript)',
'tog-editondblclick'          => 'Խմբագրել էջերը կրկնակի մատնահարմամբ (JavaScript)',
'tog-editsection'             => 'Ցույց տալ [խմբագրել] հղումը ամեն բաժնի համար',
'tog-editsectiononrightclick' => 'Խմբագրել բաժինները վերնագրի աջ մատնահարմամբ (JavaScript)',
'tog-showtoc'                 => 'Ցույց տալ բովանդակությունը (3  կամ ավել վերնագրեր ունեցող էջերի համար)',
'tog-rememberpassword'        => 'Հիշել իմ մասնակցի հաշիվը այս համակարգչում',
'tog-editwidth'               => 'Խմբագրման դաշտը պատուհանի ամբողջ լայնությամբ',
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
'tog-fancysig'                => 'Հասարակ ստորագրություն (առանց ավտոմատ հղման)',
'tog-externaleditor'          => 'Օգտագործել արտաքին խմբագրիչ ըստ լռության',
'tog-externaldiff'            => 'Օգտագործել տարբերակների համեմատման արտաքին ծրագիր ըստ լռության',
'tog-showjumplinks'           => 'Միացնել «անցնել դեպի» օգնական հղումները',
'tog-uselivepreview'          => 'Օգտագործել ուղիղ նախադիտում (JavaScript) (Փորձնական)',
'tog-forceeditsummary'        => 'Նախազգուշացնել փոփոխությունների ամփոփումը դատարկ թողնելու մասին',
'tog-watchlisthideown'        => 'Թաքցնել իմ խմբագրումները հսկացանկից',
'tog-watchlisthidebots'       => 'Թաքցնել բոտերի խմբագրումները հսկացանկից',
'tog-watchlisthideminor'      => 'Թաքցնել չնչին խմբագրումները հսկացանկից',
'tog-nolangconversion'        => 'Անջատել գրի համակարգի փոփոխումը',
'tog-ccmeonemails'            => 'Ուղարկել ինձ իմ կողմից մյուս մասնակիցներին ուղարկված նամակների պատճեններ',
'tog-diffonly'                => 'Չցուցադրել էջի պարունակությունը տարբերությունների ներքևից',

'underline-always'  => 'Միշտ',
'underline-never'   => 'Երբեք',
'underline-default' => 'Օգտագործել բրաուզերի նախընտրությունները',

'skinpreview' => '(նախադիտել)',

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

# Bits of text used by many pages
'categories'            => 'Կատեգորիաներ',
'pagecategories'        => '{{PLURAL:$1|Կատեգորիա|Կատեգորիաներ}}',
'category_header'       => '«$1» կատեգորիայի հոդվածները',
'subcategories'         => 'Ենթակատեգորիաներ',
'category-media-header' => '"$1" կատեգորիայի մեդիան։',
'category-empty'        => "''Այս կատեգորիան ներկայումս դատարկ է։''",

'mainpagetext'      => "<big>'''«MediaWiki» ծրագիրը հաջողությամբ տեղադրվեց։'''</big>",
'mainpagedocfooter' => "Այցելեք [http://meta.wikimedia.org/wiki/Help:Contents User's Guide]՝ վիքի ծրագրային ապահովման օգտագործման մասին տեղեկությունների համար։

== Որոշ օգտակար ռեսուրսներ ==

* [http://www.mediawiki.org/wiki/Manual:Configuration_settings Configuration settings list]
* [http://www.mediawiki.org/wiki/Manual:FAQ MediaWiki FAQ]
* [http://lists.wikimedia.org/mailman/listinfo/mediawiki-announce MediaWiki release mailing list]",

'about'          => 'Էությունը',
'article'        => 'Հոդված',
'newwindow'      => '(բացվելու է նոր պատուհանի մեջ)',
'cancel'         => 'Բեկանել',
'qbfind'         => 'Գտնել',
'qbbrowse'       => 'Թերթել',
'qbedit'         => 'Խմբագրել',
'qbpageoptions'  => 'Այս էջը',
'qbpageinfo'     => 'Հոդվածի մասին',
'qbmyoptions'    => 'Իմ էջերը',
'qbspecialpages' => 'Սպասարկող էջեր',
'moredotdotdot'  => 'Ավելին...',
'mypage'         => 'Իմ էջը',
'mytalk'         => 'Իմ քննարկումները',
'anontalk'       => 'Քննարկում այս IP-հասցեի համար',
'navigation'     => 'Շրջել կայքում',

# Metadata in edit box
'metadata_help' => 'Մետատվյալներ։',

'errorpagetitle'    => 'Սխալ',
'returnto'          => 'Վերադառնալ $1։',
'tagline'           => '{{SITENAME}}յից՝ ազատ հանրագիտարանից',
'help'              => 'Օգնություն',
'search'            => 'Որոնել',
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
'editthispage'      => 'Խմբագրել այս էջը',
'delete'            => 'Ջնջել',
'deletethispage'    => 'Ջնջել այս էջը',
'undelete_short'    => 'Վերականգնել {{PLURAL:$1|մեկ խմբագրում|$1 խմբագրում}}',
'protect'           => 'Պաշտպանել',
'protect_change'    => 'փոխել պաշտպանումը',
'protectthispage'   => 'Պաշտպանել այս էջը',
'unprotect'         => 'Հանել պաշտպանությունից',
'unprotectthispage' => 'Հանել այս էջը պաշտպանությունից',
'newpage'           => 'Նոր էջ',
'talkpage'          => 'Քննարկել այս էջը',
'talkpagelinktext'  => 'Քննարկում',
'specialpage'       => 'Սպասարկող էջ',
'personaltools'     => 'Անձնական գործիքներ',
'postcomment'       => 'Մեկնաբանել',
'articlepage'       => 'Դիտել հոդվածը',
'talk'              => 'Քննարկում',
'views'             => 'Դիտումները',
'toolbox'           => 'Գործիքներ',
'userpage'          => 'Դիտել մասնակցի էջը',
'projectpage'       => 'Դիտել նախագծի էջը',
'imagepage'         => 'Դիտել պատկերի էջը',
'mediawikipage'     => 'Դիտել ուղերձի էջը',
'templatepage'      => 'Դիտել կաղապարի էջը',
'viewhelppage'      => 'Դիտել օգնության էջը',
'categorypage'      => 'Դիտել կատեգորիայի էջը',
'viewtalkpage'      => 'Դիտել քննարկումը',
'otherlanguages'    => 'Այլ լեզուներով',
'redirectedfrom'    => '(Վերահղված է $1-ից)',
'redirectpagesub'   => 'Վերահղման էջ',
'lastmodifiedat'    => 'Այս էջը վերջին անգամ փոփոխվել է $2, $1։', # $1 date, $2 time
'viewcount'         => 'Այս էջին դիմել են {{plural:$1|մեկ անգամ|$1 անգամ}}։',
'protectedpage'     => 'Պաշտպանված էջ',
'jumpto'            => 'Անցնել՝',
'jumptonavigation'  => 'նավարկություն',
'jumptosearch'      => 'որոնում',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'         => '{{grammar:genitive|{{SITENAME}}}} մասին',
'aboutpage'         => '{{ns:project}}:Էությունը',
'bugreports'        => 'Սխալի զեկուցում',
'bugreportspage'    => '{{ns:project}}Սխալների զեկուցում',
'copyright'         => 'Կայքի բովանդակությունը գտնվում է «$1» լիցենզիայի տակ։',
'copyrightpagename' => '{{SITENAME}} հեղինակային իրավունքները',
'copyrightpage'     => '{{ns:project}}:Հեղինակային իրավունքներ',
'currentevents'     => 'Ընթացիկ իրադարձություններ',
'currentevents-url' => '{{ns:project}}:Ընթացիկ իրադարձություններ',
'disclaimers'       => 'Ազատում պատասխանատվությունից',
'disclaimerpage'    => '{{ns:project}}:Ազատում պատասխանատվությունից',
'edithelp'          => 'Խմբագրման ուղեցույց',
'edithelppage'      => '{{ns:help}}:Խմբագրում',
'faq'               => 'ՀՏՀ',
'faqpage'           => '{{ns:project}}:ՀՏՀ',
'helppage'          => '{{ns:project}}:Գլխացանկ',
'mainpage'          => 'Գլխավոր Էջ',
'policy-url'        => '{{ns:project}}:Կանոնակարգ',
'portal'            => 'Խորհրդարան',
'portal-url'        => '{{ns:project}}:Խորհրդարան',
'privacy'           => 'Գաղտնիության քաղաքականություն',
'privacypage'       => '{{ns:project}}:Գաղտնիության քաղաքականություն',
'sitesupport'       => 'Դրամական նվիրատվություն',
'sitesupport-url'   => '{{ns:project}}:Դրամական նվիրատվություն',

'badaccess'        => 'Թույլատրման սխալ',
'badaccess-group0' => 'Ձեզ չի թույլատրվում կատարել տվյալ գործողությունը։',
'badaccess-group1' => 'Տվյալ գործողությունը կարող են կատարել միայն $1 խմբի մասնակիցները։',
'badaccess-group2' => 'Տվյալ գործողությունը կարող են կատարել միայն $1 խմբերի մասնակիցները։',
'badaccess-groups' => 'Տվյալ գործողությունը կարող են կատարել միայն $1 խմբերի մասնակիցները։',

'versionrequired'     => 'Պահանջվում է MediaWiki ծրագրի $1 տարբերակը',
'versionrequiredtext' => 'Այս էջի օգտագործման համար պահանջվում է MediaWiki ծրագրի $1 տարբերակը։ Տես [[{{ns:special}}:Version|տարբերակի էջը]]։',

'ok'                      => 'OK',
'retrievedfrom'           => 'Ստացված է «$1» էջից',
'youhavenewmessages'      => 'Դուք ունեք $1 ($2)։',
'newmessageslink'         => 'նոր ուղերձներ',
'newmessagesdifflink'     => 'վերջին փոփոխությունը',
'youhavenewmessagesmulti' => 'Դուք նոր ուղերձներ եք ստացել $1 վրա',
'editsection'             => 'խմբագրել',
'editold'                 => 'խմբագրել',
'editsectionhint'         => 'Խմբագրել բաժինը. $1',
'toc'                     => 'Բովանդակություն',
'showtoc'                 => 'ցույց տալ',
'hidetoc'                 => 'թաքցնել',
'thisisdeleted'           => 'Դիտե՞լ կամ վերականգնե՞լ $1։',
'viewdeleted'             => 'Դիտե՞լ $1։',
'restorelink'             => '{{PLURAL:$1|մեկ ջնջված խմբագրում|$1 ջնջված խմբագրում}}',
'feedlinks'               => 'Սնուցման տեսակ.',
'feed-invalid'            => 'Սխալ սնուցման տեսակ։',
'site-rss-feed'           => '$1 RSS Սնուցում',
'site-atom-feed'          => '$1 Atom Սնուցում',
'page-rss-feed'           => '«$1» RSS Սնուցում',
'page-atom-feed'          => '«$1» Atom Սնուցում',

# Short words for each namespace, by default used in the 'article' tab in monobook
'nstab-main'      => 'Հոդված',
'nstab-user'      => 'Մասնակցի էջ',
'nstab-media'     => 'Մեդիա էջ',
'nstab-special'   => 'Սպասարկող էջ',
'nstab-project'   => 'Նախագծի էջ',
'nstab-image'     => 'Ֆայլ',
'nstab-mediawiki' => 'Ուղերձ',
'nstab-template'  => 'Կաղապար',
'nstab-help'      => 'Օգնության էջ',
'nstab-category'  => 'Կատեգորիա',

# Main script and global functions
'nosuchaction'      => 'Նման գործողություն չկա',
'nosuchactiontext'  => 'URL-ում նշված գործողությունը չի ճանաչվում վիքիի ծրագրի կողմից',
'nosuchspecialpage' => 'Նման սպասարկող էջ չկա',
'nospecialpagetext' => "'''<big>Ձեր հայցված սպասարկող էջը գոյություն չունի։</big>'''

Տեսեք [[{{ns:special}}:Specialpages|սպասարկող էջերի ցանկը]]։",

# General errors
'error'                => 'Սխալ',
'databaseerror'        => 'Տվյալների բազայի սխալ',
'dberrortext'          => 'Հայտնաբերվել է տվյալների բազային հայցի շարահյուսության սխալ։
Սա կարող է լինել ծրագրային ապահովման սխալից։
Տվյալների բազային վերջին հայցն էր.
<blockquote><tt>$1</tt></blockquote>
հետևյալ ֆունկցիայի մարմնից <tt>«$2»</tt>։
MySQL-ի վերադարձրած սխալն է. <tt>«$3: $4»</tt>։',
'dberrortextcl'        => 'Հայտնաբերվել է տվյալների բազային հայցի շարահյուսության սխալ։
Տվյալների բազային վերջին հայցն էր.
«$1»
հետևյալ ֆունկցիայի մարմնից <tt>«$2»</tt>։
MySQL-ի վերադարձրած սխալն է. <tt>«$3: $4»</tt>։',
'noconnect'            => 'Ներեցե՜ք։ Այս վիքիի տեխնիկական դժվարությունների պատճառով անհնար է կապվել տվյալների բազայի սերվերի հետ։<br />
$1',
'nodb'                 => 'Չհաջողվեց ընտրել $1 տվյալների բազան',
'cachederror'          => 'Ստորև բերված է հարցված էջի պահեստավորված պատճեն, որը կարող է հնացած լինել։',
'laggedslavemode'      => 'Զգուշացում. էջը կարող է չպարունակել վերջին փոփոխությունները։',
'readonly'             => 'Տվյալների բազան կողպված է',
'enterlockreason'      => 'Նշեք կողպման պատճառը և մոտավոր ժամկետը',
'readonlytext'         => 'Տվյալների բազան ներկայումս կողպված է նոր էջերի և փոփոխությունների համար՝ հավանաբար նախատեսված սպասարկման կապակցությամբ, որից հետո այն կվերադարձվի սովորական վիճակի։

Ադմինիստրատորը, որը կողպել է այն, թողել է հետևյալ բացատրությունը.
$1',
'missingarticle'       => 'Տվյալների բազան չկարողացավ գտնել էջի տեքստը՝ «$1» անվանմամբ, որը պետք է գտներ։

Սովորաբար սա կապված է հնացած հղման հետևանքով դեպի տարբերությունները կամ պատմությունը մի էջի, որը ջնջվել է։

Եթե սա չէ պատճառը, ապա գուցե դուք հայտնաբերել եք ծրագրային ապահովման սխալ։
Խնդրում ենք հայտնել սրա մասին ադմինիստրատորին՝ նշելով URL-հասցեն։',
'readonly_lag'         => 'Տվյալների բազան ավտոմատիկ կողպվել է ժամանակավորապես՝ մինչև ՏԲ-ի երկրորդական սերվերը չհամաժամանակեցվի առաջնայինի հետ։',
'internalerror'        => 'Ներքին սխալ',
'internalerror_info'   => 'Ներքին սխալ. $1',
'filecopyerror'        => 'Չհաջողվեց պատճենել «$1» ֆայլը «$2» ֆայլի մեջ։',
'filerenameerror'      => 'Չհաջողվեց «$1» ֆայլը վերանվանել «$2»։',
'filedeleteerror'      => 'Չհաջողվեց ջնջել «$1» ֆայլը։',
'directorycreateerror' => 'Չհաջողվեց ստեղծել «$1» պանակը։',
'filenotfound'         => 'Չհաջողվեց գտնել «$1» ֆայլը։',
'fileexistserror'      => 'Չհաջողվեց գրել«$1» ֆայլին. ֆայլը գոյություն ունի։',
'unexpected'           => 'Անսպասելի արժեք. «$1»=«$2»։',
'formerror'            => 'Սխալ. չհաջողվեց փոխանցել տվյալները',
'badarticleerror'      => 'Տվյալ գործողությունը չի կարող կատարվել այս էջում։',
'cannotdelete'         => 'Չհաջողվեց ջնջել նշված էջը կամ ֆայլը։ (Հնարավոր է այն արդեն ջնջված է այլ մասնակցի կողմից։)',
'badtitle'             => 'Անընդունելի անվանում',
'badtitletext'         => 'Հարցված էջի անվանումը անընդունելի է, դատարկ է կամ սխալ միջ-լեզվական կամ ինտերվիքի անվանում է։ Հնարավոր է, որ այն պարունակում է անթույլատրելի սիմվոլներ։',
'perfdisabled'         => 'Ներեցե՜ք։ Այս հնարավորությունը ժամանակավորապես անջատված է սերվերի գերբեռնվածության պատճառով։',
'perfcached'           => 'Հետևյալ տվյալները վերցված են քեշից և հնարավոր է չարտացոլեն վերջին փոփոխությունները։',
'perfcachedts'         => 'Հետևյալ տվյալները վերցված են քեշից և վերջին անգամ թարմացվել են $1։',
'querypage-no-updates' => 'Այս էջի փոփոխությունները ներկայումս արգելված են։ Այստեղի տվյալները այժմ չեն թարմացվի։',
'wrong_wfQuery_params' => 'Անթույլատրելի պարամետրեր wfQuery() ֆունկցիայի համար<br />
Ֆունկցիա. $1<br />
Հայցում. $2',
'viewsource'           => 'Դիտել ելատեքստը',
'viewsourcefor'        => '«$1» էջի',
'protectedpagetext'    => 'Այս էջը կողպված խմբագրման համար։',
'viewsourcetext'       => 'Դուք կարող եք դիտել և պատճենել այս էջի ելատեքստը.',
'protectedinterface'   => 'Այս էջը պարունակում է ծրագրային ապահովման ինտերֆեյսի ուզերձ և կողպված է չարաշահումների կանխարգելման նպատակով։.',
'editinginterface'     => "'''Զգուշացում.''' Դուք խմբագրում եք ծրագրային ապահովման ինտերֆեյսի տեքստ պարունակող էջ։ Այս էջի փոփոխությունը կանդրադառնա այլ մասնակիցներին տեսանելի ինտերֆեյսի տեսքի վրա։",
'sqlhidden'            => '(SQL հայցումը թաքցված է)',
'cascadeprotected'     => 'Այս էջը պաշտպանված է խմբագրումից, քանի որ ընդգրկված է հետևյալ էջերի տեքստում, որոնք պաշտպանվել են կասկադային հնարավորությամբ.
$2',
'namespaceprotected'   => 'Դուք չունեք «$1» անվանատարածքի էջերի խմբագրման իրավունք։',
'customcssjsprotected' => 'Դուք չունեք այս էջի խմբագրման իրավունք, քանի որ այն պարունակում է այլ մասնակցի անձնական նախընտրություններ։',
'ns-specialprotected'  => '«{{ns:special}}» անվանատարածքի էջերը չեն կարող խմբագրվել։',

# Login and logout pages
'logouttitle'                => 'Մասնակցի ելք',
'logouttext'                 => '<strong>Դուք դուրս եկաք համակարգից։</strong><br />
Դուք կարող եք շարունակել օգտագործել {{SITENAME}} կայքը անանուն, կամ կրկին մուտք գործել համակարգ՝ որպես նույն՝ կամ մեկ այլ մասնակից։ Ի նկատի ունեցեք, որ որոշ էջեր կարող են ցուցադրվել այնպես՝ ինչպես եթե դեռ համակարգում լինեիք մինչև որ չջնջեք ձեր բրաուզերի քէշը։',
'welcomecreation'            => '== Բարի գալուստ, $1 ==

Ձեր հաշիվը ստեղծված է։ Չմոռանաք անձնավորել ձեր [[Special:Preferences|նախընտրությունները]]։',
'loginpagetitle'             => 'Մասնակցի գրանցում',
'yourname'                   => 'Մասնակցի անուն.',
'yourpassword'               => 'Գաղտնաբառ.',
'yourpasswordagain'          => 'Կրկնեք գաղտնաբառը.',
'remembermypassword'         => 'Հիշել իմ մուտքագրված տվյալները',
'yourdomainname'             => 'Ձեր դոմենը.',
'externaldberror'            => 'Տեղի է ունեցել վավերացման արտաքին տվյալների բազայի սխալ, կամ դուք չունեք բավարար իրավունքներ ձեր արտաքին հաշվի փոփոխման համար։',
'loginproblem'               => '<b>Մուտքը համակարգ չհաջողվեց։</b><br /> Փորձեք կրկին։',
'login'                      => 'Մտնել',
'loginprompt'                => '{{SITENAME}} մուտք գործելու համար հարկավոր է քուքիները թույլատրել։',
'userlogin'                  => 'Մտնել / Գրանցվել',
'logout'                     => 'Ելնել',
'userlogout'                 => 'Ելնել',
'notloggedin'                => 'Դուք չեք մտել համակարգ',
'nologin'                    => 'Դեռևս չե՞ք գրանցվել։ $1։',
'nologinlink'                => 'Ստեղծեք մասնակցային հաշիվ',
'createaccount'              => 'Ստեղծել նոր մասնակցային հաշիվ',
'gotaccount'                 => 'Դուք արդեն գրանցվա՞ծ եք։ $1։',
'gotaccountlink'             => 'Մուտք գործեք համակարգ',
'createaccountmail'          => 'էլ-փոստով',
'badretype'                  => 'Ձեր մուտքագրած գաղտնաբառերը չեն համընկնում։',
'userexists'                 => 'Այս մասնակցի անունը արդեն զբաղված է։ Խնդրում ենք ընտրել մեկ այլ անուն։',
'youremail'                  => 'Էլեկտրոնային փոստ.',
'username'                   => 'Մասնակցի անուն.',
'uid'                        => 'Մասնակցի իդենտիֆիկատոր.',
'yourrealname'               => 'Ձեր իրական անունը.',
'yourlanguage'               => 'Ինտերֆեյսի լեզուն.',
'yourvariant'                => 'Լեզվական տարբերակ',
'yournick'                   => 'Մականուն.',
'badsig'                     => 'Սխալ ստորագրություն. ստուգեք HTML-թեգերը։',
'badsiglength'               => 'Մականունը շատ երկար է, այն չպետք է գերազանցի $1 սիմվոլից։',
'email'                      => 'Էլ-փոստ',
'prefs-help-realname'        => 'Իրական անունը պարտադիր չէ, սակայն եթե դուք նշեք դա, ապա այն կօգտագործվի ձեր փոփոխությունների իրական անվանը վերագրման համար։',
'loginerror'                 => 'Մասնակցի մուտքի սխալ',
'prefs-help-email'           => 'Էլեկտրոնային փոստի մուտքագրումը պարտադիր չէ, սակայն սա թույլ կտա մյուս մասնակիցներին կապնվել ձեզ հետ ձեր մասնակցի կամ մասնակցի քննարկման էջի միջոցով՝ առանց ձեր անձի կամ ձեր էլեկտրոնային հասցեի բացահայտման։',
'nocookiesnew'               => 'Մասնակցային հաշիվը ստեղծված է, սակայն մուտքը համակարգ չհաջողվեց։ {{SITENAME}} կայքը օգտագործում է «քուքիներ» մասնակիցների վավերացման համար։ Ձեր մոտ «քուքիները» արգելված են։ Խնդրում ենք թույլատրել սրանք, ապա մտնել համակարգ ձեր նոր մասնակցի անունով և գաղտնաբառով։',
'nocookieslogin'             => '{{SITENAME}} կայքը օգտագործում է «քուքիներ» մասնակիցների վավերացման համար։ Ձեր մոտ «քուքիները» արգելված են։ Խնդրում ենք թույլատրել սրանք և փորձել կրկին։',
'noname'                     => 'Դուք չեք նշել թույլատրելի մասնակցային անուն։',
'loginsuccesstitle'          => 'Բարեհաջող մուտք',
'loginsuccess'               => "'''Դուք մուտք գործեցիք {{SITENAME}}, որպես \"\$1\"։'''",
'nosuchuser'                 => '$1 անունով մասնակից գոյություն չունի։
Ստուգեք ձեր ուղղագրությունը կամ գրանցեք նոր հաշիվ ստորև փակցված ձևով։',
'nosuchusershort'            => '$1 անունով մասնակից գոյություն չունի։ Ստուգեք ձեր ուղղագրությունը։',
'nouserspecified'            => 'Հարկավոր է նշել մասնակցային անուն։',
'wrongpassword'              => 'Մուտքագրված գաղտնաբառը սխալ էր։ Խնդրում ենք կրկին փորձել։',
'wrongpasswordempty'         => 'Մուտքագրված գաղտնաբառը դատարկ էր։ Խնդրում ենք կրկին փորձել։',
'passwordtooshort'           => 'Մուտքագրված գաղտնաբառը անթույլատրելի է կամ շատ կարճ։ Այն պետք է պարունակի առնվազն $1 սիմվոլ և տարբերվի մասնակցի անունից։',
'mailmypassword'             => 'Ուղարկել նոր գաղտնաբառ էլ–փոստով',
'passwordremindertitle'      => '{{grammar:genitive|{{SITENAME}}}} մասնակցի գաղտնաբառի հիշեցում',
'passwordremindertext'       => 'Ինչ-որ մեկը (հավանաբար դուք՝ $1  IP-հասցեից) խնդրել է, որպեսզի մենք ուղարկենք ձեզ նոր գաղտնաբառ {{grammar:genitive|{{SITENAME}}}} մասնակցի համար ($4)։
$2 մասնակցի նոր գաղտնաբառն է՝ <code>$3</code>։
Ձեզ հարկավոր է մտնել համակարգ և փոխել գաղտնաբառը։

Եթե դուք չեք արել այսպիսի հայցում կամ արդեն հիշել եք ձեր գաղտնաբառը, ապա կարող եք անտեսել այս ուղերձը և շարունակել օգտվել ձեր հին գաղտնաբառից։',
'noemail'                    => '«$1» մասնակցի համար էլ-փոստի հասցե չի նշվել։',
'passwordsent'               => 'Նոր գաղտնաբառ է ուղարկվել $1 մասնակցի համար նշված էլ-փոստի հասցեին։

Խնդրում ենք կրկին ներկայանալ համակարգին այն ստանալուց հետո։',
'blocked-mailpassword'       => 'Ձեր IP հասցեից խմբագրումները արգելափակված են, և հետևաբար արգելված է նաև գաղտնաբառի վերականգնումը՝ հետագա չարաշահումների կանխման նպատակով։',
'eauthentsent'               => 'Առաջարկված էլ-հասցեին ուղարկվել է վավերացման նամակ։
Մինչև որևէ այլ ուղերջներ կուղարկվեն այդ հասցեին, ձեզ անհրաժեշտ է հետևել նամակում նկարագրված գործողություններին՝ հաշվի ձեզ պատկանելու փաստը վավերացնելու համար։',
'throttled-mailpassword'     => 'Գաղտնաբառի հիշեցման ուղերձ արդեն ուղարկվել է վերջին $1 ժամվա ընթացքում։ Չարաշահման կանխարգելման նպատակով թույլատրվում է միայն մեկ գաղտնաբառի հիշեցում ամեն $1 ժամվա ընթացքում։',
'mailerror'                  => 'Փոստի ուղարկման սխալ. $1',
'acct_creation_throttle_hit' => 'Ներեցեք, դուք արդեն ստեղծել եք $1 մասնակցային հաշիվ։ Չեք կարող ավելին ստեղծել։',
'emailauthenticated'         => 'Ձեր էլ-փոստի հասցեն վավերացվել է $1-ին։',
'emailnotauthenticated'      => 'Ձեր էլ-փոստի հասցեն դեռ վավերացված չէ։ Հետևյալ հնարավորությունների գործածումը անջատված է։',
'noemailprefs'               => 'Այս հնարավորության գործածման համար անհրաժեշտ է նշել էլ-փոստի հասցե։',
'emailconfirmlink'           => 'Վավերացնել ձեր էլ-փոստի հասցեն',
'invalidemailaddress'        => 'Նշված էլ-փոստի հասցեն անընդունելի է, քանի որ այն ունի անթույլատրելի ֆորմատ։ Խնդրում ենք նշել ճշմարիտ հասցե կամ այս դաշտը թողնել դատարկ։',
'accountcreated'             => 'Հաշիվը ստեղծված է',
'accountcreatedtext'         => '$1 մասնակցի հաշիվը ստեղծված է։',
'loginlanguagelabel'         => 'Լեզու. $1',

# Password reset dialog
'resetpass'               => 'Վերականգնել հաշվի գաղտնաբառը',
'resetpass_announce'      => 'Դուք ներկայացել եք էլ-փոստով ստացված ժամանակավոր գաղտնաբառով։ Համակարգ մուտքի համար անհրաժեշտ է նոր գաղտնաբառ ընտրել այստեղ.',
'resetpass_text'          => '<!-- Ավելացնել տեքստը այստեղ -->',
'resetpass_header'        => 'Վերականգնել գաղտնաբառը',
'resetpass_submit'        => 'Հաստատել գաղտնաբառը և մտնել համակարգ',
'resetpass_success'       => 'Ձեր գաղտնաբառը փոխված է։ Մուտք համակարգ…',
'resetpass_bad_temporary' => 'Ժամանակավոր գաղտնաբառը սխալ է։ Հնարավոր է դուք արդեն փոխել եք գաղտնաբառը կամ նորն եք հայցել։',
'resetpass_forbidden'     => 'Այս վիքիում գաղտնաբառի փոփոխություն չի թույլատրվում',
'resetpass_missing'       => 'Ձևը տվյալներ չի պարունակում։',

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
'math_sample'     => 'Գրեք բանաձևը այստեղ',
'math_tip'        => 'Մաթեմատիկական բանաձև (LaTeX)',
'nowiki_sample'   => 'Մուտքագրեք չձևավորված տեքստը այստեղ',
'nowiki_tip'      => 'Անտեսել վիքի ձևավորումը',
'image_sample'    => 'Example.jpg',
'image_tip'       => 'Ներդրված պատկեր',
'media_sample'    => 'Example.ogg',
'media_tip'       => 'Հղում մեդիա ֆայլին',
'sig_tip'         => 'Ձեր ստորագրությունը ամսաթվով',
'hr_tip'          => 'Հորիզոնական գիծ (միայն անհրաժեշտության դեպքում)',

# Edit pages
'summary'                  => 'Ամփոփում',
'subject'                  => 'Վերնագիր',
'minoredit'                => 'Սա չնչին խմբագրում է',
'watchthis'                => 'Հսկել այս էջը',
'savearticle'              => 'Հիշել էջը',
'preview'                  => 'Նախադիտում',
'showpreview'              => 'Նախադիտել',
'showlivepreview'          => 'Ուղիղ նախադիտում',
'showdiff'                 => 'Կատարված փոփոխությունները',
'anoneditwarning'          => "'''Զգուշացում.''' Դուք չեք մտել համակարգ։ Ձեր IP հասցեն կգրանցվի այս էջի խմբագրումների պատմության մեջ։",
'missingsummary'           => "'''Հիշեցում.''' Դուք չեք տվել խմբագրման ամփոփում։ «Հիշել» կոճակի կրկնակի մատնահարման դեպքում փոփոխությունները կհիշվեն առանց ամփոփման։",
'missingcommenttext'       => 'Խնդրում ենք մեկնաբանություն ավելացնել ստորև։',
'missingcommentheader'     => "'''Հիշեցում.''' Դուք չեք նշել մեկնաբանության վերնագիրը։ «Հիշել» կոճակի կրկնակի մատնահարման դեպքում ձեր մեկնաբանությունը կհիշվի առանց վերնագրի։",
'summary-preview'          => 'Ամփոփման նախադիտում',
'subject-preview'          => 'Վերնագրի նախադիտում',
'blockedtitle'             => 'Մասնակիցը արգելափակված է',
'blockedtext'              => "<big>'''Ձեր մասնակցի անունը կամ IP-հասցեն արգելափակված է։'''</big>

Արգելափակումը կատարվել է $1 ադմինիստրատորի կողմից։ Տրված պատճառն է. <br />''«$2»''

* Արգելափակման սկիզբ՝ $8
* Արգելափակման մարում՝ $6
* Արգելափակվել է՝ $7

Դուք կարող եք կապնվել $1 մասնակցի կամ մեկ այլ [[{{MediaWiki:grouppage-sysop}}|ադմինիստրատորի]] հետ՝ ձեր արգելափակումը քննարկելու նպատակով։
Դուք չեք կարող օգտվել` «էլ-նամակ ուղարկել այս մասնակցին» հնարավորությունից, քանի դեռ ինքներդ գործող էլ-փոստի հասցե չէք  նշել ձեր [[{{ns:special}}:Preferences|մասնակցի նախընտրություններում]]։

Ձեր ընթացիկ IP-հասցեն է` $3, արգելափակման իդենտիֆիկատորը՝ $5։ Խնդրում ենք նշել այս տվյալները ձեր հարցումների ժամանակ։",
'autoblockedtext'          => "Ձեր IP-հասցեն ավտոմատիկ արգելափակված է, քանի որ այն օգտագործվել է այլ մասնակցի կողմից, որը արգելափակվել է $1 ադմինիստրատորի կողմից։ Տրված պատճառն է. <br />''«$2»''

* Արգելափակման սկիզբ՝ $8
* Արգելափակման մարում՝ $6
* Արգելափակվել է՝ $7

Դուք կարող եք կապնվել $1 մասնակցի կամ մեկ այլ [[{{MediaWiki:grouppage-sysop}}|ադմինիստրատորի]] հետ՝ ձեր արգելափակումը քննարկելու նպատակով։
Դուք չեք կարող օգտվել` «էլ-նամակ ուղարկել այս մասնակցին» հնարավորությունից, քանի դեռ ինքներդ գործող էլ-փոստի հասցե չէք  նշել ձեր [[{{ns:special}}:Preferences|մասնակցի նախընտրություններում]]։

Ձեր ընթացիկ IP-հասցեն է` $3, արգելափակման իդենտիֆիկատորը՝ $5։ Խնդրում ենք նշել այս տվյալները ձեր հարցումների ժամանակ։",
'blockedoriginalsource'    => "«'''$1'''» էջի տեքստը բերված է ստորև։",
'blockededitsource'        => "«'''$1'''» էջի '''ձեր խմբագրումները''' հետևյալն են.",
'whitelistedittitle'       => 'Խմբագրման համար հարկավոր է մտնել համակարգ',
'whitelistedittext'        => 'Անհրաժեշտ է $1 էջերը խմբագրելու համար։',
'whitelistreadtitle'       => 'Ընթերցելու համար անհրաժեշտ է մտնել համակարգ',
'whitelistreadtext'        => 'Անհրաժեշտ է [[{{ns:special}}:Userlogin|մտնել համակարգ]] էջերը ընթերցելու համար։',
'whitelistacctitle'        => 'Ձեզ չի թույլատրվում ստեղծել մասնակցային հաշիվ',
'whitelistacctext'         => 'Այս վիքիում մասնակցային հաշիվներ ստեղծելու համար անհրաժեշտ է [[{{ns:special}}:Userlogin|մտնել համակարգ]] և ունենալ համապատասխան իրավունքներ։',
'confirmedittitle'         => 'Խբագրելու համար անհրաժեշտ է էլ-հասցեի վավերացում',
'confirmedittext'          => 'Էջերի խմբագրումից առաջ անհրաժեշտ է վավերացնել էլ-հասցեն։ Խնդրում ենք նշել և վավերացնել ձեր էլ-փոստի հասցեն ձեր [[{{ns:special}}:Preferences|նախընտրությունների]] մեջ։',
'nosuchsectiontitle'       => 'Այսպիսի բաժին գոյություն չունի',
'nosuchsectiontext'        => 'Դուք փորձել եք խմբագրել գոյություն չունեցող բաժին։ Քանի որ «$1» բաժին չկա, չկա նաև ձեր խմբագրումների հիշման վայր։',
'loginreqtitle'            => 'Անհրաժեշտ է մտնել համակարգ',
'loginreqlink'             => 'մտնել համակարգ',
'loginreqpagetext'         => 'Անհրաժեշտ է $1 այլ էջեր դիտելու համար։',
'accmailtitle'             => 'Գաղտնաբառն ուղարկված է։',
'accmailtext'              => "'$1' մասնակցի գաղտնաբառը ուղարկված է $2 հասցեին։",
'newarticle'               => '(Նոր)',
'newarticletext'           => "Դուք հղվել եք դեռևս գոյություն չունեցող էջի։ Էջը ստեղծելու համար սկսեք տեքստի մուտքագրումը ներքևի արկղում (այցելեք [[{{MediaWiki:helppage}}|օգնության էջը]]՝ մանրամասն տեղեկությունների համար)։ Եթե դուք սխալմամաբ եք այստեղ հայտնվել, ապա մատնահարեք ձեր բրաուզերի '''back''' կոճակը։",
'anontalkpagetext'         => "----''Այս քննարկման էջը պատկանում է անանուն մասնակցին, որը դեռ չի ստեղծել մասնակցային հաշիվ կամ չի մտել համակարգ մասնակցի անունով։ Այդ իսկ պատճառով օգտագործվում է թվային IP-հասցեն։ Նման IP-հասցեից կարող են օգտվել մի քանի մասնակիցներ։ Եթե դուք անանուն մասնակից եք, բայց կարծում եք, որ ուրիշներին վերաբերող դիտողությունները արվում են ձեր հասցեով, ապա խնդրում ենք պարզապես [[Special:Userlogin|գրանցվել կամ մտնել համակարգ]], որպեսզի հետագայում ձեզ չշփոթեն այլ մասնակիցների հետ և չվերագրեն ձեզ նրանց կատարած գործողությունները։''",
'noarticletext'            => 'Ներկայումս այս էջում որևէ տեքստ չկա։ Դուք կարող եք [[Special:Search/{{PAGENAME}}|որոնել այս էջի անվանումը]] այլ էջերում կամ [{{fullurl:{{FULLPAGENAME}}|action=edit}} ստեղծել նոր էջ այս անվանմամբ]։',
'clearyourcache'           => "'''Ծանուցում.''' Հիշելուց հետո կատարված փոփոխությունները տեսնելու համար մաքրեք ձեր բրաուզերի քեշը. '''Mozilla / Firefox'''՝ ''Ctrl+Shift+R'', '''IE'''՝ ''Ctrl+F5'', '''Safari'''՝ ''Cmd+Shift+R'', '''Konqueror'''՝ ''F5'', '''Opera'''՝ ''Tools→Preferences'' ընտրացանկից։",
'usercssjsyoucanpreview'   => '<strong>Հուշում.</strong> Էջը հիշելուց առաջ օգտվեք նախադիտման կոճակից՝ ձեր նոր CSS/JS-ֆայլը ստուգելու համար։',
'usercsspreview'           => "'''Նկատի ունեցեք, որ դուք միայն նախադիտում եք ձեր մասնակցի CSS-ֆայլը. այն դեռ հիշված չէ՛։'''",
'userjspreview'            => "'''Նկատի ունեցեք, որ դուք միայն նախադիտում եք ձեր մասնակցի JavaScript-ֆայլը. այն դեռ հիշված չէ՛։'''",
'userinvalidcssjstitle'    => "'''Զգուշացում.''' «$1» տեսք չի գտնվել։ Ի նկատի ունեցեք, որ մասնակցային .css և .js էջերը ունեն փոքրատառ անվանումներ, օր.՝ «{{ns:user}}:Ոմն/monobook.css», և ոչ թե «{{ns:user}}:Ոմն/Monobook.css»։",
'updated'                  => '(Թարմացված)',
'note'                     => '<strong>Ծանուցում.</strong>',
'previewnote'              => '<strong>Սա միայն նախադիտումն է. ձեր կատարած փոփոխությունները դեռ չե՛ն հիշվել։</strong>',
'previewconflict'          => 'Այս նախադիտումը արտապատկերում է վերևի խմբագրման դաշտում եղած տեքստը այնպես, ինչպես այն կերևա հիշվելուց հետո։',
'editing'                  => 'Խմբագրում. $1',
'editinguser'              => '<b>$1</b> մասնակցի համար',
'editingsection'           => 'Խմբագրում. $1 (բաժին)',
'editingcomment'           => 'Խմբագրում $1 (մեկնաբանություն)',
'editconflict'             => 'Խմբագրման ընդհարում. $1',
'explainconflict'          => 'Մեկ այլ մասնակից փոփոխել է այս տեքստը ձեր խմբագրման ընթացքում։ Վերին խմբագրման դաշտում ընդգրկված է ընթացիկ տեքստը, որն ենթակա է հիշման։ Ձեր խմբագրումներով տեքստը գտնվում է ստորին դաշտում։ Որպեսզի ձեր փոփոխությունները հիշվեն, միաձուլեք դրանք վերին տեքստի մեջ։<br />
«Հիշել էջը» կոճակին սեղմելով կհիշվի <b>միայն</b> վերևվի դաշտի տեքստը:',
'yourtext'                 => 'Ձեր տեքստը',
'storedversion'            => 'Պահված տարբերակ',
'nonunicodebrowser'        => '<strong>ԶԳՈՒՇԱՑՈՒՄ. Ջեր բրաուզերը չունի Յունիկոդ ապահովում։ Հոդվածներ խմբագրելիս բոլոր ոչ-ASCII սիմվլոները փոխարինվելու են իրենց տասնվեցական կոդերով։</strong>',
'editingold'               => '<strong>ԶԳՈՒՇԱՑՈՒՄ. Դուք խմբագրում եք այս էջի հնացած տարբերակ։ Էջը հիշելուց հետո հետագա տարբերակներում կատարված փոփոխությունները կկորեն։</strong>',
'yourdiff'                 => 'Տարբերությունները',
'copyrightwarning'         => 'Ի նկատի ունեցեք որ տեքստի յուրաքանչյուր լրացում և փոփոխություն համարվում են թողարկված $2 լիցենզիյի համաձայն (տես $1 մանրամասների համար)։
Եթե դուք չեք ցանկանում, որ ձեր նյութը անողոք խմբագրվի ու ազատ տարածվի, ապա մի՛ տեղադրեք այն այստեղ։<br />
Դուք նաև հավաստիացնում եք մեզ, որ նյութը գրված է ձեր կողմից կամ վերցված է ազատ տարածում և պարունակության փոփոխություններ թույլատրող աղբյուրներից։
<strong>ՄԻ՛ ՏԵՂԱԴՐԵՔ ՀԵՂԻՆԱԿԱՅԻՆ ԻՐԱՎՈՒՆՔՆԵՐՈՎ ՊԱՇՏՊԱՆՎԱԾ ՆՅՈՒԹԵՐ ԱՌԱՆՑ ԹՈՒՅԼԱՏՐՈՒԹՅԱՆ։</strong>',
'copyrightwarning2'        => 'Խնդրում ենք ի նկատի ունենալ, որ {{SITENAME}} կայքում արված բոլոր ներդրումները կարող են խմբագրվել, վերամշակվել կամ ջնջվել ուրիշ մասնակիցների կողմից։ Եթե դուք չեք ցանկանում, որ ձեր նյութը խմբագրվի, ապա մի՛ տեղադրեք այն այստեղ։<br /> Դուք նաև հավաստիացնում եք մեզ, որ նյութը գրված է ձեր կողմից կամ վերցված է ազատ տարածում և պարունակության փոփոխություններ թույլատրող աղբյուրներից (մանրամասնությունների համար տես $1)։ <strong>ՄԻ՛ ՏԵՂԱԴՐԵՔ ՀԵՂԻՆԱԿԱՅԻՆ ԻՐԱՎՈՒՆՔՆԵՐՈՎ ՊԱՇՏՊԱՆՎԱԾ ՆՅՈՒԹԵՐ ԱՌԱՆՑ ԹՈՒՅԼԱՏՐՈՒԹՅԱՆ։</strong>',
'longpagewarning'          => '<strong>ԶԳՈՒՇԱՑՈՒՄ. Այս էջի երկարությունն է $1 կիլոբայթ։ Որոշ բրաուզերներ կարող են դժվարանալ խմբագրել 32 ԿԲ և ավել երկարություն ունեցող էջերը։ Խնդրում ենք դիտարկել այս էջի տրոհումը փոքր բաժինների։</strong>',
'longpageerror'            => '<strong>ՍԽԱԼ. Ներկայացված տեքստը ունի $1 կիլոբայթ երկարություն, ինչը գերազանցում է սահմանված $2 ԿԲ առավելագույն չափը։ Էջը չի կարող հիշվել։</strong>',
'readonlywarning'          => '<strong>ԶԳՈՒՇԱՑՈՒՄ. Տվյալների բազան կողպվել է սպասարկման նպատակով, և դուք չեք կարող հիշել ձեր կատարած փոփոխությունները այս պահին։ Հավանաբար իմաստ ունի պատճենել տեքստը տեքստային ֆայլի մեջ և պահել այն՝ հետագայում նախագծում ավելացնելու համար։</strong>',
'protectedpagewarning'     => '<strong>ԶԳՈՒՇԱՑՈՒՄ. Այս էջը պաշտպանված է փոփոխություններից. այն կարող են խմբագրել միայն ադմինիստրատորները։</strong>',
'semiprotectedpagewarning' => "'''Ծանուցում.''' Այս էջը պաշտպանված է. այն կարող են խմբագրել միայն գրանցված մասնակիցները։",
'cascadeprotectedwarning'  => "'''Զգուշացում.''' Այս էջը պաշտպանված է և կարող է խմբագրվել միայն ադմինիստրատորների կողմից, քանի որ այն ընդգրկված է հետևյալ կասկադային-պաշտպանությամբ {{PLURAL:$1|էջում|էջերում}}.",
'templatesused'            => 'Այս էջում օգտագործված կաղապարները.',
'templatesusedpreview'     => 'Այս նախադիտման մեջ օգտագործված կաղապարները.',
'templatesusedsection'     => 'Այս բաժնում օգտագործված կաղապարները.',
'template-protected'       => '(պաշտպանված)',
'template-semiprotected'   => '(կիսապաշտպանված)',
'edittools'                => '<!-- Այստեղ տեղադրված տեքստը կցուցադրվի խմբագրման և բեռնման ձևերի տակ։ -->',

# "Undo" feature
'undo-success' => 'Խմբագրումը կարող է հետ շրջվել։ Ստուգեք տարբերակների համեմատությունը ստորև, որպեսզի համոզվեք, որ դա է ձեզ հետաքրքրող փոփոխությունը և մատնահարեք «Հիշել էջը»՝ գործողությունն ավարտելու համար։',

# Account creation failure
'cantcreateaccounttitle' => 'Չհաջողվեց ստեղծել մասնակցային հաշիվ',

# History pages
'revhistory'          => 'Փոփոխությունների պատմություն',
'viewpagelogs'        => 'Դիտել այս էջի տեղեկամատյանները',
'nohistory'           => 'Այս էջը չունի խմբագրումների պատմություն։',
'revnotfound'         => 'Տարբերակը չի գտնվել',
'revnotfoundtext'     => 'Էջի որոնված հին տարբերակը չի գտնվել։ Խնդրում ենք ստուգել այն հղումը, որով անցել եք այս էջին։',
'loadhist'            => 'Էջի պատմության բեռնում',
'currentrev'          => 'Ընթացիկ տարբերակ',
'revisionasof'        => '$1-ի տարբերակ',
'revision-info'       => '$1 տարբերակ, $2',
'previousrevision'    => '←Նախորդ տարբերակ',
'nextrevision'        => 'Հաջորդ տարբերակ→',
'currentrevisionlink' => 'Ընթացիկ տարբերակ',
'cur'                 => 'ընթ',
'next'                => 'հաջորդ',
'last'                => 'նախ',
'page_first'          => 'առաջին',
'page_last'           => 'վերջին',
'histlegend'          => "Տարբերությունների համեմատում. դրեք նշման կետեր այն տարբերակների կողքին, որոնք ուզում եք համեմատել և սեղմեք ներքևում գտնվող կոճակը։<br />
Պարզաբանում. (ընթ) = համեմատել ընթացիկ տարբերակի հետ,
(նախ) = համեմատել նախորդ տարբերակի հետ,<br />'''չ''' = չնչին խմբագրում",
'deletedrev'          => '[ջնջված]',
'histfirst'           => 'Առաջին',
'histlast'            => 'Վերջին',
'historysize'         => '($1 բայթ)',
'historyempty'        => '(դատարկ)',

# Revision feed
'history-feed-title' => 'Փոփոխությունների պատմություն',

# Revision deletion
'rev-delundel'              => 'ցույց տալ/թաքցնել',
'revisiondelete'            => 'Ջնջել/վերականգնել տարբերակները',
'revdelete-legend'          => 'Սահմանել սահմանափակումներ.',
'revdelete-hide-text'       => 'Թաքցնել տարբերակի տեքստը',
'revdelete-hide-name'       => 'Թաքցնել գործողությունը և առարկան',
'revdelete-hide-comment'    => 'Թաքցնել մեկնաբանությունը',
'revdelete-hide-user'       => 'Թաքցնել հեղինակի մասնակցի անունը/IP',
'revdelete-hide-restricted' => 'Կիրառել այս սահմանափակումները ադմինիստրատորների և նույնպես մյուսների նկատմամբ',
'revdelete-suppress'        => 'Թաքցնել տվյալները ադմինիստրատորներից և մյուսներից նոյնպես',
'revdelete-hide-image'      => 'Թաքցնել ֆայլի պարունակությունը',
'revdelete-unsuppress'      => 'Հանել սահմանափակումները վերականգնված տարբերակներից',
'revdelete-submit'          => 'Կիրառել ընտրված տարբերակի վրա',
'revdelete-logentry'        => '«[[$1]]»-ի տարբերակների տեսանելիությունը փոփոխված է',
'logdelete-logentry'        => '«[[$1]]»-ի իրադարձությունների տեսանելիությունը փոփոխված է',
'revdelete-logaction'       => '$1 {{PLURAL:$1|տարբերակ|տարբերակ}} տեղափոխված է $2 կարգավիճակ',
'logdelete-logaction'       => '«[[$3]]»-ի $1 {{PLURAL:$1|իրադարձություն|իրադարձություն}} տեղափոխված է $2 կարգավիճակ',
'revdelete-success'         => 'Տարբերակի տեսանելիությունը փոփոխված է։',
'logdelete-success'         => 'Իրադարձության տեսանելիությունը փոփոխված է։',

# Oversight log
'oversightlog'    => 'Վերահսկման տեղեկամատյան',
'overlogpagetext' => 'Ստորև բերված է ադմինիստրատորներից թաքնված նյութերի վերջին ջնջումների և արգելափակուկմների ցանկը։ Տեսեք նաև [[Special:Ipblocklist|ներկայումս գործող արգելափակումների ցանկը]]։',

# Diffs
'history-title'             => '«$1» էջի փոփոխումների պատմություն',
'difference'                => '(Խմբագրումների միջև եղած տարբերությունները)',
'loadingrev'                => 'բեռնվում է տարբերակը տարբերման համար',
'lineno'                    => 'Տող  $1.',
'editcurrent'               => 'Խմբագրել այս էջի ներկա տարբերակը',
'selectnewerversionfordiff' => 'Ընտրեք ավելի նոր տարբերակ համեմատության համար',
'selectolderversionfordiff' => 'Ընտրեք ավելի հին տարբերակ համեմատության համար',
'compareselectedversions'   => 'Համեմատել ընտրած տարբերակները',
'editundo'                  => 'հետ շրջել',

# Search results
'searchresults'     => 'Որոնման արդյունքներ',
'searchresulttext'  => '{{SITENAME}} կայքում որոնման մասին տեղեկությունների համար այցելեք [[{{MediaWiki:helppage}}|{{int:help}}]] էջը։',
'prevn'             => 'նախորդ $1',
'nextn'             => 'հաջորդ $1',
'viewprevnext'      => 'Դիտել ($1) ($2) ($3)',
'showingresults'    => 'Ստորև բերված է <b>$1</b> արդյունք` սկսած <b>$2</b> համարից։',
'showingresultsnum' => 'Ստորև բերված է <b>$3</b> արդյունք` սկսած <b>$2</b> համարից։',

# Preferences page
'preferences'              => 'Նախընտրություններ',
'mypreferences'            => 'Իմ նախընտրությունները',
'prefs-edits'              => 'Խմբագրումների քանակը.',
'qbsettings-none'          => 'Չցուցադրել',
'qbsettings-fixedleft'     => 'Ձախից անշարժ',
'qbsettings-fixedright'    => 'Աջից անշարժ',
'qbsettings-floatingleft'  => 'Ձախից լողացող',
'qbsettings-floatingright' => 'Աջից լողացող',
'changepassword'           => 'Փոխել գաղտնաբառը',
'skin'                     => 'Տեսք',
'math'                     => 'Մաթ',
'dateformat'               => 'Օր ու ժամվա ձևը',
'datedefault'              => 'Առանց նախընտրության',
'datetime'                 => 'Օր ու ժամ',
'prefs-personal'           => 'Անձնական',
'prefs-rc'                 => 'Վերջին փոփոխություններ',
'prefs-watchlist'          => 'Հսկացանկ',
'prefs-watchlist-days'     => 'Հսկացանկում ցուցադրվող օրերի թիվը՝',
'prefs-watchlist-edits'    => 'Ընդարձակված հսկացանկում ցուցադրվող օրերի թիվը՝',
'prefs-misc'               => 'Այլ',
'saveprefs'                => 'Հիշել',
'resetprefs'               => 'Անտեսել փոփոխությունները',
'oldpassword'              => 'Հին գաղտնաբառը.',
'newpassword'              => 'Նոր գաղտնաբառը.',
'retypenew'                => 'Հաստատեք նոր գաղտնաբառը.',
'textboxsize'              => 'Խմբագրում',
'rows'                     => 'Տողեր`',
'columns'                  => 'Սյունակներ',
'searchresultshead'        => 'Որոնում',
'recentchangesdays'        => 'Վերջին փոփոխություններում ցուցադրվող օրերի թիվը՝',
'recentchangescount'       => 'Վերջին փոփոխություններում ցուցադրվող խմբագրումների թիվը՝',
'savedprefs'               => 'Ձեր նախընտրությունները հիշված են։',
'timezonelegend'           => 'Ժամանակային գոտի',
'timezonetext'             => 'Ձեր տեղական ժամանակի և սերվերի ժամանակի (UTC) միջև ժամերի տարբերությունը։',
'localtime'                => 'Տեղական ժամանակ՝',
'timezoneoffset'           => 'Տարբերություն¹',
'servertime'               => 'Սերվերի ժամանակ՝',
'guesstimezone'            => 'Լրացնել բրաուզերից',
'allowemail'               => 'Թույլատրել էլ-նամակներ մյուս մասնակիցներից',
'defaultns'                => 'Լռությամբ որոնել հետևյալ անվանատարծքներում.',
'default'                  => 'լռությամբ',
'files'                    => 'Ֆայլեր',

# Groups
'group'       => 'Խումբ.',
'group-bot'   => 'Բոտեր',
'group-sysop' => 'Ադմիններ',
'group-all'   => '(բոլոր)',

'group-bot-member'   => 'Բոտ',
'group-sysop-member' => 'Ադմին',

'grouppage-sysop' => '{{ns:project}}:Ադմինիստրատոր',

# Recent changes
'nchanges'                          => '$1 {{PLURAL:$1|փոփոխություն|փոփոխություն}}',
'recentchanges'                     => 'Վերջին փոփոխություններ',
'recentchangestext'                 => 'Հետևեք վիքիում կատարված վերջին փոփոխություններին այս էջում։',
'rcnote'                            => 'Ստորև բերված են վերջին <strong>$1</strong> փոփոխությունները վերջին <strong>$2</strong> {{plural:$2|օրվա|օրվա}} ընթացքում՝ $3-ի դրությամբ։',
'rclistfrom'                        => 'Ցույց տալ նոր փոփոխությունները սկսած $1',
'rcshowhideminor'                   => '$1 չնչին խմբագրումները',
'rcshowhidebots'                    => '$1 բոտերին',
'rcshowhideliu'                     => '$1 մուտք գործած մասնակիցներին',
'rcshowhideanons'                   => '$1 անանուն մասնակիցներին',
'rcshowhidemine'                    => '$1 իմ խմբագրումները',
'rclinks'                           => 'Ցույց տալ վերջին $1 փոփոխությունները վերջին $2 օրվա ընթացքում<br />$3',
'diff'                              => 'տարբ',
'hist'                              => 'պատմ',
'hide'                              => 'Թաքցնել',
'show'                              => 'Ցույց տալ',
'minoreditletter'                   => 'չ',
'newpageletter'                     => 'Ն',
'boteditletter'                     => 'բ',
'number_of_watching_users_pageview' => '[$1 հսկող մասնակից]',
'newsectionsummary'                 => '/* $1 */ Նոր բաժին',

# Recent changes linked
'recentchangeslinked'         => 'Կապված փոփոխություններ',
'recentchangeslinked-summary' => "Այս սպասարկող էջում բերված են հղվող էջերում կատարված վերջին փոփոխությունները։ Ձեր հսկացանկի էջերը ներկայացված են '''թավատառ'''։",

# Upload
'upload'             => 'Բեռնել ֆայլ',
'uploadbtn'          => 'Բեռնել ֆայլ',
'uploaderror'        => 'Բեռնման սխալ',
'uploadtext'         => "Ֆայլ բեռնելու համար օգտագործեք ստորև բերված ձևը։ Նախկինում բեռնված ֆայլերը դիտելու կամ որոնելու համար այցելեք [[Special:Imagelist|բեռնված ֆայլերի ցանկը]]։ Բեռնումները և ջնջումները նաև գրանցվում են [[Special:Log/upload|բեռնումների տեղեկամատյանում]]։

Այս ֆայլը որևէ էջում ընդգրկելու համար օգտագործեք հետևյալ հղման ձևերը.
* '''<nowiki>[[</nowiki>{{ns:image}}<nowiki>:File.jpg]]</nowiki>''',
* '''<nowiki>[[</nowiki>{{ns:image}}<nowiki>:File.png|այլ. տեքստ]]</nowiki>''' կամ
* '''<nowiki>[[</nowiki>{{ns:media}}<nowiki>:File.ogg]]</nowiki>''' - ֆայլին ուղիղ հղման համար",
'uploadlog'          => 'բեռնումների տեղեկամատյան',
'uploadlogpage'      => 'Բեռնումների տեղեկամատյան',
'uploadlogpagetext'  => 'Ստորև բերված է ամենավերջին բեռնված ֆայլերի ցանկը։',
'filename'           => 'Ֆայլի անվանում',
'filedesc'           => 'Ամփոփում',
'fileuploadsummary'  => 'Նկարագրություն՝',
'filestatus'         => 'Հեղինակային իրավունքի կարգավիճակ',
'filesource'         => 'Աղբյուր',
'uploadedfiles'      => 'Բեռնված ֆայլեր',
'ignorewarning'      => 'Անտեսել զգուշացումը և պահպանել ֆայլը ամեն դեպքում։',
'ignorewarnings'     => 'Անտեսել բոլոր նախազգուշացումները',
'minlength1'         => 'Ֆայլի անվանումը պետք է պարունակի առնվազն մեկ տառ',
'badfilename'        => 'Պատկերի վերանվանվել է «$1» անվանման։',
'successfulupload'   => 'Բեռնումը կատարված է',
'uploadwarning'      => 'Զգուշացում',
'savefile'           => 'Հիշել ֆայլը',
'uploadedimage'      => 'բեռնվեց «[[$1]]»',
'overwroteimage'     => 'բեռնվեց «[[$1]]» ֆայլի նոր տարբերակ',
'uploaddisabled'     => 'Բեռնումները արգելված են',
'uploaddisabledtext' => 'Ֆայլերի բեռնումը արգելված է այս վիքի կայքում։',
'sourcefilename'     => 'Ելման ֆայլ՝',
'destfilename'       => 'Ֆայլի նոր անվանում՝',
'watchthisupload'    => 'Հսկել այս ֆայլի էջը',

# Image list
'imagelist'                 => 'Ֆայլերի ցանկ',
'getimagelist'              => 'ֆայլերի ցանկի կանչ',
'ilsubmit'                  => 'Որոնել',
'showlast'                  => 'Ցույց տալ վերջին $1 ֆայլը՝ դասավորված $2։',
'byname'                    => 'ըստ անվանման',
'bydate'                    => 'ըստ ամսաթվի',
'bysize'                    => 'ըստ չափի',
'imgdelete'                 => 'ջնջ.',
'imgdesc'                   => 'նկարագրություն',
'imgfile'                   => 'ֆայլ',
'filehist'                  => 'Ֆայլի պատմություն',
'filehist-deleteall'        => 'ջնջել բոլորը',
'filehist-deleteone'        => 'ջնջել այս',
'filehist-datetime'         => 'Օր/Ժամ',
'filehist-user'             => 'Մասնակից',
'filehist-dimensions'       => 'Օբյեկտի չափը',
'filehist-filesize'         => 'Ֆայլի չափ',
'filehist-comment'          => 'Մեկնաբանություն',
'imagelinks'                => 'Հղումներ',
'linkstoimage'              => 'Հետևյալ էջերը հղվում են այս ֆայլին.',
'nolinkstoimage'            => 'Այս ֆայլին հղվող էջեր չկան։',
'uploadnewversion-linktext' => 'Բեռնել այս ֆայլի նոր տարբերակ',

# File deletion
'filedelete'         => 'Ջնջում $1',
'filedelete-legend'  => 'Ջնջել ֆայլը',
'filedelete-submit'  => 'Ջնջել',
'filedelete-success' => "'''$1''' ֆայլը ջնջված է։",

# MIME search
'mimesearch' => 'Որոնել MIME տիպով',

# Unwatched pages
'unwatchedpages' => 'Չհսկվող էջեր',

# List redirects
'listredirects' => 'Վերահղումների ցանկ',

# Unused templates
'unusedtemplates' => 'Չօգտագործվող կաղապարներ',

# Random redirect
'randomredirect' => 'Պատահական վերահղում',

# Statistics
'statistics'    => 'Վիճակագրություն',
'sitestats'     => 'Կայքի վիճակագրություն',
'userstats'     => 'Մասնակիցների վիճակագրություն',
'sitestatstext' => "Տվյլաների բազայում կա '''$2''' հոդված։
<br />Այս թիվը չի ընդգրկում մասնակիցների և «քննարկման» էջերը, վերահղման, երկիմաստության փարատման և {{grammar:genitive|{{SITENAME}}}} կառավարման մասին էջերը, ինչպես նաև պատառ հոդվածները, որոնք չեն պարունակում ոչ մի ներքին հղում։ Սրանց հետ մեկտեղ, ընդհանուր էջերի թիվն է՝ '''$1'''։

{{grammar:genitive|{{SITENAME}}}} հաստատումից ի վեր եղել է '''$3''' էջի դիտում և կատարվել է '''$4''' էջի խմբագրում։
Սա կազմում է միջինում '''$5''' խմբագրում և '''$6''' դիտում մեկ էջի հաշվով։

Բեռնվել է '''$8''' ֆայլ։

[http://meta.wikimedia.org/wiki/Help:Job_queue Առաջադրանքների հերթի] երկարությունն է՝ '''$7'''։",
'userstatstext' => "Կան '''$1''' գրանցված մասնակիցներ, որոնցից '''$2'''ը (կամ '''$4%'''ը) $5 են։",

'disambiguations'     => 'Երկիմաստության փարատման էջեր',
'disambiguationspage' => '{{ns:template}}:Երկիմաստ',

'doubleredirects' => 'Կրկնակի վերահղումներ',

'brokenredirects'         => 'Կոտրված վերահղումներ',
'brokenredirects-summary' => 'Կոտրված վերահղումներ` հակիրճ',
'brokenredirectstext'     => 'Հետևյալ վերահղումները տանում են գոյություն չունեցող էջերի.',
'brokenredirects-edit'    => '(խմբագրել)',
'brokenredirects-delete'  => '(ջնջել)',

'withoutinterwiki'        => 'Լեզվային հղումներ չպարունակող էջեր',
'withoutinterwiki-header' => 'Հետևյալ էջերը չունեն լեզվական հղումներ.',

'fewestrevisions' => 'Ամենաքիչ վերափոխումներով հոդվածներ',

# Miscellaneous special pages
'nbytes'                  => '$1 բայթ',
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
'unusedimages'            => 'Չօգտագործվող ֆայլեր',
'popularpages'            => 'Հաճախ այցելվող էջեր',
'wantedcategories'        => 'Անհրաժեշտ կատեգորիաներ',
'wantedpages'             => 'Անհրաժեշտ էջեր',
'mostlinked'              => 'Էջեր, որոնց շատ են հղվում',
'mostlinkedcategories'    => 'Կատեգորիաներ, որոնց շատ են հղվում',
'mostlinkedtemplates'     => 'Կաղապարներ, որոնց շատ են հղվում',
'mostcategories'          => 'Ամենաշատ կատեգորիաներով էջեր',
'mostimages'              => 'Ամենաշատ օգտագործվող նկարներ',
'mostrevisions'           => 'Ամենաշատ վերափոխումներով հոդվածներ',
'allpages'                => 'Բոլոր էջերը',
'prefixindex'             => 'Բոլոր էջերը ըստ սկզբնատառի',
'randompage'              => 'Պատահական էջ',
'randompage-nopages'      => 'Այս անվանատարածքում էջեր չկան։',
'shortpages'              => 'Կարճ էջեր',
'longpages'               => 'Երկար էջեր',
'deadendpages'            => 'Հղումներ չպարունակող էջեր',
'deadendpagestext'        => 'Հետևյալ էջերը չունեն հղումներ վիքիի այլ էջերին։',
'protectedpages'          => 'Պաշտպանված էջեր',
'protectedpagestext'      => 'Հետևյալ էջերը պաշտպանված են վերանվանումից կամ տեղափոխումից։',
'protectedpagesempty'     => 'Ներկայումս չկան պաշտպանված էջեր նշված պարամետրերով։',
'listusers'               => 'Մասնակիցների ցանկ',
'specialpages'            => 'Սպասարկող էջեր',
'spheading'               => 'Սպասարկող էջեր բոլոր մասնակիցների համար',
'restrictedpheading'      => 'Սահմանափակ թույլատրմամբ սպասարկող էջեր',
'rclsub'                  => '(«$1» էջից հղվող էջերում)',
'newpages'                => 'Նոր էջեր',
'newpages-username'       => 'Մասնակից՝',
'ancientpages'            => 'Ամենահին էջերը',
'intl'                    => 'Միջլեզվական լինքեր',
'move'                    => 'Տեղափոխել',
'movethispage'            => 'Տեղափոխել այս էջը',
'unusedcategoriestext'    => 'Հետևյալ կատեգորիաաների էջերը գոյություն ունեն, սակայն չեն պարունակում ոչ մի էջ կամ ենթակատեգորիա։',

# Book sources
'booksources' => 'Գրքային աղբյուրներ',

'categoriespagetext' => 'Վիքիում կան հետևյալ կատեգորիաները։',
'data'               => 'Տվյալներ',
'alphaindexline'     => '$1 -ից` $2',
'version'            => 'MediaWiki տարբերակը',

# Special:Log
'specialloguserlabel'  => 'Մասնակից.',
'speciallogtitlelabel' => 'Անվանում.',
'log'                  => 'Տեղեկամատյաններ',
'all-logs-page'        => 'Բոլոր տեղեկամատյանները',
'alllogstext'          => '{{SITENAME}} կայքի տեղեկամատյանների համընդհանուր ցանկ։
Դուք կարող եք սահմանափակել արդյունքները ըստ տեղեկամատյանի տեսակի, մասնակցի անունի կամ համապատասխան էջի։',

# Special:Allpages
'nextpage'          => 'Հաջորդ էջը ($1)',
'allpagesfrom'      => 'Ցույց տալ էջերը, որոնք սկսվում են`',
'allarticles'       => 'Բոլոր հոդվածները',
'allinnamespace'    => 'Բոլոր էջերը ($1 անվանակարգ)',
'allnotinnamespace' => 'Բոլոր էջերը (ոչ $1 անվանակարգում)',
'allpagesprev'      => 'Նախորդ',
'allpagesnext'      => 'Հաջորդ',
'allpagessubmit'    => 'Սկսել',
'allpagesprefix'    => 'Ցույց տալ հետևյալ նախածանցով էջերը`',
'allpagesbadtitle'  => 'Տվյալ էջի անվանումը անթույլատրելի է։ Այն պարունակում է միջ-լեզվական կամ ինտերվիքի նախածանց, կամ էլ անվանումներում այնթույլատրելի սիմվոլներ։',

# E-mail user
'emailuser'     => 'էլ-նամակ ուղարկել այս մասնակցին',
'emailpage'     => 'Էլ-նամակ ուղարկել մասնակցին',
'emailfrom'     => 'Ումից',
'emailto'       => 'Ում',
'emailsubject'  => 'Թեմա',
'emailmessage'  => 'Ուղերձ',
'emailsend'     => 'Ուղարկել',
'emailsent'     => 'Էլ-նամակը ուղարկված է',
'emailsenttext' => 'Ձեր էլ-ուղերձն ուղարկված է։',

# Watchlist
'watchlist'            => 'Իմ հսկողության ցանկը',
'mywatchlist'          => 'իմ հսկացանկը',
'watchlistfor'         => "('''$1'''-ի համար)",
'addedwatch'           => 'Ավելացված է հսկացանկին',
'addedwatchtext'       => '«[[:$1]]» էջը ավելացված է ձեր [[Special:Watchlist|հսկացանկին]]։ Այս էջի և նրան կապված քննարկումների էջի հետագա փոփոխությունները կգրանցվեն այդտեղ, և կցուցադրվեն թավատառ [[Special:Recentchanges|վերջին փոփոխությունների]] ցանկում։

Հետագայում հսկացանկից էջը հեռացնելու ցանկության դեպքում մատնահարեք էջի վերնամասի ընտրացանկում գտնվող «հանել հսկումից» կոճակին։',
'removedwatch'         => 'Հանված է հսկման ցանկից',
'removedwatchtext'     => '«[[:$1]]» էջը հանված է ձեր հսկացանկից։',
'watch'                => 'Հսկել',
'watchthispage'        => 'Հսկել այս էջը',
'unwatch'              => 'Հանել հսկումից',
'unwatchthispage'      => 'Հանել հսկումից',
'watchlist-details'    => 'Հսկվում {{PLURAL:$1|է $1 էջ|են $1 էջեր}}` քննարկման էջերը չհաշված.',
'watchlistcontains'    => 'Ձեր հսկողության ցանկում կա $1 էջ։',
'wlshowlast'           => 'Ցուցադրել վերջին $1 ժամերը $2 օրերը $3',
'watchlist-show-bots'  => 'Ցույց տալ բոտերի խմբագրումները',
'watchlist-hide-bots'  => 'Թաքցնել բոտերի խմբագրումները',
'watchlist-show-own'   => 'Ցույց տալ իմ խմբագրումները',
'watchlist-hide-own'   => 'Թաքցնել իմ խմբագրումները',
'watchlist-show-minor' => 'Ցույց տալ չնչին խմբագրումները',
'watchlist-hide-minor' => 'Թաքցնել չնչին խմբագրումները',

# Displayed when you click the "watch" button and it's in the process of watching
'watching'   => 'Հսկվում է...',
'unwatching' => 'Հանվում է հսկումից...',

'created' => 'ստեղծված է',

# Delete/protect/revert
'deletepage'     => 'Ջնջել էջը',
'confirm'        => 'Հաստատել',
'excontent'      => 'բովանդակությունը սա էր` «$1»',
'exblank'        => 'էջը դատարկ էր',
'confirmdelete'  => 'Հաստատել ջնջումը',
'deletesub'      => '(«$1» ջնջվում է)',
'historywarning' => 'Զգուշացում. էջը, որը դուք պատրաստվում եք ջնջել ունի փոփոխությունների պատմություն։',
'actioncomplete' => 'Գործողությունը ավարտված  է',
'deletedarticle' => 'ջնջված է «[[$1]]»',
'dellogpage'     => 'Ջնջումների տեղեկամատյան',
'dellogpagetext' => 'Ստորև բերված է ամենավերջին ջնջումների ցանկը։',
'deletionlog'    => 'ջնջումների տեղեկամատյան',
'deletecomment'  => 'Ջնջելու պատճառը',
'cantrollback'   => 'Չհաջողվեց հետ շրջել խմբագրումը։ Վերջին ներդրումը կատարվել է էջի միակ հեղինակի կողմից։',
'alreadyrolled'  => 'Չհաջողվեց հետ շրջել [[:$1]] էջի վերջին խմբագրումները՝ կատարված [[User:$2|$2]] ([[User talk:$2|Քննարկում]]) մասնակցի կողմից։ Մեկ ուրիշը արդեն խմբագրել է կամ հետ է շրջել էջը։

Վերջին խմբագրումը կատարվել [[User:$3|$3]] ([[User talk:$3|Քննարկում]]) մասնակցի կողմից։',
'protectlogpage' => 'Պաշտպանումների տեղեկամատյան',
'protectlogtext' => 'Ստորև բերված է պաշտպանված և պաշտպանությունից հանված էջերի ցանկը։ Տես նաև [[Special:Protectedpages|ներկայումս պաշտպանված էջերի ցանկը]]։',
'protectsub'     => '(«$1» էջի պաշտպանման մակարդակի հաստատում)',
'confirmprotect' => 'Հաստատել պաշտպանումը',
'protectcomment' => 'Պաշտպանման պատճառը.',
'protect-text'   => 'Այստեղ դուք կարող եք դիտել և փոխել <strong>[[:$1]]</strong> էջի պաշտպանության մակարդակը։',

# Undelete
'undelete'         => 'Դիտել ջնջված էջերը',
'viewdeletedpage'  => 'Դիտել ջնջված էջերը',
'undeletedarticle' => '«[[$1]]» վերականգնված է',
'cannotundelete'   => 'Վերականգնումը ձախողվեց։ Հնարավոր է մեկ ուրիշն արդեն վերականգնել է այս էջը։',

# Namespace form on various pages
'namespace'      => 'Անվանատարածք.',
'invert'         => 'շրջել ընտրությունը',
'blanknamespace' => '(Գլխավոր)',

# Contributions
'contributions' => 'Մասնակցի ներդրում',
'mycontris'     => 'Իմ ներդրումը',
'contribsub2'   => '$1-ի ներդրումները ($2)',
'uctop'         => ' (վերջինը)',
'month'         => 'Սկսած ամսից (և վաղ)՝',
'year'          => 'Սկսած տարեթվից (և վաղ)՝',

'sp-contributions-newest'   => 'Ամենաթարմ',
'sp-contributions-oldest'   => 'Ամենահին',
'sp-contributions-newer'    => 'Նախորդ $1',
'sp-contributions-older'    => 'Հաջորդ $1',
'sp-contributions-newbies'  => 'Ցույց տալ միայն նորաստեղծ հաշիվներից կատարված ներդրումները',
'sp-contributions-blocklog' => 'Արգելափակումների տեղեկամատյան',
'sp-contributions-search'   => 'Որոնել ներդրումները',
'sp-contributions-username' => 'IP-հասե կամ մասնակցի անուն.',
'sp-contributions-submit'   => 'Որոնել',

# What links here
'whatlinkshere'       => 'Այստեղ հղվող էջերը',
'whatlinkshere-title' => 'Էջեր, որոնք հղում են դեպի $1',
'linklistsub'         => '(Հղումների ցանկ)',
'linkshere'           => "Հետևյալ էջերը հղում են '''[[:$1]]''' էջին.",
'nolinkshere'         => "Ուրիշ էջերից '''[[:$1]]''' էջին հղումներ չկան։",
'isredirect'          => 'վերահղման էջ',
'whatlinkshere-prev'  => '{{PLURAL:$1|նախորդ|նախորդ $1}}',
'whatlinkshere-next'  => '{{PLURAL:$1|հաջորդ|հաջորդ $1}}',
'whatlinkshere-links' => '← հղումներ',

# Block/unblock
'blockip'              => 'Մասնակցի արգելափակում',
'blockiptext'          => 'Օգտագործեք ստորև բերված ձևը որոշակի IP-հասցեից կամ մասնակցի անունից գրելու հնարավորությունը արգելափակելու համար։
Նման բան հարկավոր է անել միայն վանդալության կանխարգելման նպատակով և համաձայն [[{{MediaWiki:policy-url}}|կանոնակարգի]]։
Նշեք արգելափակման որոշակի պատճառը ստորև (օրինակ՝ նշեք այն էջը, որում վանդալություն է տեղի ունեցել)։',
'ipaddress'            => 'IP-հասցե.',
'ipadressorusername'   => 'IP-հասցե կամ մասնակցի անուն.',
'ipbexpiry'            => 'Մարման ժամկետ.',
'ipbreason'            => 'Պատճառ.',
'ipbreasonotherlist'   => 'Այլ պատճառ',
'ipbreason-dropdown'   => '*Արգելափակման սովորական պատճառներ
** Կեղծ տեղեկությունների ներմուծում
** Էջերից նյութերի հեռացում
** Արտաքին կայքերին հղումների սպամ
** Անիմաստ/անկապ տեքստի ներմուծում էջերում
** Վարկաբեկող/ահաբեկող պահվածք
** Բազմաթիվ մասնակցային հաշիվների չարաշահում
** Անպատշաճ մասնակցի անուն',
'ipbanononly'          => 'Արգելափակել միայն անանուն մասնակիցներին',
'ipbcreateaccount'     => 'Կանխարգելել մասնակցային հաշվի ստեղծումը',
'ipbemailban'          => 'Կանխարգելել մասնակցի կողմից էլ-նամակների ուղարկումը',
'ipbenableautoblock'   => 'Ավտոմատիկ արգելափակել այս մասնակցի վերջին IP-հասցեն և բոլոր հետագա IP-հասցեները, որոնցից նա կփորձի խբագրումներ կատարել',
'ipbsubmit'            => 'Արգելափակել այս մասնակցին',
'ipbother'             => 'Այլ ժամկետ.',
'ipboptions'           => '2 ժամ:2 hours,1 օր:1 day,3 օր:3 days,1 շաբաթ:1 week,2 շաբաթ:2 weeks,1 ամիս:1 month,3 ամիս:3 months,6 ամիս:6 months,1 տարի:1 year,առհավետ:infinite',
'ipbotheroption'       => 'այլ',
'ipbotherreason'       => 'Այլ/հավելյալ պատճառներ.',
'badipaddress'         => 'Սխալ IP-հասցե',
'blockipsuccesssub'    => 'Արգելափակումը կատարված է',
'blockipsuccesstext'   => '[[Special:Contributions/$1|«$1»]] արգելափակված է։
<br />Տես [[Special:Ipblocklist|արգելափակված IP-հասցեների ցանկը]]։',
'ipb-edit-dropdown'    => 'Խմբագրել արգելափակման պատճառները',
'ipb-unblock-addr'     => 'Անարգելել $1 մասնակցին',
'ipb-unblock'          => 'Անարգելել որևէ մասնակից կամ IP-հասցե',
'ipb-blocklist-addr'   => 'Դիտել $1 մասնակցի գործող արգելափակումները',
'ipb-blocklist'        => 'Դիտել գործող արգելափակումները',
'unblockip'            => 'Անարգելել մասնակցին',
'ipusubmit'            => 'Անարգելել մասնակցին',
'ipblocklist'          => 'Արգելափակված IP հասցեների և մասնակիցների ցանկ',
'ipblocklist-username' => 'Մասնակցի անուն կամ IP-հասցե.',
'blocklistline'        => '$1, $2 արգելափակել է $3 ($4)',
'expiringblock'        => 'կմարվի $1',
'anononlyblock'        => 'միայն անանուն',
'blocklink'            => 'արգելափակել',
'contribslink'         => 'ներդրում',
'autoblocker'          => 'Դուք ավտոմատիկ արգելափակվել եք «$1» մասնակցի հետ ձեր IP-հասցեի համընկնելու պատճառով։ Նրա արգելափակման պատճառն է՝ «$2»։',
'blocklogpage'         => 'Արգելափակումների տեղեկամատյան',
'blocklogentry'        => 'արգելափակվել է  "$1". արգելափակման ժամկետն է՝  $2 $3',
'blocklogtext'         => 'Սա մասնակիցների արգելափակման և անարգելման գործողությունների տեղեկամատյանն է։ Ավտոմատիկ արգելափակված IP-հասցեները ընդգրկված չեն այստեղ։ Տես [[{{ns:special}}:Ipblocklist|նաերկայումս գործող արգելափակումների ցանկը]]։',
'ipb_already_blocked'  => '«$1» մասնակիցը արդեն արգելափակված է',

# Developer tools
'lockdb'              => 'Կողպել տվյալների բազան',
'unlockdb'            => 'Բանալ տվյալների բազան',
'lockconfirm'         => 'Այո, ես իսկապես ուզում եմ կողպել տվյալների բազան։',
'unlockconfirm'       => 'Այո, ես իսկապես ուզում եմ բանալ տվյալների բազան։',
'lockbtn'             => 'Կողպել տվյալների բազան',
'unlockbtn'           => 'Բանալ տվյալների բազան',
'locknoconfirm'       => 'Դուք չեք նշել հաստատման արկղը։',
'lockdbsuccesssub'    => 'Տվյալների բազան կողպված է',
'unlockdbsuccesssub'  => 'Տվյալների բազան բանված է։',
'lockdbsuccesstext'   => 'Տվյալների բազան կողպված է։
<br />Չմոռանաք [[{{ns:special}}:Unlockdb|բանալ այն]] սպասարկման ավարտից հետո։',
'unlockdbsuccesstext' => 'Տվյալների բազան բանված է։',
'lockfilenotwritable' => 'Տվյալների բազայի կողպման ֆայլը գրելի չէ։ Տվյալների բազան կողպելու կամ բանալու համար վեբ-սերվերը պետք է ունենա այս ֆայլը փոփոխելու իրավունք։',
'databasenotlocked'   => 'Տվյալների բազան կողպված չէ։',

# Move page
'movepage'         => 'Տեղափոխել էջը',
'movearticle'      => 'Տեղափոխել էջը',
'movepagebtn'      => 'Տեղափոխել էջը',
'movepage-moved'   => "<big>'''«$1» վերանվանված է «$2»'''</big>", # The two titles are passed in plain text as $3 and $4 to allow additional goodies in the message.
'articleexists'    => 'Այդ անվանմամբ էջ արդեն գոյություն ունի կամ ձեր ընտրած անվանումը անթույլատրելի է։
Խնդրում ենք ընտրել այլ անվանում։',
'movedto'          => 'տեղափոխված է',
'movetalk'         => 'Տեղափոխել զուգակցված քննարկման էջը',
'talkpagemoved'    => 'Զուգակցված քննարկման էջը նույնպես տեղափոխվեց։',
'talkpagenotmoved' => 'Զուգակցված քննարկման էջը <strong>չի</strong> տեղափոխվել։',
'1movedto2'        => '«[[$1]]» վերանվանված է «[[$2]]»',
'1movedto2_redir'  => '«[[$1]]» վերանվանված է «[[$2]]» վերահղմամբ',
'movelogpage'      => 'Տեղափոխումների տեղեկամատյան',
'movelogpagetext'  => 'Ստորև բերված է վերանվանված էջերի ցանկը։',
'movereason'       => 'Պատճառ.',

# Export
'export'        => 'Արտածել էջերը',
'export-submit' => 'Արտածել',

# Namespace 8 related
'allmessages'               => 'Համակարգային ուղերձներ',
'allmessagesname'           => 'Ուղերձ',
'allmessagesdefault'        => 'Լռությամբ տեքստ',
'allmessagescurrent'        => 'Ընթացիկ տեքստ',
'allmessagestext'           => 'Ստորև բերված է «MediaWiki» անվանատարածքի բոլոր համակարգային ուղերձների ցանկը։',
'allmessagesnotsupportedDB' => "'''{{ns:special}}:Allmessages''' չի գործում, քանի որ '''wgUseDatabaseMessages''' հատկանիշը անջատված է։",
'allmessagesfilter'         => 'Ուղղերձների անվան ֆիլտր.',
'allmessagesmodified'       => 'Ցույց տալ միայն փոփոխվածները',

# Thumbnails
'filemissing' => 'Նման ֆայլ չկա',

# Special:Import
'import'                  => 'Էջերի ներմուծում',
'import-interwiki-submit' => 'Ներմուծել',
'importnotext'            => 'Տեքստը բացակայում ք',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Ձեր մասնակցի էջը',
'tooltip-pt-anonuserpage'         => 'Ձեր IP-հասցեի մասնակցային էջը',
'tooltip-pt-mytalk'               => 'Ձեր քննարկման էջը',
'tooltip-pt-anontalk'             => 'IP-հասցեից կատարված խմբագրումների քննարկում',
'tooltip-pt-preferences'          => 'Ձեր նախընտրությունները',
'tooltip-pt-watchlist'            => 'Ձեր հսկողության տակ գտնվող էջերի ցանկը',
'tooltip-pt-mycontris'            => 'Ձեր կատարած ներդրումների ցանկը',
'tooltip-pt-login'                => 'Կոչ ենք անում մտնել համակարգ, սակայն դա պարտադիր չէ',
'tooltip-pt-anonlogin'            => 'Կոչ ենք ձեզ անում մուտք գործել համակարգ, սակայն դա պարտադիր չէ',
'tooltip-pt-logout'               => 'Դուրս գալ համակարգից',
'tooltip-ca-talk'                 => 'Քննարկումներ այս էջի բովանդակության մասին',
'tooltip-ca-edit'                 => 'Դուք կարող էք խմբագրել այս էջը։ Խնդրում ենք օգտագործել նախադիտման կոճակը հիշելուց առաջ։',
'tooltip-ca-addsection'           => 'Ավելացնել մեկնաբանություն այս քննարկմանը',
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
'tooltip-p-logo'                  => 'Գլխավոր Էջ',
'tooltip-n-mainpage'              => 'Այցելեք Գլխավոր Էջը',
'tooltip-n-portal'                => 'Նախագծի մասին, որտեղ գտնել ինչը, ինչով կարող եք օգնել',
'tooltip-n-currentevents'         => 'Տեղեկություններ ընթացիկ իրադարձությունների մասին',
'tooltip-n-recentchanges'         => 'Վիքիում կատարված վերջին փոփոխությունների ցանկը',
'tooltip-n-randompage'            => 'Այցելեք պատահական էջ',
'tooltip-n-help'                  => '{{SITENAME}} նախագծի ուղեցույց',
'tooltip-n-sitesupport'           => 'Օգնեք մեզ նվիրատվությամբ',
'tooltip-t-whatlinkshere'         => 'Այս էջին հղվող բոլոր վիքի էջերի ցանկը',
'tooltip-t-recentchangeslinked'   => 'Այս էջից կապված էջերի վերջին փոփոխությունները',
'tooltip-feed-rss'                => 'Այս էջի RSS սնուցումը',
'tooltip-feed-atom'               => 'Այս էջի Atom սնուցումը',
'tooltip-t-contributions'         => 'Դիտել այս մասնակցի ներդրումների ցանկը',
'tooltip-t-emailuser'             => 'Ուղարկել էլ-նամակ այս մասնակցին',
'tooltip-t-upload'                => 'Բեռնել պատկերներ կամ մեդիա ֆայլեր',
'tooltip-t-specialpages'          => 'Բոլոր սպասարկող էջերի ցանկը',
'tooltip-t-print'                 => 'Այս էջի տպելու տարբերակ',
'tooltip-t-permalink'             => 'Էջի այս տարբերակի մշտական հղում',
'tooltip-ca-nstab-main'           => 'Դիտել հոդվածը',
'tooltip-ca-nstab-user'           => 'Դիտել մասնակցի էջը',
'tooltip-ca-nstab-media'          => 'Դիտել մեդիա-ֆայլի էջը',
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

# Attribution
'anonymous'        => '{{grammar:genitive|{{SITENAME}}}} անանուն մասնակիցները',
'siteuser'         => '{{grammar:genitive|{{SITENAME}}}} մասնակից $1',
'lastmodifiedatby' => 'Այս էջը վերջին անգամ փոփոխվել է $2, $1 $3 մասնակցի կողմից։', # $1 date, $2 time, $3 user
'and'              => 'և',
'othercontribs'    => 'Հիմնված է {{grammar:genitive|$1}} գործի վրա։',
'others'           => 'այլոք',
'siteusers'        => '{{grammar:genitive|{{SITENAME}}}} մասնակից(ներ) $1',
'creditspage'      => 'Երախտիքներ',
'nocredits'        => 'Այս էջի հեղինակների մասին տեղեկություններ չկան։',

# Spam protection
'subcategorycount'       => 'Այս կատեգորիան ունի $1 ենթակատեգորիա։',
'categoryarticlecount'   => 'Այս կատեգորիայում կա  $1 հոդված։',
'category-media-count'   => 'Այս կատեգորիայում {{PLURAL:$1|կա մեկ ֆայլ|կա $1 ֆայլ}}։',
'listingcontinuesabbrev' => ' շարունակ.',

# Image deletion
'deletedrevision' => 'Ջնջված է հին տարբերակը $1',

# Browsing diffs
'previousdiff' => '← Նախորդ տարբ',
'nextdiff'     => 'Հաջորդ տարբ →',

# Media information
'mediawarning'         => "'''Զգուշացում'''. այս ֆայլը կարող է պարունակել վնասակար ծրագրային կոդ, որի կատարումը կարող է վտանգել ձեր համակարգը։<hr />",
'imagemaxsize'         => 'Պատկերի էջում պատկերի չափի սահմանափակում.',
'thumbsize'            => 'Պատկերների փոքրացված չափ.',
'widthheightpage'      => '$1 × $2, $3 էջեր',
'file-info'            => '(ֆայլի չափ. $1, MIME-տեսակ. $2)',
'file-nohires'         => '<small>Բարձր թույլատվությամբ տարբերակ չկա։</small>',
'svg-long-desc'        => '(SVG-ֆայլ, անվանապես $1 × $2 փիքսել, ֆայլի չափ. $3)',
'show-big-image'       => 'Լրիվ թույլատվությամբ',
'show-big-image-thumb' => '<small>Նախադիտման չափ. $1 × $2 փիքսել</small>',

# Special:Newimages
'newimages'    => 'Նոր ֆայլերի սրահ',
'showhidebots' => '($1 բոտերին)',
'noimages'     => 'Տեսնելու բան չկա։',

# Video information, used by Language::formatTimePeriod() to format lengths in the above messages
'seconds-abbrev' => 'վ',
'minutes-abbrev' => 'ր',
'hours-abbrev'   => 'ժ',

# Bad image list
'bad_image_list' => 'Գրաձևը հետևյալն է.

Հաշվի են առնվելու միայն ցանկի տարրերը (* սիմվոլով սկսվող տողերը)։ Տողի առաջին հղումը պետք է լինի դեպի արգելված պատկերը։ Տողի հետագա հղումները ընկալվելու են որպես բացառություններ, այսինքն էջեր, որտեղ նշված պատկերի փակցնումը չի արգելվում։',

# Metadata
'metadata' => 'Մետատվյալներ',

# EXIF tags
'exif-artist' => 'Հեղինակ',

'exif-componentsconfiguration-0' => 'գոյություն չունի',

# External editor support
'edit-externally'      => 'Խմբագրել այս ֆայլը արտաքին խմբագրիչով',
'edit-externally-help' => 'Մանրամասնությունների համար տես [http://meta.wikimedia.org/wiki/Help:External_editors Meta:Help:External_editors]։',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'բոլոր',
'imagelistall'     => 'բոլոր',
'watchlistall2'    => 'բոլոր',
'namespacesall'    => 'բոլոր',
'monthsall'        => 'բոլոր',

# E-mail address confirmation
'confirmemail'            => 'Էլ-հասցեի վավերացում',
'confirmemail_noemail'    => 'Դուք չեք նշել գործող էլ-հասցե ձեր [[{{ns:special}}:Preferences|նախընտրություններում]]։',
'confirmemail_text'       => 'Այս վիքիում անհրաժեշտ է վավերացնել էլ-հասցեն մինչև էլ-փոստի վրա հիմնված հնարավորությունների օգտագործելը։ Մատնահարեք ստորև կոճակին՝ ձեր հասցեին վավերացման նամակ ուղարկելու համար։ Ուղերձում կգտնեք վավերացման կոդով հղում, որին հետևելով կվավերացնեք ձեր էլ-հասցեն։',
'confirmemail_pending'    => '<div class="error">
Վավերացման կոդով նամակն արդեն ուղարկվել է։ Եթե դուք նորերս եք ստեղծել մասնակցային հաշիվը, ապա, հավանաբար, արժե սպասել մի քանի րոպե մինչև նամակի ժամանելը՝ նոր կոդ հայցելուց առաջ։
</div>',
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
'trackbackbox'      => '<div id="mw_trackbacks">
Այս էջի Trackback-ները.<br />
$1
</div>',
'trackbackremove'   => ' ([$1 ջնջել])',
'trackbacklink'     => 'Trackback',
'trackbackdeleteok' => 'Trackback-ը հաջողությամբ հեռացվեց։',

# Delete conflict
'deletedwhileediting' => 'Զգուշացում. ձեր խմբագրման ընթացքում այս էջը ջնջվել է։',
'confirmrecreate'     => "[[{{ns:user}}:$1|$1]] ([[{{ns:user_talk}}:$1|քննարկում]]) մասնակիցը ջնջել է այս էջը ձեր խմաբգրումը սկսելուց հետո՝ հետևյալ պատճառով.
: ''$2''
Խնդրում ենք հաստատել, որ դուք իսկապես ուզում եք վերստեղծել այս էջը։",
'recreate'            => 'Վերստեղծել',

'unit-pixel' => ' փիքսել',

# HTML dump
'redirectingto' => 'Վերահղվում է դեպի [[$1]]…',

# action=purge
'confirm_purge'        => 'Մաքրե՞լ այս էջի քեշը։

$1',
'confirm_purge_button' => 'OK',

# AJAX search
'searchcontaining' => 'Որոնել էջեր, որոնք պարունակում են «$1»։',
'searchnamed'      => '«$1» անվանմամբ էջերի որոնում։',
'articletitles'    => "Հոդվածներ, որոնք սկսվում են ''$1''-ով։",
'hideresults'      => 'Թաքցնել արդյունքները',

# Multipage image navigation
'imgmultipageprev'   => '← նախորդ էջ',
'imgmultipagenext'   => 'հաջորդ էջ →',
'imgmultigo'         => 'Անցնե՜լ',
'imgmultigotopre'    => 'Անցնել էջին',
'imgmultiparseerror' => 'Պատկերի ֆայլը խաթարված է կամ սպարունակում է սխալ, այս պատճառով անհնարին է ստանալ էջերի ցանկը։',

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
'lag-warn-high'   => 'Տվյալների բազայի մեծ հապաղման պատճառով վերջին $1 {{plural:$1|վայրկյանում|վայրկյանում}} կատարված խմբագրումները հնարավոր է չերևան այս ցանկում։',

# Watchlist editor
'watchlistedit-numitems'       => 'Ձեր հսկացանկը պարունակում է {{PLURAL:$1|1 անվանում|$1 անվանում}}՝ քննարկման էջերը չհաշված։',
'watchlistedit-noitems'        => 'Ձեր հսկացանկը չի պարունակում ոչ մի անվանում։',
'watchlistedit-clear-title'    => 'Հսկացանկի մաքրում',
'watchlistedit-clear-legend'   => 'Մաքրել հսկացանկը',
'watchlistedit-clear-confirm'  => 'Սա կհեռացնի ձեր հսկացանկի բոլոր անվանումները։ Համոզվա՞ծ եք, որ ցանկանում եք անել սա։ Դուք կարող եք նաև [[Special:Watchlist/edit|հեռացնել անվանումները անհատականորեն]]։',
'watchlistedit-clear-submit'   => 'Մաքրել',
'watchlistedit-clear-done'     => 'Ձեր հսկացանկը մաքրված է։ Բոլոր անվանումները հեռացվեցին։',
'watchlistedit-normal-title'   => 'Հսկացանկի խմբագրում',
'watchlistedit-normal-legend'  => 'Հեռացնել անվանումները հսկացանկից',
'watchlistedit-normal-explain' => 'Ձեր հսկացանկի անվանումները բերված են ստորև։ Անվանումը հեռացնելու համար նշեք անվանման կողքի արկղում և մատնահարեք Հեռացնել Անվանումները։ Դուք կարող եք նաև [[Special:Watchlist/raw|խմբագրել հում ցանկը]] կամ  [[Special:Watchlist/clear|հեռացնել բոլոր անվանումները]]։',
'watchlistedit-normal-submit'  => 'Հեռացնել Անվանումները',
'watchlistedit-normal-done'    => '{{PLURAL:$1|1 անվանում|$1 անվանում}} հեռացվեց ձեր հսկացանկից.',
'watchlistedit-raw-title'      => 'Հում հսկացանկի խմբագրում',
'watchlistedit-raw-legend'     => 'Խմբագրել հում հսկացանկը',
'watchlistedit-raw-explain'    => 'Ձեր հսկացանկի անվանումները բերված են ստորև։ Դուք կարող եք այն խմբագրել հեռացնելով եղածները կամ ավելացնելով նոր անվանումներ՝ ամեն տողում մեկ անվանում։ Ավարտելուց հետո մատնահարեք Թարմացնել Հսկացանկը։ Կարող եք նաև [[Special:Watchlist/edit|օգտագործել ստանդարտ խմբագրիչը]]։',
'watchlistedit-raw-titles'     => 'Անվանումներ.',
'watchlistedit-raw-submit'     => 'Թարմացնել Հսկացանկը',
'watchlistedit-raw-done'       => 'Ձեր հսկացանկը թարմացված է։',
'watchlistedit-raw-added'      => 'Ավելացվեց {{PLURAL:$1|1 անվանում|$1 անվանում}}.',
'watchlistedit-raw-removed'    => 'Հեռացվեց {{PLURAL:$1|1 անվանում|$1 անվանում}}.',

# Watchlist editing tools
'watchlisttools-view'  => 'Փոփոխությունները հսկացանկում',
'watchlisttools-edit'  => 'Դիտել և խմբագրել հսկացանկը',
'watchlisttools-raw'   => 'Խմբագրել հում հսկացանկը',
'watchlisttools-clear' => 'Մաքրել հսկացանկը',

);
