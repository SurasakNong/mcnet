
function show_group_table(){ //========================== แสดงค้นหา และปุ่มเพิ่ม หมวดรายการ
    var html = `
  <div class="container animate__animated animate__fadeIn">
    <div class="row">                
        <div class="col-lg-6 mx-auto mt-3">
            <form id="fmsearch_group">
                <div class="input-group mb-2">
                    <input type="text" id="search_group" name="search_group" class="form-control" placeholder="คำค้นหา.." aria-label="Search" aria-describedby="button-search">
                    <button class="btn btn-success" type="button" id="bt_search_group" name="bt_search_group" title="ค้นหา"><i class="fas fa-search"></i></button>
                    <button class="btn btn-primary ms-2" id="bt_add_group" name="bt_add_group" style="width: 42px;" type="button" title="เพิ่มข้อมูล"><i class="fas fa-plus"></i></button>
                    <button class="btn btn-warning ms-2" id="bt_back" name="bt_back" type="button" title="กลับ"><i class="fas fa-reply"></i></button>
                </div>
            </form>
        </div>
    </div>   
    <div class="row">  
        <div class="col-lg-6 mx-auto" id="add_group"></div>
    </div>   
    <div class="row">  
        <div class="col-lg-6 mx-auto" id="edit_group"></div>
    </div>   
    <div class="row">  
        <div class="col-lg-6 mx-auto" id="table_group"></div>
    </div>
  </div>
    `;
    $("#content").html(html);
    showgrouptable(rowperpage,page_sel); //<<<<<< แสดงตาราง
}

function showgrouptable(per,p){ //======================== แสดงตาราง
  var ss = document.getElementById('search_group').value;            
  var jwt = getCookie("jwt");
  var i = ((p-1)*per);
  $.ajax({
    type: "POST",
    url: "api/data_group.php",
    data: {search:ss,perpage:per,page:p,jwt:jwt},
    success: function(result){
      var tt=`
      <table class="list-table table animate__animated animate__fadeIn" id="grouptable" >
        <thead>
          <tr>
            <th class="text-center" style="width:5%">ลำดับ</th> 
            <th class="text-left">กลุ่ม</th>
            <th class="text-left">หน่วยงาน</th>
            <th class="text-center">เครื่อง</th>
            <th class="text-center">RPM</th>
            <th class="text-center">แก้ไข&nbsp;&nbsp;&nbsp;ลบ</th>                
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
      <div class="mb-3" id="pagination">
      `;              
      $("#table_group").html(tt);      
      pagination_show(p,result.page_all,per,'showgrouptable'); //<<<<<<<< แสดงตัวจัดการหน้าข้อมูล Pagination >>const.js
      $.each(result.data, function (key, entry) {
        i++;
        listgroupTable(
            entry.group_id,
            entry.group_name,
            entry.bd_id,
            entry.bd_name,
            entry.group_mc,
            entry.group_rpm,
            i); //<<<<< แสดงรายการทั้งหมด             
      });             
    },
    error: function(xhr, resp, text) {
        if (xhr.responseJSON.message == "Access denied.") {
          showLoginPage();
          Signed("warning", "โปรดเข้าสู่ระบบก่อน !");            
        }else{
          showLoginPage();
          Signed("warning", "โปรดเข้าสู่ระบบก่อน !");
        }
    }
  });
}

