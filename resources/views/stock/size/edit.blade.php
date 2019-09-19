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
                        <h3>Edit a Size</h3>
                    </div>
                </div>
                <hr>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-3"></div>
            <div class="header-title">
            </div>
            <div class="panel panel-filled">
                <div class="panel-body">
                    <form method="post" action="{{ url('/size/Updatesize') }}" autocomplete="off">
                        {{ csrf_field() }}


                        <div class="form-group">
                            <label for="InputName">Size</label>
                            <input type="text" class="form-control" value="{{$size->size}}"  id="InputName" placeholder="eg : 123x123" name="size" required>
                            <input type="hidden" class="form-control" id="InputName" value="{{$size->id}}" placeholder="eg : 123x123" name="id" required>

                        </div>
                        <button type="submit" class="btn btn-default">Submit</button>

                    </form>
                </div>
            </div>
            <div class="col-sm-3"></div>
        </div>
    </div>



@endsection
