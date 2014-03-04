<?php
/** Tibetan (བོད་ཡིག)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Freeyak
 * @author Jason (on bo.wikipedia.org)
 * @author Phurbutsering
 * @author Shirayuki
 * @author YeshiTuhden
 */

$digitTransformTable = array(
	'0' => '༠', # &#x0f20;
	'1' => '༡', # &#x0f21;
	'2' => '༢', # &#x0f22;
	'3' => '༣', # &#x0f23;
	'4' => '༤', # &#x0f24;
	'5' => '༥', # &#x0f25;
	'6' => '༦', # &#x0f26;
	'7' => '༧', # &#x0f27;
	'8' => '༨', # &#x0f28;
	'9' => '༩', # &#x0f29;
);

$messages = array(
# User preference toggles
'tog-underline' => 'འོག་ཐིག་ཅན་ལ་སྦྲེལ་མཐུད།',
'tog-hideminor' => 'ཉེ་དུས་ཀྱི་རྩོམ་སྒྲིག་གལ་ཆུང་གྱི་འགྱུར་ལྡོག་རྣམས་སྦས།',
'tog-hidepatrolled' => 'ཉེ་དུས་ཀྱི་ལྟ་ཞིབ་བྱས་པའི་རྩོམ་སྒྲིག་རྣམས་སྦས།',
'tog-newpageshidepatrolled' => 'ཤོག་ངོས་གསར་བར་ལྟ་ཞིབ་བྱས་པའི་རྩོམ་སྒྲིག་ཀྱི་ཤོག་ངོས་སྦས།',
'tog-extendwatchlist' => 'ལྟ་ཞིབ་ཐོ་རྒྱ་སྐྱེད་ཏེ་ཉེ་ལམ་ཙམ་མིན་པར་བཟོ་བཅོས་ཡོངས་རྫོགས་སྟོན་ཅིག',
'tog-usenewrc' => 'ཡར་རྒྱས་ཅན་གྱི་ཉེ་བའི་བཟོ་བཅོས་བེད་སྤྱོད་པ།(Java ཡི་བརྡ་ཆད་དགོས)',
'tog-numberheadings' => 'རང་སྒྲིག་ཨང་རྟགས་འགོ་བརྗོད།',
'tog-showtoolbar' => 'རྩོམ་སྒྲིག་ལག་ཆ་སྟོན། (JavaScript ཡི་བརྡ་ཆད་དགོས།)',
'tog-editondblclick' => 'ཤོག་ངོས་རྩོམ་སྒྲིག་བྱེད་པར་ལན་གཉིས་རྡེབ།',
'tog-rememberpassword' => 'ངའི་ནང་འཛུལ་བཤར་ཆས་འདི་སྟེང་(མང་མཐར་ཉིན $1 དྲན་པར་མཛོད། )',
'tog-watchcreations' => 'ངའི་ལྟ་ཐོའི་གྲས་སུ་གསར་བཟོ་བྱས་པ་ལ་ཤོག་ངོས་ཁ་སྣོན།',
'tog-watchdefault' => 'ངའི་ལྟ་ཐོའི་གྲས་སུ་རྩོམ་སྒྲིག་བྱས་པ་ལ་ཤོག་ངོས་ཁ་སྣོན།',
'tog-watchmoves' => 'ངའི་ལྟ་ཐོའི་གྲས་སུ་སྤོར་བ་ལ་ཤོག་ངོས་ཁ་སྣོན།',
'tog-watchdeletion' => 'ངའི་ལྟ་ཐོའི་གྲས་སུ་དོར་བ་ལ་ཤོག་ངོས་ཁ་སྣོན།',
'tog-previewontop' => 'རྩོམ་སྒྲིག་སྒྲོམ་གྱི་གོང་སྔོན་ལྟའི་དཔེ་གཟུགས་སྟོན།',
'tog-previewonfirst' => 'ཐེངས་དང་པོའི་རྩོམ་སྒྲིག་སྟེང་དུ་སྔོན་ལྟའི་དཔེ་གཟུགས་སྟོན།',
'tog-enotifwatchlistpages' => 'ངའི་ལྟ་ཐོའི་ཤོག་ངོས་ལ་བཟོ་བཅོས་བྱུང་ཚེ་གློག་འཕྲིན་གཏང་རོགས།',
'tog-enotifusertalkpages' => 'ངའི་སྤྱོད་མིའི་གླེང་མོལ་ལ་བཟོ་བཅོས་བྱུང་ཚེ་གློག་འཕྲིན་གཏང་རོགས།',
'tog-enotifminoredits' => 'རྩོམ་སྒྲིག་ཆུང་ཚགས་རིགས་ལའང་གློག་འཕྲིན་གཏོང་རོགས།',
'tog-shownumberswatching' => 'ཤོག་ངོས་ལ་ལྟ་བཞིན་པའི་སྤྱོད་མིའི་ཁ་གྲངས་སྟོན།',
'tog-oldsig' => 'ད་ཡོད་མིང་རྟགས།',
'tog-watchlisthideown' => 'ངའི་རྩོམ་སྒྲིག་རྣམས་ལྟ་ཞིབ་ཐོ་ལས་སྦས་རོགས།',
'tog-watchlisthideminor' => 'རྩོམ་སྒྲིག་ཕལ་བ་རྣམས་ལྟ་ཞིབ་ཐོ་ལས་སྦས་རོགས།',
'tog-watchlisthideliu' => 'ཐོ་འཛུལ་སྤྱོད་མིའི་རྩོམ་སྒྲིག་རྣམས་ལྟ་ཐོ་ལས་སྦས་རོགས།',
'tog-ccmeonemails' => 'ངས་གཞན་ལ་བཏང་བའི་གློག་འཕྲིན་གྱི་འདྲ་བཤུས་སྐུར་རོགས།',
'tog-showhiddencats' => 'སྦས་བའི་དཀར་ཆག་སྟོན་རོགས།',

'underline-always' => 'དུས་རྒྱུན་དུ་',
'underline-never' => 'གཏན་ནས་མ་བྱེད།',
'underline-default' => 'རྒྱས་བ་འདྲེན་པ།',

# Font style option in Special:Preferences
'editfont-style' => 'རྩོམ་སྒྲིག་ཡིག་གཟུགས།',
'editfont-default' => 'རྒྱས་པ་འདྲེན་པ།',
'editfont-monospace' => 'བར་ཚད་མཉམ་པའི་ཡིག་གཟུགས།',
'editfont-sansserif' => 'ཡིག་གཟུགས་རྭ་མེད།',
'editfont-serif' => 'ཡིག་གཟུགས་རྭ་ཅན།',

# Dates
'sunday' => 'གཟའ་ཉི་མ།',
'monday' => 'གཟའ་ཟླ་བ།',
'tuesday' => 'གཟའ་མིག་དམར།',
'wednesday' => 'གཟའ་ལྷག་པ།',
'thursday' => 'གཟའ་ཕུར་བུ།',
'friday' => 'གཟའ་པ་སངས།',
'saturday' => 'གཟའ་སྤེན་པ།',
'sun' => 'གཟའ་ཉི་མ།',
'mon' => 'གཟའ་ཟླ་བ།',
'tue' => 'གཟའ་མིག་དམར།',
'wed' => 'གཟའ་ལྷག་པ།',
'thu' => 'གཟའ་ཕུར་བུ།',
'fri' => 'གཟའ་པ་སངས།',
'sat' => 'གཟའ་སྤེན་པ།',
'january' => 'ཟླ་བ་དང་པོ།',
'february' => 'ཟླ་བ་གཉིས་པ།',
'march' => 'ཟླ་བ་གསུམ་པ།',
'april' => 'ཟླ་བ་བཞི་བ།',
'may_long' => 'ཟྮ་བ་ལྔ་བ།',
'june' => 'ཟླ་བ་དྲུག་པ།',
'july' => 'ཟླ་བ་བདུན་པ།',
'august' => 'ཟླ་བ་བརྒྱད་པ།',
'september' => 'ཟླ་བ་དགུ་བ།',
'october' => 'ཟླ་བ་བཅུ་བ།',
'november' => 'ཟླ་བ་བཅུ་གཅིག་པ།',
'december' => 'ཟླ་བ་བཅུ་གཉིས་པ།',
'january-gen' => 'ཟླ་དང་པོ།',
'february-gen' => 'ཟླ་གཉིས་པ།',
'march-gen' => 'ཟླ་གསུམ་པ།',
'april-gen' => 'ཟླ་བཞི་བ།',
'may-gen' => 'ཟྮ་ལྔ་བ།',
'june-gen' => 'ཟླ་དྲུག་པ།',
'july-gen' => 'ཟླ་བདུན་པ།',
'august-gen' => 'ཟླ་བརྒྱད་པ།',
'september-gen' => 'ཟླ་དགུ་བ།',
'october-gen' => 'ཟླ་བཅུ་བ།',
'november-gen' => 'ཟླ་བཅུ་གཅིག་པ།',
'december-gen' => 'ཟླ་བཅུ་གཉིས་པ།',
'jan' => 'ཟླ་བ་དང་པོ།',
'feb' => 'ཟླ་བ་གཉིས་པ།',
'mar' => 'ཟླ་བ་གསུམ་པ།',
'apr' => 'ཟླ་བཞི་བ།',
'may' => 'ཟླ་ལྔ་བ།',
'jun' => 'ཟླ་དྲུག་པ།',
'jul' => 'ཟླ་བདུན་པ།',
'aug' => 'ཟླ་བརྒྱད་པ།',
'sep' => 'ཟླ་དགུ་བ།',
'oct' => 'ཟླ་བཅུ་བ།',
'nov' => 'ཟླ་བཅུ་གཅིག་པ།',
'dec' => 'ཟླ་བཅུ་གཉིས་པ།',

# Categories related messages
'pagecategories' => '{{PLURAL:|སྡེ་ཚན་|སྡེ་ཚན་ $1}}',
'category_header' => '"$1"ནང་་གི་ཤོག་ངོས།',
'subcategories' => 'རིགས་གཏོགས།',
'category-media-header' => '"$1"ནང་་གི་ཆ་འཕྲིན།',
'category-empty' => '<em> སྡེ་ཚན་འདིའི་ནང་དུ་བར་སྐབས་སུ་ཤོག་ངོས་སམ་བརྙན་རིས་མི་འདུག། </em>',
'hidden-categories' => '|སྦས་བའི་སྡེ་ཚན།|སྦས་བའི་སྡེ་ཚན།}}{{PLURAL:$1',
'hidden-category-category' => 'སྦས་བའི་སྡེ་ཚན།',
'category-subcat-count-limited' => 'སྡེ་ཚན་འདིར་གཤམ་གྱི་བྱེ་བྲག་སྡེ་ཚན་{{PLURAL:$1|subcategory|$1 subcategories}}ཡོད།',
'category-article-count' => '{{PLURAL:$2|སྡེ་ཚན་འདིར་གཤམ་གྱི་ཤོག་ངོས་ཁོ་ན་བསྡུས་ཡོད། |The following {{PLURAL:$1|page is|$1 pages are}} in this category, out of $2 total.}}',

'about' => 'སྐོར།',
'article' => 'ནང་དོན་ཤོག་ངོས།',
'newwindow' => '(སྒེའུ་ཁུང་གསར་བར་ཕྱེ་བ།)',
'cancel' => 'རྩིས་མེད།',
'moredotdotdot' => 'དེ་ལས་མང་བ་་་',
'mypage' => 'ངའི་ཤོག་ངོས།',
'mytalk' => 'གཏམ་གླེང།',
'anontalk' => 'IP གནས་ཡུལ་འདི་ལ་གླེང་མོལ།',
'navigation' => 'ཕྱོགས་ཁྲིད།',
'and' => '&#32;དང་',

# Cologne Blue skin
'qbfind' => 'འཚོལ་བ།',
'qbedit' => 'རྩོམ་སྒྲིག',
'qbpageoptions' => 'ཤོག་ངོས་འདི།',
'qbmyoptions' => 'ངའི་ཤོག་ངོས།',
'faq' => 'རྒྱུན་ལྡན་དྲི་བ།',
'faqpage' => 'Project: རྒྱུན་ལྡན་དྲི་བ།',

# Vector skin
'vector-action-addsection' => 'བརྗོད་གཞི་ཁ་སྣོན།',
'vector-action-delete' => 'སུབས།',
'vector-action-move' => 'སྤོར་བ།',
'vector-action-protect' => 'འགོག་སྲུང།',
'vector-action-undelete' => 'བསུབས་པ་གསོ་བ།',
'vector-action-unprotect' => 'སྲུང་སྐྱོབ་གློད་པ།',
'vector-view-create' => 'གསར་བཟོ།',
'vector-view-edit' => 'རྩོམ་སྒྲིག',
'vector-view-history' => 'ལོ་རྒྱུས་ལ་ལྟ་བ།',
'vector-view-view' => 'ཀློག་པ།',
'vector-view-viewsource' => 'ཁུངས་ལ་ལྟ་བ།',
'actions' => 'བྱ་སྤྱོད།',
'namespaces' => 'མིང་གནས།',
'variants' => 'འགྱུར་ཚད།',

'errorpagetitle' => 'ནོར་འཁྲུལ།',
'returnto' => '$1 ལ་བསྐྱར་ལོག་བྱེད་པ།',
'tagline' => 'ཡོང་ཁུངས་{{SITENAME}}',
'help' => 'ཕན་གྲོགས།',
'search' => 'འཚོལ་བ།',
'searchbutton' => 'འཚོལ།',
'go' => 'སོང་།',
'searcharticle' => 'འཚོལ།',
'history' => 'ཤོག་ངོས་ལོ་རྒྱུས།',
'history_short' => 'ལོ་རྒྱུས།',
'updatedmarker' => 'ཐེངས་སྔོན་མའི་ལྟ་ཀློག་རྗེས་ཀྱི་བཟོ་བཅོས།',
'printableversion' => 'པར་འདེབས་རུང་བའི་འགྱུར།',
'permalink' => 'བརྟན་འཇགས་ཀྱི་སྦྲེལ་མཐུད།',
'print' => 'དཔར་བ།',
'view' => 'ལྟ་བ།',
'edit' => 'རྩོམ་སྒྲིག',
'create' => 'གསར་སྐྲུན།',
'editthispage' => 'ངོས་འདི་བཟོ་བཅོས་བྱེད་པ།',
'create-this-page' => 'ཤོག་ངོས་འདི་སྐྲུན་པ།',
'delete' => 'སུབས།',
'deletethispage' => 'ཤོག་ངོས་འདི་འདོར་བ།',
'undelete_short' => '{{PLURAL:$1|one edit|$1edits}} མ་འདོར་ཞིག',
'viewdeleted_short' => '{{བསུབས་པའི་རྩོམ་སྒྲིག PLURAL:$1|བསུབས་པའི་རྩོམ་སྒྲིག $1}}ལ་ལྟ་བ།',
'protect' => 'འགོག་སྲུང།',
'protect_change' => 'སྒྱུར་བཅོས།',
'protectthispage' => 'ཤོག་ངོས་འདི་སྲུང་བ།',
'unprotect' => 'སྲུང་སྐྱོབ་བཅོས་བསྒྱུར།',
'unprotectthispage' => 'ངོ་ཤོག་འདིའི་སྲུང་སྐྱོབ་བཅོས་བསྒྱུར།',
'newpage' => 'ཤོག་ངོས་གསར་བ།',
'talkpage' => 'ཤོག་ངོས་འདིར་གྲོས་སྡུར།',
'talkpagelinktext' => 'གཏམ་གླེང།',
'specialpage' => 'དམིགས་གསལ་ཤོག་ངོས།',
'personaltools' => 'སྒེར་ཀྱི་ལག་ཆ།',
'postcomment' => 'སྡེ་ཚན་གསར་བ།',
'articlepage' => 'ནང་དོན་ཤོག་ངོས་ལ་ལྟ་བ།',
'talk' => 'གྲོས་བསྡུར།',
'views' => 'ལྟ་ཞིབ།',
'toolbox' => 'ལག་ཆའི་སྒམ།',
'userpage' => 'སྤྱོད་མིའི་ཤོག་ངོས་ལ་ལྟ་བ།',
'projectpage' => 'ལས་འཆར་ཤོག་ངོས་ལ་ལྟ་བ།',
'imagepage' => 'ཡིག་ཆའི་ཤོག་ངོས་ལ་ལྟ་བ།',
'mediawikipage' => 'འཕྲིན་ཐུང་ཤོག་ངོས་ལ་ལྟ་བ།',
'templatepage' => 'དཔེ་པང་ཤོག་ངོས་ལ་ལྟ་བ།',
'viewhelppage' => 'རོགས་རམ་ཤོག་ངོས་ལ་ལྟ་བ།',
'categorypage' => 'སྡེ་ཚན་ཤོག་ངོས་སྟོན་ཅིག',
'viewtalkpage' => 'གྲོས་མོལ་ལ་ལྟ་བ།',
'otherlanguages' => 'སྐད་རིགས་གཞན་ཐོག།',
'redirectedfrom' => '$1 ནས་ཁ་ཕྱོགས་བསྐྱར་དུ་བཟོས་པ།',
'redirectpagesub' => 'རིམ་འགྲེམ་ཤོག་ངོས།',
'lastmodifiedat' => 'ཤོག་ངོས་འདི་ཡི་བཟོ་བཅོས་མཐའ་མ་$1 ཀྱི་ $2 ལ་རེད།',
'protectedpage' => 'སྲུང་སྐྱོབ་བྱས་པའི་ཤོག་ངོས།',
'jumpto' => 'གནས་སྤོ།',
'jumptonavigation' => 'ཕྱོགས་ཁྲིད།',
'jumptosearch' => 'འཚོལ།',
'pool-errorunknown' => 'ངོས་མ་ཟིན་པའི་ནོར་འཁྲུལ།',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage).
'aboutsite' => '{{SITENAME}}ཡི་སྐོར།',
'aboutpage' => 'Project:སྐོར།',
'copyright' => 'དྲ་བའི་ནང་དོན་$1སྟེང་དུ་ཡོད།',
'copyrightpage' => '{{ns:project}}:པར་དབང་།',
'currentevents' => 'ད་ལྟའི་དོན་གནད།',
'currentevents-url' => 'Project:ད་ལྟའི་དོན་གནད།',
'disclaimers' => 'བདག་སྤྲོད་འདོར་མཁན།',
'disclaimerpage' => 'Project:ཡོངས་ཁྱབ་ཀྱི་བདག་སྤྲོད་ཁེ་དབང་རྩོད་ལེན།',
'edithelp' => 'རྩོམ་སྒྲིག་རོགས་རམ།',
'helppage' => 'Help:ནང་དོན།',
'mainpage' => 'གཙོ་གནད་ཤོག་ངོས།',
'mainpage-description' => 'གཙོ་གནད་ཤོག་ངོས།',
'policy-url' => 'Project: སྒྲིག་གཞི།',
'portal' => 'ཁོངས་མི་གཞུང་སྒོ།',
'privacy' => 'གསང་དོན་གན་རྒྱ།',
'privacypage' => 'Project: གསང་དོན་གན་རྒྱ།',

