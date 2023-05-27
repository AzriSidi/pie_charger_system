<!-- Content Wrapper -->
<div id="content-wrapper" class="d-flex flex-column">
  <!-- Main Content -->
  <div id="content">
    <section class="vh-92">
      <div id="loginAlert" class="alert alert-danger alert-dismissible fade show" role="alert">
        <p id="mgsAlert"></p>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="" id="form" method="post">
        <div class="container py-5 h-100">
          <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col-12 col-md-8 col-lg-6 col-xl-5">
              <div class="card shadow-2-strong" style="border-radius: 1rem;">
                <div class="card-body p-5 text-center">
                  <h2 class="fw-bold mb-2 text-uppercase">Login</h2>
                  <p class="text-black-50 mb-5">Please enter your batch no and password</p>
                  <div class="form-outline mb-4">
                    <input type="text" id="batchNo" class="form-control form-control-lg" autofocus/>
                    <label class="form-label" for="batchNo-2">Batch No</label>
                  </div>
                  <div class="form-outline mb-4">
                    <input type="password" id="password" class="form-control form-control-lg" />
                    <label class="form-label" for="password">Password</label>
                  </div>
                  <button id="subBtn" class="btn btn-primary btn-lg btn-block" type="button">Login</button>
                  <hr class="my-4">
                  <div>
                    <p class="text-center text-muted mt-4 mb-0">Don't have an account? 
                      <a href="register" class="fw-bold text-body">Register here</a>
                    </p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </form>
    </section>
<script>
  $(function(){    
    $('#loginAlert').hide();
    $('#subBtn').click(function(e){
      e.preventDefault();
      var batchNo = $("#batchNo").val();
      var password = $("#password").val();
      // submit data via ajax
      $.ajax({
        url: '<?php echo base_url("/pack/loginAuth");?>',
        type: "POST",
        data:{
          batchNo : batchNo,
          password : password,
        },
        success: function(response){
          var json = JSON.parse(response);
          var mgs = json.response;
          if(mgs == "ok"){
            window.location.href = '<?php echo base_url("/pack");?>'
          }else{
            $('#mgsAlert').html(mgs);
            $("#form").trigger('reset');
            $('#batchNo').focus();
            $('#loginAlert').show();
            setTimeout(function(){
              $('#loginAlert').hide();
            },5000);
          }          
        }
      });
    });
  });
</script>
<style>
  .vh-92 {
    height: 92vh;
  }
</style>