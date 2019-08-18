<?php include_once('../app/views/templates/header.php') ?>

        <div class="col-xs-6 login-image"></div>

        <div class="col-xs-6">
            <div class="col-lg-1"></div>
            <div class="col-lg-8">
                <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Login </h3>
                </div>
                <div class="panel-body">
                    <?php
                        if(isset($_GET['error'])){
                            ?>
                            <div class="alert alert-danger">Wrong username and password</div>
                            <?php
                        }
                    ?>
                    <form class="" action="" method="POST">
                    <div class="form-group">
                        <label for="">Username</label>
                        <input type="text" name="user" class="form-control" id="" placeholder="">
                        <!-- <p class="help-block">Help text here.</p> -->
                    </div>
                    <div class="form-group">
                        <label for="">Password</label>
                        <input type="password" name="passwd" class="form-control" id="" placeholder="">
                        <!-- <p class="help-block">Help text here.</p> -->
                    </div>
                    <div class="form-group">
                        <input type="submit" name="login" value="Login" class="btn btn-primary form-control" id="" placeholder="">
                        <label for=""></label>
                        <!-- <p class="help-block">Help text here.</p> -->
                    </div>
                    </form>
                </div>
                </div>
            </div>
            <div class="col-lg-1"></div>
        </div>
    <hr>

<?php include_once('../app/views/templates/footer.php') ?>



