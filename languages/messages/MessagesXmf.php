<?php
/** Mingrelian (მარგალური)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Alsandro
 * @author Andrijko Z.
 * @author Dato deutschland
 * @author Dawid Deutschland
 * @author Kaganer
 * @author Kilavagora
 * @author Lika2672
 * @author Machirkholi
 * @author Malafaya
 * @author Reedy
 * @author გიორგიმელა
 */

$fallback = 'ka';

$messages = array(
# User preference toggles
'tog-underline' => 'რცხუეფიშ ათოღაზუა:',
'tog-justify' => 'გამანგი აბზაცეფი',
'tog-hideminor' => 'დოფული ციქა რედაქტირაფა ეკონია თირაფეფს',
'tog-hidepatrolled' => 'დოფულით პატრულირებულ რედაქტირაფეფი ასერდეიან თირაფეფს',
'tog-newpageshidepatrolled' => 'დოფულით პატრულირებულ ხასჷლეფი ახალ ხასჷლეფიშ ერკებულშე',
'tog-extendwatchlist' => 'გოფაჩი ოთოჸუჯე ერკებული არძო თირაფეფიშ ოძირაფალო, ამარდეიან თირაფეფიშ მეკოროცხილო',
'tog-usenewrc' => 'გეგმირინე ეკონია თირაფეფიშ უჯგუში ერკებული (ითხინს JavaScript-ის)',
'tog-numberheadings' => 'ავტომატურო დონომერე დუდჯოხოეფი',
'tog-showtoolbar' => 'რედაქტირაფაშ ხეჭკუდეფიშ ძირაფა (ითხინს JavaScript-ის)',
'tog-editondblclick' => 'ხასჷლეფიშ რედაქტირაფა ჟირმანგი გეწკანტაფათ (ითხინს JavaScript-ის)',
'tog-editsection' => 'ჩართი სექციაშ რედაქტირაფა [რედაქტირაფაშ] რცხუეფით',
'tog-editsectiononrightclick' => 'ჩართი სექციაშ რედაქტირაფა სექციაშ ჯოხოშა მარძგვან გეწკანტაფათ (ითხინს JavaScript-ის)"',
'tog-showtoc' => 'ქაძირი გჷშაგორალი (სუმშე უმოს დუდჯოხოამ ხასჷლეფშო)',
'tog-showhiddencats' => 'ქაძირი ფულირი კატეგორიეფი',

'underline-always' => 'ირო',
'underline-never' => 'შურო',

# Font style option in Special:Preferences
'editfont-sansserif' => 'შრიფტი სანს-სერიფი',
'editfont-serif' => 'შრიფტი სერიფი',

# Dates
'sunday' => 'ჟაშხა',
'monday' => 'თუთაშხა',
'tuesday' => 'თახაშხა',
'wednesday' => 'ჯუმაშხა',
'thursday' => 'ცაშხა',
'friday' => 'ობიშხა',
'saturday' => 'შურიშხა',
'sun' => 'ჟაშ.',
'mon' => 'თუთ.',
'tue' => 'თახ.',
'wed' => 'ჯუმ.',
'thu' => 'ცაშ.',
'fri' => 'ობი.',
'sat' => 'შურ.',
'january' => 'ღურთუთა',
'february' => 'ფურთუთა',
'march' => 'მელახი',
'april' => 'პირელი',
'may_long' => 'მესი',
'june' => 'მანგი',
'july' => 'კვირკვე',
'august' => 'მარაშინათუთა',
'september' => 'ეკენია',
'october' => 'გჷმათუთა',
'november' => 'გერგობათუთა',
'december' => 'ქირსეთუთა',
'january-gen' => 'ღურთუთაშ',
'february-gen' => 'ფურთუთაშ',
'march-gen' => 'მელახიშ',
'april-gen' => 'პირელიშ',
'may-gen' => 'მესიშ',
'june-gen' => 'მანგიშ',
'july-gen' => 'კვირკვეშ',
'august-gen' => 'მარაშინათუთაშ',
'september-gen' => 'ეკენიაშ',
'october-gen' => 'გჷმათუთაშ',
'november-gen' => 'გერგობათუთაშ',
'december-gen' => 'ქირსეთუთაშ',
'jan' => 'ღურ.',
'feb' => 'ფურ.',
'mar' => 'მელ.',
'apr' => 'პირ.',
'may' => 'მეს.',
'jun' => 'მან.',
'jul' => 'კვრ.',
'aug' => 'მარ.',
'sep' => 'ეკნ.',
'oct' => 'გჷმ.',
'nov' => 'გერ.',
'dec' => 'ქირ.',

# Categories related messages
'pagecategories' => '{{PLURAL:$1|კატეგორია|კატეგორიეფი}}',
'category_header' => 'სტატიეფი "$1"  კატეგორიას',
'subcategories' => 'გიმენკატეგორიეფი',
'category-media-header' => 'მედია კატეგორიას "$1"',
'category-empty' => "''თე კატეგორიას ასე ვაკათ ხასჷლეფი ვარდა მედია''",
'hidden-categories' => '{{PLURAL:$1|ფულირი კატეგორია|ფულირი კატეგორიეფი}}',
'hidden-category-category' => 'ფულირი კატეგორიეფი',
'category-subcat-count' => '{{PLURAL:$2|თე კატეგორიას ოკათჷ ხვალე ათე გიმენკატეგორია.|თე კატეგორიას მოჩამილი რე $1 გიმენკატეგორია $2-შე.}}',
'category-article-count' => '{{PLURAL:$2|ათე კატეგორია იკათუანს ხვალე გეჸვენჯ ხასილას.|გეჸვენჯ {{PLURAL:$1|ხასილა რე|$1 ხასილეფ რე}} თე კატეგორიას, გვალო $2–შე.}}',
'category-file-count' => '{{PLURAL:$2|თე კატეგორიას ხვალე ათე გეჸვენჯი ფაილი რე.|თე კატეგორიას რე გეჸვენჯი $1, ართოიანო $2-შე.}}',
'listingcontinuesabbrev' => 'გინძარ.',
'noindex-category' => 'ხასჷლეფი ინდექსირაფაშ უმუშო',

'about' => '-შენი',
'article' => 'სტატია',
'newwindow' => 'ინწყუმუ ახალი ოჭკორიეს',
'cancel' => 'გოუქვაფა',
'moredotdotdot' => 'სრულო...',
'mypage' => 'ჩქიმი ხასჷლა',
'mytalk' => 'ჩქიმი სხუნუა',
'navigation' => 'ნავიგაცია',
'and' => '&#32;დო',

# Cologne Blue skin
'qbfind' => 'დოგორი',
'qbedit' => 'რედაქტირება',
'qbpageoptions' => 'თე ხასჷლა',
'qbmyoptions' => 'ჩქიმი ხასჷლეფი',
'qbspecialpages' => 'გჷშაკერძაფილი ხასჷლეფი',
'faq' => 'ბხშირი კითხვეფი',

# Vector skin
'vector-action-addsection' => 'თემაშ მიშაძინა',
'vector-action-delete' => 'ლასუა',
'vector-action-move' => 'გინოღალა',
'vector-action-protect' => 'თხილუა',
'vector-view-create' => 'დორსხუაფა',
'vector-view-edit' => 'რედაქტირაფა',
'vector-view-history' => 'ისტორიაშ ძირაფა',
'vector-view-view' => 'კითხირი',
'vector-view-viewsource' => 'ქიძირე წყუ',
'actions' => 'მოქმედალეფი',
'namespaces' => 'ჯოხოეფიშ ოფირჩა',
'variants' => 'ვარიანტეფი',

'errorpagetitle' => 'ჩილათა',
'returnto' => 'დირთი $1-შა',
'tagline' => '{{SITENAME}} ხასჷლაშე',
'help' => 'მოხვარა',
'search' => 'გორუა',
'searchbutton' => 'გორუა',
'go' => 'სტატია',
'searcharticle' => 'გინულა',
'history' => 'ხასილაშ ისტორია',
'history_short' => 'ისტორია',
'printableversion' => 'ობეშტალი ვერსია',
'permalink' => 'პერმანენტული რცხუ',
'print' => 'დობეშტი',
'edit' => 'რედაქტირაფა',
'create' => 'დორსხუაფა',
'editthispage' => 'ხასჷლაშ რედაქტირაფა',
'delete' => 'ლასუა',
'deletethispage' => 'დოლასი თე ხასჷლა',
'protect' => 'დოთხილე',
'protect_change' => 'თირუა',
'newpage' => 'ახალი ხასჷლა',
'talkpage' => 'მოჩამილი ხასჷლაშ სხუნუა',
'talkpagelinktext' => 'სხუნუა',
'specialpage' => 'გჷშაკერძაფილი ხასჷლა',
'personaltools' => 'პერსონალური ხეჭკუდეფი',
'talk' => 'სხუნუა',
'views' => 'ძირაფეფი',
'toolbox' => 'ინსტრუმენტეფი',
'otherlanguages' => 'შხვა ნინეფს',
'redirectedfrom' => '(გინოწურაფილი რე $1-შე)',
'redirectpagesub' => 'ხასჷლაშა გინოწურაფა',
'lastmodifiedat' => 'თე ხასილაქ ეკონიას გეახალუ $2-ს, $1-ის.',
'jumpto' => 'გეგნორთი:',
'jumptonavigation' => 'ნავიგაცია',
'jumptosearch' => 'გორუა',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage).
'aboutsite' => '{{SITENAME}}-შენი',
'aboutpage' => 'Project:შენი',
'copyright' => 'დინორე მიწორინაფილი რე $1–იშ ჯოხოთ.',
'copyrightpage' => '{{ns:project}}:ოავტორე ალობეფი',
'currentevents' => 'მიმალი მოლინეფი',
'currentevents-url' => 'Project:მიმალი მოლინეფი',
'disclaimers' => 'გამამინჯალაშ ვარება',
'disclaimerpage' => 'Project:გამამინჯალაშ ვარება',
'edithelp' => 'მოხვარა რედაქტირაფას',
'helppage' => 'Help:დინორე',
'mainpage' => 'დუდხასჷლა',
'mainpage-description' => 'დუდხასჷლა',
'portal' => 'ჯარალუაშ ხასჷლეფი',
'portal-url' => 'Project:ჯარალუაშ ხასჷლეფი',
'privacy' => 'ანონიმურობაშ პოლიტიკა',
'privacypage' => 'Project:ანონიმურობაშ პოლიტიკა',