function listgroupTable(id,gn,bid,dp,gmc,grpm,i){  //=========================== ฟังก์ชั่นเพิ่ม Row ตารางประเเภท
  var tableName = document.getElementById('grouptable');
    var prev = tableName.rows.length;           
    var row = tableName.insertRow(prev);
    row.id = "row" + id;
    row.style.verticalAlign = "top";
    var txtDel = `<i class="fas fa-trash-alt" style="cursor:not-allowed; color:#939393;"></i>`;
    if(u_type == "2"){
      txtDel = `<i class="fas fa-trash-alt" onclick="delete_group_Row(` + id + `)" style="cursor:pointer; color:#d9534f;"></i>`;
    }
    var col1 = row.insertCell(0);
    var col2 = row.insertCell(1);
    var col3 = row.insertCell(2);
    var col4 = row.insertCell(3);
    var col5 = row.insertCell(4);
    var collast = row.insertCell(5);
    col1.innerHTML = `<div id="no" class="text-center">`+i+`</div>`;
    col2.innerHTML = `<div id="group_name` + id + `" class="text-left">`+gn+`</div>`;
    col3.innerHTML = `<div id="depart` + id + `" class="text-left">`+dp+`</div>`;
    col4.innerHTML = `<div id="group_mc` + id + `" class="text-center">`+gmc+`</div>`;
    col5.innerHTML = `<div id="group_rpm` + id + `" class="text-center">`+parseFloat(grpm).toFixed(2)+`</div>`;
    collast.innerHTML = `
    <input type="hidden" id="group_id` + id + `" name="group_id` + id + `" value="` + id + `" />
    <input type="hidden" id="bd_id` + id + `" name="bd_id` + id + `" value="` + bid + `" />

    <i class="fas fa-edit me-3" onclick="edit_group_Row(` + id + `)" style="cursor:pointer; color:#5cb85c;"></i>
    `+txtDel; 
    collast.style = "text-align: center;";
}

function delete_group_Row(id){ //================================ ลบข้อมูลในตาราง
  const swalWithBootstrapButtons = Swal.mixin({
      customClass: {
        confirmButton: 'btn btn-primary me-3',
        cancelButton: 'btn btn-danger ms-3'
      },
      buttonsStyling: false
  })
  swalWithBootstrapButtons.fire({
      title: 'โปรดยืนยัน',
      text: "ต้องการลบข้อมูลหรือไม่?",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: ' ใช่ ',
      cancelButtonText: ' ไม่ ',
      reverseButtons: false
  }).then((result) => {
      if (result.isConfirmed) {
        var jwt = getCookie("jwt");
        var obj = new Object();
        obj.id = id;
        obj.jwt = jwt;
        obj.acc = "del";
        var data = JSON.stringify(obj);
        $.ajax({
            url: "api/group_acc.php",
            type: "POST",
            contentType: "application/json",
            data: data,
            success: function(res) {
              swalWithBootstrapButtons.fire(
                  'ข้อมูลถูกลบ!',
                  'ข้อมูลของคุณได้ถูกลบออกจากระบบแล้ว!',
                  'success'
              );  
              showgrouptable(rowperpage,page_sel);
            },
            error: function(xhr, resp, text) {
                if (xhr.responseJSON.message == "Unable to delete Group.") {
                    Signed("error", "ลบข้อมูลไม่สำเร็จ !="+xhr.responseJSON.code);
                } else if (xhr.responseJSON.message == "Unable to access Group.") {
                    Signed("error", "ไม่สามารถดำเนินการลบข้อมูลได้ โปรดลองใหม่!");
                }else{
                  showLoginPage();
                  Signed("warning", "ปฏิเสธการเข้าใช้ โปรดเข้าสู่ระบบก่อน!");
                }
            },
        });
        
          
      } else if ( result.dismiss === Swal.DismissReason.cancel ){
          swalWithBootstrapButtons.fire(
              'ยกเลิก',
              'ข้อมูลของคุณยังไม่ถูกลบ :)',
              'error'
          )
      }
  })
}

