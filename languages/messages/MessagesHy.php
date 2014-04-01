<?php
/** Armenian (Հայերեն)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
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
	'redirect'                  => array( '0', '#ՎԵՐԱՀՂՈՒՄ', '#REDIRECT' ),
	'notoc'                     => array( '0', '__ԱՌԱՆՑ_ԲՈՎ__', '__NOTOC__' ),
	'nogallery'                 => array( '0', '__ԱՌԱՆՑ_ՍՐԱՀԻ__', '__NOGALLERY__' ),
	'forcetoc'                  => array( '0', '__ՍՏԻՊԵԼ_ԲՈՎ__', '__FORCETOC__' ),
	'toc'                       => array( '0', '__ԲՈՎ__', '__TOC__' ),
	'noeditsection'             => array( '0', '__ԱՌԱՆՑ_ԲԱԺՆԻ_ԽՄԲԱԳՐՄԱՆ__', '__NOEDITSECTION__' ),
	'currentmonth'              => array( '1', 'ԸՆԹԱՑԻՔ_ԱՄԻՍԸ', 'CURRENTMONTH', 'CURRENTMONTH2' ),
	'currentmonthname'          => array( '1', 'ԸՆԹԱՑԻՔ_ԱՄՍՎԱ_ԱՆՈՒՆԸ', 'CURRENTMONTHNAME' ),
	'currentmonthnamegen'       => array( '1', 'ԸՆԹԱՑԻՔ_ԱՄՍՎԱ_ԱՆՈՒՆԸ_ՍԵՌ', 'CURRENTMONTHNAMEGEN' ),
	'currentmonthabbrev'        => array( '1', 'ԸՆԹԱՑԻՔ_ԱՄՍՎԱ_ԱՆՎԱՆ_ՀԱՊԱՎՈՒՄԸ', 'CURRENTMONTHABBREV' ),
	'currentday'                => array( '1', 'ԸՆԹԱՑԻՔ_ՕՐԸ', 'CURRENTDAY' ),
	'currentday2'               => array( '1', 'ԸՆԹԱՑԻՔ_ՕՐԸ_2', 'CURRENTDAY2' ),
	'currentdayname'            => array( '1', 'ԸՆԹԱՑԻՔ_ՕՐՎԱ_ԱՆՈՒՆԸ', 'CURRENTDAYNAME' ),
	'currentyear'               => array( '1', 'ԸՆԹԱՑԻՔ_ՏԱՐԻՆ', 'CURRENTYEAR' ),
	'currenttime'               => array( '1', 'ԸՆԹԱՑԻՔ_ԺԱՄԱՆԱԿԸ', 'CURRENTTIME' ),
	'currenthour'               => array( '1', 'ԸՆԹԱՑԻՔ_ԺԱՄԸ', 'CURRENTHOUR' ),
	'localmonth'                => array( '1', 'ՏԵՂԱԿԱՆ_ԱՄԻՍԸ', 'LOCALMONTH', 'LOCALMONTH2' ),
	'localmonthname'            => array( '1', 'ՏԵՂԱԿԱՆ_ԱՄՍՎԱ_ԱՆՈՒՆԸ', 'LOCALMONTHNAME' ),
	'localmonthnamegen'         => array( '1', 'ՏԵՂԱԿԱՆ_ԱՄՍՎԱ_ԱՆՈՒՆԸ_ՍԵՌ', 'LOCALMONTHNAMEGEN' ),
	'localmonthabbrev'          => array( '1', 'ՏԵՂԱԿԱՆ_ԱՄՍՎԱ_ԱՆՎԱՆ_ՀԱՊԱՎՈՒՄԸ', 'LOCALMONTHABBREV' ),
	'localday'                  => array( '1', 'ՏԵՂԱԿԱՆ_ՕՐԸ', 'LOCALDAY' ),
	'localday2'                 => array( '1', 'ՏԵՂԱԿԱՆ_ՕՐԸ_2', 'LOCALDAY2' ),
	'localdayname'              => array( '1', 'ՏԵՂԱԿԱՆ_ՕՐՎԱ_ԱՆՈՒՆԸ', 'LOCALDAYNAME' ),
	'localyear'                 => array( '1', 'ՏԵՂԱԿԱՆ_ՏԱՐԻՆ', 'LOCALYEAR' ),
	'localtime'                 => array( '1', 'ՏԵՂԱԿԱՆ_ԺԱՄԱՆԱԿԸ', 'LOCALTIME' ),
	'localhour'                 => array( '1', 'ՏԵՂԱԿԱՆ_ԺԱՄԸ', 'LOCALHOUR' ),
	'numberofpages'             => array( '1', 'ԷՋԵՐԻ_ՔԱՆԱԿԸ', 'NUMBEROFPAGES' ),
	'numberofarticles'          => array( '1', 'ՀՈԴՎԱԾՆԵՐԻ_ՔԱՆԱԿԸ', 'NUMBEROFARTICLES' ),
	'numberoffiles'             => array( '1', 'ՖԱՅԼԵՐԻ_ՔԱՆԱԿԸ', 'NUMBEROFFILES' ),
	'numberofusers'             => array( '1', 'ՄԱՍՆԱԿԻՑՆԵՐԻ_ՔԱՆԱԿԸ', 'NUMBEROFUSERS' ),
	'pagename'                  => array( '1', 'ԷՋԻ_ԱՆՈՒՆԸ', 'PAGENAME' ),
	'pagenamee'                 => array( '1', 'ԷՋԻ_ԱՆՈՒՆԸ_2', 'PAGENAMEE' ),
	'namespace'                 => array( '1', 'ԱՆՎԱՆԱՏԱՐԱԾՔ', 'NAMESPACE' ),
	'namespacee'                => array( '1', 'ԱՆՎԱՆԱՏԱՐԱԾՔ_2', 'NAMESPACEE' ),
	'talkspace'                 => array( '1', 'ՔՆՆԱՐԿՄԱՆ_ՏԱՐԱԾՔԸ', 'TALKSPACE' ),
	'talkspacee'                => array( '1', 'ՔՆՆԱՐԿՄԱՆ_ՏԱՐԱԾՔԸ_2', 'TALKSPACEE' ),
	'subjectspace'              => array( '1', 'ՀՈԴՎԱԾՆԵՐԻ_ՏԱՐԱԾՔԸ', 'SUBJECTSPACE', 'ARTICLESPACE' ),
	'subjectspacee'             => array( '1', 'ՀՈԴՎԱԾՆԵՐԻ_ՏԱՐԱԾՔԸ_2', 'SUBJECTSPACEE', 'ARTICLESPACEE' ),
	'fullpagename'              => array( '1', 'ARTICLESPACE', 'ԷՋԻ_ԼՐԻՎ_ԱՆՎԱՆՈՒՄԸ', 'FULLPAGENAME' ),
	'fullpagenamee'             => array( '1', 'ԷՋԻ_ԼՐԻՎ_ԱՆՎԱՆՈՒՄԸ_2', 'FULLPAGENAMEE' ),
	'subpagename'               => array( '1', 'ԵՆԹԱԷՋԻ_ԱՆՎԱՆՈՒՄԸ', 'SUBPAGENAME' ),
	'subpagenamee'              => array( '1', 'ԵՆԹԱԷՋԻ_ԱՆՎԱՆՈՒՄԸ_2', 'SUBPAGENAMEE' ),
	'basepagename'              => array( '1', 'ՀԻՄՆԱԿԱՆ_ԷՋԻ_ԱՆՎԱՆՈՒՄԸ', 'BASEPAGENAME' ),
	'basepagenamee'             => array( '1', 'ՀԻՄՆԱԿԱՆ_ԷՋԻ_ԱՆՎԱՆՈՒՄԸ_2', 'BASEPAGENAMEE' ),
	'talkpagename'              => array( '1', 'ՔՆՆԱՐԿՄԱՆ_ԷՋԻ_ԱՆՎԱՆՈՒՄԸ', 'TALKPAGENAME' ),
	'talkpagenamee'             => array( '1', 'ՔՆՆԱՐԿՄԱՆ_ԷՋԻ_ԱՆՎԱՆՈՒՄԸ_2', 'TALKPAGENAMEE' ),
	'subjectpagename'           => array( '1', 'ՀՈԴՎԱԾԻ_ԷՋԻ_ԱՆՎԱՆՈՒՄԸ', 'SUBJECTPAGENAME', 'ARTICLEPAGENAME' ),
	'subjectpagenamee'          => array( '1', 'ՀՈԴՎԱԾԻ_ԷՋԻ_ԱՆՎԱՆՈՒՄԸ_2', 'SUBJECTPAGENAMEE', 'ARTICLEPAGENAMEE' ),
	'msg'                       => array( '0', 'ՀՈՂՈՐԴ՝', 'MSG:' ),
	'msgnw'                     => array( '0', 'ՀՈՂՈՐԴ_ԱՌԱՆՑ_ՎԻՔԻԻ՝', 'MSGNW:' ),
	'img_thumbnail'             => array( '1', 'մինի', 'thumbnail', 'thumb' ),
	'img_manualthumb'           => array( '1', 'մինի=$1', 'thumbnail=$1', 'thumb=$1' ),
	'img_right'                 => array( '1', 'աջից', 'right' ),
	'img_left'                  => array( '1', 'ձախից', 'left' ),
	'img_none'                  => array( '1', 'առանց', 'none' ),
	'img_width'                 => array( '1', '$1փքս', '$1px' ),
	'img_center'                => array( '1', 'կենտրոն', 'center', 'centre' ),
	'img_framed'                => array( '1', 'շրջափակել', 'framed', 'enframed', 'frame' ),
	'img_page'                  => array( '1', 'էջը=$1', 'էջ $1', 'page=$1', 'page $1' ),
	'int'                       => array( '0', 'ՆԵՐՔ՝', 'INT:' ),
	'sitename'                  => array( '1', 'ԿԱՅՔԻ_ԱՆՈՒՆԸ', 'SITENAME' ),
	'ns'                        => array( '0', 'ԱՏ՝', 'NS:' ),
	'localurl'                  => array( '0', 'ՏԵՂԱԿԱՆ_ՀԱՍՑԵՆ՝', 'LOCALURL:' ),
	'localurle'                 => array( '0', 'ՏԵՂԱԿԱՆ_ՀԱՍՑԵՆ_2՝', 'LOCALURLE:' ),
	'server'                    => array( '0', 'ՍԵՐՎԵՐԸ', 'SERVER' ),
	'servername'                => array( '0', 'ՍԵՐՎԵՐԻ_ԱՆՈՒՆԸ', 'SERVERNAME' ),
	'scriptpath'                => array( '0', 'ՍՔՐԻՊՏԻ_ՃԱՆԱՊԱՐՀԸ', 'SCRIPTPATH' ),
	'grammar'                   => array( '0', 'ՀՈԼՈՎ՛', 'GRAMMAR:' ),
	'notitleconvert'            => array( '0', '__ԱՌԱՆՑ_ՎԵՐՆԱԳՐԻ_ՓՈՓՈԽՄԱՆ__', '__NOTITLECONVERT__', '__NOTC__' ),
	'nocontentconvert'          => array( '0', '__ԱՌԱՆՑ_ՊԱՐՈՒՆԱԿՈՒԹՅԱՆ_ՓՈՓՈԽՄԱՆ__', '__NOCONTENTCONVERT__', '__NOCC__' ),
	'currentweek'               => array( '1', 'ԸՆԹԱՑԻՔ_ՇԱԲԱԹԸ', 'CURRENTWEEK' ),
	'currentdow'                => array( '1', 'ԸՆԹԱՑԻՔ_ՇԱԲԱԹՎԱ_ՕՐԸ', 'CURRENTDOW' ),
	'localweek'                 => array( '1', 'ՏԵՂԱԿԱՆ_ՇԱԲԱԹՎԸ', 'LOCALWEEK' ),
	'localdow'                  => array( '1', 'ՏԵՂԱԿԱՆ_ՇԱԲԱԹՎԱ_ՕՐԸ', 'LOCALDOW' ),
	'revisionid'                => array( '1', 'ՏԱՐԲԵՐԱԿԻ_ՀԱՄԱՐԸ', 'REVISIONID' ),
	'revisionday'               => array( '1', 'ՏԱՐԲԵՐԱԿԻ_ՕՐԸ', 'REVISIONDAY' ),
	'revisionday2'              => array( '1', 'ՏԱՐԲԵՐԱԿԻ_ՕՐԸ_2', 'REVISIONDAY2' ),
	'revisionmonth'             => array( '1', 'ՏԱՐԲԵՐԱԿԻ_ԱՄԻՍԸ', 'REVISIONMONTH' ),
	'revisionyear'              => array( '1', 'ՏԱՐԲԵՐԱԿԻ_ՏԱՐԻՆ', 'REVISIONYEAR' ),
	'plural'                    => array( '0', 'ՀՈԳՆԱԿԻ՝', 'PLURAL:' ),
	'fullurl'                   => array( '0', 'ԼՐԻՎ_ՀԱՍՑԵՆ՝', 'FULLURL:' ),
	'fullurle'                  => array( '0', 'ԼՐԻՎ_ՀԱՍՑԵՆ_2՝', 'FULLURLE:' ),
	'lcfirst'                   => array( '0', 'ՓՈՔՐԱՏԱՌ_ՍԿԶԲՆԱՏԱՌ՝', 'LCFIRST:' ),
	'ucfirst'                   => array( '0', 'ՄԵԾԱՏԱՌ_ՍԿԶԲՆԱՏԱՌ՝', 'UCFIRST:' ),
	'lc'                        => array( '0', 'ՓՈՔՐԱՏԱՌ՝', 'LC:' ),
	'uc'                        => array( '0', 'ՄԵԾԱՏԱՌ՝', 'UC:' ),
	'displaytitle'              => array( '1', 'ՑՈՒՅՑ_ՏԱԼ_ՎԵՐՆԱԳԻՐԸ', 'DISPLAYTITLE' ),
	'rawsuffix'                 => array( '1', 'Չ', 'R' ),
	'newsectionlink'            => array( '1', '__ՀՂՈՒՄ_ՆՈՐ_ԲԱԺՆԻ_ՎՐԱ__', '__NEWSECTIONLINK__' ),
	'currentversion'            => array( '1', 'ԸՆԹԱՑԻՔ_ՏԱՐԲԵՐԱԿԸ', 'CURRENTVERSION' ),
	'urlencode'                 => array( '0', 'ՄՇԱԿՎԱԾ_ՀԱՍՑԵ՛', 'URLENCODE:' ),
	'currenttimestamp'          => array( '1', 'ԸՆԹԱՑԻՔ_ԺԱՄԱՆԱԿԻ_ԴՐՈՇՄ', 'CURRENTTIMESTAMP' ),
	'localtimestamp'            => array( '1', 'ՏԵՂԱԿԱՆ_ԺԱՄԱՆԱԿԻ_ԴՐՈՇՄ', 'LOCALTIMESTAMP' ),
	'directionmark'             => array( '1', 'ՆԱՄԱԿԻ_ՈՒՂՂՈՒԹՅՈՒՆԸ', 'DIRECTIONMARK', 'DIRMARK' ),
	'language'                  => array( '0', '#ԼԵԶՈՒ՝', '#LANGUAGE:' ),
	'contentlanguage'           => array( '1', 'ՊԱՐՈՒՆԱԿՈՒԹՅԱՆ_ԼԵԶՈՒՆ', 'CONTENTLANGUAGE', 'CONTENTLANG' ),
	'pagesinnamespace'          => array( '1', 'ԷՋԵՐ_ԱՆՎԱՆԱՏԱՐԱԾՔՈՒՄ՝', 'PAGESINNAMESPACE:', 'PAGESINNS:' ),
	'numberofadmins'            => array( '1', 'ԱԴՄԻՆՆԵՐԻ_ՔԱՆԱԿԸ', 'NUMBEROFADMINS' ),
	'formatnum'                 => array( '0', 'ՁԵՎԵԼ_ԹԻՎԸ', 'FORMATNUM' ),
	'padleft'                   => array( '0', 'ԼՐԱՑՆԵԼ_ՁԱԽԻՑ', 'PADLEFT' ),
	'padright'                  => array( '0', 'ԼՐԱՑՆԵԼ_ԱՋԻՑ', 'PADRIGHT' ),
	'special'                   => array( '0', 'սպասարկող', 'special' ),
	'defaultsort'               => array( '1', 'ԼՌՈՒԹՅԱՄԲ_ԴԱՍԱՎՈՐՈՒՄ՝', 'DEFAULTSORT:', 'DEFAULTSORTKEY:', 'DEFAULTCATEGORYSORT:' ),
);

$specialPageAliases = array(
	'Allmessages'               => array( 'Բոլորուղերձները' ),
	'Allpages'                  => array( 'Բոլորէջերը' ),
	'Ancientpages'              => array( 'Ամենահինէջերը' ),
	'Block'                     => array( 'Արգելափակել այփին' ),
	'Booksources'               => array( 'Գրքայինաղբյուրները' ),
	'BrokenRedirects'           => array( 'Կոտրվածվերահղումները' ),
	'Categories'                => array( 'Կատեգորիաները' ),
	'ChangePassword'            => array( 'Նորգաղտնաբառ' ),
	'Contributions'             => array( 'Ներդրումները' ),
	'Deadendpages'              => array( 'Հղումչպարունակողէջերը' ),
	'DoubleRedirects'           => array( 'Կրկնակիվերահղումները' ),
	'Emailuser'                 => array( 'Գրելնամակ' ),
	'Export'                    => array( 'Արտահանելէջերը' ),
	'FileDuplicateSearch'       => array( 'Կրկնօրինակֆայլերիորոնում' ),
	'Import'                    => array( 'Ներմուծել' ),
	'BlockList'                 => array( 'Արգելափակված այփի ները' ),
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

