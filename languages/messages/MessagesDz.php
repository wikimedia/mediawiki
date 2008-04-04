<?php
/** Dzongkha (ཇོང་ཁ)
 *
 * @addtogroup Language
 *
 * @author Ævar Arnfjörð Bjarmason <avarab@gmail.com>
 * @author Tenzin
 * @author Jon Harald Søby
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
# Dates
'sun'           => 'ཟླཝ།',
'mon'           => 'མིགམ།',
'tue'           => 'ལྷགཔ།',
'wed'           => 'ཕུརཔ།',
'thu'           => 'སྤ་སངས།',
'fri'           => 'སྤེནཔ།',
'sat'           => 'ཉིམ།',
'january'       => 'སྤྱི་ཟླ་དང་པ།',
'february'      => 'སྤྱི་ཟླ་གཉིས་པ།',
'march'         => 'སྤྱི་ཟླ་གསུམ་པ།',
'april'         => 'སྤྱི་ཟླ་བཞི་པ།',
'may_long'      => 'སྤྱི་ཟླ་ལྔ་པ།',
'june'          => 'སྤྱི་ཟླ་དྲུག་པ།',
'july'          => 'སྤྱི་ཟླ་བདུན་པ།',
'august'        => 'སྤྱི་ཟླ་བརྒྱད་པ།',
'september'     => 'སྤྱི་ཟླ་དགུ་པ།',
'october'       => 'སྤྱི་ཟླ་བཅུ་པ།',
'november'      => 'སྤྱི་ཟླ་བཅུ་གཅིག་པ།',
'december'      => 'སྤྱི་ཟླ་བཅུ་གཉིས་པ།',
'january-gen'   => 'སྤྱི་ཟླ་ ༡ པའི་',
'february-gen'  => 'སྤྱི་ཟླ་ ༢ པའི་',
'march-gen'     => 'སྤྱི་ཟླ་ ༣ པའི་',
'april-gen'     => 'སྤྱི་ཟླ་ ༤ པའི་',
'may-gen'       => 'སྤྱི་ཟླ་ ༥ པའི་',
'june-gen'      => 'སྤྱི་ཟླ་ ༦ པའི་',
'july-gen'      => 'སྤྱི་ཟླ་ ༧ པའི་',
'august-gen'    => 'སྤྱི་ཟླ་ ༨ པའི་',
'september-gen' => 'སྤྱི་ཟླ་ ༩ པའི་',
'october-gen'   => 'སྤྱི་ཟླ་ ༡༠ པའི་',
'november-gen'  => 'སྤྱི་ཟླ་ ༡༡ པའི་',
'december-gen'  => 'སྤྱི་ཟླ་ ༡༢ པའི་',
'jan'           => 'ཟླ་༡ པ།',
'feb'           => 'ཟླ་༢ པ།',
'mar'           => 'ཟླ་༣ པ།',
'apr'           => 'ཟླ་༤ པ།',
'may'           => 'ཟླ་༥ པ།',
'jun'           => 'ཟླ་༦ པ།',
'jul'           => 'ཟླ་༧ པ།',
'aug'           => 'ཟླ་༨ པ།',
'sep'           => 'ཟླ་༩ པ།',
'oct'           => 'ཟླ་༡༠ པ།',
'nov'           => 'ཟླ་༡༡ པ།',
'dec'           => 'ཟླ་༡༢ པ།',

# Categories related messages
'category_header'        => 'དབྱེ་རིམ་ "$1" ནང་གི་ཤོག་ལེབ་ཚུ།',
'subcategories'          => 'ཡན་ལག་དབྱེ་རིམ།',
'listingcontinuesabbrev' => 'འཕྲོ་མཐུད།',

'about'     => 'སྐོར་ལས།',
'newwindow' => '(ཝིན་ཌོ་གསརཔ་ནང་ ཁ་ཕྱེཝ་ཨིན།)',
'cancel'    => 'ཆ་མེད་གཏང་།',
'mytalk'    => 'ངེ་གི་བློ།',

'returnto'         => '$1 ལུ་ལོག།',
'tagline'          => '{{SITENAME}} ལས།',
'help'             => 'གྲོགས་རམ།',
'search'           => 'འཚོལ་ཞིབ།',
'searchbutton'     => 'འཚོལ་ཞིབ།',
'searcharticle'    => 'འགྱོ།',
'history'          => 'ཤོག་ལེབ་སྤྱོད་ཤུལ།',
'printableversion' => 'དཔར་བསྐྲུན་འབད་བཏུབ་པའི་ཐོན་རིམ།',
'permalink'        => 'རྟག་བརྟན་འབྲེལ་ལམ།',
'edit'             => 'ཞུན་དག།',
'newpage'          => 'ཤོག་ལེབ་གསརཔ།',
'talkpagelinktext' => 'བློ།',
'personaltools'    => 'རང་དོན་ལག་ཆས།',
'talk'             => 'གྲོས་བསྡུར།',
'views'            => 'མཐོང་སྣང་།',
'toolbox'          => 'ལག་ཆས་སྒྲོམ།',
'jumpto'           => 'འཕྲོ་མཐུད་འགྱོ་:',
'jumptonavigation' => 'འཛུལ་འགྱོ་',
'jumptosearch'     => 'འཚོལ་ཞིབ།',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'         => '{{SITENAME}} གི་སྐོར་ལས།',
'aboutpage'         => 'Project:སྐོར་ལས།',
'copyrightpage'     => '{{ns:project}}:འདྲ་བཤུས་འབད་ཆ།',
'currentevents'     => 'ད་ལྟོའི་བྱུང་ལས།',
'currentevents-url' => 'Project:ད་ལྟོའི་བྱུང་ལས།',
'disclaimers'       => 'ཁས་མི་ལེན་པ།',
'disclaimerpage'    => 'Project: སྤྱིར་བཏང་ཁས་མི་ལེན་པ།',
'edithelp'          => 'ཞུན་དག་གྲོགས་རམ།',
'edithelppage'      => 'Help: ཞུན་དག།',
'helppage'          => 'Help:ནང་དོན།',
'mainpage'          => 'མ་ཤོག།',
'portal'            => 'མི་སྡེའི་སྒོ་ར།',
'portal-url'        => 'Project:མི་སྡེའི་སྒོ་ར།',
'privacy'           => 'སྒེར་གསང་སྲིད་བྱུས།',
'privacypage'       => 'Project:སྒེར་གསང་སྲིད་བྱུས།',
'sitesupport'       => 'ཕན་འདེབས།',
'sitesupport-url'   => 'Project:ས་ཁོངས་རྒྱབ་སྐྱོར།',

'retrievedfrom'   => '"$1" ལས་ སླར་འདྲེན་འབད་ཡོདཔ།',
'editsection'     => 'ཞུན་དག།',
'editold'         => 'ཞུན་དག།',
'editsectionhint' => 'དབྱེ་ཚན་:$1 ཞུན་དག་འབད།',
'toc'             => 'ནང་དོན།',
'showtoc'         => 'སྟོན།',
'hidetoc'         => 'སྦ།',
'site-rss-feed'   => '$1 ཨར་ཨེསི་ཨེསི་ འབྱུང་ས།',
'site-atom-feed'  => '$1 ཨེ་ཊོམ་ འབྱུང་ས།',
'page-rss-feed'   => '"$1" ཨར་ཨེསི་ཨེསི་འབྱུང་ས།',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-user'     => 'ལག་ལེན་པའི་ཤོག་ལེབ།',
'nstab-project'  => 'ལས་འགུལ་ཤོག་ལེབ།',
'nstab-image'    => 'ཡིག་སྣོད།',
'nstab-category' => 'དབྱེ་རིམ།',

# General errors
'viewsource' => 'འབྱུང་ས་སྟོན།',

# Login and logout pages
'userlogin'  => 'ནང་བསྐྱོད་འབད་ / རྩིས་ཐོ་གསརཔ་བཟོ།',
'userlogout' => 'ཕྱིར་བསྐྱོད།',

# Edit page toolbar
'bold_sample'     => 'ཚིག་ཡིག་རྒྱགས་པ།',
'bold_tip'        => 'ཚིག་ཡིག་རྒྱགས་པ།',
'italic_sample'   => 'ཨའི་ཊ་ལིཀ་ཚིག་ཡིག།',
'italic_tip'      => 'ཨའི་ཊ་ལིཀ་ཚིག་ཡིག།',
'link_sample'     => 'འབྲེལ་ལམ་མགོ་མིང་།',
'link_tip'        => 'ནང་འཁོད་འབྲེལ་ལམ།',
'extlink_sample'  => 'http://www.example.com འབྲེལ་ལམ མགོ་མིང་།',
'extlink_tip'     => 'ཕྱིའི་འབྲེལ་ལམ་ (http:// prefix སེམས་ཁར་བཞག)',
'headline_sample' => 'གཙོ་དོན་ཚིག་ཡིག།',
'headline_tip'    => 'གནས་རིམ་ ༢ གཙོ་དོན།',
'math_sample'     => 'ནཱ་ལུ་ ཐབས་རྟགས་བཙུགས།',
'math_tip'        => 'ཨང་རྩིས་ཐབས་རྟགས་ (LaTeX)',
'nowiki_sample'   => 'ནཱ་ལུ་ རྩ་སྒྲིག་མ་འབད་བའི་ཚིག་ཡིག་བཙུགས།',
'nowiki_tip'      => 'ཝི་ཀི་རྩ་སྒྲིག་ སྣང་མེད་བཞག།',
'image_tip'       => 'གནས་འདྲེན་ཡིག་སྣོད།',
'sig_tip'         => 'དུས་བཀོད་དང་གཅིག་ཁར་ ཁྱོད་རའི་མིང་རྟགས།',
'hr_tip'          => 'ཐད་སྙོམས་གྲལ་ཐིག་ (ཉུང་སུ་སྦེ་ལག་ལེན་འཐབ)',

# Edit pages
'summary'            => 'བཅུད་དོན།',
'subject'            => 'དོན་ཚན་/གཙོ་དོན།',
'minoredit'          => 'འདི་ གལ་གནད་ཆུང་བའི་ཞུན་དག་ཅིག་ཨིན།',
'watchthis'          => 'ཤོག་ལེབ་འདི་ལུ་བལྟ།',
'showpreview'        => 'སྔོན་ལྟ་སྟོན།',
'showdiff'           => 'བསྒྱུར་བཅོས་ཚུ་སྟོན།',
'anoneditwarning'    => "'''ཉེན་བརྡ:''' ཁྱོད་ཀྱིས་ ནང་བསྐྱོད་མ་འབད་བས།
ཁྱོད་ཀྱི་ ཨའི་པི་ཁ་བྱང་འདི་ ཤོག་ལེབ་ཀྱི་ཞུན་དག་སྤྱོད་ཤུལ་འདི་ནང་ ཐོ་བཀོད་འབད་དེ་བཞག་འོང་།",
'newarticletext'     => "ཁྱོད་ཀྱིས་ ཤོག་ལེབ་ཅིག་ལུ་ ད་ཚུན་མེད་པའི་འབྲེལ་མཐུད་འབད་ཡོདཔ།
ཤོག་ལེབ་གསརཔ་བཟོ་ནི་ལུ་ འོག་གི་སྒྲོམ་ནང་ ཡིག་དཔར་རྐྱབས་ (བརྡ་དོན་ཁ་གསལ་གྱི་དོན་ལུ་ [[{{MediaWiki:Helppage}}|help page]] ལུ་བལྟ་)།
གལ་སྲིད་འཛོལ་ཏེ་ཡར་སོང་པ་ཅིན་ '''རྒྱབ་''' ཨེབ་རྟ་ལུ་ ཨེབ་གཏང་འབད།",
'noarticletext'      => 'ད་ལྟོ་ ཤོག་ལེབ་འདི་ནང་ ཚིག་ཡིག་མེདཔ་ཨིནམ་དང་ ཁྱོད་ཀྱིས་ [[Special:Search/{{PAGENAME}}| ཤོག་ལེབ་མགོ་མིང་འདི་ ]] ཤོག་ལེབ་གཞན་ནང་ལས་འཚོལ་བཏུབ་ ཡང་ན་ [{{fullurl:{{FULLPAGENAME}}|action=edit}} ཤོག་ལེབ་འདི་ ཞུན་དག་འབད་བཏུབ།]',
'editing'            => '$1 ཞུན་དག་འབད་དོ།',
'editingsection'     => '$1 (དབྱེ་ཚན་)འདི་ ཞུན་དག་འབད་ནི།',
'templatesused'      => 'ཤོག་ལེབ་འདི་གུ་ལག་ལེན་འཐབ་ཡོད་པའི་ཊེམ་པེལེཊི:',
'template-protected' => '(ཉེན་སྐྱོབ་འབད་ཡོདཔ།)',

# History pages
'viewpagelogs'     => 'ཤོག་ལེབ་འདི་གི་ལོགསི་སྟོན།',
'currentrev'       => 'ད་ལྟོའི་བསྐྱར་ཞིབ།',
'previousrevision' => '←བསྐྱར་ཞིབ་རྙིངམ།',
'cur'              => 'ཀཱར།',
'last'             => 'མཇུག།',
'histfirst'        => 'རྙིང་ཤོས།',
'histlast'         => 'གསར་ཤོས།',

# Diffs
'history-title'           => '"$1" གི་བསྐྱར་ཞིབ་སྤྱོད་ཤུལ།',
'difference'              => '(བསྐྱར་ཞིབ་བར་ནའི་ཁྱད་པར)',
'lineno'                  => 'གྲལ་ཐིག་ $1:',
'compareselectedversions' => 'སེལ་འཐུ་འབད་ཡོད་པའི་ཐོན་རིམ་ཚུ་ ག་བསྡུར་རྐྱབས།',
'editundo'                => 'འབད་བཤོལ།',

# Search results
'noexactmatch' => "'''མགོ་མིང་ \\\"\$1\\\" ཅན་མའི་ཤོག་ལེབ་མེད།'''
ཁྱོད་ཀྱིས་ [[:\$1|ཤོག་ལེབ་འདི་ གསརཔ་བཟོ་ཚུགས།]]",
'prevn'        => 'ཧེ་མའི་ $1',
'nextn'        => 'ཤུལ་མའི་ $1',
'viewprevnext' => '($1) ($2) ($3) སྟོན།',
'powersearch'  => 'མཐོ་རིམ་ཅན་གྱི་འཚོལ་ཞིབ།',

# Preferences page
'mypreferences' => 'ངེ་གི་དགའ་གདམ།',

# Recent changes
'recentchanges'   => 'འཕྲལ་གྱི་བསྒྱུར་བཅོས',
'rcshowhideminor' => '$1 གལ་གནད་ཆུང་བའི་ཞུན་དག།',
'diff'            => 'ཁྱད་པར།',
'hist'            => 'སྤྱོད་ཤུལ',
'hide'            => 'སྦ།',

# Recent changes linked
'recentchangeslinked'          => 'འབྲེལ་བ་ཅན་གྱི་བསྒྱུར་བཅོས།',
'recentchangeslinked-title'    => '$1 དང་འབྲེལ་བ་ཡོད་པའི་བསྒྱུར་བཅོས་ཚུ།',
'recentchangeslinked-noresult' => 'དུས་བཀོད་ཀྱི་སྐབས་ལུ་ འབྲེལ་མཐུད་ཅན་གྱི་ཤོག་ལེབ་ལུ་བསྒྱུར་བཅོས་མེད།',
'recentchangeslinked-summary'  => "དམིགས་བསལ་ཤོག་ལེབ་འདི་གིས་ འབྲེལ་མཐུད་ཅན་གྱི་ཤོག་ལེབ་གུ་ མཇུག་ཀྱི་བསྒྱུར་བཅོས་ཚུ་ ཐོ་བཀོད་འབདཝ་ཨིན།
ཁྱོད་ཀྱི་བལྟ་ཞིབ་ཐོ་ཡིག་གུ་འི་ཤོག་ལེབ་ཚུ་ '''མངོན་གསལ་ཅན་ཨིན།'''",

# Upload
'upload' => 'ཡིག་སྣོད་སྐྱེལ་བཙུགས་འབད།',

# Image description page
'filehist'          => 'ཡིག་སྣོད་སྤྱོད་ཤུལ།',
'filehist-help'     => 'ཡིག་སྣོད་འདི་ དེ་བསྒང་སྟོན་དོ་བཟུམ་སྦེ་ བལྟ་ནི་གི་དོན་ལུ་ ཚེས་གྲངས་/ཆུ་ཚོད་གུ་ ཨེབ་གཏང་འབད།',
'filehist-current'  => 'ད་ལྟོ།',
'filehist-datetime' => 'ཚེས་གྲངས་/ཆུ་ཚོད།',
'filehist-user'     => 'ལག་ལེན་པ།',
'filehist-filesize' => 'པར་སྣོད་ཀྱི་ཚད།',
'filehist-comment'  => 'བསམ་བཀོད།',
'imagelinks'        => 'འབྲེལ་ལམ།',
'linkstoimage'      => 'འོག་གི་ཤོག་ལེབ་ཚུ་ ཡིག་སྣོད་འདི་དང་འབྲེལ་བ་འདུག:',
'sharedupload'      => 'ཡིག་སྣོད་འདི་རུབ་སྤྱོད་ཅན་གྱི་སྐྱེལ་བཙུགས་ཅིག་ཨིནམ་ལས་ ལས་འགུལ་གཞན་ཚུ་གིས་ལག་ལེན་འཐབ་འོང་།',

# Random page
'randompage' => 'གང་འབྱུང་ཤོག་ལེབ།',

# Miscellaneous special pages
'specialpages' => 'དམིགས་བསལ་ཤོག་ལེབ།',
'move'         => 'སྤོ་བཤུད་འབད།',

# Special:Log
'log' => 'ལོགསི།',

# Special:Allpages
'allpages'       => 'ཤོག་ལེབ་ག་ར།',
'alphaindexline' => '$1 ལས་ $2',
'allpagessubmit' => 'འགྱོ།',

# Watchlist
'watchlist'   => 'ངེ་གི་བལྟ་ཞིབ་ཐོ་ཡིག།',
'mywatchlist' => 'ངེ་གི་བལྟ་ཞིབ་ཐོ་ཡིག།',
'watch'       => 'བལྟ་ཞིབ་འབད།',
'unwatch'     => 'བལྟ་བཤོལ།',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'བལྟ་ཞིབ་འབད་དོ་་་',
'unwatching' => 'བལྟ་ཞིབ་འབད་བཤོལ་དོ་་་',

# Delete/protect/revert
'deletedarticle' => '"[[$1]]" བཏོན་གཏང་ཡོདཔ།',
'rollbacklink'   => 'རྒྱབ་སྒྲིལ།',

# Namespace form on various pages
'blanknamespace' => '(གཙོ་བོ།)',

# Contributions
'contributions' => 'ལག་ལེན་པའི་ཞལ་འདེབས།',
'mycontris'     => 'ངེ་གི་ཞལ་འདེབས།',

# What links here
'whatlinkshere'       => 'ནཱ་ལུ་ ག་ཅི་འབྲེལ་མཐུད་འོང་ནི་མས།',
'whatlinkshere-title' => '$1 དང་འབྲེལ་མཐུད་ཡོད་པའི་ཤོག་ལེབ།',
'linklistsub'         => '(འབྲེལ་ལམ་ཐོ་ཡིག།)',
'linkshere'           => "འོག་གི་ཤོག་ལེབ་ཚུ་ '''[[:$1]]''' ལུ་ འབྲེལ་མཐུད་འབད་ཨིན:",
'nolinkshere'         => "'''[[:$1]]''' ལུ་ ཤོག་ལེབ་འབྲེལ་མཐུད་མིན་འདུག།",
'isredirect'          => 'སླར་ལོག་ཤོག་ལེབ།',
'istemplate'          => 'གྲངས་ཚུད།',
'whatlinkshere-links' => '← འབྲེལ་ལམ།',

# Block/unblock
'blocklink'    => 'བཀག།',
'contribslink' => 'ཕན་འདེབས།',

# Export
'export' => 'ཤོག་ལེབ་ཕྱིར་འདྲེན་འབད།',

# Thumbnails
'thumbnail-more'  => 'ཆེར་བསྐྱེད།',
'thumbnail_error' => 'མཐེ་གཟེར་གསར་བཟོའི་སྐབས་ལུ་འཛོལ་བ་: $1',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'ངེ་གི་ལག་ལེན་པའི་ཤོག་ལེབ།',
'tooltip-pt-mytalk'               => 'ངེ་གི་བློ་ཤོག།',
'tooltip-pt-preferences'          => 'ངེ་གི་དགའ་གདམ།',
'tooltip-pt-mycontris'            => 'ངེ་གི་ཞལ་འདེབས་ཐོ་ཡིག།',
'tooltip-pt-logout'               => 'ཕྱིར་བསྐྱོད།',
'tooltip-ca-talk'                 => 'ནང་དོན་ཤོག་ལེབ་ཀྱི་སྐོར་ལས་གྲོས་བསྡུར།',
'tooltip-ca-edit'                 => 'ཁྱོད་ཀྱིས་ ཤོག་ལེབ་འདི་ཞུན་དག་འབད་བཏུབ། དེ་ མ་སྲུང་པའི་ཧེ་མ་ སྔོན་ལྟའི་ཨེབ་རྟ་འདི་ ལག་ལེན་འཐབ་གནང་།',
'tooltip-ca-addsection'           => 'གྲོས་བསྡུར་འདི་ལུ་ བསམ་བཀོད་ཅིག་ཁ་སྐོང་རྐྱབས།',
'tooltip-ca-viewsource'           => 'ཤོག་ལེབ་འདི་ཉེན་སྐྱོབ་ཅན་ཅིག་ཨིན། དེ་གི་འབྱུང་ས་བལྟ་བཏུབ།',
'tooltip-ca-move'                 => 'ཤོག་ལེབ་འདི་ སྤོ་བཤུད་འབད།',
'tooltip-ca-watch'                => 'ཤོག་ལེབ་འདི་ ཁྱོད་རའི་བལྟ་ཞིབ་ཐོ་ཡིག་ནང་ ཁ་སྐོང་རྐྱབས།',
'tooltip-ca-unwatch'              => 'ཤོག་ལེབ་འདི་ ཁྱོད་རའི་བལྟ་ཞིབ་ཐོ་ཡིག་ནང་ལས་ བཏོན་གཏང་།',
'tooltip-search'                  => '{{SITENAME}} འཚོལ་ཞིབ་འབད།',
'tooltip-n-mainpage'              => 'མ་ཤོག་ལུ་བལྟ་ཞིབ་འབད།',
'tooltip-n-currentevents'         => 'ད་ལྟོའི་འབྱུང་ལས་གུ་ རྒྱབ་གཞིའི་བརྡ་དོན་འཚོལ།',
'tooltip-n-recentchanges'         => 'ཝི་ཀི་ནང་གི་ཕྲལ་གྱི་བསྒྱུར་བཅོས་ཐོ་ཡིག།',
'tooltip-n-randompage'            => 'རིམ་བྲལ་ཤོག་ལེབ་ཅིག་ མངོན་གསལ་འབད།',
'tooltip-n-help'                  => 'འཚོལ་ཞིབ་འབད་སའི་ས་གནས།',
'tooltip-n-sitesupport'           => 'ང་བཅས་ལུ་རྒྱབ་སྐྱོར་འབད།',
'tooltip-t-whatlinkshere'         => 'ནཱ་ལུ་ འབྲེལ་མཐུད་འབད་བའི་ཝི་ཀི་ཤོག་ལེབ་ག་ར་གི་ཐོ་ཡིག།',
'tooltip-t-contributions'         => 'ལག་ལེན་པ་འདི་གི་ཞལ་འདེབས་ཐོ་ཡིག་བལྟ།',
'tooltip-t-upload'                => 'ཡིག་སྣོད་སྐྱེལ་བཙུགས་འབད།',
'tooltip-t-specialpages'          => 'དམིགས་བསལ་ཤོག་ལེབ་ཚུ་གི་ཐོ་ཡིག།',
'tooltip-ca-nstab-user'           => 'ལག་ལེན་པའི་ཤོག་ལེབ་བལྟ།',
'tooltip-ca-nstab-project'        => 'ལས་འགུལ་ཤོག་ལེབ་བལྟ།',
'tooltip-ca-nstab-image'          => 'ཡིག་སྣོད་ཤོག་ལེབ་འདི་སྟོན།',
'tooltip-ca-nstab-category'       => 'དབྱེ་རིམ་ཤོག་ལེབ་སྟོན།',
'tooltip-minoredit'               => 'གལ་གནད་ཆུང་བའི་ཞུན་དག་སྦེ་རྟགས་བཀལ།',
'tooltip-preview'                 => 'ཁྱོད་ཀྱི་བསྒྱུར་བཅོས་ཚུ་མ་སྲུང་པའི་ཧེ་མར་  སྔོན་ལྟ་འབད་གནང།',
'tooltip-diff'                    => 'ཁྱོད་ཀྱིས་ ཚིག་ཡིག་ལུ་ ག་ཅི་བསྒྱུར་བཅོས་འབད་ཡི་ག་སྟོན།',
'tooltip-compareselectedversions' => 'ཤོག་ལེབ་འདི་གི་སེལ་འཐུ་འབད་ཡོད་པའི་ཐོན་རིམ་གཉིས་ཀྱི་བར་ནའི་ཁྱད་པར་ཚུ་ བལྟ།',
'tooltip-watch'                   => 'ཤོག་ལེབ་འདི་ ཁྱོད་རའི་བལྟ་ཞིབ་ཐོ་ཡིག་ནང་ ཁ་སྐོང་རྐྱབས།',

# Browsing diffs
'previousdiff' => '← ཧེ་མའི་ཁྱད་པར།',

# Media information
'file-info-size'       => '($1 × $2 པིག་སེལ་  ཡིག་སྣོད་ཀྱི་ཚད་: $3 མའིམ་དབྱེ་བ་: $4)',
'file-nohires'         => '<small>ཧུམ་ཆ་ལེགས་ཤོམ་མིན་འདུག།</small>',
'show-big-image'       => 'ཧུམ་ཆ་གང་།',
'show-big-image-thumb' => '<small>སྔོན་ལྟའི་ཚད་: $༡ × $༢ པིག་སེལསི་</small>',

# Bad image list
'bad_image_list' => 'རྩ་སྒྲིག་འདི་གཤམ་འཁོད་ལྟར་:

(གྲལ་ཐིག་ * གིས་འགོ་བཙུགས་པའི) ཐོ་ཡིག་དངོས་གྲངས་ཚུ་རྐྱངམ་ཅིག་ བརྩི་འཇོག་ཡོད།
གྲལ་ཐིག་གུ་གི་འབྲེལ་ལམ་དང་པ་འདི་ ཡིག་སྣོད་བྱང་ཉེས་ཅིག་ལུ་ འབྲེལ་མཐུད་ཡོད་དགོ།
གྲལ་ཐིག་ཅོག་འཐད་མི་གུ་ལུ་ ཤུལ་མའི་འབྲེལ་ལམ་ག་ཅི་ཨིན་རུང་ དེའི་གྲངས་སུ་མི་རྩིས་ དེ་ཡང་ གྱལ་རིམ་ནང་ཡོད་པའི་ཡིག་སྣོད་ཤོགལེབ་ཚུ།',

# Metadata
'metadata'          => 'མེ་ཊ་གནས་སྡུད།',
'metadata-expand'   => 'རྒྱ་བསྐྱེད་ཅན་གྱི་རྒྱས་བཤད་སྟོན།',
'metadata-collapse' => 'རྒྱ་བསྐྱེད་ཅན་གྱི་རྒྱས་བཤད་ཚུ་སྦ།',
'metadata-fields'   => 'མེ་ཊ་གནས་སྡུད་ཐིག་ཁྲམ་ ཧྲམ་པའི་སྐབས་ལུ་ འཕྲིན་དོན་འདི་ནང་ ཐོ་བཀོད་འབད་་ཡོད་པའི་ ཨི་ཨེགསི་ཨའི་ཨེཕ་ མེ་ཊ༌གནས་སྡུད་འདི་ གཟུགས་བརྙན་ཤོག་ལེབ་བཀྲམ་སྟོན་གུ་ གྲངས་སུ་བཙུགས་འོང་། 
གཞན་ཚུ་ སྔོན་སྒྲིག་གི་ཐོག་ལས་སྦ་འོང་།
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* focallength', # Do not translate list items

# External editor support
'edit-externally'      => 'ཕྱིའི་གློག་རིམ་ལག་ལེན་འཐབ་ཐོག་ལས་ ཡིག་སྣོད་འདི་ཞུན་དག་འབད།',
'edit-externally-help' => 'བརྡ་དོན་ཁ་གསལ་གྱི་དོན་ལུ་ [http://meta.wikimedia.org/wiki/Help:External_editors setup instructions] ལུ་ལྟ།',

# 'all' in various places, this might be different for inflected languages
'namespacesall' => 'ཆ་མཉམ།',

);
