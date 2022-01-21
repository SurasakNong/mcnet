
function show_data_page(){ //========================== แสดงค้นหา และปุ่มเพิ่ม หมวดรายการ
    var html = `
  <div class="container-fluid animate__animated animate__fadeIn">
    <div class="row">                
        <div class="col-lg-6 mx-auto mt-3">
            <form id="fmsearch_data">
                <div class="input-group mb-2">
                    <input type="text" id="search_data" name="search_data" class="form-control" placeholder="คำค้นหา..5C19 จอดกระสวย จอดอื่นๆ อาคาร5 อาคาร-12 เดิน" aria-label="Search" aria-describedby="button-search">
                    <button class="btn btn-success" type="button" id="bt_search_data" name="bt_search_data" title="ค้นหา"><i class="fas fa-search"></i></button>                    
                    <button class="btn btn-primary ms-2" id="bt_list" name="bt_list" type="button" title="รายเครื่อง"><i class="fas fa-list-alt"></i></button>
                </div>
            </form>
        </div>
    </div>   
    <hr>  
    <div class="row g-4 justify-content-center mb-5 noselect">
            <div class="data_meter col-md-6 col-lg-4 col-xl-3 pt-4 mx-2">  <!-- Meter -->
                <div class="row justify-content-center mb-1">
                    <div class="data_meter_h col">มิเตอร์รวม</div>
                    <div class="data_meter_p col" id="p_meter_p">0.00%</div>
                </div>
                <div class="row justify-content-center mb-3">
                    <div class="col">&nbsp;</div>
                    <div class="col" style="text-align: center;"><i class="far fa-clock ">&nbsp;<a id="p_timeon2">000 นาที</a></i></div>
                </div>
                <div class="data_meter_m row justify-content-center" id="p_meter_n">0</div>
                <div class="row justify-content-center ">
                    <div class="col-3" style="text-align: center;">MAX</div>
                    <div class="data_meter_mx col-6" style="text-align: center;" id="p_meter_mx">0</div>
                    <div class="col-3" style="text-align: center;">Knt/Mc</div>
                </div>
                <hr>          
                <div class="row justify-content-center "> 
                    <div class="col-3" style="text-align: center;">AVG</div>
                    <div class="data_meter_av col-6" style="text-align: center; font-weight: 600;" id="p_meter_a">0</div>
                    <div class="col-3" style="text-align: center;">Knt/Mc</div>
                </div>     
                <hr>
                <div class="row justify-content-center pb-5"> 
                    <div class="col-3" style="text-align: center;">MIN</div>
                    <div class="data_meter_mx col-6" style="text-align: center;" id="p_meter_mn">0</div>
                    <div class="col-3" style="text-align: center;">Knt/Mc</div>
                </div>   
            </div>

            <div class="data_mc col-md-6 col-lg-4 col-xl-3 pt-4 mx-2">  <!-- MC -->
                <div class="data_mc_on row mb-2 ms-2">
                    <div class="col-6 ps-4">เครื่องเดิน</div>                   
                    <div class="col" style="text-align: center;" id="p_mcon_per">00.00%</div>
                </div>                
                <div class="data_mc_on row">
                    <div class="col-6">
                        <p class="text-center" id="p_mcon">0</p>
                    </div>                    
                    <div class="col">
                        <div class="row display-6 justify-content-center">&nbsp;</div>
                        <div class="row  mb-3" style="text-align: center;" ><i class="far fa-clock">&nbsp;<a id="p_timeon">000 นาที</a></i></div>
                        <div class="row justify-content-center">เครื่องเต็ม</div>
                        <div class="row display-4 justify-content-center" style="align-items: center;" id="p_mc">0</div>
                    </div>
                </div>
                <hr>
                <div class="data_mc_off1 row mb-2 ms-2">
                    <div class="col-6 ps-4">เครื่องจอด</div>                   
                    <div class="col" style="text-align: center;" id="p_mcoff_per">00.00%</div>
                </div>
                
                <div class="data_mc_off2 row pb-4">
                    <div class="col-4" style="text-align: center;">
                        <p class="text-center" style="font-size: 6rem; margin-top: -20px;" id="p_mcoff">0</p>
                    </div>
                    <div class="col-3">
                        <div class="row">ด้ายบน</div>
                        <div class="row">ด้ายขาด</div>
                        <div class="row">อีแปะ</div>
                        <div class="row">กระสวย</div>
                        <div class="row">อื่นๆ</div>
                    </div>
                    <div class="col-2">
                        <div class="row justify-content-center" id="p_top_mc">0</div>
                        <div class="row justify-content-center" id="p_mid_mc">0</div>
                        <div class="row justify-content-center" id="p_epa_mc">0</div>
                        <div class="row justify-content-center" id="p_bob_mc">0</div>
                        <div class="row justify-content-center" id="p_off_mc">0</div>
                    </div>
                    <div class="col-3">
                        <div class="row justify-content-end pe-4" id="p_top_mc_p">0%</div>
                        <div class="row justify-content-end pe-4" id="p_mid_mc_p">0%</div>
                        <div class="row justify-content-end pe-4" id="p_epa_mc_p">0%</div>
                        <div class="row justify-content-end pe-4" id="p_bob_mc_p">0%</div>
                        <div class="row justify-content-end pe-4" id="p_off_mc_p">0%</div>
                    </div>
                </div>
            </div>


            <div class="data_rpm col-md-6 col-lg-4 col-xl-3 pt-4 mx-2">  <!-- RPM -->
                <div class="data_h_rpm row justify-content-center mb-4">รอบเครื่อง</div>
                <div class="row justify-content-center ">
                    <div class="col-3" style="text-align: center;">MAX</div>
                    <div class="col-6 display-6" style="text-align: center;" id="p_rpm_mx">0.00</div>
                    <div class="col-3" style="text-align: center;">rpm</div>
                </div>
                <hr>          
                <div class="row justify-content-center "> 
                    <div class="col-3" style="text-align: center;">AVG</div>
                    <div class="data_rpm_d col-6" style="text-align: center; font-weight: 600;" id="p_rpm_a">0.00</div>
                    <div class="col-3" style="text-align: center;">rpm</div>
                </div>     
                <hr>
                <div class="row justify-content-center"> 
                    <div class="col-3" style="text-align: center;">MIN</div>
                    <div class="col-6 display-6" style="text-align: center;" id="p_rpm_mn">0.00</div>
                    <div class="col-3" style="text-align: center;">rpm</div>
                </div>   
                <div class="row mb-2 mt-5">
                    <div class="timeon_all col-6" style="text-align: center;"><a>เวลา-เดิน</a></div>                   
                    <div class="timeon_all col" id="p_rpm_cal">0.00 รอบ/นาที</div>
                </div> 
                <div class="row mb-3 mt-2">
                    <div class="timeon_all col-6" style="text-align: center;"><a>เวลา-รวม</a></div>                   
                    <div class="timeon_all col" id="p_rpm_all_cal">0.00 รอบ/นาที</div>
                </div> 

            </div>


            <div class="data_down col-md-6 col-lg-4 col-xl-3 pt-4 mx-2">  <!-- Downtime -->
                <div class="data_h_down row justify-content-center display-4 mb-4">เวลาจอด</div>
                <div class="row">
                    <div class="data_h_down2 col-6" style="text-align: right;" id="p_down_p">0.00%</div>
                    <div class="col-3">
                        <div class="row justify-content-end pe-3" id="p_down_n">0</div>
                        <div class="row justify-content-end pe-3" id="p_down_t">0</div>
                        <div class="row justify-content-end pe-3" id="p_down_a">0.00</div>
                    </div>
                    <div class="col-3">
                        <div class="row">ครั้ง</div>
                        <div class="row">นาที</div>
                        <div class="row">นาที/ครั้ง</div>
                    </div>
                </div>
                <hr>
                
                <div class="row pb-1">
                    <div class="col-3">
                        <div class="row">&nbsp;</div>
                        <div class="data_down_h row justify-content-center">ด้ายบน</div>
                        <div class="data_down_h row justify-content-center">ด้ายขาด</div>
                        <div class="data_down_h row justify-content-center">อีแปะ</div>
                        <div class="data_down_h row justify-content-center">กระสวย</div>
                        <div class="data_down_h row justify-content-center">อื่นๆ</div>
                    </div>
                    <div class="col-2">
                        <div class="data_down_h row justify-content-end">ครั้ง</div>
                        <div class="row justify-content-end" id="p_top_n">0</div>
                        <div class="row justify-content-end" id="p_mid_n">0</div>
                        <div class="row justify-content-end" id="p_epa_n">0</div>
                        <div class="row justify-content-end" id="p_bob_n">0</div>
                        <div class="row justify-content-end" id="p_off_n">0</div>
                    </div>
                    <div class="col-2">
                        <div class="data_down_h row justify-content-end">นาที</div>
                        <div class="row justify-content-end" id="p_top_t">0</div>
                        <div class="row justify-content-end" id="p_mid_t">0</div>
                        <div class="row justify-content-end" id="p_epa_t">0</div>
                        <div class="row justify-content-end" id="p_bob_t">0</div>
                        <div class="row justify-content-end" id="p_off_t">0</div>
                    </div>
                    <div class="col-2">
                        <div class="data_down_h row justify-content-end">เฉลี่ย</div>
                        <div class="row justify-content-end" id="p_top_a">0.00</div>
                        <div class="row justify-content-end" id="p_mid_a">0.00</div>
                        <div class="row justify-content-end" id="p_epa_a">0.00</div>
                        <div class="row justify-content-end" id="p_bob_a">0.00</div>
                        <div class="row justify-content-end" id="p_off_a">0.00</div>
                    </div>
                    <div class="col-2">
                        <div class="data_down_h row justify-content-center">%</div>
                        <div class="data_down_d row justify-content-end pe-2" id="p_top_p">00.00</div>
                        <div class="data_down_d row justify-content-end pe-2" id="p_mid_p">00.00</div>
                        <div class="data_down_d row justify-content-end pe-2" id="p_epa_p">00.00</div>
                        <div class="data_down_d row justify-content-end pe-2" id="p_bob_p">00.00</div>
                        <div class="data_down_d row justify-content-end pe-2 pb-3" id="p_off_p">00.00</div>
                    </div>                    
                    <hr>
                </div>    
                <div class="row mb-4">
                    <div class="timeon_all col-7" style="text-align: center;"><i class="far fa-clock ">&nbsp;<a>เวลา (เดิน+จอด)</a></i></div>                   
                    <div class="timeon_all col" id="p_timeon_all">0 นาที</div>
                </div>               
            </div>

            
            
            <div class="data_tmp col-md-6 col-lg-4 col-xl-3 pt-4 mx-2">  <!-- TMP and HMD -->
                <div class="data_tmp_h row justify-content-center mb-5">อุณหภูมิ และ ความชื้น </div>
                <div class="row mb-4">
                    <div class="col" style="text-align: center;"><i class="fas fa-temperature-high fa-3x"></i></div>
                    <div class="col" style="text-align: center;"><i class="fas fa-tint fa-3x"></i></div>
                </div>
                <div class="row justify-content-center ">
                    <div class="data_tmp_m col-5" style="text-align: center;" id="p_tmp_mx">0.00</div>
                    <div class="col-2" style="text-align: center;">MAX</div>
                    <div class="data_tmp_m col-5" style="text-align: center;" id="p_hmd_mx">0.00</div>
                </div>
                <hr>          
                <div class="row justify-content-center "> 
                    <div class="data_tmp_av col-5" style="text-align: center; font-weight: 600;" id="p_tmp_a">0.00</div>
                    <div class="col-2" style="text-align: center;">AVG</div>
                    <div class="data_tmp_av col-5" style="text-align: center; font-weight: 600;" id="p_hmd_a">0.00</div>
                </div>     
                <hr>
                <div class="row justify-content-center pb-5"> 
                    <div class="data_tmp_m col-5" style="text-align: center;" id="p_tmp_mn">0.00</div>
                    <div class="col-2" style="text-align: center;">MIN</div>
                    <div class="data_tmp_m col-5" style="text-align: center;" id="p_hmd_mn">0.00</div>
                </div>   

            </div>      
        </div>
   
  </div>
    `;
    $("#content").html(html);    
    clearInterval(list_data_inteval); //ยกเลิกการดึงข้อมูลตามเวลาที่ตั้งไว้
    showdatapage();
    page_data_inteval = setInterval(pagedata_interval,3000);
}

