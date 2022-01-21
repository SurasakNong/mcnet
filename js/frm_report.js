
function show_frmRep(){ //==================== เลือกรายงานที่ต้องการ
    var html = `
  <div class="container-fluid animate__animated animate__fadeIn">
    <div class="row">
        <div class="col-md-12 mb-3" style='font-size:20px'> 
            <span class="d-block p-2 bg-primary text-white rounded-lg"  align="center"><i class="far fa-newspaper fa-lg" aria-hidden="true"></i> รายงาน</span>
        </div>
        <div class="col-md-12">
            <form name="frmrep" id="frmrep" method="post" action='' target="_blank" role='report'>
                <div class="row mb-3">    
                    <div class="col-md-4 mb-3">
                        <div class="input-group">
                            <div class="input-group-text" style="width: 80px; background-color: aquamarine;">วันที่</div>
                            <input type="text" class="form-control" name="datefm" id="picker">
                        </div>
                    </div>    
                    <div class="col-md-4 mb-3">
                        <div class="input-group">
                            <div class="input-group-text" style="width: 80px; background-color: aquamarine;">ถึง</div>
                            <input type="text" class="form-control" name="dateto" id="picker2">      
                        </div>     
                    </div>    
                    <div class="col-md-4 ">
                        <div class="input-group">
                            <div class="input-group-text" style="width: 80px; background-color: aquamarine;">หน่วยงาน</div>
                            <select class="form-select" name="depart" id="depart"></select>   
                        </div>     
                        <input id="dpsel" name="dpsel" type="hidden" value=""/>
                    </div>
                </div>

                <div class="row">
                    <div class="input-group mb-3 col-md-6">
                        <input name='search' type="text" class="form-control" placeholder="คำค้นหา...กะ1 5C 5C19" value='' onFocus="this.value ='';">
                    </div>						

                    <div class="input-group mb-3 col-md-12">
                        <label for="selrep">เลือก :&nbsp;&nbsp;</label>
                        <select class="fmsel form-control mb-2" size="6" id="selrep" name="selrep">
                            <option value="rep_rpm_data">1.) ประสิทธิภาพเครื่องจักร (รอบ/นาที)</option>
                            <option value="rep_meter_data">2.) ประสิทธิภาพเครื่องจักร (มิเตอร์)</option>        
                            <option value="rep_downtime_data">3.) ประสิทธิภาพเครื่องจักร (เวลาสูญเสีย)</option>
                            <option value="rep_ord_meter_data">4.) รายงานประสิทธิภาพมิเตอร์ (ออร์เดอร์)</option>
                            <option value="rep_ord_down_data">5.) รายงานเวลาสูญเสีย (ออร์เดอร์)</option>                      
                        </select>
                    </div>

                </div>
                
                <div class="row">
                    <div class='col-md-12' align="center">
                        <button id="show_rep" type='button' onclick="submitRep()" title="แสดงรายงาน" class='btn btn-primary me-2'>แสดงรายงาน</button>
                        <button id="show_export" type='button' onclick="submitExport()" title="ส่งออก Excel" class='btn btn-success me-2'>to Excel</button>
                        <button id="bt_back" type='button' title="กลับหน้าหลัก"  class='btn btn-warning ms-2' >กลับ</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
  </div>
    `;
    $("#content").html(html);    
    clearInterval(list_data_inteval); //ยกเลิกการดึงข้อมูลตามเวลาที่ตั้งไว้
    clearInterval(page_data_inteval);  
    var today = new Date();
    var dd = String( today.getDate() ).padStart( 2, '0' );
    var mm = String( today.getMonth() + 1 ).padStart( 2, '0' ); //January is 0!
    var yyyy = today.getFullYear();
    today = yyyy + '-' + mm + '-' + dd;

    jQuery.datetimepicker.setLocale( 'th' );

    $( '#picker' ).datetimepicker( {
        timepicker: false,
        datepicker: true,
        format: 'd/m/Y',
        value:today,
        mask: true
    } );

    $( '#picker2' ).datetimepicker( {
        timepicker: false,
        datepicker: true,
        format: 'd/m/Y',
        value:today,
        mask: true
    } );

    let dropdown = $('#depart');
        dropdown.empty();
        dropdown.append('<option value="0" >-- ไม่ระบุ --</option>');
        dropdown.prop('selectedIndex', 0);
        $.ajax({
                  type: "POST",
                  url: "api/getDropdown.php",
                  data: {id:'',fn:'bd'},
                  success: function(result){
                    $.each(result, function (key, entry) {
                      dropdown.append($('<option></option>').attr('value', entry.bd_id).text(entry.bd_name));
                    })                             
                  }
                });  
}

//=========================== Event =====================================


$(document).on("change", "#picker", function () { 
    $('#picker2').datetimepicker( {
        value:this.value
    });
});
$(document).on("change", "#depart", function () {   
    document.getElementById('dpsel').value = this.options[this.selectedIndex].text;
});

function submitRep() {
	var s1 = document.getElementsByName('selrep');
		s1 = s1.item(0).value;
		if(s1 == ""){
			Signed('warning','กรุณาเลือกรายงานที่ต้องการก่อน ')
		} else{
			document.getElementById('frmrep').action = s1;
			$('#frmrep').attr('target', '_blank');
			$( '#frmrep' ) . submit();
		}
}
	
function submitExport() {
	var sel = document.getElementsByName('selrep');    
    var sel_ind = $("select[name='selrep'] option:selected").index();
		if(sel.item(0).value == ""){
			Signed('warning','กรุณาเลือกรายงานที่ต้องการก่อน ')
		} else{
            if(sel_ind >= 0){
			    document.getElementById('frmrep').action = sel.item(0).value+"_export";
                $('#frmrep').attr('target', '_blank');
                $( '#frmrep' ).submit();
            }
		}
}