'badaccess' => 'ალობაშ ჩილათა',

'ok' => 'ჯგირი',
'retrievedfrom' => 'გორილ რე "$1"-იშე',
'youhavenewmessages' => 'თქვა გიღუნა $1 ($2).',
'newmessageslink' => 'ახალი შატყვინაფეფი',
'newmessagesdifflink' => 'ეკონია თირაფა',
'editsection' => 'რედაქტირაფა',
'editold' => 'რედაქტირაფა',
'viewsourceold' => 'წყუშ ძირაფა',
'editlink' => 'რედაქტირაფა',
'viewsourcelink' => 'ქოძირი წყუ',
'editsectionhint' => 'სექციაშ რედაქტირაფა: $1',
'toc' => 'დინორე',
'showtoc' => 'ძირაფა',
'hidetoc' => 'ტყობინაფა',
'site-rss-feed' => '$1-იშ RSS არხი',
'site-atom-feed' => '$1-იშ RSS არხი',
'page-rss-feed' => '$1-იშ  არხი  RSS',
'page-atom-feed' => '"$1"–იშ არხი ატომი',
'red-link-title' => '$1 (ხასჷლა ვა რე)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main' => 'ხასჷლა',
'nstab-user' => 'მახვარებუშ ხასჷლა',
'nstab-media' => 'მედიაშ ხასილა',
'nstab-special' => 'გჷშაკერძაფილი ხასჷლა',
'nstab-project' => 'პროექტიშ ხასჷლა',
'nstab-image' => 'ფაილი',
'nstab-template' => 'თანგი',
'nstab-category' => 'კატეგორია',

# Main script and global functions
'nosuchspecialpage' => 'თეჯგუა გჷშაკერძაფილი ხასჷლეფი ვარსებენს',

# General errors
'missing-article' => 'სისტემას ვაძირუ მოგორაფილი ხასჷლაშ ტექსტიქ მუნაჩმეფიშ ბაზას, ჯოხოთ «$1» $2. 

თენა, მუჭოთ წესინი, მოხვადუ თი ბორჯის, მუჟამს მახვარებუ გიაჸუნუუ თი ხასჷლაშ ისტორიაშ რცხუს, ნამუთ ლასირი რე. 
თენა თაშ ვა რე-და, შილებე თქვა ძირით ჩილათა სისტემაშ კოდის. 
ქორთხიინთ, ქატყვინუათ თენა [[Special:ListUsers/sysop|administrator]]–ს, URL–შ მეწურაფათ.',
'missingarticle-rev' => '(რედაქტირაფა#: $1)',
'badtitle' => 'ცაგანა სათაური',
'badtitletext' => 'მოთხილ ხასილაშ ჯოხო ჩილათირ რდუ, ვარა ჩოლიერ, ვარა ხოლო გოხოლუდეს ჩილათირო გინორცხუაფილ ინტერ–ნინა ვარა ინტერ–წიკი ჯოხო. 
თენა შილებე იკათუანდას ართ ვარა უმოს თიშნერ ნიშანს ნამუშ გუმორინაფა ჯოხოს ვა შილებე.',
'viewsource' => 'ქოძირი წყუ',
'viewsourcetext' => 'თქვა შეილებუნა ქოძირათ თე ხასჷლაშ დაჭყაფური ფაილი დო ქუდარსხუათ თიშ მანგი:',

# Login and logout pages
'yourname' => 'მახვარებუშ ჯოხო:',
'yourpassword' => 'პაროლი',
'yourpasswordagain' => 'კჷნე გეკორობით პაროლი:',
'remembermypassword' => 'ქჷგიშინი ჩქიმი მიშულა თე ბრაუზერს (მაქსიმუმ $1 დღას)',
'yourdomainname' => 'თქვან დომენ',
'login' => 'მიშულა',
'nav-login-createaccount' => 'მიშულა/ანგარიშიშ გონწყუმა',
'loginprompt' => '{{SITENAME}}-შა მიშაულარო ოხვილუთ ეკაკილეფიშ (cookies) გოაქტიურაფას.',
'userlogin' => 'მიშულა/ანგარიშიშ გონწყუმა',
'logout' => 'გიშულა',
'userlogout' => 'გიშულა',
'nologin' => 'დიორდე ვარეთო რეგისტრირებული? $1.',
'nologinlink' => 'გონწყით ანგარიში',
'createaccount' => 'ანგარიშიშ გონწყუმა',
'gotaccount' => "უკვე რეგისტრირებული რეთო? '''$1'''",
'gotaccountlink' => 'მინულა',
'userlogin-resetlink' => 'გუგოჭყორდესო მიშაულარო საჭირო ინფორმაციაქ?',
'loginsuccess' => "'''ასე მიშულირ რეთ {{SITENAME}}-ს მუჭოთ \"\$1\".'''",
'nouserspecified' => 'საჭირო რე მახვარებუშ ჯოხოშ მიშაჭარუა.',
'mailmypassword' => 'ახალ პაროლიშ მოჯღონა',
'noemail' => '"$1" მახვარებუშ ელ-ფოშტა წურაფილი ვარე.',
'loginlanguagelabel' => 'ნინა: $1',

# Edit page toolbar
'bold_sample' => 'რუმე ტექსტი',
'bold_tip' => 'რუმე ტექსტ',
'italic_sample' => 'ელართელი ტექსტი',
'italic_tip' => 'ელართელი ტექსტი',
'link_sample' => 'რცხუშ ჯოხო',
'link_tip' => 'დინახალენი რცხუ',
'extlink_sample' => 'http://www.example.com რცხუშ ჯოხო',
'extlink_tip' => 'გალენი რცხუ (ქორშუდანი http:// პრეფიქსი)',
'headline_sample' => 'დუდლანდარიშ ტექსტი',
'headline_tip' => 'მაჟირა დონეშ დუდლანდარი',
'nowiki_sample' => 'ქინახუნეთ უგუფორმატაფუ ტექსტი თაქ',
'nowiki_tip' => 'ვიკიშ ფორმატირაფაშ იგნორირაფა',
'image_tip' => 'დინოხუნაფილი ფაილი',
'media_tip' => 'ფაილიშ რცხუ',
'sig_tip' => 'თქვან ხეშმოჭარა დო ბორჯი',
'hr_tip' => 'ჰორიზონტალური ღოზი (ვაგიმირინუათ ბხშირას)',

# Edit pages
'summary' => 'რეზიუმე:',
'subject' => 'თემა/დუდლანდარი:',
'minoredit' => 'თენა რე ჭიჭე რედაქტირაფა',
'watchthis' => 'თე ხასჷლაშ კონტროლი',
'savearticle' => 'დოჩვი ხასილა',
'preview' => 'გიწოთოლორაფა',
'showpreview' => 'ქაძირე გიწოთოლორაფა',
'showdiff' => 'თირაფეფიშ ძირაფა',
'anoneditwarning' => "'''გური გუჩით:''' თქვა ვარეთ რეგისტრირებული. თქვან IP ოწურაფუ ინოჭარილი იჸი თე ხასჷლაშ რედაქტირაფაშ ისტორიას.",
'summary-preview' => 'რეზიუმეშ გიწოთოლორაფა',
'blockedtext' => "'''თქვან მახვარებუშ ჯოხო ვარა IP მიოწურაფუქ ბლოკირქ იჸუ. '''

