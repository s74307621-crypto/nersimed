<?php
namespace DrPlus\Utils;

use DrPlus\Model\OTP;
use DrPlus\Utils;

class SMS extends Utils {
	public static function defaults() {
		$blogname = get_option( 'blogname', '' );
		return [
			'gateway'	=> '',
			'messages'	=> [
				'auth'	=> [
					'login'				=> sprintf( __( 'Your login code is: {otp}%s', 'drplus' ), "\r\n\r\n" . $blogname ),
					'register'			=> sprintf( __( 'Your register code is: {otp}%s', 'drplus' ), "\r\n\r\n" . $blogname ),
					'lost_password'	=> sprintf( __( 'Your new password is: {password}%s', 'drplus' ), "\r\n\r\n" . $blogname ),
				]
			],
			'settings'	=> [
				'auth'	=> [
					'login'	=> [
						'enabled'	=> true,
						'pattern'	=> '',
						'otp_timer'	=> 60,
					],
					'register'	=> [
						'enabled'	=> true,
						'pattern'	=> '',
						'otp_timer'	=> 60,
					],
					'one_form'	=> true,
					'lost_password'	=> [
						'enabled'	=> true,
						'pattern'	=> '',
					],
				],
			],
			'security'	=> [
				'hide_mobile'			=> 'mid_star',
				'hide_mobile_custom'	=> sprintf( __( "{name}'s user", 'drplus' ), "\r\n\r\n" . get_option( 'blogname', '' ) ),
			],
		];
	}

	public static function gateways() {
		return apply_filters( 'drplus/sms/gateways', [
			'melipayamak'	=> [
				'label'			=> __( 'Melipayamak', 'drplus' ),
				'logo'			=> 'melipayamak.jpg',
				'fields'		=> ['username', 'password'],
			],
			'farazsms'	=> [
				'label'			=> __( 'Farazsms (ippanel)', 'drplus' ),
				'logo'			=> 'farazsms.png',
				'fields'		=> ['api_key', 'from'],
			],
			'farazsms_new'	=> [
				'label'			=> __( 'New Farazsms (iranpayamak)', 'drplus' ),
				'logo'			=> 'farazsms.png',
				'fields'		=> ['api_key', 'from'],
			],
			'smsir'	=> [
				'label'			=> __( 'SMS.ir', 'drplus' ),
				'logo'			=> 'sms.ir.svg',
				'fields'		=> ['api_key'],
			],
			'kavenegar'	=> [
				'label'			=> __( 'Kavenegar', 'drplus' ),
				'logo'			=> 'kavenegar.png',
				'fields'		=> ['api_key'],
			],
			'farapayamak'	=> [
				'label'			=> __( 'Farapayamak', 'drplus' ),
				'logo'			=> 'farapayamak.png',
				'fields'		=> ['username', 'password'],
			],
			'payamresan'	=> [
				'label'			=> __( 'Payamresan', 'drplus' ),
				'logo'			=> 'payamresan.svg',
				'fields'		=> ['api_key'],
			],
			'raygansms'	=> [
				'label'			=> __( 'Raygansms', 'drplus' ),
				'logo'			=> 'raygansms.png',
				'fields'		=> ['username', 'password', 'api_key'],
			],
			'asanak'	=> [
				'label'			=> __( 'Asanak', 'drplus' ),
				'logo'			=> 'asanak.png',
				'fields'		=> ['username', 'password'],
			],
		] );
	}
	
	public static function get_settings() {
		$settings = null;
		if( $settings === null ) {
			$settings = parent::check_default( get_option( 'drplus_sms_settings', self::defaults() ), self::defaults() );

			if( !empty( $settings['gateway'] ) ) {
				if( !isset( self::gateways()[$settings['gateway']] ) ) {
					$settings = parent::unset( $settings, [$settings['gateway']] ); // Remove the gateway settings if it's not valid
					$settings['gateway'] = '';
				} else {
					foreach( self::gateways()[$settings['gateway']]['fields'] as $field ) {
						$settings[$settings['gateway']][$field] = $settings[$settings['gateway']][$field] ?? '';
					}
				}
			}
		}

		return $settings;
	}

