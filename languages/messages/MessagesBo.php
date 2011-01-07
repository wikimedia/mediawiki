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
'tog-underline'             => 'འོག་ཐིག་འཐེན་པ།',
'tog-highlightbroken'       => 'ནང་འབྲེལ་མཚམས་ཐིག་<a hred="" class="new">ལྟ་བུ།</a>(ཡང་ན།<a href=""class="internal">?ལྟ་བུ།</a>)',
'tog-justify'               => 'ཚིག་གི་ཚད་སྙོམས་པ།',
'tog-hideminor'             => 'རྩོམ་སྒྲིག་ཆུང་ཚགས་སྦས་བ།',
'tog-hidepatrolled'         => 'ལྟ་ཞིབ་བྱས་པའི་རྩོམ་སྒྲིག་སྦས་བ།',
'tog-newpageshidepatrolled' => 'ཤོག་ངོས་གསར་བར་ལྟ་ཞིབ་བྱས་པའི་རྩོམ་སྒྲིག་སྦས་བ།',
'tog-extendwatchlist'       => 'མཉམ་འཇོག་ཐོ་བཀྲམས་ཏེ་ཉེ་ལམ་ཙམ་མིན་པར་བཟོ་བཅོས་ཡོངས་རྫོགས་སྟོན་ཅིག',
'tog-usenewrc'              => 'ཡར་རྒྱས་ཅན་གྱི་ཉེ་བའི་བཟོ་བཅོས་བེད་སྤྱོད་པ།(Java ཡི་བརྡ་ཆད་དགོས)',
'tog-showtoolbar'           => 'རྩོམ་སྒྲིག་ལག་ཆ་སྟོན།(Java ཡི་བརྡ་ཆད་དགོས།)',
'tog-editondblclick'        => 'ཤོག་ངོས་རྩོམ་སྒྲིག་བྱེད་པར་ལན་གཉིས་རྡེབ།(Java ཡི་བརྡ་ཆད་དགོས།)',
'tog-rememberpassword'      => 'ངའི་ནང་འཛུལ་བཤར་ལྟ་ཆས་འདི་རུ་མང་མཐའ་ཉིན $1 {{PLURAL:$1}} དྲན་པར་མཛོད།',
'tog-watchcreations'        => 'ངའི་ལྟ་ཐོའི་གྲས་སུ་གསར་བཟོ་བྱས་པ་ལ་ཤོག་ངོས་ཁ་སྣོན།',
'tog-watchdefault'          => 'ངའི་ལྟ་ཐོའི་གྲས་སུ་རྩོམ་སྒྲིག་བྱས་པ་ལ་ཤོག་ངོས་ཁ་སྣོན།',
'tog-watchmoves'            => 'ངའི་ལྟ་ཐོའི་གྲས་སུ་སྤོར་བ་ལ་ཤོག་ངོས་ཁ་སྣོན།',
'tog-watchdeletion'         => 'ངའི་ལྟ་ཐོའི་གྲས་སུ་དོར་བ་ལ་ཤོག་ངོས་ཁ་སྣོན།',
'tog-previewontop'          => 'རྩོམ་སྒྲིག་སྒྲོམ་གྱི་སྔོན་དུ་དཔེ་གཟུགས་སྟོན་པ།',
'tog-previewonfirst'        => 'ཐོག་མའི་རྩོམ་སྒྲིག་སྟེང་དུ་དཔེ་གཟུགས་སྟོན་པ།',
'tog-enotifwatchlistpages'  => 'ངའི་ལྟ་ཐོའི་ཤོག་ངོས་ལ་བཟོ་བཅོས་བྱུང་ཚེ་གློག་འཕྲིན་གཏང་རོགས།',
'tog-enotifusertalkpages'   => 'ངའི་སྤྱོད་མིའི་གླེང་མོལ་ལ་བཟོ་བཅོས་བྱུང་ཚེ་གློག་འཕྲིན་གཏང་རོགས།',
'tog-enotifminoredits'      => 'རྩོམ་སྒྲིག་ཆུང་ཚགས་རིགས་ལའང་གློག་འཕྲིན་གཏོང་རོགས།',
'tog-shownumberswatching'   => 'ཤོག་ངོས་ལ་ལྟ་བཞིན་པའི་སྤྱོད་མིའི་ཁ་གྲངས་སྟོན།',
'tog-oldsig'                => 'ད་ཡོད་མིང་རྟགས་དཔེ་གཟུགས།',
'tog-watchlisthideown'      => 'ངའི་རྩོམ་སྒྲིག་རྣམས་ལྟ་ཐོ་ལས་སྦས་རོགས།',
'tog-watchlisthideminor'    => 'རྩོམ་སྒྲིག་ཕལ་བ་རྣམས་ལྟ་ཐོ་ལས་སྦས་རོགས།',
'tog-watchlisthideliu'      => 'ཐོ་འཛུལ་སྤྱོད་མིའི་རྩོམ་སྒྲིག་རྣམས་ལྟ་ཐོ་ལས་སྦས་རོགས།',
'tog-ccmeonemails'          => 'ངས་གཞན་ལ་བཏང་བའི་གློག་འཕྲིན་གྱི་འདྲ་བཤུས་སྐུར་རོགས།',
'tog-showhiddencats'        => 'སྦས་བའི་དཀར་ཆག་སྟོན་རོགས།',

'underline-always'  => 'ནམ་ཡང་།',
'underline-never'   => 'ནམ་ཡང་མིན།',
'underline-default' => 'རྒྱས་བ་འདྲེན་པ།',

# Font style option in Special:Preferences
'editfont-style'     => 'རྩོམ་སྒྲིག་ཡིག་གཟུགས།',
'editfont-default'   => 'རྒྱས་པ་འདྲེན་པ།',
'editfont-monospace' => 'བར་ཚད་མཉམ་པའི་ཡིག་གཟུགས།',
'editfont-sansserif' => 'ཡིག་གཟུགས་རྭ་མེད།',
'editfont-serif'     => 'ཡིག་གཟུགས་རྭ་ཅན།',

