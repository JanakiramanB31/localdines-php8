var rbApp = rbApp || {};
var jQuery_1_8_2 = jQuery_1_8_2 || jQuery.noConflict();
(function ($, undefined) {
  $(function () {
    "use strict";
    var app_url = $("#app_url").val();

    var audio = new Audio(app_url + 'app/web/js/notify.mp3');
    console.log(audio);
    $(window).on('load', function () {
      //getClientMessage();
      //$(document).off("ajaxStart.dg");
      // $(document).bind("ajaxStart.dg", function(e) {
      // 	$target.closest(wrapper).addClass("sk-loading");
      // }).bind("ajaxStop.dg", function(e) {
      // 	$target.closest(wrapper).removeClass("sk-loading");
      // });
      $.post(
        "index.php?controller=pjFrontPublic&action=pjActionCheckNewOrder",
      ).done(function (data) {
        if (data.status == 'true') {
          $("#notify_count").text(data.orders);
        }
      });

      $.post(
        "index.php?controller=pjFrontPublic&action=pjActionGetCreditBalance",

      ).done(function (data) {
        if (data.status == 'OK' && data.text != null) {
          $("#credits_count").text(data.text.sms);
          $("#credits_count").attr("data-api-set", "true")
        } else {
          $("#credits_count").attr("data-api-set", "false");
        }
      });
      //getClientMessage();
    })

    let isBlinking = false;
    let blinkInterval;

    function toggleBlink() {
      if (isBlinking) {
        $('#blinkButton').css({
          'background-color': 'transparent',
          'color': 'black'
        });
        isBlinking = false;
        clearInterval(blinkInterval);
      } else {
        $('#blinkButton').css({
          'background-color': 'red',
          'color': 'white'
        });
        isBlinking = true;
        blinkInterval = setInterval(function () {
          $('#blinkButton').toggleClass('blinking');
        }, 500);
      }
    }

    function startBlinking() {
      // Your AJAX request and logic here
      $.ajax({
        type: "POST",
        async: false,
        global: false,
        url: "index.php?controller=pjFrontPublic&action=pjActionCheckNewOrder",
        success: function (data) {
          if (data.status == 'true') {
            $("#notify_count").text(data.orders);
            GetNewOrder();
            $('#blinkButton').show();
            toggleBlink();
            function blink_text() {
              $('.blink').fadeOut(500);
              $('.blink').fadeIn(500);
              $('#blink').text('WEB ORDER - ' + data.orders);
              $('#blink').css({
                'color': '#ed5565',
              })
            }
            $('#blink').show();
            blink_text();
            setInterval(blink_text, 5000);
          }
        },
      });
    }

    startBlinking();

    setInterval(function () {
      startBlinking();
    }, 20000);


    setInterval(getClientMessage(), 10000);
    $('#showTerms').on("click", function () {
      $.ajax({
        type: "POST",
        async: false,
        url: "index.php?controller=pjFrontPublic&action=pjActionGetAdminTerms",
        data: {
        },
        success: function (data) {
          console.log(data);
          $("#jsTerms").html(data);
          $("#TermsModal").modal();
        },
        // !MEGAMIND
      });

      return false;
    })
    function GetNewOrder() {
      $.post(
        "index.php?controller=pjFrontPublic&action=pjActionGetNewOrder",
      ).done(function (data) {
        if (data.status == 'true') {
          //console.log($.parseJSON(data.order));
          // var no_of_orders = data.orders.length;
          // var orders = data.orders;
          showNotification(data.order);
        }
      });
    }
    function showNotification(newOrder) {
      // console.log(newOrder);
      audio.loop = true;
      audio.play();
      var notes = '';
      $("#o_type").text(newOrder[0].type);
      if (newOrder[0].type == "delivery") {
        var icon = "  <i class='fa fa-truck' aria-hidden='true'></i>"
        $("#o_type").addClass("btn-success");
        $("#o_type").append(icon);
        var address = getAddress(newOrder);
        $("#c_address").text(address);
        notes = newOrder[0].d_notes;
      } else {
        var icon = "  <i class='fa fa-suitcase' aria-hidden='true'></i>"
        $("#o_type").addClass("btn-danger");
        $("#o_type").append(icon);
        notes = newOrder[0].p_notes;
      }
      $("#c_name").text(newOrder[0].surname);
      $("#c_phone").text(newOrder[0].phone_no)
      $("#o_id").val(newOrder[0].id);
      $("#order_id").html("Web - " + newOrder[0].type + "<div>" + newOrder[0].order_id) + "</div>";

      // Clear previous items
      $("#order_items").empty();

      // Add items to the modal
      if (newOrder[0].items && newOrder[0].items.length > 0) {
        var itemsHtml = '<div class="order-items-list" style="margin-top: 20px; border-top: 1px solid #eee; padding-top: 10px;">';
        itemsHtml += '<h5>Order Items:</h5>';
        itemsHtml += '<ul style="list-style: none; padding-left: 0;">';

        newOrder[0].items.forEach(function (item) {
          itemsHtml += '<li>';
          itemsHtml += '<div style="display: flex; ">';
          itemsHtml += '<span> ' + item.cnt + '</span> <span style="padding: 0px 5px;"> X </span>';
          itemsHtml += '<span style="font-weight: bold;">' + item.product_name + '</span>';

          itemsHtml += '</div>';
          // itemsHtml += '<div style="text-align: right; margin-top: 5px;">';
          // itemsHtml += 'Price: £' + item.price;
          // itemsHtml += '</div>';
          itemsHtml += '</li>';
        });

        itemsHtml += '</ul>';
        itemsHtml += '</div>';

        $("#order_items").html(itemsHtml);
        if (typeof notes === 'string' && notes.trim() !== '' && notes.trim() !== 'null') {
          var cleanedNotes = notes.trim();
          var notesHtml = '<div class="order-notes-section" style="margin: 15px 0px;">';
          notesHtml += '<h5 style="margin-bottom: 8px; color: #555;">Order Notes:</h5>';
          notesHtml += '<div style="background: #f8f9fa; border: 1px solid #dee2e6; padding: 10px; border-radius: 4px;">';
          notesHtml += cleanedNotes;
          notesHtml += '</div>';
          notesHtml += '</div>';

          $("#order_notes").html(notesHtml);
        }
      }
      $("#newOrderNotif").modal("show");
    }

    function getAddress(newOrder) {
      const addressParts = [];

      // Define the fields to check in order
      const fields = ['d_address_1', 'd_address_2', 'd_city', 'post_code'];

      fields.forEach(field => {
        if (newOrder[0][field] && newOrder[0][field].toString().trim() !== 'null') {
          addressParts.push(newOrder[0][field].toString().trim());
        }
      });

      return addressParts.join(', ');
    }

    function getClientMessage() {
      $.post(
        "index.php?controller=pjFrontPublic&action=pjActionGetClientMessage",
      ).done(function (data) {
        if (data.status == 'true') {
          showClientMessage(data.message);
        }
      });
    }
    function showClientMessage(message) {
      console.log(message);
      $("#jsClientTitle").html(message[0].title);
      $("#jsClientMessage").html(message[0].message);
      $("#jsClientMessageModal").modal("show");
    }

    $('#logoutLink').on('click', function (event) {
      event.preventDefault();
      swal({
        title: 'Are you sure?',
        text: "You will be logged out.",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#C1C1C1',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Log out'
      }, function (result) {
        if (result) {
          window.location.href = $('#logoutLink').attr('href');
        }
      });
      return false;
    });

    $("#orderViewed-btn").on("click", function () {
      audio.loop = false;
      audio.currentTime = 0;
      audio.pause();
      var $o_id = $("#o_id").val();
      var $prep_time_in_mins = $('#timeInput').val();
      $.post(
        "index.php?controller=pjFrontPublic&action=pjActionOrderViewed",
        { order_id: $o_id, prep_time_in_mins: $prep_time_in_mins }
      ).done(function (data) {
        if (data.status == 'true') {
          //showNotification(data.order);
        }
      });
      location.reload();
    })
    $('#newOrderNotif').on('hidden.bs.modal', function () {
      audio.loop = false;
      audio.currentTime = 0;
      audio.pause();
    })
    // $("#orderConfirm-btn").on("click", function() {
    // 	var $o_id = $("#o_id").val();
    // 	$.post(
    // 		"index.php?controller=pjAdminOrders&action=pjActionConfirmOrder",
    // 		{order_id: $o_id}
    // 	  ).done(function (data) {
    // 		// if (data.status == 'true') {
    // 		// 	//console.log($.parseJSON(data.order));
    //         //    showNotification(data.order);
    // 		// }
    // 	});
    // })
    // $("#orderCancel-btn").on("click", function() {
    // 	var $o_id = $("#o_id").val();
    // 	$.post(
    // 		"index.php?controller=pjAdminOrders&action=pjActionCancelOrder",
    // 		{order_id: $o_id}
    // 	  ).done(function (data) {
    // 		// if (data.status == 'true') {
    // 		// 	//console.log($.parseJSON(data.order));
    //         //    showNotification(data.order);
    // 		// }
    // 	});
    // })
    $("#nav-item-sms").on("mouseover", function () {
      if ($("#credits_count").attr("data-api-set") == "true") {
        $("#popover").popover('hide');
      }
    })
    $("#content").on("click", ".notice-close", function (e) {
      if (e && e.preventDefault) {
        e.preventDefault();
      }
      $(this).closest(".notice-box").fadeOut();
      return false;
    });
    rbApp.enableButtons = function ($dialog) {
      if ($dialog.length > 0) {
        $dialog.siblings(".ui-dialog-buttonpane").find("button").button("enable");
      }
    };

    rbApp.disableButtons = function ($dialog) {
      if ($dialog.length > 0) {
        $dialog.siblings(".ui-dialog-buttonpane").find("button").button("disable");
      }
    };
  });
})(jQuery_1_8_2);
