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

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', 'root');
define('DB_DB', 'kleinanzeigen');

define('SALT', '4b168e4d6745edbd6755f9c9de2b16b8a3517b40');
define('COST', '12');

define('TPL', 'templates/bootstrap');
define('IMAGEPATH', 'image');

define('DEBUG', false);

define('VENDOR_PATH', '/vendor');

define('PAGE_NAME', 'Kleinanzeigen');

define('MAX_UPLOAD_SIZE', 33554432); // Size in byte

define('CHARSET', 'utf-8');

define('THUMBNAIL_WIDTH', 250);
define('THUMBNAIL_HEIGHT', 250);

define('REDIRECT_WAIT_TIME', 3);