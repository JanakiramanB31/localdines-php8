var jQuery_1_8_2 = jQuery_1_8_2 || $.noConflict();
(function ($, undefined) {
	$(function () {
		"use strict";
		var $frmFindDate = $("#frmFindDate"),
			$frmCreateConfig = $("#frmCreateConfig"),
			$frmUpdateConfig = $("#frmUpdateConfig"),
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
			$grid.datagrid("load", "index.php?controller=pjAdminConfigs&action=pjActionGetConfig", "name", "ASC", content.page, content.rowCount);
			return false;
			$('.ibox-content').removeClass('sk-loading');

		}

		if ($frmFindDate.length > 0) {
			
		}
		
		if ($frmCreateConfig.length > 0 && validate) {
			$frmCreateConfig.validate({
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
				submitHandler: function(form){
					var ladda_buttons = $(form).find('.ladda-button');
				    if(ladda_buttons.length > 0)
              {
                var l = ladda_buttons.ladda();
                l.ladda('start');
              }
              form.submit();
					return false;
				}
			});
		}
		if ($frmUpdateConfig.length > 0 && validate) {
			$frmUpdateConfig.validate({
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
				submitHandler: function(form){
					var ladda_buttons = $(form).find('.ladda-button');
				    if(ladda_buttons.length > 0)
                    {
                        var l = ladda_buttons.ladda();
                        l.ladda('start');
                    }
					form.submit();
					return false;
				}
			});
		}

		if ($("#grid").length > 0 && datagrid) {
			var $grid = $("#grid").datagrid({
				buttons: [{type: "edit", url: "index.php?controller=pjAdminConfigs&action=pjActionUpdate&id={:id}"},
				          {type: "delete", url: "index.php?controller=pjAdminConfigs&action=pjActionDeleteConfig&id={:id}"}
				          ],
				columns: [
						  {text: myLabel.name, type: "text", sortable: false, editable: false},
              {text: myLabel.value, type: "text", sortable: true, editable: true},
							{text: myLabel.status, type: "text", sortable: true, editable: true},],
				dataUrl: "index.php?controller=pjAdminConfigs&action=pjActionGetConfig" + pjGrid.queryString,
				dataType: "json",
				fields: ['key','value','is_active'],
				paginator: {
					actions: [
					   {text: myLabel.delete_selected, url: "index.php?controller=pjAdminConfigs&action=pjActionDeleteConfigBulk", render: true, confirmation: myLabel.delete_confirmation}
					],
					gotoPage: true,
					paginate: true,
					total: true,
					rowCount: true
				},
				saveUrl: "index.php?controller=pjAdminConfigs&action=pjActionSaveProduct&id={:id}",
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
			$grid.datagrid("load", "index.php?controller=pjAdminConfigs&action=pjActionGetConfig", "name", "ASC", content.page, content.rowCount);
			return false;
		});
	});
})(jQuery_1_8_2);