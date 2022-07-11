@extends('master')
@section('content')  

@if(session()->has('password'))
<?php 
    if( session()->get('password') == env('LINE_APP_PASSWORD_MISFITS') ) {
?>

    <section class="one">
        <h1>MISFITS</h1>
    </section>
    <section class="two">
        <h1>TIMERS</h1>
    </section>
    <section class="three">
        <h1>ATTENDS</h1>
    </section>
    <section class="four">
        <h1>EDL LIST</h1>
    </section>
    <section class="five">
        <h1>BOT COMMANDS</h1>
    </section>

<?php
    } elseif( session()->get('password') == env('LINE_APP_PASSWORD_ELEMS') ) {
?>

    <section class="one">
        <h1>ELEMENTAL</h1>
    </section>
    <section class="two">
        <h1>TIMERS</h1>
    </section>
    <section class="three">
        <h1>ATTENDS</h1>
    </section>
    <section class="four">
        <h1>EDL LIST</h1>
    </section>
    <section class="five">
        <h1>BOT COMMANDS</h1>
    </section>

<?php
    } else {
?>
    <section class="one">
        <form method="POST" action="{{ url('') }}">
            {{ csrf_field() }}
            {{ method_field('POST') }}
            <div class="formgroup field">
                <input type="password" class="formfield" name="password" placeholder="Clan Password" id="password" value="{{ old('pass') }}" required/>
                <label for="password" class="formlabel">Clan Password</label>
                @if($errors->any('password'))
                    @foreach($errors->get('password') as $error)
                        {{ $error }}<br>
                    @endforeach
                @endif
                <input class="formfield" type="submit" value="Send Password"/>
                <p>Wrong Password! Try again...</p>
            </div>
        </form>
    </section>
<?php
    }
?>
@else
    <section class="one">
        <form method="POST" action="{{ url('') }}">
            {{ csrf_field() }}
            {{ method_field('POST') }}
            <div class="formgroup field">
                <input type="password" class="formfield" name="password" placeholder="Clan Password" id="password" value="{{ old('pass') }}" required/>
                <label for="password" class="formlabel">Clan Password</label>
                @if($errors->any('password'))
                    @foreach($errors->get('password') as $error)
                        {{ $error }}<br>
                    @endforeach
                @endif
                <input class="formfield" type="submit" value="Send Password"/>
            </div>
        </form>
    </section>
    @endif
@endsection