'badaccess' => 'ཆོག་ཆན་ལ་ནོར་འཁྲུལ།',

'ok' => 'འགྲིག',
'retrievedfrom' => '"$1"ལས་སླར་རྙེད་སོང།',
'youhavenewmessages' => 'ཁྱེད་ལ་འཕྲིན་གསར་$1($2)ཡོད།',
'youhavenewmessagesmulti' => 'ཁྱེད་ལ་ $1 སྟེང་དུ་འཕྲིན་ཡིག་འདུག',
'editsection' => 'རྩོམ་སྒྲིག།',
'editold' => 'རྩོམ་སྒྲིག',
'viewsourceold' => 'ཁོངས་ལ་ལྟ་བ།',
'editlink' => 'བཟོ་བཅོས།',
'viewsourcelink' => 'ཁོངས་ལ་ལྟ་བ།',
'editsectionhint' => 'རྩོམ་སྒྲིག་སྡེ་ཚན།：$1',
'toc' => 'ནང་དོན།',
'showtoc' => 'སྟོན།',
'hidetoc' => 'སྦས།',
'collapsible-collapse' => 'རྡིབ་སྐྱོན།',
'viewdeleted' => ' $1 ལ་ལྟའམ།',
'site-rss-feed' => '$1 ཡི་RSS འབྱུང་ཁུངས།',
'site-atom-feed' => '$1 ཡི་ཆ་ཤས་ཡ་ལན།',
'page-rss-feed' => '$1 ཡི་RSS འབྱུང་ཁུངས།',
'page-atom-feed' => '$1 ཡི་Atom འབྱུང་ཁུངས།',
'red-link-title' => '$1 (ཤོག་ངོས་མེད་པ།)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main' => 'ཤོག་ངོས།',
'nstab-user' => 'སྤྱོད་མིའི་ཤོག་ངོས།',
'nstab-special' => 'ཆེད་ལས་ཤོག་ངོས།',
'nstab-project' => 'ལས་འཆར་ཤོག་ངོས།',
'nstab-image' => 'ཡིག་ཆ།',
'nstab-mediawiki' => 'སྐད་ཆ།',
'nstab-template' => 'དཔེ་པང་།',
'nstab-help' => 'རོགས་རམ་ཤོག་ངོས།',
'nstab-category' => 'དཀར་ཆག',

