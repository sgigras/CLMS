<div class="row">
    <div class="col-6">
        <label>Registation Status</label>
        <input type="text" class="form-control" value="<?= $user_details_array[0]['registration_status'] ?>" readonly>
    </div>
    <div class="col-6">
        <label>Irla No./Regiment No.</label>
        <input type="text" id="irlaid" class="form-control" value="<?= $user_details_array[0]['irla'] ?>" readonly>
    </div>
</div>

<div class="row">
    <div class="col-6">
        <label>Name</label>
        <input type="text" class="form-control" value="<?= $user_details_array[0]['name'] ?>" readonly>
    </div>
    <div class="col-6">
        <label>Date Of Birth</label>
        <input type="date" id="dob" class="form-control" value="<?= $user_details_array[0]['date_of_birth'] ?>" readonly>
    </div>
</div>


<div class="row">
    <div class="col-4">
        <label>Rank</label>
        <input type="text" class="form-control" value="<?= $user_details_array[0]['rank'] ?>" readonly>
    </div>
    <div class="col-4">
        <label>Present Appointment</label>
        <input type="text" class="form-control" value="<?= $user_details_array[0]['present_appoitment'] ?>" readonly>
    </div>
    <div class="col-4">
        <label>Status</label>
        <input type="text" class="form-control" value="<?= $user_details_array[0]['status'] ?>" readonly>
    </div>
</div>

<div class="row">
    <div class="col-4">
        <label>Location</label>
        <input type="text" class="form-control" value="<?= $user_details_array[0]['location_name'] ?>" readonly>
    </div>
    <div class="col-4">
        <label>District</label>
        <input type="text" class="form-control" value="<?= $user_details_array[0]['district_name'] ?>" readonly>
    </div>
    <div class="col-4">
        <label>State</label>
        <input type="text" class="form-control" value="<?= $user_details_array[0]['state_name'] ?>" readonly>
    </div>
</div>

<div class="row">
    <div class="col-6">
        <label>Mobile Number</label>
        <input type="tel" id="mobile" maxlength="10" onkeypress="return checkValidInputKeyPress(numeric_regex_pattern);" class="form-control" value="<?= $user_details_array[0]['mobile_no'] ?>">
        <span id="mobile_err" style="color: red;"></span>
    </div>
    <div class="col-6">
        <label>Email Address</label>
        <input type="email" id="email" class="form-control" value="<?= $user_details_array[0]['email_id'] ?>">
        <span id="email_err" style="color: red;"></span>
    </div>
</div>
<br>
<div class="form-group">
    <div class="col-md-12">
        <button id="get_otp" onclick="getOtp()" class="btn btn-primary pull-right">Get OTP</button>&nbsp;&nbsp;
        <button id="remove_mac_binding" onclick="removeMacBinding()" class="btn btn-primary pull-right">Remove Mac Binding</button>&nbsp;&nbsp;
        <button id="edit" onclick="edituser()" class="btn btn-primary pull-right">Update</button>
        <!-- <input type="hidden" name="submit" value="submit" /> -->
    </div>
</div>