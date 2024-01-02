<?php

it('has /admin/login', function () {
    $this->get('/admin/login')->assertStatus(200);
});

it('/admin redirects to login', function () {
    $this->get('/admin')->assertStatus(302)->assertRedirectToRoute('filament.admin.auth.login');
});

it('confirm page has questions', function () {
    $this->get('/service/1/user/1/time/1703458800/confirm')
        ->assertStatus(200);
});
