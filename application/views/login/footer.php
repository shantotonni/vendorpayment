<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/plugins/jquery/jquery-2.1.4.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->

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
                                    function initTable1() {
                                        var table = jQuery('#sample_1');
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
                                            "order": [
                                                [0, 'asc']
                                            ],
                                            "lengthMenu": [
                                                [5, 10, 20, 30, -1],
                                                [5, 10, 20, 30, "All"] // change per page values here
                                            ],

                                            // set the initial value
                                            "pageLength": 10,
                                            "bSort" : false,
                                            "fixedHeader" : false,
                                            "dom": "<'row' <'col-md-12'T>><'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>", // horizobtal scrollable datatable                                                
                                        });

                                        var tableWrapper = jQuery('#sample_1_wrapper'); // datatable creates the table wrapper by adding with id {your_table_jd}_wrapper
                                        tableWrapper.find('.dataTables_length select').select2(); // initialize select2 dropdown
                                    } 
                                    initTable1();
                                }
                            });
                        });
                    });
                });
            });
        });
    });
    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip(); 
    });
</script>
