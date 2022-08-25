@if($status == 'paid')
    <span class="badge badge-success badge-pill">{{ __($status) }}</span>
@else
    <span class="badge badge-warning badge-pill">{{ __($status) }}</span>
@endif  