	public static function get_reserve_notif_book_sms_settings( $type = '', $settings = [] ) {
		if( empty( $settings ) ) $settings = self::get_settings();

		if( empty( $type ) ) {
			$specialist_settings	= self::get_reserve_notif_book_sms_settings( 'specialist', $settings );
			$patient_settings		= self::get_reserve_notif_book_sms_settings( 'patient', $settings );

			return [
				'specialist'	=> $specialist_settings,
				'patient'		=> $patient_settings,
			];
		} else {
			$message	= $settings['messages']['reserve_notification'][$type]['book'] ?? "";
			$status		= $settings['settings']['reserve_notification'][$type]['book']['enabled'] ?? false;
	
			return [
				'status'	=> $status,
				'message'	=> $message,
			];
		}
	}

	public static function get_reserve_notif_book_canceled_sms_settings( $type = '', $settings = [] ) {
		if( empty( $settings ) ) $settings = self::get_settings();

		if( empty( $type ) ) {
			$specialist_settings	= self::get_reserve_notif_book_canceled_sms_settings( 'specialist', $settings );
			$patient_settings		= self::get_reserve_notif_book_canceled_sms_settings( 'patient', $settings );

			return [
				'specialist'	=> $specialist_settings,
				'patient'		=> $patient_settings,
			];
		} else {
			$message	= $settings['messages']['reserve_notification'][$type]['book_canceled'] ?? "";
			$status		= $settings['settings']['reserve_notification'][$type]['book_canceled']['enabled'] ?? false;
	
			return [
				'status'	=> $status,
				'message'	=> $message,
			];
		}
	}

	public static function get_reserve_notif_reminder_sms_settings( $type = '', $settings = [] ) {
		if( empty( $settings ) ) $settings = self::get_settings();

		if( empty( $type ) ) { // get both
			$specialist_settings	= self::get_reserve_notif_reminder_sms_settings( 'specialist', $settings );
			$patient_settings		= self::get_reserve_notif_reminder_sms_settings( 'patient', $settings );

			return [
				'specialist'	=> $specialist_settings,
				'patient'	=> $patient_settings,
			];
		} else {
			$status		= $settings['settings']['reserve_notification'][$type]['reminder']['enabled'] ?? false;
	
			$items = [];
			if( empty( $settings['settings']['reserve_notification'][$type] ) ) {
				return [];
			}
			foreach( $settings['settings']['reserve_notification'][$type]['reminder'] as $index => $reminder ) {
				if( !is_array( $reminder ) ) continue;
				$reminder['message'] = $settings['messages']['reserve_notification'][$type]['reminder'][$index];
				$items[$index] = $reminder;
			}
			return [
				'status'	=> $status,
				'items'		=> $items,
			];
		}
	}

	public static function get_specialist_panel_settings() {
		$settings = self::get_settings();

		return [
			'settings'	=> $settings['settings']['specialist_panel'] ?? [],
			'messages'	=> $settings['messages']['specialist_panel'] ?? []
		];
	}

	public static function sanitize_gateway( string $gateway, array $gateways = [] ) {
		if( empty( $gateways ) ) {
			$gateways = self::gateways();
		}
		return parent::ensure_values_in_array( parent::convert_chars( $gateway, true, 'strtolower' ), array_keys( $gateways ) );
	}

