Ooge = {
	base_url: function(uri) {
		return 'http://i.ooge.uk/' + uri;
	}
}
Ooge.global = {

	handlers: {
		openUploadsModal: function() {
			$('.modalBack').fadeIn('normal');
			$('.modal').slideDown('normal');
		},
		closeUploadsModal: function() {
			$('.modalBack').fadeOut('normal');
			$('.modal').slideUp('normal');
		}
	},

	init: function() {
		$('#upload_toggle').on('click', Ooge.global.handlers.openUploadsModal);
		$('.modal-close').on('click', Ooge.global.handlers.closeUploadsModal);
		$('#cancel-file').on('click', Ooge.global.handlers.closeUploadsModal);
		$('#image-upload').on('submit', function(e){
			e.preventDefault();
			$('#error').html('');
			$('#send-file').html('<i class="fa fa-circle-o-notch fa-spin"></i>&nbsp;Uploading...');
			$('#send-file').attr('disabled','disabled');
			var data = new FormData(this);
			data.append('action', 'upload');
			$.ajax({
				url: Ooge.base_url('ajax/file_handler.php'),
				type: 'POST',
				data: data,
				contentType: false,
				cache: false,
				processData: false,
				success: function(data) {
					if(data.success){
						$('#send-file').html('<i class="fa fa-check"></i>&nbsp;Uploaded');
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
		Ooge.global.loadImages();
	},
	loadImages: function() {
		$.ajax({
			url: Ooge.base_url('ajax/file_handler.php'),
			type: 'POST',
			data: { action: "load"},
			success: function(data) {
				if(data.success){
					for(var i = 0; i < data.files.length; i++) {
						$('#images-container').append('<div class="image"><a href="' + data.files[i] + '"><img src="' + data.files[i] + '" alt="image" ></a></div>');
					}
				}
			},
			error: function() {

			}
		});
	}
}
Ooge.global.init();
