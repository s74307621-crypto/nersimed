<?php

use DrPlus\Components\SectionTitle;
use DrPlus\Utils;

extract($args);

if( Utils::to_bool( $options['single_specialist_show_offices'] ) && !empty( $specialist_offices ) ) { ?>								
	<section class="<?php echo $prefix ?>section <?php echo $prefix ?>offices" role="complementary" aria-label="<?php sprintf( esc_html__( 'Clinic address of %s', 'drplus' ), $specialist->display_name ) ?>">
		<?php SectionTitle::view( [
			'icon'		=> 'drplus-icon-location-fill',
			'tag'		=> $options['single_specialist_sections_tag'],
			'title'		=> sprintf( esc_html__( 'Clinic address of %s', 'drplus' ), $specialist->display_name ),
			'classes'	=> [$prefix . "side-section-title"]
		] ); ?>
		<div class="<?php echo $prefix ?>offices-list">
			<?php foreach( $specialist_offices as $office ) { ?>
				<div class="<?php echo $prefix ?>office">
					<div class="<?php echo $prefix ?>office-head">
						<div class="<?php echo $prefix ?>office-img-wrap">
							<img src="<?php echo $office['image'] ?>" class="<?php echo $prefix ?>office-img" alt="<?php echo esc_attr( $office['name'] ) ?>">
						</div>
						<div class="<?php echo $prefix ?>office-info">																
							<div class="<?php echo $prefix ?>office-name-wrap">
								<?php if( $office['type'] == 'hospital' ) { ?>
									<a href="<?php echo get_permalink( $office['id'] ) ?>" class="<?php echo $prefix ?>office-link" title="<?php echo esc_attr( $office['name'] ) ?>">
										<span class="<?php echo $prefix ?>office-name line-clamp line-clamp-1"><?php echo esc_html( $office['name'] ) ?></span>
										<i class="drplus-icon-square-arrow-<?php echo is_rtl() ? 'right' : 'left' ?>"></i>
									</a>
								<?php } else { ?>
									<span class="<?php echo $prefix ?>office-name"><?php echo esc_html( $office['name'] ) ?></span>
								<?php } ?>
							</div>
							<?php if( !empty( $office['phone'] ) ) { ?>
								<?php foreach( explode( PHP_EOL, $office['phone'] ) as $phone ) { ?>				
									<a href="tel:<?php echo $phone ?>" class="<?php echo $prefix ?>office-phone">
										<i class="drplus-icon-calling"></i>
										<?php echo esc_html( $phone ) ?>
									</a>
								<?php } ?>
							<?php } ?>
						</div>
					</div>
					<?php if( !empty( $office['address'] || !empty( $office['map_url'] ) ) ) { ?>
						<div class="<?php echo $prefix ?>office-address-wrap">
							<?php if( !empty( $office['address'] ) ) { ?>
								<p class="<?php echo $prefix ?>office-address"><?php echo esc_html( $office['address'] ) ?></p>
							<?php } ?>
							<?php if( !empty( $office['map_url'] ) ) { ?>
								<a
									href="<?php echo esc_url( $office['map_url'] ) ?>"
									class="<?php echo $prefix ?>office-map map-popup-opener"
									target="map-popup-iframe"
									title="<?php echo esc_attr( $office['name'] ) ?>"
									aria-label="<?php echo esc_attr( sprintf( __( 'Show %s on the map', 'drplus' ), $office['name'] ) ) ?>"
									data-title="<?php echo esc_attr( $office['name'] ) ?>"
								>
									<i class="drplus-icon-routing"></i>
									<?php esc_html_e( 'Show on the map', 'drplus' ) ?>
								</a>
							<?php } ?>
						</div>													
					<?php } ?>
				</div>
			<?php } ?>
		</div>
	</section>
<?php } ?>