# Main script and global functions
'nosuchaction' => 'བྱ་འགུལ་འདི་འདྲ་མེད།',
'nosuchspecialpage' => 'དམིགས་བསལ་ཤོག་ངོས་འདི་འདྲ་ཞིག་མི་འདུག',

# General errors
'error' => 'ནོར་འཁྲུལ།',
'readonly' => 'གཞི་གྲངས་མཛོད་ཟྭ་བརྒྱབ་པ།',
'missing-article' => 'གཞི་གྲངས་མཛོད་ནང་ཤོག་ངོས་ཀྱི་ཡི་གེ་བཙལ་ཐུབ་ཀྱི་མི་འདུག་པས། "$1" $2 
ཕལ་ཆེར་འདི་གཤམ་ཀྱི་འགོག་རྐྱེན་ལོ་རྒྱུས་སུབ་པ་འམ་དུས་ཡོལ་ཀྱི་ཁྱད་པར་སོགས་ཀྱི་རྒྱུ་རྐྱེན་ཡིན།
གལ་སྲིད་དེ་ལྟར་མིན་ཚེ། ཕལ་ཆེར་ཁྱེད་ཀྱི་ནོར་སྐྱོན་འཙལ་རྙེད་བྱང་བས།  [[Special:ListUsers/sysop|administrator]], དྲ་རྒྱའི་ས་ཚིགས་འདི་ཐོག་ཞུ་ཡིག་འབུལ་གནང་ཡོང་བ་མཁྱེན།',
'internalerror' => 'ནང་ལོག་ནོར་སྐྱོན།',
'internalerror_info' => 'ནང་ལོགས་ནོར་སྐྱོན། $1',
'filecopyerror' => '"$1" "$2"ལ་འདྲ་བཤུ་བྱེད་མ་ཐུབ།',
'filedeleteerror' => '"$1"ཟེར་བ་སུབ་མ་ཐུབ།',
'filenotfound' => '"$1"ཟེར་བའི་ཡིག་ཆ་མ་རྙེད་པ།',
'badtitle' => 'ཁ་བྱང་སྐྱོན་ཅན།',
'viewsource' => 'ཁོངས་ལ་ལྟ་བ།',
'actionthrottled' => 'བྱ་འགུལ་ཁེགས་སོང་།',
'namespaceprotected' => "ཁྱེད་ལ་'''$1''' མིང་གནས་ནང་གི་ཤོག་ངོས་བཟོ་བཅོས་ཀྱི་ཆོག་མཆན་མེད།",
'ns-specialprotected' => 'དམིགས་བསམ་ཤོག་ངོས་རྣམས་བཟོ་བཅོས་བྱེད་མི་ཐུབ།',

# Virus scanner
'virus-unknownscanner' => 'ངོས་མ་ཟིན་པའི་དྲ་འབུ།',

# Login and logout pages
'yourname' => 'སྤྱོད་མིང་།',
'yourpassword' => 'ལམ་ཡིག',
'yourpasswordagain' => 'གསང་བའི་ཨང་ངོས་འཛིན་གནང་རོགས།',
'remembermypassword' => 'ངའི་ལམ་ཡིག་འདིར་(མང་མཐའ་ཉིན $1 {{PLURAL:$1}}) དྲན་པར་བྱས།',
'login' => 'ནང་འཛུལ།',
'nav-login-createaccount' => 'ནང་འཛུལ། / ཐོ་འགོད།',
'userlogin' => 'ནང་འཛུལ། / ཐོ་འགོད།',
'userloginnocreate' => 'ནང་འཛུལ།',
'logout' => 'ཕྱིར་འབུད།',
'userlogout' => 'ཕྱིར་འབུད།',
'notloggedin' => 'ནང་འཛུལ་བྱས་མེད།',
'nologinlink' => 'ཐོ་ཞིག་འགོད་པ།',
'createaccount' => 'ཐོ་འགོད།',
'gotaccount' => '$1 སྔོན་ཚུད་ནས་རྩིས་ཁྲ་ཡོད་དམ།',
'gotaccountlink' => 'ནང་འཛུལ།',
'userlogin-resetlink' => 'ཁྱེད་ཀྱི་ནང་འཛུལ་ཀྱི་ཞིབ་ཕྲའི་གནད་དོན་བརྗེད་འདུག་གམ།',
'createaccountmail' => 'གློག་འཕྲིན་སྤྱད་དེ།',
'createaccountreason' => 'རྒྱུ་མཚན།',
'badretype' => 'ལམ་ཡིག་གང་བཅུག་པ་ཐོ་ཐུག་མ་བྱུང་།',
'userexists' => 'མིང་འདི་བེད་སྤྱོད་བྱས་ཟིན་པས་མིང་གཞན་ཞིག་གདམ་རོགས།',
'loginerror' => 'ནང་འཛུལ་ནོར་སྐྱོན།',
'loginsuccesstitle' => 'ནང་འཛུལ་བདེ་བར་གྲུབ།',
'nosuchusershort' => 'སྤྱོད་མི་"$1"ཟེར་བ་མི་འདུག དག་ཆར་བསྐྱར་ཞིབ་བྱོས།',
'nouserspecified' => 'བཀོལ་མིང་ཞིག་ངེས་པར་དགོས།',
'login-userblocked' => 'སྤྱོད་མི་འདི་བཀག་འགོག་བྱས་པས་ནང་འཛུལ་གྱི་ཆོག་མཆན་མེད།',
'wrongpassword' => 'ལམ་ཡིག་ནོར་འདུག བསྐྱར་དུ་ཚོད་ལྟ་བྱོས།',
'wrongpasswordempty' => 'ལམ་ཡིག་སྟོང་པ་རེད། བསྐྱར་དུ་ཚོད་ལྟ་བྱོས།',
'mailmypassword' => 'གསང་བའི་ཨང་གྲངས་བསྐྱར་བཟོ་གནང་རོགས།',
'loginlanguagelabel' => 'སྐད་རིགས། $1',

# Change password dialog
'changepassword' => 'ལམ་ཡིག་བརྗེ་བ།',
'resetpass_announce' => 'ནང་འཛུལ་བྱེད་པར། ཁྱེད་ཀྱིས་གསང་བའི་ཨང་གསར་པ་ཞིག་འཇུག་རོགས།',
'oldpassword' => 'ལམ་ཡིག་རྙིང་བ།',
'newpassword' => 'ལམ་ཡིག་གསར་བ།',
'retypenew' => 'ལམ་ཡིག་གསར་བ་བསྐྱར་འཇུག་བྱོས།',
'resetpass_submit' => 'ལམ་ཡིག་བསྒྲིགས་ནས་ནང་འཛུལ་བྱེད་པ།',
'changepassword-success' => 'ལམ་ཡིག་བདེ་ལེགས་ངང་བརྗེས་ཟིན། ད་ནི་ནང་འཛུལ་བྱེད་བཞིན་པ་་་',
'resetpass_forbidden' => 'ལམ་ཡིག་བརྗེ་མི་ཐུབ།',
'resetpass-submit-loggedin' => 'ལམ་ཡིག་བརྗེ་བ།',
'resetpass-submit-cancel' => 'རྩིས་མེད་ཐོངས།',
'resetpass-temp-password' => 'གནས་སྐབས་ལམ་ཡིག',

