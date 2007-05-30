<?php
/** Georgian (ქართული)
  *
  * @addtogroup Language
  */
$namespaceNames = array(
	NS_MEDIA            => 'მედია',
	NS_SPECIAL          => 'სპეციალური',
	NS_MAIN             => '',
	NS_TALK             => 'განხილვა',
	NS_USER             => 'მომხმარებელი',
	NS_USER_TALK        => 'მომხმარებელი_განხილვა',
	# NS_PROJECT set by $wgMetaNamespace
	NS_PROJECT_TALK     => '$1_განხილვა',
	NS_IMAGE            => 'სურათი',
	NS_IMAGE_TALK       => 'სურათი_განხილვა',
	NS_MEDIAWIKI        => 'მედიავიკი',
	NS_MEDIAWIKI_TALK   => 'მედიავიკი_განხილვა',
	NS_TEMPLATE         => 'თარგი',
	NS_TEMPLATE_TALK    => 'თარგი_განხილვა',
	NS_HELP             => 'დახმარება',
	NS_HELP_TALK        => 'დახმარება_განხილვა',
	NS_CATEGORY         => 'კატეგორია',
	NS_CATEGORY_TALK    => 'კატეგორია_განხილვა'
);

$linkPrefixExtension = true;

$linkTrail = '/^([a-zაბგდევზთიკლმნოპჟრსტუფქღყშჩცძწჭხჯჰ“»]+)(.*)$/sDu';

# Note to translators:
#   Please include the English words as synonyms.  This allows people
#   from other wikis to contribute more easily.
#
$magicWords = array(
	'redirect' => array( 0   , '#REDIRECT', '#გადამისამართება' ),
);

