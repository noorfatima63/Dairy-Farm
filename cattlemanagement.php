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
            loadCattleTable('getCattleProfiles')
            $('#addCowButton').click(function() {
                resetForm();
        $('#addCowModal').modal('show');
      });
      $('#add').click(function() {
    $('#addCowModal').modal('hide');
});
      
      
      $('#addCattleForm').submit(function (event) {
        console.log("hello")

                event.preventDefault();
                var formMode = $('#formMode').val();
                var formData = {
                    cattleID: $('#cattleId').val(),
                    name: $('#name').val(),
                    breed: $('#breed').val(),
                    group: $('#group').val(),
                    weight: $('#weight').val(),
                    milkProduction: $('#milkProduction').val(),
                    diet: $('#diet').val(),
                    temperature: $('#temperature').val(),
                    medicalHistory: $('#medicalHistory').val(),
                    price: $('#price').val()
                };

                if (formMode === 'add') {
                    // Add cattle record to the table
                    addCattle(formData);
                } else if (formMode === 'edit') {
                    // Update cattle record in the table
                    updateCattle(formData);
                }
                

                // Reset the form
                resetForm();
            });
            function resetForm() {
                $('#formMode').val('add');
                $('#cattleId').val('');
                $('#addCattleForm')[0].reset();
            }
            function addCattle(formData) {
                console.log(formData);
                $.ajax({
                    method: 'GET',
                    url: 'backend.php',
                    data: {
                        action: 'addCattle',
                        name: formData.name,
                        breed: formData.breed,
                        group: formData.group,
                        weight: formData.weight,
                        milkProduction: formData.milkProduction,
                        diet: formData.diet,
                        temperature: formData.temperature,
                        medicalHistory: formData.medicalHistory,
                        price: formData.price
                    },
                    dataType: 'json',
                    success: function (response) {
                        loadCattleTable('getCattleProfiles');
                        
                    },
                    error: function () {
                        alert('Error adding cattle record.');
                    }
                });
            }
            function loadCattleTable(action) {
                $.ajax({
                    url: 'backend.php',
                    method: 'GET',
                    data: {
                        action: action
                    },
                    success: function (response) {
                        var cattleRecords = JSON.parse(response);
                        var tableBody = $('#cattleTableBody');
                        tableBody.empty();

                        $.each(cattleRecords.data, function (index, record) {
                            var row = $('<tr>');
                            row.append($('<td>').text(record.name));
                            row.append($('<td>').text(record.breed));
                            row.append($('<td>').text(record.group_name));
                            row.append($('<td>').text(record.weight));
                            row.append($('<td>').text(record.milk_production));
                            row.append($('<td>').text(record.diet));
                            row.append($('<td>').text(record.temp));
                            row.append($('<td>').text(record.medical_history));
                            row.append($('<td>').text(record.price));

                            console.log(typeof (record.diet_assigned_date));

                            // Add the diet assigned date to the table


                            // Add edit and delete buttons to each row
                            var actions = $('<td>');
                            var editButton = $('<button>').text('Edit');
                            editButton.attr('id','editbutton');
                            editButton.click(function () {
                                editCattle(record);
                                $('#addCowModal').modal('show');
                            });
                            
                            var deleteButton = $('<button>').text('Delete');
                            deleteButton.attr('id','deletebutton');
                            deleteButton.click(function () {
                                deleteCattle(record.id, 'deleteCattle');
                            });
                            editButton.addClass("btn btn-danger");
                            deleteButton.addClass("btn btn-success");
                            actions.append(editButton);
                            actions.append(deleteButton);
                            row.append(actions);

                            tableBody.append(row);
                        });
                    },
                    error: function () {
                        alert('Error loading cattle records.');
                    }
                });
            }
            function editCattle(record) {
                $('#formMode').val('edit');
                $('#cattleId').val(record.id);
                $('#name').val(record.name);
                $('#breed').val(record.breed);
                $('#group').val(record.group_name);
                $('#weight').val(record.weight);
                $('#milkProduction').val(record.milk_production);
                $('#diet').val(record.diet);
                $('#temperature').val(record.temp);
                $('#medicalHistory').val(record.medical_history);
                $('#price').val(record.price);
            }
            function updateCattle(formData) {
                console.log(formData);
                $.ajax({
                    type: 'GET',
                    url: 'backend.php',
                    data: {
                        action: 'updateCattle',
                        cattleID: formData.cattleID,
                        name: formData.name,
                        breed: formData.breed,
                        group: formData.group,
                        weight: formData.weight,
                        milkProduction: formData.milkProduction,
                        diet: formData.diet,
                        temperature: formData.temperature,
                        medicalHistory: formData.medicalHistory,
                        price: formData.price
                    },
                    dataType: 'json',
                    success: function (response) {
                        // Reload the cattle table
                        loadCattleTable('getCattleProfiles');
                    },
                    error: function () {
                        alert('Error updating cattle record.');
                    }
                });
            }
            function deleteCattle(cattleID, action) {
                let conf = confirm("Are you sure, You want to delete this Record");
                if (conf) {
                    $.ajax({
                        type: 'GET',
                        url: 'backend.php',
                        data: {
                            action: action,
                            cattleID: cattleID
                        },
                        dataType: 'json',
                        success: function (response) {
                            if (response.status === 'success') {
                                // Reload the cattle table
                                loadCattleTable('getCattleProfiles');
                            }
                        },
                        error: function () {
                            alert('Error deleting cattle record.');
                        }
                    });
                }
            }

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
           <div class="container-fluid">
        <div class="row">
          <div class="col">
            <hr class="my-4">
            <button class="btn btn-primary btn-lg" id="addCowButton">Add Cow</button>
            <br><br>
            <div class="table-responsive">
              <table class="table table-striped table-bordered" id="cowTable">
                <thead>
                  <tr>
                    <th>Name</th>
                    <th>Breed</th>
                    <th>Group</th>
                    <th>Weight</th>
                    <th>Milk Production</th>
                    <th>Diet</th>
                    <th>Temperature</th>
                    <th>Medical History</th>
                    <th>Price</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody id="cattleTableBody">
                  <!-- Table content goes here -->
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
      <div class="modal fade" id="addCowModal" tabindex="-1" aria-labelledby="addCowModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="addCowModalLabel">Add Cow</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addCattleForm">
                    <input type="hidden" id="formMode" value="add">
                    <input type="hidden" id="cattleId">

                    <!-- Add form fields for cattle attributes -->
                    <label for="name" class='form-label'>Name:</label>
                    <input type="text" class="form-control" id="name" name="name" required><br>

                    <!-- Add form fields for other cattle attributes -->
                    <label for="breed"class='form-label'>Breed:</label>
                    <input type="text" class="form-control"id="breed" name="breed" required><br>

                    <label for="group"class='form-label'>Group:</label>
                    <select id="group"class='form-select' name="group" required>

                        <option value="A">A</option>
                        <option value="B">B</option>
                        <option value="C">C</option>
                    </select><br>

                    <label for="weight"class='form-label'>Weight:</label>
                    <input type="number"class="form-control" id="weight" name="weight" required><br>

                    <label for="milkProduction"class='form-label'>Milk Production:</label>
                    <input type="number"class="form-control" id="milkProduction" name="milkProduction" required><br>

                    <label for="diet"class='form-label'>Diet:</label>
                    <select id="diet"class='form-select' name="group" required>

                        <option value="Diet A">Diet A</option>
                        <option value="Diet B">Diet B</option>
                        <option value="Diet c">Diet c</option>
                    </select><br>

                    <label for="temperature"class='form-label'>Temperature:</label>
                    <input type="number"class="form-control" id="temperature" name="temperature" required><br>

                    <label for="medicalHistory"class='form-label'>Medical History:</label>
                    <input type="text"class="form-control" id="medicalHistory" name="medicalHistory" required><br>

                    <label for="price"class='form-label'>Price:</label>
                    <input type="number"class="form-control" id="price" name="price" required><br>

                    <button type="submit"class="form-control" id="add" class="btn btn-primary btn-lg">Add</button>
                </form>
            </div>
          </div>
        </div>
      </div>

                
            

        </div>
    </div>
</body>

</html>