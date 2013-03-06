<?php
class Apollo13_Widget_Recent_Posts extends WP_Widget {

	function __construct() {
		$widget_ops = array('classname' => 'widget_recent_posts', 'description' => __( "The most recent posts on your site", TPL_SLUG ) );
		parent::__construct('recent-posts', __( 'Recent Posts'), $widget_ops);
		$this->alt_option_name = 'widget_recent_entries';

		add_action( 'save_post', array(&$this, 'flush_widget_cache') );
		add_action( 'deleted_post', array(&$this, 'flush_widget_cache') );
		add_action( 'switch_theme', array(&$this, 'flush_widget_cache') );
	}

	function widget($args, $instance) {
		global $apollo13;
		$cache = wp_cache_get('widget_recent_entries', 'widget');

		if ( !is_array($cache) )
			$cache = array();

		if ( isset($cache[$args['widget_id']]) ) {
			echo $cache[$args['widget_id']];
			return;
		}

		ob_start();
		extract($args);

		$title = apply_filters('widget_title', empty($instance['title']) ? __('Recent Posts', TPL_SLUG ) : $instance['title'], $instance, $this->id_base);
		if ( ! $number = absint( $instance['number'] ) )
 			$number = 10;

		$r = new WP_Query(array('posts_per_page' => $number, 'no_found_rows' => true, 'post_status' => 'publish', 'ignore_sticky_posts' => true));
		if ($r->have_posts()) :
?>
		<?php echo $before_widget; ?>
		<?php if ( $title ) echo $before_title . $title . $after_title; ?>
			<?php  while ($r->have_posts()) : $r->the_post(); ?>
			<?php $page_title = get_the_title(); ?>
			<div class="item">
				<?php $apollo13->top_image_video( get_the_ID(), 200, $apollo13->get_option( 'thumbs_options', 'sidebar_thumb' ) ); ?>
				<a class="post-title" href="<?php the_permalink() ?>" title="<?php echo esc_attr($page_title); ?>"><?php echo $page_title; ?></a>
				<div class="post-info">
					<?php $apollo13->posted_on('d.m.Y'); ?><span>/</span>
					<?php echo ' <a class="comments" href="' . get_comments_link() . '" title="">' . get_comments_number() . ' ' . __( 'comment(s)', TPL_SLUG ). '</a>'; ?>
				</div>
			</div>
			<?php endwhile; ?>
		<?php echo $after_widget; ?>
<?php
		// Reset the global $the_post as this query will have stomped on it
		wp_reset_postdata();

		endif;

		$cache[$args['widget_id']] = ob_get_flush();
		wp_cache_set('widget_recent_entries', $cache, 'widget');
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['number'] = (int) $new_instance['number'];
		$this->flush_widget_cache();

		$alloptions = wp_cache_get( 'alloptions', 'options' );
		if ( isset($alloptions['widget_recent_entries']) )
			delete_option('widget_recent_entries');

		return $instance;
	}

	function flush_widget_cache() {
		wp_cache_delete('widget_recent_entries', 'widget');
	}

	function form( $instance ) {
		$title = isset($instance['title']) ? esc_attr($instance['title']) : '';
		$number = isset($instance['number']) ? absint($instance['number']) : 5;
?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', TPL_SLUG); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>

		<p><label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number of posts to show:', TPL_SLUG); ?></label>
		<input id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $number; ?>" size="3" /></p>
<?php
	}
}
unregister_widget('WP_Widget_Recent_Posts');
register_widget('Apollo13_Widget_Recent_Posts');


class Apollo13_Widget_Popular_Posts extends WP_Widget {

	function __construct() {
		$widget_ops = array('classname' => 'widget_popular_entries', 'description' => __( "The most popular posts on your site", TPL_SLUG ) );
		parent::__construct('popular-posts', __( 'Popular Posts'), $widget_ops);
		$this->alt_option_name = 'widget_popular_entries';

		add_action( 'save_post', array(&$this, 'flush_widget_cache') );
		add_action( 'deleted_post', array(&$this, 'flush_widget_cache') );
		add_action( 'switch_theme', array(&$this, 'flush_widget_cache') );
	}

