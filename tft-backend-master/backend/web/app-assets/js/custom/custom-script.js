/*================================================================================
	Item Name: Materialize - Material Design Admin Template
	Version: 5.0
	Author: PIXINVENT
	Author URL: https://themeforest.net/user/pixinvent/portfolio
================================================================================

NOTE:
------
PLACE HERE YOUR OWN JS CODES AND IF NEEDED.
WE WILL RELEASE FUTURE UPDATES SO IN ORDER TO NOT OVERWRITE YOUR CUSTOM SCRIPT IT'S BETTER LIKE THIS. */
// var _csrf = $('meta[name=csrf-token]').attr("content");
var _csrf = yii.getCsrfToken();
$(document).on('pjax:success', function() {
	
		$(".dropdown-trigger").dropdown();
		// $("select").formSelect();
		$("select")
		.not(".disabled")
		.select();
   
});

$(document).on('click','.call-action',function(){
	$('#pbody').html('');
	$('#res').html('');

	var method = $(this).data('method');
	var url = $(this).data('url');
	var attributes  = $(this).data('attributes');	
	if(attributes){
		var tr = "";
		attributes.forEach(element => {
			console.log(element)
			var f = element.type=="file"?'<input type="file" name="'+element.name+'" multiple>':'<input type="text" name="'+element.name+'">';
			var ff = '<tr><td><input type="text" value="'+element.name+'" disabled></td><td>'+f+'</td><td><textarea disabled>'+element.description+'</textarea></td></tr>';
			$('#pbody').append(ff);
		});
	}
	attributes 	= 	JSON.stringify(attributes,undefined, 2)
	$('#method').val(method);
	$('#body').val(attributes);
	$('#url').val(url);
});
$(document).on('submit','#postman-form',function(e){	
	$('#res').html('');

	$(this).find('.btn-primary').text('Sending.........')
	e.preventDefault();
	var form = $('#postman-form')[0];
	var data = new FormData(form);
	$.ajax({
		type: "POST",
		enctype: 'multipart/form-data',
		url: baseUrl+'/postman/resp',
		data: data,
		processData: false,
		contentType: false,
		cache: false,
		timeout: 600000,
		success: function (res) {
			$('#postman-form').find('.btn-primary').text('Send')
			document.getElementById('res').innerHTML = res;
		},
		error: function (e) {
			swal({title: 'Error!',html: e.responseText,type: 'error',});
			console.log("ERROR : ", e);
		}
	});

});

$(document).on('change','.select-type',function(){
	var type = $(this).val();
	$('.select-user option').prop('selected', false);
	$(".select-user option[value='']").prop('selected', false);
	$(".select-checkBox:checked").attr('checked',false)
	if(type == ''){
		$('.selectUserDiv').addClass('hide');
	}else{
		$.ajax({
			type: "POST",
			url: baseUrl+'/notification/get-users',
			data: {
				type: type
			},
			success: function (result) {
				result = JSON.parse(result);
				$('.select-user').html(result.html);
				$('.selectUserDiv').removeClass('hide');
				$(".dropdown-trigger").dropdown();
				// $("select").formSelect();
				$("select")
				.not(".disabled")
				.select();
			},
			error: function (exception) {
				swal({title: 'Error!',html: exception,type: 'error',});
			}
		});
		
	}
});

$(document).on('click','.select-checkBox',function(){
	if($(".select-checkBox:checked").length) {
		$('.select-user option').prop('selected', true);
		$(".select-user option[value='']").prop('selected', false);
	} else {
		$('.select-user option').prop('selected', false);
		$(".select-user option[value='']").prop('selected', false);
	}
	
	$(".dropdown-trigger").dropdown();
	// $("select").formSelect();
	$("select")
	.not(".disabled")
	.select();
});

$('.tooltipped').tooltip();

//! block - unblock user
$(document).on('change', '.user-status-checkbox', function(){
	var _val = $(this).val();
	var _user = $(this).attr('data-user');
	if($(this).is(":checked")){
		var _user_block = 0;
	} else {
		var _user_block = 1;
	}
	call({ url: '/user/user-status', params: { 'status': _user_block, 'user': _user }, type: 'POST' }, function(resp) {
		if(resp.status){
			swal("Good job!", resp.message, "success");
		} else {
			swal("Error!", resp.message, "error");
		}
	});
});