# Dates
'sunday'        => 'གཟའ་ཉི་མ།',
'monday'        => 'གཟའ་ཟླ་བ།',
'tuesday'       => 'གཟའ་མིག་དམར།',
'wednesday'     => 'གཟའ་ལྷག་པ།',
'thursday'      => 'གཟའ་ཕུར་བུ།',
'friday'        => 'གཟའ་པ་སངས།',
'saturday'      => 'གཟའ་སྤེན་པ།',
'sun'           => 'གཟའ་ཉི་མ།',
'mon'           => 'གཟའ་ཟླ་བ།',
'tue'           => 'གཟའ་མིག་དམར།',
'wed'           => 'གཟའ་ལྷག་པ།',
'thu'           => 'གཟའ་ཕུར་བུ།',
'fri'           => 'གཟའ་པ་སངས།',
'sat'           => 'གཟའ་སྤེན་པ།',
'january'       => 'སྤྱི་ཟླ་དང་པོ།',
'february'      => 'སྤྱི་ཟླ་གཉིས་པ།',
'march'         => 'སྤྱི་ཟླ་གསུམ་པ།',
'april'         => 'སྤྱི་ཟླ་བཞི་བ།',
'may_long'      => 'སྤྱི་ཟླ་ལྔ་བ།',
'june'          => 'སྤྱི་ཟླ་དྲུག་པ།',
'july'          => 'སྤྱི་ཟླ་བདུན་པ།',
'august'        => 'སྤྱི་ཟླ་བརྒྱད་པ།',
'september'     => 'སྤྱི་ཟླ་དགུ་བ།',
'october'       => 'སྤྱི་ཟླ་བཅུ་བ།',
'november'      => 'སྤྱི་ཟླ་བཅུ་གཅིག་པ།',
'december'      => 'སྤྱི་ཟླ་བཅུ་གཉིས་པ།',
'january-gen'   => ' ༡',
'february-gen'  => ' ༢',
'march-gen'     => ' ༣',
'april-gen'     => ' ༤',
'may-gen'       => ' ༥',
'june-gen'      => ' ༦',
'july-gen'      => ' ༧',
'august-gen'    => ' ༨',
'september-gen' => ' ༩',
'october-gen'   => ' ༡༠',
'november-gen'  => ' ༡༡',
'december-gen'  => ' ༡༢',
'jan'           => 'སྤྱི་ཟླ་དང་པོ།',
'feb'           => 'སྤྱི་ཟླ་གཉིས་པ།',
'mar'           => 'སྤྱི་ཟླ་གསུམ་པ།',
'apr'           => 'སྤྱི་ཟླ་བཞི་བ།',
'may'           => 'སྤྱི་ཟླ་ལྔ་བ།',
'jun'           => 'སྤྱི་ཟླ་དྲུག་པ།',
'jul'           => 'སྤྱི་ཟླ་བདུན་པ།',
'aug'           => 'སྤྱི་ཟླ་བརྒྱད་པ།',
'sep'           => 'སྤྱི་ཟླ་དགུ་བ།',
'oct'           => 'སྤྱི་ཟླ་བཅུ་བ།',
'nov'           => 'སྤྱི་ཟླ་བཅུ་གཅིག་པ།',
'dec'           => 'སྤྱི་ཟླ་བཅུ་གཉིས་པ།',

# Categories related messages
'pagecategories'           => '{{PLURAL:|སྡེ་ཚན་|སྡེ་ཚན་ $1}}',
'category_header'          => 'རྣམ་གྲངས་"$1"ནང་གི་ཤོག་ངོས་རྣམས།',
'subcategories'            => 'རིགས་ཕལ་བ།',
'hidden-categories'        => '{{PLURAL:$1|སྦས་བའི་སྡེ་ཚན།|སྦས་བའི་སྡེ་ཚན།}}',
'hidden-category-category' => 'སྦས་བའི་སྡེ་ཚན།',

'about'         => 'ཨཱབོཨུཏ་',
'newwindow'     => '(སྒེའུ་ཁུང་གསར་བར་ཕྱེ་བ།)',
'cancel'        => 'རྩིས་མེད།',
'moredotdotdot' => 'དེ་ལས་མང་བ་་་',
'mypage'        => 'ངའི་ཤོག་ངོས།',
'mytalk'        => 'ངའི་གླེང་མོལ།',
'anontalk'      => 'IP གནས་ཡུལ་འདི་ལ་གླེང་མོལ།',
'navigation'    => 'ཕྱོགས་ཁྲིད།',

# Cologne Blue skin
'qbfind'         => 'འཚོལ་བ།',
'qbedit'         => 'རྩོམ་སྒྲིག',
'qbpageoptions'  => 'ཤོག་ངོས་འདི།',
'qbmyoptions'    => 'ངའི་ཤོག་ངོས།',
'qbspecialpages' => 'དམིཊ་བསལ་གྱི་བཟོ་བཅོས།',
'faq'            => 'རྒྱུན་ལྡན་དྲི་བ།',
'faqpage'        => 'Project:རྒྱུན་ལྡན་དྲི་བ།',

# Vector skin
'vector-action-addsection' => 'བརྗོད་གཞི་ཁ་སྣོན།',
'vector-action-delete'     => 'སུབས།',
'vector-action-move'       => 'ལེན་པ།',
'vector-action-protect'    => 'སྲུང་སྐྱོབ།',
'vector-action-undelete'   => 'བསུབས་པ་གསོ་བ།',
'vector-action-unprotect'  => 'སྲུང་སྐྱོབ་གློད་པ།',
'vector-view-create'       => 'གསར་བཟོ།',
'vector-view-edit'         => 'རྩོམ་སྒྲིག',
'vector-view-history'      => 'ལོ་རྒྱུས་ལ་ལྟ་བ།',
'vector-view-view'         => 'ཀློག་པ།',
'vector-view-viewsource'   => 'ཁུངས་ལ་ལྟ་བ།',
'actions'                  => 'བྱ་འགུལ།',
'namespaces'               => 'མིང་འགོད་ས།',

