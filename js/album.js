/*
	album .js
	Author: PhotoDolo

	Contains functions that communicate with the album API
*/

//URL for member functions
var albumurl='/picnit/api/album.php';

//Request to be sent to the middleware
var request;

function createAlbum() {
	//Get user input, should be validated via html5
	var albumname = $("input#albumname").val();
	var albumdesc = $("input#albumdesc").val();

	//Gather post request data
	var params = new Array();
	params['action'] = 'createAlbum';
	params['username'] = getCookie('username');
	params['key'] = getCookie('key');
	params['name'] = albumname;
	params['description'] = albumdesc;

	//Send request
	request = picnitRequest(albumurl, params);

	//Good data, show album created
	if(request.status === 200) {
		window.location = '/picnit/profile';
	}
	//Unauthorized
	else if(request.status === 401) {
		
	}
	//Missing data
	else if(request.status === 400) {
		
	}
	//Unknown error
	else {
		
	}

	return false;
}

function deleteAlbum() {
	//Get user input, should be sent via GUI/other js call
	var albumname = $("input#albumname").val();
	
	//Gather post request data
	var params = new Array();
	params['action'] = 'deleteAlbum';
	params['username'] = getCookie('username');
	params['key'] = getCookie('key');

	//Send request
	request = picnitRequest(albumurl, params);

	//Debug purposes
	alert(request.status + "\n" + request.responseText);

	//Good data, show album created
	if(request.status === 200) {
		
	}
	//Unauthorized
	else if(request.status === 401) {
		
	}
	//Missing data
	else if(request.status === 400) {
		
	}
	//Unknown error
	else {
		
	}

	return false;
}

function getAlbums(user_id) {
	var params = new Array();
	params['action'] = 'getAlbums';
	params['username'] = getCookie('username');
	params['key'] = getCookie('key');
	params['user_id'] = user_id;

	//Send request
	request = picnitRequest(albumurl, params);

	//Good data, list of albums
	if(request.status === 200) {
		//Parse the response
		var resp = $.parseJSON(request.responseText);

		//Return the list
		return resp['list'];
	}
	//No albums
	else if(request.status === 204) {
		return null;
	}
	else {
		alert(request.status + "\n" + request.responseText);
	}

	return null;
}

function getImages(album_id) {
	//Gather post request data
	var params = new Array();
	params['action'] = 'getImages';
	params['username'] = getCookie('username');
	params['key'] = getCookie('key');
	params['album_id'] = album_id;

	//Send request
	request = picnitRequest(albumurl, params);

	//Debug purposes
	alert(request.status + "\n" + request.responseText);

	//Good data, request contains data
	if(request.status === 200) {
		return $parseJSON(request.responseText);
	}
	//Unauthorized
	else if(request.status === 401) {
		
	}
	//Missing data
	else if(request.status === 400) {
		
	}
	//Unknown error
	else {
		
	}

	return null;
}
