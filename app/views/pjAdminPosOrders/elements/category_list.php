<?php if(SUB_CATEGORY) { ?>
<div id="js-main-categories" class="row wrapper wrapper-content animated">
  <?php foreach($tpl['category_list'][0] as $key=>$category)  { ?>
    <div class="col-12 jsSubCatContainer" id=<?php echo "category_id_".$key; ?> style="display: none;">
      <div id="slide-up">
        <?php if (array_key_exists($key, $tpl['category_list'])) { foreach($tpl['category_list'][$key] as $subKey=>$subCategory)  { 
          $subCategory = strlen($subCategory) > 8 ? substr($subCategory, 0, 7)."..." : $subCategory;
        ?>
          <div class="col-sm-2 col-lg-1 cus-category" data-id="<?php echo $subKey; ?>" data-category="<?php echo $subCategory; ?>">
            <div class="jsCategoryContainer category-container cus-pt-2 cus-pb-2 " data-id="<?php echo $subKey; ?>">
              <div class="content"><h4><?php echo $subCategory; ?></h4></div>
            </div>
          </div>
        <?php } } ?>
      </div>
    </div>
  <?php } ?>
</div>
<div id="js-categories" class="row wrapper wrapper-content animated">
  <?php $i = 1; foreach($tpl['category_list'][0] as $key=>$category)  { 
    //$category = strlen($category) > 8 ? substr($category, 0, 7)."..." : $category;
  ?>
  <div class="col-sm-2 col-lg-6 cus-category" data-id="<?php echo $key; ?>" data-category="<?php echo $category; ?>">
    <div class="jsMainCategoryContainer category-container cus-pt-2 cus-pb-2 main-category-container main-category-container-<?php echo $i; ?>" data-id="<?php echo $key; ?>">
      <div class="content"><h4><?php echo $category; ?></h4></div>
    </div>
  </div>
  <?php $i++; } ?>
</div>
<?php } else { ?>
  <div id="js-categories" class="row wrapper wrapper-content animated">
    <?php foreach($tpl['category_list'] as $key=>$category)  { 
      $category = strlen($category) > 8 ? substr($category, 0, 7)."..." : $category;
    ?>
    <div class="col-sm-2 col-lg-1 cus-category" data-id="<?php echo $key; ?>" data-category="<?php echo $category; ?>">
      <div class="jsCategoryContainer category-container cus-pt-2 cus-pb-2" data-id="<?php echo $key; ?>">
        <div class="content"><h4><?php echo $category; ?></h4></div>
      </div>
    </div>
    <?php } ?>
  </div>
<?php } ?>