<!doctype html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>รายงานรอบเครื่อง</title>

  <link rel="shortcut icon" href="image/Report.ico">
  <link rel="stylesheet" type="text/css" href="css/report.css">
  <link rel="stylesheet" type="text/css" href="css/printMe.css" media="print">
  <script src="node_modules/jquery/dist/jquery.min.js"></script>
  <script src="js/const.js"></script>
  <script>
    var all_data =0;
    var dataPerpage = 45; //=== จำนวนบรรทัดต่อหน้า
    var n_page = 0;
    
  </script>

</head>

<body>
  <input id="date_fm" type="hidden" value="<?= isset($_POST['datefm'])?$_POST['datefm']:""; ?>"/>
  <input id="date_to" type="hidden" value="<?= isset($_POST['dateto'])?$_POST['dateto']:""; ?>"/>
  <input id="dp_select" type="hidden" value="<?= isset($_POST['dpsel'])?$_POST['dpsel']:"--"; ?>"/>
  <input id="depart" type="hidden" value="<?= isset($_POST['depart'])?$_POST['depart']:""; ?>"/>
  <input id="search" type="hidden" value="<?= isset($_POST['search'])?$_POST['search']:"--"; ?>"/>

  <div class="my_table" align="center"><br><i class="fas fa-spinner fa-pulse fa-2x"></i>&nbsp;&nbsp; กำลังโหลดข้อมูล.....</div> 

