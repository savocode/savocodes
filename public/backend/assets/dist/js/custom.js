var appConfig = (function(){

    'use strict';

    var init = function()
    {
        if ( $('.datatable').length ) {
            var dataTable = $('.datatable').DataTable({
                "aoColumnDefs": [{
                    "bSortable": false,
                    "aTargets": appConfig.get( 'dt.aoColumnDefs.aTargets', [ -1 ] )
                }],
                "order": appConfig.get( 'dt.order', [[ 0, 'asc']] ),
                "searching": appConfig.get( 'dt.searching', false )
            });

            appConfig.set('app.dataTable', dataTable)
        }

        if ( $('.yajrabox').length ) {
            var yajraDataTable = $('.yajrabox').DataTable({
                order: appConfig.get( 'dt.order', [[ 1, 'asc']] ),
                searching: appConfig.get( 'dt.searching', true ),
                serverSide: true,
                processing: true,
                ajax: {
                    url: appConfig.get( 'yajrabox.ajax' ),
                    data: appConfig.get( 'yajrabox.ajax.data', function(data) {}),
                },
                columns: appConfig.get( 'yajrabox.columns' ),
                buttons: ['csv', 'excel'],
                drawCallback: function(settings) {
                    $(".cboxImages").colorbox({rel:'cboxImages', maxWidth:"100%", maxHeight:"100%"});
                }
            });

            appConfig.set('app.yajraDataTable', yajraDataTable)
        }

        if ( $('.cboxImages').length ) {
            $(".cboxImages").colorbox({rel:'cboxImages', maxWidth:"100%", maxHeight:"100%", photo:true});
        }

        if ( $('.select2').length ) {
            $(".select2").select2()
        }

        if ( $('.ckeditor').length ) {
            $('.ckeditor').each(function(i,v) {
                CKEDITOR.replace( $(v).attr('id') || this );
            })
        }

        if ( $('.icon-picker').length ) {
            $('.icon-picker').qlIconPicker({
                'size': 'large',
                'classes': {
                    'launcher': 'btn btn-primary',
                    'clear': 'remove-times',
                }
            })
        }

        if ( $('.date-range-picker').length ) {
            var dateRangePickerArray = [];
            $('.date-range-picker').each(function(i,v) {
                var selector = $(v).attr('id') ? '#'+$(v).attr('id') : '.date-range-picker';
                dateRangePickerArray[ selector ] = $(selector).daterangepicker()
            });

            appConfig.set('app.daterangepicker', dateRangePickerArray)
        }

        $(document).on('keyup', '.numberOnly', function() {
            var val = $(this).val();
            if ( val.match(/[^\d]+/) ) {
                $(this).val( $(this).val().replace(/[^\d]+/, '') );
            }
        })

        if ( $('.chosen').length ) {
            $('.chosen').chosen();
        }

        if ( $('.datepicker').length ) {
            $('.datepicker').datepicker({
                format: 'yyyy-mm-dd'
            }).on('changeDate', function(e){
                $(this).datepicker('hide');
            });
        }

        $(document).on('click', '.filter-data, .clear-data, .delete-data', function(e) {
            e.preventDefault();

            if($(this).hasClass('clear-data')) {
                $('.grid-filter .form-control').val("").trigger("chosen:updated");
                appConfig.get('app.yajraDataTable').draw();
            }

            if($(this).hasClass('filter-data')) {
                appConfig.get('app.yajraDataTable').draw();
            }
        });

        // A-Sync/Lazy execution
        $(document).trigger('appConfig.initialized', appConfig)
    }

    return {
        set: function(key, value) {
            this[ key ] = value
        },

        get: function(key, defaultValue) {
            return this.hasOwnProperty(key) ? this[ key ] : defaultValue
            // return this[ key ] || defaultValue
        },

        init: function(){init()}
    }
})();

$(function () {
    appConfig.init();
});