# Special:PasswordReset
'passwordreset-username' => 'སྤྱོད་མིང་།',
'passwordreset-email' => 'དྲ་འཕྲིན་ཁ་བྱང་།',

# Special:ChangeEmail
'changeemail' => 'དྲ་འཕྲིན་ཁ་བྱང་བརྗེ་བ།',
'changeemail-oldemail' => 'ད་ཡོད་དྲ་འཕྲིན་ཁ་བྱང་།',
'changeemail-newemail' => 'དྲ་འཕྲིན་ཁ་བྱང་གསར་བ།',
'changeemail-none' => '(སྟོང་པ།)',
'changeemail-submit' => 'དྲ་འཕྲིན་བརྗེ་བ།',
'changeemail-cancel' => 'རྩིས་མེད་ཐོངས།',

# Edit page toolbar
'bold_sample' => 'ཡིག་གཟུགས་སྦོམ་པོ།',
'bold_tip' => 'ཡིག་གཟུགས་སྦོམ་པོ།',
'italic_sample' => 'ཡིག་གཟུགས་གསེག་མ།',
'italic_tip' => 'ཡིག་གཟུགས་གསེག་མ།',
'link_sample' => 'མཚམས་སྦྱོར་ཁ་ཡིག',
'link_tip' => 'ཕྱི་རོལ་མཐུད་སྦྲེལ།',
'extlink_sample' => 'Http://www.example.com སྦྲེལ་མཐུད་ཁ་བྱང་།',
'extlink_tip' => 'ཕྱི་ཕྱོགས་དྲ་འབྲེལ།',
'headline_sample' => 'འགོ་བརྗོད་ཡིག་གཟུགས།',
'headline_tip' => 'རིམ་པ། ༢ འགོ་ཕྲེང་།',
'nowiki_sample' => 'རྣམ་བཞག་མེད་པའི་ཡི་གེ་འདྲེན་པ།',
'nowiki_tip' => 'ཝེ་ཁེའི་རྣམ་གཞག་དོར་བ།',
'image_tip' => 'བཙུད་འཇུག་ཡིག་ཆ།',
'media_tip' => 'ཡིག་ཆ་སྦྲེལ་མཐུད།',
'sig_tip' => 'མིང་རྟགས་མཉམ་དུ་ཟླ་ཚེས་ཐེལ་ཙེ།',
'hr_tip' => 'ཐད་ཐིག ༼ཆུད་ཟོས་མེད་པར།༽',

# Edit pages
'summary' => 'བསྡུས་དོན།:',
'subject' => 'འགོ་བརྗོད།',
'minoredit' => 'འདི་ནི་རྩོམ་སྒྲིག་ཕལ་བ་ཞིག་ཡིན།',
'watchthis' => 'དྲ་ངོས་འདི་ལ་གཟིགས།',
'savearticle' => 'ཤོག་ངོས་ཉར་བ།',
'preview' => 'སྔོན་ལྟ།',
'showpreview' => 'སྔོན་ལྟ་སྟོན་ཅིག',
'showlivepreview' => 'ད་ཡོད་སྔོན་ལྟ།',
'showdiff' => 'བཟོས་བཅོས་སྟོན།',
'anoneditwarning' => "'''གསལ་བརྡ།''' ཁྱེད་ཐོ་འཛུལ་བྱས་མི་འདུག ཁྱེད་ཀྱི་ IP ཁ་བྱང་ཤོག་ངོས་འདིའི་རྩོམ་སྒྲིག་ལོ་རྒྱུས་སུ་ཉར་ཚགས་བྱས་པར་འགྱུར།",
'anonpreviewwarning' => '༼ཁྱེད་རང་ཐོ་འཛུལ་བྱས་མི་འདུག ཉར་ཚགས་ཀྱིས་ཁྱེད་ཀྱི་ IP ཁ་བྱང་ཤོག་ངོས་འདིའི་རྩོམ་སྒྲིག་ལོ་རྒྱུས་སུ་ཉར་ཚགས་བྱས་པར་འགྱུར།༽',
'summary-preview' => 'བསྡུས་དོན་སྔོན་ལྟ།',
'subject-preview' => 'བརྗོད་གཞི་དང་འགོ་བརྗོད་སྔོན་ལྟ།',
'blockedtitle' => 'སྤྱོད་མི་བཀག་ཟིན།',
'blockednoreason' => 'རྒྱུ་མཚན་བྱིན་མི་འདུག',
'whitelistedittext' => 'ཤོག་ངོས་རྩོམ་སྒྲིག་བྱེད་པར་ངེས་པར་དུ་$1བྱ་དགོས།',
'loginreqtitle' => 'ནང་འཛུལ་བྱ་དགོས།',
'loginreqlink' => 'ནང་འཛུལ་',
'loginreqpagetext' => 'ཤོག་ངོས་གཞན་རྣམས་ལྟ་བར་ངེས་པར་དུ་$1བྱ་དགོས།',
'accmailtitle' => 'ལམ་ཡིག་བཏང་ཟིན།',
'newarticle' => '(གསར་བ)',
'previewnote' => '"འདི་ནི་སྔོན་ལྟ་ཙམ་ཡིན་པ་ཡིད་ལ་འཇགས་རོགས། "ཁྱེད་ཀྱི་བཟོ་བཅོས་ད་དུང་ཉར་ཚགས་བྱས་མི་འདུག',
'editing' => '$1རྩོམ་སྒྲིག་བྱེད་བཞིན་པ།',
'editingsection' => ' $1 (སྡེ་ཚན) ལ་རྩོམ་སྒྲིག་བྱེད་བཞིན་པ།',
'yourtext' => 'ཁྱོད་ཀྱི་ཡིག་འབྲུ།',
'yourdiff' => 'མི་འདྲ་ས།',
'templatesused' => 'ཤོག་ངོས་འདིར་སྤྱད་པའི་ {{PLURAL:$1|དཔེ་པང་།}}',
'template-protected' => 'སྲུང་སྐྱོབ་འོག་ཡོད་པ།',
'nocreate-loggedin' => 'ཤོག་ངོས་གསར་བཟོའི་ཆོག་མཆན་མི་འདུག',
'permissionserrorstext-withaction' => 'གཤམ་ཀྱི་ {{PLURAL:$1|རྒྱུ་རྐྱེན་}}འོག་ཁྱེད་ལ་ $2 ཆོག་མཆན་མིན་འདུག་པས།',
'recreate-moveddeleted-warn' => "'''ཉེན་བརྡ་:རང་གིས་སུབ་ཚར་བའི་ཤོག་ལེ་ཞིག་བསྐྱར་བཟོ་བྱེད་ཀྱི་འདུག་ '''
ཁྱེད་རང་གལ་སྲིད་མུ་མཐུད་ཤོག་ལེ་འདི་བཟོ་ཅོས་བྱེད་འདོད་ན་སྟབས་བདེ་ཞིག་ལ་ང་ཚོས་སུབ་བཟིན་པའི་ཤོག་ལེ་འདིར་ཉར་ཡོད།",

# History pages
'viewpagelogs' => 'ཤོག་ངོས་འདིའི་ཉིན་ཐོ་ལ་ལྟ་བ།',
'currentrev-asof' => 'མཐའ་འཇུག་ཀྱི་ཞིབ་བཤེར། $1',
'revisionasof' => '$1 ལ་བཅོས་པ།',
'previousrevision' => ' ← བཟོ་བཅོས་སྔ་མ།',
'nextrevision' => 'ཞིབ་བཤེར་གསར་བ་ཁག།',
'currentrevisionlink' => 'ཉེ་བའི་ཆར་གྱི་ཞིབ་བཤེར།',
'cur' => 'ད་ལྟ།',
'next' => 'རྗེས་མ།',
'last' => 'མཐའ་མ།',
'page_first' => 'ཐོག་མ།',
'page_last' => 'མཐའ་མ།',
'history-fieldset-title' => 'ལོ་རྒྱུས་བཤར་ལྟ།',
'history-show-deleted' => 'དོར་ཟིན་ཁོ་ན།',
'histfirst' => 'ཆེས་སྔ་ཤོས།',
'histlast' => 'ཆེས་ཕྱི་ཤོས།',
'historyempty' => '༼སྟོང་པ།༽',

# Revision deletion
'rev-deleted-user' => '(སྤྱོད་མིང་སྤོར་ཟིན།)',
'rev-delundel' => 'སྟོན། / སྦས།',
'rev-showdeleted' => 'སྟོན།',
'revdelete-show-file-submit' => 'ཡིན།',
'revdelete-radio-same' => 'བཟོ་བཅོས་མ་བྱེད།',
'revdelete-radio-set' => 'ཡིན།',
'revdel-restore' => 'བཅོས་སུ་རུང་བ།',
'pagehist' => 'ཤོག་ངོས་ལོ་རྒྱུས།',
'revdelete-reasonotherlist' => 'རྒྱུ་མཚན་གཞན་པ།',

# History merging
'mergehistory-from' => 'འབྱུང་ཁུངས་ངོ་ཤོག',
'mergehistory-reason' => 'རྒྱུ་མཚན།',

# Merge log
'revertmerge' => 'སོ་སོར་ཕྱེ་བ།',