	public static function save_settings( $settings ) {
		$gateways = self::gateways();

		// Save gateway settings
		$settings["drplus_sms_gateway"] = !empty( $settings["drplus_sms_gateway"] ) ? $settings["drplus_sms_gateway"] : '';
		$result_settings['gateway'] = self::sanitize_gateway( $settings["drplus_sms_gateway"], $gateways );
		foreach( $gateways as $id => $gateway ) {
			foreach( $gateway['fields'] as $field ) {
				$result_settings[$id][$field] = $settings["drplus_sms_{$id}"][$field] ?? '';
			}
		}

		// Save messages settings
		$messages_settings = $settings['drplus_sms_settings'];

		// Auth: Login
		$result_settings['settings']['auth']['login']['enabled'] = !empty( $messages_settings['auth']['login']['enabled'] );
		$result_settings['settings']['auth']['login']['pattern'] = parent::convert_chars( $messages_settings['auth']['login']['pattern'] );
		$result_settings['settings']['auth']['login']['otp_timer'] = parent::convert_chars( $messages_settings['auth']['login']['otp_timer'], true, 'absint' );
		$result_settings['messages']['auth']['login'] = sanitize_textarea_field( $messages_settings['auth']['login']['message'] );

		// Auth: Register
		$result_settings['settings']['auth']['register']['enabled'] = !empty( $messages_settings['auth']['register']['enabled'] );
		$result_settings['settings']['auth']['register']['pattern'] = parent::convert_chars( $messages_settings['auth']['register']['pattern'] );
		$result_settings['settings']['auth']['register']['otp_timer'] = parent::convert_chars( $messages_settings['auth']['register']['otp_timer'], true, 'absint' );
		$result_settings['messages']['auth']['register'] = sanitize_textarea_field( $messages_settings['auth']['register']['message'] );

		$result_settings['settings']['auth']['one_form'] = $result_settings['settings']['auth']['login']['enabled'] && $result_settings['settings']['auth']['register']['enabled'] && !empty( $messages_settings['auth']['one_form'] );

		// Auth: Forget password
		$result_settings['settings']['auth']['lost_password']['enabled'] = !empty( $messages_settings['auth']['lost_password']['enabled'] );
		$result_settings['settings']['auth']['lost_password']['pattern'] = parent::convert_chars( $messages_settings['auth']['lost_password']['pattern'] );
		$result_settings['messages']['auth']['lost_password'] = sanitize_textarea_field( $messages_settings['auth']['lost_password']['message'] );

		// Reserves notifications : To Specialist When booked
		$result_settings['settings']['reserve_notification']['specialist']['book']['enabled'] = !empty( $messages_settings['reserve_notification']['specialist']['book']['enabled'] );
		$result_settings['settings']['reserve_notification']['specialist']['book']['pattern'] = parent::convert_chars( $messages_settings['reserve_notification']['specialist']['book']['pattern'] );
		$result_settings['messages']['reserve_notification']['specialist']['book'] = sanitize_textarea_field( $messages_settings['reserve_notification']['specialist']['book']['message'] );

		// Reserves notifications : To Specialist When book cancelled
		$result_settings['settings']['reserve_notification']['specialist']['book_canceled']['enabled'] = !empty( $messages_settings['reserve_notification']['specialist']['book_canceled']['enabled'] );
		$result_settings['settings']['reserve_notification']['specialist']['book_canceled']['pattern'] = parent::convert_chars( $messages_settings['reserve_notification']['specialist']['book_canceled']['pattern'] );
		$result_settings['messages']['reserve_notification']['specialist']['book_canceled'] = sanitize_textarea_field( $messages_settings['reserve_notification']['specialist']['book_canceled']['message'] );

		// Reserves notifications : Reminders For Specialist (multiple)
		$result_settings['settings']['reserve_notification']['specialist']['reminder']['enabled'] = !empty( $messages_settings['reserve_notification']['specialist']['reminder']['enabled'] );
		foreach( $messages_settings['reserve_notification']['specialist']['reminder'] as $index => $reminder ) {
			if( !is_array( $reminder ) ) continue;
			$reminder_id = !empty( $reminder['id'] ) ? Utils::convert_chars( $reminder['id'] ) : uniqid();
			$result_settings['messages']['reserve_notification']['specialist']['reminder'][$reminder_id] = sanitize_textarea_field( $reminder['message'] );
			$result_settings['settings']['reserve_notification']['specialist']['reminder'][$reminder_id]['timing'] = $reminder['timing'] ?? '-1';
			$result_settings['settings']['reserve_notification']['specialist']['reminder'][$reminder_id]['pattern'] = parent::convert_chars( $reminder['pattern'] ) ?? "";
		}

		// Reserves notifications : To Patient When booked
		$result_settings['settings']['reserve_notification']['patient']['book']['enabled'] = !empty( $messages_settings['reserve_notification']['patient']['book']['enabled'] );
		$result_settings['settings']['reserve_notification']['patient']['book']['pattern'] = parent::convert_chars( $messages_settings['reserve_notification']['patient']['book']['pattern'] );
		$result_settings['messages']['reserve_notification']['patient']['book'] = sanitize_textarea_field( $messages_settings['reserve_notification']['patient']['book']['message'] );

		// Reserves notifications : To Patient When book cancelled
		$result_settings['settings']['reserve_notification']['patient']['book_canceled']['enabled'] = !empty( $messages_settings['reserve_notification']['patient']['book_canceled']['enabled'] );
		$result_settings['settings']['reserve_notification']['patient']['book_canceled']['pattern'] = parent::convert_chars( $messages_settings['reserve_notification']['patient']['book_canceled']['pattern'] );
		$result_settings['messages']['reserve_notification']['patient']['book_canceled'] = sanitize_textarea_field( $messages_settings['reserve_notification']['patient']['book_canceled']['message'] );

		// Reserves notifications : Reminders For Patient (multiple)
		$result_settings['settings']['reserve_notification']['patient']['reminder']['enabled'] = !empty( $messages_settings['reserve_notification']['patient']['reminder']['enabled'] );
		foreach( $messages_settings['reserve_notification']['patient']['reminder'] as $index => $reminder ) {
			if( !is_array( $reminder ) ) continue;
			$reminder_id = !empty( $reminder['id'] ) ? Utils::convert_chars( $reminder['id'] ) : uniqid();
			$result_settings['messages']['reserve_notification']['patient']['reminder'][$reminder_id] = sanitize_textarea_field( $reminder['message'] );
			$result_settings['settings']['reserve_notification']['patient']['reminder'][$reminder_id]['timing'] = $reminder['timing'] ?? '-1';
			$result_settings['settings']['reserve_notification']['patient']['reminder'][$reminder_id]['pattern'] = parent::convert_chars( $reminder['pattern'] ) ?? "";
		}

		// Specialist panel: New request
		$result_settings['settings']['specialist_panel']['new_request']['enabled'] = !empty( $messages_settings['specialist_panel']['new_request']['enabled'] );
		$result_settings['settings']['specialist_panel']['new_request']['pattern'] = parent::convert_chars( $messages_settings['specialist_panel']['new_request']['pattern'] );
		$recipients = parent::convert_chars( $messages_settings['specialist_panel']['new_request']['recipients'], 'sanitize_textarea_field' );
		$recipients = explode( PHP_EOL, $recipients );
		if( !empty( $recipients ) ) {
			$recipients = array_map( function( $phone ) {
				return Sanitizers::phone( "{$phone}" );
			}, $recipients );
			$recipients = array_filter( $recipients, 'is_numeric' );
		}
		$result_settings['settings']['specialist_panel']['new_request']['recipients'] = $recipients;
		$result_settings['messages']['specialist_panel']['new_request'] = sanitize_textarea_field( $messages_settings['specialist_panel']['new_request']['message'] );

		// Specialist panel: Change status
		$statuses = UtilsSpecialists::statuses( true );
		foreach( array_keys( $statuses ) as $status ) {
			$result_settings['settings']['specialist_panel']['change_status'][$status]['enabled'] = !empty( $messages_settings['specialist_panel']['change_status'][$status]['enabled'] );
			$result_settings['settings']['specialist_panel']['change_status'][$status]['pattern'] = parent::convert_chars( $messages_settings['specialist_panel']['change_status'][$status]['pattern'] );
			$result_settings['messages']['specialist_panel']['change_status'][$status] = sanitize_textarea_field( $messages_settings['specialist_panel']['change_status'][$status]['message'] );
		}

		// Security
		$result_settings['security']['hide_mobile'] = parent::ensure_values_in_array( $settings["drplus_sms_security"]['hide_mobile'], array_keys( self::hide_mobile_types() ), 'mid_star' );
		$result_settings['security']['hide_mobile_custom'] = parent::convert_chars( $settings["drplus_sms_security"]['hide_mobile_custom'] );

		update_option( 'drplus_sms_settings', $result_settings, false );
		do_action( 'drplus/sms/settings/updated', $result_settings );
		add_settings_error( 'drplus-sms-settings', 'updated', __( 'Settings updated', 'drplus' ), 'success' );
	}