ბლოკირაფა ღოლუ \$-ქ.
სამანჯელო წუმორინაფილქ იჸუ გეჸვენჯიქ: ''\$2''.

* ბლოკუაშ დაჭყაფური: \$8
* ბლოკიშ ვადაშ გულა ბორჯი: \$6
* ბლოკირქ იჸუ: \$7

შეილებუნა დეკავშირათ \$1-ს ვარა ნამთინე შხვა [[{{MediaWiki:Grouppage-sysop}}|ადმინისტრატორს]] ბლოკუაშ კილასხუნალო.
გეთოლწონით, ნამდა თქვა ვაგუმგარინენა ფუნქცია: ''მახვარებუშა ელ-ფოშტაშ ჯღონუა'', ვაგაფუნა მეღანკილი მოქმენდი ელ-ფოშტაშ მიოწურაფუ თქვანი [[Special:Preferences|ანგარიშიშ კონფიგურაციას]], ვარა დობლოკუაშ გეშა თე ფუნქციაშ გუმორინაფაშ ნება მიდაღალირ გაფუნა და.
თქვან ასეიან IP მიოწურაფუ რე \$3, დო ბლოკიშ იდენტიფიკატორი #\$5.
რთხიინთ ქიმიოღანკათ თე მუნაჩემეფშე ნამდგაიჸინი (ვარა ჟირხოლო) თქვან კორესპონდენციას.",
'newarticle' => '(ახალ)',
'newarticletext' => "თქვა გეყ’უნელ რეთ ხასილაშ რცხის, ნამუთ დიო ვა რე დორცხუაფილ.
ხასილაშ დარცხუაფალო გემიშეყ’ონით ტექსტ თუდონ ოჭკორიეშა. (ქოძირით[[{{MediaWiki:Helppage}}|მოხვარაშ ხასილა]] უმოს ინფორმაციაშო).
თე ხასილას ჩილათირო მოხვადით–და, ქიგუნჭირით თქვან ბრაუზერიშ კონჭის '''უკახალე'''.\"",
'noarticletext' => 'ასე თე ხასილას ტექსტ ვა რე. 
თქვა შეილებუნა [[Special:Search/{{PAGENAME}}|გორათ ათე ხასილაშ ჯოხო]] შხვა ხასილეფს,
<span class=\\"plainlinks\\">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} დოგორათ მეხუჯაფილ ჟურნალეფ],
ვარა [{{fullurl:{{FULLPAGENAME}}|action=edit}} დიჭყათ ათე ხასილაშ რედაქტირაფა]</span>.',
'noarticletext-nopermission' => 'ათე ხასჷლას ასე ტექსტი ვა რე. თქვა შეილებუნა [[Special:Search/{{PAGENAME}}|დოგორათ თე ხასჷლაშ დუდჯოხო]] შხვა ხასჷლეფს,
ვარდა <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} დოგორათ გინორცხილ ჟურნალეფი]</span>.',
'previewnote' => "'''რშუდანი თენა რე ხვალე გიწოთოლორაფა'''
თქვანი თირაფეფი დიო ვარე ჩუალირი!",
'editing' => 'რედაქტირაფა $1',
'editingsection' => '$1–იშ (სექციაშ) რედაქტირაფა',
'yourtext' => 'თქვან ტექსტი',
'copyrightwarning' => "გეთოლწონით, ნამდა {{SITENAME}} ხასილაშა თქვან ხეშე მიშაღალირ არძონერ თია იფორუ \$2-ით (დეტალეფშო ქოძირით \$1). 
ვა გოკონა თქვან ნახანდაქ დუდშულო   რედაქტირებულქ დო გიონოჯღონელქ იყ’უას–და, ვა მიშეყ’ონათ თინა თაქ.<br />
თქვა ხოლო პიჯალას დუთმოდვანთ, ნამდა თე ტექსტი თქვან ნაჭარა რე, ვარა გინოღალირ რე ოირკოჩე დომენშე დო ვარა თიშ მანგურ დუდიშულ წყუშე. 
'''ვა მიშეღათ ოავტორე ნებეფით თხილერ ნახანდი ავტორიშ ქოყ’იაშ უმშო!'''\"",
'templatesused' => 'თე ხასჷლას გიმორინაფილი {{PLURAL:$1|თანგი|თანგეფი}}:',
'templatesusedpreview' => '{{PLURAL:$1|თანგი|თანგეფი}} ნამუთ თე გჷწოთოლორაფას რე გიმორინაფილი',
'template-protected' => '(თხილერი)',
'template-semiprotected' => '(გვერდო თხილერი)',
'hiddencategories' => 'თე ხასილა ოკათუ {{PLURAL:$1|1 ტყობინაფილ კატეგორიას|$1 ტყობინაფილ კატეგორიეფს}}:',
'nocreatetext' => 'თე ხასილას ვა შილებე ახალ ხასილაშ გექიმინუა დორცხუაფილ ვარიაშ გეშა. თქვა შეგილებუნა კინორთა დო გექიმინელ ხასილაშ რედაქტირაფა, ვარა [[სპეციალურ:Userlogin|მიშულა დო ანგარიშიშ დორცხუაფა]]',
'permissionserrorstext-withaction' => 'თქვა ვა გიღუნა $2–იშ ღოლამაშ ალობა თე გეჸვენჯი {{PLURAL:$1|სამანჯელით|სამანჯელეფით}}:',
'recreate-moveddeleted-warn' => "'''გართხილება: თქვა კინე ახალშო ორსხუანთ ხასჷლას, ნამუქჷთ ლასირქ იჸუ ოწოხოლე'''

რთხიინთ, დეფირქათ, ქორე თუ ვარ მისაღეფ თე ხასჷლაშ რედაქტირაფაშ გაგინძორება.
თქვან ოხუჯურო, ათე ხასილაშ ლასუაშ დო გინოღალაშ ჟურნალი მოჩამილ რე თუდო:",
'moveddeleted-notice' => 'თე ხასჷლაქ ლასირქ იჸუ. ლასუაშ დო გინოღალაშ ჟურნალი მოჩამილ რე თუდო გიმაწურაფალო',

# Parser/template warnings
'post-expand-template-inclusion-warning' => '"გური ქუჩით:" ინოხუნაფილ თანგეფიშ ზომა ნაბტანი დიდი რე. კანკალე თანგეფი ვენიკათინე.',
'post-expand-template-inclusion-category' => 'ხასჷლეფი ნამუსუთ ინოხუნაფილი თანგეფიშ ზომა გინომეტებული რე.',
'post-expand-template-argument-warning' => "\"'''გური ქუჩით:''' თე ხასჷლას ოკათ არძოშ უკლაშ ართ თიცალ თანგიშ არგუმენტი, ნამუსუთ უღუ გოძინელოფაშ ნაბტან დიდ ზომა.
თე არგუმენტეფქ გჷშატებულქ იჸუ.\"",
'post-expand-template-argument-category' => 'უარგუმენტო თანგამი ხასჷლეფი',

