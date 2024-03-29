  <nav class="mb-4 static-top shadow"></nav>
    <div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
      <h1 class="h3 mb-2 text-gray-800">Edit Model</h1>
    </div>
      <div class="card">
        <div class="card-body mb-4">
          <!-- Stepper -->
          <div class="steps-form">
            <div class="steps-row setup-panel">
              <div class="steps-step">
                <button href="#step-1" type="button" class="btn btn-success btn-circle">1</button>
                <p>Edit Info Model</p>
              </div>
              <div class="steps-step">
              <button href="#step-2" type="button" class="btn btn-primary btn-circle" disabled>2</button>
                <p>Edit Item Scan</p>
              </div>
              <div class="steps-step">
                <button href="#step-3" type="button" class="btn btn-primary btn-circle" disabled>3</button>
                <p>Finish</p>
              </div>
            </div>
          </div>

          <form role="form" action="" method="post">
            <!-- First Step -->
            <div class="row setup-content" id="step-1">
              <div class="form-group md-form">
                <h3 class="font-weight-bold pl-0 my-4"><strong>Edit Info Model</strong></h3>
                <div class="form-group md-form">
                  <label data-error="wrong" data-success="right">Model</label>
                  <select class="form-control form-control-sm" id="model">
                    <option value="">--Select Model--</option>
                    <?php
                      for($i=0;$i<count($model);$i++){                                                                                         
                    ?>
                    <option value="<?=$model[$i]?>"><?=$model[$i]?></option>
                    <?php
                      }
                    ?>
                  </select>                                                  
                </div>
                <div class="form-group md-form mt-3">
                  <label data-error="wrong" data-success="right">Cust No.</label>
                  <input id="custNo" type="text" required="required" class="form-control validate">
                </div>
                <div class="form-group md-form mt-3">
                  <label data-error="wrong" data-success="right">Quantity Per Box</label>
                  <input type="number" step="1" id="quantityBox" required="required" rows="2" class="form-control validate">
                </div>
                <div class="form-group md-form mt-3">
                  <label data-error="wrong" required="required" data-success="right">Line No.</label>
                  <select class="form-control validate" id="lineNo">                  
                    <option value="">--Select Line No--</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                  </select>
                </div>
                <div class="form-group md-form mt-3">
                  <label data-error="wrong" data-success="right">Single Unit Weight (Kg)</label>
                  <input id="singleUnit" type="text" required="required" rows="2" class="form-control">
                </div>
                <div class="form-group md-form mt-3">
                  <label data-error="wrong" data-success="right">Tolerance (Kg)</label>
                  <input id="tolerance" type="text" rows="2" class="form-control">
                </div>
                <div class="form-group md-form mt-3">
                  <label data-error="wrong" data-success="right">Check SN</label>                
                  <div class="form-check">
                    <label data-error="wrong" data-success="right">Yes</label>
                    <input class="form-check-input yes" type="radio" name="radioCheck" id="checkSN" value="1">                  
                  </div>
                  <div class="form-check">
                    <label data-error="wrong" data-success="right">No</label>
                    <input class="form-check-input no" type="radio" name="radioCheck" id="checkSN" value="0">
                  </div>
                </div>
                <button class="btn btn-dark btn-rounded nextBtn float-right" type="button">Next</button>
              </div>
            </div>

            <!-- Second Step -->
            <div class="row setup-content" id="step-2">
              <div class="col-md-12">
                <h3 class="font-weight-bold pl-0 my-4"><strong>Edit Item Scan</strong></h3>
                <div class="form-group row">
                  <label class="col-sm-1 col-form-label">Model</label>
                  <div class="col-sm-3">
                    <input id="modelItem" type="text" class="form-control" style="cursor: not-allowed;" disabled>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-sm-1 col-form-label">Total Item Scan</label>
                  <div class="col-sm-3">
                    <select class="form-control validate" id="totalScan">
                      <option value="">--Select Total Item Scan--</option>
                      <option value="1">1</option>
                      <option value="2">2</option>
                      <option value="3">3</option>
                      <option value="4">4</option>
                      <option value="5">5</option>
                      <option value="6">6</option>
                      <option value="7">7</option>
                    </select>
                  </div>
                </div>
                <div class="form-group row">
                  <div id="scanDetail"></div>
                  <div id="scanDetail2"></div>
                </div>
                <button class="btn btn-dark btn-rounded prevBtn float-left" type="button">Previous</button>
                <button id="subBtn" class="btn btn-info btn-rounded float-right" type="button">Submit</button>
              </div>
            </div>

            <!-- Third Step -->
            <div class="row setup-content" id="step-3">
              <div class="col-md-12">
                <h3 class="font-weight-bold pl-0 my-4"><strong>Finish</strong></h3>
                <div class="col text-center">
                  <button id="editInfo" class="btn btn-info btn-rounded" type="button">Edit Info Again</button>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    <br>
