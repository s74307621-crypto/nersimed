<?php
extract($args);
?>
<div class="<?php echo $prefix ?>main_section">
	<?php
	get_template_part( 'templates/specialists/single/template-specialists-single-stats', null, [
		'prefix'		=> $prefix,
		'specialist'	=> $specialist,
		'options'		=> $options,
		'stats'			=> $stats,
	] );
	get_template_part( 'templates/specialists/single/template-specialists-single-introduction', null, [
		'prefix'		=> $prefix,
		'specialist'	=> $specialist,
		'options'		=> $options,
	] );
	get_template_part( 'templates/specialists/single/template-specialists-single-certificates', null, [
		'prefix'		=> $prefix,
		'specialist'	=> $specialist,
		'options'		=> $options,
	] );
	get_template_part( 'templates/specialists/single/template-specialists-single-faqs', null, [
		'prefix'		=> $prefix,
		'specialist'	=> $specialist,
		'options'		=> $options,
		'faqs'			=> $faqs
	] );
	get_template_part( 'templates/specialists/single/template-specialists-single-reviews', null, [
		'prefix'		=> $prefix,
		'specialist'	=> $specialist,
		'options'		=> $options,
	] );
	?>
</div>