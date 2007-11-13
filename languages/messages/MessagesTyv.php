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
'tog-highlightbroken'  => '<a href="" class="new">Бо ышкаш</a> бузук холбааларны форматтап ирги.  (азы: бо ышкаш<a href="" class="internal">?</a>).',
'tog-hideminor'        => 'Сөөлгү өскерлиишкиннер арында бичии өскерлиишкиннерни чажырар',
'tog-showtoolbar'      => 'Өскертир херекселдерни көргүзер (JavaScript)',
'tog-editondblclick'   => 'Арынны өскертирде ийи катап базар (JavaScript)',
'tog-rememberpassword' => 'Мени сактып алыр',

'underline-always' => 'Кезээде',
'underline-never'  => 'Кажан-даа',

# Dates
'sunday'    => 'Улуг хүн',
'monday'    => 'Бир дугаар хүн',
'tuesday'   => 'Ийи дугаар хүн',
'wednesday' => 'Үш дугаар хүн',
'thursday'  => 'Дөрт дугаар хүн',
'friday'    => 'Беш дугаар хүн',
'saturday'  => 'Чартык улуг хүн',
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
'categories'            => 'Бөлүктер',
'pagecategories'        => '{{PLURAL:$1|Бөлүк|Бөлүктер}}',
'category_header'       => '"$1" бөлүкте чүүлдер',
'subcategories'         => 'Бичии бөлүктер',
'category-media-header' => '"$1" бөлүкте медиа',
'category-empty'        => "''Амгы бо бөлүкте арын чок азы медиа чок.''",

'about'          => 'Дугайында',
'article'        => 'Допчу арын',
'newwindow'      => '(чаа козенекке ажыытынар)',
'cancel'         => 'Соксаар',
'qbfind'         => 'Тывар',
'qbbrowse'       => 'Ажыдар',
'qbedit'         => 'Өскертир',
'qbpageoptions'  => 'Бо арын',
'qbmyoptions'    => 'Мээң арыннарым',
'qbspecialpages' => 'Тускай арыннар',
'moredotdotdot'  => 'Ам-даа...',
'mypage'         => 'Мээң арыным',
'mytalk'         => 'Мээң чугаалажырым',
'anontalk'       => 'Бо ИП-адрестиң чугаа',
'navigation'     => 'Навигация',

'errorpagetitle'    => 'Алдаг',
'returnto'          => '{{grammar:directive1|$1}} дедир.',
'tagline'           => '{{grammar:ablative|{{SITENAME}}}}',
'help'              => 'Дуза',
'search'            => 'Дилээр',
'searchbutton'      => 'Дилээр',
'go'                => 'Чоруур',
'searcharticle'     => 'Чоруур',
'history'           => 'Арынның Төөгүзү',
'history_short'     => 'Төөгү',
'info_short'        => 'Медеглел',
'printableversion'  => 'Саазынга үндүрерин көөр',
'print'             => 'Саазынга үндүрер',
'edit'              => 'Өскертир',
'editthispage'      => 'Бо арынны өскертир',
'delete'            => 'Ап каар',
'deletethispage'    => 'Бо арынны ап каар',
'protect'           => 'Камгалаар',
'protectthispage'   => 'Бо арынны камгалаар',
'unprotect'         => 'Камгалалды ап каар',
'unprotectthispage' => 'Бо арынның камгалалын ап каар',
'newpage'           => 'Чаа арын',
'talkpage'          => 'Бо арын дугайында чугаалажыр',
'talkpagelinktext'  => 'Чугаалажыр',
'specialpage'       => 'Тускай Арын',
'personaltools'     => 'Хууда херекселдер',
'articlepage'       => 'Допчу арынны көөр',
'talk'              => 'Чугаалажыр',
'views'             => 'Көрүштер',
'userpage'          => 'Ажыглакчының арынын көөр',
'imagepage'         => 'Чурук арынын көөр',
'viewhelppage'      => 'Дуза арынын көөр',
'categorypage'      => 'Бөлүк арынын көөр',
'viewtalkpage'      => 'Чугаалажыры көөр',
'otherlanguages'    => 'Өске дылдарга',
'lastmodifiedat'    => 'Бо арын сөөлгү каттап $1 өскерилген.', # $1 date, $2 time
'jumptonavigation'  => 'навигация',
'jumptosearch'      => 'дилээр',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'         => '{{SITENAME}} дугайында',
'aboutpage'         => 'Project:Дугайында',
'currentevents'     => 'Амгы үеде болуушкуннар',
'currentevents-url' => 'Амгы үеде болуушкуннар',
'edithelp'          => 'Өскертиринге дуза',
'edithelppage'      => 'Help:Өскертир',
'helppage'          => 'Help:Допчузу',
'mainpage'          => 'Кол Арын',
'portal'            => 'Ниитилелдиң хаалгазы',
'portal-url'        => 'Project:Ниитилелдиң хаалгазы',
'sitesupport'       => 'Белектер',

