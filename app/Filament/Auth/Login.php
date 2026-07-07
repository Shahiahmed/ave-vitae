<?php

namespace App\Filament\Auth;

use Filament\Actions\Action;
use Filament\Auth\Pages\Login as BaseLogin;
use Filament\Schemas\Components\Component;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\HtmlString;

class Login extends BaseLogin
{
    public function getHeading(): string | Htmlable
    {
        return new HtmlString(
            'Жүйеге кіру <span class="text-base font-normal text-gray-400 dark:text-gray-500">登录系统</span>'
        );
    }

    protected function getEmailFormComponent(): Component
    {
        return parent::getEmailFormComponent()
            ->label($this->bilingual('Электрондық пошта', '电子邮箱'));
    }

    protected function getPasswordFormComponent(): Component
    {
        return parent::getPasswordFormComponent()
            ->label($this->bilingual('Құпиясөз', '密码'));
    }

    protected function getRememberFormComponent(): Component
    {
        return parent::getRememberFormComponent()
            ->label($this->bilingual('Мені есте сақтау', '记住我'));
    }

    protected function getAuthenticateFormAction(): Action
    {
        return parent::getAuthenticateFormAction()
            ->label($this->bilingual('Кіру', '登录'));
    }

    /**
     * Казахская подпись + мелкая китайская рядом.
     */
    protected function bilingual(string $kk, string $zh): HtmlString
    {
        return new HtmlString(
            e($kk).' <span class="text-xs font-normal text-gray-400 dark:text-gray-500">'.e($zh).'</span>'
        );
    }
}
