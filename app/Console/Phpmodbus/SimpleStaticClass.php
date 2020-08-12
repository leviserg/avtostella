<?php
//	namespace Phpmodbus;

	class SimpleStaticClass {

		public static $params = [123, 456, 789];
		public static $connected;

		private function __construct($xyz) {
			$this->param = $xyz;  
		}

		public static function connect(){
			//return self::$param;
			$connected = true;
		}

		public static function disconnect(){
			$connected = false;
		}

		public static function getvalues(){
			//$self->param++;
			foreach (self::$params as $param) {
				$param++;
			}
			return self::$params;
			//return date('Y-m-d H:i:s');
		}
	}

?>