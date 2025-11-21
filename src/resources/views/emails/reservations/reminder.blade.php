<p>{{ $reservation->user->name }} 様</p>

<p>本日 {{ $reservation->date->format('Y年m月d日') }} のご予約内容です。</p>

<ul>
    <li>店舗：{{ $reservation->shop->name }}</li>
    <li>日時：{{ $reservation->date->format('Y-m-d') }} {{ $reservation->time }}</li>
    <li>人数：{{ $reservation->num_of_guests ?? 1 }}名</li>
</ul>

<p>ご来店お待ちしております。</p>
