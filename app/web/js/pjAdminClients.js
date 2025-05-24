var jQuery_1_8_2 = jQuery_1_8_2 || $.noConflict();
(function ($, undefined) {
	$(function () {
		"use strict";
		var $frmCreateClient = $("#frmCreateClient"),
			$frmUpdateClient = $("#frmUpdateClient"),
			chosen = ($.fn.chosen !== undefined),
			datagrid = ($.fn.datagrid !== undefined);
		
		if (chosen) {
			$("#c_country").chosen();
		}
		if($frmCreateClient.length > 0)
		{
			$.validator.addMethod("phoneNumber", function (value, element) {
		        if ($(element).attr("data-wt") == "valid") {
		          return true;
		        } else {
		          return false;
		        }
		    });
	  //       $.validator.addMethod("emailReceipt", function (value, element) {
	        
		 //        if ($(element).attr("data-wt") == "valid") {
		 //          return true;
		 //        } else {
		 //          return false;
		 //        }
	  //       });
			
			$frmCreateClient.validate({
				rules: {
					"c_phone": {
						phoneNumber: true,
						
						remote: "index.php?controller=pjAdminClients&action=pjActionCheckPhoneNumber"
					}
					// "mobile_delivery_info": {
					// 	mobileDelivery: true,
					// },
					// "email_receipt": {
					// 	emailReceipt: true,
					// }
				},
				messages: {
					"c_phone": {
						phoneNumber: "Mobile number is invalid",
						remote: myLabel.phoneno_exists
					}
					// "mobile_delivery_info": {
			  //           mobileDelivery: "Please select any one of the delivery info",
			  //       },
			  //       "email_receipt": {
			  //       	emailReceipt: "Please select any one of the delivery info",
			  //       }
				}
			});
		}
		if ($frmUpdateClient.length > 0) {
			$.validator.addMethod("phoneNumber", function (value, element) {
		        if ($(element).attr("data-wt") == "valid") {
		          return true;
		        } else {
		          return false;
		        }
		    });
	  //       $.validator.addMethod("emailReceipt", function (value, element) {
	        
		 //        if ($(element).attr("data-wt") == "valid") {
		 //          return true;
		 //        } else {
		 //          return false;
		 //        }
	  //       });
			$frmUpdateClient.validate({
				rules: {
					"c_phone": {
						phoneNumber: true,
						
						remote: "index.php?controller=pjAdminClients&action=pjActionCheckPhoneNumber&id=" + $frmUpdateClient.find("input[name='id']").val()
					}
					// "mobile_delivery_info": {
					// 	mobileDelivery: true,
					// },
					// "email_receipt": {
					// 	emailReceipt: true,
					// }
				},
				messages: {
					"c_phone": {
                        phoneNumber: "Mobile number is invalid",
						remote: myLabel.phoneno_exists
					}
					// "mobile_delivery_info": {
			  //           mobileDelivery: "Please select any one of the delivery info",
			  //       },
			  //       "email_receipt": {
			  //       	emailReceipt: "Please select any one of the delivery info",
			  //       }
				}
			});
		}
		function formatOrders(str, obj) 
		{
			if(parseInt(str, 10) > 0)
			{
				return '<a href="index.php?controller=pjAdminOrders&action=pjActionIndex&client_id='+obj.id+'">'+str+'</a>';
			}else{
				return str;
			}
		}
		function validatePhoneNumber(data) {
      
	      var ph = data;
	      ph = $.trim(ph);
	      var len = ph.toString().length;
	      if (len == 11 && isNaN(ph) == false) {
	        $("[name=c_phone]").attr("data-wt","valid");
	      } else {
	        $("[name=c_phone]").attr("data-wt","invalid");
	      }
	      //console.log(len);
	    }
	    function validateEmail(email){
      
	      var id = email;
	      id = $.trim(id);
	      var filter = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

	      if (filter.test(id)) {
	        $("[name=email_offer]").removeAttr("disabled");
	      } else {
	        $("[name=email_offer]").attr("disabled", true);
	      }
	      if (email == '') {
	        $("[name=email_offer]").attr("disabled", true);
	      }
	    }
		if ($("#grid").length > 0 && datagrid) 
		{
			var $grid = $("#grid").datagrid({
				buttons: [{type: "edit", url: "index.php?controller=pjAdminClients&action=pjActionUpdate&id={:id}"},
				          {type: "delete", url: "index.php?controller=pjAdminClients&action=pjActionDeleteClient&id={:id}"},
						  {type: "deleteGDPR", url: "index.php?controller=pjAdminClients&action=pjActionDeleteGdprClient&id={:id}"}
				          ],
				columns: [{text: myLabel.firstname, type: "text", sortable: true, editable: false , editableWidth: 190},
				          {text: myLabel.surname, type: "text", sortable: true, editable: false , editableWidth: 190},
				          {text: myLabel.email, type: "text", sortable: true, editable: false , editableWidth: 190},
				          {text: myLabel.phone_no, type: "text", sortable: true, editable: false , editableWidth: 190},
				          {text: myLabel.c_postcode, type: "text", sortable: true, editable: false , editableWidth: 190},
				          {text: myLabel.orders, type: "text", sortable: true, editable: false , align: "center", renderer: formatOrders},
				          {text: myLabel.status, type: "toggle", sortable: true, editable: true, positiveLabel: myLabel.active, positiveValue: "T", negativeLabel: myLabel.inactive, negativeValue: "F"},
				          ],
				dataUrl: "index.php?controller=pjAdminClients&action=pjActionGetClient",
				dataType: "json",
				fields: ['c_name','c_surname', 'c_email','phone','c_postcode', 'cnt_orders', 'status'],
				paginator: {
					actions: [
					   {text: myLabel.delete_selected, url: "index.php?controller=pjAdminClients&action=pjActionDeleteClientBulk", render: true, confirmation: myLabel.delete_confirmation},
					   {text: myLabel.revert_status, url: "index.php?controller=pjAdminClients&action=pjActionStatusClient", render: true},
					   {text: myLabel.exported, url: "index.php?controller=pjAdminClients&action=pjActionExportClient", ajax: false}
					],
					gotoPage: true,
					paginate: true,
					total: true,
					rowCount: true
				},
				saveUrl: "index.php?controller=pjAdminClients&action=pjActionSaveClient&id={:id}",
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
			$grid.datagrid("load", "index.php?controller=pjAdminClients&action=pjActionGetClient", "c_name", "ASC", content.page, content.rowCount);
			
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
			$grid.datagrid("load", "index.php?controller=pjAdminClients&action=pjActionGetClient", "c_name", "ASC", content.page, content.rowCount);
			
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
			$grid.datagrid("load", "index.php?controller=pjAdminClients&action=pjActionGetClient", "id", "ASC", content.page, content.rowCount);
			return false;
		}).on("click", "#mobile_delivery_info", function() {
			
            if($(this).prop("checked") == false) {
            	
            	var $email = $("#email_receipt").prop("checked");
		        if (!$email) {
		          $(this).attr("data-wt","invalid");
		          $(this).parent().addClass("has-error");
		          $(this).siblings("label").css("color", "#ed5565");
		        }
            }
	        
	      }).on("click", "#email_receipt", function() {
	      	if($(this).prop("checked") == false) {
		        var $mobile = $("#mobile_delivery_info").prop("checked");
		        if (!$mobile) {
		          $(this).attr("data-wt","invalid");
		          $(this).parent().addClass("has-error");
		          $(this).siblings("label").css("color", "#ed5565");
		        }
	        }
	      }).on("click", "#mobile_delivery_info", function() {
	      	if($(this).prop("checked") == true) {
	      		$("#mobile_delivery_info-error").css("display","none");
		        var $has_thisErr = $(this).parent().hasClass("has-error");
		        var $has_emailErr = $("#email_receipt").parent().hasClass("has-error");
		        if ($has_emailErr) {
		          $("input[name='email_receipt']").attr("data-wt","valid");
		          $("#email_receipt").parent().removeClass("has-error");
		          $("#email_receipt").siblings("label").css("color", "#676A6C");
		        }
		        if ($has_thisErr) {
		          $(this).attr("data-wt","valid");
		          $("#mobile_delivery_info").parent().removeClass("has-error");
		          $(this).siblings("label").css("color", "#676A6C");
		        }
	        }
	      }).on("click", "#email_receipt", function() {
	      	if($(this).prop("checked") == true) {
	      		$("#email_receipt-error").css("display","none");
		        var $has_thisErr = $(this).parent().hasClass("has-error");
		        var $has_mobileErr = $("#mobile_delivery_info").parent().hasClass("has-error");
		        if ($has_mobileErr) {
		          $("input[name='mobile_delivery_info']").attr("data-wt","valid");
		          $("#mobile_delivery_info").parent().removeClass("has-error");
		          $("#mobile_delivery_info").siblings("label").css("color", "#676A6C");
		        }
		        if ($has_thisErr) {
		          $(this).attr("data-wt","valid");
		          $(this).parent().removeClass("has-error");
		          $(this).siblings("label").css("color", "#676A6C");
		        }
	        }
	      }).on("input", "[name=c_phone]", function() {
	      	
                validatePhoneNumber($(this).val());
    
	      }).on("input", "[name=c_email]", function() {
	      	    
                validateEmail($(this).val());
    
	      });
	});
})(jQuery_1_8_2);