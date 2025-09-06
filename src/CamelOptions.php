<?php

declare(strict_types=1);

namespace Ykw\Cruet;

class CamelOptions
{
    public function __construct(
        public bool $new_word,
        public string $last_char,
        public bool $first_word,
        public string $injectable_char,
        public bool $has_separator,
        public bool $inverted,
        public bool $concat_num,
    ) {}
}
