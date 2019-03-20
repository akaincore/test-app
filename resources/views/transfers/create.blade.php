@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <span>Create new transfer</span>
                    </div>
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <form action="{{url()->route('transfers.store')}}" method="post">
                            <div class="form-group">
                                <label for="recipient_id">Transfer to:</label>
                                <div class="alert alert-danger d-none alert-user" role="alert">
                                    Invalid user id
                                </div>
                                <input
                                    required
                                    type="text"
                                    class="form-control"
                                    name="recipient_id"
                                    id="recipient_id"
                                    placeholder="Enter user id"
                                    value="{{old('recipient_id')}}"
                                >
                            </div>
                            <div class="form-group">
                                <label for="sum">Transfer sum:</label>
                                <div class="alert alert-danger d-none alert-sum" role="alert">
                                    Invalid sum
                                </div>
                                <input
                                    required
                                    type="text"
                                    class="form-control datetimepicker-input"
                                    data-target="#time"
                                    id="sum"
                                    name="sum"
                                    placeholder="Enter sum"
                                    value="{{old('sum')}}"
                                >
                            </div>
                            <div class="form-group">
                                <label for="time">Choose transfer date and time:</label>
                                <div class="alert alert-danger d-none alert-time" role="alert">
                                    Invalid time
                                </div>
                                <input
                                    required
                                    type="text"
                                    class="form-control datetimepicker-input"
                                    id="time"
                                    name="time"
                                    data-target="#time"
                                    data-toggle="datetimepicker"
                                    placeholder="Click to choose"
                                />
                            </div>
                            @csrf
                            <button type="submit" id="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <script type="text/javascript">
            $(function () {
                function getMinDate() {
                    var dateTime = new Date();
                    return moment(dateTime).add(1, 'minutes').format("YYYY-MM-DD HH");
                }

                $('#time').datetimepicker({
                    locale: 'ru',
                    format: 'YYYY-MM-DD HH',
                    minDate: getMinDate()
                });
                $('#submit').on('click', function () {
                    var user = $('#recipient_id').val(),
                        sum = $('#sum').val(),
                        isValidUser = /^\d*$/.test(user) && user !== '',
                        isValidSum = /^\d*$/.test(sum) && sum !== '',
                        isValidTime = getMinDate() <= $('#time').val();
                    if (!isValidUser) {
                        $('.alert-user').removeClass('d-none');
                    }
                    if (!isValidSum) {
                        $('.alert-sum').removeClass('d-none');
                    }
                    if (!isValidTime) {
                        $('.alert-time').removeClass('d-none');
                    }
                    return isValidUser && isValidSum && isValidTime;
                });
            });
        </script>
    </div>
@endsection