'errorpagetitle'    => 'ནོར་འཁྲུལ།',
'returnto'          => '$1 ལ་བསྐྱར་ལོག་བྱེད་པ།',
'tagline'           => 'ཡོང་ཁུངས་{{SITENAME}}',
'help'              => 'རོགས་རམ།',
'search'            => 'འཚོལ་བ།',
'searchbutton'      => 'འཚོལ་བ།',
'go'                => 'སོང་།',
'searcharticle'     => 'འཚོལ།',
'history'           => 'ཤོག་ངོས་ལོ་རྒྱུས།',
'history_short'     => 'ལོ་རྒྱུས།',
'printableversion'  => 'དཔར་ཐུབ་པ།',
'permalink'         => 'རྟག་བརྟན་གྱི་དྲ་འབྲེལ།',
'print'             => 'དཔར་བ།',
'view'              => 'ལྟ་བ།',
'edit'              => 'རྩོམ་སྒྲིག',
'create'            => 'གསར་སྐྲུན།',
'editthispage'      => 'ངོས་འདི་བཟོ་བཅོས་བྱེད་པ།',
'create-this-page'  => 'ཤོག་ངོས་འདི་སྐྲུན་པ།',
'delete'            => 'སུབས།',
'deletethispage'    => 'ཤོག་ངོས་འདི་འདོར་བ།',
'undelete_short'    => '{{PLURAL:$1|one edit|$1edits}} མ་འདོར་ཞིག',
'viewdeleted_short' => '{{བསུབས་པའི་རྩོམ་སྒྲིག PLURAL:$1|བསུབས་པའི་རྩོམ་སྒྲིག $1}}ལ་ལྟ་བ།',
'protect'           => 'སྲུང་བ།',
'protect_change'    => 'སྒྱུར་བཅོས།',
'protectthispage'   => 'ཤོག་ངོས་འདི་སྲུང་བ།',
'unprotectthispage' => 'ཤོག་ངོས་འདི་སྲུང་མི་དགོས།',
'newpage'           => 'ཤོག་ངོས་གསར་བ།',
'talkpage'          => 'ཤོག་ངོས་འདིར་གྲོས་སྡུར།',
'talkpagelinktext'  => 'གླེང་མོལ།',
'specialpage'       => 'དམིགས་གསལ་ཤོག་ངོས།',
'personaltools'     => 'སྒེར་ཀྱི་ལག་ཆ།',
'postcomment'       => 'སྡེ་ཚན་གསར་བ།',
'talk'              => 'གྲོས་བསྡུར།',
'views'             => 'མཐོང་རིས།',
'toolbox'           => 'ལག་ཆའི་སྒྲོམ།',
'userpage'          => 'སྤྱོད་མིའི་ཤོག་ངོས་ལ་ལྟ་བ།',
'projectpage'       => 'ལས་འཆར་ཤོག་ངོས་ལ་ལྟ་བ།',
'imagepage'         => 'ཡིག་ཆའི་ཤོག་ངོས་ལ་ལྟ་བ།',
'mediawikipage'     => 'འཕྲིན་ཐུང་ཤོག་ངོས་ལ་ལྟ་བ།',
'templatepage'      => 'དཔེ་པང་ཤོག་ངོས་ལ་ལྟ་བ།',
'viewhelppage'      => 'རོགས་རམ་ཤོག་ངོས་ལ་ལྟ་བ།',
'categorypage'      => 'སྡེ་ཚན་ཤོག་ངོས་སྟོན་ཅིག',
'viewtalkpage'      => 'གྲོས་མོལ་ལ་ལྟ་བ།',
'otherlanguages'    => 'སྐད་རིགས་གཞན།',
'redirectedfrom'    => '$1 ནས་ཁ་ཕྱོགས་བསྐྱར་དུ་བཟོས་པ།',
'lastmodifiedat'    => 'དྲ་ངོས་འདི་ཡི་བཟོ་བཅོས་མཐའ་མ་$1$2སྟེང་རེད།',
'protectedpage'     => 'སྲུང་སྐྱོབ་བྱས་པའི་ཤོག་ངོས།',
'jumpto'            => 'གནས་སྤོ།',
'jumptonavigation'  => 'ཕྱོགས་ཁྲིད།',
'jumptosearch'      => 'འཚོལ།',
'pool-errorunknown' => 'ངོས་མ་ཟིན་པའི་ནོར་འཁྲུལ།',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => '{{SITENAME}}ངེད་ཀྱི་སྐོར།',
'aboutpage'            => 'Project:དྲ་བའི་སྐོར།',
'copyright'            => 'དྲ་བའི་ནང་དོན་$1སྟེང་དུ་ཡོད།',
'copyrightpage'        => '{{ns:project}}:པར་དབང་།',
'currentevents'        => 'ད་ལྟའི་བྱ་བ།',
'currentevents-url'    => 'Project:ད་ལྟའི་བྱ་བ།',
'disclaimers'          => 'དགག་བྱ།',
'disclaimerpage'       => 'Project:སྤྱིའི་དགག་བྱ།',
'edithelp'             => 'རྩོམ་སྒྲིག་རོགས་རམ།',
'edithelppage'         => 'Help:རྩོམ་སྒྲིག',
'mainpage'             => 'གཙོ་ངོས།',
'mainpage-description' => 'གཙོ་ངོས།',
'portal'               => 'ཁོངས་མི་འདུ་ར།',
'privacy'              => 'སྒེར་ཁྲིམས།',
'privacypage'          => 'Project: སྒེར་ཁྲིམས།',

'badaccess' => 'ཆོག་ཆན་ལ་ནོར་འཁྲུལ།',

'ok'                      => 'འགྲིག',
'retrievedfrom'           => '"$1"ལས་རྙེད་པ།',
'youhavenewmessages'      => 'ཁྱེད་ལ་འཕྲིན་གསར་$1($2)ཡོད།',
'newmessageslink'         => 'འཕྲིན་གསར།',
'newmessagesdifflink'     => 'བཟོ་བཅོས་མཐའ་མ།',
'youhavenewmessagesmulti' => 'ཁྱེད་ལ་ $1 སྟེང་དུ་འཕྲིན་ཡིག་འདུག',
'editsection'             => 'རྩོམ་སྒྲིག',
'editold'                 => 'རྩོམ་སྒྲིག',
'viewsourceold'           => 'ཁོངས་ལ་ལྟ་བ།',
'editlink'                => 'བཟོ་བཅོས།',
'viewsourcelink'          => 'ཁོངས་ལ་ལྟ་བ།',
'editsectionhint'         => 'རྩོམ་སྒྲིག་སྡེ་ཚན།$1',
'toc'                     => 'ཟུར་མཆན།',
'showtoc'                 => 'སྟོན།',
'hidetoc'                 => 'སྦས།',
'viewdeleted'             => ' $1 ལ་ལྟའམ།',
'site-rss-feed'           => '$1 ཡི་RSS འབྱུང་ཁུངས།',
'site-atom-feed'          => '$1 ཡི་Atom འབྱུང་ཁུངས།',
'red-link-title'          => '$1 ( ཤོག་ངོས་མེད་པ།)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'རྩོམ་ཡིག',
'nstab-user'      => 'སྤྱོད་མིའི་ཤོག་ངོས།',
'nstab-special'   => 'དམིཊ་གསལ་ཤོག་ངོས།',
'nstab-project'   => 'ལས་འཆར་ཤོག་ངོས།',
'nstab-image'     => 'ཡིག་ཆ།',
'nstab-mediawiki' => 'སྐད་ཆ།',
'nstab-template'  => 'དཔེ་པང་།',
'nstab-help'      => 'རོགས་རམ་ཤོག་ངོས།',
'nstab-category'  => 'དཀར་ཆག',

# Main script and global functions
'nosuchaction' => 'བྱ་འགུལ་འདི་འདྲ་མེད།',