	function widget($args, $instance) {
		global $apollo13;
		$cache = wp_cache_get('widget_popular_entries', 'widget');

		if ( !is_array($cache) )
			$cache = array();

		if ( isset($cache[$args['widget_id']]) ) {
			echo $cache[$args['widget_id']];
			return;
		}

		ob_start();
		extract($args);

		$title = apply_filters('widget_title', empty($instance['title']) ? __('Popular Posts', TPL_SLUG ) : $instance['title'], $instance, $this->id_base);
		if ( ! $number = absint( $instance['number'] ) )
 			$number = 10;

		$r = new WP_Query(array('posts_per_page' => $number, 'no_found_rows' => true, 'orderby'=> 'comment_count', 'post_status' => 'publish', 'ignore_sticky_posts' => true));
		if ($r->have_posts()) :
?>
		<?php echo $before_widget; ?>
		<?php if ( $title ) echo $before_title . $title . $after_title; ?>
			<?php  while ($r->have_posts()) : $r->the_post(); ?>
			<?php $page_title = get_the_title(); ?>
			<div class="item">
				<?php $apollo13->top_image_video( get_the_ID(), 200, $apollo13->get_option( 'thumbs_options', 'sidebar_thumb' ) ); ?>
				<a class="post-title" href="<?php the_permalink() ?>" title="<?php echo esc_attr($page_title); ?>"><?php echo $page_title; ?></a>
				<div class="post-info">
					<?php $apollo13->posted_on('d.m.Y'); ?><span>/</span>
					<?php echo ' <a class="comments" href="' . get_comments_link() . '" title="">' . get_comments_number() . ' ' . __( 'comment(s)', TPL_SLUG ). '</a>'; ?>
				</div>
			</div>
			<?php endwhile; ?>
		<?php echo $after_widget; ?>
<?php
		// Reset the global $the_post as this query will have stomped on it
		wp_reset_postdata();

		endif;

		$cache[$args['widget_id']] = ob_get_flush();
		wp_cache_set('widget_popular_entries', $cache, 'widget');
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['number'] = (int) $new_instance['number'];
		$this->flush_widget_cache();

		$alloptions = wp_cache_get( 'alloptions', 'options' );
		if ( isset($alloptions['widget_popular_entries']) )
			delete_option('widget_popular_entries');

		return $instance;
	}

	function flush_widget_cache() {
		wp_cache_delete('widget_popular_entries', 'widget');
	}

	function form( $instance ) {
		$title = isset($instance['title']) ? esc_attr($instance['title']) : '';
		$number = isset($instance['number']) ? absint($instance['number']) : 5;
?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', TPL_SLUG); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>

		<p><label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number of posts to show:', TPL_SLUG); ?></label>
		<input id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $number; ?>" size="3" /></p>
<?php
	}
}
register_widget('Apollo13_Widget_Popular_Posts');


class Apollo13_Widget_Related_Posts extends WP_Widget {

	function __construct() {
		$widget_ops = array('classname' => 'widget_related_entries', 'description' => __( "Related posts to current post", TPL_SLUG ) );
		parent::__construct('related-posts', __( 'Related Posts' ), $widget_ops);
		$this->alt_option_name = 'widget_related_entries';

		add_action( 'save_post', array(&$this, 'flush_widget_cache') );
		add_action( 'deleted_post', array(&$this, 'flush_widget_cache') );
		add_action( 'switch_theme', array(&$this, 'flush_widget_cache') );
	}

