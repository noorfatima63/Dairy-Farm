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
        .popup-container {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
        }

        .popup-content {
            background-color: #fefefe;
            margin: 10% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 300px;
        }

        .popup-content input[type="text"] {
            width: 100%;
            margin-bottom: 10px;
            padding: 5px;
            border: 1px solid #ddd;
        }

        .popup-content button {
            background-color: #4CAF50;
            color: white;
            padding: 8px 12px;
            border: none;
            cursor: pointer;
            width: 100%;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
    <script src="jquery.js"></script>
  <script>
    function openPopup(cattleId) {
            // Get the popup container element
            console.log("hello2");
            var popupContainer =document.getElementById("dietdiv");
            

            // Set the cattle ID in the hidden input field of the popup form
            var cattleIdField = popupContainer.querySelector('#cattleId');
            cattleIdField.value = cattleId;

            // Show the popup container
            $('#addCowModal').modal('show');
        }
        
     
    

        function closePopup() {
            // Get the popup container element
            

            // Hide the popup container
            $('#addCowModal').modal('hide');
        }

        function submitDiet() {
            // Get the diet value from the input field
            console.log("hello3");
            var dietField = document.getElementById('diet');
            var diet = dietField.value;

            // Get the cattle ID from the hidden input field
            var cattleIdField = document.getElementById('cattleId');
            var cattleId = cattleIdField.value;

            console.log(diet);
            console.log(cattleId);

            // Perform the AJAX request to update the diet in the database
            $.ajax({
                url: 'backend.php',
                method: 'GET',
                data: {
                    action: 'updateDiet',
                    cattleId: cattleId,
                    diet: diet
                },
                success: function (response) {
                    // Close the popup after successful update
                    closePopup();
                    loadContent('getCattleProfiles');

                },
                error: function () {
                    alert('Failed to update the diet.');
                }
            });

        }
        loadContent('getCattleProfiles');

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
                        displayCattleProfiles(JSON.parse(response));
                    }
                }
            });
        }

        function displayCattleProfiles(cattleProfiles1) {
            var currentDate = new Date(); // Get the current date

            var cattleProfiles = cattleProfiles1.data.filter(function (cattle) {
                // Parse the diet_assigned_date as a Date object
                var assignedDate = new Date(cattle.diet_assigned_date);

                console.log(currentDate.getTime());
                console.log(assignedDate.getTime())

                // Calculate the time difference in milliseconds
                var timeDiff = assignedDate.getTime() - currentDate.getTime();

                // Calculate the number of days
                var daysDiff = Math.floor(timeDiff / (1000 * 60 * 60 * 24));

                var num = parseInt(cattle.milk_production);

                // Filter cattle records where the time difference is greater than or equal to 1 day
                if (num < 15) {

                    if (daysDiff < -1) {
                        console.log(daysDiff);
                        return true;
                    }
                }
            });

            var contentDiv = $('#content');

            // Create the table element
            var table = $('<table>');
            table.addClass('cattle-table');
            table.addClass("table table-striped table-bordered");

            // Create the table header row
            var tableHeaderRow = $('<tr>');
            
            tableHeaderRow.append($('<th>Name</th>'));
            tableHeaderRow.append($('<th>Group</th>'));
            tableHeaderRow.append($('<th>Weight</th>'));
            tableHeaderRow.append($('<th>Milk Production</th>'));
            tableHeaderRow.append($('<th>Diet</th>'));
            tableHeaderRow.append($('<th>Diet Assign Date</th>'));
            tableHeaderRow.append($('<th>Temperature</th>'));
            tableHeaderRow.append($('<th>Medical History</th>'));
            tableHeaderRow.append($('<th>Price</th>'));
            tableHeaderRow.append($('<th>Action</th>'));

            // Append the table header row to the table
            table.append(tableHeaderRow);

            // Loop through the cattle profiles and create the table rows
            for (var i = 0; i < cattleProfiles.length; i++) {
                var cattle = cattleProfiles[i];

                // Create a table row for each cattle
                var tableRow = $('<tr>');
                tableRow.attr('data-cattle-id', cattle.id);
                tableRow.append($('<td>' + cattle.name + '</td>'));
                tableRow.append($('<td>' + cattle.group_name + '</td>'));
                tableRow.append($('<td>' + cattle.weight + '</td>'));
                tableRow.append($('<td>' + cattle.milk_production + '</td>'));
                tableRow.append($('<td>' + cattle.diet + '</td>'));
                tableRow.append($('<td>' + cattle.diet_assigned_date + '</td>'));
                tableRow.append($('<td>' + cattle.temp + '</td>'));
                tableRow.append($('<td>' + cattle.medical_history + '</td>'));
                tableRow.append($('<td>' + cattle.price + '</td>'));

                // Create the "Assign Diet" button for each cattle
                var assignDietButton = $('<button>');
                assignDietButton.text('Assign Diet');
                assignDietButton.addClass("btn btn-success");
                assignDietButton.click(function (cattleId) {
                    console.log("hello");
                    return function () {
                    
                        openPopup(cattleId);
                    }
                }(cattle.id));

                // Create a table cell for the "Assign Diet" button
                var tableCell = $('<td>');
                tableCell.append(assignDietButton);

                // Append the table cell to the table row
                tableRow.append(tableCell);

                // Append the table row to the table
                table.append(tableRow);
            }

            // Append the table to the content div
            contentDiv.append(table);
        }

        $(document).ready(function () {
            // Load the initial content based on the selected navigation item

        });
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
           <div id="dashboard" class="container-fluid">
           <div>
            <h1>Assign Diet Plans</h1>
            <hr>
        </div>
        <div id="content">
            <!-- Content will be dynamically loaded here -->
        </div>
      </div>
      <div class="modal fade" id="addCowModal" tabindex="-1" aria-labelledby="addCowModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="addCowModalLabel">Assign Diet</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div id="dietdiv" class="modal-body" >
                <form id="addCattleForm" onsubmit="submitDiet()">
                    <input type="hidden" id="formMode" value="add">
                    <input type="hidden" id="cattleId">

            
                    <label for="diet"class='form-label'>Diet:</label>
                    <select id="diet"class='form-select' name="group" required>

                        <option value="Diet A">Diet A</option>
                        <option value="Diet B">Diet B</option>
                        <option value="Diet c">Diet c</option>
                    </select><br>

                    <button type="submit" class="form-control" id="add" class="btn btn-primary btn-lg">Add</button>
                </form>
            </div>
          </div>
        </div>
      </div>

                
            

        </div>
        
    </div>
    </div>
    <!-- <div id="popupContainer" class="popup-container">
        <div class="popup-content">
            <span class="close" onclick="closePopup()">&times;</span>
            <h3>Assign Diet</h3>
            <input type="hidden" id="cattleId">
            <input type="text" id="dietField" placeholder="Enter diet">
            <button onclick="submitDiet()">Submit</button>
        </div> -->
</body>

</html>