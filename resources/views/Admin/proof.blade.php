@extends('../layout')


@section('contents')
    {{-- <proof-page></proof-page> --}}
    <img src="{{ url('storage/proofs/'.$proof->proof_path) }}" alt="" title=""/>
@endsection