<?php 
// echo "<pre>";
// print_r($tpl["selected_ins_arr"]); 
// echo "</pre>";
// echo count($tpl["selected_ins_arr"]);
// echo "<pre>";
// print_r($tpl["selected_ins_arr"][0]); 
// echo "</pre>";
?>
<table class="table table-bordered">
  <thead>
    <tr>
      <th scope="col">S.No</th>
      <th scope="col">Instructions</th>
      <th scope="col">Custom Instruction</th>
    </tr>
  </thead>
  <tbody>
  	<?php for($i = 0, $counter = 1; $i < count($tpl["selected_ins_arr"]); $i++) {  ?>
      <?php if($tpl["selected_ins_arr"][$i]['ids'] != "" ||  $tpl["selected_ins_arr"][$i]['cus_ins'] != "") { ?>

  		<tr>
	      <td scope="row"><?php echo $counter ?></td>
	      <td>
	      	<?php if ( $tpl["selected_ins_arr"][$i]['imgs'] != "") {
					// $img_src = explode(',', $tpl["selected_ins_arr"][$i]['imgs']);
					foreach(explode(',', $tpl["selected_ins_arr"][$i]['imgs']) as $imgs) { 
						if ($imgs != "") {
							?><img src="<?php echo $imgs ?>" class="td-img-class" alt=""><?php
						}
					}
				} ?>
	      </td>
	      <td><?php echo $tpl["selected_ins_arr"][$i]['cus_ins'] ?></td>
	    </tr>
      <?php $counter++; ?>
      <?php } ?>
  	<?php } ?>
  </tbody>
</table>