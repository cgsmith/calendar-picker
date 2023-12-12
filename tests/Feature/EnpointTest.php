<?php

it('has /admin/login', function () {
    $this->get('/admin/login')->assertStatus(200);
});

it('/admin redirects to login', function () {
    $this->get('/admin')->assertStatus(302)->assertRedirectToRoute('filament.admin.auth.login');
});

it('confirm page has questions', function () {
    $this->get('/appointment/service/1/1/1703458800/confirm')
        ->assertStatus(200);
});