# Diffs
'lineno' => 'ཐིག་ཕྲེང་$1:',
'editundo' => 'ཕྱིར་འཐེན།',

# Search results
'searchresults' => 'བཙལ་བའི་རྙེད་དོན།',
'searchresults-title' => ' $1 བཙལ་བའི་འབྲས་བུ།',
'notextmatches' => 'ཤོག་ངོས་ཡིག་འབྲུ་མཚུངས་པ་མི་འདུག',
'prevn' => 'སྔོན་མ་{{PLURAL:$1|$1}}',
'nextn' => 'རྗེས་མ་{{PLURAL:$1|$1}}',
'shown-title' => 'མིག་སྔར་སྟོན་པ། $1{{PLURAL:$1|གྲུབ་འབྲས།}}ཤོག་ངོས་ལྟར།',
'viewprevnext' => '($1 {{int:pipe-separator}} $2) ($3)ལ་ལྟ་བ།',
'searchmenu-new' => 'ཝེ་ཁི་སྟེང་ལ་ <strong>ཤོག་ངོས་གསར་པ་</strong> "[[:$1]]" བཟོས། {{PLURAL:$2|0=|ཁྱེད་ཀྱི་འཚོལ་ཞིབ་བྱས་པའི་ཤོག་ངོས་རྣམས་ལ་ཡང་གཟིགས་རོགས།.|མ་ཟད་འཚོལ་ཞིབ་བྱས་པའི་གྲུབ་འབྲས་གཞན་རྣམས་ལ་ཡང་གཟིགས་རོགས།}}',
'searchprofile-articles' => 'ནང་དོན་ཤོག་ངོས།',
'searchprofile-project' => 'རོགས་རམ་དང་འཆར་གཞིའི་ཤོག་ངོས་',
'searchprofile-everything' => 'ཚང་མ་',
'searchprofile-advanced' => 'མཐོ་རིམ་',
'searchprofile-articles-tooltip' => '$1ནང་དུ་འཚོལ་བ།',
'searchprofile-project-tooltip' => '$1ནང་དུ་འཚོལ་བ།',
'searchprofile-images-tooltip' => 'ཡིག་ཆ་འཚོལ་བ།',
'searchprofile-everything-tooltip' => 'བརྗོད་དོན་ཚང་མ་འཚོལ་གཞིབ་བྱེད་(གྲོས་མེས་ཤོག་ངོས་ཡང་འཚུད་པ་)',
'search-result-size' => '$1({{PLURAL:$2|1 ཚིག། |$2 ཚིག།}})',
'search-redirect' => '($1རིམ་འགྲེམ།)',
'search-section' => '(ཚན་པ $1)',
'search-suggest' => '$1 ལ་ཟེར་བ་ཡིན་ནམ།',
'search-interwiki-caption' => 'སྲིང་མོའི་ལས་འཆར།',
'search-interwiki-default' => '$1ལས་རྙེད་པ།',
'search-interwiki-more' => '（དེ་ལས་མང་བ།）',
'search-relatedarticle' => 'འབྲེལ་ཡོད།',
'searchrelated' => 'འབྲེལ་ཡོད།',
'searchall' => 'ཚང་མ།',
'search-nonefound' => 'ཁྱེད་ཀྱི་འདྲི་ཞིབ་དང་མཐུན་པའི་ལན་མི་འདུག་',
'powersearch-legend' => 'ཞིབ་ཏུ་འཚོལ་བ།',
'powersearch-ns' => 'མིང་གནས་ནང་འཚོལ་བ།',
'powersearch-redir' => 'ཁ་ཕྱོགས་གསར་བཟོ་སྟོན་པ།',
'powersearch-toggleall' => 'ཚང་མ།',
'powersearch-togglenone' => 'མེད།',

# Preferences page
'mypreferences' => 'དགའ་འདེམས།',
'prefs-edits' => 'རྩོམ་སྒྲིག་གྲངས་ཚད།',
'skin-preview' => 'སྔོན་ལྟ།',
'prefs-personal' => 'སྤྱོད་མིའི་སྤྱི་ཁོག',
'prefs-rc' => 'ཉེ་བའི་བཟོ་བཅོས།',
'prefs-watchlist' => 'མཉམ་འཇོག་ཐོ།',
'prefs-watchlist-days-max' => 'Maximum $1 {{PLURAL:$1|day|days}}',
'prefs-watchlist-edits-max' => 'མང་ཚད་ཨང་གྲངས། ༡༠༠༠',
'prefs-resetpass' => 'ལམ་ཡིག་བརྗེ་བ།',
'prefs-changeemail' => 'དྲ་འཕྲིན་བརྗེ་བ།',
'prefs-setemail' => 'གློག་འཕྲིན་ཁ་བྱང་སྒྲིག་པ།',
'prefs-email' => 'གློག་འཕྲིན་འདེམས་ཚན།',
'saveprefs' => 'ཉར་བ།',
'searchresultshead' => 'འཚོལ།',
'stub-threshold-disabled' => 'ནུས་མེད་དུ་བཟོས་ཟིན།',
'timezoneregion-africa' => 'ཨ་ཧྥི་རི་ཀ',
'youremail' => 'གློག་འཕྲིན།:',
'username' => 'དྲ་མིང་།:',
'uid' => 'ནང་འཛུལ་ཐོ་མིང་།',
'yourrealname' => 'དངོས་མིང་།',
'yourlanguage' => 'སྐད་རིགས།',
'yournick' => 'མིང་རྟགས་སོ་མ།',
'yourgender' => 'ཕོ་མོ།',
'gender-male' => 'ཕོ།',
'gender-female' => 'མོ།',
'email' => 'དྲ་འཕྲིན།',
'prefs-help-email' => 'གློག་འཕྲིན་ཁ་བྱང་ནི་རང་མོས་ཡིན། ཡིན་ན་འང་གལ་སྲིད་ཁྱེད་ཀྱི་གསང་བའི་ཨང་གྲངས་འརྗེད་པ་སོགས་བྱུང་ཚེ། གསང་བའི་ཨང་བསྐྱར་སྒྲིག་སྐབས་ངེས་པར་དུ་དགོས།',
'prefs-help-email-others' => 'ཁྱེད་ཀྱི་ཐད་ཀར་གློག་འཕྲིན་འམ་ཡང་ན་འགྲོ་གླེང་ཤོག་ངོས་བརྒྱུད་སྤྱོད་མི་གཞན་རྣམས་ཀྱི་ཁྱེད་ལ་འབྲེལ་བ་བྱེད་ཐུབ། ཁྱེད་རང་གི་གློག་འཕྲིན་ཁ་བྱང་སྤྱོད་མི་གཞན་གྱི་འབྲེལ་བ་བྱེད་སྐབས་གསང་གཏོལ་བྱེད་མི་སྲིད།',
'prefs-info' => 'རྨང་གཞིའི་གནས་ཚུལ།',
'prefs-signature' => 'མིང་རྟགས།',

# User rights
'userrights-user-editname' => 'སྤྱོད་མིང་ཞིག་འཇུག་པ།',
'editusergroup' => 'སྤྱོད་མིའི་ཚོ་ཁག་རྩོམ་སྒྲིག',
'saveusergroups' => 'སྤྱོད་མིའི་ཚོ་ཁག་ཉར་ཚགས།',
'userrights-reason' => 'རྒྱུ་མཚན།',
'userrights-changeable-col' => 'ཁྱོད་ཀྱིས་བཟོ་བཅོས་ཐུབ་པའི་ཚོ་ཁག',
'userrights-unchangeable-col' => 'ཁྱོད་ཀྱིས་བཟོ་བཅོས་མི་ཐུབ་པའི་ཚོ་ཁག',

# Groups
'group' => 'ཚོ་ཁག',
'group-user' => 'ཁོངས་མི།',
'group-sysop' => 'དོ་དམ་པ།',
'group-all' => '(ཚང་མ།)',

'grouppage-user' => '{{ns:project}}:ཁོངས་མི།',
'grouppage-sysop' => '{{ns:project}}:དོ་དམ་པ།',

# Rights
'right-read' => 'ཤོག་ངོས་ཀློག་པ།',
'right-edit' => 'ཤོག་ངོས་རྩོམ་སྒྲིག',
'right-delete' => 'ཤོག་ངོས་སུབ་པ།',

# User rights log
'rightslog' => 'སྤྱོད་མིའི་ཐོབ་ཐང་།',

# Associated actions - in the sentence "You do not have permission to X"
'action-read' => 'ཤོག་ངོས་འདི་ཀློག་པ།',
'action-edit' => 'ཤོག་ངོས་འདི་རྩོམ་སྒྲིག་བྱེད་དང།',
'action-createpage' => 'ཤོག་ངོས་གསར་བཟོ།',
'action-move' => 'ཤོག་ངོས་འདི་སྤོར་ཅིག',
'action-movefile' => 'ཡིག་ཆ་འདི་སྤོར་ཅིག',
'action-upload' => 'ཡིག་ཆ་འདི་ཡར་འཇུག་བྱེད་པ།',
'action-delete' => 'ཤོག་ངོས་འདི་དོར་ཅིག',
'action-undelete' => 'ཤོག་ངོས་འདི་བསུབས་ཟིན་གསོ་བ།',
'action-block' => 'སྤྱོད་མི་འདི་རྩོམ་སྒྲིག་ལ་ཁོག་ཅིག',
'action-protect' => 'ཤོག་ངོས་འདིའི་སྲུང་སྐྱོབ་རིམ་པ་བསྒྱུར་བཅོས་གཏོང་བ།',
'action-import' => 'ཤོག་ངོས་འདི་ཝེ་ཁེ་གཞན་ནས་ནང་འདྲེན་བྱེད་པ།',
'action-importupload' => 'ཤོག་ངོས་འདི་ཡིག་ཆ་ཡར་འཇུག་ལས་ནང་འདྲེན་བྱེད་པ།',
'action-unwatchedpages' => 'མ་བལྟས་ཤོག་ངོས་ཀྱི་ཐོ་ལ་ལྟ་བ།',
'action-userrights' => 'སྤྱོད་མིའི་ཐོབ་ཐང་ཡོངས་ལ་རྩོམ་སྒྲིག་བྱེད་པ།',
'action-userrights-interwiki' => 'ཝེ་ཁེ་གཞན་གྱི་སྤྱོད་མི་ཚོའི་སྤྱོད་མིའི་ཐོབ་ཐང་རྩོམ་སྒྲིག་བྱེད་པ།',

