<?php

/*
 * This file is part of the Antvel Shop package.
 *
 * (c) Gustavo Ocanto <gustavoocanto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Antvel\Tests;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    /**
     * Contains the database schema information.
     *
     * @var array
     */
    protected $schema = null;

    /**
     * Create a new Invitations instance.
     *
     * @return  void
     */
    public function __construct()
    {
        $this->schema = require(__DIR__ . '/config/database.php');
    }

    /**
     * Setup the test environment
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $this->loadMigrations();
    }

    /**
     * Load the database migrations.
     *
     * @return void
     */
    protected function loadMigrations()
    {
        $this->artisan('migrate', [
            '--database' => $this->schema['database'],
            '--realpath' => __DIR__ . '/../database/migrations',
        ]);
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', $this->schema['database']);
        $app['config']->set('database.connections.antvel_testing', $this->schema);
    }

    /**
     * Get package service providers.
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            \Antvel\AntvelServiceProvider::class
        ];
    }
}