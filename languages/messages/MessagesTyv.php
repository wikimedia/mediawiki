<?php
/** Tyvan localization (Тыва дыл)
 * @package MediaWiki
 * @subpackage Language
 */

# From friends at tyvawiki.org

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
	'davinci' => 'ДаВинчи', //DaVinci
	'mono' => 'Моно', //Mono
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
'tog-hideminor' => 'Сөөлгү өскерлиишкиннер арында бичии өскерлиишкиннер чажырар', //Hide minor edits in recent changes
'tog-showtoolbar'		=> 'Редактолаар херекселтер көргүзер (JavaScript)', //Show edit toolbar (JavaScript)
'tog-editondblclick' => 'Арынны өскертирде ийи катап базар (JavaScript)', //Edit pages on double click (JavaScript)

'underline-always' => 'Кезээде', //Always
'underline-never' => 'Кажан-даа', //Never
'underline-default' => 'Browser default', //Browser default

# dates
'sunday' => 'Чеди дугаар хүн', //Sunday
'monday' => 'Бир дугаар хүн', //Monday
'tuesday' => 'Ийи дугаар хүн', //Tuesday
'wednesday' => 'Үш дугаар хүн', //Wednesday
'thursday' => 'Дөрт дугаар хүн', //Thursday
'friday' => 'Беш дугаар хүн', //Friday
'saturday' => 'Алды дугаар хүн', //Saturday
'january' => 'Бир ай', //January
'february' => 'ийи ай', //February
'march' => 'Үш ай', //March
'april' => 'Дөрт ай', //April
'may_long' => 'Беш ай', //May
'june' => 'Алды ай', //June
'july' => 'Чеди ай', //July
'august' => 'Сес ай', //August
'september' => 'Тос ай', //September
'october' => 'Он ай', //October
'november' => 'Он бир ай', //November
'december' => 'Он ийи ай', //December
'jan' => '1.ай', //Jan
'feb' => '2.ай', //Feb
'mar' => '3.ай', //Mar
'apr' => '4.ай', //Apr
'may' => '5.ай', //May
'jun' => '6.ай', //Jun
'jul' => '7.ай', //Jul
'aug' => '8.ай', //Aug
'sep' => '9.ай', //Sep
'oct' => '10.ай', //Oct
'nov' => '11.ай', //Nov
'dec' => '12.ай', //Dec

# Bits of text used by many pages:
#
'categories' => 'Бөлүктер', //Categories
'category_header' => '"$1" бөлүкте чүүлдер', //Articles in category $1
'subcategories' => 'Бичии бөлүктер', //Subcategories

'mainpage'		=> 'Кол Арын', //Main Page

'about'			=> 'Дугайында', //About
'aboutsite'		=> '{{SITENAME}} дугайында', //About {{SITENAME}}
'aboutpage'		=> 'Project:Дугайында', //Project:About
'article'		=> 'Азыраары арын', //Content page
'help'			=> 'Дуза', //Help
'helppage'		=> 'Дуза:Допчузу', //Help:Contents
'sitesupport'   => 'Белектер', //Donations
'newwindow'		=> '(чаа козенектен ажар)', //(opens in new window)
'edithelppage'	=> 'Дуза:Өскертир', //Help:Editing
'cancel'		=> 'Ап каар', //Cancel (Солуур)
'qbfind'		=> 'Тывар', //Find
'qbbrowse'		=> 'Көөр', //Browse
'qbedit'		=> 'Редакторлаар', //Edit
'qbpageoptions' => 'Бо арын', //This page
'qbpageinfo'	=> 'Context', //Context
'qbmyoptions'	=> 'Мээң арыннарым', //My pages
'qbspecialpages'	=> 'Тускай арыннар', //Special pages
'moredotdotdot'	=> 'Ам-даа...', //More...
'mypage'		=> 'Мээң арыным', //My page
'mytalk'		=> 'Мээң чугаалажырым', //My talk чугааm?
'anontalk'		=> 'Бо ИП-адрестиң чугаа', //Talk for this IP
'navigation' => 'Навигация', //Navigation


