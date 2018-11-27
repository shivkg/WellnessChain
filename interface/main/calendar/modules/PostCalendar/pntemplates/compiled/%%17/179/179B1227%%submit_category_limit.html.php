<?php /* Smarty version 2.6.31, created on 2018-10-08 05:36:48
         compiled from default/admin/submit_category_limit.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'config_load', 'default/admin/submit_category_limit.html', 4, false),array('modifier', 'date_format', 'default/admin/submit_category_limit.html', 89, false),)), $this); ?>
<!-- main navigation -->
<?php echo smarty_function_config_load(array('file' => "lang.".($this->_tpl_vars['USER_LANG'])), $this);?>

    
<html>
<head></head>
<body bgcolor="<?php echo $this->_tpl_vars['BGCOLOR2']; ?>
"/>
<?php echo $this->_tpl_vars['AdminMenu']; ?>

<form name="limit" action="<?php echo $this->_tpl_vars['action']; ?>
" method="post" enctype="application/x-www-form-urlencoded">
<table border="1" cellpadding="5" cellspacing="0">
			<tr>
				<td>
					<table  width ='%100' border='1'>
						<tr>
							<td colspan ='5'>
								<table width ='%100'>
									<th align="center" ><?php echo $this->_tpl_vars['_PC_NEW_LIMIT_TITLE']; ?>
</th>
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td>
								<table cellspacing='8' cellpadding='2'>
								<tr><td><?php echo $this->_tpl_vars['catTitle']; ?>
</td><td><?php echo $this->_tpl_vars['StartTimeTitle']; ?>
</td><td><?php echo $this->_tpl_vars['EndTimeTitle']; ?>
</td><td><?php echo $this->_tpl_vars['LimitTitle']; ?>
</td></tr>
            						<tr>
            							<td valign="top" align="left">
            								<select name="new<?php echo $this->_tpl_vars['catid']; ?>
">
                								<?php $_from = $this->_tpl_vars['categories']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['repeat']):
?>
                    								<option value="<?php echo $this->_tpl_vars['repeat']['id']; ?>
">
                    									<?php echo $this->_tpl_vars['repeat']['name']; ?>

                    								</option>
                								<?php endforeach; endif; unset($_from); ?>
                							</select>
                						</td>
            							<td valign="top" align="left">
    										<select name="new<?php echo $this->_tpl_vars['starttimeh']; ?>
">
                								<?php $_from = $this->_tpl_vars['hour_array']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['repeat']):
?>
                    								<option value="<?php echo $this->_tpl_vars['repeat']; ?>
">
                    									<?php echo $this->_tpl_vars['repeat']; ?>

                    								</option>
                								<?php endforeach; endif; unset($_from); ?>
                							</select>:
                							
                							<select name="new<?php echo $this->_tpl_vars['starttimem']; ?>
">
                								<?php $_from = $this->_tpl_vars['min_array']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['repeat']):
?>
                    								<option value="<?php echo $this->_tpl_vars['repeat']; ?>
">
                    									<?php echo $this->_tpl_vars['repeat']; ?>

                    								</option>
                								<?php endforeach; endif; unset($_from); ?>
                							</select>
               							
                						</td>
               							<td valign="top" align="left">                							
                							<select name="new<?php echo $this->_tpl_vars['endtimeh']; ?>
">
                								<?php $_from = $this->_tpl_vars['hour_array']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['repeat']):
?>
                    								<option value="<?php echo $this->_tpl_vars['repeat']; ?>
" >
                    									<?php echo $this->_tpl_vars['repeat']; ?>

                    								</option>
                								<?php endforeach; endif; unset($_from); ?>
                							</select>:
                							<select name="new<?php echo $this->_tpl_vars['endtimem']; ?>
">
                								<?php $_from = $this->_tpl_vars['min_array']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['repeat']):
?>
                    								<option value="<?php echo $this->_tpl_vars['repeat']; ?>
">
                    									<?php echo $this->_tpl_vars['repeat']; ?>

                    								</option>
                								<?php endforeach; endif; unset($_from); ?>
                							</select>
                					
               							</td>
               							<td align='right'>
               								<input type="text" name="new<?php echo $this->_tpl_vars['InputLimit']; ?>
" value="" size="4" />
               							</td>
                					</tr>
                				</table>
                				
                			</td>
           				</tr>
            		</table>
            		<?php echo $this->_tpl_vars['FormSubmit']; ?>

            	</td>
            </tr>
	</table>
<table border="1" cellpadding="5" cellspacing="0">
	<!--START REPEATION SECTION -->
	
		<?php $_from = $this->_tpl_vars['limits']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['limit']):
?>
		<?php $this->assign('shour', ((is_array($_tmp=$this->_tpl_vars['limit']['startTime'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%H") : smarty_modifier_date_format($_tmp, "%H"))); ?>
		<?php $this->assign('smin', ((is_array($_tmp=$this->_tpl_vars['limit']['startTime'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%M") : smarty_modifier_date_format($_tmp, "%M"))); ?>
		<?php $this->assign('ehour', ((is_array($_tmp=$this->_tpl_vars['limit']['endTime'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%H") : smarty_modifier_date_format($_tmp, "%H"))); ?>
		<?php $this->assign('emin', ((is_array($_tmp=$this->_tpl_vars['limit']['endTime'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%M") : smarty_modifier_date_format($_tmp, "%M"))); ?>
		
		
		<?php $_from = $this->_tpl_vars['categories']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['repeat']):
?>
       		
       		<?php if ($this->_tpl_vars['repeat']['id'] == $this->_tpl_vars['limit']['catid']): ?>
       				<?php $this->assign('title_color', $this->_tpl_vars['repeat']['color']); ?>
       			
       		<?php endif; ?>
        <?php endforeach; endif; unset($_from); ?>	
			<tr>
				<td>
					<table  width ='%100' border='1'>
						<tr>
							<td colspan ='5'>
								<table width ='%100'>
									<th align="center"  bgcolor="<?php echo $this->_tpl_vars['title_color']; ?>
"><?php echo $this->_tpl_vars['_PC_LIMIT_TITLE']; ?>
</th>
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td>
							<input type="checkbox" name="del[]" value="<?php echo $this->_tpl_vars['limit']['limitid']; ?>
"/>
										<?php echo $this->_tpl_vars['_PC_CAT_DELETE']; ?>

							</td>
							<td><?php echo ((is_array($_tmp=$this->_tpl_vars['limit']['starttime'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%H") : smarty_modifier_date_format($_tmp, "%H")); ?>

								<input type="hidden" name="id[]" value="<?php echo $this->_tpl_vars['limit']['limitid']; ?>
"/>
								<table cellspacing='8' cellpadding='2'>
								<tr><td><?php echo $this->_tpl_vars['catTitle']; ?>
</td><td><?php echo $this->_tpl_vars['StartTimeTitle']; ?>
</td><td><?php echo $this->_tpl_vars['EndTimeTitle']; ?>
</td><td><?php echo $this->_tpl_vars['LimitTitle']; ?>
</td></tr>
            						<tr>
            							<td valign="top" align="left">
            								<select name="<?php echo $this->_tpl_vars['catid']; ?>
[]">
                								<?php $_from = $this->_tpl_vars['categories']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['repeat']):
?>
                    								<option value="<?php echo $this->_tpl_vars['repeat']['id']; ?>
"
                    									<?php if ($this->_tpl_vars['repeat']['id'] == $this->_tpl_vars['limit']['catid']): ?>
                    										selected
                    									<?php endif; ?>
                    									>
                    									<?php echo $this->_tpl_vars['repeat']['name']; ?>

                    								</option>
                								<?php endforeach; endif; unset($_from); ?>
                							</select>
                						</td>
            							<td valign="top" align="left">
            								<select name="<?php echo $this->_tpl_vars['starttimeh']; ?>
[]">
                								<?php $_from = $this->_tpl_vars['hour_array']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['repeat']):
?>
                    								<option value="<?php echo $this->_tpl_vars['repeat']; ?>
"
                    									<?php if ($this->_tpl_vars['repeat'] == $this->_tpl_vars['shour']): ?>
                    										selected 
                    									<?php endif; ?>>
                    									<?php echo $this->_tpl_vars['repeat']; ?>

                    								</option>
                								<?php endforeach; endif; unset($_from); ?>
                							</select>:
                							
                							<select name="<?php echo $this->_tpl_vars['starttimem']; ?>
[]">
                								<?php $_from = $this->_tpl_vars['min_array']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['repeat']):
?>
                    								<option value="<?php echo $this->_tpl_vars['repeat']; ?>
"
                    									<?php if ($this->_tpl_vars['repeat'] == $this->_tpl_vars['smin']): ?>
                    										selected 
                    									<?php endif; ?>>
                    									<?php echo $this->_tpl_vars['repeat']; ?>

                    								</option>
                								<?php endforeach; endif; unset($_from); ?>
                							</select>
                					
                						</td>
               							<td valign="top" align="left">                							
                							<select name="<?php echo $this->_tpl_vars['endtimeh']; ?>
[]">
                								<?php $_from = $this->_tpl_vars['hour_array']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['repeat']):
?>
                    								<option value="<?php echo $this->_tpl_vars['repeat']; ?>
" 
														<?php if ($this->_tpl_vars['repeat'] == $this->_tpl_vars['ehour']): ?>
                    										selected 
                    									<?php endif; ?>>
                    									<?php echo $this->_tpl_vars['repeat']; ?>

                    								</option>
                								<?php endforeach; endif; unset($_from); ?>
                							</select>:
                							<select name="<?php echo $this->_tpl_vars['endtimem']; ?>
[]">
                								<?php $_from = $this->_tpl_vars['min_array']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['repeat']):
?>
                    								<option value="<?php echo $this->_tpl_vars['repeat']; ?>
"
                    									<?php if ($this->_tpl_vars['repeat'] == $this->_tpl_vars['emin']): ?>
                    										selected 
                    									<?php endif; ?>>
                    									<?php echo $this->_tpl_vars['repeat']; ?>

                    								</option>
                								<?php endforeach; endif; unset($_from); ?>
                							</select>
                							
               							</td>
               							<td align='right'>
               								<input type="text" name="<?php echo $this->_tpl_vars['InputLimit']; ?>
[]" value="<?php echo $this->_tpl_vars['limit']['limit']; ?>
" size="4" />
               							</td>
                					</tr>
                				</table>
                				
                			</td>
           				</tr>
            		</table>
            		<?php echo $this->_tpl_vars['FormSubmit']; ?>

            	</td>
            </tr>
 		<!-- /REPEATING ROWS -->
		<?php endforeach; endif; unset($_from); ?>
	</table>


<?php echo $this->_tpl_vars['FormHidden']; ?>



</form>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['TPL_NAME'])."/views/footer.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>