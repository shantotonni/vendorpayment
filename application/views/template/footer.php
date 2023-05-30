

<!-- JAVASCRIPT FILES -->
<script type="text/javascript">var plugin_path = '<?php echo base_url(); ?>assets/plugins/';</script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/sarah.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/app.js"></script>

<!-- PAGE LEVEL SCRIPTS -->
<script type="text/javascript">
loadScript(plugin_path + "datatables/js/jquery.dataTables.min.js", function(){
    loadScript(plugin_path + "datatables/js/dataTables.tableTools.min.js", function(){
        loadScript(plugin_path + "datatables/js/dataTables.colReorder.min.js", function(){
            loadScript(plugin_path + "datatables/js/dataTables.scroller.min.js", function(){
                loadScript(plugin_path + "datatables/dataTables.bootstrap.js", function(){
                    loadScript(plugin_path + "select2/js/select2.full.min.js", function(){
                        loadScript(plugin_path + "datatables/dataTables.fixedHeader.min.js", function(){
                            if (jQuery().dataTable) {
                                // Datatable with TableTools
                                function initdoctorbrandtable() {
                                    var table = jQuery('#tableformat');
                                    /* Table tools samples: https://www.datatables.net/release-datatables/extras/TableTools/ */
                                    /* Set tabletools buttons and button container */
                                    jQuery.extend(true, jQuery.fn.DataTable.TableTools.classes, {
                                        "container": "btn-group tabletools-btn-group pull-right",
                                        "buttons": {
                                            "normal": "btn btn-sm btn-default",
                                            "disabled": "btn btn-sm btn-default disabled"
                                        }
                                    });
                                    //table.buttons().disable();
                                    var oTable = table.dataTable({
                                        "order": [],
                                        "columnDefs": [
                                        ],
                                        scrollY: "400px",
                                        scrollX: true,
                                        scrollCollapse: true,
                                        paging: false,
                                        "searching": true,
                                        "pageLength": 10,
                                        "bSort": false,
                                        "fixedHeader": true,
                                        "dom": "<'row' <'col-md-12'T>><'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>", // horizobtal scrollable datatable                                                
                                    });
                                }
                                initdoctorbrandtable();
                                
                            }
                        });
                    });
                });
            });
        });
    });
});
$(document).ready(function(){
    //$('[data-toggle="tooltip"]').tooltip(); 
});
</script>

<script src="<?php echo base_url(); ?>assets/plugins/datepicker/jquery-ui.js"></script>
<script>
$( function() {
    $( "#datefrom" ).datepicker({ dateFormat: 'yy-mm-dd' });
    $( "#dateto" ).datepicker({ dateFormat: 'yy-mm-dd' });
    
    $( "#periodfrom" ).datepicker({ 
        changeMonth: true,
        changeYear: true,
        showButtonPanel: true,
        dateFormat: 'MM yy',
        onClose: function(dateText, inst) { 
            var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
            var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
            $(this).datepicker('setDate', new Date(year, month, 1));
        }
    });
    
    $( "#periodto" ).datepicker({ 
        changeMonth: true,
        changeYear: true,
        showButtonPanel: true,
        dateFormat: 'MM yy',
        onClose: function(dateText, inst) { 
            var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
            var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
            $(this).datepicker('setDate', new Date(year, month, 1));
        }
    });
     
    
} );
</script>