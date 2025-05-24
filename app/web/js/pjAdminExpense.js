var jQuery_1_8_2 = jQuery_1_8_2 || $.noConflict();
(function ($, undefined) {
	$(function () {
		"use strict";
		var $frmFindDate = $("#frmFindDate"),
			$frmCreateExpense = $("#frmCreateExpense"),
			$frmUpdateExpense = $("#frmUpdateExpense"),
			$dialogDelete = $("#dialogDeleteImage"),
			dialog = ($.fn.dialog !== undefined),
			chosen = ($.fn.chosen !== undefined),
			multilang = ($.fn.multilang !== undefined),
			validate = ($.fn.validate !== undefined),
			datagrid = ($.fn.datagrid !== undefined),
			remove_arr = new Array();
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
		function generateList() {
			$('.ibox-content').addClass('sk-loading');
			var from = $('#date_from').val();
			var to = $('#date_to').val();
			var content = $grid.datagrid("option", "content"),
			cache = $grid.datagrid("option", "cache");
			$.extend(cache, {
				date_to: to,
				date_from: from,
				category_id: updated_category,
				page: page
			});
			$grid.datagrid("option", "cache", cache);
			$grid.datagrid("load", "index.php?controller=pjAdminExpense&action=pjActionGetExpense", "c_name", "ASC", content.page, content.rowCount);
			return false;
			$('.ibox-content').removeClass('sk-loading');
		}

		if ($frmFindDate.length > 0) {
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
      $('#date_from').datepicker({
      	// endDate: $('#date_to').val(),
      	format: 'dd-mm-yyyy',
        autoclose: true
      }).on('changeDate', function (e) {
      	generateList.call(null);
			});
      $('#date_to').datepicker({
      	// startDate: $('#date_from').val(),
      	format: 'dd-mm-yyyy',
        autoclose: true
      }).on('changeDate', function (e) {
      	generateList.call(null);
			});
		}
		
		if ($frmCreateExpense.length > 0 && validate) {
			$frmCreateExpense.validate({
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
						if ($(this).attr('data-index') == myLabel.localeId) {
							$(this).css('display','block');
						} else {
							$(this).css('display','none');
						}
					});
					$(".pj-form-langbar-item").each(function( index ) {
						if($(this).attr('data-index') == myLabel.localeId) {
							$(this).addClass('btn-primary');
						} else {
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
			if ($('#expense_date').length) {
			  intializeDatePicker("#expense_date");
			}
		}
		if ($frmUpdateExpense.length > 0 && validate) {
			$frmUpdateExpense.validate({
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
						if ($(this).attr('data-index') == myLabel.localeId) {
							$(this).css('display','block');
						} else {
							$(this).css('display','none');
						}
					});
					$(".pj-form-langbar-item").each(function( index ) {
						if($(this).attr('data-index') == myLabel.localeId) {
							$(this).addClass('btn-primary');
						} else {
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

			if ($('#expense_date').length) {
	    	intializeDatePicker("#expense_date");
			}
		}

		if ($("#grid").length > 0 && datagrid) {
			var $grid = $("#grid").datagrid({
				buttons: [{type: "edit", url: "index.php?controller=pjAdminExpense&action=pjActionUpdate&id={:id}"},
				          {type: "delete", url: "index.php?controller=pjAdminExpense&action=pjActionDeleteExpense&id={:id}"}],
				columns: [{text: myLabel.date, type: "text", sortable: false, editable: false},
						  		{text: myLabel.supplier_name, type: "text", sortable: false, editable: false},
				          {text: myLabel.expense_name, type: "text", sortable: true, editable: true},
				          {text: myLabel.amount, type: "text", sortable: false, editable: false}],
				dataUrl: "index.php?controller=pjAdminExpense&action=pjActionGetExpense" + pjGrid.queryString,
				dataType: "json",
				fields: ['date','c_name', 'product_name', 'amount'],
				paginator: {
					actions: [
					   {text: myLabel.delete_selected, url: "index.php?controller=pjAdminExpense&action=pjActionDeleteExpenseBulk", render: true, confirmation: myLabel.delete_confirmation}
					],
					gotoPage: true,
					paginate: true,
					total: true,
					rowCount: true
				},
				saveUrl: "index.php?controller=pjAdminExpense&action=pjActionSaveProduct&id={:id}",
				select: {
					field: "id",
					name: "record[]",
					cellClass: 'cell-width-2'
				}
			});
		}
		$(document).on("submit", ".frm-filter", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			var $this = $(this),
				content = $grid.datagrid("option", "content"),
				cache = $grid.datagrid("option", "cache");
			$.extend(cache, {
				q: $this.find("input[name='q']").val()
			});
			$grid.datagrid("option", "cache", cache);
			$grid.datagrid("load", "index.php?controller=pjAdminExpense&action=pjActionGetExpense", "c_name", "ASC", content.page, content.rowCount);
			return false;
		});

		$(document).ready(function() {
			if(updated_category != '' && page > 0) {
				// var updated_category = $("#updated_product_category");
				// var page = $("#updated_product_page");
				var content = $grid.datagrid("option", "content"),
				cache = $grid.datagrid("option", "cache");
				$.extend(cache, {
					category_id: updated_category,
					page: page
				});
				$grid.datagrid("option", "cache", cache);
				$grid.datagrid("load", "index.php?controller=pjAdminExpense&action=pjActionGetProduct", "is_featured", "DESC", content.page, content.rowCount);
				return false;
			}
		})
		function intializeDatePicker($fieldName) {
			$($fieldName).datepicker({
        format: 'dd-mm-yyyy',
        autoclose: true
	    });
		}
	});
})(jQuery_1_8_2);