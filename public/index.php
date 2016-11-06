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

include "config/config.php";
include "config/text.php";
include "vendor/autoload.php";

setlocale(LC_ALL, 'de_DE@euro', 'de_DE', 'de', 'ge', 'deu', 'germany');

if (DEBUG) {
    echo "<b>GET</b><br />";
    var_dump($_GET);
    echo "<hr />";
    echo "<b>POST</b><br />";
    var_dump($_POST);
    echo "<hr />";
    echo "<b>FILE</b><br />";
    var_dump($_FILES);
    echo "<hr />";
    error_reporting(E_ALL);
} else {
    error_reporting(E_ALL & ~E_NOTICE);
}
$controller = new main("html", $_POST, $_GET);