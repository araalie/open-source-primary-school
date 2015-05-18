<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

if (!function_exists('valueOrBlank')) {
	function valueOrBlank($val) {
		if (isset($val) && $val != '' && !is_null($val)) {
			return $val;
		}
		return '';
	}

}

if (!function_exists('GetAppVariable')) {
	function GetAppVariable($key) {
		$CI = &get_instance();

		$data = $CI -> cache -> get($key);

		if (is_null($data) || $data == FALSE) {
			$var = $CI -> doctrine -> em -> getRepository('Entities\AppOption') -> findOneBy(array('key_name' => $key));
			$CI -> cache -> write($var, $key, 60 * 24);
			$data = $var;
		}

		if (is_null($data)) {
			return '';
		}

		return $data -> getValue();
	}

}

if (!function_exists('WriteLog')) {

	function WriteLog($type, $objectType, $objectId, $narrative) {

		ResetEM();
		// if em is closed, reopen

		$CI = &get_instance();

		$log = new Entities\Log;

		$username = $CI -> session -> userdata('username');
		if (strlen($username) < 2) {
			$username = 'system';
		}
		$log -> setUsername($username);
		$log -> setType($type);
		$log -> setObjectId(is_null($objectId) ? -1 : $objectId);
		$log -> setObjectType($objectType);
		$log -> setClientIpAddress(get_ip_address());

		if (strlen($narrative) > 512) {
			$log -> setNarrative(substr($narrative, 0, 510));
		} else {
			$log -> setNarrative($narrative);
		}

		$log -> setDateCreated(new DateTime());

		$CI -> doctrine -> em -> persist($log);

		try {
			$CI -> doctrine -> em -> flush();
		} catch( \PDOException $e ) {
			//echo $e->getMessage();
		}
	}

	function get_ip_address() {
		foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key) {
			if (array_key_exists($key, $_SERVER) === true) {
				foreach (explode(',', $_SERVER[$key]) as $ip) {
					if (filter_var($ip, FILTER_VALIDATE_IP) !== false) {
						return $ip;
					}
				}
			}
		}
	}

}

if (!function_exists('makeRandomDoB')) {
	function makeRandomDoB($startDate) {
		$n = rand(0, 367);
		return date("Y-m-d", strtotime("$startDate + $n days"));
	}

}

if (!function_exists('compare_surname')) {
	function compare_surname($a, $b) {
		return strnatcmp($a['surname'], $b['surname']);
	}

}

if (!function_exists('hex2rgb')) {
	function hex2rgb($hex) {
		$hex = str_replace("#", "", $hex);

		if (strlen($hex) == 3) {
			$r = hexdec(substr($hex, 0, 1) . substr($hex, 0, 1));
			$g = hexdec(substr($hex, 1, 1) . substr($hex, 1, 1));
			$b = hexdec(substr($hex, 2, 1) . substr($hex, 2, 1));
		} else {
			$r = hexdec(substr($hex, 0, 2));
			$g = hexdec(substr($hex, 2, 2));
			$b = hexdec(substr($hex, 4, 2));
		}
		$rgb = array($r, $g, $b);
		//return implode(",", $rgb); // returns the rgb values separated by commas
		return $rgb;
		// returns an array with the rgb values
	}

}

if (!function_exists('remove_non_numeric')) {
	function remove_non_numeric($string) {
		return preg_replace('/\D/', '', $string);
	}

}

if (!function_exists('GroupByKeySummary')) {
	function GroupByKeySummary($array, $key) {

		$groups = array();

		foreach ($array as $item) {
			$groups[$item[$key]][] = $item;
		}

		$total = count($array);

		$summary = array();

		foreach ($groups as $value => $items) {
			$summary[$value] = array('number' => count($items), 'pct' => number_format(100 * count($items) / $total, 0));
		}

		return $summary;
	}

}

if (!function_exists('ResetEM')) {
	function ResetEM() {
		$CI = &get_instance();

		if (!$CI -> doctrine -> em -> isOpen()) {
			$CI -> doctrine = new Doctrine();
		}

	}

}

if (!class_exists('Utilities')) {

	class Utilities {

		public static function getCurrentTerm($reset = FALSE) {
			$key = 'current_term';

			$CI = &get_instance();

			$term = NULL;
			// $CI->cache->get($key);

			if (is_null($term) || $reset) {

				$queryBuilder = $CI -> doctrine -> em -> createQueryBuilder();

				$queryBuilder -> select('t') -> from('Entities\Term', 't') -> innerJoin('t.term_status', 's') -> where('s.name=:status') -> setParameter('status', 'IN_PROGRESS');

				$query = $queryBuilder -> getQuery();

				$term = $query -> getResult();

				if (!is_null($term) && count($term) == 1) {
					//$CI->cache->write($term[0], $key, 60*24);
				}

			}

			if (is_null($term) || count($term) == 0) {
				return NULL;
			}

			return $term[0];
		}

		public static function getShortClassName($longName) {

			if (is_null($longName) || $longName == '') {
				return '';
			}

			if (stripos($longName, "BABY") !== FALSE) {
				return "BABY";
			} else if (stripos($longName, "MIDDLE") !== FALSE) {
				return "MIDDLE";
			}
			if (stripos($longName, "TOP") !== FALSE) {
				return "TOP";
			} else if (stripos($longName, "ONE") !== FALSE) {
				return "P1";
			} else if (stripos($longName, "TWO") !== FALSE) {
				return "P2";
			} else if (stripos($longName, "THREE") !== FALSE) {
				return "P3";
			} else if (stripos($longName, "FOUR") !== FALSE) {
				return "P4";
			} else if (stripos($longName, "FIVE") !== FALSE) {
				return "P5";
			} else if (stripos($longName, "SIX") !== FALSE) {
				return "P6";
			} else if (stripos($longName, "SEVEN") !== FALSE) {
				return "P7";
			} else {
				return $longName;
			}

		}

		public static function getCurrencyShortName() {
			return 'UGX';
		}

		public static function getLeanName($longName) {
			if (is_null($longName) || $longName == '') {
				return '';
			}

			$parts = explode('-', $longName);

			if (count($parts) > 0) {
				$first = $parts[0];

				return trim($first, ' ][');
			}

			return $longName;

		}

		function clean_string($input, $replace = '-') {
			//make it lowercase, remove punctuation, remove multiple/leading/ending spaces

			$str = trim(ereg_replace(' +', ' ', preg_replace('/[^a-zA-Z0-9\s]/', '', strtolower($input))));

			return str_replace(' ', $replace, $str);
		}

	}

}

//print_r(glob('./'.$dir.'/report*'));
?>