<?php 
define('APP_ROOT', dirname(__DIR__));
chdir(APP_ROOT);

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use \Michelf\Markdown;
use CodeExperts\Application\User\User;

require 'vendor/autoload.php';

$app = new Application();

$app['debug'] = true;

$app['entity'] = $app->share(function(){
	 $pdo = new \PDO('mysql:host=localhost;dbname=users_api', 'root', '');
	 return new CodeExperts\Application\Entity\Entity($pdo);
});

$app->get('/', function(Application $app){
	return $app->redirect('/api/v1');
});

$app->get('/api/v1', function(Request $request) use ($app) {

	$file = file_get_contents('README.md');
	return Markdown::defaultTransform($file);

});

$app->get('/api/v1/users', function(Application $app){
	$user  = new User($app['entity']);
	$users = $user->getEntity()->getAll();

	return $app->json($users); 
});

$app->get('/api/v1/users/{id}', function(Application $app, $id){

	$user = new User($app['entity']);
	$user = $user->getEntity()->where(array('id' => $id));

	return $app->json($user);
})
->convert('id', function($id){ return (int) $id; });

$app->post('/api/v1/users', function(Application $app, Request $request){
	$data = $request->request->all();
	
	$user = array(
		'name'  => (string) $data['name'],
		'email' => (string) $data['email']
	);
	
	$save = new User($app['entity']);
	$save = $save->getEntity()->save($user);
    
    $return = array('status' => true);
	
	if(!$save) $return = array('status' => false);

	return $app->json($return);

});

$app->put('/api/v1/users', function(Application $app, Request $request){
	
	$data = $request->request->all();
	
	$user = array(
		'id'    => (int) $data['id'],
		'name'  => (string) $data['name'],
		'email' => (string) $data['email']
	);

	$update = new User($app['entity']);
	$update = $update->getEntity()->save($user);


    $return = array('status' => true);
	
	if(!$update) $return = array('status' => false);

	return $app->json($return);

});

$app->delete('/api/v1/users/{id}', function(Application $app, $id){

	$user = new User($app['entity']);
	$userDeleted = $user->getEntity()->delete($id);


    $return = array('status' => true);
	
	if(!$userDeleted) $return = array('status' => false);

	return $app->json($return);
});

$app->run();