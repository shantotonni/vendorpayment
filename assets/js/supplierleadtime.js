var table = [];
var item = [];
var duplicatateindex;



function doLoadExcelData(exceldata){
    if(exceldata && exceldata.length > 0){ 
        //console.log(exceldata);
        for(i = 0; i < exceldata.length; i++){
            material = exceldata[i]['materialcode']+'-'+exceldata[i]['materialname'];
            business = exceldata[i]['businesscode']+'-'+exceldata[i]['businessname'];
            plantlocation = exceldata[i]['locationcode']+'-'+exceldata[i]['locationname'];
            materialtype = exceldata[i]['materialtypecode']+'-'+exceldata[i]['materialtypename'];
            material = exceldata[i]['materialcode']+'-'+exceldata[i]['materialname'];

            item.splice(table.length, 0, material);
            table.push({"materialcode": material,"businesscode": business
                , "locationcode": plantlocation
                , "materialtypecode": materialtype
                , "materialcode": material, "effectivedate": exceldata[i]['effectivedate']
                , "supplierleadtime": exceldata[i]['supplierleadtime']});
        }
        loaddata();
    }
}

 //document.getElementById("businesscode").focus();
 
$(function () {
    $("#businesscode").focus();
    
    $( "#businesscode" ).keydown(function (e){
        if(e.keyCode == 13){
            $("#locationcode").focus();
        }
    })
    $( "#locationcode" ).keydown(function (e){
        if(e.keyCode == 13){
            $("#materialtypecode").focus();
        }
    })
    $( "#materialtypecode" ).keydown(function (e){
        if(e.keyCode == 13){
            $("#materialcode").focus();
        }
    })
    $( "#effectivedate" ).keydown(function (e){
        if(e.keyCode == 13){
            $("#supplierleadtime").focus();
        }
    })
    $( "#supplierleadtime" ).keydown(function (e){
        if(e.keyCode == 13){
            $("#addbutton").focus();
        }
    })
    
    
    $( "#materialcode" ).dblclick(function() {
        $("#materialcode").val('');
        $("#materialcode").prop('readonly', false);
        $("#materialcode").focus();      
        $("#addbutton").prop('disabled', false);
    });

    $("#materialcode").autocomplete({ 
        source: function(request, response){ //console.log(request);
            var businesscode = $("#businesscode").val();
            if(!businesscode){
                alert('Please select business.');
                $("#materialcode").val('');
                $("#businesscode").focus();
                return false;
            }
            var materialtypecode = $("#materialtypecode").val();
            if(!materialtypecode){
                alert('Please select material type.');
                $("#materialcode").val('');
                $("#materialtypecode").focus();
                return false;
            }
            $.ajax({
                type: "POST",
                url: base_url + "material/getlist",
                data: "search=" + request.term + "&businesscode=" + businesscode + "&materialtypecode=" + materialtypecode,
                dataType: "json",
                cache: false,
                success: function (res) {
                    var transformed = $.map(res, function (el) {
                        return {
                            label: el.MaterialCode + '-' + el.MaterialName,
                            value: el.MaterialName,
                            code: el.MaterialCode,
                            unit: el.Unit,
                        };
                    });
                    response(transformed);                   
                },
                error: function (msg) {
                   response([]);
                }
            })
        },
        focus: function (event, ui) {
            event.preventDefault();
        },
        select: function (event, ui) {
            $("#addbutton").prop('disabled', false);
            event.preventDefault();
            $("#materialcode").val(ui.item.code + '-' + ui.item.value);
            $("#effectivedate").focus();
            $("#materialcode").prop('readonly', true);
            doLoadNotificationBoard(ui.item.code);
        },
        minLength: 1
    }).bind('focus', function () {
        $(this).autocomplete("search");
    }); 
});

function validateform(){
    var effectivedate= $("#effectivedate").val();
    var todaydate = new Date();
    var putdate = new Date(effectivedate);
    if(putdate < todaydate){
        alert("Invalid date");
        $("#effectivedate").focus();
        return false;
    }
    
    var businesscode = $("#businesscode").val();
    var locationcode = $("#locationcode").val();
    var materialtypecode = $("#materialtypecode").val();
    var materialcode = $("#materialcode").val();
    var supplierleadtime = $("#supplierleadtime").val();
    
    if(!businesscode){
        alert("Please select business");
        $("#businesscode").focus()();
        return false;
    }
    if(!locationcode){
        alert("Please select location");
        $("#locationcode").focus()();
        return false;
    }
    if(!materialtypecode){
        alert("Please select material type");
        $("#materialtypecode").focus()();
        return false;
    }
    if(supplierleadtime <=  0){
        $("#supplierleadtime").focus();
        alert("Buffer size invalid");
        return false;
    }
    
    duplicatateindex = -1;
    
    for(i = 0; i < item.length; i++){
        if(item[i] == materialcode){
            duplicatateindex = i;
        }
    }

    if(duplicatateindex >= 0){
        table[duplicatateindex]['materialcode'] = materialcode;
        table[duplicatateindex]['businesscode'] = businesscode;
        table[duplicatateindex]['locationcode'] = locationcode;
        table[duplicatateindex]['materialtypecode'] = materialtypecode;
        table[duplicatateindex]['materialcode'] = materialcode;            
        table[duplicatateindex]['effectivedate'] = effectivedate;    
        table[duplicatateindex]['supplierleadtime'] = supplierleadtime;    
    }else{
        item.splice(table.length, 0, materialcode);
        table.push({"materialcode": materialcode,"businesscode": businesscode
        , "locationcode": locationcode
        , "materialtypecode": materialtypecode
        , "materialcode": materialcode, "effectivedate": effectivedate
        , "supplierleadtime": supplierleadtime});
    } 
    $("#materialcode").val('');       
    $("#supplierleadtime").val('');
    $("#effectivedate").val(effectivedate);
    loaddata();
    $("#materialcode").focus();
    $("#materialcode").prop('readonly', false);
    $("#addbutton").prop('disabled', true);
    $("#notificationboard").css('display','none');
   // $("#addbutton").prop('disabled', true);
}

