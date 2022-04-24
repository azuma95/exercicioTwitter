@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Mensagens') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div>                        
                        <h2>Olá, {{ Auth::user()->name }}</h2>
                    </div>

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

                    <div class="card" style="margin-top: 10px;">
                        <div class="card-body">
                           <form action="" method="post" action="{{ route('postCreateMessage') }}">
                               <h2>Criar nova mensagem</h2>
                               <textarea name="body" rows="3" class="form-control" placeholder="Criar Mensagem"></textarea>
                               <input type="hidden" value="{{ Session::token() }}" name="_token">
                               <button type="submit" class="btn btn-primary" style="margin-top: 20px;">Criar!</button>
                           </form>
                        </div>                        
                    </div>  
                    
                    <div class = "card" style="margin-top: 20px;">

                        @foreach($messages as $message)
                            <div class="card">
                                <div class="card-body">
                                    <div class="card.body">
                                        <h5><a href="/u/{{ $message->user->id }}"> {{ $message->user->name }} </a> </h5>
                                    </div>

                                    <div class="card.body">
                                        {{ $message->body }}
                                    </div>

                                    <div class="card.body">
                                        <small> {{ $message->created_at->diffForHumans() }} </small>
                                    </div>

                                    @if(Auth::user() == $message->user)
                                        <div class="card.body">
                                            <a href="{{ route('getDeletePost', ['message_id' => $message->id]) }}">Apagar</a>
                                            <a href="{{ route('edit-message', $message) }}" style="margin-left: 10px;">Editar</a>
                                        </div>
                                    @endif

                                </div>
                            </div>
                        @endforeach

                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
