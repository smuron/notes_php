<?php namespace Notes;

	// parts from/based on https://github.com/erangalp/database-tutorial under MIT license

	class Db {
		protected static $mysqli;

		public function connect() {

			if (!isset(self::$mysqli)) {
				/* pull config out of inline for ease of update / customization */
				// $config = parse_ini_file(path)
				self::$mysqli = new \mysqli("127.0.0.1", "phpuser", "phppassword&7", "notes");
			}

			if (self::$mysqli === false) {
				return "Failed to connect to MySQL: " . $mysqli->connect_error;
				return false;
			}

			return self::$mysqli;
		}

		public function query($q) {

			$mysqli = $this->connect();

			// caller ends up with the error

			return $mysqli->query($q);
		}

		public function error() {
			$mysqli = $this->connect();

			return $mysqli->error;
		}

		public function query_list($q, $className) {

			$results = array();

			$res = $this->query($q);

			// handle error in your preferred way
			if (is_string($res)) {
				echo 'err!',$res;
				return $results;
			}

			while ($obj = $res->fetch_object($className)) {
		        $results[$obj->id()] = $obj;
		    }

		    return $results;
		}

		public function quote($value) {
	        $connection = $this -> connect();
	        return "'" . $connection -> real_escape_string($value) . "'";
	    }
	}
?>