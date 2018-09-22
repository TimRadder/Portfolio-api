<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app = new \Slim\App;


// Skill Routes
require 'skills.php';
// Experience Routes
require 'experience.php';
// Education Routes
require 'education.php';
// Interests/Hobbies Routes
require 'interests.php';
// Auth Routes
require 'auth.php';
// Admin Dashboard Route
require 'dashboard.php';

require 'playground.php';

?>