	function widget($args, $instance) {
		global $apollo13;
		$cache = wp_cache_get('widget_related_entries', 'widget');

		if ( !is_array($cache) )
			$cache = array();

		if ( isset($cache[$args['widget_id']]) ) {
			echo $cache[$args['widget_id']];
			return;
		}

		ob_start();
		extract($args);

		$title = apply_filters('widget_title', empty($instance['title']) ? __('Related Posts', TPL_SLUG ) : $instance['title'], $instance, $this->id_base);
		if ( ! $number = absint( $instance['number'] ) )
 			$number = 10;
 			
		global $post;
		
		$tags = wp_get_post_categories($post->ID);
		$tagIDs = array();
		if ( count($tags) ) {

			$r = new WP_Query(array('category__in' => $tags,'post__not_in' => array($post->ID), 'posts_per_page' => $number, 'no_found_rows' => true, 'post_status' => 'publish', 'ignore_sticky_posts' => true));
			if ($r->have_posts()) :
?>
			<?php echo $before_widget; ?>
			<?php if ( $title ) echo $before_title . $title . $after_title; ?>
			<?php  while ($r->have_posts()) : $r->the_post(); ?>
			<?php $page_title = get_the_title(); ?>
			<div class="item">
				<?php $apollo13->top_image_video( get_the_ID(), 200, $apollo13->get_option( 'thumbs_options', 'sidebar_thumb' ) ); ?>
				<a class="post-title" href="<?php the_permalink() ?>" title="<?php echo esc_attr($page_title); ?>"><?php echo $page_title; ?></a>
				<div class="post-info">
					<?php $apollo13->posted_on('d.m.Y'); ?><span>/</span>
					<?php echo ' <a class="comments" href="' . get_comments_link() . '" title="">' . get_comments_number() . ' ' . __( 'comment(s)', TPL_SLUG ). '</a>'; ?>
				</div>
			</div>
			<?php endwhile; ?>
			<?php echo $after_widget; ?>
<?php
		// Reset the global $the_post as this query will have stomped on it
		wp_reset_postdata();

			endif;
	
			$cache[$args['widget_id']] = ob_get_flush();
			wp_cache_set('widget_related_entries', $cache, 'widget');
		}
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['number'] = (int) $new_instance['number'];
		$this->flush_widget_cache();

		$alloptions = wp_cache_get( 'alloptions', 'options' );
		if ( isset($alloptions['widget_related_entries']) )
			delete_option('widget_related_entries');

		return $instance;
	}

	function flush_widget_cache() {
		wp_cache_delete('widget_related_entries', 'widget');
	}

	function form( $instance ) {
		$title = isset($instance['title']) ? esc_attr($instance['title']) : '';
		$number = isset($instance['number']) ? absint($instance['number']) : 5;
?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', TPL_SLUG); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>

		<p><label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number of posts to show:', TPL_SLUG); ?></label>
		<input id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $number; ?>" size="3" /></p>
<?php
	}
}
register_widget('Apollo13_Widget_Related_Posts');


class Apollo13_Widget_Recent_Comments extends WP_Widget {

	function __construct() {
		$widget_ops = array('classname' => 'widget_recent_comments', 'description' => __( 'The most recent comments', TPL_SLUG ) );
		parent::__construct('recent-comments', __( 'Recent Comments', TPL_SLUG), $widget_ops);
		$this->alt_option_name = 'widget_recent_comments';

		add_action( 'comment_post', array(&$this, 'flush_widget_cache') );
		add_action( 'transition_comment_status', array(&$this, 'flush_widget_cache') );
	}

	function flush_widget_cache() {
		wp_cache_delete('widget_recent_comments', 'widget');
	}

