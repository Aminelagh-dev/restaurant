@if (session('success'))
    <div class="toast ok" role="status">
        <span class="toast-ico"><x-icon name="check" size="13" stroke="2.4" /></span>
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="toast err" role="alert">
        <span class="toast-ico"><x-icon name="x" size="13" stroke="2.4" /></span>
        {{ session('error') }}
    </div>
@endif