# History pages
'viewpagelogs' => 'თე ხასილაშო ორეგისტრაციე ჟურნალეფიშ ძირაფა',
'currentrev' => 'მიმალ გიშანწყუალა',
'currentrev-asof' => '$1–შო მიმალ რედაქცია',
'revisionasof' => '$1 თარიღიშო დო საათიშო რსებულ ვერსია',
'revision-info' => '$1-იშ ვერსია, $2-იშ მიშაღალირ',
'previousrevision' => '←უმოს ჯვეშ ვერსია',
'nextrevision' => 'უახალაშ ვერსია→',
'currentrevisionlink' => 'მიმალ ვერსია',
'cur' => 'მიმალ',
'last' => 'ეკონია',
'page_first' => 'პირველი',
'page_last' => 'ეკონია',
'histlegend' => "მეღანკილ: ართიანიშ მიოზიმაფალო კორნებულ ვერსიეფიშ რადიოშ ოჭკორიეფ ქიმიოღანკეთ დო გეუნჭირით მიშულაშ კონჭის, ვარა ქვინჯის რენ თი კონჭის.
ლეგენდა: '''კუნტარაფეფ: ({{მიმ.}})''' = შხვაობა მიმალ ვერსიაწკუმა, '''({{ეკონია}}) = შხვაობა ოწოხოლენ ვერსიაწკუმა, ჭ = ჭიჭე რედაქტირაფეფ.",
'history-fieldset-title' => 'ისტორიაშ გინოჯინა',
'history-show-deleted' => 'ხვალე ლასირეფი',
'histfirst' => 'პირველი',
'histlast' => 'ეკონია',
'historysize' => '($1 ბაიტი)',
'historyempty' => '(ჩოლიერი)',

# Revision feed
'history-feed-title' => 'რედაქტირებიშ ისტორია',
'history-feed-description' => 'თენა გვერდიშ რედაქტირებეფიშ ისტორია ვიკის',
'history-feed-item-nocomment' => '$1  $2-ს',

# Revision deletion
'rev-delundel' => 'ძირაფა/ტყობინაფა',
'revdel-restore' => 'ორწყენჯობაშ თირუა',
'revdel-restore-deleted' => 'ლასირი რევიზიეფი',
'revdel-restore-visible' => 'ძირაფადი რევიზიეფი',

# Merge log
'revertmerge' => 'ეკორტყუალაშ მოლასუა',

# Diffs
'history-title' => '"$1"–იშ თირაფეფიშ ისტორია',
'lineno' => 'ღოზი $1:',
'compareselectedversions' => 'გიშაგორილ ვერსიეფიშ მეზიმაფა',
'editundo' => 'გოუქვაფა',
'diff-multi' => '( {{PLURAL:$2|ართი მახვარებუშ|$2 მახვარებუშ}} {{PLURAL:$1|ართი შქაშქუმალირი რევიზია|$1 შქაშქუმალირი რევიზია}} ვა რე ძირაფილი)',

# Search results
'searchresults' => 'გორუაშ მოღალირობეფ',
'searchresults-title' => '"$1"–იშ გორუაშ მოღალირობეფ',
'searchresulttext' => '{{SITENAME}}–იშ ოგორალო უმოს იმფორმაციოაშო  ქოძირით  [[{{MediaWiki:Helppage}}|{{int:help}}]].',
'searchsubtitle' => 'თქვა დოგორით \'\'\'[[:$1]]\'\'\' ([[Special:Prefixindex/$1|ირი ხასილა, დოჭყაფილი "$1"-ით]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|ირი ხასილა, გინორცხილ "$1"-შა]])',
'searchsubtitleinvalid' => "თქვა გორუნდით '''$1'''",
'notitlematches' => 'ვა უხუჯანს ნამუთინი ხასილაშ ჯოხო',
'notextmatches' => 'ნამთინ ხასილაშ ტექსტი ვა უხუჯანს',
'prevn' => 'წოხლენ $1',
'nextn' => 'უკულიან {{PLURAL:$1|$1}}',
'prevn-title' => 'წოხოლენი $1 მოღალუ',
'nextn-title' => 'გეჸვენჯი $1 მოღალუ',
'shown-title' => 'ქაძირი $1 მოღალუ ირ ხასჷლას',
'viewprevnext' => 'ქოძირ  ($1 {{int:pipe-separator}} $2) ($3).',
'searchmenu-exists' => 'თე ვიკის "[[:$1]]" ჯოხოთ ხასჷლა რე',
'searchmenu-new' => "''ქჷდარსხი \"[[:\$1]]\" ხასჷლა თე ვიკის'''",
'searchprofile-articles' => 'სტატიეფი',
'searchprofile-project' => 'მოხვარაშ დო პროექტიშ ხასჷლეფი',
'searchprofile-images' => 'მულტიმედია',
'searchprofile-everything' => 'ირფელი',
'searchprofile-advanced' => 'გაუჯგუშებული',
'searchprofile-articles-tooltip' => 'დოგორი $1-ს',
'searchprofile-project-tooltip' => 'გორუა $1-ის',
'searchprofile-images-tooltip' => 'დოგორი ფაილეფი',
'searchprofile-everything-tooltip' => 'გორუა არძო ხასილას (ოჩიებელი ხასჷლეფიშ მეკოროცხილო)',
'searchprofile-advanced-tooltip' => 'გორუა მახვარებუშ გენჭყილ ჯოხოეფიშ ოფირჩას',
'search-result-size' => '$1 ({{PLURAL:$2|1 ზიტყვა|$2 ზიტყვეფ}})',
'search-result-category-size' => '{{PLURAL:$1|1 მაკათური|$1 მაკათური}} ({{PLURAL:$2|1 გიმენკატეგორია|$2 გიმენკატეგორია}}, {{PLURAL:$3|1 ფაილი|$3 ფაილეფი}})',
'search-redirect' => '(გინოწურაფა $1)',
'search-section' => '(სექცია $1)',
'search-suggest' => 'ათენას ხო ვა გორუნდით: $1',
'search-interwiki-caption' => 'ჯიმაია პროექტეფ',
'search-interwiki-default' => 'მოღალირეფი $1-შე:',
'search-interwiki-more' => '(უმოს)',
'searchrelated' => 'მათანგეფ',
'searchall' => 'არძო',
'showingresultsheader' => "{{PLURAL:$5|მოღალუ '''$1''' '''$3'''-შე|მოღალუეფ '''$1 - $2''' '''$3'''-შე}} '''$4'''-შო",
'nonefound' => "'''გეთოლწონით''': სტანდარტულო ხვალე ნამთინე ჯოხოთ ოფირჩას მეურს გორუა.
ოგორალი ზიტყვას ვარა ზიტყვეფიშ ბუნას წოხოლე ქეწუყ’უნეთ ''all:'' ირდიხას იგორასინ თიშენ (სხუნუაშ ხასილეფიშ, თანგეფიშ დო ა.უ. მეკოროცხუათ,), ვარ-და პრეფიქსო გიმირინეთ კორნებულ ჯოხოთ ოფირჩა.",
'search-nonefound' => 'თქვანი მოგორაფილიშ მუთუნნერ მანგი მოღალუქ ვეძირჷ.',
'powersearch' => 'გოძინელ გორუა',
'powersearch-legend' => 'გოძინელ გორუა',
'powersearch-ns' => 'დოგორ ჯოხოეფიშ ოფირჩას:',
'powersearch-redir' => 'გინოწურაფეფიშ ერკებულიშ ძირაფა',
'powersearch-field' => 'დოგორი ათენა',

# Preferences page
'preferences' => 'კონფიგურაცია',
'mypreferences' => 'ჩქიმ კონფიგურაციეფი',
'youremail' => 'ელ-ფოშტა:',
'yourrealname' => 'ნანდულ სახელ *',
'yourlanguage' => 'ნინა:',
'prefs-help-email' => 'ელ-ფოშტაშ მიოწურაფუ ვა რე უციო, მარა ოხვილაფუ რე პაროლიშ ეიორსხებელო პაროლი გიჭყოლიდეთუ̂-და.',
'prefs-help-email-others' => 'თქვა შეილებუნა ალობა მეჩათ შხვეფს დჷგეკონტაქტან ელ-ფოშტათ თქვან ანგარიშის ვარა ოჩიებელ ხასჷლაშ ლინკიშ გეჸუნათ. თქვანი ელ-ფოშტაშ მიოწურაფუ ვანკორჩქინდჷ მუჟამს შხვა მახვარებუეფი დჷგეკავშირებუნანი.',

# Groups
'group-user' => 'მახვარებუეფი',
'group-sysop' => 'ადმინისტრატორეფი',

'grouppage-user' => '{{ns:project}}:მახვარებუეფ',
'grouppage-sysop' => '{{ns:project}}:ხემანჯღვერეფი',

# Special:Log/newusers
'newuserlogpage' => 'მახვარებუშ რეგისტრაციაშ ჟურნალ',

# User rights log
'rightslog' => 'მახვარებუშ ნებეფიშ ჟურნალ',

# Associated actions - in the sentence "You do not have permission to X"
'action-edit' => 'თე ხასილაშ რედაქტირაფა',

