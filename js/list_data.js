
function show_data_table(){ //========================== แสดงค้นหา และปุ่มเพิ่ม หมวดรายการ
    var html = `
  <div class="container-fluid animate__animated animate__fadeIn">
    <div class="row" id="fmsearch_data_main">                
        <div class="col-lg-6 mx-auto mt-3">
            <form id="fmsearch_data">
                <div class="input-group mb-2">
                    <input type="text" id="search_data" name="search_data" class="form-control" placeholder="คำค้นหา..5C19 จอดกระสวย จอดอื่นๆ อาคาร5 อาคาร-12 เดิน" aria-label="Search" aria-describedby="button-search">
                    <button class="btn btn-success" type="button" id="bt_search_data" name="bt_search_data" title="ค้นหา"><i class="fas fa-search"></i></button>                    
                    <button class="btn btn-primary ms-2" id="bt_back" name="bt_back" type="button" title="หน้าหลัก"><i class="fas fa-home"></i></button>
                </div>
            </form>
        </div>
    </div>   
    <hr>  
    <div class="row">  
        <div class="col-lg mx-auto noselect" id="table_data"></div>
    </div>

    <ul class="dropdown-menu"  id="ctxMenu">
      <li><a class="dropdown-item" href="#" id="setzero_acc">Set to Zero</a></li>
      <li><a class="dropdown-item" href="#" id="Allsetzero_acc">Set all to zero</a></li>
      <li><hr class="dropdown-divider"></li>
      <li><a class="dropdown-item" href="#" id="shift_set">Set shift now</a></li>
      <li><a class="dropdown-item" href="#" id="shift_set_all">Set all shift now</a></li>
      <li><hr class="dropdown-divider"></li>
      <li><a class="dropdown-item" href="#" id="ord_set">Set Order</a></li>
      <li><a class="dropdown-item" href="#" id="mc_set">Set all to initial</a></li>
      <li><hr class="dropdown-divider"></li>
      <li><a class="dropdown-item" href="#" id="x_closemenu">x Close menu</a></li>
    </ul>

    <div id="edit_data">
        <form id="fmedit_data">
            <div class="input-group">
            <input type="hidden" name="mc_data" id="mc_data">
            <input type="hidden" name="head_data" id="head_data">
                <input type="number" id="ed_data" name="ed_data" class="form-control" step="1" required>
                <button class="btn btn-success" type="submit" id="edit_data_bt" name="edit_data_bt" title="บันทึก"><i class="fas fa-check"></i></button>                    
                <button class="btn btn-danger ms-2" id="cancel_edit_bt" name="cancel_edit_bt" type="button" title="ยกเลิก"><i class="fas fa-times"></i></button>
            </div>
        </form>
    </div>
   
  </div>
    `;
    $("#content").html(html);
    clearInterval(page_data_inteval); //ยกเลิกการดึงข้อมูลตามเวลาที่ตั้งไว้    
    showdatatable(rowperpage,page_sel); //<<<<<< แสดงตาราง
    list_data_inteval = setInterval(listdata_interval,3000);
}

