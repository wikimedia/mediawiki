<?php
/**
  * @addtogroup Language
  */

$namespaceNames = array(
	NS_MEDIA            => 'Media',
	NS_SPECIAL          => 'Special',
	NS_MAIN             => '',
	NS_TALK             => 'Talk',
	NS_USER             => 'User',
	NS_USER_TALK        => 'User_talk',
	# NS_PROJECT set by $wgMetaNamespace
	NS_PROJECT_TALK     => '$1_talk',
	NS_IMAGE            => 'Image',
	NS_IMAGE_TALK       => 'Image_talk',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki_talk',
	NS_TEMPLATE         => 'Template',
	NS_TEMPLATE_TALK    => 'Template_talk',
	NS_HELP             => 'Help',
	NS_HELP_TALK        => 'Help_talk',
	NS_CATEGORY         => 'Category',
	NS_CATEGORY_TALK    => 'Category_talk'
);

$namespaceAliases = array(
	"特殊"  => NS_SPECIAL,
	"对话" => NS_TALK, 
	"用户" => NS_USER,
	"用户对话" => NS_USER_TALK,
	# This has never worked so it's unlikely to annoy anyone if I disable it -- TS
	#"{{SITENAME}}_对话" => NS_PROJECT_TALK
	"图像" => NS_IMAGE,
	"图像对话" => NS_IMAGE_TALK,
);

$skinNames = array(
	'standard' => "标准",
	'nostalgia' => "怀旧",
	'cologneblue' => "科隆香水蓝"
);

$extraUserToggles = array(
	'nolangconversion',
);
$datePreferences = array(
	'default',
	'ISO 8601',
);
$defaultDateFormat = 'zh';
$dateFormats = array(
	'zh time' => 'H:i',
	'zh date' => 'Y年n月j日 (l)',
	'zh both' => 'Y年n月j日 (D) H:i',
);

