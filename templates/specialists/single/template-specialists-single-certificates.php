<?php

use DrPlus\Components\SectionTitle;
use MJ\Whitebox\Utils;

extract($args);

if( Utils::to_bool( $options['single_specialist_show_certificates'] ) && !empty( $specialist->meta['certificates'] ) ) { ?>										
	<section class="<?php echo $prefix ?>section <?php echo $prefix ?>certificates" role="complementary" aria-label="<?php echo esc_html__( 'Certificates and Courses', 'drplus' ) ?>">
		<?php SectionTitle::view( [
			'icon'		=> 'drplus-icon-personalcard-bold',
			'tag'		=> $options['single_specialist_sections_tag'],
			'title'		=> esc_html__( 'Certificates and Courses', 'drplus' ),
			'classes'	=> [$prefix . "section-title"]
		] ); ?>
		<ul class="<?php echo $prefix ?>certificates-list">
			<?php
			foreach( $specialist->meta['certificates'] as $certificate ) {
				if( empty( $certificate['title'] ) ) continue;

				$item_html_attrs = [
					'classes'	=> "{$prefix}certificate-item",
				];

				if( $options['single_specialist_show_certificate_image'] && !empty( $certificate['attachment_id'] ) ) {
					$item_html_attrs['data-src'] = wp_get_attachment_image_url( $certificate['attachment_id'], 'full' );
				}
				?>
				<li <?php echo Utils::get_html_attributes( $item_html_attrs ) ?>><?php echo esc_html( $certificate['title'] ) ?></li>
			<?php } ?>
		</ul>
		<?php
		if( $options['single_specialist_show_certificates_verified'] ) {
			$certificates_verified_text = $options['single_specialist_certificates_verified_text'];
			$certificates_verified_text = str_replace( '{name}', $specialist->display_name, $certificates_verified_text );
			?>
			<div class="<?php echo $prefix ?>certificates-verified">
				<i class="drplus-icon-verify-fill"></i>
				<span class="<?php echo $prefix ?>certificates-verified-text">
					<?php echo esc_html( $certificates_verified_text ) ?>
				</span>
			</div>
		<?php } ?>
	</section>
<?php } ?>