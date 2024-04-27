<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Modernize Free</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
        crossorigin="anonymous"></script>
    <link rel="shortcut icon" type="image/png" href="../assets/images/logos/favicon.png" />
    <link rel="stylesheet" href="./assets/css/styles.min.css" />
    <style>
        .page-wrapper {
            transition: margin-left 0.3s;
        }

        .body-wrapper.sidebar-open #main-wrapper {
            margin-left: 250px;
        }
    </style>
    <script src="jquery.js"></script>
    <script>
        $(document).ready(function () {
            loadContent('getCattleProfiles');
            // Toggle sidebar on burger icon click
            $('#headerCollapse').click(function () {
                $('body').toggleClass('sidebar-open');
            });

            // Close sidebar on close button click
            $('#sidebarCollapse').click(function () {
                $('body').removeClass('sidebar-open');
            });


            // Function to load the content dynamically based on the selected navigation item
            function loadContent(action) {
                $.ajax({
                    url: 'backend.php',
                    method: 'GET',
                    data: {
                        action: action
                    },
                    success: function (response) {
                        // Clear the content div
                        $('#content').empty();

                        // Check the action and update the content accordingly
                        if (action === 'getCattleProfiles') {
                            console.log(response);
                            displayCattleProfiles(JSON.parse(response));
                        }
                    }
                });
            }
            function displayCattleProfiles(cattleProfiles) {
                var contentDiv = $('#content');
                var milk=0;



                for (var i = 0; i < cattleProfiles.data.length; i++) {
                    var cattle = cattleProfiles.data[i];
                        countA++;
                        milk += parseInt(cattle.milk_production);
                    
                }
                $('#daily').text(milk);
                $('#weekly').text(milk*7);
                $('#monthly').text(milk*30);
                $('#annually').text(milk*365);

                // Create the cards for each group
                var cardA = $('<div>').addClass('card bg-info order-card');
                var cardB = $('<div>').addClass('card bg-info order-card');
                var cardC = $('<div>').addClass('card bg-info order-card');

                // Create the card bodies
                var cardBodyA = $('<div>').addClass('card-block');
                var cardBodyB = $('<div>').addClass('card-block');
                var cardBodyC = $('<div>').addClass('card-block');

                // Count the number of cows and calculate the total milk production for each group
                var countA = 0;
                var countB = 0;
                var countC = 0;
                var totalMilkA = 0;
                var totalMilkB = 0;
                var totalMilkC = 0;

                // Loop through the cattle profiles to count the cows and calculate the total milk production
                for (var i = 0; i < cattleProfiles.data.length; i++) {
                    var cattle = cattleProfiles.data[i];

                    if (cattle.group_name === 'A') {
                        countA++;
                        totalMilkA += parseInt(cattle.milk_production);
                    } else if (cattle.group_name === 'B') {
                        countB++;
                        totalMilkB += parseInt(cattle.milk_production);
                    } else if (cattle.group_name === 'C') {
                        countC++;
                        totalMilkC += parseInt(cattle.milk_production);
                    }
                }

                // Create the content for each card
                var cardContentA = $('<h6>').addClass('m-b-20').text('Group A');
                cardContentA.append($('<hr>'));
                cardContentA.append($('<h2>').addClass('text-right').html('<i class="fa fa-cart-plus f-left"></i><span> Number Of Cows  : ' + countA + '</span>'));
                cardContentA.append($('<p>').addClass('text-right').text('Total Milk Production  :  ' + totalMilkA + '  liter'));

                var cardContentB = $('<h6>').addClass('m-b-20').text('Group B');
                cardContentB.append($('<hr>'));
                cardContentB.append($('<h2>').addClass('text-right').html('<i class="fa fa-rocket f-left"></i><span> Number Of Cows  :  ' + countB + '</span>'));
                cardContentB.append($('<p>').addClass('text-right').text('Total Milk Production  : ' + totalMilkB + '  liter'));

                var cardContentC = $('<h6>').addClass('m-b-20').text('Group C');
                cardContentC.append($('<hr>'));
                cardContentC.append($('<h2>').addClass('text-right').html('<i class="fa fa-refresh f-left"></i><span> Number Of Cows  :  ' + countC + '</span>'));
                cardContentC.append($('<p>').addClass('text-right').text('Total Milk Production  : ' + totalMilkC + '  liter'));

                // Append the card contents to the card bodies
                cardBodyA.append(cardContentA);
                cardBodyB.append(cardContentB);
                cardBodyC.append(cardContentC);

                // Append the card bodies to the cards
                cardA.append(cardBodyA);
                cardB.append(cardBodyB);
                cardC.append(cardBodyC);

                // Append the cards to the content div
                contentDiv.append(cardA, cardB, cardC);
            }

        }) 
    </script>