'errorpagetitle' => "Частырыг", //Error
'returnto'		=> "{{grammar:directive1|$1}} дедир.", //Return to $1.
'tagline'      	=> "{{grammar:ablative|{{SITENAME}}}}", //From {{SITENAME}}
'whatlinkshere'	=> 'Pages that link here', //Pages that link here
'help'			=> 'Дуза', //Help
'search'		=> 'Дилээр', //Search
'searchbutton'	=> 'Дилээр', //Search
'go'		=> 'Чоруур', //Go
'searcharticle'		=> 'Чоруур', //Go
'history'		=> 'Арынның Төөгүзү', //Page history
'history_short' => 'Төөгү', //History
'printableversion' => 'Саазынга үндүрерин көөр', //Printable version (Парлатынар арын)
'permalink'     => 'Permanent link',
'print' => 'Саазынга үндүрер', //Print
'edit' => 'Өскертир', //Edit
'editthispage'	=> 'Бо арынны өскертир', //Edit this page
'delete' => 'Ап каар', //Delete
'deletethispage' => 'Бо арынны ап каар', //Delete this page
'protect' => 'Камгалал', //Protect
'protectthispage' => 'Бо арынны камгалаар', //Protect this page
'unprotect' => 'Камгалалды ап каар', //unprotect
'unprotectthispage' => 'Бо арынның камгалалын ап каар', //Unprotect this page
'newpage' => 'Чаа Арын', //New page
'talkpage'		=> 'Бо арын дугайында чугаалажыр', //Discuss this page
'specialpage' => 'Тускай Арын', //Special Page
'personaltools' => 'Херекселдер',  //Personal tools
'articlepage'	=> 'Допчу арынны көргүзер', //View content page
'talk' => 'Чугаалажыр', //Discussion
'userpage' => 'Ажыглакчыниң арынын көргүзер', //View user page
'imagepage' => 	'Чурук арынын көргүзер', //View image page
'viewtalkpage' => 'Чугаалажыры көргүзер', //View discussion
'otherlanguages' => 'Өске дылдарга', //In other languages
'lastmodifiedat'	=> 'Бо арын сөөлгү каттап $1 өскерилген.', //This page was last modified $2, $1.
//'viewcount'		=> 'Бо арын $1 каттап ажыттынган.', //This page has been accesed $1 times.
'retrievedfrom' => "\"$1\" арынында парлаттынган", //Retrieved from \"$1\"
'newmessageslink' => 'чаа чагаалар', //new messages
'editsection'=>'өскертир', //edit
'editold'=>'өскертир', //edit
'toc' => 'Допчу', //Contents
'showtoc' => 'көргүзер', //show
'hidetoc' => 'чажырар', //hide
'restorelink' => "$1 балаттынган өскерилгелер", //$1 deleted edits

# Short words for each namespace, by default used in the 'article' tab in monobook
'nstab-main' => 'Чүүл', //Article
'nstab-user' => 'Ажыглакчының арыны', //User page
'nstab-media' => 'Медиа арыны', //Media page
'nstab-special' => 'Тускай', //Special
'nstab-project' => 'Проект арыны', //Project page
'nstab-image' => 'Файл', //File
'nstab-mediawiki' => 'Чагаа', //Message
'nstab-template' => 'Хээ', //Template
'nstab-help' => 'Дуза', //Help
'nstab-category' => 'Бөлүк', //Category

# Main script and global functions
#
'nosuchspecialpage' => 'Ындыг арын чок', //No such special page

# General errors
#
'noconnect'		=> 'Буруулуг болдувус! Викиде чамдык техниктиг бергедээшкиннер бар болганындан database серверинче коштунмаан. <br />
$1', //Sorry! The wiki is experiencing some technical difficulties, and cannot contact the database server.
'laggedslavemode'   => 'Оваарымчалыг: Бо арында чаартыышкыннар чок болуп болур', //Warning: Page may not contain recent updates.

