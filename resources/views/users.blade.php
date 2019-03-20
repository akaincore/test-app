@extends('layouts.app')

@section('content')
    <div class="container">
        @if($alert = session()->get('alert'))
            <div class="row">
                <div class="col-12">
                    <div class="alert alert-success alert-sum" role="alert">
                        {{$alert}}
                    </div>
                </div>
            </div>
        @endif
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span>Users list</span>
                        <a href="{{url()->route('transfers.create')}}" class="btn btn-primary">
                            Create transfer
                        </a>
                    </div>
                    <div class="card-body">
                        <table class="table table-hover table-bordered">
                            <caption>Users with last transfer</caption>
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Balance</th>
                                <th>Last transfer created at</th>
                                <th>Last transfer scheduled time</th>
                                <th>Last transfer sum</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($users as $user)
                                <tr>
                                    <td>
                                        {{$user->id}}
                                    </td>
                                    <td>
                                        {{$user->name}}
                                    </td>
                                    <td>
                                        {{$user->balance}}
                                    </td>
                                    <td>
                                        {{$user->transfer_created_at ?? 'User has no transfers yet'}}
                                    </td>
                                    <td>
                                        {{$user->transfer_time ?? 'User has no transfers yet'}}
                                    </td>
                                    <td>
                                        {{$user->transfer_sum ?? 'User has no transfers yet'}}
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div class="d-flex justify-content-center">
                            {{$users->links()}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
