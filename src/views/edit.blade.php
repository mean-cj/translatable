{{-- CSS Styling --}}
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha256-3dkvEK0WLHRJ7/Csr0BZjAWxERc5WH7bdeUya2aXxdU= sha512-+L4yy6FRcDGbXJ9mPG8MT/3UCDzwR9gPeyFNMCtInsol++5m3bk2bXWKdZjvybmohrAsn3Ua5x8gfLnbE1YkOg==" crossorigin="anonymous">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/s/bs/jqc-1.11.3,dt-1.10.10,r-2.0.0/datatables.min.css"/>
<style style="text/css">
.panel.with-nav-tabs .panel-heading{
    padding: 5px 5px 0 5px;
}
.panel.with-nav-tabs .nav-tabs{
  border-bottom: none;
}
.panel.with-nav-tabs .nav-justified{
  margin-bottom: -1px;
}

.panel-body { padding: 0; margin-top: 15px; }

.with-nav-tabs.panel-default .nav-tabs > li > a,
.with-nav-tabs.panel-default .nav-tabs > li > a:hover,
.with-nav-tabs.panel-default .nav-tabs > li > a:focus {
    color: #777;
}
.with-nav-tabs.panel-default .nav-tabs > .open > a,
.with-nav-tabs.panel-default .nav-tabs > .open > a:hover,
.with-nav-tabs.panel-default .nav-tabs > .open > a:focus,
.with-nav-tabs.panel-default .nav-tabs > li > a:hover,
.with-nav-tabs.panel-default .nav-tabs > li > a:focus {
    color: #777;
  background-color: #ddd;
  border-color: transparent;
}
.with-nav-tabs.panel-default .nav-tabs > li.active > a,
.with-nav-tabs.panel-default .nav-tabs > li.active > a:hover,
.with-nav-tabs.panel-default .nav-tabs > li.active > a:focus {
  color: #555;
  background-color: #fff;
  border-color: #ddd;
  border-bottom-color: transparent;
}
.with-nav-tabs.panel-default .nav-tabs > li.dropdown .dropdown-menu {
    background-color: #f5f5f5;
    border-color: #ddd;
}
.with-nav-tabs.panel-default .nav-tabs > li.dropdown .dropdown-menu > li > a {
    color: #777;   
}
.with-nav-tabs.panel-default .nav-tabs > li.dropdown .dropdown-menu > li > a:hover,
.with-nav-tabs.panel-default .nav-tabs > li.dropdown .dropdown-menu > li > a:focus {
    background-color: #ddd;
}
.with-nav-tabs.panel-default .nav-tabs > li.dropdown .dropdown-menu > .active > a,
.with-nav-tabs.panel-default .nav-tabs > li.dropdown .dropdown-menu > .active > a:hover,
.with-nav-tabs.panel-default .nav-tabs > li.dropdown .dropdown-menu > .active > a:focus {
    color: #fff;
    background-color: #555;
}
</style>

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

{{-- Display Form For Editing--}}
<div class="panel with-nav-tabs panel-default">
  <div class="panel-heading">
    <ul class="nav nav-tabs">
      @foreach($langFiles as $file)
        <li @if($file->active) class="active" @endif><a href="{{ $file->url }}" data-toggle="tab">{{ $file->name }}</a></li>
      @endforeach
    </ul>
  </div>
  <div class="panel-body">
    <div class="tab-pane active">
      <table class="table">
        <thead>
          <tr>
            <th style="border-bottom: 0;" class="col-lg-2 text-right">Key</th>
            <th style="border-bottom: 0;" class="col-lg-5">Default Language ({{$defaultLang['name']}}) Text</th>
            <th style="border-bottom: 0;" class="col-lg-5">{{ $currentLang['name'] }} Translation</th>
          </tr>
        </thead>
        <tbody>
          <form method="POST" action="{{ route('languages/view', ['lang' => $currentLang['abbr'], 'file' => $currentFile]) }}">
          {{ csrf_field() }}
          @foreach($fileArray as $key => $translation)
            <tr>
              <td class="text-right">{{ $key }}</td>
              <td><input type="text" value="{{ $defaultArray[$key] }}" class="form-control" disabled></td>
              <td><input type="text" value="{{ $translation }}" name="{{$key}}" id="{{$key}}" class="form-control"></td>
            </tr>
          @endforeach
          <tr>
            <td></td>
            <td colspan="2">
              <input type="submit" value="Save Translations" class="btn btn-block btn-info">
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>