# Recent changes
'nchanges' => '$1 {{PLURAL:$1|འགྱུར་བཅོས།|འགྱུར་བཅོས།}}',
'recentchanges' => 'ཉེ་བའི་བཟོ་བཅོས།',
'recentchanges-legend' => 'ཉེ་བའི་བཟོ་བཅོས་འདེམས་ཚན།',
'recentchanges-label-newpage' => 'རྩོམ་སྒྲིག་འདིས་ཤོག་ངོས་གསར་བ་ཞིག་བཟོས་འདུག',
'recentchanges-label-minor' => 'འདི་ནི་རྩོམ་སྒྲིག་ཕལ་བ་ཞིག་ཡིན།',
'rclistfrom' => '$1 ལས་འགོ་བཙུགས་ཏེ་འགྱུར་བཅོས་གསར་བ་སྟོན་ཅིག',
'rcshowhideminor' => '$1 རྩོམ་སྒྲིག་ཕལ་བ།',
'rcshowhideliu' => '$1 ཐོ་འགོད་སྤྱོད་མི།',
'rcshowhideanons' => 'མིང་མེད་སྤྱོད་མི $1',
'rcshowhidemine' => '$1ངའི་རྩོམ་སྒྲིག',
'rclinks' => 'འདས་བའི་ཉིན་ $2 <br />$3 ནང་ཚུན་གྱི་བཟོ་བཅོས་གཞུག་མ་ $1 སྟོན་ཅིག',
'diff' => 'མི་འདྲ་ས།',
'hist' => 'ལོ་རྒྱུས།',
'hide' => 'སྦས།',
'show' => 'སྟོན།',
'minoreditletter' => 'སྒྲིག་ཆུང་།',
'newpageletter' => 'ཤོག་གསར།',
'boteditletter' => 'རང་འགུལ་འཕྲུལ་ཆས།',
'rc_categories_any' => 'གང་རུང་།',
'rc-enhanced-expand' => 'ཞིབ་ཕྲར་སྟོན།',
'rc-enhanced-hide' => 'ཞིབ་ཕྲ་སྦས་བ།',

# Recent changes linked
'recentchangeslinked' => 'འབྲེལ་བའི་བཟོ་བཅོས།',
'recentchangeslinked-feed' => 'འབྲེལ་བའི་བཟོ་བཅོས།',
'recentchangeslinked-toolbox' => 'འབྲེལ་བའི་བཟོ་བཅོས།',
'recentchangeslinked-title' => '"$1" དང་འབྲེལ་བའི་འགྱུར་བཅོས།',
'recentchangeslinked-summary' => "འདི་ནི་དམིགས་གསལ་ཤོག་ངོས་༼ཡང་ན་དམིགས་གསལ་རྣམ་གྲངས་ཀྱི་ཁོངས་མི་༽དང་འབྲེལ་བའི་ཉེ་བའི་བཟོ་བཅོས་རེད།[[Special:Watchlist|yourwatchlist]] ནང་གི་ཤོག་ངོས་རྣམས་'''ཡིག་གཟུགས་སྦོམ་པོ་'''ཡིན།",
'recentchangeslinked-page' => 'ཤོག་ངོས་མིང་།',

# Upload
'upload' => 'ཡིག་ཆ་ཡར་འཇུག',
'uploadbtn' => 'ཡར་འཇོག',
'reuploaddesc' => 'ཡར་འཇུག་དོར་ནས་ཡར་འཇུག་རེའུ་མིག་ཏུ་ཕྱིར་ལོག་པ།',
'uploadnologin' => 'ནང་འཛུལ་བྱས་མེད།',
'uploadlogpage' => 'ཉིན་ཐོ་ཡར་འཇུག',
'filename' => 'ཡིག་ཆའི་མིང་།',
'filedesc' => 'བསྡུས་དོན།',
'fileuploadsummary' => 'བསྡུས་དོན།:',
'filereuploadsummary' => 'ཡིག་ཆ་བརྗེ་ལེན།',
'uploadedfiles' => 'ཡར་འཇུག་བྱས་པའི་ཡིག་ཆ།',
'ignorewarning' => 'ཉེན་བརྡ་སྣང་མེད་བཏང་ནས་ཡིག་ཆ་ཉོར་ཅིག',
'ignorewarnings' => 'ཉེན་བརྡ་ཅི་ཡོད་སྣང་མེད་ཐོངས་ཤིག',
'badfilename' => 'ཡིག་ཆའི་མིང་"$1"ལ་བསྒྱུར་ཟིན།',
'filename-tooshort' => 'ཡིག་ཆའི་མིང་ཐུང་དྲགས་འདུག',
'filetype-banned' => 'ཡིག་ཆ་འདིའི་རིགས་བཀག་སྡོམ་བྱས་འདུག',
'illegal-filename' => 'ཡིག་ཆའི་མིང་འདི་ལ་ཆོག་མཆན་མི་འདུག',
'uploadwarning' => 'ཡར་འཇུག་སྔོན་བརྡ།',
'savefile' => 'ཡིག་ཆ་ཉོར་ཅིག',
'uploadedimage' => '"[[$1]]"ཡར་འཇུག་བྱས་ཟིན།',
'uploaddisabled' => 'ཡར་འཇུག་ནུས་མེད་བཟོས་འདུག',
'watchthisupload' => 'ཡིག་ཆ་འདི་ལ་གཟིགས།',

'license-header' => 'གན་རྒྱ་ནང་ཞུགས་པ།',

# Special:ListFiles
'imgfile' => 'བརྙན་རིས།',
'listfiles' => 'ཡིག་ཆའི་ཐོ་གཞུང་།',
'listfiles_date' => 'ཟླ་ཚེས།',
'listfiles_name' => 'མིང་།',
'listfiles_user' => 'སྤྱོད་མི།',
'listfiles_size' => 'ཆེ་ཆུང་།',
'listfiles_description' => 'འགྲེལ་བཤད།',

# File description page
'file-anchor-link' => 'ཡིག་ཆ།',
'filehist' => 'ཡིག་ཆའི་ལོ་རྒྱུས།',
'filehist-help' => 'ཟླ་ཚེས་/དུས་ཚོད་གནུན་ཏེ་རྩོམ་ཡིག་ལ་ལྟ་བ།',
'filehist-deleteall' => 'ཚང་མ་སུབས།',
'filehist-deleteone' => 'གསུབས།',
'filehist-revert' => 'ཕྱིར་ལྡོག།',
'filehist-current' => 'ད་ལྟ།',
'filehist-datetime' => 'ཚེས་གྲངས། / དུས་ཚོད།',
'filehist-thumb' => 'བསྡུས་དོན།',
'filehist-thumbtext' => '$1 བཟོ་བཅོས་བསྡུས་དོན།',
'filehist-user' => 'སྤྱོད་མི།',
'filehist-dimensions' => 'ཚད།',
'filehist-filesize' => 'ཡིག་ཆའི་ཆེ་ཆུང་།',
'filehist-comment' => 'བསམ་ཚུལ།',
'filehist-missing' => 'ཡིག་ཆ་ཆད་པ།',
'imagelinks' => 'ཡིག་ཆ་བེད་སྤྱོད་ཁུལ།',
'linkstoimage' => '{{PLURAL:$1|pagelinks|$1pagelink}} འདི་ལ་སྦྲེལ་ཡོད།',
'shared-repo-from' => '$1 ནས།',

# File deletion
'filedelete' => '$1 སུབས་ཤིག',
'filedelete-legend' => 'ཡིག་ཆ་སུབས་ཤིག',
'filedelete-success' => "'''$1''' བསུབས་ཟིན།",

# MIME search
'download' => 'ཕབ་ལེན།',

# Unwatched pages
'unwatchedpages' => 'མ་བལྟས་ཤོག་ངོས།',

# Unused templates
'unusedtemplateswlh' => 'སྦྲེལ་མཐུད་གཞན་དག',

# Random page
'randompage' => 'སྐམས་དོན་ཤོག་ངོས།',

# Statistics
'statistics' => 'བསྡོམས་རྩིས།',

'brokenredirects-edit' => 'རྩོམ་སྒྲིག',
'brokenredirects-delete' => 'གསུབ་པ།',