$messages = array(
# User preference toggles
'tog-underline'               => '下划链接',
'tog-highlightbroken'         => '毁坏链接格式<a href="" class="new">像这样</a> (或者像这个<a href="" class="internal">?</a>)',
'tog-justify'                 => '段落对齐',
'tog-hideminor'               => '最近更改中隐藏细微修改',
'tog-usenewrc'                => '最近更改增强（只适用部分浏览器）',
'tog-numberheadings'          => '标题自动编号',
'tog-showtoolbar'             => 'Show edit toolbar',
'tog-editondblclick'          => '双击编辑页面（Javascript）',
'tog-editsection'             => '允许通过点击[编辑]链接编辑段落',
'tog-editsectiononrightclick' => '允许右击标题编辑段落(JavaScript)',
'tog-showtoc'                 => '显示目录<br />(针对一页超过3个标题的文章)',
'tog-rememberpassword'        => '下次登陆记住密码',
'tog-editwidth'               => '编辑栏位宽度',
'tog-watchdefault'            => '监视新的以及更改过的文章',
'tog-minordefault'            => '细微编辑为默认设置',
'tog-previewontop'            => '在编辑框上方显示预览',

# Dates
'sunday'        => '星期日',
'monday'        => '星期一',
'tuesday'       => '星期二',
'wednesday'     => '星期三',
'thursday'      => '星期四',
'friday'        => '星期五',
'saturday'      => '星期六',
'sun'           => '日',
'mon'           => '一',
'tue'           => '二',
'wed'           => '三',
'thu'           => '四',
'fri'           => '五',
'sat'           => '六',
'january'       => '1月',
'february'      => '2月',
'march'         => '3月',
'april'         => '4月',
'may_long'      => '5月',
'june'          => '6月',
'july'          => '7月',
'august'        => '8月',
'september'     => '9月',
'october'       => '10月',
'november'      => '11月',
'december'      => '12月',
'january-gen'   => '一月',
'february-gen'  => '二月',
'march-gen'     => '三月',
'april-gen'     => '四月',
'may-gen'       => '五月',
'june-gen'      => '六月',
'july-gen'      => '七月',
'august-gen'    => '八月',
'september-gen' => '九月',
'october-gen'   => '十月',
'november-gen'  => '十一月',
'december-gen'  => '十二月',
'jan'           => '1月',
'feb'           => '2月',
'mar'           => '3月',
'apr'           => '4月',
'may'           => '5月',
'jun'           => '6月',
'jul'           => '7月',
'aug'           => '8月',
'sep'           => '9月',
'oct'           => '10月',
'nov'           => '11月',
'dec'           => '12月',

# Bits of text used by many pages
'categories'      => '页面分类',
'pagecategories'  => '页面分类',
'category_header' => '类别”$1“中的条目',
'subcategories'   => '附分类',

'about'         => '关于',
'article'       => '条目',
'cancel'        => '取消',
'qbfind'        => '寻找',
'qbbrowse'      => '浏览',
'qbedit'        => '编辑',
'qbpageoptions' => '页面选项',
'qbpageinfo'    => '页面信息',
'qbmyoptions'   => '我的选项',
'mypage'        => '我的页面',
'mytalk'        => '我的对话页',
'anontalk'      => '该IP的对话页',
'navigation'    => '导航',

'errorpagetitle'    => '错误',
'returnto'          => '返回到$1.',
'help'              => '帮助',
'search'            => '搜索',
'searchbutton'      => '搜索',
'go'                => '进入',
'searcharticle'     => '进入',
'history'           => '较早版本',
'printableversion'  => '可打印版',
'edit'              => '编辑',
'editthispage'      => '编辑本页',
'deletethispage'    => '删除本页',
'protectthispage'   => '保护本页',
'unprotectthispage' => '解除保护',
'newpage'           => '新页面',
'talkpage'          => '讨论本页',
'postcomment'       => '发表评论',
'articlepage'       => '查看文章',
'talk'              => '讨论',
'toolbox'           => '工具',
'userpage'          => '查看用户页',
'projectpage'       => '查看计划页面',
'imagepage'         => '查看图像页面',
'viewtalkpage'      => '查看讨论',
'otherlanguages'    => '其它语言',
'redirectedfrom'    => '(重定向自$1)',
'lastmodifiedat'    => '最后更改$2, $1.', # $1 date, $2 time
'viewcount'         => '本页面已经被浏览$1次。',
'protectedpage'     => '被保护页',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'         => '关于{{SITENAME}}',
'aboutpage'         => '{{ns:project}}:关于',
'bugreports'        => '错误报告',
'bugreportspage'    => '{{ns:project}}:错误报告',
'copyrightpagename' => '{{SITENAME}}版权',
'copyrightpage'     => '{{ns:project}}:版权信息',
'currentevents'     => '新闻动态',
'edithelp'          => '编辑帮助',
'edithelppage'      => '{{ns:project}}:如何编辑页面',
'faq'               => '常见问题解答',
'faqpage'           => '{{ns:project}}:常见问题解答',
'helppage'          => '{{ns:project}}:帮助',
'mainpage'          => '首页',
'portal'            => '社区',

'ok'              => 'OK',
'retrievedfrom'   => '取自"$1"',
'newmessageslink' => '新信息',
'editsection'     => '编辑',
'editold'         => '编辑',
'toc'             => '目录',
'showtoc'         => '显示',
'hidetoc'         => '隐藏',

# Short words for each namespace, by default used in the 'article' tab in monobook
'nstab-main'      => '条目',
'nstab-user'      => '用户页面',
'nstab-special'   => '特殊',
'nstab-project'   => '关于',
'nstab-image'     => '图像',
'nstab-mediawiki' => '界面',
'nstab-template'  => '模板',
'nstab-help'      => '帮助',
'nstab-category'  => '分类',

# Main script and global functions
'nosuchaction'      => '没有这个命令',
'nosuchactiontext'  => 'URL请求的命令无法被 {{SITENAME}} 软件识别。',
'nosuchspecialpage' => '没有这个特殊页。',
'nospecialpagetext' => '您请求的页面无法被 {{SITENAME}} 软件识别。',

# General errors
'error'           => '错误',
'databaseerror'   => '数据库错误',
'dberrortext'     => '数据库指令语法错误。
这可能是由于非法搜索指令所引起的(见 $5),
也可能是由于软件自身的错误所引起。
最后一次数据库指令是：
<blockquote><tt>$1</tt></blockquote>
来自于函数 "<tt>$2</tt>"。
MySQL返回错误 "<tt>$3: $4</tt>"。',
'noconnect'       => '无法在 $1上连接数据库',
'nodb'            => '无法选择数据库 $1',
'readonly'        => '数据库禁止访问',
'enterlockreason' => '请输入禁止访问原因, 包括估计重新开放的时间',
'readonlytext'    => '{{SITENAME}}数据库目前禁止输入新内容及更改，
这很可能是由于数据库正在维修，之后即可恢复。
管理员有如下解释:
<p>$1',
'missingarticle'  => '数据库找不到文字"$1"。

<p>通常这是由于修订历史页上过时的链接到已经被删除的页面所导致的。

<p>如果情况不是这样，您可能找到了软件内的一个臭虫。
请记录下URL地址，并向管理员报告。',
'internalerror'   => '内部错误',
'filecopyerror'   => '无法复制文件"$1"到"$2"。',
'filerenameerror' => '无法重命名文件"$1" 到"$2"。',
'filedeleteerror' => '无法删除文件 "$1"。',
'filenotfound'    => '找不到文件 "$1"。',
'unexpected'      => '不正常值: "$1"="$2"。',
'formerror'       => '错误：无法提交表单',
'badarticleerror' => '无法在本页上进行此项操作。',
'cannotdelete'    => '无法删除选定的页面或图像（它可能已经被其他人删除了）。',
'badtitle'        => '错误的标题',
'badtitletext'    => '所请求页面的标题是无效的、不存在，跨语言或跨wiki链接的标题错误。',
'perfdisabled'    => '抱歉！由于此项操作有可能造成数据库瘫痪，目前暂时无法使用。',
'perfdisabledsub' => '这里是自$1的复制版本：', # obsolete?

# Login and logout pages
'logouttitle'                => '用户退出',
'logouttext'                 => '您现在已经退出。
您可以继续以匿名方式使用Wikipeida，或再次以相同或不同用户身份登录。',
'welcomecreation'            => '<h2>欢迎，$1!</h2><p>您的帐号已经建立，不要忘记设置{{SITENAME}}个人参数。',
'loginpagetitle'             => '用户登录',
'yourname'                   => '您的用户名',
'yourpassword'               => '您的密码',
'yourpasswordagain'          => '再次输入密码',
'remembermypassword'         => '下次登录记住密码。',
'loginproblem'               => '<b>登录有问题。</b><br />再试一次！',
'alreadyloggedin'            => '<strong>用户$1，您已经登录了!</strong><br />',
'login'                      => '登录',
'userlogin'                  => '用户登录',
'logout'                     => '退出',
'userlogout'                 => '用户退出',
'createaccount'              => '创建新帐号',
'createaccountmail'          => '通过eMail',
'badretype'                  => '你所输入的密码并不相同。',
'userexists'                 => '您所输入的用户名已有人使用。请另选一个。',
'youremail'                  => '您的电子邮件*',
'yourrealname'               => '真实姓名*',
'yourlanguage'               => '界面语言',
'yourvariant'                => '字体变换',
'yournick'                   => '绰号（签名时用）',
'prefs-help-realname'        => '*<strong>真实姓名</strong>（可选）：用以对您的贡献署名。<br />',
'loginerror'                 => '登录错误',
'prefs-help-email'           => '*<strong>点子邮件</strong>（可选）：让他人通过网站在不知道您的电子邮件地址的情况下通过电子邮件与您联络，以及通过电子邮件取得遗忘的密码。',
'noname'                     => '你没有输入一个有效的用户名。',
'loginsuccesstitle'          => '登录成功',
'loginsuccess'               => '你现在以 "$1"的身份登录{{SITENAME}}。',
'nosuchuser'                 => '找不到用户 "$1"。
检查您的拼写，或者用下面的表格建立一个新帐号。',
'wrongpassword'              => '您输入的密码错误，请再试一次。',
'mailmypassword'             => '将新密码寄给我',
'passwordremindertitle'      => '{{SITENAME}}密码提醒',
'passwordremindertext'       => '有人（可能是您，来自IP地址$1)要求我们将新的{{SITENAME}}登录密码寄给你。
用户 "$2" 的密码现在是 "$3"。
请立即登录并更改密码。',
'noemail'                    => '用户"$1"没有登记电子邮件地址。',
'passwordsent'               => '用户"$1"的新密码已经寄往所登记的电子邮件地址。
请在收到后再登录。',
'acct_creation_throttle_hit' => '对不起，您已经注册了$1账号。你不能再注册了。',

# Edit pages
'summary'              => '简述',
'subject'              => '主题',
'minoredit'            => '这是一个小修改',
'watchthis'            => '监视本页',
'savearticle'          => '保存本页',
'preview'              => '预览',
'showpreview'          => '显示预览',
'blockedtitle'         => '用户被封',
'blockedtext'          => "您的用户名或IP地址已被$1封。
理由是：<br />'''$2'''<p>您可以与$1向其他任何[[{{ns:project}}:管理员|管理员]]询问。",
'whitelistedittitle'   => '登录后才可编辑',
'whitelistedittext'    => '您必须先[[Special:Userlogin|登录]]才可编辑页面。',
'whitelistreadtitle'   => '登录后才可阅读',
'whitelistreadtext'    => '您必须先[[Special:Userlogin|登录]]才可阅读页面。',
'whitelistacctitle'    => '您被禁止建立帐号',
'whitelistacctext'     => '在本Wiki中建立帐号您必须先[[Special:Userlogin|登录]]并拥有相关权限。',
'accmailtitle'         => '密码寄出',
'accmailtext'          => "'$1'的密码已经寄到$2。",
'newarticle'           => '（新）',
'newarticletext'       => '您从一个链接进入了一个并不存在的页面。
要创建该页面，请在下面的编辑框中输入内容（详情参见{{ns:project}}:帮助|帮助页面]]）。
如果您不小心来到本页面，直接点击您浏览器中的“返回”按钮。',
'anontalkpagetext'     => "---- ''这是一个还未建立帐号的匿名用户的对话页。我们因此只能用[[IP地址]]来与他／她联络。该IP地址可能由几名用户共享。如果您是一名匿名用户并认为本页上的评语与您无关，请[[Special:Userlogin|创建新帐号或登录]]以避免在未来于其他匿名用户混淆。''",
'noarticletext'        => '（本页目前没有内容）',
'clearyourcache'       => "'''注意：''' 保存设置后，要清掉浏览器的缓存才能生效：'''Mozilla:''' ''Ctrl-Shift-R'', '''Internet Explorer:''' ''Ctrl-F5'', '''Safari:''' ''Cmd-Shift-R'', '''Konqueror:''' ''F5''。",
'updated'              => '（已更新）',
'note'                 => '<strong>注意：</strong>',
'previewnote'          => '请记住这只是预览，内容还未保存！',
'previewconflict'      => '这个预览显示了上面文字编辑区中的内容。它将在你选择保存后出现。',
'editing'              => '正在编辑$1',
'editinguser'          => '正在编辑$1',
'editingsection'       => '正在编辑$1 (段落)',
'editingcomment'       => '正在编辑$1 (评论)',
'editconflict'         => '编辑冲突：$1',
'explainconflict'      => '有人在你开始编辑后更改了页面。
上面的文字框内显示的是目前本页的内容。
你所做的修改显示在下面的文字框中。
你应当将你所做的修改加入现有的内容中。
<b>只有</b>在上面文字框中的内容会在你点击"保存页面"后被保存。<br />',
'yourtext'             => '您的文字',
'storedversion'        => '已保存版本',
'editingold'           => '<strong>警告：你正在编辑的是本页的旧版本。
如果你保存它的话，在本版本之后的任何修改都会丢失。</strong>',
'yourdiff'             => '差别',
'longpagewarning'      => '<strong>警告：本页长度达$1KB；一些浏览器将无法编辑长过32KB的文章。请考虑将本文切割成几个小段落。</strong>',
'readonlywarning'      => '<strong>警告：数据库被锁以进行维护，所以您目前将无法保存您的修改。您或许希望先将本断文字复制并保存到文本文件，然后等一会儿再修改。</strong>',
'protectedpagewarning' => '<strong>警告：本页已经被保护，只有拥有管理员权限的用户才可修改。请确认您遵守
[[Project:Protected_page_guidelines|保护页面守则]].</strong>',

