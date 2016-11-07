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

class main
{
    private $view;
    private $post;
    private $get;

    function __construct($view, $post, $get)
    {
        session_start();
        if (DEBUG) {
            var_dump($_SESSION);
            echo "<hr />";
        }


        $this->post = $post;
        $this->get = $get;
        $this->view = new view_html();
        $this->view->setPage("main");
        $this->view->addData('categories', $this->getcategories());

        switch ($get['site']) {
            case "ad":
                switch ($get['subsite']) {
                    default:
                    case "list":
                        $this->view->addData('ads', array_reverse($this->viewadsList()));
                        break;
                    case "category":
                        $this->view->addData('site', 'category');
                        if (!empty($get['opt1'])) {
                            $this->view->addData('activecategory', $get['opt1']);
                            $this->view->addData('ads', $this->viewadsList($get['opt1']));
                        }
                        break;
                    case "view":
                        if (isset($get['opt1'])) {
                            $data = $this->viewad($get['opt1']);
                            $this->view->addData('single', 'true');
                            if (!empty($data['id'])) {
                                $this->view->addData('ads', array($data));
                            } else {
                                $this->error(404);
                            }
                        } else {
                            $this->error(404);
                        }
                        break;
                    case "add":
                        if ($_SESSION['status'] == "loggedin") {
                            $this->view->addData('create', true);
                            if ($post['create'] == true) {
                                $this->createad($post, $this->getUser($_SESSION['user_id']));
                                view_html::addMsg("success", MSG_AD_CREATE_SUCCESS);
                                break;
                            }
                            $this->view->addData('site', 'create');
                            break;
                        } else {
                            view_html::addMsg("danger", MSG_AD_CREATE_UNATHORIZED);
                            break;
                        }
                        break;
                    case "modify":
                        if ($_SESSION['status'] == "loggedin") {
                            $ad = $this->viewad($get['opt1']);
                            if (!empty($ad['id'])) {
                                $this->view->addData('update', true);
                                if ($post['update'] == true) {
                                    if ($this->modifyad($ad['id'], $post['title'], $post['category'], $post['price'], $post['content'], $_SESSION['user_id'])) {
                                        view_html::addMsg("success", MSG_AD_MODIFY_SUCCESS);
                                        break;
                                    } else {
                                        view_html::addMsg("danger", MSG_AD_MODIFY_FAIL);
                                        break;
                                    }

                                }
                                $this->view->addData('site', 'create');
                                $this->view->addData('prefill', $ad);
                                break;
                            } else {
                                view_html::addMsg("danger", MSG_AD_VIEW_NOT_FOUND);
                            }
                        } else {
                            view_html::addMsg("danger", MSG_AD_MODIFY_UNATHORIZED);
                            break;
                        }
                        break;
                    case "delete":
                        if ($this->deletead($get['opt1'])) {
                            view_html::addMsg('success', MSG_SUCCESFULL_DELETE);
                        } else {
                            view_html::addMsg('danger', MSG_FAILD_DELETE);
                        }
                        break;
                    case "search":
                        $pattern = $post['pattern'];
                        $this->view->addData('ads', array_reverse($this->searchadList($pattern)));
                        $this->view->addData('searchPattern', $pattern);
                        break;
                    case "image":
                        switch ($get['opt1']) {
                            case "view":
                                $image = $this->viewImage($get['opt2']);
                                $ad = $this->viewad($image['ad']);
                                $this->view->addData('owner', $ad['user']['id']);
                                $this->view->addData('image', $image);
                                $this->view->addData('site', 'imagebox');
                                break;
                            case "delete":
                                $image_o = new image(IMAGEPATH);
                                $image = $image_o->getImage($get['opt2']);
                                $ad = $this->viewad($image['ad']);
                                if ($ad['user']['id'] == $_SESSION['user_id']) {
                                    $image_o->deleteImage($image['id']);
                                    view_html::addMsg('success', MSG_IMG_DELETE_SUCCESS);
                                } else {
                                    view_html::addMsg('danger', MSG_IMG_DELETE_FAIL);
                                }
                                break;
                            case "replace":
                                $image_o = new image(IMAGEPATH);
                                $image = $image_o->getImage($get['opt2']);
                                $ad = $this->viewad($image['ad']);
                                if ($ad['user']['id'] == $_SESSION['user_id']) {
                                    $image_o->deleteImage($image['id']);
                                    if ($_FILES['image']['error'] == UPLOAD_ERR_OK) {
                                        $image_o->addImage($_FILES['image'], $ad['id']);
                                    }
                                    view_html::addMsg('success', MSG_IMG_REPLACE_SUCCESS);
                                } else {
                                    view_html::addMsg('danger', MSG_IMG_REPLACE_FAIL);
                                    break;
                                }
                                break;
                        }
                        break;
                }
                break;
            case "user":
                switch ($get['subsite']) {
                    case "create":
                        if ($post['register'] == "true") {
                            if (!validation_helper::validate($post['postcode'], "postcode") OR // TODO:Simplify
                                !validation_helper::validate($post['mail'], "mail") OR
                                empty($post['name']) OR
                                empty($post['surname']) OR
                                empty($post['street']) OR
                                empty($post['housenumber']) OR
                                empty($post['city']) OR
                                empty($post['password']) OR
                                empty($post['password2'])
                            ) {
                                view_html::addMsg("danger", MSG_USER_REGISTER_DATA_ERROR);
                            } elseif ($this->getUser($post['mail'])) {
                                view_html::addMsg("danger", MSG_USER_REGISTER_MAIL_EXISTS);
                                break;
                            } else {
                                $this->registerUser($post['name'], $post['surname'], $post['mail'], $post['street'], $post['housenumber'], $post['city'], $post['postcode'], $post['password'], $post['password2']);
                                view_html::addMsg("success", MSG_USER_REGISTER_SUCCESS);
                                break;
                            }
                        }
                        $this->view->addData('site', 'register');
                        break;
                    case "delete":
                        if($post['accept'] == "true") {
                            $this->deleteUser($get['opt1']);
                            session_destroy();
                            view_html::addMsg("success", MSG_USER_DELETE_SUCCESS);
                        } else {
                            $this->view->addData('site', 'delete');
                        }
                        break;
                    case "login":
                        $this->login($post);
                        break;
                    case "logout":
                        session_destroy();
                        break;
                }
                break;
            default:
                $this->view->addData('redirect', array(
                    'time' => '0',
                    'target' => '/ad/list'
                ));
                break;
        }

        if ($_SESSION['status'] == "loggedin" AND isset($_SESSION['user_id'])) {
            $this->view->addData('user', $this->getUser($_SESSION['user_id']));
        }
        $this->view->render();
    }

