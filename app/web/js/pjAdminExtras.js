var jQuery_1_8_2 = jQuery_1_8_2 || $.noConflict();
(function ($, undefined) {
	$(function () {
		var $frmCreateExtra = $("#frmCreateExtra"),
			$frmUpdateExtra = $("#frmUpdateExtra"),
			$pjFdFormWrapper = $("#pjFdFormWrapper"),
			multilang = ($.fn.multilang !== undefined),
			chosen = ($.fn.chosen !== undefined),
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
			$.get("index.php?controller=pjAdminExtras&action=pjActionCreateForm").done(function (data) {
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
			$frmCreateExtra = $("#frmCreateExtra");
			if (chosen) {
				$("#product_id").chosen();
			}
			if ($frmCreateExtra.length > 0 && validate) {
				$frmCreateExtra.validate({
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
					    $.post("index.php?controller=pjAdminExtras&action=pjActionCreate", $(form).serialize()).done(function (data) {
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
								$grid.datagrid("load", "index.php?controller=pjAdminExtras&action=pjActionGetExtra", "name", "ASC", content.page, content.rowCount);
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
		function bindUpdateForm()
		{
			$frmUpdateExtra = $("#frmUpdateExtra");
			if (chosen) {
				$("#product_id").chosen();
			}
			if ($frmUpdateExtra.length > 0 && validate) {
				$frmUpdateExtra.validate({
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
					    $.post("index.php?controller=pjAdminExtras&action=pjActionUpdate", $(form).serialize()).done(function (data) {
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
								$grid.datagrid("load", "index.php?controller=pjAdminExtras&action=pjActionGetExtra", "name", "ASC", content.page, content.rowCount);
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
		if(myLabel.trigger_create == 1)
		{
			getCreateForm();
		}
		if ($("#grid").length > 0 && datagrid) {
			function formatProducts (str, obj) 
			{
				if(parseInt(str, 10) > 0)
				{
					return '<span class="pjFdNumberCell"><a href="index.php?controller=pjAdminProducts&action=pjActionIndex&category_id='+obj.id+'">'+str+'</a></span>';
				}else{
					return '<span class="pjFdNumberCell">'+str+'</span>';
				}
			}
			var $grid = $("#grid").datagrid({
				buttons: [{type: "edit", url: "index.php?controller=pjAdminExtras&action=pjActionUpdateForm&id={:id}"},
				          {type: "delete", url: "index.php?controller=pjAdminExtras&action=pjActionDeleteExtra&id={:id}"}
				          ],
				columns: [{text: myLabel.extra_name, type: "text", sortable: true, editable: true},
				          {text: myLabel.price, type: "text", sortable: true, editable: false},
				          {text: myLabel.products_used, type: "text", sortable: true, editable: false, renderer: formatProducts}],
				dataUrl: "index.php?controller=pjAdminExtras&action=pjActionGetExtra",
				dataType: "json",
				fields: ['name', 'price', 'products'],
				paginator: {
					actions: [
					   {text: myLabel.delete_selected, url: "index.php?controller=pjAdminExtras&action=pjActionDeleteExtraBulk", render: true, confirmation: myLabel.delete_confirmation}
					],
					gotoPage: true,
					paginate: true,
					total: true,
					rowCount: true
				},
				saveUrl: "index.php?controller=pjAdminExtras&action=pjActionSaveExtra&id={:id}",
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
			$grid.datagrid("load", "index.php?controller=pjAdminExtras&action=pjActionGetExtra", "name", "ASC", content.page, content.rowCount);
			return false;
		}).on("click", ".pjFdAddExtra", function (e) {
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