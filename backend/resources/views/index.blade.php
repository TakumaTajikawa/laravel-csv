@extends('layouts.app')

@section('content')
<table class="table">
    <thead>
        <tr>
            <th scope="col">ID</th>
            <th scope="col">建築物条件</th>
            <th scope="col">建築物デザイン</th>
            <th scope="col">名前</th>
            <th scope="col">住所</th>
            <th scope="col">電話番号</th>
            <th scope="col">Fax</th>
        </tr>
    </thead>
    <tbody class="table-stripes-row-tbody">
        @foreach($contacts as $c)
            <tr>
                <td>{{ $c->id }}</td>
                <td>{{ $c->condition_name }}</td>
                <td>{{ $c->design_name }}</td>
                <td>{{ $c->surname }}{{ $c->name }}</td>
                <td>{{ $c->zipcode }}<br>{{ $c->pref }}{{ $c->city }}{{ $c->street }}</td>
                <td>{{ $c->tel_number }}</td>
                <td>{{ $c->fax_number }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
@endsection
