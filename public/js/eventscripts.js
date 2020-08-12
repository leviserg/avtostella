$(document).ready(function(){
    evntTableShow();
    $("#maintab").hide();
});

    function evntTableShow() {
        var oTable = $('#data-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '/events/getevents',
            columns: [
                { data: 'id', name: 'id' },                             // - 0
                { data: 'appeared',
                    name: 'appeared',
                    "render": function ( data, type, full, meta ) {
                        if(data != null){
                            return moment(data).format('DD.MM.YYYY HH:mm:ss');
                        }
                        else{
                            return null;
                        }
                    }
                },                 // - 1
                { data: 'descr', name: 'descr' },                       // - 2
                { data: 'categ', name: 'categ' },                       // - 3
            ],
            aoColumnDefs:[
                {
                    "searchable": false,
                    "aTargets": [0,1,3]
                },
                {
                    "orderable": false,
                    "aTargets": [2]
                },
                {
                    "visible": false,
                    "aTargets": [0]
                },
                {
                    targets: [0,1,3],
                    className: 'dt-body-center'
                }
            ],
            language: {
                search: "Поиск в описании : ",
                processing:     "Загружаю данные...",
                lengthMenu:    "<b class='ml-3'>СОБЫТИЯ. </b>Отображать _MENU_ записей",
                info:           "Отображается от _START_ до _END_ из _TOTAL_ записей",
                infoEmpty:      "Найдено от 0 до 0 из 0 записей",
                infoFiltered:   "(фильтр из _MAX_ записей всего)",
                infoPostFix:    "",
                loadingRecords: "Ожидаю загрузки...",
                zeroRecords:    "Не найдено записей",
                emptyTable:     "Отсутствуют данные для таблицы",
                paginate: {
                    first:      "Начало",
                    previous:   "Пред",
                    next:       "След",
                    last:       "Конец"
                },
            },
            "lengthMenu": [ 15, 30, 60, 120 ],
            "createdRow": function ( row, data, index ) {
                let obj = data;
                if ( obj.categ == "Авария") {
                    $('td', row).eq(0).addClass('text-danger font-weight-bold');
                    $('td', row).eq(1).addClass('text-danger font-weight-bold');
                    $('td', row).eq(2).addClass('text-danger font-weight-bold');
                } else if ( obj.categ == "Предупрежд") {
                    $('td', row).eq(0).addClass('text-warning');
                    $('td', row).eq(1).addClass('text-warning');
                    $('td', row).eq(2).addClass('text-warning');
                }
            },
            "order": [ 0, 'desc' ],

        });

        $('#data-table tbody').on( 'click', 'tr', function () {
            if ( $(this).hasClass('selected') ) {
                $(this).removeClass('selected');
            }
            else {
                oTable.$('.selected').removeClass('selected');
                $(this).addClass('selected');
            }
        });

        setInterval( function () {
            oTable.ajax.reload( null, false );
        }, 60000 );
    }