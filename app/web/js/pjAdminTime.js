var jQuery_1_8_2 = jQuery_1_8_2 || $.noConflict();
(function ($, undefined) {
	$(function () {
		"use strict";
		var $frmTimeCustom = $("#frmTimeCustom"),
			$frmDefaultTime = $("#frmDefaultTime"),
			validate = ($.fn.validate !== undefined),
			datepicker = ($.fn.datepicker !== undefined),
			datagrid = ($.fn.datagrid !== undefined),
			classes = null;
		
		if ($('.pj-timepicker').length) {
        	$( ".pj-timepicker" ).each(function( index ) {
        		var $this = $(this);
        		$this.clockpicker({
                	twelvehour: myLabel.showperiod,
                	autoclose: true,
                	afterDone: function() {
            			
                    }
                });
    		});
        };
		if($('.i-checks').length > 0)
		{
			$('.i-checks').iCheck({
	            checkboxClass: 'icheckbox_square-green',
	            radioClass: 'iradio_square-green'
	        });
		}
		if ($frmDefaultTime.length > 0 && validate) {
			$frmDefaultTime.validate({
				submitHandler: function (form) {
					classes = null;
					var ladda_buttons = $(form).find('.ladda-button');
				    if(ladda_buttons.length > 0)
                    {
                        var l = ladda_buttons.ladda();
                        l.ladda('start');
                    }
					$.post("index.php?controller=pjAdminTime&action=pjActionCheckDefaultTime", $(form).serialize()).done(function (data) {
						if(data.status == 'OK')
						{
							form.submit();
						}else{
							if(typeof l != 'undefined')
							{
								l.ladda('stop');
							}
							if(data.code == '103')
							{
								if(data.classes.length > 0)
								{
									classes = data.classes;
									swal({
										title: myLabel.alert_title,
										text: myLabel.alert_text,
										type: "warning",
										showCancelButton: false,
										confirmButtonColor: "#DD6B55",
										confirmButtonText: myLabel.btn_ok,
										closeOnConfirm: false,
										showLoaderOnConfirm: true
									}, function () {
										swal.close();
										if(classes != null)
							    		{
							        		for (var i in classes) 
											{
												$("." + classes[i]).addClass("has-error").delay(3000).queue(function(){
												    $(this).removeClass("has-error").dequeue();
												});
											}
							    		}else{
							    			if($frmTimeCustom.length > 0)
											{
							    				$(".pjFdTimeGroup").addClass("has-error").delay(3000).queue(function(){
												    $(this).removeClass("has-error").dequeue();
												});
											}
							    		}
									});
								}
							}
						}
					});
					return false;
				}
			});
		}
		if ($frmTimeCustom.length > 0 && validate) {
			$frmTimeCustom.validate({
				submitHandler: function (form) {
					classes = null;
					var ladda_buttons = $(form).find('.ladda-button');
				    if(ladda_buttons.length > 0)
                    {
                        var l = ladda_buttons.ladda();
                        l.ladda('start');
                    }
					$.post("index.php?controller=pjAdminTime&action=pjActionCheckCustomTime", $(form).serialize()).done(function (data) {
						if(data.status == 'OK')
						{
							$.post("index.php?controller=pjAdminTime&action=pjActionSaveCustom", $(form).serialize()).done(function (data) {
								if(data.status == 'OK')
								{
									var content = $grid.datagrid("option", "content");
									$grid.datagrid("load", $grid.data('datagrid').settings.dataUrl, content.column, content.direction, content.page, content.rowCount);
									resetCustomForm(data.location_id);
								}
							});
						}else{
							if(typeof l != 'undefined')
							{
								l.ladda('stop');
							}
							if(data.code == '103')
							{
								swal({
									title: myLabel.alert_title,
									text: myLabel.alert_text,
									type: "warning",
									showCancelButton: false,
									confirmButtonColor: "#DD6B55",
									confirmButtonText: myLabel.btn_ok,
									closeOnConfirm: false,
									showLoaderOnConfirm: true
								}, function () {
									swal.close();
									if(classes != null)
						    		{
						        		for (var i in classes) 
										{
											$("." + classes[i]).addClass("has-error").delay(3000).queue(function(){
											    $(this).removeClass("has-error").dequeue();
											});
										}
						    		}else{
						    			if($frmTimeCustom.length > 0)
										{
						    				$(".pjFdTimeGroup").addClass("has-error").delay(3000).queue(function(){
											    $(this).removeClass("has-error").dequeue();
											});
										}
						    		}
								});
							}
						}
					});
					return false;
				}
			});
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
            $frmTimeCustom.find(".input-group.date").datepicker({
                autoclose: true,
                todayHighlight: true
            });
		}
		function resetCustomForm(location_id)
		{
			$.post("index.php?controller=pjAdminTime&action=pjActionResetForm", {location_id: location_id}).done(function (data) {
				$('#frmTimeCustom').html(data);
				if ($frmTimeCustom.length > 0 && validate) {
					$frmTimeCustom.find(".input-group.date").datepicker({
		                autoclose: true,
		                todayHighlight: true
		            });
				}
				$( ".pj-timepicker" ).each(function( index ) {
	        		var $this = $(this);
	        		$this.clockpicker({
	                	twelvehour: myLabel.showperiod,
	                	autoclose: true,
	                	afterDone: function() {
	            			
	                    }
	                });
	    		});
			});
		}
		
		if ($("#grid").length > 0 && datagrid) {
			var $grid = $("#grid").datagrid({
				buttons: [{type: "edit", url: "index.php?controller=pjAdminTime&action=pjActionGetCustomDate&id={:id}"},
				          {type: "delete", url: "index.php?controller=pjAdminTime&action=pjActionDeleteDate&id={:id}"}
				          ],
				columns: [{text: myLabel.date, type: "date", sortable: true, editable: false, renderer: $.datagrid._formatDate, dateFormat: pjGrid.jsDateFormat},
				          {text: myLabel.type, type: "text", sortable: true, editable: false},
				          {text: myLabel.start_time, type: "text", sortable: true, editable: false},
				          {text: myLabel.end_time, type: "date", sortable: true, editable: false},
				          {text: myLabel.is_day_off, type: "text", sortable: true, editable: false}],
				dataUrl: "index.php?controller=pjAdminTime&action=pjActionGetDate" + pjGrid.queryString,
				dataType: "json",
				fields: ['date', 'type', 'start_time', 'end_time', 'is_dayoff'],
				paginator: {
					actions: [
							   {text: myLabel.delete_selected, url: "index.php?controller=pjAdminTime&action=pjActionDeleteDateBulk", render: true, confirmation: myLabel.delete_confirmation}
							],
					gotoPage: true,
					paginate: true,
					total: true,
					rowCount: true
				},
				select: {
					field: "id",
					name: "record[]",
					cellClass: 'cell-width-2'
				}
			});
		}
		
		$(document).on("ifChanged", "input[type='checkbox']", function () {
			var $this = $(this),
				$tr = $this.closest("tr"),
				day = $tr.attr('data-day'),
				type = $tr.attr('data-type');
			if ($this.is(":checked")) {
				$('.'+type+'WorkingDay_' + day).hide();
			} else {
				$('.'+type+'WorkingDay_' + day).show();
			}
		}).on("click", ".pj-table-icon-edit", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			var href = $(this).attr('href');
			$.get(href).done(function (data) {
				$('#frmTimeCustom').html(data);
				$frmTimeCustom.find(".input-group.date").datepicker({
	                autoclose: true,
	                todayHighlight: true
	            });
				$frmTimeCustom.find( ".pj-timepicker" ).each(function( index ) {
	        		var $this = $(this);
	        		$this.clockpicker({
	                	twelvehour: myLabel.showperiod,
	                	autoclose: true,
	                	afterDone: function() {
	            			
	                    }
	                });
	    		});
			});
		})/*.on("change", "#is_dayoff", function (e) {
			if($(this).is(':checked'))
			{
				$frmTimeCustom.find('.pj-timepicker').removeClass('required');
			}else{
				$frmTimeCustom.find('.pj-timepicker').addClass('required');
			}
			
		})*/;
	});
})(jQuery_1_8_2);