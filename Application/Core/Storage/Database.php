<?php

/**
 * Handles the connection and query runners for databases.
 *
 * Copyright (C) 2018 MVC Framework.
 * This file included in MVC Framework is licensed under OSL 3.0
 *
 * http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * Please see LICENSE.txt for the full text of the OSL 3.0 license
 */

namespace Application\Core\Storage;

class Database
{
    /**
     * The connection to the database.
     *
     * @access protected
     * @var    \PDO
     */
    protected $_connection;

    /**
     * The query that we have just run.
     *
     * @access protected
     * @var    \PDOStatement
     */
    protected $_statement;

    /**
     * Connect to the database if we have not already.
     *
     * @access private
     */
    private function connect()
    {
        // Get the connection details
        $host     = Handler::get('DB_HOST');
        $database = Handler::get('DB_NAME');
        $username = Handler::get('DB_USER');
        $password = Handler::get('DB_PASS');
        $charset  = Handler::get('DB_CHAR');

        try {
            // Connect to the database
            $this->_connection = new \PDO(
                "mysql:host={$host};dbname={$database};charset={$charset}", $username, $password
            );
        }
        catch (\PDOException $e) {
            die('Sorry, we were unable to complete your request.');
        }
    }

    /**
     * Execute an SQL statement on the database.
     *
     * @access protected
     * @param  string    $sql   The SQL statement to run.
     * @param  array     $data  The data to pass into the prepared statement.
     * @param  boolean   $reset Whether we should reset the model data.
     * @return boolean
     */
    protected function run($sql, $data = array(), $reset = true)
    {
        // If we do not have a connection then establish one
        if (!$this->_connection) {
            $this->connect();
        }

        // Prepare, execute, reset, and return the outcome
        $this->_statement = $this->_connection->prepare($sql);
        $result           = $this->_statement->execute($data);
        if ($reset) {
            $this->reset();
        }
        return $result;
    }

}
