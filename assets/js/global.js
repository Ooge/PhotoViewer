// The global JS file included on every single page

Ooge = { // Base url function returns the base url
	base_url: function(uri) {
		return 'http://i.ooge.uk/' + uri;
	}
}
Ooge.global = {

	handlers: {
		// Handlers for opening and closing the upload modal
		openUploadsModal: function() {
			$('.modalBack').fadeIn('normal');
			$('.modal').slideDown('normal');
		},
		closeUploadsModal: function() {
			$('.modalBack').fadeOut('normal');
			$('.modal').slideUp('normal');
		}
	},
	// Init function ran on page load
	init: function() {
		$('.upload_toggle').on('click', Ooge.global.handlers.openUploadsModal);
		$('.modal-close').on('click', Ooge.global.handlers.closeUploadsModal);
		$('#cancel-file').on('click', Ooge.global.handlers.closeUploadsModal);

		// Here we have the function that is ran when an image is uploaded to the website
		$('#image-upload').on('submit', function(e){
			e.preventDefault();
			$('#error').html('');
			$('#send-file').html('<i class="fa fa-circle-o-notch fa-spin"></i>&nbsp;Uploading...');
			$('#send-file').attr('disabled','disabled');
			// We get the form data as a FormData object
			var data = new FormData(this);
			// We then use AJAX to send the form data to the file_handler/upload function in our AJAX.php file
			$.ajax({
				url: Ooge.base_url('ajax/file_handler/upload'),
				type: 'POST',
				data: data,
				contentType: false,
				cache: false,
				processData: false,
				success: function(data) {
					if(data.success){
						$('#image-file').val('');
						$('#image-title').val('');
						$('#image-desc').val('');
						$('#send-file').html('<i class="fa fa-upload"></i>&nbsp;Upload');
						$('#send-file').removeAttr('disabled');
					} else {
						$('#error').html('There has been an error while uploading. Try again.');
						$('#send-file').html('<i class="fa fa-upload"></i>&nbsp;Upload');
						$('#send-file').removeAttr('disabled');
					}
					console.log(data);
				},
				error: function() {
					$('#error').html('There has been an error while uploading. Try again.');
					$('#send-file').html('<i class="fa fa-upload"></i>&nbsp;Upload');
					$('#send-file').removeAttr('disabled');
				}
			});
		});
	}
}
Ooge.global.init();
