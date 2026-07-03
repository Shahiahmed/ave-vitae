<?php

it('redirects the root url to the admin panel', function () {
    $this->get('/')->assertRedirect('/admin');
});
