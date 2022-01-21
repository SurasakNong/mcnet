<!doctype html>
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
  <script>
    var all_data =0;
    var dataPerpage = 45;
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
    var x = 1; //=== ลำดับหน้า
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
            <td align="center" width="120"><button id="printPageButton" title="พิมพ์รายงาน" onClick="printpage();" style="width:40px; height:40px; margin-top:20px;"><i class="fas fa-print fa-2x"></i></button></td>
            <td align="center" class="headTitle" id="head_rep">รายงาน (------)</td>
            <td align="center" width="120"><button id="closePageButton" title="ปิดหน้ารายงาน" onClick="close_window(); return false;" style="width:40px; height:40px; margin-top:20px;"><i class="far fa-times-circle fa-2x"></i></button></td>
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

        $("#head_rep").html("ประสิทธิภาพเครื่องจักร (เวลาสูญเสีย)");
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
            data_t.setAttribute('width','1050');
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
              data_t.setAttribute('width','1050');
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


  function show_dataTable(x,yy,ob){  //========= ฟังก์ชั่นเพิ่ม Row ตาราง    
    var tableName = document.getElementById('data_table'+x);
    var prev = tableName.rows.length;           
    var row = tableName.insertRow(prev);    
    row.style.verticalAlign = "center";
    let n_col = 30;
    let col = [];
    let nn = 0;
    let grouprow = '';
    let line_t = '';
    let numMc = 1;
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
      col[2].innerHTML = `<div class="lb_lite" align="right">`+(ob.n_day*1).toFixed(0)+`</div>`;
      nn = 2;      
    }else{      
      line_t = (ob.key_p == 'all')?"tt":"t";
      if(ob.key_p == 'gr1'){
        line_t = "t";
      }else if(ob.key_p == 'gr2'){
        line_t = "dt";
      }else if(ob.key_p == 'all'){
        line_t = "tt";
      }
      col[nn].setAttribute('colspan','3');
      col[nn].innerHTML = grouprow;     
      numMc = ob.n_mc*1;

    }   