# General errors
'error'              => 'ནོར་འཁྲུལ།',
'readonly'           => 'གཞི་གྲངས་མཛོད་ཟྭ་བརྒྱབ་པ།',
'internalerror'      => 'ནང་ལོག་ནོར་སྐྱོན།',
'internalerror_info' => 'ནང་ལོགས་ནོར་སྐྱོན། $1',
'filecopyerror'      => '"$1" "$2"ལ་འདྲ་བཤུ་བྱེད་མ་ཐུབ།',
'filedeleteerror'    => '"$1"ཟེར་བ་སུབ་མ་ཐུབ།',
'filenotfound'       => '"$1"ཟེར་བའི་ཡིག་ཆ་མ་རྙེད་པ།',
'badtitle'           => 'ཁ་བྱང་སྐྱོན་ཅན།',
'viewsource'         => 'ཁོངས་ལ་ལྟ་བ།',

# Virus scanner
'virus-unknownscanner' => 'ངོས་མ་ཟིན་པའི་དྲ་འབུ།',

# Login and logout pages
'yourname'                => 'སྤྱོད་མིང་།',
'yourpassword'            => 'ལམ་ཡིག',
'yourpasswordagain'       => 'ལམ་ཡིག་ཡང་གཏག་བྱོས།',
'remembermypassword'      => 'ངའི་ལམ་ཡིག་འདིར་(མང་མཐའ་ཉིན $1 {{PLURAL:$1}}) དྲན་པར་བྱས།',
'login'                   => 'ནང་འཛུལ།',
'nav-login-createaccount' => 'ནང་འཛུལ། / ཐོ་འགོད།',
'userlogin'               => 'ནང་འཛུལ། / ཐོ་འགོད།',
'userloginnocreate'       => 'ནང་འཛུལ།',
'logout'                  => 'ཕྱིར་འབུད།',
'userlogout'              => 'ཕྱིར་འབུད།',
'notloggedin'             => 'ནང་འཛུལ་བྱས་མེད།',
'nologinlink'             => 'ཐོ་ཞིག་འགོད་པ།',
'createaccount'           => 'ཐོ་འགོད།',
'gotaccountlink'          => 'ནང་འཛུལ།',
'createaccountmail'       => 'གློག་འཕྲིན་སྤྱད་དེ།',
'createaccountreason'     => 'རྒྱུ་མཚན།',
'badretype'               => 'ལམ་ཡིག་གང་བཅུག་པ་ཐོ་ཐུག་མ་བྱུང་།',
'userexists'              => 'མིང་འདི་བེད་སྤྱོད་བྱས་ཟིན་པས་མིང་གཞན་ཞིག་གདམ་རོགས།',
'loginerror'              => 'ནང་འཛུལ་ནོར་སྐྱོན།',
'nosuchusershort'         => 'སྤྱོད་མི་"<nowiki>$1</nowiki>"ཟེར་བ་མི་འདུག དག་ཆར་བསྐྱར་ཞིབ་བྱོས།',
'login-userblocked'       => 'སྤྱོད་མི་འདི་བཀག་འགོག་བྱས་པས་ནང་འཛུལ་གྱི་ཆོག་མཆན་མེད།',
'wrongpassword'           => 'ལམ་ཡིག་ནོར་འདུག བསྐྱར་དུ་ཚོད་ལྟ་བྱོས།',
'wrongpasswordempty'      => 'ལམ་ཡིག་སྟོང་པ་རེད། བསྐྱར་དུ་ཚོད་ལྟ་བྱོས།',
'mailmypassword'          => 'གློག་འཕྲིན་ལམ་ཡིག་གསར་བ།',
'loginlanguagelabel'      => 'སྐད་རིགས། $1',

# JavaScript password checks
'password-strength-bad'        => 'སྙོམས་པ།',
'password-strength-acceptable' => 'ངོས་ལེན་རུང་བ།',
'password-strength-good'       => 'བཙན་པ།',

# Password reset dialog
'oldpassword'               => 'ལམ་ཡིག་རྙིང་བ།',
'newpassword'               => 'ལམ་ཡིག་གསར་བ།',
'retypenew'                 => 'ལམ་ཡིག་གསར་བ་བསྐྱར་འཇུག་བྱོས།',
'resetpass-submit-loggedin' => 'ལམ་ཡིག་བརྗེ་བ།',
'resetpass-submit-cancel'   => 'རྩིས་མེད་ཐོངས།',
'resetpass-temp-password'   => 'གནས་སྐབས་ལམ་ཡིག',

# Edit page toolbar
'bold_sample'     => 'ཡིག་གཟུགས་སྦོམ་པོ།',
'bold_tip'        => 'ཡིག་གཟུགས་སྦོམ་པོ།',
'italic_sample'   => 'ཡིག་གཟུགས་གསེག་མ།',
'italic_tip'      => 'ཡིག་གཟུགས་གསེག་མ།',
'link_sample'     => 'མཚམས་སྦྱོར་ཁ་ཡིག',
'link_tip'        => 'ཕྱི་རོལ་མཐུད་སྦྲེལ།',
'extlink_sample'  => 'Http://www.example.com སྦྲེལ་མཐུད་ཁ་བྱང་།',
'extlink_tip'     => 'ཕྱི་ཕྱོགས་དྲ་འབྲེལ།',
'headline_sample' => 'འགོ་བརྗོད་ཡིག་གཟུགས།',
'headline_tip'    => 'རིམ་པ། ༢ འགོ་ཕྲེང་།',
'nowiki_sample'   => 'རྣམ་བཞག་མེད་པའི་ཡི་གེ་འདྲེན་པ།',
'nowiki_tip'      => 'ཝེ་ཁེའི་རྣམ་གཞག་དོར་བ།',
'media_tip'       => 'ཡིག་ཆ་སྦྲེལ་མཐུད།',
'sig_tip'         => 'མིང་རྟགས་མཉམ་དུ་ཟླ་ཚེས་ཐེལ་ཙེ།',
'hr_tip'          => 'ཐད་ཐིག ༼ཆུད་ཟོས་མེད་པར།༽',

