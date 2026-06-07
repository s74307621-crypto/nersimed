<?php
namespace MJ\WPROM\ExtraCasts;

use MJ\WPORM\Casts\CastableInterface;

class Date implements CastableInterface {
	public function get( $value ) {
		if( is_numeric( $value ) ) {
			// Unix timestamp
			$value = Utils::convert_chars( $this->maybe_j2g( Utils::convert_chars( date_i18n( "Y-m-d", $value ) ) ) );
		} else if( is_string( $value ) && preg_match( '/^\d{4}-\d{2}-\d{2}$/', $value ) ) {
			// Already in Y-m-d format
			$value = Utils::convert_chars( $this->maybe_j2g( $value ) );
		} else if( is_string( $value ) && preg_match( '/^\d{2}\/\d{2}\/\d{4}$/', $value ) ) {
			// d/m/Y format
			$value = Utils::convert_chars( $this->maybe_j2g( Utils::convert_chars( date_i18n( "Y-m-d", strtotime( $value ) ) ) ) );
		} else if( is_string( $value ) && preg_match( '/^\d{2}-\d{2}-\d{4}$/', $value ) ) {
			// d-m-Y format
			$value = Utils::convert_chars( $this->maybe_j2g( Utils::convert_chars( date_i18n( "Y-m-d", strtotime( $value ) ) ) ) );
		} else if( is_string( $value ) && preg_match( '/^\d{4}\/\d{2}\/\d{2}$/', $value ) ) {
			// Y/m/d format
			$value = Utils::convert_chars( $this->maybe_j2g( Utils::convert_chars( date_i18n( "Y-m-d", strtotime( $value ) ) ) ) );
		} else if( is_a( $value, 'DateTime' ) ) {
			$value = $value->format( 'Y-m-d' );
		}
		if( empty( $value ) ) {
			$value = '';
		}
		return $value;
	}

	public function set( $value ) {
		return $this->get( $value );
	}

	/**
	 * Check and convert Jalali to Gregorian if needed
	 *
	 * @param string $date
	 * @return string
	 */
	public function maybe_j2g( string $date ) : string {
		$date = Utils::convert_chars( $date );
		return explode( '-', $date )[0] < 1500 ? $this->j2g( $date ) : $date;
	}

	/**
	 * Convert Jalali date to Gregorian date with Y-m-d H:i:s or Y-m-d format
	 *
	 * @param string $date
	 * @return string Converted date with Y-m-d H:i:s or Y-m-d format
	 */
	public function j2g( string $date ) : string {
		$date = Utils::convert_chars( $date );
		$datetime = explode( ' ', $date );

		$date = explode( '-', $datetime[0] );
		$result = $this->jalali_to_gregorian( $date[0], $date[1], $date[2] );
		$result[1] = str_pad( $result[1], 2, '0', STR_PAD_LEFT );
		$result[2] = str_pad( $result[2], 2, '0', STR_PAD_LEFT );
		$result = implode( "-", $result );

		return !empty( $datetime[1] ) ? "{$result} {$datetime[1]}" : $result;
	}

	public function jalali_to_gregorian($jy, $jm, $jd, $mod='') {
		$jy += 1595;
		$days = -355668 + (365 * $jy) + (((int)($jy / 33)) * 8) + ((int)((($jy % 33) + 3) / 4)) + $jd + (($jm < 7)? ($jm - 1) * 31 : (($jm - 7) * 30) + 186);
		$gy = 400 * ((int)($days / 146097));
		$days %= 146097;
		if ($days > 36524) {
			$gy += 100 * ((int)(--$days / 36524));
			$days %= 36524;
			if ($days >= 365) $days++;
		}
		$gy += 4 * ((int)($days / 1461));
		$days %= 1461;
		if ($days > 365) {
			$gy += (int)(($days - 1) / 365);
			$days = ($days - 1) % 365;
		}
		$gd = $days + 1;
		$sal_a = array(0, 31, (($gy % 4 == 0 and $gy % 100 != 0) or ($gy % 400 == 0))?29:28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
		for ($gm = 0; $gm < 13 and $gd > $sal_a[$gm]; $gm++) $gd -= $sal_a[$gm];
		return ($mod == '')? array($gy, $gm, $gd) : $gy.$mod.$gm.$mod.$gd;
	}
}