//! make paid staus of winning price
$(document).on('click', '.make-paid-price', function(){
	var _id = $(this).attr('data-key');
	swal("Write remark to change status:", {
		content: "input",
	})
	.then((value) => {
		if (value === false) return false;
		if (value === "" || value === null) {
			swal("You need to write something!", "", "error");
			return false;
		} else {
			call({ url: '/winner/payment-status', params: { 'id': _id, 'remark': value }, type: 'POST' }, function(resp) {
				if(resp.status){
					swal({title: "Good job", text: resp.message, type: "success"}).then(function(){ 
						location.reload();
					});
				} else {
					swal("Error!", resp.message, "error");
				}
			});
		}
	})
});
$(document).on('click','.mark-read',function(e){  
	e.preventDefault();
	if($(".grid-view").yiiGridView("getSelectedRows") != ""){ 		
		call({ url: '/mailmanagement/mark-as-read', params:{'mailId': $(".grid-view").yiiGridView("getSelectedRows")}, type: 'POST' }, function(resp) {
			if(resp.status){				
					location.reload();
			} else {
				swal(resp.status, {
					title: 'Oops!',
					icon: "error",
				});
			}
		});
	}else{
		swal({
			title: "Please!",
			text: "Please select atleast one item",
			type: "error",
			timer: 3000
		});
	}
});
//! delete multiple mail
$(document).on('click','.delete-multipe',function(e){  
	e.preventDefault();
	if($(".grid-view").yiiGridView("getSelectedRows") != ""){ 
		swal({
			title: "Are you sure?",
			text: "You will not be able to recover this imaginary file!",
			icon: 'warning',
			dangerMode: true,
			buttons: {
				cancel: 'No, Please!',
				delete: 'Yes, Delete It'
			}
		}).then(function (willDelete) {
			if (willDelete) {
				call({ url: '/mailmanagement/delete-selected', params:{'deleteId': $(".grid-view").yiiGridView("getSelectedRows")}, type: 'POST' }, function(resp) {
					if(resp.status){
						swal(resp.message, {
							title: 'Great!',
							icon: "success",
						}).then(function(){ 
							location.reload();
						});
					} else {
						swal(resp.status, {
							title: 'Oops!',
							icon: "error",
						});
					}
				});
			} else {
				swal("Your emails are safe", {
					title: 'Cancelled',
					icon: "error",
				});
			}
		});
	}else{
		swal({
			title: "Please!",
			text: "Please select atleast one item",
			type: "error",
			timer: 3000
		});
	}
});

//! submit form on file change
$(document).on('change', '.gallery-photos', function(){
	$('.frm-upload-gallery').submit();
});

//popup-gallery
$('.popup-gallery .gallery-image-container').magnificPopup({
	delegate: 'a',
	type: 'image',
	closeOnContentClick: true,
	fixedContentPos: true,
	tLoading: 'Loading image #%curr%...',
	mainClass: 'mfp-img-mobile mfp-no-margins mfp-with-zoom',
	gallery: {
	  enabled: true,
	  navigateByImgClick: true,
	  preload: [0, 1] // Will preload 0 - before current, and 1 after the current image
	},
	image: {
	  verticalFit: true,
	  tError: '<a href="%url%">The image #%curr%</a> could not be loaded.',
	  titleSrc: function(item) {
		return item.el.attr('title');
	  },
	  zoom: {
		enabled: true,
		duration: 300 // don't foget to change the duration also in CSS
	  }
	}
});

//! common call function
var call = function(data, callback) {
    var callTry = function(data, callback) {
        data.params._csrf = _csrf;
        var DATA = data.params;
        var ajxOpts = {
            url: baseUrl + data.url,
            data: DATA,
            dataType: 'json',
            crossDomain: true,
            cache: false,
            type: (typeof data.type != 'undefined' ? data.type : 'Post'),
        };
        $.ajax(ajxOpts).done(function(res) {
            callback(res);
        }).fail(function(r) {
            callback('fail');
        });
    }
    callTry(data, callback);
}