    // ad Site

    /**
     * @param int $count How many ads you want
     * @param int $category category Filter
     * @return array Assoc with all ads you want
     */
    private function viewadsList($category = NULL, $count = NULL)
    {
        $ads = new ad();
        $image = new image(IMAGEPATH);
        if ($category != NULL and $category != 0) {
            $list = $ads->getadList($category);
        } else {
            $list = $ads->getadList();
        }

        $result = array();
        foreach ($list as $ad) {
            $value = $ads->getad($ad['id']);
            $value['user'] = $this->getUser($value['user']);
            $value['images'] = $image->getImagebyad($value['id']);
            array_push($result, $value);
        }

        if ($count != NULL) {
            $result = array_slice($result, 0, $count);
        }
        return $result;
    }

    /**
     * @return array Assoc with all ads you want
     */
    private function searchadList($pattern)
    {
        $ads = new ad();
        $image = new image(IMAGEPATH);
        $list = $ads->search($pattern);

        $result = array();
        foreach ($list as $ad) {
            $value = $ads->getad($ad['id']);
            $value['user'] = $this->getUser($value['user']);
            $value['images'] = $image->getImagebyad($value['id']);
            array_push($result, $value);
        }

        if (count($result) < 1){
            view_html::addMsg('warning', MSG_AD_SEARCH_NOT_FOUND);
        }
        return $result;
    }

    private function createad($post, $user)
    {
        $image = new image(IMAGEPATH);
        $ad = new ad();
        $id = $ad->createad($post['title'], $post['content'], $user['id'], $post['category'], $post['price']);
        if ($_FILES['image_1']['error'] == UPLOAD_ERR_OK) {
            $image->addImage($_FILES['image_1'], $id);
        }
        if ($_FILES['image_2']['error'] == UPLOAD_ERR_OK) {
            $image->addImage($_FILES['image_2'], $id);
        }
        if ($_FILES['image_3']['error'] == UPLOAD_ERR_OK) {
            $image->addImage($_FILES['image_3'], $id);
        }
    }

