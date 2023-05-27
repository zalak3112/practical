	jQuery(function($){
	$('#filter').submit(function(){
		var filter = $('#filter');
		$.ajax({
			url:filter.attr('action'),
			data:filter.serialize(), // form data
			type:filter.attr('method'), // POST
			beforeSend:function(xhr){
				filter.find('button').text('Processing...'); // changing the button label
			},
			success:function(data){
				filter.find('button').text('Apply filter'); // changing the button label back
				$('#response').html(data); // insert data
			}
		});
		return false;
	});
});

	jQuery(function($) {
    //adding to favorite
    $('body').on('click', '.add-favorite', function() {
        var post_id = $(this).data('post_id');
        $.ajax({
            url : myAjax.ajaxurl,
            type: 'POST',
            data: {
                'action': 'favorite',
                'post_id': post_id,
            },
            success: function(data) {
                $('.fv_' + post_id).html('<i class="fas fa-heart"></i>In favorite');
                $('.num-favorite').html(data);
            },
        });
    });
    
    //deleting from favorite
    // $('body').on('click', '.delete-favorite', function() {
    //     var post_id = $(this).data('post_id');
    //     $.ajax({
    //         url: "/wp-admin/admin-ajax.php",
    //         type: 'POST',
    //         data: {
    //             'action': 'delfavorite',
    //             'post_id': post_id,
    //         },
    //         success: function(data) {
    //             $('.fv_' + post_id).html('Deleted');
    //             $('.num-favorite').html(data);
    //         },
    //     });
    // });
});
