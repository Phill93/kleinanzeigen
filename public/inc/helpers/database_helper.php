<?php
/**
 * Copyright (c) Daniel Bacher 2016.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software Foundation,
 *  Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301  USA
 */

class database_helper
{
    private $dsn;
    private $user;
    private $password;
    private $connection;

    function __construct($host = DB_HOST, $db = DB_DB, $user = DB_USER, $password = DB_PASS)
    {
        $this->dsn = $this->generateDSN("mysql", $db, $host);
        $this->user = $user;
        $this->password = $password;
        $this->connect();
    }

    private function generateDSN($type, $db, $host)
    {
        return $type . ":dbname=" . $db . ";host=" . $host;
    }

    private function connect(){
        $this->connection = new PDO($this->dsn, $this->user, $this->password);
        $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->connection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $this->connection->exec("SET CHARACTER SET utf8");
    }

    /**
     * @return PDO
     */
    public function getConnection()
    {
        return $this->connection;
    }
}