# History pages
'revhistory'      => '修订历史',
'nohistory'       => '没有本页的修订记录。',
'revnotfound'     => '没有找到修订记录',
'revnotfoundtext' => '您请求的更早版本的修订记录没有找到。
请检查您请求本页面用的 URL 是否正确。',
'loadhist'        => '载入页面修订历史',
'currentrev'      => '当前修订版本',
'revisionasof'    => '$1的修订版本',
'cur'             => '当前',
'next'            => '后继',
'last'            => '先前',
'orig'            => '初始',
'histlegend'      => '说明：(当前)指与当前修订版本比较；(先前)指与前一个修订版本比较，小 指细微修改。',

# Diffs
'difference'  => '（修订版本间差异）',
'loadingrev'  => '载入修订版本比较',
'lineno'      => '第 $1 行：',
'editcurrent' => '编辑本页的当前修订版本',

# Search results
'searchresults'         => '搜索结果',
'searchresulttext'      => '有关搜索{{SITENAME}}的更多详情,参见[[Project:搜索|搜索{{SITENAME}}]]。',
'searchsubtitle'        => '查询"[[:$1]]"',
'searchsubtitleinvalid' => '查询"$1"',
'badquery'              => '搜索查询不正确',
'badquerytext'          => '我们无法处理您的查询。
这可能是由于您试图搜索一个短于3个字母的外文单词，
或者您错误地输入了搜索项，例如"汽车和和火车"。
请再尝试一个新的搜索项。',
'matchtotals'           => '搜索项"$1"与$2条文章的题目相符，和$3条文章相符。',
'noexactmatch'          => '没有文章与搜索项完全匹配，请尝试完整文字搜索。',
'titlematches'          => '文章题目相符',
'notitlematches'        => '没有找到匹配文章题目',
'textmatches'           => '文章内容相符',
'notextmatches'         => '没有文章内容匹配',
'prevn'                 => '先前$1',
'nextn'                 => '之后$1',
'viewprevnext'          => '查看 ($1) ($2) ($3).',
'showingresults'        => '下面显示<b>$1</b>条结果，从第<b>$2</b>条开始',
'nonefound'             => '<strong>注意：</strong>失败的搜索往往是由于试图搜索诸如“的”或“和”之类的常见字所引起。',
'powersearch'           => '搜索',
'powersearchtext'       => '
搜索名字空间：<br />$1<br />$2列出重定向页面；搜索$3 $9',
'searchdisabled'        => '<p>{{SITENAME}}内部搜索功能由于高峰时段服务器超载而停止使用。
您可以暂时通过
<a href="http://google.com/">google</a>搜索{{SITENAME}}。
谢谢您的耐心。',

