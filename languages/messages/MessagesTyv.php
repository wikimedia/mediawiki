<?php
/** Tuvinian (Тыва дыл)
 *
 * @addtogroup Language
 *
 * @author Sborsody
 * @author G - ג
 * @author friends at tyvawiki.org
 * @author Nike
 */

$namespaceNames = array(
	NS_MEDIA            => 'Медиа', //Media
	NS_SPECIAL          => 'Тускай', //Special
	NS_MAIN	            => '',
	NS_TALK	            => 'Чугаа', //Talk
	NS_USER             => 'Aжыглакчы', //User
	NS_USER_TALK        => 'Aжыглакчы_чугаа', //User_talk
	# NS_PROJECT set by $wgMetaNamespace
	NS_PROJECT_TALK     => '$1_чугаа', //_talk
	NS_IMAGE            => 'Чурук', //Image
	NS_IMAGE_TALK       => 'Чурук_чугаа', //Image_talk
	NS_MEDIAWIKI        => 'МедиаВики', //MediaWiki
	NS_MEDIAWIKI_TALK   => 'МедиаВики_чугаа', //MediaWiki_talk
	NS_TEMPLATE         => 'Хээ', //Template
	NS_TEMPLATE_TALK    => 'Хээ_чугаа', //Template_talk
	NS_HELP             => 'Дуза', //Help
	NS_HELP_TALK        => 'Дуза_чугаа', //Help_talk
	NS_CATEGORY         => 'Бөлүк', //Category
	NS_CATEGORY_TALK    => 'Бөлүк_чугаа', //Category_talk
);

$skinNames = array(
	'standard' => 'Classic', //Classic
	'nostalgia' => 'Nostalgia', //Nostalgia
	'cologneblue' => 'Cologne Blue', //Cologne Blue
	'monobook' => 'Моно-Ном', //MonoBook
	'myskin' => 'MySkin', //MySkin
	'chick' => 'Chick' //Chick
);

$bookstoreList = array(
	'ОЗОН' => 'http://www.ozon.ru/?context=advsearch_book&isbn=$1',
	'Books.Ru' => 'http://www.books.ru/shop/search/advanced?as%5Btype%5D=books&as%5Bname%5D=&as%5Bisbn%5D=$1&as%5Bauthor%5D=&as%5Bmaker%5D=&as%5Bcontents%5D=&as%5Binfo%5D=&as%5Bdate_after%5D=&as%5Bdate_before%5D=&as%5Bprice_less%5D=&as%5Bprice_more%5D=&as%5Bstrict%5D=%E4%E0&as%5Bsub%5D=%E8%F1%EA%E0%F2%FC&x=22&y=8',
	'Яндекс.Маркет' => 'http://market.yandex.ru/search.xml?text=$1',
	'Amazon.com' => 'http://www.amazon.com/exec/obidos/ISBN=$1',
	'AddALL' => 'http://www.addall.com/New/Partner.cgi?query=$1&type=ISBN',
	'PriceSCAN' => 'http://www.pricescan.com/books/bookDetail.asp?isbn=$1',
	'Barnes & Noble' => 'http://shop.barnesandnoble.com/bookSearch/isbnInquiry.asp?isbn=$1'
);

$fallback8bitEncoding = "windows-1251";