$messages = array(
# User preference toggles
'tog-underline'               => 'ბმულებზე გაზის გასმა:',
'tog-highlightbroken'         => 'აჩვენე არარსებული ბმულები <a href="" class="new">როგორც ეს</a> (ალტერნატივა: როგორც ეს<a href="" class="internal">?</a>).',
'tog-hideminor'               => 'უკანასკნელ ცვლილებებში მცირე რედაქტირებების დამალვა',
'tog-extendwatchlist'         => 'განავრცეთ კონტროლის სია ისე, რომ აჩვენოთ ყველა შესაძლებელი ცვლილება',
'tog-usenewrc'                => 'გაზარდეთ ბოლო ცვლილებების სია (ჯავასკრიპტი)',
'tog-numberheadings'          => 'სათაურების ავტომატურად გადანომვრა',
'tog-showtoolbar'             => 'სარედაქტორო ინსტრუმენტების პანელის (ჯავასკრიპტი) ჩვენება',
'tog-editondblclick'          => 'გვერდების რედაქტირება მოახდინეთ ორმაგი დაჭერით (ჯავასკრიპტი)',
'tog-editsection'             => "ნება დართეთ სექციის რედაქტირებაზე '[რედაქტირება]' ბმულების გავლით",
'tog-editsectiononrightclick' => 'ნება დართეთ სექციის რედაქტირებაზე მარჯვენა ღილაკზე დაჭერით<br />სექციის სათაურებზე (ჯავასკრიპტი)',
'tog-showtoc'                 => 'აჩვენეთ სარჩევი ცხრილი (იმ გვერდებისათვის, რომელსაც სამზე მეტი სათაური აქვთ)',
'tog-rememberpassword'        => 'სესიებს შორის პაროლის დამახსოვრება',
'tog-editwidth'               => 'სარედაქტირო ყუთს აქვს სრული სიგანე',
'tog-watchcreations'          => 'გვერდები, რომელიც მე გავხსენი, დაამატეთ ჩემს საკონტროლო სიას',
'tog-watchdefault'            => 'დამალეთ ბოტის რედაქტირებები საკონტროლო სიიდან',
'tog-previewontop'            => 'რედაქტირების ფანჯრამდე წინასწარი ხედვის ჩვენება',
'tog-previewonfirst'          => 'პირველი რედაქტიებისას წინასწარი გადახედვის ჩვენება',
'tog-enotifwatchlistpages'    => 'მომწერეთ როდესაც გვერდი, რომელსაც მე ვაკონტროლებ შეიცვლება',
'tog-enotifusertalkpages'     => 'მომწერეთ, როდესაც ჩემი მომხმარებლის განხილვის გვერდი შეიცვლება',
'tog-enotifminoredits'        => 'მომწერეთ ასევე მცირე რედაქტირებების შესახებ გვერდებზე',
'tog-enotifrevealaddr'        => 'ჩემი ელ. ფოსტის მისამართი შეხსენებების წერილებში აჩვენეთ',
'tog-shownumberswatching'     => 'კონტროლის ქვეშ მყოფი მომხმარებელთა რაოდენობის ჩვენება',
'tog-fancysig'                => 'გამოუყენებელი ხელმოწერები (ავტომატური ბმულის გარეშე)',
'tog-showjumplinks'           => 'დამხმარე ბმულების "გადასვლა -კენ" ჩართვა',
'tog-uselivepreview'          => 'გამოიყენეთ ახალი წინასწარი გადახედვა (ჯავასკრიპტი) (ექსპერიმენტული)',
'tog-watchlisthideown'        => 'დამალეთ საკონტროლო სიიდან ჩემი რედაქტირებები',
'tog-watchlisthidebots'       => 'დამალეთ საკონტროლო სიიდან ჩემი რედაქტირებები',
'tog-watchlisthideminor'      => 'დამალეთ საკონტროლო სიიდან მცირე რედაქტირებები',

'underline-always' => 'ყოველთვის',
'underline-never'  => 'არასოდეს',

'skinpreview' => '(წინასწარი გადახედვა)',

# Dates
'sunday'        => 'კვირა',
'monday'        => 'ორშაბათი',
'tuesday'       => 'სამშაბათი',
'wednesday'     => 'ოთხშაბათი',
'thursday'      => 'ხუთშაბათი',
'friday'        => 'პარასკევი',
'saturday'      => 'შაბათი',
'sun'           => 'კვი',
'mon'           => 'ორშ',
'tue'           => 'სამ',
'wed'           => 'ოთხ',
'thu'           => 'ხუთ',
'fri'           => 'პარ',
'sat'           => 'შაბ',
'january'       => 'იანვარი',
'february'      => 'თებერვალი',
'march'         => 'მარტი',
'april'         => 'აპრილი',
'may_long'      => 'მაისი',
'june'          => 'ივნისი',
'july'          => 'ივლისი',
'august'        => 'აგვისტო',
'september'     => 'სექტემბერი',
'october'       => 'ოქტომბერი',
'november'      => 'ნოემბერი',
'december'      => 'დეკემბერი',
'january-gen'   => 'იანვრის',
'february-gen'  => 'თებერვლის',
'march-gen'     => 'მარტის',
'april-gen'     => 'აპრილის',
'may-gen'       => 'მაისის',
'june-gen'      => 'ივნისის',
'july-gen'      => 'ივლისის',
'august-gen'    => 'აგვისტოს',
'september-gen' => 'სექტემბრის',
'october-gen'   => 'ოქტომბრის',
'november-gen'  => 'ნოემბრის',
'december-gen'  => 'დეკემბრის',
'jan'           => 'იან',
'feb'           => 'თებ',
'mar'           => 'მარ',
'apr'           => 'აპრ',
'may'           => 'მაი',
'jun'           => 'ივნ',
'jul'           => 'ივლ',
'aug'           => 'აგვ',
'sep'           => 'სექ',
'oct'           => 'ოქტ',
'nov'           => 'ნოე',
'dec'           => 'დეკ',

# Bits of text used by many pages
'categories'      => 'კატეგორიები',
'pagecategories'  => '{{PLURAL:$1|კატეგორია|კატეგორიები}}',
'category_header' => 'სტატიები კატეგორიაში "$1"',
'subcategories'   => 'ქვეკატეგორიები',

'linkprefix' => '/^(.*?)(„|«)$/sD',

'about'          => 'შესახებ',
'article'        => 'სტატია',
'newwindow'      => '(ახალ ფანჯარაში)',
'cancel'         => 'გაუქმება',
'qbfind'         => 'ძებნა',
'qbbrowse'       => 'მიმოხილვა',
'qbedit'         => 'რედაქტირება',
'qbpageoptions'  => 'ეს გვერდი',
'qbpageinfo'     => 'კონტექსტი',
'qbmyoptions'    => 'ჩემი გვერდები',
'qbspecialpages' => 'სპეციალური გვერდები',
'moredotdotdot'  => 'მეტი...',
'mypage'         => 'ჩემი გვერდი',
'mytalk'         => 'ჩემი განხილვა',
'anontalk'       => 'ამ IP-ს განხილვა',
'navigation'     => 'ნავიგაცია',

'errorpagetitle'    => 'შეცდომა',
'returnto'          => '$1-ზე დაბრუნება.',
'tagline'           => '{{SITENAME}}დან',
'help'              => 'დახმარება',
'search'            => 'ძიება',
'searchbutton'      => 'ძიება',
'go'                => 'გვერდი',
'searcharticle'     => 'გვერდი',
'history'           => 'გვერდის ისტორია',
'history_short'     => 'ისტორია',
'updatedmarker'     => 'ჩემი უკანასკნელი შემოსვლიდან ცვლილებები',
'info_short'        => 'ინფორმაცია',
'printableversion'  => 'დასაბეჭდი ვერსია',
'permalink'         => 'მუდმივი ბმული',
'print'             => 'ბეჭდვა',
'edit'              => 'რედაქტირება',
'editthispage'      => 'ამ გვერდის რედაქტირება',
'delete'            => 'წაშლა',
'deletethispage'    => 'ამ გვერდის წაშლა',
'undelete_short'    => '$1 ცვლილების აღდგენა',
'protect'           => 'დაცვა',
'unprotect'         => 'დაცვის მოხსნა',
'unprotectthispage' => 'გვერდის დაცვის მოხსნა',
'newpage'           => 'ახალი გვერდი',
'talkpage'          => 'განიხილეთ ეს გვერდი',
'talkpagelinktext'  => 'განხილვა',
'specialpage'       => 'სპეციალური გვერდი',
'postcomment'       => 'დაურთეთ კომენტარი',
'articlepage'       => 'სტატიის ნახვა',
'talk'              => 'განხილვა',
'toolbox'           => 'ხელსაწყოები',
'userpage'          => 'მომხმარებლის გვერდის ხილვა',
'projectpage'       => 'პროექტის გვერდის ხილვა',
'imagepage'         => 'სურათის გვერდის ნახვა',
'categorypage'      => 'კატეგორიის გვერდის ხილვა',
'otherlanguages'    => 'სხვა ენებზე',
'redirectedfrom'    => '(გადამისამართდა გვერდიდან $1)',
'redirectpagesub'   => 'გადამისამართების გვერდი',
'lastmodifiedat'    => 'ეს გვერდი ბოლოს განახლდა $2, $1.', # $1 date, $2 time
'jumptonavigation'  => 'ნავიგაცია',
'jumptosearch'      => 'ძიება',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'         => '{{SITENAME}}-ის შესახებ',
'aboutpage'         => 'პროექტი:შესახებ',
'bugreports'        => 'ანგარიში შეცდომის შესახებ',
'bugreportspage'    => '{{ns:project}}:ანგარიში შეცდომის შესახებ',
'currentevents'     => 'ახალი ამბები',
'currentevents-url' => 'ახალი ამბები',
'disclaimers'       => 'პასუხისმგებლობის უარყოფა',
'disclaimerpage'    => '{{ns:project}}:პასუხისმგებლობის უარყოფა',
'edithelp'          => 'რედაქტირების დახმარება',
'edithelppage'      => '{{ns:project}}:რედაქტირების დახმარება',
'faq'               => 'ხშირი შეკითხვები',
'faqpage'           => '{{ns:project}}:ხშირი შეკითხვები',
'helppage'          => '{{ns:project}}:დახმარება',
'mainpage'          => 'მთავარი გვერდი',
'portal'            => 'საზოგადოების პორტალი',
'portal-url'        => '{{ns:project}}:საზოგადოების პორტალი',
'privacy'           => 'კონფიდენციალურობის პოლიტიკა',
'privacypage'       => '{{ns:project}}:კონფიდენციალურობის პოლიტიკა',
'sitesupport'       => 'შეწირულობები',
'sitesupport-url'   => '{{ns:project}}:შეწირულობები',

'badaccess' => 'აკრძალული მოქმედება',

'youhavenewmessages'  => 'თქვენ გაქვთ $1 ($2).',
'newmessageslink'     => 'ახალი შეტყობინებები',
'newmessagesdifflink' => 'განსხვავება უკანასკნელ მდგომარეობას შორის',
'editsection'         => 'რედაქტირება',
'editold'             => 'რედაქტირება',
'editsectionhint'     => 'სექციის რედაქტირება: $1',
'toc'                 => 'სარჩევი',
'showtoc'             => 'ჩვენება',
'hidetoc'             => 'დამალვა',
'thisisdeleted'       => 'გსურთ განიხილოთ ან აღადგინოთ $1?',
'viewdeleted'         => 'იხილე $1?',
'restorelink'         => '{{PLURAL:$1|ერთი წაშლილი რედაქტირება|$1 წაშლილი რედაქტირება}}',

# Short words for each namespace, by default used in the 'article' tab in monobook
'nstab-main'      => 'სტატია',
'nstab-user'      => 'მომხმარებლის გვერდი',
'nstab-media'     => 'მედია',
'nstab-special'   => 'სპეციალური',
'nstab-project'   => 'პროექტის გვერდი',
'nstab-image'     => 'ფაილი',
'nstab-mediawiki' => 'შეტყობინება',
'nstab-template'  => 'თარგი',
'nstab-help'      => 'დახმარება',
'nstab-category'  => 'კატეგორია',

# Login and logout pages
'login'      => 'შესვლა',
'userlogin'  => 'შესვლა / ანგარიშის გახსნა',
'logout'     => 'გასვლა',
'userlogout' => 'გასვლა',

# Diffs
'difference'              => '(სხვაობა ვერსიებს შორის)',
'lineno'                  => 'ხაზი $1:',
'editcurrent'             => 'ამ გვერდის ამჟამინდელი ვერსიის რედაქტირება',
'compareselectedversions' => 'არჩეული ვერსიების შედარება',

# Search results
'blanknamespace' => '(მთავარი)',

# Preferences page
'preferences'       => 'კონფიგურაცია',
'mypreferences'     => 'ჩემი კონფიგურაცია',
'qbsettings'        => 'სწრაფი ზოლი',
'changepassword'    => 'პაროლის შეცვლა',
'dateformat'        => 'თარიღის ფორმატი',
'datetime'          => 'თარიღი და დრო',
'prefs-personal'    => 'მომხმარებლის მონაცემები',
'prefs-rc'          => 'ბოლო ცვლილებები',
'prefs-watchlist'   => 'კონტროლის სია',
'saveprefs'         => 'შენახვა',
'resetprefs'        => 'გადატვირთვა',
'oldpassword'       => 'ძველი პაროლი:',
'newpassword'       => 'ახალი პაროლი:',
'textboxsize'       => 'რედაქტირება',
'rows'              => 'რიგები:',
'columns'           => 'სვეტები:',
'searchresultshead' => 'ძიება',
'contextlines'      => 'სტრიქონები შედეგის მიხედვით:',
'contextchars'      => 'კონტექსტი სტრიქონების მიხედვით:',
'savedprefs'        => 'თქვენს მიერ შერჩეული პარამეტრები დამახსოვრებულია.',
'localtime'         => 'ლოკალური დრო',
'guesstimezone'     => 'ბრაუზერიდან შევსება',
'allowemail'        => 'შესაძლებელია ელ. წერილების მიღება სხვა მომხმარებლებისაგან',
'defaultns'         => 'სტანდარტული ძიება ამ სახელთა სივრცეებში:',
'default'           => 'სტანდარტული',
'files'             => 'ფაილები',

# Groups
'group'            => 'ჯგუფი:',
'group-bot'        => 'ბოტები',
'group-sysop'      => 'ადმინისტრატორები',
'group-bureaucrat' => 'ბიუროკრატები',
'group-all'        => '(ყველა)',

'group-bot-member'        => 'ბოტი',
'group-sysop-member'      => 'ადმინისტრატორი',
'group-bureaucrat-member' => 'ბიუროკრატი',

'grouppage-bot'        => '{{ns:project}}:ბოტები',
'grouppage-sysop'      => '{{ns:project}}:ადმინისტრატორები',
'grouppage-bureaucrat' => '{{ns:project}}:ბიუროკრატები',

# Recent changes
'nchanges'        => '$1 ცვლილები',
'recentchanges'   => 'ბოლო ცვლილებები',
'rclistfrom'      => 'ახალი ცვლილებების ჩვენება დაწყებული $1-დან',
'rcshowhideminor' => 'მცირე რედაქტირების $1',
'rcshowhidebots'  => 'ბოტების $1',
'rcshowhideliu'   => 'რეგისტრირებული მომხმარებლების $1',
'rcshowhideanons' => 'ანონიმური მომხმარებლების $1',
'rcshowhidemine'  => 'ჩემი რედაქტირების $1',
'rclinks'         => 'ბოლო $1 ცვლილების ჩვენება უკანასკნელი $2 დღის მანძილზე<br />$3',
'diff'            => 'განსხ.',
'hist'            => 'ისტ.',
'hide'            => 'დამალვა',
'show'            => 'ჩვენება',
'minoreditletter' => 'მ',
'newpageletter'   => 'ა',
'boteditletter'   => 'ბ',
'sectionlink'     => '→',

# Recent changes linked
'recentchangeslinked' => 'დაკავშირებული ცვლილებები',

# Upload
'upload'    => 'ფაილის დამატება',
'uploadbtn' => 'ფაილის დამატება',

# Image list
'imagelist'                 => 'ფაილების სია',
'imagelisttext'             => "ქვემოთ მოცემულია '''$1''' ფაილის სია დახარისხებული მომხმარებლის $2 მიერ.",
'imagelistforuser'          => 'აქ მხოლოდ ნაჩვენებია მომხმარებლის $1 მიერ ჩატვირთული სურათები.',
'getimagelist'              => 'ფაილთა სიის ჩამოტვირთვა',
'ilsubmit'                  => 'ძიება',
'byname'                    => 'სახელით',
'bydate'                    => 'თარიღით',
'bysize'                    => 'ზომით',
'imgdelete'                 => 'წაშ.',
'imgdesc'                   => 'აღწ.',
'imgfile'                   => 'ფაილი',
'imghistory'                => 'ფაილის ისტორია',
'deleteimg'                 => 'წაშ.',
'imagelinks'                => 'ბმულები',
'linkstoimage'              => 'ამ ფაილზე ბმული მოცემულია შემდეგ გვერდებზე:',
'nolinkstoimage'            => 'არ არსებობს ამ ფაილთან დაკავშირებული გვერდები.',
'shareduploadwiki'          => 'გთხოვთ, იხილოთ $1 შემდგომი ინფორმაციის მისაღებად.',
'noimage'                   => 'ამ სახელის მქონე ფაილი არ არსებობს, თქვენ შეგიძლიათ $1.',
'noimage-linktext'          => 'ფაილის ატვირთვა',
'uploadnewversion-linktext' => 'ამ ფაილის ახალი ვერსიის ატვირთვა',
'imagelist_date'            => 'თარიღი',
'imagelist_name'            => 'სახელი',
'imagelist_user'            => 'მომხმარებელი',
'imagelist_size'            => 'ზომა (ბაიტები)',
'imagelist_description'     => 'აღწერილობა',
'imagelist_search_for'      => 'ძიება სურათის სახელის მიხედვით:',

# Random redirect
'randomredirect' => 'ნებისმიერი გადამისამართება',

# Statistics
'statistics' => 'სტატისტიკა',

# Miscellaneous special pages
'nbytes'                  => '$1 ბაიტი',
'ncategories'             => '$1 კატეგორია',
'nlinks'                  => '$1 ბმული',
'nmembers'                => '$1 წევრი',
'uncategorizedpages'      => 'გვერდები კატეგორიის გარეშე',
'uncategorizedcategories' => 'კატეგორიები კატეგორიის გარეშე',
'uncategorizedimages'     => 'სურათები კატეგორიის გარეშე',
'unusedcategories'        => 'გამოუყენებელი კატეგორიები',
'unusedimages'            => 'გამოუყენებელი სურათები',
'popularpages'            => 'პოპულარული გვერდები',
'wantedcategories'        => 'მოთხოვნილი კატეგორიები',
'wantedpages'             => 'მოთხოვნილი გვერდები',
'mostcategories'          => 'ყველაზე მეტი კატეგორიის მქონე სტატიები',
'mostrevisions'           => 'ყველაზე მეტად რედაქტირებული სტატიები',
'allpages'                => 'ყველა გვერდი',
'randompage'              => 'ნებისმიერი გვერდი',
'shortpages'              => 'მოკლე გვერდები',
'longpages'               => 'გრძელი გვერდები',
'deadendpages'            => 'ჩიხის გვერდები',
'listusers'               => 'მომხმარებლების სია',
'specialpages'            => 'სპეციალური გვერდები',
'spheading'               => 'სპეციალური გვერდები ყველა მომხმარებლისათვის',
'restrictedpheading'      => 'შეზღუდული სპეციალური გვერდები',
'newpages'                => 'ახალი გვერდები',
'newpages-username'       => 'მომხმარებლის სახელი:',
'ancientpages'            => 'ხანდაზმული გვერდები',
'intl'                    => 'ენათშორისი ბმულები',
'move'                    => 'გადატანა',
'movethispage'            => 'ამ გვერდის გადატანა',
'unusedimagestext'        => '<p>გთხოვთ გაითვალისწინოთ, რომ შეიძლება სხვა ვიკი ზოგიერთ ამ გამოსახულებას იყენებს.</p>',

# Special:Log
'specialloguserlabel'  => 'მომხმარებელი:',
'speciallogtitlelabel' => 'სათაური:',

# Special:Allpages
'nextpage'          => 'შემდეგი გვერდი ($1)',
'prevpage'          => 'წინა გვერდი ($1)',
'allpagesfrom'      => 'გვერდების ჩვენება დაწყებული:',
'allarticles'       => 'ყველა სტატია',
'allinnamespace'    => 'ყველა გვერდი ($1 სახელთა სივრცეში)',
'allnotinnamespace' => 'ყველა გვერდი ($1 სახელთა სივრცის გარეშე)',
'allpagesprev'      => 'წინა',
'allpagesnext'      => 'შემდეგი',
'allpagessubmit'    => 'ჩვენება',
'allpagesprefix'    => 'ასახე გვერდები პრეფიქსით:',
'allpagesbadtitle'  => 'მოცემული გვერდის სათაური არასწორია ან აქვს ინტერვიკი ან ნათშორისი პრეფიქსი. 
იგი შესაძლოა შეიცავდეს ერთ ან მეტ სიმბოლოს, რომელიც არ შეიძლება გამოყენებულ იქნას სათაურში.',

# Watchlist
'watchlist'   => 'ჩემი კონტროლის სია',
'mywatchlist' => 'ჩემი კონტროლის სია',
'watch'       => 'კონტროლი',

# Restrictions (nouns)
'restriction-edit' => 'რედაქტირება',

# Undelete
'undelete'                 => 'აჩვენე წაშლილი გვერდები',
'undeletepage'             => 'იხილეთ და აღადგინეთ წაშლილი გვერდები',
'viewdeletedpage'          => 'იხილეთ წაშლილი გვერდები',
'undeletepagetext'         => 'მომდევნო გვრდები წაშლილია, მაგრამ ჯერ კიდევ არქივშია და 
შესაძლებელია აღდგენა. არქივი შესაძლებელია პერიოდულად გასუფთავდეს.',
'undeleteextrahelp'        => "ამ მთლიანი გვერდის აღსადგენად, დატოვეთ ყველა მოსანიშნი უჯრა მოუნიშნავად და 
დააწკაპუნეთ '''''აღდგენა'''''. იმისათვის, რომ მოახდინოთ შერჩევითი აღდგენა მონიშნეთ უჯრები ჩასატარებელი 
ვერსიების შესაბამისად და დააწკაპუნეთ '''''აღდგენა'''''. '''''გადატვირთვაზე''''' დაწკაპუნებით გაუქმდება ყველა 
კომენტარის ველი და ყველა მოსანიშნი უჯრა.",
'undeleterevisions'        => '$1 ვერსიები დაარქივებულია',
'undeletehistory'          => 'თუ თქვენ აღადგენთ გვერდს, ყველა ვერსია აღდგება ისტორიაში. 
თუ ახალი გვერდი იგივე სახელით შეიქმნა მისი წაშლის შემდეგ, აღდგენილი 
ვერსიები გამოჩნდება წინა ისტორიაში და მიმდინარე ვერსია 
ავტომატურად არ ჩანაცვლდება.',
'undeletehistorynoadmin'   => 'ეს სტატია წაშლილია. წაშლის მიზეზი ნაჩვენებია მოკლე ანოტაციაში ქვემოთ, იმ 
მომხმარებელთა დეტალებთან ერთად ვინც რედაქტირება გაუკეთა ამ გვერდს წაშლის წინ. 
იმ წაშლილი ტექსტების აქტუალური ვერსიები მიღწევადია მხოლოდ ადმინისტრატორებისათვის.',
'undeletebtn'              => 'აღდგენა',
'undeletereset'            => 'გადატვირთეთ',
'undeletecomment'          => 'კომენტარი:',
'undeletedarticle'         => 'აღდგენილია "[[$1]]"',
'undeletedrevisions'       => '$1 ვერსია აღდგენილია',
'undeletedrevisions-files' => '$1 ვერსია და $2 ფაილი აღდგენილია',
'undeletedfiles'           => '$1 ფაილი აღდგენილია',
'cannotundelete'           => 'აღდგენა ვერ შედგა; შესაძლოა უკვე ვიღაცამ აღადგინა ეს გვერდი.',
'undeletedpage'            => "<big>'''$1 აღდგენილია'''</big>

უკანასკნელი წაშლილთა და აღდგენის სია შეგიძლიათ ნახოთ [[Special:Log/delete|წაშლილთა სიაში]].",
'undelete-search-submit'   => 'ძიება',

# Namespace form on various pages
'namespace' => 'სახელთა სივრცე:',
'invert'    => 'ყველა, მონიშნულის გარდა',

# Contributions
'contributions' => 'მომხმარებლის წვლილი',
'mycontris'     => 'ჩემი წვლილი',

# What links here
'whatlinkshere' => 'სადაა მითითებული ეს გვერდი',
'notargettitle' => 'სამიზნე არაა',
'notargettext'  => 'თქვენ არ მიუთითეთ სამიზნე გვერდი ან მომხმარებელი 
ამ ფუნქციის შესასრულებლად.',
'nolinkshere'   => "'''[[:$1]]'''-ზე ბმული არ არის.",

# Move page
'movepage'                => 'გვერდის გადატანა',
'movearticle'             => 'გვერდის გადატანა',
'movenologin'             => 'რეგისტრაცია ვერ გაიარა',
'newtitle'                => 'ახალი სათაური',
'move-watch'              => 'ამ გვერდის კონტროლი',
'movepagebtn'             => 'გვერდის გადატანა',
'pagemovedtext'           => 'გვერდი "[[$1]]" გადავიდა "[[$2]]".',
'articleexists'           => 'ამ დასახელების გვერდი უკვე არსებობს, 
ან თქვენს მიერ მითითებული დასახელება არასწორია. 
თუ შეიძლება, მიუთითეთ სხვა სახელი.',
'movedto'                 => 'გადატანილია',
'movetalk'                => 'დაკავშირებული განხილვის გადატანა',
'1movedto2'               => '[[$1]] გადატანილია [[$2]]-ზე',
'1movedto2_redir'         => '[[$1]] გადატანილია [[$2]]-ზე გადამისამართებულ გვერდში',
'movelogpage'             => 'გადატანის ჟურნალი',
'movereason'              => 'მიზეზი',
'delete_and_move'         => 'წაშლა და გადატანა',
'delete_and_move_text'    => '==საჭიროა წაშლა==

სტატია დასახელებით "[[$1]]" უკვე არსებობს. გსურთ მისი წაშლა გადატანისთვის ადგილის დასათმობად?',
'delete_and_move_confirm' => 'დიახ, წაშალეთ ეს გვერდი',
'delete_and_move_reason'  => 'წაშლილია გადატანისთვის ადგილის დასათმობად',

# Namespace 8 related
'allmessages'               => 'სისტემური შეტყობინება',
'allmessagesname'           => 'დასახელება',
'allmessagesdefault'        => 'სტანდარტული ტექსტი',
'allmessagescurrent'        => 'მიმდინარე ტექსტი',
'allmessagestext'           => 'ეს არის სახელთა სივრცე მედიავიკიში არსებული სისტემური შეტყობინებების ჩამონათვალი.',
'allmessagesnotsupportedUI' => 'თქვენს ამჟამინდელ ინტერფეისის ენას <b>$1</b> არ აქვს სპეციალური:AllMessages-ის უზრუნველყოფა ამ საიტზე.',
'allmessagesnotsupportedDB' => 'სპეციალური:AllMessages-ის უზრუნველყოფა არ ხდება, ვინაიდან wgUseDatabaseMessages გამორთულია.',
'allmessagesfilter'         => 'ფილტრი შეტყობინების სახელის მიხედვით:',
'allmessagesmodified'       => 'აჩვენე მხოლოდ შეცვლილი',

# Thumbnails
'thumbnail-more'  => 'გაზარდეთ',
'filemissing'     => 'ფაილი ვერ მოიძებნა',
'thumbnail_error' => 'ესკიზის შექმნის შეცდომა: $1',

# Attribution
'anonymous' => '{{SITENAME}}-ის ანონიმური მომხმარებლები',
'siteuser'  => '{{SITENAME}} მომხმარებელი $1',
'and'       => 'და',
'others'    => 'სხვები',
'siteusers' => '{{SITENAME}} მომხმარებლები $1',

'passwordtooshort' => 'თქვენი პაროლი ძალიან მოკლეა. მასში უნდა შედიოდეს არანაკლებ $1 ასო-ნიშანი.',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'ყველა',
'imagelistall'     => 'ყველა',
'watchlistall1'    => 'ყველა',
'watchlistall2'    => 'ყველა',
'namespacesall'    => 'ყველა',

# Inputbox extension, may be useful in other contexts as well
'tryexact'       => 'სცადეთ ზუსტი ძიება',
'searchfulltext' => 'სრული ტექსტის ძიება',
'createarticle'  => 'სტატიის შექმნა',

# Delete conflict
'deletedwhileediting' => "[[მომხმარებელი:$1|$1]] მომხმარებელმა ([[მომხმარებელი განხილვა:$1|განხილვა]]) წაშალა თქვენი რედაქტირების შემდეგ. მიზეზი:
: ''$2''
გთხოვთ დაადასტუროთ რომ ნამდვილად გსურთ ამ გვერდის თავიდან შექმნა.",

# HTML dump
'redirectingto' => 'გადამისამართდება [[$1]]-ზე...',

# action=purge
'confirm_purge' => 'გსურთ ამ გვერდის ქეშის წაშლა? $1',

'youhavenewmessagesmulti' => 'თქვენ გაქვთ ახალი შეტყობინება $1-ზე',

'articletitles' => "სტატიები დაწყებული ''$1''-ით",
'hideresults'   => 'შედეგების დამალვა',

# DISPLAYTITLE
'displaytitle' => '(ამ გვერდის ბმული როგორც [[$1]])',

'loginlanguagelabel' => 'ენა: $1',

# Table pager
'table_pager_next'         => 'შემდეგი გვერდი',
'table_pager_prev'         => 'წინა გვერდი',
'table_pager_first'        => 'პირველი გვერდი',
'table_pager_last'         => 'ბოლო გვერდი',
'table_pager_limit_submit' => 'აჩვენე',
'table_pager_empty'        => 'შედეგები არაა',

# Auto-summaries
'autosumm-blank'   => 'გვერდი დაცარიელდა',
'autosumm-replace' => "შინაარსი შეიცვალა '$1'-ით",
'autoredircomment' => 'გადამისამართება [[$1]]-ზე', # This should be changed to the new naming convention, but existed beforehand
'autosumm-new'     => 'ახალი გვერდი: $1',

# Size units
'size-bytes'     => '$1 ბ',
'size-kilobytes' => '$1 კბ',
'size-megabytes' => '$1 მბ',
'size-gigabytes' => '$1 გბ',

);

?>