'retrievedfrom'       => '"$1" арынында парлаттынган',
'newmessageslink'     => 'чаа чагаалар',
'newmessagesdifflink' => 'бурунгу өскерлиишкин',
'editsection'         => 'өскертир',
'editold'             => 'өскертир',
'toc'                 => 'Допчу',
'showtoc'             => 'көргүзер',
'hidetoc'             => 'чажырар',
'viewdeleted'         => '{{grammar:accusative|$1}} көөр?',
'restorelink'         => '$1 балаттынган өскерилгелер',
'feedlinks'           => 'Агым:',
'site-rss-feed'       => '$1 РСС Медээ Агымы',
'site-atom-feed'      => '$1 Атом Медээ Агымы',
'page-rss-feed'       => '"$1" РСС Медээ Агымы',
'page-atom-feed'      => '"$1" Атом Медээ Агымы',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Чүүл',
'nstab-user'      => 'Ажыглакчының арыны',
'nstab-media'     => 'Медиа арыны',
'nstab-special'   => 'Тускай',
'nstab-project'   => 'Проект арыны',
'nstab-image'     => 'Бижиири',
'nstab-mediawiki' => 'Чагаа',
'nstab-template'  => 'Хээ',
'nstab-help'      => 'Дуза',
'nstab-category'  => 'Бөлүк',

# Main script and global functions
'nosuchaction'      => 'Ындыг ажыл чок',
'nosuchspecialpage' => 'Ындыг арын чок',

# General errors
'error'              => 'Алдаг',
'databaseerror'      => 'Медээ шыгжамыры алдаг',
'noconnect'          => 'Буруулуг болдувус! Викиде чамдык техниктиг бергедээшкиннер бар болганындан database серверинче коштунмаан. <br />
$1',
'laggedslavemode'    => 'Оваарымчалыг: Бо арында чаартыышкыннар чок болуп болур',
'internalerror'      => 'Иштики алдаг',
'internalerror_info' => 'Иштики алдаг: $1',
'badtitle'           => 'Багай ат',
'viewsource'         => 'Бажы көөр',

# Login and logout pages
'yourname'           => 'Aжыглакчының ады',
'yourpassword'       => 'Чажыт сөс',
'remembermypassword' => 'Мени сактып алыр',
'login'              => 'Кирер',
'userlogin'          => 'Кирер / create account',
'logout'             => 'Үнер',
'userlogout'         => 'Үнер',
'gotaccountlink'     => 'Кирер',
'createaccountmail'  => 'е-чагаа-биле',
'badretype'          => 'Силернин парлаан чажыт созуңер таарышпас.',
'userexists'         => 'Силерниң парлаан адыңар амгы уеде ажыглаттынып турар. өске аттан шилип алыңар.',
'username'           => 'Aжыглакчының ады:',
'yourrealname'       => 'Шын адыңар *',
'yourlanguage'       => 'Дылыңар:',
'yournick'           => 'Шола ат:',
'loginerror'         => 'Багай кирери',
'loginsuccesstitle'  => 'Чедимчелиг кирери',
'loginlanguagelabel' => 'Дыл: $1',

# Password reset dialog
'resetpass_header'    => 'Чажыт сөстү катап чогаадып кылыр',
'resetpass_submit'    => 'Чажыт сөстү чоогадып кылыр база кирер.',
'resetpass_forbidden' => 'Бо викиде чажыт сөстү өскертивейн болбас',
'resetpass_missing'   => 'Бижиириниң медээ чок.',