# Miscellaneous special pages
'nbytes' => '{{PLURAL:$1|ཡིག་ཚིགས།}} $1',
'nmembers' => '$1 {{PLURAL:$1|ཚོགས་མི།|ཚོགས་མི།}}',
'prefixindex' => 'སྔཽན་སྦྱོར་དང་ལྷན་དུ་ཤོག་ངོས་ཧྲིལ་པོ།',
'shortpages' => 'ཤོག་ངོས་ཐུང་ངུ་།',
'newpages' => 'ཤོག་ངོས་གསར་བ།',
'newpages-username' => 'དྲ་མིང་།:',
'move' => 'སྤོར་བ།',
'movethispage' => 'ཤོག་ངོས་འདི་སྤོར།',
'pager-newer-n' => '{{PLURAL:$1|གསར་བ་1|གསར་བ་$1}}',
'pager-older-n' => '{{PLURAL:$1|རྙིང་པ་1|རྙིང་པ་$1}}',

# Book sources
'booksources' => 'དཔེ་ཆའི་ཁུངས།',
'booksources-search-legend' => 'དེབ་ཁུངས་འཚོལ་བ།',
'booksources-go' => 'སོང་།',

# Special:Log
'log' => 'པོད་ཁུག',

# Special:AllPages
'allpages' => 'དྲ་ངོས་ཡོངས།',
'alphaindexline' => '$1 ནས་ $2 བར།',
'prevpage' => 'ཤོག་ངོས་གོང་མ་ ($1)',
'allarticles' => 'ཤོག་ངོས་ཆ་ཚང་།',
'allpagessubmit' => 'སོང་།',

# Special:LinkSearch
'linksearch' => 'ཕྱི་རོལ་སྦྲེལ་མཐུད།',

# Special:ListGroupRights
'listgrouprights-members' => 'ཁོངས་མིའི་ཐོ་ཡིག',

# Email user
'emailuser' => 'སྤྱོད་མི་འདིར་གློག་འཕྲིན་སྐུར་བ།',
'emailmessage' => 'སྐད་ཆ།',

# Watchlist
'watchlist' => 'ལྟ་ཞིབ་ཐོ།',
'mywatchlist' => 'ལྟ་ཞིབ་ཐོ།',
'watchnologin' => 'ནང་འཛུལ་བྱས་མེད།',
'watch' => 'མཉམ་འཇོག་ཐོ།',
'watchthispage' => 'དྲ་ངོས་འདི་ལ་གཟིགས།',
'unwatch' => 'མི་བལྟ་བ།',
'unwatchthispage' => 'བལྟ་བ་མཚམས་འཇོག',
'wlshowlast' => 'འདས་བའི་དུས་ཚོད་ $1 ནང་ཚུན་  ཉིན་མ་ $2 ནང་ཚུན་ $3 སྟོན།',
'watchlist-options' => 'ལྟ་ཐོའི་འདེམས་ཚན།',

# Displayed when you click the "watch" button and it is in the process of watching
'watching' => 'ལྟ་ཐོར་འཇུག་བཞིན་པ་་་',
'unwatching' => 'ལྟ་ཐོ་ལས་འདོར་བཞིན་པ་་་',

'enotif_reset' => 'ཤོག་ངོས་ཚང་མ་བལྟས་ཟིན་དུ་རྟགས་རྒྱོབ།',
'created' => 'བཟོས་ཟིན།',
'changed' => 'བསྒྱུར་ཟིན།',

# Delete
'deletepage' => 'ཤོག་ངོས་འདོར་བ།',
'confirm' => 'གཏན་འཁེལ་བྱོས།',
'exblank' => 'ཤོག་ངོས་སྟོང་པ་རེད།',
'delete-confirm' => '"$1"སུབས་ཤིག',
'delete-legend' => 'སུབས་ཤིག',
'actioncomplete' => 'བྱ་འགུལ་ལེགས་གྲུབ།',
'actionfailed' => 'བྱ་འགུལ་ཕམ་ཉེས་བྱུང་བ།',
'dellogpage' => 'རྩོམ་ཡིག་སུབ་དོར།',
'deletecomment' => 'རྒྱུ་མཚན།',
'deleteotherreason' => 'རྒྱུ་མཚན་གཞན་པའམ་འཕར་མ།',
'deletereasonotherlist' => 'རྒྱུ་མཚན་གཞན།',

# Rollback
'rollbacklink' => 'རྒྱབ་འགྲིལ་གཏོང་བ།',

# Protect
'protectedarticle' => 'འགོག་སྲུང་བྱས་ཟིན།"[[$1]]"',
'modifiedarticleprotection' => '"[[$1]]" ལ་སྲུང་སྐྱོབ་རིམ་པ་བཟོ་བཅོས་བྱས་བ།',
'protectcomment' => 'རྒྱུ་མཚན།',
'protectexpiry' => 'དུས་ཡུན་རྫོགས་ཚད།',
'protect_expiry_invalid' => 'དུས་ཡུན་རྫོགས་ཚད་ནོར་བ།',
'protect-default' => 'སྤྱོད་མི་ཡོངས་ལ་ཕྱེ་བ།',
'protect-fallback' => '"$1" ཆོག་མཆན་དགོས།',
'protect-level-autoconfirmed' => 'སྤྱོད་མི་གསར་བ་དང་ཐོ་མེད་རྣམས་བཀག་འགོག',
'protect-level-sysop' => 'དོ་དམ་པ་ཁོ་ནར།',
'protect-cantedit' => 'ཁྱོད་ལ་ཤོག་ངོས་འདི་རྩོམ་སྒྲིག་གི་ཆོག་མཆན་མེད་པས་ངོས་འདི་ཡི་སྲུང་སྐྱོབ་རིམ་པ་ལ་བཟོ་བཅོས་བྱེད་མི་ཆོག',
'restriction-type' => 'ཆོག་མཆན།',
'restriction-level' => 'དམ་བསྒྲགས་ཚད་རིམ།',

# Restrictions (nouns)
'restriction-edit' => 'རྩོམ་སྒྲིག',
'restriction-move' => 'སྤོར།',

# Undelete
'undeletelink' => 'ལྟ་བ། / བསྐྱར་འདྲེན།',
'undeleteviewlink' => 'ལྟ་བ་',
'undelete-search-submit' => 'འཚོལ།',

# Namespace form on various pages
'namespace' => 'མིང་གནས།',
'invert' => 'གདམ་པའི་ལྡོག་ཕྱོགས།',
'blanknamespace' => '(གཙོ་གནད།)',

# Contributions
'contributions' => '{{GENDER:$1|སྤྱོད་མི།}} མཐུན་འགྱུར།',
'mycontris' => 'མཐུན་འགྱུར།',
'month' => 'ཟླ་བ་འདི་ནས།',
'year' => 'ལོ་འདི་ནས།',

'sp-contributions-username' => 'IP གནས་ཡུལ་ལམ་སྤྱོད་མིང་།',
'sp-contributions-submit' => 'འཚོལ་བ།',

# What links here
'whatlinkshere' => 'གང་དང་སྦྲེལ་བ།',
'whatlinkshere-title' => '"$1" ལ སྦྲེལ་ཡོད་པའི་ཤོག་ངོས།',
'whatlinkshere-page' => 'ཤོག་ངོས།',
'linkshere' => "གཤམ་གྱི་ཤོག་ངོས་རྣམས་ '''[[:$1]]''': ལ་སྦྲེལ་ཡོད།",
'nolinkshere' => '<strong>[[:$1]]</strong> ཤོག་ངོས་གཅིག་ཀྱང་སྦྲེལ་མཐུད་མི་འདུག།',
'isimage' => 'ཡིག་རིས་སྦྲེལ་མཐུད།',
'whatlinkshere-links' => '← སྦྲེལ་མཐུད།',
'whatlinkshere-hideredirs' => '$1 ཁ་ཕྱོགས་བསྐྱར་སྟོན།',
'whatlinkshere-hidelinks' => '$1 སྦྲེལ་མཐུད།',
'whatlinkshere-hideimages' => '$1 ཡིག་ཆ་སྦྲེལ་མཐུད།',
'whatlinkshere-filters' => 'ཡིག་ཚགས།',

# Block/unblock
'blockip' => 'སྤྱོད་མི་འགོག་སྡོམ།',
'ipbreason' => 'རྒྱུ་མཚན།',
'ipblocklist' => 'འགོག་སྡོམ་བྱས་པའི་སྤྱོད་མི།',
'ipblocklist-submit' => 'འཚོལ།',
'blocklink' => 'འགོག་པ།',
'unblocklink' => 'བཀག་སྡོམ་གློད་པ།',
'change-blocklink' => 'བཀག་སྡོམ་བསྒྱུར་བཅོས།',
'contribslink' => 'མཐུན་འགྱུར།',
'blocklogpage' => 'རྩོམ་ཡིག་བཀག་འགོག',
'block-log-flags-nocreate' => 'རྩིས་ཁྲ་གསར་བཟོ་འགོག་འདུག།',

# Move page
'movearticle' => 'ཤོག་ངོས་སྤོར་བ།',
'newtitle' => 'ཁ་བྱང་གསར་བ་ལ།',
'move-watch' => 'དྲ་ངོས་འདི་ལ་མཉམ་འཇོག་པ།',
'movepagebtn' => 'ཤོག་ངོས་སྤོ་བ།',
'pagemovedsub' => 'སྤོར་བ་ལེགས་གྲུབ།',
'movelogpage' => 'རྩོམ་ཡིག་སྤོ་བ།',
'movereason' => 'རྒྱུ་མཚན།',
'revertmove' => 'ཕྱིར་ལོག',

# Export
'export' => 'ཤོག་ངོས་ཕྱི་འདྲེན།',

# Namespace 8 related
'allmessages' => 'མ་ལག་གི་སྐད་ཆ།',
'allmessagesname' => 'མིང་',

