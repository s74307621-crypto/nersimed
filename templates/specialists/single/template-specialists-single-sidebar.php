<?php
extract($args);
?>
<div class="<?php echo $prefix ?>sidebar">
	<?php
	add_action( 'drplus/specialist/single/sidebar_start', $specialist );
	get_template_part( 'templates/specialists/single/template-specialists-single-bio', null, [
		'prefix'			=> $prefix,
		'specialist'		=> $specialist,
		'options'			=> $options,
		'avatar_url'		=> $avatar_url,
		'comments_count'	=> $comments_count
	] );
	add_action( 'drplus/specialist/single/after_bio', $specialist );
	get_template_part( 'templates/specialists/single/template-specialists-single-offices', null, [
		'prefix'				=> $prefix,
		'specialist'			=> $specialist,
		'options'				=> $options,
		'specialist_offices'	=> $specialist_offices,
	] );
	add_action( 'drplus/specialist/single/after_offices', $specialist );
	get_template_part( 'templates/specialists/single/template-specialists-single-services', null, [
		'prefix'				=> $prefix,
		'specialist'			=> $specialist,
		'options'				=> $options,
	] );
	add_action( 'drplus/specialist/single/after_services', $specialist );
	get_template_part( 'templates/specialists/single/template-specialists-single-insurances', null, [
		'prefix'				=> $prefix,
		'specialist'			=> $specialist,
		'options'				=> $options,
		'insurances'			=> $insurances,
	] );
	add_action( 'drplus/specialist/single/after_insurances', $specialist );
	add_action( 'drplus/specialist/single/sidebar_end', $specialist );
	?>
</div>