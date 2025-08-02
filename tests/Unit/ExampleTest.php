<?php

function hello()
{
    return 'Hello World';
}
test('that true is true', function () {

    $hell = hello();
    expect($hell)->toBe('Hello World');
});
