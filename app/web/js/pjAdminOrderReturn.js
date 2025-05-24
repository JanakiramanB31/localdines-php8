var jQuery_1_8_2 = jQuery_1_8_2 || $.noConflict();
(function ($, undefined) {
	$(function () {
		"use strict";
    var $frmFindDate = $("#frmFindDate"),
      $frmReturnOrder = $("#frmReturnOrder"),
      $frmUpdateReturnOrder = $("#frmUpdateReturnOrder"),
      $dialogDelete = $("#dialogDeleteImage"),
      datepicker = ($.fn.datepicker !== undefined),
      dialog = ($.fn.dialog !== undefined),
      chosen = ($.fn.chosen !== undefined),
      multilang = ($.fn.multilang !== undefined),
      validate = ($.fn.validate !== undefined),
      datagrid = ($.fn.datagrid !== undefined),
      remove_arr = new Array(),
      submited = false;
    
    var urlParams = new URLSearchParams(window.location.search);
    var updated_category = urlParams.get('category'); 
    var err_code = urlParams.get('err');
    var page = urlParams.get('page');
    
    if (multilang && 'pjCmsLocale' in window) {
      $(".multilang").multilang({
        langs: pjCmsLocale.langs,
        flagPath: pjCmsLocale.flagPath,
        tooltip: "",
        select: function (event, ui) {
          $("input[name='locale_id']").val(ui.index);
        }
      });
    }

    if ($("#grid").length > 0 && datagrid) {
      var $grid = $("#grid").datagrid({
        buttons: [
          {type: "edit", url: "index.php?controller=pjAdminOrderReturns&action=pjActionUpdate&id={:id}"},
          {type: "delete", url: "index.php?controller=pjAdminOrderReturns&action=pjActionDelete&id={:id}"}
        ],
        columns: [
          {text: myLabel.product_name, type: "text", sortable: false, editable: false},
          {text: myLabel.return_date, type: "text", sortable: true, editable: true},
          {text: myLabel.total_amount, type: "text", sortable: true, editable: true},
          {text: myLabel.purchase_date, type: "text", sortable: false, editable: false},
          {text: myLabel.product_qty, type: "text", sortable: false, editable: false}
        ],
        dataUrl: "index.php?controller=pjAdminOrderReturns&action=pjActionGetReturnOrderList" + pjGrid.queryString,
        dataType: "json",
        fields: ['product_name','return_date', 'amount', 'purchase_date', 'qty'],
        paginator: {
          actions: [
             {text: myLabel.delete_selected, url: "index.php?controller=pjAdminSuppliers&action=pjActionDeleteCompanyBulk", render: true, confirmation: myLabel.delete_confirmation}
          ],
          gotoPage: true,
          paginate: true,
          total: true,
          rowCount: true
        },
        saveUrl: "index.php?controller=pjAdminOrderReturns&action=pjActionSaveProduct&id={:id}",
        select: {
          field: "id",
          name: "record[]",
          cellClass: 'cell-width-2'
        }
      });
    }

    if ($('#datePickerOptions').length) {
      $.fn.datepicker.dates['en'] = {
        days: ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"],
        daysMin: $('#datePickerOptions').data('days').split("_"),
        daysShort: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"],
        months: $('#datePickerOptions').data('months').split("_"),
        monthsShort: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
        format: $('#datePickerOptions').data('format'),
        weekStart: parseInt($('#datePickerOptions').data('wstart'), 10),
      };
    };

    if (chosen) {
      $("#product_id").chosen();
    }

    if ($frmReturnOrder.length > 0) {

      $(".pj-field-count").TouchSpin({
        verticalbuttons: false,
        buttondown_class: "btn btn-white",
        buttonup_class: "btn btn-white",
        min: 1,
        max: 4294967295,
      });

      $('#purchase_date').datepicker({
        autoclose: true,
        todayHighlight: true,
        todayBtn: 'linked',
        todayTxt: 'Today',
      });
      $('#return_date').datepicker({
        autoclose: true,
        todayHighlight: true,
        todayBtn: 'linked',
        todayTxt: 'Today',
      });

      $frmReturnOrder.validate({
        errorPlacement: function(error, element) {
          if (element.hasClass('select2-hidden-accessible')) {
            error.insertAfter(element.next('.chosen-container'));
          } else if (element.parent('.input-group').length) {
            error.insertAfter(element.parent());
          } else if (element.parent().parent('.btn-group').length) {
            error.insertAfter(element.parent().parent());
          } else {
            error.insertAfter(element);
          }
        },
        ignore: "",
        invalidHandler: function (event, validator) {
            $(".pj-multilang-wrap").each(function( index ) {
            if($(this).attr('data-index') == myLabel.localeId)
            {
              $(this).css('display','block');
            }else{
              $(this).css('display','none');
            }
          });
          $(".pj-form-langbar-item").each(function( index ) {
            if($(this).attr('data-index') == myLabel.localeId)
            {
              $(this).addClass('btn-primary');
            }else{
              $(this).removeClass('btn-primary');
            }
          });
        },
        submitHandler: function(form) {
          var ladda_buttons = $(form).find('.ladda-button');
          if (ladda_buttons.length > 0) {
            var l = ladda_buttons.ladda();
            l.ladda('start');
          }
          form.submit();
          return false;
        }
      });
    }
    function viewProductList() {
      $('.ibox-content').addClass('sk-loading');
      var q = $('#query').val();
      var content = $grid.datagrid("option", "content"),
      cache = $grid.datagrid("option", "cache");
      $.extend(cache, {
        q: q,
        page: page
      });
      $grid.datagrid("option", "cache", cache);
      $grid.datagrid("load", "index.php?controller=pjAdminOrderReturns&action=pjActionGetReturnOrderList", "created_date", "DESC", content.page, content.rowCount);
      return false;
      $('.ibox-content').removeClass('sk-loading');
    }

    function getTotal(qty, price) {
      let total = qty*price
      return total.toFixed(2);
    }

    function changeAmount() {
      let qty = $('#quantity').val();
      let price = $('#price').val();
      $('#amount').val(getTotal(qty, price));
    }

    function emptyForm() {
      $('#quantity').val('');
      $('#price').val('');
      $('#amount').val('');
      $('#reason').val('');
    }

    $(document).on('change', '#product_id', function () {
      let product_id = $('#product_id').val();
      if(product_id == "0") {
        emptyForm.call(null);
      } else {
        $.ajax({
          type: "POST",
          async: false,
          url: "index.php?controller=pjAdminOrderReturns&action=pjActionGetProductInformation",
          data: {
            product_id : product_id
          },
          success: function (data) {
            if (data.status) {
              if (data.res[0].set_different_sizes == 'T') {
                var div = $('#js-select');
                var label = $('<label>').addClass('control-label').text('Size').appendTo(div);
                var sel = $('<select>').attr('id', 'cus-select').addClass('form-control change-size').appendTo(div);
                sel.append($("<option>").attr('value', "").text("-- choose --")).attr('selected', true);
                $.each(data.res[0].size, function( index, value ) {
                  sel.append($("<option>").attr('value', value.price).text(value.price_name));
                });
              } else {
                $('#js-select').empty();
              }
              $('#quantity').val(1);
              $('#price').val(data.res[0].price);
              $('#amount').val(getTotal(1, data.res[0].price));
              if(submited) {
                $frmReturnOrder.submit();
              }
            }
          },
          // !MEGAMIND
        });
      }

    }).on('change', '#quantity', function () {
      changeAmount.call(null);
    }).on('change', '#price', function () {
      changeAmount.call(null);
    }).on("submit", ".frm-filter", function (e) {
      if (e && e.preventDefault) {
        e.preventDefault();
      }
      viewProductList.call(null);
    }).on('change', ".change-size", function () {
      let price = $('#cus-select').val();
      // let text = $('#cus-select option:selected').text();
      let text = $('#cus-select option:selected').attr('data-id');
      $('#hidden_size').val(text);
      // console.log(text);
      // console.log(price);
      $('#price').val(price);
      changeAmount.call(null);
    }).on('click', '#main_form', function () {
      submited = true;
    }).on('change', '#custom', function () {
      if ($('#custom').prop('checked')) {
        emptyForm.call(null);
        $('#product_id').val('0');
        $('#product_name').removeClass('d-none');
        $('#product_name input').addClass('required');
        $('#product_select').addClass('d-none');
      } else {
        $('#product_id').val('');
        $('#product_name ').addClass('d-none');
        $('#product_name input').removeClass('required');
        $('#product_select').removeClass('d-none');
      }
    });
		
	});
})(jQuery_1_8_2);