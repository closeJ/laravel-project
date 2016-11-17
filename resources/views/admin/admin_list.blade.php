@extends('layouts.app')
@section('content')
<div class="container-fluid" style="margin-top:50px;">
	<div class="col-lg-9 col-md-10">
	<span class = 'title_text'>{{ $title->name }}</span><hr>
	<div class="form_group">
	新增人員 : <button id = "admin_add" class="btn btn-labeled btn-primary" type="button" data-toggle="modal" data-target="#add" data-id = ""><span class="btn-label"><i class="fa fa-plus"></i></span> 新增</button><p style="padding:10px;"></p>
	新增警訊條件設定功能 : <button id = "system_set" class="btn btn-labeled btn-success" type="button" data-toggle="modal" data-target="#set"><span class="btn-label"><i class="fa fa-wrench" aria-hidden="true"></i></span> 設定</button>
	</div>
	<!-- 彈出式視窗區 start-->
	<div class="modal fade" id="add" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <span class="modal-title" style="font-weight:bold;font-size:18pt;">新增管理人員</span>
        </div>
        <div class="modal-body">
      		<form>
            <div class="form-group" style="vertical-align: middle;">
              <label for="admin_name" class="form-control-label">選擇人員 : </label>
              {{ Form::select('admin_name', $selectUser) }}
            </div>
            <div class="form-group" style="vertical-align: middle;">
            	<ul>
            	@foreach($roles as $role)
	            	<li><span> {{ $role->name }} {{ Form::checkbox('roles[]', $role->id ,(in_array($role->id,$user_role['userRoles'])) ? true : false,['class' => 'users_role']) }}
	            		</span>
	            	</li>
            	@endforeach
            	</ul>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <div class="alert alert-danger" style="text-align:left;">!! 注意 ，若選擇已有群組的人員則將會取代原本的設定 !!</div>
          <button type="button" class="btn btn-primary" id="insert_admin">送出</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">關閉</button>
        </div>
      </div>
    </div>
  </div>
  <!-- 彈出式視窗區 end -->
  <!-- 彈出式視窗區 start-->
	<div class="modal fade" id="set" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <span class="modal-title" style="font-weight:bold;font-size:18pt;">警訊條件設定</span>
        </div>
        <div class="modal-body">
      		<form>
            <div class="form-group" style="vertical-align: middle;">
            <label for="set_text" class="form-control-label">
            單一平台 1 小時內，BUY IN不重覆帳號數超過 {{ Form::text('limit',null,["class" => 'limit']) }}人，<br><p style="padding-left:30px;">發送警示信件至營運、總代、代理的信箱 。</p>
            </label>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" id="setting">送出</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">關閉</button>
        </div>
      </div>
    </div>
  </div>
  <!-- 彈出式視窗區 end -->
		<table class="table table-striped table-bordered table-list table-hover">
			<thead><tr>
			<th>帳號</th>
			<th>暱稱</th>
			<th>群組名稱</th>
			</tr>
			</thead>
			@if(count($users) > 0)
			@foreach($users as $user)
			<tr>
				<td>{{ $user->username }}</td>
				<td>{{ $user->name }}</td>
				<td>
					@if (count($user->roles) > 0)
					@foreach ($user->roles as $role)
					{{ $role->name . '  ' }}
					@endforeach
					@else
					無任何群組
					@endif
				</td>
			</tr>
			@endforeach
			@else
			<tr>
				<td colspan="3" align="center" style="font-weight:bold;font-size:14pt;">查無資料</td>
			</tr>
			@endif
		</table>
		<div class="page">
		{{ $users->render() }}
		</div>
	</div>
</div>
@stop
