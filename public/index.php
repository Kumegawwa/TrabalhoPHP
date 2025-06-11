<?php
/**
 * Ponto de Entrada (Front-Controller) da Aplicação SGA.
 * Todas as requisições são direcionadas para este arquivo.
 */

// Garante que a sessão seja iniciada em todas as requisições.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. CARREGAMENTO DE ARQUIVOS ESSENCIAIS E CONFIGURAÇÕES
require_once __DIR__ . '/../config/database.php'; // Conexão com o banco ($pdo)
require_once __DIR__ . '/../helpers/csrf.php';      // Funções de proteção CSRF

/**
 * Autoloader simples para carregar classes de Models e Controllers automaticamente.
 */
spl_autoload_register(function ($class_name) {
    // Procura a classe na pasta de controllers (incluindo o BaseController)
    $controller_file = __DIR__ . '/../app/controllers/' . $class_name . '.php';
    if (file_exists($controller_file)) {
        require_once $controller_file;
        return;
    }

    // Procura a classe na pasta de models
    $model_file = __DIR__ . '/../app/models/' . $class_name . '.php';
    if (file_exists($model_file)) {
        require_once $model_file;
        return;
    }
});

// Define uma constante global para a URL base, facilitando a criação de links.
define('BASE_URL', '/TrabalhoPHP');

// 2. SISTEMA DE ROTEAMENTO
// Analisa a URL requisitada para determinar a rota.
$request_uri = $_SERVER['REQUEST_URI'];
$base_path = str_replace('/public/index.php', '', $_SERVER['SCRIPT_NAME']);
$route = '/' . trim(str_replace($base_path, '', strtok($request_uri, '?')), '/');

// Mapeamento de todas as rotas da aplicação para [Controller, Método]
$routes = [
    // Rotas GET (carregamento de páginas)
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
        '/materiais/create/{curso_id:[0-9]+}' => ['MaterialController', 'create'],
        '/materiais/edit/{id:[0-9]+}' => ['MaterialController', 'edit'], // ROTA ADICIONADA
    ],
    // Rotas POST (submissão de formulários)
    'POST' => [
        '/login' => ['AuthController', 'processLogin'],
        '/register' => ['UsuarioController', 'store'],
        '/recuperar-senha' => ['AuthController', 'processRecovery'],
        '/cursos/store' => ['CursoController', 'store'],
        '/cursos/join' => ['CursoController', 'joinByCode'],
        '/cursos/update/{id:[0-9]+}' => ['CursoController', 'update'],
        '/cursos/delete/{id:[0-9]+}' => ['CursoController', 'delete'],
        '/materiais/store' => ['MaterialController', 'store'],
        '/materiais/update/{id:[0-9]+}' => ['MaterialController', 'update'], // ROTA ADICIONADA
        '/materiais/delete/{id:[0-9]+}' => ['MaterialController', 'delete'], // ROTA ADICIONADA
    ]
];

// 3. LÓGICA DE DESPACHO DA ROTA (DISPATCHER)
$method = $_SERVER['REQUEST_METHOD'];
$controllerName = null;
$actionName = null;
$params = [];

// Encontra a rota correspondente na lista de rotas
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

// Executa o controller e o método correspondente
if ($controllerName && $actionName) {
    // Validação CSRF para todas as requisições POST
    if ($method === 'POST' && !verifyCsrfToken()) {
        $_SESSION['error_message'] = 'Falha de segurança (CSRF). Por favor, tente novamente.';
        header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? BASE_URL . '/'));
        exit;
    }

    if (class_exists($controllerName)) {
        // REFActoring: Injeta a conexão PDO no construtor do controller.
        $controllerInstance = new $controllerName($pdo);
        
        if (method_exists($controllerInstance, $actionName)) {
            // A lógica de segurança (checkAuth, checkProfessor) agora está DENTRO dos próprios controllers.
            call_user_func_array([$controllerInstance, $actionName], $params);
        } else {
            http_response_code(404);
            echo "Erro 404: O método '{$actionName}' não foi encontrado no controller '{$controllerName}'.";
        }
    } else {
        http_response_code(404);
        echo "Erro 404: O controller '{$controllerName}' não foi encontrado.";
    }
} else {
    // Se nenhuma rota foi encontrada, exibe erro 404.
    http_response_code(404);
    echo "Erro 404: Página não encontrada.";
}