# Edit pages
'summary'            => 'བསྡུས་དོན།:',
'subject'            => 'འགོ་བརྗོད།',
'minoredit'          => 'འདི་ནི་རྩོམ་སྒྲིག་ཕལ་བ་ཞིག་ཡིན།',
'watchthis'          => 'དྲ་ངོས་འདི་ལ་གཟིགས།',
'savearticle'        => 'ཤོག་ངོས་ཉར་བ།',
'preview'            => 'སྔ་མ།',
'showpreview'        => 'སྔ་མ་སྟོན་ཅིག',
'showdiff'           => 'བཟོས་བཅོས་སྟོན།',
'anoneditwarning'    => "'''གསལ་བརྡ།''' ཁྱེད་ཐོ་འཛུལ་བྱས་མི་འདུག ཁྱེད་ཀྱི་ IP ཁ་བྱང་ཤོག་ངོས་འདིའི་རྩོམ་སྒྲིག་ལོ་རྒྱུས་སུ་ཉར་ཚགས་བྱས་པར་འགྱུར།",
'blockedtitle'       => 'སྤྱོད་མི་བཀག་ཟིན།',
'loginreqtitle'      => 'ནང་འཛུལ་བྱ་དགོས།',
'loginreqlink'       => 'ནང་འཛུལ།',
'accmailtitle'       => 'ལམ་ཡིག་བཏང་ཟིན།',
'newarticle'         => '(གསར་བ)',
'previewnote'        => "'''འདི་ནི་སྔོན་ལྟ་ཙམ་ཡིན་པ་མ་བརྗེད།''' ཁྱེད་ཀྱི་བཟོ་བཅོས་ད་དུང་ཉར་ཚགས་བྱས་མི་འདུག",
'editing'            => '$1རྩོམ་སྒྲིག་བྱེད་བཞིན་པ།',
'editingsection'     => ' $1 (སྡེ་ཚན) ལ་རྩོམ་སྒྲིག་བྱེད་བཞིན་པ།',
'templatesused'      => 'ཤོག་ངོས་འདིར་སྤྱད་པའི་ {{PLURAL:$1|དཔེ་པང་།|དཔེ་པང་།}} used on this page:',
'template-protected' => 'སྲུང་སྐྱོབ་འོག་ཡོད་པ།',

# History pages
'viewpagelogs'           => 'ཤོག་ངོས་འདིའི་ཉིན་ཐོ་ལ་ལྟ་བ།',
'revisionasof'           => '$1 ལ་བཅོས་པ།',
'previousrevision'       => ' ← བཟོ་བཅོས་སྔ་མ།',
'cur'                    => 'ད་ལྟ།',
'next'                   => 'རྗེས་མ།',
'last'                   => 'མཐའ་མ།',
'page_first'             => 'ཐོག་མ།',
'page_last'              => 'མཐའ་མ།',
'history-fieldset-title' => 'ལོ་རྒྱུས་བཤར་ལྟ།',
'history-show-deleted'   => 'དོར་ཟིན་ཁོ་ན།',
'histfirst'              => 'སྔ་ཤོས།',
'histlast'               => 'ཕྱི་ཤོས།',
'historyempty'           => '༼སྟོང་པ།༽',

# Revision deletion
'rev-delundel'               => 'སྟོན། / སྦས།',
'rev-showdeleted'            => 'སྟོན།',
'revdelete-show-file-submit' => 'ཡིན།',
'revdelete-radio-set'        => 'ཡིན།',
'revdel-restore'             => 'བཅོས་སུ་རུང་བ།',

# Merge log
'revertmerge' => 'སོ་སོར་ཕྱེ་བ།',

# Diffs
'lineno'   => 'ཐིག་ཕྲེང་$1:',
'editundo' => 'ཕྱིར་འཐེན།',

# Search results
'searchresults'             => 'བཙལ་བའི་རྙེད་དོན།',
'searchresults-title'       => ' $1 བཙལ་བའི་འབྲས་བུ།',
'searchresulttext'          => '{{SITENAME}} སྐོར་ལ་རྒྱས་བར་[[{{MediaWiki:Helppage}}|{{int:help}}]]. ལ་ལྟ་རོགས།',
'searchsubtitle'            => 'ཁྱེད་ཀྱིས་\'\'\'[[:$1]]\'\'\' ([[Special:Prefixindex/$1|all pages starting with "$1"]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|all pages that link to "$1"]])བཙལ་འདུག',
'searchsubtitleinvalid'     => "ཁྱེད་ཀྱིས་'''$1'''བཙལ་འདུག",
'notitlematches'            => 'ཤོག་ངོས་འགོ་བརྗོད་མཚུངས་པ་མི་འདུག',
'notextmatches'             => 'ཤོག་ངོས་ཡིག་འབྲུ་མཚུངས་པ་མི་འདུག',
'prevn'                     => 'སྔོན་མ་{{PLURAL:$1|$1}}',
'nextn'                     => 'རྗེས་མ་{{PLURAL:$1|$1}}',
'viewprevnext'              => '($1 {{int:pipe-separator}} $2) ($3)ལ་ལྟ་བ།',
'search-result-size'        => '$1({{PLURAL:$2|1 word|$2 words}})',
'search-redirect'           => 'ཁ་ཕྱོགས་བསྐྱར་བཟོ།',
'search-section'            => '(ཚན་པ $1)',
'search-suggest'            => '$1 ལ་ཟེར་བ་ཡིན་ནམ།',
'search-interwiki-caption'  => 'སྲིང་མོའི་ལས་འཆར།',
'search-interwiki-default'  => '$1ལས་རྙེད་པ།',
'search-interwiki-more'     => '（དེ་ལས་མང་བ།）',
'search-mwsuggest-enabled'  => 'གྲོས་འཆར་དང་བཅས།',
'search-mwsuggest-disabled' => 'གྲོས་འཆར་མི་དགོས།',
'powersearch'               => 'ཞིབ་ཏུ་འཚོལ་བ།',
'powersearch-legend'        => 'ཞིབ་ཏུ་འཚོལ་བ།',
'powersearch-ns'            => 'མིང་གནས་ནང་འཚོལ་བ།',
'powersearch-redir'         => 'ཁ་ཕྱོགས་གསར་བཟོ་སྟོན་པ།',
'powersearch-field'         => 'ཆེད་འཚོལ།',

# Preferences page
'mypreferences'     => 'ངའི་ལེགས་སྒྲིག',
'prefsnologin'      => 'ནང་འཛུལ་བྱས་མེད།',
'prefs-personal'    => 'སྤྱོད་མིའི་སྤྱི་ཁོག',
'prefs-rc'          => 'ཉེ་བའི་བཟོ་བཅོས།',
'prefs-watchlist'   => 'མཉམ་འཇོག་ཐོ།',
'prefs-email'       => 'གློག་འཕྲིན་འདེམས་ཚན།',
'searchresultshead' => 'འཚོལ།',
'youremail'         => 'དྲ་འཕྲིན། *:',
'username'          => 'དྲ་མིང་།:',
'uid'               => 'ནང་འཛུལ་ཐོ་མིང་།',
'yourrealname'      => 'དངོས་མིང་།',
'yourgender'        => 'ཕོ་མོ།',
'gender-male'       => 'ཕོ།',
'email'             => 'དྲ་འཕྲིན།',
'prefs-info'        => 'རྨང་གཞིའི་གནས་ཚུལ།',
'prefs-signature'   => 'མིང་རྟགས།',

# Groups
'group-sysop' => 'དོ་དམ་པ།',

'grouppage-sysop' => '{{ns:project}}:དོ་དམ་པ།',

# User rights log
'rightslog' => 'སྤྱོད་མིའི་ཐོབ་ཐང་།',

# Associated actions - in the sentence "You do not have permission to X"
'action-edit' => 'ཤོག་ངོས་འདིར་རྩོམ་སྒྲིག',

