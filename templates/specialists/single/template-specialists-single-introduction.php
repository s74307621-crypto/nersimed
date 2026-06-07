<?php

use DrPlus\Components\SectionTitle;
use MJ\Whitebox\Utils;

extract($args);

if( Utils::to_bool( $options['single_specialist_show_introduction'] ) && !empty( $specialist->about ) ) { ?>
	<section class="<?php echo $prefix ?>section <?php echo $prefix ?>introduction" role="region" aria-label="<?php echo esc_html__( 'Introduction and Speciality', 'drplus' ) ?>" itemprop="description">
		<?php SectionTitle::view( [
			'icon'		=> 'drplus-icon-stethoscope',
			'tag'		=> $options['single_specialist_sections_tag'],
			'title'		=> esc_html__( 'Introduction and Speciality', 'drplus' ),
			'classes'	=> [$prefix . "section-title"]
		] ); ?>
		<div class="<?php echo $prefix ?>about-wrap">
			<?php echo wpautop( Utils::parse_text_editor( $specialist->about ), true ) ?>
		</div>
	</section>										
<?php } ?>