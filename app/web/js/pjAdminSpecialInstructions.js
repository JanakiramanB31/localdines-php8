var jQuery_1_8_2 = jQuery_1_8_2 || $.noConflict();
(function ($, undefined) {
	$(function () {
		"use strict";
		var $frmCreateSpecialInstruction = $("#frmCreateSpecialInstruction"),
		    $frmCreateSpecialInstructionType = $("#frmCreateSpecialInstructionType"),
			$frmUpdateSpecialInstruction = $("#frmUpdateSpecialInstruction"),
			$frmUpdateSpecialInstructionType = $("#frmUpdateSpecialInstructionType"),
			$pjFdFormWrapper = $("#pjFdFormWrapper"),
			$pjFdFormTypeWrapper = $("#pjFdFormTypeWrapper"),
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
			$.get("index.php?controller=pjAdminSpecialInstructions&action=pjActionCreateForm").done(function (data) {
				$pjFdFormWrapper.html(data);
				bindCreateForm();
			});
		}
		function getCreateTypeForm()
		{
			$.get("index.php?controller=pjAdminSpecialInstructions&action=pjActionCreateTypeForm").done(function (data) {
				$pjFdFormTypeWrapper.html(data);
				bindCreateTypeForm();
			});
		}
		if ($pjFdFormWrapper.length > 0) 
		{
			getCreateForm();
		}
		if ($pjFdFormTypeWrapper.length > 0) 
		{
			getCreateTypeForm();
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
			$frmCreateSpecialInstruction = $("#frmCreateSpecialInstruction");
			if ($frmCreateSpecialInstruction.length > 0 && validate) {
				$frmCreateSpecialInstruction.validate({
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
						console.log("getting submit");
					    // $.post("index.php?controller=pjAdminSpecialInstructions&action=pjActionCreate", $(form).serialize()).done(function (data) {
					    // 	l.ladda('stop');
					    // 	if(data.status == 'OK')
					    // 	{
					    // 		getCreateForm();
					    // 		var content = $grid.datagrid("option", "content"),
						// 			cache = $grid.datagrid("option", "cache");
						// 		$.extend(cache, {
						// 			status: "",
						// 			q: ""
						// 		});
						// 		$grid.datagrid("option", "cache", cache);
						// 		$grid.datagrid("load", "index.php?controller=pjAdminSpecialInstructions&action=pjActionGetSpecialInstruction", "order", "ASC", content.page, content.rowCount);
					    // 	}else if(data.code == '104'){
					    // 		swal({
					    // 			title: "",
						// 			text: data.text,
						// 			type: "warning",
						// 			confirmButtonColor: "#DD6B55",
						// 			confirmButtonText: "OK",
						// 			closeOnConfirm: false,
						// 			showLoaderOnConfirm: false
						// 		}, function () {
						// 			swal.close();
						// 		});
					    // 	}
						// });
						var formData = new FormData(form);
						$.ajax({
							data: formData,
							url: "index.php?controller=pjAdminSpecialInstructions&action=pjActionCreate",
							type: "POST",
							enctype: 'multipart/form-data',
							processData: false,  // tell jQuery not to process the data
							contentType: false,  // tell jQuery not to set contentType
							success: function (data) {

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
									$grid.datagrid("load", "index.php?controller=pjAdminSpecialInstructions&action=pjActionGetSpecialInstruction", "order", "ASC", content.page, content.rowCount);
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

							},
						});
						return false;
					}
				});
				highlightLanguage();
			}
		}
		function bindCreateTypeForm()
		{
			console.log("comes to bind");
			$frmCreateSpecialInstructionType = $("#frmCreateSpecialInstructionType");
			if ($frmCreateSpecialInstructionType.length > 0 && validate) {
				$frmCreateSpecialInstructionType.validate({
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
						console.log("getting submit");
					    $.post("index.php?controller=pjAdminSpecialInstructions&action=pjActionCreateType", $(form).serialize()).done(function (data) {
					    	l.ladda('stop');
					    	if(data.status == 'OK')
					    	{
					    		getCreateTypeForm();
					    		var content = $grid.datagrid("option", "content"),
									cache = $grid.datagrid("option", "cache");
								$.extend(cache, {
									status: "",
									q: ""
								});
								$grid.datagrid("option", "cache", cache);
								$grid.datagrid("load", "index.php?controller=pjAdminSpecialInstructions&action=pjActionGetSpecialInstructionType", "order", "ASC", content.page, content.rowCount);
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
						// var formData = new FormData(form);
						// $.ajax({
						// 	data: formData,
						// 	url: "index.php?controller=pjAdminSpecialInstructions&action=pjActionCreateType",
						// 	type: "POST",
						// 	enctype: 'multipart/form-data',
						// 	processData: false,  // tell jQuery not to process the data
						// 	contentType: false,  // tell jQuery not to set contentType
						// 	success: function (data) {

						// 		l.ladda('stop');
						// 		if(data.status == 'OK')
						// 		{
						// 			getCreateForm();
						// 			var content = $grid.datagrid("option", "content"),
						// 				cache = $grid.datagrid("option", "cache");
						// 			$.extend(cache, {
						// 				status: "",
						// 				q: ""
						// 			});
						// 			$grid.datagrid("option", "cache", cache);
						// 			$grid.datagrid("load", "index.php?controller=pjAdminSpecialInstructions&action=pjActionGetSpecialInstructionType", "order", "ASC", content.page, content.rowCount);
						// 		}else if(data.code == '104'){
						// 			swal({
						// 				title: "",
						// 				text: data.text,
						// 				type: "warning",
						// 				confirmButtonColor: "#DD6B55",
						// 				confirmButtonText: "OK",
						// 				closeOnConfirm: false,
						// 				showLoaderOnConfirm: false
						// 			}, function () {
						// 				swal.close();
						// 			});
						// 		}

						// 	},
						// });
						return false;
					}
				});
				highlightLanguage();
			}
		}
		function bindUpdateForm()
		{
			$frmUpdateSpecialInstruction = $("#frmUpdateSpecialInstruction");
			if ($frmUpdateSpecialInstruction.length > 0 && validate) {
				$frmUpdateSpecialInstruction.validate({
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
					    // $.post("index.php?controller=pjAdminSpecialInstructions&action=pjActionUpdate", $(form).serialize()).done(function (data) {
					    // 	l.ladda('stop');
					    // 	if(data.status == 'OK')
					    // 	{
					    // 		getCreateForm();
					    // 		var content = $grid.datagrid("option", "content"),
						// 			cache = $grid.datagrid("option", "cache");
						// 		$.extend(cache, {
						// 			status: "",
						// 			q: ""
						// 		});
						// 		$grid.datagrid("option", "cache", cache);
						// 		$grid.datagrid("load", "index.php?controller=pjAdminSpecialInstructions&action=pjActionGetSpecialInstruction", "order", "ASC", content.page, content.rowCount);
					    // 	}else if(data.code == '105'){
					    // 		swal({
					    // 			title: "",
						// 			text: data.text,
						// 			type: "warning",
						// 			confirmButtonColor: "#DD6B55",
						// 			confirmButtonText: "OK",
						// 			closeOnConfirm: false,
						// 			showLoaderOnConfirm: false
						// 		}, function () {
						// 			swal.close();
						// 		});
					    // 	}
						// });
						console.log("getting update");
						var formData = new FormData(form);
						$.ajax({
							data: formData,
							url: "index.php?controller=pjAdminSpecialInstructions&action=pjActionUpdate",
							type: "POST",
							enctype: 'multipart/form-data',
							processData: false,  // tell jQuery not to process the data
							contentType: false,  // tell jQuery not to set contentType
							success: function (data) {

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
									$grid.datagrid("load", "index.php?controller=pjAdminSpecialInstructions&action=pjActionGetSpecialInstruction", "order", "ASC", content.page, content.rowCount);
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

							},
						});
						return false;
					}
				});
				highlightLanguage();
			}
		}
		function bindUpdateTypeForm()
		{
			$frmUpdateSpecialInstructionType = $("#frmUpdateSpecialInstructionType");
			if ($frmUpdateSpecialInstructionType.length > 0 && validate) {
				$frmUpdateSpecialInstructionType.validate({
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
					    // $.post("index.php?controller=pjAdminSpecialInstructions&action=pjActionUpdate", $(form).serialize()).done(function (data) {
					    // 	l.ladda('stop');
					    // 	if(data.status == 'OK')
					    // 	{
					    // 		getCreateForm();
					    // 		var content = $grid.datagrid("option", "content"),
						// 			cache = $grid.datagrid("option", "cache");
						// 		$.extend(cache, {
						// 			status: "",
						// 			q: ""
						// 		});
						// 		$grid.datagrid("option", "cache", cache);
						// 		$grid.datagrid("load", "index.php?controller=pjAdminSpecialInstructions&action=pjActionGetSpecialInstruction", "order", "ASC", content.page, content.rowCount);
					    // 	}else if(data.code == '105'){
					    // 		swal({
					    // 			title: "",
						// 			text: data.text,
						// 			type: "warning",
						// 			confirmButtonColor: "#DD6B55",
						// 			confirmButtonText: "OK",
						// 			closeOnConfirm: false,
						// 			showLoaderOnConfirm: false
						// 		}, function () {
						// 			swal.close();
						// 		});
					    // 	}
						// });
						console.log("getting update");
						var formData = new FormData(form);
						$.ajax({
							data: formData,
							url: "index.php?controller=pjAdminSpecialInstructions&action=pjActionUpdateType",
							type: "POST",
							enctype: 'multipart/form-data',
							processData: false,  // tell jQuery not to process the data
							contentType: false,  // tell jQuery not to set contentType
							success: function (data) {

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
									$grid.datagrid("load", "index.php?controller=pjAdminSpecialInstructions&action=pjActionGetSpecialInstructionType", "order", "ASC", content.page, content.rowCount);
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

							},
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
		function formatImage(val, obj) {
			var src = val ? val : 'app/web/img/backend/no_image.png';
			return ['<a href="javascript:;"><img src="', src, '" style="width: 84px" /></a>'].join("");
		}
		if ($("#grid").length > 0 && datagrid) {
			var $grid = $("#grid").datagrid({
				buttons: [{type: "edit", url: "index.php?controller=pjAdminSpecialInstructions&action=pjActionUpdateForm&id={:id}"},
				          {type: "delete", url: "index.php?controller=pjAdminSpecialInstructions&action=pjActionDeleteSpecialInstruction&id={:id}"}
				          ],
				columns: [{text: myLabel.instructions, type: "text", sortable: true, editable: false},
				        //   {text: myLabel.products, type: "text", sortable: true, editable: false, renderer: formatProducts},
						  {text: myLabel.image, type: "text", sortable: false, editable: false, renderer: formatImage},
				        //   {text: myLabel.order, type: "text", sortable: true, editable: false, css: {cursor: "move"}, renderer: formatOrderNumber},
				          {text: myLabel.status, type: "toggle", sortable: true, editable: false, positiveLabel: myLabel.active, positiveValue: "T", negativeLabel: myLabel.inactive, negativeValue: "F"}
				          ],
				dataUrl: "index.php?controller=pjAdminSpecialInstructions&action=pjActionGetSpecialInstruction",
				dataType: "json",
				fields: ['instruction', 'image', 'status'],
				paginator: {
					actions: [
					   {text: myLabel.delete_selected, url: "index.php?controller=pjAdminSpecialInstructions&action=pjActionDeleteSpecialInstructionBulk", render: true, confirmation: myLabel.delete_confirmation}
					],
					gotoPage: true,
					paginate: true,
					total: true,
					rowCount: true
				},
				sortable: false,
				sortableUrl: "index.php?controller=pjAdminSpecialInstructions&action=pjActionSortSpecialInstruction",
				saveUrl: "index.php?controller=pjAdminSpecialInstructions&action=pjActionSaveSpecialInstruction&id={:id}",
				select: {
					field: "id",
					name: "record[]",
					cellClass: 'cell-width-2'
					
				}
			});
		}
		if ($("#grid-types").length > 0 && datagrid) {
			var $grid = $("#grid-types").datagrid({
				buttons: [{type: "edit", url: "index.php?controller=pjAdminSpecialInstructions&action=pjActionUpdateTypeForm&id={:id}"},
				          {type: "delete", url: "index.php?controller=pjAdminSpecialInstructions&action=pjActionDeleteSpecialInstruction&id={:id}"}
				          ],
				columns: [{text: "Type", type: "text", sortable: true, editable: false},
				        //   {text: myLabel.products, type: "text", sortable: true, editable: false, renderer: formatProducts},
				          {text: "Status", type: "toggle", sortable: true, editable: false, positiveLabel: myLabel.active, positiveValue: "T", negativeLabel: myLabel.inactive, negativeValue: "F"}
				          ],
				dataUrl: "index.php?controller=pjAdminSpecialInstructions&action=pjActionGetSpecialInstructionType",
				dataType: "json",
				fields: ['special_instruction_type', 'status'],
				paginator: {
					actions: [
					   {text: myLabel.delete_selected, url: "index.php?controller=pjAdminSpecialInstructions&action=pjActionDeleteSpecialInstructionBulk", render: true, confirmation: myLabel.delete_confirmation}
					],
					gotoPage: true,
					paginate: true,
					total: true,
					rowCount: true
				},
				sortable: false,
				sortableUrl: "index.php?controller=pjAdminSpecialInstructions&action=pjActionSortSpecialInstruction",
				saveUrl: "index.php?controller=pjAdminSpecialInstructions&action=pjActionSaveSpecialInstructionType&id={:id}",
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
			$grid.datagrid("load", "index.php?controller=pjAdminSpecialInstructions&action=pjActionGetSpecialInstruction", "order", "ASC", content.page, content.rowCount);
			
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
			$grid.datagrid("load", "index.php?controller=pjAdminSpecialInstructions&action=pjActionGetSpecialInstruction", "order", "ASC", content.page, content.rowCount);
			
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
			$grid.datagrid("load", "index.php?controller=pjAdminSpecialInstructions&action=pjActionGetSpecialInstruction", "order", "ASC", content.page, content.rowCount);
			return false;
		}).on("click", ".pjFdAddSpecialInstruction", function (e) {
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
		}).on("click", ".pjFdAddType", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			getCreateTypeForm();
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
		}).on("click", "#grid .pj-table-icon-edit", function (e) {
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
		}).on("click", "#grid-types .pj-table-icon-edit", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			var $this = $(this);
			var href = $this.attr('href');
			$.get(href).done(function (data) {
				$pjFdFormTypeWrapper.html(data);
				bindUpdateTypeForm();
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
			
		}).on("click", ".btnDeleteImage", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			console.log("image delete clicked");
			var id = $(this).attr('data-id');
			var $this = $(this);
			swal({
				title: myLabel.alert_title,
				text: myLabel.alert_text,
				type: "warning",
				showCancelButton: true,
				confirmButtonColor: "#DD6B55",
				confirmButtonText: myLabel.btn_delete,
				cancelButtonText: myLabel.btn_cancel,
				closeOnConfirm: false,
				showLoaderOnConfirm: true
			}, function () {
				$.post($this.attr("href"), {id: id}).done(function (data) {
					if (!(data && data.status)) {
						
					}
					switch (data.status) {
					case "OK":
						swal.close();
						$('#boxExtraImage').remove();
						break;
					}
				});
			});
		});
	});
})(jQuery_1_8_2);