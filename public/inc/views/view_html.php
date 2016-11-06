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

class view_html
{
    private static $msg = array();
    private $data = array();
    private $twig;
    private $page;

    function __construct()
    {
        $loader = new Twig_Loader_Filesystem(TPL);
        $this->twig = new Twig_Environment($loader, array());
        $this->data['config'] = array(
            'vendor' => VENDOR_PATH,
            'pagename' => PAGE_NAME,
            'tpl' => TPL,
            'max_upload_size' => MAX_UPLOAD_SIZE,
            'charset' => CHARSET,
            'imgpath' => IMAGEPATH,
            'THUMBNAIL_WIDTH' => THUMBNAIL_WIDTH,
            'THUMBNAIL_HEIGHT' => THUMBNAIL_HEIGHT,
        );
    }

    public static function addMsg($type, $msg)
    {
        self::$msg[] = array(
            'type' => $type,
            'msg' => $msg,
        );
    }

    public function setPage($page)
    {
        $this->page = $page;
    }

    function addData($name, $data) {
        $this->data[$name] = $data;
    }

    function render() {
        $this->data['messages'] = self::getMsg();
        if (DEBUG) {
            echo "<hr> <pre>";
            print_r($this->data);
            echo "</pre><hr>";;
        }
        if (isset($this->page)) {
            if (isset($this->data['title'])) {
                $this->data['title'] = $this->data['config']['pagename'] . " - " - $this->data['title'];
            } else {
                $this->data['title'] = $this->data['config']['pagename'];
            }
            echo $this->twig->render($this->page . ".html.twig", $this->data);
        } else {
            die("Please Select a Page");
        }
    }

    /**
     * @return array
     */
    public static function getMsg()
    {
        return self::$msg;
    }
}