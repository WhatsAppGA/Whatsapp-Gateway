

<div class="alert border-0 bg-light-{{$type}} alert-dismissible fade show py-2">
    <div class="d-flex align-items-center">
        <div class="fs-3 text-{{$type}}">
          @if ($type == 'success')
            <i class="bi bi-check-circle-fill"></i>
          @elseif ($type == 'danger')
            <i class="bi bi-exclamation-circle-fill"></i>
          @elseif ($type == 'warning')
            <i class="bi bi-exclamation-triangle-fill"></i>
          @endif
        </div>
        <div class="ms-3">
            <div class="text-{{$type}}">{{ $msg }}</div>
        </div>
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
