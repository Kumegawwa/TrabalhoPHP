<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Configuração e Autoloader
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../helpers/csrf.php';

spl_autoload_register(function ($class_name) {
    $paths = [
        __DIR__ . '/../app/controllers/' . $class_name . '.php',
        __DIR__ . '/../app/models/' . $class_name . '.php'
    ];
    foreach ($paths as $file) {
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// Constante para a URL base
define('BASE_URL', '/TrabalhoPHP');

// Análise da Rota
$request_uri = $_SERVER['REQUEST_URI'];
$base_path = str_replace('/public/index.php', '', $_SERVER['SCRIPT_NAME']);
$route = '/' . trim(str_replace($base_path, '', strtok($request_uri, '?')), '/');

// Rotas da Aplicação
$routes = [
    // GET
    'GET' => [
        '/' => ['SiteController', 'home'],
        '/home' => ['SiteController', 'home'],
        '/sobre' => ['SiteController', 'sobre'],
        '/lista-cursos' => ['SiteController', 'listaCursosPublicos'],
        '/login' => ['AuthController', 'login'],
        '/logout' => ['AuthController', 'logout'],
        '/register' => ['UsuarioController', 'create'],
        '/recuperar-senha' => ['AuthController', 'showRecoverForm'],
        '/dashboard' => ['DashboardController', 'index'],
        '/cursos' => ['CursoController', 'index'],
        '/cursos/create' => ['CursoController', 'create'],
        '/cursos/show/{id:[0-9]+}' => ['CursoController', 'show'],
        '/cursos/edit/{id:[0-9]+}' => ['CursoController', 'edit'],
        '/cursos/enroll/{curso_id:[0-9]+}' => ['CursoController', 'enroll'],
        '/materiais/create/{curso_id:[0-9]+}' => ['MaterialController', 'create'],
    ],
    // POST
    'POST' => [
        '/login' => ['AuthController', 'processLogin'],
        '/register' => ['UsuarioController', 'store'],
        '/recuperar-senha' => ['AuthController', 'processRecovery'],
        '/cursos/store' => ['CursoController', 'store'],
        '/cursos/join' => ['CursoController', 'joinByCode'],
        '/cursos/update/{id:[0-9]+}' => ['CursoController', 'update'],
        '/cursos/delete/{id:[0-9]+}' => ['CursoController', 'delete'],
        '/materiais/store' => ['MaterialController', 'store'],
    ]
];

// Funções de Proteção de Rota
function isProtectedRoute($controller, $action) {
    $protected = [
        'DashboardController@index',
        'CursoController@create', 'CursoController@store', 'CursoController@show', 
        'CursoController@edit', 'CursoController@update', 'CursoController@delete', 
        'CursoController@enroll', 'CursoController@joinByCode',
        'MaterialController@create', 'MaterialController@store',
        'AuthController@logout'
    ];
    return in_array($controller . '@' . $action, $protected);
}

function isProfessorRoute($controller, $action) {
    $profRoutes = [
        'CursoController@create', 'CursoController@store', 'CursoController@edit', 
        'CursoController@update', 'CursoController@delete',
        'MaterialController@create', 'MaterialController@store'
    ];
    return in_array($controller . '@' . $action, $profRoutes);
}

// Lógica de Roteamento
$method = $_SERVER['REQUEST_METHOD'];
if ($method === 'POST' && isset($_POST['_method']) && in_array(strtoupper($_POST['_method']), ['PUT', 'DELETE'])) {
    $method = strtoupper($_POST['_method']);
}

$controllerName = null;
$actionName = null;
$params = [];

if (isset($routes[$method])) {
    foreach ($routes[$method] as $routePattern => $handler) {
        $pattern = preg_replace_callback('/\{([a-zA-Z0-9_]+):([^\}]+)\}/', function($m) { return '(?P<'.$m[1].'>'.$m[2].')'; }, $routePattern);
        $pattern = '#^' . str_replace('/', '\/', $pattern) . '$#';

        if (preg_match($pattern, $route, $matches)) {
            $controllerName = $handler[0];
            $actionName = $handler[1];
            foreach ($matches as $key => $value) {
                if (is_string($key)) {
                    $params[] = $value;
                }
            }
            break;
        }
    }
}

// Despacho da Rota
if ($controllerName && $actionName) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !verifyCsrfToken()) {
        $_SESSION['error_message'] = 'Falha de segurança (CSRF). Tente novamente.';
        header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? BASE_URL . '/'));
        exit;
    }

    if (isProtectedRoute($controllerName, $actionName) && !isset($_SESSION['usuario_id'])) {
        $_SESSION['error_message'] = 'Você precisa estar logado para acessar esta página.';
        header('Location: ' . BASE_URL . '/login');
        exit;
    }

    if (isProfessorRoute($controllerName, $actionName) && (!isset($_SESSION['perfil']) || $_SESSION['perfil'] !== 'professor')) {
        $_SESSION['error_message'] = 'Acesso negado. Apenas professores podem realizar esta ação.';
        header('Location: ' . BASE_URL . '/dashboard');
        exit;
    }

    $controllerInstance = new $controllerName();
    if (method_exists($controllerInstance, $actionName)) {
        call_user_func_array([$controllerInstance, $actionName], $params);
    } else {
        http_response_code(404); echo "Erro 404: Método não encontrado.";
    }
} else {
    http_response_code(404); echo "Erro 404: Rota não encontrada.";
}