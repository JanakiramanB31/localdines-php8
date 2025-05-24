<form id="frmDefaultTime" action="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminTime&amp;action=pjActionIndex" class="form-horizontal" method="post">
	<input type="hidden" name="working_time" value="1" />

	<div class="m-b-md">
		<h2 class="no-margins"><?php __('menuWorkingTime') ?></h2>
	</div>

	<?php $yesno = __('plugin_base_yesno', true, false); ?>
	<?php $days = __('plugin_vouchers_days', true, false); ?>
	<div class="table-responsive table-responsive-secondary">
		<table class="table table-striped table-hover table-secondary">
			<thead>
				<tr>
					<th><?php __('lblDayOfWeek'); ?></th>
					<th><?php __('lblStartTime'); ?></th>
					<th><?php __('lblEndTime'); ?></th>
					<th class="text-right"><?php __('lblIsDayOff'); ?></th>
				</tr>
			</thead>

			<tbody>
				<?php
				foreach (pjUtil::getWeekdays() as $k)
				{
					if (isset($tpl['arr']) && count($tpl['arr']) > 0)
					{
						list($hour_from, $minute_from,) = explode(':', $tpl['arr'][$k.'_from']);
						list($hour_to, $minute_to,) = explode(':', $tpl['arr'][$k.'_to']);
						$checked = NULL;
						$disabled = false;
						if ($tpl['arr'][$k.'_dayoff'] == 'T')
						{
							$checked = ' checked="checked"';
							$disabled = true;
						}
					}else{
						$hour_from = NULL;
						$hour_to = NULL;
						$minute_from = NULL;
						$minute_to = NULL;
						$checked = NULL;
						$disabled = false;
					}
					?>
					<tr>
						<td><?php echo $days[$k]; ?></td>
						<td>
							<?php	
							$pjHourFrom = pjDateTime::factory()
								->attr('name', $k . '_hour_from')
								->attr('id', $k . '_hour_from')
								->attr('class', 'form-control')
								->prop('ampm', $tpl['time_ampm'])
								->prop('selected', $hour_from);
							if ($disabled)
							{
								$pjHourFrom->attr('disabled', 'disabled');
							}
							$pjMinuteFrom = pjDateTime::factory()
								->attr('name', $k . '_minute_from')
								->attr('id', $k . '_minute_from')
								->attr('class', 'form-control')
								->prop('selected', $minute_from)
								->prop('step', 5);
							if ($disabled)
							{
								$pjMinuteFrom->attr('disabled', 'disabled');
							}
							if ($tpl['time_ampm'])
							{
								$startAmPm = pjDateTime::factory()
									->attr('name', $k . '_ampm_from')
									->attr('id', $k . '_ampm_from')
									->attr('class', 'form-control')
									->prop('ampm', $tpl['time_ampm'])
									->prop('selected', $tpl[$k . '_ampm_from']);
								if ($disabled)
								{
									$startAmPm->attr('disabled', 'disabled');
								}
							}
							?>
							<div class="row">
								<div class="col-custom-3">
									<div class="input-group">
										<span class="input-group-btn"><?php echo $pjHourFrom->hour(); ?></span>
										<span class="input-group-btn"><?php echo $pjMinuteFrom->minute(); ?></span>
										<?php if ($tpl['time_ampm']) : ?>
										<span class="input-group-btn"><?php echo $startAmPm->ampm(); ?></span>
										<?php endif; ?>
									</div>
								</div>
							</div>
						</td>
						<td>
							<?php
							$pjHourTo = pjDateTime::factory()
								->attr('name', $k . '_hour_to')
								->attr('id', $k . '_hour_to')
								->attr('class', 'form-control greaterThan')
								->prop('ampm', $tpl['time_ampm'])
								->prop('selected', $hour_to);
							if ($disabled)
							{
								$pjHourTo->attr('disabled', 'disabled');
							}
							$pjMinuteTo = pjDateTime::factory()
								->attr('name', $k . '_minute_to')
								->attr('id', $k . '_minute_to')
								->attr('class', 'form-control')
								->prop('selected', $minute_to)
								->prop('step', 5);
							if ($disabled)
							{
								$pjMinuteTo->attr('disabled', 'disabled');
							}
							if ($tpl['time_ampm'])
							{
								$endAmPm = pjDateTime::factory()
									->attr('name', $k . '_ampm_to')
									->attr('id', $k . '_ampm_to')
									->attr('class', 'form-control')
									->prop('ampm', $tpl['time_ampm'])
									->prop('selected', $tpl[$k . '_ampm_to']);
								if ($disabled)
								{
									$endAmPm->attr('disabled', 'disabled');
								}
							}
							?>
							<div class="row">
								<div class="col-custom-3">
									<div class="input-group">
										<span class="input-group-btn"><?php echo $pjHourTo->hour(); ?></span>
										<span class="input-group-btn"><?php echo $pjMinuteTo->minute(); ?></span>
										<?php if ($tpl['time_ampm']) : ?>
										<span class="input-group-btn"><?php echo $endAmPm->ampm(); ?></span>
										<?php endif; ?>
									</div>
								</div>
							</div>
						</td>
						<td>
							<div class="clearfix">
								<div class="switch onoffswitch-data pull-right">
									<div class="onoffswitch">
										<input type="checkbox" class="onoffswitch-checkbox working-day" id="<?php echo $k ?>_dayoff" name="<?php echo $k ?>_dayoff" value="T" <?php echo $checked ?>>
										<label class="onoffswitch-label" for="<?php echo $k ?>_dayoff">
											<span class="onoffswitch-inner" data-on="<?php echo $yesno['T'] ?>" data-off="<?php echo $yesno['F'] ?>"></span>
											<span class="onoffswitch-switch"></span>
										</label>
									</div>
								</div>
							</div>
						</td>
					</tr>
					<?php
				}
				?>
			</tbody>
		</table>
	</div>

	<div class="hr-line-dashed"></div>

	<div class="clearfix">
		<button type="submit" class="ladda-button btn btn-primary btn-lg pull-left btn-phpjabbers-loader" data-style="zoom-in">
			<span class="ladda-label"><?php __('plugin_base_btn_save'); ?></span>
			<?php include $controller->getConstant('pjBase', 'PLUGIN_VIEWS_PATH') . 'pjLayouts/elements/button-animation.php'; ?>
		</button>
	</div><!-- /.clearfix -->
</form>