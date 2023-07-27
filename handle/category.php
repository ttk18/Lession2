<?php 

include '../model/database.php';
include '../model/category.php';

$database = new database();
$db = $database->connect();
$category = new category($db);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($_POST['form_name'] == 'edit_category') {
        $name = $_POST['name'];
        @$parent_id = $_POST['parent_id'];
        $id = $_POST['id'];

        $category->id = $id;
        $category->name = $name;
        $category->parent_id = $parent_id;
        if ($category->update()) {
            $message = "Edit category successful!";
        }
        if (@$_GET['search']) {
            $keyword = $name;
        }

    }
    if ($_POST['form_name'] == 'add_category') {
        $name = $_POST['name'];
        @$parent_id = $_POST['parent_id'];

        $category->name = $name;
        $category->parent_id = $parent_id;
        if ($category->create()) {
            $message = "Create category successful!";
        }
    }
    if ($_POST['form_name'] == 'delete_category') {
        $id = $_POST['id'];
        @$category->id = $id;
        if ($category->delete()) {
            $message = "Delete category successful!";
        }
    }

}else{
    if (@$_GET['search']) {
        $keyword = trim($_GET['search']);
    }
}

?>