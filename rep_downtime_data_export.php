﻿<!doctype html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>รายงานเวลาสูญเสีย</title>

  <link rel="shortcut icon" href="image/Report.ico">
  <link rel="stylesheet" type="text/css" href="css/report.css">
  <link rel="stylesheet" type="text/css" href="css/printMe.css" media="print">
  <!-- Fonts awesome icons -->
  <link rel="stylesheet" href="css/all.min.css" />
  <script src="node_modules/jquery/dist/jquery.min.js"></script>
  <script src="js/const.js"></script>
  <!-- Export to Excel 
  <script type="text/javascript" src="https://unpkg.com/xlsx@0.15.1/dist/xlsx.full.min.js"></script>  -->
  <script lang="javascript" src="node_modules/xlsx/dist/xlsx.full.min.js"></script>

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

    var h_str = "ประสิทธิภาพเครื่องจักร (เวลาสูญเสีย)"
    var h_str1 = "วันที่&nbsp;&nbsp;"+to_dmY(datefm)+"&nbsp;&nbsp;ถึง&nbsp;&nbsp;"+to_dmY(dateto);
    var h_str2 = "หน่วยงาน :&nbsp;"+dpsel;
    var h_str3 = "&nbsp;&nbsp;กรองข้อมูล :(&nbsp;"+sh+"&nbsp;)";

    var jwt = getCookie("jwt");
    var y = 0; //=== ลำดับบรรทัด
    var yy = 0; //=== เลขลำดับ
    $.ajax({
      type: "POST", 
      url: "api/data_downtime_rep.php",
      data: {search:sh,datefm:datefm,dateto:dateto,depart:depart,jwt:jwt},
      success: function(result){
        var str=`
        <table width="1050" border="0" align="center" cellpadding="0" cellspacing="0" id="data_title">
          <tr>
            <td align="center" width="120"><button id="ExportButton" title="ส่งออก Excel" onClick="ExportToExcel('xlsx');" style="width:40px; height:40px; margin-top:20px;"><i class="far fa-file-excel fa-2x"></i></button></td>
            <td align="center" width="120"><button id="closePageButton" title="ปิดหน้ารายงาน" onClick="close_window(); return false;" style="width:40px; height:40px; margin-top:20px;"><i class="fas fa-reply fa-2x"></i></button></td>
          </tr>
            
        </table>
        <br>
        `;              
        $(".my_table").html(str);    

         //console.log(result.data.length);
         //console.log(result.data);
       $.each(result.data, function (key, entry) {          
          y++;
          yy++;
          if(y==1){ //=== หน้าแรก สร้างหัวตาราง
            let data_t = document.createElement('table'); //=== สร้างตารางใหม่
            data_t.id = 'data_table';
            data_t.setAttribute('width','1050');
            data_t.setAttribute('border','0');
            data_t.setAttribute('align','center'); 
            data_t.setAttribute('cellpadding','0');
            data_t.setAttribute('cellspacing','0'); 
            let parentDiv = document.getElementById('data_title').parentNode
            parentDiv.appendChild(data_t);       //=== เพิ่มตารางที่สร้างใหม่เข้าไป
            show_head(h_str,h_str1,h_str2,h_str3);
            show_hTable();  //==== แสดงหัวตาราง
          }
          if(entry.key_p == 'data'){
            show_dataTable(yy,entry); //=== แสดงข้อมูลในตาราง
          }else{
              yy--; 
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

  function show_head(str0,str1,str2,str3){  //========= ฟังก์ชั่นเพิ่ม หัวรายงานใน Eport to excel   
      var tableName = document.getElementById('data_table');
      var prev = tableName.rows.length; 
      var row1 = tableName.insertRow(prev);
      var row2 = tableName.insertRow(prev+1);
      var row3 = tableName.insertRow(prev+2);
      row1.style.verticalAlign = "top";
      row2.style.verticalAlign = "top"; 
      row3.style.verticalAlign = "top";
      row1.innerHTML = `<th align="center" class="headTitle" colspan="30">${str0}</th>`;
      row2.innerHTML = `<th colspan="30" align="center" class="headTitle2">${str1}</th>`;
      row3.innerHTML = `<th colspan="30" align="center" class="headTitle2">${str2} ${str3}</th>`;      
  }

  function show_hTable(){  //========= ฟังก์ชั่นเพิ่ม หัวตาราง    
      var tableName = document.getElementById('data_table');
      var prev = tableName.rows.length;           
      var row = tableName.insertRow(prev);
      row.style.verticalAlign = "top";    
      row.innerHTML = `
        <th class="text-center tl_lite">ลำดับ</th> 
        <th class="text-center tl_lite">เครื่อง</th>
        <th class="text-center tl_lite">วัน</th>
        <th class="text-center tl_lite">เวลาเต็ม</th>
        <th class="text-center tlb_lite" colspan="2" style="background-color: #d7d7d7;">เวลาเดิน</th>
        <th class="text-end tlb_lite" colspan="4" >ด้านหลอดบน</th>
        <th class="text-end tlb_lite" colspan="4">ด้ายขาด</th>
        <th class="text-end tlb_lite" colspan="4">เก็บอีแปะ</th>
        <th class="text-end tlb_lite" colspan="4">กระสวยกระโดด</th>
        <th class="text-end tlb_lite" colspan="4">อื่นๆ</th>
        <th class="text-end tlbr_lite" colspan="4" style="background-color: #d7d7d7;">รวมทั้งหมด</th>      
      `;

      row = tableName.insertRow(prev+1);    
      row.innerHTML =`    
        <th class="text-center lb_bold">&nbsp;</th> 
        <th class="text-center lb_bold">&nbsp;</th>
        <th class="text-center lb_bold">&nbsp;</th>
        <th class="text-center lb_bold">&nbsp;</th>
        <th class="text-end lb_bold">นาที</th>
        <th class="text-end lb_bold">%</th>  

        <th class="text-end lb_bold">ครั้ง</th>  
        <th class="text-end lb_bold">นาที</th>      
        <th class="text-end lb_bold">เฉลี่ย</th>   
        <th class="text-end lb_bold" style="background-color: #e2e2e2;">%</th>   

        <th class="text-end lb_bold">ครั้ง</th>  
        <th class="text-end lb_bold">นาที</th>      
        <th class="text-end lb_bold">เฉลี่ย</th>   
        <th class="text-end lb_bold" style="background-color: #e2e2e2;">%</th>    

        <th class="text-end lb_bold">ครั้ง</th>  
        <th class="text-end lb_bold">นาที</th>      
        <th class="text-end lb_bold">เฉลี่ย</th>   
        <th class="text-end lb_bold" style="background-color: #e2e2e2;">%</th>  

        <th class="text-end lb_bold">ครั้ง</th>  
        <th class="text-end lb_bold">นาที</th>      
        <th class="text-end lb_bold">เฉลี่ย</th>   
        <th class="text-end lb_bold" style="background-color: #e2e2e2;">%</th>  

        <th class="text-end lb_bold">ครั้ง</th>  
        <th class="text-end lb_bold">นาที</th>      
        <th class="text-end lb_bold">เฉลี่ย</th>   
        <th class="text-end lb_bold" style="background-color: #e2e2e2;">%</th>  

        <th class="text-end lb_bold">ครั้ง</th>  
        <th class="text-end lb_bold">นาที</th>      
        <th class="text-end lb_bold">เฉลี่ย</th> 
        <th class="text-end lbr_bold" style="background-color: #e2e2e2;">%</th>          
      `;
  } 

  function show_dataTable(yy,ob){  //========= ฟังก์ชั่นเพิ่ม Row ตาราง    
    var tableName = document.getElementById('data_table');
    var prev = tableName.rows.length;           
    var row = tableName.insertRow(prev);    
    row.style.verticalAlign = "center";
    let n_col = 30;
    let col = [];
    let nn = 0;
    let line_t = '';
    let numMc = 1;
        
    for(let i=0; i<n_col; i++){
      col[i] = row.insertCell(i);
    }
    if(ob.key_p == 'data'){
      line_t = "";
      col[0].innerHTML = `<div class="lb_lite" align="center">${yy}</div>`;
      col[1].innerHTML = `<div class="lb_lite" align="center">${ob.mc}</div>`;
      nn = 1;      
    }
let n_all = (ob.top*1)+(ob.mid*1)+(ob.epa*1)+(ob.bob*1)+(ob.off*1);
let t_all = (ob.top_t*1)+(ob.mid_t*1)+(ob.epa_t*1)+(ob.bob_t*1)+(ob.off_t*1);
let diff_t = ((ob.sf_min*ob.n_day)-t_all-(ob.on_t*1)) < 0?0:(ob.sf_min*ob.n_day)-t_all-(ob.on_t*1);
let off_tnew = (ob.off_t*1)+diff_t;
t_all = diff_t + t_all;

    col[nn+1].innerHTML = `<div class="`+line_t+`lb_lite" align="right">`+(ob.n_day*1).toFixed(0)+`</div>`;
    col[nn+2].innerHTML = `<div class="`+line_t+`lb_lite" align="right">`+addCommas((ob.sf_min*1).toFixed(0))+`</div>`;
    col[nn+3].innerHTML = `<div class="`+line_t+`lb_lite" align="right">`+addCommas((dv(ob.on_t,ob.n_day)).toFixed(0))+`</div>`;
    col[nn+4].innerHTML = `<div class="`+line_t+`lb_lite" align="right">`+per(ob.sf_min*ob.n_day,ob.on_t)+`</div>`;

    col[nn+5].innerHTML = `<div class="`+line_t+`lb_lite" align="right">`+addCommas((dv(ob.top,ob.n_day)).toFixed(0))+`</div>`;
    col[nn+6].innerHTML = `<div class="`+line_t+`lb_lite" align="right">`+addCommas((dv(ob.top_t,ob.n_day)).toFixed(0))+`</div>`;
    col[nn+7].innerHTML = `<div class="`+line_t+`lb_lite" align="right">`+addCommas((dv(ob.top_t,ob.top)).toFixed(2))+`</div>`;
    col[nn+8].innerHTML = `<div class="`+line_t+`lb_lite" align="right" style="background-color: #e2e2e2;">`+per(ob.sf_min*ob.n_day,ob.top_t)+`</div>`;

    col[nn+9].innerHTML = `<div class="`+line_t+`lb_lite" align="right">`+addCommas((dv(ob.mid,ob.n_day)).toFixed(0))+`</div>`;
    col[nn+10].innerHTML = `<div class="`+line_t+`lb_lite" align="right">`+addCommas((dv(ob.mid_t,ob.n_day)).toFixed(0))+`</div>`;
    col[nn+11].innerHTML = `<div class="`+line_t+`lb_lite" align="right">`+addCommas((dv(ob.mid_t,ob.mid)).toFixed(2))+`</div>`;
    col[nn+12].innerHTML = `<div class="`+line_t+`lb_lite" align="right" style="background-color: #e2e2e2;">`+per(ob.sf_min*ob.n_day,ob.mid_t)+`</div>`;

    col[nn+13].innerHTML = `<div class="`+line_t+`lb_lite" align="right">`+addCommas((dv(ob.epa,ob.n_day)).toFixed(0))+`</div>`;
    col[nn+14].innerHTML = `<div class="`+line_t+`lb_lite" align="right">`+addCommas((dv(ob.epa_t,ob.n_day)).toFixed(0))+`</div>`;
    col[nn+15].innerHTML = `<div class="`+line_t+`lb_lite" align="right">`+addCommas((dv(ob.epa_t,ob.epa)).toFixed(2))+`</div>`;
    col[nn+16].innerHTML = `<div class="`+line_t+`lb_lite" align="right" style="background-color: #e2e2e2;">`+per(ob.sf_min*ob.n_day,ob.epa_t)+`</div>`;

    col[nn+17].innerHTML = `<div class="`+line_t+`lb_lite" align="right">`+addCommas((dv(ob.bob,ob.n_day)).toFixed(0))+`</div>`;
    col[nn+18].innerHTML = `<div class="`+line_t+`lb_lite" align="right">`+addCommas((dv(ob.bob_t,ob.n_day)).toFixed(0))+`</div>`;
    col[nn+19].innerHTML = `<div class="`+line_t+`lb_lite" align="right">`+addCommas((dv(ob.bob_t,ob.bob)).toFixed(2))+`</div>`;
    col[nn+20].innerHTML = `<div class="`+line_t+`lb_lite" align="right" style="background-color: #e2e2e2;">`+per(ob.sf_min*ob.n_day,ob.bob_t)+`</div>`;

    col[nn+21].innerHTML = `<div class="`+line_t+`lb_lite" align="right">`+addCommas((dv(ob.off,ob.n_day)).toFixed(0))+`</div>`;
    col[nn+22].innerHTML = `<div class="`+line_t+`lb_lite" align="right">`+addCommas((dv(off_tnew,ob.n_day)).toFixed(0))+`</div>`;
    col[nn+23].innerHTML = `<div class="`+line_t+`lb_lite" align="right">`+addCommas((dv(off_tnew,ob.off)).toFixed(2))+`</div>`;
    col[nn+24].innerHTML = `<div class="`+line_t+`lb_lite" align="right" style="background-color: #e2e2e2;">`+per(ob.sf_min*ob.n_day,off_tnew)+`</div>`;

    col[nn+25].innerHTML = `<div class="`+line_t+`lb_lite" align="right">`+addCommas((dv(n_all,ob.n_day)).toFixed(0))+`</div>`;
    col[nn+26].innerHTML = `<div class="`+line_t+`lb_lite" align="right">`+addCommas((dv(t_all,ob.n_day)).toFixed(0))+`</div>`;
    col[nn+27].innerHTML = `<div class="`+line_t+`lb_lite" align="right">`+addCommas((dv(t_all/n_all)).toFixed(2))+`</div>`;
    col[nn+28].innerHTML = `<div class="`+line_t+`lbr_lite" align="right" style="background-color: #e2e2e2;">`+per(ob.sf_min*ob.n_day,t_all)+`</div>`;
   
}
function per(tg,me){
    let sol = ((tg*1)>0?(me*100):0)/((tg*1)>0?(tg*1):1);
    return sol.toFixed(2);
}

function dv(aa,bb){
    let sol = (aa*1)/(((bb*1)>0)?(bb*1):1);
    return sol;
}

function addCommas(nStr){ // ใส่คอมม่าให้ตัวเลข
	nStr += '';
	x = nStr.split('.');
	x1 = x[0];
	x2 = x.length > 1 ? '.' + x[1] : '';
	var rgx = /(\d+)(\d{3})/;
	while (rgx.test(x1)) {
		x1 = x1.replace(rgx, '$1' + ',' + '$2');
	}
	return x1 + x2;
}

function close_window() {
  //if (confirm("Close Report?")) {
    close();
  //}
}

function ExportToExcel(type, fn, dl) {
  var dd = Date.now();
  var elt = document.getElementById('data_table');
  var wb = XLSX.utils.table_to_book(elt, { sheet: "sheet1",raw:true });
  return dl ?
      XLSX.write(wb, { bookType: type, bookSST: true, type: 'base64' }) :
      XLSX.writeFile(wb, fn || ('ประสิทธิภาพเครื่องจักร(รอบ)_'+dd+'.' + (type || 'xlsx')));
}

</script>
  </body>

  </html>
