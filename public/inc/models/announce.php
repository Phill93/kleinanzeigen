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

class ad
{
    private $database_helper;
    private $database;

    function __construct()
    {
        $this->database_helper = new database_helper();
        $this->database = $this->database_helper->getConnection();
    }

    /**
     * @param int $category ID
     * @return array
     */
    public function getadList($category = NULL)
    {
        if ($category == NULL) {
            $sql = 'SELECT ad.id, ad.title, category.id AS category
                    FROM ad, category
                    WHERE ad.fscategory = category.id
                    ORDER BY ad.id';
        } else {
            $sql = 'SELECT ad.id, ad.title, category.id AS category
                    FROM ad, category
                    WHERE ad.fscategory = category.id
                    AND ad.fscategory = :category
                    ORDER BY ad.id ASC';
        }
        try {
            $query = $this->database->prepare($sql);
            if ($category != NULL) {
                $query->bindParam(":category", $category);
            }
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

    /**
     * @param string $pattern
     * @return array
     */
    public function search($pattern)
    {
            $sql = 'SELECT ad.id, ad.title, category.id AS category
                    FROM ad, category
                    WHERE ad.content LIKE CONCAT(\'%\', :pattern ,\'%\')
                    ORDER BY ad.id';

        try {
            $query = $this->database->prepare($sql);
            $query->bindParam(":pattern", $pattern);

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

    /**
     * @param int $id
     * @return array
     */
    public function getad($id)
    {
        $sql = 'SELECT ad.id, ad.title, ad.content, ad.price, user.id as user, category.id as category
                    FROM ad, user, category
                    WHERE ad.id = :id
                    AND ad.fsUser = user.id 
                    AND ad.fscategory = category.id';
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

    /**
     * @param string $title Title as String
     * @param string $content Content as String
     * @param int $creator Creator as ID
     * @param int $category category as ID
     * @param int $price price as Numeric
     * @return int
     */
    public function createad($title, $content, $creator, $category, $price) {
        $price = conversion_helper::strToFloat($price);
        $content = nl2br($content);
        $sql = 'INSERT INTO ad 
                (ad.title, ad.content, ad.price, ad.fscategory, ad.fsUser) 
                VALUES 
                (:title, :content, :price, :category, :creator)';
        $query = $this->database->prepare($sql);
        $query->bindParam(':title', $title, PDO::PARAM_STR);
        $query->bindParam(':content', $content, PDO::PARAM_STR);
        $query->bindParam(':price', $price, PDO::PARAM_STR);
        $query->bindParam(':category', $category, PDO::PARAM_INT);
        $query->bindParam(':creator', $creator, PDO::PARAM_INT);
        $query->execute();
        return $this->database->lastInsertId();
    }

    /**
     * @param int $id ad ID
     * @param string $title Title as String
     * @param string $content Content as String
     * @param int $category category as ID
     * @param int $price price as Numeric
     *
     * @return int ad ID
     */
    public function modifyad($id,$title, $content, $category, $price) {
        $price = conversion_helper::strToFloat($price);
        $content = nl2br($content);
        $sql = 'UPDATE ad
                SET ad.title = :title, ad.content = :content, ad.fscategory = :category, ad.price = :price
                WHERE ad.id = :id';
        $query = $this->database->prepare($sql);
        $query->bindParam(':title', $title, PDO::PARAM_STR);
        $query->bindParam(':content', $content, PDO::PARAM_STR);
        $query->bindParam(':price', $price, PDO::PARAM_STR);
        $query->bindParam(':category', $category, PDO::PARAM_INT);
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        return $query->execute();
    }

    public function deletead($id) {
        $sql = 'DELETE FROM ad
                WHERE ad.id = :id';
        $query = $this->database->prepare($sql);
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        return $query->execute();
    }
}