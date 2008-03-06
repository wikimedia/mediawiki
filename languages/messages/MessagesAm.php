<?php
/** Amharic (አማርኛ)
 *
 * @addtogroup Language
 *
 * @author Codex Sinaiticus
 * @author Siebrand
 * @author Nike
 * @author G - ג
 * @author SPQRobin
 * @author Teferra
 * @author לערי ריינהארט
 */



$messages = array(
# User preference toggles
'tog-underline'               => 'በመያያዣ ስር አስምር',
'tog-highlightbroken'         => 'የተሰበረ (ቀይ) መያያዣን <a href="" class="new">እንዲህ</a>? አለዚያ: እንዲህ<a href="" class="internal">?</a>)',
'tog-justify'                 => 'አንቀጾችን አስተካክል',
'tog-hideminor'               => 'አነስተኛ እርማቶችን ደብቅ',
'tog-extendwatchlist'         => 'የሚደረጉ ለውጦችን ለማሳየት መቆጣጠሪያ-ዝርዝርን ዘርጋ',
'tog-usenewrc'                => 'የተሻሻሉ የቅርብ ለውጦች (JavaScript)',
'tog-numberheadings'          => 'አርዕስቶችን በራስገዝ ቁጥር ስጥ',
'tog-showtoolbar'             => 'አርም ትዕዛዝ-ማስጫ ይታይ (JavaScript)',
'tog-editondblclick'          => 'ሁለቴ መጫን ገጹን ማረም ያስችል (JavaScript)',
'tog-editsection'             => 'በ[አርም] መያያዣ ክፍል ማረምን አስችል',
'tog-editsectiononrightclick' => 'የክፍል አርዕስት ላይ በቀኝ በመጫን ክፍል ማረምን አስችል (JavaScript)',
'tog-showtoc'                 => 'ከ3 አርዕስቶች በላይ ሲሆን የማውጫጫ ሰንጠረዥ ይታይ',
'tog-rememberpassword'        => 'መግባቴን እዚህ አስሊ ላይ አስታውስ',
'tog-editwidth'               => 'የማረሚያ ሳጥን ሙሉ ስፋት አለው',
'tog-watchcreations'          => 'እኔ የፈጠርኳቸውን ገጾች ወደምከታተላቸው ገጾች ዝርዝር ውስጥ ጨምር',
'tog-watchdefault'            => 'ያረምኳቸውን ገጾች ወደምከታተላቸው ገጾች ዝርዝር ውስጥ ጨምር',
'tog-watchmoves'              => 'ያዛወርኳቸውን ገጾች ወደምከታተላቸው ገጾች ዝርዝር ውስጥ ጨምር',
'tog-watchdeletion'           => 'የሰረዝኳቸውን ገጾች ወደምከታተላቸው ገጾች ዝርዝር ውስጥ ጨምር',
'tog-minordefault'            => 'ሁሉም እርማቶች በቀዳሚነት አነስተኛ ይባሉ',
'tog-previewontop'            => 'ከማረሚያው ገጽ በፊት ቅድመ-ማያ አሳይ',
'tog-previewonfirst'          => 'በመጀመሪያ እርማት ቅድመ-ዕይታ ይታይ',
'tog-nocache'                 => 'ገጽ መቆጠብን አታስችል',
'tog-enotifwatchlistpages'    => 'የምከታተለው ገጽ ሲቀየር ኤመልዕክት ይላክልኝ',
'tog-enotifusertalkpages'     => 'የተጠቃሚ መወያያ ገጼ ሲቀየር ኤመልዕክት ይላክልኝ',
'tog-enotifminoredits'        => 'ለአነስተኛ የገጽ እርማቶችም ኤመልዕክት ይላክልኝ',
'tog-enotifrevealaddr'        => 'ኤመልዕክት አድራሻዬን በማሳወቂያ መልዕክቶች ውስጥ አሳይ',
'tog-shownumberswatching'     => 'የሚከታተሉ ተጠቃሚዎችን ቁጥር አሳይ',
'tog-fancysig'                => 'ጥሬ ፊርማ (ያለራስገዝ ማያያዣ)',
'tog-externaleditor'          => 'በቀዳሚነት ውጪያዊ አራሚን ተጠቀም',
'tog-forceeditsummary'        => 'ማጠቃለያው ባዶ ከሆነ ማስታወሻ ይስጠኝ',
'tog-watchlisthideown'        => 'የራስዎ ለውጦች ከሚከታተሉት ገጾች ይደበቁ',
'tog-watchlisthidebots'       => 'የቦት (መሣርያ) ለውጦች ከሚከታተሉት ገጾች ይደበቁ',
'tog-watchlisthideminor'      => 'ጥቃቅን ለውጦች ከሚከታተሉት ገጾች ይደበቁ',
'tog-ccmeonemails'            => 'ወደ ሌላ ተጠቃሚ የምልከው ኢሜል ቅጂ ለኔም ይላክ',
'tog-diffonly'                => 'ከለውጦቹ ስር የገጽ ይዞታ አታሳይ',

'underline-always'  => 'ሁሌም ይህን',
'underline-never'   => 'ሁሌም አይሁን',
'underline-default' => 'የቃኝ ቀዳሚ ባህሪዎች',

'skinpreview' => '(ቅድመ-ዕይታ)',

# Dates
'sunday'        => 'እሑድ',
'monday'        => 'ሰኞ',
'tuesday'       => 'ማክሰኞ',
'wednesday'     => 'ረቡዕ',
'thursday'      => 'ሐሙስ',
'friday'        => 'ዓርብ',
'saturday'      => 'ቅዳሜ',
'sun'           => 'እሑድ',
'mon'           => 'ሰኞ',
'tue'           => 'ማክሰኞ',
'wed'           => 'ረቡዕ',
'thu'           => 'ሐሙስ',
'fri'           => 'ዓርብ',
'sat'           => 'ቅዳሜ',
'january'       => 'ጃንዩዌሪ',
'february'      => 'ፌብሩዌሪ',
'march'         => 'ማርች',
'april'         => 'ኤይፕርል',
'may_long'      => 'ሜይ',
'june'          => 'ጁን',
'july'          => 'ጁላይ',
'august'        => 'ኦገስት',
'september'     => 'ሰፕቴምበር',
'october'       => 'ኦክቶበር',
'november'      => 'ኖቬምበር',
'december'      => 'ዲሴምበር',
'january-gen'   => 'ጃንዩዌሪ',
'february-gen'  => 'ፌብሩዌሪ',
'march-gen'     => 'ማርች',
'april-gen'     => 'ኤይፕርል',
'may-gen'       => 'ሜይ',
'june-gen'      => 'ጁን',
'july-gen'      => 'ጁላይ',
'august-gen'    => 'ኦገስት',
'september-gen' => 'ሰፕቴምበር',
'october-gen'   => 'ኦክቶበር',
'november-gen'  => 'ኖቬምበር',
'december-gen'  => 'ዲሴምበር',
'jan'           => 'ጃንዩ.',
'feb'           => 'ፌብሩ.',
'mar'           => 'ማርች',
'apr'           => 'ኤፕሪ.',
'may'           => 'ሜይ',
'jun'           => 'ጁን',
'jul'           => 'ጁላይ',
'aug'           => 'ኦገስት',
'sep'           => 'ሴፕቴ.',
'oct'           => 'ኦክቶ.',
'nov'           => 'ኖቬም.',
'dec'           => 'ዲሴም.',

# Bits of text used by many pages
'categories'            => 'ምድቦች',
'pagecategories'        => '{{PLURAL:$1|ምድብ|ምድቦች}}',
'category_header'       => 'በምድብ «$1» ውስጥ የሚገኙ ገጾች',
'subcategories'         => 'ንዑስ-ምድቦች',
'category-media-header' => 'በመደቡ «$1» የተገኙ ፋይሎች፦',
'category-empty'        => 'ይህ መደብ አሁን ባዶ ነው።',

'about'          => 'ስለ',
'newwindow'      => '(ባዲስ መስኮት ውስጥ ይከፈታል።)',
'cancel'         => 'ሰርዝ',
'qbfind'         => 'አግኝ',
'qbbrowse'       => 'ቃኝ',
'qbedit'         => 'አርም',
'qbpageoptions'  => 'ይህ ገጽ',
'qbspecialpages' => 'ልዩ ገጾች',
'mytalk'         => 'የኔ ውይይት',
'navigation'     => 'መቃኘት',

'errorpagetitle'   => 'ስህተት',
'returnto'         => 'ወደ $1 ተመለስ',
'tagline'          => 'ከ{{SITENAME}}',
'help'             => 'መመሪያ',
'search'           => 'ፈልግ',
'searchbutton'     => 'ፈልግ',
'go'               => 'ሂድ',
'searcharticle'    => 'ሂድ',
'history'          => 'የገጽ ታሪክ',
'history_short'    => 'ታሪክ',
'updatedmarker'    => 'ከመጨረሻው ጉብኝቴ በኋላ የተሻሻለ',
'info_short'       => 'መረጃ',
'printableversion' => 'የህትመት ዝርያ',
'permalink'        => 'ቋሚ መያያዣ',
'edit'             => 'አርም',
'editthispage'     => 'ይህን ገጽ አርም',
'delete'           => 'ይጥፋ',
'deletethispage'   => 'ይህን ገጽ ሰርዝ',
'protect'          => 'ጠብቅ',
'protect_change'   => 'የመቆለፍ ደረጃ ለመቀይር',
'newpage'          => 'አዲስ ገጽ',
'talkpage'         => 'ስለዚሁ ገጽ ለመወያየት',
'talkpagelinktext' => 'ውይይት',
'specialpage'      => 'ልዩ ገጽ',
'personaltools'    => 'የኔ መሣርያዎች',
'talk'             => 'ውይይት',
'views'            => 'ዕይታዎች',
'toolbox'          => 'ትዕዛዝ ማስጫ',
'otherlanguages'   => 'በሌሎች ቋንቋዎች',
'redirectedfrom'   => '(ከ$1 የተዛወረ)',
'redirectpagesub'  => 'መምሪያ መንገድ',
'lastmodifiedat'   => 'ይህ ገጽ መጨረሻ የተቀየረው እ.ኣ.አ በ$2፣ $1 ዓ.ም. ነበር።', # $1 date, $2 time
'jumpto'           => 'ዘልለው ለመሐድ፦',
'jumptonavigation' => 'የማውጫ ቁልፎች',
'jumptosearch'     => 'ፍለጋ',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'         => 'ስለ {{SITENAME}} መርሃግብር',
'aboutpage'         => 'Project:ስለ',
'bugreports'        => 'የሶፍትዌሩን ችግር ዘግብ',
'bugreportspage'    => 'Project:የሶፍትዌር ችግሮች',
'copyright'         => '<br />ይዞታ በ$1 በሚለው ሕግ ሥር በነፃ የሚገኝ ነው።<br />',
'copyrightpage'     => '{{ns:project}}:የማብዛት መብት ደንብ',
'currentevents'     => 'ወቅታዊ ጉዳዮች',
'currentevents-url' => 'Project:ወቅታዊ ጉዳዮች',
'disclaimers'       => 'የኃላፊነት ማስታወቂያ',
'disclaimerpage'    => 'Project:አጠቃላይ የሕግ ነጥቦች',
'edithelp'          => 'የማረም መመሪያ',
'edithelppage'      => 'Help:የማዘጋጀት እርዳታ',
'faq'               => 'ብጊየጥ (ብዙ ጊዜ የሚጠየቁ ጥያቀዎች)',
'helppage'          => 'Help:ይዞታ',
'mainpage'          => 'ዋና ገጽ',
'portal'            => 'የኅብረተሠቡ መረዳጃ',
'portal-url'        => 'Project:የኅብረተሠብ መረዳጃ',
'privacy'           => 'የሚስጥር ፖሊሲ',
'privacypage'       => 'Project:የግልነት ድንጋጌ',
'sitesupport'       => 'መዋጮ ለመስጠት',
'sitesupport-url'   => 'Project:መዋጮ ስለ መስጠት',

'badaccess' => 'ያልተፈቀደ - አይቻልም',

'ok'                  => 'እሺ',
'retrievedfrom'       => 'ከ «$1» ተወሰደ',
'youhavenewmessages'  => '$1 አለዎት ($2)።',
'newmessageslink'     => 'አዲስ መልእክቶች',
'newmessagesdifflink' => 'የመጨረሻ ለውጥ',
'editsection'         => 'አርም',
'editold'             => 'አርም',
'editsectionhint'     => 'ክፍሉን «$1» ለማስተካከል',
'toc'                 => 'ማውጫ',
'showtoc'             => 'አሳይ',
'hidetoc'             => 'ደብቅ',
'thisisdeleted'       => '($1ን ለመመልከት ወይም ለመመለስ)',
'restorelink'         => '{{PLURAL:$1|የጠፋ ዕትም|$1 የጠፉት ዕትሞች}}',
'site-rss-feed'       => '$1 R.S.S. Feed',
'site-atom-feed'      => '$1 አቶም Feed',
'page-rss-feed'       => '"$1" R.S.S. Feed',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'ገጽ',
'nstab-user'      => 'የአባል ገጽ',
'nstab-special'   => 'ልዩ',
'nstab-project'   => 'የፕሮጀክት ገጽ',
'nstab-image'     => 'ፋይል',
'nstab-mediawiki' => 'መልዕክት',
'nstab-template'  => 'ሰም',
'nstab-help'      => 'የመመሪያ ገጽ',
'nstab-category'  => 'ምድብ',

# General errors
'badtitle'             => 'መጥፎ አርዕስት',
'badtitletext'         => 'የፈለጉት አርዕስት ልክ አልነበረም። ምናልባት ለአርዕስት የማይሆን የፊደል ምልክት አለበት።',
'perfcached'           => 'ማስታወቂያ፡ ይህ መረጃ በየጊዜ የሚታደስ ስለሆነ ዘመናዊ ሳይሆን የቆየ ሊሆን ይችላል።',
'perfcachedts'         => 'የሚቀጥለው መረጃ ተቆጥቧል፣ መጨረሻ የታደሠው $1 እ.ኤ.አ. ነው።',
'querypage-no-updates' => 'ይህ ገጽ አሁን የታደሠ አይደለም። ወደፊትም መታደሱ ቀርቷል። በቅርብ ግዜ አይታደስም።',
'viewsource'           => 'ምንጩን ተመልከት',
'viewsourcefor'        => 'ለ«$1»',
'protectedpagetext'    => 'ይኸው ገጽ እንዳይታረም ተጠብቋል።',
'viewsourcetext'       => 'የዚህን ገጽ ምንጭ ማየትና መቅዳት ይችላሉ።',
'protectedinterface'   => 'ይህ ገጽ ለስልቱ ገጽታ ጽሑፍን ያቀርባል፣፡ ስለዚህ እንዳይበላሽ ተጠብቋል።',
'cascadeprotected'     => "'''ማስጠንቀቂያ፦''' ይህ አርእስት ሊፈጠር ወይም ሊቀየር አይቻልም። ምክንያቱም ወደ ተከለከሉት አርእስቶች ተጨምሯል። <br />This page cannot be created or changed, because it is included in the following page that is under 'cascading protection': <br />$2",

# Login and logout pages
'logouttext'            => '<strong>አሁን ወጥተዋል።</strong><br /> አሁንም በቁጥር መታወቂያዎ ማዘጋጀት ይቻላል። ወይም ደግሞ እንደገና በብዕር ስምዎ መግባት ይችላሉ። 
---- 
በጥቂት ሴኮንድ ውስጥ ወደሚከተለው ገጽ በቀጥታ ይመለሳል፦',
'welcomecreation'       => '== ሰላምታ፣ $1! ==

የብዕር ስምዎ ተፈጥሯል። [[Special:Preferences|ምርጫዎችዎን]] ለማስተካከል ይችላሉ።',
'yourname'              => 'Username / የብዕር ስም:',
'yourpassword'          => 'Password / መግቢያ ቃል',
'yourpasswordagain'     => 'መግቢያ ቃልዎን ዳግመኛ ይስጡ',
'remembermypassword'    => '(መግቢያዎ እንዲታወስ ምልክት እዚህ ያድርጉ)',
'login'                 => 'ለመግባት',
'loginprompt'           => '(You must have cookies enabled to log in to {{SITENAME}}.)',
'userlogin'             => 'መግቢያ',
'logout'                => 'ከብዕር ስምዎ ለመውጣት',
'userlogout'            => 'መውጫ',
'nologin'               => 'የብዕር ስም ገና የለዎም? $1!',
'nologinlink'           => 'አዲስ የብዕር ስም ያውጡ',
'createaccount'         => 'አዲስ አባል ለመሆን',
'gotaccount'            => '(አባልነት አሁን ካለዎ፥ $1 ይግቡ)',
'gotaccountlink'        => 'በዚህ',
'youremail'             => 'ኢ-ሜል *',
'username'              => 'የብዕር ስም:',
'uid'                   => 'የገባበት ቁ.: #',
'yourrealname'          => 'ዕውነተኛ ስም፦',
'yourlanguage'          => 'የመልኩ ቋንቋ',
'yournick'              => 'ቁልምጫ ስም (ለፊርማ)',
'email'                 => 'ኢ-ሜል',
'prefs-help-realname'   => 'ዕውነተኛ ስምዎን መግለጽ አስፈላጊነት አይደለም። ለመግለጽ ከመረጡ ለሥራዎ ደራሲነቱን ለማስታወቅ ይጠቅማል።',
'prefs-help-email'      => 'ኢሜል አድራሻን ማቅረብዎ አስፈላጊ አይደለም። ቢያቅርቡት ሌሎች አባላት አድራሻውን ሳያውቁ በፕሮግራሙ አማካኝነት ሊገናኙዎት ተቻለ።',
'loginsuccesstitle'     => 'መግባትዎ ተከናወነ!',
'loginsuccess'          => 'እንደ «$1» ሆነው አሁን {{SITENAME}}ን ገብተዋል።',
'nosuchuser'            => '«$1» የሚል ብዕር ስም አልተገኘም። አጻጻፉን ይመልከቱ ወይም አዲስ ብዕር ስም ያውጡ።',
'nosuchusershort'       => '«<nowiki>$1</nowiki>» የሚል ብዕር ስም አልተገኘም። አጻጻፉን ይመልከቱ።',
'nouserspecified'       => 'አንድ ብዕር ስም መጠቆም ያስፈልጋል።',
'wrongpassword'         => 'የተሰጠው መግቢያ ቃል ልክ አልነበረም። ዳግመኛ ይሞክሩ።',
'wrongpasswordempty'    => 'ምንም መግቢያ ቃል አልተሰጠም። ዳግመኛ ይሞክሩ።',
'passwordtooshort'      => 'የመረጡት መግቢያ ቃል ልክ አይሆንም። ቢያንስ $1 ፊደላትና ከብዕር ስምዎ የተለየ መሆን አለበት።',
'mailmypassword'        => 'Mail me a new password / መግቢያ ቃሌን ረስቼ አዲስ በኔ email ይላክልኝ።',
'passwordremindertitle' => 'አዲስ ግዜያዊ መግቢያ ቃል (PASSWORD) ለ{{SITENAME}}',
'passwordremindertext'  => 'አንድ ሰው (ከቁጥር አድራሻ #$1 ሆኖ እርስዎ ይሆናሉ) አዲስ መግቢያ ቃል ለ{{SITENAME}} ጠይቋል ($4).
ለ«$2» ይሆነው መግቢያ ቃል አሁን «$3» ነው። አሁን በዚህ መግቢያ ቃል ገብተው ወደ አዲስ መግቢያ ቃል መቀየር ይሻሎታል።  

ይህ ጥያቄ የእርስዎ ካልሆነ፣ ወይም መግቢያ ቃልዎን ያስታወሱ እንደ ሆነ፣ ይህንን መልእክት ቸል ማለት ይችላሉ። የቆየው መግቢያ ቃል ከዚህ በኋላ ተግባራዊ ሆኖ ይቀጥላል።',
'noemail'               => 'ለብዕር ስም «$1» የተመዘገበ ኢ-ሜል የለም።',
'passwordsent'          => 'አዲስ መግቢያ ቃል ለ«$1» ወደ ተመዘገበው ኢ-ሜል ተልኳል። እባክዎ ከተቀበሉት በኋላ ዳግመኛ ይግቡ።',
'eauthentsent'          => 'የማረጋገጫ ኢ-ሜል ወዳቀረቡት አድራሻ ተልኳል። ያው አድራሻ በውነት የርስዎ እንደሆነ ለማረጋገጥ፣ እባክዎ በዚያ ደብዳቤ ውስጥ የተጻፈውን መያያዣ ይጫኑ። ከዚያ ቀጥሎ ኢ-ሜል ከሌሎች ተጠቃሚዎች መቀበል ይችላሉ።',
'emailauthenticated'    => 'የርስዎ ኢ-ሜል አድራሻ በ$1 ተረጋገጠ።',
'emailnotauthenticated' => 'ያቀረቡት አድራሻ ገና አልተረጋገጠምና ከሌሎች ተጠቃሚዎች ኢሜል መቀበል አይችሉም።',
'noemailprefs'          => '(በ{{SITENAME}} በኩል ኢሜል ለመቀበል፣ የራስዎን አድራሻ አስቀድመው ማቅረብ ያስፈልጋል።)',
'emailconfirmlink'      => 'አድራሻዎን ለማረጋገጥ',

# Edit page toolbar
'bold_sample'     => 'ጨለማ ጽሕፈት',
'bold_tip'        => 'ያመለከቱትን ቃላት በጨለማ ጽሕፈት ለማድረግ',
'italic_sample'   => 'ያንጋደደ ጽሕፈት',
'italic_tip'      => 'ያመለከቱትን ቃላት ባንጋደደ (ኢታሊክ) ለማድረግ',
'link_sample'     => 'የመያያዣ ስም',
'link_tip'        => 'ባመለከቱት ቃላት ላይ የዊኪ-ማያያዣ ለማድረግ',
'extlink_sample'  => 'http://www.lemisale.com የውጭ መያያዣ',
'extlink_tip'     => "የውጭ መያያዣ ለመፍጠር (በ'http://' የሚቀደም)",
'headline_sample' => 'ንዑስ ክፍል',
'headline_tip'    => 'የንዑስ-ክፍል አርዕስት ለመፍጠር',
'math_sample'     => 'የሒሳብ ቀመር በዚህ ይግባ',
'math_tip'        => 'የሒሳብ ቀመር (LaTeX) ለመጨመር',
'nowiki_sample'   => 'በዚህ ውስጥ የሚከተት ሁሉ የዊኪ-ሥርአተ ቋንቋን ቸል ይላል',
'nowiki_tip'      => 'የዊኪ-ሥርአተ ቋንቋን ቸል ለማድረግ',
'image_tip'       => 'የስዕል መያያዣ ለመፍጠር',
'media_tip'       => 'የድምጽ ፋይል መያያዣ ለመፍጠር',
'sig_tip'         => 'ፊርማዎ ከነሰዓቱ (4x ~)',
'hr_tip'          => "አድማሳዊ መስመር (በ'----') ለመፍጠር",

# Edit pages
'summary'                  => 'ማጠቃለያ',
'subject'                  => 'ጥቅል ርዕስ',
'minoredit'                => 'ይህ ለውጥ ጥቃቅን ነው።',
'watchthis'                => 'ይህንን ገጽ ለመከታተል',
'savearticle'              => 'ገጹን አስቀምጥ',
'preview'                  => 'ሙከራ / preview',
'showpreview'              => 'ቅድመ እይታ',
'showdiff'                 => 'ማነጻጸሪያ',
'anoneditwarning'          => "'''ማስታወቂያ:''' እርስዎ አሁን በአባል ስምዎ ያልገቡ ነዎት። ማዘጋጀት ይቻሎታል፤ ነገር ግን ለውጦችዎ በአባል ስም ሳይሆን በቁጥር አድራሻዎ ይመዘገባሉ። ከፈለጉ፥ በአባልነት [[Special:Userlogin|መግባት]] ይችላሉ።",
'missingsummary'           => "'''ማስታወሻ፦''' ማጠቃለያ ገና አላቀረቡም። እንደገና «ገጹን ለማቅረብ» ቢጫኑ፣ ያለ ማጠቃለያ ይላካል።",
'summary-preview'          => 'የማጠቃለያ ቅድመ እይታ',
'blockedtext'              => "<big>'''የርስዎ ብዕር ስም ወይም ቁጥር አድራሻ ከማዘጋጀት ተከለክሏል።'''</big>

በእርስዎ ላይ ማገጃ የጣለው መጋቢ $1 ነበረ። ምክንያቱም፦ ''$2''

* ማገጃ የጀመረበት ግዜ፦ $8
* ማገጃ የሚያልቅበት ግዜ፦ $6
* የታገደው ተጠቃሚ፦ $7

$1ን ወይም ማንም ሌላ [[{{MediaWiki:Grouppage-sysop}}|መጋቢ]] ስለ ማገጃ ለመጠይቅ ይችላሉ። ነገር ግን በ[[Special:Preferences|ምርጫዎችዎ]] ትክክለኛ ኢሜል ካልኖረ ከጥቅሙም ካልተከለከሉ በቀር ለሰው ኢሜል ለመላክ አይችሉም። የአሁኑኑ ቁጥር አድራሻዎ $3 ህኖ የማገጃው ቁጥር #$5 ነው። ምንም ጥያቄ ካለዎ ይህን ቁጥር ይጨምሩ።",
'newarticle'               => '(አዲስ)',
'newarticletext'           => 'የተከተሉት መያያዣ እስካሁን ወደሌለ ገጽ ነው ያመጣዎት። ገጹን ለመፍጠር ከታች በሚገኘው ሳጥን ውስጥ መተየብ ይጀምሩ። ለተጨማሪ መረጃ፣ [[{{MediaWiki:Helppage}}|መመሪያ ገጽን]] ይመልከቱ።

ወደዚህ በስሕተት ከሆነ የመጡት፣ የቃኝውን «Back» ቁልፍ ይጫኑ።',
'anontalkpagetext'         => "----''ይኸው ገጽ ገና ያልገባ ወይም ብዕር ስም የሌለው ተጠቃሚ ውይይት ገጽ ነው። መታወቂያው በ[[ቁጥር አድራሻ]] እንዲሆን ያስፈልጋል። አንዳንዴ ግን አንድ የቁጥር አድራሻ በሁለት ወይም በብዙ ተጠቃሚዎች የጋራ ሊሆን ይችላል። ስለዚህ ለርስዎ የማይገባ ውይይት እንዳይደርስልዎ፣ [[Special:Userlogin|«መግቢያ»]] በመጫን የብዕር ስም ለማውጣት ይችላሉ።''",
'noarticletext'            => '(በዚሁ ገጽ ላይ ምንም ጽሕፈት ገና የለም።)',
'previewnote'              => 'ማስታወቂያ፦ <strong><big>ይህ ለሙከራው ብቻ ነው የሚታየው -- ምንም ለውጦች ገና አልተላኩም!</big></strong>',
'session_fail_preview'     => '<strong>ይቅርታ! ገጹን ለማቅረብ ስንሂድ፣ አንድ ትንሽ ችግር በመረቡ መረጃ ውስጥ ድንገት ገብቶበታል። እባክዎ፣ እንደገና ገጹን ለማቅረብ አንዴ ይሞክሩ። ከዚያ ገና ካልሠራ፣ ምናልባት ከአባል ስምዎ መውጣትና እንደገና መግባት ይሞክሩ።</strong>',
'editing'                  => '«$1» ማዘጋጀት / ማስተካከል',
'editingsection'           => '«$1» (ክፍል) ማዘጋጀት / ማስተካከል',
'editingcomment'           => '$1 ማዘጋጀት (ውይይት መጨመር)',
'yourtext'                 => 'የእርስዎ እትም',
'editingold'               => '<strong><big>ማስጠንቀቂያ፦</big>

ይህ እትም የአሁኑ አይደለም፣ ከዚህ ሁናቴ ታድሷል።

ይህንን እንዳቀረቡ ከዚህ እትም በኋላ የተቀየረው ለውጥ ሁሉ ያልፋል።</strong>',
'copyrightwarning'         => "*<big> '''መጣጥፎችን ለመፍጠርና ለማሻሻል አይፈሩ''!''''' — </big>ሥራዎ ትክክለኛ ካልሆነ፣ በሌሎቹ አዘጋጆች ሊታረም ይችላል።",
'longpagewarning'          => '<strong>ማስጠንቀቂያ፦ የዚሁ ገጽ መጠን እስከ $1 kilobyte ድረስ ደርሷል፤ አንድ ጽሑፍ ከ32 kilobyte የበለጠ ሲሆን ይህ ግዙፍነት ለአንዳንድ ተጠቃሚ ዌብ-ብራውዘር ያስቸግራል። እባክዎን፣ ገጹን ወደ ተለያዩ ገጾች ማከፋፈልን ያስቡበት። </strong>',
'readonlywarning'          => ':<strong>ማስታወቂያ፦</strong> {{SITENAME}} አሁን ለአጭር ግዜ ተቆልፎ ገጹን ለማቅረብ አይቻልም። ጥቂት ደቂቃ ቆይተው እባክዎ እንደገና ይሞክሩት!
:(The database has been temporarily locked for maintenance, so you cannot save your edits at this time. You may wish to cut-&-paste the text into another file, and try again in a moment or two.)',
'semiprotectedpagewarning' => "'''ማስታወቂያ፦''' ይኸው ገጽ ከቋሚ አዛጋጆች በተቀር በማንም እንዳይለወጥ ተቆልፏል።",
'templatesused'            => 'በዚሁ ገጽ ላይ የሚገኙት መልጠፊያዎች እነዚህ ናቸው፦',
'templatesusedpreview'     => 'በዚሁ ቅድመ-እይታ የሚገኙት መልጠፊያዎች እነዚህ ናቸው፦',
'template-protected'       => '(የተቆለፈ)',
'template-semiprotected'   => '(በከፊል የተቆለፈ)',
'nocreatetext'             => '{{SITENAME}} አዳዲስ ገጾችን ለመፍጠር ያሚያስችል ሁኔታ ከለክሏል። ተመልሰው የቆየውን ገጽ ማዘጋጀት ይችላሉ፤ አለዚያ [[Special:Userlogin|በብዕር ስም መግባት]] ይችላሉ።',
'permissionserrorstext'    => 'ያ አድራጎት አይቻልም - ምክንያቱም፦',
'recreate-deleted-warn'    => ":<strong><big>'''ማስጠንቀቂያ፦ ይኸው አርእስት ከዚህ በፊት የጠፋ ገጽ ነው!'''</big></strong>

*እባክዎ፥ ገጹ እንደገና እንዲፈጠር የሚገባ መሆኑን ያረጋግጡ።

*የገጹ መጥፋት ዝርዝር ከዚህ ታች ይታያል።",

# "Undo" feature
'undo-success' => "ያ ለውጥ በቀጥታ ሊገለበጥ ይቻላል። እባክዎ ከታች ያለውን ማነጻጸርያ ተመልክተው ይህ እንደሚፈልጉ ያረጋግጡና ለውጡ እንዲገለበጥ '''ገጹን ለማቅረብ''' ይጫኑ።",
'undo-failure' => 'ከዚሁ ለውጥ በኋላ ቅራኔ ለውጦች ስለ ገቡ ሊገለበጥ አይቻልም።',
'undo-summary' => 'አንድ ለውጥ ከ[[Special:Contributions/$2|$2]] ([[User talk:$2|ውይይት]]) ገለበጠ',

# History pages
'viewpagelogs'        => 'መዝገቦች ለዚሁ ገጽ',
'currentrev'          => 'የአሁኑ እትም',
'revisionasof'        => 'እትም በ$1',
'revision-info'       => 'የ$1 ዕትም (ከ$2 ተዘጋጅቶ)',
'previousrevision'    => '← የፊተኛው እትም',
'nextrevision'        => 'የሚከተለው እትም →',
'currentrevisionlink' => '«የአሁኑን እትም ለመመልከት»',
'cur'                 => 'ከአሁን',
'next'                => 'ቀጥሎ',
'last'                => 'ካለፈው',
'page_first'          => 'ፊተኞች',
'page_last'           => 'ኋለኞች',
'histlegend'          => "ከ2 እትሞች መካከል ልዩነቶቹን ለመናበብ፦ በ2 ክብ ነገሮች ውስጥ ምልክት አድርገው «የተመረጡትን እትሞች ለማነፃፀር» የሚለውን ተጭነው የዛኔ በቀጥታ ይሄዳሉ።<br /> መግለጫ፦ (ከአሁን) - ከአሁኑ እትም ያለው ልዩነት፤ (ካለፈው) - ቀጥሎ ከቀደመው እትም ያለው ልዩነት፤<br /> «'''ጥ'''» ማለት ጥቃቅን ለውጥ ነው።",
'histfirst'           => 'ቀድመኞች',
'histlast'            => 'ኋለኞች',
'historysize'         => '($1 byte)',
'historyempty'        => '(ባዶ)',

# Revision feed
'history-feed-item-nocomment' => '$1 በ$2', # user at time

# Diffs
'history-title'           => 'የ«$1» እትሞች ታሪክ',
'difference'              => '(በ2ቱ እትሞቹ ዘንድ ያለው ልዩነት)',
'lineno'                  => 'መስመር፡ $1፦',
'compareselectedversions' => 'የተመረጡትን እትሞች ለማነፃፀር',
'editundo'                => 'ለውጡ ይገለበጥ',
'diff-multi'              => '(ከነዚህ 2 እትሞች መካከል {{plural:$1|አንድ ለውጥ ነበር|$1 ለውጦች ነበሩ}}።)',

# Search results
'searchresulttext' => 'በተጨማሪ ስለ ፍለጋዎች ለመረዳት፣ [[{{MediaWiki:Helppage}}]] ያንብቡ።',
'searchsubtitle'   => "'''ፍለጋ ለ[[:$1]]፦'''",
'noexactmatch'     => "በ«$1» አርዕስት የሚሰየም መጣጥፍ '''አልተገኘም'''፤ እርሶ ግን [[:$1|ሊፈጥሩት ይችላሉ]]... ።",
'prevn'            => 'ፊተኛ $1',
'nextn'            => 'ቀጥሎ $1',
'viewprevnext'     => 'በቁጥር ለማየት፡ ($1) ($2) ($3).',
'showingresults'   => 'ከ ቁ.#<b>$2</b> ጀምሮ እስከ <b>$1</b> ውጤቶች ድረስ ከዚህ በታች ይታያሉ።',
'powersearch'      => 'ፍለጋ',

# Preferences page
'preferences'           => 'ምርጫዎች፤',
'mypreferences'         => 'ምርጫዎች፤',
'prefs-edits'           => 'የለውጦች ቁጥር:',
'changepassword'        => 'መግቢያ ቃልዎን ለመቀየር',
'skin'                  => 'የድህረ-ገጽ መልክ',
'math'                  => 'የሂሳብ መልክ',
'dateformat'            => 'ያውሮፓ አቆጣጠር ዘመን ሥርዓት',
'datedefault'           => 'ግድ የለኝም',
'datetime'              => 'ዘመንና ሰዓት',
'prefs-personal'        => 'ያባል ዶሴ',
'prefs-rc'              => 'የቅርቡ ለውጦች ዝርዝር',
'prefs-watchlist'       => 'የሚከታተሉ ገጾች',
'prefs-watchlist-days'  => 'በሚከታተሉት ገጾች ዝርዝር ስንት ቀን ይታይ፤',
'prefs-watchlist-edits' => 'በተደረጁት ዝርዝር ስንት ለውጥ ይታይ፤',
'prefs-misc'            => 'ልዩ ልዩ ምርጫዎች',
'saveprefs'             => 'ይቆጠብ',
'resetprefs'            => 'ይታደስ',
'oldpassword'           => 'የአሁኑ መግቢያ ቃልዎ',
'newpassword'           => 'አዲስ መግቢያ ቃል',
'retypenew'             => 'አዲስ መግቢያ ቃል ዳግመኛ',
'textboxsize'           => 'የማዘጋጀት ምርጫዎች',
'rows'                  => 'በማዘጋጀቱ ሰንጠረዥ ስንት ተርታዎች?',
'columns'               => 'ስንት ዓምዶችስ?',
'searchresultshead'     => 'ፍለጋ',
'resultsperpage'        => 'ስንት ውጤቶች በየገጹ?',
'contextlines'          => 'ስንት መስመሮች በየውጤቱ?',
'contextchars'          => 'ስንት ፊደላት በየመስመሩ?',
'recentchangesdays'     => 'በቅርቡ ለውጦች ዝርዝር ስንት ቀን ይታይ?',
'recentchangescount'    => 'በዝርዝርዎ ላይ ስንት ለውጥ ይታይ? (እስከ 500)',
'savedprefs'            => 'ምርጫዎችህ ተቆጥበዋል።',
'timezonelegend'        => 'የሰዓት ክልል',
'timezonetext'          => 'ከ Server time (UTC) ያለው ልዩነት (በሰዓቶች ቁጥር) <br />(እንደ ኢትዮጵያ ጊዜ ለማድረግ እንደገና ስድስት ሰዓት ይጨምሩ።)',
'timezoneoffset'        => 'ኦፍ ሰት¹',
'guesstimezone'         => 'ከኮምፒውተርዎ መዝገብ ልዩነቱ ይገኝ',
'allowemail'            => 'ኢሜል ከሌሎች ተጠቃሚዎች ለመፍቀድ',
'defaultns'             => 'በመጀመርያው ፍለጋዎ በነዚህ ክፍለ-ዊኪዎች ብቻ ይደረግ:',
'files'                 => 'የስዕሎች መጠን',

# Groups
'group'       => 'ደረጃ፦',
'group-sysop' => 'መጋቢ',

'group-sysop-member' => 'መጋቢ',

'grouppage-sysop' => '{{ns:project}}:መጋቢዎች',

# User rights log
'rightslog'      => 'የአባል መብቶች መዝገብ',
'rightslogtext'  => 'ይህ መዝገብ የአባል መብቶች ሲለወጡ ይዘረዝራል።',
'rightslogentry' => 'የ$1 ማዕረግ ከ$2 ወደ $3 ለወጠ',

# Recent changes
'nchanges'                       => '$1 {{PLURAL:$1|ለውጥ|ለውጦች}}',
'recentchanges'                  => 'በቅርብ ጊዜ የተለወጡ',
'recentchangestext'              => "በዚሁ ገጽ ላይ በቅርብ ጊዜ የወጡ አዳዲስ ለውጦች ለመከታተል ይችላሉ። <br /> ('''ጥ'''፦ ጥቃቅን ለውጥ፤ '''አ'''፦ አዲስ ገጽ)",
'recentchanges-feed-description' => 'በዚህ ዊኪ ላይ በቅርብ ግዜ የተለወጠውን በዚሁ feed መከታተል ይችላሉ',
'rcnote'                         => 'ከ$3 እ.ኤ.አ. ባለፉት <strong>$2</strong>  ቀኖች የተደረጉት <strong>$1</strong> መጨረሻ ለውጦች እታች ይገኛሉ።

:<big>አ</big>ማራጮች፦',
'rcnotefrom'                     => 'ከ<b>$2</b> ጀምሮ የተቀየሩትን ገጾች (እስከ <b>$1</b> ድረስ) ክዚህ በታች ይታያሉ።',
'rclistfrom'                     => '(ከ $1 ጀምሮ አዲስ ለውጦቹን ለማየት)',
'rcshowhideminor'                => 'ጥቃቅን ለውጦች $1',
'rcshowhidebots'                 => 'bots $1',
'rcshowhideliu'                  => 'ያባላት ለውጦች $1',
'rcshowhideanons'                => 'የቁ. አድራሻ ለውጦች $1',
'rcshowhidepatr'                 => 'የተቆጣጠሩ ለውጦች $1',
'rcshowhidemine'                 => 'የኔ $1',
'rclinks'                        => 'ባለፉት $2 ቀን ውስጥ የወጡት መጨረሻ $1 ለውጦች ይታዩ።<br />($3)',
'diff'                           => 'ለውጡ',
'hist'                           => 'ታሪክ',
'hide'                           => 'ይደበቁ',
'show'                           => 'ይታዩ',
'minoreditletter'                => 'ጥ',
'newpageletter'                  => 'አ',
'boteditletter'                  => 'B',

# Recent changes linked
'recentchangeslinked'          => 'የተዛመዱ ለውጦች',
'recentchangeslinked-title'    => 'በ«$1» በተዛመዱ ገጾች ቅርብ ለውጦች',
'recentchangeslinked-noresult' => 'በተመለከተው ጊዜ ውስጥ ከዚህ በተያየዙት ገጾች ላይ ምንም ለውጥ አልነበረም።',
'recentchangeslinked-summary'  => "ከዚሁ ገጽ የተያየዙት ሌሎች ጽሑፎች ቅርብ ለውጦች ከታች ይዘረዝራሉ።

በሚከታተሉት ገጾች መካከል ያሉት ሁሉ በ'''ጨለማ ጽሕፈት''' ይታያሉ።",

# Upload
'upload'            => 'ፋይል / ሥዕል ለመላክ',
'uploadbtn'         => 'ፋይሉ ይላክ',
'uploadtext'        => "በዚህ ማመልከቻ ላይ ፋይል ለመላክ ይችላሉ። ቀድሞ የተላኩት ስዕሎች [[Special:Imagelist|በፋይል / ሥዕሎች ዝርዝር]] ናቸው፤ ከዚህ በላይ የሚጨመረው ፋይል ሁሉ [[Special:Log/upload|በፋይሎች መዝገብ]] ይዘረዝራሉ።

ስዕልዎ በጽሑፍ እንዲታይ '''<nowiki>[[</nowiki>{{ns:image}}<nowiki>:Filename.jpg]]</nowiki>''' ወይም
'''<nowiki>[[</nowiki>{{ns:image}}<nowiki>:Filename.png|thumb|ሌላ ጽሑፍ]]</nowiki>''' በሚመስል መልክ ይጠቅሙ።",
'upload-permitted'  => 'የተፈቀዱት የፋይል አይነቶች፦ $1 ብቻ ናቸው።',
'uploadlogpage'     => 'የፋይሎች መዝገብ (filelog)',
'uploadlogpagetext' => 'ይህ መዝገብ በቅርቡ የተላኩት ፋይሎች ሁሉ ያሳያል።',
'fileuploadsummary' => 'ማጠቃለያ፦',
'ignorewarnings'    => 'ማስጠንቀቂያ ቸል ይበል',
'uploadedimage'     => '«[[$1]]» ላከ',
'overwroteimage'    => 'የ«[[$1]]» አዲስ ዕትም ላከ',
'sourcefilename'    => 'የቆየው የፋይሉ ስም',
'destfilename'      => 'የፋይሉ አዲስ ስም',
'watchthisupload'   => 'ይህንን ገጽ ለመከታተል',

# Image list
'imagelist'                 => 'የፋይል / ሥዕሎች ዝርዝር',
'imagelisttext'             => '$1 የተጨመሩ ሥእሎች ወይም ፋይሎች ከታች ይዘረዝራሉ ($2)።',
'ilsubmit'                  => 'ፍለጋ',
'showlast'                  => 'ያለፉት $1 ፋይሎች $2 ተደርድረው ይታዩ።',
'byname'                    => 'በፊደል (ሀ-ፐ) ተራ',
'bydate'                    => 'በተጨመሩበት ወቅት',
'bysize'                    => 'በትልቅነት መጠን',
'imgdesc'                   => 'መግለጫ',
'imgfile'                   => 'ፋይሉ',
'filehist'                  => 'የፋይሉ ታሪክ',
'filehist-help'             => 'የቀድሞው ዕትም ካለ ቀን/ሰዓቱን በመጫን መመልከት ይቻላል።',
'filehist-deleteall'        => 'ሁሉን ለማጥፋት',
'filehist-deleteone'        => 'ይህን ለማጥፋት',
'filehist-revert'           => 'ወዲህ ይገለበጥ',
'filehist-current'          => 'ያሁኑኑ',
'filehist-datetime'         => 'ቀን /ሰዓት',
'filehist-user'             => 'አቅራቢው',
'filehist-dimensions'       => 'ክልሉ (በpixel)',
'filehist-filesize'         => 'መጠን',
'filehist-comment'          => 'ማጠቃለያ',
'imagelinks'                => 'መያያዣዎች',
'linkstoimage'              => 'የሚከተሉ ገጾች ወደዚሁ ፋይል ተያይዘዋል።',
'nolinkstoimage'            => 'ወዲህ ፋይል የተያያዘ ገጽ የለም።',
'sharedupload'              => 'ይህ ፋይል ከጋራ ምንጭ (Commons) የተቀሰመ ነው። በማንኛውም ዊኪ ላይ ሊጠቅም ይቻላል።',
'shareduploadwiki-desc'     => 'በዚያ በ$1 የሚታየው መግለጫ እንዲህ ይላል፦',
'shareduploadwiki-linktext' => 'ፋይል መግለጫ ገጹ',
'noimage'                   => 'በዚህ ስም የሚታወቅ ፋይል የለም፤ እርስዎ ግን $1 ይችላሉ።',
'noimage-linktext'          => 'ሊልኩት',
'uploadnewversion-linktext' => 'ለዚሁ ፋይል አዲስ ዕትም ለመላክ',
'imagelist_date'            => 'ቀን እ.ኤ.አ',
'imagelist_name'            => 'የፋይል ስም',
'imagelist_user'            => 'አቅራቢው',
'imagelist_size'            => 'መጠን (byte)',
'imagelist_description'     => 'ማጠቃለያ',

# File deletion
'filedelete'             => '$1 ለማጥፋት',
'filedelete-legend'      => 'ፋይልን ለማጥፋት',
'filedelete-intro'       => "'''[[Media:$1|$1]]''' ሊያጥፉ ነው።",
'filedelete-intro-old'   => '<span class="plainlinks">በ[$4 $3፣ $2] እ.ኤ.አ. የነበረው የ\'\'\'[[Media:$1|$1]]\'\'\' እትም ሊያጥፉ ነው።</span>',
'filedelete-comment'     => 'የማጥፋቱ ምክንያት፦',
'filedelete-submit'      => 'ይጥፋ',
'filedelete-otherreason' => 'ሌላ / ተጨማሪ ምክንያት፦',

# MIME search
'mimesearch' => 'የMIME ፍለጋ',

# List redirects
'listredirects' => 'መምሪያ መንገዶች ሁሉ',

# Unused templates
'unusedtemplates'     => 'ያልተለጠፉ መልጠፊያዎች',
'unusedtemplatestext' => 'እነኚህ መልጠፊያዎች አሁን ባንዳችም ገጽ ላይ አልተለጠፉም።',
'unusedtemplateswlh'  => 'ሌሎች መያያዣዎች',

# Random page
'randompage' => 'ማናቸውንም ለማየት',

# Random redirect
'randomredirect' => 'ማናቸውም መምሪያ መንገድ',

# Statistics
'statistics'    => 'የዚሁ ሥራ እቅድ ዝርዝር ቁጥሮች',
'sitestats'     => 'የዚህ {{SITENAME}} ዝርዝር ቁጥሮች (Statistics)',
'userstats'     => 'ያባላት ዝርዝር ቁጥሮች',
'sitestatstext' => "በጠቅላላው '''$1''' ገጾች በዚህ ሥራ ዕቅድ አሉ። ይኸኛው ድምር ቁጥር የሚጠቅልለው ውይይት ገጾች፣ ልዩ ገጾች፣ አጫጭር ፅሑፎች፣ መምሪያ ገጾች፣ እንዲሁም ሌሎች ይዞታ የሌለባቸው ገጾች ሁሉ ይሆናል። ከነዚህ ውጭ '''$2''' ይዞታ ያላቸው ተገቢ ፅሑፎች ይኖራሉ። 

ይህ ዊኪፔድያ ከተመሰረተ ጀምሮ '''$4''' ለውጦች ተደርገዋል። ስለዚህ ባማካኝ '''$5''' ለውጦች በየገጹ ይሆናል።",
'userstatstext' => "እስከ ዛሬ ድረስ '''$1''' አባላት ገብተዋል። ከዚህ ቁጥር መካከል፣ '''$2''' (ማለት '''$4%''') መጋቢዎች ናቸው። There are '''$1''' registered users, of whom '''$2''' (or '''$4%''') are administrators (see $3).",

'disambiguations'      => 'ወደ መንታ መንገድ የሚያያይዝ',
'disambiguations-text' => "የሚከተሉት ጽሑፎች ወደ '''መንታ መንገድ''' እየተያያዙ ነውና ብዙ ጊዜ እንዲህ ሳይሆን ወደሚገባው ርዕስ ቢወስዱ ይሻላል። <br />መንታ መንገድ ማለት የመንታ መልጠፊያ ([[MediaWiki:disambiguationspage]]) ሲኖርበት ነው።",

'doubleredirects'     => 'ድርብ መምሪያ መንገዶች',
'doubleredirectstext' => 'ይህ ድርብ መምሪያ መንገዶች ይዘርዘራል።

ድርብ መምሪያ መንገድ ካለ ወደ መጨረሻ መያያዣ እንዲሄድ ቢስተካከል ይሻላል።',

'brokenredirects'      => 'ሰባራ መምሪያ መንገዶች',
'brokenredirectstext'  => 'እነዚህ መምሪያ መንገዶች ወደማይኖር ጽሑፍ ይመራሉ።',
'brokenredirects-edit' => '(ለማስተካከል)',

'withoutinterwiki'        => 'በሌሎች ቋንቋዎች ያልተያያዙ',
'withoutinterwiki-header' => 'እነዚህ ጽሑፎች «በሌሎች ቋንቋዎች» ሥር ወደሆኑት ሌሎች ትርጉሞች ገና አልተያያዙም።',
'withoutinterwiki-submit' => 'ይታዩ',

'fewestrevisions' => 'ለውጦች ያነሱላቸው መጣጥፎች',

# Miscellaneous special pages
'nbytes'                  => '$1 byte',
'ncategories'             => '$1 {{PLURAL:$1|መደብ|መደቦች}}',
'nlinks'                  => '$1 መያያዣዎች',
'nmembers'                => '$1 {{PLURAL:$1|መጣጥፍ|መጣጥፎች}}',
'nrevisions'              => '$1 ለውጦች',
'specialpage-empty'       => '(ይህ ገጽ ባዶ ነው።)',
'lonelypages'             => 'ያልተያያዙ ፅሑፎች',
'lonelypagestext'         => 'የሚቀጥሉት ገጾች በ{{SITENAME}} ውስጥ ከሚገኙ ሌሎች ገጾች ጋር አልተያያዙም።',
'uncategorizedpages'      => 'ገና ያልተመደቡ ጽሑፎች',
'uncategorizedcategories' => 'ያልተመደቡ መደቦች (ንዑስ ያልሆኑ)',
'uncategorizedimages'     => 'ያልተመደቡ ፋይሎች',
'uncategorizedtemplates'  => 'ያልተመደቡ መልጠፊያዎች',
'unusedcategories'        => 'ባዶ መደቦች',
'unusedimages'            => 'ያልተያያዙ ፋይሎች',
'wantedcategories'        => 'ቀይ መያያዣዎች የበዙላቸው መደቦች',
'wantedpages'             => 'ቀይ መያያዣዎች የበዙላቸው አርእስቶች',
'mostlinked'              => 'መያያዣዎች የበዙላቸው ገጾች',
'mostlinkedcategories'    => 'መያያዣዎች የበዙላቸው መደቦች',
'mostlinkedtemplates'     => 'መያያዣዎች የበዙላቸው መልጠፊያዎች',
'mostcategories'          => 'መደቦች የበዙላቸው መጣጥፎች',
'mostimages'              => 'መያያዣዎች የበዙላቸው ስዕሎች',
'mostrevisions'           => 'ለውጦች የበዙላቸው መጣጥፎች',
'allpages'                => 'ገጾች ሁሉ በሙሉ',
'prefixindex'             => 'ገጾች በፊደል ለመፈልግ',
'shortpages'              => 'ጽሁፎች ካጭሩ ተደርድረው',
'longpages'               => 'ጽሁፎች ከረጅሙ ተደርድረው',
'deadendpages'            => 'መያያዣ የሌለባቸው ፅሑፎች',
'deadendpagestext'        => 'የሚቀጥሉት ገጾች በ{{SITENAME}} ውስጥ ከሚገኙ ሌሎች ገጾች ጋር አያያይዙም።',
'protectedpages'          => 'የተቆለፉ ገጾች',
'listusers'               => 'አባላት',
'specialpages'            => 'ልዩ ገጾች',
'spheading'               => 'ለሰው ሁሉ የሚጠቅሙ ልዩ ገጾች',
'newpages'                => 'አዳዲስ መጣጥፎች',
'newpages-username'       => 'በአቅራቢው፦',
'ancientpages'            => 'የቈዩ ፅሑፎች (በተለወጠበት ሰአት)',
'move'                    => 'ለማዛወር',
'movethispage'            => 'ይህንን ገጽ ለማዛወር',
'unusedimagestext'        => '<p>እነኚህ ፋይሎች ከ{{SITENAME}} አልተያያዙም። ሆኖም ሳያጥፏቸው ከ{{SITENAME}} ውጭ በቀጥታ ተያይዘው የሚገኙ ድረ-ገጾች መኖራቸው እንደሚቻል ይገንዝቡ።</p>',
'unusedcategoriestext'    => 'እነዚህ መደብ ገጾች ባዶ ናቸው። ምንም ጽሑፍ ወይም ግንኙነት የለባቸውም።',
'pager-newer-n'           => '{{PLURAL:$1|ኋለኛ 1|ኋለኛ $1}}',
'pager-older-n'           => '{{PLURAL:$1|ፊተኛ 1|ፊተኛ $1}}',

# Book sources
'booksources'               => 'የመጻሕፍት ቤቶችና ሸጪዎች',
'booksources-search-legend' => 'የመጽሐፍ ቦታ ፍለጋ',
'booksources-isbn'          => 'የመጽሐፉ ISBN #:',
'booksources-go'            => 'ይሂድ',

'categoriespagetext' => 'በዚሁ ሥራ ዕቅድ ውስጥ የሚከተሉ መደቦች ይኖራሉ።',
'isbn'               => 'በመጽሐፉ ISBN ቁጥር # ለመፈለግ',
'alphaindexline'     => '$1 እስከ $2 ድረስ',
'version'            => 'ዝርያ',

# Special:Log
'specialloguserlabel'  => 'ብዕር ስም፡',
'speciallogtitlelabel' => 'አርዕስት፡',
'log'                  => 'Logs / መዝገቦች',
'all-logs-page'        => 'All logs - መዝገቦች ሁሉ',
'alllogstext'          => 'ይኸው መዝገብ ሁሉንም ያጠቅልላል። 1) የፋይሎች መዝገብ 2) የማጥፋት መዝገብ 3) የመቆለፍ መዝገብ 4) የማገድ መዝገብ 5) የመጋቢ አድራጎት መዝገቦች በያይነቱ ናቸው።

ከሳጥኑ የተወሰነ መዝገብ አይነት መምረጥ ይችላሉ። ከዚያ ጭምር በብዕር ስም ወይም በገጽ ስም መፈለግ ይቻላል።',
'logempty'             => '(በመዝገቡ ምንም የለም...)',

# Special:Allpages
'nextpage'       => 'የሚቀጥለው ገጽ (ከ$1 ጀምሮ)',
'prevpage'       => 'ፊተኛው ገጽ (ከ$1 ጀምሮ)',
'allpagesfrom'   => 'ገጾች ከዚሁ ፊደል ጀምሮ ይታዩ፦',
'allarticles'    => 'የመጣጥፎች ማውጫ በሙሉ፣',
'allinnamespace' => 'ገጾች ሁሉ (ክፍለ-ዊኪ፡$1)',
'allpagessubmit' => 'ይታይ',
'allpagesprefix' => 'በዚሁ ፊደል የጀመሩት ገጾች:',

# Special:Listusers
'listusersfrom' => 'ከዚሁ ፊደል ጀምሮ፦',

# E-mail user
'emailuser'     => 'ለዚህ/ች ሰው ኢሜል መላክ',
'emailpage'     => 'ወደዚህ/ች አባል ኢ-ሜል ለመላክ',
'emailpagetext' => 'አባሉ በሳቸው «ምርጫዎች» ክፍል ተግባራዊ ኢ-ሜል አድራሻ ያስገቡ እንደሆነ፣ ከታች ያለው ማመልከቻ አንድን ደብዳቤ በቀጥታ ይልካቸዋል። 

ተቀባዩም መልስ በቀጥታ ሊሰጡዎ እንዲችሉ፣ በእርስዎ «ምርጫዎች» ክፍል ያስገቡት ኢ-ሜል አድራሻ በደብዳቤዎ «From:» መስመር ይታይላቸዋል።',
'noemailtitle'  => 'ኢ-ሜል አይቻልም',
'noemailtext'   => 'ለዚህ/ች አባል ኢ-ሜል መላክ አይቻልም። ወይም ተገቢ ኢ-ሜል አድራሻ የለንም፣ ወይም ከሰው ምንም ኢ-ሜል መቀበል አልወደደ/ችም።',
'emailfrom'     => 'ከ',
'emailto'       => 'ለ',
'emailsubject'  => 'ርዕሰ ጉዳይ',
'emailmessage'  => 'መልእክት',
'emailsend'     => 'ይላክ',
'emailccme'     => 'አንድ ቅጂ ደግሞ ለራስዎ ኢ-ሜል ይላክ።',

# Watchlist
'watchlist'            => 'የምከታተላቸው ገጾች፤',
'mywatchlist'          => 'የምከታተላቸው ገጾች፤',
'watchlistfor'         => "(ለ'''$1''')",
'nowatchlist'          => 'ዝርዝርዎ ባዶ ነው። ምንም ገጽ ገና አልተጨመረም።',
'addedwatch'           => 'ወደሚከታተሉት ገጾች ተጨመረ',
'addedwatchtext'       => "ገጹ «$1» [[Special:Watchlist|ለሚከታተሉት ገጾች]] ተጨምሯል። ወደፊት ይህ ገጽ ወይም የውይይቱ ገጽ ሲቀየር፣ በዚያ ዝርዝር ላይ ይታያል። በተጨማሪም [[Special:Recentchanges|«በቅርብ ጊዜ በተለወጡ» ገጾች]] ዝርዝር፣ በቀላሉ እንዲታይ በ'''ጨለማ ጽህፈት''' ተጽፎ ይገኛል።

በኋላ ጊዜ ገጹን ከሚከታተሉት ገጾች ለማስወግድ የፈለጉ እንደሆነ፣ በጫፉ ዳርቻ «አለመከታተል» የሚለውን ይጫኑ።",
'removedwatch'         => 'ከሚከታተሉት ገጾች ተወገደ',
'removedwatchtext'     => '«<nowiki>$1</nowiki>» የሚለው ከሚከታተሉት ገጾች ዝርዝር ጠፍቷል።',
'watch'                => 'ለመከታተል',
'watchthispage'        => 'ይህንን ገጽ ለመከታተል',
'unwatch'              => 'አለመከታተል',
'watchnochange'        => 'ከተካከሉት ገጾች አንዳችም በተወሰነው ጊዜ ውስጥ አልተለወጠም።',
'watchlist-details'    => 'አሁን በሙሉ {{PLURAL:$1|$1 ገጽ|$1 ገጾች}} እየተከታተሉ ነው።',
'watchlistcontains'    => 'አሁን በሙሉ $1 ገጾች እየተከታተሉ ነው።',
'wlnote'               => 'ባለፉት <b>$2</b> ሰዓቶች የተደረጉት $1 መጨረሻ ለውጦች እታች ይገኛሉ።',
'wlshowlast'           => 'ያለፉት $1 ሰዓት፤ $2 ቀን፤ $3 ይታዩ።',
'watchlist-show-bots'  => 'የቦት (BOT) ለውጦች ይታዩ',
'watchlist-hide-bots'  => 'የቦት (BOT) ለውጦች ይደበቁ',
'watchlist-show-own'   => 'የራሴ ለውጦች ይታዩ',
'watchlist-hide-own'   => 'የራሴ ለውጦች ይደበቁ',
'watchlist-show-minor' => "'ጥ' (ጥቃቅን) ለውጦች ይታዩ",
'watchlist-hide-minor' => "'ጥ' (ጥቃቅን) ለውጦች ይደበቁ",

# Displayed when you click the "watch" button and it's in the process of watching
'watching'   => 'እየተጨመረ ነው...',
'unwatching' => 'እየተወገደ ነው...',

# Delete/protect/revert
'deletepage'                  => 'ገጹ ይጥፋ',
'confirm'                     => 'ማረጋገጫ',
'excontent'                   => 'ይዞታ፦ «$1» አለ።',
'excontentauthor'             => "ይዞታ '$1' አለ (የጻፈበትም '$2' ብቻ ነበር)",
'exbeforeblank'               => 'ባዶ፤ ከተደመሰሰ በፊት ይዞታው «$1» አለ።',
'delete-confirm'              => '«$1» ለማጥፋት',
'delete-legend'               => 'ለማጥፋት',
'historywarning'              => 'ማስጠንቀቂያ፦ ለዚሁ ገጽ የዕትም ታሪክ ደግሞ ሊጠፋ ነው! :',
'confirmdeletetext'           => 'አንድ ገጽ ወይም ስዕል ከነለውጦቹ በሙሉ ከዚሁ {{SITENAME}} ሊጠፋ ነው! ይህን ማድረግዎ ያሠቡበት መሆኑንና ማጥፋቱ በፖሊሲ ተገቢ እንደሆነ እባክዎ ያረጋግጡ፦',
'actioncomplete'              => 'ተፈጽሟል',
'deletedtext'                 => '«<nowiki>$1</nowiki>» ጠፍቷል።

(የጠፉትን ገጾች ሁሉ ለመመልከት $2 ይዩ።)',
'deletedarticle'              => '«[[$1]]» አጠፋ',
'dellogpage'                  => 'የማጥፋት መዝገብ (del log)',
'dellogpagetext'              => 'በቅርቡ የጠፉት ገጾች ከዚህ ታች የዘረዝራሉ።',
'deletionlog'                 => 'የማጥፋት መዝገብ',
'deletecomment'               => 'የማጥፋቱ ምክንያት፦',
'deleteotherreason'           => 'ሌላ /ተጨማሪ ምክንያት',
'deletereasonotherlist'       => 'ሌላ ምክንያት',
'rollbacklink'                => 'ROLLBACK ይመለስ',
'revertpage'                  => 'የ$2ን ለውጦች ወደ $1 እትም መለሰ።', # Additional available: $3: revid of the revision reverted to, $4: timestamp of the revision reverted to, $5: revid of the revision reverted from, $6: timestamp of the revision reverted from
'rollback-success'            => 'የ$1 ለውጦች ተገለበጡ፣ ወደ $2 ዕትም ተመልሷል።',
'protectlogpage'              => 'የማቆለፍ መዝገብ (prot. log)',
'protectlogtext'              => 'ይህ መዝገብ ገጽ ሲቆለፍ ወይም ሲከፈት ይዘረዝራል። ለአሁኑ የተቆለፈውን ለመመልከት፣ [[Special:Protectedpages|የቆለፉትን ገጾች]] ደግሞ ያዩ።',
'protectedarticle'            => 'ገጹን «[[$1]]» ቆለፈው።',
'modifiedarticleprotection'   => 'የመቆለፍ ደረጃ ለ«[[$1]]» ቀየረ።',
'unprotectedarticle'          => 'ገጹን «[[$1]]» ፈታ።',
'protectsub'                  => '(ለ«$1» የመቆለፍ ደረጃ ለማስተካከል)',
'confirmprotect'              => 'የመቆለፍ ማረጋገጫ',
'protectcomment'              => 'ማጠቃለያ፦',
'protectexpiry'               => 'የሚያልቅበት ግዜ፦',
'protect_expiry_invalid'      => "የተሰጠው 'የሚያልቅበት ጊዜ' ልክ አይደለም።",
'protect_expiry_old'          => "የተሰጠው 'የሚያልቅበት ጊዜ' ባለፈው ግዜ ነበር።",
'protect-unchain'             => 'ገጹን የማዛወር ፈቃዶች ለመፍታት',
'protect-text'                => 'እዚህ ለገጹ «<strong><nowiki>$1</nowiki></strong>» የመቆለፍ ደረጃ መመልከት ወይም መቀይር ይችላሉ።',
'protect-locked-access'       => 'እርስዎ ገጽ የመቆለፍ ወይም የመፍታት ፈቃድ የለዎም።<br />አሁኑ የዚሁ ገጽ መቆለፍ ደረጃ እንዲህ ነው፦ <strong>$1</strong>:',
'protect-cascadeon'           => 'ይህ ገጽ ወደ ተከለከሉት አርእስቶች ተጨምሯል። የመቆለፍ ደረጃ እዚህ መቀየር ቢቻልዎም ገጹ ግን በሚከተለው ድርብ የተቆለፈ ገጽ ውስጥ ይጨመራል።',
'protect-default'             => '(እንደ ወትሮ)',
'protect-fallback'            => 'የ$1 ፈቃደ ለማስፈልግ',
'protect-level-autoconfirmed' => 'ባልገቡትና በአዲስ አባላት ብቻ',
'protect-level-sysop'         => 'መጋቢዎች ብቻ',
'protect-summary-cascade'     => 'በውስጡም ያለውን የሚያቆልፍ አይነት',
'protect-expiring'            => 'በ$1 (UTC) ያልቃል',
'protect-cascade'             => 'በዚህ ገጽ ውስጥ የተካተተው ገጽ ሁሉ ደግሞ ይቆለፍ?',
'protect-cantedit'            => 'ይህንን ገጽ የማዘጋጀት ፈቃድ ስለሌለልዎ መቆለፍ አይቻሎትም።',
'restriction-type'            => 'ፈቃድ፦',
'restriction-level'           => 'የመቆለፍ ደረጃ፦',
'minimum-size'                => 'ቢያንስ',
'maximum-size'                => 'ቢበዛ',
'pagesize'                    => 'byte መጠን ያለው ሁሉ',

# Restrictions (nouns)
'restriction-edit' => 'እንዲዘጋጅ፦',
'restriction-move' => 'እንዲዛወር፦',

# Restriction levels
'restriction-level-sysop'         => 'በሙሉ ተቆልፎ',
'restriction-level-autoconfirmed' => 'በከፊል ተቆልፎ',

# Undelete
'undelete'          => 'የተደለዘ ገጽ ለመመለስ',
'undeletepage'      => 'የተደለዘ ገጽ ለመመለስ',
'undeleteextrahelp' => "እትሞቹን በሙሉ ለመመልስ፣ ሳጥኖቹ ሁሉ ባዶ ሆነው ይቆዩና 'ይመለስ' የሚለውን ይጫኑ። <br/>አንዳንድ እትም ብቻ ለመመልስ፣ የተፈለገውን እትሞች በየሳጥኖቹ አመልክተው 'ይመለስ' ይጫኑ። <br/>'ባዶ ይደረግ' ቢጫን፣ ማጠቃልያውና ሳጥኖቹ ሁሉ እንደገና ባዶ ይሆናሉ።",
'undeletebtn'       => 'ይመለስ',
'undeletelink'      => 'ይመለስ',
'undeletereset'     => 'ባዶ ይደረግ',
'undeletecomment'   => 'ማጠቃልያ፦',

# Namespace form on various pages
'namespace'      => 'ዓይነት፦',
'invert'         => '(ምርጫውን ለመገልበጥ)',
'blanknamespace' => 'መጣጥፎች',

# Contributions
'contributions' => 'ያባል አስተዋጽኦች',
'mycontris'     => 'የኔ አስተዋጽኦች፤',
'contribsub2'   => 'ለ $1 ($2)',
'nocontribs'    => 'ምንም አልተገኘም።',
'uctop'         => '(ላይኛ)',
'month'         => 'እስከዚህ ወር ድረስ፦',
'year'          => 'እስከዚህ አመት (እ.ኤ.አ.) ድረስ፡-',

'sp-contributions-newbies'     => 'የአዳዲስ ተጠቃሚዎች አስተዋጽዖ ብቻ እዚህ ይታይ',
'sp-contributions-newbies-sub' => '(ለአዳዲስ ተጠቃሚዎች)',
'sp-contributions-blocklog'    => 'የማገጃ መዝገብ',
'sp-contributions-search'      => 'የሰውን አስተዋጽኦች ለመፈለግ፦',
'sp-contributions-username'    => 'ብዕር ስም ወይም የቁ. አድራሻ፦',
'sp-contributions-submit'      => 'ፍለጋ',

'sp-newimages-showfrom' => 'ከ$1 እኤአ ጀምሮ አዲስ ይታዩ',

# What links here
'whatlinkshere'       => 'ወዲህ የሚያያዝ',
'whatlinkshere-title' => 'ወደ «$1» የሚያያዙት ገጾች',
'whatlinkshere-page'  => 'ለገጽ (አርዕስት)፦',
'linklistsub'         => '(ወዲህ የሚያያዝ)',
'linkshere'           => 'የሚከተሉት ገጾች ወደዚሁ ተያይዘዋል።',
'nolinkshere'         => 'ወዲህ የተያያዘ ገጽ የለም።',
'nolinkshere-ns'      => 'ባመለከቱት ክፍለ-ዊኪ ወዲህ የተያያዘ ገጽ የለም።',
'isredirect'          => 'መምሪያ መንገድ',
'istemplate'          => 'የተሰካ',
'whatlinkshere-prev'  => 'ፊተኛ $1',
'whatlinkshere-next'  => 'ቀጥሎ $1',
'whatlinkshere-links' => '← ወዲህም የሚያያዝ',

# Block/unblock
'blockip'                  => 'ተጠቃሚውን ለማገድ',
'blockiptext'              => 'ከዚህ ታች ያለው ማመልከቻ በአንድ ቁጥር አድርሻ ወይም ብዕር ስም ላይ ማገጃ (ማዕቀብ) ለመጣል ይጠቀማል።  ይህ በ[[{{MediaWiki:Policy-url}}|መርመርያዎቻችን]] መሠረት ተንኮል ወይም ጉዳት ለመከልከል ብቻ እንዲደረግ ይገባል። ከዚህ ታች የተለየ ምክንያት (ለምሣሌ የተጎዳው ገጽ በማጠቆም) ይጻፉ።',
'ipadressorusername'       => 'የቁ. አድራሻ ወይም የብዕር ስም፦',
'ipbexpiry'                => 'የሚያልቅበት፦',
'ipbother'                 => 'ሌላ የተወሰነ ግዜ፦',
'ipboptions'               => '2 ሰዓቶች:2 hours,1 ቀን:1 day,3 ቀን:3 days,1 ሳምንት:1 week,2 ሳምንት:2 weeks,1 ወር:1 month,3 ወር:3 months,6 ወር:6 months,1 አመት:1 year,ዘላለም:infinite', # display1:time1,display2:time2,...
'ipbotheroption'           => 'ሌላ',
'ipblocklist'              => 'የአሁኑ ማገጃዎች ዝርዝር',
'ipblocklist-legend'       => 'አንድ የታገደውን ተጠቃሚ ለመፈለግ፦',
'ipblocklist-username'     => 'ይህ ብዕር ስም ወይም የቁጥር አድራሻ #፡',
'ipblocklist-submit'       => 'ይፈለግ',
'blocklistline'            => '$1 (እ.ኤ.አ.)፦ $2 በ$3 ላይ ማገጃ ጣለ ($4)',
'expiringblock'            => 'በ$1 እ.ኤ.አ. ያልቃል',
'anononlyblock'            => 'ያልገቡት የቁ.# ብቻ',
'createaccountblock'       => 'ስም ከማውጣት ተከለከለ',
'blocklink'                => 'ማገጃ',
'unblocklink'              => 'ማገጃ ለመንሣት',
'contribslink'             => 'አስተዋጽኦች',
'blocklogpage'             => 'የማገጃ መዝገብ (blocklog)',
'blocklogentry'            => 'እስከ $2 ድረስ [[$1]] አገዳ $3',
'blocklogtext'             => 'ይህ መዝገብ ተጠቃሚዎች መቸም ሲታገዱ ወይም ማገጃ ሲነሣ የሚዘረዝር ነው። ለአሁኑ የታገዱት ሰዎች [[Special:Ipblocklist|በአሁኑ ማገጃዎች ዝርዝር]] ይታያሉ።',
'unblocklogentry'          => 'የ$1 ማገጃ አነሣ',
'block-log-flags-anononly' => 'ያልገቡት የቁ. አድራሻዎች ብቻ',
'block-log-flags-nocreate' => 'አዲስ ብዕር ስም ከማውጣት ተከለከለ',

# Move page
'movepage'         => 'የሚዛወር ገጽ',
'movepagetext'     => "ከታች የሚገኘው ማመልከቻ ለገጹ ይዞታ አዲስ አርእስት ያወጣል። 
ከይዞታው ጋራ የእትሞች ታሪክ ደግሞ ወደ አዲሱ ገጽ ይዛወራል።
የቆየው አርእስት እንደ መምሪያ መንገድ ለአዲሱ ገጽ ይሆናል። 
ይህ ማለት ወደዚያ የሚያያዝ መያያዣ ሁሉ በቀጥታ ወደ አዲሱ ሥፍራ ይወስዳል።
ነገር ግን ገጹን እርስዎ ካዛወሩ፣ መያያዣዎቹ ድርብ ወይም ሰባራ እንዳይሆኑ ለማረጋገጥ ኃላፊነትዎ ነው።

ባዲሱ አርእስት ሥፍራ ሌላ ገጽ ቀድሞ ካለ፤ ሌላው ገጽ ታሪክ የሌለው፣ ባዶ ወይም መምሪያ መንገድ ካልሆነ በቀር፣ 
ይህ ገጽ ወደዚያ ለማዛወር '''የማይቻል''' ነው።  ስለዚህ ስሕተት ካደረጉ ወደ ቆየው አርእስት ገጹን መመለስ ይችላሉ፤ የኖረውን ገጽ በስሕተት ለመደምሰስ አይቻልም ማለት ነው።

'''ማስጠንቀቂያ፦''' 
በጣም ለተወደደ ወይም ብዙ ጊዜ ለሚነበብ ገጽ፣ እንዲህ ያለ ለውጥ በፍጹም ያልተጠበቀ ወይም ከባድ ውጤት ሊሆን ይችላል።  ስለዚህ እባክዎ የሚገባ መደምደሚያ መሆኑን አስቀድመው ያረጋግጡ።",
'movepagetalktext' => "አብዛኛው ጊዜ፣ ከዚሁ ገጽ ጋራ የሚገናኘው የውይይት ገጽ አንድላይ ይዛወራል፤ '''ነገር ግን፦'''

* ገጹን ወደማይመሳስል ክፍለ-ዊኪ (ለምሳሌ Mediawiki:) ቢያዛውሩት፤
* ባዶ ያልሆነ ውይይት ገጽ ቅድሞ ቢገኝ፤ ወይም
* እታች ከሚገኘውን ሳጥን ምልክቱን ካጠፉ፤
:
:ከነውይይቱ ገጽ አንድላይ አይዛወሩም። የዚያን ጊዜ የውይይቱን ገጽ ለማዛወር ከወደዱ በእጅ ማድረግ ያስፈልግዎታል።",
'movearticle'      => 'የቆየ አርእስት፡',
'newtitle'         => 'አዲሱ አርእስት',
'move-watch'       => 'ይህ ገጽ በተከታተሉት ገጾች ይጨመር',
'movepagebtn'      => 'ገጹ ይዛወር',
'pagemovedsub'     => 'መዛወሩ ተከናወነ',
'movepage-moved'   => "<big>'''«$1» ወደ «$2» ተዛውሯል'''</big>", # The two titles are passed in plain text as $3 and $4 to allow additional goodies in the message.
'articleexists'    => 'በዚያ አርዕሥት ሌላ ገጽ አሁን አለ። አለበለዚያ የመረጡት ስም ልክ አይደለም - ሌላ አርእስት ይምረጡ።',
'talkexists'       => "'''ገጹ ወደ አዲሱ አርዕስት ተዛወረ፤ እንጂ በአዲሱ አርዕስት የቆየ ውይይት ገጽ አስቀድሞ ስለ ኖረ የዚህ ውይይት ገጽ ሊዛወር አልተቻለም። እባክዎ፣ በእጅ ያጋጥሙአቸው።'''",
'movedto'          => 'የተዛወረ ወደ',
'movetalk'         => 'ከተቻለ፣ ከነውይይቱ ገጽ ጋራ ይዛወር',
'talkpagemoved'    => 'ተመሳሳዩ የውይይት ገጽ ደግሞ ተዛውሯል።',
'talkpagenotmoved' => 'ተመሳሳዩ የውይይት ገጽ ግን <strong>አልተዛወረም</strong>።',
'1movedto2'        => '«$1» ወደ «[[$2]]» አዛወረ',
'1movedto2_redir'  => '«$1» ወደ «[[$2]]» አዛወረ -- በመምሪያ መንገድ ፈንታ',
'movelogpage'      => 'የማዛወር መዝገብ (movelog)',
'movelogpagetext'  => 'ይህ መዝገብ ገጽ ሲዛወር ይመዝገባል። <ይመለስ> ቢጫኑ ኖሮ መዛወሩን ይገለብጣል!',
'movereason'       => 'ምክንያት',
'revertmove'       => 'ይመለስ',

# Export
'export' => 'ገጾች ወደ ሌላ ዊኪ ለመላክ',

# Namespace 8 related
'allmessages'        => 'የድረገጽ መልክ መልእክቶች',
'allmessagesname'    => 'የመልእክት ስም',
'allmessagesdefault' => 'የቆየው ጽሕፈት',
'allmessagescurrent' => 'ያሁኑ ጽሕፈት',
'allmessagestext'    => 'በ«MediaWiki» ክፍለ-ዊኪ ያሉት የድረገጽ መልክ መልእክቶች ሙሉ ዝርዝር ይህ ነው።',

# Thumbnails
'thumbnail-more'  => 'አጎላ',
'thumbnail_error' => 'ናሙና በመፍጠር ችግር አጋጠመ፦ $1',

# Import log
'importlogpage' => 'የገጽ ማስገባት መዝገብ',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'የርስዎ መኖርያ ገጽ',
'tooltip-pt-mytalk'               => 'የርስዎ መወያያ ገጽ',
'tooltip-pt-preferences'          => 'የድረግጹን መልክ ለመምረጥ',
'tooltip-pt-watchlist'            => 'እርስዎ ስለ ለውጦች የሚከታተሏቸው ገጾች',
'tooltip-pt-mycontris'            => 'እርስዎ ያደረጓቸው ለውጦች በሙሉ',
'tooltip-pt-login'                => 'በብዕር ስም መግባትዎ ጠቃሚ ቢሆንም አስፈላጊነት አይደለም',
'tooltip-pt-logout'               => 'ከብዕር ስምዎ ለመውጣት',
'tooltip-ca-talk'                 => 'ስለ ገጹ ለመወያየት',
'tooltip-ca-edit'                 => 'ይህን ገጽ ለማዘጋጀት ይችላሉ!',
'tooltip-ca-addsection'           => 'ለዚሁ ውይይት ገጽ አዲስ አርዕስት ለመጨምር',
'tooltip-ca-viewsource'           => 'ይህ ገጽ ተቆልፏል ~ ጥሬ ምንጩን መመልከት ይችላሉ...',
'tooltip-ca-history'              => 'ለዚሁ ገጽ ያለፉትን እትሞች ለማየት',
'tooltip-ca-protect'              => 'ይህንን ገጽ ለመቆለፍ',
'tooltip-ca-delete'               => 'ይህንን ገጽ ለማጥፋት',
'tooltip-ca-move'                 => 'ይህ ገጽ ወደ ሌላ አርእስት ለማዋወር',
'tooltip-ca-watch'                => 'ይህንን ገጽ ወደ ተከታተሉት ገጾች ዝርዝር ለመጨምር',
'tooltip-ca-unwatch'              => 'ይህንን ገጽ ከተከታተሉት ገጾች ዝርዝር ለማስወግድ',
'tooltip-search'                  => 'ቃል ወይም አርዕስት በ{{SITENAME}} ለመፈለግ',
'tooltip-n-mainpage'              => 'ወደ ዋናው ገጽ ለመሔድ',
'tooltip-n-portal'                => 'ስለ መርሃገብሩ አጠቃቀም አለመረዳት',
'tooltip-n-currentevents'         => 'ስለ ወቅታዊ ጉዳዮች / ዜና መረጃ ለማግኘት',
'tooltip-n-recentchanges'         => 'በዚሁ ዊኪ ላይ በቅርቡ የተደረጉ ለውጦች',
'tooltip-n-randompage'            => 'ወደ ማንኛውም ገጽ በነሲብ ለመሔድ',
'tooltip-n-help'                  => 'ረድኤት ለማግኘት',
'tooltip-n-sitesupport'           => 'የገንዘብ ስጦታ ለዊኪሜድያ ይስጡ',
'tooltip-t-whatlinkshere'         => 'ወደዚሁ ገጽ የሚያያዙት ገጾች ዝርዝር በሙሉ',
'tooltip-t-contributions'         => 'የዚሁ አባል ለውጦች ሁሉ ለመመልከት',
'tooltip-t-emailuser'             => 'ወደዚሁ አባል ኢ-ሜል ለመላክ',
'tooltip-t-upload'                => 'ፋይል ወይም ሥዕልን ወደ {{SITENAME}} ለመላክ',
'tooltip-t-specialpages'          => 'የልዩ ገጾች ዝርዝር በሙሉ',
'tooltip-ca-nstab-main'           => 'መጣጥፉን ለማየት',
'tooltip-ca-nstab-user'           => 'የአባል መኖሪያ ገጽ ለማየት',
'tooltip-ca-nstab-special'        => 'ይህ ልዩ ገጽ ነው - ሊያዘጋጁት አይችሉም',
'tooltip-ca-nstab-project'        => 'ግብራዊ ገጹን ለማየት',
'tooltip-ca-nstab-image'          => 'የፋይሉን ገጽ ለማየት',
'tooltip-ca-nstab-template'       => 'የመልጠፊያውን ገጽ ለመመልከት',
'tooltip-ca-nstab-help'           => 'የእርዳታ ገጽ ለማየት',
'tooltip-ca-nstab-category'       => 'የመደቡን ገጽ ለማየት',
'tooltip-minoredit'               => 'እንደ ጥቃቅን ለውጥ (ጥ) ለማመልከት',
'tooltip-save'                    => 'የለወጡትን ዕትም ወደ {{SITENAME}} ለመላክ',
'tooltip-preview'                 => 'ለውጦችዎ ሳይያቀርቡዋቸው እስቲ ይመለከቷቸው!',
'tooltip-diff'                    => 'እርስዎ የሚያደርጉት ለውጦች ከአሁኑ ዕትም ጋር ለማነጻጸር',
'tooltip-compareselectedversions' => 'ካመለከቱት ዕትሞች መካከል ያለውን ልዩነት ለማነጻጸር',
'tooltip-watch'                   => 'ይህንን ገጽ ወደተከታተሉት ገጾች ዝርዝር ለመጨምር',
'tooltip-upload'                  => 'ለመጀመር ይጫኑ',

# Spam protection
'subcategorycount'       => 'በዚሁ መደብ ውስጥ {{PLURAL:$1|አንድ ንዑስ-መደብ አለ|$1 ንዑስ-መደቦች አሉ}}።',
'categoryarticlecount'   => 'በዚሁ መደብ ውስጥ {{PLURAL:$1|አንድ መጣጥፍ አለ|$1 መጣጥፎች አሉ}}።',
'category-media-count'   => 'በዚሁ መደብ {{PLURAL:$1|አንድ ፋይል አለ|$1 ፋይሎች አሉ}}።',
'listingcontinuesabbrev' => '(ተቀጥሏል)',

# Patrolling
'markaspatrolledtext'   => 'ይህን ገጽ የተመለከተ ሆኖ ለማሳለፍ',
'markedaspatrolled'     => 'የተመለከተ ሆኖ ተሳለፈ',
'markedaspatrolledtext' => 'የተመረጠው ዕትም የተመለከተ ሆኖ ተሳለፈ።',

# Patrol log
'patrol-log-line' => 'እትም $1 ከ$2 የተመለከተ ሆኖ አሳለፈ',
'patrol-log-auto' => '(በቀጥታ)',

# Browsing diffs
'previousdiff' => '← የፊተኛው ለውጥ',
'nextdiff'     => 'የሚከተለው ለውጥ →',

# Media information
'file-info-size'       => '($1 × $2 ፒክስል፤ መጠን፦ $3፤ የMIME ዓይነት፦ $4)',
'file-nohires'         => '<small>ከዚህ በላይ ማጉላት አይቻልም።</small>',
'svg-long-desc'        => '(የSVG ፋይል፡ በተግባር $1 × $2 ፒክስል፤ መጠን፦ $3)',
'show-big-image'       => 'በሙሉ ጒልህነት ለመመልከት',
'show-big-image-thumb' => '<small>የዚህ ናሙና ቅጂ ክልል፦ $1 × $2 ፒክሰል</small>',

# Special:Newimages
'newimages'    => 'የአዳዲስ ሥዕሎች ማሳያ አዳራሽ',
'showhidebots' => '(«bots» $1)',
'noimages'     => 'ምንም የለም!',

# Bad image list
'bad_image_list' => 'ሥርዓቱ እንዲህ ነው፦

በ* የሚጀምሩ መስመሮች ብቻ ይቆጠራል። በመስመሩ መጀመርያው መያያዣ የመጥፎ ስዕል መያያዣ መሆን አለበት።  ከዚያ ቀጥሎ በዚያው በመስመር መያያዣ ቢገኝ ግን ስዕሉ እንደ ተፈቀደበት ገጽ ይቆጠራል።',

# Metadata
'metadata'          => 'ተጨማሪ መረጃ',
'metadata-help'     => 'ይህ ፋይል በውስጡ ተጨማሪ መረጃ ይይዛል። መረጃውም በዲጂታል ካሜራ ወይም በኮምፒውተር ስካነር የተጨመረ ይሆናል። ይህ ከኦሪጂናሉ ቅጅ የተለወጠ ከሆነ፣ ምናልባት የመረጃው ዝርዝር ለውጦቹን የማያንጸባረቅ ይሆናል።',
'metadata-expand'   => 'ተጨማሪ መረጃ ይታይ',
'metadata-collapse' => 'ተጨማሪ መረጃ ይደበቅ',
'metadata-fields'   => "በዚህ የሚዘረዘሩ EXIF መረጃ አይነቶች በፋይል ገጽ ላይ በቀጥታ ይታያሉ። ሌሎቹ 'ተጨማሪ መረጃ ይታይ' ካልተጫነ በቀር ይደበቃሉ።
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* focallength", # Do not translate list items

# EXIF tags
'exif-make'  => 'የካሜራው ሠሪ ድርጅት',
'exif-model' => 'የካሜራው ዝርያ',

# External editor support
'edit-externally'      => 'ይህንን ፋይል በአፍአዊ ሶፍትዌር ለማዘጋጀት',
'edit-externally-help' => 'ስለ አፍአዊ የስዕል ማዘጋጀት ሶፍትዌር በተጨማሪ ለመረዳት [http://meta.wikimedia.org/wiki/Help:External_editors የመመስረት ትዕዛዝ] ያንብቡ።',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'ሁሉ',
'watchlistall2'    => 'ሁሉ',
'namespacesall'    => 'ሁሉ (all)',
'monthsall'        => 'ሁሉ',

# E-mail address confirmation
'confirmemail'            => 'ኢ-ሜልዎን ለማረጋገጥ',
'confirmemail_text'       => 'አሁን በ{{SITENAME}} በኩል «ኢ-ሜል» ለመላክም ሆነ ለመቀበል አድራሻዎን ማረጋገጥ ግዴታ ሆኗል። እታች ያለውን በተጫኑ ጊዜ አንድ የማረጋገጫ መልእክት ቀድሞ ወደ ሰጡት ኢሜል አድራሻ በቀጥታ ይላካል። በዚህ መልእክት ልዩ ኮድ ያለበት መያያዣ ይገኝበታል፣ ይህንን መያያዣ ከዚያ ቢጎብኙ ኢ-ሜል አድራሻዎ የዛኔ ይረጋግጣል።',
'confirmemail_send'       => 'የማረጋገጫ ኮድ ወደኔ ኢ-ሜል ይላክልኝ',
'confirmemail_sent'       => 'የማረጋገጫ ኢ-ሜል ቅድም ወደ ሰጡት አድራሻ አሁን ተልኳል! (ሁለተኛ መጫን የለብዎትም፣ ወደ [[{{MediaWiki:Mainpage}}|ዋናው ገጽ]] ይመልሱ።)',
'confirmemail_sendfailed' => 'ወደሰጡት ኢሜል አድራሻ መላክ አልተቻለም። እባክዎ፣ ወደ [[Special:Preferences|«ምርጫዎች»]] ተመልሰው የጻፉትን አድራሻ ደንበኛነት ይመለከቱ።',
'confirmemail_invalid'    => 'ይህ ኮድ አልተከናወነም። (ምናልባት ጊዜው አልፏል።) እንደገና ይሞክሩ!',
'confirmemail_loggedin'   => 'የርስዎ ኢ-ሜል አድራሻ ተረጋግጧል። አሁን ኢ-ሜል በ{{SITENAME}} በኩል ለመላክ ወይም ለመቀበል ይችላሉ።',
'confirmemail_body'       => 'Someone from IP address $1 (probably you), has registered an
account with the user name "$2" with this e-mail address on {{SITENAME}}.

To confirm that this account really does belong to you, and to activate e-mail features on {{SITENAME}}, open this link in your browser:

$3

If for some reason this is *not* you, don\'t follow the link. This confirmation code will expire at $4.

Amharic text follows:

ጤና ይስጥልኝ

የርስዎ ኢ-ሜል አድራሻ በ$1 ለ{{SITENAME}} ብዕር ስም «$2» ቀርቧል። 

ይህ እርስዎ እንደ ሆኑ ለማረጋገጥና የ{{SITENAME}} ኢ-ሜል ጥቅም ለማግኘት፣ እባክዎን የሚከተለውን መያያዣ ይጎበኙ።

$3

ይህ ምናልባት እርስዎ ካልሆኑ፣ መያያዣውን አይከተሉ። 

የዚህ መያያዣው ኮድ እስከ $4 ድረስ ይሠራል።',

# Table pager
'table_pager_next'         => 'ቀጥሎ ገጽ',
'table_pager_prev'         => 'ፊተኛው ገጽ',
'table_pager_first'        => 'መጀመርያው ግጽ',
'table_pager_last'         => 'መጨረሻው ገጽ',
'table_pager_limit'        => 'በየገጹ $1 መስመሮች',
'table_pager_limit_submit' => 'ይታዩ',

# Auto-summaries
'autosumm-blank'   => 'ጽሑፉን በሙሉ ደመሰሰ።',
'autosumm-replace' => 'ጽሑፉ በ«$1» ተተካ።',
'autoredircomment' => 'ወደ [[$1]] መምሪያ መንገድ ፈጠረ',
'autosumm-new'     => 'አዲስ ገጽ ፈጠረ፦ «$1»',

# Watchlist editor
'watchlistedit-numitems'       => 'አሁን በሙሉ {{PLURAL:$1|$1 ገጽ|$1 ገጾች}} እየተከታተሉ ነው።',
'watchlistedit-noitems'        => 'ዝርዝርዎ ባዶ ነው።',
'watchlistedit-normal-title'   => 'ዝርዝሩን ለማስተካከል',
'watchlistedit-normal-legend'  => 'አርእስቶችን ከተካከሉት ገጾች ዝርዝር ለማስወግድ...',
'watchlistedit-normal-explain' => 'ከዚህ ታች፣ የሚከታተሉት ገጾች ሁሉ በሙሉ ተዘርዝረው ይገኛሉ። 

አንዳንድ ገጽ ከዚህ ዝርዝር ለማስወግድ ያሠቡ እንደሆነ፣ በሳጥኑ ውስጥ ምልክት አድርገው በስተግርጌ በሚገኘው «ማስወግጃ» የሚለውን ተጭነው ከዚህ ዝርዝር ሊያስወግዷቸው ይቻላል። (ይህን በማድረግዎ ከገጹ ጋር የሚገናኘው ውይይት ገጽ ድግሞ ከዝርዝርዎ ይጠፋል።)

ከዚህ ዘዴ ሌላ [[Special:Watchlist/raw|ጥሬውን ኮድ መቅዳት ወይም ማዘጋጀት]] ይቻላል። ወይም ደግሞ [[Special:Watchlist/clear|ዝርዝሩን በሙሉ ለማሟጠጥ]] ይቻላል።',
'watchlistedit-normal-submit'  => 'ማስወገጃ',
'watchlistedit-normal-done'    => 'ከዝርዝርዎ እነዚህ አርእስቶች ተወግደዋል፦',
'watchlistedit-raw-title'      => 'የዝርዝሩ ጥሬ ኮድ',
'watchlistedit-raw-legend'     => 'የዝርዝሩን ጥሬ ኮድ ለማዘጋጀት...',
'watchlistedit-raw-explain'    => 'በተከታተሉት ገጾች ዝርዝር ላይ ያሉት አርእስቶች ሁሉ ከዚህ ታች ይታያሉ። በየመስመሩ አንድ አርእስት እንደሚኖር፣ ይህን ዝርዝር ለማዘጋጀት ይችላሉ። አዘጋጅተውት ከጨረሱ በኋላ በስተግርጌ «ዝርዝሩን ለማሳደስ» የሚለውን ይጫኑ። አለበለዚያ ቢሻልዎት፣ የተለመደውን ዘዴ ([[Special:Watchlist/edit|«ዝርዝሩን ለማስተካከል»]]) ይጠቀሙ።',
'watchlistedit-raw-titles'     => 'የተከታተሉት አርእስቶች፦',
'watchlistedit-raw-submit'     => 'ዝርዝሩን ለማሳደስ',
'watchlistedit-raw-done'       => 'ዝርዝርዎ ታድሷል።',
'watchlistedit-raw-added'      => '$1 አርዕስት {{PLURAL:$1|ተጨመረ|ተጨመሩ}}፦',
'watchlistedit-raw-removed'    => '$1 አርዕስት {{PLURAL:$1|ተወገደ|ተወገዱ}}፦',

# Watchlist editing tools
'watchlisttools-view' => 'የምከታተላቸው ለውጦች',
'watchlisttools-edit' => 'ዝርዝሩን ለማስተካከል',
'watchlisttools-raw'  => 'የዝርዝሩ ጥሬ ኮድ',

);