# Recent changes
'nchanges' => '$1 {{PLURAL:$1|თირაფა|თირაფეფ}}',
'recentchanges' => 'ასეიანი თირაფეფი',
'recentchanges-legend' => 'ასერდენ თირაფეფიშ ოფციეფ',
'recentchanges-summary' => 'თე ხასჷლას ქაძირი ვიკიშა მიშაღალირ არძოშ უახალაშ თირაფეფი.',
'recentchanges-feed-description' => "ვიკიშ უახალაშ თირაფეფიშა თოლყ'უჯიშ მეყ'უნაფა თე არხის",
'recentchanges-label-newpage' => 'ათე რედაქტირაფას მაჸუნ ახალ ხასჷლაშ დორსხუაფაქ.',
'recentchanges-label-minor' => 'თენა რე ციქა რედაქტირაფა',
'recentchanges-label-bot' => 'თე რედაქტირაფა ბოტიშ ნაღოლემი რე',
'recentchanges-label-unpatrolled' => 'თე რედაქტირაფა დიო ხოლო ვა რე პატრულირაფირი',
'rcnote' => "თუდოლე ძირით ეკონია {{PLURAL:$1|'''1''' თირაფა|$1 თირაფა}} ეკონია {{PLURAL:$2|დღაშ|'''$2''' დღაშ}}, $5, $4 რენჯობათ.",
'rcnotefrom' => 'თუდო მოჸუნაფილიე თირაფეფ, ”’$2””-შე (ძირაფილიე ”’$1”’)',
'rclistfrom' => 'ახალ თირაფეფიშ ძირაფა დოჭყაფილ $1-შე',
'rcshowhideminor' => '$1 ჭიჭე რედაქტირაფეფ',
'rcshowhidebots' => 'ბოტეფიშ  $1',
'rcshowhideliu' => '$1 მიშულირ მახვარებუეფ',
'rcshowhideanons' => '$1 ანონიმურ მახვარებუეფ',
'rcshowhidepatr' => 'გოკონტროლაფირ თირაფეფიშ $1',
'rcshowhidemine' => 'ჩქიმ რედაქტირაფეფიშ $1',
'rclinks' => 'ეკონია $2 დღას ღოლამირ ეკონია $1 თირაფეფიშ ძირაფა <br />$3',
'diff' => 'შხვანერობა',
'hist' => 'ისტ.',
'hide' => 'ტყობინაფა',
'show' => 'ძირაფა',
'minoreditletter' => 'ჭ.რ.',
'newpageletter' => 'ახ.',
'boteditletter' => 'ბ',
'rc-enhanced-expand' => 'დეტალეფიშ ძირაფა (ითხინს ჯავასქრიფთის)',
'rc-enhanced-hide' => 'დეტალეფიშ ტყობინაფა',

# Recent changes linked
'recentchangeslinked' => 'აკოხვალამირ თირაფეფ',
'recentchangeslinked-feed' => 'აკოხვალამირ თირაფეფ',
'recentchangeslinked-toolbox' => 'აკოხვალამირ თირაფეფ',
'recentchangeslinked-title' => '"$1"-შა მებუნაფილ თირაფეფი',
'recentchangeslinked-summary' => "თენა რე მეწურაფილი ხასილაწკუმა (ვარა მეწურაფილი კატეგორიაშ მაკათურეფწკუმა) გინორცხუაფილი ხასილეფს ეკონია ბორჯის ღოლამირი თირაფეფიშ ერკებულ. ხასილეფი [[Special:Watchlist|your watchlist]] გიმორთილი რე '''ფსქელას'''.\"",
'recentchangeslinked-page' => 'ხასილაშ ჯოხო:',
'recentchangeslinked-to' => 'მანგიერო ქაძირე ათე ხასილაშა მერცხილ ხასილეფშა მიშაღალირ თირაფეფ',

# Upload
'upload' => 'ფაილიშ ეხარგუა',
'uploadbtn' => 'ფაილიშ გეთება',
'uploadlogpage' => 'ეხარგუაშ ორეგისტრირებელ ჟურნალ',
'filedesc' => 'რეზიუმე',
'uploadedimage' => 'ეხარგელი რე "[[$1]]"',

'license' => 'ლიცენზირაფა:',
'license-header' => 'ლიცენზირაფა',

# Special:ListFiles
'listfiles' => 'სურათეფიშ ერკებულ',
'listfiles_name' => 'სახელ',

# File description page
'file-anchor-link' => 'ფაილი',
'filehist' => 'ფაილიშ ისტორია',
'filehist-help' => 'ქიგუნჭირით რიცხვის/ბორჯის თიშო, ნამდა ქოძირათ ფაილი თი რედაქციათ, მუ რედაქციას თი რიცხვის/ბორჯის რდუნ.',
'filehist-revert' => 'დართინე',
'filehist-current' => 'მიმალ',
'filehist-datetime' => 'რიცხვი/ბორჯი',
'filehist-thumb' => 'ჭკუდი',
'filehist-thumbtext' => 'ჭკუდი $1-შო რსებულ ვერსიაშო',
'filehist-user' => 'მახვარებუ',
'filehist-dimensions' => 'განზომილებეფ',
'filehist-filesize' => 'ფაილიშ ზომა',
'filehist-comment' => 'კომენტარ',
'imagelinks' => 'ფაილი გჷმორინაფილი რე',
'linkstoimage' => 'გეყ’ვენჯი {{PLURAL:$1|ხასილა|ხასილეფ}} მერცხილ რე თე ფაილშა',
'nolinkstoimage' => 'ვა რე თე ფაილწკუმა მერსხილ ხასილეფ.',
'sharedupload' => 'თე ფაილ რე $1-შე დო შილებე თენა შხვა პროექტეფც ხოლო გიმირინაუფუდასინ',
'sharedupload-desc-here' => 'თე ფაილი რე $1-შე დო შილებე გჷმორნაფილქ იჸუას შხვა პროექტეფს. თეშ ეჭარუა [$2 ფაილიშ ეჭარუაშ ხასჷლა] თუდოლე რე მოჩამილი.',
'uploadnewversion-linktext' => 'გეშახარგე ათე ფაილიშ ახალ ვერსია',

# MIME search
'mimesearch' => 'MIME გორუა',

# Random page
'randompage' => 'ნამდგარენ ხასილა',

# Statistics
'statistics' => 'სტატისტიკა',

'withoutinterwiki' => 'ხასილეფ ნინაშ რსხილეფიშ გარეშე',

# Miscellaneous special pages
'nbytes' => '$1 ბაიტი',
'nlinks' => '$1 რსხილ',
'nmembers' => '$1 {{PLURAL:$1|მაკათურ|მაკათურეფ}}',
'uncategorizedpages' => 'უკატეგორიე ხასილეფ',
'uncategorizedcategories' => 'კატეგორიეფ კატეგორიეფიშ გარეშე',
'uncategorizedimages' => 'სურათეფ კატეგორიაშ უმიშო',
'mostlinked' => 'ხასილეფ, ნამუდგა არძას ბრალ ბუნილეფ უღუნა',
'mostlinkedcategories' => 'კატეგორიეფ, ნამუდგა არძას ბრალ რსხილეფ უღუნა',
'mostcategories' => 'სტატიეფ, ნამუდგა არძას ბრალ კატეგორიეფ უღუნა',
'prefixindex' => 'არძო ხასილა პრეფიქსით',
'shortpages' => 'ჭიჭე ხასილეფ',
'longpages' => 'გინძე ხასილეფ',
'usercreated' => '{{GENDER:$3|დირსხუ}} $2-ის $1-ს',
'newpages' => 'ახალ ხასილეფ',
'ancientpages' => 'ჯვეშ ხასილეფ',
'move' => 'გინოღალა',
'movethispage' => 'თე გვერდიშ გინოღალა',
'pager-newer-n' => '{{PLURAL:$1|უახალაშ 1|უახალაშ $1}}',
'pager-older-n' => '{{PLURAL:$1|უმოს ჯვეში 1|უმოს ჯვეში $1}}',

# Book sources
'booksources' => 'წინგიშ წყუეფ',
'booksources-search-legend' => 'წიგნიშ წყუშ გორუა',
'booksources-go' => 'გინულა',

# Special:Log
'specialloguserlabel' => 'მახვარებუ:',
'speciallogtitlelabel' => 'სათაურ:',
'log' => 'ჟურნალეფ',
'all-logs-page' => 'ირ ჟურნალ',

# Special:AllPages
'allpages' => 'არძა ხასილა',
'alphaindexline' => '$1-იშე $2-შა',
'nextpage' => 'უკულ ხასილა ($1)',
'prevpage' => 'წოხლენ ხასილა ($1)',
'allpagesfrom' => 'გეგმარჩქინ ხასილეფ დოჭყაფილ:',
'allpagesto' => 'გეგმარჩქინ ხასილეფ, ნამუთ ითებუ:',
'allarticles' => 'არძა სტატია',
'allpagessubmit' => 'გინულა',

# Special:Categories
'categories' => 'კატეგორიეფი',

# Special:LinkSearch
'linksearch' => 'გალენ რცხიეფ',
'linksearch-line' => '$1 მერცხიილი რე $2-შე',

# Special:ListGroupRights
'listgrouprights-members' => '(მაკათურეფიშ ერკებული)',

# Email user
'emailuser' => 'მიდუჯღონით ელ.ფოშტა ათე მახვარებუს',

