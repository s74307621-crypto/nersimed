<?php

use DrPlus\Utils;
use DrPlus\Utils\UI;

extract( $args );

$meta = $specialist->meta;
$certificates = !empty( $meta['certificates'] ) ? $meta['certificates'] : [];
$certificates[] = [
	'title'			=> '',
	'attachment_id'	=> '',
];

UI::dropzone( [], true );
$repeater_rows = [];
$max_upload_size_bytes = Utils::get_max_upload_size();
foreach( $certificates as $index => $certificate ) {
	$repeater_rows[$index] = [
		[ // title
			'type'	=> 'full',
			'field'	=> [ // title
				'type'			=> 'text',
				'placeholder'	=> __( "Title", 'drplus' ),
				'name'			=> "specialist_meta[certificates][%index%][title]",
				'classes'		=> ['input-secondary', 'certificate'],
				'value'			=> $certificate['title'],
			],
		],
		[ // image
			'type'	=> 'dropzone',
			'field'	=> [
				'type'				=> 'dropzone',
				'title'				=> __( "Attachment image", 'drplus' ),
				'max_upload_size'	=> $max_upload_size_bytes,
				'input_name'		=> "specialist_meta[certificates][%index%][attachment_id]",
				'value'				=> $certificate['attachment_id'],
				'required'			=> '',
			],
		]
	];
}
UI::repeater( [
	'template_id'	=> 'specialist-certificate',
	'style'			=> 'grid',
	'flow'			=> 'row',
	'slot_attrs'	=> [
		'data-swapy-slot'	=> 'specialist-certificate-%index%-slot',
	],
	'item_attrs'	=> [
		'data-swapy-item'	=> 'specialist-certificate-%index%-item',
	],
	'rows'	=> $repeater_rows,
] );
?>