	public static function auth_variables( $additional = [], $excludes = [] ) {
		$variables = [
			'otp'		=> __( "The OTP code", 'drplus' ),
			'end_time'	=> __( "The end time of the OTP code", 'drplus' ),
			'domain'	=> __( "The domain name", 'drplus' ),
			'name'		=> __( "The website name", 'drplus' ),
		];
		$variables = array_merge( $variables, $additional );
		return parent::unset( $variables, $excludes );
	}

	public static function reserve_variables() {
		return [
			'book_id'				=> __( 'Book ID', 'drplus' ),
			'specialist_name'		=> __( 'Specialist name', 'drplus' ),
			'specialist_mobile'		=> __( 'Specialist mobile', 'drplus' ),
			'patient_name'			=> __( 'Patient full name', 'drplus' ),
			'patient_first_name'	=> __( 'Patient first name', 'drplus' ),
			'patient_last_name'		=> __( 'Patient last name', 'drplus' ),
			'patient_mobile'		=> __( 'Patient mobile', 'drplus' ),
			'visit_date'			=> __( 'Visit date', 'drplus' ),
			'visit_time'			=> __( 'Visit time', 'drplus' ),
			'office'				=> __( 'Office', 'drplus' ),
			'domain'				=> __( "The domain name", 'drplus' ),
			'name'					=> __( "The website name", 'drplus' ),
		];
	}

