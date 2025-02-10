<?php
/** Simplified Chinese (中文（简体）)
 *
 * @file
 * @ingroup Languages
 *
 * @author A911504820
 * @author Alebcay
 * @author Anakmalaysia
 * @author Anterdc99
 * @author Arlin
 * @author Bencmq
 * @author Biŋhai
 * @author Breawycker
 * @author Byfserag
 * @author Chenxiaoqino
 * @author Chenzw
 * @author Chinalace
 * @author Cicku
 * @author Cwek
 * @author Dimension
 * @author Dingyuang
 * @author Fantasticfears
 * @author Fengchao
 * @author Franklsf95
 * @author Gakmo
 * @author Gaoxuewei
 * @author GeneralNFS
 * @author Gzdavidwong
 * @author Happy
 * @author Hercule
 * @author Horacewai2
 * @author Hydra
 * @author Hzy980512
 * @author Jding2010
 * @author Jetlag
 * @author Jidanni
 * @author Jienus
 * @author Jimmy xu wrk
 * @author Kaganer
 * @author KaiesTse
 * @author Kuailong
 * @author Li3939108
 * @author Liangent
 * @author Linforest
 * @author Liuxinyu970226
 * @author M13253
 * @author Makecat
 * @author Mark85296341
 * @author MarkAHershberger
 * @author Mys 721tx
 * @author Nemo bis
 * @author O
 * @author Onecountry
 * @author PhiLiP
 * @author Qiyue2001
 * @author Shinjiman
 * @author Shirayuki
 * @author Shizhao
 * @author Simon Shek
 * @author Slboat
 * @author StephDC
 * @author Stevenliuyi
 * @author Supaiku
 * @author Tommyang
 * @author User670839245
 * @author Waihorace
 * @author Wilsonmess
 * @author Winston Sung
 * @author Wmr89502270
 * @author Wong128hk
 * @author Wrightbus
 * @author Xiaomingyan
 * @author Yfdyh000
 * @author Zoglun
 * @author 乌拉跨氪
 * @author 御坂美琴
 * @author 燃玉
 * @author 范
 * @author 阿pp
 */

$fallback = 'zh-cn, zh, zh-hant';

$fallback8bitEncoding = 'windows-936';

$namespaceNames = [
	NS_MEDIA            => '媒体',
	NS_SPECIAL          => '特殊',
	NS_TALK             => '讨论',
	NS_USER             => '用户',
	NS_USER_TALK        => '用户讨论',
	NS_PROJECT_TALK     => '$1讨论',
	NS_FILE             => '文件',
	NS_FILE_TALK        => '文件讨论',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki讨论',
	NS_TEMPLATE         => '模板',
	NS_TEMPLATE_TALK    => '模板讨论',
	NS_HELP             => '帮助',
	NS_HELP_TALK        => '帮助讨论',
	NS_CATEGORY         => '分类',
	NS_CATEGORY_TALK    => '分类讨论',
];