$messages = array(
# User preference toggles
'tog-hideminor'      => 'Сөөлгү өскерлиишкиннер арында бичии өскерлиишкиннер чажырар',
'tog-showtoolbar'    => 'Өскертир херекселдер көргүзер (JavaScript)',
'tog-editondblclick' => 'Арынны өскертирде ийи катап базар (JavaScript)',

'underline-always' => 'Кезээде',
'underline-never'  => 'Кажан-даа',

# Dates
'sunday'    => 'Чеди дугаар хүн',
'monday'    => 'Бир дугаар хүн',
'tuesday'   => 'Ийи дугаар хүн',
'wednesday' => 'Үш дугаар хүн',
'thursday'  => 'Дөрт дугаар хүн',
'friday'    => 'Беш дугаар хүн',
'saturday'  => 'Алды дугаар хүн',
'january'   => 'Бир ай',
'february'  => 'ийи ай',
'march'     => 'Үш ай',
'april'     => 'Дөрт ай',
'may_long'  => 'Беш ай',
'june'      => 'Алды ай',
'july'      => 'Чеди ай',
'august'    => 'Сес ай',
'september' => 'Тос ай',
'october'   => 'Он ай',
'november'  => 'Он бир ай',
'december'  => 'Он ийи ай',
'jan'       => '1.ай',
'feb'       => '2.ай',
'mar'       => '3.ай',
'apr'       => '4.ай',
'may'       => '5.ай',
'jun'       => '6.ай',
'jul'       => '7.ай',
'aug'       => '8.ай',
'sep'       => '9.ай',
'oct'       => '10.ай',
'nov'       => '11.ай',
'dec'       => '12.ай',

# Bits of text used by many pages
'categories'      => 'Бөлүктер',
'pagecategories'  => 'Бөлүктер',
'category_header' => '"$1" бөлүкте чүүлдер',
'subcategories'   => 'Бичии бөлүктер',

'about'          => 'Дугайында',
'article'        => 'Азыраары арын',
'newwindow'      => '(чаа козенектен ажар)',
'cancel'         => 'Ап каар',
'qbfind'         => 'Тывар',
'qbbrowse'       => 'Көөр',
'qbedit'         => 'Өскертир',
'qbpageoptions'  => 'Бо арын',
'qbmyoptions'    => 'Мээң арыннарым',
'qbspecialpages' => 'Тускай арыннар',
'moredotdotdot'  => 'Ам-даа...',
'mypage'         => 'Мээң арыным',
'mytalk'         => 'Мээң чугаалажырым',
'anontalk'       => 'Бо ИП-адрестиң чугаа',
'navigation'     => 'Навигация',

'errorpagetitle'    => 'Частырыг',
'returnto'          => '{{grammar:directive1|$1}} дедир.',
'tagline'           => '{{grammar:ablative|{{SITENAME}}}}',
'help'              => 'Дуза',
'search'            => 'Дилээр',
'searchbutton'      => 'Дилээр',
'go'                => 'Чоруур',
'searcharticle'     => 'Чоруур',
'history'           => 'Арынның Төөгүзү',
'history_short'     => 'Төөгү',
'info_short'        => 'Медее',
'printableversion'  => 'Саазынга үндүрерин көөр',
'print'             => 'Саазынга үндүрер',
'edit'              => 'Өскертир',
'editthispage'      => 'Бо арынны өскертир',
'delete'            => 'Ап каар',
'deletethispage'    => 'Бо арынны ап каар',
'protect'           => 'Камгалал',
'protectthispage'   => 'Бо арынны камгалаар',
'unprotect'         => 'Камгалалды ап каар',
'unprotectthispage' => 'Бо арынның камгалалын ап каар',
'newpage'           => 'Чаа Арын',
'talkpage'          => 'Бо арын дугайында чугаалажыр',
'talkpagelinktext'  => 'Чугаалажыр',
'specialpage'       => 'Тускай Арын',
'personaltools'     => 'Херекселдер',
'articlepage'       => 'Допчу арынны көргүзер',
'talk'              => 'Чугаалажыр',
'views'             => 'Бодалдар',
'userpage'          => 'Ажыглакчыниң арынын көргүзер',
'imagepage'         => 'Чурук арынын көргүзер',
'viewhelppage'      => 'Дуза арыны көөр',
'categorypage'      => 'Бөлүк арыны көөр',
'viewtalkpage'      => 'Чугаалажыры көргүзер',
'otherlanguages'    => 'Өске дылдарга',
'lastmodifiedat'    => 'Бо арын сөөлгү каттап $1 өскерилген.', # $1 date, $2 time
'jumptonavigation'  => 'навигация',
'jumptosearch'      => 'дилээр',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'         => '{{SITENAME}} дугайында',
'aboutpage'         => 'Project:Дугайында',
'currentevents'     => 'Амгы үе болуушкуннер',
'currentevents-url' => 'Амгы үе болуушкуннер',
'edithelp'          => 'Өскертир дуза',
'edithelppage'      => 'Help:Өскертир',
'helppage'          => 'Help:Допчузу',
'mainpage'          => 'Кол Арын',
'portal'            => 'Ниитилелдиң порталы',
'portal-url'        => 'Project:Ниитилелдиң порталы',
'sitesupport'       => 'Белектер',

'retrievedfrom'       => '"$1" арынында парлаттынган',
'newmessageslink'     => 'чаа чагаалар',
'newmessagesdifflink' => 'бурунгу өскерлири',
'editsection'         => 'өскертир',
'editold'             => 'өскертир',
'toc'                 => 'Допчу',
'showtoc'             => 'көргүзер',
'hidetoc'             => 'чажырар',
'viewdeleted'         => '{{grammar:accusative|$1}} көөр?',
'restorelink'         => '$1 балаттынган өскерилгелер',

# Short words for each namespace, by default used in the 'article' tab in monobook
'nstab-main'      => 'Чүүл',
'nstab-user'      => 'Ажыглакчының арыны',
'nstab-media'     => 'Медиа арыны',
'nstab-special'   => 'Тускай',
'nstab-project'   => 'Проект арыны',
'nstab-image'     => 'Файл',
'nstab-mediawiki' => 'Чагаа',
'nstab-template'  => 'Хээ',
'nstab-help'      => 'Дуза',
'nstab-category'  => 'Бөлүк',

# Main script and global functions
'nosuchaction'      => 'Ындыг ажыл чок',
'nosuchspecialpage' => 'Ындыг арын чок',

# General errors
'noconnect'       => 'Буруулуг болдувус! Викиде чамдык техниктиг бергедээшкиннер бар болганындан database серверинче коштунмаан. <br />
$1',
'laggedslavemode' => 'Оваарымчалыг: Бо арында чаартыышкыннар чок болуп болур',
'badtitle'        => 'Багай ат',
'viewsource'      => 'Бажы көөр',

# Login and logout pages
'yourname'           => 'Aжыглакчының ады',
'yourpassword'       => 'Чажыт сөс',
'remembermypassword' => 'Мени сактып алыр',
'login'              => 'Кирер',
'userlogin'          => 'Кирер / create account',
'logout'             => 'Үнер',
'userlogout'         => 'Үнер',
'gotaccountlink'     => 'Кирер',
'createaccountmail'  => 'email-биле',
'badretype'          => 'Силернин парлаан чажыт созуңер таарышпас.',
'userexists'         => 'Силернин парлаан адыңар амгы уеде ажыглаттынып турар. өске аттан шилип алыңар.',
'username'           => 'Aжыглакчының ады:',
'yourrealname'       => 'Шын адыңар *',
'yourlanguage'       => 'Дылыңар:',
'yournick'           => 'Шола ат:',
'loginerror'         => 'Багай кирери',
'loginsuccesstitle'  => 'Чедимчелиг кирери',
'loginlanguagelabel' => 'Дыл: $1',

# Edit page toolbar
'bold_sample'    => 'Карартыр',
'italic_sample'  => 'Ийлендирер',
'link_sample'    => 'Холбаа ады',
'link_tip'       => 'Иштики холбаа',
'extlink_sample' => 'http://www.example.com холбаа ады',
'extlink_tip'    => 'Даштыкы холбаа (http:// сактып алыр)',
'nowiki_sample'  => 'Форматтаваан текстини бээр салыр',

# Edit pages
'minoredit'          => 'Бо өскерлири биче-дир',
'watchthis'          => 'Бо арынны көөр',
'showdiff'           => 'Өскерлирилер көргүзер',
'nosuchsectiontitle' => 'Ындыг бөлгүм чок',
'loginreqlink'       => 'кирер',
'accmailtitle'       => 'Чажыт сөс чоргустунган.',
'accmailtext'        => '"{{grammar:genitive|$1}}" чажыт сөстү {{grammar:directive1|$2}} чоргузуптувус.',
'newarticle'         => '(Чаа)',
'editing'            => '$1 арынны өскертип турар',
'editinguser'        => '<b>$1</b> ажыглакчыны өскертип турар',
'editingsection'     => '$1 бөлгүмнү өскертип турар',
'yourtext'           => 'Силерниң сөзүглел',
'yourdiff'           => 'Ылгалдар',

# History pages
'revhistory'          => 'Өскерлири төөгүзү',
'nohistory'           => 'Бо арынның өскерлири төөгүзү чок.',
'currentrev'          => 'Амгы үе өскерлири',
'currentrevisionlink' => 'Амгы үе өскерлири',
'next'                => 'соонда',

# Revision feed
'history-feed-title' => 'Өскерлири төөгүзү',

# Diffs
'compareselectedversions' => 'Шилип алган хевирлери деңнээр',

# Search results
'powersearch' => 'Дилээр',

# Preferences page
'preferences'       => 'Дээре деп санаарылар',
'changepassword'    => 'Чажыт сөстү өскертир',
'prefs-personal'    => 'Ажыглакчының медээлери',
'prefs-rc'          => 'Дээм чаагы өскерлирилер',
'saveprefs'         => 'Шыгжаар',
'oldpassword'       => 'Эгри чажыт сөс:',
'newpassword'       => 'Чаа чажыт сөс:',
'textboxsize'       => 'Өскертир',
'searchresultshead' => 'Дилээр',
'files'             => 'Файлдар',

# Recent changes
'recentchanges'   => 'Өскерлиишкиннер',
'rcshowhideminor' => 'Биче өскерлирилерни $1',
'rcshowhidebots'  => 'Боц $1',
'rcshowhideliu'   => 'Кирер ажыглакчыларны $1',
'rcshowhideanons' => 'Ат чок ажыглакчыларны $1',
'rcshowhidemine'  => 'Мээң өскерлиринеримни $1',
'hide'            => 'Чажырар',
'show'            => 'көргүзер',

# Upload
'upload'     => 'Файлду киирер',
'uploadbtn'  => 'Файлду киирер',
'filename'   => 'Файлдың ады:',
'filesource' => 'Эгези:',

# Image list
'ilsubmit'              => 'Дилээр',
'imgfile'               => 'файл',
'filehist'              => 'Файлдың төөгүзү',
'filehist-current'      => 'амгы үе',
'filehist-datetime'     => 'Үйе/Шак',
'filehist-user'         => 'Ажыглакчы',
'imagelinks'            => 'Холбаалар',
'imagelist_name'        => 'Ат',
'imagelist_user'        => 'Ажыглакчы',
'imagelist_size'        => 'Хемчээл',
'imagelist_description' => 'Тодарадып бижээни',

# File deletion
'filedelete-submit' => 'Ап каар',

# Random pages
'randompage' => 'Даап арын',

'brokenredirects-edit'   => '(өскертир)',
'brokenredirects-delete' => '(ап каар)',

# Miscellaneous special pages
'allpages'          => 'Шупту арыннар',
'shortpages'        => 'Чолдак арыннар',
'longpages'         => 'Узун арыннар',
'specialpages'      => 'Тускай арыннар',
'spheading'         => 'Шупту ажыглакчыниң тускай арыннар',
'newpages'          => 'Чаа Арыннар',
'newpages-username' => 'Ажыглакчының ады:',

# Book sources
'booksources-go' => 'Чоруур',

# Special:Log
'specialloguserlabel' => 'Ажыглакчы:',

# Special:Allpages
'allarticles'    => 'Шупту чүүлдер',
'allpagesprev'   => 'Пертинде',
'allpagesnext'   => 'Соонда',
'allpagessubmit' => 'Чоруур',

# E-mail user
'emailmessage' => 'Дыңнадыры',

'enotif_newpagetext' => 'Бо чаа арын-дыр.',
'enotif_anon_editor' => 'ат чок ажыглакчы $1',

# Delete/protect/revert
'actioncomplete' => 'Ажыл доосту',

# Contributions
'contributions' => 'Ажыглакчыниң деткимчемнер',
'mycontris'     => 'Мээң деткимчемнерим',

# What links here
'whatlinkshere' => 'Pages that link here',

# Block/unblock
'ipaddress'                => 'ИП-адрес',
'ipadressorusername'       => 'ИП-адрес азы aжыглaкчының aды',
'badipaddress'             => 'Багай ИП-адрес',
'infiniteblock'            => 'кезээ-мөңгеде',
'block-log-flags-anononly' => 'чаңгыс ат чок ажыглакчылар',

# Namespace 8 related
'allmessages'        => 'Системниң дыңнадырылар',
'allmessagesname'    => 'Ат',
'allmessagesdefault' => 'Default сөзүглел',
'allmessagescurrent' => 'Амгы сөзүглел',

# Attribution
'anonymous' => '{{grammar:genitive|{{SITENAME}}}} ат чок ажыглакчызы(лары)',

);
