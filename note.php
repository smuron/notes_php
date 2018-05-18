<?php namespace Notes;

include './db.php';


// could probably go in a helper file
// makes sure a string is a string and has a (unicode length) of at least 1.
// probably generalize this to $s, $minLength, $maxLength or what have you.
function simpleStringValidate($s) {
	return (is_string($s) && mb_strlen($s) >= 1);
}

$db = new Db();

// class representing a Note, from the db.
// not sure exactly the best way of implementing - i'm sure frameworks do this in a more sensible way
// JsonSerializable makes it so we can just run json_encode on an array and return data
class Note implements \JsonSerializable {
	protected $title;
	protected $ownerName;
	protected $contents;
	protected $reminder;

	/* system */
	protected $id;
	protected $created;
	protected $updated;

	

	public function __construct($id = NULL, $title = 'Untitled', $contents = '', $owner = 'Default', $reminder = NULL) {
		if (!$this->id) {
			$this->id = $id;
		}
		if (!$this->title) {
			$this->title = $title;
		}
		if (!$this->$ownerName) {
			$this->ownerName = $owner;
		}
		if (!$this->contents) {
			$this->contents = $contents;
		}
		if (!$this->reminder) {
			$this->reminder = $reminder;
		}
	}

	public function id() {
		return $this->id;
	}

	// N.B.
	// i did a lot of on-the-fly just dumping debug into the output here, I'm sure you'd be 
	// better off using a log file or some such
	public function validate() {
		// title should not be empty / null
		if (!simpleStringValidate($this->title)) {
			//echo('title');
			//echo($this->title);
			//echo('is_str'.is_string($this->title));
			//echo('str_len'.strlen($this->title));
			return false;
		}
		// should have owner
		if (!simpleStringValidate($this->ownerName)) {
			//echo('ownerName');
			return false;
		}
		// should have contents
		if (!simpleStringValidate($this->contents)) {
			return false;
		}

		// check to see reminder is correct format or not there
		if ($this->reminder === '') {
			$this->reminder = NULL;
		}

		return true;
	}

	// get a list of Note objects for $ownerName
	public static function getList($ownerName) {
		global $db; // probably bad?

		// pagination, filtering, etc.

		// apparently large queries with fetch_object are bad

		// properly quote the ownerName to put into a query
		$ownerName = $db->quote($ownerName);

		// TODO: modify results as needed
		// we're just providing a raw query here, there's possibly a less hands-on way to approach this
		return $db->query_list("SELECT id, title, ownerName, contents, reminder, created, updated FROM Note WHERE ownerName = ${ownerName}", '\Notes\Note');
	}

	public function save() {
		global $db; // probably bad?

		if (!$this->validate()) {
			echo("debug validate failed");
			return false;
		}

		// db stuff

		// ideally you are using some sort of framework here that precludes us from
		// manually filling this out

		$title = $db->quote($this->title);
		$ownerName = $db->quote($this->ownerName);
		$contents = $db->quote($this->contents);
		if ($this->reminder == '') {
			$reminder = 'NULL';
		} else {
			// N.B. there's a timezone bug here. the browser should send a UTC timestamp, not local.
			$reminder = $db->quote(date("Y-m-d H:i:s",strtotime($this->reminder)));
		}
		
		if (isset($this->id)) {
			$id = intval($this->id);
			$result = $db->query("UPDATE Note SET title = $title, contents = $contents, ownerName = $ownerName, reminder = $reminder, updated = current_timestamp WHERE id = $id");
		} else {
			$result = $db->query("INSERT INTO Note (title, contents, ownerName, reminder) VALUES ($title, $contents, $ownerName, $reminder)");
		}
		if ($result === false) {
			echo($db->error());
		}

		// TODO: don't think i can get the inserted id from here in mysql...
		return true;
	}

	public function jsonSerialize() {
        return get_object_vars($this);
    }
}

?>