# Watchlist
'watchlist' => 'ჩქიმ ოკონტროლებულეფიშ ერკებულ',
'mywatchlist' => 'ჩქიმ კონტროლიშ ერკებულ',
'watchlistfor2' => '$1 $2-ს',
'addedwatchtext' => "\"[[:\$1]]\" ხასილაქ გეძინელქ იყ’უ თქვან [[Special:Watchlist|watchlist]]–შა.
თე ხასილაშა დო თეწკუმა ასოცირებულ სხუნუაშ ხასილაშა მუმაულარ თირაფეფ მოჩამილ იყ’ი თექ დო თქვა გეგეადვილან თიშ გიშაგორუაქინ, ხასილა გუმორჩქინდუ '''რუმეთ'''  [[Special:RecentChanges|list of recent changes]]–ს.\"",
'removedwatchtext' => 'ათე ხასილაქ "[[:$1]]" ლასირქ იყ’უ  [[Special:Watchlist|თქვნ კონტროლიშ ერკებულშე]].',
'watch' => 'გაკონტროლი',
'watchthispage' => 'თე ხასილაშ კონტროლ',
'unwatch' => 'კონტროლიშ გოუქვაფა',
'watchlist-details' => '{{PLURAL:$1|$1 ხასილა|$1 ხასილეფ}} რე თქვან კონტროლიშ ერკებულს, სხუნუაშ ხასილეფიშ მეუკოროცხუო.',
'wlshowlast' => 'ეკონია $1 საათიშ $2 დღაშ $3 ძირაფა',
'watchlist-options' => 'კონტროლიშ ერკებულიშ ოფციეფ',

# Displayed when you click the "watch" button and it is in the process of watching
'watching' => 'კონტროლირებად...',
'unwatching' => 'კონტროლ მონწყუმილ რე ...-შა',

# Delete
'deletepage' => 'ხასილაშ ლასუა',
'confirmdeletetext' => 'თქვა თე ხასილაშ, თელ მუშ ისტორიათ, ლასუაშ პიჯის რეთ.
დადასტურით, ნამდა თქვა ნანდულო გოკონა თეშ ღოლამა დო ნამუდა თქვა გარჩქილენა თე ქიმინჯალაშ მოღალუეფ დო მუჭოთ თქვა თეს ორთუთ [[{{MediaWiki:Policy-url}}|წესეფიშ]] მეხუჯაფილო.',
'actioncomplete' => 'მოქმედალა რსულებულ რე',
'actionfailed' => 'მოქმედალაქ დემარცხჷ',
'deletedtext' => '"$1\\" ლასირქ იყ’უ.
ასერდე ლასირ ხასილეფიშ ერკებულ ქოძირით $2–ს.',
'dellogpage' => 'ლასირეფიშ ერკებულ',
'deletecomment' => 'სამანჯელ:',
'deleteotherreason' => 'შხვა/გეძინელ სამანჯელ:',
'deletereasonotherlist' => 'შხვა სამანჯელ',

# Rollback
'rollbacklink' => 'დორთა',

# Protect
'protectlogpage' => 'თხილუაშ ისტორია',
'protectedarticle' => 'თხილერი რე "[[$1]]"',
'modifiedarticleprotection' => 'დოთირეთ თხილუაშ დონე \\"[[$1]]\\-შო',
'prot_1movedto2' => '[[$1]] გინოღალირიე ხასილაშა [[$2]]',
'protectcomment' => 'სამანჯელი:',
'protectexpiry' => 'ვადა გიშალე',
'protect_expiry_invalid' => 'ვადაშ გიშულაშ თარიღ რე ჩილათირ',
'protect_expiry_old' => 'ვადაშ გიშულაშ თარიღ რე ულირ ბორჯის',
'protect-text' => "'''$1''' ხასილაშო თხილუაშ დონეშ ძირაფა დო თირაფა შეგილებუნა თაქ.",
'protect-locked-access' => "თქვა ვა გიღუნა ხასილაშ თხილუაშ დონეშ თირუაშ ალობა. 
ათაქ რე '''$1''' ხასილაშ ასეიან გენწყილობეფ.",
'protect-cascadeon' => 'ათე ხასილა ასე თხილერ რე, თიშენ ნამდა თენა მიშულირ რე {{PLURAL:$1|ხასილაშა, ნამდგასით|ხასილეფშა, ნამდგეფსით}} ჩართულ აფუ კასკადურ თხილუა.
თქვა შეგილეუნა დოთირუათ ათე ხასილაშ თხილუაშ დონე, მარა თეს გავლენა ვაღვენუ კასკადურ თხილუაშა.',
'protect-default' => 'ალობა ქიმეჩ არძა მახვარეს',
'protect-fallback' => '"$1" ალობა რე საჭირო',
'protect-level-autoconfirmed' => 'ახალ დო ვარეგისტრირაფილ მახვარებუეფიშ ბლოკუა',
'protect-level-sysop' => 'ხვალე ადმინისტრატორეფ',
'protect-summary-cascade' => 'კასკადურ',
'protect-expiring' => 'ვადა გიშალე $1 (UTC)',
'protect-cascade' => 'დოთხილე ხასილეფ, ნამუთ მიშულირ რე ათე ხასილაშა (კასკადურ თხილუა)',
'protect-cantedit' => 'თქვა ვა გათირენა ათე ხასილაშ თხილუაშ დონე, თიშენ ნამდა თქვა ვა გიღუნა ალობა თეშ რედაქტირაფაშო',
'protect-expiry-options' => '2 საათი:2 hours,1 დღა:1 დღა,1 მარა:1 week,2 მარა:2 weeks,1 თუთა:1 month,3 თუთა:3 months,6 თუთა:6 months,1 წანა:1 year,განუსაზღვრელი ვადით:infinite',
'restriction-type' => 'ალობა:',
'restriction-level' => 'შეზღუდვაშ დონე',

# Undelete
'undeletebtn' => 'ახალშო ეკონწყუალა',
'undeletelink' => 'ძირაფა/ეკონწყუალა',
'undeleteviewlink' => 'ძირაფა',
'undelete-search-submit' => 'გორუა',

# Namespace form on various pages
'namespace' => 'ჯოხოეფიშ ოფირჩა:',
'invert' => 'არძა, მეღანკილიშ გუმორკებულო',
'blanknamespace' => '(დუდ)',

# Contributions
'contributions' => 'მახვარებუშ მიშაღალირ თია',
'contributions-title' => '$1-შა მახვარებუშ მიშაღალირ თია',
'mycontris' => 'ჩქიმ მიშნაღელ თია',
'contribsub2' => '$1 ($2) შენი',
'uctop' => '(დუდ)',
'month' => 'ათე თუთაშე (დო უადრაშე):',
'year' => 'ათე წანაშე (დო უადრაშე):',

'sp-contributions-newbies' => 'ქოძირით ხვალე ახალ მახვარებუეფიშ მიშაღალირ თიეფ',
'sp-contributions-newbies-sub' => 'ახალეფშოთ',
'sp-contributions-blocklog' => 'ბლოკირაფაშ ისტორია',
'sp-contributions-uploads' => 'ეხარგუეფ',
'sp-contributions-logs' => 'ჟურნალეფი',
'sp-contributions-talk' => 'ოჩიებელი',
'sp-contributions-search' => 'მიშაღალირ თიაშ გორუა',
'sp-contributions-username' => 'IP მიოწურაფუ ვარა მახვარებუშ ჯოხო:',
'sp-contributions-toponly' => 'ქაძირი ხვალე ეკონია რევიზიეფი რენ ფერი რედაქტირაფეფი',
'sp-contributions-submit' => 'გორუა',

# What links here
'whatlinkshere' => 'სოვრეშე რე თე ხასილა წურაფილ',
'whatlinkshere-title' => 'ხასილეფ, ნამუთ გინორცხილ რე $1-შა',
'whatlinkshere-page' => 'ხასჷლა:',
'linkshere' => "გეყ’ვენჯ ხასილეფ გინარცხუაფუ '''[[:$1]]'''-ეფს",
'nolinkshere' => "ნამთინ ხასილა ვა რე გინორცხილ '''[[:$1]]'''-შა.",
'isredirect' => 'გინოწურაფაშ ხასილა',
'istemplate' => 'ტრანსკლუზია',
'isimage' => 'ფაილიშ რცხი',
'whatlinkshere-prev' => '{{PLURAL:$1|წოხოლენ|წოხოლენ $1}}',
'whatlinkshere-next' => '{{PLURAL:$1|უკულიან|უკულიან $1}}',
'whatlinkshere-links' => '← რცხიეფ',
'whatlinkshere-hideredirs' => 'გინოწურაფა $1',
'whatlinkshere-hidetrans' => '$1 ტრანსკლუზიეფ',
'whatlinkshere-hidelinks' => '$1 რცხუეფ',
'whatlinkshere-hideimages' => '$1 სურათიშ რცხუეფი',
'whatlinkshere-filters' => 'ტკიბირეფი',

