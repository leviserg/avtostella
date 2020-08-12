@extends('layouts.app')
@section('content')
    <div class="container-fluid mt-4" style="width:93%">
        <div class="row">
            <div class="col-md-9 mb-2" id="chartdiv" style="height:800px; width:100%"></div>
            <div class="col-md-3 pr-4">
                <div class="card" style="height:800px; background:#F7F7F7">
                    <div class="card card-header text-center text-info" style="font-size:20px">Выбор графиков</div>
                    <div class="card card-body">
                        <div class="form-group">
                            <label for="conv" class="control-label text-info">Выбрать конвеер </label>
                            <select class="form-control  mb-2" name="conv" id="conv">
                                <option value="51" selected="selected">Конвеер O-5A</option>
                                <option value="52">Конвеер O-5Б</option>
                                <option value="61">Конвеер O-6A</option>
                                <option value="62">Конвеер O-6Б</option>
                            </select>
                            <label for="section" class="control-label text-info">Выбрать секцию</label>
                            <select class="form-control  mb-2" name="section" id="section">
                                @foreach($sectionlist as $section)
                                    @if($section->section == $firstsection->section)
                                        <option value="{{$section->section}}" selected="selected" >Секция {{$section->section}}</option>
                                    @else
                                        <option value="{{$section->section}}">Секция {{$section->section}}</option>
                                    @endif
                                @endforeach
                            </select>
                            <hr class="mt-4 mb-4"/>
                            <p for="sensorslist" class="control-label text-info">Выбрать ось</p>
                            <div class="container ml-3" id="sensorslist">
                                @foreach($axeslist as $axe)
                                    <label class="checkcontainer">Ось {{$axe->axeid}}. ID метки {{$axe->sensorid}}
                                        <input type="radio" name="sensors" value="{{$axe->sensorid}}" data-id="{{$axe->axeid}}">
                                        <span class="checkmark"></span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                        <hr/>
                        <label for="trtype" class="control-label text-info">Тип линии</label>
                        <select class="form-control  mb-2" name="trtype" id="trtype">
                            <option value="0" selected="selected" >Прямая</option>
                            <option value="2">Ступенчатая</option>
                            <option value="1">Сглаженная</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="{{ asset('js/trendscripts.js') }}"></script>
@endpush
