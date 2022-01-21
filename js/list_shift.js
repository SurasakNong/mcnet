
function show_shift_table(){ //========================== แสดงค้นหา และปุ่มเพิ่ม หมวดรายการ
    var html = `
  <div class="container animate__animated animate__fadeIn">
    <div class="row">                
        <div class="col-lg-6 mx-auto mt-3">
            <form id="fmsearch_shift">
                <div class="input-group mb-2">
                    <input type="text" id="search_shift" name="search_shift" class="form-control" placeholder="คำค้นหา.." aria-label="Search" aria-describedby="button-search">
                    <button class="btn btn-success" type="button" id="bt_search_shift" name="bt_search_shift" title="ค้นหา"><i class="fas fa-search"></i></button>
                    <button class="btn btn-primary ms-2" id="bt_add_shift" name="bt_add_shift" style="width: 42px;" type="button" title="เพิ่มข้อมูล"><i class="fas fa-plus"></i></button>
                    <button class="btn btn-warning ms-2" id="bt_back" name="bt_back" type="button" title="กลับ"><i class="fas fa-reply"></i></button>
                </div>
            </form>
        </div>
    </div>   
    <div class="row">  
        <div class="col-lg-6 mx-auto" id="add_shift"></div>
    </div>   
    <div class="row">  
        <div class="col-lg-6 mx-auto" id="edit_shift"></div>
    </div>   
    <div class="row">  
        <div class="col-lg-8 mx-auto" id="table_shift"></div>
    </div>
  </div>
    `;
    $("#content").html(html);
    showshifttable(rowperpage,page_sel); //<<<<<< แสดงตาราง
}

