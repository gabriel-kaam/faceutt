<?php
require_once 'inc.php';

$_POST = @array_map_recursive('trim', $_POST);
$_GET = @array_map_recursive('trim', $_GET);

$query = isset($_GET['q']) ? $_GET['q'] : 'home';
$query = rtrim($query, '/');
$query = mb_strtolower($query);
$query = explode('/', $query);

try {
	$a = Controller::main($query);

	session_start();
	ob_start();
	$a->execute();
	ob_end_flush();
} catch (UnknownClass $e) {
	ob_end_clean();
	ControllerLost::newInstance()->execute($e);
} catch (Exception $e) {
	ob_end_clean();
	ControllerError::newInstance()->execute($e);
}
