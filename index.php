<?php
// Check for the URL get paramter
if ( !empty( $_GET['url'] ) ) {
    $url = $_GET['url'];
    // Set the JSON header
    header( 'Content-Type: application/json' );
    // Make sure the URL is valid
    if ( !filter_var( $url, FILTER_VALIDATE_URL ) ) {
	die( json_encode( array(
	    'url' => $url,
	    'error' => TRUE
	) ) );
    }
    
    // Get the HTTP code
    $ch = curl_init( $url );
    // Get the headers
    curl_setopt( $ch, CURLOPT_HEADER, TRUE );
    // Don't get the body
    curl_setopt( $ch, CURLOPT_NOBODY, TRUE );
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
    // Time out for the HTTP request
    curl_setopt( $ch, CURLOPT_TIMEOUT, 10 );
    // Get the HTTP code
    $output = curl_exec( $ch );
    $httpStatusCode = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
    curl_close( $ch );
    
    // Return the status and terminate
    die( json_encode( array(
	'url' => $url,
	'httpStatusCode' => $httpStatusCode
    ) ) );
}
?>
<!DOCTYPE html>
<html>
    <head>
	<title>Website Status Checker</title>
	<!-- Materialize -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
	<script>
	 function setStatus( statusInfo ) {
	     let statusElem = document.getElementById( "status" );
	     statusElem.innerHTML = statusInfo;
	 }
	 
	 function checkStatus() {
	     // Get the URL to check
	     const toCheck = document.getElementById( "url" ).value;
	     // Get the URL to request
	     let searchParams = new URLSearchParams();
	     searchParams.set( "url", toCheck );
	     let url = window.location.href.split( "?" )[0] + "?" + searchParams.toString();
	     // Request the status asynchronously
	     fetch( url ).then( function( response ) {
		 response.json().then( function( data ) {
		     // Check for an error
		     if ( data.hasOwnProperty( "error" ) ) {
			 setStatus( '<strong class="red-text">Invalid URL.</strong>' );
		     } else {
			 // Check the status code
			 const statusCode = data["httpStatusCode"];
			 switch( statusCode ) {
			     case 200:
				 setStatus( '<strong class="green-text">' + toCheck + ' is up!</strong>' );
				 break;
			     case 301:
			     case 302:
				 setStatus( '<strong class="grey-text">' + toCheck + ' is redirecting.</strong>' );
				 break;
			     default:
				 setStatus( '<strong class="red-text">' + toCheck + ' is down!</strong>' );
				 break;
			 }
		     }
		 } )
		 }
	     )
	     return false;
	 }
	</script>
    </head>
    <body>
	<div class="container">
	    <p></p>
	    <form action="#" onclick="checkStatus(); return false;">
		<div class="input-field">
		    <input id="url" name="url" type="text" required>
		    <label for="url">URL</label>
		</div>
		<input type="submit" class="btn">
	    </form>
	    <p id="status"></p>
	</div>
    </body>
</html>
