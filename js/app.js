var notesModule = angular.module('notes', []);

var BASE_URL = '/hello'; // e.g.

// e.g. one controller app, but could easily split the editor into its own, and keep data cached in the api service
notesModule.controller('NotesListController', ['$scope', 'notesApi', function NotesListController($scope, notesApi) {
	$scope.notesList = {null: {
		id: null,
		title: 'Loading list...',
		ownerName: "Default",
		contents: 'loading',
		reminder: null,
		updated: 'loading',
		created: 'loading'
	}};
	$scope.ownerName = 'Default';

	$scope.refreshList = function() {
		console.log('x');
		notesApi.fetchList($scope.ownerName).then(function(data) {
			$scope.notesList = data;
			console.log(data);
		});	
	};
	$scope.refreshList();

	$scope.newNote = function() {
		$scope.editorNote = {
			id: null,
			title: 'Untitled',
			ownerName: $scope.ownerName,
			contents: '',
			reminder: ''
		};
	}
	$scope.newNote();

	$scope.editNote = function(id) {

		if (!id) { // should be positive int or str
			return;
		}

		// load note with that id, if there

		if ($scope.notesList[id]) {
			$scope.editorNote = $scope.notesList[id];
		}
	}

	$scope.saveNote = function() {
		// validate client-side

		// could use html form validation in here too probably, instead of checking easy stuff

		var note = $scope.editorNote;
		console.log(note);

		if (!note.title || note.title.length < 1) {
			alert('Title is required');
			return false;
		}

		if (!note.contents || note.contents.length < 1) {
			alert('Contents are required');
			return false;
		}

		// reminder should be a valid datetime-local. the element should enforce.
		// for the purposes of an example project, no
		console.log('saveNote call');
		notesApi.saveNote(note).then(function(result) {
			console.log('saveNote cb');
			if (result) {
				// placeholder, in a big system you could just add the new data manually instead of a full refresh
				$scope.refreshList();
				$scope.newNote();
			}
		});
		return true;

	}
}])
.factory('notesApi', ['$http', function($http) {
	var api = {}

	api.fetchList = function(ownerName) {
		// for the sake of example, passing ownerName here instead of using auth
		// console.log('fetching list',ownerName)
		return $http.get(BASE_URL+'/notes.php?endpoint=list&ownerName='+ownerName).then(function(resp) {
			console.log('debug',resp);
			return resp.data;
		}, function(resp) {
			// handle error per UX paradigm
			console.log('got error',resp);
			return [];
		});
	};
	api.saveNote = function(data) {
		//data = $.param(data);
		return $http.post(BASE_URL+'/notes.php?endpoint=save', data).then(function(resp) {
			console.log('debug',resp);
			return resp.data;
		}, function(resp) {
			// handle error per UX paradigm
			console.log('got error',resp);
			return false;
		});
	};
	api.fetchNote = function(id) {
		console.log('unimplemented');
	};
	return api;
}]);
