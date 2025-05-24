<?php 
	$ed = $tpl['arr']['d_dt'] != '' ? $tpl['arr']['d_dt'] : $tpl['arr']['p_dt'];
	$ed_dt = explode(" ", $ed);
	$ed_dt_items = explode('-', $ed_dt[0]);
	$ed_dt[0] = $ed_dt_items[2] . '-' . $ed_dt_items[1] . '-' . $ed_dt_items[0];
	$paperWidth = "290px";
?>
<div id="kPrintContainer">
<div class="ticket" style="margin: 5px 10px;width: <?php echo $paperWidth;?>;">
	<table class="table table-borderless header" style="margin: auto; text-align: center; <?php echo $paperWidth;?>;">
		<!-- <tr><th class="top">CHEF NAME :&nbsp;</th><td><?php //echo $tpl['arr']['chef_name']; ?></td></tr> -->
		<?php if (strtolower($tpl['arr']['origin']) != 'pos') { ?>
		<tr><th class="top">&nbsp;</th><td><strong><?php echo substr($tpl['arr']['origin'], 0, 3); echo " - " .ucfirst($tpl['arr']['type']);?></strong></td></tr>
	<?php } ?>
		<tr><th class="top">TIME : </th><td><?php echo date('d-m-Y H:i:s', time()); ?></td></tr>
		<tr><th class="top">ED : </th><td><?php echo $ed_dt[0] . ' ' . $ed_dt[1]; ?></td></tr>
		<tr><th class="top">OrderID : </th><td><?php echo $tpl['arr']['order_id']; ?></td></tr>
	</table>
	<br/>
	<table class="table table-borderless" style="margin: auto; text-align: center; width: <?php echo $paperWidth;?>;">
		<thead>
			<th class="kitchen" style="text-align: center;"><strong><?php echo $tpl['arr']['table_ordered_name']?$tpl['arr']['table_ordered_name']:$tpl['arr']['table_name']; ?></strong></th>
		</thead>
		<tbody>
			<?php
			  $i = 0;
			  //echo '<pre>'; print_r($tpl['product_arr']); echo '</pre>';
			  // echo '<pre>'; print_r($tpl['oi_arr']); echo '</pre>';
			  // echo '<pre>'; print_r($tpl['oi_extras']); echo '</pre>';
			  // echo "<pre>"; print_r($tpl['special_instructions']); echo "</pre>";
			  //foreach ($tpl['product_arr'] as $product) {
			    foreach ($tpl['oi_arr'] as $k => $oi) {
			    	// if (!$oi['is_kitchen']) {
			    	// 	continue;
			    	// }
			    	$strikeThroughStart = '';
			  		$strikeThroughEnd = '';
			        $i = $i + 1;
			        if ($i == 1) {
			?>
				        <tr class="rowHead">
				      		<td><hr/></td>
					      </tr>
					      <?php }
					      	if (in_array($oi['status'], RETURN_TYPES))  { 
					      		$strikeThroughStart = '<s>';
					      		$strikeThroughEnd = '</s>';
				         	}
				         ?>
			
				       	<tr>
				          <td class="kitchen" style="font-size: 18pt;">
				          	<?php
				          	// echo '<pre>'; print_r($oi); echo '</pre>';
				          	echo $strikeThroughStart;
				          	if ($oi['special_instruction'] || array_key_exists($oi['hash'], $tpl['oi_extras'])) {
					          	for ($i = 0, $counter = 0; $i < $oi['cnt'] ; $i++) {
					          		if ($oi['type'] == 'custom') {
					          			echo 1 . " x ";
					          			echo strtoupper($oi['custom_name']);
					          		} else {
					          			echo 1 . " x ";
						          		echo strtoupper($oi['product_name'])." ".$oi['size']." ";
					          		}
					          		if (array_key_exists($oi['hash'], $tpl['oi_extras']) && isset($tpl['oi_extras'][$oi['hash']][$counter])) { 
					          			$extras_counter = 0;
					          			$extras_count = count($tpl['oi_extras'][$oi['hash']]);
									        while($extras_counter < $extras_count) {
									          $extra = $tpl['oi_extras'][$oi['hash']][$extras_counter]; //echo 'came here';
									          echo '<br/><span style="margin-left: 20px">'.$extra->extra_name ." x ".$extra->extra_count.'</span>';
									          $extras_counter++;
									        }
					          		}
					          		if ($oi['special_instruction']) {
					          			$obj = json_decode($oi['special_instruction'], true);
					          			if (isset($obj[$counter])) {
					          				if ($obj[$counter]['ids']) {
					          					echo "<br/><span style='margin-left: 10px'>";
					          					$selected_ins_arr = explode(',', $obj[$counter]['ids']);
							          			foreach ($selected_ins_arr as $ins) {
							          				foreach ($tpl['special_instructions'] as $instruction) {
							          					if ($ins == $instruction['id']) {
							          						echo "<img src='".$instruction['image']."' style='margin-left: 5px;height: 30px; width:30px;'/>";
							          					}
							          				}
							          			}
						          				echo "</span>";
					          				}
					          				
						          			if ($obj[$counter]['cus_ins']) {
							          			echo "<br/><span style='margin-left: 20px'># " . $obj[$counter]['cus_ins']. '</span>';
						          			}
					          			}
					          			//echo "<br>";
					          			echo "<br/>";
					          		}
					          		//echo $counter;
					          		//echo $oi['hash'];
					          	}
				          	} else {
				          		if ($oi['type'] == 'custom') {
				          			echo $oi['cnt'] . " x ";
				          			echo strtoupper($oi['custom_name']);
				          		} else {
				          			echo $oi['cnt'] . " x ";
				          			echo strtoupper($oi['product_name'])." ".$oi['size']." ";
				          		}
				          		echo "<br>";
				          	}
				          	echo $strikeThroughEnd;
				          	?>
				          </td>
				        </tr>
        <?php
      					} 
				?>
			 <tr class="rowHead">
	    	<td><hr></td>
	    </tr>
		</tbody>
	</table>
	<?php
	if ($tpl['arr']['d_notes'] != '' || $tpl['arr']['p_notes'] != '') { ?>
	<h5>Spl. Instruction:</h5><span><?php echo $tpl['arr']['p_notes']? $tpl['arr']['p_notes']: $tpl['arr']['d_notes']; ?></span>
	<?php } ?>
</div>
</div>
<div class="hidden-print" style="margin: 5px 10px;width: <?php echo $paperWidth;?>;">
 <a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminPosOrders&amp;action=<?php echo $tpl['action']; ?>&amp;origin=<?php echo ucfirst($tpl['arr']['origin']);?>" class="btn btn-primary nextbutton"><i class="fa fa-plus"></i> <?php echo "Close" ?></a>
</div>
<script type="text/javascript">
	$(document).ready(function() {
	// your code
		function updateKitchenPrint() {
	    $.ajax({
	      type: "POST",
	      async: false,
	      url: "index.php?controller=pjAdminPosOrders&action=pjActionKitchenPrintUpdate",
	      data: {
	        order_id: <?php echo $tpl['order_id']; ?>
	      },
	      success: function (data) {
	      },
	      // !MEGAMIND
	    });
  	}
  	updateKitchenPrint();
  	printDiv('kPrintContainer');
	});
</script>