	function widget( $args, $instance ) {
		global $comments, $comment, $apollo13;

		$cache = wp_cache_get('widget_recent_comments', 'widget');

		if ( ! is_array( $cache ) )
			$cache = array();

		if ( isset( $cache[$args['widget_id']] ) ) {
			echo $cache[$args['widget_id']];
			return;
		}

 		extract($args, EXTR_SKIP);
 		$output = '';
 		$title = apply_filters('widget_title', empty($instance['title']) ? __('Recent Comments', TPL_SLUG) : $instance['title']);

		if ( ! $number = absint( $instance['number'] ) )
 			$number = 5;

		$comments = get_comments( array( 'number' => $number, 'status' => 'approve', 'post_status' => 'publish' ) );
		$output .= $before_widget;
		if ( $title )
			$output .= $before_title . $title . $after_title;
			

		if ( $comments ) {
			foreach ( (array) $comments as $comment) {
				$output .= '<div class="widget-cloud">';
				$output .= '<a class="title" href="' . esc_url( get_comment_link($comment->comment_ID) ) . '">' . get_the_title($comment->comment_post_ID) . '</a>'; 
				$output .= '<span class="author">' . get_comment_author() . ':</span> ';
				$output .= '<a class="content" href="' . esc_url( get_comment_link($comment->comment_ID) ) . '">' . $apollo13->get_comment_excerpt( $comment->comment_ID, 10 ) . '</a>';
				$output .= '<span class="arrow"></span></div>';
			}
 		}
 		
		$output .= $after_widget;

		echo $output;
		$cache[$args['widget_id']] = $output;
		wp_cache_set('widget_recent_comments', $cache, 'widget');
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['number'] = absint( $new_instance['number'] );
		$this->flush_widget_cache();

		$alloptions = wp_cache_get( 'alloptions', 'options' );
		if ( isset($alloptions['widget_recent_comments']) )
			delete_option('widget_recent_comments');

		return $instance;
	}

	function form( $instance ) {
		$title = isset($instance['title']) ? esc_attr($instance['title']) : '';
		$number = isset($instance['number']) ? absint($instance['number']) : 5;
?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', TPL_SLUG); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>

		<p><label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number of comments to show:', TPL_SLUG); ?></label>
		<input id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $number; ?>" size="3" /></p>
<?php
	}
}
register_widget('Apollo13_Widget_Recent_Comments');




class Apollo13_Widget_Twitter extends WP_Widget {

	function __construct() {
		$widget_ops = array('classname' => 'apollo13_widget_twitter', 'description' => __( 'Display twitter feeds', TPL_SLUG ) );
		$this->WP_Widget('apollo13_twitter', __( TPL_NAME . '  Twitter', TPL_SLUG ), $widget_ops);
	}

	function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters('widget_title', empty($instance['title']) ? __('Twitter', TPL_SLUG ) : $instance['title'], $instance, $this->id_base);
		$title .=  '<span class="tweet-ico"></span>';
		$username = $instance['username'];
		
		$count = (int)$instance['count'];
		if($count < 1){
			$count = 1;
		}
		
