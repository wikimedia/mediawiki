<?php /* Smarty version 2.6.2, created on 2004-03-24 20:26:29
         compiled from /var/www-phase3/Smarty-2.6.2/libs/debug.tpl */ ?>
<?php require_once(SMARTY_DIR . 'core' . DIRECTORY_SEPARATOR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'assign_debug_info', '/var/www-phase3/Smarty-2.6.2/libs/debug.tpl', 5, false),array('modifier', 'escape', '/var/www-phase3/Smarty-2.6.2/libs/debug.tpl', 12, false),array('modifier', 'string_format', '/var/www-phase3/Smarty-2.6.2/libs/debug.tpl', 12, false),array('modifier', 'debug_print_var', '/var/www-phase3/Smarty-2.6.2/libs/debug.tpl', 18, false),)), $this); ?>


<?php echo smarty_function_assign_debug_info(array(), $this);?>


<?php if (isset ( $this->_tpl_vars['_smarty_debug_output'] ) && $this->_tpl_vars['_smarty_debug_output'] == 'html'): ?>
	<table border=0 width=100%>
	<tr bgcolor=#cccccc><th colspan=2>Smarty Debug Console</th></tr>
	<tr bgcolor=#cccccc><td colspan=2><b>included templates & config files (load time in seconds):</b></td></tr>
	<?php if (isset($this->_sections['templates'])) unset($this->_sections['templates']);
