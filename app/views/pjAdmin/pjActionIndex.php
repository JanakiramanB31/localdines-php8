<?php if ( $_SESSION['admin_user']['role_id'] == ADMIN_R0LE_ID) { ?>
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-4 col-sm-6">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <span class="label label-primary pull-right"><?php __('dash_today');?></span>

                    <h5><?php __('dash_delivery');?></h5>
                </div>

                <div class="ibox-content">
                    <div class="row m-t-md m-b-sm">
                        <div class="col-xs-12">
                            <p class="h1 no-margins"><?php echo $tpl['cnt_delivery_orders'];?> / <?php echo pjCurrency::formatPrice($tpl['amount_delivery_orders'])?></p>
                            <small class="text-info"><?php echo $tpl['cnt_delivery_orders'] != 1 ? __('lblDeliveryOrdersToday', true, false) : __('lblDeliveryOrderToday', true, false); ?></small>        
                        </div><!-- /.col-xs-6 -->

                    </div><!-- /.row -->
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-sm-6">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <span class="label label-primary pull-right"><?php __('dash_today');?></span>

                    <h5><?php __('dash_pickup');?></h5>
                </div>

                <div class="ibox-content">
                    <div class="row m-t-md m-b-sm">
                        <div class="col-xs-12">
                            <p class="h1 no-margins"><?php echo $tpl['cnt_pickup_orders'];?> / <?php echo pjCurrency::formatPrice($tpl['amount_pickup_orders'])?></p>
                            <small class="text-info"><?php echo $tpl['cnt_pickup_orders'] != 1 ? __('lblPickupOrdersToday', true, false) : __('lblPickupOrderToday', true, false); ?></small>        
                        </div><!-- /.col-xs-6 -->
                    </div><!-- /.row -->
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5><?php __('dash_total');?></h5>
                </div>

                <div class="ibox-content">
                    <div class="row m-t-md m-b-sm">
                        <div class="col-xs-12">
                            <p class="h1 no-margins"><?php echo $tpl['cnt_orders'];?> / <?php echo pjCurrency::formatPrice($tpl['amount_orders'])?></p>
                            <small class="text-info"><?php echo $tpl['cnt_orders'] != 1 ? __('lblTotalOrders', true, false) : __('lblOneOrder', true, false); ?></small>        
                        </div><!-- /.col-xs-6 -->
                    </div><!-- /.row -->
                </div>
            </div>
        </div>
    </div><!-- /.row -->
<?php } ?>
	<?php
	$order_statuses = __('order_statuses', true, false);
	?>
    <div class="row">
        <div class="col-lg-4">
            <div class="ibox float-e-margins">
                <div class="ibox-content ibox-heading clearfix">
                    <div class="pull-left">
                        <h3><?php __('lblLatestDeliveryOrders');?></h3>
                    </div><!-- /.pull-left -->

                    <div class="pull-right m-t-md">
                    	<a class="btn btn-primary btn-sm btn-outline m-n" href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminPosOrders&amp;action=pjActionIndex&amp;type=delivery"><?php __('lblViewAll');?></a>
                    </div><!-- /.pull-right -->
                </div>
				
                <div class="ibox-content inspinia-timeline">
                	<?php
                	if(!empty($tpl['latest_delivery']))
                	{
                	    foreach($tpl['latest_delivery'] as $v)
                	    {
                	        if($v['d_asap'] == 'F')
                	        {
                	            $date_time = date($tpl['option_arr']['o_date_format'], strtotime($v['d_dt'])) . ',<br/>' . date($tpl['option_arr']['o_time_format'], strtotime($v['d_dt']));
                	        }else{
                	            $date_time = date($tpl['option_arr']['o_date_format'], strtotime($v['d_dt'])) . ',<br/>' . __('lblAsap', true);
                	        }
                	        ?>
                	        <div class="timeline-item">
                                <div class="row">
                                    <div class="col-xs-3 date">
                                        <i class="fa fa-clock-o"></i>
                                        <?php echo $date_time;?>
                                    </div>
        
                                    <div class="col-xs-7 content">
                                    	<p class="m-b-xs"><strong><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminClients&amp;action=pjActionUpdate&amp;id=<?php echo $v['client_id']?>"><?php echo pjSanitize::html($v['client_name']);?></a></strong></p>
                                        <p class="m-n"><?php __('lblOrderID')?>: <em><a href="#"><?php echo pjSanitize::html($v['order_id']);?></a></em></p>
                                        <p class="m-n"><?php __('lblLocation')?>: <em><?php echo pjSanitize::html($v['location']);?></em></p>
                                        <p class="m-n"><?php __('lblTotal')?>: <em><?php echo pjCurrency::formatPrice($v['total']);?></em></p>
                                    </div>
                                    
                                    <div class="badge bg-<?php echo $v['status'];?> b-r-sm pull-right m-t-md m-r-sm"><?php echo $order_statuses[$v['status']];?></div>
                                </div>
                            </div>
                	        <?php
                	    }
                	}else{
                	    ?>
                	    <div class="row">
                            <div class="col-xs-12">
                                <p class="m-b-xs"><?php __('lblDashNoOrder');?></p>
                            </div>
                        </div>
                	    <?php
                	}
                	?>
                </div>
            </div>
        </div><!-- /.col-lg-4 -->

        <div class="col-lg-4">
            <div class="ibox float-e-margins">
                <div class="ibox-content ibox-heading clearfix">
                    <div class="pull-left">
                        <h3><?php __('lblLatestPickupOrders');?></h3>
                    </div><!-- /.pull-left -->

                    <div class="pull-right m-t-md">
                    	<a class="btn btn-primary btn-sm btn-outline m-n" href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminPosOrders&amp;action=pjActionIndex&amp;type=pickup"><?php __('lblViewAll');?></a>
                    </div><!-- /.pull-right -->
                </div>

                <div class="ibox-content inspinia-timeline">
                    <?php
                	if(!empty($tpl['latest_pickup']))
                	{
                	    foreach($tpl['latest_pickup'] as $v)
                	    {
                	        if($v['d_asap'] == 'F')
                	        {
                	            $date_time = date($tpl['option_arr']['o_date_format'], strtotime($v['p_dt'])) . ',<br/>' . date($tpl['option_arr']['o_time_format'], strtotime($v['p_dt']));
                	        }else{
                	            $date_time = date($tpl['option_arr']['o_date_format'], strtotime($v['p_dt'])) . ',<br/>' . __('lblAsap', true);
                	        }
                	        ?>
                	        <div class="timeline-item">
                                <div class="row">
                                    <div class="col-xs-3 date">
                                        <i class="fa fa-clock-o"></i>
                                        <?php echo $date_time;?>
                                    </div>
        
                                    <div class="col-xs-7 content">
                                    	<p class="m-b-xs"><strong><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminClients&amp;action=pjActionUpdate&amp;id=<?php echo $v['client_id']?>"><?php echo pjSanitize::html($v['client_name']);?></a></strong></p>
                                        <p class="m-n"><?php __('lblOrderID')?>: <em><a href="#"><?php echo pjSanitize::html($v['order_id']);?></a></em></p>
                                        <p class="m-n"><?php __('lblLocation')?>: <em><?php echo pjSanitize::html($v['location']);?></em></p>
                                        <p class="m-n"><?php __('lblTotal')?>: <em><?php echo pjCurrency::formatPrice($v['total']);?></em></p>
                                    </div>
                                    
                                    <div class="badge bg-<?php echo $v['status'];?> b-r-sm pull-right m-t-md m-r-sm"><?php echo $order_statuses[$v['status']];?></div>
                                </div>
                            </div>
                	        <?php
                	    }
                	}else{
                	    ?>
                	    <div class="row">
                            <div class="col-xs-12">
                                <p class="m-b-xs"><?php __('lblDashNoOrder');?></p>
                            </div>
                        </div>
                	    <?php
                	}
                	?>
                </div>
            </div>
        </div><!-- /.col-lg-8 -->

        <div class="col-lg-4">
            <div class="ibox float-e-margins">
                <div class="ibox-content ibox-heading clearfix">
                    <div class="pull-left">
                        <h3><?php count($tpl['location_arr']) == 1 ? __('lblLocationWorkingStatus') : __('lblLocationsWorkingStatus') ;?></h3>
                        
                    </div><!-- /.pull-left -->
                </div>

                <div class="ibox-content inspinia-timeline">
                    <div class="timeline-item">
                    	<?php
                    	if(!empty($tpl['location_arr']))
                    	{
                    	    foreach($tpl['location_arr'] as $v)
                    	    {
                    	        ?>
                    	        <div class="row">
                                    <div class="col-xs-12">
                                        <p class="m-n"><strong><?php echo pjSanitize::clean($v['location_title']);?></strong></p>
                                        <p class="m-n"><?php __('lblDelivery')?>: <strong><?php echo $v['delivery']; ?></strong></p>
                                        <p class="m-n"><?php __('lblPickup')?>: <strong><?php echo $v['pickup']; ?></strong></p>
                                    </div>
                                </div>
                                <div class="hr-line-dashed"></div>
                    	        <?php
                    	    }
                    	}else{
                    	    $text = __('lblDashNoLocation', true);
                    	    $text = str_replace("[STAG]", '<a href="'.$_SERVER['PHP_SELF'].'?controller=pjAdminLocations&amp;action=pjActionCreate"><strong>', $text);
                    	    $text = str_replace("[ETAG]", '</strong></a>', $text);
                        	?>
                            <div class="row">
                                <div class="col-xs-12">
                                    <p class="m-b-xs"><?php echo $text;?></p>
                                </div>
                            </div>
                            <?php
                    	}
                        ?>
                    </div>
                 </div><!-- /.ibox-content inspinia-timeline -->
            </div>
        </div><!-- /.col-lg-8 -->
    </div><!-- /.row -->
</div><!-- /.wrapper wrapper-content -->