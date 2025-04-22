<?php

declare(strict_types=1);

use Honed\Page\Page;

it('has root page', function () {
    expect(new Page('Index.vue'))
        ->getName()->toBe('Index')
        ->getPath()->toBe('Index')
        ->getUri()->toBe('index')
        ->getRouteName()->toBe('index');
});

it('has nested page', function () {
    expect(new Page('Products/Product.vue'))
        ->getName()->toBe('Product')
        ->getPath()->toBe('Products/Product')
        ->getUri()->toBe('products/product')
        ->getRouteName()->toBe('products.product');
});
