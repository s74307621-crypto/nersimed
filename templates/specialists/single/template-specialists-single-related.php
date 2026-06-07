<?php

use DrPlus\Components\SectionTitle;
use DrPlus\Utils\UtilsSpecialists;
use MJ\Whitebox\Utils;
use MJ\Whitebox\Utils\Elementor;

extract($args);

if( empty( $related_specialists ) ) return;

$devices = ['desktop', 'tablet', 'mobile'];
$display_settings = [
	'style'					=> 'card-1',
	'desktop_slider'		=> true,
	'desktop_cols'			=> 5,
	'desktop_slides_type'	=> 'auto',
	'desktop_slides_space'	=> 16,
	'tablet_slider'			=> true,
	'tablet_cols'			=> 3,
	'tablet_slides_type'	=> 'auto',
	'tablet_slides_space'	=> 16,
	'mobile_slides_type'	=> 'auto',
	'mobile_slider'			=> true,
	'mobile_cols'			=> 1,
	'mobile_slides_space'	=> 16,

	'name-tag'		=> $options['single_specialist_related_specialists_name_tag'],
	'short_bio-tag'	=> $options['single_specialist_related_specialists_short_bio_tag'],
	'verified-text'	=> $options['single_specialist_related_specialists_verified_text'],
];
$display_attributes = Elementor::get_display_attributes( $display_settings );
$attributes = [
	'class'	=> array_merge( [
		'drplus-slider-wrap',
		$prefix . 'related-specialists-slider-wrap',
		$prefix . "related-specialists",
		$prefix . "related-specialists-wrap",
	], $display_attributes['wrap_classes'] ),
	'data-settings'	=> $display_attributes['args'],
	'style'			=> $display_attributes['style'],
];
?>
<div <?php echo Utils::get_html_attributes( $attributes ) ?>>
	<div class="drplus-slider-head <?php echo $prefix ?>related-specialists-slider-head">
		<?php
		SectionTitle::view( [
			'icon'		=> 'drplus-icon-stethoscope',
			'tag'		=> $options['single_specialist_sections_tag'],
			'title'		=> esc_html__( 'Related Specialists', 'drplus' ),
			'classes'	=> [$prefix . "section-title"],
			'nav_btns'	=> true,
		] );							
		?>
	</div>
	<?php UtilsSpecialists::list( $display_settings, 'all', $related_specialists ) ?>
</div>