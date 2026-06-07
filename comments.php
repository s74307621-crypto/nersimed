<?php
/**
 * The template for displaying comments
 *
 * The area of the page that contains both current comments
 * and the comment form.
 */

use DrPlus\Components\SectionTitle;

if( !defined( 'ABSPATH' ) ) exit;
if( post_password_required() || !post_type_supports( get_post_type(), 'comments' ) || !comments_open() ) {
	return;
}
?>
<div id="comments" class="row">
	<div class="comments-area col-12">
		<?php if( have_comments() ) { ?>
			<?php
			SectionTitle::view( [
				'icon'			=> 'drplus-icon-chat-fill',
				'tag'			=> 'h3',
				'title'			=> apply_filters( 'drplus/comments/title', esc_html__( 'Comments', 'drplus' ) ),
				'aria-label'	=> apply_filters( 'drplus/comments/title', esc_html__( 'Comments', 'drplus' ) ),
				'classes'		=> ['comments-title'],
			] );
			?>
			<ol class="comment-list">
				<?php
					wp_list_comments(
						[
							'style'			=> 'ol',
							'avatar_size'	=> 75,
							'callback'		=> 'drplus_comment_walker',
						]
					);
				?>
			</ol><!-- .comment-list -->
		<?php } ?>

		<div id="comments-form-wrap">
			<?php
			ob_start();
			get_template_part( "templates/components/template-components-button", null, [
				'type'			=> 'primary',
				'text'			=> 'button-text',
				'icon'			=> is_rtl() ? 'drplus-icon-arrow-up-left-square' : 'drplus-icon-arrow-up-right-square',
				'icon_align'	=> 'end',
				'align'			=> 'end',
				'small'			=> true,
				'classes'		=> ['button-class'],
				'id'			=> 'button-id',
				'atts'			=> [
					'type'	=> 'submit',
					'name'	=> 'button-name'
				],
			] );
			$submit_button = ob_get_clean();
			$submit_button = str_replace( 'button-name', '%1$s', $submit_button );
			$submit_button = str_replace( 'button-id', '%2$s', $submit_button );
			$submit_button = str_replace( 'button-class', '%3$s', $submit_button );
			$submit_button = str_replace( 'button-text', '%4$s', $submit_button );
			?>
			<?php comment_form(
				[
					'logged_in_as'			=> '',
					'comment_notes_before'	=> '',
					'title_reply'			=> esc_html__( 'Add reply', 'drplus' ),
					'title_reply_before'  => '<h3 class="section-title"><div class="section-title-inner">
						<span class="drplus-simple-icon-wrap icon-has-bg section-title-icon-wrap">
						<i class="drplus-icon-diamond drplus-simple-icon section-title-icon" aria-hidden="true"></i></span>	
						<span class="section-title-title">',
					'title_reply_after'   => '</span></div></h3>',
					'submit_button'			=> $submit_button
				]
			); ?>
		</div>
	</div>
</div>