function edit_group_Row(id){ //============================== แก้ไขข้อมูลในตาราง    
    var bd_id =    document.getElementById('bd_id'+id).value;
    var html = `          
    <div class="edit_group animate__animated animate__fadeIn mt-3 mb-4">
      <div style="text-align: center;">
        <i class="far fa-edit fa-3x"></i>&nbsp;&nbsp;&nbsp;<a style="font-size: 2rem">แก้ไขข้อมูล</a>   
      </div>  
      <form class="myForm mt-2" id="edit_group_form">
        <input type="hidden" name="group_id" value="`+document.getElementById('group_id'+id).value+`">
        <div class="row mb-2">    
          <div class="col-md-6 mb-2">                     
              <div class="form-group">
                <label for="group_name">ชื่อกลุ่ม :</label>
                <input type="text" class="form-control" name="group_name" id="group_name" maxlength="100" required value="` +
                document.getElementById('group_name'+id).innerText +
                `">
              </div>
          </div>    
          <div class="col-md-6">
            <div class="form-group">
                <label for="bd_id">หน่วยงาน :</label>
                <select class="form-select" name="bd_id" id="bd_id" required></select>          
            </div>     
          </div>                  
        </div>

        <div class="row mb-2">    
          <div class="col-md-6 mb-2">                     
              <div class="form-group">
                <label for="group_mc">เครื่อง :</label>
                <input type="number" class="form-control" name="group_mc" id="group_mc" step="1" required value="` +
                document.getElementById('group_mc'+id).innerText +
                `">
              </div>
          </div>    
          <div class="col-md-6">
            <div class="form-group">
                <label for="group_rpm">รอบ/นาที :</label>
                <input type="number" class="form-control" name="group_rpm" id="group_rpm" step="0.01" required value="` +
                parseFloat(document.getElementById('group_rpm'+id).innerText).toFixed(2) +
                `">
            </div>       
          </div>                  
        </div>

        <div class="row mt-3 justify-content-center" >
            <button type="submit" class="btn btn-primary me-3" style="width :80px;">บันทึก</button>
            <button id="bt_cancel_editgroup" type="button" class="btn btn-danger ms-3" style="width :80px;">ยกเลิก</button>
        </div>
      </form>
    </div>
      `;
      $("#edit_group").html(html); 

      $.ajax({ 
        type: "POST",
        url: "api/getDropdown.php",
        data: {id:'',fn:'bd'},
        success: function(res){
          let dropdown = $('#bd_id');
          dropdown.empty();
          $.each(res, function (key, entry) {
            dropdown.append($('<option></option>').attr('value', entry.bd_id).text(entry.bd_name));
          })
          $("#bd_id option[value='"+bd_id+"']").attr("selected","selected");                             
        }
      });
    
    $("#add_group").html("");  
    $("#table_group").html("");              

}

//=========================== Even เกี่ยวกับ รายการ ======================================
$(document).on('click',"#bt_search_group",function () {  //ค้นหารายการ
  $("#edit_group").html("");
  $("#add_group").html("");
  $("#bt_add_group").show();
  showgrouptable(rowperpage,'1');          
});

$(document).on("click", "#bt_add_group", function() { // แสดงหน้าบันทึกเพิ่มข้อมูล  
  var html = `          
        <div class="add_group animate__animated animate__fadeIn mt-3 mb-4">
          <div style="text-align: center;">
            <i class="fab fa-buffer fa-3x"></i>&nbsp;&nbsp;&nbsp;<a style="font-size: 2rem">เพิ่มข้อมูล</a>   
          </div> 
          <form class="myForm mt-2" id="add_group_form">
            <div class="row mb-2">    
              <div class="col-md-6 mb-2">                     
                  <div class="form-group">
                    <label for="group_name">ชื่อกลุ่ม :</label>
                    <input type="text" class="form-control" name="group_name" id="group_name" maxlength="100" required>
                  </div>
              </div>    
              <div class="col-md-6">
                <div class="form-group">
                    <label for="bd_id">หน่วยงาน :</label>
                    <select class="form-select" name="bd_id" id="bd_id" required></select>          
                </div>     
              </div>                  
            </div>

            <div class="row mb-2">    
              <div class="col-md-6 mb-2">                     
                  <div class="form-group">
                    <label for="group_mc">เครื่อง :</label>
                    <input type="number" class="form-control" name="group_mc" id="group_mc" step="1" required>
                  </div>
              </div>    
              <div class="col-md-6">
                <div class="form-group">
                    <label for="group_rpm">รอบ/นาที :</label>
                    <input type="number" class="form-control" name="group_rpm" id="group_rpm" step="0.01" required>
                </div>    
              </div>                  
            </div>

            <div class="row mt-3 justify-content-center" >
                <button type="submit" class="btn btn-primary me-3" style="width :80px;">บันทึก</button>
                <button id="bt_cancel_group" type="button" class="btn btn-danger ms-3" style="width :80px;">ยกเลิก</button>
            </div>
          </form>
        </div>
  
          `;
  $("#add_group").html(html);
  $.ajax({
    type: "POST",
    url: "api/getDropdown.php",
    data: {id:'',fn:'depart'},
    success: function(res){
      let dropdown = $('#bd_id');
      dropdown.empty();
      dropdown.append('<option value="" disabled>--เลือกหน่วยงาน--</option>');
      dropdown.prop('selectedIndex', 0);
      $.each(res, function (key, entry) {
        dropdown.append($('<option></option>').attr('value', entry.id_depart).text(entry.depart));
      })                            
    }
  });
 
  $("#edit_group").html("");
  $("#bt_add_group").hide();
  $("#table_group").html("");  
});

