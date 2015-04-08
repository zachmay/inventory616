 <html lang=eng>
 <head>
 <title>First View</title>
 </head>
 <body>
 
 {!!Form::open(['url' => 'api/inventory/{tag}/post_history']) !!}
 <table style=width:25%>
 <tr>
 <div class="form-group">
  <td>{!! Form::label('room_id_label','Room ID:') !!}	</td>
  <td>{!! Form::text('room_id', null,[ 'class' => 'form-control']) !!}</td>
  </div>
  </tr>
  <tr>
 <div class="form-group">
  <td>{!! Form::label('asset_tag_label','Asset Tag:') !!}	</td>
  <td>{!! Form::text('asset_tag', null,[ 'class' => 'form-control']) !!}</td>
  </div>
  </tr>
  <tr>
  <tr>
 <div class="form-group">
  <td>{!! Form::label('item_type_label','Item Type:') !!}	</td>
  <td>{!! Form::text('item_type', null,[ 'class' => 'form-control']) !!}</td>
  </div>
  </tr>
  <tr>
  <div class="form-group">
  <td>{!! Form::label('item_name_label',"Item Name:") !!}	</td>
  <td>{!! Form::text('item_name',null,[ 'class' => "form-control"]) !!}</td>
  </div>
  </tr>
  <tr>
  <div class="form-group">
  <td>{!! Form::label('funding_source_label',"Fund Source:") !!}	</td>
  <td>{!! Form::text('funding_source',null,[ 'class' => "form-control"]) !!}</td>
  </div>
  </tr>
  <tr>
  <div class="form-group">
  <td>{!! Form::label('model_label',"Model:") !!}	</td>
  <td>{!! Form::text('model',null,[ 'class' => "form-control"]) !!}</td>
  </div>
  </tr>
  <tr>
  <div class="form-group">
  <td>{!! Form::label('cpu_label',"CPU:") !!}	</td>
  <td>{!! Form::text('cpu',null,[ 'class' => "form-control"]) !!}</td>
  </div>
  </tr>
  <tr>
  <div class="form-group">
  <td>{!! Form::label('ram_label',"RAM:") !!}	</td>
  <td>{!! Form::text('ram',null,[ 'class' => "form-control"]) !!}</td>
  </div>
  </tr>
  <tr>
  <div class="form-group">
  <td>{!! Form::label('hard_disk_label',"Hard Disk:") !!}	</td>
  <td>{!! Form::text('hard_disk',null,[ 'class' => "form-control"]) !!}</td>
  </div>
  </tr>
  <tr>
  <div class="form-group">
  <td>{!! Form::label('os_label',"OS:") !!}	</td>
  <td>{!! Form::text('os',null,[ 'class' => "form-control"]) !!}</td>
  </div>
  </tr>
  <tr>
  <div class="form-group">
  <td>{!! Form::label('administrator_flag_label',"Administrator Flag:") !!}	</td>
  <td>{!! Form::text('administrator_flag',null,[ 'class' => "form-control"]) !!}</td>
  </div>
  </tr>
  <tr>
  <div class="form-group">
  <td>{!! Form::label('teacher_flag_label',"Teacher Flag:") !!}	</td>
  <td>{!! Form::text('teacher_flag',null,[ 'class' => "form-control"]) !!}</td>
  </div>
  </tr>
  <tr>
  <div class="form-group">
  <td>{!! Form::label('student_flag_label',"Student Flag:") !!}	</td>
  <td>{!! Form::text('student_flag',null,[ 'class' => "form-control"]) !!}</td>
  </div>
  </tr>
  <tr>
  <div class="form-group">
  <td>{!! Form::label('institution_flag_label',"Institution Flag:") !!}	</td>
  <td>{!! Form::text('institution_flag',null,[ 'class' => "form-control"]) !!}</td>
  </div>
  </tr>
  <tr>
  <div class="form-group">
  <td align=center >{!! Form::submit("Add Information",[ 'class' => "btn btn-primary form-control"]) !!}</td>
  </div>
  </tr>
 </table>
 {!! Form::close() !!}
 
</body>
</html>