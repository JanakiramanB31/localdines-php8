<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-sm-12">
        <div class="row">
            <div class="col-lg-9 col-md-8 col-sm-6">
                <h2><?php __('infoDeliveryPricesTitle');?></h2>
            </div>
            
        </div><!-- /.row -->

        <p class="m-b-none"><i class="fa fa-info-circle"></i><?php __('infoDeliveryPricesDesc');?></p>
    </div><!-- /.col-md-12 -->
</div>

<div class="row wrapper wrapper-content animated fadeInRight">
	<?php
	$error_code = $controller->_get->toString('err');
	if (!empty($error_code))
	{
	    $titles = __('error_titles', true);
	    $bodies = __('error_bodies', true);
	    switch (true)
	    {
	        case in_array($error_code, array('AL01', 'AL03', 'AL09')):
	            ?>
				<div class="alert alert-success">
					<i class="fa fa-check m-r-xs"></i>
					<strong><?php echo @$titles[$error_code]; ?></strong>
					<?php echo @$bodies[$error_code]?>
				</div>
				<?php
				break;
            case in_array($error_code, array('AL04', 'AL08')):
				?>
				<div class="alert alert-danger">
					<i class="fa fa-exclamation-triangle m-r-xs"></i>
					<strong><?php echo @$titles[$error_code]; ?></strong>
					<?php echo @$bodies[$error_code]?>
				</div>
				<?php
				break;
		}
	}
	?>
	<div class="tabs-container tabs-reservations m-b-lg">
		<ul class="nav nav-tabs" role="tablist">
            <li role="presentation"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminLocations&amp;action=pjActionUpdate&amp;id=<?php echo $controller->_get->toInt('id');?>"><?php __('menuDetails');?></a></li>
            <li role="presentation"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminTime&amp;action=pjActionIndex&amp;id=<?php echo $controller->_get->toInt('id');?>"><?php __('menuWorkingTime'); ?></a></li>
            <li role="presentation"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminTime&amp;action=pjActionIndex&amp;id=<?php echo $controller->_get->toInt('id');?>&tab=custom"><?php __('lblCustomerWorkingTime'); ?></a></li>
            <li role="presentation" class="active"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminLocations&amp;action=pjActionPrice&amp;id=<?php echo $controller->_get->toInt('id');?>"><?php __('menuDeliveryPrices'); ?></a></li>
        </ul>
       <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="details">
                <div class="panel-body">
                   <form action="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminLocations&amp;action=pjActionPrice" method="post" id="frmUpdatePrices">
            		   <input type="hidden" name="price_update" value="1" />
                	   <input type="hidden" name="location_id" value="<?php echo $controller->_get->toInt('id'); ?>" />
                       <div class="form-group">
                            <label class="control-label"><?php __('lblDoNotAddTax');?></label>
                            <div class="switch">
                                <div class="onoffswitch onoffswitch-data">
                                    <input type="checkbox" class="onoffswitch-checkbox" id="o_add_tax" name="o_add_tax"<?php echo $tpl['option_arr']['o_add_tax'] == 1 ? ' checked="checked"' : null;?>/>
                                    <label class="onoffswitch-label" for="o_add_tax">
                                        <span class="onoffswitch-inner" data-on="<?php __('_yesno_ARRAY_T', false, true);?>" data-off="<?php __('_yesno_ARRAY_F', false, true);?>"></span>
                                        <span class="onoffswitch-switch"></span>
                                    </label>
                                </div>
                            </div>
                                
                       	</div><!-- /.form-group -->
                        <div class="hr-line-dashed"></div>
                    	<div class="row">
                            <div class="col-lg-6">
                                <p>
                                    <a href="#" class="btn btn-primary btn-outline pj-add-price"><i class="fa fa-plus"></i> <?php __('btnAddDeliveryFee'); ?></a>
                                </p>
    
                                <div class="table-responsive table-responsive-secondary">
                                    <table id="tblPrices" class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th><?php __('lblPriceFrom', false, true); ?></th>
                                                <th><?php __('lblPriceTo', false, true); ?> </th>
                                                <th><?php __('lblPricePrice', false, true); ?></th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                                          
                                        <tbody>
                                        	<?php
                                        	if(!empty($tpl['arr']))
                                        	{
                                            	foreach ($tpl['arr'] as $price)
                                            	{
                                            	    ?>
                                            	  	<tr data-index="<?php echo $price['id'];?>">
                                                        <td>
                                                        	<div class="form-group">
                                                            	<div class="form-group">
                                                                    <div class="input-group">
                                                                        <span class="input-group-addon"><?php echo pjCurrency::getCurrencySign($tpl['option_arr']['o_currency'], false) ?></span> 
                    
                                                                        <input type="text" name="total_from[<?php echo $price['id'];?>]" class="form-control required number w80" value="<?php echo $price['total_from']; ?>" data-msg-required="<?php __('fd_field_required', false, true);?>" data-msg-number="<?php __('fd_field_number', false, true);?>"/>
                                                                    </div>
                                                                </div>
                                                             </div>
                                                        </td>
                                                        <td>
                                                        	<div class="form-group">
                                                            	<div class="form-group">
                                                                    <div class="input-group">
                                                                        <span class="input-group-addon"><?php echo pjCurrency::getCurrencySign($tpl['option_arr']['o_currency'], false) ?></span> 
                    
                                                                        <input type="text" name="total_to[<?php echo $price['id'];?>]" class="form-control required number w80" value="<?php echo $price['total_to']; ?>" data-msg-required="<?php __('fd_field_required', false, true);?>" data-msg-number="<?php __('fd_field_number', false, true);?>"/>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                        	<div class="form-group">
                                                            	<div class="form-group">
                                                                    <div class="input-group">
                                                                        <span class="input-group-addon"><?php echo pjCurrency::getCurrencySign($tpl['option_arr']['o_currency'], false) ?></span> 
                    
                                                                        <input type="text" name="price[<?php echo $price['id'];?>]" class="form-control required number w80" value="<?php echo $price['price']; ?>" data-msg-required="<?php __('fd_field_required', false, true);?>" data-msg-number="<?php __('fd_field_number', false, true);?>"/>
                                                                    </div>
                                                                </div>
                                                           	</div>
                                                        </td>
                                                        <td>
                                                            <div class="text-right">
                                                                <a href="#" class="btn btn-danger btn-outline btn-sm btn-delete pj-remove-price"><i class="fa fa-trash"></i></a>
                                                            </div>
                                                        </td>
                                                    </tr>  
                                            	    <?php
                                            	}
                                            	?>
                                            	<tr class="pjFdEmptyRow" style="display:none;">
                                        	    	<td colspan="4"><?php __('lblNoDeliveryFeesAdded');?></td>
                                        	    </tr>
                                            	<?php
                                        	}else{
                                        	    ?>
                                        	    <tr class="pjFdEmptyRow">
                                        	    	<td colspan="4"><?php __('lblNoDeliveryFeesAdded');?></td>
                                        	    </tr>
                                        	    <?php
                                        	}
                                        	?>
                                        </tbody>
                                    </table>
                                </div>
                            </div><!-- /.col-lg-6 -->
    
                            <div class="col-lg-6">
                                
                            </div><!-- /.col-lg-6 -->
                        </div><!-- /.row -->
    
                        <div class="hr-line-dashed"></div>
                        
    					<div class="clearfix">
                            <button type="submit" class="ladda-button btn btn-primary btn-lg btn-phpjabbers-loader pull-left" data-style="zoom-in" style="margin-right: 15px;">
                                <span class="ladda-label"><?php __('btnSave'); ?></span>
                                <?php include $controller->getConstant('pjBase', 'PLUGIN_VIEWS_PATH') . 'pjLayouts/elements/button-animation.php'; ?>
                            </button>
                        </div><!-- /.clearfix -->
                     </form>
                </div><!-- /.panel-body -->
            </div>
        </div><!-- /.col-lg-12 -->
    </div>