# Preferences page
'preferences'             => '参数设置',
'prefsnologin'            => '还未登录',
'prefsnologintext'        => '您必须先[[Special:Userlogin|登录]]才能设置个人参数。',
'prefsreset'              => '参数重新设置。',
'qbsettings'              => '快速导航条设置',
'qbsettings-none'         => '无',
'qbsettings-fixedleft'    => '左侧固定',
'qbsettings-fixedright'   => '右侧固定',
'qbsettings-floatingleft' => '左侧漂移',
'changepassword'          => '更改密码',
'skin'                    => '皮肤',
'math'                    => '数学显示',
'math_failure'            => '无法解析',
'math_unknown_error'      => '未知错误',
'math_unknown_function'   => '未知函数',
'math_lexing_error'       => '句法错误',
'math_syntax_error'       => '语法错误',
'prefs-personal'          => '用户数据',
'prefs-rc'                => '最近更新',
'prefs-misc'              => '杂项',
'saveprefs'               => '保存参数设置',
'resetprefs'              => '重设参数',
'oldpassword'             => '旧密码',
'newpassword'             => '新密码',
'retypenew'               => '重复新密码',
'textboxsize'             => '文字框尺寸',
'rows'                    => '行',
'columns'                 => '列',
'searchresultshead'       => '搜索结果设定',
'resultsperpage'          => '每页显示链接数',
'contextlines'            => '每链接行数',
'contextchars'            => '每行字数',
'stubthreshold'           => 'stub显示基本限制',
'recentchangescount'      => '最近更改页行数',
'savedprefs'              => '您的个人参数设置已经保存。',
'timezonelegend'          => '时区',
'timezonetext'            => '输入当地时间与服务器时间(UTC)的时差。',
'localtime'               => '当地时间',
'timezoneoffset'          => '差',