function loaddata(){
    console.log(item);
    console.log(table);
    $("#datagrid tbody").html('');
    for(i = 0; i < table.length; i++){
        $('#datagrid tbody').append('<tr ondblclick="loadGridData(\''+ table[i]['materialcode'] + '\')">\
            <td><input type="hidden" name="businesscode[]" value="'+ table[i]['businesscode'] + '">\n\
            <input type="hidden" name="locationcode[]" value="'+ table[i]['locationcode'] + '">\n\
            <input type="hidden" name="materialtypecode[]" value="'+ table[i]['materialtypecode'] + '">\n\
            <input type="hidden" name="materialcode[]" value="'+ table[i]['materialcode'] + '">\n\
            <input type="hidden" name="effectivedate[]" value="'+ table[i]['effectivedate'] + '">\n\
            <input type="hidden" name="supplierleadtime[]" value="'+ table[i]['supplierleadtime'] + '">\n\
            '+ table[i]['businesscode'] + ' </td>\n\
            <td> '+ table[i]['locationcode'] + ' </td>\n\
            <td> '+ table[i]['materialtypecode'] + ' </td>\n\
            <td> '+ table[i]['materialcode'] + ' </td>\n\
            <td> '+ table[i]['effectivedate'] + ' </td>\n\
            <td> '+ table[i]['supplierleadtime'] + ' </td>\n\
            <td> <i style="cursor: pointer;" onclick="loadGridData(\''+ table[i]['materialcode'] + '\')" class="glyphicon glyphicon-pencil"></i>\
            <i style="cursor: pointer;" onclick="doDeleteGridData(\''+ table[i]['materialcode'] + '\')" class="glyphicon glyphicon-trash"></i></td>\n\
        </tr>');
    }
    if(table.length > 0){
        $("#submit").css("display","block");
    }
}

function doDeleteGridData(materialcode){
    var table2 = [];
    for(i=0; i<table.length; i++){
        if(table[i]['materialcode'] != materialcode){
            table2[table2.length] = table[i];
        }
    }
    table = table2;
    if(table.length == 0){
        $("#datagrid tbody").html('');
        $("#submit").css("display","none");
    }else{
        loaddata();    
        $("#submit").css("display","block");
    }
    $("#table2").focus();
}

function loadGridData(materialinfo){
    for(i = 0; i < table.length; i++ ) {
       if(table[i]['materialcode'] == materialinfo){
            var businesscode = $("#businesscode").val(table[i]['businesscode']);
            var locationcode = $("#locationcode").val(table[i]['locationcode']);
            var materialtypecode = $("#materialtypecode").val(table[i]['materialtypecode']);
            var materialcode = $("#materialcode").val(table[i]['materialcode']);
            var effectivedate = $("#effectivedate").val(table[i]['effectivedate']);
            var supplierleadtime = $("#supplierleadtime").val(table[i]['supplierleadtime']);
       }
       $("#addbutton").prop('disabled', false);
       $("#materialcode").prop('readonly', true);
    }
}

function doLoadLocation(businesscode){
    $('#locationcode').empty();
    $.ajax({
        type: "POST",
        url: base_url + "location/getlist",
        data: "businesscode=" + businesscode.split('-')[0],               
        dataType: "json",
        cache: false,
        success: function (res) {     
            if(res.length > 1){
                $('#locationcode').append('<option value=""></option>');
            }
            for(i = 0; i < res.length; i++){
                $('#locationcode').append('<option value="'+res[i]['LocationCode']+'">'+res[i]['LocationName']+'</option>');
            }
        },
        error: function (msg) {
           console.log(msg);         
        }
    })
}

function doLoadNotificationBoard(materialcode){
    $.ajax({
        type: "POST",
        url: base_url + "material/notificationboard",
        data: "materialcode=" + materialcode,               
        dataType: "json",
        cache: false,
        success: function (res) {    
            console.log(res);
            $("#notificationboard").css('display','block');
            if(res){
                $("#nb_material").html(res[0]['ProductName']); //res[0]['ProductName']
                $("#nb_category").html(res[0]['SupplyLeadtime']);
                $("#nb_effectivedate").html(res[0]['SEffectiveDate']);
                $("#nb_coolingperiod").html(res[0]['CoolingPeriod']);
            }else{
                $("#notificationboard").html('');
                $("#notificationboard").html('No Supply Lead Time found.');
            }
        },
        error: function (msg) {
           console.log(msg);         
        }
    })
}