# Block/unblock
'blockip' => 'მახვარებუშ ბლოკირაფა',
'ipboptions' => '2 საათი:2 hours,1 დღა:1 day,3 დღა:3 days,1 მარა:1 week,2 მარა:2 weeks,1 თუთა:1 month,3 თუთა:3 months,6 თუთა:6 months,1 წანა:1 year,უხურგე ვადათ:infinite',
'ipbotheroption' => 'შხვა',
'ipblocklist' => 'ბლოკირელ მახვარებუეფჷ',
'ipblocklist-submit' => 'გორუა',
'blocklink' => 'ბლოკირაფა',
'unblocklink' => 'ბლოკიშ მონწყუმა',
'change-blocklink' => 'ბლოკიშ თირუა',
'contribslink' => 'ნახანდ',
'blocklogpage' => 'ბლოკირეფიშ ერკებულ',
'blocklogentry' => 'ბლოკირ რე [[$1]] ბლოკირაფაშ ვადაშ ათე გულა ბორჯით: $2 $3.',
'unblocklogentry' => '$1-შა ბლოკიშ მონწყუმა',
'block-log-flags-nocreate' => 'ანგარიშიშ გონწყუმა მეჭყვადილ რე',

# Move page
'move-page-legend' => 'გვერდიშ გინოღალა',
'movepagetext' => "გიმენ ფორმაშ გუმორინაფა ხასილას დუთირანს ჯოხოს დო თელ თეშ ისტორიას გეგნიღანს ახალ ჯოხოშა. 
ჯვეშ ჯოხო გინირთუ ახალ ჯოხოშა გინმაწურაფალ ხასილათ. 
თქვა შეილებუნა ავტომატურო გაახალათ თი გინოწურაფეფ, ნამუთ ჯვეშ ჯოხოშა ირძენა წურაფასინ. 
თქვა ქისხუნუანთ, ნამდა თენა ვა ღოლათინ, აუცილებერო შეამოწმით [[Special:DoubleRedirects|double]], ვარა [[Special:BrokenRedirects|broken redirects]].
თქვა რეთ თიშ გამამინჯე, ნამუდა რცხუეფ იწურუაფუდან თი ხასილეფშა, სოდგა თინეფ წესით ოკო იწურუანინ.


გეთოლისწორით, ნამუდა ხასილა '''ვა''' გინურს უკვე ქო არსებენს ხასილა ახალ ჯოხოთი-და დო თე ხასილა ვა რე ჩოლიერ-და, ვარა გინმაწურაფალ-და დო ვა უღუ რედაქტირეფეფიშ ისტორია-და. 

თენა თის ნიშნენს, ნამუდა ჩილათაქ მოირთეს–და, თქვა შეილებუნა ხასილას დურთინუათუ ჯვეში ჯოხო, მარა ვა შეილებუნა რსებულ ხასილას გინაჭარათინ. 

'''გათხილება!'''
თენაქ შილება იყ’უას პოპულარულ ხასილაშა მიშაღალირ მოულოდნელ დო არსებით თირაფაქ; ქორთხინთ, ათე ქიმინჯიშ მოღალუეფ გეთოლისწორათ სოიშახ მიაყ’უნუდათ თეშ ღოლამასინ.",
'movepagetalktext' => "ასოცირებულ სხუნუაშ ხასილა ავტომატურო იყ'ი გინოღალირ თეწკუმა ართო, '''გეყ'ვენჯ შემთხვევეფიშ გიშარკებულო:''' 
*ჩოლიერ ვარენ ეფერ სხუნუაშ ხასილა უკვე რსებენს ახალ ჯოხოთ, ვარდა
*თქვა თუდო მოჩამილ ოჭკორიეს მონწყუნთ მიკიწონებაშ ღანკის–და.

