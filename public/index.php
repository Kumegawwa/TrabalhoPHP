<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../helpers/csrf.php'; // Se você tem um helper CSRF

// Autoload para Models e Controllers (simplificado)
spl_autoload_register(function ($class_name) {
    $controller_file = __DIR__ . '/../app/controllers/' . $class_name . '.php';
    $model_file = __DIR__ . '/../app/models/' . $class_name . '.php';

    if (file_exists($controller_file)) {
        require_once $controller_file;
    } elseif (file_exists($model_file)) {
        require_once $model_file;
    }
});

// Define uma constante para a URL base do projeto
// Isso é crucial para links e redirecionamentos
// Ajuste '/TrabalhoPHP' se o nome da sua pasta for diferente
define('BASE_URL', '/TrabalhoPHP');

// Roteamento básico
$request_uri = $_SERVER['REQUEST_URI'];
$base_path = BASE_URL;

// Remove o base_path da URI para obter a rota pura
if (strpos($request_uri, $base_path) === 0) {
    $route = substr($request_uri, strlen($base_path));
} else {
    $route = $request_uri;
}

// Remove query string da rota, se houver
$route = strtok($route, '?');
// Garante que a rota comece com / e remove a barra final se não for a raiz
$route = '/' . trim($route, '/');


// Definição das rotas
$routes = [
    // Rotas GET
    'GET' => [
        '/' => ['SiteController', 'home'], // Página inicial antes do login
        '/home' => ['SiteController', 'home'], // Página inicial após login (requer auth)
        '/login' => ['AuthController', 'login'],
        '/logout' => ['AuthController', 'logout'],
        '/cursos' => ['CursoController', 'index'],
        '/cursos/create' => ['CursoController', 'create'], // Exibe formulário (requer auth professor)
        '/cursos/show/{id}' => ['CursoController', 'show'], // Placeholder para mostrar curso específico
        '/materiais/create/{curso_id}' => ['MaterialController', 'create'], // Exibe formulário (requer auth professor)
        '/sobre' => ['SiteController', 'sobre'],
        '/lista-cursos' => ['SiteController', 'listaCursosPublicos'],
        // Adicione outras rotas GET aqui
    ],
    // Rotas POST
    'POST' => [
        '/login' => ['AuthController', 'processLogin'],
        '/cursos/store' => ['CursoController', 'store'], // Salva novo curso (requer auth professor)
        '/materiais/store' => ['MaterialController', 'store'], // Salva novo material (requer auth professor)
        // Adicione outras rotas POST aqui
    ]
];

$method = $_SERVER['REQUEST_METHOD'];
$controllerName = null;
$actionName = null;
$params = [];

if (isset($routes[$method])) {
    foreach ($routes[$method] as $routePattern => $handler) {
        // Lógica para rotas com parâmetros (simplificada)
        $pattern = preg_replace('/\{[a-zA-Z0-9_]+\}/', '([a-zA-Z0-9_]+)', $routePattern);
        if (preg_match('#^' . $pattern . '$#', $route, $matches)) {
            array_shift($matches); // Remove a string completa correspondente
            $params = $matches;
            $controllerName = $handler[0];
            $actionName = $handler[1];
            break;
        }
    }
}

if ($controllerName && $actionName) {
    if (class_exists($controllerName)) {
        $controllerInstance = new $controllerName();
        if (method_exists($controllerInstance, $actionName)) {
            // Verificar autenticação para rotas protegidas (exemplo básico)
            $protectedRoutes = [
                'SiteController@home', // A home após login
                'CursoController@create',
                'CursoController@store',
                'MaterialController@create',
                'MaterialController@store',
                'AuthController@logout'
            ];
            $currentHandler = $controllerName . '@' . $actionName;

            if (in_array($currentHandler, $protectedRoutes) && !isset($_SESSION['usuario_id'])) {
                header('Location: ' . BASE_URL . '/login');
                exit;
            }
            
            // Validar CSRF para POST (se o helper estiver configurado)
            if ($method === 'POST' && function_exists('verifyCsrfToken')) {
                if (!verifyCsrfToken()) {
                    die('Falha na validação CSRF.');
                }
            }

            call_user_func_array([$controllerInstance, $actionName], $params);
        } else {
            http_response_code(404);
            echo "Erro 404: Método {$actionName} não encontrado no controller {$controllerName}.";
        }
    } else {
        http_response_code(404);
        echo "Erro 404: Controller {$controllerName} não encontrado.";
    }
} else {
    http_response_code(404);
    echo "Erro 404: Rota não encontrada para {$method} {$route}.";
}