$namespaceAliases = [
	'媒体'	=> NS_MEDIA,
	'媒体文件'	=> NS_MEDIA,
	'媒体档案'	=> NS_MEDIA,
	'特殊'  => NS_SPECIAL,
	'讨论'	=> NS_TALK,
	'对话'  => NS_TALK,
	'用户'  => NS_USER,
	'用户讨论' => NS_USER_TALK,
	'用户对话' => NS_USER_TALK,
	'使用者讨论' => NS_USER_TALK,
	'使用者对话' => NS_USER_TALK,
	# '项目' conflicted with WB_NS_ITEM
	'专案' => NS_PROJECT,
	"$1讨论" => NS_PROJECT_TALK,
	"$1对话" => NS_PROJECT_TALK,
	# "项目讨论" conflicted with WB_NS_ITEM_TALK
	'专案讨论' => NS_PROJECT_TALK,
	'Image' => NS_FILE,
	'文件' => NS_FILE,
	'档案' => NS_FILE,
	'图像' => NS_FILE,
	'图片' => NS_FILE,
	'Image_talk' => NS_FILE_TALK,
	'文件讨论' => NS_FILE_TALK,
	'文件对话' => NS_FILE_TALK,
	'档案讨论' => NS_FILE_TALK,
	'档案对话' => NS_FILE_TALK,
	'图像讨论' => NS_FILE_TALK,
	'图像对话' => NS_FILE_TALK,
	'图片讨论' => NS_FILE_TALK,
	'模板' => NS_TEMPLATE,
	'样板' => NS_TEMPLATE,
	'模板讨论' => NS_TEMPLATE_TALK,
	'模板对话' => NS_TEMPLATE_TALK,
	'样板讨论' => NS_TEMPLATE_TALK,
	'样板对话' => NS_TEMPLATE_TALK,
	'帮助' => NS_HELP,
	'说明' => NS_HELP,
	'使用说明' => NS_HELP,
	'帮助讨论' => NS_HELP_TALK,
	'帮助对话' => NS_HELP_TALK,
	'说明讨论' => NS_HELP_TALK,
	'使用说明讨论' => NS_HELP_TALK,
	'分类' => NS_CATEGORY,
	'分类讨论' => NS_CATEGORY_TALK,
	'分类对话' => NS_CATEGORY_TALK,
];