function showdatapage(){ //======================== แสดงตาราง
  var ss = document.getElementById('search_data').value;            
  var jwt = getCookie("jwt");
  $.ajax({
    type: "POST", 
    url: "api/data_monitor.php",
    data: {search:ss,jwt:jwt,fn:'page'},
    success: function(result){
        var mc_off = p_In(result.data_sum[0].n_mc)-p_In(result.data_sum[0].mc_on);    
        var mc_on = p_In(result.data_sum[0].mc_on);
        var n_mc = p_In(result.data_sum[0].n_mc);
        var time_on = p_In(result.data_sum[0].s_ont)/600;
        $("#p_mc").text(n_mc);
        $("#p_mcon").text(mc_on);
        $("#p_mcon_per").text((mc_on*100/(n_mc<=0?1:n_mc)).toFixed(2)+" %");
        $("#p_mcoff").text(mc_off);
        $("#p_mcoff_per").text((mc_off*100/(n_mc<=0?1:n_mc)).toFixed(2)+" %");
        $("#p_timeon").text(addCommas(time_on.toFixed(0))+" นาที");
        $("#p_top_mc").text(p_In(result.data_sum[0].mc_top));
        $("#p_mid_mc").text(p_In(result.data_sum[0].mc_mid));
        $("#p_epa_mc").text(p_In(result.data_sum[0].mc_epa));
        $("#p_bob_mc").text(p_In(result.data_sum[0].mc_bob));
        $("#p_off_mc").text(p_In(result.data_sum[0].mc_off));
        
        $("#p_top_mc_p").text((p_In(result.data_sum[0].mc_top)*100/(n_mc<=0?1:n_mc)).toFixed(2)+" %");
        $("#p_mid_mc_p").text((p_In(result.data_sum[0].mc_mid)*100/(n_mc<=0?1:n_mc)).toFixed(2)+" %");
        $("#p_epa_mc_p").text((p_In(result.data_sum[0].mc_epa)*100/(n_mc<=0?1:n_mc)).toFixed(2)+" %");
        $("#p_bob_mc_p").text((p_In(result.data_sum[0].mc_bob)*100/(n_mc<=0?1:n_mc)).toFixed(2)+" %");
        $("#p_off_mc_p").text((p_In(result.data_sum[0].mc_off)*100/(n_mc<=0?1:n_mc)).toFixed(2)+" %");

        var sum_down_n = p_In(result.data_sum[0].s_top)+p_In(result.data_sum[0].s_mid)+p_In(result.data_sum[0].s_epa)+p_In(result.data_sum[0].s_bob)+p_In(result.data_sum[0].s_off);
        var sum_down_t = (p_In(result.data_sum[0].s_topt)+p_In(result.data_sum[0].s_midt)+p_In(result.data_sum[0].s_epat)+p_In(result.data_sum[0].s_bobt)+p_In(result.data_sum[0].s_offt))/600;
        var sum_all_t = (p_In(result.data_sum[0].s_ont)/600) + sum_down_t
        var sum_t_on = p_In(result.data_sum[0].s_ont)<=0?1/600:p_In(result.data_sum[0].s_ont)/600;

        $("#p_rpm_mx").text(p_Fl(result.data_sum[0].mx_rpm).toFixed(2));
        $("#p_rpm_a").text(p_Fl(result.data_sum[0].a_rpm).toFixed(2));
        $("#p_rpm_mn").text(p_Fl(result.data_sum[0].mn_rpm).toFixed(2));
        $("#p_rpm_cal").text((p_In(result.data_sum[0].s_meter)/sum_t_on).toFixed(2)+" รอบ/นาที");
        $("#p_rpm_all_cal").text((p_In(result.data_sum[0].s_meter)/(sum_all_t<=0?1:sum_all_t)).toFixed(2)+" รอบ/นาที");
        
        $("#p_down_n").text(addCommas(sum_down_n));
        $("#p_down_t").text(addCommas((sum_down_t).toFixed(0)));
        $("#p_down_a").text(((sum_down_t)/(sum_down_n<=0?1:sum_down_n)).toFixed(2));
        $("#p_down_p").text(((sum_down_t)*100/(sum_all_t<=0?1:sum_all_t)).toFixed(2)+"%");
        $("#p_top_n").text(addCommas(p_In(result.data_sum[0].s_top)));
        $("#p_mid_n").text(addCommas(p_In(result.data_sum[0].s_mid)));
        $("#p_epa_n").text(addCommas(p_In(result.data_sum[0].s_epa)));
        $("#p_bob_n").text(addCommas(p_In(result.data_sum[0].s_bob)));
        $("#p_off_n").text(addCommas(p_In(result.data_sum[0].s_off)));
        $("#p_top_t").text(addCommas((p_In(result.data_sum[0].s_topt)/600).toFixed(0)));
        $("#p_mid_t").text(addCommas((p_In(result.data_sum[0].s_midt)/600).toFixed(0)));
        $("#p_epa_t").text(addCommas((p_In(result.data_sum[0].s_epat)/600).toFixed(0)));
        $("#p_bob_t").text(addCommas((p_In(result.data_sum[0].s_bobt)/600).toFixed(0)));
        $("#p_off_t").text(addCommas((p_In(result.data_sum[0].s_offt)/600).toFixed(0)));
        $("#p_top_a").text((p_In(result.data_sum[0].s_topt)/600/(p_In(result.data_sum[0].s_top)<=0?1:p_In(result.data_sum[0].s_top))).toFixed(2));
        $("#p_mid_a").text((p_In(result.data_sum[0].s_midt)/600/(p_In(result.data_sum[0].s_mid)<=0?1:p_In(result.data_sum[0].s_mid))).toFixed(2));
        $("#p_epa_a").text((p_In(result.data_sum[0].s_epat)/600/(p_In(result.data_sum[0].s_epa)<=0?1:p_In(result.data_sum[0].s_epa))).toFixed(2));
        $("#p_bob_a").text((p_In(result.data_sum[0].s_bobt)/600/(p_In(result.data_sum[0].s_bob)<=0?1:p_In(result.data_sum[0].s_bob))).toFixed(2));
        $("#p_off_a").text((p_In(result.data_sum[0].s_offt)/600/(p_In(result.data_sum[0].s_off)<=0?1:p_In(result.data_sum[0].s_off))).toFixed(2));
        $("#p_top_p").text(addCommas((p_In(result.data_sum[0].s_topt)*100/600/(sum_all_t<=0?1:sum_all_t)).toFixed(2)));
        $("#p_mid_p").text(addCommas((p_In(result.data_sum[0].s_midt)*100/600/(sum_all_t<=0?1:sum_all_t)).toFixed(2)));
        $("#p_epa_p").text(addCommas((p_In(result.data_sum[0].s_epat)*100/600/(sum_all_t<=0?1:sum_all_t)).toFixed(2)));
        $("#p_bob_p").text(addCommas((p_In(result.data_sum[0].s_bobt)*100/600/(sum_all_t<=0?1:sum_all_t)).toFixed(2)));
        $("#p_off_p").text(addCommas((p_In(result.data_sum[0].s_offt)*100/600/(sum_all_t<=0?1:sum_all_t)).toFixed(2)));
        $("#p_timeon_all").text(addCommas((sum_all_t).toFixed(0))+" นาที");

        $("#p_timeon2").text(addCommas(time_on.toFixed(0))+" นาที");
        $("#p_meter_p").text(((p_In(result.data_sum[0].s_ont/600))*100/(sum_all_t<=0?1:sum_all_t)).toFixed(2)+"%");
        $("#p_meter_n").text(addCommas(p_In(result.data_sum[0].s_meter)));
        $("#p_meter_mx").text(addCommas(p_In(result.data_sum[0].mx_meter)));
        $("#p_meter_a").text(addCommas((p_In(result.data_sum[0].s_meter)/(n_mc<=0?1:n_mc)).toFixed(0)));
        $("#p_meter_mn").text(addCommas(p_In(result.data_sum[0].mn_meter)));
     
        $("#p_tmp_mx").text(p_Fl(result.data_sum[0].mx_tmp).toFixed(2));
        $("#p_tmp_a").text(p_Fl(result.data_sum[0].a_tmp).toFixed(2));
        $("#p_tmp_mn").text(p_Fl(result.data_sum[0].mn_tmp).toFixed(2));
        $("#p_hmd_mx").text(p_Fl(result.data_sum[0].mx_hmd).toFixed(2));
        $("#p_hmd_a").text(p_Fl(result.data_sum[0].a_hmd).toFixed(2));
        $("#p_hmd_mn").text(p_Fl(result.data_sum[0].mn_hmd).toFixed(2));

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



//=========================== Even เกี่ยวกับ รายการ ======================================
$(document).on('click',"#bt_search_data",function () {  //ค้นหารายการ
  showdatapage();          
});
$(document).on('click',"#bt_list",function () {  //แสดงหน้ารายการเครื่องจักร      
    $('#data_home').html('<i class="fas fa-home"></i>&nbsp; HOME');
    page_sel = "1";
    show_data_table(); 
    first_page= true;
});

function pagedata_interval(){
    showdatapage();
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

function p_In(strnum){
    let my_num = ((strnum == "") || (strnum == null))?0:parseInt(strnum);
    return my_num;
}

function p_Fl(strnum){
    let my_num =((strnum == "") || (strnum == null))?0:parseFloat(strnum);
    return my_num;
}