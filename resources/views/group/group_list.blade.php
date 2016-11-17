@extends('layouts.app')
@section('content')
<div class="container-fluid" style="margin-top:50px;">
	<div class="col-lg-9 col-md-10">
	<span class="title_text">{{ $title->name }}</span><hr>
	<div class="form_group">
	新增群組 : <a href="{{ action('GroupController@manage') }}" class="btn btn-labeled btn-primary"><span class="btn-label"><i class="fa fa-plus"></i></span> 新增</a>
	</div>
		<table class="table table-striped table-bordered table-list table-hover" style="text-align: left;">
			<thead><tr>
			<th alight="center"><em class="fa fa-cog"></em></th>
			<th>群組名稱</th>
			<th>建立時間</th>
			<th>更新時間</th>
			</tr>
			</thead>

			@foreach($groups as $group)
			<tr>
				<td align="center">
					@if(master(['GroupController','manage',$userPermissions]))
                    <a class="btn btn-default" href="{{ action('GroupController@manage' , ['id' => $group->id]) }}"><em class="fa fa-pencil"></em></a>
                    @endif
                    &nbsp;&nbsp;
                    @if(master(['GroupController','destroy',$userPermissions]))
                    <a href="{{ action('GroupController@destroy' , ['id' => $group->id]) }}" class="btn btn-danger"><em class="fa fa-trash"></em></a>
                    @endif
                </td>
				<td>{{ $group->name }}</td>
				<td>{{ $group->created_at }}</td>
				<td>
				@if ($group->updated_at == null)
				無更新紀錄
				@else
				{{ $group->updated_at }}
				@endif
				</td>
			</tr>
			@endforeach
		</table>
	</div>
</div>
@stop
