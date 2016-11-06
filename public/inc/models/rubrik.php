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

class category
{
    function __construct()
    {
        $this->database_helper = new database_helper();
        $this->database = $this->database_helper->getConnection();
    }

    public function getcategoryList(){
        $sql = 'SELECT category.id, category.name
                    FROM category
                    ORDER BY category.id';
        try {
            $array = array();
            $query = $this->database->prepare($sql);
            $query->execute();

            while ($result = $query->fetch(PDO::FETCH_ASSOC)) {
                $array[$result['id']] = $result;
            }

            return $array;
        } catch (PDOException $e) {
            echo $e;
        }
        return array();
    }

    public function getcategory_byID($id){
        $sql = 'SELECT category.id, category.name
                    FROM category
                    WHERE id = :id';
        try {
            $query = $this->database->prepare($sql);
            $query->bindParam(":id", $id);
            $query->execute();

            return $query->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo $e;
        }
        return array();
    }
}