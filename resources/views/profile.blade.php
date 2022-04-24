@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ $user->name }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div>                        
                        <h2>Mensagens do  {{ $user->name }}</h2>
                    </div> 
                    
                    <div class = "card" style="margin-top: 20px;">

                        @foreach($user->messages as $message)
                            <div class="card">
                                <div class="card-body">
                                    <div class="card.body">
                                        <h5>{{ $message->user->name }}</h5>
                                    </div>

                                    <div class="card.body">
                                        {{ $message->body }}
                                    </div>

                                    <div class="card.body">
                                        <small> {{ $message->created_at->diffForHumans() }} </small>
                                    </div>

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
