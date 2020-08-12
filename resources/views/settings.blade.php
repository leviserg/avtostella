@extends('layouts.app')
@section('content')
    <h4 class="text-center mt-2 text-info">Системные настройки</h4>
    <div class="container mt-3 px-0 py-0 bg-white table-responsive">
        <table class="table row-border table-hover table-bordered table-striped mb-0" style="width:100%" id="data-table"><!-- table-bordered stripe-->
            <thead class="text-info bg-light">
                <tr>
                    <th class="fit text-center">ID</th>
                    <th class="fit-wide text-center">Название</th>
                    <th class="fit-wide text-center">ПЛК 1</th>
                    <th class="fit-wide text-center">ПЛК 2</th>
                    <th class="fit-wide text-center">ПЛК 3</th>
                    <th class="fit-wide text-center">ПЛК 4</th>
                    <th class="fit text-center">Действие</th>
                </tr>
            </thead>
            <tbody>
                @foreach($settings as $setting)
                    <tr>
                        <td class="dt-body-center text-center">{{$setting->id}}</td>
                        <td class="dt-body-center">{{$setting->name}}</td>
                        <td class="dt-body-center text-center">{{$setting->setting_plc1}}</td>
                        <td class="dt-body-center text-center">{{$setting->setting_plc2}}</td>
                        <td class="dt-body-center text-center">{{$setting->setting_plc3}}</td>
                        <td class="dt-body-center text-center">{{$setting->setting_plc4}}</td>
                        <td>
                            <button data-id="{{$setting->id}}" class="btn btn-xs btn-info py-0 px-2 ml-4 showsett">
                                Изменить
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="modal modal-fade mt-5" id="selected_setting" tabindex="2">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header mb-0 mt-0">
                    <h5 class="modal-title" id="settingTitle"></h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container">
                        <form name="savesett" method="post" enctype="multipart/form-data" id="settform">
                            {{csrf_field()}}
                            <div class="form-group">
                                <label for="setting_plc1" class="small">ПЛК 1:</label>
                                <input class="form-control" id="setting_plc1" name="sett_plc1" type="number" min="0.00" step="0.01">
                            </div>
                            <div class="form-group">
                                <label for="setting_plc2" class="small">ПЛК 2:</label>
                                <input class="form-control" id="setting_plc2" name="sett_plc2" type="number" min="0.00" step="0.01">
                            </div>
                            <div class="form-group">
                                <label for="setting_plc3" class="small">ПЛК 3:</label>
                                <input class="form-control" id="setting_plc3" name="sett_plc3" type="number" min="0.00" step="0.01">
                            </div>
                            <div class="form-group">
                                <label for="setting_plc4" class="small">ПЛК 4:</label>
                                <input class="form-control" id="setting_plc4" name="sett_plc4" type="number" min="0.00" step="0.01">
                            </div>
                            <hr/>
                            <button class="btn btn-primary mt-1 mb-2 ml-2" style="width:45%" type="button" data-dismiss="modal" aria-label="Close">Назад</button>
                            <input class="btn btn-info mt-1 mb-2 ml-2" style="width:45%" type="submit" value="Сохранить">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>

        $(document).ready(function(){
            //showSettings();
            updateActUnackCount();
        });

        $(".showsett").bind("click",function(elem){
            $target = $(elem.target);
            const id = $target.attr('data-id');
            showSelected(id);
        });


        function showSelected(id){
            var url = "../settings/getsetting/" + id;
            var sel = {id:id};
            var action = function(data){
                data = JSON.parse(data);
                    $("#setting_plc1").val(data.setting_plc1);
                    $("#setting_plc2").val(data.setting_plc2);
                    $("#setting_plc3").val(data.setting_plc3);
                    $("#setting_plc4").val(data.setting_plc4);
                    $('#settingTitle').text(data.name);
                    $('#selected_setting').modal("show");
                    $('#settform').attr('action', "../settings/setsetting/" + data.id);
                };
            $.get(url,sel,action);
        }

        function updateActUnackCount(){
                $.ajax({
                    type:'GET',
                    url:"../avtostella/alarms/getactunack",
                    cache: false,
                    crossDomain: true,
                    contentType: 'application/json; charset=utf-8',
                    dataType: 'json',
                    success: function(data){
                        //console.log('Updated at ' + new Date().toLocaleTimeString());
                        //let act = data[0];
                        //let unack = data[1];
                        //$('#almsum').text(act);
                        let unack = data;
                        $('#unacksum').text(unack);
                    },
                    error: function(err){
                        console.log(err);
                    }
                });
            }


    </script>
@endpush
