<!doctype html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Cache-Control" content="public, max-age=3600" /> <!-- Cache for 1 hour -->
    <title><?php __('script_name') ?></title>
      <?php
      $cnt = count($controller->getCss());
      foreach ($controller->getCss() as $i => $css)
      {
        // $version =  mt_rand(0, 999999);
        // echo '<link rel="stylesheet" href="'.(isset($css['remote']) && $css['remote'] ? NULL : PJ_INSTALL_URL).$css['path'].$css['file'].'?version='.$version.'">';
        echo '<link rel="stylesheet" href="'.(isset($css['remote']) && $css['remote'] ? NULL : PJ_INSTALL_URL).$css['path'].$css['file'].'">';
        echo "\n";
        if ($i < $cnt - 1)
        {
          echo "\t";
        }
      }
      ?>
  </head>
  <body>

    <div id="wrapper">
      <?php //require dirname(__FILE__) . '/elements/menu-left.php'; ?>
      <div id="page-wrapper" class="gray-bg dashbard-1" style="padding-bottom:0px;">
        <div id="cover-spin">
          <div id="loader_text">Please wait Processing...</div>
        </div>
          <?php
          // require dirname(__FILE__) . '/elements/menu-top.php'; 
          
          require $content_tpl;
          
          // include dirname(__FILE__) . '/elements/footer-default.php'; 
          ?>
        </div>
      <input type='hidden' value='<?php echo APP_URL ?>' id="app_url" />
    </div><!-- #wrapper -->
    <!-- Button trigger modal -->
    <!-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalCenter">
    Launch demo modal
    </button> -->

    <!-- Modal -->
    <div class="modal fade" id="newOrderNotif" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
        <!-- <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle">Modal title</h5> -->
          
        <!-- </div> -->
        <div class="modal-body">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
          </button>
          <h3>New Order!!!</h3>
          <button class="btn" id="o_type" styel="margin-bottom: 20px;"></button>
          <p id="c_name"></p>
          <p id="c_address" style="font-size: 15px"></p>
          <p id="order_id" style="font-size: 15px"></p>
          
          <h3 id="c_phone"></h3>
        </div>
          <div class="modal-footer">
            <input type="hidden" id="o_id">
            <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal" id="orderCancel-btn">Cancel</button> -->
            <button type="button" class="btn btn-primary" id="orderViewed-btn" data-dismiss="modal">OK</button>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="jsClientMessageModal" tabindex="-1" role="dialog" aria-labelledby="ClientMessage" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="jsClientTitle"></h5>
          </div>
          <div class="modal-body" id='jsClientMessage'>
             
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-primary" id="" data-dismiss="modal">OK</button>
          </div>
        </div>
      </div>
    </div>
    <?php 
      $cnt = count($controller->getJs());
      foreach ($controller->getJs() as $i => $js) {
        //echo '<pre>';print_r($js); echo '</pre>';
        if ($js['remote']) {
          echo '<script src="'.(isset($js['remote']) && $js['remote'] ? NULL : PJ_INSTALL_URL).$js['path'].$js['file'].'"></script>';
        } else {
          // $version =  mt_rand(0, 999999); 
          // echo '<script src="'.(isset($js['remote']) && $js['remote'] ? NULL : PJ_INSTALL_URL).$js['path'].$js['file'].'?version='.$version.'"></script>';
          echo '<script src="'.(isset($js['remote']) && $js['remote'] ? NULL : PJ_INSTALL_URL).$js['path'].$js['file'].'"></script>';
        }
        echo "\n";
        if ($i < $cnt - 1) {
          echo "\t";
        }
      }
    ?>
  </body>
</html>