
<div class="fdLoader"></div>
<?php
//echo $_SESSION['otp'];
$index = $controller->_get->toString('index');
$CLIENT = $controller->isFrontLogged() ? @$_SESSION[$controller->defaultClient] : array();
?>
<br />
<div class="container">
  <div class="row">
	  <div id="fdMain_<?php echo $index; ?>" class="col-md-8 col-sm-8 col-xs-12 pjFdPanelLeft mt-mob" style= "margin-left: auto;margin-right: auto;">
		  <div class="panel panel-default">
			<?php //include_once dirname(__FILE__) . '/elements/header.php';?>
			  <div class="panel-body  pjFdPanelBody">
          <h3 class="text-center">Please Enter OTP to continue!!</h3>
          <form id="fdOtpForm_<?php echo $index;?>" method="POST" data-group-name="digits" autocomplete="off" action="">
            <div class="digit-group">
              <input type="number" onkeypress="return event.charCode >= 48 && event.charCode <= 57" id="digit-1" name="digit-1" data-next="digit-2" size="1" maxlength="1"  inputmode="numeric" />
              <input type="number" onkeypress="return event.charCode >= 48 && event.charCode <= 57" id="digit-2" name="digit-2" data-next="digit-3" data-previous="digit-1" size="1" maxlength="1"  inputmode="numeric" />
              <input type="number" onkeypress="return event.charCode >= 48 && event.charCode <= 57" id="digit-3" name="digit-3" data-next="digit-4" data-previous="digit-2" size="1" maxlength="1"  inputmode="numeric" />
              <input type="number" onkeypress="return event.charCode >= 48 && event.charCode <= 57" id="digit-4" name="digit-4" data-next="digit-5" data-previous="digit-3" size="1" maxlength="1"  inputmode="numeric" />
              <input type="number" onkeypress="return event.charCode >= 48 && event.charCode <= 57" id="digit-5" name="digit-5" data-next="digit-6" data-previous="digit-4" size="1" maxlength="1"  inputmode="numeric"/>
              <input type="number" onkeypress="return event.charCode >= 48 && event.charCode <= 57" id="digit-6" name="digit-6" data-previous="digit-5" size="1" maxlength="1"  inputmode="numeric" />
            </div>
              <div style="margin-top: 20px;display: none;text-align: center" id="otpErr"><span class="text-danger">Please enter a valid OTP number</span></div>
              <div style="margin-top: 20px;display: none;text-align: center" id="otpWrong"><span class="text-danger">OTP is invalid</span></div>
              <?php   if(isset($_GET['msg_credit_err']) && $_GET['msg_credit_err'] == 1){ ?>
                  <div style="margin-top: 20px;"><span class="text-danger"><?php echo "Please contact our administrator!!";?></span></div>
              <?php }
              ?>
              <div id="otp-btns">
                <!--  <a href="review.php?resendOtp=1">Resend OTP?</a> -->
                <button role="button" name="otpResendBtn" class="btn-otp fdButtonOtpResend" style="margin-bottom: 10px;">Resend Otp?</button>
                <button role="submit" name="otpSubmitBtn" class="btn-otp fdButtonOtp" style="margin-bottom: 10px;float: right;" id="submitButton">Submit</button>
              </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<!--elements end-->
<!-- Preview Modal -->
<!-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#previewModal">
    Open modal
  </button> -->
<div class="modal" id="previewModal">
    <div class="modal-dialog">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">PREVIEW</h4>
          <button type="button" class="close close-modal" data-dismiss="modal">&times;</button>
        </div>
        <!-- Modal body -->
        <div class="modal-body">
        </div>
        <!-- Modal footer -->
        <!-- <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div> -->
      </div>
    </div>
  </div>