# Thumbnails
'thumbnail-more' => 'ཆེ་རུ་གཏོང་བ།',

# Tooltip help for the actions
'tooltip-pt-userpage' => 'ཁྱེད་ཀྱི་སྤྱོད་མིའི་ཤོག་ངོས།',
'tooltip-pt-mytalk' => 'ཁྱེད་ཀྱི་གླེང་མོལ་ཤོག་ངོས།',
'tooltip-pt-preferences' => 'ཁྱེད་ཀྱི་ལེགས་སྒྲིག',
'tooltip-pt-watchlist' => 'ཞུ་དག་གཏོང་བཞིན་པའི་ཤོག་ངོས།',
'tooltip-pt-mycontris' => 'ངའི་བྱས་རྗེས་སྟོན་པ།',
'tooltip-pt-login' => 'ཁྱེད་རང་ནང་འཛུལ་གནང་བར་དགའ་བསུ་ཞུ། འོན་ཀྱང་འདིར་འགན་འཁུར་བྱེད་མཁན་མེད།',
'tooltip-pt-logout' => 'ཕྱིར་དོན།',
'tooltip-ca-talk' => 'ཤོག་ངོས་ཀྱི་ནང་དོན་གྲོས་མོལ།',
'tooltip-ca-edit' => 'ཁྱེད་ཀྱིས་ཤོག་ངོས་འདིར་རྩོམ་སྒྲིག་བྱེད་ཆོག། ཉར་ཚགས་མ་བྱེད་གོང་སྔོན་ལྟའི་མཐེབ་གཞོང་གནོན་རོགས།',
'tooltip-ca-addsection' => 'སྡེ་ཚན་གསར་བ་ཞིག་འགོ་འཛུགས་པ།',
'tooltip-ca-viewsource' => 'ཤོག་ངོས་འདི་འགོག་སྲུང་བྱས་ཡོད། ཁྱེད་ཀྱིས་འདིའི་ཁུངས་ལ་ལྟ་ཆོག།',
'tooltip-ca-history' => 'ཤོག་ངོས་འདིའི་བསྐྱར་བཅོས་སྔ་མ།',
'tooltip-ca-protect' => 'ཤོག་ངོས་འདི་སྲུངས་ཤིག',
'tooltip-ca-delete' => 'ཤོག་ངོས་འདི་དོར་ཅིག',
'tooltip-ca-move' => 'ཤོག་ངོས་འདི་སྤོར་བ།',
'tooltip-ca-watch' => 'ཤོག་ངོས་འདི་ཁྱོད་ཀྱི་མཉམ་འཇོག་ཐོ་རུ་འཇུག་པ།',
'tooltip-ca-unwatch' => 'ཤོག་ངོས་འདི་མཉམ་འཇོག་ཐོ་ལས་ལེན་པ།',
'tooltip-search' => 'འཚོལ། {{SITENAME}}',
'tooltip-search-go' => 'མིང་ཇི་བཞིན་པའི་ཤོག་ངོས་སྟེང་དུ་སྐྱོད་པ།',
'tooltip-search-fulltext' => 'ཤོག་ངོས་ཐོག་ཡིག་རྐྱང་འདི་འཚོལ།',
'tooltip-p-logo' => 'གཙོ་གནད་ཤོག་ངོས་ལ་གཟིགས་རོགས།',
'tooltip-n-mainpage' => 'གཙོ་གནད་ཤོག་ངོས་ལ་གཟིགས་རོགས།',
'tooltip-n-mainpage-description' => 'གཙོ་གནད་ཤོག་ངོས་ལ་གཟིགས་རོགས།',
'tooltip-n-portal' => 'ལས་འཆར་སྐོར། ཁྱེད་ཀྱིས་གང་བྱེད་ཐུབ་བམ། གང་དུ་འཚོལ་དགོས་སམ།',
'tooltip-n-currentevents' => 'ཉེ་བའི་ལས་དོན་གྱི་རྒྱབ་ལྗོངས་གནས་ཚུལ་འཚོལ་བ།',
'tooltip-n-recentchanges' => 'ཝེ་ཁེ་སྟེང་གི་ཉེ་ཆར་བཟོ་བཅོས་ཀྱི་ཐོ།',
'tooltip-n-randompage' => 'སྐབས་དོན་ཤོག་ངོས་ཤིག་ཕབ་འཇུག།',
'tooltip-n-help' => 'གང་དུ་འཚོལ་བའི་གནས།',
'tooltip-t-whatlinkshere' => 'འདིར་སྦྲེལ་ཡོད་པའི་ཝེ་ཁེ་ཤོག་ངོས་ཧྲིལ་པོའི་ཐོ།',
'tooltip-t-recentchangeslinked' => 'ཤོག་ངོས་འདི་དང་སྤྲེལ་བའི་ཤོག་ངོས་གཞན་ཐོག་གི་ཉེ་བའི་བཟོ་བཅོས།',
'tooltip-feed-rss' => 'ཤོག་ངོས་འདིའི་RSS འབྱུང་ཁུངས།',
'tooltip-feed-atom' => 'ཤོག་ངོས་འདིའི་Atom འབྱུང་ཁུངས།',
'tooltip-t-contributions' => 'བཀོལ་མི་འདིའི་བྱས་རྗེས་སྟོན།',
'tooltip-t-emailuser' => 'སྤྱོད་མི་འདིར་གློག་འཕྲིན་བསྐུར་རོགས།',
'tooltip-t-upload' => 'ཡིག་ཆ་ཡར་འཇུག',
'tooltip-t-specialpages' => 'ཆེད་ལས་ཤོག་ངོས་ཀྱི་ཐོ་གཞུང་།',
'tooltip-t-print' => 'ཤོག་ངོས་འདིའི་དཔར་རུང་བའི་འགྱུར།',
'tooltip-t-permalink' => 'བཟོ་འཅོས་ཤོག་ངོས་འདིའི་བརྟན་འཇགས་ཀྱི་སྦྲེལ་མཐུད།',
'tooltip-ca-nstab-main' => 'ནང་དོན་ཀྱི་ཤོག་ངོས་ལ་གཟིགས་རོགས།',
'tooltip-ca-nstab-user' => 'སྤྱོད་མིའི་ཤོག་ངོས་ལ་ལྟ་བ།',
'tooltip-ca-nstab-special' => 'དྲ་ངོས་འདི་དམིགས་གསལ་བ་ཡིན་པས་བཟོ་བཅོས་རྒྱག་མི་ཆོག',
'tooltip-ca-nstab-project' => 'ལས་འཆར་ཤོག་ངོས་ལ་ལྟ་བ།',
'tooltip-ca-nstab-image' => 'ཡིག་ཆར་ལྟ་བ།',
'tooltip-ca-nstab-template' => 'དཔེ་པང་ལ་ལྟ་བ།',
'tooltip-ca-nstab-category' => 'རྣམ་གྲངས་ཤོག་ངོས་སྟོན།',
'tooltip-minoredit' => 'རྩོམ་སྒྲིག་ཕལ་བར་འཇུག་པ།',
'tooltip-save' => 'བཟོ་བཅོས་ཉར་ཚགས་བྱོས།',
'tooltip-preview' => 'ཉར་ཚགས་ཀྱི་སྔོན་དུ་བཟོ་བཅོས་ལ་བསྐྱར་ཞིབ་གནང་རོགས།',
'tooltip-diff' => 'གང་ལ་བཟོ་བཅོས་བྱས་པའི་ཡིག་འབྲུ་སྟོན་པ།',
'tooltip-undo' => '"ཕྱིར་འཐེན།" ཞེས་པ་དེས་ཁྱེད་ཀྱི་རྩོམ་སྒྲིག་ཕྱིར་ལྡོག་པ་དང་སྔོན་འཚུད་བལྟ་ཞིབ་ཤོག་ངོས་ཁ་ཕྱེ་རྒྱུ་ཡིན། མཇུག་སྡོམ་ཀྱི་རྒྱུ་རྐྱེན་གླེང་འཇུག་རྒྱུ་ཡིན།',
'tooltip-summary' => 'ཕྱོགས་བསྡོམས་ཐུང་ངུ་ཞིག་འབྲིས་',

# Browsing diffs
'previousdiff' => '← རྩོམ་སྒྲིག་རྙིང་བ།',
'nextdiff' => 'རྩོམ་སྒྲིག་གསར་གྲས། →',

# Media information
'show-big-image' => 'ཐོག་མའི་ཡིག་ཆ།',

# Special:NewFiles
'ilsubmit' => 'འཚོལ།',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'ཚང་མ།',
'namespacesall' => 'ཡོངས་རྫོགས།',
'monthsall' => 'ཡོངས་རྫོགས།',

# Multipage image navigation
'imgmultigo' => 'སོང་།!',

# Table pager
'table_pager_limit_submit' => 'སོང་།',

# Watchlist editing tools
'watchlisttools-view' => 'འབྲེལ་ཡོད་འགྱུར་བཅོས་ལ་གཟིགས་རོགས།',
'watchlisttools-edit' => 'མཉམ་འཇོག་ཐོར་ལྟ་བ་དང་བསྒྱུར་བཅོས་བྱེད་པ།',
'watchlisttools-raw' => 'ལྟ་ཐོའི་གོ་རིམ་བཅོས་སྒྲིག',

# Special:SpecialPages
'specialpages' => 'ཆེད་ལས་ཤོག་ངོས།',

# New logging system
'rightsnone' => '(སྟོང་པ།)',

);
