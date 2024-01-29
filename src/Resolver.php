<?php

namespace HighLiuk\Eloquent;

use Illuminate\Database\ConnectionResolverInterface;

/**
 * Connection Resolver
 *
 * @package HighLiuk\Eloquent
 * @author HighLiuk <hello@highliuk.fr>
 */
class Resolver implements ConnectionResolverInterface
{

    /**
     * Get a database connection instance.
     *
     * @param string $name
     *
     * @return bool|Database
     */
    public function connection($name = null)
    {
        return Connection::instance();
    }

    /**
     * Get the default connection name.
     *
     * @return string
     */
    public function getDefaultConnection()
    {

        // TODO: Implement getDefaultConnection() method.
    }

    /**
     * Set the default connection name.
     *
     * @param string $name
     *
     * @return void
     */
    public function setDefaultConnection($name)
    {
        // TODO: Implement setDefaultConnection() method.
    }
}