/** @phpcs-require-sorted-array */
$specialPageAliases = [
	'Activeusers'                => [ '活跃用户' ],
	'Allmessages'                => [ '所有消息', '所有信息', '所有讯息' ],
	'AllMyUploads'               => [ '我上传的所有文件', '我的所有文件' ],
	'Allpages'                   => [ '所有页面' ],
	'Ancientpages'               => [ '最早页面', '最老页面' ],
	'ApiHelp'                    => [ 'API帮助' ],
	'ApiSandbox'                 => [ 'API沙盒' ],
	'AuthenticationPopupSuccess' => [ '认证成功弹窗' ],
	'AutoblockList'              => [ '自动封禁列表', '列出自动封禁' ],
	'Badtitle'                   => [ '错误标题', '无效标题' ],
	'Blankpage'                  => [ '空白页面' ],
	'Block'                      => [ '封禁', '封禁IP', '封禁用户', '封' ],
	'BlockList'                  => [ '封禁列表', 'IP封禁列表' ],
	'Booksources'                => [ '网络书源' ],
	'BotPasswords'               => [ '机器人密码' ],
	'BrokenRedirects'            => [ '受损重定向' ],
	'Categories'                 => [ '页面分类' ],
	'ChangeContentModel'         => [ '更改内容模型' ],
	'ChangeCredentials'          => [ '更改凭据' ],
	'ChangeEmail'                => [ '修改邮箱地址' ],
	'ChangePassword'             => [ '修改密码', '重置密码', '找回密码' ],
	'ComparePages'               => [ '对比页面' ],
	'Confirmemail'               => [ '确认电子邮件' ],
	'Contribute'                 => [ '做出贡献' ],
	'Contributions'              => [ '用户贡献', '贡献', '使用者贡献' ],
	'CreateAccount'              => [ '创建账户', '建立帐号' ],
	'Deadendpages'               => [ '断链页面' ],
	'DeletedContributions'       => [ '删除的贡献', '已删除的用户贡献' ],
	'DeletePage'                 => [ '删除页面' ],
	'Diff'                       => [ '差异', '编辑差异' ],
	'DoubleRedirects'            => [ '双重重定向', '两次重定向' ],
	'EditPage'                   => [ '编辑页面', '编辑' ],
	'EditRecovery'               => [ '编辑恢复' ],
	'EditTags'                   => [ '编辑标签' ],
	'EditWatchlist'              => [ '编辑监视列表' ],
	'Emailuser'                  => [ '电邮联系' ],
	'ExpandTemplates'            => [ '展开模板' ],
	'Export'                     => [ '导出页面', '导出', '汇出页面' ],
	'Fewestrevisions'            => [ '版本最少页面', '最少修订页面' ],
	'FileDuplicateSearch'        => [ '搜索重复文件' ],
	'Filepath'                   => [ '文件路径', '档案路径' ],
	'GoToInterwiki'              => [ '去往跨wiki页面' ],
	'Import'                     => [ '导入页面', '导入', '汇入页面' ],
	'Interwiki'                  => [ '跨wiki', '跨维基' ],
	'Invalidateemail'            => [ '不可识别的电邮地址', '无效电邮地址' ],
	'JavaScriptTest'             => [ 'JavaScript测试' ],
	'LinkAccounts'               => [ '链接账号' ],
	'LinkSearch'                 => [ '链接搜索', '搜索网页链接', '连结搜寻' ],
	'Listadmins'                 => [ '管理员列表' ],
	'Listbots'                   => [ '机器人列表' ],
	'ListDuplicatedFiles'        => [ '重复文件列表' ],
	'Listfiles'                  => [ '文件列表', '图像列表', '档案列表' ],
	'Listgrants'                 => [ '列出授权' ],
	'Listgrouprights'            => [ '群组权限', '用户组权限' ],
	'Listredirects'              => [ '重定向页列表', '重定向列表' ],
	'Listusers'                  => [ '用户列表' ],
	'Lockdb'                     => [ '锁定数据库' ],
	'Log'                        => [ '日志' ],
	'Lonelypages'                => [ '孤立页面' ],
	'Longpages'                  => [ '长页面' ],
	'MediaStatistics'            => [ '媒体统计' ],
	'MergeHistory'               => [ '合并历史' ],
	'MIMEsearch'                 => [ 'MIME搜索', 'MIME搜寻' ],
	'Mostcategories'             => [ '最多分类页面' ],
	'Mostimages'                 => [ '最多链接文件' ],
	'Mostinterwikis'             => [ '最多跨wiki链接页面' ],
	'Mostlinked'                 => [ '最多链接页面' ],
	'Mostlinkedcategories'       => [ '最多链接分类' ],
	'Mostlinkedtemplates'        => [ '最多链接模板', '最多嵌入页面', '最多使用模板' ],
	'Mostrevisions'              => [ '最多修订页面' ],
	'Movepage'                   => [ '移动页面' ],
	'Mute'                       => [ '屏蔽' ],
	'Mycontributions'            => [ '我的贡献' ],
	'MyLanguage'                 => [ '我的语言' ],
	'Mylog'                      => [ '我的日志' ],
	'Mypage'                     => [ '我的用户页' ],
	'Mytalk'                     => [ '我的讨论页', '我的对话页' ],
	'Myuploads'                  => [ '我上传的文件', '我的文件' ],
	'NamespaceInfo'              => [ '命名空间信息' ],
	'Newimages'                  => [ '新建文件', '新建图像' ],
	'Newpages'                   => [ '最新页面', '新建页面' ],
	'NewSection'                 => [ '新章节' ],
	'PageData'                   => [ '页面数据' ],
	'PageHistory'                => [ '页面历史' ],
	'PageInfo'                   => [ '页面信息' ],
	'PageLanguage'               => [ '页面语言' ],
	'PagesWithProp'              => [ '带属性的页面', '基于属性的页面' ],
	'PasswordPolicies'           => [ '密码策略' ],
	'PasswordReset'              => [ '重置密码', '重设密码' ],
	'PermanentLink'              => [ '固定链接', '永久链接' ],
	'Preferences'                => [ '参数设置', '设置' ],
	'Prefixindex'                => [ '前缀索引' ],
	'Protectedpages'             => [ '已保护页面' ],
	'Protectedtitles'            => [ '已保护标题' ],
	'ProtectPage'                => [ '保护页面', '保护' ],
	'Purge'                      => [ '刷新' ],
	'RandomInCategory'           => [ '分类内随机' ],
	'Randompage'                 => [ '随机', '随机页面' ],
	'Randomredirect'             => [ '随机重定向', '随机重定向页' ],
	'Randomrootpage'             => [ '随机根页面' ],
	'Recentchanges'              => [ '最近更改' ],
	'Recentchangeslinked'        => [ '链出更改', '最近链出更改', '相关更改' ],
	'Redirect'                   => [ '重定向' ],
	'RemoveCredentials'          => [ '移除凭据' ],
	'Renameuser'                 => [ '重命名用户', '重新命名用户' ],
	'ResetTokens'                => [ '重置密钥', '重置权标' ],
	'RestSandbox'                => [ 'REST沙盒' ],
	'Revisiondelete'             => [ '版本删除', '删除修订', '恢复修订' ],
	'RunJobs'                    => [ '运行工作' ],
	'Search'                     => [ '搜索' ],
	'Shortpages'                 => [ '短页面' ],
	'Specialpages'               => [ '特殊页面' ],
	'Statistics'                 => [ '统计', '统计信息' ],
	'Tags'                       => [ '标签' ],
	'TalkPage'                   => [ '讨论页' ],
	'TrackingCategories'         => [ '追踪分类' ],
	'Unblock'                    => [ '解除封禁', '解封' ],
	'Uncategorizedcategories'    => [ '未归类分类', '未分类分类' ],
	'Uncategorizedimages'        => [ '未归类文件', '未分类文件', '未分类图像' ],
	'Uncategorizedpages'         => [ '未分类页面' ],
	'Uncategorizedtemplates'     => [ '未分类模板' ],
	'Undelete'                   => [ '恢复被删页面' ],
	'UnlinkAccounts'             => [ '取消关联账号' ],
	'Unlockdb'                   => [ '解除数据库锁定' ],
	'Unusedcategories'           => [ '未使用分类' ],
	'Unusedimages'               => [ '未使用文件', '未使用图像' ],
	'Unusedtemplates'            => [ '未使用模板' ],
	'Unwatchedpages'             => [ '未受监视页面' ],
	'Upload'                     => [ '上传文件', '上载档案' ],
	'UploadStash'                => [ '上传藏匿' ],
	'Userlogin'                  => [ '用户登录', '登录' ],
	'Userlogout'                 => [ '用户退出', '退出' ],
	'Userrights'                 => [ '用户权限' ],
	'Version'                    => [ '版本', '版本信息' ],
	'Wantedcategories'           => [ '需要的分类', '待撰分类' ],
	'Wantedfiles'                => [ '需要的文件' ],
	'Wantedpages'                => [ '需要的页面', '待撰页面', '受损链接' ],
	'Wantedtemplates'            => [ '需要的模板' ],
	'Watchlist'                  => [ '监视列表' ],
	'Whatlinkshere'              => [ '链入页面' ],
	'Withoutinterwiki'           => [ '无跨wiki', '无跨wiki链接页面' ],
];

