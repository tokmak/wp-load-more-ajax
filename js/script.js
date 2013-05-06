(function($){

//javasctipt 
//load more click function: improved to prevent double click and fire funciton only after content has been loaded (good for slow internet connection)
$('.load_more:not(.loading)').live('click',function(e){
	e.preventDefault();
	var $load_more_btn = $(this);
	var post_type = 'post'; // this is optional and can be set from anywhere, stored in mockup etc...
	var offset = $('#posts_list .single-article').length;
	var nonce = $load_more_btn.attr('data-nonce');
	$.ajax({
		        	type : "post",
		        	context: this,
		         	dataType : "json",
		         	url : headJS.ajaxurl,
					data : {action: "load_more", offset:offset, nonce:nonce, post_type:post_type, posts_per_page:headJS.posts_per_page},
		         	beforeSend: function(data) {
						// here u can do some loading animation...
						$load_more_btn.addClass('loading').html('Loading...');// good for styling and also to prevent ajax calls before content is loaded by adding loading class
		         	},
		         	success: function(response) {
						if (response['have_posts'] == 1){//if have posts:
							$load_more_btn.removeClass('loading').html('Load More');
							var $newElems = $(response['html'].replace(/(\r\n|\n|\r)/gm, ''));// here removing extra breaklines and spaces
							$('#posts_list').append($newElems);
						} else {
							//end of posts (no posts found)
							$load_more_btn.removeClass('loading').addClass('end_of_posts').html('<span>End of posts</span>'); // change buttom styles if no more posts
						}
		         	}
		      	});
});

});