$this->_sections['templates']['name'] = 'templates';
$this->_sections['templates']['loop'] = is_array($_loop=$this->_tpl_vars['_debug_tpls']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['templates']['show'] = true;
$this->_sections['templates']['max'] = $this->_sections['templates']['loop'];
$this->_sections['templates']['step'] = 1;
$this->_sections['templates']['start'] = $this->_sections['templates']['step'] > 0 ? 0 : $this->_sections['templates']['loop']-1;
if ($this->_sections['templates']['show']) {
    $this->_sections['templates']['total'] = $this->_sections['templates']['loop'];
    if ($this->_sections['templates']['total'] == 0)
        $this->_sections['templates']['show'] = false;
} else
    $this->_sections['templates']['total'] = 0;
if ($this->_sections['templates']['show']):

            for ($this->_sections['templates']['index'] = $this->_sections['templates']['start'], $this->_sections['templates']['iteration'] = 1;
                 $this->_sections['templates']['iteration'] <= $this->_sections['templates']['total'];
                 $this->_sections['templates']['index'] += $this->_sections['templates']['step'], $this->_sections['templates']['iteration']++):
$this->_sections['templates']['rownum'] = $this->_sections['templates']['iteration'];
$this->_sections['templates']['index_prev'] = $this->_sections['templates']['index'] - $this->_sections['templates']['step'];
$this->_sections['templates']['index_next'] = $this->_sections['templates']['index'] + $this->_sections['templates']['step'];
$this->_sections['templates']['first']      = ($this->_sections['templates']['iteration'] == 1);
$this->_sections['templates']['last']       = ($this->_sections['templates']['iteration'] == $this->_sections['templates']['total']);
?>
		<tr bgcolor=<?php if (!(1 & $this->_sections['templates']['index'])): ?>#eeeeee<?php else: ?>#fafafa<?php endif; ?>><td colspan=2><tt><?php if (isset($this->_sections['indent'])) unset($this->_sections['indent']);
$this->_sections['indent']['name'] = 'indent';
$this->_sections['indent']['loop'] = is_array($_loop=$this->_tpl_vars['_debug_tpls'][$this->_sections['templates']['index']]['depth']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['indent']['show'] = true;
$this->_sections['indent']['max'] = $this->_sections['indent']['loop'];
$this->_sections['indent']['step'] = 1;
$this->_sections['indent']['start'] = $this->_sections['indent']['step'] > 0 ? 0 : $this->_sections['indent']['loop']-1;
if ($this->_sections['indent']['show']) {
    $this->_sections['indent']['total'] = $this->_sections['indent']['loop'];
    if ($this->_sections['indent']['total'] == 0)
        $this->_sections['indent']['show'] = false;
} else
    $this->_sections['indent']['total'] = 0;
if ($this->_sections['indent']['show']):

            for ($this->_sections['indent']['index'] = $this->_sections['indent']['start'], $this->_sections['indent']['iteration'] = 1;
                 $this->_sections['indent']['iteration'] <= $this->_sections['indent']['total'];
                 $this->_sections['indent']['index'] += $this->_sections['indent']['step'], $this->_sections['indent']['iteration']++):
$this->_sections['indent']['rownum'] = $this->_sections['indent']['iteration'];
$this->_sections['indent']['index_prev'] = $this->_sections['indent']['index'] - $this->_sections['indent']['step'];
$this->_sections['indent']['index_next'] = $this->_sections['indent']['index'] + $this->_sections['indent']['step'];
$this->_sections['indent']['first']      = ($this->_sections['indent']['iteration'] == 1);
$this->_sections['indent']['last']       = ($this->_sections['indent']['iteration'] == $this->_sections['indent']['total']);
?>&nbsp;&nbsp;&nbsp;<?php endfor; endif; ?><font color=<?php if ($this->_tpl_vars['_debug_tpls'][$this->_sections['templates']['index']]['type'] == 'template'): ?>brown<?php elseif ($this->_tpl_vars['_debug_tpls'][$this->_sections['templates']['index']]['type'] == 'insert'): ?>black<?php else: ?>green<?php endif; ?>><?php echo ((is_array($_tmp=$this->_tpl_vars['_debug_tpls'][$this->_sections['templates']['index']]['filename'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
</font><?php if (isset ( $this->_tpl_vars['_debug_tpls'][$this->_sections['templates']['index']]['exec_time'] )): ?> <font size=-1><i>(<?php echo ((is_array($_tmp=$this->_tpl_vars['_debug_tpls'][$this->_sections['templates']['index']]['exec_time'])) ? $this->_run_mod_handler('string_format', true, $_tmp, "%.5f") : smarty_modifier_string_format($_tmp, "%.5f")); ?>
)<?php if ($this->_sections['templates']['index'] == 0): ?> (total)<?php endif; ?></i></font><?php endif; ?></tt></td></tr>
	<?php endfor; else: ?>
		<tr bgcolor=#eeeeee><td colspan=2><tt><i>no templates included</i></tt></td></tr>	
	<?php endif; ?>
	<tr bgcolor=#cccccc><td colspan=2><b>assigned template variables:</b></td></tr>
	<?php if (isset($this->_sections['vars'])) unset($this->_sections['vars']);
$this->_sections['vars']['name'] = 'vars';
$this->_sections['vars']['loop'] = is_array($_loop=$this->_tpl_vars['_debug_keys']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['vars']['show'] = true;
$this->_sections['vars']['max'] = $this->_sections['vars']['loop'];
$this->_sections['vars']['step'] = 1;
$this->_sections['vars']['start'] = $this->_sections['vars']['step'] > 0 ? 0 : $this->_sections['vars']['loop']-1;
if ($this->_sections['vars']['show']) {
    $this->_sections['vars']['total'] = $this->_sections['vars']['loop'];
    if ($this->_sections['vars']['total'] == 0)
        $this->_sections['vars']['show'] = false;
} else
    $this->_sections['vars']['total'] = 0;
if ($this->_sections['vars']['show']):

            for ($this->_sections['vars']['index'] = $this->_sections['vars']['start'], $this->_sections['vars']['iteration'] = 1;
                 $this->_sections['vars']['iteration'] <= $this->_sections['vars']['total'];
                 $this->_sections['vars']['index'] += $this->_sections['vars']['step'], $this->_sections['vars']['iteration']++):
$this->_sections['vars']['rownum'] = $this->_sections['vars']['iteration'];
$this->_sections['vars']['index_prev'] = $this->_sections['vars']['index'] - $this->_sections['vars']['step'];
$this->_sections['vars']['index_next'] = $this->_sections['vars']['index'] + $this->_sections['vars']['step'];
$this->_sections['vars']['first']      = ($this->_sections['vars']['iteration'] == 1);
$this->_sections['vars']['last']       = ($this->_sections['vars']['iteration'] == $this->_sections['vars']['total']);
?>
		<tr bgcolor=<?php if (!(1 & $this->_sections['vars']['index'])): ?>#eeeeee<?php else: ?>#fafafa<?php endif; ?>><td valign=top><tt><font color=blue>{$<?php echo $this->_tpl_vars['_debug_keys'][$this->_sections['vars']['index']]; ?>
}</font></tt></td><td nowrap><tt><font color=green><?php echo smarty_modifier_debug_print_var($this->_tpl_vars['_debug_vals'][$this->_sections['vars']['index']]); ?>
</font></tt></td></tr>
	<?php endfor; else: ?>
		<tr bgcolor=#eeeeee><td colspan=2><tt><i>no template variables assigned</i></tt></td></tr>	
	<?php endif; ?>
	<tr bgcolor=#cccccc><td colspan=2><b>assigned config file variables (outer template scope):</b></td></tr>
	<?php if (isset($this->_sections['config_vars'])) unset($this->_sections['config_vars']);
$this->_sections['config_vars']['name'] = 'config_vars';
$this->_sections['config_vars']['loop'] = is_array($_loop=$this->_tpl_vars['_debug_config_keys']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['config_vars']['show'] = true;
$this->_sections['config_vars']['max'] = $this->_sections['config_vars']['loop'];
$this->_sections['config_vars']['step'] = 1;
$this->_sections['config_vars']['start'] = $this->_sections['config_vars']['step'] > 0 ? 0 : $this->_sections['config_vars']['loop']-1;
if ($this->_sections['config_vars']['show']) {
    $this->_sections['config_vars']['total'] = $this->_sections['config_vars']['loop'];
    if ($this->_sections['config_vars']['total'] == 0)
        $this->_sections['config_vars']['show'] = false;
} else
    $this->_sections['config_vars']['total'] = 0;
if ($this->_sections['config_vars']['show']):

            for ($this->_sections['config_vars']['index'] = $this->_sections['config_vars']['start'], $this->_sections['config_vars']['iteration'] = 1;
                 $this->_sections['config_vars']['iteration'] <= $this->_sections['config_vars']['total'];
                 $this->_sections['config_vars']['index'] += $this->_sections['config_vars']['step'], $this->_sections['config_vars']['iteration']++):
$this->_sections['config_vars']['rownum'] = $this->_sections['config_vars']['iteration'];
$this->_sections['config_vars']['index_prev'] = $this->_sections['config_vars']['index'] - $this->_sections['config_vars']['step'];
$this->_sections['config_vars']['index_next'] = $this->_sections['config_vars']['index'] + $this->_sections['config_vars']['step'];
$this->_sections['config_vars']['first']      = ($this->_sections['config_vars']['iteration'] == 1);
$this->_sections['config_vars']['last']       = ($this->_sections['config_vars']['iteration'] == $this->_sections['config_vars']['total']);
?>
		<tr bgcolor=<?php if (!(1 & $this->_sections['config_vars']['index'])): ?>#eeeeee<?php else: ?>#fafafa<?php endif; ?>><td valign=top><tt><font color=maroon>{#<?php echo $this->_tpl_vars['_debug_config_keys'][$this->_sections['config_vars']['index']]; ?>
#}</font></tt></td><td><tt><font color=green><?php echo smarty_modifier_debug_print_var($this->_tpl_vars['_debug_config_vals'][$this->_sections['config_vars']['index']]); ?>
</font></tt></td></tr>
	<?php endfor; else: ?>
		<tr bgcolor=#eeeeee><td colspan=2><tt><i>no config vars assigned</i></tt></td></tr>	
	<?php endif; ?>
	</table>
</BODY></HTML>
<?php else: ?>
<SCRIPT language=javascript>
	if( self.name == '' ) {
	   var title = 'Console';
	}
	else {
	   var title = 'Console_' + self.name;
	}
	_smarty_console = window.open("",title.value,"width=680,height=600,resizable,scrollbars=yes");
	_smarty_console.document.write("<HTML><TITLE>Smarty Debug Console_"+self.name+"</TITLE><BODY bgcolor=#ffffff>");
	_smarty_console.document.write("<table border=0 width=100%>");
	_smarty_console.document.write("<tr bgcolor=#cccccc><th colspan=2>Smarty Debug Console</th></tr>");
	_smarty_console.document.write("<tr bgcolor=#cccccc><td colspan=2><b>included templates & config files (load time in seconds):</b></td></tr>");
	<?php if (isset($this->_sections['templates'])) unset($this->_sections['templates']);
$this->_sections['templates']['name'] = 'templates';
$this->_sections['templates']['loop'] = is_array($_loop=$this->_tpl_vars['_debug_tpls']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['templates']['show'] = true;
$this->_sections['templates']['max'] = $this->_sections['templates']['loop'];
$this->_sections['templates']['step'] = 1;
$this->_sections['templates']['start'] = $this->_sections['templates']['step'] > 0 ? 0 : $this->_sections['templates']['loop']-1;
if ($this->_sections['templates']['show']) {
    $this->_sections['templates']['total'] = $this->_sections['templates']['loop'];
    if ($this->_sections['templates']['total'] == 0)
        $this->_sections['templates']['show'] = false;
} else
    $this->_sections['templates']['total'] = 0;
if ($this->_sections['templates']['show']):

            for ($this->_sections['templates']['index'] = $this->_sections['templates']['start'], $this->_sections['templates']['iteration'] = 1;
                 $this->_sections['templates']['iteration'] <= $this->_sections['templates']['total'];
                 $this->_sections['templates']['index'] += $this->_sections['templates']['step'], $this->_sections['templates']['iteration']++):
$this->_sections['templates']['rownum'] = $this->_sections['templates']['iteration'];
$this->_sections['templates']['index_prev'] = $this->_sections['templates']['index'] - $this->_sections['templates']['step'];
$this->_sections['templates']['index_next'] = $this->_sections['templates']['index'] + $this->_sections['templates']['step'];
$this->_sections['templates']['first']      = ($this->_sections['templates']['iteration'] == 1);
$this->_sections['templates']['last']       = ($this->_sections['templates']['iteration'] == $this->_sections['templates']['total']);
?>
		_smarty_console.document.write("<tr bgcolor=<?php if (!(1 & $this->_sections['templates']['index'])): ?>#eeeeee<?php else: ?>#fafafa<?php endif; ?>><td colspan=2><tt><?php if (isset($this->_sections['indent'])) unset($this->_sections['indent']);
$this->_sections['indent']['name'] = 'indent';
$this->_sections['indent']['loop'] = is_array($_loop=$this->_tpl_vars['_debug_tpls'][$this->_sections['templates']['index']]['depth']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['indent']['show'] = true;
$this->_sections['indent']['max'] = $this->_sections['indent']['loop'];
$this->_sections['indent']['step'] = 1;
$this->_sections['indent']['start'] = $this->_sections['indent']['step'] > 0 ? 0 : $this->_sections['indent']['loop']-1;
if ($this->_sections['indent']['show']) {
    $this->_sections['indent']['total'] = $this->_sections['indent']['loop'];
    if ($this->_sections['indent']['total'] == 0)
        $this->_sections['indent']['show'] = false;
} else
    $this->_sections['indent']['total'] = 0;
if ($this->_sections['indent']['show']):

            for ($this->_sections['indent']['index'] = $this->_sections['indent']['start'], $this->_sections['indent']['iteration'] = 1;
                 $this->_sections['indent']['iteration'] <= $this->_sections['indent']['total'];
                 $this->_sections['indent']['index'] += $this->_sections['indent']['step'], $this->_sections['indent']['iteration']++):
$this->_sections['indent']['rownum'] = $this->_sections['indent']['iteration'];
$this->_sections['indent']['index_prev'] = $this->_sections['indent']['index'] - $this->_sections['indent']['step'];
$this->_sections['indent']['index_next'] = $this->_sections['indent']['index'] + $this->_sections['indent']['step'];
$this->_sections['indent']['first']      = ($this->_sections['indent']['iteration'] == 1);
$this->_sections['indent']['last']       = ($this->_sections['indent']['iteration'] == $this->_sections['indent']['total']);
?>&nbsp;&nbsp;&nbsp;<?php endfor; endif; ?><font color=<?php if ($this->_tpl_vars['_debug_tpls'][$this->_sections['templates']['index']]['type'] == 'template'): ?>brown<?php elseif ($this->_tpl_vars['_debug_tpls'][$this->_sections['templates']['index']]['type'] == 'insert'): ?>black<?php else: ?>green<?php endif; ?>><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['_debug_tpls'][$this->_sections['templates']['index']]['filename'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')))) ? $this->_run_mod_handler('escape', true, $_tmp, 'javascript') : smarty_modifier_escape($_tmp, 'javascript')); ?>
</font><?php if (isset ( $this->_tpl_vars['_debug_tpls'][$this->_sections['templates']['index']]['exec_time'] )): ?> <font size=-1><i>(<?php echo ((is_array($_tmp=$this->_tpl_vars['_debug_tpls'][$this->_sections['templates']['index']]['exec_time'])) ? $this->_run_mod_handler('string_format', true, $_tmp, "%.5f") : smarty_modifier_string_format($_tmp, "%.5f")); ?>
)<?php if ($this->_sections['templates']['index'] == 0): ?> (total)<?php endif; ?></i></font><?php endif; ?></tt></td></tr>");
	<?php endfor; else: ?>
		_smarty_console.document.write("<tr bgcolor=#eeeeee><td colspan=2><tt><i>no templates included</i></tt></td></tr>");	
	<?php endif; ?>
	_smarty_console.document.write("<tr bgcolor=#cccccc><td colspan=2><b>assigned template variables:</b></td></tr>");
	<?php if (isset($this->_sections['vars'])) unset($this->_sections['vars']);
$this->_sections['vars']['name'] = 'vars';
$this->_sections['vars']['loop'] = is_array($_loop=$this->_tpl_vars['_debug_keys']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['vars']['show'] = true;
$this->_sections['vars']['max'] = $this->_sections['vars']['loop'];
$this->_sections['vars']['step'] = 1;
$this->_sections['vars']['start'] = $this->_sections['vars']['step'] > 0 ? 0 : $this->_sections['vars']['loop']-1;
if ($this->_sections['vars']['show']) {
    $this->_sections['vars']['total'] = $this->_sections['vars']['loop'];
    if ($this->_sections['vars']['total'] == 0)
        $this->_sections['vars']['show'] = false;
} else
    $this->_sections['vars']['total'] = 0;
if ($this->_sections['vars']['show']):

            for ($this->_sections['vars']['index'] = $this->_sections['vars']['start'], $this->_sections['vars']['iteration'] = 1;
                 $this->_sections['vars']['iteration'] <= $this->_sections['vars']['total'];
                 $this->_sections['vars']['index'] += $this->_sections['vars']['step'], $this->_sections['vars']['iteration']++):
$this->_sections['vars']['rownum'] = $this->_sections['vars']['iteration'];
$this->_sections['vars']['index_prev'] = $this->_sections['vars']['index'] - $this->_sections['vars']['step'];
$this->_sections['vars']['index_next'] = $this->_sections['vars']['index'] + $this->_sections['vars']['step'];
$this->_sections['vars']['first']      = ($this->_sections['vars']['iteration'] == 1);
$this->_sections['vars']['last']       = ($this->_sections['vars']['iteration'] == $this->_sections['vars']['total']);
?>
		_smarty_console.document.write("<tr bgcolor=<?php if (!(1 & $this->_sections['vars']['index'])): ?>#eeeeee<?php else: ?>#fafafa<?php endif; ?>><td valign=top><tt><font color=blue>{$<?php echo $this->_tpl_vars['_debug_keys'][$this->_sections['vars']['index']]; ?>
}</font></tt></td><td nowrap><tt><font color=green><?php echo ((is_array($_tmp=smarty_modifier_debug_print_var($this->_tpl_vars['_debug_vals'][$this->_sections['vars']['index']]))) ? $this->_run_mod_handler('escape', true, $_tmp, 'javascript') : smarty_modifier_escape($_tmp, 'javascript')); ?>
</font></tt></td></tr>");
	<?php endfor; else: ?>
		_smarty_console.document.write("<tr bgcolor=#eeeeee><td colspan=2><tt><i>no template variables assigned</i></tt></td></tr>");	
	<?php endif; ?>
	_smarty_console.document.write("<tr bgcolor=#cccccc><td colspan=2><b>assigned config file variables (outer template scope):</b></td></tr>");
	<?php if (isset($this->_sections['config_vars'])) unset($this->_sections['config_vars']);
$this->_sections['config_vars']['name'] = 'config_vars';
$this->_sections['config_vars']['loop'] = is_array($_loop=$this->_tpl_vars['_debug_config_keys']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['config_vars']['show'] = true;
$this->_sections['config_vars']['max'] = $this->_sections['config_vars']['loop'];
$this->_sections['config_vars']['step'] = 1;
$this->_sections['config_vars']['start'] = $this->_sections['config_vars']['step'] > 0 ? 0 : $this->_sections['config_vars']['loop']-1;
if ($this->_sections['config_vars']['show']) {
    $this->_sections['config_vars']['total'] = $this->_sections['config_vars']['loop'];
    if ($this->_sections['config_vars']['total'] == 0)
        $this->_sections['config_vars']['show'] = false;
} else
    $this->_sections['config_vars']['total'] = 0;
if ($this->_sections['config_vars']['show']):

            for ($this->_sections['config_vars']['index'] = $this->_sections['config_vars']['start'], $this->_sections['config_vars']['iteration'] = 1;
                 $this->_sections['config_vars']['iteration'] <= $this->_sections['config_vars']['total'];
                 $this->_sections['config_vars']['index'] += $this->_sections['config_vars']['step'], $this->_sections['config_vars']['iteration']++):
$this->_sections['config_vars']['rownum'] = $this->_sections['config_vars']['iteration'];
$this->_sections['config_vars']['index_prev'] = $this->_sections['config_vars']['index'] - $this->_sections['config_vars']['step'];
$this->_sections['config_vars']['index_next'] = $this->_sections['config_vars']['index'] + $this->_sections['config_vars']['step'];
$this->_sections['config_vars']['first']      = ($this->_sections['config_vars']['iteration'] == 1);
$this->_sections['config_vars']['last']       = ($this->_sections['config_vars']['iteration'] == $this->_sections['config_vars']['total']);
?>
		_smarty_console.document.write("<tr bgcolor=<?php if (!(1 & $this->_sections['config_vars']['index'])): ?>#eeeeee<?php else: ?>#fafafa<?php endif; ?>><td valign=top><tt><font color=maroon>{#<?php echo $this->_tpl_vars['_debug_config_keys'][$this->_sections['config_vars']['index']]; ?>
#}</font></tt></td><td><tt><font color=green><?php echo ((is_array($_tmp=smarty_modifier_debug_print_var($this->_tpl_vars['_debug_config_vals'][$this->_sections['config_vars']['index']]))) ? $this->_run_mod_handler('escape', true, $_tmp, 'javascript') : smarty_modifier_escape($_tmp, 'javascript')); ?>
</font></tt></td></tr>");
	<?php endfor; else: ?>
		_smarty_console.document.write("<tr bgcolor=#eeeeee><td colspan=2><tt><i>no config vars assigned</i></tt></td></tr>");	
	<?php endif; ?>
	_smarty_console.document.write("</table>");
	_smarty_console.document.write("</BODY></HTML>");
	_smarty_console.document.close();
</SCRIPT>
<?php endif; ?>