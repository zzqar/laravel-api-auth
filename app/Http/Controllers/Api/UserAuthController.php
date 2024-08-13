<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRegisterRequest;
use Illuminate\Http\JsonResponse;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use OpenApi\Attributes as OA;

class UserAuthController extends Controller
{
    #[OA\Post(
        path: '/api/register',
        summary: 'Регистрация нового пользователя',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['name', 'email', 'password', 'password_confirmation'],
                properties: [
                    new OA\Property(property: 'name', type: 'string', example: 'John'),
                    new OA\Property(property: 'email', type: 'string', example: 'john@gmail.com'),
                    new OA\Property(property: 'password', type: 'string', format: 'password', example: '123456'),
                    new OA\Property(property: 'password_confirmation', type: 'string', format: 'password', example: '123456'),
                ]
            )
        ),
        tags: ['User'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Успешная регистрация',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'integer', example: 200),
                        new OA\Property(property: 'message', type: 'string', example: 'успешно'),
                    ]
                )
            ),
            new OA\Response(
                response: 400,
                description: 'Ошибка валидации',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'integer', example: 400),
                        new OA\Property(property: 'message', type: 'string', example: 'Ошибка валидации'),
                    ]
                )
            )
        ]
    )]
    public function register(UserRegisterRequest $request): JsonResponse
    {
        User::create($request->all());

        return response()->json([
            'status' => 200,
            'message' => 'успешно',
        ]);
    }

    #[OA\Post(
        path: '/api/login',
        summary: 'Аутентификация пользователя и возврат JWT token',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'email', type: 'string', example: 'john@gmail.com'),
                    new OA\Property(property: 'password', type: 'string', format: 'password', example: '123456'),


                ]
            )
        ),
        tags: ['User'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Успешная аутентификация',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'boolean', example: true),
                        new OA\Property(property: 'message', type: 'string', example: 'Пользователь успешно вошел в систему'),
                        new OA\Property(property: 'token', type: 'string', example: 'eyJhbGciOiJIUzI1NiICI6IkpXVCJ9...')
                    ]
                )
            ),
            new OA\Response(
                response: 422,
                description: 'Неверные данные для входа',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'boolean', example: false),
                        new OA\Property(property: 'message', type: 'string', example: 'Неверные данные для входа'),
                    ]
                )
            )
        ]
    )]
    public function login(UserLoginRequest $request): JsonResponse
    {
        // JWTAuth
        $token = JWTAuth::attempt([
            'email' => $request->email,
            'password' => $request->password
        ]);

        if (!empty($token)) {

            return response()->json([
                'status' => true,
                'message' => 'Пользователь успешно вошел в систему',
                'token' => $token
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'Неверные данные'
        ], 401);
    }

    #[OA\Get(
        path: '/api/show',
        summary: 'Получает данные профиля аутентифицированного пользователя',
        security: [['BearerAuth' => []]],
        tags: ['User'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Пользователь получен',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'boolean', example: true),
                        new OA\Property(property: 'message', type: 'string', example: 'Данные профиля'),
                        new OA\Property(
                            property: 'data',
                            type: 'object',
                            example: [
                                'id' => 1,
                                'name' => 'John',
                                'email' => 'john.doe@example.com',
                            ]
                        ),
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: 'Неавторизован',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'boolean', example: false),
                        new OA\Property(property: 'message', type: 'string', example: 'Неавторизован'),
                    ]
                )
            )
        ]
    )]
    public function show(): JsonResponse
    {
        $userdata = auth()->user();

        return response()->json([
            'status' => true,
            'message' => 'Пользователь получен',
            'data' => $userdata
        ]);
    }

    #[OA\Get(
        path: '/api/logout',
        summary: 'Выход из системы',
        security: [['BearerAuth' => []]],
        tags: ['User'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Успешный выход из системы',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'boolean', example: true),
                        new OA\Property(property: 'message', type: 'string', example: 'Пользователь успешно вышел из системы'),
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: 'Неавторизован',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'boolean', example: false),
                        new OA\Property(property: 'message', type: 'string', example: 'Неавторизован'),
                    ]
                )
            )
        ]
    )]
    public function logout(): JsonResponse
    {
        auth()->logout();
        return response()->json([
            'status' => true,
            'message' => 'Успешный выход из системы'
        ]);
    }
}
