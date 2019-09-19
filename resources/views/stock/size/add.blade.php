@extends('admin.master')

@section('content')
    <div class="container-fluid">


        <div class="row">
            <div class="col-lg-12">
                <div class="view-header">
                    <div class="pull-right text-right" style="line-height: 14px">
                    </div>
                    <div class="header-icon">
                        <i class="pe page-header-icon pe-7s-note2"></i>
                    </div>
                    <div class="header-title">
                        <h3>Size</h3>
                    </div>
                </div>
                <hr>
            </div>
        </div>

        <div class="row">
                <div class="col-md-6">
                    <div class="panel panel-filled">
                        <div class="panel-heading">
                            Size list
                        </div>
                        <div class="panel-body">
                            <p>A list of all sizes available in the stock.</p>
                            <div class="bs-example">
                                <ol>
                                    @if(count($size)>0)
                                        @foreach($size as $sizes)
                                        <li>{{$sizes->size}} <a href="/stock/size/{{$sizes->id}}/edit"> <span style="font-size: 13px !important;" class="pe-7s-note"></span></a> &nbsp;
                                        </li>
                                        @endforeach
                                    @else
                                        <li>No sizes exist</li>
                                    @endif
                                </ol>
                            </div>

                        </div>
                    </div>
                </div>
            <div class="col-md-6">
                <div class="header-title">
                </div>
                <div class="panel panel-filled">
                    <div class="panel-body">
                        <form method="post" action="{{ url('/size/postsize') }}" autocomplete="off">
                            {{ csrf_field() }}


                            <div class="form-group">
                                <label for="InputName">Size</label>
                                <input type="text" class="form-control" id="InputName" placeholder="eg : 123x123" name="size" required>
                            </div>
                            <button type="submit" class="btn btn-default">Submit</button>

                        </form>
                    </div>
                </div>

            </div>
            {{--<div class="col-sm-3"></div>--}}
        </div>
    </div>



@endsection
