@extends('radnik.master.osnovni')

@section('content')
    <p class="col-sm-12">Magacin v1.0</p>
    <br><hr>
    <div id="work-place"></div>
    <script>
        $(function(){pretraga('',true);fakturisanje.setToken('{{csrf_token()}}')})
        $(document).keypress(function(e){ if(e.which == 13) pretraga()})
    </script>
    <i class="icon-spin6 animate-spin" style="font-size: 1px;color:rgba(0,0,0,0)"></i>
@endsection