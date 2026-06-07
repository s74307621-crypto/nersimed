<?php

use DrPlus\Components\SectionTitle;
use MJ\Whitebox\Utils;

extract($args);

if( Utils::to_bool( $options['single_specialist_show_faqs'] ) && !empty( $faqs ) ) { ?>									
		<section class="<?php echo $prefix ?>section <?php echo $prefix ?>faqs" role="region" aria-label="<?php echo esc_html__( 'Frequently Asked Questions (FAQs)', 'drplus' ) ?>">
			<?php SectionTitle::view( [
				'icon'		=> 'drplus-icon-faq',
				'tag'		=> $options['single_specialist_sections_tag'],
				'title'		=> esc_html__( 'Frequently Asked Questions (FAQs)', 'drplus' ),
				'classes'	=> [$prefix . "side-section-title"]
			] ); ?>
			<div class="<?php echo $prefix ?>faqs-list">
				<?php get_template_part( "templates/components/template-components-accordion", null, [
					'items'			=> array_slice( $faqs, 0, round( count( $faqs ) / 2 ) ),
					'title_tag'		=> 'span',
					'faq_schema'	=> false,
				] ); ?>
				<?php get_template_part( "templates/components/template-components-accordion", null, [
					'items'			=> array_slice( $faqs, round( count( $faqs ) / 2 ) ),
					'title_tag'		=> 'span',
					'faq_schema'	=> false,
				] ); ?>
			</div>
		</section>
	<?php } ?>