<?php
header ("Access-Control-Allow-Origin: *");
header ("Access-Control-Expose-Headers: Content-Length, X-JSON");
header ("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header ("Access-Control-Allow-Headers: *");

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';
// Require Config, Objects, and Custom Function files
require '../src/config/db.php';
require '../src/config/Res.php';
require '../src/config/Skill.php';
require '../src/config/Interest.php';
require '../src/config/Dashboard.php';
require '../src/res/JWT.php';

//Add Middleware
require '../src/middleware/authMiddleware.php';

// Require the Routes
require '../src/routes/routes.php';

$app->run();