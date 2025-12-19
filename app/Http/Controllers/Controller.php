<?php






namespace App\Http\Controllers;


/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="Web Planner API",
 *     description="API documentation for Web Planner"
 * )
 *
 * @OA\Server(
 *     url="http://localhost:8000/api",
 *     description="Local API Server"
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="sanctum",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT"
 * )
 */
abstract class Controller
{
    //
}
