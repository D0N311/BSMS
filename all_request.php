<?php
include "bootstrap/index.php";

use Carbon\Carbon;


$certificates = $db
	->from("certificates")
	->select([
		"id" => "certificates.id",
		"name" => "certificates.name",
		"url" => "certificates.url",
	])
	->exec();

$resident_details = (function () use ($db) {
	if (isUser()) {
		return $db
			->from("residents")
			->where("account_id", $_SESSION["id"])
			->first()
			->select([
				"id" => "residents.id",
			])
			->exec();
	}

	return [];
})();

$request_list = (function () use ($db) {
	if (isUser()) {
		$resident_details = $GLOBALS["resident_details"];

		return $db
			->from(["certificate_requests" => "cr"])
			->join("certificates", "certificates.id", "cr.certificate_id")
			->where("cr.resident_id", $resident_details["id"])
			->select([
				"id" => "cr.id",
				"certificate_id" => "cr.certificate_id",
				"status" => "cr.status",
				"memo" => "cr.memo",
        "name" => "cr.business_name",
        "business_owner_1" => "cr.business_owner_1",
        "business_owner_2" => "cr.business_owner_2",
        "business_nature" => "cr.business_nature",
        "material" => "cr.cutting_material",
        "quantity" => "cr.cutting_quantity",
        "location" => "cr.cutting_location",
				"created_at" => "cr.created_at",
				"certificate_id" => "certificates.id",
				"certificate_name" => "certificates.name",
			])
			->exec();
	}

	if (role(["administrator", "staff"])) {
		return $db
			->from(["certificate_requests" => "cr"])
			->join("certificates", "certificates.id", "cr.certificate_id")
			->join("residents", "residents.id", "cr.resident_id")
      ->where("cr.status", "resolved") // Use "resolved" to exclude
      ->orderBy("cr.created_at", "desc")
			->select([
				"id" => "cr.id",
				"certificate_id" => "cr.certificate_id",
				"status" => "cr.status",
				"memo" => "cr.memo",
        "name" => "cr.business_name",
        "business_owner_1" => "cr.business_owner_1",
        "business_owner_2" => "cr.business_owner_2",
        "business_nature" => "cr.business_nature",
        "material" => "cr.cutting_material",
        "quantity" => "cr.cutting_quantity",
        "location" => "cr.cutting_location",
				"created_at" => "cr.created_at",
				"certificate_id" => "certificates.id",
				"certificate_name" => "certificates.name",
				"certificate_url" => "cr.url",
				"resident_id" => "residents.id",
				"firstname" => "residents.firstname",
				"middlename" => "residents.middlename",
				"lastname" => "residents.lastname",
			])
			->exec();
	}
})();


?>