let n_all = (ob.top*1)+(ob.mid*1)+(ob.epa*1)+(ob.bob*1)+(ob.off*1);
let t_all = (ob.top_t*1)+(ob.mid_t*1)+(ob.epa_t*1)+(ob.bob_t*1)+(ob.off_t*1);
let diff_t = ((ob.sf_min*ob.n_day)-t_all-(ob.on_t*1)) < 0?0:(ob.sf_min*ob.n_day)-t_all-(ob.on_t*1);
let off_tnew = (ob.off_t*1)+diff_t;
t_all = diff_t + t_all;

    col[nn+1].innerHTML = `<div class="`+line_t+`lb_lite" align="right">`+addCommas((ob.sf_min*1).toFixed(0))+`</div>`;
    col[nn+2].innerHTML = `<div class="`+line_t+`lb_lite" align="right">`+addCommas((dv(ob.on_t,ob.n_day)).toFixed(0))+`</div>`;
    col[nn+3].innerHTML = `<div class="`+line_t+`lb_lite" align="right">`+per(ob.sf_min*ob.n_day,ob.on_t)+`</div>`;

    col[nn+4].innerHTML = `<div class="`+line_t+`lb_lite" align="right">`+addCommas((dv(ob.top,ob.n_day)).toFixed(0))+`</div>`;
    col[nn+5].innerHTML = `<div class="`+line_t+`lb_lite" align="right">`+addCommas((dv(ob.top_t,ob.n_day)).toFixed(0))+`</div>`;
    col[nn+6].innerHTML = `<div class="`+line_t+`lb_lite" align="right">`+addCommas((dv(ob.top_t,ob.top)).toFixed(2))+`</div>`;
    col[nn+7].innerHTML = `<div class="`+line_t+`lb_lite" align="right" style="background-color: #e2e2e2;">`+per(ob.sf_min*ob.n_day,ob.top_t)+`</div>`;

    col[nn+8].innerHTML = `<div class="`+line_t+`lb_lite" align="right">`+addCommas((dv(ob.mid,ob.n_day)).toFixed(0))+`</div>`;
    col[nn+9].innerHTML = `<div class="`+line_t+`lb_lite" align="right">`+addCommas((dv(ob.mid_t,ob.n_day)).toFixed(0))+`</div>`;
    col[nn+10].innerHTML = `<div class="`+line_t+`lb_lite" align="right">`+addCommas((dv(ob.mid_t,ob.mid)).toFixed(2))+`</div>`;
    col[nn+11].innerHTML = `<div class="`+line_t+`lb_lite" align="right" style="background-color: #e2e2e2;">`+per(ob.sf_min*ob.n_day,ob.mid_t)+`</div>`;

    col[nn+12].innerHTML = `<div class="`+line_t+`lb_lite" align="right">`+addCommas((dv(ob.epa,ob.n_day)).toFixed(0))+`</div>`;
    col[nn+13].innerHTML = `<div class="`+line_t+`lb_lite" align="right">`+addCommas((dv(ob.epa_t,ob.n_day)).toFixed(0))+`</div>`;
    col[nn+14].innerHTML = `<div class="`+line_t+`lb_lite" align="right">`+addCommas((dv(ob.epa_t,ob.epa)).toFixed(2))+`</div>`;
    col[nn+15].innerHTML = `<div class="`+line_t+`lb_lite" align="right" style="background-color: #e2e2e2;">`+per(ob.sf_min*ob.n_day,ob.epa_t)+`</div>`;

    col[nn+16].innerHTML = `<div class="`+line_t+`lb_lite" align="right">`+addCommas((dv(ob.bob,ob.n_day)).toFixed(0))+`</div>`;
    col[nn+17].innerHTML = `<div class="`+line_t+`lb_lite" align="right">`+addCommas((dv(ob.bob_t,ob.n_day)).toFixed(0))+`</div>`;
    col[nn+18].innerHTML = `<div class="`+line_t+`lb_lite" align="right">`+addCommas((dv(ob.bob_t,ob.bob)).toFixed(2))+`</div>`;
    col[nn+19].innerHTML = `<div class="`+line_t+`lb_lite" align="right" style="background-color: #e2e2e2;">`+per(ob.sf_min*ob.n_day,ob.bob_t)+`</div>`;

    col[nn+20].innerHTML = `<div class="`+line_t+`lb_lite" align="right">`+addCommas((dv(ob.off,ob.n_day)).toFixed(0))+`</div>`;
    col[nn+21].innerHTML = `<div class="`+line_t+`lb_lite" align="right">`+addCommas((dv(off_tnew,ob.n_day)).toFixed(0))+`</div>`;
    col[nn+22].innerHTML = `<div class="`+line_t+`lb_lite" align="right">`+addCommas((dv(off_tnew,ob.off)).toFixed(2))+`</div>`;
    col[nn+23].innerHTML = `<div class="`+line_t+`lb_lite" align="right" style="background-color: #e2e2e2;">`+per(ob.sf_min*ob.n_day,off_tnew)+`</div>`;

    col[nn+24].innerHTML = `<div class="`+line_t+`lb_lite" align="right">`+addCommas((dv(n_all,ob.n_day)).toFixed(0))+`</div>`;
    col[nn+25].innerHTML = `<div class="`+line_t+`lb_lite" align="right">`+addCommas((dv(t_all,ob.n_day)).toFixed(0))+`</div>`;
    col[nn+26].innerHTML = `<div class="`+line_t+`lb_lite" align="right">`+addCommas((dv(t_all/n_all)).toFixed(2))+`</div>`;
    col[nn+27].innerHTML = `<div class="`+line_t+`lbr_lite" align="right" style="background-color: #e2e2e2;">`+per(ob.sf_min*ob.n_day,t_all)+`</div>`;
   
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

function show_footrep(){
  let parentDiv = document.getElementById('data_title').parentNode //=== สร้างตารางท้ายรายงาน
  let data_f = document.createElement('table');
  data_f.id = 'foot_table';
  data_f.setAttribute('width','1050');
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
