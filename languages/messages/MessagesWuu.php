<?php
/** Wu (吴语)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Hercule
 * @author O
 * @author Wu-chinese.com
 */

$fallback = 'zh-hans';

$messages = array(
# User preference toggles
'tog-underline'               => '链接下划线：',
'tog-highlightbroken'         => '无效链接格式显示成功<a href="" class="new">箇能介</a>（或者实梗<a href="" class="internal">？</a>）',
'tog-justify'                 => '对齐段落',
'tog-hideminor'               => '来许近段辰光个改动里向拿细改动囥脱',
'tog-hidepatrolled'           => '来拉近段辰光个改动里向囥脱巡查过歇个改动',
'tog-newpageshidepatrolled'   => '来拉新页面列表里向拿已巡查歇个页面囥脱',
'tog-extendwatchlist'         => '扩展监控列表，显示所有改动，而弗仅仅是最近个',
'tog-usenewrc'                => '使用强化版个近段辰光个改动（JavaScript）',
'tog-numberheadings'          => '标题自动编号',
'tog-showtoolbar'             => '显示编辑工具条（JavaScript）',
'tog-editondblclick'          => '双击个辰光编辑页面（JavaScript）',
'tog-editsection'             => '允许通过点击【编辑】链接来编辑段落',
'tog-editsectiononrightclick' => '允许右击标题编辑段落（JavaScript）',
'tog-showtoc'                 => '显示目录（针对超过三只标题个页面）',
'tog-rememberpassword'        => '来许箇台电脑上记牢我个密码（可维持$1{{PLURAL:$1|日|日}}）',
'tog-watchcreations'          => '拿我创建个页面添加到我个监控列表里向',
'tog-watchdefault'            => '拿我编辑个页面添加到我个监控列表里向',
'tog-watchmoves'              => '拿我移动个页面添加到我个监控列表里向',
'tog-watchdeletion'           => '拿我删除个页面添加到我个监控列表里向',
'tog-minordefault'            => '默认拿所有编辑标记成功细编辑',
'tog-previewontop'            => '来拉编辑框前头显示预览',
'tog-previewonfirst'          => '来拉首次编辑辰光显示预览',
'tog-nocache'                 => '禁用页面缓存',
'tog-enotifwatchlistpages'    => '我个监控列表里向个页面有改动个说话发电子邮件通知我',
'tog-enotifusertalkpages'     => '我个对话更改辰光发邮件通知我',
'tog-enotifminoredits'        => '页面有细微修改个辰光也发邮件通知我',
'tog-enotifrevealaddr'        => '来拉通知邮件列表里向显示我个邮件地址',
'tog-shownumberswatching'     => '显示监控此页个用户数目',
'tog-oldsig'                  => '现在签名个预览：',
'tog-fancysig'                => '拿签名当成wiki文本（弗产生自动链接）',
'tog-externaleditor'          => '默认使用外部编辑器（仅供高手使用，需要来许电脑上作出特殊设置）',
'tog-externaldiff'            => '默认使用外部分析（仅供高手使用，需要来许电脑上作出特殊设置）',
'tog-showjumplinks'           => '启用“跳转”链接',
'tog-uselivepreview'          => '使用实时预览（Javascript）（试验）',
'tog-forceeditsummary'        => '编辑摘要为空个辰光提醒我',
'tog-watchlisthideown'        => '来许监控列表里向拿我个编辑囥脱佢',
'tog-watchlisthidebots'       => '来许监控列表里向拿机器人个编辑囥脱',
'tog-watchlisthideminor'      => '来拉监控列表里向拿细编辑囥脱',
'tog-watchlisthideliu'        => '来拉监控列表里拿登录用户个改动囥脱',
'tog-watchlisthideanons'      => '来拉监控列表里拿匿名用户个改动囥脱',
'tog-watchlisthidepatrolled'  => '来拉监控列表里拿已巡查过歇个改动囥脱',
'tog-ccmeonemails'            => '拿我发拨别个用户个邮件同时也发只副本拨我自家',
'tog-diffonly'                => '垃拉比较两只修订版本个两样个辰光弗显示页面内容',
'tog-showhiddencats'          => '显示囥脱个分类',
'tog-norollbackdiff'          => '执行退回之后弗显示两样',

'underline-always'  => '总归',
'underline-never'   => '从来弗',
'underline-default' => '浏览器默认',

# Font style option in Special:Preferences
'editfont-style'     => '编辑区字体样式：',
'editfont-default'   => '浏览器默认',
'editfont-monospace' => '等宽字体',
'editfont-sansserif' => 'Sans-serif字体',
'editfont-serif'     => 'Serif字体',

# Dates
'sunday'        => '礼拜天',
'monday'        => '礼拜一',
'tuesday'       => '礼拜两',
'wednesday'     => '礼拜三',
'thursday'      => '礼拜四',
'friday'        => '礼拜五',
'saturday'      => '礼拜六',
'sun'           => '天',
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

# Categories related messages
'pagecategories'                 => '$1只分类',
'category_header'                => '“$1”分类里向个页面',
'subcategories'                  => '子分类',
'category-media-header'          => '"$1"分类中里向个媒体',
'category-empty'                 => "''迭只分类里向还弗曾包含任何文章咾媒体。''",
'hidden-categories'              => '$1隐藏分类',
'hidden-category-category'       => '隐藏分类',
'category-subcat-count'          => '{{PLURAL:$2|此分类仅有下列一只子分类。|此分类包含下列$1只子分类，共计$2只子分类。}}',
'category-subcat-count-limited'  => '迭只分类包含下底$1只子分类。',
'category-article-count'         => '{{PLURAL:$2|迭只分类只有下底一只页面。|迭只分类包含下底$1只页面，共计$2只页面。}}',
'category-article-count-limited' => '迭只分类包含下底$1只页面。',
'category-file-count'            => '{{PLURAL:$2|箇只分类只有下底一只文件。|箇只分类包含下底$1只文件，共计$2只文件。}}',
'category-file-count-limited'    => '迭只分类包含下底$1只文件。',
'listingcontinuesabbrev'         => '续',
'index-category'                 => '索引拉许个页面',
'noindex-category'               => '弗曾索引拉许个页面',

'mainpagetext'      => "'''MediaWiki安装成功哉！'''",
'mainpagedocfooter' => '请访问[http://meta.wikimedia.org/wiki/Help:Contents 用户手册]以获得使用此维基软件个信息！

== 入门 ==
* [http://www.mediawiki.org/wiki/Manual:Configuration_settings MediaWiki 配置设置列表]
* [http://www.mediawiki.org/wiki/Manual:FAQ MediaWiki 常见问题解答]
* [https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce MediaWiki 发布邮件列表]',

'about'         => '关于',
'article'       => '内容页面',
'newwindow'     => '（垃拉新窗口里向开开来）',
'cancel'        => '取消',
'moredotdotdot' => '还有...',
'mypage'        => '我个页面',
'mytalk'        => '我个讨论',
'anontalk'      => '箇只IP个言论',
'navigation'    => '导航',
'and'           => '&#32;搭仔',

# Cologne Blue skin
'qbfind'         => '寻',
'qbbrowse'       => '浏览',
'qbedit'         => '编辑',
'qbpageoptions'  => '迭只页面',
'qbpageinfo'     => '上下文',
'qbmyoptions'    => '我个选项',
'qbspecialpages' => '特殊页面',
'faq'            => 'FAQs',
'faqpage'        => 'Project：FAQ',

# Vector skin
'vector-action-addsection'       => '加入话题',
'vector-action-delete'           => '删除',
'vector-action-move'             => '移动',
'vector-action-protect'          => '保护',
'vector-action-undelete'         => '反删除',
'vector-action-unprotect'        => '反保护',
'vector-namespace-category'      => '分类',
'vector-namespace-help'          => '帮助页',
'vector-namespace-image'         => '文件',
'vector-namespace-main'          => '页面',
'vector-namespace-media'         => '媒体页面',
'vector-namespace-mediawiki'     => '讯息',
'vector-namespace-project'       => '项目页面',
'vector-namespace-special'       => '特殊页面',
'vector-namespace-talk'          => '讨论',
'vector-namespace-template'      => '模板',
'vector-namespace-user'          => '用户页面',
'vector-simplesearch-preference' => '打开高级搜索建议（仅适用于Vector皮肤）',
'vector-view-create'             => '创建',
'vector-view-edit'               => '编辑',
'vector-view-history'            => '望历史',
'vector-view-view'               => '阅读',
'vector-view-viewsource'         => '查看源码',
'actions'                        => '动作',
'namespaces'                     => '名字空间',
'variants'                       => '变量',

'errorpagetitle'    => '错误',
'returnto'          => '回转到$1。',
'tagline'           => '来自{{SITENAME}}',
'help'              => '帮助',
'search'            => '搜寻',
'searchbutton'      => '搜寻',
'go'                => '走',
'searcharticle'     => '走',
'history'           => '页面档案',
'history_short'     => '历史',
'updatedmarker'     => '上趟访问以来个更新',
'info_short'        => '信息',
'printableversion'  => '打印版',
'permalink'         => '永久链接',
'print'             => '打印',
'edit'              => '编辑',
'create'            => '创建',
'editthispage'      => '编辑此页',
'create-this-page'  => '创建箇只页面',
'delete'            => '删除',
'deletethispage'    => '删除此页',
'undelete_short'    => '恢复拨删脱个$1项修订',
'protect'           => '保护',
'protect_change'    => '改动',
'protectthispage'   => '保护此页',
'unprotect'         => '解除保护',
'unprotectthispage' => '解除此页保护',
'newpage'           => '新页面',
'talkpage'          => '讨论箇只页面',
'talkpagelinktext'  => '讨论',
'specialpage'       => '特殊页',
'personaltools'     => '个人工具',
'postcomment'       => '新段落',
'articlepage'       => '查看内容页面',
'talk'              => '讨论',
'views'             => '查看',
'toolbox'           => '家生',
'userpage'          => '查看用户页面',
'projectpage'       => '查看计划页面',
'imagepage'         => '望文件页',
'mediawikipage'     => '望讯息页',
'templatepage'      => '望模板页',
'viewhelppage'      => '望帮助页',
'categorypage'      => '望分类页',
'viewtalkpage'      => '望讨论页',
'otherlanguages'    => '别样闲话版本',
'redirectedfrom'    => '（$1重定向来个）',
'redirectpagesub'   => '重定向页',
'lastmodifiedat'    => '箇只页面最近修订垃拉$1 $2。',
'viewcount'         => '迭只页面已经拨浏览过$1趟。',
'protectedpage'     => '保护拉许个页面',
'jumpto'            => '跳转到：',
'jumptonavigation'  => '导航',
'jumptosearch'      => '搜寻',
'view-pool-error'   => '弗好意思，服务器现在过载，请等歇再访问。

$1',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => '关于{{SITENAME}}',
'aboutpage'            => 'Project:关于',
'copyright'            => '内容侪拉$1下底发布。',
'copyrightpage'        => '{{ns:project}}:版权',
'currentevents'        => '近段辰光个事体',
'currentevents-url'    => 'Project:近段辰光个事体',
'disclaimers'          => '免责声明',
'disclaimerpage'       => 'Project:免责声明',
'edithelp'             => '编辑帮助',
'edithelppage'         => 'Help:如何编辑页面',
'helppage'             => 'Help:目录',
'mainpage'             => '封面',
'mainpage-description' => '封面',
'policy-url'           => 'Project:政策',
'portal'               => '社区门荡',
'portal-url'           => 'Project:社区门荡',
'privacy'              => '隐私政策',
'privacypage'          => 'Project:隐私政策',

'badaccess'        => '权限',
'badaccess-group0' => '箇只操作是弗允许个。',
'badaccess-groups' => '侬刚刚只请求只有{{PLURAL:$2|迭只}}用户组个用户再好使用：$1',

'versionrequired'     => '需要$1版本个MediaWiki',
'versionrequiredtext' => '要$1版本个MediaWiki再好使用此页。参见[[Special:Version|版本页]]。',

'ok'                      => '确认',
'retrievedfrom'           => '取自“$1”',
'youhavenewmessages'      => '侬有$1（$2）。',
'newmessageslink'         => '新讯息',
'newmessagesdifflink'     => '上趟更改',
'youhavenewmessagesmulti' => '侬垃拉$1有新讯息',
'editsection'             => '编辑',
'editold'                 => '编辑',
'viewsourceold'           => '查看源码',
'editlink'                => '编辑',
'viewsourcelink'          => '查看源码',
'editsectionhint'         => '编辑段落: $1',
'toc'                     => '目录',
'showtoc'                 => '显示',
'hidetoc'                 => '囥脱',
'thisisdeleted'           => '查看或者恢复$1？',
'viewdeleted'             => '望望$1看？',
'restorelink'             => '$1只删脱个版本',
'feedlinks'               => '订阅：',
'feed-invalid'            => '订阅类型无效。',
'feed-unavailable'        => '暂时弗支持联合订阅',
'site-rss-feed'           => '$1个RSS订阅',
'site-atom-feed'          => '$1个Atom订阅',
'page-rss-feed'           => '“$1”个RSS订阅',
'page-atom-feed'          => '"$1" 个Atom feed',
'red-link-title'          => '$1 （网页不存在）',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => '文章',
'nstab-user'      => '用户页面',
'nstab-media'     => '媒体页面',
'nstab-special'   => '特殊页面',
'nstab-project'   => '项目页面',
'nstab-image'     => '文件',
'nstab-mediawiki' => '讯息',
'nstab-template'  => '模板',
'nstab-help'      => '帮助页面',
'nstab-category'  => '分类',

# Main script and global functions
'nosuchaction'      => '无没箇能介个操作',
'nosuchactiontext'  => 'URL指定个命令无效。侬作兴拿URL输错脱哉，要嚜点击仔错误个链接。箇只错误亦有可能是由{{SITENAME}}所使用软件自家个错误导致个。',
'nosuchspecialpage' => '呒没箇只特殊页面',
'nospecialpagetext' => '<strong>侬请求个特殊页面无效。</strong>

参考特殊页面列表[[Special:SpecialPages| {{int:specialpages}}]]。',

# General errors
'error'                => '错误',
'databaseerror'        => '数据库错误',
'dberrortext'          => '发生仔数据库查询语法错误，作兴是软件自身个错误所引起个。压末一趟数据库查询指令是：
<blockquote><tt>$1</tt></blockquote>
来自函数“<tt>$2</tt>”内。数据库返回错误“<tt>$3: $4</tt>”。',
'dberrortextcl'        => '发生了数据库查询语法错误。压末一趟数据库查询指令是：
“$1”
来自函数“$2”内。数据库返回错误“$3: $4”。',
'laggedslavemode'      => '警告: 页面可能弗包含最近个更新。',
'readonly'             => '数据库锁定',
'enterlockreason'      => '请输入锁定个原因，包括预计解锁个辰光',
'readonlytext'         => '数据库目前禁止输入新内容及更改，
箇蛮有可能是因为数据库拉许维修，完成仔即可恢复。

管理员有如下解释：$1',
'missing-article'      => '数据库寻弗着预期个页面文字：“$1”$2。

箇一般性是由于点击了链向旧有差异或历史个链接，而原有修订已拨删除导致个。

如果情况弗是箇能介，侬作兴寻着仔软件个一只内部错误。请拿URL地址记录下来，并向[[Special:ListUsers/sysop|管理员]]报告。',
'missingarticle-rev'   => '（修订#：$1）',
'missingarticle-diff'  => '（两样：$1、$2）',
'readonly_lag'         => '从数据库服务器垃拉从主服务器上更新，数据库已经拨自动锁定',
'internalerror'        => '内部错误',
'internalerror_info'   => '内部错误：$1',
'fileappenderrorread'  => '当附加时无法读取"$1"。',
'fileappenderror'      => '“$1”附加到“$2”弗来三。',
'filecopyerror'        => '弗好拿文件“$1”复制到“$2”。',
'filerenameerror'      => '拿文件“$1”重命名为“$2”失败。',
'filedeleteerror'      => '弗好删除文件“$1”。',
'directorycreateerror' => '创建目录“$1”失败。',
'filenotfound'         => '寻弗着文件 "$1"。',
'fileexistserror'      => '弗好写入文件“$1”：文件已存在',
'unexpected'           => '非正常值：“$1”=“$2”。',
'formerror'            => '错误：提交表单失败',
'badarticleerror'      => '呒处垃拉箇只页面进行箇只操作。',
'cannotdelete'         => '无处删除页面或图像 "$1"。
渠作兴已经拨别人家删除脱哉。',
'badtitle'             => '该只标题弗来三',
'badtitletext'         => '所请求页面个标题是无效个、弗存在，跨语言或跨wiki链接个标题错误。渠作兴包含一只或多只弗好用拉标题里向字符。',
'perfcached'           => '下底是缓存数据，箇咾作兴弗是顶新个：',
'perfcachedts'         => '下头是缓存数据，压末一趟更新辰光是$1。',
'querypage-no-updates' => '当前禁止对此页面进行更新。箇搭个数据弗好立即刷新。',
'wrong_wfQuery_params' => '错误个参数拨传递到 wfQuery（）<br />
函数：$1<br />
查询：$2',
'viewsource'           => '源码',
'viewsourcefor'        => '$1个源码',
'actionthrottled'      => '动作已压制',
'actionthrottledtext'  => '基于反垃圾链接个考量，限制垃拉短时间内多趟重复箇只操作。请过脱几分钟再试试看。',
'protectedpagetext'    => '箇只页面已经锁定，以防编辑。',
'viewsourcetext'       => '侬可以查看搭仔复制箇只页面个源码：',
'protectedinterface'   => '箇只页面提供软件个界面文本。为著防止滥用咾已经锁定。',
'editinginterface'     => "'''警告：''' 侬垃许编辑个页面是用于提供软件个界面文本。改变此页会得影响其他用户个界面外观。假使要翻译，请考虑使用 [http://translatewiki.net/wiki/Main_Page?setlang=zh-hans translatewiki.net]，一个用得来为MediaWiki软件本地化个计划。",
'sqlhidden'            => '（SQL查询已隐藏）',
'cascadeprotected'     => '箇只页面拨保护拉许，因为箇只页面拨下底已经标注“联锁保护”个{{PLURAL:$1|一只|多只}}被保护页面包含：
$2',
'namespaceprotected'   => "侬无没编辑'''$1'''名字空间里向页面个权限。",
'customcssjsprotected' => '侬无权编辑箇只页面，因为渠包含其他用户个个人设定。',
'ns-specialprotected'  => '特殊页编辑是弗来三个。',
'titleprotected'       => "箇只标题已经拨[[User:$1|$1]]保护以防止创建。理由是''$2''。",

# Virus scanner
'virus-badscanner'     => "设置问题：未知个反病毒扫描器：''$1''",
'virus-scanfailed'     => '扫描失败（代码 $1）',
'virus-unknownscanner' => '未知个反病毒扫描器：',

# Login and logout pages
'logouttext'                 => "'''侬已经登出哉。'''

侬可以继续匿名使用{{SITENAME}} ，也可以再次以相同或者两样个用户名登录。
注意，有眼页面作兴还是会搭侬登出前头一样显示，一脚到侬清除浏览器缓存。",
'welcomecreation'            => '== 欢迎， $1！ ==

侬个户头已经建立好哉。弗要忘记脱设定侬个{{SITENAME}}个人设置噢。',
'yourname'                   => '用户名:',
'yourpassword'               => '密码:',
'yourpasswordagain'          => '再拍一遍密码:',
'remembermypassword'         => '垃拉箇部电脑上记牢我个密码（可维持$1{{PLURAL:$1|日|日}}）',
'yourdomainname'             => '侬个域名：',
'externaldberror'            => '迭个作兴是由于验证数据库错误或者侬拨禁止更新侬个外部账号。',
'login'                      => '登录',
'nav-login-createaccount'    => '登录 / 开户',
'loginprompt'                => '定规要启用仔缓存（cookies）倷再好登录到{{SITENAME}}。',
'userlogin'                  => '登录 / 新开户头',
'userloginnocreate'          => '登录',
'logout'                     => '登出',
'userlogout'                 => '登出',
'notloggedin'                => '弗曾登录',
'nologin'                    => "侬还呒没账户？'''$1'''。",
'nologinlink'                => '新开户头',
'createaccount'              => '新开户头',
'gotaccount'                 => "已经有仔帐号哉？ '''$1'''。",
'gotaccountlink'             => '登录',
'createaccountmail'          => '通过 e-mail',
'badretype'                  => '倷输入个密码搭倪个档案弗配。',
'userexists'                 => '箇只ID已经拨注册脱哉。请重新再拣个用户名。',
'loginerror'                 => '登录错误',
'createaccounterror'         => '无法建立账户：$1',
'nocookiesnew'               => '侬个账户创建成功！Cookies像煞拨侬关拉许，请开开来再登录。',
'nocookieslogin'             => '本站利用Cookies进行用户登录，侬个Cookies像煞关拉许，请开开来再登录。',
'noname'                     => '用户名无效。',
'loginsuccesstitle'          => '登录成功',
'loginsuccess'               => "'''侬现在以 \"\$1\" 个身份登录到{{SITENAME}}。 '''",
'nosuchuser'                 => '寻弗着用户“$1”。用户名是大小写敏感外加区分繁简体个。请检查拼写，或者[[Special:UserLogin/signup|开只新账户]]。',
'nosuchusershort'            => '无没叫“<nowiki>$1</nowiki>”个用户。请检查侬个输入。',
'nouserspecified'            => '侬必须选个用户名。',
'login-userblocked'          => '箇个用户拨封锁拉许。弗允许登录。',
'wrongpassword'              => '密码弗对。请侬再试试看。',
'wrongpasswordempty'         => '密码为空，请重试。',
'passwordtooshort'           => '密码起码要$1个字符。',
'password-name-match'        => '密码弗好搭户名一样。',
'mailmypassword'             => '拿新密码寄拨我',
'passwordremindertitle'      => '{{SITENAME}} 个临时新密码',
'passwordremindertext'       => '有人（作兴是侬，来自IP地址$1）已经请求{{SITENAME}}个新密码（$4）。
用户“$2”个一只新临时密码现在已经设置好为“$3”。
假使箇只动作是侬发起个，侬需要立即登录并选择一只新个密码。
侬个临时密码会得垃拉$5日里向过期。

假使箇只请求弗是侬发起个，或者侬已经拿密码想起来外加弗准备改脱渠，
侬可以忽略此消息并继续使用侬个旧密码。',
'noemail'                    => '用户"$1"弗曾登记电子邮件地址。',
'noemailcreate'              => '侬要提供只有效个电子邮件地址',
'passwordsent'               => '用户"$1"个新密码已经寄往登记个电子邮件地址。
请收着仔再登录。',
'blocked-mailpassword'       => '侬个IP地址处于查封状态，弗允许编辑，为仔安全起见，密码恢复功能已经禁用。',
'eauthentsent'               => '一封确认信已经发送到指定个e-mail地址。垃拉发送其它邮件到箇只账户之前，侬必须首先按照箇封信里向个指示确认箇只电子邮箱真实有效。',
'throttled-mailpassword'     => '密码提醒已经垃拉最近$1个钟头里向发送过歇。为仔安全起见，垃拉$1个钟头里向只好发送一个密码提醒。',
'mailerror'                  => '发送邮件错误：$1',
'acct_creation_throttle_hit' => '弗好意思，使用箇只IP个访客已经创建仔$1只账号，迭个是箇段辰光里向所允许个最大值。箇咾使用箇只IP个地址个访客暂时弗好再创建账户。',
'emailauthenticated'         => '侬个电子邮箱地址已经垃拉$2 $3确认有效。',
'emailnotauthenticated'      => '侬个邮箱地址<strong>还弗曾认证</strong>。下底眼功能将弗会发送任何邮件。',
'noemailprefs'               => '指定一只电子邮箱地址以使用箇眼功能。',
'emailconfirmlink'           => '确认邮箱地址',
'invalidemailaddress'        => '邮箱地址格式弗对，请输入正确个邮箱地址或清空输入框。',
'accountcreated'             => '户头开好哉',
'accountcreatedtext'         => '$1 个户头已经建立哉。',
'createaccount-title'        => '垃拉{{SITENAME}}里向创建新账户',
'createaccount-text'         => '有人垃拉{{SITENAME}}里向利用侬个邮箱创建仔一只叫 "$2" 个新帐户（$4），密码是 "$3" 。侬应该立即登录并更改密码。

如果箇个账户创建错误个说话，侬可以忽略此信息。',
'usernamehasherror'          => '用户名里向弗好包含hash字符',
'login-throttled'            => '登录尝试忒多哉。
请等脱歇再试。',
'loginlanguagelabel'         => '语言：$1',
'suspicious-userlogout'      => '侬登出个要求已经拨回头脱，因为渠可能是由已损坏个浏览器或者缓存代理传送个。',

# Password reset dialog
'resetpass'                 => '更改密码',
'resetpass_announce'        => '侬是通过一只临时发送到e-mail里向个代码登录的。要完成登录，侬必须垃此地设定一只新密码：',
'resetpass_header'          => '更改密码',
'oldpassword'               => '旧密码:',
'newpassword'               => '新密码:',
'retypenew'                 => '再打一遍新密码:',
'resetpass_submit'          => '设置密码再登录',
'resetpass_success'         => '密码修改成功！
现在垃许登录...',
'resetpass_forbidden'       => '密码弗好更改',
'resetpass-no-info'         => '侬必须登录仔再好直接进入箇只页面。',
'resetpass-submit-loggedin' => '更改密码',
'resetpass-submit-cancel'   => '取消',
'resetpass-wrong-oldpass'   => '无效个临时或者现有密码。
侬作兴已经成功拿密码改脱，或者已经请求一个新个临时密码。',
'resetpass-temp-password'   => '临时密码：',

# Edit page toolbar
'bold_sample'     => '黑体文本',
'bold_tip'        => '黑体文本',
'italic_sample'   => '斜体文本',
'italic_tip'      => '斜体文本',
'link_sample'     => '链接标题',
'link_tip'        => '内部链接',
'extlink_sample'  => 'http://www.example.com 链接标题',
'extlink_tip'     => '外部链接（弗要忘记脱前头加http://）',
'headline_sample' => '标题文本',
'headline_tip'    => '2级标题文字',
'math_sample'     => '箇搭嵌入数学公式',
'math_tip'        => '插入数学公式 （LaTeX）',
'nowiki_sample'   => '垃拉箇搭插入非格式文本',
'nowiki_tip'      => '忽略wiki格式',
'image_tip'       => '嵌入文件',
'media_tip'       => '文件链接',
'sig_tip'         => '签名搭仔辰光戳',
'hr_tip'          => '水平线 （小心使用）',

# Edit pages
'summary'            => '摘要:',
'subject'            => '主题 / 标题：',
'minoredit'          => '箇是只细微个改动',
'watchthis'          => '监控箇只页面',
'savearticle'        => '保存页面',
'preview'            => '预览',
'showpreview'        => '显示预览',
'showlivepreview'    => '实时预览',
'showdiff'           => '显示改动',
'anoneditwarning'    => "'''警告：''' 侬弗曾登录。侬个IP地址会得记录拉页面个编辑历史里向。",
'blockednoreason'    => '弗曾拨原因',
'whitelistedittext'  => '侬必须$1才能编辑。',
'loginreqtitle'      => '必须登录',
'loginreqlink'       => '登录',
'accmailtitle'       => '密码已发送哉。',
'accmailtext'        => '"$1"个密码已经发送到 $2 哉。',
'newarticle'         => '（新）',
'newarticletext'     => "倷跟仔链接来着一个还弗勒里个页面。
要创建该页面呢，就勒下底个框框里向开始写（[[{{MediaWiki:Helppage}}|帮助页面]]浪有更加多个信息）。
要是倷是弗用心到该搭个说话，只要点击倷浏览器个'''返回'''揿钮。",
'noarticletext'      => '个篇文章目前还呒拨内容，倷可以拉别个页面里向[[Special:Search/{{PAGENAME}}|寻寻该只标题看]]或者[{{fullurl:{{FULLPAGENAME}}|action=edit}} 编辑当前页面].',
'note'               => "'''注意：'''",
'previewnote'        => "'''该个还只是预览；改动还朆保存！'''",
'editing'            => '正在编辑$1',
'editingsection'     => '正在编辑$1（段落）',
'editingcomment'     => '正在编辑$1（评论）',
'editconflict'       => '编辑冲突: $1',
'yourtext'           => '侬个文字',
'editingold'         => "''' 注意：倷勒里改动一只已经过期个页面修改。 如果倷保存俚个说话，勒拉该个修改之后个亨白浪当个修改侪会呒拨个。'''",
'template-protected' => '（保护）',

# Account creation failure
'cantcreateaccounttitle' => '呒处建立帐户',

# History pages
'viewpagelogs' => '查看该页面日志',
'nohistory'    => '该只页面呒拨编辑历史。',
'nextrevision' => '新点个版本→',
'cur'          => '当前',
'last'         => '上个',
'histlegend'   => '选择比较版本：标记要比较个两只版本，回车或者揿页面底里个揿钮。<br /> 图例：（当前） = 搭当前版本有啥两样， （上个） = 搭上个版本有啥两样，小 = 小改动。',
'histfirst'    => '顶早',
'histlast'     => '顶晏',
'historyempty' => '（空）',

# Revision feed
'history-feed-title' => '校订历史',

# Revision deletion
'rev-delundel' => '显示/囥脱',

# Diffs
'compareselectedversions' => '比较选中个版本',
'editundo'                => '撤销',
'diff-multi'              => '（$1个中途个修订版本无没显示。）',

# Search results
'searchresults'             => '搜索结果',
'searchresulttext'          => '更加全面个关于拉{{SITENAME}}里向搜索个信息，请倷看[[{{MediaWiki:Helppage}}:搜索|搜索{{SITENAME}}]]。',
'searchsubtitle'            => "倷搜寻 '''[[:$1]]'''",
'searchsubtitleinvalid'     => "倷搜寻 '''$1'''",
'prevn'                     => '上个 $1',
'nextn'                     => '下个 {{PLURAL:$1|$1}}',
'viewprevnext'              => '查看 （$1） （$2） （$3）',
'searchhelp-url'            => 'Help:目录',
'search-result-size'        => '$1（$2字）',
'search-mwsuggest-enabled'  => '与建议',
'search-mwsuggest-disabled' => ' 没有建议',
'powersearch'               => '高级搜索',

# Quickbar
'qbsettings-none' => '呒',

# Preferences page
'preferences'           => '偏好',
'mypreferences'         => '我个所欢喜',
'prefsnologin'          => '朆登录',
'changepassword'        => '改密码',
'prefs-skin'            => '皮肤',
'skin-preview'          => '预览',
'prefs-math'            => '数学公式',
'datedefault'           => '呒拨偏好',
'prefs-datetime'        => '日脚搭仔辰光',
'prefs-personal'        => '用户档案',
'prefs-rc'              => '近段辰光个改动',
'prefs-watchlist'       => '监控列表',
'prefs-watchlist-days'  => '勒拉监控列表里向显示个日数：',
'prefs-watchlist-edits' => '勒拉扩展个监控列表里向显示个编辑趟数：',
'saveprefs'             => '保存',
'prefs-editing'         => '编辑',
'searchresultshead'     => '搜索',
'recentchangescount'    => '近段辰光个改动标题数：',
'savedprefs'            => '倷个偏好已经保存哉。',
'timezonelegend'        => '时区',
'localtime'             => '当地辰光',
'servertime'            => '服务器辰光',
'allowemail'            => '接受别个用户个电子邮件',
'default'               => '默认',
'prefs-files'           => '文件',
'youremail'             => '电子邮件:',
'username'              => '用户名:',
'uid'                   => '用户号：',
'yourrealname'          => '真实姓名:',
'yourlanguage'          => '语言:',
'yournick'              => '绰号:',
'badsig'                => '无效原始签名；检查 HTML 标签。',
'email'                 => '电子邮件',
'prefs-help-email'      => '电子邮箱是备选的，让别人家好通过倷个用户页或者用户讲张页面联系着倷，用弗着暴露倷个身份。',

# User rights
'userrights-user-editname' => '输入用户名:',

# Groups
'group-bot'        => '机器人',
'group-sysop'      => '管理员',
'group-bureaucrat' => '行政员',
'group-all'        => '（全）',

'group-bot-member'        => '机器人',
'group-sysop-member'      => '管理员',
'group-bureaucrat-member' => '行政员',

'grouppage-bot'        => '{{ns:project}}:机器人',
'grouppage-sysop'      => '{{ns:project}}:管理员',
'grouppage-bureaucrat' => '{{ns:project}}:行政员',

# User rights log
'rightsnone' => '（呒）',

# Recent changes
'recentchanges'     => '近段辰光个改动',
'recentchangestext' => '登该个页面浪跟踪最近对维基百科个改动。',
'rcnote'            => '下底是来拉上个 <strong>{{PLURAL:$2|1|$2}}</strong> 日里向个（该歇辰光：$3）最近 <strong>{{PLURAL:$1|1|$1}}</strong> 趟改动。',
'rclistfrom'        => '显示 $1 以来个新改动',
'rcshowhideminor'   => '$1小改动',
'rcshowhidebots'    => '$1机器人',
'rcshowhideliu'     => '$1登录个用户',
'rcshowhideanons'   => '$1匿名用户',
'rcshowhidemine'    => '$1我个改动',
'rclinks'           => '显示来拉上个 $2 日里向个最近 $1 趟改动<br />$3',
'diff'              => '两样',
'hist'              => '历史',
'hide'              => '囥脱',
'show'              => '显示',
'minoreditletter'   => '小',
'newpageletter'     => '新',
'boteditletter'     => '机',
'newsectionsummary' => '/* $1 */ 新段落',

# Recent changes linked
'recentchangeslinked'         => '搭界个改动',
'recentchangeslinked-feed'    => '搭界个改动',
'recentchangeslinked-toolbox' => '搭界个改动',

# Upload
'upload'            => '上载文物',
'uploadbtn'         => '上载文件',
'uploadnologin'     => '朆登录',
'uploadnologintext' => '倷板定要[[Special:UserLogin|登录]]仔再好上载文件。',
'uploaderror'       => '上载出错',
'uploadtext'        => "拿下头只表格来上载文件。要查看或者搜寻之前上载个图片个说法，请到[[Special:FileList|已上载文件列表]]，上载搭仔删脱也记录勒拉[[Special:Log/upload|上载日志]]里向。

要勒拉页面里向摆进图片个说法，用下头该种形式个链接
'''<nowiki>[[{{ns:file}}:文件.jpg]]</nowiki>'''，
'''<nowiki>[[{{ns:file}}:文件.png|替代文本]]</nowiki>''' 或者用
'''<nowiki>[[{{ns:media}}:文件.ogg]]</nowiki>''' 直接链到文件。",
'uploadlog'         => '文件上载日志',
'uploadlogpage'     => '文件上载日志',
'uploadlogpagetext' => '下底是最近上载文件列表。',
'filename'          => '文件名',
'filedesc'          => '小结',
'fileuploadsummary' => '小结:',
'filestatus'        => '版权状态:',
'filesource'        => '来源:',
'uploadedfiles'     => '已经上载个文件',
'ignorewarning'     => '弗管警告，随便哪亨要保存文件。',
'successfulupload'  => '上载成功哉',
'uploadwarning'     => '上载警告',
'savefile'          => '保存文件',
'uploadedimage'     => '上载 "[[$1]]"',
'sourcefilename'    => '源文件:',
'destfilename'      => '目标文件名:',
'watchthisupload'   => '监控该只页面',

# Special:ListFiles
'listfiles_search_for'  => '寻图片名字:',
'imgfile'               => '源文件',
'listfiles'             => '文件列表',
'listfiles_date'        => '日脚',
'listfiles_name'        => '名字',
'listfiles_user'        => '用户',
'listfiles_size'        => '尺寸 （bytes）',
'listfiles_description' => '描述',

# File description page
'file-anchor-link'          => '文物',
'filehist'                  => '文物历史',
'filehist-deleteall'        => '全删',
'filehist-deleteone'        => '删',
'filehist-revert'           => '恢复',
'filehist-user'             => '用户',
'imagelinks'                => '链接',
'nolinkstoimage'            => '呒拨页面链接到该只文件。',
'uploadnewversion-linktext' => '上载该文件个新版',

# File reversion
'filerevert'        => '恢复$1',
'filerevert-legend' => '恢复文物',
'filerevert-submit' => '恢复',

# File deletion
'filedelete'        => '删除$1',
'filedelete-legend' => '删除文物',
'filedelete-submit' => '删除',

# MIME search
'download' => '下载',

# List redirects
'listredirects' => '重定向列表',

# Random page
'randompage' => '随便望望',

# Statistics
'statistics'              => '统计',
'statistics-header-users' => '用户资料',
'statistics-users-active' => '活跃用户',

'brokenredirects'        => '坏脱个重定向',
'brokenredirectstext'    => '下底个重定向链到弗存在个页面:',
'brokenredirects-edit'   => '编辑',
'brokenredirects-delete' => '删除',

# Miscellaneous special pages
'unusedimages'         => '弗勒浪使用个文件',
'popularpages'         => '热门页面',
'mostlinked'           => '链进去顶多个页面',
'mostlinkedcategories' => '链进去顶多个分类',
'mostcategories'       => '分类顶多个页面',
'mostimages'           => '链进去顶多个图片',
'mostrevisions'        => '修订过顶顶多趟数个页面',
'shortpages'           => '短页面',
'longpages'            => '长页面',
'protectedpages'       => '已保护页面',
'protectedtitles'      => '已保护个标题',
'listusers'            => '用户列表',
'newpages'             => '新页面',
'newpages-username'    => '用户名:',
'ancientpages'         => '顶顶老个页面',
'move'                 => '捅荡',
'movethispage'         => '捅该只页面',

# Book sources
'booksources-go' => '转到',

# Special:Log
'specialloguserlabel'  => '用户:',
'speciallogtitlelabel' => '标题:',
'log'                  => '记录',

# Special:AllPages
'allpages'          => '全部页面',
'alphaindexline'    => '$1到$2',
'nextpage'          => '下页 （$1）',
'prevpage'          => '上一页（$1）',
'allpagesfrom'      => '显示个页面开始于:',
'allarticles'       => '所有页面',
'allinnamespace'    => '所有页面 （$1 名字空间）',
'allnotinnamespace' => '全部页面 （弗勒 $1 名字空间里向）',
'allpagesprev'      => '前头',
'allpagesnext'      => '下底',
'allpagesprefix'    => '显示个页面有下底个前缀:',
'allpages-bad-ns'   => '{{SITENAME}}没有叫做"$1"个名字空间.',

# Special:Categories
'categories' => '页面分类',

# Special:ListUsers
'listusers-submit' => '显示',

# E-mail user
'emailuser'     => '发E-mail拨该个用户',
'emailfrom'     => '从',
'emailto'       => '发拨',
'emailsubject'  => '主题',
'emailmessage'  => '信息',
'emailsend'     => '发罢',
'emailsent'     => '电子邮件发出去哉',
'emailsenttext' => '倷个电子邮件讯息已经拨发送哉。',

# Watchlist
'watchlist'         => '监控列表',
'mywatchlist'       => '我个监控列表',
'watchlistfor'      => "（'''$1'''个监控列表）",
'nowatchlist'       => '倷个监控列表是空个。',
'watchnologin'      => '朆登录',
'addedwatch'        => '加到监控列表哉',
'addedwatchtext'    => "该个页面 \"[[:\$1]]\" 已经加到侬个[[Special:Watchlist|监控列表]]哉。
将来对该页面个改动搭仔搭界个讲张页个改动会列表垃该面，并且页面会垃拉[[Special:RecentChanges|近段辰光个改变列表]]里向显示成功'''黑体'''，实梗好外加便当拿渠拣出来。假使侬歇仔两日又想拿箇个页面登侬个监控列表里向拿脱个说法，垃侧条里向点击“弗要监控。",
'removedwatch'      => '登监控列表里向拿脱哉',
'removedwatchtext'  => '"[[:$1]]"已经从侬个监控列表移除。',
'watch'             => '监控',
'watchthispage'     => '监控该只页面',
'unwatch'           => '覅监控',
'unwatchthispage'   => '停止监控',
'watchlistcontains' => '倷个监控列表包括{{PLURAL:$1|1|$1}}只页面。',
'wlshowlast'        => '显示上个 $1 个钟头 $2 日 $3',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => '监控……',
'unwatching' => '解除监控……',

'enotif_newpagetext' => '该个是一只新页面。',
'changed'            => '改变哉',
'created'            => '建立哉',

# Delete
'deletepage'     => '删脱页面',
'confirm'        => '确认',
'historywarning' => '警告：倷要删脱个该只页面有历史：',
'actioncomplete' => '操作完成哉',
'deletedarticle' => '"[[$1]]" 已经删脱哉',
'dellogpage'     => '删除记录',
'deletionlog'    => '删除记录',
'deletecomment'  => '删脱俚个理由',

# Rollback
'rollback'       => '恢复编辑',
'rollback_short' => '恢复',
'rollbacklink'   => '恢复',
'rollbackfailed' => '恢复失败',
'revertpage'     => '恢复[[Special:Contributions/$2|$2]] （[[User talk:$2|讲张]]）个改动；恢复到[[User:$1|$1]]个上一版本',

# Protect
'protectlogpage'  => '保护日志',
'prot_1movedto2'  => '[[$1]]捅到[[$2]]',
'protectcomment'  => '保护理由:',
'protect-default' => '（默认）',

# Restrictions (nouns)
'restriction-edit' => '编辑',
'restriction-move' => '捅荡',

# Undelete
'undeletepage'    => '查看搭仔恢复删脱个页面',
'viewdeletedpage' => '望望删脱个页面',

# Namespace form on various pages
'namespace'      => '名字空间:',
'invert'         => '反选择',
'blanknamespace' => '（主）',

# Contributions
'contributions' => '用户贡献',
'mycontris'     => '我个贡献',
'contribsub2'   => '$1个贡献（$2）',
'uctop'         => '（顶浪）',

'sp-contributions-blocklog' => '查封记录',
'sp-contributions-talk'     => '讲张',
'sp-contributions-username' => 'IP地址或用户名：',

# What links here
'whatlinkshere' => '链进来点啥',
'nolinkshere'   => "呒拨页面链接到 '''[[:$1]]'''。",
'isredirect'    => '重定向页面',

# Block/unblock
'blockip'            => '查封用户',
'ipaddress'          => 'IP 地址:',
'ipadressorusername' => 'IP地址或用户名：',
'ipbreason'          => '理由:',
'ipbreasonotherlist' => '其它原因',
'ipbsubmit'          => '封杀该个用户',
'ipbother'           => '其它时间：',
'badipaddress'       => '无效 IP 地址',
'infiniteblock'      => '永远',
'blocklink'          => '封禁',
'contribslink'       => '贡献',
'blocklogpage'       => '封禁日志',
'blocklogtext'       => '该个是用户封禁搭仔解禁操作个记录。自动封禁个IP地址弗会列勒该答。到[[Special:IPBlockList|IP 封禁列表]]去看当前生效个封禁列表。',
'proxyblocksuccess'  => '好哉。',

# Move page
'move-page-legend'        => '页面捅荡',
'movepagetext'            => "下底只表格会重新命名一只页面，拿俚所有个历史也侪捅到新名字下头。
旧个名字会变成到新名字个重定向页面。
到旧页面个连接弗会改变；注意检查双重定向或者坏脱个重定向。
倷有实概个责任，即连接原要连到俚笃应该连到个场呵去。

注意，如果新名字归面搭已经有页面个说话，老名字个页面'''弗'''会拨移动，除非归个是只空页面或者是只重定向并且呒拨编辑历史。个也就是讲，假使倷犯错误个说话，倷好拿一只重命名过个页面还原到原来个名字，但倷弗好覆盖一只已经来浪个页面。

<b>警告！</b>
个作兴会引起对一只热门页面剧烈个、想弗着个改变。
来操作前头请倷确定倷已经充分了解个能做法个后果。",
'movearticle'             => '页面捅荡:',
'movenologin'             => '朆登录',
'movenologintext'         => '倷板定要是已登记用户且勒拉[[Special:UserLogin|登录]]状态下头再好拿页面捅荡。',
'newtitle'                => '新个题目:',
'movepagebtn'             => '页面捅荡',
'pagemovedsub'            => '捅荡成功哉',
'articleexists'           => '叫该个名字个页面已经勒浪哉，要么倷拣个名字是无效个。请重新拣个名字。',
'cantmove-titleprotected' => '侬弗可以移动迭个页面到个个位置，因为迭个新标题已经拨保护拉许以防止创建。',
'movedto'                 => '捅到',
'movetalk'                => '捅搭界个讲张页面',
'1movedto2'               => '[[$1]]捅到[[$2]]',
'1movedto2_redir'         => '[[$1]]通过重定向捅到[[$2]]',
'movelogpage'             => '捅荡记录',
'movelogpagetext'         => '下底是拨拉捅荡个页面列表。',
'movereason'              => '理由:',
'revertmove'              => '恢复该捅荡',
'delete_and_move'         => '删脱搭仔捅荡',
'delete_and_move_confirm' => '对哉，删脱该只页面',

# Namespace 8 related
'allmessages'               => '系统讯息',
'allmessagesname'           => '名字',
'allmessagesdefault'        => '默认文本',
'allmessagescurrent'        => '当前文本',
'allmessagestext'           => '该个是MediaWiki名字空间里可用个系统音讯列表。',
'allmessagesnotsupportedDB' => "'''{{ns:special}}:Allmessages''' 呒处显示，因为 '''\$wgUseDatabaseMessages''' 关勒浪。",

# Thumbnails
'thumbnail-more' => '放大',
'filemissing'    => '文件寻弗着哉',

# Tooltip help for the actions
'tooltip-pt-userpage'        => '我个用户页',
'tooltip-pt-mytalk'          => '我个讲张',
'tooltip-pt-preferences'     => '我个所欢喜',
'tooltip-pt-mycontris'       => '我个贡献列表',
'tooltip-pt-login'           => '鼓励大家登录，必过倒也弗是必须个。',
'tooltip-pt-anonlogin'       => '鼓励登录，必过倒也弗是必须个。',
'tooltip-pt-logout'          => '登出',
'tooltip-ca-talk'            => '讨论内容页',
'tooltip-ca-edit'            => '该篇文章倷可以编辑。请勒拉保存前头先预览。',
'tooltip-ca-addsection'      => '加个评论到搿个讨论里向',
'tooltip-ca-viewsource'      => '该只页面拨保护勒浪，必过倷可以查看源码。',
'tooltip-ca-history'         => '该只页面老早个版本。',
'tooltip-ca-protect'         => '保护该只页面',
'tooltip-ca-delete'          => '删脱该只页面',
'tooltip-ca-move'            => '移动该只页面',
'tooltip-search'             => '搜寻{{SITENAME}}',
'tooltip-search-go'          => '转到页本确切名称，如果存在',
'tooltip-p-logo'             => '封面',
'tooltip-n-mainpage'         => '进入封面',
'tooltip-n-portal'           => '关于本计划，可以做什么，应该如何做',
'tooltip-n-recentchanges'    => '列出近段辰光个改动',
'tooltip-n-help'             => '寻求帮助',
'tooltip-t-whatlinkshere'    => '列出所有与此页相链个页面',
'tooltip-t-emailuser'        => '发封信拨该个用户',
'tooltip-t-upload'           => '上传文件',
'tooltip-t-specialpages'     => '亨白浪当个特殊页面列表',
'tooltip-ca-nstab-main'      => '查看内容页',
'tooltip-ca-nstab-user'      => '查看用户页',
'tooltip-ca-nstab-media'     => '查看媒体页',
'tooltip-ca-nstab-special'   => '该个是只特殊页面，倷弗好编辑俚',
'tooltip-ca-nstab-project'   => '查看项目页',
'tooltip-ca-nstab-image'     => '查看图片页',
'tooltip-ca-nstab-mediawiki' => '查看系统讯息',
'tooltip-ca-nstab-template'  => '查看模板',
'tooltip-ca-nstab-help'      => '查看帮忙页面',
'tooltip-ca-nstab-category'  => '查看分类页面',
'tooltip-minoredit'          => '拿该趟编辑标记成小改动',
'tooltip-save'               => '保存倷个改变',
'tooltip-preview'            => '预览倷个改变，请倷勒拉保存前头用用俚!',
'tooltip-diff'               => '显示倷对文章所作个改变',
'tooltip-watch'              => '拿搿只页面加到倷监控列表里向去',

# Attribution
'anonymous' => '{{SITENAME}}浪个匿名用户',

# Patrol log
'patrol-log-auto' => '（自动）',

# Image deletion
'deletedrevision' => '拨删脱个旧修订 $1',

# Special:NewFiles
'newimages'    => '新文件陈列室',
'showhidebots' => '（$1机器人）',
'ilsubmit'     => '搜寻',

# Bad image list
'bad_image_list' => '格式如下：

只列出项目（线开始* ）的审议。
第一个环节上线必须是一个链接到一个坏文件。
其后的任何链接在同一行被认为是例外情况，即网页的文件，则可能会发生内部。',

/*
Short names for language variants used for language conversion links.
To disable showing a particular link, set it to 'disable', e.g.
'variantname-zh-sg' => 'disable',
Variants for Chinese language
*/
'variantname-zh-tw' => '台湾',

# EXIF tags
'exif-artist' => '作者',

'exif-componentsconfiguration-0' => '弗存在',

'exif-subjectdistance-value' => '$1米',

'exif-contrast-2' => '高',

# Pseudotags used for GPSSpeedRef
'exif-gpsspeed-k' => '公里每小时',
'exif-gpsspeed-m' => '英里每小时',

# External editor support
'edit-externally' => '用外部应用程序来编辑该只文件',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => '全部',
'imagelistall'     => '所有',
'watchlistall2'    => '全部',
'namespacesall'    => '全部',
'monthsall'        => '全',

# E-mail address confirmation
'confirmemail'          => '确认电子邮件地址',
'confirmemail_text'     => '该只wiki要求倷来拉用电子邮件服务之前验证电子邮件地址个有效性。揿底下只揿钮来发封确认信到倷电子邮箱。个封信里会有加密个链接。登倷个浏览器里向打开该只链接，确认倷个电子邮箱地址是有效个。',
'confirmemail_send'     => '发送确认码',
'confirmemail_sent'     => '确认电子邮件发出去哉。',
'confirmemail_success'  => '倷个电子邮箱地址已经通过确认哉。乃么倷好登录，享受倪维基百科哉。',
'confirmemail_loggedin' => '倷个电子邮件地址已经拨确认哉。',
'confirmemail_subject'  => '{{SITENAME}}电子邮件地址确认',

# Scary transclusion
'scarytranscludetoolong' => '[对呒起，URL太长了]',

# Delete conflict
'confirmrecreate' => "用户[[User:$1|$1]] （[[User talk:$1|讲张]]）勒拉倷开始编辑该页面之后拿俚删脱，理由是： : ''$2'' 请拿定章程，倷阿是真个要重建该页面。",

# action=purge
'confirm_purge_button' => '确定',

# Separators for various lists, etc.
'comma-separator' => '、',
'parentheses'     => '（$1）',

# Multipage image navigation
'imgmultipageprev' => '← 上一页',
'imgmultipagenext' => '下一页 →',

# Table pager
'ascending_abbrev'  => '升序',
'descending_abbrev' => '降序',
'table_pager_next'  => '下页',
'table_pager_prev'  => '上页',
'table_pager_first' => '头一页',
'table_pager_last'  => '压末一页',
'table_pager_limit' => '显示 $1 条每页',

# Auto-summaries
'autoredircomment' => '重定向到 [[$1]]',
'autosumm-new'     => '新页面：$1',

# Watchlist editor
'watchlistedit-normal-title' => '编辑监控列表',

# Special:Version
'version' => '版本',

# Special:FilePath
'filepath'        => '文件路径',
'filepath-page'   => '文件：',
'filepath-submit' => '路径',

# Special:SpecialPages
'specialpages' => '特殊页面',

);
