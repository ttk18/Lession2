<?php
include '../handle/category.php';
// Quantity rows each page
$quantily_category = 10;

// Pagination: page current
if (isset($_GET['page'])) {
    $page = $_GET['page'];
} else {
    $page = 1;
}

// Element begin in array when slicing
if ($page == 1) {
    $begin = 0;
} else {
    $begin = ($page * $quantily_category) - $quantily_category;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Required meta tags-->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="au theme template">
    <meta name="author" content="Hau Nguyen">
    <meta name="keywords" content="au theme template">
    <!-- Title Page-->
    <title>Category</title>
    <?php include 'css.php'; ?>
</head>
<body class="animsition">
    <div class="page-wrapper">
        <div class="container-fluid">
            <div class="row m-t-25">
                <?php
                // Display a message when the feature CRUD executes successfully
                if (isset($message)) {
                ?>
                    <div class="card-body">
                        <div class="sufee-alert alert with-close alert-success alert-dismissible fade show">
                            <span class="badge badge-pill badge-success">Success</span>
                            <strong id="flag">
                                <?php
                                print_r($message);
                                ?>
                            </strong>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    </div>
                <?php
                }
                ?>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <strong>Categories</strong>
                            <button type="button" data-toggle="modal" data-target="#add_category" class=" float-right btn btn-primary btn-sm "><i class="fa fa-plus-circle"></i>&nbsp;</button>
                            <div class="row my-3">
                                <div class="col-12">
                                    <form class="form-header" action="" method="GET">
                                        <input require class="au-input col-11 au-input--xl" type="text" 
                                            value="<?php 
                                                // Show searched word
                                                echo !empty($keyword) ?  $keyword : '' 
                                            ?>"
                                            name="search" placeholder="Search for datas..." />
                                        <button class=" col-1  au-btn--submit" type="submit">
                                            <i class="zmdi zmdi-search"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <?php
                            // Want show the number of row data when searching
                            if (isset($keyword)) {
                            ?>
                                Search found
                                <?php
                                    print_r($result = $category->read_search($keyword)->rowCount());
                                ?>
                                results
                            <?php
                            }
                            ?>
                        </div>
                        <!-- DATA TABLE-->
                        <div class="table-responsive m-b-40">
                            <table class="table " id="myTable">
                                <thead>
                                    <tr>
                                        <th width="15%" class="text-center"> #</th>
                                        <th width="">Category Name </th>
                                        <th> Operations </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // Show data when search
                                    if (!empty($keyword)) {
                                        $quantily_all_category = $category->read_search($keyword);
                                        $num = $quantily_all_category->rowCount();
                                        // Number of pages when pagination
                                        $quantity_page = ceil($num / $quantily_category);
                                        if ($num > 0) {
                                            $parent_id = 0;
                                            $rows = $quantily_all_category->fetchAll();

                                            // Function data_tree($rows, $parent_id, 0) return array
                                            // It not empty when item['parent_id'] == $parent_id
                                            // If it empty then $parent_id increase --> loop while
                                            while(empty($categories)){
                                                $categories = $category->data_tree($rows, $parent_id, 0);
                                                $categories = array_slice($categories, $begin, $quantily_category);
                                                $parent_id++;
                                            }
                                           
                                            $i = 1;
                                            foreach ($categories as $row) {
                                    ?>
                                                <tr>
                                                    <td width="15%" class="text-center"> <?php echo $i++ ?> </td>
                                                    <td width="60%"> 
                                                        <?php 
                                                            // format level of category
                                                            echo str_repeat('&nbsp;', @$row['level'] * 15) . (@$row['level'] > 0 ?  str_repeat('---', 1) : '') .  $row['name'] 
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <button type="button" data-toggle="modal" data-target="#edit_category<?php echo $row['id'] ?>" class="btn btn-primary btn-sm"><i class="fa fa-edit  "></i>&nbsp;Edit</button>
                                                        <button type="button" data-toggle="modal" data-target="#copy_category<?php echo $row['id'] ?>" class="btn btn-success btn-sm"><i class="fa fa fa-copy"></i>&nbsp;Copy</button>
                                                        <button type="button" data-toggle="modal" data-target="#delete_category<?php echo $row['id'] ?>" class="btn btn-danger btn-sm"><i class="zmdi zmdi-delete"></i>&nbsp;Delete</button>
                                                    </td>
                                                </tr>
                                            <?php
                                            }
                                        }
                                    } 
                                    // Show data default
                                    else {
                                        $quantily_all_category = $category->read_all();
                                        $num = $quantily_all_category->rowCount();
                                        // Number of pages when pagination
                                        $quantity_page = ceil($num / $quantily_category);

                                        if ($num > 0) {
                                            $parent_id = 0;
                                            $rows = $quantily_all_category->fetchAll();
                                            while(empty($categories)){
                                                $categories = $category->data_tree($rows, $parent_id, 0);
                                                $categories = array_slice($categories, $begin, $quantily_category);
                                                $parent_id++;
                                            }
                                            $i = 1;
                                            foreach ($categories as $row) {
                                            ?>
                                                <tr>
                                                    <td width="15%" class="text-center"> <?php echo $i++ ?> </td>
                                                    <td width="60%"> 
                                                        <?php 
                                                            // format level of category
                                                            echo str_repeat('&nbsp;', $row['level'] * 15) . ($row['level'] > 0 ?  str_repeat('---', 1) : '') .  $row['name'] 
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <button type="button" data-toggle="modal" data-target="#edit_category<?php echo $row['id'] ?>" class="btn btn-primary btn-sm"><i class="fa fa-edit  "></i>&nbsp;Edit</button>
                                                        <button type="button" data-toggle="modal" data-target="#copy_category<?php echo $row['id'] ?>" class="btn btn-success btn-sm"><i class="fa fa fa-copy"></i>&nbsp;Copy</button>
                                                        <button type="button" data-toggle="modal" data-target="#delete_category<?php echo $row['id'] ?>" class="btn btn-danger btn-sm"><i class="zmdi zmdi-delete"></i>&nbsp;Delete</button>
                                                    </td>
                                                </tr>
                                    <?php
                                            }
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <nav aria-label="Page navigation example  ">
                                    <ul class="pagination d-flex justify-content-center py-3">
                                        <?php
                                        // Show menu paginate when search
                                        if (!empty($keyword)) {?>
                                            <li class="page-item <?php echo $page == 1 ? 'disabled' : false  ?>">
                                                <a class="page-link" 
                                                    href="index.php?search=<?php echo $keyword ?>&page=<?php echo $page  - 1 ?>">
                                                    Previous
                                                </a>
                                            </li>
                                            <?php for ($i = 1; $i <= $quantity_page; $i++) { ?>
                                            <li class="page-item <?php echo $i == $page ? 'active' : false ?>">
                                                <a class="page-link " 
                                                    href="index.php?search=<?php echo $keyword ?>&page=<?php echo $i ?>">
                                                    <?php echo $i ?>
                                                </a>
                                            </li>
                                            <?php } ?>
                                            <li class="page-item <?php echo $page == $quantity_page ? 'disabled' : false   ?>">
                                                <a class="page-link" 
                                                    href="index.php?search=<?php echo $keyword ?>&page=<?php echo $page + 1 ?>">
                                                    Next
                                                </a>
                                            </li>
                                        <?php } 
                                        // Show menu paginate default
                                        else { ?>
                                            <li class="page-item <?php echo $page == 1 ? 'disabled' : false  ?>">
                                                <a class="page-link  " href="index.php?page=<?php echo $page  - 1 ?>">Previous</a>
                                            </li>
                                            <?php for ($i = 1; $i <= $quantity_page; $i++) { ?>
                                                <li class="page-item <?php echo $i == $page ? 'active' : false ?>">
                                                    <a class="page-link" 
                                                        href="index.php?page=<?php echo $i ?>">
                                                        <?php echo $i ?>
                                                    </a>
                                                </li>
                                            <?php } ?>
                                            <li class="page-item <?php echo $page == $quantity_page ? 'disabled' : false   ?>">
                                                <a class="page-link" 
                                                    href="index.php?page=<?php echo $page + 1 ?>">
                                                    Next
                                                </a>
                                            </li>
                                        <?php } ?>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal create category -->

    <div class="modal fade" id="add_category" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="mediumModalLabel">Add new category</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="" method="POST" name="frm_add">
                    <div class="modal-body">
                        <div class="row form-group">
                            <div class="col col-md-3">
                                <label for="text-input" class=" form-control-label">Category name</label>
                            </div>
                            <div class="col-12 col-md-12" >
                                <input  value="" type="text" id="text-input" name="name"  class="form-control" required>
                                <small class="form-text text-muted">We'll never share your email wiith anyone else </small>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col col-md-3">
                                <label for="select" class=" form-control-label">Parent category</label>
                            </div>
                            <div class="col-12 col-md-12">
                                <select name="parent_id" id="select" class="form-control">
                                    <option disabled selected value="">Option Categories</option>
                                    <?php
                                    $options = $category->read_all();
                                    while ($rs = $options->fetch()) {
                                    ?>
                                        <option value="<?php echo $rs['id'] ?>">
                                            <?php echo $rs['name'] ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="float-start ">
                            <input type="hidden" name="form_name" value="add_category">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- /Modal create category -->

    <!--  Modal edit category -->
    <?php
    $result = $category->read_all();
    $num = $result->rowCount();
    if ($num > 0) {
        while ($rows = $result->fetch()) {
    ?>
            <div class="modal fade" id="edit_category<?php echo $rows['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="mediumModalLabel">Edit category</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="" method="POST" name="frm_edit">
                            <div class="modal-body">
                                <div class="row form-group">
                                    <div class="col col-md-3">
                                        <label for="text-input" class=" form-control-label">Category name</label>
                                    </div>
                                    <div class="col-12 col-md-12">
                                        <input value="<?php echo $rows['name'] ?>" type="text" id="text-input" name="name" placeholder="" class="form-control">
                                        <small class="form-text text-muted">We'll never share your email wiith anyone else </small>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <div class="col col-md-3">
                                        <label for="select" class=" form-control-label">Parent category</label>
                                    </div>
                                    <div class="col-12 col-md-12">
                                        <select name="parent_id" id="select" class="form-control">
                                            <option disabled selected value="">Option Categories</option>
                                            <?php
                                            $options = $category->read_all();
                                            while ($rs = $options->fetch()) {
                                            ?>
                                                <option <?php
                                                        if ($rs['id'] == $rows['parent_id'])
                                                            echo 'selected';
                                                        if ($rs['id'] == $rows['id'])
                                                            echo 'disabled';
                                                        ?> value="<?php echo $rs['id'] ?>">
                                                    <?php echo $rs['name'] ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class=" float-start ">
                                    <input type="hidden" name="form_name" value="edit_category">
                                    <input type="hidden" name="id" value="<?php echo $rows['id'] ?>">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!--  /Modal edit category -->

            <!--Modal Copy category  -->
            <div class="modal fade" id="copy_category<?php echo $rows['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="mediumModalLabel">Copy category</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="" method="POST" name="frm_copy">
                            <div class="modal-body">
                                <div class="row form-group">
                                    <div class="col col-md-12">
                                        <label for="text-input" class=" form-control-label">Category name</label>
                                    </div>
                                    <div class="col-12 col-md-12">
                                        <input value="<?php echo $rows['name'] ?>" type="text" id="text-input" name="name" placeholder="" class="form-control">
                                        <small class="form-text text-muted">We'll never share your email wiith anyone else </small>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <div class="col col-md-3">
                                        <label for="select" class=" form-control-label">Parent Category</label>
                                    </div>
                                    <div class="col-12 col-md-12">
                                        <select name="parent_id" id="select" class="form-control">
                                            <option disabled selected value="">Option Categories</option>
                                            <?php
                                            $options = $category->read_all();
                                            while ($rs = $options->fetch()) {
                                            ?>
                                                <option <?php
                                                        if ($rs['id'] == $rows['parent_id'])
                                                            echo 'selected';
                                                        ?> value="<?php echo $rs['id'] ?>">
                                                    <?php echo $rs['name'] ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class=" float-start ">
                                    <input type="hidden" name="form_name" value="add_category">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!--/Modal Copy category  -->

            <!--Modal delete category  -->

            <div class="modal fade" id="delete_category<?php echo $rows['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <form method="POST" action="">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="mediumModalLabel">Delete Category</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <p>
                                    Are you sure that you want to delete this category?
                                </p>
                            </div>
                            <div class="d-block float-left modal-footer ">
                                <input type="hidden" name="form_name" value="delete_category">
                                <input type="hidden" name="id" value="<?php echo $rows['id']; ?>">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">Confirm</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!--/ Modal delete category  -->

    <?php
        }
    }
    ?>
    </div>
    </div>
    <?php include 'js.php'; ?>
</body>

</html>