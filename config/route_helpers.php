<?php

return [
    /**
     * Where should the helpers file be put?
     */
    'helpers_path' => '/bootstrap/cache/route_helpers.php',

    /**
     * Should recompilation checks be enabled? By default we'll enable them only
     * if we are in the `local` environment.
     */
    'recompilation_checks_enabled' => in_array(env('APP_ENV'), ['local'])
];
