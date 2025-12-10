@extends('layout')

@section('content')
<h1>NoorMal — Faraid Calculation Result</h1>

<p><strong>Total estate:</strong> RM {{ number_format($result['estate'],2) }}</p>

<h2>Fixed shares</h2>
@if(count($result['fixed']) === 0)
    <p>No fixed shares</p>
@endif

<ul>
@foreach($result['fixed'] as $rel => $info)
    <li>
        <strong>{{ ucfirst($rel) }}</strong> —
        total: RM {{ number_format($info['total'],2) }}
        @if($info['count'] > 1)
            (each: RM {{ number_format($info['per_person'],2) }} × {{ $info['count'] }} persons)
        @else
            (per person: RM {{ number_format($info['per_person'],2) }})
        @endif
        — fraction: {{ $info['fraction'] ?? 'n/a' }}
    </li>
@endforeach
</ul>

<h2>Residuary allocation</h2>
@if(count($result['residue_allocated']) === 0)
    <p>No residuary allocation</p>
@endif

<ul>
@foreach($result['residue_allocated'] as $rel => $info)
    <li>
        <strong>{{ ucfirst($rel) }}</strong> —
        total: RM {{ number_format($info['total'],2) }}
        (each: RM {{ number_format($info['per_person'],2) }} × {{ $info['count'] }} persons)
    </li>
@endforeach
</ul>

@if($result['unallocated'] > 0)
    <h3>Unallocated (leftover): RM {{ number_format($result['unallocated'],2) }}</h3>
@endif

@endsection
