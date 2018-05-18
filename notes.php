<?php namespace Notes;

include 'note.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);


// i don't know what the PHP way of routing like this is, so just using the query variable for sake of example
if (isset($request->title)) {
	// attempt to save

	// probably a better way of creating the object from input here
	$note = new Note($request->id, $request->title, $request->contents, $request->ownerName, $request->reminder);

	$note->save();

	echo('{"success":"true"}');
} else if ($_GET['endpoint'] == 'list') { // not using $request-> on GET method
	$output = Note::getList('Default');

	$json_out = json_encode($output, JSON_FORCE_OBJECT);

	echo($json_out);
} else {
	// log info if needed
	
	// catch all, meh
	echo('{"success":"false","error":"bad endpoint or data"}');
}
?>