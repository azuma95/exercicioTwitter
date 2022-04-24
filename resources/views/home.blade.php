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
                               <h2 style="margin-top:10px;">Criar nova mensagem</h2>
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

                                    <div class="card.body">
                                        @if(Auth::user() == $message->user)                                        
                                                <a href="{{ route('getDeletePost', ['message_id' => $message->id]) }}">Apagar</a>
                                                <a href="{{ route('edit-message', $message) }}" style="margin-left: 10px;">Editar</a>                                        
                                        @endif
                                        
                                        <div class="card" style="margin-top: 15px;">
                                            <div class="card-body" style="text-align: center;">
                                                <a href="{{ route('moveMessageUp', ['message_id' => $message->id]) }}" style="margin-right:25px;"> Mover para cima </a>
                                                <a href="{{ route('moveMessageDown', ['message_id' => $message->id]) }}" style="margin-left:25px;"> Mover para baixo</a>
                                            </div>  
                                        </div>                                                                              
                                    </div>
                                </div>
                            </div>
                        @endforeach

                    </div>

                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">

                <div class="card-header">
                    Notificações
                </div>

                <div class="card-body">
                    @foreach(Auth::user()->notifications as $notification)
                        <div class="card"> 
                            <h5 style="margin-top:15px; margin-left:10px;"><a href="/u/{{ $notification->data['user_id'] }}"> {{ $notification->data['user_name'] }} é agora teu seguidor! </a></h5>
                            <p style="margin-left:10px;">{{ $notification->created_at->diffForHumans() }}</p>
                        </div>
                    @endforeach
                </div>

            </div>
        </div>

    </div>
</div>
@endsection