# Recent changes
'recentchanges'        => 'ཉེ་བའི་བཟོ་བཅོས།',
'recentchanges-legend' => 'ཉེ་བའི་བཟོ་བཅོས་འདེམས་ཚན།',
'rcshowhideminor'      => '$1 རྩོམ་སྒྲིག་ཕལ་བ།',
'rcshowhideliu'        => 'ཐོ་འཛུལ་བྱས་པའི་སྤྱོད་མི་ $1',
'rcshowhidemine'       => '$1ངའི་རྩོམ་སྒྲིག',
'rclinks'              => 'འདས་བའི་ཉིན་ $2 <br />$3 ནང་ཚུན་གྱི་བཟོ་བཅོས་གཞུག་མ་ $1 སྟོན་ཅིག',
'diff'                 => 'མི་འདྲ་ས།',
'hist'                 => 'ལོ་རྒྱུས།',
'hide'                 => 'སྦས།',
'show'                 => 'སྟོན།',
'minoreditletter'      => 'སྒྲིག་ཆུང་།',
'newpageletter'        => 'ཤོག་གསར།',
'rc-enhanced-expand'   => 'ཞིབ་ཕྲ་སྟོན།',
'rc-enhanced-hide'     => 'ཞིབ་ཕྲ་སྦས་བ།',

# Recent changes linked
'recentchangeslinked'         => 'འབྲེལ་བའི་བཟོ་བཅོས།',
'recentchangeslinked-feed'    => 'འབྲེལ་བའི་བཟོ་བཅོས།',
'recentchangeslinked-toolbox' => 'འབྲེལ་བའི་བཟོ་བཅོས།',
'recentchangeslinked-summary' => "འདི་ནི་དམིགས་གསལ་ཤོག་ངོས་༼ཡང་ན་དམིགས་གསལ་རྣམ་གྲངས་ཀྱི་ཁོངས་མི་༽དང་འབྲེལ་བའི་ཉེ་བའི་བཟོ་བཅོས་རེད།[[Special:Watchlist|yourwatchlist]] ནང་གི་ཤོག་ངོས་རྣམས་'''ཡིག་གཟུགས་སྦོམ་པོ་'''ཡིན།",
'recentchangeslinked-page'    => 'ཤོག་ངོས་མིང་།',

# Upload
'upload'            => 'ཡིག་ཆ་ཡར་འཇུག',
'uploadbtn'         => 'ཡར་འཇོག',
'uploadnologin'     => 'ནང་འཛུལ་བྱས་མེད།',
'uploadlogpage'     => 'རྩོམ་ཡིག་ཡར་འཇུག',
'filedesc'          => 'བསྡུས་དོན།',
'fileuploadsummary' => 'བསྡུས་དོན།:',
'uploadedimage'     => '"[[$1]]"ཡར་འཇུག་བྱས་ཟིན།',
'watchthisupload'   => 'དྲ་ངོས་འདི་ལ་མཉམ་འཇོག་པ།',

# File description page
'filehist'            => 'ཡིག་ཆའི་ལོ་རྒྱུས།',
'filehist-help'       => 'ཟླ་ཚེས་/དུས་ཚོད་གནུན་ཏེ་རྩོམ་ཡིག་ལ་ལྟ་བ།',
'filehist-current'    => 'ད་ལྟ།',
'filehist-datetime'   => 'ཚེས་གྲངས། / དུས་ཚོད།',
'filehist-thumb'      => 'བསྡུས་དོན།',
'filehist-thumbtext'  => '$1 བཟོ་བཅོས་བསྡུས་དོན།',
'filehist-user'       => 'བེད་སྤྱོད་བྱེད་མི།',
'filehist-dimensions' => 'ཚད།',
'filehist-comment'    => 'བསམ་ཚུལ།',
'imagelinks'          => 'གང་ལ་སྦྲེལ་བ།',
'linkstoimage'        => '{{PLURAL:$1|pagelinks|$1pagelink}} འདི་ལ་སྦྲེལ་ཡོད།',

# Random page
'randompage' => 'རང་མོས་ཤོག་ངོས།',

'brokenredirects-edit'   => 'རྩོམ་སྒྲིག',
'brokenredirects-delete' => 'གསུབ་པ།',

# Miscellaneous special pages
'nbytes'            => '{{PLURAL:$1|ཡིག་ཚགས།|ཡིག་ཚགས།}} $1',
'newpages'          => 'ཤོག་ངོས་གསར་བ།',
'newpages-username' => 'དྲ་མིང་།:',
'move'              => 'སྤོར་བ།',
'pager-newer-n'     => '{{PLURAL：$1|གསར་བ་1|གསར་བ་$1}}',
'pager-older-n'     => '{{PLURAL：$1|རྙིང་པ་1|རྙིང་པ་$1}}',

# Book sources
'booksources'               => 'དཔེ་ཆའི་ཁུངས།',
'booksources-search-legend' => 'དེབ་ཁུངས་འཚོལ་བ།',
'booksources-go'            => 'སོང་།',

# Special:Log
'log' => 'པོད་ཁུག',

# Special:AllPages
'allpages'       => 'དྲ་ངོས་ཡོངས།',
'alphaindexline' => '$1 ནས་ $2 བར།',
'prevpage'       => 'ཤོག་ངོས་གོང་མ་ ($1)',
'allarticles'    => 'ཤོག་ངོས་ཆ་ཚང་།',
'allpagessubmit' => 'སོང་།',

# Special:LinkSearch
'linksearch' => 'ཕྱི་རོལ་སྦྲེལ་མཐུད།',

# Special:ListGroupRights
'listgrouprights-members' => 'ཁོངས་མིའི་ཐོ་ཡིག',

# E-mail user
'emailuser'    => 'སྤྱོད་མི་འདིར་གློག་འཕྲིན་སྐུར་བ།',
'emailmessage' => 'སྐད་ཆ།',

# Watchlist
'watchlist'         => 'ངའི་མཉམ་འཇོག་ཐོ།',
'mywatchlist'       => 'ངའི་ལྟ་ཐོ།',
'watchnologin'      => 'ནང་འཛུལ་བྱས་མེད།',
'addedwatch'        => 'མཉམ་འཇོག་ཐོར་བཅུག་ཟིན།',
'removedwatch'      => 'ལྟ་ཐོ་ལས་འཐེན་ཟིན།',
'watch'             => 'མཉམ་འཇོག་ཐོ།',
'watchthispage'     => 'དྲ་ངོས་འདི་ལ་མཉམ་འཇོག་པ།',
'unwatch'           => 'མ་བལྟས་པ།',
'wlshowlast'        => 'འདས་བའི་དུས་ཚོད་ $1 ནང་ཚུན་  ཉིན་མ་ $2 ནང་ཚུན་ $3 སྟོན།',
'watchlist-options' => 'མཉམ་འཇོག་ཐོའི་འདེམས་ཚན།',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'མཉམ་འཇོག་ཐོ་རུ་འཇུག་བཞིན་པ་་་',
'unwatching' => 'མཉམ་འཇོག་ཐོ་ལས་འདོར་བཞིན་པ་་་',

