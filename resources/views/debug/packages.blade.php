<!DOCTYPE html>
<html>
<head>
    <title>Package Debug</title>
    <style>
        body { font-family: Arial; margin: 20px; }
        .package { border: 1px solid #ccc; margin: 10px; padding: 20px; }
        img { max-width: 200px; height: auto; }
    </style>
</head>
<body>
    <h1>Package Debug Page</h1>
    
    <p>Total packages found: {{ count($packages) }}</p>
    
    @foreach($packages as $package)
        <div class="package">
            <h3>{{ $package->name }}</h3>
            <p>ID: {{ $package->id }}</p>
            <p>Active: {{ $package->active ? 'Yes' : 'No' }}</p>
            <p>Min: ${{ number_format($package->min_amount) }}</p>
            <p>Max: ${{ number_format($package->max_amount) }}</p>
            
            @php
                $imageName = '';
                if(str_contains(strtolower($package->name), 'capital')) {
                    $imageName = 'Capital.png';
                } elseif(str_contains(strtolower($package->name), 'energy')) {
                    $imageName = 'Energy.png';
                } elseif(str_contains(strtolower($package->name), 'growth')) {
                    $imageName = 'Growth.png';
                } else {
                    $imageName = 'Capital.png'; // Default fallback
                }
            @endphp
            
            <p>Image name: {{ $imageName }}</p>
            <p>Image URL: {{ asset($imageName) }}</p>
            <p><strong>JavaScript call:</strong> openPaymentForm({{ $package->id }}, '{{ $package->name }}', {{ $package->min_amount }}, {{ $package->max_amount }}, {{ $package->daily_shares_rate }})</p>
            <img src="{{ asset($imageName) }}" alt="{{ $package->name }}" onerror="this.style.border='2px solid red';">
            
            <button onclick="openPaymentForm({{ $package->id }}, '{{ $package->name }}', {{ $package->min_amount }}, {{ $package->max_amount }}, {{ $package->daily_shares_rate }})" 
                    style="background: blue; color: white; padding: 10px; margin: 10px;">
                Test Click for {{ $package->name }}
            </button>
    @endforeach
</body>
</html>
