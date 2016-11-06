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

class user
{
    private $database;
    private $database_helper;

    function __construct()
    {
        $this->database_helper = new database_helper();
        $this->database = $this->database_helper->getConnection();
    }

    public function createUser($name, $surname, $mail, $password, $street, $housenumber, $id_ort)
    {
        $password = password_helper::hashpassword($password);
        $sql = "INSERT INTO user 
                (user.surname, user.name, user.mail, user.password, user.street, user.housenumber, user.fsOrt) 
                VALUES 
                (:surname, :name, :mail, :password, :street, :housenumber, :ort_id)";
        $querry = $this->database->prepare($sql);
        $querry->bindParam(':surname', $surname, PDO::PARAM_STR);
        $querry->bindParam(':name', $name, PDO::PARAM_STR);
        $querry->bindParam(':mail', $mail, PDO::PARAM_STR);
        $querry->bindParam(':password', $password, PDO::PARAM_STR);
        $querry->bindParam(':street', $street, PDO::PARAM_STR);
        $querry->bindParam(':housenumber', $housenumber, PDO::PARAM_STR);
        $querry->bindParam(':ort_id', $id_ort, PDO::PARAM_INT);
        return $querry->execute();
    }

    public function login($mail, $password)
    {
        if ($return = $this->getUser($mail)) {
            echo $return['password'] . "<br />";
            echo password_helper::hashpassword($password) . "<br />";
            if (password_helper::checkpassword($password, $return['password'])) {
                $_SESSION['status'] = "authorized";
                return true;
            }
        }
        return false;
    }

    public function delete($id){
            $sql = 'DELETE FROM user
                WHERE user.id = :id';
            $query = $this->database->prepare($sql);
            $query->bindParam(':id', $id, PDO::PARAM_INT);
            return $query->execute();
    }

    public function getUser($value)
    {
        if (is_numeric($value)) {
            $sql = 'SELECT user.id, user.surname, user.name, user.mail, user.password, user.street, user.housenumber, ort.name as city, ort.postcode
                    FROM user, ort
                    WHERE user.id = :value
                    AND user.fsOrt = ort.id';
        } else {
            $sql = 'SELECT user.id, user.surname, user.name, user.mail, user.password, user.street, user.housenumber, ort.name as city, ort.postcode
                    FROM user, ort
                    WHERE user.mail = :value
                    AND user.fsOrt = ort.id';
        }
        try {
            $querry = $this->database->prepare($sql);
            $querry->bindParam(":value", $value);
            $querry->execute();

            return $querry->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo $e;
        }
        return array();
    }


}