# Recent changes
'recentchanges'     => '最近更改',
'recentchangestext' => '本页跟踪{{SITENAME}}内最新的更改。
[[{{ns:project}}:欢迎，新来者|欢迎，新来者]]！
请参见这些页面：[[{{ns:project}}:常见问题解答|{{SITENAME}}常见问题解答]]、
[[{{ns:project}}:守则与指导|{{SITENAME}}守则]]
（特别是[[{{ns:project}}:命名常规|命名常规]]、
[[{{ns:project}}:中性的观点|中立观点]]）
和[[{{ns:project}}:最常见失礼行为|最常见失礼行为]]。

如果您希望 {{SITENAME}} 成功，那么请您不要增加受其它[[{{ns:project}}:版权信息|版权]]
限制的材料，这一点将非常重要。相关的法律责任会伤害本项工程，所以请不要这样做。
此外请参见',
'rcnote'            => '下面是最近<strong>$2</strong>天内最新的<strong>$1</strong>次改动。',
'rcnotefrom'        => '下面是自<b>$2</b>（最多显示<b>$1</b>）。',
'rclistfrom'        => '显示自$1以来的新更改',
'rclinks'           => '显示最近 $2 天内最新的 $1 次改动。<br />$3',
'diff'              => '差异',
'hist'              => '历史',
'hide'              => '隐藏',
'show'              => '显示',
'minoreditletter'   => '小',
'newpageletter'     => '新',

# Recent changes linked
'recentchangeslinked' => '链出更改',

# Upload
'upload'            => '上载文件',
'uploadbtn'         => '上载文件',
'reupload'          => '重新上载',
'reuploaddesc'      => '返回上载表单。',
'uploadnologin'     => '未登录',
'uploadnologintext' => '您必须先[[Special:Userlogin|登录]]
才能上载文件。',
'uploaderror'       => '上载错误',
'uploadtext'        => "'''停止！'''在您上载之前，请先阅读并遵守{{SITENAME}}
[[Project:Image use policy|图像使用守则]]。

如果您要查看或搜索之前上载的图像，
请到[[Special:Imagelist|已上载图像列表]].
所有上载与删除行为都被记录在
[[Project:上载纪录|上载纪录]]内。

使用下面的表单来上载用在条目内新的图像文件。
在绝大多数浏览器内，你会看到一个\"浏览...\"按钮，点击它后就会跳出一个打开文件对话框。
选择一个文件后文件名将出现在按钮旁边的文字框中。
您也必须点击旁边的复选框确认您所上载的文件并没有违反相关版权法律。
点击\"上载\" 按钮完成上载程序。
如果您使用的是较慢的网络连接的话那么这个上载过程会需要一些时间。

我们建议照相图片使用JPEG格式，绘图及其他图标图像使用PNG格式，音像则使用OGG格式。
请使用具有描述性的语言来命名您的文件以避免混乱。
要在文章中加入图像，使用以下形式的连接：
'''<nowiki>[[</nowiki>{{ns:image}}:file.jpg]]</nowiki>'''或者
'''<nowiki>[[</nowiki>{{ns:image}}:file.png|解释文字]]'''
或'''<nowiki>[[</nowiki>{{ns:media}}:file.ogg]]'''来连接音像文件。

请注意在{{SITENAME}}页面中，其他人可能会为了百科全书的利益而编辑或删除您的上载文件，
而如果您滥用上载系统，您则有可能被禁止使用上载功能。",
'uploadlog'         => '上载纪录',
'uploadlogpage'     => '上载纪录',
'uploadlogpagetext' => '以下是最近上载的文件的一览表。
所有显示的时间都是服务器时间（UTC）。
<ul>
</ul>',
'filename'          => '文件名',
'filedesc'          => '简述',
'uploadedfiles'     => '已上载文件',
'minlength'         => '图像名字必须至少有三个字母。',
'badfilename'       => '图像名已被改为"$1"。',
'successfulupload'  => '上载成功',
'fileuploaded'      => '文件"$1"上载成功。
请根据连接($2)到图像描述页添加有关文件信息，例如它的来源，在何时由谁创造，
以及其他任何您知道的关于改图像的信息。',
'uploadwarning'     => '上载警告',
'savefile'          => '保存文件',
'uploadedimage'     => '已上载"[[$1]]"',

