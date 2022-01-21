
function show_mc_table(){ //========================== แสดงค้นหา และปุ่มเพิ่ม หมวดรายการ
    var html = `
  <div class="container animate__animated animate__fadeIn">
    <div class="row">                
        <div class="col-lg-6 mx-auto mt-3">
            <form id="fmsearch_mc">
                <div class="input-group mb-2">
                    <input type="text" id="search_mc" name="search_mc" class="form-control" placeholder="คำค้นหา.." aria-label="Search" aria-describedby="button-search">
                    <button class="btn btn-success" type="button" id="bt_search_mc" name="bt_search_mc" title="ค้นหา"><i class="fas fa-search"></i></button>
                    <button class="btn btn-primary ms-2" id="bt_add_mc" name="bt_add_mc" style="width: 42px;" type="button" title="เพิ่มข้อมูล"><i class="fas fa-plus"></i></button>
                    <button class="btn btn-warning ms-2" id="bt_back" name="bt_back" type="button" title="กลับ"><i class="fas fa-reply"></i></button>
                </div>
            </form>
        </div>
    </div>   
    <div class="row">  
        <div class="col-lg-12 mx-auto" id="add_mc"></div>
    </div>   
    <div class="row">  
        <div class="col-lg-12 mx-auto" id="edit_mc"></div>
    </div>   
    <div class="row">  
        <div class="col-lg-12 mx-auto" id="table_mc"></div>
    </div>
  </div>
    `;
    $("#content").html(html);
    showmctable(rowperpage,page_sel); //<<<<<< แสดงตาราง
}

