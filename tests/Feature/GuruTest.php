<?php

use App\Models\User;

it('can view the guru index page', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->get('/guru');

    $response->assertOk()
        ->assertSee('Data Guru');
});
