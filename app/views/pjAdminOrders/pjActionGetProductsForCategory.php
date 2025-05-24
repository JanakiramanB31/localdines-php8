   
    <input type='hidden' id='current_page' />
    <input type='hidden' id='show_per_page' />
    <input type='hidden' id='nop' />
    
<table id="paginate" width="100%" style="border-collapse: separate;border-spacing: 15px 15px;">

<thead>
<tr>
    <th>S.No.</th>
    <th>Product Name</th>
    <th>Extras</th>
    <th>Price</th>
</tr>
</thead>
<tbody>
<?php if ($tpl['product_arr']) {
    $count = 1;
    foreach ($tpl['product_arr'] as $p){ 
        
            
        if ($p['status'] == 1 ) {
?>
<tr>
    <td>
    <?php echo $count++; ?>
    </td>
    <td><?php echo $p['name']; ?></td>
    <td>
    <?php 
    foreach ($tpl['extras'] as $k) {
    if ($p['id'] == $k['product_id']) {
        ?>
       <span><?php echo $k['name'].' - '.pjCurrency::formatPrice($k['price']); ?><span><br>
        <?php
    } }?>
    </td>
    <td>
    <?php if ($p['set_different_sizes'] == 'T') {
        foreach ($tpl['price_arr'] as $price) {
            if ($price['product_id'] == $p['id']) {
                ?>
                <span><?php echo $price['price_name'].'-'.pjCurrency::formatPrice($price['price']); ?></span><br>
                <?php
            }
        }
        
    } else { ?>
    
        <span><?php echo pjCurrency::formatPrice($p['price']); ?></span>
     <?php }  ?>
     </td>
     
</tr>
<?php } } } else { ?>
    <tr><td colspan="4">Currently no products are available for the category</td></tr>
<?php } ?>
</tbody>
</table>
