<?php

test('the application returns a successful response', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
});

test('test test', function () {
    $this->assertTrue(true);
});
