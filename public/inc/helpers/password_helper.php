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

class password_helper
{
    private static $options = array(
        'salt' => SALT,
        'cost' => COST,
    );
    private static $algo = PASSWORD_DEFAULT;


    public static function hashpassword($password)
    {
        return password_hash($password, password_helper::$algo, password_helper::$options);
    }

    public static function checkpassword($password, $hash)
    {
        return password_verify($password, $hash);
    }
}