# Image list
'imagelist'           => '图像列表',
'imagelisttext'       => '以下是按$2排列的$1幅图像列表。',
'getimagelist'        => '正在获取图像列表',
'ilsubmit'            => '搜索',
'showlast'            => '显示按$2排列的最后$1幅图像。',
'byname'              => '名字',
'bydate'              => '日期',
'bysize'              => '大小',
'imgdelete'           => '删',
'imgdesc'             => '述',
'imglegend'           => '说明：(述) = 显示/编辑图像描述页。',
'imghistory'          => '图像历史',
'revertimg'           => '复',
'deleteimg'           => '删',
'deleteimgcompletely' => '删',
'imghistlegend'       => 'egend: (现) = 目前的图像，(删) = 删除旧版本，
(复) = 恢复到旧版本。
<br /><i>点击日期查看当天上载的图像</i>.',
'imagelinks'          => '图像链接',
'linkstoimage'        => '以下页面连接到本图像：',
'nolinkstoimage'      => '没有页面连接到本图像。',

# Statistics
'statistics'    => '统计',
'sitestats'     => '站点统计',
'userstats'     => '用户统计',
'sitestatstext' => '数据库中共有 <b>$1</b> 页页面；
其中包括对话页、关于 {{SITENAME}} 的页面、最少量的"stub"页、重定向的页面，
以及未达到条目质量的页面；除此之外还有 <b>$2</b> 页可能是合乎标准的条目。
<p>从系统软件升级以来，全站点共有页面浏览 <b>$3</b> 次，
页面编辑 <b>$4</b> 次，每页平均编辑 <b>$5</b> 次，
各次编辑后页面的每个版本平均浏览 <b>$6</b> 次。',

'disambiguations'     => '消含糊页',
'disambiguationspage' => '{{ns:project}}:Links_to_disambiguating_pages',

'doubleredirects'     => '双重重定向',
'doubleredirectstext' => '<b>请注意：</b> 这列表可能包括不正确的反应。
这通常表示在那页面第一个#REDIRECT之下还有文字。<br />

每一行都包含到第一跟第二个重定向页的链接，以及第二个重定向页的第一行文字，
通常显示的都会是\“真正\” 的目标页面，也就是第一个重定向页应该指向的条目。',

'brokenredirects'     => '损坏的重定向页',
'brokenredirectstext' => '以下的重定向页指向的是不存在的条目。',

# Miscellaneous special pages
'nbytes'           => '$1字节',
'nlinks'           => '$1个链接',
'nviews'           => '$1次浏览',
'lonelypages'      => '孤立页面',
'unusedimages'     => '未使用图像',
'popularpages'     => '热点条目',
'wantedpages'      => '待撰页面',
'allpages'         => '所有页面',
'randompage'       => '随机页面',
'shortpages'       => '短条目',
'longpages'        => '长条目',
'listusers'        => '用户列表',
'specialpages'     => '特殊页面',
'spheading'        => '特殊页面',
'rclsub'           => '（从 "$1"链出的页面）',
'newpages'         => '新页面',
'ancientpages'     => '老条目',
'intl'             => '跨语言链接',
'movethispage'     => '移动本页',
'unusedimagestext' => '<p>请注意其他网站（例如其他语言版本的{{SITENAME}}）
有可能直接链接本图像，所以这里列出的图像有可能依然被使用。',

# Book sources
'booksources' => '站外书源',

'categoriespagetext' => '以下列出所有的页面分类。',
'alphaindexline'     => '$1 到 $2',

# Special:Allpages
'allarticles'    => '所有条目',
'allinnamespace' => '所有 $1 名字空间的条目',
'allpagesprev'   => '上一页',
'allpagesnext'   => '下一页',
'allpagessubmit' => '提交',

# E-mail user
'mailnologin'     => '无电邮地址',
'mailnologintext' => '您必须先[[Special:Userlogin|登录]]
并在[[Special:Preferences|参数设置]]
中有一个有效的e-mail地址才可以电邮其他用户。',
'emailuser'       => 'E-mail该用户',
'emailpage'       => 'E-mail用户',
'emailpagetext'   => '如果该用户已经在他或她的参数设置页中输入了有效的e-mail地址，以下的表格将寄一个信息给该用户。您在您参数设置中所输入的e-mail地址将出现在邮件“发件人”一栏中，这样该用户就可以回复您。',
'noemailtitle'    => '无e-mail地址',
'noemailtext'     => '该用户还没有指定一个有效的e-mail地址，
或者选择不接受来自其他用户的e-mail。',
'emailfrom'       => '发件人',
'emailto'         => '收件人',
'emailsubject'    => '主题',
'emailmessage'    => '信息',
'emailsend'       => '发送',
'emailsent'       => 'E-mail已发送',
'emailsenttext'   => '您的e-mail已经发出。',

