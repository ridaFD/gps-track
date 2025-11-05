📐 ستارة #{{ $index }}

📏 العرض: {{ $blind->width_m }} M
📏 الارتفاع: {{ $blind->height_m }} M
🔢 الكمية: {{ $blind->qty }}
@if($blind->note)
📝 ملاحظة: {{ $blind->note }}
@endif
