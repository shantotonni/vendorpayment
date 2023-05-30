var table = [];
var item = [];
var duplicatateindex;


function doLoadExcelData(exceldata){
    if(exceldata && exceldata.length > 0){ 
        //console.log(exceldata);
        for(i = 0; i < exceldata.length; i++){
            productcode                         = exceldata[i]['productcode'];
            productname                         = exceldata[i]['productname'];
            categoryid                          = exceldata[i]['categoryid'];
            categoryname                        = exceldata[i]['categoryname'];
            lineid                              = exceldata[i]['lineid'];
            linename                            = exceldata[i]['linename'];            
            regularcapacityproduction           = exceldata[i]['regularcapacityproduction'];
            regularcapacityproductionhour       = exceldata[i]['regularcapacityproductionhour'];
            minimumcapacityproduction           = exceldata[i]['minimumcapacityproduction'];
            minimumcapacityproductionhour       = exceldata[i]['minimumcapacityproductionhour'];
            maximumcapacityproductionsinglesku  = exceldata[i]['maximumcapacityproductionsinglesku'];
            maximumcapacityproduction5sku       = exceldata[i]['maximumcapacityproduction5sku'];
            maximumcapacityproductionhour       = exceldata[i]['maximumcapacityproductionhour'];
            
            item.splice(table.length, 0, productcode);
            table.push({"productcode": productcode,"productname": productname
                ,"categoryid": categoryid,"categoryname": categoryname
                , "lineid": lineid , "linename": linename
                , "regularcapacityproduction": regularcapacityproduction
                , "regularcapacityproductionhour" : regularcapacityproductionhour
                , "minimumcapacityproduction": minimumcapacityproduction, "minimumcapacityproductionhour": minimumcapacityproductionhour
                , "maximumcapacityproductionsinglesku": maximumcapacityproductionsinglesku, "maximumcapacityproduction5sku": maximumcapacityproduction5sku
                , "maximumcapacityproductionhour": maximumcapacityproductionhour });
        }
        loaddata();
    }
}

 //document.getElementById("businesscode").focus();


function loaddata(){
    $("#datagrid tbody").html('');
    for(i = 0; i < table.length; i++){
        $('#datagrid tbody').append('<tr>\
            <td><input type="hidden" name="productcode[]" value="'+ table[i]['productcode'] + '">\n\
            <input type="hidden" name="productname[]" value="'+ table[i]['productname'] + '">\n\
            <input type="hidden" name="categoryid[]" value="'+ table[i]['categoryid'] + '">\n\
            <input type="hidden" name="categoryname[]" value="'+ table[i]['categoryname'] + '">\n\
            <input type="hidden" name="lineid[]" value="'+ table[i]['lineid'] + '">\n\
            <input type="hidden" name="linename[]" value="'+ table[i]['linename'] + '">\n\
            <input type="hidden" name="regularcapacityproduction[]" value="'+ table[i]['regularcapacityproduction'] + '">\n\
            <input type="hidden" name="regularcapacityproductionhour[]" value="'+ table[i]['regularcapacityproductionhour'] + '">\n\
            <input type="hidden" name="minimumcapacityproduction[]" value="'+ table[i]['minimumcapacityproduction'] + '">\n\
            <input type="hidden" name="minimumcapacityproductionhour[]" value="'+ table[i]['minimumcapacityproductionhour'] + '">\n\
            <input type="hidden" name="maximumcapacityproductionsinglesku[]" value="'+ table[i]['maximumcapacityproductionsinglesku'] + '">\n\
            <input type="hidden" name="maximumcapacityproduction5sku[]" value="'+ table[i]['maximumcapacityproduction5sku'] + '">\n\
            <input type="hidden" name="maximumcapacityproductionhour[]" value="'+ table[i]['maximumcapacityproductionhour'] + '">\n\
            '+ table[i]['productcode'] + ' </td>\n\
            <td> '+ table[i]['productname'] + ' </td>\n\
            <td> '+ table[i]['categoryid'] + ' </td>\n\
            <td> '+ table[i]['categoryname'] + ' </td>\n\
            <td> '+ table[i]['lineid'] + ' </td>\n\
            <td> '+ table[i]['linename'] + ' </td>\n\
            <td> '+ table[i]['regularcapacityproduction'] + ' </td>\n\
            <td> '+ table[i]['regularcapacityproductionhour'] + ' </td>\n\
            <td> '+ table[i]['minimumcapacityproduction'] + ' </td>\n\
            <td> '+ table[i]['minimumcapacityproductionhour'] + ' </td>\n\
            <td> '+ table[i]['maximumcapacityproductionsinglesku'] + ' </td>\n\
            <td> '+ table[i]['maximumcapacityproduction5sku'] + ' </td>\n\
            <td> '+ table[i]['maximumcapacityproductionhour'] + ' </td>\n\
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
