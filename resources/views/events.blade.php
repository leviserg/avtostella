@extends('layouts.app')
@section('content')
    <div class="container-fluid" style="width:90%">
        <table class="table row-border hover" style="width:100%" id="data-table" data-page-length='30'><!-- table-bordered stripe-->
            <thead>
                <tr>
                    <th class="fit">ID</th>
                    <th class="fit">Время Возникновен.</th>
                    <th data-class-name="priority">Описание</th>
                    <th class="fit-wide">Категория</th>
                </tr>
            </thead>
        </table>
    </div>
@endsection
@push('scripts')
    <script src="{{ asset('js/eventscripts.js') }}"></script>
@endpush
