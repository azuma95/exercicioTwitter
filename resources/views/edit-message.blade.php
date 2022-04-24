@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card" style="margin-top: 10px;">
                <div class="card-body">
                    <form action="" method="post" action="{{ route('update-message', $message->id) }}">
                        <h2>Editar mensagem de {{ Auth::user()->name }}</h2>

                        @if(count($errors) > 0)
                            <div class="card-body">
                                <ul>
                                    @foreach($errors->all() as $error)
                                        <span class="text-danger" style="border:1px solid red">* {{ $error }}</span>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if(Session::has('err'))
                            <div class="card-body">
                                <span class="text-success" style="border:1px solid green">* {{ Session::get('err') }} </span>
                            </div>
                        @endif

                        <textarea name="body" rows="3" class="form-control" placeholder="Criar Mensagem"> {{ $message->body }} </textarea>
                        <input type="hidden" value="{{ Session::token() }}" name="_token">
                        <button type="submit" class="btn btn-primary" style="margin-top: 20px;">Editar!</button>
                        <a href="/home" style="margin-left: 20px; padding-top: 25px;">Voltar!</a>
                    </form>
                </div>                        
            </div>              
        </div>
    </div>
</div>

@endsection