<!DOCTYPE html>
<html lang="en" dir="ltr" data-nav-layout="vertical" data-vertical-style="overlay" data-theme-mode="light"
    data-header-styles="light" data-menu-styles="light" data-toggled="close">

<head>

    <!-- Meta Data -->
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title> MMP Login</title>
    <meta name="Description" content="Bootstrap Responsive Admin Web Dashboard HTML5 Template">
    <meta name="Author" content="Wcsrm Software Private Limited">
    <meta name="keywords"
        content="bootstrap template, admin panel bootstrap, bootstrap dashboard, admin, admin dashboard template, dashboard template, html css templates, dashboard, template dashboard,  bootstrap dashboard template, dashboard html css, bootstrap admin dashboard,  bootstrap admin, dashboard template, bootstrap5 admin template">
    <!-- Favicon -->
    <link rel="icon" href="<?php echo e(url('public/assets/images/brand-logos/favicon.ico')); ?>" type="image/x-icon">

    <!-- Main Theme Js -->
    <script src="<?php echo e(url('public/assets/js/authentication-main.js')); ?>"></script>

    <!-- Bootstrap Css -->
    <link id="style" href="<?php echo e(url('public/assets/libs/bootstrap/css/bootstrap.min.css')); ?>" rel="stylesheet">

    <!-- Style Css -->
    <link href="<?php echo e(url('public/assets/css/styles.css')); ?>" rel="stylesheet">

    <!-- Icons Css -->
    <link href="<?php echo e(url('public/assets/css/icons.css')); ?>" rel="stylesheet">

    <!-- Choices Css -->
    <link rel="stylesheet" href="<?php echo e(url('public/assets/libs/choices.js/public/assets/styles/choices.min.css')); ?>">

    <!-- FlatPickr CSS -->
    <link rel="stylesheet" href="<?php echo e(url('public/assets/libs/flatpickr/flatpickr.min.css')); ?>">

    <!-- Auto Complete CSS -->
    <link rel="stylesheet" href="<?php echo e(url('public/assets/libs/@tarekraafat/autocomplete.js/css/autoComplete.css')); ?>">

    <!-- Main Theme Js -->
    <script src="<?php echo e(asset('public/assets/js/authentication-main.js')); ?>"></script>
    <!-- Bootstrap Css -->
    <link id="style" href="<?php echo e(asset('public/assets/libs/bootstrap/css/bootstrap.min.css')); ?>" rel="stylesheet">
    <!-- Style Css -->
    <link href="<?php echo e(asset('public/assets/css/styles.css')); ?>" rel="stylesheet">
    <!-- Icons Css -->
    <link href="<?php echo e(asset('public/assets/css/icons.css')); ?>" rel="stylesheet">
</head>

<body class="authenticationcover-background bg-primary-transparent position-relative" id="particles-js">

    <?php echo $__env->yieldContent('content'); ?>


    <!-- Bootstrap JS -->
    <script src="<?php echo e(url('public/assets/libs/bootstrap/js/bootstrap.bundle.min.js')); ?>"></script>

    <!-- Particles JS -->
    <script src="<?php echo e(url('public/assets/libs/particles.js/particles.js')); ?>"></script>

    <script src="<?php echo e(url('public/assets/js/cover-password.js')); ?>"></script>

    <!-- Show Password JS -->
    <script src="<?php echo e(url('public/assets/js/show-password.js')); ?>"></script>

    <!-- CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.css">
    <!-- JS -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.js"></script>


    <script>
        <?php if(session('success')): ?>
            toastr.success("<?php echo e(session('success')); ?>");
        <?php endif; ?>

        <?php if(session('error')): ?>
            toastr.error("<?php echo e(session('error')); ?>");
        <?php endif; ?>

        <?php if($errors->any()): ?>
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                toastr.error("<?php echo e($error); ?>");
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php endif; ?>
    </script>


</body>

</html>
<?php /**PATH C:\xampp\htdocs\MMP\resources\views/layouts/authentication.blade.php ENDPATH**/ ?>