# Watchlist
'watchlist'          => '监视列表',
'mywatchlist'          => '监视列表',
'nowatchlist'        => '您的监视列表为空。',
'watchnologin'       => '未登录',
'watchnologintext'   => '您必须先[[Special:Userlogin|登录]]
才能更改您的监视列表',
'addedwatch'         => '加入到监视列表',
'addedwatchtext'     => '本页（“$1”）已经被加入到您的[[Special:Watchlist|监视列表]]中。
未来有关它或它的对话页的任何修改将会在本页中列出，
而且还会在[[Special:Recentchanges|最近更改列表]]中
以<b>粗体</b>形式列出。

如果您之后想将该页面从监视列表中删除，点击导航条中的“停止监视”链接。',
'removedwatch'       => '停止监视',
'removedwatchtext'   => '页面“$1”已经从您的监视页面中移除。',
'watch'              => '监视',
'watchthispage'      => '监视本页',
'unwatchthispage'    => '停止监视',
'notanarticle'       => '不是条目',
'watchnochange'      => '在显示的时间段内您所监视的页面没有更改。',
'watchdetails'       => '($1个页面（不含对话页）被监视；
 	 总共$2个页面被编辑；
 	 $3...
 	 [$4 显示并编辑完整列表].)',
'watchmethod-recent' => '检查被监视页面的最近编辑',
'watchmethod-list'   => '检查最近编辑的被监视页面',
'removechecked'      => '将被选页面从监视列表中移除',
'watchlistcontains'  => '您的监视列表包含$1个页面。',
'watcheditlist'      => '这里是您所监视的页面的列表。要移除某一页面，只要选择该页面然后点击”移除页面“按钮。',
'removingchecked'    => '移除页面...',
'couldntremove'      => "无法移除'$1'...",
'iteminvalidname'    => "页面'$1'错误，无效命名...",
'wlnote'             => '以下是最近<b>$2</b>小时内的最后$1次修改。',

# Delete/protect/revert
'deletepage'        => '删除页面',
'confirm'           => '确认',
'confirmdelete'     => '确认删除',
'deletesub'         => '（正在删除“$1”）',
'confirmdeletetext' => '您即将从数据库中永远删除一个页面或图像以及其历史。
请确定您要进行此项操作，并且了解其后果，同时您的行为符合[[{{ns:project}}:守则与指导]]。',
'actioncomplete'    => '操作完成',
'deletedtext'       => '“$1”已经被删除。
最近删除的纪录请参见$2。',
'deletedarticle'    => '已删除“$1”',
'dellogpage'        => '删除纪录',
'dellogpagetext'    => '以下是最近删除的纪录列表。
所有的时间都是使用服务器时间(UTC)。
<ul>
</ul>',
'deletionlog'       => '删除纪录',
'reverted'          => '恢复到早期版本',
'deletecomment'     => '删除理由',
'imagereverted'     => '恢复到早期版本操作完成。',
'rollback'          => 'Roll back',
'rollbacklink'      => 'rollback',
'cantrollback'      => '无法恢复编辑；最后的巩县者是本文的唯一作者。',
'revertpage'        => '恢复到$1的最后一次编辑',

# Undelete
'undelete'          => '恢复被删页面',
'undeletepage'      => '浏览及恢复被删页面',
'undeletepagetext'  => '以下页面已经被删除，但依然在档案中并可以被恢复。
档案库可能被定时清理。',
'undeleterevisions' => '$1版本存档',
'undeletehistory'   => '如果您恢复了该页面，所有版本都会被恢复到修订历史中。
如果本页删除后有一个同名的新页面建立，
被恢复的版本将会称为较新的历史，而新页面的当前版本将无法被自动复原。',
'undeletebtn'       => '恢复！',
'undeletedarticle'  => '已经恢复“$1”',

# Contributions
'contributions' => '用户贡献',
'mycontris'     => '我的贡献',
'contribsub'    => '为$1',
'nocontribs'    => '没有找到符合特征的更改。',
'ucnote'        => '以下是该用户最近<b>$2</b>天内的最后<b>$1</b>次修改。',
'uclinks'       => '参看最后$1次修改；参看最后$2天。',
'uctop'         => ' (顶)',

# What links here
'whatlinkshere' => '链入页面',
'notargettitle' => '无目标',
'notargettext'  => '您还没有指定一个目标页面或用户以进行此项操作。',
'linklistsub'   => '(链接列表)',
'linkshere'     => '以下页面链接到这里：',
'nolinkshere'   => '没有页面链接到这里。',
'isredirect'    => '重定向页',

# Block/unblock
'blockip'            => '查封IP地址',
'blockiptext'        => '用下面的表单来禁止来自某一特定IP地址的修改权限。
只有在为防止破坏，及符合[[{{ns:project}}:守则与指导]]的情况下才可采取此行动。
请在下面输入一个具体的理由（例如引述一个被破坏的页面）。',
'ipaddress'          => 'IP地址',
'ipbreason'          => '原因',
'ipbsubmit'          => '查封该地址',
'badipaddress'       => 'IP地址不正确。',
'blockipsuccesssub'  => '查封成功',
'blockipsuccesstext' => 'IP地址“$1”已经被查封。
<br />参看[[Special:被封IP地址列表|被封IP地址列表]]以复审查封。',
'unblockip'          => '解除禁封IP地址',
'unblockiptext'      => '用下面的表单来恢复先前被禁封的IP地址的书写权。',
'ipusubmit'          => '解除禁封',
'ipblocklist'        => '被封IP地址列表',
'blocklistline'      => '$1，$2禁封$3 ($4)',
'blocklink'          => '禁封',
'unblocklink'        => '解除禁封',
'contribslink'       => '贡献',
'autoblocker'        => '你的IP和被封了的 "$1" 是一样的。封锁原因： "$2".',
'blocklogpage'       => '封锁记录',
'blocklogentry'      => '封锁 $1, $2',

