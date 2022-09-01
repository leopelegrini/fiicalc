@if(session()->has('alert'))
	<div class="nv-section">
    <div class="alert alert-{{ session()->get('alert.status') }} alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
		<b>{{ session()->get('alert.message') }}</b>
        @if(session()->has('alert.undo'))
            <a href="{{ session()->get('alert.undo') }}" class="nv-btn nv-btn-sm nv-btn-success">
                <span class="fa fa-undo btn-icon-left"></span>Desfazer
            </a>
        @endif
        @if(session()->has('alert.button'))
            <a href="{{ session()->get('alert.button.url') }}" class="nv-btn nv-btn-sm nv-btn-{!! (session()->has('alert.button.status')) ? session()->get('alert.button.status') : 'primary' !!}">
                @if(session()->has('alert.button.icon'))
                    <span class="{{ session()->get('alert.button.icon') }} btn-icon-left"></span>
                @endif
                {{ session()->get('alert.button.label') }}
            </a>
        @endif
    </div>
    </div>
@endif