function showshifttable(per,p){ //======================== แสดงตาราง
  var ss = document.getElementById('search_shift').value;            
  var jwt = getCookie("jwt");
  var i = ((p-1)*per);
  $.ajax({
    type: "POST",
    url: "api/data_shift.php",
    data: {search:ss,perpage:per,page:p,jwt:jwt},
    success: function(result){
      var tt=`
      <table class="list-table table animate__animated animate__fadeIn" id="shifttable" >
        <thead>
          <tr>
            <th class="text-center" style="width:5%">ลำดับ</th> 
            <th class="text-left">ชื่อกะ</th>
            <th class="text-center">จำนวน</th>
            <th class="text-center">กะ1-เข้า</th>
            <th class="text-center">กะ1-ออก</th>
            <th class="text-center">กะ2-เข้า</th>
            <th class="text-center">กะ2-ออก</th>
            <th class="text-center">กะ3-เข้า</th>
            <th class="text-center">กะ3-ออก</th>
            <th class="text-center">แก้ไข&nbsp;&nbsp;&nbsp;ลบ</th>                
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
      <div class="mb-3" id="pagination">
      `;              
      $("#table_shift").html(tt);      
      pagination_show(p,result.page_all,per,'showshifttable'); //<<<<<<<< แสดงตัวจัดการหน้าข้อมูล Pagination >>const.js
      $.each(result.data, function (key, entry) {
        i++;
        listshiftTable(
            entry.shift_id,
            entry.shift_name,
            entry.shift_count,
            entry.shift_be1,
            entry.shift_en1,
            entry.shift_be2,
            entry.shift_en2,
            entry.shift_be3,
            entry.shift_en3,
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

function listshiftTable(id,sn,sc,sb1,se1,sb2,se2,sb3,se3,i){  //=========================== ฟังก์ชั่นเพิ่ม Row ตารางประเเภท
  var tableName = document.getElementById('shifttable');
    var prev = tableName.rows.length;           
    var row = tableName.insertRow(prev);
    row.id = "row" + id;
    row.style.verticalAlign = "top";
    var txtDel = `<i class="fas fa-trash-alt" style="cursor:not-allowed; color:#939393;"></i>`;
    if(u_type == "2"){
      txtDel = `<i class="fas fa-trash-alt" onclick="delete_shift_Row(` + id + `)" style="cursor:pointer; color:#d9534f;"></i>`;
    }
    var col1 = row.insertCell(0);
    var col2 = row.insertCell(1);
    var col3 = row.insertCell(2);
    var col4 = row.insertCell(3);
    var col5 = row.insertCell(4);
    var col6 = row.insertCell(5);
    var col7 = row.insertCell(6);
    var col8 = row.insertCell(7);
    var col9 = row.insertCell(8);
    var collast = row.insertCell(9);
    col1.innerHTML = `<div id="no" class="text-center">`+i+`</div>`;
    col2.innerHTML = `<div id="shift_name` + id + `" class="text-left">`+sn+`</div>`;
    col3.innerHTML = `<div id="shift_count` + id + `" class="text-center">`+sc+`</div>`;
    col4.innerHTML = `<div id="shift_be1` + id + `" class="text-center">`+((sb1== null)?"-":sb1)+`</div>`;
    col5.innerHTML = `<div id="shift_en1` + id + `" class="text-center">`+((se1== null)?"-":se1)+`</div>`;
    col6.innerHTML = `<div id="shift_be2` + id + `" class="text-center">`+((sb2== null)?"-":sb2)+`</div>`;
    col7.innerHTML = `<div id="shift_en2` + id + `" class="text-center">`+((se2== null)?"-":se2)+`</div>`;
    col8.innerHTML = `<div id="shift_be3` + id + `" class="text-center">`+((sb3== null)?"-":sb3)+`</div>`;
    col9.innerHTML = `<div id="shift_en3` + id + `" class="text-center">`+((se3== null)?"-":se3)+`</div>`;
    collast.innerHTML = `
    <input type="hidden" id="shift_id` + id + `" name="shift_id` + id + `" value="` + id + `" />
    <i class="fas fa-edit me-3" onclick="edit_shift_Row(` + id + `)" style="cursor:pointer; color:#5cb85c;"></i>
    `+txtDel; 
    collast.style = "text-align: center;";
}

function delete_shift_Row(id){ //================================ ลบข้อมูลในตาราง
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
            url: "api/shift_acc.php",
            type: "POST",
            contentType: "application/json",
            data: data,
            success: function(res) {
              swalWithBootstrapButtons.fire(
                  'ข้อมูลถูกลบ!',
                  'ข้อมูลของคุณได้ถูกลบออกจากระบบแล้ว!',
                  'success'
              );  
              showshifttable(rowperpage,page_sel);
            },
            error: function(xhr, resp, text) {
                if (xhr.responseJSON.message == "Unable to delete Shift.") {
                    Signed("error", "ลบข้อมูลไม่สำเร็จ !="+xhr.responseJSON.code);
                } else if (xhr.responseJSON.message == "Unable to access Shift.") {
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

function edit_shift_Row(id){ //============================== แก้ไขข้อมูลในตาราง    
    var shift_id = document.getElementById('shift_id'+id).value;
    var shift_name = document.getElementById('shift_name'+id).innerText;
    var shift_count = document.getElementById('shift_count'+id).innerText;
    var shift_be = new Array();
    var shift_en = new Array();
    shift_be[1] = document.getElementById('shift_be1'+id).innerText;
    shift_en[1] = document.getElementById('shift_en1'+id).innerText;
    shift_be[2] = document.getElementById('shift_be2'+id).innerText;
    shift_en[2] = document.getElementById('shift_en2'+id).innerText;
    shift_be[3] = document.getElementById('shift_be3'+id).innerText;
    shift_en[3] = document.getElementById('shift_en3'+id).innerText;
    var html = `          
    <div class="edit_shift animate__animated animate__fadeIn mt-3 mb-4">
      <div style="text-align: center;">
        <i class="far fa-edit fa-3x"></i>&nbsp;&nbsp;&nbsp;<a style="font-size: 2rem">แก้ไขข้อมูล</a>   
      </div>  
      <form class="myForm mt-2" id="edit_shift_form">
        <input type="hidden" name="shift_id" value="`+shift_id+`">

        <div class="row mb-2">    
          <div class="col-md-6 mb-2">                     
              <div class="form-group">
                <label for="shift_name">ชื่อกะ :</label>
                <input type="text" class="form-control" name="shift_name" id="shift_name" maxlength="100" required value="` +
                shift_name + `">
              </div>
          </div>    
          <div class="col-md-6">
            <div class="form-group">
                <label for="shift_count">จำนวนกะ :</label>
                <input type="number" class="form-control" name="shift_count" id="shift_count" min="1" max="3" step="1" required value="` +
                shift_count + `" placeholder="1-3">      
            </div>     
          </div>                  
        </div>
        <div class="row mb-2"> 
          <div class="col-md-6 mb-2">                     
              <div class="form-group">
                <label for="shift_be1">กะที่ 1 (เข้ากะ):</label>
                <input type="time" class="form-control" name="shift_be1" id="shift_be1" required value="` +
                shift_be[1] + `">
              </div>
          </div>    
          <div class="col-md-6">
            <div class="form-group">
                <label for="shift_en1">กะที่ 1 (ออกกะ):</label>
                <input type="time" class="form-control" name="shift_en1" id="shift_en1" required value="` +
                shift_en[1] + `">
            </div>  
          </div>                  
        </div>
        <div id="shift_set"></div>

        <div class="row mt-3 justify-content-center" >
            <button type="submit" class="btn btn-primary me-3" style="width :80px;">บันทึก</button>
            <button id="bt_cancel_editshift" type="button" class="btn btn-danger ms-3" style="width :80px;">ยกเลิก</button>
        </div>
      </form>
    </div>
      `;
      $("#edit_shift").html(html); 

    var shiftset_html = "";
      for(let s=2; s <= parseInt(shift_count); s++) {
          
            shiftset_html += `
            <div class="row mb-2"> 
              <div class="col-md-6 mb-2">                     
                  <div class="form-group">
                    <label for="shift_be`+s+`">กะที่ `+s+` (เข้ากะ):</label>
                    <input type="time" class="form-control" name="shift_be`+s+`" id="shift_be`+s+`" required value="` +
                    shift_be[s] + `">
                  </div>
              </div>    
              <div class="col-md-6">
                <div class="form-group">
                    <label for="shift_en`+s+`">กะที่ `+s+` (ออกกะ):</label>
                    <input type="time" class="form-control" name="shift_en`+s+`" id="shift_en`+s+`" required value="` +
                    shift_en[s] + `">
                </div>  
              </div>                  
            </div>
            `;                 
      }
      $("#shift_set").html(shiftset_html); 
    
    $("#add_shift").html("");  
    $("#table_shift").html("");              

}

//=========================== Even เกี่ยวกับ รายการ ======================================
$(document).on('click',"#bt_search_shift",function () {  //ค้นหารายการ
  $("#edit_shift").html("");
  $("#add_shift").html("");
  $("#bt_add_shift").show();
  showshifttable(rowperpage,'1');          
});

$(document).on("click", "#bt_add_shift", function() { // แสดงหน้าบันทึกเพิ่มข้อมูล  
  var html = `          
        <div class="add_shift animate__animated animate__fadeIn mt-3 mb-4">
          <div style="text-align: center;">
            <i class="fab fa-buffer fa-3x"></i>&nbsp;&nbsp;&nbsp;<a style="font-size: 2rem">เพิ่มข้อมูล</a>   
          </div> 
          <form class="myForm mt-2" id="add_shift_form">
            <div class="row mb-2">    
            <div class="col-md-6 mb-2">                     
                <div class="form-group">
                    <label for="shift_name">ชื่อกะ :</label>
                    <input type="text" class="form-control" name="shift_name" id="shift_name" maxlength="100" required>
                </div>
            </div>    
            <div class="col-md-6">
                <div class="form-group">
                    <label for="shift_count">จำนวนกะ :</label>
                    <input type="number" class="form-control" name="shift_count" id="shift_count" min="1" max="3" step="1" required placeholder="1-3" value="1">      
                </div>     
            </div>                  
            </div>

            <div class="row mb-2"> 
              <div class="col-md-6 mb-2">                     
                  <div class="form-group">
                    <label for="shift_be1">กะที่ 1 (เข้ากะ):</label>
                    <input type="time" class="form-control" name="shift_be1" id="shift_be1" required>
                  </div>
              </div>    
              <div class="col-md-6">
                <div class="form-group">
                    <label for="shift_en">กะที่ 1 (ออกกะ):</label>
                    <input type="time" class="form-control" name="shift_en1" id="shift_en1" required>
                </div>  
              </div>                  
            </div>

            <div id="shift_set"></div>

            <div class="row mt-3 justify-content-center" >
                <button type="submit" class="btn btn-primary me-3" style="width :80px;">บันทึก</button>
                <button id="bt_cancel_shift" type="button" class="btn btn-danger ms-3" style="width :80px;">ยกเลิก</button>
            </div>
          </form>
        </div>
  
          `;
  $("#add_shift").html(html);
  
 
  $("#edit_shift").html("");
  $("#bt_add_shift").hide();
  $("#table_shift").html("");  
});

$(document).on("change", "#shift_count", function() {  // จำนวนกะ
    var shift_n = $(this).val();
    var shiftset_text='';
    if((shift_n > 1 ) && (shift_n <= 3)){
      for(let s=2; s <= shift_n; s++) {        
              shiftset_text += `
            <div class="row mb-2"> 
              <div class="col-md-6 mb-2">                     
                  <div class="form-group">
                    <label for="shift_be`+s+`">กะที่ `+s+` (เข้ากะ):</label>
                    <input type="time" class="form-control" name="shift_be`+s+`" id="shift_be`+s+`" required>
                  </div>
              </div>    
              <div class="col-md-6">
                <div class="form-group">
                    <label for="shift_en`+s+`">กะที่ `+s+` (ออกกะ):</label>
                    <input type="time" class="form-control" name="shift_en`+s+`" id="shift_en`+s+`" required>
                </div>  
              </div>                  
            </div>
            `;        
      }
    }else{
      shiftset_text='';
    }
    
    $("#shift_set").html(shiftset_text);
    
});

$(document).on("click", "#bt_cancel_shift", function() {  // ปิดฟอร์มเพิ่มข้อมูล
    $("#add_shift").html("");
    $("#bt_add_shift").show();
    showshifttable(rowperpage,'1');
});

$(document).on("click", "#bt_cancel_editshift", function() {  // ปิดฟอร์มแก้ไขข้อมูล
  $("#edit_shift").html("");
  $("#bt_add_shift").show();
  showshifttable(rowperpage,'1');
});

$(document).on("submit", "#add_shift_form", function() {   // บันทึกเพิ่มข้อมูล
    var add_form = $(this);
    var jwt = getCookie("jwt");
    var add_form_obj = add_form.serializeObject();
    add_form_obj.jwt = jwt;
    add_form_obj.acc = "add";
    var form_data = JSON.stringify(add_form_obj);

    $.ajax({
        url: "api/shift_acc.php",
        type: "POST",
        contentType: "application/json",
        data: form_data,
        success: function(result) {
            $("#add_shift").html("");
            $("#bt_add_shift").show();
            Signed("success", " บันทึกข้อมูลสำเร็จ ");
            showshifttable(rowperpage,'1');
        },
        error: function(xhr, resp, text) {
            if (xhr.responseJSON.message == "Unable to create Shift.") {
                Signed("error", " บันทึกข้อมูลไม่สำเร็จ ");
            } else if (xhr.responseJSON.message == "Shift Exit.") {
                swalertshow('warning', 'บันทึกข้อมูลไม่สำเร็จ', 'ชื่อกะทำงาน นี้มีอยู่แล้ว !');
            } else if (xhr.responseJSON.message == "Unable to access Shift.") {
                Signed("warning", "ปฏิเสธการเข้าใช้ โปรดลองใหม่!");
            }else{
              showLoginPage();
              Signed("warning", "ปฏิเสธการเข้าใช้ โปรดเข้าสู่ระบบก่อน!");
            }
        },
    });
    return false;
});

$(document).on("submit", "#edit_shift_form", function() {   // แก้ไขข้อมูล
    var edit_form = $(this);
    var jwt = getCookie("jwt");
    var edit_form_obj = edit_form.serializeObject();
    edit_form_obj.jwt = jwt;
    edit_form_obj.acc = "up";
    var form_data = JSON.stringify(edit_form_obj);    
    $.ajax({
        url: "api/shift_acc.php",
        type: "POST",
        contentType: "application/json",
        data: form_data,
        success: function(result) {
            $("#edit_shift").html("");
            Signed("success", "แก้ไขข้อมูลสำเร็จ ");
            showshifttable(rowperpage,page_sel);
        },
        error: function(xhr, resp, text) {
            if (xhr.responseJSON.message == "Unable to update Shift.") {
                Signed("error", " แก้ไขข้อมูลไม่สำเร็จ ");
            } else if (xhr.responseJSON.message == "Shift Exit.") {
                swalertshow('warning', 'แก้ไขข้อมูลไม่สำเร็จ', 'ชื่อกะทำงาน นี้มีอยู่แล้ว !');
            } else if (xhr.responseJSON.message == "Unable to access Shift.") {
                Signed("warning", "ปฏิเสธการเข้าใช้ โปรดลองใหม่!");
            }else{
              showLoginPage();
              Signed("warning", "ปฏิเสธการเข้าใช้ โปรดเข้าสู่ระบบก่อน!");
            }
        },
    });
    return false;
});