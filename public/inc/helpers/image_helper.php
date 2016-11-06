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

class image_helper
{
    private $state;
    private $image_folder;
    private $full_image_folder;
    private $thumb_image_folder;
    private $max_size;

    public function __construct($image_folder)
    {
        $this->image_folder = $image_folder;
        $this->full_image_folder = $image_folder . "/full/";
        $this->thumb_image_folder = $image_folder . "/thumb/";
        $this->max_size = MAX_UPLOAD_SIZE;
        if(!is_dir($this->image_folder)) {
            mkdir($this->image_folder);
        }
        if(!is_dir($this->full_image_folder)) {
            mkdir($this->full_image_folder);
        }
        if(!is_dir($this->thumb_image_folder)) {
            mkdir($this->thumb_image_folder);
        }
    }

    /**
     * @param array $file one Subarray of $_FILES
     * @param string $name name of the file on the server
     * @return Error Code
     */
    public function upload($file, $name){
        $extension = pathinfo($file['name'])['extension'];

        if ($file['error'] == 0) {
            if ($file['size'] <= $this->max_size) {
                if ((exif_imagetype($file['tmp_name']) == 1) OR
                    (exif_imagetype($file['tmp_name']) == 2) OR
                    (exif_imagetype($file['tmp_name']) == 3)){
                    $target = $this->full_image_folder . $name . "." . $extension;
                    $target_png = $this->full_image_folder . $name . ".png";
                    move_uploaded_file($file['tmp_name'], $target);
                    if (exif_imagetype($target) != 3) {
                        $this->convert($target, $target_png);
                        unlink($target);
                    };
                    $this->state = 0;
                } else {
                    $this->state = 9;
                }
            } else {
                $this->state = 1;
            }
        } else {
            $this->state = $file['error'];
        }
        return $this->state;
    }

    public function convert($input, $output) {
        $type = exif_imagetype($input);
        switch ($type){
            case 1:
                $im = imagecreatefromgif($input);
                break;
            case 2:
                $im = imagecreatefromjpeg($input);
                break;
            case IMAGETYPE_PNG:
                $im = imagecreatefrompng($input);
                break;
            default:
                throw new Exception('File format not compatible!');
        }
        if (!imagepng($im, $output, 0 , NULL)) {
            throw new Exception('Could not create output file');
        }
        imagedestroy($im);
        return true;
    }

    public function resize($name, $width) {
        list($input_w, $input_h) = getimagesize($this->full_image_folder . $name . ".png");
        $input_scale = $input_h / $input_w;
        $output_w = $width;
        $output_h = $width * $input_scale;
        $input_im = imagecreatefrompng($this->full_image_folder . $name . ".png");
        $output_im = imagecreatetruecolor($output_w, $output_h);
        imagecopyresized($output_im, $input_im, 0, 0 ,0 ,0, $output_w, $output_h, $input_w, $input_h);
        if (!imagepng($output_im, $this->thumb_image_folder . $name . ".png", 0 , NULL)) {
            throw new Exception('Could not create output file');
        }
        imagedestroy($output_im);
        imagedestroy($input_im);
        return true;
    }

    public function delete($name) {
        unlink($this->full_image_folder . $name . ".png");
        unlink($this->thumb_image_folder . $name . ".png");
    }
}