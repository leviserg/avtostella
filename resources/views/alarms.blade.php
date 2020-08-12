@extends('layouts.app')
@section('content')
    <div class="container-fluid" style="width:90%">
        <table class="table row-border hover" style="width:100%" id="data-table" data-page-length='15'><!-- table-bordered stripe-->
            <thead>
                <tr>
                    <th class="fit">ID</th>
                    <th class="fit">Время Возникновен.</th>
                    <th data-class-name="priority">Описание</th>
                    <th class="fit-wide">Категория</th>
                    <th class="fit-wide">Статус</th>
                    <th class="fit">Время Квитирования</th>
                    <th class="fit">Квитировано</th>
                    <th class="fit">Действие</th>
                </tr>
            </thead>
        </table>
    </div>
@endsection
@push('scripts')
    <script>
        
        $(document).ready(function(){
            almTableShow();
            $("#maintab").hide();
        });

        function almTableShow() {
            var oTable = $('#data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '/alarms/getalarms',
                columns: [
                    { data: 'id', name: 'id' },                             // - 0
                    { data: 'appeared',
                        name: 'appeared',
                        "render": function ( data, type, full, meta ) {
                            return moment(data).format('DD.MM.YYYY HH:mm:ss');
                        }
                    },                 // - 1
                    { data: 'descr', name: 'descr' },                       // - 2
                    { data: 'categ', name: 'categ' },                       // - 3
                    { data: 'status', name: 'status' },                     // - 4
                    { data: 'acknowledged',
                        name: 'acknowledged',
                        "render": function ( data, type, full, meta ) {
                            if(data != null){
                                return moment(data).format('DD.MM.YYYY HH:mm:ss');
                            }
                            else{
                                return null;
                            }
                        }
                    },         // - 5
                    { data: 'acknowledgedby', name: 'acknowledgedby' },     // - 6
                    {
                        'data': {
                            'acknowledged':'acknowledged',
                            'id':'id'
                        },
                        'name': 'id',
                        'render': function(data, type, row, meta){
                            if(type === 'display'){
                                if(data.acknowledged == null){
                                    data = '<small><button type="submit" class="btn btn-xs btn-danger ack pt-0 pb-1" data-id="'+data.id+'" id="'+data.id+'">Квитировать</button><small>';
                                }
                                else{
                                    data = '<button class="btn btn-xs btn-secondary disabled pt-0 pb-1" disabled><small>Квитировано<small></button>';
                                }
                            }
                            return data;
                        }
                    }
                ],
                aoColumnDefs:[
                    {
                        "searchable": false,
                        "aTargets": [0,1,3,4,5,7]
                    },
                    {
                        "orderable": false,
                        "aTargets": [2,7]
                    },
                    {
                        "visible": false,
                        "aTargets": [0]
                    },
                    {
                        targets: [0,1,3,4,5,6,7],
                        className: 'dt-body-center'
                    }
                ],
                language: {
                    search: "Поиск в описании : ",
                    processing:     "Загружаю данные...",
                    lengthMenu:     "<b class='ml-3'>АВАРИИ. </b>Отображать _MENU_ записей",
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
                    var obj = data;
                    if ( obj.categ == "Авария") {
                        $('td', row).eq(0).addClass('text-danger font-weight-bold');
                        $('td', row).eq(1).addClass('text-danger font-weight-bold');
                        $('td', row).eq(2).addClass('text-danger font-weight-bold');
                        $('td', row).eq(3).addClass('text-danger font-weight-bold');
                    } else if ( obj.categ == "Предупрежд") {
                        $('td', row).eq(0).addClass('text-warning font-weight-bold');
                        $('td', row).eq(1).addClass('text-warning font-weight-bold');
                        $('td', row).eq(2).addClass('text-warning font-weight-bold');
                        $('td', row).eq(3).addClass('text-warning font-weight-bold');
                    }
                    if ( obj.acknowledged != null ) {
                        $('td', row).eq(0).removeClass('font-weight-bold');
                        $('td', row).eq(1).removeClass('font-weight-bold');
                        $('td', row).eq(2).removeClass('font-weight-bold');
                        $('td', row).eq(3).removeClass('font-weight-bold');
                    }
                },
                "order": [ 0, 'desc' ],
            });

            $('#data-table tbody').on('click', '.ack', function(elem){
                $target = $(elem.target);
                const id = $target.attr('data-id');
                if(id){
                    $.ajax({
                        type:'POST',
                        url:'/alarms/ack/' + id + '?_token={{ csrf_token() }}',
                        cache: false,
                        crossDomain: true,
                        contentType: 'application/json; charset=utf-8',
                        dataType: 'xml',
                        success: function(){
                            swal("Квитирование события...", "Нажмите ОК для продолжения", "success")
                            .then(function(value){
                                oTable.ajax.reload( null, false );
                                getActUnackCount();
                            });
                        },
                        error: function(err){
                            console.log(err);
                        }
                    });
                }
                else{
                    swal("Не могу квитировать...", "Попробуйте еще раз.", "warning");
                }

            });

            setInterval( function () {
                oTable.ajax.reload( null, false );
            }, 60000 );
        }
        
    </script>
@endpush