# Delete
'deletepage'            => 'ཤོག་ངོས་འདོར་བ།',
'actioncomplete'        => 'བྱ་འགུལ་ལེགས་གྲུབ།',
'deletedarticle'        => '"[[$1]]"བསུབས་ཟིན།',
'dellogpage'            => 'རྩོམ་ཡིག་སུབ་དོར།',
'deletecomment'         => 'རྒྱུ་མཚན།',
'deleteotherreason'     => 'རྒྱུ་མཚན་གཞན་པའམ་འཕར་མ།',
'deletereasonotherlist' => 'རྒྱུ་མཚན་གཞན།',

# Rollback
'rollbacklink' => 'རྒྱབ་འགྲིལ་གཏོང་བ།',

# Protect
'protectedarticle'            => 'སྲུང་སྐྱོབ་བྱས་ཟིན།"[[$1]]"',
'modifiedarticleprotection'   => '"[[$1]]" ལ་སྲུང་སྐྱོབ་རིམ་པ་བཟོ་བཅོས་བྱས་བ།',
'protectexpiry'               => 'དུས་ཡུན་རྫོགས་ཚད།',
'protect_expiry_invalid'      => 'དུས་ཡུན་རྫོགས་ཚད་ནོར་བ།',
'protect-default'             => 'སྤྱོད་མི་ཡོངས་ལ་ཕྱེ་བ།',
'protect-level-autoconfirmed' => 'སྤྱོད་མི་གསར་བ་དང་ཐོ་མེད་རྣམས་བཀག་འགོག',
'protect-level-sysop'         => 'དོ་དམ་པ་ཁོ་ནར།',
'protect-cantedit'            => 'ཁྱོད་ལ་ཤོག་ངོས་འདི་རྩོམ་སྒྲིག་གི་ཆོག་མཆན་མེད་པས་ངོས་འདི་ཡི་སྲུང་སྐྱོབ་རིམ་པ་ལ་བཟོ་བཅོས་བྱེད་མི་ཆོག',
'restriction-type'            => 'ཆོག་མཆན།',

# Restrictions (nouns)
'restriction-edit' => 'རྩོམ་སྒྲིག',
'restriction-move' => 'སྤོར།',

# Undelete
'undeletelink'           => 'ལྟ་བ། / བསྐྱར་འདྲེན།',
'undelete-search-submit' => 'འཚོལ།',

# Namespace form on various pages
'namespace'      => 'མིང་གནས།',
'invert'         => 'གདམ་པའི་ལྡོག་ཕྱོགས།',
'blanknamespace' => '༼གཙོ་ངོས།༽',

# Contributions
'contributions' => 'སྤྱོད་མིའི་བྱས་རྗེས།',
'mycontris'     => 'ངའི་བྱས་རྗེས།',
'month'         => 'ཟླ་བ་འདི་ནས།',
'year'          => 'ལོ་འདི་ནས།',

'sp-contributions-username' => 'IP གནས་ཡུལ་ལམ་སྤྱོད་མིང་།',
'sp-contributions-submit'   => 'འཚོལ་བ།',

# What links here
'whatlinkshere'           => 'གང་དང་སྦྲེལ་བ།',
'whatlinkshere-title'     => '"$1" ལ སྦྲེལ་ཡོད་པའི་ཤོག་ངོས།',
'whatlinkshere-page'      => 'ཤོག་ངོས།',
'linkshere'               => "གཤམ་གྱི་ཤོག་ངོས་རྣམས་ '''[[:$1]]''': ལ་སྦྲེལ་ཡོད།",
'isimage'                 => 'བརྙན་རིས་སྦྲེལ་མཐུད།',
'whatlinkshere-links'     => '← སྦྲེལ་མཐུད།',
'whatlinkshere-hidelinks' => '$1 སྦྲེལ་མཐུད།',
'whatlinkshere-filters'   => 'ཡིག་ཚགས།',

# Block/unblock
'blockip'            => 'སྤྱོད་མི་འགོག་སྡོམ།',
'ipbreason'          => 'རྒྱུ་མཚན།',
'ipblocklist-submit' => 'འཚོལ།',
'blocklink'          => 'འགོག་པ།',
'unblocklink'        => 'བཀག་སྡོམ་གློད་པ།',
'change-blocklink'   => 'བཀག་སྡོམ་བསྒྱུར་བཅོས།',
'contribslink'       => 'བྱས་རྗེས།',
'blocklogpage'       => 'རྩོམ་ཡིག་བཀག་འགོག',

# Move page
'movearticle'  => 'ཤོག་ངོས་སྤོར་བ།',
'movenologin'  => 'ནང་འཛུལ་བྱས་མེད།',
'newtitle'     => 'ཁ་བྱང་གསར་བ་ལ།',
'move-watch'   => 'དྲ་ངོས་འདི་ལ་མཉམ་འཇོག་པ།',
'movepagebtn'  => 'ཤོག་ངོས་སྤོ་བ།',
'pagemovedsub' => 'སྤོར་བ་ལེགས་གྲུབ།',
'1movedto2'    => '[[$1]][[$2]]ལ་སྤོར་ཟིན།',
'movelogpage'  => 'རྩོམ་ཡིག་སྤོ་བ།',
'movereason'   => 'རྒྱུ་མཚན།',
'revertmove'   => 'ཕྱིར་ལོག',

# Export
'export' => 'ཤོག་ངོས་ཕྱི་འདྲེན།',

# Namespace 8 related
'allmessages' => 'མ་ལག་གི་སྐད་ཆ།',

# Thumbnails
'thumbnail-more' => 'ཆེ་རུ་གཏོང་བ།',

