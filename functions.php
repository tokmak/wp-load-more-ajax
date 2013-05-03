<?php
// enqueue_scripts: make sure to include ajaxurl, so we know where to send the post request
function dt_add_main_js(){
  
  wp_register_script( 'main-js', get_template_directory_uri() . '/js/main.js', array( 'jquery' ), '1.0', false );
  wp_enqueue_script( 'main-js' );
  wp_localize_script( 'main-js', 'headJS', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ), 'templateurl' => get_template_directory_uri(), 'posts_per_page' => get_option('posts_per_page') ) );
  
}
add_action( 'wp_enqueue_scripts', 'dt_add_main_js', 90);


add_action( "wp_ajax_load_more", "load_more_func" ); // when logged in
add_action( "wp_ajax_nopriv_load_more", "load_more_func" );//when logged out 
//function return new posts based on offset and posts per page value
function load_more_func() {
  //verifying nonce here
    if ( !wp_verify_nonce( $_REQUEST['nonce'], "load_posts" ) ) {
      exit("No naughty business please");
    }     
  $offset = isset($_REQUEST['offset'])?intval($_REQUEST['offset']):0;
  $posts_per_page = isset($_REQUEST['posts_per_page'])?intval($_REQUEST['posts_per_page']):10;
  //optional, if post type is not defined use regular post type
  $post_type = isset($_REQUEST['post_type'])?$_REQUEST['post_type']:'post';
  
  
  ob_start(); // buffer output instead of echoing it
  $args = array(
	  			'post_type'=>$post_type,
				'offset' => $offset,
				'posts_per_page' => $posts_per_page,
				'orderby' => 'date',
				'order' => 'DESC'
					)
  $posts_query = new WP_Query( $args );
  
  
  if ($posts_query->have_posts()) {
	  //if we have posts:
		  $result['have_posts'] = true; //set result array item "have_posts" to true
		  
		  while ( $posts_query->have_posts() ) : $posts_query->the_post(); ?>
			<article id="post-<?php the_ID(); ?>" class="single-article" >
				<?php //here goes your post content:?>
				<h1><?php the_title(); ?></h1>
				<?php the_content(); ?>
			</article>
			<?php endwhile;
		$result['html'] = ob_get_clean(); // put alloutput data into "html" item
  } else {
	  //no posts found
	  $result['have_posts'] = false; // return that there is no posts found
  } 
        if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            $result = json_encode($result); // incode resul arrat into json
            echo $result; // by echo we return JSON feed on POST request sent via AJAX
        }
        else { 
            header("Location: ".$_SERVER["HTTP_REFERER"]);
        }
  die();
}
?>