</div>
<!-- End of Content Wrapper -->  

<script>
  $(function(){
    $("#model").change(function(){
      $("#scanDetail").html("");
      var model = this.value;
      let itemNo;
      let itemDetail = [];
      let itemScan = [];

      $.ajax({
        url: '<?php echo base_url("/pack/searchModel");?>',
        type: "POST",
        async : false,
        data:{
          model : model,
        },
        success: function(response){
          var json = JSON.parse(response);          
          var res = json.response;             
          var custNo = res.cust_no;
          var qpb = res.quantity_per_box;
          var lineNo = res.line_no;
          var suw = res.single_unit_weight;
          var tol = res.tolerance;
          var checkSN = res.checkSN;
          var checked = $('#checkSN').val();
          if(checkSN == 1){
            $('.yes').prop('checked', true);
          }else{
            $('.no').prop('checked', true);
          }       

          itemNo = res.item_no;
          itemDetail = res.item_detail;
          itemScan = res.item_scan;
          
          $('#custNo').val(custNo);
          $('#quantityBox').val(qpb);
          $('#lineNo').val(lineNo);
          $('#singleUnit').val(suw);
          $('#tolerance').val(tol);
          $('#totalScan').val(itemNo);          
        }
      });

      var resultID = [];
      var str = itemDetail.toString();
      str.split(',').forEach((x, y) => { 
        resultID[y] = x;
      });

      var resultIS = [];
      var str2 = itemScan.toString();
      str2.split(',').forEach((x, y) => { 
        resultIS[y] = x;
      });

      for(var i=0;i<itemNo;i++){              
        $("#scanDetail").append("<div class='form-group row'>"+
        "<label class='col-sm-1 col-form-label'>Item Detail</label>"+
        "<div class='col-sm-3'>"+
        "<select class='itemSelect form-control form-control-sm' id='itemDetail"+i+"'>"+
        "<option value=''>--Select Item Detail--</option>"+
        <?php
          for($j=0;$j<count($itemDetail);$j++){                                             
        ?>
        "<option value='<?=$itemDetail[$j]?>'><?=$itemDetail[$j]?></option>"+
        <?php
          }
        ?>
        "</select>"+
        "<input type='text' class='itemText form-control form-control-sm' id='itemDetailText"+i+"'"+
        "placeholder='Enter Item Detail' autofocus oninput='this.value = this.value.toUpperCase()'>"+        
        "</div>"+
        "<div class='col-sm-1'>"+
        "<button id='"+i+"' class='btnClass btn btn-secondary btn-sm' type='button'>+</button>"+  
        "</div>"+
        "<div class='col-sm-1'></div>"+
        "<label class='col-sm-1 col-form-label'>Item Scan</label>"+
        "<div class='col-sm-3'>"+
        "<input type='text' class='form-control form-control-sm' id='itemScan"+i+"' placeholder='Enter Item Scan'>"+
        "</div>"+
        "</div>"
        );
        $('.itemText').hide();
        $('#itemDetail'+i).val(resultID[i]);
        $('#itemScan'+i).val(resultIS[i]);
      }

      $('body').on('click','.btnClass',(function(){
        var btnId = $(this).attr('id');
        var getItemSelect = "";
        var getItemText = "";
        var state = $(this).data('state');

        $('.itemSelect').each(function(){
          var id = $(this).attr('id');   
          if(id.includes(btnId)){
            getItemSelect = id;
          }
        });

        $('.itemText').each(function(){
          var id = $(this).attr('id');
          if(id.includes(btnId)){
            getItemText = id;
          }
        });
          
        switch(state){
          case 1 :
            $(this).text("-");
            $(this).data('state', 2);
            $('#' + getItemText).val('');
            $('#' + getItemText).focus();
            $('#' + getItemSelect).hide();
            $('#' + getItemText).show();
          break;
          case 2 :
            $(this).text("+");
            $(this).data('state', 1);
            $('#' + getItemSelect).show();
            $('#' + getItemText).hide();
          break;
          case undefined :
            $(this).text("-");
            $(this).data('state', 2);
            $('#' + getItemText).val('');
            $('#' + getItemText).focus();
            $('#' + getItemSelect).hide();
            $('#' + getItemText).show();
          break;
        }
      }));

      $("#totalScan").change(function(){        
        $("#scanDetail2").html("");
        let totalScan = this.value;
        for(var i=0;i<totalScan;i++){
          $("#scanDetail2").append("<div class='form-group row'>"+
            "<label class='col-sm-1 col-form-label'>Item Detail</label>"+
            "<div class='col-sm-3'>"+
            "<select class='itemSelect form-control form-control-sm' id='itemDetail"+i+"'>"+
            "<option value=''>--Select Item Detail--</option>"+
            <?php
              for($j=0;$j<count($itemDetail);$j++){                                             
            ?>
            "<option value='<?=$itemDetail[$j]?>'><?=$itemDetail[$j]?></option>"+
            <?php
              }
            ?>
            "</select>"+
            "<input type='text' class='itemText form-control form-control-sm' id='itemDetailText"+i+"'"+
            "placeholder='Enter Item Detail' autofocus oninput='this.value = this.value.toUpperCase()'>"+        
            "</div>"+
            "<div class='col-sm-1'>"+
            "<button id='"+i+"' class='btnClass btn btn-secondary btn-sm' type='button'>+</button>"+  
            "</div>"+
            "<div class='col-sm-1'></div>"+
            "<label class='col-sm-1 col-form-label'>Item Scan</label>"+
            "<div class='col-sm-3'>"+
            "<input type='text' class='itemScan form-control form-control-sm' id='itemScan"+i+"' placeholder='Enter Item Scan'>"+
            "</div>"+
            "</div>"
            );
          $('.itemText').hide();
        }    
      });
    });            
  });
    
    var navListItems = $('div.setup-panel div button'),
      allWells = $('.setup-content'),
      allNextBtn = $('.nextBtn'),
      allPrevBtn = $('.prevBtn');
      allWells.hide();

    navListItems.click(function(e){
      e.preventDefault();
      var $target = $($(this).attr('href')),
      $item = $(this);
      if(!$item.hasClass('disabled')){
        navListItems.removeClass('btn-success').addClass('btn-primary');
        $item.addClass('btn-success');
        allWells.hide();
        $target.show();
        $target.find('input:eq(0)').focus();
      }
    });

    allPrevBtn.click(function(){
      var curStep = $(this).closest(".setup-content"),
      curStepBtn = curStep.attr("id"),
      prevStepSteps = $('div.setup-panel div button[href="#' + curStepBtn + '"]').parent().prev().children("button");
      prevStepSteps.removeAttr('disabled').trigger('click');
    });

    allNextBtn.click(function(){
      var curStep = $(this).closest(".setup-content"),
      curStepBtn = curStep.attr("id");
      nextBtnFunc(curStep,curStepBtn);
      var model = $('#model').val();
      $('#modelItem').val(model);
      $("#subBtn").removeClass("nextBtn");
    });

    function nextBtnFunc(curStep,curStepBtn){
      var nextStepWizard = $('div.setup-panel div button[href="#' + curStepBtn + '"]').parent().next().children("button"),
      curInputs = curStep.find("input[type='text'],select");
      isValid = true;      
      $(".form-group").removeClass("has-error");
      for(var i=0; i< curInputs.length; i++){
        if(!curInputs[i].validity.valid){
          isValid = false;
          $(curInputs[i]).closest(".form-group").addClass("has-error");
        }
      }   
      if(isValid){
        nextStepWizard.removeAttr('disabled').trigger('click');
      }
    }
    $('div.setup-panel div button.btn-success').trigger('click');

    // submit button
    $('#subBtn').click(function(e){
      e.preventDefault();
      var curStep = $(this).closest(".setup-content"),
      curStepBtn = curStep.attr("id");
      // info
      var custNo = $("#custNo").val();
      var model = $("#model").val();
      var quantityBox = $("#quantityBox").val();
      var lineNo = $("#lineNo").val();
      var singleUnit = $("#singleUnit").val();
      var tolerance = $("#tolerance").val();
      var checkSN = $('input[name=radioCheck]:checked').val();
      console.log("checkSN: "+checkSN)
      // item
      var totalScan = $("#totalScan").val();
      var itemDetail = [];
      var itemDetailText = [];
      var itemScan = [];
      for(let i=0; i<totalScan; i++){
        itemDetail.push($("#itemDetail"+i).val());
        itemDetailText.push($("#itemDetailText"+i).val());
        itemScan.push($("#itemScan"+i).val());
      }
      // submit data via ajax
      $.ajax({
        url: '<?php echo base_url("/pack/saveEditModel");?>',
        type: "POST",
        data:{
          custNo : custNo,
          model : model,
          quantityBox : quantityBox,          
          lineNo : lineNo,
          singleUnit : singleUnit,
          tolerance : tolerance,
          checkSN : checkSN,
          totalScan : totalScan,
          itemDetail : itemDetail,
          itemDetailText : itemDetailText,
          itemScan : itemScan,
        },
        success: function(response){
          var json = JSON.parse(response);
          var mgs = json.response;
          if(mgs == "success"){                        
            nextBtnFunc(curStep,curStepBtn);
          }else if(mgs == "fail"){            
            alert("Cust No and Model already exist.");
          }
        }
      });
  });

  //Edit Again Button
  $('#editInfo').click(function(e){
    e.preventDefault();
    $('body').load('<?php echo base_url("/pack/editModel");?>');
  });
</script>
<style>
  .steps-form {
    display: table;
    width: 100%;
    position: relative;
  }
  .steps-form .steps-row {
    display: table-row;
  }
  .steps-form .steps-row:before {
    top: 14px;
    bottom: 0;
    position: absolute;
    content: " ";
    height: 1px;
    width: 100%;
    background-color: #ccc;
  }
  .steps-form .steps-row .steps-step {
    display: table-cell;
    text-align: center;
    position: relative;
  }
  .steps-form .steps-row .steps-step p {
    margin-top: 0.5rem;
  }
  .steps-form .steps-row .steps-step button[disabled] {
    opacity: 1 !important;
    filter: alpha(opacity=100) !important;
  }
  .steps-form .steps-row .steps-step .btn-circle {
    width: 30px;
    height: 30px;
    text-align: center;
    padding: 6px 0;
    font-size: 12px;
    line-height: 1.428571429;
    border-radius: 15px;
    margin-top: 0;
  }
</style>