<?php
/*@if(count($errors) > 0)
    @foreach($errors->all() as $error)
        <div class="alert alert-danger">
            {{$error}}
        </div>
    @endforeach
@endif
 */
?>

@if(session('success'))
    <div style="position:fixed; bottom:100px; right: 20px;z-index:99999;opacity:1;display:none" class="toast ml-auto" role="alert" data-autohide="false">
        <div class="toast-header">
            <strong class="mr-auto text-success">Success</strong>
            <small class="text-muted">Just now</small>
            <button type="button" class="ml-2 mb-1 close close-toast" data-dismiss="toast" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="toast-body bg-success text-white">{{session('success')}}</div>
    </div>
@endif

@if(session('error'))
    <div style="position:fixed; bottom:80px; right: 20px;z-index:99999;opacity:1;display:none" class="toast ml-auto" role="alert" data-autohide="false">
        <div class="toast-header">
            <strong class="mr-auto text-danger">Error</strong>
            <small class="text-muted">Just now</small>
            <button type="button" class="ml-2 mb-1 close close-toast" data-dismiss="toast" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="toast-body bg-danger text-white">{{session('error')}}</div>
    </div>
@endif