	public static function security_variables() {
		return [
			'domain'	=> __( "The domain name", 'drplus' ),
			'name'		=> __( "The website name", 'drplus' ),
		];
	}

	public static function reminder_timing_options() {
		return [
			'30'	=> __( '30 minutes before', 'drplus' ),
			'60'	=> __( '1 hour before', 'drplus' ),
			'120'	=> sprintf( __( '%d hour before', 'drplus' ), 2 ),
			'180'	=> sprintf( __( '%d hour before', 'drplus' ), 3 ),
			'240'	=> sprintf( __( '%d hour before', 'drplus' ), 4 ),
			'360'	=> sprintf( __( '%d hour before', 'drplus' ), 5 ),
			'720'	=> sprintf( __( '%d hour before', 'drplus' ), 12 ),
			'1440'	=> sprintf( __( '%d hour before', 'drplus' ), 24 ),
			'2880'	=> __( '2 days before', 'drplus' ),
			'-1'	=> __( 'No reminder', 'drplus' ),
		];
	}

	public static function apply_variables( string $text, $to, string $type = '', array $custom_variables = [] ) {
		if( strpos( $text, "{otp}" ) !== false ) {
			$otp = rand( 1000, 9999 );
			$text = str_replace( "{otp}", $otp, $text );
			
			$timer = parent::get_nested_value( self::get_settings()['settings'], $type )['otp_timer'];
			$end_time = parent::convert_chars( date_i18n( 'U' ) ) + $timer;
			$end_time = date_i18n( "Y-m-d H:i:s", $end_time );
			$text = str_replace( "{end_time}", $end_time, $text );

			$otp_db = new OTP;
			$otp_db->updateOrCreate( [
				'mobile'	=> $to[0]
			], [
				'mobile'	=> $to[0],
				'otp'		=> $otp,
				'expire'	=> Date::maybe_j2g( Utils::convert_chars( $end_time ) ),
			] );
		}

		$text = parent::apply_general_variables( $text, $custom_variables );

		return $text;
	}

	public static function hide_mobile_types() {
		return [
			'disabled'	=> esc_html__( "Disabled", 'drplus' ),
			'mid_star'	=> '0999***9999',
			'end_star'	=> '0999999****',
			'sitetitle'	=> esc_html__( "Site title", 'drplus' ),
			'custom'	=> esc_html__( "Custom", 'drplus' ),
		];
	}
}