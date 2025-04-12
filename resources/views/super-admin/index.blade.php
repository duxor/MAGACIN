@extends('super-admin.master.osnovni')
@section('content')
    Super Administrator 1.0
    <table class="table">
    @foreach(\App\Log::orderBy('created_at','desc')->get() as $p)
        <tr><td>{{$p->ip}}</td><td>{{$p->korisnici_id}}</td><td>{{$p->created_at}}</td></tr>
    @endforeach
    </table>
@endsection