function showmctable(per,p){ //======================== แสดงตาราง
  var ss = document.getElementById('search_mc').value;            
  var jwt = getCookie("jwt");
  var i = ((p-1)*per);
  $.ajax({
    type: "POST", 
    url: "api/data_mc.php",
    data: {search:ss,perpage:per,page:p,jwt:jwt},
    success: function(result){
      var tt=`
      <table class="list-table table animate__animated animate__fadeIn" id="mctable" >
        <thead>
          <tr>
            <th class="text-center" style="width:5%">ลำดับ</th> 
            <th class="text-left">ชื่อ</th>
            <th class="text-center">ใช้งาน</th>
            <th class="text-left">กลุ่ม</th>
            <th class="text-left">หน่วยงาน</th>
            <th class="text-left">กะทำงาน</th>
            <th class="text-center">RPM</th>
            <th class="text-center">ลูกค้า</th>
            <th class="text-center">POPI</th>
            <th class="text-center">Order-item</th>
            <th class="text-center">สเปค</th>
            <th class="text-center">แก้ไข&nbsp;&nbsp;&nbsp;ลบ</th>                
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
      <div class="mb-3" id="pagination">
      `;              
      $("#table_mc").html(tt);      
      pagination_show(p,result.page_all,per,'showmctable'); //<<<<<<<< แสดงตัวจัดการหน้าข้อมูล Pagination >>const.js
      $.each(result.data, function (key, entry) {
        i++;
        listmcTable(entry,i); //<<<<< แสดงรายการทั้งหมด             
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

function listmcTable(ob,i){  //=========================== ฟังก์ชั่นเพิ่ม Row
  let tableName = document.getElementById('mctable');
    let prev = tableName.rows.length;           
    let row = tableName.insertRow(prev);
    row.id = "row" + ob.id_mc;
    row.style.verticalAlign = "top";
    let txtDel = `<i class="fas fa-trash-alt" style="cursor:not-allowed; color:#939393;"></i>`;
    if(u_type == "2"){
      txtDel = `<i class="fas fa-trash-alt" onclick="delete_mc_Row(` + ob.id_mc + `)" style="cursor:pointer; color:#d9534f;"></i>`;
    }
    let mcused = (ob.mc_used == "1")?`<i class="far fa-check-square"></i>`:`<i class="far fa-square"></i>`;
    let n_col = 12;
    let col = [];
    for(let ii=0; ii<n_col; ii++){
      col[ii] = row.insertCell(ii);
    }
    col[0].innerHTML = `<div id="no" class="text-center">`+i+`</div>`;
    col[1].innerHTML = `<div id="mc` + ob.id_mc + `" class="text-left">`+ob.mc+`</div>`;
    col[2].innerHTML = `<div id="mu` + ob.id_mc + `" class="text-center">`+mcused+`</div>`;
    col[3].innerHTML = `<div id="group_name` + ob.id_mc + `" class="text-left">`+ob.group_name+`</div>`;
    col[4].innerHTML = `<div id="depart` + ob.id_mc + `" class="text-left">`+ob.bd_name+`</div>`;
    col[5].innerHTML = `<div id="shift_name` + ob.id_mc + `" class="text-left">`+ob.shift_name+`</div>`;
    col[6].innerHTML = `<div id="mc_rpm` + ob.id_mc + `" class="text-center">`+parseFloat(ob.mc_rpm).toFixed(2)+`</div>`;
    col[7].innerHTML = `<div id="cust` + ob.id_mc + `" class="text-left">`+ob.cust+`</div>`;
    col[8].innerHTML = `<div id="popi` + ob.id_mc + `" class="text-left">`+ob.popi+`</div>`;
    col[9].innerHTML = `<div id="ord_it` + ob.id_mc + `" class="text-left">`+ob.ord+`-`+ob.item+`</div>`;
    col[10].innerHTML = `<div id="spect` + ob.id_mc + `" class="text-left">${ob.dia} ${ob.color} ${ob.ms}x${ob.md}x${ob.ml_mt}(${ob.ml_kn})</div>`;
    col[n_col-1].innerHTML = `
    <input type="hidden" id="id_mc` + ob.id_mc + `" name="id_mc` + ob.id_mc + `" value="` + ob.id_mc + `" />
    <input type="hidden" id="group_id` + ob.id_mc + `" name="group_id` + ob.id_mc + `" value="` + ob.group_id + `" />
    <input type="hidden" id="shift_id` + ob.id_mc + `" name="shift_id` + ob.id_mc + `" value="` + ob.shift_id + `" />
    <input type="hidden" id="mc_used` + ob.id_mc + `" name="mc_used` + ob.id_mc + `" value="` + ob.mc_used + `" />

    <input type="hidden" id="ord` + ob.id_mc + `" name="ord` + ob.id_mc + `" value="` + ob.ord + `" />
    <input type="hidden" id="item` + ob.id_mc + `" name="item` + ob.id_mc + `" value="` + ob.item + `" />
    <input type="hidden" id="dia` + ob.id_mc + `" name="dia` + ob.id_mc + `" value="` + ob.dia + `" />
    <input type="hidden" id="color` + ob.id_mc + `" name="color` + ob.id_mc + `" value="` + ob.color + `" />
    <input type="hidden" id="ms` + ob.id_mc + `" name="ms` + ob.id_mc + `" value="` + ob.ms + `" />
    <input type="hidden" id="md` + ob.id_mc + `" name="md` + ob.id_mc + `" value="` + ob.md + `" />
    <input type="hidden" id="ml_mt` + ob.id_mc + `" name="ml_mt` + ob.id_mc + `" value="` + ob.ml_mt + `" />
    <input type="hidden" id="ml_kn` + ob.id_mc + `" name="ml_kn` + ob.id_mc + `" value="` + ob.ml_kn + `" />

    <i class="fas fa-edit me-3" onclick="edit_mc_Row(` + ob.id_mc + `)" style="cursor:pointer; color:#5cb85c;"></i>
    `+txtDel; 
    col[n_col-1].style = "text-align: center;";
}

function delete_mc_Row(id){ //================================ ลบข้อมูลในตาราง
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
            url: "api/mc_acc.php",
            type: "POST",
            contentType: "application/json",
            data: data,
            success: function(res) {
              swalWithBootstrapButtons.fire(
                  'ข้อมูลถูกลบ!',
                  'ข้อมูลของคุณได้ถูกลบออกจากระบบแล้ว!',
                  'success'
              );  
              showmctable(rowperpage,page_sel);
            },
            error: function(xhr, resp, text) {
                if (xhr.responseJSON.message == "Unable to delete Mc.") {
                    Signed("error", "ลบข้อมูลไม่สำเร็จ !="+xhr.responseJSON.code);
                } else if (xhr.responseJSON.message == "Unable to access Mc.") {
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

function edit_mc_Row(id){ //============================== แก้ไขข้อมูลในตาราง    
    var id_mc = document.getElementById('id_mc'+id).value;
    var mc = document.getElementById('mc'+id).innerText;
    var group_id = document.getElementById('group_id'+id).value;
    var shift_id = document.getElementById('shift_id'+id).value;   
    var mc_rpm = parseFloat(document.getElementById('mc_rpm'+id).innerText).toFixed(2); 
    var cust = document.getElementById('cust'+id).innerText;
    var popi = document.getElementById('popi'+id).innerText;
    var ord = document.getElementById('ord'+id).value; 
    var item = document.getElementById('item'+id).value; 
    var dia = document.getElementById('dia'+id).value; 
    var color = document.getElementById('color'+id).value; 
    var ms = document.getElementById('ms'+id).value; 
    var md = document.getElementById('md'+id).value; 
    var ml_mt = document.getElementById('ml_mt'+id).value; 
    var ml_kn = document.getElementById('ml_kn'+id).value; 

    var mc_used = document.getElementById('mc_used'+id).value;   
    var html = `          
    <div class="edit_mc animate__animated animate__fadeIn mt-3 mb-4">
      <div style="text-align: center;">
        <i class="far fa-edit fa-3x"></i>&nbsp;&nbsp;&nbsp;<a style="font-size: 2rem">แก้ไขข้อมูล ${mc}</a>   
      </div>  
      <form class="myForm mt-2" id="edit_mc_form">
        <input type="hidden" name="id_mc" value="${id_mc}">

        <div class="row mb-2">    
          <div class="col-md-3 mb-2">                     
              <div class="form-group">
                <label for="mc">ชื่อเครื่อง :</label>
                <input type="text" class="form-control" name="mc" id="mc" maxlength="100" required value="${mc}">
              </div>
          </div>    
          <div class="col-md-3">
            <div class="form-group">
                <label for="group_id">กลุ่ม :</label>
                <select class="form-select" name="group_id" id="group_id" required></select>          
            </div>     
          </div>     
          <div class="col-md-3 mb-2">                     
            <div class="form-group">
                <label for="shift_id">กะ ทำงาน :</label>
                <select class="form-select" name="shift_id" id="shift_id" required></select>          
            </div> 
          </div>    
          <div class="col-md-3">
            <div class="form-group">
                <label for="group_rpm">รอบ/นาที :</label>
                <input type="number" class="form-control" name="mc_rpm" id="mc_rpm" step="0.01" required value="${mc_rpm}">
            </div>       
          </div>             
        </div>

        <div class="row mb-3">   
          <div class="col-md-3 mb-2">                     
            <div class="form-group">
              <label for="cust">ลูกค้า :</label>
              <input type="text" class="form-control" name="cust" id="cust" maxlength="20" value="${cust}">
            </div>
          </div>    
          <div class="col-md-3">
            <div class="form-group">
              <label for="popi">POPI :</label>
              <input type="text" class="form-control" name="popi" id="popi" maxlength="20" value="${popi}">
            </div>   
          </div>     
          <div class="col-md-3 mb-2">                     
            <div class="form-group">
              <label for="ord">ออร์เดอร์ :</label>
              <input type="text" class="form-control" name="ord" id="ord" maxlength="20" value="${ord}">
            </div>  
          </div>    
          <div class="col-md-3">
            <div class="form-group">
              <label for="item">ไอเทม :</label>
              <input type="text" class="form-control" name="item" id="item" maxlength="20" value="${item}">
            </div>       
          </div>      
        </div>

        <div class="row mb-2">   
          <div class="col-md-3 mb-2">                     
            <div class="form-group">
              <label for="dia">เบอร์ด้าย :</label>
              <input type="text" class="form-control" name="dia" id="dia" maxlength="20" value="${dia}">
            </div>
          </div>    
          <div class="col-md-3">
            <div class="form-group">
              <label for="color">สี :</label>
              <input type="text" class="form-control" name="color" id="color" maxlength="20" value="${color}">
            </div>   
          </div>     
        </div>

        <div class="row mb-2">   
          <div class="col-md-3 mb-2">                     
            <div class="form-group">
              <label for="ms">ขนาดตา (ซ.ม.) :</label>
              <input type="text" class="form-control" name="ms" id="ms" maxlength="20" value="${ms}">
            </div>
          </div>    
          <div class="col-md-3">
            <div class="form-group">
              <label for="md">ความลึก :</label>
              <input type="text" class="form-control" name="md" id="md" maxlength="20" value="${md}">
            </div>   
          </div>     
          <div class="col-md-3 mb-2">                     
            <div class="form-group">
              <label for="ml_mt">ความยาว (เมตร) :</label>
              <input type="text" class="form-control" name="ml_mt" id="ml_mt" maxlength="20" value="${ml_mt}">
            </div>  
          </div>    
          <div class="col-md-3">
            <div class="form-group">
              <label for="ml_kn">ความยาว (ข้อ) :</label>
              <input type="text" class="form-control" name="ml_kn" id="ml_kn" maxlength="20" value="${ml_kn}">
            </div>       
          </div>      
        </div>

        <div class="row mt-3 mb-4 justify-content-center">        
          <div class="col-sm-2">     
            <div class="form-check mb-2">
              <input class="form-check-input" type="radio" name="mc_used" value="1" id="mc_used1">
              <label class="form-check-label" for="mc_used1">ใช้งาน</label>
            </div>
          </div>
          <div class="col-sm-2">   
            <div class="form-check">
              <input class="form-check-input" type="radio" name="mc_used" value="0" id="mc_used2">
              <label class="form-check-label" for="mc_used2">ไม่ใช้</label>
            </div>
          </div>
        </div>

        <div class="row mt-3 justify-content-center" >
            <button type="submit" class="btn btn-primary me-3" style="width :80px;">บันทึก</button>
            <button id="bt_cancel_editmc" type="button" class="btn btn-danger ms-3" style="width :80px;">ยกเลิก</button>
        </div>
      </form>
    </div>
      `;
      $("#edit_mc").html(html); 

      if(mc_used == '1'){
        document.getElementById('mc_used1').checked = true;
      }else{
        document.getElementById('mc_used2').checked = true;
      }
      $.ajax({
        type: "POST",
        url: "api/getDropdown.php",
        data: {id:'',fn:'group'},
        success: function(res){
          let dropdown = $('#group_id');
          dropdown.empty();
          $.each(res, function (key, entry) {
            dropdown.append($('<option></option>').attr('value', entry.group_id).text(entry.group_name+' '+entry.bd_name));
          })
          $("#group_id option[value='"+group_id+"']").attr("selected","selected");                             
        }
      });

      $.ajax({
        type: "POST",
        url: "api/getDropdown.php",
        data: {id:'',fn:'shift'},
        success: function(res){
          let dropdown = $('#shift_id');
          dropdown.empty();
          $.each(res, function (key, entry) {
            dropdown.append($('<option></option>').attr('value', entry.shift_id).text(entry.shift_name));
          })
          $("#shift_id option[value='"+shift_id+"']").attr("selected","selected");                             
        }
      });
    
    $("#add_mc").html("");  
    $("#table_mc").html("");              

}

//=========================== Even เกี่ยวกับ รายการ ======================================
$(document).on('click',"#bt_search_mc",function () {  //ค้นหารายการ
  $("#edit_mc").html("");
  $("#add_mc").html("");
  $("#bt_add_mc").show();
  showmctable(rowperpage,'1');          
});

$(document).on("click", "#bt_add_mc", function() { // แสดงหน้าบันทึกเพิ่มข้อมูล  
  var html = `          
        <div class="add_mc animate__animated animate__fadeIn mt-3 mb-4">
          <div style="text-align: center;">
            <i class="fab fa-buffer fa-3x"></i>&nbsp;&nbsp;&nbsp;<a style="font-size: 2rem">เพิ่มข้อมูล</a>   
          </div> 
          <form class="myForm mt-2" id="add_mc_form">
            <div class="row mb-2">    
              <div class="col-md-3 mb-2">                     
                  <div class="form-group">
                      <label for="group_name">ชื่อเครื่อง :</label>
                      <input type="text" class="form-control" name="mc" id="mc" maxlength="100" required>
                  </div>
              </div>    
              <div class="col-md-3">
                  <div class="form-group">
                      <label for="group_id">กลุ่ม :</label>
                      <select class="form-select" name="group_id" id="group_id" required></select>          
                  </div>     
              </div>    
              <div class="col-md-3 mb-2">                     
                  <div class="form-group">
                      <label for="shift_id">กะ ทำงาน :</label>
                      <select class="form-select" name="shift_id" id="shift_id" required></select>          
                  </div> 
              </div>    
              <div class="col-md-3">
                  <div class="form-group">
                      <label for="group_rpm">รอบ/นาที :</label>
                      <input type="number" class="form-control" name="mc_rpm" id="mc_rpm" step="0.01" required>
                  </div>       
              </div>               
            </div>

            <div class="row mb-3">    
              <div class="col-md-3 mb-2">                     
                  <div class="form-group">
                      <label for="cust">ลูกค้า :</label>
                      <input type="text" class="form-control" name="cust" id="mc" maxlength="20" >
                  </div>
              </div>    
              <div class="col-md-3 mb-2">                     
                  <div class="form-group">
                      <label for="popi">POPI :</label>
                      <input type="text" class="form-control" name="popi" id="popi" maxlength="20" >
                  </div>
              </div> 
              <div class="col-md-3 mb-2">                     
                  <div class="form-group">
                      <label for="ord">ออร์เดอร์ :</label>
                      <input type="text" class="form-control" name="ord" id="ord" maxlength="20" >
                  </div>
              </div> 
              <div class="col-md-3 mb-2">                     
                  <div class="form-group">
                      <label for="item">ไอเทม :</label>
                      <input type="text" class="form-control" name="item" id="item" maxlength="20" >
                  </div>
              </div>              
            </div>

            <div class="row mb-2">    
              <div class="col-md-3 mb-2">                     
                  <div class="form-group">
                      <label for="dia">เบอร์ด้าย :</label>
                      <input type="text" class="form-control" name="dia" id="dia" maxlength="20" >
                  </div>
              </div>    
              <div class="col-md-3 mb-2">                     
                  <div class="form-group">
                      <label for="color">สี :</label>
                      <input type="text" class="form-control" name="color" id="color" maxlength="20" >
                  </div>
              </div>             
            </div>

            <div class="row mb-2">    
              <div class="col-md-3 mb-2">                     
                  <div class="form-group">
                      <label for="ms">ขนาดตา :</label>
                      <input type="text" class="form-control" name="ms" id="ms" maxlength="20" >
                  </div>
              </div>    
              <div class="col-md-3 mb-2">                     
                  <div class="form-group">
                      <label for="md">ความลึก :</label>
                      <input type="text" class="form-control" name="md" id="md" maxlength="20" >
                  </div>
              </div> 
              <div class="col-md-3 mb-2">                     
                  <div class="form-group">
                      <label for="ml_mt">ความยาว (เมตร) :</label>
                      <input type="text" class="form-control" name="ml_mt" id="ml_mt" maxlength="20" >
                  </div>
              </div> 
              <div class="col-md-3 mb-2">                     
                  <div class="form-group">
                      <label for="ml_kn">ความยาว (ข้อ) :</label>
                      <input type="text" class="form-control" name="ml_kn" id="ml_kn" maxlength="20" >
                  </div>
              </div>              
            </div>

            <div class="row mt-3 mb-4 justify-content-center">        
              <div class="col-sm-2">     
                <div class="form-check mb-2">
                  <input class="form-check-input" type="radio" name="mc_used" value="1" id="mc_used1" checked>
                  <label class="form-check-label" for="mc_used1">ใช้งาน</label>
                </div>
              </div>
              <div class="col-sm-2">   
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="mc_used" value="0" id="mc_used2">
                  <label class="form-check-label" for="mc_used2">ไม่ใช้</label>
                </div>
              </div>
            </div>


            <div class="row mt-3 justify-content-center" >
                <button type="submit" class="btn btn-primary me-3" style="width :80px;">บันทึก</button>
                <button id="bt_cancel_mc" type="button" class="btn btn-danger ms-3" style="width :80px;">ยกเลิก</button>
            </div>
          </form>
        </div>
  
          `;
  $("#add_mc").html(html);
  $.ajax({
    type: "POST",
    url: "api/getDropdown.php",
    data: {id:'',fn:'group'},
    success: function(res){
      let dropdown = $('#group_id');
      dropdown.empty();
      dropdown.append('<option value="" disabled>--เลือกกลุ่ม--</option>');
      dropdown.prop('selectedIndex', 0);
      $.each(res, function (key, entry) {
        dropdown.append($('<option></option>').attr('value', entry.group_id).text(entry.group_name+' '+entry.bd_name));
      })                            
    }
  });
  $.ajax({
    type: "POST",
    url: "api/getDropdown.php",
    data: {id:'',fn:'shift'},
    success: function(res){
      let dropdown = $('#shift_id');
      dropdown.empty();
      dropdown.append('<option value="" disabled>--เลือกกะทำงาน--</option>');
      dropdown.prop('selectedIndex', 0);
      $.each(res, function (key, entry) {
        dropdown.append($('<option></option>').attr('value', entry.shift_id).text(entry.shift_name));
      })                            
    }
  });
 
  $("#edit_mc").html("");
  $("#bt_add_mc").hide();
  $("#table_mc").html("");  
});

$(document).on("click", "#bt_cancel_mc", function() {  // ปิดฟอร์มเพิ่มข้อมูล
    $("#add_mc").html("");
    $("#bt_add_mc").show();
    showmctable(rowperpage,'1');
});

$(document).on("click", "#bt_cancel_editmc", function() {  // ปิดฟอร์มแก้ไขข้อมูล
  $("#edit_mc").html("");
  $("#bt_add_mc").show();
  showmctable(rowperpage,'1');
});

$(document).on("submit", "#add_mc_form", function() {   // บันทึกเพิ่มข้อมูล
    var add_form = $(this);
    var jwt = getCookie("jwt");
    var add_form_obj = add_form.serializeObject();
    add_form_obj.jwt = jwt;
    add_form_obj.acc = "add";
    var form_data = JSON.stringify(add_form_obj);
    $.ajax({
        url: "api/mc_acc.php",
        type: "POST",
        contentType: "application/json",
        data: form_data,
        success: function(result) {
            $("#add_mc").html("");
            $("#bt_add_mc").show();
            Signed("success", " บันทึกข้อมูลสำเร็จ ");
            showmctable(rowperpage,'1');
        },
        error: function(xhr, resp, text) {
            if (xhr.responseJSON.message == "Unable to create Mc.") {
                Signed("error", " บันทึกข้อมูลไม่สำเร็จ ");
            } else if (xhr.responseJSON.message == "Mc Exit.") {
                swalertshow('warning', 'บันทึกข้อมูลไม่สำเร็จ', 'ชื่อกลุ่ม นี้มีอยู่แล้ว !');
            } else if (xhr.responseJSON.message == "Unable to access Mc.") {
                Signed("warning", "ปฏิเสธการเข้าใช้ โปรดลองใหม่!");
            }else{
              showLoginPage();
              Signed("warning", "ปฏิเสธการเข้าใช้ โปรดเข้าสู่ระบบก่อน!");
            }
        },
    });
    return false;
});

$(document).on("submit", "#edit_mc_form", function() {   // แก้ไขข้อมูล
    var edit_form = $(this);
    var jwt = getCookie("jwt");
    var edit_form_obj = edit_form.serializeObject();
    edit_form_obj.jwt = jwt;
    edit_form_obj.acc = "up";
    var form_data = JSON.stringify(edit_form_obj);    
    $.ajax({
        url: "api/mc_acc.php",
        type: "POST",
        contentType: "application/json",
        data: form_data,
        success: function(result) {
            $("#edit_mc").html("");
            Signed("success", "แก้ไขข้อมูลสำเร็จ ");
            showmctable(rowperpage,page_sel);
        },
        error: function(xhr, resp, text) {
            if (xhr.responseJSON.message == "Unable to update Mc.") {
                Signed("error", " แก้ไขข้อมูลไม่สำเร็จ ");
            } else if (xhr.responseJSON.message == "Mc Exit.") {
                swalertshow('warning', 'แก้ไขข้อมูลไม่สำเร็จ', 'ชื่อเครื่อง นี้มีอยู่แล้ว !');
            } else if (xhr.responseJSON.message == "Unable to access Mc.") {
                Signed("warning", "ปฏิเสธการเข้าใช้ โปรดลองใหม่!");
            }else{
              showLoginPage();
              Signed("warning", "ปฏิเสธการเข้าใช้ โปรดเข้าสู่ระบบก่อน!");
            }
        },
    });
    return false;
});