
(function($) {
	$(document).ready(function() {

		// Abstracted into a function rather than CSS to make sure stripes always alternate even after multiple addition/subtractiosn
		function stripe() {
			$('#list-people li').removeClass('striped');
        	$('#list-people li:nth-child(even)').addClass('striped');
		}

		stripe();

		// Add new phone number
		$('#new-number-form').submit(function(e) {
			e.preventDefault();
			$('#errors').empty();

			var errors = [],
				$phone = $('#phone'),
				$fname = $('#fname'),
				$lname = $('#lname'),
				phonePatt = /^(\(?\d{3}[\)-]?)?\s?\d{3}-?\d{4}$/;

			if (!phonePatt.test($phone.val())) {
				$phone.addClass('error');
				errors.push('The phone number is invalid.');
			} else {
				$phone.removeClass('error');
			}

			if ($fname.val() == '') {
				$fname.addClass('error');
				errors.push('The first name is invalid.');
			} else {
				$fname.removeClass('error');
			}

			if ($lname.val() == '') {
				$lname.addClass('error');
				errors.push('The last name is invalid.');
			} else {
				$lname.removeClass('error');
			}

			if (errors.length > 0) {
				$('#errors').append(errors.join('<br>'));

				console.log('does nto proceed to ajax');

			} else {

				var postData = $(this).serializeArray(),
			    	formURL = $(this).attr("action");

			    postData.push({
			    	'name': 'verb',
			    	'value': 'create'
				    }, {
				    'name': 'token',
			    	'value': token
				    }

			    );

			    $.ajax({
			        url : formURL,
			        type: "POST",
			        data : postData,
			        beforeSend: function() {
			        	$('input[type=submit]').val('Adding entry...');
			        },
			        success:function(responseData, textStatus, jqXHR) {
			        	// get initial json array of all phone numbers
						$.ajax({
						  type: "POST",
						  url: 'http://localhost:8888/phonebook/ajax.php',
						  data: { 'token': token, 'verb': 'query' },
						  success: function(responseData, textStatus, jqXHR) {
						  	var items =[];
							$.each( responseData, function( i, item ) {
								items.push( "<li data-id='" + item.id + "'>" + item.lname + ", " + item.fname + " - " + item.phone +"<span class='delete'>Delete</span></li>" );
							});			
							$('#list-people').empty().append(items);
							stripe();
						  },
						  dataType: 'json'
						});
					    $('input[type=text], input[type=tel]').val('');
			        },
			        error: function(jqXHR, textStatus, errorThrown) {
			        	$('#errors').append('There was a database error.<br>');
			        },
			        complete: function() {
			        	$('input[type=submit]').val('Add');
			        }
			    });
			}

		});

		// Delete person
		$('#list-people').on('click','.delete', function() {
			var itemID = $(this).parents('li').data('id');

			$.ajax({
				context: $(this),
		        url : 'http://localhost:8888/phonebook/ajax.php',
		        type: "POST",
		        data : {
		        	'verb': 'delete',
		        	'person': itemID,
		        	'token': token
		        },
		        beforeSend: function() {
		        	$(this).html('Deleting...');
		        },
		        success:function(data, textStatus, jqXHR) {
		        	$(this).parent().addClass('destroy')
		        		.fadeOut()
		        		.remove();
		        	stripe();
		        },
		        error: function(jqXHR, textStatus, errorThrown) {
		        	$('#errors').append('There was a database error.<br>');
		        }
		    });
		});



	});
})(jQuery);