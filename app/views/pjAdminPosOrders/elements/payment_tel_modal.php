<!-- Start of Payment Modal -->
<div class="modal" tabindex="-1" id="paymentTelModal_old" role="dialog">
  <div class="modal-dialog modal-lg" role="document" style="width: 95%;">
    <div class="modal-content">
      <div class="modal-header text-center">
        <h2 class="modal-title" style="display: inline-block;">Payment</h2>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body text-center">
        <div class="row">
          <div class="col-sm-4 text-left">
            <p><b>Total Paying:</b></p>
          </div>
          <div class="col-sm-8 text-right">
            <div class="money-container">
              <a href="javascript:;" data-rs = "13" class="btn">$13</a>
              <a href="javascript:;" data-rs = "10" class="btn">$10</a>
              <a href="javascript:;" data-rs = "20" class="btn">$20</a>
              <a href="javascript:;" data-rs = "50" class="btn">$50</a>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-sm-6 text-left">
            <p><b>Total Payable:</b></p>
          </div>
          <div class="col-sm-6 text-right">
            <div class="input-group" >
              <span id="payment_modal_curr"><?php echo pjCurrency::getCurrencySign($tpl['option_arr']['o_currency'], false) ?> </span>
              <span id="payment_modal_tot" style="padding-right:20px;"></span>
              <input type="text" id="payment_modal_pay" class="form-control pull-right input" style="width:150px" value="0.00">
              <span class="input-group-addon"><?php echo pjCurrency::getCurrencySign($tpl['option_arr']['o_currency'], false) ?></span> 
            </div> 
            <div class="keyboard-container d-none">
              <button type="button" class="close-keyboard" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
              <div class="num-keyboard-tel"></div>
            </div>
          </div>
        </div>

        <div class="row text-right">
            <div class="col-sm-12">
              <p><b>Balance: </b> <span id="payment_modal_bal"></span></p>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 text-center">
                <p id="error-msg" class="text-danger d-none">Please enter a valid amount</p>
            </div>
        </div>
       
        <div class="row">
          <p>
            <div class="col-sm-2 text-left">
              <p><b>Type:</b></p>
            </div>
            <div class="col-sm-10 text-right">
              <div>
                <span class="confirm_payment_method" id="confirm_payment_method">
                  <button id = "paymentTypeCash" class="btn payment-method-btn"><?php echo "Cash"; ?></button>
                  <button id = "paymentTypeCard" class="btn payment-method-btn"><?php echo "Card"; ?></button>
                </span>
                <span style="padding-left:100px">
                  <button type="button" id="paymentBtn" data-valid = "false" data-phone="" class="btn btn-primary">Pay</button>
                  <button type="button" class="btn btn-secondary" id="btn-clear">Clear</button>
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </span>
              </div>
              <div id="confirm-table-error-msg" class="error-msg"></div>
            </div>
          </p>
        </div>
      </div>
      <div class="modal-footer">
      </div>
    </div>
  </div>
</div>
<!-- End of Payment Modal -->