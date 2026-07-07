<?php

it('shows the login page with Kazakh and Chinese labels', function () {
    $this->get('/admin/login')
        ->assertOk()
        ->assertSee('Жүйеге кіру')
        ->assertSee('登录系统')
        ->assertSee('Электрондық пошта')
        ->assertSee('电子邮箱')
        ->assertSee('Құпиясөз')
        ->assertSee('Кіру');
});
