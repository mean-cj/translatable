{{-- CSS Styling --}}
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha256-3dkvEK0WLHRJ7/Csr0BZjAWxERc5WH7bdeUya2aXxdU= sha512-+L4yy6FRcDGbXJ9mPG8MT/3UCDzwR9gPeyFNMCtInsol++5m3bk2bXWKdZjvybmohrAsn3Ua5x8gfLnbE1YkOg==" crossorigin="anonymous">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/s/bs/jqc-1.11.3,dt-1.10.10,r-2.0.0/datatables.min.css"/>

{{-- Display Table --}}
@if (session('status'))
  <div class="alert alert-success">
    {{ session('status') }}
  </div>
@endif

@if (session('danger'))
  <div class="alert alert-danger">
    {{ session('danger') }}
  </div>
@endif

<table id="languages" class="table table-bordered table-striped table-hover">
  <thead>
    <tr>
      <th>Language Name</th>
      <th>ISO 639-1</th>
      <th class="text-center">Active</th>
      <th class="text-center">Default</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
    @foreach($languages as $lang)
      <tr>
        <td>{{ $lang->name }}</td>
        <td>{{ $lang->abbr }}</td>
        <td class="text-center">
          @if($lang->active)
            <a href="{{ route('languages/status', ['lang' => $lang->abbr, 'status' => 'disable']) }}"><i class="fa fa-check text-success"></i></a><span class="hidden">1</span>
          @else
            <a href="{{ route('languages/status', ['lang' => $lang->abbr, 'status' => 'active']) }}"><i class="fa fa-times text-muted"></i></a><span class="hidden">0</span>
          @endif
        </td>
        <td class="text-center">
          @if($lang->default)
            <i class="fa fa-check text-success"></i><span class="hidden">1</span>
          @else
            <a href="{{ route('languages/status', ['lang' => $lang->abbr, 'status' => 'defaulted']) }}"><i class="fa fa-times text-muted"></i></a><span class="hidden">0</span>
          @endif
        </td>
        <td>
          <div data-toggle="modal" data-target="#settings-{{$lang->abbr}}" class="btn btn-default btn-sm"><i class="fa fa-gears"></i> Edit Settings</div>
          <a href="{{ route('languages/view', ['lang' => $lang->abbr]) }}" class="btn btn-default btn-sm"><i class="fa fa-edit"></i> Edit Text</a>
          <div data-toggle="modal" data-target="#destroy-{{$lang->abbr}}" class="btn btn-default btn-sm"><i class="fa fa-gears"></i> Delete</div>
        </td>
      </tr>

      <div class="modal fade" id="settings-{{$lang->abbr}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title" id="myModalLabel">Editing {{ $lang->name }}</h4>
            </div>
            <div class="modal-body">
              <form method="POST">
                {{ csrf_field() }}
                <input type="hidden" value="{{ $lang->id }}" id="id" name="id" />
                <div class="form-group">
                  <label>Language Name</label>
                  <input type="string" class="form-control" id="name" name="name" value="{{ $lang->name }}" placeholder="Pirate">
                </div>
                <div class="form-group">
                  <label>Native Name</label>
                  <input type="string" class="form-control" id="native" name="native" value="{{ $lang->native }}" placeholder="Piaaarate">
                </div>
                <div class="form-group">
                  <label>Abbreviation</label>
                  <input type="string" class="form-control" id="abbr" name="abbr" value="{{ $lang->abbr }}" placeholder="ARR">
                </div>
                <div class="form-group">
                  <label><input type="checkbox" value="true" name="active" id="active" @if($lang->active) checked @endif> Active</label>
                </div>
                <div class="form-group">
                  <label><input type="checkbox" value="true" name="default" id="default" @if($lang->default) checked @endif> Default</label>
                </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
              <input type="submit" class="btn btn-primary" value="Save Changes">
              </form>
            </div>
          </div>
        </div>
      </div>

      <div class="modal fade" id="destroy-{{$lang->abbr}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header" style="background: #E95353;border-radius: 5px 5px 0 0; color: #fff;">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title" id="myModalLabel">Confirm {{ $lang->name }} Language Deletion</h4>
            </div>
            <div class="modal-body">
              <p class="lead">Are you sure that you would like to delete {{ $lang->name }}. All database information and files on the server will be completely removed.</p>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
              <a href="{{ route('languages/destroy', ['lang' => $lang->abbr]) }}" class="btn btn-danger">Confirm Deletion</a>
            </div>
          </div>
        </div>
      </div>
    @endforeach
  </tbody>
  <tfoot>
    <tr>
      <th>Language Name</th>
      <th>ISO 639-1</th>
      <th class="text-center">Active</th>
      <th class="text-center">Default</th>
      <th>Actions</th>
    </tr>
  </tfoot>
</table>


<a class="btn btn-default" href="{{ route('languages/load') }}"><i class="fa fa-language"></i> Load New Languages</a>

{{-- Javascript --}}
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
<script type="text/javascript" src="https://cdn.datatables.net/s/bs/jqc-1.11.3,dt-1.10.10,r-2.0.0/datatables.min.js"></script>
<script type="text/javascript" charset="utf-8">
  $(document).ready(function() {
    $('#languages').DataTable({
      "paging": true,
      "order": [[ 2, 'desc' ]],
      "columnDefs": [
        { "orderable": false, "targets": 4 }
      ]
    });
  });
</script>