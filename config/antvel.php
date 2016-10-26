<?php

return [

    /*
     * --------------------------------------------------------------------------
     * Path to the database directory
     * --------------------------------------------------------------------------
     *
     * This option determines the path to the database directory. It's where
     * the package will be looking for migrations, seeds and migrations
     * files.
     */
    'database_path' => realpath(base_path('database')),

    /*
     * --------------------------------------------------------------------------
     * Path to the resources directory
     * --------------------------------------------------------------------------
     *
     * This option determines the path to the resources directory. It's where
     * the package will be looking for resources files.
     */
    'resources_path' => realpath(base_path('resources'))

];