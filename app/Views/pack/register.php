<!-- Content Wrapper -->
<div id="content-wrapper" class="d-flex flex-column">
  <!-- Main Content -->
  <div id="content">
    <section class="vh-92">
      <div class="mask d-flex align-items-center h-100 gradient-custom-3">
        <div class="container h-100">
          <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col-12 col-md-9 col-lg-7 col-xl-6">
              <div class="card" style="border-radius: 15px;">
                <div class="card-body p-5">
                  <h2 class="fw-bold mb-4 text-uppercase text-center">Register</h2>
                  <form id="form">
                    <span id="validName" style="color:red;"></span>
                    <div class="form-outline mb-4">
                      <input type="text" id="name" name="name" class="form-control form-control-lg" autofocus/>
                      <label class="form-label" for="name">Name</label>
                    </div>
                    <span id="validBatch" style="color:red;"></span>                                    
                    <div class="form-outline mb-4">
                      <input type="text" id="batchNo" class="form-control form-control-lg" />
                      <label class="form-label" for="batchNo">Batch No</label>
                    </div>
                    <span id="validRole" style="color:red;"></span>
                    <div class="form-floating mb-4">
                      <select class="form-select" id="role">
                        <option value="">--Select Role--</option>
                        <option value="Manager">Manager</option>
                        <option value="Assist Manager">Assist Manager</option>
                        <option value="Supervisor">Supervisor</option>
                        <option value="Engineer">Engineer</option>
                        <option value="Assist Engineer">Assist Engineer</option>
                        <option value="Technician">Technician</option>
                      </select>
                      <label class="form-label select-label">Role</label>
                    </div>
                    <span id="validPass" style="color:red;"></span>
                    <div class="form-outline mb-4">
                      <input type="password" id="password" class="form-control form-control-lg" />
                      <label class="form-label" for="form3Example4cg">Password</label>
                    </div>
                    <span id="validConfirmPass" style="color:red;"></span>
                    <div class="form-outline mb-4">
                      <input type="password" id="confirmPass" class="form-control form-control-lg" />
                      <label class="form-label" for="form3Example4cdg">Confirm Password</label>
                    </div>
                    <div class="d-flex justify-content-center">
                      <button type="button" id="subBtn"
                        class="btn btn-success btn-block btn-lg gradient-custom-4 text-body">Register</button>
                    </div>
                    <hr class="my-4">
                    <p class="text-center text-muted mt-4 mb-0">Already have an account? 
                      <a href="login" class="fw-bold text-body">Login here</a></p>
                  </form>                 
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- Modal -->
    <div class="modal fade" id="mi-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Saved New User</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            Procced to login or insert new user by click the Close button.
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" id="modal-btn-no">Close</button>
            <button type="button" class="btn btn-primary" id="modal-btn-yes">Login</button>
          </div>
        </div>
      </div>
    </div>
<script>
  $(function(){
    $('#regisAlert').hide();
    $('#subBtn').click(function(e){
      e.preventDefault();
      var name = $("#name").val();
      var batchNo = $("#batchNo").val();
      var role = $("#role").val();
      var password = $("#password").val();
      var confirmPass = $("#confirmPass").val();
      // submit data via ajax
      $.ajax({
        url: '<?php echo base_url("/pack/store");?>',
        type: "POST",
        data:{
          name : name,
          batchNo : batchNo,
          role : role,
          password : password,
          confirmPassword : confirmPass,
        },
        success: function(response){
          var json = JSON.parse(response);
          var res = json.response;
          if(res == "ok"){
            $("#mi-modal").modal('show');
          }else{
            if(res.name !== undefined){
              $('#validName').text(res.name)
            }if(res.batchNo !== undefined){
              $('#validBatch').text(res.batchNo)
            }if(res.role !== undefined){
              $('#validRole').text(res.role)
            }if(res.password !== undefined){
              $('#validPass').text(res.password)
            }if(res.confirmPassword !== undefined){
              $('#validConfirmPass').text(res.confirmPassword)
            }
            $("#form").trigger('reset');
            $("#name").focus();
          }          
        }
      });
    });

    var modalConfirm = function(callback){
      $("#modal-btn-yes").on("click", function(){
        callback(true);
        $("#mi-modal").modal('hide');
      });
        
      $("#modal-btn-no").on("click", function(){
        callback(false);
        $("#mi-modal").modal('hide');
      });
    };
    modalConfirm(function(confirm){
      if(confirm){
        window.location.href = '<?php echo base_url("/pack/login");?>'
      }else{
        $("#form").trigger('reset');
        $("#name").focus();
      }
    });
  });
</script>
<style>
  .vh-92 {
    height: 92vh;
  }
  .gradient-custom-3 {
    /* fallback for old browsers */
    background: #84fab0;
    /* Chrome 10-25, Safari 5.1-6 */
    background: -webkit-linear-gradient(to right, rgba(132, 250, 176, 0.5), rgba(143, 211, 244, 0.5));
    /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
    background: linear-gradient(to right, rgba(132, 250, 176, 0.5), rgba(143, 211, 244, 0.5))
  }
  .gradient-custom-4 {
    /* fallback for old browsers */
    background: #84fab0;
    /* Chrome 10-25, Safari 5.1-6 */
    background: -webkit-linear-gradient(to right, rgba(132, 250, 176, 1), rgba(143, 211, 244, 1));
    /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
    background: linear-gradient(to right, rgba(132, 250, 176, 1), rgba(143, 211, 244, 1))
  }
</style>