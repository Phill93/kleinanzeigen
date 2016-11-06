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

class ort
{
    function __construct()
    {
        $this->database_helper = new database_helper();
        $this->database = $this->database_helper->getConnection();
    }

    public function getOrt_byID($id) {
        $sql = 'SELECT ort.name, ort.postcode
                FROM ort
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

    public function getOrt_byPost($postcode) {
        $sql = 'SELECT  ort.id, ort.name
                FROM ort
                WHERE postcode = :value';
        try {
            $query = $this->database->prepare($sql);
            $query->bindParam(":value", $postcode);
            $query->execute();

            return $query->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo $e;
        }
        return array();
    }

    public function createOrt($name, $postcode) {
        $sql = "INSERT INTO ort 
                (ort.name, ort.postcode) 
                VALUES 
                (:ort, :postcode)";
        $querry = $this->database->prepare($sql);
        $querry->bindParam(':ort', $name, PDO::PARAM_STR);
        $querry->bindParam(':postcode', $postcode, PDO::PARAM_STR);
        if ($querry->execute()) {
            return $this->getOrt_byPost($postcode);
        } else {
            return false;
        }
    }
}