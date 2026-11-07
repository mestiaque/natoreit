@extends('nit::master')

@section('title', $message->type !== 'newsletter' ? __('Message from :name', ['name' => $message->name]) : __('Newsletter sign-up'))

@include('nit::elements.page-ui')

@push('buttons')
    <a href="{{ $back }}" class="btn btn-sm btn-light fw-bold"><i class="fas fa-arrow-left" aria-hidden="true"></i> {{ __('Back') }}</a>
@endpush

@section('content')
<div class="nx-page">
    <x-nit::card :title="$message->type !== 'newsletter' ? (\ME\Nit\Models\Message::SUBJECTS[$message->subject] ?? $message->subject) : __('Newsletter sign-up')"
                 :subtitle="$message->created_at?->format('d M Y, h:i A') . ' · ' . $message->created_at?->diffForHumans()">
        <x-slot:actions>
            <a href="mailto:{{ $message->email }}?subject={{ rawurlencode('Re: ' . ($message->type !== 'newsletter' ? 'your message to Natore IT' : 'Natore IT')) }}" class="nx-btn nx-btn-primary"><i class="fas fa-reply" aria-hidden="true"></i> {{ __('Reply by e-mail') }}</a>
            @if($message->phone)
                <a href="https://wa.me/{{ preg_replace('/\D/', '', preg_replace('/^0/', '880', $message->phone)) }}" target="_blank" rel="noopener" class="nx-btn nx-btn-light"><i class="fab fa-whatsapp" aria-hidden="true"></i> WhatsApp</a>
            @endif
            @can('site_message_delete')
                <x-nit::delete-button :action="route('nit.admin.messages.destroy', $message->id)" :name="$message->activityLabel()" :label="__('Delete')" />
            @endcan
        </x-slot:actions>

        <dl class="row mb-0">
            @if($message->name)<dt class="col-sm-3">{{ __('Name') }}</dt><dd class="col-sm-9">{{ $message->name }}</dd>@endif
            <dt class="col-sm-3">{{ __('Email') }}</dt><dd class="col-sm-9"><a href="mailto:{{ $message->email }}">{{ $message->email }}</a></dd>
            @if($message->phone)<dt class="col-sm-3">{{ __('Mobile') }}</dt><dd class="col-sm-9"><a href="tel:{{ $message->phone }}">{{ $message->phone }}</a></dd>@endif
            @if($message->message)
                <dt class="col-sm-3">{{ __('Message') }}</dt>
                <dd class="col-sm-9" style="white-space: pre-line">{{ $message->message }}</dd>
            @endif
            <dt class="col-sm-3 text-muted fw-normal">{{ __('Sent from') }}</dt><dd class="col-sm-9 text-muted small">{{ $message->ip }} · {{ \Illuminate\Support\Str::limit($message->user_agent, 90) }}</dd>
        </dl>
    </x-nit::card>
</div>
@endsection