$(document).on("click", "#bt_cancel_group", function() {  // ปิดฟอร์มเพิ่มข้อมูล
    $("#add_group").html("");
    $("#bt_add_group").show();
    showgrouptable(rowperpage,'1');
});

$(document).on("click", "#bt_cancel_editgroup", function() {  // ปิดฟอร์มแก้ไขข้อมูล
  $("#edit_group").html("");
  $("#bt_add_group").show();
  showgrouptable(rowperpage,'1');
});

$(document).on("submit", "#add_group_form", function() {   // บันทึกเพิ่มข้อมูล
    var add_form = $(this);
    var jwt = getCookie("jwt");
    var add_form_obj = add_form.serializeObject();
    add_form_obj.jwt = jwt;
    add_form_obj.acc = "add";
    var form_data = JSON.stringify(add_form_obj);
    $.ajax({
        url: "api/group_acc.php",
        type: "POST",
        contentType: "application/json",
        data: form_data,
        success: function(result) {
            $("#add_group").html("");
            $("#bt_add_group").show();
            Signed("success", " บันทึกข้อมูลสำเร็จ ");
            showgrouptable(rowperpage,'1');
        },
        error: function(xhr, resp, text) {
            if (xhr.responseJSON.message == "Unable to create Group.") {
                Signed("error", " บันทึกข้อมูลไม่สำเร็จ ");
            } else if (xhr.responseJSON.message == "Group Exit.") {
                swalertshow('warning', 'บันทึกข้อมูลไม่สำเร็จ', 'ชื่อกลุ่ม นี้มีอยู่แล้ว !');
            } else if (xhr.responseJSON.message == "Unable to access Group.") {
                Signed("warning", "ปฏิเสธการเข้าใช้ โปรดลองใหม่!");
            }else{
              showLoginPage();
              Signed("warning", "ปฏิเสธการเข้าใช้ โปรดเข้าสู่ระบบก่อน!");
            }
        },
    });
    return false;
});

$(document).on("submit", "#edit_group_form", function() {   // แก้ไขข้อมูล
    var edit_form = $(this);
    var jwt = getCookie("jwt");
    var edit_form_obj = edit_form.serializeObject();
    edit_form_obj.jwt = jwt;
    edit_form_obj.acc = "up";
    var form_data = JSON.stringify(edit_form_obj);    
    $.ajax({
        url: "api/group_acc.php",
        type: "POST",
        contentType: "application/json",
        data: form_data,
        success: function(result) {
            $("#edit_group").html("");
            Signed("success", "แก้ไขข้อมูลสำเร็จ ");
            showgrouptable(rowperpage,page_sel);
        },
        error: function(xhr, resp, text) {
            if (xhr.responseJSON.message == "Unable to update Group.") {
                Signed("error", " แก้ไขข้อมูลไม่สำเร็จ ");
            } else if (xhr.responseJSON.message == "Group Exit.") {
                swalertshow('warning', 'แก้ไขข้อมูลไม่สำเร็จ', 'ชื่อกลุ่ม นี้มีอยู่แล้ว !');
            } else if (xhr.responseJSON.message == "Unable to access Group.") {
                Signed("warning", "ปฏิเสธการเข้าใช้ โปรดลองใหม่!");
            }else{
              showLoginPage();
              Signed("warning", "ปฏิเสธการเข้าใช้ โปรดเข้าสู่ระบบก่อน!");
            }
        },
    });
    return false;
});