		if ( ! empty( $username ) ) {
			echo $before_widget;
			if ( $title )
				echo $before_title . $title . $after_title;
				
		$uniq = uniqid(rand());
		?>
		
		
		<script type="text/javascript">
				jQuery(document).ready(function($) {
					 jQuery("#air-tweet<?php echo $uniq;?>").tweet({
						username: ["<?php echo $username;?>"],
						count: <?php echo $count;?>,
						seconds_ago_text: "<?php _e( 'about %d seconds ago', TPL_SLUG );?>",
						a_minutes_ago_text: "<?php _e( 'about a minute ago', TPL_SLUG );?>",
						minutes_ago_text: "<?php _e( 'about %d minutes ago', TPL_SLUG );?>",
						a_hours_ago_text: "<?php _e( 'about an hour ago', TPL_SLUG );?>",
						hours_ago_text: "<?php _e( 'about %d hours ago', TPL_SLUG );?>",
						a_day_ago_text: "<?php _e( 'about a day ago', TPL_SLUG );?>",
						days_ago_text: "<?php _e( 'about %d days ago', TPL_SLUG );?>",
						view_text: "<?php _e( 'view tweet on twitter', TPL_SLUG );?>",
						template: "{join}{text} {time}"
					 });
				});
		</script>
		<div id="air-tweet<?php echo $uniq;?>">
			
		</div>
		<div class="clearboth"></div>
		<?php
			echo $after_widget;
		}
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['username'] = strip_tags($new_instance['username']);
		$instance['avatar_size'] = $new_instance['avatar_size']?(int) $new_instance['avatar_size']:'';
		$instance['count'] = (int) $new_instance['count'];
		$instance['query'] = strip_tags($new_instance['query']);
		return $instance;
	}

	function form( $instance ) {
		$title = isset($instance['title']) ? esc_attr($instance['title']) : '';
		$username = isset($instance['username']) ? esc_attr($instance['username']) : '';
		$avatar_size = isset($instance['avatar_size']) ? absint($instance['avatar_size']) : '';
		$query = isset($instance['query']) ? esc_attr($instance['query']) : '';
		$count = isset($instance['count']) ? absint($instance['count']) : 3;
		$display = isset( $instance['display'] ) ? $instance['display'] : 'latest';
?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e( 'Title:', TPL_SLUG ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>

		<p><label for="<?php echo $this->get_field_id('username'); ?>"><?php _e( 'Username:', TPL_SLUG ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('username'); ?>" name="<?php echo $this->get_field_name('username'); ?>" type="text" value="<?php echo $username; ?>" /></p>
		
		
		<p><label for="<?php echo $this->get_field_id('count'); ?>"><?php _e( 'Number of tweets', TPL_SLUG ); ?></label>
		<input id="<?php echo $this->get_field_id('count'); ?>" name="<?php echo $this->get_field_name('count'); ?>" type="text" value="<?php echo $count; ?>" size="3" /></p>
		
<?php
	}
}
register_widget('Apollo13_Widget_Twitter');


class Apollo13_Widget_Recent_Projects extends WP_Widget {

	function __construct() {
		$widget_ops = array('classname' => 'widget_recent_projects', 'description' => __( "Your most recent projects", TPL_SLUG ) );
		parent::__construct('recent-projects', __( 'Recent Projects'), $widget_ops);
		$this->alt_option_name = 'widget_recent_projects';

		add_action( 'save_post', array(&$this, 'flush_widget_cache') );
		add_action( 'deleted_post', array(&$this, 'flush_widget_cache') );
		add_action( 'switch_theme', array(&$this, 'flush_widget_cache') );
	}

	function widget($args, $instance) {
		global $apollo13;
		$cache = wp_cache_get('widget_recent_projects', 'widget');

		if ( !is_array($cache) )
			$cache = array();

		if ( isset($cache[$args['widget_id']]) ) {
			echo $cache[$args['widget_id']];
			return;
		}

		ob_start();
		extract($args);

		$title = apply_filters('widget_title', empty($instance['title']) ? __('Recent Projects', TPL_SLUG ) : $instance['title'], $instance, $this->id_base);
		if ( ! $number = absint( $instance['number'] ) )
 			$number = 10;

		$r = new WP_Query(array(
			'posts_per_page' => $number,
			'no_found_rows' => true,
			'post_type' => PORTFOLIO_POST_TYPE,
			'post_status' => 'publish',
			'ignore_sticky_posts' => true,
			'orderby' => 'date'
		));
		if ($r->have_posts()) :
?>
		<?php echo $before_widget; ?>
		<?php if ( $title ) echo $before_title . $title . $after_title; ?>
			<?php  while ($r->have_posts()) : $r->the_post(); ?>
			<?php $page_title = get_the_title(); ?>
			<div class="item">
				<?php $apollo13->portfolio_top_image_video( 200, $apollo13->get_option( 'thumbs_options', 'sidebar_thumb' ), true ); ?>
				<a class="post-title" href="<?php the_permalink() ?>" title="<?php echo esc_attr($page_title); ?>"><?php echo $page_title; ?></a>
			</div>
			<?php endwhile; ?>
		<?php echo $after_widget; ?>
<?php
		// Reset the global $the_post as this query will have stomped on it
		wp_reset_postdata();

		endif;

		$cache[$args['widget_id']] = ob_get_flush();
		wp_cache_set('widget_recent_projects', $cache, 'widget');
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['number'] = (int) $new_instance['number'];
		$this->flush_widget_cache();

		$alloptions = wp_cache_get( 'alloptions', 'options' );
		if ( isset($alloptions['widget_recent_projects']) )
			delete_option('widget_recent_projects');

		return $instance;
	}

	function flush_widget_cache() {
		wp_cache_delete('widget_recent_projects', 'widget');
	}

	function form( $instance ) {
		$title = isset($instance['title']) ? esc_attr($instance['title']) : '';
		$number = isset($instance['number']) ? absint($instance['number']) : 5;
?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', TPL_SLUG); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>

		<p><label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number of posts to show:', TPL_SLUG); ?></label>
		<input id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $number; ?>" size="3" /></p>
<?php
	}
}
register_widget('Apollo13_Widget_Recent_Projects');


class Apollo13_Widget_Related_Projects extends WP_Widget {

	function __construct() {
		$widget_ops = array('classname' => 'widget_related_projects', 'description' => __( "Related projects", TPL_SLUG ) );
		parent::__construct('related-projects', __( 'Related Projects'), $widget_ops);
		$this->alt_option_name = 'widget_related_projects';

		add_action( 'save_post', array(&$this, 'flush_widget_cache') );
		add_action( 'deleted_post', array(&$this, 'flush_widget_cache') );
		add_action( 'switch_theme', array(&$this, 'flush_widget_cache') );
	}

	function widget($args, $instance) {
		global $apollo13;
		$cache = wp_cache_get('widget_related_projects', 'widget');

		if ( !is_array($cache) )
			$cache = array();

		if ( isset($cache[$args['widget_id']]) ) {
			echo $cache[$args['widget_id']];
			return;
		}

		ob_start();
		extract($args);

		$title = apply_filters('widget_title', empty($instance['title']) ? __('Related Projects', TPL_SLUG ) : $instance['title'], $instance, $this->id_base);
		if ( ! $number = absint( $instance['number'] ) )
 			$number = 10;
		
 		global $post;	
 			
		$tags = wp_get_post_terms($post->ID, 'skills', array("fields" => "ids"));
		if ( count($tags) ) {
		
			$r = new WP_Query(array(
				'posts_per_page' => $number,
				'no_found_rows' => true,
				'post__not_in' => array($post->ID),
				'post_type' => PORTFOLIO_POST_TYPE,
				'post_status' => 'publish',
				'ignore_sticky_posts' => true,
				'tax_query' => array(
					array(
						'taxonomy' => 'skills',
						'field' => 'id',
						'terms' => $tags,
						'operator' => 'IN'
					)
				)
			));
			if ($r->have_posts()) : ?>
			<?php echo $before_widget; ?>
			<?php if ( $title ) echo $before_title . $title . $after_title; ?>
				<?php  while ($r->have_posts()) : $r->the_post(); ?>
				<?php $page_title = get_the_title(); ?>
				<div class="item">
					<?php $apollo13->portfolio_top_image_video( 200, $apollo13->get_option( 'thumbs_options', 'sidebar_thumb' ), true  ); ?>
					<a class="post-title" href="<?php the_permalink() ?>" title="<?php echo esc_attr($page_title); ?>"><?php echo $page_title; ?></a>
				</div>
				<?php endwhile; ?>
			<?php echo $after_widget; ?>
<?php
			// Reset the global $the_post as this query will have stomped on it
			wp_reset_postdata();
	
			endif;
	
			$cache[$args['widget_id']] = ob_get_flush();
			wp_cache_set('widget_related_projects', $cache, 'widget');
		}
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['number'] = (int) $new_instance['number'];
		$this->flush_widget_cache();

		$alloptions = wp_cache_get( 'alloptions', 'options' );
		if ( isset($alloptions['widget_related_projects']) )
			delete_option('widget_related_projects');

		return $instance;
	}

	function flush_widget_cache() {
		wp_cache_delete('widget_related_projects', 'widget');
	}

	function form( $instance ) {
		$title = isset($instance['title']) ? esc_attr($instance['title']) : '';
		$number = isset($instance['number']) ? absint($instance['number']) : 5;
?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', TPL_SLUG); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>

		<p><label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number of posts to show:', TPL_SLUG); ?></label>
		<input id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $number; ?>" size="3" /></p>
<?php
	}
}
register_widget('Apollo13_Widget_Related_Projects');


class Apollo13_Widget_Featured_Projects extends WP_Widget {

	function __construct() {
		$widget_ops = array('classname' => 'widget_featured_projects', 'description' => __( "Featured projects", TPL_SLUG ) );
		parent::__construct('featured-projects', __( 'Featured Projects'), $widget_ops);
		$this->alt_option_name = 'widget_featured_projects';

		add_action( 'save_post', array(&$this, 'flush_widget_cache') );
		add_action( 'deleted_post', array(&$this, 'flush_widget_cache') );
		add_action( 'switch_theme', array(&$this, 'flush_widget_cache') );
	}

	function widget($args, $instance) {
		global $apollo13;
		$cache = wp_cache_get('widget_featured_projects', 'widget');

		if ( !is_array($cache) )
			$cache = array();

		if ( isset($cache[$args['widget_id']]) ) {
			echo $cache[$args['widget_id']];
			return;
		}

		ob_start();
		extract($args);

		$title = apply_filters('widget_title', empty($instance['title']) ? __('Featured Projects', TPL_SLUG ) : $instance['title'], $instance, $this->id_base);
		if ( ! $number = absint( $instance['number'] ) )
 			$number = 10;
		
 		global $post;	
		
			$r = new WP_Query(array(
				'posts_per_page' => $number,
				'no_found_rows' => true,
				'post__not_in' => array($post->ID),
				'post_type' => PORTFOLIO_POST_TYPE,
				'post_status' => 'publish',
				'ignore_sticky_posts' => true,
				'meta_key' => '_featured',
				'meta_value' => 'yes',
				'meta_compare' => '='
			));
			if ($r->have_posts()) : ?>
			<?php echo $before_widget; ?>
			<?php if ( $title ) echo $before_title . $title . $after_title; ?>
				<?php  while ($r->have_posts()) : $r->the_post(); ?>
				<?php $page_title = get_the_title(); ?>
				<div class="item">
					<?php $apollo13->portfolio_top_image_video( 200, $apollo13->get_option( 'thumbs_options', 'sidebar_thumb' ), true ); ?>
					<a class="post-title" href="<?php the_permalink() ?>" title="<?php echo esc_attr($page_title); ?>"><?php echo $page_title; ?></a>
				</div>
				<?php endwhile; ?>
			<?php echo $after_widget; ?>
<?php
			// Reset the global $the_post as this query will have stomped on it
			wp_reset_postdata();
	
			endif;
	
			$cache[$args['widget_id']] = ob_get_flush();
			wp_cache_set('widget_featured_projects', $cache, 'widget');
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['number'] = (int) $new_instance['number'];
		$this->flush_widget_cache();

		$alloptions = wp_cache_get( 'alloptions', 'options' );
		if ( isset($alloptions['widget_featured_projects']) )
			delete_option('widget_featured_projects');

		return $instance;
	}

	function flush_widget_cache() {
		wp_cache_delete('widget_featured_projects', 'widget');
	}

	function form( $instance ) {
		$title = isset($instance['title']) ? esc_attr($instance['title']) : '';
		$number = isset($instance['number']) ? absint($instance['number']) : 5;
?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', TPL_SLUG); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>

		<p><label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number of posts to show:', TPL_SLUG); ?></label>
		<input id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $number; ?>" size="3" /></p>
<?php
	}
}
register_widget('Apollo13_Widget_Featured_Projects');
?>