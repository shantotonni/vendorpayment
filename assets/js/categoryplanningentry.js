var table = [];
var item = [];
var duplicatateindex;


function doLoadExcelData(exceldata){
    if(exceldata && exceldata.length > 0){ 
        //console.log(exceldata);
        for(i = 0; i < exceldata.length; i++){
            categoryid = exceldata[i]['categoryid'];
            categoryname = exceldata[i]['categoryname'];
            lineid = exceldata[i]['lineid'];
            linename = exceldata[i]['linename'];
            capacity = exceldata[i]['capacity'];
            lineproductionhour = exceldata[i]['lineproductionhour'];
            
            item.splice(table.length, 0, categoryid + '_' + lineid);
            table.push({"categoryid": categoryid,"categoryname": categoryname
                ,"lineid": lineid,"linename": linename
                , "capacity": capacity
                , "lineproductionhour": lineproductionhour});
        }
        loaddata();
    }
}

 //document.getElementById("businesscode").focus();


function loaddata(){
    $("#datagrid tbody").html('');
    for(i = 0; i < table.length; i++){
        $('#datagrid tbody').append('<tr>\
            <td><input type="hidden" name="categoryid[]" value="'+ table[i]['categoryid'] + '">\n\
            <input type="hidden" name="lineid[]" value="'+ table[i]['lineid'] + '">\n\
            <input type="hidden" name="capacity[]" value="'+ table[i]['capacity'] + '">\n\
            <input type="hidden" name="lineproductionhour[]" value="'+ table[i]['lineproductionhour'] + '">\n\
            '+ table[i]['categoryid'] + ' </td>\n\
            <td> '+ table[i]['categoryname'] + ' </td>\n\
            <td> '+ table[i]['lineid'] + ' </td>\n\
            <td> '+ table[i]['linename'] + ' </td>\n\
            <td> '+ table[i]['capacity'] + ' </td>\n\
            <td> '+ table[i]['lineproductionhour'] + ' </td>\n\
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
            var businesscode        = $("#businesscode").val(table[i]['businesscode']);
            var locationcode        = $("#locationcode").val(table[i]['locationcode']);
            var materialtypecode    = $("#materialtypecode").val(table[i]['materialtypecode']);
            var materialcode        = $("#materialcode").val(table[i]['materialcode']);
            var effectivedate       = $("#effectivedate").val(table[i]['effectivedate']);
            var buffersize          = $("#buffersize").val(table[i]['buffersize']);
       }
       $("#addbutton").prop('disabled', false);
       $("#materialcode").prop('readonly', true);
    }
}
