var jQuery_1_8_2 = jQuery_1_8_2 || $.noConflict();
(function ($, undefined) {
	$(function () {
		"use strict";
		var $frmCreateCategory = $("#frmCreateCategory"),
			$frmUpdateCategory = $("#frmUpdateCategory"),
			$pjFdFormWrapper = $("#pjFdFormWrapper"),
			multilang = ($.fn.multilang !== undefined),
			dialog = ($.fn.dialog !== undefined),
			validate = ($.fn.validate !== undefined),
			datagrid = ($.fn.datagrid !== undefined),
			locale_id = myLabel.localeId,
			$tr = null;
		
		if (multilang && 'pjCmsLocale' in window) {
			$(".multilang").multilang({
				langs: pjCmsLocale.langs,
				flagPath: pjCmsLocale.flagPath,
				tooltip: "",
				select: function (event, ui) {
					locale_id = ui.index;
				}
			});
		}
		function getCreateForm()
		{
			$.get("index.php?controller=pjAdminCategories&action=pjActionCreateForm").done(function (data) {
				$pjFdFormWrapper.html(data);
				bindCreateForm();
			});
		}
		if ($pjFdFormWrapper.length > 0) 
		{
			getCreateForm();
		}
		function highlightLanguage()
		{
			$(".pj-form-langbar-item").removeClass('btn-primary').removeClass('btn-white');
			$(".pj-form-langbar-item").each(function( index ) {
				if($(this).attr('data-index') == myLabel.localeId)
				{
					$(this).addClass('btn-primary');
				}
			});
			$(".pj-multilang-wrap").each(function( index ) {
				if($(this).attr('data-index') == myLabel.localeId)
				{
					$(this).css('display','block');
				}else{
					$(this).css('display','none');
				}
			});
		}
		function bindCreateForm()
		{
			$frmCreateCategory = $("#frmCreateCategory");
			if ($frmCreateCategory.length > 0 && validate) {
				$frmCreateCategory.validate({
					invalidHandler: function (event, validator) {
					    $(".pj-multilang-wrap").each(function( index ) {
							if($(this).attr('data-index') == myLabel.localeId)
							{
								locale_id = myLabel.localeId;
								$(this).css('display','block');
							}else{
								$(this).css('display','none');
							}
						});
						$(".pj-form-langbar-item").each(function( index ) {
							if($(this).attr('data-index') == myLabel.localeId)
							{
								locale_id = myLabel.localeId;
								$(this).addClass('btn-primary');
							}else{
								$(this).removeClass('btn-primary');
							}
						});
					},
					ignore: "",
					submitHandler: function(form){
						var ladda_buttons = $(form).find('.ladda-button');
					    if(ladda_buttons.length > 0)
	                    {
	                        var l = ladda_buttons.ladda();
	                        l.ladda('start');
	                    }
					    $.post("index.php?controller=pjAdminCategories&action=pjActionCreate", $(form).serialize()).done(function (data) {
					    	l.ladda('stop');
					    	if(data.status == 'OK')
					    	{
					    		getCreateForm();
					    		var content = $grid.datagrid("option", "content"),
									cache = $grid.datagrid("option", "cache");
								$.extend(cache, {
									status: "",
									q: ""
								});
								$grid.datagrid("option", "cache", cache);
								$grid.datagrid("load", "index.php?controller=pjAdminCategories&action=pjActionGetCategory", "order", "ASC", content.page, content.rowCount);
					    	}else if(data.code == '104'){
					    		swal({
					    			title: "",
									text: data.text,
									type: "warning",
									confirmButtonColor: "#DD6B55",
									confirmButtonText: "OK",
									closeOnConfirm: false,
									showLoaderOnConfirm: false
								}, function () {
									swal.close();
								});
					    	}
						});
						return false;
					}
				});
				highlightLanguage();
			}
		}
		function bindUpdateForm()
		{
			$frmUpdateCategory = $("#frmUpdateCategory");
			if ($frmUpdateCategory.length > 0 && validate) {
				$frmUpdateCategory.validate({
					invalidHandler: function (event, validator) {
					    $(".pj-multilang-wrap").each(function( index ) {
							if($(this).attr('data-index') == myLabel.localeId)
							{
								locale_id = myLabel.localeId;
								$(this).css('display','block');
							}else{
								$(this).css('display','none');
							}
						});
						$(".pj-form-langbar-item").each(function( index ) {
							if($(this).attr('data-index') == myLabel.localeId)
							{
								locale_id = myLabel.localeId;
								$(this).addClass('btn-primary');
							}else{
								$(this).removeClass('btn-primary');
							}
						});
					},
					ignore: "",
					submitHandler: function(form){
						var ladda_buttons = $(form).find('.ladda-button');
					    if(ladda_buttons.length > 0)
	                    {
	                        var l = ladda_buttons.ladda();
	                        l.ladda('start');
	                    }
					    $.post("index.php?controller=pjAdminCategories&action=pjActionUpdate", $(form).serialize()).done(function (data) {
					    	l.ladda('stop');
					    	if(data.status == 'OK')
					    	{
					    		getCreateForm();
					    		var content = $grid.datagrid("option", "content"),
									cache = $grid.datagrid("option", "cache");
								$.extend(cache, {
									status: "",
									q: ""
								});
								$grid.datagrid("option", "cache", cache);
								$grid.datagrid("load", "index.php?controller=pjAdminCategories&action=pjActionGetCategory", "order", "ASC", content.page, content.rowCount);
					    	}else if(data.code == '105'){
					    		swal({
					    			title: "",
									text: data.text,
									type: "warning",
									confirmButtonColor: "#DD6B55",
									confirmButtonText: "OK",
									closeOnConfirm: false,
									showLoaderOnConfirm: false
								}, function () {
									swal.close();
								});
					    	}
						});
						return false;
					}
				});
				highlightLanguage();
			}
		}
		function formatProducts (str, obj) 
		{
			if(parseInt(str, 10) > 0)
			{
				return '<span class="pjFdNumberCell"><a href="index.php?controller=pjAdminProducts&action=pjActionIndex&category_id='+obj.id+'">'+str+'</a></span>';
			}else{
				return '<span class="pjFdNumberCell">'+str+'</span>';
			}
		}
		function formatOrderNumber (str, obj) 
		{
			return '<span class="pjFdNumberCell">'+str+'</span>';
		}
		if ($("#grid").length > 0 && datagrid) {
			var $grid = $("#grid").datagrid({
				buttons: [{type: "edit", url: "index.php?controller=pjAdminCategories&action=pjActionUpdateForm&id={:id}"},
				          {type: "delete", url: "index.php?controller=pjAdminCategories&action=pjActionDeleteCategory&id={:id}"}
				          ],
				columns: [{text: myLabel.category_name, type: "text", sortable: true, editable: true},
				          {text: myLabel.products, type: "text", sortable: true, editable: false, renderer: formatProducts},
				          {text: myLabel.order, type: "text", sortable: true, editable: false, css: {cursor: "move"}, renderer: formatOrderNumber},
				          {text: myLabel.status, type: "toggle", sortable: true, editable: true, positiveLabel: myLabel.active, positiveValue: "T", negativeLabel: myLabel.inactive, negativeValue: "F"}
				          ],
				dataUrl: "index.php?controller=pjAdminCategories&action=pjActionGetCategory",
				dataType: "json",
				fields: ['name', 'cnt_products', 'order', 'status'],
				paginator: {
					actions: [
					   {text: myLabel.delete_selected, url: "index.php?controller=pjAdminCategories&action=pjActionDeleteCategoryBulk", render: true, confirmation: myLabel.delete_confirmation}
					],
					gotoPage: true,
					paginate: true,
					total: true,
					rowCount: true
				},
				sortable: true,
				sortableUrl: "index.php?controller=pjAdminCategories&action=pjActionSortCategory",
				saveUrl: "index.php?controller=pjAdminCategories&action=pjActionSaveCategory&id={:id}",
				select: {
					field: "id",
					name: "record[]",
					cellClass: 'cell-width-2'
					
				}
			});
		}
		
		if(myLabel.trigger_create == 1)
		{
			getCreateForm();
		}
		$(document).on("click", ".btn-all", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			$(this).addClass("btn-primary active").removeClass("btn-default")
				.siblings(".btn").removeClass("btn-primary active").addClass("btn-default");
			var content = $grid.datagrid("option", "content"),
				cache = $grid.datagrid("option", "cache");
			$.extend(cache, {
				status: "",
				q: ""
			});
			$grid.datagrid("option", "cache", cache);
			$grid.datagrid("load", "index.php?controller=pjAdminCategories&action=pjActionGetCategory", "order", "ASC", content.page, content.rowCount);
			
		}).on("click", ".btn-filter", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			var $this = $(this),
				content = $grid.datagrid("option", "content"),
				cache = $grid.datagrid("option", "cache"),
				obj = {};
			$this.addClass("btn-primary active").removeClass("btn-default")
				.siblings(".btn").removeClass("btn-primary active").addClass("btn-default");
			obj.status = "";
			obj[$this.data("column")] = $this.data("value");
			$.extend(cache, obj);
			$grid.datagrid("option", "cache", cache);
			$grid.datagrid("load", "index.php?controller=pjAdminCategories&action=pjActionGetCategory", "order", "ASC", content.page, content.rowCount);
			
		}).on("submit", ".frm-filter", function (e) {
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
			$grid.datagrid("load", "index.php?controller=pjAdminCategories&action=pjActionGetCategory", "order", "ASC", content.page, content.rowCount);
			return false;
		}).on("click", ".pjFdAddCategory", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			getCreateForm();
			if($tr != null)
			{
				$tr.find('.pj-table-icon-edit').show();
				$tr.find('.pj-table-icon-delete').show();
				$tr = null;
			}
		}).on("click", ".pjFdBtnCancel", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			getCreateForm();
			if($tr != null)
			{
				$tr.find('.pj-table-icon-edit').show();
				$tr.find('.pj-table-icon-delete').show();
				$tr = null;
			}
		}).on("click", ".pj-table-icon-edit", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			var $this = $(this);
			var href = $this.attr('href');
			$.get(href).done(function (data) {
				$pjFdFormWrapper.html(data);
				bindUpdateForm();
				if($tr != null)
				{
					$tr.find('.pj-table-icon-edit').show();
					$tr.find('.pj-table-icon-delete').show();
					$tr = null;
				}
				$tr = $this.closest("tr");
				$tr.find('.pj-table-icon-edit').hide();
				$tr.find('.pj-table-icon-delete').hide();
			});
		});
	});
})(jQuery_1_8_2);