# Login and logout pages
#
'yourname'		=> 'Aжыглакчының ады', //Username
'yourpassword'	=> 'Чажыт сөс', //Password
'remembermypassword' => 'Мени сактып алыр', //Remember me
'createaccountmail'	=> 'email-биле', //by e-mail
'badretype'		=> 'Силернин парлаан чажыт созуңер таарышпас.', //The passwords you entered do not match.
'userexists'	=> 'Силернин парлаан адыңар амгы уеде ажыглаттынып турар. өске аттан шилип алыңар.',  //Username entered is already in use.  Please choose a different name.
'username'		=> 'Aжыглакчының ады:', //Username:
'yourrealname'		=> 'Шын адыңар *', //Real name *
'yourlanguage'	=> 'Дылыңар:', //Language:
'yournick'		=> 'Шола ат:', //Nickname:

# Edit page toolbar
'bold_sample'=>'Карартыр',  //Bold text
'italic_sample'=>'Ийлендирер', //Italic text
'nowiki_sample'=>'Форматтаваан текстини бээр салыр', //Insert non-formatted text here

# Edit pages
#
'watchthis'		=> 'Бо арынны көөр', //Watch this page
'accmailtitle' => 'Чажыт сөс чоргустунган.', //Password sent.
'accmailtext' => '"{{grammar:genitive|$1}}" чажыт сөстү {{grammar:directive1|$2}} чоргузуптувус.', //The password for "$1" has been sent to $2.
'newarticle'	=> '(Чаа)', //(New)
'yourtext'		=> 'Силерниң сөзүглел', //Your text

# History pages
#
'next'			=> 'соонда', //next

# Diffs
#
'compareselectedversions' => 'Шилип алган хевирлери деңнээр', //Compare selected versions

# Preferences page
#
'preferences'	=> 'Дээре деп санаарылар', //Preferences
'prefs-personal' => 'Ажыглакчының медээлери', //User profile
'saveprefs'		=> 'Шыгжаар', //Save
'oldpassword'	=> 'Эгри чажыт сөс:', //Old password:
'newpassword'	=> 'Чаа чажыт сөс:', //New password:
'searchresultshead' => 'Дилээр', //Search
'files'			=> 'Файлдар', //Files

# Recent changes
#
'recentchanges' => 'Өскерлиишкиннер', //Recent changes
'hide'			=> 'Чажырар', //Hide
'show'			=> 'көргүзер', //show

# Upload
#
'filename'		=> 'Файлдың ады', //Filename
'filesource' => 'Эгези', //Source

# Image list
#
'ilsubmit'		=> 'Дилээр', //Search

# Miscellaneous special pages
#
'randompage'	=> 'Даап арын', //Random page
'specialpages'	=> 'Тускай арыннар', //Special pages
'spheading'		=> 'Шупту ажыглакчыниң тускай арыннар', //Special pages for all users
'newpages'		=> 'Чаа Арыннар', //New pages


# Special:Allpages
'allarticles'		=> 'Шупту чүүлдер', //All articles
'allpagesprev'		=> 'Пертинде', //Previous
'allpagesnext'		=> 'Соонда', //Next
'allpagessubmit'	=> 'Чоруур', //Go

# E this user
#
'emailmessage'	=> 'Дыңнадыры', //Message

# Watchlist
#
'enotif_newpagetext'=> 'Бо чаа арын-дыр.', //This is a new page.

# Delete/protect/revert
#
'actioncomplete' => 'Ажыл доосту', //Action complete

# Contributions
#
'contributions' => 'Ажыглакчыниң деткимчемнер', //User contributions
'mycontris'     => 'Мээң деткимчемнерим', //My contributions

# Block/unblock IP
#
'ipaddress'		=> 'ИП-адрес', //IP Address
'ipadressorusername' => 'ИП-адрес азы aжыглaкчының aды', //IP Address or username
'badipaddress'	=> 'Багай ИП-адрес', //Invalid IP address
'infiniteblock' => 'кезээ-мөңгеде', //infinite

# Make sysop
'makesysopname'		=> 'Ажыглакчыниң ады:', //Name of the user:

# Namespace 8 related

'allmessages'	=> 'Системниң дыңнадырылар', //System messages
'allmessagesname' => 'Ат', //Name
'allmessagesdefault' => 'Default сөзүглел', //Default text
'allmessagescurrent' => 'Амгы сөзүглел', //Current text

# labels for User: and Title: on Special:Log pages
'specialloguserlabel' => 'Ажыглакчы: ', //User:

);
?>
