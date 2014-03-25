<?php
class spherical {
	public $radius = 6371, $ki_mi = 0.621371;

	/*
	construct:
		unit - default is metric, can also use imperial
		precision - amount of decimal places in returned results
	*/
	public function __construct($unit='metric',$precision=false){
		$this->set_unit($unit);
		$this->set_precision($precision);
	}

	public function set_unit($type){
		$this->unit = (strtolower($type)=='metric' || strtolower($type)=='imperial') ? $type : 'metric';
	}

	public function set_precision($precision){
		$this->precision = $precision;
	}

	public function format_precision($inbound){
		return ($this->precision)
			? number_format($inbound,$this->precision)
			: $inbound;
	}

	public function dist_result($dist,$type=false){
		if(!$type) $type = $this->unit;
		$dist = ($type=='metric') ? $dist : $dist * $this->ki_mi;
		return $this->format_precision($dist);
	}

	//--distance calculation functions
		/*
		spherical law of cosines: 
			great-circle distance between two points calculation
			well-conditioned results down to distances as small as ~1 metre
		*/
		public function law_of_cosines($point_a,$point_b,$type=false){
			$d_lng = deg2rad($point_b[1]-$point_a[1]);
			$lat_a = deg2rad($point_a[0]);
			$lat_b = deg2rad($point_b[0]);

			$dist = acos(sin($lat_a)*sin($lat_b) + cos($lat_a)*cos($lat_b)*cos($d_lng)) * $this->radius;
			return $this->dist_result($dist,$type);
		}

		/*
		haversine:
			great-circle distance between two points calculation
			well-conditioned for numerical computation even at small distances
		*/
		public function haversine($point_a,$point_b,$type=false){
			$d_lat = deg2rad($point_b[0]-$point_a[0]);
			$d_lng = deg2rad($point_b[1]-$point_a[1]);
			$lat_a = deg2rad($point_a[0]);
			$lat_b = deg2rad($point_b[0]);

			$half_chord = (sin($d_lat/2) * sin($d_lat/2)) + (sin($d_lng/2) * sin($d_lng/2) * cos($lat_a) * cos($lat_b));
			$ang_dist = 2 * atan2(sqrt($half_chord),sqrt(1-$half_chord));
			return $this->dist_result(($this->radius*$ang_dist),$type);
		}

		/*
		equirectangular approximation:
			pythagoreans theorem
			higher performance, less accurate
		*/
		public function equirectangular($point_a,$point_b,$type=false){
			$lat_a = deg2rad($point_a[0]);
			$lat_b = deg2rad($point_b[0]);
			$lng_a = deg2rad($point_a[1]);
			$lng_b = deg2rad($point_b[1]);

			$x = ($lng_b-$lng_a) * cos(($lat_a+$lat_b)/2);
			$y = ($lat_a-$lat_b);
			$dist = sqrt($x*$x + $y*$y) * $this->radius;
			return $this->dist_result($dist,$type);
		}
	//==distance calculation functions
}
?>