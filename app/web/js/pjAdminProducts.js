var jQuery_1_8_2 = jQuery_1_8_2 || $.noConflict();
(function ($, undefined) {
	$(function () {
		"use strict";
		var $frmCreateProduct = $("#frmCreateProduct"),
			$frmUpdateProduct = $("#frmUpdateProduct"),
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
		if (chosen) {
			$("#category_id").chosen();
			$("#extra_id").chosen();
		}
		
		function setSizes()
		{
			var index_arr = new Array();
				
			$('#fd_size_list').find(".fd-size-row").each(function (index, row) {
				index_arr.push($(row).attr('data-index'));
			});
			$('#index_arr').val(index_arr.join("|"));
		}
		
		if ($frmCreateProduct.length > 0 && validate) {
			$frmCreateProduct.validate({
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
					if($('input[name=set_different_sizes]').is(':checked'))
					{
						setSizes();
					}
					$.ajax({
						type: 'POST',
						async: true, 
						url: "index.php?controller=pjAdminProducts&action=pjActionCheckPrices",
						data: $(form).serialize(),
						success: function(data){
							if(data.status == 'OK')
							{
								form.submit();
							}else{
								l.ladda('stop');
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
						}
					});
					return false;
				}
			});
		}
		if ($frmUpdateProduct.length > 0 && validate) {
			$frmUpdateProduct.validate({
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
					if($('input[name=set_different_sizes]').is(':checked'))
					{
						setSizes();
					}
					$.ajax({
						type: 'POST',
						async: true, 
						url: "index.php?controller=pjAdminProducts&action=pjActionCheckPrices",
						data: $(form).serialize(),
						success: function(data){
							if(data.status == 'OK')
							{
								form.submit();
							}else{
								l.ladda('stop');
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
						}
					});
					return false;
				}
			});
		}
		function formatImage(val, obj) {
			var src = val ? val : 'app/web/img/backend/no_image.png';
			return ['<a href="index.php?controller=pjAdminProducts&action=pjActionUpdate&id=', obj.id ,'"><img src="', src, '" style="width: 84px" /></a>'].join("");
		}
		if ($("#grid").length > 0 && datagrid) {
			var $grid = $("#grid").datagrid({
				buttons: [{type: "edit", url: "index.php?controller=pjAdminProducts&action=pjActionUpdate&id={:id}"},
				          {type: "delete", url: "index.php?controller=pjAdminProducts&action=pjActionDeleteProduct&id={:id}"},
				          {type: "copy", url: "index.php?controller=pjAdminProducts&action=pjActionDuplicate&id={:id}"}
				          ],
				columns: [{text: myLabel.image, type: "text", sortable: false, editable: false, renderer: formatImage},
				          {text: myLabel.name, type: "text", sortable: true, editable: true},
				          {text: myLabel.price, type: "text", sortable: false, editable: false},
				          {text: myLabel.status, type:"toggle", sortable: true, editable: true, positiveLabel: myLabel.active, positiveValue: "1", negativeLabel: myLabel.inactive, negativeValue: "0"},
				          {text: myLabel.is_featured, type: "toggle", sortable: true, editable: true, 
				        	  // editableRenderer: function () {
				        		//   return 0;
				        	  // },
				        	  saveUrl: "index.php?controller=pjAdminProducts&action=pjActionSaveFeatured&id={:id}",
				        	  positiveLabel: myLabel.yes, positiveValue: "1", negativeLabel: myLabel.no, negativeValue: "0", 
				        	  cellClass: "text-center"}],
				dataUrl: "index.php?controller=pjAdminProducts&action=pjActionGetProduct" + pjGrid.queryString,
				dataType: "json",
				fields: ['image', 'name', 'price', 'status', 'is_featured'],
				paginator: {
					actions: [
					   {text: myLabel.delete_selected, url: "index.php?controller=pjAdminProducts&action=pjActionDeleteProductBulk", render: true, confirmation: myLabel.delete_confirmation}
					],
					gotoPage: true,
					paginate: true,
					total: true,
					rowCount: true
				},
				saveUrl: "index.php?controller=pjAdminProducts&action=pjActionSaveProduct&id={:id}",
				select: {
					field: "id",
					name: "record[]",
					cellClass: 'cell-width-2'
				}
			});
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
			$grid.datagrid("load", "index.php?controller=pjAdminProducts&action=pjActionGetProduct", "is_featured", "DESC", content.page, content.rowCount);
			
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
			$grid.datagrid("load", "index.php?controller=pjAdminProducts&action=pjActionGetProduct", "is_featured", "DESC", content.page, content.rowCount);
		})
		.on("click", ".btn-type", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			var $this = $(this),
				content = $grid.datagrid("option", "content"),
				cache = $grid.datagrid("option", "cache"),
				obj = {};
			$this.addClass("btn-primary active").removeClass("btn-default")
				.siblings(".btn").removeClass("btn-primary active").addClass("btn-default");
			obj[$this.data("column")] = $this.data("value");
			$.extend(cache, obj);
			$grid.datagrid("option", "cache", cache);
			$grid.datagrid("load", "index.php?controller=pjAdminProducts&action=pjActionGetProduct", "is_featured", "DESC", content.page, content.rowCount);
		})
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
			$grid.datagrid("load", "index.php?controller=pjAdminProducts&action=pjActionGetProduct", "is_featured", "DESC", content.page, content.rowCount);
			return false;

		// }).on("click", '.jsBtnStatus', function(e){
		// 	console.log("hi");
		// 	var $this = $(this),
		// 		content = $grid.datagrid("option", "content"),
		// 		cache = $grid.datagrid("option", "cache");
		// 	$.extend(cache, {
		// 		status: $this.val()
		// 	});
		// 	$grid.datagrid("option", "cache", cache);
		// 	$grid.datagrid("load", "index.php?controller=pjAdminProducts&action=pjActionGetProduct", "is_featured", "DESC", content.page, content.rowCount);
		// 	return false;

		}).on("change", '#filter_category', function(e){

			var $this = $(this),
				content = $grid.datagrid("option", "content"),
				cache = $grid.datagrid("option", "cache");
			$.extend(cache, {
				category_id: $this.val()
			});
			$grid.datagrid("option", "cache", cache);
			$grid.datagrid("load", "index.php?controller=pjAdminProducts&action=pjActionGetProduct", "is_featured", "DESC", content.page, content.rowCount);
			return false;
			
		}).on("click", '.fa-info-circle', function(event){
			// $.datagrid._defaults.content.page = 2;
			// console.log($.datagrid._defaults.content.page);
			// var e = jQuery.Event("keypress.dg");
			// e.which = 13;
			// $(".pj-selector-goto").trigger(2,e);
			console.log("clicked");
			var e = jQuery.Event("keypress.dg");
				e.which = 13;
				//$(".pj-selector-goto").val(2);
				$(".pj-selector-goto").trigger(e);
		}).on("click", '.pj-add-size', function(e){
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			//Datagrid
			var clone_text = $('#fd_size_clone').html(),
				index = Math.ceil(Math.random() * 999999),
				number_of_sizes = $('#fd_size_list').find(".fd-size-row").length,
				order = parseInt(number_of_sizes, 10) + 1;
			clone_text = clone_text.replace(/\{INDEX\}/g, 'fd_' + index);
			clone_text = clone_text.replace(/\{ORDER\}/g, order);
			$('#fd_size_list').append(clone_text);
		}).on("click", '.pj-remove-size', function(e){
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			var $size = $(this).parent().parent().parent(),
				id = $size.attr('data-index');
			if(id.indexOf("fd") == -1)
			{
				remove_arr.push(id);
			}
			$('#remove_arr').val(remove_arr.join("|"));
			$size.remove();
			
			$('#fd_size_list').find(".fd-size-row").each(function (order, row) {
				var index = $(row).attr('data-index'),
					title = myLabel.size + " " + (order + 1);
				$('.fd-title-' + index).html(title);
			});
		}).on("click", '#set_yes', function(e){
			$('#multiple_prices').css('display', 'block');
			$('#signle_price').css('display', 'none');
		}).on("click", '#set_no', function(e){
			$('#multiple_prices').css('display', 'none');
			$('#signle_price').css('display', 'block');
		}).on("keyup", '.pj-positive-number', function(e){
			if($(this).val() == '')
			{
				$(this).removeClass('pj-error-field');
			}else{
				if(Number($(this).val()) < 0 || $.isNumeric($(this).val()) == false)
			    {
			    	$(this).addClass('pj-error-field');
			    }else{
			    	$(this).removeClass('pj-error-field');
			    }
			}
			
		}).on("keyup", '.fdRequired', function(e){
			if($(this).val() == '')
			{
				$(this).addClass('pj-error-field');
			}else{
				$(this).removeClass('pj-error-field');
			}
			
		}).on("change", '.onoffswitch-size .onoffswitch-checkbox', function(e){
			if ($(this).prop('checked')) {
                $('.order-size-field').hide();
                $('.order-size-field').find('.pj-field-price').each(function(){
                	$(this).removeClass('required').removeClass('number').valid();
                });
                $('.order-size-table').show();
                $('.order-size-table').find('.fdRequired').each(function(){
                	$(this).addClass('required');
                });
                $('.order-size-table').find('.pj-field-price').each(function(){
                	$(this).addClass('required').addClass('number');
                });
            }else {
                $('.order-size-field').show();
                $('.order-size-field').find('.pj-field-price').each(function(){
                	$(this).addClass('required').addClass('number');
                });
                $('.order-size-table').hide();
                $('.order-size-table').find('.fdRequired').each(function(){
                	$(this).removeClass('required').valid();
                });
                $('.order-size-table').find('.pj-field-price').each(function(){
                	$(this).removeClass('required').removeClass('number').valid();
                });
            }
		}).on("click", ".btnDeleteImage", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
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
		}).on("change", '#category_id', function(e){
			$(this).valid();
      // console.log("category changing");
      let data = $("#category_id").val();
      $.ajax({
        type: 'POST',
        async: true, 
        url: "index.php?controller=pjAdminProducts&action=pjActionCheckProductType",
        data: {
          cat_array : data
        },
        success: function(res) {
          let $ele = $('#is_veg_div');
          let $value = $('#is_veg');
          if(res.status) {
            $ele.addClass("d-none");
            $value.prop("checked", false);
          } else {
            $ele.removeClass("d-none");
            if(res.text) {
              $value.prop("checked", true);
            } else {
              $value.prop("checked", false);
            }
          }
        }
      });
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
				$grid.datagrid("load", "index.php?controller=pjAdminProducts&action=pjActionGetProduct", "is_featured", "DESC", content.page, content.rowCount);
				return false;
			}
		})

		// $(document).ready(function() {
		// 	$('#all').not(this).addClass('btn-primary');
	  // 		$('.jsBtnStatus').click(function() {
	  // 		$('.jsBtnStatus').removeClass('btn-primary');
	  //   	$('.jsBtnStatus').removeClass('active');
	  //   	$(this).addClass('active');
	  //   	$(this).css('color', 'white');
	  //   	$(this).addClass('btn-primary');
	  //   	$('.jsBtnStatus').not(this).css('color', '');
	  //   	$('#all').not(this).addClass('btn-default');
		//   });
		// });
	});
})(jQuery_1_8_2);