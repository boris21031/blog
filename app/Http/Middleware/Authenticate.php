<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Auth;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param \Illuminate\Http\Request $request
     * @param Closure $next
     * @param ...$guards
     * @return string|null
     */
    public function handle($request, Closure $next, ...$guards): ?string
    {
        // Извлекаем токен из заголовка
        $apiToken = $request->header('Authorization');
//        dd($apiToken);

        // Проверяем, что токен существует и начинается с "Bearer "
        if ($apiToken && strpos($apiToken, 'Bearer ') === 0) {
            $apiToken = substr($apiToken, 7); // Убираем "Bearer "

            // Пытаемся найти пользователя по токену
            $user = User::where('api_token', $apiToken)->first();

            if ($user) {
                // Авторизуем пользователя
                Auth::login($user);
                return $next($request);
            }
        }

        // Если пользователь не авторизован, возвращаем ответ с ошибкой
        return response()->json(['error' => 'Unauthorized'], 401);
    }
}
