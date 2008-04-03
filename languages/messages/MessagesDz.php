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
'january'   => 'སྤྱི་ཟླ་དང་པ།',
'february'  => 'སྤྱི་ཟླ་གཉིས་པ།',
'march'     => 'སྤྱི་ཟླ་གསུམ་པ།',
'april'     => 'སྤྱི་ཟླ་བཞི་པ།',
'may_long'  => 'སྤྱི་ཟླ་ལྔ་པ།',
'june'      => 'སྤྱི་ཟླ་དྲུག་པ།',
'july'      => 'སྤྱི་ཟླ་བདུན་པ།',
'august'    => 'སྤྱི་ཟླ་བརྒྱད་པ།',
'september' => 'སྤྱི་ཟླ་དགུ་པ།',
'october'   => 'སྤྱི་ཟླ་བཅུ་པ།',
'november'  => 'སྤྱི་ཟླ་བཅུ་གཅིག་པ།',
'december'  => 'སྤྱི་ཟླ་བཅུ་གཉིས་པ།',
'jan'       => 'ཟླ་༡ པ།',
'feb'       => 'ཟླ་༢ པ།',
'mar'       => 'ཟླ་༣ པ།',
'apr'       => 'ཟླ་༤ པ།',
'may'       => 'ཟླ་༥ པ།',
'jun'       => 'ཟླ་༦ པ།',
'jul'       => 'ཟླ་༧ པ།',
'aug'       => 'ཟླ་༨ པ།',
'sep'       => 'ཟླ་༩ པ།',
'oct'       => 'ཟླ་༡༠ པ།',
'nov'       => 'ཟླ་༡༡ པ།',
'dec'       => 'ཟླ་༡༢ པ།',

'tagline'          => '{{SITENAME}} ལས།',
'search'           => 'འཚོལ་ཞིབ།',
'searchbutton'     => 'འཚོལ་ཞིབ།',
'searcharticle'    => 'འགྱོ།',
'printableversion' => 'དཔར་བསྐྲུན་འབད་བཏུབ་པའི་ཐོན་རིམ།',
'edit'             => 'ཞུན་དག།',
'talkpagelinktext' => 'བློ།',
'personaltools'    => 'རང་དོན་ལག་ཆས།',
'talk'             => 'གྲོས་བསྡུར།',
'views'            => 'མཐོང་སྣང་།',
'toolbox'          => 'ལག་ཆས་སྒྲོམ།',
'jumptosearch'     => 'འཚོལ་ཞིབ།',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'   => '{{SITENAME}} གི་སྐོར་ལས།',
'aboutpage'   => 'Project:སྐོར་ལས།',
'mainpage'    => 'མ་ཤོག།',
'privacy'     => 'སྒེར་གསང་སྲིད་བྱུས།',
'privacypage' => 'Project:སྒེར་གསང་སྲིད་བྱུས།',

'retrievedfrom'   => '"$1" ལས་ སླར་འདྲེན་འབད་ཡོདཔ།',
'editsection'     => 'ཞུན་དག།',
'editsectionhint' => 'དབྱེ་ཚན་:$1 ཞུན་དག་འབད།',

# Login and logout pages
'userlogin' => 'ནང་བསྐྱོད་འབད་ / རྩིས་ཐོ་གསརཔ་བཟོ།',

# Edit pages
'summary' => 'བཅུད་དོན།',

# History pages
'last' => 'མཇུག།',

# Diffs
'editundo' => 'འབད་བཤོལ།',

# Search results
'powersearch' => 'མཐོ་རིམ་ཅན་གྱི་འཚོལ་ཞིབ།',

# Upload
'upload' => 'ཡིག་སྣོད་སྐྱེལ་བཙུགས་འབད།',

# Miscellaneous special pages
'specialpages' => 'དམིགས་བསལ་ཤོག་ལེབ།',

# Special:Allpages
'alphaindexline' => '$1 ལས་ $2',

# Block/unblock
'contribslink' => 'ཕན་འདེབས།',

# Tooltip help for the actions
'tooltip-ca-talk'         => 'ནང་དོན་ཤོག་ལེབ་ཀྱི་སྐོར་ལས་གྲོས་བསྡུར།',
'tooltip-search'          => '{{SITENAME}} འཚོལ་ཞིབ་འབད།',
'tooltip-n-mainpage'      => 'མ་ཤོག་ལུ་བལྟ་ཞིབ་འབད།',
'tooltip-n-recentchanges' => 'ཝི་ཀི་ནང་གི་ཕྲལ་གྱི་བསྒྱུར་བཅོས་ཐོ་ཡིག།',
'tooltip-n-randompage'    => 'རིམ་བྲལ་ཤོག་ལེབ་ཅིག་ མངོན་གསལ་འབད།',
'tooltip-n-sitesupport'   => 'ང་བཅས་ལུ་རྒྱབ་སྐྱོར་འབད།',
'tooltip-t-whatlinkshere' => 'ནཱ་ལུ་ འབྲེལ་མཐུད་འབད་བའི་ཝི་ཀི་ཤོག་ལེབ་ག་ར་གི་ཐོ་ཡིག།',
'tooltip-t-upload'        => 'ཡིག་སྣོད་སྐྱེལ་བཙུགས་འབད།',
'tooltip-t-specialpages'  => 'དམིགས་བསལ་ཤོག་ལེབ་ཚུ་གི་ཐོ་ཡིག།',

);