</head>

<body>

    <!--  Body Wrapper -->
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
        data-sidebar-position="fixed" data-header-position="fixed">
        <!-- Sidebar Start -->
        <aside class="left-sidebar bg-light-primary">
            <!-- Sidebar scroll-->
            <div>
                <div class="brand-logo d-flex align-items-center justify-content-between">
                    <a href="./index.html" class="text-nowrap logo-img">
                        <img src="./assets/images/logos/dark-logo.svg" width="180" alt="" />
                    </a>
                    <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
                        <i class="ti ti-x fs-8"></i>
                    </div>
                </div>
                <!-- Sidebar navigation-->
                <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
                    <ul id="sidebarnav">
                        <li class="nav-small-cap">
                            <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                            <span class="hide-menu"></span>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="dashboard.php" aria-expanded="false">
                                <span>
                                    <i class="ti ti-layout-dashboard"></i>
                                </span>
                                <span class="hide-menu">Dashboard</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="cattlemanagement.php" aria-expanded="false">
                                <span>
                                    <i class="ti ti-article"></i>
                                </span>
                                <span class="hide-menu">Cattle Management</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="assigndiets.php" aria-expanded="false">
                                <span>
                                    <i class="ti ti-alert-circle"></i>
                                </span>
                                <span class="hide-menu">Assign Diets</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="./ui-card.html" aria-expanded="false">
                                <span>
                                    <i class="ti ti-cards"></i>
                                </span>
                                <span class="hide-menu">Charts & Reports</span>
                            </a>
                        </li>
                        
                        

                    </ul>
                </nav>
                <!-- End Sidebar navigation -->
            </div>
            <!-- End Sidebar scroll-->
        </aside>
        <!--  Sidebar End -->
        <!--  Main wrapper -->
        <div class="body-wrapper">
            <!--  Header Start -->
            <header class="app-header ">
                <nav class="navbar navbar-expand-lg  ">
                    <ul class="navbar-nav">
                        <li class="nav-item d-block d-xl-none">
                            <a class="nav-link sidebartoggler nav-icon-hover" id="headerCollapse"
                                href="javascript:void(0)">
                                <i class="ti ti-menu-2"></i>
                            </a>
                        </li>

                    </ul>
                    <div class="navbar-collapse justify-content-end px-0" id="navbarNav">
                        <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-end">
                            <li class="nav-item">
                                <a class="nav-link nav-icon-hover" href="javascript:void(0)">
                                    <i class="ti ti-bell-ringing"></i>
                                    <div class="notification bg-primary rounded-circle"></div>
                                </a>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link nav-icon-hover" href="javascript:void(0)" id="drop2"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <img src="./assets/images/profile/user-1.jpg" alt="" width="35" height="35"
                                        class="rounded-circle">
                                </a>
                                <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up"
                                    aria-labelledby="drop2">
                                    <div class="message-body">

                                        <a href="./authentication-login.html"
                                            class="btn btn-outline-primary mx-3 mt-2 d-block">Logout</a>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </nav>
            </header>
            <!--  Header End -->
            <div class="container-fluid border-danger">
                <div class="container">
                    <h1>Milk Production (liters)</h1>
                    <hr>
                    <div class="row pt-3">
                        <div class="col-md-4 col-xl-3">
                            <div class="card bg-c-green order-card">
                                <div class="card-block">
                                    <h6 class="m-b-20">Daily</h6>
                                    <h2 id="daily" class="text-right"><i class="fa fa-cart-plus f-left"></i><span >486</span></h2>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 col-xl-3">
                            <div class="card bg-c-green order-card">
                                <div class="card-block">
                                    <h6 class="m-b-20">Weekly</h6>
                                    <h2 id="weekly" class="text-right"><i class="fa fa-rocket f-left"></i><span >486</span></h2>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 col-xl-3">
                            <div class="card bg-c-green order-card">
                                <div class="card-block">
                                    <h6 class="m-b-20">Monthly</h6>
                                    <h2 id="monthly" class="text-right"><i class="fa fa-refresh f-left"></i><span id>486</span></h2>

                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 col-xl-3">
                            <div class="card bg-c-green order-card">
                                <div class="card-block">
                                    <h6 class="m-b-20">Annually</h6>
                                    <h2 id="annually" class="text-right"><i class="fa fa-credit-card f-left"></i><span>486</span></h2>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container mt-2">
                <div class="container pl-1">
                    <h2>Groups</h2>
                </div>

                <div class="row">
                    <div class="col">
                        <hr class="my-4">
                        <div id="content" class="table-responsive">

                            <!-- Table content goes here -->
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</body>

</html>