<?php

namespace App\Helpers;

class Utility {
	public static function formatPhone($phone_number) {
		$phone = preg_replace('/[^0-9]/', '', $phone_number);
		if(strlen($phone) == 7) {
			return preg_replace( "/(\d{3})(\d{4})/", "$1-$2", $phone );
		} elseif(strlen($phone) == 10) {
			return preg_replace( "/(\d{3})(\d{3})(\d{4})/", "($1) $2-$3", $phone );
		} else {
			return $phone;
		}
	}

	public static function mailto($address_list, $limit = 1) {
		$mailtos = array();
		$count = 1;
		$addresses = explode(',', $address_list);
		foreach ($addresses as $address) {
			$address = trim($address);
			$mailtos[] = '<a href="mailto:' . $address . '">' . $address . '</a>';
			if ($count >= $limit) {
				break;
			}
			$count++;
		}
		return implode(', ', $mailtos);
	}

	public static function ordinal($number) {
		if ($number < 1) {
			return '';
		}
		$ends = array('th','st','nd','rd','th','th','th','th','th','th');
		if ((($number % 100) >= 11) && (($number%100) <= 13)) {
			return $number . 'th';
		} else {
			return $number. $ends[$number % 10];
		}
	}

	public static function yesno($int_or_bool) {
		return ($int_or_bool) ? 'Yes' : 'No';
	}
}