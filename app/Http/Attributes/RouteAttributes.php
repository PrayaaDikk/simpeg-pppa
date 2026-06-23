<?php

namespace App\Http\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class Route {
    public function __construct(public string $prefix) {}
}

#[Attribute(Attribute::TARGET_METHOD)]
class Get {
    public function __construct(public string $uri, public ?string $name = null) {}
}

#[Attribute(Attribute::TARGET_METHOD)]
class Post {
    public function __construct(public string $uri, public ?string $name = null) {}
}

#[Attribute(Attribute::TARGET_METHOD)]
class Put {
    public function __construct(public string $uri, public ?string $name = null) {}
}

#[Attribute(Attribute::TARGET_METHOD)]
class Delete {
    public function __construct(public string $uri, public ?string $name = null) {}
}
