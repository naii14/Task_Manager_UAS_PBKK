<?php

namespace App\Http\Controllers;

use OpenApi\Attributes as OA;

#[OA\Info(
    title: "TaskStream API",
    version: "1.0.0",
    description: "Dokumentasi RESTful API untuk TaskStream - Platform Manajemen Tugas dengan Fitur Real-time.",
    contact: new OA\Contact(email: "admin@example.com")
)]
#[OA\Server(
    url: "http://localhost:8000/api",
    description: "Local Development API Server"
)]
#[OA\SecurityScheme(
    securityScheme: "bearerAuth",
    type: "http",
    scheme: "bearer",
    bearerFormat: "JWT",
    description: "Gunakan bearer access_token yang didapatkan dari login."
)]
abstract class Controller
{
    //
}
