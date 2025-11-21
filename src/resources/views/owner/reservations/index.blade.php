@extends('layouts.app')
@section('title','予約一覧')
@section('content')
<div class="container" style="max-width:1080px;margin:24px auto;padding:0 16px;">

  <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;">
    <h1 style="font-size:20px;font-weight:600;margin:0;">予約一覧</h1>
    <a href="{{ route('owner.dashboard') }}" style="text-decoration:none;display:inline-block;padding:8px 12px;border:1px solid #e5e7eb;border-radius:6px;">店舗代表者ページへ戻る</a>
  </div>

  @if(session('success'))
    <div style="background:#ECFDF5;color:#065F46;border:1px solid #A7F3D0;border-radius:6px;padding:10px 12px;margin-bottom:16px;">
      {{ session('success') }}
    </div>
  @endif

  @if($reservations->isEmpty())
    <p style="color:#555;">予約はありません。</p>
  @else
    <div style="overflow-x:auto;background:#fff;border:1px solid #e5e7eb;border-radius:8px;">
      <table style="width:100%;border-collapse:separate;border-spacing:0;">
        <thead>
          <tr style="background:#F9FAFB;">
            <th style="text-align:left;padding:12px 14px;border-bottom:1px solid #e5e7eb;white-space:nowrap;">店舗名</th>
            <th style="text-align:left;padding:12px 14px;border-bottom:1px solid #e5e7eb;white-space:nowrap;">予約日時</th>
            <th style="text-align:right;padding:12px 14px;border-bottom:1px solid #e5e7eb;white-space:nowrap;">人数</th>
            <th style="text-align:left;padding:12px 14px;border-bottom:1px solid #e5e7eb;white-space:nowrap;">予約者</th>
          </tr>
        </thead>
        <tbody>
          @foreach($reservations as $r)
            <tr>
              <td style="padding:12px 14px;border-bottom:1px solid #f3f4f6;white-space:nowrap;">{{ $r->shop?->name }}</td>
              <td style="padding:12px 14px;border-bottom:1px solid #f3f4f6;white-space:nowrap;">{{ optional($r->reserved_at)->format('Y/m/d H:i') }}</td>
              <td style="padding:12px 14px;border-bottom:1px solid #f3f4f6;text-align:right;white-space:nowrap;">{{ number_format((int)($r->num_of_guests ?? 0)) }}</td>
              <td style="padding:12px 14px;border-bottom:1px solid #f3f4f6;white-space:nowrap;">{{ $r->user?->name }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    <div style="margin-top:16px;">
      {{ $reservations->links() }}
    </div>
  @endif
</div>
@endsection