/** @phpcs-require-sorted-array */
$magicWords = [
	'anchorencode'              => [ '0', '锚编码', 'ANCHORENCODE' ],
	'articlepath'               => [ '0', '条目路径', 'ARTICLEPATH' ],
	'basepagename'              => [ '1', '基础页面名称', 'BASEPAGENAME' ],
	'basepagenamee'             => [ '1', '基础页面名称等同', 'BASEPAGENAMEE' ],
	'canonicalurl'              => [ '0', '规范URL:', 'CANONICALURL:' ],
	'canonicalurle'             => [ '0', '规范URL等同:', 'CANONICALURLE:' ],
	'cascadingsources'          => [ '1', '级联来源', 'CASCADINGSOURCES' ],
	'contentlanguage'           => [ '1', '内容语言', 'CONTENTLANGUAGE', 'CONTENTLANG' ],
	'currentday'                => [ '1', '今天', 'CURRENTDAY' ],
	'currentday2'               => [ '1', '今天2', 'CURRENTDAY2' ],
	'currentdayname'            => [ '1', '星期', '今天名', '今天名称', 'CURRENTDAYNAME' ],
	'currentdow'                => [ '1', '当前DOW', 'CURRENTDOW' ],
	'currenthour'               => [ '1', '当前小时', 'CURRENTHOUR' ],
	'currentmonth'              => [ '1', '本月', '本月2', 'CURRENTMONTH', 'CURRENTMONTH2' ],
	'currentmonth1'             => [ '1', '本月1', 'CURRENTMONTH1' ],
	'currentmonthabbrev'        => [ '1', '本月简称', 'CURRENTMONTHABBREV' ],
	'currentmonthname'          => [ '1', '本月名', '本月名称', 'CURRENTMONTHNAME' ],
	'currentmonthnamegen'       => [ '1', '本月名属格', '本月名称属格', 'CURRENTMONTHNAMEGEN' ],
	'currenttime'               => [ '1', '当前时间', '此时', 'CURRENTTIME' ],
	'currenttimestamp'          => [ '1', '当前时间戳', 'CURRENTTIMESTAMP' ],
	'currentversion'            => [ '1', '当前版本', 'CURRENTVERSION' ],
	'currentweek'               => [ '1', '本周', 'CURRENTWEEK' ],
	'currentyear'               => [ '1', '今年', 'CURRENTYEAR' ],
	'defaultsort'               => [ '1', '默认排序:', '默认排序关键字:', '默认分类排序:', 'DEFAULTSORT:', 'DEFAULTSORTKEY:', 'DEFAULTCATEGORYSORT:' ],
	'defaultsort_noerror'       => [ '0', '不报错', 'noerror' ],
	'defaultsort_noreplace'     => [ '0', '不替换', 'noreplace' ],
	'directionmark'             => [ '1', '方向标记', 'DIRECTIONMARK', 'DIRMARK' ],
	'displaytitle'              => [ '1', '显示标题', 'DISPLAYTITLE' ],
	'displaytitle_noerror'      => [ '0', '无错误', 'noerror' ],
	'displaytitle_noreplace'    => [ '0', '无代替', 'noreplace' ],
	'filepath'                  => [ '0', '文件路径:', 'FILEPATH:' ],
	'forcetoc'                  => [ '0', '__强显目录__', '__FORCETOC__' ],
	'formatdate'                => [ '0', '格式化日期', '日期格式化', 'formatdate', 'dateformat' ],
	'formatnum'                 => [ '0', '格式化数字', 'FORMATNUM' ],
	'fullpagename'              => [ '1', '页面全称', '完整页面名称', 'FULLPAGENAME' ],
	'fullpagenamee'             => [ '1', '完整页面名称等同', 'FULLPAGENAMEE' ],
	'fullurl'                   => [ '0', '完整URL:', 'FULLURL:' ],
	'fullurle'                  => [ '0', '完整URL等同:', 'FULLURLE:' ],
	'gender'                    => [ '0', '性别:', 'GENDER:' ],
	'grammar'                   => [ '0', '语法:', 'GRAMMAR:' ],
	'hiddencat'                 => [ '1', '__隐藏分类__', '__HIDDENCAT__' ],
	'img_alt'                   => [ '1', '替代=$1', '替代文本=$1', 'alt=$1' ],
	'img_baseline'              => [ '1', '基线', 'baseline' ],
	'img_border'                => [ '1', '边框', 'border' ],
	'img_bottom'                => [ '1', '底部', 'bottom' ],
	'img_center'                => [ '1', '居中', 'center', 'centre' ],
	'img_class'                 => [ '1', '类=$1', 'class=$1' ],
	'img_framed'                => [ '1', '有框', 'framed', 'enframed', 'frame' ],
	'img_frameless'             => [ '1', '无框', 'frameless' ],
	'img_lang'                  => [ '1', '语言=$1', 'lang=$1' ],
	'img_left'                  => [ '1', '左', 'left' ],
	'img_link'                  => [ '1', '链接=$1', 'link=$1' ],
	'img_manualthumb'           => [ '1', '缩略图=$1', 'thumbnail=$1', 'thumb=$1' ],
	'img_middle'                => [ '1', '中间', 'middle' ],
	'img_none'                  => [ '1', '无', 'none' ],
	'img_page'                  => [ '1', '页数=$1', '$1页', 'page=$1', 'page $1' ],
	'img_right'                 => [ '1', '右', 'right' ],
	'img_sub'                   => [ '1', '子', 'sub' ],
	'img_super'                 => [ '1', '超', 'super', 'sup' ],
	'img_text_bottom'           => [ '1', '文字底部', 'text-bottom' ],
	'img_text_top'              => [ '1', '文字顶部', 'text-top' ],
	'img_thumbnail'             => [ '1', '缩略图', 'thumbnail', 'thumb' ],
	'img_top'                   => [ '1', '顶部', 'top' ],
	'img_upright'               => [ '1', '右上', '右上=$1', '右上$1', 'upright', 'upright=$1', 'upright $1' ],
	'img_width'                 => [ '1', '$1像素', '$1px' ],
	'index'                     => [ '1', '__索引__', '__INDEX__' ],
	'int'                       => [ '0', '界面:', 'INT:' ],
	'language'                  => [ '0', '#语言', '#LANGUAGE' ],
	'lc'                        => [ '0', '小写:', 'LC:' ],
	'lcfirst'                   => [ '0', '小写首字:', 'LCFIRST:' ],
	'localday'                  => [ '1', '本地日', 'LOCALDAY' ],
	'localday2'                 => [ '1', '本地日2', 'LOCALDAY2' ],
	'localdayname'              => [ '1', '本地日名', 'LOCALDAYNAME' ],
	'localdow'                  => [ '1', '本地DOW', 'LOCALDOW' ],
	'localhour'                 => [ '1', '本地小时', 'LOCALHOUR' ],
	'localmonth'                => [ '1', '本地月', '本地月2', 'LOCALMONTH', 'LOCALMONTH2' ],
	'localmonth1'               => [ '1', '本地月1', 'LOCALMONTH1' ],
	'localmonthabbrev'          => [ '1', '本地月缩写', 'LOCALMONTHABBREV' ],
	'localmonthname'            => [ '1', '本地月份名', 'LOCALMONTHNAME' ],
	'localmonthnamegen'         => [ '1', '本地月历', 'LOCALMONTHNAMEGEN' ],
	'localtime'                 => [ '1', '本地时间', 'LOCALTIME' ],
	'localtimestamp'            => [ '1', '本地时间戳', 'LOCALTIMESTAMP' ],
	'localurl'                  => [ '0', '本地URL:', 'LOCALURL:' ],
	'localurle'                 => [ '0', '本地URLE:', 'LOCALURLE:' ],
	'localweek'                 => [ '1', '本地周', 'LOCALWEEK' ],
	'localyear'                 => [ '1', '本地年', 'LOCALYEAR' ],
	'namespace'                 => [ '1', '名字空间', 'NAMESPACE' ],
	'namespacee'                => [ '1', '名字空间等同', 'NAMESPACEE' ],
	'namespacenumber'           => [ '1', '名字空间编号', 'NAMESPACENUMBER' ],
	'newsectionlink'            => [ '1', '__新段落链接__', '__NEWSECTIONLINK__' ],
	'nocontentconvert'          => [ '0', '__不转换内容__', '__NOCONTENTCONVERT__', '__NOCC__' ],
	'noeditsection'             => [ '0', '__无编辑段落__', '__无段落编辑__', '__NOEDITSECTION__' ],
	'nogallery'                 => [ '0', '__无图库__', '__NOGALLERY__' ],
	'noindex'                   => [ '1', '__无索引__', '__NOINDEX__' ],
	'nonewsectionlink'          => [ '1', '__无新段落链接__', '__NONEWSECTIONLINK__' ],
	'notitleconvert'            => [ '0', '__不转换标题__', '__NOTITLECONVERT__', '__NOTC__' ],
	'notoc'                     => [ '0', '__无目录__', '__NOTOC__' ],
	'ns'                        => [ '0', '名称空间:', 'NS:' ],
	'nse'                       => [ '0', '名称空间E:', 'NSE:' ],
	'numberingroup'             => [ '1', '组中用户数', 'NUMBERINGROUP', 'NUMINGROUP' ],
	'numberofactiveusers'       => [ '1', '活跃用户数', 'NUMBEROFACTIVEUSERS' ],
	'numberofadmins'            => [ '1', '管理员数', 'NUMBEROFADMINS' ],
	'numberofarticles'          => [ '1', '条目数', 'NUMBEROFARTICLES' ],
	'numberofedits'             => [ '1', '编辑数', 'NUMBEROFEDITS' ],
	'numberoffiles'             => [ '1', '文件数', 'NUMBEROFFILES' ],
	'numberofpages'             => [ '1', '页面数', 'NUMBEROFPAGES' ],
	'numberofusers'             => [ '1', '用户数', 'NUMBEROFUSERS' ],
	'padleft'                   => [ '0', '左填充', 'PADLEFT' ],
	'padright'                  => [ '0', '右填充', 'PADRIGHT' ],
	'pageid'                    => [ '0', '页面ID', 'PAGEID' ],
	'pagename'                  => [ '1', '页名', '页面名', '页面名称', 'PAGENAME' ],
	'pagenamee'                 => [ '1', '页面名等同', '页面名称等同', 'PAGENAMEE' ],
	'pagesincategory'           => [ '1', '分类中页面数', 'PAGESINCATEGORY', 'PAGESINCAT' ],
	'pagesincategory_all'       => [ '0', '所有', 'all' ],
	'pagesincategory_files'     => [ '0', '文件', 'files' ],
	'pagesincategory_pages'     => [ '0', '页面', 'pages' ],
	'pagesincategory_subcats'   => [ '0', '子分类', 'subcats' ],
	'pagesinnamespace'          => [ '1', '名字空间中页面数:', 'PAGESINNAMESPACE:', 'PAGESINNS:' ],
	'pagesize'                  => [ '1', '页面大小', 'PAGESIZE' ],
	'plural'                    => [ '0', '复数:', 'PLURAL:' ],
	'protectionlevel'           => [ '1', '保护级别', 'PROTECTIONLEVEL' ],
	'raw'                       => [ '0', '原始:', 'RAW:' ],
	'redirect'                  => [ '0', '#重定向', '#REDIRECT' ],
	'revisionday'               => [ '1', '修订日', 'REVISIONDAY' ],
	'revisionday2'              => [ '1', '修订日2', 'REVISIONDAY2' ],
	'revisionid'                => [ '1', '修订ID', 'REVISIONID' ],
	'revisionmonth'             => [ '1', '修订月', 'REVISIONMONTH' ],
	'revisionmonth1'            => [ '1', '修订月1', 'REVISIONMONTH1' ],
	'revisionsize'              => [ '1', '修订大小', 'REVISIONSIZE' ],
	'revisiontimestamp'         => [ '1', '修订时间戳', 'REVISIONTIMESTAMP' ],
	'revisionuser'              => [ '1', '修订用户', 'REVISIONUSER' ],
	'revisionyear'              => [ '1', '修订年', 'REVISIONYEAR' ],
	'rootpagename'              => [ '1', '根页面名称', 'ROOTPAGENAME' ],
	'rootpagenamee'             => [ '1', '根页面名称等同', 'ROOTPAGENAMEE' ],
	'safesubst'                 => [ '0', '安全替代:', 'SAFESUBST:' ],
	'scriptpath'                => [ '0', '脚本路径', 'SCRIPTPATH' ],
	'server'                    => [ '0', '服务器', 'SERVER' ],
	'servername'                => [ '0', '服务器名', 'SERVERNAME' ],
	'sitename'                  => [ '1', '站点名称', 'SITENAME' ],
	'special'                   => [ '0', '特殊', 'special' ],
	'speciale'                  => [ '0', '特殊等同', 'speciale' ],
	'staticredirect'            => [ '1', '__静态重定向__', '__STATICREDIRECT__' ],
	'stylepath'                 => [ '0', '样式路径', 'STYLEPATH' ],
	'subjectpagename'           => [ '1', '主名字空间页面名称', '条目页面名称', 'SUBJECTPAGENAME', 'ARTICLEPAGENAME' ],
	'subjectpagenamee'          => [ '1', '主名字空间页面名称等同', '条目页面名称等同', 'SUBJECTPAGENAMEE', 'ARTICLEPAGENAMEE' ],
	'subjectspace'              => [ '1', '主名字空间', '条目名字空间', 'SUBJECTSPACE', 'ARTICLESPACE' ],
	'subjectspacee'             => [ '1', '主名字空间等同', '条目名字空间等同', 'SUBJECTSPACEE', 'ARTICLESPACEE' ],
	'subpagename'               => [ '1', '子页面名称', 'SUBPAGENAME' ],
	'subpagenamee'              => [ '1', '子页面名称等同', 'SUBPAGENAMEE' ],
	'subst'                     => [ '0', '替代:', 'SUBST:' ],
	'tag'                       => [ '0', '标记', 'tag' ],
	'talkpagename'              => [ '1', '讨论页面名称', '对话页面名称', 'TALKPAGENAME' ],
	'talkpagenamee'             => [ '1', '讨论页面名称等同', '对话页面名称等同', 'TALKPAGENAMEE' ],
	'talkspace'                 => [ '1', '讨论空间', '讨论名字空间', 'TALKSPACE' ],
	'talkspacee'                => [ '1', '讨论空间等同', '讨论名字空间等同', 'TALKSPACEE' ],
	'toc'                       => [ '0', '__目录__', '__TOC__' ],
	'uc'                        => [ '0', '大写:', 'UC:' ],
	'ucfirst'                   => [ '0', '大写首字:', 'UCFIRST:' ],
	'urlencode'                 => [ '0', 'URL编码:', 'URLENCODE:' ],
	'url_path'                  => [ '0', '路径', 'PATH' ],
	'url_query'                 => [ '0', '查询', 'QUERY' ],
];

$linkTrail = '/^()(.*)$/sD';

$datePreferences = [
	'default',
	'ISO 8601',
];
$defaultDateFormat = 'zh';
$dateFormats = [
	'zh time' => 'H:i',
	'zh date' => 'Y年n月j日 (l)',
	'zh both' => 'Y年n月j日 (D) H:i',
];

$bookstoreList = [
	'AddALL' => 'http://www.addall.com/New/Partner.cgi?query=$1&type=ISBN',
	'Barnes & Noble' => 'https://www.barnesandnoble.com/w/?ean=$1',
	'亚马逊' => 'https://www.amazon.com/exec/obidos/ISBN=$1',
	'亚马逊中国' => 'https://www.amazon.cn/s?i=stripbooks&k=$1',
	'当当网' => 'https://search.dangdang.com/?key=$1',
	'博客来书店' => 'https://search.books.com.tw/search/query/key/$1',
	'三民书店' => 'https://www.sanmin.com.tw/Search/Index/?ct=ISBN&qu=$1',
];
