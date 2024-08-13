<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use OpenApi\Attributes as OA;

#[OA\PathItem(
    path: "/api/",
)]
#[OA\Info(
    version: "1.0.0",
    title: "API User Authentication",

)]
#[OA\SecurityScheme(
    securityScheme: 'BearerAuth',
    type: 'http',
    description: 'JWT Authorization header using the Bearer scheme.',
    bearerFormat: 'JWT',
    scheme: 'bearer'
)]
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
