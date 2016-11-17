@extends('layouts.app')
@section('content')
<div class="container-fluid" id="page-content-wrapper">
	<div class="row">
		<div class="col-lg-9 col-md-10">
			<span class="title_text">{{ ($groupPermissions['id'] == '') ? '新增' : '編輯' }}</span><hr>
			<div class="group-permission">
				{{ Form::open(['action' => 'GroupController@save' , 'id' => 'modify_form','method' => 'post']) }}
				<fieldset>
				<div class="form-inline">
				  <div class="form-group">
				    <span> 群組名稱：</span>
				  {{ Form::text('name', set_default($groupPermissions['name']),['class' => 'form-control']) }}
				  </div>
				<div class="form-group" style="margin-left:14%;">
	        		{{ Form::hidden('prev_url', session('prev_url')) }}
					{{ Form::hidden('id', $groupPermissions['id']) }}
					<button id="submit" name="submit" class="btn btn-labeled btn-success">
                	<span class="btn-label"><i class="glyphicon glyphicon-ok"></i></span>確認送出</button>
				    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				     <button type="button" class="btn btn-labeled btn-danger" id = "cancel">
                	<span class="btn-label"><i class="glyphicon glyphicon-remove"></i></span>取消</button>
				</div>
				</div>
				<div class="tree">
				權限圖表
				<p style="padding-left:55px;display:inline-block;font-weight:bold;">
				{{ Form::checkbox('checkAll',null,null,['id' => 'checkAll']) }} 全選</p>
				<ul class="">
		        @foreach ($menuTree['modules'] as $module)
		            <li>
		            	<span style="background-color:#0888c1;color:white;">{{ $module['name'] }}</span>{{ Form::checkbox('permissions[]', $module['id'],(in_array($module['id'], $groupPermissions['groupPermissions']))?true:false) }}
			            @if (isset($menuTree['mainMenu'][$module['id']]))
			            <ul>
			                @foreach ($menuTree['mainMenu'][$module['id']] as $function)
			                <li class>
			                	<span style="background-color:#e8b20e;color:white;">{{ $function['name'] }} </span> {{ Form::checkbox('permissions[]', $function['id'], (in_array($function['id'], $groupPermissions['groupPermissions']))?true:false) }}
								@if (isset($menuTree['subMenu'][$function['id']]))
					            <ul>
					                @foreach ($menuTree['subMenu'][$function['id']] as $subFunction)
					                <li>
					                	<span style="background-color:#F44336;color:white;">{{ $subFunction['name'] }} </span> {{ Form::checkbox('permissions[]', $subFunction['id'], (in_array($subFunction['id'], $groupPermissions['groupPermissions']))?true:false) }}
					                </li>
					                @endforeach
					            </ul>
						         @endif
			                </li>
			                @endforeach
			            </ul>
			            @endif
		            </li>
		        @endforeach
	        	</ul>
	        	</div>
				</fieldset>
	        	{{ Form::close() }}
        	</div>
		</div>
    </div>
</div>
@stop