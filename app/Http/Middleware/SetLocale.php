<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Поддерживаемые локали интерфейса.
     *
     * @var array<int, string>
     */
    public const SUPPORTED = ['kk', 'zh_CN', 'ru'];

    /**
     * Локаль по умолчанию, если пользователь ещё не выбрал язык.
     */
    public const DEFAULT = 'kk';

    public function handle(Request $request, Closure $next): Response
    {
        $locale = session('locale', self::DEFAULT);

        if (in_array($locale, self::SUPPORTED, true)) {
            app()->setLocale($locale);
        }

        return $next($request);
    }
}