# Tooltip help for the actions
'tooltip-pt-userpage'            => 'ཁྱེད་ཀྱི་སྤྱོད་མིའི་ཤོག་ངོས།',
'tooltip-pt-mytalk'              => 'ཁྱེད་ཀྱི་གླེང་མོལ་ཤོག་ངོས།',
'tooltip-pt-preferences'         => 'ཁྱེད་ཀྱི་ལེགས་སྒྲིག',
'tooltip-pt-watchlist'           => 'ཞུ་དག་གཏོང་བཞིན་པའི་ཤོག་ངོས།',
'tooltip-pt-mycontris'           => 'ངའི་བྱས་རྗེས་སྟོན་པ།',
'tooltip-pt-login'               => 'ནང་འཛུལ།',
'tooltip-pt-logout'              => 'ཕྱིར་འབུད།',
'tooltip-ca-talk'                => 'གྲོས་མོལ།',
'tooltip-ca-edit'                => 'ཁྱེད་ཀྱིས་དྲ་ངོས་འདི་རྩོམ་སྒྲིག་བྱེད་ཆོག ཉར་ཚགས་བྱེད་པའི་སྔོན་དུ་མཐེབ་གཅུས་སྔོན་མ་སྤྱོད་རོགས།',
'tooltip-ca-addsection'          => 'སྡེ་ཚན་གསར་བ་ཞིག་འགོ་འཛུགས་པ།',
'tooltip-ca-viewsource'          => 'ཤོག་ངོས་འདི་སྲུང་སྐྱོབ་འོག་ཡོད། ཁྱེད་ཀྱིས་འདིའི་འབྱུང་ཁོངས་ལྟ་ཆོག',
'tooltip-ca-history'             => 'བཟོ་བཅོས་སྔ་མ།',
'tooltip-ca-protect'             => 'ཤོག་ངོས་འདི་སྲུངས་ཤིག',
'tooltip-ca-delete'              => 'ཤོག་ངོས་འདི་དོར་ཅིག',
'tooltip-ca-move'                => 'ཤོག་ངོས་འདི་སྤོར་བ།',
'tooltip-ca-watch'               => 'ལྟ་ཐོ་རུ་འཇུག་པ།',
'tooltip-ca-unwatch'             => 'ཤོག་ངོས་འདི་མཉམ་འཇོག་ཐོ་ལས་ལེན་པ།',
'tooltip-search'                 => 'ལག་ཆ་འཚོལ།',
'tooltip-search-go'              => 'མིང་ཇི་བཞིན་པའི་ཤོག་ངོས་སྟེང་དུ་སྐྱོད་པ།',
'tooltip-search-fulltext'        => 'ཚིག་འདི་འཚོལ།',
'tooltip-p-logo'                 => 'གཙོ་ངོས།',
'tooltip-n-mainpage'             => 'གཙོ་ངོས་ལ་ལྟ་བ།',
'tooltip-n-mainpage-description' => 'གཙོ་ངོས་ལ་ལྟ་བ།',
'tooltip-n-portal'               => 'ལས་འཆར་སྐོར་དང་ཁྱེད་ཀྱིས་ཅི་ཞིག་བྱེད་ནུས་པ། གང་དུ་འཚོལ་དགོས་པ།',
'tooltip-n-currentevents'        => 'ཉེ་བའི་ལས་དོན་གྱི་རྒྱབ་ལྗོངས་གནས་ཚུལ་འཚོལ་བ།',
'tooltip-n-recentchanges'        => 'ཝེ་ཁེ་སྟེང་གི་ཉེ་བའི་བཟོ་བཅོས་ཀྱི་ཐོ་གཞུང་།',
'tooltip-n-randompage'           => 'རང་མོས་ཤོག་ངོས་ཤིག་ལེན་པ།',
'tooltip-n-help'                 => 'གང་དུ་འཚོལ་བའི་གནས།',
'tooltip-t-whatlinkshere'        => 'འདི་ལ་སྦྲེལ་བའི་ཝེ་ཁེ་ཤོག་ངོས་ཡོངས་རྫོགས།',
'tooltip-t-recentchangeslinked'  => 'ངོས་འདི་དང་འབྲེལ་བའི་ཉེ་བའི་བཟོ་བཅོས།',
'tooltip-t-contributions'        => 'བཀོལ་མི་འདིའི་བྱས་རྗེས་སྟོན།',
'tooltip-t-emailuser'            => 'སྤྱོད་མི་འདིར་དྲ་འཕྲིན་སྐུར་བ།',
'tooltip-t-upload'               => 'ཡིག་ཆ་ཡར་འཇུག',
'tooltip-t-specialpages'         => 'དམིཊ་གསལ་ཤོག་ངོས་ཀྱི་ཐོ་གཞུང་།',
'tooltip-t-print'                => 'དཔར་ཐུབ་པའི་མི་འདྲ་ཆོས།',
'tooltip-t-permalink'            => 'རྟག་བརྟན་གྱི་དྲ་བར་འཇུག་པ།',
'tooltip-ca-nstab-main'          => 'ནང་དོན་ཤོག་ངོས་ལ་ལྟ་བ།',
'tooltip-ca-nstab-user'          => 'སྤྱོད་མིའི་ཤོག་ངོས་ལ་ལྟ་བ།',
'tooltip-ca-nstab-special'       => 'དྲ་ངོས་འདི་དམིགས་གསལ་བ་ཡིན་པས་བཟོ་བཅོས་རྒྱག་མི་ཆོག',
'tooltip-ca-nstab-project'       => 'ལས་འཆར་ཤོག་ངོས་ལ་ལྟ་བ།',
'tooltip-ca-nstab-image'         => 'ཡིག་ཆར་ལྟ་བ།',
'tooltip-ca-nstab-template'      => 'དཔེ་པང་ལ་ལྟ་བ།',
'tooltip-ca-nstab-category'      => 'རྣམ་གྲངས་ཤོག་ངོས་སྟོན།',
'tooltip-minoredit'              => 'རྩོམ་སྒྲིག་ཕལ་བར་འཇུག་པ།',
'tooltip-save'                   => 'བཟོ་བཅོས་ཉར་ཚགས་བྱོས།',
'tooltip-preview'                => 'ཉར་ཚགས་ཀྱི་སྔོན་དུ་བཟོ་བཅོས་ལ་བསྐྱར་ཞིབ་གནང་རོགས།',
'tooltip-diff'                   => 'གང་ལ་བཟོ་བཅོས་བྱས་པའི་ཡིག་འབྲུ་སྟོན་པ།',

# Browsing diffs
'previousdiff' => '← རྩོམ་སྒྲིག་རྙིང་བ།',
'nextdiff'     => 'རྩོམ་སྒྲིག་གསར་གྲས། →',

# Special:NewFiles
'ilsubmit' => 'འཚོལ།',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'ཚང་མ།',
'namespacesall' => 'ཡོངས་རྫོགས།',
'monthsall'     => 'ཡོངས་རྫོགས།',

# Multipage image navigation
'imgmultigo' => 'སོང་།!',

# Table pager
'table_pager_limit_submit' => 'སོང་།',

# Watchlist editing tools
'watchlisttools-edit' => 'མཉམ་འཇོག་ཐོར་ལྟ་བ་དང་བསྒྱུར་བཅོས་བྱེད་པ།',
'watchlisttools-raw'  => 'ལྟ་ཐོའི་གོ་རིམ་བཅོས་སྒྲིག',

# Special:SpecialPages
'specialpages' => 'དམིགས་གསལ་ཤོག་ངོས།',

);