# Edit page toolbar
'bold_sample'    => 'Карартыр',
'italic_sample'  => 'Ийлендирер',
'link_sample'    => 'Холбаа ады',
'link_tip'       => 'Иштики холбаа',
'extlink_sample' => 'http://www.чижек.com холбаа ады',
'extlink_tip'    => 'Даштыкы холбаа ("http://" чүве сактып алыр)',
'nowiki_sample'  => 'Форматтаваан сөзүглелини бээр салыр',
'nowiki_tip'     => 'Вики форматтаарыны херекке албас',
'image_sample'   => 'Чижек.jpg',
'media_sample'   => 'Чижек.ogg',

# Edit pages
'minoredit'          => 'Бо өскерлиишкин биче-дир',
'watchthis'          => 'Бо арынны истээр',
'savearticle'        => 'Арынны шыгжаар',
'showdiff'           => 'Өскерлиишкиннерни көргүзер',
'nosuchsectiontitle' => 'Ындыг бөлгүм чок',
'loginreqlink'       => 'кирер',
'accmailtitle'       => 'Чажыт сөс чоргустунган.',
'accmailtext'        => '"{{grammar:genitive|$1}}" чажыт сөстү {{grammar:directive1|$2}} чоргузуптувус.',
'newarticle'         => '(Чаа)',
'editing'            => '$1 арынны өскертип турар',
'editinguser'        => '<b>$1</b> ажыглакчыны өскертип турар',
'editingsection'     => '$1 бөлгүмнү өскертип турар',
'yourtext'           => 'Силерниң сөзүглелиңер',
'yourdiff'           => 'Ылгалдар',

# History pages
'revhistory'          => 'Үндүрериниң төөгүзү',
'nohistory'           => 'Бо арынның өскерлиишкин төөгүзү чок.',
'currentrev'          => 'Амгы үе үндүрери',
'currentrevisionlink' => 'Амгы үе үндүрери',
'next'                => 'соонда',

# Revision feed
'history-feed-title' => 'Үндүрериниң төөгүзү',

# Revision deletion
'rev-delundel' => 'көргүзер/чажырар',

# Diffs
'compareselectedversions' => 'Шилип алган хевирлери деңнээр',

# Search results
'powersearch' => 'Дилээр',

# Preferences page
'preferences'        => 'Дээре деп санаарылар',
'changepassword'     => 'Чажыт сөстү өскертир',
'math_unknown_error' => 'билбес алдаг',
'prefs-personal'     => 'Ажыглакчының медээлери',
'prefs-rc'           => 'Дээм чаагы өскерлиишкиннер',
'saveprefs'          => 'Шыгжаар',
'oldpassword'        => 'Эгри чажыт сөс:',
'newpassword'        => 'Чаа чажыт сөс:',
'textboxsize'        => 'Өскертир',
'searchresultshead'  => 'Дилээр',
'files'              => 'бижиирилер',

# Recent changes
'recentchanges'     => 'Өскерлиишкиннер',
'rcshowhideminor'   => 'Бичии өскерлиишкиннерни $1',
'rcshowhidebots'    => 'Боцту $1',
'rcshowhideliu'     => 'Кирер ажыглакчыларны $1',
'rcshowhideanons'   => 'Ат чок ажыглакчыларны $1',
'rcshowhidemine'    => 'Мээң өскерлиишкинимни $1',
'hist'              => 'төөгү',
'hide'              => 'Чажырар',
'show'              => 'көргүзер',
'newsectionsummary' => '/* $1 */ чаа бөлгүм',

# Upload
'upload'          => 'Бижиирини киирер',
'uploadbtn'       => 'Бижиирини киирер',
'reupload'        => 'Катап киирер',
'uploadnologin'   => 'Кирбес',
'uploaderror'     => 'Кииреринге алдаг',
'filename'        => 'бижиириниң ады:',
'filesource'      => 'Эгези:',
'savefile'        => 'бижиирини шыгжаар',
'watchthisupload' => 'Бо арынны истээр',

'upload-file-error' => 'Иштики алдаг',
'upload-misc-error' => 'Билбес кииреринге алдаг',

# Image list
'ilsubmit'              => 'Дилээр',
'imgfile'               => 'бижиири',
'filehist'              => 'Бижиириниң төөгүзү',
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