<script> 
  window.onload = function(){
    var datefm = to_Ymd($("#date_fm").val());
    var dateto = to_Ymd($("#date_to").val());
    var dpsel = $("#dp_select").val();
    var depart = $("#depart").val();
    var sh = $("#search").val();

    var jwt = getCookie("jwt");
    var x = 1; //=== หน้า
    var y = 0; //=== บรรทัด
    var yy = 0; //=== เลขลำดับ
    $.ajax({
      type: "POST", 
      url: "api/data_rpm_rep.php",
      data: {search:sh,datefm:datefm,dateto:dateto,depart:depart,jwt:jwt},
      success: function(result){
        var str=`
        <table width="750" border="0" align="center" cellpadding="0" cellspacing="0" id="data_title">
          <tr>
            <td align="center" width="120"><button id="printPageButton" onClick="printpage();" style="width:60px; height:25px;">Print</button></td>
            <td align="center" class="headTitle" id="head_rep">รายงาน (------)</td>
            <td align="center" width="120"><button id="closePageButton" onClick="close_window(); return false;" style="width:60px; height:25px;">Close</button></td>
          </tr>
          <tr>
            <td colspan="3" align="center" class="headTitle2" id="date_rep">วันที่ : --- &nbsp;&nbsp;ถึง&nbsp;&nbsp; ---</td>
          </tr>
          <tr>
            <td colspan="3" align="center" class="headTitle2" id="head2_rep">หน่วยงาน :----&nbsp;&nbsp;&nbsp;&nbsp;คำค้นหา : ( &nbsp;---&nbsp; )</td>
          </tr>       
        </table>
        <br>
        `;              
        $(".my_table").html(str);      

        $("#head_rep").html("ประสิทธิภาพเครื่องจักร (รอบ)");
        $("#date_rep").html("วันที่&nbsp;&nbsp;"+to_dmY(datefm)+"&nbsp;&nbsp;ถึง&nbsp;&nbsp;"+to_dmY(dateto));
        var dpseltxt = (dpsel!="")?"หน่วยงาน :&nbsp;"+dpsel+"&nbsp;&nbsp;&nbsp;&nbsp;":"";
        $("#head2_rep").html(dpseltxt+"คำค้นหา :(&nbsp;"+sh+"&nbsp;)");

         all_data = result.data.length; //=== จำนวนข้อมูลทั้งหมด
         dataPerpage = 50; //=== จำนวนข้อมูลต่อหน้า
         n_page = Math.ceil(all_data/dataPerpage); //=== จำนวนหน้าทั้งหมด
         //console.log(result.data.length);
         //console.log(result.data);
       $.each(result.data, function (key, entry) {          
          y++;
          yy++;
          if(x==1 && y==1){ //=== หน้าแรก สร้างหัวตาราง
            let data_t = document.createElement('table'); //=== สร้างตารางใหม่
            data_t.id = 'data_table'+x;
            data_t.setAttribute('width','750');
            data_t.setAttribute('border','0');
            data_t.setAttribute('align','center'); 
            data_t.setAttribute('cellpadding','0');
            data_t.setAttribute('cellspacing','0'); 
            let parentDiv = document.getElementById('data_title').parentNode
            parentDiv.appendChild(data_t);       //=== เพิ่มตารางที่สร้างใหม่เข้าไป
            show_hTable(x);  //==== แสดงหัวตาราง
          }
          yy = (entry.key_p != 'data')?yy-1:yy; //=== กำหนดลำดับที่แสดง
          show_dataTable(x,yy,entry); //=== แสดงข้อมูลในตาราง
          if(x==n_page && y == all_data){ //=== หน้าสุดท้าย แสดงท้ายตาราง
            show_footrep();
          }          
          if(y%dataPerpage == 0){ //=== ทุกๆหน้า สร้างหัวตาราง
            x++;
            if(x<=n_page){
              let data_t = document.createElement('table'); //=== สร้างตารางใหม่
              data_t.id = 'data_table'+x;
              data_t.setAttribute('width','750');
              data_t.setAttribute('border','0');
              data_t.setAttribute('align','center'); 
              data_t.setAttribute('cellpadding','0');
              data_t.setAttribute('cellspacing','0'); 
              let parentDiv = document.getElementById('data_table'+(x-1)).parentNode
              let page_breake = document.createElement('div');
              page_breake.setAttribute('style','break-after:page');
              parentDiv.appendChild(page_breake); //=== ขึ้นหน้าใหม่
              parentDiv.appendChild(data_t);       //=== เพิ่มตารางที่สร้างใหม่เข้าไป
              show_hTable(x);  //==== แสดงหัวตาราง
            }
          }
            
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

  function show_hTable(x){  //========= ฟังก์ชั่นเพิ่ม หัวตาราง    
      var tableName = document.getElementById('data_table'+x);
      var prev = tableName.rows.length;           
      var row = tableName.insertRow(prev);
      row.style.verticalAlign = "top";    
      row.innerHTML = `
        <th class="text-center tl_lite">&nbsp;</th> 
        <th class="text-center tl_lite">&nbsp;</th>
        <th class="text-end tl_lite">&nbsp;</th>
        <th class="text-end tlb_lite" colspan="4">กะที่ 1</th>
        <th class="text-end tlb_lite" colspan="4">กะที่ 2</th>
        <th class="text-end tlb_lite" colspan="4">กะที่ 3</th>
        <th class="text-end tlbr_lite" colspan="4" style="background-color: #d7d7d7;">รวมทั้งหมด</th>      
      `;

      row = tableName.insertRow(prev+1);    
      row.innerHTML =`    
        <th class="text-center l_lite">ลำดับ</th> 
        <th class="text-center l_lite">เครื่อง</th>
        <th class="text-end l_lite">เป้า</th>
        <th class="text-end lb_lite" colspan="2">เดิน</th>
        <th class="text-end lb_lite" colspan="2" style="background-color: #dddddd;">เฉลี่ย</th>
        <th class="text-end lb_lite" colspan="2">เดิน</th>
        <th class="text-end lb_lite" colspan="2"style="background-color: #dddddd;">เฉลี่ย</th>
        <th class="text-end lb_lite" colspan="2">เดิน</th>
        <th class="text-end lb_lite" colspan="2"style="background-color: #dddddd;">เฉลี่ย</th>
        <th class="text-end lb_lite" colspan="2">เดิน</th>
        <th class="text-end lbr_lite" colspan="2"style="background-color: #dddddd;">เฉลี่ย</th>        
      `;
      row = tableName.insertRow(prev+2);    
      row.innerHTML =`    
        <th class="text-center lb_bold">&nbsp;</th> 
        <th class="text-center lb_bold">&nbsp;</th>
        <th class="text-end lb_bold">&nbsp;</th>
        <th class="text-end lb_bold">Rpm</th>      
        <th class="text-end lb_bold">%</th>    
        <th class="text-end lb_bold">Rpm</th>      
        <th class="text-end lb_bold">%</th>   
        <th class="text-end lb_bold">Rpm</th>      
        <th class="text-end lb_bold">%</th>   
        <th class="text-end lb_bold">Rpm</th>      
        <th class="text-end lb_bold">%</th>   
        <th class="text-end lb_bold">Rpm</th>      
        <th class="text-end lb_bold">%</th>   
        <th class="text-end lb_bold">Rpm</th>      
        <th class="text-end lb_bold">%</th>   
        <th class="text-end lb_bold">Rpm</th>      
        <th class="text-end lb_bold">%</th>   
        <th class="text-end lb_bold">Rpm</th>      
        <th class="text-end lbr_bold">%</th>          
      `;
  }


  function show_dataTable(x,yy,ob){  //========= ฟังก์ชั่นเพิ่ม Row ตาราง    
    var tableName = document.getElementById('data_table'+x);
    var prev = tableName.rows.length;           
    var row = tableName.insertRow(prev);    
    row.style.verticalAlign = "center";
    let n_col = 19;
    let col = [];
    let nn = 0;
    let grouprow = '';
    let line_t = '';
    if(ob.key_p == 'gr2'){
        row.setAttribute('style','margin-bottom:5px;')        
      }
    if(ob.key_p == 'gr1'){
      n_col--;
      grouprow = `<div class="tlb_lite" align="center">รวมกลุ่ม ${ob.group_name}</div>`;
    }else if(ob.key_p == 'gr2'){
      n_col--;
      grouprow = `<div class="dtlb_lite" align="center">รวม ${ob.bd_name}</div>`;
      
    }else if(ob.key_p == 'all'){
      n_col--;
      grouprow = `<div class="ttlb_lite" align="center" style="background-color: #d7d7d7;">รวมทั้งหมด</div>`;
    }    
    
    for(let i=0; i<n_col; i++){
      col[i] = row.insertCell(i);
    }
    if(ob.key_p == 'data'){
      line_t = "";
      col[0].innerHTML = `<div class="lb_lite" align="center">${yy}</div>`;
      col[1].innerHTML = `<div class="lb_lite" align="center">${ob.mc}</div>`;
      nn = 1;      
    }else{      
      line_t = (ob.key_p == 'all')?"tt":"t";
      if(ob.key_p == 'gr1'){
        line_t = "t";
      }else if(ob.key_p == 'gr2'){
        line_t = "dt";
      }else if(ob.key_p == 'all'){
        line_t = "tt";
      }
      col[nn].setAttribute('colspan','2');
      col[nn].innerHTML = grouprow;     

    }   
let meter_all = (ob.meter1*1)+(ob.meter2*1)+(ob.meter3*1);
let on_t_all = (ob.on_t1*1)+(ob.on_t2*1)+(ob.on_t3*1);
let t_all = (ob.all_t1*1)+(ob.all_t2*1)+(ob.all_t3*1);
    col[nn+1].innerHTML = `<div class="`+line_t+`lb_lite" align="right">`+(ob.mc_rpm*1).toFixed(2)+`</div>`;
    col[nn+2].innerHTML = `<div class="`+line_t+`lb_lite" align="right">`+rpm(ob.meter1,ob.on_t1)+`</div>`;
    col[nn+3].innerHTML = `<div class="`+line_t+`lb_lite" align="right">`+per(ob.mc_rpm,ob.meter1,ob.on_t1)+`</div>`;
    col[nn+4].innerHTML = `<div class="`+line_t+`lb_lite" align="right">`+rpm(ob.meter1,ob.all_t1)+`</div>`;
    col[nn+5].innerHTML = `<div class="`+line_t+`lb_lite" align="right">`+per(ob.mc_rpm,ob.meter1,ob.all_t1)+`</div>`;
    col[nn+6].innerHTML = `<div class="`+line_t+`lb_lite" align="right">`+rpm(ob.meter2,ob.on_t2)+`</div>`;
    col[nn+7].innerHTML = `<div class="`+line_t+`lb_lite" align="right">`+per(ob.mc_rpm,ob.meter2,ob.on_t2)+`</div>`;
    col[nn+8].innerHTML = `<div class="`+line_t+`lb_lite" align="right">`+rpm(ob.meter2,ob.all_t2)+`</div>`;
    col[nn+9].innerHTML = `<div class="`+line_t+`lb_lite" align="right">`+per(ob.mc_rpm,ob.meter2,ob.all_t2)+`</div>`;
    col[nn+10].innerHTML = `<div class="`+line_t+`lb_lite" align="right">`+rpm(ob.meter3,ob.on_t3)+`</div>`;
    col[nn+11].innerHTML = `<div class="`+line_t+`lb_lite" align="right">`+per(ob.mc_rpm,ob.meter3,ob.on_t3)+`</div>`;
    col[nn+12].innerHTML = `<div class="`+line_t+`lb_lite" align="right">`+rpm(ob.meter3,ob.all_t3)+`</div>`;
    col[nn+13].innerHTML = `<div class="`+line_t+`lb_lite" align="right">`+per(ob.mc_rpm,ob.meter3,ob.all_t3)+`</div>`;
    col[nn+14].innerHTML = `<div class="`+line_t+`lb_lite" align="right">`+rpm(meter_all,on_t_all)+`</div>`;
    col[nn+15].innerHTML = `<div class="`+line_t+`lb_lite" align="right">`+per(ob.mc_rpm,meter_all,on_t_all)+`</div>`;
    col[nn+16].innerHTML = `<div class="`+line_t+`lb_lite" align="right">`+rpm(meter_all,t_all)+`</div>`;
    col[nn+17].innerHTML = `<div class="`+line_t+`lbr_lite" align="right">`+per(ob.mc_rpm,meter_all,t_all)+`</div>`;
    
}

function rpm(me,tm){
  let sol = (((tm*1)>0)?(me*1):0)/(((tm*1)>0)?(tm*1):1);
  return sol.toFixed(2);
}

function per(tg,me,tm){
  let sol = rpm(me,tm)*100/(((tg*1)>0)?(tg*1):1);
  return sol.toFixed(2);
}



function show_footrep(){
  let parentDiv = document.getElementById('data_title').parentNode //=== สร้างตารางท้ายรายงาน
  let data_f = document.createElement('table');
  data_f.id = 'foot_table';
  data_f.setAttribute('width','750');
  data_f.setAttribute('border','0');
  data_f.setAttribute('align','center'); 
  data_f.setAttribute('cellpadding','0');
  data_f.setAttribute('cellspacing','0'); 
  data_f.innerHTML = ` 
      <tr>
        <td colspan="6" align="left">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="6" align="left">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="6" align="left">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="2" align="center" class="FooterR">ผู้จัดทำ...............................</td>
        <td colspan="2" align="center" class="FooterR">ผู้รัตรวจสอบ...........................</td>
        <td colspan="2" align="center" class="FooterR">ผู้อนุมัติ...............................</td>
      </tr>
      <tr>
        <td colspan="6" align="left">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="2" align="center" class="FooterR">( &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; )</td>
        <td colspan="2" align="center" class="FooterR">( &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; )</td>
        <td colspan="2" align="center" class="FooterR">( &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; )</td>
      </tr>
      <tr>
        <td colspan="6" align="left">&nbsp;</td>
      </tr>`;
  parentDiv.appendChild(data_f);
}



function printpage() {
  var printButton = document.getElementById("printPageButton");
  var closeButton = document.getElementById("closePageButton");
  printButton.style.visibility = 'hidden';
  closeButton.style.visibility = 'hidden';
  window.print()
  printButton.style.visibility = 'visible';
  closeButton.style.visibility = 'visible';
}

function close_window() {
  //if (confirm("Close Report?")) {
    close();
  //}
}

</script>
  </body>

  </html>
