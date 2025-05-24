<div class="ticket">
	<?php //print_r($tpl['arr']); ?>
	<div>
		<h3 style="margin-bottom: 0px;">CHEF ID :</h3>
		<span><?php 
				date_default_timezone_set($tpl['timezone']);
				echo $tpl['arr']['chef_id']; ?>
		</span>
	</div>
	<div>
		<h3 style="margin-bottom: 0px;margin-top: 0px;">CHEF NAME :</h3>
		<span><?php 
				
				echo $tpl['arr']['chef_name']; ?>
		</span>
	</div>
	<div>
		<h3 style="margin-top: 0px;margin-bottom: 0px;">TIME :</h3>
		<span><?php echo date('d-m-Y H:i:s', time()); ?></span><br>
	</div>
	<div>
		<h3 style="margin-top: 0px;">ED :</h3>
		<span><?php $ed = $tpl['arr']['d_dt'] != '' ? $tpl['arr']['d_dt'] : $tpl['arr']['p_dt'];
		    
		    $ed_dt = explode(" ", $ed);
		    $ed_dt_items = explode('-', $ed_dt[0]);
		    $ed_dt[0] = $ed_dt_items[2].'-'.$ed_dt_items[1].'-'.$ed_dt_items[0];
		    echo $ed_dt[0].' '.$ed_dt[1];
		 ?></span><br>
	</div>
	
	<table class="table table-borderless">
		<thead>
			<th class="kitchen">KITCHEN</th>
		    <th class="nani">CATEGORY ID</th>
		</thead>
		<tbody>
			
			<?php   
                    
              //       print_r("<pre>");print_r($tpl["arr"]);
    		        // exit;

			       foreach ($tpl['categories'] as $cs => $c) {
			       	$i = 0;
                     
					foreach ($tpl['product_arr'] as $product)

					{

					    foreach ($tpl['oi_arr'] as $k => $oi)
					    {
					        if ($oi['type'] == 'product' && $oi['foreign_id'] == $product['id'] && $c['id'] == $product['category_id'])
					        {
					            $has_extra = false;
					            $i = $i+1;

					            if ($i == 1) {
					            	# code...
					            //}
					            	// if ($c['id'] == $product['category_id']) {
					            		
					            	
            ?>
            <tr class="rowHead">
            	<input type="hidden" id="me" name="name" value="<?php echo $i; ?>">
            	<td><hr></td>
            	<td><?php echo $product['category_id'];?></td>
            </tr>
             <tr>
            	<input type="hidden" id="kprint_" name="" value="<?php echo $kprint; ?>">

                <td class="kitchen"><?php echo $oi['cnt']. " ";?><?php echo strtoupper($product['name']); ?><br><?php if($oi['special_instruction'] != '') { echo '#'.$oi['special_instruction']; }else{ echo '';} ?></td>
                <td class="nani"></td>
                
            </tr>

        <?php } else { ?>

            <tr>
            	<input type="hidden" id="kprint_" name="" value="<?php echo $kprint; ?>">

                <td class="kitchen"><?php echo $oi['cnt']. " ";?><?php echo $product['name']; ?><br><?php if($oi['special_instruction'] != '') { echo '#'.$oi['special_instruction']; }else{ echo '';} ?></td>
                <td class="nani"></td>
                
            </tr>
            <?php
               }
		    }
           } 
       }
   }
?>
		</tbody>
	</table>
	<h5>SPECIAL INSTRUCTION:</h5><span><?php echo $tpl['arr']['d_notes']; ?></span>
</div>
	
