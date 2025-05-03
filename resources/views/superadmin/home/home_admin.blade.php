<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Tuno For | Internal Features</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="web/template/assets/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="web/template/assets/vendors/css/vendor.bundle.base.css">
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <link rel="stylesheet" href="web/template/assets/vendors/jvectormap/jquery-jvectormap.css">
    <link rel="stylesheet" href="web/template/assets/vendors/flag-icon-css/css/flag-icon.min.css">
    <link rel="stylesheet" href="web/template/assets/vendors/owl-carousel-2/owl.carousel.min.css">
    <link rel="stylesheet" href="web/template/assets/vendors/owl-carousel-2/owl.theme.default.min.css">
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <!-- endinject -->
    <!-- Layout styles -->
    <link rel="stylesheet" href="web/template/assets/css/style.css">
    <!-- End layout styles -->
    <link rel="shortcut icon" href="web/template/assets/images/favicon.png" />
</head>

<body>
    <div class="container-scroller">
        <!-- partial:partials/_sidebar.html -->
        <nav class="sidebar sidebar-offcanvas" id="sidebar">
            @include('superadmin.home.partials.navbar')
        </nav>
        <!-- partial -->
        <div class="container-fluid page-body-wrapper">
            <!-- partial:partials/_navbar.html -->
            <nav class="navbar p-0 fixed-top d-flex flex-row">
                @include('superadmin.home.partials.navbar_body_wrapper')
            </nav>
            <!-- partial -->
            <div class="main-panel">
                <div class="content-wrapper">
                    @include('superadmin.home.partials.potential_wrapper')
                    @include('superadmin.home.partials.revenue_wrapper')
                    @include('superadmin.home.partials.daily_wrapper')
                    @include('superadmin.home.partials.order_status')
                    <div class="row">
                        <div class="col-md-6 col-xl-4 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex flex-row justify-content-between">
                                        <h4 class="card-title">Messages</h4>
                                        <p class="text-muted mb-1 small">View all</p>
                                    </div>
                                    <div class="preview-list">
                                        <div class="preview-item border-bottom">
                                            <div class="preview-thumbnail">
                                                <img src="assets/images/faces/face6.jpg" alt="image"
                                                    class="rounded-circle" />
                                            </div>
                                            <div class="preview-item-content d-flex flex-grow">
                                                <div class="flex-grow">
                                                    <div class="d-flex d-md-block d-xl-flex justify-content-between">
                                                        <h6 class="preview-subject">Leonard</h6>
                                                        <p class="text-muted text-small">5 minutes ago</p>
                                                    </div>
                                                    <p class="text-muted">Well, it seems to be working now.</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="preview-item border-bottom">
                                            <div class="preview-thumbnail">
                                                <img src="assets/images/faces/face8.jpg" alt="image"
                                                    class="rounded-circle" />
                                            </div>
                                            <div class="preview-item-content d-flex flex-grow">
                                                <div class="flex-grow">
                                                    <div class="d-flex d-md-block d-xl-flex justify-content-between">
                                                        <h6 class="preview-subject">Luella Mills</h6>
                                                        <p class="text-muted text-small">10 Minutes Ago</p>
                                                    </div>
                                                    <p class="text-muted">Well, it seems to be working now.</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="preview-item border-bottom">
                                            <div class="preview-thumbnail">
                                                <img src="assets/images/faces/face9.jpg" alt="image"
                                                    class="rounded-circle" />
                                            </div>
                                            <div class="preview-item-content d-flex flex-grow">
                                                <div class="flex-grow">
                                                    <div class="d-flex d-md-block d-xl-flex justify-content-between">
                                                        <h6 class="preview-subject">Ethel Kelly</h6>
                                                        <p class="text-muted text-small">2 Hours Ago</p>
                                                    </div>
                                                    <p class="text-muted">Please review the tickets</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="preview-item border-bottom">
                                            <div class="preview-thumbnail">
                                                <img src="assets/images/faces/face11.jpg" alt="image"
                                                    class="rounded-circle" />
                                            </div>
                                            <div class="preview-item-content d-flex flex-grow">
                                                <div class="flex-grow">
                                                    <div class="d-flex d-md-block d-xl-flex justify-content-between">
                                                        <h6 class="preview-subject">Herman May</h6>
                                                        <p class="text-muted text-small">4 Hours Ago</p>
                                                    </div>
                                                    <p class="text-muted">Thanks a lot. It was easy to fix it .</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-xl-4 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Portfolio Slide</h4>
                                    <div class="owl-carousel owl-theme full-width owl-carousel-dash portfolio-carousel"
                                        id="owl-carousel-basic">
                                        <div class="item">
                                            <img src="assets/images/dashboard/Rectangle.jpg" alt="">
                                        </div>
                                        <div class="item">
                                            <img src="assets/images/dashboard/Img_5.jpg" alt="">
                                        </div>
                                        <div class="item">
                                            <img src="assets/images/dashboard/img_6.jpg" alt="">
                                        </div>
                                    </div>
                                    <div class="d-flex py-4">
                                        <div class="preview-list w-100">
                                            <div class="preview-item p-0">
                                                <div class="preview-thumbnail">
                                                    <img src="assets/images/faces/face12.jpg" class="rounded-circle"
                                                        alt="">
                                                </div>
                                                <div class="preview-item-content d-flex flex-grow">
                                                    <div class="flex-grow">
                                                        <div
                                                            class="d-flex d-md-block d-xl-flex justify-content-between">
                                                            <h6 class="preview-subject">CeeCee Bass</h6>
                                                            <p class="text-muted text-small">4 Hours Ago</p>
                                                        </div>
                                                        <p class="text-muted">Well, it seems to be working now.</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="text-muted">Well, it seems to be working now. </p>
                                    <div class="progress progress-md portfolio-progress">
                                        <div class="progress-bar bg-success" role="progressbar" style="width: 50%"
                                            aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 col-xl-4 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">To do list</h4>
                                    <div class="add-items d-flex">
                                        <input type="text" class="form-control todo-list-input"
                                            placeholder="enter task..">
                                        <button class="add btn btn-primary todo-list-add-btn">Add</button>
                                    </div>
                                    <div class="list-wrapper">
                                        <ul class="d-flex flex-column-reverse text-white todo-list todo-list-custom">
                                            <li>
                                                <div class="form-check form-check-primary">
                                                    <label class="form-check-label">
                                                        <input class="checkbox" type="checkbox"> Create invoice
                                                    </label>
                                                </div>
                                                <i class="remove mdi mdi-close-box"></i>
                                            </li>
                                            <li>
                                                <div class="form-check form-check-primary">
                                                    <label class="form-check-label">
                                                        <input class="checkbox" type="checkbox"> Meeting with Alita
                                                    </label>
                                                </div>
                                                <i class="remove mdi mdi-close-box"></i>
                                            </li>
                                            <li class="completed">
                                                <div class="form-check form-check-primary">
                                                    <label class="form-check-label">
                                                        <input class="checkbox" type="checkbox" checked> Prepare for
                                                        presentation </label>
                                                </div>
                                                <i class="remove mdi mdi-close-box"></i>
                                            </li>
                                            <li>
                                                <div class="form-check form-check-primary">
                                                    <label class="form-check-label">
                                                        <input class="checkbox" type="checkbox"> Plan weekend outing
                                                    </label>
                                                </div>
                                                <i class="remove mdi mdi-close-box"></i>
                                            </li>
                                            <li>
                                                <div class="form-check form-check-primary">
                                                    <label class="form-check-label">
                                                        <input class="checkbox" type="checkbox"> Pick up kids from
                                                        school </label>
                                                </div>
                                                <i class="remove mdi mdi-close-box"></i>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Visitors by Countries</h4>
                                    <div class="row">
                                        <div class="col-md-5">
                                            <div class="table-responsive">
                                                <table class="table">
                                                    <tbody>
                                                        <tr>
                                                            <td>
                                                                <i class="flag-icon flag-icon-us"></i>
                                                            </td>
                                                            <td>USA</td>
                                                            <td class="text-right"> 1500 </td>
                                                            <td class="text-right font-weight-medium"> 56.35% </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <i class="flag-icon flag-icon-de"></i>
                                                            </td>
                                                            <td>Germany</td>
                                                            <td class="text-right"> 800 </td>
                                                            <td class="text-right font-weight-medium"> 33.25% </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <i class="flag-icon flag-icon-au"></i>
                                                            </td>
                                                            <td>Australia</td>
                                                            <td class="text-right"> 760 </td>
                                                            <td class="text-right font-weight-medium"> 15.45% </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <i class="flag-icon flag-icon-gb"></i>
                                                            </td>
                                                            <td>United Kingdom</td>
                                                            <td class="text-right"> 450 </td>
                                                            <td class="text-right font-weight-medium"> 25.00% </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <i class="flag-icon flag-icon-ro"></i>
                                                            </td>
                                                            <td>Romania</td>
                                                            <td class="text-right"> 620 </td>
                                                            <td class="text-right font-weight-medium"> 10.25% </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <i class="flag-icon flag-icon-br"></i>
                                                            </td>
                                                            <td>Brasil</td>
                                                            <td class="text-right"> 230 </td>
                                                            <td class="text-right font-weight-medium"> 75.00% </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="col-md-7">
                                            <div id="audience-map" class="vector-map"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- content-wrapper ends -->
                <!-- partial:partials/_footer.html -->
                <footer class="footer">
                    <div class="d-sm-flex justify-content-center justify-content-sm-between">
                        <span class="text-muted d-block text-center text-sm-left d-sm-inline-block">Copyright ©
                            bootstrapdash.com 2020</span>
                        <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center"> Free <a
                                href="https://www.bootstrapdash.com/bootstrap-admin-template/"
                                target="_blank">Bootstrap admin templates</a> from Bootstrapdash.com</span>
                    </div>
                </footer>
                <!-- partial -->
            </div>
            <!-- main-panel ends -->
        </div>
        <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
    <!-- plugins:js -->
    <script src="web/template/assets/vendors/js/vendor.bundle.base.js"></script>
    <!-- endinject -->
    <!-- Plugin js for this page -->
    <script src="web/template/assets/vendors/chart.js/Chart.min.js"></script>
    <script src="web/template/assets/vendors/progressbar.js/progressbar.min.js"></script>
    <script src="web/template/assets/vendors/jvectormap/jquery-jvectormap.min.js"></script>
    <script src="web/template/assets/vendors/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
    <script src="web/template/assets/vendors/owl-carousel-2/owl.carousel.min.js"></script>
    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <script src="web/template/assets/js/off-canvas.js"></script>
    <script src="web/template/assets/js/hoverable-collapse.js"></script>
    <script src="web/template/assets/js/misc.js"></script>
    <script src="web/template/assets/js/settings.js"></script>
    <script src="web/template/assets/js/todolist.js"></script>
    <!-- endinject -->
    <!-- Custom js for this page -->
    <script src="web/template/assets/js/dashboard.js"></script>
    <!-- End custom js for this page -->
</body>

</html>
