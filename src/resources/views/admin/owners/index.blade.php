@extends('layouts.app')
@section('title','店舗代表者一覧')
@section('content')
<div class="container" style="max-width:1000px;margin:24px auto;">
  <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;">
    <h1 style="font-size:20px;">店舗代表者一覧</h1>
    <a href="{{ route('admin.owners.create') }}" class="btn btn-primary">新規作成</a>
  </div>

  @if(session('success'))
    <div style="background:#eef7ee;border:1px solid #b7e1b7;color:#135d13;padding:10px;border-radius:6px;margin-bottom:12px;">{{ session('success') }}</div>
  @endif

  <div style="overflow:auto;">
    <table style="width:100%;border-collapse:collapse;">
      <thead>
        <tr>
          <th style="border-bottom:1px solid #ddd;padding:8px;text-align:left;">ID</th>
          <th style="border-bottom:1px solid #ddd;padding:8px;text-align:left;">氏名</th>
          <th style="border-bottom:1px solid #ddd;padding:8px;text-align:left;">メール</th>
          <th style="border-bottom:1px solid #ddd;padding:8px;text-align:left;">電話</th>
          <th style="border-bottom:1px solid #ddd;padding:8px;text-align:left;">作成日</th>
          <th style="border-bottom:1px solid #ddd;padding:8px;">操作</th>
        </tr>
      </thead>
      <tbody>
        @forelse($owners as $o)
          <tr>
            <td style="border-bottom:1px solid #eee;padding:8px;">{{ $o->id }}</td>
            <td style="border-bottom:1px solid #eee;padding:8px;">{{ $o->name }}</td>
            <td style="border-bottom:1px solid #eee;padding:8px;">{{ $o->email }}</td>
            <td style="border-bottom:1px solid #eee;padding:8px;">{{ $o->phone }}</td>
            <td style="border-bottom:1px solid #eee;padding:8px;">{{ $o->created_at }}</td>
            <td style="border-bottom:1px solid #eee;padding:8px;text-align:center;white-space:nowrap;">
              <a href="{{ route('admin.owners.edit',$o) }}" class="btn">編集</a>
              <form method="POST" action="{{ route('admin.owners.destroy',$o) }}" style="display:inline-block" onsubmit="return confirm('削除しますか？');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn" style="color:#c00;border-color:#c00;">削除</button>
              </form>
            </td>
          </tr>
        @empty
          <tr><td colspan="6" style="padding:16px;text-align:center;color:#666;">店舗代表者はまだいません</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div style="margin-top:12px;">{{ $owners->links() }}</div>
</div>
@endsection