# Developer tools
'lockdb'              => '禁止更改数据库',
'unlockdb'            => '开放更改数据库',
'lockdbtext'          => '锁住数据库将禁止所有用户进行编辑页面、更改参数、编辑监视列表以及其他需要更改数据库的操作。
请确认您的决定，并且保证您在维护工作结束后会重新开放数据库。',
'unlockdbtext'        => '开放数据库将会恢复所有用户进行编辑页面、修改参数、编辑监视列表以及其他需要更改数据库的操作。
请确认您的决定。',
'lockconfirm'         => '是的，我确实想要封锁数据库。',
'unlockconfirm'       => '是的，我确实想要开放数据库。',
'lockbtn'             => '数据库上锁',
'unlockbtn'           => '开放数据库',
'locknoconfirm'       => '您并没有勾选确认按钮。',
'lockdbsuccesssub'    => '数据库成功上锁',
'unlockdbsuccesssub'  => '数据库开放',
'lockdbsuccesstext'   => '{{SITENAME}}数据库已经上锁。
<br />请记住在维护完成后重新开放数据库。',
'unlockdbsuccesstext' => '{{SITENAME}}数据库重新开放。',

# Move page
'movepage'         => '移动页面',
'movepagetext'     => "用下面的表单来重命名一个页面，并将其修订历史同时移动到新页面。
老的页面将成为新页面的重定向页。
链接到老页面的链接并不会自动更改；
请检查双重或损坏重定向链接。
您应当负责确定所有链接依然会链到指定的页面。

注意如果新页面已经有内容的话，页面将'''不会'''被移动，
除非新页面无内容或是重定向页，而且没有修订历史。
这意味着您再必要时可以在移动到新页面后再移回老的页面，
同时您也无法覆盖现有页面。

<b>警告！</b>
对一个经常被访问的页面而言这可能是一个重大与唐突的更改；
请在行动前先了结其所可能带来的后果。",
'movepagetalktext' => "有关的对话页（如果有的话）将被自动与该页面一起移动，'''除非'''：
*您将页面移动到不同的名字空间（namespaces）；
*新页面已经有一个包含内容的对话页，或者
*您不勾选下面的复选框。

在这些情况下，您在必要时必须手工移动或合并页面。",
'movearticle'      => '移动页面',
'movenologin'      => '未登录',
'movenologintext'  => '您必须是一名登记用户并且[[Special:Userlogin|登录]]
后才可移动一个页面。',
'newtitle'         => '新标题',
'movepagebtn'      => '移动页面',
'pagemovedsub'     => '移动成功',
'pagemovedtext'    => '页面“[[$1]]”已经移动到“[[$2]]”。',
'articleexists'    => '该名字的页面已经存在，或者您选择的名字无效。请再选一个名字。',
'talkexists'       => '页面本身移动成功，
但是由于新标题下已经有对话页存在，所以对话页无法移动。请手工合并两个页面。',
'movedto'          => '移动到',
'movetalk'         => '如果可能的话，请同时移动对话页。',
'talkpagemoved'    => '相应的对话页也已经移动。',
'talkpagenotmoved' => '相应的对话页<strong>没有</strong>被移动。',
'1movedto2'        => '$1移动到$2',
'1movedto2_redir'  => '$1重定向到$2',

# Namespace 8 related
'allmessages'               => '系统界面',
'allmessagestext'           => '这里列出所有可定制的系统界面。',
'allmessagesnotsupportedDB' => '系统界面功能处于关闭状态 (wgUseDatabaseMessages)。',

# Attribution
'anonymous' => '匿名用户',
'and'       => '和',

# Spam protection
'categoryarticlecount' => '该类页面共有 $1 条目',

# Math options
'mw_math_png'    => '永远使用PNG图像',
'mw_math_simple' => '如果是简单的公式使用HTML，否则使用PNG图像',
'mw_math_html'   => '如果可以用HTML，否则用PNG图像',
'mw_math_source' => '显示为TeX代码(使用文字浏览器时)',
'mw_math_modern' => '推荐为新版浏览器使用',

/*
Short names for language variants used for language conversion links.
To disable showing a particular link, set it to 'disable', e.g.
'variantname-zh-sg' => 'disable',
Variants for Chinese language
*/
'variantname-zh-cn' => '大陆简体',
'variantname-zh-tw' => '台湾繁体',
'variantname-zh-hk' => '香港繁体',
'variantname-zh-sg' => '新加坡简体',
'variantname-zh'    => '不转换',

);

?>