    /**
     * @param int $id ad ID
     * @param int $user User ID
     * @param String $title
     * @param int $category
     * @param float $price
     * @param String $content
     *
     * @return bool
     */
    private function modifyad($id, $title, $category, $price, $content, $user)
    {
        $image = new image(IMAGEPATH);
        $ad_o = new ad();
        $ad = $ad_o->getad($id);
        if ($ad['user'] == $user) {
            if ($ad_o->modifyad($id, $title, $content, $category, $price)) {
                if (isset ($_FILES['image_1']['error']) and $_FILES['image_1']['error'] == UPLOAD_ERR_OK) {
                    $image->addImage($_FILES['image_1'], $id);
                }
                if (isset ($_FILES['image_2']['error']) and $_FILES['image_2']['error'] == UPLOAD_ERR_OK) {
                    $image->addImage($_FILES['image_2'], $id);
                }
                if (isset ($_FILES['image_3']['error']) and $_FILES['image_3']['error'] == UPLOAD_ERR_OK) {
                    $image->addImage($_FILES['image_3'], $id);
                }
                return true;
            }
            return false;
        } else {
            return false;
        }
    }

    private function getcategories()
    {
        $category = new category();
        return $category->getcategoryList();
    }


    /**
     * @param int $id ID of an ad
     *
     * @return array
     */
    private function viewad($id)
    {
        $ad = new ad();
        $image = new image(IMAGEPATH);
        $value = $ad->getad($id);
        $value['user'] = $this->getUser($value['user']);
        $value['images'] = $image->getImagebyad($value['id']);
        return $value;
    }

    private function deletead($id)
    {
        $ad = new ad();
        $image_o = new image(IMAGEPATH);
        $value = $ad->getad($id);
        $value['images'] = $image_o->getImagebyad($value['id']);
        if ($_SESSION['user_id'] == $value['user']) {
            foreach ($value['images'] AS $image) {
                $image_o->deleteImage($image['id']);
            }
            $ad->deletead($id);
            return true;
        } else {
            return false;
        }
    }

    // Image Section

    private function viewImage($id)
    {
        $image = new image(IMAGEPATH);
        return $image->getImage($id);
    }

    // User Site

    private function login($post)
    {
        if ($this->checkPassword($post['mail'], $post['password'])) {
            $_SESSION['user_id'] = $this->getUser($post['mail'])['id'];
            $_SESSION['status'] = "loggedin";
            view_html::addMsg("success", MSG_USER_LOGIN_SUCCESS);
        } else {
            view_html::addMsg("danger", MSG_USER_LOGIN_FAIL);
        }
    }

    private function checkPassword($mail, $password)
    {
        if ($user = $this->getUser($mail) AND password_helper::checkpassword($password, $user['password'])) {
            return true;
        } else {
            return false;
        }
    }

    private function getUser($value)
    {
        $user = new user();
        return $user->getUser($value);
    }

    private function registerUser($name, $surname, $mail, $street, $housenumber, $city, $postcode, $password, $password2)
    {
        if ($password == $password2) {
            $user = new user();
            $ort = new ort();
            if (!$ort_id = $ort->getOrt_byPost($postcode)['id']) {
                $ort_id = $ort->createOrt($city, $postcode)['id'];
            }
            if ($user->createUser($name, $surname, $mail, $password, $street, $housenumber, $ort_id)) {
                view_html::addMsg("success", MSG_USER_REGISTER_SUCCESS);
            } else {
                view_html::addMsg("danger", MSG_USER_REGISTER_FAIL);
            }
        } else {
            view_html::addMsg("danger", MSG_USER_REGISTER_FAIL);
        }

    }


    private function deleteUser($id){
        $user = new user();
        $ads = $this->viewadsList();
        foreach ($ads as $ad) {
            if ($ad['user']['id'] = $id) {
                $this->deletead($ad['id']);
            }
        }
        $user->delete($id);

    }
    // Error Site

    private function error($id)
    {
        switch ($id) {
            case 404:
                header("HTTP/1.0 404 Not Found");
                $this->view->setPage("error");
                $this->view->addData("error", ERROR_MSG_404);
                break;
        }
    }
}