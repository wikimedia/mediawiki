<?php
/** Mingrelian (მარგალური)
 *
 * @addtogroup Language
 *
 * @author Dato deutschland
 * @author Alsandro
 * @author Malafaya
 * @author Siebrand
 * @author M.M.S.
 * @author Nike
 */

$fallback = 'ka';

$messages = array(
# User preference toggles
'tog-underline'       => 'ხაზ გუსვი ბუნილეფს:',
'tog-highlightbroken' => 'ქააძირი ვაარარსებულ ბუნილეფ <a href="" class="new">მუჭოთ თენა</a> (ალტერნატივა: მუჭოთ თენა<a href="" class="internal">?</a>).',
'tog-justify'         => 'გაასწორი პარაგრაფეფ',
'tog-hideminor'       => 'დოჩული ჭიჭე რედაქტირება ბოლო თირაფეფს',
'tog-showtoolbar'     => 'რედაქტირებაშ ინსტრუმენტეფიშ ძირაფა (ჯავასკრიპტ)',

'underline-always' => 'ირო',
'underline-never'  => 'შურო',

# Dates
'sunday'        => 'ჟაშხა',
'monday'        => 'თუთაშხა',
'tuesday'       => 'თახაშხა',
'wednesday'     => 'ჯუმაშხა',
'thursday'      => 'ცაშხა',
'friday'        => 'ობიშხა',
'saturday'      => 'საბატონი',
'sun'           => 'ჟაშ',
'mon'           => 'თუთ',
'tue'           => 'თახ',
'wed'           => 'ჯუმ',
'thu'           => 'ცაა',
'fri'           => 'ობი',
'sat'           => 'საბ',
'january'       => 'იანარი',
'february'      => 'ფრევალი',
'march'         => 'მარტი',
'april'         => 'აპრილი',
'may_long'      => 'მეესი',
'june'          => 'ივანობა',
'july'          => 'კვირკვე',
'august'        => 'არგუსო',
'september'     => 'ეკენია',
'october'       => 'გიმათუთა',
'november'      => 'გერგებათუთა',
'december'      => 'ქირსეთუთა',
'january-gen'   => 'იანარი',
'february-gen'  => 'ფრევალი',
'march-gen'     => 'მარტიშ',
'april-gen'     => 'აპრილი',
'may-gen'       => 'მეესიშ',
'june-gen'      => 'ივანობაშ',
'july-gen'      => 'კვირკვეშ',
'august-gen'    => 'არგუსო',
'september-gen' => 'ეკენიაშ',
'october-gen'   => 'გიმათუთაშ',
'november-gen'  => 'გერგებათუთაშ',
'december-gen'  => 'ქირსეთუთაშ',
'jan'           => 'იან',
'feb'           => 'ფრე',
'mar'           => 'მარ',
'apr'           => 'აპრ',
'may'           => 'მეე',
'jun'           => 'იავ',
'jul'           => 'კვი',
'aug'           => 'არგ',
'sep'           => 'ეკე',
'oct'           => 'გიმ',
'nov'           => 'გერ',
'dec'           => 'ქირ',

# Categories related messages
'categories'             => '{{PLURAL:$1|კატეგორია|კატეგორიეფ}}',
'pagecategories'         => '{{PLURAL:$1|კატეგორია|კატეგორიეფ}}',
'category_header'        => 'სტატიეფ კატეგორიას "$1"',
'subcategories'          => 'ქვეკატეგორიეფ',
'category-media-header'  => 'მედია კატეგორიას "$1"',
'category-empty'         => "''თენა კატეგორიას ვარენა გვერდეფ დო მედია''",
'listingcontinuesabbrev' => 'გინძარ.',

'about'          => '-შენი',
'article'        => 'სტატია',
'newwindow'      => '(ახალ აკოშკას)',
'cancel'         => 'გაუქვება',
'qbfind'         => 'მიგორე',
'qbedit'         => 'რედაქტირება',
'qbpageoptions'  => 'თენა ხასილა',
'qbpageinfo'     => 'კონტექსტ',
'qbmyoptions'    => 'ჩქიმ ხასილეფ',
'qbspecialpages' => 'სპეციალურ ხასილეფ',
'moredotdotdot'  => 'სრულო...',
'mypage'         => 'ჩქიმ ხასილა',
'mytalk'         => 'ჩქიმ განხილვა',
'navigation'     => 'ნავიგაცია',
'and'            => 'დო',

'errorpagetitle'   => 'შეცთომა',
'tagline'          => '{{SITENAME}} გვერდიშე',
'help'             => 'დახვარება',
'search'           => 'გორუა',
'searchbutton'     => 'გორუა',
'go'               => 'სტატია',
'searcharticle'    => 'სტატია',
'history'          => 'ხასილაშ ისტორია',
'history_short'    => 'ისტორია',
'info_short'       => 'ინფორმაცია',
'printableversion' => 'ობეშტალ ვერსია',
'permalink'        => 'ირალ ბუნი',
'print'            => 'დობეჭდი',
'edit'             => 'რედაქტირება',
'editthispage'     => 'ხასილაშ რედაქტირება',
'delete'           => 'წაშალი',
'deletethispage'   => 'წაშალ თე ხასილა',
'newpage'          => 'ახალ ხასილა',
'talkpagelinktext' => 'დისკუსია',
'specialpage'      => 'სპეციალურ ხასილა',
'personaltools'    => 'საკუთარ მოღეეფ',
'talk'             => 'დისკუსია',
'views'            => 'ძირაფა',
'toolbox'          => 'ინსტრუმენტეფი',
'otherlanguages'   => 'შხვა ნინალეფს',
'redirectedfrom'   => '(გინოღალულ რე $1-იშე)',
'redirectpagesub'  => 'გადამისამართ გვერდი',
'jumpto'           => 'გეგნორთ:',
'jumptonavigation' => 'ნავიგაცია',
'jumptosearch'     => 'გორუა',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => '{{SITENAME}}-შენი',
'aboutpage'            => 'Project:შენი',
'copyrightpage'        => '{{ns:project}}:ავტორიშ ულაფეფ',
'disclaimers'          => 'პასუხისმგებლობაშ მონწყუმა',
'disclaimerpage'       => 'Project::პასუხისმგებლობაშ ვარება',
'edithelp'             => 'დახვარება',
'edithelppage'         => 'Help:ტექსტიშ რედაქტირება (იოლი)',
'helppage'             => 'Help:დახვარება',
'mainpage'             => 'თავარ ხასილა',
'mainpage-description' => 'თავარ ხასილა',
'portal'               => 'საზოგადოებაშ ხასილეფ',
'portal-url'           => 'Project:საზოგადოებაშ ხასილეფ',
'privacy'              => 'ანონიმურობაშ პოლიტიკა',
'privacypage'          => 'Project:ანონიმურობაშ პოლიტიკა',

'ok'                 => 'ჯგირ',
'retrievedfrom'      => 'გორილ რე "$1"-იშე',
'youhavenewmessages' => 'თქვა გიღუნა $1 ($2).',
'newmessageslink'    => 'ახალ შეტყვინაფეფ',
'editsection'        => 'რედაქტირება',
'editold'            => 'რედაქტირება',
'editsectionhint'    => 'სექციაშ რედაქტირება: $1',
'toc'                => 'ოსხუნალ',
'showtoc'            => 'ძირაფა',
'hidetoc'            => 'ფულუა',
'site-rss-feed'      => '$1 RSS Feed',
'site-atom-feed'     => '$1-იშ არხი Atom',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'     => 'სტატია',
'nstab-user'     => 'მომხმარებელიშ ხასილა',
'nstab-media'    => 'მედიაშ ხასილა',
'nstab-special'  => 'სპეციალურ',
'nstab-project'  => 'პროექტიშ ხასილა',
'nstab-image'    => 'ფაილი',
'nstab-template' => 'თანგი',
'nstab-category' => 'კატეგორია',

# Main script and global functions
'nosuchspecialpage' => 'სპეციალურ ხასილეფ ვაარსეენც',

# General errors
'badtitle'      => 'ცაგანა სათაური',
'viewsourcefor' => '$1-იშ',

# Login and logout pages
'yourname'                => 'მომხმარებელ',
'yourpassword'            => 'პაროლ',
'yourdomainname'          => 'თქვან დომენ',
'login'                   => 'მინულა',
'nav-login-createaccount' => 'მინულა',
'userlogin'               => 'მინულა',
'logout'                  => 'გუმულა',
'userlogout'              => 'გუმულა',
'nologin'                 => 'დიორდე ვარეთ რეგისტრირებულ? $1.',
'gotaccount'              => 'უკვე რეგისტრირებულ რეთ? $1',
'gotaccountlink'          => 'მინულა',
'yourrealname'            => 'ნამდვილ სახელ *',
'yourlanguage'            => 'ნინა:',
'loginsuccess'            => "'''ასე მიშულირ რეთ {{SITENAME}}-ს მუჭოთ \"\$1\".'''",
'nouserspecified'         => 'საჭირო რე მომხმარებელიშ სახელიშ მიშაჭარუა.',
'mailmypassword'          => 'ახალ პაროლიშ მოჯღონა',
'loginlanguagelabel'      => 'ნინა: $1',

# Edit page toolbar
'italic_sample'   => 'კურსივ',
'italic_tip'      => 'კურსივ',
'link_sample'     => 'ბუნილიშ სახელ',
'link_tip'        => 'დინახალენ ბუნილ',
'extlink_sample'  => 'http://www.example.com ბუნილიშ სათაურ',
'extlink_tip'     => 'გალე ბუნილ (ქორშუდათი http:// პრეფიქს)',
'headline_sample' => 'სათაურიშ ტექსტ',
'headline_tip'    => 'ქვესათაურ',
'math_tip'        => 'მათემატიკურ ფორმულა (LaTeX)',
'nowiki_tip'      => 'ვიკიშ ფორმატირებიშ იგნორირება',
'media_tip'       => 'ბუნილ ფაილს',
'sig_tip'         => 'თქვან ხემოჭარა დო დრო',
'hr_tip'          => 'ჰორიზონტალურ ხაზ (ნუ გამოიყენებთ ხშირას)',

# Edit pages
'summary'          => 'რეზიუმე',
'subject'          => 'თემა/სახელ',
'minoredit'        => 'ჭიჭე რედაქტირება',
'watchthis'        => 'თე გვერდიშ კონტროლ',
'savearticle'      => 'ჩუალა',
'showpreview'      => 'ოწმახ ძირა',
'showdiff'         => 'თირაფეფიშ ძირაფა',
'newarticle'       => '(ახალ)',
'noarticletext'    => 'ასე თე ხასილას ტექსტ ვარე, [[Special:Search/{{PAGENAME}}|მიგორეთ თე ხასილაშ სახელ]] შხვა გვერდეფს ვარა [{{fullurl:{{FULLPAGENAME}}|action=edit}} დოჭარით თენა ხასილა].',
'editing'          => 'რედაქტირება - $1',
'editingsection'   => 'რედაქტირება - $1 (სექცია)',
'yourtext'         => 'თქვან ტექსტ',
'copyrightwarning' => 'თოლჸუჯი ქიმეჩით: ნამდგა ვა რდას თიამიშნაღელი ხასილას {{SITENAME}} $2 ლიცენზიას ათოჸუნს(ძირით $1 დეტალეფშოთ). ვა გოკონა თქვან ნახანდიშ დუდშულო გოფაჩუა დო თიშ უდუნდებელ რედაქტირაფა და, თიწკუმა ვა მიშეჸონათ თინა თაქ.<br />
თქვა ხოლო პიჯალას დუთმოდვანთ, ნამდა თენა თქვან ნაჭარა რე, ვარა გინოღალირ რე ოირკოჩე დომენშე, დო ვარა თიშ მანგურ დუდშულ წყუშე. 
<strong> ვა მიშეღათ ოავტორე უფლებებით თხილერ ნახანდი ავტორიშ ქოჸიაშ უმშო!</strong>',

# History pages
'viewpagelogs'     => 'თე ხასილაშა სარეგისტრაციე ჟურნალეფიშ ძირაფა',
'revisionasof'     => '$1-იშ ვერსია',
'previousrevision' => '←ჯვეშ ვერსია',
'nextrevision'     => 'უკულ ვერსია→',
'cur'              => 'მიმალ',
'last'             => 'ბოლო',
'page_first'       => 'პირველი',
'page_last'        => 'ბოლო',
'histfirst'        => 'პირველი',
'histlast'         => 'ბოლო',
'historysize'      => '($1 ბაიტ)',
'historyempty'     => '(ცალიერ)',

# Revision feed
'history-feed-title'          => 'რედაქტირებიშ ისტორია',
'history-feed-description'    => 'თენა გვერდიშ რედაქტირებეფიშ ისტორია ვიკის',
'history-feed-item-nocomment' => '$1  $2-ს', # user at time

# Diffs
'history-title'           => '"$1" თირუეფიშ ისტორია',
'difference'              => '(ვერსიეფიშ დარება)',
'lineno'                  => 'ხაზი $1:',
'compareselectedversions' => 'გიშაგორილ ვერსიეფიშ დარება',
'editundo'                => 'გაუქვება',

# Search results
'noexactmatch' => "'''გვერდ თე სახელით \"\$1\" ვა არსეენც.''' თქვა შეგილებნა [[:\$1|თე გვერდიშ კეთება]].",
'nextn'        => 'უკულ $1',
'viewprevnext' => 'ქოძირ  ($1) ($2) ($3).',
'powersearch'  => 'გორუა',

# Preferences page
'preferences'   => 'კონფიგურაცია',
'mypreferences' => 'ჩქიმ კონფიგურაცია',

'grouppage-sysop' => '{{ns:project}}:ადმინისტრატორეფ',

# User rights log
'rightslog' => 'ხვარებელიშ უფლებეფიშ ჟურნალ',

# Recent changes
'nchanges'        => '$1 თირუა',
'recentchanges'   => 'ბოლო თირუეფ',
'rcnote'          => 'თუდო ქოძირ ბოლო <strong>$1</strong> თირუა უკანასკნელი <strong>$2</strong> დღაშ $3 დგომარებათ.',
'rclistfrom'      => 'ახალ თირუეფიშ ძირაფა დოჭყაფულ $1-იშე',
'rcshowhideminor' => 'ჭიჭე რედაქტირებაშ $1',
'rcshowhidebots'  => 'რობოტეფიშ  $1',
'rcshowhideliu'   => 'რეგისტრირებულ მომხმარებელეფიშ $1',
'rcshowhideanons' => 'ანონიმურ მომხმარებელეფიშ $1',
'rcshowhidemine'  => 'ჩქიმ რედაქტირებაშ $1',
'rclinks'         => 'ბოლო $1 თირუეფიშ ძირაფა უკანასკნელი $2 დღა გარგვალებურს<br />$3',
'diff'            => 'შხვანერობა',
'hist'            => 'ისტ.',
'hide'            => 'ფულუა',
'show'            => 'ძირაფა',
'minoreditletter' => 'ჭ',
'newpageletter'   => 'ა',
'boteditletter'   => 'რ',

# Recent changes linked
'recentchangeslinked' => 'აკოხვალამირი თირაფეფი',

# Upload
'upload'        => 'ფაილიშ გეთება',
'uploadbtn'     => 'ფაილიშ გეთება',
'uploadedimage' => 'გეთებაა "[[$1]]"',

# Special:Imagelist
'imagelist_name' => 'სახელ',

# Image description page
'filehist'            => 'ფაილიშ ისტორია',
'filehist-user'       => 'მომხმარებელ',
'filehist-dimensions' => 'განზომილებეფ',
'filehist-filesize'   => 'ფაილიშ ზომა',
'filehist-comment'    => 'კომენტარ',
'imagelinks'          => 'ბუნილეფ',
'noimage'             => 'ფაილ თე სახელით ვაარსეენც, თქვან შეგილებუნთ $1.',

# MIME search
'mimesearch' => 'MIME გორუა',

# Random page
'randompage' => 'ნამდგარენ გვერდ',

# Statistics
'statistics' => 'სტატისტიკა',

'withoutinterwiki' => 'გვერდეფ ნინაშ ბუნეფიშ გარეშე',

# Miscellaneous special pages
'nbytes'                  => '$1 ბაიტ',
'nlinks'                  => '$1 ბუნილ',
'nmembers'                => '$1 მაკათურ',
'uncategorizedpages'      => 'უკატეგორიე გვერდეფ',
'uncategorizedcategories' => 'კატეგორიეფ კატეგორიეფიშ გარეშე',
'uncategorizedimages'     => 'სურათეფ კატეგორიაშ უმიშო',
'mostlinked'              => 'გვერდეფ, ნამუდგა არძას ბრალ ბუნილეფ უღუნა',
'mostlinkedcategories'    => 'კატეგორიეფ, ნამუდგა არძას ბრალ ბუნილეფ უღუნა',
'mostcategories'          => 'სტატიეფ, ნამუდგა არძას ბრალ კატეგორიეფ უღუნა',
'shortpages'              => 'ჭიჭე გვერდეფ',
'longpages'               => 'გინძე გვერდეფ',
'specialpages'            => 'სპეციალურ გვერდეფ',
'newpages'                => 'ახალ გვერდეფ',
'ancientpages'            => 'ჯვეშ გვერდეფ',
'move'                    => 'გინოღალა',
'movethispage'            => 'თე გვერდიშ გინოღალა',

# Special:Log
'specialloguserlabel'  => 'მომხმარებელ:',
'speciallogtitlelabel' => 'სათაურ:',
'log'                  => 'ჟურნალეფ',
'all-logs-page'        => 'ირ ჟურნალ',

# Special:Allpages
'allpages'       => 'არძა გვერდ',
'alphaindexline' => '$1-იშე $2-შა',
'nextpage'       => 'უკულ გვერდ ($1)',
'prevpage'       => 'წოხლენ გვერდ ($1)',
'allpagesfrom'   => 'გვერდეფიშ ძირაფა დოჭყაფულ:',
'allarticles'    => 'ირ სტატია',
'allpagessubmit' => 'ძირა',

# Watchlist
'watchlist'            => 'ჩქიმ კონტროლიშ გვერდეფ',
'mywatchlist'          => 'ჩქიმ კონტროლიშ გვერდეფ',
'watchlistfor'         => "('''$1'''-შენი)",
'removedwatchtext'     => 'ასე გვერდ "[[:$1]]" ვარე თქვან კონტროლიშ გვერდეფც.',
'watch'                => 'კონტროლ',
'watchthispage'        => 'თე გვერდიშ კონტროლ',
'unwatch'              => 'კონტროლიშ გაუქვება',
'wlshowlast'           => 'ძირაფა ბოლო $1 საათიშ $2 დღაშ $3',
'watchlist-hide-bots'  => 'რობოტიშ თირუეფიშ ფულუა',
'watchlist-hide-own'   => 'ჩქიმ რედაქტირებაშ ფულუა',
'watchlist-hide-minor' => 'ჭიჭე რედაქტირებებაშ ფულუა',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'კონტროლირაფონი...',
'unwatching' => 'ვაკონტროლირაფონი...',

# Delete/protect/revert
'deletepage'                  => 'გვერდიშ შალუა',
'actioncomplete'              => 'მოქმედალა რსულებულ რე',
'deleteotherreason'           => 'შხვა/დამატებით ბაძაძი:',
'deletereasonotherlist'       => 'შხვა ბაძაძი',
'rollbacklink'                => 'გაუქვება',
'protectcomment'              => 'კომენტარ:',
'protectexpiry'               => 'ვადა',
'protect-default'             => '(სტანდარტულ)',
'protect-level-autoconfirmed' => 'ვარეგისტრირებულ მომხმარებელეფიშ დაბლოკვა',
'protect-level-sysop'         => 'ხოლო ადმინისტრატორეფ',
'restriction-type'            => 'ულაფა',

# Undelete
'undelete-search-submit' => 'გორუა',

# Namespace form on various pages
'invert'         => 'არძა, გიშაგორილი გარდა',
'blanknamespace' => '(თავარ)',

# Contributions
'contributions' => 'მომხმარებელიშ ხანდა',
'mycontris'     => 'ჩქიმ ხანდა',
'contribsub2'   => '$1 ($2) შენი',
'uctop'         => '(დუდ)',
'month'         => 'თუთა:',
'year'          => 'წანა:',

'sp-contributions-blocklog' => 'ბლოკირებაშ ისტორია',

# What links here
'whatlinkshere'       => 'სო რე თენა გვერდ წურაფილი',
'whatlinkshere-page'  => 'გვერდ:',
'linklistsub'         => '(ბუნილეფ)',
'nolinkshere'         => 'თე გვერდც ვარე ბუნილ',
'whatlinkshere-next'  => '{{PLURAL:$1|უკულიან|უკულიან $1}}',
'whatlinkshere-links' => '← ბუნილეფ',

# Block/unblock
'blockip'            => 'ხვარებელიშ ვარა IP მისამართიშ ბლოკირება',
'ipboptions'         => '2 საათი:2 hours,1 დღა:1 დღა,3 დღა:3 დღალეფ,1 მარა:1 week,2 მარა:2 weeks,1 თუთა:1 month,3 თუთა:3 months,6 თუთა:6 months,1 წანა:1 year,განუსაზღვრელი ვადით:infinite', # display1:time1,display2:time2,...
'ipbotheroption'     => 'შხვა',
'ipblocklist-submit' => 'გორუა',
'blocklink'          => 'ბლოკირება',
'unblocklink'        => 'ბლოკიშ მონწყუმა',
'contribslink'       => 'ხანდა',

# Move page
'move-page-legend' => 'გვერდიშ გინოღალა',
'movearticle'      => 'გვერდიშ გინოღალა',
'newtitle'         => 'ახალ სათაურ',
'move-watch'       => 'თე გვერდიშ კონტროლ',
'movepagebtn'      => 'გვერდიშ გინოღალა',
'pagemovedsub'     => 'გინოღალა რსულებულ რე',
'movepage-moved'   => '<big>\'\'\'"$1" გინოღალულ რე "$2"\'\'\'</big>', # The two titles are passed in plain text as $3 and $4 to allow additional goodies in the message.
'movedto'          => 'გინაღალულ რე',
'movelogpage'      => 'გინოღალაშ რეგისტრაცია',
'movereason'       => 'საბაბი',
'revertmove'       => 'გაუქვება',

# Export
'export' => 'გვერდეფიშ ექსპორტ',

# Namespace 8 related
'allmessages' => 'ირ სისტემურ შეტყვინაფა',

# Thumbnails
'thumbnail-more' => 'მორდი',

# Import log
'importlogpage' => 'იმპორტიშ ჟურნალ',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'ჩქიმ გვერდ',
'tooltip-pt-mytalk'               => 'ჩქიმ დისკუსიაშ გვერდ',
'tooltip-pt-preferences'          => 'ჩქიმ კონფიგურაცია',
'tooltip-pt-watchlist'            => 'ხასილეფიშ ერკებული, ნამუშ თირაფას თქვა ითოლორუანთ',
'tooltip-pt-mycontris'            => 'ჩქიმ არძა ხანდა',
'tooltip-pt-login'                => 'ჯგირი იჸუაფუ თქვან რეგისტრაცია, მორო აუცილებელი ვარე.',
'tooltip-pt-logout'               => 'გუმულა',
'tooltip-ca-talk'                 => 'შინაარსიშ გვერდიშ განხილვა',
'tooltip-ca-edit'                 => 'თქვა შეგილებნა თე გვერდიშ რედაქტირება. რთხიინთ გეუნჭირით ოწმახ რწყებაშ ღილაკიშ გვერდიშ შენახაშა.',
'tooltip-ca-delete'               => 'თე გვერდიშ შალუა',
'tooltip-ca-move'                 => 'თე გვერდიშ გინოღალა',
'tooltip-ca-unwatch'              => 'მონწყუმეთ თენა გვერდ თქვან კონტროლიშ გვერდეფიშე',
'tooltip-search'                  => 'გორუა {{SITENAME}}',
'tooltip-n-mainpage'              => 'თავარ გვერდიშ ძირაფა',
'tooltip-n-portal'                => 'პროექტიშენი, მუშ ქიმინუა შეგილებჷნა, სოდეთ ძირჷნთ',
'tooltip-n-currentevents'         => 'ქიჩინით რსული ინფორმაცია ასეიან მოვლენეფიშენი',
'tooltip-n-recentchanges'         => 'ვიკიშ ბოლო თირაფეფ',
'tooltip-n-randompage'            => 'ქუმოძირ ნებისმიერ გვერდ',
'tooltip-n-help'                  => 'გვერდ, სოდეთ ძირჷნთ.',
'tooltip-n-sitesupport'           => 'ხუჯ დომკინით',
'tooltip-t-whatlinkshere'         => 'არძო ვიკი გვერდიშ სია ნამუდგა მიკოკირილ რე თაქ.',
'tooltip-t-contributions'         => 'ქოძირეთ თე მომხმარებელიშ ხანდა',
'tooltip-t-upload'                => 'ქიმკაკირ ფაილ',
'tooltip-t-specialpages'          => 'ირ სპეციალურ გვერდ',
'tooltip-ca-nstab-user'           => 'მომხმარებელიშ გვერდიშ ძირაფა',
'tooltip-ca-nstab-project'        => 'პროექტიშ გვერდ',
'tooltip-ca-nstab-image'          => 'სურათიშ გვერდიშ ძირაფა',
'tooltip-ca-nstab-help'           => 'ქოძირეთ დახვარებაშ გვერდ',
'tooltip-ca-nstab-category'       => 'გვერდიშ კატეგორიაშ ძირა',
'tooltip-save'                    => 'თირუაშ ჩუალა',
'tooltip-preview'                 => 'ოწმახ გეგნაჯინ თირუეფის, რთხიინთ, იხვარით თენა ჩუალაშახ! [alt-p]',
'tooltip-diff'                    => 'ტექსტს არსებულ თურუეფიშ ძირა. [alt-v]',
'tooltip-compareselectedversions' => 'ქოძირეთ თე გვერდიშ ჟირ გიშაგორილ ვერსიაშ განშხვავებეფ.',

# Browsing diffs
'previousdiff' => '← ჯვეშ თირუაშა',
'nextdiff'     => 'უკულ თირუა →',

# Media information
'file-info-size' => '($1 × $2 პიქსელ, ფაილიშ ზომა: $3, MIME ტიპ: $4)',

# Special:Newimages
'newimages' => 'ახალ სურათეფ',

# Bad image list
'bad_image_list' => 'ფორმატ რე უკულიანიშნერო:

ხვალე გიშნაგორეფს (ბჭკარეფ ნამდგა იჭყაფუ *) მიკიჯინუ.
ბწკარიშ პირლველ ბუნილ ოკო რდას ლინკ გლახა ფალიშა.
ნებისმიერ უკულიან ბუნილეფ კინ თი ბწკარს გენიხილებ მუჭოთ გამონაკლის, მუდგა ნიშნენს ნამუდა გვერდეფ სოდგა ფაილეფ შილებე რდას ინლაინს.',

# Metadata
'metadata'        => 'მეტამონაცემეფ',
'metadata-expand' => 'დეტალეფიშ ძირაფა რსულო',

# External editor support
'edit-externally-help' => 'რსულ ინფორმაციაშენ ქოძირეთ [http://meta.wikimedia.org/wiki/Help:External_editors ჩადგმიშ ინსტრუქციეფ].',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'არძა',
'namespacesall' => 'არძა',
'monthsall'     => 'არძა',

# Watchlist editing tools
'watchlisttools-edit' => 'კონტროლიშ გვერდეფიშ ძირაფა დო რედაქტირება',

# Special:Version
'version' => 'ვერსია', # Not used as normal message but as header for the special page itself

);