<!DOCTYPE html>
<html lang="en">

  <head>
    <?php include "templates/header.php"; ?>
    <title>Announcements - Barangay Services Management System</title>
  </head>

  <body>
    <?php include "templates/loading_screen.php"; ?>
    <div class="wrapper">
      <!-- Main Header -->
      <?php include "templates/main-header.php"; ?>
      <!-- End Main Header -->

      <!-- Sidebar -->
      <?php include "templates/sidebar.php"; ?>
      <!-- End Sidebar -->

      <div class="main-panel">
        <div class="content">
          <div class="panel-header bg-primary-gradient">
            <div class="page-inner">
              <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                <h2 class="text-white fw-bold">Resolved Request</h2>
              </div>
            </div>
          </div>
          <div class="page-inner">

            <?php include "templates/alert.php"; ?>

            <div class="row mt--2">
              <div class="col-md-12">
                <div class="card">
                  <div class="card-header">
                    <div class="card-head-row">
                      <div class="card-title">Resolved list</div>
                      <?php if (isUser()): ?>
                      <div class="card-tools">
                        <a href="#add" data-toggle="modal" class="btn btn-info btn-border btn-round btn-sm">
                          <i class="fa fa-plus"></i>
                          Request
                        </a>
                      </div>
                      <?php endif; ?>
                    </div>
                  </div>
                  <div class="card-body">
                    <div class="table-responsive">
                      <table id="announcement-table" class="display table table-striped">
                        <thead>
                          <tr>
                            <th scope="col">Certificate</th>
                            <?php if (isAdmin()): ?>
                            <th scope="col">Requested By</th>
                            <?php endif; ?>
                            <th scope="col">Memo</th>
                            <th scope="col">Status</th>
                            <th scope="col">Request Date</th>
                            <th scope="col">Action</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php foreach ($request_list as $request): ?>
                          <tr>
                            <td><?= $request["certificate_name"] ?></td>
                            <?php if (isAdmin()): ?>
                            <td><?= fullname($request) ?></td>
                            <?php endif; ?>
                            <td><?= $request["memo"] ?></td>
                            <td><?= ucwords($request["status"]) ?></td>
                            <td>
                              <?= Carbon::create($request["created_at"])->toDayDateTimeString() ?>
                            </td>
                            <td class="d-flex justify-content-center align-items-center gap-3">
                              <?php if (isUser()): ?>
                              <a href="javascript:void(0)" 
                                data-target="#edit-request"
                                data-value-id="<?= $request["id"] ?>" 
                                data-value-memo="<?= $request["memo"] ?>"
                                data-value-name="<?= $request["name"] ?>"
                                data-value-business_owner_1="<?= $request["business_owner_1"] ?>"
                                data-value-business_owner_2="<?= $request["business_owner_2"] ?>"
                                data-value-business_nature="<?= $request["business_nature"] ?>"
                                data-value-cutting_material="<?= $request["material"] ?>"
                                data-value-cutting_quantity="<?= $request["quantity"] ?>"
                                data-value-cutting_location="<?= $request["location"] ?>"
                                data-value-certificate_id="<?= $request["certificate_id"] ?>" 
                                onclick="showModal(this)">
                                <i class="fa fa-edit"></i>
                              </a>
                              <?php endif; ?>

                              <?php if (role(["administrator", "staff"])): ?>
                                <a href="javascript:void(0)" 
                                  data-target="#edit-request" 
                                  data-value-id="<?= $request["id"] ?>" 
                                  data-value-memo="<?= $request["memo"] ?>"
                                  data-value-name="<?= $request["name"] ?>"
                                  data-value-business_owner_1="<?= $request["business_owner_1"] ?>"
                                  data-value-business_owner_2="<?= $request["business_owner_2"] ?>"
                                  data-value-business_nature="<?= $request["business_nature"] ?>"
                                  data-value-cutting_material="<?= $request["material"] ?>"
                                  data-value-cutting_quantity="<?= $request["quantity"] ?>"
                                  data-value-cutting_location="<?= $request["location"] ?>"
                                  data-value-certificate_id="<?= $request["certificate_id"] ?>" 
                                  onclick="showModal(this)">
                                  <i class="fa fa-eye" data-toggle="tooltip" data-original-title="View"></i>
                                </a>
                              <?php endif; ?>

                              
                              <a href="<?= $request[
                              	"certificate_url"
                              ] ?>" class="btn-link btn-info">
                                <i class="fa fa-file" data-toggle="tooltip" data-original-title="Generate"></i>
                              </a>
                              

                              <?php if (role(["user", "administrator"])): ?>
                              <a data-toggle="tooltip" data-original-title="Remove" href="model/certificate-request.php?id=<?= $request[
                              	"id"
                              ] ?>&delete-request=1"
                                onclick="confirm('Are you sure you want to delete this blotter?');"
                                class=" btn-link btn-danger">
                                <i class="fa fa-times"></i>
                              </a>
                              <?php endif; ?>
                            </td>
                          </tr>
                          <?php endforeach; ?>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="add">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">Request Certificate</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <form method="POST" action="model/certificate-request.php" enctype="multipart/form-data">
                <div class="modal-body">
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label>Certificate</label>
                        <select id="add_certificate_options" name="certificate_id" class="form-control">
                          <option selected disabled>Select Certificate</option>
                          <?php foreach ($certificates as $certificate): ?>
                          <option value="<?= $certificate["id"] ?>">
                            <?= $certificate["name"] ?>
                          </option>
                          <?php endforeach; ?>
                        </select>
                      </div>
                    </div>

                    <div class="col-md-12">
                      <div class="form-group">
                        <label>Memo</label>
                        <textarea type="text" class="form-control" placeholder="Enter content" name="memo"
                          required></textarea>
                      </div>
                    </div>

                    <div id="add_certificate_business_container">
                      <div class="col-md-12">
                        <div class="form-group">
                          <label>Business Name</label>
                          <input class="form-control" placeholder="Enter business name" name="business_name" />
                        </div>
                      </div>

                      <div class="col-md-12">
                        <div class="form-group">
                          <label>Business Owner 1</label>
                          <input class="form-control" placeholder="Enter business name" name="business_owner_1" />
                        </div>
                      </div>

                      <div class="col-md-12">
                        <div class="form-group">
                          <label>Business Owner 2</label>
                          <input class="form-control" placeholder="Enter business name" name="business_owner_2" />
                        </div>
                      </div>

                      <div class="col-md-12">
                        <div class="form-group">
                          <label>Nature of Business</label>
                          <!--<input class="form-control" placeholder="Enter business name" name="business_nature" />-->
                          <select class="form-control" placeholder="Enter business name" name="business_nature" id="business_nature_select">
                          <option selected disabled>Select nature of business</option>
                          <option>Manufacturing</option>
                          <option>Trading</option>
                          <option>Sevice</option>
                          <option>Agriculture</option>
                          <option>Mining and Extractive</option>
                          <option>Technology and Innovation</option>
                          <option>Financial and Real Estate</option>
                          <option>Others</option>
                        </select>
                        </div>
                      </div>
                      <!-- Input field for specifying other nature of business -->
                          <div class="col-md-12" id="other_business_nature_container" style="display: none;">
                              <div class="form-group">
                                  <label>Specify Nature of Business</label>
                                  <input class="form-control" placeholder="Enter other nature of business" id="other_business_nature" name="business_nature" />
                              </div>
                          </div>
                    </div>

                    <div id="add_certificate_cutting_container">
                      <div class="col-md-12">
                        <div class="form-group">
                          <label>Material</label>
                          <input class="form-control" placeholder="Enter material name (e.g., coconut)"
                            name="cutting_material" />
                        </div>
                      </div>

                      <div class="col-md-12">
                        <div class="form-group">
                          <label>Quantity</label>
                          <input class="form-control" placeholder="Enter quantity" name="cutting_quantity" />
                        </div>
                      </div>

                      <div class="col-md-12">
                        <div class="form-group">
                          <label>Location</label>
                          <input class="form-control" placeholder="Enter location" name="cutting_location" />
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="modal-footer">
                  <input type="hidden" name="resident_id" value="<?= $resident_details["id"] ?>">
                  <input type="hidden" name="request-certificate" value="1">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  <button type="submit" class="btn btn-primary">Save</button>
                </div>
              </form>
            </div>
          </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="edit-request">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <form method="POST" action="model/certificate-request.php" enctype="multipart/form-data">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">View Request</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                  <input type="hidden" name="edit-request" value="1">

                  <input type="hidden" id="edit-request-id" name="id" required>

                  <div class="col-md-12">
                    <div class="form-group">
                      <label>Certificate</label>
                      <select name="certificate_id" id="edit-request-certificate_id" class="form-control"
                        <?= ifThen(!isUser(), "disabled") ?>>
                        <option selected disabled>Select Certificate</option>
                        <?php foreach ($certificates as $certificate): ?>
                        <option value="<?= $certificate["id"] ?>">
                          <?= $certificate["name"] ?>
                        </option>
                        <?php endforeach; ?>
                      </select>
                    </div>
                  </div>

                  <div class="col-md-12">
                    <div class="form-group">
                      <label>Memo</label>
                      <textarea type="text" class="form-control" placeholder="Enter Complainant Name"
                        id="edit-request-memo" name="memo" required <?= ifThen(!isUser(), "readonly") ?>>
                      </textarea>
                    </div>
                  </div>
            
                  <div id="edit_certificate_business_container" style="display: none;">
                      <div class="col-md-12">
                        <div class="form-group">
                          <label>Business Name</label>
                          <input class="form-control" placeholder="Enter business name" id="edit-request-name" name="business_name" <?= ifThen(!isUser(), "readonly") ?>/>
                        </div>
                      </div>

                      <div class="col-md-12">
                        <div class="form-group">
                          <label>Business Owner 1</label>
                          <input class="form-control" placeholder="Enter business name" id="edit-request-owner-1" name="business_owner_1" <?= ifThen(!isUser(), "readonly") ?>/>
                        </div>
                      </div>

                      <div class="col-md-12">
                        <div class="form-group">
                          <label>Business Owner 2</label>
                          <input class="form-control" placeholder="Enter business name" id="edit-request-owner-2" name="business_owner_2" <?= ifThen(!isUser(), "readonly") ?>/>
                        </div>
                      </div>

                      <div class="col-md-12">
                        <div class="form-group">
                          <label>Nature of Business</label>
                          <input class="form-control" placeholder="Enter business name" id="edit-request-nature" name="business_nature" <?= ifThen(!isUser(), "readonly") ?>/>
                        </div>
                      </div>
                    </div>

                    <div id="edit_certificate_cutting_container" style="display: none;">
                      <div class="col-md-12">
                        <div class="form-group">
                          <label>Material</label>
                          <input class="form-control" placeholder="Enter material name (e.g., coconut)" id="edit-request-material"
                            name="cutting_material" <?= ifThen(!isUser(), "readonly") ?>/>
                        </div>
                      </div>

                      <div class="col-md-12">
                        <div class="form-group">
                          <label>Quantity</label>
                          <input class="form-control" placeholder="Enter quantity" id="edit-request-quantity" name="cutting_quantity" <?= ifThen(!isUser(), "readonly") ?>/>
                        </div>
                      </div>

                      <div class="col-md-12">
                        <div class="form-group">
                          <label for="edit-cutting-location">Location</label>
                          <input class="form-control" placeholder="Enter location" id="edit-request-location" name="cutting_location" <?= ifThen(!isUser(), "readonly") ?>/>
                        </div>
                      </div>
                    </div>

                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

                  <?php if (isUser()): ?>
                  <button type="submit" class="btn btn-primary">Update</button>
                  <?php endif; ?>
                </div>
              </form>
            </div>
          </div>
        </div>

        <!-- Main Footer -->
        <?php include "templates/main-footer.php"; ?>
        <!-- End Main Footer -->

      </div>

    </div>
    <?php include "templates/footer.php"; ?>
    <script src="assets/js/plugin/datatables/datatables.min.js"></script>
    <script>
    $(document).ready(function() {
      var oTable = $('#announcement-table').DataTable({
        "order": []
      });

      const certificatesById = {
        5: $('#add_certificate_business_container'),
        6: $('#add_certificate_cutting_container'),  // Cutting Permit
        2: $('#add_certificate_cutting_container')
      }

      function hideCertificateContainers() {
        for (const key in certificatesById) {
          const element = certificatesById[key];
          element.hide()
        }
      }

      hideCertificateContainers() 

      $('#add_certificate_options').on('change', e => {
        const certificateId = e.target.value

        hideCertificateContainers()

        if (certificatesById[certificateId]) {
          certificatesById[certificateId].show()
        }
      })

      
    });

    

    $(document).ready(function () {
    const certificatesById = {
        5: $('#edit_certificate_business_container'), // Business Clearance
        6: $('#edit_certificate_cutting_container'),  // Cutting Permit
        2: $('#edit_certificate_cutting_container')  // Cutting Permit
    };

    // Hide all certificate containers
    function hideCertificateContainers() {
        for (const key in certificatesById) {
            certificatesById[key].hide();
        }
    }

    // Show the fields for the selected certificate
    function showRelevantFields(certificateId) {
        hideCertificateContainers();
        if (certificatesById[certificateId]) {
            certificatesById[certificateId].show(); // Show the relevant container
        }
    }

    // Listen to changes in the certificate dropdown
    $('#edit-request-certificate_id').on('change', function (e) {
        const selectedCertificateId = e.target.value;
        showRelevantFields(selectedCertificateId);
    });

    // Function to populate fields and show modal
    window.showModal = function (element) {
        const certificateId = $(element).data('value-certificate_id'); // Get certificate ID
        const modalTarget = $(element).data('target');

        // Populate modal fields
        $(`${modalTarget} #edit-request-id`).val($(element).data('value-id'));
        $(`${modalTarget} #edit-request-memo`).val($(element).data('value-memo'));
        $(`${modalTarget} #edit-request-name`).val($(element).data('value-name'));
        $(`${modalTarget} #edit-request-owner-1`).val($(element).data('value-business_owner_1'));
        $(`${modalTarget} #edit-request-owner-2`).val($(element).data('value-business_owner_2'));
        $(`${modalTarget} #edit-request-nature`).val($(element).data('value-business_nature'));
        $(`${modalTarget} #edit-request-material`).val($(element).data('value-cutting_material'));
        $(`${modalTarget} #edit-request-quantity`).val($(element).data('value-cutting_quantity'));
        $(`${modalTarget} #edit-request-location`).val($(element).data('value-cutting_location'));
        $(`${modalTarget} #edit-request-certificate_id`).val(certificateId).change(); // Set and trigger change event

        // Explicitly show fields for the current certificate
        showRelevantFields(certificateId);

        // Show the modal
        $(modalTarget).modal('show');
    };
});


  //funtion for nature of business

  $(document).ready(function () {
    // Hide the other business nature input field initially
    $('#other_business_nature_container').hide();

    // Listen for changes on the business nature dropdown
    $('#business_nature_select').on('change', function () {
        if ($(this).val() === 'Others') {
            $('#other_business_nature_container').show(); // Show the input field
        } else {
            $('#other_business_nature_container').hide(); // Hide the input field
            $('#other_business_nature').val(''); // Clear the input field
        }
    });
});

    </script>
  </body>

</html>
