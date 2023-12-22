<?php include_once('layout/header.php'); ?>
<?php include_once('layout/nav.php'); ?>
<?php include_once('layout/sideNav.php'); ?>


<?php
    if ($_SESSION['userType'] == "employee") {
        echo '<script>window.location.href = "/index.php";</script>';
        exit();
    }

    $accountModel = new AccountModel();
    $employeeList = $accountModel->GetEmployeeByCompanyId($companyId);

    $userId = $_SESSION['user']->Id;

    $DepartmentModel = new DepartmentModel();
    $departmentList = $DepartmentModel->GetDepartmentByCompanyId($companyId);
?>

<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-6 col-12 mb-2">
                <h3 class="content-header-title mb-0">Employee List</h3>
                <div class="row breadcrumbs-top">
                    <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.html">Home</a>
                            </li>
                            <li class="breadcrumb-item active">Employee List
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
            <div class="content-header-right col-md-6 col-12 mb-md-0 mb-2 text-right">
                <a type="button" class="btn btn-primary genericMPOpener" href="/employeeCRUD.php">Create Employee</a>
            </div>
        </div>
        <div class="content-body">

            <div class="card">
                <div class="card-body">
                    <div id="app-invoice-wrapper" class="">
                        <table id="employee" class="table table-striped table-bordered dataTable" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Employee Name</th>
                                    <th>Phone Number</th>
                                    <th>Email</th>
                                    <th>Department</th>
                                    <th>Created At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                
                                foreach ($employeeList as $employees) { 
                                    if($userId != $employees['Id']){ ?>
                                    <tr>
                                        <td><?= $employees['Id'] ?></td>
                                        <td><?= $employees['FullName'] ?></td>
                                        <td><?= $employees['ContactNo'] ?></td>
                                        <td><?= $employees['Email'] ?></td>
                                        <td>
                                        <?php foreach($departmentList as $item){ 
                                            if($employees["DepartmentId"] == $item["Id"]){   
                                        ?>
                                            <?= $item["DepartmentName"] ?>
                                        <?php } }?>
                                        </td>
                                        <td><?= $employees['CreatedAt'] ?></td>
                                        <td>
                                            <div class="d-flex justify-content-start align-items-center" style="gap: 20px;">   
                                                <a class="genericMPOpener" href="/employeeCRUD.php?id=<?= $employees['Id']?>">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="text-secondary" width="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                    </svg>
                                                </a>
                                                <a class="delete badge bg-danger" data-id="<?=$employees['Id']?>">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </a>
                                            </div>    
                                        </td>
                                    </tr>
                                <?php } }?>

                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once('layout/footer.php'); ?>

<script>

    $(document).on("click",".delete",function() {
        var el = this;
        var deleteid = $(el).data('id');
        var confirmalert = confirm("Are you sure?");
        if (confirmalert == true) {
            $.ajax({
                url: '/api/index.php/user/delete',
                type: 'POST',
                headers: {
                    "Authorization": "Bearer <?= $_SESSION["user"]->token ?>"
                },
                data: {
                    id: deleteid
                },
                success: function(response) {
                    if (response.success) {
                        $(el).closest('tr').css('background', 'tomato');
                        $(el).closest('tr').fadeOut(800, function() {
                            $(this).remove();
                        });
                    } else {
                        alert(response.message);
                    }
                }
            });
        }
    });
    $(document).ready(function() {
        $("#employee").DataTable();
        $(document).on('click', '.genericMPOpener', function (e) {
            e.preventDefault();
            var that = $(this);
            if (!$(this).hasClass('MpAdded')) {
                $(this).addClass('MpAdded');
                $(this).magnificPopup({
                    type: 'iframe',
                    closeOnContentClick: !that.hasClass('bgNoClose'),
                    modal: that.hasClass('bgNoClose'),
                    closeBtnInside: false,
                }).magnificPopup('open');
            } else {
                $(this).magnificPopup('open');
            }
        });
    });
</script>