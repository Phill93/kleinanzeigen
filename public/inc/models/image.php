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

class image
{
    private $imagepath;
    private $database_helper;
    private $database;
    private $image_helper;
    public function __construct($imagepath)
    {
        $this->imagepath = $imagepath;
        $this->database_helper = new database_helper();
        $this->database = $this->database_helper->getConnection();
        $this->image_helper = new image_helper($this->imagepath);
    }

    public function getImagelist() {
        $sql = 'SELECT image.id, image.fsad as ad
                    FROM image
                    ORDER BY image.id;';
        try {
            $query = $this->database->prepare($sql);
            $query->execute();

            while ($result = $query->fetch(PDO::FETCH_ASSOC)) {
                $array[$result['id']] = $result;
            }

            if (!isset($array)) {
                $array = array();
            }
        } catch (PDOException $e) {
            echo $e;
        }
        asort($array);
        return $array;
    }

    public function getImagebyad($ad) {
        $sql = 'SELECT image.id, image.fsad as ad
                    FROM image
                    WHERE image.fsad = :ad
                    ORDER BY image.id;';
        try {
            $query = $this->database->prepare($sql);
            $query->bindParam(":ad", $ad);
            $query->execute();

            while ($result = $query->fetch(PDO::FETCH_ASSOC)) {
                $array[$result['id']] = $result;
            }

            if (!isset($array)) {
                $array = array();
            }
        } catch (PDOException $e) {
            echo $e;
        }
        asort($array);
        return $array;
    }

    public function getImage($id) {
        $sql = 'SELECT image.id, image.fsad as ad
                    FROM image
                    WHERE image.id = :id
                    ORDER BY image.id;';
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

    public function addImage($file, $ad) {
        $id = $this->addImagetoDB($ad);
        $name = "img" . $id;
        if($this->image_helper->upload($file, $name) == 0) {
            $this->image_helper->resize($name, THUMBNAIL_WIDTH);
        } else {
            throw new Exception('Could not Upload Image');
        }
    }

    private function addImagetoDB($ad)
    {
        $sql = 'INSERT INTO image 
                  (image.fsad)
                  VALUES 
                  (:ad)';
        $querry = $this->database->prepare($sql);
        $querry->bindParam(':ad', $ad, PDO::PARAM_INT);
        $querry->execute();
        return $this->database->lastInsertId();
    }

    public function deleteImage($id) {
        $name = "img" . $id;
        $this->image_helper->delete($name);

        $sql = 'DELETE FROM image
                WHERE image.id = :id';
        $querry = $this->database->prepare($sql);
        $querry->bindParam(':id', $id, PDO::PARAM_INT);
        $querry->execute();
    }
}