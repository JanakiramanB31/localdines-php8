<div class="row">
  <div class="col-sm-12 col-lg-12">

    <div class="form-group">
      <label class="control-label">Key</label>
      <?php
      $keyValue = array_key_exists('arr', $tpl) && isset($tpl['arr']['key']) ? $tpl['arr']['key'] : '';
      $isEditable = empty($keyValue); // Check if the key value is empty
      ?>
      <input type="text" name="key" id="key" value="<?php echo $keyValue; ?>" class="form-control required" data-msg-required="This field is required." <?php if (!$isEditable) echo 'readonly'; ?> />
    </div><!-- /.form-group -->

    <div class="form-group">
      <label class="control-label">Value</label>
      <input type="text" name="value" id="value" value="<?php echo array_key_exists('arr', $tpl) ? $tpl['arr']['value'] : '' ?>" class="form-control required" data-msg-required="This field is required." />
    </div><!-- /.form-group -->
    <div class="form-group">
      <label class="control-label">Type</label>
      <select class="form-control required" name="type">
        <option>Select</option>
        <?php foreach($tpl['dataTypes'] as $dataType) { ?>
          <option <?php echo (array_key_exists('arr', $tpl) && $tpl['arr']['type'] == $dataType) ? "selected": ""?> value="<?php echo $dataType; ?>"><?php echo ucfirst($dataType); ?></option>
        <?php } ?>
      </select>
    </div><!-- /.form-group -->
    <div class="form-group">
    <label class="control-label">Status</label>
    <?php
    $isActive = array_key_exists('arr', $tpl) && isset($tpl['arr']['is_active']) ? $tpl['arr']['is_active'] : '';
    ?>
    <label><input type="radio" name="is_active" value="1" <?php if ($isActive === '1') echo 'checked'; ?>> Yes</label>
    <label><input type="radio" name="is_active" value="0" <?php if ($isActive === '0') echo 'checked'; ?>> No</label>
</div><!-- /.form-group -->

  </div>
</div>
<div class="row">
  <div class="col-sm-12 col-lg-12">
    <div class="hr-line-dashed"></div>
  </div>
</div>
<div class="row">
  <div class="col-sm-12 col-lg-12">
    <div class="clearfix">
      <button type="submit" class="ladda-button btn btn-primary btn-lg btn-phpjabbers-loader pull-left" data-style="zoom-in" style="margin-right: 15px;">
        <span class="ladda-label"><?php __('btnSave'); ?></span>
          <?php include $controller->getConstant('pjBase', 'PLUGIN_VIEWS_PATH') . 'pjLayouts/elements/button-animation.php'; ?>
      </button>
      <a class="btn btn-white btn-lg pull-right" href="<?php echo PJ_INSTALL_URL; ?>index.php?controller=pjAdminConfigs&action=pjActionIndex"><?php __('btnCancel'); ?></a>
    </div><!-- /.clearfix -->
  </div>
</div>
<div class="row">
  <div class="col-sm-12 col-lg-12">
    <div class="hr-line-dashed"></div>
  </div>
</div>