<!-- End of Preview Modal -->
<script type="text/javascript">
console.log(<?php echo $_SESSION['otp']; ?>)
// document.querySelectorAll('.digit-group input').forEach(item => {
//   item.addEventListener('keyup', event => {
//     var parent = document.querySelector('.digit-group');
//     if (event.keyCode === 8 || event.keyCode == 37) {
//       var prev = document.getElementById(item.getAttribute('data-previous'));
//       try {
//         prev.select();
//       } catch (e) {
//         return;
//       }
//     } else if (item.value >= 0) {
//       var next = document.getElementById(item.getAttribute('data-next'));
//       try {
//         next.select();
//       } catch (e) {
//         return;
//       }
//     }
//   })
// })
document.querySelectorAll('.digit-group input').forEach((input) => {
  // Allow only numbers (better than onkeypress)
  input.addEventListener('input', (e) => {
    e.target.value = e.target.value.replace(/[^0-9]/g, '');
    if (e.target.value.length >= 1) {
      const nextInput = document.getElementById(input.getAttribute('data-next'));
      if (nextInput) nextInput.focus();
    }
  });

  // Handle navigation (Backspace, ArrowLeft)
  input.addEventListener('keydown', (e) => {
    if (e.key === 'Backspace' && input.value.length === 0) {
      const prevInput = document.getElementById(input.getAttribute('data-previous'));
      if (prevInput) {
        e.preventDefault();
        prevInput.focus();
      }
    } else if (e.key === 'ArrowLeft') {
      const prevInput = document.getElementById(input.getAttribute('data-previous'));
      if (prevInput) {
        e.preventDefault();
        prevInput.focus();
      }
    } else if (e.key === 'ArrowRight') {
      const nextInput = document.getElementById(input.getAttribute('data-next'));
      if (nextInput) {
        e.preventDefault();
        nextInput.focus();
      }
    }
  });

  // Enhanced iOS paste handling
  input.addEventListener('paste', (e) => {
    e.preventDefault();
    const pasteData = e.clipboardData.getData('text').replace(/[^0-9]/g, '');
    const inputs = document.querySelectorAll('.digit-group input');
    let currentIndex = Array.from(inputs).indexOf(input);

    // Fill the inputs with pasted data
    for (let i = 0; i < pasteData.length; i++) {
      if (currentIndex + i < inputs.length) {
        inputs[currentIndex + i].value = pasteData[i];
      }
    }
    
    // Focus the last filled input
    const lastFilledIndex = Math.min(currentIndex + pasteData.length - 1, inputs.length - 1);
    setTimeout(() => {
      inputs[lastFilledIndex].focus();
    }, 50);
  });
});

// Get the correct form ID
const otpFormId = "fdOtpForm_<?php echo $index;?>";
const otpForm = document.getElementById(otpFormId);

// Form submission handling
otpForm.addEventListener('submit', (e) => {
  e.preventDefault();
  
  // Validate OTP (example validation)
  let otp = '';
  document.querySelectorAll('.digit-group input').forEach(input => {
    otp += input.value;
  });
  
  if (otp.length !== 6) {
    document.getElementById('otpErr').style.display = 'block';
    return;
  }
  
  // If validation passes, submit the form
  console.log('Submitting OTP:', otp);
  // Uncomment to actually submit:
  // otpForm.submit();
});

// Submit button handling
document.getElementById('submitButton').addEventListener('click', (e) => {
  otpForm.dispatchEvent(new Event('submit'));
});

// iOS touch event fixes
document.querySelectorAll('.digit-group input').forEach((input) => {
  input.addEventListener('touchend', (e) => {
    e.preventDefault();
    input.focus();
  }, { passive: false });
});

$(function() {
	if($(".food-item-desc").length > 0) {
		$(".search-me").css("display","block");
	} else {
		$(".search-me").css("display","none");
    if ($("#searchInput-group").css("display") == "flex") {
			$("#searchInput-group").css("display", "none");
      $(".logo").css("display", "block");
		}
	}
})
$(".close-modal").click(function() {
	$("#previewModal").modal("hide"); 
})
</script>


