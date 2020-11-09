<?session_start();

$router = Router::getInstance();

$router->addRoute('/handlers/users/auth', 'UserController', 'makeAuth', false);
$router->addRoute('/auth', 'UserController', 'showAuth', false);
$router->addRoute('/logout', 'UserController', 'logout', true);

$router->addRoute('/handlers/tasks/add', 'TodoController', 'addOrEdit', false);
$router->addRoute('/handlers/tasks/edit', 'TodoController', 'addOrEdit', false);
$router->addRoute('/handlers/tasks/delete/(id:[0-9]+)', 'TodoController', 'delete', false);
$router->addRoute('/handlers/tasks/status', 'TodoController', 'changeStatus', false);
$router->addRoute('/', 'TodoController', 'index', false);

?>