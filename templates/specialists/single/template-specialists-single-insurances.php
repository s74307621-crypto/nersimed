<?php
use DrPlus\Components\SectionTitle;

extract($args);

if( $options['insurance'] && !empty( $insurances ) ) { ?>
	<section class="<?php echo $prefix ?>section <?php echo $prefix ?>insurances" role="complementary" aria-label="<?php echo esc_html__( 'Covered insurances', 'drplus' ) ?>">
		<?php SectionTitle::view( [
			'icon'		=> 'drplus-icon-archive-book-bold',
			'tag'		=> $options['single_specialist_sections_tag'],
			'title'		=> esc_html__( 'Covered insurances', 'drplus' ),
			'classes'	=> [$prefix . "side-section-title"]
		] ); ?>
		<div class="<?php echo $prefix ?>insurances-list">
			<?php
			foreach( $insurances as $insurance ) {
				?>
				<div class="<?php echo $prefix ?>insurance">
					<?php if( !empty( $insurance['icon'] ) ) { ?>
						<i class="<?php echo esc_attr( $insurance['icon'] ) ?> <?php echo $prefix ?>insurance-icon"></i>
					<?php } ?>
					<span class="<?php echo $prefix ?>insurance-name"><?php echo esc_html( $insurance['name'] ) ?></span>
				</div>
			<?php } ?>
		</div>
	</section>
<?php } ?>