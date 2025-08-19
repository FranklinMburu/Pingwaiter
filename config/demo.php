<?php

return [
    // Comma-separated HTTP methods to restrict for demo user
    'restrict_methods' => explode(',', env('DEMO_RESTRICT_METHODS', 'post,put,patch,delete')),

    // Comma-separated route keywords to restrict for demo user
    'restrict_keywords' => explode(',', env('DEMO_RESTRICT_KEYWORDS', 'delete,destroy,settings,admin')),
];