ათე შემთხვევეფს, თენა მიკორინეთ–და, თქვა გაყ’ინა ხასილეფ მანუალურო გინაღალარ, ვარდა აკორტყუალარ.",
'movearticle' => 'ხასილაშ გინოღალა',
'newtitle' => 'ახალ ჯოხო',
'move-watch' => 'წყუ ხასილაშ დო სამიზნე ხასილაშ კონტროლ',
'movepagebtn' => 'ხასილაშ გინოღალა',
'pagemovedsub' => 'გინოღალა თებულ რე',
'movepage-moved' => '\'\'\'\\"$1\\" გინოღალირ რე ათაქ: \\"$2\\"\'\'\'',
'articleexists' => 'ხასილა თე ჯოხოთ უკვე რსებენს, ვარდა თქვან გიშაგორილ ჯოხო ვა რე თინ. 
ქორთხინთ, შხვა ჯოხო გეგშეგორათინ',
'talkexists' => "'''ხასილაქ გინოღალირქ იყ’უ, მარა სხუნუაშ ხასილაქ ვეგნიღინუ, თიშენ ნამდა თინა უკვე არსებენს ახალ ჯოხოთ. 
ქორთხინთ, აკორტყუათ თინეფ მანუალურო.'''",
'movedto' => 'გინაღალულ რე',
'movetalk' => 'ასოცირებულ სხუნუაშ ხასილაშ გინოღალა',
'movelogpage' => 'ორეგისტრაციე ჟურნალიშ გინოღალა',
'movereason' => 'სამანჯელი:',
'revertmove' => 'გოუქვაფა',

# Export
'export' => 'ხასილეფიშ ექსპორტ',

# Namespace 8 related
'allmessages' => 'ირ სისტემურ შეტყვინაფა',
'allmessagesname' => 'ჯოხო',
'allmessagesdefault' => 'შატყვინაფაშ სტანდარტულ ტექსტი',

# Thumbnails
'thumbnail-more' => 'მორდი',
'thumbnail_error' => 'ესკიზიშ ქიმინუაშ ჩილათა: $1',

# Import log
'importlogpage' => 'იმპორტიშ ჟურნალ',

# Tooltip help for the actions
'tooltip-pt-userpage' => 'თქვანი მახვარებუშ ხასილა',
'tooltip-pt-mytalk' => 'თქვანი სხუნუაშ ხასილა',
'tooltip-pt-preferences' => 'ჩქიმ კონფიგურაციეფ',
'tooltip-pt-watchlist' => 'ხასილეფიშ ერკებულ, ნამუთუშ მონიტორინგის ორთუთ თირაფაშ მიზანით',
'tooltip-pt-mycontris' => 'თქვანი მიშნაღელ თიეფიშ ერკებულ',
'tooltip-pt-login' => 'ჯგირ იჸი გემშურთუ–და, მარა თენა ვა რე სავალდებულო',
'tooltip-pt-logout' => 'გიშულა',
'tooltip-ca-talk' => 'დინორეშ ხასილაშ სხუნუა',
'tooltip-ca-edit' => 'თქვა შეილებნა თე ხასილაშ რედაქტირაფა. რთხინთ, გეუნჭირით გიწოთოლორაფაშ კონჭის სოიშახ ხასილას ჩუანდათინ',
'tooltip-ca-addsection' => 'ქიდიჭყით ახალ სექცია',
'tooltip-ca-viewsource' => 'ხასილა თხილერ რე. 
შეგილებუნა ძირათ თეშ წყუ.',
'tooltip-ca-history' => 'თე ხასილაშა მიშაღალირ თირაფეფ',
'tooltip-ca-protect' => 'ხასილაშ თხილუა',
'tooltip-ca-delete' => 'თე ხასილაშ ლასუა',
'tooltip-ca-move' => 'გეგნიღი თე ხასილა',
'tooltip-ca-watch' => 'თე ხასილაშ გეძინა თქვან კონტროლირებულ ხასილეფიშ ერკებულშა',
'tooltip-ca-unwatch' => 'მონწყით თე ხასილა თქვან კონტროლებულ ხასილეფიშ ერკებულშე',
'tooltip-search' => 'გორუა {{SITENAME}}',
'tooltip-search-go' => 'გეგნორთი წორას ათე ჯოხოშ ხასილაშა შურო ქო რენ-და',
'tooltip-search-fulltext' => 'დოგორი ხასილეფი, ნამუთ თე ტექსტის იკათუანან',
'tooltip-p-logo' => 'დუდხასჷლაშ ძირაფა',
'tooltip-n-mainpage' => 'დუდ ხასილაშ ძირაფა',
'tooltip-n-mainpage-description' => 'დუდ ხასილაშა გინოზოჯუა',
'tooltip-n-portal' => 'პროექტიშენი, მუშ ქიმინუა შეილებუნა, სოდე შილებე გორათინ',
'tooltip-n-currentevents' => 'დოგორით რსული ინფორმაცია ასეიან მოლინეფიშენ',
'tooltip-n-recentchanges' => 'ვიკიშა ეკონია ბორჯის მიშაღალირ თირაფეფიშ ერკებულ',
'tooltip-n-randompage' => 'ქუმოძირ ნამუდგარდასინ ხასილა',
'tooltip-n-help' => '"ხასილა, სოდეთ გარკვიენთინ',
'tooltip-t-whatlinkshere' => 'არძო ვიკი ხასილაშ ერკებულ, ნამუდგა თაქ იწურუანსინ',
'tooltip-t-recentchangeslinked' => 'თე ხასილაწკუმა გინორცხილი ხასილეფშა ასერდე მიშაღალირ თირაფეფი',
'tooltip-feed-rss' => 'მოჩამილი ხასილაშ RSS არხიშ ტრანსლაცია',
'tooltip-feed-atom' => 'ათე ხასილაშ ატომ არხიშ ტრანსლაცია',
'tooltip-t-contributions' => 'თე მახვარებუშ მიშაღალირ თიაშ ერკებულიშ ძირაფა',
'tooltip-t-emailuser' => 'მიდუჯღონით ელ.ფოშტა ათე მახვარებუს',
'tooltip-t-upload' => 'გეხარგე ფაილი',
'tooltip-t-specialpages' => 'არძო სპეციალურ ხასილაშ ერკებულ',
'tooltip-t-print' => 'თე ხასილაშ ობეშტალი ვერსია',
'tooltip-t-permalink' => 'პერმანენტულ რცხი ხასილაშ თე ვერსიაშა',
'tooltip-ca-nstab-main' => 'დინორეშ ხასილაშ ძირაფა',
'tooltip-ca-nstab-user' => 'მახვარებუშ ხასილაშ ძირაფა',
'tooltip-ca-nstab-special' => 'თქვა ასე რეთ სპეციალურ ხასილას, თქვა ვა შეილებუნა ათე ხასილაშ რედაქტირაფა',
'tooltip-ca-nstab-project' => 'პროექტიშ ხასილაშ ძირაფა',
'tooltip-ca-nstab-image' => 'ფაილიშ ხასილაშ ძირაფა',
'tooltip-ca-nstab-template' => 'თანგიშ ძირაფა',
'tooltip-ca-nstab-help' => 'ქოძირეთ დახვარებაშ გვერდ',
'tooltip-ca-nstab-category' => 'ხასილაშ კატეგორიაშ ძირაფა',
'tooltip-minoredit' => 'ქიმიოღანკი, მუჭოთ ჭიჭე რედაქტირაფა [alt-i]',
'tooltip-save' => 'თირაფეფიშ ჩუალა',
'tooltip-preview' => 'გეგნაჯინით თირაფეფს დო რთხინთ, თაშ ქოღოლათ სოიშახ თირაფეფს ჩუანდათინ! [alt-p]',
'tooltip-diff' => 'ტექსტიშა თქვან მიშაღალირ თირაფეფიშ ძირაფა [alt-v]',
'tooltip-compareselectedversions' => 'ქოძირით თე ხასილაშ ჟირ გიშაგორილ ვერსიაშ შხვანერობა',
'tooltip-watch' => 'თე ხასილაშ გეძინა თქვან ოკონტროლე ერკებულშა [alt-w]',
'tooltip-rollback' => '"დორთინა" ათე ხასილაშა ეკონია გინმახანდეშ ნაღოლემ თირაფას(ეფს) გოუქვენს ართ გენჭირათ',
'tooltip-undo' => '"გოუქვაფა" მიშაღალირ თირაფეფს გოუქვენს დო გუთმონწყუნს რედაქტირაფაშ ფორმას გიწოთოლორაფაშ რეჟიმს. თენა შესაძლებლობას ირძენს რეზიუმეს სამანჯელქ იყ’უას დაკონკრეტებულქინ.',
'tooltip-summary' => 'კუნტა რეზიუმეშ მიშაჸონაფა',

# Browsing diffs
'previousdiff' => '← წოხოლენი თირაფეფ',
'nextdiff' => 'უახალაშ თირაფა →',

# Media information
'file-info-size' => '$1 × $2 პიქსელ, ფაილიშ ზომა: $3, MIME ტიპ: $4',
'file-nohires' => ' უმოს მაღალ გიშაგორანჯალა ვა რე შელებუან.',
'svg-long-desc' => 'SVG ფაილ, ნომინალურო $1 × $2 პიქსელ, ფაილიშ ზიმა: $3',
'show-big-image' => 'რსული გიშაგორანჯალა',

# Special:NewFiles
'newimages' => 'ახალ სურათეფ',
'ilsubmit' => 'გორუა',

# Bad image list
'bad_image_list' => 'ფორმატ რე უკულიანიშნერო:\\n\\n ხვალე ერკებულშე გიშნაგორეფ (ლაწკარეფ, ნამუთ იჭყაფუ *-ით) ისხუნუ.
ლაწკარიშ პირველ რცხი ოკო რდას რცხი გლახა ფაილშა.
კინ თი ლაწკარს რინელ ნამდგაინ უკულიან რცხი კილესხუნუ მუჭოთ გიმნარკეში, ნამუთ ნიშნენს  ხასილეფს, სოდგა ფაილეფ შილებე რდას ღოზეფს შკას დინოხუნაფილ.',

# Metadata
'metadata' => 'მეტამოჩამილოფეფ',
'metadata-help' => 'თე ფაილს ოხოლუ გეძინელ ინფორმაცია, ნამუთ ოეგებიეთ თი ციფრულ კამერაშე ვარა სკანერშე რე გეძინელ, ნამუთ რდუ გუმორინაფილ თე ფაილიშ ოქიმინჯალო ვარა დაციფრებელო. ფაილიშ ორიგინალ თირელ ქორენ-და, შილებე კანკალე დეტალ ვა გიშაძირუანდას ფაილშა მიშაღალირ თირაფეფს.',
'metadata-expand' => 'დეტალეფიშ გოძინელ ძირაფა',
'metadata-collapse' => 'გოძინელ დეტალეფიშ ტყობინაფა',
'metadata-fields' => 'ათე მესიჯის შინაფილ მეტა მოჩამილოფეფიშ ოფირჩეფ ეკოროცხილ იჸი ნახანტიშ ხასილაშ დისფლეის მუჟამსით მეტა მოჩამილოფეფიშ ერკებულ იჸი გითოფაჩილინ 
შხვეფ, მუჭოთ წესინ, ტყობინაფილ იყ’ი.
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

# Exif tags
'exif-imagewidth' => 'სიგანე',
'exif-imagelength' => 'სიმაღალე',

# External editor support
'edit-externally' => 'თე ფაილიშ ორედაქტირაფალო გიმირინეთ გალენ პროგრამა',
'edit-externally-help' => '(უმოს ინფორმაციაშო ქოძირით [//www.mediawiki.org/wiki/Manual:External_editors])',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'არძა',
'namespacesall' => 'არძა',
'monthsall' => 'არძა',

# Watchlist editing tools
'watchlisttools-view' => 'მერცხილ თირაფეფიშ ძირაფა',
'watchlisttools-edit' => 'ოკონტროლებელ ხასილეფიშ ძირაფა დო რედაქტირაფა',
'watchlisttools-raw' => 'კონტროლიშ ერკებულიშ რედაქტირაფა ტექსტიშ ფორმატის',

# Core parser functions
'duplicate-defaultsort' => '\'\'გური ქუჩით:\'\'\' სტანდარტული დანწყუალაშ კილა "$2"-შო გინარჯგინანს ორდონი დონწყუალაშ კილა "$1"-ს.',

# Special:Version
'version' => 'ვერსია',

# Special:SpecialPages
'specialpages' => 'გჷშაკერძაფილი ხასჷლეფი',

# External image whitelist
'external_image_whitelist' => '"#ქჷდიტე თე ღოზი კოკობო მუჭო რენ თეში<pre>
#ქინახუნე რეგულარული გამოსახულებაშ ფრაგმენტეფი (თი ნაწილი ნამუთ თეშ // შქას ინოდოხოდ) თუდოლე
#თენეფი მეზჷმაფილ იჸე გალენ (hotlinked) სურათეფიშ URL-ეფშა.
#ნამუთ მიორენ თინა სურათეფო გაგშარჩქინდჷ, ვარ-და ხვალე სურათიშ რცხუ ირწყებედასიი.
#ღოზეფი #-თ დოჭყაფილი კომენტარო რე მერჩქინელი.
#თენა გჷნაფულენს ასოეფიშ რეგისტრის.

#ქინახუნე არძო regex ფრაგმენტეფი თე ღოზიშ ჟი. ქჷდიტე თე ღოზი კოკობო მუჭო რენ თეში</pre>"',

# Special:Tags
'tag-filter' => '[[Special:Tags|ხინტკეფიშ]] ტკიბირი:',

# Search suggestions
'searchsuggest-search' => 'გორუა',

);
