<?php
use DrPlus\Components\SectionTitle;

extract($args);

if( $options['single_specialist_show_services'] && !empty( $specialist->meta['services'] ) ) { ?>
	<section class="<?php echo $prefix ?>section <?php echo $prefix ?>services" role="region" aria-label="<?php printf( esc_html__( 'Specialized services of %s', 'drplus' ), $specialist->display_name ) ?>">
		<?php SectionTitle::view( [
			'icon'		=> 'drplus-icon-mental-health',
			'tag'		=> $options['single_specialist_sections_tag'],
			'title'		=> sprintf( esc_html__( 'Specialized services of %s', 'drplus' ), $specialist->display_name ),
			'classes'	=> [$prefix . "side-section-title"]
		] ); ?>
		<?php foreach( $specialist->meta['services'] as $service ) { ?>
			<div class="<?php echo $prefix ?>service">
				<?php SectionTitle::view( [
					'icon'			=> 'drplus-icon-diamond',
					'icon_has_bg'	=> false,
					'tag'			=> 'span',
					'title'			=> esc_html( $service['title'] ),
					'classes'		=> [$prefix . "service-title"]
				] ); ?>
				<?php if( !empty( $service['desc'] ) ) { ?>
					<span class="<?php echo $prefix ?>service-desc"><?php echo esc_html( $service['desc'] ) ?></span>
				<?php } ?>
			</div>
		<?php } ?>
		<div class="<?php echo $prefix ?>services-show-more-wrap">
			<span><?php echo esc_html__( 'Show more', 'drplus' ) ?></span>
			<i class="drplus-icon-bottom"></i>
		</div>
	</section>
<?php } ?>