# MIME search
'download' => 'алыр',

# Random page
'randompage' => 'Даап арын',

'brokenredirects-edit'   => '(өскертир)',
'brokenredirects-delete' => '(ап каар)',

# Miscellaneous special pages
'ncategories'       => '$1 бөлүк',
'nlinks'            => '$1 холбаа',
'nmembers'          => '$1 кежигүн',
'nrevisions'        => '$1 үндүрери',
'nviews'            => '$1 көрүш',
'allpages'          => 'Шупту арыннар',
'shortpages'        => 'Чолдак арыннар',
'longpages'         => 'Узун арыннар',
'specialpages'      => 'Тускай арыннар',
'spheading'         => 'Шупту ажыглакчыниң тускай арыннары',
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

# Special:Listusers
'listusers-submit' => 'Көргүзер',

# E-mail user
'emailmessage' => 'Чагаа',

# Watchlist
'watchnologin'         => 'Кирбес',
'watch'                => 'Истээр',
'watchthispage'        => 'Бо арынны истээр',
'unwatch'              => 'Истевес',
'unwatchthispage'      => 'Бо арынны истевес',
'watchlist-show-bots'  => 'Боттуң өскерлиишкиннерин көргүзер',
'watchlist-hide-bots'  => 'Боттуң өскерлиишкиннерин чажырар',
'watchlist-show-own'   => 'Мээң өскерлиишкиннеримни көргүзер',
'watchlist-hide-own'   => 'Мээң өскерлиишкиннеримни чажырар',
'watchlist-show-minor' => 'Бичии өскерлиишкиннерни көргүзер',
'watchlist-hide-minor' => 'Бичии өскерлиишкиннерни чажырар',

# Displayed when you click the "watch" button and it's in the process of watching
'watching' => 'Истеп турар...',

'enotif_newpagetext' => 'Бо чаа арын-дыр.',
'enotif_anon_editor' => 'ат чок ажыглакчы $1',

# Delete/protect/revert
'actioncomplete' => 'Ажыл доосту',

# Undelete
'undelete-search-submit' => 'Дилээр',

# Contributions
'contributions' => 'Ажыглакчыниң деткимчемнери',
'mycontris'     => 'Мээң деткимчемнерим',
'uctop'         => ' (баш)',

'sp-contributions-newest'  => 'Эң чаа',
'sp-contributions-oldest'  => 'Эң эрги',
'sp-contributions-newer'   => 'Артык чаа $1',
'sp-contributions-older'   => 'Артык эрги $1',
'sp-contributions-newbies' => 'Чаңыс чаа кирерилерниң деткимчемнерин көргүзер',

# What links here
'whatlinkshere' => 'Pages that link here',

# Block/unblock
'ipaddress'                => 'ИП-адрес',
'ipadressorusername'       => 'ИП-адрес азы aжыглaкчының aды',
'badipaddress'             => 'Багай ИП-адрес',
'infiniteblock'            => 'кезээ-мөңгеде',
'block-log-flags-anononly' => 'чаңгыс ат чок ажыглакчылар',

# Move page
'move-watch' => 'Бо арынны истээр',

# Namespace 8 related
'allmessages'        => 'Системниң дыңнадыглары',
'allmessagesname'    => 'Ат',
'allmessagesdefault' => 'Default сөзүглел',
'allmessagescurrent' => 'Амгы сөзүглел',

# Tooltip help for the actions
'tooltip-feed-rss'  => 'Бо арының РСС медээ агымы',
'tooltip-feed-atom' => 'Бо арының Атом медээ агымы',
'tooltip-save'      => 'Силерниң өскерлиишкиннериңерни шыгжаар',

# Attribution
'anonymous' => '{{grammar:genitive|{{SITENAME}}}} ат чок ажыглакчызы(лары)',

'exif-subjectdistancerange-2' => 'Чоок көрүш',
'exif-subjectdistancerange-3' => 'ырак көрүш',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'шупту',
'imagelistall'     => 'шупту',
'watchlistall2'    => 'шупту',
'namespacesall'    => 'шупту',
'monthsall'        => 'шупту',

# Auto-summaries
'autosumm-new' => 'Чаа арын: $1',

# Watchlist editor
'watchlistedit-raw-titles' => 'Адар:',

);