</div>

<table id="tblClonePrices" style="display: none">
    <tbody>
    	<tr data-index="{INDEX}">
            <td>
            	<div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon"><?php echo pjCurrency::getCurrencySign($tpl['option_arr']['o_currency'], false) ?></span> 
    
                        <input type="text" name="total_from[{INDEX}]" class="form-control required number w80" data-msg-required="<?php __('fd_field_required', false, true);?>" data-msg-number="<?php __('fd_field_number', false, true);?>"/>
                    </div>
                </div>
            </td>
            <td>
            	<div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon"><?php echo pjCurrency::getCurrencySign($tpl['option_arr']['o_currency'], false) ?></span> 
    
                        <input type="text" name="total_to[{INDEX}]" class="form-control required number w80" data-msg-required="<?php __('fd_field_required', false, true);?>" data-msg-number="<?php __('fd_field_number', false, true);?>"/>
                    </div>
                </div>
            </td>
            <td>
            	<div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon"><?php echo pjCurrency::getCurrencySign($tpl['option_arr']['o_currency'], false) ?></span> 
    
                        <input type="text" name="price[{INDEX}]" class="form-control required number w80" data-msg-required="<?php __('fd_field_required', false, true);?>" data-msg-number="<?php __('fd_field_number', false, true);?>"/>
                    </div>
                </div>
            </td>
            <td>
                <div class="text-right">
                    <a href="#" class="btn btn-danger btn-outline btn-sm btn-delete pj-remove-price"><i class="fa fa-trash"></i></a>
                </div>
            </td>
        </tr>  
    </tbody>
</table>
<style>
    #tblPrices .form-group{
        margin-bottom: 0px;
    }
</style>
<script type="text/javascript">
var myLabel = myLabel || {};
myLabel.alert_title = <?php x__encode('lblWarning'); ?>;
myLabel.alert_text = <?php x__encode('lblDeliveryFeeWarning'); ?>;
myLabel.btn_ok = <?php x__encode('btnOK'); ?>;
</script>