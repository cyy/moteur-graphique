<?php
/**
 * The template for displaying Comments.
 *
 * The area of the page that contains both current comments
 * and the comment form.  
 *
 */
global $apollo13;
?>
		<div class="comments-area" id="comments">
<?php if ( post_password_required() ) : ?>
			<p class="nopassword"><?php _e( 'This post is password protected. Enter the password to view any comments.', TPL_SLUG ); ?></p>
		</div>
<?php
		/* Stop the rest of comments.php from being processed,
		 * but don't kill the script entirely -- we still have
		 * to fully load the template.
		 */
		return;
	endif;
?>

<?php if ( have_comments() ) : ?>
			<h3 class="title mm" id="comments-title"><?php
			printf( _n( 'One Response to %2$s', '<strong>%1$s Responses</strong> to %2$s', get_comments_number(), TPL_SLUG ),
			number_format_i18n( get_comments_number() ), '&quot;' . get_the_title(). '&quot;' );
			?></h3>

			<?php
				//Loop through and list the comments.
				wp_list_comments( 
					array( 
						'callback' => array(&$apollo13, 'comment'),
						'end-callback' => array(&$apollo13, 'comment_end'), 
						'style'=> 'div' 
					) );
			?>
	<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // Are there comments to navigate through? ?>
			<div class="navigation">
				<div class="nav-previous"><?php previous_comments_link( __( '<span class="meta-nav">&larr;</span> Older Comments', TPL_SLUG ) ); ?></div>
				<div class="nav-next"><?php next_comments_link( __( 'Newer Comments <span class="meta-nav">&rarr;</span>', TPL_SLUG ) ); ?></div>
			</div><!-- .navigation -->
	<?php endif; // check for comment navigation ?>

<?php else : // or, if we don't have comments:

		/* If there are no comments and comments are closed,
		 * let's leave a little note, shall we?
		 */
		if ( ! comments_open() ) :
?>
		<p class="nocomments"><?php _e( 'Comments are closed.', TPL_SLUG ); ?></p>
	<?php endif; // end ! comments_open() ?>

<?php endif; // end have_comments() ?>

	<?php
		$commenter = wp_get_current_commenter();
	
		$req = get_option( 'require_name_email' );
		$aria_req = ( $req ? " aria-required='true'" : '' );
		
		
		$field_author = '';
		$field_author = '<p><label for="author">' . __( 'Name <span>(required)</span>', TPL_SLUG ) . '</label>' .
			            '<input class="required" id="author" name="author" type="text" value="' . esc_attr(  $commenter['comment_author'] ) . '" size="30"' . $aria_req . ' /></p>';
						
		$field_email = '<p><label for="email">' . __( 'Email (will not be published) <span>(required)</span>', TPL_SLUG ) . '</label>'  .
			            '<input class="required" id="email" name="email" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30"' . $aria_req . ' /></p>';
						
		$field_url = '<p><label for="url">' . __( 'Website', TPL_SLUG ) . '</label>'  .
			            '<input id="url" name="url" type="text" value="' . esc_attr(  $commenter['comment_author_url'] ) . '" size="30" /></p>';
		
		$fields =  array(
			'author' => $field_author,
			'email'  => $field_email,
			'url'    => $field_url,
		);
		
		$form_params = array(
			'fields'               => apply_filters( 'comment_form_default_fields', $fields ),
			'comment_field'        => '<p class="full"><label for="comment">' . __( 'Message <span>(required)</span>', TPL_SLUG ) . '</label>' . '<textarea id="comment" name="comment" cols="45" rows="8" aria-required="true"></textarea></p>',
			'title_reply'          => __( '<span class="mm">Leave a comment</span>', TPL_SLUG ),
			'title_reply_to'       => __( '<span class="mm">Leave a comment to %s</span>', TPL_SLUG ),
			'comment_notes_after'  => '',
			'comment_notes_before' => '',
			'id_submit'            => 'comment-submit',
			'label_submit'         => __( 'Submit comment', TPL_SLUG ),
			'cancel_reply_link'    => __( ' / Cancel reply' ),
			
		);
		
		comment_form( $form_params ); 
	?>
</div>