function showdatatable(per,p){ //======================== แสดงตาราง
  var ss = document.getElementById('search_data').value;            
  var jwt = getCookie("jwt");
  var i = ((p-1)*per);
  $.ajax({
    type: "POST", 
    url: "api/data_monitor.php",
    data: {search:ss,perpage:per,page:p,jwt:jwt,fn:"list"},
    success: function(result){
      var tt=`
      <table class="list-table table" id="datatable" >
        <thead>
          <tr>
            <th class="text-center">เครื่อง</th> 
            <th class="text-center">สถานะ</th>
            <th class="text-center">ออร์เดอร์</th>
            <th class="text-end">RPM</th>
            <th class="text-end">มิเตอร์</th>
            <th class="text-end" style="color:#2059df;">นาที</th>
            <th class="text-end" style="background-color: #d6d1b1; color:#400000; border-radius: 18px 0 0 0;">ด้ายบน</th>
            <th class="text-end" style="background-color: #d6d1b1; color:#400000;">นาที</th>
            <th class="text-end" style="background-color: #c7efcf; color:#400000;">ด้ายขาด</th>
            <th class="text-end" style="background-color: #c7efcf; color:#400000;">นาที</th>
            <th class="text-end" style="background-color: #6cdbee; color:#400000;">อีแปะ</th>
            <th class="text-end" style="background-color: #6cdbee; color:#400000;">นาที</th>
            <th class="text-end" style="background-color: #f0b67f; color:#400000;">กระสวย</th>
            <th class="text-end" style="background-color: #f0b67f; color:#400000;">นาที</th>
            <th class="text-end" style="background-color: #fe5f55; color:#ffffff;">อื่นๆ</th>
            <th class="text-end" style="background-color: #fe5f55; color:#ffffff; border-radius: 0 18px 0 0;">นาที</th>
            <th class="text-end">TMP.</th>
            <th class="text-end">%HMD</th>            
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
      <hr>
      <div class="mb-3" id="pagination">
      `;              
      $("#table_data").html(tt);      
      pagination_show(p,result.page_all,per,'showdatatable'); //<<<<<<<< แสดงตัวจัดการหน้าข้อมูล Pagination >>const.js
      $.each(result.data, function (key, entry) {
        i++;
        listdataTable(entry); //<<<<< แสดงรายการทั้งหมด             
      }); 
      s_listdataTable(result.data_sum[0]); //<<<<< แสดงรายการสรุป
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

function convertTZ(date, tzString) {
  return new Date((typeof date === "string" ? new Date(date) : date).toLocaleString("en-US", {timeZone: tzString}));   
}

function listdataTable(ob){  //========= ฟังก์ชั่นเพิ่ม Row ตาราง
    var event_St = new Date(ob.event_stamp);
    var d = new Date();
    d = convertTZ(d,"Asia/Bangkok");
    var timeEvent = timeDifference(d,event_St);
    var tableName = document.getElementById('datatable');
    var prev = tableName.rows.length;           
    var row = tableName.insertRow(prev);
    row.id = "row" + ob.mc;
    row.style.verticalAlign = "top";

    let mcst = "";
    let bg_color="";
    let st1 = "";
    let st2,st3,st4,st5 = "";    
    let set_txt = ` text-decoration:none; `;
    let set_rightclick = '';
    let set_value = '';
    if(u_type == "2"){ //สำหรับ Addmin ผู้ดูแลระบบ จัดการได้ทุกอย่าง
      set_txt = ` text-decoration:none; cursor:pointer; `;
      set_rightclick = ` onmouseover="my_menu(this.id);" `;
      set_value = ` onmouseover="edit_data(this.id);" `;
    }
    switch (ob.mc_status){
        case '0': 
            mcst = "";          
            bg_color = ` style="`+set_txt+` color:#400000; padding: 0 10px 0 10px;" `;    
            break;
        case '1': 
            mcst = "ด้ายบน";
            bg_color = ` style="background-color: #d6d1b1; `+set_txt+` color:#400000; border-radius: 8px 8px; padding: 0 10px 0 10px;" `;       
            st1 = ` font-weight: bold; color: #ff0000; `;
            break;
        case "2": 
            mcst = "ด้ายขาด";
            bg_color = ` style="background-color: #c7efcf; `+set_txt+` color:#400000; border-radius: 8px 8px; padding: 0 10px 0 10px;" `;
            st2 = ` font-weight: bold; color: #ff0000; `;
            break;
        case "3": 
            mcst = "อีแปะ";
            bg_color = ` style="background-color: #6cdbee; `+set_txt+` color:#400000; border-radius: 8px 8px; padding: 0 10px 0 10px;" `;
            st3 = ` font-weight: bold; color: #ff0000; `;
            break;
        case "4": 
            mcst = "กระสวย";
            bg_color = ` style="background-color: #f0b67f; `+set_txt+` color:#400000; border-radius: 8px 8px; padding: 0 10px 0 10px;" `;
            st4 = ` font-weight: bold; color: #ff0000; `;
            break;
        case "5": 
            mcst = "จอด";
            bg_color = ` style="background-color: #fe5f55; `+set_txt+` color:#ffffff; border-radius: 8px 8px; padding: 0 10px 0 10px;" `;
            st5 = ` font-weight: bold; color: #ff0000; `;
            break;
        default:
            mcst = "--";
    }
    let n_col = 18;
    let col = [];
    for(let ii=0; ii<n_col; ii++){
      col[ii] = row.insertCell(ii);
    } 
    col[0].innerHTML = `<div class="text-center"><a id="mc-` + ob.mc + `" `+bg_color+set_rightclick+`>`+ob.mc+`</a></div>`;
    col[1].innerHTML = `<div id="mc_status` + ob.mc + `" class="text-center">`+mcst+timeEvent+`</div>`;
    col[2].innerHTML = `<div id="ord_item` + ob.mc + `" class="text-center">`+ob.ord_item+`</div>`;
    col[3].innerHTML = `<div id="rpm` + ob.mc + `" class="text-end">`+parseFloat(ob.rpm).toFixed(2)+`</div>`;
    col[4].innerHTML = `<div class="text-end" ><a id="meter-` + ob.mc + `" `+set_value+` style="`+set_txt+`" >`+ob.meter+`</a></div>`;
    col[5].innerHTML = `<div class="t_data text-end" ><a id="on_t-` + ob.mc + `" `+set_value+` style="`+set_txt+`" >`+(ob.on_t/600).toFixed(0)+`</a></div>`;
    col[6].innerHTML = `<div class="text-end"><a id="top-` + ob.mc + `" `+set_value+` style="`+set_txt+st1+`">`+ob.top+`</a></div>`;
    col[7].innerHTML = `<div class="t_data text-end" ><a id="top_t-` + ob.mc + `" `+set_value+` style="`+set_txt+`" >`+(ob.top_t/600).toFixed(0)+`</a></div>`;
    col[8].innerHTML = `<div class="text-end"><a id="mid-` + ob.mc + `" `+set_value+` style="`+set_txt+st2+`">`+ob.mid+`</a></div>`;
    col[9].innerHTML = `<div class="t_data text-end" ><a id="mid_t-` + ob.mc + `" `+set_value+` style="`+set_txt+`" >`+(ob.mid_t/600).toFixed(0)+`</a></div>`;
    col[10].innerHTML = `<div class="text-end"><a id="epa-` + ob.mc + `" `+set_value+` style="`+set_txt+st3+`">`+ob.epa+`</a></div>`;
    col[11].innerHTML = `<div class="t_data text-end" ><a id="epa_t-` + ob.mc + `" `+set_value+` style="`+set_txt+`" >`+(ob.epa_t/600).toFixed(0)+`</a></div>`;
    col[12].innerHTML = `<div class="text-end"><a id="bob-` + ob.mc + `" `+set_value+` style="`+set_txt+st4+`">`+ob.bob+`</a></div>`;
    col[13].innerHTML = `<div class="t_data text-end" ><a id="bob_t-` + ob.mc + `" `+set_value+` style="`+set_txt+`" >`+(ob.bob_t/600).toFixed(0)+`</a></div>`;
    col[14].innerHTML = `<div class="text-end"><a id="off-` + ob.mc + `" `+set_value+` style="`+set_txt+st5+`">`+ob.off+`</a></div>`;
    col[15].innerHTML = `<div class="t_data text-end" ><a id="off_t-` + ob.mc + `" `+set_value+` style="`+set_txt+`" >`+(ob.off_t/600).toFixed(0)+`</a></div>`;
    col[16].innerHTML = `<div id="tmp` + ob.mc + `" class="text-end">`+parseFloat(ob.tmp).toFixed(2)+`</div>`;
    col[17].innerHTML = `<div id="tmp` + ob.mc + `" class="text-end">`+parseFloat(ob.hmd).toFixed(2)+`</div>`;
}

function s_listdataTable(ob){  //========= ฟังก์ชั่นเพิ่ม Row ท้ายตาราง  
  var tableName = document.getElementById('datatable');
  var prev = tableName.rows.length;           
  var row = tableName.insertRow(prev);
  row.id = "lastrow";
  row.style.verticalAlign = "top";
  let n_col = 18;
  let col = [];
  for(let i=0; i<n_col; i++){
    col[i] = row.insertCell(i);
  }
  col[0].innerHTML = `<div class="lst text-center">`+ob.n_mc+`</div>`;
  col[1].innerHTML = `<div class="lst text-start">เครื่อง</div>`;
  col[2].innerHTML = `<div class="lst text-start">&nbsp;</div>`;
  col[3].innerHTML = `<div class="lst text-end">`+parseFloat(ob.a_rpm).toFixed(2)+`</div>`;
  col[4].innerHTML = `<div class="lst text-end">`+ob.s_meter+`</div>`;
  col[5].innerHTML = `<div class="lst text-end" style="color:#2059df;">`+(ob.s_ont/600).toFixed(0)+`</div>`;
  col[6].innerHTML = `<div class="lst text-end">`+ob.s_top+`</div>`;
  col[7].innerHTML = `<div  class="lst text-end" style="color:#2059df;">`+(ob.s_topt/600).toFixed(0)+`</div>`;
  col[8].innerHTML = `<div class="lst text-end">`+ob.s_mid+`</div>`;
  col[9].innerHTML = `<div class="lst text-end" style="color:#2059df;">`+(ob.s_midt/600).toFixed(0)+`</div>`;
  col[10].innerHTML = `<div class="lst text-end">`+ob.s_epa+`</div>`;
  col[11].innerHTML = `<div class="lst text-end" style="color:#2059df;">`+(ob.s_epat/600).toFixed(0)+`</div>`;
  col[12].innerHTML = `<div class="lst text-end">`+ob.s_bob+`</div>`;
  col[13].innerHTML = `<div class="lst text-end" style="color:#2059df;">`+(ob.s_bobt/600).toFixed(0)+`</div>`;
  col[14].innerHTML = `<div class="lst text-end">`+ob.s_off+`</div>`;
  col[15].innerHTML = `<div class="lst text-end" style="color:#2059df;">`+(ob.s_offt/600).toFixed(0)+`</div>`;
  col[16].innerHTML = `<div class="lst text-end">`+parseFloat(ob.a_tmp).toFixed(2)+`</div>`;
  col[17].innerHTML = `<div class="lst text-end">`+parseFloat(ob.a_hmd).toFixed(2)+`</div>`;
}

//=========================== Even เกี่ยวกับ รายการ ======================================
$(document).on('click',"#bt_search_data",function () {  //ค้นหารายการ
  showdatatable(rowperpage,page_sel);          
});

function listdata_interval(){
    showdatatable(rowperpage,page_sel);
}

function timeDifference(laterdate,earlierdate) {
  // คำนวณความแตกต่างของวันที่
  var difference = laterdate.getTime() - earlierdate.getTime();
  var sec_diff = difference;
  // แปลงเป็นวัน ชม. นาที วินาที
  var daysDifference = Math.floor(difference/1000/60/60/24);
  difference -= daysDifference*1000*60*60*24
  var hoursDifference = Math.floor(difference/1000/60/60);
  difference -= hoursDifference*1000*60*60
  var minutesDifference = Math.floor(difference/1000/60);
  difference -= minutesDifference*1000*60
  //var secondsDifference = Math.floor(difference/1000);
  var min0 = (minutesDifference <10)?"0":"";
  var hour0 = (hoursDifference < 10)?"0":"";
  var day0 = (daysDifference > 0)?daysDifference+"d":"";
  if(Math.floor(sec_diff/1000) < 59){
    return ("");
  }else{
    return (" "+day0+hour0+hoursDifference+":"+min0+minutesDifference);
  }

}

function my_menu(id){ // เมนูคลิ๊กขวาทำงาน
  var ele_sel = document.getElementById(id);
  ele_sel.addEventListener("contextmenu",function(event){
      event.preventDefault();
      var ctxMenu = document.getElementById("ctxMenu");
      ctxMenu.style.display = "block";
      ctxMenu.style.left = (event.pageX + 10)+"px";
      ctxMenu.style.top = (event.pageY + 5)+"px";
      $("#setzero_acc").val(this.text);
      $("#setzero_acc").text("Set "+this.text+" to zero");
      $("#shift_set").val(this.text);
      $("#shift_set").text("Set "+this.text+" to now shift");
      $("#ord_set").val(this.text);
      $("#ord_set").text("Set "+this.text+" to now Order");
      close_edit_data();
  },false);
  ele_sel.addEventListener("click",function(event){
    close_ctxmenu();
    close_edit_data();
  },false);
}

function close_ctxmenu(){
  var ctxMenu = document.getElementById("ctxMenu");
          ctxMenu.style.display = "";
          ctxMenu.style.left = "";
          ctxMenu.style.top = "";                    
}

function edit_data(id){ // เมนูคลิ๊กขวาเพื่อแก้ไขข้อมูล
  var ele_sel = document.getElementById(id);
  ele_sel.addEventListener("contextmenu",function(event){
      event.preventDefault();
      var ctx = document.getElementById("edit_data");
      ctx.style.display = "block";
      var w_table =document.getElementById("table_data").clientWidth;
      var xx = 0;
      if((w_table - event.pageX)<250){ xx = -255;}
      ctx.style.left = (event.pageX + 5+xx)+"px";
      ctx.style.top = (event.pageY + 5)+"px";
      close_ctxmenu();
      $("#ed_data").val(this.text);
      var str_arr = id.split("-");
      $("#head_data").val(str_arr[0]);
      $("#mc_data").val(str_arr[1]);
  },false);
  ele_sel.addEventListener("click",function(event){
    close_ctxmenu();
    close_edit_data();
  },false);
}

function close_edit_data(){
  var ctx = document.getElementById("edit_data");
          ctx.style.display = "";
          ctx.style.left = "";
          ctx.style.top = "";                    
}

$(document).on("click", "#ctxMenu, #table_data, #fmsearch_data_main, #cancel_edit_bt", function () { //ปิดหน้าเมนูคลิ๊กขวา
  close_ctxmenu();
  close_edit_data();
});
  

$(document).on("click", "#setzero_acc", function () { // กำหนดให้ค่เป็น ศูนย์ เมื่อคลิ๊ก
    var my_val = this.value;
    const swalWithBootstrapButtons = Swal.mixin({
      customClass: {
        confirmButton: 'btn btn-primary me-3',
        cancelButton: 'btn btn-danger ms-3'
      },
      buttonsStyling: false
    })
    swalWithBootstrapButtons.fire({
      title: 'โปรดยืนยัน',
      text: "ต้องการกำหนดค่า "+my_val+" ให้เป็นศูนย์หรือไม่?",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: ' ตกลง ',
      cancelButtonText: ' ไม่ ',
      reverseButtons: false
    }).then((result) => {
      if (result.isConfirmed) {
        var jwt = getCookie("jwt");
        var my_obj={};
        my_obj.jwt = jwt;
        my_obj.acc = "setzero";
        my_obj.mc = my_val;
        var my_data = JSON.stringify(my_obj);
        $.ajax({
            url: "api/admin_monitor_acc.php",
            type: "POST",
            contentType: "application/json",
            data: my_data,
            success: function(result) {
                Signed("success", " Set "+my_val+" to zero success. ");
            },
            error: function(xhr, resp, text) {
                if (xhr.responseJSON.message == "Unable to set zero.") {
                    Signed("error", " กำหนดค่าเป็นศูนย์ไม่สำเร็จ ");            
                } else if (xhr.responseJSON.message == "Unable to access data.") {
                    Signed("warning", "ปฏิเสธการเข้าใช้ โปรดลองใหม่!");
                }else{
                  showLoginPage();
                  Signed("warning", "ปฏิเสธการเข้าใช้ โปรดเข้าสู่ระบบก่อน!");
                }
            },
        });    
          
      } else if ( result.dismiss === Swal.DismissReason.cancel ){
          swalWithBootstrapButtons.fire(
              'ยกเลิก',
              'ข้อมูลของคุณยังไม่ถูกเปลี่ยนแปลง :)',
              'error'
          )
      }
    })

});

$(document).on("click", "#Allsetzero_acc", function () { // กำหนดให้ค่าเป็น ศูนย์ ทั้งหมดเมื่อคลิ๊ก
  const swalWithBootstrapButtons = Swal.mixin({
    customClass: {
      confirmButton: 'btn btn-primary me-3',
      cancelButton: 'btn btn-danger ms-3'
    },
    buttonsStyling: false
  })
  swalWithBootstrapButtons.fire({
    title: 'โปรดยืนยัน',
    text: "ต้องการกำหนดค่าทุกเครื่อง ให้เป็นศูนย์หรือไม่?",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: ' ตกลง ',
    cancelButtonText: ' ไม่ ',
    reverseButtons: false
  }).then((result) => {
    if (result.isConfirmed) {
      var jwt = getCookie("jwt");
      var my_obj={};
      my_obj.jwt = jwt;
      my_obj.acc = "setzero_all";
      var my_data = JSON.stringify(my_obj);
      $.ajax({
          url: "api/admin_monitor_acc.php",
          type: "POST",
          contentType: "application/json",
          data: my_data,
          success: function(result) {
              Signed("success", " Set all to zero success. ");
          },
          error: function(xhr, resp, text) {
              if (xhr.responseJSON.message == "Unable to set zero all.") {
                  Signed("error", " กำหนดค่าเป็นศูนย์ไม่สำเร็จ ");            
              } else if (xhr.responseJSON.message == "Unable to access data.") {
                  Signed("warning", "ปฏิเสธการเข้าใช้ โปรดลองใหม่!");
              }else{
                showLoginPage();
                Signed("warning", "ปฏิเสธการเข้าใช้ โปรดเข้าสู่ระบบก่อน!");
              }
          },
      });    
        
    } else if ( result.dismiss === Swal.DismissReason.cancel ){
        swalWithBootstrapButtons.fire(
            'ยกเลิก',
            'ข้อมูลทั้งหมดของคุณยังไม่ถูกเปลี่ยนเปลง :)',
            'error'
        )
    }
  })

});

$(document).on("click", "#shift_set", function () { // กำหนดกะเป็นปัจจุบัน
  var my_val = this.value;
  const swalWithBootstrapButtons = Swal.mixin({
    customClass: {
      confirmButton: 'btn btn-primary me-3',
      cancelButton: 'btn btn-danger ms-3'
    },
    buttonsStyling: false
  })
  swalWithBootstrapButtons.fire({
    title: 'โปรดยืนยัน',
    text: "ต้องการกำหนดค่ากะ "+my_val+" ให้เป็นปัจจุบันหรือไม่?",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: ' ตกลง ',
    cancelButtonText: ' ไม่ ',
    reverseButtons: false
  }).then((result) => {
    if (result.isConfirmed) {
      var jwt = getCookie("jwt");
      var my_obj={};
      my_obj.jwt = jwt;
      my_obj.mc = my_val;
      my_obj.acc = "set_shift";
      var my_data = JSON.stringify(my_obj);
      $.ajax({
          url: "api/admin_monitor_acc.php",
          type: "POST",
          contentType: "application/json",
          data: my_data,
          success: function(result) {
              Signed("success", "Set shift to now success.");
          },
          error: function(xhr, resp, text) {
              if (xhr.responseJSON.message == "Unable to set shift.") {
                  Signed("error", " กำหนดค่ากะให้เป็นปัจจุบันไม่สำเร็จ ");            
              } else if (xhr.responseJSON.message == "Unable to access data.") {
                  Signed("warning", "ปฏิเสธการเข้าใช้ โปรดลองใหม่!");
              }else{
                showLoginPage();
                Signed("warning", "ปฏิเสธการเข้าใช้ โปรดเข้าสู่ระบบก่อน!");
              }
          },
      });    
        
    } else if ( result.dismiss === Swal.DismissReason.cancel ){
        swalWithBootstrapButtons.fire(
            'ยกเลิก',
            'ข้อมูลของคุณยังไม่เปลี่ยนแปลง:)',
            'error'
        )
    }
  })

});

$(document).on("click", "#shift_set_all", function () { // กำหนดกะทุกเครื่องให้เป็นปัจจุบัน
  const swalWithBootstrapButtons = Swal.mixin({
    customClass: {
      confirmButton: 'btn btn-primary me-3',
      cancelButton: 'btn btn-danger ms-3'
    },
    buttonsStyling: false
  })
  swalWithBootstrapButtons.fire({
    title: 'โปรดยืนยัน',
    text: "ต้องการกำหนดค่ากะ ทุกเครื่อง ให้เป็นปัจจุบันหรือไม่?",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: ' ตกลง ',
    cancelButtonText: ' ไม่ ',
    reverseButtons: false
  }).then((result) => {
    if (result.isConfirmed) {
      var jwt = getCookie("jwt");
      var my_obj={};
      my_obj.jwt = jwt;
      my_obj.acc = "set_shift_all";
      var my_data = JSON.stringify(my_obj);
      $.ajax({
          url: "api/admin_monitor_acc.php",
          type: "POST",
          contentType: "application/json",
          data: my_data,
          success: function(result) {
              Signed("success", "Set all shift to now success.");
          },
          error: function(xhr, resp, text) {
              if (xhr.responseJSON.message == "Unable to set shift all.") {
                  Signed("error", " กำหนดค่ากะให้เป็นปัจจุบันไม่สำเร็จ ");            
              } else if (xhr.responseJSON.message == "Unable to access data.") {
                  Signed("warning", "ปฏิเสธการเข้าใช้ โปรดลองใหม่!");
              }else{
                showLoginPage();
                Signed("warning", "ปฏิเสธการเข้าใช้ โปรดเข้าสู่ระบบก่อน!");
              }
          },
      });    
        
    } else if ( result.dismiss === Swal.DismissReason.cancel ){
        swalWithBootstrapButtons.fire(
            'ยกเลิก',
            'ข้อมูลของคุณยังไม่เปลี่ยนแปลง:)',
            'error'
        )
    }
  })

});

$(document).on("click", "#ord_set", function () { // กำหนดออร์เดอร์ให้เครื่องทอ
  const my_val = this.value;
  const swalWithBootstrapButtons = Swal.mixin({
    customClass: {
      confirmButton: 'btn btn-primary me-3',
      cancelButton: 'btn btn-danger ms-3'
    },
    buttonsStyling: false
  })
  swalWithBootstrapButtons.fire({
    title: 'โปรดยืนยัน',
    text: "ต้องการกำหนดออร์เดอร์ให้ "+my_val+" หรือไม่?",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: ' ตกลง ',
    cancelButtonText: ' ไม่ ',
    reverseButtons: false
  }).then((result) => {
    if (result.isConfirmed) {
      var jwt = getCookie("jwt");
      var my_obj={};
      my_obj.jwt = jwt;
      my_obj.mc = my_val;
      my_obj.acc = "set_ord";
      var my_data = JSON.stringify(my_obj);
      $.ajax({
          url: "api/admin_monitor_acc.php",
          type: "POST",
          contentType: "application/json",
          data: my_data,
          success: function(result) {
              Signed("success", "Set Order to now success.");
          },
          error: function(xhr, resp, text) {
              if (xhr.responseJSON.message == "Unable to set Order.") {
                  Signed("error", " กำหนดค่าออร์เดอร์ให้เป็นปัจจุบันไม่สำเร็จ ");            
              } else if (xhr.responseJSON.message == "Unable to access data.") {
                  Signed("warning", "ปฏิเสธการเข้าใช้ โปรดลองใหม่!");
              }else{
                showLoginPage();
                Signed("warning", "ปฏิเสธการเข้าใช้ โปรดเข้าสู่ระบบก่อน!");
              }
          }
      });    
        
    } else if ( result.dismiss === Swal.DismissReason.cancel ){
        swalWithBootstrapButtons.fire(
            'ยกเลิก',
            'ข้อมูลของคุณยังไม่เปลี่ยนแปลง:)',
            'error'
        )
    }
  })

});

$(document).on("click", "#mc_set", function () { // ตั้งค่าเครื่องทอใหม่ทั้งหมด
  const swalWithBootstrapButtons = Swal.mixin({
    customClass: {
      confirmButton: 'btn btn-primary me-3',
      cancelButton: 'btn btn-danger ms-3'
    },
    buttonsStyling: false
  })
  swalWithBootstrapButtons.fire({
    title: 'โปรดยืนยัน',
    text: "ต้องการ เริ่มต้นรายการเครื่องทอใหม่ หรือไม่?",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: ' ตกลง ',
    cancelButtonText: ' ไม่ ',
    reverseButtons: false
  }).then((result) => {
    if (result.isConfirmed) {
      var jwt = getCookie("jwt");
      var my_obj={};
      my_obj.jwt = jwt;
      my_obj.acc = "set_mc";
      var my_data = JSON.stringify(my_obj);
      $.ajax({
          url: "api/admin_monitor_acc.php",
          type: "POST",
          contentType: "application/json",
          data: my_data,
          success: function(result) {
              Signed("success", "กำหนดรายการเครื่องทอเริ่มต้น สำเร็จ");
          },
          error: function(xhr, resp, text) {
              if (xhr.responseJSON.message == "Unable to set mc all.") {
                  Signed("error", " กำหนดรายการเครื่องทอเริ่มต้น ไม่สำเร็จ ");            
              } else if (xhr.responseJSON.message == "Unable to access data.") {
                  Signed("warning", "ปฏิเสธการเข้าใช้ โปรดลองใหม่!");
              }else{
                showLoginPage();
                Signed("warning", "ปฏิเสธการเข้าใช้ โปรดเข้าสู่ระบบก่อน!");
              }
          },
      });    
        
    } else if ( result.dismiss === Swal.DismissReason.cancel ){
        swalWithBootstrapButtons.fire(
            'ยกเลิก',
            'ข้อมูลของคุณยังไม่เปลี่ยนแปลง:)',
            'error'
        )
    }
  })

});

$(document).on("submit", "#fmedit_data", function () { //===== ทำการแก้ไขข้อมูล
  var update_data_form = $(this);
  var jwt = getCookie("jwt");
  var update_data_form_obj = update_data_form.serializeObject();
  update_data_form_obj.jwt = jwt;
  update_data_form_obj.acc = "update_data";
  var form_data = JSON.stringify(update_data_form_obj);  
    $.ajax({
      url: "api/admin_monitor_acc.php",
      type: "POST",
      contentType: "application/json",
      data: form_data,
      success: function (result) {
        Signed("success"," ปรับปรุงข้อมูลสำเร็จ ");   
      },
      error: function (xhr, resp, text) {
        if(xhr.responseJSON.message == "Unable to set data.") {
          Signed("error"," ปรับปรุงข้อมูลไม่สำเร็จ ");         
        }else if(xhr.responseJSON.message == "Access denied.") {
          showLoginPage();
          Signed("warning","ปฏิเสธการเข้าใช้ โปรดเข้าสู่ระบบก่อน");
        }
      },
    });
    close_edit_data();
  return false;
});

