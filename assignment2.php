<?php
/*
 * A real quick example to get and update a doc.
 */
require_once('./src/Sag.php');
$firstname=$_POST['firstname'];
$lastname=$_POST['lastname'];
$school=$_POST['school'];
$discipline=$_POST['discipline'];
$sag = new Sag('127.0.0.1', '5984');
// Select the database that holds our blog's data.
$sag->setDatabase('sakib');
try {
  //Get a blog post (a StdClass)...
  $post = $sag->get('postID')->body;
  //...update its info...
  $post->views++;
  //..and send it back to the couch.
  if(!$sag->put($post->_id, $post)->body->ok) {
    error_log('Unable to log a view to CouchDB.');
  }
} 
catch(SagCouchException $e) {
  //The requested post doesn't exist - oh no!
  if($e->getCode() == "404") {
    $e = new Exception("That post doesn't exist.");
  }
  throw $e;
}
catch(SagException $e) {
  //We sent something to Sag that it didn't expect.
  error_log('Programmer error: '.$e->getMessage());
}
?>